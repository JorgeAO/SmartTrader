<?php

include_once '../app/modelos/ClsReportes.php';
include_once '../nucleo/Control.php';

class CtrlReportes extends Control
{
	protected $intOpcion = 5001;
    protected $strClase = 'ClsReportes';

    public function nuevos($arrParametros)
	{
		try 
		{
            $arrPrestamos = ClsReportes::nuevos($arrParametros);

			$objRta->tipo = 'exito';
			$objRta->datos = $arrPrestamos;
			return $objRta;
		} 
		catch (Exception $e) 
		{
			throw new Exception('CtrlReportes.nuevos: '.$e->getMessage());
		}
	}
}