<?
	if($DatNameSID){session_name("$DatNameSID");}
	session_start();
	include("Funciones.php");
	$ND=getdate();
	if(!$TipoTercero){$TipoTercero="Persona Natural";}
?>
<style>
table{
font-family:<?echo $Estilo[8]?>;font-size:<?echo $Estilo[9]?>;font-style:<?echo $Estilo[10]?>;
}
</style>
<head><meta http-equiv="Content-Type" content="text/html; charset=utf-8"></meta></head>
<body background="/Imgs/Fondo.jpg">
<form name="FORMA" action="ListaTerceros.php?DatNameSID=<? echo $DatNameSID?>" target="Abajo">
<center><table>
<tr><td>
<table border="1" bordercolor="#e5e5e5" cellpadding="2"  width="100%">
<tr bgcolor="#e5e5e5" style="font-weight:bold" align="center"><td>Buscar x</td><td>Identificaci&oacute;n</td>
<? if($TipoTercero=="Persona Juridica"){ ?> <td>Raz&oacute;n Social </td> <? } ?>
<? if($TipoTercero=="Persona Natural"){?><td>Prim Apellido</td><td>Seg Apellido</td><td>Prim Nombre</td><td>Seg Nombre</td><?}?>
<td colspan="3">&nbsp;</td>
</tr>
<tr>
<td>
<select name="TipoTercero" onChange="location.href='EncabTerceros.php?DatNameSID=<? echo $DatNameSID?>&TipoTercero='+this.value+'&ModOrigen=<?echo $ModOrigen?>'">
<?
	$cons="Select Tipo from Central.TiposPersonas";
	$res=ExQuery($cons);
	while($fila=ExFetch($res))
	{
		if($TipoTercero==$fila[0]){echo "<option selected value='$fila[0]'>$fila[0]</option>";}
		else{echo "<option value='$fila[0]'>$fila[0]</option>";}
	}
?>
</select>
</td>
<td><input type="Text" name="Identificacion" style="width:120px;"></td>
<? if($TipoTercero=="Persona Juridica"){?><td><input type="Text" name="PrimApe" style="width:260px;"></td><?}?>
<? if($TipoTercero=="Persona Natural"){?>
<td><input type="Text" name="PrimApe" style="width:90px;"></td>
<td><input type="Text" name="SegApe" style="width:90px;"></td>
<td><input type="Text" name="PrimNom" style="width:90px;"></td>
<td><input type="Text" name="SegNom" style="width:90px;"></td>

<? }?>


<td> <input type="Submit" value="Buscar"></td>
<input type='Hidden' name='ModOrigen' value="<? echo $ModOrigen?>">
<input type="hidden" name="DatNameSID" value="<? echo $DatNameSID?>">
<td><input type="Button" value="Nuevo" <? if($ModOrigen == "Consumo"||$ModOrigen=="ICALiq"){ echo " disabled ";}?>onClick="parent(1).location.href='NuevoTercero.php?DatNameSID=<? echo $DatNameSID?>&ModOrigen=<? echo $ModOrigen?>'"></td>
</tr>
</table>
</form>
<hr>