	<html>	
		<head>
			<title> Migracion Farmacia </title>
			<link rel="stylesheet" type="text/css" href="../../General/estilos/estilos.css">
		</head>
	
	
	<?php
	
		session_start();
		include_once '../../Conexiones/conexion.php';
		include_once('../../General/funciones/funciones.php');
		include_once('../TarifariosVenta/procedimiento.php');
		include_once('../CumsxProducto/procedimiento.php');
		include_once('../TarifasxProducto/procedimiento.php');
		include_once('../SaldosInicialesxAnio/procedimiento.php');
		
		
		
		
		
		
		/* Inicia definicion de funciones */
		
		function contarRegistrosMySQLCodMed2011() {
			$cnx = conectar_mysql("Contabilidad");
			$cons = "SELECT COUNT(*) AS conteoMySQL FROM Salud.Codmedicamentos2011";
			$res =  mysql_query($cons);
			$fila = mysql_fetch_array($res);
			$res = $fila['conteoMySQL'];
			return $res; 	
		
		}
		
						
		
		
		function  funcion0005CodMed2011($cadenaBusqueda,$cadenaReemplazo)  {
		// Busca y reemplaza ocurrencias en una tabla
			$cnx= conectar_postgres();
			$cons = "UPDATE Consumo.codproductos SET bodega = replace( bodega,'". $cadenaBusqueda."'".",'". $cadenaReemplazo."'),nombreprod1 = replace( nombreprod1,'". $cadenaBusqueda."'".",'". $cadenaReemplazo."'),presentacion = replace( presentacion,'". $cadenaBusqueda."'".",'". $cadenaReemplazo."'),unidadmedida = replace( unidadmedida,'". $cadenaBusqueda."'".",'". $cadenaReemplazo."'),codigo1 = replace( codigo1,'". $cadenaBusqueda."'".",'". $cadenaReemplazo."') , grupo = replace( grupo,'". $cadenaBusqueda."'".",'". $cadenaReemplazo."') , estante = replace( estante ,'". $cadenaBusqueda."'".",'". $cadenaReemplazo."'),nivel = replace( nivel,'". $cadenaBusqueda."'".",'". $cadenaReemplazo."'),estado = replace( estado,'". $cadenaBusqueda."'".",'". $cadenaReemplazo."')
			,compania = replace( compania,'". $cadenaBusqueda."'".",'". $cadenaReemplazo."') , almacenppal = replace( almacenppal,'". $cadenaBusqueda."'".",'". $cadenaReemplazo."')";
			
			$res = @pg_query($cnx , $cons);
				if (!$res)  {
						
					$fp = fopen("ReporteFarmacia.html", "a+");	
					$errorEjecucion= "<p class='error1'> Error de ejecucion </p>".pg_last_error()."<br>";
					$consulta= "<p class= 'subtitulo1'>Comando SQL </p> <br>".$consUTF8."<br/> <br/> <br/>";  
					fputs($fp, $errorEjecucion);
					fputs($fp, $consulta);
					fclose($fp);			
					
				}

		}
		
		function  funcion0006CodMed2011($cadenaBusqueda,$cadenaReemplazo)  {
		// Busca y reemplaza ocurrencias en una tabla
			$cnx= conectar_postgres();
			$cons = "UPDATE Consumo.almacenesppales SET almacenppal = replace( almacenppal,'". $cadenaBusqueda."'".",'". $cadenaReemplazo."')";
					
			$res = pg_query($cnx , $cons);
				if (!$res)  {
					echo "<p class='error1'> Error en la normalizacion de la codificacion </p>".pg_last_error()."<br>";
					echo "<p> <span class='subtitulo1'> Consulta SQL : </span>".$cons."</p> <br>";
				}

		}
		
		function  funcion0007CodMed2011($cadenaBusqueda,$cadenaReemplazo)  {
		// Busca y reemplaza ocurrencias en una tabla
			$cnx= conectar_postgres();
			$cons = "UPDATE Consumo.bodegas SET bodega = replace( bodega,'". $cadenaBusqueda."'".",'". $cadenaReemplazo."')";
					
			$res = @pg_query($cnx , $cons);
				if (!$res)  {
					echo "<p class='error1'> Error en la normalizacion de la codificacion </p>".pg_last_error()."<br>";
					echo "<p> <span class='subtitulo1'> Consulta SQL : </span>".$cons."</p> <br>";
				}

		}
		
		function  funcion0008CodMed2011($cadenaBusqueda,$cadenaReemplazo)  {
		// Busca y reemplaza ocurrencias en una tabla
			$cnx= conectar_postgres();
			$cons = "UPDATE Consumo.tiposproducto SET tipoproducto = replace( tipoproducto,'". $cadenaBusqueda."'".",'". $cadenaReemplazo."')";
					
			$res = pg_query($cnx , $cons);
				if (!$res)  {
					echo "<p class='error1'> Error en la normalizacion de la codificacion </p>".pg_last_error()."<br>";
					echo "<p> <span class='subtitulo1'> Consulta SQL : </span>".$cons."</p> <br>";
				}

		}
		
		
		
		
		
		
				
	
		function funcion0010CodMed2011() {
			// Selecciona los registros MySQL (Origen)
			global $res;
			$cnx = conectar_mysql("Salud");
			$cons = "SELECT *  FROM Salud.codmedicamentos2011";
			$res =  mysql_query($cons);
			return $res; 
		
		}
		
		

		

		
		function  funcion0012CodMed2011(){
		// Llena una matriz con el resultado de la consulta MySQL
			
			unset($matriz); 
			global  $matriz;	
			$res = funcion0010CodMed2011();
			$posicion=0;
				
				while ($fila = mysql_fetch_array($res))
				{	
					$matriz["autoid"][$posicion] = $fila["AutoId"];
					$matriz["codigo"][$posicion] = $fila["CodPos"];
					$matriz["nombreprod1"][$posicion] = $fila["Generico"];
					$matriz["unidadmedida"][$posicion] = $fila["Presentacion"];
					$matriz["presentacion"][$posicion] = $fila["FormaFarma"];
					$matriz["grupo"][$posicion] = $fila["TipoMd"];
					$matriz["usuariocre"][$posicion] = $fila["Usuario"];
					$matriz["fechacre"][$posicion] = $fila["Fecha"];
					$mes = 1;
					$saldoMes = "SaldoIni".$mes;
					$costoMes = "PCosto".$mes;				
					$matriz["cantidad"][$posicion] = $fila["$saldoMes"];
					$matriz["vrunidad"][$posicion] = $fila["$costoMes"];
					$matriz["cum"][$posicion] = $fila["CUM"];
					$matriz["farmaprecios"][$posicion] = $fila["Farmaprecios"];
					$matriz["codesca"][$posicion] = $fila["Codesca"];
					$matriz["saludtotal"][$posicion] = $fila["SaludTotal"];					
					$matriz["epsifarma"][$posicion] = $fila["EPSifarma"];
					$matriz["nuevaeps"][$posicion] = $fila["NuevaEPS"];		
					//$matriz["institucional"][$posicion] = $fila["Institucional"];
					//$matriz["gruposanitas"][$posicion] = $fila[61];
					// Se toma como referencia el numero de campo porque el nombre del campo puede generar Confusiones 																			
					
					
					
					$posicion++;				
				}
							
				
			}
			

			
			function funcion0013CodMed2011()  {
			// Recorre la matriz con los valores MySQL  inserta los valores en Postgresql
			
				global $res,$matriz;
					for($pos=0;$pos < mysql_num_rows($res); $pos++)  {
					
					
					$autoid= $matriz["autoid"][$pos] ;
					$compania= "CLINICA SAN JUAN DE DIOS";
					$almacenppal = "FARMACIA";
					/*$codigo1= $matriz["codigo"][$pos] ;
					$codigo1 = eliminarCaracteresEspeciales($codigo1);*/
					$codigo1 =  $autoid;
					$nombreprod1= $matriz["nombreprod1"][$pos] ;
					$nombreprod1 = eliminarCaracteresEspeciales($nombreprod1);
					$unidadmedida= $matriz["unidadmedida"][$pos] ;
					$unidadmedida = eliminarCaracteresEspeciales($unidadmedida);
					$presentacion= $matriz["presentacion"][$pos] ;
					$presentacion = eliminarCaracteresEspeciales($presentacion);
					$tipoproducto= "UNIDOSIS";
					$grupo= $matriz["grupo"][$pos] ;
					$grupo = normalizarGruposConsumo($grupo);
					$grupo = eliminarCaracteresEspeciales($grupo);
					$bodega = "PRINCIPAL";
					$estante = "A";
					$nivel= $pos +1;
					$usuariocre= $matriz["usuariocre"][$pos] ;
					$usuariocre = eliminarCaracteresEspeciales($usuariocre);
					$fechacre= $matriz["fechacre"][$pos] ;
					$fechacre = eliminarCaracteresEspeciales($fechacre);
						if($fechacre == "0000-00-00 00:00:00") {
							$fechacre= "NULL";
						}
					$estado = "AC";
					$maximo = 10;
					$minimo= 1;
					$cantidad = $matriz["cantidad"][$pos] ;
						if (!(isset($cantidad))) {
							$cantidad = 0;
						}
					$vrunidad = $matriz["vrunidad"][$pos] ;
						if (!(isset($vrunidad))) {
							$vrunidad = 0;
						}
					$vrtotal =  $cantidad * $vrunidad;
					$farmaprecios = $matriz["farmaprecios"][$pos] ;
					$codesca = $matriz["codesca"][$pos] ;					
					$saludtotal = $matriz["saludtotal"][$pos] ;										
					$epsifarma = $matriz["epsifarma"][$pos] ;
					$nuevaeps = $matriz["nuevaeps"][$pos] ;
					//$institucional = $matriz["institucional"][$pos] ;
					//$gruposanitas = $matriz["gruposanitas"][$pos] ;
					//$cum= $matriz["cum"][$pos] ;
					$cum= $matriz["codigo"][$pos] ;
					$laboratorio = "POR VALIDAR";
					
					$fechaini= "2013-01-01";
					$fechafin = "2013-12-31";
					$anio = "2011";
					


					
					

					insertarTarifasxProducto($compania, $almacenppal, "FARMAPRECIOS", $autoid, $fechaini, $fechafin, $farmaprecios, $anio);
					insertarTarifasxProducto($compania, $almacenppal, "CODESCA", $autoid, $fechaini, $fechafin, $codesca, $anio);
					insertarTarifasxProducto($compania, $almacenppal, "SALUDTOTAL", $autoid, $fechaini, $fechafin, $saludtotal, $anio);					
					insertarTarifasxProducto($compania, $almacenppal, "EPSIFARMA", $autoid, $fechaini, $fechafin, $epsifarma, $anio);
					insertarTarifasxProducto($compania, $almacenppal, "NUEVA EPS", $autoid, $fechaini, $fechafin, $nuevaeps, $anio);	
					//insertarTarifasxProducto($compania, $almacenppal, "INSTITUCIONAL", $autoid, $fechaini, $fechafin, $institucional, $anio);										
					//insertarTarifasxProducto($compania, $almacenppal, "GRUPO SANITAS", $autoid, $fechaini, $fechafin, $gruposanitas, $anio);
					//insertarSaldosInicialesxAnio($compania, $almacenppal, $autoid, $anio, $cantidad, $vrunidad, $vrtotal);	
					}
			
			}
			
			
						
			
			
		
		
		function migrarCodMed2011() {
			
			
			
			// Tabla Consumo.almacenesppales
			funcion0006CodMed2011(utf8_encode("Á"),'&Aacute;');			
			funcion0006CodMed2011(utf8_encode("É"),'&Eacute;');
			funcion0006CodMed2011(utf8_encode("Í"),'&Iacute;');
			funcion0006CodMed2011(utf8_encode("Ó"),'&Oacute;');
			funcion0006CodMed2011(utf8_encode("Ú"),'&Uacute;');
			funcion0006CodMed2011(utf8_encode("Ñ"),'&Ntilde;');
			
			
			// Tabla Consumo.bodegas
			funcion0007CodMed2011(utf8_encode("Á"),'&Aacute;');			
			funcion0007CodMed2011(utf8_encode("É"),'&Eacute;');
			funcion0007CodMed2011(utf8_encode("Í"),'&Iacute;');
			funcion0007CodMed2011(utf8_encode("Ó"),'&Oacute;');
			funcion0007CodMed2011(utf8_encode("Ú"),'&Uacute;');
			funcion0007CodMed2011(utf8_encode("Ñ"),'&Ntilde;');
			
			// Tabla Consumo.TiposProducto
			funcion0008CodMed2011(utf8_encode("Á"),'&Aacute;');			
			funcion0008CodMed2011(utf8_encode("É"),'&Eacute;');
			funcion0008CodMed2011(utf8_encode("Í"),'&Iacute;');
			funcion0008CodMed2011(utf8_encode("Ó"),'&Oacute;');
			funcion0008CodMed2011(utf8_encode("Ú"),'&Uacute;');
			funcion0008CodMed2011(utf8_encode("Ñ"),'&Ntilde;');
			
			
			
			
			
			funcion0010CodMed2011();
			funcion0012CodMed2011();
			funcion0013CodMed2011();
			
			

			
			
			// Tabla Consumo.almacenesppales
			funcion0006CodMed2011('&Aacute;', utf8_encode("Á"));			
			funcion0006CodMed2011('&Eacute;', utf8_encode("É"));
			funcion0006CodMed2011('&Iacute;', utf8_encode("Í"));
			funcion0006CodMed2011('&Oacute;', utf8_encode("Ó"));
			funcion0006CodMed2011('&Uacute;',utf8_encode("Ú"));
			funcion0006CodMed2011('&Ntilde;',utf8_encode("Ñ"));
			
			// Tabla Consumo.bodegas
			funcion0007CodMed2011('&Aacute;', utf8_encode("Á"));			
			funcion0007CodMed2011('&Eacute;', utf8_encode("É"));
			funcion0007CodMed2011('&Iacute;', utf8_encode("Í"));
			funcion0007CodMed2011('&Oacute;', utf8_encode("Ó"));
			funcion0007CodMed2011('&Uacute;',utf8_encode("Ú"));
			funcion0007CodMed2011('&Ntilde;',utf8_encode("Ñ"));
			
			// Tabla Consumo.tiposproducto
			funcion0008CodMed2011('&Aacute;', utf8_encode("Á"));			
			funcion0008CodMed2011('&Eacute;', utf8_encode("É"));
			funcion0008CodMed2011('&Iacute;', utf8_encode("Í"));
			funcion0008CodMed2011('&Oacute;', utf8_encode("Ó"));
			funcion0008CodMed2011('&Uacute;',utf8_encode("Ú"));
			funcion0008CodMed2011('&Ntilde;',utf8_encode("Ñ"));
			
			
			
		    // Tabla Consumo.codproductos
			funcion0005CodMed2011('&Aacute;', utf8_encode("Á"));			
			funcion0005CodMed2011('&Eacute;', utf8_encode("É"));
			funcion0005CodMed2011('&Iacute;', utf8_encode("Í"));
			funcion0005CodMed2011('&Oacute;', utf8_encode("Ó"));
			funcion0005CodMed2011('&Uacute;',utf8_encode("Ú"));
			funcion0005CodMed2011('&Ntilde;',utf8_encode("Ñ"));
			
	
			
	
		}
		
		
		

		
		
		
	
	
	
	?>
