<?	if($DatNameSID){session_name("$DatNameSID");}
	session_start();
	include("Funciones.php");
	if($Eliminar){
		$cons="Delete from salud.interprogramas where compania='$Compania[0]' and interprograma='$Interprograma' and cargo='$Cargo'";
		$res=ExQuery($cons);
	}
?>	
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
</head>

<body background="/Imgs/Fondo.jpg">
<form name="FORMA" method="post">
<table BORDER=1  style='font : normal normal small-caps 12px Tahoma;' border="1" bordercolor="#e5e5e5" cellpadding="2"> 	
<? 	$cons="select interprograma,cargo from salud.interprogramas where compania='$Compania[0]' order by interprograma";
	$res=ExQuery($cons);
	if(ExNumRows($res)>0){?>
		<tr   align="center"  bgcolor="#e5e5e5" style="font-weight:bold"><td>Interconsulta</td><td>Cargo</td><td colspan="2"></td></tr>
<?		while($fila=ExFetch($res)){
			echo "<tr><td>$fila[0]</td><td>$fila[1]</td><td>";?>
			<img title="Editar" src="/Imgs/b_edit.png" style="cursor:hand" onClick="location.href='NewConfInterprogams.php?DatNameSID=<? echo $DatNameSID?>&Interprograma=<? echo $fila[0]?>&Edit=1&Cargo=<? echo $fila[1]?>'"></td><td>
			<img title="Eliminar" style="cursor:hand" onClick="if(confirm('Desea eliminar este registro?')){location.href='ConfInterprogramas.php?DatNameSID=<? echo $DatNameSID?>&Interprograma=<? echo $fila[0]?>&Eliminar=1&Cargo=<? echo $fila[1]?>';}" src="/Imgs/b_drop.png"></td>
			</tr><?
		}
	}
	else{?>
		<tr>
        	<td colspan="6" align="center"  bgcolor="#e5e5e5" style="font-weight:bold">Aun no se han creado Interconsulta</td>            
      	</tr>
<?	}?>
    <tr><td colspan="6" align="center"><input type="button" value="Nuevo" onClick="location.href='NewConfInterprogams.php?DatNameSID=<? echo $DatNameSID?>'"/></td>
    </tr>
</table>
<input type="hidden" value="DatNameSID" name="<? echo $DatNameSID?>">
</form>
</body>
</html>
