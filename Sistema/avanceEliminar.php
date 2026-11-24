<?php
	session_start();
	if(isset($_SESSION["usuario"]) == FALSE){
		header("location:./login.php");
	}else{
		include ("../Bibliotecas/dbProyecto.php");
		$idAv = $_GET["idAv"];
		$idP = $_GET["idP"];
		$proyecto = new Proyecto();
		$proyecto->read($idP);
		
		include_once("../Bibliotecas/dbAvance.php");
		$a = new Avance();				
		$a->read($idAv);
		$res = $a->delete();
		if($res){
			$message= "Avance eliminado con éxito";
			$class="alert alert-success";
		}else{
			$message="No se pudo eliminar el avance";
			$class="alert alert-danger";
		}
		header("location:./avancesCargados.php?id=".$idP."&msg=".$message."&class=".$class);
	}
?>