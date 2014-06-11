<?
	if($DatNameSID){session_name("$DatNameSID");}
	session_start();
	include("Funciones.php");	
?>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Convenciones Odontograma</title>
</head>
<body background="/Imgs/Fondo.jpg">
<table border="1" bordercolor="#e5e5e5" style='font : normal normal small-caps 13px Tahoma;' align="center">
<tr bgcolor="#e5e5e5" style="font-weight:bold"><td colspan="5" align="center">Convencion de Colores Odontograma</td></tr>
<tr bgcolor="#e5e5e5" style="font-weight:bold"><td colspan="2">Color</td><td>Descripcion</td></tr>
<? 
$cons="Select color,ncolor,ruta,descripcion from odontologia.colorconvenciones where compania='$Compania[0]' order by ncolor";
$res=ExQuery($cons);
while($fila=ExFetch($res))
{	
?>
<tr onMouseOver="this.bgColor='#AAD4FF'" onMouseOut="this.bgColor=''">
<td><img src="<? echo $fila[2]?>" style="border:thin" height="16" width="16"></td>
<td><? echo $fila[1]?></td>
<td><? echo $fila[3]?></td>
</tr>
<?	
}
?>
</table>
<br />
<table bordercolor="#e5e5e5" border="1" style='font : normal normal small-caps 12px Tahoma;' align="center">
	<tr bgcolor="#e5e5e5" style="font-weight:bold"><td colspan="3" align="center">Convencion de Imagenes Odontograma</td></tr>
	<tr align="center"  bgcolor="#e5e5e5" style="font-weight:bold">
    	<td>Nombre</td><td>Imagen</td><td>Cup</td>
	</tr>
<?	
	$cons="select procedimientosimgs.codigo,procedimientosimgs.nombre,ruta,cups.nombre from odontologia.procedimientosimgs,contratacionsalud.cups
	where procedimientosimgs.compania='$Compania[0]' and cups.Compania='$Compania[0]' and cups.codigo=procedimientosimgs.cup 
	and (estadoimg is null or estadoimg='Activo')
	order by Procedimientosimgs.Nombre";
	$res=ExQuery($cons);
	while($fila=ExFetch($res))
	{?>
		<tr onMouseOver="this.bgColor='#AAD4FF'" onMouseOut="this.bgColor=''" >
        	<td><? echo $fila[1]?></td>
            <td align="center" title="Ver"
	            style="cursor:hand"><img src="<? echo $fila[2]?>" height="30px" width="30px">
            </td>
                        
            <td title="Ver" style="cursor:hand" ><? echo $fila[3]?>            
        </tr>		
<?	}?>
</table>   
</body>