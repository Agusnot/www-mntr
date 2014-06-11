<?
	if($DatNameSID){session_name("$DatNameSID");}
	else{$Compania[0]='Clinica San Juan de Dios';}
	session_start();
	include("../../../Funciones.php");
	$ND=getdate();
?>	
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Salidas X Centro y Grupo</title>
<style type="text/css">
<!--
body,td,th {
	font-family: Verdana, Arial, Helvetica, sans-serif;
	font-size: 10px;
	color: #333333;
}
body {
	background-image: url(../../../Imgs/Fondo.jpg);
}
select{
	background-color:#FFFFFF;
	border-color:#EEEEEE;
	border-style:solid;
	border-width:thin;
	color:#333333;
	font-family:Verdana, Arial, Helvetica, sans-serif;
	font-size:10px;
}
-->
</style>
<script type="text/JavaScript">
<!--
function Valida()
{
//document.FORMA.FechaIni.value=''+document.FORMA.fi1.value+'-'+document.FORMA.fi2.value+'-'+document.FORMA.fi3.value+'';
//document.FORMA.FechaFin.value=''+document.FORMA.ff1.value+'-'+document.FORMA.ff2.value+'-'+document.FORMA.ff3.value+'';
//alert('Fecha1: '+document.FORMA.FechaIni.value+' Fecha2: '+document.FORMA.FechaFin.value+'');
if(document.FORMA.FechaIni.value=="")
	{
		alert("Debes seleccionar la fecha inicial.");
	}
else{
		if(document.FORMA.FechaFin.value=="")
			{
				alert("Debes seleccionar la fecha final.");
			}
		else{
				if(document.FORMA.FechaIni.value>document.FORMA.FechaFin.value)
					{
						alert("La fecha inicial debe ser menor a la fecha final.");
					}
				else{
						location.href='?DatNameSID=<? echo $DatNameSID?>&FechaIni='+document.FORMA.FechaIni.value+'&FechaFin='+document.FORMA.FechaFin.value+'&almacen='+document.FORMA.almacen.value+'&costos='+document.FORMA.costos.value+'&grupo='+document.FORMA.grupo.value+'';
					}
			}
	}
}
//-->
</script>

</head>

<body>
<?php
		$FechaIni="".$_GET['Anio']."-".$_GET['MesIni']."-".$_GET['DiaIni']."";
		//echo $FechaIni;
		$FechaFin="".$_GET['Anio']."-".$_GET['MesFin']."-".$_GET['DiaFin']."";
		//echo $FechaFin;	  
		$AlmacenPpal=$_GET['AlmacenPpal']; 	
?>

<table border="0">
<?php 
$cons="select centrocostos,codigo from central.centroscosto where anio='".$_GET['Anio']."'order by centrocostos";
$res=ExQuery($cons);
$total=0;
while($fila=ExFetch($res)){
?>
  <tr>
    <td nowrap="nowrap" bgcolor="#CCCCCC"><div align="left"><strong>CENTRO DE COSTOS: </strong></div></td>
    <td width="10" nowrap="nowrap" bgcolor="#CCCCCC"><div align="left"></div></td>
    <td nowrap="nowrap" bgcolor="#CCCCCC"><div align="left"><strong><?php echo"$fila[0] - $fila[1]"; ?></strong></div></td>
  </tr>
<?php 
	$cons2="select grupo,sum(totcosto) from consumo.movimiento where Fecha>='$FechaIni' and Fecha<='$FechaFin' and Tipocomprobante like '%Salida%' and almacenppal='$AlmacenPpal' and 	centrocosto='$fila[1]' group by grupo order by grupo";
	//echo $cons2;
	$res2=ExQuery($cons2);
	$subtotal=0;
	while($fila2=ExFetch($res2)){ ?>
	<tr>
    <td nowrap="nowrap"><div align="justify"><?php echo"$fila2[0]"; ?></div></td>
    <td nowrap="nowrap"><div align="right"></div></td>
    <td nowrap="nowrap"><div align="right"><?php echo'$'."".number_format($fila2[1],2).""; ?></div></td>
  </tr>
	<?php 
	$subtotal=$subtotal+$fila2[1];
	$total=$total+$subtotal;
	} ?>
	  <tr>
    <td nowrap="nowrap"><div align="right"><strong>SUBTOTAL:</strong></div></td>
    <td nowrap="nowrap"><div align="right"></div></td>
    <td nowrap="nowrap"><div align="right"><strong><?php echo'$'."".number_format($subtotal,2).""; ?></strong></div></td>
  </tr>
	  <tr>
	    <td height="10" nowrap="nowrap">&nbsp;</td>
	    <td height="10" nowrap="nowrap">&nbsp;</td>
	    <td height="10" nowrap="nowrap">&nbsp;</td>
<? } ?>
  </tr>
	  <tr>
	    <td height="10" nowrap="nowrap"><div align="right"><strong>TOTAL SALIDAS: </strong></div></td>
	    <td height="10" nowrap="nowrap"><div align="right"></div></td>
	    <td height="10" nowrap="nowrap"><div align="right"><strong><?php echo'$'."".number_format($total,2).""; ?></strong></div></td>
  </tr>
</table>
</body>
</html>