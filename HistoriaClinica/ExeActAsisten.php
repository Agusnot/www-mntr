<?
	if($DatNameSID){session_name("$DatNameSID");}
	session_start();
	include("Funciones.php");
	$ND=getdate();
	$cons="select especialidad,cargo from salud.medicos where compania='$Compania[0]' and usuario='$usuario[1]'";
	$res=ExQuery($cons);
	$fila=ExFetch($res); $Especialidad=$fila[0]; $Cargo=$fila[1];
	
	if($Ejecutar)
	{
		if($PacAct){
			$consA="select nomactvidad,actvasistenciales.formato,actvasistenciales.id_item,msjhc,cup,tblformat from salud.actvasistenciales,historiaclinica.formatos
			where actvasistenciales.compania='$Compania[0]' and especialidad='$Especialidad' and id=$IdAct and formatos.compania='$Compania[0]' and formatos.tipoformato='$Especialidad'
			and actvasistenciales.formato=formatos.formato";	
			//echo $consA."<br>";
			$resA=ExQuery($consA);
			$filaA=ExFetch($resA);	
			$NumCampo=substr("00000",0,5-strlen($filaA[2])).$filaA[2];
			$Campo="CMP".$NumCampo;
			while(list($cad,$val) = each($PacAct))
			{
				$cons="select pabellon from salud.pacientesxpabellones where cedula='$cad' and estado='AC' and fechae is null";
				$res=ExQuery($cons); $fila=ExFetch($res); $Unidad=$fila[0]; 
				
				$cons="select tiposervicio,numservicio,dxserv from salud.servicios where cedula='$cad' and estado='AC'";
				$res=ExQuery($cons); $fila=ExFetch($res); $Ambito=$fila[0]; $Numserv=$fila[1]; $Dx=$fila[2];
				
				$cons="select id_historia from histoclinicafrms.$filaA[5] where cedula='$cad' and compania='$Compania[0]' and formato='$filaA[1]' and tipoformato='$Especialidad'
				order by id_historia desc";
				$res=ExQuery($cons); $fila=ExFetch($res); $Id_Histo=$fila[0]+1;
								
				$cons="insert into histoclinicafrms.$filaA[5] 
				(formato,tipoformato,id_historia,usuario,cargo,fecha,hora,cedula,ambito,unidadhosp,numservicio,compania,dx1,$Campo) 
				values ('$filaA[1]','$Especialidad','$Id_Histo','$usuario[1]','$Cargo','$FechaReg','$ND[hours]:$ND[seconds]:$ND[minutes]','$cad','$Ambito'
				,'$Unidad','$Numserv','$Compania[0]','$Dx','$filaA[3]')";
				//echo "$cons <br>";
				$res=ExQuery($cons);
				
				$cons="insert into salud.regactasist (compania,cedula,formato,especialidad,fecha,usuario,nomact,hora) values
				('$Compania[0]','$cad','$filaA[1]','$Especialidad','$FechaReg','$usuario[1]','$filaA[0]','$ND[hours]:$ND[seconds]:$ND[minutes]')";
				$res=ExQuery($cons);
				//echo $cons."<br>";
				if($filaA[4]){
					$cons="insert into histoclinicafrms.cupsxfrms (tipoformato,formato,id_historia,cedula,compania,numservicio,cup,id_item) values
					('$Especialidad','$filaA[1]',$Id_Histo,'$cad','$Compania[0]',$Numserv,'$filaA[4]',$filaA[2])";
					$res=ExQuery($cons);
					//echo $cons."<br>";
				}
			}
		}
		$Ver=1;
	}
	
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<script language='javascript' src="/calendario/popcalendar.js"></script>
</head>

