	<html>
		<head>
			<title> Migracion HistoriaClinica.TipoFormato</title> 
			<link rel="stylesheet" type="text/css" href="../../General/estilos/estilos.css">
			<meta charset="UTF-8">
		</head>
	</html>	
	


<?php

	session_start();
	include_once('../General/funciones/funciones.php');

	/* Inicia la definicion de funciones */
		
		
		
		function eliminarTipoFormato() {
		
			$cnx = conectar_postgres();
			$compania = $_SESSION["compania"];
			$cons= "DELETE FROM  HistoriaClinica.TipoFormato";
			$res =  @pg_query($cons);
				if (!$res) {
								echo "<p class='error1'> Error de ejecucion </p>".pg_last_error()."<br>";
								echo "<p class= 'subtitulo1'>Comando SQL </p> <br>".$cons."<br/>";
				}
		}
		
			
		function insertarTipoFormato() {
		
			$cnx = conectar_postgres();
			$compania = $_SESSION["compania"];
			$cons= "INSERT INTO HistoriaClinica.TipoFormato(nombre, prioridad, compania) VALUES('HISTORIA CLINICA', 1, '$compania') , ('ENFERMERIA', 2, '$compania') , ('PACIENTE SEGURO', 3, '$compania'), ('ADMINISTRATIVOS', 4, '$compania')";
			$res =  @pg_query($cons);
				if (!$res) {
								echo "<p class='error1'> Error de ejecucion </p>".pg_last_error()."<br>";
								echo "<p class= 'subtitulo1'>Comando SQL </p> <br>".$cons."<br/>";
				}
		}
		
		
		function migrarTipoFormato() {
			eliminarTipoFormato();
			insertarTipoFormato();		
		
		}
	
		
			
			
		
	
		/* Finaliza la definicion de funciones*/	
		
		
		
		
		
		
		
			
			

						
			
?>