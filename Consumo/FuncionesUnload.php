<?
function Borrar_Lotes($Compania,$AlmacenPpal,$Numero,$TMPCOD,$AutoIds)
{
    $cons = "Delete from Consumo.Lotes Where Compania='$Compania' and AlmacenPpal='$AlmacenPpal' and Numero='$Numero' and TMPCOD='$TMPCOD'
    and AutoId not in($AutoIds)";
    $res = ExQuery($cons);
    $cons = "Update COnsumo.Lotes set TMPCOD='' Where Compania='$Compania' and AlmacenPpal='$AlmacenPpal' and Numero='$Numero' and TMPCOD='$TMPCOD'";
    $res = ExQuery($cons);
}
?>