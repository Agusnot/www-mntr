<?php
	if($DatNameSID){session_name("$DatNameSID");}
	session_start();
	include("Funciones.php");	
	$ND = getdate();	
	if($TMPCOD==''){$TMPCOD=strtotime("$ND[year]-$ND[mon]-$ND[mday] $ND[hours]:$ND[minutes]:$ND[seconds]").rand(1,9999);}
	if($TMPCOD2==''){$TMPCOD2=strtotime("$ND[year]-$ND[mon]-$ND[mday] $ND[hours]:$ND[minutes]:$ND[seconds]").rand(1,9999);}
	$cons="select formato,tipoformato from historiaclinica.formatos where compania='$Compania[0]' and nopos='CUPS No POS'";
	$res=ExQuery($cons);
	if(ExNumRows($res)>0){
		$MedNoPos=1;
		$fila=ExFetch($res);
		$Formato=$fila[0]; $TipoFormato=$fila[1];
	}
	else{
		$CUPNoPos=0;
	}
	//------------------------Añadir Fechas-------------------------
	if($Anadir)
	{
		if($Fecha)
		{			
			$FechaComp='$ND[year]-$ND[mon]-$ND[mday]';
			if($Fecha>=$FechaComp){
				$cons = "Select Fecha from Salud.TMPFechasOMMedPro 
					where Compania='$Compania[0]' and Usuario='$usuario[0]' and CedPaciente='$Paciente[1]' and TMPCOD='$TMPCOD'";
				$res = ExQuery($cons);
				while($fila = ExFetch($res)){ if($Fecha==$fila[0]){$b=1;}}
				if(!$b)
				{
					$cons = "Insert into Salud.TMPFechasOMMedPro (Compania,CedPaciente,Usuario,TMPCOD,Fecha,hora) 
					values ('$Compania[0]','$Paciente[1]','$usuario[0]','$TMPCOD','$Fecha','$HoraF')";
					$res = ExQuery($cons);
				}
				else{?><script language="javascript">alert("Fecha Repetida");</script><? }
			}
			else{
				$MalFecha=1;
			}
		}
	}
	//----------------------Eliminar Fechas
	if($Eliminar)
	{
		$cons = "Delete from Salud.TMPFechasOMMedPro where Compania='$Compania[0]' and Usuario='$usuario[0]'
		and CedPaciente='$Paciente[1]' and Fecha='$FechaElim' and  tmpcod='$TMPCOD'";
		$res = ExQuery($cons);
		$Eliminar = "";
		$FechaElim = "";
	}
	if($ElimCUP)
	{
		$cons="delete from salud.tmpcupsordenesmeds where cup='$ElimCUP' and cedula='$Paciente[1]' and tmpcod='$TMPCOD2' and compania='$Compania[0]'";
		$res = ExQuery($cons);
		//echo $cons;
	}
	if(!$Limpiar){		
		$Limpiar=1;
		$cons = "Delete from Salud.TMPFechasOMMedPro where Compania='$Compania[0]' and cedpaciente='$Paciente[1]' and Usuario='$usuario[0]' and tmpcod='$TMPCOD'";
		$res = ExQuery($cons);
		$cons = "Delete from Salud.tmpcupsordenesmeds where Compania='$Compania[0]' and cedula='$Paciente[1]' and tmpcod='$TMPCOD2'";
		$res = ExQuery($cons);
		$Eliminar = "";
		$FechaElim = "";
	}
	if($Salir){
	 	$cons = "Delete from Salud.TMPFechasOMMedPro where Compania='$Compania[0]' and cedpaciente='$Paciente[1]' and Usuario='$usuario[0]' and tmpcod='$TMPCOD'";
		$res = ExQuery($cons);echo ExError($res);
		$res = ExQuery($cons);
		$cons = "Delete from Salud.tmpcupsordenesmeds where Compania='$Compania[0]' and cedula='$Paciente[1]' and tmpcod='$TMPCOD2'";
		$res = ExQuery($cons);?>
		<script language="javascript">
			location.href='NuevaOrdenMedica.php?DatNameSID=<? echo $DatNameSID?>&IdEscritura=<? echo $IdEscritura?>';
		</script><?php
	}
    $cons5 = "Select numservicio,tiposervicio from Salud.Servicios 
    where Compania = '$Compania[0]' and cedula='$Paciente[1]' and estado='AC' order by numservicio desc";					
    //echo $cons5;
    $res5 = ExQuery($cons5);
    $fila5 = ExFetch($res5);			
    $NumServ=$fila5[0];
    $AmbitoRealiz=$fila5[1];
    $cons="select numorden from salud.ordenesmedicas 
    where cedula='$Paciente[1]' and compania='$Compania[0]' and idescritura='$IdEscritura' order by numorden desc";
    $res = ExQuery($cons);
    if(ExNumRows($res)>0){
        $fila = ExFetch($res);		
        $AutoId = $fila[0]+1;
        $NoOrden = $AutoId;
    }
    else{
        $AutoId=1;
        $NoOrden = $AutoId;
    }
	if($Guardar)
	{		
		$cons="select ambito,pabellon from salud.pacientesxpabellones where compania='$Compania[0]' and numservicio=$NumServ and estado='AC'
		and fechae is null";
		$res = ExQuery($cons);	$fila=ExFetch($res); 
		$Pab=$fila[1]; $Amb=$fila[0];
		
		$cons5 = "Select numprocedimiento from Salud.plantillaprocedimientos 
		where Compania = '$Compania[0]' and cedula='$Paciente[1]' and numservicio='$NumServ' order by numprocedimiento desc";					
		//echo $cons5;
		$res5 = ExQuery($cons5);
		if(ExNumRows($res5)>0){
			$fila5 = ExFetch($res5);			
			$NumProc=$fila5[0]+1;
		}
		else{
			$NumProc=1;
		}
		$cons="select numorden from salud.ordenesmedicas 
		where cedula='$Paciente[1]' and compania='$Compania[0]' and idescritura='$IdEscritura' order by numorden desc";
		$res = ExQuery($cons);
		if(ExNumRows($res)>0){
			$fila = ExFetch($res);		
			$AutoId = $fila[0]+1;
            $NoOrden = $AutoId;
		}
		else{
			$AutoId=1;
		}
		
		$consCups="select cup,nombre,cantidad,tmpcupsordenesmeds.tipofinalidad,tmpcupsordenesmeds.finalidadcup,tmpcupsordenesmeds.formaquirurgica,contratacionsalud.cups.notas,tmpcupsordenesmeds.nota,tmpcupsordenesmeds.justificacion
		from salud.tmpcupsordenesmeds,contratacionsalud.cups
		where tmpcupsordenesmeds.compania='$Compania[0]' and tmpcod='$TMPCOD2' and cedula='$Paciente[1]' and cups.compania='$Compania[0]' and cups.codigo=cup
		order by cup,nombre";
		$resCups=ExQuery($consCups);
		while($filaCups=Exfetch($resCups)){
			$cons=" select tipoformato,formato from historiaclinica.cupsxformatos where compania='$Compania[0]' and cup='$filaCups[0]'";
			//echo $cons;
			$res = ExQuery($cons);
			while($fila=ExFetch($res)){
				$cons2="select laboratorio from historiaclinica.formatos where compania='$Compania[0]' and formato='$fila[1]' and tipoformato='$fila[0]'";
				$res2=ExQuery($cons2);
				//echo "<br>$cons2";			
				if(ExNumRows($res2)>0){			
					$fila2=ExFetch($res2);
					$Lab=$fila2[0];
				}
			}		
			if($Lab!=''){
				$Laboratorio=" and laboratorio='$Lab'";
			}			
			if($filaCups[5]!="-1"){$FQuig1=",formaquirugica"; $FQuig2=",'$filaCups[5]'";}else{$FQuig1="";$FQuig2="";}
			$Procedimiento=$filaCups[1];
			//$Notita=$filaCups[7];
			for($i=1;$i<=$filaCups[2];$i++)
			{
				//if($Suministro=="Externo"){
                                        //$Detalle="$Procedimiento - (Externo) - $Justificacion - $Observaciones";
                                        $Detalle=$Procedimiento." <i>Justificación:</i> ".$filaCups[8]." <i>Nota:</i> ".$filaCups[7];
					//$Detalle="$filaCups[1] - (Externo)";
					$cons2="insert into salud.plantillaprocedimientos 
					(compania,usuario,cedula,fechaini,cup,ambitoreal,finproced,detalle,numservicio,estado,justificacion,observaciones,numprocedimiento,diagnostico
					,causaexterna,tipodx,laboratorio,externo,idescritura,numorden $FQuig1) 
					values 
					('$Compania[0]','$usuario[1]','$Paciente[1]','$ND[year]-$ND[mon]-$ND[mday]','$filaCups[0]','$AmbitoRealiz','$filaCups[4]','$Detalle',$NumServ
					,'AC','$filaCups[8]','$filaCups[7]',$NumProc,'$CodDiagnostico1','$CausaExterna','$TipoDx','$Lab',1,$IdEscritura,$AutoId $FQuig2) returning *";
					//echo "<br><br>".$cons2;
					$res2 = ExQuery($cons2);
                                        $fila2=ExFetchAssoc($res2);
                                        //7nota y 8justificacion
					$cons="insert into salud.ordenesmedicas (compania,fecha,cedula,numservicio,detalle,idescritura,numorden,usuario,tipoorden,estado,acarreo) values 
					('$Compania[0]','$ND[year]-$ND[mon]-$ND[mday] $ND[hours]:$ND[minutes]:$ND[seconds]','$Paciente[1]',$NumServ,'$Detalle',$IdEscritura
					,$AutoId,'$usuario[1]','Procedimiento','AC',1)";
					//echo "<br>".$cons;
					$res = ExQuery($cons);

                                        $consih="SELECT id_historia FROM histoclinicafrms.tbl00004 ORDER BY id_historia DESC LIMIT 1";
                                        $resih = ExQuery($consih);
                                        $filaih=ExFetchAssoc($resih);
                                        $filaih['id_historia']+=1;
                                        
                                        $consulta="INSERT INTO histoclinicafrms.tbl00004 "
                                                . "(ambito, cargo, causaexterna, cedula, cerrado, cmp00002, cmp00004, cmp00006, cmp00008, cmp00012, compania, dx1, dx2, dx3, dx4, dx5, fecha, fechaajuste, finalidadconsult, formato, hora, id_historia, id_historia_origen, idsvital, noliquidacion, numproced, numservicio, padreformato, padretipoformato, tipodx, tipoformato, unidadhosp, usuario, usuarioajuste) "
                                                . "VALUES ( '".$fila2['ambitoreal']."', '".$usuario[3]."', '".$fila2['causaexterna']."', '".$fila2['cedula']."', NULL, '', '', '".$fila2['detalle']."', '', '', '".$fila2['compania']."', '".$fila2['diagnostico']."', '', NULL, NULL, NULL, '".$fila2['fechaini']."', NULL, '', 'NOTAS EVOLUCION', '$ND[hours]:$ND[minutes]:$ND[seconds]', '".$filaih['id_historia']."', NULL, NULL, NULL, '".$fila2['numprocedimiento']."', '".$fila2['numservicio']."', '', '', '".$fila2['tipodx']."', 'HISTORIA CLINICA', '".$Unidad."', '".$fila2['usuario']."', '' );";
                                        $resnoev = ExQuery($consulta);
                                        $filanoev=ExFetch($resnoev);
				//}
				/*else{
					if($Suministro=='Calendario'){			
						$Detalle="$Procedimiento - Ejecutar ";
						$Detalle2="$Procedimiento - Ejecutar ";
						$cons = "Select Fecha,hora from Salud.TMPFechasOMMedPro 
						where Compania='$Compania[0]' and Usuario='$usuario[0]' and CedPaciente='$Paciente[1]' and TMPCOD='$TMPCOD' order by fecha";
						//echo "<br>$cons";
						$res = ExQuery($cons);						
						while($fila = ExFetch($res)){
							$Detalle=$Detalle."\n ,".$fila[0]." ".$fila[1];
							$cons2="insert into salud.plantillaprocedimientos 						
							(compania,usuario,cedula,fechaini,fechafin,cup,ambitoreal,finproced,detalle,numservicio,numprocedimiento,diagnostico																									 							,causaexterna,tipodx,laboratorio,justificacion,observaciones,idescritura,numorden $FQuig1) values 
							('$Compania[0]','$usuario[1]','$Paciente[1]','$fila[0]','$fila[0]','$filaCups[0]','$AmbitoRealiz','$filaCups[4]','$Detalle2 $fila[0]'
							,$NumServ,$NumProc,'$CodDiagnostico1','$CausaExterna','$TipoDx','$Lab','$Justificacion','$Observaciones',$IdEscritura,$AutoId $FQuig2)";
							$res2 = ExQuery($cons2);
							//echo "<br><br>$cons2";
						}		
						//$Detalle=$Detalle." ".$Observaciones;					
						$cons="insert into salud.ordenesmedicas 
						(compania,fecha,cedula,numservicio,detalle,idescritura,numorden,usuario,tipoorden,estado,acarreo) values 
						('$Compania[0]','$ND[year]-$ND[mon]-$ND[mday] $ND[hours]:$ND[minutes]:$ND[seconds]','$Paciente[1]',$NumServ,'$Detalle',$IdEscritura,$AutoId
						,'$usuario[1]','Procedimiento','AC',1)";
						$res = ExQuery($cons);
						//echo "<br>$cons";		
					}
					else{
						$conta=0;	
						if($Dia[1]!=''){
							$Detalle=$Detalle."\n Lunes ".$Hora[1]." ".$Nota[1];$conta++;
						}
						if($Dia[2]!=''){
							if($conta==0){
								$Detalle=$Detalle."\n Martes ".$Hora[2]." ".$Nota[2];$conta++;
							}
							else{
								$Detalle=$Detalle."\n ,Martes ".$Hora[2]." ".$Nota[2];
							}
						}
						if($Dia[3]!=''){
							if($conta==0){
								$Detalle=$Detalle."\n Miercoles ".$Hora[3]." ".$Nota[3];$conta++;
							}
							else{
								$Detalle=$Detalle."\n ,Miercoles ".$Hora[3]." ".$Nota[3];
							}
						}
						if($Dia[4]!=''){
							if($conta==0){
								$Detalle=$Detalle."\n Jueves ".$Hora[4]." ".$Nota[4];$conta++;
							}
							else{
								$Detalle=$Detalle."\n ,Jueves ".$Hora[4]." ".$Nota[4];
							}
						}
						if($Dia[5]!=''){
							if($conta==0){
								$Detalle=$Detalle."\n Viernes ".$Hora[5]." ".$Nota[5];$conta++;
							}
							else{
								$Detalle=$Detalle."\n ,Viernes ".$Hora[5]." ".$Nota[5];
							}
						}
						if($Dia[6]!=''){
							if($conta==0){
								$Detalle=$Detalle."\n Sabado ".$Hora[6]." ".$Nota[6];$conta++;
							}
							else{
								$Detalle=$Detalle."\n ,Sabado ".$Hora[6]." ".$Nota[6];
							}
						}	
						if($Dia[7]!=''){
							if($conta==0){
								$Detalle=$Detalle."\n Domingo ".$Hora[7]." ".$Nota[7];
							}
							else{
								$Detalle=$Detalle."\n ,Domingo ".$Hora[7]." ".$Nota[7];
							}
						}	
						if($Suministro=='Definido'){			
							$Detalle2="$Procedimiento - Ejecutar desde $FechaIni hasta $FechaFin - ".$Detalle;						
							$cons="insert into salud.plantillaprocedimientos 
							(compania,usuario,cedula,fechaini,fechafin,cup,ambitoreal,finproced,detalle,numservicio,numprocedimiento
							 ,diagnostico,causaexterna,tipodx,laboratorio,idescritura,numorden $FQuig1) values 
							('$Compania[0]','$usuario[1]','$Paciente[1]','$FechaIni','$FechaFin','$filaCups[0]','$AmbitoRealiz','$filaCups[4]'
							,'$Detalle2 $Detalle',$NumServ,$NumProc,'$CodDiagnostico1','$CausaExterna','$TipoDx','$Lab',$IdEscritura,$AutoId $FQuig2)";	
							$res = ExQuery($cons);										
							//echo "<br><br>$cons";		
						}
						elseif($Suministro=='Indefinido'){
							$Detalle2="$Procedimiento - ".$Detalle;															
							$cons="insert into salud.plantillaprocedimientos 
							(compania,usuario,cedula,fechaini,cup,ambitoreal,finproced,detalle,numservicio,numprocedimiento,diagnostico
							 ,causaexterna,tipodx,laboratorio,idescritura,numorden $FQuig1) values  
							('$Compania[0]','$usuario[1]','$Paciente[1]','$ND[year]-$ND[mon]-$ND[mday]','$filaCups[0]','$AmbitoRealiz','$filaCups[4]'
							 ,'$Detalle2',$NumServ,$NumProc,'$CodDiagnostico1','$CausaExterna','$TipoDx','$Lab',$IdEscritura,$AutoId $FQuig2)";
							$res = ExQuery($cons);
						}
						$cons="insert into salud.ordenesmedicas (compania,fecha,cedula,numservicio,detalle,idescritura,numorden,usuario,tipoorden,estado,acarreo) 	
						values ('$Compania[0]','$ND[year]-$ND[mon]-$ND[mday] $ND[hours]:$ND[minutes]:$ND[seconds]','$Paciente[1]',$NumServ,'$Detalle2',$IdEscritura
						,$AutoId,'$usuario[1]','Procedimiento','AC',1)";	
						$res = ExQuery($cons);						
					}
				}*/
				$NumProc++;
				$AutoId++;
				$cons="select id from central.correos where compania='$Compania[0]'  order by id desc"; 
				$res=ExQuery($cons); $fila=ExFetch($res); $Id=$fila[0]+1;
				$Msj="Se ha ordenado el procedimiento $filaCups[0] - ".$filaCups[1]." al paciente $Paciente[2] 
				$Paciente[3] $Paciente[4] $Paciente[5]- CC $Paciente[1] 
				($Amb - $Pab) el dia $ND[year]-$ND[mon]-$ND[mday] a las $ND[hours]:$ND[minutes]:$ND[seconds] <br><br>Att=$usuario[0]";			
				$cons2="select usuario from salud.medicos,salud.cargos
				where medicos.compania='$Compania[0]' and medicos.cargo=cargos.cargos and vistobuenojefe=1 and cargos.compania='$Compania[0]'";
				$res2=ExQuery($cons2);
				while($fila2=ExFetch($res2))
				{
					$cons="insert into central.correos (compania,usucrea,fechacrea,usurecive,mensaje,id,asunto) values
					('$Compania[0]','$usuario[1]','$ND[year]-$ND[mon]-$ND[mday] $ND[hours]:$ND[minutes]:$ND[seconds]','$fila2[0]','$Msj',$Id
					,'Orden Procedimientos')";
					//echo $cons;
					//$res=ExQuery($cons);
					$Id++;
				}
			}
		}
		$cons = "Delete from Salud.TMPFechasOMMedPro where Compania='$Compania[0]' and cedpaciente='$Paciente[1]' and Usuario='$usuario[0]' and tmpcod='$TMPCOD'";
		$res = ExQuery($cons);
		$cons = "Delete from Salud.tmpcupsordenesmeds where Compania='$Compania[0]' and cedula='$Paciente[1]' and tmpcod='$TMPCOD2'";
		$res = ExQuery($cons);
		?>	<script language="javascript">
			location.href='NuevaOrdenMedica.php?DatNameSID=$DatNameSID&IdEscritura=<? echo $IdEscritura?>&Incremento=1&DatNameSID=<? echo $DatNameSID?>';
			</script><?
	}
