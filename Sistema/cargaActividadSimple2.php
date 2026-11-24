<?php	
	session_start();
	if(isset($_SESSION["usuario"]) == FALSE){
		header("location:./login.php");
	}else{
		$idUser= $_SESSION["id"];
		//include_once("../Bibliotecas/dbUsuario.php");
		//include_once("../Bibliotecas/dbProyecto.php");
		//$idProy = $_GET["id"];
		$proy = $_POST["idProy"];
		/*$actividad = $_POST['actividad'];
		$fecha = date("Y-m-d");
		$estado = $_POST['est'];
		$observaciones = $_POST['obs'];
		$justificacion = "";
		$inicio = "";
		$fin = "";
		if ($estado == "Finalizada"){
			$inicio = $_POST['inicio'];
			$fin = $_POST['fin'];
		}elseif($estado == "En ejecución")
			$inicio = $_POST['inicio'];
		elseif($estado == "No realizada")
			$justificacion = $_POST['just'];
		$usuario = $idUser;
		$proyecto = new Proyecto();*/
		/*$proyecto->read($proy);*/

		/*include ("../Bibliotecas/dbAvance.php");
		$a= new Avance();*/
echo "ACA3: ".$_POST["idProy"];
		if(/*isset($_POST) &&*/ !empty($_POST)){
			
			// detalles del archivo subido
			/*$fileTmpPath1 = $_FILES['fuenteV']['tmp_name'];
			$fileName1 = $_FILES['fuenteV']['name'];
			$fileSize1 = $_FILES['fuenteV']['size'];
			$fileType1 = $_FILES['fuenteV']['type'];
			$fileNameCmps1 = explode(".", $fileName1);
			$fileExtension1 = strtolower(end($fileNameCmps1));*/
			
			/*$a->setProyecto($proy);
			$a->setActividad($actividad);
			$a->setFecha($fecha);
			$a->setEstado($estado);
			$a->setObservaciones($observaciones);
			if ($justificacion != "")
				$a->setJustificacion($justificacion);
			$a->setUsuario($usuario);
			if ($inicio != "")
				$a->setInicio($inicio);
			if ($fin != "")
				$a->setFin($fin);
			if ($fileName1 != "")
				$a->setFuenteValidacion($fileName1);*/
						
			//$res = $a->create();
			//$id=$c->lastId();
			/*if($res){
				$message= "Avance registrado con éxito";
				$class="alert alert-success";
			}else{
				$message="No se pudo registrar el avance";
				$class="alert alert-danger";
			}*/
			
			// chequeo de las extensiones
			/*if ($fileName1 != ""){
				$allowedfileExtensions = array('jpg', 'pdf', 'docx', 'doc', 'xls', 'xlsx', 'zip', 'rar', 'jpeg', 'png');
				if (in_array($fileExtension1, $allowedfileExtensions)){
					$uploadFileDir = './fuenteValidacion/'.$idProy.'/';
					$dest_path = $uploadFileDir . $fileName1;
					
					if (!file_exists($uploadFileDir)) {
						mkdir($uploadFileDir, 0777, true);
					}

					if(move_uploaded_file($fileTmpPath1, $dest_path)){
						$message .=' - Archivo subido exitosamente';
						$class="alert alert-success";
					}else{
						$message .= ' - Error al subir el archivo.';
						$class="alert alert-danger";
					}
				}else{
					$message = 'Error al subir en archivo: El archivo debe tener alguno de la siguientes extensiones: ' . implode(',', $allowedfileExtensions);
				}
			}*/
			//header("location:./avanceCarga.php?id=".$proy."&msg=".$message."&class=".$class);
		}
	}
?>