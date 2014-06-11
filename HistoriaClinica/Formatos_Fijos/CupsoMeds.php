<?	
	session_start();
	include("Funciones.php");
?>	
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<script language="javascript">
	function CerrarThis()
	{
		parent.document.getElementById('FrameOpener').style.position='absolute';
		parent.document.getElementById('FrameOpener').style.top='1px';
		parent.document.getElementById('FrameOpener').style.left='1px';
		parent.document.getElementById('FrameOpener').style.width='1';
		parent.document.getElementById('FrameOpener').style.height='1';
		parent.document.getElementById('FrameOpener').style.display='none';
	}
	function Nuevo(T)
	{
		if(parent.document.FORMA.PagaNocont.value==""||parent.document.FORMA.PagaCont.value==""||parent.document.FORMA.Paga.value=="")
		{
			alert("Debe seleccionar todos los datos del pagador");
		}
		else{
			parent.document.FORMA.NoEnvia.value=1;
			parent.document.FORMA.TipoNuevo.value=T;
			parent.document.FORMA.submit();
			CerrarThis();
		}
	}
</script>	
</head>

<body background="/Imgs/Fondo.jpg">
<form name="FORMA" method="post">
<input type="button" value=" X " onClick="CerrarThis()" style="position:absolute;top:1px;right:1px;" title="Cerrar esta ventana">
<table border="1" bordercolor="#e5e5e5" style='font : normal normal small-caps 13px Tahoma;' align="center"> 
	<tr>
    	<td><input type="radio" name="Cup" onClick="Nuevo('Cup')">CUPS</td>
    </tr>
    <tr>
    	<td><input type="radio" name="Medicamentos" onClick="Nuevo('Medicamento')">Medicamentos</td>
    </tr>
</table>
</form>
</body>
</html>
