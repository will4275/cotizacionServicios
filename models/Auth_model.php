<?php


    class Auth_model
    {
        private $db;
        public function __construct()
        {
        require_once '../../config/database.php';
            $this->db = $pdo;
        }

        public function verificarUsuarioExiste($data)
        {

            $sqlQuery = "SELECT * FROM usuario WHERE usuario_email = :email";
            $stmt = $this->db->prepare($sqlQuery);
            $stmt->execute(['email'=>$data['email']]);
            $user = $stmt->fetch(PDO::FETCH_ASSOC);
            return $user;
        }

        public function registrarUsuario($data)
        {
            $hashedPassword = password_hash($data['password'], PASSWORD_DEFAULT);
            $sqlQuery = "INSERT INTO usuario (nombre_usuario, apellido_usuario, usuario_email, usuario_contraseña, rol_id) VALUES (:nombre, :apellido, :email, :password, 2)";
            $stmt = $this->db->prepare($sqlQuery);
            $stmt->execute([
                'nombre' => $data['name'],
                'apellido' => $data['apellido'],
                'email' => $data['email'],
                'password' => $hashedPassword
            ]);
            return $this->db->lastInsertId();
        }

    }













?>