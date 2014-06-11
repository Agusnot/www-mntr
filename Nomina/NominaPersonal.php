<?php
if($DatNameSID){session_name("$DatNameSID");}
session_start();
include("Funciones.php");
$ND=getdate();
$Fec="$ND[year]-$ND[mon]-$ND[mday] $ND[hours]:$ND[minutes]:$ND[seconds]";
if(!$Anio){$Anio="$ND[year]";}
if(!$Mes){$Mes="$ND[mon]";}
$NoHorasLab=240;
$TotDevengados="Null"; $TotDeducidos="Null";
//echo $Identificacion;
$ConsCont="select numero from nomina.contratos where compania='$Compania[0]' and identificacion='$Identificacion' and estado='Activo'";
$ResCont=ExQuery($ConsCont);
$fila=ExFetch($ResCont);
global $Numero;
$Numero=$fila[0];
//echo $Numero;//$ConsCont;
//echo "tod devengados: ".$TotDevengados;
//------------------------fechas---------------------------------------------------------------------
if($Mes<=9)
{
	if($Mes==2)
	{
	$FecVinI="$Anio-0$Mes-01";
	$FecVinF="$Anio-0$Mes-28";		
	}
	else
	{
	$FecVinF="$Anio-0$Mes-30";
	$FecVinI="$Anio-0$Mes-01";
	}
	$Fecha="$Anio-0$Mes-$ND[mday]";
}
else
{
$FecVinI="$Anio-$Mes-01";
$FecVinF="$Anio-$Mes-30";
}
if("$Mes"<10)
{
	$Mes="0$Mes";
}
if("$ND[mday]"<10)
{
	$Dia="0$ND[mday]";
}
$Fecha="$Anio-$Mes-$Dia";
//echo $Fecha;
//---------------------------Busca tipo vinculacion--------------------------------------------------
$ConsTipVinc="select tiposvinculacion.tipovinculacion from nomina.tiposvinculacion, nomina.contratos where tiposvinculacion.compania='$Compania[0]' and tiposvinculacion.compania=contratos.compania and contratos.identificacion='$Identificacion' and contratos.tipovinculacion=tiposvinculacion.codigo and contratos.fecinicio<='$FecVinF'";
//echo $ConsTipVinc."<br>";
$ResTipVinc=ExQuery($ConsTipVinc);
$fila=ExFetch($ResTipVinc);
$TipoVinc=$fila[0];
//$Numero=$fila[1];
//echo $TipoVinc;
//---------------------------Busca dias trabajados---------------------------------------------------							
$consdias="select concepto, diastr from nomina.diastrab,nomina.tiposvinculacion where tiposvinculacion.compania='$Compania[0]' and
diastrab.compania=tiposvinculacion.compania and identificacion='$Identificacion' and mestr='$Mes' and anio='$Anio' and
tiposvinculacion.codigo=diastrab.vinculacion";
//echo $consdias."<br>";
$resdias=ExQuery($consdias);
if(ExNumRows($resdias)>0)
{
	$filadias=ExFetch($resdias);
	${$filadias[0]}=$filadias[1];
	$DiasR=$filadias[1];
//	echo $filadias[0]." = ".$filadias[1]."<br>";
}
else
{
	$consdias="select concepto from nomina.conceptosliquidacion,nomina.tiposvinculacion,nomina.contratos where
	conceptosliquidacion.compania='$Compania[0]' and conceptosliquidacion.compania=tiposvinculacion.compania 
	and tiposvinculacion.compania=contratos.compania and contratos.identificacion='$Identificacion' 
	and contratos.tipovinculacion=tiposvinculacion.codigo and tiposvinculacion.tipovinculacion=conceptosliquidacion.tipovinculacion
	and diastr='1' ";
	$resdias=ExQuery($consdias);
	$filadias=ExFetch($resdias);
//	echo $consdias;
	${$filadias[0]}=30;
	$DiasR=30;
//	echo $filadias[0]." = ".${$filadias[0]}."<br>";
}
//---------------- Saca los Dias Trabajados	no esta listo--------------------------------------------
$consN="select novedades.concepto,dias from nomina.novedades,nomina.conceptosliquidacion where novedades.compania='$Compania[0]' and novedades.compania=conceptosliquidacion.compania and identificacion='$Identificacion' and conceptosliquidacion.novedad=novedades.novedad and conceptosliquidacion.concepto=novedades.concepto and mes='$Mes' and anio='$Anio'and claseconcepto='Dias'";
//echo $consN."<br>";
$resN=ExQuery($consN);
while($filaN=ExFetch($resN))
{
//	echo $filaN[0]."  -->  ".$filaN[1]."<br>";
	${$filadias[0]}=${$filadias[0]}-$filaN[1];
	${$filaN[0]}=$filaN[1];
//	echo $DiasTr;
}
//---------------- Salario Base del Trabajador------------------------------------------
$cons="select salario from nomina.salarios where compania='$Compania[0]' and identificacion='$Identificacion' and fecinicio<='$FecVinI' and fecfin>='$FecVinF'";
//echo $cons;
$res=ExQuery($cons);
$fila=ExFetch($res);
$SalBase=$fila[0];
//---------------- Mes de Liquidacion --------------------------------------------------
$cons="select mes from central.meses where numero=$Mes";
$res=ExQuery($cons);
$fila=ExFetch($res);
$MesL=$fila[0];
//---------------- Salario Minimo Anual-------------------------------------------------
$cons1="select salariomin from nomina.minimo where ano=$Anio";
$res1=ExQuery($cons1);
$fila1=ExFetch($res1);
$SalMinimo=$fila1[0];
//---------------activa el iniciar movimiento-------------------------------------------
$cons="select identificacion from nomina.nomina where compania='$Compania[0]' and identificacion='$Identificacion' and mes='$Mes' and anio='$Anio' and cierre is Null";
$res=ExQuery($cons);
$Ban=ExNumRows($res);
//-----------------Retirar Movimiento --------------------------------------------------
if($Mov==1)
{
	$cons="Delete from nomina.nomina where Compania='$Compania[0]' and identificacion='$Identificacion' and mes='$Mes' and anio='$Anio' and cierre is Null";					
	$res=ExQuery($cons);
	$Mov=0;
}
//----------------Iniciar Movimiento----------------------------------------------------
if($Mov==2)
{
//	echo "(".$Numero.")";
//	echo "hola";
//	echo "Hola<br>";
//	echo $Identificacion." --> ".$TipoVinc;
//----------------Consulta de Formula------------------------------------------------------------	
	$cons1="select movimiento,concepto,opera,arrastracon,claseconcepto,detconcepto,tipovinculacion,novedad from nomina.conceptosliquidacion where compania='$Compania[0]' and
	 tipoconcepto='Formula' and tipovinculacion='$TipoVinc' order by movimiento,concepto";
//	echo $cons1;
	$res=ExQuery($cons1);
	while($fila=Exfetch($res))
	{
		$Formula[$fila[0]][$fila[1]][$fila[6]]=array($fila[0],$fila[1],$fila[2],$fila[3],$fila[4],$fila[5],$fila[6]);
//		echo $fila[0]."-->".$fila[1]."-->".$fila[2]."-->".$fila[3]."-->".$fila[4]."-->".$fila[5]."-->".$fila[6]."<br>";
	} 
//---------------------------Porcentaje ARP----------------------------------------------
	$ConsPor="select arp from nomina.arpxc where compania='$Compania[0]' and identificacion='$Identificacion' and fecinicio<='$FecVinI' and (fecfin>='$FecVinF' or fecfin is null)";
	$ResPor=ExQuery($ConsPor);
	$fila=ExFetch($ResPor);
	$PorARP=$fila[0];
//	echo $PorARP;	
//-----------------------CONSULTA DE CONCEPTOS PROGRAMADOS
$consprog="select concepto,detconcepto,valor,movimiento,claseregistro,arrastracon,vinculacion from nomina.conceptosprogramados where compania='$Compania[0]' and identificacion='$Identificacion' and numero='$Numero' and fecinicio<'$Fecha' and fecfin>'$Fecha'";
//echo $consprog;
$resprog=ExQuery($consprog);
while($fila=ExFetch($resprog))
{
	$cons="insert into nomina.nomina values('$Compania[0]','$Identificacion','$Mes','$fila[0]','$fila[2]','$fila[3]','$fila[4]','$fila[1]','$fila[5]','$usuario[1]','$Anio','$Fec','$fila[6]','$Numero')";
//	echo $cons;
	$res=ExQuery($cons);
}
/*/-----------------------CONSULTA DE CONCEPTOS DE DESPROGRAMADOS
unset($Desprogramados);
$consdes="select identificacion,concepto,detconcepto,movimiento,claseregistro,arrastracon,vinculacion from nomina.conceptosdesprogramados where compania='$Compania[0]' and identificacion='$Identificacion' and numero='$Numero' and fecinicio<'$Fecha' and fecfin>'$Fecha'";
$resdes=ExQuery($consdes);
//echo $consdes."<br><br>";
while($filaD = ExFetch($resdes))
{
	$Desprogramados[$filaD[3]]=array($filaD[0],$filaD[1],$filaD[2],$filaD[3],$filaD[4],$filaD[5]);	
//	echo $filaD[0].$filaD[1].$filaD[2].$filaD[3].$filaD[4].$filaD[5]."<br>";
}
//echo $consdes."<br>";*/
//-----------------------FORMULAS DEVENGADOS------------------------------------------------------						
	foreach($Formula["Devengados"] as $Concepto)
	{
		foreach($Concepto as $Vinculacion)
		{
			$consdes="select identificacion,concepto,detconcepto,movimiento,claseregistro,arrastracon,vinculacion from nomina.conceptosdesprogramados where compania='$Compania[0]' and identificacion='$Identificacion' and concepto='$Vinculacion[1]' and numero='$Numero' and fecinicio<'$Fecha' and fecfin>'$Fecha'";
//			echo $consdes."<br>";
			$resdes=ExQuery($consdes);
			$cont=ExNumRows($resdes);
			if($cont==0)
			{
				eval("\$Valor=round($Vinculacion[2]);");
				$cons="insert into nomina.nomina(compania,identificacion,mes,concepto,valor,movimiento,anio,arrastracon,usuario,
						claseregistro,detconcepto,fecha,vinculacion,numero) values ('$Compania[0]','$Identificacion','$Mes','$Vinculacion[1]',
						'$Valor','$Vinculacion[0]','$Anio','$Vinculacion[3]','$usuario[1]','$Vinculacion[4]','$Vinculacion[5]','$Fec',
						'$Vinculacion[6]','$Numero')";
	//			echo $cons."<br>";
				$res=ExQuery($cons);
	//			echo $Vinculacion[7]."<br>";
				$TotDevengados=$TotDevengados+$Valor;
	//			echo $TotDevengados."<br>";
				${$Vinculacion[1]}=$Valor;
			}
/*			if($Desprogramados[$Vinculacion[0]])
			{ 
//				echo $Vinculacion[1];
				if($Vinculacion[1]!=$Desprogramados[$Vinculacion[0]][1])
				{
			//		echo $Desprogramados[0]."<--".$Vinculacion[0]."<br>";
					//echo "$I<br>";
					eval("\$Valor=round($Vinculacion[2]);");
					$cons="insert into nomina.nomina(compania,identificacion,mes,concepto,valor,movimiento,anio,arrastracon,usuario,
							claseregistro,detconcepto,fecha,vinculacion,numero) values ('$Compania[0]','$Identificacion','$Mes','$Vinculacion[1]',
							'$Valor','$Vinculacion[0]','$Anio','$Vinculacion[3]','$usuario[1]','$Vinculacion[4]','$Vinculacion[5]','$Fec',
							'$Vinculacion[6]','$Numero')";
		//			echo $cons."<br>";
					$res=ExQuery($cons);
		//			echo $Vinculacion[7]."<br>";
					$TotDevengados=$TotDevengados+$Valor;
		//			echo $TotDevengados."<br>";
					${$Vinculacion[1]}=$Valor;
				}
			}
			else
			{
				eval("\$Valor=round($Vinculacion[2]);");
				$cons="insert into nomina.nomina(compania,identificacion,mes,concepto,valor,movimiento,anio,arrastracon,usuario,
						claseregistro,detconcepto,fecha,vinculacion,numero) values ('$Compania[0]','$Identificacion','$Mes','$Vinculacion[1]',
						'$Valor','$Vinculacion[0]','$Anio','$Vinculacion[3]','$usuario[1]','$Vinculacion[4]','$Vinculacion[5]','$Fec',
						'$Vinculacion[6]','$Numero')";
	//			echo $cons."<br>";
				$res=ExQuery($cons);
	//			echo $Vinculacion[7]."<br>";
				$TotDevengados=$TotDevengados+$Valor;
	//			echo $TotDevengados."<br>";
				${$Vinculacion[1]}=$Valor;
			}*/
		}
	}
