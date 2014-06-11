<?	
	if($DatNameSID){session_name("$DatNameSID");}
	session_start();
	include("Funciones.php");
	$ND=getdate();
?>
	
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<script language='javascript' src="/calendario/popcalendar.js"></script>
<script language="javascript" src="/Funciones.js"></script>
<script language="javascript">
	function Validar()
	{
		if(document.FORMA.FechaIni.value>document.FORMA.FechaFin.value)
		{alert("La fecha final debe ser mayor a la fecha inicial!!!");return false;}
	}
</script>
</head>

<body background="/Imgs/Fondo.jpg">
<form name="FORMA" method="post" onsubmit="return Validar()">  
<table BORDER=1  style='font : normal normal small-caps 12px Tahoma;' border="1" bordercolor="#e5e5e5" cellpadding="2" align="center">  
<?	if(!$FechaIni)
	{
		if($ND[mon]){$C1="0";}
		$FechaIni="$ND[year]-$C1$ND[mon]-01";
	}
	if(!$FechaFin)
	{
		if($ND[mday]){$C2="0";}
		$FechaFin="$ND[year]-$C1$ND[mon]-$C2$ND[mday]";
	}?>
	<tr align="center"> 
    	<td colspan="2"  bgcolor="#e5e5e5" style="font-weight:bold">Periodo</td>
        <td rowspan="2"><input type="submit" value="Ver" name="Ver"/></td>
	</tr>
    <tr>
    	<td>
        	<input type="text" readonly="readonly" name="FechaIni" onClick="popUpCalendar(this, FORMA.FechaIni, 'yyyy-mm-dd')" 
            value="<? echo $FechaIni?>" style="width:80"/>
        </td>
        <td>
        	<input type="text" readonly="readonly" name="FechaFin" onClick="popUpCalendar(this, FORMA.FechaFin, 'yyyy-mm-dd')" 
            value="<? echo $FechaFin?>" style="width:80"/>
        </td>
    </tr>
</table>
<?	
if($Ver){	
	$cons="select cedula,(primape || ' ' || segape || ' ' || primnom || ' ' || segnom),movimiento.autoid,codigo1,nombreprod1,unidadmedida,presentacion
	,fecha,numservicio
	from consumo.movimiento,consumo.almacenesppales,central.terceros,consumo.codproductos
	where movimiento.compania='$Compania[0]' and (comprobante='Salidas por Plantilla' or comprobante='Salidas Urgentes') 
	and fecha>='$FechaIni' and fecha<='$FechaFin' and almacenesppales.almacenppal=movimiento.almacenppal and almacenesppales.compania='$Compania[0]'
	and almacenesppales.ssfarmaceutico=1 and movimiento.estado='AC' and terceros.compania='$Compania[0]' and identificacion=cedula 
	and codproductos.compania='$Compania[0]' and codproductos.almacenppal=movimiento.almacenppal and codproductos.autoid=movimiento.autoid and pos=0";
	$res=ExQuery($cons);?>
    <table BORDER=1  style='font : normal normal small-caps 12px Tahoma;' border="1" bordercolor="#e5e5e5" cellpadding="2" align="center">  
    	<tr bgcolor="#e5e5e5" style="font-weight:bold">
        	<td>Identificacion</td><td>Nombre</td><td>Cod Producto</td><td>Nom Producto</td><td>Fecha</td><td>Entidad</td><td>Contrato</td><td>No Contrato</td>
        </tr>
 	<?	while($fila=ExFetch($res))
		{
			if($fila[8]){
				$cons2="select (primape || ' ' || segape || ' ' || primnom || ' ' || segnom),contrato,nocontrato 
				from salud.pagadorxservicios,central.terceros
				where pagadorxservicios.compania='$Compania[0]' and numservicio=$fila[8] and pagadorxservicios.tipo=1 and terceros.compania='$Compania[0]'
				and identificacion=entidad";	
				$res2=ExQuery($cons2);
				$fila2=ExFetch($res2);
			}?>	
			<tr><td><? echo $fila[0]?></td><td><? echo $fila[1]?></td><td><? echo "$fila[3]"?></td><td><? echo "$fila[4] $fila[6] $fila[5]";?></td>
            <td><? echo $fila[7]?></td><td><? echo $fila2[0]?>&nbsp;</td><td><? echo $fila2[1]?>&nbsp;</td><td><? echo $fila2[2]?>&nbsp;</td></tr>
	<?	}?>
	</table><?
}?>
</form>    
</body>
</html>