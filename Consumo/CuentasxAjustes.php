<? 
	if($DatNameSID){session_name("$DatNameSID");}
	session_start();
	include("Funciones.php");
	$ND = getdate();
	if(!$AlmacenPpal)
	{
		$cons = "Select AlmacenPpal from Consumo.UsuariosxAlmacenes where Usuario='$usuario[0]' and Compania='$Compania[0]'";
		$res = ExQuery($cons);
		$fila = ExFetch($res);
		$AlmacenPpal = $fila[0];
	}
        if(!$Tipo){$Tipo="Ingreso";}
	if(!$Anio){$Anio = $ND[year];}
	if(!$Tipo){ $Tipo = "Ingreso Ajuste";}
	if($Guardar)
	{
		if(!$Editar)
		{
			$cons = "Select Comprobante from Consumo.Comprobantes Where Compania='$Compania[0]' and AlmacenPpal='$AlmacenPpal' and Comprobante='Ajustes'";
			$res = ExQuery($cons);
			if(ExNumRows($res)==0)
			{
				$cons1 = "Insert into Consumo.Comprobantes(Compania,AlmacenPpal,Comprobante,NumeroInicial,Tipo) values
				('$Compania[0]','$AlmacenPpal','Ajustes','000000','Ajustes')";
				$res1 = ExQuery($cons1);	
			}
			
			$cons = "Select Tipo from Consumo.TiposComprobante Where Tipo = 'Ingreso Ajuste'";
			$res = ExQuery($cons);
			if(ExNumRows($res)==0)
			{
				$cons1 = "Insert into Consumo.TiposComprobante(Tipo) values ('Ingreso Ajuste')";	
			}
			
			$cons = "Select Tipo from Consumo.TiposComprobante Where Tipo = 'Salida Ajuste'";
			$res = ExQuery($cons);
			if(ExNumRows($res)==0)
			{
				$cons1 = "Insert into Consumo.TiposComprobante(Tipo) values ('Salida Ajuste')";	
			}
			
			$consx = "Insert into Consumo.AjustesxCtaContable (Compania,AlmacenPpal,Comprobante,Tipo,Cuenta,Anio) values
			('$Compania[0]','$AlmacenPpal','$Comprobante','$Tipo','$Cuenta',$Anio)";		
		}
		else
		{
			$consx = "Update Consumo.AjustesxCtaContable set Comprobante = '$Comprobante', Cuenta='$Cuenta'
			Where Compania='$Compania[0]' and AlmacenPpal='$AlmacenPpal' and Tipo='$Tipo' and Anio=$Anio";	
		}
		$resx = ExQuery($consx);	
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
		if(document.FORMA.Comprobante.value == ""){alert("Ingrese el Comprobante para el Ajuste");return false;}
		if(document.FORMA.Cuenta.value == ""){alert("Ingrese la Cuenta para el Ajuste");return false;}
		if(document.FORMA.ValidaCuenta.value != "1"){alert("Seleccione la Cuenta desde el Asistente");return false;}	
	}
</script>
<body background="/Imgs/Fondo.jpg">
<form name="FORMA" method="post" onSubmit="return Validar()">
<input type="hidden" name="DatNameSID" value="<? echo $DatNameSID?>" />
<table style='font : normal normal small-caps 12px Tahoma;' border="1" bordercolor="#e5e5e5">
<tr  bgcolor="#e5e5e5" style="font-weight:bold">
<td>Almacen Principal</td>
<td>
<select name="AlmacenPpal" onChange="FORMA.submit()" onFocus="Ocultar()">
<?
		$cons = "Select AlmacenPpal from Consumo.UsuariosxAlmacenes where Usuario='$usuario[0]' and Compania='$Compania[0]'";
		$res = ExQuery($cons);
		while($fila = ExFetch($res))
		{
			if($AlmacenPpal == $fila[0]){echo "<option selected value='$fila[0]'>$fila[0]</option>";}
			else{echo "<option value='$fila[0]'>$fila[0]</option>";}
		}
