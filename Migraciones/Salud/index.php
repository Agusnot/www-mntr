	<html>	
		<head>
			<title> Migracion  Esquema Salud </title>
			<link rel="stylesheet" type="text/css" href="../General/estilos/estilos.css">
		</head>
	
	
	<?php
		session_start();
		
		include_once('../Conexiones/conexion.php');
		include_once('../General/funciones/funciones.php');
		include_once('ActvAsistenciales/procedimiento.php');
		include_once('Ambitos/procedimiento.php');
		include_once('BloqueoxDia/procedimiento.php');
		include_once('FormatoLabExt/procedimiento.php');
		include_once('MotivoSalida/procedimiento.php');
		include_once('UsuariosxHC/procedimiento.php');
		include_once('Cargos/procedimiento.php');
		include_once('Especialidades/procedimiento.php');
		include_once('Medicos/procedimiento.php');
		include_once('Pabellones/procedimiento.php');
		include_once('CamasxUnidades/procedimiento.php');
		include_once('ConfOrdenesMed/procedimiento.php');
		include_once('Interprogramas/procedimiento.php');
		include_once('Triage/procedimiento.php');
		include_once('PrioridadTriage/procedimiento.php');
		
		
		/* Termina definicion de funciones */
		
		function crearArchivoErrores() {
		// Crea un archivo HTML donde se documentaran los registros que no se insertaron en la tabla de migraciones
			$fp = fopen("Errores/ErroresEsquemaSalud.html", "w+");
			$encabezado = "<html> <head> <title> Reporte errores Esquema Salud </title> 
			<link rel='stylesheet' type='text/css' href='../../General/estilos/estilos.css'> </head>";
			fputs($fp, $encabezado);
			fclose($fp);
		}
		
	
		
		/* Inicia la ejecucion de funciones */
		
		if($_GET["esquema"]=="Salud") {
		
			echo "<fieldset>";			
			echo "<legend> Migracion Esquema n Salud  </legend>";
			echo "<br>";
			echo "<span align='left'> <a href='../index.php?migracion=MIG013' class = 'link1'> Panel de Administracion </a> </span>";
			echo "<br>";
			
			crearArchivoErrores();
			$compania = $_SESSION["compania"];
			
			
			
			migrarActvAsistenciales(1);			
			eliminarRegistrosTabla("Salud","AfectacionContable");
			eliminarRegistrosTabla("Salud","AgendaInterna");
			eliminarRegistrosTabla("Salud","AlertasIngreso");
		 	migrarAmbitos(2);
			generarNotaAclaratoria(2,"Verificar los Centros de Costo de los ambitos");
			eliminarRegistrosTabla2("Salud","AmbitosProcedimientos");
			eliminarRegistrosTabla("Salud","Anexos");			
			migrarBloqueoxDia(3);
			eliminarRegistrosTabla("Salud","CalendarioxMedicamento");
			eliminarRegistrosTabla("Salud","CamasxUnidades");
			migrarCargos(4);
			generarNotaAclaratoria(1,"Revisar la parametrizacion de los Cargos");
			eliminarRegistrosTabla("Salud","CensoGeneral");
			eliminarRegistrosTabla("Salud","CensoxEdades");
			eliminarRegistrosTabla("Salud","Checkeos");
			eliminarRegistrosTabla("Salud","ClasifcLabs");
			eliminarRegistrosTabla("Salud","ClinicasHC");
			eliminarRegistrosTabla("Salud","Comedores");
			//eliminarRegistrosTabla("Salud","ConfEstancia");
			eliminarRegistrosTabla("Salud","ConfOrdenesMed");			
			cambiarCompania("Salud","ConsecutivosLab", $compania);
			cambiarCompania("Salud","ConsistenciaDietas", $compania);
			eliminarRegistrosTabla("Salud","Topescopago");
			eliminarRegistrosTabla("Salud","Diagnosticos");
			cambiarCompania("Salud","Dietas", $compania);
			eliminarRegistrosTabla("Salud","DispoConsExterna");
			eliminarRegistrosTabla("Salud","Elementos");
			eliminarRegistrosTabla("Salud","ElementosCustodia");
			migrarEspecialidades();
			migrarFormatoLabExt(5);
			eliminarRegistrosTabla("Salud","FormatosEgreso");
			eliminarRegistrosTabla("Salud","FormatosEgreso");
			cambiarCompania("Salud","Formulaciones", $compania);
			cambiarCompania("Salud","Formulaciones", $compania);
			cambiarCompania("Salud","GrupoEtareo", $compania);
			cambiarCompania("Salud","GrupoEtareo", $compania);
			eliminarRegistrosTabla("Salud","HoraCantidadxMedicamento");
			migrarInterProgramas(6);
			cambiarCompania("Salud","IntervalosTiempos", $compania);
			//eliminarRegistrosTabla("Salud","Medicos");
			eliminarRegistrosTabla("Salud","Logsuper");
			//migrarMedicos(6);
			cambiarCompania("Salud","MotivoCancelCita", $compania);
			cambiarCompania("Salud","MotivoLevantamientoMulta", $compania);
			migrarMotivoSalida(7);
			eliminarRegistrosTabla("Salud","NoRegistroMedicamentos");
			cambiarCompania("Salud","Notas", $compania);
			eliminarRegistrosTabla("Salud","OrdenesMedicas");
			cambiarCompania("Salud","OrigenCancelCita", $compania);
			cambiarCompania("Salud","OrigenLevantamientoMulta", $compania);
			cambiarCompania("Salud","OrigenLevantamientoMulta", $compania);
			migrarPabellones(9);
			eliminarRegistrosTabla("Salud","PacientesxPabellones");
			eliminarRegistrosTabla("Salud","PagadorxServicios");
			eliminarRegistrosTabla("Salud","Partos");
			eliminarRegistrosTabla("Salud","PlantillaDietas");
			eliminarRegistrosTabla("Salud","PlantillaInterprogramas");
			eliminarRegistrosTabla("Salud","PlantillaMedicamentos");
			eliminarRegistrosTabla("Salud","PlantillaNotas");
			eliminarRegistrosTabla("Salud","PlantillaProcedimientos");
			cambiarCompania("Salud","PreguntasEgreso", $compania);
			cambiarCompania("Salud","PreguntasIngreso", $compania);
			migrarPrioridadTriage(10);
			eliminarRegistrosTabla("Salud","RegActAsist");
			cambiarCompania("Salud","RegCambioIdPacientes", $compania);
			cambiarCompania("Salud","RegCambioIdPacientes", $compania);
			eliminarRegistrosTabla2("Salud","RegEscaneos");
			eliminarRegistrosTabla("Salud","RegistroMedicamentos");
			eliminarRegistrosTabla2("Salud","RegistroMedicamentosTmp");
			eliminarRegistrosTabla("Salud","ResultadoCompensar");
			cambiarCompania("Salud","RutaImgsAnexos", $compania);
			cambiarCompania("Salud","RutaImgsProced", $compania);
			eliminarRegistrosTabla("Salud","SalaUrgencias");
			eliminarRegistrosTabla("Salud","Servicios");
			eliminarRegistrosTabla("Salud","TempConsExterna");
			eliminarRegistrosTabla("Salud","Servicios");
			eliminarRegistrosTabla("Salud","TempDispoMedsxGrup");
			cambiarCompania("Salud","TiemposConsulta", $compania);
			cambiarCompania("Salud","TiemposIntervaloCitas", $compania);
			cambiarCompania("Salud","TiposDiagnostico", $compania);
			cambiarCompania("Salud","TiemposIntervaloCitas", $compania);
			eliminarRegistrosTabla2("Salud","TiposUsuNarino");
			eliminarRegistrosTabla2("Salud","TipoUsuarioxRips");
			eliminarRegistrosTabla("Salud","TmpAlertasIngreso");
			eliminarRegistrosTabla("Salud","TmpCupsOrdenesMeds");
			eliminarRegistrosTabla("Salud","TmpFechasOmMedPro");
			eliminarRegistrosTabla("Salud","TmpHorasCantidadMedicamento");
			eliminarRegistrosTabla("Salud","TmpPagadorxFactura");
			
			migrarTriage(10);
			cambiarCompania("Salud","AccesoxOrdMeds", $compania);
			cambiarCompania("Salud","TiposDosis", $compania);
			//eliminarUsuariosxHC();
			
			//eliminarRegistrosTabla2("Salud","UsusxOrdMeds");	
			eliminarRegistrosTabla("Salud","ValorMulta");	
			cambiarCompania("Salud","ViadeSuministro", $compania);
			migrarCamasxUnidades(11);
			migrarConfordenesmed(12);
			
			
			
			echo "<div align='center'> <p class='mensajeFinalizacion'>Ha terminado la migracion del Esquema Salud</p> </div>";
			
			
			
			
			
			echo "<br/> <br/> <br/> ";
			echo "<span align='right'> <a href='Errores/ErroresEsquemaSalud.html' target='_blank' class = 'link1'> Ver Reporte de Errores</a> </span>";			
			echo "<br/> <br/>";			
			echo "<span align='right'> <a href='revertir.php?accion=revertirMigracion' class = 'link1'> Revertir Migracion Esquema Salud</a> </span>";
			
			
			echo "</fieldset>";
			
		}
			
			
		
		/* Termina  la ejecucion de funciones */
		
		
		
		
	
	
	
	?>
