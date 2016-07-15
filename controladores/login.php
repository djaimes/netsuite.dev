<?php
/**
 *	CONTROLADOR login
 *	Valida usuario y contraseña
*/

class Login_Controlador
{
    // template es el nombre del archivo html que usaremos como vista
    public $template = 'login';
    
    public function main(array $parametros)
    {
		if (empty($parametros)) {
			$vista =  new LoginVista_Modelo($this->template);
			$vista->asignar('clase', 'errorNoVisible');
			$vista->asignar('error', '');
			$vista->render();
    	} else {
			if (isset($parametros['error'])) {
				$vista =  new LoginVista_Modelo($this->template);
				$vista->asignar('clase', 'errorVisible');
				$vista->asignar('error', 'Acceso denegado, intente de nuevo.');
				$vista->render();
			} else {
				$usuario = $parametros['login'];
				$contrasena = md5($parametros['contrasena']);
				$loginModelo = new Login_Modelo;
				$login = $loginModelo->getUsuario($usuario, $contrasena);
								
				if (count($login)) {
					$_SESSION['login'] = $login['login'];
					$_SESSION['nombre'] = $login['nombre'] . ' ' . $login['paterno'];

					// Llamar al controlador menú
					header('Location:' . SITE_ROOT . '/index.php?menu');
				} else {
					header('Location:' . SITE_ROOT . '/index.php?login&error=1');
				}
			}
		}
	}
}
?>
