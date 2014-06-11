<?
	if($DatNameSID){session_name("$DatNameSID");}
	session_start();
	include("Funciones.php");

	$cons="Select AutoId,Fecha,Comprobante,Numero,Identificacion,Detalle,Cuenta,Debe,Haber,CC,DocSoporte,'',Estado,UsuarioCre
	from Contabilidad.Movimiento where Comprobante='$Comprobante' and Numero='$Numero' and Compania='$Compania[0]' Order By Debe Desc, Cuenta";
	$res=ExQuery($cons);
	$fila=ExFetchArray($res);
	$UsuarioCre=$fila[13];
	$Fecha=$fila[1];$Anio=substr($Fecha,0,4);
	$cons1="Select PrimApe,SegApe,PrimNom,SegNom,Identificacion,Direccion,Telefono from Central.Terceros where Identificacion='$fila[4]' and Terceros.Compania='$Compania[0]'";
	$res1=ExQuery($cons1);
	$fila1=ExFetch($res1);


	$cons9="Select sum(Haber) from  Contabilidad.Movimiento where Comprobante='$Comprobante' and Numero='$Numero' and Compania='$Compania[0]'";
	$res9=ExQuery($cons9);
	$fila9=ExFetch($res9);
	$Valor=$fila9[0];
	$Letras=NumerosxLet($fila9[0]);
	

	$Firmas=Firmas($Fecha,$Compania);

	$Cargo=$Firmas['Representante'][1];$NombreDirec=$Firmas['Representante'][0];$Municipio=$Compania[7];
	
	$cons5="Select * from Central.Meses where Numero=".substr($fila[1],5,2);
	$res5=ExQuery($cons5);
	$fila5=ExFetch($res5);
	$MesLet=$fila5[0];
	
?>
<title>Impresi&oacute;n de Comprobante</title>
<head><meta http-equiv="Content-Type" content="text/html; charset=utf-8"></meta></head>
<body>
<div style="font-family:<?echo $Estilo[8]?>;font-size:12;font-style:<?echo $Estilo[10]?>">
<?
	if($fila["Estado"]=="AN")
	{
		echo "<center><img src='/Imgs/Anulado.gif' style='position:absolute;top:170px;left:140px'></center>";
	}

?>
<font size="2">
<center><strong>
<br><br>
REPUBLICA DE COLOMBIA<br>
DEPARTAMENTO DE NARI&ntilde;O<br>
<?echo $Compania[0]?><br><br>
RESOLUCION NUMERO <?echo $Numero?> DE <?echo substr($fila[1],0,4)?><br>
<br>(  <?echo $fila[1]?>  )<br><br>
POR MEDIO DEL CUAL SE RECONOCE UN GASTO Y SE AUTORIZA UN PAGO<br><br>
</font>
</strong>
</center>

EL <?echo $Cargo?> DE <?echo $Compania[0]?>, en uso de sus atribuciones legales y constitucionales, en especial las conferidas en el Art. 91, Literal D, 
numeral 5 de la Ley 136 de 1994 y,<br><br>
<center><strong>
C O N S I D E R A N D O :<br><br></strong>
	</center>
Que dentro del presupuesto de Gastos de <?echo $Compania[0]?>, Vigencia <?echo substr($fila[1],0,4)?> existe una partida denominada:

<?
	$consCom="Select Comprobante,Numero,Vigencia,ClaseVigencia from Presupuesto.Movimiento 
	where DocOrigen='$Comprobante' and NoDocOrigen='$Numero' and Estado='AC' and Movimiento.Compania='$Compania[0]' 
	Group By Comprobante,Numero,Vigencia,ClaseVigencia";
	$resCom=ExQuery($consCom);
	while($filaCom=ExFetch($resCom))
	{

		$cons2="Select * from Presupuesto.Movimiento where Comprobante='$filaCom[0]' and NoDocOrigen='$Numero' and Movimiento.Compania='$Compania[0]' Order By Cuenta";
		$res2=ExQuery($cons2);
		if(ExNumRows($res2)>0)
		{
	?>
			<table style="font-family:<?echo $Estilo[8]?>;font-size:11;font-style:<?echo $Estilo[10]?>">
			<tr><td>
		<?
			$Vigencia=$filaCom[2];$ClaseVigencia=$filaCom[3];
			$res2=ExQuery($cons2);
			while($fila2=ExFetchArray($res2))
			{
				$cons9="Select Nombre from Presupuesto.PlanCuentas where Cuenta='".$fila2['cuenta']."' and Anio=$Anio and ClaseVigencia='$ClaseVigencia' and Compania='$Compania[0]'";
				$res9=ExQuery($cons9);
				$fila9=ExFetch($res9);
				$NomCuenta=substr($fila9[0],0,80);
			
				echo "<tr><td>* $NomCuenta</td></tr>";
			}
		}
	}
?>
</table><br>

Que en virtud de lo anterior le corresponde a este despacho ordenar el pago correspondiente:<br><br>

<center><strong>
R E S U E L V E :<br></strong>
</center>
<p align="justify">
<strong>ARTICULO PRIMERO.</strong> Paguese a  <?echo "$fila1[0] $fila1[1] $fila1[2] $fila1[3]"?> identificado con NIT/CC <?echo $fila[4]?> por concepto de <?echo $fila[5]?>,
 la suma de <?echo strtoupper($Letras) . "(".number_format($Valor,2).")"?><br><br>
<strong>ARTICULO SEGUNDO.</strong> El valor anterior se imputar&aacute; al presupuesto de Gastos vigencia <? echo $Anio;?>
<br><br><strong>
</p>

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
				$cons9="Select Nombre from Presupuesto.PlanCuentas where Cuenta='".$fila2['cuenta']."' and Anio=$Anio and ClaseVigencia='$ClaseVigencia' and Compania='$Compania[0]'";
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
</table>
<center>
<br><br>
C O M U N I Q U E S E &nbsp; &nbsp; &nbsp;Y    &nbsp; &nbsp; &nbsp;C U M P L A S E</center><br><br></strong>
Dada en <?echo $Municipio?>  a los  <?echo substr($Fecha,8,2)?> DIAS DEL MES DE <? echo strtoupper($MesLet)?>  DE <?echo substr($Fecha,0,4)?><br><br><br><br>
<strong>
<center>
<?echo $NombreDirec?><br><?echo $Cargo?><br><br><br></strong>
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
