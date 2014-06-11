	<html>	
		<head>
			<title> Migracion Central.TiposDocumento </title>
			<link rel="stylesheet" type="text/css" href="../General/estilos/estilos.css">
		</head>
	
	
	<?php
		
		
		
		
		
		/* Inicia defincion de funciones */
		
		function eliminarTiposDocumento() {
			// Elimina la tabla Central.Terceros que tiene el campo 'tipo' como llave foranea
			$cnx= conectar_postgres();
			$cons = "DELETE FROM Central.TiposDocumentos";
			$res = @pg_query($cnx, $cons);
				if (!$res) {
				
					echo "<p class='error1'> Error de ejecucion </p>".pg_last_error()."<br>";
					echo "<p class= 'subtitulo1'>Comando SQL </p> <br>".$cons."<br>"; 				
					echo "<br><br>";			
					//echo "<br><br>";
				}
			
		}
		
		

		
		
		function insertarTiposDocumento() {
			// Elimina la tabla de migracion
			$cnx= conectar_postgres();
			$cons = "INSERT INTO Central.TiposDocumentos(tipodoc, codigo, codws) VALUES ('Pasaporte','PA',NULL),('Adulto sin identificacion','AS',NULL),('Cedula de ciudadania','CC',1),('Cedula de extranjeria','CE',4),('Menor sin identificacion','MS',9),('Numero unico de identificacion','NU',8),('Registro civil','RC',7),('Tarjeta de identidad','TI',3)";
			$res = @pg_query($cnx, $cons);
				if (!$res) {
				
					echo "<p class='error1'> Error de ejecucion </p>".pg_last_error()."<br>";
					echo "<p class= 'subtitulo1'>Comando SQL </p> <br>".$cons."<br>"; 				
					echo "<br><br>";			
					//echo "<br><br>";
				}
		}		
				
				


		
		/* Termina defincion de funciones */
		
		
		
		
		function migrarTiposDocumento($paso) {
		
			
			eliminarTiposDocumento();
			insertarTiposDocumento();	
			echo "<p class='mensajeEjecucion'> <span class = 'subtitulo1'>Paso $paso: </span> Se ha migrado la tabla Central.TiposDocumento </p> ";
	
		}
		
		
	?>	

