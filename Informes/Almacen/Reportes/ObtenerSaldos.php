<?
	if($DatNameSID){session_name("$DatNameSID");}
	session_start();
	function SaldosIniciales($Anio,$AlmacenPpal,$Fecha)
	{
            global $Compania;

            $cons="Select AutoId,sum(Cantidad),sum(TotCosto)+sum(VrIVA) from Consumo.Movimiento where (Fecha>='$Anio-01-01' and Fecha<='$Anio-12-31')
            and Compania='$Compania[0]' and AlmacenPpal='$AlmacenPpal' and Fecha<'$Fecha'
            and (TipoComprobante='Entradas' Or TipoComprobante='Remisiones' Or TipoComprobante='Ingreso Ajuste') and Estado='AC' Group By AutoId";
            $res=ExQuery($cons);

            while($fila=ExFetch($res))
            {
                $Entradas[$fila[0]]=array($fila[1],$fila[2]);
            }

            $cons="Select AutoId,sum(Cantidad),sum(TotCosto) from Consumo.Movimiento where (Fecha>='$Anio-01-01' and Fecha<='$Anio-12-31')
            and Compania='$Compania[0]' and AlmacenPpal='$AlmacenPpal' and Fecha<'$Fecha'
            and (TipoComprobante='Salidas' Or TipoComprobante='Salida Ajuste') and Estado='AC' Group By AutoId";

            $res=ExQuery($cons);
            while($fila=ExFetch($res))
            {
                $Salidas[$fila[0]]=array($fila[1],$fila[2]);
            }


            $cons="Select AutoId,Cantidad,VrTotal
            from Consumo.SaldosInicialesxAnio where Anio=$Anio and Compania='$Compania[0]'
            and AlmacenPpal='$AlmacenPpal'";
            $res=ExQuery($cons);
            while($fila=ExFetch($res))
            {
                $Saldos[$fila[0]]=array($fila[1],$fila[2]);
            }

            $cons="Select AutoId,sum(Cantidad),sum(TotCosto) from Consumo.Movimiento where (Fecha>='$Anio-01-01' and Fecha<='$Anio-12-31')
            and Compania='$Compania[0]' and AlmacenPpal='$AlmacenPpal' and Fecha<'$Fecha'
            and (TipoComprobante='Devoluciones') and Estado='AC' Group By AutoId";
			
            $res=ExQuery($cons);
            while($fila=ExFetch($res))
            {
                    $Devoluciones[$fila[0]]=array($fila[1],$fila[2]);
            }


            $cons="Select AutoId from Consumo.CodProductos where Compania='$Compania[0]' and Anio=$Anio and AlmacenPpal='$AlmacenPpal'";
            $res=ExQuery($cons);
            while($fila=ExFetch($res))
            {
                $Cantidades=$Saldos[$fila[0]][0]+$Entradas[$fila[0]][0]-$Salidas[$fila[0]][0]+$Devoluciones[$fila[0]][0];
                $Valores=$Saldos[$fila[0]][1]+$Entradas[$fila[0]][1]-$Salidas[$fila[0]][1]+$Devoluciones[$fila[0]][1];
                $SaldosIniciales[$fila[0]]=array($Cantidades,$Valores);
            }
            return $SaldosIniciales;
	}
	function Entradas($Anio,$AlmacenPpal,$FechaIni,$FechaFin)
	{
            global $Compania;
            $cons="Select AutoId,sum(Cantidad),sum(TotCosto)+sum(VrIVA) from Consumo.Movimiento where (Fecha>='$Anio-01-01' and Fecha<='$Anio-12-31')
            and Compania='$Compania[0]' and AlmacenPpal='$AlmacenPpal' and Fecha>='$FechaIni' and Fecha<='$FechaFin'
            and (TipoComprobante='Entradas' Or TipoComprobante='Remisiones' Or TipoComprobante='Ingreso Ajuste') and Estado='AC' Group By AutoId";
            $res=ExQuery($cons);
            while($fila=ExFetch($res))
            {
                $Entradas[$fila[0]]=array($fila[1],$fila[2]);
            }
            return $Entradas;
				
	}
	function Salidas($Anio,$AlmacenPpal,$FechaIni,$FechaFin)
	{
            global $Compania;
            $cons="Select AutoId,sum(Cantidad),sum(TotCosto) from Consumo.Movimiento where (Fecha>='$Anio-01-01' and Fecha<='$Anio-12-31')
            and Compania='$Compania[0]' and AlmacenPpal='$AlmacenPpal' and Fecha>='$FechaIni' and Fecha<='$FechaFin'
            and (TipoComprobante='Salidas' Or TipoComprobante='Salida Ajuste') and Estado='AC' Group By AutoId";
            $res=ExQuery($cons);
            while($fila=ExFetch($res))
            {
                    $Salidas[$fila[0]]=array($fila[1],$fila[2]);
            }
            return $Salidas;
	}
	function Devoluciones($Anio,$AlmacenPpal,$FechaIni,$FechaFin)
        {
            global $Compania;
            $cons="Select AutoId,sum(Cantidad),sum(TotCosto) from Consumo.Movimiento where (Fecha>='$Anio-01-01' and Fecha<='$Anio-12-31')
            and Compania='$Compania[0]' and AlmacenPpal='$AlmacenPpal' and Fecha>='$FechaIni' and Fecha<='$FechaFin'
            and (TipoComprobante='Devoluciones') and Estado='AC' Group By AutoId";
            $res=ExQuery($cons);
            while($fila=ExFetch($res))
            {
                    $Devoluciones[$fila[0]]=array($fila[1],$fila[2]);
            }
            return $Devoluciones;
        }
?>