	<html>
		<head>
			<title> Migracion Consumo.Movimiento(Devoluciones) </title> 
			<link rel="stylesheet" type="text/css" href="../../General/estilos/estilos.css">
			<meta charset="UTF-8">
		</head>
	</html>	
	


<?php
	session_start();
	include_once '../../Conexiones/conexion.php';
	include_once('../../General/funciones/funciones.php');
	
	

	/* Inicia la definicion de funciones */
	
	
		
		function contarRegistrosMySQL2011() {
			
			$cnx = conectar_mysql("Salud");
			$cons = "SELECT COUNT(*) AS conteomysql FROM Salud.SalidaMedicamentos2011 WHERE YEAR(FechaDespacho) = '2011' AND Devuelto <> 0 ";
			$res =  mysql_query($cons);
			$fila = mysql_fetch_array($res);
			$res = $fila['conteomysql'];
			return $res; 	
		
		}
		
		
		function contarRegistrosPostgresql2011() {
			
			$cnx= conectar_postgres();
			$cons = "SELECT COUNT(*) AS conteo FROM Consumo.movimiento WHERE almacenppal = 'FARMACIA' AND UPPER(comprobante) = 'DEVOLUCIONES' AND DATE_PART('year', fecha) = '2011' ";
			$res =  pg_query($cnx, $cons);
			$fila = pg_fetch_array($res);
			$res = $fila['conteo'];
			return $res; 	
		
		}
		
		function contarRegistrosPostgresql2011Errores() {
			$cnx= conectar_postgres();
			$cons = "SELECT COUNT(*) AS conteo FROM Consumo.movimientoMigracion4";
			$res =  pg_query($cnx, $cons);
			$fila = pg_fetch_array($res);
			$res = $fila['conteo'];
			return $res; 	
		
		}
		
		function llamarMovimientoMySQL2011() {
			// El limite inicial y final se usan para una inmensa cantidad de registros
			global $res;
			
			$cnx = conectar_mysql("Salud");
			$cons = "SELECT YEAR(FechaDespacho) AS anio, SalidaMedicamentos2011.*  FROM Salud.SalidaMedicamentos2011  WHERE YEAR(FechaDespacho) = '2011' AND Devuelto <> 0 ORDER BY FechaDespacho ASC ";
			
			$res =  mysql_query($cons);
			return $res; 
		
		}
		
		
		function eliminarDevoluciones2011() {
			
			$cnx= conectar_postgres();
			$cons = "DELETE  FROM Consumo.movimiento WHERE almacenppal = 'FARMACIA' AND UPPER(Comprobante) = 'DEVOLUCIONES' AND DATE_PART('year', fecha) = '2011' ";
			$res =  @pg_query($cnx, $cons);
				if (!$res)  {
					echo "<p class='error1'> Error de ejecucion </p>".pg_last_error()."<br>";
					echo "<p class= 'subtitulo1'>Comando SQL </p> <br>".$cons."<br>"; 				
					echo "<br><br>";
				}
		
		}
		
		
		
		function  reemplazarTextoTabla1_2011($cadenaBusqueda,$cadenaReemplazo)  {
			$cnx= conectar_postgres();
			$cons = "UPDATE Consumo.comprobantes SET almacenppal = replace( almacenppal,'$cadenaBusqueda','$cadenaReemplazo'), compania = replace( compania,'$cadenaBusqueda','$cadenaReemplazo') ,  comprobante = replace( comprobante,'$cadenaBusqueda','$cadenaReemplazo'),  tipo = replace( tipo,'$cadenaBusqueda','$cadenaReemplazo')";
			
			$res = pg_query($cnx , $cons);
				if (!$res)  {
					echo "<p class='error1'> Error en la normalizacion de la codificacion </p>".pg_last_error()."<br>";
				}

		}
		
		function  reemplazarTextoTabla2_2011($cadenaBusqueda,$cadenaReemplazo)  {
			$cnx= conectar_postgres();
			$cons = "UPDATE Consumo.tiposcomprobante SET tipo = replace( tipo,'$cadenaBusqueda','$cadenaReemplazo')";
			
			$res = pg_query($cnx , $cons);
				if (!$res)  {
					echo "<p class='error1'> Error en la normalizacion de la codificacion </p>".pg_last_error()."<br>";
				}

		}
		
		function  reemplazarTextoTabla3_2011($cadenaBusqueda,$cadenaReemplazo)  {
			$cnx= conectar_postgres();
			$cons = "UPDATE Consumo.grupos SET grupo = replace( grupo ,'$cadenaBusqueda','$cadenaReemplazo'),  almacenppal = replace( almacenppal ,'$cadenaBusqueda','$cadenaReemplazo'),  compania = replace( compania ,'$cadenaBusqueda','$cadenaReemplazo')";
			
			$res = pg_query($cnx , $cons);
				if (!$res)  {
					echo "<p class='error1'> Error en la normalizacion de la codificacion </p>".pg_last_error()."<br>";
				}

		}
		
		
		function  reemplazarTextoTabla4_2011($cadenaBusqueda,$cadenaReemplazo)  {
			$cnx= conectar_postgres();
			$cons = "UPDATE Consumo.movimiento SET compania = replace( compania,'$cadenaBusqueda','$cadenaReemplazo'), almacenppal = replace( almacenppal,'$cadenaBusqueda','$cadenaReemplazo'), comprobante = replace( comprobante,'$cadenaBusqueda','$cadenaReemplazo'), tipocomprobante = replace( tipocomprobante,'$cadenaBusqueda','$cadenaReemplazo'), centrocosto = replace( centrocosto,'$cadenaBusqueda','$cadenaReemplazo') WHERE almacenppal = 'FARMACIA' AND UPPER(Comprobante) = 'SALIDAS POR PLANTILLA' AND DATE_PART('year',fecha) = '2011'";
			
			$res = @pg_query($cnx , $cons);
				if (!$res)  {
					echo "<p class='error1'> Error en la normalizacion de la codificacion </p>".pg_last_error()."<br>";
				}

		}
		
		
			function  reemplazarTextoTabla5_2011($cadenaBusqueda,$cadenaReemplazo)  {
			$cnx= conectar_postgres();
			$cons = "UPDATE Central.centroscosto SET compania = replace( compania,'$cadenaBusqueda','$cadenaReemplazo'), centrocostos = replace( centrocostos,'$cadenaBusqueda','$cadenaReemplazo')";
			
			$res = pg_query($cnx , $cons);
				if (!$res)  {
					echo "<p class='error1'> Error en la normalizacion de la codificacion </p>".pg_last_error()."<br>";
				}

		}
		
		
		
		
		function consultarCentroCosto2011($centrocosto) {
			$cnx= conectar_postgres();
			$cons = "SELECT codigo  FROM Central.CentrosCosto WHERE UPPER(centrocostos) = '$centrocosto'";
			$res =  @pg_query($cnx, $cons);
				if (!$res) {
					$consUTF8 = utf8_encode($cons);
					$res =  @pg_query($cnx, $consUTF8);	
						if (!$res) {
							$fp = fopen("ReporteDevoluciones", "a+");	
							$errorEjecucion= "<p class='error1'> Error de ejecucion </p>".pg_last_error()."<br>";
							$consulta= "<p class= 'subtitulo1'>Comando SQL </p> <br>".$consUTF8."<br/> <br/> <br/>";  
							fputs($fp, $errorEjecucion);
							fputs($fp, $consulta);
							fclose($fp);			
					
						}
								
			}
				
				
			
			
			if (pg_num_rows($res) == 0){
				return $centrocosto;
			}
			
			if (pg_num_rows($res) > 0){
				$res = pg_fetch_array($res);
				$resultado = $res['codigo'];
				return $resultado;
			}
			
				
			
		} 
		
	
		
	
		
		
		
		
		
		function insertarRegistroMigracion2011($compania,$almacenppal,$fecha,$comprobante,$tipocomprobante,$numero,$cedula,$detalle,$autoid,$usuariocre,$fechacre,$estado,$cantidad,$vrcosto,$totcosto,$vrventa,$totventa,$centrocosto,$idtraslado,$anio,$incluyeiva,$idregistro,$cum,$fechadespacho,$lote, $grupo,  $error) {
			
			$cnx = 	conectar_postgres();
			$cons = "INSERT INTO Consumo.movimientoMigracion4 (compania,almacenppal,fecha,comprobante,tipocomprobante,numero,cedula,detalle,autoid,usuariocre,fechacre,estado,cantidad,vrcosto,totcosto,vrventa,totventa,centrocosto,idtraslado,anio,incluyeiva,idregistro,cum,fechadespacho,lote,grupo, error) VALUES ('$compania','$almacenppal','$fecha','$comprobante','$tipocomprobante','$numero','$cedula','$detalle',$autoid,'$usuariocre','$fechacre','$estado',$cantidad,$vrcosto,$totcosto,$vrventa,$totventa,'$centrocosto',$idtraslado,$anio,$incluyeiva,$idregistro,'$cum','$fechadespacho','$lote', '$grupo', '$error')"	;
					 
			$cons = str_replace( "'NULL'","NULL",$cons  );	
			$res = @pg_query($cnx, $cons);
				if(!$res) {
					$consUTF8 = utf8_encode($cons);
					$resUTF8 = @pg_query($cnx, $consUTF8);
						if (!$resUTF8) {
							
							$fp = fopen("ReporteDevoluciones.html", "a+");	
							$errorEjecucion= "<p class='error1'> Error de ejecucion </p>".pg_last_error()."<br>";
							$consulta= "<p class= 'subtitulo1'>Comando SQL </p> <br>".$consUTF8."<br/> <br/> <br/>";  
							fputs($fp, $errorEjecucion);
							fputs($fp, $consulta);
							fclose($fp);
							
						}
				}	
		}	
		
		
		
		
		
		
		
		function insertarRegistroPostgresql2011($compania,$almacenppal,$fecha,$comprobante,$tipocomprobante,$numero,$cedula,$detalle,$autoid,$usuariocre,$fechacre,$estado,$cantidad,$vrcosto,$totcosto,$vrventa,$totventa,$centrocosto,$idtraslado,$anio,$incluyeiva,$idregistro,$cum,$fechadespacho,$lote, $grupo) {
			
			$cnx = 	conectar_postgres();
			$cons = "INSERT INTO Consumo.movimiento (compania,almacenppal,fecha,comprobante,tipocomprobante,numero,cedula,detalle,autoid,usuariocre,fechacre,estado,cantidad,vrcosto,totcosto,vrventa,totventa,centrocosto,idtraslado,anio,incluyeiva,idregistro,cum,fechadespacho,lote, grupo) VALUES ('$compania','$almacenppal','$fecha','$comprobante','$tipocomprobante','$numero','$cedula','$detalle',$autoid,'$usuariocre','$fechacre','$estado',$cantidad,$vrcosto,$totcosto,$vrventa,$totventa,'$centrocosto',$idtraslado,$anio,$incluyeiva,$idregistro,'$cum','$fechadespacho','$lote' , '$grupo')"	;
					 
			$cons = str_replace( "'NULL'","NULL",$cons  );	
			$res = @pg_query($cnx, $cons);
				if (!$res) {
					$consUTF8 = utf8_encode($cons);
					$resUTF8 = @pg_query($cnx, $consUTF8);					
						if (!$resUTF8) {
							$error = pg_last_error();
							insertarRegistroMigracion2011($compania,$almacenppal,$fecha,$comprobante,$tipocomprobante,$numero,$cedula,$detalle,$autoid,$usuariocre,$fechacre,$estado,$cantidad,$vrcosto,$totcosto,$vrventa,$totventa,$centrocosto,$idtraslado,$anio,$incluyeiva,$idregistro,$cum,$fechadespacho,$lote, $grupo,  $error); 
						}
				
				}

				
		}
		
		
		function  llenarMatriz2011(){
			
			unset($matriz); 
			global  $matriz;	
			$res = llamarMovimientoMySQL2011();
			$posicion=0;
				
				while ($fila = mysql_fetch_array($res))
				{	
					
					$matriz["fechadespacho"][$posicion] = $fila["FechaDespacho"];
					$matriz["cedula"][$posicion] = $fila["Cedula"];
					$matriz["autoid"][$posicion] = $fila["Codigo"];
					$matriz["usuariocre"][$posicion] = $fila["Usuario"];
					$matriz["fechacre"][$posicion] = $fila["FechaCre"];
					$matriz["cantidad"][$posicion] = $fila["Devuelto"];	
					$matriz["vrcosto"][$posicion] = $fila["VrCosto"];	
					$matriz["totcosto"][$posicion] = $fila["TotalCosto"];															
					$matriz["vrventa"][$posicion] = $fila["VrVenta"];
					$matriz["totventa"][$posicion] = $fila["TotalVenta"];																					
					$matriz["anio"][$posicion] = $fila["anio"];	
					$matriz["pabellon"][$posicion] = $fila["Pabellon"];
					$matriz["grupo"][$posicion] = $fila["TipoMd"];
					
																														
					
												
					$posicion++;				
				}
							
				
			}
			

				
		
		
		
			
			function insertarTabla2011()  {
			
				global $res,$matriz;
				$centrocosto = consultarCentroCosto2011("FARMACIA");
					for($pos=0;$pos < mysql_num_rows($res); $pos++)  {
					
					$compania = $_SESSION["compania"];
					$almacenppal = "FARMACIA";
					$fechadespacho =   $matriz["fechadespacho"][$pos] ;
					
					
					
					
						if($fechadespacho == "0000-00-00" or fechadespacho == "0000-00-00 00:00:00")  {
								$fechadespacho = '1900-01-01';//Valor por defecto, ya que dicho campo en la base de datos es NOT NULL 						
						}	
					$fecha = $fechadespacho;
					$fechacre = $fechadespacho;
					
					$comprobante= "Devoluciones";	 
					$tipocomprobante = "Devoluciones";	
					$numero= $pos + 1; 
					$idregistro =  $numero;
					$cedula=	 $matriz["cedula"][$pos] ;					
					$autoid=	 $matriz["autoid"][$pos] ;
					$usuariocre =  $matriz['usuariocre'][$pos] ;
					$usuariocre = eliminarCaracteresEspeciales($usuariocre);
					$estado = "AC";
					
					$pabellon = $matriz['pabellon'][$pos] ;
					$pabellon = eliminarCaracteresEspeciales($pabellon);
					
					$detalle = "DEVOLUCION ".$pabellon;

					$cantidad=	 $matriz["cantidad"][$pos] ;
						if (trim($cantidad) == '') {
							$cantidad = 0;
						}

					$vrcosto=	 $matriz["vrcosto"][$pos] ;
						if(trim($vrcosto) == ''){
							$vrcosto = 0;
						}
					
					$totcosto=	 $matriz["totcosto"][$pos] ;
						if(trim($totcosto) == ''){
							$totcosto = 0;
						}
						
					$vrventa=	 $matriz["vrventa"][$pos] ;
						if(trim($vrventa) == ''){
							$vrventa = 0;
						}
						
					$totventa=	 $matriz["totventa"][$pos] ;
						if(trim($totventa) == ''){
							$totventa = 0;
						}
						
					$idtraslado = 0;
					
					$anio = $matriz["anio"][$pos] ;
					
					$incluyeiva = 0;
					
					$cum = consultarCUM($autoid);
					
					$grupo = $matriz["grupo"][$pos] ;
					$grupo = eliminarCaracteresEspeciales($grupo);
					$grupo = normalizarGruposConsumo($grupo);
					$lote = 'NULL';
										
					insertarRegistroPostgresql2011($compania,$almacenppal,$fecha,$comprobante,$tipocomprobante,$numero,$cedula,$detalle,$autoid,$usuariocre,$fechacre,$estado,$cantidad,$vrcosto,$totcosto,$vrventa,$totventa,$centrocosto,$idtraslado,$anio,$incluyeiva,$idregistro,$cum,$fechadespacho,$lote, $grupo) ;
					
						
					}
			
	
			}
			
			
			function migrarDevoluciones2011(){
				eliminarDevoluciones2011();
				
				
				/*
				// Tabla Consumo.Comprobantes 
				reemplazarTextoTabla1_2011(utf8_encode("Á"),'&Aacute;');			
				reemplazarTextoTabla1_2011(utf8_encode("É"),'&Eacute;');
				reemplazarTextoTabla1_2011(utf8_encode("Í"),'&Iacute;');
				reemplazarTextoTabla1_2011(utf8_encode("Ó"),'&Oacute;');
				reemplazarTextoTabla1_2011(utf8_encode("Ú"),'&Uacute;');
				reemplazarTextoTabla1_2011(utf8_encode("Ñ"),'&Ntilde;');
				
				// Tabla Consumo.TiposComprobante 
				reemplazarTextoTabla2_2011(utf8_encode("Á"),'&Aacute;');			
				reemplazarTextoTabla2_2011(utf8_encode("É"),'&Eacute;');
				reemplazarTextoTabla2_2011(utf8_encode("Í"),'&Iacute;');
				reemplazarTextoTabla2_2011(utf8_encode("Ó"),'&Oacute;');
				reemplazarTextoTabla2_2011(utf8_encode("Ú"),'&Uacute;');
				reemplazarTextoTabla2_2011(utf8_encode("Ñ"),'&Ntilde;');
				
				// Tabla Consumo.Grupos 
				reemplazarTextoTabla3_2011(utf8_encode("Á"),'&Aacute;');			
				reemplazarTextoTabla3_2011(utf8_encode("É"),'&Eacute;');
				reemplazarTextoTabla3_2011(utf8_encode("Í"),'&Iacute;');
				reemplazarTextoTabla3_2011(utf8_encode("Ó"),'&Oacute;');
				reemplazarTextoTabla3_2011(utf8_encode("Ú"),'&Uacute;');
				reemplazarTextoTabla3_2011(utf8_encode("Ñ"),'&Ntilde;');
				
				// Tabla Central.CentrosCosto 
				reemplazarTextoTabla5_2011(utf8_encode("Á"),'&Aacute;');			
				reemplazarTextoTabla5_2011(utf8_encode("É"),'&Eacute;');
				reemplazarTextoTabla5_2011(utf8_encode("Í"),'&Iacute;');
				reemplazarTextoTabla5_2011(utf8_encode("Ó"),'&Oacute;');
				reemplazarTextoTabla5_2011(utf8_encode("Ú"),'&Uacute;');
				reemplazarTextoTabla5_2011(utf8_encode("Ñ"),'&Ntilde;');
				*/
						
				llenarMatriz2011();
				insertarTabla2011();
						   
				   
				/*		   
				// Tabla Consumo.Comprobantes 
				reemplazarTextoTabla1_2011('&Aacute;',utf8_encode("Á"));			
				reemplazarTextoTabla1_2011('&Eacute;', utf8_encode("É"));
				reemplazarTextoTabla1_2011('&Iacute;', utf8_encode("Í"));
				reemplazarTextoTabla1_2011('&Oacute;' , utf8_encode("Ó"));
				reemplazarTextoTabla1_2011('&Uacute;' , utf8_encode("Ú"));
				reemplazarTextoTabla1_2011('&Ntilde;', utf8_encode("Ñ"));
				
				// Tabla Consumo.TiposComprobante 
				reemplazarTextoTabla2_2011('&Aacute;', utf8_encode("Á"));			
				reemplazarTextoTabla2_2011('&Eacute;', utf8_encode("É"));
				reemplazarTextoTabla2_2011('&Iacute;', utf8_encode("Í"));
				reemplazarTextoTabla2_2011('&Oacute;', utf8_encode("Ó"));
				reemplazarTextoTabla2_2011('&Uacute;', utf8_encode("Ú"));
				reemplazarTextoTabla2_2011('&Ntilde;', utf8_encode("Ñ"));
				
				// Tabla Consumo.Grupos 
				reemplazarTextoTabla3_2011('&Aacute;', utf8_encode("Á"));			
				reemplazarTextoTabla3_2011('&Eacute;', utf8_encode("É"));
				reemplazarTextoTabla3_2011('&Iacute;', utf8_encode("Í"));
				reemplazarTextoTabla3_2011('&Oacute;', utf8_encode("Ó"));
				reemplazarTextoTabla3_2011('&Uacute;', utf8_encode("Ú"));
				reemplazarTextoTabla3_2011('&Ntilde;', utf8_encode("Ñ"));
				
				// Tabla Consumo.Movimiento 
				reemplazarTextoTabla4_2011('&Aacute;', utf8_encode("Á"));			
				reemplazarTextoTabla4_2011('&Eacute;', utf8_encode("É"));
				reemplazarTextoTabla4_2011('&Iacute;', utf8_encode("Í"));
				reemplazarTextoTabla4_2011('&Oacute;', utf8_encode("Ó"));
				reemplazarTextoTabla4_2011('&Uacute;',utf8_encode("Ú"));
				reemplazarTextoTabla4_2011('&Ntilde;',utf8_encode("Ñ"));		
				
				// Tabla Central.CentrosCosto    
				
				reemplazarTextoTabla5_2011('&Aacute;', utf8_encode("Á"));			
				reemplazarTextoTabla5_2011('&Eacute;', utf8_encode("É"));
				reemplazarTextoTabla5_2011('&Iacute;', utf8_encode("Í"));
				reemplazarTextoTabla5_2011('&Oacute;', utf8_encode("Ó"));
				reemplazarTextoTabla5_2011('&Uacute;',utf8_encode("Ú"));
				reemplazarTextoTabla5_2011('&Ntilde;',utf8_encode("Ñ"));	
				*/
			
			
			}
				

		/* Finaliza la definicion de funciones*/	
		
		
		
		
		
			
		
			
	

		
		
		
		

	
	




?>