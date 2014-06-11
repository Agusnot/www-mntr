<?
	if($DatNameSID){session_name("$DatNameSID");}
	session_start();
	include("Funciones.php");
   
	$cons="select formato,tipoformato from historiaclinica.formatos where compania='$Compania[0]' and nopos='Medicamentos No POS'";
	$res=ExQuery($cons);
	if(ExNumRows($res)>0){
		$MedNoPos=1;
		$fila=ExFetch($res);
		$Formato=$fila[0]; $TipoFormato=$fila[1];
	}
	else{
		$MedNoPos=0;
	}
	
	if(!$NoServicio)
	{
		$cons="Select NumServicio From Salud.Servicios Where Cedula='$Paciente[1]' and Compania='$Compania[0]' and estado='AC'";
		$res = ExQuery($cons);
		$fila = ExFetch($res);
		$NoServicio=$fila[0];
	}
	$cons="select IdEscritura from salud.OrdenesMedicas where cedula='$Paciente[1]' and compania='$Compania[0]' and  idescritura=$IdEscritura and usuario!='$usuario[1]'";
	$res=ExQuery($cons);
	if(ExNumRows($res)>0){
		
	}
	if(!$NoOrden)
	{
		$cons="Select NumOrden from Salud.OrdenesMedicas where Cedula='$Paciente[1]' and Compania='$Compania[0]' 
		and IdEscritura='$IdEscritura' order by NumOrden desc";
		$res = ExQuery($cons);
 		if(ExNumRows($res))
		{
			$fila = ExFetch($res);		
			$NoOrden = $fila[0]+1;
			$NoOrden2 = $fila[0]+2;
		}
		else
		{
			$NoOrden=1;
		}
	}
	$ND = getdate();
	$MostrarCancelar = 1;
	if(!$FechaActual)
	{
		if($ND[mon]<10){$mes = "0".$ND[mon];}else{$mes=$ND[mon];}
		if($ND[mday]<10){$dia = "0".$ND[mday];}else{$dia=$ND[mday];}
		$FechaActual=$ND[year]."-".$mes."-".$dia;
	}
	echo $Eliminar;
	if($Cancelar)
	{
		$cons = "Delete from Salud.TMPFechasOMMedPro where Compania='$Compania[0]' and Usuario='$usuario[0]' and CedPaciente='$Paciente[1]'
				and TMPCOD='$TMPCOD'";
		$res = ExQuery($cons);
		?><script language="javascript">location.href="NuevaOrdenMedica.php?DatNameSID=<? echo $DatNameSID?>&IdEscritura=<? echo $IdEscritura?>";</script><?
	}
	?>
	 <iframe scrolling="no" id="FrameFondo" name="FrameFondo" frameborder="0" height="0" width="0" style="filter:Alpha(Opacity=200, FinishOpacity=40, Style=2, StartX=20, StartY=40, FinishX=0, FinishY=0);display:none;border:thin; background-color:transparent" ></iframe>
	<iframe id="FrameOpenerNP" name="FrameOpenerNP" style="display:none" frameborder="0" height="1" style="border:#e5e5e5 ridge" scrolling="yes"></iframe>
	<?
	if($Guardar)
	{
		if($Edit==1){
			?>
			<script language="javascript">alert("Esta reprogramacion sera efectiva desde manana si desea que inicie el dia de hoy, favor hacerlo usando la opcion de medicamento no programado (adicional)");</script>
			<?php
		}
		/////////////////////////////////VERIFICAR PRODUCTOS EXISTENTES EN LA PLANTILLA//////////////////////////////////////////////////
		$cons = "Select CedPaciente,AutoIdProd from Salud.PlantillaMedicamentos Where Compania='$Compania[0]' and AlmacenPpal='$AlmacenPpal'
		and CedPaciente = '$Paciente[1]' and AutoIdProd = '$AutoIdProd' and Estado='AC' and TipoMedicamento='Medicamento Programado'
                and NumServicio = '$NoServicio'";
		$res = ExQuery($cons);
		if(ExNumRows($res)>0){$ExisteAutoId=1;}
		if($Edit){$ExisteAutoId=NULL;}
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		if(!$ExisteAutoId)
		{
			if($Dia[1]){ $Lunes = 1; $Dias = "Lunes,";} else{ $Lunes = 0;}
			if($Dia[2]){ $Martes = 1; $Dias = $Dias." Martes,";} else {$Martes=0;}
			if($Dia[3]){ $Miercoles = 1; $Dias = $Dias." Miercoles,";}else {$Miercoles=0;}
			if($Dia[4]){ $Jueves = 1; $Dias = $Dias." Jueves,";}else {$Jueves=0;}
			if($Dia[5]){ $Viernes = 1; $Dias = $Dias." Viernes,";}else {$Viernes=0;}
			if($Dia[6]){ $Sabado = 1; $Dias = $Dias." Sabado,";}else {$Sabado=0;}
			if($Dia[7]){ $Domingo = 1; $Dias = $Dias." Domingo";}else {$Domingo=0;}
			$Tot = $Lunes + $Martes + $Miercoles + $Jueves + $Viernes + $Sabado + $Domingo;
			if($Tot == 7){unset($Dias);}
		//	if(!$Editar)
		//	{
				if($Suministro != "Calendario")
				{
					if($Suministro == "Definido")
					{
						if($FechaIni && $FechaFin)
						{
							if($FechaIni > $FechaFin)
							{
								$band = 1;
								?><script language="javascript">alert("La fecha Inicial no puede ser mayor que la fecha Final")</script><?
							}
						}
						else
						{
							$band = 1;
							?><script language="javascript">alert("Debe llenar los campos Fecha Inicial y Fecha Final")</script><?
						}
						$DeManera = "Desde $FechaIni Hasta $FechaFin";
						$FechaFin = "'$FechaFin'";
					}
					else
					{
						$FechaFin = "NULL";
					}
					if(!$Dia)
					{
						$band = 1;
						?><script language="javascript">alert("Debe Seleccionar Dias para el suministro")</script><?
					}
					if(!$band)
					{
						$FechaI = "$ND[year]-$ND[mon]-$ND[mday]";
						if($Comenzar=="Manana"){ $Ma = $ND[mday]; $FechaI = "$ND[year]-$ND[mon]-$Ma";}
						if($FechaIni > "$ND[year]-$ND[mon]-$ND[mday]"){$FechaI = $FechaIni;}
					}
				}
				else
				{
					$cons = "Select Fecha from Salud.TMPFechasOMMedPro 
							where Compania='$Compania[0]' and Usuario='$usuario[0]' and CedPaciente='$Paciente[1]' and TMPCOD='$TMPCOD' order by Fecha asc";
					$res = ExQuery($cons);
					$NumFilas = ExNumRows($res); 
					if($NumFilas==0)
					{
						?><script language="javascript">alert("Debe Seleccionar Fechas para el suministro")</script><?
						$band = 1;
					}
					else
					{
						if(!$band)
						{
							$j = 0;
							if($Edit){
								$cons0="Delete from Salud.CalendarioXMedicamento where Compania='$Compania[0]' and AlmacenPpal='$AlmacenPpal' 
								and NoFormula=1 and CedPaciente = '$Paciente[1]' and numorden=$Numorden and idescritura=$IdEsc";
							}
							else{
								$cons0 = "Delete from Salud.CalendarioXMedicamento where Compania='$Compania[0]' and AlmacenPpal='$AlmacenPpal' 
								and AutoIdProd=$AutoIdProd and NoFormula=1 and CedPaciente = '$Paciente[1]'"; 
							}
							$res0 = ExQuery($cons0);
							$DeManera = " Fechas:";
							while($fila = ExFetch($res))
							{
								/*if($Edit){
									$cons0 = "Insert into Salud.CalendarioXMedicamento (Compania,AlmacenPpal,AutoIdProd,NoFormula,Fecha,cedpaciente,Estado,NumOrden,IdEscritura)
								values ('$Compania[0]','$AlmacenPpal',$AutoIdProd,1,'$fila[0]','$Paciente[1]','AC',$Numorden,$IdEsc)";
								}
								else{*/
									$cons0 = "Insert into Salud.CalendarioXMedicamento (Compania,AlmacenPpal,AutoIdProd,NoFormula,Fecha,cedpaciente,Estado,NumOrden,IdEscritura)
								values ('$Compania[0]','$AlmacenPpal',$AutoIdProd,1,'$fila[0]','$Paciente[1]','AC',$NoOrden,$IdEscritura)";
								//}
								$res0 = ExQuery($cons0);
								$DeManera = $DeManera." $fila[0],";
								if($j==0){ $FechaI = $fila[0];}
								if($j==($NumFilas-1))
								{ $FechaFin = "'$fila[0]'";}
								$j++;
							}
						}
					}
				}
				if(!$band)
				{
					//$Pslg="Suministrar ";
					if($Edit){
						/*$cons="update salud.horacantidadxmedicamento set estado='A'";
						$cons="delete from Salud.HoraCantidadXMedicamento where compania='$Compania[0]' and numorden=$Numorden and idescritura=$IdEsc
						and  Paciente='$Paciente[1]'";
						$res=ExQuery($cons);	*/					
					}				
					else{
						$cons = "Delete from Salud.HoraCantidadXMedicamento where Compania='$Compania[0]' and AlmacenPpal='$AlmacenPpal' 
						and AutoId=$AutoIdProd and Paciente='$Paciente[1]' and Tipo='P'";
					}
					$res = ExQuery($cons);
					
					
					$TextoOrden = "";
					while( list($cad,$val) = each($Cantidad))
					{
						if($val!="")
						{
							$hor = $cad;
							
							//$cant = substr($val,1,(strlen($val)-2)); Se comenta porque se utilizaba cuando se usaban los parentesis en la cantidad
							$cant = $val;							
							$mensaje = $Nota[$hor];
							$CantDiaria = $CantDiaria + $cant;
							
							if($Edit){
								$cons = "Insert into Salud.HoraCantidadXMedicamento (Compania,AlmacenPpal,AutoId,NoFormula,Hora,Cantidad,Nota,Paciente,Tipo,Fecha,Estado,NumOrden,IdEscritura,Via)
							values ('$Compania[0]','$AlmacenPpal',$AutoIdProd,1,$hor,$cant,'$mensaje','$Paciente[1]','P','$FechaI','AN',$NoOrden,$IdEscritura,'$ViadeSum')";
							}else{
								$cons = "Insert into Salud.HoraCantidadXMedicamento (Compania,AlmacenPpal,AutoId,NoFormula,Hora,Cantidad,Nota,Paciente,Tipo,Fecha,Estado,NumOrden,IdEscritura,Via)
							values ('$Compania[0]','$AlmacenPpal',$AutoIdProd,1,$hor,$cant,'$mensaje','$Paciente[1]','P','$FechaI','AC',$NoOrden,$IdEscritura,'$ViadeSum')";
							}
							
							$res = ExQuery($cons);
							$Cantidades = $Cantidades."$cant($hor), ";
							$Pslg=$Pslg."$cant ($hor:00) - ";
						}
					}
					$cons44 = "Select NombreProd1,UnidadMedida,Presentacion from Consumo.CodProductos where Compania='$Compania[0]'
					and AlmacenPpal='$AlmacenPpal' and Anio=$ND[year] and AutoId=253";
					$res44 = ExQuery($cons44);
					$fila44 = ExFetch($res44);
					$Medicamento44 = "$fila44[0] $fila44[1] $fila44[2]";
					$TextoOrden44 = "$Medicamento44";
					$Posolog44="$Cantidades $Dias $DeManera";
					$Pslg44=$Pslg44." $DeManera";
					if(substr($TextoOrden44,(strlen($TextoOrden44)-2),1)==",")
					{ $TextoOrden44 = substr($TextoOrden44,0,(strlen($TextoOrden44)-2));}
					
					$cons = "Select NombreProd1,UnidadMedida,Presentacion from Consumo.CodProductos where Compania='$Compania[0]'
					and AlmacenPpal='$AlmacenPpal' and Anio=$ND[year] and AutoId=$AutoIdProd";
					$res = ExQuery($cons);
					$fila = ExFetch($res);
					$Medicamento = "$fila[0] $fila[1] $fila[2]";
					$TextoOrden = "$Medicamento";
					$Posolog="$Cantidades $Dias $DeManera";
					$Pslg=$Pslg." $Dias $DeManera";
					if(substr($TextoOrden,(strlen($TextoOrden)-2),1)==",")
					{ $TextoOrden = substr($TextoOrden,0,(strlen($TextoOrden)-2));}
					
					if($Edit)
					{
						$edicion=1;
						/*
						$cons="update salud.ordenesmedicas set estado='AN', acarreo=0	where compania='$Compania[0]' 
						and IdEscritura=$IdEsc and NumOrden=$Numorden and cedula='$Paciente[1]'";
						$res = ExQuery($cons);	
																	
						$cons="update Salud.PlantillaMedicamentos set estado='AN'
						where compania='$Compania[0]' and IdEscritura=$IdEsc and NumOrden=$Numorden and cedpaciente='$Paciente[1]'";
						$res = ExQuery($cons);*/
						$FecRep1=",fechareprog";
						$FecRep2=",'$ND[year]-$ND[mon]-$ND[mday] $ND[hours]:$ND[minutes]:$ND[seconds]'";
						
						$cons = "Insert into Salud.OrdenesMedicas (Compania,Fecha,Cedula,NumServicio,Detalle,IdEscritura,NumOrden,Usuario,TipoOrden,estado,Acarreo,posologia,viasumin $FecRep1) 
						values
					('$Compania[0]','$ND[year]-$ND[mon]-$ND[mday] $ND[hours]:$ND[minutes]:$ND[seconds]','$Paciente[1]',$NoServicio,
					'$TextoOrden',$IdEscritura,$NoOrden,'$usuario[1]','Medicamento Programado','AN',1,'$Pslg','$ViadeSum' $FecRep2)";
						$res = ExQuery($cons);
						
						$cons = "Insert into Salud.PlantillaMedicamentos (Compania,AlmacenPpal,AutoIdProd,Usuario,FechaFormula,
						CedPaciente,FechaIni,FechaFin,CantDiaria,ViaSuministro,TraidoX,Lunes,Martes,Miercoles,Jueves,Viernes,Sabado,Domingo,
						Justificacion,Notas,estado,NumServicio,Detalle,TipoMedicamento,posologia,NumOrden,IdEscritura)
						values 
						('$Compania[0]','$AlmacenPpal','$AutoIdProd','$usuario[1]',
						'$ND[year]-$ND[mon]-$ND[mday] $ND[hours]:$ND[minutes]:$ND[seconds]','$Paciente[1]','$FechaI',$FechaFin,
						$CantDiaria,'$ViadeSum','$TraidoX','$Lunes','$Martes','$Miercoles','$Jueves','$Viernes','$Sabado','$Domingo',
						'$Justificacion','$Notas','AN',$NoServicio,'$TextoOrden','Medicamento Programado','$Pslg',$NoOrden,$IdEscritura)";
						$res = ExQuery($cons);
					}
					//else{
					if ($edicion!=1){
						$cons = "Insert into Salud.OrdenesMedicas (Compania,Fecha,Cedula,NumServicio,Detalle,IdEscritura,NumOrden,Usuario,TipoOrden,Acarreo,posologia,viasumin $FecRep1) 
						values
					('$Compania[0]','$ND[year]-$ND[mon]-$ND[mday] $ND[hours]:$ND[minutes]:$ND[seconds]','$Paciente[1]',$NoServicio,
					'$TextoOrden',$IdEscritura,$NoOrden,'$usuario[1]','Medicamento Programado',1,'$Pslg','$ViadeSum' $FecRep2)";
						$res = ExQuery($cons);	

						$cons = "Insert into Salud.PlantillaMedicamentos (Compania,AlmacenPpal,AutoIdProd,Usuario,FechaFormula,
						CedPaciente,FechaIni,FechaFin,CantDiaria,ViaSuministro,TraidoX,Lunes,Martes,Miercoles,Jueves,Viernes,Sabado,Domingo,
						Justificacion,Notas,NumServicio,Detalle,TipoMedicamento,posologia,NumOrden,IdEscritura)
						values 
						('$Compania[0]','$AlmacenPpal','$AutoIdProd','$usuario[1]',
						'$ND[year]-$ND[mon]-$ND[mday] $ND[hours]:$ND[minutes]:$ND[seconds]','$Paciente[1]','$FechaI',$FechaFin,
						$CantDiaria,'$ViadeSum','$TraidoX','$Lunes','$Martes','$Miercoles','$Jueves','$Viernes','$Sabado','$Domingo',
						'$Justificacion','$Notas',$NoServicio,'$TextoOrden','Medicamento Programado','$Pslg',$NoOrden,$IdEscritura) returning *";
						$res = ExQuery($cons);
						$fila=ExFetchAssoc($res);
						if($medicamentoss='AMPOLLA'){
						
							$ND2 = getdate();
							/*$cons44 = "Insert into Salud.OrdenesMedicas (Compania,Fecha,Cedula,NumServicio,Detalle,IdEscritura,NumOrden,Usuario,TipoOrden,Acarreo,posologia,viasumin $FecRep1) 
									values
									('$Compania[0]','$ND2[year]-$ND2[mon]-$ND2[mday] $ND2[hours]:$ND2[minutes]:$ND2[seconds]'','$Paciente[1]',$NoServicio,
									'$TextoOrden44',$IdEscritura,$NoOrden2,'$usuario[1]','Medicamento Programado',1,'$Pslg44','$ViadeSum' $FecRep2)";
							$res44 = ExQuery($cons);*/
							/*$cons45 = "Insert into Salud.PlantillaMedicamentos (Compania,AlmacenPpal,AutoIdProd,Usuario,FechaFormula,
							CedPaciente,FechaIni,FechaFin,CantDiaria,ViaSuministro,TraidoX,Lunes,Martes,Miercoles,Jueves,Viernes,Sabado,Domingo,
							Justificacion,Notas,NumServicio,Detalle,TipoMedicamento,posologia,NumOrden,IdEscritura)
							values 
							('$Compania[0]','$AlmacenPpal','253','$usuario[1]',
							'$ND[year]-$ND[mon]-$ND[mday] ','$Paciente[1]','$FechaI',$FechaFin,
							$CantDiaria,'$ViadeSum','$TraidoX','$Lunes','$Martes','$Miercoles','$Jueves','$Viernes','$Sabado','$Domingo',
							'$Justificacion','$Notas',$NoServicio,'$TextoOrden44','Medicamento Programado','$Pslg44',$NoOrden2,$IdEscritura)";
							$res45= ExQuery($cons45);*/
						}
						$edicion=5;

						// Consulta tiposervicio para enviarlo a Notas Evolución
						$cons5 = "Select numservicio,tiposervicio from Salud.Servicios 
                        where Compania = '$Compania[0]' and cedula='$Paciente[1]' and estado='AC' order by numservicio desc";
                        //echo $cons5;
                        $res5 = ExQuery($cons5);
                        $fila5 = ExFetchAssoc($res5);
                        
						// Consulta el id_historia para enviarlo a Notas evolución
                        $consih="SELECT id_historia FROM histoclinicafrms.tbl00004 ORDER BY id_historia DESC LIMIT 1";
                        $resih = ExQuery($consih);
                        $filaih=ExFetchAssoc($resih);
						
						$identificador = $filaih['id_historia']+1;
                        
						$textonota = "";
						if($fila['notas']!="")
							$textonota = "Nota";
						
						if($Unidad==""){
							// Envía el medicamento a Notas Evolución
							$consulta="INSERT INTO histoclinicafrms.tbl00004 "
							 . "(ambito, cargo, causaexterna, cedula, cerrado, cmp00002, cmp00004, cmp00006, cmp00008, cmp00012, compania, dx1, dx2, dx3, dx4, dx5, fecha, fechaajuste, finalidadconsult, formato, hora, id_historia, id_historia_origen, noliquidacion, numproced, numservicio, padreformato, padretipoformato, tipodx, tipoformato, unidadhosp, usuario, usuarioajuste) "
							 . "VALUES ( '".$fila5['tiposervicio']."', '".$usuario[3]."', NULL, '".$fila['cedpaciente']."', NULL, '', '', '', '', '".$fila['detalle']." ".$fila['posologia']." Vía ".$fila['viasuministro']." Justificacion ".$fila['justificacion']." $textonota ".$fila['notas']."', '".$fila['compania']."', NULL, NULL, NULL, NULL, NULL, '$ND[year]-$ND[mon]-$ND[mday]', NULL, '', 'NOTAS EVOLUCION', '$ND[hours]:$ND[minutes]:$ND[seconds]', ".$identificador.", NULL, NULL, NULL, '".$fila['numservicio']."', '', '', NULL, 'HISTORIA CLINICA', NULL, '".$fila['usuario']."', '' );";
						}
						else{
							$consulta="INSERT INTO histoclinicafrms.tbl00004 "
							 . "(ambito, cargo, causaexterna, cedula, cerrado, cmp00002, cmp00004, cmp00006, cmp00008, cmp00012, compania, dx1, dx2, dx3, dx4, dx5, fecha, fechaajuste, finalidadconsult, formato, hora, id_historia, id_historia_origen, noliquidacion, numproced, numservicio, padreformato, padretipoformato, tipodx, tipoformato, unidadhosp, usuario, usuarioajuste) "
							 . "VALUES ( '".$fila5['tiposervicio']."', '".$usuario[3]."', NULL, '".$fila['cedpaciente']."', NULL, '', '', '', '', '".$fila['detalle']." ".$fila['posologia']." Vía ".$fila['viasuministro']." Justificacion ".$fila['justificacion']." $textonota ".$fila['notas']."', '".$fila['compania']."', NULL, NULL, NULL, NULL, NULL, '$ND[year]-$ND[mon]-$ND[mday]', NULL, '', 'NOTAS EVOLUCION', '$ND[hours]:$ND[minutes]:$ND[seconds]', ".$identificador.", NULL, NULL, NULL, '".$fila['numservicio']."', '', '', NULL, 'HISTORIA CLINICA', '".$Unidad."', '".$fila['usuario']."', '' );";
						}
							$resnoev = ExQuery($consulta);
							$filanoev=ExFetch($resnoev);
					}
					//}				
					echo "<font size='1' color='yellow' style='text-align:rigth'>ahora $edicion</font></BR>";
					if(!$POS&&!$Edit){
						
						//Envio de la orden al correo						
						$cons="select ambito,pabellon from salud.pacientesxpabellones where compania='$Compania[0]' and numservicio=$NoServicio and estado='AC'
						and fechae is null";
						$res = ExQuery($cons);	$fila=ExFetch($res); $Pab=$fila[1]; $Amb=$fila[0];
						
						$cons="select id from central.correos where compania='$Compania[0]'  order by id desc"; 
						$res=ExQuery($cons); $fila=ExFetch($res); $Id=$fila[0]+1;
						
						$Msj="Se ha ordenado el medicamento NO POS $TextoOrden al paciente $Paciente[2] $Paciente[3] $Paciente[4] $Paciente[5]- CC $Paciente[1] 
						($Amb - $Pab) el dia $ND[year]-$ND[mon]-$ND[mday] a las $ND[hours]:$ND[minutes]:$ND[seconds] <br><br>Att=$usuario[0]";			
						$cons2="select usuario from salud.medicos,salud.cargos
						where medicos.compania='$Compania[0]' and medicos.cargo=cargos.cargos and vistobuenojefe=1 and cargos.compania='$Compania[0]'";
						$res2=ExQuery($cons2);
						while($fila2=ExFetch($res2))
						{
							$cons="insert into central.correos (compania,usucrea,fechacrea,usurecive,mensaje,id,asunto) values
							('$Compania[0]','$usuario[1]','$ND[year]-$ND[mon]-$ND[mday] $ND[hours]:$ND[minutes]:$ND[seconds]','$fila2[0]','$Msj',$Id
							,'Orden Medicamento Programado NO POS')";							
							$res=ExQuery($cons);
							$Id++;
						}
						//------------------------------
						// Comentado porque solicita información de un formato de registro de medicamentos no pos
						/*$cons="select formato,tipoformato,tblformat from historiaclinica.formatos where compania='$Compania[0]' and estado='AC' and nopos='Medicamentos No POS'";
						$res=ExQuery($cons);
						$fila=ExFetch($res);
						$consT="select id_historia from histoclinicafrms.$fila[2] where formato='$fila[0]' and tipoformato='$fila[1]' and cedula='$Paciente[1]' 
						order by id_historia desc";
						$resT=ExQuery($consT);
						$filaT=ExFetch($resT);
						$IdH=$fila[0]+1;
						$AbrirForm="1";
						
						*/ 
						?>                       
						
						<!--
						<script language="javascript">							
							document.getElementById('FrameFondo').style.position='absolute';
							document.getElementById('FrameFondo').style.top='1px';
							document.getElementById('FrameFondo').style.left='1px';
							document.getElementById('FrameFondo').style.display='';
							document.getElementById('FrameFondo').style.width='1000';
							document.getElementById('FrameFondo').style.height='800';
							
							frames.FrameOpenerNP.location.href="/HistoriaClinica/NuevoRegistro.php?DatNameSID=<? echo $DatNameSID?>&CedPac=<? echo $Paciente[1]?>&Fecha=<? echo "$ND[year]-$ND[mon]-$ND[mday]"?>&NumSer=<? echo $NoServicio?>&SoloUno=<? echo $IdH;?>&Formato=<? echo $fila[0]?>&TipoFormato=<? echo $fila[1]?>&Medicamento=<? echo $Medicamento?>&Posologia=<? echo $Pslg?>&MedNP=1&AlmacenPpal=<? echo $AlmacenPpal?>&AutoIdProd=<? echo $AutoIdProd?>&IdEscritura=<? echo $IdEscritura?>&FechaI=<? echo $FechaI?>&TipoMedicamento=Medicamento Programado";
							document.getElementById('FrameOpenerNP').style.position='absolute';
							document.getElementById('FrameOpenerNP').style.top='10px';
							document.getElementById('FrameOpenerNP').style.left='10px';
							document.getElementById('FrameOpenerNP').style.display='';
							document.getElementById('FrameOpenerNP').style.width='990';
							document.getElementById('FrameOpenerNP').style.height='790';						
						</script>
						-->
						<script language="javascript">location.href="NuevaOrdenMedica.php?DatNameSID=<? echo $DatNameSID?>&IdEscritura=<? echo $IdEscritura?>";</script>
				<?	}
					else{
					?>	<script language="javascript">location.href="NuevaOrdenMedica.php?DatNameSID=<? echo $DatNameSID?>&IdEscritura=<? echo $IdEscritura?>";</script><?
					}
				}				
			//}			
		}
	}
	for($i=0;$i<24;$i++)
	{
		if($Cantidad[$i]!="")
		{
			
			$f=1;
			break;
		}
	}
	if(!$f){unset($Cantidad);}
	if(!$FechasCalendario){ $FechasCalendario = array(); }
	$ND = getdate();
	if(!$TMPCOD){$TMPCOD=strtotime("$ND[year]-$ND[mon]-$ND[mday] $ND[hours]:$ND[minutes]:$ND[seconds]").rand(1,9999);}
	if($Eliminar)
	{
		$cons = "Delete from Salud.TMPFechasOMMedPro where Compania='$Compania[0]' and Usuario='$usuario[0]'
		and CedPaciente='$Paciente[1]' and Fecha='$FechaElim' and TMPCOD='$TMPCOD'";
		$res = ExQuery($cons);
		$Eliminar = "";
		$FechaElim = "";
	}
	if($Anadir)
	{
		if($Fecha)
		{
			if($Fecha < $FechaActual)
			{
				?><script language="javascript">alert("La Fecha a Anadir no debe ser menor a la Actual !!");</script><?
			}
			else
			{
				$cons = "Select Fecha from Salud.TMPFechasOMMedPro 
				where Compania='$Compania[0]' and Usuario='$usuario[0]' and CedPaciente='$Paciente[1]' and TMPCOD='$TMPCOD'";
				$res = ExQuery($cons);
				while($fila = ExFetch($res)){ if($Fecha==$fila[0]){$b=1;}}
				if(!$b)
				{
					$cons = "Insert into Salud.TMPFechasOMMedPro (Compania,CedPaciente,Usuario,TMPCOD,Fecha) 
					values ('$Compania[0]','$Paciente[1]','$usuario[0]','$TMPCOD','$Fecha')";
					$res = ExQuery($cons);
				}
				else{?><script language="javascript">alert("Fecha Repetida");</script><? }
			}
		}
	}
