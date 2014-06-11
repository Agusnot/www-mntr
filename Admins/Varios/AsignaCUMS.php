<?
$conex = pg_connect("host=localhost dbname=sistema user=postgres password=Server*1982") or die ('no establecida');

    $cons = "Select Numero,Autoid,Cantidad,CUM from Consumo.Movimiento
    Where TipoComprobante = 'Salidas' and Compania='Clinica San Juan de Dios' and AlmacenPpal='FARMACIA' and Anio=2011
    order by AutoId,Fecha";
    $res = pg_query($cons);
    while($fila=pg_fetch_row($res))
    {
        if($fila[3]){$CUM = $fila[3];}
        else
        {
            $cons2 = "Select CUM,Numero,Cantidad,Vence,Lote from Consumo.Lotes,Consumo.CumsxProducto
            Where Lotes.Compania='Clinica San Juan de Dios' and Lotes.AlmacenPpal='FARMACIA'
            and Lotes.Autoid=$fila[1] and CumsxProducto.Compania='Clinica San Juan de Dios'
            and (Lotes.Cantidad-Salidas)>=$fila[2]
            and CumsxProducto.AlmacenPpal = 'FARMACIA' and CumsxProducto.Autoid=$fila[1]
            and Lotes.RegInvima = CumsxProducto.RegInvima order by Lotes.Autoid,Vence asc LIMIT 1";
            ?><font color="blue"><?echo $cons2;?></font><br><br><?
            $res2 = pg_query($cons2);
            $fila2 = pg_fetch_row($res2);
            if($fila2[0])
            {
                $CUM = $fila2[0];
                $cons3 = "Update Consumo.Lotes set Salidas = Salidas + $fila[2]
                Where Compania='Clinica San Juan de Dios' and AlmacenPpal='FARMACIA'
                and Numero='$fila2[1]' and Autoid=$fila[1] and Vence='$fila2[3]' and Lote='$fila2[4]'";
                $res3 = pg_query($cons3);
                ?><font color="blue">*&nbsp;&nbsp;&nbsp;&nbsp;<?echo $cons3;?></font><br><br><?
                $cons3 = "Update Consumo.Movimiento set CUM = '$CUM'
                Where Compania='Clinica San Juan de Dios' and AlmacenPpal='FARMACIA'
                and Numero='$fila[0]' and Autoid=$fila[1]";
                $res3 = pg_query($cons3);
                ?><font color="blue">*&nbsp;&nbsp;&nbsp;&nbsp;<?echo $cons3;?></font><br><br><?
            }
            else
            {
                $cons3 = "Select CUM from Consumo.CumsxProducto
                Where Compania='Clinica San Juan de Dios' and AlmacenPpal='FARMACIA'
                and Autoid=$fila[1] LIMIT 1";
                ?><font color="red">*&nbsp;&nbsp;&nbsp;&nbsp;<?echo $cons3;?></font><br><br><?
                $res3 = pg_query($cons3);
                $fila3 = pg_fetch_row($res3);
                $CUM = $fila3[0];
                $cons3 = "Update Consumo.Movimiento set CUM = '$CUM'
                Where Compania='Clinica San Juan de Dios' and AlmacenPpal='FARMACIA'
                and Numero='$fila[0]' and Autoid=$fila[1]";
                $res3 = pg_query($cons3);
                ?><font color="red">*&nbsp;&nbsp;&nbsp;&nbsp;<?echo $cons3;?></font><br><br><?
            }
        }
        
    }
    