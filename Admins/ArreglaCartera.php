<?
	$conex2 = mysql_connect("10.18.176.103","root", "") or die ('2.no establecida');
	$conex = pg_connect("dbname=sistema user=apache password=Server*1982") or die ('no establecida');
	$cons="Select DocSoporte,Fecha,FechaDocumento from Contabilidad.movimiento where Comprobante='Saldos iniciales'
	and Cuenta like '1305%'";
	$res=pg_query($cons);
	while($fila=pg_fetch_row($res))
	{
		$cons2="Select Fecha,FechaDocumento from Contabilidad.movimiento where Cuenta like '1305%'
		and Debe>0 and DocSoporte='$fila[0]' and Estado='AC'";
		$res2=mysql_query($cons2);
		$fila2=mysql_fetch_row($res2);
		if($fila2[0]!=$fila[1] && $fila2[0])
		{
			$cons3="Update Contabilidad.Movimiento set Fecha='$fila2[0]', FechaDocumento='$fila2[1]'
			where DocSoporte='$fila[0]' and Cuenta like '1305%' and Estado='AC' and Comprobante='Saldos iniciales'";

			$res3=pg_query($cons3);
			echo "$fila[0] --> ".pg_affected_rows($res3)."<br>";
		}
	}
?>