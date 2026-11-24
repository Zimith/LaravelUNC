<?php
session_start();
if (!isset($_SESSION["usuario"], $_SESSION["id"])) {
    header("Location: ./login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST' || empty($_POST)) {
    header("Location: ./main.php");
    exit();
}

$idUser = (int)$_SESSION["id"];
$proy = isset($_POST["idProy"]) ? (int)$_POST["idProy"] : 0;
$actividad = isset($_POST["actividad"]) ? (int)$_POST["actividad"] : 0;
$estado = isset($_POST["est"]) ? trim((string)$_POST["est"]) : '';
$observaciones = isset($_POST["obs"]) ? trim((string)$_POST["obs"]) : '';
$fecha = date("Y-m-d");

if ($proy <= 0 || $actividad <= 0 || $estado === '') {
    header("Location: ./main.php");
    exit();
}

$justificacion = "";
$inicio = "";
$fin = "";

if ($estado === "Finalizada") {
    $inicio = isset($_POST['inicio']) ? trim((string)$_POST['inicio']) : "";
    $fin = isset($_POST['fin']) ? trim((string)$_POST['fin']) : "";
} elseif ($estado === "En ejecución") {
    $inicio = isset($_POST['inicio']) ? trim((string)$_POST['inicio']) : "";
} elseif ($estado === "No realizada") {
    $justificacion = isset($_POST['just']) ? trim((string)$_POST['just']) : "";
}

// 1. Validar y procesar primero el archivo adjunto antes de insertar en BD
$nombreArchivoFinal = "";
$allowedExtensions = ['jpg', 'jpeg', 'png', 'pdf', 'docx', 'doc', 'xls', 'xlsx', 'zip', 'rar'];

if (isset($_FILES['fuenteV']) && $_FILES['fuenteV']['error'] === UPLOAD_ERR_OK) {
    $fileTmpPath = $_FILES['fuenteV']['tmp_name'];
    $originalName = basename((string)$_FILES['fuenteV']['name']);
    $fileExtension = strtolower(pathinfo($originalName, PATHINFO_EXTENSION));

    if (!in_array($fileExtension, $allowedExtensions, true)) {
        $msg = urlencode("Error: extensión no permitida. Use: " . implode(', ', $allowedExtensions));
        header("Location: ./avanceCarga.php?id={$proy}&msg={$msg}&class=" . urlencode("alert alert-danger"));
        exit();
    }

    // Corregido: usa $proy (antes usaba $idProy indefinido) y prefijo único para evitar sobrescrituras
    $uploadFileDir = __DIR__ . '/fuenteValidacion/' . $proy . '/';
    if (!is_dir($uploadFileDir)) {
        mkdir($uploadFileDir, 0755, true);
    }

    $nombreSeguro = preg_replace('/[^a-zA-Z0-9._-]/', '_', $originalName);
    $nombreArchivoFinal = date('Ymd_His') . '_' . $nombreSeguro;
    $destPath = $uploadFileDir . $nombreArchivoFinal;

    if (!move_uploaded_file($fileTmpPath, $destPath)) {
        $msg = urlencode("Error al guardar el archivo en el servidor.");
        header("Location: ./avanceCarga.php?id={$proy}&msg={$msg}&class=" . urlencode("alert alert-danger"));
        exit();
    }
}

// 2. Registrar el avance en la base de datos
require_once __DIR__ . '/../Bibliotecas/dbAvance.php';
$a = new Avance();
$a->setProyecto($proy);
$a->setActividad($actividad);
$a->setFecha($fecha);
$a->setEstado($estado);
$a->setObservaciones($observaciones);
$a->setJustificacion($justificacion);
$a->setUsuario($idUser);
$a->setInicio($inicio);
$a->setFin($fin);
$a->setFuenteValidacion($nombreArchivoFinal);

if ($a->create()) {
    $message = "Avance registrado con éxito";
    $class = "alert alert-success";
} else {
    $message = "No se pudo registrar el avance en la base de datos";
    $class = "alert alert-danger";
}

header("Location: ./avanceCarga.php?id={$proy}&msg=" . urlencode($message) . "&class=" . urlencode($class));
exit();