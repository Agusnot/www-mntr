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
?>
	<table width="798" border="2" align="center" cellpadding="2" bordercolor="#e5e5e5"  style='font : normal normal small-caps 12px Tahoma;'>  
    
    <tr lign="center"  >
    <td colspan="9" align="center" >
      <p><font size="4" ><B>FORMATO INFORME CONCILIACION </B></font>   </p>
    
      <table width="788" border="2"  align="center" cellpadding="2" bordercolor="#e5e5e5"  style='font : normal normal small-caps 12px Tahoma;'>
<tr>
<td width="207" bgcolor="#e5e5e5" style="font-weight:bold">NOMBRE IPS</td>
<td width="202"><? echo "<font size='2' >".$Compania[0]."</font>"?></td>
<td width="178" bgcolor="#e5e5e5" style="font-weight:bold">CONSECUTIVO INTERNO</td>
<td width="163">&nbsp;</td>
</tr>
<tr>
<td bgcolor="#e5e5e5" style="font-weight:bold">TIPO DE IDENTIFICACIÓN IPS</td>
<td><? echo "<font size='2' >NIT</font>"?></td>
<td bgcolor="#e5e5e5" style="font-weight:bold">FECHA CONCILIACIÓN</td>
<td>		  
</td>
</tr>
<tr>
<td bgcolor="#e5e5e5" style="font-weight:bold">NIT DE LA IPS</td>
<td><? echo "<font size='2' >".$Compania[1]."</font>"?></td>
<td bgcolor="#e5e5e5" style="font-weight:bold">PERIODO CONCILIADO</td>
<td><? echo $FechaIni." - ".$FechaFin ?></td>
</tr>
</table>
<p align="left"><? echo "<font size='2' >".$fila[2]."</font>"?></p></td>    
</tr>    
<tr align="center"  bgcolor="#e5e5e5" style="font-weight:bold">	
<td width="106">FECHA</td>
<td width="56">VALOR FACTURA</td>
<td width="66">VALOR OBJECIÓN</td>
<td width="66">% OBJECIÓN</td>
<td width="90">VALOR RECUPERADO</td>
<td width="90">VALOR GLOSA DEFINITIVA</td>
<td width="90">% RECUPERADO</td>
<td width="52">% GLOSA FINAL</td>
<td width="108">OBSERVACIONES</td></tr>
<?	



