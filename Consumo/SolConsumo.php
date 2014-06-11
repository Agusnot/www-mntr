		<?php
			if($DatNameSID){session_name("$DatNameSID");}
			session_start();
			include("Funciones.php");
			include_once("General/Configuracion/Configuracion.php");
			$ND=getdate();
			if(!$Anio){ 
				$Anio = $ND[year];
			}
			if($Anio!=$ND[year]){
				$disNuevo = " disabled ";
			}
			else{ 
				$disNuevo = ""; 
			}
			
			if($Eliminar==1){
				$cons="Update Consumo.SolicitudConsumo set Estado='Anulado' where IdSolicitud='$IdSolicitud' and Compania='$Compania[0]' and Anio='$Anio'";
				$res=ExQuery($cons);echo ExError();
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
				function Mostrar(num,Anio)	{
						open("/Informes/Almacen/Formatos/ImpSolicitudConsumo.php?DatNameSID=<? echo $DatNameSID?>&Anio="+Anio+"&IdSolicitud="+num,'','width=600,height=400,scrollbars=yes');
				}
					function Validar()	{
						if(document.FORMA.AlmacenPpal.value=="" || document.FORMA.CC.value=="")
						{
							alert("Seleccione un Almacen Principal y un Centro de Costos");
							return false;
						}
						else
						{
							location.href="NewSolicitud.php?DatNameSID=<? echo $DatNameSID?>&Anio=<? echo $Anio?>&AlmacenPrincipal=<?echo $AlmacenPpal?>&CC=<?echo $CC?>";
						}
					}
			</script>
		</head>	
		<body <?php echo $backgroundBodyMentor; ?>>
			<?php
				$rutaarchivo[0] = "ALMAC&Eacute;N";
				$rutaarchivo[1] = "SOLICITUD DE CONSUMO";
				mostrarRutaNavegacionEstatica($rutaarchivo);
			?>
			<div <?php echo $alignDiv2Mentor; ?> class="div2">
				<form name="FORMA" method="post">
					<input type="hidden" name="DatNameSID" value="<? echo $DatNameSID?>" />		
					<table class="tabla2"  <?php echo $borderTabla2Mentor; echo $bordercolorTabla2Mentor; echo $cellspacingTabla2Mentor; echo $cellpaddingTabla2Mentor; ?>>
						<tr>
							<td colspan="6" class="encabezado2Horizontal" > SOLICITUD DE CONSUMO </td>
						</tr>
						<tr>
							<td class="encabezado2VerticalInvertido">A&Ntilde;O</td>
							<td>
								<select name="Anio" onChange="FORMA.submit()" />
								<?
									$cons = "Select Anio from Central.Anios where Compania = '$Compania[0]' order by Anio desc";
									$res = ExQuery($cons);
									while($fila = ExFetch($res))
									{
										if($Anio==$fila[0]){echo "<option selected value='$fila[0]'>$fila[0]</option>";}
										else {echo "<option value='$fila[0]'>$fila[0]</option>";}
									}
									?>
								</select>
							</td>
							<td class="encabezado2VerticalInvertido">ALMAC&Eacute;N PRINCIPAL</td>
							<td>
								<select name="AlmacenPpal" onchange="FORMA.submit()"><option></option>
								<?
								echo $cons = "Select AlmacenPpal from Consumo.AutorizaUsuxSolicitudes where Usuario='$usuario[1]' and Compania='$Compania[0]'";
								$res = ExQuery($cons);
								while($fila = ExFetch($res))
								{
									if($AlmacenPpal==$fila[0]){echo "<option selected value='$fila[0]'>$fila[0]</option>";}
									else{echo "<option value='$fila[0]'>$fila[0]</option>";}
								}
								?>
							   </select>
							   
							</td>
							<td class="encabezado2VerticalInvertido">CENTRO DE COSTOS</td>
							<td>
								<select name="CC" onchange="FORMA.submit()"><option></option>
								<?
								$cons = "Select Codigo,CentroCostos from Consumo.UsuariosxCC,Central.CentrosCosto
								where UsuariosxCC.CC = CentrosCosto.Codigo and CentrosCosto.Compania = '$Compania[0]'
								and Usuario ='$usuario[1]' and UsuariosXCC.Anio=$Anio
								and CentrosCosto.Anio = $Anio";
								//echo $cons;
								$res=ExQuery($cons);
								while($fila=ExFetch($res))
								{
									if($fila[0]==$CC){ echo "<option selected value='$fila[0]'>$fila[0] - $fila[1]</option>";}
									else{echo "<option value='$fila[0]'>$fila[0] - $fila[1]</option>";}
								}
								?>
								</select>
							</td>
						</tr>
						<tr>
							<td colspan="6" style="text-align:center;">
								<input type="button" <? //echo $disNuevo;?> onclick="Validar()" class="boton2Envio"   value="Nueva Solicitud"/>
							</td>
						</tr>
					</table>
					<br>
					<?
					if($Anio){
						?>
							<table class="tabla2"  <?php echo $borderTabla2Mentor; echo $bordercolorTabla2Mentor; echo $cellspacingTabla2Mentor; echo $cellpaddingTabla2Mentor; ?>>
								<tr>
									<td colspan="9" class="encabezado2Horizontal">SOLICITUDES REALIZADAS</td>
								</tr>
								<tr>
									<td class="encabezado2HorizontalInvertido">ALMAC&Eacute;N PRINCIPAL</td>
									<td class="encabezado2HorizontalInvertido">NO. SOLICITUD</td>
									<td class="encabezado2HorizontalInvertido">FECHA SOLICITUD</td>
									<td class="encabezado2HorizontalInvertido">CANTIDAD PRODUCTOS</td>
									<td class="encabezado2HorizontalInvertido">ESTADO</td>
									<td class="encabezado2HorizontalInvertido" colspan="3" >CENTRO DE COSTOS</td>
									<td class="encabezado2HorizontalInvertido">&nbsp;</td>
								</tr>
							<?
								if($AlmacenPpal){$AdConsA=" And AlmacenPpal='$AlmacenPpal'";}
									if($CC){$AdConsC=" And CentroCostos='$CC'";}
									$cons = "Select IdSolicitud,Fecha,Estado,count(Estado),CentroCostos,AlmacenPpal
									from Consumo.SolicitudConsumo where Usuario='$usuario[0]' and Compania='$Compania[0]' and Anio=$Anio $AdConsA $AdConsC
								Group By IdSolicitud,Estado,Fecha,CentroCostos,AlmacenPpal order by AlmacenPpal asc,Fecha Desc";
								$res = ExQuery($cons);
								while($fila = ExFetch($res))
								{
									$xn++;
									if($fila[2]=="Anulado"){$Stylo="color:red;text-decoration:line-through";}else{$Stylo="";}
									?>
									<tr style="<?echo $Stylo?>"><td align="center">
									<?
									echo "$fila[5]</td><td>$fila[0]</td><td>$fila[1]</td><td align='center'>".$fila[3]."</td><td>$fila[2]</td><td align='center'>$fila[4]</td>";
								if($fila[2]=="Solicitado" && $Anio==$ND[year]){
							?>
								<td><a href="NewSolicitud.php?CC=<?echo $fila[4]?>&AlmacenPrincipal=<?echo $fila[5]?>&DatNameSID=<? echo $DatNameSID?>&FechaSol=<? echo $fila[1]?>&Anio=<? echo $Anio?>&Editar=1&IdSolicitud=<? echo $fila[0]?>"><img border="0" src="/Imgs/b_edit.png" /></a></td>
								<td><a href="#" onClick="if(confirm('Desea anular el registro?'))
										{location.href='SolConsumo.php?Anio=<? echo $Anio?>&DatNameSID=<? echo $DatNameSID?>&Eliminar=1&IdSolicitud=<?echo $fila[0]?>';}"><img border="0" src="/Imgs/b_drop.png"/></a>
								</td><? }
								else{?><td colspan="2" align="center">&nbsp;</td><?}
								?>
								<td>
									<button type="button" onclick="Mostrar('<? echo $fila[0]?>','<? echo $Anio?>')">
										<img src="/Imgs/b_print.png" title="Imprimir Solicitud">
									</button>
								</td></tr><?
								} ?>
							</table>

				</form>			
			</div>	
		</body>	
	<? } ?>
