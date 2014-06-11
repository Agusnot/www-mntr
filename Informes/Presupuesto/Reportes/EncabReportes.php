<?
	if($DatNameSID){session_name("$DatNameSID");}
	session_start();
	include("Funciones.php");
	$ND=getdate();
	$AnioAc=$ND[year];
	session_register("ExcluyeComprobantes");
	$ExcluyeComprobantes="";
	$cons="Select Comprobante,Numero from Contabilidad.ExcluyeComprobantes where Compania='$Compania[0]'";
	$res=ExQuery($cons);
	while($fila=ExFetch($res))
	{
		$ExcluyeComprobantes=$ExcluyeComprobantes."(Comprobante!='$fila[0]')";
		if($n>1){$ExcluyeComprobantes=$ExcluyeComprobantes." and ";}
	}
	if(ExNumRows($res)==0){$ExcluyeComprobantes="1";}

	if(!$PerIni){$PerIni="$ND[year]-$ND[mon]-01";}
	if(!$PerFin){$PerFin="$ND[year]-$ND[mon]-$ND[mday]";}
	$AnioInc=$AnioAc-10;
	$AnioAf=$AnioAc+10;
?>


<body>
<table>
<tr><td>
<table border="1" bordercolor="#e5e5e5" style="font-family:<?echo $Estilo[8]?>;font-size:11;font-style:<?echo $Estilo[10]?>">
<tr><td>
<select name="Seleccion" onChange="location.href='EncabReportes.php?DatNameSID=<? echo $DatNameSID?>&Seleccion=' + this.value+'&Tipo=<?echo $Tipo?>'">
<option value=""></option>
<?
	$cons="Select Nombre from Central.Reportes where Clase='$Tipo' and Modulo='Presupuesto' Order By Id";
	$res=ExQuery($cons);echo ExError();
	while($fila=ExFetch($res))
	{
		if($Seleccion==$fila[0]){echo "<option value='$fila[0]' selected>$fila[0]</option>";}
		else{echo "<option value='$fila[0]'>$fila[0]</option>";}
	}
