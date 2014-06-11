		<?php
			if($DatNameSID){session_name("$DatNameSID");}
			session_start();
			include("Funciones.php");
			include_once("General/Configuracion/Configuracion.php");
			
			if($Guardar){
					if($FechaIni > $FechaFinal)
					{
						if($FechaFin)
						{
							if($FechaIni<$FechaFin)
							{
									$cons="Insert into Consumo.TarifasxProducto (Compania,AlmacenPpal,Tarifario,AutoId,FechaIni,FechaFin,ValorVenta,Anio)
									values ('$Compania[0]','$AlmacenPpal','$Tarifario','$AutoId','$FechaIni','$FechaFin','$VrVenta',$Anio)";
									$FechaIni=$FechaFin;$FechaFin="";$VrVenta="";
							}
							else
							{
									echo "<font color='#FF0000'><em>La Fecha Final debe ser mas reciente que la Fecha Inicial, No se ejecuto el registro</em></font>";	
							}
						}
						else
						{
							$cons="Insert into Consumo.TarifasxProducto (Compania,AlmacenPpal,Tarifario,AutoId,FechaIni,FechaFin,ValorVenta,Anio)
							values ('$Compania[0]','$AlmacenPpal','$Tarifario','$AutoId','$FechaIni','$FechaFin','$VrVenta',$Anio)";
							$FechaIni=$FechaFin;$FechaFin="";$VrVenta="";
						}
						$res=ExQuery($cons);    
					}
				}
			if($Cerrar)	{
				$cons="Update Consumo.TarifasxProducto set FechaFin='$FechaFin' where Compania='$Compania[0]'
				and AlmacenPpal='$AlmacenPpal' and Tarifario='$Tarifario' and AutoId='$AutoId' and FechaFin='0000-00-00' and Anio = $Anio
				Limit 1";
				$FechaIni=$FechaFin;$FechaFin="";
				$res=ExQuery($cons);	
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
				<script language='javascript' src="/Funciones.js"></script>
				<script language='javascript' src="/calendario/popcalendar.js"></script>
				<script language="javascript">
					function Validar()
					{
						var b=0;
						if(FORMA.FechaIni.value==""){alert("Debe llenar el campo Fecha Inicial"); b=1;}
						else{if(FORMA.VrVenta.value==""){alert("Debe llenar el campo Valor Venta"); b=1;}}
						if (b==1) return false;
					}
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
					<form name="FORMA" method="post" onSubmit="return Validar()">
						<input type="hidden" name="Anio" value="<? echo $Anio?>" />
						<input type="hidden" name="DatNameSID" value="<? echo $DatNameSID?>" />
					
							<table class="tabla1"  <?php echo $borderTabla1Mentor; echo $bordercolorTabla1Mentor; echo $cellspacingTabla1Mentor; echo $cellpaddingTabla1Mentor; ?> >
								<tr>
									<td class="encabezado1Horizontal">REGISTRO DE PRECIOS DE VENTAS</td>
								</tr>
								<tr>
									<td style="text-align:center">
											<select name="Tarifario" onChange="document.FORMA.submit();">
											<option>--Seleccione Tarifario--</option>
											<?
												$cons="Select Tarifario from Consumo.TarifariosVenta where Compania='$Compania[0]' 
												and AlmacenPpal='$AlmacenPpal' and Estado='AC' order by Tarifario";
												$res=ExQuery($cons);
												while($fila=ExFetch($res))
												{
													if($fila[0]==$Tarifario){echo "<option selected value='$fila[0]'>$fila[0]</option>";}
													else{echo "<option value='$fila[0]'>$fila[0]</option>";}
												}
											?>
											</select>
									</td>
								</tr>
								
								
												
											
								<?							
								if($Tarifario){
									?>
									<tr>
										<td style="text-align:center;">
								
											<table class="tabla1"   <?php echo $borderTabla1Mentor; echo $bordercolorTabla1Mentor; echo $cellspacingTabla1Mentor; echo $cellpaddingTabla1Mentor; ?> >
												<tr>
													<td class="encabezado1Horizontal">FECHA INICIAL</td>
													<td class="encabezado1Horizontal">FECHA FIN</td>
													<td class="encabezado1Horizontal" colspan="2">VR. VENTA</td>
												</tr>
													<?
														$cons="Select FechaIni,FechaFin,ValorVenta from Consumo.TarifasxProducto where Compania='$Compania[0]'
														and AlmacenPpal='$AlmacenPpal' and AutoId='$AutoId' and Tarifario='$Tarifario' Order By FechaIni Desc";
														$PermiteNew=1;
														$res=ExQuery($cons);
														while($fila=ExFetch($res))
														{

															echo "<tr><td>$fila[0]</td><td>";
															if($fila[1]=="0000-00-00"){
																//echo "<input type='text' style='width:90px;' name='FechaFin' value='$fila[1]' />";
																?><input type="text" style="width:90px;" name="FechaFin" readonly="readonly"
																		 onclick="popUpCalendar(this, FORMA.FechaFin, 'yyyy-mm-dd')"  value="<? echo $FechaFin; ?>" /><?
															}
															else
															{
																echo "$fila[1]";		
															}
															echo "</td><td colspan='2'>$fila[2]</td>";
															if($fila[1]=="0000-00-00"){$PermiteNew=0;echo "<td><button name='Cerrar' type='submit'><img alt='Cerrar Periodo' src='/Imgs/b_drop.png'></button>";}
																	$FechaFinal = $fila[1];

														}
														
														if($PermiteNew)	{
															?>
															<tr>
																<td>
																	<input style="width:90px;" type="text" name="FechaIni" readonly="readonly"	onclick="popUpCalendar(this, FORMA.FechaIni, 'yyyy-mm-dd')"  value="<? echo $FechaIni; ?>"  />	
																</td>		
																<td>
																	<input style="width:90px;" type="text" name="FechaFin" readonly="readonly" onclick="popUpCalendar(this, FORMA.FechaFin, 'yyyy-mm-dd'); this.value='';"  value="<? echo $FechaFin; ?>" />
																</td>	
																<td>
																	<input style="width:90px;" type="text" name="VrVenta" value="<? echo $VrVenta; ?>"	onKeyUp="xNumero(this)" onKeyDown="xNumero(this)" onBlur="campoNumero(this)">
																</td>
																<td>
																	<button type="submit" name="Guardar"><img alt="Guardar" src="/Imgs/b_save.png"></button>
																</td>
															</tr>
															<?	
														}
														?>					
											</table>
										</td>
									</tr>	
										
								<?php
							}?>
							
							<tr>
								<td style="text-align:center">						
									<input type="hidden" name="FechaFinal" value="<? echo $FechaFinal?>">
									<input type="button" onClick="CerrarThis();parent.document.FORMA.submit();" class="boton2Envio" value="Cerrar">
								</td> 
							</tr>	
						</table>
					</form>
				</div>	
			</body>
	</html>