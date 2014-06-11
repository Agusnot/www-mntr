<?
    if($DatNameSID){session_name("$DatNameSID");}
    session_start();
    include("Funciones.php");
    $ND = getdate();
    $nocodi = explode("|",$AutoId);
    if($Guardar)
    {
        if(!$Tipo)
        {
            if(count($nocodi)==1){$Ai=$AutoId;}
            else{$Ai=0;$AdUpdate = "and Tercero='$nocodi[0]' and Descripcion='$nocodi[1]' and CC='$nocodi[2]' and SubUbicacion='$nocodi[3]'";}
            $cons = "Update Infraestructura.Mantenimiento set NotaRechazo='$NotaRechazo', Motivo='$motivorechazo' Where Compania='$Compania[0]' and AutoId=$Ai and FechaSolicitud='$FechaSolicitud'
            and EstadoSolicitud='Rechazado' $AdUpdate";
        }
        else
        {
            $cons = "Update Infraestructura.Bajas set MotivoRechazo='$motivorechazo', NotaRechazo='$NotaRechazo' Where Compania='$Compania[0]'
            and Numero='$Numero'";
        }
        $res = ExQUery($cons);
    }
?>
<script  language="javascript">
    function CerrarThis()
    {
        parent.document.getElementById('FrameOpener').style.position='absolute';
        parent.document.getElementById('FrameOpener').style.width='1';
        parent.document.getElementById('FrameOpener').style.height='1';
        parent.document.getElementById('FrameOpener').style.display='none';
    }
</script>
<?
    if(!$Tipo)
    {
        if(count($nocodi)==1){$Ai=$AutoId;}
        else{$Ai=0;$AdWhere = "and Tercero='$nocodi[0]' and Descripcion='$nocodi[1]' and CC='$nocodi[2]' and SubUbicacion='$nocodi[3]'";}
        $cons = "Select NotaRechazo,Motivo from Infraestructura.Mantenimiento Where Compania='$Compania[0]' and AutoId=$Ai and FechaSolicitud='$FechaSolicitud'
        and EstadoSolicitud='Rechazado' $AdWhere";
        $res = ExQuery($cons);
        $fila = ExFetch($res);
        $NotaRechazo = $fila[0];
        $motivorechazo = $fila[1];
    }
    else
    {
        $cons = "Select NotaRechazo,Motivorechazo from Infraestructura.Bajas Where Compania='$Compania[0]' and Numero='$Numero'
        and Estado='Rechazado'";
        $res = ExQuery($cons);
        $fila = ExFetch($res);
        $NotaRechazo = $fila[0];
        $motivorechazo = $fila[1];
    }

?>
<body background="/Imgs/Fondo.jpg">
<form method="post" name="FORMA">
<input type="hidden" name="AutoId" value="<? echo $AutoId?>" />
<input type="hidden" name="FechaSolicitud" value="<? echo $FechaSolicitud ?>" />
<input type="hidden" name="Tipo" value="<? echo $Tipo?>" />
<input type="hidden" name="Numero" value="<? echo $Numero?>" />
<div align="right">
<button name="Cerrar" title="Cerrar" onClick="CerrarThis()"><img src="/Imgs/b_drop.png" /></button>
<button type="submit" name="Guardar" title="Guardar"><img src="/Imgs/b_save.png" /></button>
</div>
    <table style='font : normal normal small-caps 12px Tahoma;' border="1" bordercolor="#e5e5e5" width="100%">
        <tr><td bgcolor="e5e5e5" style="font-weight:bold">Motivo Rechazo</td>
            <td><select name="motivorechazo" style=" width: 400px"><option></option>
                    <?
                    if(!$Tipo)
                    {
                        $cons = "Select motivo from Infraestructura.RechazoMantenimiento Where Compania='$Compania[0]' order by Motivo";
                    }
                    else
                    {
                        $cons = "Select motivo from Infraestructura.RechazoBajas Where Compania='$Compania[0]' order by Motivo";
                    }
                    $res = ExQuery($cons);
                    while($fila = ExFetch($res))
                    {
                        if($fila[0] == $motivorechazo){echo "<option selected value='$fila[0]'>$fila[0]</option>";}
                        else
                        {echo "<option value='$fila[0]'>$fila[0]</option>";}

                    }
                    ?>
                </select></td>
        </tr>
        <tr>
        <tr bgcolor="#e5e5e5" style=" font-weight: bold"><td align="center" colspan="2">Nota de rechazo</td></tr>
        <tr><td colspan="2"><textarea name="NotaRechazo" style=" width: 100%" rows="7" style=" background: '/Imgs/Fondo.jpg'"><? echo $NotaRechazo?></textarea></td></tr>
        </tr>
    </table>
</form>
</body>