<?
    if($DatNameSID){session_name("$DatNameSID");}
    session_start();
    include("Funciones.php");
    $ND=getdate();
?>
<script language="Javascript">
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
        $cons = "Insert into Salud.NORegistroMedicamentos
        (Compania,AlmacenPpal,NumServicio,Cedula,AutoId,
        UsuarioCre,FechaCre,Hora,Cantidad,Tipo,NumOrden,Motivo,IdEscritura)
        values
        ('$Compania[0]','$AlmacenPpal',$NumServicio,'$Paciente[1]',$AutoId,
        '$usuario[1]','$ND[year]-$ND[mon]-$ND[mday]',$Hora,$CantNoReg,'N',$NumOrden,'$MotivoNoReg',$IdEscritura)";
        //echo $cons;
        ExQuery($cons);
        ?>
        <script language="Javascript">
            parent.document.location.href="/HistoriaClinica/Formatos_Fijos/HojaMeds.php?DatNameSID=<?echo $DatNameSID?>";
            CerrarThis();
        </script>    
        <?
    }
?>
<body background="/Imgs/Fondo.jpg">
<form name="FORMA" method="post">
    <input type="hidden" name="DatNameSID" value="<?echo $DatNameSID?>" />
    <input type="hidden" name="AutoId" value="<?echo $AutoId?>" />
    <input type="hidden" name="NumServicio" value="<?echo $NumServicio?>" />
    <input type="hidden" name="NumOrden" value="<?echo $NumOrden?>" />
    <input type="hidden" name="IdEscritura" value="<?echo $IdEscritura?>" />
    <input type="hidden" name="AlmacenPpal" value="<?echo $AlmacenPpal?>" />
    <input type="hidden" name="Hora" value="<?echo $Hora?>" />
    <div align="right">
        <button type="button" name="Cerrar" title="Cerrar" onclick="CerrarThis()">
            <img src="/Imgs/b_drop" />
        </button>
    </div>
    <table style='font : normal normal small-caps 12px Tahoma;' border="1" bordercolor="#e5e5e5">
        <tr>
            <td align="center" colspan="3" bgcolor="#e5e5e5">
                <font size="3">
                <b>No Registrar el Medicamento <?echo $Medicamento?> para las <?echo $Hora?> horas</b>
                </font>
            </td>
        </tr>
        <tr>
            <td align="right">Cantidad</td>
            <td>
                <select name="CantNoReg">
                    <?
                    for($i=1;$i<=$Cantidad;$i++)
                    {
                        echo "<option value='$i'>$i</option>";
                    }
                    ?>
                </select>
            </td>
        </tr>
        <tr>
            <td colspan="3" align="center">
                Motivo
            </td>
        </tr>
        <tr>
            <td colspan="3">
                <textarea name="MotivoNoReg" style=" width: 100%"></textarea>
            </td>
        </tr>
        <tr>
            <td align="center" colspan="3">
                <input type="submit" name="Guardar" value="Guardar" />
            </td>
        </tr>
    </table>
</form>
</body>