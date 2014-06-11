<?
	if($DatNameSID){session_name("$DatNameSID");}
	session_start();
	include("Funciones.php");

	$cons2="Select Cuenta from Contabilidad.Movimiento where Comprobante='$Comprobante' and (Cuenta ilike '111%' Or Cuenta like '112%') 
	and Numero='$Numero' and Compania='$Compania[0]'";
	$res2=ExQuery($cons2);echo ExError($res2);
	$fila2=ExFetch($res2);
	$Cuenta=$fila2[0];

	$cons1="Select Fecha,Debe,Haber,Identificacion from Contabilidad.Movimiento where Comprobante='$Comprobante' and Numero='$Numero' and Cuenta='$Cuenta' and Movimiento.Compania='$Compania[0]'";
	$res1=ExQuery($cons1);
	$fila1=ExFetchArray($res1);

	
	if($fila1[1]){$Valor=$fila1[1];}
	if($fila1[2]){$Valor=$fila1[2];}

	$Letras=NumerosxLet(number_format($Valor,2,".","")).substr("X X X X X X X X X X X X X X X X X X X X X X X X X X X X X X X X X X X X X X X X ",1,80-strlen($Letras));;
	
	$cons="Select PrimApe,SegApe,PrimNom,SegNom from Central.Terceros where Identificacion='$fila1[3]' and Terceros.Compania='$Compania[0]'";
	$res=ExQuery($cons);
	$fila=ExFetch($res);

	$Tercero="$fila[0] $fila[1] $fila[2] $fila[3]";
	$NomBeneficiario=$Tercero;
	$Tercero=$Tercero.substr("X X X X X X X X X X X X X X X X X X X X X X X X X ",1,50-strlen($Tercero));

	$cons="Select * from Contabilidad.EstructuraCheques where Cuenta='$Cuenta' and Compania='$Compania[0]'";
	$res=ExQuery($cons);
	$fila=ExFetchArray($res);
	if(ExNumRows($res)>0)
	{

?>
<title>Compuconta Software</title>
	<div style="text-align:center;position:absolute;top:<?echo $fila['aniox']?>;left:<?echo $fila['anioy']?>; font-size:12px"><?echo substr($fila1['fecha'],0,4)?></div>

	<div style="position:absolute;top:<?echo $fila['mesx']?>;left:<?echo $fila['mesy']?>;font-size:12px"><?echo substr($fila1['fecha'],5,2)?></div>

	<div style="position:absolute;top:<?echo $fila['diax']?>;left:<?echo $fila['diay']?>;font-size:12px"><?echo substr($fila1['fecha'],8,2)?></div>

	<div style="position:absolute;top:<?echo $fila['valorx']?>;left:<?echo $fila['valory']?>;font-size:12px"><?echo number_format($Valor,2)?></div>

	<div style="position:absolute;top:<?echo $fila['tercerox']?>;left:<?echo $fila['terceroy']?>;font-size:12px"><?echo $Tercero?></div>

	<div style="position:absolute;top:<?echo $fila['letrasx']?>;left:<?echo $fila['letrasy']?>;font-size:12px"><?echo strtoupper($Letras)?></div>
	<? } ?>
<br><br><br><br><br><br><br><br><br><br>
<?
	
	$cons="Select AutoId,Fecha,Comprobante,Numero,Identificacion,Detalle,Cuenta,Debe,Haber,CC,DocSoporte,'',Estado,UsuarioCre,NoCheque 
	from Contabilidad.Movimiento where Comprobante='$Comprobante' and Numero='$Numero' and Compania='$Compania[0]' Order By Debe Desc, Cuenta";
	$res=ExQuery($cons);
	$fila=ExFetchArray($res);
	$Fecha=$fila[1];$Anio=substr($Fecha,0,4);
	$UsuarioCre=$fila[13];$Cheque=$fila[14];$Anio=substr($fila[1],0,4);
?>
<head><meta http-equiv="Content-Type" content="text/html; charset=utf-8"></meta></head>
<body>
<div style="font-family:<?echo $Estilo[8]?>;font-size:12;font-style:<?echo $Estilo[10]?>">
<?
	if($fila["estado"]=="AN")
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

</div>
<table rules="cols"  bordercolor="#ffffff" style="position:absolute;right:20px;top:200px;" border="1" style="font-family:<?echo $Estilo[8]?>;font-size:10;">
<tr bgcolor="#e5e5e5"><td><?echo strtoupper($Comprobante)?></td></tr>
<tr><td><font size="+1"><center><?echo $Numero?></td></tr>
</table>
<?
	
	$cons1="Select PrimApe,SegApe,PrimNom,SegNom,Identificacion,Direccion,Telefono from Central.Terceros where Identificacion='$fila[4]' and Terceros.Compania='$Compania[0]'";
	$res1=ExQuery($cons1);
	$fila1=ExFetch($res1);
