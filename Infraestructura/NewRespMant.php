<?
	if($DatNameSID){session_name("$DatNameSID");}
	session_start();
	include("Funciones.php");
	if($Agregar)
	{
		if($checkUsuario)
		{
			while(list($cad,$val) = each($checkUsuario))
			{
				$cons = "Insert into Infraestructura.ResponsablesMantenimiento (Compania,Usuario)
				values('$Compania[0]','$cad')";
				$res = ExQuery($cons);	
			}	
		}
		?><script language="javascript">
        	location.href="RespMant.php?DatNameSID=<? echo $DatNameSID?>";
        </script><?
	}
	$cons = "Select Nombre,Cedula from Central.Usuarios 
	Where Cedula not in(Select Usuario from Infraestructura.ResponsablesMantenimiento Where Compania='$Compania[0]') order by Nombre";
	$res = ExQuery($cons);
	?>
<body background="/Imgs/Fondo.jpg">
	<form name="FORMA" method="post">
    <input type="hidden" name="DatNameSID" value="<? echo $DatNameSID?>" />
    <table style='font : normal normal small-caps 12px Tahoma;' border="1" bordercolor="#e5e5e5" width="600px">
    <tr bgcolor="#e5e5e5" align="center" style="font-weight:bold"><td>Usuario</td><td>Agregar</td></tr>
	<?
	while($fila = ExFetch($res))
	{
	?>
	<tr onMouseOver="this.bgColor='#AAD4FF'" onMouseOut="this.bgColor='#FFFFFF'">
    	<td><? echo $fila[0];?></td>
        <td align="center"><input type="checkbox" name="checkUsuario[<? echo $fila[1];?>]" value="<? echo $fila[1];?>" /></td>
    </tr>
    <?		
	}
	?></table>
    <input type="submit" name="Agregar" value="Agregar" />
    <input type="button" name="Volver" value="Volver" onClick="location.href='RespMant.php?DatNameSID=<? echo $DatNameSID?>'" />
	</form>
	<?
?>
</body>