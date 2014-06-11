<?php
	if($DatNameSID){session_name("$DatNameSID");}
	session_start();
	include("Funciones.php");
	if($Medico!=""&&$Medico!="Todos"){$MedUsu="and medicos.usuario='$Medico'";}
	if($Especialidad!=""&&$Especialidad!="Todas"){$EspUsu="and especialidad='$Especialidad'";}
	$cons="select nombre,usuarios.usuario,cargo,especialidad from central.usuarios,salud.medicos where compania='$Compania[0]' and medicos.usuario=usuarios.usuario $MedUsu $EspUsu";
	//echo $cons;
	$res=ExQuery($cons);
	while($fila=ExFetch($res))
	{
		$Usus[$fila[1]]=$fila[0];
		$Medicos[$fila[1]]=array($fila[0],$fila[1],$fila[2],$fila[3]);
		$Cargos[$fila[1]]=$fila[3];
	}
	$cons="select identificacion,primnom,segnom,primape,segape from central.terceros where compania='$Compania[0]' and tipo='Asegurador'";	
	$res=ExQuery($cons);
	while($fila=ExFetch($res))
	{
		$Aseguradores[$fila[0]]="$fila[1] $fila[2] $fila[3] $fila[4]";
		//echo "$fila[0]=$fila[1] $fila[2] $fila[3] $fila[4]";
	}
?>	
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
</head>

