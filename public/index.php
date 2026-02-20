<?php
/*/ Para que muestre el error en vez de nada en postman
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
clearstatcache(); // <- Limpiar la memoria caché de los archivos ya que se quedo con cache del anterior y no se veían los cambios
error_reporting(E_ALL);
/*/
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: GET, POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

// Incluimos el enrutador
require_once __DIR__ . '/../core/router.php';

// Instanciamos y ejecutamos el enrutador
$router = new Router();
$router->run();

// http://localhost/api_keys/public/api/password/validate <- Endpoint para validar una contraseña

/* Ejemplo de JSON para validar una contraseña:
{
    "password": "contraseña",
    "requirements": {
        "minLength": 8,
        "requireUppercase": true,
        "requireNumbers": true,
        "requireSymbols": true
    }
}
*/