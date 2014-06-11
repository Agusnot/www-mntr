	<html>	
		<head>
			<title> Migracion Contabilidad.TiposComprobante </title>
			<link rel="stylesheet" type="text/css" href="../General/estilos/estilos.css">
		</head>
	
	
	<?php
		session_start();
		include_once('../General/funciones/funciones.php');
		
		
		
		
		/* Inicia defincion de funciones */
		
			
		function  normalizarCodificacionTipoComp($cadenaBusqueda,$cadenaReemplazo)  {
		// Busca y reemplaza ocurrencias en una tabla
			$cnx= conectar_postgres();
			$cons = "UPDATE Contabilidad.TiposComprobante SET tipo = replace( tipo,'$cadenaBusqueda','$cadenaReemplazo')";
			
			$res = pg_query($cnx , $cons);
				if (!$res)  {
					echo "<p class='error1'> Error en la normalizacion de la codificacion </p>".pg_last_error()."<br>";
					echo "<p class= 'subtitulo1'>Comando SQL </p> <br>".$cons."<br/> <br/> <br/>";
				}

		}
		
				
	
		function llamarRegistrosMySQLTipoComp() {
			// Selecciona los registros MySQL (Origen)
			global $res;
			$cnx = conectar_mysql("Contabilidad");
			$cons = "SELECT *  FROM Contabilidad.TiposComprobante";
			$res =  mysql_query($cons);
			return $res; 
		
		}
		
		

		
		function insertarTipoComp($tipo) {
		//Realiza la insercion en Postgresql con base en los parametros
			
			$cnx = 	conectar_postgres();
			$cons = "INSERT INTO Contabilidad.TiposComprobante (tipo) VALUES ('$tipo')"	;
					 
			$cons = str_replace( "'NULL'","NULL",$cons  );	
			$res = @pg_query($cnx, $cons);
				if (!$res) {
					$consUTF8 = utf8_encode($cons);
					$resUTF8 = @pg_query($cnx, $consUTF8);					
						if (!$resUTF8) {
							echo  "<p class='error1'> Error de ejecucion </p>".pg_last_error()."<br>";
							echo "<p class= 'subtitulo1'>Comando SQL </p> <br>".$consUTF8."<br/> <br/> <br/>";
							
							
						}
				
				}

				
		}
		
		
		function  llenarMatrizTipoComp(){
		// Llena una matriz con el resultado de la consulta MySQL
			
			unset($matriz); 
			global  $matriz;	
			$res = llamarRegistrosMySQLTipoComp();
			$posicion=0;
				
				while ($fila = mysql_fetch_array($res))
				{	
					
					$matriz["tipo"][$posicion] = $fila["Tipo"];
															
					$posicion++;				
				}
							
				
			}
			

			
			function recorrerMatrizTipoComp()  {
			// Recorre la matriz con los valores MySQL  inserta los valores en Postgresql
			
				global $res,$matriz;
					for($pos=0;$pos < mysql_num_rows($res); $pos++)  {

					$tipo= $matriz["tipo"][$pos] ;
					$tipo = eliminarCaracteresEspeciales($tipo);
					
					
					insertarTipoComp($tipo);
					
									
					}
			
			}
			
			function eliminarTipoComp() {
				$cnx= conectar_postgres();
				$cons= "DELETE FROM Contabilidad.TiposComprobante";
				$res = @pg_query($cnx, $cons);
				if (!$res) {
							echo "<p class='error1'> Error de ejecucion </p>".pg_last_error()."<br>";
							echo "<p class= 'subtitulo1'>Comando SQL </p> <br>".$cons."<br/>";
							
						}
					
			}
			
			
			
		function insertarTipoCompDefecto() {
		//Realiza la insercion en Postgresql con base en los parametros
			
			$cnx = 	conectar_postgres();
			$cons = "INSERT INTO Contabilidad.TiposComprobante (tipo) VALUES ('POR VALIDAR')"	;
					 
			$cons = str_replace( "'NULL'","NULL",$cons  );	
			$res = @pg_query($cnx, $cons);
				if (!$res) {
					echo  "<p class='error1'> Error de ejecucion </p>".pg_last_error()."<br>";
					echo "<p class= 'subtitulo1'>Comando SQL </p> <br>".$consUTF8."<br/> <br/> <br/>";
				}
	
		}
		
		function actualizarTiposComprobante($actualizacion, $condicion) {

			$cnx = 	conectar_postgres();
			$cons = "UPDATE Contabilidad.TiposComprobante SET Tipo = '$actualizacion' WHERE Tipo = '$condicion' "	;
					 
			$res = @pg_query($cnx, $cons);
				if (!$res) {
					echo  "<p class='error1'> Error de ejecucion </p>".pg_last_error()."<br>";
					echo "<p class= 'subtitulo1'>Comando SQL </p> <br>".$consUTF8."<br/> <br/> <br/>";
				}
	
		}
		
		
		
		
		
		function migrarTipoComp($paso) {
		
		
				
			
			llamarRegistrosMySQLTipoComp();
			llenarMatrizTipoComp();
			recorrerMatrizTipoComp();
			// Mayusculas
			normalizarCodificacionTipoComp('&Aacute;', utf8_encode("Á"));			
			normalizarCodificacionTipoComp('&Eacute;', utf8_encode("É"));
			normalizarCodificacionTipoComp('&Iacute;', utf8_encode("Í"));
			normalizarCodificacionTipoComp('&Oacute;', utf8_encode("Ó"));
			normalizarCodificacionTipoComp('&Uacute;',utf8_encode("Ú"));
			normalizarCodificacionTipoComp('&Ntilde;',utf8_encode("Ñ"));			
			insertarTipoCompDefecto();
						
			
			
			
			echo "<p class='mensajeEjecucion'> <span class = 'subtitulo1'>Paso $paso: </span> Se ha migrado la tabla Contabilidad.TiposComprobante </p> ";
	
		}
		
		
		
		
		
	
	
	
	?>
