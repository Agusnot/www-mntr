<?
    $conex = pg_connect("host=10.18.176.100 dbname=sistema user=postgres password=Server*1982") or die ('no establecida');
    $cons = "Select Numero,NoDocAfectado,DocAfectado from consumo.Movimiento Where TipoComprobante = 'Devoluciones'";
    $res = pg_query($cons);
    while($fila = pg_fetch_row($res))
    {
        $cons2 = "Select CentroCosto from Consumo.Movimiento Where Numero='$fila[1]' and Comprobante = '$fila[2]'";
        $res2 = pg_query($cons2);
        while($fila2=pg_fetch_row($res2))
        {
            $cons3 = "Update Consumo.Movimiento set CentroCosto = '$fila2[0]' Where Numero = '$fila[0]' and TipoComprobante='Devoluciones'";
            $res3 = pg_query($cons3);
            echo $cons3."<br>";
        }
    }
?>