?>	
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<script language="javascript" src="/Funciones.js"></script>
<script language='javascript' src="/calendario/popcalendar.js"></script>
<script language="javascript">
	function AbrirProcedimientos()
	{
		frames.FrameOpener.location.href="Procedimientos.php?DatNameSID=<? echo $DatNameSID?>&TMPCOD2=<? echo $TMPCOD2?>&Formulacion=Programados";
		document.getElementById('FrameOpener').style.position='absolute';
		document.getElementById('FrameOpener').style.top='20px';
		document.getElementById('FrameOpener').style.left='1px';
		document.getElementById('FrameOpener').style.display='';
		document.getElementById('FrameOpener').style.width='100%';
		document.getElementById('FrameOpener').style.height='450px';
		
	}
    function Ligar_Paquetes(Codigo,NoContrato,Entidad,Contrato)
	{
		frames.FrameOpener.location.href="CargarPaquetes.php?DatNameSID=<? echo $DatNameSID?>&Procedimiento=1&NumServicio=<?echo $NumServ?>&NoOrden=<?echo $NoOrden?>&IdEscritura=<?echo $IdEscritura?>&Tipo=Medicamentos&Codigo="+Codigo+"&NoContrato="+NoContrato+"&Entidad="+Entidad+"&Contrato="+Contrato;
		document.getElementById('FrameOpener').style.position='absolute';
		document.getElementById('FrameOpener').style.top='20px';
		document.getElementById('FrameOpener').style.left='30px';
		document.getElementById('FrameOpener').style.display='';
		document.getElementById('FrameOpener').style.width='600px';
		document.getElementById('FrameOpener').style.height='450px';
    }
