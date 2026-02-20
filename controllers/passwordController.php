<?php
require_once __DIR__ . '/passwordAdapter.php';

class PasswordController {
    private $adapter;

    public function __construct() {
        $this->adapter = new PasswordAdapter();
    }

    // Maneja el GET
    public function handleGenerateSingle() {
        try {
            $length = isset($_GET['length']) ? (int)$_GET['length'] : 16;
            
            $password = $this->adapter->generateSingle($length, $_GET);
            
            http_response_code(200);
            echo json_encode([
                "success" => true,
                "data" => [
                    "password" => $password,
                    "length" => strlen($password)
                ]
            ]);

        } catch (Exception $e) {
            http_response_code(400);
            echo json_encode([
                "success" => false,
                "error" => $e->getMessage()
            ]);
        }
    }

    // Maneja el POST
    public function handleGenerateMultiple() {
        try {
            $jsonBody = file_get_contents('php://input');
            $data = json_decode($jsonBody, true) ?? [];

            $count = isset($data['count']) ? (int)$data['count'] : 5;
            $length = isset($data['length']) ? (int)$data['length'] : 16;

            $passwords = $this->adapter->generateMultiple($count, $length, $data);

            http_response_code(200);
            echo json_encode([
                "success" => true,
                "data" => [
                    "count" => count($passwords),
                    "passwords" => $passwords
                ]
            ]);

        } catch (Exception $e) {
            http_response_code(400);
            echo json_encode([
                "success" => false,
                "error" => $e->getMessage()
            ]);
        }
    }


    // Maneja el POST validate
    public function handleValidate() {
        $jsonBody = file_get_contents('php://input');
        $data = json_decode($jsonBody, true) ?? [];

        // Validamos que nos manden los datos correctos
        if (!isset($data['password']) || !isset($data['requirements'])) {
            http_response_code(400);
            echo json_encode([
                "success" => false, 
                "error" => "Faltan parámetros: asegúrate de enviar 'password' y 'requirements' en el JSON."
            ]);
            return;
        }

        $result = $this->adapter->validatePassword($data['password'], $data['requirements']);
        http_response_code(200);
        echo json_encode([
            "success" => true,
            "data" => $result
        ]);
    }
}