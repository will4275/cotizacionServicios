<?php

class Servicios_controller
{
    private $serviciosModel;

    public function __construct()
    {
        require_once __DIR__ . '/../models/Servicios_model.php';
        $this->serviciosModel = new Servicios_model();
    }

    public function index()
    {
        // Load the view
        $this->loadView('services-catalog');
    }

    public function getServicios()
    {
        $categoria_id = isset($_GET['categoria_id']) ? $_GET['categoria_id'] : null;
        $servicios = $this->serviciosModel->getServiciosPorCategoria($categoria_id);
        header('Content-Type: application/json');
        echo json_encode($servicios);
    }

    public function getCategorias()
    {
        $categorias = $this->serviciosModel->getCategorias();
        header('Content-Type: application/json');
        echo json_encode($categorias);
    }

    private function loadView($viewName, $data = [])
    {
        extract($data);
        require_once "../../views/$viewName.php";
    }
}