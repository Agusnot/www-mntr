	<html>	
		<head>
			<title> Migracion Consumo.Comprobantes </title>
			
			<link rel="stylesheet" type="text/css" href="../General/estilos/estilos.css">
		</head>
	
	
	<?php
		session_start();
		
				
		
		/* Inicia definicion de funciones */
		
		function eliminarComprobantes(){
			$cnx = conectar_postgres();
			$cons = "DELETE FROM Consumo.Comprobantes";
			$res = @pg_query($cnx, $cons);
				if (!$res) {
					echo "<p class='error1'> Error de ejecucion </p>".pg_last_error()."<br>";
					echo "<p class= 'subtitulo1'>Comando SQL </p> <br>".$cons."<br>"; 	
				}
		
		
		}
	
		
		
		
		function actualizarComprobantes($paso)
		
		{		
			eliminarComprobantes();
			$cnx = conectar_postgres();
			$ruta = $_SERVER['DOCUMENT_ROOT'];
			$cons= "COPY Consumo.Comprobantes FROM '$ruta/Migraciones/Consumo/Comprobantes/comprobantes.csv' WITH DELIMITER ';' CSV HEADER;";
			$res = @pg_query($cnx, $cons);
				if ($res) {
					echo "<p class='mensajeEjecucion'> <span class = 'subtitulo1'>Paso $paso: </span> Se ha  actualizado  la tabla Consumo.Comprobantes </p> ";
				}				
				
				if (!$res) {
					echo "<p class='error1'> Error de ejecucion </p>".pg_last_error()."<br>";
					echo "<p class= 'subtitulo1'>Comando SQL </p> <br>".$cons."<br>"; 	
				}
		
		}
		
		
		
		
		
		
		
		
		
		/* Inicia defincion de funciones */
		
		
		