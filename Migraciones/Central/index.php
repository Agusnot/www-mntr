	<html>	
		<head>
			<title> Migracion Esquema Central </title>
			<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
			<link rel="stylesheet" type="text/css" href="../General/estilos/estilos.css">
		</head>
	
	
	<?php
		
		include_once '../Conexiones/conexion.php';
		include_once '../General/funciones/funciones.php';
		include_once 'Cesantias/procedimiento.php';
		include_once 'CentrosCosto/procedimiento.php';
		include_once 'CierrexPeriodos/procedimiento.php';
		include_once 'Departamentos/procedimiento.php';
		include_once 'EntidadesBancarias/procedimiento.php';
		include_once 'EPS/procedimiento.php';
		include_once 'Estados/procedimiento.php';
		include_once 'EstadosCiviles/procedimiento.php';
		include_once 'Estilos/procedimiento.php';
		include_once 'Impactos/procedimiento.php';
		include_once 'ListaSexo/procedimiento.php';
		include_once 'Municipios/procedimiento.php';
		include_once 'Meses/procedimiento.php';
		include_once 'RegimenTercero/procedimiento.php';
		include_once 'TiposTercero/procedimiento.php';
		include_once 'TiposDocumento/procedimiento.php';
		include_once 'TiposPersona/procedimiento.php';
		include_once 'TiposSangre/procedimiento.php';
		
		
		/* Inicia definicion de funciones */
		
		function eliminarCentrosCosto($paso) {
		
			$cnx = conectar_postgres();
			$cons = "DELETE FROM central.centroscosto";
				if ($_GET['verConsultas'] == 'true') {
					echo "<p class='subtitulo1'>Consulta Paso $paso : </p>";
					echo $cons;
				}
			$res =  pg_query($cnx, $cons);
		
		}
		
		
		function cambiarCampoCodigo($paso) {
		
			$cnx = conectar_postgres();
			$cons = "ALTER TABLE Central.Eps  ALTER COLUMN codigo TYPE character varying(50)";
				if ($_GET['verConsultas'] == 'true') {
						echo "<p class='subtitulo1'>Consulta Paso $paso : </p>";
						echo $cons;
					}
			$res =  pg_query($cnx, $cons);
			echo "<p class='mensajeEjecucion'><span class = 'subtitulo1'>Paso $paso: </span>  Se ha modificado el campo codigo  de la tabla  Central.EPS a character varying(50)  </p> ";
			
		}
		
		function cambiarCampoNit($paso) {
		
			$cnx = conectar_postgres();
			$cons = "ALTER TABLE Central.Eps  ALTER COLUMN Nit TYPE character varying(50)";
				if ($_GET['verConsultas'] == 'true') {
						echo "<p class='subtitulo1'>Consulta Paso $paso : </p>";
						echo $cons;
					}
			$res =  pg_query($cnx, $cons);
			echo "<p class='mensajeEjecucion'><span class = 'subtitulo1'>Paso $paso: </span>  Se ha modificado el campo Nit de la tabla  Central.EPS a character varying(50)  </p> ";
			
		}
		
		function cambiarCampoEps($paso) {
		
			$cnx = conectar_postgres();
			$cons = "ALTER TABLE Central.Eps  ALTER COLUMN Eps TYPE character varying(255)";
				if ($_GET['verConsultas'] == 'true') {
						echo "<p class='subtitulo1'>Consulta Paso $paso : </p>";
						echo $cons;
					}
			$res =  pg_query($cnx, $cons);
			echo "<p class='mensajeEjecucion'><span class = 'subtitulo1'>Paso $paso: </span>  Se ha modificado el campo Eps de la tabla  Central.EPS a character varying(255)  </p> ";
			
		}
		
		
		
		
		
		
		/* Termina definicion de funciones */
		
		
		
		/* Inicia la ejecucion de funciones */
		
		if($_GET['esquema']="Central") {
		
			echo "<fieldset>";			
			echo "<legend> Migracion Esquema Central </legend>";
			echo "<br>";
			echo "<span align='left'> <a href='../index.php?migracion=MIG003' class = 'link1'> Panel de Administracion </a> </span>";

			
			eliminarCentrosCosto(0);
			migrarNominaCesantias(1);
			migrarCentralCostos(2);
			migrarCierrexPeriodos(3);
			migrarDepartamentos(4);
			migrarEntidadesBancarias(5);
			cambiarCampoCodigo(6);
			cambiarCampoNit(7);
			cambiarCampoEps(8);
			migrarEPS(9);
			migrarEstados(10);
			migrarEstadosCiviles(11);
			migrarEstilos(12);
			migrarImpactos(13);
			migrarListaSexo(14);
			migrarMunicipios(15);
			migrarMeses(16);
			migrarRegimenTercero(17);
	 		migrarTiposTercero(18);
			migrarTiposDocumento(19);
			migrarTiposPersona(20);
			migrarTiposSangre(21);


			echo "<br>";
			echo "<span align='right'> <a href='revertir.php?accion=revertirMigracion' class = 'link1'> Revertir Migracion Esquema Central </a> </span>";
			
			
			echo "</fieldset>";
			
		}
			
			
		
		/* Termina  la ejecucion de funciones */
		
		
		
		
	
	
	
	?>
