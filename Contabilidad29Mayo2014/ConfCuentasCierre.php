<?
	if($DatNameSID){session_name("$DatNameSID");}
	session_start();
	include("Funciones.php");
	$ND=getdate();
	if(!$AnioSel){$AnioSel=$ND[year];}
	if($Guardar)
	{
		$cons="Select * from Contabilidad.CuentasCierre where Compania='$Compania[0]' and Anio='$AnioSel'";
		$res=ExQuery($cons);
		if(ExNumRows($res)>=1)
		{
			$cons2="Update Contabilidad.CuentasCierre set Ingresos='$Ingresos',Gastos='$Gastos',Utilidad='$Utilidad',Perdida='$Perdida',Costos='$Costos' where Compania='$Compania[0]' and Anio='$AnioSel'";
		}
		else
		{
			$cons2="Insert into Contabilidad.CuentasCierre (Ingresos,Gastos,Utilidad,Perdida,Anio,Compania,Costos) values ('$Ingresos','$Gastos','$Utilidad','$Perdida','$AnioSel','$Compania[0]','$Costos')";
		}
		$res2=ExQuery($cons2);
		echo ExError($res2);
	}
?>
<body background="/Imgs/Fondo.jpg">
<script language="JavaScript">
	function Validar()
	{
		if(document.FORMA.TipoIngresos.value!="Detalle"){alert("Las cuentas deben ser de detalle unicamente");return false;}
		if(document.FORMA.TipoGastos.value!="Detalle"){alert("Las cuentas deben ser de detalle unicamente");return false;}
		if(document.FORMA.TipoUtilidad.value!="Detalle"){alert("Las cuentas deben ser de detalle unicamente");return false;}
		if(document.FORMA.TipoPerdida.value!="Detalle"){alert("Las cuentas deben ser de detalle unicamente");return false;}
		if(document.FORMA.TipoCostos.value!="Detalle"){alert("Las cuentas deben ser de detalle unicamente");return false;}
	}
	
</script>
<form name="FORMA" onSubmit="return Validar()">
<table border="0">
<tr><td>
<table cellpadding="6"  border="1" bordercolor="<?echo $Estilo[1]?>" style="font-family:<?echo $Estilo[8]?>;font-size:<?echo $Estilo[9]?>;font-style:<?echo $Estilo[10]?>">
<tr bgcolor="<?echo $Estilo[1]?>" style="color:white;font-weight:bold"><td align="center">A&ntilde;o</td><td>
<select name="AnioSel" onChange="document.FORMA.submit();">
<?	

	$cons="Select Anio from Central.Anios where Compania='$Compania[0]' order by Anio desc";
	$res=ExQuery($cons);
	while($fila=ExFetch($res))
	{
		if($AnioSel==$fila[0]){echo "<option value='$fila[0]' selected>$fila[0]</option>";}
		else{echo "<option value='$fila[0]'>$fila[0]</option>";}
	}
