<?php
require_once '../../controllers/Auth_controller.php';

$auth = new Auth_controller();
$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $result = $auth->login($_POST);

    if (isset($result['error'])) {
        $message = '<div class="alert alert-danger">' . $result['error'] . '</div>';
    } else {
        // Redirección después de login
        header("Location: ../services-catalog.php");
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <link rel="stylesheet" href="../../public/assets/css/login.css">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">

    <title>Iniciar sesión</title>
</head>

<body class="d-flex align-items-center justify-content-center bodyCustom" style="min-height: 100vh;">

    <div class="loginContainer p-4 p-lg-5 shadow rounded bg-white" style="max-width: 420px; width: 100%;">
        
        <h1 class="mb-3">Iniciar sesión</h1>

        <p class="text-muted mb-4">
            Bienvenido de nuevo.<br>
            Ingresa para continuar.
        </p>

<form method="POST">

    <div class="mb-3">
        <label for="email" class="form-label">Correo electrónico</label>
        <input 
            type="email" 
            id="email" 
            name="email" 
            class="form-control" 
            placeholder="Ingresa tu email"
            required
        >
    </div>

    <div class="mb-3">
        <label for="password" class="form-label">Contraseña</label>
        <input 
            type="password" 
            id="password" 
            name="password" 
            class="form-control" 
            placeholder="Contraseña"
            required
        >
    </div>

    <div class="mb-3 text-end">
        <small>
            ¿No tienes cuenta? 
            <a href="register.php" class="text-primary fw-semibold">Regístrate</a>
        </small>
    </div>

    <div class="d-grid">
        <button type="submit" class="btn btn-primary">
            Iniciar sesión
        </button>
    </div>

    <?php echo $message; ?>

</form>

    </div>

</body>
</html>