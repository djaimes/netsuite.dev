<?php

/**
*	CONTROLADOR para el objeto Salir
*/

class Salir_Controlador {

    public $htmlTemplate = 'htmlsalir';
    
    public function main(array $parametros) {
		session_destroy();
		$vista = new salirVista_Modelo($this->htmlTemplate);
		$vista->render();
    }
}
?>
