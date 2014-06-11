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
		   {  
			 $respuesta->Assign("divSugerencias", "innerHTML", ""); 
			 return $respuesta; 
		   }
			 if ($num == 0) {
			 $output = "<font color='red'>No existe</font>"; 
							}			   
				   else if ($num == 1)  { 
							$row = ExFetch($res45);
											if (strcasecmp($input, $row[0]) == 0)
											   {
												 $output = "";
												}
					 else {
						   $output = "  <div id='divLista'> <table border='2' align='center' cellpadding='2' bordercolor='#e5e5e5'   style='font : normal normal small-caps 12px Tahoma;' > <tr onMouseOver='this.bgColor='#AAD4FF'' onMouseOut='this.bgColor='''> 
	<td onClick=\"xajax_seleccion('".$row[0]."');xajax_autocompleta('".$row[0]."')\">".$row
	[0]." - ".$row[1]."</td> </tr> </div> </table>";
						  }
										}
					 else {
						   $output .= "<div id='divLista'> <table  border='2' align='center' cellpadding='2' bordercolor='#e5e5e5'   style='font : normal normal small-caps 12px Tahoma;' > <tr onMouseOver='this.bgColor='#AAD4FF'' onMouseOut='this.bgColor='''>";
						   while ($row = ExFetch($res45)) { $output .= "<tr style='cursor:hand'><td
						   onClick=\"xajax_seleccion('".$row[0]."');xajax_autocompleta('".$row[0]."')\">".$row
						   [0]." - ".$row[1]."</td></tr>";
						  }
						  $output .= "</div></table>";
						  }
	$respuesta->Assign("divSugerencias", "innerHTML", $output);
	return $respuesta;     
}
//---
function seleccion($pais)
{
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
		parent.location.reload();
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
		    if($VrGlosa<=$Saldo or $VrGlosa<=$VrFac){	
			$tiempo = strftime("%Y-%m-%d %H:%M:%S",time());	
			$cons="INSERT INTO facturacion.motivoglosa(compania, tipoglosa,claseglosa, observacionglosa, vrtotal, 
			vrglosa,fecharasis,usuarioglosa,nufactura )
			VALUES ('$Compania[0]','$TipoGlosa','$ClaseGlosa','$ObservacionGlosa',$VrFac,$VrGlosa,'$tiempo',
			'$usuario[1]',$NoFac)";						
			$res=ExQuery($cons);
			
			$consulta="select nufactura from facturacion.respuestaglosa WHERE compania='$Compania[0]' and nufactura='$NoFac'";
			$respuesta=ExQuery($consulta);
			if(ExNumRows($respuesta)<1){
		
			$consult="INSERT INTO  facturacion.respuestaglosa(compania,nufactura) VALUES('$Compania[0]',$NoFac)";
			$resp=ExQuery($consult);
				}			
			
			echo ExError($res); 	
		}
		 else
		 {
?>
        <script language="JavaScript">
	     alert ("El Valor Digitado Excede el valor de la factura!!");
	   </script>        
         <?	} }
		else {
		?>   <script language="JavaScript">
	     alert ("debe seleccionar un tipo de Glosa diferente!!!");
	   </script>        
         <?	} } ?>
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
document.FORMA.VrGlosa.value==""; document.FORMA.VrGlosa.focus(); return false; }		
	
</script>
<script language="javascript">
function habilita(form)
				{ 
				form.VrGlosa.disabled = false;
				document.FORMA.VrGlosa.value='';
				}

function deshabilita(form)
				{ 
				document.FORMA.VrGlosa.value=document.FORMA.VTotal.value;
				}
</script>

<?php $xajax->printJavascript("../xajax/"); ?>
<style type="text/css">
#divLista{ position:absolute; left: 82px;width:500px;height:100px;overflow:auto;border:solid 1px #ccc;background-color:#fff;}
</style>
</head>
<body background="/Imgs/Fondo.jpg">


