<?
include("Funciones.php");
$cons = "Select Cedula 
from COnsumo.movimiento 
Where Fecha>='2011-11-01' 
and centrocosto='000' and ALmacenPpal = 'FARMACIA' and TipoComprobante='Salidas' order by cedula";

$res = ExQuery($cons);
while($fila=ExFetch($res))
{
  if(!$Upt[$fila[0]])
  {
    echo $fila[0];
    $cons2 = "Select PacientesxPabellones.Pabellon,CentroCOsto 
    from salud.PacientesxPabellones,Salud.Pabellones 
    Where Cedula = '$fila[0]' and PacientesxPabellones.pabellon != 'Remision y Evasion'
    and Pabellones.Pabellon = pacientesxPabellones.Pabellon
    order by fechai desc LIMIT 1";
    $res2 = ExQuery($cons2);
    $fila2 = ExFetch($res2);
    echo " - $fila2[0] - $fila2[1]<br>";
    $cons3 = "Update consumo.Movimiento set centrocosto = '$fila2[1]'
    Where Fecha>='2011-10-01' 
    and centrocosto='000' and ALmacenPpal = 'FARMACIA' and TipoComprobante='Salidas'
    and Cedula = '$fila[0]'";
    $res3 = exQuery($cons3);
    $Upt[$fila[0]]=1;
  }
  
}

?>