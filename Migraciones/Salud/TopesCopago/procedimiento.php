	<html>	
		<head>
			<title> Migracion Salud.TopesCopago </title>
			<link rel="stylesheet" type="text/css" href="../../General/estilos/estilos.css">
		</head>
	
	
	<?php
		session_start();
		include_once('../General/funciones/funciones.php');
		include_once('../General/funciones/funciones.php');
		
		
		
		
		/* Inicia defincion de funciones */
		
		function eliminarTopesCopago() {
				$cnx= conectar_postgres();
				$cons= "DELETE FROM Salud.TopesCopago";
				$res = @pg_query($cnx, $cons);
					if (!$res) {
								echo "<p class='error1'> Error de ejecucion </p>".pg_last_error()."<br>";
								echo "<p class= 'subtitulo1'>Comando SQL </p> <br>".$cons."<br/>";
								
					}
				
					
					
		}
		
		
		function insertarTopesCopago(){
				$cnx = conectar_postgres();
				$ruta = $_SERVER['DOCUMENT_ROOT'];
				$cons= "COPY Salud.TopesCopago FROM '$ruta/Migraciones/Salud/TopesCopago/TopesCopago.csv' WITH DELIMITER ';' CSV HEADER;";
				$res =  @pg_query($cons);
				
					if (!$res) {
						echo "<p class='error1'> Error de ejecucion </p>".pg_last_error()."<br>";
						echo "<p class= 'subtitulo1'>Comando SQL </p> <br>".$cons."<br/>";
							
					}
			
		}
		
		function actualizarTopesCopago(){
				$cnx = conectar_postgres();
				
				$cons= "UPDATE Salud.TopesCopago SET TopeEvento = NULL";
				$res =  @pg_query($cons);
				
					if (!$res) {
						echo "<p class='error1'> Error de ejecucion </p>".pg_last_error()."<br>";
						echo "<p class= 'subtitulo1'>Comando SQL </p> <br>".$cons."<br/>";
							
					}
			
		}
		
		
		
		
		
		function migrarTopesCopago($paso) {
			eliminarTopesCopago();
			insertarTopesCopago();
			actualizarTopesCopago();
			
			echo "<p class='mensajeEjecucion'> <span class = 'subtitulo1'>Paso $paso: </span> Se han actualizado los registros de la tabla Salud.TopesCopago </p> ";
		
		
		}
		
		
		
		/* Termina defincion de funciones */
		
		
			
		
			
			
			
			
			
			
			
		
		
		
		
		
		
		
		
	
	
	
	?>
