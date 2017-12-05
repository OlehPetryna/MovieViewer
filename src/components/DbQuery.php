<?php

namespace app\components;


use app\base\App;
use app\base\Connection;
use app\base\Model;

class DbQuery
{
    /**
     * @var $connection \PDO
     */
    private $connection;


    public function __construct(\PDO $connection)
    {
        $this->connection = $connection;
    }

    public function deleteModel(Model $model)
    {
        $attrs = $model->getAttributes();
        $sql = "DELETE FROM {$model::getTableName()} WHERE id=:id";

        $sql = $this->connection->prepare($sql);
        $res = $sql->execute(["id" => $attrs["id"]]);

        if(!$res){
            throw new \Exception($this->connection->errorInfo(),500);
        }

        return $res;
    }

    public function saveModel(Model $model)
    {
        $attrs = $model->getAttributes(false);
        $sql = "INSERT INTO {$model::getTableName()} (".implode(',', array_keys($attrs)).") VALUES( ";

        foreach ($attrs as $key => $attr){
            $sql.= ":$key, ";
            if(is_array($attr)){
                $attrs[$key] = json_encode($attr, JSON_UNESCAPED_UNICODE);
            }
        }

        $sql = substr($sql,0, strlen($sql) - 2);
        $sql.=" )";

        $sql = $this->connection->prepare($sql);

        $res = $sql->execute($attrs);

        if(!$res){
           throw new \Exception($this->connection->errorInfo(),500);
        }

        return $res;
    }

    public function updateModel(Model $model)
    {
        $attrs = $model->getAttributes(false);
        $sql = "UPDATE {$model::getTableName()} SET ";

        foreach ($attrs as $key => $attr){
            $sql.= "$key =:$key, ";
            if(is_array($attr))
                $attrs[$key] = json_encode($attr, JSON_UNESCAPED_UNICODE);
        }

        $sql = substr($sql,0, strlen($sql) - 2);
        $sql.= " WHERE id={$model->id}";

        $sql = $this->connection->prepare($sql);

        $res = $sql->execute($attrs);

        if(!$res){
            throw new \Exception($this->connection->errorInfo(),500);
        }

        return $res;
    }

    public function select(array $condition = [], string $tableName)
    {
        $models = [];
        $wheres = "";

        foreach ($condition as $key => $val){
            if(is_array($val)){
                try{
                    $operator = $val[0];
                    $col = $val[1];
                    $val = $val[2];
                }catch (\Exception $e){
                    throw new \Exception("condition array should contain 3 vals - operator, columnName and value",500);
                }

                $wheres .= empty($wheres) ? "WHERE ($col $operator $val)" : " OR ($col $operator $val)";
            }else{
                $wheres .= empty($wheres) ? "WHERE ($key = $val)" : " OR ($key = $val)";
            }
        }

        $sql = "SELECT * FROM $tableName ". (empty($wheres) ? "" : $wheres);
        $sql.=" ORDER BY name ASC";
        $sql = $this->connection->prepare(trim($sql));

        $sql->execute();
        $modelName = "app\\model\\".$tableName."Model";
        foreach ($sql->fetchAll(\PDO::FETCH_ASSOC) as $key => $val) {
            $model = new $modelName($val);
            $models[] = $model;
        }
        return $models;
    }
}