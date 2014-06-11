<?
	if($DatNameSID){session_name("$DatNameSID");}
	session_start();
	include "Funciones.php";
	mysql_select_db("Central", $conex);
	$ND=getdate();
	if(!$MesCalend)
	{
		$MesCalend=$ND[mon];
	}
	
	if(!$AnioCalend)
	{
		$AnioCalend=$ND[year];
	}
	if(!$DiaCalend)
	{
		$DiaCalend=$ND[mday];
	}

	function NoCitasProgram($SelMedico,$FecCal)
	{
		$cons="Select * from Agenda where Fecha='$FecCal'
		and Medico='$SelMedico' and Estado!='T'";
		$res=mysql_query($cons);
		return mysql_num_rows($res);
	}
	function NoCitasRealizadas($SelMedico,$FecCal)
	{
		$cons="Select * from Agenda where Fecha='$FecCal' and Estado='A'
		and Medico='$SelMedico' and Estado!='T'";
		$res=mysql_query($cons);
		return mysql_num_rows($res);
	}
	function NoCitasNoRealizadas($SelMedico,$FecCal)
	{
		$cons="Select * from Agenda where Fecha='$FecCal' and Estado!='A'
		and Medico='$SelMedico' and Estado!='T'";
		$res=mysql_query($cons);
		return mysql_num_rows($res);
	}

	function DisponibilidadDia($SelMedico,$FecCal)
	{
		$cons="Select sum(Tiempo) from Agenda 
		where Medico='$SelMedico' and Fecha='$FecCal' and Estado!='C' and Estado!='T'";
		$res=mysql_query($cons);
		$fila=ExFetch($res);
		$TiempoOcp=$fila[0];
		$DF="$FecCal";
		$RT=strtotime($DF);
		$NT=getdate($RT);
		$DiaSem=$NT[wday];
		if($DiaSem==1){$Camp="HrsLun";}
		if($DiaSem==2){$Camp="HrsMar";}
		if($DiaSem==3){$Camp="HrsMie";}
		if($DiaSem==4){$Camp="HrsJue";}
		if($DiaSem==5){$Camp="HrsVie";}
		if($DiaSem==6){$Camp="HrsSab";}
		if($Camp){
		$cons="Select $Camp from Medicos where Nombre='$SelMedico'";
		$res=mysql_query($cons);echo mysql_error();
		$fila=ExFetch($res);
		$TotTiempo=$fila[0];
		if($TotTiempo>0){
		$PorcOcupac=round(($TiempoOcp*100)/$TotTiempo);}
		return $PorcOcupac;}
	}

	function TiempoDispo($SelMedico,$FecCal)
	{
		$cons="Select sum(Tiempo) from Agenda 
		where Medico='$SelMedico' and Fecha='$FecCal' and Estado!='C' ";
		$res=mysql_query($cons);
		$fila=ExFetch($res);
		$TiempoOcp=$fila[0];
		$DF="$FecCal";
		$RT=strtotime($DF);
		$NT=getdate($RT);
		$DiaSem=$NT[wday];
		if($DiaSem==1){$Camp="HrsLun";}
		if($DiaSem==2){$Camp="HrsMar";}
		if($DiaSem==3){$Camp="HrsMie";}
		if($DiaSem==4){$Camp="HrsJue";}
		if($DiaSem==5){$Camp="HrsVie";}
		if($DiaSem==6){$Camp="HrsSab";}
		if($Camp){
		$cons="Select $Camp from Medicos where Nombre='$SelMedico'";
		$res=mysql_query($cons);
		$fila=ExFetch($res);
		
		$ResHora=$fila[0];
		$BuscHora="S";$BuscaMins="N";
		$x=1;
		while($i<=strlen($ResHora))
		{
			$Car=substr($ResHora,$i,1);
			if($Car==":"){$BuscHora="N";$BuscaMins="S";}
			if($Car=="-"){$BuscaMins="N";$x++;$BuscHora="S";}
			if($Car==","){$BuscaMins="N";$x++;$BuscHora="S";}
			if($BuscaMins=="S" && $Car!=':')
			{
				$Mins[$x]=$Mins[$x] . $Car;
			}
			if($BuscHora=="S" && $Car!="-" && $Car!=",")
			{
				$HoraFilt[$x]=$HoraFilt[$x] . $Car;
			}
			$i++;
		}

		$TotTiempo=0;
		for($n=1;$n<=$x;$n++)
		{
			$n1=$n+1;
			$Hora1="$HoraFilt[$n]:$Mins[$n]";
			$Hora2="$HoraFilt[$n1]:$Mins[$n1]";
			$s = strtotime($Hora2)-strtotime($Hora1);
			$d = intval($s/86400);
			$s -= $d*86400;
			$h = intval($s/3600);
			$s -= $h*3600;
			$m = intval($s/60);
			$mins=$m+($h*60);
			$TotTiempo=$TotTiempo+$mins;
			$n++;
		}
		if($TotTiempo>0){
		$PorcOcupac=round(($TiempoOcp*100)/($TotTiempo+10));
		if($PorcOcupac>=96)
		{
			echo "<div><font color='#ff0000' size='4'><strong>*</strong></font></div>";
		}
		else
		{
			echo "<div><font color='green' size='4'><strong>*</strong></font></div>";
		}}
		return $PorcOcupac;}
	}
