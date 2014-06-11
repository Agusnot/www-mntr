<?
	if($DatNameSID){session_name("$DatNameSID");}
	session_start();
	include("Funciones.php");
?>
	<table border="0" width="100%"><tr><td align="right">
    	<img src="/Imgs/b_drop.png" onclick="parent.Ocultar()" title="Cerrar" style="cursor:hand" />
    </td></tr></table>
<?
	if($Tipo=="CC")
	{
		if($AutoId){$ConAutoId=" and AutoId = $AutoId ";}
		?>
        <script language="javascript">
        	function PonerCC(CC,Nombre)
			{
				parent.document.FORMA.CC.value=CC;
				parent.document.FORMA.Nombre.value=Nombre;
             	parent.document.FORMA.submit();
			}
        </script>
		<table bgcolor="#FFFFEE" border="0" style='font : normal normal small-caps 11px Tahoma;' width="100%">
		<tr style="font-weight:bold" align="center" bgcolor="<? echo $Estilo[1]?>"><td colspan="2">
        <font color="#FFFFFF">BUSQUEDA POR CENTRO DE COSTOS</font></td></tr>
        <tr><td colspan="2">Criterio: <strong><? echo $CC?></strong></td></tr>
		<?
		$cons ="Select Movimiento.CentroCosto,CentrosCosto.CentroCostos from Consumo.Movimiento,Central.CentrosCosto 
		Where Movimiento.Compania='$Compania[0]' and CentrosCosto.Compania='$Compania[0]' and Movimiento.CentroCosto like '%$CC' and Movimiento.Anio=$Anio
		and CentrosCosto.Anio=$Anio and CentrosCosto.Codigo=Movimiento.CentroCosto and Movimiento.Estado = 'AC' 
		and Fecha >= '$FechaIni' and Fecha <= '$FechaFin' $ConAutoId 
		Group by Movimiento.CentroCosto,CentrosCosto.CentroCostos order by CentrosCosto.CentroCostos";
		//echo $cons;
		$res = ExQuery($cons);
		if(ExNumRows($res)>0)
		{
			?><tr style="font-weight:bold" bgcolor="<? echo $Estilo[1]?>"><td><font color="#FFFFFF">CC</font></td>
            <td><font color="#FFFFFF">Nombre</font></td></tr><?
		}
		while($fila = ExFetch($res))
		{
			?><tr onMouseOver="this.bgColor='#AAD4FF'" onMouseOut="this.bgColor=''" style="cursor:hand"
             onclick="PonerCC('<? echo $fila[0]?>','<? echo $fila[1]?>')"><?
			echo "<td>$fila[0]</td><td>$fila[1]</td></tr>";
		}
	}
	if($Tipo=="Terceros")
	{
		if($AutoId){ $ConAutoId=" and AutoId = $AutoId ";}
		?>
        <script language="javascript">
        	function PonerTercero(Id,Nombre)
			{
				parent.document.FORMA.Cedula.value=Id;
				parent.document.FORMA.Tercero.value=Nombre;
             	parent.document.FORMA.submit();
			}
        </script>
        <table bgcolor="#FFFFEE" border="0" style='font : normal normal small-caps 11px Tahoma;' width="100%">
		<tr style="font-weight:bold" align="center" bgcolor="<? echo $Estilo[1]?>"><td colspan="2">
        <font color="#FFFFFF">BUSQUEDA POR TERCEROS</font></td></tr>
        <tr><td colspan="2">Criterio: <strong><? echo $Tercero?></strong></td></tr>
		<?
		$cons = "Select PrimApe,SegApe,PrimNom,SegNom,Identificacion from Central.Terceros where Compania = '$Compania[0]'
		and (PrimApe || ' ' || SegApe || ' ' || PrimNom || ' ' || SegNom) ilike '%$Tercero%' 
		and Identificacion in(Select Cedula from Consumo.Movimiento where Compania='$Compania[0]' and TipoComprobante='Salidas' 
		and TipoComprobante='Salidas' and Estado='AC' and Anio=$Anio $ConAutoId )
		order by PrimApe,SegApe,PrimNom,SegNom";
		$res = ExQuery($cons);
		if(ExNumRows($res)>0)
		{
			?><tr style="font-weight:bold" bgcolor="<? echo $Estilo[1]?>"><td><font color="#FFFFFF">ID</font></td>
            <td><font color="#FFFFFF">TERCERO</font></td></tr><?
		}
		while($fila = ExFetch($res))
		{
			?><tr onMouseOver="this.bgColor='#AAD4FF'" onMouseOut="this.bgColor=''" style="cursor:hand"
             onclick="PonerTercero('<? echo $fila[4]?>','<? echo "$fila[0] $fila[1] $fila[2] $fila[3]"?>')"><?
			echo "<td>$fila[4]</td><td>$fila[0] $fila[1] $fila[2] $fila[3]</td></tr>";
		}
	}
	if($Tipo=="Productos")
	{
		if($Cedula){ $ConCedula = " and Cedula = '$Cedula' ";}
		if($CC){$ConCC = "and CentroCosto = '$CC'";}
		?>
        <script language="javascript">
        	function PonerProducto(AutoId,Producto)
			{
				parent.document.FORMA.AutoId.value=AutoId;
				parent.document.FORMA.Producto.value=Producto;
             	parent.document.FORMA.submit();
			}
        </script>
        <table bgcolor="#FFFFEE" border="0" style='font : normal normal small-caps 11px Tahoma;' width="100%">
		<tr style="font-weight:bold" align="center" bgcolor="<? echo $Estilo[1]?>"><td colspan="2">
        <font color="#FFFFFF">BUSQUEDA POR PRODUCTOS</font></td></tr>
        <tr><td colspan="2">Criterio: <strong><? echo $Producto?></strong></td></tr>
		<?
		$cons = "Select AutoId,NombreProd1,UnidadMedida,Presentacion from Consumo.CodProductos where Compania = '$Compania[0]'
		and AlmacenPpal = '$AlmacenPpal' and Anio = $Anio and (NombreProd1 || ' ' || UnidadMedida || ' ' || Presentacion) ilike '%$Producto%'
		and Anio = $Anio 
		and AutoId in(Select AutoId from Consumo.Movimiento where Compania='$Compania[0]' and TipoComprobante='Salidas' 
		and Anio = $Anio and Fecha >= '$FechaIni' and Fecha <= '$FechaFin' $ConCedula $ConCC group by AutoId)
		order by AutoId";
		$res = ExQuery($cons);
		if(ExNumRows($res)>0)
		{
			?><tr style="font-weight:bold" bgcolor="<? echo $Estilo[1]?>"><td><font color="#FFFFFF">ID</font></td>
            <td><font color="#FFFFFF">PRODUCTO</font></td></tr><?
		}
		while($fila = ExFetch($res))
		{
			?><tr onMouseOver="this.bgColor='#AAD4FF'" onMouseOut="this.bgColor=''" style="cursor:hand"
             onclick="PonerProducto('<? echo $fila[0]?>','<? echo "$fila[1] $fila[2] $fila[3]"?>')"><?
			echo "<td>$fila[0]</td><td>$fila[1] $fila[2] $fila[3]</td></tr>";
		}
	}
        if($Tipo=="Grupos")
        {
            if($Cedula){ $ConCedula = " and Cedula = '$Cedula' ";}
            if($CC){$ConCC = "and CentroCosto = '$CC'";}
            ?>
            <script language="javascript">
        	function PonerGrupo(Grupo)
                {
                    parent.document.FORMA.Grupo.value=Grupo;
                    parent.document.FORMA.submit();
                }
            </script>
            <table bgcolor="#FFFFEE" border="0" style='font : normal normal small-caps 11px Tahoma;' width="100%">
		<tr style="font-weight:bold" align="center" bgcolor="<? echo $Estilo[1]?>"><td colspan="2">
                    <font color="#FFFFFF">BUSQUEDA POR PRODUCTOS</font></td></tr>
                <tr><td colspan="2">Criterio: <strong><? echo $Grupo?></strong></td></tr>
            <?
            $cons = "Select Grupo from Consumo.CodProductos where Compania = '$Compania[0]'
            and AlmacenPpal = '$AlmacenPpal' and Anio = $Anio and Grupo ilike '%$Grupo%'
            and Anio = $Anio
            and Grupo in(Select Grupo from Consumo.Movimiento where Compania='$Compania[0]' and TipoComprobante='Salidas'
            and Anio = $Anio and Fecha >= '$FechaIni' and Fecha <= '$FechaFin' $ConCedula $ConCC group by Grupo)
            Group by Grupo order by Grupo";
            $res = ExQuery($cons);
            if(ExNumRows($res)>0)
            {
                    ?><tr style="font-weight:bold" bgcolor="<? echo $Estilo[1]?>">
                        <td colspan="2"><font color="#FFFFFF">GRUPO</font></td></tr><?
            }
            while($fila = ExFetch($res))
            {
                    ?><tr onMouseOver="this.bgColor='#AAD4FF'" onMouseOut="this.bgColor=''" style="cursor:hand"
                        onclick="PonerGrupo('<? echo $fila[0]?>')"><?
                    echo "<td>$fila[0]</td></tr>";
            }
        }
?>