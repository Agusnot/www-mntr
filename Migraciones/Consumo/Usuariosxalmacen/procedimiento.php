	<html>	
		<head>
			<title> Migracion Consumo.UsuariosxAlmacenes </title>
			
			<link rel="stylesheet" type="text/css" href="../General/estilos/estilos.css">
		</head>
	
	
	<?php
		session_start();
		
		
		
		
		/* Inicia definicion de funciones */ 
		
		function eliminarUsuariosxAlmacen($paso) {
			$cnx = conectar_postgres();
			$cons= "DELETE FROM Consumo.Usuariosxalmacenes";
			$res = pg_query($cnx, $cons);
				if ($res) {
					echo "<p class='mensajeEjecucion'> <span class = 'subtitulo1'>Paso $paso: </span> Se han actualizado los registros de la tabla Consumo.UsuariosxAlmacenes </p> ";
				}
							
				if (!$res) {
					echo "<p class='error1'> Error de ejecucion </p>".pg_last_error()."<br>";
					echo "<p class= 'subtitulo1'>Comando SQL </p> <br>".$cons."<br>"; 	
				}
		
		}
		
		

		
		
		
		
		
		
		
		
		/* Inicia defincion de funciones */
		
		
		