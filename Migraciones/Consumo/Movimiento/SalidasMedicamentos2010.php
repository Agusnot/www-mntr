	<html>
		<head>
			<title> Migracion Consumo.Movimiento(SalidasMedicamentos) </title> 
			<link rel="stylesheet" type="text/css" href="../../General/estilos/estilos.css">
			<meta charset="UTF-8">
		</head>
	</html>	
	


<?php
	session_start();
	include_once '../../Conexiones/conexion.php';
	include_once('../../General/funciones/funciones.php');
	include_once('../TmpMovimiento/procedimiento.php');

	/* Inicia la definicion de funciones */
	
	
		
		function contarRegistrosMySQL2010() {
			$cnx = conectar_mysql("Salud");
			$cons = "SELECT COUNT(*) AS conteomysql FROM Salud.SalidaMedicamentos2010 AND YEAR(FechaDespacho) = '2010' ";
			$res =  mysql_query($cons);
			$fila = mysql_fetch_array($res);
			$res = $fila['conteomysql'];
			return $res; 	
		
		}
		
		
		function contarRegistrosPostgresql2010() {
			$cnx= conectar_postgres();
			$cons = "SELECT COUNT(*) AS conteo FROM Consumo.movimiento WHERE almacenppal = 'FARMACIA' AND UPPER(comprobante) = 'SALIDAS POR PLANTILLA' AND DATE_PART('year', fecha) = '2010'";
			$res =  pg_query($cnx, $cons);
			$fila = pg_fetch_array($res);
			$res = $fila['conteo'];
			return $res; 	
		
		}
		
		function contarRegistrosPostgresql2010Errores() {
			$cnx= conectar_postgres();
			$cons = "SELECT COUNT(*) AS conteo FROM Consumo.movimientoMigracion3";
			$res =  pg_query($cnx, $cons);
			$fila = pg_fetch_array($res);
			$res = $fila['conteo'];
			return $res; 	
		
		}
		
		
		function eliminarSalidasFarmacia2010() {
			$cnx= conectar_postgres();
			$cons = "DELETE  FROM Consumo.movimiento WHERE almacenppal = 'FARMACIA' AND UPPER(Comprobante) = 'SALIDAS POR PLANTILLA' AND DATE_PART('year', fecha) = '2010'";
			$res =  @pg_query($cnx, $cons);
				if (!$res)  {
					echo "<p class='error1'> Error de ejecucion </p>".pg_last_error()."<br>";
					echo "<p class= 'subtitulo1'>Comando SQL </p> <br>".$cons."<br>"; 				
					echo "<br><br>";
				}
		
		}
		
		
		
		function  reemplazarTextoTabla1_2010($cadenaBusqueda,$cadenaReemplazo)  {
			$cnx= conectar_postgres();
			$cons = "UPDATE Consumo.comprobantes SET almacenppal = replace( almacenppal,'$cadenaBusqueda','$cadenaReemplazo'), compania = replace( compania,'$cadenaBusqueda','$cadenaReemplazo') ,  comprobante = replace( comprobante,'$cadenaBusqueda','$cadenaReemplazo'),  tipo = replace( tipo,'$cadenaBusqueda','$cadenaReemplazo')";
			
			$res = pg_query($cnx , $cons);
				if (!$res)  {
					echo "<p class='error1'> Error en la normalizacion de la codificacion </p>".pg_last_error()."<br>";
				}

		}
		
		function  reemplazarTextoTabla2_2010($cadenaBusqueda,$cadenaReemplazo)  {
			$cnx= conectar_postgres();
			$cons = "UPDATE Consumo.tiposcomprobante SET tipo = replace( tipo,'$cadenaBusqueda','$cadenaReemplazo')";
			
			$res = pg_query($cnx , $cons);
				if (!$res)  {
					echo "<p class='error1'> Error en la normalizacion de la codificacion </p>".pg_last_error()."<br>";
				}

		}
		
		function  reemplazarTextoTabla3_2010($cadenaBusqueda,$cadenaReemplazo)  {
			$cnx= conectar_postgres();
			$cons = "UPDATE Consumo.grupos SET grupo = replace( grupo ,'$cadenaBusqueda','$cadenaReemplazo'),  almacenppal = replace( almacenppal ,'$cadenaBusqueda','$cadenaReemplazo'),  compania = replace( compania ,'$cadenaBusqueda','$cadenaReemplazo')";
			
			$res = pg_query($cnx , $cons);
				if (!$res)  {
					echo "<p class='error1'> Error en la normalizacion de la codificacion </p>".pg_last_error()."<br>";
				}

		}
		
		
		function  reemplazarTextoTabla4_2010($cadenaBusqueda,$cadenaReemplazo)  {
			$cnx= conectar_postgres();
			$cons = "UPDATE Consumo.movimiento SET compania = replace( compania,'$cadenaBusqueda','$cadenaReemplazo'), almacenppal = replace( almacenppal,'$cadenaBusqueda','$cadenaReemplazo'), comprobante = replace( comprobante,'$cadenaBusqueda','$cadenaReemplazo'), tipocomprobante = replace( tipocomprobante,'$cadenaBusqueda','$cadenaReemplazo'), centrocosto = replace( centrocosto,'$cadenaBusqueda','$cadenaReemplazo') WHERE almacenppal = 'FARMACIA' AND UPPER(Comprobante) = 'SALIDAS POR PLANTILLA' AND DATE_PART('year',fecha) = '2010'";
			
			$res = @pg_query($cnx , $cons);
				if (!$res)  {
					echo "<p class='error1'> Error en la normalizacion de la codificacion </p>".pg_last_error()."<br>";
				}

		}
		
		
			function  reemplazarTextoTabla5_2010($cadenaBusqueda,$cadenaReemplazo)  {
			$cnx= conectar_postgres();
			$cons = "UPDATE Central.centroscosto SET compania = replace( compania,'$cadenaBusqueda','$cadenaReemplazo'), centrocostos = replace( centrocostos,'$cadenaBusqueda','$cadenaReemplazo')";
			
			$res = pg_query($cnx , $cons);
				if (!$res)  {
					echo "<p class='error1'> Error en la normalizacion de la codificacion </p>".pg_last_error()."<br>";
				}

		}
		
		function generarNota_2010($numero,$nota) {
			echo "<p class='mensajeEjecucion'><span class = 'nota1'> Nota $numero : </span> $nota </p> ";
		}
		
		
		function  cambiarcampo_2010()  {
			$cnx= conectar_postgres();
			$cons = "ALTER TABLE consumo.movimiento   ALTER COLUMN centrocosto TYPE character varying(100)";
			
			$res = @pg_query($cnx , $cons);
				if (!$res)  {
					echo "<p class='error1'> Error de ejecucion </p>".pg_last_error()."<br>";
					echo "<p class= 'subtitulo1'>Comando SQL </p> <br>".$cons."<br>"; 				
					echo "<br><br>";
				}

		}
		
		
		function  cambiarcampoCedula_2010()  {
			$cnx= conectar_postgres();
			$cons = "ALTER TABLE consumo.movimiento   ALTER COLUMN Cedula TYPE character varying(50)";
			
			$res = @pg_query($cnx , $cons);
				if (!$res)  {
					echo "<p class='error1'> Error de ejecucion </p>".pg_last_error()."<br>";
					echo "<p class= 'subtitulo1'>Comando SQL </p> <br>".$cons."<br>"; 				
					echo "<br><br>";
				}

		}
		
		
		function consultarCentroCosto_2010($centrocosto) {
			$cnx= conectar_postgres();
			$cons = "SELECT codigo  FROM Central.CentrosCosto WHERE UPPER(centrocostos) = '$centrocosto'";
			$res =  @pg_query($cnx, $cons);
				if (!$res) {
					$consUTF8 = utf8_encode($cons);
					$res =  @pg_query($cnx, $consUTF8);	
						if (!$res) {
							$fp = fopen("ReporteSalidasMedicamentos.html", "a+");	
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
		
	
		
	
		function llamarMovimientoMySQL_2010() {
			// El limite inicial y final se usan para una inmensa cantidad de registros
			global $res;
			$cnx = conectar_mysql("Salud");
			$cons = "SELECT YEAR(FechaDespacho) AS anio, SalidaMedicamentos2010.*  FROM Salud.SalidaMedicamentos2010 WHERE YEAR(FechaDespacho) = '2010' ORDER BY FechaDespacho ASC ";
			
			$res =  mysql_query($cons);
			return $res; 
		
		}
		
		function eliminarTablaMigracion_2010() {
			// Elimina la tabla Consumo.movimientoMigracion3
			$cnx= conectar_postgres();
			$cons = "DROP TABLE  IF EXISTS Consumo.movimientoMigracion3";
			$res =  pg_query($cons);
			
		}
		
		
		function crearTablaMigracion_2010() {
		// Esta funcion crea una tabla con estructura similar a la tabla Contabilidad.movimiento, con la diferencia que carece de llave primaria y omite la restriccion NOT NULL,          ademas  crea un nuevo campo llamado error en el cual se almacena la descripcion del error generado en la consulta inicial.
			$cnx= conectar_postgres();
			$cons = "CREATE TABLE consumo.movimientoMigracion3(  compania character varying(60) ,  almacenppal character varying(30) ,  fecha date ,  comprobante character varying(30) ,  tipocomprobante character varying(30) ,  numero character varying(25) ,  cedula character varying(50),  detalle text,  autoid integer  ,  usuariocre character varying(50),  fechacre timestamp without time zone,  modificadox character varying(50),  fechamod timestamp without time zone,  estado character varying(2),  cantidad double precision,  vrcosto double precision,  totcosto double precision ,  vrventa double precision,  totventa double precision,
  porciva double precision,  vriva double precision,  porcretefte double precision,  vrretefte double precision,  porcdescto double precision,  vrdescto double precision,  porcica double precision,  vrica double precision,  centrocosto character varying(100) ,  idsolicitud integer,  aprobadox character varying(50),  fechaaprobac timestamp without time zone,  nodocafectado character varying(10),  docafectado character varying(30),  numerocontrato character varying(15),  tipotraslado character varying(1),  almacenppald character varying(30),  idtraslado integer  ,  anio integer ,  nofactura character varying(50),  vrfactura double precision,  incluyeiva integer ,
  compcontable character varying(50),  numcompcont character varying(100),  conceptortefte character varying(200),  regmedicamento double precision,  noliquidacion integer,  numservicio integer,  grupo character varying(150),  vobo integer,  usuariovobo character varying(200),  fechavobo timestamp with time zone,  notanovisado character varying(500),  numorden integer,  idescritura integer,  numerocontrolados double precision,  idregistro serial ,  cum character varying(1500),  motivodev character varying(1000),  fechadespacho date,  motivodevolucion text,  lote character(150),  error text )WITH (  OIDS=FALSE)";
	 		
			$res = @pg_query($cnx, $cons);
				if (!$res) {
				
					echo "<p class='error1'> Error de ejecucion </p>".pg_last_error()."<br>";
					echo "<p class= 'subtitulo1'>Comando SQL </p> <br>".$cons."<br>"; 				
					echo "<br><br>";			
					//echo "<br><br>";
				}
			
		}
		
		
		
		function insertarRegistroMigracion_2010($compania,$almacenppal,$fecha,$comprobante,$tipocomprobante,$numero,$cedula,$detalle,$autoid,$usuariocre,$fechacre,$estado,$cantidad,$vrcosto,$totcosto,$vrventa,$totventa,$centrocosto,$idtraslado,$anio,$incluyeiva,$idregistro,$cum,$fechadespacho,$lote, $grupo,  $error) {
			
			$cnx = 	conectar_postgres();
			$cons = "INSERT INTO Consumo.movimientoMigracion3 (compania,almacenppal,fecha,comprobante,tipocomprobante,numero,cedula,detalle,autoid,usuariocre,fechacre,estado,cantidad,vrcosto,totcosto,vrventa,totventa,centrocosto,idtraslado,anio,incluyeiva,idregistro,cum,fechadespacho,lote,grupo, error) VALUES ('$compania','$almacenppal','$fecha','$comprobante','$tipocomprobante','$numero','$cedula','$detalle',$autoid,'$usuariocre','$fechacre','$estado',$cantidad,$vrcosto,$totcosto,$vrventa,$totventa,'$centrocosto',$idtraslado,$anio,$incluyeiva,$idregistro,'$cum','$fechadespacho','$lote', '$grupo', '$error')"	;
					 
			$cons = str_replace( "'NULL'","NULL",$cons  );	
			$res = @pg_query($cnx, $cons);
				if(!$res) {
					$consUTF8 = utf8_encode($cons);
					$resUTF8 = @pg_query($cnx, $consUTF8);
						if (!$resUTF8) {
							
							$fp = fopen("ReporteSalidasMedicamentos.html", "a+");	
							$errorEjecucion= "<p class='error1'> Error de ejecucion </p>".pg_last_error()."<br>";
							$consulta= "<p class= 'subtitulo1'>Comando SQL </p> <br>".$consUTF8."<br/> <br/> <br/>";  
							fputs($fp, $errorEjecucion);
							fputs($fp, $consulta);
							fclose($fp);
							
						}
				}	
		}	
		
		function crearArchivo_2010() {
			$fp = fopen("ReporteSalidasMedicamentos.html", "w+");
			$encabezado = "<html> <head> <title> Reporte errores Consumo.movimiento (EntradasFarmacia) </title> 
			<link rel='stylesheet' type='text/css' href='../../General/estilos/estilos.css'> </head>";
			fputs($fp, $encabezado);
			fclose($fp);
		}	
		
		
		
		
		
		function insertarRegistroPostgresql_2010($compania,$almacenppal,$fecha,$comprobante,$tipocomprobante,$numero,$cedula,$detalle,$autoid,$usuariocre,$fechacre,$estado,$cantidad,$vrcosto,$totcosto,$vrventa,$totventa,$centrocosto,$idtraslado,$anio,$incluyeiva,$idregistro,$cum,$fechadespacho,$lote, $grupo) {
			
			$cnx = 	conectar_postgres();
			$cons = "INSERT INTO Consumo.movimiento (compania,almacenppal,fecha,comprobante,tipocomprobante,numero,cedula,detalle,autoid,usuariocre,fechacre,estado,cantidad,vrcosto,totcosto,vrventa,totventa,centrocosto,idtraslado,anio,incluyeiva,idregistro,cum,fechadespacho,lote, grupo) VALUES ('$compania','$almacenppal','$fecha','$comprobante','$tipocomprobante','$numero','$cedula','$detalle',$autoid,'$usuariocre','$fechacre','$estado',$cantidad,$vrcosto,$totcosto,$vrventa,$totventa,'$centrocosto',$idtraslado,$anio,$incluyeiva,$idregistro,'$cum','$fechadespacho','$lote' , '$grupo')"	;
					 
			$cons = str_replace( "'NULL'","NULL",$cons  );	
			$res = @pg_query($cnx, $cons);
				if (!$res) {
					$consUTF8 = utf8_encode($cons);
					$resUTF8 = @pg_query($cnx, $consUTF8);					
						if (!$resUTF8) {
							$error = pg_last_error();
							insertarRegistroMigracion_2010($compania,$almacenppal,$fecha,$comprobante,$tipocomprobante,$numero,$cedula,$detalle,$autoid,$usuariocre,$fechacre,$estado,$cantidad,$vrcosto,$totcosto,$vrventa,$totventa,$centrocosto,$idtraslado,$anio,$incluyeiva,$idregistro,$cum,$fechadespacho,$lote, $grupo,  $error); 
						}
				
				}

				
		}
		
		
		function  llenarMatriz_2010(){
			
			unset($matriz); 
			global  $matriz;	
			$res = llamarMovimientoMySQL_2010();
			$posicion=0;
				
				while ($fila = mysql_fetch_array($res))
				{	
					
					$matriz["fechadespacho"][$posicion] = $fila["FechaDespacho"];
					$matriz["cedula"][$posicion] = $fila["Cedula"];
					$matriz["autoid"][$posicion] = $fila["Codigo"];
					$matriz["usuariocre"][$posicion] = $fila["Usuario"];
					$matriz["fechacre"][$posicion] = $fila["FechaCre"];
					$matriz["cantidad"][$posicion] = $fila["Cantidad"];	
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
			

				
		
		
		
			
			function insertarTabla_2010()  {
			
				global $res,$matriz;
				$centrocosto = consultarCentroCosto_2010("FARMACIA");
					for($pos=0;$pos < mysql_num_rows($res); $pos++)  {
					
					$compania = $_SESSION["compania"];
					$almacenppal = "FARMACIA";
					$fechadespacho =   $matriz["fechadespacho"][$pos] ;
					
					
					
					
						if($fechadespacho == "0000-00-00" or fechadespacho == "0000-00-00 00:00:00")  {
								$fechadespacho = '1900-01-01';//Valor por defecto, ya que dicho campo en la base de datos es NOT NULL 						
						}	
					$fecha = $fechadespacho;
					$fechacre = $fechadespacho;
					
					$comprobante= "Salidas por Plantilla";	 
					$tipocomprobante = "Salidas";	
					$numero= $pos + 1; 
					$idregistro =  $numero;
					$cedula=	 $matriz["cedula"][$pos] ;					
					$autoid=	 $matriz["autoid"][$pos] ;
					$usuariocre =  $matriz['usuariocre'][$pos] ;
					$usuariocre = eliminarCaracteresEspeciales($usuariocre);
					$estado = "AC";
					
					$pabellon = $matriz['pabellon'][$pos] ;
					$pabellon = eliminarCaracteresEspeciales($pabellon);
					
					$detalle = "DESPACHO DE MEDICAMENTOS ".$pabellon;

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
						if($grupo == "MEDICAMENTO"){
							$grupo = "MEDICAMENTOS";
						}
					
					$lote = 'NULL';
										
					insertarRegistroPostgresql_2010($compania,$almacenppal,$fecha,$comprobante,$tipocomprobante,$numero,$cedula,$detalle,$autoid,$usuariocre,$fechacre,$estado,$cantidad,$vrcosto,$totcosto,$vrventa,$totventa,$centrocosto,$idtraslado,$anio,$incluyeiva,$idregistro,$cum,$fechadespacho,$lote, $grupo) ;
					
						
					}
			
	
			}
			
			function migrarSalidasMedicamentos2010() {
				
				
				/* Tabla Consumo.Comprobantes */
				reemplazarTextoTabla1_2010(utf8_encode("Á"),'&Aacute;');			
				reemplazarTextoTabla1_2010(utf8_encode("É"),'&Eacute;');
				reemplazarTextoTabla1_2010(utf8_encode("Í"),'&Iacute;');
				reemplazarTextoTabla1_2010(utf8_encode("Ó"),'&Oacute;');
				reemplazarTextoTabla1_2010(utf8_encode("Ú"),'&Uacute;');
				reemplazarTextoTabla1_2010(utf8_encode("Ñ"),'&Ntilde;');
				
				/* Tabla Consumo.TiposComprobante */
				reemplazarTextoTabla2_2010(utf8_encode("Á"),'&Aacute;');			
				reemplazarTextoTabla2_2010(utf8_encode("É"),'&Eacute;');
				reemplazarTextoTabla2_2010(utf8_encode("Í"),'&Iacute;');
				reemplazarTextoTabla2_2010(utf8_encode("Ó"),'&Oacute;');
				reemplazarTextoTabla2_2010(utf8_encode("Ú"),'&Uacute;');
				reemplazarTextoTabla2_2010(utf8_encode("Ñ"),'&Ntilde;');
				
				/* Tabla Consumo.Grupos */
				reemplazarTextoTabla3_2010(utf8_encode("Á"),'&Aacute;');			
				reemplazarTextoTabla3_2010(utf8_encode("É"),'&Eacute;');
				reemplazarTextoTabla3_2010(utf8_encode("Í"),'&Iacute;');
				reemplazarTextoTabla3_2010(utf8_encode("Ó"),'&Oacute;');
				reemplazarTextoTabla3_2010(utf8_encode("Ú"),'&Uacute;');
				reemplazarTextoTabla3_2010(utf8_encode("Ñ"),'&Ntilde;');
				
				/* Tabla Central.CentrosCosto */
				reemplazarTextoTabla5_2010(utf8_encode("Á"),'&Aacute;');			
				reemplazarTextoTabla5_2010(utf8_encode("É"),'&Eacute;');
				reemplazarTextoTabla5_2010(utf8_encode("Í"),'&Iacute;');
				reemplazarTextoTabla5_2010(utf8_encode("Ó"),'&Oacute;');
				reemplazarTextoTabla5_2010(utf8_encode("Ú"),'&Uacute;');
				reemplazarTextoTabla5_2010(utf8_encode("Ñ"),'&Ntilde;');
				
						
				llenarMatriz_2010();
				insertarTabla_2010();
						   
				   
						   
				/* Tabla Consumo.Comprobantes */
				reemplazarTextoTabla1_2010('&Aacute;',utf8_encode("Á"));			
				reemplazarTextoTabla1_2010('&Eacute;', utf8_encode("É"));
				reemplazarTextoTabla1_2010('&Iacute;', utf8_encode("Í"));
				reemplazarTextoTabla1_2010('&Oacute;' , utf8_encode("Ó"));
				reemplazarTextoTabla1_2010('&Uacute;' , utf8_encode("Ú"));
				reemplazarTextoTabla1_2010('&Ntilde;', utf8_encode("Ñ"));
				
				/* Tabla Consumo.TiposComprobante */
				reemplazarTextoTabla2_2010('&Aacute;', utf8_encode("Á"));			
				reemplazarTextoTabla2_2010('&Eacute;', utf8_encode("É"));
				reemplazarTextoTabla2_2010('&Iacute;', utf8_encode("Í"));
				reemplazarTextoTabla2_2010('&Oacute;', utf8_encode("Ó"));
				reemplazarTextoTabla2_2010('&Uacute;', utf8_encode("Ú"));
				reemplazarTextoTabla2_2010('&Ntilde;', utf8_encode("Ñ"));
				
				/* Tabla Consumo.Grupos */
				reemplazarTextoTabla3_2010('&Aacute;', utf8_encode("Á"));			
				reemplazarTextoTabla3_2010('&Eacute;', utf8_encode("É"));
				reemplazarTextoTabla3_2010('&Iacute;', utf8_encode("Í"));
				reemplazarTextoTabla3_2010('&Oacute;', utf8_encode("Ó"));
				reemplazarTextoTabla3_2010('&Uacute;', utf8_encode("Ú"));
				reemplazarTextoTabla3_2010('&Ntilde;', utf8_encode("Ñ"));
				
				/* Tabla Consumo.Movimiento */
				reemplazarTextoTabla4_2010('&Aacute;', utf8_encode("Á"));			
				reemplazarTextoTabla4_2010('&Eacute;', utf8_encode("É"));
				reemplazarTextoTabla4_2010('&Iacute;', utf8_encode("Í"));
				reemplazarTextoTabla4_2010('&Oacute;', utf8_encode("Ó"));
				reemplazarTextoTabla4_2010('&Uacute;',utf8_encode("Ú"));
				reemplazarTextoTabla4_2010('&Ntilde;',utf8_encode("Ñ"));		
				
				/* Tabla Central.CentrosCosto */   
				
				reemplazarTextoTabla5_2010('&Aacute;', utf8_encode("Á"));			
				reemplazarTextoTabla5_2010('&Eacute;', utf8_encode("É"));
				reemplazarTextoTabla5_2010('&Iacute;', utf8_encode("Í"));
				reemplazarTextoTabla5_2010('&Oacute;', utf8_encode("Ó"));
				reemplazarTextoTabla5_2010('&Uacute;',utf8_encode("Ú"));
				reemplazarTextoTabla5_2010('&Ntilde;',utf8_encode("Ñ"));	
						   
				
			}
				

		/* Finaliza la definicion de funciones*/	
		
		
		
		
		
		
			
	

		
		
		
		

	
	




?>