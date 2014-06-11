	<html>	
		<head>
			<title> Migracion ContratacionSalud.CUPSXPlanes </title>
			<link rel="stylesheet" type="text/css" href="../General/estilos/estilos.css">
		</head>
	
	
	<?php
		session_start();
		include_once('../General/funciones/funciones.php');
		
		
		
		
		/* Inicia defincion de funciones */
		
			
		function  normalizarCodificacionCUPSxPlanes($cadenaBusqueda,$cadenaReemplazo)  {
		// Busca y reemplaza ocurrencias en una tabla
			$cnx= conectar_postgres();
			$cons = "UPDATE ContratacionSalud.CUPSXPlanes SET compania = replace( compania,'$cadenaBusqueda','$cadenaReemplazo')";
			
			$res = pg_query($cnx , $cons);
				if (!$res)  {
					echo "<p class='error1'> Error en la normalizacion de la codificacion </p>".pg_last_error()."<br>";
					echo  "<p class= 'subtitulo1'>Comando SQL </p> <br>".$cons."<br/> <br/> <br/>";  
					
				}

		}
		
		function maximoAutoId1() {
			// Selecciona los registros MySQL (Origen)
			global $res;
			$cnx = conectar_postgres();
			$cons = "SELECT MAX(AutoId) AS maximoautoid  FROM ContratacionSalud.PlanesTarifas ";
			$res = pg_query($cnx, $cons);
			$fila = pg_fetch_array($res);
			$res = $fila["maximoautoid"];
			return $res;  
		
		}
		
		function actualizarCUPxPlanISS2001() {
		
			$cnx = conectar_postgres();
			$autoid = maximoAutoId1();
			$autoid = $autoid;
			$cons= "UPDATE ContratacionSalud.CUPSxPlanes SET Autoid = '$autoid' WHERE autoid = '2001'";
			$res =  @pg_query($cons);
				if (!$res) {
				echo  "<p class='error1'> Error de ejecucion </p>".pg_last_error()."<br>";
				echo  "<p class= 'subtitulo1'>Comando SQL </p> <br>".$cons."<br/> <br/> <br/>";
				
				}
		
			}
		
			
		
		function  normalizarCodificacionPlanesTarifarios($cadenaBusqueda,$cadenaReemplazo)  {
		// Busca y reemplaza ocurrencias en una tabla
			$cnx= conectar_postgres();
			$cons = "UPDATE ContratacionSalud.PlanesTarifas SET nombreplan = replace( nombreplan,'$cadenaBusqueda','$cadenaReemplazo')";
			
			$res = pg_query($cnx , $cons);
				if (!$res)  {
					echo "<p class='error1'> Error en la normalizacion de la codificacion </p>".pg_last_error()."<br>";
					echo  "<p class= 'subtitulo1'>Comando SQL </p> <br>".$cons."<br/> <br/> <br/>";
				}

		}
		
		
		
		function llamarRegistrosMySQL($tabla) {
			// Selecciona los registros MySQL (Origen)
			global $res;
			$cnx = conectar_mysql("Salud");
			$cons = "SELECT *  FROM Salud.$tabla ";
			$res =  mysql_query($cons);
			return $res; 
		
		}
		
		
		function llamarAutoId($nombre){
			$cnx= conectar_postgres();
			$cons = "SELECT autoid  FROM ContratacionSalud.PlanesTarifas WHERE NombrePlan = '$nombre'";
			$res = pg_query($cnx, $cons);
			$fila = pg_fetch_array($res);
			$res = $fila["autoid"]; 
			return $res;
		
		}
		
		function contarEPS(){
			$cnx= conectar_mysql("Salud");
			$cons = "SELECT COUNT(*) AS conteoeps FROM Salud.EPS"; 
			$res = mysql_query($cons);
			$fila = mysql_fetch_array($res);
			$res = $fila["conteoeps"]; 
			return $res;
		
		}
		
		function CupsISS2001() {
		
			$cnx = conectar_postgres();
			$ruta = $_SERVER['DOCUMENT_ROOT'];
			$cons= "COPY ContratacionSalud.CUPSXPlanes FROM '$ruta/Migraciones/Contratacionsalud/CupsxPlanes/ISS2001.csv' WITH DELIMITER ';' CSV HEADER;";
			$res =  pg_query($cnx, $cons);
				if (!$res) {
				echo  "<p class='error1'> Error de ejecucion </p>".pg_last_error()."<br>";
				echo  "<p class= 'subtitulo1'>Comando SQL </p> <br>".$consUTF8."<br/> <br/> <br/>";
				 
				
				}
		
		}
		
		
		
		
		
		function crearTablaMigracionCUPSXPlanes() {
		// Esta funcion crea una tabla con estructura similar a la tabla Contabilidad.movimiento, con la diferencia que carece de llave primaria y omite la restriccion NOT NULL,          ademas  crea un nuevo campo llamado error en el cual se almacena la descripcion del error generado en la consulta inicial.
			$cnx= conectar_postgres();
			$cons = "CREATE TABLE IF NOT EXISTS Contratacionsalud.cupsxplanesMigracion(  autoid integer ,  cup character varying(30) ,  valor double precision ,  compania character varying(80) ,  error text)WITH (  OIDS=FALSE)";
	 		
			$res = @pg_query($cnx, $cons);
				if (!$res) {
				
					echo "<p class='error1'> Error de ejecucion </p>".pg_last_error()."<br>";
					echo "<p class= 'subtitulo1'>Comando SQL </p> <br>".$cons."<br>"; 				
					echo "<br><br>";			
					//echo "<br><br>";
				}
			
		}
		
		
		function insertarCUPSXplanesMigracion($autoid, $cup, $valor, $compania,$error) {
		//Realiza la insercion en Postgresql con base en los parametros
			
			$cnx = 	conectar_postgres();
			
			$cons = "INSERT INTO ContratacionSalud.CUPSXPlanesMigracion ( autoid, cup, valor, compania, error ) VALUES ( $autoid, '$cup', $valor , '$compania', '$error')"	;
					 
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

		
			
		
		
		
		function  llenarMatriz($tabla){
		// Llena una matriz con el resultado de la consulta MySQL
			
			unset($matriz); 
			global  $matriz;
			$posicion=0;
			$res = 	llamarRegistrosMySQL($tabla) ;		
			$fin = contarEPS();
			
				while ($fila = mysql_fetch_array($res))
				{	
					
					$matriz["cup"][$posicion] = $fila["CUP"];
					
						for ($autoid=1;$autoid<=$fin;$autoid++){
							$campo = "A".$autoid;			
							$matriz[$campo][$posicion] = $fila[$campo];	
						}	
					
								
					$posicion++;				
				}
							
				
		}
		
		
		function llenarMatriz1($tabla){
			$cnx = conectar_postgres();
			unset($matriz); 
			global  $matriz;
			$res = llamarRegistrosMySQL($tabla);
			$posicion=0;
			
				
				while ($fila = mysql_fetch_array($res))	{	
					
					
					$matriz["entidad"][$posicion] = $fila["Entidad"];
					$matriz["cup"][$posicion] = $fila["CUP"];
					$matriz["valor"][$posicion] = $fila["Valor"];
					
					$posicion++;										
				
				}
			
		
		}
		
		
		
		
		function recorrerMatriz()  {
			// Recorre la matriz con los valores MySQL  inserta los valores en Postgresql
			
				global $res,$matriz;
					for($pos=0;$pos < mysql_num_rows($res); $pos++)  {
					
						$compania = $_SESSION["compania"];
						$cup= $matriz["cup"][$pos] ;
						$fin = contarEPS();
						
							
							for ($autoid=1;$autoid<=$fin;$autoid++){
								$campo = "A".$autoid;
								$valor = $matriz[$campo][$pos];
																	
									if (isset($matriz[$campo][$pos]))	{
										
										insertarCUPSXplanes($autoid, $cup, $valor, $compania);		
									}
									
							}					
		
					}
			
		}
			
			
			
			function recorrerMatriz1()  {
			// Recorre la matriz con los valores MySQL  inserta los valores en Postgresql
			
				global $res,$matriz;
					for($pos=0;$pos < mysql_num_rows($res); $pos++)  {
					
						$compania = $_SESSION["compania"];
						$entidad = $matriz["entidad"][$pos] ;						
						$entidad = eliminarCaracteresEspeciales($entidad);
						$autoid = llamarAutoId($entidad);
						$cup= $matriz["cup"][$pos] ;
						$valor = $matriz["valor"][$pos];
						
							
						insertarCUPSXplanes($autoid, $cup, $valor, $compania);
						
					}
			
			}
		
		
		
		
		
		

		
		function insertarCUPSXplanes($autoid, $cup, $valor, $compania) {
		//Realiza la insercion en Postgresql con base en los parametros
			
			$cnx = 	conectar_postgres();
			
			$cons = "INSERT INTO ContratacionSalud.CUPSXPlanes ( autoid, cup, valor, compania ) VALUES ( $autoid, '$cup', $valor , '$compania')"	;
					 
			$cons = str_replace( "'NULL'","NULL",$cons  );	
			$res = @pg_query($cnx, $cons);
				if (!$res) {
					$consUTF8 = utf8_encode($cons);
					$resUTF8 = @pg_query($cnx, $consUTF8);					
						if (!$resUTF8) {
							$error = pg_last_error();	
							insertarCUPSXplanesMigracion($autoid, $cup, $valor, $compania,$error);
						
						}
				
				}

				
		}
		
	
			function eliminarCUPSXPlanes() {
				$cnx= conectar_postgres();
				$cons= "DELETE FROM ContratacionSalud.CUPSXPlanes";
				$res = @pg_query($cnx, $cons);
						if (!$res) {
							echo "<p class='error1'> Error de ejecucion </p>".pg_last_error()."<br>";
							echo "<p class= 'subtitulo1'>Comando SQL </p> <br>".$cons."<br/>";
							
						}
					
			}
			
			function eliminarCUPSXPlanesMigracion() {
				$cnx= conectar_postgres();
				$cons= "DELETE FROM ContratacionSalud.CUPSXPlanesMigracion";
				$res = @pg_query($cnx, $cons);
						if (!$res) {
							echo "<p class='error1'> Error de ejecucion </p>".pg_last_error()."<br>";
							echo "<p class= 'subtitulo1'>Comando SQL </p> <br>".$cons."<br/>";
							
						}
					
			}
			
			
			
			
		
		
		function migrarCUPSXPlanes($paso) {
		
			/* Tabla ContratacionSalud.PlanesTarifarios */
			normalizarCodificacionPlanesTarifarios(utf8_encode("Á"),'&Aacute;');			
			normalizarCodificacionPlanesTarifarios(utf8_encode("É"),'&Eacute;');
			normalizarCodificacionPlanesTarifarios(utf8_encode("Í"),'&Iacute;');
			normalizarCodificacionPlanesTarifarios(utf8_encode("Ó"),'&Oacute;');
			normalizarCodificacionPlanesTarifarios(utf8_encode("Ú"),'&Uacute;');
			normalizarCodificacionPlanesTarifarios(utf8_encode("Ñ"),'&Ntilde;');
			
			crearTablaMigracionCUPSXPlanes();
			eliminarCUPSXPlanesMigracion();
			eliminarCUPSXPlanes();
			
			llamarRegistrosMySQL("ConfLaboratorios");
			llenarMatriz("ConfLaboratorios");
			recorrerMatriz();
			
						
			llamarRegistrosMySQL("ConfProcedimientos");
			llenarMatriz("ConfProcedimientos");
			recorrerMatriz();
			
		
			llamarRegistrosMySQL("ConfTransporte");
			llenarMatriz("ConfTransporte");
			recorrerMatriz();
			
			llamarRegistrosMySQL("AtencionConsExterna");
			llenarMatriz("AtencionConsExterna");
			recorrerMatriz();
			
			llamarRegistrosMySQL("ConFacEstancia");
			llenarMatriz1("ConFacEstancia");
			recorrerMatriz1();
			
			llamarRegistrosMySQL("ConFacAtMd");
			llenarMatriz1("ConFacAtMd");
			recorrerMatriz1();
			
			CupsISS2001();
			
			// Tabla ContratacionSalud.PlanesTarifarios
			normalizarCodificacionPlanesTarifarios('&Aacute;', utf8_encode("Á"));			
			normalizarCodificacionPlanesTarifarios('&Eacute;', utf8_encode("É"));
			normalizarCodificacionPlanesTarifarios('&Iacute;', utf8_encode("Í"));
			normalizarCodificacionPlanesTarifarios('&Oacute;', utf8_encode("Ó"));
			normalizarCodificacionPlanesTarifarios('&Uacute;',utf8_encode("Ú"));
			normalizarCodificacionPlanesTarifarios('&Ntilde;',utf8_encode("Ñ"));
			
			// Tabla ContratacionSalud.CUPSXPlanes
			normalizarCodificacionCUPSxPlanes('&Aacute;', utf8_encode("Á"));			
			normalizarCodificacionCUPSxPlanes('&Eacute;', utf8_encode("É"));
			normalizarCodificacionCUPSxPlanes('&Iacute;', utf8_encode("Í"));
			normalizarCodificacionCUPSxPlanes('&Oacute;', utf8_encode("Ó"));
			normalizarCodificacionCUPSxPlanes('&Uacute;',utf8_encode("Ú"));
			normalizarCodificacionCUPSxPlanes('&Ntilde;',utf8_encode("Ñ"));
			echo "<p class='mensajeEjecucion'> <span class = 'subtitulo1'>Paso $paso: </span> Se ha migrado la tabla ContratacionSalud.CUPSXPlanes </p> ";
	
		}
		
		
		
		
		
	
	
	
	?>
