<?

include_once '../nucleo/BaseDatos.php';
include_once '../nucleo/Modelo.php';

class ClsPermisos extends Modelo
{
    protected $strTabla = 'tb_seg_permisos';
    protected $strLlavePrimaria = 'perm_codigo';
    protected $sqlSentencia = 'select 
        PERM.perm_codigo, PERM.fk_seg_perfiles, PERM.fk_seg_opciones, PERM.perm_c, PERM.perm_r, PERM.perm_u, PERM.perm_d, PERM.perm_l, 
        PERF.perf_descripcion, 
        OPCI.fk_seg_modulos, OPCI.opci_nombre, OPCI.opci_enlace, 
        MODU.modu_descripcion, MODU.modu_icono
        from tb_seg_permisos PERM 
        join tb_seg_perfiles PERF on (PERF.perf_codigo = PERM.fk_seg_perfiles) 
        join tb_seg_opciones OPCI on (OPCI.opci_codigo = PERM.fk_seg_opciones)
        join tb_seg_modulos MODU on (MODU.modu_codigo = OPCI.fk_seg_modulos)';

	public function arrConsultarPermisos($intPerfil)
    {
        try
        {
            $sqlSentencia = "select 
                PERM.perm_codigo, PERM.fk_seg_perfiles, PERM.fk_seg_opciones, PERM.perm_c, PERM.perm_r, PERM.perm_u, PERM.perm_d, PERM.perm_l, 
                PERF.perf_descripcion, 
                OPCI.fk_seg_modulos, OPCI.opci_nombre, OPCI.opci_enlace, 
                MODU.modu_descripcion, MODU.modu_icono
                from tb_seg_permisos PERM 
                join tb_seg_perfiles PERF on (PERF.perf_codigo = PERM.fk_seg_perfiles) 
                join tb_seg_opciones OPCI on (OPCI.opci_codigo = PERM.fk_seg_opciones)
                join tb_seg_modulos MODU on (MODU.modu_codigo = OPCI.fk_seg_modulos)
                where (PERM.fk_seg_perfiles = ".$intPerfil.") 
                and (PERF.fk_par_estados = 1) 
                and (OPCI.fk_par_estados = 1)
				order by OPCI.fk_seg_modulos, OPCI.opci_nombre;";
            
            $arrResultado = BaseDatos::ejecutarSentencia($sqlSentencia);
            
            return $arrResultado;
        } 
        catch (Exception $ex)
        {
            throw new Exception($ex->getMessage());
        }
    }

	public function strMenuNav($intPerfil)
	{
		$arrPermisos = self::arrConsultarPermisos($intPerfil);

            $strModulo = "";

            $blIniciado = false;

            $strMenu = '<li class="nav-item">
              <a class="nav-link texto-12" aria-current="page" href="/apps/SmartTrader/seguridad/principal"><i class="fa fa-home"></i> Inicio</a>
            </li>';

            /**
             * Recorrer los permisos de clientes que se encontraron
             */
            for ($i = 0; $i < count($arrPermisos); $i++) 
            {
                /**
                 * Si tiene permisos C(create), R(read), U(update), D(delete) se continúa con el proceso de construcción del menú
                 * Ésto se hace por que se creó el permiso L(list) que se utiliza para mostrar los registros como lista desplegable
                 * Si un perfil tiene sólo la opción L habilitada, la opción no se mostrará en el menú, pero si tendrá permisos para
                 * verlo como lista desplegable
                 */
                if ($arrPermisos[$i]['perm_c'] != 0 || $arrPermisos[$i]['perm_r'] != 0 || $arrPermisos[$i]['perm_u'] != 0 || $arrPermisos[$i]['perm_d'] != 0)
                {
                    /**
                     * Si el módulo que está en memoria (módulo que se está maquetando en el menú en este momento)
                     * ese diferente del menú al que pertenece la opción que se está evaluando, se crea una nueva
                     * sección en el menú
                     */
                    if ($strModulo != $arrPermisos[$i]['modu_descripcion'])
                    {
                        /**
                         * Si ya se había iniciado el menú del módulo se cierra
                         */
                        if ($blIniciado)
                            $strMenu .= '</div></li>';

                        // Nombre del módulo al que se le va a construir el menú
                        $strModulo = $arrPermisos[$i]['modu_descripcion'];
                        
                        // Construir opción del menú
                        $strMenu .= '<li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle texto-12" data-toggle="dropdown" href="#mod_'.$arrPermisos[$i]['fk_seg_modulos'].'"><i class="fa fa-'.$arrPermisos[$i]['modu_icono'].'"></i> '.$arrPermisos[$i]['modu_descripcion'].'</a>
                            <div class="dropdown-menu" id="mod_'.$arrPermisos[$i]['fk_seg_modulos'].'">';
                        
                        // Marcar el menú como iniciado
                        $blIniciado = true;
                    }

                    /**
                     * Si la opción tiene enlace, se incluye en el menú
                     */
                    if ($arrPermisos[$i]['opci_enlace'] != '')
                        $strMenu .= '<a class="dropdown-item texto-12" href="/apps/SmartTrader/'.$arrPermisos[$i]['opci_enlace'].'">'.$arrPermisos[$i]['opci_nombre'].'</a>';
                }
            }

            //return $strMenu;

            $strMenu .= '</div></li>';

            return $strMenu;
	}

