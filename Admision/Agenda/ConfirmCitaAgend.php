<?
	if($DatNameSID){session_name("$DatNameSID");}
	session_start();
	include("Funciones.php");	
	$F=explode("-",$Fecha);	
	$cons="select nombre from central.usuarios where usuario='$Profecional'";
	$res=ExQuery($cons);
	$fila=ExFetch($res);	
	$d=date('w',mktime(0,0,0,$F[1],$F[2],$F[0]));	
	switch($d){
		case 1: $Diasem='Lun'; break;
		case 2: $Diasem='Mar'; break;
		case 3: $Diasem='Mie'; break;
		case 4: $Diasem='Juv'; break;
		case 5: $Diasem='Vie'; break;
		case 6: $Diasem='Sab'; break;
		case 0: $Diasem='Dom'; break;
	}
	if($Confirmar){
		$ND=getdate();
		
		$cons3="update salud.agenda set nomconfrim='$NomConfirm',observacionconfirm='$Observaciones'
		,usuconfirma='$usuario[1]',fechaconfirm='$ND[year]-$ND[mon]-$ND[mday] $ND[hours]:$ND[minutes]:$ND[seconds]'
		where compania='$Compania[0]' and hrsini='$HrIni' and minsini='$MinIni' and fecha='$Fecha' and medico='$Profecional' and cedula='$Cedula' and id=$Id";
		$res3=ExQuery($cons3);
		//echo $cons3;
		
		 ?>  <script language="javascript">
	     		location.href='NewEstadoAgend.php?DatNameSID=<? echo $DatNameSID?>&Id=<? echo $Id?>&Especialidad=<? echo $Especialidad?>&Profecional=<? echo $Profecional?>&Fecha=<? echo $Fecha?>&HrIni=<? echo $HrIni?>&MinIni=<? echo $MinIni?>';
        	</script> <?		
	}
?>	
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<script language='javascript' src="/Funciones.js"></script>
<script language="javascript">
function validar()
{
	if(document.FORMA.NomConfirm.value==""){
		alert("Debe digitar el nombre de la person con quien se confirma la cita!!!");return false;
	}
}

</script>
</head>

<body background="/Imgs/Fondo.jpg">
<form name="FORMA" method="post" onSubmit="return validar()">  
<input type="hidden" name="DatNameSID" value="<? echo $DatNameSID?>"/>
<table style='font : normal normal small-caps 12px Tahoma;' border="1" bordercolor="#e5e5e5" cellpadding="4" align="center"> 
<tr bgcolor="#e5e5e5" style="font-weight:bold" align="center">
	<td align="center" colspan="8">Confirmar Cita</td>
</tr>
<tr>
   	<td bgcolor="#e5e5e5" style="font-weight:bold" align="center" colspan="8"><? echo "$fila[0]-$Especialidad";?></td>            
</tr>
<tr><td bgcolor="#e5e5e5" style="font-weight:bold" align="center" colspan="8"> <? echo "$Fecha - $Diasem";?></td></tr>
<?	
$cons2="Select hrsini,minsini,hrsfin,minsfin,cedula,primape,segape,primnom,segnom,telefono,entidad,estado,tiempocons from central.terceros,salud.agenda where
terceros.identificacion=agenda.cedula and id=$Id and  medico='$Profecional' and hrsini='$HrIni' and minsini='$MinIni'and fecha='$Fecha' and estado='Pendiente' and agenda.compania='$Compania[0]' and terceros.compania='$Compania[0]'";
	$res2=ExQuery($cons2);echo ExError();
if(ExNumRows($res2)>0)
{?> 					
	</tr>
 <? $fila2 = ExFetchArray($res2);
	 if($fila2[3]==0){$cero1='0';}else{$cero1='';}
	if($fila2[1]==0){$cero='0';}else{$cero='';} ?>
	<tr><td bgcolor="#e5e5e5" style="font-weight:bold" align="center">Hora Cita</td><td><? echo "$fila2[0]:$fila2[1]$cero-$fila2[2]:$fila2[3]$cero1";?></td></tr>        
    <tr><td bgcolor="#e5e5e5" style="font-weight:bold" align="center">Nombre</td><td><? echo "$fila2[5] $fila2[6] $fila2[7] $fila2[8]";?></td></tr>
   	<tr><td bgcolor="#e5e5e5" style="font-weight:bold" align="center">Cedula</td><td><? echo $fila2[4]?></td></tr>
    <tr><td bgcolor="#e5e5e5" style="font-weight:bold" align="center">Telefono</td><td><? echo $fila2[9]?></td></tr>
    <tr><td bgcolor="#e5e5e5" style="font-weight:bold" align="center">Qui&eacute;n atiende la llamada</td>
    	<td><input type="text" name="NomConfirm" onKeyDown="xLetra(this)" onKeyPress="xLetra(this)" onKeyUp="xLetra(this)" size="40"
        value="<? echo $NomConfirm?>"/></td>
    </tr>
    <tr><td bgcolor="#e5e5e5" style="font-weight:bold" align="center">Observaciones</td>
    	<td>
        	<textarea cols="32" rows="3" name="Observaciones" onKeyDown="xLetra(this)" onKeyPress="xLetra(this)" onKeyUp="xLetra(this)">
			<? echo $Observaciones?></textarea>
        </td>
    </tr><?
}?>    
	  <tr>
     	<td align="center" colspan="8"><input type="submit" value="Confirmar Cita" name="Confirmar">
        	<input type="button" value="Regresar"  onClick="location.href='NewEstadoAgend.php?DatNameSID=<? echo $DatNameSID?>&Id=<? echo $Id?>&Especialidad=<? echo $Especialidad?>&Profecional=<? echo $Profecional?>&Fecha=<? echo $Fecha?>&HrIni=<? echo $HrIni?>&MinIni=<? echo $MinIni?>'">
	</td>
   	</tr>
</table>
<input type="hidden" name="Especialidad" value="<? echo $Especialidad?>">
<input type="hidden" name="Profecional" value="<? echo $Profecional?>">
<input type="hidden" name="Fecha" value="<? echo $Fecha?>">
<input type="hidden" name="HrIni" value="<? echo $HrIni?>">
<input type="hidden" name="MinIni" value="<? echo $MinIni?>">
<input type="hidden" name="Cedula" value="<? echo $fila2[4]?>">
<input type="hidden" name="Id" value="<? echo $Id?>">
<input type="hidden" name="FechaIni" value="<? echo $FechaIni?>">
<input type="hidden" name="FechaFin" value="<? echo $FechaFin?>">
<input type="hidden" name="Exp" value="<? echo $Exp?>">
</form>
</body>
</html>