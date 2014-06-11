	<?
		if($DatNameSID){session_name("$DatNameSID");}
		session_start();
		include ("Funciones.php");
		include_once("General/Configuracion/Configuracion.php");
		$ND=getdate();
		if($Eliminar)
		{
			$cons = "delete from Consumo.cumsxproducto Where Compania='$Compania[0]' and AlmacenPpal='$AlmacenPpal'
			and AutoId = $AutoId and Laboratorio='$Laboratorio' and Presentacion='$Presentacion'";
			$res = ExQuery($cons);
		}
		if($Guardar)
		{
			$cons = "Insert into Consumo.cumsxproducto (Compania,AlmacenPpal,Autoid,Laboratorio,CUM,RegInvima,Presentacion)
			values('$Compania[0]','$AlmacenPpal',$AutoId,'$Laboratorio','$CUM','$rinvima','$Presentacion')";
			$res = ExQuery($cons);
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
				<script language="Javascript">
					function CerrarThis()
					{
						parent.document.getElementById('FrameOpener').style.position='absolute';
						parent.document.getElementById('FrameOpener').style.top='1px';
						parent.document.getElementById('FrameOpener').style.left='1px';
						parent.document.getElementById('FrameOpener').style.width='1';
						parent.document.getElementById('FrameOpener').style.height='1';
						parent.document.getElementById('FrameOpener').style.display='none';
					}
					function Validar()
					{
						if (document.FORMA.CUM.value == "" || document.FORMA.Laboratorio.value==""){return false};
					}
				</script>			
				<script language="Javascript" src="/Funciones.js"></script>
		</script>		
			<body  onload="document.FORMA.Laboratorio.focus()">
				<div <?php echo $alignDiv2Mentor; ?> class="div2">
					<form name="FORMA" method="post" onsubmit="return Validar()">
						<div align="right">
							<button type="button" title="Cerrar" onclick="parent.Ocultar();CerrarThis();">
								<img src="/Imgs/b_drop.png" />
							</button>
						</div>
						
						<table class="tabla2"  <?php echo $borderTabla2Mentor; echo $bordercolorTabla2Mentor; echo $cellspacingTabla2Mentor; echo $cellpaddingTabla2Mentor; ?>>
							<tr>
								<td class="encabezado2Horizontal">LABORATORIO</td>
								<td class="encabezado2Horizontal">PRESENTACI&Oacute;N</td>
								<td class="encabezado2Horizontal">CUM</td>
								<td class="encabezado2Horizontal">REG. INVIMA</td>
								<td class="encabezado2Horizontal">&nbsp;</td>
							</tr>
							<tr>
								<td>
									<input type="text" name="Laboratorio" style=" width: 150px;" maxlength="95"
									onfocus="parent.Mostrar();
									parent.frames.Busquedas.location.href='Busquedas.php?DatNameSID=<? echo $DatNameSID?>&Tipo=Laboratorio&Laboratorio='+this.value+'&Objeto=parent.frames.FrameOpener.document.FORMA.Laboratorio&Validar=parent.frames.FrameOpener.document.FORMA.Lab&Enfocar=parent.frames.FrameOpener.document.FORMA.Presentacion';"
									onKeyUp="xLetra(this);
									parent.frames.Busquedas.location.href='Busquedas.php?DatNameSID=<? echo $DatNameSID?>&Tipo=Laboratorio&Laboratorio='+this.value+'&Objeto=parent.frames.FrameOpener.document.FORMA.Laboratorio&Validar=parent.frames.FrameOpener.document.FORMA.Lab&Enfocar=parent.frames.FrameOpener.document.FORMA.Presentacion';
									Lab.value='0';"
									onKeyDown="xLetra(this);"/>
									<input type="hidden" name="Lab" value="0" />
								</td>
								<td>
									<input type="text" name="Presentacion" style=" width: 150px;" maxlength="95"
									onfocus="parent.Mostrar();
									parent.frames.Busquedas.location.href='Busquedas.php?DatNameSID=<? echo $DatNameSID?>&Tipo=PresentacionL&Presentacion='+this.value+'&Objeto=parent.frames.FrameOpener.document.FORMA.Presentacion&Validar=parent.frames.FrameOpener.document.FORMA.Pres&Enfocar=parent.frames.FrameOpener.document.FORMA.CUM';"
									onKeyUp="xLetra(this);
									parent.frames.Busquedas.location.href='Busquedas.php?DatNameSID=<? echo $DatNameSID?>&Tipo=PresentacionL&Presentacion='+this.value+'&Objeto=parent.frames.FrameOpener.document.FORMA.Presentacion&Validar=parent.frames.FrameOpener.document.FORMA.Pres&Enfocar=parent.frames.FrameOpener.document.FORMA.CUM';
									Lab.value='0';"
									onKeyDown="xLetra(this);"/>
									<input type="hidden" name="Pres" value="0" />
								</td>
								<td>
									<input type="text" name="CUM" style=" width: 110px;" maxlength="95"
										   onfocus="parent.Ocultar()"
										   onKeyUp="xLetra(this);" onKeyDown="xLetra(this);"/>
								</td>
								<td>
									<input type="text" name="rinvima" style=" width: 110px;" maxlength="85"
										   onfocus="parent.Ocultar()"
										   onKeyUp="xLetra(this);" onKeyDown="xLetra(this);"/>
								</td>
								<td>
									<button type="submit" name="Guardar" title="Aceptar">
										<img src="/Imgs/b_check.png" />
									</button>
								</td>
							</tr>
						<?
						$cons = "Select Laboratorio,Presentacion,CUM,RegINVIMA from Consumo.CumsxProducto Where Compania='$Compania[0]'
						and AlmacenPpal='$AlmacenPpal' and Autoid='$AutoId'";
						$res = ExQuery($cons);
						while($fila = ExFetch($res))
						{
							echo "<tr><td>$fila[0]</td><td>$fila[1]</td><td>$fila[2]</td><td>$fila[3]</td>";
							?>
							<td align="center"><img src="/Imgs/b_drop.png" title="Eliminar"
									onclick="location.href='ConfCUMxProducto.php?DatNameSID=<?echo $DatNameSID?>&Anio=<?echo $Anio?>&AutoId=<?echo $AutoId?>&AlmacenPpal=<?echo $AlmacenPpal?>&Eliminar=1&Laboratorio=<?echo $fila[0]?>&Presentacion=<?echo $fila[1]?>'"
									style=" cursor: hand"/></td></tr>
							<?
						}
						?>
						</table>
					</form>
				</div>		
			</body>
	</html>		