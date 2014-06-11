	<html>	
		<head>
			<title> Migracion Formato Incapacidad</title>
			<link rel="stylesheet" type="text/css" href="../../General/estilos/estilos.css">
		</head>
	
	
	<?php
		session_start();
		include_once('../../General/funciones/funciones.php');
		include_once('../../Conexiones/conexion.php');
		include_once('../General/procedimiento.php');
		
		
		
		
		function contarRegistrosMySQL() {
			$cnx = conectar_mysql("Salud");
			$cons = "SELECT COUNT(*) AS conteomysql FROM Salud.PlanAlta";
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
				$cons= "COPY HistoriaClinica.Formatos FROM '$ruta/Migraciones/HistoriaClinicaFrms/HistoriaClinica/PlanAlta/Formatos.csv' WITH DELIMITER ';' CSV HEADER;";
				$res =  @pg_query($cons);
					if (!$res) {
						echo "<p class='error1'> Error de ejecucion </p>".pg_last_error()."<br>";
						echo "<p class= 'subtitulo1'>Comando SQL </p> <br>".$cons."<br/>";
							
					}
			
			}
			
			
			function insertarItemsxFormatos() {
		
				$cnx = conectar_postgres();
				$ruta = $_SERVER['DOCUMENT_ROOT'];
				$cons= "COPY HistoriaClinica.ItemsxFormatos FROM '$ruta/Migraciones/HistoriaClinicaFrms/HistoriaClinica/PlanAlta/ItemsxFormatos.csv' WITH DELIMITER ';' CSV HEADER;";
				$res =  @pg_query($cons);
					if (!$res) {
						echo "<p class='error1'> Error de ejecucion </p>".pg_last_error()."<br>";
						echo "<p class= 'subtitulo1'>Comando SQL </p> <br>".$cons."<br/>";
							
					}
			
			}
			
			
			
			
			function insertarPermisosxFormato() {
		
				$cnx = conectar_postgres();
				$ruta = $_SERVER['DOCUMENT_ROOT'];
				$cons= "COPY HistoriaClinica.PermisosxFormato FROM '$ruta/Migraciones/HistoriaClinicaFrms/HistoriaClinica/PlanAlta/PermisosxFormato.csv' WITH DELIMITER ';' CSV HEADER;";
				$res =  @pg_query($cons);
					if (!$res) {
						echo "<p class='error1'> Error de ejecucion </p>".pg_last_error()."<br>";
						echo "<p class= 'subtitulo1'>Comando SQL </p> <br>".$cons."<br/>";
							
					}
			
			}
			
						
			function insertarAmbitosxFormato() {
		
				$cnx = conectar_postgres();
				$ruta = $_SERVER['DOCUMENT_ROOT'];
				$cons= "COPY HistoriaClinica.AmbitosxFormato FROM '$ruta/Migraciones/HistoriaClinicaFrms/HistoriaClinica/PlanAlta/AmbitosxFormato.csv' WITH DELIMITER ';' CSV HEADER;";
				$res =  @pg_query($cons);
					if (!$res) {
						echo "<p class='error1'> Error de ejecucion </p>".pg_last_error()."<br>";
						echo "<p class= 'subtitulo1'>Comando SQL </p> <br>".$cons."<br/>";
							
					}
			
			}
			
			function insertarDxFormatos() {
		
				$cnx = conectar_postgres();
				$ruta = $_SERVER['DOCUMENT_ROOT'];
				$cons= "COPY HistoriaClinica.DxFormatos FROM '$ruta/Migraciones/HistoriaClinicaFrms/HistoriaClinica/PlanAlta/DxFormatos.csv' WITH DELIMITER ';' CSV HEADER;";
				$res =  @pg_query($cons);
					if (!$res) {
						echo "<p class='error1'> Error de ejecucion </p>".pg_last_error()."<br>";
						echo "<p class= 'subtitulo1'>Comando SQL </p> <br>".$cons."<br/>";
							
					}
			
			}
			
			
			function insertarCupsxFormatos() {
		
				$cnx = conectar_postgres();
				$ruta = $_SERVER['DOCUMENT_ROOT'];
				$cons= "COPY HistoriaClinica.CupsxFormatos FROM '$ruta/Migraciones/HistoriaClinicaFrms/HistoriaClinica/PlanAlta/CupsxFormatos.csv' WITH DELIMITER ';' CSV HEADER;";
				$res =  @pg_query($cons);
					if (!$res) {
						echo "<p class='error1'> Error de ejecucion </p>".pg_last_error()."<br>";
						echo "<p class= 'subtitulo1'>Comando SQL </p> <br>".$cons."<br/>";
							
					}
			
			}
			
			
			
			
			function insertarVoBoxFormatos() {
		
				$cnx = conectar_postgres();
				$ruta = $_SERVER['DOCUMENT_ROOT'];
				$cons= "COPY HistoriaClinica.VoBoxFormatos FROM '$ruta/Migraciones/HistoriaClinicaFrms/HistoriaClinica/PlanAlta/VoBoxFormatos.csv' WITH DELIMITER ';' CSV HEADER;";
				$res =  @pg_query($cons);
					if (!$res) {
						echo "<p class='error1'> Error de ejecucion </p>".pg_last_error()."<br>";
						echo "<p class= 'subtitulo1'>Comando SQL </p> <br>".$cons."<br/>";
							
					}
			
			}
			
			
			function insertarAjustePermanente() {
		
				$cnx = conectar_postgres();
				$ruta = $_SERVER['DOCUMENT_ROOT'];
				$cons= "COPY HistoriaClinica.AjustePermanente FROM '$ruta/Migraciones/HistoriaClinicaFrms/HistoriaClinica/PlanAlta/AjustePermanente.csv' WITH DELIMITER ';' CSV HEADER;";
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
			$cons = "UPDATE HistoClinicaFrms.$tablafrm SET formato = replace( formato,'$cadenaBusqueda','$cadenaReemplazo'), tipoformato = replace( tipoformato,'$cadenaBusqueda','$cadenaReemplazo') , compania = replace( compania,'$cadenaBusqueda','$cadenaReemplazo'), cargo = replace( cargo,'$cadenaBusqueda','$cadenaReemplazo') , ambito = replace( ambito,'$cadenaBusqueda','$cadenaReemplazo'), unidadhosp = replace( unidadhosp,'$cadenaBusqueda','$cadenaReemplazo') , finalidadconsult = replace( finalidadconsult,'$cadenaBusqueda','$cadenaReemplazo') , dx1 = replace( dx1,'$cadenaBusqueda','$cadenaReemplazo') , dx2 = replace( dx2,'$cadenaBusqueda','$cadenaReemplazo') , dx3 = replace( dx3,'$cadenaBusqueda','$cadenaReemplazo') , dx4 = replace( dx4,'$cadenaBusqueda','$cadenaReemplazo') , dx5 = replace( dx5,'$cadenaBusqueda','$cadenaReemplazo'),   cmp00004=replace(cmp00004,'$cadenaBusqueda','$cadenaReemplazo')";
			
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
			$encabezado = "<html> <head> <title> Reporte errores Formato Control  Medicina General </title> 
			<link rel='stylesheet' type='text/css' href='../../General/estilos/estilos.css'> </head>";
			fputs($fp, $encabezado);
			fclose($fp);
		}
		
		function llamarRegistrosMySQL() {
			// Selecciona los registros MySQL (Origen)
			global $res;
			$cnx = conectar_mysql("Salud");
			$cons = "SELECT PlanAlta.* FROM Salud.PlanAlta  ORDER BY Fecha ASC, Hora ASC ";
			$res =  @mysql_query($cons);
			if (!$res)  {
					echo "<p class='error1'> Error en la normalizacion de la codificacion </p>".mysql_error()."<br>";
					echo "<p class= 'subtitulo1'>Comando SQL </p> <br>".$cons."<br/>";
				}
			return $res; 
		
		}
		
		function crearTablaFormato($tabla){
				$cnx = conectar_postgres();				
				$cons= "CREATE TABLE histoclinicafrms.$tabla(  formato character varying(150) NOT NULL,  tipoformato character varying(150) NOT NULL,  id_historia integer NOT NULL DEFAULT 0,  usuario character varying(150) NOT NULL,  cargo character varying(80) NOT NULL,  fecha date,  hora time without time zone,  cedula character varying(15) NOT NULL,  ambito character varying(150),  unidadhosp character varying(150),  numservicio integer,  compania character varying(60) NOT NULL,  cerrado integer,  noliquidacion integer DEFAULT 0,  finalidadconsult character varying(5),  causaexterna character varying(5),  dx1 character varying(6),  dx2 character varying(6),  dx3 character varying(6),  dx4 character varying(6),  dx5 character varying(6),  tipodx character varying(1),  numproced integer,  usuarioajuste character varying(30),  fechaajuste date,  padretipoformato character varying(150),  padreformato character varying(150),  id_historia_origen integer,  cmp00004 text,  idsvital numeric,  CONSTRAINT pkhctbl$tabla PRIMARY KEY (formato , tipoformato , id_historia , cedula , compania ),  CONSTRAINT fkambtbl$tabla FOREIGN KEY (ambito, compania)      REFERENCES salud.ambitos (ambito, compania) MATCH SIMPLE      ON UPDATE CASCADE ON DELETE RESTRICT,  CONSTRAINT fkitemsxtbl$tabla FOREIGN KEY (formato, tipoformato, compania)      REFERENCES historiaclinica.formatos (formato, tipoformato, compania) MATCH SIMPLE      ON UPDATE CASCADE ON DELETE RESTRICT,  CONSTRAINT fkpabxtbl$tabla FOREIGN KEY (unidadhosp, compania, ambito)      REFERENCES salud.pabellones (pabellon, compania, ambito) MATCH SIMPLE      ON UPDATE CASCADE ON DELETE RESTRICT)WITH (  OIDS=FALSE)";	
				$res = @pg_query($cnx,$cons);		
				if (!$res) {							
				echo "<p class='error1'> Error de ejecucion </p>".pg_last_error()."<br>";
				echo "<p class= 'subtitulo1'>Comando SQL </p> <br>".$cons."<br/>";
	
				}
			
		}
		
		
		
		function crearTablaMigracion() {
		// Esta funcion crea una tabla con estructura similar a la tabla Postgresql con la diferencia que carece de llave primaria y omite la restriccion NOT NULL,          ademas  crea un nuevo campo llamado error en el cual se almacena la descripcion del error generado en la consulta inicial.
			global $tablafrm;
			$cnx= conectar_postgres();			
			$tablaMig = $tablafrm."Migracion";
			$cons = "CREATE TABLE IF NOT EXISTS  histoclinicafrms.$tablaMig(  formato character varying(150) ,  tipoformato character varying(150) ,  id_historia integer  ,  usuario character varying(150) ,  cargo character varying(80) ,  fecha date,  hora time without time zone,  cedula character varying(15) ,  ambito character varying(150),  unidadhosp character varying(150),  numservicio integer,  compania character varying(60) ,  cerrado integer,  noliquidacion integer ,  finalidadconsult character varying(5),  causaexterna character varying(5),  dx1 character varying(6),  dx2 character varying(6),  dx3 character varying(6),  dx4 character varying(6),  dx5 character varying(6),  tipodx character varying(1),  numproced integer,  usuarioajuste character varying(30),  fechaajuste date,  padretipoformato character varying(150),  padreformato character varying(150),  id_historia_origen integer,  cmp00004 text,  idsvital numeric,error text)WITH (  OIDS=FALSE)";		
			$res = @pg_query($cnx, $cons);	
			if (!$res) {
				
					echo "<p class='error1'> Error de ejecucion </p>".pg_last_error()."<br>";
					echo "<p class= 'subtitulo1'>Comando SQL </p> <br>".$cons."<br>"; 				
					echo "<br><br>";			
					//echo "<br><br>";
			}
			
		}
		
		
		
		
		
		
		function insertarRegistroMigracion($formato,$tipoformato,$id_historia,$usuario_rm,$cargo,$fecha,$hora,$cedula,$ambito,$unidadhosp,$numservicio,$compania,$cerrado , $noliquidacion,$finalidadconsult,$causaexterna,$dx1,$dx2,$dx3,$dx4,$dx5,$cmp00004, $error) {
		//Realiza la insercion en Postgresql con base en los parametros
			global  $tablafrm;
			$tablaMig = $tablafrm."Migracion";
			$cnx = 	conectar_postgres();
			$cons = "INSERT INTO HistoClinicaFrms.$tablaMig (formato,tipoformato,id_historia,usuario,cargo,fecha,hora,cedula,ambito,unidadhosp,numservicio,compania,cerrado , noliquidacion,finalidadconsult,causaexterna,dx1,dx2,dx3,dx4,dx5,cmp00004, error) VALUES ('$formato','$tipoformato','$id_historia','$usuario_rm','$cargo','$fecha','$hora','$cedula','$ambito','$unidadhosp','$numservicio','$compania','$cerrado', '$noliquidacion','$finalidadconsult','$causaexterna','$dx1','$dx2','$dx3','$dx4','$dx5','$cmp00004', '$error')"	;
					 
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
		
		
		
		
		function insertarRegistroPostgresql($formato,$tipoformato,$id_historia,$usuario_rm,$cargo,$fecha,$hora,$cedula,$ambito,$unidadhosp,$numservicio,$compania,$cerrado , $noliquidacion,$finalidadconsult,$causaexterna,$dx1,$dx2,$dx3,$dx4,$dx5,$cmp00004) {
		//Realiza la insercion en Postgresql con base en los parametros
			global  $tablafrm;
			$cnx = 	conectar_postgres();
			$cons = "INSERT INTO HistoClinicaFrms.$tablafrm (formato,tipoformato,id_historia,usuario,cargo,fecha,hora,cedula,ambito,unidadhosp,numservicio,compania,cerrado , noliquidacion,finalidadconsult,causaexterna,dx1,dx2,dx3,dx4,dx5,cmp00004) VALUES ('$formato','$tipoformato','$id_historia','$usuario_rm','$cargo','$fecha','$hora','$cedula','$ambito','$unidadhosp','$numservicio','$compania','$cerrado', '$noliquidacion','$finalidadconsult','$causaexterna','$dx1','$dx2','$dx3','$dx4','$dx5','$cmp00004')"	;
			$cons = str_replace( "'NULL'","NULL",$cons  );	
			$res = @pg_query($cnx, $cons);
				if (!$res) {
					$consUTF8 = utf8_encode($cons);
					$resUTF8 = @pg_query($cnx, $consUTF8);					
						if (!$resUTF8) {
							$error = pg_last_error();
							$error = eliminarCaracteresEspeciales($error);
							insertarRegistroMigracion($formato,$tipoformato,$id_historia,$usuario_rm,$cargo,$fecha,$hora,$cedula,$ambito,$unidadhosp,$numservicio,$compania,$cerrado , $noliquidacion,$finalidadconsult,$causaexterna,$dx1,$dx2,$dx3,$dx4,$dx5,$cmp00004, $error) ;
							
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
					$matriz["psiquiatria"][$posicion] = $fila["Psiquiatria"];
					$matriz["psicologia"][$posicion] = $fila["Psicologia"];
					$matriz["educador"][$posicion] = $fila["Educador"];
					$matriz["nutricionista"][$posicion] = $fila["Nutricionista"];
					$matriz["terapeuta"][$posicion] = $fila["Terapeuta"];
					$matriz["enfermeria"][$posicion] = $fila["Enfermeria"];
					$matriz["gsocial"][$posicion] = $fila["GSocial"];					
					
					$matriz["coddiagnostico"][$posicion] = $fila["CodDx"];
					$matriz["nombrediagnostico"][$posicion] = $fila["NomDx"];
					
					/*$matriz["requierevobo"][$posicion] = $fila["RequiereVoBo"];
					$matriz["cargovobo"][$posicion] = $fila["CargoVoBo"];
					$matriz["rmvobo"][$posicion] = $fila["RMVoBo"];
					$matriz["fechavobo"][$posicion] = $fila["FechaVoBo"];*/
					
														
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
					
					$psiquiatria = $matriz["psiquiatria"][$pos];
					$psiquiatria = eliminarCaracteresEspFormatos($psiquiatria);	
					
					$psicologia = $matriz["psicologia"][$pos];
					$psicologia = eliminarCaracteresEspFormatos($psicologia);


					$educador = $matriz["educador"][$pos];
					$educador = eliminarCaracteresEspFormatos($educador);
					
					$nutricionista = $matriz["nutricionista"][$pos];
					$nutricionista = eliminarCaracteresEspFormatos($nutricionista);
					
					$terapeuta = $matriz["terapeuta"][$pos];
					$terapeuta = eliminarCaracteresEspFormatos($terapeuta);
					
					$enfermeria = $matriz["enfermeria"][$pos];
					$enfermeria = eliminarCaracteresEspFormatos($enfermeria);
					
					$gsocial = $matriz["gsocial"][$pos];
					$gsocial = eliminarCaracteresEspFormatos($gsocial);
					
						if (strtoupper($cargo) == "PSIQUIATRIA"){
							$cmp00004 = $psiquiatria;					
						}
						if (strtoupper($cargo) == "PSICOLOGIA"){
							$cmp00004 = $psicologia;					
						}
						if (strtoupper($cargo) == "EDUCADOR"){
							$cmp00004 = $educador;					
						}
						if (strtoupper($cargo) == "NUTRICIONISTA"){
							$cmp00004 = $nutricionista;					
						}
						if (strtoupper($cargo) == "TERAPEUTA"){
							$cmp00004 = $terapeuta;					
						}
						if (strtoupper($cargo) == "ENFERMERIA"){
							$cmp00004 = $enfermeria;					
						}
						if (strtoupper($cargo) == "GSOCIAL"){
							$cmp00004 = $gsocial;					
						}
						
					
					
					
					
					
					
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
					$ambito = normalizarAmbitos($ambito);

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
						
					
					
					$otrosdx =  $matriz["otrosdx"][$pos];
					$otrosdx = eliminarCaracteresEspFormatos($otrosdx);
					
					$cmp00005 = $otrosdx."<br><br>".$anotacion;
					
					$cmp00007 = 'NULL'; // Se deja este campo como NULL porque es un campo nuevo. No hay valores a migrar
					
					
					$cmp00009='NULL';
					
					
					$dx1 = $matriz["coddiagnostico"][$pos];	
					$dx1 = eliminarCaracteresEspeciales($dx1);	
					
					$nombrediagnostico = $matriz["nombrediagnostico"][$pos];	
					$nombrediagnostico = eliminarCaracteresEspeciales($nombrediagnostico);	
						
						if (empty($dx1)){
							$dx1 = $nombrediagnostico;
						}
					
					
										
					
					$cerrado = 'NULL';
					$noliquidacion = 'NULL';
					
					$id_historia = $pos + 1 ;
					
					$cerrado = 'NULL';
					$numproced = 'NULL';
					
					/*$requierevobo=$matriz["requierevobo"][$pos];
					$requierevobo = eliminarCaracteresEspeciales($requierevobo);
					
					$cargovobo=$matriz["cargovobo"][$pos];
					$cargovobo = normalizarCargos($cargovobo);
					$cargovobo = eliminarCaracteresEspeciales($cargovobo);
					
					$rmvobo=$matriz["rmvobo"][$pos];
					$rmvobo = eliminarCaracteresEspeciales($rmvobo);
					
					$usuariovobo_rm =  configurarRM($requierevobo, $rmvobo, $cargovobo);
					
					$fechavobo=$matriz["fechavobo"][$pos];
					$fechavobo = eliminarCaracteresEspeciales($fechavobo);*/
					
					
					
					insertarRegistroPostgresql($formato,$tipoformato,$id_historia,$usuario_rm,$cargo,$fecha,$hora,$cedula,$ambito,$unidadhosp,$numservicio,$compania,$cerrado , $noliquidacion,$finalidadconsult,$causaexterna,$dx1,$dx2,$dx3,$dx4,$dx5,$cmp00004);
					
						/*if ($requierevobo != "0" and  strtoupper($requierevobo) != "PSIQUIATRA" and trim ($requierevobo) != "" ){
							if ($fechavobo == "0000-00-00 00:00:00" or  $fechavobo == "0000-00-00"){
								$fechavobo = $fecha;	
							}
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
			$iditem = 6;
			$cie10 = "1";
			$tagxml = 'NULL';
			$etiquetaxml = 'NULL';
			
			insertarDxFormatosSQL($compania, $usuario, $fecha, $id, $detalle, $tipo, $formato, $tipoformato, $estado, $pantalla, $iditem, $cie10, $tagxml, $etiquetaxml);
			
			$id = 2;
			$detalle = "DIAGNOSTICO RELACIONADO";
			$tipo = "Relacionado";
			$estado = "AC";
			$pantalla = 1;
			$iditem = 6;
			$cie10 = "1";
			$tagxml = 'NULL';
			$etiquetaxml = 'NULL';
			
			insertarDxFormatosSQL($compania, $usuario, $fecha, $id, $detalle, $tipo, $formato, $tipoformato, $estado, $pantalla, $iditem, $cie10, $tagxml, $etiquetaxml);
			
			
			
												
			
			
			// Tabla HistoriaClinica.ItemsxFormatos
			normalizarCodificacionItemsxFormatos('&Aacute;', utf8_encode("Á"));			
			normalizarCodificacionItemsxFormatos('&Eacute;', utf8_encode("É"));
			normalizarCodificacionItemsxFormatos('&Iacute;', utf8_encode("Í"));
			normalizarCodificacionItemsxFormatos('&Oacute;', utf8_encode("Ó"));
			normalizarCodificacionItemsxFormatos('&Uacute;',utf8_encode("Ú"));
			normalizarCodificacionItemsxFormatos('&Ntilde;',utf8_encode("Ñ"));
			normalizarCodificacionItemsxFormatos('&aacute;', utf8_encode("á"));			
			normalizarCodificacionItemsxFormatos('&eacute;', utf8_encode("é"));
			normalizarCodificacionItemsxFormatos('&iacute;', utf8_encode("í"));
			normalizarCodificacionItemsxFormatos('&oacute;', utf8_encode("ó"));
			normalizarCodificacionItemsxFormatos('&uacute;',utf8_encode("ú"));
			normalizarCodificacionItemsxFormatos('&ntilde;',utf8_encode("ñ"));
			
			// Tabla RequiereVoBoxFormatos   
			
			/*normalizarCodificacionRequiereVoBo('&Aacute;', utf8_encode("Á"));			
			normalizarCodificacionRequiereVoBo('&Eacute;', utf8_encode("É"));
			normalizarCodificacionRequiereVoBo('&Iacute;', utf8_encode("Í"));
			normalizarCodificacionRequiereVoBo('&Oacute;', utf8_encode("Ó"));
			normalizarCodificacionRequiereVoBo('&Uacute;',utf8_encode("Ú"));
			normalizarCodificacionRequiereVoBo('&Ntilde;',utf8_encode("Ñ"));
			normalizarCodificacionRequiereVoBo('&aacute;', utf8_encode("á"));			
			normalizarCodificacionRequiereVoBo('&eacute;', utf8_encode("é"));
			normalizarCodificacionRequiereVoBo('&iacute;', utf8_encode("í"));
			normalizarCodificacionRequiereVoBo('&oacute;', utf8_encode("ó"));
			normalizarCodificacionRequiereVoBo('&uacute;',utf8_encode("ú"));
			normalizarCodificacionRequiereVoBo('&ntilde;',utf8_encode("ñ"));*/
			
								
			
			// AjustePermanente

			echo "<div align='center'> <p class='mensajeFinalizacion'>Ha terminado la migracion del formato Incapacidad </p> </div>";
	
		}
			
		
			
			function migrarRegistrosFormato(){
				
				global $tipoformato, $formato;
				
				// Tabla Salud.Medicos
				/*normalizarCodificacionMedicos('&Aacute;',utf8_encode("Á"));			
				normalizarCodificacionMedicos('&Eacute;',utf8_encode("É"));
				normalizarCodificacionMedicos('&Iacute;',utf8_encode("Í"));
				normalizarCodificacionMedicos('&Oacute;',utf8_encode("Ó"));
				normalizarCodificacionMedicos('&Uacute;',utf8_encode("Ú"));
				normalizarCodificacionMedicos('&Ntilde;',utf8_encode("Ñ"));*/
				
				
				eliminarRegistrosMigracion();
				eliminarRegistros();
				eliminarRegistroVoBoxFormatos($tipoformato, $formato);
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
			
			
		
		
		
		if($_GET['formato']="PlanAlta") {
		
				echo "<fieldset>";			
				echo "<legend> Migracion tabla MySQL </legend>";
				echo "<br>";
				echo "<span align='left'> <a href='../../index.php?migracion=MIG051' class = 'link1'> Panel de Administracion </a> </span>";
				echo "<br>";
				
				global $tablafrm, $tipoformato, $formato ;
				$tablafrm = "tbl00033";
				$tipoformato = "HISTORIA CLINICA";
				$formato = "PLAN DE ALTA";
				$archivo = "Errores".$tablafrm.".html";
				
				
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
