<?
if($DatNameSID){session_name("$DatNameSID");}
session_start();
include("Informes.php");
include("ObtenerSaldos.php");
ini_set("memory_limit","512M");
$ND = getdate();
?>
<body>
    <form name="FORMA" method="post">
        <table style='font : normal normal small-caps 12px Tahoma;' border="0" width="100%">
            <tr align="center">
                <td style="font-weight: bold" colspan="8">Medicamento
                    <select name="Medicamento"><option value=""></option>
                        <?
                            $cons = "Select Autoid,NombreProd1,UnidadMedida,Presentacion
                            from Consumo.CodProductos Where Compania='$Compania[0]'
                            and AlmacenPpal = '$AlmacenPpal' and Anio=$Anio order by NombreProd1";
                            $res = ExQuery($cons);
                            while($fila=ExFetch($res))
                            {
                                if($Medicamento==$fila[0]){$Sel = " selected ";}else{$Sel = "";}
                                echo "<option $Sel value='$fila[0]'>$fila[1] $fila[2] $fila[3]</option>";
                            }
                        ?>
                    </select>
                    &nbsp;&nbsp;&nbsp;Tipo de salida
                    <select name="Comprobante"><option value=""></option>
                        <?
                        $cons = "Select Comprobante from Consumo.Comprobantes 
                        Where Compania='$Compania[0]' and AlmacenPpal='$AlmacenPpal'
                        and Tipo = 'Salidas'";
                        $res = ExQuery($cons);
                        while($fila = ExFetch($res))
                        {
                            echo "<option value='$fila[0]'>$fila[0]</option>";
                        }
                        ?>
                    </select>
                    <input type="submit" name="Verx" value="Ver" />
                </td>
            </tr>
        </table>
        <?
        if($Verx)
        {
            $cons = "Select Autoid,NoDocAfectado,Cantidad
            from Consumo.Movimiento
            Where Compania='$Compania[0]'
            and AlmacenPpal = '$AlmacenPpal' and Estado = 'AC'
            and  DocAfectado = '$Comprobante'";
            $res = ExQuery($cons);
            while($fila = ExFetch($res))
            {
                $Devolucion[$fila[0]][$fila[1]] = $fila[2];
            }
            ?>
            <table style='font : normal normal small-caps 12px Tahoma;' border="1" bordercolor="#e5e5e5" width="100%">
                <?
                if($Comprobante == "Salidas por Plantilla"){$Detalle = "Pabellon";}else{$Detalle = "Detalle";}
                if($Comprobante == "Salidas por Plantilla" || $Comprobante == "Salidas Urgentes")
                {$Tercero = "Paciente";}
                else{$Tercero = "Tercero";}
                if($Comprobante != "Salidas por Plantilla" && $Comprobante != "Salidas Urgentes")
                {
                    $AddEnc = "<td>No Solicitud</td><td>Usuario</td>";$colspan=6;
                }
                else{$colspan=4;}
                $cons = "Select Fecha,Cedula,PrimNom,SegNom,PrimApe,SegApe,Detalle,Cantidad,Numero,IdSolicitud
                from consumo.Movimiento,Central.Terceros
                Where Movimiento.Compania='$Compania[0]' and Terceros.Compania='$Compania[0]'
                and Cedula = Identificacion
                and AlmacenPpal='$AlmacenPpal'
                and Autoid = $Medicamento and TipoComprobante='Salidas' and Comprobante='$Comprobante'
                and Anio = $Anio and Fecha >= '$Anio-$MesIni-$DiaIni' and Fecha <= '$Anio-$MesFin-$DiaFin'
                and Movimiento.Estado = 'AC'
                order by Fecha,Detalle,PrimNom,SegNom,PrimApe,SegApe";
                $res = ExQuery($cons);//echo $cons;
                while($fila = ExFetch($res))
                {
                    if($fila[0]!=$FechaAnt)
                    {
                        if($FechaAnt)
                        {
                            $T = $Tot - $TotDev;
                            echo "<tr><td colspan='2' align='right'>
                            <b>Sub Total</b></td><td align='right'>$Tot</td><td align='right'>$TotDev</td>
                            <td align='right'>$T</td></tr>";
                            $TOTAL = $Tot + $TOTAL;
                            $TOTALDev = $TotDev + $TOTALDev;
                        }
                        unset($Tot);
                        unset($TotDev);
                    ?>
                        <tr bgcolor="#e5e5e5" style="font-weight: bold">
                            <td colspan="<?echo $colspan?>">&nbsp;</td><td>Fecha</td><td><? echo $fila[0]?></td></tr>
                        <tr bgcolor="#e5e5e5" style="font-weight: bold"><td><? echo $Tercero?></td><td><? echo $Detalle ?></td>
                            <td>Despacho</td><td>Devolucion</td><td>Total</td><td>Numero</td><?echo $AddEnc?></tr>
                    <?    
                    }
                    if($Detalle=="Pabellon"){$fila[6] = str_replace("Despacho medicamentos ","",$fila[6]);}
                    if(!$Devolucion[$Medicamento][$fila[8]]){$Devolucion[$Medicamento][$fila[8]] = 0;}
                    $Tot2 = $fila[7] - $Devolucion[$Medicamento][$fila[8]];
                    $Tot = $Tot + $fila[7];
                    $TotDev = $TotDev + $Devolucion[$Medicamento][$fila[8]];
                    $Tot3 = $Tot - $TotDev;
                    echo "<tr>
                            <td>$fila[2] $fila[3] $fila[4] $fila[5] ($fila[1])</td>
                            <td>$fila[6]</td>
                            <td align='right'>$fila[7]</td>
                            <td align='right'>".$Devolucion[$Medicamento][$fila[8]]."</td>
                            <td align='right'>$Tot2</td>
                            <td>$fila[8]</td>";
                    if($fila[9])
                    {
                        echo "<td>$fila[9]</td>";
                        $cons1 = "Select Usuario from Consumo.SolicitudConsumo
                        Where Compania = '$Compania[0]' and AlmacenPpal='$AlmacenPpal'
                        and Anio = $Anio and IdSolicitud = $fila[9] and Autoid=$Medicamento LIMIT 1";
                        $res1 = ExQuery($cons1);
                        $fila1 = ExFetch($res1);
                        echo "<td>$fila1[0]</td>";
                    }
                    echo "</tr>";
                    $FechaAnt = $fila[0];
                }
                $T = $Tot - $TotDev;
                echo "<tr><td colspan='2' align='right'><b>Sub Total</b></td>
                <td align='right'>$Tot</td><td align='right'>$TotDev</td>
                <td align='right'>$T</td></tr>";
                $TOTAL = $Tot + $TOTAL;
                $TOTALDev = $TotDev + $TOTALDev;
                $TT = $TOTAL - $TOTALDev;
                echo "<tr><td colspan='2' align='right'><b>TOTAL</b></td><td align='right'>$TOTAL</td>
                <td align='right'>$TOTALDev</td><td align='right'>$TT</td></tr>";
                ?>
            </table>
            <?
        }
        ?>
    </form>
</body>