<?php session_start();
if (isset($_POST['vaciar_historial'])) {
    unset($_SESSION['historial_cotizaciones']);
}
?>


<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Lista de Cotizaciones</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container my-5">

    <!-- Encabezado -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold">📋 Cotizaciones Generadas</h2>
        <a href="services-catalog.php" class="btn btn-primary">
            ← Volver al Catálogo
        </a>
    </div>

 <form method="POST">
    <button type="submit" name="vaciar_historial" 
            style="background:red;color:white;padding:8px;border:none;border-radius:5px;">
        Vaciar Historial
    </button>
</form>
    <!-- Visible desde md hacia arriba -->
    <div class="table-responsive d-none d-md-block">

        <table class="table table-bordered table-hover bg-white shadow-sm">
            <thead class="table-dark">
                <tr>
                    <th>Código</th>
                    <th>Cliente</th>
                    <th>Fecha</th>
                    <th>Total</th>
                    <th>Cantidad de Servicios</th>
                </tr>
            </thead>
<tbody>

<?php if (!empty($_SESSION['historial_cotizaciones'])): ?>

    <?php foreach ($_SESSION['historial_cotizaciones'] as $cot): ?>
        <tr>
            <td><?= $cot['codigo'] ?></td>
            <td><?= $cot['cliente'] ?></td>
            <td><?= $cot['fecha'] ?></td>
            <td>$<?= $cot['total'] ?></td>
            <td><?= $cot['cantidad'] ?></td>
        </tr>
    <?php endforeach; ?>

<?php else: ?>
    <tr>
        <td colspan="5" class="text-center">No hay cotizaciones aún</td>
    </tr>
<?php endif; ?>

</tbody>
        </table>

    </div>

    
    <!-- Visible solo en pantallas pequeñas -->
    <div class="d-block d-md-none ">

        <!-- CARD 1 -->
        <div class="card mb-3 shadow-sm">
            <div class="card-body">
                <h5 class="card-title fw-bold">COT-001</h5>
                <p class="mb-1"><strong>Cliente:</strong> Juan Pérez</p>
                <p class="mb-1"><strong>Fecha:</strong> 2026-02-24</p>
                <p class="mb-1"><strong>Total:</strong> $250.00</p>
                <p class="mb-0"><strong>Servicios:</strong> 3</p>
            </div>
        </div>

        <!-- CARD 2 -->
        <div class="card mb-3 shadow-sm">
            <div class="card-body">
                <h5 class="card-title fw-bold">COT-002</h5>
                <p class="mb-1"><strong>Cliente:</strong> Empresa XYZ</p>
                <p class="mb-1"><strong>Fecha:</strong> 2026-02-23</p>
                <p class="mb-1"><strong>Total:</strong> $480.00</p>
                <p class="mb-0"><strong>Servicios:</strong> 5</p>
            </div>
        </div>

    </div>

</div>

</body>
</html>
