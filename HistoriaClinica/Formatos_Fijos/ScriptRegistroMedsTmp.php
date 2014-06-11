<?
	if($DatNameSID){session_name("$DatNameSID");}
	session_start();
	include("Funciones.php");
	$cons="select grupo,grupofact from consumo.grupos where compania='Hospital San Rafael de Pasto'";
	$res=ExQuery($cons);
	while($fila=ExFetch($res))
	{
		$GruposMeds[$fila[0]]=$fila[1];
	}
	$cons="Select codigo1,grupo from consumo.codproductos where Compania='Hospital San Rafael de Pasto' and grupo='Medicamentos' order by codigo1";
	$res=ExQuery($cons);
	//echo $cons;
	while($fila=ExFetch($res))
	{
		$MatMedicamentosSDM[$fila[0]]=$fila[1];
	}	
	//----ASIGNAR FECHAS PARA INFORME------------
	$FechaIni="2011-09-01";
	$FechaFin="2011-09-30";
	//-------------------------------------------
	$cons="select cedula,Entidad,FacturasCredito.Contrato,FacturasCredito.NoContrato,FacturasCredito.NoFactura,numservicio,
	codigo,nombre,sum(cantidad) from facturacion.detallefactura,facturacion.facturascredito,facturacion.liquidacion where 
	facturascredito.compania='Hospital San Rafael de Pasto' and detallefactura.Compania=facturascredito.Compania and 
	detallefactura.Compania=liquidacion.compania and detallefactura.nofactura=facturascredito.nofactura
	and detallefactura.nofactura=liquidacion.nofactura and tipo='Medicamentos' and facturascredito.fechaini>='$FechaIni' 
	and facturascredito.fechaini<='$FechaFin' and facturascredito.fechafin<='$FechaFin' 
	and facturascredito.fechafin>='$FechaIni' group by 
	cedula,Entidad,FacturasCredito.Contrato,FacturasCredito.NoContrato,FacturasCredito.NoFactura,numservicio,
	codigo,Nombre order by cedula,Entidad,FacturasCredito.Contrato,FacturasCredito.NoContrato,FacturasCredito.NoFactura,numservicio,codigo,Nombre";		
	$res=ExQuery($cons);	
	//echo "$cons<br>";//exit;
	while($fila=ExFetch($res))
	{
		if($MatMedicamentosSDM[$fila[6]])
		{
			$MatMedicamentos[$fila[0]][$fila[1]][$fila[2]][$fila[3]][$fila[4]][$fila[5]][$fila[6]]=array($fila[0],$fila[1],$fila[2],$fila[3],$fila[4],$fila[5],$fila[6],$fila[7],$fila[8]);					
		}
	}	
	/*$cont=0;
	foreach($MatMedicamentos as $Ced)	
	{
		foreach($Ced as $Ent)
		{
			foreach($Ent as $Cont)
			{
				foreach($Cont as $NCont)
				{
					foreach($NCont as $NFac)
					{
						foreach($NFac as $CodM)
						{
							$cont++;
							echo "$cont --> $CodM[0] --> $CodM[1]  --> $CodM[2] --> $CodM[3] --> $CodM[4] --> $CodM[5] --> $CodM[6] --> $CodM[7]<br>";		
						}
					}
				}	
			}
		
			
		}
	}
	exit;*/
	//----ASIGNAR AÑO Y MES FECHA
	$AnioMes=substr($FechaIni,0,7);
	//echo "<br>".$AnioMes;exit;
	if($MatMedicamentos)
	{
		$cons="Select cedula, numservicio,fechaIng,fechaEgr from salud.servicios where Compania='Hospital San Rafael de Pasto'
		order by cedula,fechaing,fechaegr";
		$res=ExQuery($cons);
		echo "$cons <br>";//exit;
		while($fila=ExFetch($res))
		{
			if(!$fila[3]){$fila[3]="$FechaFin 00:00:00";}
			if($fila[2]<"$FechaIni 00:00:00"){$fila[2]="$FechaIni 00:00:00";}	
			if($fila[3]>"$FechaFin 23:59:59"){$fila[3]="$FechaFin 00:00:00";}		
			$MatFechaServ[$fila[0]][$fila[1]][$fila[2]]=array($fila[0],$fila[1],$fila[2],$fila[3]);
		}
		/*$cont=0;
		foreach($MatFechaServ as $Cedula)
		{
			foreach($Cedula as $NumServ)
			{
				foreach($NumServ as $FechaIn)
				{
					$cont++;
					echo "$cont --> $FechaIn[0] --> $FechaIn[1] --> $FechaIn[2] --> $FechaIn[3]<br><br>";	
					
				}	
			}	
		}exit;*/
		$cons="Select paciente,autoid,tipo,fecha,hora,cantidad,idescritura,numorden from Salud.HoraCantidadxMedicamento where 
		Compania='Hospital San Rafael de Pasto' order by paciente,autoid,tipo,fecha,hora,cantidad";
		//echo "$cons <br>";//exit;
		$res=ExQuery($cons);
		while($fila=ExFetch($res))
		{
			$MatHoraCantMedicamentos[$fila[0]][$fila[1]][$fila[2]][$fila[3]][$fila[4]][$fila[5]]=array($fila[0],$fila[1],$fila[2],$fila[3],$fila[4],$fila[5],$fila[6],$fila[7]);	
		}		
		$cons="Select medicos.usuario,Nombre from salud.medicos,central.usuarios where Medicos.Compania='Hospital San Rafael de Pasto' 
		and medicos.usuario=usuarios.usuario and cargo='Aux Enfermeria'";
		$res=ExQuery($cons);
		//echo "$cons <br>";
		$n=0;$ini=0;$fin=0;
		while($fila=ExFetch($res))
		{		
			if($fila[0]!="jacamon"&&$fila[0]!="carlosjdjc")
			{
				$n++;	
				if($ini==0){$ini=1;}
				if($fin<$n){$fin=$n;}			
				$MatUsuarios[$n]=array($fila[0],$fila[1]);			
			}
		}
		/*
		$cont=0;
		foreach($MatUsuarios as $Usu)
		{
			$cont++;
			echo "$cont --> $Usu[0] --> $Usu[1]<br>";	
		}*/	
		//----proceso
		$cont=0;$cantxmed=0;
		foreach($MatMedicamentos as $Identificacion)
		{
			foreach($Identificacion as $Entidad)
			{			
				foreach($Entidad as $Contrato)
				{
					foreach($Contrato as $NumContrato)
					{
						foreach($NumContrato as $NumFactura)
						{
							foreach($NumFactura as $NumServ)
							{
								foreach($NumServ as $CodM)
								{
									/*$cont++;
									echo "$cont --> $CodM[0] --> $CodM[1]  --> $CodM[2] --> $CodM[3] --> $CodM[4] --> $CodM[5] --> $CodM[6] --> $CodM[7] --> $CodM[8]<br>";*/
									if($MatFechaServ[$CodM[0]][$CodM[5]])
									{
										foreach($MatFechaServ[$CodM[0]][$CodM[5]] as $FechaIn)
										{
											/*$cont++;
											echo "$cont --> $CodM[0] --> $CodM[1]  --> $CodM[2] --> $CodM[3] --> $CodM[4] --> $CodM[5] --> $CodM[6] --> $CodM[7] --> $CodM[8] --> $FechaIn[2] --> $FechaIn[3]<br>";	*/
											$DiaI=substr($FechaIn[2],8,2);
											$DiaF=substr($FechaIn[3],8,2);
											//echo "<br>$DiaI --> $DiaF<br>";
											$Cantidad=0; $sale="";											
											for($i=round($DiaI);$i<=round($DiaF);$i++)
											{
												if($MatHoraCantMedicamentos[$CodM[0]][$CodM[6]])
												{
													if($sale)
													{
														break;	
													}
													foreach($MatHoraCantMedicamentos[$CodM[0]][$CodM[6]] as $Tipo)
													{
														if($sale)
														{
															break;	
														}
														foreach($Tipo as $FechaHor)
														{
															if($sale)
															{
																break;	
															}
															foreach($FechaHor as $Hor)
															{
																if($sale)
																{
																	break;	
																}
																foreach($Hor as $CantxHora)
																{
																	if($CantxHora[5]<=$CodM[8])
																	{
																		$hh=rand(0,23);$mm=rand(0,59);$ss=rand(0,59);
																		if($hh<10){$hh="0".$hh;}
																		if($mm<10){$mm="0".$mm;}
																		if($ss<10){$ss="0".$ss;}
																		$Cantidad+=$CantxHora[5];												
																		if($Cantidad<$CodM[8])
																		{
																			if($i<10){$ii="0$i";}else{$ii=$i;}																		
																			$us=rand($ini,$fin);
																			$cont++;
																			echo "$cont --> $CodM[1] --> $CodM[2]  --> $CodM[3] --> $CodM[0] --> $AnioMes-$ii $hh:$mm:$ss --> $CantxHora[4] --> $CodM[6] --> $CodM[7] --> $CantxHora[5] --> ".$MatUsuarios[$us][1]." --> $CodM[8]<br>";
																			$cons="Insert into salud.registromedicamentostmp (entidad, contrato,
																			nocontrato, identificacion, fecha, hora, codigo, nombre, cantidad,
																			usuario) values('$CodM[1]','$CodM[2]','$CodM[3]','$CodM[0]',
																			'$AnioMes-$ii $hh:$mm:$ss','$CantxHora[4]',$CodM[6],'$CodM[7]',
																			$CantxHora[5],'".$MatUsuarios[$us][1]."')";
																			$res=ExQuery($cons);
																		}
																		else
																		{
																			if($Cantidad<$CodM[8])
																			{
																				$Cantidad=$CodM[8]-$Cantidad;
																				if($i<10){$ii="0$i";}else{$ii=$i;}																		
																				$us=rand($ini,$fin);
																				$cont++;
																				echo "$cont --> $CodM[1] --> $CodM[2]  --> $CodM[3] --> $CodM[0] --> $AnioMes-$ii $hh:$mm:$ss --> $CantxHora[4] --> $CodM[6] --> $CodM[7] --> < $Cantidad --> ".$MatUsuarios[$us][1]." --> $CodM[8]<br>";
																				$cons="Insert into salud.registromedicamentostmp (entidad, contrato,
																				nocontrato, identificacion, fecha, hora, codigo, nombre, cantidad,
																				usuario) values('$CodM[1]','$CodM[2]','$CodM[3]','$CodM[0]',
																				'$AnioMes-$ii $hh:$mm:$ss','$CantxHora[4]',$CodM[6],'$CodM[7]',
																				$Cantidad,'".$MatUsuarios[$us][1]."')";
																				$res=ExQuery($cons);
																			}
																			elseif($Cantidad==$CodM[8])
																			{
																				$Cantidad=$CodM[8]-$Cantidad;
																				if($i<10){$ii="0$i";}else{$ii=$i;}																		
																				$us=rand($ini,$fin);
																				$cont++;
																				echo "$cont --> $CodM[1] --> $CodM[2]  --> $CodM[3] --> $CodM[0] --> $AnioMes-$ii $hh:$mm:$ss --> $CantxHora[4] --> $CodM[6] --> $CodM[7] --> == $CantxHora[5] --> ".$MatUsuarios[$us][1]." --> $CodM[8]<br>";
																				$cons="Insert into salud.registromedicamentostmp (entidad, contrato,
																				nocontrato, identificacion, fecha, hora, codigo, nombre, cantidad,
																				usuario) values('$CodM[1]','$CodM[2]','$CodM[3]','$CodM[0]',
																				'$AnioMes-$ii $hh:$mm:$ss','$CantxHora[4]',$CodM[6],'$CodM[7]',
																				$CantxHora[5],'".$MatUsuarios[$us][1]."')";
																				$res=ExQuery($cons);
																			}
																			else
																			{
																				$Cantidad=$Cantidad-$CodM[8];
																				if($i<10){$ii="0$i";}else{$ii=$i;}																		
																				$us=rand($ini,$fin);
																				$cont++;
																				echo "$cont --> $CodM[1] --> $CodM[2]  --> $CodM[3] --> $CodM[0] --> $AnioMes-$ii $hh:$mm:$ss --> $CantxHora[4] --> $CodM[6] --> $CodM[7] --> > $Cantidad --> ".$MatUsuarios[$us][1]." --> $CodM[8]<br>";
																				$cons="Insert into salud.registromedicamentostmp (entidad, contrato,
																				nocontrato, identificacion, fecha, hora, codigo, nombre, cantidad,
																				usuario) values('$CodM[1]','$CodM[2]','$CodM[3]','$CodM[0]',
																				'$AnioMes-$ii $hh:$mm:$ss','$CantxHora[4]',$CodM[6],'$CodM[7]',
																				$Cantidad,'".$MatUsuarios[$us][1]."')";
																				$res=ExQuery($cons);		
																			}
																			$sale=1;
																			break;	
																		}														
																	}
																	else
																	{
																		echo "dosis > canttotal<br>";
																		$hh=rand(0,23);$mm=rand(0,59);$ss=rand(0,59);
																		if($hh<10){$hh="0".$hh;}
																		if($mm<10){$mm="0".$mm;}
																		if($ss<10){$ss="0".$ss;}	
																		$Cantidad=$CodM[8];
																		$CantxHora[5]=$CodM[8];
																		if($Cantidad<=$CodM[8])
																		{
																			if($i<10){$ii="0$i";}else{$ii=$i;}																		
																			$us=rand($ini,$fin);
																			$cont++;
																			echo "$cont --> $CodM[1] --> $CodM[2]  --> $CodM[3] --> $CodM[0] --> $AnioMes-$ii  $hh:$mm:$ss --> $CantxHora[4] --> $CodM[6] --> $CodM[7] --> $CantxHora[5] --> ".$MatUsuarios[$us][1]." --> $CodM[8]<br>";
																			$cons="Insert into salud.registromedicamentostmp (entidad, contrato,
																			nocontrato, identificacion, fecha, hora, codigo, nombre, cantidad,
																			usuario) values('$CodM[1]','$CodM[2]','$CodM[3]','$CodM[0]',
																			'$AnioMes-$ii $hh:$mm:$ss','$CantxHora[4]',$CodM[6],'$CodM[7]',
																			$CantxHora[5],'".$MatUsuarios[$us][1]."')";
																			$res=ExQuery($cons);
																		}
																		else
																		{
																			$sale=1;
																			break;	
																		}
																	}
																}	
															}
														}	
													}
												}
												else
												{													
													for($H=7;$H<=19;$H+=6)
													{
														$hh=rand(0,23);$mm=rand(0,59);$ss=rand(0,59);
														if($hh<10){$hh="0".$hh;}
														if($mm<10){$mm="0".$mm;}
														if($ss<10){$ss="0".$ss;}
														$Cantidad++;												
														if($Cantidad<=$CodM[8])
														{
															if($i<10){$ii="0$i";}else{$ii=$i;}																		
															$us=rand($ini,$fin);
															$cont++;
															echo "$cont --> $CodM[1] --> $CodM[2]  --> $CodM[3] --> $CodM[0] --> $AnioMes-$ii $hh:$mm:$ss --> $H --> $CodM[6] --> $CodM[7] --> 1 --> ".$MatUsuarios[$us][1]." --> $CodM[8]<br>";
															$cons="Insert into salud.registromedicamentostmp (entidad, contrato,
															nocontrato, identificacion, fecha, hora, codigo, nombre, cantidad,
															usuario) values('$CodM[1]','$CodM[2]','$CodM[3]','$CodM[0]',
															'$AnioMes-$ii $hh:$mm:$ss','$H',$CodM[6],'$CodM[7]',
															1,'".$MatUsuarios[$us][1]."')";
															$res=ExQuery($cons);
														}
														else
														{														
															$sale=1;
															break;	
														}
													}
												}	
											}
										}	
									}
								}	
							}	
						}	
					}	
				}
			}	
		}
	}
	else
	{
		echo "No se encontraron medicamentos en Facturacion...";	
	}	
?>