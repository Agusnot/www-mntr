<?
	function LT_CodElementos($Compania,$TMPCOD,$Numero,$Tipo)
	{
		if($Tipo == "Orden Compra")
		{
			$cons = "Delete from Infraestructura.CodElementos Where Compania='$Compania' and TMPCOD = '$TMPCOD' and Numero = '$Numero'";
			$res = ExQuery($cons);	
		}	
	}
?>