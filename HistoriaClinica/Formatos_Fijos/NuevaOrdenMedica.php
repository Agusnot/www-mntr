<?php
	if($DatNameSID){session_name("$DatNameSID");}
	session_start();
	include("Funciones.php");
	$ND = getdate();
	
	function definirServicio($compania, $paciente){
		$consServ =  "Select numservicio,tiposervicio from salud.servicios where compania='$compania' and cedula='$paciente' and estado='AC'";
		$resServ = ExQuery($consServ);
		$filaServ = ExFetch($resServ);
		$numservicio = $filaServ[0];
		return $numservicio;
	}
	
	
	function definirPabellon($paciente, $numservicio){
		//Define si el paciente esta ubicado en un pabellon 
		if ($numservicio == NULL){
			$cons = "SELECT numservicio FROM Salud.Servicios WHERE cedula = '$paciente' AND UPPER(tiposervicio) = 'URGENCIAS' ORDER BY numservicio DESC LIMIT 1 ";
			$res = ExQuery($cons);
			$fila = ExFetchArray($res);
			$numservicio = $fila['numservicio'];
			$pabellonUrgencias = 0;
		}
		
		$consBusqPab  = "SELECT pabellon FROM Salud.PacientesxPabellones WHERE cedula = '$paciente' AND numservicio = '$numservicio' AND estado = 'AC'";
		
		if ($pabellonUrgencias == 0){
			$consBusqPab  = "SELECT pabellon FROM Salud.PacientesxPabellones WHERE cedula = '$paciente' AND numservicio = '$numservicio' ";
			
		} 
		
		$resBusqPab = ExQuery($consBusqPab);
		$filaBusqPab = ExFetch($resBusqPab);
		$pabellon = $filaBusqPab[0];
		return $pabellon;
	
	}
	
	
	
	
	
	
	
	if(!$IdEscritura){
		//echo "entra";
		$cons="select idescritura from salud.ordenesmedicas where compania='$Compania[0]' and cedula='$Paciente[1]' order by idescritura desc";
		$res=ExQuery($cons);echo ExError();
		$fila=ExFetch($res);
		$IdEscritura=$fila[0]+1;
		
	}
	//echo $IdEscritura;
	if($CerrarOrden){
		/*$cons4 = "Select numorden from salud.ordenesmedicas where cedula='$Paciente[1]' and Compania = '$Compania[0]' and idescritura=$IdEscritura order by numorden desc";
		//echo $cons4;
		$res4=ExQuery($cons4);		
		//$cons="update salud.ordenesmedicas set estado='AN' where acarreo=0 and cedula='$Paciente[1]' and compania='$Compania[0]'";
		//$res=ExQuery($cons);
	/*	if(ExNumRows($res4)>0){				
			if($IdEscritura>1){							
				$fila4 = ExFetch($res4);			
				$Numorden = $fila4[0]+1;			
				$IdAux=$IdEscritura-1;
				
				$cons="select numservicio,detalle,tipoorden,posologia from salud.ordenesmedicas 
				where cedula='$Paciente[1]' and compania='$Compania[0]' and acarreo=1 and idescritura=$IdAux and estado='AC'";
				$res=ExQuery($cons);
				//echo $cons;
				while($fila=ExFetch($res))
				{
					$cons3="insert into salud.ordenesmedicas(compania,fecha,cedula,numservicio,detalle,idescritura,numorden,usuario,tipoorden,estado,acarreo,posologia) values ('$Compania[0]','$ND[year]-$ND[mon]-$ND[mday] $ND[hours]:$ND[minutes]:$ND[seconds]','$Paciente[1]',$fila[0],'$fila[1]',$IdEscritura,'$Numorden','$usuario[1]','$fila[2]','AC',1,'$fila[3]')";
					$res3=ExQuery($cons3);
					$cons3="update salud.ordenesmedicas set estado='AN',acarreo=0 where cedula='$Paciente[1]' and compania='$Compania[0]' and estado='AC' and idescritura=$IdAux";
					$res3=ExQuery($cons3);
					$Numorden++;
				}
			}		
		}		
		/*$cons = "Select idescritura from salud.ordenesmedicas where cedula='$Paciente[1]' and Compania = '$Compania[0]' order by idescritura desc";
		$res=ExQuery($cons);
		$fila=ExFetch($res);
		$LastID=$fila[0];
		//echo $LastID;
		if($LastId!=''){/*
			$cons = "Select numorden from salud.ordenesmedicas where cedula='$Paciente[1]' and Compania = '$Compania[0]' and idescritura=$LastID order by numorden desc";		
			$res=ExQuery($cons);					
			//echo $cons4;
			$fila=ExFetch($res);		
			$Numorden=$fila[0]+1;
			$cons="select numservicio,detalle,tipoorden,idescritura from salud.ordenesmedicas 
			where cedula='$Paciente[1]' and compania='$Compania[0]' and acarreo=1 and idescritura!=$LastID and estado='AC'";
			$res=ExQuery($cons);
			echo $cons;
			while($fila=ExFetch($res))
			{
				$cons3="insert into salud.ordenesmedicas(compania,fecha,cedula,numservicio,detalle,idescritura,numorden,usuario,tipoorden,estado,acarreo) values 	
				('$Compania[0]','$ND[year]-$ND[mon]-$ND[mday] $ND[hours]:$ND[minutes]:$ND[seconds]','$Paciente[1]',$fila[0],'$fila[1]',$LastID,'$Numorden','$usuario[1]','$fila[2]','AC',1)";
				$res3=ExQuery($cons3);
				$cons3="update salud.ordenesmedicas set estado='AN',acarreo=0 where cedula='$Paciente[1]' and compania='$Compania[0]' and estado='AC' and idescritura=$fila[3]";
				$res3=ExQuery($cons3);
				$Numorden++;
			}
		}*/
	?>	<script language="javascript">
			location.href='OrdenamientoMedico.php?DatNameSID=<? echo $DatNameSID?>';
		</script><?
	}
	if($Suspender&&!$CerrarOrden&&!$ReescribirAC){		
		$cons="select detalle,posologia from salud.ordenesmedicas where idescritura='$IdEsc' and numorden='$NumOrd' and compania='$Compania[0]' and cedula='$Paciente[1]' 
		and numservicio='$Numserv'";
		$cons="select detalle,posologia from salud.ordenesmedicas where idescritura='$IdEsc' and numorden='$NumOrd' and compania='$Compania[0]' and cedula='$Paciente[1]'";
		$res=ExQuery($cons);echo ExError();
		$fila=ExFetch($res); 
		$DetalleOrd=$fila[0];
		$PosoSusp=$fila[1];
		if($DetalleOrd!='Ingreso'||$DetalleOrd!='Traslado de Unidad'){
			$cons="update salud.ordenesmedicas set estado='AN',acarreo=0,fechareprog=null
			where idescritura='$IdEsc' and numorden='$NumOrd' and compania='$Compania[0]' and cedula='$Paciente[1]' and numservicio='$Numserv'";		
			$res=ExQuery($cons);	
			//echo $cons."<br>";
			/*$cons2 = "Select idescritura from salud.ordenesmedicas where cedula='$Paciente[1]' and Compania = '$Compania[0]' order by idescritura desc";									
			$res2 = ExQuery($cons2);				
			$fila2 = ExFetch($res2);			
			$Id = $fila2[0];*/
			//echo "$Id<br>";
		
			$cons2 = "Select numorden from salud.ordenesmedicas where cedula='$Paciente[1]' and Compania = '$Compania[0]' and idescritura=$IdEscritura order by numorden desc";									
			$res2 = ExQuery($cons2);				
			$fila2 = ExFetch($res2);
			$Numorden = $fila2[0]+1;
				//echo $cons2."<br>";
			$Detallesupen='Suspensión Orden '.$DetalleOrd;
			 
			$consSusp = "insert into salud.ordenesmedicas (compania,fecha,cedula,numservicio,detalle,idescritura,numorden,usuario,tipoorden,estado,acarreo,posologia) 
 	   values ('$Compania[0]','$ND[year]-$ND[mon]-$ND[mday] $ND[hours]:$ND[minutes]:$ND[seconds]','$Paciente[1]',$Numserv,'$Detallesupen',$IdEscritura,$Numorden,'$usuario[1]','Suspension','AC',0,'$PosoSusp')";
			$resSusp=ExQuery($consSusp);
			//echo $consSusp."<br>";
			$Tipo = strtoupper($Tipo);
			switch($Tipo){
				case 'PROCEDIMIENTO':	$cons="update salud.plantillaprocedimientos set fechafin='$ND[year]-$ND[mon]-$ND[mday]',estado='AN'  
										where compania='$Compania[0]' and cedula='$Paciente[1]' and numservicio=$Numserv and estado='AC' and detalle='$DetalleOrd'";							
										$res=ExQuery($cons); break;
				case 'INGRESO'		:	?><script language="javascript">alert("No puede suspender una orden de Ingreso!!!");</script><?	 break;			
				case 'EGRESO'		:	?><script language="javascript">alert("No puede suspender una orden de Egreso!!!");</script><?	 break;			
				case 'TRASLADO DE UNIDAD': ?><script language="javascript">alert("No puede supender una orden de Traslado de Unidad!!!");</script><? 
				case 'INTERPROGRAMA':	$cons="update salud.plantillainterprogramas set fechafin='$ND[year]-$ND[mon]-$ND[mday]',estado='AN'  
										where compania='$Compania[0]' and cedula='$Paciente[1]' and numservicio=$Numserv and estado='AC' and detalle='$DetalleOrd'";
										$res=ExQuery($cons); break;
				case 'DIETA'		:	$cons="update salud.plantilladietas set fechafin='$ND[year]-$ND[mon]-$ND[mday]',estado='AN'  
										where compania='$Compania[0]' and cedula='$Paciente[1]' and numservicio=$Numserv and estado='AC' and detalle='$DetalleOrd'";
										$res=ExQuery($cons);break;
				case 'NOTA'			:	$cons="update salud.plantillanotas set fechafin='$ND[year]-$ND[mon]-$ND[mday]',estado='AN'  
										where compania='$Compania[0]' and cedula='$Paciente[1]' and numservicio=$Numserv and estado='AC' and nota='$DetalleOrd'";
										$res=ExQuery($cons);	break;
				
				case 'MEDICAMENTO PROGRAMADO'	:	$cons="update salud.plantillamedicamentos set fechafin='$ND[year]-$ND[mon]-$ND[mday]',estado='AN'  
										where compania='$Compania[0]' and cedpaciente='$Paciente[1]' and numservicio=$Numserv and estado='AC' and idescritura=$IdEsc and numorden=$NumOrd
										"; //echo $cons."<br>";
										$res=ExQuery($cons);
										
										
										$cons="update salud.horacantidadxmedicamento set estado='AN' where  compania='$Compania[0]' and paciente='$Paciente[1]' 
										and idescritura=$IdEsc and numorden=$NumOrd";
										$res=ExQuery($cons);//echo $cons."<br>";
										
										break;
				case 'MEDICAMENTOS NO PROGRAMADOS'	:	$cons="update salud.plantillamedicamentos set fechafin='$ND[year]-$ND[mon]-$ND[mday]',estado='AN'  
										where compania='$Compania[0]' and cedpaciente='$Paciente[1]' and numservicio=$Numserv and estado='AC' and detalle='$DetalleOrd'";
										$res=ExQuery($cons);	break;
						
			}
		}		
		$Suspender="";
		//echo $cons;
	}
	
	if($ReescribirAC){
		$cons="select numorden from salud.ordenesmedicas where compania='$Compania[0]' and cedula='$Paciente[1]' and estado='AC' and acarreo=1
		and idescritura=$IdEscritura order by numorden desc";
		//echo $cons;
		$res=ExQuery($cons);
		$fila=ExFetch($res);		
		$cont=$fila[0]+1;
		
		$cons="select idescritura,numservicio,detalle,numorden,revisadopor,usuario,estado,acarreo,posologia,tipoorden,dosisunica
		from salud.ordenesmedicas 
		where compania='$Compania[0]' and cedula='$Paciente[1]' and estado='AC' and acarreo=1
		and tipoorden!='Ingreso' and tipoorden!='Suspencion' and tipoorden!='Suspension'  and tipoorden!='Traslado de Unidad' and tipoorden!='Orden Egreso'
		and tipoorden!='Procedimiento' order by idescritura desc,numorden desc";
		$res=ExQuery($cons);				
		while($fila=ExFetch($res)){
		
			//echo $fila[0]."-".$fila[3]."-".$fila[2]."<br>";
			if($fila[9]=='Medicamento Programado'){
				if($fila[0]!=$IdEscritura){
					// 12 de marzo de 2014
                    // Se consulta la plantillamedicamentos para duplicar sus valores para reescribir orden
                    $consp="select * from salud.plantillamedicamentos where compania='$Compania[0]' and cedpaciente='$Paciente[1]' 
					and numservicio=$fila[1] and idescritura=$fila[0] and numorden=$fila[3]";
					$resp=ExQuery($consp);
                    $filap=ExFetchAssoc($resp);
                                        
					$cons2="update salud.ordenesmedicas set estado='AN',acarreo=0 where cedula='$Paciente[1]' and compania='$Compania[0]' and estado='AC' and idescritura=$fila[0]
					and numorden=$fila[3]";
					$res2=ExQuery($cons2);
					$cons2="update salud.horacantidadxmedicamento set idescritura=$IdEscritura,numorden=$cont where compania='$Compania[0]' and paciente='$Paciente[1]' 
					and idescritura=$fila[0] and numorden=$fila[3]";
					//echo $cons2."<br>";
					$res2=ExQuery($cons2);	
					
					// 12 de marzo de 2014
                    // Se hace update a la fechaformula para que no haya conflicto cuando se reescriba una orden
					$cons2="update salud.plantillamedicamentos set idescritura=$IdEscritura,numorden=$cont,fechaformula='$ND[year]-$ND[mon]-$ND[mday] $ND[hours]:$ND[minutes]:$ND[seconds]' where compania='$Compania[0]' and cedpaciente='$Paciente[1]' 
					and numservicio=$fila[1] and idescritura=$fila[0] and numorden=$fila[3]";
					//echo $cons2."<br>";
					$res2=ExQuery($cons2);
					
					$cons2="update salud.registromedicamentos set numorden=$cont,idescritura=$IdEscritura where compania='$Compania[0]' and cedula='$Paciente[1]' 
					and numservicio=$fila[1] and idescritura=$fila[0] and numorden=$fila[3]";
					$res2=ExQuery($cons2);	
					
					$cons2="update salud.calendarioxmedicamento set numorden=$cont,idescritura=$IdEscritura where compania='$Compania[0]' and cedpaciente='$Paciente[1]' and 
					idescritura=$fila[0] and numorden=$fila[3]";
					$res2=ExQuery($cons2);				
					
					$cons2="insert into salud.ordenesmedicas(compania,fecha,cedula,numservicio,detalle,idescritura,numorden,usuario,tipoorden,estado,acarreo,posologia)
					values ('$Compania[0]','$ND[year]-$ND[mon]-$ND[mday] $ND[hours]:$ND[minutes]:$ND[seconds]','$Paciente[1]',$fila[1],'$fila[2]',$IdEscritura,$cont,'$usuario[1]','$fila[9]','AC',1,'$fila[8]')";
					$res2=ExQuery($cons2);
					//echo $cons2."<br>";
					
					// 12 de marzo de 2014
                    // Se hace insert con la diferencia que se deja en AN(inactivo) el estado cuando se reescribe una orden
                    $cons2="insert into salud.plantillamedicamentos(compania,almacenppal,autoidprod,usuario,fechaformula,cedpaciente,fechaini,cantdiaria,viasuministro,lunes,martes,miercoles,jueves,viernes,sabado,domingo,justificacion,notas,estado,numservicio,detalle,tipomedicamento,posologia,numorden,idescritura)
					values ('".$filap['compania']."','".$filap['almacenppal']."','".$filap['autoidprod']."','".$filap['usuario']."','".$filap['fechaformula']."','".$filap['cedpaciente']."','".$filap['fechaini']."','".$filap['cantdiaria']."','".$filap['viasuministro']."','".$filap['lunes']."','".$filap['martes']."','".$filap['miercoles']."','".$filap['jueves']."','".$filap['viernes']."','".$filap['sabado']."','".$filap['domingo']."','".$filap['justificacion']."','".$filap['notas']."','AN','".$filap['numservicio']."','".$filap['detalle']."','".$filap['tipomedicamento']."','".$filap['posologia']."','".$filap['numorden']."','".$filap['idescritura']."')";
					$res2=ExQuery($cons2);
					
					$cont++;
				}
			}			
			if(strtoupper($fila[9])=='MEDICAMENTO NO PROGRAMADO'){//echo "MedUrgente<br>";
				if($fila[10]==1)
				{
					if($fila[0]!=$IdEscritura){
						$cons2="update salud.plantillamedicamentos set idescritura=$IdEscritura,numorden=$cont where compania='$Compania[0]' and cedpaciente='$Paciente[1]' 
						and numservicio=$fila[1] and idescritura=$fila[0] and numorden=$fila[3]";
						//echo $cons2."<br>";
						$res2=ExQuery($cons2);	
						$cons2="update salud.horacantidadxmedicamento set idescritura=$IdEscritura,numorden=$cont where compania='$Compania[0]' and paciente='$Paciente[1]' 
						and idescritura=$fila[0] and numorden=$fila[3]";
						//echo $cons2."<br>";
						$res2=ExQuery($cons2);
						$cons2="update salud.ordenesmedicas set estado='AN',acarreo=0 where cedula='$Paciente[1]' and compania='$Compania[0]' and estado='AC' and idescritura=$fila[0]
						and numorden=$fila[3]";
						$res2=ExQuery($cons2);
						//echo $cons2."<br>";
						$cons2="insert into salud.ordenesmedicas(compania,fecha,cedula,numservicio,detalle,idescritura,numorden,usuario,tipoorden,estado,acarreo,posologia,dosisunica)
						values ('$Compania[0]','$ND[year]-$ND[mon]-$ND[mday] $ND[hours]:$ND[minutes]:$ND[seconds]','$Paciente[1]',$fila[1],'$fila[2]',$IdEscritura,$cont,'$usuario[1]','$fila[9]','AC',1,'$fila[8]',0)";
						$res2=ExQuery($cons2);
						//echo $cons2."<br>";
						$cont++;
					}
				}
				else{					
					$cons2="update salud.ordenesmedicas set estado='AN',acarreo=0 where cedula='$Paciente[1]' and compania='$Compania[0]' and estado='AC' and idescritura=$fila[0]
					and numorden=$fila[3]";
					$res2=ExQuery($cons2);
					//echo $cons2."<br>";
				}
			}
			if(strtoupper($fila[9])!='MEDICAMENTO NO PROGRAMADO'&&$fila[9]!='Medicamento Programado'){// echo "ULTIMA OPC <BR>";
				
				if($fila[0]!=$IdEscritura){
					$cons2="update salud.ordenesmedicas set estado='AN',acarreo=0 where cedula='$Paciente[1]' and compania='$Compania[0]' and estado='AC' and idescritura=$fila[0]
					and numorden=$fila[3]";
					$res2=ExQuery($cons2);
					//echo $cons2."<br>";
					$cons2="insert into salud.ordenesmedicas(compania,fecha,cedula,numservicio,detalle,idescritura,numorden,usuario,tipoorden,estado,acarreo,posologia)
					values ('$Compania[0]','$ND[year]-$ND[mon]-$ND[mday] $ND[hours]:$ND[minutes]:$ND[seconds]','$Paciente[1]',$fila[1],'$fila[2]',$IdEscritura,$cont,'$usuario[1]','$fila[9]','AC',1,'$fila[8]')";
					$res2=ExQuery($cons2);
					//echo $cons2."<br>";
					$cont++;
				}
			}
		}
			
	?>	<script language="javascript">
			//location.href='OrdenamientoMedico.php?DatNameSID=<? echo $DatNameSID?>';
		</script><?
	}?>
	<html>
		<head>
			<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		</head>

		<body background="/Imgs/Fondo.jpg">
			<form name="FORMA" method="post">
				<table bordercolor="#e5e5e5" border="1" style='font : normal normal small-caps 12px Tahoma;' align="center" cellpadding="4">
					<tr><td colspan="3" align="center"  bgcolor="#e5e5e5" style="font-weight:bold">Ordenes Medicas</td></tr><?	
						$ban=0;
						$cont=1;



					$cons="select tiposervicio,numservicio from salud.servicios where cedula='$Paciente[1]' and compania='$Compania[0]' and estado='AC'"; 
					$res=ExQuery($cons);
					$fila=ExFetch($res);
					

					if($fila[0]==''){
						$fila[0]="Sin Ambito";
					}
					
					// Define si hay un servicio activo
					$numservicio = definirServicio($Compania[0], $Paciente[1]);
						if (isset($numservicio)){								
								$pabellon = definirPabellon($Paciente[1], $numservicio);
						}
					
					
					
							
							// Consulta en la tabla ConfOrdenesMed si esta habilitado realizar Notas
							$cons2="select notas from salud.confordenesmed where compania='$Compania[0]' and ambito='$fila[0]'";
							$res2=ExQuery($cons2);
							$fila2=ExFetch($res2);
								if($fila2[0]==1)
								{
									$cons3="select modulo from salud.ususxordmeds where usuario='$usuario[1]' and modulo='Notas'";	
									$res3=ExQuery($cons3);
									$fila3=ExFetch($res3);
									if($fila3[0]){
										$ban=1;
										if($cont==1){
											echo "<tr>";
										}?>
											<td><input type="radio" name="Opc" onClick="location.href='OMNotas.php?DatNameSID=<? echo $DatNameSID?>&IdEscritura=<? echo $IdEscritura?>'"/>
												NOTAS
											</td>
									<?	if($cont==3){
											echo "</tr>";$cont=0;
										}
										$cont++;
										
									}
								}
							
							
								// Consulta en la tabla ConfOrdenesMed si esta habilitado para formular Medicamentos No Programados
								$cons2="select mednoprog from salud.confordenesmed where compania='$Compania[0]' and ambito='$fila[0]'";
								
								$res2=ExQuery($cons2);
								$fila2=ExFetch($res2);
									if(($fila2[0]==1) )	{
										$cons3="select modulo from salud.ususxordmeds where usuario='$usuario[1]' and UPPER(modulo)='MEDICAMENTOS NO PROGRAMADOS' ";
										$res3=ExQuery($cons3);
										$fila3=ExFetch($res3);
											if($fila3[0] ) {
												$ban=1;
												if($cont==1){
													echo "<tr>";
												}?>
												
													<td><input type="radio" name="Opc" onClick="location.href='OMMedUrgentes.php?DatNameSID=<? echo $DatNameSID?>&IdEscritura=<? echo $IdEscritura?>'"/>
														MEDICAMENTOS NO PROGRAMADOS
													</td> <?
													if($cont==3){
														echo "</tr>";$cont=0;
													}												
													$cont++;
											}
									}	
							
							// Consulta en la tabla ConfOrdenesMed si esta habilitado realizar Ingreso de Pacientes
							$cons2="select hospitalizar from salud.confordenesmed where compania='$Compania[0]' and ambito='$fila[0]'";
							$res2=ExQuery($cons2);
							$fila2=ExFetch($res2);
							if($fila2[0]==1)
							{
								$cons3="select modulo from salud.ususxordmeds where usuario='$usuario[1]' and modulo='Ingresar paciente'";
								$res3=ExQuery($cons3);
								$fila3=ExFetch($res3);
								if($fila3[0]){
									$ban=1;
									if($cont==1){echo "<tr>";}?>
									
										<td>
											<input type="radio" name="Opc" onClick="location.href='OMHospPacientes.php?DatNameSID=<? echo $DatNameSID?>&IdEscritura=<? echo $IdEscritura?>'"/> 
											INGRESAR PACIENTE
										</td>
									<?		if($cont==3){
												echo "</tr>";$cont=0;
											}
											$cont++;
								}
							}
							
							
							// Consulta en la tabla ConfOrdenesMed si esta habilitado para Formular Medicamentos Programados
							
							if (isset($pabellon)){
							
								$cons2="select medprog from salud.confordenesmed where compania='$Compania[0]' and ambito='$fila[0]'";
								
								$res2=ExQuery($cons2);
								$fila2=ExFetch($res2);
									if(($fila2[0]==1) )	{
										$cons3="select modulo from salud.ususxordmeds where usuario='$usuario[1]' and modulo='Medicamentos Programados'";
										$res3=ExQuery($cons3);
										$fila3=ExFetch($res3);
											if($fila3[0] ) {
												$ban=1;
												if($cont==1){
													echo "<tr>";
												}
												?>
												
													<td><input type="radio" name="Opc" onClick="location.href='OMMedProgramados.php?DatNameSID=<? echo $DatNameSID?>&IdEscritura=<? echo $IdEscritura?>'"/>
														MEDICAMENTOS PROGRAMADOS
													</td>
												<? 
													if($cont==3){
														echo "</tr>";$cont=0;
													}
													$cont++;
											}
									}	
							
							}
							

							
							
							
							// Consulta en la tabla ConfOrdenesMed si esta habilitado para Trasladar de Servicio

							if (isset($pabellon)){
								$cons2="select trasladound from salud.confordenesmed where compania='$Compania[0]' and ambito='$fila[0]'"; 
								$res2=ExQuery($cons2);
								$fila2=ExFetch($res2);
								
									if(($fila2[0]==1))
									{
										$cons3="select modulo from salud.ususxordmeds where usuario='$usuario[1]' and modulo='Traslado de Unidad'";	
										$res3=ExQuery($cons3);
										$fila3=ExFetch($res3);
											if($fila3[0]){
												$ban=1;
													if($cont==1){
														echo "<tr>";
													}
												?>
												<td><input type="radio" name="Opc" onClick="location.href='OMTrasladodeUnd.php?DatNameSID=<? echo $DatNameSID?>&IdEscritura=<? echo $IdEscritura?>'"/> 
													TRASLADAR DE SERVICIO
												</td>
													<?		
													if($cont==3){
														echo "</tr>";$cont=0;
													}
												$cont++;
											}
									}
							}		
								
								// Consulta en la tabla ConfOrdenesMed si esta habilitado para registrar Procedminetos y ayudas diagnosticas	
							
							//if(isset($pabellon)){
								$cons2="select procedimientos from salud.confordenesmed where compania='$Compania[0]' and ambito='$fila[0]'";
								$res2=ExQuery($cons2);
								$fila2=ExFetch($res2);
								
									if($fila2[0]==1) {
										$cons3="select modulo from salud.ususxordmeds where usuario='$usuario[1]' and modulo='Procedimientos'";	
										$res3=ExQuery($cons3);
										$fila3=ExFetch($res3);
										if(($fila3[0])){
											$ban=1;
												if($cont==1){
													echo "<tr>";
												}?>
											<td><input type="radio" name="Opc" onClick="location.href='OMProcedimientos.php?DatNameSID=<? echo $DatNameSID?>&IdEscritura=<? echo $IdEscritura?>&Suministro=Externo'"/> 
												PROCEDIMIENTOS Y AYUDAS DIAGNOSTICAS
											</td>
											<?	
												if($cont==3){
													echo "</tr>";$cont=0;
												}
											$cont++;
										}
									}
							//}		
								
								// Consulta en la tabla ConfOrdenesMed si esta habilitado para solicitar Interconsultas 
							
							if(isset($pabellon)){ 							
								$cons2="select interprog from salud.confordenesmed where compania='$Compania[0]' and ambito='$fila[0]'";
								$res2=ExQuery($cons2);
								$fila2=ExFetch($res2);
								
								if(($fila2[0]==1)){
									$cons3="select modulo from salud.ususxordmeds where usuario='$usuario[1]' and modulo='Interprogramas'";	
									$res3=ExQuery($cons3);
									$fila3=ExFetch($res3);
									if($fila3[0]){
										$ban=1;
											if($cont==1){
												echo "<tr>";
											}?>
											<td><input type="radio" name="Opc" onClick="location.href='OMInterProgramas.php?DatNameSID=<? echo $DatNameSID?>&IdEscritura=<? echo $IdEscritura?>'"/>
												INTERCONSULTAS
											</td> <?
											if($cont==3){
												echo "</tr>";$cont=0;
											}
										$cont++;
									}
								}
							}
							
							if (isset($pabellon)){
								$cons2="select comedores from salud.confordenesmed where compania='$Compania[0]' and ambito='$fila[0]'";
								$res2=ExQuery($cons2);
								$fila2=ExFetch($res2);
								if(($fila2[0]==1))
								{
									$cons3="select modulo from salud.ususxordmeds where usuario='$usuario[1]' and modulo='Comedores'";	
									$res3=ExQuery($cons3);
									$fila3=ExFetch($res3);
									if($fila3[0]){
										$ban=1;
										if($cont==1){echo "<tr>";}?>
											<td><input type="radio" name="Opc" onClick="location.href='OMComedores.php?DatNameSID=<? echo $DatNameSID?>&IdEscritura=<? echo $IdEscritura?>'"/> 
												COMEDOR
											</td>
									<?	if($cont==3){echo "</tr>";$cont=0;}
										$cont++;
									}
								}
							}	
								
								
								// Consulta en la tabla ConfOrdenesMed si esta habilitado para solicitar Interconsultas 
							
							if (isset($pabellon)){ 							
								$cons2="select dietas from salud.confordenesmed where compania='$Compania[0]' and ambito='$fila[0]'";
								$res2=ExQuery($cons2);
								$fila2=ExFetch($res2);
								
								if(($fila2[0]==1)){
									
									$cons3="select modulo from salud.ususxordmeds where usuario='$usuario[1]' and modulo='Dietas'";	
									$res3=ExQuery($cons3);
									$fila3=ExFetch($res3);
									if($fila3[0]){
										$ban=1;
											if($cont==1){
												echo "<tr>";
											}?>
											<td><input type="radio" name="Opc" onClick="location.href='OMDietas.php?DatNameSID=<? echo $DatNameSID?>&IdEscritura=<? echo $IdEscritura?>'"/>
												DIETAS
											</td> <?
											if($cont==3){
												echo "</tr>";$cont=0;
											}
										$cont++;
									}
								}
							}



