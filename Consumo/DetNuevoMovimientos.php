		<?
			if($DatNameSID){session_name("$DatNameSID");}
			session_start();
			include("Funciones.php");
			include_once("General/Configuracion/Configuracion.php");
			$ND=getdate();
			$consyyy = "Select * from Consumo.AlmacenesPpales Where Compania='$Compania[0]' and AlmacenPpal='$AlmacenPpal'
			and SSFarmaceutico=1";
			$resyyy = ExQuery($consyyy);
			if(ExNumRows($resyyy)){$AlmacenSF = 1;}
			if($Eliminar){
				$cons = "Delete from Consumo.TmpMovimiento where AutoId = '$AutoId' and TMPCOD = '$TMPCOD'";
				$res = ExQuery($cons);
				echo ExError();
				?>
				<script>
						parent.frames.TotMovimientos.location.href='TotMovimientos.php?DatNameSID=<? echo $DatNameSID?>&TMPCOD=<? echo $TMPCOD?>';
				</script>
				<?
			}
			
            $cons = "Select Codigo1,(NombreProd1 || ' ' || Presentacion || ' ' || UnidadMedida),Cantidad,TotCosto,TmpMovimiento.AutoId,TotVenta,
            TmpMovimiento.VrIVA,IncluyeIVA,PorcIVA from Consumo.TmpMovimiento, Consumo.CodProductos
            where AlmacenPpal='$AlmacenPpal' and TMPCOD = '$TMPCOD' and CodProductos.AutoId = TmpMovimiento.AutoId
            and CodProductos.Anio = $Anio and Compania='$Compania[0]'";
            //echo $cons;
            $res = ExQuery($cons); echo ExError();
            //exit;
            $cons1="Select Costo,Venta from Consumo.Comprobantes where Comprobante='$Comprobante' and Compania='$Compania[0]' and AlmacenPpal='$AlmacenPpal'";
            $res1 = ExQuery($cons1); echo ExError();
            $fila1=ExFetch($res1);
            if($fila1[0]=="SI"){$MostrarCosto=1;}
            if($fila1[1]=="SI"){$MostrarVenta=1;}
			?>
			
		
	<html>
		<head>
			<?php echo $codificacionMentor; ?>
			<?php echo $autorMentor; ?>
			<?php echo $titleMentor; ?>
			<?php echo $iconMentor; ?>
			<?php echo $shortcutIconMentor; ?>
			<link rel="stylesheet" type="text/css" href="../General/Estilos/estilos.css">
		</head>
		
		<body <?php echo $backgroundBodyMentor; ?>>
			
			
				<table width="680px" class="tabla2"   <?php echo $borderTabla2Mentor; echo $bordercolorTabla2Mentor; echo $cellspacingTabla2Mentor; echo $cellpaddingTabla2Mentor; ?>>
					<tr>
						<td class="encabezado2Horizontal">C&Oacute;DIGO</td>
						<td class="encabezado2Horizontal">NOMBRE PRODUCTO</td>
						<td class="encabezado2Horizontal">CANTIDAD</td>
						<?
							if($MostrarCosto) echo "<td class='encabezado2Horizontal'>TOTAL COSTO</td>";
							if($MostrarVenta) echo "<td class='encabezado2Horizontal'>TOTAL VENTA</td>";
						?>
						<td class="encabezado2Horizontal">IVA</td>
						<td class="encabezado2Horizontal">TOTAL</td>
						<td class="encabezado2Horizontal">&nbsp;</td>
						<td class="encabezado2Horizontal">&nbsp;</td>
						
					</tr>
				
					<?
					while($fila=ExFetch($res)){
							$CANT++;
							$Prods = $Prods + 1;
							if(!$fila[6] || $fila[6] == 0)	{
								if($fila[7] == 1){
									//$fila[6]: TotalIva      $fila[3]:SUBTOTAL    $fila[8]:porcIVA
									$fila[6] = $fila[3] - ($fila[3]/(($fila[8]/100)+1));
									$fila[3] = $fila[3] - $fila[6];
								}
							}
						echo "<tr><td>$fila[0]</td><td>$fila[1]</td><td align='right'>".number_format($fila[2],2)."</td>";
						if($MostrarCosto){ echo "<td align='right'>" . number_format($fila[3],2)."</td>";$Total=$fila[3]+$fila[6];}
						if($MostrarVenta){ echo "<td align='right'>" . number_format($fila[5],2)."</td>";$Total=$fila[5]+$fila[6];}
						echo "<td align='right'>".number_format($fila[6],2)."</td><td align='right'>".number_format($Total,2)."</td>";
						$TOT = $TOT + $fila[3];
							$IVA = $IVA + $fila[6];
							if($Editar)	{
								if($Tipo=="Entradas"){
									$consxx = "Select AutoId,Fecha from Consumo.Movimiento where TipoComprobante = '$Tipo' 
									and Comprobante='$Comprobante' and Numero='$Numero' and AlmacenPpal='$AlmacenPpal' and Compania='$Compania[0]'";
									//echo $consxx;
									$resxx = ExQuery($consxx);
									while($filaxx = ExFetch($resxx))
									{
										if(!$Verificacion[$filaxx[0]])
										{
											//echo "$filaxx[0]--------<br>";
											$consx1="Select AutoId,Numero,Fecha from Consumo.Movimiento where TipoComprobante = 'Salidas'
											and fecha >= '$filaxx[1]' and AutoId = $filaxx[0] and AlmacenPpal = '$AlmacenPpal' and Compania='$Compania[0]' and Anio='$Anio'
											and Estado <> 'AN'";
											//echo $consx1.";";
											$resx1 = ExQuery($consx1);
											if(ExNumRows($resx1)>0)
											{
												while($filax1 = ExFetch($resx1))
												{
													$TieneSalida[$filax1[0]] = 1;
												} 
											}
											$Verificacion[$filaxx[0]] = 1;
										}
									}
								}
							}
				?>
					<td>
						<a <? if(!$TieneSalida[$fila[4]])
							{
							?>href="CrearDetalleMov.php?DatNameSID=<? echo $DatNameSID?>&Editar=1&Anio=<? echo $Anio?>&AlmacenPpal=<? echo $AlmacenPpal;?>&Comprobante=<? echo $Comprobante;?>&AutoId=<? echo $fila[4]?>&TMPCOD=<? echo $TMPCOD; ?>&Tipo=<? echo $Tipo?>&Numero=<? echo $Numero?>"<? } else{?> href="#" onClick="alert('NO ES POSIBLE EDITAR, El Producto Tiene Salidas con Fechas Posteriores a la Actual')"<? } ?>><img border="0" src="/Imgs/b_edit.png" /></a>
					</td>
					
				<td><a href="#"<? if(!$TieneSalida[$fila[4]])
								{?>onClick="if(confirm('Desea eliminar el registro?'))
						{location.href='DetNuevoMovimientos.php?DatNameSID=<? echo $DatNameSID?>&Eliminar=1&Anio=<? echo $Anio?>&AlmacenPpal=<? echo $AlmacenPpal;?>&Comprobante=<? echo $Comprobante;?>&AutoId=<? echo $fila[4]?>&TMPCOD=<? echo $TMPCOD; ?>&Tipo=<? echo $Tipo?>&Numero=<? echo $Numero?>';}"<? }else{?>href="#" onClick="alert('NO ES POSIBLE ELIMINAR, El Producto Tiene Salidas con Fechas Posteriores a la Actual')"<? }?> >
					<img border="0" src="/Imgs/b_drop.png"/></a></td>
				<?
				//echo $Tipo;
						if($Tipo=="Entradas")
						{
							$consAlm="Select * from Consumo.AlmacenesPpales Where
							Compania='$Compania[0]' and AlmacenPpal='$AlmacenPpal' and SSFarmaceutico=1";
							$resAlm = ExQuery($consAlm);
							if(ExNumRows($resAlm)>0)
							{
								if(!$TieneSalida[$fila[4]])
								{
								?>
								<td align="center"><img src="/Imgs/b_tblops.png" style=" cursor: hand" title="Datos tecnicos"
								 onClick="parent.AbrirLotes('<? echo $AlmacenPpal?>','<? echo $fila[4]?>','<? echo $fila[2]?>','<?echo $Comprobante?>')" /></td>
								<?
								}
								$consLotes = "Select Cerrado from Consumo.Lotes Where Compania='$Compania[0]' and AlmacenPpal='$AlmacenPpal' and Numero = '$Numero'
								and TMPCOD='$TMPCOD' and AutoId=$fila[4] and Tipo='$Comprobante' and Cerrado=1";
								//echo $consLotes;
								$resLotes = ExQuery($consLotes);
								$filaLotes=ExFetch($resLotes);
								//echo $consLotes;
								if(ExNumRows($resLotes)>0)
								{
									$CheckProds = $CheckProds + 1;
									?><td><img src="/Imgs/b_check.png" /></td><?
								}
							}
						}
							?>
						</tr>
						 <?
					}
						?>
						<script language="Javascript">
						function Valores()
						{
							parent.frames.TotMovimientos.document.FORMA.SubTotal.value="<? echo number_format($TOT,2)?>";
							parent.frames.TotMovimientos.document.FORMA.IVA.value="<? echo number_format($IVA,2)?>";
							parent.frames.TotMovimientos.document.FORMA.Total.value="<? echo number_format($IVA+$TOT,2)?>";
							parent.frames.TotMovimientos.document.FORMA.TotDef.value="<? echo $TOT+$IVA?>";
							parent.frames.TotMovimientos.document.FORMA.CantElem.value="<? echo $CANT?>";
						}
						</script>

					</table>
					
					<form name="FORMA" method="post" action="CrearDetalleMov.php">
						<input type="hidden" name="Tipo" value="<? echo $Tipo?>" />
						<input type="hidden" name="Numero" value="<? echo $Numero?>" />
						<input type="hidden" name="Eliminar" value="<? echo $Eliminar; ?>"  />
						<input type="hidden" name="TMPCOD" value="<? echo $TMPCOD; ?>" />
						<input type="Hidden" name="Comprobante" value="<? echo $Comprobante; ?>" />
						<input type="hidden" name="AlmacenPpal" value="<? echo $AlmacenPpal; ?>"   />
						<input type="hidden" name="Anio" value="<? echo $Anio?>" />
						<input type="hidden" name="DatNameSID" value="<? echo $DatNameSID?>" />
						<?
							$cons="Select * from Consumo.Comprobantes where AlmacenPpal='$AlmacenPpal' and Comprobante='$Comprobante' and Compania='$Compania[0]' and ExigeOC='SI'";
							$res=ExQuery($cons);
							if(ExNumRows($res)==0){
						?>
						<div align="center"><input type="submit" name="Nuevo" class="boton2Envio" value="Nuevo" /> </div><? }?>
					</form>
					<?
						$cons82="Select * from Consumo.EntradasxRemisiones where Compania='$Compania[0]' and TMPCOD='$TMPCOD'";
						$res82=ExQuery($cons82);echo ExError();
						if(ExNumRows($res82)>0)	{
							?>
							<iframe name="Remisiones" src="RemisionesxCompras.php?AlmacenPpal=<? echo $AlmacenPpal?>&Numero=<? echo $Numero?>&Comprobante=<? echo $Comprobante?>&TMPCOD=<? echo $TMPCOD?>" frameborder="0" width="80%"></iframe>
							<?
						}?>
					
					<?
					
					if($AlmacenSF)  {
						//echo "$Prods ---- $CheckProds";
						?>
						<script language="javascript">
							parent.document.FORMA.totprods.value = "<? echo $Prods?>";
							parent.document.FORMA.totchecks.value = "<? echo $CheckProds?>";
						</script>
						<?
					}
					?>
				
		</body>	
	</html>
