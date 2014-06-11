<?
	if($DatNameSID){session_name("$DatNameSID");}
	session_start();
	include("Funciones.php");	
	function CalculaEdad($A,$M,$D,$AA,$MA,$DA)
	{		
		$Edad="";
		if($A!=""&&$M!=""&&$D!="")
		{							
			//alert("aa:"+AA+" MA:"+MA+" DA:"+DA+" A:"+A" M:"+M+" D:"+D);
			$Edad=$AA-$A;			
			if($MA==$M)
			{
				if($DA<$D)
				{
					$Edad=$Edad-1;
				}
			}
			else
			{				
				if($MA<$M)
				{					
					$Edad=$Edad-1;
				}
			}
			if($Edad>100){$Edad="";}			
		}
		return $Edad;
	}	
	$ND=getdate();		
	$cons="Select Codigo,Nombre,cup from odontologia.procedimientosimgs  where Compania='$Compania[0]' order by Nombre";
	$res=ExQuery($cons);
	while($fila=ExFetch($res))
	{
		$MatProcedimientos[$fila[0]]=array($fila[0],$fila[1],$fila[2]);	
		//echo $fila[0]." -> ".$fila[1]." -> ".$fila[2]."<br>"; 	
	}	
	//echo $Morbilidad;
	$cons="Select Fecha,Identificacion,Diente,Zonad,Procedimiento,TipoOdonto,diagnostico1,diagnostico2,diagnostico3,diagnostico4,diagnostico5 from Odontologia.OdontogramaProc
	where Compania='$Compania[0]' and Fecha>='$FechaIni' and Fecha<='$FechaFin' order by Fecha, Identificacion, Diente, ZonaD";
	$res=ExQuery($cons);
	//echo $cons;
	while($fila=ExFetch($res))
	{
		$MatTodosOdontograma[$fila[0]][$fila[1]][$fila[2]][$fila[3]][$fila[4]]=array($fila[0],$fila[1],$fila[2],$fila[3],$fila[4],$fila[5],$fila[6],$fila[7],$fila[8],$fila[9],$fila[10]);	
	}
	if($MatTodosOdontograma)
	{
		$cons="Select identificacion, fecnac, sexo from Central.Terceros where Compania='$Compania[0]' and Tipo='Paciente' order by Identificacion";
		$res=ExQuery($cons);
		while($fila=ExFetch($res))
		{
			$MatTerceros[$fila[0]]=array($fila[0],$fila[1],$fila[2]);	
		}	
	}	
	if($MatTodosOdontograma&&$MatTerceros)
	{
		foreach($MatTodosOdontograma as $Fecha)
		{
			foreach($Fecha as $Tercero)
			{
				foreach($Tercero as $Diente)
				{
					foreach($Diente as $ZonaDiente)
					{
						foreach($ZonaDiente as $Proced)
						{
							if($MatTerceros[$Proced[1]])
							{
							//	echo $MatTerceros[$ZonaDiente[1]][2]."<br>";
								$A=substr($MatTerceros[$Proced[1]][1],0,4); $M=substr($MatTerceros[$Proced[1]][1],5,2); $D=substr($MatTerceros[$Proced[1]][1],8,2);
								$Edad=CalculaEdad($A,$M,$D,$ND[year],$ND[mon],$ND[mday]);
								if($Edad>=0&&$Edad<=5){$TipoEdad=1;}
								if($Edad>=6&&$Edad<=14){$TipoEdad=2;}
								if($Edad>=15&&$Edad<=44){$TipoEdad=3;}
								if($Edad>=45&&$Edad<=59){$TipoEdad=4;}
								if($Edad>=60){$TipoEdad=5;}								
								if($Morbilidad)
								{
									if($Morbilidad==$Proced[4])
									{
										if($Proced[6])
										{
											if($MatDatosMor[$Morbilidad][$TipoEdad][$MatTerceros[$Proced[1]][2]])
											{
												$MatDatosMor[$Morbilidad][$TipoEdad][$MatTerceros[$Proced[1]][2]][4]++;
											}
											else
											{
												$MatDatosMor[$Morbilidad][$TipoEdad][$MatTerceros[$Proced[1]][2]]=array($Morbilidad,$MatProcedimientos[$Morbilidad][1],$TipoEdad,$MatTerceros[$Proced[1]][2],1);	
											}
											if($Proced[7])
											{
												if($MatDatosMor[$Morbilidad][$TipoEdad][$MatTerceros[$Proced[1]][2]])
												{
													$MatDatosMor[$Morbilidad][$TipoEdad][$MatTerceros[$Proced[1]][2]][4]++;
												}
												else
												{
													$MatDatosMor[$Morbilidad][$TipoEdad][$MatTerceros[$Proced[1]][2]]=array($Morbilidad,$MatProcedimientos[$Morbilidad][1],$TipoEdad,$MatTerceros[$Proced[1]][2],1);	
												}	
											}
											elseif($Proced[8])
											{
												if($MatDatosMor[$Morbilidad][$TipoEdad][$MatTerceros[$Proced[1]][2]])
												{
													$MatDatosMor[$Morbilidad][$TipoEdad][$MatTerceros[$Proced[1]][2]][4]++;
												}
												else
												{
													$MatDatosMor[$Morbilidad][$TipoEdad][$MatTerceros[$Proced[1]][2]]=array($Morbilidad,$MatProcedimientos[$Morbilidad][1],$TipoEdad,$MatTerceros[$Proced[1]][2],1);	
												}	
											}
											elseif($Proced[9])
											{
												if($MatDatosMor[$Morbilidad][$TipoEdad][$MatTerceros[$Proced[1]][2]])
												{
													$MatDatosMor[$Morbilidad][$TipoEdad][$MatTerceros[$Proced[1]][2]][4]++;
												}
												else
												{
													$MatDatosMor[$Morbilidad][$TipoEdad][$MatTerceros[$Proced[1]][2]]=array($Morbilidad,$MatProcedimientos[$Morbilidad][1],$TipoEdad,$MatTerceros[$Proced[1]][2],1);	
												}	
											}
											elseif($Proced[10])
											{
												if($MatDatosMor[$Morbilidad][$TipoEdad][$MatTerceros[$Proced[1]][2]])
												{
													$MatDatosMor[$Morbilidad][$TipoEdad][$MatTerceros[$Proced[1]][2]][4]++;
												}
												else
												{
													$MatDatosMor[$Morbilidad][$TipoEdad][$MatTerceros[$Proced[1]][2]]=array($Morbilidad,$MatProcedimientos[$Morbilidad][1],$TipoEdad,$MatTerceros[$Proced[1]][2],1);	
												}	
											}											
										}
									}
								}
								else
								{	
									foreach($MatProcedimientos as $CodP)
									{
										if($CodP[0]==$Proced[4])
										{
											if($Proced[6])
											{
												if($MatDatosMor[$CodP[0]][$TipoEdad][$MatTerceros[$Proced[1]][2]])
												{
													$MatDatosMor[$CodP[0]][$TipoEdad][$MatTerceros[$Proced[1]][2]][4]++;
												}
												else
												{
													$MatDatosMor[$CodP[0]][$TipoEdad][$MatTerceros[$Proced[1]][2]]=array($CodP[0],$MatProcedimientos[$CodP[0]][1],$TipoEdad,$MatTerceros[$Proced[1]][2],1);	
												}
												if($Proced[7])
												{
													if($MatDatosMor[$CodP[0]][$TipoEdad][$MatTerceros[$Proced[1]][2]])
													{
														$MatDatosMor[$CodP[0]][$TipoEdad][$MatTerceros[$Proced[1]][2]][4]++;
													}
													else
													{
														$MatDatosMor[$CodP[0]][$TipoEdad][$MatTerceros[$Proced[1]][2]]=array($CodP[0],$MatProcedimientos[$Morbilidad][1],$TipoEdad,$MatTerceros[$Proced[1]][2],1);	
													}	
												}
												elseif($Proced[8])
												{
													if($MatDatosMor[$CodP[0]][$TipoEdad][$MatTerceros[$Proced[1]][2]])
													{
														$MatDatosMor[$CodP[0]][$TipoEdad][$MatTerceros[$Proced[1]][2]][4]++;
													}
													else
													{
														$MatDatosMor[$CodP[0]][$TipoEdad][$MatTerceros[$Proced[1]][2]]=array($CodP[0],$MatProcedimientos[$Morbilidad][1],$TipoEdad,$MatTerceros[$Proced[1]][2],1);	
													}	
												}
												elseif($Proced[9])
												{
													if($MatDatosMor[$CodP[0]][$TipoEdad][$MatTerceros[$Proced[1]][2]])
													{
														$MatDatosMor[$CodP[0]][$TipoEdad][$MatTerceros[$Proced[1]][2]][4]++;
													}
													else
													{
														$MatDatosMor[$CodP[0]][$TipoEdad][$MatTerceros[$Proced[1]][2]]=array($CodP[0],$MatProcedimientos[$Morbilidad][1],$TipoEdad,$MatTerceros[$Proced[1]][2],1);	
													}	
												}
												elseif($Proced[10])
												{
													if($MatDatosMor[$CodP[0]][$TipoEdad][$MatTerceros[$Proced[1]][2]])
													{
														$MatDatosMor[$CodP[0]][$TipoEdad][$MatTerceros[$Proced[1]][2]][4]++;
													}
													else
													{
														$MatDatosMor[$CodP[0]][$TipoEdad][$MatTerceros[$Proced[1]][2]]=array($CodP[0],$MatProcedimientos[$Morbilidad][1],$TipoEdad,$MatTerceros[$Proced[1]][2],1);	
													}	
												}											
											}	
											break;
										}	
									}									
								}									
								//echo $ZonaDiente[0]." -> ".$ZonaDiente[1]." -> ".$ZonaDiente[2]." -> ".$ZonaDiente[3]." -> ".$ZonaDiente[4]." -> ".$ZonaDiente[5]." -> ".$ZonaDiente[6]." -> ".$MatTerceros[$ZonaDiente[1]][1]." -> ".$Edad." -> ".$MatTerceros[$ZonaDiente[1]][2]."<br>";
								//echo $ZonaDiente[6]."<br>";
							}
						}
					}	
				}	
			}	
		}			
	}	