?>
<html>
<head>
	<title>Agenda Medica</title>
</head>
<body background="/Imgs/Fondo.jpg">
<script language="JavaScript">
	function Reabrir()
	{
		location.href='AgendaMedica.php?DatNameSID=<? echo $DatNameSID?>&MesCalend=' + document.FORMA.MesCalend.value 
		+ '&DiaCalend=' + document.FORMA.DiaCalend.value
		+ '&SelMedico=' + document.FORMA.SelMedico.value
		+ '&AnioCalend=' + document.FORMA.AnioCalend.value;
	}
</script>
<form name="FORMA" method="post">
<input type="hidden" name="DatNameSID" value="<? echo $DatNameSID?>"/>
<table cellpadding="2">
<tr><td>Mes:</td><td><select name="MesCalend" style="width:100px;" onChange="Reabrir()">
<?php
		$cons = "SELECT Mes,Numero,NumDias FROM Meses Order By Numero";
		$resultado = mysql_query($cons,$conex);
		while ($fila = ExFetch($resultado))
		{
			if($MesCalend==$fila[1])
			{
				echo "<option value='$fila[1]' selected>$fila[0]</option>";
				$totaldias=$fila[2];
			}
			else
			{
				echo "<option value='$fila[1]'>$fila[0]</option>";
			}
		}?>
</select></td> 
<td>
<input type="button" name="resta" value="<" style="width:13px;height:20px;" onclick=CalcAnio(1)>
<input type="text" name="AnioCalend" style="width:35px;" value=<?php echo $AnioCalend?>>
<input type="button" name="aumenta" value=">" style="width:13px;height:20px;" onclick=CalcAnio(2)>
</td></tr>
<tr><td>Especialidad</td><td><select name="Especialista"><option value="Psiquiatria">Psiquiatria</option></select></td></tr>
<tr><td>Medico</td>
<td colspan="2"><select name="SelMedico" onChange="Reabrir()">
<?
	$cons="Select Nombre from Medicos Order By Nombre";
	$res=mysql_query($cons);
	while($fila=ExFetch($res))
	{
		if(!$SelMedico){$SelMedico=$fila[0];}
		if($SelMedico==$fila[0]){echo "<option selected value='$fila[0]'>$fila[0]</option>";}
		else{echo "<option value='$fila[0]'>$fila[0]</option>";}
	}

