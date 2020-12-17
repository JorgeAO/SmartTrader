<?php 
	setlocale (LC_ALL, "es_ES");
	date_default_timezone_set ('America/Bogota'); 
?>
<script src="../recursos/librerias/jquery/jquery-3.2.0.min.js"></script>
<script src="../recursos/librerias/propias/js/scripts.js"></script>
<script src="/apps/SmartTrader/recursos/librerias/propias/js/validador.js"></script>
<script src="../recursos/librerias/bootstrap/bootstrap-4.1.2/js/bootstrap.min.js"></script>
<script src="/apps/SmartTrader/recursos/librerias/datatables/DataTables-1.10.16/js/jquery.dataTables.min.js"></script>
<script src="/apps/SmartTrader/recursos/librerias/datatables/DataTables-1.10.16/js/dataTables.bootstrap4.min.js"></script>
<script src="../recursos/librerias/datepicker/bootstrap-datepicker-1.9.0/js/bootstrap-datepicker.min.js"></script>

<link rel="stylesheet" href="../recursos/librerias/bootstrap/bootstrap-4.1.2/css/bootstrap.min.css">
<link rel="stylesheet" href="../recursos/librerias/propias/css/estilos.css">
<link rel="stylesheet" href="/apps/SmartTrader/recursos/librerias/fontawesome/font-awesome-4.7.0/css/font-awesome.min.css">
<link rel="stylesheet" href="/apps/SmartTrader/recursos/librerias/datatables/DataTables-1.10.16/css/dataTables.bootstrap4.css">
<link rel="stylesheet" href="../recursos/librerias/datepicker/bootstrap-datepicker-1.9.0/css/bootstrap-datepicker.min.css">

<? require 'Menu.php'; ?>

<br>

<div class="row col-sm-12">
	<div class="col-sm-3">
		<div class="card bg-secondary text-white p-3">
			<p style="font-size:14px"><i class="fa fa-calendar"></i> Fecha</p>
			<input type="text" class="form-control form-control-sm" name="prcu_fecha" id="prcu_fecha" placeholder="Fecha" data-tipo="texto" data-req="true" readonly="true" value="<?=date('Y-m-d')?>">
		</div>
	</div>
	<div class="col-sm-3">
		<div class="card bg-secondary text-white p-3">
			<p style="font-size:14px">
				<i class="fa fa-handshake-o"></i> Préstamos
				<a class="btn btn-secondary btn-sm pull-right text-white" title="Agregar préstamo" href="../prestamos/prestamosAdd" style="font-size:14px">
					<i class="fa fa-plus"></i>
				</a>
			</p>
			<h2 class="text-right" id="card_prestamos"></h2>
		</div>
	</div>
	<!--div class="col-sm-3">
		<div class="card bg-secondary text-white p-3">
			<p style="font-size:14px"><i class="fa fa-users"></i> Clientes</p>
			<h2 class="text-right" id="card_clientes"></h2>
		</div>
	</div>
	<div class="col-sm-3">
		<div class="card bg-secondary text-white p-3">
			<p style="font-size:14px"><i class="fa fa-bank"></i> Inversionistas</p>
			<h2 class="text-right" id="card_inversionistas"></h2>
		</div>
	</div-->
</div>
<br>
<div class="row col-sm-12">
	<div class="col-sm-3">
		<div class="card bg-secondary text-white p-3">
			<p style="font-size:14px"><i class="fa fa-usd"></i> Total por recoger hoy</p>
			<h2 class="text-right" id="card_total_hoy"></h2>
		</div>
	</div>
	<div class="col-sm-3">
		<div class="card bg-secondary text-white p-3">
			<p style="font-size:14px"><i class="fa fa-check-circle"></i> Total recogido</p>
			<h2 class="text-right" id="card_total_recogido"></h2>
		</div>
	</div>
</div>
<br>
<div class="row col-sm-12">
	<div class="col-sm-6">
		<div class="card">
			<div class="card-header bg-dark text-white">Mi Día</div>
			<div class="card-body">
				<div class="table-responsive" id="div_cuotas"></div>
			</div>
		</div>
	</div>
</div>

<div class="modal" id="mdl_pago" tabindex="-1">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title">Pagar cuota</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<div class="row">
					<div class="col-sm-3">
						<div class="form-group col-sm-12">
							<label class="font-weight-bold">Cuota:</label>
							<p id="pc_prcu_codigo"></p>
						</div>
						<div class="form-group col-sm-12">
							<label class="font-weight-bold">Valor de la Cuota:</label>
							<p id="pc_prcu_valor"></p>
						</div>
						<div class="form-group col-sm-12">
							<label class="font-weight-bold">Saldo de la Deuda:</label>
							<p id="pc_pres_vlr_saldo"></p>
						</div>
					</div>
					<div class="row col-sm-9">
						<div class="form-group col-sm-4">
							<label>Fecha</label>
							<input type="text" class="form-control form-control-sm validar" name="pc_pago_fecha" id="pc_pago_fecha" data-tipo="direccion" data-req="true" placeholder="Fecha" readonly="true">
							<label id="hlp_pc_pago_fecha" class="texto-error"></label>
						</div>
						<div class="form-group col-sm-4">
							<label>Pago</label>
							<select class="form-control form-control-sm texto-12 intCalcular validar enviar" name="pc_tipo_pago" id="pc_tipo_pago" data-req="true">
								<option value="C">Cuota</option>
								<option value="D">Valor diferente</option>
								<option value="T">Todo</option>
								<option value="N">No paga</option>
							</select>
						</div>
						<div class="form-group col-sm-4">
							<label>Valor a pagar</label>
							<input type="text" class="form-control form-control-sm validar" name="pc_vlr_pago" id="pc_vlr_pago" data-tipo="numeros" data-req="true" placeholder="Valor a pagar" readonly="true">
							<label id="hlp_pc_vlr_pago" class="texto-error"></label>
						</div>
					</div>
				</div>
			</div>
			<div class="modal-footer">
				<div id="div_mensaje" class="pull-left"></div>
				<button class="btn btn-success btn-sm texto-12" id="btn_pagar">Aceptar</button>
        		<button type="button" class="btn btn-danger btn-sm texto-12" data-dismiss="modal">Cancelar</button>
			</div>
		</div>
	</div>