?>
<head>
</head>
<body background="/Imgs/Fondo.jpg">
<table align="center" border="1" cellspacing="1" bordercolor="#e5e5e5" style='font : normal normal small-caps 13px Tahoma;'>
<tr align="center" >
    <td  bgcolor="#e5e5e5" style="font-weight:bold" colspan="3">Informe Morbilidad</td>
</tr>
<?
if($MatProcedimientos&&$MatDatosMor)
{
	foreach($MatProcedimientos as $CodigoProd)
	{		
		if($MatDatosMor[$CodigoProd[0]])
		{			
			?>
            <tr bgcolor="#e5e5e5" style="font-weight:bold">
            <td align="center" colspan="3" ><? echo $TipoColor[1]?></td>
            </tr>
            <tr bgcolor="#e5e5e5" style="font-weight:bold">
            <td>Edad Población</td><td>Género</td><td>Cantidad</td>
            </tr>
            <?
			$cont=0;
            foreach($MatDatosMor[$CodigoProd[0]] as $TipEda)
			{
				foreach($TipEda as $Sexo)
				{
					//echo $Sexo[0]." -> ".$Sexo[1]." -> ".$Sexo[2]." -> ".$Sexo[3]." -> ".$Sexo[4]."";
					if($Sexo[2]==1){$Poblacion="De 0 a 5 Años";}
					if($Sexo[2]==2){$Poblacion="De 6 a 14 Años";}
					if($Sexo[2]==3){$Poblacion="De 15 a 44 Años";}
					if($Sexo[2]==4){$Poblacion="De 45 a 59 Años";}
					if($Sexo[2]==5){$Poblacion="De 60 en Adelante";}
					$cont=$cont+$Sexo[4];
					?>
					<tr>
                    <td><? echo $Poblacion?></td>
                    <td align="center"><? echo $Sexo[3]?></td>
                    <td align="center"><? echo $Sexo[4]?></td>
                    </tr>
					<?
				}	
			}
			?>
            <tr >
            	<td colspan="2" align="right" bgcolor="#e5e5e5" style="font-weight:bold">Total</td>
                <td align="center"><? echo $cont?></td>
            </tr>	
            <?
		}	
	}
}
else
{?>
<tr>
<td>No se Encontrarón Coincidencias</td>
</tr>		
<?
}?>
</table>
</body>