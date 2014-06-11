	<html>
		<head>
			<title> Migracion HistoriaClinica.Escolaridad</title> 
			<link rel="stylesheet" type="text/css" href="../../General/estilos/estilos.css">
			<meta charset="UTF-8">
		</head>
	</html>	
	


<?php

	session_start();
	include_once('../General/funciones/funciones.php');

	/* Inicia la definicion de funciones */
		
		
		
		function eliminarEscolaridad() {
		
			$cnx = conectar_postgres();
			$compania = $_SESSION["compania"];
			$cons= "DELETE FROM  HistoriaClinica.Escolaridad";
			$res =  @pg_query($cons);
				if (!$res) {
								echo "<p class='error1'> Error de ejecucion </p>".pg_last_error()."<br>";
								echo "<p class= 'subtitulo1'>Comando SQL </p> <br>".$cons."<br/>";
				}
		}
		
			
		function insertarEscolaridad() {
		
			$cnx = conectar_postgres();
			$compania = $_SESSION["compania"];
			$cons= "INSERT INTO HistoriaClinica.Escolaridad (id, escolaridad, compania) VALUES (1, 'SIN ESCOLARIDAD', '$compania') , (2, 'PRE-ESCOLAR', '$compania'), (3, 'PRIMARIA INCOMPLETA', '$compania'), (4, 'PRIMARIA COMPLETA', '$compania'), (5, 'SECUNDARIA INCOMPLETA', '$compania'), (6, 'SECUNDARIA COMPLETA', '$compania'), (7, 'UNIVERSIDAD INCOMPLETA', '$compania'), (8, 'UNIVERSIDAD COMPLETA', '$compania'), (9, 'TECNICO', '$compania') , (10, 'TECNOLOGO', '$compania') ,(11, 'POST-UNIVERSITARIO', '$compania')";
			$res =  @pg_query($cons);
				if (!$res) {
								echo "<p class='error1'> Error de ejecucion </p>".pg_last_error()."<br>";
								echo "<p class= 'subtitulo1'>Comando SQL </p> <br>".$cons."<br/>";
				}
		}
		
		
	
		
			
			
		
	
		/* Finaliza la definicion de funciones*/	
		
		
		
		
		
		
		
			
			

						
			
?>