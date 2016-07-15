<!DOCTYPE HTML>
<html lang="es">
<head>
	<link rel="stylesheet" type="text/css" href="../public/css/login.css">
	<meta charset="UTF-8">
	<title>Netsuite</title>
</head>
<body>
    <header class="login">
	    <img src="../public/images/logo_ciberium.jpg">
    </header>
    <section class="login">
  	<p id = "nombreSistema" >Punto de Venta</p>
	<p id = "error" class="<?=$data['clase'];?>"><?=$data['error'];?></p>
    </section>
    <section class="login">
	    <form id="firmarse" method="get" action="index.php">
			<input type="hidden" name='login'>
            <label>Usuario</label>
            <input name="login" placeholder="usuario" autofocus>
            <label>Contraseña</label>
            <input name="contrasena" type="password" placeholder="contraseña">
            <input id="submit" name="submit" type="submit" value="Entrar">
        </form>
    </section>
    <footer class="login">
    </footer>
</body>
</html>
