		<?	if($DatNameSID){session_name("$DatNameSID");}
			session_start();
			include("Funciones.php");
			include_once("General/Configuracion/Configuracion.php");
		?>
		
		
		<html>
			
			<head>	
				<?php echo $codificacionMentor; ?>
				<?php echo $autorMentor; ?>
				<?php echo $titleMentor; ?>
				<?php echo $iconMentor; ?>
				<?php echo $shortcutIconMentor; ?>
				<link rel="stylesheet" type="text/css" href="../../General/Estilos/estilos.css">
				
				<script language="javascript" src="/Funciones.js"></script>
				<script language="javascript"> 
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
				
				<body <?php echo $backgroundBodyMentor; ?> onLoad="document.FORMA.Codigo.focus()">
					<div <?php echo $alignDiv2Mentor; ?> class="div2">
						<input type="button" value=" X " onClick="CerrarThis()" style="position:absolute;top:1px;right:1px;" title="Cerrar esta ventana">
						<form name="FORMA" method="post">
							<table class="tabla2"  <?php echo $borderTabla2Mentor; echo $bordercolorTabla2Mentor; echo $cellspacingTabla2Mentor; echo $cellpaddingTabla2Mentor; ?>>    	
								<tr>
									<td class="encabezado2Horizontal">C&Oacute;DIGO</td>
									<td class="encabezado2Horizontal">PROCEDIMIENTO</td>
								</tr>
								<tr>
									<td>
										<input type="text" name="Codigo" style="width:100" onkeyup="xLetra(this);frames.BusqProcedimientos.location.href='BusqProcedimientos.php?DatNameSID=<? echo $DatNameSID?>&Cargo=<? echo $Cargo?>&Item=<? echo $Item?>&Valor=<? echo $Valor?>&Formato=<? echo $Formato?>&TipoFormato=<? echo $TipoFormato?>&Codigo='+this.value+'&Nombre='+FORMA.Nombre.value;" onKeyDown="xLetra(this)"/></td>
									<td>
										<input type="text" name="Nombre" style="width:430"	onkeyup="xLetra(this);frames.BusqProcedimientos.location.href='BusqProcedimientos.php?DatNameSID=<? echo $DatNameSID?>&Cargo=<? echo $Cargo?>&Item=<? echo $Item?>&Valor=<? echo $Valor?>&Formato=<? echo $Formato?>&TipoFormato=<? echo $TipoFormato?>&Codigo='+FORMA.Codigo.value+'&Nombre='+this.value;"	onFocus="frames.BusqProcedimientos.location.href='BusqProcedimientos.php?DatNameSID=<? echo $DatNameSID?>&Cargo=<? echo $Cargo?>&Item=<? echo $Item?>&Valor=<? echo $Valor?>&Formato=<? echo $Formato?>&TipoFormato=<? echo $TipoFormato?>&Codigo='+FORMA.Codigo.value+'&Nombre='+this.value;" onKeyDown="xLetra(this)" /></td>
								</tr>
							</table>
							<input type="hidden" value="DatNameSID" name="<? echo $DatNameSID?>">
						</form>
						<iframe id="BusqProcedimientos" name="BusqProcedimientos" frameborder="0" width="100%" height="85%" src="BusqProcedimientos.php?DatNameSID=<? echo $DatNameSID?>&Cargo=<? echo $Cargo?>&Item=<? echo $Item?>&Valor<? echo $Valor?>&Formato=<? echo $Formato?>&TipoFormato=<? echo $TipoFormato?>"></iframe>
					</div>	
				</body>
		</html>		