?>
</select>
</td></tr>
<tr bgcolor="<?echo $Estilo[1]?>" style="color:white;font-weight:bold" align="center"><td>Concepto</td><td>Cuenta</td></tr>
<?

	$cons2="Select Ingresos,Gastos,Utilidad,Perdida,Costos from Contabilidad.CuentasCierre where Anio='$AnioSel' and Compania='$Compania[0]'";
	$res2=ExQuery($cons2);
	$fila2=ExFetch($res2);

	$res3=ExQuery("Select Tipo from Contabilidad.PlanCuentas where Cuenta='$fila2[0]' and Compania='$Compania[0]' and Anio=$AnioSel");$fila3=ExFetch($res3);$TipoIngresos=$fila3[0];
	$res3=ExQuery("Select Tipo from Contabilidad.PlanCuentas where Cuenta='$fila2[1]' and Compania='$Compania[0]' and Anio=$AnioSel");$fila3=ExFetch($res3);$TipoGastos=$fila3[0];
	$res3=ExQuery("Select Tipo from Contabilidad.PlanCuentas where Cuenta='$fila2[2]' and Compania='$Compania[0]' and Anio=$AnioSel");$fila3=ExFetch($res3);$TipoUtilidad=$fila3[0];
	$res3=ExQuery("Select Tipo from Contabilidad.PlanCuentas where Cuenta='$fila2[3]' and Compania='$Compania[0]' and Anio=$AnioSel");$fila3=ExFetch($res3);$TipoPerdida=$fila3[0];
	$res3=ExQuery("Select Tipo from Contabilidad.PlanCuentas where Cuenta='$fila2[4]' and Compania='$Compania[0]' and Anio=$AnioSel");$fila3=ExFetch($res3);$TipoCostos=$fila3[0];
	?>
	<tr><td>Ingresos</td><td><input type='Text' name='Ingresos' value="<?echo $fila2[0]?>" onFocus="frames.Busquedas.location.href='Busquedas.php?DatNameSID=<? echo $DatNameSID?>&Objeto=Ingresos&Tipo=CuentasCierre&Cuenta='+this.value+'&Anio='+document.FORMA.AnioSel.value" onKeyUp="frames.Busquedas.location.href='Busquedas.php?DatNameSID=<? echo $DatNameSID?>&Objeto=Ingresos&Tipo=CuentasCierre&Cuenta='+this.value+'&Anio='+document.FORMA.AnioSel.value"></td></tr>
	<tr><td>Gastos</td><td><input type='Text' name='Gastos' value="<?echo $fila2[1]?>" onFocus="frames.Busquedas.location.href='Busquedas.php?DatNameSID=<? echo $DatNameSID?>&Objeto=Gastos&Tipo=CuentasCierre&Cuenta='+this.value+'&Anio='+document.FORMA.AnioSel.value" onKeyUp="frames.Busquedas.location.href='Busquedas.php?DatNameSID=<? echo $DatNameSID?>&Objeto=Gastos&Tipo=CuentasCierre&Cuenta='+this.value+'&Anio='+document.FORMA.AnioSel.value"></td></tr>
	<tr><td>Costos</td><td><input type='Text' name='Costos' value="<?echo $fila2[4]?>" onFocus="frames.Busquedas.location.href='Busquedas.php?DatNameSID=<? echo $DatNameSID?>&Objeto=Costos&Tipo=CuentasCierre&Cuenta='+this.value+'&Anio='+document.FORMA.AnioSel.value" onKeyUp="frames.Busquedas.location.href='Busquedas.php?DatNameSID=<? echo $DatNameSID?>&Objeto=Costos&Tipo=CuentasCierre&Cuenta='+this.value+'&Anio='+document.FORMA.AnioSel.value"></td></tr>
	<tr><td>Utilidad</td><td><input type='Text' name='Utilidad' value="<?echo $fila2[2]?>" onFocus="frames.Busquedas.location.href='Busquedas.php?DatNameSID=<? echo $DatNameSID?>&Objeto=Utilidad&Tipo=CuentasCierre&Cuenta='+this.value+'&Anio='+document.FORMA.AnioSel.value" onKeyUp="frames.Busquedas.location.href='Busquedas.php?DatNameSID=<? echo $DatNameSID?>&Objeto=Utilidad&Tipo=CuentasCierre&Cuenta='+this.value+'&Anio='+document.FORMA.AnioSel.value"></td></tr>
	<tr><td>Perdida</td><td><input type='Text' name='Perdida' value="<?echo $fila2[3]?>" onFocus="frames.Busquedas.location.href='Busquedas.php?DatNameSID=<? echo $DatNameSID?>&Objeto=Perdida&Tipo=CuentasCierre&Cuenta='+this.value+'&Anio='+document.FORMA.AnioSel.value" onKeyUp="frames.Busquedas.location.href='Busquedas.php?DatNameSID=<? echo $DatNameSID?>&Objeto=Perdida&Tipo=CuentasCierre&Cuenta='+this.value+'&Anio='+document.FORMA.AnioSel.value"></td></tr>

<input type="Hidden" name="TipoIngresos" value="<?echo $TipoIngresos?>">
<input type="Hidden" name="TipoGastos" value="<?echo $TipoGastos?>">
<input type="Hidden" name="TipoCostos" value="<?echo $TipoCostos?>">
<input type="Hidden" name="TipoUtilidad" value="<?echo $TipoUtilidad?>">
<input type="Hidden" name="TipoPerdida" value="<?echo $TipoPerdida?>">
</table><br>
<input type="Submit" value="Guardar" name="Guardar">
<input type="hidden" name="DatNameSID" value="<? echo $DatNameSID?>">
</form>
</td>
<td>
<iframe id="Busquedas" name="Busquedas" src="Busquedas.php?DatNameSID=<? echo $DatNameSID?>" frameborder="0" height="400"></iframe>
</td>
</tr>
</table>
</body>