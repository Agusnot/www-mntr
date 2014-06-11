<?
	if($DatNameSID){session_name("$DatNameSID");}
	session_start();
	include("Funciones.php");
	$ND = getdate();
	$FechaIni="$Anio-$MesIni-$DiaIni";
	$FechaFin="$Anio-$MesFin-$DiaFin";
	if($Cedula){$conCedula = "and Cedula='$Cedula'";}
	if($AutoId){$conAutoId = "and Movimiento.AutoId = $AutoId";}
?><head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
</head>

<style>
P{PAGE-BREAK-AFTER: always;}
</style>
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
<body>
<form name="FORMA" method="post">
<input type="hidden" name="DatNameSID" value="<? echo $DatNameSID?>" />
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
<table border="0" style='font : normal normal small-caps 11px Tahoma;' align="center">
	<tr bgcolor="#e5e5e5" style="font-weight:bold">
    	<td>TERCERO:</td>
        <td><input type="text" name="Tercero" value="<? echo $Tercero?>" size="60"
        onfocus="Mostrar();document.frames.Busquedas.location.href='Busquedas.php?DatNameSID=<? echo $DatNameSID?>&AutoId='+FORMA.AutoId.value+'&Anio=<? echo $Anio?>&Tipo=Terceros&Tercero='+this.value"
        onkeyup="Mostrar();
        document.frames.Busquedas.location.href='Busquedas.php?DatNameSID=<? echo $DatNameSID?>&AutoId='+FORMA.AutoId.value+'&Anio=<? echo $Anio?>&Tipo=Terceros&Tercero='+this.value;
        if(this.value==''){FORMA.Cedula.value='';FORMA.submit();}" /></td>
        <td>PRODUCTO:</td>
        <td><input type="text" name="Producto" value="<? echo $Producto?>" size="60" 
        onfocus="Mostrar();document.frames.Busquedas.location.href='Busquedas.php?DatNameSID=<? echo $DatNameSID?>&FechaIni=<? echo $FechaIni?>&FechaFin=<? echo $FechaFin?>&Cedula='+FORMA.Cedula.value+'&AlmacenPpal=<? echo $AlmacenPpal?>&Anio=<? echo $Anio?>&Tipo=Productos&Producto='+this.value"
        onkeyup="Mostrar();document.frames.Busquedas.location.href='Busquedas.php?DatNameSID=<? echo $DatNameSID?>&FechaIni=<? echo $FechaIni?>&FechaFin=<? echo $FechaFin?>&Cedula='+FORMA.Cedula.value+'&AlmacenPpal=<? echo $AlmacenPpal?>&Anio=<? echo $Anio?>&Tipo=Productos&Producto='+this.value;
        if(this.value==''){FORMA.AutoId.value='';FORMA.submit();}"/></td>
    </tr>
