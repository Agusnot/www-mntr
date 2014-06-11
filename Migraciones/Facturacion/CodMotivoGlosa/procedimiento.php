	<html>	
		<head>
			<title> Migracion Facturacion.CodMotivoGlosa </title>
			<link rel="stylesheet" type="text/css" href="../General/estilos/estilos.css">
		</head>
	
	
	<?php
		session_start();
		include_once('../General/funciones/funciones.php');
		
		
		
		
		/* Inicia definicion de funciones */			
	

		
		function actualizarCodMotivoGlosa($compania) {
		//Realiza la insercion en Postgresql con base en los parametros
			
			$cnx = 	conectar_postgres();
			$cons = "UPDATE Facturacion.CodMotivoGlosa SET compania = '$compania', detalle = upper(detalle)";
					 
			$res = @pg_query($cnx, $cons);
				if (!$res) {
							echo  "<p class='error1'> Error de ejecucion </p>".pg_last_error()."<br>";
							echo "<p class= 'subtitulo1'>Comando SQL </p> <br>".$cons."<br/> <br/> <br/>";  
				}

		}
		
		
		
		
		
		function migrarCodMotivoGlosa($paso) {
		
			
			$compania= $_SESSION["compania"];
			actualizarCodMotivoGlosa($compania);
			
			echo "<p class='mensajeEjecucion'> <span class = 'subtitulo1'>Paso $paso: </span> Se ha actualizado la tabla Facturacion.CodMotivoGlosa </p> ";
	
		}
		
		
		
		
		
	
	
	
	?>
