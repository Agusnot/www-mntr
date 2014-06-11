<?
	if($DatNameSID){session_name("$DatNameSID");}
	session_start();
	include("Funciones.php");
	if($Guardar)
	{
		if($Consumo=="on"){$Consumo=1;}else{$Consumo=0;}
		if(!$Editar)
		{
			$cons = "Insert into Contabilidad.BasesRetencion 
			(Compania,Concepto,Porcentaje,Base,Cuenta,MontoMinimo,IVA,Anio,TipoRetencion,Consumo) values
			('$Compania[0]','$Concepto',$Porc,$Base,'$Cuenta',$MontoMin,$IVA,$Anio,'$TipoRetencion',$Consumo)";
		}
		else
		{
			$cons = "Update Contabilidad.BasesRetencion set Concepto = '$Concepto', Porcentaje = $Porc,
			Base = $Base, Cuenta = '$Cuenta', MontoMinimo = '$MontoMin', IVA = $IVA,Consumo=$Consumo, TipoRetencion = '$TipoRetencion' 
			where Concepto = '$ConceptoX' and Anio = '$Anio' and Compania = '$Compania[0]'";
		}
		echo ExError();
		$res = ExQuery($cons);
		?><script language="javascript">location.href="ConfBDRetencion.php?DatNameSID=<? echo $DatNameSID?>&Anio=<? echo $Anio;?>";</script><?
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
		if(document.FORMA.ValCuenta.value=="0"){alert("Seleccione una cuenta de la lista!!");return false;}
		if (document.FORMA.Concepto.value == ""){alert("Por favor, llene el campo Concepto");b = 1;}
		else{if(document.FORMA.Porc.value == ""){alert("Por favor, llene el campo Porcentaje");b = 1;}
			else{if(document.FORMA.Base.value == ""){alert("Por favor, llene el campo Base");b = 1;}
				else{if(document.FORMA.Cuenta.value == ""){alert("Por favor, llene el campo Cuenta");b = 1;}
					else{if(document.FORMA.MontoMin.value == ""){alert("Por favor, llene el campo Monto Minimo");b = 1;}
						else{if(document.FORMA.IVA.value == ""){alert("Por favor, llene el campo IVA");b = 1;}}}}}}
		if(b!=0){ return false;}
	}
</script>
<body background="/Imgs/Fondo.jpg">
<form name="FORMA" method="post" onSubmit="return Validar()">
<input type="hidden" name="Anio" value="<? echo $Anio?>" />
<input type="hidden" name="ConceptoX" value="<? echo $Concepto ?>" />
<input type="hidden" name="Editar" value="<? echo $Editar?>"  />
<?
	if($Editar)
	{
		$cons1 = "Select Concepto,TipoRetencion,Porcentaje,Base,Cuenta,MontoMinimo,Iva,Consumo 
		from Contabilidad.BasesRetencion where Compania = '$Compania[0]' and Anio = '$Anio' and Concepto = '$Concepto'
		and TipoRetencion = '$TipoRetencion'";
		$res1 = ExQuery($cons1);
		$fila1 = ExFetch($res1);
		$Concepto = $fila1[0]; $TipoRetencion = $fila1[1]; $Porc = $fila1[2]; $Base = $fila1[3];
		$Cuenta = $fila1[4]; $MontoMin = $fila1[5]; $IVA = $fila1[6];$ValidaCuenta=1;$xConsumo=$fila1[7];
	}
