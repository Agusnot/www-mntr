<?	if($DatNameSID){session_name("$DatNameSID");}
	session_start();
	include("Funciones.php");	

	
	if($Paciente[1]!=''){
		$cons9="Select * from Central.Terceros where Identificacion='$Paciente[1]' and compania='$Compania[0]'";
		//echo $cons9;
		$res9=ExQuery($cons9);echo ExError();
		$fila9=ExFetch($res9);

		$Paciente[1]=$fila9[0];
		$n=1;
		for($i=1;$i<=ExNumFields($res9);$i++)
		{
			$n++;
			$Paciente[$n]=$fila9[$i];
			//echo "<br>$n=$Paciente[$n]";
		}
		//echo $Paciente[47];
		session_register("Paciente");
	}
	$cons="Select Ajuste,AgruparxHospi,Alineacion,CierreVoluntario,TblFormat,rutaformatant,Paguinacion,laboratorio,formatoxml,LogoAdicional,
	AnchoLogo,AltoLogo from HistoriaClinica.Formatos where Formato='$Formato' and TipoFormato='$TipoFormato' and Compania='$Compania[0]'";
	//echo $cons;
	$res=ExQuery($cons,$conex);
	$fila=ExFetch($res);

	$TiempoAjuste=$fila[0];

	$TAxFORM=$TiempoAjuste*60;
	$Agrupar=$fila[1];
	$Alineacion=$fila[2];
	if($TAxFORM==0){$TAxFORM=30;}
	$CierreVoluntario=$fila[3];
	$Tabla=$fila[4];
	$RutaAnt=$fila[5];
	$LogoAdicional=$fila[9];
	$AnchoLogo=$fila[10];
	$AltoLogo=$fila[11];
	$Paginacion=$fila[6];
	if(!$LimSup){$LimSup=$Paginacion;}
	if(!$LimInf){$LimInf=0;}
	if($SigPagina){$LimInf=$LimSup;$LimSup=$LimSup+$Paginacion;}
	if($AntPagina){$LimInf=$LimInf-$Paginacion;$LimSup=$LimSup-$Paginacion;}
	if($fila[7]){$Laboratorio=1;}
	$FormatoXML=$fila[8]; 
	
	//fecha automatica
 	$cons="Select Ajuste,AgruparxHospi,Alineacion,CierreVoluntario,TblFormat,rutaformatant,Paguinacion,laboratorio from HistoriaClinica.Formatos 
	where Formato='$Formato' and TipoFormato='$TipoFormato' and Compania='$Compania[0]'";
	//echo $cons;
	$res=ExQuery($cons,$conex);
	$fila=ExFetch($res);
	$Tabla=$fila[4];
	$Alineacion=$fila[2];

	$ND=getdate();

	$AnioNac=substr($Paciente[23],0,4);
	$MesNac=substr($Paciente[23],5,2);
	$DiaNac=substr($Paciente[23],8,2);
	$FecAct=getdate();
	$AnioAct=$FecAct[year];
	$MesAct=$FecAct[mon];
	$DiaAct=$FecAct[mday];
	$Edad=$AnioAct-$AnioNac;
	if($MesAct==$MesNac)
	{
		if($DiaAct<$DiaNac)
		{
			$Edad=$Edad-1;
		}
	}
	elseif($MesAct<$MesNac)
	{
		$Edad=$Edad-1;
	}
	if($Edad>100){$Edad="";}
	else{$Edad=$Edad . " A&Ntilde;OS";}
