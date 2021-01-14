<?php
error_reporting(E_ALL);
ini_set('error_reporting', E_ALL);

include '../../nucleo/BaseDatos.php';

try 
{
    $sqlCuotas = "select * 
        from ( 
            select 
                cuot.fk_pre_prestamos, 
                count(cuot.prcu_codigo) as cant, 
                ( 
                    select count(1) 
                    from tb_pre_cuotas x 
                    where (x.fk_par_estados = 4) 
                    and (cuot.fk_pre_prestamos = x.fk_pre_prestamos) 
                ) as yaPagados 
            from tb_pre_cuotas cuot 
            join tb_pre_prestamos pres on (cuot.fk_pre_prestamos = pres.pres_codigo) 
            where (pres.fk_par_estados = 1) 
            group by cuot.fk_pre_prestamos 
        ) as y 
        where y.cant = y.yaPagados";
    
    $arrCuotas = BaseDatos::ejecutarSentencia($sqlCuotas);
    
    foreach ($arrCuotas as $arrCuota)
        BaseDatos::ejecutarSentencia("update tb_pre_prestamos set fk_par_estados = 7 where pres_codigo = ".$arrCuota['fk_pre_prestamos'].";");
} 
catch (Execption $e)
{
    echo $e->getMessage();
}

?>