//-----------------------FORMULAS DEDUCIDOS------------------------------------------------------						
	foreach($Formula["Deducidos"] as $Concepto)
	{
		foreach($Concepto as $Vinculacion)
		{
			$consdes="select identificacion,concepto,detconcepto,movimiento,claseregistro,arrastracon,vinculacion from nomina.conceptosdesprogramados where compania='$Compania[0]' and identificacion='$Identificacion' and concepto='$Vinculacion[1]' and numero='$Numero' and fecinicio<'$Fecha' and fecfin>'$Fecha'";
//			echo $consdes."<br>";
			$resdes=ExQuery($consdes);
			$cont=ExNumRows($resdes);
//			echo $cont;
			if($cont==0)
			{
//				echo $Vinculacion[1]."--".$Desprogramados[$Vinculacion[0]][1]."    despro<br>";
				$Valor=round(eval("$Vinculacion[2];"));
				$cons="insert into nomina.nomina(compania,identificacion,mes,concepto,valor,movimiento,anio,usuario,claseregistro,detconcepto,arrastracon,fecha,vinculacion,numero)
				values('$Compania[0]','$Identificacion','$Mes','$Vinculacion[1]','$Valor','$Vinculacion[0]','$Anio','$usuario[1]','$Vinculacion[4]','$Vinculacion[5]',
				'$ConceptoS','$Fec','$Vinculacion[6]','$Numero')";
	//			echo $cons."<br>";
				$res=ExQuery($cons);
	//			$TotDeducidos=$TotDeducidos+$Valor;
				${$Concepto[1]}=$Valor;
			}
/*			if($Desprogramados[$Vinculacion[0]])
			{	
//				foreach($Desprogramados as $DesConcepto)
//				{
				if($Vinculacion[1]!=$Desprogramados[$Vinculacion[0]][1])
				{
					echo $Vinculacion[1]."--".$Desprogramados[$Vinculacion[0]][1]."    despro<br>";
					$Valor=round(eval("$Vinculacion[2];"));
					$cons="insert into nomina.nomina(compania,identificacion,mes,concepto,valor,movimiento,anio,usuario,claseregistro,detconcepto,arrastracon,fecha,vinculacion,numero)
					values('$Compania[0]','$Identificacion','$Mes','$Vinculacion[1]','$Valor','$Vinculacion[0]','$Anio','$usuario[1]','$Vinculacion[4]','$Vinculacion[5]',
					'$ConceptoS','$Fec','$Vinculacion[6]','$Numero')";
		//			echo $cons."<br>";
					$res=ExQuery($cons);
		//			$TotDeducidos=$TotDeducidos+$Valor;
					${$Concepto[1]}=$Valor;
				}
//				}
			}
			else
			{
					echo $Vinculacion[1]."--".$Desprogramados[$Vinculacion[0]][1]."normal<br>";
					$Valor=round(eval("$Vinculacion[2];"));
					$cons="insert into nomina.nomina(compania,identificacion,mes,concepto,valor,movimiento,anio,usuario,claseregistro,detconcepto,arrastracon,fecha,vinculacion,numero)
					values('$Compania[0]','$Identificacion','$Mes','$Vinculacion[1]','$Valor','$Vinculacion[0]','$Anio','$usuario[1]','$Vinculacion[4]','$Vinculacion[5]',
					'$ConceptoS','$Fec','$Vinculacion[6]','$Numero')";
		//			echo $cons."<br>";
					$res=ExQuery($cons);
		//			$TotDeducidos=$TotDeducidos+$Valor;
					${$Concepto[1]}=$Valor;
			}*/
		}
	}
