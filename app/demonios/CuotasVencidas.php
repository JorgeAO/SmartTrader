<?php
error_reporting(E_ALL);
ini_set('error_reporting', E_ALL);

include '../../nucleo/BaseDatos.php';

try 
{
    $sqlCuotasAntiguas = "select * from tb_pre_cuotas where prcu_fecha < '".date('Y-m-d')." 00:00:00' and fk_par_estados = 3 order by prcu_fecha asc";
    
    $arrCuotasAntiguas = BaseDatos::ejecutarSentencia($sqlCuotasAntiguas);
    
    foreach ($arrCuotasAntiguas as $arrCuota)
        BaseDatos::ejecutarSentencia("update tb_pre_cuotas set fk_par_estados = 5 where prcu_codigo = ".$arrCuota['prcu_codigo'].";");
} 
catch (Execption $e)
{
    echo $e->getMessage();
}

?>