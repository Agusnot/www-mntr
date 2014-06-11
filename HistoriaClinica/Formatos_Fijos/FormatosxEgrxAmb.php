<?php
	if($DatNameSID){session_name("$DatNameSID");}
	session_start();
	include("Funciones.php");
	$ND=getdate();
	if($Eliminar)
	{
		$cons="delete from salud.formatosegreso where compania='$Compania[0]' and ambito='$Ambito' and tipoformato='$TipoFormato' and formato='$Formato'";
		//echo $cons;
		$res=ExQuery($cons);
	}
?>	
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
</head>

<body background="/Imgs/Fondo.jpg">
<form name="FORMA" method="post" onSubmit="return validar()">  
<table BORDER=1  style='font : normal normal small-caps 12px Tahoma;' border="1" bordercolor="#e5e5e5" cellpadding="2" align="center"> 
	<tr><td colspan="4" align="center"  bgcolor="#e5e5e5" style="font-weight:bold">FORMATOS EGRESO X PROCESO</td></tr>
<?	$cons="select ambito from salud.ambitos where compania='$Compania[0]' and ambito!='Sin Ambito' 
	and ambito in (select ambito from salud.formatosegreso where compania='$Compania[0]') order by ambito";
	$res=ExQuery($cons);
	while($fila=ExFetch($res))
	{?>
    	<tr align="center"  bgcolor="#e5e5e5" style="font-weight:bold">
    		<td colspan="4"><? echo $fila[0]?></td>
    	</tr>
        <tr align="center"  bgcolor="#e5e5e5" style="font-weight:bold">
        	<td>Tipo de Formato</td><td>Formato</td><td colspan="2"></td>
        </tr>
<?		$cons2="select tipoformato,formato from salud.formatosegreso where compania='$Compania[0]' and ambito='$fila[0]' order by tipoformato,formato";
		$res2=ExQuery($cons2);
		while($fila2=ExFetch($res2))
		{?>
			<tr>
            	<td><? echo $fila2[0]?></td><td><? echo $fila2[1]?></td>                
                <td>
                	<img src="/Imgs/b_drop.png" title="Eliminar" style="cursor:hand" 
                    onClick="if(confirm('¿Esta seguro de eliminar este registro?')){location.href='FormatosxEgrxAmb.php?DatNameSID=<? echo $DatNameSID?>&Eliminar=1&TipoFormato=<? echo $fila2[0]?>&Formato=<? echo $fila2[1]?>&Ambito=<? echo $fila[0]?>';}">
                </td>
            </tr>
	<?	}
	}?> 
    <tr align="center">
    	<td colspan="4">
        	<input type="button" value="Nuevo" onClick="location.href='NewFormatoxEgrxAmb.php?DatNameSID=<? echo $DatNameSID?>';">
     	</td>
	</tr>       
</table>
</form>
</body>
</html>