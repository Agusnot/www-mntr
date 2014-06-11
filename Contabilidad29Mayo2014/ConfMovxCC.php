<?
	if($DatNameSID){session_name("$DatNameSID");}
	session_start();
	include("Funciones.php");
	if(!$Cuenta && $Cuenta!='0'){exit;}
?>
<style>body{color:<?echo $Estilo[7]?>;font-family:<?echo $Estilo[8]?>;font-size:<?echo $Estilo[9]?>;font-style:<?echo $Estilo[10]?>}</style>

<head><meta http-equiv="Content-Type" content="text/html; charset=utf-8"></meta></head>
<body background="/Imgs/Fondo.jpg">
<style>
.Tit1{color:white;background:<?echo $Estilo[1]?>;font-weight:bold;}
</style>

<table border="1" bordercolor="#ffffff" width="100%" style="font-family:<?echo $Estilo[8]?>;font-size:<?echo $Estilo[9]?>;font-style:<?echo $Estilo[10]?>">

<tr class="Tit1"><td>Mes</td><td>Debitos</td><td>Creditos</td></tr>
<?
	for($i=1;$i<=12;$i++)
	{
		if($Fondo==1){$BG="#EEF6F6";$Fondo=0;}
		else{$BG="white";$Fondo=1;}

		$consMes="Select Mes from Central.Meses where Numero=$i";
		$resMes=ExQuery($consMes,$conex);echo ExError($resMes);
		$filaMes=ExFetch($resMes);
		echo "<tr bgcolor='$BG'><td>".strtoupper($filaMes[0])."</td>";

		$cons="Select sum(Debe),sum(Haber) from Contabilidad.Movimiento where CC = '$Cuenta' and date_part('month',Fecha)=$i and date_part('year',Fecha)=$Anio and Compania='$Compania[0]' and Estado='AC'";
		$res=ExQuery($cons);
		$fila=ExFetch($res);echo ExError($res);
		$Debitos=$fila[0];$Creditos=$fila[1];
		if(!$Debitos){$Debitos=0;}if(!$Creditos){$Creditos=0;}
		echo "<td align='right'>".number_format($Debitos,2)."</td><td align='right'>".number_format($Creditos,2)."</td>";
	}
?>
</table>
<br />
</body>
