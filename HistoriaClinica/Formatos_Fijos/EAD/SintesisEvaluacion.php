<?	if($DatNameSID){session_name("$DatNameSID");}
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
	//--	
	$cons="Select letraarea,area from historiaclinica.ead1 group by letraarea,area order by letraarea";
	$res=ExQuery($cons);
	//echo $cons;
	while($fila=ExFetch($res))
	{		
		$MatAreas[$fila[1]]=array($fila[0],$fila[1]);
	}
	//--
	//$cons="SELECT fechaead, nomarea, sum(valor), edadmeses FROM historiaclinica.eadevaluacion where Compania='$Compania[0]' and Identificacion='$Paciente[1]' group by fechaead,edadmeses,nomarea order by fechaead,edadmeses,nomarea";	
	$cons="SELECT fechaead, nomarea, item, edadmeses FROM historiaclinica.eadevaluacion where Compania='$Compania[0]' and Identificacion='$Paciente[1]'
	and valor=1 order by fechaead,edadmeses,nomarea,item";
	//echo $cons;	
	$res=ExQuery($cons);	
	while($fila=ExFetch($res))
	{
		$MatValores[$fila[0]][$fila[1]]=array($fila[0],$fila[1],$fila[2],$fila[3]);		
	}
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
<?
echo "<center><font face='Tahoma' color='#0066FF' size='+2' ><b>SINTESIS DE EVALUACIÓN </b></font></center>";
?>
<form name="FORMA" method="post" >
<input type="hidden" name="DatNameSID" value="<? echo $DatNameSID?>">
<input type="hidden" name="NumServicio" value="<? echo $NumServicio?>" />

<table  border="1" bordercolor="#e5e5e5" style='font : normal normal small-caps 11px Tahoma;' align="center">
<tr bgcolor="#e5e5e5" style="font-weight:bold" align="center">
<td colspan="3">Fecha Evaluación</td><td>Edad</td><td colspan="5">Resultados por Areas</td>
</tr>
<tr bgcolor="#e5e5e5" style="font-weight:bold" align="center">
<td>Dia</td><td>Mes</td><td>Año</td><td>Meses</td>
<?
foreach($MatAreas as $Area)
{?> 
    <td><? echo $Area[0]."<br>".$Area[1]?></td>   
<?
}?>
<td>Total</td>
</tr>
<?
if($MatValores)
{
	foreach($MatValores as $FechaEval)
	{
		?>
        <tr align="center" onMouseOver="this.bgColor='#AAD4FF'" onMouseOut="this.bgColor=''">
        <?
		$pv="";
		$Tot=0;
		foreach($FechaEval as $Ar)
		{
			if(!$pv)
			{
			$pv=1;
			
			?>
			<td><? echo substr($Ar[0],8,2);?></td>		
            <td><? echo substr($Ar[0],5,2);?></td>		
            <td><? echo substr($Ar[0],0,4);?></td>		
            <td><? if($Ar[3]=="-1"){echo "< 1";}else{ echo $Ar[3];}?></td>		           
			<?
            }			
		}		
		foreach($MatAreas as $Area)
		{
			if($FechaEval[$Area[1]])
			{
				$Tot+=$FechaEval[$Area[1]][2];
				?>                
                <td><? echo $FechaEval[$Area[1]][2];?></td>		            
                <?
            }
			else
			{
				?><td>&nbsp;</td><?	
			}
        }
		?>
        <td style="font-weight:bold"><? echo $Tot;?></td>
		</tr>    
	<?
    }?>	
<?
}
else
{
?>
<tr><td colspan="9" align="center">No existen registros!!!</td></tr>
<?
}
?>
</table>
</form>
</body>
