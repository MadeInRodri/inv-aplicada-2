<?php
require __DIR__ . '/vendor/autoload.php';

use React\Http\HttpServer;
use React\Http\Message\Response;
use Psr\Http\Message\ServerRequestInterface;
use React\Socket\SocketServer;
use App\Config\Database;
use App\Controllers\FileController;
// use App\Controllers\DataController; // Lo descomentaremos cuando hagamos el CRUD

// Inicializar la conexión asíncrona a la DB
$db = Database::getConnection();

// Configurar el Servidor HTTP
$server = new HttpServer(function (ServerRequestInterface $request) use ($db) {
    $path = $request->getUri()->getPath();
    $method = $request->getMethod();

    echo "[" . date('H:i:s') . "] $method $path\n";

    try {
        switch ($path) {
            // --- RUTAS ESTÁTICAS ---
            case '/':
                return FileController::serve(__DIR__ . '/public/index.html', 'text/html');
            case '/contact.html':
                return FileController::serve(__DIR__ . '/public/contact.html', 'text/html');
            case '/admin.html':
                return FileController::serve(__DIR__ . '/public/admin.html', 'text/html');
            case '/style.css':
                return FileController::serve(__DIR__ . '/public/style.css', 'text/css');

            // --- RUTAS DINÁMICAS (CRUD) ---
            case '/data':
                // Aquí delegaremos a DataController::handleRequest($request, $db);
                return Response::json(['mensaje' => 'Ruta /data lista para recibir operaciones CRUD']);

            default:
                return new Response(404, ['Content-Type' => 'application/json'], json_encode(['error' => 'Ruta no encontrada']));
        }
    } catch (\Exception $e) {
        // Control de errores global (Requerimiento 2)
        return new Response(500, ['Content-Type' => 'application/json'], json_encode(['error' => 'Error interno: ' . $e->getMessage()]));
    }
});

$socket = new SocketServer('127.0.0.1:8080');
$server->listen($socket);

echo "Servidor ReactPHP iniciado.\n";
echo "Local: http://127.0.0.1:8080\n";