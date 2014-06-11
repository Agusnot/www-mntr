	<html>
		<head>
			<title> Migracion ContratacionSalud.TiposdeProdXFormulacion </title> 
			<link rel="stylesheet" type="text/css" href="../../General/estilos/estilos.css">
			<meta charset="UTF-8">
		</head>
	</html>	
	


<?php


	include_once('../General/funciones/funciones.php');

	/* Inicia la definicion de funciones */
		
			
		function eliminarTiposdeProdXFormulacion() {
		
			$cnx = conectar_postgres();			
			$cons= "DELETE FROM  ContratacionSalud.TiposdeProdXFormulacion";
			$res =  @pg_query($cons);
				if (!$res) {
								echo "<p class='error1'> Error de ejecucion </p>".pg_last_error()."<br>";
								echo "<p class= 'subtitulo1'>Comando SQL </p> <br>".$cons."<br/>";
				}
		}
		
		
	
			
		function insertarTiposdeProdXFormulacion() {
		
			$cnx = conectar_postgres();
			$ruta = $_SERVER['DOCUMENT_ROOT'];
			$cons= "COPY ContratacionSalud.TiposdeProdXFormulacion  FROM '$ruta/Migraciones/ContratacionSalud/TiposdeProdXFormulacion/TiposdeProdXFormulacion.csv' WITH DELIMITER ';' CSV HEADER;";
			$res =  @pg_query($cons);
				if (!$res) {
								echo "<p class='error1'> Error de ejecucion </p>".pg_last_error()."<br>";
								echo "<p class= 'subtitulo1'>Comando SQL </p> <br>".$cons."<br/>";
				}
		}
		
		
	
		
			
			
		
	
		/* Finaliza la definicion de funciones*/	
		
		
		
		
		/* Inicia la ejecucion de la migracion */
		function migrarTiposdeProdXFormulacion($paso) {
			
			
			eliminarTiposdeProdXFormulacion();
			insertarTiposdeProdXFormulacion();
			
			
			
			echo "<p class='mensajeEjecucion'> <span class = 'subtitulo1'>Paso $paso: </span> Se ha migrado la tabla ContratacionSalud.TiposdeProdXFormulacion </p> ";
	
		}	
		
			
			

						
			
?>