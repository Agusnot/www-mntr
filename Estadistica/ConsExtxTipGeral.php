<?
	if($DatNameSID){session_name("$DatNameSID");}
	session_start();
	include("Funciones.php");
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
</head>

<body background="/Imgs/Fondo.jpg"><?
if($Anio!=''&&$MesIni!=''&&$MesFin!=''&&$DiaIni!=''&&$DiaFin!='')
{	?>
	<table bordercolor="#e5e5e5" border="1"  cellpadding="1" cellspacing="1"style="font : normal normal small-caps 11px Tahoma;" align="center">
<?		$cont=1;
		if($MesIni<10){$C1="0";}else{$C1="";}
		if($DiaIni<10){$C2="0";}else{$C2="";}
		if($MesFin<10){$C3="0";}else{$C3="";}
		if($DiaFin<10){$C4="0";}else{$C4="";}?>
   		<tr bgcolor="#e5e5e5" style="font-weight:bold" align="center">
        	<td colspan="8">Consulta Externa Por Tipo General <? echo "$Anio-$C1$MesIni-$C2$DiaIni Hasta $Anio-$C3$MesFin-$C4$DiaFin"?></td>
       	</tr>  	    	
		</tr>
   	</table>
    <br>
    <table bordercolor="#e5e5e5" border="1"  cellpadding="2" cellspacing="2"style="font : normal normal small-caps 11px Tahoma;" align="center">
 	<?	if($Especialidad&&$Especialidad!="Todas"){$Esp="and especialidad='$Especialidad'";}
		$cons="select count(cup),cup,nombre,especialidad from salud.agenda,contratacionsalud.cups,salud.medicos
		where agenda.compania='$Compania[0]' and fecha>='$Anio-$C1$MesIni-$C2$DiaIni' and cups.compania='$Compania[0]' and medicos.compania='$Compania[0]'
		and fecha<='$Anio-$C1$MesFin-$C2$DiaFin' and cup=codigo and medicos.usuario=agenda.medico $Esp
		group by cup,nombre,especialidad  order by nombre";
		$res=ExQuery($cons);
		while($fila=ExFetch($res))
		{
			$Agenda[$fila[3]][$fila[1]]=array($fila[1],$fila[2],$fila[0]);
		}
		$cons="select count(cup),cup,nombre,especialidad from salud.agenda,contratacionsalud.cups,salud.medicos
		where agenda.compania='$Compania[0]' and fecha>='$Anio-$C1$MesIni-$C2$DiaIni' and cups.compania='$Compania[0]' and medicos.compania='$Compania[0]'
		and fecha<='$Anio-$C1$MesFin-$C2$DiaFin' and cup=codigo and medicos.usuario=agenda.medico and (agenda.estado='Activa' or agenda.estado='Atendida')
		$Esp
		group by cup,nombre,especialidad";
		$res=ExQuery($cons);
		while($fila=ExFetch($res))
		{
			$porcRealizadas=round((($fila[0]/$Agenda[$fila[3]][$fila[1]][2])*100),0);			
			array_push($Agenda[$fila[3]][$fila[1]],$fila[0],$porcRealizadas);
		}
		$cons="select count(cup),cup,nombre,especialidad from salud.agenda,contratacionsalud.cups,salud.medicos
		where agenda.compania='$Compania[0]' and fecha>='$Anio-$C1$MesIni-$C2$DiaIni' and cups.compania='$Compania[0]' and medicos.compania='$Compania[0]'
		and fecha<='$Anio-$C1$MesFin-$C2$DiaFin' and cup=codigo and medicos.usuario=agenda.medico and agenda.estado='Cancelada' $Esp
		group by cup,nombre,especialidad order by nombre";
		$res=ExQuery($cons);
		while($fila=ExFetch($res))
		{
			$porcCanceladas=round((($fila[0]/$Agenda[$fila[3]][$fila[1]][2])*100),0);
			//echo $Agenda[$fila[3]][$fila[1]][2]."<br>";
			array_push($Agenda[$fila[3]][$fila[1]],$fila[0],$porcCanceladas);
		}
		$cons="select count(cup),cup,nombre,especialidad from salud.agenda,contratacionsalud.cups,salud.medicos
		where agenda.compania='$Compania[0]' and fecha>='$Anio-$C1$MesIni-$C2$DiaIni' and cups.compania='$Compania[0]' and medicos.compania='$Compania[0]'
		and fecha<='$Anio-$C1$MesFin-$C2$DiaFin' and cup=codigo and medicos.usuario=agenda.medico and agenda.estado='Pendiente' $Esp
		group by cup,nombre,especialidad  order by nombre";
		$res=ExQuery($cons);
		while($fila=ExFetch($res))
		{
			$porcNoReal=round((($fila[0]/$Agenda[$fila[3]][$fila[1]][2])*100),0);
			array_push($Agenda[$fila[3]][$fila[1]],$fila[0],$porcNoReal);
		}
		//echo $cons;
		$cons="select especialidad from salud.especialidades where compania='$Compania[0]' 
		and especialidad in (select especialidad from salud.agenda,contratacionsalud.cups,salud.medicos
		where agenda.compania='$Compania[0]' and fecha>='$Anio-$C1$MesIni-$C2$DiaIni' and cups.compania='$Compania[0]' and medicos.compania='$Compania[0]'
		and fecha<='$Anio-$C1$MesFin-$C2$DiaFin' and cup=codigo and medicos.usuario=agenda.medico group by medicos.especialidad) $Esp
		order by especialidad ";
		//echo $cons;
		$res=ExQuery($cons);
		while($fila=ExFetch($res))
		{?>
			<tr bgcolor="#e5e5e5" style="font-weight:bold" align="center"><td colspan="8"><? echo strtoupper($fila[0])?></td></tr>	
            <tr bgcolor="#e5e5e5" style="font-weight:bold" align="center">
            	<td>CUP</td><td>Prog</td><td colspan="2">Real</td><td colspan="2">Canc</td><td colspan="2">No Real</td>
         	</tr>	
	<?		foreach($Agenda[$fila[0]] as $Agend)
			{
				if(!$Agend[2]){$Agend[2]="0";}if(!$Agend[3]){$Agend[3]="0";}if(!$Agend[4]){$Agend[4]="0";}if(!$Agend[5]){$Agend[5]="0";}
				if(!$Agend[6]){$Agend[6]="0";}if(!$Agend[7]){$Agend[7]="0";}if(!$Agend[8]){$Agend[8]="0";}?>
				<tr>
        	<?		echo "<td>$Agend[0] - $Agend[1]</td><td  align='right'>$Agend[2]</td><td  align='right'>$Agend[3]</td><td  align='right'>$Agend[4]%</td>
					<td align='right'>$Agend[5]</td><td align='right'>$Agend[6]%</td><td align='right'>$Agend[7]</td><td align='right'>$Agend[8]%</td></tr>";       
					$TotProg=$TotProg+$Agend[2]; $TotReal=$TotReal+$Agend[3]; $TotCanc=$TotCanc+$Agend[5]; $TotNoReal=$TotNoReal+$Agend[7];  
			}
		}
		if($TotProg>0){
			$PorctReal=round((($TotReal/$TotProg)*100),0); $PorctCanc=round((($TotCanc/$TotProg)*100),0); $PorctNoReal=round((($TotNoReal/$TotProg)*100),0);
		}
		else{
			$TotProg="0";
		}
		if(!$TotReal){$TotReal="0";}if(!$TotCanc){$TotCanc="0";}if(!$TotNoReal){$TotNoReal="0";}
        echo "<tr style='font-weight:bold'><td align='right'><strong>TOTALES</strong></td><td align='right'>$TotProg</td><td align='right'>$TotReal</td>
		<td align='right'>$PorctReal%</td><td align='right'>$TotCanc</td><td align='right'>$PorctCanc%</td>
		<td align='right'>$TotNoReal</td><td align='right'>$PorctNoReal%</td></tr>";?>
    </table>
<?
}?>    
</body>
</html>