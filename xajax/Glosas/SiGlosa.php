<?
if($DatNameSID){session_name("$DatNameSID");}
	session_start();
	include("Funciones.php");
	$ND=getdate();
	include "../xajax/xajax_core/xajax.inc.php";	
	$xajax= new xajax();	
	//---------------
	function autocompleta($input)
{

$respuesta = new xajaxResponse();
$con= "SELECT  codigo ,detalle FROM facturacion.codmotivoglosa WHERE  codigo  LIKE '".$input."%' OR  detalle LIKE '".$input."%' LIMIT 10 ";
$res45 = ExQuery($con);
$num = ExNumRows($res45);
if ($input == "")
        {  $respuesta->Assign("divSugerencias", "innerHTML", ""); return $respuesta; }
if ($num == 0) { $output = "<font color='red'>No existe</font>";  }			   
else if ($num == 1)  { $row = ExFetch($res45);
                       if (strcasecmp($input, $row[0]) == 0)
                                 { $output = ""; }
else   { 
 $output = "  <div id='divLista'> <table > <tr style='cursor:hand'> 
<td onClick=\"xajax_seleccion('".$row[0]."');xajax_autocompleta('".$row[0]."')\">".$row
[0]." - ".$row[1]."</td> </tr> </div> </table>";
       }}
else{ 
$output .= "<div id='divLista'> <table  cellpadding='0'cellspacing='0'>";
while ($row = ExFetch($res45))  
 { $output .= "<tr style='cursor:hand'><td
onClick=\"xajax_seleccion('".$row[0]."');xajax_autocompleta('".$row[0]."')\">".$row
[0]." - ".$row[1]."</td></tr>";  }
$output .= "</div></table>";   }
$respuesta->Assign("divSugerencias", "innerHTML", $output);
return $respuesta;}
//----
function seleccion($pais){
$respuesta = new xajaxResponse();
$respuesta->Assign("TipoGlosa", "value", $pais);
return $respuesta;
}
$xajax->registerFunction('autocompleta');
$xajax->registerFunction('seleccion');
$xajax->processRequest();

?>
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
<?	
	if($Guardar){		
	$con ="select tipoglosa,vrglosa FROM facturacion.motivoglosa where compania='$Compania[0]' and tipoglosa='$TipoGlosa' and nufactura='$NoFac' ";
		$resx=ExQuery($con);
		
			while($fila3=ExFetch($resx))
					{
						if ($fila3[0]>0)
						{$Total = $Total + $fila3[0];}						
					}
							
		
		if(ExNumRows($resx)==0)
		{		
			$tiempo = strftime("%Y-%m-%d %H:%M:%S",time());	
			$cons="INSERT INTO facturacion.motivoglosa(compania, tipoglosa,claseglosa, observacionglosa, vrtotal, 
			vrglosa,fecharasis,usuarioglosa,nufactura )
			VALUES ('$Compania[0]','$TipoGlosa','$ClaseGlosa','$ObservacionGlosa',$VrFac,$VrGlosa,'$tiempo',
			'$usuario[1]',$NoFac)";	
			
					
			$res=ExQuery($cons);
			echo ExError($res);                
		}
		else
		{
		?>
        <script language="JavaScript">
	     alert ("debe seleccionar un tipo de Glosa diferente!!!");
	   </script>        
         <?	
		}
						 
		 }	
	
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<script language='javascript' src="/calendario/popcalendar.js"></script>
<script language='javascript' src="/Funciones.js"></script>
<script language="javascript">


	function Validar()
	{	
 if(document.FORMA.TipoGlosa.value==""){alert("Debe Seleccionar un tipo de glosa!"); document.FORMA.TipoGlosa.focus() ; return false;}	

if(document.FORMA.ObservacionGlosa.value==""){alert("Debe Digitar una observacion!"); document.FORMA.ObservacionGlosa.focus(); return false;}
if(document.FORMA.VrGlosa.value==""){alert("Debe digitar el valor de la glosa!!!");	document.FORMA.VrGlosa.focus(); return false;}
if(document.FORMA.VrGlosa.value> document.FORMA.VrFac.value){alert("el valor de la glosa debe ser menor o igual al valor de la factura");
   document.FORMA.VrGlosa.value==""; document.FORMA.VrGlosa.focus(); return false; }
if(document.FORMA.VrGlosa.value>document.FORMA.Faltante.value){ alert("El valor a Glosar debe ser menor igual q el valor de la fatura"); return false;}		
	}
