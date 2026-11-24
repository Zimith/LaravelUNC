<?php
session_start();
if (!isset($_SESSION["usuario"], $_SESSION["id"])) {
    header("Location: ./login.php");
    exit();
}

$id = isset($_GET["id"]) ? (int)$_GET["id"] : 0;
if ($id <= 0) {
    header("Location: ./main.php");
    exit();
}

require_once __DIR__ . '/../Bibliotecas/dbProyecto.php';
require_once __DIR__ . '/../Bibliotecas/dbAvance.php';
require_once __DIR__ . '/../Bibliotecas/dbActividades.php';

$proyecto = new Proyecto();
if (!$proyecto->read($id)) {
    header("Location: ./main.php");
    exit();
}

$msg = isset($_GET['msg']) ? (string)$_GET['msg'] : '';
$class = isset($_GET['class']) ? (string)$_GET['class'] : 'alert alert-info';
$clasesPermitidas = ['alert alert-success', 'alert alert-danger', 'alert alert-warning', 'alert alert-info'];
if (!in_array($class, $clasesPermitidas, true)) {
    $class = 'alert alert-info';
}

$act = new Actividad();
$a = new Avance();
$avances = $a->readXProyecto($id);
if (!is_array($avances)) {
    $avances = [];
}
?>
<!DOCTYPE html>
<html lang="es">
   <head>
      <meta charset="utf-8">
      <meta http-equiv="X-UA-Compatible" content="IE=edge">
      <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
      <title>SiMEP - Avances Cargados</title>
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
                  </div>
               </div>
               <div class="col-md-12">
                  <div class="titlepage text_align_center">
                     <a class="btn btn-primary" style="color: white;" href="./proyectoTest.php?id=<?= $id ?>">Volver al proyecto</a>
                     <a class="btn btn-primary" style="color: white;" href="./avanceCarga.php?id=<?= $id ?>">Carga de Avances</a>
                     <a class="btn btn-primary" style="color: white;" href="./informesCargados.php?id=<?= $id ?>">Ver Informes de Avance</a>
                  </div>
               </div>

               <div class="col-md-10 offset-md-1">
                  <div id="request" class="main_form">
                     <div class="row">
                        <div class="col-md-12">
                           <label class="label">Cargas de avances realizadas</label>
                           <?php if (empty($avances)): ?>
                              <p>Aún no hay avances registrados para este proyecto.</p>
                           <?php else: ?>
                              <table class="table table-bordered">
                                 <thead>
                                    <tr>
                                       <th>Fecha</th>
                                       <th>Actividad</th>
                                       <th>Estado</th>
                                       <th>Observaciones</th>
                                       <th>Fuente de Verif.</th>
                                    </tr>
                                 </thead>
                                 <tbody>
                                    <?php foreach ($avances as $av): ?>
                                       <?php
                                          $fecha = (string)$av['fecha'];
                                          $f = strlen($fecha) >= 10 ? substr($fecha, 2, 8) : $fecha;
                                          $act->read((int)$av['actividad']);
                                          $actividadNombre = htmlspecialchars((string)$act->getNombre(), ENT_QUOTES, 'UTF-8');
                                          $estado = htmlspecialchars((string)$av['estado'], ENT_QUOTES, 'UTF-8');
                                          $observaciones = htmlspecialchars((string)$av['observaciones'], ENT_QUOTES, 'UTF-8');
                                          if ($av['estado'] === "No realizada" && !empty($av['justificacion'])) {
                                              $observaciones .= "<br><strong>Justificación:</strong><br>" . htmlspecialchars((string)$av['justificacion'], ENT_QUOTES, 'UTF-8');
                                          }
                                          $archivo = (string)$av['fuenteValidacion'];
                                          $rutaArchivo = "./fuenteValidacion/{$id}/" . rawurlencode($archivo);
                                       ?>
                                       <tr>
                                          <td><?= htmlspecialchars($f, ENT_QUOTES, 'UTF-8') ?></td>
                                          <td><?= $actividadNombre ?></td>
                                          <td><?= $estado ?></td>
                                          <td><?= $observaciones ?></td>
                                          <td>
                                             <?php if ($archivo !== ""): ?>
                                                <a href="<?= $rutaArchivo ?>" target="_blank" rel="noopener noreferrer">Archivo</a>
                                             <?php endif; ?>
                                          </td>
                                       </tr>
                                    <?php endforeach; ?>
                                 </tbody>
                              </table>
                           <?php endif; ?>
                        </div>
                     </div>
                  </div>
               </div>
            </div>
         </div>
      </div>

      <?php include __DIR__ . "/footer.php"; ?>
      <script src="js/jquery.min.js"></script>
      <script src="js/bootstrap.bundle.min.js"></script>
      <script src="js/custom.js"></script>
   </body>
</html>