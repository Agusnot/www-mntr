<?
	if($DatNameSID){session_name("$DatNameSID");}
	session_start();
	ini_set("memory_limit","512M");
	include("Informes.php");	
	require('LibPDF/fpdf.php');
	$ND=getdate();
	if($MesIni<10){$MesIni="0".$MesIni;}
	if($MesFin<10){$MesFin="0".$MesFin;}
	$DiaFFin=UltimoDia($Anio,$MesFin);
	if($DiaFFin<10){$DiaFFin="0".$DiaFFin;}	
	$cons="Select Numero,Mes from Central.Meses";
	$res=ExQuery($cons);
	while($fila=ExFetch($res))
	{
		$Meses[$fila[0]]=array($fila[0],$fila[1]);
	}
	if($Entidad){$OpcionEnt="and Entidad='$Entidad'";}
	if($Contrato){$OpcionCont="and Contrato='$Contrato'";}
	if($NumContrato){$OpcionNumCont="and NoContrato='$NumContrato'";}
	if($CedPaciente){$OpcPac="and Identificacion='$CedPaciente'";}
	$cons="Select  entidad, contrato, nocontrato, identificacion, fecha, hora, codigo, nombre,
	cantidad, usuario from Salud.registromedicamentostmp where Fecha>='$Anio-$MesIni-$DiaIni 00:00:00' 
	and Fecha<='$Anio-$MesFin-$DiaFin 23:59:59' $OpcionEnt $OpcionCont $OpcionNumCont $OpcPac
	order by Entidad,Contrato,NoContrato,Identificacion,Codigo,Nombre,Fecha,Hora";
	$res=ExQuery($cons);
	while($fila=ExFetch($res))
	{
		$MatMedicamentos[$fila[0]][$fila[1]][$fila[2]][$fila[3]][$fila[4]][$fila[5]][$fila[6]]=array($fila[0],$fila[1],$fila[2],$fila[3],$fila[4],$fila[5],$fila[6],$fila[7],$fila[8],$fila[9]);	
	}
	$cons="Select Identificacion,PrimApe from central.terceros where Compania='$Compania[0]' and tipo='Asegurador' 
	group by Identificacion,PrimApe order by Identificacion";
	$res=ExQuery($cons);
	//echo $cons;
	while($fila=ExFetch($res))
	{		
		$MatEntidades[$fila[0]]=array($fila[0],$fila[1]);
	}
	$cons="Select Identificacion,(PrimApe || ' ' || SegApe || ' ' || PrimNom || ' ' || SegNom) as Paciente from central.terceros 
	where Compania='$Compania[0]' and tipo='Paciente' group by Identificacion, PrimApe, SegApe, PrimNom,SegNom  
	order by PrimApe, SegApe, PrimNom,SegNom";
	$res=ExQuery($cons);
	//echo $cons;
	while($fila=ExFetch($res))
	{		
		$MatPacientes[$fila[0]]=array($fila[0],$fila[1]);
	}
	