$cons_A="Select numservicio,tiposervicio from salud.servicios where compania='$Compania[0]' and cedula='$Paciente[1]' and estado='AC'"; 
			$res_A=ExQuery($cons_A); 
			$filaA=ExFetch($res_A);
	
$cons_="select salud.formatosegreso.tipoformato,salud.formatosegreso.formato,tblformat from salud.formatosegreso inner join historiaclinica.formatos ON 
historiaclinica.formatos.tipoformato=salud.formatosegreso.tipoformato and historiaclinica.formatos.formato=salud.formatosegreso.formato
where salud.formatosegreso.compania='$Compania[0]' and ambito='$filaA[1]'
group by salud.formatosegreso.tipoformato,salud.formatosegreso.formato,tblformat";
$res_=ExQuery($cons_); 
while($fila_=ExFetch($res_))
	{
							$cons3E="select cedula,fecha,numservicio,formato,tipoformato,id_historia from histoclinicafrms.".$fila_[2]." 
							where compania='$Compania[0]' and tipoformato='$fila_[0]' and formato='$fila_[1]' and cedula='$Paciente[1]' and numservicio='$filaA[0]'
							and fecha='$ND[year]-$ND[mon]-$ND[mday]'";	
							$res3E=ExQuery($cons3E);
                            $fila3E=ExFetch($res3E);
							if($fila3E[3]!=$fila_[1]){$BanEpic="1"; $format[]=$fila_[1];} 
	}

	$pabellon = definirPabellon($Paciente[1], $filaA[0]);
	
	if (isset($pabellon)){
		$cons2="select egreso from salud.confordenesmed where compania='$Compania[0]' and ambito='$fila[0]'";
		$res2=ExQuery($cons2);
		$fila2=ExFetch($res2);
		if($fila2[0]==1)
		{
			$cons3="select modulo from salud.ususxordmeds where usuario='$usuario[1]' and modulo='Egreso'";	
			$res3=ExQuery($cons3);
			$fila3=ExFetch($res3);
			if($fila3[0]){
				$ban=1;
				if($cont==1){echo "<tr>";}?>
					<td><input type="radio" name="Opc" onClick="<?if($BanEpic=="1"){?>alert('No se ha(n) diligenciado el(los) siguiente(s) formato(s) necesario(s) para realizar el egreso:\n<?for($i=0;$i<count($format);$i++){?>\n<?echo $format[$i];}?>')<?}else{?>location.href='OMEgreso.php?DatNameSID=<? echo $DatNameSID?>&IdEscritura=<? echo $IdEscritura?>'<?}?>"/>
						EGRESO
					</td>
			<?	if($cont==3){echo "</tr>";$cont=0;}
				$cont++;
			}
		}
	}


