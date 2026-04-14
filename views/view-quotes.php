<?php
if (session_status() === PHP_SESSION_NONE) { session_start(); }

require_once '../config/database.php';
require_once '../models/quote_model.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$model = new QuoteModel($pdo);
$user_id = $_SESSION['user_id'];
$rol_id = $_SESSION['user_role'];

// --- LÓGICA DE ELIMINACIÓN ---
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['eliminar_id'])) {
    // Tanto el Admin (Rechazar) como el Usuario (Cancelar) borrarán el dato
    $model->eliminarCotizacion($_POST['eliminar_id']);
    header("Location: view-quotes.php");
    exit;
}

$cotizaciones = $model->obtenerListadoCotizaciones($user_id, $rol_id);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Historial de Cotizaciones</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container mt-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold"><?= ($rol_id == 1) ? 'Cotizaciones Globales (Admin)' : 'Mis Cotizaciones' ?></h2>
        <a href="services-catalog.php" class="btn btn-primary">← Volver al Catálogo</a>
    </div>

    <div class="card shadow-sm border-0">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-dark">
                <tr>
                    <th>Código</th>
                    <th>Cliente</th>
                    <th>Empresa</th>
                    <th>Total</th>
                    <th class="text-center">Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($cotizaciones as $cot): ?>
                    <tr>
                        <td class="fw-bold text-primary"><?= $cot['cotizacion_id'] ?></td>
                        <td><?= htmlspecialchars($cot['nombre_cliente']) ?></td>
                        <td><?= htmlspecialchars($cot['empresa'] ?? 'Particular') ?></td>
                        <td class="fw-bold">$<?= number_format($cot['total'], 2) ?></td>
                        <td class="text-center">
                            <form method="POST" onsubmit="return confirm('¿Estás seguro? Esta acción no se puede deshacer.');">
                                <input type="hidden" name="eliminar_id" value="<?= $cot['cotizacion_id'] ?>">
                                
                                <?php if ($rol_id == 1): ?>
                                    <button type="submit" class="btn btn-sm btn-danger">Rechazar y Eliminar</button>
                                <?php else: ?>
                                    <button type="submit" class="btn btn-sm btn-outline-danger">Cancelar y Borrar</button>
                                <?php endif; ?>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
</body>
</html>