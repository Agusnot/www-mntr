	<html>	
		<head>
			<title> Migracion Contabilidad.CuentasCierre </title>
			<link rel="stylesheet" type="text/css" href="../General/estilos/estilos.css">
		</head>
	
	
	<?php
		session_start();
		include_once('../General/funciones/funciones.php');
		
		
		
		
		/* Inicia defincion de funciones */
		
			
		function  normalizarCodificacionCuentasCierre($cadenaBusqueda,$cadenaReemplazo)  {
		// Busca y reemplaza ocurrencias en una tabla
			$cnx= conectar_postgres();
			$cons = "UPDATE Contabilidad.CuentasCierre SET compania = replace( compania,'$cadenaBusqueda','$cadenaReemplazo') , ingresos = replace( ingresos,'$cadenaBusqueda','$cadenaReemplazo'),  utilidad = replace( utilidad,'$cadenaBusqueda','$cadenaReemplazo'), perdida = replace( perdida,'$cadenaBusqueda','$cadenaReemplazo'), gastos = replace( gastos,'$cadenaBusqueda','$cadenaReemplazo')";
			
			$res =@ pg_query($cnx , $cons);
				if (!$res)  {
					echo "<p class='error1'> Error en la normalizacion de la codificacion </p>".pg_last_error()."<br>";
					echo "<p class= 'subtitulo1'>Comando SQL </p> <br>".$cons."<br/> <br/> <br/>";  
				}

		}
		
				
	
		function llamarRegistrosMySQLCuentasCierre() {
			// Selecciona los registros MySQL (Origen)
			global $res;
			$cnx = conectar_mysql("Contabilidad");
			$cons = "SELECT *  FROM Contabilidad.CuentasCierre";
			$res =  mysql_query($cons);
			return $res; 
		
		}
		
		

		
		function insertarCuentasCierre($anio, $compania, $ingresos, $gastos, $utilidad, $perdida, $costos) {
		//Realiza la insercion en Postgresql con base en los parametros
			
			$cnx = 	conectar_postgres();
			$cons = "INSERT INTO Contabilidad.CuentasCierre ( anio, compania, ingresos, gastos, utilidad, perdida, costos ) VALUES ($anio, '$compania', '$ingresos', '$gastos', '$utilidad', '$perdida', '$costos')"	;
					 
			$cons = str_replace( "'NULL'","NULL",$cons  );	
			$res = @pg_query($cnx, $cons);
				if (!$res) {
					$consUTF8 = utf8_encode($cons);
					$resUTF8 = @pg_query($cnx, $consUTF8);					
						if (!$resUTF8) {
							$fp = fopen("Errores/ReporteContabilidad.html", "a+");	
							$errorEjecucion= "<p class='error1'> Error de ejecucion </p>".pg_last_error()."<br>";
							$consulta= "<p class= 'subtitulo1'>Comando SQL </p> <br>".$consUTF8."<br/> <br/> <br/>";  
							fputs($fp, $errorEjecucion);
							fputs($fp, $consulta);
							fclose($fp);
							
							
						}
				
				}

				
		}
		
		
		function  llenarMatrizCuentasCierre(){
		// Llena una matriz con el resultado de la consulta MySQL
			
			unset($matriz); 
			global  $matriz;	
			$res = llamarRegistrosMySQLCuentasCierre();
			$posicion=0;
				
				while ($fila = mysql_fetch_array($res))
				{	
					
					$matriz["anio"][$posicion] = $fila["Anio"];
					$matriz["ingresos"][$posicion] = $fila["Ingresos"];
					$matriz["gastos"][$posicion] = $fila["Gastos"];										
					$matriz["utilidad"][$posicion] = $fila["Utilidad"];										
					$matriz["perdida"][$posicion] = $fila["Perdida"];
					$matriz["costos"][$posicion] = $fila["Costos"];										
					$posicion++;				
				}
							
				
			}
			

			
			function recorrerMatrizCuentasCierre()  {
			// Recorre la matriz con los valores MySQL  inserta los valores en Postgresql
			
				global $res,$matriz;
					for($pos=0;$pos < mysql_num_rows($res); $pos++)  {

					$anio= $matriz["anio"][$pos] ;

					$ingresos= $matriz["ingresos"][$pos] ;
					$ingresos = eliminarCaracteresEspeciales($ingresos);
					
					$gastos= $matriz["gastos"][$pos] ;
					$gastos = eliminarCaracteresEspeciales($gastos);
					
					$utilidad= $matriz["utilidad"][$pos] ;
					$utilidad = eliminarCaracteresEspeciales($utilidad);
					
					$perdida= $matriz["perdida"][$pos] ;
					$perdida = eliminarCaracteresEspeciales($perdida);
					
					$costos= $matriz["costos"][$pos] ;
					$costos = eliminarCaracteresEspeciales($costos);
					
					$compania= $_SESSION["compania"];
					
					insertarCuentasCierre($anio, $compania, $ingresos, $gastos, $utilidad, $perdida, $costos);
					
									
					}
			
			}
			
			function eliminarCuentasCierre() {
				$cnx= conectar_postgres();
				$cons= "DELETE FROM Contabilidad.CuentasCierre";
				$res = @pg_query($cnx, $cons);
				if (!$res) {
							echo "<p class='error1'> Error de ejecucion </p>".pg_last_error()."<br>";
							echo "<p class= 'subtitulo1'>Comando SQL </p> <br>".$cons."<br/>";
							
						}
					
			}
			
			
			
			
		
		
		function migrarCuentasCierre($paso) {
		
			eliminarCuentasCierre();
			llamarRegistrosMySQLCuentasCierre();
			llenarMatrizCuentasCierre();
			recorrerMatrizCuentasCierre();
			normalizarCodificacionCuentasCierre('&Aacute;', utf8_encode("Á"));			
			normalizarCodificacionCuentasCierre('&Eacute;', utf8_encode("É"));
			normalizarCodificacionCuentasCierre('&Iacute;', utf8_encode("Í"));
			normalizarCodificacionCuentasCierre('&Oacute;', utf8_encode("Ó"));
			normalizarCodificacionCuentasCierre('&Uacute;',utf8_encode("Ú"));
			normalizarCodificacionCuentasCierre('&Ntilde;',utf8_encode("Ñ"));
			echo "<p class='mensajeEjecucion'> <span class = 'subtitulo1'>Paso $paso: </span> Se ha migrado la tabla Contabilidad.CuentasCierre </p> ";
	
		}
		
		
		
		
		
	
	
	
	?>
