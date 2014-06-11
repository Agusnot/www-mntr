<?php
if($DatNameSID){session_name("$DatNameSID");}
session_start();
include("Funciones.php");
$ND=getdate();
$Fec="$ND[year]-$ND[mon]-$ND[mday] $ND[hours]:$ND[minutes]:$ND[seconds]";
if(!$Mes)$Mes="$ND[mon]";
if(!$Anio)$Anio="$ND[year]";
$NoHorasLab=240;
$DiasTr=30;	
//-------------------------------------------------------------------------------
//	echo "inicia";
	$cons="select mes from central.meses where numero=$Mes";
	$res=ExQuery($cons);
	$fila=ExFetch($res);
	$MesL=$fila[0];
//----------------------------------------------------------------------------	
$cons1="select movimiento,concepto,opera,arrastracon,claseconcepto,detconcepto,tipovinculacion from nomina.conceptosliquidacion where compania='$Compania[0]'
and tipoconcepto='Formula' order by movimiento,concepto";
//	echo $cons1;
$res=ExQuery($cons1);
while($fila=Exfetch($res))
{
	$Formula[$fila[0]][$fila[1]][$fila[6]]=array($fila[0],$fila[1],$fila[2],$fila[3],$fila[4],$fila[5],$fila[6]);
	//echo $fila[0]."-->".$fila[1]."-->".$fila[2]."-->".$fila[3]."-->".$fila[4]."-->".$fila[5]."-->".$fila[6]."<br>";
} 

