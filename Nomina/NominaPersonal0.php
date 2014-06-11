<?php
if($DatNameSID){session_name("$DatNameSID");}
session_start();
include("Funciones.php");
/*global $SalBase;
global $SalMinimo;
global $TotDevengados;
global $TotDeducidos;
global $Identificacion;*/
//global $Concepto;
$TotDevengados=0;
$TotDeducidos=0;
$ND=getdate();
$Fec="$ND[year]-$ND[mon]-$ND[mday] $ND[hours]:$ND[minutes]:$ND[seconds]";
$DiasTr=30;
$NoHorasLab=240;
if(!$Anio)$Anio="$ND[year]";
if(!$Mes)$Mes="$ND[mon]";
//----------------------------------------------------------------------------------------
$cons="select concepto from nomina.conceptosliquidacion where compania='$Compania[0]' and claseconcepto='Dias'";
	//echo $cons."<br>";
	$res=ExQuery($cons);
	while($fila=ExFetch($res))
	{
		$consB="select dias from nomina.novedades where compania='$Compania[0]' and identificacion='$Identificacion' and mes='$Mes' and anio='$Anio' and concepto='$fila[0]'";
		//echo $consB."<br>";
		$resB=ExQuery($consB);
		while($filaB=ExFetch($resB))
		{
			$DiasTr=$DiasTr-$filaB[0];
			//echo $DiasTr;
		}
	}