//-----------------------FORMULAS POSTDEVENGADOS------------------------------------------------------						
	foreach($Formula["PostDevengados"] as $Concepto)
	{
		foreach($Concepto as $Vinculacion)
		{
			$consdes="select identificacion,concepto,detconcepto,movimiento,claseregistro,arrastracon,vinculacion from nomina.conceptosdesprogramados where compania='$Compania[0]' and identificacion='$Identificacion' and concepto='$Vinculacion[1]' and numero='$Numero' and fecinicio<'$Fecha' and fecfin>'$Fecha'";
//			echo $consdes."<br>";
			$resdes=ExQuery($consdes);
			$cont=ExNumRows($resdes);
//			echo $cont;
			if($cont==0)
			{
				$Valor=round(eval("$Vinculacion[2];"));
				$cons="insert into nomina.nomina(compania,identificacion,mes,concepto,valor,movimiento,anio,usuario,claseregistro,detconcepto,arrastracon,fecha,vinculacion,numero) values ('$Compania[0]','$Identificacion','$Mes','$Vinculacion[1]','$Valor','$Vinculacion[0]','$Anio','$usuario[1]','$Vinculacion[4]','$Vinculacion[5]','$ConceptoS','$Fec','$Vinculacion[6]','$Numero')";
	//			echo $cons."<br>";
				$res=ExQuery($cons);
	//			$TotDeducidos=$TotDeducidos+$Valor;
				${$Concepto[1]}=$Valor;

			}
/*			if($Desprogramados[$Vinculacion[0]])
			{	
				if($Vinculacion[1]!=$Desprogramados[$Vinculacion[0]][1])
				{
		//			echo $I."<br>";
					$Valor=round(eval("$Vinculacion[2];"));
					$cons="insert into nomina.nomina(compania,identificacion,mes,concepto,valor,movimiento,anio,usuario,claseregistro,detconcepto,arrastracon,fecha,vinculacion,numero) values ('$Compania[0]','$Identificacion','$Mes','$Vinculacion[1]','$Valor','$Vinculacion[0]','$Anio','$usuario[1]','$Vinculacion[4]','$Vinculacion[5]','$ConceptoS','$Fec','$Vinculacion[6]','$Numero')";
		//			echo $cons."<br>";
					$res=ExQuery($cons);
		//			$TotDeducidos=$TotDeducidos+$Valor;
					${$Concepto[1]}=$Valor;
				}
			}
			else
			{
//				echo $I."<br>";
				$Valor=round(eval("$Vinculacion[2];"));
				$cons="insert into nomina.nomina(compania,identificacion,mes,concepto,valor,movimiento,anio,usuario,claseregistro,detconcepto,arrastracon,fecha,vinculacion,numero) values ('$Compania[0]','$Identificacion','$Mes','$Vinculacion[1]','$Valor','$Vinculacion[0]','$Anio','$usuario[1]','$Vinculacion[4]','$Vinculacion[5]','$ConceptoS','$Fec','$Vinculacion[6]','$Numero')";
	//			echo $cons."<br>";
				$res=ExQuery($cons);
	//			$TotDeducidos=$TotDeducidos+$Valor;
				${$Concepto[1]}=$Valor;
			}*/
		}
	}
