<?
	if($DatNameSID){session_name("$DatNameSID");}
	session_start();
	include("Funciones.php");
	if($Eliminar)
	{
		$cons = "Delete from Infraestructura.SubUbicaciones Where Compania='$Compania[0]' and CC='$CC' and SubUbicacion = '$SubUbicacion'";
		$res = ExQuery($cons);		
	}
	if($Agregar)
	{
		if($SubUbicacion)
		{
			$cons = "Insert into Infraestructura.SubUbicaciones (Compania,CC,SubUbicacion) values ('$Compania[0]','$CC','$SubUbicacion')";
			$res = ExQuery($cons);
		}	
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
	}
</script>
<body background="/Imgs/Fondo.jpg">
<form name="FORMA" method="post">
<input type="hidden" name="DatNameSID" value="<? echo $DatNameSID?>" />
<input type="hidden" name="CC" value="<? echo $CC;?>" />
<input type="hidden" name="CentroCostos" value="<? echo $CentroCostos;?>" />
<div align="right">
<button name="Cerrar" title="Cerrar" onClick="CerrarThis()"><img src="/Imgs/b_drop.png" /></button>
</div>
<table border="1" bordercolor="#e5e5e5" width="100%" style="font-family:<? echo $Estilo[8]?>;font-size:12;font-style:<? echo $Estilo[10]?>">
	<tr bgcolor="#e5e5e5" style="font-weight:bold"><td>Sub Ubicaciones para <? echo "$CC - $CentroCostos";?></td><td>&nbsp;</td></tr>
    <tr>
    	<td><input type="text" name="SubUbicacion" style="width:100%" onKeyUp="xLetra(this)" onKeyDown="xLetra(this)" /></td>
        <td><button type="submit" name="Agregar" title="Agregar"><img src="/Imgs/b_save.png" /></button></td>
    </tr>
    <?
    $cons = "Select SubUbicacion from Infraestructura.SubUbicaciones Where Compania='$Compania[0]' and CC='$CC' and SubUbicacion != '-'";
	$res = ExQuery($cons);
	while($fila = ExFetch($res))
	{
		?>
		<tr onMouseOver="this.bgColor='#AAD4FF'" onMouseOut="this.bgColor='#FFFFFF'"><td><? echo $fila[0]?></td>
        <td><img src="/Imgs/b_drop.png" style="cursor:hand" 
        onclick="if(confirm('Desea Eliminar este registro?')){location.href='SubUbicacionesxCC.php?DatNameSID=<? echo $DatNameSID?>&Eliminar=1&CC=<? echo $CC?>&CentroCostos=<? echo $CentroCostos?>&SubUbicacion=<? echo $fila[0]?>';}"
        /></td></tr>
		<?	
	}
	?>
</table>
</form>
</body>