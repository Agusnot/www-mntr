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
	if($Cancelar){
		$ND=getdate();
		if($Multa){
			$cons3="select valor,agenda.entidad from salud.agenda,central.terceros,salud.valormulta where terceros.identificacion=agenda.entidad and valormulta.compania='$Compania[0]'
			and terceros.compania='$Compania[0]' and agenda.compania='$Compania[0]' and hrsini='$HrIni' and minsini='$MinIni' and agenda.fecha='$Fecha' and medico='$Profecional' 
			and valormulta.tipoasegurador=terceros.tipoasegurador and cedula='$Cedula' and id=$Id";
			//echo $cons3;
	 		$res3=ExQuery($cons3);echo ExError();
			$fila3=ExFetch($res3); $Valor=$fila3[0]; $Eps=$fila3[1];
			if($Valor!=''){
				$cons3="insert into salud.multas (compania,usuario,entidad,fechacrea,cedula,valor) values
				('$Compania[0]','$usuario[1]','$Eps','$ND[year]-$ND[mon]-$ND[mday] $ND[hours]:$ND[minutes]:$ND[seconds]','$Cedula',$Valor)";
				$res3=ExQuery($cons3);
			}
		}
		
		$cons3="update salud.agenda set estado='Cancelada',nomcancelador='$NomCancelador',origencancel='$OrigenCancelacion',motivocancel='$MotivoCancelacion',observaciones='$Observaciones',			 		usucancel='$usuario[1]',fechacancel='$ND[year]-$ND[mon]-$ND[mday] $ND[hours]:$ND[minutes]:$ND[seconds]'
		where compania='$Compania[0]' and hrsini='$HrIni' and minsini='$MinIni' and fecha='$Fecha' and medico='$Profecional' and cedula='$Cedula' and id=$Id";
		$res3=ExQuery($cons3);
		//echo $cons3;
		if(!$Exp){
		 ?>  <script language="javascript">
	     		location.href='ConfAgendMed.php?DatNameSID=<? echo $DatNameSID?>&Especialidad=<? echo $Especialidad?>&Profecional=<? echo $Profecional?>&AnioCalend=<? echo $F[0]?>&MesCalend=<? echo $F[1]?>&DiaCalend=<? echo $F[2]?>';
        	</script> <?
		}
		else{?>
			<script language="javascript">
				location.href='CitasExpiradas.php?DatNameSID=<? echo $DatNameSID?>&FechaIni=<? echo $FechaIni?>&FechaFin=<? echo $FechaFin?>&Ver=1';
			</script>
	<?	}
	}
?>	
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<script language='javascript' src="/Funciones.js"></script>
<script language="javascript">
function validar(){
	if(document.FORMA.OrigenCancelacion.value==""){
		alert("Debe seleccionar el origen de la cancelacion!!!");return false;
	}
	if(document.FORMA.NomCancelador.value==""){
		alert("Debe digitar el nombre de quien cancela la cita!!!");return false;
	}
}
</script>
</head>

<body background="/Imgs/Fondo.jpg">
<form name="FORMA" method="post" onSubmit="return validar()">  
<input type="hidden" name="DatNameSID" value="<? echo $DatNameSID?>"/>
<table style='font : normal normal small-caps 12px Tahoma;' border="1" bordercolor="#e5e5e5" cellpadding="4" align="center"> 
<tr bgcolor="#e5e5e5" style="font-weight:bold" align="center">
	<td align="center" colspan="8">Cancelar Cita</td>
</tr>
<tr>
   	<td bgcolor="#e5e5e5" style="font-weight:bold" align="center" colspan="8"><? echo "$fila[0]-$Especialidad";?></td>            
</tr>
<tr><td bgcolor="#e5e5e5" style="font-weight:bold" align="center" colspan="8"> <? echo "$Fecha - $Diasem";?></td></tr>
<?	
$cons2="Select hrsini,minsini,hrsfin,minsfin,cedula,primape,segape,primnom,segnom,telefono,entidad,estado,tiempocons from central.terceros,salud.agenda where
terceros.identificacion=agenda.cedula and id=$Id and  medico='$Profecional' and hrsini='$HrIni' and minsini='$MinIni'and fecha='$Fecha' and estado='Pendiente' and agenda.compania='$Compania[0]' and terceros.compania='$Compania[0]'";
	$res2=ExQuery($cons2);echo ExError();
