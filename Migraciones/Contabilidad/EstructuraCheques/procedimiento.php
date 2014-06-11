	<html>	
		<head>
			<title> Migracion Contabilidad.EstructuraCheques </title>
			<link rel="stylesheet" type="text/css" href="../General/estilos/estilos.css">
		</head>
	
	
	<?php
		session_start();
		include_once('../General/funciones/funciones.php');
		
		
		
		
		/* Inicia defincion de funciones */
		
			
		function  normalizarCodificacionEstCheques($cadenaBusqueda,$cadenaReemplazo)  {
		// Busca y reemplaza ocurrencias en una tabla
			$cnx= conectar_postgres();
			$cons = "UPDATE Contabilidad.EstructuraCheques SET compania = replace( compania,'$cadenaBusqueda','$cadenaReemplazo') , cuenta = replace( cuenta,'$cadenaBusqueda','$cadenaReemplazo')";
			
			$res =@ pg_query($cnx , $cons);
				if (!$res)  {
					echo "<p class='error1'> Error en la normalizacion de la codificacion </p>".pg_last_error()."<br>";
					echo "<p class= 'subtitulo1'>Comando SQL </p> <br>".$cons."<br/> <br/> <br/>";  
				}

		}
		
				
	
		function llamarRegistrosMySQLEstCheque() {
			// Selecciona los registros MySQL (Origen)
			global $res;
			$cnx = conectar_mysql("Contabilidad");
			$cons = "SELECT *  FROM Contabilidad.EstructuraCheques";
			$res =  mysql_query($cons);
			return $res; 
		
		}
		
		

		
		function insertarEstCheques($compania, $cuenta, $aniox, $anioy, $mesx, $mesy, $diax, $diay, $valorx, $valory, $tercerox, $terceroy, $letrasx, $letrasy, $anio) {
		//Realiza la insercion en Postgresql con base en los parametros
			
			$cnx = 	conectar_postgres();
			$cons = "INSERT INTO Contabilidad.EstructuraCheques (compania, cuenta, aniox, anioy, mesx, mesy, diax, diay, valorx, valory, tercerox, terceroy, letrasx, letrasy, anio ) VALUES ('$compania', '$cuenta', $aniox, $anioy, $mesx, $mesy, $diax, $diay, $valorx, $valory, $tercerox, $terceroy, $letrasx, $letrasy, $anio)"	;
					 
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
		
		
		function  llenarMatrizEstCheques(){
		// Llena una matriz con el resultado de la consulta MySQL
			
			unset($matriz); 
			global  $matriz;	
			$res = llamarRegistrosMySQLEstCheque();
			$posicion=0;
				
				while ($fila = mysql_fetch_array($res))
				{	
					
					$matriz["cuenta"][$posicion] = $fila["Cuenta"];
					$matriz["aniox"][$posicion] = $fila["AnioX"];
					$matriz["anioy"][$posicion] = $fila["AnioY"];										
					$matriz["mesx"][$posicion] = $fila["MesX"];										
					$matriz["mesy"][$posicion] = $fila["MesY"];
					$matriz["diax"][$posicion] = $fila["DiaX"];	
					$matriz["diay"][$posicion] = $fila["DiaY"];
					$matriz["valorx"][$posicion] = $fila["ValorX"];										
					$matriz["valory"][$posicion] = $fila["ValorY"];										
					$matriz["tercerox"][$posicion] = $fila["TerceroX"];
					$matriz["terceroy"][$posicion] = $fila["TerceroY"];														
					$matriz["letrasx"][$posicion] = $fila["LetrasX"];					
					$matriz["letrasy"][$posicion] = $fila["LetrasY"];
					$matriz["tercerox"][$posicion] = $fila["TerceroX"];						
									
					$posicion++;				
				}
							
				
			}
			

			
			function recorrerMatrizEstCheques()  {
			// Recorre la matriz con los valores MySQL  inserta los valores en Postgresql
			
				global $res,$matriz;
					for($pos=0;$pos < mysql_num_rows($res); $pos++)  {

					

					$cuenta= $matriz["cuenta"][$pos] ;
					$cuenta = eliminarCaracteresEspeciales($cuenta);
					
					$aniox= $matriz["aniox"][$pos] ;
					$aniox = eliminarCaracteresEspeciales($aniox);
					
					$anioy= $matriz["anioy"][$pos] ;
					$anioy = eliminarCaracteresEspeciales($anioy);
					
					$mesx= $matriz["mesx"][$pos] ;
					$mesx = eliminarCaracteresEspeciales($mesx);
					
					$mesy= $matriz["mesy"][$pos] ;
					$mesy = eliminarCaracteresEspeciales($mesy);
					
					$diax= $matriz["diax"][$pos] ;
					$diax = eliminarCaracteresEspeciales($diax);
					
					$diay= $matriz["diay"][$pos] ;
					$diay = eliminarCaracteresEspeciales($diay);
					
					$valorx= $matriz["valorx"][$pos] ;
					$valorx = eliminarCaracteresEspeciales($valorx);
					
					$valory= $matriz["valory"][$pos] ;
					$valory = eliminarCaracteresEspeciales($valory);
					
					$tercerox= $matriz["tercerox"][$pos] ;
					$tercerox = eliminarCaracteresEspeciales($tercerox);
					
					$terceroy= $matriz["terceroy"][$pos] ;
					$terceroy = eliminarCaracteresEspeciales($terceroy);
					
					$letrasx= $matriz["letrasx"][$pos] ;
					$letrasx = eliminarCaracteresEspeciales($letrasx);
					
					$letrasy= $matriz["letrasy"][$pos] ;
					$letrasy = eliminarCaracteresEspeciales($letrasy);
					
					$compania= $_SESSION["compania"];
					$anio= consultarAnio();
					
					insertarEstCheques($compania, $cuenta, $aniox, $anioy, $mesx, $mesy, $diax, $diay, $valorx, $valory, $tercerox, $terceroy, $letrasx, $letrasy, $anio);
					
									
					}
			
			}
			
			function eliminarEstCheques() {
				$cnx= conectar_postgres();
				$cons= "DELETE FROM Contabilidad.EstructuraCheques";
				$res = @pg_query($cnx, $cons);
				if (!$res) {
							echo "<p class='error1'> Error de ejecucion </p>".pg_last_error()."<br>";
							echo "<p class= 'subtitulo1'>Comando SQL </p> <br>".$cons."<br/>";
							
						}
					
			}
			
			
			
			
		
		
		function migrarEstCheques($paso) {
		
			eliminarEstCheques();
			llamarRegistrosMySQLEstCheque();
			llenarMatrizEstCheques();
			recorrerMatrizEstCheques();
			normalizarCodificacionEstCheques('&Aacute;', utf8_encode("Á"));			
			normalizarCodificacionEstCheques('&Eacute;', utf8_encode("É"));
			normalizarCodificacionEstCheques('&Iacute;', utf8_encode("Í"));
			normalizarCodificacionEstCheques('&Oacute;', utf8_encode("Ó"));
			normalizarCodificacionEstCheques('&Uacute;',utf8_encode("Ú"));
			normalizarCodificacionEstCheques('&Ntilde;',utf8_encode("Ñ"));
			echo "<p class='mensajeEjecucion'> <span class = 'subtitulo1'>Paso $paso: </span> Se ha migrado la tabla Contabilidad.CuentasCierre </p> ";
	
		}
		
		
		
		
		
	
	
	
	?>
