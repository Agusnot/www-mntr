<head>
<meta http-equiv="refresh" content="25">
</head>

<?
	$ND=getdate();
	if($ND[mon]<10){$Mes="0".$ND[mon];}else{$Mes=$ND[mon];}
	if($ND[mday]<10){$Dia="0".$ND[mday];}else{$Dia=$ND[mday];}
	if($ND[hours]<10){$Hora="0".$ND[hours];}else{$Hora=$ND[hours];}
	if($ND[minutes]<10){$Minutos="0".$ND[minutes];}else{$Minutos=$ND[minutes];}
?>
<center>
<table border="1" cellpadding="8" bordercolor="white">
<tr bgcolor="#000066" align="center">
<td><font face="Trebuchet MS, Arial, Helvetica, sans-serif" style=" font-size:35px;color:yellow">Fecha</td>
<td><font face="Trebuchet MS, Arial, Helvetica, sans-serif" style=" font-size:35px;color:yellow">Hora</td></tr>
<tr><td><font face="Trebuchet MS, Arial, Helvetica, sans-serif" style=" font-size:28px;color:white"><? echo "$ND[year]-$Mes-$Dia"?></td>
<td><font face="Trebuchet MS, Arial, Helvetica, sans-serif" style=" font-size:28px;color:white"><? echo "$Hora:$Minutos"?></td></tr>
</table>