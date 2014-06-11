<?
$conex = pg_connect("host=localhost dbname=sistema user=postgres password=Server*1982") or die ('no establecida');
$cons = "Select COdigo,NumServicio,CodProductos.Autoid,Liquidacion.NoFactura,DetalleLiquidacion.NoLiquidacion
from Facturacion.DetalleLiquidacion,Facturacion.Liquidacion,Consumo.CodProductos,Facturacion.FacturasCredito
Where Tipo = 'Medicamentos' and Liquidacion.nofactura is not null and
DetalleLiquidacion.Noliquidacion = Liquidacion.NoLiquidacion
and CodProductos.Codigo1 = Detalleliquidacion.Codigo
and CodProductos.AlmacenPpal = 'FARMACIA' and CodProductos.Grupo = 'Medicamentos'
and FacturasCredito.NoFactura = Liquidacion.NoFactura
and date_part('month',FacturasCredito.FechaIni)=9";
$res = pg_query($cons);
while($fila = pg_fetch_row($res))
{
  echo "$fila[0]---$fila[1]---$fila[2]---$fila[3]<br>";
  $cons2 = "Select CUM from Consumo.Movimiento Where ALmacenPpal = 'FARMACIA'
  and NumServicio = $fila[1] and Autoid = $fila[2] LIMIT 1";
  $res2 = pg_query($cons2);
  $fila2 = pg_fetch_row($res2);
  echo "<b>$fila2[0]</b><br>";
  $cons3 = "Update Facturacion.DetalleLiquidacion set Codigo = '$fila2[0]'
  Where Codigo = '$fila[0]' and NoLiquidacion = '$fila[4]'";
  echo "$cons3<br>";
  $res3 = pg_query($cons3);
  $cons3 = "Update Facturacion.DetalleFactura set Codigo = '$fila2[0]'
  Where Codigo = '$fila[0]' and NoFactura = '$fila[3]'";
  echo "$cons3<br>";
  $res3 = pg_query($cons3);
}
?>