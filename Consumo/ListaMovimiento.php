		<?
			if($DatNameSID){session_name("$DatNameSID");}
			session_start();
			include("Funciones.php");
			include_once("General/Configuracion/Configuracion.php");
			$ND=getdate();
			if(!$MesI){$MesI=$MesTrabajo;}if(!$AnioI){$AnioI=$ND[year];}
			$MesI=$MesI*1;
			if(strlen($MesI)==1){$MesI="0".$MesI;}
				if($Tipo=="Orden de Compra")
				{
					$cons = "Select RequiereVoBo from Consumo.Comprobantes Where Compania='$Compania[0]' and Tipo='$Tipo' and Comprobante='$Comprobante'";
					$res = ExQuery($cons);
					$fila = ExFetch($res);
					$RequiereVoBo = $fila[0];
				}
				$cons="Select Mes,NumDias from Central.Meses where Numero=$MesI";
			$res=ExQuery($cons);
			$fila=ExFetch($res);
			$UltDia=$fila[1];

			
			$cons2="Select ComprobanteContable from Consumo.Comprobantes where Comprobante='$Comprobante' and Compania='$Compania[0]' and AlmacenPpal='$AlmacenPpal'";
			$res2=ExQuery($cons2);
			$fila2=ExFetch($res2);
			$CompContable=$fila2[0];
			//if(!$EstadoOC){$EstadoOC = "Todos";}
			if($CompContable){
			$cons="Select sum(Debe),Comprobante,Numero from contabilidad.Movimiento where Comprobante='$CompContable' and Fecha>='$AnioI-$MesI-01' and Fecha<='$AnioI-$MesI-$UltDia' and Compania='$Compania[0]' and Estado='AC'
			Group By Comprobante,Numero";
			$res=ExQuery($cons);
			while($fila=ExFetch($res))
			{
				$MatContabilidad[$fila[1]][$fila[2]]=$fila[0];
				$SumContabilizado=$SumContabilizado+$fila[0];
			}}
																									  
			$cons = "Select Mes From Central.CierreXPeriodos Where Compania='$Compania[0]' and Modulo='Consumo' and Anio=$AnioI and Mes=$MesI";
			$res = ExQuery($cons);
			if(ExNumRows($res)==1)
			{
				?><script language="javascript">
				parent(0).document.FORMA.Nuevo.disabled=true;
				parent(0).document.FORMA.Nuevo.title="PERIODO CERRADO, No se pueden Ingresar Nuevos Registros";
				</script>
				<?
				$NoEdEl = 1;
			}
			else
			{
				?><script language="javascript">
				parent(0).document.FORMA.Nuevo.disabled=false;
				parent(0).document.FORMA.Nuevo.title="";
				</script>
				<?
				unset($NoEdEl);
			}
			if($Elim)
			{
					if($Tipo=="Entradas")
					{
						$consxx = "Select AutoId,fecha,compcontable,numcompcont from Consumo.Movimiento where TipoComprobante = '$Tipo'
						and Comprobante='$Comprobante' and Numero='$Numero' and AlmacenPpal='$AlmacenPpal'";
						$resxx = ExQuery($consxx);
						while($filaxx = ExFetch($resxx))
						{
							$CompContable=$filaxx[2];$NoCompContable=$filaxx[3];
							$consx1="Select AutoId,Numero,Fecha from Consumo.Movimiento where TipoComprobante = 'Salidas'
							and fecha >= '$filaxx[1]' and AutoId = $filaxx[0] and AlmacenPpal = '$AlmacenPpal' and Compania='$Compania[0]' and Anio=$AnioI
							and Estado <> 'AN'";
							$resx1 = ExQuery($consx1);
							if(ExNumRows($resx1)>0)
							{
								$filax1 = ExFetch($resx1);
								$Este = $filax1[2];
								$NoElim = 1; break;
							}
						}
					}
					if($NoElim)
					{?>
							<script language="javascript">
							alert("NO se puede Anular este Movimiento, se encontraron Salidas que Relacionan a Este Comprobante con Fechas Posteriores --- <? echo $Este?>");
					</script>
					<? }
					else
					{
						$cons="Update Consumo.Movimiento set Estado='AN' where Comprobante='$Comprobante' and Numero='$Numero' and AlmacenPpal='$AlmacenPpal' and Compania='$Compania[0]'";
						$res=ExQuery($cons);
						echo ExError();
						/// DOS PENDIENTES: VERIFICAR EL CIERRE DE CONTABILIDAD Y VERIFICAR SI YA FUE AFECTADO EL DOCUMENTO
						$cons = "Select compcontable,numcompcont from Consumo.Movimiento Where Comprobante='$Comprobante' and Numero='$Numero'
						and AlmacenPpal='$AlmacenPpal' and Compania='$Compania[0]'";
						$res = ExQuery($cons);
						$fila=ExFetch($res);
						if(ExNumRows($res)>0)
						{
							$cons="Update Contabilidad.Movimiento set Estado='AN' where Comprobante='$fila[0]' and Numero='$fila[1]' and Compania='$Compania[0]'";
							$res=ExQuery($cons);
						}
						echo ExError();
						$Numero="";
					}
				}
		?>
		
		
		<html>
			<head>
				<?php echo $codificacionMentor; ?>
				<?php echo $autorMentor; ?>
				<?php echo $titleMentor; ?>
				<?php echo $iconMentor; ?>
				<?php echo $shortcutIconMentor; ?>
				<link rel="stylesheet" type="text/css" href="../General/Estilos/estilos.css">	
				<script language="javascript" src="/Funciones.js"></script>
				<script language="javascript">
					function VerImprimible(Numero,Comprobante,AlmacenPpal,NoFactura)
					{
						<? 
							$cons000 = "Select Formato from Consumo.Comprobantes where Compania='$Compania[0]' and AlmacenPpal = '$AlmacenPpal' and Comprobante = '$Comprobante'";
							$res000 = ExQuery($cons000);
							$fila000 = ExFetch($res000);
							$Archivo = $fila000[0];
						?>
						open("/Informes/Almacen/<? echo $Archivo?>?DatNameSID=<? echo $DatNameSID?>&NoFactura="+NoFactura+"&Numero="+Numero+"&Comprobante=<? echo $Comprobante?>&AlmacenPpal=<? echo $AlmacenPpal?>&Anio=<? echo $AnioI?>","","width=700,height=500,scrollbars=yes")
					}
					function MostrarActa(Numero,Comprobante,AlmacenPpal)
					{
						//St = document.body.scrollTop;
						//frames.FrameOpener.location.href = "Acta.php?Anio=$Anio&DatNameSID=<? echo $DatNameSID?>&Anio=<? echo $AnioI?>&Numero="+Numero+"&Comprobante="+Comprobante+"&AlmacenPpal="+AlmacenPpal;
						//document.getElementById('FrameOpener').style.position='absolute';
						//document.getElementById('FrameOpener').style.top=St + 20;
						//document.getElementById('FrameOpener').style.left='20px';
						//document.getElementById('FrameOpener').style.display='';
						//document.getElementById('FrameOpener').style.width='800';
						//document.getElementById('FrameOpener').style.height='600';
								open("Acta.php?Anio=$Anio&DatNameSID=<? echo $DatNameSID?>&Anio=<? echo $AnioI?>&Numero="+Numero+"&Comprobante="+Comprobante+"&AlmacenPpal="+AlmacenPpal,"","width=1000,height=700,scrollbars=yes");

					}
						function AbrirVisar(Numero,e,Comprobante,AlmacenPpal)
						{
							sT = document.body.scrollTop;
							posY = e.ClientY;
							frames.FrameOpener.location.href = "Visar.php?DatNameSID=<? echo $DatNameSID?>&Anio=<? echo $AnioI?>&Numero="+Numero+"&Comprobante="+Comprobante+"&AlmacenPpal="+AlmacenPpal;
							document.getElementById('FrameOpener').style.position='absolute';
							document.getElementById('FrameOpener').style.top=20 + sT;
							document.getElementById('FrameOpener').style.left='480px';
							document.getElementById('FrameOpener').style.display='';
							document.getElementById('FrameOpener').style.width='400';
							document.getElementById('FrameOpener').style.height='100';
							
						}
				</script>
			</head>	
			<body <?php echo $backgroundBodyMentor; ?>>
				<div <?php echo $alignDiv3Mentor; ?> class="div3">
					<form name="FORMA" method="post">
						<? 
						///Afectados
						$cons="Select Numero,NoDocAfectado from Consumo.Movimiento Where Compania='$Compania[0]' and TipoComprobante='Entradas' and Compania='$Compania[0]'
							And Estado = 'AC'";
							//echo $cons;
						$res = ExQuery($cons);
						while($fila=ExFetch($res))
						{
							$Afectado[$fila[1]]=$fila[0];	
						}
						///Estados
						$cons = "Select Numero,Aprobadox from Consumo.Movimiento Where Compania='$Compania[0]' 
						and Fecha>='$AnioI-$MesI-01' and Fecha<='$AnioI-$MesI-$UltDia' and TipoComprobante='Orden de Compra' and Estado != 'AN' and Aprobadox IS NOT NULL";
							//echo $cons;
						$res = ExQuery($cons);
						while($fila=ExFetch($res)){
							if(!$fila[1]){$Estado[$fila[0]]="Sin Revisar";}
							else
							{
								if($fila[1]=="Rechazado"){$Estado[$fila[0]]="Rechazado";}
								else
								{
									if($Afectado[$fila[0]])
													{
														$Estado[$fila[0]]="ING - ".$Afectado[$fila[0]];
													}
									else{$Estado[$fila[0]]="Aprobado";}
								}	
							}
						}
						//////
						//echo $EstadoOC."-*-*-*-*";
						if($EstadoOC){
							if($EstadoOC=="Sin revisar"){$ConsOC=" and Aprobadox IS NULL and FechaAprobac IS NULL";}
							if($EstadoOC=="Aprobado"){$ConsOC=" and (Aprobadox != 'Rechazado' and Aprobadox IS NOT NULL) and FechaAprobac IS NOT NULL 
														and Numero not in (Select NoDocAfectado from Consumo.Movimiento Where TipoComprobante='Entradas' and Compania='$Compania[0]')";}
							if($EstadoOC=="Rechazado"){$ConsOC=" and Aprobadox = 'Rechazado'";}
							if($EstadoOC=="Ingresado")
							{
								$ConsOC=" and Numero in(Select NoDocAfectado from Consumo.Movimiento Where TipoComprobante='Entradas' and Compania='$Compania[0]' and Estado='AC')";
							}
						}
							if($VoBo){
								if($VoBo == "Sin revisar"){$ConsVoBo = " and VoBo IS NULL and UsuarioVoBo IS NULL";}
								if($VoBo == "Visado"){$ConsVoBo = " and VoBo=1 ";}
								if($VoBo == "No visado"){$ConsVoBo = " and VoBo=0 ";}
							}
						if($DiaI){ $conFecha = " and Fecha='$AnioI-$MesI-$DiaI' ";}
						if($Numero){ $conNumero = " and Numero like '%$Numero' ";}
						if($Detalle){ $conDetalle = " and Detalle ilike '%$Detalle%' ";}
						if($Identificacion){ $conIdentificacion= " and Cedula like '$Identificacion%' ";}
						if($TotalCosto || $TotalVenta){ $H = " HAVING ";}
						if($TotalCosto){ $conTotalCosto = " Sum(TotCosto)+sum(VrIVA)-sum(VrDescto) = $TotalCosto ";}
						if($TotalVenta){ if($TotalCosto){ $conTotalVenta = " and Sum(TotVenta) = $TotalVenta ";} else {$conTotalVenta = " Sum(TotVenta) = $TotalVenta ";}}
						$cons = "Select Fecha,Numero,Detalle,PrimApe,SegApe,PrimNom,SegNom,Movimiento.Cedula,Sum(TotCosto)+sum(VrIVA)-sum(VrDescto),
						Sum(TotVenta),TipoComprobante,Estado,NoFactura,VrFactura,compcontable,numcompcont,VoBo,FechaVoBo,UsuarioVoBo,notanovisado
						from Consumo.Movimiento,Central.Terceros where Movimiento.Cedula=Terceros.Identificacion and Movimiento.Compania='$Compania[0]'
						and Terceros.Compania='$Compania[0]' and Fecha>='$AnioI-$MesI-01' and Fecha<='$AnioI-$MesI-$UltDia' and TipoComprobante='$Tipo'
						and AlmacenPpal='$AlmacenPpal' and Comprobante='$Comprobante' $conFecha $conNumero $conDetalle $conIdentificacion $ConsOC $ConsVoBo
						Group by Numero,Fecha,Detalle,PrimApe,SegApe,PrimNom,SegNom,Movimiento.Cedula,TipoComprobante,
							Estado,NoFactura,VrFactura,compcontable,numcompcont,VoBo,FechaVoBo,UsuarioVoBo,notanovisado
						$H $conTotalCosto $conTotalVenta 
						Order By Numero,Fecha";
						//echo $cons;
						$res = ExQuery($cons);
						?>
						<table width="95%" class="tabla3"   <?php echo $borderTabla3Mentor; echo $bordercolorTabla3Mentor; echo $cellspacingTabla3Mentor; echo $cellpaddingTabla3Mentor; ?>>
						
							<tr>
								<td class="encabezado2Horizontal">FECHA</td>
								<td class="encabezado2Horizontal">N&Uacute;MERO</td>
								<td class="encabezado2Horizontal">DETALLE</td>
								<td class="encabezado2Horizontal">TERCERO</td>
								<td class="encabezado2Horizontal">TOTAL COSTO</td>
									<? if($Tipo=="Salidas") {
										?>
										<td class="encabezado2Horizontal">TOTAL VENTA</td>
										<?									
									}?>
								<? if($Tipo=="Orden de Compra") {
									if($RequiereVoBo == 1){
										?><td class="encabezado2Horizontal">VOBO.</td><?}
										?><td class="encabezado2Horizontal">ESTADO</td><?
								}?>
								<td colspan="4" class="encabezado2Horizontal" width="120px;">BUSCAR</td>
							</tr>
						<tr align="center" valign="middle">
							<td>
								<? echo "$AnioI-$MesI-"?><input type="text" name="DiaI" value="<? echo $DiaI?>" maxlength="2" style="width:20px" onKeyUp="xNumero(this)" onKeyDown="xNumero(this)" onBlur="campoNumero(this)">
							</td>
							<td>
								<? echo $AnioI?><input type="text" name="Numero" value="<? echo $Numero?>" maxlength="6" style="width:50px" onKeyUp="xNumero(this)" onKeyDown="xNumero(this)" onBlur="campoNumero(this)">
							</td>
							 <td>
								<input type="text" name="Detalle" value="<? echo $Detalle?>" style="width:250px;" onKeyUp="xLetra(this)" onKeyDown="xLetra(this)">
							</td>
							<td>
								<input type="text" name="Identificacion" value="<? echo $Identificacion?>" style="text-align:center"  onKeyUp="xLetra(this)" onKeyDown="xLetra(this)" maxlength="20">
							</td>
							<td>
								<input type="text" name="TotalCosto" value="<? echo $TotalCosto?>" style="width:100%; text-align:right"	onKeyUp="xNumero(this)" onKeyDown="xNumero(this)" onBlur="campoNumero(this)" />
							</td>
							 <? if($Tipo=="Orden de Compra") {
									if($RequiereVoBo==1){
										?>
										<td>
											<select name="VoBo">
												<option value="Todos">Todos</option>
												<option value="Sin revisar">Sin revisar</option>
												<option value="Visado">Visado</option>
												<option value="No visado">No visado</option>
											</select>
										</td>
										<?
									}
									?>
									
									<td>
										<select name="EstadoOC">
											<option value="Todos">Todos</option>
											<option value="Sin revisar">Sin revisar</option>
											<option value="Aprobado">Aprobado</option>
											<option value="Rechazado">Rechazado</option>
											<option value="Ingresado">Ingresado</option>
										</select>
									</td>
									<? 
								}
								?>
							
								<? if($Tipo=="Salidas") {
									?>
									<td>
										<input type="text" name="TotalVenta" value="<? echo $TotalVenta?>" style="width:100%; text-align:right" onKeyUp="xNumero(this)" onKeyDown="xNumero(this)" onBlur="campoNumero(this)" />
									</td>
									<? 
								}?>
							<td colspan="4">
								<input type="hidden" name="Tipo" value="<? echo $Tipo?>">
								<input type="hidden" name="AlmacenPpal" value="<? echo $AlmacenPpal?>">
								<input type="hidden" name="Comprobante" value="<? echo $Comprobante?>">
								<input type="hidden" name="AnioI" value="<? echo $AnioI?>">
								<input type="hidden" name="MesI" value="<? echo $MesI?>">
								<input type="hidden" name="DatNameSID" value="<? echo $DatNameSID?>" />
								<button name="Buscar" type="submit">
									<img src="/Imgs/b_search.png" title="Buscar Registro">
								</button>
								<!--  onClick="parent.location.href='Movimiento.php?Tipo=<? echo $Tipo?>&AlmacenPpal=<? echo $AlmacenPpal?>&Comprobante=<? echo $Comprobante?>&AnioI=<? echo $AnioI?>&MesI=<? echo $MesI?>&DiaI='+FORMA.DiaI.value+'&Numero='+FORMA.Numero.value+'&Detalle='+FORMA.Detalle.value+'&Identificacion='+FORMA.Identificacion.value+'&TotalCosto='+FORMA.TotalCosto.value+'&TotalVenta='+FORMA.TotalVenta.value">
								-->
								<input type="checkbox" name="Recursivo" title="Busqueda Recursiva" value="1">
							</td>
						</tr>
						
						<?
						while ($fila=ExFetch($res))	{
							$Color="";$Est="";
							if($fila[11]=="AN"){$Est="text-decoration:underline";$Color="red";}else{$Est="";$TotalMovs = $TotalMovs + $fila[8];}
							$DifxComp=$fila[8]-$MatContabilidad[$fila[14]][$fila[15]];
							if($fila[11]!="AN" && $CompContable){
										if($Tipo=="Salidas"){
											if(!$MatContabilidad[$fila[14]][$fila[15]]){$Est="font-weight:bold";$Color="blue";}
											else{$Est="color:black;";$Color="";}
										}
										else{
											if($fila[8]!=$MatContabilidad[$fila[14]][$fila[15]]){$Est="font-weight:bold";$Color="blue";}
											else{$Est="color:black;";$Color="";}
										}
							}
									
							

							?>
							
							<tr style="<? echo $Est ?>; color:<? echo $Color?>" 
								onMouseOver="this.bgColor='#AAD4FF'" 
								onmouseout="this.bgColor='#FFFFFF'"  title="<? echo $DifxComp?>"
								><?

							if($Tipo=="Salidas"){$FilaFinal="<td align='right'>$fila[9]</td>";}
							if($Tipo=="Orden de Compra"){
										if($RequiereVoBo == 1)
										{
											if($fila[16]=="1"){$FilaVoBo = "<td align='center'><img src='/Imgs/b_check.png' title='Visado $fila[18] - $fila[17]'></td>";}
											if($fila[16]=="0"){$FilaVoBo = "<td align='center'><img src='/Imgs/b_alert.png' title='No Visado $fila[18] - $fila[17]: $fila[19]'></td>";}
											if($fila[16]==NULL)
											{
												if($fila[11]!="AN")
												{
													$FilaVoBo = "<td align='center'><img src='/Imgs/down.gif' title='Visar/NoVisar' style=' cursor: hand'
													onclick=\"AbrirVisar('$fila[1]',event,'$Comprobante','$AlmacenPpal')\"/></td>";
												}
												else{$FilaVoBo = "<td>&nbsp;</td>";}
											}
										}
										$FilaFinal="<td align='center'>".$Estado[$fila[1]]."</td>";
							}
							echo "<td>$fila[0]</td><td>$fila[1]</td><td>$fila[2]</td><td>$fila[3] $fila[4] $fila[5] $fila[6] - $fila[7]</td><td align='right'>".number_format($fila[8],2)."</td>
							$FilaVoBo$FilaFinal";
							?>
								<td width="18px">
								<img style="cursor:hand;" border="0" onClick="VerImprimible('<? echo $fila[1]?>','<? echo $Comprobante ?>','<? echo $AlmacenPpal?>','<? echo $fila[12]?>')" title="Ver la Versión imprimible" src="/Imgs/b_print.png" /></td>
							<?
							if($fila[10] == "Entradas" || $fila[10] == "Remisiones")
							{ 
							?>
									<td width="18px">
									<a onClick="MostrarActa('<? echo $fila[1]?>','<? echo $Comprobante?>','<? echo $AlmacenPpal?>')">
									<img border="0" title="Generar Acta" src="/Imgs/doct.gif" /></a></td>
							<? } ?>
							<td width="16px">
							<?
								if(!$NoEdEl)
								{
									?><img onClick="if(confirm('Desea anular documento?')){location.href='ListaMovimiento.php?DatNameSID=<? echo $DatNameSID?>&Elim=1&AlmacenPpal=<? echo $AlmacenPpal?>&Comprobante=<? echo $Comprobante?>&Tipo=<? echo $fila[10]?>&Numero=<? echo $fila[1]?>&AnioI=<? echo substr($fila[0],0,4)?>&MesI=<? echo substr($fila[0],5,2)?>';}"  style="cursor:hand" title="Anular Documento" src='/Imgs/b_drop.png'><?
								}
								else
								{
									?><img src="/Imgs/b_drop_gray.png" title="Periodo Cerrado, NO SE PUEDE ELIMINAR" /><?
								}
							?>
							</td>
							<td width="16px">
							<? if(!$NoEdEl)
							{
								?><img onClick="parent.location.href='NuevoMovimiento.php?DatNameSID=<? echo $DatNameSID?>&Editar=1&AlmacenPpal=<? echo $AlmacenPpal?>&Comprobante=<? echo $Comprobante?>&Numero=<? echo $fila[1]?>&Anio=<? echo substr($fila[0],0,4)?>&Mes=<? echo  substr($fila[0],5,2)?>&Dia=<? echo  substr($fila[0],8,2)?>&Detalle=<? echo $fila[2]?>&Cedula=<? echo $fila[7]?>&Tercero=<? echo "$fila[3] $fila[4] $fila[5] $fila[6]"?>&Tipo=<? echo $fila[10]?>&NoFactura=<? echo $fila[12]?>&TotFactura=<? echo $fila[13]?>'" style='cursor:hand' title="Editar Documento" src='/Imgs/b_edit.png'><?
							}
							else
							{
								?><img src="/Imgs/b_edit_gray.png" title="Periodo Cerrado, NO SE PUEDE EDITAR" /><?
							}
							?>
							</td>
					<?		echo "</tr>";
						}
						if($TotalMovs)
						{
						?><tr><td colspan="10"><hr></td></tr>
						<tr bgcolor="#e5e5e5" style="font-weight:bold"><td colspan="4" align="right">TOTAL:</td>
							<td align="right"><? echo number_format($TotalMovs,2);?></td></tr>
						   <tr bgcolor="#e5e5e5" style="font-weight:bold"><td colspan="4" align="right">CONTABILIZADO:</td><td align="right"><? echo number_format($SumContabilizado,2);
						   $Diferencia=$TotalMovs-$SumContabilizado;
					?>		
						   <tr bgcolor="#e5e5e5" style="font-weight:bold"><td colspan="4" align="right">DIFERENCIA:</td><td align="right"><? echo number_format($Diferencia,2);?>

					</td>
						</tr><?
						}
						echo "</table>";
						if(($DiaI || $Numero || $Detalle || $Identificacion || $TotalCosto || $TotalVenta) && $Recursivo==1)
						{
							$consxx = "Select Fecha,Numero,Detalle,PrimApe,SegApe,PrimNom,SegNom,Movimiento.Cedula,Sum(TotCosto)+sum(VrIVA)-sum(VrDescto),
							Sum(TotVenta),TipoComprobante,Estado,NoFactura,VrFactura
							from Consumo.Movimiento,Central.Terceros where Movimiento.Cedula=Terceros.Identificacion and Movimiento.Compania='$Compania[0]'
							and Terceros.Compania='$Compania[0]' and Movimiento.Anio=$AnioI and Numero not in(Select Numero
							from Consumo.Movimiento,Central.Terceros where Movimiento.Cedula=Terceros.Identificacion and Movimiento.Compania='$Compania[0]'
							and Terceros.Compania='$Compania[0]' and Fecha>='$AnioI-$MesI-01' and Fecha<='$AnioI-$MesI-$UltDia' and TipoComprobante='$Tipo'
							and AlmacenPpal='$AlmacenPpal' and Comprobante='$Comprobante' $conFecha $conNumero $conDetalle $conIdentificacion 
							Group by Numero,Fecha,Detalle,PrimApe,SegApe,PrimNom,SegNom,Movimiento.Cedula,TipoComprobante,Estado,NoFactura,VrFactura
							$H $conTotalCosto $conTotalVenta
							Order By Numero,Fecha) and AlmacenPpal='$AlmacenPpal' and Comprobante='$Comprobante' $conFecha $conNumero $conDetalle $conIdentificacion 
							Group by Numero,Fecha,Detalle,PrimApe,SegApe,PrimNom,SegNom,Movimiento.Cedula,TipoComprobante,Estado,NoFactura,VrFactura
							$H $conTotalCosto $conTotalVenta
							Order By Numero,Fecha";
							$resxx = ExQuery($consxx);
							//echo $consxx;
							if(ExNumRows($resxx)>0)
							{
							?>
							<hr size="2">
							<table width='95%' class="tabla3"   <?php echo $borderTabla3Mentor; echo $bordercolorTabla3Mentor; echo $cellspacingTabla3Mentor; echo $cellpaddingTabla3Mentor; ?>>
								<tr>
									<td colspan="7" class="encabezado2Horizontal">	OTRAS COINCIDENCIAS FUERA DEL PERIODO</td>
								</tr>
								<tr>
									<td class="encabezado2HorizontalInvertido">FECHA</td>
									<td class="encabezado2HorizontalInvertido">N&Uacute;MERO</td>
									<td class="encabezado2HorizontalInvertido">DETALLE</td>
									<td class="encabezado2HorizontalInvertido">TERCERO</td>
									<td class="encabezado2HorizontalInvertido">TOTAL COSTO</td>
									<td class="encabezado2HorizontalInvertido">TOTAL VENTA</td>
								</tr>
								<?
									while($filaxx = ExFetch($resxx)){
										echo "<tr><td>$filaxx[0]</td><td>$filaxx[1]</td><td>$filaxx[2]</td><td> $filaxx[3] $filaxx[4] $filaxx[5] $filaxx[6]
										- $filaxx[7]<td align='right'>".number_format($filaxx[8],2)."</td><td align='right'>".number_format($filaxx[9],2)."</td></tr>";
									}
								?>
							</table>
							<?
							}
						}
					?>
					<iframe id="FrameOpener" name="FrameOpener" style="display:none" frameborder="0" height="1" style="border:#e5e5e5 ridge"></iframe>
					</form>
				</div>	
			</body>