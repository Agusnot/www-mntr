<?
if($DatNameSID){session_name("$DatNameSID");}
session_start();
include("Funciones.php");
$ND = getdate();
?>
<script language="javascript" src="/Funciones.js"></script>
<script language='javascript' src="/calendario/popcalendar.js"></script>
<script language="javascript">
    function Validar()
    {
        for (i=0;i<document.FORMA.elements.length;i++)
        {
            if(document.FORMA.elements[i].type == "text" || document.FORMA.elements[i].type == "hidden")
            {
                if(document.FORMA.elements[i].value=="")
                {
                    alert("NO SE HAN CORREGIDO TODOS LAS INCONSISTENCIAS");
                    return false;
                }
            }
        }
    }
    function CerrarThis()
    {
        parent.document.getElementById('FrameOpener').style.position='absolute';
        parent.document.getElementById('FrameOpener').style.top='1px';
        parent.document.getElementById('FrameOpener').style.left='1px';
        parent.document.getElementById('FrameOpener').style.width='1';
        parent.document.getElementById('FrameOpener').style.height='1';
        parent.document.getElementById('FrameOpener').style.display='none';
    }
</script>
<?
if($Guardar)
{
    if($RegINVIMA)
    {
        while(list($Datos_Lote,$R_INVIMA)=each($RegINVIMA))
        {
            $Datos_Inserta = explode("|$DatNameSID|",$Datos_Lote);
            //Tipo-----------------Numero-------------------Cantidad
            //$Datos_Inserta[0]----$Datos_Inserta[1]-----$Datos_Inserta[2]
            $cons = "Insert into Consumo.Lotes
            (Compania,AlmacenPpal,AutoId,Cantidad,Lote,
            Vence,Tipo,Cerrado,Numero,TMPCOD,
            Laboratorio,RegINVIMA,Presentacion,Salidas)
            values
            ('$Compania[0]','$AlmacenPpal',$AutoId,$Datos_Inserta[2],'".$Lote[$Datos_Lote]."',
            '".$FechaVenc[$Datos_Lote]."','$Datos_Inserta[0]',1,'$Datos_Inserta[1]',NULL,
            '".$Laboratorio[$Datos_Lote]."','$R_INVIMA','".$Presentacion[$Datos_Lote]."',0)";
            //echo $cons;
            $res = ExQuery($cons);
        }
    }
    if($CUM)
    {
        while(list($Datos_CUM,$C_CUM)=each($CUM))
        {
            $Datos_Inserta = explode("|$DatNameSID|",$Datos_CUM);
            //Autoid-----------------RegINVIMA-----------Laboratorio-----------Presentacion
            //$Datos_Inserta[0]----$Datos_Inserta[1]-----$Datos_Inserta[2]----$Datos_Inserta[3]
            $cons = "Select * from Consumo.CUMSxProducto
            Where Compania='$Compania[0]' and AlmacenPpal = '$AlmacenPpal' and AutoId = $AutoId
            and Laboratorio = '$Datos_Inserta[2]' and Presentacion = '$Datos_Inserta[3]' and RegINVIMA = '$Datos_Inserta[1]'
            LIMIT 1";
            $res = ExQuery($cons);
            if(ExNumRows($res)==0)
            {
                $cons1 = "Insert into Consumo.CumsxProducto values
                ('$Compania[0]','$AlmacenPpal',$AutoId,'$Datos_Inserta[2]','$C_CUM','$Datos_Inserta[1]','$Datos_Inserta[3]')";
                $res1 = ExQuery($cons1);
                //echo $cons1;
            }
        }
    }
    ?>
    <script language="javascript">
        parent.document.FORMA.submit();
        CerrarThis();
    </script>
    <?
}
?>
    <body backgroud="/Imgs/Fondo.jpg">
