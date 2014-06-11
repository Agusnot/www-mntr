<html>	
		<head>
			<title> Reversion Migracion Central.Terceros</title>
			<link rel="stylesheet" type="text/css" href="../../General/estilos/estilos.css">
		</head>
		
		<?php
	
			include('../../Conexiones/conexion.php');
			
		/* Inicia la definicion de funciones */
		
			function eliminarTablaMigracion($paso) {
				// Elimina la tabla Contabilidad.movimientoMigracion 
				$cnx= conectar_postgres();
				$cons = "DROP TABLE  IF EXISTS Central.TercerosMigracion";
				$res =  pg_query($cons);
					if($res) { 
						echo "<p class='mensajeEjecucion'> <span class = 'subtitulo1'>Paso $paso: </span> Se ha eliminado  la tabla Central.TercerosMigracion </p> ";
					}	
			
			}
		
			function eliminarRegistros($paso, $esquema, $tabla) {
			
			
			
				$cnx = conectar_postgres();
				$cons = "DELETE FROM  $esquema.$tabla";
					if ($_GET['verConsultas'] == 'true') {
						echo "<p class='subtitulo1'>Consulta Paso $paso : </p>";
						echo $cons;
					}
				$res =  pg_query($cons);
					if($res) { 
						echo "<p class='mensajeEjecucion'> <span class = 'subtitulo1'>Paso $paso: </span> Se han eliminado los registros de la tabla $esquema.$tabla </p> ";
					}			
			}
			
			
			 
			 
			
			
			
			
			
			
		
		/* Termina la definicion de funciones */
		
		
		/* Inicia la ejecucion de las funciones */
		
		if($_GET['accion']=="revertirMigracion") { 
		
			echo "<fieldset>";			
			echo "<legend> Reversion migracion Terceros</legend>";
			echo "<br>";
			echo "<div > <a href='../../index.php?migracion=MIG003' class= 'link1'> Panel de Administracion </a> </div>";
			
			eliminarTablaMigracion(1);
			eliminarRegistros(2, "Central", "Terceros");	
			
			
			
			
			echo "<p class='mensajeEjecucion'> <span class = 'error1'>Reversion finalizada :  </span> La reversion de la tabla Central.Terceros ha finalizado. </p> ";
			
		
		
		
		}
		
		
		
		/* Termina  la ejecucion de las funciones */
		
		
			
		
		
		