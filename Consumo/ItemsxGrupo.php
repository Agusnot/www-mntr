<?php
	if($DatNameSID){session_name("$DatNameSID");}
	session_start();
	include("Funciones.php");
	if($Guardar)
	{
		$Item = trim($Item);
		if($Item!="")
		{
			$cons = "Insert into Consumo.ItemsxGrupo (Compania,AlmacenPpal,Grupo,Item,Anio) values
					('$Compania[0]','$AlmacenPpal','$Grupo','$Item','$Anio')";
			$res = ExQuery($cons);
			$Eliminar = 0;
		}
	}
	if($Eliminar)
	{
		$cons = "Delete from Consumo.ItemsxGrupo where Item = '$Item' and AlmacenPpal = '$AlmacenPpal' and Grupo = '$Grupo' and Anio='$Anio'";
		$res = ExQuery($cons);
		$Eliminar = 1;
	}
?>
<body background="/Imgs/Fondo.jpg">
<form name="FORMA" method="post">
<input type="hidden" name="DatNameSID" value="<? echo $DatNameSID?>" />
<table style='font : normal normal small-caps 12px Tahoma;' border="1" width="100%" bordercolor="#e5e5e5">
<input type="Hidden" name="AlmacenPpal" value="<? echo $AlmacenPpal?>" />
<input type="Hidden" name="Grupo" value="<? echo $Grupo?>" />
<input type="hidden" name="Anio" value="<? echo $Anio?>" />
<input type="Hidden" name="Eliminar" value="<? echo $Eliminar?>" />
<?
	$cons = "Select Item from Consumo.ItemsxGrupo where Compania = '$Compania[0]' and AlmacenPpal='$AlmacenPpal' and Grupo = '$Grupo' and Anio='$Anio' order by Item";
	$res = ExQuery($cons);
	echo "<tr bgcolor='#e5e5e5'><td align='center'>Item</td></tr>";
	while($fila = ExFetch($res))
	{
		echo "<tr><td>$fila[0]</td>";
		?>  <td width="20px"><a href="#"  onclick="if(confirm('Desea eliminar el registro?'))
            {location.href='ItemsxGrupo.php?DatNameSID=<? echo $DatNameSID?>&Anio=<? echo $Anio?>&AlmacenPpal=<? echo $AlmacenPpal?>&Grupo=<? echo $Grupo?>&Eliminar=1&Item=<? echo $fila[0];?>'}">
			<img title="Eliminar" border="0" src="/Imgs/b_drop.png"/></a></td></tr>
         <?
	}
?>
<tr>
	<td><input type="text" name="Item" style="width:100%" /></td>
	<td width="20px"><button type="submit" name="Guardar"><img src="/Imgs/b_save.png" title="Guardar"></button></td>
</tr>
</table>
</form>
</body>
