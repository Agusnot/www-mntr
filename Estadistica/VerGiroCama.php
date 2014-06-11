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
<?
if($Anio!=''&&$Mes!='')
{
	if($Ambito){$Amb=" and ambitos.ambito='$Ambito'";}
	$first_of_month = mktime (0,0,0, $Mes, 1, $Anio); 
	$LastDay = date('t', $first_of_month);?>
	<table bordercolor="#e5e5e5" border="1"  cellpadding="1" cellspacing="1"style='font : normal normal small-caps 11px Tahoma;'>	    	
    	<tr bgcolor="#e5e5e5" style="font-weight:bold" align="center"><td>Unidad</td><td>No Camas</td><td>Egresos</td>
        <td>Giro Cama</td><td>Num Dias Est</td><td>Prom Dias Est</td></tr>
<?	$cons="select pabellon,nocamas  from salud.pabellones,salud.ambitos where ambitos.ambito!='Sin Ambito' and hospitalizacion=1 $Amb
	and ambitos.ambito=pabellones.ambito and ambitos.compania='$Compania[0]' and pabellones.compania='$Compania[0]' 
	and pabellon!='Observacion Medica' order by pabellon";
	$res=ExQuery($cons);
	while($fila=ExFetch($res))
	{
		if($Ambito){;$Amb2="  and tiposervicio='$Ambito'";}
		$cons2="select count(servicios.numservicio)
		from central.terceros,salud.servicios,salud.pacientesxpabellones
		where terceros.compania='$Compania[0]' and servicios.compania='$Compania[0]' and identificacion=servicios.cedula 
		and servicios.fechaegr>='$Anio-$Mes-1' and servicios.fechaegr<='$Anio-$Mes-$LastDay' 
		and pacientesxpabellones.numservicio=servicios.numservicio and pacientesxpabellones.pabellon='$fila[0]'  $Amb2";	
		//echo $cons2;	
		$res2=ExQuery($cons2);
		$contIng=0;
		if(ExNumRows($res2)>0){
			$fila2=ExFetch($res2);
			$contIng=$fila2[0];		
		}		
		$Giro=$fila2[0]/$fila[1];
		//$cons1="SELECT FechaI,FechaE FROM pacientesxpabellones where month(FechaE)=$Mes and Year(FechaE)=$Anio and Pabellon='$fila[0]'";
		$cons3="select fechai,fechae from salud.pacientesxpabellones where compania='$Compania[0]' and pabellon='$fila[0]'
		and (date_part('month',Fechae)=$Mes and date_part('month',Fechae) is not null ) 
		and (date_part('year',Fechae)=$Anio and date_part('year',Fechae) is not null  )";
		$res3=ExQuery($cons3);
		//echo $cons3."<br>";
		while($fila3=ExFetch($res3))
		{
			$date2="$fila3[0]";
			if(!$fila3[1]){
				if($Mes==$ND[mon]&&$ND[year]==$Anio)
				{	
					$date1=$Anio."-".$Mes."-".$ND[mday];
				}
				else{
					$date1=$Anio."-".$Mes."-".$LastDay;
				}
				//echo $date1." ";
			}
			else{
				$date1="$fila3[1]";
			}			
			$s = strtotime($date1)-strtotime($date2);
			$d = intval($s/86400);
			$NumDiasEst=$NumDiasEst+$d;	
			if($fila[0]=="Eugenio Ramirez")
			{
				//echo "date2=$date2 date1=$date1 s=$s d=$d NumDiasEst=$NumDiasEst<br>";
			}
		}
		if(!$NumDiasEst){$NumDiasEst="0";}
		if($fila2[0]>0)
		{
			$PromDiasEst=number_format($NumDiasEst/$fila2[0],2);	
		}
		?>
		<tr align="center">
        	<td><? echo $fila[0]?></td><td align="center"><? echo $fila[1]?></td><td><? echo $fila2[0]?></td>
            <td><? echo substr($Giro,0,4)?></td><td><? echo $NumDiasEst?></td><td><? echo $PromDiasEst?></td>
        </tr>	
<?		$NumDiasEst=0;
	}?>
    </table>
<?
}?>
</body>
</html>