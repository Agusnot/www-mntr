<?	if($DatNameSID){session_name("$DatNameSID");}

	session_start();
	include("Funciones.php");
	$alineacionFormato = definirAlineacionFormato($TipoFormato, $Formato);
		if (strtoupper($alineacionFormato) == "HORIZONTAL"){
			$anchoTabla = "2000px";
		}
		else {
			$anchoTabla = "1000px";
		}
	
	if($CedImpMasv){$Paciente[1]=$CedImpMasv;}
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
		AnchoLogo,AltoLogo, EntidadLogo,ContratoLogo,NoContratoLogo,incluirsignosvitales,firmapaciente,confimpdiagnostico, impcoddiagnostico,
		impnomdiagnostico from HistoriaClinica.Formatos 
		where Formato='$Formato' and TipoFormato='$TipoFormato' and Compania='$Compania[0]'";
		//echo $cons;
		$res=ExQuery($cons,$conex);
		$fila=ExFetch($res);

		$TiempoAjuste=$fila[0];

		$TAxFORM=$TiempoAjuste*60;
		$Agrupar=$fila[1];
		$Alineacion=$fila[2];
			if($TAxFORM==0){
				$TAxFORM=30;
			}
		$CierreVoluntario=$fila[3];
		$Tabla=$fila[4];
		$RutaAnt=$fila[5];
		$LogoAdicional=$fila[9];
		$AnchoLogo=$fila[10];
		$AltoLogo=$fila[11];
		$EntidadLogo=$fila[12];
		$ContratoLogo=$fila[13];
		$NoContratoLogo=$fila[14];	
		$Paginacion=$fila[6];
		if(!$LimSup){$LimSup=$Paginacion;}
		if(!$LimInf){$LimInf=0;}
		if($SigPagina){$LimInf=$LimSup;$LimSup=$LimSup+$Paginacion;}
		if($AntPagina){$LimInf=$LimInf-$Paginacion;$LimSup=$LimSup-$Paginacion;}
		if($fila[7]){$Laboratorio=1;}
		$FormatoXML=$fila[8]; 
		$IncluirSignosVitales=$fila[15];
		$FirmaPacienteF=$fila[16];
		$ConfImpDiagnostico=$fila[17];
		$ImpCodDiagnostico=$fila[18];
		$ImpNomDiagnostico=$fila[19];	
		
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
			if($MesAct==$MesNac){
			
				if($DiaAct<$DiaNac)	{
					$Edad=$Edad-1;
				}
			}
			elseif($MesAct<$MesNac){			
				$Edad=$Edad-1;
			}
			
			
			if($Edad>100){
				$Edad="";
			}	
			else{
				$Edad=$Edad . " A&Ntilde;OS";
			}
			
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
		
		//---imprime rango de fechas -- Todo -- unsa sola
		if($IdHistoria)	{
			$ParteConsId="and Id_Historia=$IdHistoria";
		}
		elseif($FechaIni&&$FechaFin){
		
			$ParteConsFechas="and fecha>='$FechaIni' and fecha<='$FechaFin'";
		}
		else{
			$IdHistoria="";	
		}		
		
		$consImp="Select id_historia from HistoClinicaFrms.$Tabla 
		where Cedula='$Paciente[1]' and Formato='$Formato' and TipoFormato='$TipoFormato' $ParteConsId $ParteConsFechas and Compania='$Compania[0]'
		order by fecha desc,hora desc"; 	
		$resImp=ExQuery($consImp);
		
			while($filaImp=ExFetch($resImp)){
			
				$IdHistoria=$filaImp[0];

				//Encuentro los datos del formato
				$cons="Select * from HistoClinicaFrms.$Tabla 
				where Cedula='$Paciente[1]' and Formato='$Formato' and TipoFormato='$TipoFormato' and Id_Historia=$IdHistoria  and Compania='$Compania[0]'"; 	//echo $cons;
				$res=ExQuery($cons);echo ExError();
				$fila=ExFetchArray($res);
				$NumServ=$fila['numservicio'];
				$FechaExp=$fila['fecha'];
				$IdHospitalizacion=$fila['idhospitalizacion'];
				//echo $fila['numservicio']." ".$NumServ;	
				//---Servicio mas proximo a la fecha
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
					$consServ="select fechaing,fechaegr,numservicio from salud.servicios 
					where compania='$Compania[0]' and servicios.cedula='$Paciente[1]' and fechaIng<='$FechaExp 23:59:59' order by fechaIng desc ";
					//echo $consServ;
					$resServ=ExQuery($consServ);
					while($filaServ=ExFetch($resServ)){
						if($filaServ[2]==$NumServ){
							break;
						}	
					}			
					
					if($filaServ[2]){
						$consPxS="select primape,segape,primnom,segnom,tipoasegurador,Identificacion,contrato,nocontrato
						 from central.terceros,salud.pagadorxservicios 
						where terceros.compania='$Compania[0]' and pagadorxservicios.compania='$Compania[0]' and identificacion=entidad	and numservicio=$filaServ[2] order by fechaini desc";
						$resPxS=ExQuery($consPxS);
						$filaPxS=Exfetch($resPxS);
						$Ent=$filaPxS[5];
						$Contrat=$filaPxS[6];
						$NoCont=$filaPxS[7];
					}
					
				}	
				
				if($filaServ[0]){
					$consdi="SELECT diagnosticos.diagnostico,cie.diagnostico FROM salud.diagnosticos,salud.cie where Compania='$Compania[0]' 
					and cedula='$Paciente[1]' and numservicio=$filaServ[2] and clasedx='Ingreso' and cie.codigo=diagnosticos.diagnostico";
					//echo $consdi." $NumServ entra di<br>";
					$resdi=ExQuery($consdi);
					$filadi=ExFetch($resdi);
				}
				
				if($filaServ[1]){
					$consde="SELECT diagnosticos.diagnostico,cie.diagnostico FROM salud.diagnosticos,salud.cie where Compania='$Compania[0]' 
					and cedula='$Paciente[1]' and numservicio=$filaServ[2] and clasedx='Egreso' and cie.codigo=diagnosticos.diagnostico";
					//echo $consde." $NumServ entra di<br>";
					$resde=ExQuery($consde);
					$filade=ExFetch($resde);
				}
			?>
			
			<head>
				<style type="text/css">
				
					.subtitulo1 {
						font-size: 14px;
						color: #000;
						font-weight:bold;
					}
					
					.subtitulo2 {
						font-size: 12px;
						color: #000;
						font-weight:bold;
					}
					
					.letra1{
						font-size: 12px;
						color: #000;
						font-weight:normal;
						
					}
					
					.compania {
						font-size: 14px;
						color: #000;
						font-weight:bold;					
					}
				
				</style>
			
			</head>
			
			<body>
				
				<!-- Inicia la visualizacion de la informacion de la Entidad -->
				<div align="center">
					<table width="<?php echo $anchoTabla; ?>" style="table-layout:fixed;border-width:1px; border-style:solid; border-color:#000;" border="1px" bordercolor="#000"  cellpadding="2" cellspacing="0">
						<tr>
							<td>
								<table width="<?php echo $anchoTabla; ?>" border="0px"  cellpadding="5" cellspacing="0" style='font: normal normal small-caps 13px Tahoma;text-align:center; vertical-align:middle;'>
									<tr>
										<td>
											<div align="center" style="margin-top:3px;margin-bottom:2px;">
												<table style="border:0px;vertical-align:text-top;text-align:center;" cellpadding='0' border="0" cellspacing ="0">
													<tr>
														<td>
															<img src="/Imgs/Logo.jpg"  width="70px" height="70px" >
														</td>
															<?
															if($LogoAdicional){
																if($EntidadLogo&&$ContratoLogo)	{
																	if($Ent==$EntidadLogo&&$ContratoLogo==$Contrat&&$NoCont==$NoContratoLogo)
																	{
																		?>
																		<td>
																			<img src="<? echo $LogoAdicional?>" alt="" width="<? echo $AnchoLogo?>" height="<? echo $AltoLogo?>">
																				
																		</td>
																		<?php
																			
																	}			
																}
																elseif($EntidadLogo){
																	echo $Ent." ".$EntidadLogo;
																	if($Ent==$EntidadLogo){					
																	
																		?>
																		<td>
																			<img src="<? echo $LogoAdicional?>" alt="" width="<? echo $AnchoLogo?>" height="<? echo $AltoLogo?>">
																				
																		</td>		
																		<?php
																	}				
																}
																else
																{
																	?>
																	<td>
																		<img src="<? echo $LogoAdicional?>" alt="" width="<? echo $AnchoLogo?>" height="<? echo $AltoLogo?>">	
																			
																	</td>	
																	<?php	
																}				
															}
															?>
														
													</tr>
												</table>
											</div>
										</td>
									</tr>		
											
									<tr>
										<td style="text-align:center; vertical-align:middle;">
											<div style="line-height:1px;margin-top:2px;">
												<p class="compania"> <?php echo strtoupper($Compania[0]);?>  </p>
												
												<?php 
													if (!empty($Compania[20])){
														?>
														<p class="letra1" style="font-size:13px;">  <?php echo $Compania[20] ;?>  </p>
														<?php
													
													}
												?>	
													
											
												<p class="letra1">  <?php echo $Compania[2] ;?>  </p>
											
												<?php
													$nombredepartamento = consultarNombreDepto($Compania[18]); 
													$nombremunicipio = consultarNombreMpo($Compania[18], $Compania[19]); 
												?>
												<p class="letra1">  <?php echo $nombremunicipio." - ".$nombredepartamento ;?>  </p>
												<p class="letra1">  <?php echo $Compania[1] ;?>  </p>								
												<p class="letra1"> <?php echo $Compania[17] ;?>  </p>
												
											</div>	
										</td>							
									</tr>
									
								</table>
							</td>
						</tr>
							<!-- Termina la visualizacion de la informacion de la Entidad -->
							
							<!-- Inicia la visualizacion de la informacion del paciente -->
						<tr>
							<td style="text-align:center;">
								<p class="subtitulo1"> DATOS PACIENTE </p>
							</td>
						</tr>
						
						<tr>
							<td>
								
											<table style = "text-align:left;" width="100%" cellpadding="2px" border="0" >
												
												<tr>
													<td width="20%"><p class="subtitulo2">TIPO DE DOCUMENTO: </p></td>
													<td width="25%"><p class="letra1"><? echo strtoupper($Paciente[19]);?></p></td>
													<td width="15%">&nbsp;</td>
													<td width="20%"><p class="subtitulo2">N&Uacute;MERO DE DOCUMENTO:</p></td>
													<td width="25%"><p class="letra1"><? echo strtoupper($Paciente[1]);?></p></td>
												</tr>
												
												<tr>
													<td><p class="subtitulo2">NOMBRE: </p></td>
													<td colspan="4"><p class="letra1" style="word-spacing:7px;"><? echo $Paciente[2]." ".$Paciente[3]." ".$Paciente[4]." ".$Paciente[5]?></p></td>
												</tr>
												
												<tr>	
													<td width="20%"><p class="subtitulo2">FECHA DE NACIMIENTO:</p></td>
													<td width="25%"><p class="letra1"><? echo "$Paciente[23] ($Edad)"?></td>
													<td width="15%">&nbsp;</td>
													<td width="20%"><p class="subtitulo2">LUGAR DE  NACIMIENTO: </p></td>
													<td width="25%"><p class="letra1"><? echo $Paciente[76]." - ".$Paciente[75];?></p></td>
												</tr>
												
												<tr>													
													<td><p class="subtitulo2">LUGAR DE  RESIDENCIA: </p></td>
													<td colspan="4"><p class="letra1"><? echo $Paciente[7]." (".$Paciente[11]." - ".$Paciente[10].")";?></p></td>
												</tr>
												
												<tr>
													<td width="20%"><p class="subtitulo2">TEL&Eacute;FONO: </p></td>
													<td width="25%"><p class="letra1"><? echo $Paciente[8];?></p></td>
													<td width="15%">&nbsp;</td>
													<td width="20%"><p class="subtitulo2">CELULAR: </p></td>
													<td width="25%"><p class="letra1"><? echo $Paciente[50];?></p></td>
												</tr>
												
												<tr>
													<td><p class="subtitulo2">PROCEDENCIA: </p></td>
													<td colspan="4"><p class="letra1"><? echo $Paciente[53];?></p></td>
												</tr> 
												
												<tr>
													<td width="20%"><p class="subtitulo2">FECHA DE INGRESO: </p></td>
													<td width="25%"><p class="letra1"><? echo $filaServ[0];?></p></td>
													<td width="15%">&nbsp;</td>
													<td width="20%"><p class="subtitulo2">DIAGNOSTICO DE INGRESO:</p></td>
													<td width="25%">
														<?
															if($filadi)	{
																if(!$ConfImpDiagnostico||($ConfImpDiagnostico&&$ImpCodDiagnostico&&$ImpNomDiagnostico))	{
																	echo"<p class='letra1'>".$filadi[0]." ".$filadi[1]."</p>";
																}
															}
															else{
																echo "&nbsp;";
															} 
															
														?>
																	
													</td>
													
												</tr>
												
												<tr>												
													<td> <p class="subtitulo2">FECHA DE EGRESO: </p></td>
													<td>
														<? 
															if($filaServ[1]) {
																echo "<p class='letra1'>".$filaServ[1]."</p>";
															} 
															else {
																echo "&nbsp;";
															}														
														?>
													</td>
													<td width="15%">&nbsp;</td>
													<td> <p class="subtitulo2">DIAGNOSTICO DE EGRESO:</p></td>
													<td>
														<?
															if($filade)	{
																if(!$ConfImpDiagnostico||($ConfImpDiagnostico&&$ImpCodDiagnostico&&$ImpNomDiagnostico))	{
																	echo "<p class='letra1'>".$filade[0]." ".$filade[1]."</p>";
																}
															} else {
																echo "&nbsp;";
															}
																	
														?>
													</td>
																
												</tr>
														
												
														
												<tr>
													<td><p class="subtitulo2">ASEGURADORA:</p></td>
													<td><? echo "<p class='letra1'>".$filaPxS[0]." ".$filaPxS[1]." ".$filaPxS[2]." ".$filaPxS[3]."</p>"?></td>
													<td width="15%">&nbsp;</td>
													<td><p class="subtitulo2">REGIMEN: </p></td>
													<td><? echo "<p class='letra1'>".$filaPxS[4]."</p>";?></td>
												</tr>
												
												<tr>
													<td> <p class="subtitulo2">TIPO DE USUARIO: </p></td>
													<td><? echo "<p class='letra1'".$Paciente[27]."</p>"?></td>
													<td width="15%">&nbsp;</td>
													<td><p class="subtitulo2">NIVEL DE USUARIO:</p></td>
													<td><? echo "<p class='letra1'".$Paciente[28]."</p>"?></td>
												</tr>
												
												<tr>
													<td><p class="subtitulo2">FECHA DE REGISTRO: </p></td>
													<td>
														<? echo "<p class='letra1'>".$fila[5]." ".$fila[6]."</p>";?>
													</td>
												</tr>
														
														
											</table>
											
							</td>
						
											
						</tr>
						
						<tr>
							<td style="text-align:center;">
								<p class="subtitulo1"> DATOS ACUDIENTE </p>
							</td>
						</tr>
						
						<tr>
							<td>
								<table style = "text-align:left;" cellpadding="2px" width="100%" border="0">
									<tr>
										<td width="20%"><p class="subtitulo2">TIPO DE DOCUMENTO: </p></td>
										<td width="25%"><p class="letra1" style="word-spacing:7px;"><? echo $Paciente[74];?></p></td>
										<td width="15%">&nbsp;</td>
										<td width="20%"><p class="subtitulo2">N&Uacute;MERO DE DOCUMENTO: </p></td>
										<td width="25%"><p class="letra1" style="word-spacing:7px;"><? echo $Paciente[65];?></p></td>
									</tr>
									
									<tr>
										<td><p class="subtitulo2">NOMBRE: </p></td>
										<td colspan="4"><p class="letra1" style="word-spacing:7px;"><? echo $Paciente[49];?></p></td>
									</tr>
									
									<tr>
										<td><p class="subtitulo2">DIRECCI&Oacute;N: </p></td>
										<td><p class="letra1" style="word-spacing:7px;"><? echo $Paciente[67];?></p></td>
										<td width="15%">&nbsp;</td>
										<td><p class="subtitulo2">TEL&Eacute;FONO: </p></td>
										<td><p class="letra1" style="word-spacing:7px;"><? echo $Paciente[68];?></p></td>
									</tr>
									
									<tr>
										<td><p class="subtitulo2">PARENTESCO: </p></td>
										<td><p class="letra1" style="word-spacing:7px;"><? echo $Paciente[69];?></p></td>
										<td width="15%">&nbsp;</td>
										<td><p class="subtitulo2">INSTITUCI&Oacute;N RESPONSABLE: </p></td>
										<td><p class="letra1" style="word-spacing:7px;"><? echo $Paciente[70];?></p></td>
									</tr>
									
								</table>	
							</td>
						</tr>
					</table>
				</div>
						
						
					<?	/*if($Titulo){?>   
							<center class="Estilo1"><span class="Estilo2"><? echo strtoupper($Titulo);?></span></center>
					<?	}
						else{?> 
							<center class="Estilo1"><span class="Estilo2"><? echo strtoupper($Formato);?></span></center>
					<?	}*/
							
					?>
					
					<div align="center">
								<?php
								
								$CrearTabla= 0;
								$cons99="Select * from HistoriaClinica.ItemsxFormatos where  TipoFormato='$TipoFormato' and Formato='$Formato' and Compania='$Compania[0]' and Estado='AC' Order By Pantalla,Orden";
								$res99=ExQuery($cons99);
								while($fila99=ExFetchArray($res99))	{ 
									$MatItems[$fila99['id_item']]=array($fila99['id_item'],$fila99['item'],$fila99['lineasola'],$fila99['cierrafila'],$fila99['titulo'],$fila99['imagen'],$fila99['subformato'],$fila99['tipocontrol'],$fila99['cargoxitem'],$fila99['alto'],$fila99['ancho']);
									$NumTotCmps++;
									//Dependencia
									$DatCampos[$fila99['id_item']]=array($fila99['id_item'],1,$fila99['item'],0,0);
									$consxx="select condedad1, edad1, condedad2, edad2, sexo, estadocivil, eps, tipousuario, nivel 
									from historiaclinica.dependenciahc where Compania='$Compania[0]' and Formato='$Formato' and Id_Item=".$fila99['id_item']." 
									and Item='".$fila99['item']."' and TipoFormato='$TipoFormato'";
									//echo $consxx."<br>";
									$resxx=ExQuery($consxx);
									
									while($filaxx=ExFetch($resxx)){
										if((($filaxx[0]&&$filaxx[1])&&(empty($filaxx[2])&&empty($filaxx[3])))||(($filaxx[0]&&$filaxx[1]&&$filaxx[2]&&$filaxx[3]))){$DatCampos[$fila99['id_item']][3]++;}				
										if($filaxx[4]){$DatCampos[$fila99['id_item']][3]++;}
										if($filaxx[5]){$DatCampos[$fila99['id_item']][3]++;}
										if($filaxx[6]){$DatCampos[$fila99['id_item']][3]++;}
										if($filaxx[7]){$DatCampos[$fila99['id_item']][3]++;}
										if($filaxx[8]){$DatCampos[$fila99['id_item']][3]++;}
										$MatDependenciaxItem[$fila99['id_item']]=array($filaxx[0],$filaxx[1],$filaxx[2],$filaxx[3],$filaxx[4],$filaxx[5],$filaxx[6],$filaxx[7],$filaxx[8]);
										//echo $filaxx[0]." --> ".$filaxx[1]." --> ".$filaxx[2]." --> ".$filaxx[3]." --> ".$filaxx[4]." --> ".$filaxx[5]." --> ".$filaxx[6]." --> ".$filaxx[6]." --> ".$filaxx[7]." --> ".$filaxx[8]."<br> ";
									}
									
								}
										
								
								if($Alineacion=="Horizontal"){
								
										?>
										<table cellpadding="3px;" border='0' bordercolor='#000' style='font: 12px Tahoma;text-align:justify' width="<?php echo $anchoTabla;?>">
											<tr style='font-weight:bold;text-align:center' bgcolor='#e5e5e5'>
												<td>Fecha</td>
												<td>Hora</td>
												<td>Usuario</td>
										<?php	
									foreach($MatItems as $Tits)	{
										echo "<td>".$Tits[1]."</td>";
									}
									echo "<td></td></tr><tr>";
								}
								
								$cons="Select * from HistoClinicaFrms.$Tabla where Cedula='$Paciente[1]' and Formato='$Formato' and TipoFormato='$TipoFormato' and Compania='$Compania[0]'
								and Id_Historia=$IdHistoria $condSF $Registro Order By Fecha Desc,Hora Desc,Id_historia Desc $CondPag";
								$res=ExQuery($cons,$conex);
								$NumTotReg=ExNumRows($res);
								
							while($fila=ExFetchArray($res))	{
									if($IncluirSignosVitales){			
										//$IdSVital=$fila['idsvital']
										if($fila['idsvital']){
											$conssv="Select AutoId,Fecha,Usuario,Temperatura,Pulso,Respiracion,TensionArterial1,TensionArterial2 from historiaclinica.signosvitales 
											where Compania='$Compania[0]' and Cedula='$Paciente[1]' and autoid=".$fila['idsvital'];
											$ressv=ExQuery($conssv);				
											$filasv=ExFetch($ressv);
										}
										else{
											$filasv="";	
										}
										if($filasv)	{					
										
											$cons3="Select usuarios.nombre, Cargo from central.usuarios,Salud.Medicos,salud.cargos where usuarios.usuario='$filasv[2]' 
											and usuarios.usuario=medicos.usuario and Medicos.compania='$Compania[0]' and cargos.compania='$Compania[0]' and cargos.asistencial=1 
											and (tratante=1 or vistobuenojefe=1 or vistobuenoaux=1) limit 1";
											$res3=ExQuery($cons3);
											$fila3=ExFetch($res3);
											?>
											<table cellspacing="4" border="0" bordercolor="#e5e5e5" style="font : normal normal small-caps 12px Tahoma; " width="<?php echo $anchoTabla;?>"> 
												<tr bgcolor='#e5e5e5' style="font-weight:bold">
													<td colspan="4">SIGNOS VITALES: <? echo $filasv[1]?></td>
													<td colspan="4">Registró: <? echo $fila3[0]?> - <? echo $fila3[1]?></td>
												</tr>
												<tr>
													<td bgcolor='#e5e5e5' style="font-weight:bold">Temperatura (ºC)</td>
													<td><? echo $filasv[3]?></td>
													<td bgcolor='#e5e5e5' style="font-weight:bold">Pulso (x min)</td>
													<td><? echo $filasv[4]?></td>
													<td bgcolor='#e5e5e5' style="font-weight:bold">Respiración (x min)</td>
													<td><? echo $filasv[5]?></td>
													<td bgcolor='#e5e5e5' style="font-weight:bold">Tension Arterial</td>
													<td><? echo $filasv[6]?>/<? echo $filasv[7]?></td>
												</tr>
											</table>
											<?
										}			
									}		
									if($Alineacion=="Vertical")	{
										?>
										<table rules="groups" width="<?php echo $anchoTabla; ?>" style="border-width:1px; border-style:solid; border-color:#000; font : 12px Tahoma;text-align:justify; vertical-align:middle;"   cellpadding="0" cellspacing="0" >
										<?php
									}
								
									if($Alineacion=="Horizontal"){
										echo "<td>".$fila['fecha']."</td><td>".$fila['hora']."</td><td>".$fila['usuario']."</td>";
									}
									$iii = 0;
									foreach($MatItems as $IndItems)	{
										//--cargo
										//echo $IndItems[0]." -- ".$IndItems[8]."<br>";
										if($IndItems[8]){
										
											$DiligItem="usu".strtolower(str_replace(" ","",$IndItems[8]));												
											if($fila[$DiligItem]){
											
												$MatProfesionales[$IndItems[8]]=array($IndItems[8],$fila[$DiligItem]);	
												if($UsuMed==$fila[$DiligItem]){
													$UsuCreaIgual=1;	
												}				
											}					
											else{$MatProfesionales[$IndItems[8]]=array($IndItems[8],"NA");}	
										}
										
										//--Dependencia HC
										$NoCamposCumple=0;			
										
										if(empty($DatCampos[$IndItems[0]][3])||$DatCampos[$IndItems[0]][3]==0){
											//nadaaa
										}
										else{			
											//echo $DatCampos[$IndItems[0]][0]." --> ".$DatCampos[$IndItems[0]][2]." --> ".$DatCampos[$IndItems[0]][3]."<br>";				
											if(!empty($MatDependenciaxItem[$DatCampos[$IndItems[0]][0]][0])&&!empty($MatDependenciaxItem[$DatCampos[$IndItems[0]][0]][1])&&!empty($MatDependenciaxItem[$DatCampos[$IndItems[0]][0]][2])&&!empty($MatDependenciaxItem[$DatCampos[$IndItems[0]][0]][3])) {
												
												$Operador=$MatOperadores[$MatDependenciaxItem[$DatCampos[$IndItems[0]][0]][0]];					
												$Operador1=$MatOperadores[$MatDependenciaxItem[$DatCampos[$IndItems[0]][0]][2]];					
												eval("
												if(\$MatDatosPaciente[0] $Operador  \$MatDependenciaxItem[\$DatCampos[\$IndItems[0]][0]][1] && \$MatDatosPaciente[0] $Operador1  \$MatDependenciaxItem[\$DatCampos[\$IndItems[0]][0]][3])
												{
													\$NoCamposCumple++;
												}");					
											}
											elseif(!empty($MatDependenciaxItem[$DatCampos[$IndItems[0]][0]][0])&&!empty($MatDependenciaxItem[$DatCampos[$IndItems[0]][0]][1]))	{								
												
												$Operador=$MatOperadores[$MatDependenciaxItem[$DatCampos[$IndItems[0]][0]][0]];					
												eval("
												if(\$MatDatosPaciente[0] $Operador  \$MatDependenciaxItem[\$DatCampos[\$IndItems[0]][0]][1])
												{
													\$NoCamposCumple++;
												}");					
											}	
											
											if(!empty($MatDependenciaxItem[$DatCampos[$IndItems[0]][0]][4])){
											
												if($MatDependenciaxItem[$DatCampos[$IndItems[0]][0]][4]==$MatDatosPaciente[1]){
													$NoCamposCumple++;
												}		
											}				
											if(!empty($MatDependenciaxItem[$DatCampos[$IndItems[0]][0]][5])){
											
												if($MatDependenciaxItem[$DatCampos[$IndItems[0]][0]][5]==$MatDatosPaciente[2]){
													$NoCamposCumple++;	
												}	
											}
											if(!empty($MatDependenciaxItem[$DatCampos[$IndItems[0]][0]][6])){
												
												if($MatDependenciaxItem[$DatCampos[$IndItems[0]][0]][6]==$MatDatosPaciente[3]){
													$NoCamposCumple++;
												}		
											}	
											
											if(!empty($MatDependenciaxItem[$DatCampos[$IndItems[0]][0]][7])){
												
												if($MatDependenciaxItem[$DatCampos[$IndItems[0]][0]][7]==$MatDatosPaciente[4]){	
													$NoCamposCumple++;			
												}	
											}
											
											if(!empty($MatDependenciaxItem[$DatCampos[$IndItems[0]][0]][8])){
												
												if($MatDependenciaxItem[$DatCampos[$IndItems[0]][0]][8]==$MatDatosPaciente[5]){
													$NoCamposCumple++;			
												}		
											}
											
											if($NoCamposCumple==$DatCampos[$IndItems[0]][3]){							
												$DatCampos[$DatCampos[$IndItems[0]][0]][4]=$NoCamposCumple;
												//echo "Cumple --> ".$DatCampos[$IndItems[0]][0]." --> ".$DatCampos[$IndItems[0]][2]." --> ".$DatCampos[$IndItems[0]][3]." --> ".$DatCampos[$DatCampos[$IndItems[0]][0]][4]."<br>";		
											}				
										}	
										//--
										if(empty($DatCampos[$IndItems[0]])||($DatCampos[$IndItems[0]][3]==$DatCampos[$IndItems[0]][4]))	{
										
											$IdItem=$IndItems[0];
											$Item=$IndItems[1];
											$LineaSola=$IndItems[2];
											$Titulo=$IndItems[4];
											$Imagen=$IndItems[5];
											$SubFormato=$IndItems[6];
											$TipoCont=$IndItems[7];
											
											$NumCamp="cmp".substr("00000",0,5-strlen($IdItem)).$IdItem;
											$Mensaje=$Item;
											
											if($CrearTabla==1 && $Titulo==1 && $Alineacion=="Vertical"){
												echo "</table>";
												$CrearTabla=0;
											}
											
											if($Titulo==1){
												if($Alineacion=="Vertical")	{
												
													if($Mensaje=="<b> INFORMACIÓN MÉDICO </b>" || $Mensaje=="<b> INFORMACI&Oacute;N M&Eacute;DICO </b>"){
														$extra="display:none;";
													}
													else{
														$extra="";
													}

																									
													if ($fila['formato']!="NOTAS EVOLUCION") {
														?>
														<tr>
															<td colspan='99' bgcolor="#E5E5E5"  style="font-weight:bold;background-color:#E5E5E5;text-align:center; vertical-align:middle;padding-top:5px;padding-bottom:5px;font-size:12px;<?php echo $extra; echo $oculto;?>">
																
																<?php
																	// Aqui imprime los titulos
																	echo $Mensaje;
																?>
															</td>
														</tr>
														<?php
													}
													
													
													
													if($Mensaje=="Diagnostico")	{
														?>
														<table border='1' bordercolor="#333"  style='font : 12px Tahoma;text-align:justify' cellpadding="10px" cellspacing="0" width="<?php echo $anchoTabla;?>">
														<table border='1' bordercolor="#333"  style='font : 12px Tahoma;text-align:justify' cellpadding="10px" cellspacing="0" width="<?php echo $anchoTabla;?>">
														<?php
														$cons8="Select Detalle,CIE10,Id from historiaclinica.dxformatos where Compania='$Compania[0]' and Estado='AC' and Formato='$Formato' and TipoFormato='$TipoFormato' Order By Id";
														$res8=ExQuery($cons8);
														
															while($fila8=ExFetch($res8)){
																if($fila8[1]!=1){
																	$Colspan=3;
																	$Width="480px";
																}else{
																	$Colspan=1;
																	$Width="300px";
																}
																
																$cons19="Select Diagnostico from Salud.CIE where Codigo='".$fila['dx'.$fila8[2]]."'";
																$res19=ExQuery($cons19);
																$fila19=ExFetch($res19);
																$DetValDx=$fila19[0]; 
																if($fila['dx'.$fila8[2]]){
																	?>
																	<tr>
																		<td><? echo $fila8[0]?></td>
																		<?
																		if(!$ConfImpDiagnostico||($ConfImpDiagnostico&&$ImpCodDiagnostico))	{
																			?>
																			<td colspan="<? echo $Colspan?>" <? if($Colspan==1){ echo "style='background:#e5e5e5'";}?> align="center">
																				<strong ><? echo $fila['dx'.$fila8[2]] ?></strong>
																			</td>
																			<?
																		}
																		
																		if($fila8[1]==1){											
																			if(!$ConfImpDiagnostico||($ConfImpDiagnostico&&$ImpNomDiagnostico))	{
																				?>
																				<td>
																					<? echo $DetValDx?>
																				</td>
																				<?
																			}
																		}
																	if($fila8[2]==1){
																		$cons45="Select TipoDiagnost from Salud.TiposDiagnostico where Compania='$Compania[0]' and Codigo='".$fila['tipodx']."'";
																		$res45=ExQuery($cons45);
																		$fila45=ExFetch($res45);
																		$TipDx=$fila45[0];
																		
																		?>
																		<td style="background:#e5e5e5" align="center">
																		<strong><? echo $TipDx; ?></strong>											
																		<?											
																	}
																	else{
																		//echo "<td>&nbsp";
																	}
																	echo "</td></tr>";										
																											
																}
															}
														echo "</table>";
														?>
														<table border='1' bordercolor='#e5e5e5' style='font : 12px Tahoma;text-align:justify' width="<?php echo $anchoTabla;?>"  >
														
														<tr>
														<?	$cons45="select causa from salud.causaexterna where codigo='".$fila['causaexterna']."'";
															$res45=ExQuery($cons45);
															$fila45=ExFetch($res45);
															$CausaExterna=$fila45[0];
															
															$cons45="select finalidad from salud.finalidadesact where codigo='".$fila['finalidadconsult']."' and tipo=1";
															$res45=ExQuery($cons45);
															$fila45=ExFetch($res45);
															$FinalidadConsulta=$fila45[0];
															/* ?>
															<td style="background:#e5e5e5">
																<strong>Causa Externa: </strong></td>
															<td><? echo $CausaExterna?></td>
															<td style="background:#e5e5e5">
																<strong>Finalidad Consulta: </strong></td>
															<td>	
																<? echo $FinalidadConsulta?>
															</td>
															<? */ ?>
														</tr>
														
														</table>
														
														<table   width="<?php echo $anchoTabla;?>"  style="border-width:1px; border-style:solid; border-color:#000; font : 12px Tahoma;text-align:justify; vertical-align:middle;"   cellpadding="0" cellspacing="0" >
														<?	
													}
													
													if($Mensaje=="Medicamento No Pos"){
														$cons19="select detalle,posologia from salud.plantillamedicamentos where compania='$Compania[0]' and tipoformato='$TipoFormato' and cedpaciente='$Paciente[1]' and formato='$Formato' and id_historia=".$fila['id_historia'];
														$res19=ExQuery($cons19);
														$fila19=ExFetch($res19);
														echo "<tr><td><strong>Principio Activo:</strong> $fila19[0]</td></tr><tr><td><strong>Posologia: </strong>$fila19[1]</td></tr>";
													}
													
													if($Mensaje=="CUP No Pos")	{
														$cons19="select cup,nombre from salud.plantillaprocedimientos,contratacionsalud.cups
														where plantillaprocedimientos.compania='$Compania[0]' and tipoformato='$TipoFormato' and cedula='$Paciente[1]' and formato='$Formato' 
														and cup=codigo and cups.compania='$Compania[0]' and id_historia=".$fila['id_historia'];
														$res19=ExQuery($cons19);
														$fila19=ExFetch($res19);
														echo "<tr><td><strong>Codigo CUP:</strong> $fila19[0]</td></tr><tr><td><strong>Nombre CUP: </strong>$fila19[1]</td></tr>";
													}
												}
											}
											elseif ($Titulo != 1){
												if($Alineacion=="Vertical")	{
													if($Mensaje=="<b> INFORMACIÓN MÉDICO </b>" || $Mensaje=="<b> INFORMACI&Oacute;N M&Eacute;DICO </b>"){
														$extra="display:none;";
													}
													else{
														$extra="";
													}

													//echo $fila['cmp00004']." - ".$fila['cmp00006']." - ".$fila['cmp00008']." - ".$fila['cmp00012']." - ";
																			
													/*$oculto = "";
													if(($fila['cmp00004']=="") && ($fila['cmp00006']=="") && ($fila['cmp00008']=="") && ($fila['formato']=="NOTAS EVOLUCION")){
														$oculto = "visibility:hidden; display:none; width:0px;";
													}

													if(($Mensaje=="<b> PLAN </b>")&&($fila['formato']=="NOTAS EVOLUCION")){
														$oculto = "";
													}*/
																			
													if($CrearTabla==1 && $LineaSola==1){
														echo "</table>";
														$CrearTabla=0;
													}
													if($LineaSola==1){
														if($fila['formato']!=="NOTAS EVOLUCION"){
															echo "<tr>";
																echo "<td colspan='99'  style='$extra $oculto'>";
																//echo $Mensaje; Aqui imprime el nombre del campo
																echo "</td>";
															echo "</tr>";
															echo"<tr>";
																echo "<td colspan='99'>";
														}
													}
													elseif($LineaSola==0){
														if($CierraFila){
															echo "</tr> <tr>";
														}
															if(!$CrearTabla){
																echo "<tr><td>";
																	if($SubFormato==1){
																		$Ww=" width='<?php echo $anchoTabla;?>'";
																	}									
																	?>
																	<table border='0'  cellpadding="2" style='font : 12px Tahoma;' width="<?php echo $anchoTabla;?>">
																		<tr>
																<?php
																$CrearTabla=1;
															}
														if(!$SubFormato){	
																					
																echo "<td style='font-weight:bold;'>".$Mensaje.":</td><td>";
															
														}
														else {//frame subformato
															$DivFor=explode("/",$IndItems[1]);
															$SFTF=$DivFor[0];$SFFormato=$DivFor[1];							
															echo "</tr>";	
															?> 
															<tr>
																<td colspan="99" align="center">
																	<iframe name="SubF_<? echo $fila['id_historia']."_".$IndItems[0]?>" id="SubF_<? echo $fila['id_historia']."_".$IndItems[0]?>" style="width:<? echo $IndItems[10]?>; height:<? echo $IndItems[9]?>" src="Datos.php?DatNameSID=<? echo $DatNameSID ?>&IdHistoOrigen=<? echo $fila['id_historia']?>&IdItemSF=<? echo $IndItems[0]?>&SFFormato=<? echo $Formato ?>&SFTF=<? echo $TipoFormato ?>&TipoFormato=<? echo $SFTF ?>&Formato=<? echo $SFFormato?>&IdHistoria=<? //echo $IdHistoria?>&SoloMuestra=1" frameborder="0"></iframe> 
																</td>
															</tr>
														   <?	
														}
													}
												}
											}

											if (strtoupper($TipoCont) == "MEDICAMENTOS MULTILINEA" or strtoupper($TipoCont) == "MEDICAMENTOS UNILINEA" ){									
																
												mostrarMedsxFormato($Formato,$TipoFormato,$fila['id_historia'], $Paciente[1], $Compania[0], $IdItem);
															
											}
											
											if (strtoupper($TipoCont) == "PROCEDIMIENTOS MULTILINEA" or strtoupper($TipoCont) == "PROCEDIMIENTOS UNILINEA" ){									
																
												mostrarProcedxFormato($Formato,$TipoFormato,$fila['id_historia'], $Paciente[1], $Compania[0], $IdItem);
															
											}
										$CierraFila=$IndItems[3];
										if($fila['imagen']){
											echo "<img src='".$fila['imagen']."'>";
										}
										$fila[$NumCamp]=str_replace("\n","<br>",$fila[$NumCamp]);
										if($Titulo!=1 && !$Imagen && !$SubFormato && $Alineacion=="Vertical"){
											if($IndItems[7]=="PDF"){
												if($fila[$NumCamp]){
													$RAIZ=$_SERVER['DOCUMENT_ROOT'];
													$Mostrar=str_replace("$RAIZ/HistoriaClinica/ImgsLabs/"," ",$fila[$NumCamp]);?>
													<ul><div style="cursor:hand" title="Ver" onClick="VerPDF('<? echo $fila[$NumCamp]?>')"><? echo $Mostrar?></a></div>
												<?		
												}
											}
											else{
													if($TipoCont=="Cuadro de Chequeo")	{
														?><input type='checkbox' name='<? echo $Mensaje?>' <? if($fila[$NumCamp]=='Si'){ echo "checked";}?> readonly onClick="this.checked=!this.checked"><?
														
													}
													else{											
														if($fila['formato']=="NOTAS EVOLUCION"){
															if($iii==0){
																$iii = 1;
																echo "<tr>";
																			echo "<td style='padding-top:15px;padding-bottom:15px;padding-left:50px;padding-right:50px;'>";
																				$fila['cmp00004'] = trim($fila['cmp00004']) ;
																				$fila['cmp00006'] = trim($fila['cmp00006']) ;
																				$fila['cmp00008'] = trim($fila['cmp00008']) ;
																				$fila['cmp00012'] = trim($fila['cmp00012']) ;
																				
																				if (strlen($fila['cmp00004'])>1){
																					echo "<p style='font-weight:bold;margin-top:5px;'>Subjetivo</p>";
																					echo "<p>".$fila['cmp00004']."</p>";
																				}
																				
																				if (strlen($fila['cmp00006'])>1){												
																					echo "<p style='font-weight:bold;margin-top:5px;'>Objetivo</p>";
																					echo "<p>".$fila['cmp00006']."</p>";
																				}	
																				
																				if (strlen($fila['cmp00008'])>1){												
																					echo "<p style='font-weight:bold;margin-top:5px;'>An&aacute;lisis</p>";
																					echo "<p>".$fila['cmp00008']."</p>";
																				}
																				if (strlen($fila['cmp00012'])>1){												
																					echo "<p style='font-weight:bold;margin-top:5px;'>Plan</p>";
																					echo "<p>".$fila['cmp00012']."</p>";
																				}
																			echo "</td>";
																echo "</tr>";
															}
															
														}
														elseif (($fila['formato']=="TRIAGE") and (strtoupper($Mensaje) == "PRIORIDAD") ) {
																
																$descTriage = descripcionTriage($fila[$NumCamp]);
																
																echo "<p style='padding-top:7px;padding-bottom:7px;padding-left:50px;padding-right:50px;'>";
																	echo $fila[$NumCamp]." ".$descTriage ;
																echo "</p>";	
																
														}
														else {
														
															echo "<p style='padding-top:15px;padding-bottom:15px;padding-left:50px;padding-right:50px;'>".$fila[$NumCamp]."</p>";
															// Aqui imprime los campos TextArea
														
														}
														
													}
													
												
												}
											}
										
										
										if($Titulo!=1 && !$Imagen && !$SubFormato && $Alineacion=="Horizontal")	{
											if($usuario[1]==$fila['usuario']){
											
												$date1=$fila['fecha'] ." " . $fila['hora'];
												$date2="$ND[year]-$ND[mon]-$ND[mday] $ND[hours]:$ND[minutes]:$ND[seconds]";
												$s = strtotime($date2)-strtotime($date1);
												$d = intval($s/86400);
												$s -= $d*86400;
												$d = $d*1440;
												$m = intval($s/60) + $d;
												if($m<$TiempoAjuste){}
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
										elseif($Alineacion=="Horizontal"&&$Mensaje=="Diagnostico")	{
										
											echo "<td>";																					
											$cons8="Select Detalle,CIE10,Id from historiaclinica.dxformatos where Compania='$Compania[0]' and Estado='AC' and Formato='$Formato' and TipoFormato='$TipoFormato' Order By Id";
											$res8=ExQuery($cons8);
											while($fila8=ExFetch($res8))
											{								
												$cons19="Select Diagnostico from Salud.CIE where Codigo='".$fila['dx'.$fila8[2]]."'";
												$res19=ExQuery($cons19);
												$fila19=ExFetch($res19);
												$DetValDx=$fila19[0]; 
												if($fila['dx'.$fila8[2]])
												{								
													//echo $fila8[0]."  ";										
													if(!$ConfImpDiagnostico||($ConfImpDiagnostico&&$ImpCodDiagnostico))
													{
														echo $fila['dx'.$fila8[2]]."  ";								 	
													}
													if($fila8[1]==1)
													{											
														if(!$ConfImpDiagnostico||($ConfImpDiagnostico&&$ImpNomDiagnostico))
														{
															echo $DetValDx."  ";
														}
													}																																			
												}
											}
											echo "</td>";			
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
									if($Alineacion=="Vertical"){
										echo "</table><br><br>";
									}else{
										if($Lapiz){
											echo "<td>$Lapiz</td>";
										}
										
									echo "</tr>";}
									
									
									
									if($UsuMed&&!$UsuCreaIgual){
										$cons="select rm,cargo from salud.medicos where compania='$Compania[0]' and usuario='$UsuMed'";
										$res=ExQuery($cons);
										$fila=ExFetch($res);
										$RM=$fila[0];
										$Cargo=$fila[1];
										$cons="select cedula from central.usuarios where usuario='$UsuMed'";			
										$res=ExQuery($cons);
										$fila=ExFetch($res);
										if($FirmaPacienteF){$wi="width='<?php echo $anchoTabla;?>'"; $bor="border='0'";}else{$bor="border='1'";}
										$bor = 0;
										?>
										
									
										<table  width="<?php echo $anchoTabla;?>" cellpadding="0" cellspacing="0"   style="border-width:0px; font : 12px Tahoma;text-align:center; vertical-align:middle;"  >
											<tr align="center">
												<td><center><? echo $filaMed[0]?></center></td>
												<? 
												if($FirmaPacienteF){ 
													?>
													<td rowspan="4">
														<br><br><br>
														<img src='/Imgs/HistoriaClinica/FirmaPacienteF.jpg' style='width:180px; height:130px'>
													</td>
													<? 
												}
												?>
											</tr>
												<?php
													$RAIZ=$_SERVER['DOCUMENT_ROOT'];
													$dimensiones = calcularDimensiones("$RAIZ/Firmas/$fila[0].GIF",200);
												
													$styleAncho = "width: ".$dimensiones['ancho']."px ;";
													$styleAlto = "height: ".$dimensiones['alto']."px ;";
													$styleImagen = "style= '".$styleAncho.$styleAlto."'";
												?>
											
											<tr align="center">
												<td><center><img src='/Firmas/<? echo "$fila[0].GIF"?>' <?php echo $styleImagen; ?> ></center></td>
											</tr>
											
											<tr align="center">
												<td><? echo $Cargo?></td>
											</tr>
											<tr align="center">
												<td><? echo "Registro Medico $RM"?></td>
											</tr>
										</table>
									
									
										
									
										<?	
									}
									elseif($MatProfesionales){
											$cont=0;
											?>
											<table border="1" bordercolor="#000" style='font : normal normal small-caps 13px Tahoma;' align="center" width="<?php echo $anchoTabla;?>">
												<tr align="center">
													<?
													$RAIZ=$_SERVER['DOCUMENT_ROOT'];			
													foreach($MatProfesionales as $CargoProf){
													
														$cons="select rm,cargo from salud.medicos where compania='$Compania[0]' and usuario='$CargoProf[1]'";
														$res=ExQuery($cons);
														$fila=ExFetch($res);
														$RM=$fila[0];				
														$cons="select cedula,nombre from central.usuarios where usuario='$CargoProf[1]'";			
														$res=ExQuery($cons);
														$fila=ExFetch($res);			
														$cont++;
														
														if(is_file("$RAIZ/Firmas/$fila[0].GIF")){
															$Img="<img src='/Firmas/$fila[0].GIF' style='width:80px; height:50px'><br>";
															}else{
																$Img="<img src='/Imgs/FirmaTransparente.GIF' style='width:160px; height:100px'><br>";
															}
														if($CargoProf[1]!="NA"){$NombreMedico=$fila[1];}else{$NombreMedico="";}				
														echo "<td>$Img <strong>$NombreMedico</strong><br>$CargoProf[0] R.M. $RM</td>";
														//echo $CargoProf[0]." --> ".$CargoProf[1]."<br>";
														if($cont>2){echo "</tr><tr align='center'>";$cont=0;}
													}
													
													if($FirmaPacienteF)	{
														?>
														<td><br><br><br><img src='/Imgs/HistoriaClinica/FirmaPacienteF.jpg' style='width:180px; height:100px'></td>
														<? 			
													}
											
													$filaImp="";	
								
									}
									
								echo "</table>";
							
								
							}
							
					echo "</div>";			
								
							
						if(strtoupper($alineacionFormato)== "VERTICAL"){
							?>
							<hr width="100%" style="border-width:2px; border-style:solid; border-color:#0068D4; margin-top:25px; margin-bottom:50px;" size="1px">
							<?php
						} 
						else {
							?>
							<hr width="<?php echo $anchoTabla;?>" style="border-width:2px; border-style:solid; border-color:#0068D4; margin-top:25px; margin-bottom:50px;" size="1px">
							<?php
						}
			}
							?>
						
						
	