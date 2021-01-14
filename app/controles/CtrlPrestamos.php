<?php

include_once '../app/modelos/ClsPrestamos.php';
include_once '../app/modelos/ClsInversionistas.php';
include_once '../app/modelos/ClsCuotas.php';
include_once '../app/modelos/ClsMovimientos.php';
include_once '../app/modelos/ClsParticipacion.php';
include_once '../nucleo/Control.php';

class CtrlPrestamos extends Control
{
	protected $intOpcion = 4001;
	protected $strClase = 'ClsPrestamos';

	public function intCalcular($arrParametros)
	{
		try 
		{

			$strMensaje = '';

			if ($arrParametros['pres_vlr_monto'] == '')
				$strMensaje .= 'Ingrese el valor\n';
			if ($arrParametros['pres_nro_cuotas'] == '')
				$strMensaje .= 'Ingrese el número de cuotas\n';
			if ($arrParametros['pres_plazo'] == '')
				$strMensaje .= 'Ingrese el plazo\n';
			if ($arrParametros['pres_interes'] == '')
				$strMensaje .= 'Ingrese el interés\n';
			
			if (!empty($strMensaje))
				throw new Exception('\n'.$strMensaje);

			$flInteresMensual = $arrParametros['pres_vlr_monto'] * $arrParametros['pres_interes'] / 100;
			$flInteresTotal = $flInteresMensual * $arrParametros['pres_plazo'];
			$flTotalPago = $arrParametros['pres_vlr_monto'] + $flInteresTotal;
			$flCuota = $flTotalPago / $arrParametros['pres_nro_cuotas'];

			$arrCalculo[0]['pres_int_mensual'] = round($flInteresMensual, -2);
			$arrCalculo[0]['pres_int_total'] = round($flInteresTotal, -2);
			$arrCalculo[0]['pres_vlr_pago'] = round($flTotalPago, -2);
			$arrCalculo[0]['pres_vlr_cuota'] = round($flCuota, -2);

			$objRta->tipo = 'exito';
			$objRta->datos = $arrCalculo;
			return $objRta;
		} 
		catch (Exception $e) 
		{
			throw new Exception('CtrlPrestamos.intCalcular: '.$e->getMessage());
		}
	}