//====----------------acomodar------------------------------------------------------------
if($Borrar==1)
{	
//	echo "jborra<br>";
	$TotDevengados=0;
	$TotDeducidos=0;
	$cons="delete from nomina.nomina where concepto='$ConceptoBrr' and compania='$Compania[0]' and identificacion='$Identificacion' and mes='$Mes' and anio='$Anio'";
	$res=ExQuery($cons);		
	$cons="select concepto,valor from nomina.nomina where compania='$Compania[0]' and identificacion='$Identificacion' and mes=$Mes and anio=$Anio and claseregistro!='AutoRegistro'";
	$res=ExQuery($cons);
	//echo $cons;
	while($fila=Exfetch($res))
	{
		//echo $SalMinimo." ".$SalBase." ".${$fila[0]}."<br>";
		${$fila[0]}=$fila[1];
	}
// OJO TIPO DE VINCULACION
	$cons1="select movimiento,concepto,opera,arrastracon,claseconcepto,detconcepto from nomina.conceptosliquidacion where compania='$Compania[0]'
and tipoconcepto='Formula' order by movimiento,concepto";
//	echo $cons1;
	$res=ExQuery($cons1);
	while($fila=Exfetch($res))
	{
		$Formula[$fila[0]][$fila[1]]=array($fila[0],$fila[1],$fila[2],$fila[3],$fila[4],$fila[5]);			
	}
//---------------------------------------------------------
	foreach($Formula["Devengados"] as $Concepto)
						{
							eval("\$Valor=round($Concepto[2]);");
							
								$cons="update nomina.nomina set valor='$Valor',fecha='$Fec'  where compania='$Compania[0]' and identificacion='$Identificacion' and mes='$Mes'and concepto='$Concepto[1]' and movimiento='$Concepto[0]' and anio='$Anio' and arrastracon='$Concepto[3]' and usuario='$usuario[1]' and claseregistro='$Concepto[4]' and detconcepto='$Concepto[5]'";
								$res=ExQuery($cons);
								//echo $cons."<br>";
								$TotDevengados=$TotDevengados+$Valor;
								${$Concepto[1]}=$Valor;
							
						}
	//-----------------------FORMULAS DEDUCIDOS------------------------------------------------------
						foreach($Formula["Deducidos"] as $Concepto)
						{
							$Valor=round(eval("$Concepto[2];"));
							
								$cons="update nomina.nomina set valor='$Valor',fecha='$Fec' where compania='$Compania[0]' and identificacion='$Identificacion' and mes='$Mes'and concepto='$Concepto[1]' and movimiento='$Concepto[0]' and anio='$Anio' and arrastracon='$Concepto[3]' and usuario='$usuario[1]' and claseregistro='$Concepto[4]' and detconcepto='$Concepto[5]'";
								$res=ExQuery($cons);
								${$Concepto[1]}=$Valor;				
								$TotDeducidos=$TotDeducidos+$Valor;					
							
						}
//----------------------FORMULAS POSTDEVENGADOS------------------------------------------------------------------							
						foreach($Formula["PostDevengados"] as $Concepto)
						{
							$Valor=round(eval("$Concepto[2];"));
							
								$cons="update nomina.nomina set valor='$Valor',fecha='$Fec' where compania='$Compania[0]' and identificacion='$Identificacion' and mes='$Mes'and concepto='$Concepto[1]' and movimiento='$Concepto[0]' and anio='$Anio' and arrastracon='$Concepto[3]' and usuario='$usuario[1]' and claseregistro='$Concepto[4]' and detconcepto='$Concepto[5]'";
								$res=ExQuery($cons);
								${$Concepto[1]}=$Valor;				
//								$TotDevengados=$TotDevengados+$Valor;					
							
						}
//----------------------FORMULAS POSTDEDUCIDOS ------------------------------------------------------------------							
						foreach($Formula["PostDeducidos"] as $Concepto)
						{
							$Valor=round(eval("$Concepto[2];"));
							
								$cons="update nomina.nomina set valor='$Valor',fecha='$Fec' where compania='$Compania[0]' and identificacion='$Identificacion' and mes='$Mes'and concepto='$Concepto[1]' and movimiento='$Concepto[0]' and anio='$Anio' and arrastracon='$Concepto[3]' and usuario='$usuario[1]' and claseregistro='$Concepto[4]' and detconcepto='$Concepto[5]'";
								$res=ExQuery($cons);
								${$Concepto[1]}=$Valor;				
//								$TotDeducidos=$TotDeducidos+$Valor;					
							
						}
						
	//----------------------FORMULAS------------------------------------------------------------------							
						foreach($Formula as $Movimiento)
						{
							foreach($Movimiento as $Concepto)
							{
								if($Concepto[0]!="Devengados"&&$Concepto[0]!="Deducidos"&&$Concepto[0]!="PostDevengados"&&$Concepto[0]!="PostDeducidos")
								{
									$Valor=round(eval("$Concepto[2];"));
									
										$cons="update nomina.nomina set valor='$Valor',fecha='$Fec' where compania='$Compania[0]' and identificacion='$Identificacion' and mes='$Mes'and concepto='$Concepto[1]' and movimiento='$Concepto[0]' and anio='$Anio' and arrastracon='$Concepto[3]' and usuario='$usuario[1]' and claseregistro='$Concepto[4]' and detconcepto='$Concepto[5]'";
										$res=ExQuery($cons);
										${$Concepto[1]}=$Valor;	
									
								}
							}
						}	
//	echo $cons;*/
$Borrar="";
}
//--------------------------------------------------------------------------------------
if($Registrar)
{
//	echo "registra<br>";
	${$ConceptoS}=$Valor;
	$TotDevengados=0;
	$TotDeducidos=0;
//	echo $Concepto." --> ".${$Concepto}."<br>";
	$cons="select tipoconcepto,movimiento,detconcepto,claseconcepto from nomina.conceptosliquidacion where concepto='$ConceptoS'";
//	echo $cons;
	$res=ExQuery($cons);
	$fila=Exfetch($res);
	$cons="insert into nomina.nomina(compania,identificacion,mes,concepto,valor,movimiento,anio,usuario,claseregistro,detconcepto,fecha) values ('$Compania[0]','$Identificacion','$Mes','$ConceptoS','${$ConceptoS}','$fila[1]','$Anio','$usuario[1]','$fila[3]','$fila[2]','$Fec')";
//	echo $cons;
	$res=ExQuery($cons);
	$cons="select concepto,valor from nomina.nomina where compania='$Compania[0]' and identificacion='$Identificacion' and mes=$Mes and anio=$Anio and claseregistro!='Formula'";
	$res=ExQuery($cons);
	while($fila=Exfetch($res))
	{
		${$fila[0]}=$fila[1];
	}
// OJO TIPO VINCULACION
	$cons1="select movimiento,concepto,opera,arrastracon,claseconcepto,detconcepto from nomina.conceptosliquidacion where compania='$Compania[0]'
and tipoconcepto='Formula' order by movimiento,concepto";
//	echo $cons1;
	$res=ExQuery($cons1);
	while($fila=Exfetch($res))
	{
		$Formula[$fila[0]][$fila[1]]=array($fila[0],$fila[1],$fila[2],$fila[3],$fila[4],$fila[5]);			
	}
//---------------------------------------------------------
					if(!$Concepto)
					{
						foreach($Formula["Devengados"] as $Concepto)
						{
							eval("\$Valor=round($Concepto[2]);");
							
								$cons="update nomina.nomina set valor='$Valor',fecha='$Fec' where compania='$Compania[0]' and identificacion='$Identificacion' and mes='$Mes'and concepto='$Concepto[1]' and movimiento='$Concepto[0]' and anio='$Anio' and arrastracon='$Concepto[3]' and usuario='$usuario[1]' and claseregistro='$Concepto[4]' and detconcepto='$Concepto[5]'";
								$res=ExQuery($cons);
								$TotDevengados=$TotDevengados+$Valor;
								${$Concepto[1]}=$Valor;
							
						}
					}
	//-----------------------FORMULAS DEDUCIDOS------------------------------------------------------
						if(!$Concepto)
						{
							foreach($Formula["Deducidos"] as $Concepto)
							{
								$Valor=round(eval("$Concepto[2];"));
								
									$cons="update nomina.nomina set valor='$Valor',fecha='$Fec' where compania='$Compania[0]' and identificacion='$Identificacion' and mes='$Mes'and concepto='$Concepto[1]' and movimiento='$Concepto[0]' and anio='$Anio' and arrastracon='$Concepto[3]' and usuario='$usuario[1]' and claseregistro='$Concepto[4]' and detconcepto='$Concepto[5]'";
									$res=ExQuery($cons);
									${$Concepto[1]}=$Valor;				
									$TotDeducidos=$TotDeducidos+$Valor;					
								
							}
						}
//----------------------FORMULAS POSTDEVENGADOS------------------------------------------------------------------							
						if(!$Concepto)
						{
							foreach($Formula["PostDevengados"] as $Concepto)
							{
								$Valor=round(eval("$Concepto[2];"));
								
									$cons="update nomina.nomina set valor='$Valor',fecha='$Fec' where compania='$Compania[0]' and identificacion='$Identificacion' and mes='$Mes'and concepto='$Concepto[1]' and movimiento='$Concepto[0]' and anio='$Anio' and arrastracon='$Concepto[3]' and usuario='$usuario[1]' and claseregistro='$Concepto[4]' and detconcepto='$Concepto[5]'";
									$res=ExQuery($cons);
									${$Concepto[1]}=$Valor;				
	//								$TotDevengados=$TotDevengados+$Valor;					
								
							}
						}
//----------------------FORMULAS POSTDEDUCIDOS ------------------------------------------------------------------							
						if(!$Concepto)
						{
							foreach($Formula["PostDeducidos"] as $Concepto)
							{
								$Valor=round(eval("$Concepto[2];"));
								
									$cons="update nomina.nomina set valor='$Valor',fecha='$Fec' where compania='$Compania[0]' and identificacion='$Identificacion' and mes='$Mes'and concepto='$Concepto[1]' and movimiento='$Concepto[0]' and anio='$Anio' and arrastracon='$Concepto[3]' and usuario='$usuario[1]' and claseregistro='$Concepto[4]' and detconcepto='$Concepto[5]'";
									$res=ExQuery($cons);
									${$Concepto[1]}=$Valor;				
	//								$TotDeducidos=$TotDeducidos+$Valor;					
								
							}
						}
						
	//----------------------FORMULAS------------------------------------------------------------------							
						foreach($Formula as $Movimiento)
						{
							if(!$Concepto)
							{
								foreach($Movimiento as $Concepto)
								{
									if($Concepto[0]!="Devengados"&&$Concepto[0]!="Deducidos"&&$Concepto[0]!="PostDevengados"&&$Concepto[0]!="PostDeducidos")
									{
										$Valor=round(eval("$Concepto[2];"));
										
											$cons="update nomina.nomina set valor='$Valor',fecha='$Fec' where compania='$Compania[0]' and identificacion='$Identificacion' and mes='$Mes'and concepto='$Concepto[1]' and movimiento='$Concepto[0]' and anio='$Anio' and arrastracon='$Concepto[3]' and usuario='$usuario[1]' and claseregistro='$Concepto[4]' and detconcepto='$Concepto[5]'";
											$res=ExQuery($cons);
											${$Concepto[1]}=$Valor;	
										
									}
								}
							}
						}	
//	echo $cons;
}
//=-----------------------------------\
if($Mov==1)
{
	$cons="Delete from nomina.nomina where Compania='$Compania[0]' and identificacion='$Identificacion' and mes='$Mes' and anio='$Anio'";					
	$res=ExQuery($cons);
	$Mov=0;
}
//=------------------------------------------------------------------

