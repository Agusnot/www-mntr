	<html>	
		<head>
			<title> Migracion Salud.Medicos </title>
			<link rel="stylesheet" type="text/css" href="../General/estilos/estilos.css">
		</head>
	
	
	<?php
		session_start();
		include_once('../General/funciones/funciones.php');
		
		
		
		
		/* Inicia defincion de funciones */
		
			
		function  normalizarCodificacionMedicos($cadenaBusqueda,$cadenaReemplazo)  {
		// Busca y reemplaza ocurrencias en una tabla
			$cnx= conectar_postgres();
			$cons = "UPDATE Salud.Medicos SET Usuario = replace( Usuario,'$cadenaBusqueda','$cadenaReemplazo'),  compania = replace( compania,'$cadenaBusqueda','$cadenaReemplazo'), cargo = replace( cargo,'$cadenaBusqueda','$cadenaReemplazo') ";
			
			$res = pg_query($cnx , $cons);
				if (!$res)  {
					echo "<p class='error1'> Error en la normalizacion de la codificacion </p>".pg_last_error()."<br>";
					echo"<p class= 'subtitulo1'>Comando SQL </p> <br>".$cons."<br/> <br/> <br/>";  
				}

		}
		
		function  normalizarCodificacionCargos1($cadenaBusqueda,$cadenaReemplazo)  {
		// Busca y reemplaza ocurrencias en una tabla
			$cnx= conectar_postgres();
			$cons = "UPDATE Salud.Cargos SET cargos = replace( cargos,'$cadenaBusqueda','$cadenaReemplazo'),  compania = replace( compania,'$cadenaBusqueda','$cadenaReemplazo') ";
			
			$res = pg_query($cnx , $cons);
				if (!$res)  {
					echo "<p class='error1'> Error en la normalizacion de la codificacion </p>".pg_last_error()."<br>";
					echo"<p class= 'subtitulo1'>Comando SQL </p> <br>".$cons."<br/> <br/> <br/>";  
				}

		}
		
				
	
		function llamarRegistrosMySQLMedicos() {
			// Selecciona los registros MySQL (Origen)
			global $res;
			$cnx = conectar_mysql("Salud");
			$cons = "SELECT *  FROM Salud.DatosMedicos";
			$res =  mysql_query($cons);
			return $res; 
		
		}
		
		

		
		function insertarMedicos($usuario, $rm, $cargo, $compania, $estadomed, $especialidad) {
		//Realiza la insercion en Postgresql con base en los parametros
			
			$cnx = 	conectar_postgres();
			$cons = "INSERT INTO Salud.Medicos ( usuario, rm, cargo, compania, estadomed, especialidad ) VALUES ('$usuario', '$rm', '$cargo', '$compania', '$estadomed', '$especialidad')"	;
					 
			$cons = str_replace( "'NULL'","NULL",$cons  );	
			$res = @pg_query($cnx, $cons);
				if (!$res) {
					$consUTF8 = utf8_encode($cons);
					$resUTF8 = @pg_query($cnx, $consUTF8);					
						if (!$resUTF8) {
							$fp = fopen("Errores/ErroresEsquemaSalud.html", "a+");	
							$errorEjecucion= "<p class='error1'> Error de ejecucion </p>".pg_last_error()."<br>";
							$consulta= "<p class= 'subtitulo1'>Comando SQL </p> <br>".$consUTF8."<br/> <br/> <br/>";  
							fputs($fp, $errorEjecucion);
							fputs($fp, $consulta);
							fclose($fp);
							
							
						}
				
				}

				
		}
		
		
		function  llenarMatrizMedicos(){
		// Llena una matriz con el resultado de la consulta MySQL
			
			unset($matriz); 
			global  $matriz;	
			$res = llamarRegistrosMySQLMedicos();
			$posicion=0;
				
				while ($fila = mysql_fetch_array($res))
				{	
					
					$matriz["usuario"][$posicion] = $fila["Nombre"];
					$matriz["rm"][$posicion] = $fila["RM"];
					$matriz["cargo"][$posicion] = $fila["Cargo"];
					$matriz["estadomed"][$posicion] = $fila["Activo"];
					
															
					$posicion++;				
				}
							
				
			}
			

			
			function recorrerMatrizMedicos()  {
			// Recorre la matriz con los valores MySQL  inserta los valores en Postgresql
			
				global $res,$matriz;
					for($pos=0;$pos < mysql_num_rows($res); $pos++)  {

					$usuario= $matriz["usuario"][$pos] ;
					$usuario = normalizarMedicos($usuario);
					$usuario = eliminarCaracteresEspeciales($usuario);
					
					
					$rm= $matriz["rm"][$pos] ;
					$cargo= $matriz["cargo"][$pos] ;
					$cargo = normalizarCargos($cargo);
					$especialidad = normalizarEspecialidades($cargo);
					$cargo = eliminarCaracteresEspeciales($cargo);
					
					
						
					$compania= $_SESSION["compania"];
					$estadomed= $matriz["estadomed"][$pos] ;
					
					
						if ($estadomed == 0) {
							$estadomed = "Activo";
						}
						
						if ($estadomed == 1) {
							$estadomed = "Inactivo";
						}
						
						if ($usuario == "JUAN CASTRO NAVARRO" and $cargo == "PSIQUIATRA"){
							$estadomed = "Inactivo";
						}	
					
					
					
					
					insertarMedicos($usuario, $rm, $cargo, $compania, $estadomed, $especialidad);
					
									
					}
			
			}
			
			function eliminarMedicos() {
				$cnx= conectar_postgres();
				$cons= "TRUNCATE Salud.Medicos CASCADE ";
				$res = @pg_query($cnx, $cons);
				if (!$res) {
							echo "<p class='error1'> Error de ejecucion </p>".pg_last_error()."<br>";
							echo "<p class= 'subtitulo1'>Comando SQL </p> <br>".$cons."<br/>";
							
						}
					
			}
			
			
			
			function medicosFaltantes() {
		
			$cnx = conectar_postgres();
			$ruta = $_SERVER['DOCUMENT_ROOT'];
			$cons= "COPY Salud.Medicos FROM '$ruta/Migraciones/Salud/Medicos/Medicos.csv' WITH DELIMITER ';' CSV HEADER;";
			$res =  @pg_query($cons);
						if (!$res) {
							echo "<p class='error1'> Error de ejecucion </p>".pg_last_error()."<br>";
							echo "<p class= 'subtitulo1'>Comando SQL </p> <br>".$cons."<br/>";
							
						}
			
			
		}
			
			
			
			
			
			
			
		
		
		function migrarMedicos($paso) {
		
			// Tabla Salud.Cargos
			normalizarCodificacionCargos1('&Aacute;',utf8_encode("Á"));			
			normalizarCodificacionCargos1('&Eacute;',utf8_encode("É"));
			normalizarCodificacionCargos1('&Iacute;',utf8_encode("Í"));
			normalizarCodificacionCargos1('&Oacute;',utf8_encode("Ó"));
			normalizarCodificacionCargos1('&Uacute;',utf8_encode("Ú"));
			normalizarCodificacionCargos1('&Ntilde;',utf8_encode("Ñ"));
			
			eliminarMedicos();
			llamarRegistrosMySQLMedicos();
			llenarMatrizMedicos();
			recorrerMatrizMedicos();
			//medicosFaltantes();
			
			
			//Tabla Salud.Cargos
			normalizarCodificacionCargos1('&Aacute;', utf8_encode("Á"));			
			normalizarCodificacionCargos1('&Eacute;', utf8_encode("É"));
			normalizarCodificacionCargos1('&Iacute;', utf8_encode("Í"));
			normalizarCodificacionCargos1('&Oacute;', utf8_encode("Ó"));
			normalizarCodificacionCargos1('&Uacute;',utf8_encode("Ú"));
			normalizarCodificacionCargos1('&Ntilde;',utf8_encode("Ñ"));
			
			//Tabla Salud.Medicos
			normalizarCodificacionMedicos('&Aacute;', utf8_encode("Á"));			
			normalizarCodificacionMedicos('&Eacute;', utf8_encode("É"));
			normalizarCodificacionMedicos('&Iacute;', utf8_encode("Í"));
			normalizarCodificacionMedicos('&Oacute;', utf8_encode("Ó"));
			normalizarCodificacionMedicos('&Uacute;',utf8_encode("Ú"));
			normalizarCodificacionMedicos('&Ntilde;',utf8_encode("Ñ"));
			normalizarCodificacionMedicos('&Aacute&', utf8_encode("Á"));			
			normalizarCodificacionMedicos('&Eacute&', utf8_encode("É"));
			normalizarCodificacionMedicos('&Iacute&', utf8_encode("Í"));
			normalizarCodificacionMedicos('&Oacute&', utf8_encode("Ó"));
			normalizarCodificacionMedicos('&Uacute&',utf8_encode("Ú"));
			normalizarCodificacionMedicos('&Ntilde&',utf8_encode("Ñ"));
			echo "<p class='mensajeEjecucion'> <span class = 'subtitulo1'>Paso $paso: </span> Se ha migrado la tabla Salud.Medicos </p> ";
			
	
		}
		
		
		
		
		
	
	
	
	?>
