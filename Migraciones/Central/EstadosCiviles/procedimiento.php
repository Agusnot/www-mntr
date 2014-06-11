	<html>	
		<head>
			<title> Migracion Central.EstadosCiviles </title>
			<link rel="stylesheet" type="text/css" href="../General/estilos/estilos.css">
		</head>
	
	
	<?php
		
		
		
		
		
		/* Inicia defincion de funciones */
		
		
		function funcion0073() {
			// Elimina la tabla de migracion
			$cnx= conectar_postgres();
			$cons = "DROP TABLE  IF EXISTS Central.estadoscivilesMigracion";
			$res =  pg_query($cons);
			
		}
		
		function funcion0074() {
		// Esta funcion crea una tabla con estructura similar a la tabla Postgresql con la diferencia que carece de llave primaria y omite la restriccion NOT NULL,          ademas  crea un nuevo campo llamado error en el cual se almacena la descripcion del error generado en la consulta inicial.
			$cnx= conectar_postgres();
			$cons = "CREATE TABLE central.estadoscivilesMigracion(  estadocivil character varying(50) ,  codigo character varying(50) , error text)WITH (  OIDS=FALSE)";	
			$res = @pg_query($cnx, $cons);
				if (!$res) {
				
					echo "<p class='error1'> Error de ejecucion </p>".pg_last_error()."<br>";
					echo "<p class= 'subtitulo1'>Comando SQL </p> <br>".$cons."<br>"; 				
					echo "<br><br>";			
					//echo "<br><br>";
				}
			
		}
		
		function funcion0075() {
		// Crea un archivo HTML donde se documentaran los registros que no se insertaron en la tabla de migraciones
			$fp = fopen("Errores/ReporteEstadosCiviles.html", "w+");
			$encabezado = "<html> <head> <title> Reporte errores Central.EstadosCiviles </title> 
			<link rel='stylesheet' type='text/css' href='../../General/estilos/estilos.css'> </head>";
			fputs($fp, $encabezado);
			fclose($fp);
		}	
		
		
		function funcion0076( $estadocivil, $codigo, $error){
		// Inserta en la tabla de migraciones para documentar los errores
			
			$cnx = 	conectar_postgres();
			$cons = "INSERT INTO Central.estadoscivilesMigracion (estadocivil,  codigo, error ) VALUES ('".$estadocivil."','".$codigo."','".$error."')"	;
			
			$cons = str_replace( "'NULL'","NULL",$cons  );	
			$res = @pg_query($cnx, $cons);
				if(!$res) {
					$consUTF8 = utf8_encode($cons);
					$resUTF8 = @pg_query($cnx, $consUTF8);
						if (!$resUTF8) {
							
							$fp = fopen("Errores/ReporteEstadosCiviles.html", "a+");	
							$errorEjecucion= "<p class='error1'> Error de ejecucion </p>".pg_last_error()."<br>";
							$consulta= "<p class= 'subtitulo1'>Comando SQL </p> <br>".$consUTF8."<br/> <br/> <br/>";  
							fputs($fp, $errorEjecucion);
							fputs($fp, $consulta);
							fclose($fp);
							
						}
				}	
		}	


		
		
		
		function  funcion0077($cadenaBusqueda,$cadenaReemplazo)  {
		// Busca y reemplaza ocurrencias en una tabla
			$cnx= conectar_postgres();
			$cons = "UPDATE Central.EstadosCiviles SET estadocivil = replace( estadocivil,'". $cadenaBusqueda."'".",'". $cadenaReemplazo."'),codigo = replace( codigo,'". $cadenaBusqueda."'".",'". $cadenaReemplazo."')";
			
			$res = pg_query($cnx , $cons);
				if (!$res)  {
					echo "<p class='error1'> Error en la normalizacion de la codificacion </p>".pg_last_error()."<br>";
				}

		}
		
				
	
		function funcion0078() {
			// Selecciona los registros MySQL (Origen)
			global $res;
			$cnx = conectar_mysql("Salud");
			$cons = "SELECT *  FROM Salud.EstadosCiviles";
			$res =  mysql_query($cons);
			return $res; 
		
		}
		
		

		
		function funcion0079($estadocivil, $codigo) {
		//Realiza la insercion en Postgresql con base en los parametros
			
			$cnx = 	conectar_postgres();
			$cons = "INSERT INTO Central.EstadosCiviles (estadocivil, codigo ) VALUES ('".$estadocivil."','".$codigo."')"	;
					 
			$cons = str_replace( "'NULL'","NULL",$cons  );	
			$res = @pg_query($cnx, $cons);
				if (!$res) {
					$consUTF8 = utf8_encode($cons);
					$resUTF8 = @pg_query($cnx, $consUTF8);					
						if (!$resUTF8) {
							$error = pg_last_error();
							funcion0076( $estadocivil, $codigo, $error);
							
						}
				
				}

				
		}
		
		
		function  funcion0080(){
		// Llena una matriz con el resultado de la consulta MySQL
			
			unset($matriz); 
			global  $matriz;	
			$res = funcion0078();
			$posicion=0;
				
				while ($fila = mysql_fetch_array($res))
				{	
					
					$matriz["estadocivil"][$posicion] = $fila["EstadoCivil"];
					$matriz["codigo"][$posicion] = $fila["Codigo"];
					$posicion++;				
				}
							
				
			}
			

			
			function funcion0081()  {
			// Recorre la matriz con los valores MySQL  inserta los valores en Postgresql
			
				global $res,$matriz;
					for($pos=0;$pos < mysql_num_rows($res); $pos++)  {

					$estadocivil= $matriz["estadocivil"][$pos] ;
					$estadocivil = eliminarCaracteresEspeciales($estadocivil);
					$codigo= $matriz["codigo"][$pos] ;
					$codigo = eliminarCaracteresEspeciales($codigo);
					
					
									
					funcion0079($estadocivil, $codigo );
									
					}
			
			}
			
			function funcion0082() {
				$cnx= conectar_postgres();
				$cons= "DELETE FROM Central.EstadosCiviles";
				$res = @pg_query($cnx, $cons);
				if (!$res) {
							echo "<p class='error1'> Error de ejecucion </p>".pg_last_error()."<br>";
							echo "<p class= 'subtitulo1'>Comando SQL </p> <br>".$cons."<br/>";
							
						}
					
			}
			
			
			
			
		
		
		function migrarEstadosCiviles($paso) {
		
			funcion0072();
			funcion0082();
			funcion0073();
			funcion0074();
			funcion0075();
			funcion0078();
			funcion0080();
			funcion0081();
			funcion0077('&Aacute;', utf8_encode("Á"));			
			funcion0077('&Eacute;', utf8_encode("É"));
			funcion0077('&Iacute;', utf8_encode("Í"));
			funcion0077('&Oacute;', utf8_encode("Ó"));
			funcion0077('&Uacute;',utf8_encode("Ú"));
			funcion0077('&Ntilde;',utf8_encode("Ñ"));
			echo "<p class='mensajeEjecucion'> <span class = 'subtitulo1'>Paso $paso: </span> Se ha migrado la tabla Central.EstadosCiviles </p> ";
	
		}
		
		
		
		
		
	
	
	
	?>