<body background="/Imgs/Fondo.jpg">
<form name="FORMA" method="post">
<table bordercolor="#e5e5e5" border="1" style='font : normal normal small-caps 12px Tahoma;' align="center" cellpadding="2">
	<tr>
    	<td bgcolor="#e5e5e5" style="font-weight:bold">Proceso</td>
        <td>
    	<?	$cons2="select ambito from salud.ambitos where compania='$Compania[0]' and hospitalizacion=1 and ambito!='Sin Ambito'";
			$res2=ExQuery($cons2);?>
            <select name="Ambito" onChange="document.FORMA.submit()"><option></option>
      	<?	while($fila2=ExFetch($res2))
			{
				if($Ambito==$fila2[0])
				{echo "<option value='$fila2[0]' selected>$fila2[0]</option>";}
				else
				{echo "<option value='$fila2[0]'>$fila2[0]</option>";}
			}?>
            </select>
        </td>
        <td bgcolor="#e5e5e5" style="font-weight:bold">Unidad</td>
        <td>
        <?	$cons2="select pabellon from salud.pabellones where compania='$Compania[0]' and ambito='$Ambito'";
			$res2=ExQuery($cons2); ?>        
            <select name="Pabellon"><option></option>
            <?	while($fila2=ExFetch($res2))
				{
					if($Pabellon==$fila2[0])
					{echo "<option value='$fila2[0]' selected>$fila2[0]</option>";}
					else
					{echo "<option value='$fila2[0]'>$fila2[0]</option>";}
				}?>
            </select>
       	</td>   
        <td bgcolor="#e5e5e5" style="font-weight:bold">Actividad</td>
        <td>
        <?	$cons2="select nomactvidad,id from salud.actvasistenciales 
			where compania='$Compania[0]' and especialidad='$Especialidad'";
			$res2=ExQuery($cons2); ?>            
            <select name="Actividad">
            <?	while($fila2=ExFetch($res2))
				{
					if($Actividad==$fila2[1])
					{echo "<option value='$fila2[1]' selected>$fila2[0]</option>";}
					else
					{echo "<option value='$fila2[1]'>$fila2[0]</option>";}
				}?>
            </select>
        </td>     
        <td bgcolor="#e5e5e5" style="font-weight:bold">Fecha</td>
   	<?	if(!$FechaReg){
			if($ND[mon]<10){$C1="0";}else{$C1="";}
			if($ND[mday]<10){$C2="0";}else{$C2="";}
			$FechaReg="$ND[year]-$C1$ND[mon]-$C2$ND[mday]";
		}?>
        <td><input type="Text" name="FechaReg"  readonly onClick="popUpCalendar(this, FORMA.FechaReg, 'yyyy-mm-dd')" value="<? echo $FechaReg?>" style="width:80">
        </td>       
        <td>
        	<input type="submit" value="Ver" name="Ver">
        </td>
    </tr>
