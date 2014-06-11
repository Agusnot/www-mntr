<?php
	if($DatNameSID){session_name("$DatNameSID");}
	session_start();
	include("Funciones.php");
	//echo "UnidadHosp=$UnidadHosp Ambito=$Ambito Id=$Idcama";
	if($Cedula!=''){
		$cons="update salud.pacientesxpabellones set idcama='$Idcama' where pabellon='$UnidadHosp'  and cedula='$Cedula' and ambito='$Ambito' and estado='AC' and compania='$Compania[0]'";
		//echo $cons;
		$res=ExQuery($cons);echo ExError();?>
        <script language="javascript">
			location.href='AsigCamas.php?DatNameSID=<? echo $DatNameSID?>&Ambito=<?echo $Ambito?>&UnidadHosp=<?echo $UnidadHosp?>&Regresa=1';
		</script>
<?	}
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
</head>

<body background="/Imgs/Fondo.jpg">
<form name="FORMA" method="post">
<table bordercolor="#e5e5e5" border="1" style='font : normal normal small-caps 12px Tahoma;'>
<? 	$cons="select cedula,primnom,segnom,primape,segape,idcama from central.terceros,salud.pacientesxpabellones  where identificacion=cedula and pabellon='$UnidadHosp' and ambito='$Ambito' and idcama!=0 and estado='AC' and terceros.compania='$Compania[0]' and pacientesxpabellones.compania='$Compania[0]' order by primape";
	$res=ExQuery($cons);echo ExError();
	if(ExNumRows($res)){?>
	<tr title='Asignar' align="center"  bgcolor="#e5e5e5" style="font-weight:bold"><td>Cedula</td><td>Nombre</td><td>Cama</td></tr>    
<?		while($row=ExFetch($res)){?>
			<tr title='Reasignar' style='cursor:hand'  onMouseOver="this.bgColor='#AAD4FF'" onMouseOut="this.bgColor=''" onClick="location.href='ReasigCamaPaciente.php?DatNameSID=<? echo $DatNameSID?>&Idcama=<? echo $Idcama?>&Ambito=<? echo $Ambito?>&UnidadHosp=<? echo $UnidadHosp?>&Cedula=<? echo $row[0]?>'"><td><? echo $row[0]?></td><td><? echo "$row[3] $row[4] $row[1] $row[2]"?></td><td><? echo $row[5]?></td></tr>	
	<?	}
	}
	else{?>
	<tr  align="center"  bgcolor="#e5e5e5" style="font-weight:bold"><td>No hay pacientes para asignar en esta unidad</td></tr>
<?	}?>
	<tr><td align="center" colspan="5"><input type="button" value="Cancelar" onClick="location.href='AsigCamas.php?DatNameSID=<? echo $DatNameSID?>&Ambito=<? echo $Ambito?>&UnidadHosp=<? echo $UnidadHosp?>&Regresa=1'"></td></tr>
</table>
<input type="Hidden" name="DatNameSID" value="<? echo $DatNameSID?>">
</form>
</body>
</html>