function raton(Chk,e,Dia,Ced,TMPCOD) { 
	var ind = Chk.value;
	if(Chk.checked == true){
		for (i=0;i<document.FORMA.elements.length;i++){
			if(document.FORMA.elements[i].type == "checkbox"){
				document.FORMA.elements[i].disabled = true;
			} 
		}
		x = e.clientX; 
		y = e.clientY; 	
		st = document.body.scrollTop;
		frames.FrameOpener2.location.href="AsigNotaDia.php?DatNameSID=<? echo $DatNameSID?>&Dia="+Dia+"&Ced="+Ced+"&TMPCOD="+TMPCOD;
		document.getElementById('FrameOpener2').style.position='absolute';
		document.getElementById('FrameOpener2').style.top=y-100+st;
		document.getElementById('FrameOpener2').style.left=x;
		document.getElementById('FrameOpener2').style.display='';
		document.getElementById('FrameOpener2').style.width='200';
		document.getElementById('FrameOpener2').style.height='170';
	}
	else{	
		document.getElementById('Hora['+Dia+']').value="";
		document.getElementById('Nota['+Dia+']').value="";
	}
	
} 
function Inserta(){
	if(document.FORMA.FechaIng.value>document.FORMA.Fecha.value){
		alert("La fecha ingresada no es valida!!!");
	}
	else{
		document.FORMA.Anadir.value=1;
		document.FORMA.submit();
	}
}
function Validar(){
	/*if(document.FORMA.NoFormato.value==1&&document.FORMA.NoValidar.value!="1"){
		alert("Este no puede ser ordenado ya que no se encuentra el formato para justificacion de Medicamentos No POS"); 						
	}
	else{
	}*/
		if(document.FORMA.CodDiagnostico1.value==""||document.FORMA.NomDiagnostico1.value==""){
			alert("Debe seleccionar el diagnostico!!!");
		}
                else{
                    document.FORMA.Guardar.value=1;
                    document.FORMA.submit();
                }
		/*else
		{
			if(document.FORMA.Radios.value=="Externo"){
				if(document.FORMA.Justificacion.value==""){
					alert("Debe ingresar la justificacion!!!");return false;
				}
				else{
					document.FORMA.Guardar.value=1;
					document.FORMA.submit();
				}
			}
			else{	
				if(document.FORMA.Radios.value!="Calendario"){
					var ban=0;
					for (var i=0;i < document.forms["FORMA"].elements.length;i++) 
					{ 
						var elemento = document.forms[0].elements[i]; 
						if (elemento.type == "checkbox") 
						{ 
							if(elemento.checked){
								ban=1
							}
						} 	
					} 
					if(ban==0){
						alert("Debe seleccionar almenos un dia!!!");return false;
					}
					else{	
						if(document.FORMA.Radios.value=="Definido"){
							if(document.FORMA.FechaIni.value==''||document.FORMA.FechaFin.value==''){
								alert("Debe seleccionar la fecha de inicio y la fecha final!!!");
							}else{
								if(document.FORMA.FechaIni.value<document.FORMA.FechaComp.value){		
									alert("La fecha inicial seleccionada es invalida!!!");
								}
								else{
									if(document.FORMA.FechaIni.value>document.FORMA.FechaFin.value){
										alert("La fecha inicial es menor a la fecha final");
									}	
									else{
										document.FORMA.Guardar.value=1;
										document.FORMA.submit();
									}
								}
							}	
						}
						else{
							document.FORMA.Guardar.value=1;
							document.FORMA.submit();				
						}
					}
				}
				else{		
			<?		$cons = "Select * from Salud.TMPFechasOMMedPro where Compania='$Compania[0]' and Usuario='$usuario[0]' and CedPaciente='$Paciente[1]' and TMPCOD='$TMPCOD'";
					$res = ExQuery($cons);
					if(ExNumRows($res)>0){?>
						document.FORMA.Guardar.value=1;
						document.FORMA.submit();
				<?	}
					else{?>
						alert("No seleccionado ninguna fecha!!!");
			<?		}	
	?>			}
			}	
		}*/
}
function ValidaDiagnostico2(Objeto1,Objeto2)
	{		
		frames.FrameOpener2.location.href="ValidaDiagnostico2.php?DatNameSID=<? echo $DatNameSID?>&TMPCOD2=<? echo $TMPCOD2?>";
		document.getElementById('FrameOpener2').style.position='absolute';
		document.getElementById('FrameOpener2').style.top='60px';
		document.getElementById('FrameOpener2').style.left='60px';
		document.getElementById('FrameOpener2').style.display='';
		document.getElementById('FrameOpener2').style.width='800px';
		document.getElementById('FrameOpener2').style.height='400px';
	}
