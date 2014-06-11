	<html>	
		<head>
			<title> Migracion ContratacionSalud.CUPSxConsultaExter </title>
			<link rel="stylesheet" type="text/css" href="../../General/estilos/estilos.css">
		</head>
	
	
	<?php
		session_start();
		include_once('../General/funciones/funciones.php');
		include_once('../General/funciones/funciones.php');
		
		
		
		
		/* Inicia defincion de funciones */
		
		function eliminarCUPSxConsulExtern() {
				$cnx= conectar_postgres();
				$cons= "DELETE FROM ContratacionSalud.CUPSxConsulExtern";
				$res = @pg_query($cnx, $cons);
					if (!$res) {
								echo "<p class='error1'> Error de ejecucion </p>".pg_last_error()."<br>";
								echo "<p class= 'subtitulo1'>Comando SQL </p> <br>".$cons."<br/>";
								
					}
				
					
					
		}
		
		
		function insertarCUPSxConsulExtern(){
				$cnx = conectar_postgres();
				$ruta = $_SERVER['DOCUMENT_ROOT'];
				$cons= "COPY ContratacionSalud.CUPSxConsulExtern FROM '$ruta/Migraciones/ContratacionSalud/CUPSxConsulExtern/CUPSxConsulExtern.csv' WITH DELIMITER ';' CSV HEADER;";
				$res =  @pg_query($cons);
				
					if (!$res) {
						echo "<p class='error1'> Error de ejecucion </p>".pg_last_error()."<br>";
						echo "<p class= 'subtitulo1'>Comando SQL </p> <br>".$cons."<br/>";
							
					}
			
		}
		
		
		
		
		
		function migrarCUPSxConsulExtern($paso) {
			eliminarCUPSxConsulExtern();
			insertarCUPSxConsulExtern();
			
			echo "<p class='mensajeEjecucion'> <span class = 'subtitulo1'>Paso $paso: </span> Se han actualizado los registros de la tabla ContratacionSalud.CUPSxConsulExtern </p> ";
		
		
		}
		
		
		
		/* Termina defincion de funciones */
		
		
			
		
			
			
			
			
			
			
			
		
		
		
		
		
		
		
		
	
	
	
	?>
