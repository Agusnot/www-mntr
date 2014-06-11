<?	if($DatNameSID){session_name("$DatNameSID");}
	session_start();
	include("Funciones.php");
	if($Eliminar){
		$cons="delete from contratacionsalud.gruposservicio where compania='$Compania[0]' and codigo='$Codigo' and grupo='$Grupo'";
		$res=ExQuery($cons);
		$cons="delete from contratacionsalud.cuentaxgrupos where compania='$Compania[0]' and codigo='$Codigo' and grupo='$Grupo'";
		$res=ExQuery($cons);
	}
	$cons="select codigo,grupo from contratacionsalud.gruposservicio where compania='$Compania[0]' order by codigo";
	//echo $cons;
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
    	<td>Codigo</td><td>Grupo</td><td colspan="3"></td>
    </tr>
<?	while($fila=ExFetch($res)){?>
		<tr onMouseOver="this.bgColor='#AAD4FF'" onMouseOut="this.bgColor=''">
        	<td><? echo $fila[0]?></td><td><? echo $fila[1]?></td>
            <td><button style="cursor:hand" style="Configurar Cuentas"
            	onClick="location.href='ConfCuentxGruposServ.php?DatNameSID=<? echo $DatNameSID?>&Edit=1&Codigo=<? echo $fila[0]?>&Grupo=<? echo $fila[1]?>'">            	
            		<img src="/Imgs/s_process.png">
                </button>
            </td>
            <td><button style="cursor:hand" title="Editar"
            		 onClick="location.href='NewConfGruposServicios.php?DatNameSID=<? echo $DatNameSID?>&Edit=1&Codigo=<? echo $fila[0]?>&Grupo=<? echo $fila[1]?>'">
            		<img src="/Imgs/b_edit.png">
            	</button>
          	</td>
            <td>
            	<button style="cursor:hand" title="Eliminar" 
                onClick="if(confirm('Desea eliminar este registro?')){location.href='ConfGruposServicios.php?DatNameSID=<? echo $DatNameSID?>&Eliminar=1&Codigo=<? echo $fila[0]?>&Grupo=<? echo $fila[1]?>'}">
                	<img src="/Imgs/b_drop.png">
                </button>
            </td> 
        </tr>	
<?	}?>    
	<tr align="center">
    	<td colspan="6"><input type="button" value="Nuevo" onClick="location.href='NewConfGruposServicios.php?DatNameSID=<? echo $DatNameSID?>'"></td>
    </tr>
</table>
</form>  
<input type="hidden" name="DatNameSID" value="<? echo $DatNameSID?>">  
</body>
</html>
