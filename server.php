<?php
require __DIR__ . '/vendor/autoload.php';

use React\Http\HttpServer;
use React\Http\Message\Response;
use Psr\Http\Message\ServerRequestInterface;
use React\Socket\SocketServer;
use App\Config\Database;
use App\Controllers\FileController;
use App\Controllers\DataController;

// Inicializar la conexión asíncrona a la DB
$db = Database::getConnection();

// Configurar el Servidor HTTP
//ServerRequestInterface es un interface que sirve como una abstraccion de una request
//O sea tiene como propiedades lo que tendría una peticiión normal 
$server = new HttpServer(function (ServerRequestInterface $request) use ($db) {
    $path = $request->getUri()->getPath();
    $method = $request->getMethod();

    echo "[" . date('H:i:s') . "] $method $path\n";
    
    try {
        switch ($path) {
            // --- RUTAS ESTÁTICAS ---
            
            case '/contact.html':
                return FileController::serve(__DIR__ . '/public/contact/contact.html', 'text/html');
            case '/admin.html':
                return FileController::serve(__DIR__ . '/public/admin/admin.html', 'text/html');
            case '/style.css':
                return FileController::serve(__DIR__ . '/public/style.css', 'text/css');
            case '/contactValidator.js':
                return FileController::serve(__DIR__ . '/public/contact/contactValidator.js', 'application/javascript');
            case '/adminValidator.js':
                return FileController::serve(__DIR__ . '/public/admin/adminValidator.js', 'application/javascript');
            

            // --- RUTAS DINÁMICAS (CRUD) ---
                
            case '/':
                return DataController::onLoadIndex(__DIR__ . '/public/index.html', $db);
            case '/service':
                
                if($method == 'GET'){
                    $queryParams = $request->getQueryParams();
                    $id = $queryParams['id'] ?? null;

                    if ($id) {
                        
                        return DataController::serviceById($id, $db);
                    }

                    return DataController::index($db);
                }

                if($method == "POST"){
                   return DataController::createServcice($db, $request);
                }

                if($method == 'PATCH'){
                    return DataController::patchSercice($db, $request);
                }
                if($method == 'DELETE'){
                    return DataController::deleteService($db, $request);
                }

                

            case '/contacts':
                if($method == 'POST'){
                    return DataController::createContact($request, $db);
                }

            default:
                return new Response(404, ['Content-Type' => 'application/json'], json_encode(['error' => 'Ruta no encontrada']));
        }
    } catch (\Exception $e) {
        // Control de errores global (Requerimiento 2)
        
        return new Response(500, ['Content-Type' => 'application/json'], json_encode(['error PESIMO' => 'Error interno: ' . $e->getMessage()]));
    }
});

$socket = new SocketServer('127.0.0.1:8080');
$server->listen($socket);

echo "Servidor ReactPHP iniciado.\n";
echo "Local: http://127.0.0.1:8080\n";