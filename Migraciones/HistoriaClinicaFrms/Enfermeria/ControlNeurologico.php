
	<html>	
		<head>
			<title> Migracion Formato ControlNeurologico</title>
			<link rel="stylesheet" type="text/css" href="../../General/estilos/estilos.css">
		</head>
	
	
	<?php
		session_start();
		include_once('../../General/funciones/funciones.php');
		include_once('../../Conexiones/conexion.php');
		include_once('../General/procedimiento.php');
		
		
		
		
		function contarRegistrosMySQL() {
			$cnx = conectar_mysql("Salud");
			$cons = "SELECT COUNT(*)  AS conteomysql FROM Salud.ControlNeurologico ";
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
				$cons= "COPY HistoriaClinica.Formatos FROM '$ruta/Migraciones/HistoriaClinicaFrms/Enfermeria/ControlNeurologico/Formatos.csv' WITH DELIMITER ';' CSV HEADER;";
				$res =  @pg_query($cons);
					if (!$res) {
						echo "<p class='error1'> Error de ejecucion </p>".pg_last_error()."<br>";
						echo "<p class= 'subtitulo1'>Comando SQL </p> <br>".$cons."<br/>";
							
					}
			
			}
			
			
			function insertarItemsxFormatos() {
		
				$cnx = conectar_postgres();
				$ruta = $_SERVER['DOCUMENT_ROOT'];
				$cons= "COPY HistoriaClinica.ItemsxFormatos FROM '$ruta/Migraciones/HistoriaClinicaFrms/Enfermeria/ControlNeurologico/ItemsxFormatos.csv' WITH DELIMITER ';' CSV HEADER;";
				$res =  @pg_query($cons);
					if (!$res) {
						echo "<p class='error1'> Error de ejecucion </p>".pg_last_error()."<br>";
						echo "<p class= 'subtitulo1'>Comando SQL </p> <br>".$cons."<br/>";
							
					}
			
			}
			
			
			
			
			function insertarPermisosxFormato() {
		
				$cnx = conectar_postgres();
				$ruta = $_SERVER['DOCUMENT_ROOT'];
				$cons= "COPY HistoriaClinica.PermisosxFormato FROM '$ruta/Migraciones/HistoriaClinicaFrms/Enfermeria/ControlNeurologico/PermisosxFormato.csv' WITH DELIMITER ';' CSV HEADER;";
				$res =  @pg_query($cons);
					if (!$res) {
						echo "<p class='error1'> Error de ejecucion </p>".pg_last_error()."<br>";
						echo "<p class= 'subtitulo1'>Comando SQL </p> <br>".$cons."<br/>";
							
					}
			
			}
			
						
			function insertarAmbitosxFormato() {
		
				$cnx = conectar_postgres();
				$ruta = $_SERVER['DOCUMENT_ROOT'];
				$cons= "COPY HistoriaClinica.AmbitosxFormato FROM '$ruta/Migraciones/HistoriaClinicaFrms/Enfermeria/ControlNeurologico/AmbitosxFormato.csv' WITH DELIMITER ';' CSV HEADER;";
				$res =  @pg_query($cons);
					if (!$res) {
						echo "<p class='error1'> Error de ejecucion </p>".pg_last_error()."<br>";
						echo "<p class= 'subtitulo1'>Comando SQL </p> <br>".$cons."<br/>";
							
					}
			
			}
			
			function insertarDxFormatos() {
		
				$cnx = conectar_postgres();
				$ruta = $_SERVER['DOCUMENT_ROOT'];
				$cons= "COPY HistoriaClinica.DxFormatos FROM '$ruta/Migraciones/HistoriaClinicaFrms/Enfermeria/ControlNeurologico/DxFormatos.csv' WITH DELIMITER ';' CSV HEADER;";
				$res =  @pg_query($cons);
					if (!$res) {
						echo "<p class='error1'> Error de ejecucion </p>".pg_last_error()."<br>";
						echo "<p class= 'subtitulo1'>Comando SQL </p> <br>".$cons."<br/>";
							
					}
			
			}
			
			
			function insertarCupsxFormatos() {
		
				$cnx = conectar_postgres();
				$ruta = $_SERVER['DOCUMENT_ROOT'];
				$cons= "COPY HistoriaClinica.CupsxFormatos FROM '$ruta/Migraciones/HistoriaClinicaFrms/Enfermeria/ControlNeurologico/CupsxFormatos.csv' WITH DELIMITER ';' CSV HEADER;";
				$res =  @pg_query($cons);
					if (!$res) {
						echo "<p class='error1'> Error de ejecucion </p>".pg_last_error()."<br>";
						echo "<p class= 'subtitulo1'>Comando SQL </p> <br>".$cons."<br/>";
							
					}
			
			}
			
			
			
			
			function insertarVoBoxFormatos() {
		
				$cnx = conectar_postgres();
				$ruta = $_SERVER['DOCUMENT_ROOT'];
				$cons= "COPY HistoriaClinica.VoBoxFormatos FROM '$ruta/Migraciones/HistoriaClinicaFrms/Enfermeria/ControlNeurologico/VoBoxFormatos.csv' WITH DELIMITER ';' CSV HEADER;";
				$res =  @pg_query($cons);
					if (!$res) {
						echo "<p class='error1'> Error de ejecucion </p>".pg_last_error()."<br>";
						echo "<p class= 'subtitulo1'>Comando SQL </p> <br>".$cons."<br/>";
							
					}
			
			}
			
			
			function insertarAjustePermanente() {
		
				$cnx = conectar_postgres();
				$ruta = $_SERVER['DOCUMENT_ROOT'];
				$cons= "COPY HistoriaClinica.AjustePermanente FROM '$ruta/Migraciones/HistoriaClinicaFrms/Enfermeria/ControlNeurologico/AjustePermanente.csv' WITH DELIMITER ';' CSV HEADER;";
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
			$cons = "UPDATE HistoClinicaFrms.$tablafrm SET formato = replace( formato,'$cadenaBusqueda','$cadenaReemplazo'), tipoformato = replace( tipoformato,'$cadenaBusqueda','$cadenaReemplazo') , compania = replace( compania,'$cadenaBusqueda','$cadenaReemplazo'), cargo = replace( cargo,'$cadenaBusqueda','$cadenaReemplazo') , ambito = replace( ambito,'$cadenaBusqueda','$cadenaReemplazo'), unidadhosp = replace( unidadhosp,'$cadenaBusqueda','$cadenaReemplazo') , finalidadconsult = replace( finalidadconsult,'$cadenaBusqueda','$cadenaReemplazo') , dx1 = replace( dx1,'$cadenaBusqueda','$cadenaReemplazo') , dx2 = replace( dx2,'$cadenaBusqueda','$cadenaReemplazo') , dx3 = replace( dx3,'$cadenaBusqueda','$cadenaReemplazo') , dx4 = replace( dx4,'$cadenaBusqueda','$cadenaReemplazo') , dx5 = replace( dx5,'$cadenaBusqueda','$cadenaReemplazo'),   cmp00003=replace(cmp00003,'$cadenaBusqueda','$cadenaReemplazo'),cmp00004=replace(cmp00004,'$cadenaBusqueda','$cadenaReemplazo'),cmp00005=replace(cmp00005,'$cadenaBusqueda','$cadenaReemplazo'),cmp00007=replace(cmp00007,'$cadenaBusqueda','$cadenaReemplazo'),cmp00008=replace(cmp00008,'$cadenaBusqueda','$cadenaReemplazo'),cmp00010=replace(cmp00010,'$cadenaBusqueda','$cadenaReemplazo'), cmp00011=replace(cmp00011,'$cadenaBusqueda','$cadenaReemplazo'), cmp00013=replace(cmp00013,'$cadenaBusqueda','$cadenaReemplazo'), cmp00015=replace(cmp00015,'$cadenaBusqueda','$cadenaReemplazo'), cmp00016=replace(cmp00016,'$cadenaBusqueda','$cadenaReemplazo'), cmp00018=replace(cmp00018,'$cadenaBusqueda','$cadenaReemplazo'), cmp00021=replace(cmp00021,'$cadenaBusqueda','$cadenaReemplazo'), cmp00022=replace(cmp00022,'$cadenaBusqueda','$cadenaReemplazo'), cmp00023=replace(cmp00023,'$cadenaBusqueda','$cadenaReemplazo')";
			
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
			$cons = "SELECT ControlNeurologico.*, TIME(Fecha) AS hora  FROM Salud.ControlNeurologico ORDER BY Fecha ASC, Hora ASC ";
			$res =  @mysql_query($cons);
			if (!$res)  {
					echo "<p class='error1'> Error en la normalizacion de la codificacion </p>".mysql_error()."<br>";
					echo "<p class= 'subtitulo1'>Comando SQL </p> <br>".$cons."<br/>";
				}
			return $res; 
		
		}
		
		
		



		function crearTablaFormato($tabla){
				$cnx = conectar_postgres();				
				$cons= "CREATE TABLE histoclinicafrms.$tabla(  formato character varying(150) NOT NULL,  tipoformato character varying(150) NOT NULL,  id_historia integer NOT NULL DEFAULT 0,  usuario character varying(150) NOT NULL,  cargo character varying(255) ,  fecha date,  hora time without time zone,  cedula character varying(15) NOT NULL,  ambito character varying(150),  unidadhosp character varying(150),  numservicio integer,  compania character varying(60) NOT NULL,  cerrado integer,  noliquidacion integer DEFAULT 0,  finalidadconsult character varying(5),  causaexterna character varying(5),  dx1 character varying(6),  dx2 character varying(6),  dx3 character varying(6),  dx4 character varying(6),  dx5 character varying(6),  tipodx character varying(1),  numproced integer,  usuarioajuste character varying(30),  fechaajuste date,  padretipoformato character varying(150),  padreformato character varying(150),  id_historia_origen integer,  cmp00002 text,  cmp00003 text,  cmp00004 text,  cmp00005 text,  cmp00007 text,  cmp00008 text,  cmp00010 text,  cmp00011 text,  cmp00013 text,  cmp00015 text,  cmp00016 text,  cmp00018 text,  idsvital numeric,  cmp00021 text,  cmp00022 text,  cmp00023 text,  CONSTRAINT pkhctbl$tabla PRIMARY KEY (formato , tipoformato , id_historia , cedula , compania ),  CONSTRAINT fkambtbl$tabla FOREIGN KEY (ambito, compania)      REFERENCES salud.ambitos (ambito, compania) MATCH SIMPLE      ON UPDATE CASCADE ON DELETE RESTRICT,  CONSTRAINT fkitemsxtbl$tabla FOREIGN KEY (formato, tipoformato, compania)      REFERENCES historiaclinica.formatos (formato, tipoformato, compania) MATCH SIMPLE      ON UPDATE CASCADE ON DELETE RESTRICT,  CONSTRAINT fkpabxtbl$tabla FOREIGN KEY (unidadhosp, compania, ambito)      REFERENCES salud.pabellones (pabellon, compania, ambito) MATCH SIMPLE      ON UPDATE CASCADE ON DELETE RESTRICT)WITH (  OIDS=FALSE)";	
	  			$res = @pg_query($cnx,$cons);		
				if (!$res) {							
				echo "<p class='error1'> Error de ejecucion </p>".pg_last_error()."<br>";
				echo "<p class= 'subtitulo1'>Comando SQL </p> <br>".$cons."<br/>";
	
				}
			
		}



		
		
		function creartablaMigracion() {
		// Esta funcion crea una tabla con estructura similar a la tabla Postgresql con la diferencia que carece de llave primaria y omite la restriccion NOT NULL,          ademas  crea un nuevo campo llamado error en el cual se almacena la descripcion del error generado en la consulta inicial.
			global $tablafrm;
			$cnx= conectar_postgres();			
			$tablaMig = $tablafrm."Migracion";
			$cons = "CREATE TABLE IF NOT EXISTS histoclinicafrms.$tablaMig(  formato character varying(150) ,  tipoformato character varying(150) ,  id_historia integer  ,  usuario character varying(150) ,  cargo character varying(255) ,  fecha date,  hora time without time zone,  cedula character varying(15) ,  ambito character varying(150),  unidadhosp character varying(150),  numservicio integer,  compania character varying(60) ,  cerrado integer,  noliquidacion integer ,  finalidadconsult character varying(5),  causaexterna character varying(5),  dx1 character varying(6),  dx2 character varying(6),  dx3 character varying(6),  dx4 character varying(6),  dx5 character varying(6),  tipodx character varying(1),  numproced integer,  usuarioajuste character varying(30),  fechaajuste date,  padretipoformato character varying(150),  padreformato character varying(150),  id_historia_origen integer,  cmp00002 text,  cmp00003 text,  cmp00004 text,  cmp00005 text,  cmp00007 text,  cmp00008 text,  cmp00010 text,  cmp00011 text,  cmp00013 text,  cmp00015 text,  cmp00016 text,  cmp00018 text,  idsvital numeric,  cmp00021 text,  cmp00022 text,  cmp00023 text, error text)WITH (  OIDS=FALSE)";	
			$res = @pg_query($cnx, $cons);
				if (!$res) {
				
					echo "<p class='error1'> Error de ejecucion </p>".pg_last_error()."<br>";
					echo "<p class= 'subtitulo1'>Comando SQL </p> <br>".$cons."<br>"; 				
					echo "<br><br>";			
					//echo "<br><br>";
				}
			
		}
		
		
		
		
		
		
		
		
		function insertarRegistroMigracion($formato,$tipoformato,$id_historia,$usuario,$cargo,$fecha,$hora,$cedula,$ambito,$unidadhosp,$numservicio,$compania,$cerrado , $noliquidacion,$finalidadconsult,$causaexterna,$dx1,$dx2,$dx3,$dx4,$dx5,$cmp00003,$cmp00004,$cmp00005,$cmp00007,$cmp00008,$cmp00010, $cmp00011, $cmp00013, $cmp00015, $cmp00016, $cmp00018, $cmp00021, $cmp00022, $cmp00023, $error) {
		//Realiza la insercion en Postgresql con base en los parametros
			global  $tablafrm;
			$tablaMig = $tablafrm."Migracion";
			$cnx = 	conectar_postgres();
			$cons = "INSERT INTO HistoClinicaFrms.$tablaMig (formato,tipoformato,id_historia,usuario,cargo,fecha,hora,cedula,ambito,unidadhosp,numservicio,compania,cerrado , noliquidacion,finalidadconsult,causaexterna,dx1,dx2,dx3,dx4,dx5,cmp00003,cmp00004,cmp00005,cmp00007,cmp00008,cmp00010, cmp00011, cmp00013, cmp00015, cmp00016, cmp00018, cmp00021, cmp00022, cmp00023, error ) VALUES ('$formato','$tipoformato','$id_historia','$usuario','$cargo','$fecha','$hora','$cedula','$ambito','$unidadhosp','$numservicio','$compania','$cerrado', '$noliquidacion','$finalidadconsult','$causaexterna','$dx1','$dx2','$dx3','$dx4','$dx5','$cmp00003','$cmp00004','$cmp00005','$cmp00007','$cmp00008','$cmp00010', '$cmp00011', '$cmp00013', '$cmp00015', '$cmp00016', '$cmp00018', '$cmp00021', '$cmp00022', '$cmp00023', '$error')"	;
					 
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
		
		
		
		
		function insertarRegistroPostgresql($formato,$tipoformato,$id_historia,$usuario,$cargo,$fecha,$hora,$cedula,$ambito,$unidadhosp,$numservicio,$compania,$cerrado , $noliquidacion,$finalidadconsult,$causaexterna,$dx1,$dx2,$dx3,$dx4,$dx5,$cmp00003,$cmp00004,$cmp00005,$cmp00007,$cmp00008,$cmp00010, $cmp00011, $cmp00013, $cmp00015, $cmp00016, $cmp00018, $cmp00021, $cmp00022, $cmp00023) {
		//Realiza la insercion en Postgresql con base en los parametros
			global  $tablafrm;
			$cnx = 	conectar_postgres();
			$cons = "INSERT INTO HistoClinicaFrms.$tablafrm (formato,tipoformato,id_historia,usuario,cargo,fecha,hora,cedula,ambito,unidadhosp,numservicio,compania,cerrado , noliquidacion,finalidadconsult,causaexterna,dx1,dx2,dx3,dx4,dx5,cmp00003,cmp00004,cmp00005,cmp00007,cmp00008,cmp00010, cmp00011, cmp00013, cmp00015, cmp00016, cmp00018, cmp00021, cmp00022, cmp00023) VALUES ('$formato','$tipoformato','$id_historia','$usuario','$cargo','$fecha','$hora','$cedula','$ambito','$unidadhosp','$numservicio','$compania','$cerrado', '$noliquidacion','$finalidadconsult','$causaexterna','$dx1','$dx2','$dx3','$dx4','$dx5','$cmp00003','$cmp00004','$cmp00005','$cmp00007','$cmp00008','$cmp00010', '$cmp00011', '$cmp00013', '$cmp00015', '$cmp00016', '$cmp00018', '$cmp00021', '$cmp00022', '$cmp00023')"	;
			$cons = str_replace( "'NULL'","NULL",$cons  );	
			$res = @pg_query($cnx, $cons);
				if (!$res) {
					$consUTF8 = utf8_encode($cons);
					$resUTF8 = @pg_query($cnx, $consUTF8);					
						if (!$resUTF8) {
							$error = pg_last_error();
							$error = eliminarCaracteresEspeciales($error);
							insertarRegistroMigracion($formato,$tipoformato,$id_historia,$usuario,$cargo,$fecha,$hora,$cedula,$ambito,$unidadhosp,$numservicio,$compania,$cerrado , $noliquidacion,$finalidadconsult,$causaexterna,$dx1,$dx2,$dx3,$dx4,$dx5,$cmp00003,$cmp00004,$cmp00005,$cmp00007,$cmp00008,$cmp00010, $cmp00011, $cmp00013, $cmp00015, $cmp00016, $cmp00018, $cmp00021, $cmp00022, $cmp00023, $error);					
							
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
					$matriz["fecha"][$posicion] = $fila["Fecha"];
					$matriz["hora"][$posicion] = $fila["hora"];
					$matriz["ta"][$posicion] = $fila["TA"];					
					$matriz["pulso"][$posicion] = $fila["Pulso"];
					$matriz["temperatura"][$posicion] = $fila["Temperatura"];
					$matriz["tiporesp"][$posicion] = $fila["TipoResp"];
					$matriz["fr"][$posicion] = $fila["FR"];
					$matriz["od"][$posicion] = $fila["OD"];
					$matriz["oi"][$posicion] = $fila["OI"];
					$matriz["estadoconciencia"][$posicion] = $fila["EstadoConciencia"];
					$matriz["mmss"][$posicion] = $fila["MMSS"];
					$matriz["mmii"][$posicion] = $fila["MMII"];
					$matriz["convulsiones"][$posicion] = $fila["Convulsiones"];
					
					
														
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
					$cargo = consultarCargoMedMySQL($usuario);
					$rm = consultarRegMedMySQL($usuario);
					
					$usuario_rm =  configurarRM($usuario, $rm, $cargo);
					
					$usuario = normalizarMedicos($usuario);
					$usuario = eliminarCaracteresEspeciales($usuario);					
					
					$cargo =normalizarCargos($cargo);
					$cargo = eliminarCaracteresEspeciales($cargo);
					
					$rm = eliminarCaracteresEspeciales($rm);
					
					
					
					
					$fecha=$matriz["fecha"][$pos];
						if ($fecha == "0000-00-00 00:00" or $fecha == "0000-00-00" ) {
							$fecha = 'NULL';
						}
						
					$hora=$matriz["hora"][$pos];
						if ($hora == "00:00" ) {
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
					
					$cmp00003=$matriz["ta"][$pos];
					$cmp00003 = eliminarCaracteresEspeciales($cmp00003);
					
					$cmp00004=$matriz["pulso"][$pos];
					$cmp00004 = eliminarCaracteresEspeciales($cmp00004);
					
					$cmp00005=$matriz["temperatura"][$pos];
					$cmp00005 = eliminarCaracteresEspeciales($cmp00005);
					
					$cmp00007=$matriz["tiporesp"][$pos];
					$cmp00007 = eliminarCaracteresEspeciales($cmp00007);
					
					$cmp00008=$matriz["fr"][$pos];
					$cmp00008 = eliminarCaracteresEspeciales($cmp00008);
					
					$cmp00010=$matriz["od"][$pos];
					$cmp00010 = eliminarCaracteresEspeciales($cmp00010);
					
					$cmp00011=$matriz["oi"][$pos];
					$cmp00011 = eliminarCaracteresEspeciales($cmp00011);
					
					$cmp00013=$matriz["estadoconciencia"][$pos];
					$cmp00013 = eliminarCaracteresEspeciales($cmp00013);
					
					$cmp00015=$matriz["mmss"][$pos];
					$cmp00015 = eliminarCaracteresEspeciales($cmp00015);
					
					$cmp00016=$matriz["mmii"][$pos];
					$cmp00016 = eliminarCaracteresEspeciales($cmp00016);
					
					$cmp00018=$matriz["convulsiones"][$pos];
					$cmp00018 = eliminarCaracteresEspeciales($cmp00018);
					
					$dx1 = 'NULL';
					
					$cerrado = 'NULL';
					$noliquidacion = 'NULL';
					
					$id_historia = $pos +1 ;
					
					$cerrado = 'NULL';
					$numproced = 'NULL';
					
					
					
					insertarRegistroPostgresql($formato,$tipoformato,$id_historia,$usuario_rm,$cargo,$fecha,$hora,$cedula,$ambito,$unidadhosp,$numservicio,$compania,$cerrado , $noliquidacion,$finalidadconsult,$causaexterna,$dx1,$dx2,$dx3,$dx4,$dx5,$cmp00003,$cmp00004,$cmp00005,$cmp00007,$cmp00008,$cmp00010, $cmp00011, $cmp00013, $cmp00015, $cmp00016, $cmp00018, $cmp00021, $cmp00022, $cmp00023);
					
					
						/*if ($requierevobo != "0" and  strtoupper($requierevobo) != "PSIQUIATRA" and trim ($requierevobo) != "" ){
							insertarRegistroVoBoxFormatos($usuariovobo_rm, $fechavobo, $tipoformato, $formato, $cargovobo, $compania, $id_historia);
													
						}*/
							
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
				/*normalizarCodificacionMedicos('&Aacute;',utf8_encode("�"));			
				normalizarCodificacionMedicos('&Eacute;',utf8_encode("�"));
				normalizarCodificacionMedicos('&Iacute;',utf8_encode("�"));
				normalizarCodificacionMedicos('&Oacute;',utf8_encode("�"));
				normalizarCodificacionMedicos('&Uacute;',utf8_encode("�"));
				normalizarCodificacionMedicos('&Ntilde;',utf8_encode("�"));*/
				
				creartablaMigracion();
				eliminarRegistrosMigracion();
				eliminarRegistros();
				crearArchivoErrores();
				llenarMatriz();
				recorrerMatriz();
				
				//Tabla Formato Autonomo 
				normalizarCodificacionFormato('&Aacute;', utf8_encode("�"));			
				normalizarCodificacionFormato('&Eacute;', utf8_encode("�"));
				normalizarCodificacionFormato('&Iacute;', utf8_encode("�"));
				normalizarCodificacionFormato('&Oacute;', utf8_encode("�"));
				normalizarCodificacionFormato('&Uacute;',utf8_encode("�"));
				normalizarCodificacionFormato('&Ntilde;',utf8_encode("�"));
				//actualizarMedico("HistoClinicaFrms", "" , $campo, $valorBusqueda, $valorReemplazo)
			
			
			}
			
			
		
		
		function migrarEstructuraFormato() {
			
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
			
			//eliminarVoBoxFormatos($tipoformato, $formato);
			//insertarVoBoxFormatos();
			
			// CupsxFormatos
			
			//eliminarCupsxFormatos($tipoformato, $formato);
			/*insertarCupsxFormatos();*/
			
			
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
			/*eliminarDxFormatos($formato, $tipoformato);
			
			$usuariocre = "ADMINISTRADOR";
			$fechacre = FechaActual();
			$usuario = $usuariocre;
			$fecha = $fechacre;
			$id = 1;
			$detalle = "DIAGNOSTICO PRINCIPAL";
			$tipo = "Principal";
			$estado = "AC";
			$pantalla = 1;
			$iditem = 5;
			$cie10 = "1";
			$tagxml = 'NULL';
			$etiquetaxml = 'NULL';
			
			insertarDxFormatosSQL($compania, $usuario, $fecha, $id, $detalle, $tipo, $formato, $tipoformato, $estado, $pantalla, $iditem, $cie10, $tagxml, $etiquetaxml);
			
			$id = 2;
			$detalle = "DIAGNOSTICO RELACIONADO";
			$tipo = "Relacionado";
			$estado = "AC";
			$pantalla = 1;
			$iditem = 5;
			$cie10 = "1";
			$tagxml = 'NULL';
			$etiquetaxml = 'NULL';
			
			insertarDxFormatosSQL($compania, $usuario, $fecha, $id, $detalle, $tipo, $formato, $tipoformato, $estado, $pantalla, $iditem, $cie10, $tagxml, $etiquetaxml);
			*/
			
			
			echo "<div align='center'> <p class='mensajeFinalizacion'>Ha terminado la migracion del Formato 'ControlNeurologico'</p> </div>";
	
		}
		
		
		
		
		
		
		
		if($_GET['formato']="ControlNeurologico") {
		
				echo "<fieldset>";			
				echo "<legend> Migracion tabla MySQL </legend>";
				echo "<br>";
				echo "<span align='left'> <a href='../../index.php?migracion=MIG035' class = 'link1'> Panel de Administracion </a> </span>";
				echo "<br>";
				
				global $tablafrm, $tipoformato, $formato ;
				$tablafrm = "tbl00017";
				$tipoformato = "ENFERMERIA";
				$formato = "CONTROL NEUROLOGICO";
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
					
					?><p> <a href="<?php echo $archivo;?>" class = "link1" target="_blank"> Ver Reporte de errores de la migracion </a> </p><?php
					
					echo "</fieldset>";
			
			}
		
		
		
	
	
	
	?>