if(ExNumRows($res2)>0){?> 					
	</tr>
 <? $fila2 = ExFetchArray($res2);
	 if($fila2[3]==0){$cero1='0';}else{$cero1='';}
	if($fila2[1]==0){$cero='0';}else{$cero='';} ?>
	<tr><td bgcolor="#e5e5e5" style="font-weight:bold" align="center">Hora Cita</td><td><? echo "$fila2[0]:$fila2[1]$cero-$fila2[2]:$fila2[3]$cero1";?></td></tr>        
    <tr><td bgcolor="#e5e5e5" style="font-weight:bold" align="center">Nombre</td><td><? echo "$fila2[5] $fila2[6] $fila2[7] $fila2[8]";?></td></tr>
   	<tr><td bgcolor="#e5e5e5" style="font-weight:bold" align="center">Cedula</td><td><? echo $fila2[4]?></td></tr>
    <tr><td bgcolor="#e5e5e5" style="font-weight:bold" align="center">Telefono</td><td><? echo $fila2[9]?></td></tr>
    </tr>
<tr><td bgcolor="#e5e5e5" style="font-weight:bold" align="center">Origen de Cancelacion</td>
<? $cons3="select origencancelacion from salud.origencancelcita where compania='$Compania[0]' order by origencancelacion";
		$res3=ExQuery($cons3);?>
	 <td><select name="OrigenCancelacion" onChange="document.FORMA.submit()"><option></option>
<?   while($fila3=ExFetch($res3))
		{
			if($fila3[0]==$OrigenCancelacion){echo "<option selected value='$fila3[0]'>$fila3[0]</option>";}
			else{echo "<option value='$fila3[0]'>$fila3[0]</option>";}
		}?>
    </select></td>
<tr>       
   	<td bgcolor="#e5e5e5" style="font-weight:bold" align="center">Motivo Cancelacion</td>
<? 	$cons3="select motivocancelcita from salud.motivocancelcita where compania='$Compania[0]' and origencalcel='$OrigenCancelacion'";
	$res3=ExQuery($cons3);?>
    <td><select name="MotivoCancelacion">
<?	while($fila3=ExFetch($res3))
	{
		if($fila3[0]==$MotivoCancelacion){echo "<option selected value='$fila3[0]'>$fila3[0]</option>";}
		else{echo "<option value='$fila3[0]'>$fila3[0]</option>";}
	}?>
    </select>
    </td>
    </tr>
    <tr>
        <td bgcolor="#e5e5e5" style="font-weight:bold" align="center">Nombre de quien cancela</td>
        <td><input type="text" name="NomCancelador" onKeyUp="ExLetra(this)" onKeyDown="ExLetra(this)" value="<? echo $NomCancelador?>"></td>
    </tr>
    <tr>
        <td bgcolor="#e5e5e5" style="font-weight:bold" align="center">Multa</td>
        <td><input type="checkbox" name="Multa" <? if($Multa){?> checked<? }?>></td> 
    </tr>
    <tr>
        <td bgcolor="#e5e5e5" style="font-weight:bold" align="center">Observaciones</td>
        <td><textarea name="Observaciones" cols="30" rows="5" onKeyUp="xLetra(this)" onKeyDown="xLetra(this)"><? echo $Observaciones?></textarea></td> 
    </tr>
    <tr>
            <td align="center" colspan="8"><input type="submit" value="Cancelar Cita" name="Cancelar">
<?	if($Exp){?>
		<input type="button" value="Regresar"  onClick="location.href='/Admision/Agenda/CitasExpiradas.php?DatNameSID=<? echo $DatNameSID?>&FechaIni=<? echo $FechaIni?>&FechaFin=<? echo $FechaFin?>&Ver=1'">
<?	}
	else{?>
        <input type="button" value="Regresar"  onClick="location.href='NewEstadoAgend.php?DatNameSID=<? echo $DatNameSID?>&Id=<? echo $Id?>&Especialidad=<? echo $Especialidad?>&Profecional=<? echo $Profecional?>&Fecha=<? echo $Fecha?>&HrIni=<? echo $HrIni?>&MinIni=<? echo $MinIni?>'"><? 
	}
}?>
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
