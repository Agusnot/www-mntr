	<html>
		<head>
			<title> Migracion Contabilidad.Movimiento </title> 
			<link rel="stylesheet" type="text/css" href="../General/estilos/estilos.css">
			<meta charset="UTF-8">
		</head>
	</html>	
	


<?php
	session_start();
	include_once '../../Conexiones/conexion.php';
	include_once('../../General/funciones/funciones.php');

	/* Inicia la definicion de funciones */
	
	
		
		function contarRegistrosMySQL() {
			$cnx = conectar_mysql("Contabilidad");
			$cons = "SELECT COUNT(*) AS conteoMySQL FROM Contabilidad.movimiento";
			$res =  mysql_query($cons);
			$fila = mysql_fetch_array($res);
			$res = $fila['conteoMySQL'];
			return $res; 	
		
		}
		
		
		function contarRegistrosPostgresql() {
			$cnx= conectar_postgres();
			$cons = "SELECT COUNT(*) AS conteo FROM Contabilidad.movimiento";
			$res =  pg_query($cnx, $cons);
			$fila = pg_fetch_array($res);
			$res = $fila['conteo'];
			return $res; 	
		
		}
		
		function contarRegistrosPostgresqlErrores() {
			$cnx= conectar_postgres();
			$cons = "SELECT COUNT(*) AS conteo FROM Contabilidad.movimientoMigracion";
			$res =  pg_query($cnx, $cons);
			$fila = pg_fetch_array($res);
			$res = $fila['conteo'];
			return $res; 	
		
		}
		
		
		
		function  reemplazarTextoTabla1($cadenaBusqueda,$cadenaReemplazo)  {
			$cnx= conectar_postgres();
			$cons = "UPDATE Contabilidad.comprobantes SET comprobante = replace( comprobante,'$cadenaBusqueda','$cadenaReemplazo')";
			
			$res = pg_query($cnx , $cons);
				if (!$res)  {
					echo "<p class='error1'> Error en la normalizacion de la codificacion </p>".pg_last_error()."<br>";
				}

		}
		
		function  reemplazarTextoTabla2($cadenaBusqueda,$cadenaReemplazo)  {
			$cnx= conectar_postgres();
			$cons = "UPDATE Contabilidad.formaspago SET forma = replace( forma,'$cadenaBusqueda','$cadenaReemplazo')";
			
			$res = pg_query($cnx , $cons);
				if (!$res)  {
					echo "<p class='error1'> Error en la normalizacion de la codificacion </p>".pg_last_error()."<br>";
				}

		}
		
		function  reemplazarTextoTabla3($cadenaBusqueda,$cadenaReemplazo)  {
			$cnx= conectar_postgres();
			$cons = "UPDATE Contabilidad.tipospago SET tipo = replace( tipo,'$cadenaBusqueda','$cadenaReemplazo')";
			
			$res = pg_query($cnx , $cons);
				if (!$res)  {
					echo "<p class='error1'> Error en la normalizacion de la codificacion </p>".pg_last_error()."<br>";
				}

		}
		
		
		function  reemplazarTextoTabla4($cadenaBusqueda,$cadenaReemplazo)  {
			$cnx= conectar_postgres();
			$cons = "UPDATE Contabilidad.clasespago SET clase = replace( clase,'$cadenaBusqueda','$cadenaReemplazo')";
			
			$res = pg_query($cnx , $cons);
				if (!$res)  {
					echo "<p class='error1'> Error en la normalizacion de la codificacion </p>".pg_last_error()."<br>";
				}

		}
		
		
		
		
		
		
		
		function  reemplazarTextoTabla5($cadenaBusqueda,$cadenaReemplazo)  {
			$cnx= conectar_postgres();
			$cons = "UPDATE Contabilidad.movimiento SET comprobante = replace( comprobante,'". $cadenaBusqueda."'".",'". $cadenaReemplazo."'"."), numero = replace( numero,'". $cadenaBusqueda."'".",'". $cadenaReemplazo."'"."), identificacion = replace( identificacion,'". $cadenaBusqueda."'".",'". $cadenaReemplazo."'".") , detalle = replace( detalle,'". $cadenaBusqueda."'".",'". $cadenaReemplazo."'".") , cuenta  = replace( cuenta,'". $cadenaBusqueda."'".",'". $cadenaReemplazo."'".") , cc = replace( cc,'". $cadenaBusqueda."'".",'". $cadenaReemplazo."'".") , docsoporte = replace( docsoporte,'". $cadenaBusqueda."'".",'". $cadenaReemplazo."'".") , compania = replace( compania,'". $cadenaBusqueda."'".",'". $cadenaReemplazo."'".") , usuariocre = replace( usuariocre,'". $cadenaBusqueda."'".",'". $cadenaReemplazo."'"."), modificadox = replace( modificadox,'". $cadenaBusqueda."'".",'". $cadenaReemplazo."'".") , cerrado = replace( cerrado,'". $cadenaBusqueda."'".",'". $cadenaReemplazo."'".") , banco = replace( banco,'". $cadenaBusqueda."'".",'". $cadenaReemplazo."'".") , docdestino = replace( docdestino,'". $cadenaBusqueda."'".",'". $cadenaReemplazo."'".") , conceptorte = replace( conceptorte,'". $cadenaBusqueda."'".",'". $cadenaReemplazo."'".") , porcretenido = replace( porcretenido,'". $cadenaBusqueda."'".",'". $cadenaReemplazo."'"."), bancorecrec = replace( bancorecrec,'". $cadenaBusqueda."'".",'". $cadenaReemplazo."'".") , noresolucion = replace( noresolucion,'". $cadenaBusqueda."'".",'". $cadenaReemplazo."'".")";
			$res = pg_query($cnx , $cons);
				if (!$res)  {
					echo "<p class='error1'> Error en la normalizacion de la codificacion </p>".pg_last_error()."<br>";
				}

		}
		
		
			
		
	
		function llamarMovimientoMySQL($limiteIni, $limiteFin) {
			// El limite inicial y final se usan para una inmensa cantidad de registros
			global $res;
			$cnx = conectar_mysql("Contabilidad");
			$cons = "SELECT *  FROM Contabilidad.movimiento ORDER BY Fecha ASC, Comprobante ASC, Numero ASC, Identificacion ASC LIMIT ".$limiteIni. " , ".$limiteFin;
			$res =  mysql_query($cons);
			return $res; 
		
		}
		
		function eliminarTablaMigracion() {
			// Elimina la tabla Contabilidad.movimientoMigracion 
			$cnx= conectar_postgres();
			$cons = "DROP TABLE  IF EXISTS Contabilidad.movimientomigracion";
			$res =  pg_query($cons);
			
		}
		
		
		function crearTablaMigracion() {
		// Esta funcion crea una tabla con estructura similar a la tabla Contabilidad.movimiento, con la diferencia que carece de llave primaria y omite la restriccion NOT NULL,          ademas  crea un nuevo campo llamado error en el cual se almacena la descripcion del error generado en la consulta inicial.
			$cnx= conectar_postgres();
			$cons = "CREATE TABLE contabilidad.movimientoMigracion(  autoid integer  ,  fecha date ,  comprobante character varying(50) ,  numero character varying(100)  ,
				  identificacion character varying(30) ,  detalle text,  cuenta character varying(200),debe double precision ,  haber double precision ,  cc character varying(20),  docsoporte character varying(30),  basegravable double   precision ,  compania character varying(200) ,  usuariocre character varying(200),  fechacre timestamp without time zone,  modificadox  character   varying(200),  fechamod timestamp without time zone,  cerrado character varying(1),  formapago character varying(100),  nocheque integer ,  banco character varying(200),  diasvencimiento integer ,  estado character varying(2) ,  docdestino character  varying(200),  conceptorte character varying(255),   porcretenido character varying(200),  tipopago character varying(200),  clasepago character varying(200),  bancorecrec character varying(200),  fechadocumento   date,  anio integer,  fechaconciliado date,  cierrexcajero date,  mesesvenc integer ,  noresolucion character varying(30),  error text )WITH (   
	 OIDS=FALSE)";
	 		
			$res = @pg_query($cnx, $cons);
				if (!$res) {
				
					echo "<p class='error1'> Error de ejecucion </p>".pg_last_error()."<br>";
					echo "<p class= 'subtitulo1'>Comando SQL </p> <br>".$cons."<br>"; 				
					echo "<br><br>";			
					//echo "<br><br>";
				}
			
		}
		
		
		
		function insertarRegistroMigracion($autoid, $fecha,$comprobante, $numero, $identificacion, $detalle,  $cuenta, $debe, $haber, $cc, $docsoporte, $basegravable, $compania, $usuariocre, $fechacre,  $modificadopor, $fechamod, $cerrado, $formapago, $nocheque, $banco, $diasvencimiento, $estado, $docdestino, $conceptorte, $porcretenido, $tipopago, $clasepago, $bancorecrec, $fechadocumento, $cierrexcajero, $fechaconciliado, $error) {
			
			$cnx = 	conectar_postgres();
			$cons = "INSERT INTO Contabilidad.movimientomigracion (autoid, fecha,comprobante, numero, identificacion, detalle,  cuenta, debe, haber, cc, docsoporte, basegravable, compania, usuariocre, fechacre,  modificadox, fechamod, cerrado, formapago, nocheque, banco, diasvencimiento, estado, docdestino, conceptorte, porcretenido, tipopago, clasepago, bancorecrec, fechadocumento, cierrexcajero, fechaconciliado, error) VALUES ('".$autoid."','".$fecha."','".$comprobante."',".$numero.",'".$identificacion."','".$detalle."','".$cuenta."',".$debe.",'".$haber."','".$cc."','".$docsoporte."',".$basegravable.",'".$compania."','".$usuariocre."','".$fechacre."','".$modificadopor."','".$fechamod."','".$cerrado."','".$formapago."',".$nocheque.",'".$banco."',".$diasvencimiento.",'".$estado."','".$docdestino."','".$conceptorte."','".$porcretenido."','".$tipopago."','".$clasepago."','".$bancorecrec."','".$fechadocumento."','".$cierrexcajero."','".$fechaconciliado."','".$error."')"	;
					 
			$cons = str_replace( "'NULL'","NULL",$cons  );	
			$res = @pg_query($cnx, $cons);
				if(!$res) {
					$consUTF8 = utf8_encode($cons);
					$resUTF8 = @pg_query($cnx, $consUTF8);
						if (!$resUTF8) {
							
							$fp = fopen("ReporteErrores.html", "a+");	
							$errorEjecucion= "<p class='error1'> Error de ejecucion </p>".pg_last_error()."<br>";
							$consulta= "<p class= 'subtitulo1'>Comando SQL </p> <br>".$consUTF8."<br/> <br/> <br/>";  
							fputs($fp, $errorEjecucion);
							fputs($fp, $consulta);
							fclose($fp);
							
						}
				}	
		}	
		
		function crearArchivo() {
			$fp = fopen("ReporteErrores.html", "w+");
			$encabezado = "<html> <head> <title> Reporte errores Contabilidad.movimiento </title> 
			<link rel='stylesheet' type='text/css' href='../General/estilos/estilos.css'> </head>";
			fputs($fp, $encabezado);
			fclose($fp);
		}	
		
		
		
		
		
		function insertarRegistroPostgresql($autoid, $fecha,$comprobante, $numero, $identificacion, $detalle,  $cuenta, $debe, $haber, $cc, $docsoporte, $basegravable, $compania, $usuariocre, $fechacre,  $modificadopor, $fechamod, $cerrado, $formapago, $nocheque, $banco, $diasvencimiento, $estado, $docdestino, $conceptorte, $porcretenido, $tipopago, $clasepago, $bancorecrec, $fechadocumento, $cierrexcajero, $fechaconciliado) {
			
			$cnx = 	conectar_postgres();
			$cons = "INSERT INTO Contabilidad.movimiento (autoid, fecha,comprobante, numero, identificacion, detalle,  cuenta, debe, haber, cc, docsoporte, basegravable, compania, usuariocre, fechacre,  modificadox, fechamod, cerrado, formapago, nocheque, banco, diasvencimiento, estado, docdestino, conceptorte, porcretenido, tipopago, clasepago, bancorecrec, fechadocumento, cierrexcajero, fechaconciliado) VALUES ('".$autoid."','".$fecha."','".$comprobante."',".$numero.",'".$identificacion."','".$detalle."','".$cuenta."',".$debe.",'".$haber."','".$cc."','".$docsoporte."',".$basegravable.",'".$compania."','".$usuariocre."','".$fechacre."','".$modificadopor."','".$fechamod."','".$cerrado."','".$formapago."',".$nocheque.",'".$banco."',".$diasvencimiento.",'".$estado."','".$docdestino."','".$conceptorte."','".$porcretenido."','".$tipopago."','".$clasepago."','".$bancorecrec."','".$fechadocumento."','".$cierrexcajero."','".$fechaconciliado."')"	;
					 
			$cons = str_replace( "'NULL'","NULL",$cons  );	
			$res = @pg_query($cnx, $cons);
				if (!$res) {
					$consUTF8 = utf8_encode($cons);
					$resUTF8 = @pg_query($cnx, $consUTF8);					
						if (!$resUTF8) {
							$error = pg_last_error();
							insertarRegistroMigracion($autoid, $fecha,$comprobante, $numero, $identificacion, $detalle,  $cuenta, $debe, $haber, $cc, $docsoporte, $basegravable, $compania, $usuariocre, $fechacre,  $modificadopor, $fechamod, $cerrado, $formapago, $nocheque, $banco, $diasvencimiento, $estado, $docdestino, $conceptorte, $porcretenido, $tipopago, $clasepago, $bancorecrec, $fechadocumento, $cierrexcajero, $fechaconciliado,$error);
						}
				
				}

				
		}
		
		
		function  llenarMatriz($limiteIni, $limiteFin){
			
			unset($matriz); 
			global  $matriz;	
			$res = llamarMovimientoMySQL($limiteIni, $limiteFin);
			$posicion=0;
				
				while ($fila = mysql_fetch_array($res))	{	
					
					$matriz["autoid"][$posicion] = $fila["AutoId"];
					$matriz["fecha"][$posicion] = $fila["Fecha"];
					$matriz["comprobante"][$posicion] = $fila["Comprobante"];
					$matriz["numero"][$posicion] = $fila["Numero"];
					$matriz["identificacion"][$posicion] = $fila["Identificacion"];
					$matriz["detalle"][$posicion] = $fila["Detalle"];
					$matriz["cuenta"][$posicion] = $fila["Cuenta"];
					$matriz["debe"][$posicion] = $fila["Debe"];
					$matriz["haber"][$posicion] = $fila["Haber"];
					$matriz["cc"][$posicion] = $fila["CC"];
					$matriz["docsoporte"][$posicion] = $fila["DocSoporte"];
					$matriz["basegravable"][$posicion] = $fila["BaseGravable"];
					$matriz["compania"][$posicion] = $fila["Compania"];
					$matriz["usuariocre"][$posicion] = $fila["Usuariocre"];
					$matriz["fechacre"][$posicion] = $fila["FechaCre"];
					$matriz["modificadopor"][$posicion] = $fila["Modificadox"];
					$matriz["fechamod"][$posicion] = $fila["FechaMod"];
					$matriz["cerrado"][$posicion] = $fila["Cerrado"];
					$matriz["formapago"][$posicion] = $fila["Formapago"];
					$matriz["nocheque"][$posicion] = $fila["NoCheque"];
					$matriz["banco"][$posicion] = $fila["Banco"];
					$matriz["diasvencimiento"][$posicion] = $fila["DiasVencimiento"];
					$matriz["estado"][$posicion] = $fila["Estado"];
					$matriz["docdestino"][$posicion] = $fila["DocDestino"];
					$matriz["conceptorte"][$posicion] = $fila["ConceptoRte"];
					$matriz["PorcRetenido"][$posicion] = $fila["PorcRetenido"];
					$matriz["TipoPago"][$posicion] = $fila["TipoPago"];
					$matriz["BancoRecRec"][$posicion] = $fila["BancoRecRec"];
					$matriz["Fechadocumento"][$posicion] = $fila["FechaDocumento"];
					$matriz["CierreXCajero"][$posicion] = $fila["CierrexCajero"];
					$matriz["FechaConciliado"][$posicion] = $fila["FechaConciliado"];
												
					$posicion++;				
				}
							
				
			}
			

				
		
		
		
			
			function insertarTabla($puntero)  {
			
				global $res,$matriz;
					for($pos=0;$pos <= mysql_num_rows($res); $pos++)  {

					//$autoid=	 $matriz["autoid"][$pos] ; //Asigna el autoid con base en el autoid de MySQL
					$autoid = $puntero + $pos;
					$fecha= 	 $matriz["fecha"][$pos] ;
						if($fecha == "0000-00-00")  {
								$fecha = '1900-01-01';//Valor por defecto, ya que dicho campo en la base de datos es NOT NULL 						
							}	
					$comprobante=	 $matriz["comprobante"][$pos] ;
					$comprobante= normalizarComprobantesContabilidad($comprobante);
					
						if ($comprobante == "") {
							$comprobante = "POR VALIDAR";
						}					
					$numero=	 $matriz['numero'][$pos] ;
					$identificacion=	 $matriz["identificacion"][$pos] ;
					$identificacion= eliminarCaracteresEspeciales($identificacion);	
					$detalle=	 $matriz["detalle"][$pos] ;
					$detalle= eliminarCaracteresEspeciales($detalle);
					$cuenta=	 $matriz["cuenta"][$pos] ;
					$cuenta= eliminarCaracteresEspeciales($cuenta);
					$debe=	 $matriz['debe'][$pos] ;
					$haber=	 $matriz['haber'][$pos] ;
					$cc=	 $matriz["cc"][$pos] ;
					$cc= eliminarCaracteresEspeciales($cc);
					$docsoporte=	 $matriz["docsoporte"][$pos] ;
					$docsoporte= eliminarCaracteresEspeciales($docsoporte);
					$basegravable=	 $matriz["basegravable"][$pos] ;
					$basegravable= eliminarCaracteresEspeciales($basegravable);					
					$compania = $_SESSION["compania"];
					$usuariocre=	 $matriz["usuariocre"][$pos] ;
					$usuariocre= eliminarCaracteresEspeciales($usuariocre);
					$fechacre= 	 $matriz["fechacre"][$pos] ;
						if($fechacre == "0000-00-00 00:00:00")  {
								$fechacre = 'NULL';						
						}
						elseif($fechacre == "2011-01-00 00:00:00" or $fechacre == "2011-01-00" )  {
								$fechacre = '2011-01-01';						
						}
						
					$modificadopor=	 $matriz["modificadopor"][$pos] ;
					$modificadopor= eliminarCaracteresEspeciales($modificadopor);
					$fechamod= 	 $matriz["fechamod"][$pos] ;
						if($fechamod == "0000-00-00 00:00:00")  {
								$fechamod = 'NULL';						
						}
						elseif($fechamod == "2011-01-00 00:00:00" or $fechamod == "2011-01-00" )  {
								$fechamod = '2011-01-01';						
						}
						
						
					$cerrado=	 $matriz["cerrado"][$pos] ;
					$cerrado= eliminarCaracteresEspeciales($cerrado);
					$formapago=	 $matriz["formapago"][$pos] ;
					$formapago= eliminarCaracteresEspeciales($formapago);
					$nocheque=	 $matriz["nocheque"][$pos] ;
					$nocheque= eliminarCaracteresEspeciales($nocheque);
					$banco=	 $matriz["banco"][$pos] ;
					$banco= eliminarCaracteresEspeciales($banco);
					$diasvencimiento=	 $matriz["diasvencimiento"][$pos] ;
					$diasvencimiento= eliminarCaracteresEspeciales($diasvencimiento);
					$estado=	 $matriz["estado"][$pos] ;
					$estado= eliminarCaracteresEspeciales($estado);
					$docdestino=	 $matriz["docdestino"][$pos] ;
					$docdestino= eliminarCaracteresEspeciales($docdestino);
					$conceptorte=	 $matriz["conceptorte"][$pos] ;
					$conceptorte= eliminarCaracteresEspeciales($conceptorte);
					$porcretenido=	 $matriz["PorcRetenido"][$pos] ;
					$porcretenido= eliminarCaracteresEspeciales($porcretenido);
					$tipopago=	 $matriz["TipoPago"][$pos] ;
					$tipopago= eliminarCaracteresEspeciales($tipopago);
					$clasepago=	 $matriz["ClasePago"][$pos] ;
					$clasepago= eliminarCaracteresEspeciales($clasepago);
						if ($clasepago == "") {
							$clasepago = 'NULL';
						}
						
					$bancorecrec=	 $matriz["BancoRecRec"][$pos] ;
					$bancorecrec= eliminarCaracteresEspeciales($bancorecrec);
					$fechadocumento= 	 $matriz["Fechadocumento"][$pos] ;
						if($fechadocumento == "0000-00-00")  {
								$fechadocumento = 'NULL';						
						}
						elseif ($fechadocumento == "2011-01-00")  {
								$fechadocumento = '2011-01-01';						
						}
						
					$cierrexcajero= 	 $matriz["CierreXCajero"][$pos] ;
						if($cierrexcajero == "0000-00-00")  {
								$cierrexcajero = 'NULL';						
						}
						elseif ($cierrexcajero == "2011-01-00")  {
								$cierrexcajero = '2011-01-01';						
						}
						
					$fechaconciliado= 	 $matriz["FechaConciliado"][$pos] ;
						if($fechaconciliado == "0000-00-00")  {
								$fechaconciliado = 'NULL';						
						}
						elseif ($fechaconciliado == "2011-01-00")  {
								$fechaconciliado = '2011-01-01';						
						}
					
										
					insertarRegistroPostgresql($autoid, $fecha,$comprobante, $numero, $identificacion, $detalle,  $cuenta, $debe, $haber, $cc, $docsoporte, $basegravable, $compania, $usuariocre, $fechacre,  $modificadopor, $fechamod, $cerrado, $formapago, $nocheque, $banco, $diasvencimiento, $estado, $docdestino, $conceptorte, $porcretenido, $tipopago, $clasepago, $bancorecrec, $fechadocumento, $cierrexcajero, $fechaconciliado);
					
						
					}
			
	
			}
			
			
				
		function eliminarRegistrosMovimiento() {
			
			$cnx = 	conectar_postgres();
			$cons = "DELETE FROM Contabilidad.movimiento"	;
			
			$res = @pg_query($cnx, $cons);
				if (!$res) {
					
							$error = pg_last_error();
							insertarRegistroMigracion($autoid, $fecha,$comprobante, $numero, $identificacion, $detalle,  $cuenta, $debe, $haber, $cc, $docsoporte, $basegravable, $compania, $usuariocre, $fechacre,  $modificadopor, $fechamod, $cerrado, $formapago, $nocheque, $banco, $diasvencimiento, $estado, $docdestino, $conceptorte, $porcretenido, $tipopago, $clasepago, $bancorecrec, $fechadocumento, $cierrexcajero, $fechaconciliado,$error);
						}
		
		}
				

		/* Finaliza la definicion de funciones*/	
		
		
		
		
		/* Inicia la ejecucion de la migracion */
			
		if($_GET['tabla']="Contabilidad_movimiento") {
		
			echo "<fieldset>";			
			echo "<legend> Migracion tabla MySQL </legend>";
			echo "<br>";
			echo "<span align='left'> <a href='../../index.php?migracion=MIG064' class = 'link1'> Panel de Administracion </a> </span>";
			echo "<br>";
			
			eliminarTablaMigracion();
			crearTablaMigracion();
			crearArchivo();
			eliminarRegistrosMovimiento();
			
			/* Tabla Contabilidad.Comprobantes */
			reemplazarTextoTabla1(utf8_encode("Á"),'&Aacute;');			
			reemplazarTextoTabla1(utf8_encode("É"),'&Eacute;');
			reemplazarTextoTabla1(utf8_encode("Í"),'&Iacute;');
			reemplazarTextoTabla1(utf8_encode("Ó"),'&Oacute;');
			reemplazarTextoTabla1(utf8_encode("Ú"),'&Uacute;');
			reemplazarTextoTabla1(utf8_encode("Ñ"),'&Ntilde;');
			
			/* Tabla Contabilidad.FormasPago */
			reemplazarTextoTabla2(utf8_encode("Á"),'&Aacute;');			
			reemplazarTextoTabla2(utf8_encode("É"),'&Eacute;');
			reemplazarTextoTabla2(utf8_encode("Í"),'&Iacute;');
			reemplazarTextoTabla2(utf8_encode("Ó"),'&Oacute;');
			reemplazarTextoTabla2(utf8_encode("Ú"),'&Uacute;');
			reemplazarTextoTabla2(utf8_encode("Ñ"),'&Ntilde;');
			
			/* Tabla Contabilidad.TiposPago */
			reemplazarTextoTabla3(utf8_encode("Á"),'&Aacute;');			
			reemplazarTextoTabla3(utf8_encode("É"),'&Eacute;');
			reemplazarTextoTabla3(utf8_encode("Í"),'&Iacute;');
			reemplazarTextoTabla3(utf8_encode("Ó"),'&Oacute;');
			reemplazarTextoTabla3(utf8_encode("Ú"),'&Uacute;');
			reemplazarTextoTabla3(utf8_encode("Ñ"),'&Ntilde;');
			
			/* Tabla Contabilidad.ClasesPago */
			reemplazarTextoTabla4(utf8_encode("Á"),'&Aacute;');			
			reemplazarTextoTabla4(utf8_encode("É"),'&Eacute;');
			reemplazarTextoTabla4(utf8_encode("Í"),'&Iacute;');
			reemplazarTextoTabla4(utf8_encode("Ó"),'&Oacute;');
			reemplazarTextoTabla4(utf8_encode("Ú"),'&Uacute;');
			reemplazarTextoTabla4(utf8_encode("Ñ"),'&Ntilde;');
			
			
			
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
									echo "<div align='center'> <p class='mensajeFinalizacion'>Ha terminado la migracion de la tabla Contabilidad.movimiento</p> </div>";
									break;
								}	
							$limiteIni = $limiteIni + $fragmento;							
							
					   } 
					   
					   
			/* Tabla Contabilidad.Comprobantes */
			reemplazarTextoTabla1('&Aacute;',utf8_encode("Á"));			
			reemplazarTextoTabla1('&Eacute;',utf8_encode("É"));
			reemplazarTextoTabla1('&Iacute;',utf8_encode("Í"));
			reemplazarTextoTabla1('&Oacute;',utf8_encode("Ó"));
			reemplazarTextoTabla1('&Uacute;',utf8_encode("Ú"));
			reemplazarTextoTabla1('&Ntilde;',utf8_encode("Ñ"));
			
			/* Tabla Contabilidad.FormasPago */
			reemplazarTextoTabla2('&Aacute;',utf8_encode("Á"));			
			reemplazarTextoTabla2('&Eacute;',utf8_encode("É"));
			reemplazarTextoTabla2('&Iacute;',utf8_encode("Í"));
			reemplazarTextoTabla2('&Oacute;',utf8_encode("Ó"));
			reemplazarTextoTabla2('&Uacute;',utf8_encode("Ú"));
			reemplazarTextoTabla2('&Ntilde;',utf8_encode("Ñ"));
			
			/* Tabla Contabilidad.TiposPago */
			reemplazarTextoTabla3('&Aacute;', utf8_encode("Á"));			
			reemplazarTextoTabla3('&Eacute;', utf8_encode("É"));
			reemplazarTextoTabla3('&Iacute;', utf8_encode("Í"));
			reemplazarTextoTabla3('&Oacute;', utf8_encode("Ó"));
			reemplazarTextoTabla3('&Uacute;', utf8_encode("Ú"));
			reemplazarTextoTabla3('&Ntilde;', utf8_encode("Ñ"));
			
			/* Tabla Contabilidad.ClasesPago */
			reemplazarTextoTabla4('&Aacute;', utf8_encode("Á"));			
			reemplazarTextoTabla4('&Eacute;', utf8_encode("É"));
			reemplazarTextoTabla4('&Iacute;', utf8_encode("Í"));
			reemplazarTextoTabla4('&Oacute;', utf8_encode("Ó"));
			reemplazarTextoTabla4('&Uacute;',utf8_encode("Ú"));
			reemplazarTextoTabla4('&Ntilde;',utf8_encode("Ñ"));		   
					   
					   
			
			/* Tabla Contabilidad.Movimiento */
			reemplazarTextoTabla5('&Aacute;', utf8_encode("Á"));			
			reemplazarTextoTabla5('&Eacute;', utf8_encode("É"));
			reemplazarTextoTabla5('&Iacute;',utf8_encode("Í"));
			reemplazarTextoTabla5('&Oacute;', utf8_encode("Ó"));
			reemplazarTextoTabla5('&Uacute;', utf8_encode("Ú"));
			reemplazarTextoTabla5('&Ntilde;', utf8_encode("Ñ"));	   
					   
					   
				}	   
				else  {
					echo "<p class='error1'> El total de registros en MySQL es menor al fragmento</p>";
				
				}
			
				
				$totalMySQL = contarRegistrosMySQL();
				$totalPostgresql =  contarRegistrosPostgresql();
				$totalPostgresqlErrores =  contarRegistrosPostgresqlErrores();
				
				echo "<p class= 'subtitulo1'> Total registros MySQL:</p>";
				echo  $totalMySQL."<br/>";
				echo "<p class= 'subtitulo1'> Total registros Postgresql migrados:</p>";
				echo  $totalPostgresql."<br/>";
				echo "<p class= 'error1'> Total errores genereados(Tabla Contabilidad.MovimientoMigracion):</p>";
				echo  $totalPostgresqlErrores."<br/>";
				
				echo "<p> <a href='reporteErrores.html' class = 'link1' target='_blank'> Ver Reporte de errores de la migracion </a> </p>";
				
				
									

				
				
			echo "</fieldset>";
			
		}
			
	

		
		
		
		

	
	




?>