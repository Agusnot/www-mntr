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
				function Mostrar(x)	{
					open("/Informes/Almacen/Formatos/OrdenCompra.php?DatNameSID=<? echo $DatNameSID?>&AlmacenPpal=<? echo $AlmacenPpal?>&Comprobante=Orden de Compra&Numero="+x,'','width=700,height=600,scrollbars=yes');	
				}
			</script>
		</head>	
		
		<body <?php echo $backgroundBodyMentor; ?>>
			<div <?php echo $alignDiv2Mentor; ?> class="div2">
				<form name="FORMA" method="post">
					<input type="Hidden" name="Cedula" value="<? echo $Cedula?>" />
					<input type="Hidden" name="AlmacenPpal" value="<? echo $AlmacenPpal?>" />
					<input type="hidden" name="DatNameSID" value="<? echo $DatNameSID?>" />
						<table class="tabla2" style="margin-top:25px;margin-bottom:25px;" <?php echo $borderTabla2Mentor; echo $bordercolorTabla2Mentor; echo $cellspacingTabla2Mentor; echo $cellpaddingTabla2Mentor; ?>>
							<tr>
								<td  colspan="4" class="encabezado2Horizontal"> ORDEN DE COMPRA <? echo strtoupper($Nombre);?></td>
							</tr>
							<tr>
								<td class="encabezado2VerticalInvertido">PERIODO </td>
								<td style="text-align:center;">
									<input type="text" name="FechaIni" value="<? echo $FechaIni?>" size="12" readonly="yes" onClick="popUpCalendar(this, FORMA.FechaIni, 'yyyy-mm-dd')" />
								</td>
								<td>
									<input type="text" name="FechaFin" value="<? echo $FechaFin?>" size="12" readonly="yes" onClick="popUpCalendar(this, FORMA.FechaFin, 'yyyy-mm-dd')"/>
								</td>
								<td>
									<input type="button" name="Ver" class="boton2Envio" value="Ver" onClick="FORMA.submit()" /></td>
								</td>	
							</tr>
							<tr>
								<td  colspan="2" class="encabezado2VerticalInvertido" >TOTAL ORDENES DE COMPRA ESTE PERIODO</td>
								<td colspan="2" style="text-align:center;">
									<input type="text" name="TotPeriodo" value="<? echo $TotPeriodo?>" readonly style="border:thin; text-align:center" />
								</td>
							</tr>
						</table>
						
						<table class="tabla2" style="margin-top:25px;margin-bottom:25px;"  <?php echo $borderTabla2Mentor; echo $bordercolorTabla2Mentor; echo $cellspacingTabla2Mentor; echo $cellpaddingTabla2Mentor; ?>>
							<tr>
								<td class="encabezado2Horizontal">FECHA</td>
								<td class="encabezado2Horizontal">N&Uacute;MERO</td>
								<td class="encabezado2Horizontal">DETALLE</td>
								<td class="encabezado2Horizontal">VALOR</td>
								<td class="encabezado2Horizontal">APROBADO POR</td>
								<td class="encabezado2Horizontal">FECHA APROBACI&Oacute;N</td>
							</tr>
							<?
							$cons="Select Fecha,Numero,Detalle,Sum(TotCosto),Aprobadox,FechaAprobac
							from Consumo.Movimiento where Movimiento.Compania='$Compania[0]' and Cedula='$Cedula' and
							Comprobante='Orden de Compra' and AlmacenPpal='$AlmacenPpal' and Fecha>='$FechaIni' and Fecha<='$FechaFin' 
							Group by Numero,Fecha,Detalle,Aprobadox,FechaAprobac";
							//echo $cons;
							$TotPeriodo=0;
							$res=ExQuery($cons);
							if(ExNumRows($res))	{
								while($fila=ExFetch($res)){
									if($fila[4]!=""){
										echo "<tr style='text-align:center'><td>$fila[0]</td>";
										?>
										<td style="cursor:hand" title="Ver Orden de Compra" 
										onMouseOver="this.bgColor='#AAD4FF'" 
										onmouseout="this.bgColor='#FFFFFF'" 
										onclick="Mostrar(<? echo $fila[1];?>)" 
										align="center">
										<?
										echo "$fila[1]</td><td>$fila[2]</td>
										<td>".number_format($fila[3],2)."</td><td>$fila[4]</td><td>$fila[5]</td></tr>";
										$TotPeriodo++;
									}
								}	
							}
							else
							{
								echo "<div align='center' style='margin-top:25px;margin-bottom:25px;' class='mensaje1'>No existen registros para este periodo</div>";	
							}
							
							?>
							<script language="javascript">document.FORMA.TotPeriodo.value="<? echo $TotPeriodo?>"</script>
						</table>
				</form>
			</div>	
		</body>
	</html>	