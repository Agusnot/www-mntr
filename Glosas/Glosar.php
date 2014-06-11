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
<form name="FORMA" method="post" enctype="multipart/form-data">
<br><br><br>
<table BORDER=1  style='font : normal normal small-caps 12px Tahoma;' border="1" bordercolor="#e5e5e5" cellpadding="2" align="center"> 
	<? /*<tr><td colspan="2" align="center"  bgcolor="#e5e5e5" style="font-weight:bold">Motivo Glosa</td></tr>
    <tr><td colspan="2" align="center"><textarea name="MotivoGlosa"></textarea></td></tr>*/?>
    <tr><td  align="center" style="font-weight:bold">Iniciar Proceso de Glosa?</td></tr>
    <tr align="center">
    	<td><input type="button" value="Si" onClick="location.href='SiGlosa.php?DatNameSID=<? echo $DatNameSID?>&NoFac=<? echo $NoFac?>&VrFac=<? echo $VrFac?>'">&nbsp;&nbsp;&nbsp;
    		<input type="button" value="No" onClick="parent.document.getElementById('Fac'+<? echo $NoFac?>).checked=false;CerrarThis()">
        </td>
  	</tr>
</table>	
<input type="hidden" name="DatNameSID" value="<? echo $DatNameSID?>">
</form>
</body>
</html>
