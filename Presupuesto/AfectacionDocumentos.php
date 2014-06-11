<?	if($DatNameSID){session_name("$DatNameSID");}
	session_start();
include("Funciones.php");
	include("CalcularSaldos.php");

?>
<script language="javascript">
	function CerrarThis()
	{
		parent.document.getElementById('FrameOpener').style.position='absolute';
		parent.document.getElementById('FrameOpener').style.top='1px';
		parent.document.getElementById('FrameOpener').style.left='1px';
		parent.document.getElementById('FrameOpener').style.width='1';
		parent.document.getElementById('FrameOpener').style.height='1';
		parent.document.getElementById('FrameOpener').style.display='none';
	}
</script>
<?

	$cons="Select * from Presupuesto.TmpMovimiento where NUMREG='$NUMREG'";
	$res=ExQuery($cons);
	if(ExNumRows($res)>0)
	{
	//	echo "<br><br><center><font size=4><em>No puede afectar documentos mientras tiene registros en su documento, <br>eliminelos para continuar</em><br><br>
		//<input type='button' value='Regresar' onclick='CerrarThis()'>";exit;
		
	}

	if(!$PerFin){$PerFin="$Anio-$Mes-$Dia";}
	if(!$PerIni){$PerIni="$Anio-01-01";}
	$cons="Select TipoComprobant from Presupuesto.Comprobantes where Comprobante='$Comprobante'";
	$res=ExQuery($cons);
	$fila=ExFetch($res);
	$TipoCom=$fila[0];
	$cons="Select TipoOperacion,Movimiento from Presupuesto.TiposComprobante where Tipo='$TipoCom'";

	$res=ExQuery($cons);
	$fila=ExFetch($res);
	$Movimiento=$fila[1];
	
	if($Registrar)
	{
		$cons="Select sum(Credito),sum(ContraCredito),CompAfectado,DocSoporte,Cuenta from Presupuesto.Movimiento where Comprobante='$Comprobante' 
		and Numero='$Numero' and Movimiento.Compania='$Compania[0]' Group By CompAfectado,DocSoporte,Cuenta";

		$res=ExQuery($cons);
		while($fila=ExFetch($res))
		{
			if($fila[0]>0){$ValorDocActual[$fila[2]][$fila[3]][$fila[4]]=$fila[0];}
			if($fila[1]>0){$ValorDocActual[$fila[2]][$fila[3]][$fila[4]]=$fila[1];}
		}

		$cons="Select AutoId from Presupuesto.TmpMovimiento where NumReg='$NUMREG' Order By AutoId Desc";
		$res=ExQuery($cons);
		$fila=ExFetch($res);
		$IdOt=$fila[0]+1;

		$AutoId=$fila[0]+1;

		if($ComprobSel)
		{
			while (list($val,$cad) = each ($ComprobSel)) 
			{
				ObtieneValoresxDocxCuenta($PerIni,$PerFin,$cad);
				$cons="Select Numero,Fecha,Detalle,Cuenta,0,Vigencia,ClaseVigencia from Presupuesto.Movimiento 
				where Comprobante='$cad' and Numero='$val' 
				and (Identificacion='$Tercero' Or Identificacion='99999999999-0') and Compania='$Compania[0]' 
				and Estado='AC' Group By Numero,Fecha,Detalle,Cuenta,Vigencia,ClaseVigencia Order By Cuenta";

				$res=ExQuery($cons);echo ExError();
				while($fila=ExFetchArray($res))
				{
					
					$Vigencia=$fila[5];$ClaseVigencia=$fila[6];
					$Valor=CalcularSaldoxDocxCuenta($fila[3],$fila[0],$cad,$PerIni,$PerFin,$Vigencia,$ClaseVigencia)+$ValorDocActual[$cad][$fila[0]][$fila[3]];
					if($Valor){
					$cons2="Delete from Presupuesto.TmpMovimiento where NUMREG='$NUMREG' and Comprobante='$Comprobante' and Cuenta='".$fila['cuenta'] ."' and DocSoporte='$val'";
					$res2=ExQuery($cons2);echo ExError();
					if($Movimiento=="Credito"){$Credito=$Valor;$CCredito=0;}
					if($Movimiento=="Contra Credito"){$CCredito=$Valor;$Credito=0;}
					$cons2="Insert into Presupuesto.TmpMovimiento(NumReg,AutoId,Comprobante,Cuenta,Identificacion,Credito,ContraCredito,DocSoporte,Compania,Detalle,Vigencia,ClaseVigencia)
					values('$NUMREG',$AutoId,'$Comprobante','".$fila['cuenta']."','$Tercero',$Credito,$CCredito,$val,'$Compania[0]','$Detalle','$Vigencia','$ClaseVigencia')";
					$res2=ExQuery($cons2);echo ExError();
					$AutoId++;}
					$CompAfectado=$cad;
				}

			}
				?>
		<script language="JavaScript">
			parent.document.FORMA.CompAfectado.value="<? echo $CompAfectado?>"
			parent.frames.NuevoMovimiento.location.href='DetNuevoMovimientos.php?DatNameSID=<? echo $DatNameSID?>&Guardar=1&NoInsert=1&NUMREG=<?echo $NUMREG?>&Comprobante=<?echo $Comprobante?>&Detalle=<?echo $Detalle?>&Tercero=<?echo $Tercero?>&Anio=<?echo $Anio?>&Mes=<?echo $Mes?>&Dia=<?echo $Dia?>&Numero=<?echo $Numero?>';
			CerrarThis();
		</script>
<?		}
	}