//	for($i=1;$i<=100;$i++){echo "$i--> ".$Paciente[$i]."<br>";}
	
	$cons="Select fecnac, sexo, ecivil, eps, tipousu, nivelusu from central.Terceros where Compania='$Compania[0]' and Identificacion='$Paciente[1]'";
	$res=ExQuery($cons);
	$MatDatosPaciente=ExFetch($res);
	$MatDatosPaciente[0]=ObtenEdad($MatDatosPaciente[0]);
	$MatOperadores["Igual a"]="==";
	$MatOperadores["Mayor a"]=">";
	$MatOperadores["Mayor Igual a"]=">=";
	$MatOperadores["Menor a"]="<";
	$MatOperadores["Menor Igual a"]="<=";	
	
	
		
	$consImp="Select id_historia from HistoClinicaFrms.$Tabla 
	where Cedula='$Paciente[1]' and Formato='$Formato' and TipoFormato='$TipoFormato' and Compania='$Compania[0]'
	order by fecha desc,hora desc"; 	
	$resImp=ExQuery($consImp);
	while($filaImp=ExFetch($resImp))
	{
		//Encuentro el numero de servicio y la fecha en la q se diligencio el formato
		$cons="Select numservicio,fecha from HistoClinicaFrms.$Tabla where Cedula='$Paciente[1]' and Formato='$Formato' and TipoFormato='$TipoFormato' and Id_Historia=$filaImp[0]  
		and Compania='$Compania[0]'"; 	
		//echo $cons;
		$res=ExQuery($cons);
		$fila=ExFetch($res);
		$NumServ=$fila[0];
		$FechaExp=$fila[1];
		//Enuentro los datos del formato
		$cons="Select * from HistoClinicaFrms.$Tabla 
		where Cedula='$Paciente[1]' and Formato='$Formato' and TipoFormato='$TipoFormato' and Id_Historia=$filaImp[0]  and Compania='$Compania[0]'"; 	//echo $cons;
		$res=ExQuery($cons);echo ExError();
		$fila=ExFetch($res);
		$IdHospitalizacion=$fila['idhospitalizacion'];
		
			
		//Encuentro el nombre del medico tratante
		$consMed="select nombre from central.usuarios where usuario='$fila[3]'";
		$resMed=ExQuery($consMed);
		$filaMed=ExFetch($resMed);$UsuMed=$fila[3];
	//	$cons="Select FechaIng,FechaEgr,MedicoTratante,Entidad,Regimen,TipoUsu,NivelUsu from salud.hospitalizacion where Cedula='$Paciente[1]' and IdHospitalizacion='$IdHospitalizacion'";
	//	$res=ExQuery($cons);
	//	$fila=ExFetch($res);
		$FechaIng="";$FechaEgr="";
		$MedTratante=$fila2[2];$Entidad=$fila2[3];$Regimen=$fila2[4];$TipoUsu=$fila2[5];$NivelUsu=$fila2[6];
			
		
		//OBTENER FECHA DE INGRESO DE PACIENTES NO HOSPITALIZADOS.....
		$FechaIngnoHosp="Select Fecha from salud.Agenda where Cedula='$Paciente[1]' and Estado='A'";
		$resFechaIngnoHosp=ExQuery($FechaIngnoHosp,$conex);
	//	$datonohosp=ExFetch($resFechaIngnoHosp);
		$FechaIng2=$datonohosp[0];
		/////
		$consxyz = "Select Eps from Central.Terceros Where Compania='$Compania[0]' and Identificacion='$Paciente[1]'";
		$resxyz = ExQuery($consxyz);
		$filaxyz = ExFetch($resxyz);
		if($NumServ){
			$consServ="select fechaing,fechaegr from salud.servicios 
			where compania='$Compania[0]' and servicios.cedula='$Paciente[1]' and numservicio=$NumServ ";
			$resServ=ExQuery($consServ);
			$filaServ=ExFetch($resServ);	
			//echo $consServ;		
			$consPxS="select primape,segape,primnom,segnom,tipoasegurador from central.terceros,salud.pagadorxservicios 
			where terceros.compania='$Compania[0]' and pagadorxservicios.compania='$Compania[0]' and identificacion=entidad	and numservicio=$NumServ order by fechaini desc";
			$resPxS=ExQuery($consPxS);
			$filaPxS=Exfetch($resPxS);
		}	
	?>
		<style>
		.style1 {
			font-size: 11px;
			font-weight: bold;
			font-family:Tahoma
		}
		
		.Estilo2 {font-family: Tahoma;font-weight: bold;font-size: 18px}
		</style>
		<body>
        <br><br><br>
		<table width="100%" border="1" bordercolor="#ffffff" style='font : normal normal small-caps 13px Tahoma;'>
		<tr><td align="center"><img src="/Imgs/Logo.jpg" alt="" width="50" height="50" style="position:absolute;left:50px;">
		<?
		if($LogoAdicional)
		{?>
			<img src="<? echo $LogoAdicional?>" alt="" width="<? echo $AnchoLogo?>" height="<? echo $AltoLogo?>" style="position:absolute;right:50px;">	
		<?
			if($AltoLogo>50){echo "<br>";}if($AltoLogo>80){echo "<bR>";}if($AltoLogo>100){echo "<br>";}
		}	
		?>
		<strong><font size="4"><?echo strtoupper($Compania[0])?></font><br>
		HERMANOS HOSPITALARIOS DE SAN JUAN DE DIOS</strong><br>
		<font size="1"><? echo $Compania[1]."<br>".$Compania[2]?> <br> Telefonos <? echo $Compania[3]?>       
		<br>
		</td>     
		</tr>
		</table>		
	
		
		<table border="1" width="100%">
		<tr><td>
			<table style='font : normal normal small-caps 10px Tahoma;'>
			<tr><td>NOMBRE:</td><td><? echo "$Paciente[2] $Paciente[3] $Paciente[4] $Paciente[5]"?></td></tr>
			<tr><td>IDENTIFICACION:</td><td><? echo "$Paciente[1]"?></td></tr>
			<tr><td>FECHA DE NACIMIENTO:</td><td><? echo "$Paciente[23] ($Edad)"?></td></tr>
			<tr><td>DIRECCION:</td><td><? echo $Paciente[7];?></td></tr>
			<tr><td>FECHA DE INGRESO:</td><td><? echo $filaServ[0];?></td></tr>
			<tr><td>FECHA DE REGISTRO:</td><td><? echo $fila[5];?></td></tr>
			<tr><td>FECHA DE EGRESO:</td><td><? echo $filaServ[1];?></td></tr>
			</table>
		<td>
			<table style='font : normal normal small-caps 10px Tahoma;'>
			<tr><td>MEDICO TRATANTE:</td><td><? echo $filaMed[0]?></td></tr>
			<tr><td>ENTIDAD:</td><td><? echo "$filaPxS[0] $filaPxS[1] $filaPxS[2] $filaPxS[3]"?></td></tr>
			<tr><td>REGIMEN:</td><td><? echo "$filaPxS[4]"?></td></tr>
			<tr><td>TIPO DE USUARIO:</td><td><? echo "$Paciente[27]"?></td></tr>
			<tr><td>NIVEL DE USUARIO:</td><td><? echo "$Paciente[28]"?></td></tr>
			</table>
		</td>
		</table>
	<?	if($Titulo){?>   
			<center class="Estilo1"><span class="Estilo2"><? echo strtoupper($Titulo);?></span></center>
	<?	}
		else{?> 
			<center class="Estilo1"><span class="Estilo2"><? echo strtoupper($Formato);?></span></center>
	<?	} 
	
		$cons99="Select * from HistoriaClinica.ItemsxFormatos where  TipoFormato='$TipoFormato' and Formato='$Formato' and Compania='$Compania[0]' and Estado='AC' Order By Pantalla,Orden";
		$res99=ExQuery($cons99);
		while($fila99=ExFetchArray($res99))
		{ 
			$MatItems[$fila99['id_item']]=array($fila99['id_item'],$fila99['item'],$fila99['lineasola'],$fila99['cierrafila'],$fila99['titulo'],$fila99['imagen'],$fila99['subformato'],$fila99['tipocontrol']);
			$NumTotCmps++;
			//Dependencia
			$DatCampos[$fila99['id_item']]=array($fila99['id_item'],1,$fila99['item'],0,0);
			$consxx="select condedad1, edad1, condedad2, edad2, sexo, estadocivil, eps, tipousuario, nivel 
			from historiaclinica.dependenciahc where Compania='$Compania[0]' and Formato='$Formato' and Id_Item=".$fila99['id_item']." 
			and Item='".$fila99['item']."' and TipoFormato='$TipoFormato'";
			//echo $consxx."<br>";
			$resxx=ExQuery($consxx);
			while($filaxx=ExFetch($resxx))
			{
				if((($filaxx[0]&&$filaxx[1])&&(empty($filaxx[2])&&empty($filaxx[3])))||(($filaxx[0]&&$filaxx[1]&&$filaxx[2]&&$filaxx[3]))){$DatCampos[$fila99['id_item']][3]++;}				
				if($filaxx[4]){$DatCampos[$fila99['id_item']][3]++;}
				if($filaxx[5]){$DatCampos[$fila99['id_item']][3]++;}
				if($filaxx[6]){$DatCampos[$fila99['id_item']][3]++;}
				if($filaxx[7]){$DatCampos[$fila99['id_item']][3]++;}
				if($filaxx[8]){$DatCampos[$fila99['id_item']][3]++;}
				$MatDependenciaxItem[$fila99['id_item']]=array($filaxx[0],$filaxx[1],$filaxx[2],$filaxx[3],$filaxx[4],$filaxx[5],$filaxx[6],$filaxx[7],$filaxx[8]);
				//echo $filaxx[0]." --> ".$filaxx[1]." --> ".$filaxx[2]." --> ".$filaxx[3]." --> ".$filaxx[4]." --> ".$filaxx[5]." --> ".$filaxx[6]." --> ".$filaxx[6]." --> ".$filaxx[7]." --> ".$filaxx[8]."<br> ";
			}
			//---
		}	
			
	
		if($Alineacion=="Horizontal")
		{
		
			echo "<table border='0' bordercolor='#e5e5e5' style='font : 12px Tahoma;text-align:justify'>
				<tr style='font-weight:bold;text-align:center' bgcolor='#e5e5e5'><td>Fecha</td><td>Hora</td><td>Usuario</td>";
			foreach($MatItems as $Tits)
			{
				echo "<td>".$Tits[1]."</td>";
			}
			echo "<td></td></tr><tr>";
		}
		$cons="Select * from HistoClinicaFrms.$Tabla where Cedula='$Paciente[1]' and Formato='$Formato' and TipoFormato='$TipoFormato' and Compania='$Compania[0]'
		and Id_Historia=$filaImp[0] $condSF $Registro Order By Fecha Desc,Hora Desc,Id_historia Desc $CondPag";
		$res=ExQuery($cons,$conex);
		$NumTotReg=ExNumRows($res);
		while($fila=ExFetchArray($res))
		{
			if($Alineacion=="Vertical")
			{
				echo "<table width='100%' border='0' bordercolor='#e5e5e5' rules='groups' style='font : 12px Tahoma;text-align:justify'>";
			}
		
			if($Alineacion=="Horizontal"){echo "<td>".$fila['fecha']."</td><td>".$fila['hora']."</td><td>".$fila['usuario']."</td>";}
			foreach($MatItems as $IndItems)
			{
				//--Dependencia HC
				$NoCamposCumple=0;			
				if(empty($DatCampos[$IndItems[0]][3])||$DatCampos[$IndItems[0]][3]==0)
				{
					//nadaaa
				}
				else
				{			
					//echo $DatCampos[$IndItems[0]][0]." --> ".$DatCampos[$IndItems[0]][2]." --> ".$DatCampos[$IndItems[0]][3]."<br>";				
					if(!empty($MatDependenciaxItem[$DatCampos[$IndItems[0]][0]][0])&&!empty($MatDependenciaxItem[$DatCampos[$IndItems[0]][0]][1])&&!empty($MatDependenciaxItem[$DatCampos[$IndItems[0]][0]][2])&&!empty($MatDependenciaxItem[$DatCampos[$IndItems[0]][0]][3]))
					{
						$Operador=$MatOperadores[$MatDependenciaxItem[$DatCampos[$IndItems[0]][0]][0]];					
						$Operador1=$MatOperadores[$MatDependenciaxItem[$DatCampos[$IndItems[0]][0]][2]];					
						eval("
						if(\$MatDatosPaciente[0] $Operador  \$MatDependenciaxItem[\$DatCampos[\$IndItems[0]][0]][1] && \$MatDatosPaciente[0] $Operador1  \$MatDependenciaxItem[\$DatCampos[\$IndItems[0]][0]][3])
						{
							\$NoCamposCumple++;
						}");					
					}
					elseif(!empty($MatDependenciaxItem[$DatCampos[$IndItems[0]][0]][0])&&!empty($MatDependenciaxItem[$DatCampos[$IndItems[0]][0]][1]))
					{								
						$Operador=$MatOperadores[$MatDependenciaxItem[$DatCampos[$IndItems[0]][0]][0]];					
						eval("
						if(\$MatDatosPaciente[0] $Operador  \$MatDependenciaxItem[\$DatCampos[\$IndItems[0]][0]][1])
						{
							\$NoCamposCumple++;
						}");					
					}				
					if(!empty($MatDependenciaxItem[$DatCampos[$IndItems[0]][0]][4]))
					{
						if($MatDependenciaxItem[$DatCampos[$IndItems[0]][0]][4]==$MatDatosPaciente[1])
						{
							$NoCamposCumple++;
						}		
					}				
					if(!empty($MatDependenciaxItem[$DatCampos[$IndItems[0]][0]][5]))
					{
						if($MatDependenciaxItem[$DatCampos[$IndItems[0]][0]][5]==$MatDatosPaciente[2])
						{
							$NoCamposCumple++;	
						}	
					}
					if(!empty($MatDependenciaxItem[$DatCampos[$IndItems[0]][0]][6]))
					{
						if($MatDependenciaxItem[$DatCampos[$IndItems[0]][0]][6]==$MatDatosPaciente[3])
						{
							$NoCamposCumple++;
						}		
					}				
					if(!empty($MatDependenciaxItem[$DatCampos[$IndItems[0]][0]][7]))
					{
						if($MatDependenciaxItem[$DatCampos[$IndItems[0]][0]][7]==$MatDatosPaciente[4])
						{	
							$NoCamposCumple++;			
						}	
					}
					if(!empty($MatDependenciaxItem[$DatCampos[$IndItems[0]][0]][8]))
					{
						if($MatDependenciaxItem[$DatCampos[$IndItems[0]][0]][8]==$MatDatosPaciente[5])
						{
							$NoCamposCumple++;			
						}		
					}
					if($NoCamposCumple==$DatCampos[$IndItems[0]][3])
					{							
						$DatCampos[$DatCampos[$IndItems[0]][0]][4]=$NoCamposCumple;
						//echo "Cumple --> ".$DatCampos[$IndItems[0]][0]." --> ".$DatCampos[$IndItems[0]][2]." --> ".$DatCampos[$IndItems[0]][3]." --> ".$DatCampos[$DatCampos[$IndItems[0]][0]][4]."<br>";		
					}				
				}	
				//--
				if(empty($DatCampos[$IndItems[0]])||($DatCampos[$IndItems[0]][3]==$DatCampos[$IndItems[0]][4]))
				{
					$IdItem=$IndItems[0];$Item=$IndItems[1];$LineaSola=$IndItems[2];$Titulo=$IndItems[4];$Imagen=$IndItems[5];$SubFormato=$IndItems[6];
					$NumCamp="cmp".substr("00000",0,5-strlen($IdItem)).$IdItem;
					$Mensaje=$Item;
					if($CrearTabla==1 && $Titulo==1 && $Alineacion=="Vertical"){echo "</table>";$CrearTabla=0;}
					if($Titulo==1)
					{
						if($Alineacion=="Vertical")
						{
							echo "<tr><td bgcolor='#e5e5e5' colspan='99'><strong><center>".$Mensaje."</td></tr>";
							if($Mensaje=="Diagnostico")
							{
								echo "<table border='1' bordercolor='#e5e5e5' style='font : 12px Tahoma;text-align:justify' >";
								$cons8="Select Detalle,CIE10,Id from historiaclinica.dxformatos where Compania='$Compania[0]' and Estado='AC' and Formato='$Formato' and TipoFormato='$TipoFormato' Order By Id";
								$res8=ExQuery($cons8);
								while($fila8=ExFetch($res8))
								{
									if($fila8[2]!=1){$Colspan=2;$Width="480px";}else{$Colspan=1;$Width="300px";}
									$cons19="Select Diagnostico from Salud.CIE where Codigo='".$fila['dx'.$fila8[2]]."'";
									$res19=ExQuery($cons19);
									$fila19=ExFetch($res19);
									$DetValDx=$fila19[0]; 
									if($fila['dx'.$fila8[2]])
									{
										?>
										<tr>
											<td><? echo $fila8[0]?></td>
											<td style="background:#e5e5e5" align="center">
												<strong ><? echo $fila['dx'.$fila8[2]] ?></strong>
											</td>
											<td>
												<? echo $DetValDx?>
											</td>
										<? if($fila8[2]==1)
										{
											$cons45="Select TipoDiagnost from Salud.TiposDiagnostico where Compania='$Compania[0]' and Codigo='".$fila['tipodx']."'";
											$res45=ExQuery($cons45);
											$fila45=ExFetch($res45);
											$TipDx=$fila45[0];
											
											?>
											<td style="background:#e5e5e5" align="center">
											<strong><? echo $TipDx; ?></strong>
											
									<?	}
										else{
											echo "<td>&nbsp";
										}
										echo "</td></tr>";
									}
								}
								echo "</table><table border='1' bordercolor='#e5e5e5' style='font : 12px Tahoma;text-align:justify' >";?>
								<tr>
								<?	$cons45="select causa from salud.causaexterna where codigo='".$fila['causaexterna']."'";
									$res45=ExQuery($cons45);
									$fila45=ExFetch($res45);
									$CausaExterna=$fila45[0];
									
									$cons45="select finalidad from salud.finalidadesact where codigo='".$fila['finalidadconsult']."' and tipo=1";
									$res45=ExQuery($cons45);
									$fila45=ExFetch($res45);
									$FinalidadConsulta=$fila45[0];?>
									<td style="background:#e5e5e5">
										<strong>Causa Externa: </strong></td>
									<td><? echo $CausaExterna?></td>
									<td style="background:#e5e5e5">
										<strong>Finalidad Consulta: </strong></td>
									<td>	
										<? echo $FinalidadConsulta?>
									</td>
								</tr>
							<?	echo "</table><table border='0' bordercolor='#e5e5e5' style='font : 12px Tahoma;text-align:justify'  width='100%'>";
							}
							if($Mensaje=="Medicamento No Pos")
							{
								$cons19="select detalle,posologia from salud.plantillamedicamentos where compania='$Compania[0]' and tipoformato='$TipoFormato' and cedpaciente='$Paciente[1]' and formato='$Formato' and id_historia=".$fila['id_historia'];
								$res19=ExQuery($cons19);
								$fila19=ExFetch($res19);
								echo "<tr><td><strong>Principio Activo:</strong> $fila19[0]</td></tr><tr><td><strong>Posologia: </strong>$fila19[1]</td></tr>";
							}
							if($Mensaje=="CUP No Pos")
							{
								$cons19="select cup,nombre from salud.plantillaprocedimientos,contratacionsalud.cups
								where plantillaprocedimientos.compania='$Compania[0]' and tipoformato='$TipoFormato' and cedula='$Paciente[1]' and formato='$Formato' 
								and cup=codigo and cups.compania='$Compania[0]' and id_historia=".$fila['id_historia'];
								$res19=ExQuery($cons19);
								$fila19=ExFetch($res19);
								echo "<tr><td><strong>Codigo CUP:</strong> $fila19[0]</td></tr><tr><td><strong>Nombre CUP: </strong>$fila19[1]</td></tr>";
							}
						}
					}
					else
					{
						if($Alineacion=="Vertical")
						{
	
							if($CrearTabla==1 && $LineaSola==1){echo "</table>";$CrearTabla=0;
							}
							if($LineaSola==1)
							{
								
								echo "<tr><td colspan='99' ><strong>".$Mensaje."</td></tr><tr><td colspan='99'>";								
							}
							elseif($LineaSola==0)
							{
								if($CierraFila){echo "<tr>";}
								if(!$CrearTabla)
								{
									echo "<tr><td>";
									if($SubFormato==1){$Ww=" width='100%'";}									
									echo "<table border='0' $Ww cellpadding=4  bordercolor='#e5e5e5' cellpadding=2 style='font : 12px Tahoma;'><tr>";$CrearTabla=1;
								}
								if(!$SubFormato)
								{									
									echo "<td><strong>".$Mensaje.":</strong> </td><td>";									
								}
							}
						}
					}				
				$CierraFila=$IndItems[3];
				if($fila['imagen']){echo "<img src='".$fila['imagen']."'>";}
				$fila[$NumCamp]=str_replace("\n","<br>",$fila[$NumCamp]);
				if($Titulo!=1 && !$Imagen && !$SubFormato && $Alineacion=="Vertical"){
					if($IndItems[7]=="PDF"){
						if($fila[$NumCamp]){
							$Mostrar=str_replace("C:/AppServ/www/HistoriaClinica/ImgsLabs/"," ",$fila[$NumCamp]);?>
							<ul><div style="cursor:hand" title="Ver" onClick="VerPDF('<? echo $fila[$NumCamp]?>')"><? echo $Mostrar?></a>
				<?		}
					}
					else{
						echo "<ul> ".$fila[$NumCamp];
					}
				}
				if($Titulo!=1 && !$Imagen && !$SubFormato && $Alineacion=="Horizontal")
				{
					if($usuario[1]==$fila['usuario'])
					{
						$date1=$fila['fecha'] ." " . $fila['hora'];
						$date2="$ND[year]-$ND[mon]-$ND[mday] $ND[hours]:$ND[minutes]:$ND[seconds]";
						$s = strtotime($date2)-strtotime($date1);
						$d = intval($s/86400);
						$s -= $d*86400;
						$d = $d*1440;
						$m = intval($s/60) + $d;
						if($m<$TiempoAjuste){$Lapiz="<a href='NuevoRegistro.php?DatNameSID=$DatNameSID&Formato=$Formato&TipoFormato=$TipoFormato&IdHistoria=".$fila['id_historia'] ."'><img border=0 src='/Imgs/b_edit.png'></a>";}
						else{$Lapiz="&nbsp;";}
					}
					if(!$fila[$NumCamp]){$fila[$NumCamp]="&nbsp;";}
					if($IndItems[7]=="PDF"){
						echo "<td>SIIIII</td>";
					}
					else{
						echo "<td>".$fila[$NumCamp]."</td>";
					}
				}
				}
			
			}
			if($Laboratorio){
				if($fila['numproced']){
					$consLab="select interpretacion from histoclinicafrms.ayudaxformatos where cedula='$Paciente[1]' and compania='$Compania[0]' and numservicio=".$fila['numservicio']."
					and numproced=".$fila['numproced']."and formato='$Formato' and tipoformato='$TipoFormato' and id_historia=".$fila['id_historia'];
					$resLab=ExQuery($consLab);
					$filaLab=ExFetch($resLab);
					//echo $consLab;
				}
			?>     
				<tr><td><strong>Interpretacion Laboratorio:&nbsp;</strong><? echo $filaLab[0]?></td></tr>
			<?
			}
			if($Alineacion=="Vertical"){echo "</table><br><br>";}else{if($Lapiz){echo "<td>$Lapiz</td>";}echo "</tr>";}
		}
			if($UsuMed){
				$cons="select rm,cargo from salud.medicos where compania='$Compania[0]' and usuario='$UsuMed'";
				$res=ExQuery($cons);
				$fila=ExFetch($res);
				$RM=$fila[0];
				$Cargo=$fila[1];
				$cons="select cedula from central.usuarios where usuario='$UsuMed'";			
				$res=ExQuery($cons);
				$fila=ExFetch($res);?>
				<table border="1" style='font : normal normal small-caps 13px Tahoma;' align="center">
					<tr align="center"><td><center><? echo $filaMed[0]?></center></td></tr>
					<tr align="center"><td><center><img src='/Firmas/<? echo "$fila[0].GIF"?>' style='width:160px; height:100px'></center></td></tr>             			
					<tr align="center"><td><? echo $Cargo?></td></tr>
					<tr align="center"><td><? echo "Registro Medico $RM"?></td></tr>
				</table>
		<?	}
	}	
			?>