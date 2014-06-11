<html>	
		<head>
			<title> Reversion Migracion Central.CodProductos (Farmacia)</title>
			<link rel="stylesheet" type="text/css" href="../../General/estilos/estilos.css">
		</head>
		
		<?php
	
			include('../../Conexiones/conexion.php');
			
		/* Inicia la definicion de funciones */
		
			function eliminarTablaMigracion($paso) { 
				// Elimina la tabla  de Migracion
				$cnx= conectar_postgres();
				$cons = "DROP TABLE  IF EXISTS Consumo.CodProductosMigracion2";
				$res =  pg_query($cons);
					if($res) { 
						echo "<p class='mensajeEjecucion'> <span class = 'subtitulo1'>Paso $paso: </span> Se ha eliminado  la tabla Consumo.CodproductosMigracion2 </p> ";
					}	
			
			}
		
			function eliminarRegistrosCodProductos($paso, $esquema, $tabla) {
			
			
			
				$cnx = conectar_postgres();
				$cons = "DELETE FROM  $esquema.$tabla  WHERE almacenppal = 'FARMACIA'";
					if ($_GET['verConsultas'] == 'true') {
						echo "<p class='subtitulo1'>Consulta Paso $paso : </p>";
						echo $cons;
					}
				$res =  pg_query($cons);
					if($res) { 
						echo "<p class='mensajeEjecucion'> <span class = 'subtitulo1'>Paso $paso: </span> Se han eliminado los registros de la tabla $esquema.$tabla  relacionados con el almacen 'FARMACIA'</p> ";
					}			
			}
			
			
			
			function eliminarRegistros($paso, $esquema, $tabla) {
			
			
			
				$cnx = conectar_postgres();
				$cons = "DELETE FROM  $esquema.$tabla ";
					if ($_GET['verConsultas'] == 'true') {
						echo "<p class='subtitulo1'>Consulta Paso $paso : </p>";
						echo $cons;
					}
				$res =  pg_query($cons);
					if($res) { 
						echo "<p class='mensajeEjecucion'> <span class = 'subtitulo1'>Paso $paso: </span> Se han eliminado los registros de la tabla $esquema.$tabla  </p> ";
					}			
			}
			
			
			
	
			
		
		/* Termina la definicion de funciones */
		
		
		/* Inicia la ejecucion de las funciones */
		
		if($_GET['accion']=="revertirMigracion") { 
		
			echo "<fieldset>";			
			echo "<legend> Reversion migracion Consumo.codproductos (Farmacia)</legend>";
			echo "<br>";
			echo "<div > <a href='../../index.php?migracion=MIG006' class= 'link1'> Panel de Administracion </a> </div>";
			
			eliminarTablaMigracion(1);
			eliminarRegistros(2, "Consumo", "tarifasxproducto");
			eliminarRegistros(3, "Consumo", "tarifariosventa");
			eliminarRegistros(4, "Consumo", "cumsxproducto");
			eliminarRegistrosCodProductos(5, "Consumo", "Codproductos");	
			
			
			
			
			echo "<p class='mensajeEjecucion'> <span class = 'error1'>Reversion finalizada :  </span> La reversion del modulo de Farmacia ha finalizado. </p> ";
			
		
		
		
		}
		
		
		
		/* Termina  la ejecucion de las funciones */
		
		
			
		
		
		