<?php
session_start();
if (!isset($_SESSION["usuario"], $_SESSION["id"])) {
    header("Location: ./login.php");
    exit();
}

$idUser = (int)$_SESSION["id"];
require_once __DIR__ . '/../Bibliotecas/dbUsuario.php';

$user = new Usuario();
$user->read($idUser);
$proyectos = $user->proyectos($idUser);

// Asegurar que $proyectos sea siempre un array iterable para evitar TypeError en PHP 8.5
if (!is_array($proyectos)) {
    $proyectos = [];
}
?>
<!DOCTYPE html>
<html lang="es">
   <head>
      <meta charset="utf-8">
      <meta http-equiv="X-UA-Compatible" content="IE=edge">
      <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
      <title>SiMEP</title>
      <link rel="stylesheet" href="css/bootstrap.min.css">
      <link rel="stylesheet" href="css/style.css">
      <link rel="stylesheet" href="css/responsive.css">
      <link rel="icon" href="images/fevicon.png" type="image/gif" />
      <link rel="stylesheet" href="https://netdna.bootstrapcdn.com/font-awesome/4.0.3/css/font-awesome.css">
   </head>
   <body class="main-layout">
      <?php include __DIR__ . "/header.php"; ?>

      <div class="guarantee">
         <div class="container">
            <div class="row">
               <div class="col-md-12">
                  <div class="titlepage text_align_center">
                     <h2>Bienvenido <span class="blue_light"><?= htmlspecialchars($user->getNombreCompleto(), ENT_QUOTES, 'UTF-8') ?></span></h2>
                     <br>
                     <a href="logout.php" class="btn btn-warning">Salir</a>
                     <br><br>
                     <p>Eres responsable de los siguientes proyectos</p>
                  </div>
               </div>
            </div>

            <div class="row">
               <?php if (empty($proyectos)): ?>
                  <div class="col-md-12 text_align_center">
                     <p>Actualmente no tienes proyectos asignados.</p>
                  </div>
               <?php else: ?>
                  <?php foreach ($proyectos as $proy): ?>
                     <?php
                        $idProyecto = (int)$proy['id'];
                        $tituloProyecto = htmlspecialchars((string)$proy['titulo'], ENT_QUOTES, 'UTF-8');
                     ?>
                     <div class="col-lg-3 col-md-6">
                        <div id="ho_co" class="guarantee-box_main">
                           <div class="guarantee-box text_align_center">
                              <i>
                                 <a href="proyectoTest.php?id=<?= $idProyecto ?>">
                                    <img src="./images/proyectos.png" width="150px" alt="Proyecto">
                                 </a>
                              </i>
                              <h3>
                                 <a href="proyectoTest.php?id=<?= $idProyecto ?>"><?= $tituloProyecto ?></a>
                              </h3>
                           </div>
                           <a class="read_more" href="proyectoTest.php?id=<?= $idProyecto ?>">Ver más</a>
                        </div>
                     </div>
                  <?php endforeach; ?>
               <?php endif; ?>
            </div>
         </div>
      </div>

      <?php include __DIR__ . "/footer.php"; ?>
      <script src="js/jquery.min.js"></script>
      <script src="js/bootstrap.bundle.min.js"></script>
      <script src="js/custom.js"></script>
   </body>
</html>