	public function insertar($arrParametros)
	{
		try 
		{
			if (!ClsPermisos::blValidarPermiso($this->intOpcion, 'c'))
				throw new Exception('Usted no posee permisos para ejecutar esta acción');

			// Consultar los inversionistas activos
			$arrInversionistas = ClsInversionistas::consultar([
				'inve.fk_par_estados' => 1
			]);

			// Calcular el capital disponible y el mínimo por inversionista
			$dbCapitalDispo = 0;
			$arrMontoXInver = array();
			foreach ($arrInversionistas as $key => $value)
			{
				$dbCapitalDispo += $value['inve_saldo'];
				$arrMontoXInver[$value['inve_codigo']]['inve_saldo'] = $value['inve_saldo'];
				$arrMontoXInver[$value['inve_codigo']]['inve_saldo_min'] = $value['inve_saldo_min'];
			}

			if ($arrParametros['pres_vlr_monto'] > $dbCapitalDispo)
				throw new Exception('No hay capital suficiente para realizar el préstamo');

			// Calcular porcentaje de partcipación de los inversionistas
			// la cantidad que aporta cada uno
			// el saldo restante
			foreach ($arrMontoXInver as $key => $value) 
			{
				$dbPorcentaje = 0;
				$dbAporte = 0;
				$dbSaldo = 0;
				
				$dbPorcentaje = round(($value['inve_saldo'] / $dbCapitalDispo * 100), 2);
				$dbAporte = $arrParametros['pres_vlr_monto'] * $dbPorcentaje / 100;
				$dbSaldo = $value['inve_saldo'] - $dbAporte;

				$arrMontoXInver[$key]['part_porcentaje'] = $dbPorcentaje;
				$arrMontoXInver[$key]['part_monto'] = $dbAporte;
				$arrMontoXInver[$key]['saldo_final'] = $dbSaldo;
			}

			// Insertar el registro del préstamo
			$arrPrestamo = ClsPrestamos::insertar([
				'fk_par_clientes' => $arrParametros['fk_par_clientes'],
				'pres_fecha' => $arrParametros['pres_fecha'],
				'pres_vlr_monto' => $arrParametros['pres_vlr_monto'],
				'pres_frecuencia' => $arrParametros['pres_frecuencia'],
				'pres_nro_cuotas' => $arrParametros['pres_nro_cuotas'],
				'pres_plazo' => $arrParametros['pres_plazo'],
				'pres_interes' => $arrParametros['pres_interes'],
				'pres_int_mensual' => $arrParametros['pres_int_mensual'],
				'pres_int_mensual' => $arrParametros['pres_int_mensual'],
				'pres_int_total' => $arrParametros['pres_int_total'],
				'pres_vlr_pago' => $arrParametros['pres_vlr_pago'],
				'pres_vlr_saldo' => $arrParametros['pres_vlr_pago'],
				'pres_vlr_cuota' => $arrParametros['pres_vlr_cuota'],
				'fc' => date('Y-m-d H:m:s'),
				'uc' => $_SESSION['usuario_sesion'][0]['usua_codigo']
			]);

			// Calular la cuotas y crear el registro de cada una
			for ($i = 1; $i <= $arrParametros['pres_nro_cuotas']; $i++)
			{
				$strFrecuencia = '';
				if ($arrParametros['pres_frecuencia'] == 'S')
					$strFrecuencia = $i.' weeks';
				else if ($arrParametros['pres_frecuencia'] == 'Q')
					$strFrecuencia = ($i*2).' weeks';
				else if ($arrParametros['pres_frecuencia'] == 'M')
					$strFrecuencia = $i.' months';

				if ($strFrecuencia == '')
					throw new Exception('No se pudo calcular la frecuencia de las cuotas');

				ClsCuotas::insertar([
					'fk_pre_prestamos' => $arrPrestamo['insert_id'],
					'prcu_numero' => $i,
					'prcu_fecha' => date('Y-m-d', strtotime('+'.$strFrecuencia, strtotime($arrParametros['pres_fecha']))),
					'prcu_valor' => $arrParametros['pres_vlr_cuota'],
					'prcu_vlr_saldo' => $arrParametros['pres_vlr_cuota'],
				]);
			}

			/**
			 * Modificar el saldo de cada inversionista
			 * Insertar el registro del movimiento en la caja
			 * Insertar el registro de participación en el préstamo
			 */
			foreach ($arrMontoXInver as $key => $value)
			{
				ClsInversionistas::editar([
					'inve_codigo' => $key,
					'inve_saldo' => $value['saldo_final']
				]);

				ClsMovimientos::insertar([
					'fk_par_inversionistas' => $key,
					'movi_tipo' => 'S',
					'movi_descripcion' => 'Participación en el prestamo '.$arrPrestamo['insert_id'],
					'movi_fecha' => $arrParametros['pres_fecha'],
					'movi_monto' => $value['part_monto'],
					'fc' => date('Y-m-d H:m:s'),
					'uc' => $_SESSION['usuario_sesion'][0]['usua_codigo']
				]);

				ClsParticipacion::insertar([
					'fk_pre_prestamos' => $arrPrestamo['insert_id'],
					'fk_par_inversionistas' => $key,
					'prpa_porcentaje' => $value['part_porcentaje']
				]);
			}

			$objRta->tipo = 'exito';
			$objRta->mensaje = 'El proceso se realizó con éxito';
			return $objRta;
		} 
		catch (Exception $e) 
		{
			throw new Exception('CtrlMovimientos.insertar: '.$e->getMessage());
		}
	}

	public function detalles($arrParametros)
	{
		try 
		{
			// Consultar los préstamos para obtener la información general
			$clsPrestamos = new ClsPrestamos();
			$arrPrestamos = $clsPrestamos->consultar([ 
				'pres.pres_codigo' => $arrParametros['pres_codigo'] 
			]);

			// Consultar cuotas
			$clsCoutas = new ClsCuotas();
			$arrCuotas = $clsCoutas->consultar([ 'fk_pre_prestamos' => $arrParametros['pres_codigo'] ]);

			// Consultar participación
			$clsParticipacion = new ClsParticipacion();
			$arrParticipacion = $clsParticipacion->consultar([ 'fk_pre_prestamos' => $arrParametros['pres_codigo'] ]);

			$arrDatos['prestamo'] = $arrPrestamos;
			$arrDatos['cuotas'] = $arrCuotas;
			$arrDatos['participacion'] = $arrParticipacion;

			$objRta->tipo = 'exito';
			$objRta->datos = $arrDatos;
			return $objRta;
		} 
		catch (Exception $e) 
		{
			throw new Exception('CtrlPrestamos.detalles: '.$e->getMessage());
		}
	}

