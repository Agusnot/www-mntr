<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Informe de Retenci&oacute;n Detallado</title>
<style type="text/css">
<!--
body,td,th {
	font-family: Verdana, Arial, Helvetica, sans-serif;
	font-size: 10px;
	color: #333333;
}
-->
</style></head>
<body>
		<table border="0">	
		  
  <tr>
    <td nowrap="nowrap" bgcolor="#999999"><div align="center">FECHA</div></td>
    <td width="10" nowrap="nowrap" bgcolor="#999999"><div align="center"></div></td>
    <td nowrap="nowrap" bgcolor="#999999"><div align="center">NIT</div></td>
    <td width="10" nowrap="nowrap" bgcolor="#999999"><div align="center"></div></td>
    <td nowrap="nowrap" bgcolor="#999999"><div align="center">NOMBRE TERCERO</div></td>
    <td nowrap="nowrap" bgcolor="#999999"><div align="center"></div></td>
    <td nowrap="nowrap" bgcolor="#999999"><div align="center">COMPROBANTE</div></td>
    <td width="10" nowrap="nowrap" bgcolor="#999999"><div align="center"></div></td>
    <td nowrap="nowrap" bgcolor="#999999"><div align="center">% RETENCI&Oacute;N </div></td>
    <td width="10" nowrap="nowrap" bgcolor="#999999"><div align="center"></div></td>
    <td nowrap="nowrap" bgcolor="#999999"><div align="center">CONCEPTO</div></td>
    <td width="10" nowrap="nowrap" bgcolor="#999999"><div align="center"></div></td>
    <td nowrap="nowrap" bgcolor="#999999"><div align="center">BASE</div></td>
    <td width="10" nowrap="nowrap" bgcolor="#999999"><div align="center"></div></td>
    <td nowrap="nowrap" bgcolor="#999999"><div align="center">VALOR RETENIDO </div></td>
  </tr>
  <?php
  $suma=0;
if($DatNameSID){session_name("$DatNameSID");}
session_start();
include("../../../Funciones.php");
$Tercero=$_GET['Tercero'];
$qt='="$Tercero"';
if($Tercero==NULL){$qt='is not null';}
$FechaIni="".$_GET['Anio']."-".$_GET['MesIni']."-".$_GET['DiaIni']."";
$FechaFin="".$_GET['Anio']."-".$_GET['MesFin']."-".$_GET['DiaFin']."";
//echo"Tercero: $Tercero, Fecha 1: $FechaIni, Fecha 2: $FechaFin";
$cons="SELECT fecha,comprobante,numero,contabilidad.movimiento.identificacion,central.terceros.primape,conceptorte,porcretenido,basegravable,haber
FROM contabilidad.movimiento
INNER JOIN central.terceros 
ON contabilidad.movimiento.identificacion=central.terceros.identificacion
WHERE basegravable !='0' AND central.terceros.identificacion $qt
AND fecha BETWEEN '$FechaIni' and '$FechaFin'
ORDER BY fecha, contabilidad.movimiento.identificacion";
//echo"$cons";
$res=ExQuery($cons);
while($fila=ExFetch($res)){ ?>
  <tr>
    <td nowrap="nowrap"><div align="center"><?php echo"$fila[0]"; ?></div></td>
    <td nowrap="nowrap"><div align="center"></div></td>
    <td nowrap="nowrap"><div align="center"><?php echo"$fila[3]"; ?></div></td>
    <td width="10" nowrap="nowrap"><div align="center"></div></td>
    <td nowrap="nowrap"><div align="center"><?php echo"$fila[4]"; ?></div></td>
    <td width="10" nowrap="nowrap"><div align="center"></div></td>
    <td nowrap="nowrap"><div align="center"><?php echo"$fila[1]"; ?>(<?php echo"$fila[2]"; ?>)
        </div>
    </div></td>
    <td width="10" nowrap="nowrap"><div align="center"></div></td>
    <td nowrap="nowrap"><div align="center"><?php echo"$fila[6]"; ?></div></td>
    <td width="10" nowrap="nowrap"><div align="center"></div></td>
    <td nowrap="nowrap"><div align="center"><?php echo"$fila[5]"; ?></div></td>
    <td width="10" nowrap="nowrap"><div align="center"></div></td>
    <td nowrap="nowrap"><div align="center"><?php echo number_format($fila[7],2); ?></div></td>
    <td width="10" nowrap="nowrap"><div align="center"></div></td>
    <td nowrap="nowrap"><div align="right">
      <?php $suma=$suma+$fila[8];echo number_format($fila[8],2); ?>
    </div></td>
  </tr>
    <tr>
      <td height="10" nowrap="nowrap"><div align="justify"></div></td>
      <td height="10" nowrap="nowrap"><div align="justify"></div></td>
      <td height="10" nowrap="nowrap"><div align="justify"></div></td>
      <td height="10" nowrap="nowrap"><div align="justify"></div></td>
      <td height="10" nowrap="nowrap"><div align="justify"></div></td>
      <td height="10" nowrap="nowrap"><div align="justify"></div></td>
      <td height="10" nowrap="nowrap"><div align="justify"></div></td>
      <td height="10" nowrap="nowrap"><div align="justify"></div></td>
      <td height="10" nowrap="nowrap"><div align="justify"></div></td>
      <td height="10" nowrap="nowrap"><div align="justify"></div></td>
      <td height="10" nowrap="nowrap"><div align="justify"></div></td>
      <td height="10" nowrap="nowrap"><div align="justify"></div></td>
      <td height="10" nowrap="nowrap"><div align="justify"></div></td>
      <td height="10" nowrap="nowrap"><div align="justify"></div></td>
      <td height="10" nowrap="nowrap"><div align="right"></div></td>
    </tr>
  	<?php }
?>
    <tr>
    <td nowrap="nowrap"><div align="justify"></div></td>
    <td nowrap="nowrap"><div align="justify"></div></td>
    <td nowrap="nowrap"><div align="justify"></div></td>
    <td width="10" nowrap="nowrap"><div align="justify"></div></td>
    <td nowrap="nowrap"><div align="justify"></div></td>
    <td nowrap="nowrap"><div align="justify"></div></td>
    <td nowrap="nowrap"><div align="justify"></div></td>
    <td width="10" nowrap="nowrap"><div align="justify"></div></td>
    <td nowrap="nowrap"><div align="justify"></div></td>
    <td width="10" nowrap="nowrap"><div align="justify"></div></td>
    <td nowrap="nowrap"><div align="justify"></div></td>
    <td width="10" nowrap="nowrap"><div align="justify"></div></td>
    <td nowrap="nowrap"><div align="right">TOTAL:</div></td>
    <td width="10" nowrap="nowrap"><div align="justify"></div></td>
    <td nowrap="nowrap"><div align="right"><?php echo number_format($suma,2); ?></div></td>
  </tr>
</table>

</body>
</html>
