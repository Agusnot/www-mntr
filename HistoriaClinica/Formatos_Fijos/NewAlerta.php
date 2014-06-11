<?php
	if($DatNameSID){session_name("$DatNameSID");}
	session_start();
	include("Funciones.php");
	$ND=getdate();
	if($ND[mon]<10){$cero='0';}else{$cero='';}
	if($ND[mday]<10){$cero1='0';}else{$cero1='';}
	$FechaComp="$ND[year]-$cero$ND[mon]-$cero1$ND[mday]";
	if($Guardar)
	{
		if(!$Edit)
		{
			//$cons="insert into salud.alertasingreso (compania,usuario,fecha,fechaini,fechafin,alerta,cedula) 
			//values ('$Compania[0]','$usuario[1]','$ND[year]-$ND[mon]-$ND[mday] $ND[hours]:$ND[minutes]:$ND[seconds]','$FechaIni','$FechaFin','$Alerta','$Paciente[1]')";
                        $cons="insert into salud.alertasingreso (compania,usuario,fecha,fechaini,alerta,cedula) 
			values ('$Compania[0]','$usuario[1]','$ND[year]-$ND[mon]-$ND[mday] $ND[hours]:$ND[minutes]:$ND[seconds]','$FechaIni','$Alerta','$Paciente[1]')";
			//echo $cons;			
		}
		else
		{
			/*$cons="update salud.alertasingreso 
			set alerta='$Alerta',fechaini='$FechaIni',fechafin='$FechaFin',usuariomod='$usuario[1]',fechamod='$ND[year]-$ND[mon]-$ND[mday] $ND[hours]:$ND[minutes]:$ND[seconds]'
			where alerta='$AlertaAnt' and fechaini='$FechaIniAnt' and fechafin='$FechaFinAnt' and cedula='$Paciente[1]'";*/
			//echo $cons;
                        $cons="update salud.alertasingreso 
			set alerta='$Alerta',fechaini='$FechaIni',usuariomod='$usuario[1]',fechamod='$ND[year]-$ND[mon]-$ND[mday] $ND[hours]:$ND[minutes]:$ND[seconds]'
			where alerta='$AlertaAnt' and fechaini='$FechaIniAnt' and cedula='$Paciente[1]'";
		}
		$res=ExQuery($cons);
		?><script language="javascript">location.href="Alertas.php?DatNameSID=<? echo $DatNameSID?>";</script><?
	}
?>	
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<script language='javascript' src="/Funciones.js"></script>
<script language='javascript' src="/calendario/popcalendar.js"></script>
<script language="javascript">
function Validar(){
	if(document.FORMA.Alerta.value==""){
		alert("El Campo Alerta no puede quedar en blanco!!!");return false;
	}
	else{
		//if(document.FORMA.FechaIni.value==""||document.FORMA.FechaFin.value==""){
                if(document.FORMA.FechaIni.value==""){
			alert("Debe ingresar ambas fechas!!");return false;
		}
		else{
			if(document.FORMA.FechaIni.value<document.FORMA.FechaComp.value){
				alert("La fecha inicial es menor a la fecha actual!!!");return false;
			}
			//else{
			//	if(document.FORMA.FechaIni.value>document.FORMA.FechaFin.value){
			//		alert("La fecha de inicio es mayor a la fehca final!!!");return false;
			//	}				
			//}
		}	
	}
}

</script>
</head>

<body background="/Imgs/Fondo.jpg">
<form name="FORMA" method="post" onSubmit="return Validar()">
<table bordercolor="#e5e5e5" border="1" style='font : normal normal small-caps 12px Tahoma;' align="center" cellpadding="4">
<?
	if(!$NumServ){
		$cons="select numservicio from salud.servicios where compania='$Compania[0]' and cedula='$Paciente[1]' and estado='AC'";
		$res=ExQuery($cons);
		$fila=ExFetch($res);
		$NumServ=$fila[0];		
	}
?>
	<tr>
    	<td  align="center"  bgcolor="#e5e5e5" style=" font-weight:bold">Alerta</td>
        <td colspan="3"><input type="text" name="Alerta" value="<? echo $Alerta?>" onKeyDown="xLetra(this)" onKeyUp="xLetra(this)" style="width:450"></td>
    </tr>
    <tr>
    	<td align="center"  bgcolor="#e5e5e5" style=" font-weight:bold">Fecha Inicio</td>        
        <td><input type="text" readonly name="FechaIni" style="width:175" onClick="popUpCalendar(this, FORMA.FechaIni, 'yyyy-mm-dd')" value="<? echo $FechaIni?>"></td>
        <!-- Fechafin no es necesaria -->
    	<!--<td align="center"  bgcolor="#e5e5e5" style=" font-weight:bold">Fecha Fin</td>                
        <td><input type="text" readonly name="FechaFin" style="width:175" onClick="popUpCalendar(this, FORMA.FechaFin, 'yyyy-mm-dd')" value="<? echo $FechaFin?>"></td>-->
    </tr>
    <tr>
    	<td colspan="4" align="center"><input type="submit" value="Guardar" name="Guardar"><input type="button" value="Cancelar" onClick="location.href='Alertas.php?DatNameSID=<? echo $DatNameSID?>'"</td>
    </tr>
</table>
<input type="hidden" name="FechaComp" value="<? echo $FechaComp?>"> 
<input type="hidden" name="FechaIniAnt" value="<? echo $FechaIni?>">
<input type="hidden" name="FechaFinAnt" value="<? echo $FechaFin?>">
<input type="hidden" name="AlertaAnt" value="<? echo $Alerta?>">
<input type="hidden" name="Edit" value="<? echo $Edit?>">
<input type="hidden" name="DatNameSID" value="<? echo $DatNameSID?>">
</form>
</body>
</html>
