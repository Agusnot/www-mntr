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
	
	
		
		function contarRegistrosMySQL2012() {
			$cnx = conectar_mysql("Salud");
			$cons = "SELECT COUNT(*) AS conteomysql FROM Salud.EntradasFarmacia2012";
			$res =  mysql_query($cons);
			$fila = mysql_fetch_array($res);
			$res = $fila['conteomysql'];
			return $res; 	
		
		}
		
		
		function contarRegistrosPostgresql2012() {
			$cnx= conectar_postgres();
			$cons = "SELECT COUNT(*) AS conteo FROM Consumo.movimiento WHERE almacenppal = 'FARMACIA' AND UPPER(comprobante) = 'ORDEN DE COMPRA FARMACIA' AND DATE_PART('year', fecha) = '2012'";
			$res =  pg_query($cnx, $cons);
			$fila = pg_fetch_array($res);
			$res = $fila['conteo'];
			return $res; 	
		
		}
		
		function contarRegistrosPostgresql2012Errores() {
			$cnx= conectar_postgres();
			$cons = "SELECT COUNT(*) AS conteo FROM Consumo.movimientoMigracion2";
			$res =  pg_query($cnx, $cons);
			$fila = pg_fetch_array($res);
			$res = $fila['conteo'];
			return $res; 	
		
		}
		
		
		function eliminarEntradasFarmacia2012() {
			$cnx= conectar_postgres();
			$cons = "DELETE  FROM Consumo.movimiento WHERE almacenppal = 'FARMACIA' AND UPPER(Comprobante) = 'ORDEN DE COMPRA FARMACIA' AND DATE_PART('year', fecha) = '2012'";
			$res =  @pg_query($cnx, $cons);
				if (!$res)  {
					echo "<p class='error1'> Error de ejecucion </p>".pg_last_error()."<br>";
					echo "<p class= 'subtitulo1'>Comando SQL </p> <br>".$cons."<br>"; 				
					echo "<br><br>";
				}
		
		}
		
		
		
		function  reemplazarTextoTabla1_2012($cadenaBusqueda,$cadenaReemplazo)  {
			$cnx= conectar_postgres();
			$cons = "UPDATE Consumo.comprobantes SET almacenppal = replace( almacenppal,'$cadenaBusqueda','$cadenaReemplazo'), compania = replace( compania,'$cadenaBusqueda','$cadenaReemplazo') ,  comprobante = replace( comprobante,'$cadenaBusqueda','$cadenaReemplazo'),  tipo = replace( tipo,'$cadenaBusqueda','$cadenaReemplazo')";
			
			$res = pg_query($cnx , $cons);
				if (!$res)  {
					echo "<p class='error1'> Error en la normalizacion de la codificacion </p>".pg_last_error()."<br>";
				}

		}
		
		function  reemplazarTextoTabla2_2012($cadenaBusqueda,$cadenaReemplazo)  {
			$cnx= conectar_postgres();
			$cons = "UPDATE Consumo.tiposcomprobante SET tipo = replace( tipo,'$cadenaBusqueda','$cadenaReemplazo')";
			
			$res = pg_query($cnx , $cons);
				if (!$res)  {
					echo "<p class='error1'> Error en la normalizacion de la codificacion </p>".pg_last_error()."<br>";
				}

		}
		
		function  reemplazarTextoTabla3_2012($cadenaBusqueda,$cadenaReemplazo)  {
			$cnx= conectar_postgres();
			$cons = "UPDATE Consumo.grupos SET grupo = replace( grupo ,'$cadenaBusqueda','$cadenaReemplazo'),  almacenppal = replace( almacenppal ,'$cadenaBusqueda','$cadenaReemplazo'),  compania = replace( compania ,'$cadenaBusqueda','$cadenaReemplazo')";
			
			$res = pg_query($cnx , $cons);
				if (!$res)  {
					echo "<p class='error1'> Error en la normalizacion de la codificacion </p>".pg_last_error()."<br>";
				}

		}
		
		
		function  reemplazarTextoTabla4_2012($cadenaBusqueda,$cadenaReemplazo)  {
			$cnx= conectar_postgres();
			$cons = "UPDATE Consumo.movimiento SET compania = replace( compania,'$cadenaBusqueda','$cadenaReemplazo'), almacenppal = replace( almacenppal,'$cadenaBusqueda','$cadenaReemplazo'), comprobante = replace( comprobante,'$cadenaBusqueda','$cadenaReemplazo'), tipocomprobante = replace( tipocomprobante,'$cadenaBusqueda','$cadenaReemplazo'), centrocosto = replace( centrocosto,'$cadenaBusqueda','$cadenaReemplazo')  WHERE almacenppal = 'FARMACIA' AND UPPER(Comprobante) = 'ORDEN DE COMPRA FARMACIA' AND DATE_PART('year',fecha) = '2012'";
			
			$res = @pg_query($cnx , $cons);
				if (!$res)  {
					echo "<p class='error1'> Error en la normalizacion de la codificacion </p>".pg_last_error()."<br>";
				}

		}
		
		
			function  reemplazarTextoTabla5_2012($cadenaBusqueda,$cadenaReemplazo)  {
			$cnx= conectar_postgres();
			$cons = "UPDATE Central.centroscosto SET compania = replace( compania,'$cadenaBusqueda','$cadenaReemplazo'), centrocostos = replace( centrocostos,'$cadenaBusqueda','$cadenaReemplazo')";
			
			$res = pg_query($cnx , $cons);
				if (!$res)  {
					echo "<p class='error1'> Error en la normalizacion de la codificacion </p>".pg_last_error()."<br>";
				}

		}
		
		function generarNota2012($numero,$nota) {
			echo "<p class='mensajeEjecucion'><span class = 'nota1'> Nota $numero : </span> $nota </p> ";
		}
		
		
		function  cambiarcampo2012()  {
			$cnx= conectar_postgres();
			$cons = "ALTER TABLE consumo.movimiento   ALTER COLUMN centrocosto TYPE character varying(100)";
			
			$res = @pg_query($cnx , $cons);
				if (!$res)  {
					echo "<p class='error1'> Error de ejecucion </p>".pg_last_error()."<br>";
					echo "<p class= 'subtitulo1'>Comando SQL </p> <br>".$cons."<br>"; 				
					echo "<br><br>";
				}

		}
		
		
		function consultarCentroCosto2012($centrocosto) {
			$cnx= conectar_postgres();
			$cons = "SELECT codigo  FROM Central.CentrosCosto WHERE UPPER(centrocostos) = '$centrocosto'";
			$res =  @pg_query($cnx, $cons);
				if (!$res) {
					$consUTF8 = utf8_encode($cons);
					$res =  @pg_query($cnx, $consUTF8);	
						if (!$res) {
							$fp = fopen("ReporteEntradasFarmacia.html", "a+");	
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
		
	
		
	
		function llamarMovimientoMySQL2012() {
			// El limite inicial y final se usan para una inmensa cantidad de registros
			global $res;
			$cnx = conectar_mysql("Salud");
			$cons = "SELECT YEAR(Fecha) AS anio, EntradasFarmacia2012.*  FROM Salud.EntradasFarmacia2012 ORDER BY Fecha ASC, OrdenCompra ASC ";
			$res =  mysql_query($cons);
			return $res; 
		
		}
		
		function eliminarTablaMigracion2012() {
			// Elimina la tabla Consumo.movimientoMigracion2
			$cnx= conectar_postgres();
			$cons = "DROP TABLE  IF EXISTS Consumo.movimientoMigracion2";
			$res =  pg_query($cons);
			
		}
		
		
		function crearTablaMigracion2012() {
		// Esta funcion crea una tabla con estructura similar a la tabla Contabilidad.movimiento, con la diferencia que carece de llave primaria y omite la restriccion NOT NULL,          ademas  crea un nuevo campo llamado error en el cual se almacena la descripcion del error generado en la consulta inicial.
			$cnx= conectar_postgres();
			$cons = "CREATE TABLE IF NOT EXISTS consumo.movimientoMigracion2(  compania character varying(60) ,  almacenppal character varying(30) ,  fecha date ,  comprobante character varying(30) ,  tipocomprobante character varying(30) ,  numero character varying(25) ,  cedula character varying(15),  detalle text,  autoid integer  ,  usuariocre character varying(50),  fechacre timestamp without time zone,  modificadox character varying(50),  fechamod timestamp without time zone,  estado character varying(2),  cantidad double precision,  vrcosto double precision,  totcosto double precision ,  vrventa double precision,  totventa double precision,
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
		
		
		
		function insertarRegistroMigracion2012($compania, $almacenppal, $fecha, $comprobante, $tipocomprobante, $numero, $autoid, $usuariocre, $estado, $cantidad, $usuariocre, $estado,  $cantidad, $vrcosto, $totcosto, $vrventa, $totventa, $porciva, $vriva,  $porcdescto, $vrdescto, $centrocosto, $nofactura, $vrfactura, $anio, $grupo,$cum, $lote, $error) {
			
			$cnx = 	conectar_postgres();
			$cons = "INSERT INTO Consumo.movimientoMigracion2 (compania, almacenppal, fecha, comprobante, tipocomprobante, numero, autoid, usuariocre, estado, cantidad, vrcosto, totcosto, vrventa, totventa, porciva, vriva, porcdescto, vrdescto,  centrocosto, nofactura, vrfactura, anio, grupo, cum , lote,  error) VALUES ('$compania', '$almacenppal', '$fecha', '$comprobante', '$tipocomprobante', '$numero', $autoid, '$usuariocre', '$estado', $cantidad, $vrcosto, $totcosto, $vrventa, $totventa, $porciva, $vriva,  $porcdescto, $vrdescto, '$centrocosto', '$nofactura', $vrfactura, $anio, '$grupo', '$cum', '$lote', '$error')"	;
					 
			$cons = str_replace( "'NULL'","NULL",$cons  );	
			$res = @pg_query($cnx, $cons);
				if(!$res) {
					$consUTF8 = utf8_encode($cons);
					$resUTF8 = @pg_query($cnx, $consUTF8);
						if (!$resUTF8) {
							
							$fp = fopen("ReporteEntradasFarmacia.html", "a+");	
							$errorEjecucion= "<p class='error1'> Error de ejecucion </p>".pg_last_error()."<br>";
							$consulta= "<p class= 'subtitulo1'>Comando SQL </p> <br>".$consUTF8."<br/> <br/> <br/>";  
							fputs($fp, $errorEjecucion);
							fputs($fp, $consulta);
							fclose($fp);
							
						}
				}	
		}	
		
		function crearArchivo2012() {
			$fp = fopen("ReporteEntradasFarmacia.html", "w+");
			$encabezado = "<html> <head> <title> Reporte errores Consumo.movimiento (EntradasFarmacia) </title> 
			<link rel='stylesheet' type='text/css' href='../../General/estilos/estilos.css'> </head>";
			fputs($fp, $encabezado);
			fclose($fp);
		}	
		
		
		
		
		
		function insertarRegistroPostgresql2012($compania, $almacenppal, $fecha, $comprobante, $tipocomprobante, $numero, $autoid, $usuariocre, $estado, $cantidad,  $vrcosto, $totcosto, $vrventa, $totventa, $porciva, $vriva,  $porcdescto, $vrdescto, $centrocosto, $nofactura, $vrfactura, $anio, $grupo, $cum, $lote ) {
			
			$cnx = 	conectar_postgres();
			$cons = "INSERT INTO Consumo.movimiento(compania, almacenppal, fecha, comprobante, tipocomprobante, numero, autoid, usuariocre, estado, cantidad, vrcosto, totcosto, vrventa, totventa, porciva, vriva, porcdescto, vrdescto,  centrocosto, nofactura, vrfactura, anio, grupo, cum , lote) VALUES ('$compania', '$almacenppal', '$fecha', '$comprobante', '$tipocomprobante', '$numero', $autoid, '$usuariocre', '$estado', $cantidad, $vrcosto, $totcosto, $vrventa, $totventa, $porciva, $vriva,  $porcdescto, $vrdescto, '$centrocosto', '$nofactura', $vrfactura, $anio, '$grupo' , '$cum' , '$lote')"	;
				 ;
			$cons = str_replace( "'NULL'","NULL",$cons  );	
			$res = @pg_query($cnx, $cons);
				if (!$res) {
					$consUTF8 = utf8_encode($cons);
					$resUTF8 = @pg_query($cnx, $consUTF8);					
						if (!$resUTF8) {
							$error = pg_last_error();
							insertarRegistroMigracion2012($compania, $almacenppal, $fecha, $comprobante, $tipocomprobante, $numero, $autoid, $usuariocre, $estado, $cantidad, $usuariocre, $estado,  $cantidad, $vrcosto, $totcosto, $vrventa, $totventa, $porciva, $vriva,  $porcdescto, $vrdescto, $centrocosto, $nofactura, $vrfactura, $anio, $grupo, $cum, $lote,  $error) ;
						}
				
				}

				
		}
		
		
		function  llenarMatriz2012(){
			
			unset($matriz); 
			global  $matriz;	
			$res = llamarMovimientoMySQL2012();
			$posicion=0;
				
				while ($fila = mysql_fetch_array($res))
				{	
					
					$matriz["fecha"][$posicion] = $fila["Fecha"];
					$matriz["numero"][$posicion] = $fila["OrdenCompra"];
					$matriz["autoid"][$posicion] = $fila["Codigo"];
					$matriz["usuariocre"][$posicion] = $fila["Usuario"];
					$matriz["estado"][$posicion] = $fila["Estado"];					
					$matriz["cantidad"][$posicion] = $fila["Cantidad"];	
					$matriz["vrcosto"][$posicion] = $fila["VrUnidad"];	
					$matriz["totcosto"][$posicion] = $fila["VrTotal"];															
					$matriz["porciva"][$posicion] = $fila["PIVA"];
					$matriz["vriva"][$posicion] = $fila["VrIVA"];																					
					$matriz["porcdescto"][$posicion] = $fila["Pdscto"];																										
					$matriz["vrdescto"][$posicion] = $fila["VrDescto"];																										
					$matriz["nofactura"][$posicion] = $fila["Factura"];
					$matriz["vrfactura"][$posicion] = $fila["VrTotal"];
					$matriz["anio"][$posicion] = $fila["anio"];
					$matriz["grupo"][$posicion] = $fila["TipoMd"];
					$matriz["lote"][$posicion] = $fila["Lote"];
					$matriz["laboratorio"][$posicion] = $fila["Proveedor"];
					$matriz["fechavencimiento"][$posicion] = $fila["FecVenc"];
					$matriz["reginvima"][$posicion] = $fila["NoRegSan"];
					$matriz["cantidad"][$posicion] = $fila["Cantidad"];
					
					
					
					
												
					$posicion++;				
				}
							
				
			}
			

				
		
		
		
			
			function insertarTabla2012()  {
			
				global $res,$matriz;
				$numeroMaximo = consultarMaximoLote();
				$centrocosto = consultarCentroCosto2012("FARMACIA");
					for($pos=0;$pos <= mysql_num_rows($res); $pos++)  {
					
					$compania = $_SESSION["compania"];
					$almacenppal = "FARMACIA";
					$fecha=  $matriz["fecha"][$pos] ;
						if($fecha == "0000-00-00" or fecha == "0000-00-00 00:00:00")  {
								$fecha = '1900-01-01';//Valor por defecto, ya que dicho campo en la base de datos es NOT NULL 						
						}	
					$comprobante= "Orden de Compra Farmacia";	 
					$tipocomprobante = "Entradas";	
					
					$numero= $matriz['numero'][$pos] ;
					
					
					$autoid=	 $matriz["autoid"][$pos] ;
					$usuariocre =  $matriz['usuariocre'][$pos] ;
					$estado = "AC";

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
											
					$porciva=	 $matriz["porciva"][$pos] ;
						if(trim($totventa) == ''){
							$totventa = 0;
						}
					$vriva=	 $matriz["vriva"][$pos] ;
						if(trim($vriva) == ''){
							$vriva = 0;
						}
						
					$porcretefte=	 $matriz["porcretefte"][$pos] ;
						if(trim($porcretefte) == ''){
							$porcretefte = 0;
						}
					$vrretefte=	 $matriz["vrretefte"][$pos] ;
						if(trim($vrretefte) == ''){
							$vrretefte = 0;
						}
						
					$porcdescto=	 $matriz["porcdescto"][$pos] ;
						if(trim($porcdescto) == ''){
							$porcdescto = 0;
						}
					$vrdescto=	 $matriz["vrdescto"][$pos] ;
						if(trim($vrdescto) == ''){
							$vrdescto = 0;
						}
					$porcica=	 $matriz["porcica"][$pos] ;
						if(trim($porcica) == ''){
							$porcica = 0;
						}
					$vrica=	 $matriz["vrica"][$pos] ;
						if(trim($vrica) == ''){
							$vrica = 0;
						}
					$centrocosto=	 $matriz["centrocosto"][$pos] ;
					$centrocosto= eliminarCaracteresEspeciales($centrocosto);
					$centrocosto= consultarCentroCosto2012($centrocosto);
					
					$grupo=	 $matriz["grupo"][$pos] ;
					$grupo= eliminarCaracteresEspeciales($grupo);
					
					$idsol =	 $matriz["idsolicitud"][$pos] ;
					
					$anio =  $matriz["anio"][$pos] ;
					
					$vrfactura =  $matriz["vrfactura"][$pos] ;
					
					$grupo = $matriz["grupo"][$pos] ;
					$grupo = eliminarCaracteresEspeciales($grupo);
					$grupo = normalizarGruposConsumo($grupo);
						
					$lote =  $matriz["lote"][$pos] ;
					$lote = eliminarCaracteresEspeciales($lote);
					
					$laboratorio = $matriz["laboratorio"][$pos] ;
					$laboratorio = eliminarCaracteresEspeciales($laboratorio);
					
					$fechavencimiento = $matriz["fechavencimiento"][$pos] ;
						if(($fechavencimiento == "0000-00-00 00:00:00") or ($fechavencimiento == "0000-00-00")) {
							$fechavencimiento= "1900-01-01";// Fecha por defecto, teniendo en cuenta que el campo es NOT NULL
						}
					
					$tipo = "Entradas Farmacia";
					
					$cerrado = 1; // Por defecto se definen los lotes como cerrados
					
					$numero = $pos + $numeroMaximo; // Valor incrementable
					
					$reginvima = $matriz["reginvima"][$pos] ;
					$reginvima = eliminarCaracteresEspeciales($reginvima);	

					$presentacion = definirPresentacionProducto($autoid);
					
					$cantidad  = $matriz["cantidad"][$pos] ;
					
					$salidas = $cantidad; // Al definir los lotes como cerrados las salidas deben ser igual a la cantidad entrante.
									
					$temperatura = 30; // Se deja por defecto 30 grados como temperatura por defecto
					
					//$cum = consultarCUMProducto($autoid);
					$cum = 'NULL';
					
					insertarRegistroPostgresql($compania, $almacenppal, $fecha, $comprobante, $tipocomprobante, $numero, $autoid, $usuariocre, $estado, $cantidad,  $vrcosto, $totcosto, $vrventa, $totventa, $porciva, $vriva,  $porcdescto, $vrdescto, $centrocosto, $nofactura, $vrfactura, $anio, $grupo , $cum, $lote ) ;
					
					insertarLotes($compania, $almacenppal, $autoid, $cantidad, $lote, $fechavencimiento, $tipo, $cerrado, $numero, $laboratorio, $reginvima, $presentacion, $salidas, $temperatura);
					
						
					}
			
	
			}
				

		/* Finaliza la definicion de funciones*/	
		
		
		
		
		/* Inicia la ejecucion de la migracion */
			
		function migrarEntradasFarmacia2012(){
			
			
			
			eliminarEntradasFarmacia2012();
			//eliminarTmpMovimiento(1);
			//eliminarTablaMigracion2012();
			//crearTablaMigracion2012();
			//crearArchivo2012();
			//cambiarcampo2012();
			
			// Tabla Consumo.Comprobantes 
			reemplazarTextoTabla1_2012(utf8_encode("Á"),'&Aacute;');			
			reemplazarTextoTabla1_2012(utf8_encode("É"),'&Eacute;');
			reemplazarTextoTabla1_2012(utf8_encode("Í"),'&Iacute;');
			reemplazarTextoTabla1_2012(utf8_encode("Ó"),'&Oacute;');
			reemplazarTextoTabla1_2012(utf8_encode("Ú"),'&Uacute;');
			reemplazarTextoTabla1_2012(utf8_encode("Ñ"),'&Ntilde;');
			
			//* Tabla Consumo.TiposComprobante 
			reemplazarTextoTabla2_2012(utf8_encode("Á"),'&Aacute;');			
			reemplazarTextoTabla2_2012(utf8_encode("É"),'&Eacute;');
			reemplazarTextoTabla2_2012(utf8_encode("Í"),'&Iacute;');
			reemplazarTextoTabla2_2012(utf8_encode("Ó"),'&Oacute;');
			reemplazarTextoTabla2_2012(utf8_encode("Ú"),'&Uacute;');
			reemplazarTextoTabla2_2012(utf8_encode("Ñ"),'&Ntilde;');
			
			// Tabla Consumo.Grupos 
			reemplazarTextoTabla3_2012(utf8_encode("Á"),'&Aacute;');			
			reemplazarTextoTabla3_2012(utf8_encode("É"),'&Eacute;');
			reemplazarTextoTabla3_2012(utf8_encode("Í"),'&Iacute;');
			reemplazarTextoTabla3_2012(utf8_encode("Ó"),'&Oacute;');
			reemplazarTextoTabla3_2012(utf8_encode("Ú"),'&Uacute;');
			reemplazarTextoTabla3_2012(utf8_encode("Ñ"),'&Ntilde;');
			
			// Tabla Central.CentrosCosto 
			reemplazarTextoTabla5_2012(utf8_encode("Á"),'&Aacute;');			
			reemplazarTextoTabla5_2012(utf8_encode("É"),'&Eacute;');
			reemplazarTextoTabla5_2012(utf8_encode("Í"),'&Iacute;');
			reemplazarTextoTabla5_2012(utf8_encode("Ó"),'&Oacute;');
			reemplazarTextoTabla5_2012(utf8_encode("Ú"),'&Uacute;');
			reemplazarTextoTabla5_2012(utf8_encode("Ñ"),'&Ntilde;');
			
					
			llenarMatriz2012();
			insertarTabla2012();
					   
			   
					   
			/* Tabla Consumo.Comprobantes */
			reemplazarTextoTabla1_2012('&Aacute;',utf8_encode("Á"));			
			reemplazarTextoTabla1_2012('&Eacute;', utf8_encode("É"));
			reemplazarTextoTabla1_2012('&Iacute;', utf8_encode("Í"));
			reemplazarTextoTabla1_2012('&Oacute;' , utf8_encode("Ó"));
			reemplazarTextoTabla1_2012('&Uacute;' , utf8_encode("Ú"));
			reemplazarTextoTabla1_2012('&Ntilde;', utf8_encode("Ñ"));
			
			/* Tabla Consumo.TiposComprobante */
			reemplazarTextoTabla2_2012('&Aacute;', utf8_encode("Á"));			
			reemplazarTextoTabla2_2012('&Eacute;', utf8_encode("É"));
			reemplazarTextoTabla2_2012('&Iacute;', utf8_encode("Í"));
			reemplazarTextoTabla2_2012('&Oacute;', utf8_encode("Ó"));
			reemplazarTextoTabla2_2012('&Uacute;', utf8_encode("Ú"));
			reemplazarTextoTabla2_2012('&Ntilde;', utf8_encode("Ñ"));
			
			/* Tabla Consumo.Grupos */
			reemplazarTextoTabla3_2012('&Aacute;', utf8_encode("Á"));			
			reemplazarTextoTabla3_2012('&Eacute;', utf8_encode("É"));
			reemplazarTextoTabla3_2012('&Iacute;', utf8_encode("Í"));
			reemplazarTextoTabla3_2012('&Oacute;', utf8_encode("Ó"));
			reemplazarTextoTabla3_2012('&Uacute;', utf8_encode("Ú"));
			reemplazarTextoTabla3_2012('&Ntilde;', utf8_encode("Ñ"));
			
			/* Tabla Consumo.Movimiento */
			reemplazarTextoTabla4_2012('&Aacute;', utf8_encode("Á"));			
			reemplazarTextoTabla4_2012('&Eacute;', utf8_encode("É"));
			reemplazarTextoTabla4_2012('&Iacute;', utf8_encode("Í"));
			reemplazarTextoTabla4_2012('&Oacute;', utf8_encode("Ó"));
			reemplazarTextoTabla4_2012('&Uacute;',utf8_encode("Ú"));
			reemplazarTextoTabla4_2012('&Ntilde;',utf8_encode("Ñ"));		
			
			/* Tabla Central.CentrosCosto */   
			
			reemplazarTextoTabla5_2012('&Aacute;', utf8_encode("Á"));			
			reemplazarTextoTabla5_2012('&Eacute;', utf8_encode("É"));
			reemplazarTextoTabla5_2012('&Iacute;', utf8_encode("Í"));
			reemplazarTextoTabla5_2012('&Oacute;', utf8_encode("Ó"));
			reemplazarTextoTabla5_2012('&Uacute;',utf8_encode("Ú"));
			reemplazarTextoTabla5_2012('&Ntilde;',utf8_encode("Ñ"));	
					   
					   
			
				   
					   
					   
			
			
				//$nota = "Validar los Centros de Costo del esquema Central con respecto a los diferentes Centros de Costo de los Movimientos";
				//generarNota2012(1,$nota);	
				
				//$nota = "Modificar el tamaño del campo 'CentroCosto' de la tabla Consumo.Movimiento a Character Varying(20) ";
				//generarNota2012(2,$nota);	
				
				/*
				$totalMySQL = contarRegistrosMySQL2012();
				$totalPostgresql =  contarRegistrosPostgresql2012();
				$totalPostgresqlErrores =  contarRegistrosPostgresql2012Errores();
				
				echo "<p class= 'subtitulo1'> Total registros MySQL:</p>";
				echo  $totalMySQL."<br/>";
				echo "<p class= 'subtitulo1'> Total registros Postgresql migrados:</p>";
				echo  $totalPostgresql."<br/>";
				echo "<p class= 'error1'> Total errores generados(Tabla Consumo.movimientoMigracion2):</p>";
				echo  $totalPostgresqlErrores."<br/>";
				
				echo "<p> <a href='ReporteEntradasFarmacia.html' class = 'link1' target='_blank'> Ver Reporte de errores de la migracion </a> </p>";
				
				echo "<span align='right'> <a href='revertirSuministros.php?accion=revertirMigracion' class = 'link1'> Revertir Migracion Movimiento Suministros </a> </span>";*/
				
			
			
		}
			
	

		
		
		
		

	
	




?>