	<html>	
		<head>
			<title> Migracion Consumo.Bodegas </title>
			<link rel="stylesheet" type="text/css" href="../General/estilos/estilos.css">
		</head>
	
	
	<?php
	
		include_once('../General/funciones/funciones.php');
		
		
		
		
		/* Inicia defincion de funciones */
		
			
		function  normalizarCodificacion($cadenaBusqueda,$cadenaReemplazo)  {
		// Busca y reemplaza ocurrencias en una tabla
			$cnx= conectar_postgres();
			$cons = "UPDATE Consumo.bodegas SET bodega = replace( bodega,'". $cadenaBusqueda."'".",'". $cadenaReemplazo."'),almacenppal = replace( almacenppal,'". $cadenaBusqueda."'".",'". $cadenaReemplazo."'),compania = replace( compania,'". $cadenaBusqueda."'".",'". $cadenaReemplazo."')";
			
			$res = pg_query($cnx , $cons);
				if (!$res)  {
					echo "<p class='error1'> Error en la normalizacion de la codificacion </p>".pg_last_error()."<br>";
				}

		}
		
				
	
		function llamarRegistrosMySQL() {
			// Selecciona los registros MySQL (Origen)
			global $res;
			$cnx = conectar_mysql("Suministros");
			$cons = "SELECT *  FROM Suministros.bodegas";
			$res =  mysql_query($cons);
			return $res; 
		
		}
		
		

		
	function insertarBodega($bodega, $almacenppal, $compania) {
		//Realiza la insercion en Postgresql con base en los parametros
			
			$cnx = 	conectar_postgres();
			$cons = "INSERT INTO Consumo.Bodegas (bodega, almacenppal, compania ) VALUES ('".$bodega."','".$almacenppal."','".$compania."')"	;
					 
			$cons = str_replace( "'NULL'","NULL",$cons  );	
			$res = @pg_query($cnx, $cons);
				if (!$res) {
					$consUTF8 = utf8_encode($cons);
					$resUTF8 = @pg_query($cnx, $consUTF8);					
						if (!$resUTF8) {
							echo "<p class='error1'> Error de ejecucion </p>".pg_last_error()."<br>";
							echo "<p class= 'subtitulo1'>Comando SQL </p> <br>".$consUTF8."<br/> <br/> <br/>";
							
							
							
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
					
					$matriz["bodega"][$posicion] = $fila["Bodega"];
					$posicion++;				
				}
							
				
			}
			

			
			function recorrerMatriz()  {
			// Recorre la matriz con los valores MySQL  inserta los valores en Postgresql
			
				global $res,$matriz;
					for($pos=0;$pos < mysql_num_rows($res); $pos++)  {

					$bodega= $matriz["bodega"][$pos] ;
					$bodega = eliminarCaracteresEspeciales($bodega);
					$almacenppal="SUMINISTROS";
					$compania= "CLINICA SAN JUAN DE DIOS";
					
					insertarBodega($bodega, $almacenppal, $compania);
					
									
					}
			
			}
			
			function eliminarBodegas() {
				$cnx= conectar_postgres();
				$cons= "DELETE FROM Consumo.Bodegas";
				$res = @pg_query($cnx, $cons);
				if (!$res) {
							echo "<p class='error1'> Error de ejecucion </p>".pg_last_error()."<br>";
							echo "<p class= 'subtitulo1'>Comando SQL </p> <br>".$cons."<br/>";
							
						}
					
			}
			
			
			
			
		
		
		function migrarBodegas($paso) {
		
			eliminarBodegas();
			llamarRegistrosMySQL();
			llenarMatriz();
			recorrerMatriz();
			normalizarCodificacion('&Aacute;', utf8_encode("Á"));			
			normalizarCodificacion('&Eacute;', utf8_encode("É"));
			normalizarCodificacion('&Iacute;', utf8_encode("Í"));
			normalizarCodificacion('&Oacute;', utf8_encode("Ó"));
			normalizarCodificacion('&Uacute;',utf8_encode("Ú"));
			normalizarCodificacion('&Ntilde;',utf8_encode("Ñ"));
			echo "<p class='mensajeEjecucion'> <span class = 'subtitulo1'>Paso $paso: </span> Se ha migrado la tabla Consumo.Bodegas </p> ";
	
		}
		
		
		
		
		
	
	
	
	?>
