	<html>	
		<head>
			<title> Migracion ContratacionSalud.PlanesTarifas </title>
			<link rel="stylesheet" type="text/css" href="../General/estilos/estilos.css">
		</head>
	
	
	<?php
		session_start();
		include_once('../General/funciones/funciones.php');
		
		
		
		
		/* Inicia defincion de funciones */
		
			
		function  normalizarCodificacionPlanesTarifas($cadenaBusqueda,$cadenaReemplazo)  {
		// Busca y reemplaza ocurrencias en una tabla
			$cnx= conectar_postgres();
			$cons = "UPDATE ContratacionSalud.PlanesTarifas SET nombreplan = replace( nombreplan,'$cadenaBusqueda','$cadenaReemplazo')";
			
			$res = pg_query($cnx , $cons);
				if (!$res)  {
					echo "<p class='error1'> Error en la normalizacion de la codificacion </p>".pg_last_error()."<br>";
					echo "<p class= 'subtitulo1'>Comando SQL </p> <br>".$cons."<br/> <br/> <br/>";
				}

		}
		
				
	
			function llamarRegistrosMySQLPlanesTarifas() {
			// Selecciona los registros MySQL (Origen)
			global $res;
			$cnx = conectar_mysql("Salud");
			$cons = "SELECT *  FROM Salud.EPS ORDER BY AutoId ASC";
			$res =  mysql_query($cons);
			return $res; 
		
		}
		
		
		function maximoAutoId() {
			// Selecciona los registros MySQL (Origen)
			global $res;
			$cnx = conectar_postgres();
			$cons = "SELECT MAX(AutoId) AS maximoautoid  FROM ContratacionSalud.PlanesTarifas ";
			$res = pg_query($cnx, $cons);
			$fila = pg_fetch_array($res);
			$res = $fila["maximoautoid"];
			return $res;  
		
		}
		
		

		
		function insertarPlanesTarifas($nombreplan, $autoid, $compania, $estado, $usuaprobado, $fechaaprobado) {
		//Realiza la insercion en Postgresql con base en los parametros
			
			$cnx = 	conectar_postgres();
			
			$cons = "INSERT INTO ContratacionSalud.PlanesTarifas ( nombreplan, autoid, compania, estado, usuaprobado, fechaaprobado ) VALUES ( '$nombreplan', '$autoid', '$compania', '$estado', '$usuaprobado', '$fechaaprobado')"	;
					 
			$cons = str_replace( "'NULL'","NULL",$cons  );	
			$res = @pg_query($cnx, $cons);
				if (!$res) {
					$consUTF8 = utf8_encode($cons);
					$resUTF8 = @pg_query($cnx, $consUTF8);					
						if (!$resUTF8) {
							$fp = fopen("Errores/ContratacionSalud.html", "a+");	
							$errorEjecucion= "<p class='error1'> Error de ejecucion </p>".pg_last_error()."<br>";
							$consulta= "<p class= 'subtitulo1'>Comando SQL </p> <br>".$consUTF8."<br/> <br/> <br/>";  
							fputs($fp, $errorEjecucion);
							fputs($fp, $consulta);
							fclose($fp);
							
							
						}
				
				}

				
		}
		
		
		function  llenarMatrizPlanesTarifas(){
		// Llena una matriz con el resultado de la consulta MySQL
			
			unset($matriz); 
			global  $matriz;	
			$res = llamarRegistrosMySQLPlanesTarifas();
			$posicion=0;
				
				while ($fila = mysql_fetch_array($res))
				{	
					
					$matriz["nombreplan"][$posicion] = $fila["Nombre"];
					$matriz["autoid"][$posicion] = $fila["AutoId"];
					
					
													
					$posicion++;				
				}
							
				
		}
		
			
				

			
			function recorrerMatrizPlanesTarifas()  {
			// Recorre la matriz con los valores MySQL  inserta los valores en Postgresql
			
				global $res,$matriz;
					for($pos=0;$pos < mysql_num_rows($res); $pos++)  {

					$nombreplan= $matriz["nombreplan"][$pos] ;	
					$nombreplan = eliminarCaracteresEspeciales($nombreplan)	;
					$autoid = $matriz["autoid"][$pos];
					$autoid = str_replace("A","",$autoid);
					$autoid = str_replace("a","", $autoid);
					$compania = $_SESSION["compania"];
					$estado = "AC";
					$usuaprobado = "Admin";
					$fechaaprobado = FechaActual();
				
					insertarPlanesTarifas($nombreplan, $autoid, $compania, $estado, $usuaprobado, $fechaaprobado);
							
					}
			
			}
			
			function eliminarPlanesTarifas() {
				$cnx= conectar_postgres();
				$cons= "DELETE FROM ContratacionSalud.PlanesTarifas";
				$res = @pg_query($cnx, $cons);
				if (!$res) {
							echo "<p class='error1'> Error de ejecucion </p>".pg_last_error()."<br>";
							echo "<p class= 'subtitulo1'>Comando SQL </p> <br>".$cons."<br/>";
							
						}
					
			}
			
			function insertarRegistroISS2001() {
			$nombreplan = "ISS2001";
			$autoid = maximoAutoId();
			$autoid = $autoid + 1;
			$compania = $_SESSION["compania"];
			$estado = "AC";
			$usuaprobado = "Admin";
			$fechaaprobado = FechaActual();
			insertarPlanesTarifas($nombreplan, $autoid, $compania, $estado, $usuaprobado, $fechaaprobado);
			}
			
			
			function actualizarPlanTarifarioISS2001() {
		
			$cnx = conectar_postgres();
			$autoid = maximoAutoId();
			$autoid = $autoid + 1;
			$cons= "UPDATE ContratacionSalud.PlanesTarifas SET Autoid = '$autoid' WHERE autoid = '2001' ";
			$res =  pg_query($cons);
				if (!$res) {
				echo  "<p class='error1'> Error de ejecucion </p>".pg_last_error()."<br>";
				echo  "<p class= 'subtitulo1'>Comando SQL </p> <br>".$consUTF8."<br/> <br/> <br/>";
				
				}
		
			}
			
			
			
			
			
			
			
		
		
		function migrarPlanesTarifas($paso) {
		
			eliminarPlanesTarifas();
			llamarRegistrosMySQLPlanesTarifas();
			llenarMatrizPlanesTarifas();
			recorrerMatrizPlanesTarifas();
			insertarRegistroISS2001();
			normalizarCodificacionPlanesTarifas('&Aacute;', utf8_encode("Á"));			
			normalizarCodificacionPlanesTarifas('&Eacute;', utf8_encode("É"));
			normalizarCodificacionPlanesTarifas('&Iacute;', utf8_encode("Í"));
			normalizarCodificacionPlanesTarifas('&Oacute;', utf8_encode("Ó"));
			normalizarCodificacionPlanesTarifas('&Uacute;',utf8_encode("Ú"));
			normalizarCodificacionPlanesTarifas('&Ntilde;',utf8_encode("Ñ"));
			echo "<p class='mensajeEjecucion'> <span class = 'subtitulo1'>Paso $paso: </span> Se ha migrado la tabla ContratacionSalud.PlanesTarifas </p> ";
	
		}
		
		
		
		
		
	
	
	
	?>
