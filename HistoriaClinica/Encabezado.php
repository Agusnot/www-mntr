<?
	if($DatNameSID){session_name("$DatNameSID");}
	session_start();
	include("Funciones.php");
	$ND=getdate();
	
	$cons="select unidad,idcama,nombre from salud.camasxunidades where compania='$Compania[0]'";
	$res=ExQuery($cons);
	while($fila=ExFetch($res))
	{
		$Camas[$fila[0]][$fila[1]]=$fila[2];
		//echo "$fila[0] qqq $fila[1] === $fila[2] <br>";
	}
	if($ND[mon]<10){$cero1='0';}else{$cero1='';}
	if($ND[mday]<10){$cero2='0';}else{$cero2='';}	
	$FechaComp="$ND[year]-$cero1$ND[mon]-$cero2$ND[mday]";
	//header ('refresh:3; url=/HistoriaClinica/Encabezado.php');
	$consult="select hrsini,minsini,cedula,id,numservicio,estado from salud.agenda where compania='$Compania[0]' and medico='$usuario[1]' and estado='Activa' and fecha='$FechaComp'";
	//echo $consult;	
	//echo "$ND[hours] $ND[minutes]";
	$result=ExQuery($consult);
	while($row=ExFetch($result)){	
		//echo "$row[0] $row[1]" ;
		if($ND[hours]==$row[0]){	
			if($ND[minutes]>=($row[1]-5)&&$ND[minutes]<=$row[1]){
				//echo "<br>$row[0]:$row[1] $ND[hours]:$ND[minutes]";				
				$MSJ="Tiene pacientes en sala de espera";			
				//echo $MSJ;
			}
		}
		else{
			if($row[0]==($ND[hours]+1)){			
							
				if($ND[minutes]>=55&&$row[1]<=5){
					$dif1=60-$ND[minutes];
					
					//echo "<br>$row[0]:$row[1] $ND[hours]:$ND[minutes]";	
					$dif=$dif1+$row[1];
					//echo "<br>$dif1  $dif2 $dif3";					
					if($dif<=5){
						$MSJ="Tiene pacientes en sala de espera";
					}
				}
			}
		}
	}
	
?>

