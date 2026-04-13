<?php
session_start();
header('Content-Type: application/json');
require_once '../API/quote.php';
require_once '../config/database.php';
require_once '../models/quote_model.php';

$json = file_get_contents('php://input');
$data = json_decode($json, true);
$items = $data['items'] ?? [];
$action = $data['action'] ?? 'calculate';

$quote = new Quote();
$quote->vaciar();

foreach ($items as $item) {
    for ($i = 0; $i < intval($item['cantidad']); $i++) {
        $quote->agregarItem($item);
    }
}

$subtotal  = $quote->calcularSubtotal();
$descuento = $quote->calcularDescuento();
$neto      = $subtotal - $descuento;
$iva       = $quote->calcularIVA($neto);
$total     = $neto + $iva;

// Generamos el código aquí para que sea el mismo en el cálculo y en el guardado
$codigoAleatorio = "COT-" . strtoupper(substr(uniqid(), -5));

if ($action === 'save') {
    try {
        if (!isset($_SESSION['usuario_id'])) {
            throw new Exception("Inicie sesión para continuar.");
        }

        $model = new QuoteModel($pdo);
        $resumen = [
            'cantidad' => count($items),
            'subtotal' => $subtotal,
            'descuento'=> $descuento,
            'iva'      => $iva,
            'total'    => $total
        ];

        // Guardamos usando el código aleatorio como ID
        $model->guardarCotizacionCompleta($codigoAleatorio,$id_usuario_prueba = $_SESSION['usuario_id'] ?? 1;, $data['cliente'], $resumen);

        echo json_encode(["status" => "success", "id_cotizacion" => $codigoAleatorio]);
        exit;
    } catch (Exception $e) {
        echo json_encode(["status" => "error", "message" => $e->getMessage()]);
        exit;
    }
}

echo json_encode([
    "status"    => "success",
    "subtotal"  => number_format($subtotal, 2, '.', ''),
    "descuento" => number_format($descuento, 2, '.', ''),
    "iva"       => number_format($iva, 2, '.', ''),
    "total"     => number_format($total, 2, '.', ''),
    "codigo"    => $codigoAleatorio
]);