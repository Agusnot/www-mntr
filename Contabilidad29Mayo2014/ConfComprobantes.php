<?
	if($DatNameSID){session_name("$DatNameSID");}
	session_start();
	include("Funciones.php");
	if($Eliminar)
	{
		$cons="Select * from Contabilidad.Movimiento where Comprobante='$Comprobante' and Compania='$Compania[0]'";
		$res=ExQuery($cons);
		if(ExNumRows($res)>0)
		{
			echo "<br><em><font style='color:red;font-weight:bold'>Comprobante tiene movimiento, no es posible eliminar!!!<br><br></font>";		
		}
		else
		{
			$cons="Delete from Contabilidad.Comprobantes where Comprobante='$Comprobante' and Compania='$Compania[0]'";
			$res=ExQuery($cons);
			echo ExError($res);	
		}
		
	}
?>
<body background="/Imgs/Fondo.jpg">
	<table cellpadding="4"  border="1" bordercolor="<?echo $Estilo[1]?>" style="font-family:<?echo $Estilo[8]?>;font-size:12px;font-style:<?echo $Estilo[10]?>">
	<tr bgcolor="#e5e5e5" bgcolor="" style="font-weight:bold">
	<td>Comprobante</td>
	<td>Retenc</td>
	<td>Cta Cero</td>
	<td>Cierre</td>
	<td>No</td>
	<td>Formato</td>
	<td>Presupuesto</td>
	<td colspan="2">&nbsp;</td>
	</tr>
<?
	$cons1 = "Select TipoComprobant,count(*) from Contabilidad.Comprobantes where Compania='$Compania[0]' Group by TipoComprobant";
	$res1 = ExQuery($cons1);echo ExError($res1);
	while ($fila1 = ExFetch($res1))
	{
		echo "<tr><td colspan=9 style='color:white' bgcolor='#A8A6A7'><strong><center>$fila1[0] ($fila1[1])</td></tr>";
		$cons2 = "Select Comprobante,Retencion,CruceCtaCero,Cierre,NumeroInicial,Formato,CompPresupuesto,CompPresupuestoAdc from Contabilidad.Comprobantes where Compania='$Compania[0]' and TipoComprobant = '$fila1[0]'";
		$res2 = ExQuery($cons2);echo ExError($res2);
		while ($fila2 = ExFetch($res2))
		{
			if(!$fila2[4]){$fila2[4]="&nbsp;";}?>
			<tr onMouseOver="this.bgColor='#AAD4FF'" onMouseOut="this.bgColor='#FFFFFF'">
			<? echo "
			<td>$fila2[0]</td>
			<td>$fila2[1]</td>
			<td>&nbsp;$fila2[2]</td>
			<td>&nbsp;$fila2[3]</td>
			<td>&nbsp;$fila2[4]</td>
			<td>&nbsp;$fila2[5]</td>
			<td>&nbsp;$fila2[6] - $fila2[7]</td>";
			echo "
			<td><a href='NuevoConfComprobante.php?DatNameSID=$DatNameSID&Editar=1&Comprobante=$fila2[0]'><img border=0 src='/Imgs/b_edit.png'></a></td>";?>
			<td><a href="#" onClick="if(confirm('Desea eliminar el registro?')){location.href='ConfComprobantes.php?DatNameSID=<? echo $DatNameSID?>&Eliminar=1&Comprobante=<?echo $fila2[0]?>';}">
			<img border="0" src="/Imgs/b_drop.png"/></a>
			</td></tr>		
		<?}
	}
					
?>
</table><br />
<input type="button" value="Nuevo Registro" onClick="location.href='NuevoConfComprobante.php?DatNameSID=<? echo $DatNameSID?>'"/>

</body>