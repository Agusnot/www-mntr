	<html>	
		<head>
			<title> Migracion Consumo.Clasificaciones </title>
			<link rel="stylesheet" type="text/css" href="../General/estilos/estilos.css">
		</head>
	
	
	<?php
		session_start();
		
		
		
		
		/* Inicia defincion de funciones */
		
		
		function insertarClasificaciones() {
			$cnx = conectar_postgres();
				$ruta = $_SERVER['DOCUMENT_ROOT'];
				$cons= "COPY Consumo.Clasificaciones FROM '$ruta/Migraciones/Consumo/Clasificaciones/Clasificaciones.csv' WITH DELIMITER ';' CSV HEADER;";
				$res =  @pg_query($cons);
					if (!$res) {
						echo "<p class='error1'> Error de ejecucion </p>".pg_last_error()."<br>";
						echo "<p class= 'subtitulo1'>Comando SQL </p> <br>".$cons."<br/>";
							
					}
		
		}
		
		function eliminarClasificaciones(){
		
			$cnx = conectar_postgres();
			$cons= "DELETE FROM Consumo.Clasificaciones";
			$res = pg_query($cnx, $cons);
				if ($res) {
					echo "<p class='mensajeEjecucion'> <span class = 'subtitulo1'>Paso $paso: </span> Se ha actualizado la tabla Consumo.Clasificaciones </p> ";
				}
				
				if (!$res) {
					echo "<p class='error1'> Error de ejecucion </p>".pg_last_error()."<br>";
					echo "<p class= 'subtitulo1'>Comando SQL </p> <br>".$cons."<br>"; 	
				}
		
		}
		
		
		function migrarClasificaciones($paso){
			eliminarClasificaciones();
			insertarClasificaciones();
			
			echo "<p class='mensajeEjecucion'><span class = 'subtitulo1'>Paso $paso: </span>  Se ha migrado la tabla Consumo.Clasificaciones </p> ";
		
		}
		
		
		
		
		/* Inicia defincion de funciones */
		
		
		