<?php 

namespace App\Model;

use Exception;
use React\Mysql\MysqlClient;
use React\Mysql\MysqlResult;
use React\Promise\Promise;
use React\Promise\PromiseInterface;

class ContactosModel {

    public static function create(array $data, MysqlClient $db): PromiseInterface {

      $sql = "INSERT INTO contactos (nombre, apellido, email, telefono, asunto)
                VALUES (?, ?, ?, ?, ?)";

        return $db->query($sql, [
            $data['nombre'],
            $data['apellido'],
            $data['email'],
            $data['telefono'] ?? null,
            $data['asunto']
        ]);

    }

    public static function allService(MysqlClient $db): PromiseInterface {
        return $db->query('SELECT * from servicios');
    }

}