<?php
session_start();
if (!isset($_SESSION["usuario"], $_SESSION["id"])) {
    header("Location: ./login.php");
    exit();
}

$TypeUser = isset($_SESSION["tipo"]) ? (int)$_SESSION["tipo"] : 0;
if ($TypeUser === 6) {
    http_response_code(403);
    die("Acción no permitida.");
}

$idProy = isset($_GET["id"]) ? (int)$_GET["id"] : 0;
if ($idProy <= 0) {
    header("Location: ./main.php");
    exit();
}

require_once __DIR__ . '/../Bibliotecas/dbProyecto.php';
$proyecto = new Proyecto();
if (!$proyecto->read($idProy)) {
    header("Location: ./main.php");
    exit();
}

$listaActividades = $proyecto->obtenerActividadesLista();

$msg = isset($_GET['msg']) ? (string)$_GET['msg'] : '';
$class = isset($_GET['class']) ? (string)$_GET['class'] : 'alert alert-info';
$clasesPermitidas = ['alert alert-success', 'alert alert-danger', 'alert alert-warning', 'alert alert-info'];
if (!in_array($class, $clasesPermitidas, true)) {
    $class = 'alert alert-info';
}
?>
<!DOCTYPE html>
<html lang="es">
   <head>
      <meta charset="utf-8">
      <meta http-equiv="X-UA-Compatible" content="IE=edge">
      <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
      <title>SiMEP - Carga Múltiple de Avances</title>
      <link rel="stylesheet" href="css/bootstrap.min.css">
      <link rel="stylesheet" href="css/style.css">
      <link rel="stylesheet" href="css/responsive.css">
      <link rel="icon" href="images/fevicon.png" type="image/gif" />
      <link rel="stylesheet" href="https://netdna.bootstrapcdn.com/font-awesome/4.0.3/css/font-awesome.css">
   </head>
   <body class="main-layout">
      <?php include __DIR__ . "/header.php"; ?>

      <div class="contact">
         <div class="container">
            <div class="row">
               <div class="col-md-12">
                  <div class="titlepage text_align_center">
                     <h2><?= htmlspecialchars((string)$proyecto->getTitulo(), ENT_QUOTES, 'UTF-8') ?></h2>
                     <?php if ($msg !== ""): ?>
                        <div class="<?= $class ?>" role="alert"><?= htmlspecialchars($msg, ENT_QUOTES, 'UTF-8') ?></div>
                     <?php endif; ?>
                     <h3><br><strong>Utilizar solo cuando una fuente de validación corresponde para varias actividades</strong></h3><br>
                     <a class="btn btn-primary" style="color: white;" href="./proyectoTest.php?id=<?= $idProy ?>">Volver al proyecto</a>
                     <a class="btn btn-primary" style="color: white;" href="./avanceCarga.php?id=<?= $idProy ?>">Cargar una actividad individual</a>
                  </div>
               </div>

               <div class="col-md-10 offset-md-1">
                  <form id="request" class="main_form" action="cargaActividadMultiple.php" method="post" enctype="multipart/form-data">
                     <input type="hidden" value="<?= $idProy ?>" name="idProy" id="idProy">
                     <div class="row">
                        <div class="col-md-12">
                           <label class="label">Seleccione las actividades a informar (*)</label>
                           <div class="form-group" style="max-height: 260px; overflow-y: auto; border: 1px solid #ced4da; padding: 12px; border-radius: 4px; background: #fff;">
                              <?php if (empty($listaActividades)): ?>
                                 <p>No hay actividades disponibles para este proyecto.</p>
                              <?php else: ?>
                                 <?php foreach ($listaActividades as $item): ?>
                                    <div class="form-check">
                                       <input class="form-check-input" type="checkbox" name="act[]" id="act_<?= (int)$item['id'] ?>" value="<?= (int)$item['id'] ?>">
                                       <label class="form-check-label" for="act_<?= (int)$item['id'] ?>">
                                          <?= htmlspecialchars((string)$item['nombre'], ENT_QUOTES, 'UTF-8') ?>
                                       </label>
                                    </div>
                                 <?php endforeach; ?>
                              <?php endif; ?>
                           </div>

                           <label class="label" for="est">Estado de las Actividades (*)</label>
                           <select class="form-control" name="est" id="est" required>
                              <option value="">-- Seleccione un estado --</option>
                              <option value="En ejecución">En ejecución</option>
                              <option value="Finalizada">Finalizada</option>
                              <option value="Continua">Continua</option>
                              <option value="No realizada">No realizada</option>
                           </select>

                           <label class="label" for="obs">Observaciones (opcional)</label>
                           <textarea class="login" placeholder="Puede agregar más información sobre el estado de la Actividad" name="obs" id="obs"></textarea>

                           <div id="extension" name="extension"></div>

                           <label class="label" name="labelFV" id="labelFV" for="fuenteV">Fuente de verificación (Archivo jpg, pdf, zip o rar) (*)</label>
                           <input type="file" class="form-control" id="fuenteV" name="fuenteV" required>
                        </div>

                        <div class="col-md-12">
                           <label class="label label-danger">(*) Datos requeridos</label>
                        </div>
                        <div class="col-md-12">
                           <button type="submit" class="send_btn">Informar</button><br><br>
                        </div>
                     </div>
                  </form>
               </div>
            </div>
         </div>
      </div>

      <?php include __DIR__ . "/footer.php"; ?>
      <script src="js/jquery.min.js"></script>
      <script src="js/bootstrap.bundle.min.js"></script>
      <script src="js/custom.js"></script>
      <script>
         $(document).ready(function() {
            $("#est").on("change", function() {
               const elegido = $(this).val();
               if (!elegido) {
                  $("#extension").html("");
                  return;
               }
               $.post("cargaAvanceExtension.php", { elegido: elegido }, function(data) {
                  $("#extension").html(data);
               });
            });
         });
      </script>
   </body>
</html>