	<html>	
		<head>
			<title> Migracion Consumo.TipoCausaSalida </title>
			
			<link rel="stylesheet" type="text/css" href="../General/estilos/estilos.css">
		</head>
	
	
	<?php
		session_start();
		
		
		
		
		/* Inicia definicion de funciones */ 
		
		function eliminarTiposCausaSalida($paso) {
			$cnx = conectar_postgres();
			$cons= "DELETE FROM Consumo.Tipocausalida";
			$res = pg_query($cnx, $cons);
				if ($res) {
					echo "<p class='mensajeEjecucion'> <span class = 'subtitulo1'>Paso $paso: </span> Se han eliminado los registros de la tabla Consumo.TipoCausalida </p> ";
				}
							
				if (!$res) {
					echo "<p class='error1'> Error de ejecucion </p>".pg_last_error()."<br>";
					echo "<p class= 'subtitulo1'>Comando SQL </p> <br>".$cons."<br>"; 	
				}
		
		}
		
		

	
		
		
		
		
		/* Inicia defincion de funciones */
		
		
		