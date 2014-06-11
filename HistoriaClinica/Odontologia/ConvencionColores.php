<?
	if($DatNameSID){session_name("$DatNameSID");}
	session_start();
	include("Funciones.php");
	if($Eliminar)
	{
		$cons="Delete from Odontologia.ColorConvenciones where Compania='$Compania[0]' and Color='#$color'";		
		$res=ExQuery($cons);	
	}
?> 
<body background="/Imgs/Fondo.jpg">
<form name="FORMA" method="post">
<input type="hidden" name="DatNameSID" value="<? echo $DatNameSID?>"/>
<table border="1" bordercolor="#e5e5e5" style='font : normal normal small-caps 12px Tahoma;'>
<tr bgcolor="#e5e5e5" style="font-weight:bold"><td colspan="5" align="center">Convencion de Colores Odontograma</td></tr>
<tr bgcolor="#e5e5e5" style="font-weight:bold"><td colspan="2">Color - Nombre</td><td>Descripcion</td><td colspan="2"></td></tr>
<? 
$cons="Select color,ncolor,ruta,descripcion from odontologia.colorconvenciones where compania='$Compania[0]' order by ncolor";
$res=ExQuery($cons);
while($fila=ExFetch($res))
{	
?>
<tr onMouseOver="this.bgColor='#AAD4FF'" onMouseOut="this.bgColor=''">
<td><img src="<? echo $fila[2]?>" style="border:thin" height="25" width="25"></td>
<td><? echo $fila[1]?></td>
<td><? echo $fila[3]?></td>
<?
if($fila[1]=="BLANCO")
{?>
	<td width="16px"><a href="NuevoColor.php?DatNameSID=<? echo $DatNameSID?>&Editar=1&color=<? echo substr($fila[0],1);?>"><img src="/Imgs/b_edit.png" border="0" title="Editar" /></a>
</td>
<td>&nbsp;</td>
<?
}
else
{?>
<td width="16px"><a href="NuevoColor.php?DatNameSID=<? echo $DatNameSID?>&Editar=1&color=<? echo substr($fila[0],1);?>"><img src="/Imgs/b_edit.png" border="0" title="Editar" /></a>
</td>
<td width="16px"><a href="#" onClick="if(confirm('Desea Eliminar el Color Seleccionado?')){location.href='ConvencionColores.php?DatNameSID=<? echo $DatNameSID?>&Eliminar=1&color=<? echo substr($fila[0],1);?>'}"><img src="/Imgs/b_drop.png" border="0" title="Eliminar"/></a>
</td>
<?
}
?>
</tr>
<?	
}
?>
</table>
<input type="button" name="Nuevo" value="Nuevo" onClick="location.href='NuevoColor.php?DatNameSID=<? echo $DatNameSID?>'"/>
</form>
</body>