</script>	
</head>

<body background="/Imgs/Fondo.jpg">
<form name="FORMA" method="post">
<input type="hidden" name="CUPNoPos" value="<? echo $CUPNoPos?>">
<input type="hidden" name="TipoFormato" value="<? echo $TipoFormato?>">
<input type="hidden" name="Formato" value="<? echo $Formato?>">
<input type="hidden" name="AbrirForm" value="<? echo $AbrirForm?>">
<table style='font : normal normal small-caps 12px Tahoma;' border="1" bordercolor="#e5e5e5" cellpadding="2" align="center"> 

<tr>
<td colspan="7" align="center">
<button onclick="AbrirProcedimientos()" value="Escoger Procedimiento" style="width:200px;" name="Escoger Procedimiento"><img src="/Imgs/HistoriaClinica/bigfolder.png"><br>Escoger Procedimiento</button>

	</td>
</tr><?php
$cons="select finalidad,codigo,tipo from salud.finalidadesact order by finalidad";	
$res=ExQuery($cons);
while($fila=ExFetch($res))
{
	$Findalidaes[$fila[2]][$fila[1]]=$fila[0];
}
$cons="select codigo,forma from salud.formarquirurgico order by forma";	
$res=ExQuery($cons);
while($fila=ExFetch($res))
{
	$FormasQuirurguicas[$fila[0]]=$fila[1];
}

