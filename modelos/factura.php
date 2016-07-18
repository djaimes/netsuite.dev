<?php

class Factura_Modelo {
    
    private $db;
    
    public function __construct() 
    {
        $this->db = new Mysql_Driver();
        $this->db->connect();
    }

    /**
     * Importar las facturas del directorio ./data
     */
    
    public function importarFacturas() 
	{
        require_once '/var/www/html/netsuite.dev/librerias/Factura_Xml.class.php';
        $pathFacturas = '/var/www/html/netsuite.dev/datos/facturas/';
        $facturas = scandir($pathFacturas);

        foreach($facturas as $factura) {
            if (strpos($factura,'.xml')) {
                $xml = new Factura_Xml($pathFacturas . $factura);
                $nombreFactura = basename($factura);
                if ($result = $xml->grabarFactura()) {
                    $estatus = 'IMPORTADA';
                } else {
                    $estatus = 'DUPLICADA';
                }
                $facs[] = array('factura' => $nombreFactura, 'estado' => $estatus);
            }
        }
        return $facs;
    }
}
?>
