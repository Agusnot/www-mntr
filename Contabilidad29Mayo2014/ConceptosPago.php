<?
	if($DatNameSID){session_name("$DatNameSID");}
	session_start();
	include("Funciones.php");
    $ND=getdate();

	$cons = "Select Concepto,Completa From Contabilidad.ConceptosPagoxCC where Compania='$Compania[0]' and Anio=$Anio";
	$res = ExQuery($cons);
	while($fila = ExFetch($res))
	{
		$Completa[$fila[0]] = $fila[1];
	}

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
	function Registra($Cuenta,$AutoId,$Debito,$Credito,$NUMREG,$Comprobante,$Tercero,$DocSoporte,$Detalle,$CentCos)
	{
		global $Compania;

		$cons="Insert into Contabilidad.TmpMovimiento(NumReg,AutoId,Comprobante,Cuenta,Identificacion,Debe,Haber,CC,DocSoporte,Compania,Detalle)
		values('$NUMREG',$AutoId,'$Comprobante',$Cuenta,'$Tercero',$Debito,$Credito,'$CentCos','$DocSoporte','$Compania[0]','$Detalle')";
		$res=ExQuery($cons);echo ExError($res);
	}

	if($Registrar)
	{
		$cons="Select AutoId from Contabilidad.TmpMovimiento where NumReg='$NUMREG' Order By AutoId Desc";
		$res=ExQuery($cons);echo ExError($res);
		$fila=ExFetch($res);
		$AutoId=$fila[0];

		$cons="Select CuentaDebe,CuentaHaber from Contabilidad.ConceptosPago where Compania='$Compania[0]' and Concepto='$Concepto'";
		$res=ExQuery($cons);
		$fila=ExFetch($res);

			if($CentroCost)
			{
				while (list($val,$cad) = each ($CentroCost)) 
				{
					$AutoId++;
					Registra($cad,$AutoId,$ValorxCentro[$val],0,$NUMREG,$Comprobante,$Tercero,$DocSoporte,$Detalle,$val);
				}
			}
			else
			{
				$AutoId++;
				Registra($fila[0],$AutoId,$Valor,0,$NUMREG,$Comprobante,$Tercero,$DocSoporte,$Detalle,'000');
			}

			$cons="Select Cuenta,AutoId,Haber from Contabilidad.TmpMovimiento where NumReg='$NUMREG' and Haber>0";
			$res=ExQuery($cons);
			if(ExNumRows($res)>0)
			{
				$fila=ExFetch($res);
				$Valor=$fila[2]+$Valor;
				$CtaHaber=$fila[0];
				$IdHaber=$fila[1];

				$cons="Update Contabilidad.TmpMovimiento set Haber=$Valor where NumReg='$NUMREG' and Cuenta='$CtaHaber' and AutoId=$IdHaber";
				$res=ExQuery($cons);
				echo ExError($res);
			}
			else
			{
				$AutoId++;
				Registra($fila[1],$AutoId,0,$Valor,$NUMREG,$Comprobante,$Tercero,$DocSoporte,$Detalle,'000');
			}

		?>
		<script language="JavaScript">
			CerrarThis();
			parent.frames.NuevoMovimiento.location.href='DetNuevoMovimientos.php?DatNameSID=<? echo $DatNameSID?>&Guardar=1&NoInsert=1&NUMREG=<?echo $NUMREG?>&Comprobante=<?echo $Comprobante?>&Detalle=<?echo $Detalle?>&Tercero=<?echo $Tercero?>';
		</script>
<?	
	}
?><head><title>Conceptos de Pago</title></head>
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


<form name="FORMA" method="post">
<table border="1" width="100%" bordercolor="<?echo $Estilo[1]?>" style="font-family:<?echo $Estilo[8]?>;font-size:12;font-style:<?echo $Estilo[10]?>">
<tr style="color:<?echo $Estilo[6]?>;font-weight:bold" bgcolor="<?echo $Estilo[1]?>" align="center">
<td>Concepto</td><td>Valor</td><td>Documento</td></tr>
<?
	$cons="Select Concepto,CuentaDebe,CuentaHaber from Contabilidad.ConceptosPago where Compania='$Compania[0]' and Comprobante='$Comprobante' and anio='$ND[year]' order by Concepto ASC";
	$res=ExQuery($cons);?>
	<tr><td><select name="Concepto" onchange="document.FORMA.submit();" style='width:330px;'>
	<option>
