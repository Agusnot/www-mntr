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
	if($Reversar)
	{
		$cons3="update salud.agenda set estado='Pendiente',fechaactiva=NULL,usuactiva=NULL,numservicio=NULL
		where compania='$Compania[0]' and hrsini='$HrIni' and minsini='$MinIni' and fecha='$Fecha' and cedula='$CedRev' and medico='$Profecional' and id=$Id";
		//echo $cons3;
		$res3=ExQuery($cons3);
		$cons3="delete from salud.servicios where compania='$Compania[0]' and cedula='$CedRev' and estado='AC' ";
		$res3=ExQuery($cons3);		
		//echo $cons3;
	}
?>	
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<script language="javascript">
function ValidarAsignacion(H,M){
<? 	$ND=getdate();
	$Fec=explode("-",$Fecha);?>		
	if(<? echo $ND[year]?><=<? echo $Fec[0]?>){
		if(<? echo $ND[year]?>==<? echo $Fec[0]?>){
			if(<? echo $ND[mon]?><=<? echo $Fec[1]?>){
				if(<? echo $ND[mon]?>==<? echo $Fec[1]?>){
					if(<? echo $ND[mday]?><=<? echo $Fec[2]?>){
						if(<? echo $ND[mday]?>==<? echo $Fec[2]?>){
							if(<? echo $ND[hours]?>==H){
								if(<? echo $ND[minutes]?><=M){		
									return 1;
								}
								else{			
									return 0;				
								}
							}
							else{
								if(<? echo $ND[hours]?><H){
									return 1;
								}
								else{				
									return 0;				
								}
							}
						}
						else{
							return 1;
						}
					}
					else{
						return 0;
					}
				}
				else{
					return 1;
				}
			}
			else{
				return 0;
			}
		}
		else{
			return 1;
		}
	}
	else{		
		return 0;
	}
		
	
}
function ValidarActivacion(H,M){
<? 	$resul=ExQuery("select * from salud.tiemposconsulta where compania='$Compania[0]'");
	$row=ExFetch($resul);
	
	$fechaac = date("Y-m-d H:i:s");
	$timeac = strtotime($fechaac);
	
	$operaan = "-";
	if($row[1]<0){
		$operaan="+";
	}
	$timean = strtotime($Fecha." ".$HrIni.":".$MinIni.":00"." $operaan".$row[1]." minutes");
	
	$operade = "+";
	if($row[2]<0){
		$operade="";
	}
	$timede = strtotime($Fecha." ".$HrIni.":".$MinIni.":00"." $operade".$row[2]." minutes");
	
	if($timeac>=$timean && $timeac<=$timede){
		echo "return 1;";
	}
	else{
		echo "return 0;";
	}
	
	/*$ND=getdate();
	//echo "alert('Fecha: '+$timeac+' '+$timean+' '+$timede+' Fec:'+$Fecha);";
	$MDespues=$row[2];	
	$HDespues=$ND[hours];
	
	$MAntes=$row[1];
	$HAntes=$ND[hours];
	
	if(($ND[minutes]-$row[1])>0){
		$MAntes=$ND[minutes]-$MAntes;		
	}
	else{
		if(($ND[minutes]-$row[1])==0){
			$MAntes=0;
			$HAntes--;
		}
		else{
			for($i=$row[1];$i>=60;$i=$i-60){
				$MAntes=$MAntes-60;
				$HAntes--;				
			}
			$aux=$ND[minutes]-$MAntes;
			if($aux>=0){
				$MAntes=$aux;
			}
			else{
				$MAntes=60-($MAntes-$ND[minutes]);
				$HAntes--;
			}
		}
	}
		
	if($ND[minutes]+$row[2]<60){
		$MDespues=$ND[minutes]+$MDespues;
	}
	else{
		if($ND[minutes]+$row[2]==60){
			$HDespues++;
			$MDespues=0;
		}
		else{
					
			for($i=$row[2];$i>=60;$i=$i-60){
				$MDespues=$MDespues-60;
				$HDespues++;				
			}
			$MDespues=$ND[minutes]+$MDespues;
			if($MDespues>=60){
				$MDespues=$MDespues-60;
				$HDespues++;
			}			
		}
	}
	$Fec=explode("-",$Fecha);*/?>	
	/*alert('hora del sistema<?echo $ND[hours]."-". $ND[minutes]?>')
	alert('hora antes <?echo $HAntes." ".$MAntes?>');	
	alert('hora a comparar <?echo $HrIni." ".$MinIni?>');
	alert('hora despues <?echo $HDespues." ".$MDespues?>');	*/
	/*if(<? echo $ND[year]?><=<? echo $Fec[0]?>&&<? echo $ND[mon]?><=<? echo $Fec[1]?>&&<? echo $ND[mday]?><=<? echo $Fec[2]?>){
		if(<? echo $HrIni?>==<? echo $HAntes?>){			
			if(<? echo $MinIni?>>=<? echo $MAntes?>){				
				if(<? echo $HrIni?>==<? echo $HDespues?>){
					if(<? echo $MinIni?><=<? echo $MDespues?>){					
						return 1;
					}
					else{
						return 0;
					}
				}
				else{
					if(<? echo $HrIni?><<? echo $HDespues?>){
						return 1;
					}
					else{
						return 0;
					}
				}				
			}
			else{
				return 0;
			}
		}
		else{		
			if(<? echo $HrIni?>><? echo $HAntes?>){
				if(<? echo $HrIni?>==<? echo $HDespues?>){
					if(<? echo $MinIni?><=<? echo $MDespues?>){					
						return 1;
					}
					else{
						return 0;
					}
				}
				else{
					if(<? echo $HrIni?><<? echo $HDespues?>){
						return 1;
					}
					else{
						return 0;
					}
				}
			}
			else{
				return 0;
			}
		}
	}
	else{
		return 0;
	}*/
	
	
	
}	
</script>
</head>

