
	<html>	
		<head>
			<title> Migracion Formato FormatoNoPOS Medicamentos</title>
			<link rel="stylesheet" type="text/css" href="../../General/estilos/estilos.css">
		</head>
	
	
	<?php
		session_start();
		include_once('../../General/funciones/funciones.php');
		include_once('../../Conexiones/conexion.php');
		include_once('../General/procedimiento.php');
		
		
		
		
		function contarRegistrosMySQL() {
			$cnx = conectar_mysql("Salud");
			$cons = "SELECT COUNT(*)  AS conteomysql FROM Salud.FormatoNoPOS ";
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
				$cons= "COPY HistoriaClinica.Formatos FROM '$ruta/Migraciones/HistoriaClinicaFrms/HistoriaClinica/FormatoNoPOSMedicamentos/Formatos.csv' WITH DELIMITER ';' CSV HEADER;";
				$res =  @pg_query($cons);
					if (!$res) {
						echo "<p class='error1'> Error de ejecucion </p>".pg_last_error()."<br>";
						echo "<p class= 'subtitulo1'>Comando SQL </p> <br>".$cons."<br/>";
							
					}
			
			}
			
			
			function insertarItemsxFormatos() {
		
				$cnx = conectar_postgres();
				$ruta = $_SERVER['DOCUMENT_ROOT'];
				$cons= "COPY HistoriaClinica.ItemsxFormatos FROM '$ruta/Migraciones/HistoriaClinicaFrms/HistoriaClinica/FormatoNoPOSMedicamentos/ItemsxFormatos.csv' WITH DELIMITER ';' CSV HEADER;";
				$res =  @pg_query($cons);
					if (!$res) {
						echo "<p class='error1'> Error de ejecucion </p>".pg_last_error()."<br>";
						echo "<p class= 'subtitulo1'>Comando SQL </p> <br>".$cons."<br/>";
							
					}
			
			}
			
			
			
			
			function insertarPermisosxFormato() {
		
				$cnx = conectar_postgres();
				$ruta = $_SERVER['DOCUMENT_ROOT'];
				$cons= "COPY HistoriaClinica.PermisosxFormato FROM '$ruta/Migraciones/HistoriaClinicaFrms/HistoriaClinica/FormatoNoPOSMedicamentos/PermisosxFormato.csv' WITH DELIMITER ';' CSV HEADER;";
				$res =  @pg_query($cons);
					if (!$res) {
						echo "<p class='error1'> Error de ejecucion </p>".pg_last_error()."<br>";
						echo "<p class= 'subtitulo1'>Comando SQL </p> <br>".$cons."<br/>";
							
					}
			
			}
			
						
			function insertarAmbitosxFormato() {
		
				$cnx = conectar_postgres();
				$ruta = $_SERVER['DOCUMENT_ROOT'];
				$cons= "COPY HistoriaClinica.AmbitosxFormato FROM '$ruta/Migraciones/HistoriaClinicaFrms/HistoriaClinica/FormatoNoPOSMedicamentos/AmbitosxFormato.csv' WITH DELIMITER ';' CSV HEADER;";
				$res =  @pg_query($cons);
					if (!$res) {
						echo "<p class='error1'> Error de ejecucion </p>".pg_last_error()."<br>";
						echo "<p class= 'subtitulo1'>Comando SQL </p> <br>".$cons."<br/>";
							
					}
			
			}
			
			function insertarDxFormatos() {
		
				$cnx = conectar_postgres();
				$ruta = $_SERVER['DOCUMENT_ROOT'];
				$cons= "COPY HistoriaClinica.DxFormatos FROM '$ruta/Migraciones/HistoriaClinicaFrms/HistoriaClinica/FormatoNoPOSMedicamentos/DxFormatos.csv' WITH DELIMITER ';' CSV HEADER;";
				$res =  @pg_query($cons);
					if (!$res) {
						echo "<p class='error1'> Error de ejecucion </p>".pg_last_error()."<br>";
						echo "<p class= 'subtitulo1'>Comando SQL </p> <br>".$cons."<br/>";
							
					}
			
			}
			
			
			function insertarCupsxFormatos() {
		
				$cnx = conectar_postgres();
				$ruta = $_SERVER['DOCUMENT_ROOT'];
				$cons= "COPY HistoriaClinica.CupsxFormatos FROM '$ruta/Migraciones/HistoriaClinicaFrms/HistoriaClinica/FormatoNoPOSMedicamentos/CupsxFormatos.csv' WITH DELIMITER ';' CSV HEADER;";
				$res =  @pg_query($cons);
					if (!$res) {
						echo "<p class='error1'> Error de ejecucion </p>".pg_last_error()."<br>";
						echo "<p class= 'subtitulo1'>Comando SQL </p> <br>".$cons."<br/>";
							
					}
			
			}
			
			
			
			
			function insertarVoBoxFormatos() {
		
				$cnx = conectar_postgres();
				$ruta = $_SERVER['DOCUMENT_ROOT'];
				$cons= "COPY HistoriaClinica.VoBoxFormatos FROM '$ruta/Migraciones/HistoriaClinicaFrms/HistoriaClinica/FormatoNoPOSMedicamentos/VoBoxFormatos.csv' WITH DELIMITER ';' CSV HEADER;";
				$res =  @pg_query($cons);
					if (!$res) {
						echo "<p class='error1'> Error de ejecucion </p>".pg_last_error()."<br>";
						echo "<p class= 'subtitulo1'>Comando SQL </p> <br>".$cons."<br/>";
							
					}
			
			}
			
			
			function insertarAjustePermanente() {
		
				$cnx = conectar_postgres();
				$ruta = $_SERVER['DOCUMENT_ROOT'];
				$cons= "COPY HistoriaClinica.AjustePermanente FROM '$ruta/Migraciones/HistoriaClinicaFrms/HistoriaClinica/FormatoNoPOSMedicamentos/AjustePermanente.csv' WITH DELIMITER ';' CSV HEADER;";
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
			$cons = "UPDATE HistoClinicaFrms.$tablafrm SET formato = replace( formato,'$cadenaBusqueda','$cadenaReemplazo'), tipoformato = replace( tipoformato,'$cadenaBusqueda','$cadenaReemplazo') , compania = replace( compania,'$cadenaBusqueda','$cadenaReemplazo'), cargo = replace( cargo,'$cadenaBusqueda','$cadenaReemplazo') , ambito = replace( ambito,'$cadenaBusqueda','$cadenaReemplazo'), unidadhosp = replace( unidadhosp,'$cadenaBusqueda','$cadenaReemplazo') , finalidadconsult = replace( finalidadconsult,'$cadenaBusqueda','$cadenaReemplazo') , dx1 = replace( dx1,'$cadenaBusqueda','$cadenaReemplazo') , dx2 = replace( dx2,'$cadenaBusqueda','$cadenaReemplazo') , dx3 = replace( dx3,'$cadenaBusqueda','$cadenaReemplazo') , dx4 = replace( dx4,'$cadenaBusqueda','$cadenaReemplazo') , dx5 = replace( dx5,'$cadenaBusqueda','$cadenaReemplazo'),   cmp00005=replace(cmp00005,'$cadenaBusqueda','$cadenaReemplazo'),cmp00009=replace(cmp00009,'$cadenaBusqueda','$cadenaReemplazo'),cmp00013=replace(cmp00013,'$cadenaBusqueda','$cadenaReemplazo'),cmp00015=replace(cmp00015,'$cadenaBusqueda','$cadenaReemplazo'),cmp00017=replace(cmp00017,'$cadenaBusqueda','$cadenaReemplazo'),cmp00019=replace(cmp00019,'$cadenaBusqueda','$cadenaReemplazo'), cmp00020=replace(cmp00020,'$cadenaBusqueda','$cadenaReemplazo'), cmp00021=replace(cmp00021,'$cadenaBusqueda','$cadenaReemplazo')";
			
			$res = @pg_query($cnx , $cons);
				if (!$res)  {
					echo "<p class='error1'> Error en la normalizacion de la codificacion </p>".pg_last_error()."<br>";
					echo "<p class= 'subtitulo1'>Comando SQL </p> <br>".$cons."<br/>";
				}

		}
		
		
		function  normalizarCodificacionMedsxFormato($cadenaBusqueda,$cadenaReemplazo)  {
		// Busca y reemplaza ocurrencias en una tabla
			global $tablafrm;
			$cnx= conectar_postgres();
			$cons = "UPDATE HistoriaClinica.MedsxFormato SET usuario = replace( usuario,'$cadenaBusqueda','$cadenaReemplazo'), nombre_medicamento = replace( nombre_medicamento,'$cadenaBusqueda','$cadenaReemplazo') , presentacion = replace( presentacion,'$cadenaBusqueda','$cadenaReemplazo'), posologia = replace( posologia,'$cadenaBusqueda','$cadenaReemplazo') , tiempo_tratamiento = replace( tiempo_tratamiento,'$cadenaBusqueda','$cadenaReemplazo')";
			
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
			$encabezado = "<html> <head> <title> Reporte errores Formato Epicrisis   </title> 
			<link rel='stylesheet' type='text/css' href='../../General/estilos/estilos.css'> </head>";
			fputs($fp, $encabezado);
			fclose($fp);
		}
		
		function llamarRegistrosMySQL() {
			// Selecciona los registros MySQL (Origen)
			global $res;
			$cnx = conectar_mysql("Salud");
			$cons = "SELECT FormatoNoPOS.*, TIME(Fecha) AS hora   FROM Salud.FormatoNoPOS   ORDER BY Fecha ASC, Hora ASC  ";
			$res =  @mysql_query($cons);
			if (!$res)  {
					echo "<p class='error1'> Error en la normalizacion de la codificacion </p>".mysql_error()."<br>";
					echo "<p class= 'subtitulo1'>Comando SQL </p> <br>".$cons."<br/>";
				}
			return $res; 
		
		}
		
		
		



		function crearTablaFormato($tabla){
				$cnx = conectar_postgres();				
				$cons= "CREATE TABLE histoclinicafrms.$tabla(  formato character varying(150) NOT NULL,  tipoformato character varying(150) NOT NULL,  id_historia integer NOT NULL DEFAULT 0,  usuario character varying(150) NOT NULL,  cargo character varying(80) NOT NULL,  fecha date,  hora time without time zone,  cedula character varying(15) NOT NULL,  ambito character varying(150),  unidadhosp character varying(150),  numservicio integer,  compania character varying(60) NOT NULL,  cerrado integer,  noliquidacion integer DEFAULT 0,  finalidadconsult character varying(5),  causaexterna character varying(5),  dx1 text,  dx2 text,  dx3 text,  dx4 text,  dx5 text,  tipodx character varying(1),  numproced integer,  usuarioajuste character varying(30),  fechaajuste date,  padretipoformato character varying(150),  padreformato character varying(150),  id_historia_origen integer,  cmp00003 character varying(10000),  idsvital numeric,  cmp00005 text,  cmp00009 text,  cmp00013 text,  cmp00015 text,  cmp00017 text,  cmp00019 character varying(255),  cmp00020 character varying(255),  cmp00021 character varying(255),  CONSTRAINT pkhctbl$tabla PRIMARY KEY (formato , tipoformato , id_historia , cedula , compania ),  CONSTRAINT fkambtbl$tabla FOREIGN KEY (ambito, compania)      REFERENCES salud.ambitos (ambito, compania) MATCH SIMPLE      ON UPDATE CASCADE ON DELETE RESTRICT,  CONSTRAINT fkitemsxtbl$tabla FOREIGN KEY (formato, tipoformato, compania)      REFERENCES historiaclinica.formatos (formato, tipoformato, compania) MATCH SIMPLE      ON UPDATE CASCADE ON DELETE RESTRICT,  CONSTRAINT fkpabxtbl$tabla FOREIGN KEY (unidadhosp, compania, ambito)      REFERENCES salud.pabellones (pabellon, compania, ambito) MATCH SIMPLE      ON UPDATE CASCADE ON DELETE RESTRICT)WITH (  OIDS=FALSE)";	
	  			$res = @pg_query($cnx,$cons);		
				if (!$res) {							
				echo "<p class='error1'> Error de ejecucion </p>".pg_last_error()."<br>";
				echo "<p class= 'subtitulo1'>Comando SQL </p> <br>".$cons."<br/>";
	
				}
			
		}



		
		
		function creartablaMigracion() {
			// Esta funcion crea una tabla con estructura similar a la tabla Postgresql con la diferencia que carece de llave primaria y omite la restriccion NOT NULL,          ademas  crea un nuevo campo llamado error en el cual se almacena la descripcion del error generado en la consulta inicial.
			global $tablafrm ;
			$cnx= conectar_postgres();			
			$tablaMig = $tablafrm."Migracion";
			$cons = "CREATE TABLE IF NOT EXISTS histoclinicafrms.$tablaMig(  formato character varying(150) ,  tipoformato character varying(150) ,  id_historia integer  ,  usuario character varying(150) ,  cargo character varying(80) ,  fecha date,  hora time without time zone,  cedula character varying(15) ,  ambito character varying(150),  unidadhosp character varying(150),  numservicio integer,  compania character varying(60) ,  cerrado integer,  noliquidacion integer ,  finalidadconsult character varying(5),  causaexterna character varying(5),  dx1 text,  dx2 text,  dx3 text,  dx4 text,  dx5 text,  tipodx character varying(1),  numproced integer,  usuarioajuste character varying(30),  fechaajuste date,  padretipoformato character varying(150),  padreformato character varying(150),  id_historia_origen integer,  cmp00003 character varying(10000),  idsvital numeric,  cmp00005 text,  cmp00009 text,  cmp00013 text,  cmp00015 text,  cmp00017 text,  cmp00019 character varying(255),  cmp00020 character varying(255),  cmp00021 character varying(255),error text)WITH (  OIDS=FALSE)";	
			$res = @pg_query($cnx, $cons);
				if (!$res) {
				
					echo "<p class='error1'> Error de ejecucion </p>".pg_last_error()."<br>";
					echo "<p class= 'subtitulo1'>Comando SQL </p> <br>".$cons."<br>"; 				
					echo "<br><br>";			
					//echo "<br><br>";
				}
			
		}
		
		
		
		
		
		
		
		
		function insertarRegistroMigracion($formato,$tipoformato,$id_historia,$usuario_rm,$cargo,$fecha,$hora,$cedula,$ambito,$unidadhosp,$numservicio,$compania,$cerrado , $noliquidacion,$finalidadconsult,$causaexterna,$dx1,$dx2,$dx3,$dx4,$dx5,$cmp00003,$cmp00005,$cmp00009,$cmp00013, $cmp00015, $cmp00017, $cmp00019, $cmp00020, $cmp00021, $error) {
			//Realiza la insercion en Postgresql con base en los parametros
			global  $tablafrm;
			$tablaMig = $tablafrm."Migracion";
			$cnx = 	conectar_postgres();
			$cons = "INSERT INTO HistoClinicaFrms.$tablaMig (formato,tipoformato,id_historia,usuario,cargo,fecha,hora,cedula,ambito,unidadhosp,numservicio,compania,cerrado , noliquidacion,finalidadconsult,causaexterna,dx1,dx2,dx3,dx4,dx5,cmp00003,cmp00005,cmp00009,cmp00013, cmp00015, cmp00017, cmp00019, cmp00020, cmp00021, error) VALUES ('$formato','$tipoformato','$id_historia','$usuario_rm','$cargo','$fecha','$hora','$cedula','$ambito','$unidadhosp','$numservicio','$compania','$cerrado', '$noliquidacion','$finalidadconsult','$causaexterna','$dx1','$dx2','$dx3','$dx4','$dx5','$cmp00003','$cmp00005','$cmp00009','$cmp00013', '$cmp00015', '$cmp00017','$cmp00019','$cmp00020','$cmp00021', '$error')"	;
					 
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
		
		
		
		
		function insertarRegistroPostgresql($formato,$tipoformato,$id_historia,$usuario_rm,$cargo,$fecha,$hora,$cedula,$ambito,$unidadhosp,$numservicio,$compania,$cerrado , $noliquidacion,$finalidadconsult,$causaexterna,$dx1,$dx2,$dx3,$dx4,$dx5,$cmp00003,$cmp00005,$cmp00009,$cmp00013, $cmp00015, $cmp00017, $cmp00019, $cmp00020, $cmp00021) {
		//Realiza la insercion en Postgresql con base en los parametros
			global  $tablafrm;
			$cnx = 	conectar_postgres();
			$cons = "INSERT INTO HistoClinicaFrms.$tablafrm (formato,tipoformato,id_historia,usuario,cargo,fecha,hora,cedula,ambito,unidadhosp,numservicio,compania,cerrado , noliquidacion,finalidadconsult,causaexterna,dx1,dx2,dx3,dx4,dx5,cmp00003,cmp00005,cmp00009,cmp00013, cmp00015, cmp00017, cmp00019, cmp00020, cmp00021) VALUES ('$formato','$tipoformato','$id_historia','$usuario_rm','$cargo','$fecha','$hora','$cedula','$ambito','$unidadhosp','$numservicio','$compania','$cerrado', '$noliquidacion','$finalidadconsult','$causaexterna','$dx1','$dx2','$dx3','$dx4','$dx5','$cmp00003','$cmp00005','$cmp00009','$cmp00013', '$cmp00015', '$cmp00017','$cmp00019','$cmp00020','$cmp00021')"	;
			
			$cons = str_replace( "'NULL'","NULL",$cons  );	
			$res = @pg_query($cnx, $cons);
				if (!$res) {
					$consUTF8 = utf8_encode($cons);
					$resUTF8 = @pg_query($cnx, $consUTF8);					
						if (!$resUTF8) {
							$error = pg_last_error();
							$error = eliminarCaracteresEspeciales($error);
							insertarRegistroMigracion($formato,$tipoformato,$id_historia,$usuario_rm,$cargo,$fecha,$hora,$cedula,$ambito,$unidadhosp,$numservicio,$compania,$cerrado , $noliquidacion,$finalidadconsult,$causaexterna,$dx1,$dx2,$dx3,$dx4,$dx5,$cmp00003,$cmp00005,$cmp00009,$cmp00013, $cmp00015, $cmp00017, $cmp00019, $cmp00020, $cmp00021, $error);					
							
						}
				
				}

				
		}
		
		
		function insertarMedxFormato($formato,$tipoformato,$idhistoria, $cedula, $compania,$usuario, $iditem, $orden, $codmedicamento, $nombre_medicamento, $presentacion, $posologia, $tiempo_tratamiento){
			global  $tablafrm;
			$cnx = 	conectar_postgres();
			$cons = "INSERT INTO HistoriaClinica.Medsxformato (formato, tipo_formato, id_historia, cedula, compania, usuario, id_item, orden, cod_medicamento, nombre_medicamento, presentacion, posologia, tiempo_tratamiento) VALUES
			('$formato','$tipoformato','$idhistoria', '$cedula', '$compania', '$usuario', $iditem, $orden, '$codmedicamento', '$nombre_medicamento', '$presentacion', '$posologia', '$tiempo_tratamiento')";
			$cons = str_replace( "'NULL'","NULL",$cons);	
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

		
		
		
		
		function  llenarMatriz(){
		// Llena una matriz con el resultado de la consulta MySQL
			
			unset($matriz); 
			global  $matriz, $res;	
			$res = llamarRegistrosMySQL();
			$posicion=0;
				
				while ($fila = mysql_fetch_array($res))	{	
					
					$matriz["usuario"][$posicion] = $fila["Usuario"];
					$matriz["cedula"][$posicion] = $fila["Cedula"];
					$matriz["cargo"][$posicion] = $fila["Cargo"];
					$matriz["fecha"][$posicion] = $fila["Fecha"];
					$matriz["hora"][$posicion] = $fila["Hora"];
					$matriz["rm"][$posicion] = $fila["RM"];	
					$matriz["autoidmysql"][$posicion] = $fila["AutoId"];	
					$matriz["fechadx"][$posicion] = $fila["FechaDx"];
					$matriz["desripcion"][$posicion] = $fila["Descripcion"];
					$matriz["respuesta"][$posicion] = $fila["Respuesta"];
					$matriz["justificacion"][$posicion] = $fila["Justificacion"];
					$matriz["efectoesperado"][$posicion] = $fila["Deseado"];
					$matriz["efectosecundario"][$posicion] = $fila["Secundario"];
					$matriz["tiempo"][$posicion] = $fila["Tiempo"];
					$matriz["riesgo"][$posicion] = $fila["Riesgo"];
					$matriz["posibilidad"][$posicion] = $fila["Posibilidad"];					
					$matriz["invima"][$posicion] = $fila["Invima"];
					$matriz["coddx1"][$posicion] = $fila["CodDx1"];
					$matriz["nombredx1"][$posicion] = $fila["NomDx1"];
					$matriz["coddx2"][$posicion] = $fila["CodDx2"];
					$matriz["nombredx2"][$posicion] = $fila["NomDx2"];
					$matriz["coddx3"][$posicion] = $fila["CodDx3"];
					$matriz["nombredx3"][$posicion] = $fila["NomDx3"];
					
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
					
					$id_historia = $pos +1 ;
									
					$compania = $_SESSION["compania"];

					$cargo = $matriz["cargo"][$pos];
					$cargo =normalizarCargos($cargo);
					$cargo = eliminarCaracteresEspeciales($cargo);
					
					
					$usuario=$matriz["usuario"][$pos];	
					$usuario = normalizarMedicos($usuario);
					$usuario = eliminarCaracteresEspeciales($usuario);
					
					$rm = $matriz["rm"][$pos];					
					$rm = eliminarCaracteresEspeciales($rm);
					
					$usuario_rm =  configurarRM($usuario, $rm, $cargo);	
										
					
					$fecha=$matriz["fecha"][$pos];
						if ($fecha == "0000-00-00 00:00" or $fecha == "0000-00-00" ) {
							$fecha = 'NULL';
						}
					$hora=$matriz["hora"][$pos];
					
					$cedula=$matriz["cedula"][$pos];
					$cedula=eliminarCaracteresEspeciales($cedula);
					
					
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
					
					$fechadx=$matriz["fechadx"][$pos];
						if ($fechadx == "0000-00-00 00:00" or $fechadx == "0000-00-00" ) {
							$fechadx = 'NULL';
						}					
					
					
					$autoidmysql = $matriz["autoidmysql"][$pos];
					
					$cmp00003 = $fechadx;
					
					$descripcion = $matriz["desripcion"][$pos];
					$descripcion = eliminarCaracteresEspFormatos($descripcion );
					
					$cmp00005 = $descripcion ;
					
					
					
					$resPOS= consultarDetFormatoPOS($autoidmysql, $cedula);
					if (mysql_num_rows($resPOS)>0){
						$orden = 0;
						
							while($POS = mysql_fetch_array($resPOS)){
								$nombre_medicamento = eliminarCaracteresEspFormatos($POS['Principio']);
								$posologia = eliminarCaracteresEspFormatos($POS['Posologia']);
								$presentacion = eliminarCaracteresEspFormatos($POS['Presentacion']);
								$dosis = eliminarCaracteresEspFormatos($POS['Dosis']);
								$cantidad = eliminarCaracteresEspFormatos($POS['Cantidad']);
								$tiempo_tratamiento = eliminarCaracteresEspFormatos($POS['Tiempo']);
								$iditem = 7;
								$orden++;
								$codmedicamento = 'NULL';								
								
								insertarMedxFormato($formato,$tipoformato,$id_historia, $cedula, $compania,$usuario, $iditem, $orden, $codmedicamento, $nombre_medicamento, $presentacion, $posologia, $tiempo_tratamiento);
							}
					}
					
					
					
					
					$resNoPOS= consultarDetFormatoNoPOS($autoidmysql, $cedula);
					if (mysql_num_rows($resNoPOS)>0){
						$orden = 0;
							
							while($NOPOS = mysql_fetch_array($resNoPOS)){
								$nombre_medicamento = eliminarCaracteresEspFormatos($NOPOS['Principio']);
								$posologia = eliminarCaracteresEspFormatos($NOPOS['Posologia']);
								$presentacion = eliminarCaracteresEspFormatos($NOPOS['Presentacion']);
								$dosis = eliminarCaracteresEspFormatos($NOPOS['Dosis']);
								$cantidad = eliminarCaracteresEspFormatos($NOPOS['Cantidad']);
								$tiempo_tratamiento = eliminarCaracteresEspFormatos($NOPOS['Tiempo']);
								$iditem = 11;
								$orden++;
								$codmedicamento = 'NULL';								
								
								insertarMedxFormato($formato,$tipoformato,$id_historia, $cedula, $compania,$usuario, $iditem, $orden, $codmedicamento, $nombre_medicamento, $presentacion, $posologia, $tiempo_tratamiento);
							
							}
						
					}
					
					$respuesta = $matriz["respuesta"][$pos];
					$respuesta = eliminarCaracteresEspFormatos($respuesta );
					
					$cmp00009 = $respuesta;
					
					$justificacion = $matriz["justificacion"][$pos];
					$justificacion = eliminarCaracteresEspFormatos($justificacion );
					
					$cmp00013 = $justificacion;
					
					$efectoesperado = $matriz["efectoesperado"][$pos];
					$efectoesperado = eliminarCaracteresEspFormatos($efectoesperado );
					
					$efectosecundario = $matriz["efectosecundario"][$pos];
					$efectosecundario = eliminarCaracteresEspFormatos($efectosecundario );
					
					$cmp00015 = "<b>Efecto esperado:</b><br>".$efectoesperado."<br><br><b>Efecto Secundario: </b><br>".$efectosecundario;
					
					$tiempo = $matriz["tiempo"][$pos];
					$tiempo = eliminarCaracteresEspFormatos($tiempo );
					
					$cmp00017 = $tiempo;
					
					$riesgo = $matriz["riesgo"][$pos];
					$riesgo = eliminarCaracteresEspFormatos($riesgo );
					
					$cmp00019 = $riesgo;
					
					$posibilidad = $matriz["posibilidad"][$pos];
					$posibilidad = eliminarCaracteresEspFormatos($posibilidad );
					
					$cmp00020 = $posibilidad;
					
					$invima = $matriz["invima"][$pos];
					$invima = eliminarCaracteresEspFormatos($invima );
					
					$cmp00021 = $invima;
					
					$coddx1 = $matriz["coddx1"][$pos];
					$coddx1 = eliminarCaracteresEspFormatos($coddx1 );
					
					$nombredx1 = $matriz["nombredx1"][$pos];
					$nombredx1 = eliminarCaracteresEspFormatos($nombredx1 );
					
					if(!empty($coddx1)){
						$dx1 = $coddx1;
					}
					else {
						$dx1 = $nombredx1;
					}
					
					
					$coddx2 = $matriz["coddx2"][$pos];
					$coddx2 = eliminarCaracteresEspFormatos($coddx2 );
					
					$nombredx2 = $matriz["nombredx2"][$pos];
					$nombredx2 = eliminarCaracteresEspFormatos($nombredx2 );
					
					if(!empty($coddx2)){
						$dx2 = $coddx2;
					}
					else {
						$dx2 = $nombredx2;
					}
					
					
					$coddx3 = $matriz["coddx3"][$pos];
					$coddx3 = eliminarCaracteresEspFormatos($coddx3 );
					
					$nombredx3 = $matriz["nombredx3"][$pos];
					$nombredx3 = eliminarCaracteresEspFormatos($nombredx3 );
					
					if(!empty($coddx3)){
						$dx3 = $coddx3;
					}
					else {
						$dx3 = $nombredx3;
					}
					
					$dx4 = 'NULL';
										
					$cerrado = 'NULL';
					$noliquidacion = 'NULL';
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
					
					
					insertarRegistroPostgresql($formato,$tipoformato,$id_historia,$usuario_rm,$cargo,$fecha,$hora,$cedula,$ambito,$unidadhosp,$numservicio,$compania,$cerrado , $noliquidacion,$finalidadconsult,$causaexterna,$dx1,$dx2,$dx3,$dx4,$dx5,$cmp00003,$cmp00005,$cmp00009,$cmp00013, $cmp00015, $cmp00017, $cmp00019, $cmp00020, $cmp00021);
					
					
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
			
			function eliminarMedsxFormato() {
				global $tablafrm;
				$cnx= conectar_postgres();
				$cons= "DELETE FROM HistoriaClinica.MedsxFormato";
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
				eliminarMedsxFormato();
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
			
			// Inician definiciones generales
		
		global $tablafrm, $tipoformato , $formato ;			
			
			$compania = $_SESSION["compania"];				
			$tablaMig = $tablafrm."Migracion";
			
			
			
			// Tabla FrmMigracion (tbl00001Migracion o la que corresponda)
			eliminarTablaFormato($tablaMig);
			crearTablaMigracion();
			
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
			
			$usuariocre = "ADMINISTRADOR";
			$fechacre = FechaActual();
			$usuario = $usuariocre;
			$fecha = $fechacre;
			$id = 1;
			$detalle = "DIAGNOSTICO PRINCIPAL";
			$tipo = "Principal";
			$estado = "AC";
			$pantalla = 1;
			$iditem = 2;
			$cie10 = "1";
			$tagxml = 'NULL';
			$etiquetaxml = 'NULL';
			
			insertarDxFormatosSQL($compania, $usuario, $fecha, $id, $detalle, $tipo, $formato, $tipoformato, $estado, $pantalla, $iditem, $cie10, $tagxml, $etiquetaxml);
			
			$id = 2;
			$detalle = "DIAGNOSTICO RELACIONADO 1";
			$tipo = "Relacionado";
			$estado = "AC";
			$pantalla = 1;
			$iditem = 2;
			$cie10 = "1";
			$tagxml = 'NULL';
			$etiquetaxml = 'NULL';
			
			insertarDxFormatosSQL($compania, $usuario, $fecha, $id, $detalle, $tipo, $formato, $tipoformato, $estado, $pantalla, $iditem, $cie10, $tagxml, $etiquetaxml);
			
			
			$id = 3;
			$detalle = "DIAGNOSTICO RELACIONADO 2";
			$tipo = "Relacionado";
			$estado = "AC";
			$pantalla = 1;
			$iditem = 2;
			$cie10 = "1";
			$tagxml = 'NULL';
			$etiquetaxml = 'NULL';
			
			insertarDxFormatosSQL($compania, $usuario, $fecha, $id, $detalle, $tipo, $formato, $tipoformato, $estado, $pantalla, $iditem, $cie10, $tagxml, $etiquetaxml);
			
			$id = 4;
			$detalle = "DIAGNOSTICO RELACIONADO 3";
			$tipo = "Relacionado";
			$estado = "AC";
			$pantalla = 1;
			$iditem = 2;
			$cie10 = "1";
			$tagxml = 'NULL';
			$etiquetaxml = 'NULL';
			
			insertarDxFormatosSQL($compania, $usuario, $fecha, $id, $detalle, $tipo, $formato, $tipoformato, $estado, $pantalla, $iditem, $cie10, $tagxml, $etiquetaxml);
			
			
			$id = 5;
			$detalle = "DIAGNOSTICO RELACIONADO 4";
			$tipo = "Relacionado";
			$estado = "AC";
			$pantalla = 1;
			$iditem = 2;
			$cie10 = "1";
			$tagxml = 'NULL';
			$etiquetaxml = 'NULL';
			
			insertarDxFormatosSQL($compania, $usuario, $fecha, $id, $detalle, $tipo, $formato, $tipoformato, $estado, $pantalla, $iditem, $cie10, $tagxml, $etiquetaxml);
			
			
			
			echo "<div align='center'> <p class='mensajeFinalizacion'>Ha terminado la migracion del Formato 'FormatoNoPOSMedicamentos'</p> </div>";
	
		}
		
		
		
		
		
		
		
		if($_GET['formato']="FormatoNoPOSMedicamentos") {
		
				echo "<fieldset>";			
				echo "<legend> Migracion tabla MySQL </legend>";
				echo "<br>";
				echo "<span align='left'> <a href='../../index.php?migracion=MIG026' class = 'link1'> Panel de Administracion </a> </span>";
				echo "<br>";
				
				global $tablafrm, $tipoformato, $formato ;
				$tablafrm = "tbl00008";
				$tipoformato = "HISTORIA CLINICA";
				$formato = "FORMATO NO POS MEDICAMENTOS";
				$archivo = "Errores".$tablafrm.".html";
				eliminarRegistroVoBoxFormatos($tipoformato, $formato);
				migrarEstructuraFormato();
				migrarRegistrosFormato();
				
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
					
					?><p> <a href="<?php echo $archivo; ?>" class = "link1" target="_blank"> Ver Reporte de errores de la migracion </a> </p><?php
					
					echo "</fieldset>";
			
			}
		
		
		
	
	
	
	?>
