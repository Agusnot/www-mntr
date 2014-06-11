<?	if($DatNameSID){session_name("$DatNameSID");}
	session_start();
include("Funciones.php");

	if($Registrar)
	{

		$cons="Select AutoId from Presupuesto.TmpMovimiento where NumReg='$NUMREG' Order By AutoId Desc";
		$res=ExQuery($cons);
		$fila=ExFetch($res);
		$IdOt=$fila[0]+1;

		$AutoId=$fila[0]+1;

		if($ComprobSel)
		{
			while (list($val,$cad) = each ($ComprobSel)) 
			{
			
				$cons="Select Movimiento.Comprobante,Numero,Fecha,Detalle,TipoComprobant,PrimApe,SegApe,PrimNom,SegNom,Credito,ContraCredito,Cuenta from Presupuesto.Movimiento,Presupuesto.Comprobantes,Central.Terceros 
				where Movimiento.Comprobante=Comprobantes.Comprobante and Terceros.Identificacion=Movimiento.Identificacion and TipoComprobant='$TipoComprobante' 
				and Terceros.Compania='$Compania[0]'
				and Movimiento.Identificacion='$Tercero'
				and Movimiento.Compania='$Compania[0]' and Numero='$cad'  and Estado='AC'";

				$res=ExQuery($cons);
				while($fila=ExFetchArray($res))
				{	
					$AutoId++;

					$cons20="Select sum(Credito),sum(ContraCredito) from Presupuesto.Movimiento where 
					Identificacion='$Tercero' and Comprobante='$Comprobante' and Movimiento.Compania='$Compania[0]' and DocSoporte='$fila[1]'  and Cuenta='$fila[11]'  and Estado='AC'";

					$res20=ExQuery($cons20);
					$fila20=ExFetch($res20);
					if($fila20[0]){$VrsPagados=$fila20[0];}
					if($fila20[1]){$VrsPagados=$fila20[1];}

					if($fila['Credito']){$Credito=$fila['Credito']-$VrsPagados;$CCredito=0;}
					if($fila['ContraCredito']){$CCredito=$fila['ContraCredito']-$VrsPagados;$Credito=0;}
					
					if($Credito>0 || $CCredito>0)
					{
						$cons2="Insert into Presupuesto.TmpMovimiento(NumReg,AutoId,Comprobante,Cuenta,Identificacion,Credito,ContraCredito,DocSoporte,Compania,Detalle)
						values('$NUMREG',$AutoId,'$Comprobante','".$fila['Cuenta']."','$Tercero',$Credito,$CCredito,'$cad','$Compania[0]','$Detalle')";
						$res2=ExQuery($cons2);
					}
				}
			}
		}
		?>
		<script language="JavaScript">
			window.close();
			opener.frames.NuevoMovimiento.location.href='DetNuevoMovimientos.php?DatNameSID=<? echo $DatNameSID?>&Guardar=1&NoInsert=1&NUMREG=<?echo $NUMREG?>&Comprobante=<?echo $Comprobante?>&Detalle=<?echo $Detalle?>&Tercero=<?echo $Tercero?>';
		</script>
<?	}

?>
<title>Afectar Comprobante</title>
<script language="JavaScript">
	function Marcar()
	{
		if(document.FORMA.Marcacion.checked==1){MarcarTodo();}
		else{QuitarTodo();}
	}

	function MarcarTodo()
	{
		for (i=0;i<document.FORMA.elements.length;i++) 
    	if(document.FORMA.elements[i].type == "checkbox") 
        document.FORMA.elements[i].checked=1 
	}
	function QuitarTodo()
	{
		for (i=0;i<document.FORMA.elements.length;i++) 
    	if(document.FORMA.elements[i].type == "checkbox") 
        document.FORMA.elements[i].checked=0
	}
</script>

<body>
<form name="FORMA1">
<table border="1" bordercolor="<?echo $Estilo[1]?>" style="font-family:<?echo $Estilo[8]?>;font-size:11;font-style:<?echo $Estilo[10]?>">
<tr style="color:<?echo $Estilo[6]?>;font-weight:bold" bgcolor="<?echo $Estilo[1]?>" align="center"><td colspan="2">Fecha</td><td colspan="2">Rango</td><td></td></tr>
<tr>
<td><input type="Text" name="FechaI" style="width:80px;" value="<?echo $FechaI?>"></td>
<td><input type="Text" name="FechaF" style="width:80px;" value="<?echo $FechaF?>"></td>
<td><input type="Text" name="RangoI" style="width:80px;" value="<?echo $RangoI?>"></td>
<td><input type="Text" name="RangoF" style="width:80px;" value="<?echo $RangoF?>"></td>
<td><input type="Submit" name="Buscar" value="Buscar"></td>
</tr>
</table>

