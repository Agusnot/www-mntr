<html>	
		<head>
			<title> Reversion Migracion Esquema Contratacion Salud </title>
			<link rel="stylesheet" type="text/css" href="../General/estilos/estilos.css">
		</head>
		
		<?php
	
			include('../Conexiones/conexion.php');
			
		/* Inicia la definicion de funciones */
		
			function eliminarRegistros($paso, $esquema, $tabla) {
			
			
			
				$cnx = conectar_postgres();
				$cons = "DELETE FROM  $esquema.$tabla";
					if ($_GET['verConsultas'] == 'true') {
						echo "<p class='subtitulo1'>Consulta Paso $paso : </p>";
						echo $cons;
					}
				$res =  pg_query($cons);
				echo "<p class='mensajeEjecucion'> <span class = 'subtitulo1'>Paso $paso: </span> Se han eliminado los registros de la tabla $esquema.$tabla </p> ";
							
			}
			
			
			
			
			
		
		/* Termina la definicion de funciones */
		
		
		/* Inicia la ejecucion de las funciones */
		
		if($_GET['accion']=="revertirMigracion") {
		
			echo "<fieldset>";			
			echo "<legend> Reversion migracion Esquema Contratacion Salud</legend>";
			echo "<br>";
			echo "<div > <a href='../index.php?migracion=MIG015' class= 'link1'> Panel de Administracion </a> </div>";
			
			
			eliminarRegistros(1, "Salud", "Pabellones");
			eliminarRegistros(2, "Salud", "Cargos");	
			eliminarRegistros(3, "Salud", "Ambitos");				
			eliminarRegistros(4, "Salud", "Medicos");
			eliminarRegistros(5, "Salud", "MotivoSalida");
										
			
			echo "<p class='mensajeEjecucion'> <span class = 'error1'>Reversion finalizada :  </span> La reversion del  Esquema Contratacion Salud  ha finalizado. </p> ";
			
		
		
		
		}
		
		
		
		/* Termina  la ejecucion de las funciones */
		
		
			
		
		
		