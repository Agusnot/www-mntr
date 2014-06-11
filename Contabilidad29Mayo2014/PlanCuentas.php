<?
	if($DatNameSID){session_name("$DatNameSID");}
	session_start();
	include("Funciones.php");
	$ND=getdate();
	$AnioAc=$ND[year];
?>	
<head>
<title></title>
</head>


<frameset cols="350,*" frameborder="no" border="0" framespacing="0">
	<frame src="SelCuenta2.php?DatNameSID=<? echo $DatNameSID?>&AnioAc=<?=$AnioAc?>&usuario[0]=<?=$usuario[0]?>&usuario[1]=<?=$usuario[1]?>&Compania[0]=<?=$Compania[0]?>" name="Cuenta"/>
	<frameset rows="290,*" frameborder="no" border="0" framespacing="0">
		<frame src="DetalleCuenta.php?DatNameSID=<? echo $DatNameSID?>&AnioAc=<?=$AnioAc?>&usuario[0]=<?=$usuario[0]?>&usuario[1]=<?=$usuario[1]?>&Compania[0]=<?=$Compania[0]?>" name="Derecha" scrolling="no">
		<frame src="" name="Abajo">
</frameset>
</frameset><noframes></noframes>

</html>