<body background="/Imgs/Fondo.jpg">
<form name="FORMA" method="post">
<input type="hidden" name="DatNameSID" value="<? echo $DatNameSID?>"/>
<table style='font : normal normal small-caps 12px Tahoma;' border="1" bordercolor="#e5e5e5" cellpadding="4" align="center"> 
<tr>
   	<td bgcolor="#e5e5e5" style="font-weight:bold" align="center" colspan="6"><? echo "$fila[0]-$Especialidad";?></td>            
</tr>

<tr><td bgcolor="#e5e5e5" style="font-weight:bold" align="center" colspan="6"> <? echo "$Fecha - $Diasem";?></td></tr>
<?	
$cons4="Select hrsini,minsini,hrsfin,minsfin,cedula,primape,segape,primnom,segnom,telefono,entidad,estado,tiempocons,usuconfirma from central.terceros,salud.agenda where
terceros.identificacion=agenda.cedula and medico='$Profecional' and hrsini='$HrIni' and minsini='$MinIni'and fecha='$Fecha' and (estado='Pendiente' or estado='Activa') and agenda.compania='$Compania[0]' and terceros.compania='$Compania[0]' and id=$Id";
	$res4=ExQuery($cons4);echo ExError();
if(ExNumRows($res4)>0){?> 			
	<tr bgcolor="#e5e5e5" style="font-weight:bold" align="center">
		<td>Hora</td><td>Cedula</td><td>Nombre<td>Telefono</td><td>Entidad</td><td>Estado</td>        
	</tr>
 <? while($fila4 = ExFetchArray($res4)){ 
	 	$cons3="select (primape || ' ' || segape || ' ' || primnom || ' ' || segnom) as Nombre  from Central.Terceros where  identificacion='$fila4[10]' and Tipo='Asegurador' and Compania='$Compania[0]' order by primape";
		$res3=ExQuery($cons3);echo ExError();//consulta de la agenda
		$fila3=ExFetchArray($res3);?>  
		<tr align="center">
     <? if($fila4[3]==0){$cero1='0';}else{$cero1='';}
		if($fila4[1]==0){$cero='0';}else{$cero='';} 
		echo "<td>$fila4[0]:$fila4[1]$cero-$fila4[2]:$fila4[3]$cero1</td><td>$fila4[4]</td><td>$fila4[5] $fila4[6] $fila4[7] $fila4[8]</td><td>$fila4[9]</td><td>$fila3[0]</td><td>$fila4[11]</td><tr>";  
		$UsuConfirm=$fila4['usuconfirma'];
	}						
}

	if(!$UsuConfirm)
	{?>
		<tr ><td colspan="6" align="left">
			<input type="radio" name="NewEstado" onClick="if(ValidarAsignacion('<? echo $HrIni?>','<? echo $MinIni?>')){location.href='ConfirmCitaAgend.php?DatNameSID=<? echo $DatNameSID?>&Id=<? echo $Id?>&Especialidad=<? echo $Especialidad?>&Profecional=<? echo $Profecional?>&Fecha=<? echo $Fecha?>&HrIni=<? echo $HrIni?>&MinIni=<? echo $MinIni?>&HrFin=<? echo $HrFin?>&MinFin=<? echo $MinFin?>';}else{alert('Hora o fecha de confirmación no valida!!!');}">Confirmar Cita</td>
		</tr>
<?	}
	if(!$Activa){		
	  	$cons="select cedula from salud.agenda where compania='$Compania[0]' and medico='$Profecional' and hrsini='$HrIni' and minsini='$MinIni'and fecha='$Fecha' and estado='Pendiente' and id=$Id";
		$res=ExQuery($cons);
		$fila=ExFetchArray($res);
		$consMulta="select cedula from salud.multas where compania='$Compania[0]' and estado='AC' and cedula='$fila[0]'";
		$resMulta=ExQuery($consMulta);
		//echo $consMulta;
		if(ExNumRows($resMulta)>0){$banMulta=1;}
		$cons="";
		$cons="select estado,numservicio from salud.servicios where estado='AC' and compania='$Compania[0]' and cedula='$fila[0]'";
		$res=ExQuery($cons);//echo $cons."<br>";
		$fila=ExFetchArray($res);
		
		if(ExNumRows($res)==0){
			if($banMulta!=1){?>
				<tr ><td colspan="6" align="left">
				<input type="radio" name="NewEstado" onClick="if(ValidarActivacion()){location.href='ActivarCitaAgend.php?DatNameSID=<? echo $DatNameSID?>&Id=<? echo $Id?>&Especialidad=<? echo $Especialidad?>&Profecional=<? echo $Profecional?>&Fecha=<? echo $Fecha?>&HrIni=<? echo $HrIni?>&MinIni=<? echo $MinIni?>&HrFin=<? echo $HrFin?>&MinFin=<? echo $MinFin?>';}else{alert('Hora o Fecha de activacion no valida');}">Activar Cita</td>
				</tr>
<?			}
			else
			{?>
				<tr><td colspan="6" align="center"> El Usuario tiene una multa por pagar por lo cual no puede activar la cita</td></tr>	
		<?	}
		}		
		else
		{
			$consDA="select medico from salud.agenda,salud.medicos where agenda.compania='$Compania[0]' and medicos.compania='$Compania[0]'
			and agenda.numservicio=$fila[1] and agenda.medico=medicos.usuario and medicos.especialidad!='$Especialidad'
			and medico!='$Profecional'";
			$resDA=ExQuery($consDA);
			$filaDA=ExFetch($resDA);
			///echo $consDA;
			if(!$filaDA[0])
			{?>
				<tr><td colspan="6" align="center"> El Usuario ya tiene un servicio activo por lo cual no puede activar la cita</td></tr>
<? 			}
			else{?>
            	<tr><td colspan="6">
				<input type="radio" name="NewEstado" onClick="if(confirm('El usuario tiene una cita activa,¿Aun así desea activar esta cita?')){if(ValidarActivacion()){location.href='ActivarCitaAgend.php?DatNameSID=<? echo $DatNameSID?>&Id=<? echo $Id?>&Especialidad=<? echo $Especialidad?>&Profecional=<? echo $Profecional?>&Fecha=<? echo $Fecha?>&HrIni=<? echo $HrIni?>&MinIni=<? echo $MinIni?>&HrFin=<? echo $HrFin?>&MinFin=<? echo $MinFin?>&NumServCitaAnt=<? echo $fila[1]?>';}else{alert('Hora o Fecha de activacion no valida');}}">Activar Cita (El usuario tiene una cita activa)
                </td></tr>
		<?	}
		}?>
		<tr ><td colspan="6" align="left">
			<input type="radio" name="NewEstado"  onClick="location.href='CancelCitaAgend.php?DatNameSID=<? echo $DatNameSID?>&Id=<? echo $Id?>&Especialidad=<? echo $Especialidad?>&Profecional=<? echo $Profecional?>&Fecha=<? echo $Fecha?>&HrIni=<? echo $HrIni?>&MinIni=<? echo $MinIni?>'">Cancelar Cita</td>
	</tr>
<?	}?>    
<tr ><td colspan="6" align="left">
	<input type="radio" name="NewEstado" onClick="if(ValidarAsignacion('<? echo $HrIni?>','<? echo $MinIni?>')){location.href='NewConfAgendMed.php?DatNameSID=<? echo $DatNameSID?>&Especialidad=<? echo $Especialidad?>&Horario=<? echo $Horario?>&Fecha=<? echo $Fecha?>&Profecional=<? echo $Profecional?>&HrIni=<? echo $HrIni?>&MinIni=<? echo $MinIni?>&Tiempo=<? echo $Tiempo?>&HrFin=<? echo $HrFin?>&MinFin=<? echo $MinFin?>&TimConsulta=<? echo $TimConsulta?>';}else{alert('Hora o Fecha de asignacion no valida');}">Agregar Sobrecupo</td>
</tr>	
<?
if($Activa){?>
    <tr>
        <td colspan="6" align="left">
        <input type="radio" name="NewEstado" onClick="location.href='NewEstadoAgend.php?DatNameSID=<? echo $DatNameSID?>&Id=<? echo $Id?>&Especialidad=<? echo $Especialidad?>&Profecional=<? echo $Profecional?>&Fecha=<? echo $Fecha?>&HrIni=<? echo $HrIni?>&MinIni=<? echo $MinIni?>&CedRev=<? echo $CedRev?>&Reversar=1'">Reversar Activacion
        </td>
    </tr><?
}?>
<tr>
	<td align="center" colspan="6"><input type="button" value="Cancelar"  onClick="location.href='ConfAgendMed.php?DatNameSID=<? echo $DatNameSID?>&Especialidad=<? echo $Especialidad?>&Profecional=<? echo $Profecional?>&AnioCalend=<? echo $F[0]?>&MesCalend=<? echo $F[1]?>&DiaCalend=<? echo $F[2]?>'"></td>
</tr>
</table>
</form>
</body>
</html>
