<?php
/**
* Esta clase es abstracta por que sirve de base para implementar
* drivers para distintos motores de base de datos.
* Se indica qué métodos deben implementar.
* Se pone abstracta para evitar que pueda instanciarse directamente.
* Esto permite tener drivers para mysql y postgresql
*/
abstract class Database_Library
{
	abstract protected function connect();
	abstract protected function disconnect();
	abstract protected function prepare($query);
	abstract protected function query();
	abstract protected function fetch();
}
?>
