	<html>	
		<head>
			<title> Migracion Consumo.Riesgos </title>
			
			<link rel="stylesheet" type="text/css" href="../General/estilos/estilos.css">
		</head>
	
	
	<?php
		session_start();
		
		
		
		
		/* Inicia definicion de funciones */ 
		
		function eliminarRiesgos() {
			$cnx = conectar_postgres();
			$cons= "DELETE FROM Consumo.Riesgos";
			$res = pg_query($cnx, $cons);
							
				if (!$res) {
					echo "<p class='error1'> Error de ejecucion </p>".pg_last_error()."<br>";
					echo "<p class= 'subtitulo1'>Comando SQL </p> <br>".$cons."<br>"; 	
				}
		
		}
		
		

		function insertarRiesgos($paso)
		
		{
			$cnx = conectar_postgres();
			$ruta = $_SERVER['DOCUMENT_ROOT'];
			$cons= "INSERT INTO Consumo.Riesgos(riesgo, compania) VALUES ('RIESGO I','$_SESSION[compania]'), ('RIESGO II','$_SESSION[compania]'), ('RIESGO IIA','$_SESSION[compania]'), ('RIESGO IIB','$_SESSION[compania]'), ('RIESGO III','$_SESSION[compania]')";
			$res = pg_query($cnx, $cons);
				if ($res) {
					echo "<p class='mensajeEjecucion'> <span class = 'subtitulo1'>Paso $paso: </span> Se han actualizado los registros de la tabla Consumo.Riesgos </p> ";
				}
				
				if (!$res) {
					echo "<p class='error1'> Error de ejecucion </p>".pg_last_error()."<br>";
					echo "<p class= 'subtitulo1'>Comando SQL </p> <br>".$cons."<br>"; 	
				}
		
		}
		
		
		
		function migrarRiesgos($paso){
			eliminarRiesgos();
			insertarRiesgos($paso);		
		
		}
		
		
		
		
		
		
		
		/* Inicia defincion de funciones */
		
		
		