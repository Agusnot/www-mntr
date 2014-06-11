	<html>	
		<head>
			<title> Migracion Formato Triage </title>
			<link rel="stylesheet" type="text/css" href="../../General/estilos/estilos.css">
		</head>
	
	
	<?php
		session_start();
		include_once('../../General/funciones/funciones.php');
		include_once('../../Conexiones/conexion.php');
		include_once('../General/procedimiento.php');
		
		
		
		
		
		// Inicia definicion de funciones 
		
		function normalizarTriage($cadena){
			global $tablafrm;
			$cadena = strtoupper($cadena);
			$cadena = trim($cadena)	;
			$validacionTriage = 0;
			
			$validacion1 = strpos($cadena, "I ROJO");
			$validacion2 =  strpos($cadena, "II AMARILLO");
			$validacion3 =  strpos($cadena, "III VERDE");
			$validacion4 =  strpos($cadena, "IV VERDE");
			
				if($validacion1 !== FALSE){
					$nivelTriage = 1;
					$validacionTriage++;
				}
				
				if($validacion2 !== FALSE){
					$nivelTriage = 2;
					$validacionTriage++;
				}
				
				if($validacion3 !== FALSE){
					$nivelTriage = 3;
					$validacionTriage++;
				}
				
				if($validacion4 !== FALSE){
					$nivelTriage = 4;
					$validacionTriage++;
				}
				
				// Si no encuentra ninguna ocurrencia de alguna de las cadenas
				if ($validacionTriage== 0){
					$nivelTriage = 'NULL';
				}
				
				if ($validacionTriage > 1){
					$archivo = "Errores".$tablafrm.".html";
							$fp = fopen("$archivo", "a+");	
							$errorEjecucion= "<p class='error1'> Error de ejecucion </p> <br>";
							$consulta= "<p class= 'subtitulo1'> Ambiguedad en la definicion de prioridad del Triage </p> <br/> <br/> <br/>";  
							fclose($fp);
					
				}
				
				return $nivelTriage;
			
			
		
		}
		
		
		
		
		function contarRegistrosMySQL() {
			$cnx = conectar_mysql("Salud");
			$cons = "SELECT COUNT(*) AS conteomysql  FROM Salud.Triage";
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
			$cons = "UPDATE HistoClinicaFrms.$tablafrm SET formato = replace( formato,'$cadenaBusqueda','$cadenaReemplazo'), tipoformato = replace( tipoformato,'$cadenaBusqueda','$cadenaReemplazo') , compania = replace( compania,'$cadenaBusqueda','$cadenaReemplazo'), cargo = replace( cargo,'$cadenaBusqueda','$cadenaReemplazo') , ambito = replace( ambito,'$cadenaBusqueda','$cadenaReemplazo'), unidadhosp = replace( unidadhosp,'$cadenaBusqueda','$cadenaReemplazo') , finalidadconsult = replace( finalidadconsult,'$cadenaBusqueda','$cadenaReemplazo') , dx1 = replace( dx1,'$cadenaBusqueda','$cadenaReemplazo') , dx2 = replace( dx2,'$cadenaBusqueda','$cadenaReemplazo') , dx3 = replace( dx3,'$cadenaBusqueda','$cadenaReemplazo') , dx4 = replace( dx4,'$cadenaBusqueda','$cadenaReemplazo') , dx5 = replace( dx5,'$cadenaBusqueda','$cadenaReemplazo'),   cmp00003=replace(cmp00003,'$cadenaBusqueda','$cadenaReemplazo'),cmp00005=replace(cmp00005,'$cadenaBusqueda','$cadenaReemplazo'),cmp00007=replace(cmp00007,'$cadenaBusqueda','$cadenaReemplazo'),cmp00009=replace(cmp00009,'$cadenaBusqueda','$cadenaReemplazo'),cmp00011=replace(cmp00011,'$cadenaBusqueda','$cadenaReemplazo'), cmp00013=replace(cmp00013,'$cadenaBusqueda','$cadenaReemplazo'), cmp00015=replace(cmp00015,'$cadenaBusqueda','$cadenaReemplazo')";
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
			$cons = "SELECT *  FROM Salud.Triage ";
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
			$cons = "CREATE TABLE IF NOT EXISTS histoclinicafrms.$tablaMig (formato character varying(150) ,  tipoformato character varying(150) ,  id_historia integer  ,  usuario character varying(150) ,  cargo character varying(80) ,  fecha date,  hora time without time zone,  cedula character varying(15) ,  ambito character varying(150),  unidadhosp character varying(150),  numservicio integer,  compania character varying(60) ,  cerrado integer,  noliquidacion integer ,  finalidadconsult character varying(5),  causaexterna character varying(5),  dx1 character varying(6),  dx2 character varying(6),  dx3 character varying(6),  dx4 character varying(6),  dx5 character varying(6),  tipodx character varying(1),  numproced integer,  usuarioajuste character varying(30),  fechaajuste date,  padretipoformato character varying(150),  padreformato character varying(150),  id_historia_origen integer,  cmp00003 text,  cmp00005 text,  cmp00007 text,  cmp00009 text,  cmp00011 text,  cmp00013 text,  cmp00015 character varying(255), error text)WITH (  OIDS=FALSE);";	
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
				$cons= "COPY HistoriaClinica.Formatos FROM '$ruta/Migraciones/HistoriaClinicaFrms/HistoriaClinica/Triage/Formatos.csv' WITH DELIMITER ';' CSV HEADER;";
				$res =  @pg_query($cons);
					if (!$res) {
						echo "<p class='error1'> Error de ejecucion </p>".pg_last_error()."<br>";
						echo "<p class= 'subtitulo1'>Comando SQL </p> <br>".$cons."<br/>";
							
					}
			
			}
			
			
			function insertarItemsxFormatos() {
		
				$cnx = conectar_postgres();
				$ruta = $_SERVER['DOCUMENT_ROOT'];
				$cons= "COPY HistoriaClinica.ItemsxFormatos FROM '$ruta/Migraciones/HistoriaClinicaFrms/HistoriaClinica/Triage/ItemsxFormatos.csv' WITH DELIMITER ';' CSV HEADER;";
				$res =  @pg_query($cons);
					if (!$res) {
						echo "<p class='error1'> Error de ejecucion </p>".pg_last_error()."<br>";
						echo "<p class= 'subtitulo1'>Comando SQL </p> <br>".$cons."<br/>";
							
					}
			
			}
			
			
			
			
			function insertarPermisosxFormato() {
		
				$cnx = conectar_postgres();
				$ruta = $_SERVER['DOCUMENT_ROOT'];
				$cons= "COPY HistoriaClinica.PermisosxFormato FROM '$ruta/Migraciones/HistoriaClinicaFrms/HistoriaClinica/Triage/PermisosxFormato.csv' WITH DELIMITER ';' CSV HEADER;";
				$res =  @pg_query($cons);
					if (!$res) {
						echo "<p class='error1'> Error de ejecucion </p>".pg_last_error()."<br>";
						echo "<p class= 'subtitulo1'>Comando SQL </p> <br>".$cons."<br/>";
							
					}
			
			}
			
						
			function insertarAmbitosxFormato() {
		
				$cnx = conectar_postgres();
				$ruta = $_SERVER['DOCUMENT_ROOT'];
				$cons= "COPY HistoriaClinica.AmbitosxFormato FROM '$ruta/Migraciones/HistoriaClinicaFrms/HistoriaClinica/Triage/AmbitosxFormato.csv' WITH DELIMITER ';' CSV HEADER;";
				$res =  @pg_query($cons);
					if (!$res) {
						echo "<p class='error1'> Error de ejecucion </p>".pg_last_error()."<br>";
						echo "<p class= 'subtitulo1'>Comando SQL </p> <br>".$cons."<br/>";
							
					}
			
			}
			
			function insertarDxFormatos() {
		
				$cnx = conectar_postgres();
				$ruta = $_SERVER['DOCUMENT_ROOT'];
				$cons= "COPY HistoriaClinica.DxFormatos FROM '$ruta/Migraciones/HistoriaClinicaFrms/HistoriaClinica/Triage/DxFormatos.csv' WITH DELIMITER ';' CSV HEADER;";
				$res =  @pg_query($cons);
					if (!$res) {
						echo "<p class='error1'> Error de ejecucion </p>".pg_last_error()."<br>";
						echo "<p class= 'subtitulo1'>Comando SQL </p> <br>".$cons."<br/>";
							
					}
			
			}
			
			
			function insertarCupsxFormatos() {
		
				$cnx = conectar_postgres();
				$ruta = $_SERVER['DOCUMENT_ROOT'];
				$cons= "COPY HistoriaClinica.CupsxFormatos FROM '$ruta/Migraciones/HistoriaClinicaFrms/HistoriaClinica/Triage/CupsxFormatos.csv' WITH DELIMITER ';' CSV HEADER;";
				$res =  @pg_query($cons);
					if (!$res) {
						echo "<p class='error1'> Error de ejecucion </p>".pg_last_error()."<br>";
						echo "<p class= 'subtitulo1'>Comando SQL </p> <br>".$cons."<br/>";
							
					}
			
			}
			
			
			
			
			function insertarVoBoxFormatos() {
		
				$cnx = conectar_postgres();
				$ruta = $_SERVER['DOCUMENT_ROOT'];
				$cons= "COPY HistoriaClinica.VoBoxFormatos FROM '$ruta/Migraciones/HistoriaClinicaFrms/HistoriaClinica/Triage/VoBoxFormatos.csv' WITH DELIMITER ';' CSV HEADER;";
				$res =  @pg_query($cons);
					if (!$res) {
						echo "<p class='error1'> Error de ejecucion </p>".pg_last_error()."<br>";
						echo "<p class= 'subtitulo1'>Comando SQL </p> <br>".$cons."<br/>";
							
					}
			
			}
			
			
			function insertarAjustePermanente() {
		
				$cnx = conectar_postgres();
				$ruta = $_SERVER['DOCUMENT_ROOT'];
				$cons= "COPY HistoriaClinica.AjustePermanente FROM '$ruta/Migraciones/HistoriaClinicaFrms/HistoriaClinica/Triage/AjustePermanente.csv' WITH DELIMITER ';' CSV HEADER;";
				$res =  @pg_query($cons);
					if (!$res) {
						echo "<p class='error1'> Error de ejecucion </p>".pg_last_error()."<br>";
						echo "<p class= 'subtitulo1'>Comando SQL </p> <br>".$cons."<br/>";
							
					}
			
			}


			
			function crearTablaFormato($tabla){
				$cnx = conectar_postgres();				
				$cons= "CREATE TABLE histoclinicafrms.$tabla(  formato character varying(150) NOT NULL,  tipoformato character varying(150) NOT NULL,  id_historia integer NOT NULL DEFAULT 0,  usuario character varying(150) NOT NULL,  cargo character varying(80) NOT NULL,  fecha date,  hora time without time zone,  cedula character varying(15) NOT NULL,  ambito character varying(150),  unidadhosp character varying(150),  numservicio integer,  compania character varying(60) NOT NULL,  cerrado integer,  noliquidacion integer DEFAULT 0,  finalidadconsult character varying(5),  causaexterna character varying(5),  dx1 character varying(6),  dx2 character varying(6),  dx3 character varying(6),  dx4 character varying(6),  dx5 character varying(6),  tipodx character varying(1),  numproced integer,  usuarioajuste character varying(30),  fechaajuste date,  padretipoformato character varying(150),  padreformato character varying(150),  id_historia_origen integer,  cmp00003 text,  cmp00005 text,  cmp00007 text,  cmp00009 text,  cmp00011 text,  cmp00013 text,  cmp00015 character varying(255),  CONSTRAINT pkhctbl$tabla PRIMARY KEY (formato , tipoformato , id_historia , cedula , compania ),  CONSTRAINT fkambtbl$tabla FOREIGN KEY (ambito, compania)      REFERENCES salud.ambitos (ambito, compania) MATCH SIMPLE      ON UPDATE CASCADE ON DELETE RESTRICT,  CONSTRAINT fkitemsxtbl$tabla FOREIGN KEY (formato, tipoformato, compania)      REFERENCES historiaclinica.formatos (formato, tipoformato, compania) MATCH SIMPLE      ON UPDATE CASCADE ON DELETE RESTRICT,  CONSTRAINT fkpabxtbl$tabla FOREIGN KEY (unidadhosp, compania, ambito)      REFERENCES salud.pabellones (pabellon, compania, ambito) MATCH SIMPLE      ON UPDATE CASCADE ON DELETE RESTRICT)WITH (  OIDS=FALSE);";	
	  			$res = @pg_query($cnx,$cons);		
				if (!$res) {							
				echo "<p class='error1'> Error de ejecucion </p>".pg_last_error()."<br>";
				echo "<p class= 'subtitulo1'>Comando SQL </p> <br>".$cons."<br/>";
	
				}
			
		}
		
		
		
		
		
		
		
		
		function insertarRegistroMigracion($formato,$tipoformato,$id_historia,$usuario,$cargo,$fecha,$hora,$cedula,$ambito,$unidadhosp,$numservicio,$compania,$cerrado , $noliquidacion,$finalidadconsult,$causaexterna,$dx1,$dx2,$dx3,$dx4,$dx5,$cmp00003,$cmp00005,$cmp00007, $cmp00009,$cmp00011, $cmp00013, $cmp00015, $error ) {
		//Realiza la insercion en Postgresql con base en los parametros
			global  $tablafrm;
			$tablaMig = $tablafrm."Migracion";
			$cnx = 	conectar_postgres();
			$cons = "INSERT INTO HistoClinicaFrms.$tablaMig (formato,tipoformato,id_historia,usuario,cargo,fecha,hora,cedula,ambito,unidadhosp,numservicio,compania,cerrado, noliquidacion,finalidadconsult,causaexterna,dx1,dx2,dx3,dx4,dx5,cmp00003,cmp00005,cmp00007, cmp00009, cmp00011, cmp00013, cmp00015, error) VALUES ('$formato','$tipoformato','$id_historia','$usuario','$cargo','$fecha','$hora','$cedula','$ambito','$unidadhosp',$numservicio,'$compania',$cerrado, '$noliquidacion','$finalidadconsult','$causaexterna','$dx1','$dx2','$dx3','$dx4','$dx5','$cmp00003','$cmp00005','$cmp00007', '$cmp00009', '$cmp00011', '$cmp00013', '$cmp00015', '$error')"	;
					 
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
		
		
		
		
		function insertarRegistroPostgresql($formato,$tipoformato,$id_historia,$usuario,$cargo,$fecha,$hora,$cedula,$ambito,$unidadhosp,$numservicio,$compania,$cerrado , $noliquidacion,$finalidadconsult,$causaexterna,$dx1,$dx2,$dx3,$dx4,$dx5,$cmp00003,$cmp00005,$cmp00007, $cmp00009,$cmp00011, $cmp00013, $cmp00015 ) {
		//Realiza la insercion en Postgresql con base en los parametros
			global  $tablafrm;
			$cnx = 	conectar_postgres();
			$cons = "INSERT INTO HistoClinicaFrms.$tablafrm (formato,tipoformato,id_historia,usuario,cargo,fecha,hora,cedula,ambito,unidadhosp,numservicio,compania,cerrado, noliquidacion,finalidadconsult,causaexterna,dx1,dx2,dx3,dx4,dx5,cmp00003,cmp00005,cmp00007, cmp00009, cmp00011, cmp00013, cmp00015) VALUES ('$formato','$tipoformato','$id_historia','$usuario','$cargo','$fecha','$hora','$cedula','$ambito','$unidadhosp',$numservicio,'$compania',$cerrado, '$noliquidacion','$finalidadconsult','$causaexterna','$dx1','$dx2','$dx3','$dx4','$dx5','$cmp00003','$cmp00005','$cmp00007', '$cmp00009', '$cmp00011', '$cmp00013', '$cmp00015')"	;
					 
			$cons = str_replace( "'NULL'","NULL",$cons  );	
			$res = @pg_query($cnx, $cons);
				if (!$res) {
					$consUTF8 = utf8_encode($cons);
					$resUTF8 = @pg_query($cnx, $consUTF8);					
						if (!$resUTF8) {
							$error = pg_last_error();
							insertarRegistroMigracion($formato,$tipoformato,$id_historia,$usuario,$cargo,$fecha,$hora,$cedula,$ambito,$unidadhosp,$numservicio,$compania,$cerrado , $noliquidacion,$finalidadconsult,$causaexterna,$dx1,$dx2,$dx3,$dx4,$dx5,$cmp00003,$cmp00005,$cmp00007, $cmp00009,$cmp00011, $cmp00013, $cmp00015, $error ) ;					
							
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
					$matriz["rm"][$posicion] = $fila["RM"];
					$matriz["cedula"][$posicion] = $fila["Cedula"];
					$matriz["cargo"][$posicion] = $fila["Cargo"];
					$matriz["fecha"][$posicion] = $fila["Fecha"];
					$matriz["hora"][$posicion] = $fila["Hora"];
					$matriz["dx1"][$posicion] = $fila["CodDxPpal"];
					$matriz["fecha"][$posicion] = $fila["Fecha"];
					
					$matriz["cmp00003"][$posicion] = $fila["MotivoConsulta"];
					$matriz["cmp00007"][$posicion] = $fila["SignosVitales"];
					$matriz["cmp00009"][$posicion] = $fila["ExamenMental"];
					$matriz["cmp00011"][$posicion] = $fila["Analisis"];
					$matriz["cmp00013"][$posicion] = $fila["CodDxPpal"]." ".$fila["DxPpal"];					
					$matriz["cmp00015"][$posicion] = $fila["Triage"];
					$matriz["usuariocre"][$posicion] = $fila["Usuario"];
					$matriz["fechacre"][$posicion] = $fila["Fecha"];
					$matriz["requierevobo"][$posicion] = $fila["RequiereVoBo"];
					$matriz["cargovobo"][$posicion] = $fila["CargoVoBo"];
					$matriz["rmvobo"][$posicion] = $fila["RMVoBo"];
					
														
					$posicion++;				
				}
							
				
			}
			
		
			
			function recorrerMatriz()  {
			// Recorre la matriz con los valores MySQL  inserta los valores en Postgresql
			
				global $res,$matriz, $tablafrm, $tipoformato, $formato ;
					for($pos=0;$pos < mysql_num_rows($res); $pos++)  {
									
					$compania = $_SESSION["compania"];				
					
					$usuario=$matriz["usuario"][$pos];
					$usuario = normalizarMedicos($usuario);
					$usuario = eliminarCaracteresEspeciales($usuario);
					
					$cargo= $matriz["cargo"][$pos];
					$cargo = normalizarCargos($cargo);				
					$cargo = eliminarCaracteresEspeciales($cargo);
					
					
					$rm=$matriz["rm"][$pos];
					$rm = eliminarCaracteresEspeciales($rm);
					
					$usuario_rm =  configurarRM($usuario, $rm, $cargo);
					
					
					$fecha=$matriz["fecha"][$pos];
						if ($fecha == "0000-00-00 00:00" or $fecha == "0000-00-00" ) {
							$fecha = 'NULL';
						}
					$hora=$matriz["hora"][$pos];
					
					$cedula=$matriz["cedula"][$pos];
					
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
					$compania= $_SESSION["compania"];
					$dx1=$matriz["dx1"][$pos];
					$dx1 = eliminarCaracteresEspeciales($dx1);
					
					$cmp00003 = $matriz["cmp00003"][$pos];					
					$cmp00003 = eliminarCaracteresEspFormatos($cmp00003);				
					
					$cmp00005='NULL'; // Se deja NULL porque en la base de datos MySQL no existe ese campo
					
					$cmp00007=$matriz["cmp00007"][$pos];
					$cmp00007 = eliminarCaracteresEspFormatos($cmp00007);
					
					$cmp00009=$matriz["cmp00009"][$pos];
					$cmp00009 = eliminarCaracteresEspFormatos($cmp00009);
					
					$cmp00011=$matriz["cmp00011"][$pos];
					$cmp00011 = eliminarCaracteresEspFormatos($cmp00011);
					
					$cmp00013=$matriz["cmp00013"][$pos];
					$cmp00013 = eliminarCaracteresEspFormatos($cmp00013);
					
					$cmp00015=$matriz["cmp00015"][$pos];
					$cmp00015 = eliminarCaracteresEspeciales($cmp00015);
					$cmp00015 = normalizarTriage($cmp00015);
					
					$usuariocre=$matriz["usuariocre"][$pos];
					$fechacre=$matriz["fechacre"][$pos];
					
					$compania= $_SESSION["compania"];
					$cerrado = 'NULL';
					$noliquidacion = 'NULL';
					
					$id_historia = $pos +1 ;
					
					$cerrado = 'NULL';
					$numproced = 'NULL';
					
					$requierevobo= $matriz["requierevobo"][$pos];
					$requierevobo = eliminarCaracteresEspeciales($requierevobo);
					
					$cargovobo= $matriz["cargovobo"][$pos];
					$cargovobo = normalizarCargos($cargovobo);
					$cargovobo = eliminarCaracteresEspeciales($cargovobo);
					
					$rmvobo=$matriz["rmvobo"][$pos];
					$rmvobo = eliminarCaracteresEspeciales($rmvobo);
					
					$usuariovobo_rm =  configurarRM($requierevobo, $rmvobo, $cargovobo);
					
					
					$fechavobo = $matriz["fechavobo"][$pos];
					
					
					
					
					insertarRegistroPostgresql($formato,$tipoformato,$id_historia,$usuario_rm,$cargo,$fecha,$hora,$cedula,$ambito,$unidadhosp,$numservicio,$compania,$cerrado , $noliquidacion,$finalidadconsult,$causaexterna,$dx1,$dx2,$dx3,$dx4,$dx5,$cmp00003,$cmp00005,$cmp00007, $cmp00009,$cmp00011, $cmp00013, $cmp00015 );
					
						if ($requierevobo != "0" and  strtoupper($requierevobo) != "PSIQUIATRA" and trim ($requierevobo) != "" ){
							if ($fechavobo == "0000-00-00 00:00:00" or  $fechavobo == "0000-00-00"){
								$fechavobo = $fecha;	
							}
							insertarRegistroVoBoxFormatos($usuariovobo_rm, $fechavobo, $tipoformato, $formato, $cargovobo, $compania, $id_historia);
													
						}	
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
			
		
			
			function migrarRegistrosTriage(){
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
			
			
		
		
		function migrarEstructuraTriage() {
			
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
			
			
			echo "<div align='center'> <p class='mensajeFinalizacion'>Ha terminado la migracion del Formato 'Triage'</p> </div>";
	
		}
		
		
		
		
		
		
		
		if($_GET['formato']="Triage") {
		
				echo "<fieldset>";			
				echo "<legend> Migracion tabla MySQL </legend>";
				echo "<br>";
				echo "<span align='left'> <a href='../../index.php?migracion=MIG019' class = 'link1'> Panel de Administracion </a> </span>";
				echo "<br>";
				
				global $tablafrm, $tipoformato, $formato ;
				$tablafrm = "tbl00001";
				$tipoformato = "HISTORIA CLINICA";
				$formato = "TRIAGE";
				$archivo = "Errores".$tablafrm.".html";				
				
				
				migrarEstructuraTriage();
				migrarRegistrosTriage();
				
					$totalMySQL = contarRegistrosMySQL();
					$totalPostgresql =  contarRegistrosPostgresql();
					$totalPostgresqlErrores =  contarRegistrosPostgresqlErrores();
					
					echo "<p class= 'subtitulo1'> Total registros MySQL:</p>";
					echo  $totalMySQL."<br/>";
					echo "<p class= 'subtitulo1'> Total registros Postgresql migrados:</p>";
					echo  $totalPostgresql."<br/>";
					echo "<p class= 'error1'> Total errores generados(Tabla HistoClinicaFrms.$tablafrm.Migracion):</p>";
					echo  $totalPostgresqlErrores."<br/>";
						?>
					<p> <a href="<?php echo $archivo; ?>" class = 'link1' target='_blank'> Ver Reporte de errores de la migracion </a> </p>
						<?php
					
					echo "</fieldset>";
			
			}
		
		
		
	
	
	
	?>
