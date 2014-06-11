	<html>	
		<head>
			<title> Migracion Contabilidad.BasesRetencion </title>
			<link rel="stylesheet" type="text/css" href="../General/estilos/estilos.css">
		</head>
	
	
	<?php
		session_start();
		include_once('../General/funciones/funciones.php');
		
		
		
		
		/* Inicia defincion de funciones */
		
			
		function  normalizarCodificacionBasesRetencion($cadenaBusqueda,$cadenaReemplazo)  {
		// Busca y reemplaza ocurrencias en una tabla
			$cnx= conectar_postgres();
			$cons = "UPDATE Contabilidad.BasesRetencion SET compania = replace( compania,'$cadenaBusqueda','$cadenaReemplazo'), concepto = replace( concepto,'$cadenaBusqueda','$cadenaReemplazo')";
			
			$res = pg_query($cnx , $cons);
				if (!$res)  {
					echo "<p class='error1'> Error en la normalizacion de la codificacion </p>".pg_last_error()."<br>";
				}

		}
		
				
	
		function llamarRegistrosMySQLBasesRetencion() {
			// Selecciona los registros MySQL (Origen)
			global $res;
			$cnx = conectar_mysql("Contabilidad");
			$cons = "SELECT *  FROM Contabilidad.BasesRetencion";
			$res =  mysql_query($cons);
			return $res; 
		
		}
		
		

		
		function insertarBasesRetencion($compania, $concepto, $porcentaje, $base, $cuenta, $montominimo, $iva, $anio) {
		//Realiza la insercion en Postgresql con base en los parametros
			
			$cnx = 	conectar_postgres();
			$cons = "INSERT INTO Contabilidad.BasesRetencion ( compania, concepto, porcentaje, base, cuenta, montominimo, iva, anio ) VALUES ('$compania', '$concepto', $porcentaje, $base, '$cuenta', $montominimo, $iva, $anio)"	;
					 
			$cons = str_replace( "'NULL'","NULL",$cons  );	
			$res = @pg_query($cnx, $cons);
				if (!$res) {
					$consUTF8 = utf8_encode($cons);
					$resUTF8 = @pg_query($cnx, $consUTF8);					
						if (!$resUTF8) {
							$fp = fopen("Errores/ReporteContabilidad.html", "a+");	
							$errorEjecucion= "<p class='error1'> Error de ejecucion </p>".pg_last_error()."<br>";
							$consulta= "<p class= 'subtitulo1'>Comando SQL </p> <br>".$consUTF8."<br/> <br/> <br/>";  
							fputs($fp, $errorEjecucion);
							fputs($fp, $consulta);
							fclose($fp);
							
							
						}
				
				}

				
		}
		
		
		function  llenarMatrizBasesRetencion(){
		// Llena una matriz con el resultado de la consulta MySQL
			
			unset($matriz); 
			global  $matriz;	
			$res = llamarRegistrosMySQLBasesRetencion();
			$posicion=0;
				
				while ($fila = mysql_fetch_array($res))
				{	
					
					$matriz["compania"][$posicion] = $fila["Compania"];
					$matriz["concepto"][$posicion] = $fila["Concepto"];
					$matriz["porcentaje"][$posicion] = $fila["Porcentaje"];										
					$matriz["base"][$posicion] = $fila["Base"];										
					$matriz["cuenta"][$posicion] = $fila["Cuenta"];	
					$matriz["montominimo"][$posicion] = $fila["MontoMinimo"];																			
					$matriz["iva"][$posicion] = $fila["IVA"];										
					$matriz["anio"][$posicion] = $fila["Anio"];										
					$posicion++;				
				}
							
				
			}
			

			
			function recorrerMatrizBasesRetencion()  {
			// Recorre la matriz con los valores MySQL  inserta los valores en Postgresql
			
				global $res,$matriz;
					for($pos=0;$pos < mysql_num_rows($res); $pos++)  {

					$compania= $_SESSION["compania"];
					$concepto= $matriz["concepto"][$pos] ;
					$concepto = eliminarCaracteresEspeciales($concepto);
					$porcentaje= $matriz["porcentaje"][$pos] ;
					$base= $matriz["base"][$pos] ;
					$cuenta= $matriz["cuenta"][$pos] ;
					$montominimo= $matriz["montominimo"][$pos] ;
					$iva= $matriz["iva"][$pos] ;
					$anio= $matriz["anio"][$pos] ;
										
					insertarBasesRetencion($compania, $concepto, $porcentaje, $base, $cuenta, $montominimo, $iva, $anio);
					
									
					}
			
			}
			
			function eliminarBasesRetencion() {
				$cnx= conectar_postgres();
				$cons= "DELETE FROM Contabilidad.BasesRetencion";
				$res = @pg_query($cnx, $cons);
				if (!$res) {
							echo "<p class='error1'> Error de ejecucion </p>".pg_last_error()."<br>";
							echo "<p class= 'subtitulo1'>Comando SQL </p> <br>".$cons."<br/>";
							
						}
					
			}
			
			
			function actualizarBasesRetencion() {
				$cnx= conectar_postgres();
				$cons= "UPDATE Contabilidad.BasesRetencion SET tiporetencion = 'BASES DE RETENCION'";
				$res = @pg_query($cnx, $cons);
				if (!$res) {
							echo "<p class='error1'> Error de ejecucion </p>".pg_last_error()."<br>";
							echo "<p class= 'subtitulo1'>Comando SQL </p> <br>".$cons."<br/>";
							
						}
					
			}
			
			
			
			
		
		
		function migrarBasesRetencion($paso) {
		
			eliminarBasesRetencion();
			llamarRegistrosMySQLBasesRetencion();
			llenarMatrizBasesRetencion();
			recorrerMatrizBasesRetencion();
			actualizarBasesRetencion();
			normalizarCodificacionBasesRetencion('&Aacute;', utf8_encode("Á"));			
			normalizarCodificacionBasesRetencion('&Eacute;', utf8_encode("É"));
			normalizarCodificacionBasesRetencion('&Iacute;', utf8_encode("Í"));
			normalizarCodificacionBasesRetencion('&Oacute;', utf8_encode("Ó"));
			normalizarCodificacionBasesRetencion('&Uacute;',utf8_encode("Ú"));
			normalizarCodificacionBasesRetencion('&Ntilde;',utf8_encode("Ñ"));
			
			echo "<p class='mensajeEjecucion'> <span class = 'subtitulo1'>Paso $paso: </span> Se ha migrado la tabla Contabilidad.BasesRetencion </p> ";
	
		}
		
		
		
		
		
	
	
	
	?>
