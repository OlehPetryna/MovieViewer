<?php

namespace app\base;


use app\components\DbQuery;

abstract class Model
{
    public function __construct(array $attributes = [])
    {
        if(!empty($attributes)){
            foreach ($attributes as $key => $val) {
                if(is_array($val))
                    $this->$key = array_map(function($v){
                        return trim(htmlspecialchars($v));
                    }, $val);
                else
                    $this->$key = trim(htmlspecialchars_decode($val));

            }
        }

    }

    protected abstract function validate();

    public function save()
    {
        $query = new DbQuery(App::$app->dbConnection["class"]->connection);
        return $query->saveModel($this);
    }

    public function update()
    {
        $query = new DbQuery(App::$app->dbConnection["class"]->connection);
        return $query->updateModel($this);
    }

    public function delete()
    {
        $query = new DbQuery(App::$app->dbConnection["class"]->connection);
        return $query->deleteModel($this);
    }

    public function getAttributes(bool $all = true)
    {
        $attrs = get_object_vars($this);
        if(!$all)
            unset($attrs["id"]);
        return $attrs;
    }

    public static function find(array $condition = [])
    {
        $query = new DbQuery(App::$app->dbConnection["class"]->connection);
        $tableName = call_user_func(get_called_class()."::getTableName");

        return $query->select($condition, $tableName);
    }

}