?>
<script language="javascript" src="/Funciones.js"></script>
<script language='javascript' src="/calendario/popcalendar.js"></script>
<script language="javascript">
	function AbrirMedicamentos()
	{
		frames.FrameOpener.location.href="Medicamentos.php?DatNameSID=<? echo $DatNameSID?>&Formulacion=Programados";
		document.getElementById('FrameOpener').style.position='absolute';
		document.getElementById('FrameOpener').style.top='20px';
		document.getElementById('FrameOpener').style.left='30px';
		document.getElementById('FrameOpener').style.display='';
		document.getElementById('FrameOpener').style.width='600px';
		document.getElementById('FrameOpener').style.height='450px';
	}
	function AbrirCantidades(ind,x,y,amp)
	{
		frames.FrameOpener.location.href="Cantidades.php?DatNameSID=<? echo $DatNameSID?>&Indice="+ind+"&tipopresenta="+amp;
		document.getElementById('FrameOpener').style.position='absolute';
		document.getElementById('FrameOpener').style.top=y;
		document.getElementById('FrameOpener').style.left=x;
		document.getElementById('FrameOpener').style.display='';
		document.getElementById('FrameOpener').style.width='160';
		document.getElementById('FrameOpener').style.height='165';
	}
	function OpcionChequeo(Chk,e,ampo)
	{
		var ind = Chk.value;
		var amp =ampo.value;
		if(Chk.checked == true)
		{
			for (i=0;i<document.FORMA.elements.length;i++)
			{
				if(document.FORMA.elements[i].type == "checkbox")
				{
					document.FORMA.elements[i].disabled = true;
				} 
 			} 
			var x = e.clientX;
			var y = e.clientY;
			AbrirCantidades(ind,x,y,amp);
		}
		else
		{
			document.getElementById('Cantidad['+ind+']').value="";
			document.getElementById('Cantidad['+ind+']').title="";
			document.getElementById('Nota['+ind+']').value="";
			document.FORMA.submit();
		}
		 
	}
	
	function Validar()
	{
		if(document.FORMA.NoFormato.value==1&&document.FORMA.NoValidar.value!="1"){
			alert("Este medicamento no puede ser ordenado ya que no se encuentra el formato para justificacion de Medicamentos No POS"); 
			return false;
		}		
	}
	
	function validaDosis(id){
		var validaciones = 2;
		var numeros="0123456789.";		
		var valor = document.getElementById(id).value;
		
		for (var i=0;i<valor.length;i++)
		{
			tmp=valor.substring(i,i+1)
			if (numeros.indexOf(tmp)==-1){
				alert(" Si es dosis fraccionada favor escribirla con punto (.) \r\n La cantidad no debe incluir letras ni comas \r\n");
				document.getElementById(id).value = '';
				document.getElementById(id).focus();
				validaciones = validaciones -1;
				return false;
			}
			
		}
		
		var modulo = (valor%0.25);	
				
			if(modulo != 0){
				alert("Las dosis fraccionadas se deben ordenar por 0.5 , 0.25 o 0.75");				
				document.getElementById(id).value = '';
				document.getElementById(id).focus();
				validaciones = validaciones -1;
				return false;
			}
			
			if(valor > 6){
				alert("Favor verificar si la dosis escrita es correcta");
				document.getElementById(id).focus();
						
			}
			
			if(validaciones == 2){
				
				document.getElementById(id).focus();
				//document.FORMA.submit();
			}
			
	}
