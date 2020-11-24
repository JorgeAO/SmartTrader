<script src="/apps/SmartTrader/recursos/librerias/jquery/jquery-3.2.0.min.js"></script>
<script src="/apps/SmartTrader/recursos/librerias/propias/js/scripts.js"></script>
<script src="/apps/SmartTrader/recursos/librerias/bootstrap/bootstrap-4.1.2/js/bootstrap.min.js"></script>
<script src="/apps/SmartTrader/recursos/librerias/datatables/DataTables-1.10.16/js/jquery.dataTables.min.js"></script>
<script src="/apps/SmartTrader/recursos/librerias/datatables/DataTables-1.10.16/js/dataTables.bootstrap4.min.js"></script>
<script src="/apps/SmartTrader/recursos/librerias/highcharts/highcharts-8.1.2/code/highcharts.js"></script>

<link rel="stylesheet" type="text/css" href="/apps/SmartTrader/recursos/librerias/bootstrap/bootstrap-4.1.2/css/bootstrap.min.css">
<link rel="stylesheet" type="text/css" href="/apps/SmartTrader/recursos/librerias/fontawesome/font-awesome-4.7.0/css/font-awesome.min.css">
<link rel="stylesheet" href="/apps/SmartTrader/recursos/librerias/datatables/DataTables-1.10.16/css/dataTables.bootstrap4.css">
<link rel="stylesheet" type="text/css" href="/apps/SmartTrader/recursos/librerias/propias/css/estilos.css">

<script type="text/javascript">
	$(document).ready(function(){

		$('#btn_act_mov').on('click', function(){
			actInformacion();
			actMovimientos();
		});

		actInformacion();
		actMovimientos();

	});

	function actInformacion() 
	{
		enviarPeticion('inversionistas/consultar',
			{'inve_codigo':<?=$_REQUEST['cod']?>}, 
			function(rta){
				$.each(rta.datos[0], function(i, val){
					$('#'+i).html(val);
				});
			}
		);	
	}

	function actMovimientos()
	{
		enviarPeticion('movimientos/consultar',
			{ 'fk_par_inversionistas':<?=$_REQUEST['cod']?>}, 
			function(rta){
				if (rta.tipo == 'error')
					alert(rta.mensaje)
				else if (rta.tipo == 'exito')
				{
					var tabla = '<table id="tbl_resultados" class="table table-hover table-bordered table-striped table-sm texto-12" width="100%" cellspacing="0">'+
						'<thead>'+
						'<tr>'+
						'<th>Código</th>'+
						'<th>Inversionista</th>'+
						'<th>Tipo de Movimiento</th>'+
						'<th>Descripción</th>'+
						'<th>Fecha</th>'+
						'<th>Monto</th>'+
						'</tr>'+
						'</thead>'+
						'<tbody>';

					$.each(rta.datos, function(i, val){
						tabla += '<tr>'+
							'<td>'+val['movi_codigo']+'</td>'+
							'<td>'+val['inve_nombre']+' '+val['inve_apellido']+'</td>'+
							'<td>'+val['movi_tipo_2']+'</td>'+
							'<td>'+val['movi_descripcion']+'</td>'+
							'<td>'+val['movi_fecha']+'</td>'+
							'<td>'+val['movi_monto']+'</td>'+
							'</tr>';
					});

					tabla += '</tbody></table>';

					$('#div_mov').html(tabla);
					$('#tbl_resultados').DataTable({
						"language": { "url": ruta_tabla_esp }
					});
				}
			}
		);
	}

</script>

<? require '../../seguridad/seguridad/Menu.php'; ?>

<div class="col-sm-12">
	<div class="card">
		<div class="card-header bg-dark text-white">
			<button class="btn btn-success btn-sm texto-12" id="btn_act_mov" title="Actualizar movimientos"><i class="fa fa-refresh"></i></button>
			Detalles del Inversionista
		</div>
		<div class="card-body">
			<div class="row">
					<div class="col">
						<div class="card">
							<div class="card-header bg-dark text-white" id="div_gen">Datos Generales</div>
							<div class="card_body">
								<table class="table table-sm texto-12">
									<tr>
										<th scope="col">Tipo de Identificación</th>
										<td id="tiid_descripcion"></td>
									</tr>
									<tr>
										<th scope="col">Identificación</th>
										<td id="inve_identificacion"></td>
									</tr>
									<tr>
										<th scope="col">Nombre</th>
										<td id="inve_nombre"></td>
									</tr>
									<tr>
										<th scope="col">Apellido</th>
										<td id="inve_apellido"></td>
									</tr>
									<tr>
										<th scope="col">Celular</th>
										<td id="inve_celular"></td>
									</tr>
									<tr>
										<th scope="col">Dirección</th>
										<td id="inve_direccion"></td>
									</tr>
									<tr>
										<th scope="col">Correo Electrónico</th>
										<td id="inve_correo"></td>
									</tr>
									<tr>
										<th scope="col">Usuario</th>
										<td id="usua_login"></td>
									</tr>
									<tr>
										<th scope="col">Estado Actual</th>
										<td id="esta_descripcion"></td>
									</tr>
									<tr>
										<th scope="col">Fecha de Ingreso</th>
										<td id="fc"></td>
									</tr>
								</table>
							</div>
						</div>
					</div>
					<div class="col">
						<div class="card">
							<div class="card-header bg-dark text-white" id="div_fin">Datos Financieros</div>
							<div class="card-body">
								<table class="table table-sm texto-12">
									<tr>
										<th scope="col">Saldo Actual</th>
										<td id="inve_saldo"></td>
									</tr>
									<tr>
										<th scope="col">Saldo Mínimo</th>
										<td id="inve_saldo_min"></td>
									</tr>
								</table>
							</div>
						</div>
				</div>
			</div>
			<div class="row">
				<div class="col-sm-12"> <p></p> </div>
			</div>
			<div class="row">
				<div class="col-sm-12">
					<div class="card">
						<div class="card-header bg-dark text-white">
							Movimientos
						</div>
						<div class="card-body">
							<div class="table-responsive" id="div_mov"></div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="card-footer">
			<a class="btn btn-danger btn-sm texto-12" href="../inversionistas">Volver</a>
		</div>
	</div>
</div>