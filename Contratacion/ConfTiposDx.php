<?	if($DatNameSID){session_name("$DatNameSID");}
	session_start();
	include("Funciones.php");
	if($Eliminar){
		$cons="Delete from salud.tiposdiagnostico where compania='$Compania[0]' and codigo='$Codigo'";
		$res=ExQuery($cons);echo ExError();
	}
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
</head>

<body background="/Imgs/Fondo.jpg">
<form name="FORMA" method="post" onSubmit="return validar()">
<table BORDER=1  style='font : normal normal small-caps 12px Tahoma;' border="1" bordercolor="#e5e5e5" cellpadding="4"> 
	<tr><td colspan="4" align="center"  bgcolor="#e5e5e5" style="font-weight:bold">Tipos Dignostico</td></tr>
<? 	$cons="select codigo,tipodiagnost from salud.tiposdiagnostico where compania='$Compania[0]'";
	$res=ExQuery($cons);echo ExError();
	if(ExNumRows($res)>0){?>
		<tr  align="center"  bgcolor="#e5e5e5" style="font-weight:bold"><td>Codigo</td><td align="center">Nombre</td><td colspan="2"></td></tr>
<?		while($fila = ExFetch($res)){
			echo "<tr><td>$fila[0]</td><td>$fila[1]</td><td>";?>
            <img title="editar" src="/Imgs/b_edit.png" style="cursor:hand" onClick="location.href='NewConfTiposDx.php?DatNameSID=<? echo $DatNameSID?>&Codigo=<? echo $fila[0]?>&Nombre=<? echo $fila[1]?>&Edit=1'"></td><td>
			<img title="Eliminar" style="cursor:hand" onClick="if(confirm('Desea eliminar este registro?')){location.href='ConfTiposDx.php?DatNameSID=<? echo $DatNameSID?>&Codigo=<? echo $fila[0]?>&Eliminar=1';}" src="/Imgs/b_drop.png"></td></tr>
	<?	}
	}?> 
    <tr><td align="center" colspan="4"><input type="button" value="Nuevo" onClick="location.href='NewConfTiposDx.php?DatNameSID=<? echo $DatNameSID?>'"></td></tr>       
</table>
<input type="hidden" value="DatNameSID" name="<? echo $DatNameSID?>">
</form>    
</body>
</html>
