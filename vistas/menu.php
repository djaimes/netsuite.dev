<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="utf-8">
<title>Netsuite</title>
<link rel="stylesheet" type="text/css" href="../public/css/menu.css" />
<script type="text/javascript" src="../public/js/menu.js"></script>
</head>
<body id="index" class="home" onload="init()">
<header id="banner" class="body">
	<nav>
		<ul>
			<li>
				<a href="index.php?venta">Vender</a>
			</li>
			<li>
				<a href="index.php?producto&metodo=captura">Producto</a>
			</li>
			<li>
				<a href="index.php?nota&metodo=corte">Nota</a>
			</li>
			<li>
				<a href="index.php?corte&metodo=captura">Corte</a>
			</li>
			<li>
				<a href="index.php?corte&metodo=cambio"> Modificar Corte</a>
			</li>
			<li>
				<a href="index.php?factura&metodo=importar">Importar Facturas</a>
			</li>
			<li>
				<a href="index.php?salir">Salir</a>
			</li>
		</ul>
	</nav>
    <p><?=$data['nombre'];?></p>
</header>
<section id="contenido" class="body">
</section>
</body>
</html>
