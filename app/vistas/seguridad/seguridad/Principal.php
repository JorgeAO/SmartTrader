<?php 
	setlocale (LC_ALL, "es_ES");
	date_default_timezone_set ('America/Bogota'); 
?>
<script type="text/javascript" src="../recursos/librerias/jquery/jquery-3.2.0.min.js"></script>
<script type="text/javascript" src="../recursos/librerias/propias/js/scripts.js"></script>
<script type="text/javascript" src="../recursos/librerias/bootstrap/bootstrap-4.1.2/js/bootstrap.min.js"></script>

<script type="text/javascript">
	$(document).ready(function(){
		enviarPeticion('principal/consultarTarjetas',
			{ '':'' }, 
			function(rta){
				if (rta.tipo == 'error')
					alert(rta.mensaje)
				else if (rta.tipo == 'exito')
				{
					$.each(rta.datos, function(i, val){
						$('#card_'+i).html(val);
					});
				}
			}
		);
	});
</script>

<link rel="stylesheet" type="text/css" href="../recursos/librerias/bootstrap/bootstrap-4.1.2/css/bootstrap.min.css">
<link rel="stylesheet" type="text/css" href="../recursos/librerias/propias/css/estilos.css">
<link rel="stylesheet" type="text/css" href="/apps/SmartTrader/recursos/librerias/fontawesome/font-awesome-4.7.0/css/font-awesome.min.css">

<? require 'Menu.php'; ?>

<br>

<div class="row col-sm-12">
	<div class="col-sm-3">
		<div class="card bg-primary text-white p-3">
			<p style="font-size:14px"><i class="fa fa-calendar"></i> Fecha</p>
			<h2 class="text-right" id="card_fecha"></h2>
		</div>
	</div>
	<div class="col-sm-3">
		<div class="card bg-primary text-white p-3">
			<p style="font-size:14px">
				<i class="fa fa-handshake-o"></i> Préstamos
				<a class="btn btn-primary btn-sm pull-right text-white" title="Agregar préstamo" href="../prestamos/prestamosAdd" style="font-size:14px">
					<i class="fa fa-plus"></i>
				</a>
			</p>
			<h2 class="text-right" id="card_prestamos"></h2>
		</div>
	</div>
	<div class="col-sm-3">
		<div class="card bg-primary text-white p-3">
			<p style="font-size:14px"><i class="fa fa-users"></i> Clientes</p>
			<h2 class="text-right" id="card_clientes"></h2>
		</div>
	</div>
	<div class="col-sm-3">
		<div class="card bg-primary text-white p-3">
			<p style="font-size:14px"><i class="fa fa-bank"></i> Inversionistas</p>
			<h2 class="text-right" id="card_inversionistas"></h2>
		</div>
	</div>
</div>