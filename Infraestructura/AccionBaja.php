<?
	if($DatNameSID){session_name("$DatNameSID");}
	session_start();
	include("Funciones.php");
	$ND = getdate();
	if($Guardar)
	{
            if($Aprobar)
            {
                while(list($cad,$val) = each($Aprobar))
                {
                    $Upt = "";
                    if($val == "Aprobar"){ $Upt = " Estado = 'Aprobado'";}
                    if($val == "Rechazar"){ $Upt = " Estado = 'Rechazado'";}
                    if($Upt)
                    {
                        $cons = "Update Infraestructura.Bajas set $Upt,UsuarioAR = '$usuario[0]',
                        FechaAR='$ND[year]-$ND[mon]-$ND[mday] $ND[hours]:$ND[minutes]:$ND[seconds]'
                        Where Compania='$Compania[0]' and Numero = '$cad'";
                        $res = ExQuery($cons);
                    }
                }
            }
	}
	if($Reversar)
	{
            while(list($cad,$val)=each($Reversar))
            {
                $Valores = explode(",",$cad);
                $cons = "Update Infraestructura.Bajas set Estado='Solicitado' Where Compania='$Compania[0]'
                and Numero='$Valores[0]'";
                $res = ExQuery($cons);
            }
	}
	if($Ejecutar)
	{
            while(list($cad,$val) = each($Ejecutar))
            {
                $Valores = explode(",",$cad);
                //$Valores[0]->Numero, $Valores[1]->Cedula, $Valores[2]->CCDestino
                $cons = "Update Infraestructura.Bajas set Estado='Ejecutado' Where Compania='$Compania[0]' and Numero='$Valores[0]'";
                $res = ExQuery($cons);

                $cons = "Select AutoId from InfraEstructura.Bajas Where Compania='$Compania[0]' and Numero = '$Valores[0]'";
                $res = ExQuery($cons);
                while($fila = ExFetch($res))
                {
                    $cons1 = "Update Infraestructura.CodElementos set Tipo='Baja' Where Compania='$Compania[0]' and AutoId = $fila[0]";
                    $res1 = ExQuery($cons1);
                }
            }
	}
	if($Tipo == "Aprobar"){ $EstadoTipo = " and Bajas.Estado != 'Ejecutado'"; $Tit = "APROBACION";}
	if($Tipo == "Ejecutar"){ $EstadoTipo = " and Bajas.Estado = 'Aprobado'"; $Tit = "EJECUCION";}
	$cons = "Select Fecha,Numero,Bajas.Estado,Bajas.UsuarioCrea
	From InfraEstructura.Bajas, InfraEstructura.CodElementos Where
	Bajas.Compania='$Compania[0]' and CodElementos.Compania='$Compania[0]' and 
	Bajas.AutoId = CodElementos.AutoId and Bajas.Estado != 'ANULADO'  
	$EstadoTipo Group by Numero,Fecha,Bajas.Estado,Bajas.UsuarioCrea order by Numero";
	$res = ExQuery($cons);
?>
<script language="JavaScript">
    function AbrirHV(Acta_Baja)
    {
        open('/Informes/Infraestructura/Reportes/FichaElemento.php?DatNameSID=<? echo $DatNameSID?>&Origen=Bajas&Numero='+Acta_Baja,'','width=800,height=600,scrollbars=yes');
    }
    function AbrirNotaRechazo(Numero,e)
    {
        posY = e.clientY;
        sT = document.body.scrollTop;
        frames.FrameOpener.location.href="NotaRechazo.php?Tipo=Bajas&DatNameSID=<? echo $DatNameSID?>&Numero="+Numero;
        document.getElementById('FrameOpener').style.position='absolute';
        document.getElementById('FrameOpener').style.right='300px';
        document.getElementById('FrameOpener').style.top=(posY)+sT;
        document.getElementById('FrameOpener').style.display='';
        document.getElementById('FrameOpener').style.width='500';
        document.getElementById('FrameOpener').style.height='255';
    }
</script>
<body background="/Imgs/Fondo.jpg">
<form name="FORMA" method="post">
<input type="hidden" name="DatNameSID" value="<? echo $DatNameSID?>" />
<table border="1" bordercolor="#e5e5e5" width="50%" style="font-family:<? echo $Estilo[8]?>;font-size:12;font-style:<? echo $Estilo[10]?>" align="center">
	<tr bgcolor="#e5e5e5" style="font-weight:bold" align="center">
    	<td colspan="6"><? echo $Tit;?> DE BAJA</td>
    </tr>
    <tr bgcolor="#e5e5e5" style="font-weight:bold" align="center">
    	<td>Fecha</td><td colspan="2">Acta</td><td>Usuario solicita</td><td>Accion</td>
        <?
        	while($fila = ExFetch($res))
                {
                    echo "<tr><td align='center'>$fila[0]</td><td align='center' title='Ver acta de baja'
                    onMouseOver=\"this.bgColor='#AAD4FF'\" onMouseOut=\"this.bgColor='#FFFFFF'\"
                    onClick=\"open('/Informes/Infraestructura/Formatos/Bajas.php?Anio=$ND[year]&DatNameSID=$DatNameSID&Numero=$fila[1]','','width=800,height=600,scrollbars=yes');\">
                    $fila[1]</td>
                    <td title='Ver Hoja de vida de los elementos relacionados' width='15px'>
                        <button type='button' name='VerHV' onclick=\"AbrirHV('$fila[1]')\" style='height:20px'>
                            <img src='/Imgs/b_sbrowse.png' />
                        </button>
                    </td>    
                    <td>$fila[3]</td>
                    ";
                    if($Tipo == "Aprobar")
                    {
                        if($fila[2]=="Solicitado")
                        {
                            ?>
                            <td>
                            <select name="Aprobar[<? echo $fila[1]?>]" style="width:100%">
                                <option></option>
                                <option value="Aprobar">Aprobar</option>
                                <option value="Rechazar">Rechazar</option>
                            </select></td>
                            <?
                        }
                        else
                        {
                        ?>
                            <td align="center">
                            <button type="submit" name="Reversar[<? echo $fila[1];?>]"
                            title="Reversar <? if($fila[2]=="Aprobado"){ echo " Aprobacion";}else{ echo " Rechazo";}?>"><img src="/Imgs/b_drop.png" /></button>
                            <?
                            if($fila[2]=="Rechazado")
                            {
                            ?>
                                <button type="button" name="NotaRechazo[<? echo $fila[1]?>]" title="Adjuntar nota de rechazo"
                                        onclick="AbrirNotaRechazo('<? echo $fila[1]?>',event);">
                                    <img src="/Imgs/b_edit.png" />
                                </button>
                            <?
                            }
                            ?>
                            </td>
                        <?
                        }

                    }
                    if($Tipo == "Ejecutar")
                    {
                    ?>
                        <td align="center">
                        <button type="submit" name="Ejecutar[<? echo $fila[1];?>]"
                        title="Ejecutar"><img src="/Imgs/b_check.png" /></button>
                        </td>
                    <?	
                    }

                }
		?>
    </tr>
</table>
<?
	if($Tipo == "Aprobar")
	{
	?>
            <center><input type="submit" name="Guardar" value="Guardar" /></center>
	<?	
	}
?>
</form>
<iframe id="FrameOpener" name="FrameOpener" style="display:none" frameborder="0" height="1" style="border:#e5e5e5 ridge" ></iframe>
</body>