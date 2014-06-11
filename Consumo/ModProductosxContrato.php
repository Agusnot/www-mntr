<?
	if($DatNameSID){session_name("$DatNameSID");}
	session_start();
	include("Funciones.php");
	$ND = getdate();
	if($Eliminar)
	{
		$cons1="Delete from Consumo.ProductosxContrato where AutoId='$AutoId' and AlmacenPpal='$AlmacenPpal' and Compania = '$Compania[0]'";
		$res1 = ExQuery($cons1);
		$Eliminar = 0;
	}
	if($Guardar)
	{
		$cons = "Insert into Consumo.ProductosxContrato (Compania,AlmacenPpal,NumeroContrato,AutoId,Cantidad,ValorUnidad,Anio)
		values ('$Compania[0]','$AlmacenPpal','$Numero','$AutoId','$Cantidad','$ValorUnidad',$ND[year])";
		$res = ExQuery($cons);
	}
?>
<script language="javascript" src="/Funciones.js"></script>
<script language="javascript">
	function Validar()
	{
		var b=0;
		if (document.FORMA.Codigo.value	== ""){alert("Falta Llenar el campo Codigo"); b = 1;}
		else{if(document.FORMA.Nombre.value	== ""){alert("Falta Llenar el campo Nombre"); b = 1;}
			else{if(document.FORMA.Cantidad.value == ""){alert("Falta Llenar el campo Cantidad"); b = 1;}
				else{if(document.FORMA.ValorUnidad.value == ""){alert("Falta Llenar el campo Valor"); b = 1;}}}}
		if (b == 1)
		{
			return false;	
		}
	}
</script>
<body background="/Imgs/Fondo.jpg">
<form name="FORMA" method="post" onSubmit="return Validar()">
<input type="hidden" name="DatNameSID" value="<? echo $DatNameSID?>" />
<table border="1" bordercolor='#e5e5e5' style='font : normal normal small-caps 12px Tahoma;' width="100%">
	<tr align="center" style="font-weight:bold" bgcolor="#e5e5e5">
    	<td width="10%">Codigo</td><td>Nombre</td><td width="10%">Cantidad</td><td width="10%">Valor Unidad</td>
    </tr>
<?
	$cons = "Select Codigo1,NombreProd1,UnidadMedida,Presentacion,
	ProductosxContrato.Cantidad,ProductosxContrato.ValorUnidad, ProductosxContrato.AutoId 
	from Consumo.CodProductos, Consumo.ProductosxContrato
	where ProductosxContrato.AutoId = CodProductos.AutoId and ProductosxContrato.Compania='$Compania[0]' and CodProductos.Compania='$Compania[0]' 
	and ProductosxContrato.AlmacenPpal='$AlmacenPpal' and CodProductos.AlmacenPpal = '$AlmacenPpal'
	and ProductosxContrato.AlmacenPpal = CodProductos.AlmacenPpal
	and ProductosxContrato.NumeroContrato = '$Numero' and CodProductos.Anio=$ND[year] and ProductosxContrato.Anio=$ND[year]";
	$res = ExQuery($cons);
	while($fila=ExFetch($res))
	{
		echo "<tr><td>$fila[0]</td><td>$fila[1] $fila[2] $fila[3]</td><td>$fila[4]</td><td>$fila[5]</td>";
		?><td>
		<a href="#"  
  		onclick="if(confirm('Desea eliminar el Producto del Contrato?'))
        {location.href='ModProductosxContrato.php?DatNameSID=<? echo $DatNameSID?>&Eliminar=1&AlmacenPpal=<? echo $AlmacenPpal?>&Numero=<? echo $Numero?>&AutoId=<? echo $fila[6]?>'}">
		<img border="0" title="Eliminar" src="/Imgs/b_drop.png"/></a></td></tr><?
	}
?>

	<tr>
    	<td align="center"><input type="text" name="Codigo" id="Codigo" readonly size="10" /></td>
        <td><input type="text" name="Nombre" id="Nombre" style="width:100%" 
        onFocus="frames.parent.Mostrar();frames.parent.Busquedas.location.href='Busquedas.php?DatNameSID=<? echo $DatNameSID?>&Anio=<? echo $ND[year];?>&Fecha=<? echo "$ND[year]-$ND[mon]-$ND[mday]";?>&Tipo=NombreProducto&NomProducto='+this.value+'&Objeto=Nombre&AlmacenPpal=<? echo $AlmacenPpal?>';" 
		onkeyup="FORMA.Codigo.value='';xLetra(this);
        frames.parent.Busquedas.location.href='Busquedas.php?DatNameSID=<? echo $DatNameSID?>&Anio=<? echo $ND[year];?>&Fecha=<? echo "$ND[year]-$ND[mon]-$ND[mday]";?>&Tipo=NombreProducto&NomProducto='+this.value+'&Objeto=Nombre&AlmacenPpal=<? echo $AlmacenPpal?>';"
        onKeyDown="xLetra(this)"/></td>
        <td align="center"><input type="text" name="Cantidad" size="6" onFocus="frames.parent.Ocultar()"
        onKeyUp="xNumero(this)" onKeyDown="xNumero(this)" onBlur="campoNumero(this)" /></td>
        <td align="center"><input type="text" name="ValorUnidad" size="6" onFocus="frames.parent.Ocultar()" 
        onKeyUp="xNumero(this)" onKeyDown="xNumero(this)" onBlur="campoNumero(this)"/></td>
        <td width="5%"><button type="submit" name="Guardar"><img src="/Imgs/b_save.png" title="Guardar"></button></td>
    </tr>
</table>
<input type="Hidden" name="AutoId"/>
<input type="Hidden" name="AlmacenPpal" value="<? echo $AlmacenPpal?>" />
<input type="Hidden" name="Numero" value="<? echo $Numero?>" />
<input type="Hidden" name="Eliminar" value="<? echo $Eliminar?>" />
</form>
</body>