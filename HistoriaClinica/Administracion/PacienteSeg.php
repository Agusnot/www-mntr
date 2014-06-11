<?
	if($DatNameSID){session_name("$DatNameSID");}
	session_start();
	include("Funciones.php");
	if($Guardar)
	{
		if($Cargo=="")
		{
			echo "<script languaje='javascript'>alert('Seleccione algun cargo!');</script>"	;
		}
		else
		{
			$Fecha=date("Y-m-d");
			$cons="Insert into  HistoriaClinica.pacienteseg (UsuarioCre,FechaCre,TipoFormato,Formato,Cargo,Compania) values ('$usuario[0]','$Fecha','$TF','$NewFormato','$Cargo','$Compania[0]')";
			$res=ExQuery($cons,$conex);
			echo ExError($conex);
		}
	}
	if($Eliminar)
	{
		$cons="Delete from HistoriaClinica.pacienteseg where Formato='$NewFormato' and TipoFormato='$TF' and Cargo='$Cargo' and Compania='$Compania[0]'";
		$res=ExQuery($cons,$conex);
	}
?>

<body background="/Imgs/Fondo.jpg">
<table border="1" bordercolor="#e5e5e5" cellpadding="5" style="font-family:Tahoma; font-size:11px;">
<tr style="color:white; font-weight:bold" align="center" bgcolor="<? echo $Estilo[1]?>"><td colspan="2">Cargo</td></tr>
<?
	$cons="Select * from HistoriaClinica.pacienteseg where Formato='$NewFormato' and TipoFormato='$TF' and Compania='$Compania[0]'";
	$res=ExQuery($cons,$conex);
	while($fila=ExFetchArray($res))
	{
		echo "<tr bgcolor='white'><td>" . $fila['cargo'] . "</td>";
		echo "<td><a href='PacienteSeg.php?DatNameSID=$DatNameSID&Eliminar=1&NewFormato=$NewFormato&TF=$TF&Cargo=" . $fila['cargo'] . "'><img src='/Imgs/b_drop.png' border=0></a></td></tr>";
		$consAdc=$consAdc . "'" . $fila['cargo'] . "'" . " and Cargos !=";
	}
	if(!$consAdc){$consAdc="1=1";}
	else{$consAdc=" Cargos !=" . $consAdc;
		$consAdc=substr($consAdc,1,strlen($consAdc)-14);
		$CondAdc2=" and Asistencial=1";
	}
?>
<tr>
<td>
<form name="FORMA">
<select name="Cargo">
<?

	$cons="Select * from Salud.Cargos where $consAdc $CondAdc2 and Compania='$Compania[0]' Order By Cargos";
	$res=ExQuery($cons,$conex);echo ExError($conex);
	while($fila=ExFetch($res))
	{
		echo "<option value='$fila[0]'>$fila[0]</option>";
	}
?>
</select>

<input type="Hidden" name="NewFormato" value="<?echo $NewFormato?>">
<input type="hidden" name="DatNameSID" value="<? echo $DatNameSID?>">
<input type="Hidden" name="TF" value="<? echo $TF?>">
<?	if(ExNumRows($res)>0){?>
<td><input type="Submit" name="Guardar" value="G"></td><? }?>

</form>
</table>
<input type="button" value="Volver" onClick="location.href='/HistoriaClinica/Administracion/ItemsxFormato.php?DatNameSID=<? echo $DatNameSID?>&NewFormato=<? echo $NewFormato?>&TF=<? echo $TF?>'">

</body>