/**
*	ventas.js
*/

var folioNota = 0;
var subtotal = 0;
var iva = 0;
var total = 0;

function init(){
	var codigo = document.getElementById('codigo');
	var cerrarnota = document.getElementById('cerrarnota');
	var patronNumerico = new RegExp('[0-9]');

	codigo.onkeyup = function sugiereProducto() {
		if ( this.value.length >= 3 ) {
			if ( !patronNumerico.test(this.value) ) {	
				buscarProducto('cadena', this.value);
			} else if ( this.value.length == 13 && 
						patronNumerico.test(this.value)) {
				// Buscar por código de barra
				buscarProducto('codigobarra', this.value);
			  }
		} else {
			document.getElementById('divProductosSugeridos');
				divProductosSugeridos.innerHTML = '';
		}
	};

	cerrarnota.onclick = function cerrarNota(){

		// Subtotal
		var tr = document.createElement('tr');
		tr.id = 'subtotal';
	
		var tdTexto = document.createElement('td');
		tdTexto.innerHTML = 'Subtotal';
		tdTexto.setAttribute('class','alinearDerecha');
	
		var tdCantidad = document.createElement('td');
		tdCantidad.innerHTML = subtotal.toFixed(2);
		tdCantidad.setAttribute('class', 'alinearDerecha');
	
		tr.appendChild(tdTexto);
		tr.appendChild(tdCantidad);
	
		var tabla = document.getElementById('tablaProductos');
		tabla.appendChild(tr);

		// Iva
		var tr = document.createElement('tr');
		tr.id = 'iva';
	
		var tdTexto = document.createElement('td');
		tdTexto.innerHTML = 'Iva';
		tdTexto.setAttribute('class','alinearDerecha');
	
		var tdCantidad = document.createElement('td');
		tdCantidad.innerHTML = (subtotal * 0.16).toFixed(2);
		tdCantidad.setAttribute('class', 'alinearDerecha');
	
		tr.appendChild(tdTexto);
		tr.appendChild(tdCantidad);
	
		var tabla = document.getElementById('tablaProductos');
		tabla.appendChild(tr);

		// Total
		var tr = document.createElement('tr');
		tr.id = 'total';
	
		var tdTexto = document.createElement('td');
		tdTexto.innerHTML = 'Total';
		tdTexto.setAttribute('class','alinearDerecha');
	
		var tdCantidad = document.createElement('td');
		tdCantidad.innerHTML = (subtotal * 1.16).toFixed(2);
		tdCantidad.setAttribute('class', 'alinearDerecha');
	
		tr.appendChild(tdTexto);
		tr.appendChild(tdCantidad);
	
		var tabla = document.getElementById('tablaProductos');
		tabla.appendChild(tr);

		//backend
		actualizarNota();

		var cerrarNota = document.getElementById('cerrarnota');
		cerrarNota.setAttribute('disabled','disabled');
		
		// Habilitar la impresión
		var imprimirNota = document.getElementById('imprimirnota');
		imprimirNota.removeAttribute("disabled");
		imprimirNota.onclick = getPdfNota;
	};
	
	getFolio();
	agregarNota();
}

/**
*	Con 3 letras vamos a al AJAX
*/

function buscarProducto(tipoBusqueda, cadena) {

	// Crear objeto ajax 
	var ajax = new XMLHttpRequest();		
	
	// Cuando responda esta función recibe los resultados 
	ajax.onreadystatechange = function(){	
		if (ajax.readyState == 4 && ajax.status == 200) { /*4=terminó;200=OK;*/
			var txtProductos = ajax.responseText;
			if ( txtProductos ) {			// Si encontramos algo
				desplegarProductos(txtProductos);
			}
		}
	}
	
	// Creamos la consulta ajax
	var tipo = "GET";
	var url = "index.php?producto&metodo=buscar&" + tipoBusqueda + "=" + cadena;
	var asincrono = true;

	ajax.open(tipo, 		// GET o POST
			  url,			// URL
			  asincrono);	// true = asyn, false = sync
	ajax.send();			// Enviar la consulta
}

/**
*	Desplegar los productos sugeridos
*/
function desplegarProductos(txtProductos){
	// Sin el var para hacerla global
	jsonProductos = JSON.parse(txtProductos); 
	var i;
	var html = '';
	
	var divProductosSugeridos = document.getElementById('divProductosSugeridos');
	divProductosSugeridos.innerHTML = '';
	
	for ( i = 0; i < jsonProductos.length; ++i) {
		var parrafoProducto = document.createElement('p');
		var textoProducto = document.createTextNode(jsonProductos[i].descripcion);
		
		parrafoProducto.setAttribute('id',i);		
		parrafoProducto.appendChild(textoProducto);
		
		parrafoProducto.addEventListener('mouseover', productoSelected);
		parrafoProducto.addEventListener('mouseout', productoUnSelected);
		parrafoProducto.addEventListener('click', productoSeleccionado);
		
		divProductosSugeridos.appendChild(parrafoProducto);
	}
}

/**
* Iluminar en azul el producto sobre el cursor
*/
function productoSelected() {
	this.setAttribute('class','productoSelected');
}

