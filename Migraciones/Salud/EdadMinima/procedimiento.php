	<html>	
		<head>
			<title> Migracion Salud.EdadMinima </title>
			<link rel="stylesheet" type="text/css" href="../General/estilos/estilos.css">
		</head>
	
	
	<?php
		session_start();
		include_once('../General/funciones/funciones.php');
		
		
		
		
		/* Inicia defincion de funciones */
		
		
		function eliminarEdadMinima(){
		
		$cnx = 	conectar_postgres();
			$cons = "DELETE FROM Salud.EdadMinima"	;
					 
			
			$res = @pg_query($cnx, $cons);
				if (!$res) {
					 	echo "<p class='error1'> Error de ejecucion </p>".pg_last_error()."<br>";
						echo "<p class= 'subtitulo1'>Comando SQL </p> <br>".$consUTF8."<br/> <br/> <br/>";  
				
						}
		
		}
		
		function insertarEdadMinima($compania, $edad) {
		//Realiza la insercion en Postgresql con base en los parametros
		
			
			$cnx = 	conectar_postgres();
			$cons = "INSERT INTO Salud.EdadMinima (compania, edad) VALUES ('$compania', $edad)"	;
					 
			
			$res = @pg_query($cnx, $cons);
				if (!$res) {
					 	echo "<p class='error1'> Error de ejecucion </p>".pg_last_error()."<br>";
						echo "<p class= 'subtitulo1'>Comando SQL </p> <br>".$consUTF8."<br/> <br/> <br/>";  
				
						}
						
				
	
		}
		
		
		
		function migrarBloqueoxDia($paso){
		
		$compania = $_SESSION["compania"];
		$edad = 0;	
		eliminarEdadMinima();	
		insertarEdadMinima($compania, $edad);
		
		
		echo "<p class='mensajeEjecucion'> <span class = 'subtitulo1'>Paso $paso: </span> Se han actualizado los registros de la tabla Salud.EdadMinima </p> ";
			
		
		}
		
		
		
		
		
	
	
	
	?>
