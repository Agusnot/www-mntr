<?
	if($DatNameSID){session_name("$DatNameSID");}
	session_start();
	include("Funciones.php");
	$ND = getdate();
        if($Guardar)
	{
		if(!$CuentaCru)
		{
			if($Bancos)
			{
				$CuentaCru = "Bancos";
			}
		}
		if(!$Editar)
		{
			$cons = "Insert into Contabilidad.CruzarComprobantes
			(Comprobante,CruzarCon,Movimiento,Cuenta,CuentaCruzar,Compania,Anio) values
			('$Comprobante','$CruzarCon','$Movimiento','$Cuenta','$CuentaCru','$Compania[0]',$Anio)";
		}
		else
		{
			$cons = "Update Contabilidad.CruzarComprobantes set
			Comprobante = '$Comprobante', CruzarCon = '$CruzarCon', Movimiento = '$Movimiento', Cuenta = '$Cuenta',
			CuentaCruzar = '$CuentaCru' where Compania='$Compania[0]' and Comprobante = '$ComprobanteX' and CruzarCon = '$CruzarConX'
			and Movimiento='$MovimientoX' and Cuenta = '$CuentaX' and CuentaCruzar = '$CuentaCruX' and Anio = '$Anio'";
		}
		$res = ExQuery($cons);
		echo ExError();
		?><script language="javascript">location.href="ConfCruceComprobantes.php?DatNameSID=<? echo $DatNameSID?>&Anio=<? echo $Anio?>";</script><?
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
	function Validar()
	{
		var b = 0;
		if(document.FORMA.Comprobante.value==""){alert("Por Favor llene el campo Comprobante"); b=1;}
		else{if(document.FORMA.CruzarCon.value==""){alert("Por Favor llene el campo Cruzar Con"); b=1;}
			else{if(document.FORMA.Cuenta.value==""){alert("Por Favor llene el campo Cuenta"); b=1;}
				else{if(document.FORMA.CuentaCru.value==""){alert("Por Favor llene el campo Cuenta a Cruzar"); b=1;}}}}
		if(b==1){return false;}
	}
</script>
<?
	if($Editar)
	{
		$cons = "Select Comprobante,CruzarCon,Movimiento,Cuenta,CuentaCruzar from Contabilidad.CruzarComprobantes 
		where Compania='$Compania[0]' and Comprobante = '$Comprobante' and CruzarCon = '$CruzarCon'
                and Movimiento='$Movimiento' and Cuenta='$Cuenta' and CuentaCruzar='$CuentaCruzar'
                and Anio=$Anio";
                $res = ExQuery($cons);
		$fila = ExFetch($res);
		$Comprobante = $fila[0]; $CruzarCon = $fila[1]; $Movimiento = $fila[2]; $Cuenta = $fila[3]; 
		$CuentaCru = $fila[4];
		if($fila[4]=="Bancos"){ $ChkBancos = " checked "; $disCuentaCru = " disabled ";}
		if($Movimiento=="Haber"){ $HS = " selected ";}
		else{ $DS = " selected ";}
	}
?>
<body background="/Imgs/Fondo.jpg">
<form name="FORMA" method="post" onSubmit="return Validar()">
<input type="hidden" name="Editar" value="<? echo $Editar?>" />
<input type="hidden" name="ComprobanteX" value="<? echo $Comprobante?>" />
<input type="hidden" name="CruzarConX" value="<? echo $CruzarCon ?>" />
<input type="hidden" name="MovimientoX" value="<? echo $Movimiento?>" />
<input type="hidden" name="CuentaX" value="<? echo $Cuenta?>" />
<input type="hidden" name="CuentaCruX" value="<? echo $CuentaCru?>" />
<input type="hidden" name="Anio" value="<? echo $Anio?>" />
<table cellpadding="4"  border="1" bordercolor="<?echo $Estilo[1]?>" style="font-family:<?echo $Estilo[8]?>;font-size:12px;font-style:<?echo $Estilo[10]?>">
	<tr>
    	<td colspan="4" bgcolor="#e5e5e5" align="center" style="font-weight:bold">Nuevo Cruce de Comprobantes Para el A&ntilde;o <? echo $Anio?></td>
    </tr>
    <tr>
    	<td bgcolor="#e5e5e5">Comprobante:</td>
        <td colspan="3"><input type="text" name="Comprobante" value="<? echo $Comprobante?>" style="width:100%" 
        onFocus="Mostrar();frames.Busquedas.location.href='Busquedas.php?DatNameSID=<? echo $DatNameSID?>&ObjComprobante=Comprobante&Tipo=Comprobante&Comprobante='+this.value" 
		onkeyup="xLetra(this);frames.Busquedas.location.href='Busquedas.php?DatNameSID=<? echo $DatNameSID?>&Tipo=Comprobante&Comprobante='+this.value;"
        onKeyDown="xLetra(this)"/></td>
    </tr>
    <tr>
    	<td bgcolor="#e5e5e5">Cruzar con:</td>
        <td colspan="3"><input type="text" name="CruzarCon" value="<? echo $CruzarCon?>" style="width:100%" 
        onFocus="Mostrar();frames.Busquedas.location.href='Busquedas.php?DatNameSID=<? echo $DatNameSID?>&ObjComprobante=CruzarCon&Tipo=Comprobante&Comprobante='+this.value;" 
		onkeyup="xLetra(this);frames.Busquedas.location.href='Busquedas.php?DatNameSID=<? echo $DatNameSID?>&ObjComprobante=CruzarCon&Tipo=Comprobante&Comprobante='+this.value;"
        onKeyDown="xLetra(this)"/></td>
    </tr>
    <tr>
    	<td bgcolor="#e5e5e5" colspan="3" align="right">Movimiento: </td>
        <td><select name="Movimiento" style="width:100%">
        	<option <? echo $HS ?> value="Haber" >HABER</option>
            <option <? echo $DS ?> value="Debe" >DEBE</option>
        </select></td>
    </tr>
    <tr>
    	<td bgcolor="#e5e5e5">Cuenta:</td>
        <td><input type="text" name="Cuenta" value="<? echo $Cuenta?>"
        onFocus="Mostrar();frames.Busquedas.location.href='Busquedas.php?DatNameSID=<? echo $DatNameSID?>&NoMovimiento=1&ObjCuenta=Cuenta&Tipo=PlanCuentas&Cuenta='+this.value+'&Anio=<? echo $Anio?>'" 
		onkeyup="xNumero(this);frames.Busquedas.location.href='Busquedas.php?DatNameSID=<? echo $DatNameSID?>&NoMovimiento=1&ObjCuenta=Cuenta&Tipo=PlanCuentas&Cuenta='+this.value+'&Anio=<? echo $Anio?>';"
        onKeyDown="xNumero(this)" onBlur="campoNumero(this)"  /></td>
        <td bgcolor="#e5e5e5">Cuenta a Cruzar:</td>
        <td><input type="text" name="CuentaCru" value="<? echo $CuentaCru?>" <? echo $disCuentaCru ?>
        onFocus="Mostrar();frames.Busquedas.location.href='Busquedas.php?DatNameSID=<? echo $DatNameSID?>&NoMovimiento=1&ObjCuenta=CuentaCru&Tipo=PlanCuentas&Cuenta='+this.value+'&Anio=<? echo $Anio ?>'" 
		onkeyup="xNumero(this);frames.Busquedas.location.href='Busquedas.php?DatNameSID=<? echo $DatNameSID?>&NoMovimiento=1&ObjCuenta=CuentaCru&Tipo=PlanCuentas&Cuenta='+this.value+'&Anio=<? echo $Anio ?>';"
        onKeyDown="xNumero(this)" onBlur="campoNumero(this)" /></td>
    </tr>
    <tr>
    	<td colspan="4" align="right" bgcolor="#e5e5e5">Bancos
        	<input type="checkbox" name="Bancos" <? echo $ChkBancos ?> onClick="if(this.checked==true){FORMA.CuentaCru.value='Bancos';FORMA.CuentaCru.disabled=true;}
            else{FORMA.CuentaCru.value='';FORMA.CuentaCru.disabled=false;};">
        </td>
    </tr>
</table>
<input type="hidden" name="DatNameSID" value="<? echo $DatNameSID?>">
<input type="submit" name="Guardar" value="Guardar" />
<input type="button" name="Cancelar" value="Cancelar" onClick="location.href='ConfCruceComprobantes.php?DatNameSID=<? echo $DatNameSID?>&Anio=<? echo $Anio?>'" />
</form>
<iframe id="Busquedas" name="Busquedas" style="display:none;" src="Busquedas.php?DatNameSID=<? echo $DatNameSID?>&" frameborder="0" height="400"></iframe>
</body>