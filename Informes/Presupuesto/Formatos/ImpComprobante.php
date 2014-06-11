<?
	if($DatNameSID){session_name("$DatNameSID");}
	session_start();
	include("Funciones.php");

	$cons="Select AutoId,Fecha,Comprobante,Numero,Identificacion,Detalle,Cuenta,Credito,ContraCredito,DocSoporte,'',Estado,UsuarioCre,CompAfectado 
	from Presupuesto.Movimiento where Comprobante='$Comprobante' and Numero='$Numero' and Compania='$Compania[0]' and Vigencia='$Vigencia' and ClaseVigencia='$ClaseVigencia' Order By Cuenta Asc";
	$res=ExQuery($cons);echo ExError();
	$fila=ExFetchArray($res);
	$UsuarioCre=$fila[12];
	$Anio=substr($fila[1],0,4);
	$Fecha=$fila[1];
?>
<title>Impresión de Comprobante</title>
<div style="font-family:<?echo $Estilo[8]?>;font-size:12;font-style:<?echo $Estilo[10]?>">
<?
	if($fila["Estado"]=="AN")
	{
		echo "<img src='/Imgs/Anulado.gif' style='position:absolute;top:170px;left:140px'>";
	}

	$Numero=$Numero;

?>
<table border="0">
<tr><td>
<img src="/Imgs/Logo.jpg" style="width:100px;position:relative">
</td><td><strong>
<font style="font-family:<?echo $Estilo[8]?>;font-size:11;">
<?
echo strtoupper($Compania[0])."<br></strong>";
echo "$Compania[1]<br>$Compania[2] - $Compania[3]<br>";
?>
</table>

</div>
<table rules="cols"  bordercolor="#ffffff" style="position:absolute;right:20px;top:20px;" border="1" style="font-family:<?echo $Estilo[8]?>;font-size:10;">
<tr bgcolor="#e5e5e5"><td><?echo strtoupper($Comprobante)?></td></tr>
<tr><td><font size="+1"><center><?echo $Numero?></td></tr>
</table>
<?
	
	$cons1="Select PrimApe,SegApe,PrimNom,SegNom,Identificacion,Direccion,Telefono from Central.Terceros where Identificacion='$fila[4]' and Terceros.Compania='$Compania[0]'";
	$res1=ExQuery($cons1);
	$fila1=ExFetch($res1);
?>
<br><br>
<center>
<table border="1" width="85%" bordercolor="#ffffff" style="font-family:<?echo $Estilo[8]?>;font-size:11;font-style:<?echo $Estilo[10]?>">
<tr><td><strong>Fecha</td><td><?echo $fila[1]?></td></tr>
<tr><td><strong>Tercero</td><td><?echo "$fila1[0] $fila1[1] $fila1[2] $fila1[3]"?></td><td><strong>Identificaci&oacute;n</td><td><?echo $fila1[4]?></td></tr>
<tr><td><strong>Direcci&oacute;n</td><td><?echo $fila1[5]?></td><td><strong>Telefono</td><td><?echo $fila1[6]?></td></tr>
<tr><td><strong>Detalle</td><td colspan="3"><?echo $fila['detalle']?></td></tr>
</table>
<br><br>
</center>
			<table border="1" bordercolor="#ffffff" width="100%" style="font-family:<?echo $Estilo[8]?>;font-size:11;font-style:<?echo $Estilo[10]?>">
			<tr bgcolor="#e5e5e5" style="font-weight:bold"><td>Codigo</td><td>Nombre</td><td>Doc Afectado</td><td>No</td><td>Credito</td><td>Contra Credito</td></tr>
<?
	$res2=ExQuery($cons);
	while($fila2=ExFetchArray($res2))
	{
		$cons9="Select Nombre from Presupuesto.PlanCuentas where Cuenta='".$fila2['cuenta']."' and Anio=$Anio and Vigencia='$Vigencia' and ClaseVigencia='$ClaseVigencia'";

		$res9=ExQuery($cons9);
		$fila9=ExFetch($res9);
		$NomCuenta=$fila9[0];
			
		echo "<tr><td>".$fila2['cuenta']."</td><td>$NomCuenta</td><td>".$fila2["compafectado"]."</td><td>".$fila2['docsoporte']."</td><td align='right'>".number_format($fila2['credito'],2)."</td><td align='right'>".number_format($fila2['contracredito'],2)."</td></tr>";
		$TotCre=$TotCre+$fila2['credito'];
		$TotCCre=$TotCCre+$fila2['contracredito'];
	}

	$Firmas=Firmas($Fecha,$Compania);	

?>
<tr align="right" bgcolor="#e5e5e5" style="font-weight:bold"><td colspan="4">TOTAL</td><td><?echo number_format($TotCre,2)?></td><td align="right"><?echo number_format($TotCCre,2)?></td></tr>
</table>
<br><br>

<table border="0" bordercolor="#000000" cellspacing="1" width="100%" style="font : normal normal 12px Tahoma;">
<tr valign="top"><td width="40%"><em><hr /><? echo $Firmas['Presupuesto'][0]?><br />Aprob&oacute;</em></td><td width="20%"></td><td width="40%"><hr /><em><?echo $UsuarioCre?><br />Elabor&oacute;</em></td></tr>
</table>