<body background="/Imgs/Fondo.jpg">
<form name="FORMA" method="post" onSubmit="return validar()">  
<table BORDER=1  style='font : normal normal small-caps 11px Tahoma;' border="1" bordercolor="#e5e5e5" cellpadding="1" align="center">  <?
if($Entidad){$Ent="and entidad='$Entidad'";}
if($CUP){$Cu="and cup='$CUP'";}
if($Estado){$Est="and estado='$Estado'";}
if($Medico!=""&&$Medico!="Todos"){$Med="and medico='$Medico'";}
if($Especialidad!=""&&$Especialidad!="Todas"){$Esp="and especialidad='$Especialidad'";} 
if($OrgCancelacion){$OrgCancel="and origencancel='$OrgCancelacion'";}
if($MotvCancelacion){$MotivCancel="and motivocancel='$MotvCancelacion'";}
if($Cedula){$Ced="and cedula='$Cedula'";}
if($Confirmacion=="Si"){$Confirm=" and fechaconfirm is not null ";}
if($Confirmacion=="No"){$Confirm=" and fechaconfirm is null ";}
$cons="select fecha,hrsini,minsini,hrsfin,minsfin,entidad,cup,cedula,estado,medico,(primape || ' ' || segape || ' ' || primnom || ' ' || segnom ) as nom,nombre,especialidad,origencancel,motivocancel,fechaconfirm,observacionconfirm,nomconfrim,salud.agenda.solicitadapor
from salud.agenda,central.terceros,contratacionsalud.cups,salud.medicos
where agenda.compania='$Compania[0]' and agenda.fecha<='$FechaFin' and fecha>='$FechaIni' and terceros.compania='$Compania[0]' and identificacion=cedula and cups.compania='$Compania[0]'
and cups.codigo=cup and medicos.compania='$Compania[0]' and medicos.usuario=medico $Ent $Cu $Est $Med $Esp $OrgCancel $MotivCancel $Ced $Confirm
order by fecha,hrsini,minsini";
//echo $cons;
$res=ExQuery($cons);
if(ExNumRows($res)>0){
	$contusu=1;
	if($Especialidad!=""&&$Medico=="")
	{
		$cont=0;
		while($fila=ExFetch($res))
		{
			$Agenda[$cont]=array($fila[0],$fila[1],$fila[2],$fila[3],$fila[4],$fila[5],$fila[6],$fila[7],$fila[8],$fila[9],$fila[10],$fila[11],$fila[12],$fila[13],$fila[14],$fila[15],$fila[16],$fila[17],$fila[18]); 
			$EspExist[$fila[12]]=1;
			$cont++;
		}
		$cons2="select especialidad from salud.especialidades where compania='$Compania[0]' $Esp order by especialidad";
		$res2=ExQuery($cons2);
		
		while($fila2=ExFetch($res2))
		{
			if($EspExist[$fila2[0]])
			{?>
    	    	<tr bgcolor="#e5e5e5" style="font-weight:bold" align="center"><td colspan="16"><? echo strtoupper($fila2[0])?></td></tr>
				<tr bgcolor="#e5e5e5" style="font-weight:bold" align="center">
    				<td></td><td>Fecha</td><td>Hora</td><td>Paciente</td><td>Identificacion</td><td>Entidad</td><td>CUP</td><td>Nombre CUP</td>
                    <td>Medico</td><td>Estado</td><td>Origen Cancelacion</td><td>Mot Cancelacion</td><td>Fecha Confirma</td>
            		<td>Observacion Confirma</td><td>Usuario Confirma</td><td>Solicitada Por</td>
				</tr>	
<?				foreach($Agenda as $AGD)
				{
					if($AGD[12]==$fila2[0])
					{?>
						<tr onMouseOver="this.bgColor='#AAD4FF'" onMouseOut="this.bgColor=''">	
				<?		echo "<td>$contusu</td><td>$AGD[0]</td><td>$AGD[1]:$CH1$AGD[2]-$AGD[3]:$AGD[4]</td><td>$AGD[10]</td><td>$AGD[7]</td><td>".$Aseguradores[$AGD[5]]."</td>
				  		<td>$AGD[6]</td><td>$AGD[11]</td><td>".$Usus[$AGD[9]]."</td><td>$AGD[8]</td><td>$AGD[13]&nbsp;</td><td>$AGD[14]&nbsp;</td>
						<td>$AGD[15]&nbsp;</td><td>$AGD[16]&nbsp;</td><td>$AGD[17]&nbsp;</td><td>$AGD[18]&nbsp;</td>";
						$contusu++;
					}					
				}
				$AGD="";
			}
		}		
	}
	elseif($Especialidad!=""&&$Medico!="")
	{ 
		$cont=0;
		//echo $cons;
		while($fila=ExFetch($res))
		{
			$Agenda[$cont]=array($fila[0],$fila[1],$fila[2],$fila[3],$fila[4],$fila[5],$fila[6],$fila[7],$fila[8],$fila[9],$fila[10],$fila[11],$fila[12],$fila[13],$fila[14],$fila[15],$fila[16],$fila[17],$fila[18]);
			$EspExist[$fila[12]]=1;
			$MedExist[$fila[9]]=1;
			$cont++;
			//echo "xx $fila[14] ss $fila[15] xx $fila[16] ss $fila[17] <br>";
		}
		$cons2="select especialidad from salud.especialidades where compania='$Compania[0]' $Esp order by especialidad";
		$res2=ExQuery($cons2);
		while($fila2=ExFetch($res2))
		{				
			if($EspExist[$fila2[0]]){?>
            	<tr bgcolor="#e5e5e5" style="font-weight:bold" align="center"><td colspan="16"><? echo strtoupper($fila2[0])?></td></tr>
                
			<?	foreach($Medicos as $Med)
				{
					if($MedExist[$Med[1]]&&$Med[3]==$fila2[0])
					{?>
						<tr bgcolor="#e5e5e5" style="font-weight:bold" align="center"><td colspan="16"><? echo $Med[0]?></td></tr>	
                        <tr bgcolor="#e5e5e5" style="font-weight:bold" align="center">
    						<td></td><td>Fecha</td><td>Hora</td><td>Paciente</td><td>Identificacion</td><td>Entidad</td><td>CUP</td>
                            <td>Nombre CUP</td><td>Estado</td><td>Origen Cancelacion</td><td>Mot Cancelacion</td><td>Fecha Confirma</td>
                            <td>Observacion Confirma</td><td>Usuario Confirma</td><td>Solicitada por</td>
						</tr>
                        
				<?		foreach($Agenda as $AGD)
						{
							if($AGD[12]==$fila2[0]&&$AGD[9]==$Med[1])
							{?>
								<tr onMouseOver="this.bgColor='#AAD4FF'" onMouseOut="this.bgColor=''">	
						<?		echo "<td>$contusu</td><td>$AGD[0]</td><td>$AGD[1]:$CH1$AGD[2]-$AGD[3]:$AGD[4]</td><td>$AGD[10]</td><td>$AGD[7]</td><td>".$Aseguradores[$AGD[5]]."</td>
								<td>$AGD[6]</td><td>$AGD[11]</td><td>$AGD[8]</td><td>$AGD[13]&nbsp;</td><td>$AGD[14]&nbsp;</td>
								<td>$AGD[15]&nbsp;</td><td>$AGD[16]&nbsp;</td><td>$AGD[17]&nbsp;</td><td>$AGD[18]&nbsp;</td>";
								$contusu++;
							}
							
						}
						$AGD="";
					}
				}				
			}
		}		
	}
	elseif($Especialidad==""&&$Medico!="")
	{
		$cont=0;
		$contusu=1;
		while($fila=ExFetch($res))
		{
			$Agenda[$cont]=array($fila[0],$fila[1],$fila[2],$fila[3],$fila[4],$fila[5],$fila[6],$fila[7],$fila[8],$fila[9],$fila[10],$fila[11],$fila[12],$fila[13],$fila[14],$fila[15],$fila[16],$fila[17],$fila[18]);
			$MedExist[$fila[9]]=1;
			$cont++;
		}
		foreach($Medicos as $Med)
		{
			if($MedExist[$Med[1]])
			{?>
				<tr bgcolor="#e5e5e5" style="font-weight:bold" align="center"><td colspan="16"><? echo "$Med[0]-$Med[2]"?></td></tr>	
                <tr bgcolor="#e5e5e5" style="font-weight:bold" align="center">
                    <td></td><td>Fecha</td><td>Hora</td><td>Paciente</td><td>Identificacion</td><td>Entidad</td><td>CUP</td><td>Nombre CUP</td><td>Estado</td>
                    <td>Origen Cancelacion</td><td>Mot Cancelacion</td><td>Fecha Confirma</td>
            		<td>Observacion Confirma</td><td>Usuario Confirma</td><td>Solicitada por</td>
                </tr>
		<?		foreach($Agenda as $AGD)
                {
                    if($AGD[9]==$Med[1])
                    {?>
                        <tr onMouseOver="this.bgColor='#AAD4FF'" onMouseOut="this.bgColor=''">	
                <?		echo "<td>$contusu</td><td>$AGD[0]</td><td>$AGD[1]:$CH1$AGD[2]-$AGD[3]:$AGD[4]</td><td>$AGD[10]</td><td>$AGD[7]</td><td>".$Aseguradores[$AGD[5]]."</td>
                        <td>$AGD[6]</td><td>$AGD[11]</td><td>$AGD[8]</td><td>$AGD[13]&nbsp;</td><td>$AGD[14]&nbsp;</td>
						<td>$AGD[15]&nbsp;</td><td>$AGD[16]&nbsp;</td><td>$AGD[17]&nbsp;</td><td>$AGD[18]&nbsp;</td>";
                    	$contusu++;
					}					
                }
                $AGD="";
			}			
		}				
	}
	else
	{?>

		<tr bgcolor="#e5e5e5" style="font-weight:bold" align="center">
    		<td></td><td>Fecha</td><td>Hora</td><td>Paciente</td><td>Identificacion</td><td>Entidad</td><td>CUP</td><td>Nombre CUP</td><td>Medico</td>
            <td>Especialidad</td><td>Estado</td><td>Origen Cancelacion</td><td>Mot Cancelacion</td><td>Fecha Confirma</td><td>Observacion Confirma</td><td>Usuario Confirma</td>
            <td>Solicitada por</td>
		</tr>
	<?	while($fila=ExFetch($res)){
			if($fila[2]<10){$CH1="0";}else{$CH1="";}
			if($fila[4]<10){$CH2="0";}else{$CH2="";}?>
    		<tr onMouseOver="this.bgColor='#AAD4FF'" onMouseOut="this.bgColor=''">	
	<?		echo "<td>$contusu</td><td>$fila[0]</td><td>$fila[1]:$CH1$fila[2]-$fila[3]:$fila[4]</td><td>$fila[10]</td><td>$fila[7]</td>
			<td>".$Aseguradores[$fila[5]]."</td><td>$fila[6]</td><td>$fila[11]</td><td>".$Usus[$fila[9]]."</td><td>".$Cargos[$fila[9]]."</td>
			<td>$fila[8]</td><td>$fila[13]&nbsp;</td><td>$fila[14]&nbsp;</td><td>$fila[15]&nbsp;</td><td>$fila[16]&nbsp;</td><td>$fila[17]&nbsp;</td><td>$fila[18]&nbsp;</td>";
			$contusu++;
		}
		
	}
}
else{?>
	<tr bgcolor="#e5e5e5" style="font-weight:bold" align="center">
    	<td>NO SE ENCOTRARON REGISTROS QUE COINCIDAN CON LOS PARAMETROS DE BUSQUEDA</td>
    </tr><?	
}?>
</table>
<input type="hidden" name="DatNameSID" value="<? echo $DatNameSID?>"/>
</form>            
</body>
</html>
