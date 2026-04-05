<?php
//Clase para el manejo de archivos y vistas (.html, .css)
namespace App\Controllers;

use React\Http\Message\Response;

class FileController {
    public static function serve(string $path, string $contentType): Response {
        //Convierte la ruta en absoluta
        $realPath = realpath($path);

        //Si la ruta no existe o está vacía
        if (!$realPath || !file_exists($realPath)) {
            return new Response(404, ['Content-Type' => 'text/plain'], "404 - Archivo no encontrado");
        }

        //Toma el contenido del archivo
        $content = file_get_contents($realPath);
        
        //Trata de leerlo
        if ($content === false) {
            return new Response(500, ['Content-Type' => 'text/plain'], "500 - Error de lectura interna");
        }

        //Lo devuelve
        return new Response(200, ['Content-Type' => $contentType], $content);
    }
}