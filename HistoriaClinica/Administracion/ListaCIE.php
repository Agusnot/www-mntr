<?
	session_start();
	include("Funciones.php");
?>
<body background="/Imgs/Fondo.jpg">
<style>
a{color:black;text-decoration:none;}
a:hover{color:blue;text-decoration:underline;}
</style>
<table border="1" bordercolor="#ffffff" style='font : normal normal small-caps 13px Tahoma;'>
<?
	if($Codigo || $Diagnostico){
		if($Codigo){$cons="Select Diagnostico,Codigo from Salud.CIE where Codigo ilike '$Codigo%'";}
		if($Diagnostico){$cons="Select Diagnostico,Codigo from Salud.CIE where Diagnostico ilike '%$Diagnostico%'";}
		$res=ExQuery($cons);echo ExError();
		while($fila=ExFetch($res))
		{?>
			<tr><td><a href="#" onClick="parent.document.FORMA.CodSeleccionado.value='<?echo $fila[1]?>';parent.document.FORMA.DxSeleccionado.value='<?echo $fila[0]?>'"><?echo $fila[1]?></a></td>
			<td><a href="#" onclick="parent.document.FORMA.CodSeleccionado.value='<?echo $fila[1]?>';parent.document.FORMA.DxSeleccionado.value='<?echo $fila[0]?>'"><?echo $fila[0]?></td></tr>
	<?	}
	}?>
</table>
</body>
