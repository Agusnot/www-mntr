<?
	session_start();
	$ND=getdate();
	$conex2 = mysql_connect("localhost", "root", 'Server*1492');

	$cons="Select AutoId,Fecha,Comprobante,Numero,Identificacion,Detalle,Cuenta,Debe,Haber,CC,DocSoporte,BaseGravable,Compania,UsuarioCre,FechaCre,Modificadox,FechaMod,Cerrado,FormaPago,NoCheque,
	Banco,DiasVencimiento,DetConcepto,Estado,MesConciliado,AnioConciliado,FechaDocumento,FechaCierre  
	from Contabilidad.Movimiento2010 where AutoId=0 Order By Fecha,Comprobante,Numero,AutoId";
	$res=mysql_query($cons);

$AutoId=175726;
	while($fila=mysql_fetch_row($res))
	{
		$AutoId++;
		$cons2="Update Contabilidad.Movimiento2010 set AutoId=$AutoId where Comprobante='$fila[2]' and Numero='$fila[3]' and DocSoporte='$fila[10]' and Identificacion='$fila[4]' and Fecha='$fila[1]' and Debe=$fila[7] and Haber=$fila[8]";
		$res2=mysql_query($cons2);echo mysql_error();
	}

?>