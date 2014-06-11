	<html>	
		<head>
			<title> Migracion Formato Notas SIAU </title>
			<link rel="stylesheet" type="text/css" href="../../General/estilos/estilos.css">
		</head>
	
	
	<?php
		session_start();
		include_once('../../General/funciones/funciones.php');
		include_once('../../Conexiones/conexion.php');
		include_once('../General/procedimiento.php');
		
		
		
		
		
		// Inicia definicion de funciones 
		
		
		function contarRegistrosMySQL() {
			$cnx = conectar_mysql("Salud");
			$cons = "SELECT COUNT(*) AS conteomysql  FROM Salud.NotasSIAU";
			$res =  mysql_query($cons);
			$fila = mysql_fetch_array($res);
			$res = $fila['conteomysql'];
			return $res; 	
		
		}
		
		
		
		
		
		function contarRegistrosPostgresql() {
			global $tablafrm;
			$cnx= conectar_postgres();
			$cons = "SELECT COUNT(*) AS conteo FROM HistoClinicaFrms.$tablafrm";
			$res =  pg_query($cnx, $cons);
			$fila = pg_fetch_array($res);
			$res = $fila['conteo'];
			return $res; 	
		
		}
		
		
		function contarRegistrosPostgresqlErrores() {
			global $tablafrm;
			$tablamigracion = $tablafrm."migracion";
			$cnx= conectar_postgres();
			$cons = "SELECT COUNT(*) AS conteo FROM HistoClinicaFrms.$tablamigracion";
			$res =  pg_query($cnx, $cons);
			$fila = pg_fetch_array($res);
			$res = $fila['conteo'];
			return $res; 	
		
		}
		
		
			
		function  normalizarCodificacion($cadenaBusqueda,$cadenaReemplazo)  {
		// Busca y reemplaza ocurrencias en una tabla
			global $tablafrm;
			$cnx= conectar_postgres();
			$cons = "UPDATE HistoClinicaFrms.$tablafrm SET formato = replace( formato,'$cadenaBusqueda','$cadenaReemplazo'), tipoformato = replace( tipoformato,'$cadenaBusqueda','$cadenaReemplazo') , compania = replace( compania,'$cadenaBusqueda','$cadenaReemplazo'), cargo = replace( cargo,'$cadenaBusqueda','$cadenaReemplazo') , ambito = replace( ambito,'$cadenaBusqueda','$cadenaReemplazo'), unidadhosp = replace( unidadhosp,'$cadenaBusqueda','$cadenaReemplazo') , finalidadconsult = replace( finalidadconsult,'$cadenaBusqueda','$cadenaReemplazo') , dx1 = replace( dx1,'$cadenaBusqueda','$cadenaReemplazo') , dx2 = replace( dx2,'$cadenaBusqueda','$cadenaReemplazo') , dx3 = replace( dx3,'$cadenaBusqueda','$cadenaReemplazo') , dx4 = replace( dx4,'$cadenaBusqueda','$cadenaReemplazo') , dx5 = replace( dx5,'$cadenaBusqueda','$cadenaReemplazo'),   cmp00003=replace(cmp00003,'$cadenaBusqueda','$cadenaReemplazo'), cmp00004=replace(cmp00004,'$cadenaBusqueda','$cadenaReemplazo')";
			$res = @pg_query($cnx , $cons);
				if (!$res)  {
					echo "<p class='error1'> Error en la normalizacion de la codificacion </p>".pg_last_error()."<br>";
					echo "<p class= 'subtitulo1'>Comando SQL </p> <br>".$cons."<br/>";
				}

		}
		
		function crearArchivoErrores() {
		// Crea un archivo HTML donde se documentaran los registros que no se insertaron en la tabla de migraciones
			global $tablafrm;
			$archivo = "Errores".$tablafrm.".html";
			$fp = fopen("$archivo", "w+");
			$encabezado = "<html> <head> <title> Reporte errores Formato Primera Vez Medicina General </title> 
			<link rel='stylesheet' type='text/css' href='../../General/estilos/estilos.css'> </head>";
			fputs($fp, $encabezado);
			fclose($fp);
		}
		
			function llamarRegistrosMySQL() {
			// Selecciona los registros MySQL (Origen)
			global $res;
			$cnx = conectar_mysql("Salud");
			$cons = "SELECT  NotasSIAU.*, TIME(Fecha) AS hora  FROM Salud.NotasSIAU ORDER BY Fecha ASC";
			$res =  @mysql_query($cons);
			if (!$res)  {
					echo "<p class='error1'> Error en la normalizacion de la codificacion </p>".mysql_error()."<br>";
					echo "<p class= 'subtitulo1'>Comando SQL </p> <br>".$cons."<br/>";
				}
			return $res; 
		
		}
		
		function creartablaMigracion() {
		// Esta funcion crea una tabla con estructura similar a la tabla Postgresql con la diferencia que carece de llave primaria y omite la restriccion NOT NULL,          ademas  crea un nuevo campo llamado error en el cual se almacena la descripcion del error generado en la consulta inicial.
			global  $tablafrm;
			$cnx= conectar_postgres();
			$tablaMig = $tablafrm."Migracion";
			$cons = "CREATE TABLE IF NOT EXISTS histoclinicafrms.$tablaMig(  formato character varying(150) ,  tipoformato character varying(150) ,  id_historia integer  ,  usuario character varying(150) ,  cargo character varying(80) ,  fecha date,  hora time without time zone,  cedula character varying(15) ,  ambito character varying(150),  unidadhosp character varying(150),  numservicio integer,  compania character varying(60) ,  cerrado integer,  noliquidacion integer ,  finalidadconsult character varying(5),  causaexterna character varying(5),  dx1 character varying(6),  dx2 character varying(6),  dx3 character varying(6),  dx4 character varying(6),  dx5 character varying(6),  tipodx character varying(1),  numproced integer,  usuarioajuste character varying(30),  fechaajuste date,  padretipoformato character varying(150),  padreformato character varying(150),  id_historia_origen integer,  cmp00002 date,  cmp00003 character varying(255),  cmp00004 character varying(255),  cmp00005 character varying(255),  idsvital numeric,error text)WITH (  OIDS=FALSE)";	
			$res = @pg_query($cnx, $cons);
				if (!$res) {
				
					echo "<p class='error1'> Error de ejecucion </p>".pg_last_error()."<br>";
					echo "<p class= 'subtitulo1'>Comando SQL </p> <br>".$cons."<br>"; 				
					echo "<br><br>";			
					//echo "<br><br>";
				}
			
		}
		
		
		function insertarFormatos() {
		
				$cnx = conectar_postgres();
				$ruta = $_SERVER['DOCUMENT_ROOT'];
				$cons= "COPY HistoriaClinica.Formatos FROM '$ruta/Migraciones/HistoriaClinicaFrms/Administrativos/NotasSIAU/Formatos.csv' WITH DELIMITER ';' CSV HEADER;";
				$res =  @pg_query($cons);
					if (!$res) {
						echo "<p class='error1'> Error de ejecucion </p>".pg_last_error()."<br>";
						echo "<p class= 'subtitulo1'>Comando SQL </p> <br>".$cons."<br/>";
							
					}
			
			}
			
			
			function insertarItemsxFormatos() {
		
				$cnx = conectar_postgres();
				$ruta = $_SERVER['DOCUMENT_ROOT'];
				$cons= "COPY HistoriaClinica.ItemsxFormatos FROM '$ruta/Migraciones/HistoriaClinicaFrms/Administrativos/NotasSIAU/ItemsxFormatos.csv' WITH DELIMITER ';' CSV HEADER;";
				$res =  @pg_query($cons);
					if (!$res) {
						echo "<p class='error1'> Error de ejecucion </p>".pg_last_error()."<br>";
						echo "<p class= 'subtitulo1'>Comando SQL </p> <br>".$cons."<br/>";
							
					}
			
			}
			
			
			
			
			function insertarPermisosxFormato() {
		
				$cnx = conectar_postgres();
				$ruta = $_SERVER['DOCUMENT_ROOT'];
				$cons= "COPY HistoriaClinica.PermisosxFormato FROM '$ruta/Migraciones/HistoriaClinicaFrms/Administrativos/NotasSIAU/PermisosxFormato.csv' WITH DELIMITER ';' CSV HEADER;";
				$res =  @pg_query($cons);
					if (!$res) {
						echo "<p class='error1'> Error de ejecucion </p>".pg_last_error()."<br>";
						echo "<p class= 'subtitulo1'>Comando SQL </p> <br>".$cons."<br/>";
							
					}
			
			}
			
						
			function insertarAmbitosxFormato() {
		
				$cnx = conectar_postgres();
				$ruta = $_SERVER['DOCUMENT_ROOT'];
				$cons= "COPY HistoriaClinica.AmbitosxFormato FROM '$ruta/Migraciones/HistoriaClinicaFrms/Administrativos/NotasSIAU/AmbitosxFormato.csv' WITH DELIMITER ';' CSV HEADER;";
				$res =  @pg_query($cons);
					if (!$res) {
						echo "<p class='error1'> Error de ejecucion </p>".pg_last_error()."<br>";
						echo "<p class= 'subtitulo1'>Comando SQL </p> <br>".$cons."<br/>";
							
					}
			
			}
			
			function insertarDxFormatos() {
		
				$cnx = conectar_postgres();
				$ruta = $_SERVER['DOCUMENT_ROOT'];
				$cons= "COPY HistoriaClinica.DxFormatos FROM '$ruta/Migraciones/HistoriaClinicaFrms/Administrativos/NotasSIAU/DxFormatos.csv' WITH DELIMITER ';' CSV HEADER;";
				$res =  @pg_query($cons);
					if (!$res) {
						echo "<p class='error1'> Error de ejecucion </p>".pg_last_error()."<br>";
						echo "<p class= 'subtitulo1'>Comando SQL </p> <br>".$cons."<br/>";
							
					}
			
			}
			
			
			function insertarCupsxFormatos() {
		
				$cnx = conectar_postgres();
				$ruta = $_SERVER['DOCUMENT_ROOT'];
				$cons= "COPY HistoriaClinica.CupsxFormatos FROM '$ruta/Migraciones/HistoriaClinicaFrms/Administrativos/NotasSIAU/CupsxFormatos.csv' WITH DELIMITER ';' CSV HEADER;";
				$res =  @pg_query($cons);
					if (!$res) {
						echo "<p class='error1'> Error de ejecucion </p>".pg_last_error()."<br>";
						echo "<p class= 'subtitulo1'>Comando SQL </p> <br>".$cons."<br/>";
							
					}
			
			}
			
			
			
			
			function insertarVoBoxFormatos() {
		
				$cnx = conectar_postgres();
				$ruta = $_SERVER['DOCUMENT_ROOT'];
				$cons= "COPY HistoriaClinica.VoBoxFormatos FROM '$ruta/Migraciones/HistoriaClinicaFrms/Administrativos/NotasSIAU/VoBoxFormatos.csv' WITH DELIMITER ';' CSV HEADER;";
				$res =  @pg_query($cons);
					if (!$res) {
						echo "<p class='error1'> Error de ejecucion </p>".pg_last_error()."<br>";
						echo "<p class= 'subtitulo1'>Comando SQL </p> <br>".$cons."<br/>";
							
					}
			
			}
			
			
			function insertarAjustePermanente() {
		
				$cnx = conectar_postgres();
				$ruta = $_SERVER['DOCUMENT_ROOT'];
				$cons= "COPY HistoriaClinica.AjustePermanente FROM '$ruta/Migraciones/HistoriaClinicaFrms/Administrativos/NotasSIAU/AjustePermanente.csv' WITH DELIMITER ';' CSV HEADER;";
				$res =  @pg_query($cons);
					if (!$res) {
						echo "<p class='error1'> Error de ejecucion </p>".pg_last_error()."<br>";
						echo "<p class= 'subtitulo1'>Comando SQL </p> <br>".$cons."<br/>";
							
					}
			
			}


			
			function crearTablaFormato($tabla){
				$cnx = conectar_postgres();				
				$cons= "CREATE TABLE histoclinicafrms.$tabla(  formato character varying(150) NOT NULL,  tipoformato character varying(150) NOT NULL,  id_historia integer NOT NULL DEFAULT 0,  usuario character varying(150) NOT NULL,  cargo character varying(80) NOT NULL,  fecha date,  hora time without time zone,  cedula character varying(15) NOT NULL,  ambito character varying(150),  unidadhosp character varying(150),  numservicio integer,  compania character varying(60) NOT NULL,  cerrado integer,  noliquidacion integer DEFAULT 0,  finalidadconsult character varying(5),  causaexterna character varying(5),  dx1 character varying(6),  dx2 character varying(6),  dx3 character varying(6),  dx4 character varying(6),  dx5 character varying(6),  tipodx character varying(1),  numproced integer,  usuarioajuste character varying(30),  fechaajuste date,  padretipoformato character varying(150),  padreformato character varying(150),  id_historia_origen integer,  cmp00002 date,  cmp00003 character varying(255),  cmp00004 character varying(255),  cmp00005 character varying(255),  idsvital numeric,  CONSTRAINT pkhctbl$tabla PRIMARY KEY (formato , tipoformato , id_historia , cedula , compania ),  CONSTRAINT fkambtbl$tabla FOREIGN KEY (ambito, compania)      REFERENCES salud.ambitos (ambito, compania) MATCH SIMPLE      ON UPDATE CASCADE ON DELETE RESTRICT,  CONSTRAINT fkitemsxtbl$tabla FOREIGN KEY (formato, tipoformato, compania)      REFERENCES historiaclinica.formatos (formato, tipoformato, compania) MATCH SIMPLE      ON UPDATE CASCADE ON DELETE RESTRICT,  CONSTRAINT fkpabxtbl$tabla FOREIGN KEY (unidadhosp, compania, ambito)      REFERENCES salud.pabellones (pabellon, compania, ambito) MATCH SIMPLE      ON UPDATE CASCADE ON DELETE RESTRICT)WITH (  OIDS=FALSE)";		  
				$res = @pg_query($cnx,$cons);				
				if (!$res) {							
					echo "<p class='error1'> Error de ejecucion </p>".pg_last_error()."<br>";
					echo "<p class= 'subtitulo1'>Comando SQL </p> <br>".$cons."<br/>";	
				}
			
		}
		
		
		
		
		
		
		
		
		function insertarRegistroMigracion($formato,$tipoformato,$id_historia,$usuario_rm,$cargo,$fecha,$hora,$cedula,$ambito,$unidadhosp,$numservicio,$compania,$cerrado , $noliquidacion,$finalidadconsult,$causaexterna,$dx1,$dx2,$dx3,$dx4,$dx5,$cmp00002, $cmp00003, $cmp00004, $cmp00005, $error) {
		//Realiza la insercion en Postgresql con base en los parametros
			global  $tablafrm;
			$tablaMig = $tablafrm."Migracion";
			$cnx = 	conectar_postgres();
			$cons = "INSERT INTO HistoClinicaFrms.$tablaMig (formato,tipoformato,id_historia,usuario,cargo,fecha,hora,cedula,ambito,unidadhosp,numservicio,compania,cerrado, noliquidacion,finalidadconsult,causaexterna,dx1,dx2,dx3,dx4,dx5,cmp00002, cmp00003, cmp00004, cmp00005, error ) VALUES ('$formato','$tipoformato','$id_historia','$usuario_rm','$cargo','$fecha','$hora','$cedula','$ambito','$unidadhosp',$numservicio,'$compania',$cerrado, '$noliquidacion','$finalidadconsult','$causaexterna','$dx1','$dx2','$dx3','$dx4','$dx5','$cmp00002', '$cmp00003', '$cmp00004', '$cmp00005', '$error')"	;
					 
			$cons = str_replace( "'NULL'","NULL",$cons  );	
			$res = @pg_query($cnx, $cons);
				if (!$res) {
					$consUTF8 = utf8_encode($cons);
					$resUTF8 = @pg_query($cnx, $consUTF8);					
						if (!$resUTF8) {
							$archivo = "Errores".$tablafrm.".html";
							$fp = fopen("$archivo", "a+");	
							$errorEjecucion= "<p class='error1'> Error de ejecucion </p>".pg_last_error()."<br>";
							$consulta= "<p class= 'subtitulo1'>Comando SQL </p> <br>".$consUTF8."<br/> <br/> <br/>";  
							fputs($fp, $errorEjecucion);
							fputs($fp, $consulta);
							fclose($fp);
							
							
						}
				
				}

				
		}
		
		
		
		
		function insertarRegistroPostgresql($formato,$tipoformato,$id_historia,$usuario_rm,$cargo,$fecha,$hora,$cedula,$ambito,$unidadhosp,$numservicio,$compania,$cerrado , $noliquidacion,$finalidadconsult,$causaexterna,$dx1,$dx2,$dx3,$dx4,$dx5,$cmp00002, $cmp00003, $cmp00004, $cmp00005) {
		//Realiza la insercion en Postgresql con base en los parametros
			global  $tablafrm;
			$cnx = 	conectar_postgres();
			$cons = "INSERT INTO HistoClinicaFrms.$tablafrm (formato,tipoformato,id_historia,usuario,cargo,fecha,hora,cedula,ambito,unidadhosp,numservicio,compania,cerrado, noliquidacion,finalidadconsult,causaexterna,dx1,dx2,dx3,dx4,dx5,cmp00002, cmp00003, cmp00004, cmp00005) VALUES ('$formato','$tipoformato','$id_historia','$usuario_rm','$cargo','$fecha','$hora','$cedula','$ambito','$unidadhosp',$numservicio,'$compania',$cerrado, '$noliquidacion','$finalidadconsult','$causaexterna','$dx1','$dx2','$dx3','$dx4','$dx5','$cmp00002', '$cmp00003', '$cmp00004', '$cmp00005')"	;
					 
			$cons = str_replace( "'NULL'","NULL",$cons  );	
			$res = @pg_query($cnx, $cons);
				if (!$res) {
					$consUTF8 = utf8_encode($cons);
					$resUTF8 = @pg_query($cnx, $consUTF8);					
						if (!$resUTF8) {
							$error = pg_last_error();
							insertarRegistroMigracion($formato,$tipoformato,$id_historia,$usuario_rm,$cargo,$fecha,$hora,$cedula,$ambito,$unidadhosp,$numservicio,$compania,$cerrado , $noliquidacion,$finalidadconsult,$causaexterna,$dx1,$dx2,$dx3,$dx4,$dx5,$cmp00002, $cmp00003, $cmp00004, $cmp00005, $error) ;					
							
						}
				
				}

				
		}

		
		
		
		
		
		
		function  llenarMatriz(){
		// Llena una matriz con el resultado de la consulta MySQL
			
			unset($matriz); 
			global  $matriz;	
			$res = llamarRegistrosMySQL();
			$posicion=0;
				
				while ($fila = mysql_fetch_array($res))
				{	
					
					$matriz["usuario"][$posicion] = $fila["Usuario"];
					$matriz["cedula"][$posicion] = $fila["Cedula"];
					$matriz["cargo"][$posicion] = $fila["Cargo"];
					$matriz["fecha"][$posicion] = $fila["Fecha"];
					$matriz["hora"][$posicion] = $fila["hora"];
					$matriz["cmp00002"][$posicion] = $fila["FechaReg"];	
					$matriz["cmp00003"][$posicion] = $fila["Folio"];	
					$matriz["cmp00004"][$posicion] = $fila["Nota"];	
					
														
					$posicion++;				
				}
							
				
			}
			
		
			
			function recorrerMatriz()  {
			// Recorre la matriz con los valores MySQL  inserta los valores en Postgresql
			
				global $res,$matriz, $tablafrm, $tipoformato, $formato ;
					for($pos=0;$pos < mysql_num_rows($res); $pos++)  {
					
					
					
					$compania = $_SESSION["compania"];
					
					$usuario=$matriz["usuario"][$pos];					
					$cargo = consultarCargoMedMySQL($usuario);					
					$rm = consultarRegMedMySQL($usuario);
					
					$usuario = eliminarCaracteresEspeciales($usuario);
					$usuario = normalizarMedicos($usuario);
									
					$cargo =normalizarCargos($cargo);
					$cargo = eliminarCaracteresEspeciales($cargo);
					
					$rm = eliminarCaracteresEspeciales($rm);
					
					$usuario_rm =  configurarRM($usuario, $rm, $cargo);
					
					
					
					$fecha=$matriz["fecha"][$pos];
						if ($fecha == "0000-00-00 00:00" or $fecha == "0000-00-00" ) {
							$fecha = 'NULL';
						}
						elseif ($fecha == "2005-11-31 00:00" or $fecha == "2005-11-31" ) {
							$fecha = '2005-11-30';
						}
						elseif ($fecha == "2006-02-29 00:00" or $fecha == "2006-02-29" ) {
							$fecha = '2006-02-28';
						}
						elseif ($fecha == "2006-04-31 00:00" or $fecha == "2006-04-31" ) {
							$fecha = '2006-04-30';
						}
						elseif ($fecha == "2005-04-31 00:00" or $fecha == "2005-04-31" ) {
							$fecha = '2005-04-30';
						}
						elseif ($fecha == "2007-09-31 00:00" or $fecha == "2007-09-31" ) {
							$fecha = '2007-09-30';
						}
						elseif ($fecha == "2008-00-31 00:00" or $fecha == "2008-00-31" ) {
							$fecha = '2008-01-31';
						}
						elseif ($fecha == "2010-00-16 00:00" or $fecha == "2010-00-16" ) {
							$fecha = '2010-01-16';
						}
						elseif ($fecha == "2011-00-28 00:00" or $fecha == "2011-00-28" ) {
							$fecha = '2011-01-28';
						}
						elseif ($fecha == "2011-00-30 00:00" or $fecha == "2011-00-30" ) {
							$fecha = '2011-01-30';
						}
						elseif ($fecha == "2011-09-00 00:00" or $fecha == "2011-09-00" ) {
							$fecha = '2011-09-01';
						}
					
					$hora=$matriz["hora"][$pos];	
						if ($hora == "00:00"  ) {
							$hora = 'NULL';
						}
					
					$cedula=$matriz["cedula"][$pos];
					$cedula = eliminarCaracteresEspeciales($cedula);
					
					$unidadhosp=seleccionarPabellon($cedula,$fecha);
					$unidadhosp = normalizarPabellones($unidadhosp)	;
					$ambito =seleccionarAmbito($unidadhosp);

					if (trim($unidadhosp) == ""){
						$unidadhosp = 'NULL'	;
					}
					
					if (trim($ambito) == ""){
						$ambito = 'NULL'	;
					}
					
					$numservicio=  seleccionarServicio($cedula, $fecha);
						if ($numservicio == "" ){
							$numservicio = 0;
						}
					
					$dx1= 'NULL';
					
					$cmp00002=$matriz["cmp00002"][$pos];
					$cmp00002 = eliminarCaracteresEspFormatos($cmp00002);
						if ($cmp00002 == "0000-00-00 00:00" or $cmp00002 == "0000-00-00" ) {
							$cmp00002 = 'NULL';
						}
						elseif ($cmp00002 == "2005-11-31 00:00" or $cmp00002 == "2005-11-31" ) {
							$cmp00002 = '2005-11-30';
						}
						elseif ($cmp00002 == "2006-02-29 00:00" or $cmp00002 == "2006-02-29" ) {
							$cmp00002 = '2006-02-28';
						}
						elseif ($cmp00002 == "2006-04-31 00:00" or $cmp00002 == "2006-04-31" ) {
							$cmp00002 = '2006-04-30';
						}
						elseif ($cmp00002 == "2005-04-31 00:00" or $cmp00002 == "2005-04-31" ) {
							$cmp00002 = '2005-04-30';
						}
						elseif ($cmp00002 == "2007-09-31 00:00" or $cmp00002 == "2007-09-31" ) {
							$cmp00002 = '2007-09-30';
						}
						elseif ($cmp00002 == "2008-00-31 00:00" or $cmp00002 == "2008-00-31" ) {
							$cmp00002 = '2008-01-31';
						}
						elseif ($cmp00002 == "2010-00-16 00:00" or $cmp00002 == "2010-00-16" ) {
							$cmp00002 = '2010-01-16';
						}
						elseif ($cmp00002 == "2011-00-28 00:00" or $cmp00002 == "2011-00-28" ) {
							$cmp00002 = '2011-01-28';
						}
						elseif ($cmp00002 == "2011-00-30 00:00" or $cmp00002 == "2011-00-30" ) {
							$cmp00002 = '2011-01-30';
						}
						elseif ($cmp00002 == "2011-09-00 00:00" or $cmp00002 == "2011-09-00" ) {
							$cmp00002 = '2011-09-01';
						}
					
					$cmp00003=$matriz["cmp00003"][$pos];
					$cmp00003 = eliminarCaracteresEspFormatos($cmp00003);
					
					$cmp00004=$matriz["cmp00004"][$pos];
					$cmp00004 = eliminarCaracteresEspFormatos($cmp00004);
					
					$cmp00005 = 'NULL';
					
					
					$cerrado = 'NULL';
					$noliquidacion = 'NULL';
					
					$id_historia = $pos +1 ;
					
					$cerrado = 'NULL';
					$numproced = 'NULL';
					
					insertarRegistroPostgresql($formato,$tipoformato,$id_historia,$usuario_rm,$cargo,$fecha,$hora,$cedula,$ambito,$unidadhosp,$numservicio,$compania,$cerrado , $noliquidacion,$finalidadconsult,$causaexterna,$dx1,$dx2,$dx3,$dx4,$dx5,$cmp00002, $cmp00003, $cmp00004, $cmp00005);
							
					}
			
			}
			
			function eliminarRegistros() {
				global $tablafrm;
				$cnx= conectar_postgres();
				$cons= "DELETE FROM HistoClinicaFrms.$tablafrm";
				$res = @pg_query($cnx, $cons);
				if (!$res) {
							echo "<p class='error1'> Error de ejecucion </p>".pg_last_error()."<br>";
							echo "<p class= 'subtitulo1'>Comando SQL </p> <br>".$cons."<br/>";
							
						}
					
			}
			
			function eliminarRegistrosMigracion() {
				global  $tablafrm;
				$tablaMig = $tablafrm."Migracion";
				$cnx= conectar_postgres();
				$cons= "DELETE FROM HistoClinicaFrms.$tablaMig";
				$res = @pg_query($cnx, $cons);
				if (!$res) {
							echo "<p class='error1'> Error de ejecucion </p>".pg_last_error()."<br>";
							echo "<p class= 'subtitulo1'>Comando SQL </p> <br>".$cons."<br/>";
							
						}
					
			}
			
		
			
			function migrarRegistros(){
				creartablaMigracion();
				eliminarRegistrosMigracion();
				eliminarRegistros();
				crearArchivoErrores();
				llenarMatriz();
				recorrerMatriz();
				//Tabla Formato Autonomo 
				normalizarCodificacion('&Aacute;', utf8_encode("Á"));			
				normalizarCodificacion('&Eacute;', utf8_encode("É"));
				normalizarCodificacion('&Iacute;', utf8_encode("Í"));
				normalizarCodificacion('&Oacute;', utf8_encode("Ó"));
				normalizarCodificacion('&Uacute;',utf8_encode("Ú"));
				normalizarCodificacion('&Ntilde;',utf8_encode("Ñ"));
				//actualizarMedico("HistoClinicaFrms", "" , $campo, $valorBusqueda, $valorReemplazo)
			
			
			}
			
			
		
		
		function migrarEstructura() {
			
			// Inician definiciones generales
			
			
			global $tablafrm, $tipoformato , $formato ;			
			
			$compania = $_SESSION["compania"];				
			$tablaMig = $tablafrm."Migracion";
			
			
			
			// Tabla FrmMigracion (tbl00001Migracion o la que corresponda)
			eliminarTablaFormato($tablaMig);
			crearTablaMigracion($tablafrm);
			
			// Tabla Frm (tbl00001 o la que corresponda)
			
			eliminarTablaFormato($tablafrm);
			crearTablaFormato($tablafrm);
			
			
			
			//HistoriaClinica.Formatos
			eliminarFormatos($tipoformato, $formato);
			insertarFormatos();
			
			// ItemsxFormatos
			eliminarItemsxFormatos($tipoformato, $formato);
			insertarItemsxFormatos();
			
			//PermisosxFormato
			eliminarPermisosxFormato($tipoformato, $formato);
			insertarPermisosxFormato();
			
			//VoBoxFormatos
			
			eliminarVoBoxFormatos($tipoformato, $formato);
			//insertarVoBoxFormatos();
			
			// CupsxFormatos
			
			eliminarCupsxFormatos($tipoformato, $formato);
			//insertarCupsxFormatos();
			
			
			// Ajuste Permanente
			
			eliminarAjustePermanente($tipoformato, $formato);
			insertarAjustePermanente();
			
			
			// AmbitosxFormato
			eliminarAmbitosxFormato($tipoformato, $formato);
			
			$usuariocre = 'ADMINISTRADOR';
			$fechacre = FechaActual();
			$ambito = 'NULL';
			$disponible = "Si";
			insertarAmbitosxFormatoSQL($usuariocre, $fechacre, $tipoformato, $formato, $ambito, $disponible, $compania);
			
			// DxFormatos
			eliminarDxFormatos($formato, $tipoformato);
			
			/*$usuariocre = "ADMINISTRADOR";
			$fechacre = FechaActual();
			$usuario = $usuariocre;
			$fecha = $fechacre;
			$id = 1;
			$detalle = "DIAGNOSTICO PRINCIPAL";
			$tipo = "Principal";
			$estado = "AC";
			$pantalla = 1;
			$iditem = 10;
			$cie10 = "1";
			$tagxml = 'NULL';
			$etiquetaxml = 'NULL';
			
			insertarDxFormatosSQL($compania, $usuario, $fecha, $id, $detalle, $tipo, $formato, $tipoformato, $estado, $pantalla, $iditem, $cie10, $tagxml, $etiquetaxml);
			
			$id = 2;
			$detalle = "DIAGNOSTICO RELACIONADO";
			$tipo = "Relacionado";
			$estado = "AC";
			$pantalla = 1;
			$iditem = 10;
			$cie10 = "1";
			$tagxml = 'NULL';
			$etiquetaxml = 'NULL';
			
			insertarDxFormatosSQL($compania, $usuario, $fecha, $id, $detalle, $tipo, $formato, $tipoformato, $estado, $pantalla, $iditem, $cie10, $tagxml, $etiquetaxml);
			*/
			
			
			echo "<div align='center'> <p class='mensajeFinalizacion'>Ha terminado la migracion del Formato 'Notas SIAU'</p> </div>";
	
		}
		
		
		
		
		
		
		
		if($_GET['formato']="NotasSIAU") {
		
				echo "<fieldset>";			
				echo "<legend> Migracion tabla MySQL </legend>";
				echo "<br>";
				echo "<span align='left'> <a href='../../index.php?migracion=MIG054' class = 'link1'> Panel de Administracion </a> </span>";
				echo "<br>";
				
				global $tablafrm, $tipoformato, $formato ;
				$tablafrm = "tbl00036";
				$tipoformato = "ADMINISTRATIVOS";
				$formato = "NOTAS SIAU";
				$archivo = "Errores".$tablafrm.".html";				
				
				
				migrarEstructura();
				migrarRegistros();
				
					$totalMySQL = contarRegistrosMySQL();
					$totalPostgresql =  contarRegistrosPostgresql();
					$totalPostgresqlErrores =  contarRegistrosPostgresqlErrores();
					
					echo "<p class= 'subtitulo1'> Total registros MySQL:</p>";
					echo  $totalMySQL."<br/>";
					echo "<p class= 'subtitulo1'> Total registros Postgresql migrados:</p>";
					echo  $totalPostgresql."<br/>";
					echo "<p class= 'error1'> Total errores generados(Tabla HistoClinicaFrms.$tablafrm.Migracion):</p>";
					echo  $totalPostgresqlErrores."<br/>";
					
					?><p> <a href='<?php echo $archivo; ?>' class = 'link1' target='_blank'> Ver Reporte de errores de la migracion </a> </p><?php
					
					echo "</fieldset>";
			
			}
		
		
		
	
	
	
	?>
