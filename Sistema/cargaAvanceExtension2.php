<?php
session_start();
if (!isset($_SESSION["usuario"], $_SESSION["id"])) {
    http_response_code(403);
    exit();
}

$idAct = isset($_POST["elegido2"]) ? (int)$_POST["elegido2"] : 0;
if ($idAct <= 0) {
    exit();
}

require_once __DIR__ . '/../Bibliotecas/dbActividades.php';
$act = new Actividad();
if (!$act->read($idAct)) {
    exit();
}
?>
<div class="form-group">
    <label for="cronograma_inicio">Fecha de inicio según cronograma</label>
    <input type="text" class="form-control" id="cronograma_inicio" name="cronograma_inicio" value="<?= htmlspecialchars($act->getInicioHumano2(), ENT_QUOTES, 'UTF-8') ?>" readonly>
    <label for="cronograma_fin">Fecha de finalización según cronograma</label>
    <input type="text" class="form-control" id="cronograma_fin" name="cronograma_fin" value="<?= htmlspecialchars($act->getFinHumano2(), ENT_QUOTES, 'UTF-8') ?>" readonly>
</div>