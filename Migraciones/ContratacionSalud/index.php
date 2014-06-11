	<html>	
		<head>
			<title> Migracion  Esquema Contratacion Salud </title>
			<link rel="stylesheet" type="text/css" href="../General/estilos/estilos.css">
		</head>
	
	
	<?php
		session_start();
		
		include_once('../Conexiones/conexion.php');
		include_once('../General/funciones/funciones.php');
		include_once('CuentaxGrupos/procedimiento.php');
		include_once('ClasesPlanServicios/procedimiento.php');
		include_once('CUPS/procedimiento.php');	
		include_once('CUPSxConsulExtern/procedimiento.php');		
		include_once('CUPSxPlanes/procedimiento.php');	
		include_once('CUPSxPlanServic/procedimiento.php');	
		include_once('FrecAgendaInterna/procedimiento.php');
		include_once('GruposServicio/procedimiento.php');
		include_once('MedsxPlanServic/procedimiento.php');	
		include_once('PlaneServicios/procedimiento.php');
		include_once('PlanesTarifas/procedimiento.php');
		include_once('PolizasxContrato/procedimiento.php');	
		include_once('RestriccionesCobro/procedimiento.php');	
		include_once('TiposdeProdxFormulacion/procedimiento.php');	
		include_once('TiposServicio/procedimiento.php');
		include_once('Contratos/procedimiento.php');
		include_once('../Salud/Confestancia/procedimiento.php');
		include_once('../Salud/TopesCopago/procedimiento.php');
		
		
		
		
		/* Termina definicion de funciones */
		
		function crearArchivoErrores() {
		// Crea un archivo HTML donde se documentaran los registros que no se insertaron en la tabla de migraciones
			$fp = fopen("Errores/ContratacionSalud.html", "w+");
			$encabezado = "<html> <head> <title> Reporte errores Esquema Contratacion salud </title> 
			<link rel='stylesheet' type='text/css' href='../../General/estilos/estilos.css'> </head>";
			fputs($fp, $encabezado);
			fclose($fp);
		}
		
	
		
		
		
		
		/* Inicia la ejecucion de funciones */
		
		if($_GET["esquema"]=="ContratacionSalud") {
		
			echo "<fieldset>";			
			echo "<legend> Migracion Esquema Contratacion Salud  </legend>";
			echo "<br>";
			echo "<span align='left'> <a href='../index.php?migracion=MIG012' class = 'link1'> Panel de Administracion </a> </span>";
			echo "<br>";
			
			
			
			crearArchivoErrores();
			
			actualizarClasesPlanServicios(1);
			generarNotaAclaratoria(1,"Revisar la parametrizacion  de la tabla ContratacionSalud.CuentaXGrupos");
			generarNotaAclaratoria(2,"La tabla ContratacionSalud.Contratos tiene por defecto el valor 0 en la columna AjustarDias");
			migrarCuentasxGrupos(2);
			actualizarCUPS(3);
			migrarCUPSxConsulExtern(4);
			migrarCUPSxPlanServic(5);
			migrarFrecAgendaInterna(6);
			migrarGruposServicio(6);
			migrarMedsxPlanServic(7);
			migrarPlaneServicios(8);
			migrarPlanesTarifas(9);
			migrarPolizasxContrato(10);
			migrarRestriccionesCobro(11);
			migrarTiposdeProdxFormulacion(12);
			migrarTiposServicio(13);
			migrarCUPSXPlanes(14);
			actualizarPlanTarifarioISS2001();
			actualizarCUPxPlanISS2001();
			migrarContratos(15);
			migrarConfEstancia(16);
			crearArchivoCupsFaltantes();
			registrarCupsFaltantes();
			migrarTopesCopago(17);
			
			
			echo "<span align='right'> <a href='Errores/CUPSFaltantes.html' target='_blank' class = 'link1'> Ver CUPS por configurar </a> </span>";
			
			
			echo "<div align='center'> <p class='mensajeFinalizacion'>Ha terminado la migracion del Esquema ContratacionSalud </p> </div>";
			
			
			echo "<br/> <br/> <br/> ";
			echo "<span align='right'> <a href='Errores/ContratacionSalud.html' target='_blank' class = 'link1'> Ver Reporte de Errores</a> </span>";			
			echo "<br/> <br/>";			
			echo "<span align='right'> <a href='revertir.php?accion=revertirMigracion' class = 'link1'> Revertir Migracion Esquema Contratacion Salud </a> </span>";
			
			
			echo "</fieldset>";
			
		}
			
			
		
		/* Termina  la ejecucion de funciones */
		
		
		
		
	
	
	
	?>
