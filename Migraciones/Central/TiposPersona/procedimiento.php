	<html>	
		<head>
			<title> Migracion Central.TiposPersona </title>
			<link rel="stylesheet" type="text/css" href="../General/estilos/estilos.css">
		</head>
	
	
	<?php
		
		
		
		
		
		/* Inicia defincion de funciones */
		
		function eliminarTiposPersona() {
			// Elimina la tabla Central.Terceros que tiene el campo 'tipo' como llave foranea
			$cnx= conectar_postgres();
			$cons = "DELETE FROM Central.TiposPersonas";
			$res = @pg_query($cnx, $cons);
				if (!$res) {
				
					echo "<p class='error1'> Error de ejecucion </p>".pg_last_error()."<br>";
					echo "<p class= 'subtitulo1'>Comando SQL </p> <br>".$cons."<br>"; 				
					echo "<br><br>";			
					//echo "<br><br>";
				}
			
		}
		
		
			
		
		function insertarTiposPersona() {
			// Elimina la tabla de migracion
			$cnx= conectar_postgres();
			$cons = "INSERT INTO Central.TiposPersonas (tipo, codigo) VALUES ('Persona Juridica',1) , ('Persona Natural', 2), ('Por validar', 3)";
			$res = @pg_query($cnx, $cons);
				if (!$res) {
				
					echo "<p class='error1'> Error de ejecucion </p>".pg_last_error()."<br>";
					echo "<p class= 'subtitulo1'>Comando SQL </p> <br>".$cons."<br>"; 				
					echo "<br><br>";			
					//echo "<br><br>";
				}
		}		
				
				


		
		/* Termina defincion de funciones */
		
		
		
		
		function migrarTiposPersona($paso) {
		
			
			eliminarTiposPersona();
			insertarTiposPersona();	
			echo "<p class='mensajeEjecucion'> <span class = 'subtitulo1'>Paso $paso: </span> Se ha migrado la tabla Central.TiposPersona </p> ";
	
		}
		
		
	?>	

