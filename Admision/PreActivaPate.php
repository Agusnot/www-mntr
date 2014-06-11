<?
	include("Funciones.php");
	mysql_select_db("Central", $conex);
	$ND=getdate();
	$HoraAct =$ND[hours] . ":" . $ND[minutes];
	if($ND[mon]<10){$MesAct="0" . $ND[mon];}else{$MesAct=$ND[mon];}
	if($ND[mday]<10){$DiaAct="0" . $ND[mday];}else{$DiaAct=$ND[mday];}
	$FechaAct=$ND[year] . "-" . $MesAct . "-" . $DiaAct;

	if($Activar)
	{
		$cons="Update Agenda set Estado='A',HoraLlegada='$HoraAct',Factura='SF' 
		where Cedula='$CedPaciente' and Fecha='$Fecha' and Medico='$Medico' 
		and HoraInicio='$Hora' and Estado='P'";
		$res=mysql_query($cons);
		echo mysql_error();
	?>
	<script language="JavaScript">
		open("GenFacturaAmbu.php?CedPaciente=<?echo $CedPaciente?>&EPS=<?echo $Entidad?>&Medico=<?echo $Medico?>&TipoAt=<?echo $TipoAt?>&Fecha=<?echo $Fecha?>&Hora=<?echo $Hora?>&Cargo=<?echo $Cargo?>","","width=600,height=300,top=130,left=100");
		opener.document.location.href="ListaPacxMedico.php?Dia=<?echo $Dia?>&Mes=<?echo $Mes?>&Anio=<?echo $Anio?>&Medico=<?echo $Medico?>";
		window.close();
	</script>	
<?	}
	if($Multa)
	{
		$cons="Update Agenda set Multa='' where Cedula='$CedPaciente'";
		$res=mysql_query($cons);
		echo mysql_error();
		$cons="Insert into ListaMultas(Fecha,Cedula,Valor) 
		values('$FechaAct','$CedPaciente','$ValMulta')";
		$res=mysql_query($cons);
		echo mysql_error();
	}
	
	if($Cancelar1)
	{
		$cons="Update Agenda set Estado='C', QuienCancela=1,MotivoCanc='$MotivoUsu',Multa='$Multa' 
		where Cedula='$CedPaciente' and Fecha='$Fecha' and Medico='$Medico' and HoraInicio='$Hora' and Estado='P'";
		$res=mysql_query($cons);
		$Anio=substr($Fecha,0,4);
		$Mes=substr($Fecha,5,2);
		$Dia=substr($Fecha,8,2);
		echo mysql_error();
		?>
			<script language="JavaScript">
				opener.document.location.href='ListaPacxMedico.php?Dia=<?echo $Dia?>&Mes=<?echo $Mes?>&Anio=<?echo $Anio?>&Medico=<?echo $Medico?>'
				window.close();
			</script>	

<?	}
	
	if($Cancelar2)
	{
		$cons="Update Agenda set Estado='C',QuienCancela=2,MotivoCanc='$MotivoMd' 
		where Cedula='$CedPaciente' and Fecha='$Fecha' and Medico='$Medico' and HoraInicio='$Hora' and Estado='P'";
		$res=mysql_query($cons);
		$Anio=substr($Fecha,0,4);
		$Mes=substr($Fecha,5,2);
		$Dia=substr($Fecha,8,2);
		echo mysql_error();
		?>
			<script language="JavaScript">
				opener.document.location.href='ListaPacxMedico.php?Dia=<?echo $Dia?>&Mes=<?echo $Mes?>&Anio=<?echo $Anio?>&Medico=<?echo $Medico?>'
				window.close();
			</script>	

<?	}

?>
<html>
<head>
	<title>Activación/Desactivación de paciente</title>
