	<html>	
		<head>
			<title> Migracion Salud.Servicios </title>
			<link rel="stylesheet" type="text/css" href="../../General/estilos/estilos.css">
		</head>
	
	
	<?php
		session_start();
		include_once '../../General/funciones/funciones.php';
		include_once '../../Conexiones/conexion.php';
		include_once '../PagadorxServicios/procedimiento.php';
		
		
		
		
		/* Inicia defincion de funciones */
		
			
		function  normalizarCodificacionServicios($cadenaBusqueda,$cadenaReemplazo)  {
		// Busca y reemplaza ocurrencias en una tabla
			$cnx= conectar_postgres();
			$cons = "UPDATE Salud.Servicios SET tipousu = replace( tipousu,'$cadenaBusqueda','$cadenaReemplazo'),  nivelusu = replace( nivelusu,'$cadenaBusqueda','$cadenaReemplazo') ,  tiposervicio = replace( tiposervicio,'$cadenaBusqueda','$cadenaReemplazo'),  compania = replace( compania,'$cadenaBusqueda','$cadenaReemplazo'),  medicotte = replace( medicotte,'$cadenaBusqueda','$cadenaReemplazo'),  usuarioingreso = replace( usuarioingreso,'$cadenaBusqueda','$cadenaReemplazo') ";
			
			$res = pg_query($cnx , $cons);
				if (!$res)  {
					echo "<p class='error1'> Error en la normalizacion de la codificacion </p>".pg_last_error()."<br>";
					echo"<p class= 'subtitulo1'>Comando SQL </p> <br>".$cons."<br/> <br/> <br/>";  
				}

		}
		
		
		function  normalizarCodificacionEPS($cadenaBusqueda,$cadenaReemplazo)  {
		// Busca y reemplaza ocurrencias en una tabla
			$cnx= conectar_postgres();
			$cons = "UPDATE Central.Terceros SET primape = replace( primape,'$cadenaBusqueda','$cadenaReemplazo') WHERE UPPER(Tipo) = 'ASEGURADOR'";
			
			$res = pg_query($cnx , $cons);
				if (!$res)  {
					echo "<p class='error1'> Error en la normalizacion de la codificacion </p>".pg_last_error()."<br>";
					echo"<p class= 'subtitulo1'>Comando SQL </p> <br>".$cons."<br/> <br/> <br/>";  
				}

		}
		
		
		
		
		function contarRegistrosPostgresql() {
			$cnx= conectar_postgres();
			$cons = "SELECT COUNT(*) AS conteo FROM Salud.Servicios";
			$res =  pg_query($cnx, $cons);
			$fila = pg_fetch_array($res);
			$res = $fila['conteo'];
			return $res; 	
		
		}
		
		function contarRegistrosPostgresqlErrores() {
			$cnx= conectar_postgres();
			$cons = "SELECT COUNT(*) AS conteo FROM Salud.ServiciosMigracion";
			$res =  pg_query($cnx, $cons);
			$fila = pg_fetch_array($res);
			$res = $fila['conteo'];
			return $res; 	
		
		}
		
		
		
		function contarRegistrosMySQL() {
			// Selecciona los registros MySQL (Origen)
			global $res;
			$cnx = conectar_mysql('salud');
			$cons = "SELECT COUNT(*) AS conteo FROM Salud.Hospitalizacion";
			
			$res =  mysql_query($cons);
			$fila = mysql_fetch_array($res);
			$numregistros = $fila["conteo"];
			
			return $numregistros; 
		
		}		
				
	
		function llamarRegistrosMySQLServicios($limiteinicial, $limitefinal) {
			// Selecciona los registros MySQL (Origen)
			//global $res;
			$cnx = conectar_mysql("salud");
			//$cons = "SELECT Salud.Hospitalizacion.*, Salud.EPS.Nit FROM Salud.hospitalizacion INNER JOIN Salud.EPS ON UPPER(Salud.Hospitalizacion.Entidad) = UPPER(Salud.Eps.Nombre)  ORDER BY FechaIng ASC, HorasIng ASC, MinutosIng ASC, FechaEgr ASC, HorasEgr ASC, MinutosEgr ASC LIMIT $limiteinicial, $limitefinal ";
			$cons = "SELECT *  FROM Salud.hospitalizacion   ORDER BY FechaIng ASC, HorasIng ASC, MinutosIng ASC, FechaEgr ASC, HorasEgr ASC, MinutosEgr ASC LIMIT $limiteinicial, $limitefinal ";			
			$res =  mysql_query($cons);
			return $res; 
		
		}
		
		
		function seleccionarPabellon( $cnxmysql, $cedula, $idhospitalizacionmysql) {
			// Selecciona los registros MySQL (Origen)
			global $res;
				if ($cnxmysql != 'FALSE'){
					$cnx = conectar_mysql('salud');
				}
			$cons = "SELECT pabellon FROM Salud.pacientesxpabellones WHERE Cedula = '$cedula' AND IdHospitalizacion = '$idhospitalizacionmysql' ";
			$resultado =  @mysql_query($cons);
				if (!$resultado){
					$fp = fopen("ErroresServicios.html", "a+");	
					$errorEjecucion= "<p class='error1'> Error de ejecucion </p>".pg_last_error()."<br>";
					$consulta= "<p class= 'subtitulo1'>Comando SQL </p> <br>".$consUTF8."<br/> <br/> <br/>";  
					fputs($fp, $errorEjecucion);
					fputs($fp, $consulta);
					fclose($fp);
				}			
			$fila =  @mysql_fetch_array($resultado);
			$pabellon= $fila["pabellon"];
			$pabellon = strtoupper($pabellon);
			$pabellon = trim($pabellon);
			$pabellon = normalizarPabellones($pabellon);
				
				
			return $pabellon; 
		
		}
		
		function consultarNitEntidadMySQL($cnxmysql, $nombreentidad){
			$nombreentidad = strtoupper($nombreentidad);
			$nombreentidad = trim($nombreentidad);
				if ($cnxmysql != 'FALSE'){
					$cnx = conectar_mysql('salud');
				}
			
			$cons = "SELECT nit FROM Salud.EPS WHERE TRIM(UPPER(Nombre)) = '$nombreentidad'";
			$res =  @mysql_query($cons);
				if (!$res){
					$fp = fopen("ErroresServicios.html", "a+");	
					$errorEjecucion= "<p class='error1'> Error de ejecucion </p>".pg_last_error()."<br>";
					$consulta= "<p class= 'subtitulo1'>Comando SQL </p> <br>".$consUTF8."<br/> <br/> <br/>";  
					fputs($fp, $errorEjecucion);
					fputs($fp, $consulta);
					fclose($fp);
				}
			$fila =  @mysql_fetch_array($res);
			$nit= $fila["nit"];
			
			if (empty($nit)){
				$nit = 'NULL';
			}
			
			return $nit; 			
		}
		
		
		function selecionarAmbito($pabellon) {
		
			$cnx= conectar_postgres();
			$cons = "SELECT ambito FROM Salud.Pabellones WHERE pabellon = '$pabellon'";	
			$res = @pg_query($cnx, $cons);
			
			
				if (!$res) {
				
					echo "<p class='error1'> Error de ejecucion </p>".pg_last_error()."<br>";
					echo "<p class= 'subtitulo1'>Comando SQL </p> <br>".$cons."<br>"; 				
					echo "<br><br>";			

				}
				if ($res){
					$fila = pg_fetch_array($res);
					$ambito = $fila["ambito"];
					
				}	
			return $ambito;
		}
		
		function cambiarCampoDxServ(){
			$cnx= conectar_postgres();
			$cons = "ALTER TABLE salud.servicios   ALTER COLUMN dxserv TYPE character varying(25)";	
			$res = @pg_query($cnx, $cons);
				if (!$res) {
				
					echo "<p class='error1'> Error de ejecucion </p>".pg_last_error()."<br>";
					echo "<p class= 'subtitulo1'>Comando SQL </p> <br>".$cons."<br>"; 				
					echo "<br><br>";			

				}
		}	

		
		
		
		function crearServiciosMigracion() {
		// Esta funcion crea una tabla con estructura similar a la tabla Postgresql con la diferencia que carece de llave primaria y omite la restriccion NOT NULL,          ademas  crea un nuevo campo llamado error en el cual se almacena la descripcion del error generado en la consulta inicial.
			$cnx= conectar_postgres();
			$cons = "CREATE TABLE IF NOT EXISTS salud.serviciosMigracion(  cedula character varying(15),  numservicio integer ,  tiposervicio character varying(50),  fechaing timestamp without time zone,  fechaegr timestamp without time zone,  tipousu character varying(50),  nivelusu character varying(50),   autorizac1 character varying(50),  autorizac2 character varying(50),  autorizac3 character varying(50),  estado character varying(2),
  nocarnet character varying(50),  compania character varying(60) ,  medicotte character varying(200),  ordensalida timestamp without time zone,  usuordensalida character varying(100),  notasegreso text,  usuarioingreso character varying(100),  ingreso integer DEFAULT 0,  notaingreso text,  fecharegingreso timestamp without time zone,  viaingreso character varying(2),  causaexterna character varying(2),  estadosalida character varying(1),  dxserv character varying(20),
  usuegreso character varying(100),  tipousunarino character varying(150),  clinica character varying(150),  usucrea character varying(100),  fechacrea timestamp without time zone,  usucreaserv character varying(100),  fecreaserv time without time zone,  pagina character varying(300),  pagmodif character varying(300),  fecmodserv time without time zone,  usumodserv character varying(100),  diasestancia integer, idhospitalizacionmysql varchar, egreso integer,   error text )WITH (  OIDS=FALSE)";	
			$res = @pg_query($cnx, $cons);
				if (!$res) {
				
					echo "<p class='error1'> Error de ejecucion </p>".pg_last_error()."<br>";
					echo "<p class= 'subtitulo1'>Comando SQL </p> <br>".$cons."<br>"; 				
					echo "<br><br>";			
					//echo "<br><br>";
				}
			
		}
		
		
		
		function eliminarServiciosMigracion() {
		// Esta funcion crea una tabla con estructura similar a la tabla Postgresql con la diferencia que carece de llave primaria y omite la restriccion NOT NULL,          ademas  crea un nuevo campo llamado error en el cual se almacena la descripcion del error generado en la consulta inicial.
			$cnx= conectar_postgres();
			$cons = "DELETE FROM  salud.serviciosMigracion";	
			$res = @pg_query($cnx, $cons);
				if (!$res) {
				
					echo "<p class='error1'> Error de ejecucion </p>".pg_last_error()."<br>";
					echo "<p class= 'subtitulo1'>Comando SQL </p> <br>".$cons."<br>"; 				
					echo "<br><br>";			
					//echo "<br><br>";
				}
			
		}
		
		
		function convertirHora($hora){
		
			if (strlen($hora) == 1){
				if($hora == 0){
					$hora = 12;
				}
				elseif($hora != 0){
					$hora = "0".$hora;
				}
				
			}
			
			if ($hora > 12){
				$hora = $hora -12;
			}
			     
			
			return $hora;
		}
		
		
		function insertarServiciosMigracion($cedula, $numservicio, $tiposervicio, $fechaing, $fechaegr, $tipousu, $nivelusu, $autorizac1,  $estado, $nocarnet, $compania, $medicotte, $ordensalida, $dxserv, $diasestancia, $idhospitalizacionmysql, $egreso,   $error) {
		//Realiza la insercion en Postgresql con base en los parametros
			
			$cnx = 	conectar_postgres();
			$cons = "INSERT INTO Salud.ServiciosMigracion (cedula, numservicio, tiposervicio, fechaing, fechaegr, tipousu, nivelusu, autorizac1,  estado, nocarnet, compania, medicotte, ordensalida, dxserv, diasestancia, idhospitalizacionmysql, egreso, error) VALUES ('$cedula', $numservicio, '$tiposervicio', '$fechaing', '$fechaegr', '$tipousu', '$nivelusu','$autorizac1',  '$estado', '$nocarnet', '$compania', '$medicotte', '$ordensalida', '$dxserv', $diasestancia,'$idhospitalizacionmysql', '$egreso',   '$error')"	;
					 
			$cons = str_replace( "'NULL'","NULL",$cons  );	
			$res = @pg_query($cnx, $cons);
				if (!$res) {
					$consUTF8 = utf8_encode($cons);
					$resUTF8 = @pg_query($cnx, $consUTF8);					
						if (!$resUTF8) {
							$fp = fopen("ErroresServicios.html", "a+");	
							$errorEjecucion= "<p class='error1'> Error de ejecucion </p>".pg_last_error()."<br>";
							$consulta= "<p class= 'subtitulo1'>Comando SQL </p> <br>".$consUTF8."<br/> <br/> <br/>";  
							fputs($fp, $errorEjecucion);
							fputs($fp, $consulta);
							fclose($fp);
							
							
						}
				
				}

				
		}
		
		
		function crearArchivoErrores() {
		// Crea un archivo HTML donde se documentaran los registros que no se insertaron en la tabla de migraciones
			$fp = fopen("ErroresServicios.html", "w+");
			$encabezado = "<html> <head> <title> Reporte errores Salud.Servicios </title> 
			<link rel='stylesheet' type='text/css' href='../../General/estilos/estilos.css'> </head>";
			fputs($fp, $encabezado);
			fclose($fp);
		}
		
		
		
		function eliminarCampoIdHospitalizacion() {
			$cnx = 	conectar_postgres();
			$cons = "ALTER TABLE Salud.Servicios DROP COLUMN IF EXISTS IdHospitalizacionMysql";
			$res= @pg_query($cnx, $cons);
					if (!$res) {
						echo "<p class='error1'> Error de ejecucion </p>".pg_last_error()."<br>";
						echo "<p class= 'subtitulo1'>Comando SQL </p> <br>".$cons."<br/> <br/> <br/>";
					}
			
			}
		
		function agregarCampoIdHospitalizacion() {
			$cnx = 	conectar_postgres();
			$cons = "ALTER TABLE Salud.Servicios ADD COLUMN  IdHospitalizacionMysql varchar";
			$res= @pg_query($cnx, $cons);
					if (!$res) {
						echo "<p class='error1'> Error de ejecucion </p>".pg_last_error()."<br>";
						echo "<p class= 'subtitulo1'>Comando SQL </p> <br>".$cons."<br/> <br/> <br/>";
					}
			
		}
		

		
		function insertarServicios($cedula, $numservicio, $tiposervicio, $fechaing, $fechaegr, $tipousu, $nivelusu,$autorizac1,  $estado, $nocarnet, $compania, $medicotte, $ordensalida, $dxserv, $diasestancia, $idhospitalizacionmysql, $egreso) {
			//Realiza la insercion en Postgresql con base en los parametros
			
			$cnx = 	conectar_postgres();
			$cons = "INSERT INTO Salud.Servicios (cedula, numservicio, tiposervicio, fechaing, fechaegr, tipousu, nivelusu, autorizac1,  estado, nocarnet, compania, medicotte, ordensalida, dxserv, diasestancia, idhospitalizacionmysql, egreso) VALUES ('$cedula', $numservicio, '$tiposervicio', '$fechaing', '$fechaegr', '$tipousu', '$nivelusu', '$autorizac1', '$estado', '$nocarnet', '$compania', '$medicotte', '$ordensalida', '$dxserv', $diasestancia, '$idhospitalizacionmysql', $egreso)"	;
					 
			$cons = str_replace( "'NULL'","NULL",$cons  );	
			$res = @pg_query($cnx, $cons);
				if (!$res) {
					$consUTF8 = utf8_encode($cons);
					$resUTF8 = @pg_query($cnx, $consUTF8);					
						if (!$resUTF8) {
							$error = pg_last_error();
							insertarServiciosMigracion($cedula, $numservicio, $tiposervicio, $fechaing, $fechaegr, $tipousu, $nivelusu, $autorizac1,  $estado, $nocarnet, $compania, $medicotte, $ordensalida, $dxserv, $diasestancia, $idhospitalizacionmysql,$egreso,  $error);
							
						}				
				}
		}
		
		
		function  llenarMatrizServicios($limiteinicial, $limitefinal){
		// Llena una matriz con el resultado de la consulta MySQL
			
			unset($matriz); 
			global  $res, $matriz;	
			$res = llamarRegistrosMySQLServicios($limiteinicial, $limitefinal);
			
			$posicion=0;
				
				while ($fila = mysql_fetch_array($res))
				{	
					
					$matriz["cedula"][$posicion] = $fila["Cedula"];
					$matriz["fechaing"][$posicion] = $fila["FechaIng"];
					$matriz["horasing"][$posicion] = $fila["HorasIng"];
					$matriz["minutosing"][$posicion] = $fila["MinutosIng"];					
					$matriz["fechaegr"][$posicion] = $fila["FechaEgr"];
					$matriz["horasegr"][$posicion] = $fila["HorasEgr"];
					$matriz["minutosegr"][$posicion] = $fila["MinutosEgr"];	
					$matriz["tipousu"][$posicion] = $fila["TipoUsu"];
					$matriz["nivelusu"][$posicion] = $fila["NivelUsu"];
					$matriz["estado"][$posicion] = $fila["Estado"];
					$matriz["nocarnet"][$posicion] = $fila["NoCarnet"];
					$matriz["medicotte"][$posicion] = $fila["MedicoTratante"];
					$matriz["ordensalida"][$posicion] = $fila["FechaOrdenEgreso"];
					$matriz["usuordensalida"][$posicion] = $fila["MedicoEgr"];
					$matriz["dxserv"][$posicion] = $fila["CodDxIngreso"];
					$matriz["idhospitalizacionmysql"][$posicion] = $fila["IdHospitalizacion"];
					$matriz["nombreentidad"][$posicion] = $fila["Entidad"];
					$matriz["autorizac1"][$posicion] = $fila["Autorizac"];
					
					
															
					$posicion++;				
				}
							
				
			}
			

			
			function recorrerMatrizServicios($inicial)  {
			// Recorre la matriz con los valores MySQL  inserta los valores en Postgresql
			
				global $res,$matriz;
					
					
					$cnx = conectar_mysql('salud');
					for($pos=0;$pos <= mysql_num_rows($res); $pos++)  {
					
					$cedula= $matriz["cedula"][$pos] ;
					$idhospitalizacionmysql= $matriz["idhospitalizacionmysql"][$pos];
					$numservicio = $inicial + $pos;
					$dateing = $matriz["fechaing"][$pos] ;
					$horasing = $matriz["horasing"][$pos] ;
					$horasing = convertirHora($horasing);
					$minutosing =  $matriz["minutosing"][$pos] ;
					$dateing =substr($dateing, 0, 10);
						if($dateing == "0000-00-00") {
							$fechaing= "NULL";
						}
						elseif ($dateing != "0000-00-00"){
							$fechaing = $dateing." ".$horasing.":".$minutosing;
						}
					
					$dateegr = $matriz["fechaegr"][$pos] ;
					$horasegr = $matriz["horasegr"][$pos] ;
					$horasegr = convertirHora($horasegr);
					$minutosegr =  $matriz["minutosegr"][$pos] ;
					$dateegr =substr($dateegr, 0, 10);
						if($dateegr == "0000-00-00") {
							$fechaegr= "NULL";
						}	
						elseif ($dateegr != "0000-00-00"){
							$fechaegr = $dateegr." ".$horasegr.":".$minutosegr;
						}
					
					$pabellon = seleccionarPabellon('FALSE', $cedula, $idhospitalizacionmysql);
					
					$tiposervicio = selecionarAmbito($pabellon) ;

					$tipousu= $matriz["tipousu"][$pos] ;
					$tipousu = eliminarCaracteresEspeciales($tipousu);
					
					$nivelusu= $matriz["nivelusu"][$pos] ;
					$nivelusu = trim($nivelusu);
						if ($nivelusu == ""){
							$nivelusu = "NA";
						}
					
					$autorizac1= $matriz["autorizac1"][$pos] ;
					$autorizac1 = eliminarCaracteresEspeciales($autorizac1);
					
					$estado= $matriz["estado"][$pos] ;
					$estado = strtoupper($estado);
					
						if ($estado == "A") {
							$estado = "AC";
							$egreso = 0;
						}
						elseif ($estado != "A") {
							$estado = "AN";
							$egreso = 1; 
						}
					
					$nocarnet= $matriz["nocarnet"][$pos] ;	
					
					$medicotte= $matriz["medicotte"][$pos] ;
					$medicotte = eliminarCaracteresEspeciales($medicotte);
					
					$ordensalida = $matriz["ordensalida"][$pos] ;
						if($ordensalida == "0000-00-00 00:00:00") {
							$ordensalida= "NULL";
						}
						

					$usuordensalida= $matriz["usuordensalida"][$pos] ;
					$usuordensalida = eliminarCaracteresEspeciales($usuordensalida);
					
					$dxserv= $matriz["dxserv"][$pos] ;
					$dxserv = strtoupper($dxserv);
					
					$compania= $_SESSION["compania"];
					
						if ($fechaing != 'NULL' and  $fechaegr != 'NULL'){
							$diasestancia = $fechaegr - $fechaing;
							$diasestancia = round($diasestancia);
						}
						elseif ($fechaing == 'NULL' or  $fechaegr == 'NULL'){
							$diasestancia = 'NULL';
						}
						
						if (trim($diasestancia) == ""){
							$diasestancia= 'NULL';
						}
					
					// Informacion para la tabla Salud.PagadorxServicios					
					$nombreentidad = $matriz["nombreentidad"][$pos];
					$entidad = consultarNitEntidadMySQL('FALSE', $nombreentidad) ;					
					$nombreentidad = normalizarEntidades($nombreentidad);	
					$nombreentidad = eliminarCaracteresEspeciales($nombreentidad);
										
					//$entidad = $matriz["entidad"][$pos];
					$nombreentidad = agregarPuntoFinal($nombreentidad);
					$vectorcontrato =  consultarContrato($entidad, $nombreentidad);
					$contrato =  $vectorcontrato["contrato"];
					$nocontrato = $vectorcontrato["nocontrato"];
					$fechaini = $fechaing;
					$fechafin = $fechaegr;
					$usuariocre = 'NULL';
					$fechacre = 'NULL';
					$tipo = 1; 
					
					
					
					insertarPagadorxServicios($numservicio, $compania, $entidad, $contrato, $nocontrato, $fechaini, $fechafin, $usuariocre, $fechacre, $tipo);
					
					insertarServicios($cedula, $numservicio, $tiposervicio, $fechaing, $fechaegr, $tipousu, $nivelusu,$autorizac1,  $estado, $nocarnet, $compania, $medicotte, $ordensalida, $dxserv, $diasestancia, $idhospitalizacionmysql, $egreso);
					
									
					}
			
			}
			
			function eliminarServicios() {
				$cnx= conectar_postgres();
				$cons= "DELETE FROM Salud.servicios";
				$res = @pg_query($cnx, $cons);
				if (!$res) {
							echo "<p class='error1'> Error de ejecucion </p>".pg_last_error()."<br>";
							echo "<p class= 'subtitulo1'>Comando SQL </p> <br>".$cons."<br/>";
							
						}
					
			}
			
			
			
			
		
		
		function migrarServicios() {
			
			
			
			
			crearServiciosMigracion();
			crearPagadorxServiciosMigracion();
			eliminarServiciosMigracion();			
			crearArchivoErrores();
			crearArchivoPagadorxServicios();
			cambiarCampoDxServ();
			
			//eliminarCampoIdHospitalizacion();
			//agregarCampoIdHospitalizacion();
			
			eliminarPagadorxServicios();
			eliminarServicios();
			
			/* Tabla Central.Terceros (Tipo Asegurador) */
			normalizarCodificacionEPS(utf8_encode("Á"),'&Aacute;');			
			normalizarCodificacionEPS(utf8_encode("É"),'&Eacute;');
			normalizarCodificacionEPS(utf8_encode("Í"),'&Iacute;');
			normalizarCodificacionEPS(utf8_encode("Ó"),'&Oacute;');
			normalizarCodificacionEPS(utf8_encode("Ú"),'&Uacute;');
			normalizarCodificacionEPS(utf8_encode("Ñ"),'&Ntilde;');
			
			$total = contarRegistrosMySQL();
			
			$fragmento = 8192;	
			
				if ($total >= $fragmento ) {

				
					$numCiclos = $total / $fragmento;
					$numCiclos = ceil($numCiclos);
										
					$limiteIni = 1;
					
						for ($i = 1; $i <= $numCiclos; $i++){
						
							llenarMatrizServicios($limiteIni,$fragmento);
							recorrerMatrizServicios($limiteIni);
									
							$limiteIni = $limiteIni + $fragmento;							
							
					   } 
				} 
				
			/*$numRegistros = contarRegistrosMySQL();
			$ciclo = $numRegistros/2 ;
			
			$ciclo = ceil($ciclo);		
			
			llenarMatrizServicios(1, $ciclo);
			recorrerMatrizServicios(1);
			$posicionInicial = $ciclo +1;
			llenarMatrizServicios($posicionInicial, $ciclo);
			recorrerMatrizServicios($posicionInicial); */
			
			//Tabla Central.Terceros (Tipo Asegurador)
			normalizarCodificacionEPS('&Aacute;', utf8_encode("Á"));			
			normalizarCodificacionEPS('&Eacute;', utf8_encode("É"));
			normalizarCodificacionEPS('&Iacute;', utf8_encode("Í"));
			normalizarCodificacionEPS('&Oacute;', utf8_encode("Ó"));
			normalizarCodificacionEPS('&Uacute;',utf8_encode("Ú"));
			normalizarCodificacionEPS('&Ntilde;',utf8_encode("Ñ"));
			
			//Tabla Salud.Servicios
			normalizarCodificacionServicios('&Aacute;', utf8_encode("Á"));			
			normalizarCodificacionServicios('&Eacute;', utf8_encode("É"));
			normalizarCodificacionServicios('&Iacute;', utf8_encode("Í"));
			normalizarCodificacionServicios('&Oacute;', utf8_encode("Ó"));
			normalizarCodificacionServicios('&Uacute;',utf8_encode("Ú"));
			normalizarCodificacionServicios('&Ntilde;',utf8_encode("Ñ"));
			
			
			echo "<div align='center'> <p class='mensajeFinalizacion'>Ha terminado la migracion de la tabla Salud.Servicios</p> </div>";
			
	
		}
		
		
		if($_GET['tabla']="Salud_Servicios") {
		
			echo "<fieldset>";			
			echo "<legend> Migracion tabla MySQL </legend>";
			echo "<br>";
			echo "<span align='left'> <a href='../../index.php?migracion=MIG015' class = 'link1'> Panel de Administracion </a> </span>";
			echo "<br>";
			
			migrarServicios();
			
			$totalMySQL = contarRegistrosMySQL();
			$totalPostgresql =  contarRegistrosPostgresql();
			$totalPostgresqlErrores =  contarRegistrosPostgresqlErrores();
			
			echo "<p class= 'subtitulo1'> Total registros MySQL:</p>";
			echo  $totalMySQL."<br/>";
			echo "<p class= 'subtitulo1'> Total registros Postgresql migrados:</p>";
			echo  $totalPostgresql."<br/>";
			echo "<p class= 'error1'> Total errores genereados(Tabla Salud.ServiciosMigracion):</p>";
			echo  $totalPostgresqlErrores."<br/>";
			
			echo "<br/> <br/>";
			echo "<span align='right'> <a href='ErroresServicios.html' target='_blank' class = 'link1'> Ver Reporte de Errores</a> </span>";			
			echo "<br/> <br/>";
			
			echo "</fieldset>";
			
		}
			
		
		
		
	
	
	
	?>
