<?
	if($DatNameSID){session_name("$DatNameSID");}
	session_start();
	include("Funciones.php");
	$ND=getdate();
	$cons="select plantillaprocedimientos.cedula,primape,segape,primnom,segnom,tiposervicio,usuario,FechaIni from salud.plantillaprocedimientos,central.terceros,salud.servicios
	where plantillaprocedimientos.compania='$Compania[0]' and usuario='$usuario[1]' and (interpretacion='' or interpretacion is null) 
	and rutaimg is not null and terceros.compania='$Compania[0]' and identificacion=plantillaprocedimientos.cedula and servicios.cedula=plantillaprocedimientos.cedula
	and servicios.numservicio=plantillaprocedimientos.numservicio order by primape,segape,primnom,segnom";
	$res=ExQuery($cons);	
	//echo $cons;
	
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

</head>

<body background="/Imgs/Fondo.jpg">
<form name="FORMA" method="post">
<table BORDER=1  border="1" bordercolor="#e5e5e5" cellpadding="1" align="center" style='font : normal normal small-caps 12px Tahoma;'>   
<tr bgcolor="#e5e5e5" style="font-weight:bold" align="center" style='font : normal normal small-caps 12px Tahoma;'>  
	<td colspan="9">PACIENTES CON LABORATORIOS POR INTERPRETAR</td>    
</tr> 
<tr bgcolor="#e5e5e5" style="font-weight:bold" align="center" style='font : normal normal small-caps 12px Tahoma;'>  
	<td>Nombre</td><td>Identificacion</td><td>Proceso</td><td>Solicitado x</td><td>Fecha</td>
</tr>
<?
while($fila=ExFetch($res))
{
	$cons2="Select Nombre from Central.usuarios where Usuario='$fila[6]'";
	$res2=ExQuery($cons2);
	$fila2=ExFetch($res2);
	?>
	<tr  onMouseOver="this.bgColor='#AAD4FF'" onMouseOut="this.bgColor=''" title="Abrir Historia" style="cursor:hand"
    onClick="location.href='ResultBuscarHC.php?DatNameSID=<? echo $DatNameSID?>&Cedula=<? echo $fila[0]?>&Buscar=1'">
    	<td><? echo "$fila[1] $fila[2] $fila[3] $fila[4]";?></td><td><? echo $fila[0]?></td><td><? echo $fila[5]?></td><td><? echo $fila2[0]?></td><td><? echo $fila[7]?></td>
    </tr><?	
}
?>
</table>
</form>
</body>
</html>
