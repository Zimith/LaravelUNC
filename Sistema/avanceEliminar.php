<?php
session_start();
if (!isset($_SESSION["usuario"], $_SESSION["id"])) {
    header("Location: ./login.php");
    exit();
}

$TypeUser = isset($_SESSION["tipo"]) ? (int)$_SESSION["tipo"] : 0;
if ($TypeUser !== 6) {
    http_response_code(403);
    die("Acción no permitida.");
}

$idAv = isset($_GET["idAv"]) ? (int)$_GET["idAv"] : 0;
$idP = isset($_GET["idP"]) ? (int)$_GET["idP"] : 0;

if ($idAv <= 0 || $idP <= 0) {
    header("Location: ./main.php");
    exit();
}

require_once __DIR__ . '/../Bibliotecas/dbAvance.php';
$a = new Avance();

if ($a->read($idAv)) {
    $archivo = basename((string)$a->getFuenteValidacion());
    $res = $a->delete();

    if ($res) {
        if ($archivo !== '') {
            $rutaArchivo = __DIR__ . '/fuenteValidacion/' . $idP . '/' . $archivo;
            if (is_file($rutaArchivo)) {
                unlink($rutaArchivo);
            }
        }
        $message = "Avance eliminado con éxito";
        $class = "alert alert-success";
    } else {
        $message = "No se pudo eliminar el avance";
        $class = "alert alert-danger";
    }
} else {
    $message = "El avance indicado no existe";
    $class = "alert alert-warning";
}

header("Location: ./avancesCargados2.php?id={$idP}&msg=" . urlencode($message) . "&class=" . urlencode($class));
exit();