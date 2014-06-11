<?
	if($DatNameSID){session_name("$DatNameSID");}
	session_start();
	include("Funciones.php");
	$ND=getdate();
?>
<html>
<?
if($Guardar) {
	$cons1="Update facturacion.respuestaglosa Set  aceptaglosa=$VAceptado where nufactura='$NoFac'";
		$resul=ExQuery($cons1);	echo ExError();	}		
if($Modifica)
	{
	$cons="Update facturacion.motivoglosa 
 Set  aceptaglosa=$ValGlosa where Compania='$Compania[0]' and tipoglosa='$TipoGlosa' and nufactura='$NoFac'";
	$res=ExQuery($cons);   echo ExError($res);		
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
	}
</script>
</head>

<body background="/Imgs/Fondo.jpg">

        <input type="button" value=" X " onClick="parent.document.getElementById('Fac'+<? echo $NoFac?>).checked=false;CerrarThis()" style="position:absolute;top:1px;right:1px;" 
title="Cerrar esta ventana">
<form name="FORMA" method="post" onSubmit="return Validar()">

<table  style='font : normal normal small-caps 12px Tahoma;' border="1" bordercolor="#e5e5e5" cellpadding="2" align="center"> 
	  
    <tr>
    
    <td  align="center" bgcolor="#e5e5e5" style="font-weight:bold" colspan="8">
    <div align="left" style="font-weight:bold"><? echo $NoFac;?></div>
    Respuesta De Glosa</td></tr>
    <tr align="center">
    <td width="16" bgcolor="#e5e5e5" style="font-weight:bold"></td>
    	<td width="33" bgcolor="#e5e5e5" style="font-weight:bold">Tipo </td>
        <td width="43" bgcolor="#e5e5e5" style="font-weight:bold">Clase </td>
        <td width="162" bgcolor="#e5e5e5" style="font-weight:bold">Observacion </td>
        <td width="91" bgcolor="#e5e5e5" style="font-weight:bold">Glosa Total</td>
        <td width="103" bgcolor="#e5e5e5" style="font-weight:bold">Valor Item </td>
        <td width="118" bgcolor="#e5e5e5" style="font-weight:bold">valor Glosa Aceptada</td>
        <td  bgcolor="#e5e5e5" style="font-weight:bold"></td>
  	</tr>     <? 
	$cons="SELECT tipoglosa,claseglosa,observacionglosa,vrtotal,vrglosa,nufactura,aceptaglosa FROM facturacion.motivoglosa 
	 where Compania='$Compania[0]' and nufactura='$NoFac'";
	 $res=ExQuery($cons);       
      while($fila=ExFetch($res))
		{ $i++;  ?>    
    <tr align="center">    
    	<td> <img src="../Imgs/b_check.png"></td>
        <td><? echo $fila[0]?> </td>
        <td><? echo $fila[1]?> </td>
        <td><? echo $fila[2]?></td>
        <td><? echo $fila[3]?> </td>
        <td><? echo $fila[4]?> </td>
     <td><input name="ValGlosa" type="text" size="10" value="<? echo $fila[6]?>"  onKeyDown="xNumero(this)"  onKeyUp="xNumero(this)">
        
        </td>
        <td width="42">
        <button onClick="if(confirm('Desea registra el valor?\n')){location.href='Respuesta.php?DatNameSID=<? echo $DatNameSID?>&Modifica=1&TipoGlosa=<? echo $fila[0]?>&NoFac=<? echo $NoFac?>';}"><img src="../Imgs/vobo.jpg" width="17" height="17"></button>  
          </td>
          <?  } ?> 
</tr> 
    <tr>
    <td colspan="5" ></td>
    <td colspan="1" bgcolor="#e5e5e5" style="font-weight:bold" align="center">Total Glosa</td>
     <td colspan="1" bgcolor="#e5e5e5" style="font-weight:bold" align="center">valor Aceptado</td>    
    </tr>
      <tr>
  
    <td colspan="5" ></td>
    <? $conx= "SELECT vrglosa,aceptaglosa FROM facturacion.motivoglosa where  nufactura='$NoFac' ";
					$resx= ExQuery($conx);
					while($fil=ExFetch($resx))
					{	if ($fil[0]>=1)	{
						$Total = $Total + $fil[0];
						$TotalAceptado= $TotalAceptado + $fil[1];}}					
					?>	
 <td colspan="1"  align="center"><input name="TotalGlosa" type="hidden" size="8" disabled value="<? echo $Total?>">
 <? echo number_format($Total,2)?>
 </td>
<td colspan="1" align="center">
<input name="VAceptado" type="hidden" size="8" value=" <? echo $TotalAceptado ?>">
<? echo number_format($TotalAceptado,2)?>
</td>		
</tr>
<tr>
<td align="center" colspan="8">
<button type="submit" name="Guardar">Guardar</button></td>
</tr>
</table> 
<input type="hidden" name="DatNameSID" value="<? echo $DatNameSID?>"></form>
</body>
</html>
