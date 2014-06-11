	<html>	
		<head>
			<title> Migracion Central.Estilos </title>
			<link rel="stylesheet" type="text/css" href="../General/estilos/estilos.css">
		</head>
	
	
	<?php
		
		
		
		
		
		/* Inicia defincion de funciones */
		
		
		function eliminarEstilos() {
		
			$cnx = conectar_postgres();
			$cons = "DELETE FROM Central.estilos";
				if ($_GET['verConsultas'] == 'true') {
						echo "<p class='subtitulo1'>Consulta Paso $paso : </p>";
						echo $cons;
					}
			$res =  pg_query($cnx, $cons);
			
			
		}
		
		
		function insertarEstilos() {
		
			$cnx = conectar_postgres();
			$cons = "INSERT INTO central.estilos (NomEstilo, BAColorFon, BAColorLet, BATipoLet, BATamLet, BAEstLet, BBColorFon, BBColorLet, BBTipoLet, BBTamLet,BBEstLet
)  VALUES ( 'Estandar' ,'#666699' ,'#ffffff' ,'Tahoma' ,'11' ,'Normal' ,'#ffffff' ,'#000000' ,'Tahoma' ,'11' ,'Normal');";
			$res =  pg_query($cnx, $cons);
			
			
		}
		
		
		/* Termina defincion de funciones */
		
					
			
		
		
		function migrarEstilos($paso) {
		
			eliminarEstilos();
			insertarEstilos();
			echo "<p class='mensajeEjecucion'> <span class = 'subtitulo1'>Paso $paso: </span> Se ha migrado la tabla Central.Estilos </p> ";
	
		}
		
		
		
		
		
	
	
	
	?>
