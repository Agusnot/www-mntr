		<?
			if($DatNameSID){session_name("$DatNameSID");}
			include("Funciones.php");
			include_once("General/Configuracion/Configuracion.php");	
			session_start();
		?>
	
	<html>
		<head>
			<?php echo $codificacionMentor; ?>
			<?php echo $autorMentor; ?>
			<?php echo $titleMentor; ?>
			<?php echo $iconMentor; ?>
			<?php echo $shortcutIconMentor; ?>
			<link rel="stylesheet" type="text/css" href="../../../General/Estilos/estilos.css">	
			<script language='javascript' src="/calendario/popcalendar.js"></script>
			<script language="javascript">
				function CerrarThis(){
						parent.document.getElementById('FrameOpener').style.position='absolute';
						parent.document.getElementById('FrameOpener').style.top='1px';
						parent.document.getElementById('FrameOpener').style.left='1px';
						parent.document.getElementById('FrameOpener').style.width='1';
						parent.document.getElementById('FrameOpener').style.height='1';
						parent.document.getElementById('FrameOpener').style.display='none';
				}
			</script>
		</head>	
		<body>
			<table border="0" cellpadding="0" cellspacing="0" width="100%" height="8px">
				<tr>
					<td width="98%" style="text-align:right;">&nbsp;</td>
					<td style="text-align:right;padding-right:5px;" title="Cerrar" 	onMouseOver="this.bgColor='#AAD4FF'" onmouseout="this.bgColor='#FFFFFF'">
						<img src="/Imgs/b_drop.png" style="cursor:hand;" onClick="CerrarThis()">
					</td>
				</tr>
			</table>
			<div align="center">
				<form name="FORMA" method="post">
					<input type="hidden" name="DatNameSID" value="<? echo $DatNameSID?>" />
						<table class="tabla1"  <?php echo $borderTabla1Mentor; echo $bordercolorTabla1Mentor; echo $cellspacingTabla1Mentor; echo $cellpaddingTabla1Mentor; ?>>
							<tr>
								<td colspan="4" class="encabezado1Horizontal"><? echo strtoupper($Nombre);?></td>
							</tr>
							<tr>
								<td class="encabezado1VerticalInvertido">PERIODO </td>
								<td style="text-align:center;">
									<input type="text" name="FechaIni" value="<? echo $FechaIni?>" size="12" readonly="yes" onClick="popUpCalendar(this, FORMA.FechaIni, 'yyyy-mm-dd')" />
								</td>
								<td style="text-align:center;">
									<input type="text" name="FechaFin" value="<? echo $FechaFin?>" size="12" readonly="yes" onClick="popUpCalendar(this, FORMA.FechaFin, 'yyyy-mm-dd')"/>
								</td>
								<td style="text-align:center;">
									<input type="button" class="boton2Envio" name="Ver" value="Ver" onClick="FORMA.submit()" />
								</td>
							</tr>
							<tr>
								<td colspan="2" class="encabezado1VerticalInvertido" >TOTAL SOLICITUDES ESTE PERIODO</td>
								<td colspan="2" style="text-align:center;">
									<input type="text" name="TotPeriodo" value="<? echo $TotPeriodo?>" readonly style="border:thin; text-align:center" />
								</td>
							</tr>
							<tr>
								<td colspan="2" class="encabezado1HorizontalInvertido">FECHA</td>
								<td colspan="2" class="encabezado1HorizontalInvertido">CANTIDAD DESPACHADA</td>
							</tr>
								<?
								$cons="Select Movimiento.Fecha,sum(Movimiento.Cantidad)
								from Consumo.Movimiento
								where 
								Movimiento.AutoId='$AutoId' and Movimiento.Cedula='$Tercero' and TipoComprobante='Salidas' and Movimiento.Estado='AC'
								and Movimiento.Fecha>='$FechaIni' and Movimiento.Fecha<='$FechaFin' and CentroCosto='$CC'
								group by Movimiento.Fecha order by Fecha Desc";
								$TotPeriodo=0;
								$res=ExQuery($cons);
								while($fila=ExFetch($res)){
									echo "<tr style='text-align:center'>";
										echo "<td colspan='2'>$fila[0]</td><td>$fila[1]</td>";
									echo "</tr>";
									$TotPeriodo=$TotPeriodo+$fila[1];
								}
								?>
							<script language="javascript">document.FORMA.TotPeriodo.value="<? echo $TotPeriodo?>"</script>
						</table>    	
				</form>
			</div>	
		</body>
	</html>	