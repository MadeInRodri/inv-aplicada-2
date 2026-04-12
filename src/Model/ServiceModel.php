<?php 

namespace App\Model;

use Exception;
use React\Mysql\MysqlClient;
use React\Mysql\MysqlResult;
use React\Promise\Promise;
use React\Promise\PromiseInterface;

class ServiceModel {

    public static function foundServiceId(string $id, MysqlClient $db): PromiseInterface {

      
        return $db->query('SELECT * from servicios where id = ?', [$id]);

    }

    public static function patchService(array $service, MysqlClient $db): PromiseInterface {
        $query = 'UPDATE servicios SET nombre = ?, descripcion = ?, estado = ? where id = ?';
        return $db->query($query,
        [
            $service['nombre'],
            $service['descripcion'],
            $service['estado'],
            $service['id']
        ]);
    }

    public static function createService(array $service, MysqlClient $db): PromiseInterface {

        $query = 'INSERT INTO servicios (nombre, descripcion, estado) VALUES (?, ?, ?)';
        return $db->query($query, 
        [
            $service['nombre'],
            $service['descripcion'],
            $service['estado']
        ]);
    }

    public static function deleteService(string $id, MysqlClient $db){
        return $db->query(
            'DELETE FROM servicios WHERE id = ?',
            [$id]
        );
    }
    public static function allService(MysqlClient $db): PromiseInterface {
        return $db->query("SELECT * from servicios");
    }

    public static function allActiveService(MysqlClient $db): PromiseInterface {
        return $db->query("SELECT * from servicios WHERE estado = 'activo'");
    }

}