</table>
<?
	function Encabezados()
	{
		global $Compania;global $Fecha;global $NumPag;global $TotPaginas;global $ND;
		?>
		<table border="1" bordercolor="#e5e5e5" cellpadding="2" width="100%"  style='font : normal normal small-caps 11px Tahoma;'>
		<tr><td colspan="12"><center><strong><? echo strtoupper($Compania[0])?><br>
		<? echo $Compania[1]?><br>SALIDAS POR TERCERO<? echo $AlmacenPpal?></td></tr>
		<tr><td colspan="12" align="right">Fecha de Impresi&oacute;n <? echo "$ND[year]-$ND[mon]-$ND[mday]"?></td></tr></td>
		</tr></strong></center>
<?	}
	Encabezados();
	$cons = "Select Cedula,PrimApe,SegApe,PrimNom,SegNom 
	from Consumo.Movimiento,Central.Terceros where Movimiento.Cedula = Terceros.Identificacion and
	Movimiento.Compania='$Compania[0]' and Terceros.COmpania='$Compania[0]' and AlmacenPpal = '$AlmacenPpal' and TipoComprobante = 'Salidas'
	and Estado = 'AC' and Fechadespacho >= '$FechaIni' and Fechadespacho <= '$FechaFin' $conCedula $conAutoId
	Group by Cedula,PrimApe,SegApe,PrimNom,SegNom order by PrimApe,SegApe,PrimNom,SegNom";
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
		?><tr style="font-weight:bold" bgcolor="#e5e5e5"><td colspan="12"><? echo "$fila[1] $fila[2] $fila[3] $fila[4] ($fila[0])"?></td></tr>
		<tr style="font-weight:bold" bgcolor="#e5e5e5">
        	<td>Fecha Despacho</td><td>Codigo</td><td>Nombre</td>
            <td>Numero</td><td>Cant</td><td>Devol</td><td>Total</td><td>Vr Unit</td><td>Vr TOTAL</td>
        </tr>
		<?
		$cons0 = "Select Movimiento.AutoId,Codigo1,NombreProd1,UnidadMedida,Presentacion,
		Fechadespacho,Numero,Cantidad,VrCosto+Movimiento.VrIVA,TotCosto,Comprobante 
		from Consumo.Movimiento,Consumo.CodProductos
		where Movimiento.AutoId = CodProductos.AutoId and CodProductos.Anio = $Anio and TipoComprobante = 'Salidas'
		and Movimiento.Estado = 'AC' and Movimiento.Compania='$Compania[0]' and Movimiento.AlmacenPpal='$AlmacenPpal' and CodProductos.AlmacenPpal='$AlmacenPpal'
                and Fechadespacho >= '$FechaIni' and Fechadespacho <= '$FechaFin'
		and Cedula = '$fila[0]' $conAutoId  order by Fechadespacho,TipoComprobante asc";
		//echo $cons0;
		$res0 = ExQuery($cons0);
		$ValorTotal = 0; $CanTotal = 0;
		while($fila0 = ExFetch($res0))
		{
			
			$cons9="Select sum(Cantidad) from Consumo.Movimiento where TipoComprobante='Devoluciones' and Fechadespacho>='$FechaIni' and Fechadespacho<='$FechaFin' and AlmacenPpal='$AlmacenPpal'
			and NoDocAfectado='$fila0[6]' and DocAfectado='$fila0[10]' and Estado='AC' and AutoId='$fila0[0]' Group By NoDocAfectado,DocAfectado";

			$res9=ExQuery($cons9);
			$fila9=ExFetch($res9);
			$SalTotal=$fila0[7]-$fila9[0];
			echo "<tr><td width='8%'>$fila0[5]</td><td>$fila0[1]</td><td>$fila0[2] $fila0[3] $fila0[4]</td>
			<td>$fila0[6]</td>
			<td align='right'>".number_format($fila0[7],2)."</td>

			<td align='right'>".number_format($fila9[0],2)."</td>

			<td align='right'>".number_format($SalTotal,2)."</td>

			<td align='right'>".number_format($fila0[8],2)."</td>

			<td align='right'>".number_format(($fila0[7]-$fila9[0])*$fila0[8],2)."</td></tr>";

			$TotPacte=$TotPacte+$SalTotal;
			$ValorTotal = $ValorTotal + $fila0[9];
			$CanTotal = $CanTotal + $fila0[7];
			$NumRec++;
			$TotDevolucion=$TotDevolucion+$fila9[0];
			
		}
		$TotalValor = $TotalValor + $ValorTotal;
		$TotalCantidad = $TotalCantidad + $CanTotal;
		$SumTotDevol=$SumTotDevol+$TotDevolucion;
		?>
		<tr bgcolor="#e5e5e5" style="font-weight:bold">
        	<td colspan="4" align="right">SUBTOTAL</td>
            <td align="right"><? echo number_format($CanTotal,2)?></td>
            <td align="right"><? echo number_format($TotDevolucion,2)?></td>
            <td align="right"><? echo number_format($TotPacte,2)?></td>
            <td align="right" colspan="2"><? echo number_format($ValorTotal,2)?></td>
        </tr>
		<?
		$TotPacteSalidas=$TotPacteSalidas+$TotPacte;
		$NumRec += 2;$TotDevolucion=0;$TotPacte=0;
	}

	
?>
<tr bgcolor="#e5e5e5" style="font-weight:bold">
    <td colspan="4" align="right">TOTAL</td>
    <td align="right"><? echo number_format($TotalCantidad,2)?></td>
            <td align="right"><? echo number_format($SumTotDevol,2)?></td>
            <td align="right"><? echo number_format($TotalCantidad-$SumTotDevol,2)?></td>
    <td align="right" colspan="2"><? echo number_format($TotalValor,2)?></td>
</tr>
</table>
</form>
<iframe id="Busquedas" name="Busquedas" style="display:none;" src="Busquedas.php" frameborder="1" scrolling="yes" style="border:#e5e5e5" height="400"></iframe>
</body>