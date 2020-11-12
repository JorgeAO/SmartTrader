<?

include '../app/modelos/ClsPermisos.php';

class Control
{
	protected $intOpcion;
	protected $strClase = '';

	public function insertar($arrParametros)
	{
		try 
		{
			if (!ClsPermisos::blValidarPermiso($this->intOpcion, 'c'))
				throw new Exception('Usted no posee permisos para ejecutar esta acción');
			
			session_start();
			$arrParametros['fc'] = date('Y-m-d H:m:s');
			$arrParametros['uc'] = $_SESSION['usuario_sesion'][0]['usua_codigo'];

			//include_once '../../app/modelos/'.$this->strClase.'.php';
			$clase = new $this->strClase();
			$clase::insertar($arrParametros);

			$objRta->tipo = 'exito';
			$objRta->mensaje = 'El proceso se realizó con éxito';
			return $objRta;
		} 
		catch (Exception $e) 
		{
			throw new Exception($e->getMessage());
		}
	}

	public function consultar($arrParametros = [])
	{
		try 
		{
			if (!ClsPermisos::blValidarPermiso($this->intOpcion, 'r'))
				throw new Exception('Usted no posee permisos para ejecutar esta acción');

			//include_once '../../app/modelos/'.$this->strClase.'.php';
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

	public function editar($arrParametros)
	{
		try 
		{
			if (!ClsPermisos::blValidarPermiso($this->intOpcion, 'u'))
				throw new Exception('Usted no posee permisos para ejecutar esta acción');
			
			session_start();
			$arrParametros['fm'] = date('Y-m-d H:m:s');
			$arrParametros['um'] = $_SESSION['usuario_sesion'][0]['usua_codigo'];

			//include_once '../../app/modelos/'.$this->strClase.'.php';
			$clase = new $this->strClase();
			$clase::editar($arrParametros);

			$objRta->tipo = 'exito';
			$objRta->mensaje = 'El proceso se realizó con éxito';
			return $objRta;
		} 
		catch (Exception $e) 
		{
			throw new Exception($e->getMessage());
		}
	}

	public function eliminar($arrParametros)
	{
		try 
		{
			if (!ClsPermisos::blValidarPermiso($this->intOpcion, 'd'))
				throw new Exception('Usted no posee permisos para ejecutar esta acción');

			//include_once '../../app/modelos/'.$this->strClase.'.php';
			$clase = new $this->strClase();
			$clase::eliminar($arrParametros);

			$objRta->tipo = 'exito';
			$objRta->mensaje = 'El proceso se realizó con éxito';
			return $objRta;
		} 
		catch (Exception $e) 
		{
			throw new Exception($e->getMessage());
		}
	}

	public function listar($arrParametros = [])
	{
		try 
		{
			if (!ClsPermisos::blValidarPermiso($this->intOpcion, 'l'))
				throw new Exception('Usted no posee permisos para ejecutar esta acción');

			//include_once '../../app/modelos/'.$this->strClase.'.php';
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