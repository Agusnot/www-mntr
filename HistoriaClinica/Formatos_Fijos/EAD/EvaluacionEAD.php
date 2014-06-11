<?
	if($DatNameSID){session_name("$DatNameSID");}
	session_start();
	include("Funciones.php");
	$ND=getdate();
	if($ND[mon]<10){$ND[mon]="0".$ND[mon];}
	if($ND[mday]<10){$ND[mday]="0".$ND[mday];}
	$FechaHoy=$ND[year]."-".$ND[mon]."-".$ND[mday];
	if($ND[hours]<10){$ND[hours]="0".$ND[hours];}
	if($ND[minutes]<10){$ND[minutes]="0".$ND[minutes];}
	if($ND[seconds]<10){$ND[seconds]="0".$ND[seconds];}
	$HoraHoy=$ND[hours].":".$ND[minutes].":".$ND[seconds];	
	if($Paciente[48]!=$FechaHoy){echo "<em><center><br><br><br><br><br><font size=5 color='BLUE'>La Hoja de Identificacion no se ha guardado!!!";exit;}		
	$cons="Select numservicio from Salud.Servicios where Compania='$Compania[0]' and Cedula='$Paciente[1]' and estado='AC'";
	$res=ExQuery($cons);
	$fila=ExFetch($res);
	$NumServicio=$fila[0];	
	//--	
	$cons="Select letraarea,area,IdItem,NombreItem,rangoedad from historiaclinica.ead1 order by letraarea,IdItem";
	$res=ExQuery($cons);
	while($fila=ExFetch($res))
	{
		$MItems[$fila[2]]=$fila[2];
		$MatEscala[$fila[1]][$fila[2]]=array($fila[0],$fila[1],$fila[2],$fila[3],$fila[4]);
		$MatAreas[$fila[1]]=array($fila[0],$fila[1]);
	}
	//--
	$cons="SELECT nomarea, item, valor, fechaead, edadmeses FROM historiaclinica.eadevaluacion where Compania='$Compania[0]' and Identificacion='$Paciente[1]' 
	order by fechaead,edadmeses,nomarea,item";
	$res=ExQuery($cons);	
	while($fila=ExFetch($res))
	{
		$MatValores[$fila[0]][$fila[1]][$fila[3]]=array($fila[0],$fila[1],$fila[2],$fila[3],$fila[4]);
		$MatEdades[$fila[3]]=array($fila[3],$fila[4]);
	}
	/*foreach($MatValores as $are)
	{
		foreach($are as $idit)
		{
			foreach($idit as $fee)
			{
				echo $fee[0]." --- ".$fee[1]." --- ".$fee[2]." --- ".$fee[3]." --- ".$fee[4]."<br>";
			}
		}
	
	}*/
	
	
?>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<style type="text/css"> body { background-color: transparent } </style>
<script language="javascript" src="/Funciones.js"></script>
<script language='javascript' src="/calendario/popcalendar.js"></script>
<script language="javascript">

