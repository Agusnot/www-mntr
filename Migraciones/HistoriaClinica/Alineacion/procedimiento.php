	<html>
		<head>
			<title> Migracion HistoriaClinica.Alineacion</title> 
			<link rel="stylesheet" type="text/css" href="../../General/estilos/estilos.css">
			<meta charset="UTF-8">
		</head>
	</html>	
	


<?php


	include_once('../General/funciones/funciones.php');

	/* Inicia la definicion de funciones */
		
			
		function eliminarAlineacion() {
		
			$cnx = conectar_postgres();			
			$cons= "DELETE FROM HistoriaClinica.Alineacion";
			$res =  @pg_query($cons);
				if (!$res) {
								echo "<p class='error1'> Error de ejecucion </p>".pg_last_error()."<br>";
								echo "<p class= 'subtitulo1'>Comando SQL </p> <br>".$cons."<br/>";
				}
		}
	
			
		function insertarAlineacion() {
		
			$cnx = conectar_postgres();
			
			$cons= "INSERT INTO HistoriaClinica.Alineacion (nombre) VALUES ('Horizontal'), ('Vertical')";
			$res =  @pg_query($cons);
				if (!$res) {
								echo "<p class='error1'> Error de ejecucion </p>".pg_last_error()."<br>";
								echo "<p class= 'subtitulo1'>Comando SQL </p> <br>".$cons."<br/>";
				}
		}
		
		
	
		
			
			
		
	
		/* Finaliza la definicion de funciones*/	
		
		
		
		
		/* Inicia la ejecucion de la migracion */
		function migrarAlineacion($paso) {
			
			
			eliminarAlineacion();
			insertarAlineacion();
			
			
			echo "<p class='mensajeEjecucion'> <span class = 'subtitulo1'>Paso $paso: </span> Se ha migrado la tabla HistoriaClinica.Alineacion </p> ";
	
		}	
		
			
			

						
			
?>