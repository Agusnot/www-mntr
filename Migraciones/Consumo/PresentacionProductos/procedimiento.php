	<html>	
		<head>
			<title> Migracion Consumo.PresentacionProductos </title>
			
			<link rel="stylesheet" type="text/css" href="../General/estilos/estilos.css">
		</head>
	
	
	<?php
		session_start();
		
		
		
		
		/* Inicia definicion de funciones */ 
		
		function eliminarPresentacionProductos() {
			$cnx = conectar_postgres();
			$cons= "DELETE FROM Consumo.PresentacionProductos";
			$res = pg_query($cnx, $cons);
							
				if (!$res) {
					echo "<p class='error1'> Error de ejecucion </p>".pg_last_error()."<br>";
					echo "<p class= 'subtitulo1'>Comando SQL </p> <br>".$cons."<br>"; 	
				}
		
		}
		
		

		function cargarPresentacionProductos($paso)
		
		{
			$cnx = conectar_postgres();
			$ruta = $_SERVER['DOCUMENT_ROOT'];
			$cons= "COPY Consumo.PresentacionProductos FROM '$ruta/Migraciones/Consumo/PresentacionProductos/presentacionProductos.csv' WITH DELIMITER ';' CSV HEADER;";
			$res = pg_query($cnx, $cons);
				if ($res) {
					echo "<p class='mensajeEjecucion'> <span class = 'subtitulo1'>Paso $paso: </span> Se han actualizado los registros de la tabla Consumo.PresentacionProductos </p> ";
				}
				
				if (!$res) {
					echo "<p class='error1'> Error de ejecucion </p>".pg_last_error()."<br>";
					echo "<p class= 'subtitulo1'>Comando SQL </p> <br>".$cons."<br>"; 	
				}
		
		}
		
		
		function normalizarCodificacionPresProd($cadenaBusqueda, $cadenaReemplazo)
		
		{
			$cnx = conectar_postgres();
			$cons= "UPDATE Consumo.PresentacionProductos SET presentacion = replace( presentacion, '$cadenaBusqueda' , '$cadenaReemplazo') ,almacenppal = replace( almacenppal, '$cadenaBusqueda' ,  '$cadenaReemplazo'),compania = replace( compania, '$cadenaBusqueda' , '$cadenaReemplazo')";
			$res = pg_query($cnx, $cons);
							
				if (!$res) {
					echo "<p class='error1'> Error de ejecucion </p>".pg_last_error()."<br>";
					echo "<p class= 'subtitulo1'>Comando SQL </p> <br>".$cons."<br>"; 	
				}
		
		}
		
		function migrarPresentacionProductos($paso){
			eliminarPresentacionProductos();
			cargarPresentacionProductos($paso);
			normalizarCodificacionPresProd('&Aacute&', utf8_encode("Á"));			
			normalizarCodificacionPresProd('&Eacute&', utf8_encode("É"));
			normalizarCodificacionPresProd('&Iacute&', utf8_encode("Í"));
			normalizarCodificacionPresProd('&Oacute&', utf8_encode("Ó"));
			normalizarCodificacionPresProd('&Uacute&',utf8_encode("Ú"));
			normalizarCodificacionPresProd('&Ntilde&',utf8_encode("Ñ"));
		
		}
		
		
		
		
		
		
		
		/* Inicia defincion de funciones */
		
		
		