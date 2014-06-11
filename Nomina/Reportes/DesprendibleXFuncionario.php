<?
if($DatNameSID){session_name("$DatNameSID");}
session_start();
include("Funciones.php");
$ND=getdate();
if("$Mes"<10)
{
	$Mes="0$Mes";
}
if("$ND[mday]"<10)
{
	$Dia="0$ND[mday]";
}
if(!$Vinculacion==""){$Vin=" and tiposvinculacion.tipovinculacion='$Vinculacion'";}
$Fecha=$Anio."-".$Mes."-".$Dia;
require('LibPDF/fpdf.php');
$cons="select terceros.identificacion,contratos.fecinicio,contratos.fecfin,terceros.primnom,terceros.segnom,terceros.primape,terceros.segape, 
	tiposvinculacion.tipovinculacion,contratos.numero,cargos.cargo from central.terceros,nomina.contratos,nomina.tiposvinculacion,nomina.cargos,nomina.nomina where terceros.compania='$Compania[0]' and
	 terceros.identificacion=contratos.identificacion and contratos.tipovinculacion=tiposvinculacion.codigo and (terceros.tipo='Empleado' or regimen='Empleado') and
	  contratos.estado='Activo' and contratos.cargo=cargos.codigo $Vin group by terceros.identificacion,contratos.fecinicio,contratos.fecfin,terceros.primnom,terceros.segnom,terceros.primape,terceros.segape, 
	  tiposvinculacion.tipovinculacion,contratos.numero,cargos.cargo order by primape";
//echo $cons."<br>";
$res=ExQuery($cons);
while($fila=Exfetch($res))
{
	$Empleados[$fila[0]][$fila[1]][$fila[2]][$fila[3]][$fila[8]]=array($fila[0],$fila[1],$fila[2],$fila[3],$fila[4],$fila[5],$fila[6],$fila[7],$fila[8],$fila[9],$fila[10]);			
}
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<script language='javascript' src="/Funciones.js"></script>
</head>
<body background="/Imgs/Fondo.jpg">
<form name="FORMA" method="post">
<? if(!$Pdf)
{
?>
<center>
<input type="button" name="PDF" value="COMPROBANTES" onClick="open('DesprendibleXFuncionario.php?DatNameSID=<? echo $DatNameSID?>&Identificacion=<? echo $Identificacion?>&Vinculacion=<? echo $TipoVinc?>&Numero=<? echo $Numero?>&Mes=<? echo $Mes?>&Anio=<? echo $Anio?>&Pdf=1','Comprobante','outerWidth=400,outerHeight=400,menubar=yes,scrollbars=yes')">
</center>
<?
}
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
//						echo $Vinc[0]." -- ".$Vinc[1]." -- ".$Vinc[2]." -- ".$Vinc[3]." -- ".$Vinc[4]." -- ".$Vinc[5]." -- ".$Vinc[6]." -- ".$Vinc[7]." -- ".$Vinc[8]." -- ".$Vinc[9]." -- ".$Vinc[10];
						$consvin="select codigo from nomina.tiposvinculacion where compania='$Compania[0]' and tipovinculacion='$Vinc[7]'";
						$resvin=ExQuery($consvin);
						$filavin=ExFetch($resvin);
						$conscar="select cargo from nomina.cargos where compania='$Compania[0]' and vinculacion='$filavin[0]' and codigo='$Vinc[9]'";
						$rescar=ExQuery($conscar);
						$filacar=Exfetch($rescar);
//						echo $conscar."<br>";
						$conssal="select salario from nomina.salarios where compania='$Compania[0]' and identificacion='$Vinc[0]' and fecinicio<='$Fecha' and fecfin>='$Fecha'
						and numcontrato='$Vinc[8]'";
						$ressal=ExQuery($conssal);
						$filasal=ExFetch($ressal);
						$cons="select identificacion from nomina.nomina where compania='$Compania[0]' and identificacion='$Vinc[0]' and mes='$Mes' and anio='$Anio'";
						$res=ExQuery($cons);
						$cont=ExNumRows($res);
						$consmes="select mes from central.meses where numero='$Mes'";
						$resmes=ExQuery($consmes);
						$fila=ExFetch($resmes);
						$NomMes=$fila[0];
