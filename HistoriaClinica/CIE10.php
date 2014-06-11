		<?
			if($DatNameSID){session_name("$DatNameSID");}
			session_start();
			include("Funciones.php");
			include_once("General/Configuracion/Configuracion.php");
			if(!$NxP){$NxP=25;}
		?>
		
	<html>
		<head>
			<?php echo $codificacionMentor; ?>
			<?php echo $autorMentor; ?>
			<?php echo $titleMentor; ?>
			<?php echo $iconMentor; ?>
			<?php echo $shortcutIconMentor; ?>
			<link rel="stylesheet" type="text/css" href="../General/Estilos/estilos.css">
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
					parent.document.FORMA.submit();
				}
			</script>
		</head>	
		
		<body <?php echo $backgroundBodyMentor; ?>>
			<?php
				$rutaarchivo[0] = "HISTORIA CL&Iacute;NICA";
				$rutaarchivo[1] = "CONFIGURACI&Oacute;N";
				$rutaarchivo[2] = "CIE 10";
				mostrarRutaNavegacionEstatica($rutaarchivo);
			?>
			<div <?php echo $alignDiv3Mentor; ?> class="div3">
				<form name="FORMA" method="post">
					<input type="hidden" name="DatNameSID" value="<? echo $DatNameSID?>">
					<table width="100%" class="tabla3"  <?php echo $borderTabla3Mentor; echo $bordercolorTabla3Mentor; echo $cellspacingTabla3Mentor; echo $cellpaddingTabla3Mentor; ?>>
						<tr>
							<td class="encabezado2Horizontal" colspan="4">LISTA DE DIAGN&Oacute;STICOS</td>      
						</tr>
						<tr>
							<td width="8%"class="encabezado2HorizontalInvertido">C&Oacute;DIGO</td>
							<td width="75%" class="encabezado2HorizontalInvertido">NOMBRE</td>
							<td width="6%" class="encabezado2HorizontalInvertido">CLASIFICACI&Oacute;N</td>
							<td width="7%" class="encabezado2HorizontalInvertido" style="font-size:11px;">NO. POR PAG.</td>      
						</tr>
						<tr>
							<td><input type="text" name="Codigo" style="width:100%" value="<? echo $Codigo?>"
							onkeyup="xLetra(this);frames.Busquedascups.location.href='BusquedaCIE.php?DatNameSID=<? echo $DatNameSID?>&Offset=0&Codigo='+this.value+'&Nombre='+Nombre.value+'&Clasificacion='+Clasificacion.value+'&NxP='+NxP.value"
							onKeyDown="xLetra(this)" maxlength="6" /></td>
							<td><input type="text" name="Nombre" style="width:100%" value="<? echo $Nombre?>"
							onkeyup="xLetra(this);frames.Busquedascups.location.href='BusquedaCIE.php?DatNameSID=<? echo $DatNameSID?>&Offset=0&Codigo='+Codigo.value+'&Nombre='+this.value+'&Clasificacion='+Clasificacion.value+'&NxP='+NxP.value"
							onKeyDown="xLetra(this)" /></td>       
							<td>
							<select name="Clasificacion" onChange="frames.Busquedascups.location.href='BusquedaCIE.php?DatNameSID=<? echo $DatNameSID?>&Offset=0&Codigo='+Codigo.value+'&Nombre='+Nombre.value+'&Clasificacion='+Clasificacion.value+'&NxP='+NxP.value">
								<option value="">Todos</option>
								<option value="Favoritos" <? if($Clasificaicon=="Favoritos"){?> selected<? }?>>Favoritos</option>
								<option value="No Favoritos" <? if($Clasificaicon=="No Favoritos"){?> selected<? }?>>No Favoritos</option>
							</select>
							</td>
							<td><input type="text" name="NxP" style="width:100%; text-align:center" value="<? echo $NxP?>"
							onkeyup="if(this.value==''){this.value=1;this.selected;}xNumero(this);frames.Busquedascups.location.href='BusquedaCIE.php?DatNameSID=<? echo $DatNameSID?>&Offset=0&Codigo='+document.FORMA.Codigo.value+'&Nombre='+Nombre.value+'&Clasificacion='+Clasificacion.value+'&NxP='+this.value"
							onKeyDown="if(this.value==''){this.value=1;this.selected;}xNumero(this)" maxlength="5" /></td>		
						</tr>
						</table>   
				</form>
				<iframe frameborder="0" id="Busquedascups" src="BusquedaCIE.php?DatNameSID=<? echo $DatNameSID?>" width="100%" height="85%"></iframe>
				<?
					?><script language="javascript">			
						frames.Busquedascups.location.href="BusquedaCIE.php?DatNameSID=<? echo $DatNameSID?>&Codigo=<? echo $Codigo?>&Nombre=<? echo $Nombre?>&NxP=<? echo $NxP?>&Offset=0";
					</script><?
					
				?>
			</div>	
		</body>
	</html>	