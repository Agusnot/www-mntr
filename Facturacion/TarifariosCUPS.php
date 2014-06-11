<?
	if($DatNameSID){session_name("$DatNameSID");}
	session_start();
	include ("Funciones.php");

	if($AsignaDef)
	{
		$cons1="Update Facturacion.TarifariosCUPS set xDefecto='NO' where  Compania='$Compania[0]'";
		$res1=ExQuery($cons1);
		
		$cons = "Update Facturacion.TarifariosCUPS set xDefecto='SI' where Tarifario='$Tarifario' and Compania='$Compania[0]'";
		$res = ExQuery($cons);
		$AsignaDef=0;
		echo ExError();
	}
	if($Eliminar)
	{
		$cons = "Delete from Facturacion.TarifariosCUPS where Tarifario='$Tarifario'   and Compania = '$Compania[0]'";
		$res = ExQuery($cons);
		//echo $cons;
		//echo ExError();exit;
	}
?>
<script language="javascript">
	function Validar()
	{
		document.FORMA.action = "NewConfTarifarioCUPS.php?DatNameSID=<? echo $DatNameSID?>";
		document.FORMA.submit();
	}
</script>
<body background="/Imgs/Fondo.jpg">
<form method="post" name="FORMA" >
<input type="hidden" name="DatNameSID" value="<? echo $DatNameSID?>"/>
<input type="Hidden" name="Tabla" value="<? echo $Tabla; ?>"  />
<input type="Hidden" name="Campo" value="<? echo $Campo; ?>"  />
<?
	$cons="Select Tarifario,Estado,xDefecto from Facturacion.TarifariosCUPS where Compania='$Compania[0]' ";
			$res = ExQuery($cons);
			echo ExError();
			echo "<table width='600px' style='font : normal normal small-caps 12px Tahoma;' border='1' bordercolor='#e5e5e5'>
				<tr style='font-weight:bold' width='280px' bgcolor='#e5e5e5' align='center'>
				<td>Nombre Tarifario</td><td>Estado</td><td>Tarifario xDefecto</td></tr>";
			while($fila=ExFetch($res))
			{
				if($fila[2]=="SI"){$Checked=" checked ";}else{$Checked="";}
				echo "<tr><td>$fila[0]</td>";
				if($fila[1]=='AC'){ echo "<td>Activo</td>";}else {echo "<td>Inactivo</td>";}
				?>
                <td align="center"><input type="radio" <? echo " $Checked ";?> name="xDefecto" 
                onClick="location.href='TarifariosCUPS.php?DatNameSID=<? echo $DatNameSID?>&Tarifario=<? echo $fila[0];?>&AsignaDef=1'" /></td>
                <td width="20px">
                <a href="NewConfTarifarioCUPS.php?DatNameSID=<? echo $DatNameSID?>&Editar=1&AlmacenPpal=<? echo $AlmacenPpal;?>&Tarifario=<? echo $fila[0];?>"><img border="0" src="/Imgs/b_edit.png" /></a>
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
</body>