?>
<form name="FORMA">
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
<table border="1" width="100%" bordercolor="<?echo $Estilo[1]?>" style="font-family:<?echo $Estilo[8]?>;font-size:11;font-style:<?echo $Estilo[10]?>">
<?	
	ObtieneValoresxDoc($PerIni,$PerFin);

	$cons="Select sum(Credito),sum(ContraCredito),CompAfectado,DocSoporte from Presupuesto.Movimiento where Comprobante='$Comprobante' and Numero='$Numero' and Movimiento.Compania='$Compania[0]' Group By CompAfectado,DocSoporte";

	$res=ExQuery($cons);
	while($fila=ExFetch($res))
	{
		if($fila[0]>0){$ValorDocActual[$fila[2]][$fila[3]]=$fila[0];}
		if($fila[1]>0){$ValorDocActual[$fila[2]][$fila[3]]=$fila[1];}
	}

	$cons1="Select CompDestino from Presupuesto.CruceComprobantes where CompOrigen='$TipoCom'";
	$res1=ExQuery($cons1);
	while($fila1=ExFetch($res1))
	{
		$cons2="Select Comprobante,TipoComprobant from Presupuesto.Comprobantes where TipoComprobant='$fila1[0]' and Compania='$Compania[0]'";
		$res2=ExQuery($cons2);
		while($fila2=ExFetch($res2))
		{
		?>
			<tr style="color:<?echo $Estilo[6]?>;font-weight:bold" bgcolor="<?echo $Estilo[1]?>" align="center"><td colspan=6><?echo $fila2[0]?></td></tr>
			<tr style="color:<?echo $Estilo[6]?>;font-weight:bold" bgcolor="<?echo $Estilo[1]?>" align="center">
			<td><input type="Checkbox" onclick="Marcar()" name="Marcacion"></td>
			<td>No</td><td>Fecha</td><td>Detalle</td><td>Valor</td></tr>
		<?
			$cons="Select Numero,Fecha,Detalle,0,Identificacion,Vigencia,ClaseVigencia 
			from Presupuesto.Movimiento where Comprobante='$fila2[0]' and (Identificacion='$Tercero' Or Identificacion='99999999999-0')
			and Movimiento.Compania='$Compania[0]' and Estado='AC' Group By Numero,Vigencia,ClaseVigencia,Fecha,Detalle,Identificacion Order By Fecha,Numero";
			$res=ExQuery($cons);
			while($fila=ExFetch($res))
			{
				$i++;
				$Valor=(CalcularSaldoxDoc($fila[0],$fila2[0],$PerIni,$PerFin,$fila[5],$fila[6])+$ValorDocActual[$fila2[0]][$fila[0]]);
				//echo "$fila[0] $fila2[0] $fila[5] $fila[6] --> $Valor<br>";
				if($Valor>0){
				?>
				<tr><td align='center'><input type='Checkbox' name="ComprobSel[<?echo $fila[0]?>]" value="<?echo $fila2[0]?>"></td><td><?echo $fila[0]?></td>
				<td><?echo $fila[1]?></td><td><?echo $fila[2]?></td><td align="right"><?echo number_format($Valor,2)?></td></tr><?}?>
<?			}
			$CompDestino=$fila2[0];
		}
	}
?>

</table><br>
<input type="Hidden" name="Comprobante" value="<?echo $Comprobante?>">
<input type="Hidden" name="CompDestino" value="<?echo $CompDestino?>">
<input type="Hidden" name="Tercero" value="<?echo $Tercero?>">
<input type="Hidden" name="NUMREG" value="<?echo $NUMREG?>">
<input type="Hidden" name="Detalle" value="<?echo $Detalle?>">
<input type="Hidden" name="PerFin" value="<?echo $PerFin?>">
<input type="Hidden" name="PerIni" value="<?echo $PerIni?>">
<input type="Hidden" name="Anio" value="<?echo $Anio?>">
<input type="Hidden" name="Mes" value="<?echo $Mes?>">
<input type="Hidden" name="Dia" value="<?echo $Dia?>">
<input type="Hidden" name="Numero" value="<?echo $Numero?>">
<input type="Submit" name="Registrar" value="Registrar">
<input type="button" value="Cerrar" onclick="CerrarThis()" />
<input type="hidden" name="DatNameSID" value="<? echo $DatNameSID?>" />
</form>

