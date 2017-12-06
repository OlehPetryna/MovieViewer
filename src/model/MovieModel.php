<?php

namespace app\model;


use app\base\Model;

class MovieModel extends Model
{
    /**
     * @var $id integer
     * @var $name string
     * @var $releaseYear integer
     * @var $format string
     * @var $actors array
     */

    public $id;
    public $name;
    public $releaseYear;
    public $format;
    public $actors;

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);

        if(!is_array($this->actors)){
            $this->actors = json_decode($this->actors);
        }
    }

    public static function getTableName()
    {
        return "Movie";
    }

    public static function importFromFile($path,$name)
    {
        if(!preg_match("/\.txt$/", $name))
            throw new \Exception("Invalid file format", 500);
        $f = fopen($path, 'r');

        $currentModel = [];
        while($b = fgets($f)){
            $b = str_replace("\n", "", $b);
            if(empty($b) && !empty($currentModel)){
                $model = new MovieModel($currentModel);
                $model->save();
                $currentModel = [];
            }
            else if(!empty($b)){
                $data = explode(": ", $b);
                while(count($data) > 2){
                    $tmp = array_pop($data);
                    $data[1] = implode(": ", [$data[1],$tmp]);
                }
                $data[0] = strtolower($data[0]);
                $data[0] = preg_replace_callback("/\s\w{1}/",function($matches){
                    return strtoupper(substr($matches[0], 1));
                }, $data[0]);

                if($data[0] === "stars"){
                    $data[0] = "actors";
                    $data[1] = explode(", ", $data[1]);
                }

                if($data[0] === "title")
                    $data[0] = "name";

                $currentModel[$data[0]] = $data[1];
            }
        }

        fclose($f);
    }
}