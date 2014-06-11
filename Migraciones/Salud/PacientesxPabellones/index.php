	<html>	
		<head>
			<title> Migracion Salud.PacientesxPabellones </title>
			<link rel="stylesheet" type="text/css" href="../../General/estilos/estilos.css">
		</head>
	
	
	<?php
		session_start();
		include_once('../../General/funciones/funciones.php');
		include_once('../../Conexiones/conexion.php');
		
		
		
		
		/* Inicia definicion de funciones */
		
		
		function contarRegistrosPostgresql() {
			$cnx= conectar_postgres();
			$cons = "SELECT COUNT(*) AS conteo FROM Salud.PacientesxPabellones";
			$res =  pg_query($cnx, $cons);
			$fila = pg_fetch_array($res);
			$res = $fila['conteo'];
			return $res; 	
		
		}
		
		function contarRegistrosPostgresqlErrores() {
			$cnx= conectar_postgres();
			$cons = "SELECT COUNT(*) AS conteo FROM Salud.PacientesxPabellonesMigracion";
			$res =  pg_query($cnx, $cons);
			$fila = pg_fetch_array($res);
			$res = $fila['conteo'];
			return $res; 	
		
		}
		
		
		
		function contarRegistrosMySQL() {
			// Selecciona los registros MySQL (Origen)
			global $res;
			$cnx = conectar_mysql('salud');
			$cons = "SELECT COUNT(*) AS conteo FROM Salud.PacientesxPabellones";
			
			$res =  mysql_query($cons);
			$fila = mysql_fetch_array($res);
			$numregistros = $fila["conteo"];
			
			return $numregistros; 
		
		}	
		
			
		function  normalizarCodificacionPacientesxPabellones($cadenaBusqueda,$cadenaReemplazo)  {
		// Busca y reemplaza ocurrencias en una tabla
			$cnx= conectar_postgres();
			$cons = "UPDATE Salud.PacientesxPabellones SET usuario = replace( usuario,'$cadenaBusqueda','$cadenaReemplazo'),  pabellon = replace( pabellon,'$cadenaBusqueda','$cadenaReemplazo') ,  estado = replace( estado,'$cadenaBusqueda','$cadenaReemplazo'),  compania = replace( compania,'$cadenaBusqueda','$cadenaReemplazo'),  lugtraslado = replace( lugtraslado,'$cadenaBusqueda','$cadenaReemplazo'),  ambito = replace( ambito,'$cadenaBusqueda','$cadenaReemplazo') ";
			
			$res = pg_query($cnx , $cons);
				if (!$res)  {
					echo "<p class='error1'> Error en la normalizacion de la codificacion </p>".pg_last_error()."<br>";
					echo"<p class= 'subtitulo1'>Comando SQL </p> <br>".$cons."<br/> <br/> <br/>";  
				}

		}
		
				
				
	
		function llamarRegistrosMySQLPacientesxPabellones() {
			// Selecciona los registros MySQL (Origen)
			global $res;
			$cnx = conectar_mysql("Salud");
			$cons = "SELECT * FROM Salud.PacientesxPabellones ORDER BY FechaI ASC ";
			$res =  mysql_query($cons);
			return $res; 
		
		}
		
		
		function crearArchivoErrores() {
		// Crea un archivo HTML donde se documentaran los registros que no se insertaron en la tabla de migraciones
			$fp = fopen("ErroresPacientesxPabellones.html", "w+");
			$encabezado = "<html> <head> <title> Reporte errores Salud.PacientesxPabellones </title> 
			<link rel='stylesheet' type='text/css' href='../../General/estilos/estilos.css'> </head>";
			fputs($fp, $encabezado);
			fclose($fp);
		}
		

		
	
		
		function crearPacientesxPabellonesMigracion() {
		// Esta funcion crea una tabla con estructura similar a la tabla Postgresql con la diferencia que carece de llave primaria y omite la restriccion NOT NULL,          ademas  crea un nuevo campo llamado error en el cual se almacena la descripcion del error generado en la consulta inicial.
			$cnx= conectar_postgres();
			$cons = "CREATE TABLE IF NOT EXISTS salud.pacientesxpabellonesMigracion(  usuario character varying(50),  cedula character varying(15) ,  pabellon character varying(80) ,  estado character varying(2),  fechai date ,  horai time without time zone ,  fechae date,  horae time without time zone,  lugtraslado character varying(100),  numservicio integer ,  compania character varying(60) ,  ambito character varying(80) ,  idcama integer,  diasestancia integer , error text )WITH (  OIDS=FALSE)";	
			$res = @pg_query($cnx, $cons);
				if (!$res) {
				
					echo "<p class='error1'> Error de ejecucion </p>".pg_last_error()."<br>";
					echo "<p class= 'subtitulo1'>Comando SQL </p> <br>".$cons."<br>"; 				
					echo "<br><br>";			
					//echo "<br><br>";
				}
			
		}
		
		
		
		
		function seleccionarServicio($cedula, $idhospitalizacionmysql, $fechaing, $fechaegr) {
		// Esta funcion crea una tabla con estructura similar a la tabla Postgresql con la diferencia que carece de llave primaria y omite la restriccion NOT NULL,          ademas  crea un nuevo campo llamado error en el cual se almacena la descripcion del error generado en la consulta inicial.
			$cnx= conectar_postgres();
			$cons = "SELECT numservicio FROM Salud.Servicios WHERE cedula = '$cedula' AND idhospitalizacionmysql = '$idhospitalizacionmysql'  ";	
			$res = @pg_query($cnx, $cons);
				if (!$res) {
				
					/*echo "<p class='error1'> Error de ejecucion </p>".pg_last_error()."<br>";
					echo "<p class= 'subtitulo1'>Comando SQL </p> <br>".$cons."<br>"; 				
					echo "<br><br>";	*/
					$servicio= 0;							
					
				}
				
				if ($res) {
					
					if (@pg_num_rows($res) > 1){
						$fechaing = $fechaing." 23:59:59";
						$fechaegr = $fechaegr." 00:00:00";
						$cons = "SELECT numservicio FROM Salud.Servicios WHERE cedula = '$cedula' AND idhospitalizacionmysql = '$idhospitalizacionmysql' AND fechaing <= '$fechaing' AND fechaegr >= '$fechaegr'  ORDER BY numservicio DESC";	
						$res = @pg_query($cnx, $cons);
							if (@pg_num_rows($res) == 1){
								$fila = pg_fetch_array($res);				
								$servicio = $fila["numservicio"];
							}
							else{
								$fila = pg_fetch_array($res);				
								$servicio = $fila["numservicio"];
									if (empty($servicio)){
										$servicio = 0;
									}
								$fp = fopen("ErroresPacientesxPabellones.html", "a+");	
									$errorEjecucionHTML = "<p class='error1'> Posible de error de ejecucion </p> Ambig&uuml;edad en la definicion del Servicio<br>";
									$comandoHTML = "<p class= 'subtitulo1'> Comando SQL:</p>".$cons."<br/>";
									$cedulaHTML = "<p class= 'subtitulo1'> Cedula:</p>".$cedula."<br/>";
									$idhospitalizacionHTML = "<p class= 'subtitulo1'> Id Hospitalizacion MySQL:</p> ".$idhospitalizacionmysql."<br/> <br/>";
									fputs($fp, $errorEjecucionHTML);
									fputs($fp, $comandoHTML);
									fputs($fp, $cedulaHTML);
									fputs($fp, $idhospitalizacionHTML);
								fclose($fp);								
							}
						
					}
					else {
						$fila = pg_fetch_array($res);				
						$servicio = $fila["numservicio"];
					}
				}
			return $servicio;
		}
		
		
		function seleccionarAmbito($pabellon) {
		// Esta funcion crea una tabla con estructura similar a la tabla Postgresql con la diferencia que carece de llave primaria y omite la restriccion NOT NULL,          ademas  crea un nuevo campo llamado error en el cual se almacena la descripcion del error generado en la consulta inicial.
			$cnx= conectar_postgres();
			$cons = "SELECT ambito FROM Salud.pabellones WHERE pabellon = '$pabellon' ";	
			$res = @pg_query($cnx, $cons);
				if (!$res) {
				
					echo "<p class='error1'> Error de ejecucion </p>".pg_last_error()."<br>";
					echo "<p class= 'subtitulo1'>Comando SQL </p> <br>".$cons."<br>"; 				
					echo "<br><br>";			
					
				}
				if ($res) {
					$fila = pg_fetch_array($res);				
					$ambito = $fila["ambito"];
					return $ambito;
				}
			
		}
		
		
		
		
		
		
		function eliminarPacientesxPabellonesMigracion() {
		// Esta funcion crea una tabla con estructura similar a la tabla Postgresql con la diferencia que carece de llave primaria y omite la restriccion NOT NULL,          ademas  crea un nuevo campo llamado error en el cual se almacena la descripcion del error generado en la consulta inicial.
			$cnx= conectar_postgres();
			$cons = "DELETE FROM  salud.PacientesxPabellonesMigracion";	
			$res = @pg_query($cnx, $cons);
				if (!$res) {
				
					echo "<p class='error1'> Error de ejecucion </p>".pg_last_error()."<br>";
					echo "<p class= 'subtitulo1'>Comando SQL </p> <br>".$cons."<br>"; 				
					echo "<br><br>";			
					//echo "<br><br>";
				}
			
		}
		
		
		
		function insertarPacientesxPabellonesMigracion($usuario, $cedula, $pabellon, $estado, $fechai, $horai, $fechae, $horae, $lugtraslado, $numservicio, $compania, $ambito, $diasestancia,  $error) {
		//Realiza la insercion en Postgresql con base en los parametros
			
			$cnx = 	conectar_postgres();
			$cons = "INSERT INTO Salud.PacientesxPabellonesMigracion (usuario, cedula, pabellon, estado, fechai, horai, fechae, horae, lugtraslado, numservicio, compania, ambito, diasestancia,  error) VALUES ('$usuario', '$cedula', '$pabellon', '$estado', '$fechai', '$horai' , '$fechae' , '$horae' , '$lugtraslado', $numservicio, '$compania', '$ambito' , $diasestancia,  '$error')"	;
					 
			$cons = str_replace( "'NULL'","NULL",$cons  );	
			$res = @pg_query($cnx, $cons);
				if (!$res) {
					$consUTF8 = utf8_encode($cons);
					$resUTF8 = @pg_query($cnx, $consUTF8);					
						if (!$resUTF8) {
							
							$fp = fopen("ErroresPacientesxPabellones.html", "a+");	
							$errorEjecucion= "<p class='error1'> Error de ejecucion </p>".pg_last_error()."<br>";
							$consulta= "<p class= 'subtitulo1'>Comando SQL </p> <br>".$consUTF8."<br/> <br/> <br/>";  
							fputs($fp, $errorEjecucion);
							fputs($fp, $consulta);
							fclose($fp);
							
							
						}
				
				}

				
		}
		
		
		
		

		
		function insertarPacientesxPabellones($usuario, $cedula, $pabellon, $estado, $fechai, $horai, $fechae, $horae, $lugtraslado, $numservicio, $compania, $ambito, $diasestancia) {
		//Realiza la insercion en Postgresql con base en los parametros
			
			$cnx = 	conectar_postgres();
			$cons = "INSERT INTO Salud.PacientesxPabellones (usuario, cedula, pabellon, estado, fechai, horai, fechae, horae, lugtraslado, numservicio, compania, ambito, diasestancia) VALUES ('$usuario', '$cedula', '$pabellon', '$estado', '$fechai', '$horai' , '$fechae' , '$horae' , '$lugtraslado', $numservicio, '$compania', '$ambito' , $diasestancia)"	;
					 
			$cons = str_replace( "'NULL'","NULL",$cons  );	
			$res = @pg_query($cnx, $cons);
				if (!$res) {
					$consUTF8 = utf8_encode($cons);
					$resUTF8 = @pg_query($cnx, $consUTF8);					
						if (!$resUTF8) {
							$error = pg_last_error();
							insertarPacientesxPabellonesMigracion($usuario, $cedula, $pabellon, $estado, $fechai, $horai, $fechae, $horae, $lugtraslado, $numservicio, $compania, $ambito, $diasestancia,  $error);
							
							
						}
				
				}

				
		}
		
		
		function  llenarMatrizPacientesxPabellones(){
		// Llena una matriz con el resultado de la consulta MySQL
			
			unset($matriz); 
			global  $matriz;	
			$res = llamarRegistrosMySQLPacientesxPabellones();
			$posicion=0;
				
				while ($fila = mysql_fetch_array($res))
				{	
					
					$matriz["usuario"][$posicion] = $fila["Usuario"];
					$matriz["cedula"][$posicion] = $fila["Cedula"];
					$matriz["pabellon"][$posicion] = $fila["Pabellon"];
					$matriz["estado"][$posicion] = $fila["Estado"];					
					$matriz["fechai"][$posicion] = $fila["FechaI"];
					$matriz["horai"][$posicion] = $fila["HoraI"];
					$matriz["fechae"][$posicion] = $fila["FechaE"];	
					$matriz["horae"][$posicion] = $fila["HoraE"];
					$matriz["lugtraslado"][$posicion] = $fila["LugTraslado"];
					$matriz["idhospitalizacionmysql"][$posicion] = $fila["IdHospitalizacion"];

					
									
															
					$posicion++;				
				}
							
				
			}
			

			
			function recorrerMatrizPacientesxPabellones()  {
			// Recorre la matriz con los valores MySQL  inserta los valores en Postgresql
			
				global $res,$matriz;
					
					for($pos=0;$pos < mysql_num_rows($res); $pos++)  {

					$idhospitalizacionmysql= $matriz["idhospitalizacionmysql"][$pos] ;	
					$usuario= $matriz["usuario"][$pos] ;
					$usuario = eliminarCaracteresEspeciales($usuario);
					$cedula= $matriz["cedula"][$pos] ;
					$pabellon= $matriz["pabellon"][$pos] ;
					
					
					$pabellon = normalizarPabellones($pabellon);
					
					
					$estado= $matriz["estado"][$pos] ;
					$estado = strtoupper($estado);
						if ($estado == "A"){
							$estado = "AC";
						}
						elseif ($estado != "A"){
							$estado = "AN";
						}
						
					$fechai= $matriz["fechai"][$pos] ;
					
					
					
					
					
						if ($fechai == "0000-00-00 00:00" or $fechai == "0000-00-00"){
							$fechai = 'NULL';
						}
						/*elseif ($fechai != "0000-00-00 00:00" and  $fechai != "0000-00-00" ){
							$anioi = substr($fechai, 0,4);
								if ($anioi=="0000"){
									$anioi = "1900";
								}
							$mesi = substr($fechai, 5,2);
								if ($mesi=="00"){
										$mesi = "01";
								}							
							$diai = substr($fechai, 8,2);
								if ($diai=="00"){
										$diai = "01";
								}
							$fechai = 	$anioi."-".$mesi."-".$diai;
							
						}*/
						
						
					$horai= $matriz["horai"][$pos] ;
					
					
					
					$fechae= $matriz["fechae"][$pos] ;
						if ($fechae == "0000-00-00 00:00" or $fechae == "0000-00-00" ){
							$fechae = 'NULL';
						}
						/*elseif ($fechae != "0000-00-00 00:00" and $fechae != "0000-00-00"){
							$anioe = substr($fechae, 0,4);
								if ($anioe=="0000"){
									$anioe = "1900";
								}
							$mese = substr($fechae, 5,2);
								if ($mese=="00"){
										$mese = "01";
								}							
							$diae = substr($fechae, 8,2);
								if ($diae=="00"){
										$diae = "01";
								}
							$fechae = 	$anioe."-".$mese."-".$diae;
							
						}*/
					$horae= $matriz["horae"][$pos] ;
					
					$lugtraslado= $matriz["lugtraslado"][$pos] ;
					$lugtraslado = eliminarCaracteresEspeciales($lugtraslado);
					
					
					$numservicio = seleccionarServicio($cedula, $idhospitalizacionmysql,$fechai, $fechae );
						if (trim($numservicio) == "" ){
							$numservicio = 0;
						}
						
					$compania = $_SESSION["compania"];
						
					$ambito = seleccionarAmbito($pabellon);
					
						if (trim($fechae)!= "" and trim($fechai)!= ""){
						
							$diasestancia = strtotime($fechae) - strtotime($fechai);
							$diasestancia = round($diasestancia/86400);
							
						} 
						else {	
										
							$diasestancia = 'NULL';
						}	
					
					insertarPacientesxPabellones($usuario, $cedula, $pabellon, $estado, $fechai, $horai, $fechae, $horae, $lugtraslado, $numservicio, $compania, $ambito, $diasestancia);
					
									
					}
			
			}
			
			function eliminarPacientesxPabellones() {
				$cnx= conectar_postgres();
				$cons= "DELETE FROM Salud.PacientesxPabellones";
				$res = @pg_query($cnx, $cons);
				if (!$res) {
							echo "<p class='error1'> Error de ejecucion </p>".pg_last_error()."<br>";
							echo "<p class= 'subtitulo1'>Comando SQL </p> <br>".$cons."<br/>";
							
						}
					
			}
			
			
			
			
			
			
		
		
		function migrarPacientesxPabellones() {
			
			
			crearPacientesxPabellonesMigracion();
			eliminarPacientesxPabellonesMigracion();
			crearArchivoErrores();
			
			eliminarPacientesxPabellones();
			
			llenarMatrizPacientesxPabellones();
			recorrerMatrizPacientesxPabellones();
			
			//Tabla Salud.Servicios
			normalizarCodificacionPacientesxPabellones('&Aacute;', utf8_encode("Á"));			
			normalizarCodificacionPacientesxPabellones('&Eacute;', utf8_encode("É"));
			normalizarCodificacionPacientesxPabellones('&Iacute;', utf8_encode("Í"));
			normalizarCodificacionPacientesxPabellones('&Oacute;', utf8_encode("Ó"));
			normalizarCodificacionPacientesxPabellones('&Uacute;',utf8_encode("Ú"));
			normalizarCodificacionPacientesxPabellones('&Ntilde;',utf8_encode("Ñ"));
			echo "<div align='center'> <p class='mensajeFinalizacion'>Ha terminado la migracion de la tabla Salud.PacientesxPabellones</p> </div>";
			
	
		}
		
		
		if($_GET['tabla']="PacientesxPabellones") {
		
			echo "<fieldset>";			
			echo "<legend> Migracion tabla MySQL </legend>";
			echo "<br>";
			echo "<span align='left'> <a href='../../index.php?migracion=MIG016' class = 'link1'> Panel de Administracion </a> </span>";
			echo "<br>";
			
			migrarPacientesxPabellones();
			
			$totalMySQL = contarRegistrosMySQL();
			$totalPostgresql =  contarRegistrosPostgresql();
			$totalPostgresqlErrores =  contarRegistrosPostgresqlErrores();
			
			echo "<p class= 'subtitulo1'> Total registros MySQL:</p>";
			echo  $totalMySQL."<br/>";
			echo "<p class= 'subtitulo1'> Total registros Postgresql migrados:</p>";
			echo  $totalPostgresql."<br/>";
			echo "<p class= 'error1'> Total errores generados(Tabla Salud.PacientesxPabellonMigracion):</p>";
			echo  $totalPostgresqlErrores."<br/>";
			
			echo "<br/> <br/>";
			
			echo "<br/> <br/>";
			echo "<span align='right'> <a href='ErroresPacientesxPabellones.html' target='_blank' class = 'link1'> Ver Reporte de Errores</a> </span>";			
			echo "<br/> <br/>";
			
			echo "</fieldset>";
			
		}
			
		
		
		
	
	
	
	?>
