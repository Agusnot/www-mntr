<?
	session_start();
	include("Funciones.php");
	mysql_select_db("Central", $conex);
	$ND=getdate();
	$FechaAct="$ND[year]-$ND[mon]-$ND[mday]";
	$HoraAct=$ND[hours];
?>
<style>
	a{
		color:black;
		text-decoration:none;
	}
	a:hover
	{
		color:blue;
		text-decoration:underline;
	}
</style>

<body background="/Imgs/Fondo.jpg">
<table border="1" cellspacing="0" style="font-size:12px;" width="100%">
<tr bgcolor="#DFDFDF" style="font-weight:bold;text-align:center"><td>Hr Ini</td><td>Nombre</td><td>Fac</td><td>H Fin</td></tr>
<?
	$RT=strtotime("$Anio-$Mes-$Dia");
	$NF=getdate($RT);
	$DiaSem=$NF[wday];
	if($DiaSem==1){$Camp="HrsLun";}
	if($DiaSem==2){$Camp="HrsMar";}
	if($DiaSem==3){$Camp="HrsMie";}
	if($DiaSem==4){$Camp="HrsJue";}
	if($DiaSem==5){$Camp="HrsVie";}
	if($DiaSem==6){$Camp="HrsSab";}
	if($Camp){
	$cons="Select $Camp from Medicos where Nombre='$Medico'";
	$res=mysql_query($cons);
	$fila=ExFetch($res);
	$ResHora=$fila[0];
	$HagaA="S";$HagaB="N";
	$Cond1='($hrs>=';
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
	$Cond1='if(mysql_num_rows($res)==0 && ';
	for($n=1;$n<=$x;$n++)
	{
		$n1=$n+1;
		$Cond1=$Cond1 . '($hrs>=' . $HoraFilt[$n]. ' && $mins>=' . $Mins[$n]. ' || $hrs>' . $HoraFilt[$n] . ')&&($hrs<=' . $HoraFilt[$n1] . ' && $mins<=' . $Mins[$n1] . ' || $hrs<' . $HoraFilt[$n1] . ')';
		$n++;
		if($n<$x){$Cond1=$Cond1 . '||';}
	}
	$Cond1=$Cond1 . '){return 1;}else{return 2;}';}
	for($hrs=7;$hrs<=17;$hrs++)
	{
		for($mins=0;$mins<=50;$mins=$mins+10)
		{
			$cons="Select PrimApe,SegApe,PrimNom,SegNom,HoraInicio,HoraFin,hour(HoraFin),minute(HoraFin),
			Terceros.Cedula,Fecha,Medico,Entidad,Estado,TipoConsulta,Telefono,Direccion,MotivoCanc,Factura,Agenda.Cargo
			from Agenda,Terceros 
			where Terceros.cedula=Agenda.Cedula and 
			Fecha='$Anio-$Mes-$Dia' and Medico='$Medico' and hour(HoraInicio)='$hrs' 
			and minute(HoraInicio)=$mins
			and (Estado!='C')";
			$res=mysql_query($cons);echo mysql_error();
			if($Fondo==1){$BG="#EEF6F6";$Fondo=0;}
			else{$BG="white";$Fondo=1;}
			if(strlen($hrs)<2){$hrs="0" . $hrs;}
			if(strlen($mins)<2){$mins="0" . $mins;}
			if($ResHora!=""){
			$Exist=eval($Cond1);}
			if($Exist==1 && mysql_num_rows($res)==0)
			{echo "<tr bgcolor='$BG'><td>";?> <a href="#" onclick="open('NuevaCita.php?Hora=<?echo $hrs?>&Min=<?echo $mins?>&Anio=<?echo $Anio?>&Mes=<?echo $Mes?>&Dia=<?echo $Dia?>&Medico=<?echo $Medico?>','','width=450,height=300,left=190,top=120')"><?
			echo "$hrs:$mins</a></td>";}
			while($fila=ExFetch($res))
			{
				$Ind++;
				$Hora1=$fila[5];
				$Hora2=$fila[4];$Cargo=$fila[18];
				$s = strtotime($Hora1)-strtotime($Hora2);
				$d = intval($s/86400);
				$s -= $d*86400;
				$h = intval($s/3600);
				$s -= $h*3600;
				$m = intval($s/60);
				$mins=$mins+$m-10;
				if($hrs<$fila[6]){$hrs=$fila[6];$mins=$fila[7]-10;}
				echo "<tr bgcolor='$BG'><td>" . substr($fila[4],0,5) . "<td>";
				if($fila[12]=="P")
				{
				?>
					<a name="1">
					<a href="#" title="<?echo $fila[11]?> - <?echo $fila[15]?> - <?echo $fila[14]?>"
					
					onclick="open('PreActivaPate.php?CedPaciente=<?echo $fila[8]?>&Fecha=<?echo $fila[9]?>&Hora=<?echo $fila[4]?>&Medico=<?echo $fila[10]?>&Entidad=<?echo $fila[11]?>&TipoAt=<?echo $fila[13]?>&Cargo=<?echo $Cargo?>','','width=300,height=320,left=230,top=100')">

			<? }
				if($fila[12]=="T"){echo "<font style='text-transform:uppercase'>";
					if($usuario[1]=="Gerencia"){?>
					<a href="#" onclick="open('ReactivarHorario.php?Fecha=<?echo $fila[9]?>&Hora=<?echo $fila[4]?>&Medico=<?echo $fila[10]?>','','width=200,height=40,left=230,top=200')">
				
<?				}echo "$fila[16]</a></font>";}
				echo "$fila[2] $fila[3] $fila[0] $fila[1]</td>";

				if($fila[17]!="SF" && $fila[17]!="0")
				{?>
					<td>
					<a href="#" onclick="open('/salud/Facturacion/ImpFacCredito.php?FacIni=<?echo $fila[17]?>&FacFinal=<?echo $fila[17]?>','','width=790,height=600,menubar=yes,scrollbars=yes')"><?echo $fila[17]?></td></a>
<?				}
				else
				{
					if($fila[17]=="SF")
					{?>
						<td><strong><center><a href="#" onclick="open('PreActivaPate.php?SoloGenera=1&CedPaciente=<?echo $fila[8]?>&Fecha=<?echo $fila[9]?>&Hora=<?echo $fila[4]?>&Medico=<?echo $fila[10]?>&Entidad=<?echo $fila[11]?>&TipoAt=<?echo $fila[13]?>&Cargo=<?echo $Cargo?>','','width=300,height=320,left=230,top=100')"><font color='#ff0000'>SF</a></td>
<?					}
					else{echo "<td>.</td>";}
				}
				echo "<td>"  . substr($fila[5],0,5)?>
<?				echo "</a></td>";
			}
		}
	}
?>
</table>
</body>