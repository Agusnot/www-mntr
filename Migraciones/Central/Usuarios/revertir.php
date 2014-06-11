<html>	
		<head>
			<title> Reversion Migracion Central.Usuarios</title>
			<link rel="stylesheet" type="text/css" href="../../General/estilos/estilos.css">
		</head>
		
		<?php
	
			include('../../Conexiones/conexion.php');
			
		/* Inicia la definicion de funciones */
		
			function eliminarTablaMigracion($paso) {
				// Elimina la tabla Migracion
				$cnx= conectar_postgres();
				$cons = "DROP TABLE  IF EXISTS Central.UsuariosMigracion";
				$res =  pg_query($cons);
					if($res) { 
						echo "<p class='mensajeEjecucion'> <span class = 'subtitulo1'>Paso $paso: </span> Se ha eliminado  la tabla Central.UsuariosMigracion </p> ";
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
			echo "<legend> Reversion migracion Usuarios</legend>";
			echo "<br>";
			echo "<div > <a href='../../index.php?migracion=MIG004' class= 'link1'> Panel de Administracion </a> </div>";
			
			eliminarTablaMigracion(1);
			eliminarRegistros(2, "Central", "Usuariosxmodulos");
			eliminarRegistros(3, "Central", "Usuarios");	
			
			
			
			
			echo "<p class='mensajeEjecucion'> <span class = 'error1'>Reversion finalizada :  </span> La reversion de la tabla Central.Usuarios ha finalizado. </p> ";
			
		
		
		
		}
		
		
		
		/* Termina  la ejecucion de las funciones */
		
		
			
		
		
		