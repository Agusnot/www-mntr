	<html>
		<head>
			<title> Migracion HistoriaClinica.EmpleosOIT </title> 
			<link rel="stylesheet" type="text/css" href="../../General/estilos/estilos.css">
			<meta charset="UTF-8">
		</head>
	</html>	
	


<?php

	session_start();
	include_once('../General/funciones/funciones.php');

	/* Inicia la definicion de funciones */
		
		
		
		function eliminarEmpleosOIT() {
		
			$cnx = conectar_postgres();
			$compania = $_SESSION["compania"];
			$cons= "DELETE FROM  HistoriaClinica.EmpleosOIT";
			$res =  @pg_query($cons);
				if (!$res) {
								echo "<p class='error1'> Error de ejecucion </p>".pg_last_error()."<br>";
								echo "<p class= 'subtitulo1'>Comando SQL </p> <br>".$cons."<br/>";
				}
		}
		
		
		
			
		function normalizarCodificacionEmpleosOIT($cadenaBusqueda, $cadenaReemplazo)
		
		{
			$cnx = conectar_postgres();
			$cons= "UPDATE HistoriaClinica.EmpleosOIT SET Empleo = replace( Empleo, '$cadenaBusqueda' , '$cadenaReemplazo') ,compania = replace( compania, '$cadenaBusqueda' ,  '$cadenaReemplazo')";
			$res = pg_query($cnx, $cons);
							
				if (!$res) {
					echo "<p class='error1'> Error de ejecucion </p>".pg_last_error()."<br>";
					echo "<p class= 'subtitulo1'>Comando SQL </p> <br>".$cons."<br>"; 	
				}
		
		}
			
		function insertarEmpleosOIT() {
		
			$cnx = conectar_postgres();
			$compania = $_SESSION["compania"];
			$ruta = $_SERVER['DOCUMENT_ROOT'];
			$cons= "COPY HistoriaClinica.EmpleosOIT  FROM '$ruta/Migraciones/HistoriaClinica/EmpleosOIT/EmpleosOIT.csv' WITH DELIMITER ';' CSV HEADER;";
			
			$res =  @pg_query($cons);
				if (!$res) {
								echo "<p class='error1'> Error de ejecucion </p>".pg_last_error()."<br>";
								echo "<p class= 'subtitulo1'>Comando SQL </p> <br>".$cons."<br/>";
				}
		}
		
		
		function migrarEmpleosOIT(){
			
			eliminarEmpleosOIT();
			insertarEmpleosOIT();		
			
			
			// Tabla HistoriaClinica.EmpleosOIT
			normalizarCodificacionEmpleosOIT('&Aacute&', utf8_encode("�"));			
			normalizarCodificacionEmpleosOIT('&Eacute&', utf8_encode("�"));
			normalizarCodificacionEmpleosOIT('&Iacute&', utf8_encode("�"));
			normalizarCodificacionEmpleosOIT('&Oacute&', utf8_encode("�"));
			normalizarCodificacionEmpleosOIT('&Uacute&',utf8_encode("�"));
			normalizarCodificacionEmpleosOIT('&Ntilde&',utf8_encode("�"));
			
		}	
			
		
	
		/* Finaliza la definicion de funciones*/	
		
		
		
		
		
		
		
			
			

						
			
?>