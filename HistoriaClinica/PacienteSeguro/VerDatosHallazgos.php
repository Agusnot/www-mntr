<?php
	if($DatNameSID){session_name("$DatNameSID");}
	session_start();
	include("Funciones.php");	
	$cons="select (primape || ' ' || segape || ' ' || primnom || ' ' || segnom) as nomb, cedula,accinseg1,accinseg2,accinseg3,accinseg4,accinseg5,accinseg6,accinseg7
	,faccontrib1,faccontrib2,faccontrib3,faccontrib4,faccontrib5,faccontrib6,faccontrib7,clasificfinal
	from historiaclinica.regpacienteseg,central.terceros where regpacienteseg.compania='$Compania[0]' and cedula=identificacion
	and numrep=$NumRep and terceros.compania='$Compania[0]'";
	$res=ExQuery($cons);
	$fila=ExFetch($res);
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<script language="javascript">
	function CerrarThis()
	{
		parent.document.getElementById('FrameOpener').style.position='absolute';
		parent.document.getElementById('FrameOpener').style.top='1px';
		parent.document.getElementById('FrameOpener').style.left='1px';
		parent.document.getElementById('FrameOpener').style.width='1';
		parent.document.getElementById('FrameOpener').style.height='1';
		parent.document.getElementById('FrameOpener').style.display='none';
		parent.document.FORMA.submit();
	}
</script>	
</head>

<body background="/Imgs/Fondo.jpg">
<form name="FORMA" method="post" onSubmit="return Validar()">
<input type="button" value=" X " onClick="CerrarThis()" style="position:absolute;top:1px;right:1px;" title="Cerrar esta ventana">
<table cellpadding="4"  border="1" bordercolor="#e5e5e5" style="font-family:<?echo $Estilo[8]?>;font-size:12px;font-style:<?echo $Estilo[10]?>" width="100%">
	<tr>
    	<td bgcolor="#e5e5e5" style="font-weight:bold" align="center" colspan="4">REPORTE DE HALLAZGOS</td>
  	</tr>
    <tr>
    	<table cellpadding="4"  border="1" bordercolor="#e5e5e5" style="font-family:<?echo $Estilo[8]?>;font-size:12px;font-style:<?echo $Estilo[10]?>" width="100%">
        	<tr>
	    		<td bgcolor="#e5e5e5" style="font-weight:bold" align="center">Paciente</td><td><? echo $fila[0]?></td>
    			<td bgcolor="#e5e5e5" style="font-weight:bold" align="center">Identificaicon</td><td><? echo $fila[1]?></td>
         	</tr>
      	<table cellpadding="4"  border="1" bordercolor="#e5e5e5" style="font-family:<?echo $Estilo[8]?>;font-size:12px;font-style:<?echo $Estilo[10]?>" width="100%">
    </tr>
    <tr bgcolor="#e5e5e5" style="font-weight:bold" align="center">
    	<td colspan="2">Acciones Inseguras</td><td colspan="2">Factores Contributivos</td>
    </tr>
    <tr><td colspan="2"><strong>1.</strong><? echo $fila[2]?></td><td colspan="2"><strong>1.</strong><? echo $fila[9]?></td></tr>
    <tr><td colspan="2"><strong>2.</strong><? echo $fila[3]?></td><td colspan="2"><strong>2.</strong><? echo $fila[10]?></td></tr>
    <tr><td colspan="2"><strong>3.</strong><? echo $fila[4]?></td><td colspan="2"><strong>3.</strong><? echo $fila[11]?></td></tr>
    <tr><td colspan="2"><strong>4.</strong><? echo $fila[5]?></td><td colspan="2"><strong>4.</strong><? echo $fila[12]?></td></tr>
    <tr><td colspan="2"><strong>5.</strong><? echo $fila[6]?></td><td colspan="2"><strong>5.</strong><? echo $fila[13]?></td></tr>
    <tr><td colspan="2"><strong>6.</strong><? echo $fila[7]?></td><td colspan="2"><strong>6.</strong><? echo $fila[14]?></td></tr>
    <tr><td colspan="2"><strong>7.</strong><? echo $fila[8]?></td><td colspan="2"><strong>7.</strong><? echo $fila[15]?></td></tr>
    <tr><td colspan="4"><strong>Clasificacion Final: </strong><? echo $fila[16]?></td></tr>
</table>
</form>
</body>
</html>
