	<html>	
		<head>
			<title> Migracion ContratacionSalud.PlaneServicios </title>
			<link rel="stylesheet" type="text/css" href="../General/estilos/estilos.css">
		</head>
	
	
	<?php
		session_start();
		include_once('../General/funciones/funciones.php');
		
		
		
		
		/* Inicia defincion de funciones */
		
			
		function  normalizarCodificacionPlaneServicios($cadenaBusqueda,$cadenaReemplazo)  {
		// Busca y reemplaza ocurrencias en una tabla
			$cnx= conectar_postgres();
			$cons = "UPDATE ContratacionSalud.PlaneServicios SET nombreplan = replace( nombreplan,'$cadenaBusqueda','$cadenaReemplazo')";
			
			$res = pg_query($cnx , $cons);
				if (!$res)  {
					echo "<p class='error1'> Error en la normalizacion de la codificacion </p>".pg_last_error()."<br>";
					echo  "<p class= 'subtitulo1'>Comando SQL </p> <br>".$consUTF8."<br/> <br/> <br/>";  
				}

		}
		
				
	
		function llamarRegistrosMySQLPlaneServicios() {
			// Selecciona los registros MySQL (Origen)
			global $res;
			$cnx = conectar_mysql("Salud");
			$cons = "SELECT *  FROM Salud.EPS ORDER BY AutoId ASC";
			$res =  mysql_query($cons);
			return $res; 
		
		}
		
		

		
		function insertarPlaneServicios($nombreplan, $autoid, $compania, $ambito, $clase) {
		//Realiza la insercion en Postgresql con base en los parametros
			
			$cnx = 	conectar_postgres();
			
			$cons = "INSERT INTO ContratacionSalud.PlaneServicios ( nombreplan, autoid, compania, ambito, clase ) VALUES ( '$nombreplan', '$autoid', '$compania', '$ambito', '$clase')"	;
					 
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
		
		
		function  llenarMatrizPlaneServicios(){
		// Llena una matriz con el resultado de la consulta MySQL
			
			unset($matriz); 
			global  $matriz;	
			$res = llamarRegistrosMySQLPlaneServicios();
			$posicion=0;
				
				while ($fila = mysql_fetch_array($res))
				{	
					
					$matriz["nombreplan"][$posicion] = $fila["Nombre"];
					$matriz["autoid"][$posicion] = $fila["AutoId"];
					
					
													
					$posicion++;				
				}
							
				
		}
		
			
				

			
			function recorrerMatrizPlaneServicios()  {
			// Recorre la matriz con los valores MySQL  inserta los valores en Postgresql
			
				global $res,$matriz;
					for($pos=0;$pos < mysql_num_rows($res); $pos++)  {

					$nombreplan= $matriz["nombreplan"][$pos] ;	
					$nombreplan = eliminarCaracteresEspeciales($nombreplan)	;
					$autoid = $matriz["autoid"][$pos];
					$autoid = str_replace("A","",$autoid);
					$autoid = str_replace("a","", $autoid);
					$compania = $_SESSION["compania"];
					$ambito = "";
					$clase = "CUPS";
				
					insertarPlaneServicios($nombreplan, $autoid, $compania, $ambito, $clase);
							
					}
			
			}
			
			function eliminarPlaneServicios() {
				$cnx= conectar_postgres();
				$cons= "DELETE FROM ContratacionSalud.PlaneServicios";
				$res = @pg_query($cnx, $cons);
				if (!$res) {
							echo "<p class='error1'> Error de ejecucion </p>".pg_last_error()."<br>";
							echo "<p class= 'subtitulo1'>Comando SQL </p> <br>".$cons."<br/>";
							
						}
					
			}
			
			
			
			
			
			
		
		
		function migrarPlaneServicios($paso) {
			$compania = $_SESSION["compania"];
			eliminarPlaneServicios();
			llamarRegistrosMySQLPlaneServicios();
			llenarMatrizPlaneServicios();
			recorrerMatrizPlaneServicios();
			insertarPlaneServicios("PLAN GENERAL MEDICAMENTOS", 111, $compania,'NULL', 'Medicamentos');
			normalizarCodificacionPlaneServicios('&Aacute;', utf8_encode("Á"));			
			normalizarCodificacionPlaneServicios('&Eacute;', utf8_encode("É"));
			normalizarCodificacionPlaneServicios('&Iacute;', utf8_encode("Í"));
			normalizarCodificacionPlaneServicios('&Oacute;', utf8_encode("Ó"));
			normalizarCodificacionPlaneServicios('&Uacute;',utf8_encode("Ú"));
			normalizarCodificacionPlaneServicios('&Ntilde;',utf8_encode("Ñ"));
			echo "<p class='mensajeEjecucion'> <span class = 'subtitulo1'>Paso $paso: </span> Se ha migrado la tabla ContratacionSalud.PlaneServicios </p> ";
	
		}
		
		
		
		
		
	
	
	
	?>
