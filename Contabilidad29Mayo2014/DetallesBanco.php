<?
	if($DatNameSID){session_name("$DatNameSID");}
	session_start();
	include("Funciones.php");
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
<head><title>Compuconta Software</title></head>
<body background="/Imgs/Fondo.jpg">
<form name="FORMA">
<center>
<table border="1" cellpadding="6" bordercolor="<?echo $Estilo[1]?>" style="font-family:<?echo $Estilo[8]?>;font-size:<?echo $Estilo[9]?>;font-style:<?echo $Estilo[10]?>">
<tr><td>Nombre Entidad Bancaria</td>
<td>
<select name="Nombre">
<option>
<?
	$cons="Select Nombre from Central.EntidadesBancarias";
	$res=ExQuery($cons);
	while($fila=ExFetch($res))
	{
		if($NomBanco==$fila[0]){echo "<option selected value='$fila[0]'>$fila[0]</option>";}
		else{echo "<option value='$fila[0]'>$fila[0]</option>";}
	}
?>
</select></td>
</tr>
<tr><td>Numero de Cuenta</td><td><input type="Text" name="NumCuenta"  value="<?echo $NumCuenta?>"></td></tr>
<tr><td>Destinaci&oacute;n de la Cuenta (SIA)</td><td>
<select name="Destinacion">
<option>
<?
	$cons="Select Destinacion from Contabilidad.DestinacionesCuenta where Compania='$Compania[0]'";
	$res=ExQuery($cons);echo ExError($res);
	while($fila=ExFetch($res))
	{
		if($Destinacion==$fila[0]){echo "<option selected value='$fila[0]'>$fila[0]</option>";}
		else{echo "<option value='$fila[0]'>$fila[0]</option>";}
	}
?>
</select></td>
</td></tr>
<tr><td>Fuente de Financiacion (SIA)</td>
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
<br><input type="Submit" name="Guardar" value="Guardar y Regresar">
<input type="button" value="Cerrar" onClick="CerrarThis()">
<input type="Hidden" name="Anio" value="<?echo $Anio?>">
<input type="Hidden" name="Cuenta" value="<?echo $Cuenta?>">
<input type="hidden" name="DatNameSID" value="<? echo $DatNameSID?>">

</form>
</body>