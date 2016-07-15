<?php

/**
*	Corte de caja diario
*/

class Corte_Controlador
{
	public $template = array(
							'captura' => 'cortecaptura',
							'jsonres' => 'jsonresultado',
							'alta' => 'cortealta'
						);
    
	public function main(array $param)
	{
		if (!isset($param['metodo'])) {
			$vista = new corteVista_Modelo($this->template['error']);
			$vista->render();
			return FALSE;
		} 

		$modelo = new Corte_Modelo;

		switch ($param['metodo']) {
			case 'alta':
				if (isset($param['fecha']) &&
					isset($param['turno']) &&
					isset($param['efectivo']) &&
					isset($param['vales']) &&
					isset($param['compras'])
					) {
					$resultado = $modelo->altaCorte($param);	
					$vista = new corteVista_Modelo($this->template['jsonres']);
					$vista->asignar('resultado', $resultado);
				} else {
					$vista = new corteVista_Modelo($this->template['error']);
				}
				$vista->render();
				break;
			case 'baja':
				if (isset($param['codigobarra'])) {
					$resultado = $modelo->bajaCorte($param['codigobarra']);	
					$vista = new corteVista_Modelo($this->template['jsonres']);
					$vista->asignar('resultado', $resultado);
				} else {
					$vista = new corteVista_Modelo($this->template['error']);
				}
				$vista->render();
				break;
			case 'buscar':
                if (isset($param['fecha']) && isset($param['turno'])) {
					$corte = $modelo->getCorteByFechaTurno(
                        $param['fecha'],
                        $param['turno']
					);
				} else {
					$vista = new corteVista_Modelo($this->template['error']);
					$vista->render();
					return FALSE;
				}
				$vista = new corteVista_Modelo($this->template['json']);
				$vista->asignar('corte', $corte);
				$vista->render();
				break;
			case 'captura':
				// Enviar vista para captura de corte
				$vista = new corteVista_Modelo($this->template['captura']);
				$vista->render();
				break;
			default:
				// to do
		}
	}
} 
?>
