<?

class BaseDatos
{
	private function arrObtenerDatosCnx($strBaseDatos)
	{
		try 
		{
			$arrDatosCnx['desarrollo'] = [
				'servidor' => 'localhost',
				'usuario' => 'user_smarttrader',
				'clave' => '',
				'basedatos' => 'bd_smarttrader',
			];
			$arrDatosCnx['produccion'] = [
				'servidor' => 'localhost',
				'usuario' => 'root',
				'clave' => 'st2020.*',
				'basedatos' => 'bd_smarttrader',
			];
			$arrDatosCnx['pruebas'] = [
				'servidor' => 'localhost',
				'usuario' => 'user_smarttrader',
				'clave' => 'st2020.*',
				'basedatos' => 'bd_smarttrader_test',
			];

			return $arrDatosCnx[$strBaseDatos];
		} 
		catch (Exception $e) 
		{
			throw new Exception($e->getMessage());
		}
	}

	private function cnxConectar($strDestino = 'produccion')
	{
		try 
		{
			$arrDatosCnx = self::arrObtenerDatosCnx($strDestino);

			$cnxConexion = mysqli_connect(
				$arrDatosCnx['servidor'], 
				$arrDatosCnx['usuario'], 
				$arrDatosCnx['clave'], 
				$arrDatosCnx['basedatos']
			);

			if (mysqli_connect_errno())
				throw new Exception(mysqli_connect_error());

			mysqli_set_charset($cnxConexion, 'utf8');
			return $cnxConexion;
		} 
		catch (Exception $e) 
		{
			throw new Exception($e->getMessage());
		}
	}

	public static function ejecutarSentencia($sqlSentencia)
	{
		try 
		{
			$obCnx = self::cnxConectar();

			$rslConsulta = mysqli_query($obCnx, $sqlSentencia);

			if (!$rslConsulta)
				throw new Exception($obCnx->error);

			$arrFilas = array();

			if (is_object($rslConsulta))
			{
				while ($drFila = mysqli_fetch_assoc($rslConsulta))
					$arrFilas[] = $drFila;
			}
			else if ($rslConsulta === TRUE)
			{
				$arrFilas['insert_id'] = $obCnx->insert_id;
				$arrFilas['affected_rows'] = $obCnx->affected_rows;
			}

			return $arrFilas;
		} 
		catch (Exception $e) 
		{
			throw new Exception($e->getMessage());
		}
	}
}