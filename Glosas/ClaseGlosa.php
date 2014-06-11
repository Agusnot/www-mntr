<?
	if($DatNameSID){session_name("$DatNameSID");}
	session_start();
	include("Funciones.php");
	$ND=getdate();
	if($ClaseG){
		$cons="delete from facturacion.clasesglosa where compania='$Compania[0]' and claseglosa='$ClaseG'";
		$res=ExQuery($cons);
	}
	$cons="select claseglosa from facturacion.clasesglosa where compania='$Compania[0]'";
	$res=ExQuery($cons);
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
</head>

<body background="/Imgs/Fondo.jpg">
<form name="FORMA" method="post" onSubmit="return Validar()">
<table BORDER=1  style='font : normal normal small-caps 12px Tahoma;' border="1" bordercolor="#e5e5e5" cellpadding="2" > 
<?
if(ExNumRows($res)){?>
	<tr align="center"  bgcolor="#e5e5e5" style="font-weight:bold"><td>Nombre</td><td colspan="2"></tr>
<?	while($fila=ExFetch($res)){?>
		<tr onMouseOver="this.bgColor='#AAD4FF'" onMouseOut="this.bgColor=''"> 
        	<td><? echo $fila[0]?></td>
            <td>
            	<img title="Editar" src="/Imgs/b_edit.png" style="cursor:hand" onClick="location.href='NewClaseGlosa.php?DatNameSID=<? echo $DatNameSID?>&ClaseG=<? echo $fila[0]?>&Edit=1'">
           	</td>
            <td>
				<img title="Eliminar" style="cursor:hand" 
                onClick="if(confirm('Desea eliminar este registro?')){location.href='ClaseGlosa.php?DatNameSID=<? echo $DatNameSID?>&ClaseG=<? echo $fila[0]?>';}" src="/Imgs/b_drop.png">
          	</td>
        </tr>
<?	} 
}
else{?>
	<tr><td colspan="3" align="center"  bgcolor="#e5e5e5" style="font-weight:bold">No se han registrado clases de glosas</td></tr><?
}?>
	<tr align="center"><td colspan="3"><input type="button" value="Nuevo" onClick="location.href='NewClaseGlosa.php?DatNameSID=<? echo $DatNameSID?>'" /></td></tr>
</table>
<input type="hidden" name="DatNameSID" value="<? echo $DatNameSID?>">
</form>
</body>
</html>