</div>

<script type="text/javascript">
	$(document).ready(function(){
		
		$('#prcu_fecha').datepicker({
			format: "yyyy-mm-dd",
			todayBtn: "linked",
			language: "es"
		});

		$('#pc_pago_fecha').datepicker({
			format: "yyyy-mm-dd",
			todayBtn: "linked",
			language: "es"
		});

		$('#prcu_fecha').on('change', function(){
			cargarTarjetas();
			miDia();
		});

		$('#btn_pagar').on('click', function(){
			let valido = true;

			valido = validarCampo('pc_pago_fecha');

			if (valido == true)
			{
				if ($('#pc_tipo_pago').val() == 'D')
					valido = validarCampo('pc_vlr_pago');
			}

			if (valido == true)
			{
				$('#btn_pagar').attr("disabled", true);
				$('#div_mensaje').html('<i class="fa fa-spinner fa-pulse fa-fw"></i> Procesando pago, un momento por favor');

				enviarPeticion(
					'prestamos/pago',
					{
						'cuota':$('#pc_prcu_codigo')[0].innerText,
						'vlr_cuota':$('#pc_prcu_valor')[0].innerText,
						'saldo':$('#pc_pres_vlr_saldo')[0].innerText,
						'fecha':$('#pc_pago_fecha').val(),
						'tipo':$('#pc_tipo_pago').val(),
						'valor':$('#pc_vlr_pago').val(),
					}, 
					function(rta){
						alert(rta.mensaje);

						$('#div_mensaje').html('');
						$('#btn_pagar').attr("disabled", false);
						
						if (rta.tipo == 'exito')
							location.reload();
					}
				);
			}
		});

		cargarTarjetas();
		miDia();
	});

	function cargarTarjetas()
	{
		enviarPeticion('principal/consultarTarjetas',
			{ 'fecha':$('#prcu_fecha').val() }, 
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
	}

	function miDia()
	{
		enviarPeticion('cuotas/cuotasPorFecha',
			{ 'fecha':$('#prcu_fecha').val() }, 
			function(rta){
				if (rta.tipo == 'error')
					alert(rta.mensaje)
				else if (rta.tipo == 'exito')
				{
						
					var tabla = '<table id="tbl_cuotas" class="table table-hover table-bordered table-striped table-sm texto-12" width="100%" cellspacing="0">'+
						'<thead>'+
						'<tr>'+
						'<th>Cliente</th>'+
						'<th>Teléfono</th>'+
						'<th>Cód. Prestamo</th>'+
						'<th>Nro. Cuota</th>'+
						'<th>Vlr. Cuota</th>'+
						'<th>Estado</th>'+
						'<th>Opciones</th>'+
						'</tr>'+
						'</thead>'+
						'<tbody>';

					$.each(rta.datos, function(i, val){
						let estado;
						if (val['fk_par_estados'] == 3)
							estado = 'primary';
						else if (val['fk_par_estados'] == 4)
							estado = 'success';
						else if (val['fk_par_estados'] == 5)
							estado = 'danger';

						tabla += '<tr>'+
							'<td>'+val['clie_nombre']+' '+val['clie_apellido']+'</td>'+
							'<td>'+val['clie_celular']+'</td>'+
							'<td>'+val['fk_pre_prestamos']+'</td>'+
							'<td>'+val['prcu_numero']+'</td>'+
							'<td>'+val['prcu_vlr_saldo']+'</td>'+
							'<td><span class="badge badge-'+estado+'">'+val['esta_descripcion']+'</span></td>'+
							'<td>';
							if (val['fk_par_estados'] == 3 || val['fk_par_estados'] == 5)
								tabla += '<button class="btn btn-success btn-sm" type="button" title="Registrar pago" onclick="registrarPago('+val['prcu_codigo']+', '+val['prcu_vlr_saldo']+', '+val['pres_vlr_saldo']+')"><i class="fa fa-usd"></i></button>';
							tabla += '</td></tr>';
					});

					tabla += '</tbody></table>';

					$('#div_cuotas').html(tabla);
					$('#tbl_cuotas').DataTable({
						"language": { "url": ruta_tabla_esp }
					});
				}
			}
		);	
	}

	function registrarPago(cod, vlrCuota, vlrSaldo)
	{
		$('#pc_prcu_codigo').html(cod);
		$('#pc_prcu_valor').html(vlrCuota);
		$('#pc_pres_vlr_saldo').html(vlrSaldo);
		$('#mdl_pago').modal('show');
	}
</script>