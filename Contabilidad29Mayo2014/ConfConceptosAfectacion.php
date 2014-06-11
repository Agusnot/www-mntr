<?
	if($DatNameSID){session_name("$DatNameSID");}
	session_start();
	include("Funciones.php");
	if($Eliminar)
	{
		$cons = "Delete from Contabilidad.ConceptosAfectacion where Compania = '$Compania[0]' and Comprobante = '$Comprobante' and Concepto = '$Concepto'";
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
		?>
			<td colspan="6" align="right"><input type="button" name="Nuevo" value="Nuevo Registro" 
    								onclick="location.href='NuevoConfConcAfectac.php?DatNameSID=<? echo $DatNameSID?>&Anio=<? echo $Anio?>'" /></td></tr>
			<tr bgcolor="#e5e5e5" align="center" style="font-weight:bold">
    			<td>Comprobante</td><td>Concepto</td><td>Cuenta Destino</td><td>Cuenta Base</td><td>Operacion</td><td colspan="2">&nbsp;</td>
    		</tr>
    	<?
    		$cons = "Select Comprobante,Concepto,Cuenta,CuentaBase,Opera from Contabilidad.ConceptosAfectacion where Compania='$Compania[0]'
					and Anio = '$Anio' Order by Comprobante";
			$res = ExQuery($cons);
			while($fila = ExFetch($res))
			{
				?><tr onMouseOver="this.bgColor='#AAD4FF'" onMouseOut="this.bgColor=''"><?
				echo "<td>$fila[0]</td><td>$fila[1]</td><td align='right'>$fila[2]</td><td align='right'>$fila[3]</td><td align='center'>$fila[4]</td>";
				?><td width="16px">
               	<a href="NuevoConfConcAfectac.php?DatNameSID=<? echo $DatNameSID?>&Editar=1&Comprobante=<? echo $fila[0]?>&Concepto=<? echo $fila[1]?>&Anio=<? echo $Anio?>">
               	<img border=0 src="/Imgs/b_edit.png"></a></td>
				<td width="16px"><a href="#" onClick="if(confirm('Desea eliminar el registro?'))
                {location.href='ConfConceptosAfectacion.php?DatNameSID=<? echo $DatNameSID?>&Eliminar=1&Comprobante=<? echo $fila[0]?>&Concepto=<? echo $fila[1]?>';}">
				<img border="0" src="/Imgs/b_drop.png"/></a></td></tr><?
			}
		?></table><?
	}
?>
<input type="hidden" name="DatNameSID" value="<? echo $DatNameSID?>">
</form>
</body>