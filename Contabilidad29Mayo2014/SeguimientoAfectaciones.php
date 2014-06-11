<?
	if($DatNameSID){session_name("$DatNameSID");}	if($DatNameSID){session_name("$DatNameSID");}
	session_start();
	include("Funciones.php");
?>
<title>Compuconta Software</title>
<body background="/Imgs/Fondo.jpg">
<center>
<table border="1" bordercolor="#666699" style="font-family:<?echo $Estilo[8]?>;font-size:13;font-style:<?echo $Estilo[10]?>">
<tr style="font-weight:bold"><td colspan="2" align="center">Afectaciones de <? echo $Comprobante ?>- <? echo $Numero?></td></tr>
<tr bgcolor="#666699" style="color:white;font-weight:bold" align="center"><td>Presupuesto</td><td>Contabilidad</td></tr>
<tr>
<td>
<?
	$NoEditar=0;
	$cons="Select Comprobante,Numero,date_part('month',Fecha),date_part('year',Fecha) from Presupuesto.Movimiento where DocOrigen='$Comprobante' 
	and NoDocOrigen='$Numero' and Compania='$Compania[0]' and Estado='AC' Group By Comprobante,Numero,Fecha";
	$res=ExQuery($cons);
	while($fila=ExFetch($res))
	{
		echo "<li>$fila[0] $fila[1]";
	}

	$cons="Select date_part('month',Fecha),date_part('year',Fecha) from Contabilidad.Movimiento where Comprobante='$Comprobante' and Numero='$Numero' 
	and Compania='$Compania[0]'";
	$res=ExQuery($cons);echo ExError($res);
	$fila=ExFetch($res);

	$cons2="Select * from Central.CierrexPeriodos where Compania='$Compania[0]' and Mes=$fila[0] and Anio=$fila[1] and Modulo='Contabilidad'";
	$res2=ExQuery($cons2);
	if(ExNumRows($res2)>=1){$NoEditar=2;}
?>
</td>

<td>
<?

	$cons1="Select Comprobante from Contabilidad.CruzarComprobantes where CruzarCon='$Comprobante'";
	$res1=ExQuery($cons1);
	$fila1=ExFetch($res1);
	$ComsAfectados=$fila1[0];

	$cons="Select Comprobante,Numero from Contabilidad.Movimiento where DocSoporte='$Numero' and Comprobante='$ComsAfectados' 
	and Compania='$Compania[0]' and Estado='AC' Group By Comprobante,Numero";
	$res=ExQuery($cons);
	while($fila=ExFetch($res))
	{
		echo "<li>$fila[0] $fila[1]";
	}	
echo "</td>";
	$cons2="Select * from Contabilidad.Movimiento where Comprobante='$Comprobante' and Numero='$Numero' and Estado='AC'";
	$res2=ExQuery($cons2);
	if(ExNumRows($res2)==0){$NoEditar=2;echo "<tr><td colspan=2 align='center'><strong>DOCUMENTO ANULADO</td></tr>";}
echo "</table>";
?>
<br>
<?	if($NoEditar==0){?>
<button value="Editar" onClick="opener.parent.location.href='NuevoMovimiento.php?DatNameSID=<? echo $DatNameSID?>&Edit=1&Comprobante=<? echo $Comprobante?>&Numero=<? echo $Numero?>&Tipo=<? echo $Tipo?>';window.close();"><img src="/Imgs/b_edit.png"> Editar Registro</button>
<?}?>
