<?
	if($DatNameSID){session_name("$DatNameSID");}
	session_start();
	include("Funciones.php");
	if($Guardar)
	{
		if(!$Editar)
		{
			$cons = "Insert into Infraestructura.MensajeActas (Compania,Cabecerademensaje,Piedemensaje,Acta)
					values ('$Compania[0]','$Encabezado','$PiedePagina','$Origen')";
			
		}
		else
		{
			$cons = "Update Infraestructura.MensajeActas set Cabecerademensaje = '$Encabezado',Piedemensaje = '$PiedePagina'
					where Compania = '$Compania[0]' and Cabecerademensaje = '$Encabezadox'
					and Piedemensaje = '$PiedePaginax' and Acta='$Origen'";	
		}
		$res = ExQuery($cons);
	}
?>
<body background="/Imgs/Fondo.jpg">
<form name="FORMA" method="post">
<input type="hidden" name="DatNameSID" value="<? echo $DatNameSID?>" />
<?
	$cons = "Select CabeceradeMensaje,PiedeMensaje from Infraestructura.MensajeActas where Compania = '$Compania[0]' and Acta = '$Origen'";
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
		<tr><td><textarea name="Encabezado" style="width:100%; height:150px; background:/Imgs/Fondo.jpg" ><? echo $Encabezado?></textarea></td></tr>
		<tr bgcolor="#e5e5e5" align="center" style="font-weight:bold"><td>Pie de P&aacute;gina</td></tr>
		<tr><td><textarea name="PiedePagina" style="width:100% ; height:150px; background:/Imgs/Fondo.jpg"><? echo $PiedePagina?></textarea></td></tr>
	</table>
	<input type="submit" name="Guardar" value="Guardar" />	
	
</form>
</body>