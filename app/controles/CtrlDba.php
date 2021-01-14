<?

include_once '../nucleo/BaseDatos.php';

class CtrlDba
{
	protected $intOpcion = 1005;
    protected $strClase = '';
    
    public function consultar($arrParametros = [])
    {
        try 
        {
            $clsBD = new BaseDatos();

            error_reporting(E_ALL);
            ini_set('display_errors', '1');

            $arrDbas = scandir('../app/dba');

            $arrRespuesta = [];

            //foreach ($arrDbas as $dba)
            for ($i = 0; $i < count($arrDbas); $i++)
            {
                $dba = $arrDbas[$i];

                if (!in_array($dba, [ '.', '..' ]))
                {
                    $arrArchivo = explode('.', $dba);

                    if ($arrArchivo[1] == 'sql')
                    {
                        $arrRegistro = [];

                        $arrRegistro['codigo'] = $arrArchivo[0];

                        $arrDbaBD = BaseDatos::ejecutarSentencia("select * from tb_seg_dba where dba_id = '".$arrArchivo[0]."';");
                        $arrRegistro['ejecutada'] = (count($arrDbaBD) == 1 ? $arrDbaBD[0]['dba_ejecutada'] : 0);

                        $arrRegistro['sql'] = file_get_contents('../app/dba/'.$dba);
                        
                        array_push($arrRespuesta, $arrRegistro);
                    }
                }
            }
            
            echo "<pre>";
            print_r($arrRespuesta);
            echo "</pre>";
            exit();
        } 
        catch (Exception $e)
        {
            throw new Exception($e->getMessage());
        }
    }
}