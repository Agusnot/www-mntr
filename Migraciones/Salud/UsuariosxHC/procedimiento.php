	<html>	
		<head>
			<title> Migracion Salud.UsuariosxHC </title>
			<link rel="stylesheet" type="text/css" href="../General/estilos/estilos.css">
		</head>
	
	
	<?php
		session_start();
		include_once('../General/funciones/funciones.php');
		
		
		
		
		/* Inicia defincion de funciones */
		
		
		function eliminarUsuariosxHC(){
		
		$cnx = 	conectar_postgres();
		$cons = "DELETE FROM Salud.UsuariosxHC WHERE usuario <> 'Admin' "	;
					 
			
			$res = @pg_query($cnx, $cons);
				if (!$res) {
					 	echo "<p class='error1'> Error de ejecucion </p>".pg_last_error()."<br>";
						echo "<p class= 'subtitulo1'>Comando SQL </p> <br>".$consUTF8."<br/> <br/> <br/>";  
				
						}
		
		}
		
		
		
		
		
		function migrarUsuariosxHC($paso){		
			
		eliminarUsuariosxHC();	
		
		echo "<p class='mensajeEjecucion'> <span class = 'subtitulo1'>Paso $paso: </span> Se han actualizado los registros de la tabla Salud.UsuariosxHC </p> ";
			
		
		}
		
		
		
		
		
	
	
	
	?>
