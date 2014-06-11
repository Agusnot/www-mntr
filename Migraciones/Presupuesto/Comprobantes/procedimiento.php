	<html>	
		<head>
			<title> Migracion Presupuesto.Comprobantes </title>
			<link rel="stylesheet" type="text/css" href="../General/estilos/estilos.css">
		</head>
	
	
	<?php
		session_start();
		include_once('../General/funciones/funciones.php');
		
		
		
		
		/* Inicia defincion de funciones */
		
			
		function  normalizarCodificacionPresupComp($cadenaBusqueda,$cadenaReemplazo)  {
		// Busca y reemplaza ocurrencias en una tabla
			$cnx= conectar_postgres();
			$cons = "UPDATE Presupuesto.Comprobantes SET Comprobante = replace( comprobante ,'$cadenaBusqueda','$cadenaReemplazo'), tipocomprobant = replace( tipocomprobant ,'$cadenaBusqueda','$cadenaReemplazo'), numeroinicial = replace( numeroinicial ,'$cadenaBusqueda','$cadenaReemplazo'), archivo = replace( archivo ,'$cadenaBusqueda','$cadenaReemplazo'), compania = replace( compania ,'$cadenaBusqueda','$cadenaReemplazo')";
			
			$res = pg_query($cnx , $cons);
				if (!$res)  {
					echo "<p class='error1'> Error en la normalizacion de la codificacion </p>".pg_last_error()."<br>";
					echo "<p class= 'subtitulo1'>Comando SQL </p> <br>".$cons."<br/> <br/> <br/>";
				}

		}
		
				
	
		function llamarRegistrosMySQLPresupComp() {
			// Selecciona los registros MySQL (Origen)
			global $res;
			$cnx = conectar_mysql("Contabilidad");
			$cons = "SELECT *  FROM Contabilidad.Comprobantes";
			$res =  mysql_query($cons);
			return $res; 
		
		}
		
		

		
		function insertarPresupComp($comprobante, $tipocomprobante, $retencion, $numeroinicial, $archivo, $compania) {
		//Realiza la insercion en Postgresql con base en los parametros
			
			$cnx = 	conectar_postgres();
			$cons = "INSERT INTO Presupuesto.Comprobantes (comprobante, tipocomprobant, retencion, numeroinicial, archivo, compania) VALUES ('$comprobante', '$tipocomprobante', $retencion, '$numeroinicial', '$archivo', '$compania')"	;
					 
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
		
		
		function  llenarMatrizPresupComp(){
		// Llena una matriz con el resultado de la consulta MySQL
			
			unset($matriz); 
			global  $matriz;	
			$res = llamarRegistrosMySQLPresupComp();
			$posicion=0;
				
				while ($fila = mysql_fetch_array($res))
				{	
					
					$matriz["comprobante"][$posicion] = $fila["Comprobante"];
					$matriz["tipocomprobante"][$posicion] = $fila["TipoComprobant"];
					$matriz["retencion"][$posicion] = $fila["Retencion"];										
					$matriz["numeroinicial"][$posicion] = $fila["NumeroInicial"];					
					$matriz["archivo"][$posicion] = $fila["Formato"];					
					$matriz["compania"][$posicion] = $fila["Compania"];					
					
									
															
					$posicion++;				
				}
							
				
			}
			

			
			function recorrerMatrizPresupComp()  {
			// Recorre la matriz con los valores MySQL  inserta los valores en Postgresql
			
				global $res,$matriz;
					for($pos=0;$pos < mysql_num_rows($res); $pos++)  {

					$comprobante= $matriz["comprobante"][$pos] ;
					$comprobante = eliminarCaracteresEspeciales($comprobante);
					
					$tipocomprobante= $matriz["tipocomprobante"][$pos] ;
					$tipocomprobante = eliminarCaracteresEspeciales($tipocomprobante);
					
					$retencion= $matriz["retencion"][$pos] ;
					$retencion = eliminarCaracteresEspeciales($retencion);
					
					$numeroinicial= $matriz["numeroinicial"][$pos] ;
					$numeroinicial = eliminarCaracteresEspeciales($numeroinicial);
					
					$archivo= $matriz["archivo"][$pos] ;
					$archivo = eliminarCaracteresEspeciales($archivo);
					
					$compania = $_SESSION["compania"];
					

					
					
					
					
					insertarPresupComp($comprobante, $tipocomprobante, $retencion, $numeroinicial, $archivo, $compania);
					
									
					}
			
			}
			
			function eliminarPresupComp() {
				$cnx= conectar_postgres();
				$cons= "DELETE FROM Presupuesto.Comprobantes";
				$res = @pg_query($cnx, $cons);
				if (!$res) {
							echo "<p class='error1'> Error de ejecucion </p>".pg_last_error()."<br>";
							echo "<p class= 'subtitulo1'>Comando SQL </p> <br>".$cons."<br/>";
							
						}
					
			}
			
			
			
			
		
		
		function migrarPresupComp($paso) {
		
			
			llamarRegistrosMySQLPresupComp();
			llenarMatrizPresupComp();
			recorrerMatrizPresupComp();
			normalizarCodificacionPresupComp('&Aacute;', utf8_encode("Á"));			
			normalizarCodificacionPresupComp('&Eacute;', utf8_encode("É"));
			normalizarCodificacionPresupComp('&Iacute;', utf8_encode("Í"));
			normalizarCodificacionPresupComp('&Oacute;', utf8_encode("Ó"));
			normalizarCodificacionPresupComp('&Uacute;',utf8_encode("Ú"));
			normalizarCodificacionPresupComp('&Ntilde;',utf8_encode("Ñ"));
			echo "<p class='mensajeEjecucion'> <span class = 'subtitulo1'>Paso $paso: </span> Se ha migrado la tabla Presupuesto.Comprobantes </p> ";
	
		}
		
		
		
		
		
	
	
	
	?>
