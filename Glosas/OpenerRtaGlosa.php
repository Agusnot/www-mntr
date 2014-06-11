<?	
	if($DatNameSID){session_name("$DatNameSID");}
    session_start();
	include("Funciones.php");
	$ND=getdate();
	$cons="select mes from central.meses where numero='$ND[mon]'";
	$res=ExQuery($cons); echo ExError();
	$fila=ExFetch($res); $Mes=$fila[0];
	
	$cons2="select encabezado,piepag from facturacion.plantillartaglosa where compania='$Compania[0]'";
	$res2=ExQuery($cons2); echo ExError();
	$fila2=ExFetch($res2);
	$fila2[0]=str_replace("AÑO",$ND[year],$fila2[0]);	
	$fila2[0]=str_replace("MES",$Mes,$fila2[0]);	
	$fila2[0]=str_replace("DIA",$ND[mday],$fila2[0]);	
	
	if($FacI){$FacIni="and nofactura>=$FacI";}
	if($FacF){$FacFin="and nofactura<=$FacF";}
	if($Entidad){$Ent="and entidad='$Entidad'";} 
	if($Contrato){$Contr="and contrato='$Contrato'"; }
	if($NoContrato){$NoContr="and nocontrato='$NoContrato'"; }
	if($Facs){$FacsNoIn="and facturascredito.nofactura in ($Facs)";}
	$cons="select facturascredito.nofactura,fechacrea,(primape || segape || primnom || segnom) as noment,total,fecharadic,fechaglosa,vrglosa,motivoglosa,fecharta,argumentorta
	from facturacion.facturascredito,central.terceros
	where facturascredito.compania='$Compania[0]' and terceros.compania='$Compania[0]' and fechacrea>='$FechaIni 00:00:00' and fechacrea<='$FechaFin 23:59:59'
	and terceros.identificacion=facturascredito.entidad and fechavoboglosa is not null and fechaglosa is not null and fecharta is not null $FacIni $FacFin $Ent $Contr $NoContr $FacsNoIn
	order by facturascredito.nofactura";
	$res=ExQuery($cons); echo ExError();
	//echo $cons;
?>	
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
</head>

<body background="/Imgs/Fondo.jpg">
<form name="FORMA" method="post" onSubmit="return Validar()"> 
<table BORDER=0  border="1" bordercolor="#e5e5e5" cellpadding="2">  
	<tr align="justify">
    	<td><? echo $fila2[0]?></td>
    </tr>
</table>
<br>
<table BORDER=1  border="1" bordercolor="#e5e5e5" align="center">  
	<tr align="center" bgcolor="#e5e5e5" style="font-weight:bold">
	    <td>No Factura</td><td>Fecha Factura</td><td>Fecha Radicacion</td><td>Entidad</td><td>Vr Factura</td><td>Vr Glosa</td><td>Nota Glosa</td><td>Observacion</td>
    </tr>
<?	while($fila=ExFetch($res)){
		$Fec=explode(" ",$fila[1]);?>    
	    <tr>
        	<td align="center"><? echo $fila[0]?></td><td align="center"><? echo $Fec[0]?></td><td align="center"><? echo $fila[4]?></td><td align="center"><? echo $fila[2]?></td>
            <td align="right"><? echo number_format($fila[3],2)?></td><td align="right">&nbsp;<? if($fila[6]) {echo number_format($fila[6],2);}?></td>
           <td>&nbsp;<font style="font-size:9px"><? echo "$fila[5]"?></font> <? echo "<br>$fila[7]"?></td><td align="center"><? echo $fila[9]?>&nbsp;</td>
        </tr>
<?	}?>        
</table>
<br>
<table BORDER=0 border="1" bordercolor="#e5e5e5" cellpadding="2">  
	<tr align="justify">
    	<td><? echo $fila2[1]?></td>
    </tr>
</table>
<input type="hidden" name="DatNameSID" value="<? echo $DatNameSID?>">
</form>
</body>
</html>