	public function pago($arrParametros = [])
	{
		try 
		{
			// Obtener el código del préstamo----------------------------------
			$arrCuota = ClsCuotas::consultar([
				'prcu_codigo' => $arrParametros['cuota'],
			]);
			// ----------------------------------------------------------------


			// Consultar el préstamo ------------------------------------------
			$arrPrestamo = ClsPrestamos::consultar([
				'pres_codigo' => $arrCuota[0]['fk_pre_prestamos'],
			]);
			// ----------------------------------------------------------------


			// Obtener la participación de los inversionisas ------------------
			$arrParticipacion = ClsParticipacion::consultar([
				'fk_pre_prestamos' => $arrCuota[0]['fk_pre_prestamos'],
			]);
			// ----------------------------------------------------------------

			// Si está pagando el valor exacto de la cuota
			if ($arrParametros['tipo'] == 'C')
			{
				// Registrar el pago de la cuota ----------------------------------
				ClsCuotas::editar([
					'prcu_codigo' => $arrParametros['cuota'],
					'prcu_fecha_pago' => $arrParametros['fecha'],
					'prcu_valor_pago' => $arrParametros['vlr_cuota'],
					'fk_par_estados' => 4, 
				]);
				// ----------------------------------------------------------------

				// Distribuir ingreso entre los inversionistas --------------------
				// Recorrer los inversionistas que participaron en el préstamo
				foreach ($arrParticipacion as $arrPrestamista)
				{
					// Calcular el valor ganado en la cuota por el inversionista de acuerdo al 
					// porcentaje de participación
					// Valor Ganado = ( Valor Pagado / % Participacion ) * 100
					$flValorGanado = ($arrParametros['vlr_cuota'] / $arrPrestamista['prpa_porcentaje']) * 100;

					// Consultar la información del inversionista
					$arrInversionista = ClsInversionistas::consultar([
						'inve_codigo' => $arrPrestamista['fk_par_inversionistas']
					]);

					// Sumar el valor ganado por el inversionista a su saldo
					ClsInversionistas::editar([
						'inve_codigo' => $arrPrestamista['fk_par_inversionistas'],
						'inve_saldo' => $arrInversionista[0]['inve_saldo'] + $flValorGanado,
					]);
					
					// Insertar movimiento de caja ------------------------------------
					ClsMovimientos::insertar([
						'fk_par_inversionistas' => $arrPrestamista['fk_par_inversionistas'],
						'movi_tipo' => 'E',
						'movi_descripcion' => 'Pago de la cuota # '.$arrCuota[0]['prcu_numero'].' del prestamo # '.$arrCuota[0]['fk_pre_prestamos'],
						'movi_fecha' => $arrParametros['fecha'],
						'movi_monto' => $flValorGanado,
						'fc' => date('Y-m-d H:m:s'),
						'uc' => $_SESSION['usuario_sesion'][0]['usua_codigo']
					]);
				}
				// ----------------------------------------------------------------
				
				
				// Recalcular el saldo del préstamo -------------------------------
				ClsPrestamos::editar([
					'pres_codigo' => $arrPrestamo[0]['pres_codigo'],
					'pres_vlr_saldo' => ( $arrPrestamo[0]['pres_vlr_saldo'] - $arrParametros['vlr_cuota'] ),
				]);
				// ----------------------------------------------------------------

			}

			// Si paga un valor diferente
			else if ($arrParametros['tipo'] == 'D')
			{
				// Si paga menos del valor de la cuota
				if ($arrParametros['valor'] < $arrParametros['vlr_cuota'])
				{
					// Consultar la cuota siguiente
					$arrCuotaSiguiente = ClsCuotas::consultar([
						'fk_pre_prestamos' => $arrCuota[0]['fk_pre_prestamos'],
						'prcu_numero' => ($arrCuota[0]['prcu_numero'] + 1),
					]);

					// Validar si es la cuota final
					if (count($arrCuotaSiguiente) == 0)
						throw new Exception('No se puede recibir un pago inferior al valor de la cuota ya que ésta es la última cuota');


					// Registrar el pago de la cuota ----------------------------------
					ClsCuotas::editar([
						'prcu_codigo' => $arrParametros['cuota'],
						'prcu_fecha_pago' => $arrParametros['fecha'],
						'prcu_valor_pago' => $arrParametros['valor'],
						'fk_par_estados' => 4, 
						]);
					// ----------------------------------------------------------------


					// Recalcular el valor a pagar de la próxima cuota ----------------
					// El valor a pagarde la próxima cuota es: 
					// el valor de la cuota siguiente + el saldo de la cuota actual - el valor que pagó
					ClsCuotas::editar([
						'prcu_codigo' => $arrCuotaSiguiente[0]['prcu_codigo'],
						'prcu_vlr_saldo' => ( $arrCuotaSiguiente[0]['prcu_vlr_saldo'] + $arrParametros['vlr_cuota'] - $arrParametros['valor'] ), 
						]);
					// ----------------------------------------------------------------


					// Distribuir ingreso entre los inversionistas --------------------
					// Recorrer los inversionistas que participaron en el préstamo
					foreach ($arrParticipacion as $arrPrestamista)
					{
						// Calcular el valor ganado en la cuota por el inversionista de acuerdo al 
						// porcentaje de participación
						// Valor Ganado = ( Valor Pagado / % Participacion ) * 100
						$flValorGanado = ($arrParametros['valor'] / $arrPrestamista['prpa_porcentaje']) * 100;

						// Consultar la información del inversionista
						$arrInversionista = ClsInversionistas::consultar([
							'inve_codigo' => $arrPrestamista['fk_par_inversionistas']
						]);

						// Sumar el valor ganado por el inversionista a su saldo
						ClsInversionistas::editar([
							'inve_codigo' => $arrPrestamista['fk_par_inversionistas'],
							'inve_saldo' => $arrInversionista[0]['inve_saldo'] + $flValorGanado,
						]);
						
						// Insertar movimiento de caja ------------------------------------
						ClsMovimientos::insertar([
							'fk_par_inversionistas' => $arrPrestamista['fk_par_inversionistas'],
							'movi_tipo' => 'E',
							'movi_descripcion' => 'Pago de la cuota # '.$arrCuota[0]['prcu_numero'].' del prestamo # '.$arrCuota[0]['fk_pre_prestamos'],
							'movi_fecha' => $arrParametros['fecha'],
							'movi_monto' => $flValorGanado,
							'fc' => date('Y-m-d H:m:s'),
							'uc' => $_SESSION['usuario_sesion'][0]['usua_codigo']
						]);
					}
					// ----------------------------------------------------------------
				}

				// Si paga más del valor de la cuota
				else if ($arrParametros['valor'] > $arrParametros['vlr_cuota'])
				{
					// El valor disponible es lo que el cliente está pagando
					$dbDisponible = $arrParametros['valor'];

					// Saber qué cuota se va a pagar
					$intNroCuota = $arrCuota[0]['prcu_numero'];

					while ($dbDisponible > 0)
					{
						$arrCuotaSiguiente = ClsCuotas::consultar([
							'fk_pre_prestamos' => $arrCuota[0]['fk_pre_prestamos'],
							'prcu_numero' => ($intNroCuota),
						]);

						// Si el valor disponible es superior al valor de la cuota
						if ($dbDisponible >= $arrParametros['vlr_cuota'])
						{
							// Registrar el pago de la cuota ----------------------------------
							ClsCuotas::editar([
								'prcu_codigo' => $arrCuotaSiguiente[0]['prcu_codigo'],
								'prcu_fecha_pago' => $arrParametros['fecha'],
								'prcu_valor_pago' => $arrParametros['vlr_cuota'],
								'fk_par_estados' => 4, 
								]);
							// ----------------------------------------------------------------


							// Distribuir ingreso entre los inversionistas --------------------
							// Recorrer los inversionistas que participaron en el préstamo
							foreach ($arrParticipacion as $arrPrestamista)
							{
								// Calcular el valor ganado en la cuota por el inversionista de acuerdo al 
								// porcentaje de participación
								// Valor Ganado = ( Valor Pagado / % Participacion ) * 100
								$flValorGanado = ($arrParametros['vlr_cuota'] / $arrPrestamista['prpa_porcentaje']) * 100;

								// Consultar la información del inversionista
								$arrInversionista = ClsInversionistas::consultar([
									'inve_codigo' => $arrPrestamista['fk_par_inversionistas']
								]);

								// Sumar el valor ganado por el inversionista a su saldo
								ClsInversionistas::editar([
									'inve_codigo' => $arrPrestamista['fk_par_inversionistas'],
									'inve_saldo' => $arrInversionista[0]['inve_saldo'] + $flValorGanado,
								]);
								
								// Insertar movimiento de caja ------------------------------------
								ClsMovimientos::insertar([
									'fk_par_inversionistas' => $arrPrestamista['fk_par_inversionistas'],
									'movi_tipo' => 'E',
									'movi_descripcion' => 'Pago de la cuota # '.$arrCuotaSiguiente[0]['prcu_numero'].' del prestamo # '.$arrCuotaSiguiente[0]['fk_pre_prestamos'],
									'movi_fecha' => $arrParametros['fecha'],
									'movi_monto' => $flValorGanado,
									'fc' => date('Y-m-d H:m:s'),
									'uc' => $_SESSION['usuario_sesion'][0]['usua_codigo']
								]);
							}
							// ----------------------------------------------------------------
							
							$dbDisponible -= $arrParametros['vlr_cuota'];
						}

						// Si el valor disponible es inferior al valor de la cuota
						else if ($dbDisponible < $arrParametros['vlr_cuota'])
						{
							// Recalcular el valor a pagar de la próxima cuota ----------------
							// El valor a pagarde la próxima cuota es: 
							// el valor de la cuota siguiente + el saldo de la cuota actual - el valor que pagó
							ClsCuotas::editar([
								'prcu_codigo' => $arrCuotaSiguiente[0]['prcu_codigo'],
								'prcu_vlr_saldo' => ( $arrCuotaSiguiente[0]['prcu_vlr_saldo'] - $dbDisponible ), 
							]);
							// ----------------------------------------------------------------


							// Distribuir ingreso entre los inversionistas --------------------
							// Recorrer los inversionistas que participaron en el préstamo
							foreach ($arrParticipacion as $arrPrestamista)
							{
								// Calcular el valor ganado en la cuota por el inversionista de acuerdo al 
								// porcentaje de participación
								// Valor Ganado = ( Valor Pagado / % Participacion ) * 100
								$flValorGanado = ($dbDisponible / $arrPrestamista['prpa_porcentaje']) * 100;

								// Consultar la información del inversionista
								$arrInversionista = ClsInversionistas::consultar([
									'inve_codigo' => $arrPrestamista['fk_par_inversionistas']
								]);

								// Sumar el valor ganado por el inversionista a su saldo
								ClsInversionistas::editar([
									'inve_codigo' => $arrPrestamista['fk_par_inversionistas'],
									'inve_saldo' => $arrInversionista[0]['inve_saldo'] + $flValorGanado,
								]);
								
								// Insertar movimiento de caja ------------------------------------
								ClsMovimientos::insertar([
									'fk_par_inversionistas' => $arrPrestamista['fk_par_inversionistas'],
									'movi_tipo' => 'E',
									'movi_descripcion' => 'Pago de la cuota # '.$arrCuota[0]['prcu_numero'].' del prestamo # '.$arrCuota[0]['fk_pre_prestamos'],
									'movi_fecha' => $arrParametros['fecha'],
									'movi_monto' => $flValorGanado,
									'fc' => date('Y-m-d H:m:s'),
									'uc' => $_SESSION['usuario_sesion'][0]['usua_codigo']
								]);
							}
							// ----------------------------------------------------------------
							
							$dbDisponible -= $dbDisponible;
						}

						$intNroCuota++;
					}
				}
				
				// Recalcular el saldo del préstamo -------------------------------
				ClsPrestamos::editar([
					'pres_codigo' => $arrPrestamo[0]['pres_codigo'],
					'pres_vlr_saldo' => ( $arrPrestamo[0]['pres_vlr_saldo'] - $arrParametros['valor'] ),
				]);
				// ----------------------------------------------------------------
			}

			// Si paga todo
			else if ($arrParametros['tipo'] == 'T')
			{
				// Consultar las cuotas pendientes del préstamo
				$arrCuotasPendientes = ClsCuotas::consultar([
					'fk_pre_prestamos' => $arrPrestamo[0]['pres_codigo'],
					'fk_par_estados' => '3, 5',
				]);

				// Recorrer las cuotas pendientes
				foreach ($arrCuotasPendientes as $arrCuotaPagar) 
				{
					// Registrar el pago de la cuota ----------------------------------
					ClsCuotas::editar([
						'prcu_codigo' => $arrCuotaPagar['prcu_codigo'],
						'prcu_fecha_pago' => $arrParametros['fecha'],
						'prcu_valor_pago' => $arrCuotaPagar['prcu_vlr_saldo'],
						'fk_par_estados' => 4, 
						]);
					// ----------------------------------------------------------------	


					// Distribuir ingreso entre los inversionistas --------------------
					// Recorrer los inversionistas que participaron en el préstamo
					foreach ($arrParticipacion as $arrPrestamista)
					{
						// Calcular el valor ganado en la cuota por el inversionista de acuerdo al 
						// porcentaje de participación
						// Valor Ganado = ( Valor Pagado / % Participacion ) * 100
						$flValorGanado = ($arrCuotaPagar['prcu_vlr_saldo'] / $arrPrestamista['prpa_porcentaje']) * 100;

						// Consultar la información del inversionista
						$arrInversionista = ClsInversionistas::consultar([
							'inve_codigo' => $arrPrestamista['fk_par_inversionistas']
						]);

						// Sumar el valor ganado por el inversionista a su saldo
						ClsInversionistas::editar([
							'inve_codigo' => $arrPrestamista['fk_par_inversionistas'],
							'inve_saldo' => $arrInversionista[0]['inve_saldo'] + $flValorGanado,
						]);
						
						// Insertar movimiento de caja ------------------------------------
						ClsMovimientos::insertar([
							'fk_par_inversionistas' => $arrPrestamista['fk_par_inversionistas'],
							'movi_tipo' => 'E',
							'movi_descripcion' => 'Pago de la cuota # '.$arrCuotaPagar['prcu_numero'].' del prestamo # '.$arrCuota[0]['fk_pre_prestamos'],
							'movi_fecha' => $arrParametros['fecha'],
							'movi_monto' => $flValorGanado,
							'fc' => date('Y-m-d H:m:s'),
							'uc' => $_SESSION['usuario_sesion'][0]['usua_codigo']
						]);
					}
					// ----------------------------------------------------------------
				
					
					// Recalcular el saldo del préstamo -------------------------------
					ClsPrestamos::editar([
						'pres_codigo' => $arrPrestamo[0]['pres_codigo'],
						'pres_vlr_saldo' => ( $arrPrestamo[0]['pres_vlr_saldo'] - $arrParametros['saldo'] ),
					]);
					// ----------------------------------------------------------------
				}
			}

			// Si no paga nada
			else if ($arrParametros['tipo'] == 'N')
			{
				// Consultar la cuota siguiente
				$arrCuotaSiguiente = ClsCuotas::consultar([
					'fk_pre_prestamos' => $arrCuota[0]['fk_pre_prestamos'],
					'prcu_numero' => ($arrCuota[0]['prcu_numero'] + 1),
				]);

				// Validar si es la cuota final
				if (count($arrCuotaSiguiente) == 0)
					throw new Exception('No se puede recibir un pago inferior al valor de la cuota ya que ésta es la última cuota');

				// Registrar el pago de la cuota ----------------------------------
				ClsCuotas::editar([
					'prcu_codigo' => $arrCuota[0]['prcu_codigo'],
					'prcu_fecha_pago' => $arrParametros['fecha'],
					'prcu_valor_pago' => 0,
					'fk_par_estados' => 4, 
					]);
				// ----------------------------------------------------------------	
				
				// Recalcular el valor a pagar de la próxima cuota ----------------
				// El valor a pagarde la próxima cuota es: 
				// el valor de la cuota siguiente + el saldo de la cuota actual
				ClsCuotas::editar([
					'prcu_codigo' => $arrCuotaSiguiente[0]['prcu_codigo'],
					'prcu_vlr_saldo' => ( $arrCuotaSiguiente[0]['prcu_vlr_saldo'] + $arrCuota[0]['prcu_vlr_saldo'] ), 
				]);
				// ----------------------------------------------------------------
			}

			// Consultar las cuotas no pagadas del préstamo
			$arrCuotasNoPagadas = ClsCuotas::consultar([
				'fk_pre_prestamos' => $arrCuota[0]['fk_pre_prestamos'],
				'fk_par_estados' => '1,3,5', 
			]);

			// Validar si no hay más cuotas por pagar
			if (count($arrCuotasNoPagadas) == 0)
			{
				// Si no hay más cuotas por pagar, se marca el préstamo como terminado
				ClsPrestamos::editar([
					'pres_codigo' => $arrPrestamo[0]['pres_codigo'],
					'fk_par_estados' => 7,
				]);
			}
			
			$objRta->tipo = 'exito';
			$objRta->mensaje = 'El proceso se realizó con éxito';
			return $objRta;
		}
		catch (Exception $e) 
		{
			throw new Exception('CtrlPrestamos.pago: '.$e->getMessage());
		}
	}

