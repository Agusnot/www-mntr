<?
	if($DatNameSID){session_name("$DatNameSID");}
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
	if($Codigo || $Procedimiento){
	if($Codigo){$cons="Select nombre,codigo from ContratacionSalud.CUPS where codigo like '$Codigo%'";}
	if($Procedimiento){$cons="Select nombre,codigo from ContratacionSalud.CUPS where Nombre ilike '%$Procedimiento%'";}
	$res=ExQuery($cons);

	while($fila=ExFetch($res))
	{?>
		<tr><td><a href="#" onClick="parent.document.FORMA.CodSeleccionado.value='<?echo $fila[1]?>';parent.document.FORMA.ProcedSeleccionado.value='<?echo $fila[0]?>'"><?echo $fila[1]?></a></td>
		<td><a href="#" onclick="parent.document.FORMA.CodSeleccionado.value='<?echo $fila[1]?>';parent.document.FORMA.ProcedSeleccionado.value='<?echo $fila[0]?>'"><?echo $fila[0]?></td></tr>
<?	}}
?>
</table>
</body>
