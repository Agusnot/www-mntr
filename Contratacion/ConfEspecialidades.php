<?	if($DatNameSID){session_name("$DatNameSID");}
	session_start();
	include("Funciones.php");
	$ND=getdate();
	if($Eliminar){
		$cons="delete from salud.especialidades where compania='$Compania[0]' and especialidad='$Especialidad'";
		$res=ExQuery($cons);
	}
	$cons="select codigo,centrocostos from central.centroscosto where compania='$Compania[0]' and anio='$ND[year]'";
	$res=ExQuery($cons);
	while($fila=ExFetch($res)){
		$CentCos[$fila[0]]=$fila[1];
	}
	$cons="select especialidad,cuentacont,nomcuenta,especialidades.centrocostos from salud.especialidades
	where especialidades.compania='$Compania[0]' order by especialidad";	
	$res=ExQuery($cons);
?>	
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
</head>

<body background="/Imgs/Fondo.jpg">
<form name="FORMA" method="post">
<table  BORDER=1  style='font : normal normal small-caps 12px Tahoma;' border="1" bordercolor="#e5e5e5" cellpadding="4">
	<tr bgcolor="#e5e5e5" style="font-weight:bold" align="center">
    	<td>Especialidad</td><td>Cod Cuenta</td><td>Cuenta</td><td>Cod Centro Costos</td><td>Centro Costos</td><td colspan="2"></td>
    </tr>
<?	while($fila=ExFetch($res)){?>
		<tr onMouseOver="this.bgColor='#AAD4FF'" onMouseOut="this.bgColor=''">
        	<td><? echo $fila[0]?></td><td><? echo $fila[1]?></td><td><? echo $fila[2]?></td><td><? echo $fila[3]?></td><td><? echo $CentCos[$fila[3]]?></td>
            <td><button style="cursor:hand" title="Editar" onClick="location.href='NewConfEspecialidades.php?DatNameSID=<? echo $DatNameSID?>&Edit=1&Especialidad=<? echo $fila[0]?>'">
            		<img src="/Imgs/b_edit.png">
            	</button>
          	</td>
            <td>
            	<button style="cursor:hand" title="Eliminar" 
                onClick="if(confirm('Desea eliminar este registro?')){location.href='ConfEspecialidades.php?DatNameSID=<? echo $DatNameSID?>&Eliminar=1&Especialidad=<? echo $fila[0]?>'}">
                	<img src="/Imgs/b_drop.png">
                </button>
            </td> 
        </tr>	
<?	}?>    
	<tr align="center">
    	<td colspan="8"><input type="button" value="Nuevo" onClick="location.href='NewConfEspecialidades.php?DatNameSID=<? echo $DatNameSID?>'"></td>
    </tr>
</table>
</form>  
<input type="hidden" name="DatNameSID" value="<? echo $DatNameSID?>">  
</body>
</html>
