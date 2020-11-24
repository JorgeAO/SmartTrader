<script src="../recursos/librerias/jquery/jquery-3.2.0.min.js"></script>
<script src="../recursos/librerias/propias/js/scripts.js"></script>
<script src="../recursos/librerias/propias/js/validador.js"></script>
<script src="../recursos/librerias/bootstrap/bootstrap-4.1.2/js/bootstrap.min.js"></script>
<script src="../recursos/librerias/datatables/DataTables-1.10.16/js/jquery.dataTables.min.js"></script>
<script src="../recursos/librerias/datatables/DataTables-1.10.16/js/dataTables.bootstrap4.min.js"></script>
<script src="../recursos/librerias/select2/js/select2.min.js"></script>
<script src="../recursos/librerias/datepicker/bootstrap-datepicker-1.9.0/js/bootstrap-datepicker.min.js"></script>

<link rel="stylesheet" href="../recursos/librerias/bootstrap/bootstrap-4.1.2/css/bootstrap.min.css">
<link rel="stylesheet" href="../recursos/librerias/fontawesome/font-awesome-4.7.0/css/font-awesome.min.css">
<link rel="stylesheet" href="../recursos/librerias/datatables/DataTables-1.10.16/css/dataTables.bootstrap4.css">
<link rel="stylesheet" href="../recursos/librerias/propias/css/estilos.css">
<link rel="stylesheet" href="../recursos/librerias/select2/css/select2.min.css">
<link rel="stylesheet" href="../recursos/librerias/datepicker/bootstrap-datepicker-1.9.0/css/bootstrap-datepicker.min.css">

<? require '../../seguridad/seguridad/Menu.php'; ?>

<div class="col-sm-12">
	<div class="card">
		<div class="card-header bg-dark text-white">Agregar Préstamo</div>
		<div class="card-body">
			<!--form id="frm_add"-->
				<div class="row">
					<div class="form-group col-sm-3">
						<label>Cliente</label>
						<select class="form-control form-control-sm texto-12 validar enviar" name="fk_par_clientes" id="fk_par_clientes" data-req="true"></select>
						<label id="hlp_fk_par_clientes" class="texto-error"></label>
					</div>
					<div class="form-group col-sm-3">
						<label>Fecha</label>
						<input type="text" class="form-control form-control-sm validar enviar" name="pres_fecha" id="pres_fecha" data-tipo="direccion" data-req="true" placeholder="Fecha" readonly="true">
						<label id="hlp_pres_fecha" class="texto-error"></label>
					</div>
					<div class="form-group col-sm-3">
						<label>Valor</label>
						<input type="text" class="form-control form-control-sm intCalcular validar enviar" name="pres_vlr_monto" id="pres_vlr_monto" data-tipo="numeros" data-req="true" placeholder="Valor">
						<label id="hlp_pres_vlr_monto" class="texto-error"></label>
					</div>
				</div>
				<div class="row">
					<div class="form-group col-sm-3">
						<label>Frecuencia de Pago</label>
						<select class="form-control form-control-sm texto-12 intCalcular validar enviar" name="pres_frecuencia" id="pres_frecuencia" data-req="true">
							<option value="S">Semanal</option>
							<option value="Q">Quincenal</option>
							<option value="M">Mensual</option>
						</select>
					</div>
					<div class="form-group col-sm-3">
						<label>Número de Cuotas</label>
						<input type="text" class="form-control form-control-sm intCalcular validar enviar" name="pres_nro_cuotas" id="pres_nro_cuotas" data-tipo="numeros" data-req="true" placeholder="Número de Cuotas">
						<label id="hlp_pres_nro_cuotas" class="texto-error"></label>
					</div>
					<div class="form-group col-sm-3">
						<label>Plazo (en meses)</label>
						<input type="text" class="form-control form-control-sm intCalcular validar enviar" name="pres_plazo" id="pres_plazo" data-tipo="numeros" data-req="true" placeholder="Plazo (en meses)">
						<label id="hlp_pres_plazo" class="texto-error"></label>
					</div>
					<div class="form-group col-sm-3">
						<label>Porcentaje de Interés</label>
						<input type="text" class="form-control form-control-sm intCalcular validar enviar" name="pres_interes" id="pres_interes" data-tipo="numeros" data-req="true" placeholder="Porcentaje de Interés">
						<label id="hlp_pres_interes" class="texto-error"></label>
					</div>
				</div>
				<div class="row">
					<div class="form-group col-sm-3">
						<button class="btn btn-primary btn-sm texto-12" id="btn_int_calcular">Calcular</button>
					</div>
				</div>
				<div class="row">
					<div class="form-group col-sm-3">
						<label>Interés Mensual</label>
						<input type="text" class="form-control form-control-sm validar enviar" name="pres_int_mensual" id="pres_int_mensual" data-tipo="numeros" data-req="true" placeholder="Interés Mensual" readonly="true">
						<label id="hlp_pres_int_mensual" class="texto-error"></label>
					</div>
					<div class="form-group col-sm-3">
						<label>Interés Total</label>
						<input type="text" class="form-control form-control-sm validar enviar" name="pres_int_total" id="pres_int_total" data-tipo="numeros" data-req="true" placeholder="Interés Total" readonly="true">
						<label id="hlp_pres_int_total" class="texto-error"></label>
					</div>
					<div class="form-group col-sm-3">
						<label>Total Pago</label>
						<input type="text" class="form-control form-control-sm validar enviar" name="pres_vlr_pago" id="pres_vlr_pago" data-tipo="numeros" data-req="true" placeholder="Interés Total" readonly="true">
						<label id="hlp_pres_vlr_pago" class="texto-error"></label>
					</div>
					<div class="form-group col-sm-3">
						<label>Valor Cuota</label>
						<input type="text" class="form-control form-control-sm validar enviar" name="pres_vlr_cuota" id="pres_vlr_cuota" data-tipo="numeros" data-req="true" placeholder="Valor Cuota" readonly="true">
						<label id="hlp_pres_vlr_cuota" class="texto-error"></label>
					</div>
				</div>
			<!--/form-->
		</div>
		<div class="card-footer">
			<button class="btn btn-success btn-sm texto-12" id="btn_guardar">Guardar y Crear Nuevo</button>
			<button class="btn btn-success btn-sm texto-12" id="btn_aceptar">Guardar</button>
			<a class="btn btn-danger btn-sm texto-12" href="prestamos">Cancelar</a>
		</div>
	</div>
