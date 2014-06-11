<?php
	if($DatNameSID){session_name("$DatNameSID");}
	session_start();
	include("Funciones.php");
	$ND=getdate();	
	switch($ND[wday])
	{
		case 0: $DiaSemana="domingo";
				break;	
		case 1: $DiaSemana="lunes";
				break;	
		case 2: $DiaSemana="martes";
				break;	
		case 3: $DiaSemana="miercoles";
				break;	
		case 4: $DiaSemana="jueves";
				break;	
		case 5: $DiaSemana="viernes";
				break;	
		case 6: $DiaSemana="sabado";
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
	$Entidad="Clinica San Juan de Dios - Manizales";;
	$NoId=strtoupper(hex2bin($DatosLic[1]));
	$NoLic=strtoupper(hex2bin($DatosLic[2]));
?><head>
	<meta http-equiv="refresh" content="60">
        <link href="/css/modulos.css" type="text/css" rel="stylesheet">
</head>

<body>

<em>
<? echo $Sistema[$NoSistema]?></em>

<?php /*if($ND[hours]==12){
		$Horas[0]=$ND[hours];
		$Horas[1]="m.";
   }elseif($ND[hours]>12){ 
		if($HoraPM[$ND[hours]]<10){
			$HoraPM[$ND[hours]]="0".$HoraPM[$ND[hours]];
		}
		$Horas[0]=$HoraPM[$ND[hours]];
		$Horas[1]="p.m.";
	}else{
		if($ND[hours]<10){
			$ND[hours]="0".$ND[hours];
		}
		$Horas[0]=$ND[hours];
		$Horas[1]="a.m.";
	}*/
if($ND[hours]<10){$ND[hours]="0".$ND[hours];}
if($ND[minutes]<10){$ND[minutes]="0".$ND[minutes];}
?>

<div style="float:right; padding-right: 5px; padding-top: 10px; color: #cccccc; font-size: 11px; font-family: Tahoma, Geneva, sans-serif;">
    <?php echo strtoupper("$DiaSemana, $ND[mday] DE ".$Meses[$ND[mon]]." DE $ND[year]"." $ND[hours]:$ND[minutes]");?>
</div>
<div style="font-size: 11px; padding-top: 10px; color: #CCCCCC; font-family: Tahoma, Geneva, sans-serif;"><img src="/Imgs/3.png" style="float:left; width: 18px; padding-left: 20px; padding-right: 5px;">USUARIO: <span style="color:#FFFFFF; font-weight:bold;"><?php echo $usuario[0];?></span>
</div>

<?php
if($ND[hours]>=1 && $ND[hours]<13){
	 $dia_anterior=$ND[mday]-1;
	 $mes_anterior=$ND[mon];
	 $anio_anterior=$ND[year];
	 //echo "<label style='float:right; padding-right: 5px;'>$anio_anterior-$mes_anterior-$dia_anterior $Horas[1]</label><br>";
	 if ($dia_anterior==0 && $ND[mon]==1){
		 $mes_anterior=12;
		 $dia_anterior=31;
		 $anio_anterior=$ND[year]-1;
		 //echo "<label style='float:right; padding-right: 5px;'>ahora $anio_anterior-$dia_anterior-$dia_anterior $Horas[1]</label><br>";
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
	 //se buscan todas las ordenes que fueron reprogramadas el d�a anterior.
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
 ?>
<!--
<span style="float:right; padding-right: 5px; color: #a0a0a0;">
    Licenciado a
    <strong><?echo $Entidad?> - <?echo $NoId?>
     - Release PgSql
    <?php
        echo date("YmdHs", filemtime("ValidarArchivos.php"));;
        ?></strong>
</span>
<br>
<span style="float:right; padding-right: 5px; color: #a0a0a0;">
    Licencia No. 20120401 <? echo $NoLic ?>
</span>
-->
<br><br>
<div style="z-index: -9; background-color: #002147; width:105%; height: 50px; position: absolute; top: 0px; right: 0px;"></div>
<?php
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
		//Parametros adicionales para alertas 
		    $param = '';
			if($fila[4] == 19){
			 $param = '&y=1';
			}
			if($fila[4] == 22){
			 $param = '&y=3';
			}
			
			$cons1=str_replace("|","'",$fila[0]);			
			$cons1=str_replace("[COMPANIA]","$Compania[0]",$cons1);
			$cons1=str_replace("[FEC_ACTUAL]","$ND[year]-$ND[mon]-$ND[mday]",$cons1);
			$cons1=str_replace("[USU]","$usuario[1]",$cons1); //echo $cons1."<br>";						
			$cons1=str_replace("+","||",$cons1);
			$res1=ExQuery($cons1);
			//Para poder enviar variables GET desde las alertas
            $abuscar   = '?';
            $pos = strpos($fila[3], $abuscar);
            if ($pos !== false){
                $parametros = "&DatNameSID=$DatNameSID";
            }
            else{
                $parametros = "?DatNameSID=$DatNameSID";
            }
                        
			if(!$fila[3]){
                $fila[3]="ModOpciones.php";
            }
			else{
                $fila[3]=$fila[3]."".$parametros;
            }
			if(ExNumRows($res1)>0){
				if($BanUsus==1){
					if($BanUsuSi==1){$Msj=$Msj." <a href='$fila[3]$param' target='Derecha'>&nbsp;$fila[1]</a> ";}
				}
				else{
					$Msj=$Msj." <a href='$fila[3]$param' target='Derecha'>&nbsp;$fila[1]</a> ";
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
<marquee SCROLLDELAY="155"><span style="color: #002147; font-size: 10px; font-family: Tahoma, Geneva, sans-serif;"><? echo $Msj?></span></marquee>
<? }
?>
</font>
	