	<html>	
		<head>
			<title> Migracion  Esquema Historia Clinica </title>
			<link rel="stylesheet" type="text/css" href="../General/estilos/estilos.css">
		</head>
	
	
	<?php
		session_start();
		
		include_once('../Conexiones/conexion.php');
		include_once('../General/funciones/funciones.php');
		include_once('Alineacion/procedimiento.php');
		include_once('Escolaridad/procedimiento.php');
		include_once('EmpleosOIT/procedimiento.php');
		include_once('TipoFormato/procedimiento.php');
		
		
		
		/* Termina definicion de funciones */
		
		function crearArchivoErrores() {
		// Crea un archivo HTML donde se documentaran los registros que no se insertaron en la tabla de migraciones
			$fp = fopen("Errores/HistoriaClinica.html", "w+");
			$encabezado = "<html> <head> <title> Reporte errores Esquema Historia Clinica </title> 
			<link rel='stylesheet' type='text/css' href='../../General/estilos/estilos.css'> </head>";
			fputs($fp, $encabezado);
			fclose($fp);
		}
		
	
		
		/* Inicia la ejecucion de funciones */
		
		if($_GET["esquema"]=="Historia_Clinica") {
		
			echo "<fieldset>";			
			echo "<legend> Migracion Esquema  Historia Clinica   </legend>";
			echo "<br>";
			echo "<span align='left'> <a href='../index.php?migracion=MIG017' class = 'link1'> Panel de Administracion </a> </span>";
			echo "<br>";
			
			crearArchivoErrores();
			$compania = $_SESSION["compania"];
			
			
			
			eliminarRegistrosTabla("HistoriaClinica","AgendaInterna");
			cambiarCompania("HistoriaClinica","AjustePermanente", $compania);
			eliminarRegistrosTabla("HistoriaClinica","AjustePermanenteDet");
			eliminarRegistrosTabla("HistoriaClinica","AlertasHC");
			migrarAlineacion(1);
			eliminarRegistrosTabla("HistoriaClinica","AmbitosxFormato");
			eliminarRegistrosTabla("HistoriaClinica","CausaExternxFormato");
			eliminarRegistrosTabla("HistoriaClinica","ClapControles");
			eliminarRegistrosTabla("HistoriaClinica","Claps");
			eliminarRegistrosTabla("HistoriaClinica","CtrlLiquidos");
			eliminarRegistrosTabla("HistoriaClinica","CUPSLabs");			
			eliminarRegistrosTabla("HistoriaClinica","CUPSxFormatos");
			eliminarRegistrosTabla("HistoriaClinica","DependenciaHC");
			eliminarRegistrosTabla("HistoriaClinica","DxFormatos");
			eliminarRegistrosTabla("HistoriaClinica","DxPermitidosxFormato");
			migrarEmpleosOIT();
			eliminarRegistrosTabla2("HistoriaClinica","Ead1");
			eliminarRegistrosTabla("HistoriaClinica","EadEvaluacion");
			eliminarRegistrosTabla("HistoriaClinica","EadObservaciones");
			eliminarRegistrosTabla2("HistoriaClinica","EadParametrosNormativos");
			cambiarCompania("HistoriaClinica","EmpleosOIT", $compania);
			eliminarRegistrosTabla("HistoriaClinica","EtiquetasxFormatoXML");			
			cambiarCompania("HistoriaClinica","Escolaridad", $compania);
			eliminarEscolaridad() ;
			insertarEscolaridad();
			cambiarCompania("HistoriaClinica","FichaId", $compania);
			cambiarCompania("HistoriaClinica","FinalidadxFormato", $compania);
			eliminarRegistrosTabla("HistoriaClinica","FormatoRedApoyo");
			eliminarRegistrosTabla("HistoriaClinica","Formatos");
			eliminarRegistrosTabla("HistoriaClinica","FormatosXML");
			eliminarRegistrosTabla("HistoriaClinica","IndicadoresxHC");
			eliminarRegistrosTabla("HistoriaClinica","InscripcionClaps");
			cambiarCompania("HistoriaClinica","InterpretacionLabs", $compania);					
			eliminarRegistrosTabla("HistoriaClinica","ItemsxFormatos");
			eliminarRegistrosTabla2("HistoriaClinica","NoActualHA");
			eliminarRegistrosTabla("HistoriaClinica","NotasCambioJefe");
			eliminarRegistrosTabla("HistoriaClinica","NotasCambioMedico");
			eliminarRegistrosTabla("HistoriaClinica","NotasCambioTurno");
			eliminarRegistrosTabla("HistoriaClinica","PacienteSeg");
			eliminarRegistrosTabla("HistoriaClinica","PermisosxFormato");
			eliminarRegistrosTabla("HistoriaClinica","RegistroVoBoxFormatos");
			eliminarRegistrosTabla("HistoriaClinica","RegPacienteSeg");
			eliminarRegistrosTabla("HistoriaClinica","SignosVitales");
			eliminarRegistrosTabla("HistoriaClinica","RegPacienteSeg");
			eliminarRegistrosTabla("HistoriaClinica","TagsXML");
			eliminarRegistrosTabla("HistoriaClinica","TipoFormato");
			eliminarRegistrosTabla("HistoriaClinica","TitulosxFormato");
			eliminarRegistrosTabla("HistoriaClinica","VoBoxFormatos");
			eliminarRegistrosTabla("HistoriaClinica","MedsxFormato");
			eliminarRegistrosTabla("HistoriaClinica","ProcedxFormato");
			eliminarRegistrosTabla("HistoriaClinica","TipoFormato");
			
			

	
			
			echo "<div align='center'> <p class='mensajeFinalizacion'>Ha terminado la migracion del Esquema Historia Clinica </p> </div>";
			
			
			
			
			
			echo "<br/> <br/> <br/> ";
			echo "<span align='right'> <a href='Errores/HistoriaClinica.html' target='_blank' class = 'link1'> Ver Reporte de Errores</a> </span>";			
			echo "<br/> <br/>";			
			echo "<span align='right'> <a href='revertir.php?accion=revertirMigracion' class = 'link1'> Revertir Migracion Esquema Historia Clinica</a> </span>";
			
			
			echo "</fieldset>";
			
		}
			
			
		
		/* Termina  la ejecucion de funciones */
		
		
		
		
	
	
	
	?>