if($Mes && $Anio && $Iniciar)
{

//------------------------------------------------------------------------------
	$cons1="select salariomin from nomina.minimo where ano=$Anio";
	$res1=ExQuery($cons1);
	$fila1=ExFetch($res1);
	$SalMinimo=$fila1[0];
//-------------------------------------------------------------------------------	
	$consIni="select identificacion from nomina.nomina where anio=$Anio and mes=$Mes";
	$resIni=ExQuery($consIni);
	$ContCons=ExNumRows($resIni);
//	echo $ContCons;
//--------------------------------------------------------------------------------
	
//--------------------------------------------------------------------------------
	if($ContCons==0)
	{
//-------------------------------------------------------------------------------		
		$cons="select terceros.identificacion,mesi,mesf,salarios.salario,terceros.primnom,terceros.segnom,terceros.primape,terceros.segape,
		tiposvinculacion.tipovinculacion from central.terceros,nomina.salarios,nomina.contratos,nomina.tiposvinculacion
where terceros.compania='$Compania[0]' and terceros.compania=salarios.compania and salarios.compania=contratos.compania and terceros.identificacion=salarios.identificacion and salarios.identificacion=contratos.identificacion and contratos.tipovinculacion=tiposvinculacion.codigo and terceros.tipo='Empleado' 
and salarios.anio='$Anio' and salarios.mesi<='$Mes' and salarios.mesf>='$Mes'";
//echo $cons;
		$res=ExQuery($cons);
		while($fila=Exfetch($res))
		{
			$Empleados[$fila[0]][$fila[1]][$fila[2]][$fila[3]][$fila[8]]=array($fila[0],$fila[1],$fila[2],$fila[3],$fila[4],$fila[5],$fila[6],$fila[7],$fila[8]);			
		}
//		echo $Empleados;
		$cont=0;
		if($Empleados)
		{
			foreach($Empleados as $Identi)
			{
				$DiasTr=30;
				foreach($Identi as $MesI)
				{
					foreach($MesI as $MesF)
					{
						foreach($MesF as $Sal)
						{
							foreach($Sal as $Vinc)
							{
									$Identificacion=$Vinc[0];
									$consNov="select dias,concepto from nomina.novedades where compania='$Compania[0]' and identificacion='$Identificacion' and anio = '$Anio' and mes = '$Mes'";
									//echo $consNov;
									$resNov=ExQuery($consNov);
									if(ExNumRows($resNov)>0)
									{
										while($filaNov=ExFetch($resNov))
										{									
											//echo $filaNov[0]." -- ".$filaNov[1]."<br>";	
											${$filaNov[1]}=$filaNov[0];
											$DiasTr=$DiasTr-$filaNov[0];
										}							
									}
									//echo $Sal[0]." --- ".$DiasIncapacEG." --- ".$DiasIncapacAT."<br>";
									$TotDevengados=0;
									$Nombre=$Vinc[4]." ".$Vinc[5]." ".$Vinc[6]." ".$Vinc[7];
									//echo $Nombre;
									$SalBase=$Vinc[3];
									echo $Nombre." --> ".$SalBase."<br>";
									$cont++;
		//-----------------------FORMULAS DEVENGADOS------------------------------------------------------						
									foreach($Formula["Devengados"] as $Concepto)
									{
										foreach($Concepto as $Vinculacion)
										{
											if($Vinc[8]==$Vinculacion[6])
											{
												eval("\$Valor=round($Vinculacion[2]);");
												//echo $Concepto[2];
													$cons="insert into nomina.nomina(compania,identificacion,mes,concepto,valor,movimiento,anio,arrastracon,usuario,claseregistro,detconcepto,fecha,vinculacion) values ('$Compania[0]','$Identificacion','$Mes','$Vinculacion[1]','$Valor','$Vinculacion[0]','$Anio','$Vinculacion[3]','$usuario[1]','$Vinculacion[4]','$Vinculacion[5]','$Fec','$Vinculacion[6]')";
												//	echo $cons."<br>";
													$res=ExQuery($cons);
													$TotDevengados=$TotDevengados+$Valor;
													${$Vinculacion[1]}=$Valor;
													//echo $Concepto[1]."  -->  ".$Valor."  -- ".$DiasTr."<br>";										
											}
										}
									}
									
		//-----------------------FORMULAS DEDUCIDOS------------------------------------------------------
						/*			foreach($Formula["Deducidos"] as $Concepto)
									{
										foreach($Concepto as $Vinculacion)
										{
											if($Vinc[8]==$Vinculacion[6])
											{
												$Valor=round(eval("$Vinculacion[2];"));
												
													$cons="insert into nomina.nomina(compania,identificacion,mes,concepto,valor,movimiento,anio,arrastracon,usuario,claseregistro,detconcepto,fecha,vinculacion) values ('$Compania[0]','$Identificacion','$Mes','$Vinculacion[1]','$Valor','$Vinculacion[0]','$Anio','$Vinculacion[3]','$usuario[1]','$Vinculacion[4]','$Vinculacion[5]','$Fec','$Vinculacion[6]')";
													$res=ExQuery($cons);
													${$Vinculacion[1]}=$Valor;									
													//echo $Vinculacion[1]."  -->  ".$Valor."<br>";										
												
				//								echo $Vinculacion[0]."--".$Vinculacion[1]."--".$Vinculacion[2]."--".$Vinculacion[3]."--".$Vinculacion[4]."--".$Valor."<br>";
											}
										}
									}
		//----------------------FORMULAS------------------------------------------------------------------							
									foreach($Formula["PostDevengados"] as $Concepto)
									{
										foreach($Concepto as $Vinculacion)
										{
											if($Vinc[8]==$Vinculacion[6])
											{
												$Valor=round(eval("$Vinculacion[2];"));
												
													$cons="insert into nomina.nomina(compania,identificacion,mes,concepto,valor,movimiento,anio,arrastracon,usuario,claseregistro,detconcepto,fecha,vinculacion) values ('$Compania[0]','$Identificacion','$Mes','$Vinculacion[1]','$Valor','$Vinculacion[0]','$Anio','$Vinculacion[3]','$usuario[1]','$Vinculacion[4]','$Vinculacion[5]','$Fec','$Vinculacion[6]')";
													$res=ExQuery($cons);
													${$Vinculacion[1]}=$Valor;									
													//echo $Vinculacion[1]."  -->  ".$Valor."  -->  ".${$Vinculacion[1]}."  -->  ".$AuxTransporte."<br>";										
												
				//								echo $Vinculacion[0]."--".$Vinculacion[1]."--".$Vinculacion[2]."--".$Vinculacion[3]."--".$Vinculacion[4]."--".$Valor."<br>";								
											}
										}
									}
		//----------------------FORMULAS------------------------------------------------------------------							
									foreach($Formula as $Movimiento)
									{
										foreach($Movimiento as $Concepto)
										{
											foreach($Concepto as $Vinculacion)
											{
												if($Vinc[8]==$Vinculacion[6])
												{
													if($Concepto[0]!="Devengados"&&$Concepto[0]!="Deducidos"&&$Concepto[0]!="PostDevengados")
													{
														$Valor=round(eval("$Vinculacion[2];"));
														
															$cons="insert into nomina.nomina(compania,identificacion,mes,concepto,valor,movimiento,anio,arrastracon,usuario,claseregistro,detconcepto,fecha,vinculacion) values ('$Compania[0]','$Identificacion','$Mes','$Vinculacion[1]','$Valor','$Vinculacion[0]','$Anio','$Vinculacion[3]','$usuario[1]','$Vinculacion[4]','$Vinculacion[5]','$Fec','$Vinculacion[6]')";
															$res=ExQuery($cons);
															${$Vinculacion[1]}=$Valor;	
															//echo $Vinculacion[1]."  -->  ".$Valor."  -->  ".${$Vinculacion[1]}."<br>";										
														
														//echo $Vinculacion[0]."--".$Vinculacion[1]."--".$Vinculacion[2]."--".$Vinculacion[3]."--".$Vinculacion[4]."<br>";									
													}
												}
											}
										}
									} */
									$consNov="select dias,concepto from nomina.novedades where compania='$Compania[0]' and identificacion='$Identificacion' and anio = '$Anio' and mes = '$Mes'";							
									$resNov=ExQuery($consNov);
									
									while($filaNov=ExFetch($resNov))
									{									
										//echo $filaNov[0]." -- ".$filaNov[1]."<br>";	
										${$filaNov[1]}='';
										//$DiasTr=$DiasTr-$filaNov[0];
									}
							}
						}
					}
				}			
			}
		}
		else
		{
			echo "<center>No existe registro de empleados para el año $Anio</center>";	
		}
	}
}
elseif($Mes && $Anio && $RetirarMov)
{
	$cons="Delete from nomina.nomina where Compania='$Compania[0]' and mes='$Mes' and anio='$Anio'";					
	$res=ExQuery($cons);		
}
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<script language="javascript" src="/Funciones.js"></script>
</head>
<body background="/Imgs/Fondo.jpg">
<?
if($Anio&&$Mes)
{
	$cons="select terceros.identificacion,terceros.primape,terceros.segape,terceros.primnom,terceros.segnom,detconcepto,valor from central.terceros,nomina.nomina where terceros.compania='$Compania[0]' and terceros.compania=nomina.compania and terceros.identificacion=nomina.identificacion and terceros.tipo='Empleado' and nomina.anio='$Anio' and nomina.mes='$Mes' order by primape,detconcepto";
	$res=ExQuery($cons);
	while($fila=Exfetch($res))
	{
		$Empleados[$fila[0]][$fila[5]]=array($fila[0],$fila[1],$fila[2],$fila[3],$fila[4],$fila[5],$fila[6]);			
		$IdNombres[$fila[0]]=array($fila[0],trim("$fila[1] $fila2 $fila[3] $fila[4]"));			
	}?>
	<table border="1" bordercolor="#e5e5e5" style='font : normal normal small-caps 12px Tahoma;' align="center" width="400px">	
<?	if($Empleados)
	{?>
		<tr><td bgcolor="#666699" style="color:white" align="center" ><? echo "Liquidacion Mes de "."$MesL";?></td></tr>
<?		foreach($IdNombres as $Empleado)
		{
			?>
            <tr><td>
			<table border="0" bordercolor="#e5e5e5" style='font : normal normal small-caps 12px Tahoma;' align="center" width="380px" >
			<tr><td bgcolor="#666699" style="color:white" align="center" colspan="2"><? echo $Empleado[1]?></td></tr>
            <tr bgcolor="#666699" style="color:white" align="center"><td>Concepto</td><td>Valor</td></tr>
            <?
			foreach($Empleados[$Empleado[0]] as $Auto)
			{
				if($Auto[6]!=0)
				{?>
                	<tr onMouseOver="this.bgColor='#AAD4FF'" onMouseOut="this.bgColor=''"><td><? echo $Auto[5]?></td><td align="right"><? echo number_format($Auto[6],0);?></td></tr>
                    <?
				}
			}
			?>
            </table>
            </td></tr> 
			<?
		}
?>		</table> 		
<?	}
}
?>
</body>
</html>	