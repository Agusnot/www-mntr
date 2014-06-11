	<html>
		<head>
			<title> Migracion Salud.Ambitos </title> 
			<link rel="stylesheet" type="text/css" href="../../General/estilos/estilos.css">
			<meta charset="UTF-8">
		</head>
	</html>	
	


<?php


	include_once('../General/funciones/funciones.php');

	/* Inicia la definicion de funciones */
		
			
		function eliminarAmbitos() {
		
			$cnx = conectar_postgres();			
			$cons= "TRUNCATE Salud.Ambitos CASCADE";
			$res =  @pg_query($cons);
				if (!$res) {
								echo "<p class='error1'> Error de ejecucion </p>".pg_last_error()."<br>";
								echo "<p class= 'subtitulo1'>Comando SQL </p> <br>".$cons."<br/>";
				}
		}
		
		function actualizarCentroCostosAmbitos() {
		
			$cnx = conectar_postgres();			
			$cons= "UPDATE Salud.Ambitos SET centrocostos = '000' WHERE centrocostos = '0'";
			$res =  @pg_query($cons);
				if (!$res) {
								echo "<p class='error1'> Error de ejecucion </p>".pg_last_error()."<br>";
								echo "<p class= 'subtitulo1'>Comando SQL </p> <br>".$cons."<br/>";
				}
		}
	
			
		function insertarAmbitos() {
		
			$cnx = conectar_postgres();
			$ruta = $_SERVER['DOCUMENT_ROOT'];
			$cons= "COPY Salud.Ambitos  FROM '$ruta/Migraciones/Salud/Ambitos/Ambitos.csv' WITH DELIMITER ';' CSV HEADER;";
			$res =  @pg_query($cons);
				if (!$res) {
								echo "<p class='error1'> Error de ejecucion </p>".pg_last_error()."<br>";
								echo "<p class= 'subtitulo1'>Comando SQL </p> <br>".$cons."<br/>";
				}
		}
		
		
	
		
			
			
		
	
		/* Finaliza la definicion de funciones*/	
		
		
		
		
		/* Inicia la ejecucion de la migracion */
		function migrarAmbitos($paso) {
			
			
			eliminarAmbitos();
			insertarAmbitos();
			actualizarCentroCostosAmbitos();
			
			
			echo "<p class='mensajeEjecucion'> <span class = 'subtitulo1'>Paso $paso: </span> Se ha migrado la tabla Salud.Ambitos </p> ";
	
		}	
		
			
			

						
			
?>