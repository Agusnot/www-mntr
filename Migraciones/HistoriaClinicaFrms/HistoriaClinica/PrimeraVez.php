	<html>	
		<head>
			<title> Migracion Formato Hoja de Ingreso </title>
			<link rel="stylesheet" type="text/css" href="../../General/estilos/estilos.css">
		</head>
	
	
	<?php
		session_start();
		include_once('../../General/funciones/funciones.php');
		include_once('../../Conexiones/conexion.php');
		include_once('../General/procedimiento.php');
		
		
		
		
		function contarRegistrosMySQL() {
			$cnx = conectar_mysql("Salud");
			$cons = "SELECT COUNT(*)  AS conteomysql FROM Salud.PsiqGeneral WHERE UPPER(tipoformato) = '1A VEZ'";
			$res =  mysql_query($cons);
			$fila = mysql_fetch_array($res);
			$res = $fila['conteomysql'];
			return $res; 	
		
		}
		
		
		function contarRegistrosPostgresql() {
			global  $tablafrm;
			$cnx= conectar_postgres();
			$cons = "SELECT COUNT(*) AS conteo FROM HistoClinicaFrms.$tablafrm ";
			$res =  pg_query($cnx, $cons);
			$fila = pg_fetch_array($res);
			$res = $fila['conteo'];
			return $res; 	
		
		}
		
		function contarRegistrosPostgresqlErrores() {
			global $tablafrm;
			$tablaMig = $tablafrm."Migracion";
			$cnx= conectar_postgres();
			$cons = "SELECT COUNT(*) AS conteo FROM HistoClinicaFrms.$tablaMig";
			$res =  pg_query($cnx, $cons);
			$fila = pg_fetch_array($res);
			$res = $fila['conteo'];
			return $res; 	
		
		}
		
		
		
		
		function insertarFormatos() {
		
				$cnx = conectar_postgres();
				$ruta = $_SERVER['DOCUMENT_ROOT'];
				$cons= "COPY HistoriaClinica.Formatos FROM '$ruta/Migraciones/HistoriaClinicaFrms/HistoriaClinica/HojaIngreso/Formatos.csv' WITH DELIMITER ';' CSV HEADER;";
				$res =  @pg_query($cons);
					if (!$res) {
						echo "<p class='error1'> Error de ejecucion </p>".pg_last_error()."<br>";
						echo "<p class= 'subtitulo1'>Comando SQL </p> <br>".$cons."<br/>";
							
					}
			
			}
			
			
			function insertarItemsxFormatos() {
		
				$cnx = conectar_postgres();
				$ruta = $_SERVER['DOCUMENT_ROOT'];
				$cons= "COPY HistoriaClinica.ItemsxFormatos FROM '$ruta/Migraciones/HistoriaClinicaFrms/HistoriaClinica/HojaIngreso/ItemsxFormatos.csv' WITH DELIMITER ';' CSV HEADER;";
				$res =  @pg_query($cons);
					if (!$res) {
						echo "<p class='error1'> Error de ejecucion </p>".pg_last_error()."<br>";
						echo "<p class= 'subtitulo1'>Comando SQL </p> <br>".$cons."<br/>";
							
					}
			
			}
			
			
			
			
			function insertarPermisosxFormato() {
		
				$cnx = conectar_postgres();
				$ruta = $_SERVER['DOCUMENT_ROOT'];
				$cons= "COPY HistoriaClinica.PermisosxFormato FROM '$ruta/Migraciones/HistoriaClinicaFrms/HistoriaClinica/HojaIngreso/PermisosxFormato.csv' WITH DELIMITER ';' CSV HEADER;";
				$res =  @pg_query($cons);
					if (!$res) {
						echo "<p class='error1'> Error de ejecucion </p>".pg_last_error()."<br>";
						echo "<p class= 'subtitulo1'>Comando SQL </p> <br>".$cons."<br/>";
							
					}
			
			}
			
						
			function insertarAmbitosxFormato() {
		
				$cnx = conectar_postgres();
				$ruta = $_SERVER['DOCUMENT_ROOT'];
				$cons= "COPY HistoriaClinica.AmbitosxFormato FROM '$ruta/Migraciones/HistoriaClinicaFrms/HistoriaClinica/HojaIngreso/AmbitosxFormato.csv' WITH DELIMITER ';' CSV HEADER;";
				$res =  @pg_query($cons);
					if (!$res) {
						echo "<p class='error1'> Error de ejecucion </p>".pg_last_error()."<br>";
						echo "<p class= 'subtitulo1'>Comando SQL </p> <br>".$cons."<br/>";
							
					}
			
			}
			
			function insertarDxFormatos() {
		
				$cnx = conectar_postgres();
				$ruta = $_SERVER['DOCUMENT_ROOT'];
				$cons= "COPY HistoriaClinica.DxFormatos FROM '$ruta/Migraciones/HistoriaClinicaFrms/HistoriaClinica/HojaIngreso/DxFormatos.csv' WITH DELIMITER ';' CSV HEADER;";
				$res =  @pg_query($cons);
					if (!$res) {
						echo "<p class='error1'> Error de ejecucion </p>".pg_last_error()."<br>";
						echo "<p class= 'subtitulo1'>Comando SQL </p> <br>".$cons."<br/>";
							
					}
			
			}
			
			
			function insertarCupsxFormatos() {
		
				$cnx = conectar_postgres();
				$ruta = $_SERVER['DOCUMENT_ROOT'];
				$cons= "COPY HistoriaClinica.CupsxFormatos FROM '$ruta/Migraciones/HistoriaClinicaFrms/HistoriaClinica/HojaIngreso/CupsxFormatos.csv' WITH DELIMITER ';' CSV HEADER;";
				$res =  @pg_query($cons);
					if (!$res) {
						echo "<p class='error1'> Error de ejecucion </p>".pg_last_error()."<br>";
						echo "<p class= 'subtitulo1'>Comando SQL </p> <br>".$cons."<br/>";
							
					}
			
			}
			
			
			
			
			function insertarVoBoxFormatos() {
		
				$cnx = conectar_postgres();
				$ruta = $_SERVER['DOCUMENT_ROOT'];
				$cons= "COPY HistoriaClinica.VoBoxFormatos FROM '$ruta/Migraciones/HistoriaClinicaFrms/HistoriaClinica/HojaIngreso/VoBoxFormatos.csv' WITH DELIMITER ';' CSV HEADER;";
				$res =  @pg_query($cons);
					if (!$res) {
						echo "<p class='error1'> Error de ejecucion </p>".pg_last_error()."<br>";
						echo "<p class= 'subtitulo1'>Comando SQL </p> <br>".$cons."<br/>";
							
					}
			
			}
			
			
			function insertarAjustePermanente() {
		
				$cnx = conectar_postgres();
				$ruta = $_SERVER['DOCUMENT_ROOT'];
				$cons= "COPY HistoriaClinica.AjustePermanente FROM '$ruta/Migraciones/HistoriaClinicaFrms/HistoriaClinica/HojaIngreso/AjustePermanente.csv' WITH DELIMITER ';' CSV HEADER;";
				$res =  @pg_query($cons);
					if (!$res) {
						echo "<p class='error1'> Error de ejecucion </p>".pg_last_error()."<br>";
						echo "<p class= 'subtitulo1'>Comando SQL </p> <br>".$cons."<br/>";
							
					}
			
			}



		
		
		
		// Inicia definicion de funciones 
		
			
		function  normalizarCodificacionFormato($cadenaBusqueda,$cadenaReemplazo)  {
		// Busca y reemplaza ocurrencias en una tabla
			global $tablafrm;
			$cnx= conectar_postgres();
			$cons = "UPDATE HistoClinicaFrms.$tablafrm SET formato = replace( formato,'$cadenaBusqueda','$cadenaReemplazo'), tipoformato = replace( tipoformato,'$cadenaBusqueda','$cadenaReemplazo') , compania = replace( compania,'$cadenaBusqueda','$cadenaReemplazo'), cargo = replace( cargo,'$cadenaBusqueda','$cadenaReemplazo') , ambito = replace( ambito,'$cadenaBusqueda','$cadenaReemplazo'), unidadhosp = replace( unidadhosp,'$cadenaBusqueda','$cadenaReemplazo') , finalidadconsult = replace( finalidadconsult,'$cadenaBusqueda','$cadenaReemplazo') , dx1 = replace( dx1,'$cadenaBusqueda','$cadenaReemplazo') , dx2 = replace( dx2,'$cadenaBusqueda','$cadenaReemplazo') , dx3 = replace( dx3,'$cadenaBusqueda','$cadenaReemplazo') , dx4 = replace( dx4,'$cadenaBusqueda','$cadenaReemplazo') , dx5 = replace( dx5,'$cadenaBusqueda','$cadenaReemplazo'),   cmp00003=replace(cmp00003,'$cadenaBusqueda','$cadenaReemplazo'),cmp00005=replace(cmp00005,'$cadenaBusqueda','$cadenaReemplazo'),cmp00007=replace(cmp00007,'$cadenaBusqueda','$cadenaReemplazo'),cmp00009=replace(cmp00009,'$cadenaBusqueda','$cadenaReemplazo'),cmp00011=replace(cmp00011,'$cadenaBusqueda','$cadenaReemplazo'),cmp00013=replace(cmp00013,'$cadenaBusqueda','$cadenaReemplazo'), cmp00015=replace(cmp00015,'$cadenaBusqueda','$cadenaReemplazo') , cmp00017 =replace(cmp00017,'$cadenaBusqueda','$cadenaReemplazo'), cmp00019=replace(cmp00019,'$cadenaBusqueda','$cadenaReemplazo') , cmp00021=replace(cmp00021,'$cadenaBusqueda','$cadenaReemplazo'), cmp00023=replace(cmp00023,'$cadenaBusqueda','$cadenaReemplazo'), cmp00025=replace(cmp00025,'$cadenaBusqueda','$cadenaReemplazo'), cmp00027=replace(cmp00027,'$cadenaBusqueda','$cadenaReemplazo'), cmp00029=replace(cmp00029,'$cadenaBusqueda','$cadenaReemplazo'), cmp00031=replace(cmp00031,'$cadenaBusqueda','$cadenaReemplazo'), cmp00033=replace(cmp00033,'$cadenaBusqueda','$cadenaReemplazo'), cmp00035=replace(cmp00035,'$cadenaBusqueda','$cadenaReemplazo'), cmp00037=replace(cmp00037,'$cadenaBusqueda','$cadenaReemplazo'), cmp00039=replace(cmp00039,'$cadenaBusqueda','$cadenaReemplazo'), cmp00041=replace(cmp00041,'$cadenaBusqueda','$cadenaReemplazo'), cmp00043=replace(cmp00043,'$cadenaBusqueda','$cadenaReemplazo'), cmp00045=replace(cmp00045,'$cadenaBusqueda','$cadenaReemplazo'), cmp00048=replace(cmp00048,'$cadenaBusqueda','$cadenaReemplazo')";
			
			$res = @pg_query($cnx , $cons);
				if (!$res)  {
					echo "<p class='error1'> Error en la normalizacion de la codificacion </p>".pg_last_error()."<br>";
					echo "<p class= 'subtitulo1'>Comando SQL </p> <br>".$cons."<br/>";
				}

		}
		
		
		function  normalizarCodificacionItemsxFormatos($cadenaBusqueda,$cadenaReemplazo)  {
		// Busca y reemplaza ocurrencias en una tabla
			global $tablafrm;
			$cnx= conectar_postgres();
			$cons = "UPDATE HistoriaClinica.ItemsxFormatos SET  formato =replace(formato,'$cadenaBusqueda','$cadenaReemplazo'),  item =replace(item,'$cadenaBusqueda','$cadenaReemplazo'),  tipoformato =replace(tipoformato,'$cadenaBusqueda','$cadenaReemplazo'),  compania =replace(compania,'$cadenaBusqueda','$cadenaReemplazo')";
			
			$res = @pg_query($cnx , $cons);
				if (!$res)  {
					echo "<p class='error1'> Error en la normalizacion de la codificacion </p>".pg_last_error()."<br>";
					echo "<p class= 'subtitulo1'>Comando SQL </p> <br>".$cons."<br/>";
				}

		}
		
		function  normalizarCodificacionRequiereVoBo($cadenaBusqueda,$cadenaReemplazo)  {
		// Busca y reemplaza ocurrencias en una tabla
			global $tablafrm;
			$cnx= conectar_postgres();
			$cons = "UPDATE HistoriaClinica.RegistroVoBoxFormatos SET  usuario =replace(usuario,'$cadenaBusqueda','$cadenaReemplazo'),   tipoformato =replace(tipoformato,'$cadenaBusqueda','$cadenaReemplazo'), formato =replace(formato,'$cadenaBusqueda','$cadenaReemplazo') , cargo =replace(cargo,'$cadenaBusqueda','$cadenaReemplazo'),    compania =replace(compania,'$cadenaBusqueda','$cadenaReemplazo')";
			
			$res = @pg_query($cnx , $cons);
				if (!$res)  {
					echo "<p class='error1'> Error en la normalizacion de la codificacion </p>".pg_last_error()."<br>";
					echo "<p class= 'subtitulo1'>Comando SQL </p> <br>".$cons."<br/>";
				}

		}
		
		function  normalizarCodificacionMedicos($cadenaBusqueda,$cadenaReemplazo)  {
		// Busca y reemplaza ocurrencias en una tabla
			$cnx= conectar_postgres();
			$cons = "UPDATE Salud.Medicos SET usuario = replace( usuario,'$cadenaBusqueda','$cadenaReemplazo')";
			
			$res = pg_query($cnx , $cons);
				if (!$res)  {
					echo "<p class='error1'> Error en la normalizacion de la codificacion </p>".pg_last_error()."<br>";
					echo"<p class= 'subtitulo1'>Comando SQL </p> <br>".$cons."<br/> <br/> <br/>";  
				}

		}
		
		function crearArchivoErrores() {
		// Crea un archivo HTML donde se documentaran los registros que no se insertaron en la tabla de migraciones
			global $tablafrm;
			$archivo = "Errores".$tablafrm.".html";
			$fp = fopen("$archivo", "w+");
			$encabezado = "<html> <head> <title> Reporte errores Formato Hoja de Ingreso </title> 
			<link rel='stylesheet'type='text/css'href='../../General/estilos/estilos.css'> </head>";
			fputs($fp, $encabezado);
			fclose($fp);
		}
		
		function llamarRegistrosMySQL() {
			// Selecciona los registros MySQL (Origen)
			global $res;
			$cnx = conectar_mysql("Salud");
			$cons = "SELECT *  FROM Salud.PsiqGeneral WHERE UPPER(tipoformato) = '1A VEZ'  ORDER BY Fecha ASC, Hora ASC  ";
			$res =  @mysql_query($cons);
			if (!$res)  {
					echo "<p class='error1'> Error en la normalizacion de la codificacion </p>".mysql_error()."<br>";
					echo "<p class= 'subtitulo1'>Comando SQL </p> <br>".$cons."<br/>";
				}
			return $res; 
		
		}
		
		function llamarRegistrosMySQL2() {
			// Selecciona los registros MySQL (Origen)
			global $res;
			$cnx = conectar_mysql("Salud");
			$cons = "SELECT *  FROM Salud.Psiq1aVezInfantil  ORDER BY Fecha ASC, Hora ASC ";
			$res =  @mysql_query($cons);
			if (!$res)  {
					echo "<p class='error1'> Error en la normalizacion de la codificacion </p>".mysql_error()."<br>";
					echo "<p class= 'subtitulo1'>Comando SQL </p> <br>".$cons."<br/>";
				}
			return $res; 
		
		}
		
		
		


		function crearTablaFormato($tabla){
						$cnx = conectar_postgres();				
						$cons= "CREATE TABLE histoclinicafrms.$tabla(  formato character varying(150) NOT NULL,  tipoformato character varying(150) NOT NULL,  id_historia integer NOT NULL DEFAULT 0,  usuario character varying(150) NOT NULL,  cargo character varying(80) NOT NULL,  fecha date,  hora time without time zone,  cedula character varying(15) NOT NULL,  ambito character varying(150),  unidadhosp character varying(150),  numservicio integer,  compania character varying(60) NOT NULL,  cerrado integer,  noliquidacion integer DEFAULT 0,  finalidadconsult character varying(5),  causaexterna character varying(5),  dx1 character varying(6),  dx2 character varying(6),  dx3 character varying(6),  dx4 character varying(6),  dx5 character varying(6),  tipodx character varying(1),  numproced integer,  usuarioajuste character varying(30),  fechaajuste date,  padretipoformato character varying(150),  padreformato character varying(150),  id_historia_origen integer,  cmp00003 text,  cmp00005 text,  cmp00007 text,  cmp00009 text,  cmp00011 text,  cmp00013 text,  cmp00015 text,  cmp00017 text,  cmp00019 text,  cmp00021 text,  cmp00023 text,  cmp00025 text,  cmp00027 text,  cmp00029 text,  cmp00031 text,  cmp00033 text,  cmp00035 text,  cmp00037 text,  cmp00039 text,  cmp00041 text,  cmp00043 text,  cmp00045 text,  cmp00048 text,  CONSTRAINT pkhctbl$tabla PRIMARY KEY (formato , tipoformato , id_historia , cedula , compania ),  CONSTRAINT fkambtbl$tabla FOREIGN KEY (ambito, compania)      REFERENCES salud.ambitos (ambito, compania) MATCH SIMPLE      ON UPDATE CASCADE ON DELETE RESTRICT,  CONSTRAINT fkitemsxtbl$tabla FOREIGN KEY (formato, tipoformato, compania)      REFERENCES historiaclinica.formatos (formato, tipoformato, compania) MATCH SIMPLE      ON UPDATE CASCADE ON DELETE RESTRICT,  CONSTRAINT fkpabxtbl$tabla FOREIGN KEY (unidadhosp, compania, ambito)      REFERENCES salud.pabellones (pabellon, compania, ambito) MATCH SIMPLE      ON UPDATE CASCADE ON DELETE RESTRICT)WITH (  OIDS=FALSE);";	
						$res = @pg_query($cnx,$cons);		
						if (!$res) {							
						echo "<p class='error1'> Error de ejecucion </p>".pg_last_error()."<br>";
						echo "<p class= 'subtitulo1'>Comando SQL </p> <br>".$cons."<br/>";
			
						}
					
				}
		
		function creartablaMigracion() {
		// Esta funcion crea una tabla con estructura similar a la tabla Postgresql con la diferencia que carece de llave primaria y omite la restriccion NOT NULL,          ademas  crea un nuevo campo llamado error en el cual se almacena la descripcion del error generado en la consulta inicial.
			global  $tablafrm;
			$cnx= conectar_postgres();
			$tablaMig = $tablafrm."Migracion";
			$cons = "CREATE TABLE IF NOT EXISTS  histoclinicafrms.$tablaMig(  formato character varying(150) ,  tipoformato character varying(150) ,  id_historia integer  ,  usuario character varying(150) ,  cargo character varying(80) ,  fecha date,  hora time without time zone,  cedula character varying(15) ,  ambito character varying(150),  unidadhosp character varying(150),  numservicio integer,  compania character varying(60) ,  cerrado integer,  noliquidacion integer ,  finalidadconsult character varying(5),  causaexterna character varying(5),  dx1 character varying(6),  dx2 character varying(6),  dx3 character varying(6),  dx4 character varying(6),  dx5 character varying(6),  tipodx character varying(1),  numproced integer,  usuarioajuste character varying(30),  fechaajuste date,  padretipoformato character varying(150),  padreformato character varying(150),  id_historia_origen integer,  cmp00003 text,  cmp00005 text,  cmp00007 text,  cmp00009 text,  cmp00011 text,  cmp00013 text,  cmp00015 text,  cmp00017 text,  cmp00019 text,  cmp00021 text,  cmp00023 text,  cmp00025 text,  cmp00027 text,  cmp00029 text,  cmp00031 text,  cmp00033 text,  cmp00035 text,  cmp00037 text,  cmp00039 text,  cmp00041 text,  cmp00043 text,  cmp00045 text,  cmp00048 text,error text)WITH (  OIDS=FALSE);";	
			$res = @pg_query($cnx, $cons);
				if (!$res) {
				
					echo "<p class='error1'> Error de ejecucion </p>".pg_last_error()."<br>";
					echo "<p class= 'subtitulo1'>Comando SQL </p> <br>".$cons."<br>"; 				
					echo "<br><br>";			
					
				}
			
		}
		
		
		
		
		
		
		
		
		function insertarRegistroMigracion($formato,$tipoformato,$id_historia,$usuario,$cargo,$fecha,$hora,$cedula,$ambito,$unidadhosp,$numservicio,$compania,$cerrado , $noliquidacion,$finalidadconsult,$causaexterna,$dx1,$dx2,$dx3,$dx4,$dx5, $cmp00003,$cmp00005,$cmp00007,$cmp00009,$cmp00011,$cmp00013,$cmp00015,$cmp00017,$cmp00019,$cmp00021,$cmp00023,$cmp00025,$cmp00027,$cmp00029,$cmp00031,$cmp00033,$cmp00035,$cmp00037, $cmp00039,$cmp00041, $cmp00043 , $cmp00045, $cmp00048, $error) {
		//Realiza la insercion en Postgresql con base en los parametros
			global  $tablafrm;
			$tablaMig = $tablafrm."Migracion";
			$cnx = 	conectar_postgres();
			$cons = "INSERT INTO HistoClinicaFrms.$tablaMig (formato,tipoformato,id_historia,usuario,cargo,fecha,hora,cedula,ambito,unidadhosp,numservicio,compania,cerrado , noliquidacion,finalidadconsult,causaexterna,dx1,dx2,dx3,dx4,dx5,cmp00003,cmp00005,cmp00007,cmp00009,cmp00011,cmp00013,cmp00015,cmp00017,cmp00019,cmp00021,cmp00023,cmp00025,cmp00027,cmp00029,cmp00031,cmp00033,cmp00035,cmp00037, cmp00039, cmp00041, cmp00043, cmp00045, cmp00048, error) VALUES ('$formato','$tipoformato','$id_historia','$usuario','$cargo','$fecha','$hora','$cedula','$ambito','$unidadhosp',$numservicio,'$compania',$cerrado, '$noliquidacion','$finalidadconsult','$causaexterna','$dx1','$dx2','$dx3','$dx4','$dx5', '$cmp00003','$cmp00005','$cmp00007','$cmp00009','$cmp00011','$cmp00013','$cmp00015','$cmp00017','$cmp00019','$cmp00021','$cmp00023','$cmp00025','$cmp00027','$cmp00029','$cmp00031','$cmp00033','$cmp00035','$cmp00037', '$cmp00039','$cmp00041', '$cmp00043' , '$cmp00045', '$cmp00048', '$error')"	;
					 
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
		
		
		
		
		function insertarRegistroPostgresql($formato,$tipoformato,$id_historia,$usuario,$cargo,$fecha,$hora,$cedula,$ambito,$unidadhosp,$numservicio,$compania,$cerrado , $noliquidacion,$finalidadconsult,$causaexterna,$dx1,$dx2,$dx3,$dx4,$dx5, $cmp00003,$cmp00005,$cmp00007,$cmp00009,$cmp00011,$cmp00013,$cmp00015,$cmp00017,$cmp00019,$cmp00021,$cmp00023,$cmp00025,$cmp00027,$cmp00029,$cmp00031,$cmp00033,$cmp00035,$cmp00037, $cmp00039,$cmp00041, $cmp00043 , $cmp00045, $cmp00048) {
		//Realiza la insercion en Postgresql con base en los parametros
			global  $tablafrm;
			$cnx = 	conectar_postgres();
			$cons = "INSERT INTO HistoClinicaFrms.$tablafrm (formato,tipoformato,id_historia,usuario,cargo,fecha,hora,cedula,ambito,unidadhosp,numservicio,compania,cerrado , noliquidacion,finalidadconsult,causaexterna,dx1,dx2,dx3,dx4,dx5,cmp00003,cmp00005,cmp00007,cmp00009,cmp00011,cmp00013,cmp00015,cmp00017,cmp00019,cmp00021,cmp00023,cmp00025,cmp00027,cmp00029,cmp00031,cmp00033,cmp00035,cmp00037, cmp00039, cmp00041, cmp00043, cmp00045, cmp00048) VALUES ('$formato','$tipoformato','$id_historia','$usuario','$cargo','$fecha','$hora','$cedula','$ambito','$unidadhosp',$numservicio,'$compania',$cerrado, '$noliquidacion','$finalidadconsult','$causaexterna','$dx1','$dx2','$dx3','$dx4','$dx5', '$cmp00003','$cmp00005','$cmp00007','$cmp00009','$cmp00011','$cmp00013','$cmp00015','$cmp00017','$cmp00019','$cmp00021','$cmp00023','$cmp00025','$cmp00027','$cmp00029','$cmp00031','$cmp00033','$cmp00035','$cmp00037', '$cmp00039','$cmp00041', '$cmp00043' , '$cmp00045', '$cmp00048')"	;
			$cons = str_replace( "'NULL'","NULL",$cons  );	
			$res = @pg_query($cnx, $cons);
				if (!$res) {
					$consUTF8 = utf8_encode($cons);
					$resUTF8 = @pg_query($cnx, $consUTF8);					
						if (!$resUTF8) {
							$error = pg_last_error();
							$error = eliminarCaracteresEspeciales($error);
							insertarRegistroMigracion($formato,$tipoformato,$id_historia,$usuario,$cargo,$fecha,$hora,$cedula,$ambito,$unidadhosp,$numservicio,$compania,$cerrado , $noliquidacion,$finalidadconsult,$causaexterna,$dx1,$dx2,$dx3,$dx4,$dx5, $cmp00003,$cmp00005,$cmp00007,$cmp00009,$cmp00011,$cmp00013,$cmp00015,$cmp00017,$cmp00019,$cmp00021,$cmp00023,$cmp00025,$cmp00027,$cmp00029,$cmp00031,$cmp00033,$cmp00035,$cmp00037, $cmp00039,$cmp00041, $cmp00043 , $cmp00045, $cmp00048, $error);					
							
						}
				
				}

				
		}

		
		
		
		
		
		
		function  llenarMatriz(){
		// Llena una matriz con el resultado de la consulta MySQL
			
			unset($matriz); 
			global  $matriz, $res;	
			$res = llamarRegistrosMySQL();
			$posicion=0;
				
				while ($fila = mysql_fetch_array($res))
				{	
					
					$matriz["usuario"][$posicion] = $fila["Usuario"];
					$matriz["cedula"][$posicion] = $fila["Cedula"];
					$matriz["cargo"][$posicion] = $fila["Cargo"];
					$matriz["fecha"][$posicion] = $fila["Fecha"];
					$matriz["hora"][$posicion] = $fila["Hora"];					
					$matriz["rm"][$posicion] = $fila["RM"];					
					$matriz["dx1"][$posicion] = $fila["CodDxPpal"];
					$matriz["otrosdx"][$posicion] = $fila["OtrosDx"];	
					
					
					$matriz["cmp00003"][$posicion] = $fila["MotivoConsulta"];
					$matriz["cmp00005"][$posicion] = $fila["EnfActual"];
					
					$matriz["cmp00009"][$posicion] = $fila["PersonPrevia"];
					$matriz["cmp00011"][$posicion] = $fila["HistoPersonal"];
					$matriz["cmp00013"][$posicion] = $fila["Antecedentes"];
					$matriz["cmp00015"][$posicion] = $fila["HistoFamiliar"];
					$matriz["cmp00017"][$posicion] = $fila["AspectoGral"];
					$matriz["cmp00019"][$posicion] = $fila["CondMotora"];
					$matriz["cmp00021"][$posicion] = $fila["Afecto"];
					$matriz["cmp00023"][$posicion] = $fila["Pensamiento"];
					$matriz["cmp00025"][$posicion] = $fila["Sensopercepcion"];
					$matriz["cmp00027"][$posicion] = $fila["Orientacion"];					
					$matriz["cmp00029"][$posicion] = $fila["Atencion"];
					$matriz["cmp00031"][$posicion] = $fila["Memoria"];
					$matriz["cmp00033"][$posicion] = $fila["Introspeccion"];
					
					$matriz["cmp00037"][$posicion] = $fila["Inteligencia"];
					$matriz["cmp00041"][$posicion] = $fila["Prospeccion"];
					$matriz["cmp00043"][$posicion] = $fila["Juicio"];
					$matriz["cmp00045"][$posicion] = $fila["Conducta"];
					$matriz["cmp00048"][$posicion] = $fila["Conducta"];
					
					
					
					$matriz["requierevobo"][$posicion] = $fila["RequiereVoBo"];
					$matriz["cargovobo"][$posicion] = $fila["CargoVoBo"];
					$matriz["rmvobo"][$posicion] = $fila["RMVoBo"];
					$matriz["fechavobo"][$posicion] = $fila["FechaVoBo"];
					
														
					$posicion++;				
				}
							
				
			}
			
		
			
			function recorrerMatriz()  {
			// Recorre la matriz con los valores MySQL  inserta los valores en Postgresql
			
				global $res,$matriz, $tablafrm;
					for($pos=0;$pos <= mysql_num_rows($res); $pos++)  {
					
					global $tipoformato, $formato;
									
					$compania = $_SESSION["compania"];
					
					$usuario=$matriz["usuario"][$pos];
					$usuario = normalizarMedicos($usuario);
					$usuario = eliminarCaracteresEspeciales($usuario);
					
					$cargo = $matriz["cargo"][$pos];
					$cargo =normalizarCargos($cargo);
					$cargo = eliminarCaracteresEspeciales($cargo);
					
					$rm = $matriz["rm"][$pos];
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
					
					if (empty($fecha)){
						$fecha = 'NULL';
					}
					
					$numservicio=  seleccionarServicio($cedula, $fecha);
						if ($numservicio == "" ){
							$numservicio = 0;
						}
						
					
					
					$dx1=$matriz["dx1"][$pos];
					$dx1 = eliminarCaracteresEspeciales($dx1);
					
					$dx2 = 'NULL';
					
									
					$cmp00003=$matriz["cmp00003"][$pos];
					$cmp00003 = eliminarCaracteresEspFormatos($cmp00003);
					
					$cmp00005=$matriz["cmp00005"][$pos];
					$cmp00005 = eliminarCaracteresEspFormatos($cmp00005);
					
					$cmp00007='NULL'; // Se deja NULL porque es un nuevo campo
					
					$cmp00009=$matriz["cmp00009"][$pos];
					$cmp00009 = eliminarCaracteresEspFormatos($cmp00009);
					
					$cmp00011=$matriz["cmp00011"][$pos];
					$cmp00011 = eliminarCaracteresEspFormatos($cmp00011);
					
					$cmp00013=$matriz["cmp00013"][$pos];
					$cmp00013 = eliminarCaracteresEspFormatos($cmp00013);
					
					$cmp00015=$matriz["cmp00015"][$pos];
					$cmp00015 = eliminarCaracteresEspFormatos($cmp00015);
					
					$cmp00017=$matriz["cmp00017"][$pos];
					$cmp00017 = eliminarCaracteresEspFormatos($cmp00017);
					
					$cmp00019=$matriz["cmp00019"][$pos];
					$cmp00019 = eliminarCaracteresEspFormatos($cmp00019);
					
					$cmp00021=$matriz["cmp00021"][$pos];
					$cmp00021 = eliminarCaracteresEspFormatos($cmp00021);
					
					$cmp00023=$matriz["cmp00023"][$pos];
					$cmp00023 = eliminarCaracteresEspFormatos($cmp00023);
					
					$cmp00025=$matriz["cmp00025"][$pos];
					$cmp00025 = eliminarCaracteresEspFormatos($cmp00025);
					
					$cmp00027=$matriz["cmp00027"][$pos];
					$cmp00027 = eliminarCaracteresEspFormatos($cmp00027);
					
					$cmp00029=$matriz["cmp00029"][$pos];
					$cmp00029 = eliminarCaracteresEspFormatos($cmp00029);
					
					$cmp00031=$matriz["cmp00031"][$pos];
					$cmp00031 = eliminarCaracteresEspFormatos($cmp00031);
					
					$cmp00033=$matriz["cmp00033"][$pos];
					$cmp00033 = eliminarCaracteresEspFormatos($cmp00033);
					
					$cmp00035=$matriz["cmp00035"][$pos];
					$cmp00035 = eliminarCaracteresEspFormatos($cmp00035);
					
					$cmp00037=$matriz["cmp00037"][$pos];
					$cmp00037 = eliminarCaracteresEspFormatos($cmp00037);
					
					$cmp00039=$matriz["cmp00039"][$pos];
					$cmp00039 = eliminarCaracteresEspFormatos($cmp00039);				
					
					
					$cmp00041=$matriz["cmp00041"][$pos];
					$cmp00041 = eliminarCaracteresEspFormatos($cmp00041);					
					
					
					$cmp00043=$matriz["cmp00043"][$pos];
					$cmp00043 = eliminarCaracteresEspFormatos($cmp00043);
					
					$otrosdx=$matriz["otrosdx"][$pos];
					$otrosdx = eliminarCaracteresEspFormatos($otrosdx);
					
					$cmp00045=$matriz["cmp00045"][$pos];
					$cmp00045 = eliminarCaracteresEspFormatos($cmp00045);

					$cmp00045 = $otrosdx."<br><br>".$cmp00045;
					
					$cmp00048=$matriz["cmp00048"][$pos];
					$cmp00048 = eliminarCaracteresEspFormatos($cmp00048);
					
					
					$usuariocre=$matriz["usuariocre"][$pos];
					$fechacre=$matriz["fechacre"][$pos];
						if (empty($fechacre)){
						$fechacre ='NULL';
					}
					
					$compania= $_SESSION["compania"];
					$cerrado = 'NULL';
					$noliquidacion = 'NULL';
					
					$id_historia = $pos +1 ;
					
					$cerrado = 'NULL';
					$numproced = 'NULL';
					
					$requierevobo=$matriz["requierevobo"][$pos];
					$requierevobo = eliminarCaracteresEspeciales($requierevobo);
					
					
					$cargovobo=$matriz["cargovobo"][$pos];
					$cargovobo = normalizarCargos($cargovobo);
					$cargovobo = eliminarCaracteresEspeciales($cargovobo);
					
					$rmvobo=$matriz["rmvobo"][$pos];
					$rmvobo = eliminarCaracteresEspeciales($rmvobo);
					
					$usuariovobo_rm =  configurarRM($requierevobo, $rmvobo, $cargovobo);	
					
					$fechavobo=$matriz["fechavobo"][$pos];
					$fechavobo = eliminarCaracteresEspeciales($fechavobo);
					
					insertarRegistroPostgresql($formato,$tipoformato,$id_historia,$usuario_rm,$cargo,$fecha,$hora,$cedula,$ambito,$unidadhosp,$numservicio,$compania,$cerrado , $noliquidacion,$finalidadconsult,$causaexterna,$dx1,$dx2,$dx3,$dx4,$dx5, $cmp00003,$cmp00005,$cmp00007,$cmp00009,$cmp00011,$cmp00013,$cmp00015,$cmp00017,$cmp00019,$cmp00021,$cmp00023,$cmp00025,$cmp00027,$cmp00029,$cmp00031,$cmp00033,$cmp00035,$cmp00037, $cmp00039,$cmp00041, $cmp00043 , $cmp00045, $cmp00048);
					
					
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
			
		
			
			function migrarRegistrosFormato(){
			
				// Tabla Salud.Medicos
				/*normalizarCodificacionMedicos('&Aacute;',utf8_encode("Á"));			
				normalizarCodificacionMedicos('&Eacute;',utf8_encode("É"));
				normalizarCodificacionMedicos('&Iacute;',utf8_encode("Í"));
				normalizarCodificacionMedicos('&Oacute;',utf8_encode("Ó"));
				normalizarCodificacionMedicos('&Uacute;',utf8_encode("Ú"));
				normalizarCodificacionMedicos('&Ntilde;',utf8_encode("Ñ"));*/
				
				creartablaMigracion();
				eliminarRegistrosMigracion();
				eliminarRegistros();
				crearArchivoErrores();
				
				llenarMatriz();
				recorrerMatriz();
				
				//Tabla Formato Autonomo 
				normalizarCodificacionFormato('&Aacute;', utf8_encode("Á"));			
				normalizarCodificacionFormato('&Eacute;', utf8_encode("É"));
				normalizarCodificacionFormato('&Iacute;', utf8_encode("Í"));
				normalizarCodificacionFormato('&Oacute;', utf8_encode("Ó"));
				normalizarCodificacionFormato('&Uacute;',utf8_encode("Ú"));
				normalizarCodificacionFormato('&Ntilde;',utf8_encode("Ñ"));
				//actualizarMedico("HistoClinicaFrms", "" , $campo, $valorBusqueda, $valorReemplazo)
			
			
			}
			
			
		
		
		function migrarEstructuraFormato() {
			
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
			insertarVoBoxFormatos();
			
			// CupsxFormatos
			
			/*eliminarCupsxFormatos($tipoformato, $formato);
			insertarCupsxFormatos();*/
			
			
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
			
			$usuariocre = "ADMINISTRADOR";
			$fechacre = FechaActual();
			$usuario = $usuariocre;
			$fecha = $fechacre;
			$id = 1;
			$detalle = "DIAGNOSTICO PRINCIPAL";
			$tipo = "Principal";
			$estado = "AC";
			$pantalla = 1;
			$iditem = 46;
			$cie10 = "1";
			$tagxml = 'NULL';
			$etiquetaxml = 'NULL';
			
			insertarDxFormatosSQL($compania, $usuario, $fecha, $id, $detalle, $tipo, $formato, $tipoformato, $estado, $pantalla, $iditem, $cie10, $tagxml, $etiquetaxml);
			
			$id = 2;
			$detalle = "DIAGNOSTICO RELACIONADO";
			$tipo = "Relacionado";
			$estado = "AC";
			$pantalla = 1;
			$iditem = 46;
			$cie10 = "1";
			$tagxml = 'NULL';
			$etiquetaxml = 'NULL';
			
			insertarDxFormatosSQL($compania, $usuario, $fecha, $id, $detalle, $tipo, $formato, $tipoformato, $estado, $pantalla, $iditem, $cie10, $tagxml, $etiquetaxml);
			
			
			
			
			echo "<div align='center'> <p class='mensajeFinalizacion'>Ha terminado la migracion del Formato 'Hoja de Ingreso'</p> </div>";
	
		}
		
		
		
		
		
		
		
		if($_GET['formato']="PrimeraVez") {
		
				echo "<fieldset>";			
				echo "<legend> Migracion tabla MySQL </legend>";
				echo "<br>";
				echo "<span align='left'> <a href='../../index.php?migracion=MIG020'class = 'link1'> Panel de Administracion </a> </span>";
				echo "<br>";
				
				global $tablafrm, $tipoformato, $formato ;
				$tablafrm = "tbl00002";
				$tipoformato = "HISTORIA CLINICA";
				$formato = "HOJA DE INGRESO";
				$archivo = "Errores".$tablafrm.".html";
				eliminarRegistroVoBoxFormatos($tipoformato, $formato);
				migrarEstructuraFormato();
				migrarRegistrosFormato(); // Migra los registros de la tabla Salud.PsiqGeneral where TipoFormato = '1a vez'
				
					$totalMySQL = contarRegistrosMySQL();
					$totalPostgresql =  contarRegistrosPostgresql();
					$totalPostgresqlErrores =  contarRegistrosPostgresqlErrores();
					
					echo "<p class= 'subtitulo1'> Total registros MySQL:</p>";
					echo  $totalMySQL."<br/>";
					echo "<p class= 'subtitulo1'> Total registros Postgresql migrados:</p>";
					echo  $totalPostgresql."<br/>";
					$tablaMig = $tablafrm."Migracion";
					echo "<p class= 'error1'> Total errores generados(Tabla HistoClinicaFrms.$tablaMig):</p>";
					echo  $totalPostgresqlErrores."<br/>";
					?>
						<p> <a href="<?php echo $archivo; ?>" class = "link1" target="_blank"> Ver Reporte de errores de la migracion </a> </p>
					<?
					
					echo "</fieldset>";
			
			}
		
		
		
	
	
	
	?>
