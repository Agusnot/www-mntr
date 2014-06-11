	<html>	
		<head>
			<title> Migracion Contabilidad.ConceptosPago </title>
			<link rel="stylesheet" type="text/css" href="../General/estilos/estilos.css">
		</head>
	
	
	<?php
		session_start();
		include_once('../General/funciones/funciones.php');
		
		
		
		
		/* Inicia defincion de funciones */
		
			
		function  normalizarCodificacionComp2($cadenaBusqueda,$cadenaReemplazo)  {
		// Busca y reemplaza ocurrencias en una tabla
			$cnx= conectar_postgres();
			$cons = "UPDATE Contabilidad.Comprobantes SET comprobante = replace(comprobante ,'$cadenaBusqueda','$cadenaReemplazo'), tipocomprobant = replace( tipocomprobant ,'$cadenaBusqueda','$cadenaReemplazo'), numeroinicial = replace( numeroinicial ,'$cadenaBusqueda','$cadenaReemplazo'), formato = replace( formato ,'$cadenaBusqueda','$cadenaReemplazo'), compania = replace( compania ,'$cadenaBusqueda','$cadenaReemplazo'), comppresupuesto = replace( comppresupuesto ,'$cadenaBusqueda','$cadenaReemplazo'), comppresupuestoadc = replace( comppresupuestoadc ,'$cadenaBusqueda','$cadenaReemplazo'),  cierre = replace( cierre ,'$cadenaBusqueda','$cadenaReemplazo'),  formatoadc = replace( formatoadc ,'$cadenaBusqueda','$cadenaReemplazo')";
			
			$res = pg_query($cnx , $cons);
				if (!$res)  {
					echo "<p class='error1'> Error en la normalizacion de la codificacion </p>".pg_last_error()."<br>";
					echo "<p class= 'subtitulo1'>Comando SQL </p> <br>".$cons."<br/> <br/> <br/>";
				}

		}	
			
			
			
		function  normalizarCodificacionConceptosPago($cadenaBusqueda,$cadenaReemplazo)  {
		// Busca y reemplaza ocurrencias en una tabla
			$cnx= conectar_postgres();
			$cons = "UPDATE Contabilidad.ConceptosPago SET compania = replace( compania,'$cadenaBusqueda','$cadenaReemplazo'), concepto = replace( concepto,'$cadenaBusqueda','$cadenaReemplazo'), cuentadebe = replace( cuentadebe ,'$cadenaBusqueda','$cadenaReemplazo'), cuentahaber = replace( cuentahaber,'$cadenaBusqueda','$cadenaReemplazo'), comprobante = replace( comprobante,'$cadenaBusqueda','$cadenaReemplazo')";
			
			$res = pg_query($cnx , $cons);
				if (!$res)  {
					echo "<p class='error1'> Error en la normalizacion de la codificacion </p>".pg_last_error()."<br>";
				}

		}
		
				
	
		function llamarRegistrosMySQLConceptosPago() {
			// Selecciona los registros MySQL (Origen)
			global $res;
			$cnx = conectar_mysql("Contabilidad");
			$cons = "SELECT *  FROM Contabilidad.ConceptosPago";
			$res =  mysql_query($cons);
			return $res; 
		
		}
		
		

		
		function insertarConceptosPago($compania, $concepto, $cuentadebe, $cuentahaber, $comprobante, $anio) {
		//Realiza la insercion en Postgresql con base en los parametros
			
			$cnx = 	conectar_postgres();
			$cons = "INSERT INTO Contabilidad.ConceptosPago ( compania, concepto, cuentadebe, cuentahaber, comprobante, anio ) VALUES ('$compania', '$concepto', '$cuentadebe', '$cuentahaber', '$comprobante', $anio)"	;
					 
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
		
		
		function  llenarMatrizConceptosPago(){
		// Llena una matriz con el resultado de la consulta MySQL
			
			unset($matriz); 
			global  $matriz;	
			$res = llamarRegistrosMySQLConceptosPago();
			$posicion=0;
				
				while ($fila = mysql_fetch_array($res))
				{	
					
					$matriz["concepto"][$posicion] = $fila["Concepto"];
					$matriz["cuentadebe"][$posicion] = $fila["CuentaDebe"];
					$matriz["cuentahaber"][$posicion] = $fila["CuentaHaber"];										
					$matriz["comprobante"][$posicion] = $fila["Comprobante"];										
					$matriz["anio"][$posicion] = $fila["Anio"];															
					$posicion++;				
				}
							
				
			}
			

			
			function recorrerMatrizConceptosPago()  {
			// Recorre la matriz con los valores MySQL  inserta los valores en Postgresql
			
				global $res,$matriz;
					for($pos=0;$pos < mysql_num_rows($res); $pos++)  {

					$concepto= $matriz["concepto"][$pos] ;
					$concepto = eliminarCaracteresEspeciales($concepto);
					$cuentadebe= $matriz["cuentadebe"][$pos] ;
					$cuentadebe = eliminarCaracteresEspeciales($cuentadebe);
					$cuentahaber= $matriz["cuentahaber"][$pos] ;
					$cuentahaber = eliminarCaracteresEspeciales($cuentahaber);
					$comprobante= $matriz["comprobante"][$pos] ;
					$comprobante = eliminarCaracteresEspeciales($comprobante);
					//$anio= $matriz["anio"][$pos] ;  Se toma por defecto el valor del año actual por que el parametro anio no se usa en el Mentor Antiguo
					$anio = consultarAnio();
					$compania= $_SESSION["compania"]; 
					
					insertarConceptosPago($compania, $concepto, $cuentadebe, $cuentahaber, $comprobante, $anio);
					
									
					}
			
			}
			
			function eliminarConceptosPago() {
				$cnx= conectar_postgres();
				$cons= "DELETE FROM Contabilidad.ConceptosPago";
				$res = @pg_query($cnx, $cons);
				if (!$res) {
							echo "<p class='error1'> Error de ejecucion </p>".pg_last_error()."<br>";
							echo "<p class= 'subtitulo1'>Comando SQL </p> <br>".$cons."<br/>";
							
						}
					
			}
			
			
			
			
		
		
		function migrarConceptosPago($paso) {
		
			
			// Tabla Contabilidad.Comprobantes
			normalizarCodificacionComp1(utf8_encode("Á"),'&Aacute;');			
			normalizarCodificacionComp1(utf8_encode("É"),'&Eacute;');
			normalizarCodificacionComp1(utf8_encode("Í"),'&Iacute;');
			normalizarCodificacionComp1(utf8_encode("Ó"),'&Oacute;');
			normalizarCodificacionComp1(utf8_encode("Ú"),'&Uacute;');
			normalizarCodificacionComp1(utf8_encode("Ñ"),'&Ntilde;');
			llamarRegistrosMySQLConceptosPago();
			llenarMatrizConceptosPago();
			recorrerMatrizConceptosPago();
			
			// Tabla Contabilidad.Comprobantes
			normalizarCodificacionComp2('&Aacute;', utf8_encode("Á"));			
			normalizarCodificacionComp2('&Eacute;', utf8_encode("É"));
			normalizarCodificacionComp2('&Iacute;', utf8_encode("Í"));
			normalizarCodificacionComp2('&Oacute;', utf8_encode("Ó"));
			normalizarCodificacionComp2('&Uacute;',utf8_encode("Ú"));
			normalizarCodificacionComp2('&Ntilde;',utf8_encode("Ñ"));
			
			
			// Tabla Contabilidad.ConceptosPago			
			normalizarCodificacionConceptosPago('&Aacute;', utf8_encode("Á"));			
			normalizarCodificacionConceptosPago('&Eacute;', utf8_encode("É"));
			normalizarCodificacionConceptosPago('&Iacute;', utf8_encode("Í"));
			normalizarCodificacionConceptosPago('&Oacute;', utf8_encode("Ó"));
			normalizarCodificacionConceptosPago('&Uacute;',utf8_encode("Ú"));
			normalizarCodificacionConceptosPago('&Ntilde;',utf8_encode("Ñ"));
			echo "<p class='mensajeEjecucion'> <span class = 'subtitulo1'>Paso $paso: </span> Se ha migrado la tabla Contabilidad.ConceptosPago </p> ";
	
		}
		
		
		
		
		
	
	
	
	?>
