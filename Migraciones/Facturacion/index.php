	<html>	
		<head>
			<title> Migracion  Esquema Contabilidad </title>
			<link rel="stylesheet" type="text/css" href="../General/estilos/estilos.css">
		</head>
	
	
	<?php
		session_start();
		
		include_once('../Conexiones/conexion.php');
		include_once('../General/funciones/funciones.php');
		include_once('ClasesGlosa/procedimiento.php');
		include_once('CodMotivoGlosa/procedimiento.php');
		include_once('DetalleLiquidacion/procedimiento.php');
		include_once('FirmasRtaGlosas/procedimiento.php');
		include_once('HistorialCuenta/procedimiento.php');
		include_once('Liquidacion/procedimiento.php');
		include_once('NotasPiePag/procedimiento.php');
		include_once('TmpCupsomeds/procedimiento.php');
		include_once('TmpRtaGlosa/procedimiento.php');
		
				
		/* Termina definicion de funciones */
		
		function crearArchivoErrores() {
		// Crea un archivo HTML donde se documentaran los registros que no se insertaron en la tabla de migraciones
			$fp = fopen("Errores/ReporteFacturacion.html", "w+");
			$encabezado = "<html> <head> <title> Reporte errores Esquema Contabilidad(Farmacia) </title> 
			<link rel='stylesheet' type='text/css' href='../../General/estilos/estilos.css'> </head>";
			fputs($fp, $encabezado);
			fclose($fp);
		}
		
		
		function  reemplazarTextoTabla1($cadenaBusqueda,$cadenaReemplazo)  {
			$cnx= conectar_postgres();
			$cons = "UPDATE Contabilidad.comprobantes SET comprobante = replace( comprobante,'$cadenaBusqueda','$cadenaReemplazo')";
			
			$res = pg_query($cnx , $cons);
				if (!$res)  {
					echo "<p class='error1'> Error en la normalizacion de la codificacion </p>".pg_last_error()."<br>";
				}

		}
		
		
		function  reemplazarTextoTabla5($cadenaBusqueda,$cadenaReemplazo)  {
			$cnx= conectar_postgres();
			$cons = "UPDATE Contabilidad.movimiento SET comprobante = replace( comprobante,'$cadenaBusqueda','$cadenaReemplazo'), numero = replace( numero,'$cadenaBusqueda' , '$cadenaReemplazo'), identificacion = replace( identificacion,'$cadenaBusqueda' , '$cadenaReemplazo') , detalle = replace( detalle,'$cadenaBusqueda' , '$cadenaReemplazo') , cuenta  = replace( cuenta,'$cadenaBusqueda' , '$cadenaReemplazo') , cc = replace( cc,'$cadenaBusqueda' , '$cadenaReemplazo') , docsoporte = replace( docsoporte,'$cadenaBusqueda' , '$cadenaReemplazo') , compania = replace( compania,'$cadenaBusqueda' , '$cadenaReemplazo') , usuariocre = replace( usuariocre,'$cadenaBusqueda' , '$cadenaReemplazo'), modificadox = replace( modificadox,'$cadenaBusqueda' , '$cadenaReemplazo') , cerrado = replace( cerrado,'$cadenaBusqueda' , '$cadenaReemplazo') , banco = replace( banco,'$cadenaBusqueda' , '$cadenaReemplazo') , docdestino = replace( docdestino,'$cadenaBusqueda' , '$cadenaReemplazo') , conceptorte = replace( conceptorte,'$cadenaBusqueda' , '$cadenaReemplazo') , porcretenido = replace( porcretenido,'$cadenaBusqueda' , '$cadenaReemplazo'), bancorecrec = replace( bancorecrec,'$cadenaBusqueda' , '$cadenaReemplazo') , noresolucion = replace( noresolucion,'$cadenaBusqueda' , '$cadenaReemplazo')";
			$res = pg_query($cnx , $cons);
				if (!$res)  {
					echo "<p class='error1'> Error en la normalizacion de la codificacion </p>".pg_last_error()."<br>";
				}

		}
		
		
		
		
		
		
		/* Inicia la ejecucion de funciones */
		
		if($_GET['esquema']=="Facturacion") {
		
			echo "<fieldset>";			
			echo "<legend> Migracion Esquema Facturacion </legend>";
			echo "<br>";
			echo "<span align='left'> <a href='../index.php?migracion=MIG065' class = 'link1'> Panel de Administracion </a> </span>";
			echo "<br>";
			
			
			
			/* Tabla Contabilidad.Comprobantes */
			reemplazarTextoTabla1('&Aacute;',utf8_encode("Á"));			
			reemplazarTextoTabla1('&Eacute;',utf8_encode("É"));
			reemplazarTextoTabla1('&Iacute;',utf8_encode("Í"));
			reemplazarTextoTabla1('&Oacute;',utf8_encode("Ó"));
			reemplazarTextoTabla1('&Uacute;',utf8_encode("Ú"));
			reemplazarTextoTabla1('&Ntilde;',utf8_encode("Ñ"));
			
			/* Tabla Contabilidad.Movimiento */
			reemplazarTextoTabla5('&Aacute;', utf8_encode("Á"));			
			reemplazarTextoTabla5('&Eacute;', utf8_encode("É"));
			reemplazarTextoTabla5('&Iacute;',utf8_encode("Í"));
			reemplazarTextoTabla5('&Oacute;', utf8_encode("Ó"));
			reemplazarTextoTabla5('&Uacute;', utf8_encode("Ú"));
			reemplazarTextoTabla5('&Ntilde;', utf8_encode("Ñ"));
			
			
			migrarClasesGlosa(1);	
			migrarCodMotivoGlosa(2);
			migrarDetalleLiquidacion(3);
			migrarFirmasRtaGlosas(4);
			migrarHistorialCuenta(5);
			migrarLiquidacion(6) ;
			migrarNotasPiePag(7);
			migrarTmpCupsomeds(8);
			migrarTmpRtaGlosa(9);
			
			
			
			echo "</fieldset>";
			
		}
			
			
		
		/* Termina  la ejecucion de funciones */
		
		
		
		
	
	
	
	?>
