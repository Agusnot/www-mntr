<?
	if($DatNameSID){session_name("$DatNameSID");}
	session_start();
	include("Funciones.php");
?>
<htm>
<title>Buscar Medicamentos</title>
<head>
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
	}
	
	function Asignar()
	{
		if(document.FORMA.CodSeleccionado.value==""||document.FORMA.CUPSeleccionado.value==""){
			alert("Debe seleccionar un CUP!!");
		}
		else{
			if(document.FORMA.Indicacion.value==""){
				alert("Debe digitar la Indicacion!!!");
			}
			else{
				var Msj=document.FORMA.CodSeleccionado.value+' - '+document.FORMA.CUPSeleccionado.value+', '+document.FORMA.Indicacion.value;					
				if(parent.document.getElementById('<? echo $NomCampo?>').value==""){
					parent.document.getElementById('<? echo $NomCampo?>').value=Msj;
				}
				else
				{
					parent.document.getElementById('<? echo $NomCampo?>').value=parent.document.getElementById('<? echo $NomCampo?>').value+'\n\r'+Msj;
				}
				CerrarThis();			
			}
		}
	}
</script>
</head>
<body background="/Imgs/Fondo.jpg">
<form name="FORMA">
<table border="1" bordercolor="#ffffff" style='font : normal normal small-caps 13px Tahoma;'>
<tr align="right">
	<td colspan="3">
    	<input type="button" value=" X " onClick="CerrarThis()" title="Cerrar esta ventana">
    </td>
</tr>
<tr bgcolor="#e5e5e5" style="font-weight:bold;text-align:center"><td>Codigo</td><td colspan="3">Nombre</td></tr>
<tr>
<td><input type="Text" name="Codigo" style="width:50px;" onKeyUp="xLetra(this);frames.ListaCUPS.location.href='ListaCUPS.php?DatNameSID=<? echo $DatNameSID?>&Codigo='+this.value+'&Nombre='+Nombre.value" onKeyDown="xLetra(this)" onKeyPress="xLetra(this)"></td>
<td colspan="3">
<input type="Text" name="Nombre" style="width:500px;" onKeyUp="xLetra(this);frames.ListaCUPS.location.href='ListaCUPS.php?DatNameSID=<? echo $DatNameSID?>&Nombre='+this.value+'&Codigo='+Codigo.value"
onKeyDown="xLetra(this)" onKeyPress="xLetra(this)"></td></tr>
<tr><td colspan="3">
<iframe name="ListaCUPS" id="ListaCUPS" src="ListaCUPS.php" name="ListaCUPS" style="height:180px;width:550px;" frameborder="0">

</iframe>
</td></tr>
<tr><td>Cod</td><td>Medicamento</td><td>Indicacion</td></tr>
<tr>
<td><input type="Text" name="CodSeleccionado" style="width:50px;border:0px;background:#e5e5e5;font : 13px Tahoma;" readonly></td>
<td><input type="Text" name="CUPSeleccionado" style="width:300px;border:0px;background:#e5e5e5;font : 13px Tahoma;" readonly></td>
<td><input type="Text" name="Indicacion" onKeyDown="xLetra(this)" onKeyPress="xLetra(this)" onKeyUp="xLetra(this)"> 
</td>

</tr>
</table>
<center><br>
<input type="Button" value="Asignar CUP" onClick="Asignar()">
</form>
</body>
</html>