<?	if($DatNameSID){session_name("$DatNameSID");}
	session_start();
include("Funciones.php");
	$ND=getdate();

	if(!$DiaTrabajo){$DiaTrabajo=$ND[mday];}
	if(!$AnioTrabajo){$AnioTrabajo=$ND[year];}
	if(!$MesTrabajo){$MesTrabajo=$ND[mon];}
	$AnioI=$AnioTrabajo;
	$MesI=$MesTrabajo;
	$DiaI=$DiaTrabajo;
?>

<html>

<body>
<form name="FORMA" target="Abajo" action="ListaMovimiento.php">
<center>
<table border="1" bordercolor="#e5e5e5" cellpadding="4" style="font-family:<?echo $Estilo[8]?>;font-size:12;font-style:<?echo $Estilo[10]?>">
<tr bgcolor="#e5e5e5" style="font-weight:bold;text-align:center"><td><center>Visualizar Periodo</td><td>Comprobante</td><td colspan="2"></td></tr>
<tr>
<td>

<select name="AnioI" onChange="document.FORMA.submit()">
<?
	for($an=1995;$an<=2100;$an++){if($AnioI==$an){echo "<option value='$an' selected>$an</option>";}
		else{echo "<option value='$an'>$an</option>";}
	}
?>
</select>

<select name="MesI" onChange="document.FORMA.submit();">
<?
	$cons="Select Mes,Numero,NumDias from Central.Meses Order By Numero";
	$res=ExQuery($cons,$conex);
	while($fila=ExFetch($res))
	{
		if($MesI==$fila[1]){echo "<option value='$fila[1]' selected>$fila[0]</option>";$NumDias=$fila[2];}
		else{echo "<option value='$fila[1]'>$fila[0]</option>";}
	}
?>
</select>
</td>
<td>
<select name="Comprobante" onChange="document.FORMA.submit();">

<?
	$cons="SELECT Comprobante FROM 
	Presupuesto.Comprobantes,Presupuesto.TiposComprobante 
	WHERE Tipo=TipoComprobant and  TipoGr='$Tipo' and Compania='$Compania[0]' ORDER BY Comprobantes.Comprobante";
	$res=ExQuery($cons,$conex);echo ExError($conex);
	while($fila=ExFetch($res))
	{
		if(!$Comprobante){$Comprobante=$fila[0];}
		if($Comprobante==$fila[0]){echo "<option value='$fila[0]' selected>$fila[0]</option>";}
		else{echo "<option value='$fila[0]'>$fila[0]</option>";}
	}
	
?>
</select>

<?
	$cons = "Select Mes From Central.CierreXPeriodos Where Compania='$Compania[0]' and Modulo='Presupuesto' and Anio=$AnioI and Mes=$MesI";
	$res = ExQuery($cons);
	if(ExNumRows($res)>=1)
	{	$Disabled=" disabled ";
	}

?>
</td>

<td>
<input type="Button" name="Nuevo" <? echo $Disabled ?> value="Nuevo" onClick="parent.location.href='NuevoMovimiento.php?DatNameSID=<? echo $DatNameSID?>&Anio='+AnioI.value+'&Mes='+MesI.value+'&Comprobante='+Comprobante.value+'&Tipo=<?echo $Tipo?>&TipoVigencia='+document.FORMA.Vigencia.value+'&Vigencia='+document.FORMA.NMVig.value+'&ClaseVigencia='+document.FORMA.TMVig.value">
<input type="Hidden" name="Tipo" value="<?echo $Tipo?>">

</td>
</tr>
<tr><td colspan="3" align="center">
Vigencia <input type="Radio" name="Vigencia" value="Actual" checked onClick="document.FORMA.submit();NMVig.value='Actual';TMVig.value=''">
Reservas <input type="Radio" name="Vigencia" value="Reservas" onClick="document.FORMA.submit();NMVig.value='Anteriores';TMVig.value='Reservas'"">
C x P <input type="Radio" name="Vigencia" value="CxP" onClick="document.FORMA.submit();NMVig.value='Anteriores';TMVig.value='CxP'"">
</td></tr>
</table>
<input type="Hidden" name="NMVig" value="Actual">
<input type="Hidden" name="TMVig" value="">
<input type="hidden" name="DatNameSID" value="<? echo $DatNameSID?>">
</form>
<br>
<iframe id="Busquedas" src="/Contabilidad/Busquedas.php?DatNameSID=<? echo $DatNameSID?>" frameborder="0" style="visibility:hidden"></iframe>
</body>
</html>
