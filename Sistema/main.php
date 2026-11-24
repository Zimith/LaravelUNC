<?php
	session_start();
	if(isset($_SESSION["usuario"]) == FALSE){
		header("location:./login.php");
	}else{
		$idUser= $_SESSION["id"];
		include_once("../Bibliotecas/dbUsuario.php");
	
		$user = new Usuario();
		$user->read($idUser);
		$proyectos = $user->proyectos($idUser);
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
      <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script><![endif]-->
   </head>
   <!-- body -->
   <body class="main-layout">
      <!-- header -->
      <?php include "header.php";?>
      <!-- end header inner -->

      <div class="guarantee">
         <div class="container">
            <div class="row">
               <div class="col-md-12">
                  <div class="titlepage text_align_center">
                     <h2>Bienvenido <span class="blue_light"><?php echo $user->getNombreCompleto();?></span></h2>
					 <br><button  type="button" class="btn btn-warning"><a href="logout.php">Salir</a></button><br>
                     <p> Eres responsable de los siguientes proyectos </p>
                  </div>
               </div>
            </div>
			<div class="row">
            <?php
				$nElem = sizeof($proyectos);
				$mod = fmod($nElem, 4);
				$div = intdiv($nElem, 4);
				if ($nElem >= 4){
					$n = -5;
					for ($i = 1; $i <= $div; $i++) {	
						for ($ii = 1; $ii <= 4; $ii++) {
						echo "<div class='col-lg-3 col-md-6'>
								<div id='ho_co' class='guarantee-box_main'>
									<div class='guarantee-box text_align_center'>
										<i>
										<a href='proyectoTest.php?id=".$proyectos[($ii+($n+(4*$i)))][1]."'><img src='./images/proyectos.png' width='150px' alt='Proyecto'> </a>
										</i>
										<h3><a href='proyectoTest.php?id=".$proyectos[($ii+($n+(4*$i)))][1]."'>".$proyectos[($ii+($n+(4*$i)))][0]."</a></h3>
										
									</div>
									<a class='read_more' href='proyectoTest.php?id=".$proyectos[($ii+($n+(4*$i)))][1]."'>Ver mas</a>
								</div>
							</div>";   
						}
					}
					for ($i = 1; $i <= $mod; $i++) {
						echo "<div class='col-lg-3 col-md-6'>
								<div id='ho_co' class='guarantee-box_main'>
									<div class='guarantee-box text_align_center'>
										<i>
										<a href='proyectoTest.php?id=".$proyectos[($div*4)+($i-1)][1]."'><img src='./images/proyectos.png' width='150px' alt='Proyecto'></a>
										</i>
										<h3><a href='proyectoTest.php?id=".$proyectos[($div*4)+($i-1)][1]."'>".$proyectos[($div*4)+($i-1)][0]."</a></h3>
										
									</div>
									<a class='read_more' href='proyectoTest.php?id=".$proyectos[($div*4)+($i-1)][1]."'>Ver mas</a>
								</div>
							</div>";
					}
				}else{
					for ($i = 1; $i <= $nElem; $i++) {
						echo "<div class='col-lg-3 col-md-6'>
								<div id='ho_co' class='guarantee-box_main'>
									<div class='guarantee-box text_align_center'>
										<i>
										<img src='./images/proyectos.png' width='150px' alt='Proyecto'>
										</i>
										<h3>".$proyectos[$i-1][0]."</h3>
									</div>
									<a class='read_more' href='proyectoTest.php?id=".$proyectos[$i-1][1]."'>Ver mas</a>
								</div>
							</div>";   
					}
				}
			?>
			
			

            </div> 
         </div>
      </div>
      
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