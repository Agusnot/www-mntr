	<html>	
		<head>
			<title> Migracion Facturacion.FacturasCredito </title>
			<link rel="stylesheet" type="text/css" href="../../General/estilos/estilos.css">
		</head>
	
	
	<?php
		session_start();
		include_once('../../General/funciones/funciones.php');
		include_once('../../Conexiones/conexion.php');
		include_once('../Liquidacion/procedimiento.php');
		
		
		
		
		/* Inicia defincion de funciones */
		
			
		function  normalizarCodificacion($cadenaBusqueda,$cadenaReemplazo)  {
		// Busca y reemplaza ocurrencias en una tabla
			$cnx= conectar_postgres();
			$cons = "UPDATE Facturacion.FacturasCredito SET Contrato = replace( contrato,'$cadenaBusqueda','$cadenaReemplazo'), ambito = replace( ambito,'$cadenaBusqueda','$cadenaReemplazo'), estado = replace( estado,'$cadenaBusqueda','$cadenaReemplazo')";
			
			$res = pg_query($cnx , $cons);
				if (!$res)  {
					echo "<p class='error1'> Error en la normalizacion de la codificacion </p>".pg_last_error()."<br>";
					echo "<p class= 'subtitulo1'>Comando SQL </p> <br>".$cons."<br>"; 				
				}

		}
		
		
		
		
		
		function  consultarServicio($cedula, $fechaing, $fechaegr)  {
		// Busca y reemplaza ocurrencias en una tabla
			
			$cnx= conectar_postgres();
			$cons = " SELECT  * FROM Salud.Servicios WHERE cedula = '$cedula' AND DATE(fechaing) = '$fechaing' AND DATE(FechaEgr) = '$fechaegr'";
			
			$res = @pg_query($cnx , $cons);
				if (!$res) {				
					echo "<p class='error1'> Error de ejecucion </p>".pg_last_error()."<br>";
					echo "<p class= 'subtitulo1'>Comando SQL </p> <br>".$cons."<br>"; 				
					echo "<br><br>";			
				}
				
			return $res;	

		}

		
				
	
		function llamarRegistrosMySQL() {
			// Selecciona los registros MySQL (Origen)
			global $res;
			$cnx = conectar_mysql("Facturacion");
			$cons = "SELECT *  FROM Facturacion.FacturasCredito ORDER BY NoFactura ASC ";
			$res =  mysql_query($cons);
			return $res; 
		
		}
		
		
		function contarRegistrosMySQL() {
			// Selecciona los registros MySQL (Origen)
			global $res;
			$cnx = conectar_mysql("Facturacion");
			$cons = "SELECT COUNT(*) AS conteo FROM Facturacion.FacturasCredito ";
			$res =  mysql_query($cons);
			$fila = mysql_fetch_array($res);
			$conteo = $fila["conteo"];
			return $conteo; 
		
		}
		
		
		function consultarNitEntidadMySQL($nombreentidad) {
			// Selecciona los registros MySQL (Origen)
			
			$cnx = conectar_mysql("Salud");
			$cons = "SELECT nit  FROM Salud.EPS WHERE Nombre = '$nombreentidad' ";
			$res =  mysql_query($cons);
			$fila = mysql_fetch_array($res);
			$fila = $fila["nit"];
			return $fila; 
		
		}
		
		function consultarNitEntidadPostgresql($nombreentidad) {
			// Selecciona los registros MySQL (Origen)
			$nombreentidad = strtoupper($nombreentidad);
			$nombreentidad = trim($nombreentidad);
			$cnx = conectar_postgres();
			$cons = "SELECT identificacion  FROM Central.Terceros WHERE UPPER(TRIM(primape)) = '$nombreentidad' AND UPPER(Tipo) = 'ASEGURADOR'";
			
			$res =  @pg_query($cons);
			
				if (!$res) {
				
					echo "<p class='error1'> Error de ejecucion </p>".pg_last_error()."<br>";
					echo "<p class= 'subtitulo1'>Comando SQL </p> <br>".$cons."<br>"; 				
					echo "<br><br>";			
				}
				
			$fila = @pg_fetch_array($res);
			$fila = $fila["identificacion"];
			return $fila; 
		
		}
		
		function eliminarTablaMigracion() {
		// Esta funcion crea una tabla con estructura similar a la tabla Postgresql con la diferencia que carece de llave primaria y omite la restriccion NOT NULL,          ademas  crea un nuevo campo llamado error en el cual se almacena la descripcion del error generado en la consulta inicial.
			$cnx= conectar_postgres();
			$cons = "DROP TABLE facturacion.facturascreditoMigracion";	
			$res = @pg_query($cnx, $cons);
				if (!$res) {
				
					/*echo "<p class='error1'> Error de ejecucion </p>".pg_last_error()."<br>";
					echo "<p class= 'subtitulo1'>Comando SQL </p> <br>".$cons."<br>"; 				
					echo "<br><br>";			*/
					
				}
			
		}
		
		function crearTablaMigracion() {
		// Esta funcion crea una tabla con estructura similar a la tabla Postgresql con la diferencia que carece de llave primaria y omite la restriccion NOT NULL,          ademas  crea un nuevo campo llamado error en el cual se almacena la descripcion del error generado en la consulta inicial.
			$cnx= conectar_postgres();
			$cons = "CREATE TABLE IF NOT EXISTS facturacion.facturascreditoMigracion(  compania character varying(80) ,  fechacrea timestamp without time zone,  usucrea character varying(100),  fechaini date,  fechafin date,  entidad character varying(200),  contrato character varying(150),  nocontrato character varying(150),  ambito character varying(150),
  subtotal double precision,  copago double precision,  descuento double precision,  total double precision,  nofactura integer ,  fecharadic date,  usuarioradic character varying(200),  fechaglosa date,  usuarioglosa character varying(100),  motivoglosa character varying(400),  vrglosa double precision,  fechavoboglosa date,  claseglosa character varying(200),  fecharta date,  argumentorta character varying(400),  estadoreceprta character varying(200),  fechaconciliacion date,
  individual integer,  estado character varying(2) ,  usumod character varying(100),  fechamod timestamp without time zone,  compcontable character varying(200),  nocompcontable character varying(50),  fecharasis timestamp with time zone,  tipofactura character varying(300),  phpcrea character varying(200),  phpmodif character varying(200),  devolucion smallint,  numradicacion character varying(30), error text )WITH (  OIDS=FALSE)";	
			$res = @pg_query($cnx, $cons);
				if (!$res) {
				
					echo "<p class='error1'> Error de ejecucion </p>".pg_last_error()."<br>";
					echo "<p class= 'subtitulo1'>Comando SQL </p> <br>".$cons."<br>"; 				
					echo "<br><br>";			
					//echo "<br><br>";
				}
			
		}
		
		function insertarFacturasCreditoMigracion($compania, $fechacrea, $usucrea, $fechaini, $fechafin, $entidad, $contrato, $nocontrato, $ambito, $subtotal, $copago, $descuento, $total, $nofactura, $individual,  $error) {
		//Realiza la insercion en Postgresql con base en los parametros
			
			$cnx = 	conectar_postgres();
			$cons = "INSERT INTO Facturacion.FacturasCredito (compania, fechacrea, usucrea, fechaini, fechafin, entidad, contrato, nocontrato, ambito, subtotal, copago, descuento, total, nofactura, individual,  error ) VALUES ('$compania', '$fechacrea', '$usucrea', '$fechaini', '$fechafin', '$entidad', '$contrato', '$nocontrato', '$ambito', $subtotal, $copago, $descuento, $total, $nofactura, '$individual',  '$error')"	;
					 
			$cons = str_replace( "'NULL'","NULL",$cons  );	
			$res = @pg_query($cnx, $cons);
				if (!$res) {
					$consUTF8 = utf8_encode($cons);
					$resUTF8 = @pg_query($cnx, $consUTF8);					
						if (!$resUTF8) {
							$fp = fopen("ReporteFacturasCredito.html", "a+");	
							$errorEjecucion= "<p class='error1'> Error de ejecucion </p>".pg_last_error()."<br>";
							$consulta= "<p class= 'subtitulo1'>Comando SQL </p> <br>".$consUTF8."<br/> <br/> <br/>";  
							fputs($fp, $errorEjecucion);
							fputs($fp, $consulta);
							fclose($fp);
						
						}
				
				}
	
		}
		
		
		function crearArchivo() {
			$fp = fopen("ReporteFacturasCredito.html", "w+");
			$encabezado = "<html> <head> <title> Reporte errores Facturacion.FacturasCredito </title> 
			<link rel='stylesheet' type='text/css' href='../../General/estilos/estilos.css'> </head>";
			fputs($fp, $encabezado);
			fclose($fp);
		}	
		
		
		
		
		
		

		
		function insertarFacturasCredito($compania, $fechacrea, $usucrea, $fechaini, $fechafin, $entidad, $contrato, $nocontrato, $ambito, $subtotal, $copago, $descuento, $total, $nofactura , $individual, $estado ) {
		//Realiza la insercion en Postgresql con base en los parametros
			
			$cnx = 	conectar_postgres();
			$cons = "INSERT INTO Facturacion.FacturasCredito (compania, fechacrea, usucrea, fechaini, fechafin, entidad, contrato, nocontrato, ambito, subtotal, copago, descuento, total, nofactura, individual) VALUES ('$compania', '$fechacrea', '$usucrea', '$fechaini', '$fechafin', '$entidad', '$contrato', '$nocontrato', '$ambito', $subtotal, $copago, $descuento, $total, $nofactura, '$individual')"	;
					 
			$cons = str_replace( "'NULL'","NULL",$cons  );	
			$res = @pg_query($cnx, $cons);
				if (!$res) {
					$consUTF8 = utf8_encode($cons);
					$resUTF8 = @pg_query($cnx, $consUTF8);					
						if (!$resUTF8) {
							$error = pg_last_error();
							insertarFacturasCreditoMigracion($compania, $fechacrea, $usucrea, $fechaini, $fechafin, $entidad, $contrato, $nocontrato, $ambito, $subtotal, $copago, $descuento, $total, $nofactura, $individual,  $error);
							
							
						}
				
				}

				
		}
		
		
		function  llenarMatriz(){
		// Llena una matriz con el resultado de la consulta MySQL
			
			unset($matriz); 
			global  $res, $matriz;	
			$res = llamarRegistrosMySQL();
			$posicion=0;
				
				while ($fila = mysql_fetch_array($res))
				{	
					
					$matriz["fechacrea"][$posicion] = $fila["FechaExp"];
					$matriz["usucrea"][$posicion] = $fila["Usuario"];
					$matriz["fechaini"][$posicion] = $fila["FecInicio"];										
					$matriz["fechafin"][$posicion] = $fila["FecFinal"];
					$matriz["entidad"][$posicion] = $fila["NombreEntidad"];	
					$matriz["nocontrato"][$posicion] = $fila["NoContrato"];	
					$matriz["ambito"][$posicion] = $fila["Ambito"];	
					$matriz["copago"][$posicion] = $fila["VrCopago"];
					$matriz["descuento"][$posicion] = $fila["VrDescuentos"];
					$matriz["total"][$posicion] = $fila["VrFactura"];	
					$matriz["nofactura"][$posicion] = $fila["NoFactura"];
					$matriz["estado"][$posicion] = $fila["Estado"];
					$matriz["cedula"][$posicion] = $fila["NoIdUsu"];
					
											
					$posicion++;				
				}
				//echo "La Posicion es: ".$posicion."<br>";
				//echo "El numero de elementos es: ".count($matriz)."<br>";
				//var_dump($matriz)		;
				
			}
			

			
			function recorrerMatriz()  {
			// Recorre la matriz con los valores MySQL  inserta los valores en Postgresql
			
				global $res,$matriz;
					
									
					for($pos=0;$pos < mysql_num_rows($res); $pos++)  {
					
					
					$fechacrea= $matriz["fechacrea"][$pos] ;
					$fechacrea = eliminarCaracteresEspeciales($fechacrea);
						if($fechacre == "0000-00-00") {
							$fechacre= "1900-01-01";
						}
					$usucrea= $matriz["usucrea"][$pos] ;
					$usucrea = eliminarCaracteresEspeciales($usucrea);
					$usuario = $usucrea;
					
					
					$fechaini= $matriz["fechaini"][$pos] ;
					$fechaini = eliminarCaracteresEspeciales($fechaini);
						if($fechaini == "0000-00-00") {
							$fechaini= 'NULL';
						}
						
					$fechafin= $matriz["fechafin"][$pos] ;
					$fechafin = eliminarCaracteresEspeciales($fechafin);
						if($fechafin == "0000-00-00") {
							$fechafin= 'NULL' ;
						}
					
					$nofactura =  $matriz["nofactura"][$pos] ;	
					$noliquidacion =  $nofactura ;
					
					$cedula= $matriz["cedula"][$pos] ;	
					
					$vectorservicio =  consultarServicio($cedula, $fechaini, $fechafin);
					
						// Si no existe un servicio asociado se realiza la insercion se realiza con valores nulos
						if (pg_num_rows($vectorservicio) == 0 ){
							$numservicio = 'NULL';
							$medicotte = 'NULL';
							$nocarnet = 'NULL';
							$tipousu = 'NULL';
							$autorizac1 = 'NULL';
							$autorizac2 = 'NULL';
							$autorizac3 = 'NULL';
						}
						
						// Si existe un solo registro asociado se realiza la insercion con la informacion encontrada en la tabla Salud.Servicios
						if (pg_num_rows($vectorservicio) == 1 ){
							$fila = pg_fetch_array($vectorservicio);
							$numservicio = $fila["numservicio"];
							$medicotte = $fila["medicotte"];
							$nocarnet = $fila["nocarnet"];
							$tipousu = $fila["tipousu"];
							$autorizac1 = $fila["autorizac1"];
							$autorizac2 = $fila["autorizac2"];
							$autorizac3 = $fila["autorizac3"];
						}
						
						if (pg_num_rows($vectorservicio) > 1 ){
							$pos = 0;
								while ($fila = pg_fetch_array($vectorservicio)){
									$numservicio = $fila[$pos]["numservicio"];
									$medicotte = $fila[$pos]["medicotte"];
									$nocarnet = $fila[$pos]["nocarnet"];
									$tipousu = $fila[$pos]["tipousu"];
									$autorizac1 = $fila[$pos]["autorizac1"];
									$autorizac2 = $fila[$pos]["autorizac2"];
									$autorizac3 = $fila[$pos]["autorizac3"];
								}
								
							$numservicio = $fila[0]["numservicio"];
							$medicotte = $fila[0]["medicotte"];
							$nocarnet = $fila[0]["nocarnet"];
							$tipousu = $fila[0]["tipousu"];
							$autorizac1 = $fila[0]["autorizac1"];
							$autorizac2 = $fila[0]["autorizac2"];
							$autorizac3 = $fila[0]["autorizac3"];	
								
								
							$fp = fopen("../Liquidacion/ReporteLiquidacion.html", "a+");	
							$errorEjecucion= "<p class='error1'> Posible error de ejecucion: Ambiguedad en la definicion del servicio </p> <br>";
							$consulta= "<p class= 'subtitulo1'> Numero de Factura </p> <br>".$nofactura."<br/> <br/> <br/>";  
							fputs($fp, $errorEjecucion);
							fputs($fp, $consulta);
							fclose($fp);
								
						}
						
						
							
					
					$nombreentidadmysql= $matriz["entidad"][$pos] ;					
					$nombreentidadmysql = eliminarCaracteresEspeciales($nombreentidadmysql);
					
					$entidad = consultarNitEntidadPostgresql($nombreentidadmysql);
					
						if (trim($entidad) == ""){
							$entidad = consultarNitEntidadMySQL($nombreentidadmysql);	
						}
					
					
					// El nombre de la aseguradora en la tabla Salud.Eps (MySQL) no coincide con la tabla Contabilidad.Terceros
					$nombreentidadpostgresql = consultarNombreEntidad($entidad);
					$nombreentidadpostgresql = agregarPuntoFinal($nombreentidadpostgresql);
					
					$pagador = $entidad;
					
					
					$vectorcontrato =  consultarContrato($entidad, $nombreentidadpostgresql);
					$contrato =  $vectorcontrato["contrato"];
					$nocontrato = $vectorcontrato["nocontrato"];
					
					
					$ambito= $matriz["ambito"][$pos] ;
					$ambito = eliminarCaracteresEspeciales($ambito);
					$ambito = normalizarAmbitos($ambito);
					
					$copago= $matriz["copago"][$pos] ;
						if (trim($copago) == '') {
							$copago = 0 ;
						}
					
					$valorcopago = 	$copago;
					
					$descuento= $matriz["descuento"][$pos] ;
						if (trim($descuento) == '') {
							$descuento = 0;
						}
					$valordescuento = 	$descuento;
					
					$total= $matriz["total"][$pos] ;
						if (trim($total) == '') {
							$total = 'NULL';
						}
					
					
					$subtotal = ($total - $copago) - $descuento;
						if (trim($subtotal) == '') {
							$subtotal = 0 ;
						}
						
						$porcentajecopago = 0; // Valor por defecto
						if (trim($valorcopago) != '' and trim($subtotal) != '' and  trim($subtotal) != 'NULL' and $subtotal != 0) {
							$porcentajecopago = (100 * $valorcopago ) / $subtotal;
						} 
						else {
							$porcentajecopago = 0 ;
						}	
						
						$porcentajedesc = 0; // Valor por defecto
						if (trim($valordescuento) != '' and trim($subtotal) != '' and trim($subtotal) != 'NULL' and $subtotal != 0 ) {
							$porcentajedesc = (100 * $valordescuento ) / $subtotal;
						}
						else {
							$porcentajedesc = 0 ;
						}	
	

					$tipocopago = 'NULL';
					
					$clasecopago = 'NULL';
					
					$parto = 'NULL';
					
					$recaudo = 0;
					
					$fechamod = 'NULL';
					
					
					$individual = 1;
					
					$estado = $matriz["estado"][$pos] ;	
					
					$compania= $_SESSION["compania"];
					

						
					//Inserta la liquidacion porque para visualizar facturas se requieren liquidaciones asociadas
					insertarLiquidacion($compania,$fechacrea,$usuario,$fechaini,$fechafin,$contrato, $nocontrato,$ambito,$subtotal,$valorcopago,$valordescuento,$total,$nofactura,$cedula,$numservicio,$medicotte,$nocarnet,$tipousu,$nivelusu,$autorizac1,$autorizac2,$autorizac3,$noliquidacion,$porcentajecopago,$porcentajedesc,$tipocopago,$clasecopago,$pagador,$recaudo,$compcontablerecaudo,$motivonocopago,$tipofactura,$formatofurips,$parto,$phpcrealiq,$phpmodifliq,$estado,$usumod,$fechamod);
					
					insertarFacturasCredito($compania, $fechacrea, $usucrea, $fechaini, $fechafin, $entidad, $contrato, $nocontrato, $ambito, $subtotal, $copago, $descuento, $total, $nofactura, $individual, $estado);
					
									
					}
							
			}
			
			function eliminarFacturasCredito() {
				$cnx= conectar_postgres();
				$cons= "DELETE FROM Facturacion.FacturasCredito";
				$res = @pg_query($cnx, $cons);
				if (!$res) {
							echo "<p class='error1'> Error de ejecucion </p>".pg_last_error()."<br>";
							echo "<p class= 'subtitulo1'>Comando SQL </p> <br>".$cons."<br/>";
							
						}
					
			}
			
			
			
			
		
		
		function migrarFacturasCredito() {
			
			crearArchivo();	
			crearArchivoLiquidacion();
			eliminarTablaMigracionLiquidacion();		
			eliminarTablaMigracion();
			eliminarTablaMigracionLiquidacion();
			crearTablaMigracion();
			crearTablaMigracionLiquidacion();
			eliminarFacturasCredito();
			eliminarLiquidacion();
			llamarRegistrosMySQL();
			llenarMatriz();
			recorrerMatriz();
			normalizarCodificacion('&Aacute;', utf8_encode("Á"));			
			normalizarCodificacion('&Eacute;', utf8_encode("É"));
			normalizarCodificacion('&Iacute;', utf8_encode("Í"));
			normalizarCodificacion('&Oacute;', utf8_encode("Ó"));
			normalizarCodificacion('&Uacute;',utf8_encode("Ú"));
			normalizarCodificacion('&Ntilde;',utf8_encode("Ñ"));
			echo "<div align='center'> <p class='mensajeFinalizacion'>Ha terminado la migracion de la tabla Facturacion.FacturasCredito</p> </div>";
	
		}
		
		
		if($_GET['tabla']=="FacturasCredito") {
		
			echo "<fieldset>";			
			echo "<legend> Migracion Tabla Facturacion.FacturasCredito </legend>";
			echo "<br>";
			echo "<span align='left'> <a href='../../index.php?migracion=MIG066' class = 'link1'> Panel de Administracion </a> </span>";
			echo "<br>";
			
			
			echo "<br/> <br/>  ";
			echo "<span align='right'> <a href='ReporteFacturasCredito.html' target='_blank' class = 'link1'> Ver Reporte de Errores Facturacion.FacturasCredito</a> </span>";			
			
			
			
			echo "<br/> <br/> ";
			echo "<span align='right'> <a href='../Liquidacion/ReporteLiquidacion.html' target='_blank' class = 'link1'> Ver Reporte de Errores Liquidacion</a> </span>";			
			echo "<br/> <br/>";	
			
			migrarFacturasCredito();
			
			echo "</fieldset>";
			
		}
		
		
		
		
		
	
	
	
	?>
