<?php
namespace app\base;


class Connection
{
    public $connection;

    public function __construct()
    {
        /**@var $db []*/
        $db = App::$app->db;
        $this->connection = new \PDO("mysql:host={$db['host']};dbname={$db['db']};",$db['user'],$db['password'],[\PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION]);
    }

}