</script>
<script language="javascript">
function habilita(form)
{ 
form.VrGlosa.disabled = false;
document.FORMA.VrGlosa.value='';
}

function deshabilita(form)
{ 
form.VrGlosa.disabled = true;
document.FORMA.VrGlosa.value=document.FORMA.VTotal.value;
}</script>
<?php 
$xajax->printJavascript("../xajax/");
?>
<style type="text/css">
#divLista{
position:absolute;
left: 130px;
width:500px;
height:100px;
overflow:auto;
border:solid 1px #ccc;
background-color:#fff;
}
</style>
</head>
<body background="/Imgs/Fondo.jpg">
<input type="button" value=" X " onClick="parent.document.getElementById('Fac'+<? echo $NoFac?>).checked=false;CerrarThis()" style="position:absolute;top:1px;right:1px;" 
title="Cerrar esta ventana">
<form name="FORMA" method="post" onSubmit="return Validar()">
<input type="hidden" name="VrFac" value="<? echo $VrFac?>">
<table BORDER=1  style='font : normal normal small-caps 12px Tahoma;' border="1" bordercolor="#e5e5e5" cellpadding="2" align="center"> 
	<tr><td colspan="6" align="center"  bgcolor="#e5e5e5" style="font-weight:bold">
 <div align="left"> <font face="Verdana, Geneva, sans-serif" color="green">N° Fac: </font><? echo $NoFac; ?> </div>
    Motivo Glosa</td></tr>
    <tr><td bgcolor="#e5e5e5" style="font-weight:bold">Tipo glosa </td>
    <td >
       <input type="text" name="TipoGlosa" id="TipoGlosa" style="width:90px" onKeyUp="xajax_autocompleta(this.value)">
       <div id="divSugerencias" style="margin-top:3px;"></div> 
    </td col>   
      <td bgcolor="#e5e5e5" style="font-weight:bold">Clase glosa </td>      
      <?
	  $conclase="SELECT compania, claseglosa FROM facturacion.clasesglosa";
	  $res=ExQuery($conclase);
	  
	  ?>
	<td >   
      <select name="ClaseGlosa"    onChange="document.FORMA.submit();">    
      <?
	  while($filclase=ExFetch($res)){
		   if($ClaseGlosa==$filclase[0]){
			   
								echo "<option selected value='$filclase[1]'>$filclase[1] </option>";						
								                          }
                            else{echo "<option value='$filclase[1]'>$filclase[1] </option>";}   
	  }
	  ?>
      </select>     
      </td>
      
      <td bgcolor="#e5e5e5" >Observacion </td>
    <td >
    <textarea name="ObservacionGlosa"  cols="24" rows="2" ></textarea>
    </td>
    </tr></tr>
    	<td   bgcolor="#e5e5e5" style="font-weight:bold">Glosar Todo</td>
         <td>
         
       <input type="radio" name="Todo" value="si" checked onClick="habilita(this.form)">No

<input type="radio" name="Todo" value="no" onClick="deshabilita(this.form)"> Si
        </td>
        	<td  bgcolor="#e5e5e5" style="font-weight:bold">Valor A Glosar</td>
        <td>
        <input type="hidden" name="VTotal" value="<? echo $VrFac; ?>">      
        
        <input type="text" name="VrGlosa" onKeyDown="xNumero(this)"  onKeyUp="xNumero(this)" style="width:90px"></td>   
      
        </td>
   <td colspan="4" align="left">  
   <button type="submit" name="Guardar"><img src="/Imgs/b_check.png"></button>
   </td></tr>
</table>
<input type="hidden" name="DatNameSID" value="<? echo $DatNameSID?>">

</form>

 <iframe frameborder="0" id="ListaGlosa" src="ListaGlosa.php?DatNameSID=<? echo $DatNameSID?>&TipoGlosa=<? echo $TipoGlosa?>&ClaseGlosa=<? echo $ClaseGlosa?>&VrFac=<? echo $VrFac?>&NoFac=<? echo $NoFac?>" width="100%" height="85%"></iframe>                     
</body>
</html>