<form name="FORMA" method>
    <div align="right" style="color: red">
        <button type="button" onclick="parent.Ocultar();CerrarThis();"><b>X</b></button>
    </div>
    <input type="hidden" name="DatNameSID" value="<?echo $DatNameSID?>" />
    <input type="hidden" name="AlmacenPpal" value="<? echo $AlmacenPpal?>" />
    <input type="hidden" name="AutoId" value="<?echo $AutoId?>" />
    <table style='font : normal normal small-caps 12px Tahoma;' border="1" bordercolor="#e5e5e5">
        <tr bgcolor="#e5e5e5" style="font-weight: bold">
            <td colspan="3">Inconsistencias encontradas para <? echo utf8_decode_seguro($Medicamento)?></td>
        </tr>
        <?
            $cons = "Select NoFactura,Numero,Comprobante,Cantidad from Consumo.movimiento
            Where Compania = '$Compania[0]' and AlmacenPpal = '$AlmacenPpal'
            and tipocomprobante = 'Entradas' and Estado = 'AC'
            AND autoid = $AutoId and Numero not in
            (
                Select Numero from Consumo.Lotes Where
                Compania = '$Compania[0]' and AlmacenPpal='$AlmacenPpal' 
                and Autoid=$AutoId and Cerrado = 1
            )";
            $res = ExQuery($cons);
            if(ExNumRows($res)>0)
            {
                ?>
                <tr>
                    <td colspan="3"><b>FACTURAS SIN LOTE REGISTRADO</b></td>
                </tr>
                <?
                while($fila = ExFetch($res))
                {
                    ?>
                    <tr bgcolor="#e5e5e5">
                        <td>Factura</td><td>Entrada</td><td>&nbsp;</td>
                    </tr>
                    <tr>
                        <td><?echo $fila[0]?></td>
                        <td><?echo $fila[1]?></td>
                        <td>&nbsp;</td>
                    </tr>
                    <tr>
                        <td>REGINVIMA</td><td>LOTE</td><td>Fecha de Vencimiento</td>
                    </tr>
                    <tr>
                        <td>
                            <input type="text" name="RegINVIMA[<?echo "$fila[2]|$DatNameSID|$fila[1]|$DatNameSID|$fila[3]";?>]" id="RegINVIMA-<?echo "$fila[0]-$fila[1]";?>" 
                                onkeyup="ValidaINVIMA.value='';parent.Mostrar();parent.document.frames.Busquedas.location.href='Busquedas.php?Factura=<?echo $fila[0]?>&Numero=<?echo $fila[1]?>&AutoId=<?echo $AutoId?>&DatNameSID=<? echo $DatNameSID?>&Tipo=INVIMA&RegINVIMA='+this.value+'&AlmacenPpal=<?echo $AlmacenPpal?>&Anio=<?echo $ND[year]?>&Fecha=<?echo "$ND[year]-$ND[mon]-$ND[mday]";?>';"
                                onfocus="parent.Mostrar();parent.document.frames.Busquedas.location.href='Busquedas.php?Factura=<?echo $fila[0]?>&Numero=<?echo $fila[1]?>&AutoId=<?echo $AutoId?>&DatNameSID=<? echo $DatNameSID?>&Tipo=INVIMA&RegINVIMA='+this.value+'&AlmacenPpal=<?echo $AlmacenPpal?>&Anio=<?echo $ND[year]?>&Fecha=<?echo "$ND[year]-$ND[mon]-$ND[mday]";?>';"
                                />
                            <input type="hidden" name="ValidaINVIMA[<?echo "$fila[2]|$DatNameSID|$fila[1]|$DatNameSID|$fila[3]";?>]" id="ValidaINVIMA-<?echo "$fila[0]-$fila[1]";?>" />
                            <input type="hidden" name="Laboratorio[<?echo "$fila[2]|$DatNameSID|$fila[1]|$DatNameSID|$fila[3]";?>]" id="Laboratorio-<?echo "$fila[0]-$fila[1]";?>"/>
                            <input type="hidden" name="Presentacion[<?echo "$fila[2]|$DatNameSID|$fila[1]|$DatNameSID|$fila[3]";?>]" id="Presentacion-<?echo "$fila[0]-$fila[1]";?>" />
                        </td>
                        <td>
                            <input type="text" name="Lote[<?echo "$fila[2]|$DatNameSID|$fila[1]|$DatNameSID|$fila[3]";?>]" onKeyup="xLetra(this)" onKeydown="xLetra(this)" size="6" onfocus="parent.Ocultar()" />
                        </td>
                        <td>
                            <input type="text" name="FechaVenc[<?echo "$fila[2]|$DatNameSID|$fila[1]|$DatNameSID|$fila[3]";?>]"
                            onclick="popUpCalendar(this, this,'yyyy-mm-dd')" onDblClick="this.value=''" readonly size="6" onfocus="parent.Ocultar()" />
                        </td>
                    </tr>
                    <tr>
                        <td colspan="3"><hr></td>
                    </tr>
                    <?
                }
            }
            
        ?>
    </table>
    <table style='font : normal normal small-caps 12px Tahoma;' border="1" bordercolor="#e5e5e5">
        <?
        $cons = "Select RegINVIMA,Presentacion,Laboratorio,Lotes.Numero,NoFactura
        from Consumo.Lotes,Consumo.Movimiento 
        Where Lotes.AlmacenPpal='$AlmacenPpal' and Movimiento.AlmacenPpal = '$AlmacenPpal'
        and Lotes.Compania='$Compania[0]' and Movimiento.Compania='$Compania[0]' 
        and Lotes.AutoId = $AutoId and Movimiento.Autoid=$AutoId
        and Movimiento.Numero = Lotes.Numero and Movimiento.Comprobante = Lotes.Tipo";
        //echo $cons;
        $res = ExQuery($cons);
        if(ExNumRows($res)>0)
        {
            ?>
            <tr>
                <td colspan="3">
                    <b>LOTES CON CUMS INEXISTENTES</b>
                </td>
            </tr>
            <?
            while($fila = ExFetch($res))
            {
                $cons1 = "Select * from Consumo.CUMSxProducto
                Where COmpania='$Compania[0]' and Autoid=$AutoId
                and RegINVIMA='$fila[0]' and Presentacion='$fila[1]'
                and Laboratorio='$fila[2]'";
                $res1 = ExQuery($cons1);
                if(ExNumRows($res1)==0)
                {
                    ?>
                    <tr bgcolor="#e5e5e5">
                        <td>Factura</td>
                        <td>RegINVIMA</td>
                        <td>Laboratorio</td>
                        <td>Presentacion</td>
                        <td>CUM</td>
                    </tr>
                    <tr>
                        <td><?echo $fila[4]?></td>
                        <td><?echo $fila[0]?></td>
                        <td><?echo $fila[2]?></td>
                        <td><?echo $fila[1]?></td>
                        <td><input type="text" name="CUM[<?echo "$AutoId|$DatNameSID|$fila[0]|$DatNameSID|$fila[2]|$DatNameSID|$fila[1]"?>]" size="8" /></td>
                    </tr>
                    <?
                }
            }
        }
        ?>
    </table>
    <center>
        <input type="submit" name="Guardar" value="Guardar" onclick="return Validar()" />
    </center>
</form>
    </body>