//----------------------- DEMAS FORMULAS POSTDEDUCIDOS------------------------------------------------------						
	foreach($Formula as $Movimiento)
	{
		foreach($Movimiento as $Concepto)
		{
			foreach($Concepto as $Vinculacion)
			{
				if($Vinculacion[0]!="Devengados"&&$Vinculacion[0]!="Deducidos"&&$Vinculacion[0]!="PostDevengados")
				{
					$consdes="select identificacion,concepto,detconcepto,movimiento,claseregistro,arrastracon,vinculacion from nomina.conceptosdesprogramados where compania='$Compania[0]' and identificacion='$Identificacion' and concepto='$Vinculacion[1]' and numero='$Numero' and fecinicio<'$Fecha' and fecfin>'$Fecha'";
		//			echo $consdes."<br>";
					$resdes=ExQuery($consdes);
					$cont=ExNumRows($resdes);
		//			echo $cont;
					if($cont==0)
					{
						$Valor=round(eval("$Vinculacion[2];"));
						$cons="insert into nomina.nomina(compania,identificacion,mes,concepto,valor,movimiento,anio,
						arrastracon,usuario,claseregistro,detconcepto,fecha,vinculacion,numero) values ('$Compania[0]','$Identificacion',
						'$Mes','$Vinculacion[1]','$Valor','$Vinculacion[0]','$Anio','$Vinculacion[3]','$usuario[1]',
						'$Vinculacion[4]','$Vinculacion[5]','$Fec','$Vinculacion[6]','$Numero')";
						$res=ExQuery($cons);
						${$Vinculacion[1]}=$Valor;	
		//				echo $Vinculacion[1]."  -->  ".$Valor."  -->  ".${$Vinculacion[1]}."<br>";
					}
/*					if($Desprogramados[$Vinculacion[0]])
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
			//				echo $Vinculacion[1]."  -->  ".$Valor."  -->  ".${$Vinculacion[1]}."<br>";
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
		//				echo $Vinculacion[1]."  -->  ".$Valor."  -->  ".${$Vinculacion[1]}."<br>";
					}*/
				}
			}
		}
	}
