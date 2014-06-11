<?
	if($DatNameSID){session_name("$DatNameSID");}
	session_start();
	include ("Funciones.php");
	$ND = getdate();
	$cons = "Select Nombre,Cedula from Central.Usuarios where Nombre not in
			(Select Usuario from InfraEstructura.Administrador where Compania='$Compania[0]')
			order by Nombre asc";
	$res = ExQuery($cons);
	if($Guardar)
	{
		
		while( list($cad,$val) = each($Activar))
		{
			$cons1 = "Select Identificacion from Central.Terceros Where Compania='$Compania[0]' and Identificacion='$val'";
			$res1 = ExQuery($cons1);
			if(ExNumRows($res1)==1)
			{
				$cons1 = "Insert into Infraestructura.Administrador (Compania,Usuario,Cedula,UsuarioAutoriza,FechAutoriza) values
						('$Compania[0]','$cad','$val','$usuario[0]','$ND[year]-$ND[mon]-$ND[mday] $ND[hours]:$ND[minutes]:$ND[seconds]')";
				$res1 = ExQuery($cons1);		
			}
			else
			{
				?>
				<script language="javascript">
                	alert("Hay Inconsistencias en la Identificacion del Usuario: <? echo $cad;?>");
                </script>
				<?	
			}
		}
		?>
		<script language="javascript">
        	location.href="Administrador.php?DatNameSID=<? echo $DatNameSID?>";
        </script>
		<?
	}
?>
<body background="/Imgs/Fondo.jpg">
<form name="FORMA" method="post">
<input type="hidden" name="DatNameSID" value="<? echo $DatNameSID?>" />
<table style='font : normal normal small-caps 12px Tahoma;' border="1" bordercolor="#e5e5e5" width="600px">
	<tr bgcolor="#e5e5e5" style="font-weight:bold" align="center">
    	<td>Usuario</td><td>Agregar</td>
    </tr>
        <?
        	while($fila = ExFetch($res))
			{
				echo "<tr><td>$fila[0]</td>";
				?><td align="center"><input type="checkbox" name="Activar[<? echo $fila[0]?>]" value="<? echo $fila[1];?>" /></td></tr><?
			}
		?>
</table>
<input type="submit" name="Guardar" value="Guardar" />
<input type="button" name="Cancelar" value="Cancelar" onClick="location.href='Administrador.php?DatNameSID=<? echo $DatNameSID?>'"  />
</form>
</body>