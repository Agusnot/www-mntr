<?	
	if($DatNameSID){session_name("$DatNameSID");}
	session_start();
	include("Funciones.php");
	$ND=getdate();
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<script language="javascript" src="/Funciones.js"></script>
</head>

<body background="/Imgs/Fondo.jpg">
<form name="FORMA" method="post" >  
<?
if($Ver)
{
	if($Ambito){
			$Amb=" and tiposervicio='$Ambito'";
	}else{$Amb="";}
	if($CedPac){
		$CP="and servicios.cedula ilike '$CedPac%'";
	}else{$CP="";}
	$cons="select servicios.numservicio,tiposervicio,servicios.cedula,primape,segape,primnom,segnom,fechaing,fechaegr,noliquidacion,pagador,contrato,nocontrato 
	from salud.servicios,central.terceros,salud.ambitos,facturacion.liquidacion
	where servicios.compania='$Compania[0]' and (consultaextern=1 or pyp =1) and terceros.compania='$Compania[0]' and identificacion=servicios.cedula 
	and ambitos.ambito=tiposervicio
	and ambitos.compania='$Compania[0]' and liquidacion.compania='$Compania[0]' and liquidacion.numservicio=servicios.numservicio and nofactura is null $Amb $CP
	order by primape,segape,primnom,segnom,tiposervicio";
	$res=ExQuery($cons);
	//echo $cons;?>
	<table BORDER=1  style='font : normal normal small-caps 12px Tahoma;' bordercolor="#e5e5e5" cellpadding="2" align="center">  
    	<tr bgcolor="#e5e5e5" style="font-weight:bold" align="center">	
        	<td>Idenficacion</td><td>Nombre</td><td>Proceso</td><td>Fecha Inicio</td><td>Fecha Finl</td>
        </tr>
<?	while($fila=ExFetch($res))
	{
		$Fecha=explode(" ",$fila[7]);?>
		<tr style="cursor:hand" onMouseOver="this.bgColor='#AAD4FF'" onMouseOut="this.bgColor=''"
        onClick="location.href='ServxConsolidxPac.php?DatNameSID=<? echo $DatNameSID?>&NumServM=<? echo $fila[0]?>&CargarEnLiq=1&NoLiq=<? echo $fila[9]?>&CedPac=<? echo $fila[2]?>&NomPac=<? echo "$fila[3] $fila[4] $fila[5] $fila[6]" ?>&PagaM=<? echo $fila[10]?>&ContraM=<? echo $fila[11]?>&NoContraM=<? echo $fila[12]?>&Ambito=<? echo $fila[1]?>&FechaIni=<? echo $Fecha[0]?>'">
        	<td><? echo $fila[2]?></td><td><? echo "$fila[3] $fila[4] $fila[5] $fila[6]"?></td><td><? echo $fila[1]?></td>
            <td><? echo $fila[7]?></td><td><? echo $fila[8]?></td>
        </tr>	
<?	}?>        
	</table><?
}
?>
<input type="hidden" name="DatNameSID" value="<? echo $DatNameSID?>">
</form>
</body>
</html>