</div>

<script type="text/javascript">
	$(document).ready(function(){

		$('#pres_fecha').datepicker({
			format: "yyyy-mm-dd",
			todayBtn: "linked",
			language: "es"
		});
		
		enviarPeticion('clientes/listar',
			{ 'clie.fk_par_estados':'1' }, 
			function(rta){
				$('#fk_par_clientes').append('<option value="">-- Seleccione --</option>');
				$.each(rta.datos, function(i, val){
					$('#fk_par_clientes').append('<option value="'+val['clie_codigo']+'">' + val['clie_identificacion']+' - '+val['clie_nombre']+' '+val['clie_apellido']+'</option>');
				});
			}
		);

		$("#fk_par_clientes").select2({
			placeholder:'-- Seleccione --'
		});

		/*$('.intCalcular').on('change', function(){
			intCalcular();
		});*/

		$('#btn_int_calcular').on('click', function(){
			intCalcular();
		});

		$('#btn_aceptar').on('click', function(){
			guardar('prestamos');
		});

		$('#btn_guardar').on('click', function(){
			guardar('prestamosAdd');
		});
	});

	function intCalcular()
	{
		$('#div_alert').hide('slow');
		enviarPeticion(
			'prestamos/intCalcular',
			{
				'pres_vlr_monto': $('#pres_vlr_monto').val(),
				'pres_frecuencia' : $('#pres_frecuencia').val(),				
				'pres_nro_cuotas' : $('#pres_nro_cuotas').val(),				
				'pres_plazo':$('#pres_plazo').val(),
				'pres_interes':$('#pres_interes').val()
			}, 
			function(rta){
				if (rta.tipo == 'exito')
				{
					$.each(rta.datos[0], function(i, val){
						$('#'+i).val(val);
					});
				}
				else
				{
					alert(rta.mensaje);
				}
			}
		);
	}

	/**
	 * El parámetro siguiente define cuál es la próxima página que se muestra
	 * Puede ser el listado de prestamos: prestamos
	 * O puede ser la página de agregar préstamo: prestamosAdd
	 */
	function guardar(siguiente)
	{
		let valido = true;
		$.each($('.validar'), function(i, val){
			valido = validarCampo(val.id);
		});

		if (valido == true)
		{
			enviarPeticion('prestamos/insertar',
				{
					'fk_par_clientes':$('#fk_par_clientes').val(),
					'pres_fecha':$('#pres_fecha').val(),
					'pres_vlr_monto':$('#pres_vlr_monto').val(),
					'pres_frecuencia':$('#pres_frecuencia').val(),
					'pres_nro_cuotas':$('#pres_nro_cuotas').val(),
					'pres_plazo':$('#pres_plazo').val(),
					'pres_interes':$('#pres_interes').val(),
					'pres_int_mensual':$('#pres_int_mensual').val(),
					'pres_int_total':$('#pres_int_total').val(),
					'pres_vlr_pago':$('#pres_vlr_pago').val(),
					'pres_vlr_cuota':$('#pres_vlr_cuota').val()
				}, 
				function(rta){
					alert(rta.mensaje);
					if (rta.tipo == 'exito')
						window.location.href = siguiente;
				}
			);
		}
		
	}
</script>