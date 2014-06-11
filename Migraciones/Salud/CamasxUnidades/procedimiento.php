	<html>	
		<head>
			<title> Migracion Salud.CamasxUnidades </title>
			<link rel="stylesheet" type="text/css" href="../General/estilos/estilos.css">
		</head>
	
	
	<?php
		session_start();
		include_once('../General/funciones/funciones.php');
		
		
		
		
		/* Inicia defincion de funciones */
		
			
		function  normalizarCodificacionCamasxUnidades($cadenaBusqueda,$cadenaReemplazo)  {
		// Busca y reemplaza ocurrencias en una tabla
			$cnx= conectar_postgres();
			$cons = "UPDATE Salud.CamasxUnidades SET compania = replace( compania,'$cadenaBusqueda','$cadenaReemplazo'),  ambito = replace( ambito,'$cadenaBusqueda','$cadenaReemplazo') , unidad = replace( unidad,'$cadenaBusqueda','$cadenaReemplazo') ";
			
			$res = pg_query($cnx , $cons);
				if (!$res)  {
					echo "<p class='error1'> Error en la normalizacion de la codificacion </p>".pg_last_error()."<br>";
					echo"<p class= 'subtitulo1'>Comando SQL </p> <br>".$cons."<br/> <br/> <br/>";  
				}

		}
		
		
	
		function llamarRegistrosCamasxUnidades() {
			
			global $res;
			$cnx = conectar_postgres();
			$cons = "SELECT *  FROM Salud.Pabellones";
			$res =  pg_query($cnx, $cons);
			return $res; 
		
		}
		
		

		
		function insertarCamasxPabellones($compania, $ambito, $unidad, $idcama, $nombre, $detalle, $estado) {
		//Realiza la insercion en Postgresql con base en los parametros
			
			$cnx = 	conectar_postgres();
			$cons = "INSERT INTO Salud.CamasxUnidades ( compania, ambito, unidad, idcama, nombre, detalle, estado ) VALUES ('$compania', '$ambito', '$unidad', '$idcama', '$nombre', '$detalle', '$estado')"	;
			
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
		
		
		function  llenarMatrizCamasxPabellones(){
		// Llena una matriz con el resultado de la consulta MySQL
			
			unset($matriz); 
			global  $matriz;	
			$res = llamarRegistrosCamasxUnidades();
			$posicion=0;
				
				while ($fila = pg_fetch_array($res))
				{	
					
					$matriz["pabellon"][$posicion] = $fila["pabellon"];
					$matriz["nocamas"][$posicion] = $fila["nocamas"];
					$matriz["ambito"][$posicion] = $fila["ambito"];
					
					$posicion++;				
				}
							
				
			}
			

			
			function recorrerMatrizCamasxUnidades()  {
			// Recorre la matriz con los valores MySQL  inserta los valores en Postgresql
			
				global $res,$matriz;
					for($pos=0;$pos < pg_num_rows($res); $pos++)  {

						$ambito= $matriz["ambito"][$pos] ;
						//$ambito = eliminarCaracteresEspeciales($ambito);
						$nocamas= $matriz["nocamas"][$pos] ;
						$pabellon= $matriz["pabellon"][$pos] ;
						//$pabellon = eliminarCaracteresEspeciales($pabellon);
						$compania = $_SESSION["compania"];
						$estado = "AC";
						$unidad = $pabellon;
						
						
						$posicion = 1;
							while ($posicion <= $nocamas){
								
								$idcama = $posicion;
								$nombre = "Cama ".$idcama;
								$detalle = $nombre." ".$unidad;
								insertarCamasxPabellones($compania, $ambito , $unidad, $idcama, $nombre, $detalle, $estado);
								$posicion++;	
							}
							
					
					
					
					
									
					}
			
			}
			
			function eliminarCamasxUnidades() {
				$cnx= conectar_postgres();
				$cons= "DELETE FROM Salud.CamasxUnidades";
				$res = @pg_query($cnx, $cons);
				if (!$res) {
							echo "<p class='error1'> Error de ejecucion </p>".pg_last_error()."<br>";
							echo "<p class= 'subtitulo1'>Comando SQL </p> <br>".$cons."<br/>";
							
						}
					
			}
			
			
			
			
		
		
		function migrarCamasxUnidades($paso) {
		
			
			eliminarCamasxUnidades();
			llamarRegistrosCamasxUnidades();
			llenarMatrizCamasxPabellones();
			recorrerMatrizCamasxUnidades();
			
			
			
			//Tabla Salud.Medicos
			normalizarCodificacionCamasxUnidades('&Aacute;', utf8_encode("Á"));			
			normalizarCodificacionCamasxUnidades('&Eacute;', utf8_encode("É"));
			normalizarCodificacionCamasxUnidades('&Iacute;', utf8_encode("Í"));
			normalizarCodificacionCamasxUnidades('&Oacute;', utf8_encode("Ó"));
			normalizarCodificacionCamasxUnidades('&Uacute;',utf8_encode("Ú"));
			normalizarCodificacionCamasxUnidades('&Ntilde;',utf8_encode("Ñ"));
			echo "<p class='mensajeEjecucion'> <span class = 'subtitulo1'>Paso $paso: </span> Se ha migrado la tabla Salud.CamasxUnidades </p> ";
			
	
		}
		
		
		
		
		
	
	
	
	?>
