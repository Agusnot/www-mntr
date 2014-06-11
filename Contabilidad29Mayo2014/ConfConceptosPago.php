<?
	if($DatNameSID){session_name("$DatNameSID");}
	session_start();
	include("Funciones.php");
	$ND=getdate();
	if(!$Anio){$Anio=$ND[year];}
	$cons = "Select Concepto,Completa From Contabilidad.ConceptosPagoxCC where Compania='$Compania[0]' and Anio=$Anio";
	$res = ExQuery($cons);
	while($fila = ExFetch($res))
	{
		$Completa[$fila[0]] = $fila[1];
	}
	if($Guardar)
	{
		if($Concepto && $Cuenta)
		{
			$cons="Insert into Contabilidad.ConceptosPago(Compania,Concepto,CuentaHaber,Comprobante,Anio)
				   values('$Compania[0]','$Concepto','$Cuenta','$Comprobante',$Anio)";
			$res=ExQuery($cons);
			echo ExError($res);
			$Cuenta = "";
		}
		
	}
	if($GuardarC)
	{
		while(list($cad,$val) = each($GuardarC))
		{
			$cons = "Update Contabilidad.ConceptosPago set Concepto = '$ConceptoC[$cad]', CuentaHaber = '$CuentaC[$cad]'
			where Compania = '$Compania[0]' and Anio = $Anio and Concepto = '$ConceptoX[$cad]' and CuentaHaber = '$CuentaX[$cad]'";
			$res = ExQuery($cons);
		}
	}
	if($Eliminar)
	{
		while(list($cad,$val) = each($Eliminar))
		{
			$cons = "Delete from Contabilidad.ConceptosPagoxCC 
			Where Compania='$Compania[0]' and Concepto='$ConceptoX[$cad]' and Comprobante='$Comprobante' and Anio=$Anio";
			$res = ExQuery($cons);
			
			$cons = "Delete from Contabilidad.ConceptosPago where Compania='$Compania[0]' and Anio = $Anio
			and Concepto = '$ConceptoX[$cad]' and CuentaHaber = '$CuentaX[$cad]' and Comprobante='$Comprobante'";
			$res = ExQuery($cons);
			
		}
	}
?>
<script language="javascript" src="/Funciones.js"></script>
<script language="javascript">
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
	
	function SelCredito()
	{
		frames.FrameOpener.location.href='/Contabilidad/BusquedaxOtros.php?DatNameSID=<? echo $DatNameSID?>&Tipo=Cuentas&Campo=CtaCredito&Formulario=FORMA&Anio=<?echo $Anio?>';
		document.getElementById('FrameOpener').style.position='absolute';
		document.getElementById('FrameOpener').style.top='50px';
		document.getElementById('FrameOpener').style.left='15px';
		document.getElementById('FrameOpener').style.display='';
		document.getElementById('FrameOpener').style.width='690';
		document.getElementById('FrameOpener').style.height='390';

	}
	function AbrirDebito(Concepto)
	{
		frames.FrameOpener.location.href="DetConceptosPago.php?DatNameSID=<? echo $DatNameSID?>&Concepto="+Concepto+"&Comprobante=<? echo $Comprobante?>&Anio=<? echo $Anio?>";
		document.getElementById('FrameOpener').style.position='absolute';
		document.getElementById('FrameOpener').style.top='50px';
		document.getElementById('FrameOpener').style.left='15px';
		document.getElementById('FrameOpener').style.display='';
		document.getElementById('FrameOpener').style.width='690';
		document.getElementById('FrameOpener').style.height='390';
	}
	function Editando(Id,Tot)
	{
		for(i = 0; i<Tot; i++)
		{
			if(i == Id)
			{
				document.getElementById("ConceptoC[" + i + "]").readOnly = false;
				document.getElementById("CuentaC[" + i + "]").readOnly = false;
				document.getElementById("ConceptoC[" + i + "]").style.border = 'groove';
				document.getElementById("CuentaC[" + i + "]").style.border = 'groove';
				document.getElementById("ConceptoC[" + i + "]").focus();
			}
			else
			{
				document.getElementById("GuardarC[" + i + "]").disabled = true;
				document.getElementById("Editar[" + i + "]").disabled = true;
				document.getElementById("Eliminar[" + i + "]").disabled = true;
			}
		}
	}
</script>
<body background="/Imgs/Fondo.jpg">
<table border="1" bordercolor="<?echo $Estilo[1]?>" style="font-family:<?echo $Estilo[8]?>;font-size:12;font-style:<?echo $Estilo[10]?>">
<tr style="color:<?echo $Estilo[6]?>;font-weight:bold" bgcolor="<?echo $Estilo[1]?>" align="center">

<td>Comprobante</td>
<td><select name="Comprobante" onChange="location.href='ConfConceptosPago.php?DatNameSID=<? echo $DatNameSID?>&Comprobante='+Comprobante.value+'&Anio='+Anio.value">
<option>
<?
	$cons="Select Comprobante from Contabilidad.Comprobantes where Compania='$Compania[0]' Order by Comprobante";
	$res=ExQuery($cons);
	while($fila=ExFetch($res))
	{
		if($fila[0]==$Comprobante){echo "<option selected value='$fila[0]'>$fila[0]</option>";}
		else{echo "<option value='$fila[0]'>$fila[0]</option>";}
	}