?>
<table cellpadding="4"  border="1" bordercolor="<?echo $Estilo[1]?>" style="font-family:<?echo $Estilo[8]?>;font-size:12px;font-style:<?echo $Estilo[10]?>">
	<tr align="center" style="font-weight:bold">
    	<td colspan="4" bgcolor="#e5e5e5">Nueva Base de Retencion A&ntilde;o <? echo $Anio?></td>
    </tr>
    <tr>
    	<td bgcolor="#e5e5e5">Tipo de Retencion:</td>
        <td colspan="3"><select name="TipoRetencion" style="width:100%" onFocus="Ocultar();">
        <?
        	$cons = "Select Tipo from Contabilidad.TiposRetencion where Compania = '$Compania[0]'";
			$res = ExQuery($cons);
			while($fila = ExFetch($res))
			{
				if($TipoRetencion == $fila[0]){ echo "<option selected value='$fila[0]'>$fila[0]</option>";}
				else {echo "<option value='$fila[0]'>$fila[0]</option>";}
			}	
		?>
        </select></td>
    </tr>
    <tr>
    	<td bgcolor="#e5e5e5">Concepto:</td>
        <td colspan="3"><input type="text" name="Concepto" value="<? echo $Concepto?>" style="width:100%"  onfocus="Ocultar();" 
        onKeyUp="xLetra(this)" onKeyDown="xLetra(this)"/></td>
    </tr>
    <tr>
        <td bgcolor="#e5e5e5">Porcentaje:</td>
        <td><input type="text" name="Porc" value="<? echo $Porc?>" onFocus="Ocultar();" maxlength="5" size="6"
        onKeyUp="xNumero(this)" onKeyDown="xNumero(this)" onBlur="campoNumero(this)" /></td>
       	<td bgcolor="#e5e5e5">Base:</td>
        <td><input type="text" name="Base" value="<? echo $Base?>" onFocus="Ocultar();" maxlength="6" size="4"
        onKeyUp="xNumero(this)" onKeyDown="xNumero(this)" onBlur="campoNumero(this)" /></td>
    </tr>
    <tr>
        <td bgcolor="#e5e5e5" colspan="3" align="right">Cuenta:</td>
        <td><input type="text" name="Cuenta" id="Cuenta" value="<? echo $Cuenta?>"
        onFocus="Mostrar();frames.Busquedas.location.href='Busquedas.php?DatNameSID=<? echo $DatNameSID?>&Tipo=PlanCuentasDetalle&Objeto=Cuenta&Cuenta='+this.value+'&Anio=<? echo $Anio?>';" 
		onkeyup="xNumero(this);frames.Busquedas.location.href='Busquedas.php?DatNameSID=<? echo $DatNameSID?>&Tipo=PlanCuentasDetalle&Objeto=Cuenta&Cuenta='+this.value+'&Anio=<? echo $Anio?>';ValCuenta.value=0"
        onKeyDown="xNumero(this)" onBlur="campoNumero(this)"  /></td>
    </tr>
    <tr>
    	<td bgcolor="#e5e5e5">Monto Minimo:</td>
        <td><input type="text" name="MontoMin" value="<? echo $MontoMin?>" onFocus="Ocultar();" maxlength="10" size="11"
        onKeyUp="xNumero(this)" onKeyDown="xNumero(this)" onBlur="campoNumero(this)"/></td>
        <td bgcolor="#e5e5e5">IVA: </td>
        <td><input type="text" name="IVA" value="<? echo $IVA?>" onFocus="Ocultar();" maxlength="5" size="6"
        onKeyUp="xNumero(this)" onKeyDown="xNumero(this)" onBlur="campoNumero(this)"/></td>
    </tr>
    <tr bgcolor="#e5e5e5"><td colspan="4" style="font-weight:bold" align="center">Afectaciones en Linea</td></tr>
    <tr><td colspan="4">Consumo 
    <? if($xConsumo==1){?>
    <input type="checkbox" name="Consumo" checked>
    <? }else{?>
    <input type="checkbox" name="Consumo">
	<? }?>
    </td></tr>
</table>
<input type="hidden" name="DatNameSID" value="<? echo $DatNameSID?>">
<input type="submit" name="Guardar" value="Guardar" />
<input type="Hidden" name="ValCuenta" value="<? echo $ValCuenta?>">
<input type="button" name="Cancelar" value="Cancelar" onClick="location.href='ConfBDRetencion.php?DatNameSID=<? echo $DatNameSID?>&Anio=<? echo $Anio?>'" />
</form>
<iframe id="Busquedas" name="Busquedas" style="display:none;" src="" frameborder="0" height="400"></iframe>
</body>