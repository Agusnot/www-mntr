<?
	if($DatNameSID){session_name("$DatNameSID");}
	session_start();
	include("Funciones.php");	
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
</head>

<body background="/Imgs/Fondo.jpg">
<? 
$Ambito=utf8_decode_seguro($Ambito);
$cons="select nocamas,sobrecupo from salud.pabellones where pabellon='$UnidadHosp' and ambito='$Ambito' and compania='$Compania[0]'";
$res=ExQuery($cons);echo ExError();
$fila=ExFetch($res);
$TotalCamas=$fila[0];
$SobreCupo=$fila[1];
if($TotalCamas==''){$TotalCamas='0';}
if($SobreCupo=='') {$SobreCupo='0';}

$cons="select cedula from salud.pacientesxpabellones where pabellon='$UnidadHosp' and ambito='$Ambito' and estado='AC' and compania='$Compania[0]'";
$res=ExQuery($cons);echo ExError();
//echo $cons;
$CamasOcup=ExNumRows($res);
$CamasDispo=$TotalCamas-$CamasOcup;

$CamasPerm=$CamasDispo+$SobreCupo;
?>
<script language="javascript">
	//alert('<? echo $CamasPerm?>');
	parent.document.FORMA.CamasDispo.value="<? echo $CamasPerm?>";
</script>
<table BORDER=1  style='font : normal normal small-caps 12px Tahoma;' border="1" bordercolor="#e5e5e5" cellpadding="4" align="center"> 
<tr bgcolor="#e5e5e5" style=" font-weight:bold" align="center">
	<td>Total Camas</td><td>Camas Ocupadas</td><td>Camas Disponibles</td>
</tr>
<tr align="center">
	<td><? echo $TotalCamas?></td><td><? echo $CamasOcup?></td><td><? echo $CamasDispo?></td>
</tr>
</table>
</body>
</html>
