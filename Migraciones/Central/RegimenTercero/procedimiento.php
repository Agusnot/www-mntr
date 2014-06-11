	<html>	
		<head>
			<title> Migracion Central.RegimenTercero </title>
			<link rel="stylesheet" type="text/css" href="../General/estilos/estilos.css">
		</head>
	
	
	<?php
		
		
		
		
		
		/* Inicia defincion de funciones */
		
		function eliminarRegimenTercero() {
			// Elimina la tabla de migracion
			$cnx= conectar_postgres();
			$cons = "DELETE FROM Central.RegimenTercero";
			$res = @pg_query($cnx, $cons);
				if (!$res) {
				
					echo "<p class='error1'> Error de ejecucion </p>".pg_last_error()."<br>";
					echo "<p class= 'subtitulo1'>Comando SQL </p> <br>".$cons."<br>"; 				
					echo "<br><br>";			
					//echo "<br><br>";
				}
			
		}
		
		
		function insertarRegimenTercero() {
			// Elimina la tabla de migracion
			$cnx= conectar_postgres();
			$cons = "INSERT INTO central.RegimenTercero (Regimen) VALUES ( 'Comun'), ( 'Empleado'), ( 'Gran Contribuyente'), ( 'No Contribuyente'), ( 'Persona Natural'), ( 'Simplificado')";
			$cons= str_replace("Ú","&Uacute;",$cons);
			$res = @pg_query($cnx, $cons);
				if (!$res) {
				
					echo "<p class='error1'> Error de ejecucion </p>".pg_last_error()."<br>";
					echo "<p class= 'subtitulo1'>Comando SQL </p> <br>".$cons."<br>"; 				
					echo "<br><br>";			
					//echo "<br><br>";
				}
				
				
				
		function  normalizarCodificacion($cadenaBusqueda,$cadenaReemplazo)  {
		// Busca y reemplaza ocurrencias en una tabla
			$cnx= conectar_postgres();
			$cons = "UPDATE Central.RegimenTercero SET regimen = replace( regimen,'". $cadenaBusqueda."'".",'". $cadenaReemplazo."')";
			
			$res = pg_query($cnx , $cons);
				if (!$res)  {
					echo "<p class='error1'> Error en la normalizacion de la codificacion </p>".pg_last_error()."<br>";
				}

		}		
			
			
		}

		
		/* Termina defincion de funciones */
		
		
		
		
		function migrarRegimenTercero($paso) {
		
			eliminarRegimenTercero();
			insertarRegimenTercero();	
			normalizarCodificacion('&Uacute;',utf8_encode("Ú"));		
			echo "<p class='mensajeEjecucion'> <span class = 'subtitulo1'>Paso $paso: </span> Se ha migrado la tabla Central.RegimenTercero </p> ";
	
		}
		
		
		
		
		
	
	
	
	?>
