<?
	if($DatNameSID){session_name("$DatNameSID");}
	session_start();
	include("Funciones.php");
	$ND=getdate();	
	switch($ND[wday])
	{
		case 0: $DiaSemana="Domingo";
				break;	
		case 1: $DiaSemana="Lunes";
				break;	
		case 2: $DiaSemana="Martes";
				break;	
		case 3: $DiaSemana="Miercoles";
				break;	
		case 4: $DiaSemana="Jueves";
				break;	
		case 5: $DiaSemana="Viernes";
				break;	
		case 6: $DiaSemana="Sabado";
				break;	
		default:$DiaSemana="";
				break;			
	}
	$cons="Select Numero,Mes from Central.Meses order by numero";
	$res=ExQuery($cons);
	while($fila=ExFetch($res))
	{
		$Meses[$fila[0]]=$fila[1];
	}
	for($m=13;$m<24;$m++)
	{
		$HoraPM[$m]=($m-12);
	}
	function hex2bin($hexdata)
	{
		for ($i=0;$i<strlen($hexdata);$i+=2){ 
		$bindata.=chr(hexdec(substr($hexdata,$i,2)));}
		return $bindata; 
	}

	$DatosLic=explode("\r\n",$contenido);
	$Entidad="Clinica San Jose - Cali";;
	$NoId=strtoupper(hex2bin($DatosLic[1]));
	$NoLic=strtoupper(hex2bin($DatosLic[2]));
?><head>
	<meta http-equiv="refresh" content="60">
</head>

<style>
a{color:white;text-decoration:none;}
a:hover{color:yellow;}
</style>
<?	if($NoSistema==0){?>
<body bgcolor="#666699" background="/Imgs/encabezado.jpg"><?	}
else{?>
<body bgcolor="#666699"><?	}?>
<font size="5" color="yellow">
<em>
<? echo $Sistema[$NoSistema]?></em>
</font>
<label style="text-align:right; position:absolute; right:5px"><? echo "<font size='1' color='yellow' style='text-align:rigth'>$DiaSemana, $ND[mday] de ".$Meses[$ND[mon]]." de $ND[year]</font>";?></label>
<br>
<font size="1" face="Tahoma" color="#ffffff">
<font style="text-transform:uppercase" color="#ffff00">Usuario Valido : <?echo $usuario[0]?></font>
<label style="text-align:right; position:absolute; right:5px; top:20px">
<? if($ND[hours]==12){
		$Horas[0]=$ND[hours];
		$Horas[1]="M.";
   }elseif($ND[hours]>12){ 
		if($HoraPM[$ND[hours]]<10){
			$HoraPM[$ND[hours]]="0".$HoraPM[$ND[hours]];
		}
		$Horas[0]=$HoraPM[$ND[hours]];
		$Horas[1]="P.M.";
	}else{
		if($ND[hours]<10){
			$ND[hours]="0".$ND[hours];
		}
		$Horas[0]=$ND[hours];
		$Horas[1]="A.M.";
	}