$fecha1="$FechaIni"; 
$fecha1=date("m-d-Y",strtotime($fecha1));
$fechaInicio = $fecha1; 
$mesInicio = substr($fechaInicio, 0, 2); 
$diaInicio  = substr($fechaInicio, 3, 3); 
$anioInicio= substr($fechaInicio, 6, 10);  
// fecha fin
$fecha2="$FechaFin"; 
$fecha2=date("m-d-Y",strtotime($fecha2));
$fechaFi = $fecha2; 
$mesFin = substr($fechaFi, 0, 2); 
$diaFin  = substr($fechaFi, 3, 3); 
$anioFin= substr($fechaFi, 6, 10);  
$mes=date("$mesInicio");
$mes2=date("$mesFin");
for($mes=$mes; $mes<=$mesFin;$mes++)
{?>
<tr > 	  
<td align='center' style='cursor:hand'>
<?
if ($mes==01){
echo "Enero";
} else if ($mes==02){
echo "Febrero";
} else if ($mes==03){
echo "Marzo";
} else if ($mes==04){
echo "Abril";
} else if ($mes==05){
echo "Mayo";
} else if ($mes==06){
echo "Junio";
} else if ($mes==07){
echo "Julio";
}else if ($mes==08){
echo "Junio";
}  else if ($mes==10){
echo "Octubre";
} else if ($mes==11){
echo "Noviembre";
} else if ($mes==12){
echo "Diciembre";  }
echo  "&nbsp;&nbsp;<br>";
?>
</td>
<td align="right">
<?
$cons="select fechaconciliacion,vrtotal FROM facturacion.respuestaglosa 
where compania='$Compania[0]' and fechaconciliacion>='$FechaIni 00:00:00' and  fechaconciliacion<='$FechaFin 23:59:59'";
$res=ExQuery($cons);	
while($fila=ExFetch($res)){	
$vfactura= "$fila[0]";
$vfactura=date("m-d-Y",strtotime($vfactura));
$vfactura1 = $vfactura; 
$mesf = substr($vfactura1, 0, 2); 
$diaf  = substr($vfactura1, 3, 3); 
$aniof= substr($vfactura1, 6, 10);  
if($mes==$mesf){
echo number_format($fila[1],2);
 }
} 
?>  
  </td>
  <td align="right">
 <?
$cons="select fechaconciliacion, vrglosatotal FROM facturacion.respuestaglosa 
where compania='$Compania[0]' and fechaconciliacion>='$FechaIni 00:00:00' and  fechaconciliacion<='$FechaFin 23:59:59'";
$res=ExQuery($cons);	
while($fila=ExFetch($res)){	
$vfactura= "$fila[0]";
$vfactura=date("m-d-Y",strtotime($vfactura));
$vfactura1 = $vfactura; 
$mesf = substr($vfactura1, 0, 2); 
$diaf  = substr($vfactura1, 3, 3); 
$aniof= substr($vfactura1, 6, 10);  
if($mes==$mesf){
echo number_format($fila[1],2);
 }
} 
?>   
</td>
<td align="right">
 <?
$cons="select fechaconciliacion, vrtotal,vrglosatotal FROM facturacion.respuestaglosa 
where compania='$Compania[0]' and fechaconciliacion>='$FechaIni 00:00:00' and  fechaconciliacion<='$FechaFin 23:59:59'";
$res=ExQuery($cons);	
while($fila=ExFetch($res)){	
$vfactura= "$fila[0]";
$vfactura=date("m-d-Y",strtotime($vfactura));
$vfactura1 = $vfactura; 
$mesf = substr($vfactura1, 0, 2); 
$diaf  = substr($vfactura1, 3, 3); 
$aniof= substr($vfactura1, 6, 10);  
if($mes==$mesf){

$promedio=round((($fila[2]/$fila[1])*100),0);
echo "%".number_format($promedio,2);
 }
} 
?>   
  </td> <td align="right">
 <?
$cons="select fechaconciliacion, aceptaglosa FROM facturacion.respuestaglosa 
where compania='$Compania[0]' and fechaconciliacion>='$FechaIni 00:00:00' and  fechaconciliacion<='$FechaFin 23:59:59'";
$res=ExQuery($cons);	
while($fila=ExFetch($res)){	
$vfactura= "$fila[0]";
$vfactura=date("m-d-Y",strtotime($vfactura));
$vfactura1 = $vfactura; 
$mesf = substr($vfactura1, 0, 2); 
$diaf  = substr($vfactura1, 3, 3); 
$aniof= substr($vfactura1, 6, 10);  
if($mes==$mesf){
echo number_format($fila[1],2);
 }
} 
?>  
  </td>
      <td align="right">
 <?
$cons="select fechaconciliacion, vrglosatotal,aceptaglosa FROM facturacion.respuestaglosa 
where compania='$Compania[0]' and fechaconciliacion>='$FechaIni 00:00:00' and  fechaconciliacion<='$FechaFin 23:59:59'";
$res=ExQuery($cons);	
while($fila=ExFetch($res)){	
$vfactura= "$fila[0]";
$vfactura=date("m-d-Y",strtotime($vfactura));
$vfactura1 = $vfactura; 
$mesf = substr($vfactura1, 0, 2); 
$diaf  = substr($vfactura1, 3, 3); 
$aniof= substr($vfactura1, 6, 10);  
if($mes==$mesf){
$promedio1=$fila[1]-$fila[2];
echo number_format($promedio1,2);
 }
} 
?>   
</td>
<td align="right">
 <?
$cons="select fechaconciliacion, vrglosatotal,aceptaglosa FROM facturacion.respuestaglosa 
where compania='$Compania[0]' and fechaconciliacion>='$FechaIni 00:00:00' and  fechaconciliacion<='$FechaFin 23:59:59'";
$res=ExQuery($cons);	
while($fila=ExFetch($res)){	
$vfactura= "$fila[0]";
$vfactura=date("m-d-Y",strtotime($vfactura));
$vfactura1 = $vfactura; 
$mesf = substr($vfactura1, 0, 2); 
$diaf  = substr($vfactura1, 3, 3); 
$aniof= substr($vfactura1, 6, 10);  
if($mes==$mesf){
$promedio2=round((($fila[2]/$fila[1])*100),0);
if($promedio>=0){
echo "%".number_format($promedio2,2);
 }}
} 
?>   
</td>
<td align="right">
 <?
$cons="select fechaconciliacion, vrtotal,aceptaglosa FROM facturacion.respuestaglosa 
where compania='$Compania[0]' and fechaconciliacion>='$FechaIni 00:00:00' and  fechaconciliacion<='$FechaFin 23:59:59'";
$res=ExQuery($cons);	
while($fila=ExFetch($res)){	
$vfactura= "$fila[0]";
$vfactura=date("m-d-Y",strtotime($vfactura));
$vfactura1 = $vfactura; 
$mesf = substr($vfactura1, 0, 2); 
$diaf  = substr($vfactura1, 3, 3); 
$aniof= substr($vfactura1, 6, 10);  
if($mes==$mesf){
$promedio2=round((($promedio1/$fila[1])*100),0);
echo "%".number_format($promedio2,2);
 }
} 
?>   
</td>
<td align="right"s>
 <?
$cons="select fechaconciliacion, vrtotal,aceptaglosa FROM facturacion.respuestaglosa 
where compania='$Compania[0]' and fechaconciliacion>='$FechaIni 00:00:00' and  fechaconciliacion<='$FechaFin 23:59:59'";
$res=ExQuery($cons);	
while($fila=ExFetch($res)){	
$vfactura= "$fila[0]";
$vfactura=date("m-d-Y",strtotime($vfactura));
$vfactura1 = $vfactura; 
$mesf = substr($vfactura1, 0, 2); 
$diaf  = substr($vfactura1, 3, 3); 
$aniof= substr($vfactura1, 6, 10);  
if($mes==$mesf){
echo "-";
 }
} 
?>   
</td>
</tr> 
 <? }  ?>
  </table>
 <br></br>
 <table width="200" border="2" align="center" cellpadding="2" bordercolor="#e5e5e5"  style='font : normal normal small-caps 12px Tahoma;'>
	<tr>
	<td align="center">