//						echo $cons;
						if($cont>0)
						{
							$TotDevengados=0;$TotDeducidos=0;$TotPostDevengados=0;$TotPostDeducidos=0;
							?>
                            <table border="0" bordercolor="#e5e5e5" style="font : normal normal small-caps 11px Tahoma; width:100%" align="center" />
                            	<tr>
                            		<td colspan="2">
                                        <img border="0" src="/Imgs/Logo.jpg" style="width:42; height:56" align="right" />
                                                <font style="font-size:12px"/><? echo strtoupper($Compania[0]."<br>DESPRENDIBLE DE PAGO - PERIODO:  ".$NomMes."/".$Anio);?>
                                            <br><br><font style="font-weight:bold"><? echo $Vinc[3]." ".$Vinc[4]." ".$Vinc[5]." ".$Vinc[6]." - ".$Vinc[0];?></font>&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;SALARIO BASE: <? echo number_format($filasal[0],0,'','.');?><br /><font style="font-weight:bold"><? echo $Vinc[9];?></font>
                                    </td>
                                </tr>
                                <!---------------------------Tabla Devengados---------------------------------------------- -->    
                            	<tr>
                            		<td valign="top">
		                                <table border="1" cellpadding="1" cellspacing="0" style="font : normal normal small-caps 11px Tahoma; width:100%">
                                        	<tr>
                                            	<td colspan="2" align="center">DEVENGADOS</td>
                                            </tr>
                                            <tr>
                                            	<td align="center">Concepto</td><td style="width:70px" align="center">Valor</td>
                                            </tr>
                            				<?
											$cons="select detconcepto,valor,arrastracon,concepto from nomina.nomina where compania='$Compania[0]' and identificacion='$Vinc[0]' 
											and mes='$Mes' and anio='$Anio' and movimiento='Devengados' and valor!=0 and claseregistro!='Cantidad'";
				//							echo $cons;
											$res=ExQuery($cons);
											while($fila=ExFetch($res))
											{
											?>
												<tr onMouseOver="this.bgColor='#AAD4FF'" onMouseOut="this.bgColor=''">
                                                	<td><? echo strtoupper($fila[0]);?></td><td align="right"><? echo "$ ".number_format($fila[1],0,'','.');?></td>
                                                </tr>
											<?
												$TotDevengados=$TotDevengados+$fila[1];
											}
											?>
                                        </table>
									</td>
					<!---------------------------Tabla Deducidos------------------------------------------------>    
									<td valign="top">
										<table border="1" cellpadding="1" cellspacing="0" style="font : normal normal small-caps 11px Tahoma; width:100%"/>
											<tr>
                                            	<td colspan="2" align="center">DEDUCIDOS</td>
                                            </tr>
											<tr>
                                            	<td align="center">Concepto</td><td style="width:70px" align="center">Valor</td>
						   					</tr>
						   					<?
						   					$cons="select detconcepto,valor,arrastracon,concepto from nomina.nomina where compania='$Compania[0]' and identificacion='$Vinc[0]'
											 and mes='$Mes' and anio='$Anio' and movimiento='Deducidos' and valor!=0 and claseregistro!='Cantidad'";
									//		echo $cons;
											$res=ExQuery($cons);
											while($fila=ExFetch($res))
											{
											?>
												<tr onMouseOver="this.bgColor='#AAD4FF'" onMouseOut="this.bgColor=''"><td><? echo strtoupper($fila[0]);?></td><td align="right"><? echo "$ ".number_format($fila[1],0,'','.');?></td></tr>
											<?
												$TotDeducidos=$TotDeducidos+$fila[1];
											}
											?>
										</table>
                                    </td>
                                    <?
									if($TotDevengados>0||$TotDeducidos>0)
									{
										?>
							   			<tr>
                                        <td align="center" style="font:bold"><? echo "Total Devengados : $ ".number_format($TotDevengados,0,'','.')."<br><br>";?></td>
                                        <td align="center" style="font:bold"><? echo "Total Deducidos : $ ".number_format($TotDeducidos,0,'','.')."<br><br>";?></td>
                                        </tr>
										<?
									}
									?>
                                    <tr>
                                    	<td>
                                        <table border="1" cellpadding="1" cellspacing="0" style="font : normal normal small-caps 11px Tahoma; width:100%">
											<?
                                            $cons="select detconcepto,valor,arrastracon,concepto from nomina.nomina where compania='$Compania[0]' and identificacion='$Vinc[0]'
                                             and mes='$Mes' and anio='$Anio' and movimiento='PostDevengados' and valor!=0 and claseregistro!='Cantidad'";
        //									echo $cons;
                                            $res=ExQuery($cons);
                                            while($fila=ExFetch($res))
                                            {
                                            ?>
                                                <tr onMouseOver="this.bgColor='#AAD4FF'" onMouseOut="this.bgColor=''"><td><? echo strtoupper($fila[0]);?></td><td align="right" style="width:70px"><? echo "$ ".number_format($fila[1],0,'','.');?></td></tr>
                                            <?
											$TotPostDevengados=$TotPostDevengados+$fila[1];
                                            }
                                        	?>
                                		</table>
                                		</td>
                                        <td>
                                        <table border="1" cellpadding="1" cellspacing="0" style="font : normal normal small-caps 11px Tahoma; width:100%">
											<?
                                            $cons="select detconcepto,valor,arrastracon,concepto from nomina.nomina where compania='$Compania[0]' and identificacion='$Vinc[0]'
                                             and mes='$Mes' and anio='$Anio' and movimiento='PostDeducidos' and valor!=0 and claseregistro!='Cantidad'";
        //									echo $cons;
                                            $res=ExQuery($cons);
                                            while($fila=ExFetch($res))
                                            {
                                            ?>
                                                <tr onMouseOver="this.bgColor='#AAD4FF'" onMouseOut="this.bgColor=''"><td><? echo strtoupper($fila[0]);?></td><td align="right" style="width:70px"><? echo "$ ".number_format($fila[1],0,'','.');?></td></tr>
                                            <?
											$TotPostDeducidos=$TotPostDeducidos+$fila[1];
                                            }
                                        	?>
                                		</table>
                                		</td>
                                	</tr>
                                    <tr align="center">
                                    <td colspan="2" style="font:bold; font-size:16px"><? $Neto=$TotDevengados+$TotPostDevengados-$TotDeducidos-$TotPostDeducidos; echo "NETO A PAGAR :   $  ".number_format($Neto,0,'','.');?></td>
                                    </tr>
                                 </tr>
							</table>
 <br><br><font style="font:normal normal small-caps 11px Tahoma;width:100%">__________________________________<br>Firma Funcionario</font>	
                            <font style="color:#999">-------------------------------------------------------------------------------------------------</font>
							<?
						}
					}
				}
			}
		}
	}
	
}
if(!$Pdf)
{
?>
<center>
<input type="button" name="PDF" value="COMPROBANTES" onClick="open('DesprendibleXFuncionario.php?DatNameSID=<? echo $DatNameSID?>&Identificacion=<? echo $Identificacion?>&Vinculacion=<? echo $TipoVinc?>&Numero=<? echo $Numero?>&Mes=<? echo $Mes?>&Anio=<? echo $Anio?>&Pdf=1','Comprobante','outerWidth=400,outerHeight=400,menubar=yes,scrollbars=yes')">
</center>
<?
}
?>
</form>
</body>
</html>
    