<html>
<head>
<meta http-equiv="refresh" content="300">
<style type="text/css">
<!--
a{color:black;text-decoration:none;}
a:hover{color:yellow}
<?
	if($NoSistema!=1){
?>
body{background-image: url(/Imgs/encabezado.jpg); background-repeat:no-repeat;}<?	}?>
</style>
</head>
<body>
<div style="font-size:12px; padding-top:5px; color:#CCCCCC; font-family:Tahoma, Geneva, sans-serif;"><img src="/Imgs/3.png" style="float:left; width: 18px; padding-left: 20px; padding-right: 5px;">USUARIO: <span style="color:#FFFFFF; font-weight:bold;"><?php echo $usuario[1];?></span>
</div>
<table width="100%" style="color:yellow;font-size:11px;text-align:justify;font-family: Tahoma;position:absolute;top:1px;text-align:center" cellspacing="0" >
<?	if($Pacie){$Paciente[1]=$Pacie;
		$cons="select identificacion,primape,segape,primnom,segnom,eps from central.terceros $Serv $U $M2
		where terceros.compania='$Compania[0]' and tipo ='Paciente' and identificacion='$Pacie' group by PrimApe,SegApe,PrimNom,SegNom,identificacion,eps
		Order By PrimApe,SegApe,PrimNom,SegNom";
		$res=Exquery($cons);
		$fila=ExFetch($res);
		$Paciente[1]=$fila[0];
		$n=1;
		for($i=1;$i<=ExNumFields($res);$i++)
		{
			$n++;
			$Paciente[$n]=$fila[$i];
		}
		session_register("Paciente");
	}
	
	if(!$Paciente[1]){echo "<tr><td><center><font size=4><font color='red'><strong><br>NO HAY PACIENTE SELECCIONADO</center></font>";exit;}
		
	$cons="select eps from central.terceros where terceros.compania='$Compania[0]' and terceros.identificacion='$Paciente[1]'";	
	$res=ExQuery($cons);
	$fila=ExFetch($res); 
	
	$cons="select fecnac from central.terceros where compania='$Compania[0]' and identificacion='$Paciente[1]'";
	$res=ExQuery($cons);
	$fila=ExFetch($res);
	$Paciente[23]=$fila[0];
	if($Paciente[23]){
		$Edad=ObtenEdad($Paciente[23]);
	}
	
	$cons="select primape,segape,primnom,segnom,pagadorxservicios.entidad from central.terceros,salud.pagadorxservicios,salud.servicios
	where terceros.compania='$Compania[0]' and pagadorxservicios.compania='$Compania[0]' and servicios.compania='$Compania[0]' 
	and servicios.cedula='$Paciente[1]' and terceros.identificacion = pagadorxservicios.entidad and pagadorxservicios.numservicio=servicios.numservicio 
	and '$FechaComp'>=fechaini and (fechafin>='2011-04-20' or fechafin is null) order by fechaini desc,pagadorxservicios.tipo asc";	
	//echo $cons;
	$res=ExQuery($cons);
			
	if(ExNumRows($res)>0){
		while($fila=ExFetch($res))
		{
			if($fila[0]!=''||$fila[1]!=''||$fila[2]!=''||$fila[3]!=''){
				//$Asegurador="$fila[0] $fila[1] $fila[2] $fila[3]";
				$Asegurador[$fila[4]]="$fila[0] $fila[1] $fila[2] $fila[3]";
			}
		}
	}
	else{			
		$cons3="select primape,segape,primnom,segnom,fechafin,pagadorxservicios.entidad from central.terceros,salud.pagadorxservicios,salud.servicios
		where terceros.compania='$Compania[0]' and pagadorxservicios.compania='$Compania[0]' and servicios.compania='$Compania[0]' and servicios.cedula='$Paciente[1]' and 
		terceros.identificacion = pagadorxservicios.entidad and pagadorxservicios.numservicio=servicios.numservicio and '$FechaComp'>=fechaini";	
		
		$res3=ExQuery($cons3);	
		if(ExNumRows($res3)>0){				
			while($fila3=ExFetch($res3))
			{
				if(!$fila3[4]){					
					if($fila3[0]!=''||$fila3[1]!=''||$fila3[2]!=''||$fila3[3]!=''){
						//$Asegurador="$fila3[0] $fila3[1] $fila3[2] $fila3[3]";
						$Asegurador[$fila[5]]="$fila[0] $fila[1] $fila[2] $fila[3]";
					}					
				}
			}
		}
		else{
			$Asegurador='';
		}
	}		
	
	$cons="select tiposervicio,numservicio,medicotte,dxserv,fechaing,clinica
	from salud.servicios where servicios.estado='AC' and servicios.cedula='$Paciente[1]'";
	$res=ExQuery($cons);
	$fila=ExFetch($res);

	if($fila[1]){
		$cons0004="select dx1
		from histoclinicafrms.tbl00004 where numservicio=$fila[1] and cedula='$Paciente[1]' and dx1<>'' and cargo='PSIQUIATRA' ORDER BY fecha desc,hora desc limit 1";
		$res0004=ExQuery($cons0004);
		$fila0004=ExFetch($res0004);
	}
	//echo $cons0004;
	$Ambito=$fila[0];
	$NumServ=$fila[1];
	$DiaI=$fila[4];
	$Clinica=$fila[5];
	$medtratante=$fila[2];
	if(ExNumRows($res0004)>0)
        $consDx="select diagnostico,codigo from salud.cie where codigo='$fila0004[0]'";
    else
        $consDx="select diagnostico,codigo from salud.cie where codigo='$fila[3]'";
	$resDx=ExQuery($consDx);
	$filaDx=ExFetch($resDx);
	//echo $consDx;
	if($DiaI){
		$FecIng=explode(" ",$DiaI);
		$FI=explode("-",$FecIng[0]);
		$timestamp1 = mktime(0,0,0,$FI[1],$FI[2],$FI[0]); 
		$timestamp2 = mktime(4,12,0,$ND[mon],$ND[mday],$ND[year]); 
		$segundos_diferencia = $timestamp1 - $timestamp2; 
		$dias_diferencia = $segundos_diferencia / (60 * 60 * 24); 
		$dias_diferencia = abs($dias_diferencia); 
		$DiasEstancia=$dias_diferencia = floor($dias_diferencia); 
	}
	$consM="select Nombre from central.usuarios,salud.medicos,salud.cargos
	where usuarios.usuario='$fila[2]' and medicos.usuario=usuarios.usuario and medicos.compania='$Compania[0]'
	and cargos.cargos=medicos.cargo and cargos.asistencial=1";
	$resM=ExQuery($consM);
	$filaM=ExFetch($resM);
	if(ExNumRows($resM)==0)
	{
		$filaM=0;
	}
	if($NumServ&&$Ambito){
		$cons="Select Pabellon,idcama from Salud.PacientesxPabellones
		where PacientesxPabellones.cedula='$Paciente[1]' and PacientesxPabellones.Estado='AC' and numservicio=$NumServ
		and PacientesxPabellones.compania='$Compania[0]' order by numservicio asc";
		$res=ExQuery($cons);
		$fila=ExFetch($res);
		$Unidad=$fila[0];
		$Cama=$fila[1]; //$AmbCama=$fila[2];
		//echo $cons."<br>";
	}
	
	echo "<tr style='text-transform:uppercase;'>
	<td colspan=6><strong><font size=3>$Paciente[2] $Paciente[3] $Paciente[4] $Paciente[5] - $Paciente[1]</strong></font><br>";
	if($Asegurador!=''){?>
		<strong>Asegurador :</strong>        
<?		$ban=0;
		foreach($Asegurador as $Aseg)
		{
			if($ban==0){
				$AuxAseg=$Aseg;
				$ban=1;
			}
			else{
				$AuxAseg=$AuxAseg." - ".$Aseg;
			}
		}
		echo "$AuxAseg";
	}
	if($Paciente[23]){
		echo "<br><strong>Edad :</strong> &nbsp;$Edad a&ntilde;os";
	}
	if($filaDx[0])
	{
		echo "<strong> Dx :</strong> $filaDx[1] - ".substr($filaDx[0],0,60);
	}
	if($Ambito!=''){
		echo "<br><strong>Proceso :</strong> &nbsp;$Ambito";
		//if ($DiasEstancia>=10 && $Ambito!='Consulta Externa' && $Ambito!='Hospital Dia'&& $medtratante!='lestancia'){ echo '<SCRIPT>	javascript:alert("ALERTA: Paciente con 10 o mas dias de Estancia,     \nPor Favor Realizar Comite Tecnico Cientifico. ");</SCRIPT>';}
		if ($DiasEstancia>=10 && $Ambito!='Consulta Externa' && $Ambito!='Hospital Dia'&& $medtratante!='lestancia'){
            //echo '<SCRIPT>javascript:alert("ALERTA: Paciente con 10 o mas dias de Estancia,     \nPor Favor Realizar Comite Tecnico Cientifico. ");</SCRIPT>';
            $estanciapasada="ATENCIÓN: Paciente con 10 o más días de Estancia * ";
        }
		if($DiasEstancia){ echo " ($DiasEstancia dias)";}
	}
	if($Unidad&&$NumServ){
		if(substr($Compania[1],4)=="890801495-9")
		{
			echo " &nbsp;&nbsp;&nbsp;<strong>Servicio :</strong> &nbsp;$Unidad ";
		}
		else
		{
			echo " &nbsp;&nbsp;&nbsp;<strong>Unidad :</strong> &nbsp;$Unidad ";		
		}
	}
	if($Camas[$Unidad][$Cama]){
		echo " &nbsp;&nbsp;&nbsp;<strong>Cama :</strong> &nbsp;".$Camas[$Unidad][$Cama];		
	}
	if($Clinica){
		echo " &nbsp;&nbsp;&nbsp;<strong>Clinica :</strong> &nbsp;".$Clinica;		
	}
	if($filaM[0]){
		echo "&nbsp;&nbsp;<strong>Medico: </strong>&nbsp;$filaM[0]";
	}
		
	if(!$MSJ){echo "<br>";}?>
			
 <table border="0" style="color:white;font-size:11px;text-align:justify;font-family: Tahoma;" cellspacing="0" width="100%" align="center">
 <tr align="center">
 <? /*
	
 	<td onClick="open('/HistoriaClinica/HistoriaClinica.php?DatNameSID=<? echo $DatNameSID?>&Reabrir=1&Pacie=<? echo $Paciente[1]?>','','width=1200,height=800')" style="cursor:hand">	*/?>
   
	<td><a href="#" style="cursor:hand; color:white" onClick="open('/HistoriaClinica/HistoriaClinica.php?DatNameSID=<? echo $DatNameSID?>&Pacie=<? echo $Paciente[1]?>','','width=800,height=600,scrollbars=YES')">REABRIR HISTORIA</a></td>
    <td><a href="#" style="cursor:hand; color:white" onClick="open('/HistoriaClinica/ListarServicios.php?DatNameSID=<? echo $DatNameSID?>','','width=800,height=600,scrollbars=YES')">SERVICIOS</a></td>
    <td><a href="#" style="cursor:hand; color:white" onClick="open('/HistoriaClinica/SignosVitales.php?DatNameSID=<? echo $DatNameSID?>&NumServicio=<? echo $NumServ?>','','width=800,height=600,scrollbars=yes')"> SIGNOS VITALES</a></td>
    <td><a href="#" style="cursor:hand; color:white" onClick="open('/HistoriaClinica/ControlLiquidos.php?DatNameSID=<? echo $DatNameSID?>&NumServicio=<? echo $NumServ?>','','width=850,height=600')">CONTROL DE LIQUIDOS</a></td>
    <?
    if(is_file($_SERVER['DOCUMENT_ROOT']."/ArchivoFisico/$Paciente[1].pdf"))
	{
		$AF="/ArchivoFisico/$Paciente[1].pdf";
	}
	elseif(is_file($_SERVER['DOCUMENT_ROOT']."/ArchivoFisico/$Paciente[1].PDF"))
	{
		$AF="/ArchivoFisico/$Paciente[1].PDF";
	}
	if($AF)
	{
	?>
    <td ><a href="#" style="cursor:hand; color:white" onClick="parent.Datos.location.href='/ArchivoFisico/<? echo "$Paciente[1].pdf"?>'">ARCHIVO FISCO</a></td>
    <?
	}?>
    </tr><tr>
    <?	
	if($MSJ)
	{?>
    <strong style="cursor:hand" >
    <marquee SCROLLDELAY="155" style="cursor:hand" onClick="open('PacientesSalaEspera.php?DatNameSID=<? echo $DatNameSID?>&Medico=<? echo $usuario[1]?>&DeHC=1','','width=1100,height=600')"><? echo $MSJ?></marquee>
    </strong><?
	}else{echo "&nbsp;";}?></td></tr>		
        <?
	
