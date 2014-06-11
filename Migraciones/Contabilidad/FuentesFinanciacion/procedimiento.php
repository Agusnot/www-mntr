	<html>	
		<head>
			<title> Migracion Contabilidad.FuentesFinanciacion </title>
			<link rel="stylesheet" type="text/css" href="../General/estilos/estilos.css">
		</head>
	
	
	<?php
		session_start();
		include_once('../General/funciones/funciones.php');
		
		
		
		
		/* Inicia defincion de funciones */
		
		
			
			function eliminarFuentesFinanciacion($paso) {
				$cnx= conectar_postgres();
				$cons= "DELETE FROM Contabilidad.FuentesFinanciacion";
				$res = @pg_query($cnx, $cons);
					if (!$res) {
								echo "<p class='error1'> Error de ejecucion </p>".pg_last_error()."<br>";
								echo "<p class= 'subtitulo1'>Comando SQL </p> <br>".$cons."<br/>";
								
					}
				
					if ($res) {
						echo "<p class='mensajeEjecucion'> <span class = 'subtitulo1'>Paso $paso: </span> Se han eliminado los registros de la tabla Contabilidad.FuentesFinanciacion </p> ";
					
					}
					
			}
			
			
			
			
		
		
		
		
		
		
		
		
	
	
	
	?>
