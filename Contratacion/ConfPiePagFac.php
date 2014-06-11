<?	
    if($DatNameSID){session_name("$DatNameSID");}
	session_start();
	include("Funciones.php");
	$cons="select codigo,nota from facturacion.notaspiepag where compania='$Compania[0]' order by codigo";
	$res=ExQuery($cons);
?>	
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
</head>

<body background="/Imgs/Fondo.jpg">
<form name="FORMA" method="post" onSubmit="return Validar()">

<table  BORDER=1  style='font : normal normal small-caps 12px Tahoma;' border="1" bordercolor="#e5e5e5" cellpadding="4" align="center"> 
	<tr>
    	<td colspan="4" bgcolor="#e5e5e5" style="font-weight:bold" align="center">NOTAS PIE DE PAGINA FACTURAS</td>
    </tr>
    <tr bgcolor="#e5e5e5" style="font-weight:bold" align="center">
    	<td>Codigo</td><td>Nombre</td><td colspan="2"></td>
    </tr>
<?	while($fila=ExFetch($res)){?>
		<tr onMouseOver="this.bgColor='#AAD4FF'" onMouseOut="this.bgColor=''">
        	<td align="center"><? echo $fila[0]?></td><td><? echo $fila[1]?></td>
            <td><img src="/Imgs/b_edit.png" style="cursor:hand" title="Editar"
            	onclick="location.href='NewConfPiePagFac.php?DatNameSID=<? echo $DatNameSID?>&Edit=1&Codigo=<? echo $fila[0]?>&Nota=<? echo $fila[1]?>'"/>
           	</td>
            <td>
            	<img src="/Imgs/b_drop.png" style="cursor:hand" title="Eliminar" 
                onclick="location.href='ConfPiePagFac.php?DatNameSID=<? echo $DatNameSID?>&Eliminar=1&Codigo=<? echo $fila[0]?>'"/>
            </td>
        </tr>	
<?	}?>    
	<tr align="center">
    	<td colspan="4"><input type="button" value="Nuevo" onclick="location.href='NewConfPiePagFac.php?DatNameSID=<? echo $DatNameSID?>'"/></td>
    </tr>
</table>
</form>    
</body>
</html>
