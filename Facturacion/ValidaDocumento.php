<?php
	if($DatNameSID){session_name("$DatNameSID");}
	session_start();
	include("Funciones.php");
?>
<script language="javascript">
	function CerrarThis()
	{
		parent.document.getElementById('FrameOpener').style.position='absolute';
		parent.document.getElementById('FrameOpener').style.top='1px';
		parent.document.getElementById('FrameOpener').style.left='1px';
		parent.document.getElementById('FrameOpener').style.width='1';
		parent.document.getElementById('FrameOpener').style.height='1';
		parent.document.getElementById('FrameOpener').style.display='none';
		parent.document.FORMA.submit();
	}
</script>

<head>
<style>
	a{color:blue; text-decoration:none;}
	a:hover{color:red; text-decoration:underline;}
</style>


<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
</head>
<body background="/Imgs/Fondo.jpg">
<form name="FORMA" method="post">
<input type="button" value=" X " onClick="CerrarThis()" style="position:absolute;top:1px;right:1px;" title="Cerrar esta ventana">
<table bordercolor="#e5e5e5" border="1" style='font : normal normal small-caps 12px Tahoma;'>
<?
	$cons="Select Identificacion,PrimApe,SegApe,PrimNom,SegNom from Central.Terceros where Compania='$Compania[0]' and Identificacion ilike '$Cedula%'";
	$res=ExQuery($cons);
	while($fila=ExFetch($res))
	{
		?>
		<tr title="seleccionar" onMouseOver="this.bgColor='#AAD4FF'" style="cursor:hand" onMouseOut="this.bgColor=''"
      		onClick="parent.document.FORMA.Cedula.value='<? echo $fila[0]?>';parent.document.FORMA.CambCed.value='<? echo $fila[0]?>';CerrarThis();" >
        	<td><? echo $fila[0]?></td>
        	<td><? echo "$fila[1] $fila[2] $fila[3] $fila[4]"?></td></tr>
<?	}
?>
<input type="hidden" name="DatNameSID" value="<? echo $DatNameSID?>">
</table>
</form>
</body>
</html>
