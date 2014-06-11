	<html>	
		<head>
			<title> Migracion Contabilidad.Comprobantes </title>
			<link rel="stylesheet" type="text/css" href="../General/estilos/estilos.css">
		</head>
	
	
	<?php
		session_start();
		include_once('../General/funciones/funciones.php');
		
		
		
		
		/* Inicia defincion de funciones */
		
			
		function  normalizarCodificacionComprobantes($cadenaBusqueda,$cadenaReemplazo)  {
		// Busca y reemplaza ocurrencias en una tabla
			$cnx= conectar_postgres();
			$cons = "UPDATE Contabilidad.Comprobantes SET compania = replace( compania,'$cadenaBusqueda','$cadenaReemplazo'), comprobante = replace( comprobante,'$cadenaBusqueda','$cadenaReemplazo'), tipocomprobant = replace( tipocomprobant,'$cadenaBusqueda','$cadenaReemplazo')";
			
			$res = @pg_query($cnx , $cons);
				if (!$res)  {
					echo "<p class='error1'> Error en la normalizacion de la codificacion </p>".pg_last_error()."<br>";
					echo "<p class= 'subtitulo1'>Comando SQL </p> <br>".$cons."<br/> <br/> <br/>"; 
				}

		}
		
		
	
		
		
			
			function eliminarComprobantes() {
				$cnx= conectar_postgres();
				$cons= "DELETE FROM Contabilidad.Comprobantes";
				$res = @pg_query($cnx, $cons);
				if (!$res) {
							echo "<p class='error1'> Error de ejecucion </p>".pg_last_error()."<br>";
							echo "<p class= 'subtitulo1'>Comando SQL </p> <br>".$cons."<br/>";
							
						}
					
			}
			
			
			
			function insertarComprobantesCSV(){
				$cnx = conectar_postgres();
				$ruta = $_SERVER['DOCUMENT_ROOT'];
				$cons= "COPY Contabilidad.Comprobantes FROM '$ruta/Migraciones/Contabilidad/Comprobantes/Comprobantes.csv' WITH DELIMITER ';' CSV HEADER;";
				$res =  @pg_query($cons);
				
					if (!$res) {
						echo "<p class='error1'> Error de ejecucion </p>".pg_last_error()."<br>";
						echo "<p class= 'subtitulo1'>Comando SQL </p> <br>".$cons."<br/>";
							
					}
			
			}
			
			
			
			
		
		
		function migrarComprobantes($paso) {
		
			eliminarComprobantes();
			insertarComprobantesCSV();
			normalizarCodificacionComprobantes('&Aacute;', utf8_encode("Á"));			
			normalizarCodificacionComprobantes('&Eacute;', utf8_encode("É"));
			normalizarCodificacionComprobantes('&Iacute;', utf8_encode("Í"));
			normalizarCodificacionComprobantes('&Oacute;', utf8_encode("Ó"));
			normalizarCodificacionComprobantes('&Uacute;',utf8_encode("Ú"));
			normalizarCodificacionComprobantes('&Ntilde;',utf8_encode("Ñ"));
			
			echo "<p class='mensajeEjecucion'> <span class = 'subtitulo1'>Paso $paso: </span> Se ha migrado la tabla Contabilidad.Comprobantes </p> ";
	
		}
		
		
		
		
		
	
	
	
	?>