if($Mov==2)
{
	$TotDevengados=0;
	$TotDeducidos=0;
	$cons1="select movimiento,concepto,opera,arrastracon,claseconcepto,detconcepto from nomina.conceptosliquidacion where compania='$Compania[0]'
and tipoconcepto='Formula' order by movimiento,concepto";
//	echo $cons1;
	$res=ExQuery($cons1);
	while($fila=Exfetch($res))
	{
		$Formula[$fila[0]][$fila[1]]=array($fila[0],$fila[1],$fila[2],$fila[3],$fila[4],$fila[5]);			
	}
//=---------------------------------------------------------------------------
	$cons="select terceros.identificacion,mesi,mesf,salarios.salario,terceros.primnom,terceros.segnom,terceros.primape,terceros.segape from central.terceros,nomina.salarios 
	where terceros.compania='$Compania[0]' and terceros.compania=salarios.compania and terceros.identificacion='$Identificacion' and terceros.identificacion=salarios.identificacion and terceros.tipo='Empleado' 
	and salarios.anio='$Anio' and salarios.mesi<='$Mes' and salarios.mesf>='$Mes'";
	//echo $cons;
	$res=ExQuery($cons);
	while($fila=Exfetch($res))
	{
		$Empleados[$fila[0]][$fila[1]][$fila[2]][$fila[3]]=array($fila[0],$fila[1],$fila[2],$fila[3],$fila[4],$fila[5],$fila[6],$fila[7]);			
	}
	$cont=0;
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
						$Identificacion=$Sal[0];
						$TotDevengados=0;
						$TotDeducidos=0;
						$Nombre=$Sal[4]." ".$Sal[5]." ".$Sal[6]." ".$Sal[7];
						$cont++;
	//-----------------------FORMULAS DEVENGADOS------------------------------------------------------						
						if($Formula["Devengados"])
						{
							foreach($Formula["Devengados"] as $Concepto)
							{
								eval("\$Valor=round($Concepto[2]);");
								
									$cons="insert into nomina.nomina(compania,identificacion,mes,concepto,valor,movimiento,anio,arrastracon,usuario,claseregistro,detconcepto,fecha) values ('$Compania[0]','$Identificacion','$Mes','$Concepto[1]','$Valor','$Concepto[0]','$Anio','$Concepto[3]','$usuario[1]','$Concepto[4]','$Concepto[5]','$Fec')";
									$res=ExQuery($cons);
									$TotDevengados=$TotDevengados+$Valor;
									${$Concepto[1]}=$Valor;
								
							}
						}
	//-----------------------FORMULAS DEDUCIDOS------------------------------------------------------
						if($Formula["Deducidos"])
						{
							foreach($Formula["Deducidos"] as $Concepto)
							{
								$Valor=round(eval("$Concepto[2];"));
								
									$cons="insert into nomina.nomina(compania,identificacion,mes,concepto,valor,movimiento,anio,arrastracon,usuario,claseregistro,detconcepto,fecha) values ('$Compania[0]','$Identificacion','$Mes','$Concepto[1]','$Valor','$Concepto[0]','$Anio','$Concepto[3]','$usuario[1]','$Concepto[4]','$Concepto[5]','$Fec')";
									$res=ExQuery($cons);
									${$Concepto[1]}=$Valor;				
									$TotDeducidos=$TotDeducidos+$Valor;					
								
							}
						}
//----------------------FORMULAS POSTDEVENGADOS------------------------------------------------------------------							
						if($Formula["PostDevengados"])
						{
							foreach($Formula["PostDevengados"] as $Concepto)
							{
								$Valor=round(eval("$Concepto[2];"));
								
									$cons="insert into nomina.nomina(compania,identificacion,mes,concepto,valor,movimiento,anio,arrastracon,usuario,claseregistro,detconcepto,fecha) values ('$Compania[0]','$Identificacion','$Mes','$Concepto[1]','$Valor','$Concepto[0]','$Anio','$Concepto[3]','$usuario[1]','$Concepto[4]','$Concepto[5]','$Fec')";
									$res=ExQuery($cons);
									${$Concepto[1]}=$Valor;				
	//								$TotDevengados=$TotDevengados+$Valor;					
								
							}
						}
//----------------------FORMULAS POSTDEDUCIDOS ------------------------------------------------------------------							
						if($Formula["PostDeducidos"])
						{
							foreach($Formula["PostDeducidos"] as $Concepto)
							{
								$Valor=round(eval("$Concepto[2];"));
								
									$cons="insert into nomina.nomina(compania,identificacion,mes,concepto,valor,movimiento,anio,arrastracon,usuario,claseregistro,detconcepto,fecha) values ('$Compania[0]','$Identificacion','$Mes','$Concepto[1]','$Valor','$Concepto[0]','$Anio','$Concepto[3]','$usuario[1]','$Concepto[4]','$Concepto[5]','$Fec')";
									$res=ExQuery($cons);
									${$Concepto[1]}=$Valor;				
	//								$TotDeducidos=$TotDeducidos+$Valor;					
								
							}
						}
//----------------------FORMULAS------------------------------------------------------------------							
						foreach($Formula as $Movimiento)
						{
							foreach($Movimiento as $Concepto)
							{
								if($Concepto[0]!="Devengados"&&$Concepto[0]!="Deducidos"&&$Concepto[0]!="PostDevengados"&&$Concepto[0]!="PostDeducidos")
								{
									$Valor=round(eval("$Concepto[2];"));
									
										$cons="insert into nomina.nomina(compania,identificacion,mes,concepto,valor,movimiento,anio,arrastracon,usuario,claseregistro,detconcepto,fecha) values ('$Compania[0]','$Identificacion','$Mes','$Concepto[1]','$Valor','$Concepto[0]','$Anio','$Concepto[3]','$usuario[1]','$Concepto[4]','$Concepto[5]','$Fec')";
										$res=ExQuery($cons);
										${$Concepto[1]}=$Valor;	
									
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
		echo "<center>No existe registro de empleados para el año $Anio</center>";	
	}
	$Mov=0;
}
//=-----------------------------------------------
$cons="select salario from nomina.salarios where compania='$Compania[0]' and identificacion='$Identificacion' and anio='$Anio' and mesi<='$Mes' and mesf>='$Mes'";
$res=ExQuery($cons);
$fila=ExFetch($res);
$SalBase=$fila[0];
//=------------------------------------
$cons="select mes from central.meses where numero=$Mes";
$res=ExQuery($cons);
$fila=ExFetch($res);
$MesL=$fila[0];
//=---------------------------------------------
$cons="select identificacion from nomina.nomina where compania='$Compania[0]' and identificacion='$Identificacion' and mes='$Mes' and anio='$Anio'";
$res=ExQuery($cons);
$Ban=ExNumRows($res);
//=-----------------------------------
$cons1="select salariomin from nomina.minimo where ano=$Anio";
$res1=ExQuery($cons1);
$fila1=ExFetch($res1);
$SalMinimo=$fila1[0];

//=---------------------------------------------

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
<input type="hidden" name="Mov" value="<? echo "$Mov";?>" >
<input type="hidden" name="Movimiento">
<input type="hidden" name="SalBase" value="<? echo "$SalBase";?>">
<input type="hidden" name="SalMinimo" value="<? echo "$SalMinimo";?>">
<input type="hidden" name="TotDevengados">
<input type="hidden" name="TotDeducidos">
<input type="hidden" name="Borrar">
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
    <td><? echo $Anio?></td></tr>
    </table>
<!--    -->
<table border="1" bordercolor="#e5e5e5" style='font : normal normal small-caps 11px Tahoma;' align="center" >    
<tr><td colspan="6" bgcolor="#666699" style="color:white" align="center" >Datos A Liquidar</td></tr>
<tr><td style='font : normal normal small-caps 11px Tahoma;' align="center">Concepto</td>
	<td><select name="ConceptoS" >
            <option ></option>
                    <?
                    $cons = "select concepto,detconcepto from nomina.conceptosliquidacion where tipoconcepto='Campo' and claseconcepto!='Dias' and concepto not in (select concepto from nomina.nomina where Compania='$Compania[0]' and Identificacion='$Identificacion' and Mes=$Mes and Anio=$Anio) order by detconcepto";
					//echo $cons;
                    $resultado = ExQuery($cons,$conex);
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
     <td><input type="text" name="Valor" onKeyDown="xNumero(this)" onKeyUp="xNumero(this)"></td></tr>
     <tr><td>Mes Liquidacion : </td><td><input type="text" name="MesL" value="<? echo "$MesL";?>" disabled /></td><td>Dias Reportados : </td><td><input type="text" name="Diastr" value="<? echo "$DiasTr";?>" disabled/></td></tr>
	<tr><td>Salario Basico : </td><td><input type="text" name="SalBase" value="<? echo "$SalBase";?>" disabled/></td><td>Salario Minimo : </td><td><input type="text" name="SalMinimo" value="<? echo "$SalMinimo";?>" disabled/></td></tr>    
    <tr><td colspan="4"><center><input type="submit" name="Registrar" value="Registrar" <? if($Ban==0){echo "disabled";}?> ><input type="button" name="Movimiento"  <? if($Ban==0){echo "value='Iniciar Movimiento'"; $Mov=0; echo "onclick='return ValidarIni();'"; } else{ echo "value='Retirar Movimiento'"; $Mov=0; echo "onClick='return ValidarRet();'";}?>/></center></tr>
<!--------------------------------------------------------------------------->    
	</table>
    <table border="1" bordercolor="#e5e5e5" style='font : normal normal small-caps 11px Tahoma;' align="center"/>
    </tr>
    <!--------------------------------------------------->
    <tr><td valign="top">
    <table  style='font : normal normal small-caps 11px Tahoma;' style="width:100%"/>
    <tr><td colspan="3" bgcolor="#666699" style="color:white" align="center">DEVENGADOS</td></tr>
    <tr><td bgcolor="#666699" style="color:white" align="center"></td><td bgcolor="#666699" style="color:white" align="center">Concepto</td><td bgcolor="#666699" style="color:white" align="center">Valor</td></tr>
    <?
	$TotDevengados=0;
	$TotDeducidos=0;
	$cons="select detconcepto,valor,arrastracon,concepto from nomina.nomina where compania='$Compania[0]' and identificacion='$Identificacion' and mes='$Mes' and anio='$Anio' and movimiento='Devengados' and valor!=0 and claseregistro!='Cantidad'";
	echo $cons;
	$res=ExQuery($cons);
	while($fila=ExFetch($res))
	{
		$cons="select valor,detconcepto,concepto,arrastracon from nomina.nomina where compania='$Compania[0]' and identificacion='$Identificacion' and mes='$Mes' and anio='$Anio' and movimiento='Devengados' and concepto='$fila[2]'";
		$resA=ExQuery($cons);
		$filaA=ExFetch($resA);
//		echo $cons;		
		$consB="select dias from nomina.novedades where compania='$Compania[0]' and identificacion='$Identificacion' and mes='$Mes' and anio='$Anio' and concepto='$fila[2]'";
		$resB=ExQuery($consB);
		$filaB=ExFetch($resB);
		if($fila){
			if($fila[0]=='Sueldo')
			{$MsjAdc=" $fila[0]($DiasTr)";}
			else
			{$MsjAdc=" $fila[0]($filaB[0])";}}
		if($Fondo==1){$BG="#D5D5D5";$Fondo=0;}
		else{$BG="#e5e5e5";$Fondo=1;}
		echo "<tr bgcolor='$BG'><td>";
//		echo $fila1[2];
		if($fila[2]){$ConBorra=$fila[2]; if($filaA){$Preg=$filaA[1]; $Preg1=$filaA[2];}else{$Preg=$fila[0]; $Preg1=$fila[3];}}else{$ConBorra=$fila[3];$Preg=$fila[0];$Preg1=$fila[3];}
		if(!$fila[2]||$fila[2]==$filaA[2])
		{
		?>        
	           <a onClick="if(confirm('Eliminar este concepto <? echo $Preg." ".$Preg1?>?')==false){return false;}" href="NominaPersonal.php?DatNameSID=<? echo $DatNameSID?>&Borrar=1&ConceptoBrr=<? echo $ConBorra?>&Identificacion=<? echo $Identificacion;?>&SalBase=<? echo $SalBase?>&SalMinimo=<? echo $SalMinimo?>&Mes=<? echo $Mes?>"><img src="/Imgs/Nomina/b_drop.png" border="0"></a>
         <? 		
		}
		else
		{
			echo "&nbsp;&nbsp;";	
		}
		echo "</td><td height='9'> $MsjAdc</td><td align='right'>" . number_format($fila[1],0) . "</td></tr>";
		$MsjAdc="";
		$TotDevengados=$TotDevengados+$fila[1];					
	}
?></table></td>
	
	<!------------------------------------------------------------------->
	<td valign="top">
    <table style='font : normal normal small-caps 11px Tahoma;' style="width:100%">
    <tr><td colspan="3" bgcolor="#666699" style="color:white" align="center">DEDUCIDOS</td></tr>
    <tr><td bgcolor="#666699" style="color:white" align="center"></td>
    <td bgcolor="#666699" style="color:white" align="center" >Concepto</td><td bgcolor="#666699" style="color:white" align="center">Valor</td><tr>
    <?
		
	 $cons="select detconcepto,valor from nomina.nomina where compania='$Compania[0]' and identificacion='$Identificacion' and mes='$Mes' and anio='$Anio' and movimiento='Deducidos' and valor!=0";
//	echo $cons;
	$res=ExQuery($cons);
	while($fila=ExFetch($res))
	{
		$cons1="select detconcepto,valor,concepto from nomina.nomina where compania='$Compania[0]' and identificacion='$Identificacion' and mes='$Mes' and anio='$Anio' and movimiento='Deducidos' ";
		$res1=ExQuery($cons1);
		$fila1=ExFetch($res1);
		$cont1=ExNumRows($res1);
		//echo $cont1;
		if($fila1){$MsjAdc=" $fila[0]";}
		if($Fondo==1){$BG="#D5D5D5";$Fondo=0;}
		else{$BG="#e5e5e5";$Fondo=1;}
		echo "<tr bgcolor='$BG'><td>";
		if($habilitado>=1)
			{ 	
				echo"<img src='/Imgs/Nomina/b_drop.png' border='0'></a>";
			}
            else
            {?>
	           <a onClick="if(confirm('Eliminar este concepto?')==false){return false;}"href="NominaPersonal.php?DatNameSID=<? echo $DatNameSID?>&Borrar=1&Concepto=<? echo $fila1[2]?>&Identificacion=<? echo $Identificacion;?>"><img src="/Imgs/Nomina/b_drop.png" border="0"></a>
         <? }
		echo "</td><td height='9'>$fila[7] $MsjAdc</td><td align='right'>" . number_format($fila[1],0) . "</td></tr>";
		$MsjAdc="";
		$TotDeducidos=$TotDeducidos+$fila[1];					
	}

?>
    </table>
    </td>
    </tr>
	<tr align="center" bgcolor="#6666AA" style="color:white"><td>Total Devengados : <? echo $TotDevengados ?></td><td>Total Deducidos : <? echo $TotDeducidos?></td></tr>
    <tr>
<!-------------------------------------------------------------->    
    <td valign="top">
     <table style='font : normal normal small-caps 11px Tahoma;' style="width:100%">
    <tr><td colspan="3" bgcolor="#666699" style="color:white" align="center">POSTDEVENGADOS</td></tr>
    <tr><td bgcolor="#666699" style="color:white" align="center"></td>
    <td bgcolor="#666699" style="color:white" align="center" >Concepto</td><td bgcolor="#666699" style="color:white" align="center">Valor</td><tr>
    <? $cons="select detconcepto,valor from nomina.nomina where compania='$Compania[0]' and identificacion='$Identificacion' and mes='$Mes' and anio='$Anio' and movimiento='PostDevengados' and valor!=0";
//	echo $cons;
	$res=ExQuery($cons);
	while($fila=ExFetch($res))
	{
		$cons1="select detconcepto,valor from nomina.nomina where compania='$Compania[0]' and identificacion='$Identificacion' and mes='$Mes' and anio='$Anio' and movimiento='PostDevengados'";
		$res1=ExQuery($cons1);
		$fila1=ExFetch($res1);
		$cont1=ExNumRows($res1);
		//echo $cont1;
		if($fila1){$MsjAdc=" $fila[0]";}
		if($Fondo==1){$BG="#D5D5D5";$Fondo=0;}
		else{$BG="#e5e5e5";$Fondo=1;}
		echo "<tr bgcolor='$BG'><td>";
		if($habilitado>=1)
			{ 	
				echo"<img src='/Imgs/Nomina/b_drop.png' border='0'></a>";
			}
            else
            {?>
	           <a onClick="if(confirm('Eliminar este concepto?')==false){return false;}" href="DatNomina.php?DatNameSID=<? echo $DatNameSID?>&Borrar=1&Concepto=<?echo $fila[3]?>&Arrastra=<?echo $fila[8]?>"><img src="/Imgs/Nomina/b_drop.png" border="0"></a>
         <? }
		echo "</td><td height='9'>$fila[7] $MsjAdc</td><td align='right'>" . number_format($fila[1],0) . "</td></tr>";
		$MsjAdc="";
		$TotPostDevengados=$TotPostDevengados+$fila[1];
	}
?>
    </table>
    </td>
    <!--------------------------------------------------------------------->
    <td valign="top">
     <table style='font : normal normal small-caps 11px Tahoma;' style="width:100%">
    <tr><td colspan="3" bgcolor="#666699" style="color:white" align="center">POSTDEDUCIDOS</td></tr>
    <tr><td bgcolor="#666699" style="color:white" align="center"></td>
    <td bgcolor="#666699" style="color:white" align="center" >Concepto</td><td bgcolor="#666699" style="color:white" align="center">Valor</td><tr>
    <? $cons="select detconcepto,valor from nomina.nomina where compania='$Compania[0]' and identificacion='$Identificacion' and mes='$Mes' and anio='$Anio' and movimiento='PostDeducidos' and valor!=0";
//	echo $cons;
	$res=ExQuery($cons);
	while($fila=ExFetch($res))
	{
		$cons1="select detconcepto,valor from nomina.nomina where compania='$Compania[0]' and identificacion='$Identificacion' and mes='$Mes' and anio='$Anio' and movimiento='PostDeducidos'";
		$res1=ExQuery($cons1);
		$fila1=ExFetch($res1);
		$cont1=ExNumRows($res1);
		//echo $cont1;
		if($fila1){$MsjAdc=" $fila[0]";}
		if($Fondo==1){$BG="#D5D5D5";$Fondo=0;}
		else{$BG="#e5e5e5";$Fondo=1;}
		echo "<tr bgcolor='$BG'><td>";
		if($habilitado>=1)
			{ 	
				echo"<img src='/Imgs/Nomina/b_drop.png' border='0'></a>";
			}
            else
            {?>
	           <a onClick="if(confirm('Eliminar este concepto?')==false){return false;}" href="DatNomina.php?DatNameSID=<? echo $DatNameSID?>&Borrar=1&Concepto=<?echo $fila[3]?>&Arrastra=<?echo $fila[8]?>"><img src="/Imgs/Nomina/b_drop.png" border="0"></a>
         <? }
		echo "</td><td height='9'>$fila[7] $MsjAdc</td><td align='right'>" . number_format($fila[1],0) . "</td></tr>";
		$MsjAdc="";
		$TotPostDeducidos=$TotPostDeducidos+$fila[1];					
	}
?>
    </table>
    </td>
    <!--------------------------------------------------------------------->    
    </tr>
	<tr align="center" bgcolor="#6666AA" style="color:white"><td>Total PostDevengados : <? echo $TotPostDevengados ?></td><td>Total PostDeducidos : <? echo $TotPostDeducidos?></td></tr>			    
    <tr>
    <td bgcolor="#666699" style="color:white" align="center" colspan="2">Neto a Pagar</td>
    </tr>
    <tr>
    <td colspan="2" align="center"><? $Neto=$TotDevengados-$TotDeducidos+$TotPostDevengados-$TotPostDeducidos; echo $TotDevengados." - ".$TotDeducidos." + ".$TotPostDevengados." - ".$TotPostDeducidos." = ".$Neto;?></td>
    </tr>
        <tr>
<!-------------------------------------------------------------->    
    <td valign="top">
     <table style='font : normal normal small-caps 11px Tahoma;' style="width:100%">
    <tr><td colspan="3" bgcolor="#666699" style="color:white" align="center">PARAFISCALES</td></tr>
    <td bgcolor="#666699" style="color:white" align="center" >Concepto</td><td bgcolor="#666699" style="color:white" align="center">Valor</td><tr>
    <? $cons="select detconcepto,valor from nomina.nomina where compania='$Compania[0]' and identificacion='$Identificacion' and mes='$Mes' and anio='$Anio' and movimiento='Parafiscales' and valor!=0";
//	echo $cons;
	$res=ExQuery($cons);
	while($fila=ExFetch($res))
	{
		$cons1="select detconcepto,valor from nomina.nomina where compania='$Compania[0]' and identificacion='$Identificacion' and mes='$Mes' and anio='$Anio' and movimiento='Parafiscales'";
		$res1=ExQuery($cons1);
		$fila1=ExFetch($res1);
		$cont1=ExNumRows($res1);
		//echo $cont1;
		if($fila1){$MsjAdc=" $fila[0]";}
		if($Fondo==1){$BG="#D5D5D5";$Fondo=0;}
		else{$BG="#e5e5e5";$Fondo=1;}
		echo "<tr bgcolor='$BG'>";
		
		echo "<td height='9'>$fila[7] $MsjAdc</td><td align='right'>" . number_format($fila[1],0) . "</td></tr>";
		$MsjAdc="";
		$ParaFiscales=$ParaFiscales+$fila[1];					
	}
?>
    </table>
    </td>
    <!--------------------------------------------------------------------->
    <td valign="top">
     <table style='font : normal normal small-caps 11px Tahoma;' style="width:100%">
    <tr><td colspan="3" bgcolor="#666699" style="color:white" align="center">PRESTACIONES</td></tr>
    <td bgcolor="#666699" style="color:white" align="center" >Concepto</td><td bgcolor="#666699" style="color:white" align="center">Valor</td><tr>
    <? $cons="select detconcepto,valor from nomina.nomina where compania='$Compania[0]' and identificacion='$Identificacion' and mes='$Mes' and anio='$Anio' and movimiento='Provisiones Prestaciones' and valor!=0";
//	echo $cons;
	$res=ExQuery($cons);
	while($fila=ExFetch($res))
	{
		$cons1="select detconcepto,valor from nomina.nomina where compania='$Compania[0]' and identificacion='$Identificacion' and mes='$Mes' and anio='$Anio' and movimiento='Provisiones Prestaciones'";
		$res1=ExQuery($cons1);
		$fila1=ExFetch($res1);
		$cont1=ExNumRows($res1);
		//echo $cont1;
		if($fila1){$MsjAdc=" $fila[0]";}
		if($Fondo==1){$BG="#D5D5D5";$Fondo=0;}
		else{$BG="#e5e5e5";$Fondo=1;}
		echo "<tr bgcolor='$BG'>";
		echo "<td height='9'>$fila[7] $MsjAdc</td><td align='right'>" . number_format($fila[1],0) . "</td></tr>";
		$MsjAdc="";
		$Prestaciones=$Prestaciones+$fila[1];					
	}
?>
    </table>
    </td>
    <!--------------------------------------------------------------------->    
    </tr>
   	<tr align="center" bgcolor="#6666AA" style="color:white"><td>Total ParaFiscales : <? echo $ParaFiscales ?></td><td>Total Prestaciones : <? echo $Prestaciones?></td></tr>			    
    <tr>
<!-------------------------------------------------------------->    
    <td valign="top">
     <table style='font : normal normal small-caps 11px Tahoma;' style="width:100%">
    <tr><td colspan="3" bgcolor="#666699" style="color:white" align="center">PROVISIONES</td></tr>
    <td bgcolor="#666699" style="color:white" align="center" >Concepto</td><td bgcolor="#666699" style="color:white" align="center">Valor</td><tr>
    <? $cons="select detconcepto,valor from nomina.nomina where compania='$Compania[0]' and identificacion='$Identificacion' and mes='$Mes' and anio='$Anio' and movimiento='ProvisionesSS' and valor!=0";
//	echo $cons;
	$res=ExQuery($cons);
	while($fila=ExFetch($res))
	{
		$cons1="select detconcepto,valor from nomina.nomina where compania='$Compania[0]' and identificacion='$Identificacion' and mes='$Mes' and anio='$Anio' and movimiento='ProvisionesSS'";
		$res1=ExQuery($cons1);
		$fila1=ExFetch($res1);
		$cont1=ExNumRows($res1);
		//echo $cont1;
		if($fila1){$MsjAdc=" $fila[0]";}
		if($Fondo==1){$BG="#D5D5D5";$Fondo=0;}
		else{$BG="#e5e5e5";$Fondo=1;}
		echo "<tr bgcolor='$BG'>";
		echo "<td height='11'>$fila[7] $MsjAdc</td><td align='right'>" . number_format($fila[1],0) . "</td></tr>";
		$MsjAdc="";
		$Provisiones=$Provisiones+$fila[1];					
	}
?>
    </table>
    </td>
    <!--------------------------------------------------------------------->
    <td >
     <table style='font : normal normal small-caps 11px Tahoma;' style="width:100%">
    <tr ><td bgcolor="#666699" style="color:white" align="center">AFECTACION CONTABLE</td></tr>
    <tr ><td><input type="text" name="AfeContable" style="width:100%"></td></tr>
     </table>
    </td>
    <!--------------------------------------------------------------------->    
    </tr>
	<tr align="center" bgcolor="#6666AA" style="color:white"><td>Total Provisiones : <? echo $Provisiones ?></td></tr>			    
    </table>
</body>
</html>