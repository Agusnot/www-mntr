<?
	if($DatNameSID){session_name("$DatNameSID");}
	session_start();
	include("Funciones.php");
	$ND=getdate();
	$Corte="$ND[year]-$ND[mon]-$ND[mday]";
	?>
    <body background="/Imgs/Fondo.jpg">
    <?

	$cons="Select Numero from Contabilidad.Movimiento where UsuarioCre='$usuario[0]' 
	and CierrexCajero='$Corte' AND Estado='AC' Group By Numero";
	$res=ExQuery($cons);
	if(ExNumRows($res)>0)
	{
		echo "<br><center><font size=4><em>Cierre ya efectuado para este dia!!!</em></font><br><br>";
	}
	else
	{
	if(!$Si)
	{
		echo "<br><br><br><center><font size=4><em>Desea hacer cierre de caja?. No podr&aacute; deshacer este procedimiento</em></font><br><br>";
		echo "<input type='Button' value='Iniciar' onclick=location.href='CierreCaja.php?DatNameSID=$DatNameSID&Si=1' style='width:100px;'>";
	}
	else{
	$consTip="Select Comprobante from Contabilidad.Comprobantes where Compania='$Compania[0]' and TipoComprobant='Ingreso'";
	$resTip=ExQuery($consTip);
	while($filaTip=ExFetch($resTip))
	{
		$cons="Update Contabilidad.Movimiento set CierrexCajero='$ND[year]-$ND[mon]-$ND[mday]' where Comprobante='$filaTip[0]' and UsuarioCre='$usuario[0]' 
		and CierrexCajero IS NULL AND Estado='AC'";
		$res=ExQuery($cons);
	}
?>
	<table width="90%" border="1" rules="groups" bordercolor="#ffffff" width="100%" style="font-family:<?echo $Estilo[8]?>;font-size:10;font-style:<?echo $Estilo[10]?>">
	<tr><td colspan="8"><center><strong><?echo strtoupper($Compania[0])?><br>
	<?echo $Compania[1]?><br>CIERRE DE CAJA</td></tr>
	<tr><td colspan="8" align="right">Fecha de Impresión <?echo "$ND[year]-$ND[mon]-$ND[mday] $ND[hours]:$ND[minutes]"?></td>
	</tr>
	</table>

	<table border="1" rules="groups" align="center" bordercolor="#ffffff" width="90%" style="font-family:<?echo $Estilo[8]?>;font-size:11;font-style:<?echo $Estilo[10]?>">
	<tr style='font-weight:bold' bgcolor="#e5e5e5"><td>FECHA CREACION</td><td>COMPROBANTE</td><td>NUMERO</td><td align="right">VALOR</td></tr>
<?
	$consTip="Select Comprobante from Contabilidad.Comprobantes where Compania='$Compania[0]' and TipoComprobant='Ingreso'";
	$resTip=ExQuery($consTip);
	while($filaTip=ExFetch($resTip))
	{
		$consCta="Select Cuenta from Contabilidad.PlanCuentas where Cuenta like '1105%' and Compania='$Compania[0]' and Tipo='Detalle' and Anio=$ND[year]";
		$resCta=ExQuery($consCta);
		while($filaCta=ExFetch($resCta))
		{
			$cons="Select Fecha,Numero,sum(Debe),FechaCre from Contabilidad.Movimiento where Comprobante='$filaTip[0]' and UsuarioCre='$usuario[0]' 
			and Cuenta='$filaCta[0]' and CierrexCajero='$Corte' AND Estado='AC' Group By Numero,FechaCre,Fecha Order By Numero";
			$res=ExQuery($cons);
			while($fila=ExFetch($res))
			{
				echo "<tr><td>$fila[3]</td><td>$filaTip[0]</td><td>$fila[1]</td><td align='right'>".number_format($fila[2],2)."</td></tr>";
				$Total=$Total+$fila[2];
			}
	}
	}
				echo "<tr bgcolor='#e5e5e5' style='font-weight:bold' align='right'><td colspan=3>TOTAL</td><td align='right'>".number_format($Total,2)."</td></tr>";

		?>
        </table><br /><br /><br /><center>
		____________________________________<br>
		<? echo $usuario[0];
	
	}	
}
?>