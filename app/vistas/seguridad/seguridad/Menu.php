<meta name="viewport" content="width=device-width, user-scalable=no">

<? session_start(); ?>

<script type="text/javascript">
	$(document).ready(function(){
		enviarPeticion('permisos/menu',
			{'':''}, 
			function(rta){
				if (rta.tipo == 'error')
					alert(rta.mensaje)
				else if (rta.tipo == 'exito')
					$('#ul_menu').html(rta.mensaje);
			}
		);
	});
</script>

<nav class="navbar navbar-dark navbar-expand-sm bg-dark">
	<p>
		<h5 class="text-white">
			<a href="../seguridad/principal"> Smart Trader </a>
		</h5>
	</p>
	<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#nav_menu" aria-controls="nav_menu" aria-expanded="false" aria-label="Toggle navigation">
		<span class="navbar-toggler-icon"></span>
	</button>
	<div class="collapse navbar-collapse" id="nav_menu">
		<ul class="navbar-nav mr-auto" id="ul_menu"></ul>
		<ul class="navbar-nav">
			<li class="nav-item dropdown dropleft">
				<a class="nav-link dropdown-toggle" href="#" id="mod_user" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
					​<picture>
						<img src="/apps/SmartTrader/recursos/imagenes/avatar2.png" class="rounded-circle" title="<?=$_SESSION['usuario_sesion'][0]['usua_nombre']?>" width="30">
					</picture>
				</a>
				<div class="dropdown-menu" aria-labelledby="mod_user">
					<div class="col-sm-12">
						<p class="texto-12"><?=$_SESSION['usuario_sesion'][0]['usua_nombre']?></p>
						<p class="texto-12"><?=$_SESSION['usuario_sesion'][0]['perf_descripcion']?></p>
						<p>
							<button class="btn btn-sm btn-danger" onclick="salir()">Salir</button>
						</p>
					</div>
				</div>
			</li>
		</ul>
	</div>
</nav>
<br>