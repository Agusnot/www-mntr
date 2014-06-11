<?
    if($DatNameSID){session_name("$DatNameSID");}
    session_start();
    include("Funciones.php");
    if($Eliminar)
    {
        $cons = "Delete from Infraestructura.Mantenimiento Where COmpania='$Compania[0]' and AutoId=0
        and Descripcion='$NombreNoCodi' and Tercero='$Identificacion' and FechaSolicitud='$FechaSolicitud'";
        $res = ExQuery($cons);
        $Tercero=$Identificacion;
    }
    $ND = getdate();
    $cons = "Select primApe,segApe,primNom,SegNom from Central.Terceros Where Identificacion='$Tercero' and Compania='$Compania[0]'";
    $res = ExQuery($cons);
    $fila = ExFetch($res);
    $Nombre = strtoupper("$fila[0] $fila[1] $fila[2] $fila[3]");
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
    <button name="Cerrar" title="Cerrar" onClick="CerrarThis()"><img src="/Imgs/b_drop.png" /></button>
</div>
<table style='font : normal normal small-caps 12px Tahoma;' border="1" bordercolor="#e5e5e5" width="100%">
    <tr bgcolor="#e5e5e5" align="center" style="font-weight:bold"><td colspan="8">Solicitudes de elementos no codificados para: <? echo $Nombre?></td></tr>
    <tr bgcolor="#e5e5e5" align="center" style="font-weight:bold"><td>Descripci&oacute;n Elemento</td><td>Detalle</td><td>Centro Costo</td><td>Sub Ubicacion</td><td>Fecha</td>
        <td colspan="3">&nbsp;</td></tr>
    <?
        $cons = "Select Descripcion,DetalleSolicitud,CC,CentroCostos,SubUbicacion,FechaSolicitud,EstadoSolicitud from Infraestructura.Mantenimiento,Central.CentrosCosto
        Where Mantenimiento.COmpania='$Compania[0]' and CentrosCosto.Compania='$Compania[0]' and CentrosCosto.Anio=$ND[year]
        and Mantenimiento.CC = CentrosCosto.Codigo and Tercero='$Tercero'";
        $res = ExQuery($cons);
        while($fila = ExFEtch($res))
        {
            echo "<tr><td>$fila[0]</td><td>$fila[1]</td><td>$fila[2]-$fila[3]</td><td>$fila[4]</td><td>".substr($fila[5],0,10)."</td>";
            if($fila[6]=="Solicitado")
            {
            ?>
            <td><img src="/Imgs/b_edit.png" title="Editar" 
                onclick="location.href='NewSolMantenimiento.php?Editar=1&DatNameSID=<? echo $DatNameSID?>&AutoId=NoCodi&NombreNoCodi=<? echo $fila[0]?>&Identificacion=<? echo $Tercero?>&FechaSolicitud=<? echo $fila[5]?>';"
                style="cursor:hand"></td>
            <td><img src="/Imgs/b_drop.png" title="Eliminar" 
                onclick="location.href='MantenimientoNoCodi.php?Eliminar=1&DatNameSID=<? echo $DatNameSID?>&AutoId=NoCodi&NombreNoCodi=<? echo $fila[0]?>&Identificacion=<? echo $Tercero?>&FechaSolicitud=<? echo $fila[5]?>';"
                style="cursor:hand"></td><?    
            }
            if($fila[6]=="Cerrado")
            {
                ?><td><img src="/Imgs/b_newtbl.png" title="Generar nueva solicitud a partir de esta" style="cursor:hand"
                     onclick="location.href='NewSolMantenimiento.php?DatNameSID=<? echo $DatNameSID?>&AutoId=NoCodi&NombreNoCodi=<? echo $fila[0]?>&Identificacion=<? echo $Tercero?>';"></td><?
            }

        }
    ?>
</table>
</form>
</body>