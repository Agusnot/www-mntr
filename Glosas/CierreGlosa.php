<?
	if($DatNameSID){session_name("$DatNameSID");}
	session_start();
	include("Funciones.php");
	$ND=getdate();
			 
if($Gtodo)
{
$consm="Update facturacion.respuestaglosa
Set estado='$CierreGlosa', usuariocierre='$usuario[1]'		
where nufactura='$NoFac'";
$resu=ExQuery($consm);	
?>
<script language="javascript">
alert('Cambios realizados exitosamente!!!')
</script>
<?
echo ExError($resu);	
         }					 	 
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<script language='javascript' src="/calendario/popcalendar.js"></script>
<script language='javascript' src="/Funciones.js"></script>

<script language="javascript">
	function CerrarThis()
	{
		for (i=0;i<parent.document.FORMA.elements.length;i++){
			if(parent.document.FORMA.elements[i].type == "checkbox")
			{
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


<table  style='font : normal normal small-caps 12px Tahoma;' border="1" bordercolor="#e5e5e5" cellpadding="2" align="center"> 
	  
    <tr>
    
    <td  align="center" bgcolor="#e5e5e5" style="font-weight:bold" colspan="8">

    Cierre de Conciliacion</td></tr>
    <tr align="left"> 
    	<td width="145" bgcolor="#e5e5e5" style="font-weight:bold" align="left">Numero De Factura </td> 
		<td align="left">
		<? echo $NoFac;?>
		</td>   
       
    <tr align="center">    
    <td  bgcolor="#e5e5e5" style="font-weight:bold" align="left"> Estado</td>	
	  <td width="124" align="left">	  
	  <select name="CierreGlosa" id="CierreGlosa" >
	  <option value="AC" >Activa</option>
	  <option value="AN" >Inactiva</option>
	  </select>
	  </td></tr> 
 <tr>
  <td align="center" colspan="10">
  </td>
  </tr>
  <tr>
  <td align="center" colspan="10">
  <button name="Gtodo" type="submit" 
   >Guardar </button>
  </td>
  </tr>
</table>
<input type="hidden" name="DatNameSID" value="<? echo $DatNameSID?>">
<input type="hidden" name="Guardar" value="">
</form>
</body>
</html>