$TotDevengados="Null"; $TotDeducidos="Null";$Mov="Null";
}
//---------------- Boton Registrar -----------------------------------------------------
if($Registrar)
{
	${$ConceptoS}=$Valor;
//	echo $ConceptoS." --> ".$Valor." --> ".${$ConceptoS};
	$cons="select tipoconcepto,movimiento,detconcepto,claseconcepto from nomina.conceptosliquidacion where concepto='$ConceptoS'";
//	echo $cons;
	$res=ExQuery($cons);
	$fila=Exfetch($res);
//---------------inserta el concepto y su valor ---------------------------------
	$cons="insert into nomina.nomina(compania,identificacion,mes,concepto,valor,movimiento,anio,usuario,claseregistro,detconcepto,fecha,vinculacion,numero) values ('$Compania[0]','$Identificacion','$Mes','$ConceptoS','${$ConceptoS}','$fila[1]','$Anio','$usuario[1]','$fila[3]','$fila[2]','$Fec','$TipoVinc','$Numero')";
//	echo "hoa<br>";
//	echo $cons;
	$res=ExQuery($cons);
	$cons="select concepto,valor from nomina.nomina where compania='$Compania[0]' and identificacion='$Identificacion' and mes=$Mes and anio=$Anio and claseregistro!='Formula' and valor!='0'";
//	echo $cons;
	$res=ExQuery($cons);
	while($fila=Exfetch($res))
	{
		${$fila[0]}=$fila[1];
//		echo $fila[0]."=".$fila[1]."<br>";
	}
//---------------------------Porcentaje ARP----------------------------------------------
	$ConsPor="select arp from nomina.arpxc where compania='$Compania[0]' and identificacion='$Identificacion' and fecinicio<='$FecVinI' and (fecfin>='$FecVinF' or fecfin is null)";
	$ResPor=ExQuery($ConsPor);
	$fila=ExFetch($ResPor);
	$PorARP=$fila[0];
//	echo $PorARP;	
	
//----------------Consulta de Formula------------------------------------------------------------	
	$cons1="select movimiento,concepto,opera,arrastracon,claseconcepto,detconcepto,tipovinculacion from nomina.conceptosliquidacion where compania='$Compania[0]'
	and tipoconcepto='Formula' and tipovinculacion='$TipoVinc' order by movimiento,concepto";
//	echo $cons1."<br>";
	$res=ExQuery($cons1);
	while($fila=Exfetch($res))
	{
		$Formula[$fila[0]][$fila[1]][$fila[6]]=array($fila[0],$fila[1],$fila[2],$fila[3],$fila[4],$fila[5],$fila[6]);
		//echo $fila[0]."-->".$fila[1]."-->".$fila[2]."-->".$fila[3]."-->".$fila[4]."-->".$fila[5]."-->".$fila[6]."<br>";
	}
//-------------------------------------------------------------------------------------------------
	if($ConceptoS)
	{
		foreach($Formula as $Concepto)
		{
			foreach($Concepto as $Vinculacion)
			{
				foreach($Vinculacion as $fila)
				{
//					echo $ConceptoS." --> ".${$ConceptoS}."<br>";
//------------------movimiento devengados------------------------------------------------------
					if($fila[0]=="Devengados" && $fila[3]==$ConceptoS )
					{
//						echo $fila[2]."<br>";
						eval("\$Valor=round($fila[2]);");
						$cons="insert into nomina.nomina(compania,identificacion,mes,concepto,valor,movimiento,anio,usuario,claseregistro,detconcepto,arrastracon,fecha,vinculacion,numero) values ('$Compania[0]','$Identificacion','$Mes','$fila[1]','$Valor','$fila[0]','$Anio','$usuario[1]','$fila[4]','$fila[5]','$ConceptoS','$Fec','$fila[6]','$Numero')";
//						echo $cons."<br>";
						$res=ExQuery($cons);
//						$TotDevengados=$TotDevengados+$Valor;
						${$Concepto[1]}=$Valor;
//						echo ${$ConceptoS}."=".$ConceptoS."<br>";
					}
//-----------------movimiento Deducidos--------------------------------------------------------
					if($fila[0]=="Deducidos" && $fila[3]==$ConceptoS )
					{
//						echo "2.";
						$Valor=round(eval("$fila[2];"));
						$cons="insert into nomina.nomina(compania,identificacion,mes,concepto,valor,movimiento,anio,usuario,claseregistro,detconcepto,arrastracon,fecha,vinculacion,numero) values ('$Compania[0]','$Identificacion','$Mes','$fila[1]','$Valor','$fila[0]','$Anio','$usuario[1]','$fila[4]','$fila[5]','$ConceptoS','$Fec','$fila[6]','$Numero')";
//						echo $cons."<br>";
						$res=ExQuery($cons);
//						$TotDeducidos=$TotDeducidos+$Valor;
						${$Concepto[1]}=$Valor;
					}
//------------------movimiento postdevengados------------------------------------------------					
					if($fila[0]=="PostDevengados" && $fila[3]==$ConceptoS )
					{
//						echo "3.";
						$Valor=round(eval("$fila[2];"));
						$cons="insert into nomina.nomina(compania,identificacion,mes,concepto,valor,movimiento,anio,usuario,claseregistro,detconcepto,arrastracon,fecha,vinculacion,numero) values ('$Compania[0]','$Identificacion','$Mes','$fila[1]','$Valor','$fila[0]','$Anio','$usuario[1]','$fila[4]','$fila[5]','$ConceptoS','$Fec','$fila[6]','$Numero')";
//						echo $cons."<br>";
						$res=ExQuery($cons);
//						$TotDeducidos=$TotDeducidos+$Valor;
						${$Concepto[1]}=$Valor;
					}
//---------------movimiento postdeducidos-------------------------------------------------------
					if($fila[0]=="PostDeducidos" && $fila[3]==$ConceptoS )
					{
//						echo "4.";
						$Valor=round(eval("$fila[2];"));
						$cons="insert into nomina.nomina(compania,identificacion,mes,concepto,valor,movimiento,anio,usuario,claseregistro,detconcepto,arrastracon,fecha,vinculacion,numero) values ('$Compania[0]','$Identificacion','$Mes','$fila[1]','$Valor','$fila[0]','$Anio','$usuario[1]','$fila[4]','$fila[5]','$ConceptoS','$Fec','$fila[6]','$Numero')";
//						echo $cons."<br>";
						$res=ExQuery($cons);
//						$TotDeducidos=$TotDeducidos+$Valor;
						${$Concepto[1]}=$Valor;
					}					
//-----------------resto de movimientos-----------------------------------------------------------
					if($fila[0]!="Devengados" && $fila[0]!="Deducidos" && $fila[0]=="PostDevengados" && $fila[0]=="PostDeducidos" && $fila[3]==$ConceptoS)
					{
						$Valor=round(eval("$fila[2];"));
						$cons="insert into nomina.nomina(compania,identificacion,mes,concepto,valor,movimiento,anio,usuario,claseregistro,detconcepto,arrastracon,fecha,vinculacion,numero) values ('$Compania[0]','$Identificacion','$Mes','$fila[1]','$Valor','$fila[0]','$Anio','$usuario[1]','$fila[4]','$fila[5]','$ConceptoS','$Fec','$fila[6]','$Numero')";
//						echo $cons."<br>";
						$res=ExQuery($cons);
//						$TotDeducidos=$TotDeducidos+$Valor;
						${$Concepto[1]}=$Valor;
					}
//					echo $TotDevengados."<br>".$TotDeducidos."<br>".$TotPostDevengados."<br>".$TotPostDeducidos;
				}
			}
		}
	}
	$Mov=0;	
}
//-----------------Activar o desactivar boton Iniciar o retirar Movimiento--------------
$cons="select identificacion from nomina.nomina where compania='$Compania[0]' and identificacion='$Identificacion' and mes='$Mes' and anio='$Anio'";
$res=ExQuery($cons);
$Ban=ExNumRows($res);
//echo $Ban;
//echo $TipoVinc;
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<script language="javascript" src="/Funciones.js"></script>
<script language="javascript">
function ValidarRet()
{
   if(confirm("Esta seguro de Retirar el movimiento?")){document.FORMA.Mov.value=1;FORMA.submit();}
}
function ValidarIni()
{
   document.FORMA.Mov.value=2;FORMA.submit();
}

