<?php

/**
* Esta parte del modelo traerá la vista correspondiente
*/

class facturaVista_Modelo {

    private $data = array();
    private $render = FALSE;
    
    public function __construct($template) {

        // Componer el nombre del archivo
        // Los nombres de los archivos van en minúscula
        $file = SERVER_ROOT . '/vistas/' . strtolower($template) . '.php';
        if (file_exists($file)) {
			$this->render = $file;
        }
    }

    // Recibe valores del controlador y los almacena localmente
    public function asignar($variable , $value){
		$this->data[$variable] = $value;
	}
    
    public function render($direct_output = TRUE){

		// Activa la captura de la salida para pasarla al buffer
		if ($direct_output !== TRUE) {
			ob_start();
		}
		
        // Parsea las variables de data en variables locales
		$data = $this->data;
		
        // Obtener el template
		include($this->render);
		
		// Recibir el contenido del buffer y retornarlo
		if ($direct_output !== TRUE){
			return ob_get_clean();
		}
		
    }
	
	public function __destruct(){
		
	}
}
