<?
	if($DatNameSID){session_name("$DatNameSID");}
	session_start();
	include("Funciones.php");
	if($Guardar){
		$cons="update ContratacionSalud.CupsXPlanes set Valor=$Precio where compania='$Compania[0]' and AutoId=$Plan and CUP='$Codigo'";
		$res=ExQuery($cons);
		?>
        <script language="javascript">
			parent.document.FORMA.submit();
		</script>
        <?
	}
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<script language="javascript" src="/Funciones.js"></script>
<script language="javascript">
	function CerrarThis()
	{
		parent.document.getElementById('FrameOpener').style.position='absolute';
		parent.document.getElementById('FrameOpener').style.top='1px';
		parent.document.getElementById('FrameOpener').style.left='1px';
		parent.document.getElementById('FrameOpener').style.width='1';
		parent.document.getElementById('FrameOpener').style.height='1';
		parent.document.getElementById('FrameOpener').style.display='none';
		//parent.document.FORMA.submit();
	}
	function Validar()
	{
		if(document.FORMA.Precio.value==""){alert("Debe digitar el precio CUP!!!");return false;}
	}
</script>
</head>

<body background="/Imgs/Fondo.jpg">
<form name="FORMA" method="post" onSubmit="return Validar()">
<table cellpadding="4"  border="1" bordercolor="#e5e5e5" style="font-family:<?echo $Estilo[8]?>;font-size:12px;font-style:<?echo $Estilo[10]?>" width="100%">
	<tr>
    	<td colspan="10" align="right"><button type="button" name="Cerrar" onClick="CerrarThis()"><img src="/Imgs/b_drop.png" title="Cerrar"></button></td>
    </tr>
	<tr>
    	<td bgcolor="#e5e5e5" align="center" style="font-weight:bold">Codigo</td><td><? echo $Codigo?></td>	
    	<td bgcolor="#e5e5e5" align="center" style="font-weight:bold">Nombre</td><td><? echo $Nombre?></td>
        <td bgcolor="#e5e5e5" align="center" style="font-weight:bold">Precio</td>
        <td>
        	<input type="text" name="Precio" value="<? echo $Precio?>" onKeyDown="xNumero(this)" onKeyPress="xNumero(this)" onKeyUp="xNumero(this)" style="width:70; text-align:right"/>
        </td>       
	</tr>
    <tr align="center">
    	<td colspan="10"><input type="submit" name="Guardar" value="Guardar" /></td>
    </tr>
</table>        
<input type="hidden" name="Codigo" value="<? echo $Codigo?>" />
<input type="hidden" name="Nombre" value="<? echo $Nombre?>" />
<input type="hidden" name="Plan" value="<? echo $Plan?>" />
</form>
</body>
</html>
