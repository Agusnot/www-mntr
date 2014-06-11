<?
	if($DatNameSID){session_name("$DatNameSID");}
	session_start();
	include("Funciones.php");
	$ND=getdate();	
	
	
	if($Guardar==1){
		if($SinRad!=NULL){
			while( list($cad,$val) = each($SinRad))
			         {
				if($cad && $val)
				       {	
$tiempo = strftime("%Y-%m-%d %H:%M:%S",time());		
$cons= "INSERT INTO facturacion.informerespuesglosa(numeroinforme,compania,fecharasis,usuariorespuesta,encabezado,firma,nufactura)   VALUES('$NumeroInforme','$Compania[0]','$tiempo','$usuario[1]','$EncabezadoInforme','$FirmaInforme','$cad') ";	
$res = ExQuery($cons);	echo ExError();							
				       }				
			         }
?>					
<script language="javascript">
alert("Se Ha Registrado con exito toda la informacion:");
</script>
<? }
else{
?>
<script language="javascript">
alert("Se debe seleccionar por lo menos una factura")
</script>
<? }				   
}	
	
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<script language='javascript' src="/calendario/popcalendar.js"></script>
<script language="javascript">
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


<script language="javascript">
	function Validar()
	{	
 if(document.FORMA.NumeroDocumento.value==""){alert("Debe Seleccionar un tipo de glosa!"); document.FORMA.NumeroDocumento.focus() ; return false;}

}
</script>

<script type="text/javascript" src="/tinymce/jscripts/tiny_mce/tiny_mce.js"></script>
<script type="text/javascript">
	tinyMCE.init({
		// General options
		mode : "textareas",
		theme : "advanced",
		plugins : "style,advimage,advlink,emotions,iespell,insertdatetime,media,searchreplace,print,contextmenu,paste,directionality,fullscreen,noneditable,visualchars,xhtmlxtras,template,wordcount,advlist,autosave",

		// Theme options
		theme_advanced_buttons1 : "bold,italic,underline,strikethrough,|,justifyleft,justifycenter,justifyright,justifyfull,formatselect,fontselect,fontsizeselect",
		theme_advanced_buttons2 : "cut,copy,paste,|,search,replace,|,bullist,numlist,|,outdent,indent,|,undo,redo,|,link,unlink,image,|,insertdate,inserttime,|,forecolor,backcolor",
		theme_advanced_buttons3 : "",
		theme_advanced_buttons4 : "insertlayer,moveforward,movebackward,absolute,|,styleprops,|,cite,abbr,acronym,del,ins,attribs,|,visualchars,nonbreaking,template,pagebreak,restoredraft",
		theme_advanced_toolbar_location : "top",
		theme_advanced_toolbar_align : "left",
		theme_advanced_resizing : true,
		content_css : "css/content.css",
		template_external_list_url : "lists/template_list.js",
		external_link_list_url : "lists/link_list.js",
		external_image_list_url : "lists/image_list.js",
		media_external_list_url : "lists/media_list.js",

		// Style formats
		style_formats : [
			{title : 'Bold text', inline : 'b'},
			{title : 'Red text', inline : 'span', styles : {color : '#ff0000'}},
			{title : 'Red header', block : 'h1', styles : {color : '#ff0000'}},
			{title : 'Example 1', inline : 'span', classes : 'example1'},
			{title : 'Example 2', inline : 'span', classes : 'example2'},
			{title : 'Table styles'},
			{title : 'Table row 0', selector : 'tr', classes : 'tablerow1'}
		],

		// Replace values for the template plugin
		template_replace_values : {
			username : "Some User",
			staffid : "991234"
		}
	});
</script>

</head>

<body background="/Imgs/Fondo.jpg">
<form name="FORMA" method="post" onSubmit="return Validar()"> 



<p>
<table width="365" border="2" align="center" cellpadding="2" bordercolor="#e5e5e5" style='font : normal normal small-caps 12px Tahoma;'>
   <tr lign="center"  bgcolor="#e5e5e5" style="font-weight:bold">
   <td colspan="4" align="center">REALIZAR INFORME</td>     
   </tr>
   <tr lign="center"  bgcolor="#e5e5e5" style="font-weight:bold">
   <td><input type="checkbox" name="Todos" onClick="ChequearTodos(this);" title="Seleccionar Todos"></td>
   <td>Numero de factura</td>
   <td>valor factura</td>
   <td>fecha radicado</td>
   </tr>
 <?
 $conexion="select nufactura, vrtotal ,fecharasis FROM facturacion.respuestaglosa WHERE compania='$Compania[0]'";
 $resp=ExQuery($conexion);
 while($row=ExFetch($resp)){
 ?>  
   <tr>   
<?  
$consul= "select nufactura FROM facturacion.informerespuesglosa  where nufactura='$row[0]'";
					 $respues=Exquery($consul);
					 while($fill=ExFetch($respues)){
						$Radicado=$fill[0];						
					 }
					if($Radicado==$row[0]){?>
<td><input type="checkbox" name="Rad[<? echo $fila[0]?>]" title="Elimanar Radiacacion" checked disabled value="<? echo $fila[0]?>"></td>
				<?	}
					else{?>
	            		<td><input type="checkbox" name="SinRad[<? echo $row[0]?>]" title="Radiacar"></td>
                <? }    
 ?> 
   <td><? echo $row[0]?></td>
   <td><? echo number_format ($row[1],2)?></td>
   <td><? echo $row[2]; }?></td>
   </tr>   
</table>
<p></p>
<table width="625" border="2" align="center" cellpadding="2" bordercolor="#e5e5e5"   style='font : normal normal small-caps 12px Tahoma;'>
  <tr>
    <td align="center" colspan="4" bgcolor="#e5e5e5" style="font-weight:bold">      
      <font size="4">FORMATO DE RESPUESTA DE GLOSA</font></br></td>
  </tr>
  <tr> 
   <td colspan="6" >
  <b> Encabezado Del Oficio de La glosa: </b><br></br>
  <textarea name="EncabezadoInforme" cols="80" rows="8" ></textarea> 
  </td></tr> 

  <tr>
  <td colspan="6" > <b> Firma del informe Del Oficio de La glosa:</b> <br></br>
  <textarea name="FirmaInforme" cols="80" rows="8" ></textarea>  
  </td></tr> 
  <tr> 
  <td  bgcolor="#e5e5e5" style="font-weight:bold">Numero De informe</td>
   <td > 
   <input name="NumeroInforme" type="text"> 
   </td>
  </tr> <tr>  <td height="41" colspan="6"><div align="center">     
     <input type="button" value="Realizar Informe" onClick="GuardarRad()" onKeyUp="Validar(this.value)" > </div> </td>
</tr></table>
  <input type="hidden" name="DatNameSID" value="<? echo $DatNameSID?>">
  <input type="hidden" name="Guardar" value="">
</p>
</form>    
</body>
</html>

	