<?	
if($DatNameSID){session_name("$DatNameSID");}
session_start();
include("Informes.php");
?>
<form name="FORMA">
    <?
    $cons = "Select NoLiquidacion,NumServicio,NoFactura,date_part('month',FechaIni)
    from Facturacion.Liquidacion
    Where Liquidacion.Compania = '$Compania[0]' 
    and date_part('month',FechaIni)>=$MesIni
    and date_part('month',FechaIni)<=$MesFin
    and Liquidacion.Estado = 'AC' 
    and NoFactura is not NULL
    order by FechaIni";//echo $cons."<br>";
    $res = ExQuery($cons);
    while($fila = ExFetch($res))
    {
        $cons1 = "Select Nombre,Codigo,VrUnidad,Cantidad,generico,presentacion,forma,VrTotal
        from Facturacion.DetalleLiquidacion 
        Where DetalleLiquidacion.Compania='$Compania[0]'
        and NoLiquidacion = $fila[0] and Tipo='Medicamentos'
        and VrUnidad > 0 and VrTotal > 0";//echo "<b>$cons1</b><br>";
        $res1 = ExQuery($cons1);
        //echo "$fila[0],$fila[1],$fila[2]:<br>";
        $C=0;
        while($fila1 = ExFetch($res1))
        {
            $COD = explode(" - ",$fila1[1]);
            //echo "<b>$fila[0]</b>,$fila1[0],$fila1[1],$fila1[2],$fila1[3],<b>$fila[2]</b><br>";
            if ($fila1[4]==""){unset($fila1[4]);}
            if ($fila1[5]==""){unset($fila1[5]);}
            if ($fila1[6]==""){unset($fila1[6]);}
            if($fila1[4]&&$fila1[5]&&$fila1[6])
            {
                $Ad_ConsNom = " and (NombreProd1 || ' ' || UnidadMedida || ' ' || Presentacion)='$fila1[4] $fila1[5] $fila1[6]'";
            }
            else
            {
                $Ad_ConsNom = " and (NombreProd1 || ' ' || UnidadMedida || ' ' || Presentacion)='$fila1[0]'";
            }    
            $cons2 = "Select Movimiento.CUM 
            from Consumo.Movimiento,Consumo.CodProductos
            Where Movimiento.AlmacenPpal = '$AlmacenPpal'
            and CodProductos.AlmacenPpal = '$AlmacenPpal'
            and CodProductos.Anio = $Anio and Movimiento.Anio = $Anio
            and CodProductos.Autoid = Movimiento.Autoid
            $Ad_ConsNom
            and NUmServicio = $fila[1]
            and Movimiento.Grupo != 'Dispositivo Medico'
            and Movimiento.CUM is NOT NULL and Movimiento.CUM != ''
            LIMIT 1";//echo "**** $cons2<br>";
            $res2 = ExQuery($cons2);
            $fila2 = ExFetch($res2);
            //echo "<b>----$fila2[0]</b><br>";
            $C++;
            if($fila2[0])
            {
                //echo "$fila[3],$fila2[0],$fila1[2],$fila1[2],".$fila1[2]*$fila1[3].",$fila1[3],$fila[2]<br>";
                // ACUMULADORES: VrTotal,Cantidad
                $RegSISMED[$fila[3]][$fila2[0]]['MES'] = $fila[3];
                $RegSISMED[$fila[3]][$fila2[0]]['CUM'] = $fila2[0];
                $RegSISMED[$fila[3]][$fila2[0]]['VrTotal'] = $RegSISMED[$fila[3]][$fila2[0]]['VrTotal'] + $fila1[7];
                $RegSISMED[$fila[3]][$fila2[0]]['CantTotal'] = $RegSISMED[$fila[3]][$fila2[0]]['CantTotal'] + $fila1[3];
                $CANTMEDICAMENTOS = $CANTMEDICAMENTOS + $fila1[3];
                $SUMVRMEDICAMENTOS = $SUMVRMEDICAMENTOS + $fila1[7];
                if(!$RegSISMED[$fila[3]][$fila2[0]]['MinPrecio'])
                {
                    $RegSISMED[$fila[3]][$fila2[0]]['MinPrecio'] = $fila1[2];
                    $RegSISMED[$fila[3]][$fila2[0]]['FactMinPrecio'] = $fila[2];
                }
                else
                {
                    if($fila1[2]<$RegSISMED[$fila[3]][$fila2[0]]['MinPrecio'])
                    {
                        $RegSISMED[$fila[3]][$fila2[0]]['MinPrecio'] = $fila1[2];
                        $RegSISMED[$fila[3]][$fila2[0]]['FactMinPrecio'] = $fila[2];
                    }
                }
                if(!$RegSISMED[$fila[3]][$fila2[0]]['MaxPrecio'])
                {
                    $RegSISMED[$fila[3]][$fila2[0]]['MaxPrecio'] = $fila1[2];
                    $RegSISMED[$fila[3]][$fila2[0]]['FactMaxPrecio'] = $fila[2];
                }
                else
                {
                    if($fila1[2]>$RegSISMED[$fila[3]][$fila2[0]]['MaxPrecio'])
                    {
                        $RegSISMED[$fila[3]][$fila2[0]]['MaxPrecio'] = $fila1[2];
                        $RegSISMED[$fila[3]][$fila2[0]]['FactMaxPrecio'] = $fila[2];
                    }
                }
            }
        }
        //if($C>0){echo "<hr>";}
        
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
        <?
        $NI = explode("-",substr($Compania[1],3,strlen($Compania[1])));
        ?>
        <tr>
            <td>1</td>
            <td>1</td>
            <td>NI</td>
            <td><?echo $NI[0]?></td>
            <td><?echo $NI[1]?></td>
            <td><?echo $CANTMEDICAMENTOS?></td>
            <TD>&nbsp;</TD>
            <TD>&nbsp;</TD>
            <TD>&nbsp;</TD>
            <td><?echo $Anio?></td>
            <td><? echo $MesIni?></td>
            <td><? echo $MesFin ?></td>
            <td><input type="text" name="Registros" readonly style=" border: 0; font-size: 12"/></td>
            <td><?echo $SUMVRMEDICAMENTOS?></td>
            <td>0</td>
        </tr>
    </table>    
    <table border="1" bordercolor="#e5e5e5" width="100%"  style='font : normal normal small-caps 11px Tahoma;'>
        <tr bgcolor="#e5e5e5" style=" font-weight: bold">
            <td>Tipo de Reg.</td>
            <td>Consecutivo</td>
            <td>Mes</td>
            <td>Canal</td>
            <td>CUM</td>
            <td>Precio Min.</td>
            <td>Precio Max.</td>
            <td>Vr. Total</td>
            <td>Cantidad</td>
            <td>No Factura P.Min.</td>
            <td>No Factura P.Max.</td>
        </tr>
    <?
    foreach($RegSISMED as $RegSISMED1)
    {
        foreach($RegSISMED1 as $RegSISMED2)
        {
            $Y++;
            ?>
            <tr>
                <td>2</td>
                <td><?echo $Y?></td>
                <td><?echo $RegSISMED2['MES']?></td>
                <td>INS</td>
                <td><?echo $RegSISMED2['CUM']?></td>
                <td><?echo $RegSISMED2['MinPrecio']?></td>
                <td><?echo $RegSISMED2['MaxPrecio']?></td>
                <td><?echo $RegSISMED2['VrTotal']?></td>
                <td><?echo $RegSISMED2['CantTotal']?></td>
                <td><?echo $RegSISMED2['FactMinPrecio']?></td>
                <td><?echo $RegSISMED2['FactMaxPrecio']?></td>
            </tr>
            <?
        }
    }
    ?>
            <script language="javascript">
                document.FORMA.Registros.value = "<?echo $Y?>";
            </script>    
    </table>
</form>