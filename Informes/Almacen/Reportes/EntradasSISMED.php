<?	
if($DatNameSID){session_name("$DatNameSID");}
session_start();
include("Informes.php");
?>
<form name="FORMA" method="post">

    <?
    $cons = "Select Numero,Autoid,date_part('month',fecha),Numero,VrCosto,TotCosto,Cantidad,NoFactura from Consumo.Movimiento
    Where TipoComprobante = 'Entradas' and Compania='$Compania[0]' and AlmacenPpal='$AlmacenPpal' and Anio=$Anio
    and date_part('month',fecha)>=$MesIni and date_part('month',fecha)<=$MesFin and Grupo = 'Medicamentos'
    order by Fecha,AutoId";
    $res = ExQuery($cons);
	//echo $cons;
    while($fila=ExFetch($res))
    {
        $cons2 = "Select CUM from Consumo.CumsxProducto,Consumo.Lotes
        Where Lotes.Compania='$Compania[0]' and Lotes.AlmacenPpal='$AlmacenPpal'
        and Lotes.Numero = '$fila[0]' and Lotes.Autoid=$fila[1]
        and CumsxProducto.Compania = '$Compania[0]' and CumsxProducto.AlmacenPpal='$AlmacenPpal'
        and CumsxProducto.Autoid=$fila[1] and CumsxProducto.RegINVIMA = Lotes.RegINVIMA LIMIT 1";
        $res2 = ExQuery($cons2);
        $fila2 = ExFetch($res2);
        $Total = $Total + $fila[5];
        
        if(!$fila2[0])
        {
            $consxx = "Select CUM from Consumo.CumsxProducto Where AutoId = $fila[1] LIMIT 1";
            $resxx = ExQuery($consxx);
            $filaxx = ExFetch($resxx);
            if($filaxx[0])
            {
                $CUM = $filaxx[0];
            }
            else $CUM = "NA";
        }
        else{$CUM = $fila2[0];}
        if(!$Registro[$fila[2]][$CUM][$fila[1]])
        {
            $C++;
            //echo "NUEVO...$fila[1]...$CUM....$fila[5]<br>";
            $Registro[$fila[2]][$CUM][$fila[1]]=array($fila[2],$CUM,round($fila[4]),round($fila[4]),
                                                        round($fila[5]),$fila[6],$fila[7],$fila[7]);

        }
//        if($CUM!=$CUMAnt || $fila[1]!=$AutoIdAnt)
//        {
//            //echo "NUEVO...$fila[1]...$CUM<br>";
//            //Precio Minimo
//            array_push($Registro[$fila[2]][$CUM][$fila[1]],$fila[4]);
//            array_push($Registro[$fila[2]][$CUM][$fila[1]],$fila[4]);
//            array_push($Registro[$fila[2]][$CUM][$fila[1]],$fila[5]);
//            array_push($Registro[$fila[2]][$CUM][$fila[1]],$fila[6]);
//            array_push($Registro[$fila[2]][$CUM][$fila[1]],$fila[7]);
//            array_push($Registro[$fila[2]][$CUM][$fila[1]],$fila[7]);
//
//        }
        else
        {
            //echo "REPETIDO...$fila[1]...$CUM....$fila[2]...$fila[5]<br>";
            //Precio Minimo
            if($fila[4]<$Registro[$fila[2]][$CUM][$fila[1]][2])
            {
                //echo "Es Menor...$fila[7]<br>";
                $Registro[$fila[2]][$CUM][$fila[1]][2]=$fila[4];
                $Registro[$fila[2]][$CUM][$fila[1]][6]=$fila[7];
            }
            if($fila[4]>$Registro[$fila[2]][$CUM][$fila[1]][3])
            {
                //echo "Es Mayor...$fila[7]<br>";
                $Registro[$fila[2]][$CUM][$fila[1]][3]=$fila[4];
                $Registro[$fila[2]][$CUM][$fila[1]][7]=$fila[7];
            }
            $Registro[$fila[2]][$CUM][$fila[1]][4]=$Registro[$fila[2]][$CUM][$fila[1]][4]+$fila[5];
            $Registro[$fila[2]][$CUM][$fila[1]][5]=$Registro[$fila[2]][$CUM][$fila[1]][5]+$fila[6];
        }
        //echo "Registro[$fila[2]][$CUM][$fila[1]][2]".$Registro[$fila[2]][$CUM][$fila[1]][2]."<br>";
        if(!$Registro[$fila[2]][$CUM][$fila[1]][8]){array_push($Registro[$fila[2]][$CUM][$fila[1]],$fila[1]);}

        $CUMAnt = $CUM;$AutoIdAnt = $fila[1];$MesAnt=$fila[2];
    }
    ?>
    <table border="1" bordercolor="#e5e5e5" width="100%"  style='font : normal normal small-caps 11px Tahoma;'>
        <tr align="center" style=" font-weight: bold">
            <td colspan="12">
                <font size="3">Compras de Medicamentos</font><br>
                <?echo $Compania[0];?><br>
                <?echo $Compania[1];?><br>
                <?
                $NI = explode("-",substr($Compania[1],3,strlen($Compania[1])));
                ?>
                Periodo: <? echo "$Anio-$MesIni-01 - $Anio-$MesFin-30";?>
            </td>
        </tr>
        <tr bgcolor="#e5e5e5" style="font-weight: bold">
            <td>Tipo de Registro</td><td>Tipo de Archivo</td><td>Tipo de ID</td><td>Numero de ID</td><td>Digito de Verificacion</td><td>Sucursal</td>
            <td>A&ntilde;o de Reporte</td><td>Mes Inicial</td><td>Mes Final</td><td>Numero de Registros</td><td>Sumatoria de Compras</td>
            <td>Sumatoria de recobros</td>
        </tr>
        <tr>
            <td>1</td><td>2</td><td>NI</td><td><?echo $NI[0]?></td><td><?echo $NI[1]?></td><td>&nbsp;</td>
            <td><?echo $Anio?></td><td><? echo $MesIni?></td><td><? echo $MesFin ?></td>
            <td><label id="TotRegistros"><?echo $C?></label></td>
            <?
                $cons = "Select SUM(TotCosto) from Consumo.Movimiento
                Where Compania='$Compania[0]' and AlmacenPpal='$AlmacenPpal' and Anio=$Anio
                and (date_part('month',fecha)>=$MesIni and date_part('month',fecha)<=$MesFin)
                and TipoComprobante = 'Entradas' and Grupo = 'Medicamentos'";//echo $cons;
                $res = ExQuery($cons);
                $fila = ExFetch($res);
                $SumCompras = $fila[0] + 0;

            ?>
            <td><label id="SumCompras"><?echo round($SumCompras)?></label></td>
            <td>0</td>
        </tr>
    </table>
    <table border="1" bordercolor="#e5e5e5" width="100%"  style='font : normal normal small-caps 11px Tahoma;'>
        <tr bgcolor="#e5e5e5" style="font-weight: bold">
            <td>Tipo de Reg.</td>
            <td>Consecutivo</td>
            <td>Mes</td>
            <td>CUM</td>
            <td>Precio Min.</td>
            <td>Precio Max.</td>
            <td>Vr Total</td>
            <td>Cantidad</td>
            <td>No Factura P.Min.</td>
            <td>No Factura P.Max.</td>
        </tr>
    <?
    unset($C);
    foreach($Registro as $CUM1)
    {
        foreach($CUM1 as $CUM2)
        {
            foreach($CUM2 as $CUM3)
            {
                $C++;
                ?><tr>
                    <td>2</td><td><?echo $C?></td><td><?echo $CUM3[0]?></td>
                    <td><?echo "$CUM3[1]"?></td><td><?echo $CUM3[2]?></td><td><?echo $CUM3[3]?></td>
                    <td><?echo $CUM3[4]?></td><td><?echo $CUM3[5]?></td><td><?echo $CUM3[6]?></td>
                    <td><?echo $CUM3[7]?></td>
                </tr><?
            }
        }
    }
    ?>
</table>
</form>