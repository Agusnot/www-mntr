<?php
	if($DatNameSID){session_name("$DatNameSID");}
	session_start();
	include("Funciones.php");
	$ND=getdate();
	if($Eliminar){
		$cons="Delete from salud.valormulta where compania='$Compania[0]' and tipoasegurador='$TipoAsegurador'";
		$res=ExQuery($cons);
	}
	
	$cons="select tipoasegurador,valor from salud.valormulta where compania='$Compania[0]'";
	$res=ExQuery($cons);
?>	
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
</head>

<body background="/Imgs/Fondo.jpg">
<form name="FORMA" method="post" onSubmit="return validar()">  
<table BORDER=1  style='font : normal normal small-caps 12px Tahoma;' border="1" bordercolor="#e5e5e5" cellpadding="4">     
	<tr bgcolor="#e5e5e5" style="font-weight:bold" align="center"><td >Tipo Asegurador</td><td>Valor Multa</td><td colspan="2"></td></tr>
<?
	while($fila=ExFetch($res))
	{?>
		<tr>
        	<td><? echo $fila[0]?></td><td><? echo $fila[1]?></td>
            <td>
            	<img title="Editar" src="/Imgs/b_edit.png" style="cursor:hand" 
                onClick="location.href='NewConfValorMulta.php?DatNameSID=<? echo $DatNameSID?>&Edit=1&TipoAsegurador=<? echo $fila[0]?>&ValorMulta=<? echo $fila[1]?>'"></td>
            <td>
            	<img title="Eliminar" style="cursor:hand" 
            	onClick="if(confirm('Desea eliminar este registro?')){location.href='ConfValorMulta.php?DatNameSID=<? echo $DatNameSID?>&Eliminar=1&TipoAsegurador=<? echo $fila[0]?>';}" src="/Imgs/b_drop.png">	
        	</td>
      	</tr>        
<?	}
?>    
	<tr>
    	<td colspan="4" align="center">
        	<input type="button"  value="Nuevo" onClick="location.href='NewConfValorMulta.php?DatNameSID=<? echo $DatNameSID?>'">
        </td>
    </tr>     
</table> 
<input type="hidden" value="DatNameSID" name="<? echo $DatNameSID?>">
</form>
</body>
</html>
