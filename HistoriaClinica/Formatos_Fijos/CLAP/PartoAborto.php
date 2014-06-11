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
	if(!$NumServicio)
	{
		echo "<center><font face='Tahoma' color='#0066FF' size='+2' ><b>La Paciente no tiene Servicios Activos!!! </b></font></center><br>";
		exit;	
	}
	$SexoPaciente=$Paciente[24];
	if($SexoPaciente=="M")
	{
		echo "<center><font face='Tahoma' color='#0066FF' size='+2' ><b>La Historia Clinica Materno-Perinatal Aplica Solo Para Mujeres!!!</b></font></center>";		
		exit;	
	}
	$cons="select idclap,identificacion,fechacrea,Preantecedentes,Antecedentes,PartoAborto from historiaclinica.claps 
	where Compania='$Compania[0]' and Identificacion='$Paciente[1]'	and estado='AC' order by idclap desc";
	//echo $cons;
	$res=ExQuery($cons);
	$fila=ExFetch($res);
	$IdClap=$fila[0];
	if($IdClap)
	{
	//--
	$cons1="select idcontrol from historiaclinica.clapcontroles where compania='$Compania[0]' and idclap=$IdClap and Identificacion='$Paciente[1]'";
	$res1=ExQuery($cons1);
	$NControles=ExNumRows($res1);	
	}
	//--
	if(!$fila)
	{?>
        	<center>
			<font face='Tahoma' color='#0066FF' size='+1' ><b>
            En este momento No Existe Hoja de Historia Materno-Perinatal (CLAP) Activa!!!<br>   
            <script language="javascript">location.href="Antecedentes.php?DatNameSID=<? echo $DatNameSID?>";</script>         
		<?
			exit;     
	}
	elseif(!$fila[4])
	{
		echo "<center><font face='Tahoma' color='#0066FF' size='+2' ><b>No se han Registrado los datos de los Antecedentes de la Paciente para continuar con esta Hoja!!! </b></font><br>";	
		exit;
	}
	elseif($NControles==0)
	{
		echo "<center><font face='Tahoma' color='#0066FF' size='+2' ><b>No se han Registrado datos de controles para continuar con esta Hoja!!! </b></font><br>";	
		exit;	
	}
	else
	{
		$LlenarPartoAborto=1;
	}
	if($Guardar)
	{
		if($FechaIngreso=="aaaa-mm-dd"){$FechaIngreso="1111-11-11";}
		if($FechaRupturaMembranas=="aaaa-mm-dd"){$FechaRupturaMembranas="1111-11-11";}		
		if($FechaNacimiento=="aaaa-mm-dd"){$FechaNacimiento="1111-11-11";}	
		//-----
		if(!$ConsultasPrenatalesSI){$ConsultasPrenatalesSI="NULL";}	
		if(!$HoraRupturaMembranas){$HoraRupturaMembranas="NULL";}
		if(!$MinutoRupturaMembranas){$MinutoRupturaMembranas="NULL";}
		if(!$SemanaRupturaMembranas){$SemanaRupturaMembranas="NULL";}
		if(!$HorasRupturaParto){$HorasRupturaParto="NULL";}
		if(!$EdadGestSemanas){$EdadGestSemanas="NULL";}
		if(!$EdadGestDias){$EdadGestDias="NULL";}
		if(!$HoraNacimiento){$HoraNacimiento="NULL";}
		if(!$MinutoNacimiento){$MinutoNacimiento="NULL";}
		if(!$MultipleOrden){$MultipleOrden="NULL";}
		if(!$MultipleFetos){$MultipleFetos="NULL";}
		if(!$DesgarrosGrado){$DesgarrosGrado="NULL";}
		//--
		$cons="UPDATE historiaclinica.claps SET partoaborto='1', papartoaborto='$PartoAborto', pahospitalizadaemb='$HospitalizEmbarazo', 
		padiashospitalizada='$DiasHospitalizada', pacorticoidesant='$CorticoidesAntenatales', pasemanainicioca=$ConsultasPrenatalesSI, painiciotp='$InicioTP',
		parupturamembranas='$RupturaMembranas', pafecharupturamembranas='$FechaRupturaMembranas', pahorarupturamembranas=$HoraRupturaMembranas,
		paminutorupturamembranas=$MinutoRupturaMembranas, parupturamembranasmen37sem='$RuptMembranaMen37Sem', parupturamembranasmay37hs='$RuptMembranaMay37Hs',
		parupturamembranasmay38gc='$RuptMembranaTemp', pasemanarupturamem=$SemanaRupturaMembranas, pahorasrupturaparto=$HorasRupturaParto, 
		paedadgestacionalsemanas=$EdadGestSemanas,paedadgestacionaldias=$EdadGestDias, paedadgestacionalxfum='$EdadGestxFUM', paedadgestacionalxeco='$EdadGestxECO',
		papresentacion='$Presentacion', paacompanantetp='$AcompanianteTP', pafechaingreso='$FechaIngreso', pacarne='$Carne', paconsultasprenatales='$ConsultasPrenatales', 
		panacimiento='$Nacimiento', pahoranacimiento=$HoraNacimiento,paminutonacimiento=$MinutoNacimiento, pafechanacimiento='$FechaNacimiento', 
		pamultipleorden=$MultipleOrden, pamultiplefetos=$MultipleFetos, paterminacion='$Terminacion', paindicacioninduccionparto='$IndicacionInduccionParto', 					
		painduccion='$Induccion', paoperatorio='$Operatorio', paposicionparto='$PosicionParto', paepisiotomia='$Episiotomia', padesgarros='$Desgarros', 
		padesgarrosgrado=$DesgarrosGrado, paoxitocitosalumbramiento='$OxitocicosAlumbramiento', paplacentacompleta='$PlacentaCompleta', 
		paplacentaretenida='$PlacentaRetenida', paligaduracordon='$LigaduraCordon', paocitocicostdp='$OcitocicosTdP', paanalgeciaepidural='$AnalgeciaEpidural',
		paantibioticos='$Antibioticos', paanesteciaregion='$AnesteciaRegional', paanesteciagral='$AnesteciaGral', pasulfatomg='$SulfatoMg', patransfusion='$Transfusion',
		paotros='$OtrosMedi', paespecificarotros='$EspecificarOtros', pahizopartograma='$HizoPartograma', panotas1='$Notas1', panotas2='$Notas2',panotas3='$Notas3',
		panotas4='$Notas4', panotas5='$Notas5', paningunaenfermedad='$EnfermedadesNinguna', pahtacronica='$HTACronica', pahtagestacional='$HTAGestacional',
		painfeccionurinaria='$InfeccionUrinaria', paamenazapartopreter='$AmenazaPartoP',pahemorragia1trim='$Hemorragia1erTrim', pahemorragia2trim='$Hemorragia2doTrim',
		papreclampsia='$EnfermedadPreclampsia', paeclampsia='$EnfermedadEclampsia', parciu='$RCIU', parupturapremembranas='$RupturaPremMembranas', 
		pahemorragia3trim='$Hemorragia3erTrim',	pahemorragiapostparto='$HemorragiaPostparto',pacardiopatia_nefropatia='$EnfermedadCardiopatia_Nefropatia', 
		padiabetes='$EnfermedadDiabetes', paanemia='$EnfermedadAnemia', pacorioamnionitis='$CorioAmnionitis', paotrasgraves='$OtrasGraves', 
		painfeccionpuerperal='$InfeccionPuerperal', pabacteriuria='$Bacteriuria', pacodigoenfermedad1='$CodigoEnfermedad1', pacodigoenfermedad2='$CodigoEnfermedad2', 
		pacodigoenfermedad3='$CodigoEnfermedad3', paenfermedadesnosehizo='$BacteriuriaNSH'  WHERE Compania='$Compania[0]' and IdClap=$IdClap 
		and Identificacion='$Paciente[1]' and Estado='AC'";
		$res=ExQuery($cons);
		if(!ExError($res))
		{
			?>
			<script language="javascript">
			alert("Los Datos se han guardado correctamente!!!");
			</script>
			<?		
		}
		else
		{
			?>
			<script language="javascript">
			alert("No se pudo guardar la hoja de Parto/Aborto, es posible que exista un error!!!");
			</script>
			<?	
		}
		if($ConsultasPrenatalesSI=="NULL"){$ConsultasPrenatalesSI="";}	
		if($HoraRupturaMembranas=="NULL"){$HoraRupturaMembranas="";}
		if($MinutoRupturaMembranas=="NULL"){$MinutoRupturaMembranas="";}
		if($SemanaRupturaMembranas=="NULL"){$SemanaRupturaMembranas="";}
		if($HorasRupturaParto=="NULL"){$HorasRupturaParto="";}
		if($EdadGestSemanas=="NULL"){$EdadGestSemanas="";}
		if($EdadGestDias=="NULL"){$EdadGestDias="";}
		if($HoraNacimiento=="NULL"){$HoraNacimiento="";}
		if($MinutoNacimiento=="NULL"){$MinutoNacimiento="";}
		if($MultipleOrden=="NULL"){$MultipleOrden="";}
		if($MultipleFetos=="NULL"){$MultipleFetos="";}
		if($DesgarrosGrado=="NULL"){$DesgarrosGrado="";}
	}
	if($NumServicio&&$IdClap)
	{
		$cons="SELECT papartoaborto, pahospitalizadaemb, padiashospitalizada, pacorticoidesant, pasemanainicioca, painiciotp, parupturamembranas, pafecharupturamembranas,
		pahorarupturamembranas, paminutorupturamembranas, parupturamembranasmen37sem, parupturamembranasmay37hs, parupturamembranasmay38gc, pasemanarupturamem,
		pahorasrupturaparto, paedadgestacionalsemanas, paedadgestacionaldias, paedadgestacionalxfum, paedadgestacionalxeco, papresentacion, paacompanantetp, 
		pafechaingreso, pacarne, paconsultasprenatales, panacimiento, pahoranacimiento, paminutonacimiento, pafechanacimiento, pamultipleorden, pamultiplefetos, 
		paterminacion, paindicacioninduccionparto, painduccion, paoperatorio, paposicionparto, paepisiotomia, padesgarros, padesgarrosgrado, paoxitocitosalumbramiento,
		paplacentacompleta, paplacentaretenida, paligaduracordon, paocitocicostdp, paanalgeciaepidural, paantibioticos, paanesteciaregion, paanesteciagral, pasulfatomg,
		patransfusion, paotros, paespecificarotros, pahizopartograma, panotas1, panotas2, panotas3, panotas4, panotas5, paningunaenfermedad, pahtacronica, 
		pahtagestacional, painfeccionurinaria, paamenazapartopreter, pahemorragia1trim, pahemorragia2trim, papreclampsia, paeclampsia, parciu, parupturapremembranas,
		pahemorragia3trim, pahemorragiapostparto, pacardiopatia_nefropatia, padiabetes, paanemia, pacorioamnionitis, paotrasgraves, painfeccionpuerperal, pabacteriuria,
		pacodigoenfermedad1, pacodigoenfermedad2, pacodigoenfermedad3, paenfermedadesnosehizo FROM historiaclinica.claps WHERE Compania='$Compania[0]' 
		and IdClap=$IdClap and Identificacion='$Paciente[1]' and Estado='AC' and PartoAborto='1'";
		$res=ExQuery($cons);
		$fila=ExFetchArray($res);
		if(!$PartoAborto){$PartoAborto=$fila['papartoaborto'];}
		if(!$HospitalizEmbarazo){$HospitalizEmbarazo=$fila['pahospitalizadaemb'];}
		if(!$DiasHospitalizada){$DiasHospitalizada=$fila['padiashospitalizada'];}
		if(!$CorticoidesAntenatales){$CorticoidesAntenatales=$fila['pacorticoidesant'];}
		if(!$ConsultasPrenatalesSI){$ConsultasPrenatalesSI=$fila['pasemanainicioca'];}
		if(!$InicioTP){$InicioTP=$fila['painiciotp'];}
		if(!$RupturaMembranas){$RupturaMembranas=$fila['parupturamembranas'];}
		if(!$FechaRupturaMembranas){$FechaRupturaMembranas=$fila['pafecharupturamembranas'];}
		if(!$HoraRupturaMembranas){$HoraRupturaMembranas=$fila['pahorarupturamembranas'];}
		if(!$MinutoRupturaMembranas){$MinutoRupturaMembranas=$fila['paminutorupturamembranas'];}
		if(!$RuptMembranaMen37Sem){$RuptMembranaMen37Sem=$fila['parupturamembranasmen37sem'];}
		if(!$RuptMembranaMay37Hs){$RuptMembranaMay37Hs=$fila['parupturamembranasmay37hs'];}		
		if(!$RuptMembranaTemp){$RuptMembranaTemp=$fila['parupturamembranasmay38gc'];}
		if(!$SemanaRupturaMembranas){$SemanaRupturaMembranas=$fila['pasemanarupturamem'];}
		if(!$HorasRupturaParto){$HorasRupturaParto=$fila['pahorasrupturaparto'];}
		if(!$EdadGestSemanas){$EdadGestSemanas=$fila['paedadgestacionalsemanas'];}
		if(!$EdadGestDias){$EdadGestDias=$fila['paedadgestacionaldias'];}
		if(!$EdadGestxFUM){$EdadGestxFUM=$fila['paedadgestacionalxfum'];}
		if(!$EdadGestxECO){$EdadGestxECO=$fila['paedadgestacionalxeco'];}
		if(!$Presentacion){$Presentacion=$fila['papresentacion'];}
		if(!$AcompanianteTP){$AcompanianteTP=$fila['paacompanantetp'];}
		if(!$FechaIngreso){$FechaIngreso=$fila['pafechaingreso'];}
		if(!$Carne){$Carne=$fila['pacarne'];}
		if(!$ConsultasPrenatales){$ConsultasPrenatales=$fila['paconsultasprenatales'];}
		if(!$Nacimiento){$Nacimiento=$fila['panacimiento'];}
		if(!$HoraNacimiento){$HoraNacimiento=$fila['pahoranacimiento'];}
		if(!$MinutoNacimiento){$MinutoNacimiento=$fila['paminutonacimiento'];}		
		if(!$FechaNacimiento){$FechaNacimiento=$fila['pafechanacimiento'];}
		if(!$MultipleOrden){$MultipleOrden=$fila['pamultipleorden'];}
		if(!$MultipleFetos){$MultipleFetos=$fila['pamultiplefetos'];}
		if(!$Terminacion){$Terminacion=$fila['paterminacion'];}
		if(!$IndicacionInduccionParto){$IndicacionInduccionParto=$fila['paindicacioninduccionparto'];}
		if(!$Induccion){$Induccion=$fila['painduccion'];}
		if(!$Operatorio){$Operatorio=$fila['paoperatorio'];}
		if(!$PosicionParto){$PosicionParto=$fila['paposicionparto'];}
		if(!$Episiotomia){$Episiotomia=$fila['paepisiotomia'];}
		if(!$Desgarros){$Desgarros=$fila['padesgarros'];}
		if(!$DesgarrosGrado){$DesgarrosGrado=$fila['padesgarrosgrado'];}
		if(!$OxitocicosAlumbramiento){$OxitocicosAlumbramiento=$fila['paoxitocitosalumbramiento'];}
		if(!$PlacentaCompleta){$PlacentaCompleta=$fila['paplacentacompleta'];}
		if(!$PlacentaRetenida){$PlacentaRetenida=$fila['paplacentaretenida'];}		
		if(!$LigaduraCordon){$LigaduraCordon=$fila['paligaduracordon'];}
		if(!$OcitocicosTdP){$OcitocicosTdP=$fila['paocitocicostdp'];}
		if(!$AnalgeciaEpidural){$AnalgeciaEpidural=$fila['paanalgeciaepidural'];}
		if(!$Antibioticos){$Antibioticos=$fila['paantibioticos'];}
		if(!$AnesteciaRegional){$AnesteciaRegional=$fila['paanesteciaregion'];}
		if(!$AnesteciaGral){$AnesteciaGral=$fila['paanesteciagral'];}
		if(!$SulfatoMg){$SulfatoMg=$fila['pasulfatomg'];}
		if(!$Transfusion){$Transfusion=$fila['patransfusion'];}
		if(!$OtrosMedi){$OtrosMedi=$fila['paotros'];}
		if(!$EspecificarOtros){$EspecificarOtros=$fila['paespecificarotros'];}
		if(!$HizoPartograma){$HizoPartograma=$fila['pahizopartograma'];}
		if(!$Notas1){$Notas1=$fila['panotas1'];}
		if(!$Notas2){$Notas2=$fila['panotas2'];}
		if(!$Notas3){$Notas3=$fila['panotas3'];}
		if(!$Notas4){$Notas4=$fila['panotas4'];}
		if(!$Notas5){$Notas5=$fila['panotas5'];}
		if(!$EnfermedadesNinguna){$EnfermedadesNinguna=$fila['paningunaenfermedad'];}
		if(!$HTACronica){$HTACronica=$fila['pahtacronica'];}
		if(!$HTAGestacional){$HTAGestacional=$fila['pahtagestacional'];}		
		if(!$InfeccionUrinaria){$InfeccionUrinaria=$fila['painfeccionurinaria'];}
		if(!$AmenazaPartoP){$AmenazaPartoP=$fila['paamenazapartopreter'];}
		if(!$Hemorragia1erTrim){$Hemorragia1erTrim=$fila['pahemorragia1trim'];}
		if(!$Hemorragia2doTrim){$Hemorragia2doTrim=$fila['pahemorragia2trim'];}
		if(!$EnfermedadPreclampsia){$EnfermedadPreclampsia=$fila['papreclampsia'];}
		if(!$EnfermedadEclampsia){$EnfermedadEclampsia=$fila['paeclampsia'];}
		if(!$RCIU){$RCIU=$fila['parciu'];}
		if(!$RupturaPremMembranas){$RupturaPremMembranas=$fila['parupturapremembranas'];}
		if(!$Hemorragia3erTrim){$Hemorragia3erTrim=$fila['pahemorragia3trim'];}
		if(!$HemorragiaPostparto){$HemorragiaPostparto=$fila['pahemorragiapostparto'];}
		if(!$EnfermedadCardiopatia_Nefropatia){$EnfermedadCardiopatia_Nefropatia=$fila['pacardiopatia_nefropatia'];}
		if(!$EnfermedadDiabetes){$EnfermedadDiabetes=$fila['padiabetes'];}
		if(!$EnfermedadAnemia){$EnfermedadAnemia=$fila['paanemia'];}
		if(!$CorioAmnionitis){$CorioAmnionitis=$fila['pacorioamnionitis'];}
		if(!$OtrasGraves){$OtrasGraves=$fila['paotrasgraves'];}
		if(!$InfeccionPuerperal){$InfeccionPuerperal=$fila['painfeccionpuerperal'];}
		if(!$Bacteriuria){$Bacteriuria=$fila['pabacteriuria'];}
		if(!$CodigoEnfermedad1){$CodigoEnfermedad1=$fila['pacodigoenfermedad1'];}
		if(!$CodigoEnfermedad2){$CodigoEnfermedad2=$fila['pacodigoenfermedad2'];}
		if(!$CodigoEnfermedad3){$CodigoEnfermedad3=$fila['pacodigoenfermedad3'];}
		if(!$BacteriuriaNSH){$BacteriuriaNSH=$fila['paenfermedadesnosehizo'];}
	}
	if(!$FechaIngreso){$FechaIngreso="aaaa-mm-dd";}
	if($FechaIngreso=="1111-11-11"){$FechaIngreso="aaaa-mm-dd";}
	if(!$FechaRupturaMembranas){$FechaRupturaMembranas="aaaa-mm-dd";}
	if($FechaRupturaMembranas=="1111-11-11"){$FechaRupturaMembranas="aaaa-mm-dd";}
	if(!$FechaNacimiento){$FechaNacimiento="aaaa-mm-dd";}
	if($FechaNacimiento=="1111-11-11"){$FechaNacimiento="aaaa-mm-dd";}	
	
	/*if(!$NControles)
	{
		$cons="SELECT idcontrol FROM historiaclinica.clapcontroles where Compania='$Compania[0]' and IdClap=$IdClap and Identificacion='$Paciente[1]' 
		and Estado='AN'";
		$res=ExQuery($cons);
		$NControles=ExNumRows($res);
	}*/
	if(!$ConsutasPrenatales){$ConsultasPrenatales=$NControles;}
	
