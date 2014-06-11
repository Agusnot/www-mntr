<?
	if($DatNameSID){session_name("$DatNameSID");}
	session_start();
	include("Funciones.php");
	$ND=getdate();
	if(!$Anio){$Anio=$ND[year];}
?>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
</head>
<body background="/Imgs/Fondo.jpg">
<form name="FORMA" method="post">
<table border="1" bordercolor="#e5e5e5" style="font-family:<?echo $Estilo[8]?>;font-size:12;font-style:<?echo $Estilo[10]?>">
<tr bgcolor="#e5e5e5"><td>
Seleccion Periodo
<select name="Anio" onChange="document.FORMA.submit();">
<option></option>

<?
	$cons="Select Anio from Central.Anios where Compania='$Compania[0]' order by Anio desc";
	$res=ExQuery($cons);
	while($fila=ExFetch($res))
	{
		if($Anio==$fila[0]){echo "<option selected value='$fila[0]'>$fila[0]</option>";}
		else{echo "<option value='$fila[0]'>$fila[0]</option>";}
	}
?>
</select>
</td>
<td rowspan="3" valign="middle">
<iframe name="Movimiento" src="ConfMovxCC.php?DatNameSID=<? echo $DatNameSID?>&Anio=<? echo $Anio?>" frameborder="0" style="width:400px; height:100%"></iframe>

</td>
</tr>
<tr><td>
<iframe name="Detalle" src="ConfEncxCC.php?DatNameSID=<? echo $DatNameSID?>&Anio=<? echo $Anio?>" frameborder="0" width="400px;"></iframe>
</td></tr>
<tr><td>
<iframe name="Lista" src="ConfListxCC.php?DatNameSID=<? echo $DatNameSID?>&Anio=<? echo $Anio?>" frameborder="0" style="width:400px; height:280px"></iframe>
</td></tr>
<input type="hidden" name="DatNameSID" value="<? echo $DatNameSID?>">
</form>
</body>
</html>
