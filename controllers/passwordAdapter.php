<?php
require_once __DIR__ . '/../utils/genPassword.php';

class PasswordAdapter {
    // Establecimiento de min y max de caracteres
    private const MIN_LENGTH = 4;
    private const MAX_LENGTH = 128;

    // Generar una sola contraseña
    public function generateSingle(int $length, array $apiParams): string {
        $this->validateLength($length);
        $options = $this->mapParams($apiParams);
        return generate_password($length, $options);
    }

    //  Generar múltiples contraseñas
    public function generateMultiple(int $count, int $length, array $apiParams): array {
        $this->validateLength($length);
        $options = $this->mapParams($apiParams);
        return generate_passwords($count, $length, $options);
    }

    // Validación de la longitud para evitar contraseña muy pequeñas y las muy granes (que dudo que alguien haga algo mas de 50 caracteres)
    private function validateLength(int $length): void {
        if ($length < self::MIN_LENGTH || $length > self::MAX_LENGTH) {
            throw new Exception("La longitud debe estar entre " . self::MIN_LENGTH . " y " . self::MAX_LENGTH . " caracteres.");
        }
    }

    // Traductor de parámetros
    private function mapParams(array $params): array {
        return [
            'upper' => isset($params['includeUppercase']) ? filter_var($params['includeUppercase'], FILTER_VALIDATE_BOOLEAN) : true,
            'lower' => isset($params['includeLowercase']) ? filter_var($params['includeLowercase'], FILTER_VALIDATE_BOOLEAN) : true,
            'digits' => isset($params['includeNumbers']) ? filter_var($params['includeNumbers'], FILTER_VALIDATE_BOOLEAN) : true,
            'symbols' => isset($params['includeSymbols']) ? filter_var($params['includeSymbols'], FILTER_VALIDATE_BOOLEAN) : true,
            'avoid_ambiguous' => isset($params['excludeAmbiguous']) ? filter_var($params['excludeAmbiguous'], FILTER_VALIDATE_BOOLEAN) : false,
            'exclude' => $params['exclude'] ?? '',
            'require_each' => true
        ];
    }
    // Valida una contraseña existente (Caso 3)
    public function validatePassword(string $password, array $requirements): array {

        $errors = [];
        
        // Verificamos la longitud mínima
        $minLength = $requirements['minLength'] ?? 8;
        if (strlen($password) < $minLength) {
            $errors[] = "La contraseña debe tener al menos {$minLength} caracteres.";
        }
        
        // Verificamos si requiere mayúsculas usando expresiones regulares
        if (!empty($requirements['requireUppercase']) && !preg_match('/[A-Z]/', $password)) {
            $errors[] = "Debe contener al menos una letra mayúscula.";
        }
        
        // Verificamos si requiere números
        if (!empty($requirements['requireNumbers']) && !preg_match('/[0-9]/', $password)) {
            $errors[] = "Debe contener al menos un número.";
        }
        
        // Verificamos si requiere símbolos (cualquier cosa que no sea letra o número)
        if (!empty($requirements['requireSymbols']) && !preg_match('/[^a-zA-Z0-9]/', $password)) {
            $errors[] = "Debe contener al menos un símbolo especial.";
        }

        // Retornamos si es válida y la lista de errores (si los hay)
        return [
            'isValid' => count($errors) === 0,
            'errors' => $errors
        ];
    }
}