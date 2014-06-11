	<html>	
		<head>
			<title> Migracion Facturacion.HistorialCuenta </title>
			<link rel="stylesheet" type="text/css" href="../General/estilos/estilos.css">
		</head>
	
	
	<?php
		session_start();
		include_once('../General/funciones/funciones.php');
		
		
		
		
		/* Inicia definicion de funciones */			
	

		
		function eliminarHistorialCuenta() {
		//Realiza la insercion en Postgresql con base en los parametros
			
			$cnx = 	conectar_postgres();
			$cons = "DELETE FROM Facturacion.HistorialCuenta";
					 
			$res = @pg_query($cnx, $cons);
				if (!$res) {
							echo  "<p class='error1'> Error de ejecucion </p>".pg_last_error()."<br>";
							echo "<p class= 'subtitulo1'>Comando SQL </p> <br>".$cons."<br/> <br/> <br/>";  
				}

		}
		
		
		
		
		
		function migrarHistorialCuenta($paso) {
		
			

			eliminarHistorialCuenta();
			
			echo "<p class='mensajeEjecucion'> <span class = 'subtitulo1'>Paso $paso: </span> Se han eliminado los registros de  la tabla Facturacion.HistorialCuenta </p> ";
	
		}
		
		
		
		
		
	
	
	
	?>
