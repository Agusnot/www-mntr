	<html>	
		<head>
			<title> Migracion Central.CentrosCosto </title>
			<link rel="stylesheet" type="text/css" href="../General/estilos/estilos.css">
		</head>
	
	
	<?php
		
		
		//include_once '../Conexiones/Conexion.php';
		//include_once '../General/funciones/funciones.php';
		
		session_start();
		
		
		/* Inicia defincion de funciones */
		
		
		function funcion0011() {
			// Elimina la tabla de migracion
			$cnx= conectar_postgres();
			$cons = "DROP TABLE  IF EXISTS Central.centroscostoMigracion";
			$res =  pg_query($cons);
			
		}
		
		function funcion0012() {
		// Esta funcion crea una tabla con estructura similar a la tabla Postgresql con la diferencia que carece de llave primaria y omite la restriccion NOT NULL,          ademas  crea un nuevo campo llamado error en el cual se almacena la descripcion del error generado en la consulta inicial.
			$cnx= conectar_postgres();
			$cons = "CREATE TABLE central.centroscostoMigracion(  codigo character varying(20),  centrocostos character varying(80),  compania character varying(60) ,  anio integer ,  tipo character varying(10), error text)WITH (  OIDS=FALSE)";	
			$res = @pg_query($cnx, $cons);
				if (!$res) {
				
					echo "<p class='error1'> Error de ejecucion </p>".pg_last_error()."<br>";
					echo "<p class= 'subtitulo1'>Comando SQL </p> <br>".$cons."<br>"; 				
					echo "<br><br>";			
					//echo "<br><br>";
				}
			
		}
		
		function funcion0013() {
		// Crea un archivo HTML donde se documentaran los registros que no se insertaron en la tabla de migraciones
			$fp = fopen("Errores/ReporteCentroCostos.html", "w+");
			$encabezado = "<html> <head> <title> Reporte errores Central.CentrosCosto </title> 
			<link rel='stylesheet' type='text/css' href='../../General/estilos/estilos.css'> </head>";
			fputs($fp, $encabezado);
			fclose($fp);
		}	
		
		
		function funcion0014($codigo, $centrocosto,$compania, $anio, $tipo, $error){
		// Inserta en la tabla de migraciones para documentar los errores
			
			$cnx = 	conectar_postgres();
			$cons = "INSERT INTO Central.CentroscostoMigracion (codigo, centrocostos,compania, anio ,tipo, error) VALUES ('".$codigo."','".$centrocosto."','".$compania."','".$anio."','".$tipo."','".$error."')"	;
			
			$cons = str_replace( "'NULL'","NULL",$cons  );	
			$res = pg_query($cnx, $cons);
				if(!$res) {
					$consUTF8 = utf8_encode($cons);
					$resUTF8 = @pg_query($cnx, $consUTF8);
						if (!$resUTF8) {
							
							$fp = fopen("Errores/ReporteCentroCostos.html", "a+");	
							$errorEjecucion= "<p class='error1'> Error de ejecucion </p>".pg_last_error()."<br>";
							$consulta= "<p class= 'subtitulo1'>Comando SQL </p> <br>".$consUTF8."<br/> <br/> <br/>";  
							fputs($fp, $errorEjecucion);
							fputs($fp, $consulta);
							fclose($fp);
							
						}
				}	
		}	


		
		
		
		function  funcion0015($cadenaBusqueda,$cadenaReemplazo)  {
		// Busca y reemplaza ocurrencias en una tabla
			$cnx= conectar_postgres();
			$cons = "UPDATE Central.centroscosto SET codigo = replace( codigo,'". $cadenaBusqueda."'".",'". $cadenaReemplazo."'"."), centrocostos = replace( centrocostos,'". $cadenaBusqueda."'".",'". $cadenaReemplazo."'"."), compania = replace( compania,'". $cadenaBusqueda."'".",'". $cadenaReemplazo."'"."), tipo = replace( tipo,'". $cadenaBusqueda."'".",'". $cadenaReemplazo."')";
			
			$res = pg_query($cnx , $cons);
				if (!$res)  {
					echo "<p class='error1'> Error en la normalizacion de la codificacion </p>".pg_last_error()."<br>";
				}

		}
		
				
	
		function funcion0016() {
			// Selecciona los registros MySQL (Origen)
			global $res;
			$cnx = conectar_mysql("Contabilidad");
			$cons = "SELECT *  FROM Contabilidad.CentrosCosto ORDER BY CentroCostos ";
			$res =  mysql_query($cons);
			return $res; 
		
		}
		
		

		
		function funcion0017($codigo, $centrocosto,$compania, $anio, $tipo) {
		//Realiza la insercion en Postgresql con base en los parametros
			
			$cnx = 	conectar_postgres();
			$cons = "INSERT INTO Central.Centroscosto (codigo, centrocostos,compania, anio ,tipo) VALUES ('".$codigo."','".$centrocosto."','".$compania."','".$anio."','".$tipo."')"	;
					 
			$cons = str_replace( "'NULL'","NULL",$cons  );	
			$res = @pg_query($cnx, $cons);
				if (!$res) {
					$consUTF8 = utf8_encode($cons);
					$resUTF8 = @pg_query($cnx, $consUTF8);					
						if (!$resUTF8) {
							$error = pg_last_error();
							funcion0014($codigo, $centrocosto,$compania, $anio, $tipo,$error);
							
						}
				
				}

				
		}
		
		
		function  funcion0018(){
		// Llena una matriz con el resultado de la consulta MySQL
			
			unset($matriz); 
			global  $matriz;	
			$res = funcion0016();
			$posicion=0;
				
				while ($fila = mysql_fetch_array($res))
				{	
					
					$matriz["codigo"][$posicion] = $fila["Codigo"];
					$matriz["centrocosto"][$posicion] = $fila["CentroCostos"];
					$matriz["compania"][$posicion] = $fila["Compania"];
					
					$posicion++;				
				}
							
				
			}
			

			
			function funcion0019()  {
			// Recorre la matriz con los valores MySQL  inserta los valores en Postgresql
			
				global $res,$matriz;
					for($pos=0;$pos < mysql_num_rows($res); $pos++)  {

					
					
					$codigo= 	 $matriz["codigo"][$pos] ;
					$codigo= eliminarCaracteresEspeciales($codigo);	
					$centrocosto= 	 $matriz["centrocosto"][$pos] ;
					$centrocosto= eliminarCaracteresEspeciales($centrocosto);	
					$compania= 	 $_SESSION["compania"];
					$compania= eliminarCaracteresEspeciales($compania);	
					//El tipo y el año se diligencian con los valores que actualmente tiene la abase de datos de Cali. Debido a que en MySQL no existen esos campos.
					$tipo = "Detalle";
					$anio = consultarAnio();
					
						for ($anioinicial = 2005; $anioinicial <=  $anio; $anioinicial++ ) {
							funcion0017($codigo, $centrocosto,$compania, "$anioinicial", $tipo);						
						} 
						
					/*funcion0017($codigo, $centrocosto,$compania, "2005", $tipo);
					funcion0017($codigo, $centrocosto,$compania, "2006", $tipo);
					funcion0017($codigo, $centrocosto,$compania, "2007", $tipo);
					funcion0017($codigo, $centrocosto,$compania, "2008", $tipo);
					funcion0017($codigo, $centrocosto,$compania, "2009", $tipo);
					funcion0017($codigo, $centrocosto,$compania, "2010", $tipo);
					funcion0017($codigo, $centrocosto,$compania, "2011", $tipo);
					funcion0017($codigo, $centrocosto,$compania, "2012", $tipo);
					funcion0017($codigo, $centrocosto,$compania, "2013", $tipo);
					funcion0017($codigo, $centrocosto,$compania, "2014", $tipo);*/
					
						
					}
			
	
			}
			
			function funcion0020() {
				$cnx= conectar_postgres();
				$cons= "DELETE FROM Central.centroscosto";
				$res = @pg_query($cnx, $cons);
				if (!$res) {
							echo "<p class='error1'> Error de ejecucion </p>".pg_last_error()."<br>";
							echo "<p class= 'subtitulo1'>Comando SQL </p> <br>".$cons."<br/>";
							
						}
					
			
			
			
			}
		
		
		function migrarCentralCostos($paso) {
		
			funcion0011();
			funcion0012();
			funcion0013();
			funcion0020();
			funcion0016();
			funcion0018();
			funcion0019();
			funcion0015('&Aacute;', utf8_encode("Á"));			
			funcion0015('&Eacute;', utf8_encode("É"));
			funcion0015('&Iacute;', utf8_encode("Í"));
			funcion0015('&Oacute;', utf8_encode("Ó"));
			funcion0015('&Uacute;',utf8_encode("Ú"));
			funcion0015('&Ntilde;',utf8_encode("Ñ"));
			echo "<p class='mensajeEjecucion'> <span class = 'subtitulo1'>Paso $paso: </span> Se ha migrado la tabla Central.CentroCostos </p> ";
			
	
		}
		
		
		
		
		
	
	
	
	?>