?>
<br>
<center>
<table border="1" width="85%" bordercolor="#ffffff" style="font-family:<?echo $Estilo[8]?>;font-size:11;font-style:<?echo $Estilo[10]?>">
<tr><td><strong>Fecha</td><td><?echo $fila[1]?></td></tr>
<tr><td><strong>Tercero</td><td><?echo "$fila1[0] $fila1[1] $fila1[2] $fila1[3]"?></td><td><strong>Identificacion</td><td><?echo $fila1[4]?></td></tr>
<tr><td><strong>Direccin</td><td><?echo $fila1[5]?></td><td><strong>Telefono</td><td><?echo $fila1[6]?></td></tr>
<tr><td><strong>Detalle</td><td colspan="3"><?echo $fila['detalle']?></td></tr>
<tr><td><strong>Cheque</td><td><? echo $Cheque?></td></tr>
</table>
<br>
<?
	$consCom="Select Comprobante,Numero,ClaseVigencia,Vigencia from Presupuesto.Movimiento 
	where DocOrigen='$Comprobante' and NoDocOrigen='$Numero' and Estado='AC' and Movimiento.Compania='$Compania[0]' Group By Comprobante,Numero,ClaseVigencia,Vigencia";
	$resCom=ExQuery($consCom);
	while($filaCom=ExFetch($resCom))
	{
		$cons2="Select * from Presupuesto.Movimiento where Comprobante='$filaCom[0]' and NoDocOrigen='$Numero' and Movimiento.Compania='$Compania[0]' and Vigencia='$filaCom[3]'
		and ClaseVigencia='$filaCom[2]' 
		Order By Cuenta";
		$res2=ExQuery($cons2);
		if(ExNumRows($res2)>0)
		{
	?>
			<table border="1" bordercolor="#ffffff" width="100%" style="font-family:<?echo $Estilo[8]?>;font-size:11;font-style:<?echo $Estilo[10]?>">
			<tr bgcolor="#e5e5e5" style="font-weight:bold"><td colspan="6" style="text-transform:uppercase"><center><?echo "$filaCom[0] NO. $filaCom[1] "?></td></tr>
			<tr bgcolor="#e5e5e5" style="font-weight:bold"><td>Codigo</td><td>Nombre</td><td>Doc Afectado</td><td>No</td><td>Credito</td><td>Contra Credito</td></tr>
		<?
			$ClaseVigencia=$filaCom[2];$Vigencia=$filaCom[3];
			$res2=ExQuery($cons2);
			while($fila2=ExFetchArray($res2))
			{
				$cons9="Select Nombre from Presupuesto.PlanCuentas where Cuenta='".$fila2['cuenta']."' and Anio=$Anio and Compania='$Compania[0]' and Vigencia='$Vigencia' and ClaseVigencia='$ClaseVigencia'";
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
		$res9=ExQuery($cons9);
		$fila9=ExFetch($res9);
		$NomCuenta=$fila9[0];

		echo "<tr><td>$fila[6]</td><td>$NomCuenta</td><td>$fila[4]</td><td>$fila[9]</td><td>$fila[10]</td><td align='right'>".number_format($fila[7],2)."</td><td align='right'>".number_format($fila[8],2)."</td></tr>";
		$TotDebe=$TotDebe+$fila[7];$TotHaber=$TotHaber+$fila[8];
	}
?>
<tr bgcolor="#e5e5e5" style="font-weight:bold"><td colspan="5">SUMAS</td><td align="right"><?echo number_format($TotDebe,2)?></td><td align="right"><?echo number_format($TotHaber,2)?></td></tr>
</table>
<table border="1" width="70%" bordercolor="#ffffff" style="font-family:<?echo $Estilo[8]?>;font-size:11;font-style:<?echo $Estilo[10]?>">
<?
	$cons2="Select * from Contabilidad.Movimiento where Comprobante='$Comprobante' and Numero='$Numero' and Compania='$Compania[0]' and ConceptoRte!='' and ConceptoRte!='0'";
	$res2=ExQuery($cons2);
	if(ExNumRows($res2)>0)
	{
?>

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
</table>
<?	}
	$Firmas=Firmas($Fecha,$Compania);
?>
<br><br><br>
<table border="1" bordercolor="#000000" cellspacing="1" width="100%" style=";font-family:<?echo $Estilo[8]?>;font-size:10;font-style:<?echo $Estilo[10]?>">
<tr  valign="bottom"><td width="25%"><br><br><? echo $UsuarioCre?><br>Elabor&oacute;</td><td width="25%"><br><br><br>Revis&oacute;</td><td width="25%"><br><br><br>Aprob&oacute;</td><td width="25%"><br><br><? echo $NomBeneficiario?><br>Beneficiario</td></tr>
</table>
