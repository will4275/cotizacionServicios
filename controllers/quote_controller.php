<?php
// 1. Evitamos que los 'Notices' de sesión ensucien el JSON
error_reporting(E_ALL & ~E_NOTICE);
ini_set('display_errors', 0);

// 2. Iniciamos sesión solo si no está activa
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

header('Content-Type: application/json');

// Cargamos la lógica de cálculo
require_once '../API/quote.php';

$json = file_get_contents('php://input');
$data = json_decode($json, true);
$items = $data['items'] ?? [];
$action = $data['action'] ?? 'calculate';

// Inicializamos la clase de cálculos
$quote = new Quote();
$quote->vaciar();

foreach ($items as $item) {
    $qty = intval($item['cantidad']);
    for ($i = 0; $i < $qty; $i++) {
        $quote->agregarItem($item);
    }
}

// Realizamos todos los cálculos económicos
$subtotal  = $quote->calcularSubtotal();
$descuento = $quote->calcularDescuento();
$neto      = $subtotal - $descuento;
$iva       = $quote->calcularIVA($neto);
$total     = $neto + $iva;

// --- BLOQUE DE GUARDADO ---
if ($action === 'save') {
    try {
        // Verificamos la sesión usando 'user_id' (según tu captura de pantalla)
        if (!isset($_SESSION['user_id'])) {
            echo json_encode(["status" => "error", "message" => "Sesión no encontrada. Por favor inicie sesión."]);
            exit;
        }

        require_once '../config/database.php';
        require_once '../models/quote_model.php';

        $model = new QuoteModel($pdo);
        
        // Generamos el código COT-XXXXX
        $codigoAleatorio = "COT-" . strtoupper(substr(uniqid(), -5));

        // Preparamos los datos del cliente que vienen del modal verde
        $cliente = $data['cliente'];
        
        $resumen = [
            'cantidad'  => count($items),
            'subtotal'  => $subtotal,
            'descuento' => $descuento,
            'iva'       => $iva,
            'total'     => $total
        ];

        // Guardamos en la tabla
        $resultado = $model->guardarCotizacionCompleta(
            $codigoAleatorio, 
            $_SESSION['user_id'], 
            $cliente, 
            $resumen
        );

        if ($resultado) {
            echo json_encode(["status" => "success", "id_cotizacion" => $codigoAleatorio]);
        } else {
            throw new Exception("No se pudo insertar el registro en la base de datos.");
        }
        exit;

    } catch (Exception $e) {
        echo json_encode(["status" => "error", "message" => $e->getMessage()]);
        exit;
    }
}

// --- RESPUESTA PARA EL CARRITO (Actualización en tiempo real) ---
echo json_encode([
    "status"    => "success",
    "subtotal"  => number_format($subtotal, 2, '.', ''),
    "descuento" => number_format($descuento, 2, '.', ''),
    "iva"       => number_format($iva, 2, '.', ''),
    "total"     => number_format($total, 2, '.', '')
]);