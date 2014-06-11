<?
if($DatNameSID){session_name("$DatNameSID");}
session_start();
include("Funciones.php");

if($Eliminar==1)
{
	$cons="delete from nomina.centrocostos where compania='$Compania[0]' and Identificacion='$Identificacion' and cc='$CC' and porcentaje='$Porcentaje' and anio='$Anio' and mesi='$MesI' and mesf='$MesF' and numcontrato='$NumContrato'";
//	echo $cons;
	$res=ExQuery($cons);
	?>
    <script language="javascript">location.href="DistriCC.php?DatNameSID=<? echo $DatNameSID?>&Identificacion=<? echo $Identificacion?>&CC=<? echo $fila[0]?>&Porcentaje=<? echo $fila[1] ?>&FecIni=<? echo $FecIni?>&Eliminar=1&Anio=<? echo $Anio?>&MesI=<? echo $MesI?>&MesF=<? echo $MesF?>&NumContrato=<? echo $NumContrato?>&Eliminar=0";
    </script>
    <?
}
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<script language='javascript' src="/Funciones.js"></script>
<script language="javascript">
function Validar()
{
   if(document.FORMA.Anio.value==""){alert("Por favor ingrese el Año Inicial !!!");return false;}
   if(document.FORMA.MesI.value==""){alert("Por favor ingrese el Mes Inicial !!!");return false;}
   if(document.FORMA.MesF.value==""){alert("Por favor ingrese el Mes Final !!!");return false;}
}
</script>
</head>
<body>
<table border="1" bordercolor="#e5e5e5" style='font : normal normal small-caps 12px Tahoma;' align="center">
<?
$cons="select cc,porcentaje from nomina.centrocostos where compania='$Compania[0]' and identificacion='$Identificacion' and numcontrato='$NumContrato' and anio='$Anio' and mesi='$MesI' and mesf='$MesF' order by anio, mesi ";
$res=ExQuery($cons);
$consmesI="select mes from central.meses where numero='$MesI'";
$resMesI=ExQuery($consmesI);
$filaI=ExFetch($resMesI);
$consmesF="select mes from central.meses where numero='$MesF'";
$resMesF=ExQuery($consmesF);
$filaF=ExFetch($resMesF);
?>
<tr bgcolor="#666699"style="color:white" align="center"><td colspan="4">DISTRIBUCION CENTRO DE COSTOS PARA EL PERIODO <? echo strtoupper($filaI[0]." - ".$filaF[0]." del ".$Anio)?></td></tr>
<tr bgcolor="#666699"style="color:white" align="center"><td>Centro de Costos</td><td>Porcentaje</td><td colspan="2">&nbsp;</td></tr>
<?
while($fila=ExFetch($res))
{
	?>
	<tr align="center">
    <td><? echo $fila[0]?></td><td><? echo $fila[1]?></td>
    <td width="16px"><a href="#" onClick="location.href='EditCC.php?DatNameSID=<? echo $DatNameSID?>&Identificacion=<? echo $Identificacion?>&CC=<? echo $fila[0]?>&Porcentaje=<? echo $fila[1] ?>&FecIni=<? echo $FecIni?>&Editar=1&Anio=<? echo $Anio?>&MesI=<? echo $MesI?>&MesF=<? echo $MesF?>&NumContrato=<? echo $NumContrato?>'"/><img src="/Imgs/b_usredit.png" border="0" title="Editar"/></a></td>
    
<?
}
?>
</table>
<center><input type="button" name="Volver" value="Volver" onClick="location.href='CentroCostos.php?DatNameSID=<? echo $DatNameSID?>&Identificacion=<? echo $Identificacion?>&NumContrato=<? echo $NumContrato?>&Anio=<? echo $Anio?>&MesI=<? echo $MesI?>&MesF=<? echo $MesF?>&FecIni=<? echo $FecIni?>'" /></center>
</body>
</html>
