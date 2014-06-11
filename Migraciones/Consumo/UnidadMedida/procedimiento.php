	<html>	
		<head>
			<title> Migracion Consumo.UnidaMedida </title>
			
			<link rel="stylesheet" type="text/css" href="../General/estilos/estilos.css">
		</head>
	
	
	<?php
		session_start();
		
		
		
		
		/* Inicia definicion de funciones */ 
		
		function eliminarUnidadMedida() {
			$cnx = conectar_postgres();
			$cons= "DELETE FROM Consumo.Unidadmedida";
			$res = pg_query($cnx, $cons);
							
				if (!$res) {
					echo "<p class='error1'> Error de ejecucion </p>".pg_last_error()."<br>";
					echo "<p class= 'subtitulo1'>Comando SQL </p> <br>".$cons."<br>"; 	
				}
		
		}
		
		

		function cargarUnidadMedida($paso)
		
		{
			$cnx = conectar_postgres();
			$ruta = $_SERVER['DOCUMENT_ROOT'];
			$cons= "COPY Consumo.UnidadMedida FROM '$ruta/Migraciones/Consumo/Unidadmedida/unidadmedida.csv' WITH DELIMITER ';' CSV HEADER;";
			$res = pg_query($cnx, $cons);
				if ($res) {
					echo "<p class='mensajeEjecucion'> <span class = 'subtitulo1'>Paso $paso: </span> Se han actualizado los registros de la tabla Consumo.Unidadmedida </p> ";
				}
				
				if (!$res) {
					echo "<p class='error1'> Error de ejecucion </p>".pg_last_error()."<br>";
					echo "<p class= 'subtitulo1'>Comando SQL </p> <br>".$cons."<br>"; 	
				}
		
		}
		
		function normalizarCodUnidadMedida($cadenaBusqueda, $cadenaReemplazo)
		
		{
			$cnx = conectar_postgres();
			$cons= "UPDATE Consumo.UnidadMedida SET unidad = replace( unidad, '$cadenaBusqueda' , '$cadenaReemplazo') ,almacenppal = replace( almacenppal, '$cadenaBusqueda' ,  '$cadenaReemplazo'),compania = replace( compania, '$cadenaBusqueda' , '$cadenaReemplazo')";
			$res = pg_query($cnx, $cons);
							
				if (!$res) {
					echo "<p class='error1'> Error de ejecucion </p>".pg_last_error()."<br>";
					echo "<p class= 'subtitulo1'>Comando SQL </p> <br>".$cons."<br>"; 	
				}
		
		}
		
		
		
		function migrarUnidadMedida($paso){
			eliminarUnidadMedida();
			cargarUnidadMedida($paso);
			normalizarCodUnidadMedida('&Aacute&', utf8_encode("Á"));			
			normalizarCodUnidadMedida('&Eacute&', utf8_encode("É"));
			normalizarCodUnidadMedida('&Iacute&', utf8_encode("Í"));
			normalizarCodUnidadMedida('&Oacute&', utf8_encode("Ó"));
			normalizarCodUnidadMedida('&Uacute&',utf8_encode("Ú"));
			normalizarCodUnidadMedida('&Ntilde&',utf8_encode("Ñ"));
		
		
		}
		
		
		
		
		
		
		
		/* Inicia defincion de funciones */
		
		
		