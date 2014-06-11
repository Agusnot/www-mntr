	<html>	
		<head>
			<title> Migracion Central.Impactos </title>
			<link rel="stylesheet" type="text/css" href="../General/estilos/estilos.css">
		</head>
	
	
	<?php
		
		
		
		
		
		/* Inicia defincion de funciones */
		
		
		function eliminarImpactos() {
		
			$cnx = conectar_postgres();
			$cons = "DELETE FROM Central.impactos";
			$res =  pg_query($cnx, $cons);
			
			
		}
		
		
		function insertarImpactos() {
		
			$cnx = conectar_postgres();
			$cons = "INSERT INTO central.impactos(nombre) VALUES ('ALTO') , ('MEDIO'), ('BAJO');";				
			$res =  pg_query($cnx, $cons);
			
			
		}
		
		
		/* Termina defincion de funciones */
		
					
			
		
		
		function migrarImpactos($paso) {
		
			eliminarImpactos();
			insertarImpactos();
			echo "<p class='mensajeEjecucion'> <span class = 'subtitulo1'>Paso $paso: </span> Se ha migrado la tabla Central.Impactos </p> ";
	
		}
		
		
		
		
		
	
	
	
	?>
