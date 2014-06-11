<?
if($DatNameSID){session_name("$DatNameSID");}
session_start();
include("Funciones.php");
$ND = getdate();
$cons = "select FechaSolicitud,Descripcion,CC,centroscosto.* as Nombre,
 SubUbicacion,PrimApe || ' ' || SegApe || ' ' || PrimNom || ' '  || SegNom  as Solicitante
 from Infraestructura.Mantenimiento,Central.Terceros,Central.CentrosCosto
 where 
 Mantenimiento.Tercero=Terceros.Identificacion and
 Mantenimiento.CC=CentrosCosto.Codigo and
 Mantenimiento.Compania=Terceros.Compania and
 Mantenimiento.Compania=CentrosCosto.Compania
 and Mantenimiento.Compania='Hospital San Rafael de Pasto'
 and CentrosCosto.Compania='Hospital San Rafael de Pasto'
 and FechaSolicitud >= '$Anio-$MesIni-$DiaIni 00:00:00'
 and FechaSolicitud <= '$Anio-$MesFin-$DiaFin 23:59:59'
 and Anio=2011
 and CC is not NULL and CC!=''
 Order By FechaSolicitud";
?>
<table style='font : normal normal small-caps 12px Tahoma;' border="0" bordercolor="#e5e5e5" align="center" width="100%">
    <tr>
        <td align="center" style="font-weight: bold">
            <font size="3"><?echo "$Compania[0]<br>";?></font>
            <?echo "$Compania[1]<br>";?>
            INFORME DE MANTENIMIENTOS REALIZADOS</br>
            <?echo "Desde $Anio-$MesIni-$DiaIni Hasta $Anio-$MesFin-$DiaFin";?>
        </td>
    </tr>
</table><br>
<table style='font : normal normal small-caps 12px Tahoma;' border="1" bordercolor="#e5e5e5" align="center" width="100%">
    <tr bgcolor="#e5e5e5" style="font-weight: bold">
        <td>Fecha Solicitud</td>
        <td>Detalle Solicitud</td>
        <td>Centro de Costos</td>
        <td>Ubicacion Especifica</td>
        <td>Solicitante</td>
    </tr>
<?
$res = ExQuery($cons);
while($fila=ExFetch($res))
{
    ?><tr>
        <td><?echo $fila[0]?></td>
        <td><?echo $fila[1]?></td>
        <td><?echo "$fila[3] - $fila[4]"?></td>
        <td><?echo $fila[8]?></td>
        <td><?echo $fila[9]?></td>
    </tr><?
}
?>
</table>
