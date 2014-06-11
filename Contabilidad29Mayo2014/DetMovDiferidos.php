<?
	if($DatNameSID){session_name("$DatNameSID");}
session_start();
include("Funciones.php");
?>
<body background="/Imgs/Fondo.jpg">

<?
	$cons="Select Concepto,Tercero,CtaCredito,SaldoIni,NoCuotas,VrDiferidoMensual,Id,Fecha from Contabilidad.ProgDiferidos where Compania='$Compania[0]' And Id=$Id";
	$res=ExQuery($cons);
	$fila=ExFetch($res);

	$cons2="Select PrimApe,SegApe,PrimNom,SegNom from Central.Terceros where Identificacion='$fila[1]'";
	$res2=ExQuery($cons2);
	$fila2=ExFetch($res2);
	$NomTerc="$fila2[0] $fila2[1] $fila2[2] $fila2[3]";
?>

<table border="0" width="100%" cellpadding="3" style="font-family:<?echo $Estilo[8]?>;font-size:11px;font-style:<?echo $Estilo[10]?>">
<tr><td><strong>Concepto:</td><td><? echo $fila[0]?></td></tr>
<tr><td><strong>Tercero:</td><td><? echo $fila[1]?> <?echo $NomTerc?></td></tr>
<tr><td><strong>Valor Total:</td><td><? echo number_format($fila[3],2)?></td></tr>
</table>

<table width="100%" border="0" cellpadding="3" style="font-family:<?echo $Estilo[8]?>;font-size:11px;font-style:<?echo $Estilo[10]?>">
<tr style="color:<?echo $Estilo[6]?>;font-weight:bold" bgcolor="<?echo $Estilo[1]?>" align="center">
<td>No</td><td>Fecha</td><td>Comprobante</td><td>Numero</td><td>Vr Diferido</td><td>Saldo</td></tr>
<?
	$cons1="Select FechaEjec,Comprobante,Numero,VrDiferido from Contabilidad.ProgDiferidosEjec where Id=$Id and Compania='$Compania[0]'";
	$res1=ExQuery($cons1);
	$SaldoAct=$fila[3];
	while($fila1=ExFetch($res1))
	{
		$SaldoAct=$SaldoAct-$fila1[3];
		$AutoId++;
		echo "<tr><td>$AutoId</td><td>$fila1[0]</td><td>$fila1[1]</td><td align='right'>$fila1[2]</td><td align='right'>".number_format($fila1[3],2)."</td><td align='right'>".number_format($SaldoAct,2)."</td></tr>";
	}
	
?>
</table>
</body>