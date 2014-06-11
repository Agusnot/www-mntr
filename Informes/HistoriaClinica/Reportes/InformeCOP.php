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
	$cons="Select color, ncolor, ruta from odontologia.colorconvenciones  where Compania='$Compania[0]' order by ncolor";
	$res=ExQuery($cons);
	while($fila=ExFetch($res))
	{
		$MatColores[$fila[0]]=array($fila[0],$fila[1],$fila[2]);	
		//echo $fila[0]." -> ".$fila[1]." -> ".$fila[2]."<br>"; 	
	}	
	//echo $COP;
	$cons="Select Fecha,Identificacion,Diente,Zonad,TipoOdonto,Denticion,ImagenZona from Odontologia.OdontogramaProc
	where Compania='$Compania[0]' and Fecha>='$FechaIni' and Fecha<='$FechaFin' order by Fecha, Identificacion, Diente, ZonaD";
	$res=ExQuery($cons);
	//echo $cons;
	while($fila=ExFetch($res))
	{
		$MatTodosOdontograma[$fila[0]][$fila[1]][$fila[2]][$fila[3]]=array($fila[0],$fila[1],$fila[2],$fila[3],$fila[4],$fila[5],$fila[6]);	
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
						if($MatTerceros[$ZonaDiente[1]])
						{
						//	echo $MatTerceros[$ZonaDiente[1]][2]."<br>";
							$A=substr($MatTerceros[$ZonaDiente[1]][1],0,4); $M=substr($MatTerceros[$ZonaDiente[1]][1],5,2); $D=substr($MatTerceros[$ZonaDiente[1]][1],8,2);
							$Edad=CalculaEdad($A,$M,$D,$ND[year],$ND[mon],$ND[mday]);
							if($Edad>=0&&$Edad<=5){$TipoEdad=1;}
							if($Edad>=6&&$Edad<=14){$TipoEdad=2;}
							if($Edad>=15&&$Edad<=44){$TipoEdad=3;}
							if($Edad>=45&&$Edad<=59){$TipoEdad=4;}
							if($Edad>=60){$TipoEdad=5;}
							$Colores="";
							if($ZonaDiente[6]=="/Imgs/Odontologia/D3.gif"||$ZonaDiente[6]=="/Imgs/Odontologia/D1.gif"||$ZonaDiente[6]=="/Imgs/Odontologia/D2.gif"||$ZonaDiente[6]=="/Imgs/Odontologia/D4.gif"||$ZonaDiente[6]=="/Imgs/Odontologia/D5.gif"||$ZonaDiente[6]=="")
							{								
								$Colores="#FFFFFF";		
								if($COP)
								{
									if($Colores==$COP)
									{
										if($MatDatosCOP[$COP][$TipoEdad][$MatTerceros[$ZonaDiente[1]][2]])
										{
											$MatDatosCOP[$COP][$TipoEdad][$MatTerceros[$ZonaDiente[1]][2]][4]++;
										}
										else
										{
											$MatDatosCOP[$COP][$TipoEdad][$MatTerceros[$ZonaDiente[1]][2]]=array($COP,$MatColores[$COP][1],$TipoEdad,$MatTerceros[$ZonaDiente[1]][2],1);	
										}
									}
								}
								else
								{	
									if($MatDatosCOP[$Colores][$TipoEdad][$MatTerceros[$ZonaDiente[1]][2]])
									{
										$MatDatosCOP[$Colores][$TipoEdad][$MatTerceros[$ZonaDiente[1]][2]][4]++;
									}
									else
									{
										$MatDatosCOP[$Colores][$TipoEdad][$MatTerceros[$ZonaDiente[1]][2]]=array($Colores,$MatColores[$Colores][1],$TipoEdad,$MatTerceros[$ZonaDiente[1]][2],1);	
									}
								}
							}
							else
							{
								if($ZonaDiente[6]=="/Imgs/Odontologia/DAC3.GIF"||$ZonaDiente[6]=="/Imgs/Odontologia/DAC1.GIF"||$ZonaDiente[6]=="/Imgs/Odontologia/DAC2.GIF"||$ZonaDiente[6]=="/Imgs/Odontologia/DAC4.GIF"||$ZonaDiente[6]=="/Imgs/Odontologia/DAC5.GIF")
								{
									$Colores="#2A9FFF";
									if($COP)
									{
										if($Colores==$COP)
										{
											if($MatDatosCOP[$COP][$TipoEdad][$MatTerceros[$ZonaDiente[1]][2]])
											{
												$MatDatosCOP[$COP][$TipoEdad][$MatTerceros[$ZonaDiente[1]][2]][4]++;
											}
											else
											{
												$MatDatosCOP[$COP][$TipoEdad][$MatTerceros[$ZonaDiente[1]][2]]=array($COP,$MatColores[$COP][1],$TipoEdad,$MatTerceros[$ZonaDiente[1]][2],1);	
											}
										}
									}
									else
									{
										if($MatDatosCOP[$Colores][$TipoEdad][$MatTerceros[$ZonaDiente[1]][2]])
										{
											$MatDatosCOP[$Colores][$TipoEdad][$MatTerceros[$ZonaDiente[1]][2]][4]++;
										}
										else
										{
											$MatDatosCOP[$Colores][$TipoEdad][$MatTerceros[$ZonaDiente[1]][2]]=array($Colores,$MatColores[$Colores][1],$TipoEdad,$MatTerceros[$ZonaDiente[1]][2],1);	
										}
									}
								}
								else
								{
									if($ZonaDiente[6]=="/Imgs/Odontologia/DV3.GIF"||$ZonaDiente[6]=="/Imgs/Odontologia/DV1.GIF"||$ZonaDiente[6]=="/Imgs/Odontologia/DV2.GIF"||$ZonaDiente[6]=="/Imgs/Odontologia/DV4.GIF"||$ZonaDiente[6]=="/Imgs/Odontologia/DV5.GIF")
									{
										$Colores="#2ADF00";
										if($COP)
										{											
											if($Colores==$COP)
											{
												if($MatDatosCOP[$COP][$TipoEdad][$MatTerceros[$ZonaDiente[1]][2]])
												{
													$MatDatosCOP[$COP][$TipoEdad][$MatTerceros[$ZonaDiente[1]][2]][4]++;
												}
												else
												{
													$MatDatosCOP[$COP][$TipoEdad][$MatTerceros[$ZonaDiente[1]][2]]=array($COP,$MatColores[$COP][1],$TipoEdad,$MatTerceros[$ZonaDiente[1]][2],1);	
												}
											}
										}
										else
										{											
											if($MatDatosCOP[$Colores][$TipoEdad][$MatTerceros[$ZonaDiente[1]][2]])
											{
												$MatDatosCOP[$Colores][$TipoEdad][$MatTerceros[$ZonaDiente[1]][2]][4]++;
											}
											else
											{
												$MatDatosCOP[$Colores][$TipoEdad][$MatTerceros[$ZonaDiente[1]][2]]=array($Colores,$MatColores[$Colores][1],$TipoEdad,$MatTerceros[$ZonaDiente[1]][2],1);	
											}
										}
									}
									else
									{
										if($ZonaDiente[6]=="/Imgs/Odontologia/DR3.GIF"||$ZonaDiente[6]=="/Imgs/Odontologia/DR1.GIF"||$ZonaDiente[6]=="/Imgs/Odontologia/DR2.GIF"||$ZonaDiente[6]=="/Imgs/Odontologia/DR4.GIF"||$ZonaDiente[6]=="/Imgs/Odontologia/DR5.GIF")
										{
											$Colores="#FF0000";
											if($COP)
											{
												if($Colores==$COP)
												{
													if($MatDatosCOP[$COP][$TipoEdad][$MatTerceros[$ZonaDiente[1]][2]])
													{
														$MatDatosCOP[$COP][$TipoEdad][$MatTerceros[$ZonaDiente[1]][2]][4]++;
													}
													else
													{
														$MatDatosCOP[$COP][$TipoEdad][$MatTerceros[$ZonaDiente[1]][2]]=array($COP,$MatColores[$COP][1],$TipoEdad,$MatTerceros[$ZonaDiente[1]][2],1);	
													}
												}
											}
											else
											{
												if($MatDatosCOP[$Colores][$TipoEdad][$MatTerceros[$ZonaDiente[1]][2]])
												{
													$MatDatosCOP[$Colores][$TipoEdad][$MatTerceros[$ZonaDiente[1]][2]][4]++;
												}
												else
												{
													$MatDatosCOP[$Colores][$TipoEdad][$MatTerceros[$ZonaDiente[1]][2]]=array($Colores,$MatColores[$Colores][1],$TipoEdad,$MatTerceros[$ZonaDiente[1]][2],1);	
												}
											}
										}	
										else
										{
											if($ZonaDiente[6]=="/Imgs/Odontologia/DAO3.GIF"||$ZonaDiente[6]=="/Imgs/Odontologia/DAO1.GIF"||$ZonaDiente[6]=="/Imgs/Odontologia/DAO2.GIF"||$ZonaDiente[6]=="/Imgs/Odontologia/DAO4.GIF"||$ZonaDiente[6]=="/Imgs/Odontologia/DAO5.GIF")
											{
												$Colores="#0000FF";
												if($COP)
												{
													if($Colores==$COP)
													{
														if($MatDatosCOP[$COP][$TipoEdad][$MatTerceros[$ZonaDiente[1]][2]])
														{
															$MatDatosCOP[$COP][$TipoEdad][$MatTerceros[$ZonaDiente[1]][2]][4]++;
														}
														else
														{
															$MatDatosCOP[$COP][$TipoEdad][$MatTerceros[$ZonaDiente[1]][2]]=array($COP,$MatColores[$COP][1],$TipoEdad,$MatTerceros[$ZonaDiente[1]][2],1);	
														}
													}
												}
												else
												{
													if($MatDatosCOP[$Colores][$TipoEdad][$MatTerceros[$ZonaDiente[1]][2]])
													{
														$MatDatosCOP[$Colores][$TipoEdad][$MatTerceros[$ZonaDiente[1]][2]][4]++;
													}
													else
													{
														$MatDatosCOP[$Colores][$TipoEdad][$MatTerceros[$ZonaDiente[1]][2]]=array($Colores,$MatColores[$Colores][1],$TipoEdad,$MatTerceros[$ZonaDiente[1]][2],1);	
													}
												}
											}	
											else
											{
												if($ZonaDiente[6]=="/Imgs/Odontologia/DN3.GIF"||$ZonaDiente[6]=="/Imgs/Odontologia/DN1.GIF"||$ZonaDiente[6]=="/Imgs/Odontologia/DN2.GIF"||$ZonaDiente[6]=="/Imgs/Odontologia/DN4.GIF"||$ZonaDiente[6]=="/Imgs/Odontologia/DN5.GIF")
												{
													$Colores="#000000";
													if($COP)
													{
														if($Colores==$COP)
														{
															if($MatDatosCOP[$COP][$TipoEdad][$MatTerceros[$ZonaDiente[1]][2]])
															{
																$MatDatosCOP[$COP][$TipoEdad][$MatTerceros[$ZonaDiente[1]][2]][4]++;
															}
															else
															{
																$MatDatosCOP[$COP][$TipoEdad][$MatTerceros[$ZonaDiente[1]][2]]=array($COP,$MatColores[$COP][1],$TipoEdad,$MatTerceros[$ZonaDiente[1]][2],1);	
															}
														}
													}
													else
													{
														if($MatDatosCOP[$Colores][$TipoEdad][$MatTerceros[$ZonaDiente[1]][2]])
														{
															$MatDatosCOP[$Colores][$TipoEdad][$MatTerceros[$ZonaDiente[1]][2]][4]++;
														}
														else
														{
															$MatDatosCOP[$Colores][$TipoEdad][$MatTerceros[$ZonaDiente[1]][2]]=array($Colores,$MatColores[$Colores][1],$TipoEdad,$MatTerceros[$ZonaDiente[1]][2],1);	
														}
													}
												}	
											}
										}
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
?>
<head>
</head>
<body background="/Imgs/Fondo.jpg">
<table align="center" border="1" cellspacing="1" bordercolor="#e5e5e5" style='font : normal normal small-caps 13px Tahoma;'>
<tr align="center" >
    <td  bgcolor="#e5e5e5" style="font-weight:bold" colspan="3">Informe COP</td>
</tr>
<?
if($MatColores&&$MatDatosCOP)
{
	foreach($MatColores as $TipoColor)
	{		
		if($MatDatosCOP[$TipoColor[0]])
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
            foreach($MatDatosCOP[$TipoColor[0]] as $TipEda)
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