		<?php
			if($DatNameSID){session_name("$DatNameSID");}
			session_start();
			include("Funciones.php");
			include_once("General/Configuracion/Configuracion.php");
			$ND=getdate();
			//echo $DatNameSID;
			if($Guardar){
				$cons="insert into salud.formatosegreso (compania,ambito,tipoformato,formato,fechacrea,usucrea) values
				('$Compania[0]','$Ambito','$TipoFormato','$Formato','$ND[year]-$ND[mon]-$ND[mday] $ND[hours]:$ND[minutes]:$ND[seconds]','$usuario[1]')";	
				$res=ExQuery($cons);?>
				<script language="javascript">
					location.href="FormatosxEgrxAmb.php?DatNameSID=<? echo $DatNameSID?>";
				</script>
		<?	}
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
					function Validar()
					{
						if(document.FORMA.TipoFormato.value==""){alert("Debe selecionar el tipo de formato!!!");return false;}
						if(document.FORMA.Formato.value==""){alert("Debe selecionar el formato!!!");return false;}
					}
				</script>
			</head>

		<body <?php echo $backgroundBodyMentor; ?>>
			<?php
				$rutaarchivo[0] = "HISTORIA CL&Iacute;NICA";
				$rutaarchivo[1] = "CONFIGURACI&Oacute;N";
				$rutaarchivo[2] = "FORMATOS EGRESO POR PROCESO";
				$rutaarchivo[3] = "NUEVO";
				mostrarRutaNavegacionEstatica($rutaarchivo);
					
			?>
			<div <?php echo $alignDiv2Mentor; ?> class="div2">
				<form name="FORMA" method="post" onSubmit="return Validar()">  
					<table class="tabla2"  <?php echo $borderTabla2Mentor; echo $bordercolorTabla2Mentor; echo $cellspacingTabla2Mentor; echo $cellpaddingTabla2Mentor; ?>> 
						<tr>
							<td colspan="2" class="encabezado2Horizontal">NUEVO FORMATO EGRESO</td>
						</tr>
						<tr>
							<td class="encabezado2VerticalInvertido">PROCESO</td>
							<td>
							<?	$cons="select Ambito from salud.ambitos where compania='$Compania[0]' and ambito!='Sin Ambito' order by ambito";
								$res=ExQuery($cons);?>
								<select name="Ambito">
								<?	while($fila=ExFetch($res))
									{
										if($fila[0]==$Ambito){echo "<option value='$fila[0]' selected>$fila[0]</option>";}
										else{echo "<option value='$fila[0]'>$fila[0]</option>";}
									}?>
								</select>
							</td>
						</tr>
						<tr>
							<td class="encabezado2VerticalInvertido">TIPO FORMATO</td>
							<td>
							<?	$cons="select tipoformato from historiaclinica.formatos where compania='$Compania[0]' 
								group by tipoformato order by tipoformato";
								$res=ExQuery($cons);?>
								<select name="TipoFormato" onchange="document.FORMA.submit()">
									<option></option>
								<?	while($fila=ExFetch($res))
									{
										if($fila[0]==$TipoFormato){echo "<option value='$fila[0]' selected>$fila[0]</option>";}
										else{echo "<option value='$fila[0]'>$fila[0]</option>";}
									}?>
								</select>
							</td>
						</tr>
						 <tr>
							<td class="encabezado2VerticalInvertido">FORMATO</td>
							<td>
							<?	$cons="select formato from historiaclinica.formatos where compania='$Compania[0]' and tipoformato='$TipoFormato'
								and formato not in (select formato from salud.formatosegreso where compania='$Compania[0]' and ambito='$Ambito' and tipoformato='$TipoFormato')
								group by formato order by formato";
								$res=ExQuery($cons);?>
								<select name="Formato" onchange="document.FORMA.submit()">
									<option></option>
								<?	while($fila=ExFetch($res))
									{
										if($fila[0]==$Formato){echo "<option value='$fila[0]' selected>$fila[0]</option>";}
										else{echo "<option value='$fila[0]'>$fila[0]</option>";}
									}?>
								</select>
							</td>
						</tr>
						<tr>
							<td colspan="2" style="text-align:center;">
								<input type="submit" name="Guardar" class="boton2Envio" value="Guardar" />
								<input type="button"  class="boton2Envio" value="Cancelar" onclick="location.href='FormatosxEgrxAmb.php?DatNameSID=<? echo $DatNameSID?>'"/>
							</td>
						</tr>
					</table>
					<input type="hidden" name="DatNameSID" value="<? echo $DatNameSID?>" />
				</form>  
			</div>	
		</body>
	</html>