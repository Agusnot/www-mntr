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
        <td align="center" colspan="14"><font color="green">INFORME TRAZABILIDAD</font></td>
        
        </tr>
        
        
        
		<tr align="center"  bgcolor="#e5e5e5" style="font-weight:bold">	  	
			
        <td>Asegurador</td><td>Numero Factura</td><td>Fecha Generacion Factura</td><td>Valor Factura</td><td>Fecha Radicacion Factura</td><td>VALOR EN TRAMITE</td>
            <td>VALOR DEVUELTA</td> <td>VALOR GLOSA</td><td>FECHA RADICACION GLOSA</td><td>VALOR ACEPTADO GLOSA</td><td>SALDO FACTURA (D-G)</td><td>FECHA PAGO REALIZADO</td><td>VALOR PAGO</td><td>VALOR PENDIENTE PAGO (D-J-G)</td>
       	<?	while($fila=ExFetch($res)){
				$Fec=explode(" ",$fila[1]);?>	
       			<tr onMouseOver="this.bgColor='#AAD4FF'" onMouseOut="this.bgColor=''">                
         <td align="center" ><? echo $fila[2]?>   	</td>
         <td align="center"><? echo $fila[0]?></td>   
         <td align="center"><? echo $Fec[0]?></td>
         <td align="center"><? echo number_format($fila[3],2)?></td>
         <td align="right"><? echo $fila[4] ?></td>
         <td align="right">
                    <font style="font-size:11px">
 <?	$numf= $fila[0];				     
$con4= "SELECT vrglosatotal,fecharasis,aceptaglosa,pagarips FROM facturacion.respuestaglosa where nufactura='$numf'";					
$res4= ExQuery($con4);
while($fil=ExFetch($res4))
{  ?>                    
                    </font>
                    </td> <td></td>                   
                    <td><? echo number_format($fil[0],2)?></td>
                    <td><? echo $fil[1] ?></td>
					<td><? echo number_format($fil[2],2)?></td>
                    <td><? echo number_format($fil[3],2)?></td>
                  <td><? echo $fil[1] ?>*</td>
                    <td>
                    <? 
					$totalfactura= $totalfactura+ $fila[3];	
					$totalglosa= $totalglosa+$fil[0];
					$tvaloraceptado=$tvaloraceptado+$fil[2];	
					$saldofactura=$saldofactura+ $fil[3];		
					?>               
                    
                    
                    </td>
                  <td></td>
					<? }?>                 
                </tr> <? }?><td height="25"></tr>
<tr align="center"  bgcolor="#e5e5e5" style="font-weight:bold" >
                <td colspan="14" align="center">TOTALIZADO:</td>
                </tr>
                <tr>
                <td colspan="3"></td>
               
                <td align="center"  bgcolor="#e5e5e5" style="font-weight:bold"><? echo number_format($totalfactura,2)?></td>
                <td></td>
                <td align="center"  bgcolor="#e5e5e5" style="font-weight:bold"></td>
                <td align="center"  bgcolor="#e5e5e5" style="font-weight:bold"></td>
                <td align="center"  bgcolor="#e5e5e5" style="font-weight:bold"><? echo number_format($totalglosa,2)?></td>
                <td ></td>
                <td align="center"  bgcolor="#e5e5e5" style="font-weight:bold"><? echo number_format($tvaloraceptado,2)?></td>
                <td align="center"  bgcolor="#e5e5e5" style="font-weight:bold"><? echo number_format($saldofactura,2) ?></td>
                <td ></td>
                <td align="center"  bgcolor="#e5e5e5" style="font-weight:bold"></td>
                <td align="center"  bgcolor="#e5e5e5" style="font-weight:bold"></td>
                
             
                </tr>
                
                
  </table>
<?	} else{?>
    	<table style='font : normal normal small-caps 12px Tahoma;' border="1" bordercolor="#e5e5e5" cellpadding="2" align="center">  
        	<tr align="center"  bgcolor="#e5e5e5" style="font-weight:bold"><td>No hay Glosas que cumpan con los parametros de la busqueda</td></tr>
		</table> <?	}}?>
<input type="hidden" name="DatNameSID" value="<? echo $DatNameSID?>">
</form>
<iframe scrolling="yes" id="FrameOpener" name="FrameOpener" style="display:none" frameborder="0" height="1" style="border:#e5e5e5 ridge"></iframe> 
</body>
</html>
