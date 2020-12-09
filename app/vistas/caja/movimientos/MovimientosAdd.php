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
		<div class="card-header bg-dark text-white">Agregar Movimiento</div>
		<div class="card-body">
			<form id="frm_add">
				<div class="row">
					<div class="form-group col-sm-3">
						<label>Inversionista</label>
						<select class="form-control form-control-sm texto-12" name="fk_par_inversionistas" id="fk_par_inversionistas" data-req="true"></select>
						<label id="hlp_fk_par_inversionistas" class="texto-error"></label>
					</div>
					<div class="form-group col-sm-3">
						<label>Tipo de Movimiento</label>
						<select class="form-control form-control-sm texto-12" name="movi_tipo" id="movi_tipo">
							<option value="E">Entrada</option>
							<option value="S">Salida</option>
						</select>
						<label id="hlp_fk_par_inversionistas" class="texto-error"></label>
					</div>
					<div class="form-group col-sm-3">
						<label>Descripción</label>
						<input type="text" class="form-control form-control-sm" name="movi_descripcion" id="movi_descripcion" data-tipo="texto" data-req="true" placeholder="Descripción">
						<label id="hlp_movi_descripcion" class="texto-error"></label>
					</div>
					<div class="form-group col-sm-3">
						<label>Fecha</label>
						<input type="text" class="form-control form-control-sm" name="movi_fecha" id="movi_fecha" data-tipo="direccion" data-req="true" placeholder="Fecha" readonly="true">
						<label id="hlp_movi_fecha" class="texto-error"></label>
					</div>
					<div class="form-group col-sm-3">
						<label>Monto</label>
						<input type="text" class="form-control form-control-sm" name="movi_monto" id="movi_monto" data-tipo="numeros" data-req="true" placeholder="Monto">
						<label id="hlp_movi_monto" class="texto-error"></label>
					</div>
				</div>
			</form>
		</div>
		<div class="card-footer">
			<button class="btn btn-success btn-sm texto-12" id="btn_guardar">Guardar y Crear Nuevo</button>
			<button class="btn btn-success btn-sm texto-12" id="btn_aceptar">Guardar</button>
			<a class="btn btn-danger btn-sm texto-12" href="movimientos">Cancelar</a>
			<div id="div_mensaje"></div>
		</div>
	</div>
</div>

<script type="text/javascript">
	$(document).ready(function(){

		$('#movi_fecha').datepicker({
			format: "yyyy-mm-dd",
			todayBtn: "linked",
			language: "es"
		});
		
		enviarPeticion('inversionistas/listar',
			{ 'inve.fk_par_estados':'1' }, 
			function(rta){
				$('#fk_par_inversionistas').append('<option value="">-- Seleccione --</option>');
				$.each(rta.datos, function(i, val){
					$('#fk_par_inversionistas').append('<option value="'+val['inve_codigo']+'">' + val['inve_identificacion']+' - '+val['inve_nombre']+' '+val['inve_apellido']+'</option>');
				});
			}
		);

		$("#fk_par_inversionistas").select2({
			placeholder:'-- Seleccione --'
		});

		$('#btn_aceptar').on('click', function(){
			guardar('movimientos');
		});

		$('#btn_guardar').on('click', function(){
			guardar('movimientosAdd');
		});
	});

	function guardar(siguiente)
	{
		if (validarFormulario('frm_add'))
		{
			$('#btn_aceptar').attr("disabled", true);
			$('#btn_guardar').attr("disabled", true);
			$('#div_mensaje').html('<i class="fa fa-spinner fa-pulse fa-fw"></i> Procesando, un momento por favor');

			enviarPeticion('movimientos/insertar',
				prepararFormulario('frm_add'), 
				function(rta){
					alert(rta.mensaje);

					$('#div_mensaje').html('');
					$('#btn_aceptar').attr("disabled", false);
					$('#btn_guardar').attr("disabled", false);

					if (rta.tipo == 'exito')
						window.location.href = siguiente;
				}
			);
		}
	}
</script>