<?	
if($DatNameSID){session_name("$DatNameSID");}
session_start();
include("Informes.php");
?>
<form name="FORMA" method="post">

    <?
    $cons = "Select Movimiento.Autoid,date_part('month',fecha),Movimiento.CUM,VrVenta,TotVenta,Cantidad,NumServicio,VrCosto,Codigo1
    from Consumo.Movimiento,Consumo.CodProductos
    Where TipoComprobante = 'Salidas' and Movimiento.Compania='$Compania[0]' and Movimiento.AlmacenPpal='$AlmacenPpal' and Movimiento.Anio=$Anio
    and date_part('month',fecha)>=$MesIni and date_part('month',fecha)<=$MesFin
    and CodProductos.Compania = '$Compania[0]' and CodProductos.AlmacenPpal = '$AlmacenPpal' and CodProductos.Anio = $Anio
    and CodProductos.AutoId = Movimiento.AutoId and Movimiento.Grupo like 'Medicamentos%'
    order by AutoId,Fecha";//echo $cons;
    $res = ExQuery($cons);
    while($fila=ExFetch($res))
    {
        
        if($fila[2]){$CUM = $fila[2];}
        if(!$NoContar[$fila[1]][$CUM][$fila[0]])
        {
            if(!$Registro[$fila[1]][$CUM][$fila[0]])
            {
                $C++;$Registro[$fila[1]][$CUM][$fila[0]]=array($fila[1],$CUM);
            }
        }
        
        $TotMedicamentos = $TotMedicamentos + round($fila[5],0);//echo "$fila[5].....$fila[0].....$CUM<br>";
        if(!$Minimo[$fila[0]])
        {
            $cons2 = "Select Min(VrUnidad),NoFactura
            from Facturacion.DetalleLiquidacion,Facturacion.Liquidacion
            Where Liquidacion.Compania='$Compania[0]' and DetalleLiquidacion.Compania = '$Compania[0]'
            and Tipo='Medicamentos' and Codigo = '$fila[8]' and VrUnidad>0 and NoFactura is not Null
            and DetalleLiquidacion.Noliquidacion = Liquidacion.NoLiquidacion
            group by Liquidacion.noliquidacion,Nofactura LIMIT 1";//echo $cons2."<br>";
            $res2 = ExQuery($cons2);
            $fila2 = ExFetch($res2);
            $Minimo[$fila[0]] = $fila2[0];
            $FacMin[$fila[0]] = $fila2[1];
        }
        if(!$Maximo[$fila[0]])
        {
            $cons2 = "Select Max(VrUnidad),NoFactura
            from Facturacion.DetalleLiquidacion,Facturacion.Liquidacion
            Where Liquidacion.Compania='$Compania[0]' and DetalleLiquidacion.Compania = '$Compania[0]'
            and Tipo='Medicamentos' and Codigo = '$fila[8]' and VrUnidad>0 and NoFactura is not Null
            and DetalleLiquidacion.Noliquidacion = Liquidacion.NoLiquidacion
            group by Liquidacion.noliquidacion,Nofactura LIMIT 1";//echo $cons2."<br>";
            $res2 = ExQuery($cons2);
            $fila2 = ExFetch($res2);
            $Maximo[$fila[0]] = $fila2[0];
            $FacMax[$fila[0]] = $fila2[1];
        }
        
        if(!$NoContar[$fila[1]][$CUM][$fila[0]])
        {
            if($CUM!=$CUMAnt || $fila[0]!=$AutoIdAnt)
            {
                //echo "NUEVO...$fila[0]...$CUM------$CUMAnt------$AutoIdAnt<br>";
                //Precio Minimo
                if($fila[3]!="0")
                {
                    array_push($Registro[$fila[1]][$CUM][$fila[0]],$fila[3]);
                }
                else
                {
                    array_push($Registro[$fila[1]][$CUM][$fila[0]],$Minimo[$fila[0]]);
                }
                //Precio Maximo
                if($fila[3]!="0")
                {
                    array_push($Registro[$fila[1]][$CUM][$fila[0]],$fila[3]);
                }
                else
                {
                    array_push($Registro[$fila[1]][$CUM][$fila[0]],$Maximo[$fila[0]]);
                }
                //TotalVenta
                if($fila[4]!="0")
                {
                    array_push($Registro[$fila[1]][$CUM][$fila[0]],$fila[4]);
                }
                else
                {
                    array_push($Registro[$fila[1]][$CUM][$fila[0]],$Minimo[$fila[0]]*$fila[5]);
                }
                //Cantidad
                array_push($Registro[$fila[1]][$CUM][$fila[0]],$fila[5]);
                //NoFact. Minimo
                array_push($Registro[$fila[1]][$CUM][$fila[0]],$FacMin[$fila[0]]);
                //NoFact. Maximo
                array_push($Registro[$fila[1]][$CUM][$fila[0]],$FacMax[$fila[0]]);

            }
            else
            {
                if($fila[3]!="0")
                {
                    if($fila[3]<$Registro[$fila[1]][$CUM][$fila[0]][2])
                    {
                        $Registro[$fila[1]][$CUM][$fila[0]][2]=$fila[3];
                        $Registro[$fila[1]][$CUM][$fila[0]][6]=$FacMin[$fila[0]];
                    }
                }
                else
                {
                    $Registro[$fila[1]][$CUM][$fila[0]][6]=$FacMin[$fila[0]];
                }

                if($fila[3]>$Registro[$fila[1]][$CUM][$fila[0]][3])
                {
                    $Registro[$fila[1]][$CUM][$fila[0]][3]=$fila[3];
                    $Registro[$fila[1]][$CUM][$fila[0]][7]=$fila[6];
                }
                if($fila[4]!="0")
                {
                    $Registro[$fila[1]][$CUM][$fila[0]][4]=$Registro[$fila[1]][$CUM][$fila[0]][4]+$fila[4];
                }
                else
                {
                    $Registro[$fila[1]][$CUM][$fila[0]][4]=$Registro[$fila[1]][$CUM][$fila[0]][4]+round($fila[7]+($fila[7]*0.35)*$fila[5]);

                }
                $Registro[$fila[1]][$CUM][$fila[0]][4]=$Registro[$fila[1]][$CUM][$fila[0]][4]+$fila[4];
                $Registro[$fila[1]][$CUM][$fila[0]][5]=$Registro[$fila[1]][$CUM][$fila[0]][5]+$fila[5];
            }
            
            if(!$Registro[$fila[1]][$CUM][$fila[0]][8])
            {
                array_push($Registro[$fila[1]][$CUM][$fila[0]],$fila[0]);
            }
        }
        //echo "Registro[$fila[1]][$CUM][$fila[0]][2]".$Registro[$fila[1]][$CUM][$fila[0]][2]."<br>";
        $CUMAnt=$CUM;$AutoIdAnt = $fila[0];$MesAnt=$fila[1];
        
        if(!$Minimo[$fila[0]])
        {
            unset($Registro[$fila[1]][$CUM][$fila[0]]);
            $NoContar[$fila[1]][$CUM][$fila[0]] = 1;
        }
        
    }
    ?>
    <table border="1" bordercolor="#e5e5e5" width="100%"  style='font : normal normal small-caps 11px Tahoma;'>
        <tr align="center" style=" font-weight: bold">
            <td colspan="15">
                <font size="3">Venta de Medicamentos</font><br>
                <?echo $Compania[0];?><br>
                <?echo $Compania[1];?><br>
                <?
                $NI = explode("-",substr($Compania[1],3,strlen($Compania[1])));
                ?>
                Periodo: <? echo "$Anio-$MesIni-01 - $Anio-$MesFin-30";?>
            </td>
        </tr>
        <tr bgcolor="#e5e5e5" style="font-weight: bold">
            <td>Tipo de Registro</td>
            <td>Tipo de Archivo</td>
            <td>Tipo de ID</td>
            <td>Numero de ID</td>
            <td>Digito de Verificacion</td>
            <td>Numero de Medicamentos</td>
            <TD>&nbsp;</TD>
            <TD>&nbsp;</TD>
            <TD>&nbsp;</TD>
            <td>A&ntilde;o de Reporte</td><td>Mes Inicial</td><td>Mes Final</td><td>Numero de Registros</td><td>Sumatoria de Ventas</td>
            <td>Sumatoria de recobros</td>
        </tr>
        <tr>
            <td>1</td>
            <td>1</td>
            <td>NI</td>
            <td><?echo $NI[0]?></td>
            <td><?echo $NI[1]?></td>
            <td><?echo $TotMedicamentos?></td>
            <TD>&nbsp;</TD>
            <TD>&nbsp;</TD>
            <TD>&nbsp;</TD>
            <td><?echo $Anio?></td>
            <td><? echo $MesIni?></td>
            <td><? echo $MesFin ?></td>
            <td><input type="text" name="TotRegistros" size="3" readonly style="text-align:right; border: none" /></td>
            <?
                $cons = "Select SUM(TotVenta) from Consumo.Movimiento
                Where Compania='$Compania[0]' and AlmacenPpal='$AlmacenPpal' and Anio=$Anio
                and (date_part('month',fecha)>=$MesIni and date_part('month',fecha)<=$MesFin)
                and TipoComprobante = 'Salidas'";//echo $cons;
                $res = ExQuery($cons);
                $fila = ExFetch($res);
                $SumCompras = $fila[0] + 0;

            ?>
            <td><label id="SumCompras"><?echo $SumCompras?></label></td>
            <td>0</td>
        </tr>
    </table>
    <table border="1" bordercolor="#e5e5e5" width="100%"  style='font : normal normal small-caps 11px Tahoma;'>
        <tr bgcolor="#e5e5e5" style="font-weight: bold">
            <td>Tipo de Reg.</td>
            <td>Consecutivo</td>
            <td>Mes</td>
            <td>Canal</td>
            <td>CUM</td>
			<td>Venta Externa</td>
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
                    <td>2</td><td><?echo $C?></td><td><?echo $CUM3[0];?></td><td>INS</td>
                    <td><?echo "$CUM3[1]"?></td><td>No</td><td><?echo $Minimo[$CUM3[8]];?></td><td><?echo $Maximo[$CUM3[8]]?></td>
                    <td><?echo $CUM3[4]?></td><td><?echo $CUM3[5]?></td><td><?echo $FacMin[$CUM3[8]]?></td>
                    <td><?echo $FacMax[$CUM3[8]]?></td>
                </tr><?
            }
        }
    }
    ?>
            <script language="javascript">
                document.FORMA.TotRegistros.value = "<?echo $C;?>";
            </script>
</table>
</form>