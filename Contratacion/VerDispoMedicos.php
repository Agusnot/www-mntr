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
		$cons="Select fecha,horaini,minsinicio,horasfin,minsfin,idhorario,citaspermitidas 
		from Salud.DispoConsExterna where Usuario='$Medico' and Compania='$Compania[0]' and Fecha='$Anio-$Mes-$Dia' 
		order by idhorario";
		
		$res=ExQuery($cons);
		$DiaSemana=CalcDia($Dia);
		if($Dia<10){$Cero='0';}else{$Cero='';};
		if(ExNumRows($res)>0)
		{	?>
			<tr onMouseOver="this.bgColor='#AAD4FF'" onMouseOut="this.bgColor=''"><td bgcolor="#e5e5e5" style="font-weight:bold" align="center"><? echo $Cero.$Dia." ".$DiaSemana?></td><td>
                <?php
                        $motivo="";
                        $usuario_motivo="";
			while($fila=ExFetch($res)){
                                if($fila[2]==0){
                                    $Cero2=0;
                                }else{
                                    $Cero2='';
                                }
				if($fila[4]==0){
                                    $Cero3=0;
                                }else{
                                    $Cero3='';
                                }
                                
                                $consBlock = "select * from salud.bloqconsexterna where compania_bloqconsexterna='$Compania[0]' and medico_bloqconsexterna='$Medico' and fechaini_bloqconsexterna<='$fila[0] $fila[1]:$fila[2]$Cero2:00' and fechafin_bloqconsexterna>='$fila[0] $fila[1]:$fila[2]$Cero2:00'";
                                $resBlock = ExQuery($consBlock);
                                
                                $color="";
                                if(ExNumRows($resBlock)>0){
                                    $color = "color:red;";
                                    $filaBlock=ExFetchAssoc($resBlock);
                                    $motivo=$filaBlock['motivo_bloqconsexterna'];
                                    $usuario_motivo=$filaBlock['usuario_bloqconsexterna'];
                                }
                                
                                //$filaBlock = ExFetchAssoc($resBlock);
                                
				echo "<span style='$color'>$fila[1]:$fila[2]$Cero2-$fila[3]:$fila[4]$Cero3; </span>";
			}
			echo "</td>";
                        echo "<td>".$motivo."</td>";
                        echo "<td>".$usuario_motivo."</td>";
                ?>
			<td><img title="Editar" src="/Imgs/b_edit.png" style="cursor:hand" onClick="parent.location.href='NewDiaDispMed.php?DatNameSID=<? echo $DatNameSID?>&Edit=1&Anio=<? echo $Anio?>&Mes=<? echo $Mes?>&Dia=<? echo $Dia?>&Medico=<? echo $Medico?>'">
		<?php	echo "</td></tr>";
		}
		else
		{
                    $consBlock = "select * from salud.bloqconsexterna where compania_bloqconsexterna='$Compania[0]' and medico_bloqconsexterna='$Medico' and fechaini_bloqconsexterna<='$Anio-$Mes-$Dia 23:59:59' and fechafin_bloqconsexterna>='$Anio-$Mes-$Dia 00:00:00'";
                    $resBlock = ExQuery($consBlock);
                    $filaBlock = ExFetchAssoc($resBlock);
                    
                    $motivo="";
                    if(ExNumRows($resBlock)>0){
                        $motivo = $filaBlock['motivo_bloqconsexterna'];  
                    }
                    else{
                        $motivo = "SIN CITAS ASIGNADAS";
                    }
                    ?>
			
			<tr onMouseOver="this.bgColor='#AAD4FF'" onMouseOut="this.bgColor=''"><td bgcolor='#e5e5e5' style='font-weight:bold' align='center' ><? echo $Cero.$Dia." ".$DiaSemana?></td><td>-- No Definido --</td>
                <?php	
                        echo "<td>$motivo</td><td></td>";?>
                        <td>
			<img title="Editar" src="/Imgs/b_edit.png" style="cursor:hand" onClick="parent.location.href='NewDiaDispMed.php?DatNameSID=<? echo $DatNameSID?>&Edit=1&Anio=<? echo $Anio?>&Mes=<? echo $Mes?>&Dia=<? echo $Dia?>&Medico=<? echo $Medico?>'">
		<?php
                        echo "</td></tr>";
		}
	}
?>
</table>
</body>
</html>
