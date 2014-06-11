	<html>	
		<head>
			<title> Migracion Contabilidad.CruzarComprobantes </title>
			<link rel="stylesheet" type="text/css" href="../General/estilos/estilos.css">
		</head>
	
	
	<?php
		session_start();
		include_once('../General/funciones/funciones.php');
		
		
		
		
		/* Inicia defincion de funciones */
		
			
		function  normalizarCodificacionCruzarComprobantes($cadenaBusqueda,$cadenaReemplazo)  {
		// Busca y reemplaza ocurrencias en una tabla
			$cnx= conectar_postgres();
			$cons = "UPDATE Contabilidad.CruzarComprobantes SET compania = replace( compania,'$cadenaBusqueda','$cadenaReemplazo'), comprobante = replace( comprobante,'$cadenaBusqueda','$cadenaReemplazo'), cruzarcon = replace( cruzarcon,'$cadenaBusqueda','$cadenaReemplazo')";
			
			$res = @pg_query($cnx , $cons);
				if (!$res)  {
					echo "<p class='error1'> Error en la normalizacion de la codificacion </p>".pg_last_error()."<br>";
					echo "<p class= 'subtitulo1'>Comando SQL </p> <br>".$cons."<br/> <br/> <br/>"; 
				}

		}
		
		
	
		
		
			
			function eliminarCruzarComprobantes() {
				$cnx= conectar_postgres();
				$cons= "DELETE FROM Contabilidad.CruzarComprobantes";
				$res = @pg_query($cnx, $cons);
				if (!$res) {
							echo "<p class='error1'> Error de ejecucion </p>".pg_last_error()."<br>";
							echo "<p class= 'subtitulo1'>Comando SQL </p> <br>".$cons."<br/>";
							
				}
					
			}
			
			
			function insertarCruzarComprobantesCSV(){
				$cnx = conectar_postgres();
				$ruta = $_SERVER['DOCUMENT_ROOT'];
				$cons= "COPY Contabilidad.CruzarComprobantes FROM '$ruta/Migraciones/Contabilidad/CruzarComprobantes/CruzarComprobantes.csv' WITH DELIMITER ';' CSV HEADER;";
				$res =  @pg_query($cons);
				
					if (!$res) {
						echo "<p class='error1'> Error de ejecucion </p>".pg_last_error()."<br>";
						echo "<p class= 'subtitulo1'>Comando SQL </p> <br>".$cons."<br/>";
							
					}
			
			}
			
			function actualizarCruzarComprobante($actualizacion, $condicion) {

				$cnx = 	conectar_postgres();
				$cons = "UPDATE Contabilidad.CruzarComprobantes SET Movimiento = '$actualizacion' WHERE Movimiento  = '$condicion' "	;
						 
				$res = @pg_query($cnx, $cons);
					if (!$res) {
						echo  "<p class='error1'> Error de ejecucion </p>".pg_last_error()."<br>";
						echo "<p class= 'subtitulo1'>Comando SQL </p> <br>".$consUTF8."<br/> <br/> <br/>";
					}
	
			}
			
			
			
			
		
		
		function migrarCruzarComprobantes($paso) {
		
			eliminarCruzarComprobantes();
			insertarCruzarComprobantesCSV();
			normalizarCodificacionCruzarComprobantes('&Aacute;', utf8_encode("Á"));			
			normalizarCodificacionCruzarComprobantes('&Eacute;', utf8_encode("É"));
			normalizarCodificacionCruzarComprobantes('&Iacute;', utf8_encode("Í"));
			normalizarCodificacionCruzarComprobantes('&Oacute;', utf8_encode("Ó"));
			normalizarCodificacionCruzarComprobantes('&Uacute;',utf8_encode("Ú"));
			normalizarCodificacionCruzarComprobantes('&Ntilde;',utf8_encode("Ñ"));
			
			echo "<p class='mensajeEjecucion'> <span class = 'subtitulo1'>Paso $paso: </span> Se ha migrado la tabla Contabilidad.CruzarComprobantes </p> ";
	
		}
		
		
		
		
		
	
	
	
	?>
