	<html>	
		<head>
			<title> Migracion Consumo.PresentacionLabs </title>
			
			<link rel="stylesheet" type="text/css" href="../General/estilos/estilos.css">
		</head>
	
	
	<?php
		session_start();
		
		
		
		
		/* Inicia definicion de funciones */ 
				
		function eliminarPresentacionLabs() {
			$cnx = conectar_postgres();
			$cons= "DELETE FROM Consumo.PresentacionLabs";
			$res = pg_query($cnx, $cons);
							
				if (!$res) {
					echo "<p class='error1'> Error de ejecucion </p>".pg_last_error()."<br>";
					echo "<p class= 'subtitulo1'>Comando SQL </p> <br>".$cons."<br>"; 	
				}
		
		}
		
		

		function cargarPresentacionLabs($paso)
		
		{
			$cnx = conectar_postgres();
			$ruta = $_SERVER['DOCUMENT_ROOT'];
			$cons= "COPY Consumo.PresentacionLabs FROM '$ruta/Migraciones/Consumo/PresentacionLabs/presentacionLabs.csv' WITH DELIMITER ';' CSV HEADER;";
			$res = pg_query($cnx, $cons);
				if ($res) {
					echo "<p class='mensajeEjecucion'> <span class = 'subtitulo1'>Paso $paso: </span> Se han actualizado los registros de la tabla Consumo.PresentacionLabs </p> ";
				}
				
				if (!$res) {
					echo "<p class='error1'> Error de ejecucion </p>".pg_last_error()."<br>";
					echo "<p class= 'subtitulo1'>Comando SQL </p> <br>".$cons."<br>"; 	
				}
		
		}
		
		
		
		function migrarPresentacionLabs($paso){
			eliminarPresentacionLabs();
			cargarPresentacionLabs($paso);
			
		
		}
		
		
		
		
		
		
		
		/* Inicia defincion de funciones */
		
		
		