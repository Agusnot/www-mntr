<?
	mysql_select_db("salud", $conex);

	if($Reactivar)
	{
		$cons="Delete from Agenda where Medico='$Medico' and Fecha='$Fecha' and HoraInicio='$Hora' 
		and Cedula='0' and Estado='T'";
		$res=mysql_query($cons);
		echo mysql_error();
		?>
		<script language="JavaScript">
			opener.parent.document.location.href=opener.parent.document.location.href;
			window.close();
		</script>
		<?
	}
?>
<html>
<head>
	<title>Reactivación de Horario</title>
</head>

<body background="/Imgs/Fondo.jpg">
<form name="FORMA">
<br>
<center><input type="Submit" name="Reactivar" value="Reactivar Horario"></center>
<input type="Hidden" name="Fecha" value="<?echo $Fecha?>">
<input type="Hidden" name="Hora" value="<?echo $Hora?>">
<input type="Hidden" name="Medico" value="<?echo $Medico?>">
</form>

</body>
</html>
