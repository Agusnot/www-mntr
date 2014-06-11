<?php
if($DatNameSID){session_name("$DatNameSID");}
session_start();
include("Funciones.php");
$ND=getdate();
if("$ND[mday]"<10)
{
	$Dia="0$ND[mday]";
}
if("$ND[mon]"<10)
{
	$MesA="0$ND[mon]";
}
$Fecha="$Anio-$Mes-$Dia";
$Fec="$ND[year]-$MesA-$Dia $ND[hours]:$ND[minutes]:$ND[seconds]";
if($Mes<10){$Mes="0$Mes";}
$FecInicio="$Anio-$Mes-01";
if($Mes==2)
{
	$FecFin="$Anio-$Mes-28";
}
else
{
	$FecFin="$Anio-$Mes-30";
}

if($Anio&&$Mes)
{
?>
<script language="javascript">
parent(0).location.href="EncabAbrirMes.php?DatNameSID=<? echo $DatNameSID?>&Anio=<? echo $Anio?>&Mes=<? echo $Mes?>";
</script>
<?
}
if(!$Mes)$Mes="$ND[mon]";
if(!$Anio)$Anio="$ND[year]";
$NoHorasLab=240;
//------------------Busca el concepto de dias trabajados--------------------------------------------------------
$consD="select concepto from nomina.conceptosliquidacion where diastr='1'";
//echo $cons."<br>";
$resD=ExQuery($consD);
while($filaD=ExFetch($resD))
{
	${$filaD[0]};
//	echo $filaD[0]."<br>";
}
//------------------Consulta el nombre del mes--------------------------------------------------------
$cons="select mes from central.meses where numero=$Mes";
//echo $cons;
$res=ExQuery($cons);
$fila=ExFetch($res);
$MesL=$fila[0];
//----------------Consulta de Formula------------------------------------------------------------	
$cons1="select movimiento,concepto,opera,arrastracon,claseconcepto,detconcepto,tipovinculacion from nomina.conceptosliquidacion where compania='$Compania[0]'
and tipoconcepto='Formula' order by movimiento,concepto";
//	echo $cons1;
$res=ExQuery($cons1);
while($fila=Exfetch($res))
{
	$Formula[$fila[0]][$fila[1]][$fila[6]]=array($fila[0],$fila[1],$fila[2],$fila[3],$fila[4],$fila[5],$fila[6]);
	//echo $fila[0]."-->".$fila[1]."-->".$fila[2]."-->".$fila[3]."-->".$fila[4]."-->".$fila[5]."-->".$fila[6]."<br>";
} 
//-----------------Inicio de mes ---------------------------------------------------------------
if($Mes && $Anio && $Iniciar)
{
//-----------------Consulta de Salario Minimo vigente------------------------------------------------
	$cons1="select salariomin from nomina.minimo where ano=$Anio";
	$res1=ExQuery($cons1);
	$fila1=ExFetch($res1);
	$SalMinimo=$fila1[0];
//	echo $SalMinimo;
	//-----------------
	$cons="select terceros.identificacion,contratos.fecinicio,contratos.fecfin,terceros.primnom,terceros.segnom,terceros.primape,terceros.segape, 
	tiposvinculacion.tipovinculacion,contratos.numero from central.terceros,nomina.contratos,nomina.tiposvinculacion where terceros.compania='$Compania[0]' and
	 terceros.identificacion=contratos.identificacion and contratos.tipovinculacion=tiposvinculacion.codigo and (terceros.tipo='Empleado' or regimen='Empleado') and
	  contratos.estado='Activo' group by terceros.identificacion,contratos.fecinicio,contratos.fecfin,terceros.primnom,terceros.segnom,terceros.primape,terceros.segape, 
	  tiposvinculacion.tipovinculacion,contratos.numero order by primape";
//	echo $cons."<br>";
	$res=ExQuery($cons);
	while($fila=ExFetch($res))
	{
		$Empleados[$fila[0]][$fila[1]][$fila[2]][$fila[3]][$fila[8]]=array($fila[0],$fila[1],$fila[2],$fila[3],$fila[4],$fila[5],$fila[6],$fila[7],$fila[8],$fila[9]);			
	}
//----------------- VALIDAR SI TIENE DATOS FALTANTES ------------------------------------
if($Empleados)
{
	foreach($Empleados as $Identi)
	{
		foreach($Identi as $MesI)
		{
			foreach($MesI as $MesF)
			{
				foreach($MesF as $Sal)
				{
					foreach($Sal as $Vinc)
					{
//						echo $Vinc[0]."-->".$Vinc[1]."-->".$Vinc[2]."-->".$Vinc[3]."-->".$Vinc[4]."-->".$Vinc[5]."-->".$Vinc[6]."-->".$Vinc[7]."-->".$Vinc[8]."<br>";
						$A=0;$B=0;$C=0;$D=0;$E=0;$F=0;$Cont=0;
						$Identificacion=$Vinc[0];
						$Numero=$Vinc[8];
						$Nombre=$Vinc[3]." ".$Vinc[4]." ".$Vinc[5]." ".$Vinc[6];
						$conssuma="select sum(porcentaje) from nomina.centrocostos where compania='$Compania[0]' and identificacion='$Identificacion' 
						and fecinicio<='$FecInicio' and (fecfin>='$FecFin' or fecfin is null) and numcontrato='$Numero'";
						$ressuma=ExQuery($conssuma);
						$filasuma=ExFetch($ressuma);
//						echo $conssuma."<br>";
						if($filasuma[0]!=100)
						{
//							echo "A";
							$A=1;
							$Cont++;
						}
						$coneps="select identificacion from nomina.epsxc where compania='$Compania[0]' and identificacion = '$Identificacion' and fecinicio<='$FecInicio' and (fecfin>='$FecFin' or fecfin is null)";
						$reseps=ExQuery($coneps);
						$Con1=ExNumRows($reseps);
//						echo $coneps."<br>";
						if($Con1==0)
						{
//							echo "B";
							$B=1;
							$Cont++;
						}
						$conces="select identificacion from nomina.cesantiasxc where compania='$Compania[0]' and identificacion = '$Identificacion' and fecinicio<='$FecInicio' and (fecfin>='$FecFin' or fecfin is null)";
						$resces=ExQuery($conces);
						$Con2=ExNumRows($resces);
						if($Con2==0)
						{
//							echo "C";
							$C=1;
							$Cont++;
						}
						$conpen="select identificacion from nomina.pensionesxc where compania='$Compania[0]' and identificacion = '$Identificacion' and fecinicio<='$FecInicio' and (fecfin>='$FecFin' or fecfin is null)";
//						echo $conpen."<br>";
						$respen=ExQuery($conpen);
						$Con3=ExNumRows($respen);
						if($Con3==0)
						{
//							echo "D";
							$D=1;
							$Cont++;
						}
						$conarp="select identificacion from nomina.arpxc where compania='$Compania[0]' and identificacion = '$Identificacion' and fecinicio<='$FecInicio' and (fecfin>='$FecFin' or fecfin is null)";
//						echo $conarp."<br>";
						$resarp=ExQuery($conarp);
						$Con4=ExNumRows($resarp);
						if($Con4==0)
						{
//							echo "E";
							$E=1;
							$Cont++;
						}
						$consal="select identificacion from nomina.salarios where compania='$Compania[0]' and identificacion = '$Identificacion' and fecinicio<='$FecInicio' and (fecfin>='$FecFin' or fecfin is null)";
//						echo $consal."<br>";
						$ressal=ExQuery($consal);
						$Con5=ExNumRows($ressal);
						if($Con5==0)
						{
//							echo $consal."<br>";
							$F=1;
							$Cont++;
						}
						if($Cont>0)
						{
							?>
                           	<table border="1" bordercolor="#e5e5e5" style='font : normal normal small-caps 12px Tahoma;' align="center" width="400px">
                            <tr><td bgcolor="#666699" style="color:white" align="center" colspan="2"><a href="/Nomina/ResultBusHojaVida.php?DatNameSID=<? echo $DatNameSID;?>&Identificacion=<? echo $Identificacion?>&Buscar=1&Estado=Activo"><font color="#FFFFFF"><? echo $Identificacion." - ".$Nombre?></font></a></td></tr>
                            <? if($A==1)
                            {?>
                            	<tr><td align="center"><font color="#FF0000">No se encuentra configurado el CENTRO DE COSTO para este MES</font></td></tr>
                            <? }
							if($B==1)
                            {?>
                            	<tr><td align="center"><font color="#FF0000">No se encuentra configurado la EPS para este MES</font></td></tr>
                            <? }
							if($C==1)
                            {?>
                            	<tr><td align="center"><font color="#FF0000">No se encuentra configurado la CESANTIA para este MES</font></td></tr>
                            <? }
							if($D==1)
                            {?>
                            	<tr><td align="center"><font color="#FF0000">No se encuentra configurado la PENSION para este MES</font></td></tr>
                            <? }
							if($E==1)
                            {?>
                            	<tr><td align="center"><font color="#FF0000">No se encuentra configurado la ARP para este MES</font></td></tr>
                            <? }
							if($F==1)
                            {?>
                            	<tr><td align="center"><font color="#FF0000">No se encuentra configurado el SALARIO para este MES</font></td></tr>
                            <? }
							?>
                               </table>
							<?
							if($Cont>0)
							{
								$Cont1++;
							}
//							echo $Cont1;
						}
					}
				}
			}
		}
	}
}
//echo $Cont;
//----------------------------------------------------------------------------------------	
if($Cont1==0)
{
	if($Empleados)
	{
		foreach($Empleados as $Identi)
		{
//			$DiasTr=30;
			foreach($Identi as $MesI)
			{
				
				foreach($MesI as $MesF)
				{
					foreach($MesF as $Sal)
					{
						foreach($Sal as $Vinc)
						{
//------------------------inicializar los dias------------------------------------------
							$cons="select concepto from nomina.conceptosliquidacion where claseconcepto='Dias'";
							$res=ExQuery($cons);
							while($fila=ExFetch($res))
							{
								${$fila[0]}=0;
							}
//--------------------------------------------------------------------------------------							
							$Cont=0;
							$Identificacion=$Vinc[0];
							$Numero=$Vinc[8];
//---------------------------Porcentaje ARP----------------------------------------------
							$ConsPor="select arp from nomina.arpxc where compania='$Compania[0]' and identificacion='$Identificacion' and fecinicio<='$FecInicio' and (fecfin>='$FecFin' or fecfin is null)";
//							echo $ConsPor;
							$ResPor=ExQuery($ConsPor);
							$fila=ExFetch($ResPor);
							$PorARP=$fila[0];
//							echo $PorARP;
//---------------------------Busca dias trabajados---------------------------------------------------							
							$consdias="select concepto, diastr from nomina.diastrab,nomina.tiposvinculacion where tiposvinculacion.compania='$Compania[0]' and
							 diastrab.compania=tiposvinculacion.compania and identificacion='$Identificacion' and mestr='$Mes' and anio='$Anio' and numero='$Numero' and
							  tiposvinculacion.codigo=diastrab.vinculacion";
//							echo $consdias."<br>";
							$resdias=ExQuery($consdias);
							if(ExNumRows($resdias)>0)
							{
								$filadias=ExFetch($resdias);
								${$filadias[0]}=$filadias[1];
//								echo $filadias[0]." = ".$filadias[1]."<br>";
							}
							else
							{
								$consdias="select concepto from nomina.conceptosliquidacion,nomina.tiposvinculacion where conceptosliquidacion.compania='$Compania[0]'
								and conceptosliquidacion.compania=tiposvinculacion.compania and diastr='1' and conceptosliquidacion.tipovinculacion='$Vinc[7]' and conceptosliquidacion.tipovinculacion=tiposvinculacion.
								tipovinculacion";
								$resdias=ExQuery($consdias);
								$filadias=ExFetch($resdias);
//								echo $consdias;
								${$filadias[0]}=30;
//								echo $filadias[0]." = ".${$filadias[0]}."<br>";
							}
//----------------------------- Buscar salario Base
							$conssal="select salario from nomina.salarios where compania='$Compania[0]' and identificacion='$Identificacion' and fecinicio<='$FecInicio' and fecfin>='$FecFin' and numcontrato='$Numero'";
//							echo $conssal."<br>";
							$Res=ExQuery($conssal);
							$fila=ExFetch($Res);
							$SalBase=$fila[0];
//							echo $SalBase."<br>";
//-----------------------------	asignacion de variables---------------------------------------------------						
							$TotDevengados=0;
								
							$Nombre=$Vinc[3]." ".$Vinc[4]." ".$Vinc[5]." ".$Vinc[5];
//							if($SalBase){ echo "$SalBase  $Nombre <br>";}	
//							echo $Nombre."<br>";
//								echo "hace<br>";
//-----------------------------	consulta las novedades y las asigna ------------------------------------------------
							$consNov="select dias,concepto from nomina.novedades where compania='$Compania[0]' and identificacion='$Identificacion' and anio = '$Anio'
							 and mes = '$Mes'";
// 							echo $consNov."<br>";
							$resNov=ExQuery($consNov);
							if(ExNumRows($resNov)>0)
							{
								while($filaNov=ExFetch($resNov))
								{									
//									echo $filaNov[0]." -- ".$filaNov[1]."<br>";	
									${$filaNov[1]}=$filaNov[0];
//									echo ${$filadias[0]}."<br>";
									${$filadias[0]}=${$filadias[0]}-$filaNov[0];
//									echo ${$filadias[0]}." --> ".$filaNov[0];
								}							
							}
//							echo $DiasIncapac."<br>";
//---------------------------consulta de conceptos programados
								$consprog="select concepto,detconcepto,valor,movimiento,claseregistro,arrastracon,vinculacion from nomina.conceptosprogramados where compania='$Compania[0]' and identificacion='$Vinc[0]' and numero='$Numero' and fecinicio<'$Fecha' and fecfin>'$Fecha'";
								$resprog=ExQuery($consprog);
								while($fila=ExFetch($resprog))
								{
									$cons="insert into nomina.nomina values ('$Compania[0]','$Vinc[0]','$Mes','$fila[0]','$fila[2]','$fila[3]','$fila[4]','$fila[1]','$fila[5]','$usuario[1]','$Anio','$Fec','$fila[6]','$Numero')";
//									echo $cons;
									$res=ExQuery($cons);
								}
//								echo $consprog."<br>";
//---------------------------consulta de conceptos desprogramados --------------------------------------------
								unset($Desprogramados);
								$consdes="select identificacion,concepto,detconcepto,movimiento,claseregistro,arrastracon,vinculacion from nomina.conceptosdesprogramados where compania='$Compania[0]' and identificacion='$Vinc[0]' and numero='$Numero' and fecinicio<'$Fecha' and fecfin>'$Fecha'";
								$resdes=ExQuery($consdes);
//								echo $consdes."<br><br>";
								while($filaD = ExFetch($resdes))
								{
									$Desprogramados[$filaD[3]]=array($filaD[0],$filaD[1],$filaD[2],$filaD[3],$filaD[4],$filaD[5]);	
								}
//								echo $consdes."<br>";
//---------------------------formulas devengados -------------------------------------------------------------
	//							echo "Devengados<br>";
								foreach($Formula["Devengados"] as $Concepto)
								{
									foreach($Concepto as $Vinculacion)
									{
//										echo $Vinc[8]." --> ".$Vinculacion[6]." && ".$Vinculacion[3]." == ".$filadias[0]."<br>";
										if($Vinc[7]==$Vinculacion[6])
										{
//											echo $Vinculacion[1]."<br>";
											$consdes="select identificacion,concepto,detconcepto,movimiento,claseregistro,arrastracon,vinculacion from nomina.conceptosdesprogramados where compania='$Compania[0]' and identificacion='$Identificacion' and concepto='$Vinculacion[1]' and numero='$Numero' and fecinicio<'$Fecha' and fecfin>'$Fecha'";
											$resdes=ExQuery($consdes);
											$cont=ExNumRows($resdes);
											if($cont==0)
											{
												eval("\$Valor=round($Vinculacion[2]);");
												$cons="insert into nomina.nomina(compania,identificacion,mes,concepto,valor,movimiento,anio,arrastracon,usuario,
												claseregistro,detconcepto,fecha,vinculacion,numero) values ('$Compania[0]','$Identificacion','$Mes','$Vinculacion[1]',
												'$Valor','$Vinculacion[0]','$Anio','$Vinculacion[3]','$usuario[1]','$Vinculacion[4]','$Vinculacion[5]','$Fec',
												'$Vinculacion[6]','$Numero')";
												$res=ExQuery($cons);
												$TotDevengados=$TotDevengados+$Valor;
												${$Vinculacion[1]}=$Valor;
											}

											/*if($Desprogramados[$Vinculacion[0]])
											{ 
												if($Vinculacion[1]!=$Desprogramados[$Vinculacion[0]][1])
												{
//													echo $Vinculacion[1]." restringido<br>";											
													//echo $ConcDes[2]."<--<br>";
		//											echo ${$filaNov[0]}." = ".$filaNov[1];
													eval("\$Valor=round($Vinculacion[2]);");
			//										echo $DiasTr.$dias." --> ".$Valor." --> ".$Vinculacion[2]."<br>";
			//										echo $Valor."<br>";
													$cons="insert into nomina.nomina(compania,identificacion,mes,concepto,valor,movimiento,anio,arrastracon,usuario,
													claseregistro,detconcepto,fecha,vinculacion,numero) values ('$Compania[0]','$Identificacion','$Mes','$Vinculacion[1]',
													'$Valor','$Vinculacion[0]','$Anio','$Vinculacion[3]','$usuario[1]','$Vinculacion[4]','$Vinculacion[5]','$Fec',
													'$Vinculacion[6]','$Numero')";
		//											echo $cons."<br><br>";
													$res=ExQuery($cons);
													$TotDevengados=$TotDevengados+$Valor;
													${$Vinculacion[1]}=$Valor;
		//											echo $TotDevengados;
			//										echo ${$Vinculacion[1]};		
												}
											}
											else
											{
//												echo $Vinculacion[1]."  normal<br>";											
												//echo $ConcDes[2]."<--<br>";
	//											echo ${$filaNov[0]}." = ".$filaNov[1];
												eval("\$Valor=round($Vinculacion[2]);");
		//										echo $DiasTr.$dias." --> ".$Valor." --> ".$Vinculacion[2]."<br>";
		//										echo $Valor."<br>";
												$cons="insert into nomina.nomina(compania,identificacion,mes,concepto,valor,movimiento,anio,arrastracon,usuario,
												claseregistro,detconcepto,fecha,vinculacion,numero) values ('$Compania[0]','$Identificacion','$Mes','$Vinculacion[1]',
												'$Valor','$Vinculacion[0]','$Anio','$Vinculacion[3]','$usuario[1]','$Vinculacion[4]','$Vinculacion[5]','$Fec',
												'$Vinculacion[6]','$Numero')";
	//											echo $cons."<br><br>";
												$res=ExQuery($cons);
												$TotDevengados=$TotDevengados+$Valor;
												${$Vinculacion[1]}=$Valor;
	//											echo $TotDevengados;
		//										echo ${$Vinculacion[1]};
											}*/										
										}
									}
								}
	//-------------------------formulas deducidos------------------------------------------------------------							
	//							echo "Deducidos<br>";
//								echo $Vinc[8]." --> ".$Vinculacion[6]." // ".$Vinculacion[3]." --> ".$filadias[0]."<br>";
								foreach($Formula["Deducidos"] as $Concepto)
								{
									foreach($Concepto as $Vinculacion)
									{
										if($Vinc[7]==$Vinculacion[6])
										{
											$consdes="select identificacion,concepto,detconcepto,movimiento,claseregistro,arrastracon,vinculacion from nomina.conceptosdesprogramados where compania='$Compania[0]' and identificacion='$Identificacion' and concepto='$Vinculacion[1]' and numero='$Numero' and fecinicio<'$Fecha' and fecfin>'$Fecha'";
											$resdes=ExQuery($consdes);
											$cont=ExNumRows($resdes);
											if($cont==0)
											{
												$Valor=round(eval("$Vinculacion[2];"));
												$cons="insert into nomina.nomina(compania,identificacion,mes,concepto,valor,movimiento,anio,arrastracon,usuario,
												claseregistro,detconcepto,fecha,vinculacion,numero) values('$Compania[0]','$Identificacion','$Mes','$Vinculacion[1]',
												'$Valor','$Vinculacion[0]','$Anio','$Vinculacion[3]','$usuario[1]','$Vinculacion[4]','$Vinculacion[5]','$Fec',
												'$Vinculacion[6]','$Numero')";
												$res=ExQuery($cons);
												${$Vinculacion[1]}=$Valor;									
											}
/*											if($Desprogramados[$Vinculacion[0]])
											{	
												if($Vinculacion[1]!=$Desprogramados[$Vinculacion[0]][1])
												{
//													echo $Desprogramados[$Vinculacion[0]][3]."  <-- ".$Vinculacion[0]."desprogramados <br>";
		//											echo $TotDevengados;
													$Valor=round(eval("$Vinculacion[2];"));
//													echo $Valor."desprogramado<br>";
													$cons="insert into nomina.nomina(compania,identificacion,mes,concepto,valor,movimiento,anio,arrastracon,usuario,
													claseregistro,detconcepto,fecha,vinculacion,numero) values('$Compania[0]','$Identificacion','$Mes','$Vinculacion[1]',
													'$Valor','$Vinculacion[0]','$Anio','$Vinculacion[3]','$usuario[1]','$Vinculacion[4]','$Vinculacion[5]','$Fec',
													'$Vinculacion[6]','$Numero')";
													$res=ExQuery($cons);
													${$Vinculacion[1]}=$Valor;									
													//echo $Vinculacion[1]."  -->  ".$Valor."<br>";										
			//										echo $Vinculacion[0]."--".$Vinculacion[1]."--".$Vinculacion[2]."--".$Vinculacion[3]."--".$Vinculacion[4]."--".$Valor."<br>";
												}
											}
											else
											{
//												echo $TotDevengados;
												$Valor=round(eval("$Vinculacion[2];"));
//												echo $Desprogramados[$Vinculacion[0]][3]."  <-- ".$Vinculacion[0]."normal <br>";
												$cons="insert into nomina.nomina(compania,identificacion,mes,concepto,valor,movimiento,anio,arrastracon,usuario,
												claseregistro,detconcepto,fecha,vinculacion,numero) values('$Compania[0]','$Identificacion','$Mes','$Vinculacion[1]',
												'$Valor','$Vinculacion[0]','$Anio','$Vinculacion[3]','$usuario[1]','$Vinculacion[4]','$Vinculacion[5]','$Fec',
												'$Vinculacion[6]','$Numero')";
												$res=ExQuery($cons);
												${$Vinculacion[1]}=$Valor;									
												//echo $Vinculacion[1]."  -->  ".$Valor."<br>";										
		//										echo $Vinculacion[0]."--".$Vinculacion[1]."--".$Vinculacion[2]."--".$Vinculacion[3]."--".$Vinculacion[4]."--".$Valor."<br>";
											}*/
										}
									}
								}
	//----------------------Formulas postdevengados------------------------------------------------------------
	//							echo "PostDevengados<br>";
								foreach($Formula["PostDevengados"] as $Concepto)
								{
									foreach($Concepto as $Vinculacion)
									{
										if($Vinc[7]==$Vinculacion[6])
										{
											$consdes="select identificacion,concepto,detconcepto,movimiento,claseregistro,arrastracon,vinculacion from nomina.conceptosdesprogramados where compania='$Compania[0]' and identificacion='$Identificacion' and concepto='$Vinculacion[1]' and numero='$Numero' and fecinicio<'$Fecha' and fecfin>'$Fecha'";
											$resdes=ExQuery($consdes);
											$cont=ExNumRows($resdes);
											if($cont==0)
											{
												$Valor=round(eval("$Vinculacion[2];"));
												$cons="insert into nomina.nomina(compania,identificacion,mes,concepto,valor,movimiento,anio,arrastracon,usuario,
												claseregistro,detconcepto,fecha,vinculacion,numero) values ('$Compania[0]','$Identificacion','$Mes','$Vinculacion[1]',
												'$Valor','$Vinculacion[0]','$Anio','$Vinculacion[3]','$usuario[1]','$Vinculacion[4]','$Vinculacion[5]','$Fec',
												'$Vinculacion[6]','$Numero')";
												$res=ExQuery($cons);
												${$Vinculacion[1]}=$Valor;									
											}
/*											if($Desprogramados[$Vinculacion[0]])
											{	
												if($Vinculacion[1]!=$Desprogramados[$Vinculacion[0]][1])
												{
													$Valor=round(eval("$Vinculacion[2];"));
			//										echo $Valor."<br>";
													$cons="insert into nomina.nomina(compania,identificacion,mes,concepto,valor,movimiento,anio,arrastracon,usuario,
													claseregistro,detconcepto,fecha,vinculacion,numero) values ('$Compania[0]','$Identificacion','$Mes','$Vinculacion[1]',
													'$Valor','$Vinculacion[0]','$Anio','$Vinculacion[3]','$usuario[1]','$Vinculacion[4]','$Vinculacion[5]','$Fec',
													'$Vinculacion[6]','$Numero')";
													$res=ExQuery($cons);
													${$Vinculacion[1]}=$Valor;									
													//echo $Vinculacion[1]."  -->  ".$Valor."  -->  ".${$Vinculacion[1]}."  -->  ".$AuxTransporte."<br>";										
			//										echo $Vinculacion[0]."--".$Vinculacion[1]."--".$Vinculacion[2]."--".$Vinculacion[3]."--".$Vinculacion[4]."--".$Valor."<br>";								
												}
											}
											else
											{
												$Valor=round(eval("$Vinculacion[2];"));
		//										echo $Valor."<br>";
												$cons="insert into nomina.nomina(compania,identificacion,mes,concepto,valor,movimiento,anio,arrastracon,usuario,
												claseregistro,detconcepto,fecha,vinculacion,numero) values ('$Compania[0]','$Identificacion','$Mes','$Vinculacion[1]',
												'$Valor','$Vinculacion[0]','$Anio','$Vinculacion[3]','$usuario[1]','$Vinculacion[4]','$Vinculacion[5]','$Fec',
												'$Vinculacion[6]','$Numero')";
												$res=ExQuery($cons);
												${$Vinculacion[1]}=$Valor;									
												//echo $Vinculacion[1]."  -->  ".$Valor."  -->  ".${$Vinculacion[1]}."  -->  ".$AuxTransporte."<br>";										
		//										echo $Vinculacion[0]."--".$Vinculacion[1]."--".$Vinculacion[2]."--".$Vinculacion[3]."--".$Vinculacion[4]."--".$Valor."<br>";
											} */
										}
									}
								}							
	//---------------------resto de formulas--------------------------------------------------------------
								foreach($Formula as $Movimiento)
								{
									foreach($Movimiento as $Concepto)
									{
										foreach($Concepto as $Vinculacion)
										{
											if($Vinc[7]==$Vinculacion[6])
											{
												if($Vinculacion[0]!="Devengados"&&$Vinculacion[0]!="Deducidos"&&$Vinculacion[0]!="PostDevengados")
												{
													$consdes="select identificacion,concepto,detconcepto,movimiento,claseregistro,arrastracon,vinculacion from nomina.conceptosdesprogramados where compania='$Compania[0]' and identificacion='$Identificacion' and concepto='$Vinculacion[1]' and numero='$Numero' and fecinicio<'$Fecha' and fecfin>'$Fecha'";
													$resdes=ExQuery($consdes);
													$cont=ExNumRows($resdes);
													if($cont==0)
													{
														$Valor=round(eval("$Vinculacion[2];"));
														$cons="insert into nomina.nomina(compania,identificacion,mes,concepto,valor,movimiento,anio,
														arrastracon,usuario,claseregistro,detconcepto,fecha,vinculacion,numero) values ('$Compania[0]','$Identificacion',
														'$Mes','$Vinculacion[1]','$Valor','$Vinculacion[0]','$Anio','$Vinculacion[3]','$usuario[1]',
														'$Vinculacion[4]','$Vinculacion[5]','$Fec','$Vinculacion[6]','$Numero')";
														$res=ExQuery($cons);
														${$Vinculacion[1]}=$Valor;	
													}
/*													if($Desprogramados[$Vinculacion[0]])
													{	
														if($Vinculacion[1]!=$Desprogramados[$Vinculacion[0]][1])
														{
															$Valor=round(eval("$Vinculacion[2];"));
															$cons="insert into nomina.nomina(compania,identificacion,mes,concepto,valor,movimiento,anio,
															arrastracon,usuario,claseregistro,detconcepto,fecha,vinculacion,numero) values ('$Compania[0]','$Identificacion',
															'$Mes','$Vinculacion[1]','$Valor','$Vinculacion[0]','$Anio','$Vinculacion[3]','$usuario[1]',
															'$Vinculacion[4]','$Vinculacion[5]','$Fec','$Vinculacion[6]','$Numero')";
															$res=ExQuery($cons);
															${$Vinculacion[1]}=$Valor;	
			//												echo $Vinculacion[1]."  -->  ".$Valor."  -->  ".${$Vinculacion[1]}."<br>";										
			//												echo $Vinculacion[0]."--".$Vinculacion[1]."--".$Vinculacion[2]."--".$Vinculacion[3]."--".$Vinculacion[4]."<br>";									
														}
													}
													else
													{
														$Valor=round(eval("$Vinculacion[2];"));
														$cons="insert into nomina.nomina(compania,identificacion,mes,concepto,valor,movimiento,anio,
														arrastracon,usuario,claseregistro,detconcepto,fecha,vinculacion,numero) values ('$Compania[0]','$Identificacion',
														'$Mes','$Vinculacion[1]','$Valor','$Vinculacion[0]','$Anio','$Vinculacion[3]','$usuario[1]',
														'$Vinculacion[4]','$Vinculacion[5]','$Fec','$Vinculacion[6]','$Numero')";
														$res=ExQuery($cons);
														${$Vinculacion[1]}=$Valor;	
		//												echo $Vinculacion[1]."  -->  ".$Valor."  -->  ".${$Vinculacion[1]}."<br>";										
		//												echo $Vinculacion[0]."--".$Vinculacion[1]."--".$Vinculacion[2]."--".$Vinculacion[3]."--".$Vinculacion[4]."<br>";									
													} */
												}
											}
										}
									}
								}
//---------------------------- Fin ---------------------------------------------------------
							}
						}
					}
				}
			}
		}
	}
}
elseif($Mes && $Anio && $RetirarMov)
{
	$cons="Delete from nomina.nomina where Compania='$Compania[0]' and mes='$Mes' and anio='$Anio' and cierre is Null";	
//	echo $cons;				
	$res=ExQuery($cons);		
	?><script language="javascript">alert("Se ha Retirado el Movimiento con Exito");</script>
    <?
}
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<script language="javascript" src="/Funciones.js"></script>
</head>
<body background="/Imgs/Fondo.jpg">
<input type="hidden" name="DatNameSID" value="<? echo $DatNameSID?>">
<?
//echo $DatNameSID;
$cons="select terceros.identificacion,terceros.primape,terceros.segape,terceros.primnom,terceros.segnom,detconcepto,valor,movimiento from central.terceros,nomina.nomina where terceros.compania='$Compania[0]' and terceros.compania=nomina.compania and terceros.identificacion=nomina.identificacion and (terceros.tipo='Empleado' or terceros.regimen='Empleado') and nomina.anio='$Anio' and nomina.mes='$Mes' and claseregistro!='Cantidad' order by primape,detconcepto,movimiento";
//echo $cons;
$res=ExQuery($cons);
while($fila=Exfetch($res))
{
	$Empleados1[$fila[0]][$fila[5]]=array($fila[0],$fila[1],$fila[2],$fila[3],$fila[4],$fila[5],$fila[6],$fila[7]);			
	$IdNombres[$fila[0]]=array($fila[0],trim("$fila[1] $fila2 $fila[3] $fila[4]"));			
}?>
<table border="1" bordercolor="#e5e5e5" style='font : normal normal small-caps 12px Tahoma;' align="center" width="400px">	
<?
if($Empleados1)
{?>
	<tr><td bgcolor="#666699" style="color:white" align="center" ><? echo "Liquidacion Mes de "."$MesL";?></td></tr>
<?	
	foreach($IdNombres as $Empleado)
	{
		?>
		<tr><td>
		<table border="0" bordercolor="#e5e5e5" style='font : normal normal small-caps 12px Tahoma;' align="center" width="380px" >
		<tr><td bgcolor="#666699" style="color:white" align="center" colspan="2"><? echo $Empleado[0]." - ".$Empleado[1]?></td></tr>
        <tr bgcolor="#666699" style="color:white" align="center"><td>Concepto</td><td>Valor</td></tr>
        <?
		foreach($Empleados1[$Empleado[0]] as $Auto)
		{
//			echo $Auto[7];
			if($Auto[6]!=0)
			{?>
               	<tr onMouseOver="this.bgColor='#AAD4FF'" onMouseOut="this.bgColor=''"><td><? echo $Auto[5]?></td><td align="right"><? echo number_format($Auto[6],0,'','.');?></td></tr>
             <?
			}
		}
		?>
        </table>
        </td></tr> 
		<?
	}
?>		
</table> 		
<?	
}
?>
</body>
</html>