<?php
	session_start();
	$UserType = $_SESSION["tipo"];
	if(isset($_SESSION["usuario"]) == FALSE){
		header("location:./login.php");
	}else{
		include ("../Bibliotecas/dbProyecto.php");
		include ("../Bibliotecas/dbObjetivoEspecifico.php");
		include ("../Bibliotecas/dbIndicador.php");
		include ("../Bibliotecas/dbActividades.php");
		
		$id = $_GET["id"];
		$proyecto = new Proyecto();
		$proyecto->read($id);
		$objEsp = new ObjetivoEspecifico();
		$listaOE = $objEsp->objetivosEspecificos($id);
		$indicador = new Indicador();
		$listaInd = $indicador->indicadoresXProyecto($id);
		$actividad = new Actividad();
		$listaAct = $actividad->actividadesXProyecto($id);
		
?>
<!DOCTYPE html>
<html lang="en">
   <head>
      <!-- basic -->
      <meta charset="utf-8">
      <meta http-equiv="X-UA-Compatible" content="IE=edge">
      <!-- mobile metas -->
      <meta name="viewport" content="width=device-width, initial-scale=1">
      <meta name="viewport" content="initial-scale=1, maximum-scale=1">
      <!-- site metas -->
      <title>SiMEP</title>
      <meta name="keywords" content="">
      <meta name="description" content="">
      <meta name="author" content="">
      <!-- bootstrap css -->
      <link rel="stylesheet" href="css/bootstrap.min.css">
      <!-- style css -->
      <link rel="stylesheet" href="css/style.css">
      <!-- Responsive-->
      <link rel="stylesheet" href="css/responsive.css">
      <!-- fevicon -->
      <link rel="icon" href="images/fevicon.png" type="image/gif" />
      <!-- Tweaks for older IEs-->
      <link rel="stylesheet" href="https://netdna.bootstrapcdn.com/font-awesome/4.0.3/css/font-awesome.css">
      <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script><![endif]-->
   </head>
   <!-- body -->
   <body class="main-layout">
      <!-- loader  -->
	  <?php //include "loader.php"; ?>
      <!-- end loader -->
      <!-- header -->
      <?php include "header.php";?>
      <!-- end header inner -->
      <!-- contact -->
      <div class="contact">
         <div class="container">
            <div class="row ">
               <div class="col-md-12">
                  <div class="titlepage text_align_center">
                     <h2><?php echo $proyecto->getTitulo();?></h2>
                  </div>
               </div>
               <div class="col-md-12">
					<div class="titlepage text_align_center">
						<button type="button" class="btn btn-primary"><a style="color: white;" href="<?php echo "main.php";?>">Volver a Proyectos</a></button>
						<button type="button" class="btn btn-primary"><a style="color: white;" href="<?php echo "avanceCarga.php?id=".$proyecto->getId();?>">Carga de Avances</a></button>
						<button type="button" class="btn btn-primary"><a style="color: white;" href="<?php if($UserType == 6) echo "avancesCargados2.php?id=".$proyecto->getId(); else echo "avancesCargados.php?id=".$proyecto->getId();?>">Ver Avances cargados</a></button>
						<button type="button" class="btn btn-primary"><a style="color: white;" href="<?php echo "informesCargados.php?id=".$proyecto->getId();?>">Ver Informes de Avance</a></button>
					</div>
               </div>
               <div class="col-md-10 offset-md-1">
                  <form id="request" class="main_form">
                     <div class="row">
                        <div class="col-md-12 ">
                           <label class="label">Objetivo General</label>
                           <p><strong><?php echo $proyecto->getObjetivoGeneral();?></strong></p>
                        </div>
                        <div class="col-md-12">
						   <label class="label">Objetivos Específicos</label>
                           <div>
								<ul type="square">
									<?php foreach($listaOE as $row){
										echo "<li>". $row[0] ."</li>";
									}
								?>
								</ul>
							</div>
                        </div>
                        <div class="col-md-12">
                           <label class="label">Indicadores</label>
                           <div>
								<ul type="square">
									<?php foreach($listaInd as $row){
											echo "<li>". $row[0] ."</li>";
										}
									?>
								</ul>
							</div>
                        </div>
                        <div class="col-md-12">
                           <label class="label">Actividades</label>
                           <div>
								<?php foreach($listaOE as $row){
									//echo "ACA";
									$listaAct2 = $actividad->actividadesXObjetivoEspecifico($row[1]);
									echo "<h4>".$row[0] ."</h4>";
									echo "<ol>";
									foreach($listaAct2 as $row2){
										//echo "<li><strong>". $row2[0] ."</strong><br><mark>Desde: ".$actividad->inicioHumano($row2[1])." - Hasta: ".$actividad->finHumano($row2[2])."<br><strong>Reprogramado:</strong><br>De: ".$actividad->inicioHumano($row2[3])." Hasta: ".$actividad->finHumano($row2[4])."</mark></li>";
										$tipo = $row2[6];
										$estado=$actividad->estado($row2[5]);
										if($estado=="Finalizada"){
											echo "<li><strong>". $row2[0] . "</strong> [Tipo: ".$tipo."]<br> <p style=\"color: darkgreen;\">Estado: " .$estado. "</p>";
										}elseif($estado==""){
											echo "<li><strong>". $row2[0] . "</strong> [Tipo: ".$tipo."]<br><p style=\"color: red;\">Estado: No informado ";
											echo "<button class=\"send_btn2\"><a href='https://simep.com.ar/avanceCarga.php?id=".$id."&act=".$row2[5]."'>Cargar avance</a></button></p>";
										}
										else{
											echo "<li><strong>". $row2[0] . "</strong> [Tipo: ".$tipo."]<br><p style=\"color: blue;\">Estado: " .$estado. " ";
											echo "<button class=\"send_btn2\"><a href='https://simep.com.ar/avanceCarga.php?id=".$id."&act=".$row2[5]."'>Cargar avance</a></button></p>";
										}
										echo "Desde: ".$actividad->inicioHumano($row2[3])." Hasta: ".$actividad->finHumano($row2[4])."</li>";
									}
									echo "</ol>";
								}
								?>
							</div>
                        </div>
                        <!--<div class="col-md-12">
                           <label class="label">Cronograma</label>
						   <img src=<?php echo "./cronogramas/".$id.".jpg"?> alt="Cronograma">
                        </div>-->
                        <div class="col-md-12">
                           <label class="label">Recursos Humanos</label>
                           <p><?php echo $proyecto->getRecursosHumanos();?></p>
                        </div>
						<div class="col-md-12">
                           <label class="label">Responsable</label>
                           <p><?php echo $proyecto->getResponsable();?></p>
                        </div>
						<div class="col-md-12">
                           <label class="label">Impacto</label>
                           <p><?php echo $proyecto->getImpacto();?></p> <br>
                        </div>
                        <!--<div class="col-md-12">
                           <button class="send_btn">Ingresar</button><br><br>
                        </div>-->
                     </div>
               </form>
               </div>
            </div>
         </div>
      </div>
      <!-- contact -->
      <!--  footer -->
      <?php include "footer.php";?>
      <!-- end footer -->
      <!-- Javascript files-->
      <script src="js/jquery.min.js"></script>
      <script src="js/bootstrap.bundle.min.js"></script>
      <!-- sidebar -->
      <script src="js/custom.js"></script>
   </body>
</html>
<?php
	}
?>