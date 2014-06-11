<?
if($DatNameSID){session_name("$DatNameSID");}
session_start();
include ("Funciones.php");
$cons = "Select Nombreprod1,UnidadMedida,Presentacion from Consumo.CodProductos Where AlmacenPpal='$AlmacenPpal'
and Compania = '$Compania[0]' and Autoid=$AutoId";
$res = ExQuery($cons);
$fila = ExFetch($res);
$Producto = "$fila[0] $fila[1] $fila[2]";
?>
<script language="javascript">
function VerFecha(Objeto,Doc)
{
    b = true;
    if(Doc == 1)
    {
        var Anio = parent.document.FORMA.Anio.value;
        var MesDoc = parent.document.FORMA.Mes.value; if (parseInt(MesDoc)<10){MesDoc = "0"+MesDoc;}
        var DiaDoc = parent.document.FORMA.Dia.value; if (parseInt(DiaDoc)<10){DiaDoc = "0"+DiaDoc;}
        Fecha = Anio+"-"+MesDoc+"-"+DiaDoc;
        if(Objeto.value < Fecha)
        {
            alert("El lote que intenta ingresar se encuentra vencido");
            b=false;
        }
        else
        {
            if(parseInt(document.FORMA.CantidadLote.value)>parseInt(document.FORMA.Cantidadx.value))
            {
                b=false;
            }
        }
    }
    
    if(document.FORMA.RegINVIMA.value=="" || document.FORMA.FechaVenc.value=="" || document.FORMA.CantidadLote.value=="")
    {
        b = false;
    }
    if(document.FORMA.ValidaINVIMA.value != "1")
    {
        alert("El registro INVIMA debe ser seleccionado desde es asistente de busqueda, si no se encuentra por favor realizar la configuracion correspondiente");
        b=false;
    }
    return b;
}
function CerrarThis()
{
    <?
    if($Tipo=="Saldo Inicial")
    {
        ?>
        parent.AbrirSaldoInicial();
        <?
    }
    else
    {
    ?>
        parent.document.getElementById('FrameOpener').style.position='absolute';
        parent.document.getElementById('FrameOpener').style.top='1px';
        parent.document.getElementById('FrameOpener').style.left='1px';
        parent.document.getElementById('FrameOpener').style.width='1';
        parent.document.getElementById('FrameOpener').style.height='1';
        parent.document.getElementById('FrameOpener').style.display='none';
        parent.frames.NuevoMovimiento.document.FORMA.action="";
        parent.frames.NuevoMovimiento.document.FORMA.submit();
    <?
    }
    ?>

}
</script>
<?
if($Cerrar)
{
    if($Tipo=="Saldo Inicial"){$adCons = "or Numero IS NULL";}
    if($Tipo != "Saldo Inicial"){$adconsE = " and TMPCOD='$TMPCOD'";}
    $cons = "Delete from COnsumo.Lotes Where Compania='$Compania[0]'
    and AlmacenPpal='$AlmacenPpal' and AutoId='$AutoId' and Tipo='$Tipo' and Cerrado=0
    and (Numero='$Numero' $adCons) $adconsE";
    $res = exQuery($cons);
    ?><script language="javascript">CerrarThis();</script><?
}
if($Eliminar)
{
    while(list($cad,$val)=each($Eliminar))
    {
        if($Tipo=="Saldo Inicial"){$adCons = "or Numero IS NULL";}
        if($Tipo != "Saldo Inicial"){$adconsE = " and TMPCOD='$TMPCOD'";}
        $valores = explode("|",$cad);
        $cons = "Delete from Consumo.Lotes Where Compania='$Compania[0]' and AlmacenPpal='$AlmacenPpal' and AutoId='$AutoId' and Tipo='$Tipo'
        and Lote='$valores[0]' and Vence='$valores[1]' and Cantidad='$valores[2]' and (Numero='$valores[3]' $adCons) $adconsE";
        $res = ExQuery($cons);
        $cons = "Update Consumo.Lotes set Cerrado=0 Where Compania='$Compania[0]' and AlmacenPpal='$AlmacenPpal' and AutoId=$AutoId and Tipo='$Tipo'";
        $res = ExQuery($cons);
    }
}
if($Terminar)
{
    if($Tipo != "Saldo Inicial"){$adcons = " and Numero='$Numero' and TMPCOD='$TMPCOD'";}
    $cons = "Update Consumo.Lotes set Cerrado=1 Where Compania='$Compania[0]' and AlmacenPpal='$AlmacenPpal' and AutoId=$AutoId and Tipo='$Tipo'
    $adcons";
    $res = ExQuery($cons);
    ?><script language="javascript">CerrarThis();</script><?
}
if($Guardar)
{
    if($Tipo=="Saldo Inicial"){$adCons = "or Numero IS NULL";$Numero="$Anio";}
    $cons = "Select * from Consumo.Lotes Where Compania='$Compania[0]' and AlmacenPpal='$AlmacenPpal' and AutoId=$AutoId and Tipo='$Tipo'
    and Lote='$Lote' and Vence='$FechaVenc' and Cantidad='$CantidadLote' and (Numero='$Numero' $adCons)";
    $res = ExQuery($cons);
    if(ExNumRows($res)==0){$Insertar=1;}
    if($Insertar)
    {
        if($Lote && $CantidadLote && $FechaVenc)
        {
            $cons = "Insert into Consumo.Lotes (Compania,AlmacenPpal,AutoId,Cantidad,
            Lote,Vence,Tipo,Numero,TMPCOD,Laboratorio,Presentacion,regInvima,Temperatura)
            values('$Compania[0]','$AlmacenPpal',$AutoId,$CantidadLote,'$Lote','$FechaVenc','$Tipo','$Numero',
            '$TMPCOD','$Laboratorio','$Presentacion','$RegINVIMA','$Temperatura')";
            $res = ExQuery($cons);
        }
    }

}
?>
<script language="javascript" src="/Funciones.js"></script>
<script language='javascript' src="/calendario/popcalendar.js"></script>
<form name="FORMA" method="post">
    <input type="hidden" name="DatNameSID" value="<? echo $DatNameSID?>"/>
    <input type="hidden" name="AlmacenPpal" value="<? echo $AlmacenPpal?>"/>
    <input type="hidden" name="AutoId" value="<? echo $AutoId?>"/>
    <input type="hidden" name="Cantidad" value="<? echo $Cantidad?>" />
    <input type="hidden" name="Tipo" value="<? echo $Tipo?>" />
    <input type="hidden" name="Numero" value="<? echo $Numero?>" />
    <input type="hidden" name="TMPCOD" value="<? echo $TMPCOD?>" />
    <input type="hidden" name="Anio" value="<? echo $Anio?>" />
	  <input type="hidden" name="Temperatura" value="<? echo $Temperatura?>" />
    <input type="submit" name="Terminar" disabled value="Terminar" onclick="parent.Ocultar();" />
    <input type="submit" name="Cerrar" value="Cerrar"
           onClick="parent.Ocultar();return(confirm('Su informacion se eliminara si no termina de llenar los lotes. Desea Continuar?'));" />
    <table style="font : small-caps 12px Tahoma;" border="1" bordercolor="#e5e5e5" width="100%">
        <tr bgcolor="#e5e5e5" style="font-weight: bold" align="center"><td colspan="6">Datos t&eacute;cnicos <? echo $Producto?></td></tr>
    <tr bgcolor="#e5e5e5" style="font-weight: bold" align="center">
        <td>Registro INVIMA</td><td>Lote</td><td>Vencimiento</td><td>Cantidad</td><td>Temperatura (Grados)</td><td>&nbsp;</td>
    </tr>
    <tr align="center">
        <td>
            <input type="text" name="RegINVIMA" 
                onkeyup="ValidaINVIMA.value='';parent.Mostrar();parent.document.frames.Busquedas.location.href='Busquedas.php?AutoId=<?echo $AutoId?>&DatNameSID=<? echo $DatNameSID?>&Tipo=INVIMA&RegINVIMA='+this.value+'&AlmacenPpal=<?echo $AlmacenPpal?>&Anio='+parent.document.FORMA.Anio.value+'&Fecha='+parent.document.FORMA.Anio.value+'-'+parent.document.FORMA.Mes.value+'-'+parent.document.FORMA.Dia.value"
                onfocus="parent.Mostrar();parent.document.frames.Busquedas.location.href='Busquedas.php?AutoId=<?echo $AutoId?>&DatNameSID=<? echo $DatNameSID?>&Tipo=INVIMA&RegINVIMA='+this.value+'&AlmacenPpal=<?echo $AlmacenPpal?>&Anio='+parent.document.FORMA.Anio.value+'&Fecha='+parent.document.FORMA.Anio.value+'-'+parent.document.FORMA.Mes.value+'-'+parent.document.FORMA.Dia.value"
                />
            <input type="hidden" name="ValidaINVIMA" />
            <input type="hidden" name="Laboratorio" />
            <input type="hidden" name="Presentacion" />
        </td>
        <td>
            <input type="text" name="Lote" onKeyup="xLetra(this)" onKeydown="xLetra(this)" size="6" onfocus="parent.Ocultar()" />
        </td>
        <td>
            <input type="text" name="FechaVenc"
            onclick="popUpCalendar(this, this, 'yyyy-mm-dd')" onDblClick="this.value=''" readonly size="6" onfocus="parent.Ocultar()" />
        </td>
        <td>
           <input type="text" name="CantidadLote" onKeyup="xNumero(this)" onKeydown="xNumero(this)" style=" text-align: right" size="6"
           onBlur="campoNumero(this);if(parseInt(this.value)>parseInt(Cantidadx.value)){alert('Esta cantidad excede el total del ingresado');this.value='';};"
           onfocus="parent.Ocultar()"/>
        </td>
		
		 <td>
           <input type="text" name="Temperatura" onKeyup="xNumero(this)" onKeydown="xNumero(this)" style=" text-align: right" size="6" />
        </td>
		
        <td>
            <button type="submit" title="Guardar" name="Guardar" onClick="return VerFecha(FechaVenc);return Validar();parent.Ocultar()">
                <img src="/Imgs/b_save.png" />
            </button>
        </td>
    </tr>
    <?
    if($Tipo != "Saldo Inicial"){$adconsE = " and Numero='$Numero' and TMPCOD='$TMPCOD'";}
    $cons = "Select Laboratorio,Presentacion,Lote,Vence,Cantidad,Numero,RegINVIMA,Temperatura from Consumo.Lotes Where Compania='$Compania[0]' and AlmacenPpal='$AlmacenPpal' and AutoId='$AutoId' and Tipo='$Tipo'
    $adconsE order by Vence,Cantidad,Lote asc";
    $res = ExQuery($cons);
    $Cantidadx = $Cantidad;
    while($fila = ExFetch($res))
    {
        $Cantidadx = $Cantidadx - $fila[4];
        ?>
        <tr>
            <td><? echo "<b>$fila[6]</b><br><i>($fila[0] - $fila[1])</i>)"?></td><td align="center"><? echo $fila[2]?></td><td align="center"><? echo $fila[3]?></td>
            <td align="center"><? echo $fila[4]?></td>
            <td align="center"> <? echo $fila[7]?>  </td> <td>
                <button type="submit" name="Eliminar[<?echo "$fila[2]|$fila[3]|$fila[4]|$fila[5]"?>]"
                        title="Eliminar" style="background-color: white; border: white">
                    <img src="/Imgs/b_drop.png" title="Eliminar"/>
                </button>
            </td>
        </tr>
        <?


    }
    if($Cantidadx==0)
    {
        ?><script language="javascript">document.FORMA.Terminar.disabled=false;</script><?
    }
    else
    {
        ?><script language="javascript">document.FORMA.CantidadLote.value=<? echo $Cantidadx?>;</script><?
    }
    ?>
    <input type="hidden" name="Cantidadx" value="<? echo $Cantidadx?>"/>
    </table>
</form>