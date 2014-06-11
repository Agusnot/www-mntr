	<html>	
		<head>
			<title> Migracion ContratacionSalud.ClasesPlanServicios </title>
			<link rel="stylesheet" type="text/css" href="../../General/estilos/estilos.css">
		</head>
	
	
	<?php
		session_start();
		include_once('../General/funciones/funciones.php');
		include_once('../General/funciones/funciones.php');
		
		
		
		
		/* Inicia defincion de funciones */
		
		
			
		
			
			
			function actualizarCUPS($paso) {
			
				$compania = $_SESSION["compania"];
				$cnx= conectar_postgres();
				$cons= "UPDATE ContratacionSalud.CUPS SET compania = '$compania', nombre = upper(nombre), ambitocup = 'Recuperacion', causaexternacup = '13'";
				$res = @pg_query($cnx, $cons);
					if (!$res) {
								echo "<p class='error1'> Error de ejecucion </p>".pg_last_error()."<br>";
								echo "<p class= 'subtitulo1'>Comando SQL </p> <br>".$cons."<br/>";
								
					}
					if ($res) {
						echo "<p class='mensajeEjecucion'> <span class = 'subtitulo1'>Paso $paso: </span> Se han actualizado los registros de la tabla ContratacionSalud.CUPS </p> ";
					
					}
			
			}
			
			
			function crearArchivoCupsFaltantes() {
		// Crea un archivo HTML donde se documentaran los registros que no se insertaron en la tabla de migraciones
			$fp = fopen("Errores/CUPSFaltantes.html", "w+");
			$encabezado = "<html> <head> <title> Reporte CUPS por configurar </title> 
			<link rel='stylesheet' type='text/css' href='../../General/estilos/estilos.css'> </head>";
			fputs($fp, $encabezado);
			fclose($fp);
		}
			
			
			function calcularCUPSFaltantes() {
			
			$cnx = conectar_postgres();
			$cons = "SELECT DISTINCT(cup)  FROM ContratacionSalud.CUPSXPlanes WHERE cup NOT IN (SELECT DISTINCT(codigo) FROM ContratacionSalud.CUPS)";
			$res = pg_query($cnx, $cons);
				if (!$res) {
						echo "<p class='error1'> Error de ejecucion </p>".pg_last_error()."<br>";
						echo "<p class= 'subtitulo1'>Comando SQL </p> <br>".$cons."<br/>";
								
					}
			return $res;		
			
			} 
			
			
			function registrarCupsFaltantes() {
			
			$resultado = calcularCUPSFaltantes();
			$pos = 0;

				while ($registro= pg_fetch_array($resultado) ){
				
					$fp = fopen("Errores/CUPSFaltantes.html", "a+");	
					$mensaje= "<p class='error1'> CUP:   </p>".$registro["cup"]."<br>";
					fputs($fp, $mensaje);
					fclose($fp);
					$pos++;
			
				}


			}
			
			
			
			
		
		
		
		
		
		
		
		
	
	
	
	?>
