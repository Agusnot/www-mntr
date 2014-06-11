<?
	if($DatNameSID){session_name("$DatNameSID");}
	include("Funciones.php");
	session_start();
	include("Consumo/ObtenerSaldos.php");
        $ND=getdate();
	if($Anio != $ND[year])
        {
            $FechaIni = "$Anio-01-01";
            $FechaFin = "$Anio-12-31";
        }
        if(!$FechaIni){$FechaIni="$Anio-$ND[mon]-01";}
	else
	{
		$FechaEx = explode("-",$FechaIni);
		$Anio = $FechaEx[0];
	}
	//elseif($Anio){$FechaIni="$Anio-$MesIni-$DiaIni";}
	if(!$FechaFin)
	{
	$cons="Select NumDias from Central.Meses where Numero=$ND[mon]";
	$res=ExQuery($cons);$fila=ExFetch($res);$UltDia=$fila[0];
	$FechaFin="$Anio-$ND[mon]-$ND[mday]";
	}
	//elseif($Anio){$FechaFin="$Anio-$MesFin-$DiaFin";}

	if(!$Anio){$Anio=$ND[year];}
	$VrSaldoIni=SaldosIniciales($Anio,$AlmacenPpal,$FechaIni);
?>
<head>
<script language="javascript">
	function CerrarThis()
	{
		parent.document.getElementById('FrameOpener').style.position='absolute';
		parent.document.getElementById('FrameOpener').style.width='1';
		parent.document.getElementById('FrameOpener').style.height='1';
		parent.document.getElementById('FrameOpener').style.display='none';
	}
