	<html>	
		<head>
			<title> Migracion Central.ListaSexo </title>
			<link rel="stylesheet" type="text/css" href="../General/estilos/estilos.css">
		</head>
	
	
	<?php
		
		
		
		
		
		/* Inicia defincion de funciones */
		
		
		function eliminarListaSexo() {
		
			$cnx = conectar_postgres();
			$cons = "DELETE FROM Central.listasexo";
			$res =  pg_query($cnx, $cons);
			
			
		}
		
		
		function insertarListaSexo() {
		
			$cnx = conectar_postgres();
			$cons = "INSERT INTO central.listasexo(sexo, codigo) VALUES ('FEMENINO','F'), ('MASCULINO','M');";				
			$res =  pg_query($cnx, $cons);
			
			
		}
		
		
		/* Termina defincion de funciones */
		
					
			
		
		
		function migrarListaSexo($paso) {
		
			eliminarListaSexo();
			insertarListaSexo();
			echo "<p class='mensajeEjecucion'> <span class = 'subtitulo1'>Paso $paso: </span> Se ha migrado la tabla Central.ListaSexo </p> ";
	
		}
		
		
		
		
		
	
	
	
	?>
