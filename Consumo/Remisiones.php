		<?
			if($DatNameSID){session_name("$DatNameSID");}
			session_start();
			include("Funciones.php");
			include("ObtenerSaldos.php");
			include_once("General/Configuracion/Configuracion.php");
		?>

		<script language="javascript">
			function CerrarThis()
			{
				parent.document.getElementById('FrameOpener').style.position='absolute';
				parent.document.getElementById('FrameOpener').style.top='1px';
				parent.document.getElementById('FrameOpener').style.left='1px';
				parent.document.getElementById('FrameOpener').style.width='1';
				parent.document.getElementById('FrameOpener').style.height='1';
				parent.document.getElementById('FrameOpener').style.display='none';
			}
		</script>
		<?
			$ND=getdate();

			if($Registrar)
			{
				while (list($val,$cad) = each ($Registra)) 
				{
					$cons3="Insert into Consumo.EntradasxRemisiones (Compania,AlmacenPpal,CompEntrada,NoCompEntrada,CompRemision,NoCompRemision,TMPCOD)
					values('$Compania[0]','$AlmacenPpal','$Comprobante','$Numero','$cad','$val','$TMPCOD')";
					$res3=ExQuery($cons3);echo ExError();
				}
				?>
				<script language="javascript">
					parent.frames.NuevoMovimiento.location.href=parent.frames.NuevoMovimiento.location.href;
					parent.frames.TotMovimientos.location.href='TotMovimientos.php?DatNameSID=<? echo $DatNameSID?>&TMPCOD=<? echo $TMPCOD?>&Comprobante=<? echo $Comprobante?>&Numero=<? echo $Numero?>&AlmacenPpal=<? echo $AlmacenPpal?>';
					CerrarThis();
				</script>
				<?
			}


			$cons = "Select Numero,Comprobante,Fecha,sum(TotCosto)+sum(VrIVA) from Consumo.Movimiento where Compania='$Compania[0]' and
			AlmacenPpal='$AlmacenPpal' and Cedula='$Cedula' and TipoComprobante='Remisiones' and Estado='AC'
			and (Comprobante || Numero) NOT in(Select (CompRemision || NoCompRemision) from Consumo.EntradasxRemisiones where Compania='$Compania[0]' and AlmacenPpal='$AlmacenPpal' )
			 Group By Numero,Comprobante,Fecha"; 
			$res = ExQuery($cons);echo ExError();
			$fila=ExFetch($res);
			
		?>
		
		<html>
			<head>
				<?php echo $codificacionMentor; ?>
				<?php echo $autorMentor; ?>
				<?php echo $titleMentor; ?>
				<?php echo $iconMentor; ?>
				<?php echo $shortcutIconMentor; ?>
				<link rel="stylesheet" type="text/css" href="../General/Estilos/estilos.css">
				<script language="javascript">
					function Mostrar(num){
						open("/Informes/Almacen/Formatos/ImpSolicitudConsumo.php?DatNameSID=<? echo $DatNameSID?>&IdSolicitud="+num,'','width=600,height=400,scrollbars=yes');
					}
				</script>
			</head>	
			<body>
				<div align="center">
					<form name="FORMA" method="post">
						<input type="hidden" name="DatNameSID" value="<? echo $DatNameSID?>" />
						<table class="tabla2" width="90%"   <?php echo $borderTabla2Mentor; echo $bordercolorTabla2Mentor; echo $cellspacingTabla2Mentor; echo $cellpaddingTabla2Mentor; ?>>
							<tr>
								<td colspan="6" class="encabezado2Horizontal" >REMISIONES PENDIENTES</td>
							</tr>
							<tr>
								<td class="encabezado2HorizontalInvertido">&nbsp;</td>
								<td class="encabezado2HorizontalInvertido">N&Uacute;MERO</td>
								<td class="encabezado2HorizontalInvertido">COMPROBANTE</td>
								<td class="encabezado2HorizontalInvertido">FECHA</td>
								<td class="encabezado2HorizontalInvertido">TOTAL</td>
							</tr>
							<?

							$res = ExQuery($cons);
							while($fila = ExFetch($res)){
								$cons9="Select Codigo1,CodProductos.AutoId,Cantidad,NombreProd1,UnidadMedida,Presentacion,TotCosto+Movimiento.VrIVA,Comprobante,Numero,VrCosto
								from Consumo.Movimiento,Consumo.CodProductos
								where CodProductos.Compania='$Compania[0]' and Movimiento.Compania='$Compania[0]' 
								and CodProductos.AutoId=Movimiento.AutoId and Numero='$fila[0]' and Comprobante='$fila[1]' and CodProductos.Anio=$Anio and
								Movimiento.Estado='AC' and Movimiento.AlmacenPpal='$AlmacenPpal' and CodProductos.AlmacenPpal='$AlmacenPpal' and Cedula='$Cedula' and TipoComprobante='Remisiones'";
								$xn++;
								?>
								<tr>
									<td>
										<input type="checkbox" name="Registra[<? echo $fila[0]?>]" value="<? echo $fila[1]?>" title="A&ntilde;adir Esta Orden al Registro" />
									</td>
									<td style="cursor:hand" onMouseOver="this.bgColor='#AAD4FF'" onmouseout="this.bgColor='#FFFFFF'" onclick="Mostrar(<? echo $fila[0];?>)" align="center" title="Ver Esta Orden">
										<? echo "$fila[0]</td><td>$fila[1]</td><td align='center'>".$fila[2]."</td><td align='right'>".number_format($fila[3],2)."</td>";?>
										<input type="Hidden" name="CC" value="<? echo $fila[5]?>">
									</td>
								</tr>
								<tr>
									<td colspan="6" align="right">
										<table  width="90%"  class="tabla2" style="margin-top:25px;margin-bottom:25px;" <?php echo $borderTabla2Mentor; echo $bordercolorTabla2Mentor; echo $cellspacingTabla2Mentor; echo $cellpaddingTabla2Mentor; ?>>
											<tr>
												<td class="encabezado2Horizontal">C&Oacute;DIGO</td>
												<td class="encabezado2Horizontal">DESCRIPCI&Oacute;N</td>
												<td class="encabezado2Horizontal">CANT.</td>
												<td class="encabezado2Horizontal">VALOR</td>
											</tr>
											<?
											$res9=ExQuery($cons9);echo ExError();
											while($fila9=ExFetch($res9)){
												echo "<tr><td>$fila9[0]</td><td>$fila9[3] $fila9[4] $fila9[5]</td><td align='right'>".number_format($fila9[2],2)."</td><td align='right'>".number_format($fila9[6],2)."</td></tr>";
											}
											?>
										</table>
									</td>
								</tr>
						<?	}
						?>
						</table>
						<div align="center" style="margin-top:25px;margin-bottom:25px;">
							<input type="submit" name="Registrar" class="boton2Envio" value="Registrar" />
							<input type="button" value="Cancelar" class="boton2Envio" onClick="CerrarThis()">
						</div>	
					</form>	
				</div>	
			</body>
		</html>	