<?php
session_start();

// Si no viene por POST, redirigir al login
if ($_SERVER['REQUEST_METHOD'] !== 'POST' || empty($_POST['user']) || empty($_POST['pass'])) {
    header("Location: ./login.php");
    exit();
}

$user = trim($_POST['user']);
$pass = trim($_POST['pass']);

require_once __DIR__ . '/../Bibliotecas/dbLogin.php';
$log = new Login();
$result = $log->userExist($user, $pass);

if ($result && mysqli_num_rows($result) > 0) {
    $array = mysqli_fetch_assoc($result);

    // Regenerar ID de sesión para evitar fijación de sesión
    session_regenerate_id(true);

    $_SESSION["usuario"] = $array["username"];
    $_SESSION["tipo"]    = (int)$array["tipo"];
    $_SESSION["id"]      = (int)$array["id"];

    // Redirección controlada para cualquier rol válido
    $rolesPermitidos = [1, 5, 6];
    if (in_array($_SESSION["tipo"], $rolesPermitidos, true)) {
        header("Location: ./main.php");
        exit();
    } else {
        // Si existe otro tipo de usuario en la BD, evitar pantalla en blanco
        header("Location: ./main.php");
        exit();
    }
} else {
    header("Location: ./login.php?msg=1");
    exit();
}