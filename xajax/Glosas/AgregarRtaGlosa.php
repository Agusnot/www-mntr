<?
	if($DatNameSID){session_name("$DatNameSID");}
	session_start();
	include("Funciones.php");
	$ND=getdate();	
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<script language='javascript' src="/Funciones.js"></script>
<script language="javascript">
	function CerrarThis()
	{
		parent.document.getElementById('FrameOpener').style.position='absolute';
		parent.document.getElementById('FrameOpener').style.top='1px';
		parent.document.getElementById('FrameOpener').style.left='1px';
		parent.document.getElementById('FrameOpener').style.width='1';
		parent.document.getElementById('FrameOpener').style.height='1';
		parent.document.getElementById('FrameOpener').style.display='none';
		for (i=0;i<parent.document.FORMA.elements.length;i++){
			if(parent.document.FORMA.elements[i].type == "checkbox"){
				parent.document.FORMA.elements[i].disabled = false;
			} 
		}
	}
</script>
<?
if($Guardar){
	$cons="insert into facturacion.tmprtaglosa (compania,nofactura,tmpcod,argumento) values ('$Compania[0]',$NoFac,'$TMPC','$Argumento')";
	$res=ExQuery($cons); echo ExError();
	//echo $cons;
	?><script language="javascript">CerrarThis();</script><?
}
?>

</head>

<body background="/Imgs/Fondo.jpg">
<input type="button" value=" X " onClick="parent.document.getElementById('Rta'+<? echo $NoFac?>).checked=false;CerrarThis()" style="position:absolute;top:1px;right:1px;" 
title="Cerrar esta ventana">
<form name="FORMA" method="post" enctype="multipart/form-data">
<br>
<table BORDER=1  style='font : normal normal small-caps 12px Tahoma;' border="1" bordercolor="#e5e5e5" cellpadding="2" align="center"> 
	<tr align="center">
    	<td bgcolor="#e5e5e5" style="font-weight:bold">Argumento</td>
    </tr>
    <tr align="center">
       	<td><textarea name="Argumento" cols="35" rows="6" onKeyDown="xLetra(this)" onKeyUp="xLetra(this)"></textarea></td>
   	</tr>
    <tr align="center">
    	<td><input type="submit" value="Guardar" name="Guardar"></td>
    </tr>    
</table>
<input type="hidden" name="DatNameSID" value="<? echo $DatNameSID?>">
</form>
</body>
</html>
