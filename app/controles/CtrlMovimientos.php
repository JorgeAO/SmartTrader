<?

include_once '../app/modelos/ClsMovimientos.php';
include_once '../app/modelos/ClsInversionistas.php';
include_once '../nucleo/Control.php';

class CtrlMovimientos extends Control
{
	protected $intOpcion = 3001;
	protected $strClase = 'ClsMovimientos';
	

	public function insertar($arrParametros)
	{
		try 
		{
			if (!ClsPermisos::blValidarPermiso($this->intOpcion, 'c'))
				throw new Exception('Usted no posee permisos para ejecutar esta acción');

			$arrInversionista = ClsInversionistas::consultar([
				'inve_codigo' => $arrParametros['fk_par_inversionistas']
			]);

			// Si el movimiento es una salida, se valida que tenga fondos suficientes
			if ($arrParametros['movi_tipo'] == 'S')
			{
				if ($arrInversionista[0]['inve_saldo'] < $arrParametros['movi_monto'])
					throw new Exception('El inversionista no tiene saldo suficiente para este retiro');
			}

			ClsInversionistas::editar([
				'inve_codigo' => $arrParametros['fk_par_inversionistas'],
				'inve_saldo' => $arrParametros['movi_tipo'] == 'E' ? 
					($arrInversionista[0]['inve_saldo'] + $arrParametros['movi_monto']) : 
					($arrInversionista[0]['inve_saldo'] - $arrParametros['movi_monto']),
				'fc' => date('Y-m-d H:m:s'),
				'uc' => $_SESSION['usuario_sesion'][0]['usua_codigo']
			]);

			ClsMovimientos::insertar($arrParametros);

			$objRta->tipo = 'exito';
			$objRta->mensaje = 'El proceso se realizó con éxito';
			return $objRta;

			print_r($arrParametros);
			exit();
		} 
		catch (Exception $e) 
		{
			throw new Exception('CtrlMovimientos.insertar: '.$e->getMessage());
		}
	}
}