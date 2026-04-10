<?php
//En esta clase va el CRUD de la bd

namespace App\Controllers;

use App\Model\ContactosModel;
use Psr\Http\Message\ServerRequestInterface;
use React\Mysql\MysqlClient;
use React\Mysql\MysqlResult;
use App\Model\ServiceModel;
use Dom\Mysql;
use Exception;
use React\Http\Message\Response;

class DataController {


    // -------- SERVICE -------------
    //Ver si la puedo hacer abstracta para cualquier caso de {{}}
    public static function onLoadIndex(string $path, MysqlClient $db){
       
        $realPath = realpath($path);
        return ServiceModel::allService($db)->then(
            function (MysqlResult $result) use($realPath) {
                $servicesHtml = '';

                foreach ($result->resultRows as $service) {
                    $nombre = htmlspecialchars($service['nombre']);
                    $descripcion = htmlspecialchars($service['descripcion']);

                    $servicesHtml .= "
                        <div class='form-card service-card'>
                            <h4>{$nombre}</h4>
                            <p>{$descripcion}</p>
                        </div>
                    ";
                }

                $html = file_get_contents($realPath);

                $html = str_replace('{{ SERVICES }}', $servicesHtml, $html);

                return new Response(
                    200,
                    ['Content-Type' => 'text/html; charset=UTF-8'],
                    $html
                );
            },
            function ($error) {
                return new Response(
                    500,
                    ['Content-Type' => 'text/plain; charset=UTF-8'],
                    'Error cargando servicios: ' . $error->getMessage()
                );
            }
        );
    }

    public static function index(MysqlClient $db){
        

        return ServiceModel::allService($db)->then(
            function (MysqlResult $command){
                return new Response(200, ['Content-Type' => 'application/json'], json_encode($command->resultRows));
            },
            function (Exception $er){
                return new Response(69, ['Content-Type' => 'application/json'],'Noo mi compa');
            }
        );
       
    }

    public static function createServcice(MysqlClient $db, ServerRequestInterface $request){
        try{
            $service = $request->getParsedBody();

            if(!$service){
                return new Response(
                    400,
                    ['Content-Type' => 'application/json'],
                    json_encode([
                        'success' => false,
                        'message' => 'Datos Invalidos'
                    ])
                );
            }

            return ServiceModel::createService($service, $db)->then(
                function (MysqlResult $result){
                   
                    return new Response(
                        201,
                        ['Content-Type' => 'application/json'],
                        json_encode([
                            'success' => true,
                            'message' => 'Servicio creado correctamente',
                            'service' => $result->resultRows
                        ])
                    );
                },
                function (Exception $e){
                    new Response (
                        404,
                        ['Content-Type' => 'application/json'],
                        json_encode([
                            'success' => false,
                            'message' => 'Servicio no creado. Posible error en la bd'
                        ])
                    );
                }
            );

        }catch(Exception $e){
            return new Response(
                500,
                ['Content-Type' => 'application/json'],
                json_encode([
                    'success' => false,
                    'message' => 'Error interno CRITICO',
                    'error' => $e->getMessage()
                ])
            );
        }

    }

