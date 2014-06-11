<?
	session_start();
	include("Funciones.php");
	if($Guardar)
	{
		if($Perfil=="Psiquiatra"){$Tte="MedicoTratante";}else{ 
		if($Perfil=="Psicologo"){$Tte="PsicologoTte";}else{
		if($Perfil=="Medico General"){$Tte="MedGralTte";}else{
		if($Perfil=="Terapia Ocupacional"){$Tte="TerapeutaTte";}else{
		if($Perfil=="Nutricionista"){$Tte="NutTratante";}}}}}
			
		$cons="Insert into HistoriaClinica.AgendaInterna(Formato,Perfil,TipoFormato,Regimen,Periodicidad,CampoRelac) values ('$NewFormato','$Perfil','$TF','$Regimen','$Periodicidad','$Tte')";
		$res=ExQuery($cons,$conex);
		echo ExError($conex);
	}
	
	if($Eliminar)
	{
		$cons="Delete from HistoriaClinica.AgendaInterna where Formato='$NewFormato' and Perfil='$Perfil' and TipoFormato='$TF' and Periodicidad='$Periodicidad' and Regimen='$Regimen'";
		$res=ExQuery($cons,$conex);
	}
?>

<head>
<title><? echo "$Sistema[$NoSistema]"?></title>
</head>
<body background="/Imgs/Fondo.jpg">
<table border="1" background="borde.png">
<tr class="style1">
  <td><div align="center"><strong>Regimen</strong></div></td><td><div align="center"><strong>Periodicidad</strong></div></td></tr>
<?
	$cons="Select * from  HistoriaClinica.AgendaInterna where Formato='$NewFormato' and TipoFormato='$TF' and Perfil='$Perfil'";
	$res=ExQuery($cons,$conex);
	while($fila=mysql_fetch_array($res))
	{?>
		<tr><td><? echo $fila['Regimen']?></td><td><? echo $fila['Periodicidad']?></td></a>
    <?	echo "<td><a href='AgendaInterna.php?Eliminar=1&NewFormato=$NewFormato&Perfil=".$fila['Perfil'] ."&TF=$TF&Periodicidad=".$fila['Periodicidad']."&Regimen=".$fila['Regimen']."'><img src='/Imgs/b_drop.png' border=0></a></td></tr>";
		$consAdc=$consAdc . "'" . $fila['Regimen'] . "'" . " and Regimen !=";
	}

	if(!$consAdc){$consAdc=1;}
	else{$consAdc=" Regimen !=" . $consAdc;
	$consAdc=substr($consAdc,1,strlen($consAdc)-16);
	}


?>
<tr class="style1">
<td>
<form name="FORMA">
<select name="Regimen">
<?
	
	$cons="Select * from Salud.Regimenes where $consAdc Order By Regimen";
	$res=ExQuery($cons,$conex);echo ExError($conex);
	while($fila=ExFetch($res))
	{
		echo "<option value='$fila[1]'>$fila[1]</option>";
	}
?>
</select>

<input type="Hidden" name="NewFormato" value="<?echo $NewFormato?>">
<input type="Hidden" name="Perfil" value="<?echo $Perfil?>">
<input type="Hidden" name="TF" value="<?echo $TF?>">
<td><input type="Text" name="Periodicidad" size="3"></td>
<td><input type="Submit" name="Guardar" value="G"></td>

</form>
</table>
</body>