</script>
</head>
<body background="/Imgs/Fondo.jpg">
<form name="FORMA" method="post">
<input type="hidden" name="Identificacion" value="<? echo "$Identificacion";?>" >
<input type="hidden" name="DatNameSID" value="<? echo $DatNameSID?>">
<input type="hidden" name="Anio" value="<? echo "$Anio";?>" >
<input type="hidden" name="Mes" value="<? echo "$Mes";?>" >
<input type="hidden" name="Movimiento">
<input type="hidden" name="SalBase" value="<? echo "$SalBase";?>">
<input type="hidden" name="SalMinimo" value="<? echo "$SalMinimo";?>">
<input type="hidden" name="Borrar">
<input type="hidden" name="TotDeducidos" value="<? echo $TotDeducidos;?>" />
<input type="hidden" name="TotDevengados" value="<? echo $TotDevengados;?>" />
<input type="hidden" name="Mov" value="<? echo "$Mov";?>" >
<input type="hidden" name="ConceptoS" value="<? echo $ConceptoS;?>" />
<input type="hidden" name="Valor" value="<? echo $Valor;?>">
<input type="hidden" name="Numero" value="<? echo $Numero;?>">
<input type="hidden" name="Fecha" value="<? echo $Fecha;?>">
<table border="1" bordercolor="#e5e5e5" style='font : normal normal small-caps 11px Tahoma;' align="center" >
<tr><td colspan="4" bgcolor="#666699" style="color:white" align="center">Mes A Liquidar</td><td align="center">Mes</td>
	<td><select name="Mes" onChange="FORMA.submit();" >
                    <?
                    $cons = "select numero,mes from central.meses";
                    $resultado = ExQuery($cons);
                    while ($fila = ExFetch($resultado))
                    {                        
						 if($fila[0]==$Mes)
						 {
							 echo "<option value='$fila[0]' selected>$fila[1]</option>"; 
						 }
						 else{echo "<option value='$fila[0]'>$fila[1]</option>";}						 
                    }
				?>
            </select></td>
	<td align="center">Año</td>
    <td> <select name="Anio" onChange="FORMA.submit();" value="<? echo $Anio?>">
    <?
		$consA="Select anio from central.anios where compania='$Compania[0]' order by anio desc";
		 $resultadoA = ExQuery($consA);
        while ($filaA = ExFetch($resultadoA))
            {                        
			if($filaA[0]==$Anio)
				{
					echo "<option value='$filaA[0]' selected>$filaA[0]</option>"; 
				}
			else{echo "<option value='$filaA[0]'>$filaA[0]</option>";}						 
            }
	?>
	</select></td>
</tr>
</table>
<!-----------------------------Tabla Inicial de datos------------------------------------------------>
<table border="1" bordercolor="#e5e5e5" style='font : normal normal small-caps 11px Tahoma;' align="center" >    
<tr><td colspan="6" bgcolor="#666699" style="color:white" align="center" >Datos A Liquidar</td></tr>
<tr><td style='font : normal normal small-caps 11px Tahoma;' align="center">Concepto</td>
	<td><select name="ConceptoS" onChange="FORMA.submit()">
            <option ></option>
                    <?
                    $cons = "select concepto,detconcepto from nomina.conceptosliquidacion where tipoconcepto='Campo' and claseconcepto!='Dias' and tipovinculacion='$TipoVinc' and concepto not in (select concepto from nomina.nomina where Compania='$Compania[0]' and Identificacion='$Identificacion' and Mes=$Mes and Anio=$Anio) order by detconcepto";
					echo $cons;
                    $resultado = ExQuery($cons);
                    while ($fila = ExFetch($resultado))
                    {                        
						if($ConceptoS==$fila[0])
                        {
                            echo "<option value='$fila[0]' selected>$fila[1]</option>";
                        }
                        else
                        {
                            echo "<option value='$fila[0]'>$fila[1]</option>";
                        }
                    }
				?>
            </select></td>
     <td style='font : normal normal small-caps 11px Tahoma;' align="center">Valor</td>
     <td><input type="text" name="Valor" onKeyDown="xNumero(this)" onKeyUp="xNumero(this)" ></td></tr>
     <tr>
     <td>Mes Liquidacion : </td><td><input type="text" name="MesL" value="<? echo "$MesL";?>" disabled /></td>
     <td>Dias Reportados : </td><td><input type="text" name="DiasR" value="<? echo "$DiasR";?>" disabled/></td>
     </tr>
	<tr>
    <td>Salario Basico : </td><td><input type="text" name="SalBase" value="<? echo "$SalBase";?>" disabled/></td>
    <td>Salario Minimo : </td><td><input type="text" name="SalMinimo" value="<? echo "$SalMinimo";?>" disabled/></td>
    </tr>    
    <tr>
    <td colspan="4"><center><input type="submit" name="Registrar" value="Registrar" <? if($Ban==0){echo "disabled";} ?> ><input type="button" name="Movimiento"  <? if($Ban==0){echo "value='Iniciar Movimiento'"; $Mov=1; echo "onclick='return ValidarIni();'"; } else{ echo "value='Retirar Movimiento'"; $Mov=0; echo "onClick='return ValidarRet();'";}?>/></center>
    </tr>
</table>
<!--------------------------------------------------------------------------->    
<table border="1" bordercolor="#e5e5e5" style='font : normal normal small-caps 11px Tahoma;' align="center"/>
    <tr>
<!-----------------------------inicio de Variables---------------------------------------->    
<? if($Retirar)
{
	$TotDevengados=0;
	$TotDeducidos=0;
	$TotPostDevengados=0;
	$TotPostDeducidos=0;
}
?>
<!---------------------------Tabla Devengados---------------------------------------------- -->    
    <td valign="top">
    	<table  style='font : normal normal small-caps 11px Tahoma;' style="width:100%" />
    	<tr><td colspan="3" bgcolor="#666699" style="color:white" align="center">DEVENGADOS</td></tr>
    	<tr><td bgcolor="#666699" style="color:white" align="center"></td><td bgcolor="#666699" style="color:white" align="center">Concepto</td><td bgcolor="#666699" style="color:white" align="center">Valor</td>
       </tr>
       <?
	   $cons="select detconcepto,valor,arrastracon,concepto from nomina.nomina where compania='$Compania[0]' and identificacion='$Identificacion' and mes='$Mes' and anio='$Anio' and movimiento='Devengados' and valor!=0 and claseregistro!='Cantidad'";
