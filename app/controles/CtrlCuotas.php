<?

include_once '../app/modelos/ClsCuotas.php';
include_once '../nucleo/Control.php';

class CtrlCuotas extends Control
{
	protected $intOpcion = 4001;
    protected $strClase = 'ClsCuotas';
    
    public function cuotasPorFecha($arrParametros = [])
    {
        try 
        {
            $sqlCuotas = "select 
                    pres.fk_par_clientes,
                    pres.pres_vlr_saldo,
                    clie.clie_nombre,
                    clie.clie_apellido,
                    clie.clie_celular,
                    clie.clie_direccion,
                    prcu.prcu_codigo,
                    prcu.fk_pre_prestamos,
                    prcu.prcu_numero,
                    prcu.prcu_fecha,
                    prcu.prcu_fecha_pago,
                    prcu.prcu_valor,
                    prcu.prcu_vlr_saldo,
                    prcu.prcu_valor_pago,
                    prcu.fk_par_estados,
                    pces.esta_descripcion
                from tb_pre_cuotas prcu
                join tb_pre_prestamos pres on (prcu.fk_pre_prestamos = pres.pres_codigo)
                join tb_par_clientes clie on (pres.fk_par_clientes = clie.clie_codigo)
                join tb_par_estados pces on (prcu.fk_par_estados = pces.esta_codigo)
                where prcu.prcu_fecha = '".$arrParametros['fecha']."'";

            $arrCuotas = BaseDatos::ejecutarSentencia($sqlCuotas);

			$objRta->tipo = 'exito';
			$objRta->datos = $arrCuotas;
			return $objRta;
        } 
        catch (Exception $e)
        {
            throw new Exception('CtrlCuotas:cuotasPorFecha: '+$e->getMessage());
        }
    }
}