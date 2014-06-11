		<?
			if($DatNameSID){session_name("$DatNameSID");}
			session_start();
			include("Funciones.php");
			include("ObtenerSaldos.php");
			include_once("General/Configuracion/Configuracion.php");
			$ND=getdate();
			$VrSaldoIni=SaldosIniciales($Anio,$AlmacenPpal,"$Anio-".substr($Fecha,5,2)."-01");
			$VrEntradas=Entradas($Anio,$AlmacenPpal,"$Anio-".substr($Fecha,5,2)."-01",$Fecha);
			$VrSalidas=Salidas($Anio,$AlmacenPpal,"$Anio-".substr($Fecha,5,2)."-01",$Fecha);
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
			///////////////////CENTROS DE COSTO//////////////////////////////////////////////////////////////////////
			$cons = "Select Codigo,CentroCostos From Central.CentrosCosto where Compania='$Compania[0]' and Anio=$Anio";
			$res = ExQuery($cons);
			while($fila = ExFetch($res))
			{
				$CentrosCosto[$fila[0]] = array($fila[0],strtoupper($fila[1]));
			}
			///////////////////SALIDAS PRODUCTOS/////////////////////////////////////////////////////////////////////
			$cons20="Select sum(Cantidad),IdSolicitud,AutoId from Consumo.Movimiento 
			where Compania='$Compania[0]' and AlmacenPpal='$AlmacenPpal' and TipoComprobante='Salidas' 
			and Estado='AC' and Anio = $Anio Group By IdSolicitud,AutoId";
			$res20=ExQuery($cons20);
			while($fila20=ExFetch($res20))
			{
				$SalidasProd[$fila20[1]][$fila20[2]]=$fila20[0];
			}
			////////////////////CANTIDADES PENDIENTES////////////////////////////////////////////////////////////////
			$cons9="Select Codigo1,CodProductos.AutoId,SolicitudConsumo.CantAprobada,NombreProd1,UnidadMedida,Presentacion,IdSolicitud
			from Consumo.SolicitudConsumo,Consumo.CodProductos
			where CodProductos.Compania='$Compania[0]' and SolicitudConsumo.Compania='$Compania[0]' 
			and CodProductos.AutoId=SolicitudConsumo.AutoId and 
			SolicitudConsumo.Estado='Aprobada' and SolicitudConsumo.AlmacenPpal='$AlmacenPpal' and CodProductos.AlmacenPpal='$AlmacenPpal'
			and CodProductos.Anio = $Anio
			and SolicitudConsumo.Anio = $Anio";
			$res9 = ExQuery($cons9);
			while($fila9 = ExFetch($res9))
			{
				//Pendientes[IdSolicitud][AutoId] = array([0]AutoId,[1]CantPendiente,[2]Codigo,[3]NombreProducto)                              
				if($fila9[2] - $SalidasProd[$fila9[6]][$fila9[1]] > 0)
				{
					$Pendientes[$fila9[6]][$fila9[1]] = array($fila9[1],$fila9[2] - $SalidasProd[$fila9[6]][$fila9[1]],$fila9[0],"$fila9[3] $fila9[4] $fila9[5]");
				}
			}
			if($Registrar)
			{
				if($Registra)
				{
					while (list($val,$cad) = each ($Registra)) 
					{
						foreach($Pendientes[$val] as $ProdPendientes)
						{	
							if($ProdPendientes[1]>0)
							{
								$SumCantExistencias=$VrSaldoIni[$ProdPendientes[0]][0]+$VrEntradas[$ProdPendientes[0]][0]-$VrSalidas[$ProdPendientes[0]][0];
								$SumVrExistencias=$VrSaldoIni[$ProdPendientes[0]][1]+$VrEntradas[$ProdPendientes[0]][1]-$VrSalidas[$ProdPendientes[0]][1];
								if($SumCantExistencias>0){$PromedioPond=$SumVrExistencias/$SumCantExistencias;}else{$PromedioPond=0;}
								$TotCosto=$PromedioPond*$ProdPendientes[1];
			
								$cons3="Insert into Consumo.TmpMovimiento
								(TMPCOD,AutoId,Cantidad,VrCosto,TotCosto,VrVenta,TotVenta,PorcIVA,VrIVA,PorcReteFte,
								 VrReteFte,PorcDescto,VrDescto,PorcICA,VrICA,CentroCosto,IdSolicitud)
								 values('$TMPCOD',$ProdPendientes[0],$ProdPendientes[1],$PromedioPond,$TotCosto,0,0,0,0,0,0,0,0,0,0,'$CC[$val]',$val)";
								$res3=ExQuery($cons3);echo ExError();}
							}
						?>
						<script language="javascript">
							parent.frames.NuevoMovimiento.location.href="DetNuevoMovimientos.php?DatNameSID=<? echo $DatNameSID?>&AlmacenPpal=<? echo $AlmacenPpal?>&Comprobante=<? echo $Comprobante?>&TMPCOD=<? echo $TMPCOD?>&Tipo=Salidas&Numero=<? echo $Numero?>&Anio=<? echo $Anio?>";
							parent.frames.TotMovimientos.location.href='TotMovimientos.php?DatNameSID=<? echo $DatNameSID?>&TMPCOD=<? echo $TMPCOD?>';
							parent.document.FORMA.Detalle.value="ENTREGA DE PEDIDO PARA <? echo $CentrosCosto[$CC[$val]][1]?> SOLICITUD No <? echo $val?> DE <? echo $ND[year]?>";
							CerrarThis();
						</script>
						<?
					}
				}
				else
				{ ?><div align="center" class="mensaje1">No ha seleccionado ninguna solicitud</div><? }
				
			}
			$cons = "Select IdSolicitud,Fecha,Estado,count(Estado),Usuario,CentroCostos from Consumo.SolicitudConsumo where Compania='$Compania[0]' and
			Estado='Aprobada' and AlmacenPpal='$AlmacenPpal' and Anio=$Anio Group By IdSolicitud,Estado,Fecha,Usuario,CentroCostos";
			$res = ExQuery($cons);
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
					function Mostrar(num,Anio){
						open("/Informes/Almacen/Formatos/ImpSolicitudConsumo.php?DatNameSID=<? echo $DatNameSID?>&Anio="+Anio+"&IdSolicitud="+num,'','width=600,height=400,scrollbars=yes');
					}
				</script>
			</head>
			<body>
				<div align="center">
					<form name="FORMA" method="post">
						<input type="hidden" name="DatNameSID" value="<? echo $DatNameSID?>" />
						<table class="tabla1" width="90%"  <?php echo $borderTabla1Mentor; echo $bordercolorTabla1Mentor; echo $cellspacingTabla1Mentor; echo $cellpaddingTabla1Mentor; ?>>
							<tr>
								<td colspan="6" class="encabezado2Horizontal" >SOLICITUDES APROBADAS <? echo strtoupper($fila[4]); ?></td>
							</tr>
							<tr>
								<td class="encabezado2HorizontalInvertido">&nbsp;</td>
								<td class="encabezado2HorizontalInvertido">SOLICITUD</td>
								<td class="encabezado2HorizontalInvertido">FECHA SOLICITUD</td>
								<td class="encabezado2HorizontalInvertido">CANTIDAD</td>
								<td class="encabezado2HorizontalInvertido">ESTADO</td>
								<td class="encabezado2HorizontalInvertido">CC</td>
							</tr>
							<?

							$res = ExQuery($cons);
							while($fila = ExFetch($res)){
								if($Pendientes[$fila[0]]){
									$xn++;
									?>
									<tr>
										<td>
											<input type="checkbox" name="Registra[<? echo $fila[0]?>]" title="A&ntilde;adir Esta Solicitud al Registro" />
										</td>
										<td style="cursor:hand" onMouseOver="this.bgColor='#AAD4FF'" onmouseout="this.bgColor='#FFFFFF'" onclick="Mostrar('<? echo $fila[0]?>','<? echo $Anio?>')" align="center" title="Ver Esta Solicitud">
											<? echo "$fila[0]</td><td>$fila[1]</td><td align='center'>".$fila[3]."</td><td>$fila[2]</td><td>$fila[5]</td>";?>
											<input type="Hidden" name="CC[<? echo $fila[0]?>]" value="<? echo $fila[5]?>">
										</td>
									</tr>
									<tr>
										<td colspan="6" style="text-align:center;">
												<table class="tabla1" width="100%"  <?php echo $borderTabla1Mentor; echo $bordercolorTabla1Mentor; echo $cellspacingTabla1Mentor; echo $cellpaddingTabla1Mentor; ?>>
													<tr>
														<td class="encabezado2Horizontal" >C&Oacute;DIGO</td>
														<td class="encabezado2Horizontal">DESCRIPCI&Oacute;N</td>
														<td class="encabezado2Horizontal">CANT.</td>
														<td class="encabezado2Horizontal">EXIST.</td>
													</tr>
														<?
														foreach($Pendientes[$fila[0]] as $ProdPendientes){
															$SumCantExistencias=$VrSaldoIni[$ProdPendientes[0]][0]+$VrEntradas[$ProdPendientes[0]][0]-$VrSalidas[$ProdPendientes[0]][0];
															echo "<tr>";
																echo "<td style='text-align:center;'>$ProdPendientes[2]</td>";
																echo "<td>$ProdPendientes[3]</td>";
																echo "<td align='right'>".number_format($ProdPendientes[1],2)."</td><td align='right'>";
															if($SumCantExistencias<$ProdPendientes[1]){echo "<font color='red'>";}echo number_format($SumCantExistencias,2)."</td></tr>";
														}
														?>
												</table>
										</td>
									</tr>
								<?		
								}
							}
						?>
						</table>
						<div style="margin-top:15px;margin-bottom:15px;">
							<input type="submit" name="Registrar" class="boton2Envio" value="Registrar" />
							<input type="button" value="Cancelar" class="boton2Envio" onClick="CerrarThis()">
						</div>	
					</form>
				</div>	
			</body>
		</html>
