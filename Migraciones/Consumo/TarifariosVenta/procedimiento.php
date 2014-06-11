	<html>	
		<head>
			<title> Migracion Consumo.TarifariosVenta </title>
			
			<link rel="stylesheet" type="text/css" href="../../General/estilos/estilos.css">
		</head>
	
	
	<?php
		session_start();
		include_once('../../General/funciones/funciones.php');
		
		
		
		
		/* Inicia definicion de funciones */ 
		 
		
		
		function eliminarTarifariosVenta() {
			$cnx= conectar_postgres();
			
			$cons = "DELETE FROM Consumo.TarifariosVenta";
			$res =  pg_query($cnx, $cons);
			$fila = pg_fetch_array($res);
			$res = $fila['conteo'];
			return $res; 	
		
		}
		
		
		
		function insertarTarifariosVenta($compania, $almacenppal) { 
			$cnx= conectar_postgres();
			
			$cons = "INSERT INTO Consumo.TarifariosVenta(Compania, almacenppal, tarifario)VALUES ('$compania', '$almacenppal','FARMAPRECIOS'), ('$compania', '$almacenppal','CODESCA'), ('$compania', '$almacenppal','SALUDTOTAL'), ('$compania', '$almacenppal','EPSIFARMA'), ('$compania', '$almacenppal','NUEVA EPS'), ('$compania', '$almacenppal','INSTITUCIONAL'), ('$compania', '$almacenppal','GRUPO SANITAS') , ('$compania', '$almacenppal','INSTITUCIONAL 2014')";
			$res =  @pg_query($cnx, $cons);
				if (!$res) {
							
							$fp = fopen("../Farmacia/ReporteFarmacia.html", "a+");	
							$errorEjecucion= "<p class='error1'> Error de ejecucion </p>".pg_last_error()."<br>";
							$consulta= "<p class= 'subtitulo1'>Comando SQL </p> <br>".$consUTF8."<br/> <br/> <br/>";  
							fputs($fp, $errorEjecucion);
							fputs($fp, $consulta);
							fclose($fp);
							
						}
			
				
		
		}
		
		
		
		
		
		function actualizarTarifariosVenta() { 
			$cnx= conectar_postgres();
			$usuario = "Admin";
			$fecha = FechaActual();
			$estado = "AC";
			$cons = "UPDATE Consumo.TarifariosVenta SET usuariocre = '$usuario', fechacre = '$fecha', usuaprobado = '$usuario' ,estado = '$estado',  fechaaprobado = '$fecha' ";
			$res =  @pg_query($cnx, $cons);
				if (!$res) {
							
							$fp = fopen("../Farmacia/ReporteFarmacia.html", "a+");	
							$errorEjecucion= "<p class='error1'> Error de ejecucion </p>".pg_last_error()."<br>";
							$consulta= "<p class= 'subtitulo1'>Comando SQL </p> <br>".$consUTF8."<br/> <br/> <br/>";  
							fputs($fp, $errorEjecucion);
							fputs($fp, $consulta);
							fclose($fp);
							
						}
			
				
		
		}
		
		
		function actualizarTarifarios(){
			
			
			$cnx= conectar_postgres();			
			$fecha = fechaActual();
			$cons= "UPDATE Consumo.Tarifariosventa SET estado = 'AC', usuaprobado = 'ADMINISTRADOR', fechaaprobado = '$fecha'";
			$res =  pg_query($cnx, $cons);
		
		}
		
		
		
		function MigrarTarifariosVenta($compania, $almacenppal) {
			eliminarTarifariosVenta();
			insertarTarifariosVenta($compania, $almacenppal);
			actualizarTarifariosVenta();
			actualizarTarifarios();	
		
		}
		
		

		
		
		
		
		
		
		
		
		/* Inicia defincion de funciones */
		
		
		