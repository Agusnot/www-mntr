		<?
			if($DatNameSID){session_name("$DatNameSID");}
			session_start();
			include("Funciones.php");
			include_once("General/Configuracion/Configuracion.php");
				$ND=getdate(); 
				if(!$MesI){$MesI=$MesTrabajo;}
			if(!$DiaI){$DiaI=$ND[mday];}
			if(!$AnioI){$AnioI=$ND[year];}
			if(!$AlmacenPpal)
			{
					$cons = "Select AlmacenPpal from Consumo.UsuariosxAlmacenes where Usuario='$usuario[1]' and Compania='$Compania[0]'";
					$res = ExQuery($cons);
					$fila = ExFetch($res);
					$AlmacenPpal = $fila[0];
					$HacerSubmit = 1;
			}
			else
			{
					unset ($HacerSubmit);
			}
			if(!$Comprobante)
			{
					$cons="SELECT Comprobante FROM Consumo.Comprobantes WHERE Tipo='$Tipo' and Compania='$Compania[0]'
					and AlmacenPpal='$AlmacenPpal'
					ORDER BY Comprobante";
					$res=ExQuery($cons);
					$fila = ExFetch($res);
					$Comprobante = $fila[0];
			}
			
			$cons = "Select Mes From Central.CierreXPeriodos Where Compania='$Compania[0]' and Modulo='Contabilidad' and Anio=$AnioI and Mes=$MesI";
			$res = ExQuery($cons);
			if(ExNumRows($res)==1)
			{	$Disabled=" disabled ";
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
			<body <?php echo $backgroundBodyMentor; ?> onLoad="document.FORMA.submit();">
				<?php	
					$rutaarchivo[0] = "ALMAC&Eacute;N";
					$rutaarchivo[1] = "REGISTRO DE MOVIMIENTOS";
					if (!empty($Tipo)){
						$rutaarchivo[2] = strtoupper($Tipo);
					}
					
					mostrarRutaNavegacionEstatica($rutaarchivo);
				?>	
				<div <?php echo $alignDiv2Mentor; ?> class="div2">
					<form name="FORMA" target="Abajo" <?
						if($Tipo!="Ajustes" && $Tipo!="Devoluciones")
						{
							?>action="ListaMovimiento.php?DatNameSID=<? echo $DatNameSID?>"<?
						}
						if($Tipo=="Ajustes"){
							?>action="ListaMovimientoxAjustes.php?DatNameSID=<? echo $DatNameSID?>"<?
						}
						if($Tipo=="Devoluciones"){
							?>action="ListaDevoluciones.php?DatNameSID=<? echo $DatNameSID?>"<?
						}
						?> method="post">
						
						<table class="tabla2"   <?php echo $borderTabla2Mentor; echo $bordercolorTabla2Mentor; echo $cellspacingTabla2Mentor; echo $cellpaddingTabla2Mentor; ?>>
							<tr>
								<td class="encabezado2Horizontal">VISUALIZAR PERIODO</td>
								<td class="encabezado2Horizontal">ALMAC&Eacute;N</td>
								<td class="encabezado2Horizontal">COMPROBANTE</td>
								<td colspan="2" class="encabezado2Horizontal">&nbsp;</td>
							</tr>
							<tr>
								<td>
									<select name="AnioI" onChange="document.FORMA.submit(); ">
										<?
											$cons="Select Anio from Central.Anios where Compania = '$Compania[0]' order by Anio desc";
											$res=ExQuery($cons);
											while($fila=ExFetch($res)){
												if($AnioI==$fila[0]){echo "<option value='$fila[0]' selected>$fila[0]</option>";}
												else{echo "<option value='$fila[0]'>$fila[0]</option>";}		
											}
											
										?>
									</select>

									<select name="MesI" onChange="document.FORMA.submit();"> <!--parent.location.href='Movimiento.php?Tipo=<? echo $Tipo?>&AlmacenPpal='+FORMA.AlmacenPpal.value+'&Comprobante='+FORMA.Comprobante.value+'&AnioI='+FORMA.AnioI.value+'&MesI='+FORMA.MesI.value -->
										<?
										$cons="Select Mes,Numero,NumDias from Central.Meses Order By Numero";
										$res=ExQuery($cons,$conex);
										while($fila=ExFetch($res)){
											if($MesI==$fila[1]){echo "<option value='$fila[1]' selected>$fila[0]</option>";$NumDias=$fila[2];}
											else{echo "<option value='$fila[1]'>$fila[0]</option>";}
										}
										?>
									</select>
									
									<label>
										<input name="diacorte" type="text" id="diacorte" value="30" size="2" maxlength="2">
									</label>
							</td>

							<td>
							<select name="AlmacenPpal" onChange="document.FORMA.Comprobante.value=undefined;parent.location.href='Movimiento.php?DatNameSID=<? echo $DatNameSID?>&Tipo=<? echo $Tipo?>&AlmacenPpal='+FORMA.AlmacenPpal.value+'&Comprobante='+FORMA.Comprobante.value+'&AnioI='+FORMA.AnioI.value+'&MesI='+FORMA.MesI.value;document.frames.BusqComprobantes.location.href='BusqComprobantes.php?DatNameSID=<? echo $DatNameSID?>&Tipo=<? echo $Tipo?>&AlmacenPpal='+this.value;">
							<option><?
							$cons = "Select AlmacenPpal from Consumo.UsuariosxAlmacenes where Usuario='$usuario[1]' and Compania='$Compania[0]'";
							$res = ExQuery($cons);
							while($fila = ExFetch($res))
							{
								if($AlmacenPpal==$fila[0]){echo "<option selected value='$fila[0]'>$fila[0]</option>";}
								else{echo "<option value='$fila[0]'>$fila[0]</option>";}
							}
							?>
							</select>
							</td>
							<?
							if($Tipo!="Ajustes" && $Tipo !="Devoluciones"){?>
							<td>
							<select name="Comprobante" onChange="parent.location.href='Movimiento.php?DatNameSID=<? echo $DatNameSID?>&Tipo=<? echo $Tipo?>&AlmacenPpal='+FORMA.AlmacenPpal.value+'&Comprobante='+FORMA.Comprobante.value+'&AnioI='+FORMA.AnioI.value+'&MesI='+FORMA.MesI.value;">
								<option value="">&nbsp;</option>
							<?
								$cons="SELECT Comprobante FROM Consumo.Comprobantes WHERE Tipo='$Tipo' and Compania='$Compania[0]'
								and AlmacenPpal='$AlmacenPpal'
								ORDER BY Comprobante";
								$res=ExQuery($cons,$conex);echo ExError($conex);
								while ($fila=ExFetch($res))	{
									if($Comprobante==$fila[0]){echo "<option value='$fila[0]' selected>$fila[0]</option>";}
									else{echo "<option value='$fila[0]'>$fila[0]</option>";}
								}
							?>
							</select>
							</td>
							<td>
							<input type="Button" name="Nuevo" <? echo $Disabled?> class="boton2Envio" value="Nuevo" onClick="parent.location.href='NuevoMovimiento.php?DatNameSID=<? echo $DatNameSID?>&Anio='+AnioI.value+'&Mes='+MesI.value+'&Comprobante='+Comprobante.value+'&Tipo=<?echo $Tipo?>'+'&AlmacenPpal='+document.FORMA.AlmacenPpal.value" />
							</td>
							</tr><? }
							else{
							
								if($Tipo=="Ajustes"){
									?>
									<input type="hidden" name="Comprobante">
									<td>
										<input type="button" value="Nuevo Ajuste" class="boton2Envio" name="Nuevo" <? echo $Disabled?>	onClick="parent(1).location.href='NuevoAjuste.php?diacorte='+document.FORMA.diacorte.value+'&DatNameSID=<? echo $DatNameSID?>&Anio='+AnioI.value+'&Mes='+MesI.value+'&AlmacenPpal='+document.FORMA.AlmacenPpal.value">
									</td>	
									
									<?																					                     //+'&DiaI='+DiaI.value+
								}
								
								if($Tipo == "Devoluciones")	{
									?>
									<input type="hidden" name="Comprobante">
									<td>
										<input type="button" value="Nueva Devolucion" class="boton2Envio" name="Nuevo" <? echo $Disabled?>	onClick="parent(1).location.href='NuevaDevolucion.php?DatNameSID=<? echo $DatNameSID?>&Anio='+AnioI.value+'&Mes='+MesI.value+'&AlmacenPpal='+document.FORMA.AlmacenPpal.value">
									</td>	
									<?
								}
							}?>
						</table>
						<input type="Hidden" name="Tipo" value="<? echo $Tipo?>">
						<input type="hidden" name="DatNameSID" value="<? echo $DatNameSID?>" />
						<? if($HacerSubmit){?><script language="javascript">document.FORMA.submit();</script><?	}?>

					</form>
				</div>	
			</body>
			<iframe name="BusqComprobantes" id="BusqComprobantes"  width="1px" height="1px" src=""></iframe>			
		</html>