//		echo $cons;
		$res=ExQuery($cons);
		while($fila=ExFetch($res))
		{
			$consNov="select concepto,dias from nomina.novedades where compania='$Compania[0]' and identificacion='$Identificacion' and anio='$Anio' and mes='$Mes'";
			$resNov=ExQuery($consNov);
			while($filaNov=ExFetch($resNov))
			{
//				echo $fila[2]." = ".$filaNov[0].$filaNov[1];
				if($fila[2]==$filaNov[0])
				{ 
					//--datos q ya se mostraron
					if(empty($MatDatMos[$fila[0]]))
					{
						$MatDatMos[$fila[0]]=$fila[0];
					?>
					<tr><td><? //echo $fila[2]?></td><td><? echo $fila[0]."(".$filaNov[1]." dias)";?></td><td align="right"><? echo "$ ";echo number_format($fila[1],0,'','.');?></td></tr>
                    <?
					}
				}
			}
			if($fila[2]==$filadias[0])
			{
				if(empty($MatDatMos[$fila[0]]))
				{
					$MatDatMos[$fila[0]]=$fila[0];
				?>
            	<tr><td><? //echo $fila[2]?></td><td><? echo $fila[0]."(".${$filadias[0]}." dias)";?></td><td align="right"><? echo "$ ";echo number_format($fila[1],0,'','.');?></td></tr>
            <?
				}
			}
			$consHor="select concepto, valor from nomina.nomina where compania='$Compania[0]' and identificacion='$Identificacion' and anio = '$Anio' and mes = '$Mes' and claseregistro='Cantidad'";
			$resHor=ExQuery($consHor);
			while($filaHor=ExFetch($resHor))
			{
				if($fila[2]==$filaHor[0])
				{ 
					if(empty($MatDatMos[$fila[0]]))
					{
						$MatDatMos[$fila[0]]=$fila[0];
					?>
					<tr><td><? //echo $fila[2]?></td><td><? echo $fila[0]."(".$filaHor[1]." Horas)";?></td><td align="right"><? echo "$ "; echo number_format($fila[1],0,'','.');?></td></tr>
                    <?
					}
				}
			}
			if(empty($MatDatMos[$fila[0]]))
			{
				$MatDatMos[$fila[0]]=$fila[0];
				?>
				<tr><td><? //echo $fila[2]?></td><td><? echo $fila[0];?></td><td align="right"><? echo "$ ";echo number_format($fila[1],0,'','.');?></td></tr> 
				
				<?
			}
			$TotDevengados=$TotDevengados+$fila[1];
		}
		if($TotDevengados>0){
	   ?>
           <tr><td colspan="3" align="center" style="font:bold" bgcolor="#D2C5DA"><? echo "Total Devengados : $ ".number_format($TotDevengados,0,'','.');?></td></tr>
           <? }?>
        </table>
    </td>
<!---------------------------Tabla Deducidos------------------------------------------------>    
    <td valign="top">
    	<table  style='font : normal normal small-caps 11px Tahoma;' style="width:100%"/>
    	<tr><td colspan="3" bgcolor="#666699" style="color:white" align="center">DEDUCIDOS</td></tr>
    	<tr><td bgcolor="#666699" style="color:white" align="center"></td><td bgcolor="#666699" style="color:white" align="center">Concepto</td><td bgcolor="#666699" style="color:white" align="center">Valor</td>
       </tr>
       <?
	   $cons="select detconcepto,valor,arrastracon,concepto from nomina.nomina where compania='$Compania[0]' and identificacion='$Identificacion' and mes='$Mes' and anio='$Anio' and movimiento='Deducidos' and valor!=0 and claseregistro!='Cantidad'";
//		echo $cons;
		$res=ExQuery($cons);
		while($fila=ExFetch($res))
		{
			?>
            
            <tr><td><? //echo $fila[2]?></td><td><? echo $fila[0];?></td><td align="right"><? echo "$ ".number_format($fila[1],0,'','.');?></td></tr>
            <?
		$TotDeducidos=$TotDeducidos+$fila[1];
		}
		if($TotDeducidos>0)
		{
	   ?>
           <tr><td colspan="3" align="center" style="font:bold" bgcolor="#D2C5DA"><? echo "Total Deducidos : $ ".number_format($TotDeducidos,0,'','.');?></td></tr>
           <? }?>
        </table>
    </td>
<!---------------------------Tabla Parafiscales---------------------------------------------->    
    <td bgcolor="#666699">&nbsp;&nbsp;</td>
    <td valign="top">
    	<table  style='font : normal normal small-caps 11px Tahoma;' style="width:100%"/>
    	<tr><td colspan="3" bgcolor="#666699" style="color:white" align="center">PARAFISCALES</td></tr>
    	<tr><td bgcolor="#666699" style="color:white" align="center"></td><td bgcolor="#666699" style="color:white" align="center">Concepto</td><td bgcolor="#666699" style="color:white" align="center">Valor</td>
       </tr>
       <?
	   $cons="select detconcepto,valor,arrastracon,concepto from nomina.nomina where compania='$Compania[0]' and identificacion='$Identificacion' and mes='$Mes' and anio='$Anio' and movimiento='Parafiscales' and valor!=0";
//		echo $cons;
		$res=ExQuery($cons);
		while($fila=ExFetch($res))
		{
			?>
            
            <tr><td><? //echo $fila[2]?></td><td><? echo $fila[0];?></td><td align="right"><? echo "$ ".number_format($fila[1],0,'','.');?></td></tr>
            <?
		}
	   ?>
        </table>
	</td>
<!--------------------------Tabla Prestaciones--------------------------------------------->    
    <td valign="top">
    	<table  style='font : normal normal small-caps 11px Tahoma;' style="width:100%"/>
    	<tr><td colspan="3" bgcolor="#666699" style="color:white" align="center">PROVISIONES</td></tr>
    	<tr><td bgcolor="#666699" style="color:white" align="center"></td><td bgcolor="#666699" style="color:white" align="center">Concepto</td><td bgcolor="#666699" style="color:white" align="center">Valor</td>
       </tr>
       <?
	   $cons="select detconcepto,valor,arrastracon,concepto from nomina.nomina where compania='$Compania[0]' and identificacion='$Identificacion' and mes='$Mes' and anio='$Anio' and movimiento='Provisiones Prestaciones' and valor!=0";
//		echo $cons;
		$res=ExQuery($cons);
		while($fila=ExFetch($res))
		{
			?>
            
            <tr><td><? //echo $fila[2]?></td><td><? echo $fila[0];?></td><td align="right"><? echo "$ ".number_format($fila[1],0,'','.');?></td></tr>
            <?
		}
	   ?>
        </table>
	</td>
</tr>
<tr>
<!------------------------Tabla PostDevengados---------------------------------------------->
    <td valign="top">
    	<table  style='font : normal normal small-caps 11px Tahoma;' style="width:100%"/>
    	<tr><td colspan="3" bgcolor="#666699" style="color:white" align="center">POSTDEVENGADOS</td></tr>
    	<tr><td bgcolor="#666699" style="color:white" align="center"></td><td bgcolor="#666699" style="color:white" align="center">Concepto</td><td bgcolor="#666699" style="color:white" align="center">Valor</td>
       </tr>
       <?
	   $cons="select detconcepto,valor,arrastracon,concepto from nomina.nomina where compania='$Compania[0]' and identificacion='$Identificacion' and mes='$Mes' and anio='$Anio' and movimiento='PostDevengados' and valor!=0";
