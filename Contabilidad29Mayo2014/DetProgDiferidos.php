<?
	if($DatNameSID){session_name("$DatNameSID");}
	session_start();
	include("Funciones.php");
	$ND = getdate();
	if($Nuevo)
	{
		if($PorcDist>0)
		{
			$cons="Insert into Contabilidad.ProgDiferidosxCC(Compania,Concepto,CC,PorcDist,CtaDebito,VrxCC,Id,Fecha,Tercero,SaldoIni,Anio) 
			values ('$Compania[0]','$Concepto','$CC','$PorcDist','$Cuenta','$VrxCC',$Id,'$Fecha','$Tercero',$SaldoIni,$ND[year])";
			$res=ExQuery($cons);
			echo ExError();	
		}
	}
	if($Actualizar)
	{
		while(list($cad,$val) = each($CCAnt))
		{
			$cons="Update Contabilidad.ProgDiferidosxCC set CC='$CC',PorcDist='$PorcDist',CtaDebito='$Cta[$cad]',VrxCC=$VrxCC 
			where Compania='$Compania[0]' and Id=$Id and CC='$val' and CtaDebito = '$cad'";
			$res=ExQuery($cons);echo ExError();
		}
		
	}
	if($Borrar)
	{
		while(list($cad,$val) = each($CCAnt))
		{
			$cons="Delete From Contabilidad.ProgDiferidosxCC 
			where Compania='$Compania[0]' and Id=$Id and CC='$val' and CtaDebito = '$cad'";
			$res=ExQuery($cons);echo ExError();
		}
	}
	$cons = "Select SUM(porcdist) from Contabilidad.ProgDiferidosxCC Where Compania='$Compania[0]' and ID=$Id";
	$res = ExQuery($cons);
	$fila = ExFetch($res);
	$vrXDefecto = 100 - $fila[0];
?>
<script language="javascript" src="/Funciones.js"></script>
<script language="javascript">
function CerrarThis()
	{
		parent.document.getElementById('FrameOpener').style.position='absolute';
		parent.document.getElementById('FrameOpener').style.top='1px';
		parent.document.getElementById('FrameOpener').style.left='1px';
		parent.document.getElementById('FrameOpener').style.width='1';
		parent.document.getElementById('FrameOpener').style.height='1';
		parent.document.getElementById('FrameOpener').style.display='none';
		parent.document.FORMA.submit();
	}
</script>
<body background="/Imgs/Fondo.jpg">
<table border="0" width="100%">
	<tr align="right"><td><button type="button" name="Cerrar" onClick="parent.Ocultar();CerrarThis()" title="Cerrar"><img src="/Imgs/b_drop.png" /></button></td></tr>
</table>
<table border="1" width="100%" bordercolor="<?echo $Estilo[1]?>" style="font-family:<?echo $Estilo[8]?>;font-size:12;font-style:<?echo $Estilo[10]?>">
<tr style="color:#FFFFFF; font-weight:bold" align="center" bgcolor="<? echo $Estilo[1]?>">
<td colspan="6" align="center">Cta Debito <? echo $Concepto?></td></tr>
<tr style="color:<?echo $Estilo[6]?>;font-weight:bold" bgcolor="<?echo $Estilo[1]?>" align="center">

