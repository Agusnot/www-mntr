<?
	session_start();
	include("Funciones.php");
	mysql_select_db("Central", $conex);

	$cons="Select Cargo from Medicos where Nombre='$Medico'";
	$res=mysql_query($cons);
	$fila=ExFetch($res);
	$Cargo=$fila[0];
	if($Cargo=="Residente"){$Cargo="Psiquiatra";}
	if($Cargo=="Practicante psicologia"){$Cargo="Psicologo";}



	if($Guardar)
	{
		$cons="Select * from Terceros where Cedula='$CedPaciente'";
		$res=mysql_query($cons);
		if(mysql_num_rows($res)==0)
		{
			$cons2="Insert into Terceros (TipoDoc,Cedula,primape,segape,primnom,segnom,telefono,FecNac,Compania) values
			('$TipoDoc','$CedPaciente','$PrimApe','$SegApe','$PrimNom','$SegNom','$Telefono','$FecNac','$Compania[0]')";
			$res2=mysql_query($cons2);echo mysql_error();
		}
		else
		{
			$cons2="Update Terceros set TipoDoc='$TipoDoc',primape='$PrimApe',segape='$SegApe',
			primnom='$PrimNom',segnom='$SegNom',telefono='$Telefono',FecNac='$FecNac' where Cedula='$CedPaciente'";
			$res2=mysql_query($cons2);echo mysql_error();
		}
		$HRANT=strtotime("$Hora:$Min:00");
		$HRANT=strtotime("+$Duracion minutes" , $HRANT);
		$HRNEW=getdate($HRANT);
		$HoraFin=$HRNEW[hours] . ":" . $HRNEW[minutes];
		$ND=getdate();
		$cons="Insert into Agenda(Medico,Cedula,Fecha,HoraInicio,Tiempo,HoraFin,Estado,Entidad,Regimen,TipoConsulta,Cargo,usuario,FecHoraCr) values
		('$Medico','$CedPaciente','$Anio-$Mes-$Dia','$Hora:$Min','$Duracion','$HoraFin','P','$SelEntidad','$Regimen','$TipoAt','$Cargo','$usuario[0]','$ND[year]-$ND[mon]-$ND[mday] $ND[hours]:$ND[minutes]')";
		$res=mysql_query($cons);
		echo mysql_error();
		?>
		<script language="JavaScript">
			opener.document.location.href='ListaPacxMedico.php?Dia=<?echo $Dia?>&Mes=<?echo $Mes?>&Anio=<?echo $Anio?>&Medico=<?echo $Medico?>'
			window.close();
		</script>	
<?		
	}
?>

<script language="JavaScript">
	function Rellamar()
	{
		location.href='NuevaCita.php?Hora=<?echo $Hora?>&TipoAt=' + document.FORMA.TipoAt.value + '&Min=<?echo $Min?>&Dia=<?echo $Dia?>&Mes=<?echo $Mes?>&Anio=<?echo $Anio?>&Medico=<?echo $Medico?>&SelEntidad=' + document.FORMA.SelEntidad.value;
	}
	
</script>
<html>
<head>
	<title>Nueva cita</title>
</head>
<body background="/Imgs/Fondo.jpg">
<script language="JavaScript">
	function ValidaDatos()
	{
	
		if(document.FORMA.TipoAt.value=="")
		{
			alert("Favor seleccione la clase de atención");
			document.FORMA.TipoAt.focus();
			return false;
		}
		
		if(document.FORMA.Duracion.value=="")
		{
			alert("Favor fijar la cantidad de minutos posibles de atención");
			document.FORMA.Duracion.focus();
			return false;
		}

		if(document.FORMA.SelEntidad.value=="")
		{
			alert("Debe seleccionar una entidad");
			document.FORMA.SelEntidad.focus();
			return false;
		}
		if(document.FORMA.CedPaciente.value=="")
		{
			alert("Favor escribir documento de paciente");
			document.FORMA.CedPaciente.focus();
			return false;
		}
		if(document.FORMA.PrimApe.value=="")
		{
			alert("Favor escribir el primer apellido del paciente");
			document.FORMA.PrimApe.focus();
			return false;
		}
		if(document.FORMA.PrimNom.value=="")
		{
			alert("Favor escribir el primer nombre del paciente");
			document.FORMA.PrimNom.focus();
			return false;
		}
	}
</script>
<form name="FORMA" onSubmit="return ValidaDatos()">
<table border="1" cellspacing="0" style="font-family:Tahoma; font-size:11px; font-variant:normal;">
<tr><td width="51" bgcolor="#C7C7C7"><strong>Hora Inicio</strong></td>
<td width="148">
<input type="Text" style="width:60px;" readonly="yes" value="<?echo "$Hora:$Min"?>">
</td>
<td width="152" bgcolor="#C7C7C7"><strong>Duración:</strong></td>
<?
	$Mina=$Min;
	for($hrs=$Hora;$hrs<=17;$hrs++)
	{
		for($mins=$Mina;$mins<=50;$mins=$mins+10)
		{
			$cons="Select PrimApe,SegApe,PrimNom,SegNom,HoraInicio,HoraFin,hour(HoraFin),minute(HoraFin)
			from Agenda,Terceros 
			where Agenda.Cedula=Terceros.Cedula and 
			Fecha='$Anio-$Mes-$Dia' and Medico='$Medico' and hour(HoraInicio)='$hrs' 
			and minute(HoraInicio)=$mins and Estado!='C'";
			$res=mysql_query($cons);
			echo mysql_error();
			if(mysql_num_rows($res)>0){$fila=ExFetch($res);$SigHora=$fila[4];$hrs=18;break;}
		}
		$Mina=0;
	}

	$Hora1=$SigHora;
	$Hora2="$Hora:$Min:00";
	$s = strtotime($Hora1)-strtotime($Hora2);
	$d = intval($s/86400);
	$s -= $d*86400;
	$h = intval($s/3600);
	$s -= $h*3600;
	$m = intval($s/60);
	$m=$m+$h*60;
