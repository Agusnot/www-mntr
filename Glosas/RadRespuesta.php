<?
	if($DatNameSID){session_name("$DatNameSID");}
	session_start();
	include("Funciones.php");
	$ND=getdate();
?>
<html>
<?
	
if($Guardar==1){
if($SinRad!=NULL){
while( list($cad,$val) = each($SinRad))
{
if($cad && $val)
{	

$cons="update facturacion.motivoglosa set estadoconciliado='SI' where tipoglosa=$cad and compania='$Compania[0]' ";		
$res = ExQuery($cons);	echo ExError();							
}
}
?>					
<script language="javascript">
alert("Se Ha Registrado con exito toda la informacion:");
</script>
<?
}
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
	
	function ChequearTodos(chkbox) 
	{ 
		for (var i=0;i < document.forms["FORMA"].elements.length;i++) 
		{ 
			var elemento = document.forms[0].elements[i]; 
			if (elemento.type == "checkbox") 
			{ 
				elemento.checked = chkbox.checked 
			} 
		} 
	}
	function GuardarRad(){
		if(document.FORMA.FechaRad!=null){
			if(document.FORMA.FechaRad.value>document.FORMA.FechaAtc.value){
				alert("La fecha de Radicacion no puede ser mayor a la actual!!!");
			}
			else{
				document.FORMA.Guardar.value=1;
				document.FORMA.submit();
			}
		}
		else{
			document.FORMA.Guardar.value=1;
			document.FORMA.submit();
		}
	}	
</script>
</head>

<body background="/Imgs/Fondo.jpg">

        <input type="button" value=" X " onClick="parent.document.getElementById('Fac'+<? echo $NoFac?>).checked=false;CerrarThis()" style="position:absolute;top:1px;right:1px;" 
title="Cerrar esta ventana">
<form name="FORMA" method="post" onSubmit="return Validar()">
<? 
	$cons="SELECT tipoglosa,claseglosa,vrglosa,aceptaglosa,observacionglosa,estadoconciliado FROM facturacion.motivoglosa 
	 where Compania='$Compania[0]' and nufactura='$NoFac'";
	 $res=ExQuery($cons);  
	 if(ExNumRows($res)>0){    
      ?>

<table  style='font : normal normal small-caps 12px Tahoma;' border="1" bordercolor="#e5e5e5" cellpadding="2" align="center"> 
	  
    <tr>
    
    <td  align="center" bgcolor="#e5e5e5" style="font-weight:bold" colspan="8">
 <div align="left" style="font-weight:bold"> Factura Nº:   <? echo $NoFac;?></div>
    Respuesta De Glosa</td></tr>
    <tr align="center">
    <td width="16" bgcolor="#e5e5e5" style="font-weight:bold"></td>
    	<td width="64" bgcolor="#e5e5e5" style="font-weight:bold">Codigo Glosa </td>
        <td width="76" bgcolor="#e5e5e5" style="font-weight:bold">Tipo Auditoria </td>      
        <td width="94" bgcolor="#e5e5e5" style="font-weight:bold">valor Glosa </td>
        <td width="97" bgcolor="#e5e5e5" style="font-weight:bold">valor Glosa Aceptada</td>
        <td width="317" bgcolor="#e5e5e5" style="font-weight:bold">Observacion del valor Aceptado</td>
    
        <td bgcolor="#e5e5e5" style="font-weight:bold"><input type="checkbox" name="Todos" onClick="ChequearTodos(this);" title="Seleccionar Todos"></td>
  	</tr>
    
    <? 
	 while($fila=ExFetch($res))
		{ 
		
		
		$i++; 
	 ?>       
    <tr align="center">    
    	<td> <img src="../Imgs/b_check.png"></td>
        <td><? echo $fila[0]?> </td>
        <td><? echo $fila[1]?> </td>            
        <td><? echo $fila[2]?> </td>
     <td><? echo $fila[3]?></td>
     <td><? echo $fila[4]?> </td>
        <td width="39">
    <input type="checkbox" name="SinRad[<? echo $fila[0]?>]" title="Guardar"   
<? $vali =SI; $Estado= $fila[5];
if($Estado==$vali){  ?> checked<? }?>
  
    >
          </td>
         <? } ?> 
</tr> 
    <tr>
    <td colspan="3" ></td> 
    </tr><tr> 
    <td colspan="3" ></td>	
 <td colspan="0"  align="center">
 </td>
<td colspan="0" align="center">

</td>		
</tr>
<tr>
<td align="center" colspan="8">
<button type="submit" onClick="GuardarRad()">Guardar</button></td>
</tr>
</table>

<? }	else{?>
    	<table style='font : normal normal small-caps 12px Tahoma;' border="1" bordercolor="#e5e5e5" cellpadding="2" align="center">  
        	<tr align="center"  bgcolor="#e5e5e5" style="font-weight:bold"><td>No hay Glosas que cumpan con los parametros de la busqueda</td></tr>
		</table>
<? } ?>
<input type="hidden" name="DatNameSID" value="<? echo $DatNameSID?>">
<input type="hidden" name="Guardar" value="">
</form>
</body>
</html>