<? 
  $conexion="SELECT nombre,cedula,usuario FROM central.usuarios where usuario='$usuario[1]' ";
  $respuesta= ExQuery($conexion);
  $fiz=ExFetch($respuesta); 
  $nombre=$fiz[0];	 
  $user=$fiz[2]; 
  $cons="select rm,cargo from salud.medicos where   usuario='$user'";
				$res=ExQuery($cons);
				$fila=ExFetch($res);
				$RM=$fila[0];
				$Cargo=$fila[1]; 
echo "<br><b>".$nombre."</b>"; 

?>
	 </td>
	</tr>
	<tr>
	  <td>
   <? 
if (file_exists($_SERVER['DOCUMENT_ROOT']."/Firmas/$fiz[1].GIF")){?>      	
	<img src="/Firmas/<? echo $fiz[1]?>.GIF" width="158" height="63"><?
}  ?> 	  
	  </td>  </tr>
	  <tr>
	  <td align="center"><? echo "<b>".$Cargo."</b>" ?> </td>
	  </tr>
	<tr>	
	<td height="24" align="center"><b>INFORME CONCILIACION . <? echo $RM ?></b></td>
	 </tr>
    </table>
	
	<div align="center"><input value="Imprimir"  onClick="Imprimir()" type="button"></div>
  
  <input type="hidden" name="DatNameSID" value="<?  echo $DatNameSID?>">
</form>
<? } ?>
<iframe scrolling="yes" id="FrameOpener" name="FrameOpener" style="display:none" frameborder="0" height="1" style="border:#e5e5e5 ridge"></iframe> 
</body>
</html>
