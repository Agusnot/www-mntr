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
        parent.document.getElementById('FrameOpener').style.width='1';
        parent.document.getElementById('FrameOpener').style.height='1';
        parent.document.getElementById('FrameOpener').style.display='none';
    }
</script>
<body background="/Imgs/Fondo.jpg">
<form name="FORMA" method="post">
    <div align="right">
        <button type="button" name="Cerrar" title="Cerrar" onClick="CerrarThis()"><img src="/Imgs/b_drop.png"></button>
    </div>
    <table style='font : normal normal small-caps 12px Tahoma;' border="1" bordercolor="#e5e5e5" width="100%">
        <tr bgcolor="#e5e5e5" style=" font-weight:bold" align="center">
            <td>Codigo</td><td>Elemento</td><td>Detalle Solicitud</td><td>FechaSolicitud</td><td>Estado Solicitud</td></tr>
    <?
        if(!$TercerosAdm)
        {
            $adFrom = " ,Central.Usuarios ";
            $adWhere = " and Usuarios.Nombre = Mantenimiento.UsuarioSolicitud and Usuarios.Cedula='$Identificacion'";
        }
        else
        {
            $TercerosAdm="'".str_replace(",","','",$TercerosAdm)."'";
            $adFrom = " ,Central.Usuarios ";
            $adWhere = " and Usuarios.Nombre = Mantenimiento.UsuarioSolicitud and Usuarios.Cedula in($TercerosAdm)";
        }

        $cons = "Select UsuarioSolicitud,Mantenimiento.AutoId,FechaSolicitud,Descripcion,CC,Tercero,SubUbicacion,EstadoSolicitud,
        UsuarioAR,FechaAR,DetalleSolicitud,Codigo,CodElementos.Nombre,Caracteristicas,Marca,Modelo
        from Infraestructura.Mantenimiento,Infraestructura.CodElementos$adFrom
        Where Mantenimiento.Compania='$Compania[0]' and CodElementos.Compania='$Compania[0]'
        and CodElementos.AutoId = Mantenimiento.AutoId $adWhere order by Mantenimiento.AutoId,Descripcion,CodElementos.Nombre";
        $res = ExQuery($cons);
        while($fila = ExFetch($res))
        {
            if($fila[1]=="0")
            {
                $Mantenimiento[$fila[0]]["$fila[3]|$fila[4]|$fila[5]|$fila[6]"][$fila[2]]=array($fila[10],$fila[2],$fila[7],$fila[8],$fila[9]);
            }
            else
            {
                $Mantenimiento[$fila[0]][$fila[1]][$fila[2]]=array($fila[10],$fila[2],$fila[7],$fila[8],$fila[9],$fila[11],$fila[12],$fila[13],$fila[14]);
            }
        }
        if($Mantenimiento)
        {
            while(list($cad,$val)= each($Mantenimiento))
            {
                //$cad: Usuario     $val:array
                ?>
                <tr><td colspan="6" bgcolor="<? echo $Estilo[1]?>" style=" color: #FFFFFF; font-weight: bold"><? echo "Usuario: $cad";?></td></tr>
                <?
                while(list ($cad1,$val1)=each($val))
                {
                    //$cad1: AutoId     $val1:array
                    while(list($cad2,$val2)=each($val1))
                    {
                        //$cad2: FechaSolicitud    $val2:Array
                        ?><tr onMouseOver="this.bgColor='#AAD4FF'" onMouseOut="this.bgColor=''" style=" cursor: hand"
                            onClick="open('/Informes/Infraestructura/Formatos/SolicitudMantenimiento.php?DatNameSID=<? echo $DatNameSID?>&AutoId=<? echo $cad1?>&FechaSolicitud=<? echo $cad2?>','','width=800,height=600,scrollbars=yes');"><?
                        if($val2[6]){echo "<td>$val2[5]</td><td>$val2[6] $val2[7] $val2[8] $val2[9]</td>";}
                        else
                        {
                            echo "<td colspan='2'>".str_replace("|"," ",$cad1)."</td>";
                            
                        }
                        echo "<td>$val2[0]</td><td>$cad2</td>";
                        if($val2[2]=="Aprobado" || $val2[2]=="Rechazado"){$titAR=" title ='$val2[2] por: $val2[3] - $val2[4]' ";}
                        else{$titAR="";}
                        echo "<td $titAR>$val2[2]</td>";

                    }
                }
            }   
        }

    ?>
    </table>
</form>
</body>