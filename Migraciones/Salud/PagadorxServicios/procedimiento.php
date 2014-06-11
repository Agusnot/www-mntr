	<html>	
		<head>
			<title> Migracion Salud.PagadorxServicios </title>
			<link rel="stylesheet" type="text/css" href="../General/estilos/estilos.css">
		</head>
	
	
	<?php
		session_start();
		//include_once('../General/funciones/funciones.php');
		
		
		
		
		/* Inicia defincion de funciones */
		
		function insertarPagadorxServiciosMigracion($numservicio, $compania, $entidad, $contrato, $nocontrato, $fechaini, $fechafin, $usuariocre, $fechacre, $tipo, $error) {
		//Realiza la insercion en Postgresql con base en los parametros
			
			$cnx = 	conectar_postgres();
			
			$cons= "INSERT INTO Salud.PagadorxServiciosMigracion(numservicio, compania, entidad, contrato, nocontrato, fechaini, fechafin, usuariocre, fechacre, tipo , error) VALUES ('$numservicio', '$compania', '$entidad', '$contrato', '$nocontrato', '$fechaini', '$fechafin', '$usuariocre', '$fechacre', '$tipo', '$error')";
			$cons = str_replace( "'NULL'","NULL",$cons  );	
			$res = @pg_query($cnx, $cons);
				if (!$res) {
					$consUTF8 = utf8_encode($cons);
					$resUTF8 = @pg_query($cnx, $consUTF8);					
						if (!$resUTF8) {
							$fp = fopen("../PagadorxServicios/ErroresPagadorxServicios.html", "a+");	
							$errorEjecucion= "<p class='error1'> Error de ejecucion </p>".pg_last_error()."<br>";
							$consulta= "<p class= 'subtitulo1'>Comando SQL </p> <br>".$consUTF8."<br/> <br/> <br/>";  
							fputs($fp, $errorEjecucion);
							fputs($fp, $consulta);
							fclose($fp);
						
						}
				
				}
	
		}
		
		
		function crearArchivoPagadorxServicios() {
		// Crea un archivo HTML donde se documentaran los registros que no se insertaron en la tabla de migraciones
			$fp = fopen("../PagadorxServicios/ErroresPagadorxServicios.html", "w+");
			$encabezado = "<html> <head> <title> Reporte errores Salud.Servicios </title> 
			<link rel='stylesheet' type='text/css' href='../../General/estilos/estilos.css'> </head>";
			fputs($fp, $encabezado);
			fclose($fp);
		}
	
	
	
		
		function insertarPagadorxServicios($numservicio, $compania, $entidad, $contrato, $nocontrato, $fechaini, $fechafin, $usuariocre, $fechacre, $tipo) {
		//Realiza la insercion en Postgresql con base en los parametros
			
			$cnx = 	conectar_postgres();
			
			$cons= "INSERT INTO Salud.PagadorxServicios(numservicio, compania, entidad, contrato, nocontrato, fechaini, fechafin, usuariocre, fechacre, tipo) VALUES ('$numservicio', '$compania', '$entidad', '$contrato', '$nocontrato', '$fechaini', '$fechafin', '$usuariocre', '$fechacre', '$tipo')";
			$cons = str_replace( "'NULL'","NULL",$cons  );	
			$res = @pg_query($cnx, $cons);
				if (!$res) {
					$consUTF8 = utf8_encode($cons);
					$resUTF8 = @pg_query($cnx, $consUTF8);					
						if (!$resUTF8) {
							$error = pg_last_error();
							insertarPagadorxServiciosMigracion($numservicio, $compania, $entidad, $contrato, $nocontrato, $fechaini, $fechafin, $usuariocre, $fechacre, $tipo, $error);
						
						}
				
				}
	
		}
	
			
			
			
			function crearPagadorxServiciosMigracion() {
		// Esta funcion crea una tabla con estructura similar a la tabla Postgresql con la diferencia que carece de llave primaria y omite la restriccion NOT NULL,          ademas  crea un nuevo campo llamado error en el cual se almacena la descripcion del error generado en la consulta inicial.
			$cnx= conectar_postgres();
			$cons = "CREATE TABLE IF NOT EXISTS salud.pagadorxserviciosMigracion(  numservicio integer ,  compania character varying(80) ,  entidad character varying(200) ,  contrato character varying(150) ,  nocontrato character varying(150) ,  fechaini date ,  fechafin date,  usuariocre character varying(100),  fechacre timestamp without time zone,  tipo integer ,  error text)WITH (  OIDS=FALSE);";	
			$res = @pg_query($cnx, $cons);
				if (!$res) {
				
					echo "<p class='error1'> Error de ejecucion </p>".pg_last_error()."<br>";
					echo "<p class= 'subtitulo1'>Comando SQL </p> <br>".$cons."<br>"; 				
					echo "<br><br>";			
					//echo "<br><br>";
				}
			
		}
		
			function eliminarPagadorxServiciosMigracion() {
			// Esta funcion crea una tabla con estructura similar a la tabla Postgresql con la diferencia que carece de llave primaria y omite la restriccion NOT NULL,          ademas  crea un nuevo campo llamado error en el cual se almacena la descripcion del error generado en la consulta inicial.
				$cnx= conectar_postgres();
				$cons = "DELETE FROM  salud.PagadorxServiciosMigracion";	
				$res = @pg_query($cnx, $cons);
					if (!$res) {
					
						echo "<p class='error1'> Error de ejecucion </p>".pg_last_error()."<br>";
						echo "<p class= 'subtitulo1'>Comando SQL </p> <br>".$cons."<br>"; 				
						echo "<br><br>";			
						
					}
				
			}
			
			function eliminarPagadorxServicios() {
			// Esta funcion crea una tabla con estructura similar a la tabla Postgresql con la diferencia que carece de llave primaria y omite la restriccion NOT NULL,          ademas  crea un nuevo campo llamado error en el cual se almacena la descripcion del error generado en la consulta inicial.
				$cnx= conectar_postgres();
				$cons = "DELETE FROM  salud.PagadorxServicios";	
				$res = @pg_query($cnx, $cons);
					if (!$res) {
					
						echo "<p class='error1'> Error de ejecucion </p>".pg_last_error()."<br>";
						echo "<p class= 'subtitulo1'>Comando SQL </p> <br>".$cons."<br>"; 				
						echo "<br><br>";			
						
					}
				
			}
			
			
		
		
		
	
	?>
