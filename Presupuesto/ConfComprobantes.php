<?	if($DatNameSID){session_name("$DatNameSID");}
	session_start();
include("Funciones.php");
	if($Eliminar)
	{
		$cons="Select * from Presupuesto.Movimiento where Comprobante='$Comprobante' and Compania='$Compania[0]'";
		$res=ExQuery($cons);
		if(ExNumRows($res)>0)
		{
			echo "<br><em><font style='color:red;font-weight:bold'>Comprobante tiene movimiento, no es posible eliminar!!!<br><br></font>";		
		}
		else
		{
			$cons="Delete from Presupuesto.Comprobantes where Comprobante='$Comprobante' and Compania='$Compania[0]'";
			$res=ExQuery($cons);
			echo ExError($res);	
		}
		
	}
?>
	<table cellpadding="4"  border="1" bordercolor="<?echo $Estilo[1]?>" style="font-family:<?echo $Estilo[8]?>;font-size:12px;font-style:<?echo $Estilo[10]?>" width="100%">
	<tr bgcolor="#e5e5e5" style="font-weight:bold">
	<td>Comprobante</td>
	<td>No. Inicial</td>
	<td>Archivo</td>
    <td colspan="2">&nbsp;</td>		
	</tr>
<?
	$cons1 = "Select TipoComprobant,count(*) from Presupuesto.Comprobantes where Compania='$Compania[0]' Group by TipoComprobant";
	$res1 = ExQuery($cons1);echo ExError($res1);
	while ($fila1 = ExFetch($res1))
	{
		echo "<tr><td colspan=9 style='color:white' bgcolor='$Estilo[1]'><strong><center>$fila1[0] ($fila1[1])</td></tr>";
		$cons2 = "Select Comprobante,Numeroinicial,Archivo from Presupuesto.Comprobantes where Compania='$Compania[0]' and TipoComprobant = '$fila1[0]' order by TipoComprobant";
		$res2 = ExQuery($cons2);echo ExError($res2);
		while ($fila2 = ExFetch($res2))
		{?>
			<tr onMouseOver="this.bgColor='#AAD4FF'" onmouseout="this.bgColor='#FFFFFF'">
			<? echo "<td>$fila2[0]</td><td>$fila2[1]</td><td>$fila2[2]</td>";
			echo "<td><a href='NuevoConfComprobante.php?DatNameSID=$DatNameSID&Editar=1&Comprobante=$fila2[0]'><img border=0 src='/Imgs/b_edit.png'></a></td>";?>
			<td><a href="#" onclick="if(confirm('Desea eliminar el registro?')){location.href='ConfComprobantes.php?DatNameSID=<? echo $DatNameSID?>&Eliminar=1&Comprobante=<?echo $fila2[0]?>';}">
			<img border="0" src="/Imgs/b_drop.png"/></a>
			</td></tr>		
		<? 
		}
	}
					
?>
</table><br />
<input type="button" value="Nuevo Comprobante" onclick="location.href='NuevoConfComprobante.php?DatNameSID=<? echo $DatNameSID?>'"/>

