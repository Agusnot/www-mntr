	<html>	
		<head>
			<title> Migracion Contabilidad.CruzarComprobantes </title>
			<link rel="stylesheet" type="text/css" href="../General/estilos/estilos.css">
		</head>
	
	
	<?php
		session_start();
		include_once('../General/funciones/funciones.php');
		
		
		
		
		
		/* Inicia defincion de funciones */
		
			
		function  normalizarCodificacionComp4($cadenaBusqueda,$cadenaReemplazo)  {
		// Busca y reemplaza ocurrencias en una tabla
			$cnx= conectar_postgres();
			$cons = "UPDATE Contabilidad.Comprobantes SET comprobante = replace(comprobante ,'$cadenaBusqueda','$cadenaReemplazo'), tipocomprobant = replace( tipocomprobant ,'$cadenaBusqueda','$cadenaReemplazo'), numeroinicial = replace( numeroinicial ,'$cadenaBusqueda','$cadenaReemplazo'), formato = replace( formato ,'$cadenaBusqueda','$cadenaReemplazo'), compania = replace( compania ,'$cadenaBusqueda','$cadenaReemplazo'), comppresupuesto = replace( comppresupuesto ,'$cadenaBusqueda','$cadenaReemplazo'), comppresupuestoadc = replace( comppresupuestoadc ,'$cadenaBusqueda','$cadenaReemplazo'),  cierre = replace( cierre ,'$cadenaBusqueda','$cadenaReemplazo'),  formatoadc = replace( formatoadc ,'$cadenaBusqueda','$cadenaReemplazo')";
			
			$res = pg_query($cnx , $cons);
				if (!$res)  {
					echo "<p class='error1'> Error en la normalizacion de la codificacion </p>".pg_last_error()."<br>";
					echo "<p class= 'subtitulo1'>Comando SQL </p> <br>".$cons."<br/> <br/> <br/>";
				}

		}	
		
		
		
			
			
			
		function  normalizarCodificacionCruzarComp($cadenaBusqueda,$cadenaReemplazo)  {
		// Busca y reemplaza ocurrencias en una tabla
			$cnx= conectar_postgres();
			$cons = "UPDATE Contabilidad.CruzarComprobantes  SET comprobante = replace( comprobante,'$cadenaBusqueda','$cadenaReemplazo') , cruzarcon = replace( cruzarcon,'$cadenaBusqueda','$cadenaReemplazo'), movimiento = replace( movimiento,'$cadenaBusqueda','$cadenaReemplazo'), cuenta = replace( cuenta,'$cadenaBusqueda','$cadenaReemplazo'), cuentacruzar = replace( cuentacruzar,'$cadenaBusqueda','$cadenaReemplazo'), compania = replace( compania,'$cadenaBusqueda','$cadenaReemplazo')";
			
			$res = pg_query($cnx , $cons);
				if (!$res)  {
					echo "<p class='error1'> Error en la normalizacion de la codificacion </p>".pg_last_error()."<br>";
					echo  "<p class= 'subtitulo1'>Comando SQL </p> <br>".$cons."<br/> <br/> <br/>"; 
				}

		}
		
				
	
		function llamarRegistrosMySQLCruzarComp() {
			// Selecciona los registros MySQL (Origen)
			global $res;
			$cnx = conectar_mysql("Contabilidad");
			$cons = "SELECT *  FROM Contabilidad.CruzarComprobantes";
			$res =  mysql_query($cons);
			return $res; 
		
		}
		
		
		function crearTablaMigracionCruzarComp() {
		// Esta funcion crea una tabla con estructura similar a la tabla Contabilidad.movimiento, con la diferencia que carece de llave primaria y omite la restriccion NOT NULL,          ademas  crea un nuevo campo llamado error en el cual se almacena la descripcion del error generado en la consulta inicial.
			$cnx= conectar_postgres();
			$cons = "CREATE TABLE IF NOT EXISTS contabilidad.CruzarComprobantesMigracion(  comprobante character varying(100) ,  cruzarcon character varying(100) ,  movimiento character varying(100) ,  cuenta character varying(100) ,  cuentacruzar character varying(100) ,  compania character varying(100) ,  anio integer ,  varios integer,  error text)WITH (  OIDS=FALSE)";	 		
			$res = @pg_query($cnx, $cons);
				if (!$res) {
				
					echo "<p class='error1'> Error de ejecucion </p>".pg_last_error()."<br>";
					echo "<p class= 'subtitulo1'>Comando SQL </p> <br>".$cons."<br>"; 				
					echo "<br><br>";			
					//echo "<br><br>";
				}
			
		}
		
		function insertarCruzarCompMigracion($comprobante, $cruzarcon, $movimiento, $cuenta, $cuentacruzar, $compania, $anio,  $error) {
		//Realiza la insercion en Postgresql con base en los parametros
			
			$cnx = 	conectar_postgres();
			$cons = "INSERT INTO Contabilidad.CruzarComprobantesMigracion ( comprobante, cruzarcon, movimiento, cuenta, cuentacruzar, compania, anio, error ) VALUES ('$comprobante', '$cruzarcon', '$movimiento', '$cuenta', '$cuentacruzar', '$compania', $anio, '$error')"	;
					 
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
		

		
		function insertarCruzarComp($comprobante, $cruzarcon, $movimiento, $cuenta, $cuentacruzar, $compania, $anio) {
		//Realiza la insercion en Postgresql con base en los parametros
			
			$cnx = 	conectar_postgres();
			$cons = "INSERT INTO Contabilidad.CruzarComprobantes ( comprobante, cruzarcon, movimiento, cuenta, cuentacruzar, compania, anio ) VALUES ('$comprobante', '$cruzarcon', '$movimiento', '$cuenta', '$cuentacruzar', '$compania', $anio)"	;
					 
			$cons = str_replace( "'NULL'","NULL",$cons  );	
			$res = @pg_query($cnx, $cons);
				if (!$res) {
					$consUTF8 = utf8_encode($cons);
					$resUTF8 = @pg_query($cnx, $consUTF8);					
						if (!$resUTF8) {
							$error= pg_last_error();
							insertarCruzarCompMigracion($comprobante, $cruzarcon, $movimiento, $cuenta, $cuentacruzar, $compania, $anio,  $error);
							
							
							
						}
				
				}

				
		}
		
		
		function  llenarMatrizCruzarComp(){
		// Llena una matriz con el resultado de la consulta MySQL
			
			unset($matriz); 
			global  $matriz;	
			$res = llamarRegistrosMySQLCruzarComp();
			$posicion=0;
				
				while ($fila = mysql_fetch_array($res))
				{	
					
					$matriz["comprobante"][$posicion] = $fila["Comprobante"];
					$matriz["cruzarcon"][$posicion] = $fila["CruzarCon"];
					$matriz["movimiento"][$posicion] = $fila["Movimiento"];
					$matriz["cuenta"][$posicion] = $fila["Cuenta"];
					$matriz["cuentacruzar"][$posicion] = $fila["CuentaCruzar"];										

					$posicion++;				
				}
							
				
			}
			

			
			function recorrerMatrizCruzarComp()  {
			// Recorre la matriz con los valores MySQL  inserta los valores en Postgresql
			
				global $res,$matriz;
					for($pos=0;$pos < mysql_num_rows($res); $pos++)  {

					
					$comprobante= $matriz["comprobante"][$pos] ;
					$comprobante = eliminarCaracteresEspeciales($comprobante);
					
					$cruzarcon= $matriz["cruzarcon"][$pos] ;
					$cruzarcon = eliminarCaracteresEspeciales($cruzarcon);
					
					$movimiento= $matriz["movimiento"][$pos] ;
					$movimiento = eliminarCaracteresEspeciales($movimiento);
					
					$cuenta= $matriz["cuenta"][$pos] ;
					$cuenta = eliminarCaracteresEspeciales($cuenta);
					
					$cuentacruzar= $matriz["cuentacruzar"][$pos] ;
					$cuentacruzar = eliminarCaracteresEspeciales($cuentacruzar);
					
					$compania= $_SESSION["compania"];
					
					$anio= consultarAnio();					
					
					insertarCruzarComp($comprobante, $cruzarcon, $movimiento, $cuenta, $cuentacruzar, $compania, $anio);
					
									
					}
			
			}
			
			function eliminarCruzarComp() {
				$cnx= conectar_postgres();
				$cons= "DELETE FROM Contabilidad.CruzarComprobantes";
				$res = @pg_query($cnx, $cons);
				if (!$res) {
							echo "<p class='error1'> Error de ejecucion </p>".pg_last_error()."<br>";
							echo "<p class= 'subtitulo1'>Comando SQL </p> <br>".$cons."<br/>";
							
						}
					
			}
			
			
			function eliminarCruzarCompMigracion() {
				$cnx= conectar_postgres();
				$cons= "DELETE FROM Contabilidad.cruzarcomprobantesMigracion";
				$res = @pg_query($cnx, $cons);
				if (!$res) {
							echo "<p class='error1'> Error de ejecucion </p>".pg_last_error()."<br>";
							echo "<p class= 'subtitulo1'>Comando SQL </p> <br>".$cons."<br/>";
							
						}
					
			}
			
			
		function actualizarCruzarComprobante($actualizacion, $condicion) {

			$cnx = 	conectar_postgres();
			$cons = "UPDATE Contabilidad.CruzarComprobantes SET Movimiento = '$actualizacion' WHERE Movimiento  = '$condicion' "	;
					 
			$res = @pg_query($cnx, $cons);
				if (!$res) {
					echo  "<p class='error1'> Error de ejecucion </p>".pg_last_error()."<br>";
					echo "<p class= 'subtitulo1'>Comando SQL </p> <br>".$consUTF8."<br/> <br/> <br/>";
				}
	
		}
			
		
		
		function migrarCruzarComp($paso) {
		
			crearTablaMigracionCruzarComp();
			eliminarCruzarCompMigracion();
			
			// Tabla Contabilidad.Comprobantes
			normalizarCodificacionComp4(utf8_encode("Á"),'&Aacute;');			
			normalizarCodificacionComp4(utf8_encode("É"),'&Eacute;');
			normalizarCodificacionComp4(utf8_encode("Í"),'&Iacute;');
			normalizarCodificacionComp4(utf8_encode("Ó"),'&Oacute;');
			normalizarCodificacionComp4(utf8_encode("Ú"),'&Uacute;');
			normalizarCodificacionComp4(utf8_encode("Ñ"),'&Ntilde;');
			
			
			
			
			
			llamarRegistrosMySQLCruzarComp();
			llenarMatrizCruzarComp();
			recorrerMatrizCruzarComp();
			
			// Tabla Contabilidad.Comprobantes
			normalizarCodificacionComp4('&Aacute;', utf8_encode("Á"));			
			normalizarCodificacionComp4('&Eacute;', utf8_encode("É"));
			normalizarCodificacionComp4('&Iacute;', utf8_encode("Í"));
			normalizarCodificacionComp4('&Oacute;', utf8_encode("Ó"));
			normalizarCodificacionComp4('&Uacute;',utf8_encode("Ú"));
			normalizarCodificacionComp4('&Ntilde;',utf8_encode("Ñ"));
			
			
			
			
			// Tabla Contabilidad.CruzarComrobantes		
			normalizarCodificacionCruzarComp('&Aacute;', utf8_encode("Á"));			
			normalizarCodificacionCruzarComp('&Eacute;', utf8_encode("É"));
			normalizarCodificacionCruzarComp('&Iacute;', utf8_encode("Í"));
			normalizarCodificacionCruzarComp('&Oacute;', utf8_encode("Ó"));
			normalizarCodificacionCruzarComp('&Uacute;',utf8_encode("Ú"));
			normalizarCodificacionCruzarComp('&Ntilde;',utf8_encode("Ñ"));
			echo "<p class='mensajeEjecucion'> <span class = 'subtitulo1'>Paso $paso: </span> Se ha migrado la tabla Contabilidad.CruzarComprobantes </p> ";
	
		}
		
		
		
		
		
	
	
	
	?>
