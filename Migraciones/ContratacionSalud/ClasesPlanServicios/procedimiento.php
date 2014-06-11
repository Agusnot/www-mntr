	<html>	
		<head>
			<title> Migracion ContratacionSalud.ClasesPlanServicios </title>
			<link rel="stylesheet" type="text/css" href="../../General/estilos/estilos.css">
		</head>
	
	
	<?php
		session_start();
		include_once('../General/funciones/funciones.php');
		include_once('../General/funciones/funciones.php');
		
		
		
		
		/* Inicia defincion de funciones */
		
		
			
			function eliminarClasesPlanServicios() {
				$cnx= conectar_postgres();
				$cons= "DELETE FROM ContratacionSalud.ClasesPlanServicios";
				$res = @pg_query($cnx, $cons);
					if (!$res) {
								echo "<p class='error1'> Error de ejecucion </p>".pg_last_error()."<br>";
								echo "<p class= 'subtitulo1'>Comando SQL </p> <br>".$cons."<br/>";
								
					}
				
					
					
			}
			
			
			function actualizarClasesPlanServicios($paso) {
			
				eliminarClasesPlanServicios();
				$compania = $_SESSION["compania"];
				$cnx= conectar_postgres();
				$cons= "INSERT INTO ContratacionSalud.ClasesPlanServicios(compania,nombre) VALUES ('$compania', 'CUPS'), ('$compania', 'Medicamentos')";
				$res = @pg_query($cnx, $cons);
					if (!$res) {
								echo "<p class='error1'> Error de ejecucion </p>".pg_last_error()."<br>";
								echo "<p class= 'subtitulo1'>Comando SQL </p> <br>".$cons."<br/>";
								
					}
					if ($res) {
						echo "<p class='mensajeEjecucion'> <span class = 'subtitulo1'>Paso $paso: </span> Se han actualizado los registros de la tabla ContratacionSalud.ClasesPlanServicios </p> ";
					
					}
				
					
					
			}
			
			
			
			
		
		
		
		
		
		
		
		
	
	
	
	?>
