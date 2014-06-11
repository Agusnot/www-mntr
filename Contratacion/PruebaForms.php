<?
	session_start();
	include("Funciones.php");
?>
<script language="javascript">
	function evitarSubmit(evento)
	{
		if(document.all){ tecla = evento.keyCode;}
		else{ tecla = evento.which;}
		return(tecla != 13);
	}
	function Pasar(evento,proxCampo)
	{
		if(evento.keyCode == 13){document.getElementById(proxCampo).focus();}
	}
</script>
<body>
<form name="FORMA" method="post">
	<table border="0">		
		<tr>
			<td>Campo1</td>
			<td><input type="text" name="Campo1" id="Campo1" onkeypress="return evitarSubmit(event)" 
				onkeyup="Pasar(event,'Campo2')" /></td>
		</tr>
		<tr>
			<td>Campo2</td>
			<td><input type="text" name="Campo2" id="Campo2" onkeypress="return evitarSubmit(event)" 
				onkeyup="Pasar(event,'Campo3')" /></td>
		</tr>
		<tr>
			<td>Campo3</td>
			<td><input type="text" name="Campo3" id="Campo3" onkeypress="return evitarSubmit(event)" 
				onkeyup="Pasar(event,'Campo4')" /></td>
		</tr>
		<tr>
			<td>Campo4</td>
			<td><input type="text" name="Campo4" id="Campo4" onkeypress="return evitarSubmit(event)" 
				onkeyup="Pasar(event,'Campo5')" /></td>
		</tr>
		<tr>
			<td>Campo5</td>
			<td><input type="text" name="Campo5" id="Campo5" onkeypress="return evitarSubmit(event)" 
				onkeyup="Pasar(event,'Cambiar')" /></td>
		</tr>
	</table>
<input type="submit" name="Cambiar" id="Cambiar" value="Cambiar" />
</form>
</body>