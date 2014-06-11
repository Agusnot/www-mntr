<?	if($DatNameSID){session_name("$DatNameSID");}
	session_start();
include("Funciones.php");
	$ND=getdate();
	if($AnioAc1){$AnioAc=$AnioAc1;}
?>
<style>body{background:<?echo $Estilo[1]?>;color:<?echo $Estilo[2]?>;font-family:<?echo $Estilo[3]?>;font-size:12;font-style:<?echo $Estilo[5]?>}</style>
<table border="1" bordercolor="<?echo $Estilo[1]?>" style="font-family:<?echo $Estilo[8]?>;font-size:<?echo $Estilo[9]?>;font-style:<?echo $Estilo[10]?>">
<tr><td>A&ntilde;o</td><td>Cuenta</td>
<tr>
<td>
<select name="Anio" onchange="location.href='EncabSelCuenta2.php?DatNameSID=<? echo $DatNameSID?>&AnioAc1='+this.value;parent(1).location.href='ListaCuentasPUC.php?DatNameSID=<? echo $DatNameSID?>';parent.parent(2).location.href='ResumenEjecucion.php?DatNameSID=<? echo $DatNameSID?>'">
<?
		$AnioInc=$AnioAc-10;
		$AnioAf=$AnioAc+10;
		for($i=$AnioInc;$i<$AnioAf;$i++)
		if($i==$AnioAc){echo "<option selected value=$i>$i</option>";}
		else{echo "<option value=$i>$i</option>";}
		echo "</select>";
?>
</td>
<td><input type="Text" name="CtaBuscar" onkeyup="parent(1).location.href='ListaCuentasPUC.php?DatNameSID=<? echo $DatNameSID?>&CtaBuscar='+this.value"></td>
<td><input type="Button" value="Volver" onclick="parent.parent.location.href='/Principal.php?DatNameSID=<? echo $DatNameSID?>'"></td>
<tr><td colspan="3" align="center">
Vigencia<input name="TipoVigencia" type="Radio" checked onclick="parent(1).location.href='ListaCuentasPUC.php?DatNameSID=<? echo $DatNameSID?>';parent.parent(2).location.href='ResumenEjecucion.php?DatNameSID=<? echo $DatNameSID?>'"> 
Reservas<input name="TipoVigencia" type="Radio" onclick="parent(1).location.href='ListaCuentasPUC.php?DatNameSID=<? echo $DatNameSID?>&TipoVigencia=Reservas';parent.parent(2).location.href='ResumenEjecucion.php?DatNameSID=<? echo $DatNameSID?>'"> 
C x P<input name="TipoVigencia" type="Radio" onclick="parent(1).location.href='ListaCuentasPUC.php?DatNameSID=<? echo $DatNameSID?>&TipoVigencia=CxP';parent.parent(2).location.href='ResumenEjecucion.php?DatNameSID=<? echo $DatNameSID?>'"></td></tr>
<input type="hidden" name="DatNameSID" value="<? echo $DatNameSID?>" />
</tr>
</table>