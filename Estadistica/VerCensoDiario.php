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
if($Anio!=''&&$Mes!='')
{	
	$first_of_month = mktime (0,0,0, $Mes, 1, $Anio); 
	$LastDay = date('t', $first_of_month);	?>
  	<table bordercolor="#e5e5e5" border="1"  cellpadding="1" cellspacing="1"style='font : normal normal small-caps 11px Tahoma;'>	    	
<?	if($Ambito){$Amb=" and ambito='$Ambito'";}
	$cons="select ambito,pabellon from salud.pabellones where compania='$Compania[0]' $Amb order by pabellon";
	$res=ExQuery($cons);	
	while($fila=ExFetch($res))
	{		
		$Pabellones[$fila[0]][$fila[1]]=$fila[1];
	}
	$cons="select unidad,dia,numpacientes,ambito from salud.censogeneral where compania='$Compania[0]' and dia>='$Anio-$Mes-1' and dia<='$Anio-$Mes-$LastDay' $Amb";
	$res=ExQuery($cons);
	while($fila=ExFetch($res))
	{
		$Censo[$fila[0]][trim($fila[1])][$fila[3]]=$fila[2];
		//echo "$fila[0] $fila[1] $fila[3]= $fila[2]<br>";
	}
	$cons="select ambito from salud.ambitos where compania='$Compania[0]' and hospitalizacion=1 and ambito!='Sin Ambito' $amb order by ambito";
	$res=ExQuery($cons);
	while($fila=ExFetch($res))
	{
		if($Pabellones[$fila[0]])
		{?>
            <tr bgcolor="#e5e5e5" style="font-weight:bold" align="center"><td colspan="60"><? echo $fila[0]?></td></tr>         
            <tr bgcolor="#e5e5e5" style="font-weight:bold" align="center"><td>Unidad</td><td colspan="59">Dia</td></tr>
            <tr bgcolor="#e5e5e5" style="font-weight:bold" align="center"><td>&nbsp;</td>      
            <?	for($i=1;$i<=$LastDay;$i++)
                {
                    echo "<td>$i</td>";
                }
        ?>	
            
    <?		foreach($Pabellones[$fila[0]] as $Pabs)
            {?>
                <tr>
                    <td align="center"><? echo $Pabs?></td><?
						for($i=1;$i<=$LastDay;$i++)
                		{
							if($i<10){$C1="0";}else{$C1="";}
							if($Mes<10){$C2="0";}else{$C2="";}
							$Fecha="$Anio-$C2$Mes-$C1$i";
							echo "<td>".$Censo[$Pabs][$Fecha][$fila[0]]."&nbsp;</td>";
                		}     ?>	                    
                </tr>	
        <?	}
		}
	}
	
	//for($i=1;$i<=$LastDay;$i++)
	//{
	
	?>
<?	
}?>
</body>
</html>
