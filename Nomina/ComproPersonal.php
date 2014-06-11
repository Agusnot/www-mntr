<?
if($DatNameSID){session_name("$DatNameSID");}
session_start();
include("Funciones.php");
//echo $Numero;
$ND=getdate();
/*if("$Mes"<10)
{
	$Mes="0$Mes";
}*/
if("$ND[mday]"<10)
{
	$Dia="0$ND[mday]";
}
$Fecha=$Anio."-".$Mes."-".$Dia;
//echo $Fecha;
//echo $DatNameSID."<--".$Identificacion."<--".$Mes."<--".$Anio."<--".$Vinculacion;
$cons="select terceros.identificacion,terceros.primape,terceros.segape,terceros.primnom,terceros.segnom,detconcepto,valor from nomina.nomina,central.terceros where terceros.compania='$Compania[0]' and terceros.compania=nomina.compania and terceros.identificacion='$Identificacion' and terceros.identificacion=nomina.identificacion";
//echo $cons."<br>";
$res=ExQuery($cons);
$fila=Exfetch($res);
$consmes="select mes from central.meses where numero='$Mes'";
//echo $consmes;
$resmes=ExQuery($consmes);
$filames=ExFetch($resmes);
$nomes=$filames[0];
//echo $Empleados[0]."<--".$nomes;
$conssal="select salario from nomina.salarios where compania='$Compania[0]' and identificacion='$Identificacion' and fecinicio<='$Fecha' and fecfin>='$Fecha'";
//echo $conssal;
$ressal=ExQuery($conssal);
$filasal=ExFetch($ressal);
//echo $filasal[0];
$concargo="select cargo from nomina.contratos where compania='$Compania[0]' and identificacion='$Identificacion' and numero='$Numero' and estado='Activo'";
$rescargo=ExQuery($concargo);
$filacargo=ExFetch($rescargo);
$connomcar="select cargos.cargo from nomina.contratos,nomina.tiposvinculacion,nomina.cargos where contratos.compania='$Compania[0]'
			and contratos.compania=tiposvinculacion.compania and tiposvinculacion.compania=cargos.compania and identificacion='$Identificacion' 
			and tiposvinculacion.tipovinculacion='$Vinculacion' and cargos.codigo='$filacargo[0]' and numero='$Numero' and estado='Activo'";
$resconnomcar=ExQuery($connomcar);
$filanomcar=ExFetch($resconnomcar);
//echo $connomcar;			
//echo $filacargo[0]."<--";
//echo $concargo."    ".$Vinculacion;
$TotDevengados=0;$TotDeducidos=0;$TotPostDevengados=0;$TotPostDeducidos=0;
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<script language='javascript' src="/Funciones.js"></script>
</head>
<body background="/Imgs/Fondo.jpg">
<form name="FORMA" method="post">
<table border="0" bordercolor="#e5e5e5" style="font : normal normal small-caps 11px Tahoma; width:100%" align="center" />
	<tr>
    	<td colspan="2">
        <img border="0" src="/Imgs/Logo.jpg" style="width:42; height:56" align="right" />
		<font style="font-size:12px"/><? echo strtoupper($Compania[0]."<br>DESPRENDIBLE DE PAGO - PERIODO:  ".$nomes."/".$Anio);?>
    <br><br><font style="font-weight:bold"><? echo $fila[3]." ".$fila[4]." ".$fila[1]." ".$fila[2]." - ".$Identificacion;?></font>&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;SALARIO BASE: <? echo number_format($filasal[0],0,'','.');?><br /><font style="font-weight:bold"><? echo $filanomcar[0];?></font>
		</td>
	</tr>
    <tr>
    	<td valign="top">
        	<table border="1" cellpadding="1" cellspacing="0" style="font : normal normal small-caps 11px Tahoma; width:100%">
                <tr>
                    <td colspan="2" align="center" style="font-weight:bold">DEVENGADOS</td>
                </tr>
                <tr>
                    <td align="center" style="font-weight:bold">Concepto</td><td style="width:70px;font-weight:bold" align="center">Valor</td>
                </tr>
                <?
                $cons="select detconcepto,valor,arrastracon,concepto from nomina.nomina where compania='$Compania[0]' and identificacion='$Identificacion' 
                and mes='$Mes' and anio='$Anio' and movimiento='Devengados' and valor!=0 and claseregistro!='Cantidad'";
//				echo $cons;
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
        <td valign="top">
        	<table border="1" cellpadding="1" cellspacing="0" style="font : normal normal small-caps 11px Tahoma; width:100%">
                <tr>
                    <td colspan="2" align="center" style="font-weight:bold">DEDUCIDOS</td>
                </tr>
                <tr>
                    <td align="center" style="font-weight:bold">Concepto</td><td style="width:70px;font-weight:bold" align="center">Valor</td>
                </tr>
                <?
                $cons="select detconcepto,valor,arrastracon,concepto from nomina.nomina where compania='$Compania[0]' and identificacion='$Identificacion' 
                and mes='$Mes' and anio='$Anio' and movimiento='Deducidos' and valor!=0 and claseregistro!='Cantidad'";
//				echo $cons;
                $res=ExQuery($cons);
                while($fila=ExFetch($res))
                {
                ?>
                    <tr onMouseOver="this.bgColor='#AAD4FF'" onMouseOut="this.bgColor=''">
                        <td><? echo strtoupper($fila[0]);?></td><td align="right"><? echo "$ ".number_format($fila[1],0,'','.');?></td>
                    </tr>
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
            <td align="center" style="font:bold" ><? echo "Total Devengados : $ ".number_format($TotDevengados,0,'','.')."<br><br>";?></td>
            <td align="center" style="font:bold" ><? echo "Total Deducidos : $ ".number_format($TotDeducidos,0,'','.')."<br><br>";?></td>
            </tr>
            <?
        }
        ?>
        <tr>
            <td>
            <table border="1" cellpadding="1" cellspacing="0" style="font : normal normal small-caps 11px Tahoma;width:100%" >
                <?
                $cons="select detconcepto,valor,arrastracon,concepto from nomina.nomina where compania='$Compania[0]' and identificacion='$Identificacion'
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
            <table border="1" cellpadding="1" cellspacing="0" style="font : normal normal small-caps 11px Tahoma;width:100%" >
                <?
                $cons="select detconcepto,valor,arrastracon,concepto from nomina.nomina where compania='$Compania[0]' and identificacion='$Identificacion'
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
        <td colspan="2" style="font:bold; font-size:16px" ><? $Neto=$TotDevengados+$TotPostDevengados-$TotDeducidos-$TotPostDeducidos; echo "NETO A PAGAR :   $  ".number_format($Neto,0,'','.');?></td>
        </tr>
     </tr>

</table>
<br><br><font style="font:normal normal small-caps 11px Tahoma;width:100%">__________________________________<br>Firma Funcionario</font>
</form>
</body>
</html>
