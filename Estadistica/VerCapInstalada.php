<?
	if($DatNameSID){session_name("$DatNameSID");}
	session_start();
	include("Funciones.php");
	function calcular_tiempo_trasnc($hora1,$mins1,$hora2,$mins2)
	{ 		
		
		if($mins1>$mins2)
		{
			$hora1++;
			$H=($hora2-$hora1)*60;//echo "$hora1 H=$H<br>";
			$M=60-$mins1;
			$Tiemp=$H+$M;
		}		
		elseif($mins1==0)
		{
			$H=($hora2-$hora1)*60;
			$Tiemp=$mins2+$H;
		}
		else{
			$H=($hora2-$hora1)*60;
			$Tiemp=((60-$mins1)-$mins2)+$H;
		}
		//echo $H." -- ".$Tiemp."<br>";
		return $Tiemp;
	} 


?>	
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
</head>

<body background="/Imgs/Fondo.jpg"><?
if($Anio!=''&&$MesIni!=''&&$MesFin!=''&&$DiaIni!=''&&$DiaFin!='')
{	?>
	<table bordercolor="#e5e5e5" border="1"  cellpadding="1" cellspacing="1"style="font : normal normal small-caps 11px Tahoma;">	
 <?		$cont=1;
		if($MesIni<10){$C1="0";}else{$C1="";}
		if($DiaIni<10){$C2="0";}else{$C2="";}
		if($MesFin<10){$C3="0";}else{$C3="";}
		if($DiaFin<10){$C4="0";}else{$C4="";}?>
   		<tr bgcolor="#e5e5e5" style="font-weight:bold" align="center">
        	<td colspan="6">Capacidad Instalada Desde <? echo "$Anio-$C1$MesIni-$C2$DiaIni Hasta $Anio-$C3$MesFin-$C4$DiaFin"?></td>
       	</tr>  	
        <tr bgcolor="#e5e5e5" style="font-weight:bold"><td>Medico</td><td>Horas Disponibles</td><td>Horas Ocupadas</td><td>% Aprovechamiento</td></tr>
  	<?	$cons2="select usuario,horaini,minsinicio,horasfin,minsfin,idhorario,fecha from salud.dispoconsexterna where compania='$Compania[0]' 
		and fecha>='$Anio-$MesIni-$DiaIni' and fecha<='$Anio-$MesFin-$DiaFin'";
		//echo $cons2;
		$res2=ExQuery($cons2);
		while($fila2=ExFetch($res2))
		{
			$HorarioDisp[$fila2[0]][$fila2[6]][$fila[5]]=array($fila2[1],$fila2[2],$fila2[3],$fila2[4]);
		}
		
		$cons2="select medico,id,hrsini,minsini,hrsfin,minsfin from salud.agenda where fecha>='$Anio-$MesIni-$DiaIni' and fecha<='$Anio-$MesFin-$DiaFin' 
		and compania='$Compania[0]' and estado!='Cancelada'	order by medico,id";
		$res2=ExQuery($cons2);
		while($fila2=ExFetch($res2))
		{
			$HorarioAgend[$fila2[0]][$fila2[1]]=array($fila2[2],$fila2[3],$fila2[4],$fila2[5]);
		}
		//echo "$Especialidad";
		if(!$Especialidad){$Especialidad="Todas";}
		if($Especialidad!='Todas'){$Esp=" and especialidad='$Especialidad'";}
		$cons="select nombre,medicos.usuario from salud.medicos,central.usuarios
		where medicos.compania='$Compania[0]' and usuarios.usuario=medicos.usuario $Esp
		and medicos.usuario in (select usuario from salud.dispoconsexterna where fecha>='$Anio-$MesIni-$DiaIni' and fecha<='$Anio-$MesFin-$DiaFin' 
		and compania='$Compania[0]' group by usuario) group by nombre,medicos.usuario order by nombre";
		$res=ExQuery($cons);
		while($fila=ExFetch($res))
		{
			echo "<tr><td>$fila[0]</td>";
			$Tiemp=0;
			if($HorarioDisp[$fila[1]])
			{
				foreach($HorarioDisp[$fila[1]] as $Fec)
				{
					foreach($Fec as $IdH)
					{
						$Tiemp = $Tiemp+calcular_tiempo_trasnc($IdH[0],$IdH[1],$IdH[2],$IdH[3]);
						//echo "$fila[0] $IdH[0]:$IdH[1] a $IdH[2]:$IdH[3] Tiempo=$Tiemp<br>";
					}
				}	
	
				$horas=floor($Tiemp/60); 
				$minutos2=$Tiemp%60; 			
				if($minutos2<10){$minutos2='0'.$minutos2; }
				echo "<td align='center'>$horas:$minutos2</td>";
			}
			$TiempA=0;
			if($HorarioAgend[$fila[1]])
			{
				foreach($HorarioAgend[$fila[1]] as $IdHAge)
				{
					$TiempA = $TiempA+calcular_tiempo_trasnc($IdHAge[0],$IdHAge[1],$IdHAge[2],$IdHAge[3]);
					//echo "$fila[0] $IdHAge[0] $IdHAge[1] Tiempo=$TiempA<br>";
				}
			}
			$horas=floor($TiempA/60); 
			$minutos2=$TiempA%60; 			
			if($minutos2<10){$minutos2='0'.$minutos2; }
			echo "<td align='center'>$horas:$minutos2</td>";
			
			$Porcent="0";
			if($Tiemp){$Porcent=round((($TiempA/$Tiemp)*100),2);}
			echo "<td align='center'>$Porcent %</td></tr>";
			$TiempDTot=$TiempDTot+$Tiemp;
			$TiempATot=$TiempATot+$TiempA;
		}
		$horas1=floor($TiempDTot/60); 
		$minutos1=$TiempDTot%60; 			
		if($minutos1<10){$minutos1='0'.$minutos1; }
		
		$horas2=floor($TiempATot/60); 
		$minutos2=$TiempATot%60; 			
		if($TiempDTot){$ProcentajeTotal=round((($TiempATot/$TiempDTot)*100),2);}else{$ProcentajeTotal="0";}
		if($minutos2<10){$minutos2='0'.$minutos2; }
		echo "<tr><td align='Center'><strong>Totales</strong></td><td align='center'>$horas1:$minutos1</td><td align='center'>$horas2:$minutos2</td>
		<td align='center'>$ProcentajeTotal %</td></tr>";
		?>
 	</table>
<?				
}?>
</body>
</html>