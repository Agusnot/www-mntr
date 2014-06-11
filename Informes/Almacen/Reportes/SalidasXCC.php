<?
	if($DatNameSID){session_name("$DatNameSID");}
	session_start();
	include("Funciones.php");
	$ND = getdate();
	$FechaIni="$Anio-$MesIni-$DiaIni";
	$FechaFin="$Anio-$MesFin-$DiaFin";
	if($CC){$conCC = "and Movimiento.CentroCosto='$CC'";}
	if($AutoId){$conAutoId = "and Movimiento.AutoId = $AutoId";}
        if($Grupo){$conGrupo = " and Movimiento.Grupo = '$Grupo'";}
	if(!$PDF)
	{
?><head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<script language="javascript" src="/Funciones.js"></script>
<script language="JavaScript">
	function Mostrar()
	{
		document.getElementById('Busquedas').style.position='absolute';
		document.getElementById('Busquedas').style.top='50px';
		document.getElementById('Busquedas').style.right='10px';
		document.getElementById('Busquedas').style.display='';
	}
	function Ocultar()
	{
		document.getElementById('Busquedas').style.display='none';
	}
</script>
</head>

<style>
P{PAGE-BREAK-AFTER: always;}
</style>
<body>
<form name="FORMA" method="post">
<input type="hidden" name="Anio" value="<? echo $Anio?>" />
<input type="hidden" name="MesIni" value="<? echo $MesIni?>" />
<input type="hidden" name="DiaIni" value="<? echo $DiaIni?>" />
<input type="hidden" name="MesFin" value="<? echo $MesFin?>" />
<input type="hidden" name="DiaFin" value="<? echo $DiaFin?>" />
<input type="hidden" name="AlmacenPpal" value="<? echo $AlmacenPpal?>" />
<input type="hidden" name="Encabezados" value="<? echo $Encabezados?>" />
<input type="hidden" name="Ver" value="<? echo $Ver?>" />
<input type="hidden" name="Cedula" value="<? echo $Cedula?>" />
<input type="hidden" name="AutoId" value="<? echo $AutoId?>"  />
<input type="hidden" name="DatNameSID" value="<? echo $DatNameSID?>" />
<!--<table border="0" style='font : normal normal small-caps 11px Tahoma;' align="center">
	<tr bgcolor="#e5e5e5" style="font-weight:bold">
    	<td>CENTRO DE COSTOS:</td>
        <td><input type="text" name="CC" onblur="campoNumero(this)" value="<? echo $CC?>" size="60" style="text-align:right; width:150px"
        onfocus="Mostrar();document.frames.Busquedas.location.href='Busquedas.php?FechaIni=<? echo $FechaIni?>&FechaFin=<? echo $FechaFin?>&AutoId='+FORMA.AutoId.value+'&Anio=<? echo $Anio?>&Tipo=CC&CC='+this.value"
        onkeyup="Mostrar();xNumero(this);Nombre.value='';
        document.frames.Busquedas.location.href='Busquedas.php?FechaIni=<? echo $FechaIni?>&FechaFin=<? echo $FechaFin?>&AutoId='+FORMA.AutoId.value+'&Anio=<? echo $Anio?>&Tipo=CC&CC='+this.value;
        if(this.value==''){FORMA.Cedula.value='';FORMA.submit();}" onkeydown="xNumero(this)" />
        <input type="text" name="Nombre" readonly value="<? echo $Nombre?>" />
        </td>
        <td>PRODUCTO:</td>
        <td><input type="text" name="Producto" value="<? echo $Producto?>" size="60" 
        onfocus="Mostrar();document.frames.Busquedas.location.href='Busquedas.php?FechaIni=<? echo $FechaIni?>&FechaFin=<? echo $FechaFin?>&CC='+FORMA.CC.value+'&AlmacenPpal=<? echo $AlmacenPpal?>&Anio=<? echo $Anio?>&Tipo=Productos&Producto='+this.value"
        onkeyup="Mostrar();document.frames.Busquedas.location.href='Busquedas.php?FechaIni=<? echo $FechaIni?>&FechaFin=<? echo $FechaFin?>&CC='+FORMA.CC.value+'&AlmacenPpal=<? echo $AlmacenPpal?>&Anio=<? echo $Anio?>&Tipo=Productos&Producto='+this.value;
        if(this.value==''){FORMA.AutoId.value='';FORMA.submit();}"/></td>
    </tr>
</table>-->
<?
		
	
	function Encabezados()
	{
		global $Compania;global $Fecha;global $NumPag;global $TotPaginas;global $ND;
		?>
		<table border="1" bordercolor="#e5e5e5" cellpadding="2" width="100%"  style='font : normal normal small-caps 11px Tahoma;'>
		<tr><td colspan="12"><center><strong><? echo strtoupper($Compania[0])?><br>
		<? echo $Compania[1]?><br>SALIDAS POR CENTRO DE COSTOS<? echo $AlmacenPpal?></td></tr>
		<tr><td colspan="12" align="right">Fecha de Impresi&oacute;n <? echo "$ND[year]-$ND[mon]-$ND[mday]"?></td></tr></td>
		</tr></strong></center>
<?	}
Encabezados();
	}
	$cons = "Select Movimiento.CentroCosto,CentrosCosto.CentroCostos
	from Consumo.Movimiento,Central.CentrosCosto 
        Where Movimiento.CentroCosto = CentrosCosto.Codigo And
	Movimiento.Compania='$Compania[0]' and CentrosCosto.Compania='$Compania[0]' 
        and AlmacenPpal = '$AlmacenPpal' and TipoComprobante = 'Salidas' and CentrosCosto.Anio = $Anio
	and Estado = 'AC' and Fechadespacho >= '$FechaIni' and Fechadespacho <= '$FechaFin' $conCC $conAutoId $conGrupo
	Group by Movimiento.CentroCosto,CentrosCosto.CentroCostos order by Movimiento.CentroCosto";
	//echo $cons;
	$res = ExQuery($cons);
	while($fila = ExFetch($res))
	{
		if($NumRec>=$Encabezados)
		{
			echo "</table><P>&nbsp;</P>";
			$NumPag++;
			Encabezados();
			$NumRec=0;
		}
		?><tr style="font-weight:bold" bgcolor="#e5e5e5"><td colspan="12"><? echo "$fila[1]-$fila[0]";
                if($Grupo){echo " ($Grupo)";}?></td></tr>
		<tr style="font-weight:bold" bgcolor="#e5e5e5">
        	<td>Fecha Despacho</td><td>Codigo</td><td>Nombre</td>
            <td>Numero</td><td>Cantidad</td><td>Vr Unidad</td><td>Vr TOTAL</td>
        </tr>
		<?
		$cons0 = "Select Movimiento.AutoId,Codigo1,NombreProd1,UnidadMedida,Presentacion,
		Fechadespacho,Numero,Cantidad,VrCosto+Movimiento.VrIVA,TotCosto 
		from Consumo.Movimiento,Consumo.CodProductos 
		where Movimiento.AutoId = CodProductos.AutoId and CodProductos.Anio = $Anio and TipoComprobante = 'Salidas'
                and Movimiento.AlmacenPpal = '$AlmacenPpal'
                and CodProductos.AlmacenPpal = '$AlmacenPpal'
		and Movimiento.Estado = 'AC' and Movimiento.Compania='$Compania[0]' and Fechadespacho >= '$FechaIni' and Fechadespacho <= '$FechaFin' 
		and CentroCosto = '$fila[0]' $conAutoId  $conGrupo order by Fechadespacho,TipoComprobante asc";
		//echo $cons0;
		$res0 = ExQuery($cons0);
		$ValorTotal = 0; $CanTotal = 0;
		while($fila0 = ExFetch($res0))
		{
			echo "<tr><td width='8%'>$fila0[5]</td><td>$fila0[1]</td><td>".utf8_decode_seguro("$fila0[2] $fila0[3] $fila0[4]")."</td>
			<td>$fila0[6]</td>
			<td align='right'>".number_format($fila0[7],2)."</td>
			<td align='right'>".number_format($fila0[8],2)."</td>
			<td align='right'>".number_format($fila0[9],2)."</td></tr>";
			$ValorTotal = $ValorTotal + $fila0[9];
			$CanTotal = $CanTotal + $fila0[7];
			$NumRec++;
		}
		$TotalValor = $TotalValor + $ValorTotal;
		$TotalCantidad = $TotalCantidad + $CanTotal;
		?>
		<tr bgcolor="#e5e5e5" style="font-weight:bold">
        	<td colspan="4" align="right">SUBTOTAL</td>
            <td align="right"><? echo number_format($CanTotal,2)?></td>
            <td align="right" colspan="2"><? echo number_format($ValorTotal,2)?></td>
        </tr>
		<?
		$NumRec += 2;
                $SUPERTOTAL = $SUPERTOTAL + $ValorTotal;
	}

?>
        <tr bgcolor ="#e5e5e5" style=" font-weight: bold">
            <td colspan="6" align="Right">TOTAL SALIDAS</td>
            <td align="Right"><?echo number_format($SUPERTOTAL,2);?></td>
        </tr>
</form>
<iframe id="Busquedas" name="Busquedas" style="display:none;" src="Busquedas.php" frameborder="1" scrolling="yes" style="border:#e5e5e5" height="400"></iframe>
</body>