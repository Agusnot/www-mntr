	<html>	
		<head>
			<title> Migracion Salud.BloqueoxDia </title>
			<link rel="stylesheet" type="text/css" href="../General/estilos/estilos.css">
		</head>
	
	
	<?php
		session_start();
		include_once('../General/funciones/funciones.php');
		
		
		
		
		/* Inicia defincion de funciones */
		
		
		function eliminarBloqueoxDia(){
		
		$cnx = 	conectar_postgres();
		$cons = "DELETE FROM Salud.BloqueoxDia WHERE motivo <> 'FESTIVO' "	;
					 
			
			$res = @pg_query($cnx, $cons);
				if (!$res) {
					 	echo "<p class='error1'> Error de ejecucion </p>".pg_last_error()."<br>";
						echo "<p class= 'subtitulo1'>Comando SQL </p> <br>".$consUTF8."<br/> <br/> <br/>";  
				
						}
		
		}
		
		function actualizarBloqueoxDia($compania, $usuario, $fecha) {
		//Realiza la insercion en Postgresql con base en los parametros
		
			
			$cnx = 	conectar_postgres();
			$cons = "UPDATE Salud.BloqueoxDia SET compania = '$compania', usuario = '$usuario', fechacrea = '$fecha'"	;
					 
			
			$res = @pg_query($cnx, $cons);
				if (!$res) {
					 	echo "<p class='error1'> Error de ejecucion </p>".pg_last_error()."<br>";
						echo "<p class= 'subtitulo1'>Comando SQL </p> <br>".$consUTF8."<br/> <br/> <br/>";  
				
						}
						
				
	
		}
		
		
		
		function migrarBloqueoxDia($paso){
		
		$compania = $_SESSION["compania"];
		$usuario = "Admin";
		$fecha = FechaActual();
		
		eliminarBloqueoxDia();		
		actualizarBloqueoxDia($compania, $usuario, $fecha);	
		
		echo "<p class='mensajeEjecucion'> <span class = 'subtitulo1'>Paso $paso: </span> Se han actualizado los registros de la tabla Salud.BloqueoxDia </p> ";
			
		
		}
		
		
		
		
		
	
	
	
	?>
