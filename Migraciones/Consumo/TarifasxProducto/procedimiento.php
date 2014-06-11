	<html>	
		<head>
			<title> Migracion Consumo.TarifasxProducto</title>
			
			<link rel="stylesheet" type="text/css" href="../General/estilos/estilos.css">
		</head>
	
	
	<?php
		session_start();
		
		
		
		
		/* Inicia definicion de funciones */  
		
		
		function eliminarTarifasxProducto() {
			$cnx= conectar_postgres();
			
			$cons = "DELETE FROM Consumo.TarifasxProducto ";
			$res =  pg_query($cnx, $cons);
			$fila = pg_fetch_array($res);
			$res = $fila['conteo'];
			return $res; 	
		
		}
		
		
		
		function insertarTarifasxProducto($compania, $almacenppal, $tarifario, $autoid, $fechaini, $fechafin, $valorventa, $anio) {
			$cnx= conectar_postgres();
			
			$cons = "INSERT INTO Consumo.TarifasxProducto(compania, almacenppal, tarifario, autoid, fechaini, fechafin, valorventa, anio)VALUES ('$compania', '$almacenppal', '$tarifario', '$autoid', '$fechaini', '$fechafin', $valorventa, $anio)";
			$res =  @pg_query($cnx, $cons);
				if(!$res) {
					$fp = fopen("../Farmacia/ReporteFarmacia.html", "a+");	
					$errorEjecucion= "<p class='error1'> Error de ejecucion </p>".pg_last_error()."<br>";
					$consulta= "<p class= 'subtitulo1'>Comando SQL </p> <br>".$cons."<br/> <br/> <br/>";  
					fputs($fp, $errorEjecucion);
					fputs($fp, $consulta);
					fclose($fp);
				}
			
			$res = $fila['conteo'];
			return $res; 	
		
		}
		
		
		
		
		

		
		
		
		
		
		
		
		
		/* Inicia defincion de funciones */
		
		
		