<?
		if($DatNameSID){session_name("$DatNameSID");}
	session_start();
	include("Funciones.php");
	$ND=getdate();
	if($Todos=="No"){$CondAdy=" and Identificacion='$Tercero' ";}
	$consTerc="Select Identificacion from Contabilidad.Movimiento where Cuenta>='$CtaInicial' and Cuenta<='$CtaFinal' and BaseGravable>0 and date_part('year',Fecha)=$Anio $CondAdy
	Group By Identificacion";
	$resTerc=ExQuery($consTerc);
	while($filaTerc=ExFetch($resTerc))
	{
	$Tercero=$filaTerc[0];
	$cons="Select Identificacion,PrimApe,SegApe,PrimNom,SegNom,Direccion,Telefono,Departamento,Municipio from Central.Terceros where Identificacion='$Tercero'
	and Compania='$Compania[0]'";
	$res=ExQuery($cons);
	$filaTercero=ExFetch($res);
?>


<style>
P{PAGE-BREAK-AFTER: always;}
</style>
<table border="0" width="100%">
<tr><td>
<img src="/Imgs/Logo.jpg" style="width:100px;position:relative">
</td><td><strong>
<font style="font-family:<?echo $Estilo[8]?>;font-size:11;">
<?
echo strtoupper($Compania[0])."<br></strong>";
echo "$Compania[1]<br>$Compania[2] - $Compania[3]<br>";
?>
</td><td>
<font style="font-family:<?echo $Estilo[8]?>;font-size:18;">
<center>
<strong>CERTIFICADO DE RETENCION<br> EN LA FUENTE</td>
</table>


<table style="text-align:justify" border="0" cellpadding="6" style="font-family:<?echo $Estilo[8]?>;font-size:12;">
<tr><td>Raz&oacute;n Social del Retenedor:</td></tr>
<tr><td><font size="+1"><strong><?echo strtoupper($Compania[0])?></strong></td></tr>
<tr><td> <?echo $Compania[1]?></td></tr>
<tr><td>Direccion: <?echo $Compania[2]?></td></tr>
<tr><td>Telefono(s): <?echo $Compania[3]?></td></tr>
<?$Mensaje=str_replace("Anio",$Anio,$Mensaje);?>
<tr><td><?echo $Mensaje?></td></tr>
<tr><td><center><strong><font size="+1"><br>HEMOS RETENIDO A:<br><br></td></tr>
<tr><td><font size="+1"><strong><?echo "$filaTercero[1] $filaTercero[2] $filaTercero[3] $filaTercero[4]"?></strong></td></tr>
<tr><td>Nit <?echo $Tercero?></td></tr>
<tr><td>Direccion: <?echo $filaTercero[5]?></td></tr>
<tr><td>Ciudad: <? echo utf8_decode($filaTercero[8]) . " (" . utf8_decode($filaTercero[7]) . ")" ?></td></tr>
<tr><td>Telefono: <?echo $filaTercero[6]?></td></tr>
</table>
<br><br>
<table border="1" bordercolor="#ffffff" width="100%" style="font-family:<?echo $Estilo[8]?>;font-size:12;">
<tr bgcolor="#e5e5e5" style="font-weight:bold" align="center" ><td>CONCEPTO</td><td>Monto Sometido a Retenci&oacute;n</td><td>Cuantia Retenci&oacute;n</td></tr>
<?
	$cons="SELECT sum(Haber) as VrRetenido,sum(BaseGravable),ConceptoRte FROM Contabilidad.Movimiento WHERE Identificacion='$Tercero' 
	and Cuenta>='$CtaInicial' and Cuenta<='$CtaFinal' and BaseGravable>0 and date_part('year',Fecha)=$Anio Group By ConceptoRte";
	$res=ExQuery($cons);
	while($fila=ExFetch($res))
	{
		echo "<tr><td>$fila[2]</td><td align='right'>".number_format($fila[1],2)."</td><td align='right'>".number_format($fila[0],2)."</td></tr>";
		$TotRetenido=$TotRetenido+$fila[0];
	}
	$Letras=NumerosxLet($TotRetenido);


	$cons5="Select * from Central.Meses where Numero=".$ND[mon];
	$res5=ExQuery($cons5);
	$fila5=ExFetch($res5);
	$MesLet=$fila5[0];
?>
<tr><td></td><td></td><td><hr></td></tr>
<tr align="right" style="font-weight:bold"><td></td><td align="right">TOTAL RETENIDO</td><td align="right"><?echo number_format($TotRetenido,2)?></td></tr>
<tr><td colspan="3">SON: <?echo strtoupper($Letras)?></td></tr>
</table>
<br>
<hr>
<?$TotRetenido=0;?>
<font style="font-family:<?echo $Estilo[8]?>;font-size:12;">
Retenci&oacute;n consignada oportunamente en la Administraci&oacute;n de Impuestos Nacionales.<br>
Se expide el presente certificado en cumplimiento de lo establecido en el articulo 381 del Estatuto Tributario<br>
Expedida en Yumbo (Valle) a los <?echo $ND[mday]?> dias del mes de <?echo $MesLet?> de <?echo $ND[year]?>
<br><br><br><hr><em>
<strong>Las personas juridicas pueden entregar los certificados de retenci&oacute;n en la fuente en forma continua impresa por computador, sin necesidad de firma aut&oacute;grafa (D.R. 8036/91 Art 10)</strong></em>
<br><br><br><br><br>

<?}?><br><br><p></p>