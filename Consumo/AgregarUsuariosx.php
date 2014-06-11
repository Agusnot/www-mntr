<?
	if($DatNameSID){session_name("$DatNameSID");}
	session_start();
	include ("Funciones.php");
	$cons = "Select Nombre, usuario from Central.Usuarios where Usuario not in
			(Select Usuario from Consumo.".$Tabla." where AlmacenPpal='$AlmacenPpal' and Compania='$Compania[0]')
			order by usuario ASC ,Nombre ASC";
	$res = ExQuery($cons);
	if($Guardar)
	{
		while( list($cad,$val) = each($Activar))
		{
			if($val == "on")
			{
				$cons1 = "Insert into Consumo.".$Tabla." (Usuario,AlmacenPpal,Compania) values
							('$cad','$AlmacenPpal','$Compania[0]')";
				$res1 = ExQuery($cons1);
			}
		}
		?>
		<script language="javascript">
        	location.href="Usuariox.php?DatNameSID=<? echo $DatNameSID?>&Tabla=<? echo $Tabla?>&AlmacenPpal=<? echo $AlmacenPpal?>";
        </script>
		<?
	}
?>
<form name="FORMA" method="post">
<input type="hidden" name="DatNameSID" value="<? echo $DatNameSID?>" />
<input type="Hidden" name="Tabla" value="<? echo $Tabla?>" />
<input type="Hidden" name="AlmacenPpal" value="<? echo $AlmacenPpal?>"  />
<table style='font : normal normal small-caps 12px Tahoma;' border="1" bordercolor="#e5e5e5" width="600px">
	<tr bgcolor="#e5e5e5" style="font-weight:bold" align="center">
    	<td>Usuario</td><td>Activar</td>
    </tr>
        <?
        	while($fila = ExFetch($res))
			{
				echo "<tr><td>$fila[0]</td>";
				?><td align="center"><input type="checkbox" name="Activar[<? echo $fila[1]?>]" /></td></tr><?
			}
		?>
</table>
<input type="submit" name="Guardar" value="Guardar" />
<input type="button" name="Cancelar" value="Cancelar" onclick="location.href='Usuariox.php?DatNameSID=<? echo $DatNameSID?>&Tabla=<? echo $Tabla?>&AlmacenPpal=<? echo $AlmacenPpal?>'"  />
</form>