</script>
<body background="/Imgs/Fondo.jpg">
<?
if($Edit){	
	$consEdit="Select detalle,plantillamedicamentos.almacenppal,autoidprod,codproductos.presentacion from salud.plantillamedicamentos, consumo.codproductos
	where plantillamedicamentos.compania='$Compania[0]' and cedpaciente='$Paciente[1]' and numorden=$Numorden and idescritura=$IdEsc
	and consumo.codproductos.almacenppal='FARMACIA'
	and consumo.codproductos.autoid=autoidprod
	and consumo.codproductos.compania='$Compania[0]'";
	//echo $consEdit;
	$resEdit=ExQuery($consEdit);
	$filaEdit=ExFetch($resEdit);
	$Medicamento=$filaEdit[0];
	$AlmacenPpal=$filaEdit[1];
	$AutoIdProd=$filaEdit[2];
	$Medicamentoss=$filaEdit[3];
	$AMPOLLA=$filaEdit[3];
}
?>
<form name="FORMA" method="post" onSubmit="return Validar()">
<input type="hidden" name="TMPCOD" value="<? echo $TMPCOD?>" />
<input type="Hidden" name="AutoIdProd" value="<? echo $AutoIdProd?>" />
<input type="hidden" name="AlmacenPpal" value="<? echo $AlmacenPpal?>" />
<input type="Hidden" name="IdEscritura" value="<? echo $IdEscritura?>" />
<input type="hidden" name="NoServicio" value="<? echo $NoServicio?>" />
<input type="hidden" name="NoOrden" value="<? echo $NoOrden?>" />
<input type="hidden" name="POS" value="<? echo $POS?>">
<input type="hidden" name="MedNoPos" value="<? echo $MedNoPos?>">
<input type="hidden" name="TipoFormato" value="<? echo $TipoFormato?>">
<input type="hidden" name="Formato" value="<? echo $Formato?>">
<input type="hidden" name="AbrirForm" value="<? echo $AbrirForm?>">

	<table rules="groups" width="60%" align="center" cellpadding="2" border="1" bordercolor="#e5e5e5" style='font : normal normal small-caps 13px Tahoma;'>
    	<tbody>
			<tr>
			<td colspan="3" align="center" style="padding-bottom:25px; padding-top:10px;"><input type="hidden" name="Medicamentoss" value="<? echo $Medicamentoss?>" readonly size="90" style="text-align:center"/>
     <?	if(!$Edit){?>
			<button onclick="AbrirMedicamentos()" value="Escoger Medicamento" style="width:200px;" name="Escoger Medicamento"><img src="/Imgs/HistoriaClinica/bigfolder.png"><br>Escoger Medicamento</button>
			</td>
   	<?	}?>
        </tr>
		<tr>
        	<td colspan="3" align="center"><input type="Text" name="Medicamento" value="<? echo $Medicamento?>" readonly size="90" style="text-align:center;border-style:solid;border-width:0px;"/></td></tr>
		</tbody>
 <? if($Medicamento)
	{ ?>
    	<tbody><tr>
        	<td colspan="3" bgcolor="<? echo $Estilo[1]?>" style="color:white" align="center"><strong>FRECUENCIA</strong></td>
        </tr></tbody>
        <tbody>
        <tr>
        	<td colspan="3">
            	<table border="1" bordercolor="#e5e5e5" width="100%" style='font : normal normal small-caps 13px Tahoma;'>
                	<tr bgcolor="#e5e5e5" style="font-weight:bold"><td colspan="8">Ma&ntilde;ana</td></tr>
                    <tr>
						<? for($i=4;$i<=11;$i++)
						{
							?><td width='12.5%'><? echo $i.":00"?>
                            
                            <input type="hidden" name="Nota[<? echo $i?>]" id="Nota[<? echo $i?> ]"
                            value="<? echo $Nota[$i]?>" /></td><?
						} ?>
                    </tr>
                    <tr>
						<? for($i=4;$i<=11;$i++)
						{
							?><td width='12.5%' align="center">
                            <input type="text" name="Cantidad[<? echo $i;?>]" id="Cantidad[<?echo $i;?>]" title="<? echo $Nota[$i]?> " 
                            value = "<? echo $Cantidad[$i]?>"   size="4" maxlength="4" max = "10"  style="text-align:center;" onChange="validaDosis(this.id)"/></td><?
						} ?>
                    </tr>
                    <tr bgcolor="#e5e5e5" style="font-weight:bold"><td colspan="8">Tarde</td></tr>
                    <tr>
						<? for($i=12;$i<=19;$i++)
						{
							?><td width='12.5%' title="<? echo $Nota[$i]?>"><? echo $i.":00"?>
                            
                            <input type="hidden" name="Nota[<? echo $i?>]" id="Nota[<? echo $i?>]"
                            value="<? echo $Nota[$i]?>" /></td><?
						} ?>
                    </tr>
                    <tr>
						<? for($i=12;$i<=19;$i++)
						{
							?><td width='12.5%' align="center">
								<input type="text" name="Cantidad[<? echo $i;?>]" id="Cantidad[<? echo $i;?>]" 
                            value = "<? echo $Cantidad[$i]?>"  size="4" maxlength="4" max = "10"  style="text-align:center;" onChange="validaDosis(this.id)" />
							</td><?
						} ?>
                    </tr>
                    <tr bgcolor="#e5e5e5" style="font-weight:bold"><td colspan="8">Noche</td></tr>
                    <tr>
						<? for($i=20;$i<=27;$i++)
						{
							if($i>23){$j=$i-24;}
							else{$j = $i;}
							?><td width='12.5%' title="<? echo $Nota[$j]?>"><? echo $j.":00"?>
                           
                            <input type="hidden" name="Nota[<? echo $j?>]" id="Nota[<? echo $j?> ]"
                            value="<? echo $Nota[$j]?>" /></td><?
						} ?>
                    </tr>
                    <tr>
						<? for($i=20;$i<=27;$i++)
						{
							if($i>23){$j=$i-24;}
							else{$j = $i;}
							?><td width='12.5%' align="center">
                            <input type="text" name="Cantidad[<? echo $j;?>]" id="Cantidad[<? echo $j;?>]"
                            value = "<? echo $Cantidad[$j]?>"  size="4" maxlength="4" max = "10"  style="text-align:center;" onChange="validaDosis(this.id)"/></td><?
						} ?>
                    </tr>
                </table>
            </td>
        </tr>
        </tbody>
		<tbody>
		<tr>
		 <tr>
                	<td colspan="7" bgcolor="#e5e5e5" align="center">Notas</td>
                </tr>
                	<td colspan="7">
                    	<textarea name="Notas" style="width:100%" onKeyUp="xLetra(this)" onKeyDown="xLetra(this)"><? echo $Notas?></textarea>                    </td>
                </tr>
				<tr>
                	<td colspan="7" bgcolor="#e5e5e5" align="center">Justificaci&oacute;n</td>
                </tr>
                <tr>
                	<td colspan="7">
                    	<textarea name="Justificacion" onKeyUp="xLetra(this)" onKeyDown="xLetra(this)" style="width:100%"><? echo $Justificacion?></textarea>                    </td>
                </tr>
				<tr>
				</tr>
				<?php
					if(!$Cantidad){
				?>
					<td colspan="7" style="text-align:center; padding-top:10px; padding-bottom:20px;"><input type="submit" value="Frecuencia" style="width:50%;"></td>
				<?php
					}
				?>
        <? if($Cantidad)
		{ ?>
			<tr>
        		<td colspan="3" bgcolor="<? echo $Estilo[1]?>" style="color:white" align="center"><strong>SUMINISTRO</strong></td>
        	</tr></tbody>
            <tbody><tr align="center">
            	<td>Indefinido<input type="radio" name="Suministro" id="Suministro1" value="Indefinido" 
                <? if($Suministro=="Indefinido"){ echo " checked ";} ?> onClick="FORMA.submit()" /></td>
                <td>Definido<input type="radio" name="Suministro" id="Suministro2" value="Definido" 
                <? if($Suministro=="Definido"){ echo " checked ";} ?> onClick="FORMA.submit()" /></td>
                <td>Calendario<input type="radio" name="Suministro" id="Suministro3" value="Calendario" 
                <? if($Suministro=="Calendario"){ echo " checked ";} ?> onClick="FORMA.submit()" /></td>
            </tr></tbody>
            <?
            if($Suministro)
			{	unset($MostrarCancelar);
				?> <tbody><tr><td colspan="3">
            	<table width="100%" rules="groups" cellpadding="2" border="1" bordercolor="#e5e5e5" style='font : normal normal small-caps 13px Tahoma;'><?
				if($Suministro!="Calendario")
				{
					if($Suministro == "Definido")
					{
						?><tbody><tr>
                        	<td colspan="3" align="center">Desde:<input type="text" name="FechaIni" readonly="readonly" size="6"
        										   onclick="popUpCalendar(this, FORMA.FechaIni, 'yyyy-mm-dd')"  value="<? echo $FechaIni; ?>"  /></td>
                            <td>&nbsp;</td>
                            <td colspan="3" align="center">Hasta:<input type="text" name="FechaFin" readonly="readonly" size="6"
        											onclick="popUpCalendar(this, FORMA.FechaFin, 'yyyy-mm-dd')"  
                                                    value="<? echo substr($FechaFin,1,(strlen($FechaFin)-2)); ?>"  /></td>
                        </tr></tbody><?
					}
					?>
						<tbody><tr align="center" bgcolor="#e5e5e5">
                        	<td>Lun</td><td>Mar</td><td>Mier</td><td>Jue</td><td>Vie</td><td>Sab</td><td>Dom</td></tr><tr>
                            <?
                            for($i=1;$i<=7;$i++)
							{
								?><td align="center" width="14.28%"><input type="checkbox" name="Dia[<? echo $i;?>]" value="<? echo $i?>" 
                                <? if($Editar){if($Dia[$i]){ echo " checked ";}}else{ echo " checked ";}?>/></td>
                            <?
							}	
							?>
                        </tr>
                        <tr>
                        	<td colspan="7" align="right">Comenzar:<select name="Comenzar">
                            <option value="Hoy" <? if($Comenzar=="Hoy"){echo " selected ";}?> >Hoy</option>
                            <option value="Manana" <? if($Comenzar=="Manana"){echo " selected ";}?>>Ma&ntilde;ana</option>
                            </select></td>
                        </tr>
                        </tbody>
                     <?
                }
				else
				{
					$cons = "Select Fecha from Salud.TMPFechasOMMedPro 
					where Compania='$Compania[0]' and Usuario='$usuario[0]' and CedPaciente='$Paciente[1]' and TMPCOD='$TMPCOD'";
					$res = ExQuery($cons);
					if(ExNumRows($res)>0)
					{
						?>
                        <tbody><tr><td colspan="7">
						<table border="1" width="100%" align="center" bordercolor="<? echo $Estilo[1]?>" style='font : normal normal small-caps 13px Tahoma;'><tr>
                        <input type="hidden" name="Eliminar" id="Eliminar" />
                        <input type="hidden" name="FechaElim" id="CampoFechaElim" />
                        <?
						$j=0;
                        while($fila = ExFetch($res))
						{
							if($j==4){$j=0;echo"</tr><tr>";}
							?><td align="center" onMouseOver="this.bgColor='#AAD4FF'" onMouseOut="this.bgColor=''"><? echo $fila[0]?>
                            <img src="/Imgs/b_drop.png" style="cursor:hand" title="Eliminar Fecha"
                            onClick="FORMA.Eliminar.value='1';FORMA.FechaElim.value='<? echo $fila[0]?>';FORMA.submit();" /></td><? 
							$j++;
						}
						?>
                        </tr></table></td></tr></tbody>
						<?	
					}
					?><tbody>
                    <tr><td colspan="7" align="center">Fecha:<input type="text" name="Fecha" readonly="readonly" size="6"
        												onclick="popUpCalendar(this, FORMA.Fecha, 'yyyy-mm-dd')"  />
                        <button type="submit" name="Anadir"><img src="/Imgs/b_check.png" title="A&ntilde;adir" /></button></td>
                    </tr></tbody><?
				}
			?>
               <tr>
             	<td colspan="3">Via de Suministro:
                <select name="ViadeSum">
                <?
                	$cons = "Select NombreVia from Salud.ViadeSuministro where Compania='$Compania[0]'";
					$res = ExQuery($cons);
					while($fila = ExFetch($res))
					{
						if($ViadeSum==$fila[0]){echo "<option selected value='$fila[0]'>$fila[0]</option>";}
						else {echo "<option value='$fila[0]'>$fila[0]</option>";}
					}
				?>
                </select></td>
                <td>&nbsp;</td>
                <td colspan="4">Traido X:
                <select name="TraidoX"><option value=""></option>
                <?
                	$cons = "Select CedAcudiente,NombreAcudiente,Parentesco from ContratacionSalud.Acudientes where Compania='$Compania[0]' and
					CedPaciente='$Paciente[1]'";
					$res = ExQuery($cons);
					while($fila = ExFetch($res))
					{
						if($TraidoX==$fila[0]){echo "<option selected value='$fila[0]'>$fila[1]($fila[2])</option>";}
						else{ echo "<option value='$fila[0]'>$fila[1]($fila[2])</option>";}
					}
				?>
                </select></td>
                </tr>
                </tbody>
			</table></td></tr></tbody>
			<tbody><tr><td colspan="3">
            <center>
			
			<input type="submit" name="Guardar" value="Guardar Medicamento" />
            <input type="submit" name="Cancelar" value="Cancelar" onClick="document.FORMA.NoValidar.value=1;"/></center></td></tr></tbody><? }
		 }
     }?>	
    </table>
<?
	if($MostrarCancelar)
	{?><center>
<input type="button" name="BtnMostrarCancelar" value="Cancelar" 
    onClick="location.href='NuevaOrdenMedica.php?DatNameSID=<? echo $DatNameSID?>&IdEscritura=<? echo $IdEscritura?>'"></center><? }
?>
<input type="hidden" name="DatNameSID" value="<? echo $DatNameSID?>">
<input type="hidden" name="NoFormato">
<input type="hidden" name="NoValidar">
<input type="hidden" name="Edit" value="<? echo $Edit?>">
</form>
<script language="javascript">
if(document.FORMA.POS.value!="1"&&document.FORMA.MedNoPos.value!=1){document.FORMA.NoFormato.value="1";}else{document.FORMA.NoFormato.value="";}
</script>

<iframe scrolling="no" id="FrameOpener" name="FrameOpener" style="display:none" frameborder="0" height="1" style="border:#e5e5e5 ridge" ></iframe>
<? if($ExisteAutoId)
{
	?><script language="javascript">alert("El Medicamento Seleccionado ya tiene una Orden Medica Activa para este Paciente");</script><?
}?> 

</body>