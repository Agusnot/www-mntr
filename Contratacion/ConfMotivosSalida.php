<?php
	if($DatNameSID){session_name("$DatNameSID");}
	session_start();
	include("Funciones.php");
	
	if($Eliminar){
		$cons="delete from salud.motivosalida where compania='$Compania[0]' and estadosalida='$EstadoSalida' and motivosalida='$MotivoSalida'";
		//echo $cons;
		$res=ExQuery($cons);
	}
?>	
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
</head>

<body background="/Imgs/Fondo.jpg">
<form name="FORMA" method="post">
<table bordercolor="#e5e5e5" border="1" style='font : normal normal small-caps 12px Tahoma;' align="left" cellpadding="4">
	<tr>
    <?	if($EstadoSalida==''){$EstadoSalida='Vivo';}?>
    	<td align="center" bgcolor="#e5e5e5" style="font-weight:bold" colspan="4">Estado Salida</td>
  	</tr>
    <tr>      
        <td colspan="4" align="center"><select name="EstadoSalida" onChange="document.FORMA.submit()">
        <? 	if($EstadoSalida=='Vivo'){?>
        		<option value="Vivo" selected>Vivo</option>
		<?	}
			else{?>
				<option value="Vivo">Vivo</option>
		<?	}?>
		<? 	if($EstadoSalida=='Muerto'){?>
        		<option value="Muerto" selected>Muerto</option>
		<?	}
			else{?>
				<option value="Muerto">Muerto</option>
		<?	}?>
        </select></td>
    </tr>
    </tr>
<?	$cons="select motivosalida from salud.motivosalida where compania='$Compania[0]' and estadosalida='$EstadoSalida'";
	$res=ExQuery($cons);
	if(ExNumRows($res)>0)
	{	?>
		<tr align="center" bgcolor="#e5e5e5" style="font-weight:bold"><td>motivos salida</td><td colspan="2"></td><tr><?
		while($fila=ExFetch($res))
		{?>    	     
        	<tr><td><? echo $fila[0]?></td><td>
            <img title="Editar" src="/Imgs/b_edit.png" style="cursor:hand" onClick="location.href='NewConfMotivoSalida.php?DatNameSID=<? echo $DatNameSID?>&Edit=1&MotivoSalida=<? echo $fila[0]?>&EstadoSalida='+EstadoSalida.value"></td><td>
			<img title="Eliminar" style="cursor:hand" onClick="if(confirm('Desea eliminar este registro?')){location.href='ConfMotivosSalida.php?DatNameSID=<? echo $DatNameSID?>&Eliminar=1&MotivoSalida=<? echo $fila[0]?>&EstadoSalida='+EstadoSalida.value;}" src="/Imgs/b_drop.png"></td></tr>
<?		}
	}
	else
	{?>		
    	<tr align="center" bgcolor="#e5e5e5" style="font-weight:bold"><td colspan="2">No se han ingresado motivos de salida para este esatdo</td>  </tr>     
<?	}?>    
    <tr><td align="center" colspan="4"><input type="button" value="Nuevo" onClick="location.href='NewConfMotivoSalida.php?DatNameSID=<? echo $DatNameSID?>&EstadoSalida=<? echo $EstadoSalida?>'"></td></tr>
</table>
<input type="hidden" value="DatNameSID" name="<? echo $DatNameSID?>">
</form>    	    
</body>
</html>
