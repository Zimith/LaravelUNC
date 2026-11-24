<?php 
	if ($_POST["elegido"]=='Finalizada'){
		?>
		<div class="form-group">
			<label>Fecha de inicio</label>
			<input type='date' class='form-control' id='inicio' name='inicio'></input>
			<label>Fecha de finalización</label>
			<input type='date' class='form-control' id='fin' name='fin'></input>
		</div>
		<?php
	}
	if ($_POST["elegido"]=='En ejecución'){
		?>
		<div class="form-group">
			<label>Fecha de inicio</label>
			<input type='date' class='form-control' id='inicio' name='inicio'></input>
		</div>
		<?php
	}
	if ($_POST["elegido"]=='No realizada'){
		?>
		<div class="form-group">
			<label class="label">Justificación (*)</label>
			  <textarea class="login" placeholder="Justificación de porque no fue realizada la actividad" type="type" name="just" id="just" required></textarea>
		</div>
		<script language="javascript">
			document.getElementById("fuenteV").required = false;
			document.getElementById("labelFV").innerHTML = "Fuente de verificación (Archivo jpg, pdf, zip o rar)";
		</script>
		<?php
	}
	?>