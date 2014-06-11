	<html>	
		<head>
			<title> Migracion Contabilidad.EstructuraPUC </title>
			<link rel="stylesheet" type="text/css" href="../General/estilos/estilos.css">
		</head>
	
	
	<?php
		session_start();
		include_once('../General/funciones/funciones.php');
		
		
		
		
		// Inicia definicion de funciones 
		
			
		function  normalizarCodificacion($cadenaBusqueda,$cadenaReemplazo)  {
		// Busca y reemplaza ocurrencias en una tabla
			$cnx= conectar_postgres();
			$cons = "UPDATE Contabilidad.EstructuraPUC SET detalle = replace( detalle,'$cadenaBusqueda','$cadenaReemplazo')";
			
			$res = pg_query($cnx , $cons);
				if (!$res)  {
					echo "<p class='error1'> Error en la normalizacion de la codificacion </p>".pg_last_error()."<br>";
				}

		}
		
				
	
		function llamarRegistrosMySQL() {
			// Selecciona los registros MySQL (Origen)
			global $res;
			$cnx = conectar_mysql("Contabilidad");
			$cons = "SELECT *  FROM Contabilidad.EstructuraPuc";
			$res =  mysql_query($cons);
			return $res; 
		
		}
		
		

		
		function insertarestructuraPUC($nocaracteres, $detalle, $nivel, $compania, $anio) {
		//Realiza la insercion en Postgresql con base en los parametros
			
			$cnx = 	conectar_postgres();
			$cons = "INSERT INTO Contabilidad.EstructuraPuc ( nocaracteres, detalle, nivel, compania, anio ) VALUES ('$nocaracteres', '$detalle', '$nivel', '$compania', '$anio')"	;
					 
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
		
		
		function  llenarMatriz(){
		// Llena una matriz con el resultado de la consulta MySQL
			
			unset($matriz); 
			global  $matriz;	
			$res = llamarRegistrosMySQL();
			$posicion=0;
				
				while ($fila = mysql_fetch_array($res))
				{	
					
					$matriz["nocaracteres"][$posicion] = $fila["NoCaracteres"];
					$matriz["detalle"][$posicion] = $fila["Detalle"];
					$matriz["nivel"][$posicion] = $fila["Nivel"];										
					$matriz["anio"][$posicion] = $fila["Anio"];										
					$posicion++;				
				}
							
				
			}
			

			
			function recorrerMatriz()  {
			// Recorre la matriz con los valores MySQL  inserta los valores en Postgresql
			
				global $res,$matriz;
					for($pos=0;$pos < mysql_num_rows($res); $pos++)  {

					$nocaracteres= $matriz["nocaracteres"][$pos] ;
					$nocaracteres = eliminarCaracteresEspeciales($nocaracteres);
					$detalle= $matriz["detalle"][$pos] ;
					$detalle = eliminarCaracteresEspeciales($detalle);
					$nivel= $matriz["nivel"][$pos] ;
					$nivel = eliminarCaracteresEspeciales($nivel);
					$anio= $matriz["anio"][$pos] ;
					$anio = eliminarCaracteresEspeciales($anio);
					$compania= $_SESSION["compania"];
					
					insertarestructuraPUC($nocaracteres, $detalle, $nivel, $compania, $anio);
					
									
					}
			
			}
			
			function eliminarEstructuraPUC() {
				$cnx= conectar_postgres();
				$cons= "DELETE FROM Contabilidad.EstructuraPUC";
				$res = @pg_query($cnx, $cons);
				if (!$res) {
							echo "<p class='error1'> Error de ejecucion </p>".pg_last_error()."<br>";
							echo "<p class= 'subtitulo1'>Comando SQL </p> <br>".$cons."<br/>";
							
						}
					
			}
			
			
			
			
		
		
		function migrarEstructuraPUC($paso) {
		
			eliminarEstructuraPUC();
			llamarRegistrosMySQL();
			llenarMatriz();
			recorrerMatriz();
			normalizarCodificacion('&Aacute;', utf8_encode("Á"));			
			normalizarCodificacion('&Eacute;', utf8_encode("É"));
			normalizarCodificacion('&Iacute;', utf8_encode("Í"));
			normalizarCodificacion('&Oacute;', utf8_encode("Ó"));
			normalizarCodificacion('&Uacute;',utf8_encode("Ú"));
			normalizarCodificacion('&Ntilde;',utf8_encode("Ñ"));
			echo "<p class='mensajeEjecucion'> <span class = 'subtitulo1'>Paso $paso: </span> Se ha migrado la tabla Contabilidad.EstructuraPUC </p> ";
	
		}
		
		
		
		
		
	
	
	
	?>
