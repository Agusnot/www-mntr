<?
	if($DatNameSID){session_name("$DatNameSID");}
	session_start();
	include("Funciones.php");
	$ND=getdate();
	$AnioAc=$ND[year];
?>
<head>
<title>Compuconta Software</title>
</head>
<frameset cols="350,*" frameborder="no" border="0" framespacing="0">
	<frame src="SelCuenta2.php?DatNameSID=<? echo $DatNameSID?>" name="Cuenta"/>
	<frameset rows="280,*" frameborder="no" border="0" framespacing="0">
		<frame src="DetalleCuenta.php?DatNameSID=<? echo $DatNameSID?>" name="Derecha" scrolling="no">
		<frame src="" name="Abajo">
</frameset>
</frameset>
</html>