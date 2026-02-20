<?php
// Para que muestre el error en vez de nada en postman
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
//
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