<input type="Hidden" name="Comprobante" value="<?echo $Comprobante?>">
<input type="Hidden" name="Tercero" value="<?echo $Tercero?>">
<input type="Hidden" name="CuentaCruzar" value="<?echo $CuentaCruzar?>">
<input type="Hidden" name="NUMREG" value="<?echo $NUMREG?>">
<input type="Hidden" name="Banco" value="<?echo $Banco?>">
<input type="Hidden" name="Detalle" value="<?echo $Detalle?>">
<input type="hidden" name="DatNameSID" value="<? echo $DatNameSID?>">
</form>
<form name="FORMA">
<table border="1" width="100%" bordercolor="<?echo $Estilo[1]?>" style="font-family:<?echo $Estilo[8]?>;font-size:11;font-style:<?echo $Estilo[10]?>">

<?
	if($FechaI && $FechaF){$Rangos="and Fecha>='$FechaI' and Fecha<='$FechaF'";}
	if($RangoI && $RangoF){$Rangos="and DocSoporte>='$RangoI' and DocSoporte<='$RangoF'";}
				

	$cons1="Select CompDestino from Presupuesto.CruceComprobantes where CompOrigen='$Comprobante'";
	$res1=ExQuery($cons1);
	while($fila1=ExFetch($res1))
	{
		$cons="Select Movimiento from Presupuesto.TiposComprobante where Tipo='$fila1[0]'";
		$res=ExQuery($cons);
		$fila=ExFetch($res);
		$Movimiento=$fila[0];
		$Movimiento=str_replace(" ","",$Movimiento);
		
	$cons="Select Movimiento.Comprobante,Numero,Fecha,Detalle,TipoComprobant,PrimApe,SegApe,PrimNom,SegNom,sum(Credito),sum(ContraCredito) from Presupuesto.Movimiento,Presupuesto.Comprobantes,Central.Terceros 
	where Movimiento.Comprobante=Comprobantes.Comprobante and Terceros.Identificacion=Movimiento.Identificacion and TipoComprobant='$fila1[0]' 
	and Terceros.Compania='$Compania[0]'
	and Movimiento.Identificacion='$Tercero'
	and Movimiento.Compania='$Compania[0]' and Estado='AC' 
	Group By Numero,Movimiento.Comprobante,Numero,Fecha,Detalle,TipoComprobant,PrimApe,SegApe,PrimNom,SegNom Order By Numero";

		$res=ExQuery($cons);echo ExError();

		?>
		<tr style="color:<?echo $Estilo[6]?>;font-weight:bold" bgcolor="<?echo $Estilo[1]?>" align="center"><td colspan=6><?echo $fila1[0]?></td></tr>
		<tr style="color:<?echo $Estilo[6]?>;font-weight:bold" bgcolor="<?echo $Estilo[1]?>" align="center">
		<td><input type="Checkbox" onClick="Marcar()" name="Marcacion"></td>
		<td>No</td><td>Fecha</td><td>Detalle</td><td>Valor</td></tr>
<?		while($fila=ExFetch($res))
		{
			
			$cons2="Select sum(Credito),sum(ContraCredito) from Presupuesto.Movimiento where 
			Identificacion='$Tercero' and Comprobante='$Comprobante' and Movimiento.Compania='$Compania[0]' and DocSoporte='$fila[1]'  and Estado='AC'";

			$res2=ExQuery($cons2);
			$fila2=ExFetch($res2);
			
		if($fila2[0]){$VrsPagados=$fila2[0];}
		if($fila2[1]){$VrsPagados=$fila2[1];}

		if($fila[9]){$TotDocumento=$fila[9];}
		if($fila[10]){$TotDocumento=$fila[10];}
		$TotDocumento=$TotDocumento-$VrsPagados;

			if($TotDocumento>0){?>
			<input type="Hidden" name="TipoComprobante" value="<?echo $fila1[0]?>"><?
			echo "<tr><td align='center'><input type='Checkbox' name='ComprobSel[$i]' value='$fila[1]'></td><td align='right'>$fila[1]</td><td>$fila[2]</td><td>$fila[3]</td>";
			echo "<td align='right'>".number_format($TotDocumento,2)."</td>";
			}
			$TotDocumento=0;$VrsPagados=0;
			$NombreTercero="$fila[5] $fila[6] $fila[7] $fila[8]";
		}
	}
?>
</table><br>

<br>
<input type="Hidden" name="Comprobante" value="<?echo $Comprobante?>">
<input type="Hidden" name="Tercero" value="<?echo $Tercero?>">
<input type="Hidden" name="NUMREG" value="<?echo $NUMREG?>">
<input type="Hidden" name="Detalle" value="<?echo $Detalle?>">
<input type="Hidden" name="Movimiento" value="<?echo $Movimiento?>">
<input type="hidden" name="DatNameSID" value="<? echo $DatNameSID?>">
<input type="Submit" name="Registrar" value="Registrar">
</form>
</body>
