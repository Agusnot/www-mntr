<?
	if($DatNameSID){session_name("$DatNameSID");}
	session_start();
	include("Funciones.php");
	$ND=getdate();
	$Anio=$ND[year];
?>
<title>Compuconta Software</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8"></meta></head>
<body background="/Imgs/Fondo.jpg">
<form name="FORMA" target="Listado" action="DetPartidasIniciales.php">
<table border="1" rules="groups" bordercolor="#e5e5e5" cellspacing="4" cellpadding="6" style="font-family:<?echo $Estilo[8]?>;font-size:12;font-style:<?echo $Estilo[10]?>">
<tr bgcolor="#e5e5e5" style="font-weight:bold;text-align:center"><td>Banco</td><td>Comprobante</td></tr>
<tr align="center"><td>
<select name="Banco" onChange="FORMA.submit()">
<option>
<?
	$cons="Select Nombre,Cuenta from Contabilidad.PlanCuentas where Banco=1 and Compania='$Compania[0]' and Anio=$Anio";
	$res=ExQuery($cons);
	while($fila=ExFetch($res))
	{
		echo "<option value='$fila[1]'>$fila[0] $fila[1]</option>";
	}
?>
</select>
</td>
<td>
<select name="Comprobante" onChange="FORMA.submit()">
<option>
<?
	$cons="SELECT Comprobante FROM Contabilidad.Comprobantes where Compania='$Compania[0]'";
	$res=ExQuery($cons,$conex);echo ExError($res);
	while($fila=ExFetch($res))
	{
		echo "<option value='$fila[0]'>$fila[0]</option>";
	}
?>

</select>
</td>
</tr>
<tr>
<td colspan="2">
<iframe name="Listado" id="Listado" frameborder="0" style="width:700px;height:400px;" src="DetPartidasIniciales.php?DatNameSID=<? echo $DatNameSID?>"></iframe>
</td>
</tr>
</table>
<input type="hidden" name="DatNameSID" value="<? echo $DatNameSID?>">
</form></body>

