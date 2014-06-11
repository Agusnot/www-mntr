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
	$MatNiveles["ALERTA"]=array("ALERTA","#3366FF","#FFFFFF");
	$MatNiveles["MEDIO"]=array("MEDIO","#C6FFFF","#000000");
	$MatNiveles["MEDIO ALTO"]=array("MEDIO ALTO","#6699CC","#000000");
	$MatNiveles["ALTO"]=array("ALTO","#339900","#FFFFFF");		
	//--	
	$cons="Select letraarea,area from historiaclinica.ead1 order by letraarea";
	$res=ExQuery($cons);
	while($fila=ExFetch($res))
	{		
		$MatAreas[$fila[1]]=array($fila[0],$fila[1]);
	}
	//--
	//$cons="SELECT fechaead, nomarea, sum(valor), edadmeses FROM historiaclinica.eadevaluacion where Compania='$Compania[0]' and Identificacion='$Paciente[1]' group by fechaead,edadmeses,nomarea order by fechaead,edadmeses,nomarea";
	$cons="SELECT fechaead, nomarea, item, edadmeses FROM historiaclinica.eadevaluacion where Compania='$Compania[0]' and Identificacion='$Paciente[1]'
	and valor=1 order by fechaead,edadmeses,nomarea,item";	
	$res=ExQuery($cons);
	$fe="";$ea="";
	while($fila=ExFetch($res))
	{
		//if($fe!=$fila[0]){if($fe){$MatValores[$fe]["TOTAL"]=array($fe,"TOTAL",$tot,$ea);}$fe=$fila[0];$ea=$fila[3];$tot=0;}
		$MatValores[$fila[0]][$fila[1]]=array($fila[0],$fila[1],$fila[2],$fila[3]);
		//$tot+=$fila[2];		
	}
	if($MatValores)
	{
		foreach($MatValores as $fe)
		{
			foreach($fe as $ar)
			{
				$MatValores[$ar[0]]["TOTAL"][0]=$ar[0];
				$MatValores[$ar[0]]["TOTAL"][1]="TOTAL";
				$MatValores[$ar[0]]["TOTAL"][2]+=$ar[2];
				$MatValores[$ar[0]]["TOTAL"][3]=$ar[3];
			}
		}
	}
	//if($fe){$MatValores[$fe]["TOTAL"]=array($fe,"TOTAL",$tot,$ea);}
	/*foreach($MatValores as $fec)
	{
		foreach($fec as $ar)
		{
			echo $ar[0]." -- ".$ar[1]." -- ".$ar[2]." -- ".$ar[3]."<br>";	
		}		
	}*/
	//--
	$cons="SELECT renglon, area, niveles, rangoedad, rangopuntos  FROM historiaclinica.eadparametrosnormativos order by renglon, area";
	$res=ExQuery($cons);	
	while($fila=ExFetch($res))
	{
		$MatParametros[$fila[0]][$fila[1]][$fila[2]]=array($fila[0],$fila[1],$fila[2],$fila[3],$fila[4]);
	}
	/*foreach($MatParametros as $renglon)
	{
		foreach($renglon as $Area)
		{
			foreach($Area as $niv)
			{
				echo $niv[0]." -- ".$niv[1]." -- ".$niv[2]." -- ".$niv[3]." -- ".$niv[4]."<br>";
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
//echo "<center><font face='Tahoma' color='#0066FF' size='+2' ><b>SINTESIS DE EVALUACIÓN </b></font></center><br>";
?>
<table  border="1" bordercolor="#e5e5e5" style='font : normal normal small-caps 11px Tahoma;' align="center">
<tr bgcolor="#e5e5e5" style="font-weight:bold" align="center">
<td rowspan="3">Edad<br>en<br>Meses</td><td colspan="20">PARAMETROS NORMATIVOS PARA LA EVALUACION DEL DESARROLLO DE NIÑOS MENORES DE 60 MESES</td>
</tr>
<tr bgcolor="#e5e5e5" style="font-weight:bold" align="center">
<?
foreach($MatAreas as $Area)
{?> 
    <td colspan="4"><? echo $Area[1]."<br>(".$Area[0].")"?></td>   
<?
}?>
<td colspan="4">TOTAL</td>
</tr>
<tr bgcolor="#e5e5e5" style="font-weight:bold" align="center">
<?
for($i=0;$i<5;$i++)
{
	foreach($MatNiveles as $Niv)
	{		
	?>
	<td bgcolor="<? echo $Niv[1]?>" style="color:<? echo $Niv[2]?>;"><? echo $Niv[0]?></td>	
	<?
	}
}?>
</tr>
<?
$ra="";
$RangoE="";
$RangoP="";
if($MatParametros)
{
	foreach($MatParametros as $Renglon)
	{?>
	<tr align="center">    	
		<?
        foreach($MatAreas as $Area)
        {
			if($Renglon[$Area[1]])
			{				
				foreach($MatNiveles as $Nivel)
				{
					if($Renglon[$Area[1]][$Nivel[0]])
					{
						//echo $Renglon[$Area[1]][$Nivel[0]][0]." -- ".$Renglon[$Area[1]][$Nivel[0]][1]."  -- ".$Renglon[$Area[1]][$Nivel[0]][2]."  -- ".$Renglon[$Area[1]][$Nivel[0]][3]."  -- ".$Renglon[$Area[1]][$Nivel[0]][4]."<br>";
						if($ra!=$Renglon[$Area[1]][$Nivel[0]][3])
						{
							$ra=$Renglon[$Area[1]][$Nivel[0]][3];
							$RangoE=explode("-",$Renglon[$Area[1]][$Nivel[0]][3]);
							?> 
							<td><? echo $Renglon[$Area[1]][$Nivel[0]][3]?></td>
							<?
						}
						$RangoP=explode("-",$Renglon[$Area[1]][$Nivel[0]][4]);
						
						$entra="";
						if($MatValores)
						{
							foreach($MatValores as $fe)
							{
								if($fe[$Area[1]])
								{
									if($fe[$Area[1]][3]>=$RangoE[0]&&$fe[$Area[1]][3]<=$RangoE[1])
									{										
										if($RangoP[0]!=""&&$RangoP[1]!="")
										{
											if($fe[$Area[1]][2]>=$RangoP[0]&&$fe[$Area[1]][2]<=$RangoP[1])
											{
												//echo $fe[$Area[1]][3]." -- ".$RangoE[0]." -- ".$Nivel[0]." -- ".$Nivel[1]."<br>";		
												$entra=1;break;
											}
										}
										else
										{							
											//echo $fe[$Area[1]][2]." -- ".$RangoP[0]." -- ".$Renglon[$Area[1]][$Nivel[0]][4].$Renglon[$Area[1]][$Nivel[0]][4]." -- ".$Nivel[0]." -- ".$Nivel[1]."<br>";	
											if($fe[$Area[1]][2]>=$RangoP[0]&&$RangoP[0]!="")
											{
												//echo $fe[$Area[1]][3]." -- ".$RangoE[0]." -- ".$Nivel[0]." -- ".$Nivel[1]."<br>";	
												$entra=1;break;
											}	
										}
									}
								}	
							}	
						}
						if($entra)
						{
							$colf=$Nivel[1];
							$coll=$Nivel[2];	
						}
						else
						{
							$colf="#FFFFFF";
							$coll="#000000";	
						}						
						?>
						<td bgcolor="<? echo $colf?>" style="color:<? echo $coll?>" ><? echo $Renglon[$Area[1]][$Nivel[0]][4]?></td>
						<?
					}	
				}				
			}                
        }
		if($Renglon["TOTAL"])
		{
			foreach($MatNiveles as $Nivel)
			{
				if($Renglon["TOTAL"][$Nivel[0]])
				{					
					if($ra!=$Renglon["TOTAL"][$Nivel[0]][3])
					{
						$ra=$Renglon["TOTAL"][$Nivel[0]][3];
						$RangoE=explode("-",$Renglon["TOTAL"][$Nivel[0]][3]);
						?> 
						<td><? echo $Renglon["TOTAL"][$Nivel[0]][3]?></td>
						<?
					}
					$RangoP=explode("-",$Renglon["TOTAL"][$Nivel[0]][4]);
					$entra="";
					if($MatValores)
					{
						foreach($MatValores as $fe)
						{
							if($fe["TOTAL"])
							{
								if($fe["TOTAL"][3]>=$RangoE[0]&&$fe["TOTAL"][3]<=$RangoE[1])
								{
									if($RangoP[0]!=""&&$RangoP[1]!="")
									{
										if($fe["TOTAL"][2]>=$RangoP[0]&&$fe["TOTAL"][2]<=$RangoP[1])
										{
											//echo $fe[$Area[1]][3]." -- ".$RangoE[0]." -- ".$Nivel[0]." -- ".$Nivel[1]."<br>";		
											$entra=1;break;
										}
									}
									else
									{										
										if($fe["TOTAL"][2]>=$RangoP[0]&&$RangoP[0]!="")
										{
											//echo $fe[$Area[1]][3]." -- ".$RangoE[0]." -- ".$Nivel[0]." -- ".$Nivel[1]."<br>";	
											$entra=1;break;
										}	
									}
								}
							}	
						}	
					}
					if($entra)
					{
						$colf=$Nivel[1];
						$coll=$Nivel[2];	
					}
					else
					{
						$colf="#FFFFFF";
						$coll="#000000";	
					}
					?>
					<td bgcolor="<? echo $colf?>" style="color:<? echo $coll?>"><? echo $Renglon["TOTAL"][$Nivel[0]][4]?></td>
					<?
				}	
			}	
		}
		?>
    </tr>	
	<?
    }
}
else
{
?>
<tr><td colspan="9">No existen registros!!!</td></tr>
<?
}
?>
</table>
</form>
</body>