if($ND[minutes]<10){$ND[minutes]="0".$ND[minutes];}
 echo "<font size='1' color='yellow' style='text-align:rigth'>$Horas[0]:$ND[minutes] $Horas[1]  </font></BR>";

 if($ND[hours]>=1 && $ND[hours]<13){
	 $dia_anterior=$ND[mday]-1;
	 $mes_anterior=$ND[mon];
	 $anio_anterior=$ND[year];
	  echo "<font size='1' color='yellow' style='text-align:rigth'>$anio_anterior-$mes_anterior-$dia_anterior $Horas[1]</font></BR>";
	 if ($dia_anterior==0 && $ND[mon]==1){
		 $mes_anterior=12;
		 $dia_anterior=31;
		 $anio_anterior=$ND[year]-1;
		 echo "<font size='1' color='yellow' style='text-align:rigth'>ahora $anio_anterior-$dia_anterior-$dia_anterior $Horas[1]</font></BR>";
	 }
	 if ($dia_anterior==0 && $ND[mon]==2){
		 $mes_anterior=1;
		 $dia_anterior=31;
	 }
	 if ($dia_anterior==0 && $ND[mon]==3){
		 $mes_anterior=2;
		 $dia_anterior=28;
	 }
	 if ($dia_anterior==0 && $ND[mon]==4){
		 $mes_anterior=3;
		 $dia_anterior=31;
	 }
	 if ($dia_anterior==0 && $ND[mon]==5){
		 $mes_anterior=4;
		 $dia_anterior=30;
	 }
	 if ($dia_anterior==0 && $ND[mon]==6){
		 $mes_anterior=5;
		 $dia_anterior=31;
	 }
	 if ($dia_anterior==0 && $ND[mon]==7){
		 $mes_anterior=6;
		 $dia_anterior=30;
	 }
	 if ($dia_anterior==0 && $ND[mon]==8){
		 $mes_anterior=7;
		 $dia_anterior=31;
	 }
	 if ($dia_anterior==0 && $ND[mon]==9){
		 $mes_anterior=8;
		 $dia_anterior=31;
	 }
	 if ($dia_anterior==0 && $ND[mon]==10){
		 $mes_anterior=9;
		 $dia_anterior=30;
	 }
	 if ($dia_anterior==0 && $ND[mon]==11){
		 $mes_anterior=10;
		 $dia_anterior=31;
	 }
	 if ($dia_anterior==0 && $ND[mon]==12){
		 $mes_anterior=11;
		 $dia_anterior=30;
	 }
	 //echo "<font size='1' color='yellow' style='text-align:rigth'>$dia_anterior</font></BR>";
	 //se buscan todas las ordenes que fueron reprogramadas el día anterior.
	$cons="select fechareprog,cedula,numservicio,tipoorden,detalle,ordenesmedicas.idescritura,ordenesmedicas.numorden,autoid 
	 from salud.ordenesmedicas, salud.HoraCantidadXMedicamento
	 where fechareprog>='$anio_anterior-$mes_anterior-$dia_anterior 00:00:00'
	 and fechareprog<='$ND[year]-$ND[mon]-$ND[mday]'
	 and cedula=paciente
	 and ordenesmedicas.idescritura=HoraCantidadXMedicamento.idescritura
	 and ordenesmedicas.numorden=HoraCantidadXMedicamento.numorden
	 ";
	 $res = ExQuery($cons);
	 while ($fila=ExFetch($res))
		{
		// cambiar la fecha de inicio y colocarla activa la orden reprogramada en plantillamedicamentos
		$cons2="update salud.plantillamedicamentos set fechaini='$ND[year]-$ND[mon]-$ND[mday]',estado='AC' where fechaformula='$fila[0]'and cedpaciente='$fila[1]' 
		and numservicio='$fila[2]' and tipomedicamento='Medicamento Programado';";
		$res2 = ExQuery($cons2);
		//colocar activa la nueva orden medica en horas de la madrugada
		$cons5="update salud.ordenesmedicas set estado='AC' where fecha='$fila[0]' and cedula='$fila[1]' and numservicio='$fila[2]';";
		$res5 = ExQuery($cons5);
		// desactivar la orden anterior a la reprogramacion
		$cons3="update salud.ordenesmedicas set estado='AN' where fecha<'$fila[0]' and cedula='$fila[1]' and numservicio='$fila[2]' and detalle='$fila[4]';";
		$res3 = ExQuery($cons3);
		// desactivar la plantilla anterior
		$cons4="update salud.plantillamedicamentos set estado='AN' where fechaformula<'$fila[0]' and cedpaciente='$fila[1]' and numservicio='$fila[2]' and detalle='$fila[4]';";
		$res4 = ExQuery($cons4);
		
		$cons6="update salud.HoraCantidadXMedicamento set estado='AC',fecha='$ND[year]-$ND[mon]-$ND[mday]'
		where fecha='$fila[0]' and paciente='$fila[1]' and idescritura='$fila[5]' and autoid='$fila[7]' and numorden='$fila[6]' and tipo='P';";
		$res6 = ExQuery($cons6);
		
		$cons7="update salud.HoraCantidadXMedicamento set estado='AN' where fecha<'$fila[0]' and paciente='$fila[1]' and autoid='$fila[7]' and tipo='P';";
		$res7 = ExQuery($cons7);
	}
 }
 
 
 ?></label>
