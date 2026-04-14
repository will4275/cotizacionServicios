<?php
session_start();

// 🔥 borrar todas las variables de sesión
$_SESSION = [];

// 🔥 destruir la sesión
session_destroy();

// 🔥 borrar cookie de sesión (opcional pero recomendado)
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params["path"],
        $params["domain"],
        $params["secure"],
        $params["httponly"]
    );
}
session_destroy();
// 🔥 redirigir al login
header("Location: login.php");
exit;
?>