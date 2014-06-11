<?
	session_start();
	mysql_select_db("salud", $conex);
	$ND=getdate();
	if($MarcarHC)
	{
		$cons="select FechaIng,FechaEgr from hospitalizacion where cedula='$Paciente[1]' and IdHospitalizacion=$MarcarHC";
		$res=ExQuery($cons);
		$fila=ExFetch($res);
		$PeriodoxFormatos="$fila[0]|$fila[1]";
	}
?>
<style>
</style>
<body background="/Imgs/Fondo.jpg">
<?
	if($NumCed){$Paciente[1]=$NumCed;}
	if($usuario[1]=='Estadistica' || $usuario[1]=='siau')
	{
		echo "<center><input type='Button' value='Imprimir Registro' onclick=open('Reportes/RegistrosIngreso.php','','menubar=no,scrolling=yes,left=0,top=0,height=580,width=800')></center><br>";
	}

	$cons="select * from hospitalizacion where cedula='$Paciente[1]' Order By IdHospitalizacion Desc";
	$resultado=ExQuery($cons);
	while($fila=ExFetch($resultado))
	{
		echo "<center><table border=1 cellspacing=0 cellpadding=2 style='font : normal normal small-caps 11px Tahoma;' width='100%' >";
		if($fila[7]=='0000-00-00'){$msj='HOSPITALIZACION ACTUAL';}else{$msj='HOSPITALIZACION - EGRESO';}
		echo "<tr bgcolor='#CCCCCC'><td colspan=5><font color='black'><strong><center>$msj ";
		if($MarcarHC==$fila[0]){$check=" checked ";}else{$check="";}
		echo "<input type='Checkbox' $check onclick=location.href='DatosHospitalizacion.php?MarcarHC=$fila[0]'></td></tr>";
		if($fila[7]=='0000-00-00')
		{
			echo "<tr><td colspan=4><center>";
			if($usuario[1]=='TrSocial' || $usuario[1]=='siau')
			{
				if($fila[27]=='Sin Entidad')
				{
					echo "<input type='Button' value='Asignar Entidad' class='BotonSel1' onclick=location.href='SelEntidad.php?Tipo=1'>";
				}
				else
				{
					echo "<input type='Button' value='Cambiar Entidad' class='BotonSel1' onclick=location.href='SelEntidad.php?Tipo=1'>";
					echo "<input type='Button' value='Finalizar periodo' class='BotonSel1' onclick=location.href='SelEntidad.php?Tipo=2'>";
				}
			}
			echo "</td></tr>";
		}
		echo "<tr style='color:black' bgcolor='#E8E8E8'><td><b>Ingreso</b></td><td><b>Egreso</b></td><td><b>Psiquiatra</b></td><td><b>Entidad</b></td></tr>";
		echo "<tr style='color:black'><td>$fila[3]</td><td>$fila[7]</td>
		<td>$fila[14]</td><td>$fila[27]</td>
		<tr><td colspan=4><font color='black'><b>Dx:</b> </font><font color='black'>$fila[6]</td></tr>
		</table><br>";
		$cons2="Select * from pacientesxpabellones where cedula='$Paciente[1]' and IdHospitalizacion=$fila[0]";
		$res2=ExQuery($cons2);
		$TotRec=mysql_num_rows($res2);
		echo "<table border=1 cellspacing=0 style='font : normal normal small-caps 11px Tahoma;'>";
		echo "<tr style='color:black'><td><b>Unidad</b></td><td><b>Ingreso</b></td><td><b>Egreso</b></td><td><b>To Estancia</b></td><td><b>Estado</b></td><tr>";
		while($fila2=ExFetch($res2))
		{
			$i++;
			if($fila2[4]=='A'){$Est="<font color='black'>Hospitalizado</font>";}
			else{$Est="<font color='blue'>x Fuera</font>";}
			
			
			$date1="$fila2[7] $fila2[8]";
			$mdate=date("m");
			$ddate=date("d");
			if($fila2[9]=='0000-00-00'){$fila2[9]="$ND[year]-$mdate-$ddate";$fila2[10]="$ND[hours]:$ND[minutes]";}
			$date2="$fila2[9] $fila2[10]";
			$s = strtotime($date2)-strtotime($date1);
			$d = intval($s/86400);
			$s -= $d*86400;
			$h = intval($s/3600);
			$s -= $h*3600;
			$m = intval($s/60);
			$s -= $m*60;
			$dif= (($d*24)+$h).hrs." ".$m."min";
			$dif2= $d.$space . " dia(s)";

			if($Fondo==1){$BG="#EFEFEF";$Fondo=0;}
			else{$BG="#CCCCCC";$Fondo=1;}

			echo "<tr bgcolor='$BG' style='color:black'><td>$fila2[2]</td><td>$date1</td>";
			if($fila2[4]!='A'){echo "<td>$date2</td>";}
			else{echo "<td>00-00-0000</td>";}
			
			echo "<td>$dif2</td><td>$Est</td>";
		}
		echo "</table><br><hr><br>";
	}
?>
</body>