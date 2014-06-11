	<html>	
		<head>
			<title> Migracion Facturacion.FirmasRtaGlosas </title>
			<link rel="stylesheet" type="text/css" href="../General/estilos/estilos.css">
		</head>
	
	
	<?php
		session_start();
		include_once('../General/funciones/funciones.php');
		
		
		
		
		/* Inicia definicion de funciones */			
	

		
		function eliminarFirmasRtaGlosas() {
		//Realiza la insercion en Postgresql con base en los parametros
			
			$cnx = 	conectar_postgres();
			$cons = "DELETE FROM Facturacion.FirmasRtaGlosas";
					 
			$res = @pg_query($cnx, $cons);
				if (!$res) {
							echo  "<p class='error1'> Error de ejecucion </p>".pg_last_error()."<br>";
							echo "<p class= 'subtitulo1'>Comando SQL </p> <br>".$cons."<br/> <br/> <br/>";  
				}

		}
		
		
		
		
		
		function migrarFirmasRtaGlosas($paso) {
		
			

			eliminarFirmasRtaGlosas();
			
			echo "<p class='mensajeEjecucion'> <span class = 'subtitulo1'>Paso $paso: </span> Se han eliminado los registros de  la tabla Facturacion.FirmasRtaGlosas </p> ";
	
		}
		
		
		
		
		
	
	
	
	?>