<td>CC</td><td>% Dist</td><td>Valor</td><td>Cuenta</td><td>&nbsp;</td></tr>
<?
	$cons="Select CC,PorcDist,CtaDebito,VrxCC from Contabilidad.ProgDiferidosxCC where Compania='$Compania[0]' and Id=$Id";
	$res=ExQuery($cons);echo ExError();
	while($fila=ExFetch($res))
	{
		$i++;
		echo "<form name='FORMA$i'>";
		echo "<tr align='center'><td>";
		echo "<select name='CC'>";
		$cons3="Select Codigo,CentroCostos from Central.CentrosCosto Where Compania='$Compania[0]' and Anio=$ND[year] Order By Codigo";
		$res3=ExQuery($cons3);
		while($fila3=ExFetch($res3))
		{
			if($fila3[0]==$fila[0]){echo "<option selected value='$fila3[0]'>$fila3[1]</option>";}
			else{echo "<option value='$fila3[0]'>$fila3[1]</option>";}
		}
		?>
		</select>
		</td>
		<td><input type="Text" name="PorcDist" style="width:30px;" onChange="VrxCC.value=(this.value*SaldoIni.value)/100" value="<?echo $fila[1]?>"></td>
		<td><input type="Text" name="VrxCC" style="width:80px;" value="<?echo $fila[3]?>"></td>
		<td>
		<input type="text" name="Cta[<? echo $fila[2]?>]" id="Cta[<? $fila[2]?>]" value="<? echo $fila[2]?>" style="text-align:right;" 
        onFocus="parent.Mostrar();
        parent.frames.Busquedas.location.href='Busquedas.php?DatNameSID=<? echo $DatNameSID?>&Frame=FrameOpener&NoMovimiento=1&ObjCuenta=Cta&ID=<? echo $fila[2]?>&Tipo=PlanCuentas&Cuenta='+this.value+'&Anio=<? echo $ND[year]?>';" 
		onkeyup="xNumero(this);
        parent.frames.Busquedas.location.href='Busquedas.php?DatNameSID=<? echo $DatNameSID?>&Frame=FrameOpener&NoMovimiento=1&ObjCuenta=Cta&ID=<? echo $fila[2]?>&Tipo=PlanCuentas&Cuenta='+this.value+'&Anio=<? echo $ND[year]?>';"
        onKeyDown="xNumero(this)" onBlur="campoNumero(this)"/>
		</td>
		<td>

		<button type="submit" name="Actualizar"><img src="/Imgs/b_save.png" title="Actualizar"></button>
		<button type="submit" name="Borrar"><img src="/Imgs/b_drop.png" title="Eliminar"></button>
		
		</td>
		</tr>
	<input type="Hidden" name="Concepto" value="<? echo $Concepto?>">
	<input type="Hidden" name="Tercero" value="<? echo $Tercero?>">
	<input type="Hidden" name="Id" value="<? echo $Id?>">
	<input type="Hidden" name="SaldoIni" value="<? echo $SaldoIni?>">
	<input type="Hidden" name="Fecha" value="<? echo $Fecha?>">
	<input type="Hidden" name="Tercero" value="< ?echo $Tercero?>">
	<input type="Hidden" name="CCAnt[<? echo $fila[2]?>]" value="<? echo $fila[0]?>">
    <input type="hidden" name="DatNameSID" value="<? echo $DatNameSID?>">
</form>
<?	}
?>

<form name="FORMA">
<tr><td align="center">
<input onFocus="parent.document.frames.Busquedas.location.href='Busquedas.php?DatNameSID=<? echo $DatNameSID?>&Frame=FrameOpener&Centro='+this.value+'&Tipo=CCG&Anio=<? echo $ND[year]?>';
    parent.Mostrar();" 
    onkeyup="parent.document.frames.Busquedas.location.href='Busquedas.php?DatNameSID=<? echo $DatNameSID?>&Frame=FrameOpener&Centro='+this.value+'&Tipo=CCG&Anio=<? echo $ND[year]?>';xNumero(this)"
    type="text" name="CC" style="width:100px"/>
</td>
<td align="center"><input type="Text" name="PorcDist" style="width:30px;" onChange="VrxCC.value=(this.value*SaldoIni.value)/100" onFocus="parent.Ocultar()"
	onKeyUp="xNumero(this)" onKeyDown="xNumero(this)" onBlur="campoNumero(this);if(parseInt(this.value)><? echo $vrXDefecto?>){this.value = '<? echo $vrXDefecto?>'}" 
    value="<? echo $vrXDefecto?>"></td>
<td align="center"><input type="Text" name="VrxCC" style="width:90px;" onFocus="parent.Ocultar()" value="<? echo ($vrXDefecto*$SaldoIni)/100;?>"
	onKeyUp="xNumero(this)" onKeyDown="xNumero(this)" readonly /></td>
<td align="center">
<input type="text" name="Cuenta" style="text-align:right; width:100px"
        onFocus="parent.Mostrar();
        parent.frames.Busquedas.location.href='Busquedas.php?DatNameSID=<? echo $DatNameSID?>&Frame=FrameOpener&NoMovimiento=1&ObjCuenta=Cuenta&Tipo=PlanCuentas&Cuenta='+this.value+'&Anio=<? echo $ND[year]?>'" 
		onkeyup="xNumero(this);
        parent.frames.Busquedas.location.href='Busquedas.php?DatNameSID=<? echo $DatNameSID?>&Frame=FrameOpener&NoMovimiento=1&ObjCuenta=Cuenta&Tipo=PlanCuentas&Cuenta='+this.value+'&Anio=<? echo $ND[year]?>';"
        onKeyDown="xNumero(this)" onBlur="campoNumero(this)"/>
</td>
<td><button type="submit" name="Nuevo" onFocus="parent.Ocultar()"><img src="/Imgs/b_save.png" title="Guardar"></button></td>
</tr>
</table>
	<input type="Hidden" name="Concepto" value="<? echo $Concepto?>">
	<input type="Hidden" name="Tercero" value="<? echo $Tercero?>">
	<input type="Hidden" name="Id" value="<? echo $Id?>">
	<input type="Hidden" name="SaldoIni" value="<? echo $SaldoIni?>">
	<input type="Hidden" name="Fecha" value="<? echo $Fecha?>">
	<input type="Hidden" name="Tercero" value="<? echo $Tercero?>">
	<input type="Hidden" name="CCAnt" value="<? echo $fila[0]?>">
    <input type="hidden" name="DatNameSID" value="<? echo $DatNameSID?>">
</form>
</body>