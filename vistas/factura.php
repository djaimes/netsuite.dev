<!DOCTYPE html>
<html lang="es">
<head>
	<meta charset="UTF-8">
	<title>Factura</title>
</head>
<body>
    <form id="importarfactura" method="get" action="index.php">
        <input type="hidden" name='corte'>
	    <input type="hidden" name='metodo' value='alta'>
		<h1>Corte de caja</h1>
		<hr>
        <label>Fecha</label>
        <input name="fecha" placeholder="fecha" type="date" <?php echo date('Y-m-d'); ?> autofocus />
        <label>Turno</label>
        <input name="turno" placeholder="turno" autofocus />
        <label>Efectivo</label>
        <input name="efectivo" placeholder="efectivo" autofocus />
        <label>Vales</label>
        <input name="vales" placeholder="vales" autofocus />
        <label>Compras</label>
        <input name="compras" placeholder="compras" autofocus />
        <label>Total</label>
        <input name="total" placeholder="total" autofocus />
        <input id="grabar" name="submit" type="submit" value="Guardar corte" />
    </form>
</body>
</html>
