<?php

require_once __DIR__ . '/../controllers/Servicios_controller.php';

$controller = new Servicios_controller();

$action = isset($_GET['action']) ? $_GET['action'] : 'index';

switch ($action) {
    case 'getServicios':
        $controller->getServicios();
        break;
    case 'getCategorias':
        $controller->getCategorias();
        break;
    default:
        $controller->index();
        break;
}