if(!$PDF)
{
?>
<head></head>
<body background="/Imgs/Fondo.jpg">
<?
}
$cont=0; $tab=0; $AmbitoAnt="";
if($MatMedicamentos)
{	
	if(!$PDF)
	{?>
	<table border='1' bordercolor='#e5e5e5' style='font : normal normal small-caps 10px Tahoma;' align="center">    
    <?
	}
	foreach($MatEntidades as $Ent)
	{		
		if($MatMedicamentos[$Ent[0]])
		{
			//echo "$Ent[0] --> $Ent[1] sd<br>";	
			if($Ent[0]=="D891280001-0-NaN"||$Ent[0]=="I891280001-0"){$NitE="891280001-0";
;}else{$NitE=$Ent[0];}
			?>
			<tr bgcolor='#e5e5e5' style='font-weight:bold; font-size:14px' align='center'>
            <td colspan="9"><? echo utf8_decode_seguro("ENTIDAD RESPONSABLE DEL PAGO: $Ent[1] - $NitE");?></td>
            </tr>
			<?
			$pri=1;
			foreach($MatMedicamentos[$Ent[0]] as $Cont)
			{
				foreach($Cont as $NoCont)
				{
					foreach($MatPacientes as $Paciente)
					{
						if($NoCont[$Paciente[0]])
						{
							?>
                            <tr bgcolor='#e5e5e5' style='font-weight:bold; font-size:12px' align='center'>
                            <td colspan="9"><? echo utf8_decode_seguro("PACIENTE: $Paciente[1] - $Paciente[0]");?></td>
                            </tr>
                            <tr bgcolor='#e5e5e5' style='font-weight:bold;' align='center'>
                            <td>Fecha</td><td>Hora</td><td>Codigo</td><td>Medicamento</td><td>Cantidad</td><td>Quien Suministra</td>
                            </tr>
                            <?
							$cm="";$nm="";$rp=0;$pri=1;
							foreach($NoCont[$Paciente[0]] as $FechaM)
							{
								foreach($FechaM as $HoraM)
								{
									foreach($HoraM as $CodM)
									{									
										if($pri){$pri="";$cm=$CodM[6];$nm=$CodM[7];$rp++;;}
										if($cm==$CodM[6])
										{								
											$submedxpac=$submedxpac+$CodM[8];											
										}
										else
										{											
											?>
											<tr bgcolor='#e5e5e5' style='font-weight:bold;'>
                                            <td colspan="3" align="right" >SUBTOTALES</td>
                                            <td><? echo utf8_decode_seguro($nm);?></td>
                                            <td align="center"><? echo $submedxpac?></td>
                                            <td>&nbsp;</td>
                                            </tr>
											<?												
											$submedxpac=0;	
											$cm=$CodM[6];$nm=$CodM[7];	
											$submedxpac=$submedxpac+$CodM[8];									
										}
										?>
                                        <tr>
			                            <td align="center"><? echo substr($CodM[4],0,10);?></td>
                                        <td align="center"><? echo $CodM[5]?></td>
                                        <td align="center"><? echo $CodM[6]?></td>
                                        <td><? echo utf8_decode_seguro($CodM[7]);?></td>
                                        <td align="center"><? echo $CodM[8]?></td>
                                        <td><? echo utf8_decode_seguro($CodM[9]);?></td>
            			                </tr>
                                        <?	
										$MatTotMed[$CodM[6]][0]=$CodM[6];
										$MatTotMed[$CodM[6]][1]=$CodM[7];
										$MatTotMed[$CodM[6]][2]+=$CodM[8];									
									}									                                    
								}	
							}
							?>
                            <tr bgcolor='#e5e5e5' style='font-weight:bold;'>
                            <td colspan="3" align="right" >SUBTOTALES</td>
                            <td><? echo utf8_decode_seguro($nm);?></td>
                            <td align="center"><? echo $submedxpac?></td>
                            <td>&nbsp;</td>
                            </tr>
                            <?												
                            $submedxpac=0;		
						}	
					}	
				}	
			}			
		}	
	}	
	if(!$PDF)
	{			
	?>
    </table>
    <?
	if($MatTotMed)
	{
		?>
        <br>
        <table border='1' bordercolor='#e5e5e5' style='font : normal normal small-caps 10px Tahoma;' align="center">   
        <tr bgcolor='#e5e5e5' style='font-weight:bold;'><td>Codigo</td><td>Medicamento</td><td>Total</td></tr>
        <?
        foreach($MatTotMed as $CM)
		{
			?>
			<tr>
            <td align="right"><? echo $CM[0]?></td>
            <td><? echo utf8_decode_seguro($CM[1]);?></td>
            <td align="right"><? echo $CM[2]?></td>
            </tr>
			<?	
		}
		?>
        </table>
        <?	
	}
	}
}
else
{
	echo"<center>No se Encontraron Coincidencias</center>";	
}
    