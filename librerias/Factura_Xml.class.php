<?php

class Factura_Xml 
{
    private $xmlFilePath = '';
    private $pdfFilePath = '';
    private $xml = '';
    private $db = '';
    private $factura = array();
    private $detalle = array();

    function __construct($filePath)
    {
        $this->xmlFilePath = $filePath; 
        $this->pdfFilePath = substr($filePath,0,-3) . 'pdf'; 
        if (!file_exists($this->pdfFilePath)) {
            $this->pdfFilePath = substr($filePath, 0, -3) . 'PDF';
        }
        $this->parserXmlFile();
    }
    
    function parserXmlFile()
    {
        $this->xml = simplexml_load_file($this->xmlFilePath);

        foreach($this->xml->xpath('//cfdi:Comprobante//cfdi:Emisor') as $Emisor) {
            $this->factura['rfc'] = $Emisor['rfc'];
		    $this->factura['nombre'] = $Emisor['nombre'];
		}

		foreach($this->xml->xpath('//cfdi:Comprobante') as $Comprobante) {
            $this->factura['serie'] = $Comprobante['serie'];
		    $this->factura['folio'] = $Comprobante['folio'];
		    $this->factura['fecha'] = $Comprobante['fecha'];
		    $this->factura['subTotal'] = $Comprobante['subTotal'];
		    $this->factura['descuento'] = $Comprobante['descuento'];
		    $this->factura['total'] = $Comprobante['total'];
		    $this->factura['metodoDePago'] = $Comprobante['metodoDePago'];
		}

		foreach($this->xml->xpath('//cfdi:Impuestos//cfdi:Traslados//cfdi:Traslado') as $Impuestos) {
		    $this->factura['impuesto'] = $Impuestos['impuesto'];
		    $this->factura['tasa'] = $Impuestos['tasa'];
		    $this->factura['importe'] = $Impuestos['importe'];
        }
            
        // Para obtener el timbre fiscal, ya que está en otro namespace
        $nameSpaces = $this->xml->getNamespaces(true);
        $this->xml->registerXPathNamespace('t',$nameSpaces['tfd']);
        
        foreach($this->xml->xpath('//t:TimbreFiscalDigital') as $TimbreFiscalDigital) {
            $this->factura['UUID'] = $TimbreFiscalDigital['UUID'];
		}

        foreach($this->xml->xpath('//cfdi:Conceptos//cfdi:Concepto') as $Concepto) {
            array_push($this->detalle, $Concepto);
		}

    }

    function getFactura()
    {
        return $this->factura;
    }

    function getDetalle()
    {
        return $this->detalle;
    }


    function grabarFactura()
    {

        require_once '/var/www/html/netsuite.dev/librerias/database.php';
        require_once '/var/www/html/netsuite.dev/librerias/drivers/mysql.php';

        $this->db = new Mysql_Driver();
		$this->db->connect();
        $xmlContenido = file_get_contents($this->xmlFilePath);
        $pdfContenido = file_get_contents($this->pdfFilePath);

        $xmlContenido = $this->db->escape($xmlContenido);
        $pdfContenido = $this->db->escape($pdfContenido);

        $xmlFileName = basename($this->xmlFilePath);
        $pdfFileName = basename($this->pdfFilePath);

        $sql = 'insert into factura(UUID,
                                    rfc, 
                                    nombre, 
                                    serie,
                                    folio,
                                    fecha, 
                                    subtotal, 
                                    descuento,
                                    total,
                                    metododepago,
                                    impuesto, 
                                    tasa, 
                                    importe,
                                    pdfcontenido, 
                                    xmlcontenido, 
                                    pdffilename, 
                                    xmlfilename) 
                                    values(
                                        "'.$this->factura['UUID'].'", 
                                        "'.$this->factura['rfc'].'", 
                                        "'.$this->factura['nombre'].'", 
                                        "'.$this->factura['serie'].'", 
                                        "'.$this->factura['folio'].'", 
                                        "'.$this->factura['fecha'].'", 
                                        '.$this->factura['subTotal'].', 
                                        '.$this->factura['descuento'].', 
                                        '.$this->factura['total'].', 
                                        "'.$this->factura['metodoDePago'].'", 
                                        "'.$this->factura['impuesto'].'", 
                                        '.$this->factura['tasa'].', 
                                        '.$this->factura['importe'].', 
                                        "'.$pdfContenido.'",
                                        "'.$xmlContenido.'",
                                        "'.$pdfFileName.'", 
                                        "'.$xmlFileName.'"
                                    )';

        $this->db->prepare($sql);
        
        if ($result = $this->db->query()) {
            //grabar los conceptos
            foreach($this->detalle as $Concepto) {
                $sql = 'INSERT INTO detalle(UUID, cantidad, unidad, noidentificacion, descripcion, valorunitario, importe) 
                        VALUES( "'.$this->factura['UUID'].'", 
                                '.$Concepto['cantidad'].',
                                "'.$Concepto['unidad'].'",
                                "'.$Concepto['noIdentificacion'].'",
                                "'.$Concepto['descripcion'].'",
                                '.$Concepto['valorUnitario'].',
                                '.$Concepto['importe'].'
                )';
                $this->db->prepare($sql);
                $result = $this->db->query();
           }
        }
        $this->db->disconnect();
        return $result;
    }
}
?>
