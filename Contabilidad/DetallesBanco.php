<?
	if($DatNameSID){session_name("$DatNameSID");}
	session_start();
	include("Funciones.php");
	include_once("General/Configuracion/Configuracion.php");
?>
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
<?
	if($Guardar)
	{
		if(!$Fuente){$Fuente="NULL";}else{$Fuente="'$Fuente'";}
		if(!$Destinacion){$Destinacion="NULL";}else{$Destinacion="'$Destinacion'";}
		$cons="Update Contabilidad.PlanCuentas set NomBanco='$Nombre',NumCuenta='$NumCuenta',Destinacion=$Destinacion,FteFinanciacion=$Fuente where Cuenta='$Cuenta' and Compania='$Compania[0]' and Anio='$Anio'";
		$res=ExQuery($cons);
		echo ExError($res);
		?>
		<script language="JavaScript">
			CerrarThis();
		</script>
		<?
	}

	$cons="Select NomBanco,NumCuenta,Destinacion,FteFinanciacion from Contabilidad.PlanCuentas where Compania='$Compania[0]' and Anio='$Anio' and Cuenta='$Cuenta'";
	$res=ExQuery($cons);echo ExError($res);
	$fila=ExFetch($res);
	$NomBanco=$fila[0];$NumCuenta=$fila[1];$Destinacion=$fila[2];$FteFinanc=$fila[3];
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
			<body>
				<div align="center">
					<form name="FORMA">			
						<table class="tabla1"  <?php echo $borderTabla1Mentor ; echo $bordercolorTabla1Mentor ; echo $cellspacingTabla1Mentor ; echo $cellpaddingTabla1Mentor; ?>>
							<tr>
								<td class="encabezado2Horizontal" colspan="2" >
									DETALLES ENTIDAD BANCARIA
								</td>
							</tr>	
							<tr>
								<td class="encabezado2VerticalInv" >NOMBRE ENTIDAD BANCARIA</td>
								<td>
									<select name="Nombre">
										<option value=""> &nbsp; </option>
										<?
											$cons="Select Nombre from Central.EntidadesBancarias";
											$res=ExQuery($cons);
											while($fila=ExFetch($res))
											{
												if($NomBanco==$fila[0]){echo "<option selected value='$fila[0]'>$fila[0]</option>";}
												else{echo "<option value='$fila[0]'>$fila[0]</option>";}
											}
										?>
									</select>
								</td>
						</tr>
						<tr>
							<td class="encabezado2VerticalInv" >N&Uacute;MERO DE CUENTA</td>
							<td><input type="Text" name="NumCuenta"  value="<?echo $NumCuenta?>"></td>
						</tr>
						<tr>
							<td class="encabezado2VerticalInv"> DESTINACI&Oacute;N DE LA CUENTA  (SIA)</td><td>
								<select name="Destinacion">
									<option value=""> &nbsp; </option>
									<?
										$cons="Select Destinacion from Contabilidad.DestinacionesCuenta where Compania='$Compania[0]'";
										$res=ExQuery($cons);echo ExError($res);
										while($fila=ExFetch($res))
										{
											if($Destinacion==$fila[0]){echo "<option selected value='$fila[0]'>$fila[0]</option>";}
											else{echo "<option value='$fila[0]'>$fila[0]</option>";}
										}
									?>
								</select>
							</td>
						</tr>
						<tr>
							<td class="encabezado2VerticalInv">FUENTE DE FINANCIACI&Oacute;N (SIA)</td>
						<td>
						<select name="Fuente">
						<option>
						<?
							$cons="Select FteFinanciacion from Contabilidad.FuentesFinanciacion where Compania='$Compania[0]'";
							$res=ExQuery($cons);
							while($fila=ExFetch($res))
							{
								if($FteFinanc==$fila[0]){echo "<option selected value='$fila[0]'>$fila[0]</option>";}
								else{echo "<option value='$fila[0]'>$fila[0]</option>";}
							}
						?>
						</select></td>
						</tr>
						</table>
						<br><input type="Submit" class="boton2Envio" name="Guardar" value="Guardar y Regresar">
						<input type="button" class="boton2Envio" value="Cerrar" onClick="CerrarThis()">
						<input type="Hidden" name="Anio" value="<?echo $Anio?>">
						<input type="Hidden" name="Cuenta" value="<?echo $Cuenta?>">
						<input type="hidden" name="DatNameSID" value="<? echo $DatNameSID?>">

					</form>
				</div>	
			</body>
	</html>		