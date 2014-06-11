<?
	if($DatNameSID){session_name("$DatNameSID");}
	session_start();
	include("Funciones.php");

	$ND=getdate();
?>
<html>
<?
if($Gtodo) {
ob_start();
	$cons1="Update facturacion.respuestaglosa 
	Set usuariorespuesta='$usuario[1]',aceptaglosa='$VAceptado',pagaipsglosa='$Objetado',pagarips='$ValorEPS'
	 where nufactura='$NoFac'";
	
		$resul=ExQuery($cons1);	
	echo ExError();	}			
if($Modifica){	
ob_start();
	$consm="Update facturacion.motivoglosa 
    Set aceptaglosa=$ValorAceptado, obseraceptado='$ArguAceptado' where  tipoglosa='$TipoGlosa' and nufactura='$NoFac'";
	$resu=ExQuery($consm); 
	?>
	<script language="javascript">
	
	location.href="Respuesta.php?DatNameSID=<? echo $DatNameSID?>&NoFac="+NoFac+"&VrFac="+VrFac;
	</script>
	<?
	echo ExError($resu);		
	         }
?>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<script language='javascript' src="/calendario/popcalendar.js"></script>
<script language='javascript' src="/Funciones.js"></script>
<script language="javascript">

function Validar()
{	
if(document.FORMA.VAceptado.value==""){	alert("Ingrese un valor de Glosa Aceptado");document.FORMA.ValorGlosa.focus();return false;}
if(document.FORMA.ELIMINA.button=true){ document.FORMA.ValorGlosa.disabled= true;}
}
</script>
<script language="javascript">
	function CerrarThis()
	{
		for (i=0;i<parent.document.FORMA.elements.length;i++){
			if(parent.document.FORMA.elements[i].type == "checkbox"){
				parent.document.FORMA.elements[i].disabled = false;
			} 
		}
		parent.document.getElementById('FrameOpener').style.position='absolute';
		parent.document.getElementById('FrameOpener').style.top='1px';
		parent.document.getElementById('FrameOpener').style.left='1px';
		parent.document.getElementById('FrameOpener').style.width='1';
		parent.document.getElementById('FrameOpener').style.height='1';
		parent.document.getElementById('FrameOpener').style.display='none';
		parent.location.reload();
	}
