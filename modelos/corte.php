<?php

/**
*	 MODELO para el controlador: producto 
*/
class Corte_Modelo {
    
    private $db;
    
	/**
	*	Driver para MySQL
	*/
    public function __construct() {
        $this->db = new Mysql_Driver();
        $this->db->connect();
    }
    
    /**
	*	ALTA de un corte
	*/
    public function altaCorte($param) 
	{
		$fecha = $this->db->escape($param['fecha']);
		$turno = $this->db->escape($param['turno']);
		$efectivo = $this->db->escape($param['efectivo']);
		$vales = $this->db->escape($param['vales']);
		$compras = $this->db->escape($param['compras']);
		$total = $this->db->escape($param['total']);

        $sql = "INSERT INTO corte(fecha, turno, efectivo, vales, compras, total)
            VALUES('$fecha','$turno',$efectivo,$vales, $compras, $total)";
        $this->db->prepare($sql);
        $resultado = $this->db->query();
        return $resultado;
	}
		
    /**
	*	BAJA de un producto
	*/
    public function bajaCorte($codigoBarra) 
	{
		$codigoBarra = $this->db->escape($codigoBarra);

        $this->db->prepare(
			"
			DELETE FROM producto
			WHERE codigobarra =  $codigoBarra
			"
		);

        $resultado = $this->db->query();
        return $resultado;
	}
		
    /**
	*	Buscar producto por código de barra
	*/
    public function getCorteByCodigo($codigoBarra) {
		
		$codigoBarra = $this->db->escape($codigoBarra);
		
        $this->db->prepare(
            "
            select codigobarra, descripcion, precio, unidad
            from producto 
            where codigobarra ='$codigoBarra';
            "
        );
        
        $this->db->query();
        $producto = $this->db->fetch();
        
        return $producto;
    }

	/**
	*	Buscar productos por cadena de coincidencia
	*/
	public function getCorteByNombre($nombre) {
		$nombre = $this->db->escape($nombre);
		$this->db->prepare(
			"
			select codigobarra, descripcion, precio, unidad 
			from producto 
			where descripcion like '%$nombre%'
			"
		);
		$this->db->query();
        $producto = $this->db->fetch();
        return $producto;
	}
}
?>
