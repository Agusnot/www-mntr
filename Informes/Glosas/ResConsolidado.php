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
$cons="select motivoglosa.nufactura,primape,segape,primnom,
segnom,identificacion,vrtotal,tipoglosa,
motivoglosa.vrglosa ,observacionglosa,aceptaglosa,obseraceptado
from facturacion.facturascredito,central.terceros,facturacion.motivoglosa,facturacion.liquidacion 	
where facturascredito.compania='$Compania[0]' and facturascredito.fechacrea>='$FechaIni 00:00:00' and facturascredito.fechacrea<='$FechaFin 23:59:59' and terceros.compania='$Compania[0]' and fecharadic is not null 
and motivoglosa.nufactura=facturascredito.nofactura and motivoglosa.nufactura=liquidacion.nofactura
and terceros.identificacion=liquidacion.cedula $FacIni $FacFin $Ent $Contr $NoContr order by motivoglosa.nufactura ASC";
$res=ExQuery($cons);
	if(ExNumRows($res)>0){?>
	<table  style='font : normal normal small-caps 10px Tahoma;' border="1" bordercolor="#e5e5e5" cellpadding="2" align="center">  
    	<tr align="center"  bgcolor="#e5e5e5" style="font-weight:bold">	
        <td align="center" colspan="16"><font color="#000000" size="4">INFORME CONSOLIDADO</font></td>        
        </tr>       
		<tr align="center"  bgcolor="#e5e5e5" style="font-weight:bold">	 
<td>Nº</td>		
<td width="56">Nº Factura</td>
<td width="143">Primer Apellido del Paciente</td>
<td width="61">Segundo Apellido Del paciente</td>
<td width="59">Primer Nombre del Paciente</td>
<td width="61">Segundo nombre Del paciente</td>
<td width="90">Numero de identifacion del paciente</td>
<td width="56">Valor Total factura</td>
<td width="52">Codigo Glosa</td>
<td width="64">Valor Objetado</td>
<td width="103">Observaciones</td>
<td width="95">Valor aceptado por IPS</td>
<td width="139">Valor de la objecion No aceptado por IPS</td>
<td>RESPUESTA IPS</td>
<?	while($fila=ExFetch($res)){
$Fec=explode(" ",$fila[1]);?>	
	  <tr onMouseOver="this.bgColor='#AAD4FF'" onMouseOut="this.bgColor=''">         
         <td align="center" >  <? $cont++; echo $cont ?> 	</td>		 
         <td><? echo $fila[0]?></td>
         <td align="center"><? echo $fila[1]?></td>   
         <td align="center"><? echo $fila[2]?></td>
         <td align="center"><? echo $fila[3]?></td>
         <td align="center"><? echo $fila[4]?></td>
		 <td align="center"><? echo $fila[5]?></td>		
		 <td align="right"><? echo number_format($fila[6],2)?></td>       
		 <td align="center"><? echo $fila[7]?></td>                   
         <td align="right"><? echo number_format($fila[8],2)?></td>
         <td align="justify"><? echo $fila[9]?></td>
		 <td align="right"><? echo number_format ($fila[10],2)?></td>
         <td align="right">
		 <?
		 $saldo=0;
		 $saldo= $fila[8]-$fila[10];
		 echo number_format ($saldo,2);
		 ?>		 
		 </td>
         <td align="justify"><? echo $fila[11]?></td>					               
         </tr>			
<? 

$totalfac=$totalfac+$fila[6];
$vobjetado=$vobjetado+$fila[8];
$vaceptado=$vaceptado+$fila[10];
$noaceptado=$noaceptado+$saldo;
} ?>
 <tr  bgcolor="#e5e5e5" style="font-weight:bold">
		 <td colspan="7" align="right">TOTALIZADO</td>
		 <td align="right"><? echo number_format ($totalfac,2)?></td>
		 <td align="center"></td>
		 <td align="right"><? echo number_format ($vobjetado,2)?></td>
		 <td></td>
		 <td align="right"><? echo number_format ($vaceptado,2)?></td>
		 <td align="right"><? echo number_format ($noaceptado,2)?></td>
		 </tr>
		 <tr>
		 <td align="center" colspan="18"><input value="Imprimir"  onClick="Imprimir()" type="button"></td>
		 </tr>
  </table>
<? 	} 
else{ ?>
<table style='font : normal normal small-caps 12px Tahoma;' border="1" bordercolor="#e5e5e5" cellpadding="2" align="center">  
<tr align="center" bgcolor="#e5e5e5"style="font-weight:bold"><td>No hay Glosas que cumpan con los parametros de la busqueda</td></tr>
</table> <?	}}?>
<input type="hidden" name="DatNameSID" value="<? echo $DatNameSID?>">
</form>
<iframe scrolling="yes" id="FrameOpener" name="FrameOpener" style="display:none" frameborder="0" height="1" style="border:#e5e5e5 ridge">
</iframe> 
</body>
</html>