?>
</select></td></td><td>
<table  border="1" bordercolor="#e5e5e5" style="font-family:<?echo $Estilo[8]?>;font-size:11;font-style:<?echo $Estilo[10]?>">
<?
	if($Seleccion){
	$cons="Select Tipo,Archivo from Central.Reportes where Nombre='$Seleccion' and Modulo='Presupuesto'";
	$res=ExQuery($cons);
	$fila=ExFetch($res);
	$Tipo=$fila[0];
	$NomArchivo=$fila[1];
	$cons2="Select sum(NoCaracteres) from Presupuesto.EstructuraPuc where Compania='$Compania[0]'";
	$res2=ExQuery($cons2);
	$fila2=ExFetch($res2);
	$NoDigitos=$fila2[0];
	if(!$NoDigitos){$NoDigitos=0;}

	if($Tipo==1)
	{
		echo "<form name='FORMA' action='$NomArchivo' target='Abajo'>";
		echo "<tr bgcolor='#e5e5e5' align='center'><td colspan=2><center>Perido</td><td>Ceros</td><td>No. Digitos</td><td>Cuenta Inicial</td><td>Cta Final</td><td>CC</td>";
		echo "<tr><td><input type='Text' name='PerIni' style='width:70px;' value='$PerIni'></td>";
		echo "<td><input type='Text' name='PerFin' style='width:70px;' value='$PerFin'></td>";
		echo "<td><input  type='Checkbox' name='IncluyeCeros'></td>";
		echo "<td><input type='Text' name='NoDigitos' style='width:70px;' value=$NoDigitos></td>";
		echo "<td><input type='Text' name='CuentaIni' style='width:70px;' value=$CuentaIni>";?>
		<input type='Button' value='...' onClick="open('/Presupuesto/BusquedaxOtros.php?DatNameSID=<? echo $DatNameSID?>&Tipo=Cuentas&Campo=CuentaIni','','width=600,height=400')"></td>
<?
		echo "<td><input type='Text' name='CuentaFin' style='width:70px;' value='$CuentaFin' onfocus='CuentaFin.value=CuentaIni.value'>";
?>
		<input type='Button' value='...' onClick="open('/Presupuesto/BusquedaxOtros.php?DatNameSID=<? echo $DatNameSID?>&Tipo=Cuentas&Campo=CuentaFin','','width=600,height=400')"></td>
<?
		echo "<td><input type='Checkbox' name='IncluyeCC'><input type='Text' name='CC' style='width:40px;'></td>";
	}

	if($Tipo==2)
	{
		echo "<form name='FORMA' action='$NomArchivo' target='Abajo'>";
		echo "<tr bgcolor='#e5e5e5' align='center'><td><center>Corte</td><td>Ceros</td><td>No. Digitos</td><td>Cuenta Inicial</td><td>Cta Final</td><td>CC</td>";
		echo "<tr><td><input type='Text' name='PerFin' style='width:70px;' value='$PerFin'></td>";
		echo "<td><input  type='Checkbox' name='IncluyeCeros'></td>";
		echo "<td><input type='Text' name='NoDigitos' style='width:70px;' value=$NoDigitos></td>";
		echo "<td><input type='Text' name='CuentaIni' style='width:70px;' value=$CuentaIni>";?>
		<input type='Button' value='...' onClick="open('/Presupuesto/BusquedaxOtros.php?DatNameSID=<? echo $DatNameSID?>&Tipo=Cuentas&Campo=CuentaIni','','width=600,height=400')"></td>
<?
		echo "<td><input type='Text' name='CuentaFin' style='width:70px;' value='$CuentaFin' onfocus='CuentaFin.value=CuentaIni.value'>";
?>
		<input type='Button' value='...' onClick="open('/Presupuesto/BusquedaxOtros.php?DatNameSID=<? echo $DatNameSID?>&Tipo=Cuentas&Campo=CuentaFin','','width=600,height=400')"></td>
<?
		echo "<td><input type='Checkbox' name='IncluyeCC'><input type='Text' name='CC' style='width:40px;'></td>";
	}


	if($Tipo==3)
	{
		echo "<form name='FORMA' action='$NomArchivo' target='Abajo'>";
		echo "<tr bgcolor='#e5e5e5' align='center'><td colspan=2><center>Perido</td><td>CC</td><td>No. Digitos</td><td>Cuenta Inicial</td><td>Cta Final</td><td>Tercero</td><td>Comprobante</td>";
		echo "<tr><td><input type='Text' name='PerIni' style='width:70px;' value='$PerIni'></td>";
		echo "<td><input type='Text' name='PerFin' style='width:70px;' value='$PerFin'></td>";
		echo "<td><input type='Text' name='CC' style='width:40px;'></td>";
		echo "<td><input type='Text' name='NoDigitos' style='width:70px;' value=$NoDigitos></td>";
		echo "<td><input type='Text' name='CuentaIni' style='width:70px;' value='$CuentaIni'>";?>
		<input type='Button' value='...' onClick="open('/Presupuesto/BusquedaxOtros.php?DatNameSID=<? echo $DatNameSID?>&Tipo=Cuentas&Campo=CuentaIni','','width=600,height=400')"></td>
<?
		echo "<td><input type='Text' name='CuentaFin' style='width:70px;' value='$CuentaFin' onfocus='CuentaFin.value=CuentaIni.value'>";
?>
		<input type='Button' value='...' onClick="open('/Presupuesto/BusquedaxOtros.php?DatNameSID=<? echo $DatNameSID?>&Tipo=Cuentas&Campo=CuentaFin','','width=600,height=400')"></td>
<?
		echo "<td><input type='Text' name='Tercero' style='width:70px;' value=$Tercero>";?>
		<input type='Button' value='...' onClick="open('/Presupuesto/BusquedaxOtros.php?DatNameSID=<? echo $DatNameSID?>&Tipo=Tercero&Campo=Tercero','','width=600,height=400')"></td>
<?		echo "<td><input type='Text' name='Comprobante' style='width:70px;' value=$Comprobante>";?>
		<input type='Button' value='...' onClick="open('/Presupuesto/BusquedaxOtros.php?DatNameSID=<? echo $DatNameSID?>&Tipo=Comprobante&Campo=Comprobante','','width=600,height=400')"></td>
<?
	}
	if($Tipo==4)
	{
		echo "<form name='FORMA' action='$NomArchivo' target='Abajo'>";
		echo "<tr bgcolor='#e5e5e5' align='center'><td><center>Corte</td><td>No. Digitos</td>";
		echo "<tr><td><input type='Text' name='PerFin' style='width:70px;' value='$PerFin'></td>";
		echo "<td><input type='Text' name='NoDigitos' style='width:70px;' value=$NoDigitos></td>";
	}
	if($Tipo==5)
	{
		echo "<form name='FORMA' action='$NomArchivo' target='Abajo'>";
		echo "<tr bgcolor='#e5e5e5' align='center'><td><center>A&ntilde;o</td><td>Mes Ini</td><td>Mes Fin</td><td>No. Digitos</td><td>Vigencia</td>";
		echo "<tr>
		<td><input type='Text' name='Anio' style='width:70px;' value=$AnioAc></td>";?>
		<td>
		<select name="MesIni" onChange="document.FORMA.submit();">
<?
	$cons="Select Numero,Mes from Central.Meses order by numero";
	$res=ExQuery($cons);
	while($fila=ExFetch($res))
	{
		if($MesIni==$fila[0]){echo "<option selected value='$fila[0]'>$fila[1]</option>";}
		else{echo "<option value='$fila[0]'>$fila[1]</option>";}
	}
?>
		</td>
<td><select name="MesFin" onchange="document.FORMA.submit();">
<?
	$cons="Select Numero,Mes from Central.Meses order by numero";
	$res=ExQuery($cons);
	while($fila=ExFetch($res))
	{
		if($MesFin==$fila[0]){echo "<option selected value='$fila[0]'>$fila[1]</option>";}
		else{echo "<option value='$fila[0]'>$fila[1]</option>";}
	}
?>
</select></td>
<?	
		echo "<td><input type='Text' name='NoDigitos' style='width:70px;' value=$NoDigitos></td>";
		echo "<td>
		<select name='ClaseVigencia'><option>";
		$cons="Select TiposVigencia from Presupuesto.TiposVigencia";
		$res=ExQuery($cons);
		while($fila=ExFetch($res))
		{
			echo "<option value='$fila[0]'>$fila[0]</option>";		
		}
		echo "</select>
		</td>";
}
	}
	if($Tipo==6)
	{
		echo "<form name='FORMA' action='$NomArchivo' target='Abajo'>";?>
		<tr bgcolor='#e5e5e5' align='center'><td colspan="2">Periodo</td>
		<tr><td>
		<select name="Anio"><?
		for($i=$AnioInc;$i<$AnioAf;$i++)
		if($i==$AnioAc){echo "<option selected value=$i>$i</option>";}
		else{echo "<option value=$i>$i</option>";}
		?></select>
		<select name="MesIni">
		<?for($i=1;$i<=12;$i++)
		{
			if($ND[mon]==$i){echo "<option selected value='$i'>$NombreMesC[$i]</option>";}
			else{echo "<option value='$i'>$NombreMesC[$i]</option>";}
		}
		?>
		</select>
		<input type='Text' name='DiaIni' style='width:20px;' maxlength="2" value='01'>

		</td>
		<td>
		<select name="MesFin">
		<?for($i=1;$i<=12;$i++)
		{
			if($ND[mon]==$i){echo "<option selected value='$i'>$NombreMesC[$i]</option>";}
			else{echo "<option value='$i'>$NombreMesC[$i]</option>";}
		}
		?>
		</select>
		<input type='Text' name='DiaFin' style='width:20px;' maxlength="2" value='<?echo $ND[mday]?>'>
<?	}

	if($Tipo==7)
	{
		if(!$Anio){$Anio=$ND[year];}
		echo "<form name='FORMA' action='$NomArchivo' target='Abajo'>";
		echo "<tr bgcolor='#e5e5e5' align='center'><td><center>A&ntilde;o</td><td>Trimestre</td>";
		echo "<tr><td><input type='Text' name='Anio' style='width:40px;' value='$Anio'></td>";
		echo "<td>
		<select name='Trimestre' onchange='document.FORMA.submit();'>
		<option value=01>01 Ene-Mar</option>
		<option value=02>02 Abr-Jun</option>
		<option value=03>03 Jul-Sep</option>
		<option value=04>04 Oct-Dic</option>
		<option value=00>00 Anual</option>
		</select>
		</td>";
	}
	if($Tipo==8)
	{
		echo "<form name='FORMA' action='$NomArchivo' target='Abajo'>";
	?>
		<tr bgcolor='#e5e5e5' align='center'><td>A&&ntilde;o</td>		
		<tr><td>
		<select name="Anio"><?
		for($i=$AnioInc;$i<$AnioAf;$i++)
		if($i==$AnioAc){echo "<option selected value=$i>$i</option>";}
		else{echo "<option value=$i>$i</option>";}
		?></select>
		
<?	}
	if($Tipo==9)
	{
		echo "<form name='FORMA' action='$NomArchivo' target='Abajo'>";
		echo "<tr bgcolor='#e5e5e5' align='center'><td><center>A&ntilde;o</td><td>Mes Ini</td><td>Mes Fin</td>";
		echo "<tr>
		<td><input type='Text' name='Anio' style='width:70px;' value=$AnioAc></td>";?>
		<td>
		<select name="MesIni" onChange="document.FORMA.submit();">
<?
	$cons="Select Numero,Mes from Central.Meses order by numero";
	$res=ExQuery($cons);
	while($fila=ExFetch($res))
	{
		if($MesIni==$fila[0]){echo "<option selected value='$fila[0]'>$fila[1]</option>";}
		else{echo "<option value='$fila[0]'>$fila[1]</option>";}
	}
?>
		</td>
<td><select name="MesFin" onchange="document.FORMA.submit();">
<?
	$cons="Select Numero,Mes from Central.Meses order by numero";
	$res=ExQuery($cons);
	while($fila=ExFetch($res))
	{
		if($MesFin==$fila[0]){echo "<option selected value='$fila[0]'>$fila[1]</option>";}
		else{echo "<option value='$fila[0]'>$fila[1]</option>";}
	}
?>
</select></td>
<?	
}
	
?>

</table>
</td>
<TD>
<table border="0" bordercolor="#e5e5e5" style="font-family:<?echo $Estilo[8]?>;font-size:12;font-style:<?echo $Estilo[10]?>">
<tr><td align="center">Filas</td></tr>
<tr><td><input type="Text" name="Encabezados" style="width:40px;" value="37"></td></tr>
</table>
</TD>

<td><input type="Submit" name="Ver" value="Ver"></td>
<td><input type="Button" name="Cerrar" value="Cerrar" onClick="parent.location.href='/salud/Portada.php'"></td>
<input type="hidden" name="DatNameSID" value="<? echo $DatNameSID?>">
</table>
</table>
</form>
</body>