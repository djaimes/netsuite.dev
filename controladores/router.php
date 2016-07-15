<?php

session_start();

/***
 * Una URL típica es: 
 * http://ciberium.com/index.php?producto=&metodo=alta&desc=galleta&precio=7
 * QUERY_STRING trae toda la cadena que se encuentra después del signo de
 * pregunta (?)
 * controlador = producto
 * metodo =  alta
 * parámetros = ('desc' => 'galleta', 'precio' = 7);
 */

$request = $_SERVER['QUERY_STRING'];
$parametros = array();

if (empty($request)) {
    // Si no se indica el controlador, llamamos al controlador login
    $nombreControlador = 'login';
} else {
	$url = explode('&', $request);
	$nombreControlador = str_replace('=','',array_shift($url));

	// Obtener todos los parámetros de la url
	foreach ($url as $dupla) {
		$dupla = explode('=', $dupla);
		$variable = $dupla[0];
		$valor = $dupla[1];

		// El urldecode quita caracteres como galleta%20cracket%20salada
		$parametros[$variable] = urldecode($valor);
	}
}

// Construir la ruta del controlador solicitado
$rutaControlador = SERVER_ROOT . '/controladores/' . $nombreControlador . '.php';

// El archivo debe existir y la clase debe poder ser llamada
if (file_exists($rutaControlador)) {
	include_once($rutaControlador);
	
    // Construimos el nombre de la clase
	// La primera letra en mayúscula para cumplir con las reglas de nombrado
    // Todas los controladores son del tipo: Nombre_Controlador
	$clase = ucfirst($nombreControlador) . '_Controlador';
	
	// Nos aseguramos que la clase exista 
	if (class_exists($clase)) {
		$objetoControlador = new $clase;
	} else {
		die('La clase no existe');
	}
} else {
	die('El controlador no existe');
}

// Llamar al método main del controlador solicitado
// y mandamos el arreglo de variables
$objetoControlador->main($parametros);

/***	
 *	La función __autoload realiza automáticamente la inclusión
 *	del archivo .php que contiene la clase solicitada
 *	sin necesidad de un include_once previo
 *	P.E
 *	Si la clase se llama LoginVista_Modelo
 *	el nombre del archivo que la contiene es loginvista.php
 *	en la carpeta /modelos/
*/
function __autoload($nombreClase)
{
	    $parseClase = explode('_' , $nombreClase);
	    $nombreArchivo = $parseClase[0];
	    $sufijo = strtolower($parseClase[1]);

	    // Definir en que carpeta está el archivo
	    switch ($sufijo) {
	    	case 'modelo':
	    		$folder = '/modelos/';
	    		break;
	    	case 'library':
	    		$folder = '/librerias/';
	    		break;
	    	case 'driver':
	    		$folder = '/librerias/drivers/';
	    }
		
        // Componer el nombre del archivo que debe contener el código
        // de la clase solicitada
	    $file = SERVER_ROOT . $folder . strtolower($nombreArchivo) . '.php';
	    
	    // Verificar que exista el archivo
	    if (file_exists($file)) {
	        include_once($file);        
	    } else {
	        die("El archivo '$nombreArchivo' no existe.'$file'");    
	    }
}
?>
