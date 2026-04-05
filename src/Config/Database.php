<?php
namespace App\Config;

//Usamos MysqlClient
use React\Mysql\MysqlClient;

class Database {
    public static function getConnection() {
        //Crea la conexión de la bd
        $uri = 'root:@127.0.0.1:3306/empresa_db'; 
        return new MysqlClient($uri);
    }
}