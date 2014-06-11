	<html>
		<head>
			<title> Migracion Salud.Cargos </title> 
			<link rel="stylesheet" type="text/css" href="../../General/estilos/estilos.css">
			<meta charset="UTF-8">
		</head>
	</html>	
	


<?php


	include_once('../General/funciones/funciones.php');

	/* Inicia la definicion de funciones */
		
			
		function eliminarCargos() {
		
			$cnx = conectar_postgres();			
			$cons= "DELETE FROM   Salud.Cargos ";
			$res =  @pg_query($cons);
				if (!$res) {
								echo "<p class='error1'> Error de ejecucion </p>".pg_last_error()."<br>";
								echo "<p class= 'subtitulo1'>Comando SQL </p> <br>".$cons."<br/>";
				}
		}
		
		
		function normalizarCodificacionCargos($cadenaBusqueda, $cadenaReemplazo)
		
		{
			$cnx = conectar_postgres();
			$cons= "UPDATE Salud.Cargos SET Cargos = replace( Cargos, '$cadenaBusqueda' , '$cadenaReemplazo') ,compania = replace( compania, '$cadenaBusqueda' ,  '$cadenaReemplazo')";
			$res = pg_query($cnx, $cons);
							
				if (!$res) {
					echo "<p class='error1'> Error de ejecucion </p>".pg_last_error()."<br>";
					echo "<p class= 'subtitulo1'>Comando SQL </p> <br>".$cons."<br>"; 	
				}
		
		}
	
			
		function insertarCargos() {
		
			$cnx = conectar_postgres();
			$ruta = $_SERVER['DOCUMENT_ROOT'];
			$cons= "COPY Salud.Cargos  FROM '$ruta/Migraciones/Salud/Cargos/Cargos.csv' WITH DELIMITER ';' CSV HEADER;";
			$res =  @pg_query($cons);
				if (!$res) {
								echo "<p class='error1'> Error de ejecucion </p>".pg_last_error()."<br>";
								echo "<p class= 'subtitulo1'>Comando SQL </p> <br>".$cons."<br/>";
				}
		}
		
		
		function cambiarCampoCargo() {
		
			$cnx = conectar_postgres();
			$cons = "ALTER TABLE Salud.Cargos  ALTER COLUMN Cargos TYPE character varying(50)";
			$res =  @pg_query($cnx, $cons);
				if (!$res) {
								echo "<p class='error1'> Error de ejecucion </p>".pg_last_error()."<br>";
								echo "<p class= 'subtitulo1'>Comando SQL </p> <br>".$cons."<br/>";
				}
			
			
		}
		
		
		/* Finaliza la definicion de funciones*/	
		
		
		
		
		/* Inicia la ejecucion de la migracion */
		function migrarCargos($paso) {
			
			cambiarCampoCargo();
			eliminarCargos();
			insertarCargos();
			normalizarCodificacionCargos('&Aacute&', utf8_encode("Á"));			
			normalizarCodificacionCargos('&Eacute&', utf8_encode("É"));
			normalizarCodificacionCargos('&Iacute&', utf8_encode("Í"));
			normalizarCodificacionCargos('&Oacute&', utf8_encode("Ó"));
			normalizarCodificacionCargos('&Uacute&',utf8_encode("Ú"));
			normalizarCodificacionCargos('&Ntilde&',utf8_encode("Ñ"));
			
			
			echo "<p class='mensajeEjecucion'> <span class = 'subtitulo1'>Paso $paso: </span> Se ha migrado la tabla Salud.Cargos </p> ";
	
		}	
		
			
			

						
			
?>