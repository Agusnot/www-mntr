<?
    if($DatNameSID){session_name("$DatNameSID");}
    session_start();
    include("Funciones.php");
    $ND=getdate();
    if(!$Hora){$Hora = $ND[hours];}
    if($Registrar)
    {
        $cons = "Insert into salud.registroMedicamentos (Compania,AlmacenPpal,NumServicio,
        Cedula,Autoid,Usuariocre,FechaCre,Hora,Cantidad,Tipo,NumOrden,IdEscritura) values
        ('$Compania[0]','$AlmacenPpal',0,'$Paciente[1]',$AutoId,'$usuario[1]',
        '$ND[year]-$ND[mon]-$ND[mday] $ND[hours]:$ND[minutes]:$ND[seconds]',$Hora,$Cantidad,'N',-1,-1)";
        $res = ExQuery($cons);
    }
?>
<script language="Javascript">
    function Mostrar()
    {
        parent.document.getElementById('Busquedas').style.position='absolute';
        parent.document.getElementById('Busquedas').style.top='50px';
        parent.document.getElementById('Busquedas').style.right='10px';
        parent.document.getElementById('Busquedas').style.display='';
    }
    function Ocultar()
    {
        parent.document.getElementById('Busquedas').style.display='none';
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
    function Validar()
    {
        if (document.FORMA.CUM.value == "" || document.FORMA.Laboratorio.value==""){return false;}
        
    }
</script>
<script labguage="Javascript" src="/Funciones.js"></script>
<form name="FORMA" method="post">
    <div align="right">
        <button type="button" name="Cerrar" title="Cerrar" onclick="CerrarThis()">
            <img src="/Imgs/b_drop" />
        </button>
    </div>
    <table style='font : normal normal small-caps 12px Tahoma;' border="1" bordercolor="#e5e5e5">
        <tr bgcolor="#e5e5e5">
            <td>Codigo</td><td>Medicamento</td><td>Cantidad</td><td>Hora</td>
        </tr>
        <tr>
        <input type="hidden" name="AutoId" value="<? echo $AutoId?>" readonly />
        <td><input type="text" name="Codigo" readonly="yes" style="width:60px;"/></td>
        <td><input type="text" name="Producto" style="width:405px;"
		onfocus="frames.BuscaProductos.location.href='BuscaProductos.php?DatNameSID=<? echo $DatNameSID?>&TMPCOD=<? echo $TMPCOD?>&Anio=<? echo $Anio?>&NomProducto='+this.value+'&AlmacenPpal=<?echo $AlmacenPpal?>'"
		onKeyUp="xLetra(this);
        Codigo.value='';frames.BuscaProductos.location.href='BuscaProductos.php?DatNameSID=<? echo $DatNameSID?>&TMPCOD=<? echo $TMPCOD?>&Anio=<? echo $Anio?>&NomProducto='+this.value+'&AlmacenPpal=<?echo $AlmacenPpal?>'"
        onKeyDown="xLetra(this)"/></td>
        <td><input type="text" name="Cantidad" style="width:50px;"
        onKeyUp="xNumero(this)" onKeyDown="xNumero(this)" onBlur="campoNumero(this)"/></td>
        <td>
            <select name="Hora">
                <?
                for($i=0;$i<=24;$i++)
                {
                    if($i==$Hora){$sel=" selected ";}else{unset($sel);}
                    echo "<option $sel value='$i'>$i</option>";
                }
                ?>
            </select>
        </td>
        <td>
            <button type="submit" name="Registrar" title="Registrar">
                <img src="/Imgs/b_save.png" />
            </button>
        </td>
        </tr>
        <tr>
            <?
            $cons = "Select CodProductos.Autoid,Codigo1,NombreProd1,UnidadMedida,Presentacion,Cantidad,Hora
            from Consumo.CodProductos,Salud.RegistroMedicamentos
            Where CodProductos.Compania='$Compania[0]' and RegistroMedicamentos.Compania='$Compania[0]'
            and CodProductos.AlmacenPpal='$AlmacenPpal' and RegistroMedicamentos.AlmacenPpal='$AlmacenPpal'
            and Anio = $ND[year] and RegistroMedicamentos.Autoid = CodProductos.Autoid and
            RegistroMedicamentos.UsuarioCre='$usuario[1]'
            and date(RegistroMedicamentos.FechaCre) = '$ND[year]-$ND[mon]-$ND[mday]' and Tipo='N' order by hora";
            $res = ExQuery($cons);
            while($fila=ExFetch($res))
            {
                echo "<tr><td>$fila[1]</td><td>$fila[2] $fila[3] $fila[4]</td>
                <td>$fila[5]</td><td>$fila[6]</td></tr>";
            }
            ?>
        </tr>
    </table>
</form>
<iframe width="100%" id="BuscaProductos" name="BuscaProductos" src="" frameborder="0" style="height:380px;" ></iframe>