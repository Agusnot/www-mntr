<?
	if($DatNameSID){session_name("$DatNameSID");}
	session_start();
	include("Funciones.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Documento sin t&iacute;tulo</title>
</head>
<table style='font : normal normal small-caps 12px Tahoma;' border="1" bordercolor="#e5e5e5" width="100%">
<tr><td  colspan="5" align="center" style="font-weight:bold" bgcolor="#e5e5e5">Lista de Facturas del Contrato</td></tr>
<tr><td>No Factura</td><td>Fecha</td><td>Usuario</td><td>Vr Factura</td><td>Pagos</td></tr>
<tr><td><a href="#" onclick="open('ImpFactura.php?DatNameSID=<? echo $DatNameSID?>','','width=800,height=800,scrollbars=yes')">2009000001</td><td>2009-08-03</td><td>Jaime Casanova Montalvo</td><td>$ 1,418,643.00</td><td>$ 0.00</td></tr>
<body>
</body>
</html>
