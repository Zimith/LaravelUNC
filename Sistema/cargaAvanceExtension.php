<?php
session_start();
if (!isset($_SESSION["usuario"], $_SESSION["id"])) {
    http_response_code(403);
    exit();
}

$elegido = isset($_POST["elegido"]) ? trim((string)$_POST["elegido"]) : '';

if ($elegido === 'Finalizada'): ?>
    <div class="form-group">
        <label for="inicio">Fecha de inicio</label>
        <input type="date" class="form-control" id="inicio" name="inicio">
        <label for="fin">Fecha de finalización</label>
        <input type="date" class="form-control" id="fin" name="fin">
    </div>
<?php elseif ($elegido === 'En ejecución'): ?>
    <div class="form-group">
        <label for="inicio">Fecha de inicio</label>
        <input type="date" class="form-control" id="inicio" name="inicio">
    </div>
<?php elseif ($elegido === 'No realizada'): ?>
    <div class="form-group">
        <label class="label" for="just">Justificación (*)</label>
        <textarea class="login" placeholder="Justificación de por qué no fue realizada la actividad" name="just" id="just" required></textarea>
    </div>
    <script>
        document.getElementById("fuenteV").required = false;
        document.getElementById("labelFV").innerHTML = "Fuente de verificación (Archivo jpg, pdf, zip o rar)";
    </script>
<?php endif; ?>