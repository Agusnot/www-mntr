<?
	if($DatNameSID){session_name("$DatNameSID");}
	session_start();
	include("Funciones.php");
	if($Guardar)
	{
		$cons = "Update Infraestructura.CodElementos set NotaBaja = '$NotaBaja' Where Compania='$Compania[0]' and AutoId=$AutoId and Codigo='$Codigo'";
		$res = ExQuery($cons);	
	}
	$cons = "Select NotaBaja from Infraestructura.CodElementos Where Compania='$Compania[0]' and AutoId=$AutoId and Codigo='$Codigo'";
	$res = ExQuery($cons);
	$fila = ExFetch($res);
	$NotaBaja = $fila[0];
?>
<script language="javascript">
	function CerrarThis()
	{
		parent.document.getElementById('FrameOpener').style.position='absolute';
		//parent.document.getElementById('FrameOpener').style.top='1px';
		//parent.document.getElementById('FrameOpener').style.left='1px';
		parent.document.getElementById('FrameOpener').style.width='1';
		parent.document.getElementById('FrameOpener').style.height='1';
		parent.document.getElementById('FrameOpener').style.display='none';
	}
</script>
<body background="/Imgs/Fondo.jpg" onLoad="document.FORMA.NotaBaja.focus()">
<form name="FORMA" method="post">
<input type="hidden" name="DatNameSID" value="<? echo $DatNameSID?>" />
<input type="hidden" name="Codigo" value="<? echo $Codigo?>" />
<input type="hidden" name="AutoId" value="<? echo $AutoId?>" />
<div align="right">
<button name="Cerrar" title="Cerrar" onClick="CerrarThis()" ><img src="/Imgs/b_drop.png"/></button>
</div>
<table width="100%" style='font : normal normal small-caps 12px Tahoma;' border="1" bordercolor="#e5e5e5">
<tr>
<td width="100%" bgcolor="#e5e5e5" align="center" style="font-weight:bold">Nota de Baja para este Elemento</td>
</tr>
<tr>
<td><textarea name="NotaBaja" style=" background-image:url(/Imgs/Fondo.jpg);width:100%;" rows="5"><? echo $NotaBaja?></textarea></td>
</tr>
<tr>
	<td align="center"><input type="submit" name="Guardar" value="Guardar" /></td>
</tr>
</table>
</form>
</body>