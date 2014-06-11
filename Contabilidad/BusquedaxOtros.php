		<?
			if($DatNameSID){session_name("$DatNameSID");}
			session_start();
			include("Funciones.php");
			include_once("General/Configuracion/Configuracion.php");
			$ND=getdate();
			if(!$Anio){$Anio=$ND[year];}
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
				function CerrarThis(){
					parent.document.getElementById('FrameOpener').style.position='absolute';
					parent.document.getElementById('FrameOpener').style.top='1px';
					parent.document.getElementById('FrameOpener').style.left='1px';
					parent.document.getElementById('FrameOpener').style.width='1';
					parent.document.getElementById('FrameOpener').style.height='1';
					parent.document.getElementById('FrameOpener').style.display='none';
				}
			</script>
		</head>
		<body <?php echo $backgroundBodyMentor; ?>>
			<div <?php echo $alignDiv2Mentor; ?> class="div2" >
					<?
					if($Tipo=="Tercero"){
						?>
						<form name="FORMA">
							<table class="tabla2"  <?php echo $borderTabla2Mentor; echo $bordercolorTabla2Mentor; echo $cellspacingTabla2Mentor; echo $cellpaddingTabla2Mentor; ?> >
								<tr>
									<td class="encabezado2Horizontal"> NOMBRE </td>
									<td class="encabezado2Horizontal">IDENTIFICACI&Oacute;N</td>
								</tr>
								<tr>
									<td><input type="Text" name="Tercero" style="width:420px;" onkeyup="frames.Busquedas.location.href='Busquedas.php?DatNameSID=<? echo $DatNameSID?>&Tipo=Nombre&Nombre='+this.value"></td>
									<td><input type="Text" name="Identificacion"></td>
								</tr>
								<input type="Hidden" name="Detalle">
							</table>
							<?	
							if(!$NuevoMovimiento){
								?>
								<input type="Button" class="boton2Envio"  value="Regresar" onclick="parent.frames.FORMA.<? echo $Campo?>.value=Identificacion.value;CerrarThis();">
								<?
							}
							else{
								?>
								<input type="Button" class="boton2Envio"  value="Regresar" onclick="parent.frames.NuevoMovimiento.document.FORMA.<? echo $Campo?>.value=Identificacion.value;CerrarThis();">
								<?
							}
							?>
						</form>
						<?
					}
					
					
					
					
					if($Tipo=="Cuentas"){
						?>
						<form name="FORMA">
							<table class="tabla2"  <?php echo $borderTabla2Mentor; echo $bordercolorTabla2Mentor; echo $cellspacingTabla2Mentor; echo $cellpaddingTabla2Mentor; ?>>
								<tr>
									<td class="encabezado2Horizontal">CUENTA</td>
								</tr>
								<tr>
									<td>
										<input type="Text" name="Cuenta" style="width:420px;" onkeyup="frames.Busquedas.location.href='Busquedas.php?DatNameSID=<? echo $DatNameSID?>&Tipo=PlanCuentasGr&Cuenta='+this.value+'&Anio=<?echo $Anio?>'">
									</td>
								</tr>	
							</table>
							<?
							if(!$Formulario){
								$Formulario="FORMA";
							}
							?>
							<input type="Button" class="boton2Envio"  value="Regresar" onclick="parent.document.<?echo $Formulario?>.<?echo $Campo?>.value=Cuenta.value;CerrarThis();">
						</form>
						<?	
					}
					
					
					
					
					if($Tipo=="CuentasDetalle")	{
						?>
						<form name="FORMA">
							<table class="tabla2"  <?php echo $borderTabla2Mentor; echo $bordercolorTabla2Mentor; echo $cellspacingTabla2Mentor; echo $cellpaddingTabla2Mentor; ?> >
								<tr>
									<td class="encabezado2Horizontal">CUENTA</td>
								</tr>
								<tr>
									<td>
										<input type="Text" name="Cuenta" style="width:420px;" onkeyup="frames.Busquedas.location.href='Busquedas.php?DatNameSID=<? echo $DatNameSID?>&Tipo=PlanCuentasGr&Cuenta='+this.value+'&Anio=<?echo $Anio?>'">
									</td>
								</tr>	
							</table>
							<?
							if(!$Formulario){
								$Formulario="FORMA";
							}
							?>
							<input type="Button" class="boton2Envio" value="Regresar" onclick="parent.document.<?echo $Formulario?>.<?echo $Campo?>.value=Cuenta.value;CerrarThis();">
						</form>
						<?	
					}
					
					
					
					
					if($Tipo=="Comprobante"){
						?>
						<form name="FORMA">
							<table class="tabla2"  <?php echo $borderTabla2Mentor; echo $bordercolorTabla2Mentor; echo $cellspacingTabla2Mentor; echo $cellpaddingTabla2Mentor; ?>>
								<tr>
									<td class="encabezado2Horizontal"> CUENTA</td>
								</tr>
								<tr>
									<td>
										<input type="Text" name="Comprobante" style="width:420px;" onkeyup="frames.Busquedas.location.href='Busquedas.php?DatNameSID=<? echo $DatNameSID?>&Tipo=Comprobante&Comprobante='+this.value">
									</td>
								</tr>	
							</table>
							<input type="Button" class="boton2Envio" value="Regresar" onclick="opener.document.FORMA.<?echo $Campo?>.value=Comprobante.value;window.close();">
						</form>
						<?
					}
					
					
					
					
					if($Tipo=="CodigoExogena"){
						?>
						<form name="FORMA">
							<table class="tabla2"  <?php echo $borderTabla2Mentor; echo $bordercolorTabla2Mentor; echo $cellspacingTabla2Mentor; echo $cellpaddingTabla2Mentor; ?>>
								<tr>
									<td class="encabezado2Horizontal">CUENTA</td>
								</tr>
								<tr>
									<td><input type="Text" name="Codigo" style="width:420px;" onkeyup="frames.Busquedas.location.href='Busquedas.php?DatNameSID=<? echo $DatNameSID?>&Tipo=CodigoExogena&Codigo='+this.value"></td>
								</tr>
							</table>
							<input type="Button" class="boton2Envio" value="Regresar" onclick="parent.document.FORMA.<?echo $Campo?>.value=Codigo.value;CerrarThis();">
						</form>
						<?	
					}
					
					
					
			?>

				<iframe width="100%" style="height:200px" name="Busquedas" id="Busquedas" src="Busquedas.php?DatNameSID=<? echo $DatNameSID?>&" frameborder="0"></iframe> 
		</div>
	</body>