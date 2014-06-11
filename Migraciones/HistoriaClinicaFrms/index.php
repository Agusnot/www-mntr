	<html>	
		<head>
			<title> Migracion  Esquema HistoriaClinicaFrms </title>
			<link rel="stylesheet" type="text/css" href="../General/estilos/estilos.css">
		</head>
	
	
	<?php
		session_start();
		
		include_once('../Conexiones/conexion.php');
		include_once('../General/funciones/funciones.php');
		include_once('../HistoriaClinica/TipoFormato/procedimiento.php');


		// Inicia definicion de funciones
		
		function eliminarEsquema(){
			//Se elimina el esquema con el proposito de eliminar todas las tablas delos formatos autonomos
			$cnx = conectar_postgres();
			$cons= "DROP SCHEMA IF EXISTS HistoClinicaFrms CASCADE";
			$res = @pg_query($cnx,$cons);
			if (!$res) {
							echo "<p class='error1'> Error de ejecucion </p>".pg_last_error()."<br>";
							echo "<p class= 'subtitulo1'>Comando SQL </p> <br>".$cons."<br/>";
							
			}
		
		}
	
	
		function crearEsquema(){
				$cnx = conectar_postgres();
				$cons= "CREATE SCHEMA histoclinicafrms  AUTHORIZATION postgres";
				$res = @pg_query($cnx,$cons);
				if (!$res) {
								echo "<p class='error1'> Error de ejecucion </p>".pg_last_error()."<br>";
								echo "<p class= 'subtitulo1'>Comando SQL </p> <br>".$cons."<br/>";
								
				}
			
			}
			
			
		function crearTabla1(){
				$cnx = conectar_postgres();
				$cons= 'CREATE TABLE histoclinicafrms.ayudaxformatos(  compania character varying(80) NOT NULL,  usuario character varying(100),  fecha timestamp without time zone,  formato character varying(30) NOT NULL,  tipoformato character varying(50) NOT NULL,  id_historia integer NOT NULL,  cedula character varying(15) NOT NULL,  numservicio integer NOT NULL,  fechainterpretacion timestamp without time zone,  interpretacion text,  numproced integer NOT NULL,  CONSTRAINT "PkHCFrmsAyudaxFormat" PRIMARY KEY (compania , formato , tipoformato , id_historia , cedula , numservicio , numproced ))WITH (  OIDS=FALSE)';	
				$res = @pg_query($cnx,$cons);		
				if (!$res) {							
				echo "<p class='error1'> Error de ejecucion </p>".pg_last_error()."<br>";
				echo "<p class= 'subtitulo1'>Comando SQL </p> <br>".$cons."<br/>";
								
				}
			
		}	
		
		
		function crearTabla2(){
				$cnx = conectar_postgres();
				$cons= 'CREATE TABLE histoclinicafrms.cupsxfrms(  formato character varying(300) NOT NULL,  tipoformato character varying(50) NOT NULL,  id_historia integer NOT NULL,  cedula character varying(15) NOT NULL,  compania character varying(60) NOT NULL,  numservicio integer NOT NULL,  cup character varying(130) NOT NULL,  id_item integer NOT NULL,  formarealizacion character varying(1),  finalidadproced character varying(1),  numorden integer,  id_escritura integer,  CONSTRAINT "PkCupsxFrms" PRIMARY KEY (formato , tipoformato , id_historia , cedula , compania , numservicio , cup , id_item ))WITH (  OIDS=FALSE)';	
				$res = @pg_query($cnx,$cons);		
				if (!$res) {							
				echo "<p class='error1'> Error de ejecucion </p>".pg_last_error()."<br>";
				echo "<p class= 'subtitulo1'>Comando SQL </p> <br>".$cons."<br/>";
	
				}
			
		}
		
		function eliminarFormatos(){
				$cnx = conectar_postgres();
				$cons= "DELETE FROM HistoriaClinica.formatos";	
				$res = @pg_query($cnx,$cons);		
				if (!$res) {							
				echo "<p class='error1'> Error de ejecucion </p>".pg_last_error()."<br>";
				echo "<p class= 'subtitulo1'>Comando SQL </p> <br>".$cons."<br/>";
	
				}
			
		}	
		
		function eliminarItemsxFormatos(){
				$cnx = conectar_postgres();
				$cons= "DELETE FROM HistoriaClinica.ItemsXFormatos";	
				$res = @pg_query($cnx,$cons);		
				if (!$res) {							
				echo "<p class='error1'> Error de ejecucion </p>".pg_last_error()."<br>";
				echo "<p class= 'subtitulo1'>Comando SQL </p> <br>".$cons."<br/>";
	
				}
			
		}	
		
		
			function eliminarPermisosxFormato(){
			$cnx = conectar_postgres();
			$cons = "DELETE FROM HistoriaClinica.PermisosxFormato";
			$res = @pg_query($cnx,$cons);		
				if (!$res) {							
				echo "<p class='error1'> Error de ejecucion </p>".pg_last_error()."<br>";
				echo "<p class= 'subtitulo1'>Comando SQL </p> <br>".$cons."<br/>";
	
				}
	
		}
		
		function eliminarAjustePermanente(){
			$cnx = conectar_postgres();
			$cons = "DELETE FROM HistoriaClinica.AjustePermanente";
			$res = @pg_query($cnx,$cons);		
				if (!$res) {							
				echo "<p class='error1'> Error de ejecucion </p>".pg_last_error()."<br>";
				echo "<p class= 'subtitulo1'>Comando SQL </p> <br>".$cons."<br/>";
	
				}
	
		}
		
		function eliminarCUPSxFormatos(){
			$cnx = conectar_postgres();
			$cons = "DELETE FROM HistoriaClinica.CUPSxFormatos";
			$res = @pg_query($cnx,$cons);		
				if (!$res) {							
				echo "<p class='error1'> Error de ejecucion </p>".pg_last_error()."<br>";
				echo "<p class= 'subtitulo1'>Comando SQL </p> <br>".$cons."<br/>";
	
				}
	
		}
		
		function eliminarDxFormatos(){
			$cnx = conectar_postgres();
			$cons = "DELETE FROM HistoriaClinica.DxFormatos";
			$res = @pg_query($cnx,$cons);		
				if (!$res) {							
				echo "<p class='error1'> Error de ejecucion </p>".pg_last_error()."<br>";
				echo "<p class= 'subtitulo1'>Comando SQL </p> <br>".$cons."<br/>";
	
				}
	
		}
		
		function eliminarIndicadoresxHC(){
			$cnx = conectar_postgres();
			$cons = "DELETE FROM HistoriaClinica.IndicadoresxHC";
			$res = @pg_query($cnx,$cons);		
				if (!$res) {							
				echo "<p class='error1'> Error de ejecucion </p>".pg_last_error()."<br>";
				echo "<p class= 'subtitulo1'>Comando SQL </p> <br>".$cons."<br/>";
	
				}
	
		}
		
		
		function eliminarVoBoxFormatos(){
			$cnx = conectar_postgres();
			$cons = "DELETE FROM HistoriaClinica.VoBoxFormatos";
			$res = @pg_query($cnx,$cons);		
				if (!$res) {							
				echo "<p class='error1'> Error de ejecucion </p>".pg_last_error()."<br>";
				echo "<p class= 'subtitulo1'>Comando SQL </p> <br>".$cons."<br/>";
	
				}
	
		}
		
		
		
		function eliminarRegistroVoBoxFormatos(){
			$cnx = conectar_postgres();
			$cons = "DELETE FROM HistoriaClinica.RegistroVoBoxFormatos";
			$res = @pg_query($cnx,$cons);		
				if (!$res) {							
				echo "<p class='error1'> Error de ejecucion </p>".pg_last_error()."<br>";
				echo "<p class= 'subtitulo1'>Comando SQL </p> <br>".$cons."<br/>";
	
				}
	
		}
		
		
		
		function parametrizarEsquema() {
			eliminarEsquema();
			crearEsquema();
			crearTabla1();
			crearTabla2();
			eliminarFormatos();
			eliminarItemsxFormatos();
			eliminarPermisosxFormato();
			eliminarCUPSxFormatos();
			eliminarDxFormatos();
			eliminarVoBoxFormatos();
			eliminarRegistroVoBoxFormatos();
			eliminarIndicadoresxHC();
			migrarTipoFormato();
			

		}
		
		
		
	
		
		
		
		// Termina definicion de funciones
		
		function crearArchivoErrores() {
		// Crea un archivo HTML donde se documentaran los registros que no se insertaron en la tabla de migraciones
			$fp = fopen("Errores/HistoriaClinicaFrms.html", "w+");
			$encabezado = "<html> <head> <title> Reporte errores Esquema HistoriaClinicaFrms </title> 
			<link rel='stylesheet' type='text/css' href='../../General/estilos/estilos.css'> </head>";
			fputs($fp, $encabezado);
			fclose($fp);
		}
		
	
		
		/* Inicia la ejecucion de funciones */
		
		if($_GET["esquema"]=="HistoriaClinicaFrms") {
		
			echo "<fieldset>";			
			echo "<legend> Migracion Esquema  HistoriaClinicaFrms </legend>";
			echo "<br>";
			echo "<span align='left'> <a href='../index.php?migracion=MIG018' class = 'link1'> Panel de Administracion </a> </span>";
			echo "<br>";
			
			
			$compania = $_SESSION["compania"];
			crearArchivoErrores();
			parametrizarEsquema();
			
			
			
			
			
			echo "<div align='center'> <p class='mensajeFinalizacion'>Ha terminado la migracion del Esquema HistoriaClinicaFrms</p> </div>";
			
			
			echo "<br/> <br/> <br/> ";
			echo "<span align='right'> <a href='Errores/HistoriaClinicaFrms.html' target='_blank' class = 'link1'> Ver Reporte de Errores</a> </span>";			
			echo "<br/> <br/>";			
			echo "<span align='right'> <a href='revertir.php?accion=revertirMigracion' class = 'link1'> Revertir Migracion Esquema HistoriaClinicaFrms</a> </span>";
			
			
			echo "</fieldset>";
			
		}
			
			
		
		/* Termina  la ejecucion de funciones */
		
		
		
		
	
	
	
	?>