?>
<?php
        // Para no tomar la fechafin
	/*$cons="select alertasingreso.fechaini,alertasingreso.fechafin,alertasingreso.alerta from salud.alertasingreso
	where alertasingreso.compania='$Compania[0]' and alertasingreso.cedula='$Paciente[1]'  group by alertasingreso.fechaini,alertasingreso.fechafin,alertasingreso.alerta 
	order by alertasingreso.alerta";*/
        $cons="select alertasingreso.fechaini,alertasingreso.alerta from salud.alertasingreso
	where alertasingreso.compania='$Compania[0]' and alertasingreso.cedula='$Paciente[1]' and alertasingreso.fechafin is null group by alertasingreso.fechaini,alertasingreso.alerta,alertasingreso.fecha 
	order by alertasingreso.fecha";
	//echo $cons;
	$res=ExQuery($cons);
        
        if(ExNumRows($res)>2){
            $alertapac="<marquee scrolldelay='155'>";
            $finalertapac="</marquee>";
        }
        else{
            $alertapac="";
            $finalertapac="";
        }
	while($fila=ExFetch($res)){
		//if($FechaComp>=$fila[0]&&$FechaComp<=$fila[1]){
		if($FechaComp>=$fila[0]){
                    $alertapac.="  *  ".$fila[1];
		}
	}
        $alertpac.=$finalertapac;
?>
</table>
<div style="padding-top: 20px; text-transform: uppercase; font-size: 12px; font-weight: bold; color: red; text-align: center;"><?php echo $estanciapasada; ?><span style="color:#FF5555;"><?php echo $alertapac; ?></span></div>
</body>
