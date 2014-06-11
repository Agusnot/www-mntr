<?
	if($DatNameSID){session_name("$DatNameSID");}
	session_start();
	include("Funciones.php");
?>
<body background="/Imgs/Fondo.jpg">
<table style='font : normal normal small-caps 12px Tahoma;' border="1" bordercolor="#e5e5e5" width="100%">
<tr bgcolor="#e5e5e5"><td style="font-weight:bold" align="center" colspan="5">Ingresos Parciales agregados</td></tr>
<tr><td>Comprobante</td><td>Numero</td><td>Total Costo</td><td>IVA</td><td>Total</td></tr>
<?
	$cons="Select CompRemision,NoCompRemision from Consumo.EntradasxRemisiones 
	where Compania='$Compania[0]' and TMPCOD='$TMPCOD'";
	$res=ExQuery($cons);
	while($fila=ExFetch($res))
	{
		$cons1="Select sum(TotCosto),sum(VrIVA) from Consumo.Movimiento where Comprobante='$fila[0]' and Numero='$fila[1]' and Compania='$Compania[0]' and AlmacenPpal='$AlmacenPpal'";

		$res1=ExQuery($cons1);echo ExError();
		while($fila1=ExFetch($res1))
		{
			$Total=$fila1[0]+$fila1[1];
			echo "<tr><td>$fila[0]</td><td>$fila[1]</td><td align='right'>".number_format($fila1[0],2)."</td><td align='right'>".number_format($fila1[1],2)."</td><td align='right'>".number_format($Total,2)."</td></tr>";
		}
	}
?>
</table>

</body>