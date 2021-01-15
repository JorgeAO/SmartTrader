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

<? require '../../seguridad/seguridad/Menu.php'; ?>

<br>

<div class="container col-sm-12">
	<div class="card">
		<div class="card-header bg-dark text-white">Prestamos</div>
		<div class="card-body row">
			<div class="form-group col-sm-3">
				<label>Fecha Inicial</label>
				<input type="text" class="form-control form-control-sm" name="fecha_inicial" id="fecha_inicial" data-req="true" placeholder="Fecha Inicial" readonly="true">
			</div>
			<div class="form-group col-sm-3">
				<label>Fecha Final</label>
				<input type="text" class="form-control form-control-sm" name="fecha_final" id="fecha_final" data-req="true" placeholder="Fecha Final" readonly="true">
			</div>
			<div class="form-group col-sm-12">
				<button class="btn btn-primary btn-sm texto-12" id="btn_consultar">Consultar</button>
			</div>
			<div class="table-responsive" id="div_resultados"></div>
		</div>
	</div>
</div>

<script type="text/javascript">
	$(document).ready(function(){
		$('#fecha_inicial').datepicker({
			format: "yyyy-mm-dd",
			todayBtn: "linked",
			language: "es"
		});
        
		$('#fecha_final').datepicker({
			format: "yyyy-mm-dd",
			todayBtn: "linked",
			language: "es"
		});

		$('#btn_consultar').on('click', function(){
			consultar();
		});
	});

	function consultar()
	{
		enviarPeticion('reportes/nuevos',
			{
                '~pres.fc':$('#fecha_inicial').val()+','+$('#fecha_final').val() ,
            }, 
			function(rta){
				if (rta.tipo == 'error')
					alert(rta.mensaje)
				else if (rta.tipo == 'exito')
				{
					var tabla = '<table id="tbl_resultados" class="table table-hover table-bordered table-striped table-sm texto-12" width="100%" cellspacing="0">'+
						'<thead>'+
						'<tr>'+
						'<th>Código</th>'+
						'<th>Cliente</th>'+
						'<th>Fecha</th>'+
						'<th>Plazo (Meses)</th>'+
						'<th>Valor</th>'+
						'<th>Porc. Interés</th>'+
						'<th>Vlr. Interés</th>'+
						'<th>Estado</th>'+
						'<th>Opciones</th>'+
						'</tr>'+
						'</thead>'+
						'<tbody>';

					$.each(rta.datos, function(i, val){
						tabla += '<tr>'+
							'<td>'+val['pres_codigo']+'</td>'+
							'<td>'+val['clie_nombre']+' '+val['clie_apellido']+'</td>'+
							'<td>'+val['pres_fecha']+'</td>'+
							'<td>'+val['pres_plazo']+'</td>'+
							'<td>'+val['pres_vlr_monto']+'</td>'+
							'<td>'+val['pres_interes']+'</td>'+
							'<td>'+val['pres_int_total']+'</td>'+
							'<td>'+val['esta_descripcion']+'</td>'+
							'<td class="btn-group">'+
							'<a href="prestamosDet/'+val['pres_codigo']+'" class="btn btn-primary btn-sm" title="Detalles"><i class="fa fa-info"></i></a>'+
							'</td>'+
							'</tr>';
					});

					tabla += '</tbody></table>';

					$('#div_resultados').html(tabla);
					$('#tbl_resultados').DataTable({
						"language": { "url": ruta_tabla_esp }
					});
				}
			}
		);
	}
</script>