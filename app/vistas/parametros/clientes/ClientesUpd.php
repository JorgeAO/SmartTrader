<script type="text/javascript" src="/apps/SmartTrader/recursos/librerias/jquery/jquery-3.2.0.min.js"></script>
<script type="text/javascript" src="/apps/SmartTrader/recursos/librerias/propias/js/scripts.js"></script>
<script type="text/javascript" src="/apps/SmartTrader/recursos/librerias/propias/js/validador.js"></script>
<script type="text/javascript" src="/apps/SmartTrader/recursos/librerias/bootstrap/bootstrap-4.1.2/js/bootstrap.min.js"></script>
<script src="/apps/SmartTrader/recursos/librerias/datatables/DataTables-1.10.16/js/jquery.dataTables.min.js"></script>
<script src="/apps/SmartTrader/recursos/librerias/datatables/DataTables-1.10.16/js/dataTables.bootstrap4.min.js"></script>

<link rel="stylesheet" type="text/css" href="/apps/SmartTrader/recursos/librerias/bootstrap/bootstrap-4.1.2/css/bootstrap.min.css">
<link rel="stylesheet" type="text/css" href="/apps/SmartTrader/recursos/librerias/fontawesome/font-awesome-4.7.0/css/font-awesome.min.css">
<link rel="stylesheet" href="/apps/SmartTrader/recursos/librerias/datatables/DataTables-1.10.16/css/dataTables.bootstrap4.css">
<link rel="stylesheet" type="text/css" href="/apps/SmartTrader/recursos/librerias/propias/css/estilos.css"> 

<script type="text/javascript">
	$(document).ready(function(){
		enviarPeticion('tiposIdentificacion/listar',
			{ '':'' }, 
			function(rta){
				$.each(rta.datos, function(i, val){
					$('#fk_par_tipos_identificacion').append('<option value="'+val['tiid_codigo']+'">'+val['tiid_codigo']+' - '+val['tiid_descripcion']+'</option>');
				});
			}
        );
        
		enviarPeticion('estados/listar',
			{ '':'' }, 
			function(rta){
				$.each(rta.datos, function(i, val){
					$('#fk_par_estados').append('<option value="'+val['esta_codigo']+'">'+val['esta_codigo']+' - '+val['esta_descripcion']+'</option>');
				});
			}
		);

        enviarPeticion('clientes/consultar',
            {'clie_codigo':<?=$_REQUEST['cod']?>}, 
            function(rta){
                $.each(rta.datos[0], function(i, val){
                    $('#'+i).val(val);
                });
            }
        );

		$('#btn_aceptar').on('click', function(){
			if (validarFormulario('frm_upd'))
			{
				if ($('#usua_clave').val() != $('#repetir_clave').val())
					alert('Las contraseñas no coinciden')
				else
					enviarPeticion('clientes/editar',
						prepararFormulario('frm_upd'), 
						function(rta){
							alert(rta.mensaje);
							if (rta.tipo == 'exito')
								window.location.href = '../clientes';
						}
					);
			}
		});
	});
</script>

<? require '../../seguridad/seguridad/Menu.php'; ?>

<div class="col-sm-12">
	<div class="card">
		<div class="card-header bg-dark text-white">Editar Cliente</div>
		<div class="card-body">
			<form id="frm_upd">
				<div class="row">
					<div class="form-group col-sm-3">
						<label>Código</label>
						<input type="text" class="form-control form-control-sm" name="clie_codigo" id="clie_codigo" placeholder="Código" readonly="true">
					</div>
					<div class="form-group col-sm-3">
						<label>Tipo de Identificación</label>
						<select class="form-control form-control-sm texto-12" name="fk_par_tipos_identificacion" id="fk_par_tipos_identificacion" data-req="true"></select>
						<label id="hlp_fk_par_tipos_identificacion" class="texto-error"></label>
					</div>
					<div class="form-group col-sm-3">
						<label>Identificación</label>
						<input type="text" class="form-control form-control-sm texto-12" name="clie_identificacion" id="clie_identificacion" placeholder="Identificación" data-tipo="alfanumerico" data-req="true">
						<label id="hlp_clie_identificacion" class="texto-error"></label>
					</div>
					<div class="form-group col-sm-3">
						<label>Nombre</label>
						<input type="text" class="form-control form-control-sm texto-12
						" name="clie_nombre" id="clie_nombre" placeholder="Nombre" data-tipo="texto" data-req="true">
						<label id="hlp_clie_nombre" class="texto-error"></label>
					</div>
					<div class="form-group col-sm-3">
						<label>Apellido</label>
						<input type="text" class="form-control form-control-sm texto-12
						" name="clie_apellido" id="clie_apellido" placeholder="Apellido" data-tipo="texto" data-req="true">
						<label id="hlp_clie_apellido" class="texto-error"></label>
					</div>
					<!--div class="form-group col-sm-3">
						<label>Correo Electrónico</label>
						<input type="text" class="form-control form-control-sm texto-12" name="clie_correo" id="clie_correo" placeholder="Correo Electrónico" data-tipo="correo" data-req="true">
						<label id="hlp_clie_correo" class="texto-error"></label>
					</div-->
					<div class="form-group col-sm-3">
						<label>Dirección</label>
						<input type="text" class="form-control form-control-sm texto-12" name="clie_direccion" id="clie_direccion" placeholder="Dirección" data-tipo="alfanumerico" data-req="true">
						<label id="hlp_clie_direccion" class="texto-error"></label>
					</div>
					<div class="form-group col-sm-3">
						<label>Celular</label>
						<input type="text" class="form-control form-control-sm texto-12" name="clie_celular" id="clie_celular" placeholder="Celular" data-tipo="numeros" data-req="true">
						<label id="hlp_clie_celular" class="texto-error"></label>
					</div>
					<!--div class="form-group col-sm-3">
						<label>Nombre de Usuario</label>
						<input type="text" class="form-control form-control-sm texto-12" name="usua_login" id="usua_login" placeholder="Nombre de Usuario" data-tipo="login" data-req="true">
						<label id="hlp_usua_login" class="texto-error"></label>
					</div>
					<div class="form-group col-sm-3">
						<label>Clave</label>
						<input type="password" class="form-control form-control-sm texto-12" name="usua_clave" id="usua_clave" placeholder="Clave" data-tipo="clave" data-req="true">
						<label id="hlp_usua_clave" class="texto-error"></label>
					</div>
					<div class="form-group col-sm-3">
						<label>Repetir Clave</label>
						<input type="password" class="form-control form-control-sm texto-12" name="repetir_clave" id="repetir_clave" placeholder="Repetir Clave" data-tipo="clave" data-req="true">
						<label id="hlp_repetir_clave" class="texto-error"></label>
					</div-->
					<div class="form-group col-sm-3">
						<label>Estado</label>
						<select class="form-control form-control-sm" name="fk_par_estados" id="fk_par_estados" data-req="true"></select>
						<label id="hlp_fk_par_estados" class="texto-error"></label>
					</div>
				</div>
			</form>
		</div>
		<div class="card-footer">
			<button class="btn btn-success btn-sm texto-12" id="btn_aceptar">Aceptar</button>
			<a class="btn btn-danger btn-sm texto-12" href="clientes">Cancelar</a>
		</div>
	</div>
</div>