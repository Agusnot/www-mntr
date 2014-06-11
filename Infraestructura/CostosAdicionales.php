<?
	if($DatNameSID){session_name("$DatNameSID");}
	session_start();
	include("Funciones.php");
	if($Eliminar)
	{
		$cons = "Delete From Infraestructura.CostosAdicionales Where Compania='$Compania[0]' and Concepto='$Concepto' and TMPCOD='$TMPCOD'";
		$res = ExQuery($cons);	
	}
	if($Guardar)
	{
		$cons = "Insert into Infraestructura.CostosAdicionales (Compania,TMPCOD,Concepto,Valor)
		values('$Compania[0]','$TMPCOD','$Concepto','$Valor')";
		$res = ExQuery($cons);
	}
?>
<script language="javascript" src="/Funciones.js"></script>
<script language="javascript">
	function CerrarThis()
	{
		parent.document.getElementById('FrameOpener').style.position='absolute';
		parent.document.getElementById('FrameOpener').style.top='1px';
		parent.document.getElementById('FrameOpener').style.left='1px';
		parent.document.getElementById('FrameOpener').style.width='1';
		parent.document.getElementById('FrameOpener').style.height='1';
		parent.document.getElementById('FrameOpener').style.display='none';
		parent.document.FORMA.CostAd.value = document.FORMA.Totadicional.value;
	}
</script>
<body background="/Imgs/Fondo.jpg">
<form name="FORMA" method="post">
<input type="hidden" name="DatNameSID" value="<? echo $DatNameSID?>" />
<input type="hidden" name="TMPCOD" value="<? echo $TMPCOD?>" />
<div align="right">
	<button type="button" name="Cerrar" title="Cerrar" onClick="CerrarThis()"><img src="/Imgs/b_drop.png" /></button>
</div>
<table border="1" width="100%" bordercolor="#e5e5e5" style="font-family:<? echo $Estilo[8]?>;font-size:12;font-style:<? echo $Estilo[10]?>">
<tr bgcolor="#e5e5e5" style="font-weight:bold" align="center"><td width="80%">Concepto</td><td width="20%">Valor</td><td>&nbsp;</td></tr>
<tr><td><input type="text" name="Concepto" style="width:100%" onKeyUp="xLetra(this)" onKeyDown="xLetra(this)" /></td>
<td><input type="text" name="Valor" style="text-align:right" onKeyUp="xNumero(this)" onKeyDown="xNumero(this)" onBlur="campoNumero(this)" /></td>
<td><button type="submit" name="Guardar" title="Guardar"><img src="/Imgs/b_save.png" /></button></td></tr>
<?
$cons = "Select Concepto,Valor from Infraestructura.CostosAdicionales Where Compania='$Compania[0]' and TMPCOD='$TMPCOD'";
$res = ExQuery($cons);
while($fila = ExFetch($res))
{
	$TotalCostoAdicional = $TotalCostoAdicional + $fila[1];
	echo "<tr><td>$fila[0]</td><td align='right'>".number_format($fila[1],2)."</td>";
	?><td align="center"><img src="/Imgs/b_drop.png" title="Eliminar" style="cursor:hand" 
    onclick="if(confirm('Desea Eliminar este registro?')){location.href='CostosAdicionales.php?DatNameSID=<? echo $DatNameSID?>&Eliminar=1&Concepto=<? echo $fila[0]?>&TMPCOD=<? echo $TMPCOD;?>'}" /></td></tr><?	
}
?>
<tr bgcolor="#e5e5e5" style="font-weight:bold"><td align="right">TOTAL</td><td align="right"><? echo number_format($TotalCostoAdicional,2);?></td></tr>
</table>
<input type="hidden" name="Totadicional" value="<? echo $TotalCostoAdicional;?>" />
</form>
</body>