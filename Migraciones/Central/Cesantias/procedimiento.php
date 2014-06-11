	<html>	
		<head>
			<title> Migracion Central.Cesatinas </title>
			<link rel="stylesheet" type="text/css" href="../General/estilos/estilos.css">
		</head>
	
	
	<?php
		
		
		//include_once '../Conexiones/Conexion.php';
		//include_once '../General/funciones/funciones.php';
		
		
		/* Inicia defincion de funciones */
		
		
		function funcion0001() {
			// Elimina la tabla de migracion
			$cnx= conectar_postgres();
			$cons = "DROP TABLE  IF EXISTS Central.cesantiasMigracion";
			$res =  pg_query($cons);
			
		}
		
		
		
		function funcion0002() {
		// Esta funcion crea una tabla con estructura similar a la tabla Postgresql con la diferencia que carece de llave primaria y omite la restriccion NOT NULL,          ademas  crea un nuevo campo llamado error en el cual se almacena la descripcion del error generado en la consulta inicial.
			$cnx= conectar_postgres();
			$cons = "CREATE TABLE central.cesantiasMigracion (codigo character varying(10) ,  nit character varying(20) ,  nombre character varying(100), error text)WITH (  OIDS=FALSE)";
			$res = @pg_query($cnx, $cons);
				if (!$res) {
				
					echo "<p class='error1'> Error de ejecucion </p>".pg_last_error()."<br>";
					echo "<p class= 'subtitulo1'>Comando SQL </p> <br>".$cons."<br>"; 				
					echo "<br><br>";			
					//echo "<br><br>";
				}
			
		}
		
		function funcion0003() {
			$fp = fopen("Errores/ReporteCentralCesantias.html", "w+");
			$encabezado = "<html> <head> <title> Reporte errores Central.Cesantias </title> 
			<link rel='stylesheet' type='text/css' href='../../General/estilos/estilos.css'> </head>";
			fputs($fp, $encabezado);
			fclose($fp);
		}	
		
		
		function funcion0004($codigo, $nit,$nombre,$error){
			
			$cnx = 	conectar_postgres();
			$cons = "INSERT INTO Central.CesantiasMigracion (codigo, nit,nombre,error) VALUES ('".$codigo."','".$nit."','".$nombre."','".$error."')";
					 
			$cons = str_replace( "'NULL'","NULL",$cons  );	
			$res = @pg_query($cnx, $cons);
				if(!$res) {
					$consUTF8 = utf8_encode($cons);
					$resUTF8 = @pg_query($cnx, $consUTF8);
						if (!$resUTF8) {
							
							$fp = fopen("Errores/ReporteCentralCesantias.html", "a+");	
							$errorEjecucion= "<p class='error1'> Error de ejecucion </p>".pg_last_error()."<br>";
							$consulta= "<p class= 'subtitulo1'>Comando SQL </p> <br>".$consUTF8."<br/> <br/> <br/>";  
							fputs($fp, $errorEjecucion);
							fputs($fp, $consulta);
							fclose($fp);
							
						}
				}	
		}	
		
		
		
		
		
		function  funcion0005($cadenaBusqueda,$cadenaReemplazo)  {
		// Busca y reemplaza ocurrencias en una tabla
			$cnx= conectar_postgres();
			$cons = "UPDATE Central.cesantias SET codigo = replace( codigo,'". $cadenaBusqueda."'".",'". $cadenaReemplazo."'"."), nit = replace( nit,'". $cadenaBusqueda."'".",'". $cadenaReemplazo."'"."), nombre = replace( nombre,'". $cadenaBusqueda."'".",'". $cadenaReemplazo."'".")";
			
			$res = pg_query($cnx , $cons);
				if (!$res)  {
					echo "<p class='error1'> Error en la normalizacion de la codificacion </p>".pg_last_error()."<br>";
				}

		}
		
				
	
		function funcion0006() {
			// Selecciona los registros MySQL (Origen)
			global $res;
			$cnx = conectar_mysql("Nomina");
			$cons = "SELECT *  FROM Nomina.cesantias ORDER BY Nit ASC ";
			$res =  mysql_query($cons);
			return $res; 
		
		}
		
		

		
		function funcion0007($codigo, $nit,$nombre) {
		//Realiza la insercion en Postgresql con base en los parametros
			
			$cnx = 	conectar_postgres();
			$cons = "INSERT INTO Central.Cesantias (codigo, nit,nombre) VALUES ('".$codigo."','".$nit."','".$nombre."')"	;
					 
			$cons = str_replace( "'NULL'","NULL",$cons  );	
			$res = @pg_query($cnx, $cons);
				if (!$res) {
					$consUTF8 = utf8_encode($cons);
					$resUTF8 = @pg_query($cnx, $consUTF8);					
						if (!$resUTF8) {
							$error = pg_last_error();
							funcion0004($codigo, $nit,$nombre, $error);
							
						}
				
				}

				
		}
		
		
		function  funcion0008(){
		// Llena una matriz con el resultado de la consulta MySQL
			
			unset($matriz); 
			global  $matriz;	
			$res = funcion0006();
			$posicion=0;
				
				while ($fila = mysql_fetch_array($res))
				{	
					
					$matriz["codigo"][$posicion] = $fila["Codigo"];
					$matriz["nit"][$posicion] = $fila["NIT"];
					$matriz["nombre"][$posicion] = $fila["Nombre"];
					
					$posicion++;				
				}
							
				
			}
			

			
			function funcion0009()  {
			// Recorre la matriz con los valores MySQL  inserta los valores en Postgresql
			
				global $res,$matriz;
					for($pos=0;$pos < mysql_num_rows($res); $pos++)  {

					
					
					$codigo= 	 $matriz["codigo"][$pos] ;
					$codigo= eliminarCaracteresEspeciales($codigo);	
					$nit= 	 $matriz["nit"][$pos] ;
					$nit= eliminarCaracteresEspeciales($nit);	
					$nombre= 	 $matriz["nombre"][$pos] ;
					$nombre= eliminarCaracteresEspeciales($nombre);	
					
					funcion0007($codigo, $nit,$nombre);
					
						
					}
			
	
			}
			
			function funcion0010() {
				$cnx= conectar_postgres();
				$cons= "DELETE FROM Central.Cesantias";
				$res = @pg_query($cnx, $cons);
				if (!$res) {
							echo "<p class='error1'> Error de ejecucion </p>".pg_last_error()."<br>";
							echo "<p class= 'subtitulo1'>Comando SQL </p> <br>".$cons."<br/>";
							
						}
					
			
			
			
			}
		
		
		function migrarNominaCesantias($paso) {
			
			funcion0001();
			funcion0002();
			funcion0003();
			funcion0010();
			funcion0006();
			funcion0008();
			funcion0009();
			funcion0005('&Aacute;', utf8_encode("Á"));			
			funcion0005('&Eacute;', utf8_encode("É"));
			funcion0005('&Iacute;', utf8_encode("Í"));
			funcion0005('&Oacute;', utf8_encode("Ó"));
			funcion0005('&Uacute;',utf8_encode("Ú"));
			funcion0005('&Ntilde;',utf8_encode("Ñ"));
			echo "<p class='mensajeEjecucion'> <span class = 'subtitulo1'>Paso $paso: </span> Se ha migrado la tabla Central.Cesantias </p> ";
	
		}
		
		
		
		
		
	
	
	
	?>
