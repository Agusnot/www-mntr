<?
	if($DatNameSID){session_name("$DatNameSID");}
	session_start();
	include("Funciones.php");
?>
<script language='javascript' src="/calendario/popcalendar.js"></script>
<script language="javascript">
	function Validar(x)
	{
		if(x==1)
		{
			document.FORMA.action = "ActuaTarifasIncPorc.php?DatNameSID=<? echo $DatNameSID?>";
			document.FORMA.submit();	
		}
	}
</script>
<body background="/Imgs/Fondo.jpg">
<form name="FORMA" method="post">
<input type="hidden" name="DatNameSID" value="<? echo $DatNameSID?>"/>
     <select style="width:300px;" name="Tarifario" onChange="document.FORMA.submit();">
     <option>-- Seleccione Tarifario--</option>
<?
	$cons="Select Tarifario from Facturacion.TarifariosCUPS where Compania='$Compania[0]' 
	 and Estado='AC'";
	$res=ExQuery($cons);
	while($fila=ExFetch($res))
	{
		if($Tarifario==$fila[0]){echo "<option selected value='$fila[0]'>$fila[0]</option>";}
		else{echo "<option value='$fila[0]'>$fila[0]</option>";}
	}
?>
	</select>
<?
	if($Tarifario && $Tarifario!="--Tarifario--")
	{
		$cons = "Select Codigo,Nombre,Grupo,Tipo from Central.CUPS
		";
		$res = ExQuery($cons);echo ExError();
		echo "<table style='font : normal normal small-caps 12px Tahoma;' border='1' bordercolor='#e5e5e5'>
		<tr align='center' bgcolor='#e5e5e5' style='font-weight:bold'><td colspan='2'>&nbsp;</td><td align='center'>NUEVA VIGENCIA:</td>";
		?>
        <td>desde <input type="text" name="NewFechaIni" size="8" 
        onclick="popUpCalendar(this, FORMA.NewFechaIni, 'yyyy-mm-dd')"  value="<? echo $NewFechaIni; ?>" readonly="yes" /></td>
        <td>hasta <input type="text" name="NewFechaFin" size="8" 
        onclick="popUpCalendar(this, FORMA.NewFechaFin, 'yyyy-mm-dd')"  value="<? echo $NewFechaFin; ?>" readonly="yes" /></td></tr>
		<?
		echo "<tr align='center' bgcolor='#e5e5e5' style='font-weight:bold'><td>Codigo</td><td width='350px'>C U P</td><td>Vigecia Actual</td><td>Valor</td>
		<td>Nuevo Valor</td></tr>";
		while($fila=ExFetch($res))
		{
			$cons1="Select FechaIni,FechaFin,Valor 
			from Facturacion.TarifasxCUPS
			where Codigo='$fila[0]' and Tarifario='$Tarifario' order by FechaIni Desc";
			$res1=ExQuery($cons1);echo ExError();
			$fila1=ExFetch($res1);
			echo "<tr><td>$fila[0]</td><td>$fila[1]</td><td>$fila1[0]  <strong>hasta</strong>  $fila1[1]</td><td align='right'>$fila1[2]</td>";
?>
			<td align="center">$<input type="text" name="NewTarifa" size="6" maxlength="10" style="text-align:right" /></td>
<?
			echo "</tr>";
		
		}
		echo "</table>";
?>
		<input type="submit" name="Guardar" value="Guardar" />
		<input type="button" name="IncPorc" value="Ajuste Porcentual" onClick="Validar(1)" />		

<?		
	}
?>
</form>
</body>