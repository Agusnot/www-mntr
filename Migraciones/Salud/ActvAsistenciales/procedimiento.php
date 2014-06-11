	<html>	
		<head>
			<title> Migracion Salud.ActvAsistenciales </title>
			<link rel="stylesheet" type="text/css" href="../General/estilos/estilos.css">
		</head>
	
	
	<?php
		session_start();
		include_once('../General/funciones/funciones.php');
		
		
		
		
		/* Inicia defincion de funciones */
		
		function actualizarActvAsistenciales($compania, $usuario, $fecha) {
		//Realiza la insercion en Postgresql con base en los parametros
			
			$cnx = 	conectar_postgres();
			$cons = "UPDATE Salud.ActvAsistenciales SET compania = '$compania', usucrea = '$usuario', fechacrea = '$fecha', usumod = '$usumod', fechamod = '$fecha'"	;
					 
			
			$res = @pg_query($cnx, $cons);
				if (!$res) {
					 	echo "<p class='error1'> Error de ejecucion </p>".pg_last_error()."<br>";
						echo "<p class= 'subtitulo1'>Comando SQL </p> <br>".$cons."<br/> <br/> <br/>";  
				
						}
						
				
	
		}
		
		
		
		function migrarActvAsistenciales($paso){
		
		$compania = $_SESSION["compania"];
		$usuario = "Admin";
		$fecha = FechaActual();
		
		actualizarActvAsistenciales($compania, $usuario, $fecha);	
		
		echo "<p class='mensajeEjecucion'> <span class = 'subtitulo1'>Paso $paso: </span> Se han actualizado los registros de la tabla Salud.ActvAsistenciales </p> ";
			
		
		}
		
		
		
		
		
	
	
	
	?>
