<?
	if($DatNameSID){session_name("$DatNameSID");}
	session_start();
	include("Funciones.php");
	
	if($IdElim){
		$cons="update central.correos set estado='AN' where compania='$Compania[0]' and id=$IdElim";
		$res=ExQuery($cons);
	}
	if($IdElimEnv){
		$cons="update central.correos set estadoenv='AN' where compania='$Compania[0]' and id=$IdElimEnv";
		$res=ExQuery($cons);
	}
	
	$cons="select count(id) from central.correos where compania='$Compania[0]' and usurecive='$usuario[1]' and estado='AC' and fechalee is null group by usurecive";
	$res=ExQuery($cons);
	$fila=ExFetch($res); 
	$SinLeer=$fila[0]; if(!$SinLeer){$SinLeer="0";}
	$cons="select count(id) from central.correos where compania='$Compania[0]' and usurecive='$usuario[1]' and estado='AC' and fechalee is not null group by usurecive";
	$res=ExQuery($cons);
	$fila=ExFetch($res);
	$Leidos=$fila[0]; if(!$Leidos){$Leidos="0";}
	$cons="select count(id) from central.correos where compania='$Compania[0]' and usucrea='$usuario[1]' and estadoenv='AC' ";
	$res=ExQuery($cons); 
	$fila=ExFetch($res);
	$Enviados=$fila[0]; if(!$Enviados){$Enviados="0";}
	if(!$Tipo&&$Ver){$Tipo=$Ver;}
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
</head>

<body background="/Imgs/Fondo.jpg">
<form name="FORMA" method="post" onSubmit="return Validar()">
<table BORDER=1  border="1" bordercolor="#e5e5e5" cellpadding="4" align="center" style='font : normal normal small-caps 12px Tahoma;'>	
	<tr align="center" bgcolor="#e5e5e5" style="font-weight:bold">
    	<td colspan="4">Bandeja de entrada</td>        
	</tr>
    <tr align="center">
    	<td><strong>Correos sin leer: </strong><? echo $SinLeer?></td><td><strong>Correos Leidos: </strong><? echo $Leidos?></td>
        <td><strong>Correos Enviados: </strong><? echo $Enviados?></td>
        <td><strong>Ver </strong>
        	<select name="Tipo" onChange="document.FORMA.submit()">
            	<option value="Todos" <? if($Tipo=="Todos"){?> selected<? }?>>Bandeja Entrada</option>
                <option value="Leidos" <? if($Tipo=="Leidos"){?> selected<? }?>>Leidos</option>
                <option value="Sin Leer" <? if($Tipo=="Sin Leer"){?> selected<? }?>>Sin Leer</option>
                <option value="Enviados" <? if($Tipo=="Enviados"){?> selected<? }?>>Enviados</option>
            </select>
        </td>
 	</tr>
    <tr>
        <td colspan="4" align="center"><input type="button" value="Nuevo" onClick="location.href='NewCorreo.php?DatNameSID=<? echo $DatNameSID?>'"></td>
    </tr>
</table>
</form>    
</body>
<iframe frameborder="0" id="Busquedascups" src="VerCorreos.php?DatNameSID=<? echo $DatNameSID?>&Ver=<? echo $Tipo?>" width="100%" height="85%"></iframe>
</html>