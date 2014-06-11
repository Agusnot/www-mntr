<?
if($DatNameSID){session_name("$DatNameSID");}
session_start();
include("Informes.php");
include("ObtenerSaldos.php");
ini_set("memory_limit","512M");
$ND = getdate();
$Ambito = str_replace("-0","",$Ambito);
if(!$FechaI){$FechaI = $Fecha;}if(!$FechaF){$FechaF = $Fecha;}
$Fechaplus = explode("-",$Fecha);
$Anio = $Fechaplus[0];$Mes = $Fechaplus[1]; $Dia = $Fechaplus[2];
if($Verx == "consolidado"){$Pabellon = "";}
?>
<style>
    div.Salto
    {
        page-break-after: always;
    }
</style>
<form name ="FORMA" method="post">
    <input type="hidden" name="DatNameSID" value="<? echo $DatNameSID?>" />
    <input type="hidden" name="AlmacenPpal" value="<? echo $AlmacenPpal?>" />
    <input type="hidden" name="Fecha" value="<? echo $Fecha?>" />
    <input type="hidden" name="Ambito" value="<? echo $Ambito?>" />
    <table style='font : normal normal small-caps 12px Tahoma;' border="0">
        <tr bgcolor="#e5e5e5">
            <td>Pabellon:</td>
            <td><select name="Pabellon"><option value="">Todos</option>
                    <?
                    $cons = "Select Pabellon from Salud.Pabellones where
                    Compania='$Compania[0]' and Ambito = '$Ambito' order by Pabellon";
                    $res = ExQuery($cons);
                    while($fila = ExFetch($res))
                    {
                        if($fila[0]=="$Pabellon"){$Sel=" selected ";}else{$Sel="";}
                        echo "<option $Sel value='$fila[0]'>$fila[0]</option>";
                    }
                    ?>
                </select></td>
            <td>Ver por:</td>
            <td><select name="Verx">
                    <option
                        <?
                        if($Verx=="paciente"){echo " selected ";}
                        ?>
                        value="paciente">Paciente x Pabellon</option>
                    <option
                        <?
                        if($Verx=="medicamento"){echo " selected ";}
                        ?>
                        value="medicamento">Medicamento x Pabellon</option>
                    <option
                        <?
                        if($Verx=="consolidado"){echo " selected ";}
                        ?>
                        value="consolidado">Consolidado total</option>
                </select></td>
                <td>Desde</td>
                <td><input type="text" name ="FechaI" size="6" value="<?echo $FechaI?>"></td>
                <td>Hasta</td>
                <td><input type="text" name ="FechaF" size="6" value="<?echo $FechaF?>"></td>
                <td><input type="submit" name="Ver" value="Ver"/></td>
        </tr>
    </table>
