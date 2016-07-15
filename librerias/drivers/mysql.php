<?php

/**
*  Mi driver para MySQL
*/
class Mysql_Driver extends Database_Library {

	private $connection;
	private $connected;
	private $query;
	private $result;
	
	/**
	*	Realizar la conexión a la base de datos
	*/
    public function connect() 
    {

		// Parámetros de conexión
		$host = 'localhost';
		$user = 'netsuite';
		$password = 'colage';
		$database = 'netsuite';

		// Indicamos a Mysql que lance excepciones
		
		mysqli_report(MYSQLI_REPORT_STRICT);
		
		try {
			$this->connection = new mysqli($host, $user, $password, $database); 
	
		} catch (Exception $e){
			echo '<h1>La base de datos no est&aacute; accesible</h1>'; 
			exit;
		}
		return TRUE;
	}

	/**
	* 	Debido a que derivamos de clase abstracta, 
	*	hay que implementar estos métodos 
	*/
    public function disconnect() 
    {
		$this->connection->close();
		return TRUE;
	}

	/**
	*	Recibir el query
	*/
    public function prepare($query)
    {
		$this->query = $query;	
		return TRUE;
	}
	
	/**
	*	Enviar a ejecución el query
	*/
    public function query() 
    {
		if (isset($this->query)) {
			$this->result = $this->connection->query($this->query);
		} else {
			return FALSE;
		}
		return $this->result;
	}
	
	/**
	*	Sanitizar la entrada para evitar inyección sql
	*/
    public function escape($data)
    {
		return $this->connection->real_escape_string($data);
	}
	
	/**
	*	Obtener una fila del resultado del recordset
	*/
    public function fetch() 
    {
		if (isset($this->result)){
			$arreglo = array();
			while ($row = $this->result->fetch_array(MYSQL_ASSOC)) {
				$arreglo[]=$row;
			}
			return $arreglo;
		} else {
			return FALSE;
		}
	}
}
?>
