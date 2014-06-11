<?
	if($DatNameSID){session_name("$DatNameSID");}
	session_start();
	include("Funciones.php");

	$cons="Select AutoId,Fecha,Comprobante,Numero,Identificacion,Detalle,Cuenta,Debe,Haber,CC,DocSoporte,'',Estado,UsuarioCre,Anio 
	from Contabilidad.Movimiento where Comprobante='$Comprobante' and Numero='$Numero' and Compania='$Compania[0]' Order By Debe Desc, Cuenta";
	$res=ExQuery($cons);
	$fila=ExFetchArray($res);
	$Anio=$fila[14];
	$UsuarioCre=$fila[13];
?>
<title>Impresi&oacute;n de Comprobante</title>
<body>
<div style="font-family:<?echo $Estilo[8]?>;font-size:12;font-style:<?echo $Estilo[10]?>">
<?
	if($fila["Estado"]=="AN")
	{
		echo "<center><img src='/Imgs/Anulado.gif' style='position:absolute;top:170px;left:140px'></center>";
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
</font>
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

<center>
<table border="1" width="85%" bordercolor="#ffffff" style="font-family:<?echo $Estilo[8]?>;font-size:11;font-style:<?echo $Estilo[10]?>">
<tr><td><strong>Fecha</td><td><?echo $fila[1]?></td></tr>
<tr><td><strong>Tercero</td><td><?echo "$fila1[0] $fila1[1] $fila1[2] $fila1[3]"?></td><td><strong>Identificacion</td><td><?echo $fila1[4]?></td></tr>
<tr><td><strong>Direcci&oacute;n</td><td><?echo $fila1[5]?></td><td><strong>Telefono</td><td><?echo $fila1[6]?></td></tr>
<tr><td><strong>Detalle</td><td colspan="3"><?echo $fila['detalle']?></td></tr>
</table>

<?
	$consCom="Select Comprobante,Numero from Presupuesto.Movimiento where DocOrigen='$Comprobante' and NoDocOrigen='$Numero' and Estado='AC' and Movimiento.Compania='$Compania[0]' Group By Comprobante,Numero";
	$resCom=ExQuery($consCom);
	while($filaCom=ExFetch($resCom))
	{

		$cons2="Select * from Presupuesto.Movimiento where Comprobante='$filaCom[0]' and NoDocOrigen='$Numero' and Movimiento.Compania='$Compania[0]' Order By Cuenta";
		$res2=ExQuery($cons2);
		if(ExNumRows($res2)>0)
		{
	?>
			<table border="1" bordercolor="#ffffff" width="100%" style="font-family:<?echo $Estilo[8]?>;font-size:11;font-style:<?echo $Estilo[10]?>">
			<tr bgcolor="#e5e5e5" style="font-weight:bold"><td colspan="6" style="text-transform:uppercase"><center><?echo "$filaCom[0] NO. $filaCom[1] "?></td></tr>
			<tr bgcolor="#e5e5e5" style="font-weight:bold"><td>Codigo</td><td>Nombre</td><td>Doc Afectado</td><td>No</td><td>Credito</td><td>Contra Credito</td></tr>
		<?
			$res2=ExQuery($cons2);
			while($fila2=ExFetchArray($res2))
			{
				$cons9="Select Nombre from Presupuesto.PlanCuentas where Cuenta='".$fila2['cuenta']."' and Vigencia='".$fila2['vigencia']."' and ClaseVigencia='".$fila2['clasevigencia']."' and Anio='".$fila2['anio']."' and Compania='$Compania[0]'";
				$res9=ExQuery($cons9);
				$fila9=ExFetch($res9);
				$NomCuenta=substr($fila9[0],0,80);
			
				echo "<tr><td>".$fila2['cuenta']."</td><td>$NomCuenta</td><td>".$fila2['compafectado']."</td><td>".$fila2['docsoporte']."</td><td align='right'>".number_format($fila2['credito'],2)."</td><td align='right'>".number_format($fila2['contracredito'],2)."</td></tr>";
				$TotCre=$TotCre+$fila2['credito'];
				$TotCCre=$TotCCre+$fila2['contracredito'];
			}
			echo "<tr align='right' style='font-weight:bold'><td colspan=3></td><td bgcolor='#e5e5e5'>TOTAL</td><td bgcolor='#e5e5e5' >".number_format($TotCre,2)."</td><td bgcolor='#e5e5e5' >".number_format($TotCCre,2)."</td></tr>";
			$TotCre=0;$TotCCre=0;
		}
	}
?>


</center>

<table border="1" bordercolor="#ffffff" width="100%" style="font-family:<?echo $Estilo[8]?>;font-size:11;font-style:<?echo $Estilo[10]?>">
<tr bgcolor="#e5e5e5" style="font-weight:bold"><td colspan="7"><center>AFECTACION CONTABLE</td></tr>
<tr bgcolor="#e5e5e5" style="font-weight:bold"><td>Codigo</td><td>Nombre</td><td>Tercero</td><td>CC</td><td>Doc</td><td>Debito</td><td>Credito</td></tr>
<?
	$res=ExQuery($cons);
	while($fila=ExFetch($res))
	{
		$cons9="Select Nombre from Contabilidad.PlanCuentas where Cuenta='".$fila[6]."' and Anio=$Anio and Compania='$Compania[0]'";
		$res9=ExQuery($cons9);echo ExError();
		$fila9=ExFetch($res9);
		$NomCuenta=$fila9[0];

		echo "<tr><td>$fila[6]</td><td>$NomCuenta</td><td>$fila[4]</td><td>$fila[9]</td><td>$fila[10]</td><td align='right'>".number_format($fila[7],2)."</td><td align='right'>".number_format($fila[8],2)."</td></tr>";
		$TotDebe=$TotDebe+$fila[7];$TotHaber=$TotHaber+$fila[8];
	}
?>
<tr bgcolor="#e5e5e5" style="font-weight:bold"><td colspan="5">SUMAS</td><td align="right"><?echo number_format($TotDebe,2)?></td><td align="right"><?echo number_format($TotHaber,2)?></td></tr>
</table>
<? 
	$cons2="Select * from Contabilidad.Movimiento where Comprobante='$Comprobante' and Numero='$Numero' and Compania='$Compania[0]' and ConceptoRte!='' and ConceptoRte!='0'";
	$res2=ExQuery($cons2);
	if(ExNumRows($res2)>0)
	{
?>
<table border="1" width="70%" bordercolor="#ffffff" style="font-family:<?echo $Estilo[8]?>;font-size:11;font-style:<?echo $Estilo[10]?>">
<tr bgcolor="#e5e5e5" align="center"><td colspan="4"><strong>RETENCIONES</td></tr>
<tr bgcolor="#e5e5e5" style="font-weight:bold;text-align:center"><td>Concepto</td><td>Base</td><td>%</td><td>Vr Retenido</td></tr>
<?
	$cons="Select ConceptoRte,BaseGravable,PorcRetenido,Haber from Contabilidad.Movimiento where Compania='$Compania[0]' and Comprobante='$Comprobante' and Numero='$Numero' and ConceptoRte!='' and ConceptoRte!='0'";
	$res=ExQuery($cons);echo ExError($res);
	while($fila=ExFetch($res))
	{
		echo "<tr><td>$fila[0]</td><td align='right'>".number_format($fila[1],2)."</td><td align='right'>".number_format($fila[2],2)."</td><td align='right'>".number_format($fila[3],2)."</td></tr>";
	}
?>
</table><?	}?>
<br><br>

<table border="1" bordercolor="white" cellspacing="1" width="100%" style="font : normal normal 12px Tahoma;">
<tr valign="top"><td><hr>Revisado Por</td><td style="width:100px;"></td><td><hr>Elaborado Por<br><?echo $UsuarioCre?></em></td></tr>
</table>
