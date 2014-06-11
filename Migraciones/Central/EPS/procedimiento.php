	<html>	
		<head>
			<title> Migracion Central.EPS </title>
			<link rel="stylesheet" type="text/css" href="../General/estilos/estilos.css">
		</head>
	
	
	<?php
		
		
		
		
		
		/* Inicia defincion de funciones */
		
		
		function funcion0052() {
			// Elimina la tabla de migracion
			$cnx= conectar_postgres();
			$cons = "DROP TABLE  IF EXISTS Central.epsMigracion";
			$res =  pg_query($cons);
			
		}
		
		function funcion0053() {
		// Esta funcion crea una tabla con estructura similar a la tabla Postgresql con la diferencia que carece de llave primaria y omite la restriccion NOT NULL,          ademas  crea un nuevo campo llamado error en el cual se almacena la descripcion del error generado en la consulta inicial.
			$cnx= conectar_postgres();
			$cons = "CREATE TABLE central.epsMigracion(  codigo character varying(50) ,  nit character varying(50),  eps character varying(255), error text)WITH (  OIDS=FALSE)";	
			$res = @pg_query($cnx, $cons);
				if (!$res) {
				
					echo "<p class='error1'> Error de ejecucion </p>".pg_last_error()."<br>";
					echo "<p class= 'subtitulo1'>Comando SQL </p> <br>".$cons."<br>"; 				
					echo "<br><br>";			
					//echo "<br><br>";
				}
			
		}
		
		function funcion0054() {
		// Crea un archivo HTML donde se documentaran los registros que no se insertaron en la tabla de migraciones
			$fp = fopen("Errores/ReporteEPS.html", "w+");
			$encabezado = "<html> <head> <title> Reporte errores Central.EPS </title> 
			<link rel='stylesheet' type='text/css' href='../../General/estilos/estilos.css'> </head>";
			fputs($fp, $encabezado);
			fclose($fp);
		}	
		
		
		function funcion0055( $codigo, $nit, $eps,  $error){
		// Inserta en la tabla de migraciones para documentar los errores
			
			$cnx = 	conectar_postgres();
			$cons = "INSERT INTO Central.epsMigracion (codigo, nit , eps  , error ) VALUES ('".$codigo."','".$nit."','".$eps."','".$error."')"	;
			
			$cons = str_replace( "'NULL'","NULL",$cons  );	
			$res = @pg_query($cnx, $cons);
				if(!$res) {
					$consUTF8 = utf8_encode($cons);
					$resUTF8 = @pg_query($cnx, $consUTF8);
						if (!$resUTF8) {
							
							$fp = fopen("Errores/ReporteEPS.html", "a+");	
							$errorEjecucion= "<p class='error1'> Error de ejecucion </p>".pg_last_error()."<br>";
							$consulta= "<p class= 'subtitulo1'>Comando SQL </p> <br>".$consUTF8."<br/> <br/> <br/>";  
							fputs($fp, $errorEjecucion);
							fputs($fp, $consulta);
							fclose($fp);
							
						}
				}	
		}	


		
		
		
		function  funcion0056($cadenaBusqueda,$cadenaReemplazo)  {
		// Busca y reemplaza ocurrencias en una tabla
			$cnx= conectar_postgres();
			$cons = "UPDATE Central.Eps SET codigo = replace( codigo,'". $cadenaBusqueda."'".",'". $cadenaReemplazo."'"."), nit = replace( nit,'". $cadenaBusqueda."'".",'". $cadenaReemplazo."'".") , eps = replace( eps,'". $cadenaBusqueda."'".",'". $cadenaReemplazo."')";
			
			$res = pg_query($cnx , $cons);
				if (!$res)  {
					echo "<p class='error1'> Error en la normalizacion de la codificacion </p>".pg_last_error()."<br>";
				}

		}
		
				
	
		function funcion0057() {
			// Selecciona los registros MySQL (Origen)
			global $res;
			$cnx = conectar_mysql("Salud");
			$cons = "SELECT *  FROM Salud.Eps";
			$res =  mysql_query($cons);
			return $res; 
		
		}
		
		

		
		function funcion0058($codigo, $nit, $eps) {
		//Realiza la insercion en Postgresql con base en los parametros
			
			$cnx = 	conectar_postgres();
			$cons = "INSERT INTO Central.Eps (codigo, nit, eps ) VALUES ('".$codigo."','".$nit."','".$eps."')"	;
					 
			$cons = str_replace( "'NULL'","NULL",$cons  );	
			$res = @pg_query($cnx, $cons);
				if (!$res) {
					$consUTF8 = utf8_encode($cons);
					$resUTF8 = @pg_query($cnx, $consUTF8);					
						if (!$resUTF8) {
							$error = pg_last_error();
							funcion0055( $codigo, $nit, $eps,  $error);
							
						}
				
				}

				
		}
		
		
		function  funcion0059(){
		// Llena una matriz con el resultado de la consulta MySQL
			
			unset($matriz); 
			global  $matriz;	
			$res = funcion0057();
			$posicion=0;
				
				while ($fila = mysql_fetch_array($res))
				{	
					
					$matriz["codigo"][$posicion] = $fila["Codigo"];
					$matriz["nit"][$posicion] = $fila["Nit"];
					$matriz["eps"][$posicion] = $fila["Nombre"];
					
					$posicion++;				
				}
							
				
			}
			

			
			function funcion0060()  {
			// Recorre la matriz con los valores MySQL  inserta los valores en Postgresql
			
				global $res,$matriz;
					for($pos=0;$pos < mysql_num_rows($res); $pos++)  {

					$codigo= 	 $matriz["codigo"][$pos] ;
					$codigo = eliminarCaracteresEspeciales($codigo);
					$nit= 	 $matriz["nit"][$pos] ;
					$nit = eliminarCaracteresEspeciales($nit);
					$eps= 	 $matriz["eps"][$pos] ;
					$eps = eliminarCaracteresEspeciales($eps);
									
					funcion0058($codigo, $nit, $eps);
					
						
					}
			
	
			}
			
			function funcion0061() {
				$cnx= conectar_postgres();
				$cons= "DELETE FROM Central.EPS";
				$res = @pg_query($cnx, $cons);
				if (!$res) {
							echo "<p class='error1'> Error de ejecucion </p>".pg_last_error()."<br>";
							echo "<p class= 'subtitulo1'>Comando SQL </p> <br>".$cons."<br/>";
							
						}
					
			}
			
			
		
		
		function migrarEPS($paso) {
		
			funcion0061();
			funcion0052();
			funcion0053();
			funcion0054();
			funcion0061();
			funcion0057();
			funcion0059();
			funcion0060();
			funcion0056('&Aacute;', utf8_encode("Á"));			
			funcion0056('&Eacute;', utf8_encode("É"));
			funcion0056('&Iacute;', utf8_encode("Í"));
			funcion0056('&Oacute;', utf8_encode("Ó"));
			funcion0056('&Uacute;',utf8_encode("Ú"));
			funcion0056('&Ntilde;',utf8_encode("Ñ"));
			echo "<p class='mensajeEjecucion'> <span class = 'subtitulo1'>Paso $paso: </span> Se ha migrado la tabla Central.EPS </p> ";
	
		}
		
		
		
		
		
	
	
	
	?>
