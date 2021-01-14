<?

include_once 'BaseDatos.php';

class Modelo
{
	protected $strTabla = '';
	protected $strLlavePrimaria = '';
	protected $sqlSentencia = '';

	public static function insertar($arrParametros)
	{
		try 
		{
			$modelo = new static();

			$sqlSentencia = "insert into ".$modelo->strTabla." set ";

			foreach ($arrParametros as $strCampo => $strValor)
				$sqlSentencia .= $strCampo." = '".$strValor."', ";

			$sqlSentencia = rtrim($sqlSentencia, ', ');

			$blDebug = 0;
			if ($blDebug && $modelo->strTabla == 'tb_caj_movimientos')
			{
				echo '<pre>';
				print_r($sqlSentencia);
				echo '</pre>';
				exit();
			}

			$arrRta = BaseDatos::ejecutarSentencia($sqlSentencia);

			return $arrRta;
		} 
		catch (Exception $e) 
		{
			throw new Exception($e->getMessage());
		}
	}

	public static function consultar($arrParametros = [])
	{
		try 
		{
			$modelo = new static();

			$sqlWhere = '';

			foreach ($arrParametros as $strCampo => $strValor)
			{
				if (is_numeric($strValor) && $strValor != 0)
					$sqlWhere .= $strCampo." = ".$strValor." and ";
				elseif (is_string($strValor) && $strValor != '') 
				{
					if (strpos($strValor, ','))
						$sqlWhere .= $strCampo.' in ('.$strValor.') and ';
					else
						$sqlWhere .= $strCampo." = '".$strValor."' and ";
				}
			}

			$sqlWhere = rtrim($sqlWhere, ' and ');

			if ($sqlWhere != '')
				$modelo->sqlSentencia .= ' where '.$sqlWhere;
						
			$blDebug = 0;
			if ($modelo->strTabla == 'tb_par_estados' && $blDebug)
			{
				echo $modelo->sqlSentencia; 
				exit();
			}

			$arrResultado = BaseDatos::ejecutarSentencia($modelo->sqlSentencia);

			return $arrResultado;
		} 
		catch (Exception $e) 
		{
			throw new Exception($e->getMessage());
		}
	}

	public static function editar($arrParametros)
	{
		try 
		{
			$modelo = new static();

			$sqlSentencia = "update ".$modelo->strTabla." set ";

			foreach ($arrParametros as $strCampo => $strValor)
			{
				if ($strCampo != $modelo->strLlavePrimaria) 
					$sqlSentencia .= $strCampo." = '".$strValor."', ";
			}

			$sqlSentencia = rtrim($sqlSentencia, ', ');

			$sqlSentencia .= ' where '.$modelo->strLlavePrimaria.' = '.$arrParametros[$modelo->strLlavePrimaria];
			
			$blDebug = false;
			if ($blDebug && $modelo->strTabla == 'tb_seg_perfiles')
			{
				echo '<pre>';
				print_r($arrParametros);
				echo '</pre>';
				echo $sqlSentencia; exit();
			}

			$arrRta = BaseDatos::ejecutarSentencia($sqlSentencia);

			return $arrRta;
		} 
		catch (Exception $e) 
		{
			throw new Exception($e->getMessage());
		}
	}

	public static function eliminar($arrParametros)
	{
		try 
		{
			$modelo = new static();

			$sqlSentencia = "delete from ".$modelo->strTabla." where ";

			foreach ($arrParametros as $strCampo => $strValor)
				$sqlSentencia .= $strCampo." = '".$strValor."', ";

			$sqlSentencia = rtrim($sqlSentencia, ', ');

			$arrRta = BaseDatos::ejecutarSentencia($sqlSentencia);

			return $arrRta;
		} 
		catch (Exception $e) 
		{
			throw new Exception($e->getMessage());
		}
	}
}