	public static function blValidarPermiso($intOpcion, $strAccion)
	{
		try 
		{
			if (!isset($_SESSION['usuario_sesion'][0]['fk_seg_perfiles']))
                session_start();

            $strSentencia = "select perm_".$strAccion." 
                from tb_seg_permisos 
                where fk_seg_perfiles = ".$_SESSION['usuario_sesion'][0]['fk_seg_perfiles']." 
                and fk_seg_opciones = ".$intOpcion."
                and perm_".$strAccion." = 1;";

            $arrConsulta = BaseDatos::ejecutarSentencia($strSentencia);

            if (count($arrConsulta) == 1)
            	return true;
            else
            	return false;
		} 
		catch (Exception $e) 
		{
			throw new Exception($e->getMessage());
		}
	}

    public static function arrVerPermisos($intPerfil)
    {
        try
        {
            $strSentencia = "select distinct opci.fk_seg_modulos, modu.modu_descripcion, opci.opci_codigo, opci.opci_nombre,
                (case (select p.perm_c from tb_seg_permisos p where (p.fk_seg_opciones = opci.opci_codigo and p.fk_seg_perfiles = ".$intPerfil."))
                when 1 then 'checked'
                when 0 then 'false'
                when null then 'false'
                else 'false'
                end) as perm_c, 
                (case (select p.perm_r from tb_seg_permisos p where (p.fk_seg_opciones = opci.opci_codigo and p.fk_seg_perfiles = ".$intPerfil."))
                when 1 then 'checked'
                when 0 then 'false'
                when null then 'false'
                else 'false'
                end) as perm_r, 
                (case (select p.perm_u from tb_seg_permisos p where (p.fk_seg_opciones = opci.opci_codigo and p.fk_seg_perfiles = ".$intPerfil."))
                when 1 then 'checked'
                when 0 then 'false'
                when null then 'false'
                else 'false'
                end) as perm_u, 
                (case (select p.perm_d from tb_seg_permisos p where (p.fk_seg_opciones = opci.opci_codigo and p.fk_seg_perfiles = ".$intPerfil."))
                when 1 then 'checked'
                when 0 then 'false'
                when null then 'false'
                else 'false'
                end) as perm_d, 
                (case (select p.perm_l from tb_seg_permisos p where (p.fk_seg_opciones = opci.opci_codigo and p.fk_seg_perfiles = ".$intPerfil."))
                when 1 then 'checked'
                when 0 then 'false'
                when null then 'false'
                else 'false'
                end) as perm_l
                from tb_seg_opciones opci 
                join tb_seg_modulos modu on (opci.fk_seg_modulos = modu.modu_codigo)
                left join tb_seg_permisos perm on (opci.opci_codigo = perm.fk_seg_opciones)
                order by opci.opci_codigo asc";
            
            //echo $strSentencia; exit();
            
            $arrResultado = BaseDatos::ejecutarSentencia($strSentencia);
            
            return $arrResultado;
        } 
        catch (Exception $ex)
        {
            throw new Exception($ex->getMessage());
        }
    }
}