?>
<td width="144">
<select name="Duracion">
<option value="">-</option>
<?
	if($TipoAt=="Control"){$CmpSel="MinControl";}else{$CmpSel="Min1a";}
	$cons="Select Duracion from AtencionConsExterna where Tipo='$TipoAt'";
	$res=mysql_query($cons);
	$fila=ExFetch($res);
	$Durac=$fila[0];
	if($m<=0){$m=60;}
	for($i=10;$i<=$m;$i+=10)
	{
		if($Durac==$i){echo "<option selected value='$i'>$i</option>";}
		else{echo "<option value='$i'>$i</option>";}
	}
?>
</select>
<strong>minutos</strong></td>
</tr>
<tr><td bgcolor="#C7C7C7"><strong>Entidad</strong></td>
<td colspan="3"><select name="SelEntidad" onChange="Rellamar()" style="width:290px;">
<option value="">-Seleccione Entidad-</option>
<?
	$cons="Select nombre from Clientes order by nombre";
	$res=mysql_query($cons);
	echo mysql_error();
	while($fila=ExFetch($res))
	{
		if($SelEntidad==$fila[0]){echo "<option selected value='$fila[0]'>$fila[0]</option>";}
		else{echo "<option value='$fila[0]'>$fila[0]</option>";}
	}
?>
</select></td>
</tr>
<tr>
<td bgcolor="#C7C7C7"><strong>Tipo</strong></td>
<td>
<select name="TipoAt" onChange="Rellamar()">
	<option value="">-Seleccione el tipo-</option>
	<?
	$cons="Select Tipo from AtencionConsExterna";
	$res=mysql_query($cons);echo mysql_error();
	while($fila=ExFetch($res))
	{
		if($fila[0]==$TipoAt){echo "<option selected value='$fila[0]'>$fila[0]</option>";}
		else{echo "<option value='$fila[0]'>$fila[0]</option>";}
	}
	?>
</select>
</tr>
<tr>
<td colspan=2>
<select name="TipoDoc">
<?php
	$cons = "SELECT TipoDoc FROM TiposDocumentos";
	$resultado = mysql_query($cons,$conex);
	while ($fila = ExFetch($resultado))
	{
		if($TipoDoc==$fila[0])
		{
			echo "<option value='$fila[0]' selected>$fila[0]</option>";
		}
		else
		{
			echo "<option value='$fila[0]'>$fila[0]</option>";
		}
	}?>
</select>

</td>
<td colspan="2">No. <input type="Text" name="CedPaciente" style="width:100px;">
<input type="Button" value="?" onClick="open('BuscarDatoxCedula.php?CedPaciente=' + document.FORMA.CedPaciente.value,'','width=100,height=100,top=1,left=1')">
</td></tr>

<tr><td colspan="4" bgcolor="#C7C7C7"><strong><center><strong>Apellidos</strong></td></tr>
<tr align="center"><td colspan="2"><input type="Text" name="PrimApe"></td>
<td colspan="2"><input type="Text" name="SegApe"></td></tr>

<tr align="center"><td colspan="4" bgcolor="#C7C7C7"><strong>Nombres</strong></td></tr>
<tr align="center">
	<td colspan="2"><input type="Text" name="PrimNom"></td>
	<td colspan="2"><input type="Text" name="SegNom"></td>
</tr>

<tr align="center">
  <td bgcolor="#c7c7c7"><strong>Telefono</strong></td>
  <td><input type="Text" name="Telefono"></td>
  
  <td colspan="2"><input type="Text" name="MensMult" style="border:0px;background:transparent;color:red;font-weight:bold;" readonly="yes"></td></tr>
<tr align="center">
  <td colspan="4"><input type="Submit" name="Guardar" value="Asignar Cita">
    <input type="Button" value="Buscar Registro" onClick="open('BuscarRegistroAgenda.php','','')"></td>
</tr>
</table>
<input type="Hidden" name="Medico" value="<?echo $Medico?>">
<input type="Hidden" name="Anio" value="<?echo $Anio?>">
<input type="Hidden" name="Mes" value="<?echo $Mes?>">
<input type="Hidden" name="Dia" value="<?echo $Dia?>">
<input type="Hidden" name="Hora" value="<?echo $Hora?>">
<input type="Hidden" name="Min" value="<?echo $Min?>">
<input type="Hidden" name="Regimen" value="<?echo $Regimen?>">
</form>

</body>
</html>
