	<html>	
		<head>
			<title> Migracion Central.TiposSangre </title>
			<link rel="stylesheet" type="text/css" href="../General/estilos/estilos.css">
		</head>
	
	
	<?php
		
		
		
		
		
		/* Inicia defincion de funciones */
		
		function eliminarTiposSangre() {
			// Elimina la tabla Central.Terceros que tiene el campo 'tipo' como llave foranea
			$cnx= conectar_postgres();
			$cons = "DELETE FROM Central.TiposSangre";
			$res = @pg_query($cnx, $cons);
				if (!$res) {
				
					echo "<p class='error1'> Error de ejecucion </p>".pg_last_error()."<br>";
					echo "<p class= 'subtitulo1'>Comando SQL </p> <br>".$cons."<br>"; 				
					echo "<br><br>";			
					//echo "<br><br>";
				}
			
		}
		
		function cambiarCampo() {
		
			$cnx = conectar_postgres();
			$cons = "ALTER TABLE Central.TiposSangre  ALTER COLUMN tiposangre TYPE character varying(15)";
				if ($_GET['verConsultas'] == 'true') {
						echo "<p class='subtitulo1'>Consulta Paso $paso : </p>";
						echo $cons;
					}
			$res =  pg_query($cnx, $cons);
			echo "<p class='mensajeEjecucion'><span class = 'subtitulo1'>Paso $paso: </span>  Se ha modificado el campo TipoSangre de la tabla  Central.TiposSangre a character varying(15)  </p> ";
			
		}
		
			
		
		function insertarTiposSangre() {
			// Elimina la tabla de migracion
			$cnx= conectar_postgres();
			$cons = "INSERT INTO central.tipossangre (tiposangre) VALUES ('-NO REGISTRA -'), ('A NEGATIVO'), ('A POSITIVO'), ('AB NEGATIVO'), ('AB POSITIVO'), ('B NEGATIVO') , ('B POSITIVO') , ('O NEGATIVO'), ('O POSITIVO')";
			$res = @pg_query($cnx, $cons);
				if (!$res) {
				
					echo "<p class='error1'> Error de ejecucion </p>".pg_last_error()."<br>";
					echo "<p class= 'subtitulo1'>Comando SQL </p> <br>".$cons."<br>"; 				
					echo "<br><br>";			
					//echo "<br><br>";
				}
		}		
				
				


		
		/* Termina defincion de funciones */
		
		
		
		
		function migrarTiposSangre($paso) {
		
			
			eliminarTiposSangre();
			cambiarCampo();
			insertarTiposSangre();	
			echo "<p class='mensajeEjecucion'> <span class = 'subtitulo1'>Paso $paso: </span> Se ha migrado la tabla Central.TiposSangre </p> ";
	
		}
		
		
	?>	

