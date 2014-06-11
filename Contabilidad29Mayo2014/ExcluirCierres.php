<?
	if($DatNameSID){session_name("$DatNameSID");}
	session_start();
	include("Funciones.php");
	if($Tipo=="Excluido")
	{
		$cons="Delete from Contabilidad.ExcluyeComprobantes where Compania='$Compania[0]' and Comprobante='$Comprobante' and Numero='$Numero'";
		$res=ExQuery($cons);echo ExError($res);
	}

	if($Tipo=="Incluido")
	{
		$cons="Insert into Contabilidad.ExcluyeComprobantes (Compania,Comprobante,Numero) values ('$Compania[0]','$Comprobante','$Numero')";
		$res=ExQuery($cons);echo ExError($res);
	}
?>
<body background="/Imgs/Fondo.jpg">
<table cellpadding="4"  border="1" bordercolor="<?echo $Estilo[1]?>" style="font-family:<?echo $Estilo[8]?>;font-size:<?echo $Estilo[9]?>;font-style:<?echo $Estilo[10]?>">
<tr style="color:white;font-weight:bold;text-align:center" bgcolor="<?echo $Estilo[1]?>"><td>Comprobante</td><td>Numero</td><td>Fecha</td><td>Estado</td></tr>
<?
	$cons="Select Comprobante from Contabilidad.Comprobantes where Cierre='1' and Compania='$Compania[0]'";
	$res=ExQuery($cons);
	$fila=ExFetch($res);
	$Comprobante=$fila[0];
	
	$cons="Select Comprobante,Numero,Fecha,date_part('year',Fecha) from Contabilidad.Movimiento 
	where Comprobante='$Comprobante' and Compania='$Compania[0]' and Estado='AC' Group By Comprobante,Numero,Fecha";
	$res=ExQuery($cons);echo ExError($res);
	while($fila=ExFetch($res))
	{
		$cons2="Select Comprobante,Numero from Contabilidad.ExcluyeComprobantes where Compania='$Compania[0]' and Comprobante='$Comprobante' and Numero='$fila[1]'";
		$res2=ExQuery($cons2);echo ExError($res2);
		if(ExNumRows($res2)>0){$Estado="Excluido";$Img="b_insrow.png";$Alt="Incluir Comprobante en Informes";$BG="style='background-color:$Estilo[1];font-weight:bold;color:yellow'";}
		else{$Estado="Incluido";$Img="b_tblexport.png";$Alt="Excluir Comprobante en Informes";$BG="";}
		echo "<tr><td>$fila[0]</td><td>$fila[1]</td><td>$fila[2]</td><td $BG>$Estado</td><td><a href='ExcluirCierres.php?DatNameSID=$DatNameSID&Tipo=$Estado&Comprobante=$Comprobante&Numero=$fila[1]'><img alt='$Alt' border=1 src='/Imgs/$Img'></a></td></tr>";
	}
?>
</table>
</body>