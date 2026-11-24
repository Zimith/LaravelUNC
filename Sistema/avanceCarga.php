<?php
	session_start();
	$TypeUser = $_SESSION["tipo"];
	if(isset($_SESSION["usuario"]) == FALSE){
		header("location:./login.php");
	}elseif($TypeUser == 6){
		echo "Accion no permitida";
	}else{
		$idUser = $_SESSION["id"];
		//include_once("../Bibliotecas/dbUsuario.php");
		include_once("../Bibliotecas/dbProyecto.php");
		$idProy = $_GET["id"];
		$act = (isset($_GET['act']))?$_GET['act']:'';
		$msg = (isset($_GET['msg']))?$_GET['msg']:'';
		$class = (isset($_GET['class']))?$_GET['class']:'';
		$proyecto = new Proyecto();
		$proyecto->read($idProy);
	
		
		/*$user = new Usuario();
		$user->read($idUser);
		$proyectos = $user->proyectos($idUser);*/
		//print_r($proyectos);
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
		<script src="https://code.jquery.com/jquery-3.2.1.js"></script>
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
							<?php 
								if($msg != "") 
									echo "<div class='".$class."' role='alert'>".$msg."</div>";
							?>
							<h3><br><strong>Carga de avance por Actividades</strong></h3><br>
							<button type="button" class="btn btn-primary" id="proyecto" name="proyecto"><a style="color: white;" href="<?php echo "./proyectoTest.php?id=".$idProy;?>">Volver al proyecto</a></button>
							<button type="button" class="btn btn-primary" id="multicarga" name="multicarga"><a style="color: white;" href="<?php echo "./avanceCargaMultiple.php?id=".$idProy;?>">Cargar varias actividades juntas</a></button>
							<button type="button" class="btn btn-primary"><a style="color: white;" href="<?php echo "informesCargados.php?id=".$proyecto->getId();?>">Ver Informes de Avance</a></button>
							<button type="button" class="btn btn-primary"><a style="color: white;" href=https://drive.google.com/file/d/1sZOy_44toI55ZY4FU8RDuZnIWN2PxwmD/view?usp=sharing">Informe 2do Taller</a></button>
						</div>
					</div>
					<div class="col-md-10 offset-md-1">
						<form id="request" class="main_form" action="cargaActividadSimple.php" method="post" enctype="multipart/form-data">
							<input type="hidden" value="<?php echo $idProy;?>" name="idProy" id ="idProy"></input>
							<div class="row">
								<div class="col-md-12 ">
									<label class="label">Seleccione una actividad (*)</label>
									<?php $proyecto->actividades_S();?>
									<script>
										// Ejecución inmediata al cargar el DOM
										document.addEventListener("DOMContentLoaded", () => {
											
											// 2. Traemos el valor desde PHP de forma segura
											const valorBuscado = <?php echo json_encode($act); ?>;

											// 3. Si la variable no está vacía, asignamos el valor directamente al select
											if (valorBuscado) {
												const select = document.getElementById("actividad");
												select.value = valorBuscado;
											}
										});
									</script>
									<div id="extension2" name="extension2"></div>
									<label class="label">Estado de la Actividad (*)</label>
									<select class='form-control' name="est" id="est" required>   }
										<!--<option value="No comenzada">No comenzada</option>-->
										<option value="En ejecución">En ejecución</option>
										<option value="Finalizada">Finalizada</option>
										<option value="Continua">Continua</option>
										<option value="No realizada">No realizada</option>
									</select>
									<label class="label">Observaciones (opcional)</label>
									<textarea class="login" placeholder="Puede agregar mas información sobre el estado de la Actividad" type="type" name="obs" id="obs"></textarea>
									<!--Se carga contenido en función de la option seleccionada en el select descripcion con jqery-->
									<div id="extension" name="extension"></div>
									<label class="label" name='labelFV' id='labelFV'>Fuente de verificación (Archivo jpg, pdf, zip o rar) (*)</label>
									<input type='file' class='form-control' id='fuenteV' name='fuenteV' required></input>
								</div>
								<div class="col-md-12 ">
									<label class="label label-danger">(*) Datos requridos</label>
								</div>
                        		<div class="col-md-12">
									<button class="send_btn">Informar</button><br><br>
								</div>
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
		
			
		<script language="javascript">
			$(document).ready(function(){
				$("#est").on('change', function () {
					$("#est option:selected").each(function () {
						elegido=$(this).val();
						$.post("cargaAvanceExtension.php", { 
							elegido: elegido 
						}, function(data){
							$("#extension").html(data);
						});			
					});
			   });
			   $("#actividad").on('change', function () {
					$("#actividad :selected").each(function () {
						elegido2=$(this).val();
						$.post("cargaAvanceExtension2.php", { 
							elegido2: elegido2 
						}, function(data){
							$("#extension2").html(data);
						});			
					});
			   });
			});	
		</script>
		<script src="js/jquery.min.js"></script>
		<script src="js/bootstrap.bundle.min.js"></script>
		<!-- sidebar -->
		<script src="js/custom.js"></script>
	</body>
</html>
<?php
}
?>