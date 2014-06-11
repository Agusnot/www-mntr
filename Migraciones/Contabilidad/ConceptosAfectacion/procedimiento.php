	<html>	
		<head>
			<title> Migracion Contabilidad.ConceptosAfectacion </title>
			<link rel="stylesheet" type="text/css" href="../General/estilos/estilos.css">
		</head>
	
	
	<?php
		session_start();
		include_once('../General/funciones/funciones.php');
		
		
		
		
		/* Inicia defincion de funciones */
		
		function  normalizarCodificacionComp1($cadenaBusqueda,$cadenaReemplazo)  {
		// Busca y reemplaza ocurrencias en una tabla
			$cnx= conectar_postgres();
			$cons = "UPDATE Contabilidad.Comprobantes SET comprobante = replace( comprobante ,'$cadenaBusqueda','$cadenaReemplazo'), tipocomprobant = replace( tipocomprobant ,'$cadenaBusqueda','$cadenaReemplazo'), numeroinicial = replace( numeroinicial ,'$cadenaBusqueda','$cadenaReemplazo'), formato = replace( formato ,'$cadenaBusqueda','$cadenaReemplazo'), compania = replace( compania ,'$cadenaBusqueda','$cadenaReemplazo'), comppresupuesto = replace( comppresupuesto ,'$cadenaBusqueda','$cadenaReemplazo'), comppresupuestoadc = replace( comppresupuestoadc ,'$cadenaBusqueda','$cadenaReemplazo'),  cierre = replace( cierre ,'$cadenaBusqueda','$cadenaReemplazo'),  formatoadc = replace( formatoadc ,'$cadenaBusqueda','$cadenaReemplazo')";
			
			$res = pg_query($cnx , $cons);
				if (!$res)  {
					echo "<p class='error1'> Error en la normalizacion de la codificacion </p>".pg_last_error()."<br>";
					echo "<p class= 'subtitulo1'>Comando SQL </p> <br>".$cons."<br/> <br/> <br/>";
				}

		}
		
		
		
			
		function  normalizarCodificacionConcAfec($cadenaBusqueda,$cadenaReemplazo)  {
		// Busca y reemplaza ocurrencias en una tabla
			$cnx= conectar_postgres();
			$cons = "UPDATE Contabilidad.ConceptosAfectacion  SET comprobante = replace( comprobante ,'$cadenaBusqueda','$cadenaReemplazo'), concepto = replace( concepto ,'$cadenaBusqueda','$cadenaReemplazo'), cuenta = replace( cuenta ,'$cadenaBusqueda','$cadenaReemplazo'), cuentabase = replace( cuentabase ,'$cadenaBusqueda','$cadenaReemplazo'), opera = replace( opera ,'$cadenaBusqueda','$cadenaReemplazo'), compania = replace( compania ,'$cadenaBusqueda','$cadenaReemplazo')";
			
			$res = pg_query($cnx , $cons);
				if (!$res)  {
					echo "<p class='error1'> Error en la normalizacion de la codificacion </p>".pg_last_error()."<br>";
					echo "<p class= 'subtitulo1'>Comando SQL </p> <br>".$cons."<br/> <br/> <br/>";
				}

		}
		
		function crearTablaMigracionConcAfec() {
		// Esta funcion crea una tabla con estructura similar a la tabla Contabilidad.movimiento, con la diferencia que carece de llave primaria y omite la restriccion NOT NULL,          ademas  crea un nuevo campo llamado error en el cual se almacena la descripcion del error generado en la consulta inicial.
			$cnx= conectar_postgres();
			$cons = "CREATE TABLE IF NOT EXISTS contabilidad.conceptosafectacionMigracion(  comprobante character varying(50) ,  concepto character varying(100) ,  cuenta character varying(200),  cuentabase character varying(200),  opera character varying(1),  compania character varying(200) ,  anio integer , error text)WITH (  OIDS=FALSE)";	 		
			$res = @pg_query($cnx, $cons);
				if (!$res) {
				
					echo "<p class='error1'> Error de ejecucion </p>".pg_last_error()."<br>";
					echo "<p class= 'subtitulo1'>Comando SQL </p> <br>".$cons."<br>"; 				
					echo "<br><br>";			
					//echo "<br><br>";
				}
			
		}
		
		function insertarConcAfecMigracion($comprobante, $concepto, $cuenta, $cuentabase, $opera, $compania, $anio, $errormig) {
		//Realiza la insercion en Postgresql con base en los parametros
			
			$cnx = 	conectar_postgres();
			
			$cons = "INSERT INTO Contabilidad.ConceptosAfectacionMigracion (comprobante, concepto, cuenta, cuentabase, opera, compania, anio , error ) VALUES ('$comprobante', '$concepto', $cuenta, '$cuentabase', '$opera', '$compania', $anio, '$errormig')"	;
					 
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
				
	
		function llamarRegistrosMySQLConcAfec() {
			// Selecciona los registros MySQL (Origen)
			global $res;
			$cnx = conectar_mysql("Contabilidad");
			$cons = "SELECT *  FROM Contabilidad.ConceptosAfectacion";
			$res =  mysql_query($cons);
			return $res; 
		
		}
		
		

		
		function insertarConcAfec($comprobante, $concepto, $cuenta, $cuentabase, $opera, $compania, $anio) {
		//Realiza la insercion en Postgresql con base en los parametros
			
			$cnx = 	conectar_postgres();
			$cons = "INSERT INTO Contabilidad.ConceptosAfectacion (comprobante, concepto, cuenta, cuentabase, opera, compania, anio) VALUES ('$comprobante', '$concepto', $cuenta, '$cuentabase', '$opera', '$compania', $anio)"	;
					 
			$cons = str_replace( "'NULL'","NULL",$cons  );	
			$res = @pg_query($cnx, $cons);
				if (!$res) {
					$consUTF8 = utf8_encode($cons);
					$resUTF8 = @pg_query($cnx, $consUTF8);					
						if (!$resUTF8) {
							
							$error  =  pg_last_error();
							
							insertarConcAfecMigracion($comprobante, $concepto, $cuenta, $cuentabase, $opera, $compania, $anio, $error);
							
						}
				
				}

				
		}
		
		
		function  llenarMatrizConcAfec(){
		// Llena una matriz con el resultado de la consulta MySQL
			
			unset($matriz); 
			global  $matriz;	
			$res = llamarRegistrosMySQLConcAfec();
			$posicion=0;
				
				while ($fila = mysql_fetch_array($res))
				{	
					
					$matriz["comprobante"][$posicion] = $fila["Comprobante"];
					$matriz["concepto"][$posicion] = $fila["Concepto"];
					$matriz["Cuenta"][$posicion] = $fila["cuenta"];										
					$matriz["numeroinicial"][$posicion] = $fila["CuentaBase"];					
					$matriz["opera"][$posicion] = $fila["Opera"];					
					$matriz["compania"][$posicion] = $fila["Compania"];	
					$matriz["anio"][$posicion] = $fila["Anio"];					
					
									
															
					$posicion++;				
				}
							
				
			}
			

			
			function recorrerMatrizConcAfec()  {
			// Recorre la matriz con los valores MySQL  inserta los valores en Postgresql
			
				global $res,$matriz;
					for($pos=0;$pos < mysql_num_rows($res); $pos++)  {

					$comprobante= $matriz["comprobante"][$pos] ;
					$comprobante = eliminarCaracteresEspeciales($comprobante);
					
					$concepto= $matriz["concepto"][$pos] ;
					$concepto = eliminarCaracteresEspeciales($concepto);
					
					$cuenta= $matriz["cuenta"][$pos] ;
					$cuenta = eliminarCaracteresEspeciales($cuenta);
					
					$cuentabase= $matriz["cuentabase"][$pos] ;
					$cuentabase = eliminarCaracteresEspeciales($cuentabase);
					
					$opera= $matriz["opera"][$pos] ;
					$opera = eliminarCaracteresEspeciales($opera);
					
					$compania = $_SESSION["compania"];
					
					
					$anio= $matriz["anio"][$pos] ;

					
					
					
					
					insertarConcAfec($comprobante, $concepto, $cuenta, $cuentabase, $opera, $compania, $anio);
					
									
					}
			
			}
			
			function eliminarConcAfec() {
				$cnx= conectar_postgres();
				$cons= "DELETE FROM Contabilidad.ConceptosAfectacion";
				$res = @pg_query($cnx, $cons);
						if (!$res) {
							echo "<p class='error1'> Error de ejecucion </p>".pg_last_error()."<br>";
							echo "<p class= 'subtitulo1'>Comando SQL </p> <br>".$cons."<br/>";
							
						}
					
			}
			
			function eliminarConcAfecMigracion() {
				$cnx= conectar_postgres();
				$cons= "DELETE FROM Contabilidad.ConceptosAfectacionMigracion";
				$res = @pg_query($cnx, $cons);
						if (!$res) {
							echo "<p class='error1'> Error de ejecucion </p>".pg_last_error()."<br>";
							echo "<p class= 'subtitulo1'>Comando SQL </p> <br>".$cons."<br/>";
							
						}
					
			}
			
			
			
			
		
		
		function migrarConcAfec($paso) {
			
			
			crearTablaMigracionConcAfec();
			 eliminarConcAfecMigracion();
			// Tabla Contabilidad.Comprobantes 
			normalizarCodificacionComp1(utf8_encode("Á"),'&Aacute;');			
			normalizarCodificacionComp1(utf8_encode("É"),'&Eacute;');
			normalizarCodificacionComp1(utf8_encode("Í"),'&Iacute;');
			normalizarCodificacionComp1(utf8_encode("Ó"),'&Oacute;');
			normalizarCodificacionComp1(utf8_encode("Ú"),'&Uacute;');
			normalizarCodificacionComp1(utf8_encode("Ñ"),'&Ntilde;');
			eliminarConcAfec();
			llamarRegistrosMySQLConcAfec();
			llenarMatrizConcAfec();
			recorrerMatrizConcAfec();
			
			
			// Tabla Contabilidad.Comprobantes
			normalizarCodificacionComp1('&Aacute;', utf8_encode("Á"));			
			normalizarCodificacionComp1('&Eacute;', utf8_encode("É"));
			normalizarCodificacionComp1('&Iacute;', utf8_encode("Í"));
			normalizarCodificacionComp1('&Oacute;', utf8_encode("Ó"));
			normalizarCodificacionComp1('&Uacute;',utf8_encode("Ú"));
			normalizarCodificacionComp1('&Ntilde;',utf8_encode("Ñ"));
			
			// Tabla Contabilidad.ConceptosAfectacion
			normalizarCodificacionConcAfec('&Aacute;', utf8_encode("Á"));			
			normalizarCodificacionConcAfec('&Eacute;', utf8_encode("É"));
			normalizarCodificacionConcAfec('&Iacute;', utf8_encode("Í"));
			normalizarCodificacionConcAfec('&Oacute;', utf8_encode("Ó"));
			normalizarCodificacionConcAfec('&Uacute;',utf8_encode("Ú"));
			normalizarCodificacionConcAfec('&Ntilde;',utf8_encode("Ñ"));
			
			echo "<p class='mensajeEjecucion'> <span class = 'subtitulo1'>Paso $paso: </span> Se ha migrado la tabla Contabilidad.ConceptosAfectacion </p> ";
	
		}
		
		
		
		
		
	
	
	
	?>