?>
    </select>
</td>
<td>Tipo</td>
<td>
<select name="Tipo" onChange="FORMA.submit()" onFocus="Ocultar()">
	<option value="Ingreso Ajuste" <? if($Tipo=="Ingreso Ajuste"){echo " selected ";}?>>Ingreso</option>
    <option value="Salida Ajuste" <? if($Tipo=="Salida Ajuste"){echo " selected ";}?>>Salida</option>
</select>
</td>
<td>A&ntilde;o</td>
<td>
<select name="Anio" onChange="FORMA.submit()" onFocus="Ocultar()">
	<?
    $cons = "Select Anio From Central.Anios Where Compania='$Compania[0]' order by Anio desc";
	$res = ExQuery($cons);
	while($fila = ExFetch($res))
	{
		if($Anio == $fila[0]){echo "<option selected value='$fila[0]'>$fila[0]</option>";}
		else{echo "<option value='$fila[0]'>$fila[0]</option>";	}
		
	}
	?>
</select>
</td>	
</tr>
<?
	$cons = "Select Comprobante,Cuenta From Consumo.AjustesxCtaContable Where Compania='$Compania[0]' and AlmacenPpal='$AlmacenPpal'
	and Anio = $Anio and Tipo = '$Tipo'";
	$res = ExQuery($cons);
	if(ExNumRows($res) != 0)
	{
		$fila = ExFetch($res);
		$Comprobante = $fila[0];
		$Cuenta = $fila[1];
		$Editar = 1;
		$ValidaCuenta = 1;	
	}
	else
	{
		$Comprobante = "";
		$Cuenta = "";
		$Editar = "";
	}
?>
<tr  bgcolor="#e5e5e5" style="font-weight:bold">
<td colspan="2" align="right">Comprobante Contable</td>
<td colspan="4">
<select name="Comprobante" onFocus="Ocultar()"><option></option>
	<?
    $cons = "Select Comprobante From Contabilidad.Comprobantes Where Compania = '$Compania[0]' order by Comprobante";
	$res = ExQuery($cons);
	while($fila = ExFetch($res))
	{
		if($Comprobante == $fila[0]){echo "<option selected value='$fila[0]'>$fila[0]</option>";}
		else{echo "<option value='$fila[0]'>$fila[0]</option>";}	
	}
	?>
</select>
</td>
</tr>
<tr  bgcolor="#e5e5e5" style="font-weight:bold">
<td colspan="2" align="right">Cuenta</td>
<td colspan="4">
<input type="text" name="Cuenta" id="Cuenta" value="<? echo $Cuenta?>" style="width:100%; text-align:right" 
 onFocus="Mostrar();if(this.value != ''){frames.Busquedas.location.href='Busquedas.php?DatNameSID=<? echo $DatNameSID?>&ObjetoValida=ValidaCuenta&Anio=<? echo $Anio?>&Tipo=PlanCuentas&Cuenta='+this.value+'&Objeto=Cuenta';}" 
 onkeyup="xNumero(this); ValidaCuenta.value='0';
 frames.Busquedas.location.href='Busquedas.php?DatNameSID=<? echo $DatNameSID?>&ObjetoValida=ValidaCuenta&Anio=<? echo $Anio?>&Tipo=PlanCuentas&Cuenta='+this.value+'&Objeto=Cuenta';"
 onKeyDown="xNumero(this)" onBlur="campoNumero(this)" /></td>
</tr>
</table>
<input type="hidden" name="Editar" value="<? echo $Editar?>"  />
<input type="submit" name="Guardar" value="Guardar" />
<input type="hidden" name="ValidaCuenta" id="ValidaCuenta" value="<? echo $ValidaCuenta?>" />
</form>
<iframe id="Busquedas" name="Busquedas" style="display:none;" src="Busquedas.php" frameborder="0" height="400"></iframe>
</body>