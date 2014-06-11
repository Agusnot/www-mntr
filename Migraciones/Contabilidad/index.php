	<html>	
		<head>
			<title> Migracion  Esquema Contabilidad </title>
			<link rel="stylesheet" type="text/css" href="../General/estilos/estilos.css">
		</head>
	
	
	<?php
		session_start();
		
		include_once('../Conexiones/conexion.php');
		include_once('../General/funciones/funciones.php');
		include_once('EstructuraPUC/procedimiento.php');
		include_once('FuentesFinanciacion/procedimiento.php');
		include_once('NaturalezaCuentas/procedimiento.php');
		include_once('TiposCuenta/procedimiento.php');
		include_once('TiposRetencion/procedimiento.php');
		include('PlanCuentas/procedimiento.php');
		include('BasesRetencion/procedimiento.php');
		include('../Presupuesto/TiposComprobante/procedimiento.php');
		include('../Presupuesto/Comprobantes/procedimiento.php');
		include_once('TiposComprobante/procedimiento.php');
		include_once('Comprobantes/procedimiento.php');
		include_once('ConceptosAfectacion/procedimiento.php');
		include_once('ConceptosPago/procedimiento.php');
		include_once('ConceptosPagoxcc/procedimiento.php');	
		include_once('CruzarComprobantes/procedimiento.php');
		include_once('CuentasCierre/procedimiento.php');	
		include_once('EstructuraCheques/procedimiento.php');
		include_once('SaldosConciliacion/procedimiento.php');
		include_once('ConvDirecciones/procedimiento.php');	
		include_once('ActividadesEconomicas/procedimiento.php');

		
		
		
		
		
		
		
		/* Termina definicion de funciones */
		
		function crearArchivoErrores() {
		// Crea un archivo HTML donde se documentaran los registros que no se insertaron en la tabla de migraciones
			$fp = fopen("Errores/ReporteContabilidad.html", "w+");
			$encabezado = "<html> <head> <title> Reporte errores Esquema Contabilidad(Farmacia) </title> 
			<link rel='stylesheet' type='text/css' href='../../General/estilos/estilos.css'> </head>";
			fputs($fp, $encabezado);
			fclose($fp);
		}
		
		function eliminarRegistrosMovimiento() {
			
			$cnx = 	conectar_postgres();
			$cons = "DELETE FROM Contabilidad.movimiento"	;
			
			$res = @pg_query($cnx, $cons);
				if (!$res) {
					echo "<p class='error1'> Error de ejecucion </p>".pg_last_error()."<br>";
					echo "<p class= 'subtitulo1'>Comando SQL </p> <br>".$cons."<br>"; 				
					echo "<br><br>";
							
				}
		
		}
		
		
		
		
		/* Inicia la ejecucion de funciones */
		
		if($_GET['esquema']=="Contabilidad") {
		
			echo "<fieldset>";			
			echo "<legend> Migracion Esquema Contabilidad </legend>";
			echo "<br>";
			echo "<span align='left'> <a href='../index.php?migracion=MIG063' class = 'link1'> Panel de Administracion </a> </span>";
			echo "<br>";
			
			eliminarRegistrosMovimiento();
			crearArchivoErrores();
			migrarEstructuraPUC(1);
			eliminarPlanCuentas();
			eliminarFuentesFinanciacion(2);
			actualizarNaturazalezaCuentas(3);
			actualizarTiposCuenta(4);
			migrarPlanCuentas(5);
			migrarBasesRetencion(6);
			eliminarCruzarComprobantes();
			eliminarConceptosPagoxcc();
			eliminarConceptosPago();
			eliminarConcAfec();
			eliminarComprobantes();	
			eliminarTipoComp();
			eliminarPresupComp();
			eliminarPresupTipoComp();
			migrarPresupTipoComp(7);
			migrarPresupComp(8);
			migrarTipoComp(9);
			generarNotaAclaratoria(1,"Favor verificar la tabla Contabilidad.TiposComprobante");
			migrarComprobantes(10);
			migrarConcAfec(11);
			migrarConceptosPago(12);
			migrarConceptosPagoxcc(13);
			migrarCruzarComprobantes(14);
			migrarCuentasCierre(15);
			migrarEstCheques(16);
			migrarSaldosConc(17);
			migrarConvDirecciones(18);
			//migrarActividadesEconomicas(19);
			actualizarTiposRetencion(20);
			actualizarTiposComprobante("Ingreso","INGRESO");
			actualizarTiposComprobante("Egreso","EGRESO");
			actualizarTiposComprobante("Cuentas x Pagar","CUENTAS X PAGAR");
			actualizarTiposComprobante("Contables","CONTABLES");
			actualizarTiposComprobante("Facturas","FACTURAS");
			actualizarCruzarComprobante("Debe", "DEBE");
			actualizarCruzarComprobante("Haber", "HABER");
			
			
			
			
			$totalMySQLConcAfec= consultaConteoMySQL("Contabilidad","ConceptosAfectacion");
			$totalPostgresConcAfec =  consultaConteoPostgresql("Contabilidad", "ConceptosAfectacion");
			$totalPostgresConcAfecMigracion =  consultaConteoPostgresql("Contabilidad", "ConceptosAfectacionMigracion");	
			
			$totalMySQLConcPagoxcc= consultaConteoMySQL("Contabilidad","ConceptosPagoxcc");
			$totalPostgresConcPagoxcc =  consultaConteoPostgresql("Contabilidad", "ConceptosPagoxcc");
			$totalPostgresConcPagoxccMigracion =  consultaConteoPostgresql("Contabilidad", "ConceptosPagoxccMigracion");
			
			$totalMySQLCruzarComp= consultaConteoMySQL("Contabilidad","CruzarComprobantes");
			$totalPostgresCruzarComp =  consultaConteoPostgresql("Contabilidad", "CruzarComprobantes");
			$totalPostgresCruzarCompMigracion =  consultaConteoPostgresql("Contabilidad", "CruzarComprobantesMigracion");
			
			$totalMySQLPlanCuentas= consultaConteoMySQL("Contabilidad","PlanCuentas");
			$totalPostgresPlanCuentas=  consultaConteoPostgresql("Contabilidad", "PlanCuentas");
			$totalPostgresPlanCuentasMigracion =  consultaConteoPostgresql("Contabilidad", "PlanCuentasMigracion");	
			
			$totalMySQLSaldosConc= consultaConteoMySQL("Contabilidad","SaldosConciliacion");
			$totalPostgresSaldosConc =  consultaConteoPostgresql("Contabilidad", "SaldosConciliacion");
			$totalPostgresSaldosConcMigracion =  consultaConteoPostgresql("Contabilidad", "SaldosConciliacionMigracion");	
			
			echo "<br/> <br/> <br/> ";
			
			echo "<table border='2'>";
			
			echo "<tr>";
			echo "<td class='subtitulo1'> Tabla </td>";
			echo "<td class='subtitulo1'> Total Registros Origen (MySQL)</td>";
			echo "<td class='subtitulo1'>  Total Postgresql (Destino)</td>";
			echo "<td class='subtitulo1'>  Total Errores Migracion</td>";
			echo "</tr>";
			
			echo "<tr>";
			echo "<td> Contabilidad.ConceptosAfectacion </td>";
			echo "<td> $totalMySQLConcAfec  </td>";
			echo "<td> $totalPostgresConcAfec  </td>";
			echo "<td>  $totalPostgresConcAfecMigracion</td>";
			echo "</tr>";
			
			echo "<tr>";
			echo "<td> Contabilidad.ConceptosPagoxcc </td>";
			echo "<td> $totalMySQLConcPagoxcc  </td>";
			echo "<td> $totalPostgresConcPagoxcc  </td>";
			echo "<td>  $totalPostgresConcPagoxccMigracion </td>";
			echo "</tr>";
			
			echo "<tr>";
			echo "<td> Contabilidad.CruzarComprobantes </td>";
			echo "<td> $totalMySQLCruzarComp  </td>";
			echo "<td> $totalPostgresCruzarComp  </td>";
			echo "<td>  $totalPostgresCruzarCompMigracion </td>";
			echo "</tr>";
			
			
			echo "<tr>";
			echo "<td> Contabilidad.PlanCuentas </td>";
			echo "<td> $totalMySQLPlanCuentas  </td>";
			echo "<td> $totalPostgresPlanCuentas  </td>";
			echo "<td>  $totalPostgresPlanCuentasMigracion </td>";
			echo "</tr>";			
			
			echo "<tr>";
			echo "<td> Contabilidad.SaldosConciliacion </td>";
			echo "<td> $totalMySQLSaldosConc  </td>";
			echo "<td> $totalPostgresSaldosConc  </td>";
			echo "<td> $totalPostgresSaldosConcMigracion </td>";
			echo "</tr>";	
			
			echo "</table>";			
			echo "<br/> <br/> <br/> ";
			echo "<span align='right'> <a href='Errores/ReporteContabilidad.html' target='_blank' class = 'link1'> Ver Reporte de Errores</a> </span>";			
			echo "<br/> <br/>";			
			echo "<span align='right'> <a href='revertir.php?accion=revertirMigracion' class = 'link1'> Revertir Migracion Esquema Contabilidad </a> </span>";
			
			
			echo "</fieldset>";
			
		}
			
			
		
		/* Termina  la ejecucion de funciones */
		
		
		
		
	
	
	
	?>
