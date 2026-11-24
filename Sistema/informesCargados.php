<?php
	session_start();
	if(isset($_SESSION["usuario"]) == FALSE){
		header("location:./login.php");
	}else{
		include ("../Bibliotecas/dbProyecto.php");
		$id = $_GET["id"];
		/*$msg = (isset($_GET['msg']))?$_GET['msg']:'';
		$class = (isset($_GET['class']))?$_GET['class']:'';*/
		$proyecto = new Proyecto();
		$proyecto->read($id);
		
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
					 <?php 
						if($msg != "") 
							echo "<div class='".$class."' role='alert'>".$msg."</div>";
					  ?>
                  </div>
               </div>
               <div class="col-md-12">
					<div class="titlepage text_align_center">
						<button type="button" class="btn btn-primary" id="proyecto" name="proyecto"><a style="color: white;" href="<?php echo "./proyectoTest.php?id=".$id;?>">Volver al proyecto</a></button>
						<button type="button" class="btn btn-primary"><a style="color: white;" href="<?php echo "avanceCarga.php?id=".$proyecto->getId();?>">Carga de Avances</a></button>
						<button type="button" class="btn btn-primary"><a style="color: white;" href="">Ver Informes de Avance</a></button>
					</div>
               </div>
               <div class="col-md-10 offset-md-1">
                  <form id="request" class="main_form">
                     <div class="row">
                        <div class="col-md-12 ">
                           <label class="label">Informe de avances de UEPEP</label>
							<?php 
								include_once "../Bibliotecas/dbInforme.php";
								
								$i = new Informe();
								$informe = $i->read($id);
								echo $i->getInforme();
							?>
							<p><br>
							</p>
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
      <script src="js/jquery.min.js"></script>
      <script src="js/bootstrap.bundle.min.js"></script>
      <!-- sidebar -->
      <script src="js/custom.js"></script>
   </body>
</html>
<?php
	}
?>