</script>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
</head>
<body>
<form name="FORMA" method="post">
<input type="hidden" name="DatNameSID" value="<? echo $DatNameSID?>" />
<? if(!$Cerrar){?>
<table border="1" bordercolor="#e5e5e5" width="100%"  style='font : normal normal small-caps 12px Tahoma;'>
<tr bgcolor="#e5e5e5" style="font-weight:bold"><td>
Producto:
<select name="Producto" onChange="document.FORMA.submit();"><option></option>
<?
	$cons="Select AutoId,NombreProd1,UnidadMedida,Presentacion from Consumo.CodProductos 
	where Estado='AC' and Compania='$Compania[0]' and AlmacenPpal='$AlmacenPpal'
	order by NombreProd1";
	$res=ExQuery($cons);
	while($fila=ExFetch($res))
	{
		if($fila[0]==$Producto){echo "<option selected value='$fila[0]'>$fila[1] $fila[2] $fila[3]</option>";}
		else{echo "<option value='$fila[0]'>$fila[1] $fila[2] $fila[3]</option>";}
	}
?>
</select>
</td></tr></table><? }
else{?>
<center>
<table border="1" bordercolor="#e5e5e5" style='font : normal normal small-caps 11px Tahoma;'>
    <tr align="center" style="font-weight:bold" bgcolor="#e5e5e5"><td>Fecha Inicial</td><td>Fecha Finnal</td><td>&nbsp;</td></tr>
<tr><td><input type="text" name="FechaIni" value="<? echo $FechaIni?>" style="width:90px;text-align:right"></td>
<td><input type="text" name="FechaFin" value="<? echo $FechaFin?>" style="width:90px;text-align:right"></td><td><input type="submit" value="Ver" name="Ver"></td></tr>
</table>
</center>
<?
}?>
</form>
<? if($Producto){?>
<table border="1" bordercolor="#e5e5e5" width="100%"  style='font : normal normal small-caps 11px Tahoma;'>
	<tr bgcolor="#e5e5e5" style="font-weight:bold" align="center">
    	<td>Fecha</td><td>Comprobante</td><td>Numero</td>
        <td>Tipo</td><td>Tercero</td><td>Identificacion</td><td>Detalle</td>
        <td>Entradas</td><td>Salidas</td><td>Valor Unidad</td><td>VrIVA</td>
        <td>Valor Total</td><td>Saldo</td><td>Valor</td><td>Promedio</td>
    </tr>
	<tr bgcolor="#e5e5e5" style="font-weight:bold" align="right">
    	<td colspan="12">SALDO INICIAL</td>
        <td><? echo number_format($VrSaldoIni[$Producto][0],2) ?> </td>
        <td><? echo number_format($VrSaldoIni[$Producto][1],2) ?> </td>
        <td><? if($VrSaldoIni[$Producto][0]>0){echo number_format(($VrSaldoIni[$Producto][1]/$VrSaldoIni[$Producto][0]),2);}?></td>
    </tr>
<?

	$cons="Select Fecha,Comprobante,Numero,TipoComprobante,Cedula,Detalle,Cantidad,VrCosto,TotCosto+VrIVA,VrIVA,
    PrimApe,SegApe,PrimNom,SegNom,idregistro,fechacre,terceros.identificacion,terceros.compania
	from Consumo.Movimiento,Central.Terceros 
    where Movimiento.Compania='$Compania[0]' and AlmacenPpal='$AlmacenPpal' and movimiento.cedula=terceros.identificacion and terceros.compania='$Compania[0]' and AutoId=$Producto and Estado='AC' and
        (TipoComprobante='Entradas' or TipoComprobante='Salidas' or TipoComprobante='Remisiones' Or TipoComprobante='Ingreso Ajuste'
	Or TipoComprobante='Salida Ajuste' or TipoComprobante='Devoluciones')
	and Fecha>='$FechaIni' and Fecha<='$FechaFin'
group By Fecha,Comprobante,Numero,TipoComprobante,Cedula,Detalle,Cantidad,VrCosto,TotCosto+VrIVA,VrIVA, PrimApe,SegApe,PrimNom,SegNom,idregistro,fechacre,terceros.identificacion,terceros.compania

order By Fecha Asc,Comprobante,Numero";
//
	$res=ExQuery($cons);echo ExError();
	$NewSaldo=$VrSaldoIni[$Producto][0];
	$VrSaldo=$VrSaldoIni[$Producto][1];
	while($fila=ExFetch($res))
	{
		$Salidas=0;$Entradas=0;
		if($fila[3]=="Salidas" || $fila[3]=="Salida Ajuste"){$Salidas=$fila[6];$Entradas=0;}
		if($fila[3]=="Entradas" || $fila[3]=="Remisiones" || $fila[3]=="Ingreso Ajuste" || $fila[3]=="Devoluciones")
                {$Entradas=$fila[6];$Salidas=0;}
		$NewSaldo=$NewSaldo+$Entradas-$Salidas;
		$VrSaldo=$VrSaldo+(($fila[7]*$Entradas)+$fila[9])-($fila[7]*$Salidas);
		?><tr onMouseOver="this.bgColor='#AAD4FF'" onMouseOut="this.bgColor='#FFFFFF'"><?
		echo"<td align='center'>$fila[0]</td><td>$fila[1]</td><td align='center'>$fila[2]</td>
		<td>$fila[3]</td>
        <td>$fila[10] $fila[11] $fila[12] $fila[13]</td>
        <td>$fila[4]</td><td>$fila[5]</td>
		<td align='right'>".number_format($Entradas,2)."</td><td align='right'>".number_format($Salidas,2)."</td>
		<td align='right'>".number_format($fila[7],2)."</td>";
		if($fila[3]=="Entradas" || $fila[3]=="Remisiones" || $fila[3]=="Ingreso Ajuste"){ echo "<td align='right'>".number_format($fila[9],2);}
		else{ echo "<td align='right'>0.00";}
		echo "</td><td align='right'>".number_format($fila[8],2)."</td><td align='right'>".number_format($NewSaldo,2)."</td>
		<td align='right'>".number_format($VrSaldo,2)."</td><td align='right'>";
		if($NewSaldo>0){echo number_format(($VrSaldo/$NewSaldo),2);}else{echo "0.00";}echo "</td></tr>";
	}
	?>
	<tr bgcolor="#e5e5e5" style="font-weight:bold" align="right">
		<td colspan="12">SALDO FINAL</td>
        <td><? echo number_format($NewSaldo,2) ?> </td>
        <td><? echo number_format($VrSaldo,2) ?> </td>
        <td><? if($NewSaldo>0){echo number_format(($VrSaldo/$NewSaldo),2);}?></td>
    </tr>
	<?
}?>
</table>
<?
	if($Cerrar)
	{?>
	<input type="button" value="Cerrar" onClick="CerrarThis()">
<?	}
?> 

</body>
</html>
