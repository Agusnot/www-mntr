<?php
	if($DatNameSID){session_name("$DatNameSID");}
	session_start();
	include("Funciones.php");	
	if($Eliminar)
	{
		$cons="delete from odontologia.procedimientosimgs where compania='$Compania[0]' and codigo='$Codigo'";		
		$res=ExQuery($cons);	
		if(!ExError($res))
		{		
			if(is_file($_SERVER['DOCUMENT_ROOT'].$Ruta))
			{
				unlink($_SERVER['DOCUMENT_ROOT'].$Ruta);	
			}		
		}
	}
	$cons="select procedimientosimgs.codigo,procedimientosimgs.nombre,ruta,cups.nombre,estadoimg from odontologia.procedimientosimgs,contratacionsalud.cups
	where procedimientosimgs.compania='$Compania[0]' and cups.Compania='$Compania[0]' and cups.codigo=procedimientosimgs.cup 
	order by Procedimientosimgs.Nombre";
	$res=ExQuery($cons);
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
</head>
<body background="/Imgs/Fondo.jpg">
<form name="FORMA" method="post" enctype="multipart/form-data">
<input type="hidden" name="DatNameSID" value="<? echo $DatNameSID?>"/>
<table bordercolor="#e5e5e5" border="1" style='font : normal normal small-caps 12px Tahoma;'>
	<tr align="center"  bgcolor="#e5e5e5" style="font-weight:bold">
    	<td>Nombre</td><td>Imagen</td><td>Ruta</td><td>Cup</td><td>Estado</td><td colspan="2"></td>
	</tr>
<?	while($fila=ExFetch($res))
	{
		if(empty($fila[4])){$fila[4]="Activo";}?>
		<tr onMouseOver="this.bgColor='#AAD4FF'" onMouseOut="this.bgColor=''" >
        	<td><? echo $fila[1]?></td>
            <td align="center" title="Ver"
	            style="cursor:hand" onClick="location.href='VerImgOdontologia.php?DatNameSID=<? echo $DatNameSID?>&Codigo=<? echo $fila[0]?>'"><img src="<? echo $fila[2]?>" height="30px" width="30px">
            </td>
            <td><? echo $fila[2]?></td>            
            <td ><? echo $fila[3]?>
            </td>
            <td ><? echo $fila[4]?>
            </td>
          	<td><img title="Editar" src="/Imgs/b_edit.png" style="cursor:hand" 
                onClick="location.href='NewImgOdontologia.php?DatNameSID=<? echo $DatNameSID?>&Edit=1&Codigo=<? echo $fila[0]?>&Nombre=<? echo $fila[1]?>&Ruta=<? echo $fila[2]?>'">
          	</td>
            <td><img title="Eliminar" style="cursor:hand" src="/Imgs/b_drop.png"
                onClick="if(confirm('Desea eliminar este registro?')){location.href='ImgsOdontologia.php?DatNameSID=<? echo $DatNameSID?>&Eliminar=1&Codigo=<? echo $fila[0]?>&Ruta=<? echo $fila[2]?>';}">
            </td>
        </tr>		
<?	}?>
</table>    
<input type="button" value="Nuevo" onClick="location.href='NewImgOdontologia.php?DatNameSID=<? echo $DatNameSID?>'" />
</form>
</body>
</html>


