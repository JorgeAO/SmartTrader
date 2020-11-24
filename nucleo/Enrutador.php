<?

session_start();

$objRta = new stdClass();

try 
{
	$arrExcepciones = array('usuarios/validar', 'usuarios/recuperar', 'usuarios/reestablecer');

	if (!in_array($_GET['peticion'], $arrExcepciones))
	{
		if (!isset($_SESSION['usuario_sesion'][0]['usua_login']) || $_SESSION['usuario_sesion'][0]['usua_login'] == '')
		{
			$obRta->tipo = 'redirect';
			$obRta->ruta = '/apps/SmartTrader/';
			echo json_encode($obRta);
			exit();
		}
	}

	$arrPeticion = explode('/', $_GET['peticion']);

	$ctrlNombre = 'Ctrl'.ucfirst($arrPeticion[0]);
	$strMetodo = $arrPeticion[1];

	if (!file_exists('../app/controles/'.$ctrlNombre.'.php'))
		throw new Exception('No existe el control "'.$ctrlNombre.'"');
		
	include '../app/controles/'.$ctrlNombre.'.php';
	$control = new $ctrlNombre();

	if (!method_exists($control, $arrPeticion[1]))
		throw new Exception('No se encuentra definido el método "'.$arrPeticion[1].'" en el control "'.$ctrlNombre.'"');

	$objRta = $control->$strMetodo($_POST['parametros']);
} 
catch (Exception $e) 
{
	$objRta->tipo = 'error';
	$objRta->mensaje = $e->getMessage();
}

ob_clean();
echo json_encode($objRta);