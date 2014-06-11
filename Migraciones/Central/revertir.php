<html>	
		<head>
			<title> Reversion Migracion Esquema Central </title>
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
			echo "<legend> Reversion migracion Esquema Central</legend>";
			echo "<br>";
			echo "<div > <a href='../index.php?migracion=MIG002' class= 'link1'> Panel de Administracion </a> </div>";
			
			eliminarRegistros(1, "Central", "Cesantias");	
			eliminarRegistros(2, "Central", "CierrexPeriodos");
			eliminarRegistros(3, "Central", "EntidadesBancarias");	
			eliminarRegistros(4, "Central", "EPS");	
			eliminarRegistros(5, "Central", "CentrosCosto");
			
			
			
			echo "<p class='mensajeEjecucion'> <span class = 'error1'>Reversion finalizada :  </span> La reversion del  Esquema Central ha finalizado. </p> ";
			
		
		
		
		}
		
		
		
		/* Termina  la ejecucion de las funciones */
		
		
			
		
		
		