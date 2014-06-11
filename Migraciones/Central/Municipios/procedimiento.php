	<html>	
		<head>
			<title> Migracion Central.Municipios </title>
			<link rel="stylesheet" type="text/css" href="../General/estilos/estilos.css">
		</head>
	
	
	<?php
		session_start();
		include_once('../General/funciones/funciones.php');
		
		
		
		
		/* Inicia defincion de funciones */
		
			
		
			
			
			
			function insertarMunicipios() {
				$cnx = conectar_postgres();
				$ruta = $_SERVER['DOCUMENT_ROOT'];
				$cons= "COPY Central.Municipios FROM '$ruta/Migraciones/Central/Municipios/Municipios.csv' WITH DELIMITER ';' CSV HEADER;";
				$res =  @pg_query($cons);
					if (!$res) {
						echo "<p class='error1'> Error de ejecucion </p>".pg_last_error()."<br>";
						echo "<p class= 'subtitulo1'>Comando SQL </p> <br>".$cons."<br/>";
							
					}
					
			}
			
			
			function eliminarMunicipios() {
				$cnx= conectar_postgres();
				$cons= "DELETE FROM Central.Municipios";
				$res = @pg_query($cnx, $cons);
				if (!$res) {
							echo "<p class='error1'> Error de ejecucion </p>".pg_last_error()."<br>";
							echo "<p class= 'subtitulo1'>Comando SQL </p> <br>".$cons."<br/>";
							
						}
					
			}
			
			
			
			
		
		
		function migrarMunicipios($paso) {
			
			
			eliminarMunicipios();
			insertarMunicipios();
			
			echo "<p class='mensajeEjecucion'> <span class = 'subtitulo1'>Paso $paso: </span> Se ha migrado la tabla Central.Municipios </p> ";
	
		}
		
		
		
		
		
	
	
	
	?>
