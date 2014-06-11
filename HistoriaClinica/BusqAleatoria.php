		<?
			if($DatNameSID){session_name("$DatNameSID");}
			session_start();
			include("Funciones.php");
			include_once("General/Configuracion/Configuracion.php");
			$ND=getdate();
		?>	
		
		
		<html>
			<head>
					<?php echo $codificacionMentor; ?>
					<?php echo $autorMentor; ?>
					<?php echo $titleMentor; ?>
					<?php echo $iconMentor; ?>
					<?php echo $shortcutIconMentor; ?>
					<link rel="stylesheet" type="text/css" href="../General/Estilos/estilos.css">
					<script language='javascript' src="/Funciones.js"></script>
					<script language='javascript' src="/calendario/popcalendar.js"></script>
					<script language="javascript">
						function Validar()
						{
							if(document.FORMA.Especialidad.value==""){alert("Debe seleccionar la especialidad!!!");return false;}
							if(document.FORMA.Formato.value==""){alert("Debe seleccionar el formato!!!");return false;}
							if(document.FORMA.Formato.value==""){alert("Debe seleccionar el formato!!!");return false;}
							if(document.FORMA.NoRegistros.value==""){alert("Debe digitar el numero de registros a desplegar!!!");return false;}
							if(document.FORMA.NoRegistros.value<0){alert("Debe el numero de registros debe ser mayor o igual a cero!!!");return false;}
							if(document.FORMA.FechaIni.value>document.FORMA.FechaFin.value){alert("La fecha final debe ser mayor a la fecha incial!!!");return false;}
						}
					</script>
					<style>
						a{color:blue;text-decoration:none;}
						a:hover{text-decoration:underline;}
					</style>
			</head>

		<body  <?php echo $backgroundBodyMentor; ?>>
				<?php
					$rutaarchivo[0] = "HISTORIA CL&Iacute;NICA";
					$rutaarchivo[1] = "UTILIDADES";
					$rutaarchivo[2] = "B&Uacute;SQUEDA ALEATORIA";
					mostrarRutaNavegacionEstatica($rutaarchivo);
				?>
			<div <?php echo $alignDiv2Mentor; ?> class="div2">
				<form name="FORMA" method="post" onSubmit="return Validar()">
					<table class="tabla2"  <?php echo $borderTabla2Mentor; echo $bordercolorTabla2Mentor; echo $cellspacingTabla2Mentor; echo $cellpaddingTabla2Mentor; ?>>	
						<tr>
							<td class="encabezado2Horizontal" colspan="11"> B&Uacute;SQUEDA ALEATORIA </td>
						</tr>
						<tr>
							<?	if(!$FechaIni){$FechaIni="$ND[year]-$ND[mon]-01";}?>
						<td class="encabezado2VerticalInvertido">DESDE</td>
						<td>
							<input type="text" name="FechaIni" value="<? echo $FechaIni?>" readonly onClick="popUpCalendar(this, FORMA.FechaIni, 'yyyy-mm-dd')" style="width:70">
						</td>
					<?	if($ND[mday]<10){$C="0";}
						if(!$FechaFin){$FechaFin="$ND[year]-$ND[mon]-$C$ND[mday]";}?>    
						<td class="encabezado2VerticalInvertido">HASTA</td>
						<td>
							<input type="text" name="FechaFin" value="<? echo $FechaFin?>" readonly onClick="popUpCalendar(this, FORMA.FechaFin, 'yyyy-mm-dd')" style="width:70">
						</td>
						<td class="encabezado2VerticalInvertido">ESPECIALIDAD</td>
						<td>
						<?	$cons="select especialidad from salud.especialidades where compania='$Compania[0]' order by especialidad";
							$res=ExQuery($cons);?>
							<select name="Especialidad" onChange="document.FORMA.submit()">
								<option></option>
							<?	while($fila=ExFetch($res))
								{
									if($fila[0]==$Especialidad){echo "<option value='$fila[0]' selected>$fila[0]</option>";}	
									else{echo "<option value='$fila[0]'>$fila[0]</option>";}	
								}?>
							</select>
						</td>
						<td class="encabezado2VerticalInvertido">FORMATO</td>
						<td>
						<?	$cons="select formato from historiaclinica.formatos where compania='$Compania[0]' and tipoformato='$Especialidad' and estado='AC'
							order by formato";
							$res=ExQuery($cons);?>   	
							<select name="Formato" onChange="document.FORMA.submit()">
								<option></option>
							<?	while($fila=ExFetch($res))
								{
									if($fila[0]==$Formato){echo "<option value='$fila[0]' selected>$fila[0]</option>";}	
									else{echo "<option value='$fila[0]'>$fila[0]</option>";}	
								}?>
							</select>
						</td>
						<td class="encabezado2VerticalInvertido">NO. REGISTROS</td>
						<td>
						<?	if(!$NoRegistros){$NoRegistros="5";}?>
							<input type="text" name="NoRegistros" onKeyDown="xNumero(this)" onKeyPress="xNumero(this)" onKeyUp="xNumero(this)" value="<? echo $NoRegistros?>"
							style="width:30"/>
						</td>
						<td>
							<input type="submit" value="Ver" name="Ver" class="boton2Envio"/>
						</td>
					</tr>    
					</table>
					<input type="hidden" name="DatNameSID" value="<? echo $DatNameSID?>">
				</form>
				<iframe frameborder="0" id="VerInforme"	src="ResultBusqAleatoria.php?DatNameSID=<? echo $DatNameSID?>&Formato=<? echo $Formato?>&NoRegistros=<? echo $NoRegistros?>&Especialidad=<? echo $Especialidad?>&Ver=<? echo $Ver?>&FechaIni=<? echo $FechaIni?>&FechaFin=<? echo $FechaFin?>'" width="100%" height="80%" scrolling="yes"></iframe>
			</div>
		</body>
		</html>