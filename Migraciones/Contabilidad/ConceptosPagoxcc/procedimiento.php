	<html>	
		<head>
			<title> Migracion Contabilidad.ConceptosPagoxcc </title>
			<link rel="stylesheet" type="text/css" href="../General/estilos/estilos.css">
		</head>
	
	
	<?php
		session_start();
		include_once('../General/funciones/funciones.php');
		
		
		
		
		
		/* Inicia defincion de funciones */
		
			
		function  normalizarCodificacionComp3($cadenaBusqueda,$cadenaReemplazo)  {
		// Busca y reemplaza ocurrencias en una tabla
			$cnx= conectar_postgres();
			$cons = "UPDATE Contabilidad.Comprobantes SET comprobante = replace(comprobante ,'$cadenaBusqueda','$cadenaReemplazo'), tipocomprobant = replace( tipocomprobant ,'$cadenaBusqueda','$cadenaReemplazo'), numeroinicial = replace( numeroinicial ,'$cadenaBusqueda','$cadenaReemplazo'), formato = replace( formato ,'$cadenaBusqueda','$cadenaReemplazo'), compania = replace( compania ,'$cadenaBusqueda','$cadenaReemplazo'), comppresupuesto = replace( comppresupuesto ,'$cadenaBusqueda','$cadenaReemplazo'), comppresupuestoadc = replace( comppresupuestoadc ,'$cadenaBusqueda','$cadenaReemplazo'),  cierre = replace( cierre ,'$cadenaBusqueda','$cadenaReemplazo'),  formatoadc = replace( formatoadc ,'$cadenaBusqueda','$cadenaReemplazo')";
			
			$res = pg_query($cnx , $cons);
				if (!$res)  {
					echo "<p class='error1'> Error en la normalizacion de la codificacion </p>".pg_last_error()."<br>";
					echo "<p class= 'subtitulo1'>Comando SQL </p> <br>".$cons."<br/> <br/> <br/>";
				}

		}	
		
		
		function  normalizarCodificacionConceptosPago2($cadenaBusqueda,$cadenaReemplazo)  {
		// Busca y reemplaza ocurrencias en una tabla
			$cnx= conectar_postgres();
			$cons = "UPDATE Contabilidad.ConceptosPago SET compania = replace( compania,'$cadenaBusqueda','$cadenaReemplazo'), concepto = replace( concepto,'$cadenaBusqueda','$cadenaReemplazo'), cuentadebe = replace( cuentadebe ,'$cadenaBusqueda','$cadenaReemplazo'), cuentahaber = replace( cuentahaber,'$cadenaBusqueda','$cadenaReemplazo'), comprobante = replace( comprobante,'$cadenaBusqueda','$cadenaReemplazo')";
			
			$res = pg_query($cnx , $cons);
				if (!$res)  {
					echo "<p class='error1'> Error en la normalizacion de la codificacion </p>".pg_last_error()."<br>";
				}

		}
			
			
			
		function  normalizarCodificacionConceptosPagoxcc($cadenaBusqueda,$cadenaReemplazo)  {
		// Busca y reemplaza ocurrencias en una tabla
			$cnx= conectar_postgres();
			$cons = "UPDATE Contabilidad.ConceptosPagoxcc SET compania = replace( compania,'$cadenaBusqueda','$cadenaReemplazo'), concepto = replace( concepto,'$cadenaBusqueda','$cadenaReemplazo'), cuentadebe = replace( cuentadebe ,'$cadenaBusqueda','$cadenaReemplazo'), comprobante = replace( comprobante,'$cadenaBusqueda','$cadenaReemplazo') , cc = replace( cc,'$cadenaBusqueda','$cadenaReemplazo')";
			
			$res = pg_query($cnx , $cons);
				if (!$res)  {
					echo "<p class='error1'> Error en la normalizacion de la codificacion </p>".pg_last_error()."<br>";
				}

		}
		
				
	
		function llamarRegistrosMySQLConceptosPagoxcc() {
			// Selecciona los registros MySQL (Origen)
			global $res;
			$cnx = conectar_mysql("Contabilidad");
			$cons = "SELECT *  FROM Contabilidad.ConceptosPagoxcc";
			$res =  mysql_query($cons);
			return $res; 
		
		}
		
		
		function crearTablaMigracionConceptosPagoxcc() {
		// Esta funcion crea una tabla con estructura similar a la tabla Contabilidad.movimiento, con la diferencia que carece de llave primaria y omite la restriccion NOT NULL,          ademas  crea un nuevo campo llamado error en el cual se almacena la descripcion del error generado en la consulta inicial.
			$cnx= conectar_postgres();
			$cons = "CREATE TABLE IF NOT EXISTS contabilidad.conceptospagoxccMigracion(  compania character varying(200) ,  concepto character varying(200) ,  cc character varying(100) ,  porcdist double precision,  cuentadebe character varying(100),  comprobante character varying(200) ,  anio integer ,  completa integer ,  error text)WITH (  OIDS=FALSE)";	 		
			$res = @pg_query($cnx, $cons);
				if (!$res) {
				
					echo "<p class='error1'> Error de ejecucion </p>".pg_last_error()."<br>";
					echo "<p class= 'subtitulo1'>Comando SQL </p> <br>".$cons."<br>"; 				
					echo "<br><br>";			
					//echo "<br><br>";
				}
			
		}
		
		function insertarConceptosPagoxccMigracion($compania, $concepto, $cc, $porcdist, $cuentadebe, $comprobante, $anio, $error) {
		//Realiza la insercion en Postgresql con base en los parametros
			
			$cnx = 	conectar_postgres();
			$cons = "INSERT INTO Contabilidad.ConceptosPagoxccMigracion ( compania, concepto, cc, porcdist, cuentadebe, comprobante, anio, error) VALUES ('$compania', '$concepto', '$cc', $porcdist, '$cuentadebe', '$comprobante', $anio, '$error')"	;
					 
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
		

		
		function insertarConceptosPagoxcc($compania, $concepto, $cc, $porcdist, $cuentadebe, $comprobante, $anio) {
		//Realiza la insercion en Postgresql con base en los parametros
			
			$cnx = 	conectar_postgres();
			 $cons = "INSERT INTO Contabilidad.ConceptosPagoxcc (compania, concepto, cc, porcdist, cuentadebe, comprobante, anio) VALUES ('$compania', '$concepto', $cc, $porcdist, '$cuentadebe', '$comprobante', $anio)"	;
			
			$cons = str_replace( "'NULL'","NULL",$cons  );	
			$res = @pg_query($cnx, $cons);
				if (!$res) {
					$consUTF8 = utf8_encode($cons);
					$resUTF8 = @pg_query($cnx, $consUTF8);					
						if (!$resUTF8) {
							$error= pg_last_error();
							insertarConceptosPagoxccMigracion($compania, $concepto, $cc, $porcdist, $cuentadebe, $comprobante, $anio, $error);
							
							
							
						}
				
				}

				
		}
		
		
		function  llenarMatrizConceptosPagoxcc(){
		// Llena una matriz con el resultado de la consulta MySQL
			
			unset($matriz); 
			global  $matriz;	
			$res = llamarRegistrosMySQLConceptosPagoxcc();
			$posicion=0;
				
				while ($fila = mysql_fetch_array($res))
				{	
					
					$matriz["concepto"][$posicion] = $fila["Concepto"];
					$matriz["cc"][$posicion] = $fila["CC"];
					$matriz["porcdist"][$posicion] = $fila["PorcDist"];
					$matriz["cuentadebe"][$posicion] = $fila["CuentaDebe"];
					$matriz["comprobante"][$posicion] = $fila["Comprobante"];										
					$matriz["anio"][$posicion] = $fila["Anio"];															
					$posicion++;				
				}
							
				
			}
			

			
			function recorrerMatrizConceptosPagoxcc()  {
			// Recorre la matriz con los valores MySQL  inserta los valores en Postgresql
			
				global $res,$matriz;
					for($pos=0;$pos < mysql_num_rows($res); $pos++)  {

					
					
					$concepto= $matriz["concepto"][$pos] ;
					$concepto = eliminarCaracteresEspeciales($concepto);
					
					$cc= $matriz["cc"][$pos] ;
					$cc = eliminarCaracteresEspeciales($cc);
					
					$porcdist= $matriz["porcdist"][$pos] ;
					$porcdist = eliminarCaracteresEspeciales($porcdist);
					
					$cuentadebe= $matriz["cuentadebe"][$pos] ;
					$cuentadebe = eliminarCaracteresEspeciales($cuentadebe);
					
					$comprobante= $matriz["comprobante"][$pos] ;
					$comprobante = eliminarCaracteresEspeciales($comprobante);
					
					//$anio= $matriz["anio"][$pos] ;
					$anio = consultarAnio();
					$compania= $_SESSION["compania"];
					
					
					insertarConceptosPagoxcc($compania, $concepto, $cc, $porcdist, $cuentadebe, $comprobante, $anio);
					
									
					}
			
			}
			
			function eliminarConceptosPagoxcc() {
				$cnx= conectar_postgres();
				$cons= "DELETE FROM Contabilidad.ConceptosPagoxcc";
				$res = @pg_query($cnx, $cons);
				if (!$res) {
							echo "<p class='error1'> Error de ejecucion </p>".pg_last_error()."<br>";
							echo "<p class= 'subtitulo1'>Comando SQL </p> <br>".$cons."<br/>";
							
						}
					
			}
			
			
			
			
			function eliminarConceptosPagoxccMigracion() {
				$cnx= conectar_postgres();
				$cons= "DELETE FROM Contabilidad.ConceptosPagoxccMigracion";
				$res = @pg_query($cnx, $cons);
				if (!$res) {
							echo "<p class='error1'> Error de ejecucion </p>".pg_last_error()."<br>";
							echo "<p class= 'subtitulo1'>Comando SQL </p> <br>".$cons."<br/>";
							
						}
					
			}
			
			function actualizarConceptosPagoxcc() {
				$cnx= conectar_postgres();
				$cons= "UPDATE Contabilidad.ConceptosPagoxcc SET Completa = '1'";
				$res = @pg_query($cnx, $cons);
				if (!$res) {
							echo "<p class='error1'> Error de ejecucion </p>".pg_last_error()."<br>";
							echo "<p class= 'subtitulo1'>Comando SQL </p> <br>".$cons."<br/>";
							
				}
					
			}
			
			function actualizarConceptosPagoxcc2(){
				$cnx= conectar_postgres();
				$cons= "UPDATE Contabilidad.ConceptosPagoxCC SET cc = '000'  WHERE cc = '0'";
				$res = @pg_query($cnx, $cons);
				if (!$res) {
							echo "<p class='error1'> Error de ejecucion </p>".pg_last_error()."<br>";
							echo "<p class= 'subtitulo1'>Comando SQL </p> <br>".$cons."<br/>";
							
				}			
			
			
			}
			
			
			
			
		
		
		function migrarConceptosPagoxcc($paso) {
		
			crearTablaMigracionConceptosPagoxcc();
			eliminarConceptosPagoxccMigracion();
			// Tabla Contabilidad.Comprobantes
			normalizarCodificacionComp1(utf8_encode("Á"),'&Aacute;');			
			normalizarCodificacionComp1(utf8_encode("É"),'&Eacute;');
			normalizarCodificacionComp1(utf8_encode("Í"),'&Iacute;');
			normalizarCodificacionComp1(utf8_encode("Ó"),'&Oacute;');
			normalizarCodificacionComp1(utf8_encode("Ú"),'&Uacute;');
			normalizarCodificacionComp1(utf8_encode("Ñ"),'&Ntilde;');
			
			
			
			// Tabla Contabilidad.ConceptosPago
			normalizarCodificacionConceptosPago2(utf8_encode("Á"),'&Aacute;');			
			normalizarCodificacionConceptosPago2(utf8_encode("É"),'&Eacute;');
			normalizarCodificacionConceptosPago2(utf8_encode("Í"),'&Iacute;');
			normalizarCodificacionConceptosPago2(utf8_encode("Ó"),'&Oacute;');
			normalizarCodificacionConceptosPago2(utf8_encode("Ú"),'&Uacute;');
			normalizarCodificacionConceptosPago2(utf8_encode("Ñ"),'&Ntilde;');
			
			llamarRegistrosMySQLConceptosPagoxcc();
			llenarMatrizConceptosPagoxcc();
			recorrerMatrizConceptosPagoxcc();
			actualizarConceptosPagoxcc();
			actualizarConceptosPagoxcc2();
			
			// Tabla Contabilidad.Comprobantes
			normalizarCodificacionComp3('&Aacute;', utf8_encode("Á"));			
			normalizarCodificacionComp3('&Eacute;', utf8_encode("É"));
			normalizarCodificacionComp3('&Iacute;', utf8_encode("Í"));
			normalizarCodificacionComp3('&Oacute;', utf8_encode("Ó"));
			normalizarCodificacionComp3('&Uacute;',utf8_encode("Ú"));
			normalizarCodificacionComp3('&Ntilde;',utf8_encode("Ñ"));
			
			// Tabla Contabilidad.ConceptosPago
			normalizarCodificacionConceptosPago2('&Aacute;', utf8_encode("Á"));			
			normalizarCodificacionConceptosPago2('&Eacute;', utf8_encode("É"));
			normalizarCodificacionConceptosPago2('&Iacute;', utf8_encode("Í"));
			normalizarCodificacionConceptosPago2('&Oacute;', utf8_encode("Ó"));
			normalizarCodificacionConceptosPago2('&Uacute;',utf8_encode("Ú"));
			normalizarCodificacionConceptosPago2('&Ntilde;',utf8_encode("Ñ"));
			
			
			// Tabla Contabilidad.ConceptosPagoxcc			
			normalizarCodificacionConceptosPagoxcc('&Aacute;', utf8_encode("Á"));			
			normalizarCodificacionConceptosPagoxcc('&Eacute;', utf8_encode("É"));
			normalizarCodificacionConceptosPagoxcc('&Iacute;', utf8_encode("Í"));
			normalizarCodificacionConceptosPagoxcc('&Oacute;', utf8_encode("Ó"));
			normalizarCodificacionConceptosPagoxcc('&Uacute;',utf8_encode("Ú"));
			normalizarCodificacionConceptosPagoxcc('&Ntilde;',utf8_encode("Ñ"));
			echo "<p class='mensajeEjecucion'> <span class = 'subtitulo1'>Paso $paso: </span> Se ha migrado la tabla Contabilidad.ConceptosPago </p> ";
	
		}
		
		
		
		
		
	
	
	
	?>
