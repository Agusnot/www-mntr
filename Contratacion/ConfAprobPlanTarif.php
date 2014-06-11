<?
	if($DatNameSID){session_name("$DatNameSID");}
	session_start();
	include("Funciones.php");
?>	
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
</head>

<body background="/Imgs/Fondo.jpg">
<form name="FORMA" method="post">
<table BORDER=1  style='font : normal normal small-caps 12px Tahoma;' border="1" bordercolor="#e5e5e5" cellpadding="4" align="center">   
<?	$cons="Select Tarifario,nombre,fechaaprobado from Consumo.TarifariosVenta,central.usuarios
	where Compania='$Compania[0]' and usuario=usuaprobado and usuaprobado is not null and fechaaprobado is not null order by Tarifario";
	$res=ExQuery($cons);
	if(ExNumRows($res)>0){
		$ban=1;?>
        <TR bgcolor="#e5e5e5" style="font-weight:bold" align="center"><td colspan="4">Medicamentos</td></TR>	
        <TR bgcolor="#e5e5e5" style="font-weight:bold">
			<TD>Plan</TD><td>Usuario Aprobador</td><td>Fecha de Aprobacion</td>
		</TR>	    
   	<?	while($fila=ExFetch($res)){?>
    		<tr>
            	<td><? echo $fila[0]?></td><td><? echo $fila[1]?></td><td><? echo $fila[2]?></td>
            </tr>
	<?	}?>
<?	}
	$cons="Select nombreplan,nombre,fechaaprobado from Contratacionsalud.Planestarifas,central.usuarios
	where Compania='$Compania[0]' and usuario=usuaprobado and usuaprobado is not null and fechaaprobado is not null 
	order by nombreplan";
	$res=ExQuery($cons);
	if(ExNumRows($res)>0){			
		$ban=1;?>
	    <TR bgcolor="#e5e5e5" style="font-weight:bold" align="center"><td colspan="4">Procedimientos</td></TR>		
        <TR bgcolor="#e5e5e5" style="font-weight:bold">
				<TD>Plan</TD><td>Usuario Aprobador</td><td>Fecha de Aprobacion</td>
			</TR>
   	<?	while($fila=ExFetch($res)){?>
    		<tr>
            	<td><? echo $fila[0]?></td><td><? echo $fila[1]?></td><td><? echo $fila[2]?></td>
            </tr>
	<?	}?>
<?	}	
	if($ban!=1){?>
		<TR bgcolor="#e5e5e5" style="font-weight:bold" align="center"><td colspan="4">No Se Han Aprobado Planes Tarifarios</td></TR>	
<?	}?>
	<tr align="center">
    	<td colspan="4"><input type="button" value="Aprobar Plan" onClick="location.href='NewAprobPlanTarif.php?DatNameSID=<? echo $DatNameSID?>'"></td>
    </tr>
</table>
<input type="hidden" value="DatNameSID" name="<? echo $DatNameSID?>">
</form>    
</body>
</html>