?>
</select>
<input type="Button" value="?" onClick="open('DispoxMedico.php?DatNameSID=<? echo $DatNameSID?>&Medico=' + document.FORMA.SelMedico.value,'','width=250,height=100')">
</td>
</tr>
<tr><td colspan="3"><center>
<table border=1 cellspacing=0 style="width:250px;height:200px;font-size:13px;font-weight:bold">
<tr style="height:20px;font-weight:bold;text-align:center" bgcolor="#CAD0D0"><td class="TDTit"><font color='red'>D</td><td class="TDTit">L</td><td class="TDTit">M</td><td class="TDTit">M</td><td class="TDTit">J</td><td class="TDTit">V</td><td class="TDTit">S</td></tr>
<tr>
<?php
	$nd=0;
	$fecha = $MesCalend . '/01/' . $AnioCalend;
	$hora = getdate(strtotime($fecha));  
	for($i=0;$i<=$hora[wday]-1;$i++)
	{
		echo "<td class='Calend'>.</td>";
	}
	for($dia=1;$dia<=7-$i;$dia++)
	{
		if($DiaCalend==$dia){$BgColor="#CAD0D0";$TD=DisponibilidadDia($SelMedico,$FecCal);}else{$BgColor="white";}
		echo "<td bgcolor='$BgColor' onclick='document.FORMA.DiaCalend.value=$dia;Reabrir()'><div align='right'>$dia</div>";
		$FecCal="$AnioCalend-$MesCalend-$dia";
		$TiempoDispo=TiempoDispo($SelMedico,$FecCal);
		echo "</td>";
	}
	$nd=$dia-1;
	echo "</tr>";
	while($nd<=$totaldias)
	{
		echo "<tr>";	
		for($n=0;$n<=6;$n++)
		{
			$nd=$nd+1;
			if($nd>$totaldias)
			{
				echo "<td class='Calend'>.</td>";
			}
			else
			{
				$FecCal="$AnioCalend-$MesCalend-$nd";
				if($n==0)
				{
					echo "<td valign='top' align='right'><font color='red'>$nd<font color='red'></td>";  //Este es el dia actual
				}
				else
				{
					if($DiaCalend==$nd)
					{
						$BgColor="#CAD0D0";
						$NoCitasProgram=NoCitasProgram($SelMedico,$FecCal);
						$NoCitasReal=NoCitasRealizadas($SelMedico,$FecCal);
						$NoCitasNoReal=NoCitasNoRealizadas($SelMedico,$FecCal);
					}
					else
					{
						$BgColor="white";
					}
					echo "<td  bgcolor='$BgColor' onclick='document.FORMA.DiaCalend.value=$nd;Reabrir();'><div align='right'>$nd</div>";
					if($nd==$DiaCalend){
					$TiempoDispo=TiempoDispo($SelMedico,$FecCal);}
					else{TiempoDispo($SelMedico,$FecCal);}
					echo "</td>";
				}
			}
		}
		echo "</tr>";
	}
?>
</table>
<tr>
<td colspan="3">
<table border="1" width="100%" cellspacing="0" bordercolor="white">
<tr bgcolor="E5E5E5"><td colspan="3"><em><center>Ocupacionalidad</td><td colspan="3"><em><center>Citas</td></tr>
<tr style="text-align:center"><td>Dia</td><td>Mes</td><td>Año</td><td>Asig</td><td>Real</td><td>N R</td></tr>
<tr style="text-align:center" bgcolor="E5E5E5"><td><?echo $TiempoDispo?>%</td><td>...</td><td>...</td><td><?echo $NoCitasProgram?></td><td><?echo $NoCitasReal?></td><td><?echo $NoCitasNoReal?></td></tr>
</table>
<tr>
<td colspan="3"><center>
<input type="Button" value="Cerrar" onClick="location.href='/salud/Portada.php?DatNameSID=<? echo $DatNameSID?>'">
<input type="Button" value="Reporte" onClick="open('RptAgendaDiaria.php?DatNameSID=<? echo $DatNameSID?>&FechaAct=<?echo "$AnioCalend-$MesCalend-$DiaCalend"?>','','')">
<input type="Button" value="Buscar" onClick="open('BuscarPacienteAgenda.php?DatNameSID=<? echo $DatNameSID?>','','width=560,height=290,left=100,scrollbars=yes,menubars=yes')"><br>
<input type="button" value="Festivos">
<input type="button" value="Sobre Cupos">
<?
	if($usuario[1]=="Direccion Cientifica")
	{?>
<input type="Button" value="x Horario" onClick="open('CancelaHorario.php?DatNameSID=<? echo $DatNameSID?>&Medico=<?echo $SelMedico?>&Mes=<?echo $MesCalend?>&Dia=<?echo $DiaCalend?>&Anio=<?echo $AnioCalend?>','','width=500,height=100,left=140,top=200')">
<?
	}
?>
</td>
</tr>
</table>

<iframe style="position:absolute;top:10px;right:30px;width:380px;background:transparent;height:380px" src="ListaPacxMedico.php?DatNameSID=<? echo $DatNameSID?>?DatNameSID=<? echo $DatNameSID?>&Dia=<? echo $DiaCalend ?>&Mes=<? echo $MesCalend?>&Anio=<?echo $AnioCalend?>&Medico=<?echo $SelMedico?>#1" scrolling=vertical marginwidth=0 marginheight=0 frameborder=0></iframe>

<input type="Hidden" name="DiaCalend" value=<?php echo $DiaCalend?>>

</form>
</body>
</html>
