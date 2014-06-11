<?
    if($DatNameSID){session_name("$DatNameSID");}
    session_start();
    include("Funciones.php");
    $ND = getdate();
?>
<script language="javascript">
	function CerrarThis()
	{
		parent.document.getElementById('FrameOpener').style.position='absolute';
		//parent.document.getElementById('FrameOpener').style.top='1px';
		//parent.document.getElementById('FrameOpener').style.left='1px';
		parent.document.getElementById('FrameOpener').style.width='1';
		parent.document.getElementById('FrameOpener').style.height='1';
		parent.document.getElementById('FrameOpener').style.display='none';
	}
</script>
<?
    if($Guardar)
    {
        if($Visar=="Visar"){$VoBo=1;}
        if($Visar=="noVisar"){$VoBo=0; if(!$notanovisado){$notanovisado="Sin Nota";}}
        $cons = "Update Consumo.Movimiento set Vobo=$VoBo, FechaVoBo='$ND[year]-$ND[mon]-$ND[mday] $ND[hours]:$ND[minutes]:$ND[seconds]', 
        UsuarioVoBo='$usuario[0]',NotaNoVisado='$notanovisado' Where Compania='$Compania[0]' and AlmacenPpal='$AlmacenPpal' and Comprobante='$Comprobante'
        and TipoComprobante='Orden de Compra' and Numero='$Numero'";
        $res = ExQuery($cons);
        ?>
        <script language="javascript">
           parent.document.FORMA.submit();
           CerrarThis();
        </script>
        <?
    }
?>
<form name="FORMA" method="post">
    <input type="hidden" name="Anio" value="<? echo $Anio?>" />
    <input type="hidden" name="AlmacenPpal" value="<? echo $AlmacenPpal?>" />
    <input type="hidden" name="Comprobante" value="<? echo $Comprobante?>" />
    <input type="hidden" name="Numero" value="<? echo $Numero?>" />
    <div align="right">
        <button name="Cerrar" title="Cerrar" onClick="CerrarThis()"><img src="/Imgs/b_drop.png" /></button>
        <button type="submit" name="Guardar" title="Guardar"><img src="/Imgs/b_save.png" /></button>
    </div>
    <table style='font : normal normal small-caps 12px Tahoma;' border="1" bordercolor="#e5e5e5" width="100%">
        <tr><td bgcolor="#e5e5e5" style=" font-weight: bold" align="center">Visar</td><td bgcolor="#e5e5e5" style=" font-weight: bold" align="center">No visar</td></tr>
        <tr align="center">
            <td><input type="radio" name="Visar" <? if($Visar=="Visar"){ echo " checked ";}?> value="Visar" onclick="FORMA.submit()"></td>
            <td><input type="radio" name="Visar" <? if($Visar=="noVisar"){ echo " checked ";}?>value="noVisar" onclick="FORMA.submit()"></td>
        </tr>
        <?
        if($Visar=="noVisar")
        {
            ?>
            <script language="javascript">
                parent.document.getElementById('FrameOpener').style.height='200';
            </script>
            <tr>
                <td colspan="2" bgcolor="#e5e5e5" style=" font-weight: bold" align="center">
                    Nota de no visado
                </td>
            </tr>
            <tr>
                <td colspan="2">
                    <textarea rows="3" name="notanovisado" <? if($notanovisado){ echo " readonly ";}?> style=" width: 100%"><? echo $notanovisado?></textarea>
                </td>
            </tr>
            <?
        }
        else
        {
           ?>
            <script language="javascript">
                parent.document.getElementById('FrameOpener').style.height='100';
            </script>
            <?
        }
        ?>
    </table>
</form>