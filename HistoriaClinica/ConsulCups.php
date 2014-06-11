		<?
			if($DatNameSID){session_name("$DatNameSID");}
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
				<script language='javascript' src="/Funciones.js"></script>
			</head>

			<body <?php echo $backgroundBodyMentor; ?>>
			<?php
				$rutaarchivo[0] = "HISTORIA CL&Iacute;NICA";
				$rutaarchivo[1] = "UTILIDADES";
				$rutaarchivo[2] = "CONSULTA DE CUPS";
				mostrarRutaNavegacionEstatica($rutaarchivo);
			?>
			<div <?php echo $alignDiv2Mentor; ?> class="div2">	
				<form name="FORMA" method="post" onSubmit="return Validar()">
					<table class="tabla2" style="margin-top:25px;margin-bottom:25px;"    <?php echo $borderTabla2Mentor; echo $bordercolorTabla2Mentor; echo $cellspacingTabla2Mentor; echo $cellpaddingTabla2Mentor; ?>>	
						<tr>
							<td class="encabezado2Horizontal" colspan="4">CONSULTA DE CUPS</td>        
						</tr>
						<tr>
							<td class="encabezado2HorizontalInvertido">C&Oacute;DIGO</td>
							<td class="encabezado2HorizontalInvertido">NOMBRE</td>
							<td class="encabezado2HorizontalInvertido">GRUPO</td>
							<td class="encabezado2HorizontalInvertido">TIPO</td>
						</tr>
						<tr align="center">
							<td><input type="text" name="Codigo" onKeyDown="xLetra(this)" onKeyPress="xLetra(this)" onKeyUp="xLetra(this);frames.Busquedascups.location.href='BusqConsulCUPS.php?DatNameSID=<? echo $DatNameSID?>&Codigo='+this.value+'&Nombre='+Nombre.value+'&Tipo='+Tipo.value+'&Grupo='+Grupo.value" style="width:80" value="<? echo $Codigo?>">
							</td>
							<td><input type="text" name="Nombre" onKeyDown="xLetra(this)" onKeyPress="xLetra(this)" onKeyUp="xLetra(this);frames.Busquedascups.location.href='BusqConsulCUPS.php?DatNameSID=<? echo $DatNameSID?>&Codigo='+Codigo.value+'&Nombre='+this.value+'&Tipo='+Tipo.value+'&Grupo='+Grupo.value" style="width:460" value="<? echo $Nombre?>">
							</td>
							<td>
							<?	$cons="select codigo,grupo from contratacionsalud.gruposservicio where compania='$Compania[0]' order by grupo";
								$res=ExQuery($cons);?>
								<select name="Grupo" onChange="document.FORMA.submit()"><option></option>
								<?	while($fila=ExFetch($res)){
										if($Grupo==$fila[0]){
											echo "<option value='$fila[0]' selected>$fila[1]</option>";
										}
										else{
											echo "<option value='$fila[0]'>$fila[1]</option>";
										}
									}?>
								</select>
							</td>
								<td>
							<?	$cons="select codigo,tipo from contratacionsalud.tiposservicio where compania='$Compania[0]' order by tipo";
								$res=ExQuery($cons);?>
								<select name="Tipo" onChange="document.FORMA.submit()"><option></option>
								<?	while($fila=ExFetch($res)){
										if($Tipo==$fila[0]){
											echo "<option value='$fila[0]' selected>$fila[1]</option>";
										}
										else{
											echo "<option value='$fila[0]'>$fila[1]</option>";
										}
									}?>
								</select>
							</td>
						</tr>
					</table>
					<input type="hidden" name="DatNameSID" value="<? echo $DatNameSID?>">
				</form>
				<iframe frameborder="0" id="Busquedascups" src="BusqConsulCUPS.php?DatNameSID=<? echo $DatNameSID?>" width="100%" height="85%"></iframe>
				<?
					if($Codigo || $Nombre || $Grupo || $Tipo)
					{
						?><script language="javascript">
							frames.Busquedascups.location.href="BusqConsulCUPS.php?DatNameSID=<? echo $DatNameSID?>&Codigo=<? echo $Codigo?>&Nombre=<? echo $Nombre?>&Tipo=<? echo $Tipo?>&Grupo=<? echo $Grupo?>";
						</script><?
					}
				?>
			</div>
			</body>
		</html>
