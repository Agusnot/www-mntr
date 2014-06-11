	<html>	
		<head>
			<title> Migracion Central.Cierrexperiodos </title>
			<link rel="stylesheet" type="text/css" href="../General/estilos/estilos.css">
		</head>
	
	
	<?php
		
		
		//include_once '../Conexiones/Conexion.php';
		//include_once '../General/funciones/funciones.php';
		
		
		/* Inicia defincion de funciones */
		
		
		function funcion0021() {
			// Elimina la tabla de migracion
			$cnx= conectar_postgres();
			$cons = "DROP TABLE  IF EXISTS Central.cierrexperiodosMigracion";
			$res =  pg_query($cons);
			
		}
		
		function funcion0022() {
		// Esta funcion crea una tabla con estructura similar a la tabla Postgresql con la diferencia que carece de llave primaria y omite la restriccion NOT NULL,          ademas  crea un nuevo campo llamado error en el cual se almacena la descripcion del error generado en la consulta inicial.
			$cnx= conectar_postgres();
			$cons = "CREATE TABLE central.cierrexperiodosMigracion(  compania character varying(200) ,  anio integer  ,  mes integer  ,  cierrefiscal integer,  modulo character varying(100), error text)WITH (  OIDS=FALSE)	 			";	
			$res = @pg_query($cnx, $cons);
				if (!$res) {
				
					echo "<p class='error1'> Error de ejecucion </p>".pg_last_error()."<br>";
					echo "<p class= 'subtitulo1'>Comando SQL </p> <br>".$cons."<br>"; 				
					echo "<br><br>";			
					//echo "<br><br>";
				}
			
		}
		
		function funcion0023() {
		// Crea un archivo HTML donde se documentaran los registros que no se insertaron en la tabla de migraciones
			$fp = fopen("Errores/ReporteCierrexPeriodos.html", "w+");
			$encabezado = "<html> <head> <title> Reporte errores Central.Cierre por periodos </title> 
			<link rel='stylesheet' type='text/css' href='../../General/estilos/estilos.css'> </head>";
			fputs($fp, $encabezado);
			fclose($fp);
		}	
		
		
		function funcion0024($compania,$anio,$mes,$cierrefiscal,$modulo, $error){
		// Inserta en la tabla de migraciones para documentar los errores
			
			$cnx = 	conectar_postgres();
			$cons = "INSERT INTO Central.cierrexperiodosMigracion (compania, anio,mes, cierrefiscal ,modulo, error) VALUES ('".$compania."','".$anio."','".$mes."','".$cierrefiscal."','".$modulo."','".$error."')"	;
			
			$cons = str_replace( "'NULL'","NULL",$cons  );	
			$res = pg_query($cnx, $cons);
				if(!$res) {
					$consUTF8 = utf8_encode($cons);
					$resUTF8 = @pg_query($cnx, $consUTF8);
						if (!$resUTF8) {
							
							$fp = fopen("Errores/ReporteCierrexPeriodos.html", "a+");	
							$errorEjecucion= "<p class='error1'> Error de ejecucion </p>".pg_last_error()."<br>";
							$consulta= "<p class= 'subtitulo1'>Comando SQL </p> <br>".$consUTF8."<br/> <br/> <br/>";  
							fputs($fp, $errorEjecucion);
							fputs($fp, $consulta);
							fclose($fp);
							
						}
				}	
		}	


		
		
		
		function  funcion0025($cadenaBusqueda,$cadenaReemplazo)  {
		// Busca y reemplaza ocurrencias en una tabla
			$cnx= conectar_postgres();
			$cons = "UPDATE Central.cierrexperiodos SET compania = replace( compania,'". $cadenaBusqueda."'".",'". $cadenaReemplazo."'"."), modulo = replace( modulo,'". $cadenaBusqueda."'".",'". $cadenaReemplazo."'".")";
			
			$res = pg_query($cnx , $cons);
				if (!$res)  {
					echo "<p class='error1'> Error en la normalizacion de la codificacion </p>".pg_last_error()."<br>";
				}

		}
		
				
	
		function funcion0026() {
			// Selecciona los registros MySQL (Origen)
			global $res;
			$cnx = conectar_mysql("Contabilidad");
			$cons = "SELECT *  FROM Contabilidad.CierrexPeriodos";
			$res =  mysql_query($cons);
			return $res; 
		
		}
		
		

		
		function funcion0027($compania,$anio,$mes,$cierrefiscal,$modulo) {
		//Realiza la insercion en Postgresql con base en los parametros
			
			$cnx = 	conectar_postgres();
			$cons = "INSERT INTO Central.cierrexperiodos (compania, anio,mes, cierrefiscal ,modulo) VALUES ('".$compania."','".$anio."','".$mes."','".$cierrefiscal."','".$modulo."')"	;
					 
			$cons = str_replace( "'NULL'","NULL",$cons  );	
			$res = @pg_query($cnx, $cons);
				if (!$res) {
					$consUTF8 = utf8_encode($cons);
					$resUTF8 = @pg_query($cnx, $consUTF8);					
						if (!$resUTF8) {
							$error = pg_last_error();
							funcion0024($compania,$anio,$mes,$cierrefiscal,$modulo,$error);
							
						}
				
				}

				
		}
		
		
		function  funcion0028(){
		// Llena una matriz con el resultado de la consulta MySQL
			
			unset($matriz); 
			global  $matriz;	
			$res = funcion0026();
			$posicion=0;
				
				while ($fila = mysql_fetch_array($res))
				{	
					
					$matriz["compania"][$posicion] = $fila["Compania"];
					$matriz["anio"][$posicion] = $fila["Anio"];
					$matriz["mes"][$posicion] = $fila["Mes"];
					$matriz["cierrefiscal"][$posicion] = $fila["CierreFiscal"];
					
					$posicion++;				
				}
							
				
			}
			

			
			function funcion0029()  {
			// Recorre la matriz con los valores MySQL  inserta los valores en Postgresql
			
				global $res,$matriz;
					for($pos=0;$pos < mysql_num_rows($res); $pos++)  {

					
					
					$compania= 	 "CLINICA SAN JUAN DE DIOS" ;
					$anio= 	 $matriz["anio"][$pos] ;
					$mes= 	 $matriz["mes"][$pos] ;
					$anio= 	 $matriz["anio"][$pos] ;
					$cierrefiscal= 	 $matriz["cierrefiscal"][$pos] ;
					
					//El campo modulo se diligencia con el mismo nombre del campo por que es NOT NULL. Teniendo en cuenta que en MySQL no existe ese campos.
					if($cierrefiscal == 0) {
					$modulo = "MODULO";
					}
					if($cierrefiscal == 1) {
					$modulo = "Contabilidad";
					}
					
					funcion0027($compania,$anio,$mes,$cierrefiscal,$modulo);
					
						
					}

			}
			
			function funcion0030() {
				$cnx= conectar_postgres();
				$cons= "DELETE FROM Central.cierrexperiodos";
				$res = @pg_query($cnx, $cons);
				if (!$res) {
							echo "<p class='error1'> Error de ejecucion </p>".pg_last_error()."<br>";
							echo "<p class= 'subtitulo1'>Comando SQL </p> <br>".$cons."<br/>";
							
						}
					
			
			
			
			}
		
		
		function migrarCierrexPeriodos($paso) {
		
			funcion0021();
			funcion0022();
			funcion0023();
			funcion0030();
			funcion0026();
			funcion0028();
			funcion0029();
			funcion0025('&Aacute;', utf8_encode("Á"));			
			funcion0025('&Eacute;', utf8_encode("É"));
			funcion0025('&Iacute;', utf8_encode("Í"));
			funcion0025('&Oacute;', utf8_encode("Ó"));
			funcion0025('&Uacute;',utf8_encode("Ú"));
			funcion0025('&Ntilde;',utf8_encode("Ñ"));
			echo "<p class='mensajeEjecucion'> <span class = 'subtitulo1'>Paso $paso: </span> Se ha migrado la tabla Central.CierrexPeriodos </p> ";
	
		}
		
		
		
		
		
	
	
	
	?>
