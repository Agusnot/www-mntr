<?	if($DatNameSID){session_name("$DatNameSID");}
	session_start();
include("Funciones.php");
	$ND=getdate();
	if(!$PerIni){$PerIni="$ND[year]-$ND[mon]-01";}
	if(!$PerFin){$PerFin="$ND[year]-$ND[mon]-$ND[mday]";}
?>
<title>Compuconta Software</title>
<style>
a{color:black;text-decoration:none;}
a:hover{color:blue;text-decoration:underline;}
</style>
<style>body{background:<?echo $Estilo[6]?>;color:<?echo $Estilo[7]?>;font-family:<?echo $Estilo[8]?>;font-size:<?echo $Estilo[9]?>;font-style:<?echo $Estilo[10]?>}</style>
<style>
.Tit1{color:white;background:<?echo $Estilo[1]?>;font-weight:bold;}
a.For2{color:yellow;text-decoration:none;}
a.For2:hover{color:white;text-decoration:underline;}
</style>
<center>
<form name="FORMA" method="post">
<table border="1" bordercolor="#ffffff" style="font-family:<?echo $Estilo[8]?>;font-size:<?echo $Estilo[9]?>;font-style:<?echo $Estilo[10]?>">
<tr><td colspan="2"><center>
PERIODO
</td>
<td>
<input type="Text" name="PerIni" style="width:70px;" value="<?echo $PerIni?>">
</td>
<td>
<input type="Text" name="PerFin" style="width:70px;" value="<?echo $PerFin?>">
<input type="Hidden" name="CondAdc" value="">
<input type="Hidden" name="OrdCampo">
</td>
<td><input type="Submit" value="Ver"></td>
</table></center>
<script language="JavaScript">
	function Reabrir(Campos)
	{
		document.FORMA.OrdCampo.value=Campos;
		document.FORMA.submit();
	}
</script>
<table border="1" bordercolor="#ffffff" width="100%" style="font-family:<?echo $Estilo[8]?>;font-size:<?echo $Estilo[9]?>;font-style:<?echo $Estilo[10]?>">
<tr class="Tit1">
<td><a href="javascript:Reabrir('Cuenta')" class="For2">Cuenta</a></td>
<td><a href="javascript:Reabrir('Comprobante')" class="For2">Comprobante</td>
<td><a href="javascript:Reabrir('Numero')" class="For2">Numero</td>
<td><a href="javascript:Reabrir('Fecha')" class="For2">Fecha</td>
<td><a href="javascript:Reabrir('Credito')" class="For2">Credito</td>
<td><a href="javascript:Reabrir('ContraCredito')" class="For2">Contra Cred</td>

<td><a href="javascript:Reabrir('Detalle')" class="For2">Detalle</td></tr>
<?
	if($TerceroSel)
	{
		$cons="Select PrimApe,SegApe,PrimNom,SegNom from Central.Terceros where Identificacion='$TerceroSel' and Terceros.Compania='$Compania[0]'";
		$res=ExQuery($cons);
		$fila=ExFetch($res);
		$CondAdc2=" and Movimiento.Identificacion='$TerceroSel'";
		echo "<tr class='Tit1'><td colspan=8><center><font color='#ffff00'>TERCERO SELECCIONADO: $TerceroSel - " . strtoupper("$fila[0] $fila[1] $fila[2] $fila[3]") . "</td></tr>";
	}
	if($OrdCampo)
	{
		if($OldCampo==$OrdCampo){$Ord="Desc";$OldCampo="x";}
		else{$Ord="Asc";$OldCampo=$OrdCampo;}
		$CondAdc3=" Order By $OrdCampo $Ord";
	}
	elseif(!$OrdCampo){$CondAdc3=" Order By Fecha,Comprobante,Numero,Cuenta";}
?>
<input type="hidden" name="DatNameSID" value="<? echo $DatNameSID?>" />
<input type="Hidden" name="OldCampo" value="<?echo $OldCampo?>">
</form>
<?
	if(!$CondAdc){$Condicion="1=1";}
	else{$Condicion=" Comprobante='$CondAdc' ";}
	$cons="Select Cuenta,Comprobante,Numero,Fecha,Credito,ContraCredito,Detalle,PrimApe,SegApe,PrimNom,SegNom,Movimiento.Identificacion 
	from 
	Presupuesto.Movimiento,Central.Terceros where Movimiento.Identificacion=Terceros.Identificacion and Cuenta ilike '$Cuenta%' and Fecha>='$PerIni' 
	and Terceros.Compania='$Compania[0]'
	and Fecha<='$PerFin' and Movimiento.Compania='$Compania[0]' and Estado='AC' and $Condicion $CondAdc2 $CondAdc3";

	$res=ExQuery($cons,$conex);
	while($fila=ExFetchArray($res))
	{
		if($Fondo==1){$BG="#EEF6F6";$Fondo=0;}
		else{$BG="white";$Fondo=1;}
		echo "<tr bgcolor='$BG'><td>".$fila['cuenta']."</td><td><a href='MovimientoxCuenta.php?PerIni=$PerIni&PerFin=$PerFin&Cuenta=$Cuenta&CondAdc=" . $fila['comprobante'] ."'>".$fila['comprobante']."</a></td><td>".$fila['numero']."</td><td>".$fila['fecha']."</td><td align='right'>".number_format($fila['credito'],2)."</td><td align='right'>".number_format($fila['contracredito'],2)."</td><td>".strtoupper(substr($fila['detalle'],0,60))."</td></tr>";
		$SumDeb=$SumDeb+$fila['credito'];
		$SumCred=$SumCred+$fila['contracredito'];
	}
?>
<tr class="Tit1" style="font-weight:bold" align="right"><td colspan="4">TOTAL</td><td><?echo number_format($SumDeb,2)?></td><td><?echo number_format($SumCred,2)?></td><td colspan="2">&nbsp;</td></tr>
</table>