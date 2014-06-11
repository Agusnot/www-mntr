<?php
	if($DatNameSID){session_name("$DatNameSID");}
	session_start();
	include("Funciones.php");	
	$cons="select (primape || ' ' || segape || ' ' || primnom || ' ' || segnom) as nomb, cedula,accioncorect1,accioncorect2,accioncorect3,accioncorect4,accioncorect5,accioncorect6
	,accioncorect7,responscorre1,responscorre2,responscorre3,responscorre4,responscorre5,responscorre6,responscorre7,fechacorrect1,fechacorrect2,fechacorrect3,fechacorrect4
	,fechacorrect5,fechacorrect6,fechacorrect7
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
    		<td bgcolor="#e5e5e5" style="font-weight:bold" align="center" colspan="4">ACCIONES CORRECTIVAS PROPUESTAS</td>
	  	</tr>    
    </table>	   
	<table cellpadding="4"  border="1" bordercolor="#e5e5e5" style="font-family:<?echo $Estilo[8]?>;font-size:12px;font-style:<?echo $Estilo[10]?>" width="100%">
       	<tr>
	    	<td bgcolor="#e5e5e5" style="font-weight:bold" align="center">Paciente</td><td><? echo $fila[0]?></td>
    		<td bgcolor="#e5e5e5" style="font-weight:bold" align="center">Identificaicon</td><td><? echo $fila[1]?></td>
      	</tr>
  	</table>
    <table cellpadding="4"  border="1" bordercolor="#e5e5e5" style="font-family:<?echo $Estilo[8]?>;font-size:12px;font-style:<?echo $Estilo[10]?>" width="100%">    
	    <tr bgcolor="#e5e5e5" style="font-weight:bold" align="center">
    		<td width="3%"></td><td width="50%">Acciones</td><td width="30%">Responsable</td><td width="17%">Fecha</td>
    	</tr>
        <tr>
    		<td width="3%"><strong>1.</strong></td><td width="50%"><? echo $fila[2]?></td><td width="30%"><? echo $fila[9]?></td><td width="17%" align="center"><? echo $fila[16]?></td>
    	</tr>
        <tr>
    		<td width="3%"><strong>2.</strong></td><td width="50%"><? echo $fila[3]?>&nbsp;</td><td width="30%"><? echo $fila[10]?>&nbsp;</td>
            <td width="17%" align="center"><? echo $fila[17]?>&nbsp;</td>
    	</tr>
        <tr>
    		<td width="3%"><strong>3.</strong></td><td width="50%"><? echo $fila[4]?>&nbsp;</td><td width="30%"><? echo $fila[11]?>&nbsp;</td>
            <td width="17%" align="center"><? echo $fila[18]?>&nbsp;</td>
    	</tr>
        <tr>
    		<td width="3%"><strong>4.</strong></td><td width="50%"><? echo $fila[5]?>&nbsp;</td><td width="30%"><? echo $fila[12]?>&nbsp;</td>
            <td width="17%" align="center"><? echo $fila[19]?>&nbsp;</td>
    	</tr>
        <tr>
    		<td width="3%"><strong>5.</strong></td><td width="50%"><? echo $fila[6]?>&nbsp;</td><td width="30%"><? echo $fila[13]?>&nbsp;</td>
            <td width="17%" align="center"><? echo $fila[20]?>&nbsp;</td>
    	</tr>
        <tr>
    		<td width="3%"><strong>6.</strong></td><td width="50%"><? echo $fila[7]?>&nbsp;</td><td width="30%"><? echo $fila[14]?>&nbsp;</td>
            <td width="17%" align="center"><? echo $fila[21]?>&nbsp;</td>
    	</tr>
        <tr>
    		<td width="3%"><strong>7.</strong></td><td width="50%"><? echo $fila[8]?>&nbsp;</td><td width="30%"><? echo $fila[15]?>&nbsp;</td>
            <td width="17%" align="center"><? echo $fila[22]?>&nbsp;</td>
    	</tr>
  	</table>
</table>
</form>    
</body>
</html>
