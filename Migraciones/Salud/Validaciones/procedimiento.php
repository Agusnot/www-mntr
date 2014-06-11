	<html>	
		<head>
			<title> Migracion Salud.Medicos </title>
			<link rel="stylesheet" type="text/css" href="../General/estilos/estilos.css">
		</head>
	
	
	<?php
		session_start();
		include_once('../../General/funciones/funciones.php');
		include_once('../../Conexiones/conexion.php');
		
		
		
		
		/* Inicia defincion de funciones */
		
			
		function  normalizarCodificacionMedicos($cadenaBusqueda,$cadenaReemplazo)  {
		// Busca y reemplaza ocurrencias en una tabla
			$cnx= conectar_postgres();
			$cons = "UPDATE Salud.Medicos SET Usuario = replace( Usuario,'$cadenaBusqueda','$cadenaReemplazo'),  compania = replace( compania,'$cadenaBusqueda','$cadenaReemplazo') ";
			
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
			$cons = "SELECT DISTINCT(UPPER(TRIM(Medico)))  AS medico FROM Salud.Hospitalizacion WHERE UPPER(TRIM(Medico)) NOT IN (SELECT DISTINCT(UPPER(TRIM(Nombre))) FROM Salud.DatosMedicos)";
			$res =  mysql_query($cons);
			return $res; 
		
		}
		
		

		
		function buscarMedico($medico) {
		//Realiza la insercion en Postgresql con base en los parametros
			
			$cnx = 	conectar_mysql("Salud");
			$cons = "SELECT COUNT(*) AS conteo FROM   Salud.Hospitalizacion WHERE UPPER(Medico) = '$medico'"	;
					 
			
			$res = mysql_query($cons);
			$fila= mysql_fetch_array($res);
			$res = $fila["conteo"];
			return $res;
			
				

				
		}
		
		
		function  llenarMatrizMedicos(){
		// Llena una matriz con el resultado de la consulta MySQL
			
			unset($matriz); 
			global  $matriz;	
			$res = llamarRegistrosMySQLMedicos();
			$posicion=0;
				
				while ($fila = mysql_fetch_array($res))
				{	
					
					$matriz["medico"][$posicion] = $fila["medico"];
						
															
					$posicion++;				
				}
							
				
			}
			

			
			function recorrerMatrizMedicos()  {
			// Recorre la matriz con los valores MySQL  inserta los valores en Postgresql
			
				global $res,$matriz;
					$total = 0;	
					echo "<table border='1'>";
					for($pos=0;$pos < mysql_num_rows($res); $pos++)  {

					$medico= $matriz["medico"][$pos] ;
					
									
					$numero = buscarMedico($medico);
						if ($numero > 0 && $numero <= 5){
						/*echo "Medico: ".$medico."<br>";
						echo "Registros : ".$numero."<br>";*/
						echo "<tr>";
						echo "<td>".$medico."</td>";
						echo "<td>".$numero."</td>";
						echo "</tr>";
						$total = $total + $numero;
						
						}
							
					}
					echo "</table>";
					echo "EL NUMERO TOTAL ES: ".$total."<br>";
			
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
			
			
			
			
			function validacion() {
			llamarRegistrosMySQLMedicos();
			llenarMatrizMedicos();
			recorrerMatrizMedicos();
			}
			
			
		
			validacion();
			
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
			echo "<p class='mensajeEjecucion'> <span class = 'subtitulo1'>Paso $paso: </span> Se ha migrado la tabla Salud.Medicos </p> ";
			
	
		}
		
		
		
		
		
	
	
	
	?>