$cons="select cup,nombre,cantidad,tmpcupsordenesmeds.tipofinalidad,tmpcupsordenesmeds.finalidadcup,tmpcupsordenesmeds.formaquirurgica,contratacionsalud.cups.notas,tmpcupsordenesmeds.nota,tmpcupsordenesmeds.justificacion
from salud.tmpcupsordenesmeds,contratacionsalud.cups
where tmpcupsordenesmeds.compania='$Compania[0]' and tmpcod='$TMPCOD2' and cedula='$Paciente[1]' and cups.compania='$Compania[0]' and cups.codigo=cup
order by cup,nombre";
$res=ExQuery($cons);
//echo $cons;
if(ExNumRows($res)>0)
{?>
	<tr>
    	<td colspan="7">
        	<table style='font : normal normal small-caps 12px Tahoma;' border="1" bordercolor="#e5e5e5" cellpadding="2" align="center"> 
            	<tr align="center" bgcolor="#e5e5e5" style="font-weight:bold">
                    <td>Codigo</td><td>Nombre</td>
                    <td style="display:none;">Cantidad</td>
                    <td style="display:none;">Finalidad</td>
                    <td style="display:none;">Resolución</td>
                    <td style="display:none;"></td>
                        <td style="display:none;">Forma Acto Quirurgico</td><td>Nota</td><td>Justificación</td><td>Operaciones</td></tr>
			<?php	while($fila=ExFetch($res))
                {?>
                    <tr onMouseOver="this.bgColor='#AAD4FF'" onMouseOut="this.bgColor=''" style="cursor:hand" align="center">	
                        <td><? echo $fila[0]?></td>
                        <td><? echo $fila[1]?></td>
                        <td style="display:none;"><? echo $fila[2]?></td>
                        <td style="display:none;"><? echo $Findalidaes[$fila[3]][$fila[4]]?></td>
                  	<td style="display:none;"><? echo $fila[6];?></td>
               	      <?php	if($fila[5]=="-1"){echo "<td style='display:none;'>NO APLICA</td>";}else{echo "<td style='display:none;'>".$FormasQuirurguicas[$fila[5]]."</td>";}?>
                        <td><? echo $fila[7];?></td>
                        <td><? echo $fila[8];?></td>
                    	<td>
                        	<button title="Descartar" onClick="if(confirm('Esta seguro de descartar este item?')){document.FORMA.ElimCUP.value=<? echo $fila[0]?>;document.FORMA.submit();}">
                            	<img src="/Imgs/b_drop.png"></button>
                        </td>
                    </tr>
            <?	}?>
            </table>
        </td>
	</tr>
	<tr>
    	<td colspan="7" align="center" style="font-weight:bold" bgcolor="#e5e5e5">Diagnostico</td>
    </tr>
    <tr>
        <?php            
            if(!$CodDiagnostico1){
                $consp="select dxserv,numservicio from salud.servicios where servicios.estado='AC' and servicios.cedula='$Paciente[1]'";
                $resp=ExQuery($consp);
                $filap=ExFetch($resp);
				
				$cons0004="select dx1 from histoclinicafrms.tbl00004 where numservicio=$filap[1] and cedula='$Paciente[1]' ORDER BY fecha,hora desc limit 1";
				$res0004=ExQuery($cons0004);
				$fila0004=ExFetch($res0004);
				
				if($fila0004[0])
                    $consDx="select diagnostico,codigo from salud.cie where codigo='$fila0004[0]'";
                else
                    $consDx="select diagnostico,codigo from salud.cie where codigo='$filap[0]'";
                
                $resDx=ExQuery($consDx);
                $filaDx=ExFetch($resDx);
                
                $CodDiagnostico1 = $filaDx[1];
                $NomDiagnostico1 = $filaDx[0];
            }
        ?>
        
    	<td align="left" colspan="7" style="font-weight:bold">Codigo <input style="width:100" type="text" readonly name="CodDiagnostico1" 
        	onFocus="ValidaDiagnostico2(this,NomDiagnostico1)"  onKeyUp="ValidaDiagnostico2(this,NomDiagnostico1);xLetra(this)" onKeyDown="xLetra(this)" value="<? echo $CodDiagnostico1?>">
    Nombre <input type="text" style="width:435px" name="NomDiagnostico1" readonly 
        	onFocus="ValidaDiagnostico2(CodDiagnostico1,this)" onKeyUp="ValidaDiagnostico2(CodDiagnostico1,this);xLetra(this)" onKeyDown="ExLetra(this)" value="<? echo $NomDiagnostico1?>">
        </td>
    </tr>
    
	<tr>
    <?php	$cons="select tipodiagnost,codigo from salud.tiposdiagnostico where compania='$Compania[0]'";
		$res=ExQuery($cons);?>
	    <td colspan="7" align="left" style="font-weight:bold">Tipo de Diagnostico 
    	    <select name="TipoDx"><?php
				while($fila=ExFetch($res)){
		    	    if($TipoDx==$fila[1]){
    	    			echo "<option value='$fila[1]' selected>$fila[0]</option>";
	        	  	}
					else{
						echo "<option value='$fila[1]'>$fila[0]</option>";
					}			
				}
    	?>	</select>
       	</td>
        
    </tr>	
		<tr>
                    <td colspan="7" align="center"><input type="button" value="Guardar" onClick="Validar()"><input type="submit" value="Cancelar" name="Salir">
                    </td>
                </tr><?php
	}

