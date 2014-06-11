<html>	
		<head>
			<title> Reversion Migracion Esquema Contabilidad </title>
			<link rel="stylesheet" type="text/css" href="../General/estilos/estilos.css">
		</head>
		
		<?php
	
			include('../Conexiones/conexion.php');
			
		/* Inicia la definicion de funciones */
		
			function eliminarRegistros($paso, $esquema, $tabla) {
			
			
			
				$cnx = conectar_postgres();
				$cons = "DELETE FROM  $esquema.$tabla";
					if ($_GET['verConsultas'] == 'true') {
						echo "<p class='subtitulo1'>Consulta Paso $paso : </p>";
						echo $cons;
					}
				$res =  pg_query($cons);
				echo "<p class='mensajeEjecucion'> <span class = 'subtitulo1'>Paso $paso: </span> Se han eliminado los registros de la tabla $esquema.$tabla </p> ";
							
			}
			
			
			
			
			
		
		/* Termina la definicion de funciones */
		
		
		/* Inicia la ejecucion de las funciones */
		
		if($_GET['accion']=="revertirMigracion") {
		
			echo "<fieldset>";			
			echo "<legend> Reversion migracion Esquema Contabilidad</legend>";
			echo "<br>";
			echo "<div > <a href='../index.php?migracion=MIG009' class= 'link1'> Panel de Administracion </a> </div>";
			
			eliminarRegistros(1, "Contabilidad", "Movimiento");	
			eliminarRegistros(2, "Contabilidad", "CruzarComprobantes");	
			eliminarRegistros(3, "Contabilidad", "ConceptosPagoxcc");	
			eliminarRegistros(4, "Contabilidad", "ConceptosPago");	
			eliminarRegistros(5, "Contabilidad", "ConceptosAfectacion");	
			eliminarRegistros(6, "Contabilidad", "Comprobantes");
			eliminarRegistros(7, "Contabilidad", "TiposComprobante");		
			eliminarRegistros(8, "Presupuesto", "Comprobantes");	
			eliminarRegistros(9, "Presupuesto", "TiposComprobante");	
			eliminarRegistros(10, "Contabilidad", "PlanCuentas");	
			eliminarRegistros(11, "Contabilidad", "EstructuraPUC");	
			eliminarRegistros(12, "Contabilidad", "FuentesFinanciacion");	
			eliminarRegistros(13, "Contabilidad", "NaturalezaCuentas");	
			eliminarRegistros(14, "Contabilidad", "TiposCuenta");	
			eliminarRegistros(15, "Contabilidad", "BasesRetencion");	
			eliminarRegistros(16, "Contabilidad", "CruzarComprobantes");	
			eliminarRegistros(17, "Contabilidad", "CuentasCierre");	
			eliminarRegistros(18, "Contabilidad", "EstructuraCheques");	
			eliminarRegistros(19, "Contabilidad", "SaldosConciliacion");
			eliminarRegistros(10, "Contabilidad", "ConvDirecciones");			
				
			
			
				
			
			
			echo "<p class='mensajeEjecucion'> <span class = 'error1'>Reversion finalizada :  </span> La reversion del  Esquema Contabilidad ha finalizado. </p> ";
			
		
		
		
		}
		
		
		
		/* Termina  la ejecucion de las funciones */
		
		
			
		
		
		