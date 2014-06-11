	<html>	
		<head>
			<title> Migracion Central.Meses </title>
			<link rel="stylesheet" type="text/css" href="../General/estilos/estilos.css">
		</head>
	
	
	<?php
		
		
		
		
		
		/* Inicia defincion de funciones */
		
		function eliminarMeses() {
			// Elimina la tabla de migracion
			$cnx= conectar_postgres();
			$cons = "DELETE FROM Central.Meses";
			$res = @pg_query($cnx, $cons);
				if (!$res) {
				
					echo "<p class='error1'> Error de ejecucion </p>".pg_last_error()."<br>";
					echo "<p class= 'subtitulo1'>Comando SQL </p> <br>".$cons."<br>"; 				
					echo "<br><br>";			
					//echo "<br><br>";
				}
			
		}
		
		
		function insertarMeses() {
			// Elimina la tabla de migracion
			$cnx= conectar_postgres();
			$cons = "INSERT INTO central.meses (Mes, Numero, Numdias) VALUES ( 'ENERO', '1', '31'), ( 'FEBRERO', '2', '28'), ( 'MARZO', '3', '31'), ( 'ABRIL', '4', '30'), ( 'MAYO', '5', '31'), ( 'JUNIO', '6', '30'), ( 'JULIO', '7', '31'), ( 'AGOSTO', '8', '31'), ( 'SEPTIEMBRE', '9', '30'), ( 'OCTUBRE', '10', '31'), ( 'NOVIEMBRE', '11', '30'), ( 'DICIEMBRE', '12', '31')";
			$res = @pg_query($cnx, $cons);
				if (!$res) {
				
					echo "<p class='error1'> Error de ejecucion </p>".pg_last_error()."<br>";
					echo "<p class= 'subtitulo1'>Comando SQL </p> <br>".$cons."<br>"; 				
					echo "<br><br>";			
					//echo "<br><br>";
				}
			
			
		}

		
		/* Termina defincion de funciones */
		
		
		
		
		function migrarMeses($paso) {
		
			eliminarMeses();
			insertarMeses();			
			echo "<p class='mensajeEjecucion'> <span class = 'subtitulo1'>Paso $paso: </span> Se ha migrado la tabla Central.Meses </p> ";
	
		}
		
		
		
		
		
	
	
	
	?>
