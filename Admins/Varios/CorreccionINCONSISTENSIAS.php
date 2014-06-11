<?
include("Funciones.php");
$cons = "Select Laboratorio,Autoid from COnsumo.Lotes
Where Numero = '2011'";
$res = ExQuery($cons);
while($fila = ExFetch($res))
{
  echo "$fila[0]----$fila[1]<br>";
  $consa = "Select RegInvima,Presentacion
  from consumo.cumsxproducto Where Autoid=$fila[1]
  and Laboratorio = '$fila[0]' LIMIT 1";
  $resa = ExQuery($consa);
  if(ExNumRows($resa)>0)
  {
    while($filaa = ExFetch($resa))
    {
      echo "*********$filaa[0]----$filaa[1]<br>";
      $consb = "Update Consumo.Lotes set RegINVIMA = '$filaa[0]', Presentacion = '$filaa[1]'
      Where Autoid = $fila[1] and Numero = '2011' and Laboratorio='$fila[0]'";
      echo $consb."<br>";
      $resb = ExQuery($consb);
    }
  }
  else
  {
    $conc = "Select Laboratorio,RegInvima,Presentacion
    from Consumo.CumsxProducto Where Autoid = $fila[1] LIMIT 1";
    $resc = ExQuery($conc);
    $filac = ExFetch($resc);
    echo "=======$filac[0]----$filac[1]----$filac[2]<br>";
    $consd = "Update Consumo.Lotes set Laboratorio = '$filac[0]',RegINVIMA = '$filac[1]', Presentacion = '$filac[2]'
    Where Autoid = $fila[1] and Numero = '2011'";
    $resd = ExQuery($consd);
  }
}
?>