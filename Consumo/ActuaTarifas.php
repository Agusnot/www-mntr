		<?
			if($DatNameSID){session_name("$DatNameSID");}
			session_start();
			include("Funciones.php");
			include_once("General/Configuracion/Configuracion.php");
			$ND = getdate();
			if(!$AlmacenPpal)
			{
				$cons = "Select AlmacenPpal from Consumo.UsuariosxAlmacenes where Usuario='$usuario[0]' and Compania='$Compania[0]'";
				$res = ExQuery($cons);
				$fila = ExFetch($res);
				$AlmacenPpal = $fila[0];	
			}
			if(!$Tarifario)
			{
				$cons="Select Tarifario from Consumo.TarifariosVenta where Compania='$Compania[0]' 
				and AlmacenPpal='$AlmacenPpal' and Estado='AC' and XDefecto = 'SI'";
				$res=ExQuery($cons);
				$fila = ExFetch($res);
				$Tarifario = $fila[0];
			}
			if(!$Anio){$Anio = $ND[year];}
				$cons = "Select distinct on (Autoid) Autoid,Fecha,VrCosto
					from consumo.movimiento
					Where Compania='$Compania[0]' and AlmacenPpal = '$AlmacenPpal'
					and Anio = $Anio and TipoComprobante = 'Entradas' and Estado='AC'
					order by Autoid,Fecha desc";
				$res = ExQuery($cons);
				while($fila=ExFetch($res))
				{
					$Precio[$fila[0]] = $fila[2];
				}
				$cons = "Select Autoid,VrUnidad from Consumo.SaldosInicialesxAnio
					Where Compania='$Compania[0]' and AlmacenPpal='$AlmacenPpal'
					and Anio = $Anio";
				$res = ExQuery($cons);
				while($fila = ExFetch($res))
				{
					if(!$Precio[$fila[0]])
					{
						$Precio[$fila[0]] = $fila[1];
					}
				}
			if($Guardar)
			{
				if($NewFechaIni && $NewFechaFin)
				{
							if($NewFechaIni >= $NewFechaFin)
							{ ?><script language="javascript">alert("LA FECHA FINAL DEBE SER MAS RECIENTE QUE LA FECHA INICIAL")</script><? }
							else
							{
								while(list($cad,$val)=each($NewTarifa))
								{
									if($val)
									{
										$cons = "Delete from Consumo.TarifasxProducto Where
										Compania='$Compania[0]' and AlmacenPpal='$AlmacenPpal'
										and Tarifario='$Tarifario' and Autoid=$cad and Anio=$Anio
										and FechaIni = '$VigActIni[$cad]' and FechaFin = '".$VigActFin[$cad]."'";
										if($NewFechaIni>=$VigActIni[$cad] && $NewFechaIni<=$VigActFin[$cad])
										{
											$res = ExQuery($cons);
										}
										else
										{
											if($NewFechaFin>=$VigActIni[$cad] && $NewFechaFin<=$VigActFin[$cad])
											{
												$res = ExQuery($cons);
											}
											else
											{
												if($NewFechaIni <= $VigActIni[$cad] && $NewFechaFin >= $VigActFin[$cad])
												{
													$res = ExQuery($cons);
												}
											}
										}
										$cons="Insert into Consumo.TarifasxProducto (Compania,AlmacenPpal,Tarifario,AutoId,
										FechaIni,FechaFin,ValorVenta,Anio,UltMod,UsuUltMod)
										values('$Compania[0]','$AlmacenPpal','$Tarifario','$cad','$NewFechaIni','$NewFechaFin',
										'$val',$Anio,'$ND[year]-$ND[mon]-$ND[mday] $ND[hours]:$ND[Minutes]:$ND[seconds]','$usuario[0]')";
										$res=ExQuery($cons);
									}
								}
								while(list($cad,$val)=each($PorcActAut))
								{
									if($val)
									{
										if(!$Precio[$cad]){$Precio[$cad]=0;}
										else
										{
											$Precio[$cad] = $Precio[$cad] + $Precio[$cad]*$val/100;
											$Precio[$cad] = round($Precio[$cad],0);
										}
										$cons = "Delete from consumo.TarifasxProducto Where AlmacenPpal='$AlmacenPpal'
										and Compania='$Compania[0]' and Tarifario='$Tarifario' and Anio = $Anio and AutoId=$cad";
										$res = ExQuery($cons);
										$cons = "Insert into Consumo.TarifasxProducto
											(Compania,AlmacenPpal,Tarifario,Autoid,FechaIni,
											FechaFin,ValorVenta,Anio,UltMod,UsuUltMod,PorcActAut)
											Values
											('$Compania[0]','$AlmacenPpal','$Tarifario',$cad,'$Anio-01-01',
											'$Anio-12-31',$Precio[$cad],$Anio,'$ND[year]-$ND[mon]-$ND[mday] $ND[hours]:$ND[minutes]:$ND[seconds]',
											'$usuario[0]',$val)";
										//echo $cons;exit;
										$res = ExQuery($cons);
									}
								}
							}
				}
						else
						{
							while(list($cad,$val)=each($PorcActAut))
							{
								if($val)
								{
									if(!$Precio[$cad]){$Precio[$cad]=0;}
									else
									{
										$Precio[$cad] = $Precio[$cad] + $Precio[$cad]*$val/100;
										$Precio[$cad] = round($Precio[$cad],0);
									}
									$cons = "Delete from consumo.TarifasxProducto Where AlmacenPpal='$AlmacenPpal'
									and Compania='$Compania[0]' and Tarifario='$Tarifario' and Anio = $Anio and AutoId=$cad";
									$res = ExQuery($cons);
									$cons = "Insert into Consumo.TarifasxProducto
										(Compania,AlmacenPpal,Tarifario,Autoid,FechaIni,
										FechaFin,ValorVenta,Anio,UltMod,UsuUltMod,PorcActAut)
										Values
										('$Compania[0]','$AlmacenPpal','$Tarifario',$cad,'$Anio-01-01',
										'$Anio-12-31',$Precio[$cad],$Anio,'$ND[year]-$ND[mon]-$ND[mday] $ND[hours]:$ND[minutes]:$ND[seconds]',
										'$usuario[0]',$val)";
									//echo $cons;exit;
									$res = ExQuery($cons);
								}
							}
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
				<script language='javascript' src="/calendario/popcalendar.js"></script>
				<script language='javascript' src="/Funciones.js"></script>
				<script language="javascript">
					function Validar(x)	{
						if(x==1){
							document.FORMA.action = "ActuaTarifasIncPorc.php";
							document.FORMA.submit();	
						}
					}
						function Des_BloquearTarifa(Objeto,AutoId)	{
							if(Objeto.value!=''){
								document.getElementById("NewTarifa["+AutoId+"]").value='';
								document.getElementById("NewTarifa["+AutoId+"]").disabled=true;
							}
							else{
								document.getElementById("NewTarifa["+AutoId+"]").disabled=false;
							}
						}
						function ActualizarTodo(objeto)	{
							if(objeto.value!=''){
								for (i=0;i<document.FORMA.elements.length;i++)	{
									if(document.FORMA.elements[i].type == "text"){
										//PorcActAut[$fila[0]]
										var Campo = document.FORMA.elements[i].name.substr(0, 10);
										if(Campo=="PorcActAut"){document.FORMA.elements[i].value=objeto.value;}
										else{document.FORMA.elements[i].disabled=true;}
									}
								}
							}
							else{
								for (i=0;i<document.FORMA.elements.length;i++)	{
									if(document.FORMA.elements[i].type == "text"){
										//PorcActAut[$fila[0]]
										var Campo = document.FORMA.elements[i].name.substr(0, 10);
										if(Campo=="PorcActAut"){document.FORMA.elements[i].value=objeto.value;}
										else{document.FORMA.elements[i].disabled=false;}
									}
								}
							}
							/*if(objeto.checked==true)
							{
								for (i=0;i<document.FORMA.elements.length;i++)
								{
									if(document.FORMA.elements[i].type == "text")
									{
										document.FORMA.elements[i].name;break;
									}
								}
							}
							else
							{
								for (i=0;i<document.FORMA.elements.length;i++)
								{
									if(document.FORMA.elements[i].type == "text")
									{
											document.FORMA.elements[i].checked = false;
									}
								}
							}*/
						}
				</script>
			</head>	
			<body <?php echo $backgroundBodyMentor; ?>>
				<?php
					$rutaarchivo[0] = "ALMAC&Eacute;N";
					$rutaarchivo[1] = "ACTUALIZACI&Oacute;N DE TARIFAS";
					mostrarRutaNavegacionEstatica($rutaarchivo);
				?>
				
				
				<div <?php echo $alignDiv2Mentor; ?> class="div2">
					<form name="FORMA" method="post">
						<input type="hidden" name="DatNameSID" value="<? echo $DatNameSID?>" />	
						<table width="500px" class="tabla2"  <?php echo $borderTabla2Mentor; echo $bordercolorTabla2Mentor; echo $cellspacingTabla2Mentor; echo $cellpaddingTabla2Mentor; ?>>		
							<tr>
								<td colspan="4" class="encabezado2Horizontal"> ACTUALIZACI&Oacute;N DE TARIFAS </td>
							</tr>
							<tr>
								<td class="encabezado2HorizontalInvertido"> ALMAC&Eacute;N </td>
								<td class="encabezado2HorizontalInvertido"> GRUPO</td>
								<td class="encabezado2HorizontalInvertido"> TARIFARIO</td>
								<td class="encabezado2HorizontalInvertido"> &nbsp;</td>
							</tr>
							<tr>
								<td>
									<select style="width:300px;" name="AlmacenPpal" onChange="document.FORMA.Tarifario.value='';document.FORMA.submit();">
										<option value="">&nbsp;</option>
										<?
										$cons = "Select AlmacenPpal from Consumo.UsuariosxAlmacenes where Usuario='$usuario[1]' and Compania='$Compania[0]'";
										$res = ExQuery($cons);
										while($fila = ExFetch($res)){
											if($AlmacenPpal==$fila[0]){echo "<option selected value='$fila[0]'>$fila[0]</option>";}
											else{echo "<option value='$fila[0]'>$fila[0]</option>";}
										}
										?>
									 </select>
								</td>
								<td>								
									<select style="width:300px;" name="Grupo" onChange="document.FORMA.submit();"><option></option>
										<?
										$cons="Select grupo from Consumo.grupos where Compania='$Compania[0]' 
										and AlmacenPpal='$AlmacenPpal' and anio='$ND[year]'";
										$res=ExQuery($cons);
										while($fila=ExFetch($res)){
											if($Grupo==$fila[0]){echo "<option selected value='$fila[0]'>$fila[0]</option>";}
											else{echo "<option value='$fila[0]'>$fila[0]</option>";}
										}
										?>
									</select>
									
								</td>
								<td>									
									<select style="width:300px;" name="Tarifario" onChange="document.FORMA.submit();"><option></option>
										<?
										$cons="Select Tarifario from Consumo.TarifariosVenta where Compania='$Compania[0]' 
										and AlmacenPpal='$AlmacenPpal' and Estado='AC'";
										$res=ExQuery($cons);
										while($fila=ExFetch($res)){
											if($Tarifario==$fila[0]){echo "<option selected value='$fila[0]'>$fila[0]</option>";}
											else{echo "<option value='$fila[0]'>$fila[0]</option>";}
										}
										?>
									</select>
								</td>
								<td>
									<input type="submit" class="boton2Envio" value="Enviar"/>
								</td>
							</tr>
						</table>


						
						<?						
						
							if($Tarifario){
								$cons = "Select AutoId,Codigo1,NombreProd1,Presentacion,UnidadMedida from Consumo.CodProductos 
								where Compania='$Compania[0]' and AlmacenPpal='$AlmacenPpal' and Anio = $ND[year] and grupo='$Grupo' order by NombreProd1";
								$res = ExQuery($cons);
								?>
								<br>
								<table class="tabla2" style="margin-top:25px;margin-bottom:25px;"  <?php echo $borderTabla2Mentor; echo $bordercolorTabla2Mentor; echo $cellspacingTabla2Mentor; echo $cellpaddingTabla2Mentor; ?>>
									<tr>
										<td colspan='2' class="encabezado2Horizontal">&nbsp;</td>
										<td class="encabezado2Horizontal">ACTUALIZACI&Oacute;N AUTOMATICA</td>
										<td class="encabezado2Horizontal">NUEVA VIGENCIA</td>							
										<td class="encabezado2Horizontal">DESDE 
											<input type="text" name="NewFechaIni" size="8" 	onclick="popUpCalendar(this, FORMA.NewFechaIni, 'yyyy-mm-dd')"  value="<? echo $NewFechaIni; ?>" readonly="yes" />
										</td>
										<td class="encabezado2Horizontal">HASTA
											<input type="text" name="NewFechaFin" size="8" 	onclick="popUpCalendar(this, FORMA.NewFechaFin, 'yyyy-mm-dd')"  value="<? echo $NewFechaFin; ?>" readonly="yes" />
										</td>
									</tr>
								
									<tr>
										<td class='encabezado2HorizontalInvertido' >C&Oacute;DIGO</td>
										<td width='350px' class='encabezado2HorizontalInvertido'>PRODUCTO</td>
										<td title='Esta configuracion se aplicara para todos los productos' class='encabezado2HorizontalInvertido'>
											PORCENTAJE
											
											<input type="text" name="PorcActAutTODO" size="3" maxlength="3" style="text-align:right" onKeyUp="xNumero(this);ActualizarTodo(this);" onKeyDown="xNumero(this)" onBlur="campoNumero(this)" />
											%
										</td>
										<td class='encabezado2HorizontalInvertido'>&Uacute;LTIMA VIGENCIA</td>
										<td class='encabezado2HorizontalInvertido'>VALOR VENTA</td>
										<td class='encabezado2HorizontalInvertido'>NUEVO VALOR</td>
									</tr>
								<?php
								while($fila=ExFetch($res))	{
									$cons1="Select FechaIni,FechaFin,ValorVenta 
									from Consumo.TarifasxProducto
									where AutoId='$fila[0]' and Tarifario='$Tarifario' order by FechaFin Desc";
									$res1=ExQuery($cons1);
									$fila1=ExFetch($res1);
									?>
									<tr>
									<td style="text-align:center;"><?php echo $fila[1];?></td>
									<td><?php echo "$fila[2] $fila[3] $fila[4]";?></td>
									<td style="text-align:center;">
										<input type="text" name="PorcActAut[$fila[0]]"	size="3" maxlength="3" style="text-align:right"	onKeyUp="xNumero(this);Des_BloquearTarifa(this,'$fila[0]');" onKeyDown="xNumero(this)"	onBlur="campoNumero(this)" />
										%
									</td>
									<td>
										<table width="100%" border="0" cellpadding="1" cellspacing="0">
											<tr>
												<td style="text-align:center;">
													<input type="text" size="6" style="border:none"	name="VigActIni[$fila[0]]" value="<?php echo $fila1[0]; ?>" readonly>
												</td>
											</tr>
											<tr>
												<td style="text-align:center;">
													<span style="font-weight:bold;font-size:11px;">HASTA </span>
												</td>
											</tr>
											<tr>
												<td style="text-align:center;">
													<input type="text" size="6" style="border:none"	name="VigActFin[$fila[0]]" value="<?php echo $fila1[1]; ?>" readonly>
												</td>
											</tr>
										</table>												
									</td>
									<td style="text-align:center;"><?php echo number_format($fila1[2],2);?></td>
						
									<td align="center">$
										<input type="text" name="NewTarifa[<? echo $fila[0]?>]"  id="NewTarifa[<? echo $fila[0]?>]"	size="6" maxlength="10" style="text-align:left"	onKeyUp="xNumero(this)" onKeyDown="xNumero(this)" onBlur="campoNumero(this)" />
									</td>
						<?
									echo "</tr>";
								
								}
								echo "</table>";
						?>
								<input type="submit" name="Guardar" class="boton2Envio" value="Guardar" />
								<input type="button" name="IncPorc"  class="boton2Envio" value="Ajuste Porcentual" onClick="Validar(1)" />		

						<?		
							}
						?>
					</form>
				</div>	
			</body>
		</html>	