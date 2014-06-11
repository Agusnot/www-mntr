		<?
			if($DatNameSID){session_name("$DatNameSID");}
			session_start();
			include("Funciones.php");
			include_once("General/Configuracion/Configuracion.php");
			
			if($TMPCOD){
				$cons="Select * from Consumo.TmpMovimiento where TMPCOD='$TMPCOD'";
				$res=ExQuery($cons);
				$CantElementos=ExNumRows($res);

				$cons="Select sum(VrIVA),sum(TotVenta),sum(VrDescto) from Consumo.TmpMovimiento where TMPCOD='$TMPCOD'";
				$res=ExQuery($cons);
				$fila=ExFetch($res);
				$IVA=$fila[0];
				$Descto=$fila[2];
				$SubTotal=$fila[1];
				$Total=$fila[1]+$fila[0];

				if($CantElementos>=1 || ExNumRows(ExQuery("Select CompRemision,NoCompRemision from Consumo.EntradasxRemisiones where Compania='$Compania[0]' and TMPCOD='$TMPCOD'"))>0)
				{
					if($fila[1]==0)
					{
					
						$IVA=0;
						$Descto=0;
						$SubTotal=0;
						$Total=0;

						$cons82="Select CompRemision,NoCompRemision from Consumo.EntradasxRemisiones where Compania='$Compania[0]' and TMPCOD='$TMPCOD'";
						$res82=ExQuery($cons82);echo ExError();
						if(ExNumRows($res82)>0)
						{
							while($fila82=ExFetch($res82))
							{
								$cons44="Select sum(VrIVA),sum(TotCosto),sum(VrDescto) from Consumo.Movimiento where Comprobante='$fila82[0]' and Numero='$fila82[1]' and Compania='$Compania[0]'
								and AlmacenPpal='$AlmacenPpal' and Estado='AC' and TipoComprobante='Remisiones'";
								$res44=ExQuery($cons44);
								$fila44=ExFetch($res44);
								$IVA=$IVA+$fila44[0];
								$Descto=$Descto+$fila44[2];
								$SubTotal=$SubTotal+$fila44[1];
								$Total=$Total+$fila44[1]+$fila44[0];
							}
						}
						$cons="Select sum(VrIVA),sum(TotCosto),sum(VrDescto) from Consumo.TmpMovimiento where TMPCOD='$TMPCOD'";
						$res=ExQuery($cons);
						$fila=ExFetch($res);
						$IVA=$IVA+$fila[0];
						$Descto=$Descto+$fila[2];
						$SubTotal=$SubTotal+$fila[1];
						$Total=$Total+$fila[1]+$fila[0];
										$Total = round($Total,0);
					}
					
					$consxx = "Select IncluyeIVA,PorcIVA,TotCosto from Consumo.TmpMovimiento where TMPCOD = '$TMPCOD' and IncluyeIVA = 1";
					$resxx = ExQuery($consxx);
					while($filaxx = ExFetch($resxx))
					{
									$Ivapar = $filaxx[2] - ($filaxx[2]/(($filaxx[1]/100)+1));
									$IVA = $IVA + $Ivapar;
									$SubTotal = $SubTotal - $Ivapar;
					}
					?>
				<script language="javascript">
					parent.document.FORMA.Guardar.disabled=false;
				</script>			
		<?		} 
				else
				{?>
				<script language="javascript">
					parent.document.FORMA.Guardar.disabled=true;
				</script>			
		<?		}
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
			</head>
			<body>
				<form name="FORMA">
					<input type="hidden" name="DatNameSID" value="<? echo $DatNameSID?>" />
					<table class="tabla2" width="690px" <?php echo $borderTabla2Mentor; echo $bordercolorTabla2Mentor; echo $cellspacingTabla2Mentor; echo $cellpaddingTabla2Mentor; ?>>
						<tr>
							<td style="text-align:left;padding-left:15px;">
								<table class="tabla2" border="0" <?php echo $cellspacingTabla2Mentor; echo $cellpaddingTabla2Mentor; ?>>
									<tr>
										<td class="encabezado2VerticalInvertido">EXIST. ANUAL</td>
										<td colspan="3">
											<input type="text" name="ExAnuales" size="3" style="text-align:right;width:170px" readonly="yes"  />
										</td>
									</tr>
									<tr>
										<td class="encabezado2VerticalInvertido">EXIST. CORTE</td>
										<td><input type="text" name="Existencias" size="3" style="text-align:right;" readonly="yes"  /></td>
										<td class="encabezado2VerticalInvertido">MIN.</td>
										<td><input type="text" name="Min" size="3" style="text-align:right;" readonly="yes"  /></td>
									</tr>
									<tr>
										<td class="encabezado2VerticalInvertido">M&Aacute;X.</td>
										<td><input type="text" name="Max" size="3" style="text-align:right;" readonly="yes" /></td>
										<td class="encabezado2VerticalInvertido">CANT.</td>
										<td><input type="text" name="CantElem" size="3" style="text-align:right;" readonly="yes" value="<?echo $CantElementos?>" /></td>
									</tr>
								</table>
							</td>
							<td style="text-align:right;padding-right:15px;">
								<table class="tabla2" border="0" <?php echo $cellspacingTabla2Mentor; echo $cellpaddingTabla2Mentor; ?>>
									<tr>
										<td class="encabezado2VerticalInvertido">SUBTOTAL</td>
										<td><input type="text" name="SubTotal" style="text-align:right" value='<? echo number_format($SubTotal,2)?>' size="13" readonly="yes" /></td>
										<td class="encabezado2VerticalInvertido">IVA</td>
										<td><input type="text" name="IVA" value="<? echo number_format($IVA,2)?>" style="text-align:right;width:100px;" size="13" readonly="yes" /></td>
									</tr>
									<tr>
										<td class="encabezado2VerticalInvertido">DESCUENTO</td>
										<td><input type="text" name="Descto" value="<? echo number_format($Descto,2)?>" style="text-align:right" size="13" readonly="yes"/></td>
										<td class="encabezado2VerticalInvertido">TOTAL</td>
										<td><input type="text" name="Total" value="<? echo number_format($Total,2)?>" style="text-align:right;font-weight:bold;width:100px;" size="11" readonly="yes" /></td>
									</tr>
								</table>
								<input type="hidden" name="TotDef" value="<? echo $Total?>" />
							</td>
						</tr>
					</table>
				</form>
			</body>	
		</html>	