<?
    if($Verx=="consolidado" || $Verx=="medicamento"){$Orderby="order by NombreProd1,UnidadMedida,Presentacion";}
    else{$Orderby="order by Cedula,Movimiento.AutoId";}
    if($Pabellon){$Adcons = " and Detalle like 'Despacho medicamentos $Ambito - $Pabellon' ";}
    $cons = "Select Cedula,Movimiento.AutoId,Numero,Cantidad,Control,NumeroControlados,Detalle,
    NombreProd1,UnidadMedida,Presentacion
    from Consumo.Movimiento,Consumo.CodProductos
    where Movimiento.Compania='$Compania[0]' and CodProductos.Compania='$Compania[0]'
    and Comprobante = 'Salidas por Plantilla' and Movimiento.Autoid = CodProductos.Autoid
    and TipoComprobante = 'Salidas' and Fecha <='$Fecha' and
    Movimiento.AlmacenPpal = '$AlmacenPpal' and Movimiento.Anio = $Anio and
    CodProductos.AlmacenPpal = '$AlmacenPpal' and CodProductos.Anio = $Anio
    and Fecha >= '$FechaI' and Fecha <='$FechaF'
    and Movimiento.Estado = 'AC' $Adcons $Orderby";
    $res = ExQuery($cons);
    while($fila = ExFetch($res))
    {
        $Pabellon = str_replace("Despacho medicamentos $Ambito - ","",$fila[6]);
        if($Verx=="paciente")
        {
            $MatrizMxP[$Pabellon][$fila[2]][$fila[0]][$fila[1]] = $fila[3];
        }
        if($Verx == "medicamento")
        {
            $MatrizM[$Pabellon][$fila[1]] = $MatrizM[$Pabellon][$fila[1]] + $fila[3];
            //echo $MatrizM[$Pabellon][$fila[1]]."*****$fila[1]***$fila[3]<br>";
            $NxP[$fila[2]] = $Pabellon;
        }
        if($Verx == "consolidado")
        {
            $MatrizConso[$fila[1]] = $MatrizConso[$fila[1]] + $fila[3];
            //echo $MatrizConso[$fila[1]]."----$fila[1]-----$fila[3]<br>";
        }
        if(!$LTerceros){$LTerceros = "'$fila[0]'";}else{$LTerceros=$LTerceros.",'$fila[0]'";}
        if(!$LAutoId){$LAutoId = "$fila[1]";}else{$LAutoId=$LAutoId.",$fila[1]";}
        if(!$LNumeros){$LNumeros = "'$fila[2]'";}else{$LNumeros = "$LNumeros,'$fila[2]'";}
        //echo $LNumeros."<br>";
    }
    if($LNumeros)
    {
        $cons = "Select NoDocAfectado,Autoid,Cedula,Cantidad from Consumo.Movimiento Where
        Compania='$Compania[0]' and AlmacenPpal='$AlmacenPpal' and Movimiento.Estado = 'AC'
        and Cedula in($LTerceros) and Autoid in($LAutoId) and NoDocAfectado in ($LNumeros)
        and NoDocAfectado is Not NULL";//echo $cons;
        $res = ExQuery($cons);
        while($fila = ExFetch($res))
        {
            if($Verx=="paciente")
            {
                $Devolucion[$fila[0]][$fila[2]][$fila[1]] = $fila[3];
            }
            if($Verx=="medicamento")
            {
                $Devolucion[$NxP[$fila[0]]][$fila[1]] = $Devolucion[$NxP[$fila[0]]][$fila[1]] + $fila[3];
            }
            $TotDevolucion[$fila[1]] = $TotDevolucion[$fila[1]] + $fila[3];
        }
    }
    if($LTerceros)
    {
        $cons = "Select Identificacion,PrimNom,SegNom,PrimApe,SegApe from Central.Terceros
        Where Compania='$Compania[0]' and Identificacion in($LTerceros)";//echo $cons;
        $res = ExQuery($cons);
        while($fila = ExFetch($res))
        {
            $NomPaciente[$fila[0]] = "$fila[1] $fila[2] $fila[3] $fila[4]";
        }
    }
    if($LAutoId)
    {
        $cons = "Select Autoid,Nombreprod1,UnidadMedida,Presentacion,Codigo1 from Consumo.CodProductos
        Where AlmacenPpal = '$AlmacenPpal' and Anio = '$Anio' and Autoid in($LAutoId)";
        $res = ExQuery($cons);
        while($fila = ExFetch($res))
        {
            $NomMedicamento[$fila[0]] = "$fila[1] $fila[2] $fila[3]";
            $Codigo[$fila[0]] = $fila[4];
        }
    }
    if($LTerceros && $LAutoId)
    {
        $cons = "Select Paciente,Autoid,Hora,Cantidad,Via from salud.horacantidadxmedicamento Where
        Compania='$Compania[0]' and AlmacenPpal='$AlmacenPpal'
        and tipo = 'P' and Estado = 'AC' and Paciente in ($LTerceros) and AutoId in ($LAutoId)";
        $res = ExQuery($cons);
        while($fila = ExFetch($res))
        {
            if(!$Frecuencia[$fila[0]][$fila[1]]){$Frecuencia[$fila[0]][$fila[1]] = "$fila[3]($fila[2])";}
            else{$Frecuencia[$fila[0]][$fila[1]] = $Frecuencia[$fila[0]][$fila[1]]."- $fila[3]($fila[2])";}
            $Via[$fila[0]][$fila[1]] = $fila[4];
        }
    }
    
    if($MatrizMxP)
    {
        ?>
        <table style='font : normal normal small-caps 12px Tahoma;' border="1" width="100%" bordercolor="#e5e5e5">
            <?
            while(list($Pabellonw,$MxP1) = each($MatrizMxP))
            {
                ?>
                <tr><td align ="center" style="font-weight: bold; font-size: 15" colspan="7">
                    <? echo "$Compania[0]";?><br>
                    Lista de Medicamentos despachados por paciente<br>
                    Desde <? echo $FechaI ?> Hasta <? echo $FechaF ?><br>
                    <? echo "Pabellon $Pabellonw"?>
                </td></tr>
                <tr bgcolor="#e5e5e5"><td>Codigo</td><td>Medicamento</td><td>Frecuencia</td><td>Via</td>
                        <td>Despacho</td><td>Devolucion</td><td>Total</td><td>No.Despacho</td></tr><?
                while(list($Numero,$MxP2) = each($MxP1))
                {
                    //echo "<tr><td>$Numero</td></tr>";
                    while(list($Cedula,$MxP3) = each($MxP2))
                    {
                        echo "<tr><td colspan='7' bgcolor='#e5e5e5' style='font-weight:bold'>
                        $OrdenPacientes[$Cedula].$NomPaciente[$Cedula]($Cedula)</td></tr>";
                        while(list($AutoId,$Cantidad) = each($MxP3))
                        {
                            echo "<tr><td>$Codigo[$AutoId]</td><td>".utf8_decode($NomMedicamento[$AutoId])."</td>
                            <td align='right'>".$Frecuencia[$Cedula][$AutoId]."</td>
                            <td>".$Via[$Cedula][$AutoId]."&nbsp;</td>
                            <td align='right'>".number_format($Cantidad,2)."</td>
                            <td align='right'>".number_format($Devolucion[$Numero][$Cedula][$AutoId],2)."</td>
                            <td align='right'>".number_format($Cantidad - $Devolucion[$Numero][$Cedula][$AutoId],2)."</td>
                            <td>$Numero</td>
                            </tr>";
                        }
                    }
                }
                ?>
                </table>
                <div class="Salto"></div>
                <br>
                <table style='font : normal normal small-caps 12px Tahoma;' border="1" width="100%" bordercolor="#e5e5e5">
            <? }
            ?>
        </table>
        <?
    }
    if($MatrizM)
    {
        ?>
        <table style='font : normal normal small-caps 12px Tahoma;' border="1" width="100%" bordercolor="#e5e5e5">
        <?
        while(list($Pabellonw,$MM) = each($MatrizM))
        {
            ?>
                <tr><td align ="center" style="font-weight: bold; font-size: 15" colspan="7">
                    <? echo "$Compania[0]";?><br>
                    Consolidado total de Medicamentos despachados por Pabellon<br>
                    Desde <? echo $FechaI ?> Hasta <? echo $FechaF ?><br>
                    <? echo "Pabellon $Pabellonw"?>
                </td></tr>
                <tr bgcolor="#e5e5e5"><td>Codigo</td><td>Medicamento</td><td>Despacho</td><td>Devolucion</td>
                    <td>Total</td></tr>
            <?
            while(list($AutoId,$Cantidad) = each($MM))
            {
                ?>
                <tr><td><? echo $Codigo[$AutoId]?></td><td><? echo utf8_decode($NomMedicamento[$AutoId])?></td>
                <td align="right"><? echo number_format($Cantidad,2)?></td>
                <td align="right"><? echo number_format($Devolucion[$Pabellonw][$AutoId],2)?></td>
                <td align="right"><? echo number_format($Cantidad - $Devolucion[$Pabellonw][$AutoId],2)?></td>
                </tr>
                <?
            }
        }
        ?>
        </table>
        <?
    }
    if($MatrizConso)
    {
       ?>
        <table style='font : normal normal small-caps 12px Tahoma;' border="1" width="100%" bordercolor="#e5e5e5">
        <tr><td align ="center" style="font-weight: bold; font-size: 15" colspan="7">
                <? echo "$Compania[0]";?><br>
                Consolidado total de Medicamentos despachados<br>
                Desde <? echo $FechaI ?> Hasta <? echo $FechaF ?><br>
            </td>
        </tr>
        <tr bgcolor="#e5e5e5"><td>Codigo</td><td>Medicamento</td>
                <td>Despacho</td><td>Devolucion</td><td>Total</td>
        <?
        while(list($AutoId,$Cantidad) = each($MatrizConso))
            {
                ?>
                <tr><td><? echo $Codigo[$AutoId]?></td><td><? echo utf8_decode($NomMedicamento[$AutoId])?></td>
                <td align="right"><? echo number_format($Cantidad,2)?></td>
                <td align="right"><? echo number_format($TotDevolucion[$AutoId],2)?></td>
                <td align="right"><? echo number_format($Cantidad - $TotDevolucion[$AutoId],2)?></td>
                </tr>
                <?
            }
        ?>
        </table>
        <?
    }
?>
</form>