if($ban==0){?>
	<tr><td align="center" bgcolor="#e5e5e5" style="font-weight:bold" colspan="3">No hay ordenes autorizadas para este proceso</td></tr><?
}?>
</table>
<br>
<table bordercolor="#e5e5e5" border="1" style='font : normal normal small-caps 12px Tahoma;' align="center" cellpadding="4">
	<tr><td align="center" bgcolor="#e5e5e5" style="font-weight:bold" colspan="4">Ordenes Activas</td></tr>
<?php	if($fila[1]){
		$ban2=0;
		$cons77="select detalle,tipoorden,idescritura,numorden,posologia,viasumin,fechareprog,consistenciaDieta,observacionDieta,numservicio,fecha from salud.ordenesmedicas where cedula='$Paciente[1]' and estado='AN' and fechareprog>='$ND[year]-$ND[mon]-$ND[mday] 0:00:00' and fechareprog<='$ND[year]-$ND[mon]-$ND[mday] 23:59:59'
		order by idescritura desc,numorden desc";
				$res77=ExQuery($cons77);
				if(ExNumRows($res77)>0){ 
					$fila77=ExFetch($res77);
					$detalleorden=$fila77[0];
					$fechacambio=$fila77[6];
				}
		//$cons3="select detalle,tipoorden,idescritura,numorden,posologia from salud.ordenesmedicas where cedula='$Paciente[1]' and estado='AC' and numservicio=$fila[1] order by fecha";
		$cons3="select detalle,tipoorden,idescritura,numorden,posologia,viasumin,fechareprog,consistenciaDieta,observacionDieta,numservicio,fecha,tipodosis from salud.ordenesmedicas where cedula='$Paciente[1]' and estado='AC' and numservicio=$numservicio 
		order by idescritura desc,numorden desc";
		//Gracias Mauro!!!!, te queremos!!
		//echo $cons3;
		$res3=ExQuery($cons3);
		if(ExNumRows($res3)>0){   	?>
			<tr align="center" bgcolor="#e5e5e5" style="font-weight:bold"><td>Tipo De Orden</td><td>Detalle</td><td colspan="2"></td></tr><?php
			while($fila3=ExFetch($res3)){
				$consNota="select notas,justificacion from salud.plantillamedicamentos where numservicio='$fila3[9]' and idescritura='$fila3[2]' and numorden='$fila3[3]'";
				$resNota=ExQuery($consNota);
				$filaNota=ExFetch($resNota);
                                
				$consNota2="select detalle from salud.plantillamedicamentos where numservicio='$fila3[9]' and idescritura='$fila3[2]' and fechaformula='$fila3[10]' and detalle like '%AMPOLLA%'";
				$resNota2=ExQuery($consNota2);
				$filaNota2=ExFetch($resNota2);
                                
				echo "<tr><td>$fila3[1]</td><td>"; 
				if($fila3[6]){
					echo "(Reprogramado) ";
				}
				if($filaNota2[0]){
                                        echo "$fila3[0] $filaNota[0] $fila3[8] $fila3[4]  "; 
                                        if($fila3[4]){ 
                                            echo " Via $fila3[5] ";
                                        } 										
                                }else{
                                        echo "$fila3[0] $fila3[7] $fila3[8] $fila3[4] "; 
                                        if($fila3[4]){ 
                                            echo " Via $fila3[5] ";
                                        }

                                        // Para imprimir las notas y justificacion
                                        if($fila3[1]=='Medicamento Programado' || $fila3[1]=='Medicamento Urgente'){
                                            if($filaNota[0]){
                                                echo " <i>Nota:</i> ".$filaNota[0]." ";
                                            }
                                            if($filaNota[1]){
                                                echo " <i>Justificación:</i> ".$filaNota[1]." ";
                                            }
                                        }

                                        if($fila3[1]=='Nota'){
                                            $consJu="select justificacion from salud.plantillanotas where numservicio='$fila3[9]' and idescritura='$fila3[2]' and fechaini='$fila3[10]' and numorden='$fila3[3]'";
                                            $resJu=ExQuery($consJu);
                                            $filaJu=ExFetch($resJu);
                                            
											if($filaJu[0])
												echo " <i>Justificación:</i> ".$filaJu[0]." ";
                                        }
                                        
                                        if($fila3[1]=='Procedimiento'){
                                            $consProc="select observaciones,justificacion from salud.plantillaprocedimientos where numservicio='$fila3[9]' and idescritura='$fila3[2]' and numorden='$fila3[3]'";
                                            $resProc=ExQuery($consProc);
                                            $filaProc=ExFetch($resProc);
                                            
                                            if($filaProc[0]){
                                                echo " <i>Nota:</i> ".$filaProc[0]." ";
                                            }
                                            if($filaProc[1]){
                                                echo " <i>Justificación:</i> ".$filaProc[1]." ";
                                            }
                                        }
                                }
				// 12 de marzo de 2014
				// Se comenta por limpieza de código para impresión de ordenes
				/*if($filaNota2[0]){
					if($justifnota!='' || $fila3[1]=='Procedimiento')
                                            echo "$fila3[0]. $fila3[7] $fila3[8] - $filaNota[0] $filaNota[1] $justifnota - $fila3[11]";
                                        else
                                            echo "$fila3[0]. $fila3[7] $fila3[8] - <i>Nota: </i> $filaNota[0] <i>Justificación: </i> $filaNota[1] $justifnota - $fila3[11]";
				} ELSE{
					if($justifnota!='' || $fila3[1]=='Procedimiento')
                                            echo "$fila3[0]. $fila3[7] $fila3[8] $fila3[4] - $filaNota[0] $filaNota[1] $justifnota - $fila3[11]"; 
                                        else
                                            echo "$fila3[0]. $fila3[7] $fila3[8] $fila3[4] - <i>Nota: </i> $filaNota[0] <i>Justificación</i> $filaNota[1] $justifnota - $fila3[11]"; 
				}
				if($fila3[5]){
					echo "Via $fila3[5] ";
				}*/
				echo "</td><td>";

				if($fila3[1]!='Ingreso'&&$fila3[1]!='Traslado de Unidad'&&$fila3[1]!='Egreso'){?>
	    			<img title="Suspender" style="cursor:hand;" onClick="if(confirm('Desea suspender esta orden medica?')){location.href='NuevaOrdenMedica.php?DatNameSID=<? echo $DatNameSID?>&Suspender=1&Numserv=<? echo $fila[1]?>&IdEsc=<? echo $fila3[2]?>&NumOrd=<? echo $fila3[3]?>&Tipo=<? echo $fila3[1]?>&IdEscritura=<? echo $IdEscritura?>';}" src="/Imgs/b_drop.png">
           <?	}
		   		else{?>
					<img title="Suspender" style="cursor:hand;" src="/Imgs/b_drop.png" onClick="alert('Esta orden medica no puede ser suspendida!!!')">
			<?	}
				echo "</td><td>&nbsp;";
				
				if($fila3[1]=="Procedimiento"||$fila3[1]=="Medicamento Programado"){
					if($fila3[0]!=$detalleorden){
					?>                		
						<img title="Reprogramar" style="cursor:hand" src="/Imgs/b_edit.png" 
						<? 	if($fila3[1]=="Medicamento Programado"){?>
				onClick="location.href='/HistoriaClinica/Formatos_Fijos/OMMedProgramados.php?DatNameSID=<? echo $DatNameSID?>&IdEscritura=<? echo $IdEscritura?>&Numorden=<? echo $fila3[3]?>&IdEsc=<? echo $fila3[2]?>&Edit=1'"<? 		
						}
							if($fila3[1]=="Procedimiento"){?> onClick="alert('Disponible En Breve');"<? }?>>
		<?			}
				}
				else{?>
					<img title="No es posible reprogramar este tipo de orden" src="/Imgs/b_edit_gray.png">	
			<?	}				
			}?>
            	</td>
            </tr>
        </table>
		
		
		
        	<br>
        	<table align="center"> 
            	<tr align="center">
                    <td>
                        <input type="button" value="Ver Formula Medicamentos" onClick="open('VerOrdenMed.php?DatNameSID=<? echo $DatNameSID?>&NumServ=<? echo $fila[1]?>&IdEscritura=<? echo $IdEscritura?>','','left=10,top=10,width=790,height=600,menubar=yes,scrollbars=YES')">
                        <input type="button" value="Ver Formula Procedimientos" onClick="open('VerOrdenCUPs.php?DatNameSID=<? echo $DatNameSID?>&NumServ=<? echo $fila[1]?>&IdEscritura=<? echo $IdEscritura?>','','left=10,top=10,width=790,height=600,menubar=yes,scrollbars=YES')">
                        <input type="button" value="Ver Formula Completa" onClick="open('VerOrdenMedAmbos.php?DatNameSID=<? echo $DatNameSID?>&NumServ=<? echo $fila[1]?>&IdEscritura=<? echo $IdEscritura?>','','left=10,top=10,width=790,height=600,menubar=yes,scrollbars=YES')" >
                        
                    <td>
                </tr>
            </table>
<?		}
		else{?>
		<tr><td align="center" colspan="4">El Paciente No tiene Ordenes Activas</td></tr>
<?		}
	}
	else
	{?>
		<tr><td align="center" colspan="4">El Paciente No tiene ningun Servicio Activo</td></tr>	
<?	}?> 
</table>
<br>
<table align="center">     	
<tr><td align="center" colspan="4"><input type="submit" value="Reescribir Ordenes Activas" name="ReescribirAC"><input type="submit" value="Cerrar Orden" name="CerrarOrden"/></td></tr>
</table>
<input type="hidden" name="IdEscritura" value="<? echo $IdEscritura?>">
<input type="hidden" name="DatNameSID" value="<? echo $DatNameSID?>">

