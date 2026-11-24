<?php 
	$idAct = $_POST["elegido2"];
	include_once "../Bibliotecas/dbActividades.php";
	$act = new Actividad();
	$act->read($idAct);
	
?>
	<div class="form-group">
		<label>Fecha de inicio segun cronograma</label>
		<input type='text' class='form-control' id='inicio' name='inicio' value='<?php echo $act->getInicioHumano2();?>' readonly></input>
		<label>Fecha de finalización segun crograma</label>
		<input type='text' class='form-control' id='fin' name='fin' value='<?php echo $act->getFinHumano2();?>' readonly></input>
	</div>