//		echo $cons;
		$res=ExQuery($cons);
		while($fila=ExFetch($res))
		{
			?>
            
            <tr><td><? //echo $fila[2]?></td><td><? echo $fila[0];?></td><td align="right"><? echo "$ ".number_format($fila[1],0,'','.');?></td></tr>
            <?
		$TotPostDevengados=$TotPostDevengados+$fila[1];
		}
		if($TotPostDevengados>0)
		{
	   ?>
           <tr><td colspan="3" align="center" style="font:bold" bgcolor="#D2C5DA"><? echo "Total PostDevengados : $ ".number_format($TotPostDevengados,0,'','.');?></td></tr>
           <? }?>
        </table>
	</td>
<!------------------------Tabla PostDeducidos---------------------------------------------->
    <td valign="top">
    	<table  style='font : normal normal small-caps 11px Tahoma;' style="width:100%"/>
    	<tr><td colspan="3" bgcolor="#666699" style="color:white" align="center">POSTDEDUCIDOS</td></tr>
    	<tr><td bgcolor="#666699" style="color:white" align="center"></td><td bgcolor="#666699" style="color:white" align="center">Concepto</td><td bgcolor="#666699" style="color:white" align="center">Valor</td>
       </tr>
       <?
	   $cons="select detconcepto,valor,arrastracon,concepto from nomina.nomina where compania='$Compania[0]' and identificacion='$Identificacion' and mes='$Mes' and anio='$Anio' and movimiento='PostDeducidos' and valor!=0";
//		echo $cons;
		$res=ExQuery($cons);
		while($fila=ExFetch($res))
		{
			?>
            
            <tr><td><? //echo $fila[2]?></td><td><? echo $fila[0];?></td><td align="right"><? echo "$ ".number_format($fila[1],0,'','.');?></td></tr>
            <?
		$TotPostDeducidos=$TotPostDeducidos+$fila[1];
		}
		if($TotPostDeducidos>0)
		{
	   ?>
           <tr><td colspan="3" align="center" style="font:bold" bgcolor="#D2C5DA"><? echo "Total PostDeducidos : $ ".number_format($TotPostDeducidos,0,'','.');?></td></tr>
           <? }?>
        </table>
	</td>
<td bgcolor="#666699">&nbsp;</td>
<!------------------------Tabla Provisiones------------------------------------------------>
    <td valign="top">
    	<table  style='font : normal normal small-caps 11px Tahoma;' style="width:100%"/>
    	<tr><td colspan="3" bgcolor="#666699" style="color:white" align="center">APORTES PATRONALES</td></tr>
    	<tr><td bgcolor="#666699" style="color:white" align="center"></td><td bgcolor="#666699" style="color:white" align="center">Concepto</td><td bgcolor="#666699" style="color:white" align="center">Valor</td>
       </tr>
       <?
	   $cons="select detconcepto,valor,arrastracon,concepto from nomina.nomina where compania='$Compania[0]' and identificacion='$Identificacion' and mes='$Mes' and anio='$Anio' and movimiento='ProvisionesSS' and valor!=0";
//		echo $cons;
		$res=ExQuery($cons);
		while($fila=ExFetch($res))
		{
			?>
            <tr><td><? //echo $fila[2]?></td><td><? echo $fila[0];?></td><td align="right"><? echo "$ ".number_format($fila[1],0,'','.');?></td></tr>
            <?
		}
		if($Registrar)
		{
			$cons="select concepto,valor from nomina.nomina where compania='$Compania[0]' and identificacion='$Identificacion' and mes=$Mes and anio=$Anio and claseregistro!='Formula' and valor!='0'";
//	echo $cons."<br>";
			$res=ExQuery($cons);
			while($fila=Exfetch($res))
			{
				${$fila[0]}=$fila[1];
		//		echo $fila[0]."=".$fila[1]."<br>";
			}
			foreach($Formula as $Concepto)
			{
				foreach($Concepto as $Vinculacion)
				{
					foreach($Vinculacion as $fila)
					{
						if($fila[0]!="Devengados")
						{
//							echo $fila[0];
							$Valor=round(eval("$fila[2];"));
							$cons="update nomina.nomina set valor='$Valor',fecha='$Fec',usuario='$usuario[1]' where compania='$Compania[0]' and identificacion='$Identificacion' and mes='$Mes'and concepto='$fila[1]' and movimiento='$fila[0]' and anio='$Anio' and claseregistro='$fila[4]' and detconcepto='$fila[5]'";
//							echo $cons."<br><br><br>";
							$res=ExQuery($cons);
							${$fila[1]}=$Valor;				
		//					$TotDeducidos=$TotDeducidos+$Valor;					
						}
					}
				}
			}
			?>
            <script language="javascript">location.href="NominaPersonal.php?DatNameSID=<? echo $DatNameSID?>&Identificacion=<? echo $Identificacion?>";</script>
            <?
//			echo $TotDevengados;
		}
	   ?>
        </table>
	</td>
<!-------------------------Tabla Afectacion Contable---------------------------------------->    
    <td>
    	<table  style='font : normal normal small-caps 11px Tahoma;' style="width:100%"/>
    	<tr><td colspan="3" bgcolor="#666699" style="color:white" align="center">AFECTACION CONTABLE</td></tr>
    	<tr ><td><input type="text" name="AfeContable" style="width:100%"></td></tr>
        </table>
	</td>
<!------------------------Fin Tablas----------------------------------------------------->    
    </tr>
</form>
	<tr><td colspan="5" bgcolor="#E0E0E0" align="center" style="font:bold;font-size:14px" >Neto A Pagar : $ <? $Neto=$TotDevengados+$TotPostDevengados-$TotDeducidos-$TotPostDeducidos;?><font size="+1"><? echo number_format($Neto,0,'','.');?></font></td></tr>
</table>
<center><input type="button" name="Comprobante" value="Comprobante" 
onClick="open('ComproPersonal.php?DatNameSID=<? echo $DatNameSID?>&Identificacion=<? echo $Identificacion?>&Vinculacion=<? echo $TipoVinc?>&Numero=<? echo $Numero?>&Mes=<? echo $Mes?>&Anio=<? echo $Anio?>','Comprobante','outerWidth=400,outerHeight=400,menubar,scrollbars=yes')"></center>
</body>
</html>