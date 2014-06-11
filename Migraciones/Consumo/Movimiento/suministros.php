	<html>
		<head>
			<title> Migracion Consumo.Movimiento </title> 
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
	
	
		
		function contarRegistrosMySQL() {
			$cnx = conectar_mysql("Suministros");
			$cons = "SELECT COUNT(*) AS conteoMySQL FROM Suministros.movimientos";
			$res =  mysql_query($cons);
			$fila = mysql_fetch_array($res);
			$res = $fila['conteoMySQL'];
			return $res; 	
		
		}
		
		
		function contarRegistrosPostgresql() {
			$cnx= conectar_postgres();
			$cons = "SELECT COUNT(*) AS conteo FROM Consumo.movimiento WHERE almacenppal = 'SUMINISTROS'";
			$res =  pg_query($cnx, $cons);
			$fila = pg_fetch_array($res);
			$res = $fila['conteo'];
			return $res; 	
		
		}
		
		function contarRegistrosPostgresqlErrores() {
			$cnx= conectar_postgres();
			$cons = "SELECT COUNT(*) AS conteo FROM Consumo.movimientoMigracion1";
			$res =  pg_query($cnx, $cons);
			$fila = pg_fetch_array($res);
			$res = $fila['conteo'];
			return $res; 	
		
		}
		
		
		function eliminarMovimientosSuministros() {
			$cnx= conectar_postgres();
			$cons = "DELETE  FROM Consumo.movimiento WHERE almacenppal = 'SUMINISTROS'";
			$res =  @pg_query($cnx, $cons);
				if (!$res)  {
					echo "<p class='error1'> Error de ejecucion </p>".mysql_error()."<br>";
					echo "<p class= 'subtitulo1'>Comando SQL </p> <br>".$cons."<br>"; 				
					echo "<br><br>";
				}
			 	
		
		}
		
		
		
		
		
		function  reemplazarTextoTabla1($cadenaBusqueda,$cadenaReemplazo)  {
			$cnx= conectar_postgres();
			$cons = "UPDATE Consumo.comprobantes SET almacenppal = replace( almacenppal,'$cadenaBusqueda','$cadenaReemplazo'), compania = replace( compania,'$cadenaBusqueda','$cadenaReemplazo') ,  comprobante = replace( comprobante,'$cadenaBusqueda','$cadenaReemplazo'),  tipo = replace( tipo,'$cadenaBusqueda','$cadenaReemplazo')";
			
			$res = pg_query($cnx , $cons);
				if (!$res)  {
					echo "<p class='error1'> Error en la normalizacion de la codificacion </p>".pg_last_error()."<br>";
				}

		}
		
		function  reemplazarTextoTabla2($cadenaBusqueda,$cadenaReemplazo)  {
			$cnx= conectar_postgres();
			$cons = "UPDATE Consumo.tiposcomprobante SET tipo = replace( tipo,'$cadenaBusqueda','$cadenaReemplazo')";
			
			$res = pg_query($cnx , $cons);
				if (!$res)  {
					echo "<p class='error1'> Error en la normalizacion de la codificacion </p>".pg_last_error()."<br>";
				}

		}
		
		function  reemplazarTextoTabla3($cadenaBusqueda,$cadenaReemplazo)  {
			$cnx= conectar_postgres();
			$cons = "UPDATE Consumo.grupos SET grupo = replace( grupo ,'$cadenaBusqueda','$cadenaReemplazo'),  almacenppal = replace( almacenppal ,'$cadenaBusqueda','$cadenaReemplazo'),  compania = replace( compania ,'$cadenaBusqueda','$cadenaReemplazo')";
			
			$res = pg_query($cnx , $cons);
				if (!$res)  {
					echo "<p class='error1'> Error en la normalizacion de la codificacion </p>".pg_last_error()."<br>";
				}

		}
		
		
		function  reemplazarTextoTabla4($cadenaBusqueda,$cadenaReemplazo)  {
			$cnx= conectar_postgres();
			$cons = "UPDATE Consumo.movimiento SET compania = replace( compania,'$cadenaBusqueda','$cadenaReemplazo'), almacenppal = replace( almacenppal,'$cadenaBusqueda','$cadenaReemplazo'), comprobante = replace( comprobante,'$cadenaBusqueda','$cadenaReemplazo'), tipocomprobante = replace( tipocomprobante,'$cadenaBusqueda','$cadenaReemplazo'), centrocosto = replace( centrocosto,'$cadenaBusqueda','$cadenaReemplazo')";
			
			$res = @pg_query($cnx , $cons);
				if (!$res)  {
					echo "<p class='error1'> Error en la normalizacion de la codificacion </p>".pg_last_error()."<br>";
				}

		}
		
		
			function  reemplazarTextoTabla5($cadenaBusqueda,$cadenaReemplazo)  {
			$cnx= conectar_postgres();
			$cons = "UPDATE Central.centroscosto SET compania = replace( compania,'$cadenaBusqueda','$cadenaReemplazo'), centrocostos = replace( centrocostos,'$cadenaBusqueda','$cadenaReemplazo')";
			
			$res = pg_query($cnx , $cons);
				if (!$res)  {
					echo "<p class='error1'> Error en la normalizacion de la codificacion </p>".pg_last_error()."<br>";
				}

		}
		
		function generarNota($numero,$nota) {
			echo "<p class='mensajeEjecucion'><span class = 'nota1'> Nota $numero : </span> $nota </p> ";
		}
		
		
		function  cambiarcampo()  {
			$cnx= conectar_postgres();
			$cons = "ALTER TABLE consumo.movimiento   ALTER COLUMN centrocosto TYPE character varying(100)";
			
			$res = @pg_query($cnx , $cons);
				if (!$res)  {
					echo "<p class='error1'> Error de ejecucion </p>".mysql_error()."<br>";
					echo "<p class= 'subtitulo1'>Comando SQL </p> <br>".$cons."<br>"; 				
					echo "<br><br>";
				}

		}
		
		
		function consultarCentroCosto($centrocosto) {
			$cnx= conectar_postgres();
			$cons = "SELECT codigo  FROM Central.CentrosCosto WHERE centrocostos = '$centrocosto'";
			$res =  @pg_query($cnx, $cons);
				if (!$res) {
					$consUTF8 = utf8_encode($cons);
					$res =  @pg_query($cnx, $consUTF8);	
						if (!$res) {
							$fp = fopen("ReporteMovimientoSuministros.html", "a+");	
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
		
		
		
		
	
		
		
			
		
	
		function llamarMovimientoMySQL($limiteIni, $limiteFin) {
			// El limite inicial y final se usan para una inmensa cantidad de registros
			global $res;
			$cnx = conectar_mysql("Suministros");
			$cons = "SELECT YEAR(Fecha) AS anio, movimientos.*  FROM Suministros.movimientos ORDER BY AutoId ASC LIMIT $limiteIni , $limiteFin";
			$res =  mysql_query($cons);
			return $res; 
		
		}
		
		function eliminarTablaMigracion() {
			// Elimina la tabla Consumo.movimientoMigracion1
			$cnx= conectar_postgres();
			$cons = "DROP TABLE  IF EXISTS Consumo.movimientomigracion1";
			$res =  pg_query($cons);
			
		}
		
		
		function crearTablaMigracion() {
		// Esta funcion crea una tabla con estructura similar a la tabla Contabilidad.movimiento, con la diferencia que carece de llave primaria y omite la restriccion NOT NULL,          ademas  crea un nuevo campo llamado error en el cual se almacena la descripcion del error generado en la consulta inicial.
			$cnx= conectar_postgres();
			$cons = "CREATE TABLE consumo.movimientoMigracion1(  compania character varying(60) ,  almacenppal character varying(30) ,  fecha date ,  comprobante character varying(30) ,  tipocomprobante character varying(30) ,  numero character varying(25) ,  cedula character varying(15),  detalle text,  autoid integer  ,  usuariocre character varying(50),  fechacre timestamp without time zone,  modificadox character varying(50),  fechamod timestamp without time zone,  estado character varying(2),  cantidad double precision,  vrcosto double precision,  totcosto double precision ,  vrventa double precision,  totventa double precision,
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
		
		
		
		function insertarRegistroMigracion($compania, $almacenppal, $fecha, $comprobante, $tipocomprobante, $numero, $autoid, $cantidad, $vrcosto, $totcosto, $vrventa, $totventa, $porciva, $vriva, $porcretefte, $vrretefte, $porcdescto, $vrdescto, $porcica, $vrica, $centrocosto, $grupo, $idsolicitud, $anio,  $error) {
			
			$cnx = 	conectar_postgres();
			$cons = "INSERT INTO Consumo.movimientomigracion1 (compania, almacenppal, fecha, comprobante, tipocomprobante, numero, autoid, cantidad, vrcosto, totcosto, vrventa, totventa, porciva, vriva, porcretefte, vrretefte, porcdescto, vrdescto, porcica, vrica, centrocosto, grupo, idsolicitud, anio,  error) VALUES ('$compania', '$almacenppal', '$fecha', '$comprobante', '$tipocomprobante', '$numero', $autoid, $cantidad, $vrcosto, $totcosto, $vrventa, $totventa, $porciva, $vriva, $porcretefte, $vrretefte, $porcdescto, $vrdescto, $porcica, $vrica, '$centrocosto', '$grupo', $idsolicitud, $anio, '$error')"	;
					 
			$cons = str_replace( "'NULL'","NULL",$cons  );	
			$res = @pg_query($cnx, $cons);
				if(!$res) {
					$consUTF8 = utf8_encode($cons);
					$resUTF8 = @pg_query($cnx, $consUTF8);
						if (!$resUTF8) {
							
							$fp = fopen("ReporteMovimientoSuministros.html", "a+");	
							$errorEjecucion= "<p class='error1'> Error de ejecucion </p>".pg_last_error()."<br>";
							$consulta= "<p class= 'subtitulo1'>Comando SQL </p> <br>".$consUTF8."<br/> <br/> <br/>";  
							fputs($fp, $errorEjecucion);
							fputs($fp, $consulta);
							fclose($fp);
							
						}
				}	
		}	
		
		function crearArchivo() {
			$fp = fopen("ReporteMovimientoSuministros.html", "w+");
			$encabezado = "<html> <head> <title> Reporte errores Consumo.movimiento (Suministros) </title> 
			<link rel='stylesheet' type='text/css' href='../../General/estilos/estilos.css'> </head>";
			fputs($fp, $encabezado);
			fclose($fp);
		}	
		
		
		
		
		
		function insertarRegistroPostgresql($compania, $almacenppal, $fecha, $comprobante, $tipocomprobante, $numero, $autoid, $cantidad, $vrcosto, $totcosto, $vrventa, $totventa, $porciva, $vriva, $porcretefte, $vrretefte, $porcdescto, $vrdescto, $porcica, $vrica, $centrocosto, $grupo, $idsolicitud, $anio) {
			
			$cnx = 	conectar_postgres();
			$cons = "INSERT INTO Consumo.movimiento(compania, almacenppal, fecha, comprobante, tipocomprobante, numero, autoid, cantidad, vrcosto, totcosto, vrventa, totventa, porciva, vriva, porcretefte, vrretefte, porcdescto, vrdescto, porcica, vrica, centrocosto, grupo, idsolicitud, anio) VALUES ('$compania', '$almacenppal', '$fecha', '$comprobante', '$tipocomprobante', '$numero', $autoid, $cantidad, $vrcosto, $totcosto, $vrventa, $totventa, $porciva, $vriva, $porcretefte, $vrretefte, $porcdescto, $vrdescto, $porcica, $vrica, '$centrocosto', '$grupo',$idsolicitud, $anio)"	;
					 
			$cons = str_replace( "'NULL'","NULL",$cons  );	
			$res = @pg_query($cnx, $cons);
				if (!$res) {
					$consUTF8 = utf8_encode($cons);
					$resUTF8 = @pg_query($cnx, $consUTF8);					
						if (!$resUTF8) {
							$error = pg_last_error();
							insertarRegistroMigracion($compania, $almacenppal, $fecha, $comprobante, $tipocomprobante, $numero, $autoid, $cantidad, $vrcosto, $totcosto, $vrventa, $totventa, $porciva, $vriva, $porcretefte, $vrretefte, $porcdescto, $vrdescto, $porcica, $vrica, $centrocosto, $grupo, $idsolicitud,$anio, $error);
						}
				
				}

				
		}
		
		
		function  llenarMatriz($limiteIni, $limiteFin){
			
			unset($matriz); 
			global  $matriz;	
			$res = llamarMovimientoMySQL($limiteIni, $limiteFin);
			$posicion=0;
				
				while ($fila = mysql_fetch_array($res))
				{	
					
					$matriz["fecha"][$posicion] = $fila["Fecha"];
					$matriz["tipocomprobante"][$posicion] = $fila["Tipo"];
					$matriz["numero"][$posicion] = $fila["DocSoporte"];
					$matriz["autoid"][$posicion] = $fila["Codigo"];					
					$matriz["cantidad"][$posicion] = $fila["Cantidad"];	
					$matriz["vrcosto"][$posicion] = $fila["VrCosto"];	
					$matriz["totcosto"][$posicion] = $fila["TotCosto"];															
					$matriz["vrventa"][$posicion] = $fila["VrVenta"];						
					$matriz["totventa"][$posicion] = $fila["TotVenta"];											
					$matriz["porciva"][$posicion] = $fila["IVA"];
					$matriz["vriva"][$posicion] = $fila["VrIVA"];																					
					$matriz["porcretefte"][$posicion] = $fila["RteFte"];																										
					$matriz["vrretefte"][$posicion] = $fila["VrRteFte"];																															
					$matriz["porcdescto"][$posicion] = $fila["Descto"];																										
					$matriz["vrdescto"][$posicion] = $fila["VrDescto"];																										
					$matriz["porcica"][$posicion] = $fila["ICA"];
					$matriz["vrica"][$posicion] = $fila["VrICA"];
					$matriz["centrocosto"][$posicion] = $fila["CentroCost"];																										
					$matriz["grupo"][$posicion] = $fila["Grupo"];
					$matriz["idsolicitud"][$posicion] = $fila["AutoId"];
					$matriz["anio"][$posicion] = $fila["anio"];
					
					
												
					$posicion++;				
				}
							
				
			}
			

				
		
		
		
			
			function insertarTabla($puntero)  {
			
				global $res,$matriz;
					for($pos=0;$pos < mysql_num_rows($res); $pos++)  {
					
					$compania = $_SESSION["compania"];
					$almacenppal = "SUMINISTROS";
					$fecha= 	 $matriz["fecha"][$pos] ;
						if($fecha == "0000-00-00")  {
								$fecha = '1900-01-01';//Valor por defecto, ya que dicho campo en la base de datos es NOT NULL 						
						}	
					$tipocomprobante=	 $matriz["tipocomprobante"][$pos] ;
					$tipocomprobante= eliminarCaracteresEspeciales($tipocomprobante);
					
						if ($tipocomprobante == "SUM") {
							$tipocomprobante = "Entradas";
						}	
						
						if ($tipocomprobante == "SAL") {
							$tipocomprobante = "Salidas";
						}
					$comprobante = $tipocomprobante;						
					$numero=	 $matriz['numero'][$pos] ;
					$autoid=	 $matriz["autoid"][$pos] ;
					$cantidad=	 $matriz["cantidad"][$pos] ;
					$vrcosto=	 $matriz["vrcosto"][$pos] ;
					$totcosto=	 $matriz["totcosto"][$pos] ;
					$vrventa=	 $matriz["vrventa"][$pos] ;
					$totventa=	 $matriz["totventa"][$pos] ;
					$porciva=	 $matriz["porciva"][$pos] ;
					$vriva=	 $matriz["vriva"][$pos] ;
					$porcretefte=	 $matriz["porcretefte"][$pos] ;
					$vrretefte=	 $matriz["vrretefte"][$pos] ;
					$porcdescto=	 $matriz["porcdescto"][$pos] ;
					$vrdescto=	 $matriz["vrdescto"][$pos] ;
					$porcica=	 $matriz["porcica"][$pos] ;
					$vrica=	 $matriz["vrica"][$pos] ;
					$centrocosto=	 $matriz["centrocosto"][$pos] ;
					$centrocosto= eliminarCaracteresEspeciales($centrocosto);
					$centrocosto= consultarCentroCosto($centrocosto);
					$grupo=	 $matriz["grupo"][$pos];
					$grupo= eliminarCaracteresEspeciales($grupo);
						if (trim($grupo)== ''){
							$grupo = "POR VALIDAR";
						}
					$idsol =	 $matriz["idsolicitud"][$pos] ;
					$anio =  $matriz["anio"][$pos] ;
					
					
										
										
					insertarRegistroPostgresql($compania, $almacenppal, $fecha, $comprobante, $tipocomprobante, $numero, $autoid, $cantidad, $vrcosto, $totcosto, $vrventa, $totventa, $porciva, $vriva, $porcretefte, $vrretefte, $porcdescto, $vrdescto, $porcica, $vrica, $centrocosto, $grupo, $idsol,$anio);
					
						
					}
			
	
			}
				

		/* Finaliza la definicion de funciones*/	
		
		
		
		
		/* Inicia la ejecucion de la migracion */
			
		if($_GET['tabla']="Suministros_movimiento") {
		
			echo "<fieldset>";			
			echo "<legend> Migracion tabla MySQL </legend>";
			echo "<span align='left'> <a href='../../index.php?migracion=MIG062' class = 'link1'> Panel de Administracion </a> </span>";
			
			eliminarMovimientosSuministros();
			eliminarTmpMovimiento(1);
			eliminarTablaMigracion();
			crearTablaMigracion();
			crearArchivo();
			cambiarcampo();
			
			/* Tabla Consumo.Comprobantes */
			reemplazarTextoTabla1(utf8_encode("Á"),'&Aacute;');			
			reemplazarTextoTabla1(utf8_encode("É"),'&Eacute;');
			reemplazarTextoTabla1(utf8_encode("Í"),'&Iacute;');
			reemplazarTextoTabla1(utf8_encode("Ó"),'&Oacute;');
			reemplazarTextoTabla1(utf8_encode("Ú"),'&Uacute;');
			reemplazarTextoTabla1(utf8_encode("Ñ"),'&Ntilde;');
			
			/* Tabla Consumo.TiposComprobante */
			reemplazarTextoTabla2(utf8_encode("Á"),'&Aacute;');			
			reemplazarTextoTabla2(utf8_encode("É"),'&Eacute;');
			reemplazarTextoTabla2(utf8_encode("Í"),'&Iacute;');
			reemplazarTextoTabla2(utf8_encode("Ó"),'&Oacute;');
			reemplazarTextoTabla2(utf8_encode("Ú"),'&Uacute;');
			reemplazarTextoTabla2(utf8_encode("Ñ"),'&Ntilde;');
			
			/* Tabla Consumo.Grupos */
			reemplazarTextoTabla3(utf8_encode("Á"),'&Aacute;');			
			reemplazarTextoTabla3(utf8_encode("É"),'&Eacute;');
			reemplazarTextoTabla3(utf8_encode("Í"),'&Iacute;');
			reemplazarTextoTabla3(utf8_encode("Ó"),'&Oacute;');
			reemplazarTextoTabla3(utf8_encode("Ú"),'&Uacute;');
			reemplazarTextoTabla3(utf8_encode("Ñ"),'&Ntilde;');
			
			/* Tabla Central.CentrosCosto */
			reemplazarTextoTabla5(utf8_encode("Á"),'&Aacute;');			
			reemplazarTextoTabla5(utf8_encode("É"),'&Eacute;');
			reemplazarTextoTabla5(utf8_encode("Í"),'&Iacute;');
			reemplazarTextoTabla5(utf8_encode("Ó"),'&Oacute;');
			reemplazarTextoTabla5(utf8_encode("Ú"),'&Uacute;');
			reemplazarTextoTabla5(utf8_encode("Ñ"),'&Ntilde;');
			
			
			
			
			$total = contarRegistrosMySQL();
			$fragmento = 8192;	
			
				if ($total >= $fragmento ) {

				
					$numCiclos = $total / $fragmento;
					$numCiclos = ceil($numCiclos);
										
					$limiteIni = 1;
					
						for ($i = 1; $i <= $numCiclos; $i++){
						
							llenarMatriz($limiteIni,$fragmento);
							insertarTabla($limiteIni);
								if ($i == $numCiclos) {
									echo "<div align='center'> <p class='mensajeFinalizacion'>Ha terminado la migracion de los movimientos de suministros </p> </div>";
									break;
								}	
							$limiteIni = $limiteIni + $fragmento;							
							
					   } 
					   
			   
					   
			/* Tabla Consumo.Comprobantes */
			reemplazarTextoTabla1('&Aacute;',utf8_encode("Á"));			
			reemplazarTextoTabla1('&Eacute;', utf8_encode("É"));
			reemplazarTextoTabla1('&Iacute;', utf8_encode("Í"));
			reemplazarTextoTabla1('&Oacute;' , utf8_encode("Ó"));
			reemplazarTextoTabla1('&Uacute;' , utf8_encode("Ú"));
			reemplazarTextoTabla1('&Ntilde;', utf8_encode("Ñ"));
			
			/* Tabla Consumo.TiposComprobante */
			reemplazarTextoTabla2('&Aacute;', utf8_encode("Á"));			
			reemplazarTextoTabla2('&Eacute;', utf8_encode("É"));
			reemplazarTextoTabla2('&Iacute;', utf8_encode("Í"));
			reemplazarTextoTabla2('&Oacute;', utf8_encode("Ó"));
			reemplazarTextoTabla2('&Uacute;', utf8_encode("Ú"));
			reemplazarTextoTabla2('&Ntilde;', utf8_encode("Ñ"));
			
			/* Tabla Consumo.Grupos */
			reemplazarTextoTabla3('&Aacute;', utf8_encode("Á"));			
			reemplazarTextoTabla3('&Eacute;', utf8_encode("É"));
			reemplazarTextoTabla3('&Iacute;', utf8_encode("Í"));
			reemplazarTextoTabla3('&Oacute;', utf8_encode("Ó"));
			reemplazarTextoTabla3('&Uacute;', utf8_encode("Ú"));
			reemplazarTextoTabla3('&Ntilde;', utf8_encode("Ñ"));
			
			/* Tabla Consumo.Movimiento */
			reemplazarTextoTabla4('&Aacute;', utf8_encode("Á"));			
			reemplazarTextoTabla4('&Eacute;', utf8_encode("É"));
			reemplazarTextoTabla4('&Iacute;', utf8_encode("Í"));
			reemplazarTextoTabla4('&Oacute;', utf8_encode("Ó"));
			reemplazarTextoTabla4('&Uacute;',utf8_encode("Ú"));
			reemplazarTextoTabla4('&Ntilde;',utf8_encode("Ñ"));		
			
			/* Tabla Central.CentrosCosto */   
			
			reemplazarTextoTabla5('&Aacute;', utf8_encode("Á"));			
			reemplazarTextoTabla5('&Eacute;', utf8_encode("É"));
			reemplazarTextoTabla5('&Iacute;', utf8_encode("Í"));
			reemplazarTextoTabla5('&Oacute;', utf8_encode("Ó"));
			reemplazarTextoTabla5('&Uacute;',utf8_encode("Ú"));
			reemplazarTextoTabla5('&Ntilde;',utf8_encode("Ñ"));	
					   
					   
			
				   
					   
					   
				}	   
				else  {
					echo "<p class='error1'> El total de registros en MySQL es menor al fragmento</p>";
				
				}
			
				$nota = "Validar los Centros de Costo del esquema Central con respecto a los diferentes Centros de Costo de los Movimientos";
				generarNota(1,$nota);	
				
				$nota = "Modificar el tamaño del campo 'CentroCosto' de la tabla Consumo.Movimiento a Character Varyng(20) ";
				generarNota(2,$nota);	
				$totalMySQL = contarRegistrosMySQL();
				$totalPostgresql =  contarRegistrosPostgresql();
				$totalPostgresqlErrores =  contarRegistrosPostgresqlErrores();
				
				echo "<p class= 'subtitulo1'> Total registros MySQL:</p>";
				echo  $totalMySQL."<br/>";
				echo "<p class= 'subtitulo1'> Total registros Postgresql migrados:</p>";
				echo  $totalPostgresql."<br/>";
				echo "<p class= 'error1'> Total errores generados(Tabla Consumo.MovimientoMigracion1):</p>";
				echo  $totalPostgresqlErrores."<br/>";
				
				echo "<p> <a href='ReporteMovimientoSuministros.html' class = 'link1' target='_blank'> Ver Reporte de errores de la migracion </a> </p>";
				
				echo "<span align='right'> <a href='revertirSuministros.php?accion=revertirMigracion' class = 'link1'> Revertir Migracion Movimiento Suministros </a> </span>";
				
				
									

				
				
			echo "</fieldset>";
			
		}
			
	

		
		
		
		

	
	




?>