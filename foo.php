<?php

require_once './librerias/database.php';
require_once './librerias/drivers/mysql.php';

$db = new Mysql_Driver();

if ($db->connect()){

    $sql = "SELECT * FROM foo WHERE bar = 't'";
    //$sql = "insert into foo(bar) values('x')";
    // $sql = 'create table foobar(foo varchar(1))';
    $db->prepare($sql);
    $result = $db->query();

    if (is_object($result)) {
        if (empty((array) $result)) {
            echo 'No hay registros';
        } else {
            print_r($result->num_rows);
        } 
    } elseif ($result) {
        echo 'Operación exitosa';
    } else {
        echo 'Operación fallida';
    }
}
?>

