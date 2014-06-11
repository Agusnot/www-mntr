	<html>	
		<head>
			<title> Migracion Consumo.TiposComprobante </title>
			
			<link rel="stylesheet" type="text/css" href="../General/estilos/estilos.css">
		</head>
	
	
	<?php
		session_start();
		
				
		
		/* Inicia definicion de funciones */
		
		
		
		function eliminarTiposComprobante(){
			$cnx = conectar_postgres();
			$cons = "DELETE FROM Consumo.TiposComprobante";
			$res = @pg_query($cnx, $cons);
				if (!$res) {
					echo "<p class='error1'> Error de ejecucion </p>".pg_last_error()."<br>";
					echo "<p class= 'subtitulo1'>Comando SQL </p> <br>".$cons."<br>"; 	
				}
		
		
		}
		
		
	
		
		function actualizarTiposComprobante($paso)
		
		{	
			eliminarTiposComprobante();
			$cnx = conectar_postgres();
			$cons= "INSERT INTO Consumo.TiposComprobante(tipo) VALUES ('Orden de Compra'), ('Devoluciones'), ('Entradas'), ('Ingreso Ajuste'), ('Remisiones'), ('Salida Ajuste'), ('Salidas'), ('Traslados')";
			$res = pg_query($cnx, $cons);
				if ($res) {
					echo "<p class='mensajeEjecucion'> <span class = 'subtitulo1'>Paso $paso: </span> Se ha  actualizado la tabla Consumo.TiposComprobante </p> ";
				}				
				
				if (!$res) {
					echo "<p class='error1'> Error de ejecucion </p>".pg_last_error()."<br>";
					echo "<p class= 'subtitulo1'>Comando SQL </p> <br>".$cons."<br>"; 	
				}
		
		}
		
		
		function migrarTiposComprobante($paso){
			eliminarTiposComprobante();
			insertarTiposComprobante($paso);
		
		
		}
		
		
		
		
		
		
		/* Inicia defincion de funciones */
		
		
		