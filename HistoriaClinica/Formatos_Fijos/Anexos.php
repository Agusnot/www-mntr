<?php
	if($DatNameSID){session_name("$DatNameSID");}
	session_start();
	include("Funciones.php");
	if($Eliminar)
	{
		$cons="delete from salud.anexos where compania='$Compania[0]' and cedula='$Paciente[1]' and nombre='$Nombre' and ruta='$Ruta'";
		$res=ExQuery($cons);
		unlink("/var/www/html/".$Ruta);
		//echo $cons;
	}	
	$RutaSistAnt="http://10.18.176.100:8080/salud/HistoriaClinica/AnexosMedicos.php?CedPaciente=$Paciente[1]";
?>	
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
</head>

<body background="/Imgs/Fondo.jpg">
<form name="FORMA" method="post"><?
if($Paciente[1]){?>
<table bordercolor="#e5e5e5" border="1" style='font : normal normal small-caps 12px Tahoma;' cellpadding="2">
	<tr><td colspan="4" align="center"><a style="color:blue" href="<? echo $RutaSistAnt?>">Ver Anexos Sistema Anterior</a></td></tr>
	<tr><td  align="center"  bgcolor="#e5e5e5" colspan="4" style="font-weight:bold">Anexos</td></tr> 
<?	$cons="select nombre,ruta from salud.anexos where compania='$Compania[0]' and cedula='$Paciente[1]'";
	$res=ExQuery($cons);
	if(ExNumRows($res)>0)
	{?> 		       
    	<tr align="center"  bgcolor="#e5e5e5" colspan="4" style="font-weight:bold"><td>Nombre</td><td colspan="2"></td></tr>
<?		while($fila=ExFetch($res)){
		$pe=explode(".",$fila[1]);
		$ext=$pe[count($pe)-1];
		//echo $ext;
		?>
			<tr title="Ver" onMouseOver="this.bgColor='#AAD4FF'" onMouseOut="this.bgColor=''" >
            	<td style="cursor:hand" onClick="<? if($ext!="pdf"&&$ext!="PDF"){?> location.href='VerAnexo.php?DatNameSID=<? echo $DatNameSID?>&Ruta=<? echo $fila[1]?>&Nombre=<? echo $fila[0]?>'<? }else{?> location.href='<? echo $fila[1]?>'<? }?>"><? echo $fila[0]?></td>
               <!-- 
                <td style="cursor:hand" onClick="location.href='VerAnexo.php?DatNameSID=<? echo $DatNameSID?>&Ruta=<? echo $fila[1]?>&Nombre=<? echo $fila[0]?>'"><? echo $fila[1]?></td>
            	-->
                <td><img title="Editar" src="/Imgs/b_edit.png" style="cursor:hand" 
                onClick="location.href='NewAnexo.php?DatNameSID=<? echo $DatNameSID?>&Edit=1&Nombre=<? echo $fila[0]?>&Ruta=<? echo $fila[1]?>'"></td>
                <td><img title="Eliminar" style="cursor:hand" src="/Imgs/b_drop.png"
                onClick="if(confirm('Desea eliminar este registro?')){location.href='Anexos.php?DatNameSID=<? echo $DatNameSID?>&Eliminar=1&Nombre=<? echo $fila[0]?>&Ruta=<? echo $fila[1]?>';}"></td>                
          	</tr>
<?		}
	}
	else
	{?>
		<tr><td  align="center"  bgcolor="#e5e5e5" colspan="4" style="font-weight:bold">No Se Han Ingresado Anexos</td></tr>
<?	}?>   
 	<tr><td  align="center" colspan="4"><input type="button" value="Nuevo" onClick="location.href='NewAnexo.php?DatNameSID=<? echo $DatNameSID?>'"></td></tr>
</table><?
}
else{
		echo "<center><font face='Tahoma' color='#0066FF' size='+2' ><b>No hay un paciente seleccionado!!! </b></font></center>";
}?>
<input type="hidden" name="DatNameSID" value="<? echo $DatNameSID?>">
</form>
</body>
</html>
