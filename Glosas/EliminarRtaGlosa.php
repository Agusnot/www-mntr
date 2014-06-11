<?
	session_start();
	include("Funciones.php");
	$ND=getdate();	
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
</head>

<body>
<form name="FORMA" method="post">
<? 	$cons="delete from facturacion.tmprtaglosa where compania='$Compania[0]' and tmpcod='$TMPC' and nofactura=$NoFac";
	$res=ExQuery($cons);?>
</form>
</body>
</html>
