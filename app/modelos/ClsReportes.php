<?

include_once '../../nucleo/BaseDatos.php';

class ClsReportes
{
	public static function nuevos($arrParametros)
	{
		try 
		{
            $sqlSentencia = "select 
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

            $sqlWhere = '';

            foreach ($arrParametros as $key => $value) 
            {
                // Si tiene el caracter '~' se asume la sentencia between
                if ($key[0] == '~')
                    $sqlWhere .= str_replace('~', '', $key).' between "'.explode(',',$value)[0].'" and "'.explode(',',$value)[1].'" and ';
            }

			$sqlWhere = rtrim($sqlWhere, ' and ');

			if ($sqlWhere != '')
                $sqlSentencia .= ' where '.$sqlWhere;
                
            $arrResultado = BaseDatos::ejecutarSentencia($sqlSentencia);

            return $arrResultado;
		} 
		catch (Exception $e) 
		{
			throw new Exception($e->getMessage());
		}
	}
}