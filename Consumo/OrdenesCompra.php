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
			$cons20="Select sum(Cantidad),DocAfectado,NoDocAfectado,AutoId from Consumo.Movimiento where Compania='$Compania[0]' and AlmacenPpal='$AlmacenPpal' 
			and (TipoComprobante='Entradas' Or TipoComprobante='Remisiones') and Anio = $Anio
			and Estado='AC'
			Group By DocAfectado,NoDocAfectado,AutoId";
			$res20=ExQuery($cons20);
			while($fila20=ExFetch($res20))
			{
				$EntradasProd[$fila20[1]][$fila20[2]][$fila20[3]]=$fila20[0];
			}
				
			if($Registrar)
			{
				$VrSaldoIni=SaldosIniciales($Anio,$AlmacenPpal,$Fecha);
				if($Registra)
				{
					while (list($val,$cad) = each ($Registra)) 
					{
						$cons2="Select AutoId,Cantidad,VrCosto,TotCosto,PorcIVA,(VrIVA/Cantidad),PorcReteFte,VrReteFte,PorcDescto,VrDescto,PorcICA,VrICA,
						CentroCosto,IncluyeIVA,conceptortefte from Consumo.Movimiento where Compania='$Compania[0]' and Anio = $Anio and
						Estado='AC' and AprobadoX!='' and Cedula='$Cedula' and Comprobante='$cad' and Numero='$val' and AlmacenPpal='$AlmacenPpal' 
						and TipoComprobante='Orden de Compra'";
						$res2=ExQuery($cons2);
						while($fila2=ExFetch($res2))
						{
							$Cantidad=$fila2[1]-$EntradasProd[$cad][$val][$fila2[0]];
							if($Cantidad>0){
							$TotCosto=$Cantidad*$fila2[2];
							$cons3="Insert into Consumo.TmpMovimiento(TMPCOD,AutoId,Cantidad,VrCosto,TotCosto,VrVenta,TotVenta,PorcIVA,VrIVA,PorcReteFte,VrReteFte,
							PorcDescto,VrDescto,PorcICA,VrICA,DocAfectado,NoDocAfectado,CentroCosto,IncluyeIVA,conceptortefte)					
							values('$TMPCOD',$fila2[0],$Cantidad,$fila2[2],$TotCosto,0,0,$fila2[4],($fila2[5]*$Cantidad),$fila2[6],$fila2[7],$fila2[8],$fila2[9],
							$fila2[10],$fila2[11],'$cad','$val','$fila2[12]',$fila2[13],'$fila2[14]')";
							$res3=ExQuery($cons3);echo ExError();}
						}
						?>
						<script language="javascript">
							parent.frames.NuevoMovimiento.location.href="DetNuevoMovimientos.php?DatNameSID=<? echo $DatNameSID?>&AlmacenPpal=<? echo $AlmacenPpal?>&Comprobante=<? echo $Comprobante?>&TMPCOD=<? echo $TMPCOD?>&Tipo=Entradas&Numero=<? echo $Numero?>&Anio=<? echo $Anio?>";
							parent.frames.TotMovimientos.location.href='TotMovimientos.php?DatNameSID=<? echo $DatNameSID?>&TMPCOD=<? echo $TMPCOD?>&Comprobante=<? echo $Comprobante?>&Numero=<? echo $Numero?>&AlmacenPpal=<? echo $AlmacenPpal?>';
							frames.parent.document.FORMA.Detalle.value='<? echo $Detalle?>';
							CerrarThis();
						</script>
						<?
					}
				}
				else{ ?><div align="center" class="mensaje1">No ha seleccionado ninguna solicitud</div><? }	
			}
			
			$cons = "Select Numero,Comprobante,Fecha,sum(TotCosto)+sum(VrIVA) from Consumo.Movimiento where Compania='$Compania[0]' and Anio = $Anio and
			AprobadoX!='' and AlmacenPpal='$AlmacenPpal' and Cedula='$Cedula' and TipoComprobante='Orden de Compra' and Estado='AC'
			Group By Numero,Comprobante,Fecha"; 
			$res = ExQuery($cons);echo ExError();
			$fila=ExFetch($res);
			
		?>
		<script language="javascript">
			function Mostrar(num,Anio,Comprobante)
			{
				open("/Informes/Almacen/Formatos/OrdenCompra.php?DatNameSID=<? echo $DatNameSID?>&Comprobante="+Comprobante+"&AlmacenPpal=<? echo $AlmacenPpal?>&Anio="+Anio+"&Numero="+num,'','width=600,height=400,scrollbars=yes');
			}
		</script>
	
	<html>
		<head>
			<?php echo $codificacionMentor; ?>
			<?php echo $autorMentor; ?>
			<?php echo $titleMentor; ?>
			<?php echo $iconMentor; ?>
			<?php echo $shortcutIconMentor; ?>
			<link rel="stylesheet" type="text/css" href="../General/Estilos/estilos.css">
		</head>
		<body>
			<div align="center">
				<form name="FORMA" method="post">
					<input type="hidden" name="DatNameSID" value="<? echo $DatNameSID?>" />
						<table class="tabla2" width="90%"   <?php echo $borderTabla2Mentor; echo $bordercolorTabla2Mentor; echo $cellspacingTabla2Mentor; echo $cellpaddingTabla2Mentor; ?>>

								<tr>
									<td colspan="6" class="encabezado2Horizontal" >ORDENES DE COMPRA APROBADAS</td>
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
								while($fila = ExFetch($res))
								{
									$cons9="Select Codigo1,CodProductos.AutoId,Cantidad,NombreProd1,UnidadMedida,Presentacion,(TotCosto+Movimiento.VrIVA),Comprobante,
									Numero,(VrCosto+(Movimiento.VrIVA/Cantidad)),Detalle,IncluyeIVA
									from Consumo.Movimiento,Consumo.CodProductos
									where CodProductos.Compania='$Compania[0]' and Movimiento.Compania='$Compania[0]' 
									and CodProductos.AutoId=Movimiento.AutoId and Numero='$fila[0]' and Comprobante='$fila[1]' and 
									Movimiento.Estado='AC' and CodProductos.AlmacenPpal='$AlmacenPpal' and Cedula='$Cedula'
											and  AprobadoX!='' and AprobadoX!='Rechazado' and CodProductos.Anio=$Anio
									and CodProductos.AutoId not in(Select AutoId from Consumo.TMPMovimiento Where TMPCOD='$TMPCOD' and docafectado='Orden de Compra' and Numero='$fila[0]'
									and Numero not in(Select nodocafectado from Consumo.Movimiento Where TipoComprobante='Entradas'))";
									//echo $cons9;
									$Hacer=0;
									$res9=ExQuery($cons9);echo ExError();
									while($fila9=ExFetch($res9))
									{
										$Cantidad=$fila9[2]-$EntradasProd[$fila9[7]][$fila9[8]][$fila9[1]];
										if($Cantidad>0){$Hacer=1;break;}
									}
								if($Hacer){
									$xn++;
									?>
									<tr><td>
										<input type="checkbox" name="Registra[<? echo $fila9[8]?>]" value="<? echo $fila9[7]?>" title="A&ntilde;adir Esta Orden al Registro" />
										<input type="hidden" name="Detalle" value="<? echo $fila9[10];?>" />
										</td>
										<td style="cursor:hand" onMouseOver="this.bgColor='#AAD4FF'" onmouseout="this.bgColor='#FFFFFF'" onclick="Mostrar('<? echo $fila[0]?>','<? echo $Anio?>','<? echo $fila[1]?>')" align="center" title="Ver Esta Orden">
									<? echo "$fila[0]</td><td>$fila[1]</td><td align='center'>".$fila[2]."</td><td align='right'>"; ?>
									<input type="text" name="TotalDocumento<? echo $fila[0]?>" style="border:0; background:/Imgs/Fondo.jpg; font-family:Tahoma, Geneva, sans-serif" value="" size="6" readonly></td>
									<input type="Hidden" name="CC" value="<? echo $fila[5]?>">
									</td>
									</tr>
									<tr>
										<td colspan="6" style="text-align:right;">
											<table width="90%" class="tabla2"   <?php echo $borderTabla2Mentor; echo $bordercolorTabla2Mentor; echo $cellspacingTabla2Mentor; echo $cellpaddingTabla2Mentor; ?>>
												<tr>
													<td class="encabezado2Horizontal">C&Oacute;DIGO</td>
													<td class="encabezado2Horizontal">DESCRIPCI&Oacute;N</td>
													<td class="encabezado2Horizontal">CANT.</td>
													<td class="encabezado2Horizontal">VALOR</td>
												</tr>
												<?
												$res9 = ExQuery($cons9);
												$TotDoc = 0;
												while($fila9=ExFetch($res9)){
													$Cantidad=$fila9[2]-$EntradasProd[$fila9[7]][$fila9[8]][$fila9[1]];
													if($Cantidad>0){
														////////////
														$VrTotal=$Cantidad*$fila9[9];
														$TotDoc = $TotDoc + $VrTotal;
														echo "<tr><td>$fila9[0]</td><td>$fila9[3] $fila9[4] $fila9[5]</td><td align='right'>".number_format($Cantidad,2)."</td><td align='right'>".number_format($VrTotal,2)."</td></tr>";
													}
												}
												?>
												<script language="javascript">
													document.FORMA.TotalDocumento<? echo $fila[0]?>.value = "<? echo number_format($TotDoc,2); ?>";
												</script>
											</table>
										</td>
									</tr>
									<?	
								}
							}
							?>
						</table>
						<div style="margin-top:25px;margin-bottom:25px">
							<input type="submit" class="boton2Envio" name="Registrar" value="Registrar" />
							<input type="button" class="boton2Envio" value="Cancelar" onClick="CerrarThis()">
						</div>
				</form>
			</div>
		</body>
	</html>


