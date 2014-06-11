		<?
			if($DatNameSID){session_name("$DatNameSID");}
			include ("Funciones.php");
			include_once("General/Configuracion/Configuracion.php");	
			session_start();
			$ND = getdate();
				if(!$Anio){$Anio = $ND[year];}
			if(!$AlmacenPpal)
			{
				$cons = "Select AlmacenPpal from Consumo.UsuariosxAlmacenes where Usuario='$usuario[0]' and Compania='$Compania[0]'";
				$res = ExQuery($cons);
				$fila = ExFetch($res);
				$AlmacenPpal = $fila[0];		
			}
			if(!$Estado)
				{
					if(!$RO){$Estado = "Solicitado";}
					else{$Estado = "Todos";}
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
				<script language="javascript">
					function Mostrar(num,Anio)
					{
						open("/Informes/Almacen/Formatos/ImpSolicitudConsumo.php?DatNameSID=<? echo $DatNameSID?>&Anio="+Anio+"&IdSolicitud="+num,'','width=600,height=400,scrollbars=yes');
					}
				</script>
			</head>	
			<body <?php echo $backgroundBodyMentor; ?>>
				<?php
					$rutaarchivo[0] = "ALMAC&Eacute;N";
					$rutaarchivo[1] = "APROBAR SOLICITUD DE CONSUMO";
					mostrarRutaNavegacionEstatica($rutaarchivo);
				?>
				<div <?php echo $alignDiv2Mentor; ?> class="div2">
					<form name="FORMA" method="post">
						<input type="hidden" name="DatNameSID" value="<? echo $DatNameSID?>" />
						<table class="tabla2"  <?php echo $borderTabla2Mentor; echo $bordercolorTabla2Mentor; echo $cellspacingTabla2Mentor; echo $cellpaddingTabla2Mentor; ?>>
							<tr>
								<td colspan="4" class="encabezado2Horizontal"> APROBACI&Oacute;N SOLICITUD DE CONSUMO </td>
							</tr>
							<tr>
								<td class="encabezado2VerticalInvertido">ALMAC&Eacute;N</td>
								<td colspan="3">
									<select name="AlmacenPpal" style="width:100%;" onChange="FORMA.submit();">
										<option value="">&nbsp;</option>
										<?
										$cons = "Select AlmacenPpal from Consumo.UsuariosxAlmacenes where Usuario='$usuario[1]' and Compania='$Compania[0]'";
										$res = ExQuery($cons);
										while($fila = ExFetch($res)){
												if($AlmacenPpal==$fila[0]){
													echo "<option selected value='$fila[0]'>$fila[0]</option>";
												}
												else{
													echo "<option value='$fila[0]'>$fila[0]</option>";
												}
										}
										?>
									</select>
								</td>
							</tr>
							<tr>
								<td class="encabezado2VerticalInvertido">A&Ntilde;O</td>
								<td>
									<select name="Anio" onChange="FORMA.submit()">
										<?
										$cons = "Select Anio from Central.Anios Where Compania='$Compania[0]' order by Anio DESC LIMIT 10";
										$res = ExQuery($cons);
										while($fila = ExFetch($res)){
										   if($Anio == $fila[0]){
												echo "<option selected value='$fila[0]'>$fila[0]</option>";
											}
										   else{
												echo "<option value='$fila[0]'>$fila[0]</option>";}
										}
										?>    
									</select>
								</td>
								<td class="encabezado2VerticalInvertido">ESTADO</td>
								<td>
									<select name="Estado" onChange="FORMA.submit()">
										<option <? if ($Estado=="Solicitado"){ echo " selected ";}?> value="Solicitado">Solicitado</option>
										<option <? if ($Estado=="Todos"){ echo " selected ";}?> value="Todos">Todos</option>
										<option <? if ($Estado=="Aprobada"){ echo " selected ";}?> value="Aprobada">Aprobada</option>
										<option <? if ($Estado=="Rechazada"){ echo " selected ";}?> value="Rechazada">Rechazada</option>
										<option <? if ($Estado=="Anulado"){ echo " selected ";}?> value="Anulado">Anulado</option>
									</select>
								</td>
							</tr>
							<tr>
								<td class="encabezado2VerticalInvertido">NO. SOLICITUD</td>
								<td>
									<input type="text" size="3" style="width:100%" name="NoSolicitud"/>
								</td>
								<td class="encabezado2VerticalInvertido">SOLICITANTE</td>
								<td>
								
									<select name="Solicitante">
										<option value="">&nbsp;</option>
											<?
											$cons = "Select Usuario from Consumo.SolicitudConsumo 
											Where SolicitudConsumo.Compania='$Compania[0]'
											and AlmacenPpal = '$AlmacenPpal' and Anio = $Anio group by Usuario order by Usuario";
											$res = ExQuery($cons);
											while($fila = ExFetch($res)){
												if($fila[0]==$Solicitante){$Selected = " selected ";}else{$Selected = "";}
												?><option <? echo $Selected?> value="<?echo $fila[0]?>"><?echo $fila[0]?></option><?
											}
											?>
									</select>
								</td>
							</tr>
							<tr>
								<td class="encabezado2VerticalInvertido">CENTRO DE COSTOS</td>
								<td colspan ="2">
									<select name="CCSolicitud"><option></option>
										<?
											$cons = "Select SolicitudConsumo.CentroCostos,CentrosCosto.CentroCostos
												from Central.CentrosCosto,Consumo.SolicitudConsumo
												Where CentrosCosto.Compania = '$Compania[0]' and SolicitudConsumo.Compania='$Compania[0]'
												and AlmacenPpal = '$AlmacenPpal' and SolicitudConsumo.Anio = $Anio and CentrosCosto.Anio = $Anio
												and CentrosCosto.Codigo = SolicitudConsumo.CentroCostos
												group by SolicitudConsumo.CentroCostos,CentrosCosto.CentroCostos order by CentrosCosto.CentroCostos";
											$res = ExQuery($cons);
											while($fila = ExFetch($res))
											{
												if($fila[0]==$CCSolicitud){$Selected = " selected ";}else{$Selected = "";}
												?><option <? echo $Selected?> value=<?echo $fila[0]?>><?echo "$fila[1] - $fila[0]"?></option><?
											}
										?>
									</select>
								<td style="text-align:center">
									<button type="submit"><img src="/Imgs/b_search.png" /></button><br>
									<span style="font-weight:bold;">Buscar</span>
								</td>
							</tr>
						</table>
					</form>	
					<?
						if($AlmacenPpal){
								if($Estado != "Todos"){$AdConsEstado = " and Estado='$Estado'";}
								if($NoSolicitud){$AdConsNoSol = " and IdSolicitud=$NoSolicitud";}
								if($Solicitante){$AdConsSolicitante = " and usuario='$Solicitante'";}
								if($CCSolicitud){$AdConsCC = " and CentroCostos = '$CCSolicitud'";}
								$cons = "Select IdSolicitud,Usuario,Fecha,count(Estado),Estado,CentroCostos
								from Consumo.SolicitudConsumo where Compania='$Compania[0]'
								and AlmacenPpal='$AlmacenPpal' and Anio=$Anio $AdConsEstado $AdConsNoSol $AdConsSolicitante $AdConsCC
								Group By IdSolicitud,Estado,Usuario,Fecha,Estado,CentroCostos order by Fecha DESC";
								//echo $cons;

								
								$res=ExQuery($cons);echo ExError();
							?>
							
							<table class="tabla2" style="margin-top:25px;margin-bottom:25px;"  <?php echo $borderTabla2Mentor; echo $bordercolorTabla2Mentor; echo $cellspacingTabla2Mentor; echo $cellpaddingTabla2Mentor; ?>>
								<tr>
									<td class="encabezado2Horizontal">NO. SOLICITUD</td>
									<td class="encabezado2Horizontal">SOLICITANTE</td>
									<td class="encabezado2Horizontal">FECHA SOLICITUD</td>
									<td class="encabezado2Horizontal">CANTIDAD PRODUCTOS</td>
									<td class="encabezado2Horizontal">ESTADO</td>
									<td class="encabezado2Horizontal">CENTRO DE COSTOS</td>
									<td class="encabezado2Horizontal">&nbsp;</td>
									
								</tr><?
									echo "";
							while($fila=ExFetch($res))	{
								$xn++;
								if($fila[4]=="Anulado"){$Stylo="color:red;";}else{$Stylo="";}
								//$AnioSol = substr($fila[2],0,4);
								?>
								<tr style="<?echo $Stylo?>"><td style="cursor:hand" title="Ver la solicitud" 
									onMouseOver="this.bgColor='#AAD4FF'" 
									onmouseout="this.bgColor='#FFFFFF'" 
									onclick="Mostrar('<? echo $fila[0]?>','<? echo $Anio?>')" 
									align="center">
								<?
								echo "$fila[0]</td><td>$fila[1]</td><td>$fila[2]</td><td align='center'>".$fila[3]."</td><td>$fila[4]</td><td align='center'>$fila[5]</td>";
								if(!$RO)
											{
											?>
												<td title="Ver Detalle"><a href="DetAprobSolConsumo.php?DatNameSID=<? echo $DatNameSID?>&IdSolicitud=<? echo $fila[0]?>&AlmacenPpal=<? echo $AlmacenPpal?>&EstadoPro=<? echo $fila[4]?>&CC=<? echo $fila[5]?>&Anio=<? echo $Anio?>">
												<img border="0" src="/Imgs/b_tblops.png" /></a></td></tr>
								<?    
											}

							}
									?></table><?
						}
					?>
				</div>
			</body>
		</html>	