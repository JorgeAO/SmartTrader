<script type="text/javascript" src="../recursos/librerias/jquery/jquery-3.2.0.min.js"></script>
<script type="text/javascript" src="../recursos/librerias/propias/js/scripts.js"></script>
<script type="text/javascript" src="../recursos/librerias/propias/js/validador.js"></script>
<script type="text/javascript" src="../recursos/librerias/bootstrap/bootstrap-4.1.2/js/bootstrap.min.js"></script>
<script src="../recursos/librerias/datatables/DataTables-1.10.16/js/jquery.dataTables.min.js"></script>
<script src="../recursos/librerias/datatables/DataTables-1.10.16/js/dataTables.bootstrap4.min.js"></script>

<link rel="stylesheet" type="text/css" href="../recursos/librerias/bootstrap/bootstrap-4.1.2/css/bootstrap.min.css">
<link rel="stylesheet" type="text/css" href="../recursos/librerias/fontawesome/font-awesome-4.7.0/css/font-awesome.min.css">
<link rel="stylesheet" href="../recursos/librerias/datatables/DataTables-1.10.16/css/dataTables.bootstrap4.css">
<link rel="stylesheet" type="text/css" href="../recursos/librerias/propias/css/estilos.css">

<script type="text/javascript">
	$(document).ready(function(){
        consultar();

        $('#btn_consultar').on('click', function(){
            consultar();
        });
	});

    function consultar() 
    {
        enviarPeticion('dba/consultar',
            {'':''}, 
            function(rta){
                console.info(rta);
            }
        );   
    }
		
    function ejecutar(cod) 
    {
        enviarPeticion('dba/ejecutar',
            { 'dba_codigo':cod }, 
            function(rta){
                
            }
        );
    }

</script>

<? require '../seguridad/Menu.php'; ?>

<div class="col-sm-12">
	<div class="card">
		<div class="card-header bg-dark text-white">Cambiar Clave</div>
		<div class="card-body">
            <div class="row">
                <div class="form-group col-sm-4">
                    <button class="btn btn-primary btn-sm" id="btn_consultar">Consultar</button>
                </div>
            </div>
		</div>
		<div class="card-footer">
			<button class="btn btn-success btn-sm" id="btn_aceptar">Aceptar</button>
		</div>
	</div>
</div>