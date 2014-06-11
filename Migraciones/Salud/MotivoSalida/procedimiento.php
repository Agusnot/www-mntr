	<html>	
		<head>
			<title> Migracion Salud.MotivoSalida </title>
			<link rel="stylesheet" type="text/css" href="../General/estilos/estilos.css">
		</head>
	
	
	<?php
		session_start();
		include_once('../General/funciones/funciones.php');
		
		
		
		
		/* Inicia defincion de funciones */
		
		
		function eliminarMotivoSalida(){
		
		$cnx = 	conectar_postgres();
			$cons = "DELETE FROM Salud.MotivoSalida"	;
					 
			
			$res = @pg_query($cnx, $cons);
				if (!$res) {
					 	echo "<p class='error1'> Error de ejecucion </p>".pg_last_error()."<br>";
						echo "<p class= 'subtitulo1'>Comando SQL </p> <br>".$consUTF8."<br/> <br/> <br/>";  
				
						}
		
		}
		
		function insertarMotivoSalida() {
		
			$cnx = conectar_postgres();
			$ruta = $_SERVER['DOCUMENT_ROOT'];
			$cons= "COPY Salud.MotivoSalida FROM '$ruta/Migraciones/Salud/MotivoSalida/MotivoSalida.csv' WITH DELIMITER ';' CSV HEADER;";
				
			$res =  @pg_query($cons);
					if (!$res) {
					 	echo "<p class='error1'> Error de ejecucion </p>".pg_last_error()."<br>";
						echo "<p class= 'subtitulo1'>Comando SQL </p> <br>".$cons."<br/> <br/> <br/>";  
				
					}
			//echo "<p class='mensajeEjecucion'><span class = 'subtitulo1'>Paso $paso: </span> Se ha relacionado  el usuario 'Admin' con  la compania 'Clinica San Juan de Dios' en la tabla 'usuariosxmodulos'  </p> ";
			
		}
		
		
		
		function migrarMotivoSalida($paso){
		
		$compania = $_SESSION["compania"];
		$edad = 0;	
		eliminarMotivoSalida();	
		insertarMotivoSalida();
		
		
		echo "<p class='mensajeEjecucion'> <span class = 'subtitulo1'>Paso $paso: </span> Se han actualizado los registros de la tabla Salud.MotivoSalida </p> ";
			
		
		}
		
		
		
		
		
	
	
	
	?>
