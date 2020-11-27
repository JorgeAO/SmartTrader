<?php

include_once '../app/modelos/ClsPrestamos.php';
include_once '../app/modelos/ClsClientes.php';
include_once '../app/modelos/ClsInversionistas.php';
include_once '../nucleo/Control.php';

class CtrlPrincipal
{
	public function consultarTarjetas($arrParametros = [])
    {
        try 
        {
            // Establecer zona horaria
            setlocale (LC_ALL, "es_ES");
	        date_default_timezone_set ('America/Bogota'); 
            
            // Consultar prestamos
            $clsPrestamos = new ClsPrestamos();
            $arrPrestamos = $clsPrestamos->consultar([ 'pres.fk_par_estados' => 1 ]);

            // Consultar clientes
            $clsClientes = new ClsClientes();
            $arrClientes = $clsClientes->consultar([ 'clie.fk_par_estados' => 1 ]);

            // Consultar inversionistas
            $clsInversionistas = new ClsInversionistas();
            $arrInversionistas = $clsInversionistas->consultar([ 'inve.fk_par_estados' => 1 ]);

            // Consultar cuánto se debe recoger hoy
            $arrTotalHoy = BaseDatos::ejecutarSentencia("select coalesce(sum(prcu_vlr_saldo), 0) as total_hoy from tb_pre_cuotas where prcu_fecha = '".$arrParametros['fecha']."' and fk_par_estados not in (6)");

            // Consultar cuánto se ha recogido hoy
            $arrTotalRecogido = BaseDatos::ejecutarSentencia("select coalesce(sum(prcu_vlr_saldo), 0) as total_recogido from tb_pre_cuotas where fk_par_estados = 4 and prcu_fecha = '".$arrParametros['fecha']."'");

            
            $arrDatos['fecha'] = date('Y-m-d');
            $arrDatos['prestamos'] = count($arrPrestamos);
            $arrDatos['clientes'] = count($arrClientes);
            $arrDatos['inversionistas'] = count($arrInversionistas);
            $arrDatos['total_hoy'] = $arrTotalHoy[0]['total_hoy'];
            $arrDatos['total_recogido'] = $arrTotalRecogido[0]['total_recogido'];

			$objRta->tipo = 'exito';
			$objRta->datos = $arrDatos;
			return $objRta;
        } 
        catch (Exception $e) 
        {
            throw new Exception($e->getMessage());
        }
    }
}

?>