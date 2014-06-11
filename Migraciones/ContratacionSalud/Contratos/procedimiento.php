	<html>	
		<head>
			<title> Migracion ContratacionSalud.Contratos </title>
			<link rel="stylesheet" type="text/css" href="../General/estilos/estilos.css">
		</head>
	
	
	<?php
		session_start();
		include_once('../General/funciones/funciones.php');
		
		
		
		
		/* Inicia defincion de funciones */
		
			
		function  normalizarCodificacionContratos($cadenaBusqueda,$cadenaReemplazo)  {
		// Busca y reemplaza ocurrencias en una tabla
			$cnx= conectar_postgres();
			$cons = "UPDATE ContratacionSalud.Contratos SET compania = replace( compania,'$cadenaBusqueda','$cadenaReemplazo'), contrato = replace( contrato,'$cadenaBusqueda','$cadenaReemplazo'), objeto = replace( objeto,'$cadenaBusqueda','$cadenaReemplazo'), compfacturacion = replace( compfacturacion,'$cadenaBusqueda','$cadenaReemplazo'), comprobantecaja = replace( comprobantecaja,'$cadenaBusqueda','$cadenaReemplazo')";
			
			$res = pg_query($cnx , $cons);
				if (!$res)  {
					echo "<p class='error1'> Error en la normalizacion de la codificacion </p>".pg_last_error()."<br>";
					echo "<p class= 'subtitulo1'>Comando SQL </p> <br>".$cons."<br/> <br/> <br/>"; 
				}

		}
		
		
		
		function  seleccionarTercero($nombre)  {
		// Busca y reemplaza ocurrencias en una tabla
			$cnx= conectar_postgres();
			$nombre = strtoupper($nombre);
			$cons = "SELECT identificacion FROM Central.Terceros WHERE TRIM(UPPER(CONCAT(primape, ' ', segape, ' ', primnom, ' ', segnom))) = '$nombre' LIMIT 1";
			$res = pg_query($cnx , $cons);
				if (!$res)  {
					echo "<p class='error1'> Error de ejecucion </p>".pg_last_error()."<br>";
					echo "<p class= 'subtitulo1'>Comando SQL </p> <br>".$cons."<br/> <br/> <br/>";  
					
				}
				
				if ($res) {
					$fila = pg_fetch_array($res);
					$res = $fila["identificacion"];
					return $res;				
				
				}
	
		}
		
		
		function  seleccionarPlanTarifario($nombre)  {
		// Busca y reemplaza ocurrencias en una tabla
			$cnx= conectar_postgres();
			$nombre = strtoupper($nombre);
			$cons = "SELECT autoid FROM ContratacionSalud.PlanesTarifas WHERE UPPER(nombreplan) = '$nombre' LIMIT 1";
			$res = pg_query($cnx , $cons);
				if (!$res)  {
					echo "<p class='error1'> Error de ejecucion </p>".pg_last_error()."<br>";
					echo "<p class= 'subtitulo1'>Comando SQL </p> <br>".$cons."<br/> <br/> <br/>";  
					
				}
				
				if ($res) {
					$fila = pg_fetch_array($res);
					$res = $fila["autoid"];
					return $res;				
				
				}
		
		}
		
		
			function  seleccionarPlanServicio($nombre)  {
		// Busca y reemplaza ocurrencias en una tabla
			$cnx= conectar_postgres();
			$nombre = strtoupper($nombre);
			$cons = "SELECT autoid FROM ContratacionSalud.PlaneServicios WHERE UPPER(nombreplan) = '$nombre' LIMIT 1";
			$res = pg_query($cnx , $cons);
				if (!$res)  {
					echo "<p class='error1'> Error de ejecucion </p>".pg_last_error()."<br>";
					echo "<p class= 'subtitulo1'>Comando SQL </p> <br>".$cons."<br/> <br/> <br/>";  
					
				}
				
				if ($res) {
					$fila = pg_fetch_array($res);
					$res = $fila["autoid"];
					return $res;				
				
				}
		
		}
		
		
		function  seleccionarNombreCuenta($numero)  {
		// Busca y reemplaza ocurrencias en una tabla
			$cnx= conectar_postgres();
			$nombre = strtoupper($nombre);
			$cons = "SELECT nombre FROM Contabilidad.PlanCuentas WHERE cuenta = '$numero' LIMIT 1";
			$res = pg_query($cnx , $cons);
				if (!$res)  {
					echo "<p class='error1'> Error de ejecucion </p>".pg_last_error()."<br>";
					echo "<p class= 'subtitulo1'>Comando SQL </p> <br>".$cons."<br/> <br/> <br/>";  
					
				}
				
				if ($res) {
					$fila = pg_fetch_array($res);
					$res = $fila["nombre"];
					return $res;				
				
				}
	
		}

				
	
		function llamarRegistrosMySQLContratos() {
			// Selecciona los registros MySQL (Origen)
			global $res;
			$cnx = conectar_mysql("Facturacion");
			$cons = "SELECT Salud.EPS.*, Facturacion.Contratos.* ,salud.EPS.AutoId AS autoideps FROM Salud.EPS, Facturacion.Contratos WHERE UPPER(Salud.EPS.Nombre) = UPPER(Facturacion.Contratos.Entidad)";
			$res =  mysql_query($cons);
			return $res; 
		
		}
		
		

		
		function insertarContrato($compania, $entidad, $contrato,$numero, $fechaini, $fechafin, $tipocontrato, $plantarifario,$planbeneficios, $plantarifameds,   $monto , $estado, $ultdia, $primdia, $cuotamoderadora,  $cuentacont, $nomcuenta, $copago) {
		//Realiza la insercion en Postgresql con base en los parametros
			
			$cnx = 	conectar_postgres();
			$cons = "INSERT INTO ContratacionSalud.Contratos (compania, entidad, contrato,numero, fechaini, fechafin, tipocontrato, plantarifario, planbeneficios, plantarifameds,  monto , estado, ultdia, primdia, cuotamod,  cuentacont, nomcuenta, copago ) VALUES ('$compania', '$entidad', '$contrato','$numero', '$fechaini', '$fechafin', '$tipocontrato', '$plantarifario', '$planbeneficios', '$plantarifameds',  $monto, '$estado', $ultdia, $primdia, $cuotamoderadora,  '$cuentacont', '$nomcuenta', '$copago')"	;
					 
			$cons = str_replace( "'NULL'","NULL",$cons  );	
			$res = @pg_query($cnx, $cons);
				if (!$res) {
					$consUTF8 = utf8_encode($cons);
					$resUTF8 = @pg_query($cnx, $consUTF8);					
						if (!$resUTF8) {
							$fp = fopen("Errores/ContratacionSalud.html", "a+");	
							$errorEjecucion= "<p class='error1'> Error de ejecucion </p>".pg_last_error()."<br>";
							$consulta= "<p class= 'subtitulo1'>Comando SQL </p> <br>".$consUTF8."<br/> <br/> <br/>";  
							fputs($fp, $errorEjecucion);
							fputs($fp, $consulta);
							fclose($fp);
							
							
						}
				
				}

				
		}
		
		
		function  llenarMatrizContratos(){
		// Llena una matriz con el resultado de la consulta MySQL
			
			unset($matriz); 
			global  $matriz;	
			$res = llamarRegistrosMySQLContratos();
			$posicion=0;
				
				while ($fila = mysql_fetch_array($res))
				{	
					
					$matriz["entidad"][$posicion] = $fila["Entidad"];
					$matriz["nit"][$posicion] = $fila["Nit"];					
					$matriz["numerocontrato"][$posicion] = $fila["No"];					
					$matriz["fechaini"][$posicion] = $fila["FechaInicio"];
					$matriz["fechafin"][$posicion] = $fila["FechaFin"];	
					$matriz["evento"][$posicion] = $fila["Evento"];	
					$matriz["estado"][$posicion] = $fila["Estado"];										
					$matriz["plantarifario"][$posicion] = $fila["TarifaMeds"];	
					$matriz["monto"][$posicion] = $fila["VrTotal"];	
					$matriz["monto"][$posicion] = $fila["VrTotal"];	
					$matriz["ultdia"][$posicion] = $fila["PagUltDia"];	
					$matriz["primdia"][$posicion] = $fila["PagPrimDia"];
					$matriz["cuentacont"][$posicion] = $fila["Cuenta"];	
					$matriz["copago"][$posicion] = $fila["Copago"];
					$matriz["plantarifario"][$posicion] = $fila["autoideps"];	
					$matriz["planbeneficios"][$posicion] = $fila["autoideps"];	
					$matriz["cuotamoderadora"][$posicion] = $fila["CuotaMod"];
					$matriz["plantarifameds"][$posicion] = $fila["TarifaMeds"];		
													
					$posicion++;				
				}
							
				
			}
			

			
			function recorrerMatrizContratos()  {
			// Recorre la matriz con los valores MySQL  inserta los valores en Postgresql
			
				global $res,$matriz;
					for($pos=0;$pos < mysql_num_rows($res); $pos++)  {
					
					$compania= $_SESSION["compania"];
					
					$nombreEntidad= $matriz["entidad"][$pos] ;

					$entidad =  $matriz["nit"][$pos];					
					
					$contrato= "CONTRATO ".$nombreEntidad ;
					$contrato = eliminarCaracteresEspeciales($contrato);
					
					$numero = $matriz["numerocontrato"][$pos] ;
					
					$fechaini= $matriz["fechaini"][$pos] ;

					$fechafin= $matriz["fechafin"][$pos] ;
					
					$evento = $matriz["evento"][$pos] ;
						if ($evento == 1) {
							$tipocontrato = "Evento";
						}
						
						if ($evento != 1) {
							$tipocontrato = "Capita";
						}
					
					

					//$plantarifario = seleccionarPlanTarifario($nombreEntidad) ;
					$plantarifario= $matriz["plantarifario"][$pos] ;
					$plantarifario = str_replace("A","",$plantarifario);
					
					
					//$planbeneficios  = seleccionarPlanServicio($nombreEntidad)  ;
					$planbeneficios= $matriz["planbeneficios"][$pos] ;
					$planbeneficios = str_replace("A","",$planbeneficios);
					
					$monto= $matriz["monto"][$pos] ;
					
					$estado =  $matriz["estado"][$pos] ;
						if ($estado == "A"){
							$estado = "AC";
						}
						elseif  ($estado != "A"){
							$estado = "AN";
						}
					
					$plantarifameds = $matriz["plantarifameds"][$pos];
					$plantarifameds = strtoupper($plantarifameds);
					
					
					$ultdia= $matriz["ultdia"][$pos] ;
						if ($ultdia == "S"){
							$ultdia = 1;
						}
						elseif  ($ultdia == "N"){
							$ultdia = 0;
						}
						
					
					$primdia= $matriz["primdia"][$pos] ;
						if ($primdia == "S"){
							$primdia = 1;
						}
						elseif  ($primdia == "N"){
							$primdia = 0;
						}	
						
						$cuotamoderadora= $matriz["cuotamoderadora"][$pos] ;
						if ($cuotamoderadora == "S"){
							$cuotamoderadora = 1;
						}
						elseif  ($cuotamoderadora == "N"){
							$cuotamoderadora = 0;
						}
						
					
					$cuentacont =  $matriz["cuentacont"][$pos] ;
					
					$nomcuenta = seleccionarNombreCuenta($cuentacont);				
						
					
					$copago= $matriz["copago"][$pos] ;
						if ($copago == "S"){
							$copago = 1;
						}
						elseif  ($copago == "N"){
							$copago = 0;
						}	
					
					
					
					insertarContrato($compania, $entidad, $contrato,$numero, $fechaini, $fechafin, $tipocontrato, $plantarifario,$planbeneficios, $plantarifameds,  $monto , $estado, $ultdia, $primdia, $cuotamoderadora,  $cuentacont, $nomcuenta, $copago);
					
									
					}
			
			}
			
			function eliminarContratos() {
				$cnx= conectar_postgres();
				$cons= "DELETE FROM ContratacionSalud.Contratos";
				$res = @pg_query($cnx, $cons);
				if (!$res) {
							echo "<p class='error1'> Error de ejecucion </p>".pg_last_error()."<br>";
							echo "<p class= 'subtitulo1'>Comando SQL </p> <br>".$cons."<br/>";
							
						}
					
			}
			
			function actualizarContratos() {
				$cnx= conectar_postgres();
				$cons= "UPDATE ContratacionSalud.Contratos SET mttoejecutado = 0, consumcontra= 0";
				$res = @pg_query($cnx, $cons);
				if (!$res) {
							echo "<p class='error1'> Error de ejecucion </p>".pg_last_error()."<br>";
							echo "<p class= 'subtitulo1'>Comando SQL </p> <br>".$cons."<br/>";
							
						}
					
			}
			
			function insertarContratosCSV(){
				$cnx = conectar_postgres();
				$ruta = $_SERVER['DOCUMENT_ROOT'];
				$cons= "COPY ContratacionSalud.Contratos FROM '$ruta/Migraciones/ContratacionSalud/Contratos/Contratos.csv' WITH DELIMITER ';' CSV HEADER;";
				$res =  @pg_query($cons);
				
					if (!$res) {
						echo "<p class='error1'> Error de ejecucion </p>".pg_last_error()."<br>";
						echo "<p class= 'subtitulo1'>Comando SQL </p> <br>".$cons."<br/>";
							
					}
			
			}
			
			
			
			
		
		
		function migrarContratos($paso) {
		
			eliminarContratos();
			//llamarRegistrosMySQLContratos();
			//llenarMatrizContratos();
			//recorrerMatrizContratos();
			insertarContratosCSV();
			normalizarCodificacionContratos('&Aacute;', utf8_encode("Á"));			
			normalizarCodificacionContratos('&Eacute;', utf8_encode("É"));
			normalizarCodificacionContratos('&Iacute;', utf8_encode("Í"));
			normalizarCodificacionContratos('&Oacute;', utf8_encode("Ó"));
			normalizarCodificacionContratos('&Uacute;',utf8_encode("Ú"));
			normalizarCodificacionContratos('&Ntilde;',utf8_encode("Ñ"));
			actualizarContratos();
			echo "<p class='mensajeEjecucion'> <span class = 'subtitulo1'>Paso $paso: </span> Se ha migrado la tabla ContratacionSalud.Contratos </p> ";
	
		}
		
		
		
		
		
	
	
	
	?>
