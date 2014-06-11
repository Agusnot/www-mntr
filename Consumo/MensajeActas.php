<?
	if($DatNameSID){session_name("$DatNameSID");}
	session_start();
	include("Funciones.php");
	if(!$AlmacenPpal)
	{
		$cons = "Select AlmacenPpal from Consumo.UsuariosxAlmacenes where Usuario='$usuario[0]' and Compania='$Compania[0]'";
		$res = ExQuery($cons);
		$fila = ExFetch($res);
		$AlmacenPpal = $fila[0];		
	}
	if($Guardar)
	{
		if(!$Editar)
		{
			$cons = "Insert into Consumo.MensajeActas (Compania,AlmacenPpal,Encabezado,PiedePagina)
					values ('$Compania[0]','$AlmacenPpal','$Encabezado','$PiedePagina')";
			
		}
		else
		{
			$cons = "Update Consumo.MensajeActas set Encabezado = '$Encabezado',PiedePagina = '$PiedePagina'
					where Compania = '$Compania[0]' and AlmacenPpal = '$AlmacenPpal' and Encabezado = '$Encabezadox'
					and PiedePagina = '$PiedePaginax'";	
		}
		$res = ExQuery($cons);
	}
?>
<body background="/Imgs/Fondo.jpg">
<form name="FORMA" method="post">
<input type="hidden" name="DatNameSID" value="<? echo $DatNameSID?>"/>
	<select name="AlmacenPpal" onChange="document.FORMA.submit();">
<?
			$cons = "Select AlmacenPpal from Consumo.UsuariosxAlmacenes where Usuario='$usuario[0]' and Compania='$Compania[0]'";
			$res = ExQuery($cons);
			while($fila = ExFetch($res))
			{
				if($AlmacenPpal==$fila[0]){echo "<option selected value='$fila[0]'>$fila[0]</option>";}
				else{echo "<option value='$fila[0]'>$fila[0]</option>";}
			}
?>
	</select>
<?
	if($AlmacenPpal)
	{
		$cons = "Select Encabezado,PiedePagina from Consumo.MensajeActas where Compania = '$Compania[0]' and AlmacenPpal = '$AlmacenPpal'";
		$res = ExQuery($cons);
		if(ExNumRows($res) > 0)
		{
			$fila = ExFetch($res);
			$Editar = 1;
			$Encabezado = $fila[0];
			$PiedePagina = $fila[1];
		}
		else{
			$Editar=0;
			$Encabezado = "";
			$PiedePagina = "";
			}
		?>
		<input type="Hidden" name="Editar" value="<? echo $Editar?>" />
		<input type="Hidden" name="Encabezadox" value="<? echo $Encabezado?>" />
		<input type="Hidden" name="PiedePaginax" value="<? echo $PiedePagina?>" />
		<table style='font : normal normal small-caps 12px Tahoma;' border="1" bordercolor="#e5e5e5" width="650px">
			<tr bgcolor="#e5e5e5" align="center" style="font-weight:bold"><td>Encabezado</td></tr>
    		<tr><td><textarea name="Encabezado" style="width:100%; height:150px;" ><? echo $Encabezado?></textarea></td></tr>
    		<tr bgcolor="#e5e5e5" align="center" style="font-weight:bold"><td>Pie de P&aacute;gina</td></tr>
    		<tr><td><textarea name="PiedePagina" style="width:100% ; height:150px;"><? echo $PiedePagina?></textarea></td></tr>
		</table>
		<input type="submit" name="Guardar" value="Guardar" />	
	<? } ?>
</form>
</body>