</head>
<body background="/Imgs/Fondo.jpg">
<table border="1" width="100%">
<form name="FORMA">
<tr><td bgcolor="#F4F4F4"><center><strong>
Paciente en sala de espera
</td></tr>
<tr><td><center>
<input type="Hidden" name="CedPaciente" value="<?echo $CedPaciente?>">
<input type="Hidden" name="Fecha" value="<?echo $Fecha?>">
<input type="Hidden" name="Hora" value="<?echo $Hora?>">
<input type="Hidden" name="Medico" value="<?echo $Medico?>">
<input type="Hidden" name="Entidad" value="<?echo $Entidad?>">
<input type="Hidden" name="TipoAt" value="<?echo $TipoAt?>">
<input type="Hidden" name="Cargo" value="<?echo $Cargo?>">
<?
	$cons="Select Multa,Regimen from Agenda where Cedula='$CedPaciente' and Multa='on'";
	$res=mysql_query($cons);
	if(mysql_num_rows($res)>0)
	{
		$fila=ExFetch($res);
		$Regimen=$fila[1];
		$cons2="Select Valor from Multas where Regimen='$Regimen'";
		$res2=mysql_query($cons2);
		$fila2=ExFetch($res2);
		$VrMulta=$fila2[0];
		echo "<input type='Hidden' name='ValMulta' value=$VrMulta>";
		echo "<font color='#ff0000'><strong>Cancelar Multa x " . CNumeros($VrMulta,'$ ') . "</font></strong>";
		echo " <input type='Submit' value='Ok' name='Multa'>";
	}
	else
	{
		$cons="Select Entidad from Agenda where Cedula='$CedPaciente' and Fecha='$Fecha' and HoraInicio='$Hora'
		and Medico='$Medico'";
		$res=mysql_query($cons);
		$fila=ExFetch($res);
		$EPS=$fila[0];

		if($SoloGenera)
		{?>
			<input type="Button" value="Genera Factura" onclick="opener.document.location.href='ListaPacxMedico.php?Dia=<?echo $Dia?>&Mes=<?echo $Mes?>&Anio=<?echo $Anio?>&Medico=<?echo $Medico?>';window.close();open('GenFacturaAmbu.php?CedPaciente=<?echo $CedPaciente?>&EPS=<?echo $Entidad?>&Medico=<?echo $Medico?>&TipoAt=<?echo $TipoAt?>&Fecha=<?echo $Fecha?>&Hora=<?echo $Hora?>&Cargo=<?echo $Cargo?>','','width=600,height=300,top=130,left=100')">
		<?}
		else
		{
//		if($FechaAct==$Fecha)
	//	{
			?><input type="Submit" value="Activar Cita" name="Activar">
<?		//}
//		else
	//	{
		?><!--<input type="Submit" value="Activar Cita" name="Activar" disabled>!-->
<?		//}
		}
	}?>
</td></tr>
</form>
</table>

<hr>

<table border="1" width="100%">
<form name="FORMA1">
<input type="Hidden" name="CedPaciente" value="<?echo $CedPaciente?>">
<input type="Hidden" name="Fecha" value="<?echo $Fecha?>">
<input type="Hidden" name="Hora" value="<?echo $Hora?>">
<input type="Hidden" name="Medico" value="<?echo $Medico?>">
<tr><td colspan="2" bgcolor="#F4F4F4"><center><strong>
Paciente que cancela la cita
</td></tr>
<tr>
<td>Motivo</td>
<td><select name="MotivoUsu">
<option value="No informa/No asiste">No informa/No asiste</option>
<option value="Entidad no autoriza">Entidad no autoriza</option>
<option value="Inconformidad con medico">Inconformidad con medico</option>
<option value="Inconformidad con institucion">Inconformidad con institucion</option>
<option value="Otros">Otros</option>
</select></td>
<tr><td colspan="2"><center>
<input type="Checkbox" name="Multa">
Multa?</center></td></tr>
<tr><td colspan="2"><center><input type="Submit" value="Cancelar Cita" name="Cancelar1">
<input type="Button" name="Reasignar" value="Reajustar Cita" onclick="open('ReajustarCita.php?Cedula=<?echo $CedPaciente?>&FechaOriginal=<?echo $Fecha?>&MedOriginal=<?echo $Medico?>&HoraOriginal=<?echo $Hora?>','','width=700,height=400')" />

</td></tr>
</form>

</table>
<hr>

<table border="1" width="100%">
<form name="FORMA2">
<input type="Hidden" name="CedPaciente" value="<?echo $CedPaciente?>">
<input type="Hidden" name="Fecha" value="<?echo $Fecha?>">
<input type="Hidden" name="Hora" value="<?echo $Hora?>">
<input type="Hidden" name="Medico" value="<?echo $Medico?>">
<tr><td colspan="2" bgcolor="#F4F4F4"><center><strong>
Medico que cancela la cita</td></tr>
<tr>
<td>Motivo</td>
<td><select name="MotivoMd">
<option value="No informa/No asiste">No informa/No asiste</option>
<option value="Reunión administrativa">Reunión administrativa</option>
<option value="Reunión clinica">Reunión clinica</option>
<option value="Permiso laboral">Permiso laboral</option>
<option value="Capacitación">Capacitación</option>
<option value="Otros">Otros</option>
</select></td><tr>
<td colspan="2"><center><input type="Submit" name="Cancelar2" value="Cancelar Cita"></td>
</tr>
</form>
</table>


</body>
</html>