if(!$Suministro){?>
	<tr>
    	<td colspan="7" align="center">
        	<input type="button" value="Cancelar" onClick="location.href='NuevaOrdenMedica.php?DatNameSID=<? echo $DatNameSID?>&IdEscritura=<? echo $IdEscritura?>'">	
       	</td>
 	</tr><?
}?>
</table>
<input type="hidden" name="ElimCUP" value="">
<input type="hidden" name="TMPCOD" value="<? echo $TMPCOD?>">
<input type="hidden" name="TMPCOD2" value="<? echo $TMPCOD2?>">
<input type="hidden" name="Limpiar" value="<? echo $Limpiar?>">
<input type="hidden" name="Guardar" value="">
<input type="hidden" name="IdEscritura" value="<? echo $IdEscritura?>">
<input type="hidden" name="MalFecha" value="<? echo $MalFecha?>">
<input type="hidden" name="DatNameSID" value="<? echo $DatNameSID?>">
<input type="hidden" name="NoFormato">
<input type="hidden" name="AuxObserv" value="<? echo $AuxObserv?>"><? echo $fila[6]?>
<input type="hidden" name="Suministro" id="Suministro" value="Externo">
<input type="hidden" name="NoValidar">

<script language="javascript">
if(document.FORMA.MalFecha.value==1){
	alert("La fecha seleccionada no es valida");
}
//if(document.FORMA.POS.value!="1"&&document.FORMA.CUPNoPos.value!=1){document.FORMA.NoFormato.value="1";}else{document.FORMA.NoFormato.value="";}
</script>

</form>	
<iframe scrolling="no" id="FrameOpener" name="FrameOpener" style="display:none" frameborder="0" height="1" style="border:#e5e5e5 ridge"></iframe> 
<iframe scrolling="no" id="FrameOpener2" name="FrameOpene2" style="display:none" frameborder="0" height="1" style="border:#e5e5e5 ridge"></iframe> 
</body>
</html>
