<?
	if($DatNameSID){session_name("$DatNameSID");}
	session_start();
	include("Funciones.php");
	if(!$AlmacenPpal)
	{
		$cons = "Select AlmacenPpal from Consumo.UsuariosxAlmacenes where Usuario='$usuario[0]' and Compania='$Compania[0]'";
		$res = ExQuery($cons);
		$fila = ExFetch($res);
		$AlmacenPpal = $fila[0];		
	}
	if($Eliminar)
	{
		$cons = "Delete from Consumo.ProductosxContrato where NumeroContrato = '$Numero'";
		$res = ExQuery($cons);
		$cons = "Delete from Consumo.Contratos where Numero = '$Numero'";
		$res = ExQuery($cons);
	}
	if($Nuevo)
	{
		?><script language="javascript">
        	location.href = "NewContrato.php?DatNameSID=<? echo $DatNameSID?>&AlmacenPpal=<? echo $AlmacenPpal?>";
        </script><?	
	}
?>
<body background="/Imgs/Fondo.jpg">
<form name="FORMA" method="post">
<input type="hidden" name="DatNameSID" value="<? echo $DatNameSID?>" />
<select name="AlmacenPpal" onChange="document.FORMA.submit();">
<?
		$cons = "Select AlmacenPpal from Consumo.UsuariosxAlmacenes where Usuario='$usuario[0]' and Compania='$Compania[0]'";
		$res = ExQuery($cons);
		while($fila = ExFetch($res))
		{
			if($AlmacenPpal==$fila[0]){echo "<option selected value='$fila[0]'>$fila[0]</option>";}
			else{echo "<option value='$fila[0]'>$fila[0]</option>";}
		}
?>
</select>
<?
	if($AlmacenPpal)
	{
		$cons1 = "Select PrimApe,SegApe,PrimNom,SegNom from Central.Terceros where Identificacion = '$Cedula' and Compania='$Compania[0]'";
		$res1 = ExQuery($cons1);
		$fila1 = ExFetch($res1);
		$Tercero = "$fila1[0] $fila1[1] $fila1[2] $fila1[3]";
		
		$cons = "Select  Numero,Proveedor,FechaInicio,FechaFin,Valor,Contacto,Contratos.Telefono,Observaciones,PrimApe,SegApe,PrimNom,SegNom
		from Consumo.Contratos, Central.Terceros where Contratos.Proveedor = Terceros.Identificacion
		and Contratos.Compania = '$Compania[0]' and Terceros.Compania='$Compania[0]' and AlmacenPpal = '$AlmacenPpal'";
		//echo $cons;
		$res = ExQuery($cons);
		echo "<table style='font : normal normal small-caps 12px Tahoma;' border='1' bordercolor='#e5e5e5' width='100%'>
		<tr bgcolor='#e5e5e5' align='center' style='font-weight:bold'>
		<td>Numero</td><td>Proveedor</td><td>FechaInicio</td><td>FechaFin</td><td>Valor</td><td>Contacto</td><td>Telefono</td><td>Observaciones</td></tr>";
		while($fila = ExFetch($res))
		{
			echo "<tr><td>$fila[0]</td><td>$fila[10] $fila[11] $fila[8] $fila[9]</td><td>$fila[2]</td><td>$fila[3]</td><td>$fila[4]</td>
			<td>$fila[5]</td><td>$fila[6]</td><td>$fila[7]</td>";
			?>
            <td width="20px">
			<a href="NewContrato.php?DatNameSID=<? echo $DatNameSID?>&Editar=1&AlmacenPpal=<? echo $AlmacenPpal?>&NumeroContrato=<? echo $fila[0]?>&Cedula=<? echo $fila[1]?>">
			<img border="0" title="Editar" src="/Imgs/b_edit.png"/></a></td>
            <td width="20px">
			<a href="#"  
  			onclick="if(confirm('Desea eliminar el Registro?'))
        	{location.href='Contratos.php?DatNameSID=<? echo $DatNameSID?>&Eliminar=1&AlmacenPpal=<? echo $AlmacenPpal?>&Numero=<? echo $fila[0]?>'}">
			<img border="0" title="Eliminar" src="/Imgs/b_drop.png"/></a></td></tr><?
		}
		?>
        </table>
        <input type="submit" name="Nuevo" value="Nuevo"/><?	
	}
?>
</form>
</body>
