	<html>	
		<head>
			<title> Migracion Contabilidad.Comprobantes </title>
			<link rel="stylesheet" type="text/css" href="../General/estilos/estilos.css">
		</head>
	
	
	<?php
		session_start();
		include_once('../General/funciones/funciones.php');
		
		
		
		
		/* Inicia defincion de funciones */
		
			
		function  normalizarCodificacionComp($cadenaBusqueda,$cadenaReemplazo)  {
		// Busca y reemplaza ocurrencias en una tabla
			$cnx= conectar_postgres();
			$cons = "UPDATE Contabilidad.Comprobantes SET comprobante = replace( comprobante ,'$cadenaBusqueda','$cadenaReemplazo'), tipocomprobant = replace( tipocomprobant ,'$cadenaBusqueda','$cadenaReemplazo'), numeroinicial = replace( numeroinicial ,'$cadenaBusqueda','$cadenaReemplazo'), formato = replace( formato ,'$cadenaBusqueda','$cadenaReemplazo'), compania = replace( compania ,'$cadenaBusqueda','$cadenaReemplazo'), comppresupuesto = replace( comppresupuesto ,'$cadenaBusqueda','$cadenaReemplazo'), comppresupuestoadc = replace( comppresupuestoadc ,'$cadenaBusqueda','$cadenaReemplazo'),  cierre = replace( cierre ,'$cadenaBusqueda','$cadenaReemplazo'),  formatoadc = replace( formatoadc ,'$cadenaBusqueda','$cadenaReemplazo')";
			
			$res = pg_query($cnx , $cons);
				if (!$res)  {
					echo "<p class='error1'> Error en la normalizacion de la codificacion </p>".pg_last_error()."<br>";
					echo "<p class= 'subtitulo1'>Comando SQL </p> <br>".$cons."<br/> <br/> <br/>";
				}

		}
		
				
	
		function llamarRegistrosMySQLComp() {
			// Selecciona los registros MySQL (Origen)
			global $res;
			$cnx = conectar_mysql("Contabilidad");
			$cons = "SELECT *  FROM Contabilidad.Comprobantes";
			$res =  mysql_query($cons);
			return $res; 
		
		}
		
		

		
		function insertarComp($comprobante, $tipocomprobante, $retencion, $numeroinicial, $formato, $compania) {
		//Realiza la insercion en Postgresql con base en los parametros
			
			$cnx = 	conectar_postgres();
			$cons = "INSERT INTO Contabilidad.Comprobantes (comprobante, tipocomprobant, retencion, numeroinicial, formato, compania) VALUES ('$comprobante', '$tipocomprobante', $retencion, '$numeroinicial', '$formato', '$compania')"	;
					 
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
		
		
		function  llenarMatrizComp(){
		// Llena una matriz con el resultado de la consulta MySQL
			
			unset($matriz); 
			global  $matriz;	
			$res = llamarRegistrosMySQLComp();
			$posicion=0;
				
				while ($fila = mysql_fetch_array($res))
				{	
					
					$matriz["comprobante"][$posicion] = $fila["Comprobante"];
					$matriz["tipocomprobante"][$posicion] = $fila["TipoComprobant"];
					$matriz["retencion"][$posicion] = $fila["Retencion"];										
					$matriz["numeroinicial"][$posicion] = $fila["NumeroInicial"];					
					$matriz["formato"][$posicion] = $fila["Formato"];					
					$matriz["compania"][$posicion] = $fila["Compania"];					
					
									
															
					$posicion++;				
				}
							
				
			}
			

			
			function recorrerMatrizComp()  {
			// Recorre la matriz con los valores MySQL  inserta los valores en Postgresql
			
				global $res,$matriz;
					for($pos=0;$pos < mysql_num_rows($res); $pos++)  {

					$comprobante= $matriz["comprobante"][$pos] ;
					$comprobante = normalizarComprobantesContabilidad($comprobante);
					
					$tipocomprobante= $matriz["tipocomprobante"][$pos] ;
					$tipocomprobante = eliminarCaracteresEspeciales($tipocomprobante);
						if ($comprobante == "NOTAS DEBITO"){
							$tipocomprobante = "EGRESO";
						}
					
					
					$retencion= $matriz["retencion"][$pos] ;
					$retencion = eliminarCaracteresEspeciales($retencion);
					
					$numeroinicial= $matriz["numeroinicial"][$pos] ;
					$numeroinicial = eliminarCaracteresEspeciales($numeroinicial);
					
					$formato= "Formatos/ComprobanteFrm1.php"; 
					
					if (strtoupper($comprobante) == "EGRESO") {
						$formato = "Formatos/ComprobanteEgresoSinFirmas.php";	
					}
					//$formato = eliminarCaracteresEspeciales($formato);
					
					$compania = $_SESSION["compania"];
					
					insertarComp($comprobante, $tipocomprobante, $retencion, $numeroinicial, $formato, $compania);
					
									
					}
			
			}
			
			function eliminarComp() {
				$cnx= conectar_postgres();
				$cons= "DELETE FROM Contabilidad.Comprobantes";
				$res = @pg_query($cnx, $cons);
				if (!$res) {
							echo "<p class='error1'> Error de ejecucion </p>".pg_last_error()."<br>";
							echo "<p class= 'subtitulo1'>Comando SQL </p> <br>".$cons."<br/>";
							
						}
					
			}
			
			
			
		function insertarComprobanteDefecto($comprobante, $tipocomprobante, $retencion, $numeroinicial, $formato, $compania) {
			//Realiza la insercion en Postgresql con base en los parametros
			
			$cnx = 	conectar_postgres();
			$cons = "INSERT INTO Contabilidad.Comprobantes (comprobante, tipocomprobant, retencion, numeroinicial, formato, compania) VALUES ('$comprobante', '$tipocomprobante', $retencion, '$numeroinicial', '$formato', '$compania')"	;
					 
			$cons = str_replace( "'NULL'","NULL",$cons  );	
			$res = @pg_query($cnx, $cons);
				if (!$res) {
					
							echo  "<p class='error1'> Error de ejecucion </p>".pg_last_error()."<br>";
							echo "<p class= 'subtitulo1'>Comando SQL </p> <br>".$consUTF8."<br/> <br/> <br/>";

				}

		}
		
		
		
		
		
		
			
		
			
			
			
			
		
		
		function migrarComp($paso) {
			

			
			llamarRegistrosMySQLComp();
			llenarMatrizComp();
			recorrerMatrizComp();
			normalizarCodificacionComp('&Aacute;', utf8_encode("Á"));			
			normalizarCodificacionComp('&Eacute;', utf8_encode("É"));
			normalizarCodificacionComp('&Iacute;', utf8_encode("Í"));
			normalizarCodificacionComp('&Oacute;', utf8_encode("Ó"));
			normalizarCodificacionComp('&Uacute;',utf8_encode("Ú"));
			normalizarCodificacionComp('&Ntilde;',utf8_encode("Ñ"));
			insertarComprobanteDefecto("POR VALIDAR", "POR VALIDAR", 0, 1, "impcomprobante.php", $_SESSION["compania"]);
			echo "<p class='mensajeEjecucion'> <span class = 'subtitulo1'>Paso $paso: </span> Se ha migrado la tabla Contabilidad.Comprobantes </p> ";
	
		}
		
		
		
		
		
	
	
	
	?>
