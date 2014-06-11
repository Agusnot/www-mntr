<?	if($DatNameSID){session_name("$DatNameSID");}
	session_start();
include("Funciones.php");

	$cons="Select AutoId,Fecha,Comprobante,Numero,Identificacion,Detalle,Cuenta,Credito,ContraCredito,DocSoporte,'',Estado,UsuarioCre 
	from Presupuesto.Movimiento where Comprobante='$Comprobante' and Numero='$Numero' and Compania='$Compania[0]' and Estado='AC' Order By Credito Desc";
	$res=ExQuery($cons);
	$fila=ExFetchArray($res);
	$UsuarioCre=$fila[13];
?>
<title>Impresi&oacute;n de Comprobante</title>
<body background="/Imgs/Fondo.jpg">
<div style="font-family:<?echo $Estilo[8]?>;font-size:12;font-style:<?echo $Estilo[10]?>">
<?
	if($fila["Estado"]=="AN")
	{
		echo "<img src='/Imgs/Anulada.gif' style='position:absolute;left:70px;width:600px;'>";
	}
?>
<font style="font-family:<?echo $Estilo[8]?>;font-size:11;">
<strong>
<?
echo strtoupper($Compania[0])."<br></strong>";
echo "$Compania[1]<br>$Compania[2] - $Compania[3]<br>";
?>
</font>
</div>
<table rules="cols"  bordercolor="#ffffff" style="position:absolute;right:20px;top:20px;" border="1" style="font-family:<?echo $Estilo[8]?>;font-size:10;">
<tr bgcolor="#e5e5e5"><td><?echo strtoupper($Comprobante)?></td></tr>
<tr><td><font size="+1"><center><?echo $Numero?></td></tr>
</table>
<?
	
	$cons1="Select PrimApe,SegApe,PrimNom,SegNom,Identificacion,Direccion,Telefono from Contabilidad.Terceros where Identificacion='$fila[4]' and Terceros.Compania='$Compania[0]'";
	$res1=ExQuery($cons1);
	$fila1=ExFetch($res1);
?>
<br><br>
<center>
<table border="1" width="85%" bordercolor="#ffffff" style="font-family:<?echo $Estilo[8]?>;font-size:11;font-style:<?echo $Estilo[10]?>">
<tr><td><strong>Fecha</td><td><?echo $fila[1]?></td></tr>
<tr><td><strong>Tercero</td><td><?echo "$fila1[0] $fila1[1] $fila1[2] $fila1[3]"?></td><td><strong>Identificacion</td><td><?echo $fila1[4]?></td></tr>
<tr><td><strong>Direcci&oacute;n</td><td><?echo $fila1[5]?></td><td><strong>Telefono</td><td><?echo $fila1[6]?></td></tr>
<tr><td><strong>Detalle</td><td colspan="3"><?echo $fila['Detalle']?></td></tr>
</table>
<br><br>
</center>
<table border="1" bordercolor="#ffffff" width="100%" style="font-family:<?echo $Estilo[8]?>;font-size:11;font-style:<?echo $Estilo[10]?>">
<tr bgcolor="#e5e5e5" style="font-weight:bold"><td>Codigo</td><td>Credito</td><td>Contra Credito</td><td>Tercero</td><td>Doc</td><td>Detalle</td></tr>
<?
	$res=ExQuery($cons);
	while($fila=ExFetch($res))
	{
		echo "<tr><td>$fila[6]</td><td align='right'>".number_format($fila[7],2)."</td><td align='right'>".number_format($fila[8],2)."</td><td>$fila[4]</td><td>$fila[9]</td><td>$fila[10]</td></tr>";
		$TotDebe=$TotDebe+$fila[7];$TotHaber=$TotHaber+$fila[8];
	}
?>
<tr bgcolor="#e5e5e5" style="font-weight:bold"><td>SUMAS</td><td align="right"><?echo number_format($TotDebe,2)?></td><td align="right"><?echo number_format($TotHaber,2)?></td><td colspan="4"></td></tr>
</table>
<br><br>

<table border="1" bordercolor="#000000" cellspacing="1" width="100%" style="font : normal normal 12px Tahoma;">
<tr valign="top"><td>Ordenado Por</td><td>Revisado Por</td><td>Elaborado Por<br><br><em><?echo $UsuarioCre?></em></td><td>Vo. Bo.</td><td>Firma y Sello Beneficiario<br><br><font style="font-size:8px;">C.C. o NIT</td></tr>
</table>
