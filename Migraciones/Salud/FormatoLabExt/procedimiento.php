	<html>	
		<head>
			<title> Migracion Salud.FormatoLabExt </title>
			<link rel="stylesheet" type="text/css" href="../General/estilos/estilos.css">
		</head>
	
	
	<?php
		session_start();
		include_once('../General/funciones/funciones.php');
		
		
		
		
		/* Inicia defincion de funciones */
		
		
		function eliminarFormatoLabExt(){
		
		$cnx = 	conectar_postgres();
			$cons = "DELETE FROM Salud.FormatoLabExt"	;
					 
			
			$res = @pg_query($cnx, $cons);
				if (!$res) {
					 	echo "<p class='error1'> Error de ejecucion </p>".pg_last_error()."<br>";
						echo "<p class= 'subtitulo1'>Comando SQL </p> <br>".$consUTF8."<br/> <br/> <br/>";  
				
						}
		
		}
		
		function insertarFormatoLabExt($compania, $edad) {
		//Realiza la insercion en Postgresql con base en los parametros
		
			
			$cnx = 	conectar_postgres();
			$cons = "INSERT INTO Salud.FormatoLabExt(compania, tipoformato, formato, id_item) VALUES ('$compania', 'Formatos Generales', 'INTERPRETACION LABORATORIO', 1)"	;
					 
			
			$res = @pg_query($cnx, $cons);
				if (!$res) {
					 	echo "<p class='error1'> Error de ejecucion </p>".pg_last_error()."<br>";
						echo "<p class= 'subtitulo1'>Comando SQL </p> <br>".$consUTF8."<br/> <br/> <br/>";  
				
						}
						
				
	
		}
		
		
		
		function migrarFormatoLabExt($paso){
		
		$compania = $_SESSION["compania"];
		$edad = 0;	
		eliminarFormatoLabExt();	
		insertarFormatoLabExt($compania, $edad);
		
		
		echo "<p class='mensajeEjecucion'> <span class = 'subtitulo1'>Paso $paso: </span> Se han actualizado los registros de la tabla Salud.FormatoLabExt </p> ";
			
		
		}
		
		
		
		
		
	
	
	
	?>
