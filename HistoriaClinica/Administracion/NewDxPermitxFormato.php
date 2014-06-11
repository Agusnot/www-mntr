		<?	if($DatNameSID){session_name("$DatNameSID");}
			session_start();
			include("Funciones.php");
			include_once("General/Configuracion/Configuracion.php");
			if($Guardar)
			{
			}
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
						parent.document.getElementById('FrameOpener2').style.position='absolute';
						parent.document.getElementById('FrameOpener2').style.top='1px';
						parent.document.getElementById('FrameOpener2').style.left='1px';
						parent.document.getElementById('FrameOpener2').style.width='1';
						parent.document.getElementById('FrameOpener2').style.height='1';
						parent.document.getElementById('FrameOpener2').style.display='none';
					}
				</script>
			</head>
			<body <?php echo $backgroundBodyMentor; ?>>
				<div align="center">
					<form name="FORMA" method="post" onSubmit="return Validar()">

						<input type="button" value=" X " onClick="CerrarThis()" style="position:absolute;top:1px;right:1px;" title="Cerrar esta ventana">
						<table class="tabla2" width="95%"  <?php echo $borderTabla2Mentor; echo $bordercolorTabla2Mentor; echo $cellspacingTabla2Mentor; echo $cellpaddingTabla2Mentor; ?>>
							<tr> 
								<td class="encabezado2Horizontal">C&Oacute;DIGO</td>
								<td class="encabezado2Horizontal">NOMBRE</td>
							</tr>
							<tr>
								<td>
									<input type="text" name="Codigo" onKeyDown="xLetra(this)" onKeyPress="xLetra(this)" onKeyUp="xLetra(this);frames.BusqDxRestric.location.href='BusqDxRestric.php?DatNameSID=<? echo $DatNameSID?>&TF=<? echo $TF?>&Formato=<? echo $NewFormato?>&CodCup=<? echo $CodCup?>&Codigo='+FORMA.Codigo.value+'&Nombre='+FORMA.Nombre.value;" 
									style="width:90">
								</td>
								<td width="100%">
									<input type="text" name="Nombre" onKeyDown="xLetra(this)" onKeyPress="xLetra(this)" onKeyUp="xLetra(this);frames.BusqDxRestric.location.href='BusqDxRestric.php?DatNameSID=<? echo $DatNameSID?>&TF=<? echo $TF?>&Formato=<? echo $NewFormato?>&CodCup=<? echo $CodCup?>&Codigo='+FORMA.Codigo.value+'&Nombre='+FORMA.Nombre.value;" 
									style="width:100%">
								</td>
							</tr>
						</table>
						<input type="hidden" name="DatNameSID" value="<? echo $DatNameSID?>">
						<input type="hidden" name="CodCup" value="<? echo $CodCup?>">
						<input type="hidden" name="TF" value="<? echo $TF?>">
						<input type="hidden" name="NewFormato" value="<? echo $NewFormato?>">
					</form>
					<iframe id="BusqProcedimientos" name="BusqDxRestric" frameborder="0" width="100%" height="85%" src="BusqDxRestric.php?DatNameSID=<? echo $DatNameSID?>&CodCup=<? echo $CodCup?>"></iframe>
				</div>	
			</body>
		</html>