<input type="button" value=" X " onClick="parent.document.getElementById('Fac'+<? echo $NoFac?>).checked=false;CerrarThis()" style="position:absolute;top:1px;right:1px;" 
title="Cerrar esta ventana">
<form name="FORMA" method="post" onSubmit="return Validar()">
<input type="hidden" name="VrFac" value="<? echo $VrFac?>">
<table width="905" border="2" align="center" cellpadding="2" bordercolor="#e5e5e5"   style='font : normal normal small-caps 12px Tahoma;'> 
	<tr><td colspan="11" align="center"  bgcolor="#e5e5e5" style="font-weight:bold">
 <div align="left"> <font face="Verdana, Geneva, sans-serif" color="green">N° Fac: </font><? echo $NoFac; ?> </div>
    Motivo Glosa</td></tr>
    <tr><td width="103" rowspan="2" bgcolor="#e5e5e5" style="font-weight:bold">Codigo glosa </td>
    <td width="60" rowspan="2" >
       <input type="text" name="TipoGlosa" id="TipoGlosa" style="width:60px" onKeyUp="xajax_autocompleta(this.value)"
       value="<? echo $TipoGlosa?>">
       <div id="divSugerencias" style="margin-top:3px;"></div>    </td col>   
      <td width="91" rowspan="2" bgcolor="#e5e5e5" style="font-weight:bold">Tipo Auditoria</td>      
      <? $conclase="SELECT claseglosa FROM facturacion.clasesglosa"; 
	   ?>
	<td width="41" rowspan="2">   
    	<select name="ClaseGlosa" onChange="document.FORMA.submit()" onKeyUp="Validar(this.value)"><option></option>
      	<?	$res=ExQuery($conclase);
			while($row = ExFetch($res))
			{
				if($ClaseGlosa==$row[0])
				{ ?><option value="<? echo $row[0]?>" selected><? echo $row[0]?></option>
             <? }
			 	else
				{	?><option value="<? echo $row[0]?>"><? echo $row[0]?></option>
              <? }  }?>
        </select>      </td>      
      <td width="92" rowspan="2" bgcolor="#e5e5e5" >Observacion </td>
    <td width="175" rowspan="2" >
    <textarea name="ObservacionGlosa"  cols="24" rows="2" ><? echo $ObservacionGlosa ?></textarea>    </td>
    
    	<td width="131"   bgcolor="#e5e5e5" style="font-weight:bold">Glosa todo</td>
         <td width="110" height="26"> 
		   <input type="radio" name="Todo" value="si" checked onClick="habilita(this.form)">No
		   <input type="radio" name="Todo" value="no" onClick="deshabilita(this.form)"> Si		  </td>
        
      <td width="26" colspan="4" rowspan="2" align="left">  
   <button type="submit" name="Guardar" onKeyUp="Validar(this.value)"><img src="/Imgs/b_check.png"></button>     </td> </tr>
    <tr>
      <td width="131"   bgcolor="#e5e5e5" style="font-weight:bold">Valor Glosar</td>
      <td>  <input type="hidden" name="VTotal" value="<? echo $VrFac; ?>">      
        
        <input type="text" name="VrGlosa" onKeyDown="xNumero(this)"  onKeyUp="xNumero(this)" style="width:90px" value="<? echo $VrGlosa?>">
        <? $co="SELECT vrglosatotal FROM facturacion.respuestaglosa WHERE nufactura='$NoFac'";
		$re= ExQuery($co);
		while($fi= ExFetch($re)){ ?> <input type="hidden" name="Saldo" value="<? echo $fi[0]; ?>"> <? }?>  </td>
    </tr>
    </tr>
   </tr>
</table>
<input type="hidden" name="DatNameSID" value="<? echo $DatNameSID?>">
</form>	
 <iframe frameborder="0" id="ListaGlosa" src="ListaGlosa.php?DatNameSID=<? echo $DatNameSID?>&TipoGlosa=<? echo $TipoGlosa?>&ClaseGlosa=<? echo $ClaseGlosa?>&VrFac=<? echo $VrFac?>&NoFac=<? echo $NoFac?>" width="100%" height="85%"></iframe>                     
</body>
</html>
