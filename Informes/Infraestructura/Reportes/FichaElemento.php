<?
if($DatNameSID){session_name("$DatNameSID");}
session_start();
include("Funciones.php");
$ND = getdate();
$cons = "Select Departamento from Central.Departamentos Where Codigo = '$Compania[18]'";
$res = ExQuery($cons);
$fila = ExFetch($res); $Departamento = $fila[0];
?>
<style>
    .Sdp
    {
        page-break-after: always;
    }
</style>
<?
if(!$Origen){$Arreglo_Autoid = array($AutoId);}
else
{
    $cons = "Select Autoid from Infraestructura.Bajas
    Where Compania = '$Compania[0]' and Numero = '$Numero'";
    $res = ExQuery($cons);
    while($fila=ExFetch($res))
    {
        if(!$Arreglo_Autoid){$Arreglo_Autoid = array($fila[0]);}
        else{array_push($Arreglo_Autoid,$fila[0]);}
    }
}
foreach($Arreglo_Autoid as $Autoid_Elemento)
{
    ?>
    <table style='font : normal normal small-caps 12px Tahoma;' border="0" bordercolor="#e5e5e5" align="center">
        <tr style="font-weight: bold;" align="center">
            <td><font size="4"><b><?echo $Compania[0]?></b></font></td>
        </tr>
        <tr align="center">
            <td><?echo "<b>$Compania[1]</b><br>$Compania[2] Telefono: $Compania[3]<br>$Compania[7] - $Departamento"?></td>
        </tr>
    </table>
    <?
    $cons = "Select Nombre,Codigo,Grupo,Impacto,
    Modelo,Serie,Marca,Caracteristicas,
    Documentacion,Observaciones,FechaAdquisicion,CostoInicial,
    Tipo,FechaOrdenCompra,NumeroOrdenCompra,EstadoOrdenCompraX,
    FechaCompra,NumeroCompra,EstadoComprasX,
    FechaCrea,UsuarioCrea,Estado,
    DepreciarEn,DepreciarDurante,DepAcumulada
    from Infraestructura.CodElementos 
    Where AutoId=$Autoid_Elemento and Compania='$Compania[0]' LIMIT 1";
    $res = ExQuery($cons);
    $fila = ExFetch($res);
    $Nombre_Elemento = $fila[0];$Codigo_Elemento = $fila[1];$Grupo_Elemento=$fila[2];$Impacto_Elemento=$fila[3];
    $Modelo_Elemento=$fila[4];$Serie_Elemento=$fila[5];$Marca_Elemento=$fila[6];$Caracteristicas_Elemento=trim($fila[7]);
    $Documentacion_Elemento=trim($fila[8]);$Observaciones_Elemento=trim($fila[9]);$FechaAdquisicion_Elemento = $fila[10];
    $CostoInicial_Elemento = $fila[11]; $Tipo_Elemento = $fila[12];$FechaOrdenCompra_Elemento = $fila[13]; $NumeroOrdenCompra_Elemento = $fila[14];
    $ApruebaOC_Elemento = $fila[15];
    $FechaCompra_Elemento = $fila[16];$NumeroCompra_Elemento=$fila[17];$Ingresa_Elemento=$fila[18];
    $FechaCrea_Elemento = $fila[19]; $UsuarioCrea_Elemento = $fila[20]; $EstadoIni_Elemento = $fila[21];
    $DepEn_Elemento = str_replace("ni","&ntilde;",$fila[22]);$DepDur_Elemento=$fila[23];$DepAcumulada_Elemento=$fila[24];
    ?>
    </br>
    <table style='font : normal normal small-caps 12px Tahoma;' border="1" bordercolor="#e5e5e5" width="100%">
        <tr bgcolor="#e5e5e5" style="font-weight: bold">
            <td align="center" colspan="6">FICHA TECNICA PARA EL ELEMENTO:<?echo $Nombre_Elemento?></td>
        </tr>
        <tr>
            <td bgcolor="#e5e5e5">Grupo:</td><td><?echo $Grupo_Elemento?></td>
            <td bgcolor="#e5e5e5">Codigo:</td><td><?echo $Codigo_Elemento?></td>
            <td bgcolor="#e5e5e5">Impacto:</td><td><?echo $Impacto_Elemento?></td>
        </tr>
        <tr>
            <td bgcolor="#e5e5e5">Modelo:</td><td><?echo $Modelo_Elemento?></td>
            <td bgcolor="#e5e5e5">Serie:</td><td><?echo $Serie_Elemento?></td>
            <td bgcolor="#e5e5e5">Marca:</td><td><?echo $Marca_Elemento?></td>
        </tr>
        <?
        if($Caracteristicas_Elemento || $Caracteristicas_Elemento!="")
        {
            ?>
            <tr>
                <td bgcolor="#e5e5e5">Caracteristicas:</td><td colspan="5"><?echo $Caracteristicas_Elemento?></td>
            </tr>
            <?
        }
        if($Documentacion_Elemento || $Documentacion_Elemento!="")
        {
            ?>
            <tr>
                <td bgcolor="#e5e5e5">Documentacion:</td><td colspan="5"><?echo $Documentacion_Elemento?></td>
            </tr>
            <?
        }
        if($Observaciones_Elemento || $Observaciones_Elemento!="")
        {
            ?>
            <tr>
                <td bgcolor="#e5e5e5">Observaciones:</td><td colspan="5"><?echo $Observaciones_Elemento?></td>
            </tr>
            <?
        }
        ?>
        <tr>
            <td bgcolor="#e5e5e5">Fecha de Adquisicion:</td><td><?echo $FechaAdquisicion_Elemento?></td>
            <td bgcolor="#e5e5e5">Costo Inicial:</td><td><?echo $CostoInicial_Elemento?></td>
            <td bgcolor="#e5e5e5">Tipo de Registro:</td><td><?echo $Tipo_Elemento?></td>
        </tr>
        <?
        if($Tipo_Elemento != "Levantamiento Inicial")
        {
            ?>
            <tr>
                <td bgcolor="#e5e5e5">Fecha Orden de Compra:</td><td><?echo $FechaOrdenCompra_Elemento?></td>
                <td bgcolor="#e5e5e5">Numero Orden Compra:</td><td><?echo $NumeroOrdenCompra_Elemento?></td>
                <td bgcolor="#e5e5e5">Aprueba:</td><td><?echo $ApruebaOC_Elemento?></td>
            </tr>
            <tr>
                <td bgcolor="#e5e5e5">Fecha Compra:</td><td><?echo $FechaCompra_Elemento?></td>
                <td bgcolor="#e5e5e5">Numero Compra:</td><td><?echo $NumeroCompra_Elemento?></td>
                <td bgcolor="#e5e5e5">Ingresa:</td><td><?echo $Ingresa_Elemento?></td>
            </tr>
            <tr>
                <td colspan="4" bgcolor="#e5e5e5">&nbsp;</td>
            <?
        }
        else
        {
            ?>
            <tr>
                <td bgcolor="#e5e5e5">Fecha Creacion:</td><td><?echo $FechaCrea_Elemento?></td>
                <td bgcolor="#e5e5e5">Usuario Creacion:</td><td><?echo $UsuarioCrea_Elemento?></td>
            <?
        }
        ?>
                <td bgcolor="#e5e5e5">Estado Inicial:</td><td><?echo $EstadoIni_Elemento?></td>
            </tr>
    </table>
    <hr></hr>
    <!---UBICACIONES----->
    <table style='font : normal normal small-caps 12px Tahoma;' border="1" bordercolor="#e5e5e5" width="100%">
        <tr bgcolor="#e5e5e5" style="font-weight: bold">
            <td align="center" colspan="6">UBICACIONES</td>
        </tr>
        <tr bgcolor="#e5e5e5" style="font-weight: bold">
            <td>Fecha Inicial</td><td>Fecha Final</td><td>Responsable</td><td>Ubicacion</td><td>SubUbicacion</td>
        </tr>
        <?
        $cons = "Select FechaIni,FechaFin,Responsable,CentroCostos,SubUbicacion
            from Infraestructura.Ubicaciones Where Compania='$Compania[0]' and Autoid=$Autoid_Elemento order by FechaIni";
        $res = ExQuery($cons);
        while($fila = ExFetch($res))
        {
            $cons2 = "Select CentroCostos from Central.CentrosCosto 
            Where COmpania = '$Compania[0]' and Codigo = '$fila[3]' order by Anio desc LIMIT 1";
            $res2 = ExQuery($cons2);
            $fila2 = ExFetch($res2);
            $CC_Ubicacion = $fila2[0];
            $cons2 = "Select PrimNom,SegNom,PrimApe,SegApe from Central.Terceros 
            Where Compania='$Compania[0]' and Identificacion like '$fila[2]%' LIMIT 1";
            $res2 = ExQuery($cons2);
            $fila2 = ExFetch($res2);
            $Responsable_Ubicacion = "$fila2[0] $fila2[1] $fila2[2] $fila2[3]";
            if(!$fila[4])
            ?>
            <tr>
                <td><?echo $fila[0]?></td>
                <td><?echo $fila[1]?></td>
                <td><?echo "$Responsable_Ubicacion ($fila[2])";?></td>
                <td><?echo "$CC_Ubicacion ($fila[3])";?></td>
                <td><?echo $fila[4]?></td>
            </tr>
            <?
        }
        ?>
    </table>
    <hr></hr>
    <!---DEPRECIACIONES----->
    <table style='font : normal normal small-caps 12px Tahoma;' border="1" bordercolor="#e5e5e5" width="100%">
        <tr bgcolor="#e5e5e5" style="font-weight: bold">
            <td align="center" colspan="6">DEPRECIACION</td>
        </tr>
        <tr>
            <td bgcolor="#e5e5e5">Tiempo de Depreciacion:</td><td><? echo "$DepDur_Elemento $DepEn_Elemento";?></td>
            <td bgcolor="#e5e5e5">Depreciacon Acumulada:</td><td><? echo number_format($DepAcumulada_Elemento,2);?></td>
        </tr>
        <?
        $cons = "Select FechaDepreciacion,VrDepreciacion,FechaEjecucion,UsuarioEjecuta
        from Infraestructura.Depreciaciones Where Compania = '$Compania[0]' and Codigo='$Codigo_Elemento'
        order by FechaDepreciacion";
        $res = ExQuery($cons);
        if(ExNumRows($res)>0)
        {
            ?>
            <tr bgcolor="#e5e5e5" style="font-weight: bold">
                <td>Fecha Depreciacion</td><td>Valor</td><td>Fecha Ejecucion</td><td>Usuario</td>
            </tr>
            <?
        }
        while($fila = ExFetch($res))
        {
            $DepAcumulada_Elemento = $DepAcumulada_Elemento + $fila[1];
            ?>
            <tr>
                <td><? echo $fila[0];?></td>
                <td><? echo number_format($fila[1],2);?></td>
                <td><? echo $fila[2];?></td>
                <td><? echo $fila[3];?></td>
            </tr>
            <?
        }
        $ValorActual = $CostoInicial_Elemento - $DepAcumulada_Elemento;
        ?>
        <tr>
            <td bgcolor="#e5e5e5" colspan="3" align="right">Valor Actual:</td><td><? echo number_format($ValorActual,2);?></td>
        </tr>
    </table>
    <!---MANTENIMIENTOS----->
    <hr>
    <table style='font : normal normal small-caps 12px Tahoma;' border="1" bordercolor="#e5e5e5" width="100%">
    <?
    $cons = "Select date(fechaSolicitud),UsuarioSolicitud,DetalleSolicitud,
    EstadoSolicitud,date(FechaAR),UsuarioAR,Encargado,
    date(FechUltRev),RepTecnico,TraRealizado,Observaciones,Repuestos,
    TotCosto,ClaseMantenimiento,TipoMantenimiento,
    date(FechEvaluacion),Evaluacion,date(fechaCierreCAso),NotaCierre
    from Infraestructura.Mantenimiento 
    Where Compania='$Compania[0]' and Autoid = $Autoid_Elemento 
    order by FechaSolicitud";
    $res = ExQuery($cons);
    if(ExNumRows($res)>0)
    {
        ?>
        <tr bgcolor="#e5e5e5" style="font-weight: bold">
            <td align="center" colspan="6">MANTENIMIENTOS</td>
        </tr>
        <?
        while($fila = ExFetch($res))
        {
            if($fila[6])
            {
                $consxx = "Select PrimNom,SegNom,PrimApe,SegApe from Central.Terceros 
                Where Compania='$Compania[0]' and Identificacion like '$fila[6]%' LIMIT 1";
                $resxx = ExQuery($consxx);
                $filaxx = ExFetch($resxx);
                $Encargado = "$filaxx[0] $filaxx[1] $filaxx[2] $filaxx[3]";
            }
            ?>
            <tr>
                <td bgcolor="#e5e5e5" colspan="6">SOLICITUD</td>
            </tr>
            <tr>
                <td bgcolor="#e5e5e5">Fecha:</td><td><?echo $fila[0]?></td>
                <td bgcolor="#e5e5e5" colspan="2">&nbsp;</td>
                <td bgcolor="#e5e5e5">Usuario:</td><td><?echo $fila[1]?></td>
            </tr>
            <tr>
                <td bgcolor="#e5e5e5">Detalle:</td><td colspan="5"><?echo $fila[2]?></td>
            </tr>
            <tr>
                <td bgcolor="#e5e5e5">Estado</td><td><?echo $fila[3]?></td>
                <td bgcolor="#e5e5e5">Usuario</td><td><?echo $fila[5]?></td>
                <td bgcolor="#e5e5e5">Fecha</td><td><?echo $fila[4]?></td>
            </tr>
            <?
            if($fila[6])
            {
                ?>
                <tr><td colspan="6">&nbsp;</td></tr>
                <tr><td colspan="6" bgcolor="#e5e5e5" align="center">ENCARGADO: <?echo $Encargado?></td></tr>
                <?
            }
            if($fila[7])
            {
                ?>
                <tr>
                    <td bgcolor="#e5e5e5">Fecha de Revision</td><td><? echo $fila[7]?></td>
                    <td bgcolor="#e5e5e5">Clase de Mantenimiento</td><td><? echo $fila[13]?></td>
                    <td bgcolor="#e5e5e5">Tipo de Mantenimiento</td><td><? echo $fila[14]?></td>
                </tr>
                <tr>
                    <td colspan="6">
                    <? echo "<b>Reporte Tecnico:</b><br>
                        $fila[8]<br>
                    <b>Trabajo Realizado:</b><br>
                        $fila[9]<br>
                    <b>Observaciones:</b><br>
                        $fila[10]<br>
                    <b>Repuestos:</b><br>
                        $fila[11]<br>
                    <b>Costo Total:</b><br>
                        $fila[12]<br>";?>
                    </td>
                </tr>
                <?
            }
            if($fila[15])
            {
                ?>
                <tr><td colspan="6">&nbsp;</td></tr>
                <tr><td bgcolor="#e5e5e5">Evaluacion</td><td><?echo $fila[16]?></td>
                <td bgcolor="#e5e5e5">Fecha</td><td><?echo $fila[15]?></td></tr>
                <?
            }
            if($fila[17])
            {
                ?>
                <tr><td colspan="6">&nbsp;</td></tr>
                <tr><td bgcolor="#e5e5e5">Cierre de Caso</td><td><?echo $fila[17]?></td>
                <td bgcolor="#e5e5e5">Nota de Cierre</td><td colspan="3"><? echo $fila[18]?></td></tr>
                <?
            }
            ?><tr><td colspan="6"><hr></td></tr><?
        }
    }
    ?>
    </table>
    <?
    if($Origen)
    {
        ?><br class="Sdp" /><?
    }
}