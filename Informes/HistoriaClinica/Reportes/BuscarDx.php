<?
	if($DatNameSID){session_name("$DatNameSID");}
	session_start();
	include("Funciones.php");
?>
<title>Buscar Diagnóstico</title>
<body background="/Imgs/Fondo.jpg">
<form name="FORMA">
<table border="1" bordercolor="#ffffff" style='font : normal normal small-caps 13px Tahoma;'>
<tr bgcolor="#e5e5e5" style="font-weight:bold;text-align:center"><td>Codigo</td><td>Diagnostico</td></tr>
<tr>
<td><input type="Text" name="Codigo" style="width:50px;" onKeyUp="frames.ListaCIE.location.href='ListaCIE.php?Codigo='+this.value"></td>
<td><input type="Text" name="Diagnostico" style="width:500px;" onKeyUp="frames.ListaCIE.location.href='ListaCIE.php?Diagnostico='+this.value"></td></tr>
<tr><td colspan="2">
<iframe name="ListaCIE" id="ListaCIE" src="ListaCIE.php" name="ListaDx" style="height:240px;width:550px;" frameborder="0">

</iframe>
</td></tr>
<tr>
<td><input type="Text" name="CodSeleccionado" style="width:50px;border:0px;background:#e5e5e5;font : 13px Tahoma;"></td>
<td><input type="Text" name="DxSeleccionado" style="width:500px;border:0px;background:#e5e5e5;font : 13px Tahoma;"></td>

</tr>
</table>
<center><br>
<input type="Button" value="Regresar" onClick="if(DxSeleccionado.value!=''){opener.document.FORMA.<?echo $ControlOrigen?>.value=CodSeleccionado.value;window.close();}else{alert('Seleccione Diagnostico');}">
</form>
</body>
</html>
