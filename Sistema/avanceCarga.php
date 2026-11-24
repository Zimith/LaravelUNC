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

$act = isset($_GET['act']) ? (string)$_GET['act'] : '';
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
      <title>SiMEP - Carga de Avances</title>
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
                     <h3><br><strong>Carga de avance por Actividades</strong></h3><br>
                     <a class="btn btn-primary" style="color: white;" href="./proyectoTest.php?id=<?= $idProy ?>">Volver al proyecto</a>
                     <a class="btn btn-primary" style="color: white;" href="./avanceCargaMultiple.php?id=<?= $idProy ?>">Cargar varias actividades juntas</a>
                     <a class="btn btn-primary" style="color: white;" href="./informesCargados.php?id=<?= $idProy ?>">Ver Informes de Avance</a>
                     <a class="btn btn-primary" style="color: white;" href="https://drive.google.com/file/d/1sZOy_44toI55ZY4FU8RDuZnIWN2PxwmD/view?usp=sharing" target="_blank" rel="noopener noreferrer">Informe 2do Taller</a>
                  </div>
               </div>

               <div class="col-md-10 offset-md-1">
                  <form id="request" class="main_form" action="cargaActividadSimple.php" method="post" enctype="multipart/form-data">
                     <input type="hidden" value="<?= $idProy ?>" name="idProy" id="idProy">
                     <div class="row">
                        <div class="col-md-12">
                           <label class="label" for="actividad">Seleccione una actividad (*)</label>
                           <select class="form-control" name="actividad" id="actividad" required>
                              <option value="">-- Seleccione una actividad --</option>
                              <?php foreach ($listaActividades as $item): ?>
                                 <?php
                                    $idItem = (string)$item['id'];
                                    $selected = ($act !== '' && $act === $idItem) ? 'selected' : '';
                                 ?>
                                 <option value="<?= htmlspecialchars($idItem, ENT_QUOTES, 'UTF-8') ?>" <?= $selected ?>>
                                    <?= htmlspecialchars((string)$item['nombre'], ENT_QUOTES, 'UTF-8') ?>
                                 </option>
                              <?php endforeach; ?>
                           </select>

                           <div id="extension2" name="extension2"></div>

                           <label class="label" for="est">Estado de la Actividad (*)</label>
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
            function cargarExtensionEstado(elegido) {
               if (!elegido) {
                  $("#extension").html("");
                  return;
               }
               $.post("cargaAvanceExtension.php", { elegido: elegido }, function(data) {
                  $("#extension").html(data);
               });
            }

            function cargarExtensionActividad(elegido2) {
               if (!elegido2) {
                  $("#extension2").html("");
                  return;
               }
               $.post("cargaAvanceExtension2.php", { elegido2: elegido2 }, function(data) {
                  $("#extension2").html(data);
               });
            }

            $("#est").on("change", function() {
               cargarExtensionEstado($(this).val());
            });

            $("#actividad").on("change", function() {
               cargarExtensionActividad($(this).val());
            });

            // Si ya vino una actividad preseleccionada por URL (?act=3), cargar su detalle al abrir
            if ($("#actividad").val()) {
               cargarExtensionActividad($("#actividad").val());
            }
         });
      </script>
   </body>
</html>