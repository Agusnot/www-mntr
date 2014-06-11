<?
	if($DatNameSID){session_name("$DatNameSID");}
	session_start();
	include("Funciones.php");
	$ND=getdate();
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
</head>

<body background="/Imgs/Fondo.jpg">
<form name="FORMA" method="post" >
<table BORDER=1  style='font : normal normal small-caps 12px Tahoma;' border="1" bordercolor="#e5e5e5" cellpadding="4" align="center"> 
	<tr>
    	<td colspan="11"><center><strong><? echo strtoupper($Compania[0])?><br><? echo $Compania[1]?><br></td>
  	</tr>
    <tr><td bgcolor="#e5e5e5" style="font-weight:bold" align="center" colspan="11">BOLETA DE EGRESO</td></tr>
    <? 	$cons="select tiposervicio from salud.servicios where numservicio='$NumServ'";
		$res=ExQuery($cons);
		$fila=ExFetch($res);?>		
    <tr>
    	<td colspan="11"><strong>Proceso: </strong><? echo $fila[0]?></td>
    </tr>
    <tr>
	<?php 
		$cons2="select pabellon from salud.pacientesxpabellones where numservicio='$NumServ' order by fechai desc limit 1";
		$res2=ExQuery($cons2);
		$fila2=ExFetch($res2);
	?>
    	<td colspan="11"><strong>Servicio: </strong><? echo $fila2[0]?></td>
    </tr>	
    	<td colspan="11"> <strong>Numero De Servicio:  </strong><? echo $NumServ?></td>        
    </tr>
    <tr>
    	<td colspan="11">	<strong>Fecha: </strong> <? echo "$ND[year]-$ND[mon]-$ND[mday]"?> &nbsp;&nbsp;&nbsp;&nbsp;
        					<strong> Hora: </strong><? echo "$ND[hours]:$ND[minutes]:$ND[seconds]"?></td>        
    </tr>
    <tr>
    <? 	$cons="select primnom,segnom,primape,segape,identificacion from central.terceros where identificacion='$Ced'";
		$res=ExQuery($cons);
		$fila=ExFetch($res);?>
    	<td style="font-weight:bold">Nombre Paciente: </td><td><? echo "$fila[0] $fila[1] $fila[2] $fila[3]"?></td><td style="font-weight:bold">Cedula: </td><td><? echo $Ced?></td>
    </tr>
    <? 	$cons="select detalle from salud.ordenesmedicas where numservicio=$NumServ and tipoorden='Orden Egreso'";
		$res=ExQuery($cons);
		$fila=ExFetch($res);
		$fila[0]=str_replace("Egreso paciente por:","",$fila[0]);?>
   	<tr><td><strong>Motivo Egreso: </strong></td><td colspan="10"><? echo strtoupper($fila[0])?></td></tr>
    <tr><td colspan="11">&nbsp;</td></tr>
    <tr>
    <? 	$cons="select usuordensalida from salud.servicios where numservicio='$NumServ'";
		$res=ExQuery($cons);
		$fila=ExFetch($res);
		$cons="select nombre from central.usuarios,salud.servicios where usuario=usuordensalida and servicios.compania='$Compania[0]' and numservicio=$NumServ";
		$res=ExQuery($cons);
		$fila=ExFetch($res);?>
    	<td colspan="11"><strong>Medico que Ordena Egreso: </strong><? echo $fila[0]?></td>
    </tr>
    
</table>
<input type="Hidden" name="DatNameSID" value="<? echo $DatNameSID?>">
</form>
</body>
</html>
