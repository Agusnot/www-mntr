<?
	if($DatNameSID){session_name("$DatNameSID");}
	session_start();
	include("Funciones.php");
	$ND=getdate();
?>	
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<script language="javascript">
function Imprimir()
{
window.print();
}
</script>
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
$cons="select nofactura,fechacrea,(primape || segape || primnom || segnom) as noment,total,fecharadic,fechaglosa,vrglosa,
motivoglosa ,vrglosatotal,pagaipsglosa,aceptaglosa,pagarips ,totalrecepcion,contrato,numradicacion
from facturacion.facturascredito,central.terceros ,facturacion.respuestaglosa 
where facturascredito.compania='$Compania[0]' and fechacrea>='$FechaIni 00:00:00' and fechacrea<='$FechaFin 23:59:59' and terceros.compania='$Compania[0]' and fecharadic is not null and respuestaglosa.nufactura=facturascredito.nofactura and respuestaglosa.vrglosatotal<respuestaglosa.vrtotal
and terceros.identificacion=facturascredito.entidad $FacIni $FacFin $Ent $Contr $NoContr order by entidad ASC, nofactura ASC";
$res=ExQuery($cons);
if(ExNumRows($res)>0){?>
	<table width="911" border="2" align="center" cellpadding="2" bordercolor="#e5e5e5"  style='font : normal normal small-caps 10px Tahoma;'>  
    
    <tr lign="center"  bgcolor="#e5e5e5" style="font-weight:bold">
    <td colspan="15" align="center">
    <font color="#000000" size="4">INFORME RECEPCION DE RESPUESTA</font>
    </td>
    
    </tr>
    
    <tr align="center"  bgcolor="#e5e5e5" style="font-weight:bold">	
        
	<td width="17">Nº</td>		
  <td width="56">No Factura</td>
  <td width="56">Fecha Factura</td>
  <td width="79">Fecha Radicacion</td>
  <td width="54">Entidad</td>
   <td width="54">Contrato</td>
   <td>Numero Radicacion</td>
  <td width="56">Valor Factura</td>
    <td width="46">Valor Glosa</td>
	  <td width="81">Valor Aceptado IPS</td>
	    <td width="95">Valor Objetado No aceptado IPS</td>
		  <td width="52">Valor a Pagar EPS</td>
		  <td width="86">Valor Reiterado</td>
		 
		  <strong></strong>
       	<?	while($fila=ExFetch($res)){
				$Fec=explode(" ",$fila[1]);?>	
	  <tr onMouseOver="this.bgColor='#AAD4FF'" onMouseOut="this.bgColor=''">
<td><? $cont++; echo $cont?></td>
<td align="center" style="cursor:hand" title="Ver" onClick="open('/Facturacion/IntermedioFactura.php?DatNameSID=<? echo $DatNameSID?>&NoFac=<? echo $fila[0]?>&Estado=<? echo "AC"?>','','left=10,top=10,width=790,height=600,menubar=yes,scrollbars=YES')"><? echo $fila[0]?></td>
<td align="center"><? echo $Fec[0]?></td>
<td align="center"><? echo $fila[4]?></td>
<td align="center"><? echo $fila[2]?></td>
<td align="center"><? echo $fila[13]?></td>
<td align="center"><? echo $fila[14]?></td>
<td align="right"><? echo number_format($fila[3],2)?></td>
<td align="right"><? echo number_format($fila[8],2)?></td>
<td align="right"><? echo number_format($fila[10],2)?></td>
<td align="right"><? echo number_format($fila[9],2)?></td>
<td align="right"><? echo number_format($fila[11],2)?></td>
<td align="right"><? echo number_format($fila[12],2)?></td>
                              
  </tr> <? }?>
  
  <tr>
  <td align="center" colspan="12"><input value="Imprimir"  onClick="Imprimir()" type="button"></td>
  </tr>
  
  
  	</table><?	} else{?>
  <table style='font : normal normal small-caps 12px Tahoma;' border="1" bordercolor="#e5e5e5" cellpadding="2" align="center">  
        	<tr align="center"  bgcolor="#e5e5e5" style="font-weight:bold"><td>No hay Glosas que cumpan con los parametros de la busqueda</td></tr>
  </table><?	} } ?><input type="hidden" name="DatNameSID" value="<? echo $DatNameSID?>">
</form>
<iframe scrolling="yes" id="FrameOpener" name="FrameOpener" style="display:none" frameborder="0" height="1" style="border:#e5e5e5 ridge"></iframe> 
</body>
</html>
