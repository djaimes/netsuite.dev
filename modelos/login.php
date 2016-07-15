<?php

/**
*	MODELO Login 
*/
class Login_Modelo {
    
    private $db;
    
    public function __construct() 
    {
        $this->db = new Mysql_Driver();
    }
    
    public function getUsuario($usuario, $contrasena) 
    {
		$this->db->connect();
		$usuario = $this->db->escape($usuario);
		$contrasena = $this->db->escape($contrasena);
        $sql = "
            SELECT login, nombre, paterno, materno, idperfil
            FROM usuario 
            WHERE login='$usuario' and contrasena='$contrasena';
            ";
        $this->db->prepare($sql);

        $this->db->query();
        $usuario = $this->db->fetch();
        
        return $usuario;
    }
}
?>
