<?
	if($DatNameSID){session_name("$DatNameSID");}
	session_start();
	include ("Funciones.php");
	if(!$AlmacenPpal)
	{
		$cons = "Select AlmacenPpal from Consumo.UsuariosxAlmacenes where Usuario='$usuario[0]' and Compania='$Compania[0]'";
		$res = ExQuery($cons);
		$fila = ExFetch($res);
		$AlmacenPpal = $fila[0];		
	}
	if($AsignaDef)
	{
		$cons1="Update Consumo.TarifariosVenta set xDefecto='NO' where AlmacenPpal='$AlmacenPpal' and Compania='$Compania[0]'";
		$res1=ExQuery($cons1);
		
		$cons = "Update Consumo.TarifariosVenta set xDefecto='SI' where Tarifario='$Tarifario' and AlmacenPpal='$AlmacenPpal' and Compania='$Compania[0]'";
		$res = ExQuery($cons);
		$AsignaDef=0;
		echo ExError();
	}
	if($Eliminar)
	{
            $cons = "Select * from Consumo.TarifasxProducto where compania='$Compania[0]' and AlmacenPpal='$AlmacenPpal' and Tarifario='$Tarifario'";
            $res = ExQuery($cons);
            if(ExNumRows($res)>0)
            {
                $MensajeElim="No se puede eliminar tarifario. Aun se encuentra referenciado desde TarifasxProducto";
            }
            if(!$MensajeElim)
            {
                $cons = "Delete from Consumo.TarifariosVenta where Tarifario='$Tarifario' and AlmacenPpal = '$AlmacenPpal' and Compania = '$Compania[0]'";
                $res = ExQuery($cons);
            }
        }
?>
<script language="javascript">
	function Validar()
	{
		document.FORMA.action = "NewConfTarifarioVenta.php";
		document.FORMA.submit();
	}
</script>
<body background="/Imgs/Fondo.jpg">
<form method="post" name="FORMA" >
<input type="hidden" name="DatNameSID" value="<? echo $DatNameSID?>" />
<input type="Hidden" name="Tabla" value="<? echo $Tabla; ?>"  />
<input type="Hidden" name="Campo" value="<? echo $Campo; ?>"  />
<select name="AlmacenPpal" onChange="document.FORMA.submit()">
        	<?
            $cons = "Select AlmacenPpal from Consumo.UsuariosxAlmacenes where Usuario='$usuario[0]' and Compania='$Compania[0]'";
			$res = ExQuery($cons);
			echo ExError();
			while($fila=ExFetch($res))
			{
				if($AlmacenPpal==$fila[0]){echo "<option selected value='$fila[0]'>$fila[0]</option>";}
				else{echo "<option value='$fila[0]'>$fila[0]</option>";}
			}
			?>
</select>
<?
	if($AlmacenPpal)
	{
		$cons="Select Tarifario,Estado,xDefecto from Consumo.TarifariosVenta where Compania='$Compania[0]' and AlmacenPpal='$AlmacenPpal' order by tarifario";
		$res = ExQuery($cons);
		echo ExError();
		echo "<table width='600px' style='font : normal normal small-caps 12px Tahoma;' border='1' bordercolor='#e5e5e5'>
			<tr style='font-weight:bold' width='280px' bgcolor='#e5e5e5' align='center'>
			<td>Nombre Tarifario</td><td>Estado</td><td>Tarifario xDefecto</td></tr>";
		while($fila=ExFetch($res))
		{
			if($fila[2]=="SI"){$Checked=" checked ";}else{$Checked="";}
			echo "<tr><td>$fila[0]</td>";
			if($fila[1]=='AC'){ echo "<td>Activo</td>"; $Desabilitar = "";}else {echo "<td>Inactivo</td>"; $Desabilitar = " disabled ";}
			?>
            <td align="center"><input type="radio" <? echo " $Checked ";?> name="xDefecto"  <? echo $Desabilitar?> 
            onClick="location.href='ConfTarifariosVenta.php?DatNameSID=<? echo $DatNameSID?>&AlmacenPpal=<? echo $AlmacenPpal;?>&Tarifario=<? echo $fila[0];?>&AsignaDef=1'" /></td>
            <td width="20px">
            <a href="NewConfTarifarioVenta.php?DatNameSID=<? echo $DatNameSID?>&Editar=1&AlmacenPpal=<? echo $AlmacenPpal;?>&Tarifario=<? echo $fila[0];?>"><img border="0" src="/Imgs/b_edit.png" /></a>
            </td>
			<td width="20px"><a href="#" onClick="if(confirm('Desea eliminar el registro?'))
            {location.href='ConfTarifariosVenta.php?DatNameSID=<? echo $DatNameSID?>&Eliminar=1&AlmacenPpal=<? echo $AlmacenPpal;?>&Tarifario=<? echo $fila[0];?>';}">
			<img border="0" src="/Imgs/b_drop.png"/></a></td></tr>
            <?		
		}
?>
		</table>
		<input type="hidden" name="AsignaDef" value="0" />
		<input type="button" name="Nuevo" value="Nuevo" onClick="Validar()"  />
		</form>	
	<? }
        if($MensajeElim)
        {
            ?><script language="javascript">
                alert("<? echo $MensajeElim?>");
            </script><?
        }?>
</body>