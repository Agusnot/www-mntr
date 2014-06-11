	<html>
		<head>
			<title> Migracion Contabilidad.ConvDirecciones </title> 
			<link rel="stylesheet" type="text/css" href="../../General/estilos/estilos.css">
			<meta charset="UTF-8">
		</head>
	</html>	
	


<?php


	include_once('../General/funciones/funciones.php');

	/* Inicia la definicion de funciones */
	

			
			
	
			
		function insertarConvDirecciones() {
		
			$cnx = conectar_postgres();
			$ruta = $_SERVER['DOCUMENT_ROOT'];
			$cons= "COPY Contabilidad.ConvDirecciones FROM '$ruta/Migraciones/Contabilidad/ConvDirecciones/ConvDirecciones.csv' WITH DELIMITER ';' CSV HEADER;";
			$res =  @pg_query($cons);
				if (!$res) {
								echo "<p class='error1'> Error de ejecucion </p>".pg_last_error()."<br>";
								echo "<p class= 'subtitulo1'>Comando SQL </p> <br>".$cons."<br/>";
				}
		}
		
		function eliminarConvDirecciones() {
		
			$cnx = conectar_postgres();
			$ruta = $_SERVER['DOCUMENT_ROOT'];
			$cons= "DELETE FROM Contabilidad.ConvDirecciones";
			$res =  @pg_query($cons);
				if (!$res) {
								echo "<p class='error1'> Error de ejecucion </p>".pg_last_error()."<br>";
								echo "<p class= 'subtitulo1'>Comando SQL </p> <br>".$cons."<br/>";
				}
		}
		
			
			
		function migrarConvDirecciones($paso) {
			eliminarConvDirecciones();
			insertarConvDirecciones();
			echo "<p class='mensajeEjecucion'> <span class = 'subtitulo1'>Paso $paso: </span> Se han actualizado los registros de la tabla Contabilidad.ConvDirecciones </p> ";
		
		
		
		
		}	
	

		/* Finaliza la definicion de funciones*/	
		
		
		
		
		/* Inicia la ejecucion de la migracion */
			
		
			
			

						
			
?>