		<?
		if($DatNameSID){session_name("$DatNameSID");}
		session_start();
		include("Funciones.php");
		include_once("General/Configuracion/Configuracion.php");
		$ND = getdate();
		$ND=getdate();
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
			</head>	
			<body>
				<div align="center">
					<form name="FORMA" method="post">
					<div align="right">
						<button type="button" title="Cerrar" onClick="CerrarThis()">
							<img src="/Imgs/b_drop.png" />
						</button>
					</div>
						<input type="hidden" name="AlmacenPpal" value="<? echo $AlmacenPpal?>" />
						<input type="hidden" name="Anio" value="<? echo $Anio?>"
						<input type="hidden" name="DatNameSID" value="<? echo $DatNameSID?>" />
						<table  width="95%" class="tabla2" style="margin-top:10px;margin-bottom:10px;" <?php echo $borderTabla2Mentor; echo $bordercolorTabla2Mentor; echo $cellspacingTabla2Mentor; echo $cellpaddingTabla2Mentor; ?>>
							<tr>
								<td class="encabezado1Horizontal">DETALLE</td>
								<td class="encabezado1Horizontal">FECHA DESPACHO SISTEMA</td>
								<td class="encabezado1Horizontal">FECHA TOMA DOSIS</td>
								<td class="encabezado1Horizontal">COMPROBANTE</td>
								<td class="encabezado1Horizontal">N&Uacute;MERO</td>
								<td class="encabezado1Horizontal">TOT. COSTO</td>
							</tr>
						<?
						$cons = "Select Anio,Mes from Central.CierrexPeriodos Where Compania='$Compania[0]' and Modulo='Consumo'";
						$res = ExQuery($cons);
						while($fila=ExFetch($res))
						{
							$Cierre[$fila[0]][$fila[1]]=1;
						}
						$cons = "Select Detalle,fecha,Comprobante,Numero,SUM(TotCosto),fechadespacho from Consumo.Movimiento Where Compania='$Compania[0]' and AlmacenPpal='$AlmacenPpal'
						and TipoComprobante='Salidas' and Cedula='$Cedula' group by fechadespacho,fecha,Comprobante,Numero,Detalle order by fechadespacho desc";
						$res = ExQuery($cons);                            //and fechadespacho<='$Anio-$Mes-$ND[mday]'
						while($fila = ExFetch($res))
						{
							$Fecha = getdate(strtotime($fila[1]));
							if(!$Cierre[$Fecha[year]][$Fecha[mon]])
							{
								$rojo=0;
								$consROJO1 = "Select Movimiento.Autoid,NombreProd1,UnidadMedida,Presentacion,Cantidad,CentroCosto,NumServicio
							from Consumo.Movimiento,Consumo.CodProductos
							Where Movimiento.AutoId = CodProductos.AutoId and CodProductos.Compania='$Compania[0]' and Movimiento.Compania='$Compania[0]'
							and CodProductos.AlmacenPpal='$AlmacenPpal' and Movimiento.AlmacenPpal='$AlmacenPpal' and CodProductos.Anio=$Anio 
							and Comprobante='$fila[2]' and Numero='$fila[3]'";
							$resROJO1=ExQuery($consROJO1);
							while($filaROJO1=ExFetch($resROJO1)){
								$consROJO2 = "Select NoDocAfectado,AutoId,Numero,Cantidad from Consumo.Movimiento Where Compania='$Compania[0]' and AlmacenPpal = '$AlmacenPpal' and Estado = 'AC'
					and NoDocAfectado='$fila[3]' and Comprobante = 'Devoluciones' and TipoComprobante='Devoluciones'";
								$resROJO2=ExQuery($consROJO2);
								while($filaROJO2=ExFetch($resROJO2)){
									$ResROJO=$filaROJO2[3];//-$filaROJO1[3];
									if($ResROJO>0){$rojo=1;}
								}		
							}
								?><tr onMouseOver="this.bgColor='#AAD4FF'" onMouseOut="this.bgColor=''" 
									onclick="parent.CargarProductosxSalida('<? echo $fila[2]?>','<? echo $fila[3]?>','<? echo $AlmacenPpal?>','<? echo $Cedula?>')" style="cursor: hand">
									<td align="center" <?php if($rojo>0){echo'bgcolor="#FF0000"';}?>><? echo $fila[0]?></td>
									<td <?php if($rojo>0){echo'bgcolor="#FF0000"';}?>><? echo $fila[1]?></td>
									<td align='center' <?php if($rojo>0){echo'bgcolor="#FF0000"';}?>><? echo $fila[5]?></td>
									<td <?php if($rojo>0){echo'bgcolor="#FF0000"';}?>><? echo $fila[2]?></td>
									<td <?php if($rojo>0){echo'bgcolor="#FF0000"';}?>><? echo $fila[3]?></td>
									<td align='right' <?php if($rojo>0){echo'bgcolor="#FF0000"';}?>><? echo number_format($fila[4],2)?></td>
								</tr><?
							}

						}
						?>
					  </table>
					</form>
				</div>	
			</body>
		</html>	