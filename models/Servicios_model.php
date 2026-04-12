<?php

class Servicios_model
{
    private $db;
    public function __construct()
    {
        require_once __DIR__ . '/../config/database.php';
        $this->db = $pdo;
    }

    public function getCategorias()
    {
        $sql = "SELECT * FROM categoria";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getServiciosPorCategoria($categoria_id = null)
    {
        if ($categoria_id) {
            $sql = "SELECT s.servicio_id, s.nombre_servicio, s.descripcion_servicio, CAST(s.precio AS DECIMAL(10,2)) as precio, c.nombre_categoria FROM servicio s JOIN categoria c ON s.categoria_id = c.categoria_id WHERE s.categoria_id = :categoria_id";
            $stmt = $this->db->prepare($sql);
            $stmt->execute(['categoria_id' => $categoria_id]);
        } else {
            $sql = "SELECT s.servicio_id, s.nombre_servicio, s.descripcion_servicio, CAST(s.precio AS DECIMAL(10,2)) as precio, c.nombre_categoria FROM servicio s JOIN categoria c ON s.categoria_id = c.categoria_id";
            $stmt = $this->db->prepare($sql);
            $stmt->execute();
        }
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}