<?
	if($DatNameSID){session_name("$DatNameSID");}
	session_start();
	include("Funciones.php");
	$ND=getdate();
?>	
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	
</head>

<body background="/Imgs/Fondo.jpg">
<form name="FORMA" method="post" onSubmit="return Validar()"> 
<?
if($Ver){
	if($FacI){$FacIni="and nofactura>=$FacI";}
	if($FacF){$FacFin="and nofactura<=$FacF";}
	if($Entidad){$Ent="and entidad='$Entidad'";} 
	if($Contrato){$Contr="and contrato='$Contrato'"; }
	if($NoContrato){$NoContr="and nocontrato='$NoContrato'"; }
	$cons="select nofactura,fechacrea,(primape || segape || primnom || segnom) as noment,total,fecharadic,fechaglosa,vrglosa,motivoglosa 
	from facturacion.facturascredito,central.terceros,facturacion.respuestaglosa 
	
	where facturascredito.compania='$Compania[0]' and fechacrea>='$FechaIni 00:00:00' and fechacrea<='$FechaFin 23:59:59' and terceros.compania='$Compania[0]' and fecharadic is not null and respuestaglosa.nufactura=facturascredito.nofactura
	and terceros.identificacion=facturascredito.entidad $FacIni $FacFin $Ent $Contr $NoContr order by nofactura";
	$res=ExQuery($cons);
	//echo $cons;
	if(ExNumRows($res)>0){?>
	<table  style='font : normal normal small-caps 12px Tahoma;' border="1" bordercolor="#e5e5e5" cellpadding="2" align="center">  
    	<tr align="center"  bgcolor="#e5e5e5" style="font-weight:bold">	
        <td align="center" colspan="9"><font color="green">INFORME DE RESPUES GLOSAS</font></td>
        
        </tr>
        
        
        
		<tr align="center"  bgcolor="#e5e5e5" style="font-weight:bold">	  	
			
        <td>No Factura</td><td>Fecha Factura</td><td>Fecha Radicacion</td><td>Entidad</td><td>Valor Factura</td><td>Valor Glosa</td>
            <td>Valor Aceptado IPS</td> <td>Valor Objetado No aceptado IPS</td><td>Valor a Pagar EPS</td>
       	<?	while($fila=ExFetch($res)){
				$Fec=explode(" ",$fila[1]);?>	
       			<tr onMouseOver="this.bgColor='#AAD4FF'" onMouseOut="this.bgColor=''">                
                	<td align="center" title="Generar Oficio de Respues de Glosa" >
						<? echo $fila[0]?>
                  	</td>
                    <td align="center"><? echo $Fec[0]?></td><td align="center"><? echo $fila[4]?></td><td align="center"><? echo $fila[2]?></td>
                    <td align="right"><? echo number_format($fila[3],2)?></td>
                    <td align="right">
                    <font style="font-size:11px">
 <?	$numf= $fila[0];				     
$con4= "SELECT vrglosatotal,pagaipsglosa,aceptaglosa,pagarips FROM facturacion.respuestaglosa where nufactura='$numf'";					
$res4= ExQuery($con4);
while($fil=ExFetch($res4))
{ echo number_format($fil[0],2); ?>                    
                    </font>
                    </td> <td><? echo number_format($fil[2],2)?></td>                   
                    <td><? echo number_format($fil[1],2)?></td>
                    <td><? echo number_format($fil[3],2)?></td><? }?>                 
                </tr> <? }?></tr></table>
<?	} else{?>
    	<table style='font : normal normal small-caps 12px Tahoma;' border="1" bordercolor="#e5e5e5" cellpadding="2" align="center">  
        	<tr align="center"  bgcolor="#e5e5e5" style="font-weight:bold"><td>No hay Glosas que cumpan con los parametros de la busqueda</td></tr>
		</table> <?	}}?>
<input type="hidden" name="DatNameSID" value="<? echo $DatNameSID?>">
</form>
<iframe scrolling="yes" id="FrameOpener" name="FrameOpener" style="display:none" frameborder="0" height="1" style="border:#e5e5e5 ridge"></iframe> 
</body>
</html>
