	<html>	
		<head>
			<title> Migracion Presupuesto.TiposComprobante </title>
			<link rel="stylesheet" type="text/css" href="../General/estilos/estilos.css">
		</head>
	
	
	<?php
		session_start();
		include_once('../General/funciones/funciones.php');
		
		
		
		
		/* Inicia defincion de funciones */
		
			
		function  normalizarCodificacionPresupTipoComp($cadenaBusqueda,$cadenaReemplazo)  {
		// Busca y reemplaza ocurrencias en una tabla
			$cnx= conectar_postgres();
			$cons = "UPDATE Presupuesto.TiposComprobante SET tipo = replace( tipo,'$cadenaBusqueda','$cadenaReemplazo')";
			
			$res = pg_query($cnx , $cons);
				if (!$res)  {
					echo "<p class='error1'> Error en la normalizacion de la codificacion </p>".pg_last_error()."<br>";
					echo "<p class= 'subtitulo1'>Comando SQL </p> <br>".$cons."<br/> <br/> <br/>";
				}

		}
		
				
	
		function llamarRegistrosMySQLPresupTipoComp() {
			// Selecciona los registros MySQL (Origen)
			global $res;
			$cnx = conectar_mysql("Contabilidad");
			$cons = "SELECT *  FROM Contabilidad.TiposComprobante";
			$res =  mysql_query($cons);
			return $res; 
		
		}
		
		

		
		function insertarPresupTipoComp($tipo) {
		//Realiza la insercion en Postgresql con base en los parametros
			
			$cnx = 	conectar_postgres();
			$cons = "INSERT INTO Presupuesto.TiposComprobante (tipo) VALUES ('$tipo')"	;
					 
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
		
		
		function  llenarMatrizPresupTipoComp(){
		// Llena una matriz con el resultado de la consulta MySQL
			
			unset($matriz); 
			global  $matriz;	
			$res = llamarRegistrosMySQLPresupTipoComp();
			$posicion=0;
				
				while ($fila = mysql_fetch_array($res))
				{	
					
					$matriz["tipo"][$posicion] = $fila["Tipo"];
															
					$posicion++;				
				}
							
				
			}
			

			
			function recorrerMatrizPresupTipoComp()  {
			// Recorre la matriz con los valores MySQL  inserta los valores en Postgresql
			
				global $res,$matriz;
					for($pos=0;$pos < mysql_num_rows($res); $pos++)  {

					$tipo= $matriz["tipo"][$pos] ;
					$tipo = eliminarCaracteresEspeciales($tipo);
					
					
					insertarPresupTipoComp($tipo);
					
									
					}
			
			}
			
			function eliminarPresupTipoComp() {
				$cnx= conectar_postgres();
				$cons= "DELETE FROM Presupuesto.TiposComprobante";
				$res = @pg_query($cnx, $cons);
				if (!$res) {
							echo "<p class='error1'> Error de ejecucion </p>".pg_last_error()."<br>";
							echo "<p class= 'subtitulo1'>Comando SQL </p> <br>".$cons."<br/>";
							
						}
					
			}
			
			
			
			
		
		
		function migrarPresupTipoComp($paso) {
		
			
			llamarRegistrosMySQLPresupTipoComp();
			llenarMatrizPresupTipoComp();
			recorrerMatrizPresupTipoComp();
			normalizarCodificacionPresupTipoComp('&Aacute;', utf8_encode("Á"));			
			normalizarCodificacionPresupTipoComp('&Eacute;', utf8_encode("É"));
			normalizarCodificacionPresupTipoComp('&Iacute;', utf8_encode("Í"));
			normalizarCodificacionPresupTipoComp('&Oacute;', utf8_encode("Ó"));
			normalizarCodificacionPresupTipoComp('&Uacute;',utf8_encode("Ú"));
			normalizarCodificacionPresupTipoComp('&Ntilde;',utf8_encode("Ñ"));
			echo "<p class='mensajeEjecucion'> <span class = 'subtitulo1'>Paso $paso: </span> Se ha migrado la tabla Presupuesto.TiposComprobante </p> ";
	
		}
		
		
		
		
		
	
	
	
	?>
