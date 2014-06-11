<?
	session_start();
	mysql_select_db("salud", $conex);
	if($Detalle)
	{
		$ND=getdate();
		$HoraInicio="$HoraI:$MinI";
		$HoraFin="$HoraF:$MinF";

		$s = strtotime($HoraFin)-strtotime($HoraInicio);
		$d = intval($s/86400);
		$s -= $d*86400;
		$h = intval($s/3600);
		$s -= $h*3600;
		$m = intval($s/60);
		$m=$m+$h*60;
		$Tiempo=$m;
		$cons="Insert into Agenda(Medico,Cedula,Fecha,HoraInicio,Tiempo,HoraFin,Estado,Usuario,FecHoraCr,MotivoCanc) values
		('$Medico','0','$Fecha','$HoraInicio',$Tiempo,'$HoraFin','T','$usuario[0]','$ND[year]-$ND[mon]-$ND[mday] $ND[hours]:$ND[minutes]','$Detalle')";
		$res=mysql_query($cons);
		echo mysql_error();
		?>
		<script language="JavaScript">
			RutaAnt=opener.location.href;
			opener.document.location.href=RutaAnt;
			window.close();
		</script>
		<?
	}
?>
<body background="/Imgs/Fondo.jpg">
<form name="FORMA">
<center>
<table border="1">
<tr><td>Hora Inicio</td>
<td>Hora Final</td><td>Detalle</td></tr>
<tr>
<td>
<select name="HoraI">
<?
	for($i=7;$i<=17;$i++)
	{
		echo "<option value=$i>$i</option>";
	}
?>
</select>
<select name="MinI">
<?
	for($i=0;$i<=50;$i=$i+10)
	{
		echo "<option value=$i>$i</option>";
	}
?>
</select>
</td>
<td>
<select name="HoraF">
<?
	for($i=7;$i<=17;$i++)
	{
		echo "<option value=$i>$i</option>";
	}
?>
</select>
<select name="MinF">
<?
	for($i=0;$i<=50;$i=$i+10)
	{
		echo "<option value=$i>$i</option>";
	}
?>
</select>
</td>
<td>
<input type="Text" name="Detalle" style="border:1px solid;width:250px;">
<input type="Hidden" name="Fecha" value="<?echo "$Anio-$Mes-$Dia"?>">
<input type="Hidden" name="Medico" value="<?echo $Medico?>">
</td>
<td>
<input type="Submit" name="Guardar" value="G">
</td>
</tr>
</table>
</form>
</body>