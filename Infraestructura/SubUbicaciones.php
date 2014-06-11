<?
	if($DatNameSID){session_name("$DatNameSID");}
	session_start();
	include("Funciones.php");
	if($Eliminar)
	{
		$cons = "Select SubUbicacion from Infraestructura.SubUbicaciones Where CC='$CC' and Compania='$Compania[0]' and SubUbicacion != '-'";
		$res = ExQuery($cons);
		if(ExNumRows($res)>0)
		{
			?><script language="javascript">alert("Asegurese de eliminar las sububicaciones antes de eliminar el centro de costos");</script><?	
		}
		else
		{
			$cons = "Delete from Infraestructura.SubUbicaciones Where CC='$CC' and Compania='$Compania[0]'";
			$res = ExQuery($cons);	
		}	
	}
?>
<script language="javascript">
	function Mostrar()
	{
		document.getElementById('Busquedas').style.position='absolute';
		document.getElementById('Busquedas').style.top='110px';
		document.getElementById('Busquedas').style.right='10px';
		document.getElementById('Busquedas').style.display='';
	}
	function Ocultar()
	{
		document.getElementById('Busquedas').style.display='none';
	}
	function AbrirSubUbicaiones(CC,CentroCostos)
	{
		St = document.body.scrollTop;
		frames.FrameOpener.location.href="SubUbicacionesxCC.php?DatNameSID=<? echo $DatNameSID?>&CC="+CC+"&CentroCostos="+CentroCostos;
		document.getElementById('FrameOpener').style.position='absolute';
		document.getElementById('FrameOpener').style.top=St + 20;
		document.getElementById('FrameOpener').style.left='8px';
		document.getElementById('FrameOpener').style.display='';
		document.getElementById('FrameOpener').style.width='400';
		document.getElementById('FrameOpener').style.height='400';
	}
</script>
<?
	$ND = getdate();
	if($Agregar)
	{
		$cons = "Select Codigo from Central.CentrosCosto Where Codigo='$CC' 
		and Codigo not in(Select CC from Infraestructura.SubUbicaciones Group by CC) 
		and Compania='$Compania[0]' and Anio=$ND[year] and Tipo = 'Detalle'";
		$res = ExQuery($cons);
		if(ExNumRows($res)>0)
		{
			$cons = "Insert into Infraestructura.SubUbicaciones(Compania,CC,SubUbicacion) values ('$Compania[0]','$CC','-')";
			$res = ExQuery($cons);
			?><script language="javascript">Ocultar();</script><?
		}
		else
		{?><script language="javascript">alert("No se inserto el Centro de Costos");</script><? }	
	}
?>
<body background="/Imgs/Fondo.jpg">
<form name="FORMA" method="post">
<input type="hidden" name="DatNameSID" value="<? echo $DatNameSID?>" />
<table border="1" bordercolor="#e5e5e5" style="font-family:<? echo $Estilo[8]?>;font-size:12;font-style:<? echo $Estilo[10]?>" width="30%">
<tr bgcolor="#e5e5e5" style="font-weight:bold"><td align="center">Centro de Costos</td><td width="2%" colspan="2">&nbsp;</td></tr>
<tr><td><input type="text" name="CC" style="width:100%;text-align:right;" 
onFocus="Mostrar();
frames.Busquedas.location.href='Busquedas.php?DatNameSID=<? echo $DatNameSID?>&Centro='+this.value+'&Tipo=CCG&Anio=<? echo $ND[year]?>';"
onkeyup="frames.Busquedas.location.href='Busquedas.php?DatNameSID=<? echo $DatNameSID?>&Centro='+this.value+'&Tipo=CCG&Anio=<? echo $ND[year]?>';
xNumero(this);" onKeyDown="xNumero(this)" onBlur="campoNumero(this)" /></td>
<td><button type="submit" name="Agregar" title="Agregar"><img src="/Imgs/b_save.png" /></button></td></tr>
<? $cons = "Select Codigo,CentroCostos from Central.CentrosCosto,Infraestructura.SubUbicaciones 
	Where CentrosCosto.Compania='$Compania[0]' and SubUbicaciones.Compania='$Compania[0]' and Anio=$ND[year] and CentrosCosto.Codigo = SubUbicaciones.CC
	Group by Codigo,CentroCostos Order by Codigo";
	$res = ExQuery($cons);
	while($fila = ExFetch($res))
	{
		?>
		<tr onMouseOver="this.bgColor='#AAD4FF'" onMouseOut="this.bgColor=''"><td><? echo "$fila[0] - $fila[1]";?></td>
        <td><img src="/Imgs/b_engine.png" title="Agregar SubUbicaciones" style="cursor:hand" onClick="AbrirSubUbicaiones('<? echo $fila[0]?>','<? echo $fila[1];?>')" /></td>
        <td><img src="/Imgs/b_drop.png" title="Eliminar" style="cursor:hand" 
        onclick="if(confirm('Desea Eliminar este registro?')){location.href='SubUbicaciones.php?DatNameSID=<? echo $DatNameSID?>&Eliminar=1&CC=<? echo $fila[0]?>';}" /></td>
        </tr>
		<?
	}	
?>
</table>
</form>
<iframe id="Busquedas" name="Busquedas" style="display:none;" src="Busquedas.php" frameborder="0" height="400"></iframe>
<iframe id="FrameOpener" name="FrameOpener" style="display:none" frameborder="0" height="1" style="border:#e5e5e5 ridge"></iframe>
</body>