<table bordercolor="#e5e5e5" border="1" style='font : normal normal small-caps 12px Tahoma;' align="center" cellpadding="4">
	<tr><td align="center" bgcolor="#e5e5e5" style="font-weight:bold" colspan="4">Ordenes reprogramadas para el Día de mañana</td></tr>
	<?	if($fila[1]){
		$ban2=0;
		//$cons3="select detalle,tipoorden,idescritura,numorden,posologia from salud.ordenesmedicas where cedula='$Paciente[1]' and estado='AC' and numservicio=$fila[1] order by fecha";
		$cons3="select detalle,tipoorden,idescritura,numorden,posologia,viasumin,fechareprog,consistenciaDieta,observacionDieta,numservicio,fecha from salud.ordenesmedicas where cedula='$Paciente[1]' and estado='AN' and fechareprog>='$ND[year]-$ND[mon]-$ND[mday] 0:00:00' and fechareprog<='$ND[year]-$ND[mon]-$ND[mday] 23:59:59'
		order by idescritura desc,numorden desc";
		
		//echo $cons3;
		$res3=ExQuery($cons3);
		if(ExNumRows($res3)>0){   	?>
			<tr align="center" bgcolor="#e5e5e5" style="font-weight:bold">
				<td>Tipo De Orden</td>
				<td>Detalle</td>
				<td colspan="2"></td>
			</tr><?
			while($fila3=ExFetch($res3)){
				$consNota="select notas from salud.plantillamedicamentos where numservicio='$fila3[9]' and idescritura='$fila3[2]' and fechaformula='$fila3[10]'";
				$resNota=ExQuery($consNota);
				$filaNota=ExFetch($resNota);
				$consNota2="select detalle from salud.plantillamedicamentos where numservicio='$fila3[9]' and idescritura='$fila3[2]' and fechaformula='$fila3[10]' and detalle ilike '%AMPOLLA%'";
				$resNota2=ExQuery($consNota2);
				$filaNota2=ExFetch($resNota2);
				echo "<tr><td>$fila3[1]</td><td>"; 
				if($fila3[6]){echo "(Reprogramado) ";}
				if($filaNota2[0]){
					echo "$fila3[0]. $fila3[7] $fila3[8] - $filaNota[0] - $fila3[11]";
				} ELSE{
					echo "$fila3[0]. $fila3[7] $fila3[8] $fila3[4] - $filaNota[0] - $fila3[11]"; 
				}
				if($fila3[5]){
					echo "Via $fila3[5] ";
				}
				echo "</td><td>";
				if($fila3[1]!='Ingreso'&&$fila3[1]!='Traslado de Unidad'&&$fila3[1]!='Egreso'){?>
	    			<img title="Suspender" style="cursor:hand;" onClick="if(confirm('Desea suspender esta orden medica?')){location.href='NuevaOrdenMedica.php?DatNameSID=<? echo $DatNameSID?>&Suspender=1&Numserv=<? echo $fila[1]?>&IdEsc=<? echo $fila3[2]?>&NumOrd=<? echo $fila3[3]?>&Tipo=<? echo $fila3[1]?>&IdEscritura=<? echo $IdEscritura?>';}" src="/Imgs/b_drop.png">
				<?	}
		   		else{?>
					<img title="Suspender" style="cursor:hand;" src="/Imgs/b_drop.png" onClick="alert('Esta orden medica no puede ser suspendida!!!')">
				<?	}
				echo "</td><td>&nbsp;";
				
				
			}?>
            	</td>
            </tr>
        </table>
		
		
		
        	<br>
        	<table align="center"> 
            	<tr align="center">
                    <td>
                        <input type="button" value="Ver Formula Medicamentos" onClick="open('VerOrdenMed.php?DatNameSID=<? echo $DatNameSID?>&NumServ=<? echo $fila[1]?>&IdEscritura=<? echo $IdEscritura?>','','left=10,top=10,width=790,height=600,menubar=yes,scrollbars=YES')">
                        <input type="button" value="Ver Formula Procedimientos" onClick="open('VerOrdenCUPs.php?DatNameSID=<? echo $DatNameSID?>&NumServ=<? echo $fila[1]?>&IdEscritura=<? echo $IdEscritura?>','','left=10,top=10,width=790,height=600,menubar=yes,scrollbars=YES')">
                        <input type="button" value="Ver Formula Completa" onClick="open('VerOrdenMedAmbos.php?DatNameSID=<? echo $DatNameSID?>&NumServ=<? echo $fila[1]?>&IdEscritura=<? echo $IdEscritura?>','','left=10,top=10,width=790,height=600,menubar=yes,scrollbars=YES')" >
                        
                    <td>
                </tr>
            </table>
<?		}
		else{?>
		<tr><td align="center" colspan="4">El Paciente No tiene Ordenes Activas</td></tr>
<?		}
	}
	else
	{?>
		<tr><td align="center" colspan="4">El Paciente No tiene ningun Servicio Activo</td></tr>	
<?	}?> 
	</table>
</form>
</body>
</html>