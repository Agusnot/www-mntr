<?
	if($DatNameSID){session_name("$DatNameSID");}
	session_start();
	include("Funciones.php");
	$ND=getdate();
	if($Eliminar)
	{
		$cons = "Delete from Contabilidad.BasesRetencion where Concepto = '$Concepto' and TipoRetencion = '$TipoRetencion' and Anio = '$Anio'
		and Compania = '$Compania[0]'";
		$res = ExQuery($cons);ExError();
	}
	if($Nuevo)
	{
		?><script language="javascript">location.href="NuevoConfBDR.php?DatNameSID=<? echo $DatNameSID?>&Anio=<? echo $Anio ?>"</script><?
	}
	if(!$Anio){$Anio=$ND[year];}
?>
<body background="/Imgs/Fondo.jpg">
		<table cellpadding="4"  border="1" bordercolor="<?echo $Estilo[1]?>" style="font-family:<?echo $Estilo[8]?>;font-size:12px;font-style:<?echo $Estilo[10]?>"
    	width="100%">

<tr bgcolor="#e5e5e5"><td colspan="4">
<form name="FORMA" method="post">
	A&ntilde;o: 
    <select name="Anio" onChange="FORMA.submit()">
<?
	$cons="Select Anio from Central.Anios where Compania='$Compania[0]' Order By Anio";
	$res=ExQuery($cons);
	while($fila=ExFetch($res))
	{
		if($Anio==$fila[0]){echo "<option selected value='$fila[0]'>$fila[0]</option>";}
		else{echo "<option value='$fila[0]'>$fila[0]</option>";}
	}
?>
    </select>
</td>
    <?
    if($Anio)
	{ ?>
    		<td colspan="4" align="right"><input type="submit" name="Nuevo" value="Nuevo Registro" /></td></tr>
    		<tr align="center" style="font-weight:bold" bgcolor="#e5e5e5">
    			<td>Concepto</td><td>Porc</td><td>Base</td><td>Cuenta</td><td>Monto Minimo</td><td>IVA</td><td colspan="2">&nbsp;</td>
    		</tr>
    		<?
    			$cons = "Select TipoRetencion, Count(*) from Contabilidad.BasesRetencion where Compania = '$Compania[0]' and Anio = '$Anio' group by TipoRetencion";
				$res = ExQuery($cons);
				while($fila = ExFetch($res))
				{
					echo "<tr><td colspan=8 style='color:white' bgcolor='$Estilo[1]'><strong><center>$fila[0] ($fila[1])</td></tr>";
					$cons1 = "Select Concepto,Porcentaje,Base,Cuenta,MontoMinimo,Iva from Contabilidad.BasesRetencion where Compania = '$Compania[0]' and Anio = '$Anio'
					and TipoRetencion = '$fila[0]'";
					$res1 = ExQuery($cons1);
					while($fila1 = ExFetch($res1))
					{
						?><tr onMouseOver="this.bgColor='#AAD4FF'" onMouseOut="this.bgColor='#FFFFFF'" align="right"><?
						echo "<td align='left'>$fila1[0]</td><td>".number_format($fila1[1],2)."</td><td>$fila1[2]</td><td>$fila1[3]</td><td>".number_format($fila1[4],2)."</td><td>".number_format($fila1[5],2)."</td>";
						?><td width="16px">
                		<a href="NuevoConfBDR.php?DatNameSID=<? echo $DatNameSID?>&Editar=1&TipoRetencion=<? echo $fila[0]?>&Concepto=<? echo $fila1[0]?>&Anio=<? echo $Anio?> ">
                		<img border=0 src="/Imgs/b_edit.png"></a></td>
						<td width="16px"><a href="#" onClick="if(confirm('Desea eliminar el registro?'))
                		{location.href='ConfBDRetencion.php?DatNameSID=<? echo $DatNameSID?>&Eliminar=1&TipoRetencion=<? echo $fila[0]?>&Concepto=<? echo $fila1[0]?>&Anio=<? echo $Anio?> ';}">
							<img border="0" src="/Imgs/b_drop.png"/></a>
							</td></tr><?
					}
				}
		?></table> <?
	} ?>
    <input type="hidden" name="DatNameSID" value="<? echo $DatNameSID?>">
</form>
</body>