	<html>	
		<head>
			<title> Migracion Central.Departamentos </title>
			<link rel="stylesheet" type="text/css" href="../General/estilos/estilos.css">
		</head>
	
	
	<?php
		session_start();
		include_once('../General/funciones/funciones.php');
		include_once('../Central/Municipios/procedimiento.php');
		
		
		
		
		/* Inicia defincion de funciones */
		
			
		
			
			function eliminarDepartamentos() {
				$cnx= conectar_postgres();
				$cons= "DELETE FROM Central.Departamentos";
				$res = @pg_query($cnx, $cons);
				if (!$res) {
							echo "<p class='error1'> Error de ejecucion </p>".pg_last_error()."<br>";
							echo "<p class= 'subtitulo1'>Comando SQL </p> <br>".$cons."<br/>";
							
						}
					
			}
			
			function insertarDepartamentos() {
				$cnx = conectar_postgres();
				$ruta = $_SERVER['DOCUMENT_ROOT'];
				$cons= "COPY Central.Departamentos FROM '$ruta/Migraciones/Central/Departamentos/Departamentos.csv' WITH DELIMITER ';' CSV HEADER;";
				$res =  @pg_query($cons);
					if (!$res) {
						echo "<p class='error1'> Error de ejecucion </p>".pg_last_error()."<br>";
						echo "<p class= 'subtitulo1'>Comando SQL </p> <br>".$cons."<br/>";
							
					}
					
			}
			
			
			
			
			
			
		
		
		function migrarDepartamentos($paso) {
			
			eliminarMunicipios() ;
			eliminarDepartamentos();
			insertarDepartamentos();
			
			echo "<p class='mensajeEjecucion'> <span class = 'subtitulo1'>Paso $paso: </span> Se ha migrado la tabla Central.Departamentos </p> ";
	
		}
		
		
		
		
		
	
	
	
	?>
