# 🚀 Empresa X - Servidor Event-Driven con ReactPHP

Este proyecto implementa un sitio web dinámico utilizando un servidor web no bloqueante basado en el paradigma **Event-Driven** mediante **ReactPHP**. El sistema es capaz de manejar múltiples solicitudes simultáneas de manera eficiente, sirviendo archivos estáticos y realizando operaciones CRUD asíncronas en la base de datos sin detener el flujo del proceso principal.

## 🏗️ Arquitectura del Proyecto

El proyecto utiliza una adaptación del patrón MVC (Modelo-Vista-Controlador) orientada a eventos para mantener el código modular y escalable:

\`\`\`text
INVESTIGACION-APLICADA-2/
├── public/ # Archivos estáticos y vistas accesibles por el cliente
│ ├── index.html # Punto de entrada estático (Inicio)
│ ├── contact.html # Formulario de captura de prospectos
│ └── style.css # Hoja de estilos principal
├── src/ # Lógica de la aplicación (Autocargada vía PSR-4)
│ ├── Config/
│ │ └── Database.php # Configuración y fábrica de conexión MySQL diferida (Lazy)
│ └── Controllers/
│ ├── FileController.php # Controlador para servir archivos sin detener el Event Loop
│ └── DataController.php # Controlador asíncrono para operaciones CRUD
├── vendor/ # Dependencias gestionadas por Composer
├── composer.json # Configuración de dependencias y autoloader
└── server.php # Punto de entrada: Inicia el Event Loop y enruta las peticiones
\`\`\`

## ⚙️ Requisitos Previos

Asegúrese de tener instalados los siguientes componentes en su entorno local:

- **PHP** >= 7.4 (Configurado en las variables de entorno del sistema).
- **Composer** (Gestor de dependencias de PHP).
- **MySQL/MariaDB** (Puede utilizarse el módulo de XAMPP).

## 🚀 Instrucciones de Instalación y Ejecución

Siga estos pasos para compilar y levantar el servidor por primera vez:

### 1. Clonar e instalar dependencias

Abra su terminal en la raíz del proyecto y ejecute:
\`\`\`bash
composer install
\`\`\`

### 2. Generar el Autoloader

Para que PHP reconozca la estructura de la carpeta `src/`, ejecute:
\`\`\`bash
composer dump-autoload
\`\`\`

### 3. Configurar la Base de Datos

1. Inicie el servicio de **MySQL** en su entorno (ej. panel de control de XAMPP).
2. Ejecute el script SQL proporcionado en el proyecto para crear la base de datos `empresa_x_db` y las tablas necesarias (`contactos`, `servicios`).
3. Verifique que las credenciales en `src/Config/Database.php` coincidan con su entorno local (por defecto: usuario `root` sin contraseña).

### 4. Iniciar el Servidor Event-Driven

Levante el servidor ejecutando el script principal:
\`\`\`bash
php server.php
\`\`\`

Si todo está configurado correctamente, verá el siguiente mensaje en la terminal:
\`\`\`text
✅ Servidor ReactPHP iniciado.
🔗 Local: http://127.0.0.1:8080
\`\`\`

### 5. Probar la Concurrencia

Abra su navegador web y acceda a `http://127.0.0.1:8080`. Puede navegar entre el inicio y el formulario de contacto. La terminal registrará las peticiones entrantes en tiempo real. Para detener el servidor, presione `Ctrl + C` en la terminal.