/**
* Quitarle el azul
*/
function productoUnSelected() {
	this.setAttribute('class','productoUnSelected');
}

/**
* Pasar la selección a la caja de texto
*/
function productoSeleccionado() {
	// grabar en backend
	agregarDetalle(jsonProductos[this.id].codigobarra,
				   jsonProductos[this.id].precio);
	
	subtotal += parseFloat(jsonProductos[this.id].precio);

	var codigo = document.getElementById('codigo');
	var divProductosSugeridos = document.getElementById('divProductosSugeridos');
	divProductosSugeridos.innerHTML = '';
	
	// Crear el registro para el ticket con this.id como índice
	var tr = document.createElement('tr');
	tr.id = this.id;
	
	var tdDescripcion = document.createElement('td');
	tdDescripcion.innerHTML = jsonProductos[this.id].descripcion;
	tdDescripcion.setAttribute('class','alinearIzquierda');
	
	var tdPrecio = document.createElement('td');
	tdPrecio.innerHTML = jsonProductos[this.id].precio;
	tdPrecio.setAttribute('class', 'alinearDerecha');
	
	tr.appendChild(tdDescripcion);
	tr.appendChild(tdPrecio);
	
	var tabla = document.getElementById('tablaProductos');
	tabla.appendChild(tr);

	codigo.value = '';	// limpiamos búsqueda
	codigo.focus();		// otra búsqueda
	
}

/**
*	Grabar el producto en la nota
*/
function agregarDetalle(codigobarra, precio) {

	var ajax = new XMLHttpRequest();		

	ajax.onreadystatechange = function() {	
		if (ajax.readyState == 4 && ajax.status == 200) { /*4=terminó;200=OK;*/
			if ( ajax.responseText != 1 ) {			// Algún error?
				return 'error';
			}
		}
	}
	
	var tipo = "GET";
	var url = "index.php?detalle&metodo=agregardetalle&folio=" + folioNota + "&codigobarra=" + codigobarra +"&precio=" + precio;

	var asincrono = false;

	ajax.open(tipo, url, asincrono);
	ajax.send();
}

/**
*	Obtener el folio de la nota
*/
function getFolio() {

	var ajax = new XMLHttpRequest();		

	ajax.onreadystatechange = function() {	
		if (ajax.readyState == 4 && ajax.status == 200) { 
			// alert(ajax.responseText);
			var jsonFolio = JSON.parse(ajax.responseText); 
			var folio = jsonFolio[0].folio;
			var tagfolio = document.getElementById("folio");
			var txtFolio = document.createTextNode("Folio: " + folio);
			tagfolio.appendChild(txtFolio);
			folioNota = folio;
		}
	}
	
	var tipo = "GET";
	var url = "index.php?folios&metodo=getfolio&documento=1";
	var asincrono = false;

	ajax.open(tipo, url, asincrono);
	ajax.send();
}

/**
*	Crear la nota
*/
function agregarNota() {

	var ajax = new XMLHttpRequest();		

	ajax.onreadystatechange = function() {	
		if (ajax.readyState == 4 && ajax.status == 200) { 
			// alert(ajax.responseText);
		}
	}
	
	var tipo = "GET";
	var url = "index.php?nota&metodo=agregarnota&folio=" + folioNota;
	var asincrono = true; // hay que esperar la respuesta

	ajax.open(tipo, url, asincrono);
	ajax.send();
}

/**
*	Actualizar la nota
*/
function actualizarNota() {

	var ajax = new XMLHttpRequest();		

	ajax.onreadystatechange = function() {	
		if (ajax.readyState == 4 && ajax.status == 200) { 
			// alert(ajax.responseText);
			pdfNota();
		}
	}
	
	var tipo = "GET";
	var url = "index.php?nota&metodo=actualizarnota&folio=" + folioNota + "&subtotal=" + subtotal;
	var asincrono = true; // hay que esperar la respuesta
	ajax.open(tipo, url, asincrono);
	ajax.send();
}

/**
*	Generar el pdf de una nota
*/
function pdfNota() {

	var ajax = new XMLHttpRequest();		

	ajax.onreadystatechange = function() {	
		if (ajax.readyState == 4 && ajax.status == 200) { 
			// alert(ajax.responseText);
		}
	}
	
	var tipo = "GET";
	var url = "index.php?nota&metodo=pdfnota&folio=" + folioNota;
	var asincrono = true; // hay que esperar la respuesta
	ajax.open(tipo, url, asincrono);
	ajax.send();
}

/**
*	Recuperar el PDF de una nota
*/
function getPdfNota() {

	var ajax = new XMLHttpRequest();		

	ajax.onreadystatechange = function() {	
		if (ajax.readyState == 4 && ajax.status == 200) { 
			// alert(ajax.responseText);
		}
	}
	
	var tipo = "GET";
	var url = "index.php?nota&metodo=getpdfnota&folio=" + folioNota;
	var asincrono = true; // hay que esperar la respuesta
	ajax.open(tipo, url, asincrono);
	ajax.send();
}

