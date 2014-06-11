	<html>
		<head>
			<title> Migracion Salud.Interprogramas </title> 
			<link rel="stylesheet" type="text/css" href="../../General/estilos/estilos.css">
			<meta charset="UTF-8">
		</head>
	</html>	
	


<?php


	include_once('../General/funciones/funciones.php');

	/* Inicia la definicion de funciones */
		
			
		function eliminarInterprogramas() {
		
			$cnx = conectar_postgres();			
			$cons= "DELETE FROM  Salud.Interprogramas";
			$res =  @pg_query($cons);
				if (!$res) {
								echo "<p class='error1'> Error de ejecucion </p>".pg_last_error()."<br>";
								echo "<p class= 'subtitulo1'>Comando SQL </p> <br>".$cons."<br/>";
				}
		}
		
		
	
			
		function insertarInterprogramas() {
		
			$cnx = conectar_postgres();
			$ruta = $_SERVER['DOCUMENT_ROOT'];
			$cons= "COPY Salud.Interprogramas  FROM '$ruta/Migraciones/Salud/Interprogramas/Interprogramas.csv' WITH DELIMITER ';' CSV HEADER;";
			$res =  @pg_query($cons);
				if (!$res) {
								echo "<p class='error1'> Error de ejecucion </p>".pg_last_error()."<br>";
								echo "<p class= 'subtitulo1'>Comando SQL </p> <br>".$cons."<br/>";
				}
		}
		
		
	
		
			
			
		
	
		/* Finaliza la definicion de funciones*/	
		
		
		
		
		/* Inicia la ejecucion de la migracion */
		function migrarInterprogramas($paso) {
			
			
			eliminarInterprogramas();
			insertarInterprogramas();
			
			
			
			echo "<p class='mensajeEjecucion'> <span class = 'subtitulo1'>Paso $paso: </span> Se ha migrado la tabla Salud.Interprogramas </p> ";
	
		}	
		
			
			

						
			
?>