<?	while($fila=ExFetch($res))
	{
		if($Completa[$fila[0]] == 1)
		{
			if($fila[0]==$Concepto){echo "<option selected value='$fila[0]'>$fila[0]</option>";}
			else{echo "<option value='$fila[0]'>$fila[0]</option>";}
		}
	}
	?>
	</select>
	<td><input type='Text' style='width:160px;' name="Valor" onchange="document.FORMA.submit();" onblur="document.FORMA.submit();" value="<? echo $Valor?>"></td>
	<td><input type='Text' style='width:100%;' name="DocSoporte" value="<? echo $Numero?>"></td>
<?
	echo "<input type='Hidden' name='Comprobante' value='$Comprobante'>";
	echo "<input type='Hidden' name='Tercero' value='$Tercero'>";
	echo "<input type='Hidden' name='Detalle' value='$Detalle'>";
	echo "<input type='Hidden' name='NUMREG' value=$NUMREG>";

?>
<tr style="color:<?echo $Estilo[6]?>;font-weight:bold" bgcolor="<?echo $Estilo[1]?>" align="center"><td colspan="3">Distribuci&oacute;n x Centros de Costo</td></tr>

<tr><td colspan="3">
<?
	$cons="Select Concepto,CuentaDebe,CuentaHaber from Contabilidad.ConceptosPago where Compania='$Compania[0]' and Concepto='$Concepto'";
	$res=ExQuery($cons);
	$fila=ExFetch($res);
	$CtaHaber=$fila[2];$CtaDebe=$fila[1];
	
	if($Concepto)
	{
		$cons3="Select Codigo,CentroCostos from Central.CentrosCosto where Compania='$Compania[0]' and Anio=$Anio Order By Codigo";
		$res3=ExQuery($cons3);?>
		<table border=1 width="100%" bordercolor="white" style="font-family:<?echo $Estilo[8]?>;font-size:11;font-style:<?echo $Estilo[10]?>">
<?		echo "<tr bgcolor='#e5e5e5' style='font-weight:bold'><td>CC</td><td>Cuenta</td><td>Porc</td><td align='right'>Total</td></tr>";
		while($fila3=ExFetch($res3))
		{
			$cons4="Select CuentaDebe,PorcDist from Contabilidad.ConceptosPagoxCC where Compania='$Compania[0]' and Concepto='$Concepto' and CC='$fila3[0]' and anio='$ND[year]'
			        order by CuentaDebe ASC";
			$res4=ExQuery($cons4);echo ExError($res);
			$fila4=ExFetch($res4);
			$CtaDebe=$fila4[0];
			if($CtaDebe){
			$ValorPorc=($Valor*$fila4[1])/100;
			$Total=$Total+$ValorPorc;
			$i++;
		?>

			<td> <input style="visibility:hidden" readonly="readonly" type="Checkbox" name="CentroCost[<? echo $fila3[0]?>]" value="<? echo $CtaDebe?>" checked="checked" /> <?echo "$fila3[0] - $fila3[1]"?></td>
			<td><? echo $fila4[0]?></td>
			<td><? echo $fila4[1]?> % </td>
			<td align="right"><input type="Hidden" readonly="yes" readonly="readonly" name="ValorxCentro[<?echo $fila3[0]?>]" value="<? echo $ValorPorc?>" style="width:120px;"><? echo number_format($ValorPorc,2)?></td>
			</tr>

<?		}		}
		echo "<tr><td colspan=4 align='right' bgcolor='#e5e5e5'><strong>".number_format($Total,2)."</td></tr>";
		echo "</table>";
	}
?>

</table>
<br><br>
<input type="hidden" name="DatNameSID" value="<? echo $DatNameSID?>" />
<input type="Submit" name="Registrar" value="Registrar">
<input type="button" value="Cerrar" onclick="CerrarThis()" />
</form>