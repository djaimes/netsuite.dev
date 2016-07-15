<?php

/**
*	Menu_Controlador
*/

class Menu_Controlador {

    public $template = 'menu';
    
    public function main(array $parametros) 
    {
		if (empty($parametros)) {
			$vista =  new menuVista_Modelo($this->template);
			$vista->asignar('nombre',$_SESSION['nombre']);
			$vista->render();
    	}
	}	
}
?>
