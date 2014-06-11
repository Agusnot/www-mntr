
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
		
		<title>Migraciones  OH-Mentor</title>
		<link rel="stylesheet" type="text/css" href="General/estilos/estilos.css">
	</head>
	
	<body>
	
	
		<?php
			session_start();
			include('Conexiones/conexion.php');
			$_SESSION["compania"]="CLINICA SAN JUAN DE DIOS";
			
			
			
		
		?>
		
		<p align="center" style = 'font-size: 14px; color:#000033; font-weight:bold;'> Interfaz de administracion de migraciones </p>
		
		<fieldset>
			
			<legend> Datos Generales Migracion</legend>
			
			
				<p style="font-size:13px; color:#000033; font-weight:bold;"> Base de datos MySQL(Origen) </p>
				
					<?php $cnxmysql= conectar_mysql("Salud"); 
						if ($cnxmysql == FALSE){
							
							echo "<p> <span style='font-weight: 300; color:#FF0000'>  Error de conexion MySQL   </span>     </p>";
							
						}
					?>	
					
				<p> <span style="font-weight: 300">  Base de datos : </span>  De acuerdo a la solicitud  </p>
				
				<p> <span style="font-weight: 300">  Host : </span> <?php echo  $hostMySQL;?>   </p>
				
				<br />
				
				<p style="font-size:13px; color:#000033; font-weight:bold;"> Base de datos Postgresql(Destino) </p>
					
				<?php $cnxpostgresql= conectar_postgres(); 
						if ($cnxpostgresql == FALSE){							
							echo "<p> <span style='font-weight: 300; color:#FF0000'>  Error de conexion Postgresql   </span>     </p>";
						}
				?>
				
				<p> <span style="font-weight: 300">  Base de datos : </span>  <?php echo $bdPostgresql; ?> </p>
				
				<p> <span style="font-weight: 300">  Host : </span> <?php echo  $hostPostgresql;?>   </p>
			
		
		  </fieldset>
		  
		<?php 
		
			if($_GET['migracion']=="MIG001")  { 
				 echo "<p> <a href='Consumo/index.php?esquema=Consumo' class = 'link1'> Migrar Esquema Consumo</a> </p>";
			}
			
			if($_GET['migracion']=="MIG002")  { 
				 echo "<p> <a href='Central/index.php?esquema=Central' class = 'link1'> Migrar Esquema Central</a> </p>";
			}
			
			if($_GET['migracion']=="MIG003")  { 
				 echo "<p> <a href='Central/Terceros/index.php?tabla=Terceros' class = 'link1'> Migrar Terceros</a> </p>";
			}
			
			if($_GET['migracion']=="MIG004")  { 
				 echo "<p> <a href='Central/Usuarios/index.php?tabla=Usuarios' class = 'link1'> Migrar Usuarios</a> </p>";
			}
			
			if($_GET['migracion']=="MIG005")  { 
				 echo "<p> <a href='Consumo/Suministros/index.php?tabla=suministros' class = 'link1'> Migrar Suministros </a> </p> ";
			}
			
			if($_GET['migracion']=="MIG006")  { 
				 echo "<p> <a href='Consumo/Farmacia/index.php?tabla=Farmacia' class = 'link1'> Migrar Farmacia </a> </p> ";
			}
						
			
			if($_GET['migracion']=="MIG007")  { 
				 echo "<p> <a href='Consumo/Movimiento/EntradasFarmacia.php?tabla=Entradas_Farmacia' class = 'link1'> Migrar  Movimientos Entradas Farmacia</a> </p> ";
			}
			
			
			if($_GET['migracion']=="MIG008")  { 
				 echo "<p> <a href='Consumo/Movimiento/SalidasMedicamentos.php?tabla=Salidas_Medicamentos' class = 'link1'> Migrar  Movimientos (SalidasMedicamentos)</a> </p> ";
			}
			
			if($_GET['migracion']=="MIG009")  { 
				 echo "<p> <a href='Consumo/Movimiento/Devoluciones.php?tabla=Devoluciones' class = 'link1'> Migrar  Devoluciones</a> </p> ";
			}
			
			if($_GET['migracion']=="MIG010")  { 
				 echo "<p> <a href='Consumo/Lotes/configuracionLotes.php?accion=ConfigurarLotes' class = 'link1'> Realizar configuracion de los Lotes</a> </p> ";
			}
			
			
			
			if($_GET['migracion']=="MIG011")  { 
				 echo "<p> <a href='ContratacionSalud/index.php?esquema=ContratacionSalud' class = 'link1'> Migrar Esquema Contratacion Salud </a> </p> ";
			}
			
			
			
			if($_GET['migracion']=="MIG012")  { 
				 echo "<p> <a href='Salud/index.php?esquema=Salud' class = 'link1'> Migrar Esquema Salud </a> </p> ";
			}
			
			
			if($_GET['migracion']=="MIG013")  { 
				 echo "<p> <a href='HistoriaClinica/Pacientes/index.php?tabla=Pacientes' class = 'link1'> Migrar Pacientes </a> </p> ";
			}
			
			
			if($_GET['migracion']=="MIG014")  { 
				 echo "<p> <a href='Salud/Servicios/index.php?tabla=Salud_Servicios' class = 'link1'> Migrar tabla Salud.Servicios</a> </p> ";
			}
			
			if($_GET['migracion']=="MIG015")  { 
				 echo "<p> <a href='Salud/PacientesxPabellones/index.php?tabla=PacientesxPabellones' class = 'link1'> Migrar  Pacientes por Pabellones </a> </p> ";
			}
			
			if($_GET['migracion']=="MIG016")  { 
				 echo "<p> <a href='HistoriaClinica/index.php?esquema=Historia_Clinica' class = 'link1'> Migrar Esquema Historia Clinica  </a> </p> ";
			}			
			
			
			if($_GET['migracion']=="MIG017")  { 
				 echo "<p> <a href='HistoriaClinicaFrms/index.php?esquema=HistoriaClinicaFrms' class = 'link1'> Migrar Esquema HistoriaClinicaFrms </a> </p> ";
			}
			
			
			
			if($_GET['migracion']=="MIG018")  { 
				 echo "<p> <a href='HistoriaClinicaFrms/HistoriaClinica/Triage.php?formato=Triage' class = 'link1'> Migrar Formato Triage  </a> </p> ";
			}
			
			if($_GET['migracion']=="MIG019")  { 
				 echo "<p> <a href='HistoriaClinicaFrms/HistoriaClinica/PrimeraVez.php?formato=PrimeraVez' class = 'link1'> Migrar Formato Hoja de Ingreso   </a> </p> ";
			}
			
			if($_GET['migracion']=="MIG020")  { 
				 echo "<p> <a href='HistoriaClinicaFrms/HistoriaClinica/Control.php?formato=Control' class = 'link1'> Migrar Formato Control </a> </p> ";
			}
			
			
			
			if($_GET['migracion']=="MIG021")  { 
				 echo "<p> <a href='HistoriaClinicaFrms/HistoriaClinica/NotasEvolucion.php?formato=NotasEvolucion' class = 'link1'> Migrar  Notas de Evolucion   </a> </p> ";
			}
			
			if($_GET['migracion']=="MIG022")  { 
				 echo "<p> <a href='HistoriaClinicaFrms/HistoriaClinica/Epicrisis.php?formato=Epicrisis' class = 'link1'> Migrar Formato Epicrisis  </a> </p> ";
			}
			
			if($_GET['migracion']=="MIG023")  { 
				 echo "<p> <a href='HistoriaClinicaFrms/HistoriaClinica/Referencia.php?formato=Referencia' class = 'link1'> Migrar Formato Referencia  </a> </p> ";
			}
			
			if($_GET['migracion']=="MIG024")  { 
				 echo "<p> <a href='HistoriaClinicaFrms/HistoriaClinica/Contrarreferencia.php?formato=Contrarreferencia' class = 'link1'> Migrar Formato Contrarreferencia  </a> </p> ";
			}
			
			if($_GET['migracion']=="MIG025")  { 
				 echo "<p> <a href='HistoriaClinicaFrms/HistoriaClinica/FormatoNoPosMedicamentos.php?formato=FormatoNoPosMedicamentos' class = 'link1'> Migrar Formato Formato No POS (Medicamentos)  </a> </p> ";
			}
			
			
			if($_GET['migracion']=="MIG026")  { 
				 echo "<p> <a href='HistoriaClinicaFrms/HistoriaClinica/FormatoNoPosProcedimientos.php?formato=FormatoNoPosProcedimientos' class = 'link1'> Migrar Formato Formato No POS (Procedimientos)  </a> </p> ";
			}		
			
			
			if($_GET['migracion']=="MIG027")  { 
				 echo "<p> <a href='HistoriaClinicaFrms/HistoriaClinica/JustificacionProximaCita.php?formato=JustificacionProximaCita' class = 'link1'> Migrar Formato Justificacion Proxima Cita  </a> </p> ";
			}
			
					
			
			if($_GET['migracion']=="MIG028")  { 
				 echo "<p> <a href='HistoriaClinicaFrms/Enfermeria/NotasEvolucion.php?formato=NotasEvolucion' class = 'link1'> Migrar Formato Notas Enfermeria (Evolucion)  </a> </p> ";
			}
			
			if($_GET['migracion']=="MIG029")  { 
				 echo "<p> <a href='HistoriaClinicaFrms/Enfermeria/NotasEvolucionNocturna.php?formato=NotasEvolucionNocturna' class = 'link1'> Migrar Formato Notas Enfermeria (Evolucion Nocturna)  </a> </p> ";
			}
			
			if($_GET['migracion']=="MIG030")  { 
				 echo "<p> <a href='HistoriaClinicaFrms/Enfermeria/NotasTraslados.php?formato=NotasTraslados' class = 'link1'> Migrar Formato Notas Enfermeria (Traslados)  </a> </p> ";
			}
			
			if($_GET['migracion']=="MIG031")  { 
				 echo "<p> <a href='HistoriaClinicaFrms/Enfermeria/NotasGenerales.php?formato=NotasGenerales' class = 'link1'> Migrar Formato Notas Enfermeria (Generales)  </a> </p> ";
			}
			
			if($_GET['migracion']=="MIG032")  { 
				 echo "<p> <a href='HistoriaClinicaFrms/Enfermeria/HojaGlasgow.php?formato=HojaGlasgow' class = 'link1'> Migrar Formato Hoja de Glasgow  </a> </p> ";
			}
			
			if($_GET['migracion']=="MIG033")  { 
				 echo "<p> <a href='HistoriaClinicaFrms/Enfermeria/Convulsiones.php?formato=Convulsiones' class = 'link1'> Migrar Formato Convulsiones </a> </p> ";
			}
			
			if($_GET['migracion']=="MIG034")  { 
				 echo "<p> <a href='HistoriaClinicaFrms/Enfermeria/ControlNeurologico.php?formato=ControlNeurologico' class = 'link1'> Migrar Formato Control Neurologico </a> </p> ";
			}
			
			if($_GET['migracion']=="MIG035")  { 
				 echo "<p> <a href='HistoriaClinicaFrms/PacienteSeguro/Evasiones.php?formato=Evasiones' class = 'link1'> Migrar Formato Evasiones </a> </p> ";
			}
			
			if($_GET['migracion']=="MIG036")  { 
				 echo "<p> <a href='HistoriaClinicaFrms/PacienteSeguro/Farmacovigilancia.php?formato=Farmacovigilancia' class = 'link1'> Migrar Formato Farmacovigilancia </a> </p> ";
			}
			
			if($_GET['migracion']=="MIG037")  { 
				 echo "<p> <a href='HistoriaClinicaFrms/PacienteSeguro/InfeccionIntrahospitalaria.php?formato=InfeccionIntrahospitalaria' class = 'link1'> Migrar Formato Infeccion Intrahospitalaria </a> </p> ";
			}
			
			if($_GET['migracion']=="MIG038")  { 
				 echo "<p> <a href='HistoriaClinicaFrms/Enfermeria/Glucometria.php?formato=Glucometria' class = 'link1'> Migrar Formato Glucometria </a> </p> ";
			}
			
			
			if($_GET['migracion']=="MIG039")  { 
				 echo "<p> <a href='HistoriaClinicaFrms/PacienteSeguro/Caidas.php?formato=Caidas' class = 'link1'> Migrar Formato Caidas </a> </p> ";
			}
			
			
			if($_GET['migracion']=="MIG040")  { 
				 echo "<p> <a href='HistoriaClinicaFrms/PacienteSeguro/ConductaSuicida.php?formato=ConductaSuicida' class = 'link1'> Migrar Formato Conducta Suicida </a> </p> ";
			}
			
			if($_GET['migracion']=="MIG041")  { 
				 echo "<p> <a href='HistoriaClinicaFrms/PacienteSeguro/ConsumoSpaIntrahospitalario.php?formato=ConsumoSpaIntrahospitalario' class = 'link1'> Migrar Formato Consumo SPA Intrahospitalario </a> </p> ";
			}
			
			if($_GET['migracion']=="MIG042")  { 
				 echo "<p> <a href='HistoriaClinicaFrms/Administrativos/DatosAcudientes.php?formato=DatosAcudientes' class = 'link1'> Migrar Formato Datos Acudientes </a> </p> ";
			}
			
			if($_GET['migracion']=="MIG043")  { 
				 echo "<p> <a href='HistoriaClinicaFrms/Administrativos/NotasGenerales.php?formato=NotasGenerales' class = 'link1'> Migrar Formato Notas Generales (Administrativos) </a> </p> ";
			}
			
			if($_GET['migracion']=="MIG044")  { 
				 echo "<p> <a href='HistoriaClinicaFrms/Enfermeria/Requisa.php?formato=Requisa' class = 'link1'> Migrar Formato Requisa </a> </p> ";
			}
			
			if($_GET['migracion']=="MIG045")  { 
				 echo "<p> <a href='HistoriaClinicaFrms/PacienteSeguro/Tecnovigilancia.php?formato=Tecnovigilancia' class = 'link1'> Migrar Formato Tecnovigilancia </a> </p> ";
			}
			
			if($_GET['migracion']=="MIG046")  { 
				 echo "<p> <a href='HistoriaClinicaFrms/PacienteSeguro/OtroReporte.php?formato=OtroReporte' class = 'link1'> Migrar Formato Otro Reporte </a> </p> ";
			}
			
			if($_GET['migracion']=="MIG047")  { 
				 echo "<p> <a href='HistoriaClinicaFrms/Administrativos/NotificacionFamiliares.php?formato=NotificacionFamiliares' class = 'link1'> Migrar Formato Notificacion a Familiares </a> </p> ";
			}
			
			
			
			if($_GET['migracion']=="MIG048")  { 
				 echo "<p> <a href='HistoriaClinicaFrms/HistoriaClinica/Incapacidad.php?formato=Incapacidad' class = 'link1'> Migrar Formato de Incapacidad </a> </p> ";
			}
			
			
			if($_GET['migracion']=="MIG049")  { 
				 echo "<p> <a href='HistoriaClinicaFrms/Enfermeria/PacienteInmovilizado.php?formato=PacienteInmovilizado' class = 'link1'> Migrar Formato Paciente Inmovilizado </a> </p> ";
			}
			
			if($_GET['migracion']=="MIG050")  { 
				 echo "<p> <a href='HistoriaClinicaFrms/HistoriaClinica/PlanAlta.php?formato=PlanAlta' class = 'link1'> Migrar Formato Plan de Alta </a> </p> ";
			}
			
			if($_GET['migracion']=="MIG051")  { 
				 echo "<p> <a href='HistoriaClinicaFrms/HistoriaClinica/JuntaMedica.php?formato=JuntaMedica' class = 'link1'> Migrar Formato Junta Medica </a> </p> ";
			}
			
			if($_GET['migracion']=="MIG052")  { 
				 echo "<p> <a href='HistoriaClinicaFrms/HistoriaClinica/Justificaciones.php?formato=Justificaciones' class = 'link1'> Migrar Formato Justificaciones </a> </p> ";
			}
			
			if($_GET['migracion']=="MIG053")  { 
				 echo "<p> <a href='HistoriaClinicaFrms/Administrativos/NotasSIAU.php?formato=NotasSIAU' class = 'link1'> Migrar Formato Notas SIAU </a> </p> ";
			}
			
			if($_GET['migracion']=="MIG054")  { 
				 echo "<p> <a href='HistoriaClinicaFrms/Administrativos/NotasEgreso.php?formato=NotasEgreso' class = 'link1'> Migrar Formato Notas Egreso (Administrativos) </a> </p> ";
			}
			
			if($_GET['migracion']=="MIG055")  { 
				 echo "<p> <a href='HistoriaClinicaFrms/Administrativos/AyudasDiagnosticas.php?formato=AyudasDiagnosticas' class = 'link1'> Migrar Formato Ayudas Diagnosticas </a> </p> ";
			}
			
			
			if($_GET['migracion']=="MIG056")  { 
				 echo "<p> <a href='HistoriaClinicaFrms/HistoriaClinica/FormulaExterna.php?formato=FormulaExterna' class = 'link1'> Migrar Formato Formula Externa </a> </p> ";
			}
			
			
			
			
			
			
			
			
			
			
			
			
			
			
			
			
			
			
			
			if($_GET['migracion']=="MIG060")  { 
				 echo "<p> <a href='Consumo/SolicitudConsumo/index.php?tabla=SolicitudConsumo' class = 'link1'> Migrar  tabla  Consumo.SolicitudConsumo </a> </p> ";
			}
			
			if($_GET['migracion']=="MIG061")  { 
				 echo "<p> <a href='Consumo/Movimiento/suministros.php?tabla=Suministros_movimiento' class = 'link1'> Migrar  Movimientos del area de Suministros </a> </p> ";
			}
			
			
			if($_GET['migracion']=="MIG062")  { 
				echo "<p> <a href='Contabilidad/index.php?esquema=Contabilidad' class = 'link1'> Migrar Esquema Contabilidad </a> </p> ";
			}
			
						 
			if($_GET['migracion']=="MIG063")  { 
				echo "<p> <a href='Contabilidad/Movimiento/index.php?tabla=Contabilidad_movimiento' class = 'link1'> Migrar Contabilidad.movimiento </a> </p> ";
			}
			
			
			if($_GET['migracion']=="MIG064")  { 
				 echo "<p> <a href='Facturacion/index.php?esquema=Facturacion' class = 'link1'> Migrar Esquema Facturacion</a> </p> ";
			}
			
			if($_GET['migracion']=="MIG065")  { 
				 echo "<p> <a href='Facturacion/FacturasCredito/index.php?tabla=FacturasCredito' class = 'link1'> Migrar Tabla FacturasCredito</a> </p> ";
			}
			
			if($_GET['migracion']=="MIG066")  { 
				 echo "<p> <a href='Facturacion/DetalleFactura/index.php?tabla=DetalleFactura' class = 'link1'> Migrar Tabla FacturasDetalle </a> </p> ";
			}
			
			
			if($_GET['migracion']=="MIG067")  { 
				 echo "<p> <a href='Salud/OrdenesMedicas/index.php?tabla=Ordenes_Medicas' class = 'link1'> Migrar tabla Salud.OrdenesMedicas </a> </p> ";
			}
			
			
			
			
			if($_GET['migracion']=="MIG068")  { 
				 echo "<p> <a href='Salud/Agenda/index.php?tabla=Salud_agenda' class = 'link1'> Migrar tabla Salud.Agenda </a> </p> ";
			}
			
			
			
			
			
			
			
			?>	

</body>
</html>
