<?php
session_start();
if (!isset($_SESSION["usuario"], $_SESSION["id"])) {
    header("Location: ./login.php");
    exit();
}

$UserType = isset($_SESSION["tipo"]) ? (int)$_SESSION["tipo"] : 0;
$id = isset($_GET["id"]) ? (int)$_GET["id"] : 0;

if ($id <= 0) {
    header("Location: ./main.php");
    exit();
}

require_once __DIR__ . '/../Bibliotecas/dbProyecto.php';
require_once __DIR__ . '/../Bibliotecas/dbObjetivoEspecifico.php';
require_once __DIR__ . '/../Bibliotecas/dbIndicador.php';
require_once __DIR__ . '/../Bibliotecas/dbActividades.php';

$proyecto = new Proyecto();
if (!$proyecto->read($id)) {
    header("Location: ./main.php");
    exit();
}

$objEsp = new ObjetivoEspecifico();
$listaOE = $objEsp->objetivosEspecificos($id);
if (!is_array($listaOE)) {
    $listaOE = [];
}

$indicador = new Indicador();
$listaInd = $indicador->indicadoresXProyecto($id);
if (!is_array($listaInd)) {
    $listaInd = [];
}

$actividad = new Actividad();
$urlVerAvances = ($UserType === 6)
    ? "avancesCargados2.php?id=" . $proyecto->getId()
    : "avancesCargados.php?id=" . $proyecto->getId();
?>
<!DOCTYPE html>
<html lang="es">
   <head>
      <meta charset="utf-8">
      <meta http-equiv="X-UA-Compatible" content="IE=edge">
      <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
      <title>SiMEP - <?= htmlspecialchars((string)$proyecto->getTitulo(), ENT_QUOTES, 'UTF-8') ?></title>
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
                  </div>
               </div>
               <div class="col-md-12">
                  <div class="titlepage text_align_center">
                     <a class="btn btn-primary" style="color: white;" href="main.php">Volver a Proyectos</a>
                     <a class="btn btn-primary" style="color: white;" href="avanceCarga.php?id=<?= (int)$proyecto->getId() ?>">Carga de Avances</a>
                     <a class="btn btn-primary" style="color: white;" href="<?= $urlVerAvances ?>">Ver Avances cargados</a>
                     <a class="btn btn-primary" style="color: white;" href="informesCargados.php?id=<?= (int)$proyecto->getId() ?>">Ver Informes de Avance</a>
                  </div>
               </div>

               <div class="col-md-10 offset-md-1">
                  <div id="request" class="main_form">
                     <div class="row">
                        <div class="col-md-12">
                           <label class="label">Objetivo General</label>
                           <p><strong><?= htmlspecialchars((string)$proyecto->getObjetivoGeneral(), ENT_QUOTES, 'UTF-8') ?></strong></p>
                        </div>

                        <div class="col-md-12">
                           <label class="label">Objetivos Específicos</label>
                           <div>
                              <?php if (empty($listaOE)): ?>
                                 <p>No hay objetivos específicos cargados.</p>
                              <?php else: ?>
                                 <ul type="square">
                                    <?php foreach ($listaOE as $row): ?>
                                       <li><?= htmlspecialchars((string)$row[0], ENT_QUOTES, 'UTF-8') ?></li>
                                    <?php endforeach; ?>
                                 </ul>
                              <?php endif; ?>
                           </div>
                        </div>

                        <div class="col-md-12">
                           <label class="label">Indicadores</label>
                           <div>
                              <?php if (empty($listaInd)): ?>
                                 <p>No hay indicadores cargados.</p>
                              <?php else: ?>
                                 <ul type="square">
                                    <?php foreach ($listaInd as $row): ?>
                                       <li><?= htmlspecialchars((string)$row[0], ENT_QUOTES, 'UTF-8') ?></li>
                                    <?php endforeach; ?>
                                 </ul>
                              <?php endif; ?>
                           </div>
                        </div>

                        <div class="col-md-12">
                           <label class="label">Actividades</label>
                           <div>
                              <?php if (empty($listaOE)): ?>
                                 <p>No hay actividades registradas para este proyecto.</p>
                              <?php else: ?>
                                 <?php foreach ($listaOE as $row): ?>
                                    <?php
                                       $idObjEsp = (int)$row[1];
                                       $listaAct2 = $actividad->actividadesXObjetivoEspecifico($idObjEsp);
                                       if (!is_array($listaAct2)) {
                                           $listaAct2 = [];
                                       }
                                    ?>
                                    <h4><?= htmlspecialchars((string)$row[0], ENT_QUOTES, 'UTF-8') ?></h4>
                                    <ol>
                                       <?php foreach ($listaAct2 as $row2): ?>
                                          <?php
                                             $nombreAct = htmlspecialchars((string)$row2[0], ENT_QUOTES, 'UTF-8');
                                             $tipoAct = htmlspecialchars((string)$row2[6], ENT_QUOTES, 'UTF-8');
                                             $idAct = (int)$row2[5];
                                             $estado = $actividad->estado($idAct);
                                             $urlCargar = "avanceCarga.php?id={$id}&act={$idAct}";
                                          ?>
                                          <li>
                                             <strong><?= $nombreAct ?></strong> [Tipo: <?= $tipoAct ?>]<br>
                                             <?php if ($estado === "Finalizada"): ?>
                                                <p style="color: darkgreen;">Estado: <?= htmlspecialchars($estado, ENT_QUOTES, 'UTF-8') ?></p>
                                             <?php elseif (empty($estado)): ?>
                                                <p style="color: red;">
                                                   Estado: No informado
                                                   <a class="send_btn2" href="<?= $urlCargar ?>">Cargar avance</a>
                                                </p>
                                             <?php else: ?>
                                                <p style="color: blue;">
                                                   Estado: <?= htmlspecialchars($estado, ENT_QUOTES, 'UTF-8') ?>
                                                   <a class="send_btn2" href="<?= $urlCargar ?>">Cargar avance</a>
                                                </p>
                                             <?php endif; ?>
                                             Desde: <?= htmlspecialchars($actividad->inicioHumano($row2[3]), ENT_QUOTES, 'UTF-8') ?>
                                             Hasta: <?= htmlspecialchars($actividad->finHumano($row2[4]), ENT_QUOTES, 'UTF-8') ?>
                                          </li>
                                       <?php endforeach; ?>
                                    </ol>
                                 <?php endforeach; ?>
                              <?php endif; ?>
                           </div>
                        </div>

                        <div class="col-md-12">
                           <label class="label">Recursos Humanos</label>
                           <p><?= htmlspecialchars((string)$proyecto->getRecursosHumanos(), ENT_QUOTES, 'UTF-8') ?></p>
                        </div>

                        <div class="col-md-12">
                           <label class="label">Responsable</label>
                           <p><?= htmlspecialchars((string)$proyecto->getResponsable(), ENT_QUOTES, 'UTF-8') ?></p>
                        </div>

                        <div class="col-md-12">
                           <label class="label">Impacto</label>
                           <p><?= htmlspecialchars((string)$proyecto->getImpacto(), ENT_QUOTES, 'UTF-8') ?></p><br>
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