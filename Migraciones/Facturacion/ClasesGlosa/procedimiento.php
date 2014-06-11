	<html>	
		<head>
			<title> Migracion Facturacion.ClasesGlosa </title>
			<link rel="stylesheet" type="text/css" href="../General/estilos/estilos.css">
		</head>
	
	
	<?php
		session_start();
		include_once('../General/funciones/funciones.php');
		
		
		
		
		/* Inicia definicion de funciones */			
	

		
		function insertarClasesGlosa($compania) {
		//Realiza la insercion en Postgresql con base en los parametros
			
			$cnx = 	conectar_postgres();
			$cons = "INSERT INTO Facturacion.ClasesGlosa ( compania, claseglosa) VALUES ('$compania', 'ADMINISTRATIVA'), ('$compania', 'ASISTENCIAL')";
					 
			$cons = str_replace( "'NULL'","NULL",$cons  );	
			$res = @pg_query($cnx, $cons);
				if (!$res) {
							echo  "<p class='error1'> Error de ejecucion </p>".pg_last_error()."<br>";
							echo "<p class= 'subtitulo1'>Comando SQL </p> <br>".$cons."<br/> <br/> <br/>";  
				}

		}
		
		
		function eliminarClasesGlosa() {
				$cnx= conectar_postgres();
				$cons= "DELETE FROM Facturacion.ClasesGlosa";
				$res = @pg_query($cnx, $cons);
				if (!$res) {
							echo "<p class='error1'> Error de ejecucion </p>".pg_last_error()."<br>";
							echo "<p class= 'subtitulo1'>Comando SQL </p> <br>".$cons."<br/>";
							
						}
					
		}
		
		
		function migrarClasesGlosa($paso) {
		
			eliminarClasesGlosa();
			$compania= $_SESSION["compania"];
			insertarClasesGlosa($compania);
			
			echo "<p class='mensajeEjecucion'> <span class = 'subtitulo1'>Paso $paso: </span> Se ha migrado la tabla Facturacion.ClasesGlosa </p> ";
	
		}
		
		
		
		
		
	
	
	
	?>