    public static function deleteService(MysqlClient $db, ServerRequestInterface $request){
        try{
            //Ahora cambia, puesto que es una peticion que hago desde JS
            $body = json_decode((string) $request->getBody(), true);
            $id = $body['id'] ?? null;

            if(!$id){
                return new Response(
                    400,
                    ['Content-Type' => 'application/json'],
                    json_encode([
                        'success' => false,
                        'message' => 'Datos Invalidos'
                    ])
                );
            }

            return ServiceModel::deleteService($id, $db)->then(
                function (MysqlResult $result){
                    return new Response(
                        201,
                        ['Content-Type' => 'application/json'],
                        json_encode([
                            'success' => true,
                            'message' => 'Servicio borrado correctamente',
                            'service' => $result->resultRows
                        ])
                    );
                },
                function (Exception $e){
                    new Response (
                        404,
                        ['Content-Type' => 'application/json'],
                        json_encode([
                            'success' => false,
                            'message' => 'Servicio no borrado. Posible error en la bd'
                        ])
                    );
                }
            );

        }catch(Exception $e){
            return new Response(
                500,
                ['Content-Type' => 'application/json'],
                json_encode([
                    'success' => false,
                    'message' => 'Error interno',
                    'error' => $e->getMessage()
                ])
            );
        
        }
    }
    public static function patchSercice(MysqlClient $db, ServerRequestInterface $request){
        try{
            $service = $request->getParsedBody();

            if (!$service) {
                return new Response(
                    400,
                    ['Content-Type' => 'application/json'],
                    json_encode([
                        'success' => false,
                        'message' => 'Datos Invalidos'
                    ])
                );
            }

            return ServiceModel::patchService($service, $db)->then(
                function (MysqlResult $result){
                    return new Response(
                        201,
                        ['Content-Type' => 'application/json'],
                        json_encode([
                            'success' => true,
                            'message' => 'Servicio actualizado correctamente'
                        ])
                    );
                },
                function (Exception $e){
                    return new Response(
                        500,
                        ['Content-Type' => 'application/json'],
                        json_encode([
                            'success' => false,
                            'message' => 'Error al guardar contacto',
                            'error' => $e->getMessage()
                        ])
                    );
                }
            );

        }catch(Exception $ex){
            
        }
    }

    public static function serviceById(string $id, MysqlClient $db){
        try{
           
            if(!$id){
                return new Response(
                    400,
                    ['Content-Type' => 'application/json'],
                    json_encode([
                        'success' => false,
                        'message' => 'Datos Invalidos'
                    ])
                );
            }

            return ServiceModel::foundServiceId($id, $db)->then(
                function (MysqlResult $result){
                    return new Response(201,
                    ['Content-Type'=> 'application/json'],
                    json_encode([
                        'success' => true,
                        'message' => 'Servicio encontrado',
                        'service' => $result->resultRows
                    ])
                    );
                },
                function (Exception $e){
                    return new Response(
                        500,
                        ['Content-Type' => 'application/json'],
                        json_encode([
                            'success' => false,
                            'message' => 'Error al encontrar el servicio',
                            'error' => $e->getMessage()
                        ])
                    );
                }
            );

        }catch(Exception $e){
            return new Response(
                500,
                ['Content-Type' => 'application/json'],
                json_encode([
                    'success' => false,
                    'message' => 'Error interno',
                    'error' => $e->getMessage()
                ])
            );
        }
    }
    // ------------- CONTACTS -------------
    public static function createContact(ServerRequestInterface $request, MysqlClient $db){
         try {
            // Leer body en formato form-data/urlencoded (ESTO POR QUE ASI LO MANDA EL FORM)
            $data = $request->getParsedBody();

            // Validar si vino JSON correcto
            if (!$data) {
                return new Response(
                    400,
                    ['Content-Type' => 'application/json'],
                    json_encode([
                        'success' => false,
                        'message' => 'Datos Invalidos'
                    ])
                );
            }

            return ContactosModel::create($data, $db)->then(
                function ($result) {
                    return new Response(
                        201,
                        ['Content-Type' => 'application/json'],
                        json_encode([
                            'success' => true,
                            'message' => 'Contacto guardado correctamente'
                        ])
                    );
                },
                function (Exception $error) {
                    return new Response(
                        500,
                        ['Content-Type' => 'application/json'],
                        json_encode([
                            'success' => false,
                            'message' => 'Error al guardar contacto',
                            'error' => $error->getMessage()
                        ])
                    );
                }
            );

        } catch (Exception $e) {
            return new Response(
                500,
                ['Content-Type' => 'application/json'],
                json_encode([
                    'success' => false,
                    'message' => 'Error interno',
                    'error' => $e->getMessage()
                ])
            );
        }
    }
   
}