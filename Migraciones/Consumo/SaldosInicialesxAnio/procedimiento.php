	<html>	
		<head>
			<title> Migracion Consumo.SaldosInicialesxAnio </title>
			
			<link rel="stylesheet" type="text/css" href="../../General/estilos/estilos.css">
		</head>
	
	
	<?php
		session_start();
		
		
		
		
		/* Inicia definicion de funciones */  
		
		
		function eliminarSaldosInicialesxAnio() {
			$cnx= conectar_postgres();
			
			$cons = "DELETE FROM Consumo.SaldosInicialesxAnio";
			$res =  @pg_query($cnx, $cons);
				if (!$res) {
					echo "<p class='error1'> Error de ejecucion </p>".pg_last_error()."<br>";
					echo "<p class= 'subtitulo1'>Comando SQL </p> <br>".$cons."<br>"; 
				}	
			
		
		}
		
		
		
		function insertarSaldosInicialesxAnio($compania, $almacenppal, $autoid, $anio, $cantidad, $vrunidad, $vrtotal) {
			$cnx= conectar_postgres();
			
			$cons = "INSERT INTO Consumo.SaldosInicialesxAnio(compania, almacenppal, autoid, anio, cantidad, vrunidad, vrtotal)VALUES ( '$compania', '$almacenppal', $autoid, $anio, $cantidad, $vrunidad, $vrtotal )";
			$res =  @pg_query($cnx, $cons);
				if (!$res) {
					echo "<p class='error1'> Error de ejecucion </p>".pg_last_error()."<br>";
					echo "<p class= 'subtitulo1'>Comando SQL </p> <br>".$cons."<br>"; 
				}	
			 	
		
		}
		
		
	
		

		
		
		
		
		
		
		
		
		/* Inicia defincion de funciones */
		
		
		