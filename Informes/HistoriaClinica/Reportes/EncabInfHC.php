<?
	if($DatNameSID){session_name("$DatNameSID");}
	session_start();
	include("Funciones.php");
	$ND=getdate();
	$PerIni="$ND[year]-$ND[mon]-01";
	$PerFin="$ND[year]-$ND[mon]-$ND[mday]";
?>
<body background="/Imgs/Fondo.jpg">
<form name="FORMA" target="Abajo" action="RptInfHC.php">
<?	echo "<table border=1 bordercolor='#e5e5e5' style='font : normal normal small-caps 12px Tahoma;'>"; ?>
<tr bgcolor="#e5e5e5"><td>Cargo</td><td>Sexo</td><td>CUP</td><td>Dx</td><td>Entidad</td><td align="center">Periodo</td></tr>
<tr>
<td><select name="Cargo" style="width:120px;"><option>
<?
	$cons="SELECT Cargos FROM salud.Cargos where Asistencial=1 and Compania='$Compania[0]' Group By Cargos";
	$res=ExQuery($cons);
	while($fila=ExFetch($res))
	{
		echo "<option value='$fila[0]'>$fila[0]</option>";
	}
	
?>
</select></td>
<td>
<select name="Sexo">
<option></option>
<option value="F">F</option>
<option value="M">M</option>
</select>
</td>
<td><input type="text" name="CUPSel" style="width:100px;" onClick="open('BuscarCUP.php?ControlOrigen=CUPSel','','width=600,height=400')"></td>
<td><input type="text" name="DX" style="width:100px;" onClick="open('BuscarDx.php?ControlOrigen=DX','','width=600,height=400')"></td>

<td><select name="Entidad" style="width:200px;"><option>
<?
	$cons="SELECT PrimApe,Entidad FROM salud.Agenda,Central.Terceros 
	where Agenda.Entidad=Terceros.Identificacion and Agenda.Compania=Terceros.Compania and Terceros.Compania='$Compania[0]'
	and Fecha>='$PerIni' and Fecha<='$PerFin' Group By PrimApe,Entidad";
	$res=ExQuery($cons);
	while($fila=ExFetch($res))
	{
		echo "<option value='$fila[1]'>$fila[0]</option>";
	}
	
?>
</select></td>


<td>
<input type="text" name="PerIni" value="<? echo $PerIni?>" style="width:70px;">
<input type="text" name="PerFin" value="<? echo $PerFin?>" style="width:70px;">
<input type="hidden" name="DatNameSID" value="<? echo $DatNameSID?>">
</td>
<td><input type="submit" name="Ver" value="Ver">
</tr>
</table>
</form>
</body>