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

        enviarPeticion('inversionistas/consultar',
            {'inve_codigo':<?=$_REQUEST['cod']?>}, 
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
					enviarPeticion('inversionistas/editar',
						prepararFormulario('frm_upd'), 
						function(rta){
							alert(rta.mensaje);
							if (rta.tipo == 'exito')
								window.location.href = '../inversionistas';
						}
					);
			}
		});
	});
</script>

<? require '../../seguridad/seguridad/Menu.php'; ?>

<div class="col-sm-12">
	<div class="card">
		<div class="card-header bg-dark text-white">Editar Inversionista</div>
		<div class="card-body">
			<form id="frm_upd">
				<div class="row">
					<div class="form-group col-sm-3">
						<label>Código</label>
						<input type="text" class="form-control form-control-sm" name="inve_codigo" id="inve_codigo" placeholder="Código" readonly="true">
					</div>
					<div class="form-group col-sm-3">
						<label>Tipo de Identificación</label>
						<select class="form-control form-control-sm texto-12" name="fk_par_tipos_identificacion" id="fk_par_tipos_identificacion" data-req="true"></select>
						<label id="hlp_fk_par_tipos_identificacion" class="texto-error"></label>
					</div>
					<div class="form-group col-sm-3">
						<label>Identificación</label>
						<input type="text" class="form-control form-control-sm texto-12" name="inve_identificacion" id="inve_identificacion" placeholder="Identificación" data-tipo="alfanumerico" data-req="true">
						<label id="hlp_inve_identificacion" class="texto-error"></label>
					</div>
					<div class="form-group col-sm-3">
						<label>Nombre</label>
						<input type="text" class="form-control form-control-sm texto-12
						" name="inve_nombre" id="inve_nombre" placeholder="Nombre" data-tipo="texto" data-req="true">
						<label id="hlp_inve_nombre" class="texto-error"></label>
					</div>
					<div class="form-group col-sm-3">
						<label>Apellido</label>
						<input type="text" class="form-control form-control-sm texto-12
						" name="inve_apellido" id="inve_apellido" placeholder="Apellido" data-tipo="texto" data-req="true">
						<label id="hlp_inve_apellido" class="texto-error"></label>
					</div>
					<div class="form-group col-sm-3">
						<label>Dirección</label>
						<input type="text" class="form-control form-control-sm texto-12" name="inve_direccion" id="inve_direccion" placeholder="Dirección" data-tipo="alfanumerico" data-req="true">
						<label id="hlp_inve_direccion" class="texto-error"></label>
					</div>
					<!--div class="form-group col-sm-3">
						<label>Correo Electrónico</label>
						<input type="text" class="form-control form-control-sm texto-12" name="inve_correo" id="inve_correo" placeholder="Correo Electrónico" data-tipo="correo" data-req="true">
						<label id="hlp_inve_correo" class="texto-error"></label>
					</div-->
					<div class="form-group col-sm-3">
						<label>Celular</label>
						<input type="text" class="form-control form-control-sm texto-12" name="inve_celular" id="inve_celular" placeholder="Celular" data-tipo="numeros" data-req="true">
						<label id="hlp_inve_celular" class="texto-error"></label>
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
						<label>Saldo</label>
						<input type="text" class="form-control form-control-sm texto-12" name="inve_saldo" id="inve_saldo" placeholder="Saldo" data-tipo="numeros" data-req="true">
						<label id="hlp_inve_saldo" class="texto-error"></label>
					</div>
					<!--div class="form-group col-sm-3">
						<label>Saldo Mínimo</label>
						<input type="text" class="form-control form-control-sm texto-12" name="inve_saldo_min" id="inve_saldo_min" placeholder="Saldo Mínimo" data-tipo="numeros" data-req="true">
						<label id="hlp_inve_saldo_min" class="texto-error"></label>
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
			<a class="btn btn-danger btn-sm texto-12" href="inversionistas">Cancelar</a>
		</div>
	</div>
</div>