<?
include("Funciones.php");
$cons = "Select NoDocAfectado,DocAfectado,Comprobante,Numero,AlmacenPpal 
    from Consumo.Movimiento Where TipoComprobante='Devoluciones'
and fecha>='2012-01-01'";
$res = ExQuery($cons);
while($fila=ExFEtch($res))
{
    echo "$fila[0]&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;$fila[1]&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
    $fila[2]&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;$fila[3]&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;$fila[4]<br>";
    
    $cons2 = "Select CentroCosto,NumServicio from Consumo.Movimiento
    Where ALmacenPpal = '$fila[4]' and Numero = '$fila[0]' 
    and Comprobante = '$fila[1]'";
    $res2 = ExQuery($cons2);
    $fila2 = ExFetch($res2);
    if(!$fila2[1]){$fila2[1]="NULL";}
    echo "$fila2[0]&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;$fila2[1]<br>";
    $cons3 = "Update Consumo.Movimiento set CentroCosto='$fila2[0]',NumServicio=$fila2[1]
    Where Comprobante = 'Devoluciones' and ALmacenPpal = '$fila[4]' and Numero='$fila[3]'
    and DocAfectado='$fila[1]' and NoDocAfectado='$fila[0]'";
    echo "$cons3<br>";
    $res3 = ExQUery($cons3);
}
?>