</table>
<br>
<?
if($Ver)
{
	
	//Matriz de Actividades
	$cons="select nomactvidad,formato,id_item,msjhc,cup from salud.actvasistenciales 
	where compania='$Compania[0]' and especialidad='$Especialidad'";
	$res=ExQuery($cons);
	$ban=0;
	while($fila=ExFetch($res))
	{
		$ban=1;
		$Acts[$fila[0]]=array($fila[0],$fila[1],$fila[2],$fila[3],$fila[4]);
	}
	
	if($Ambito){$Amb="and ambito='$Ambito'";}else{$Amb="";}
	if($Pabellon){$Pab="and pabellon = '$Pabellon'";}else{$Pab="";}
	
	//Matriz de Ambitos
	$cons="select ambito from salud.ambitos where compania='$Compania[0]' and ambito!='Sin Ambito' $Amb 
	and hospitalizacion=1 order by ambito";
	$res=ExQuery($cons);
	$ban1=0;
	while($fila=ExFetch($res))
	{
		$ban1=1;
		$Ambits[$fila[0]]=$fila[0];	
	}
	//Matriz de Pabellones 
	$cons="select pabellon,ambito from salud.pacientesxpabellones where compania='$Compania[0]' $Pab order by pabellon";
	$res=ExQuery($cons); 
	$ban2=0;
	while($fila=ExFetch($res))
	{
		$ban2=1;
		$Pabs[$fila[1]][$fila[0]]=$fila[0];
	}
	
	$cons="select nomactvidad,actvasistenciales.formato,actvasistenciales.id_item,tblformat,interprog from salud.actvasistenciales,historiaclinica.formatos
	where actvasistenciales.compania='$Compania[0]' and especialidad='$Especialidad' and id=$Actividad and formatos.compania='$Compania[0]' and formatos.tipoformato='$Especialidad'
	and actvasistenciales.formato=formatos.formato";
	//echo $cons;
	$res=ExQuery($cons);
	$fila=ExFetch($res);
	$NomAct=$fila[0]; $Formato=$fila[1]; $Tabla=$fila[3]; $Interprog=$fila[4];
	
	//Matriz de pacientes por pabellones
	if($Interprog==1){
		$cons="select cedula,pabellon,ambito,primape,segape,primnom,segnom from salud.pacientesxpabellones,central.terceros
		where pacientesxpabellones.compania='$Compania[0]' and terceros.compania='$Compania[0]' and estado='AC' and fechae is null
		and identificacion=cedula and cedula in (
			select agendainterna.cedula	from salud.agendainterna,salud.servicios,central.terceros 
			where agendainterna.compania='$Compania[0]' and profecional='$usuario[1]' and terceros.compania='$Compania[0]' and servicios.cedula=identificacion
			and estado='AC' and agendainterna.numservicio=servicios.numservicio and servicios.compania='$Compania[0]'
		)
		$Amb $Pab order by primape,segape,primnom,segnom";
	}
	else
	{
		$cons="select cedula,pabellon,ambito,primape,segape,primnom,segnom from salud.pacientesxpabellones,central.terceros
		where pacientesxpabellones.compania='$Compania[0]' and terceros.compania='$Compania[0]' and estado='AC' and fechae is null
		and identificacion=cedula $Amb $Pab order by primape,segape,primnom,segnom";
	}
	$res=ExQuery($cons);
	$ban3=0;
	//echo $cons;
	while($fila=ExFetch($res))
	{
		$ban3=1;		
		$cons3="select cedula from salud.regactasist where compania='$Compania[0]' and cedula='$fila[0]'
		and formato='$Formato' and especialidad='$Especialidad' and nomact='$NomAct' and fecha='$FechaReg'";
		//echo $cons3."<br>";
		$res3=ExQuery($cons3);
		if(ExNumRows($res3)>0){$Realizada=1;}else{$Realizada=0;}
		$Pacientes[$fila[2]][$fila[1]][$fila[0]]=array($fila[0],$fila[1],$fila[2],$fila[3],$fila[4],$fila[5],$fila[6],$Realizada);	
		
	}
	
	if($ban==1&&$ban1==1&&$ban2==1&&$ban3==1)
	{?>
        <table bordercolor="#e5e5e5" border="1" style='font : normal normal small-caps 12px Tahoma;' align="center" cellpadding="2">
        	<tr>
            	<td colspan="5" align="center">
                	<input type="submit" name="Ejecutar" value="Ejecutar">
                </td>
            </tr>
            <tr>
            	<td colspan="5" style="font-weight:bold" align="center"><? echo $FechaReg?></td>
            </tr>
        <?	foreach($Ambits as $Ambs)
            {	?>			
            <?	foreach($Pabs[$Ambs] as $PA){
					if($Pacientes[$Ambs][$PA]){?>
                        <tr  bgcolor="#e5e5e5" style="font-weight:bold" align="center">
                            <td colspan="5"><? echo $Ambs." - ".$PA?></td>
                        </tr>
                        <tr  bgcolor="#e5e5e5" style="font-weight:bold" align="center">
                            <td>Nombre</TD><td>Identificacion</td><td><? echo $NomAct?></td>
                       
                        </tr>
                <?	}
					if($Pacientes[$Ambs][$PA]){
						foreach($Pacientes[$Ambs][$PA] as $Pac)
						{
							if($Pac[2]==$Ambs){
								$cont=1;?>
								<tr onMouseOver="this.bgcolor='#00CCFF'" onMouseOut="this.bgcolr=''">
									<td><? echo strtoupper("$Pac[3] $Pac[4] $Pac[5] $Pac[6]");?></td><td><? echo $Pac[0]?></td>
									<td align="center">
										<input type="checkbox" name="PacAct[<? echo $Pac[0]?>]" checked <? if($Pac[7]==1){?> disabled <? }?>>
									</td>								
								</tr>
					<?		}
						}
					}
                }
            }?>
            <tr>
            	<td colspan="5" align="center">
                	<input type="submit" name="Ejecutar" value="Ejecutar">
                </td>
            </tr>
        </table>
        <input type="hidden" name="IdAct" value="<? echo $Actividad?>">
<?	}
	else
	{?>
		<table bordercolor="#e5e5e5" border="1" style='font : normal normal small-caps 12px Tahoma;' align="center" cellpadding="2">
        	<tr  bgcolor="#e5e5e5" style="font-weight:bold" align="center">
            <?	if($Interprog==1){
					echo "<td>Esta Actividad Requiere Asignacion de Pacientes Por Interconsultas</td>";
				}
				else{
            		echo "<td>No Se Encontraron Pacientes Para Ejecutar Actividades Asistenciales Sengun Los Criterios de Busqueda</td>";
				}?>
            </tr>
        </table>
<?	}
}?>
<input type="hidden" name="DatNameSID" value="<? echo $DatNameSID?>">
</form>
</body>
</html>