?>
</select>
<select name="Anio" onChange="location.href='ConfConceptosPago.php?DatNameSID=<? echo $DatNameSID?>&Comprobante='+Comprobante.value+'&Anio='+Anio.value">
<?
	$cons = "Select Anio from Central.Anios where Compania='$Compania[0]' order by Anio desc";
	$res = ExQuery($cons);
	while($fila = ExFetch($res))
	{
		if($Anio == $fila[0]){echo "<option selected value='$fila[0]'>$fila[0]</option>";}
		else{echo "<option value='$fila[0]'>$fila[0]</option>";}
	}
?>
</select>
<?
if($Comprobante && $Anio)
{ ?>
	</td>
	<form name="FORMA" method="post">
	</tr>
	</table>
	<table border="1" bordercolor="<?echo $Estilo[1]?>" style="font-family:<?echo $Estilo[8]?>;font-size:12;font-style:<?echo $Estilo[10]?>">
	<tr style="color:<?echo $Estilo[6]?>;font-weight:bold" bgcolor="<?echo $Estilo[1]?>" align="center">
	<td>Concepto</td><td>Cta Credito</td><td colspan="5">Cta Debito</td></tr>
	<?
		$cons="Select Concepto,CuentaHaber from Contabilidad.ConceptosPago where Compania='$Compania[0]' and Comprobante='$Comprobante' and Anio=$Anio";
		$res=ExQuery($cons);
		$C = 0;
		$X = ExNumRows($res);
		while($fila=ExFetch($res))
		{
			?><tr>
            	<td><input type="text" name="ConceptoC[<? echo $C?>]" id="ConceptoC[<? echo $C?>]" value="<? echo $fila[0]?>" 
                readonly style="border:#FFFFFF; width:100%"><input type="hidden" name="ConceptoX[<? echo $C?>]" value="<? echo $fila[0]?>" /></td>
                <td><input type="text" name="CuentaC[<? echo $C?>]" id="CuentaC[<? echo $C?>]" value="<? echo $fila[1]?>" readonly 
                style="border:#FFFFFF; text-align:right; width:100%"
                onFocus="Mostrar();
                frames.Busquedas.location.href='Busquedas.php?DatNameSID=<? echo $DatNameSID?>&NoMovimiento=1&Anio=<? echo $Anio?>&Tipo=PlanCuentas&Cuenta='+this.value+'&ObjCuenta=CuentaC&ID=<? echo $C?>';" 
				onkeyup="xNumero(this);
                frames.Busquedas.location.href='Busquedas.php?DatNameSID=<? echo $DatNameSID?>&NoMovimiento=1&Anio=<? echo $Anio?>&Tipo=PlanCuentas&Cuenta='+this.value+'&ObjCuenta=CuentaC&ID=<? echo $C?>';">
                <input type="hidden" name="CuentaX[<? echo $C?>]" value="<? echo $fila[1]?>" />
                </td>
                <?
                	if($Completa[$fila[0]] == 1)
					{ $col=1; ?><td align="center"><img src="/Imgs/b_check.png" border="0" title="Concepto Utilizable" /></td><? }
					else{$col=2;}
				?>
				<td align="right" colspan="<? echo $col?>">
                <button type="button" title="Abrir Debito" name="CtaDebito" onClick="Ocultar();AbrirDebito('<? echo $fila[0]?>');">
            	<img src="/Imgs/b_tblops.png">
            	</button></td>
            	<td><button type="submit" name="GuardarC[<? echo $C?>]" id="GuardarC[<? echo $C?>]" title="Guardar" ><img src="/Imgs/b_save.png"></button></td>
            	<td><button type="button" name="Editar[<? echo $C?>]" id="Editar[<? echo $C?>]" title="Editar" 
                 onClick="Editando('<? echo $C?>','<? echo $X?>')"><img src="/Imgs/b_edit.png"></button></td>
            	<td><button type="submit" name="Eliminar[<? echo $C?>]" id="Eliminar[<? echo $C?>]" title="Eliminar"><img src="/Imgs/b_drop.png"></button></td> 
            </tr>
            <? $C++; 
		}
	?>
	<tr>
	<td><input type="text" name="Concepto" <? echo $Concepto?> style="width:300px;" onFocus="Ocultar();"></td>
	<td><input type="text" name="Cuenta" value="<? echo $Cuenta?>" style="text-align:right"
        onFocus="Mostrar();frames.Busquedas.location.href='Busquedas.php?DatNameSID=<? echo $DatNameSID?>&NoMovimiento=1&ObjCuenta=Cuenta&Tipo=PlanCuentas&Cuenta='+this.value+'&Anio=<? echo $Anio?>'" 
		onkeyup="xNumero(this);frames.Busquedas.location.href='Busquedas.php?DatNameSID=<? echo $DatNameSID?>&NoMovimiento=1&ObjCuenta=Cuenta&Tipo=PlanCuentas&Cuenta='+this.value+'&Anio=<? echo $Anio?>';"
        onKeyDown="xNumero(this)" onBlur="campoNumero(this)"/></td>
	<td colspan="5"><button type="submit" name="Guardar" title="Guardar"><img src="/Imgs/b_save.png"></td>
	<input type="Hidden" name="Comprobante" value="<?echo $Comprobante?>">
    <input type="hidden" name="DatNameSID" value="<? echo $DatNameSID?>">
	</form>
	</tr>
	</table>
	</body>
	<iframe id="Busquedas" name="Busquedas" style="display:none;" src="Busquedas.php?DatNameSID=<? echo $DatNameSID?>" frameborder="0" height="400"></iframe>
	<iframe id="FrameOpener" name="FrameOpener" style="display:none" frameborder="1" style="border:dashed; border:#e5e5e5"></iframe>
<? } ?>
