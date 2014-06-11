<?php
	if($DatNameSID){session_name("$DatNameSID");}
	session_start();
	include("Funciones.php");
	$ND=getdate();
	if($Eliminar)
	{
		$cons="delete from salud.alertasingreso where compania='$Compania[0]' and fechaini='$FechaIni' and alerta='$Alerta'";
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
<?	if($ND[mon]<10){$cero='0';}else{$cero='';}
	if($ND[mday]<10){$cero1='0';}else{$cero1='';}
	$FechaCompActua="$ND[year]-$cero$ND[mon]-$cero1$ND[mday]";
	if($Paciente[48]!=$FechaCompActua){echo "<em><center><br><br><br><br><br><font size=5 color='BLUE'>La Hoja de Identificacion no se ha guardado!!!";exit;}	
if($Paciente[1]){?>
    <table bordercolor="#e5e5e5" border="1" style='font : normal normal small-caps 12px Tahoma;' align="center" cellpadding="4">
    <?
    $cons="select alertasingreso.alerta,alertasingreso.fechaini,alertasingreso.fechafin from salud.alertasingreso 
	where alertasingreso.compania='$Compania[0]' and alertasingreso.cedula='$Paciente[1]'  order by fecha";
    
    $res=ExQuery($cons);
    if(ExNumRows($res)>0)
    {?>
        <tr align="center"  bgcolor="#e5e5e5" style=" font-weight:bold"><td>Alerta</td><td>Fecha Inicio</td><!--<td>Fecha Fin</td>--><td colspan="2"></td></tr>	
    <? 	while($fila=ExFetch($res)){
            $NumServ=$fila[3]?>
            <tr>
                <td><? echo $fila[0]?></td><td><? echo $fila[1]?></td><!--<td><? echo $fila[2]?></td>--><td>
                <img title="Editar" src="/Imgs/b_edit.png" style="cursor:hand" 
                onClick="location.href='NewAlerta.php?DatNameSID=<? echo $DatNameSID?>&Edit=1&Alerta=<? echo $fila[0]?>&FechaIni=<? echo $fila[1]?>&FechaFin=<? echo $fila[2]?>&NumServ=<? echo $fila[3]?>'"></td><td>
                <img title="Eliminar" style="cursor:hand" 
                onClick="if(confirm('Desea eliminar este registro?')){location.href='Alertas.php?DatNameSID=<? echo $DatNameSID?>&Eliminar=1&Alerta=<? echo $fila[0]?>&FechaIni=<? echo $fila[1]?>&FechaFin=<? echo $fila[2]?>&NumServ=<? echo $fila[3]?>';}" 
                src="/Imgs/b_drop.png">
                </td>
            </tr>
    <?	}   
    }
    else
    {?>
        <tr><td  align="center"  bgcolor="#e5e5e5" colspan="5">No se Han Registrado Alertas</td></tr>
    <?    
    }?>
    <tr><td colspan="5" align="center"><input type="button" value="Nuevo" onClick="location.href='NewAlerta.php?DatNameSID=<? echo $DatNameSID?>&NumServ=<? echo $NumServ?>'"></td></tr>
    </table><?
}
else{
	echo "<center><font face='Tahoma' color='#0066FF' size='+2' ><b>No hay un paciente seleccionado!!! </b></font></center>";
}?>    
<input type="hidden" name="DatNameSID" value="<? echo $DatNameSID?>">
</form>
</html>
