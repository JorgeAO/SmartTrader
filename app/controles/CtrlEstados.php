<?

include_once '../app/modelos/ClsEstados.php';
include_once '../nucleo/Control.php';

class CtrlEstados extends Control
{
	protected $strClase = 'ClsEstados';

	public function listar($arrParametros = [])
	{
		try 
		{
			$clase = new $this->strClase();
			$arrResultados = $clase::consultar($arrParametros);

			$objRta->tipo = 'exito';
			$objRta->datos = $arrResultados;
			return $objRta;
		} 
		catch (Exception $e) 
		{
			throw new Exception($e->getMessage());
		}
	}
}