</script>
</head>
<body background="/Imgs/Fondo.jpg">
<form name="FORMA" method="post" >
<input type="hidden" name="DatNameSID" value="<? echo $DatNameSID?>">
<input type="hidden" name="NumServicio" value="<? echo $NumServicio?>" />
<?
echo "<center><font face='Tahoma' color='#0066FF' size='+2' ><b>HISTORIAL ESCALA ABREVIADA DE DESARROLLO (EAD-1) </b></font></center><br>";
if($MatEscala&&$MatAreas)
{
	$FechaNacimiento=$Paciente[23];
	$EdadPacienteMeses=(ObtenEdad($FechaNacimiento)*12)+ObtenMesesEnEdad($FechaNacimiento);	
	if(!$EdadMeses){$EdadMeses=$EdadPacienteMeses;}
?>
<center>
<input type="button" name="NuevaEvaluacion" value="Nueva Evaluación (EAD-1)" onClick="if(document.FORMA.NumServicio.value==''){alert('No se puede crear un nuevo registro porque el paciente no tiene servicios activos!!!');}else{document.location.href='NuevaEvaluacionEAD.php?DatNameSID=<? echo $DatNameSID?>&NumServicio=<? echo $NumServicio?>';}" style="cursor:hand" title="Nueva Evaluación Escala Abreviada de Desarrollo" />
</center>
<table  border="1" bordercolor="#e5e5e5" style='font : normal normal small-caps 10px Tahoma;'>
<tr bgcolor="#e5e5e5" style="font-weight:bold" align="center">
<td rowspan="2">Rango<br />Edad</td>
<?
if(count($MatEdades)>0)
{
	$cp=count($MatEdades);
	$colsp="colspan=$cp";
}
else
{
	$colsp="";	
}

foreach($MatAreas as $Area)
{?>    
    <td rowspan="2">Item</td>
    <td rowspan="2"><? echo $Area[0]."<br>".$Area[1]?></td>
    <td <? echo $colsp?>>Edad Evalucación meses</td>    
<?
}?>
</tr>
<tr bgcolor="#e5e5e5" style="font-weight:bold" align="center">
<?
foreach($MatAreas as $Area)
{
	if($MatEdades)
	{
		foreach($MatEdades as $Ed)
		{
			?><td align="center"><? if($Ed[1]==-1){echo "< 1";}else{ echo $Ed[1];}?></td>	<?
		}
	}
	else
	{?>
	<td>N/A</td>	
	<?
	}
}
?>
</tr>
<?
///----
$crp=0;
foreach($MItems as $IdItem)
{
	$re="";		
	if($crp>1)
	{		
		if((3%($crp-1))==0&&$crp!=2)
		{
			//echo "$IdItem $crp entra mod 0<br>";			
			$rowsp="rowspan='3'";
			$crp=2;	
		}
		else
		{			
			//echo "$IdItem $crp entra 'vacio' <br>";
			$rowsp="";	
			$crp++;			
		}
	}
	elseif($crp==1)
	{
		//echo "$IdItem $crp entra ==1<br>";
		$rowsp="rowspan='3'";	
		$crp++;		
	}
	else
	{
		//echo "$IdItem $crp entra 0<br>";
		$crp++;
		$rowsp="rowspan='1'";		
	}
	?>
    <tr>
    <?
	foreach($MatEscala as $Ar)
	{
		$Aria=$Ar[$IdItem][1];	
		if($rowsp)
		{
			if($IdItem<10){$nc=1;$sc=4;}else{$nc=2;$sc=5;}
			//echo $EdadMeses." -- ".substr($Ar[$IdItem][4],0,$nc)." --> ".substr($Ar[$IdItem][4],$sc,$nc);
			if($EdadMeses>=substr($Ar[$IdItem][4],0,$nc)&&$EdadMeses<=substr($Ar[$IdItem][4],$sc,$nc)||$EdadMeses==-1&&$Ar[$IdItem][4]=="< 1")
			{
				?><script language="javascript">document.FORMA.idini.value="<? echo $IdItem;?>";
				if(document.FORMA.idini.value==0){document.FORMA.idfin.value=0;}else{document.FORMA.idfin.value=parseInt(document.FORMA.idini.value)+2;}</script><?	
			}			
		if(!$re)
		{
			$re=1;
			if($Ar[$IdItem][4]!="< 1")
			{			
				$Rango=str_replace(" ","<br><br>",$Ar[$IdItem][4]);
			}
			else
			{$Rango=$Ar[$IdItem][4];}
			?>	    
			<td align="center" <? echo $rowsp?> ><? echo $Rango?></td>
			<?
			}
		}?>

		<td  align="right"  ><? echo $Ar[$IdItem][2]?></td>
		<td ><? echo $Ar[$IdItem][3]?></td>
        <?
        if($MatValores[$Aria][$IdItem])
        {
			foreach($MatEdades as $Ed)
			{
				if($MatValores[$Aria][$IdItem][$Ed[0]])
				{				
					?><td align="center" style="font-weight:bold"><? echo $MatValores[$Aria][$IdItem][$Ed[0]][2]?></td>	<?
				}
				else
				{
					?><td>&nbsp;</td><?	
				}
			}
        }
        else
        {
			if($MatEdades)
			{
				foreach($MatEdades as $Ed)
				{
					?>
					<td>&nbsp;</td>	                
					<?
				}
			}
			else
			{
				?><td>&nbsp;</td><?
			}
        }
	}
	?>
    </tr>
    <?
}
?>
</table>
<?
}
?>
<center>
<input type="button" name="NuevaEvaluacion" value="Nueva Evaluación (EAD-1)" onClick="if(document.FORMA.NumServicio.value==''){alert('No se puede crear un nuevo registro porque el paciente no tiene servicios activos!!!');}else{document.location.href='NuevaEvaluacionEAD.php?DatNameSID=<? echo $DatNameSID?>&NumServicio=<? echo $NumServicio?>';}" style="cursor:hand" title="Nueva Evaluación Escala Abreviada de Desarrollo" />
</center>
</form>
</body>
