<?php

    class Auth_controller 
    {
        private $authModel;
        public function __construct()
        {
            require_once '../../models/Auth_model.php';
            $this->authModel = new Auth_model();
        }

        public function registrarUsuario()
        {
            $this->authModel->verificarUsuarioExiste('');
            $this->loadView('auth/register');
        }

        public function register($data)
        {
            // Validate inputs
            if (empty($data['name']) || empty($data['apellido']) || empty($data['email']) || empty($data['password']) || empty($data['confirmPassword'])) {
                return ['error' => 'Todos los campos son obligatorios.'];
            }
            if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
                return ['error' => 'Correo electrónico inválido.'];
            }
            if ($data['password'] !== $data['confirmPassword']) {
                return ['error' => 'Las contraseñas no coinciden.'];
            }
            if (strlen($data['password']) < 6) {
                return ['error' => 'La contraseña debe tener al menos 6 caracteres.'];
            }

            // Check if user exists
            $existingUser = $this->authModel->verificarUsuarioExiste($data);
            if ($existingUser) {
                return ['error' => 'El correo electrónico ya está registrado.'];
            }

            // Register user
            $userId = $this->authModel->registrarUsuario($data);
            if ($userId) {
                return ['success' => 'Usuario registrado exitosamente.'];
            } else {
                return ['error' => 'Error al registrar el usuario.'];
            }
        }

        private function loadView($viewName, $data = []) {
        // 1. Convierte las llaves del array en variables reales
        // Si $data es ['nombre' => 'Alma'], crea una variable $nombre
        extract($data);

        // 2. Construye la ruta al archivo de la vista
        $viewPath = './views/' . $viewName . '.php';

        // 3. Verifica si el archivo existe y lo incluye
        if (file_exists($viewPath)) {
            require_once $viewPath;
        } else {
            die("La vista $viewName no existe en $viewPath");
        }
        }

    }


