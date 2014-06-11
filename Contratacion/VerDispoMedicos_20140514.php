<?php	if($DatNameSID){session_name("$DatNameSID");}
	session_start();
	include("Funciones.php");  
	
$ND=getdate();
	if(!$Anio){$Anio=$ND[year];}
	if(!$Mes){$Mes=$ND[mon];}
	$cons="Select numdias from central.meses where numero=$Mes";
	$res=ExQuery($cons);
	$fila=ExFetch($res);
	$Dias=$fila[0];	
	$first_of_month = mktime (0,0,0, $Mes, 1, $Anio); 
	$Dias = date('t', $first_of_month); 
	//mktime(0,0,0,mes,dia,año);	
	function CalcDia($Dia)
	{
		global $Anio;
		global $Mes;
		$d=date('w',mktime(0,0,0,$Mes,$Dia,$Anio));	
		switch($d){
			case 1: $Diasem='Lun';return $Diasem; break;
			case 2: $Diasem='Mar';return $Diasem; break;
			case 3: $Diasem='Mie';return $Diasem; break;
			case 4: $Diasem='Juv';return $Diasem; break;
			case 5: $Diasem='Vie';return $Diasem; break;
			case 6: $Diasem='Sab';return $Diasem; break;
			case 0: $Diasem='Dom';return $Diasem; break;
		}
	}	
?>	
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
</head>

<body background="/Imgs/Fondo.jpg">
<table BORDER=1  style='font : normal normal small-caps 12px Tahoma;' border="1" bordercolor="#e5e5e5" cellpadding="4">
<?php
	for($Dia=1;$Dia<=$Dias;$Dia++)
	{
		$cons="Select  fecha,horaini,minsinicio,horasfin,minsfin,idhorario,citaspermitidas 
		from Salud.DispoConsExterna where Usuario='$Medico' and Compania='$Compania[0]' and Fecha='$Anio-$Mes-$Dia' 
		order by idhorario";
		
		$res=ExQuery($cons);
		$DiaSemana=CalcDia($Dia);
		if($Dia<10){$Cero='0';}else{$Cero='';};
		if(ExNumRows($res)>0)
		{	?>
			<tr onMouseOver="this.bgColor='#AAD4FF'" onMouseOut="this.bgColor=''"><td bgcolor="#e5e5e5" style="font-weight:bold" align="center"><? echo $Cero.$Dia." ".$DiaSemana?></td><td><?
			while($fila=ExFetch($res))
			{	if($fila[2]==0){$Cero2=0;}else{$Cero2='';}
				if($fila[4]==0){$Cero3=0;}else{$Cero3='';}
				echo "$fila[1]:$fila[2]$Cero2-$fila[3]:$fila[4]$Cero3; ";
			}
			echo "</td>";
			if($fila[6]>0){echo "<td>Sin limite de citas</td>";}else{echo "<td>Numero de Citas Limitado</td>";}?>
			<td><img title="Editar" src="/Imgs/b_edit.png" style="cursor:hand" onClick="parent.location.href='NewDiaDispMed.php?DatNameSID=<? echo $DatNameSID?>&Edit=1&Anio=<? echo $Anio?>&Mes=<? echo $Mes?>&Dia=<? echo $Dia?>&Medico=<? echo $Medico?>'">
		<?	echo "</td></tr>";
		}
		else
		{	?>
			
			<tr onMouseOver="this.bgColor='#AAD4FF'" onMouseOut="this.bgColor=''"><td bgcolor='#e5e5e5' style='font-weight:bold' align='center' ><? echo $Cero.$Dia." ".$DiaSemana?></td><td>-- No Definido --</td>
      	<?	if($fila[6]){echo "<td>$fila[6] citas permitidas</td>";}else{echo "<td>Sin limite de citas</td>";}?>
            <td>
			<img title="Editar" src="/Imgs/b_edit.png" style="cursor:hand" onClick="parent.location.href='NewDiaDispMed.php?DatNameSID=<? echo $DatNameSID?>&Edit=1&Anio=<? echo $Anio?>&Mes=<? echo $Mes?>&Dia=<? echo $Dia?>&Medico=<? echo $Medico?>'">
		<?	echo "</td></tr>";
		}
	}
?>
</table>
</body>
</html>
