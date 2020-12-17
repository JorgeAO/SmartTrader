<?

include_once '../nucleo/Modelo.php';

class ClsPrestamos extends Modelo
{
	protected $strTabla = 'tb_pre_prestamos';
	protected $strLlavePrimaria = 'pres_codigo';
	protected $sqlSentencia = "select 
		pres.*, 
		(case pres_frecuencia
			when 'S' then 'Semanal'
			when 'Q' then 'Quincenal'
			when 'M' then 'Mensual'
		end) as pres_frecuencia2,
		clie.clie_nombre,
		clie.clie_apellido,
		clie.clie_celular,
		clie.clie_correo,
		esta.esta_descripcion
		from tb_pre_prestamos pres 
		join tb_par_clientes clie on (pres.fk_par_clientes = clie.clie_codigo) 
		join tb_par_estados esta on (pres.fk_par_estados = esta.esta_codigo) 
		";
}