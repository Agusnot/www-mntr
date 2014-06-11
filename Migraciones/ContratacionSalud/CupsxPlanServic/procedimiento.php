	<html>	
		<head>
			<title> Migracion ContratacionSalud.CUPSxPlanServic </title>
			<link rel="stylesheet" type="text/css" href="../General/estilos/estilos.css">
		</head>
	
	
	<?php
		session_start();
		include_once('../General/funciones/funciones.php');
		
		
		
		
		/* Inicia defincion de funciones */
		
			
		function  normalizarCodificacionCUPSxPlanServic($cadenaBusqueda,$cadenaReemplazo)  {
		// Busca y reemplaza ocurrencias en una tabla
			$cnx= conectar_postgres();
			$cons = "UPDATE ContratacionSalud.CUPSxPlanServic SET compania = replace( compania,'$cadenaBusqueda','$cadenaReemplazo')";
			
			$res = pg_query($cnx , $cons);
				if (!$res)  {
					echo "<p class='error1'> Error en la normalizacion de la codificacion </p>".pg_last_error()."<br>";
					echo  "<p class= 'subtitulo1'>Comando SQL </p> <br>".$cons."<br/> <br/> <br/>";  
					
				}

		}
		
		
		
			
		
		function  normalizarCodificacionPlanesTarifarios1($cadenaBusqueda,$cadenaReemplazo)  {
		// Busca y reemplaza ocurrencias en una tabla
			$cnx= conectar_postgres();
			$cons = "UPDATE ContratacionSalud.PlanesTarifas SET nombreplan = replace( nombreplan,'$cadenaBusqueda','$cadenaReemplazo')";
			
			$res = pg_query($cnx , $cons);
				if (!$res)  {
					echo "<p class='error1'> Error en la normalizacion de la codificacion </p>".pg_last_error()."<br>";
					echo  "<p class= 'subtitulo1'>Comando SQL </p> <br>".$cons."<br/> <br/> <br/>";
				}

		}
		
		
		
		function llamarRegistrosMySQL1($tabla) {
			// Selecciona los registros MySQL (Origen)
			global $res;
			$cnx = conectar_mysql("Salud");
			$cons = "SELECT *  FROM Salud.$tabla ";
			$res =  @mysql_query($cons);
				if (!$res){
					echo "<p class='error1'> Error SQL </p>".mysql_error()."<br>";
					echo  "<p class= 'subtitulo1'>Comando SQL </p> <br>".$cons."<br/> <br/> <br/>";
				}
			return $res; 
		
		}
		
		
		
		
		function contarEPS1(){
			$cnx= conectar_mysql("Salud");
			$cons = "SELECT COUNT(*) AS conteoeps FROM Salud.EPS"; 
			$res = mysql_query($cons);
			$fila = mysql_fetch_array($res);
			$res = $fila["conteoeps"]; 
			return $res;
		
		}
		
		
		
		
		
		
		
		function crearTablaMigracionCUPSxPlanServic() {
		// Esta funcion crea una tabla con estructura similar a la tabla Contabilidad.movimiento, con la diferencia que carece de llave primaria y omite la restriccion NOT NULL,          ademas  crea un nuevo campo llamado error en el cual se almacena la descripcion del error generado en la consulta inicial.
			$cnx= conectar_postgres();
			$cons = "CREATE TABLE IF NOT EXISTS  contratacionsalud.cupsxplanservicMigracion(  autoid integer ,  cup character varying(30) ,  compania character varying(80) ,  reqvobo integer,  facturable integer,  minimos integer,  maximos integer,  clase character varying(60) ,  error text)WITH (  OIDS=FALSE)";
	 		
			$res = @pg_query($cnx, $cons);
				if (!$res) {
				
					echo "<p class='error1'> Error de ejecucion </p>".pg_last_error()."<br>";
					echo "<p class= 'subtitulo1'>Comando SQL </p> <br>".$cons."<br>"; 				
					echo "<br><br>";			
					//echo "<br><br>";
				}
			
		}
		
		
		function insertarCUPSxPlanServicMigracion($autoid, $cup,  $compania,$reqvobo, $facturable, $minimo, $maximo, $clase, $error) {
		//Realiza la insercion en Postgresql con base en los parametros
			
			$cnx = 	conectar_postgres();
			
			$cons = "INSERT INTO ContratacionSalud.CUPSxPlanServicMigracion (autoid, cup,  compania,reqvobo, facturable, minimos, maximos, clase, error ) VALUES ( $autoid, '$cup',  '$compania',$reqvobo, $facturable, $minimo, $maximo, '$clase', '$error')"	;
					 
			$cons = str_replace( "'NULL'","NULL",$cons  );	
			$res = @pg_query($cnx, $cons);
				if (!$res) {
					$consUTF8 = utf8_encode($cons);
					$resUTF8 = @pg_query($cnx, $consUTF8);					
						if (!$resUTF8) {
							$fp = fopen("Errores/ContratacionSalud.html", "a+");	
							$errorEjecucion= "<p class='error1'> Error de ejecucion </p>".pg_last_error()."<br>";
							$consulta= "<p class= 'subtitulo1'>Comando SQL </p> <br>".$consUTF8."<br/> <br/> <br/>";  
							fputs($fp, $errorEjecucion);
							fputs($fp, $consulta);
							fclose($fp);
						
						}
				
				}

				
		}

		
			
		
		
		
		function  llenarMatriz2($tabla){
		// Llena una matriz con el resultado de la consulta MySQL
			
			unset($matriz); 
			global  $matriz;
			$posicion=0;
			$res = 	llamarRegistrosMySQL1($tabla) ;		
			$fin = contarEPS1();
			
				while ($fila = mysql_fetch_array($res))
				{	
					
					$matriz["cup"][$posicion] = $fila["CUP"];
					
						for ($autoid=1;$autoid<=$fin;$autoid++){
							$campo = "A".$autoid;			
							$matriz[$campo][$posicion] = $fila[$campo];	
						}	
					
								
					$posicion++;				
				}
							
				
		}
		
		
		function llenarMatriz3($tabla){
			$cnx = conectar_postgres();
			unset($matriz); 
			global  $matriz;
			$res = llamarRegistrosMySQL1($tabla);
			$posicion=0;
			
				
				while ($fila = mysql_fetch_array($res))	{	
					
					
					$matriz["entidad"][$posicion] = $fila["Entidad"];
					$matriz["cup"][$posicion] = $fila["CUP"];
					$matriz["valor"][$posicion] = $fila["Valor"];
					
					
					$posicion++;										
				
				}
			
		
		}
		
		
		
		
		function recorrerMatriz2()  {
			// Recorre la matriz con los valores MySQL  inserta los valores en Postgresql
			
				global $res,$matriz;
					for($pos=0;$pos < mysql_num_rows($res); $pos++)  {
					
						$compania = $_SESSION["compania"];
						$cup= $matriz["cup"][$pos] ;
						$fin = contarEPS1();
						
							
							for ($autoid=1;$autoid<=$fin;$autoid++){
								$campo = "A".$autoid;
								$valor = $matriz[$campo][$pos];
								$reqvobo = 0;
								$facturable = 1;
								$minimo = 1;
								$maximo=10;
								$clase= "CUPS";
																	
									if (isset($matriz[$campo][$pos]))	{
										
										insertarCUPSxPlanServic($autoid, $cup,  $compania,$reqvobo, $facturable, $minimo, $maximo, $clase);		
									}
									
							}					
		
					}
			
		}
			
			
			
			function recorrerMatriz3()  {
			// Recorre la matriz con los valores MySQL  inserta los valores en Postgresql
			
				global $res,$matriz;
					for($pos=0;$pos < mysql_num_rows($res); $pos++)  {
					
						$compania = $_SESSION["compania"];
						$entidad = $matriz["entidad"][$pos] ;						
						$entidad = eliminarCaracteresEspeciales($entidad);
						$autoid = llamarAutoId($entidad);
						$cup= $matriz["cup"][$pos] ;
						$valor = $matriz["valor"][$pos];
						$reqvobo = 0;
						$facturable = 1;
						$minimo = 1;
						$maximo=10;
						$clase= "CUPS";
						
							
						insertarCUPSxPlanServic($autoid, $cup,  $compania,$reqvobo, $facturable, $minimo, $maximo, $clase);
						
					}
			
			}
		
		
		
		
		
		

		
		function insertarCUPSxPlanServic($autoid, $cup,  $compania,$reqvobo, $facturable, $minimo, $maximo, $clase) {
		
		//Realiza la insercion en Postgresql con base en los parametros
			
			$cnx = 	conectar_postgres();
			
			$cons = "INSERT INTO ContratacionSalud.CUPSxPlanServic (autoid, cup,  compania,reqvobo, facturable, minimos, maximos, clase ) VALUES ( $autoid, '$cup',  '$compania',$reqvobo, $facturable, $minimo, $maximo, '$clase')"	;
					 
			$cons = str_replace( "'NULL'","NULL",$cons  );	
			$res = @pg_query($cnx, $cons);
				if (!$res) {
					$consUTF8 = utf8_encode($cons);
					$resUTF8 = @pg_query($cnx, $consUTF8);					
						if (!$resUTF8) {
							$error = pg_last_error();	
							insertarCUPSxPlanServicMigracion($autoid, $cup,  $compania,$reqvobo, $facturable, $minimo, $maximo, $clase, $error );
						
						}
				
				}

				
		}
		
	
			function eliminarCUPSxPlanServic() {
				$cnx= conectar_postgres();
				$cons= "DELETE FROM ContratacionSalud.CUPSxPlanServic";
				$res = @pg_query($cnx, $cons);
						if (!$res) {
							echo "<p class='error1'> Error de ejecucion </p>".pg_last_error()."<br>";
							echo "<p class= 'subtitulo1'>Comando SQL </p> <br>".$cons."<br/>";
							
						}
					
			}
			
			function eliminarCUPSxPlanServicMigracion() {
				$cnx= conectar_postgres();
				$cons= "DELETE FROM ContratacionSalud.CUPSxPlanServicMigracion";
				$res = @pg_query($cnx, $cons);
						if (!$res) {
							echo "<p class='error1'> Error de ejecucion </p>".pg_last_error()."<br>";
							echo "<p class= 'subtitulo1'>Comando SQL </p> <br>".$cons."<br/>";
							
						}
					
			}
			
			
			
			
		
		
		function migrarCUPSxPlanServic($paso) {
		
			// Tabla ContratacionSalud.PlanesTarifarios 
			normalizarCodificacionPlanesTarifarios1(utf8_encode("Á"),'&Aacute;');			
			normalizarCodificacionPlanesTarifarios1(utf8_encode("É"),'&Eacute;');
			normalizarCodificacionPlanesTarifarios1(utf8_encode("Í"),'&Iacute;');
			normalizarCodificacionPlanesTarifarios1(utf8_encode("Ó"),'&Oacute;');
			normalizarCodificacionPlanesTarifarios1(utf8_encode("Ú"),'&Uacute;');
			normalizarCodificacionPlanesTarifarios1(utf8_encode("Ñ"),'&Ntilde;');
			
			crearTablaMigracionCUPSxPlanServic();
			eliminarCUPSxPlanServicMigracion();
			eliminarCUPSxPlanServic();
			
			llamarRegistrosMySQL1("ConfLaboratorios");
			llenarMatriz2("ConfLaboratorios");
			recorrerMatriz2();
			
						
			llamarRegistrosMySQL1("ConfProcedimientos");
			llenarMatriz2("ConfProcedimientos");
			recorrerMatriz2();
			
		
			llamarRegistrosMySQL1("ConfTransporte");
			llenarMatriz2("ConfTransporte");
			recorrerMatriz2();
			
			llamarRegistrosMySQL1("AtencionConsExterna");
			llenarMatriz2("AtencionConsExterna");
			recorrerMatriz2();
			
			llamarRegistrosMySQL1("ConFacEstancia");
			llenarMatriz3("ConFacEstancia");
			recorrerMatriz3();
			
			llamarRegistrosMySQL1("ConFacAtMd");
			llenarMatriz3("ConFacAtMd");
			recorrerMatriz3();
			
			
			
			// Tabla ContratacionSalud.PlanesTarifarios
			normalizarCodificacionPlanesTarifarios1('&Aacute;', utf8_encode("Á"));			
			normalizarCodificacionPlanesTarifarios1('&Eacute;', utf8_encode("É"));
			normalizarCodificacionPlanesTarifarios1('&Iacute;', utf8_encode("Í"));
			normalizarCodificacionPlanesTarifarios1('&Oacute;', utf8_encode("Ó"));
			normalizarCodificacionPlanesTarifarios1('&Uacute;',utf8_encode("Ú"));
			normalizarCodificacionPlanesTarifarios1('&Ntilde;',utf8_encode("Ñ"));
			
			// Tabla ContratacionSalud.CUPSxPlanServic
			normalizarCodificacionCUPSxPlanServic('&Aacute;', utf8_encode("Á"));			
			normalizarCodificacionCUPSxPlanServic('&Eacute;', utf8_encode("É"));
			normalizarCodificacionCUPSxPlanServic('&Iacute;', utf8_encode("Í"));
			normalizarCodificacionCUPSxPlanServic('&Oacute;', utf8_encode("Ó"));
			normalizarCodificacionCUPSxPlanServic('&Uacute;',utf8_encode("Ú"));
			normalizarCodificacionCUPSxPlanServic('&Ntilde;',utf8_encode("Ñ"));
			echo "<p class='mensajeEjecucion'> <span class = 'subtitulo1'>Paso $paso: </span> Se ha migrado la tabla ContratacionSalud.CUPSxPlanServic </p> ";
	
		}
		
		
		
		
		
	
	
	
	?>
