<?
	if($DatNameSID){session_name("$DatNameSID");}
	session_start();
	include("Funciones.php");
	$ND=getdate();
	if($ND[mon]<10){$ND[mon]="0".$ND[mon];}
	if($ND[mday]<10){$ND[mday]="0".$ND[mday];}
	$FechaHoy=$ND[year]."-".$ND[mon]."-".$ND[mday];
	if($ND[hours]<10){$ND[hours]="0".$ND[hours];}
	if($ND[minutes]<10){$ND[minutes]="0".$ND[minutes];}
	if($ND[seconds]<10){$ND[seconds]="0".$ND[seconds];}
	$HoraHoy=$ND[hours].":".$ND[minutes].":".$ND[seconds];
	if($Paciente[48]!=$FechaHoy){echo "<em><center><br><br><br><br><br><font size=5 color='BLUE'>La Hoja de Identificacion no se ha guardado!!!";exit;}	
	$SexoPaciente=$Paciente[24];
	if($SexoPaciente=="M")
	{
		echo "<center><font face='Tahoma' color='#0066FF' size='+2' ><b>La Historia Clinica Materno-Perinatal Aplica Solo Para Mujeres!!!</b></font></center>";		
		exit;	
	}
	$cons="Select numservicio from Salud.Servicios where Compania='$Compania[0]' and Cedula='$Paciente[1]' and estado='AC'";
	$res=ExQuery($cons);
	$fila=ExFetch($res);
	$NumServicio=$fila[0];
	$SexoPaciente=$Paciente[24];
	if($SexoPaciente=="M")
	{
		echo "<center><font face='Tahoma' color='#0066FF' size='+2' ><b>La Historia Clinica Materno-Perinatal Aplica Solo Para Mujeres!!!</b></font></center>";		
		exit;	
	}
	$cons="select idclap,identificacion,fechacrea,Preantecedentes,Antecedentes from historiaclinica.claps where Compania='$Compania[0]' and Identificacion='$Paciente[1]'
	and estado='AC' order by idclap desc";
	$res=ExQuery($cons);
	$fila=ExFetch($res);
	if(!$fila)
	{			
		if(!$NumServicio)
		{
			echo "<center><font face='Tahoma' color='#0066FF' size='+2' ><b>La Paciente no tiene Servicios Activos!!! </b></font></center><br>";
			exit;	
		}
		else
		{?>
        	<center>
			<font face='Tahoma' color='#0066FF' size='+1' ><b>
            En este momento No Existe Hoja de Historia Materno-Perinatal (CLAP) Activa!!!<br>            
            <script language="javascript">location.href="Antecedentes.php?DatNameSID=<? echo $DatNameSID?>";</script>
		<?
			exit;
        }		
	}
	elseif(!$fila[4])
	{
		echo "<center><font face='Tahoma' color='#0066FF' size='+2' ><b>No se han Registrado los datos de los Antecedentes de la Paciente para continuar con esta Hoja!!! </b></font><br>";	
		exit;
	}
	
?>