	public function anular($arrParametros = [])
	{
		try 
		{
			// Consultar las cuotas pagadas del préstamo
			$arrCuotasPagadas = ClsCuotas::consultar([
				'fk_pre_prestamos' => $arrParametros['pres_codigo'],
				'fk_par_estados' => 4, // Cuotas pagadas
			]);
			
			// Validar si hay cuotas pagadas, no se puede anular el préstamo
			if (count($arrCuotasPagadas) > 0)
				throw new Exception('El préstamo no se puede anular porque ya hay cuotas pagadas');
				
			// Cambiar el estado del préstamos a anulado
			ClsPrestamos::editar([
				'pres_codigo' => $arrParametros['pres_codigo'],
				'fk_par_estados' => 6
			]);

			// Cambiar el estado de todas la cuptas a anuladas
			BaseDatos::ejecutarSentencia("update tb_pre_cuotas set fk_par_estados = 6 where fk_pre_prestamos = ".$arrParametros['pres_codigo'].";");

			// Consultar el préstamo
			$arrPrestamo = ClsPrestamos::consultar([ 'pres_codigo' => $arrParametros['pres_codigo'] ]);

			// Consultar la participación de los inversionistas
			$arrParticipaciones = ClsParticipacion::consultar([
				'fk_pre_prestamos' => $arrParametros['pres_codigo'],
			]);
			
			// Recorrer a los inversionistas
			foreach ($arrParticipaciones as $arrParticipacion)
			{
				// Calcular el valor invertido por el inversionista
				$flCapitalInvertido = $arrPrestamo[0]['pres_vlr_monto'] * $arrParticipacion['prpa_porcentaje'] / 100;

				// Consultar la información del inversionista
				$arrInversionista = ClsInversionistas::consultar([
					'inve_codigo' => $arrParticipacion['fk_par_inversionistas']
				]);

				// Sumar el valor invertido por el inversionista a su saldo
				ClsInversionistas::editar([
					'inve_codigo' => $arrParticipacion['fk_par_inversionistas'],
					'inve_saldo' => $arrInversionista[0]['inve_saldo'] + $flCapitalInvertido,
				]);
				
				// Insertar movimiento de caja ------------------------------------
				ClsMovimientos::insertar([
					'fk_par_inversionistas' => $arrParticipacion['fk_par_inversionistas'],
					'movi_tipo' => 'E',
					'movi_descripcion' => 'Devolución de la inversión por anulación del préstamos # '.$arrParametros['pres_codigo'],
					'movi_fecha' => date('Y-m-d H:i:s'),
					'movi_monto' => $flCapitalInvertido,
					'fc' => date('Y-m-d H:m:s'),
					'uc' => $_SESSION['usuario_sesion'][0]['usua_codigo']
				]);
			}
			
			$objRta->tipo = 'exito';
			$objRta->mensaje = 'El proceso se realizó con éxito';
			return $objRta;
		}
		catch (Exception $e)
		{
			throw new Exception('CtrlPrestamos->anular: ' . $e->getMessage());
		}
	}
}