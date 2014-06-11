<title>Buscar CUP</title>
<body background="/Imgs/Fondo.jpg">
<form name="FORMA">
<table border="1" bordercolor="#ffffff" style='font : normal normal small-caps 13px Tahoma;'>
<tr bgcolor="#e5e5e5" style="font-weight:bold;text-align:center"><td>Codigo</td><td>Procedimiento</td></tr>
<tr>
<td><input type="Text" name="Codigo" style="width:50px;" onKeyUp="frames.ListaCUPS.location.href='ListaCUPS.php?Codigo='+this.value"></td>
<td><input type="Text" name="Procedimiento" style="width:500px;" onKeyUp="frames.ListaCUPS.location.href='ListaCUPS.php?Procedimiento='+this.value"></td></tr>
<tr><td colspan="2">
<iframe name="ListaCUPS" id="ListaCUPS" src="ListaCUPS.php" name="ListaCUPS" style="height:240px;width:550px;" frameborder="0">

</iframe>
</td></tr>
<tr>
<td><input type="Text" name="CodSeleccionado" style="width:50px;border:0px;background:#e5e5e5;font : 13px Tahoma;"></td>
<td><input type="Text" name="ProcedSeleccionado" style="width:500px;border:0px;background:#e5e5e5;font : 13px Tahoma;"></td>

</tr>
</table>
<center><br>
<input type="Button" value="Volver" onClick="if(CodSeleccionado.value!=''){opener.document.FORMA.<?echo $ControlOrigen?>.value=CodSeleccionado.value;window.close();}else{alert('Seleccione Procedimiento');}">
</form>
</body>
</html>