</script>
</head>
<body background="/Imgs/Fondo.jpg">
<input type="button" value=" X " onClick="parent.document.getElementById('Fac'+<? echo $NoFac?>).checked=false;CerrarThis()" style="position:absolute;top:1px;right:1px;" 
title="Cerrar esta ventana">
<form name="FORMA" method="post" onSubmit="return Validar()">
<?
$cons="SELECT tipoglosa,claseglosa,observacionglosa,vrtotal,vrglosa,nufactura,aceptaglosa,obseraceptado FROM facturacion.motivoglosa 
	 where Compania='$Compania[0]' and nufactura='$NoFac'
	 order by tipoglosa";
	 $res=ExQuery($cons);   
	 if(ExNumRows($res)>0){  
?>
<table  style='font : normal normal small-caps 12px Tahoma;' border="2" bordercolor="#e5e5e5" cellpadding="2" align="center">	  
    <tr>    
    <td  align="center" bgcolor="#e5e5e5" style="font-weight:bold" colspan="8">
 <div align="left" style="font-weight:bold"> Factura Nº:   <? echo $NoFac;?></div>
    Respuesta De Glosa</td></tr>
    <tr align="center">    
    	<td width="71" bgcolor="#e5e5e5" style="font-weight:bold">Codigo Glosa </td>
        <td width="364" bgcolor="#e5e5e5" style="font-weight:bold">Nombre</td>      
        <td width="99" bgcolor="#e5e5e5" style="font-weight:bold">valor Glosa </td>
        <td width="99" bgcolor="#e5e5e5" style="font-weight:bold">valor Glosa Aceptada</td>
		<td width="161" bgcolor="#e5e5e5" style="font-weight:bold">Obeservacion</td>      
        <td  bgcolor="#e5e5e5" style="font-weight:bold"></td>
  	</tr><? 	    
      while($fila=ExFetch($res))
		{ $i++;  ?>    
    <tr align="center">     	
        <td><? echo $fila[0]?> </td>
        <td>
        <?
		$numeroTipo=  $fila[0];
		$consult="SELECT codigo,detalle FROM facturacion.codmotivoglosa WHERE  codigo='$numeroTipo'";
		$resp=ExQuery($consult);
		while($row=ExFetch($resp)){	echo $row[1]; }		
		?>        
      </td>            
      <td><? echo number_format ($fila[4],2)?> <input name"Acepta" type="hidden" size="5" value="<? echo $fila[4]?> "> </td>		
     <td>
     <input name="ValorAceptado_<? echo $fila[0]?>" type="text" size="7" value="<? echo $fila[6]?>"  onKeyDown="xNumero(this)"  onKeyUp="xNumero(this)"></td>
	 <td> <textarea name="ArguAceptado_<? echo $fila[0]?>" cols="30" rows="2"><?  echo $fila[7]?></textarea></td>    
        <td width="34">
         <button onClick="        
      if((document.FORMA.ValorAceptado_<? echo $fila[0]?>.value!='') 
	  )
      {   
        	if(confirm('Desea registra el valor?'))
            {	
  location.href='Respuesta.php?DatNameSID=<? echo $DatNameSID?>&Modifica=1&TipoGlosa=<? echo $fila[0]?>&NoFac=<? echo $NoFac?>&ValorAceptado='+document.FORMA.ValorAceptado_<? echo $fila[0]?>.value+'&ArguAceptado='+document.FORMA.ArguAceptado_<? echo $fila[0]?>.value;
           	}
    	}                                                        
        else{
        	alert('Atencion: El campo esta vacion o el valor Ingresado Excede el valor de la glosa');
            document.FORMA.ValorAceptado_<? echo $fila[0]?>.value=''
            document.FORMA.ValorAceptado_<? echo $fila[0]?>.focus();
       	}"
       	><img src="../Imgs/vobo.jpg" width="17" height="17"></button>
      </td>
          <? } ?>   		  
</tr> 
    <tr>
    <td colspan="6">
   <table  style='font : normal normal small-caps 12px Tahoma;' border="2" bordercolor="#e5e5e5" cellpadding="2"  align="center">
  <tr>
   
    <td width="206" colspan="1" align="center" bgcolor="#e5e5e5" style="font-weight:bold">Total Glosa</td>
    <td colspan="3" align="center" bgcolor="#e5e5e5" style="font-weight:bold">valor Aceptado</td>
  </tr>
  <tr><? 


unset($Total,$TotalAceptado,$faltante,$Objetado,$valoreps);
$conx= "SELECT vrtotal,vrglosa,aceptaglosa FROM facturacion.motivoglosa where  nufactura='$NoFac'";	  

$resx= ExQuery($conx);
while($fil=ExFetch($resx))
{	
if ($fil[0]>=1)	{					
$Total = ($Total+$fil[1]);
$TotalAceptado=($TotalAceptado+$fil[2]);												 
$Objetado= ($Total-$TotalAceptado);
$valoreps= ($fil[0]-$TotalAceptado);						 					
}}					
?>
    <td colspan="0"  align="center">
      <? echo number_format($Total,2)?></td>
    <td width="194" colspan="0" align="center"><input name="VAceptado" type="hidden" readonly="1" size="8" value=" <? echo $TotalAceptado ?>">
      <? echo number_format($TotalAceptado,2)?>
     
      <input name="Objetado" type="hidden" size="5" readonly="1"  value="<? echo $Objetado ?>">
      <input name="ValorEPS" type="hidden" size="5" readonly="1"  value="<? echo $valoreps ?>">	  
	  </td>
  </tr>
  <tr>
    <td colspan="6" align="center"><font color="green" ><b>Nota: Recuerde Guardar Los cambion Presionando Click en el boton inferior!!!</b></font></td>
  </tr>
  <tr>
    <td align="center" colspan="8">	
	
<button name="Gtodo" type="submit" >Guardar </button>
	</td>
  </tr>
  </table>
    </td>    
</tr>
</table> 
<? }else{?>
  <table style='font : normal normal small-caps 12px Tahoma;' border="1" bordercolor="#e5e5e5" cellpadding="2" align="center">  
        	<tr align="center"  bgcolor="#e5e5e5" style="font-weight:bold"><td>No hay Respuesta De Glosa con esta factura actualmente!!!!!!!</td></tr>
		</table>
<? } ?>
<input type="hidden" name="DatNameSID" value="<? echo $DatNameSID?>"></form>
</body>
</html>
