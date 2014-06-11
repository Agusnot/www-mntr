<?
	if($DatNameSID){session_name("$DatNameSID");}
	session_start();
	include("Funciones.php");
	if($Eliminar)
	{
		$cons = "Delete from Contabilidad.CruzarComprobantes where Compania = '$Compania[0]' and Comprobante = '$Comprobante' and CruzarCon = '$CruzarCon'";
		$res = ExQuery($cons);
		echo ExError();
	}
	if(!$Anio)
	{
		$ND = getdate();
		$Anio = $ND[year];
	}
?>
<body background="/Imgs/Fondo.jpg">
<table cellpadding="4"  border="1" bordercolor="<?echo $Estilo[1]?>" 
style="font-family:<?echo $Estilo[8]?>;font-size:12px;font-style:<?echo $Estilo[10]?>" width="100%">
<tr bgcolor="#e5e5e5"><td>
<form name="FORMA" method="post">
A&ntilde;o: 
<select name="Anio" onChange="FORMA.submit()">
<?
	$cons = "Select Anio from Central.Anios where Compania='$Compania[0]' order by Anio desc";
	$res = ExQuery($cons);
	while($fila = ExFetch($res))
	{
		if($Anio == $fila[0]){echo "<option selected value='$fila[0]'>$fila[0]</option>";}
		else {echo "<option value='$fila[0]'>$fila[0]</option>";}
	}
?>
</select>
</td>
<?
	if($Anio)
	{
		?><td align="right" colspan="6"><input type="button" name="Nuevo" value="Nuevo Registro" 
    								onclick="location.href='NuevoConfCruceComp.php?DatNameSID=<? echo $DatNameSID?>&Anio=<? echo $Anio?>'" /></td></tr>
			<tr bgcolor="#e5e5e5" align="center" style="font-weight:bold">
    			<td>Comprobante</td><td>Cruzar Con</td><td>Movimiento</td><td>Cuenta</td><td>Cuenta a Cruzar</td><td colspan="2">&nbsp;</td>
    		</tr>
    	<?
    		$cons = "Select Comprobante,CruzarCon,Movimiento,Cuenta,CuentaCruzar from Contabilidad.CruzarComprobantes where Compania='$Compania[0]'
					and Anio = '$Anio' Order by Comprobante";
			$res = ExQuery($cons);
			while($fila = ExFetch($res))
			{
				?><tr onMouseOver="this.bgColor='#AAD4FF'" onMouseOut="this.bgColor='#FFFFFF'"><?
				echo "<td>$fila[0]</td><td>$fila[1]</td><td>$fila[2]</td><td align='right'>$fila[3]</td><td align='right'>$fila[4]</td>";
				?><td width="16px">
               	<a href="NuevoConfCruceComp.php?DatNameSID=<? echo $DatNameSID?>&Anio=<? echo $Anio?>&Editar=1&Comprobante=<? echo $fila[0]?>&CruzarCon=<? echo $fila[1]?>&Movimiento=<? echo $fila[2]?>&Cuenta=<? echo $fila[3]?>&CuentaCruzar=<? echo $fila[4]?>">
               	<img border=0 src="/Imgs/b_edit.png"></a></td>
				<td width="16px"><a href="#" onClick="if(confirm('Desea eliminar el registro?'))
                {location.href='ConfCruceComprobantes.php?DatNameSID=<? echo $DatNameSID?>&Eliminar=1&Comprobante=<? echo $fila[0]?>&CruzarCon=<? echo $fila[1]?>';}">
				<img border="0" src="/Imgs/b_drop.png"/></a></td></tr><?
			}
		?></table><?
	}
?>
<input type="hidden" name="DatNameSID" value="<? echo $DatNameSID?>">
</form>
</body>