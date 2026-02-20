<?php
require_once __DIR__ . '/../controllers/passwordController.php';

class Router {
    public function run() {
        // Obtenemos la ruta y el método GET o POST
        $uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

        $uri = str_replace('/api_keys/public', '', $uri);// Esto para limpiar la ruta y evitar errores

        $method = $_SERVER['REQUEST_METHOD'];

        $controller = new PasswordController();
        if ($uri === '/api/password' && $method === 'GET') {
            $controller->handleGenerateSingle();
            
        } elseif ($uri === '/api/passwords' && $method === 'POST') {
            $controller->handleGenerateMultiple();
            
        } elseif ($uri === '/api/password/validate' && $method === 'POST') {
            http_response_code(501); // 501 Not Implemented
            echo json_encode(["message" => "Endpoint de validación en construcción"]);
            
        } else {
            http_response_code(404);
            echo json_encode(["error" => "Ruta no encontrada"]);
        }
    }
}