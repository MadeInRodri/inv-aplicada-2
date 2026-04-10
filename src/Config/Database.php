<?php
namespace App\Config;

//Usamos MysqlClient

use Exception;
use React\Mysql\MysqlClient;
use React\Http\Message\Response;


class Database {
    public static function getConnection() {
        //Crea la conexión de la bd
        try{
            $uri = 'root:@127.0.0.1:3306/empresa_db'; 
            return new MysqlClient($uri);
        }catch(Exception $error){
            new Response(500, ['Content-Type' => 'text/plain'], 'Error critico al acceder a la base de datos: ' . $error->getMessage());
        }
    }
}