?>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<style type="text/css"> body { background-color: transparent } </style>
<script language="javascript" src="/Funciones.js"></script>
<script language='javascript' src="/calendario/popcalendar.js"></script>
<script language="javascript">
function Validar()
{
	var frm = document.getElementById("FORMA");
	crad=0;
	cotros=0;
	NomAnt="";
	for (i=0;i<frm.elements.length;i++)
	{		
		pasa=false;
		if(frm.elements[i].type=="radio")
		{											
			if(NomAnt==frm.elements[i].name)
			{				
				crad+=document.getElementsByName(frm.elements[i].name).length-1;
				i+=document.getElementsByName(frm.elements[i].name).length-2;
				pasa=true;
				//alert("NomAnt: "+NomAnt+" -- NombreAct: "+frm.elements[i].name+" -- Iguales");
			}
			else
			{
				for (j=0;j<document.getElementsByName(frm.elements[i].name).length;j++)
				{
					//alert("veamos "+document.getElementsByName(frm.elements[i].name)[j].checked);
					if(document.getElementsByName(frm.elements[i].name)[j].checked)
					{						
						pasa=true;											
						break;
					}
				}			
				//i=parseInt(i)+parseInt(document.getElementsByName(frm.elements[i].name).length);
				crad++;
				//alert("NomAnt: "+NomAnt+" -- NombreAct: "+frm.elements[i].name+" -- Diferentes");	
				NomAnt=frm.elements[i].name;
			}										
			if(!pasa){alert("Por favor escoja una opcion para "+frm.elements[i].title);frm.elements[i].focus();break;}		
		}
		else if(frm.elements[i].type=="text")
		{
			/*if(frm.elements[i].name=="FinEmbarazoAnterior"&&frm.elements[i].value==""){alert("Por favor ingrese la Fecha fin embarazo anterior!!!");break;}			
			if(frm.elements[i].name=="Talla"&&frm.elements[i].value==""){alert("Por favor ingrese la Talla!!!");break;}
			if(frm.elements[i].name=="PesoAnterior"&&frm.elements[i].value==""){alert("Por favor ingrese el peso anterior!!!");break;}
			if(frm.elements[i].name=="FUM"&&frm.elements[i].value==""){alert("Por favor ingrese la Fecha FUM!!!");break;}
			if(frm.elements[i].name=="FPP"&&frm.elements[i].value==""){alert("Por favor ingrese la Fecha FPP!!!");break;}			
			//if(frm.elements[i].name=="PesoAnterior"&&frm.elements[i].value==""){alert("Por favor ingrese el peso anterior!!!");break;}*/			
			/*if(frm.elements[i].name!="Notas2"&&frm.elements[i].name!="Notas3"&&frm.elements[i].name!="Notas4"&&frm.elements[i].name!="Notas5")
			{
				if(frm.elements[i].value==""||frm.elements[i].value=="aaaa-mm-dd"||frm.elements[i].value=="hora"||frm.elements[i].value=="min"){alert("Por favor ingrese el valor correspondiente a "+frm.elements[i].title);frm.elements[i].focus();break;}
			}*/
			if(frm.elements[i].name=="DiasHospitalizada"&&frm.elements[i].value==""){alert("Por favor ingrese el numero de dias que estuvo hospitalizada!!!");return false;}
			if(frm.elements[i].name=="ConsutasPrenatales"&&frm.elements[i].value==""){alert("Por favor ingrese el numero de consultas prenatales de la paciente!!!");return false;}			
			cotros++;	
		}
		else
		{			
			cotros++;	
		}				
	}	
	//alert(frm.elements.length+"  --  "+crad+" -- "+cotros);
	sum=parseInt(frm.elements.length)-(parseInt(crad)+parseInt(cotros));
	if(parseInt(sum)>0){return false;}
}
function EnfermedadesNO()
{
	document.FORMA.HTACronica[0].checked=true;
	document.FORMA.HTAGestacional[0].checked=true;
	document.FORMA.EnfermedadPreclampsia[0].checked=true;
	document.FORMA.EnfermedadEclampsia[0].checked=true;
	document.FORMA.EnfermedadCardiopatia_Nefropatia[0].checked=true;
	document.FORMA.EnfermedadDiabetes[0].checked=true;
	document.FORMA.EnfermedadAnemia[0].checked=true;
	//---
	document.FORMA.InfeccionUrinaria[1].checked=true;
	document.FORMA.AmenazaPartoP[1].checked=true;
	document.FORMA.RCIU[1].checked=true;
	document.FORMA.RupturaPremMembranas[1].checked=true;
	document.FORMA.CorioAmnionitis[1].checked=true;
	document.FORMA.OtrasGraves[1].checked=true;
	//---
	document.FORMA.Hemorragia1erTrim[1].checked=true;
	document.FORMA.Hemorragia2doTrim[1].checked=true;
	document.FORMA.Hemorragia3erTrim[1].checked=true;
	document.FORMA.HemorragiaPostparto[1].checked=true;
	document.FORMA.InfeccionPuerperal[1].checked=true;
	document.FORMA.Bacteriuria[1].checked=true;
}
</script>
</head>
<body background="/Imgs/Fondo.jpg">
<form name="FORMA" method="post" onSubmit="return Validar();">
<input type="hidden" name="DatNameSID" value="<? echo $DatNameSID?>">
<input type="hidden" name="IdClap" value="<? echo $IdClap?>">
<?
if($LlenarPartoAborto&&$NumServicio)
{
	echo "<center><font face='Tahoma' color='#0066FF' size='+2' ><b>PARTO O ABORTO </b></font><br>";
	?>
    <table border="1" cellspacing="0" cellpadding="0" bordercolor="#ffffff" style="font : normal normal small-caps 8px Tahoma; width:1058px" align="center">
    <tr align="center" >
    <td colspan="2" style="border-left-color:#000; border-top-color:#000; "><strong>PARTO</strong><input type="radio" id="PartoAborto" name="PartoAborto" value="Parto" <? if($PartoAborto=="Parto"){echo "checked";}?>  title="Parto / Aborto"  />
    <strong>ABORTO</strong><input type="radio" id="PartoAborto" name="PartoAborto" value="Aborto" <? if($PartoAborto=="Aborto"){echo "checked";}?>  title="Parto / Aborto" style="background-color:#E6E600"/>
    </td>
    <td width="85" style="border-left-color:#000; border-top-color:#000; ">Hospitaliz. en Embarazo</td>    
    <td colspan="3" style="border-left-color:#000; border-top-color:#000; font-weight:bold">Corticoides Antenatales</td>
    <td width="48" style="border-left-color:#000; border-top-color:#000; font-weight:bold">Inicio T de P</td>
    <td colspan="3" style="border-left-color:#000; border-top-color:#000; font-weight:bold;">Ruptura de Membranas Anteparto</td>
    <td width="45" rowspan="3" style="border-left-color:#000; border-top-color:#000; font-weight:bold">Horas<br />Entre<br />Ruptura<br />y Parto<br /><br />
     <input type="text" id="HorasRupturaParto" name="HorasRupturaParto" value="<? echo $HorasRupturaParto?>" style="width:35px; font-size:12px" maxlength="3" onKeyDown="xNumero(this)" onKeyUp="xNumero(this)" title="Horas Entre Ruptura y Parto">
    </td>
    <td colspan="2" style="border-left-color:#000; border-top-color:#000; font-weight:bold">Edad Gest al parto</td>
    <td width="62" style="border-left-color:#000; border-top-color:#000; font-weight:bold">Presentación</td>
    <td width="72" style="border-left-color:#000; border-top-color:#000; border-right-color:#000; font-weight:bold">Acompañante en T de P</td>
    </tr>
    <tr align="center">
    <td width="91" style="border-left-color:#000; border-top-color:#000; ">Fecha de Ingreso<br />
    <input type="text" id="FechaIngreso" name="FechaIngreso" value="<? echo $FechaIngreso?>" style="width:80px; font-size:12px" maxlength="10" onKeyDown="xLetra(this)" onKeyUp="xLetra(this)" readonly onClick="if(this.value=='aaaa-mm-dd'){this.value='';}popUpCalendar(this, this, 'yyyy-mm-dd')" onBlur="if(this.value==''){this.value='aaaa-mm-dd';}" title="Fecha de Ingreso">
    </td>
    <td width="74" rowspan="2" style="border-left-color:#000; border-top-color:#000; ">Consultas<br />Pre-Natales<br />Total<br />
    <input type="text" id="ConsutasPrenatales" name="ConsultasPrenatales" value="<? echo $ConsultasPrenatales?>" style="width:25px; font-size:12px" maxlength="2" onKeyDown="xNumero(this)" onKeyUp="xNumero(this)" title="Consultas Prenatales">
    </td>
    <td rowspan="2" style="border-left-color:#000; ">
    No<input type="radio" id="HospitalizEmbarazo" name="HospitalizEmbarazo" value="No" <? if($HospitalizEmbarazo=="No"){echo "checked";}?>  title="Hospitalizada en Embarazo" onClick="document.FORMA.DiasHospitalizada.value='0';document.FORMA.DiasHospitalizada.readOnly=true;" />
   Si<input type="radio" id="HospitalizEmbarazo" name="HospitalizEmbarazo" value="Si" <? if($HospitalizEmbarazo=="Si"){echo "checked";}?>  title="Hospitalizada en Embarazo"  style="background-color:#E6E600" onClick="document.FORMA.DiasHospitalizada.readOnly=false;if(document.FORMA.DiasHospitalizada.value=='0'){<? if($DiasHospitalizada!="0"){?>document.FORMA.DiasHospitalizada.value='<? echo $DiasHospitalizada?>';<? }else{?>document.FORMA.DiasHospitalizada.value='';<? }?>}"/>   
   <br />
    Dias<br />
    <input type="text" id="DiasHospitalizada" name="DiasHospitalizada" value="<? echo $DiasHospitalizada?>" style="width:25px; font-size:12px" maxlength="2" onKeyDown="xNumero(this)" onKeyUp="xNumero(this)" title="Dias Hospitalizada" onMouseOver="if(document.FORMA.HospitalizEmbarazo[0].checked){this.readOnly=true;}"  onFocus="if(document.FORMA.HospitalizEmbarazo[0].checked){this.readOnly=true;}" onClick="if(document.FORMA.HospitalizEmbarazo[0].checked){this.readOnly=true;}">
    </td>
    <td width="65" rowspan="2" style="border-left-color:#000; ">Ciclo Unico<br />Completo<br />
    <input type="checkbox" id="CorticoidesAntenatales" name="CorticoidesAntenatales" value="Ciclo Unico Completo" <? if($CorticoidesAntenatales=="Ciclo Unico Completo"){echo "checked";}?>  title="Corticoides Antenatales"  /><br />
    Multiples<br />
    <input type="checkbox" id="CorticoidesAntenatales" name="CorticoidesAntenatales" value="Multiples" <? if($CorticoidesAntenatales=="Multiples"){echo "checked";}?>  title="Corticoides Antenatales" style="background-color:#E6E600" />
    </td>
    <td width="48" rowspan="2">Ciclo Unico<br />Incompleto<br />
    <input type="checkbox" id="CorticoidesAntenatales" name="CorticoidesAntenatales" value="Ciclo Unico Incompleto" <? if($CorticoidesAntenatales=="Ciclo Unico Incompleto"){echo "checked";}?>  title="Corticoides Antenatales" style="background-color:#E6E600" /><br />
    Ninguna<br />
    <input type="checkbox" id="CorticoidesAntenatales" name="CorticoidesAntenatales" value="Ninguna" <? if($CorticoidesAntenatales=="Ninguna"){echo "checked";}?>  title="Corticoides Antenatales" style="background-color:#E6E600" />
    </td>
    <td width="83" rowspan="2">
     N/C<br />
    <input type="checkbox" id="CorticoidesAntenatales" name="CorticoidesAntenatales" value="N/C" <? if($CorticoidesAntenatales=="N/C"){echo "checked";}?>  title="Corticoides Antenatales" /><br>
    <input type="text" id="ConsultasPrenatalesSI" name="ConsultasPrenatalesSI" value="<? echo $ConsultasPrenatalesSI?>" style="width:25px; font-size:12px" maxlength="2" onKeyDown="xNumero(this)" onKeyUp="xNumero(this)" title="Semana de Inicio">    <br />
    Semana de Inicio<br />   
    </td>
    <td rowspan="2" style="border-left-color:#000; ">
    Espontaneo<br />
    <input type="radio" id="InicioTP" name="InicioTP" value="Espontaneo" <? if($InicioTP=="Espontaneo"){echo "checked";}?>  title="Inicio Trabajo de Parto" /><br />
    Inducido<br />
    <input type="radio" id="InicioTP" name="InicioTP" value="Inducido" <? if($InicioTP=="Inducido"){echo "checked";}?>  title="Inicio Trabajo de Parto" style="background-color:#E6E600" /><br />
    Cesar. Elect.<br />
    <input type="radio" id="InicioTP" name="InicioTP" value="Cesarea Electiva" <? if($InicioTP=="Cesarea Electiva"){echo "checked";}?>  title="Inicio Trabajo de Parto" style="background-color:#E6E600" />
    </td>
    <td width="36" rowspan="2" style="border-left-color:#000; ">
    Integras<br />
    <input type="checkbox" id="RupturaMembranas" name="RupturaMembranas" value="Integras" <? if($RupturaMembranas=="Integras"){echo "checked";}?>  title="Ruptura de Membranas Anteparto" /><br />
    Rotas<br />
    <input type="checkbox" id="RupturaMembranas" name="RupturaMembranas" value="Rotas" <? if($RupturaMembranas=="Rotas"){echo "checked";}?>  title="Ruptura de Membranas Anteparto" style="background-color:#E6E600"  />
    </td>
    <td width="101" rowspan="2" >
    <input type="text" id="FechaRupturaMembranas" name="FechaRupturaMembranas" value="<? echo $FechaRupturaMembranas?>" style="width:80px; font-size:12px" maxlength="10" onKeyDown="xLetra(this)" onKeyUp="xLetra(this)" readonly onClick="if(this.value=='aaaa-mm-dd'){this.value='';}popUpCalendar(this, this, 'yyyy-mm-dd')" onBlur="if(this.value==''){this.value='aaaa-mm-dd';}" title="Fecha Ruptura Membranas"><br />
    <input type="text" id="HoraRupturaMembranas" name="HoraRupturaMembranas" value="<? echo $HoraRupturaMembranas?>" style="width:30px; font-size:12px" maxlength="2" onKeyDown="xNumero(this)" onKeyUp="xNumero(this)"  title="Hora Ruptura Membranas">
    <input type="text" id="MinutoRupturaMembranas" name="MinutoRupturaMembranas" value="<? echo $MinutoRupturaMembranas?>" style="width:30px; font-size:12px" maxlength="2" onKeyDown="xNumero(this)" onKeyUp="xNumero(this)" title="Minuto Ruptura Membranas">
    </td>
    <td width="125" rowspan="2" align="right">
    < 37 Sem.<input type="checkbox" id="RuptMembranaMen37Sem" name="RuptMembranaMen37Sem" value="< 37 Sem"<? if($RuptMembranaMen37Sem=="< 37 Sem"){echo "checked";}?> style="background-color:#E6E600" ><br />
    >= 37 Hs.<input type="checkbox" id="RuptMembranaMay37Hs" name="RuptMembranaMay37Hs" value=">= 37 Hs"<? if($RuptMembranaMay37Hs==">= 37 Hs"){echo "checked";}?> style="background-color:#E6E600" ><br />
    Temp >38ºC.<input type="checkbox" id="RuptMembranaTemp" name="RuptMembranaTemp" value="> 38 ºC"<? if($RuptMembranaTemp=="> 38 ºC"){echo "checked";}?> style="background-color:#E6E600" ><br /> 
    Semana&nbsp;<input type="text" id="SemanaRupturaMembranas" name="SemanaRupturaMembranas" value="<? echo $SemanaRupturaMembranas?>" style="width:25px; font-size:12px" maxlength="2" onKeyDown="xNumero(this)" onKeyUp="xNumero(this)" title="Semana Ruptura Membranas">   
    <br />Ruptura&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;       
    </td>
    <td width="36" rowspan="2" style="border-left-color:#000; ">
    Semanas<br />
    <input type="text" id="EdadGestSemanas" name="EdadGestSemanas" value="<? echo $EdadGestSemanas?>" style="width:25px; font-size:12px" maxlength="2" onKeyDown="xNumero(this)" onKeyUp="xNumero(this)" title="Edad Gestacional - Semanas"><br />
    por FUM<br />
    <input type="checkbox" id="EdadGestxFUM" name="EdadGestxFUM" value="por FUM"<? if($EdadGestxFUM=="por FUM"){echo "checked";}?> >
    </td>
    <td width="55" rowspan="2">
    Dias<br />
    <input type="text" id="EdadGestDias" name="EdadGestDias" value="<? echo $EdadGestDias?>" style="width:20px; font-size:12px" maxlength="1" onKeyDown="xNumero(this)" onKeyUp="xNumero(this)" title="Edad Gestacional - Dias"><br />
    por ECO<br />
    <input type="checkbox" id="EdadGestxECO" name="EdadGestxECO" value="por ECO"<? if($EdadGestxECO=="por ECO"){echo "checked";}?> >
    </td>
    <td rowspan="2" style="border-left-color:#000; ">
    Cefalica<br />
    <input type="radio" id="Presentacion" name="Presentacion" value="Cefalica" <? if($Presentacion=="Cefalica"){echo "checked";}?>  title="Presentacion" /><br />
    Pelviana<br />
    <input type="radio" id="Presentacion" name="Presentacion" value="Pelviana" <? if($Presentacion=="Pelviana"){echo "checked";}?>  title="Presentación" style="background-color:#E6E600" /><br />
    Transversa<br />
    <input type="radio" id="Presentacion" name="Presentacion" value="Transversa" <? if($Presentacion=="Transversa"){echo "checked";}?>  title="Presentacion" style="background-color:#E6E600" />
    </td>
    <td rowspan="2" align="right" style="border-left-color:#000; border-right-color:#000; ">
    Pareja
    <input type="radio" id="AcompanianteTP" name="AcompanianteTP" value="Pareja" <? if($AcompanianteTP=="Pareja"){echo "checked";}?>  title="Acom pañante Trabajo de Parto" /><br />
    Familiar
    <input type="radio" id="AcompanianteTP" name="AcompanianteTP" value="Familiar" <? if($AcompanianteTP=="Familiar"){echo "checked";}?>  title="Acom pañante Trabajo de Parto" /><br />
    Otro
    <input type="radio" id="AcompanianteTP" name="AcompanianteTP" value="Otro" <? if($AcompanianteTP=="Otro"){echo "checked";}?>  title="Acom pañante Trabajo de Parto" /><br />
   Ninguno
    <input type="radio" id="AcompanianteTP" name="AcompanianteTP" value="Ninguno" <? if($AcompanianteTP=="Ninguno"){echo "checked";}?>  title="Presentacion" style="background-color:#E6E600" />
    </td>
    </tr>
    <tr align="center">
    <td style="border-left-color:#000;">
    CARNÉ&nbsp;&nbsp;&nbsp;Si
    <input type="radio" id="Carne" name="Carne" value="Si" <? if($Carne=="Si"){echo "checked";}?>  title="CARNÉ" />&nbsp;
   	No
    <input type="radio" id="Carne" name="Carne" value="No" <? if($Carne=="No"){echo "checked";}?>  title="CARNÉ" style="background-color:#E6E600"/>
    </td>
    </tr>
    <tr align="center">
    <td colspan="2" align="left" style="border-left-color:#000; border-top-color:#000;">
    <strong>NACIMIENTO</strong>&nbsp;&nbsp;&nbsp;Vivo<input type="radio" id="Nacimiento" name="Nacimiento" value="Vivo" <? if($Nacimiento=="Vivo"){echo "checked";}?>  title="Nacimiento" /><br />
    Muerto&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Ignora <br />
    Anteparto<input type="radio" id="Nacimiento" name="Nacimiento" value="Anteparto" <? if($Nacimiento=="Anteparto"){echo "checked";}?>  title="Nacimiento"  style="background-color:#E6E600"/>
    Parto<input type="radio" id="Nacimiento" name="Nacimiento" value="Parto" <? if($Nacimiento=="Parto"){echo "checked";}?>  title="Nacimiento"  style="background-color:#E6E600"/>   
    Movimiento<input type="radio" id="Nacimiento" name="Nacimiento" value="Ignora Movimiento" <? if($Nacimiento=="Ignora Movimiento"){echo "checked";}?>  title="Nacimiento"  style="background-color:#E6E600"/>
    </td>
    <td style="border-left-color:#000; border-top-color:#000;">
    Hora - minutos nacimiento<br />
    <input type="text" id="HoraNacimiento" name="HoraNacimiento" value="<? echo $HoraNacimiento?>" style="width:30px; font-size:12px" maxlength="2" onKeyDown="xNumero(this)" onKeyUp="xNumero(this)"  title="Hora Nacimiento">
    <input type="text" id="MinutoNacimiento" name="MinutoNacimiento" value="<? echo $MinutoNacimiento?>" style="width:30px; font-size:12px" maxlength="2" onKeyDown="xNumero(this)" onKeyUp="xNumero(this)" title="Minuto Nacimiento">
    </td>
    <td colspan="2" style="border-left-color:#000; border-top-color:#000;" >
    Fecha Nacimiento<br />
    <input type="text" id="FechaNacimiento" name="FechaNacimiento" value="<? echo $FechaNacimiento?>" style="width:80px; font-size:12px" maxlength="10" onKeyDown="xLetra(this)" onKeyUp="xLetra(this)" readonly onClick="if(this.value=='aaaa-mm-dd'){this.value='';}popUpCalendar(this, this, 'yyyy-mm-dd')" onBlur="if(this.value==''){this.value='aaaa-mm-dd';}" title="Fecha Nacimiento">
    </td>
    <td align="left"  style="border-left-color:#000; border-top-color:#000;" >
    MULTIPLE&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Fetos<br>
    Orden    
    <input type="text" id="MultipleOrden" name="MultipleOrden" value="<? echo $MultipleOrden?>" style="width:25px; font-size:12px" maxlength="1" onKeyDown="xNumero(this)" onKeyUp="xNumero(this)" title="Orden">/
    <input type="text" id="MultipleFetos" name="MultipleFetos" value="<? echo $MultipleFetos?>" style="width:25px; font-size:12px" maxlength="1" onKeyDown="xNumero(this)" onKeyUp="xNumero(this)" title="Fetos"><br>
    0=Unico
    </td>
    <td style="border-left-color:#000; border-top-color:#000;" align="right">
    <br>
    Espont<input type="checkbox" id="Terminacion" name="Terminacion" value="Espontanea" <? if($Terminacion=="Espontanea"){echo "checked";}?>  title="Terminación" /><br>
    Forceps<input type="checkbox" id="Terminacion" name="Terminacion" value="Forceps" <? if($Terminacion=="Forceps"){echo "checked";}?>  title="Terminación" />
    </td>
    <td align="left" colspan="2" style="border-top-color:#000;">
    TERMINACION<BR>
    Cesarea<input type="checkbox" id="Terminacion" name="Terminacion" value="Cesarea" <? if($Terminacion=="Cesarea"){echo "checked";}?>  title="Terminación" /><br>
    Espatula<input type="checkbox" id="Terminacion" name="Terminacion" value="Espatula" <? if($Terminacion=="Espatula"){echo "checked";}?>  title="Terminación" />
    </td>
    <td colspan="3" align="left" style="border-left-color:#000; border-top-color:#000;">
    INDICACION PRINCIPAL DE INDUCCION O PARTO OPERATORIO<BR>
    <input type="text" id="IndicacionInduccionParto" name="IndicacionInduccionParto" value="<? echo $IndicacionInduccionParto?>" style="width:200px; font-size:12px" maxlength="200" onKeyDown="this.value.toUpperCase();" onKeyUp="this.value.toUpperCase();" title="INDICACION PRINCIPAL DE INDUCCION O PARTO OPERATORIO" />
    </td>
    <td colspan="2" style="border-top-color:#000;">
    INDUCCIÓN<BR>
    <input type="text" id="Induccion" name="Induccion" value="<? echo $Induccion?>" style="width:50px; font-size:12px; background-color:#FF0;" maxlength="4" onKeyDown="xNumero(this)" onKeyUp="xNumero(this)" title="Codigo Inducción" />
    </td>
    <td style="border-left-color:#000; border-top-color:#000; border-right-color:#000;">
    OPERATORIO<BR>
    <input type="text" id="Operatorio" name="Operatorio" value="<? echo $Operatorio?>" style="width:50px; font-size:12px; background-color:#FF0;" maxlength="4" onKeyDown="xNumero(this)" onKeyUp="xNumero(this)" title="Codigo Operatorio" />
    </td>
    </tr>
    <tr align="center">
    <td style="border-left-color:#000; border-top-color:#000;">
    POSICIÓN PARTO<br>
    Sentada<input type="radio" id="PosicionParto" name="PosicionParto" value="Sentada" <? if($PosicionParto=="Sentada"){echo "checked";}?>  title="Posicion Parto" />
    Acostada<input type="radio" id="PosicionParto" name="PosicionParto" value="Acostada" <? if($PosicionParto=="Acostada"){echo "checked";}?>  title="Posicion Parto"  style="background-color:#E6E600" /><br>
    Cuclillas<input type="radio" id="PosicionParto" name="PosicionParto" value="Cuclillas" <? if($PosicionParto=="Cuclillas"){echo "checked";}?>  title="Posicion Parto" />
    </td>
    <td style="border-left-color:#000; border-top-color:#000;">
   	Episiotomia<br>
    No<input type="radio" id="Episiotomia" name="Episiotomia" value="No" <? if($Episiotomia=="No"){echo "checked";}?>  title="Episiotomia" />
    Si<input type="radio" id="Episiotomia" name="Episiotomia" value="Si" <? if($Episiotomia=="Si"){echo "checked";}?>  title="Episiotomia"  style="background-color:#E6E600" />
    </td>
    <td colspan="2" align="left" style="border-left-color:#000; border-top-color:#000;">
    DESGARROS&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;OXITOCICOS EN<BR>
    Grado(1 a 4)&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;ALUMBRAMIENTO<BR>
    No<input type="checkbox" id="Desgarros" name="Desgarros" value="No" <? if($Desgarros=="No"){echo "checked";}?>  title="Desgarros" />
    <input type="text" id="DesgarrosGrado" name="DesgarrosGrado" value="<? echo $DesgarrosGrado?>" style="width:20px; font-size:12px" maxlength="1" onKeyDown="xNumero(this);if(parseInt(this.value)>4){this.value=4;}" onKeyUp="xNumero(this);if(parseInt(this.value)>4){this.value=4;}" title="Grado Desgarro">
   &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Si<input type="radio" id="OxitocicosAlumbramiento" name="OxitocicosAlumbramiento" value="Si" <? if($OxitocicosAlumbramiento=="Si"){echo "checked";}?>  title="Oxitocicos en Alumbramiento" />
    No<input type="radio" id="OxitocicosAlumbramiento" name="OxitocicosAlumbramiento" value="No" <? if($OxitocicosAlumbramiento=="No"){echo "checked";}?>  title="Oxitocicos en Alumbramiento"  style="background-color:#E6E600" />
    </td>
    <td colspan="2" align="left" style="border-left-color:#000; border-top-color:#000;">
    PLACENTA&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Si&nbsp;&nbsp;&nbsp;&nbsp;No<BR>
    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Completa&nbsp;&nbsp;<input type="radio" id="PlacentaCompleta" name="PlacentaCompleta" value="Si" <? if($PlacentaCompleta=="Si"){echo "checked";}?>  title="Completa"   /> 
    <input type="radio" id="PlacentaCompleta" name="PlacentaCompleta" value="No" <? if($PlacentaCompleta=="No"){echo "checked";}?>  title="Completa"  style="background-color:#E6E600" /> <br>
    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Retenida&nbsp;&nbsp;<input type="radio" id="PlacentaRetenida" name="PlacentaRetenida" value="Si" <? if($PlacentaRetenida=="Si"){echo "checked";}?>  title="Retenida" style="background-color:#E6E600"  /> 
    <input type="radio" id="PlacentaRetenida" name="PlacentaRetenida" value="No" <? if($PlacentaRetenida=="No"){echo "checked";}?>  title="Retenida"   /> 
    </td>
    <td colspan="2" style="border-left-color:#000; border-top-color:#000;">
    LIGADURA CORDON<BR>
    <30s&nbsp; 30s 1m &nbsp; >1m<br>
    <input type="checkbox" id="LigaduraCordon" name="LigaduraCordon" value="< 30s" <? if($LigaduraCordon=="< 30s"){echo "checked";}?>  title="Ligadura Cordon"   style="background-color:#E6E600" />&nbsp;
    <input type="checkbox" id="LigaduraCordon" name="LigaduraCordon" value="30s 1m" <? if($LigaduraCordon=="30s 1m"){echo "checked";}?>  title="Ligadura Cordon"   />&nbsp;
    <input type="checkbox" id="LigaduraCordon" name="LigaduraCordon" value="> 1m" <? if($LigaduraCordon=="> 1m"){echo "checked";}?>  title="Ligadura Cordon"   />    
    </td>
    <td align="left" style="border-left-color:#000;border-top-color:#000; width:90px;">
   MEDICACIÓN&nbsp;&nbsp;Ocitocicos<BR>RECIBIDA&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;en TdP 
    <br>
    <input type="text" id="EspecificarOtros" name="EspecificarOtros" value="<? echo $EspecificarOtros?>" style="width:50px; font-size:10px" maxlength="40" title="Especificar Otros medicamentos" onMouseOver="if(document.FORMA.OtrosMedi[0].checked){this.readOnly=true;}"  onFocus="if(document.FORMA.OtrosMedi[0].checked){this.readOnly=true;}" onClick="if(document.FORMA.OtrosMedi[0].checked){this.readOnly=true;}">
    &nbsp;
    No<input type="radio" id="OcitocicosTdP" name="OcitocicosTdP" value="No" <? if($OcitocicosTdP=="No"){echo "checked";}?>  title="Ocitocicos en TdP" /><br>
    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
    Si<input type="radio" id="OcitocicosTdP" name="OcitocicosTdP" value="Si" <? if($OcitocicosTdP=="Si"){echo "checked";}?>  title="Ocitocicos en TdP"  style="background-color:#E6E600" />    
    </td>
    <td align="left" style="border-top-color:#000;">    
    Analgecia&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Anest.<br>Epidural
    &nbsp;&nbsp;&nbsp;Antibiot.&nbsp;&nbsp;Region.
    <br>    
     No<input type="radio" id="AnalgeciaEpidural" name="AnalgeciaEpidural" value="No" <? if($AnalgeciaEpidural=="No"){echo "checked";}?>  title="Analgecia Epidural" />
    &nbsp;&nbsp;
    No<input type="radio" id="Antibioticos" name="Antibioticos" value="No" <? if($Antibioticos=="No"){echo "checked";}?>  title="Antibioticos" /> 
    &nbsp;&nbsp;
    No<input type="radio" id="AnesteciaRegional" name="AnesteciaRegional" value="No" <? if($AnesteciaRegional=="No"){echo "checked";}?>  title="Anestecia Regional" /> 
    <br>    
    &nbsp;Si<input type="radio" id="AnalgeciaEpidural" name="AnalgeciaEpidural" value="Si" <? if($AnalgeciaEpidural=="Si"){echo "checked";}?>  title="Analgecia Epidural"  style="background-color:#E6E600" />
     &nbsp;&nbsp;&nbsp;
    Si<input type="radio" id="Antibioticos" name="Antibioticos" value="Si" <? if($Antibioticos=="Si"){echo "checked";}?>  title="Antibioticos" style="background-color:#E6E600" />        
    &nbsp;&nbsp;&nbsp;&nbsp;Si<input type="radio" id="AnesteciaRegional" name="AnesteciaRegional" value="Si" <? if($AnesteciaRegional=="Si"){echo "checked";}?>  title="Anestecia Regional"  style="background-color:#E6E600" />     
    </td>
    <td style="border-top-color:#000;">   
    Anest.<br>Gral.<br>
    No<input type="radio" id="AnesteciaGral" name="AnesteciaGral" value="No" <? if($AnesteciaGral=="No"){echo "checked";}?>  title="Anestecia General" /><br> 
    &nbsp;Si<input type="radio" id="AnesteciaGral" name="AnesteciaGral" value="Si" <? if($AnesteciaGral=="Si"){echo "checked";}?>  title="Anestecia General"  style="background-color:#E6E600" />    
   	</td>
    <td style="border-top-color:#000;">
    Sulfato<br>de Mg<br>
    No<input type="radio" id="SulfatoMg" name="SulfatoMg" value="No" <? if($SulfatoMg=="No"){echo "checked";}?>  title="Sulfato de Mg" /><br> 
    &nbsp;Si<input type="radio" id="SulfatoMg" name="SulfatoMg" value="Si" <? if($SulfatoMg=="Si"){echo "checked";}?>  title="Sulfato de Mg"  style="background-color:#E6E600" /> 
    </td>
    <td style="border-top-color:#000;">
    <br>Transfusión<br>
    No<input type="radio" id="Transfusion" name="Transfusion" value="No" <? if($Transfusion=="No"){echo "checked";}?>  title="Transfusión" /><br> 
    &nbsp;Si<input type="radio" id="Transfusion" name="Transfusion" value="Si" <? if($Transfusion=="Si"){echo "checked";}?>  title="Transfusión"  style="background-color:#E6E600" /> 
    </td>
    <td style="border-top-color:#000;">
    <br>Otros &nbsp;<br>
    No<input type="radio" id="OtrosMedi" name="OtrosMedi" value="No" <? if($OtrosMedi=="No"){echo "checked";}?>  title="Otros" onClick="document.FORMA.EspecificarOtros.value='NA';document.FORMA.EspecificarOtros.readOnly=true;" /><br>
    &nbsp;Si<input type="radio" id="OtrosMedi" name="OtrosMedi" value="Si" <? if($OtrosMedi=="Si"){echo "checked";}?>  title="Otros"  style="background-color:#E6E600" onClick="document.FORMA.EspecificarOtros.readOnly=false;if(document.FORMA.EspecificarOtros.value=='NA'){<? if($EspecificarOtros!="NA"){?>document.FORMA.EspecificarOtros.value='<? echo $EspecificarOtros?>';<? }else{?>document.FORMA.EspecificarOtros.value='';<? }?>}"/> 
    </td>
    <td style="border-top-color:#000; border-left-color:#000; border-right-color:#000;">
    <strong>Se Hizo<br>Partograma</strong>
    Si&nbsp;<input type="radio" id="HizoPartograma" name="HizoPartograma" value="Si" <? if($HizoPartograma=="Si"){echo "checked";}?>  title="Se Hizo Partograma" /><br>
    &nbsp;No<input type="radio" id="HizoPartograma" name="HizoPartograma" value="No" <? if($HizoPartograma=="No"){echo "checked";}?>  title="Se Hizo Partograma"  style="background-color:#E6E600" />
    </td>
    </tr>
    <tr align="center">
    <td align="left" colspan="6" style="border-left-color:#000; border-top-color:#000;">
    <strong> NOTAS</strong>
    </td>
    <td align="left" colspan="9" style="border-left-color:#000; border-top-color:#000; border-right-color:#000;">
    <strong>ENFERMEDADES</strong>
    </td>
    </tr>
    <tr align="center">
    <td rowspan="4" align="left" colspan="6" style="border-left-color:#000; border-bottom-color:#000; ">
    <input type="text" id="Notas1" name="Notas1" value="<? echo $Notas1?>" style="width:480px; font-size:10px" title="Nota 1"><br>
    <input type="text" id="Notas2" name="Notas2" value="<? echo $Notas2?>" style="width:480px; font-size:10px" title="Nota 2"><br>
    <input type="text" id="Notas3" name="Notas3" value="<? echo $Notas3?>" style="width:480px; font-size:10px" title="Nota 3"><br>
    <input type="text" id="Notas4" name="Notas4" value="<? echo $Notas4?>" style="width:480px; font-size:10px" title="Nota 4"><br>
    <input type="text" id="Notas5" name="Notas5" value="<? echo $Notas5?>" style="width:480px; font-size:10px" title="Nota 5">
    </td>
    <td colspan="2" align="right"  valign="top"  style="border-left-color:#000;" >
    Ninguna<input type="checkbox" id="EnfermedadesNinguna" name="EnfermedadesNinguna" value="Ninguna" <? if($EnfermedadesNinguna=="Ninguna"){echo "checked";}?> onClick="if(this.checked){EnfermedadesNO();}"  title="Ninguna Enfermedad" /><br>
    HTA Cronica<br>
    HTA gestacional    
    </td>
    <td align="left" valign="top" >
    &nbsp; no&nbsp;&nbsp;&nbsp;&nbsp; si<br>
    <input type="radio" id="HTACronica" name="HTACronica" value="No" <? if($HTACronica=="No"){echo "checked";}?>  title="HTA Cronica" />
    <input type="radio" id="HTACronica" name="HTACronica" value="Si" <? if($HTACronica=="Si"){echo "checked";}?>  title="HTA Cronica"  style="background-color:#E6E600" />
<br>
<input type="radio" id="HTAGestacional" name="HTAGestacional" value="No" <? if($HTAGestacional=="No"){echo "checked";}?>  title="HTA Gestacional" />
<input type="radio" id="HTAGestacional" name="HTAGestacional" value="Si" <? if($HTAGestacional=="Si"){echo "checked";}?>  title="HTA Gestacional"  style="background-color:#E6E600" /></td>
    <td align="right" valign="top" >
    <br><br>
    Infec. Urinaria<br>
    Amenaza parto preter.    
    </td>
    <td colspan="2" align="left"  valign="top" >
    &nbsp;&nbsp;Si&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;No<br>
    <input type="radio" id="InfeccionUrinaria" name="InfeccionUrinaria" value="Si" <? if($InfeccionUrinaria=="Si"){echo "checked";}?>  title="Infección Urinaria"  style="background-color:#E6E600" />
	<input type="radio" id="InfeccionUrinaria" name="InfeccionUrinaria" value="No" <? if($InfeccionUrinaria=="No"){echo "checked";}?>  title="Infección Urinaria" />
    <br>
    <input type="radio" id="AmenazaPartoP" name="AmenazaPartoP" value="Si" <? if($AmenazaPartoP=="Si"){echo "checked";}?>  title="Amenaza Parto Preter."  style="background-color:#E6E600" />
	<input type="radio" id="AmenazaPartoP" name="AmenazaPartoP" value="No" <? if($AmenazaPartoP=="No"){echo "checked";}?>  title="Amenaza Parto Preter." />    
    </td>
    <td align="right" valign="top" >
    HEMORRAGIA<br><br>
    1er Trim<br>
    2do Trim.    
    </td>
    <td align="left"  valign="top" >
    &nbsp;&nbsp;Si&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;No<br>
    <input type="radio" id="Hemorragia1erTrim" name="Hemorragia1erTrim" value="Si" <? if($Hemorragia1erTrim=="Si"){echo "checked";}?>  title="Hemorragia Primer Trimestre"  style="background-color:#E6E600" />
	<input type="radio" id="Hemorragia1erTrim" name="Hemorragia1erTrim" value="No" <? if($Hemorragia1erTrim=="No"){echo "checked";}?>  title="Hemorragia Primer Trimestre" />
    <br>
    <input type="radio" id="Hemorragia2doTrim" name="Hemorragia2doTrim" value="Si" <? if($Hemorragia2doTrim=="Si"){echo "checked";}?>  title="Hemorragia Segundo Trimestre"  style="background-color:#E6E600" />
	<input type="radio" id="Hemorragia2doTrim" name="Hemorragia2doTrim" value="No" <? if($Hemorragia2doTrim=="No"){echo "checked";}?>  title="Hemorragia Segundo Trimestre" />    
    </td>
    <td align="center" valign="top" style="border-right-color:#000;" >
    Codigo<br>
    <input type="text" id="CodigoEnfermedad1" name="CodigoEnfermedad1" value="<? echo $CodigoEnfermedad1?>" style="width:50px; font-size:12px; background-color:#FF0;" maxlength="4" onKeyDown="xNumero(this)" onKeyUp="xNumero(this)" title="Codigo Enfermedad 1" />
    </td>
    </tr>
    <tr align="center">    
    <td colspan="2" align="right"  valign="top"  style="border-left-color:#000;" >   
    <br>
    Preclampsia<br>
    Eclampsia    
    </td>
    <td align="left" valign="top" ><input type="radio" id="EnfermedadPreclampsia" name="EnfermedadPreclampsia" value="No" <? if($EnfermedadPreclampsia=="No"){echo "checked";}?>  title="Preclampsia" />
      <input type="radio" id="EnfermedadPreclampsia2" name="EnfermedadPreclampsia" value="Si" <? if($EnfermedadPreclampsia=="Si"){echo "checked";}?>  title="Preclampsia"  style="background-color:#E6E600" />
<br>
<input type="radio" id="EnfermedadEclampsia" name="EnfermedadEclampsia" value="No" <? if($EnfermedadEclampsia=="No"){echo "checked";}?>  title="Elcampsia" />
<input type="radio" id="EnfermedadEclampsia2" name="EnfermedadEclampsia" value="Si" <? if($EnfermedadEclampsia=="Si"){echo "checked";}?>  title="Eclampsia"  style="background-color:#E6E600" /></td>
    <td align="right" valign="top" >
    <br>
    R.C.I.U<br>
    Ruptura prem de membranas    
    </td>
    <td colspan="2" align="left"  valign="top" >    
    <input type="radio" id="RCIU" name="RCIU" value="Si" <? if($RCIU=="Si"){echo "checked";}?>  title="R.C.I.U"  style="background-color:#E6E600" />
	<input type="radio" id="RCIU" name="RCIU" value="No" <? if($RCIU=="No"){echo "checked";}?>  title="R.C.I.U" />
    <br>
    <input type="radio" id="RupturaPremMembranas" name="RupturaPremMembranas" value="Si" <? if($RupturaPremMembranas=="Si"){echo "checked";}?>  title="Ruptura Prem. de Membranas"  style="background-color:#E6E600" />
	<input type="radio" id="RupturaPremMembranas" name="RupturaPremMembranas" value="No" <? if($RupturaPremMembranas=="No"){echo "checked";}?>  title="Ruptura Prem. de Membranas" />    
    </td>
    <td align="right" valign="top" >
    <br>
    3er. Trim<br>
    Postparto    
    </td>
    <td align="left"  valign="top" >    
    <input type="radio" id="Hemorragia3erTrim" name="Hemorragia3erTrim" value="Si" <? if($Hemorragia3erTrim=="Si"){echo "checked";}?>  title="Hemorragia Tercer Trimestre"  style="background-color:#E6E600" />
	<input type="radio" id="Hemorragia3erTrim" name="Hemorragia3erTrim" value="No" <? if($Hemorragia3erTrim=="No"){echo "checked";}?>  title="Hemorragia Tercer Trimestre" />
    <br>
    <input type="radio" id="HemorragiaPostparto" name="HemorragiaPostparto" value="Si" <? if($HemorragiaPostparto=="Si"){echo "checked";}?>  title="Hemorragia Postparto"  style="background-color:#E6E600" />
	<input type="radio" id="HemorragiaPostparto" name="HemorragiaPostparto" value="No" <? if($HemorragiaPostparto=="No"){echo "checked";}?>  title="Hemorragia Postparto" />    
    </td>
    <td align="center" valign="top" style="border-right-color:#000;">   
    <input type="text" id="CodigoEnfermedad2" name="CodigoEnfermedad2" value="<? echo $CodigoEnfermedad2?>" style="width:50px; font-size:12px; background-color:#FF0;" maxlength="4" onKeyDown="xNumero(this)" onKeyUp="xNumero(this)" title="Codigo Enfermedad 2" />
    </td>
    </tr>
    <tr align="center">    
    <td colspan="2" align="right"  valign="top"  style="border-left-color:#000; border-bottom-color:#000;" >    <br>   
    Cardiopatia/Nefropatia<br><br>
    Diabetes<br><br>
    Anemia    
    </td>
    <td align="left" valign="top" style=" border-bottom-color:#000;" ><input type="radio" id="EnfermedadCardiopatia_Nefropatia" name="EnfermedadCardiopatia_Nefropatia" value="No" <? if($EnfermedadCardiopatia_Nefropatia=="No"){echo "checked";}?>  title="Cardiopatia/Nefropatia" />
      <input type="radio" id="EnfermedadCardiopatia_Nefropatia" name="EnfermedadCardiopatia_Nefropatia" value="Si" <? if($EnfermedadCardiopatia_Nefropatia=="Si"){echo "checked";}?>  title="Cardiopatia/Nefropatia"  style="background-color:#E6E600" />
      <img src="diabetesclap.png" style="width:40; height:14" title="Diabetes">
<br>
    <input type="radio" id="EnfermedadDiabetes" name="EnfermedadDiabetes" value="No" <? if($EnfermedadDiabetes=="No"){echo "checked";}?>  title="Diabetes" />
    <input type="radio" id="EnfermedadDiabetes" name="EnfermedadDiabetes" value="I" <? if($EnfermedadDiabetes=="I"){echo "checked";}?>  title="Diabetes"  style="background-color:#E6E600" />
    <input type="radio" id="EnfermedadDiabetes" name="EnfermedadDiabetes" value="II" <? if($EnfermedadDiabetes=="II"){echo "checked";}?>  title="Diabetes" style="background-color:#E6E600" />
    <input type="radio" id="EnfermedadDiabetes" name="EnfermedadDiabetes" value="G" <? if($EnfermedadDiabetes=="G"){echo "checked";}?>  title="Diabetes" style="background-color:#E6E600"/><br>       
    <input type="radio" id="EnfermedadAnemia2" name="EnfermedadAnemia" value="No" <? if($EnfermedadAnemia=="No"){echo "checked";}?>  title="Anemia" />
    <input type="radio" id="EnfermedadAnemia" name="EnfermedadAnemia" value="Si" <? if($EnfermedadAnemia=="Si"){echo "checked";}?>  title="Anemia"  style="background-color:#E6E600" /></td>
    <td align="right" valign="top" style=" border-bottom-color:#000;"  >
    <br>
    Corio Amnionitis<br>
    Otras Graves    
    </td>
    <td colspan="2" align="left"  valign="top"  style=" border-bottom-color:#000;">    
    <input type="radio" id="CorioAmnionitis" name="CorioAmnionitis" value="Si" <? if($CorioAmnionitis=="Si"){echo "checked";}?>  title="Corio Amnionitis"  style="background-color:#E6E600" />
	<input type="radio" id="CorioAmnionitis" name="CorioAmnionitis" value="No" <? if($CorioAmnionitis=="No"){echo "checked";}?>  title="Corio Amnionitis" />
    <br>
    <input type="radio" id="OtrasGraves" name="OtrasGraves" value="Si" <? if($OtrasGraves=="Si"){echo "checked";}?>  title="Otras Graves"  style="background-color:#E6E600" />
	<input type="radio" id="OtrasGraves" name="OtrasGraves" value="No" <? if($OtrasGraves=="No"){echo "checked";}?>  title="Otras Graves" />    
    </td>
    <td align="right" valign="top"  style=" border-bottom-color:#000;">   
    Infección Puerperal<br><br>
    Bacteriuria    
    </td>
    <td align="left"  valign="top" style=" border-bottom-color:#000;">    
    <input type="radio" id="InfeccionPuerperal" name="InfeccionPuerperal" value="Si" <? if($InfeccionPuerperal=="Si"){echo "checked";}?>  title="Infección Puerperal"  style="background-color:#E6E600" />
	<input type="radio" id="InfeccionPuerperal" name="InfeccionPuerperal" value="No" <? if($InfeccionPuerperal=="No"){echo "checked";}?>  title="Infección Puerperal" />
    <br>
    <input type="radio" id="Bacteriuria" name="Bacteriuria" value="Si" <? if($Bacteriuria=="Si"){echo "checked";}?>  title="Bacteriuria"  style="background-color:#E6E600" />
	<input type="radio" id="Bacteriuria" name="Bacteriuria" value="No" <? if($Bacteriuria=="No"){echo "checked";}?>  title="Bacteriuria" />    
    </td>
    <td align="center" valign="top" style=" border-bottom-color:#000; border-right-color:#000;">   
    <input type="text" id="CodigoEnfermedad3" name="CodigoEnfermedad3" value="<? echo $CodigoEnfermedad3?>" style="width:50px; font-size:12px; background-color:#FF0;" maxlength="4" onKeyDown="xNumero(this)" onKeyUp="xNumero(this)" title="Codigo Enfermedad 3" /><br>
    No se hizo
    <input type="checkbox" id="BacteriuriaNSH" name="BacteriuriaNSH" value="No se hizo" <? if($BacteriuriaNSH=="No se hizo"){echo "checked";}?>  title="No se hizo"  style="background-color:#E6E600"/>
    </td>
    </tr>
    </table>
    <center><input type="submit" name="Guardar" value="Guardar"></center>
    <script language="javascript">
    if(document.FORMA.EnfermedadesNinguna.checked)
	{
		EnfermedadesNO();		
	}
    </script>
<?
}
?>
</form>
</body>