<br>
Licenciado a
<strong><?echo $Entidad?> - <?echo $NoId?>
 - Release <font color="#ffff00"> PgSql
<?
		echo date("YmdHs", filemtime("ValidarArchivos.php"));;
?></font>
<br>Licencia No. 20120401 <? echo $NoLic ?></strong>

<br>
<font size="2" face="Tahoma" color="#ffffff">
<?	
	$cons = "Select InstruccionSQL,MsjAlerta,Estado,Archivo,Id from Alertas.AlertasProgramadas where Compania='$Compania[0]' and estado='Activo'";
	//echo $cons;
	$res = ExQuery($cons);
	while ($fila=ExFetch($res))
	{
		$cons3="select usuario from alertas.usuariosxalertas where compania='$Compania[0]' and idalerta=$fila[4]";
		$res3=ExQuery($cons3);
		if(ExNumRows($res3)>0){
			$BanUsus=1;
			$cons4="select usuario from alertas.usuariosxalertas where compania='$Compania[0]' and idalerta=$fila[4] and usuario='$usuario[1]'";
			$res4=ExQuery($cons4);
			if(ExNumRows($res4)>0){$BanUsuSi=1;}
		}
		$cons2="SELECT Id from Alertas.AlertasxModulos,Central.UsuariosxModulos where AlertasxModulos.Modulo=UsuariosxModulos.Modulo and AlertasxModulos.Id=$fila[4] 
		and UsuariosxModulos.Usuario='$usuario[1]' and Alertas.AlertasxModulos.Compania='$Compania[0]'";
		//echo $cons2;
		$res2=ExQuery($cons2);
		if(ExNumRows($res2)>0)
		{	
			$cons1=str_replace("|","'",$fila[0]);			
			$cons1=str_replace("[COMPANIA]","$Compania[0]",$cons1);
			$cons1=str_replace("[FEC_ACTUAL]","$ND[year]-$ND[mon]-$ND[mday]",$cons1);
			$cons1=str_replace("[USU]","$usuario[1]",$cons1); //echo $cons1."<br>";						
			$cons1=str_replace("+","||",$cons1);
			$res1=ExQuery($cons1);
			if(!$fila[3]){$fila[3]="ModOpciones.php";}
			else{$fila[3]=$fila[3]."?DatNameSID=$DatNameSID";}
			if(ExNumRows($res1)>0){
				if($BanUsus==1){
					if($BanUsuSi==1){$Msj=$Msj." <a href='$fila[3]' target='Derecha'>&nbsp;$fila[1]</a> ";}
				}
				else{
					$Msj=$Msj." <a href='$fila[3]' target='Derecha'>&nbsp;$fila[1]</a> ";
				}
			}			
		/*	if($fila[4]==">"){if(ExNumRows($res1)>$fila[5]&&$fila[2]=='Activo'){$Msj=$Msj."<a href='$fila[3]' target='Derecha'>* $fila[1] *</a>";}}
			if($fila[4]=="<"){if(ExNumRows($res1)<$fila[5]&&$fila[2]=='Activo'){$Msj=$Msj."<a href='$fila[3]' target='Derecha'>$fila[1]</a>";}}
			if($fila[4]=="=="){if(ExNumRows($res1)==$fila[5]&&$fila[2]=='Activo'){$Msj=$Msj."<a href='$fila[3]' target='Derecha'>$fila[1]</a>";}}
			if($fila[4]=="<>"){if(ExNumRows($res1)!=$fila[5]&&$fila[2]=='Activo'){$Msj=$Msj."<a href='$fila[3]' target='Derecha'>$fila[1]</a>";}}
			if($fila[4]==">="){if(ExNumRows($res1)>=$fila[5]&&$fila[2]=='Activo'){$Msj=$Msj."<a href='$fila[3]' target='Derecha'>$fila[1]</a>";}}
			if($fila[4]=="<="){if(ExNumRows($res1)<=$fila[5]&&$fila[2]=='Activo'){$Msj=$Msj."<a href='$fila[3]' target='Derecha'>$fila[1]</a>";}}*/
		}
		$BanUsuSi="";
	}
	if($Msj){?>    	
			<marquee SCROLLDELAY="155"><? echo $Msj?></marquee>        
<? }
?>
</font>

	
	