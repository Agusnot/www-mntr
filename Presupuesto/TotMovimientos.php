<?	if($DatNameSID){session_name("$DatNameSID");}
	session_start();
include("Funciones.php");
?>
<form name="FORMA">
<table width="100%" border="1" bordercolor="black" cellspacing="0" cellpadding="2" rules="groups" style="font-family:<?echo $Estilo[8]?>;font-size:12;font-style:<?echo $Estilo[10]?>">
<tr style="color:<?echo $Estilo[6]?>;font-weight:bold;text-align:center"bgcolor="<?echo $Estilo[1]?>"><td>Descripci&oacute;n</td><td>Saldo</td>
<td>Creditos</td><td>Contra Creditos</td><td>Diferencia</td></tr>
<tr>
<td>
<input type="Text" name="Descripcion" style="width:200px;border:1px solid" readonly="yes">
</td>
<td>
<input type="Text" name="Saldo" style="width:100px;border:1px solid;color:<?echo $Estilo[1]?>;text-align:right" readonly="yes">
</td>
<td>
<input type="Text" name="TotDebitos" style="width:100px;border:1px solid;color:<?echo $Estilo[1]?>;text-align:right" readonly="yes">
</td>
<td>
<input type="Text" name="TotCreditos" style="width:100px;border:1px solid;color:<?echo $Estilo[1]?>;text-align:right" readonly="yes">
</td>
<td>
<input type="Text" name="Diferencia" style="width:100px;border:1px solid;color:<?echo $Estilo[1]?>;text-align:right" readonly="yes">
</td>
</tr>
</table>
</form>