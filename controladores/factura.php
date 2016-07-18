<?php

/**
*	Facturas de compra
*/

class Factura_Controlador
{
	public $template = array(
							'importar' => 'facturaimportar',
							'error' => 'facturaerror'
						);
    
	public function main(array $param)
	{
		if (!isset($param['metodo'])) {
			$vista = new facturaVista_Modelo($this->template['error']);
			$vista->render();
			return FALSE;
		} 

		$modelo = new Factura_Modelo;

		switch ($param['metodo']) {
			case 'importar':
                $facturas = $modelo->importarFacturas();
				$vista = new facturaVista_Modelo($this->template['importar']);
				$vista->asignar('facturas', $facturas);
				$vista->render();
				break;
			default:
				break;
		}
	}
} 
?>
