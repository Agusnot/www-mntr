<?
	if($DatNameSID){session_name("$DatNameSID");}
	session_start();
	include("Funciones.php");
	$ND=getdate();
	if($UserGlosa){
		$cons="delete from facturacion.firmasrtaglosas where compania='$Compania[0]' and usuario='$UserGlosa'";
		$res=ExQuery($cons);
	}
	$cons="select usuario,nombre,cedula from facturacion.firmasrtaglosas where compania='$Compania[0]'";
	$res=ExQuery($cons);
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
</head>

<body background="/Imgs/Fondo.jpg">
<form name="FORMA" method="post" onSubmit="return Validar()">
<table   style='font : normal normal small-caps 12px Tahoma;' border="1" bordercolor="#e5e5e5" cellpadding="2" > 
<?
if(ExNumRows($res)){?>
	<tr align="center"  bgcolor="#e5e5e5" style="font-weight:bold"><td>Usuario</td><td>Nombre</td><td>Identificacion</td><td colspan="2">
    
    </tr>
<?	while($fila=ExFetch($res)){?>
		<tr onMouseOver="this.bgColor='#AAD4FF'" onMouseOut="this.bgColor=''"> 
        	<td><? echo $fila[0]?></td>
            <td><? echo $fila[1]?></td>
            <td><? echo $fila[2]?></td>
            <td>
            	
           	</td>
            <td>
				<img title="Eliminar" style="cursor:hand" 
                onClick="if(confirm('Desea eliminar este registro?')){location.href='AdminUsuarios.php?DatNameSID=<? echo $DatNameSID?>&UserGlosa=<? echo $fila[0]?>';}" src="/Imgs/b_drop.png">
          	</td>
        </tr>
<?	} 
}
else{?>
	<tr><td colspan="3" align="center"  bgcolor="#e5e5e5" style="font-weight:bold">No se han registrado clases de glosas</td></tr><?
}?>
	<tr align="center"><td colspan="3"><input type="button" value="Nuevo" onClick="location.href='NewUsuarioGlosa.php?DatNameSID=<? echo $DatNameSID?>'" /></td></tr>
</table>
<input type="hidden" name="DatNameSID" value="<? echo $DatNameSID?>">
</form>
</body>
</html>
