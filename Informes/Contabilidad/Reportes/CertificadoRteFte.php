		<?
				if($DatNameSID){session_name("$DatNameSID");}
				session_start();
				include("Informes.php");
				include_once("General/Configuracion/Configuracion.php");
				$ND=getdate();
		?>
		
	<html>
		<head>
			<?php echo $codificacionMentor; ?>
			<?php echo $autorMentor; ?>
			<?php echo $titleMentor; ?>
			<?php echo $iconMentor; ?>
			<?php echo $shortcutIconMentor; ?>
			<link rel="stylesheet" type="text/css" href="../../../General/Estilos/estilos.css">
			<script language="javascript">
				function SelTercero()
				{
					frames.FrameOpener.location.href='/Contabilidad/BusquedaxOtros.php?DatNameSID=<? echo $DatNameSID?>&Tipo=Tercero&Campo=Tercero';
					document.getElementById('FrameOpener').style.position='absolute';
					document.getElementById('FrameOpener').style.top='50px';
					document.getElementById('FrameOpener').style.left='15px';
					document.getElementById('FrameOpener').style.display='';
					document.getElementById('FrameOpener').style.width='690';
					document.getElementById('FrameOpener').style.height='390';
				
				}

				function SelCuenta(Anio,SelCuenta)
				{
					frames.FrameOpener.location.href='/Contabilidad/BusquedaxOtros.php?DatNameSID=<? echo $DatNameSID?>&Tipo=Cuentas&Campo='+SelCuenta+'&Formulario=FORMA&Anio='+Anio;
					document.getElementById('FrameOpener').style.position='absolute';
					document.getElementById('FrameOpener').style.top='50px';
					document.getElementById('FrameOpener').style.left='15px';
					document.getElementById('FrameOpener').style.display='';
					document.getElementById('FrameOpener').style.width='690';
					document.getElementById('FrameOpener').style.height='390';
				
				}

			</script>
		</head>	
			<body <?php echo $backgroundBodyMentor; ?> >
				<?php
						$rutaarchivo[0] = "CONTABILIDAD";
						$rutaarchivo[1] = "REPORTES";
						$rutaarchivo[2] = "CERTIFICADO DE RETENCION EN LA FUENTE";
						
						mostrarRutaNavegacionEstatica($rutaarchivo);					
					?>
				<div align="center" style="margin-top:50px;margin-bottom:50px;">
					<form name="FORMA" method="post">
						<table class="tabla2"  <?php echo $borderTabla2Mentor; echo $bordercolorTabla2Mentor; echo $cellspacingTabla2Mentor; echo $cellpaddingTabla2Mentor; ?> >
							<tr>
								<td class="encabezado2Horizontal" colspan="5" > CERTIFICADO DE RETENCI&OacuteN EN LA FUENTE</td>
							</tr>
							<tr>
								<td class= "encabezado2VerticalInvertido">TODOS LOS TERCEROS</td>
								<td>
									<select name="Todos">
										<option value="No">No</option>
										<option value="Si">Si</option>
									</select>
								</td>
								<td class= "encabezado2VerticalInvertido">SELECCIONE TERCERO</td>
								<td><input type="Text" name="Tercero" onclick="SelTercero()"></td>
							</tr>
							<tr>
								<td class= "encabezado2VerticalInvertido">MENSAJE</td>
								<td colspan="3">
									<textarea style="width:450px;height:200px;" name="Mensaje">
										La Divisi&oacute;n Administrativa y Financiera con el fin de dar cumplimiento a las disposiciones vigentes sobre la retencion en la fuente, certifica que durante el periodo 
										comprendido entre: Enero 01 y Diciembre 31 del a&ntilde;o gravable de Anio
									</textarea>
								</td>
							</tr>
							<tr>
								<td class= "encabezado2VerticalInvertido">CUENTA INICIAL</td>
								<td><input type="Text" name="CtaInicial" onclick="SelCuenta('<? echo $Anio?>','CtaInicial')"></td>
								<td class= "encabezado2VerticalInvertido">CUENTA FINAL</td>
								<td><input type="Text" name="CtaFinal" onclick="SelCuenta('<? echo $Anio?>','CtaFinal')"></td>
							</tr>
						</table>
							<br>
						<input type="Button" name="Generar" class="boton2Envio" value="Generar Certificado" onclick="open('ImpCertificadoReteFte.php?DatNameSID=<? echo $DatNameSID?>&Tercero='+Tercero.value+'&CtaInicial='+CtaInicial.value+'&CtaFinal='+CtaFinal.value+'&Mensaje='+Mensaje.value+'&Anio=<?echo $Anio?>&Todos='+Todos.value,'','width=800,height=600,scrollbars=yes')">
					</form>
					<iframe id="FrameOpener" name="FrameOpener" style="display:none" frameborder="0" height="1" style="border:#e5e5e5 ridge" ></iframe>
				</div>
		</body>
	</html>	