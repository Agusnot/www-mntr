	<html>	
		<head>
			<title> Migracion Central.TiposTercero </title>
			<link rel="stylesheet" type="text/css" href="../General/estilos/estilos.css">
		</head>
	
	
	<?php
		
		
		
		
		
		/* Inicia defincion de funciones */
		
		function eliminarTerceros() {
			// Elimina la tabla Central.Terceros que tiene el campo 'tipo' como llave foranea
			$cnx= conectar_postgres();
			$cons = "TRUNCATE Central.Terceros CASCADE";
			$res = @pg_query($cnx, $cons);
				if (!$res) {
				
					echo "<p class='error1'> Error de ejecucion </p>".pg_last_error()."<br>";
					echo "<p class= 'subtitulo1'>Comando SQL </p> <br>".$cons."<br>"; 				
					echo "<br><br>";			
					//echo "<br><br>";
				}
			
		}
		
		
			function eliminarTiposTercero() {

			$cnx= conectar_postgres();
			$cons = "DELETE FROM Central.TiposTercero";
			$res = @pg_query($cnx, $cons);
				if (!$res) {
				
					echo "<p class='error1'> Error de ejecucion </p>".pg_last_error()."<br>";
					echo "<p class= 'subtitulo1'>Comando SQL </p> <br>".$cons."<br>"; 				
					echo "<br><br>";			
					//echo "<br><br>";
				}
			
		}
		
		
		function insertarTiposTercero() {
			// Elimina la tabla de migracion
			$cnx= conectar_postgres();
			$cons = "INSERT INTO Central.TiposTercero(tipo) VALUES ('Cliente'), ('Proveedor'),('Empleado'),('Tercero'),('Gastos'), ('Paciente'), ('Asegurador'), ('Pensiones y Cesantias'), ('Fondo de Pensiones'),('Fondo de Cesantias'),('')";
			$res = @pg_query($cnx, $cons);
				if (!$res) {
				
					echo "<p class='error1'> Error de ejecucion </p>".pg_last_error()."<br>";
					echo "<p class= 'subtitulo1'>Comando SQL </p> <br>".$cons."<br>"; 				
					echo "<br><br>";			
					//echo "<br><br>";
				}
		}		
				
				


		
		/* Termina defincion de funciones */
		
		
		
		
		function migrarTiposTercero($paso) {
		
			eliminarTerceros();
			eliminarTiposTercero();
			insertarTiposTercero();	
			echo "<p class='mensajeEjecucion'> <span class = 'subtitulo1'>Paso $paso: </span> Se ha migrado la tabla Central.TiposTercero </p> ";
	
		}
		
		
	?>	

