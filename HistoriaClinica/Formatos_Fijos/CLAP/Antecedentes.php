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
	//$FechaGuard="$ND[year]-$ND[mon]-$ND[mday] $ND[hours]:$ND[minutes] $ND[seconds]";
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
	if($Inscribir)
	{
		$cons="select Identificacion from historiaclinica.InscripcionClaps where Compania='$Compania[0]' and Identificacion='$Paciente[1]' order by identificacion";
		$res=ExQuery($cons);		
		if(ExNumRows($res)==0)
		{
			$cons="Insert into HistoriaClinica.InscripcionClaps (Compania,Identificacion,Estado,FechaInscripcion,UsuarioCrea) values
			('$Compania[0]','$Paciente[1]','AC','$FechaHoy $HoraHoy','$usuario[1]')";	
			$res=ExQuery($cons);	
		}
		else
		{
			$cons="Update HIstoriaClinica.InscripcionClaps set Estado='AC' where Compania='$Compania[0]' and Identificacion='$Paciente[1]'";
			$res=ExQuery($cons);
		}
		$Inscribir="";
		?><script language="javascript">alert("La paciente <? echo $Paciente[2]." ".$Paciente[3]." ".$Paciente[4]." ".$Paciente[5]?> se inscribio correctamente!!!");</script><?								
	}
	if($CrearHoja)
	{
		$cons="select idclap from historiaclinica.claps where Compania='$Compania[0]' order by idclap desc";
		$res=ExQuery($cons);
		$fila=ExFetch($res);
		if($fila[0]){$NewIdClap=$fila[0]+1;}else{$NewIdClap=1;}
		$cons="Insert into HistoriaClinica.Claps (Compania,IdClap,Identificacion,NumServicio,FechaCrea,HoraCrea,UsuarioCrea,Estado) values
		('$Compania[0]',$NewIdClap,'$Paciente[1]',$NumServicio,'$FechaHoy','$HoraHoy','$usuario[1]','AC')";	
		$res=ExQuery($cons);		
		$CrearHoja="";
	}	
	$cons="select identificacion from historiaclinica.inscripcionclaps where Compania='$Compania[0]' and Identificacion='$Paciente[1]'
	and estado='AC' order by identificacion";	
	$res=ExQuery($cons);
	$fila=ExFetch($res); //$Inscrito=$fila[0];
	if(!$fila[0])
	{	
		?>
        	<center>
			<font face='Tahoma' color='#0066FF' size='+1' ><b>
            La Paciente no se encuentra Inscrita en el CLAP!!!<br>
            ¿Desea Inscribir la paciente en CLAP?
            </b></font><br><br>
            <input type="button" name="SI" value="SI" onClick="if(confirm('Se va a inscribir a <? echo $Paciente[2]." ".$Paciente[3]." ".$Paciente[4]." ".$Paciente[5]?> en CLAP \n¿Desea Continuar con la Inscripción?')){location.href='Antecedentes.php?DatNameSID=<? echo $DatNameSID?>&Inscribir=1';}" style="cursor:hand; font-weight:bold; font-size:20px" title="Inscribir">	
            </center>
		<?
		exit;        	
	}
	$cons="select idclap,identificacion,fechacrea,Preantecedentes,Antecedentes from historiaclinica.claps where Compania='$Compania[0]' and Identificacion='$Paciente[1]'
	and estado='AC' order by idclap desc";
	$res=ExQuery($cons);
	$fila=ExFetch($res);
	$IdClap=$fila[0];
	//echo $cons."<br>";
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
            ¿Desea Crear una Nueva Hoja de CLAP para la Paciente?
            </b></font><br><br>
            <input type="button" name="SI" value="SI" onClick="if(confirm('Se va a Crear una Nueva Hoja Materno-Perinatal (CLAP)para <? echo $Paciente[2]." ".$Paciente[3]." ".$Paciente[4]." ".$Paciente[5]?> \n¿Desea Crear la hoja?')){location.href='Antecedentes.php?DatNameSID=<? echo $DatNameSID?>&CrearHoja=1';}" style="cursor:hand; font-weight:bold; font-size:20px" title="Crear Nueva Hoja CLAP">	
            </center>
		<?
        }		
	}
	elseif(!$fila[3])
	{
		echo "<center><font face='Tahoma' color='#0066FF' size='+2' ><b>Debe Diligenciar algunos datos para continuar con los Antecedentes!!! </b></font><br>";		
		?>
        <script language="javascript">
		var PreAntecedentes=1;       
		</script>	
        <?	
	}	
	elseif(!$fila[4])
	{
		$FaltanAnt=1;				
	}
	//---
	if($GuardarA)
	{
		if($FinEmbarazoAnterior=="aaaa-mm-dd"){$FinEmbarazoAnterior="1111-11-11";}
		if($FUM=="aaaa-mm-dd"){$FUM="1111-11-11";}
		if($FPP=="aaaa-mm-dd"){$FPP="1111-11-11";}
		//--
		if(!$GestasPrevias){$GestasPrevias="NULL";}
		if(!$Abortos){$Abortos="NULL";}
		if(!$Vaginales){$Vaginales="NULL";}
		if(!$NacidosVivos){$NacidosVivos="NULL";}
		if(!$Viven){$Viven="NULL";}
		if(!$Partos){$Partos="NULL";}
		if(!$Cesareas){$Cesareas="NULL";}
		if(!$NacidosMuertos){$NacidosMuertos="NULL";}
		if(!$Muertos1Sem){$Muertos1Sem="NULL";}
		if(!$MuertosDespues1Sem){$MuertosDespues1Sem="NULL";}
		if(!$PesoAnterior){$PesoAnterior="NULL";}
		if(!$Talla){$Talla="NULL";}
		if(!$CigarrillosxDia){$CigarrillosxDia=0;}
		if(!$Dosis1MesG){$Dosis1MesG="NULL";}
		if(!$Dosis2MesG){$Dosis2MesG="NULL";}
		//if(!$Hbmen){$Hbmen="NULL";}
		//if(!$Hbmay){$Hbmay="NULL";}
		$cons="UPDATE historiaclinica.claps SET antecedentes='1', antfamiliarestbc='$FamiliaresTBC', 
		antfamiliaresdiabetes='$FamiliaresDiabetes', antfamiliareshipertension='$FamiliaresHipertension', 
		antfamiliarespreclampsia='$FamiliaresPreclampsia', antfamiliareseclampsia='$FamiliaresEclampsia', 
		antfamiliaresotros='$FamiliaresOtros', antpersonalestbc='$PersonalesTBC',antpersonalesdiabetes='$PersonalesDiabetes',
		antpersonaleshipertension='$PersonalesHipertension', antpersonalespreclampsia='$PersonalesPreclampsia', 
		antpersonaleseclampsia='$PersonalesEclampsia', antpersonalesotros='$PersonalesOtros', antcirugiapelvica='$CirugiaPelvica',
		antintensidad='$Intensidad', antvihmas='$VIH', antcariopatianefropatia='$Cardiopatia_Nefropatia', 
		antcondmedicagrave='$CondMedicaGrave', antmolas='$Molas', antectopicos='$Ectopicos', antultimoprevio='$UltPrevio', 
		antgemerales='$Gemerales', antgestasprevias=$GestasPrevias, antabortos=$Abortos, anttresespontconsec='$EspontConsec',
		antpartos=$Partos,  antvaginales=$Vaginales, antcesareas=$Cesareas, antnacidosvivos=$NacidosVivos, 
		antnacidosmuertos=$NacidosMuertos, antviven=$Viven, antmuertosunasem=$Muertos1Sem, antmuertosdespuesunasem=$MuertosDespues1Sem,
		antfinembarazoant='$FinEmbarazoAnterior', antmenunaniomaycinco='$MenAnioMasCinco', antembarazoplaneado='$EmbarazoPlaneado',
		antfracasometodoant='$FracasoMetAnti', actpesoant=$PesoAnterior, acttalla=$Talla, actfum='$FUM', actfpp='$FPP', 
		actegconfiablefum='$EGFUM', actegconfiableeco='$EGECO', actfuma='$Fuma', actcigarrilosxdia='$CigarrillosxDia', actalcohol='$Alcohol',
		actdrogas='$Drogas', actantitetanicavig='$Antitetanica', actantitetanicadosis1=$Dosis1MesG, actantitetanicadosis2=$Dosis2MesG,
		actantirubeola='$AntiRubeola', actexodont='$ExNormalOdont', actexmamas='$ExNormalMamas', actexcervix='$ExNormalCervix',
		actgrupo='$Grupo', actrh='$RH', actsensibil='$Sensibil', actcitologia='$Citologia', actcolposcopia='$Colposcopia',
		actvihconsej='$VIHConsej', actvihsolicitado='$VIHSoli', actvrdlmen20='$VRDLRPRmen', actvrdlmay20='$VRDLRPRmay', 
		actsifilisfta='$SifilisConf', acthbmen20sem='$Hbmen', acthbmen20men12p5g='$HbMen12p5Men', acthbmay20sem='$Hbmay',
		acthbmay20men12p5g='$HbMen12p5May', actagshb='$AgsHB', actiggtoxoplasma='$agGToxoplasma', acttestsullivan='$TestSullivan', 
		actiggrubeola='$IgGRubeola', actversioncefalicaext='$VersionCefalicaExt', fechaantecedentes='$FechaHoy' WHERE Compania='$Compania[0]' 
		and IdClap=$IdClap and Identificacion='$Paciente[1]' and Estado='AC'";	
		$res=ExQuery($cons);
		if(!ExError($res))
		{
			?>
			<script language="javascript">
			alert("Los Antecedentes se han guardado correctamente!!!");
			</script>
			<?		
		}
		else
		{
			?>
			<script language="javascript">
			alert("No se pudo guardar la hoja de antecedentes, es posible que exista un error!!!");
			</script>
			<?	
		}
		if($GestasPrevias=="NULL"){$GestasPrevias="";}
		if($Abortos=="NULL"){$Abortos="";}
		if($Vaginales=="NULL"){$Vaginales="";}
		if($NacidosVivos=="NULL"){$NacidosVivos="";}
		if($Viven=="NULL"){$Viven="";}
		if($Partos=="NULL"){$Partos="";}
		if($Cesareas=="NULL"){$Cesareas="";}
		if($NacidosMuertos=="NULL"){$NacidosMuertos="";}
		if($Muertos1Sem=="NULL"){$Muertos1Sem="";}
		if($MuertosDespues1Sem=="NULL"){$MuertosDespues1Sem="";}
		if($PesoAnterior=="NULL"){$PesoAnterior="";}
		if($Talla=="NULL"){$Talla="";}
		//if($CigarrillosxDia){$CigarrillosxDia=0;}
		if($Dosis1MesG=="NULL"){$Dosis1MesG="";}
		if($Dosis2MesG=="NULL"){$Dosis2MesG="";}
		//if($Hbmen=="NULL"){$Hbmen="";}
		//if($Hbmay=="NULL"){$Hbmay="";}
	}	
	//---
	if($NumServicio&&$IdClap)
	{
		$cons="SELECT antfamiliarestbc, antfamiliaresdiabetes, antfamiliareshipertension, antfamiliarespreclampsia, antfamiliareseclampsia, 
		antfamiliaresotros, antpersonalestbc, antpersonalesdiabetes, antpersonaleshipertension, antpersonalespreclampsia, antpersonaleseclampsia, 
		antpersonalesotros, antcirugiapelvica, antintensidad, antvihmas, antcariopatianefropatia, antcondmedicagrave, antmolas, antectopicos, 
		antultimoprevio, antgemerales, antgestasprevias, antabortos, anttresespontconsec,  antpartos, antvaginales, antcesareas, antnacidosvivos, 
		antnacidosmuertos, antviven, antmuertosunasem, antmuertosdespuesunasem, antfinembarazoant, antmenunaniomaycinco, antembarazoplaneado, 
		antfracasometodoant, actpesoant, acttalla, actfum, actfpp, actegconfiablefum, actegconfiableeco, actfuma, actcigarrilosxdia, actalcohol, 
		actdrogas, actantitetanicavig, actantitetanicadosis1, actantitetanicadosis2, actantirubeola, actexodont, actexmamas, actexcervix, actgrupo, 
		actrh, actsensibil, actcitologia, actcolposcopia, actvihconsej, actvihsolicitado, actvrdlmen20, actvrdlmay20, actsifilisfta, acthbmen20sem, 
		acthbmen20men12p5g, acthbmay20sem, acthbmay20men12p5g, actagshb, actiggtoxoplasma, acttestsullivan, actiggrubeola, actversioncefalicaext
		FROM historiaclinica.claps WHERE Compania='$Compania[0]' and IdClap=$IdClap and Identificacion='$Paciente[1]' and Estado='AC' 
		and Antecedentes='1'";
		$res=ExQuery($cons);
		$fila=ExFetchArray($res);	
		if(!$FamiliaresTBC){$FamiliaresTBC=$fila['antfamiliarestbc'];}	 
		if(!$FamiliaresDiabetes){$FamiliaresDiabetes=$fila['antfamiliaresdiabetes'];}
		if(!$FamiliaresHipertension){$FamiliaresHipertension=$fila['antfamiliareshipertension'];}
		if(!$FamiliaresPreclampsia){$FamiliaresPreclampsia=$fila['antfamiliarespreclampsia'];}
		if(!$FamiliaresEclampsia){$FamiliaresEclampsia=$fila['antfamiliareseclampsia'];}
		if(!$FamiliaresOtros){$FamiliaresOtros=$fila['antfamiliaresotros'];}		
		if(!$PersonalesTBC){$PersonalesTBC=$fila['antpersonalestbc'];}
		if(!$PersonalesDiabetes){$PersonalesDiabetes=$fila['antpersonalesdiabetes'];}
		if(!$PersonalesHipertension){$PersonalesHipertension=$fila['antpersonaleshipertension'];}
		if(!$PersonalesPreclampsia){$PersonalesPreclampsia=$fila['antpersonalespreclampsia'];}
		if(!$PersonalesEclampsia){$PersonalesEclampsia=$fila['antpersonaleseclampsia'];}
		if(!$PersonalesOtros){$PersonalesOtros=$fila['antpersonalesotros'];}
		if(!$CirugiaPelvica){$CirugiaPelvica=$fila['antcirugiapelvica'];}
		if(!$Intensidad){$Intensidad=$fila['antintensidad'];}
		if(!$VIH){$VIH=$fila['antvihmas'];}
		if(!$Cardiopatia_Nefropatia){$Cardiopatia_Nefropatia=$fila['antcariopatianefropatia'];}
		if(!$CondMedicaGrave){$CondMedicaGrave=$fila['antcondmedicagrave'];}	
		if(!$Molas){$Molas=$fila['antmolas'];}
		if(!$Ectopicos){$Ectopicos=$fila['antectopicos'];}
		if(!$UltPrevio){$UltPrevio=$fila['antultimoprevio'];}
		if(!$Gemerales){$Gemerales=$fila['antgemerales'];}
		if(!$GestasPrevias){$GestasPrevias=$fila['antgestasprevias'];}
		if(!$Abortos){$Abortos=$fila['antabortos'];}
		if(!$EspontConsec){$EspontConsec=$fila['anttresespontconsec'];}
		if(!$Partos){$Partos=$fila['antpartos'];}
		if(!$Vaginales){$Vaginales=$fila['antvaginales'];}
		if(!$Cesareas){$Cesareas=$fila['antcesareas'];}
		if(!$NacidosVivos){$NacidosVivos=$fila['antnacidosvivos'];}
		if(!$NacidosMuertos){$NacidosMuertos=$fila['antnacidosmuertos'];}
		if(!$Viven){$Viven=$fila['antviven'];}
		if(!$Muertos1Sem){$Muertos1Sem=$fila['antmuertosunasem'];}
		if(!$MuertosDespues1Sem){$MuertosDespues1Sem=$fila['antmuertosdespuesunasem'];}
		if(!$FinEmbarazoAnterior){$FinEmbarazoAnterior=$fila['antfinembarazoant'];}
		if(!$MenAnioMasCinco){$MenAnioMasCinco=$fila['antmenunaniomaycinco'];}
		if(!$EmbarazoPlaneado){$EmbarazoPlaneado=$fila['antembarazoplaneado'];}
		if(!$FracasoMetAnti){$FracasoMetAnti=$fila['antfracasometodoant'];}	
		if(!$PesoAnterior){$PesoAnterior=$fila['actpesoant'];}	
		if(!$Talla){$Talla=$fila['acttalla'];}
		if(!$FUM){$FUM=$fila['actfum'];}
		if(!$FPP){$FPP=$fila['actfpp'];}
		if(!$EGFUM){$EGFUM=$fila['actegconfiablefum'];}
		if(!$EGECO){$EGECO=$fila['actegconfiableeco'];}
		if(!$Fuma){$Fuma=$fila['actfuma'];}	
		if(!$CigarrillosxDia){$CigarrillosxDia=$fila['actcigarrilosxdia'];}
		if(!$Alcohol){$Alcohol=$fila['actalcohol'];}
		if(!$Drogas){$Drogas=$fila['actdrogas'];}
		if(!$Antitetanica){$Antitetanica=$fila['actantitetanicavig'];}
		if(!$Dosis1MesG){$Dosis1MesG=$fila['actantitetanicadosis1'];}
		if(!$Dosis2MesG){$Dosis2MesG=$fila['actantitetanicadosis2'];}
		if(!$AntiRubeola){$AntiRubeola=$fila['actantirubeola'];}
		if(!$ExNormalOdont){$ExNormalOdont=$fila['actexodont'];}
		if(!$ExNormalMamas){$ExNormalMamas=$fila['actexmamas'];}
		if(!$ExNormalCervix){$ExNormalCervix=$fila['actexcervix'];}
		if(!$Grupo){$Grupo=$fila['actgrupo'];}
		if(!$RH){$RH=$fila['actrh'];}
		if(!$Sensibil){$Sensibil=$fila['actsensibil'];}
		if(!$Citologia){$Citologia=$fila['actcitologia'];}
		if(!$Colposcopia){$Colposcopia=$fila['actcolposcopia'];}
		if(!$VIHConsej){$VIHConsej=$fila['actvihconsej'];}
		if(!$VIHSoli){$VIHSoli=$fila['actvihsolicitado'];}
		if(!$VRDLRPRmen){$VRDLRPRmen=$fila['actvrdlmen20'];}
		if(!$VRDLRPRmay){$VRDLRPRmay=$fila['actvrdlmay20'];} 
		if(!$SifilisConf){$SifilisConf=$fila['actsifilisfta'];}
		if(!$Hbmen){$Hbmen=$fila['acthbmen20sem'];}
		if(!$HbMen12p5Men){$HbMen12p5Men=$fila['acthbmen20men12p5g'];}
		if(!$Hbmay){$Hbmay=$fila['acthbmay20sem'];}
		if(!$HbMen12p5May){$HbMen12p5May=$fila['acthbmay20men12p5g'];}
		if(!$AgsHB){$AgsHB=$fila['actagshb'];}
		if(!$agGToxoplasma){$agGToxoplasma=$fila['actiggtoxoplasma'];}
		if(!$TestSullivan){$TestSullivan=$fila['acttestsullivan'];}
		if(!$IgGRubeola){$IgGRubeola=$fila['actiggrubeola'];}
		if(!$VersionCefalicaExt){$VersionCefalicaExt=$fila['actversioncefalicaext'];}
	}
	if(!$FinEmbarazoAnterior){$FinEmbarazoAnterior="aaaa-mm-dd";}	
	if(!$FUM){$FUM="aaaa-mm-dd";}
	if(!$FPP){$FPP="aaaa-mm-dd";}
	if($FinEmbarazoAnterior=="1111-11-11"){$FinEmbarazoAnterior="aaaa-mm-dd";}
	if($FUM=="1111-11-11"){$FUM="aaaa-mm-dd";}
	if($FPP=="1111-11-11"){$FPP="aaaa-mm-dd";}
?>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<style type="text/css"> body { background-color: transparent } </style>
<script language="javascript" src="/Funciones.js"></script>
<script language='javascript' src="/calendario/popcalendar.js"></script>
<script language="javascript">
function VerPreAntecedentes(VerIdentificacion)
{
	    document.getElementById('FrameFondo').src="Framefondo.php";				
		document.getElementById('FrameFondo').style.position='absolute';
		document.getElementById('FrameFondo').style.top='1px';
		document.getElementById('FrameFondo').style.left='1px';
		document.getElementById('FrameFondo').style.display='';
		document.getElementById('FrameFondo').style.width='100%';
		document.getElementById('FrameFondo').style.height='95%';	
		//---revizar idclap
		document.getElementById('PreAntecedentes').src='PreAntecedentes.php?DatNameSID=<? echo $DatNameSID?>&IdClap=<? echo $IdClap?>&VerIdentificacion='+VerIdentificacion;
		document.getElementById('PreAntecedentes').style.position='absolute';
		document.getElementById('PreAntecedentes').style.top='15%';
		document.getElementById('PreAntecedentes').style.left='18%';
		document.getElementById('PreAntecedentes').style.display='';
		document.getElementById('PreAntecedentes').style.width='65%';
		document.getElementById('PreAntecedentes').style.height='35%';		
}
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
			//if(frm.elements[i].value==""||frm.elements[i].value=="hora"||frm.elements[i].value=="min"){alert("Por favor ingrese el valor correspondiente a "+frm.elements[i].title);frm.elements[i].focus();break;}
			
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
</script>
</head>
<body background="/Imgs/Fondo.jpg">
<form name="FORMA" method="post" onSubmit="return Validar();">
<input type="hidden" name="DatNameSID" value="<? echo $DatNameSID?>">
<input type="hidden" name="IdClap" value="<? echo $IdClap?>">
<!--<input type="button" name="elementos" value="elementos" onClick="RecorrerForm()">-->
<?
if($IdClap)
{	
	echo "<center><font face='Tahoma' color='#0066FF' size='+2' ><b>ANTECEDENTES </b></font><br>";
	?>
    <input type="button" name="VerIdentificacion" value="Identificacion" style="font-size:11px; position:relative; top:0; left:-470; cursor:hand" onClick="VerPreAntecedentes('1')">	
    <table border="1" cellspacing="0" cellpadding="0" bordercolor="#ffffff" style="font : normal normal small-caps 10px Tahoma; width:1058px" align="center">
    <tr align="center" >
    <td style="border-left-color:#000; border-top-color:#000; font-weight:bold" align="right">Familiares</td>
    <td style="border-top-color:#000; font-weight:bold">&nbsp;</td>
    <td align="left" style="border-top-color:#000; font-weight:bold" colspan="2" >Personales</td>
    <td style="border-left-color:#000; border-top-color:#000; font-weight:bold" width="65px">Obstetricos</td>
    <td style="border-top-color:#000" width="70px">Gestas Previas</td>
    <td style="border-top-color:#000;" >Abortos</td>
    <td style="border-top-color:#000;">Vaginales</td>    
    <td style="border-top-color:#000;">Nacidos Vivos</td>
	<td style="border-top-color:#000;" width="60px">Viven</td>   
    <td style="border-top-color:#000;">Fin Embarazo Anterior</td>
    <td rowspan="2" style="border-top-color:#000; border-right-color:#000"><br><input type="checkbox" name="MenAnioMasCinco" value="Menor de 1 año mas de 5 años"<? if($MenAnioMasCinco=="Menor de 1 año mas de 5 años"){echo "checked";}?> style="background-color:#E6E600" title="Menor de 1 año más de 5 años" ><!--<input type="radio" name="MenAnioMasCinco" value="Si" <? if($MenAnioMasCinco=="Si"){echo "checked";}?> style="background-color:#E6E600">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;-->
        <br><font size="-2">menos 1 año mas 5 años</font></td>
    </tr>
    <tr>
    <td rowspan="3" style="border-left-color:#000; border-bottom-color:#000" align="right" width="70px">    	
    	No&nbsp;&nbsp;&nbsp;&nbsp;Si<br>
        <input type="radio" name="FamiliaresTBC" value="No" <? if($FamiliaresTBC=="No"){echo "checked";}?>  title="Antecedentes Familiares TBC"  />
        <input type="radio" name="FamiliaresTBC" value="Si" <? if($FamiliaresTBC=="Si"){echo "checked";}?> style="background-color:#E6E600" title="Antecedentes Familiares TBC"  /><br />   
        <input type="radio" name="FamiliaresDiabetes" value="No" <? if($FamiliaresDiabetes=="No"){echo "checked";}?> title="Antecedentes Familiares Diabetes"   />
        <input type="radio" name="FamiliaresDiabetes" value="Si" <? if($FamiliaresDiabetes=="Si"){echo "checked";}?> style="background-color:#E6E600"  title="Antecedentes Familiares Diabetes" /><br />        
        <input type="radio" name="FamiliaresHipertension" value="No" <? if($FamiliaresHipertension=="No"){echo "checked";}?>  title="Antecedentes Familiares Hipertensión"  />
        <input type="radio" name="FamiliaresHipertension" value="Si" <? if($FamiliaresHipertension=="Si"){echo "checked";}?> style="background-color:#E6E600" title="Antecedentes Familiares Hipertensión"/><br />        
        <input type="radio" name="FamiliaresPreclampsia" value="No" <? if($FamiliaresPreclampsia=="No"){echo "checked";}?>  title="Antecedentes Familiares Preclampsia"/>
        <input type="radio" name="FamiliaresPreclampsia" value="Si" <? if($FamiliaresPreclampsia=="Si"){echo "checked";}?> style="background-color:#E6E600" title="Antecedentes Familiares Preclampsia"/><br />          
        <input type="radio" name="FamiliaresEclampsia" value="No" <? if($FamiliaresEclampsia=="No"){echo "checked";}?> title="Antecedentes Familiares Eclampsia" />
        <input type="radio" name="FamiliaresEclampsia" value="Si" <? if($FamiliaresEclampsia=="Si"){echo "checked";}?> style="background-color:#E6E600" title="Antecedentes Familiares Eclampsia"/><br />
        <input type="radio" name="FamiliaresOtros" value="No" <? if($FamiliaresOtros=="No"){echo "checked";}?> title="Antecedentes Familiares Otros"  />
        <input type="radio" name="FamiliaresOtros" value="Si" <? if($FamiliaresOtros=="Si"){echo "checked";}?> style="background-color:#E6E600" title="Antecedentes Familiares Otros"/><br />        
    </td>
    <td rowspan="3" style="border-bottom-color:#000" align="center" width="81px" valign="middle">
        <br><br>
        <font size="-5">
        <input type="text" value="<------ TBC ------>" style=" font-size:10px; width:80px; text-align:center; border:thin " readonly  /><br>
        <input type="text" value=" <--- Diabetes --->" style=" font-size:10px; width:80px; text-align:center; border:thin;" readonly><br>
        <input type="text" value="<-Hipertensión->" style=" font-size:10px; width:80px; text-align:center; border:thin;" readonly><br>
        <input type="text" value="<-Preclampsia->" style=" font-size:10px; width:80px; text-align:center; border:thin;" readonly><br>
        <input type="text" value="<--- Eclampsia --->" style=" font-size:10px; width:80px; text-align:center; border:thin;" readonly><br>
        <input type="text" value="<----- Otros ----->" style=" font-size:10px; width:80px; text-align:center; border:thin;" readonly><br><br> 
        </font>
    </td>
    <td rowspan="3" style="border-bottom-color:#000;" width="89px">
        No&nbsp;&nbsp;&nbsp;&nbsp;Si<br>        
        <input type="radio" name="PersonalesTBC" value="No" <? if($PersonalesTBC=="No"){echo "checked";}?>  title="Personales TBC" />
        <input type="radio" name="PersonalesTBC" value="Si" <? if($PersonalesTBC=="Si"){echo "checked";}?> style="background-color:#E6E600" title="Personales TBC"/>
        <img src="diabetesclap.png" style="width:40; height:14" title="Diabetes">
        <br />   
        <input type="radio" name="PersonalesDiabetes" value="No" <? if($PersonalesDiabetes=="No"){echo "checked";}?>  title="Personales Diabetes"/>
        <input type="radio" name="PersonalesDiabetes" value="I" <? if($PersonalesDiabetes=="I"){echo "checked";}?> title="Personales Diabetes" style="background-color:#E6E600"/> 
        <input type="radio" name="PersonalesDiabetes" value="II" <? if($PersonalesDiabetes=="II"){echo "checked";}?> title="Personales Diabetes" style="background-color:#E6E600"/>
        <input type="radio" name="PersonalesDiabetes" value="G" <? if($PersonalesDiabetes=="G"){echo "checked";}?> title="Personales Diabetes" style="background-color:#E6E600"/><br />        
        <input type="radio" name="PersonalesHipertension" value="No" <? if($PersonalesHipertension=="No"){echo "checked";}?>  title="Personales Hipertension"/>
        <input type="radio" name="PersonalesHipertension" value="Si" <? if($PersonalesHipertension=="Si"){echo "checked";}?> style="background-color:#E6E600" title="Personales Hipertension"/><br />        
        <input type="radio" name="PersonalesPreclampsia" value="No" <? if($PersonalesPreclampsia=="No"){echo "checked";}?> title="Personales Preclampsia" />
        <input type="radio" name="PersonalesPreclampsia" value="Si" <? if($PersonalesPreclampsia=="Si"){echo "checked";}?> style="background-color:#E6E600" title="Personales Preclampsia"/><br />          
        <input type="radio" name="PersonalesEclampsia" value="No" <? if($PersonalesEclampsia=="No"){echo "checked";}?>  title="Personales Eclampsia"/>
        <input type="radio" name="PersonalesEclampsia" value="Si" <? if($PersonalesEclampsia=="Si"){echo "checked";}?> style="background-color:#E6E600" title="Personales Eclampsia"/><br />
        <input type="radio" name="PersonalesOtros" value="No" <? if($PersonalesOtros=="No"){echo "checked";}?> title="Personales Otros" />
        <input type="radio" name="PersonalesOtros" value="Si" <? if($PersonalesOtros=="Si"){echo "checked";}?> style="background-color:#E6E600" title="Personales Otros"/><br />        
    </td>
    <td rowspan="3" style="border-bottom-color:#000; border-right-color:#000; font-size:10px" align="right" width="155px">
    	No&nbsp;&nbsp;&nbsp;&nbsp;Si&nbsp;<br>
        Cirugia Pélvica
        <input type="radio" name="CirugiaPelvica" value="No" <? if($CirugiaPelvica=="No"){echo "checked";}?> title="Cirugia Pelvica" />
        <input type="radio" name="CirugiaPelvica" value="Si" <? if($CirugiaPelvica=="Si"){echo "checked";}?> style="background-color:#E6E600" title="Cirugia Pelvica"/><br />  
        Intensidad
        <input type="radio" name="Intensidad" value="No" <? if($Intensidad=="No"){echo "checked";}?> title="Intensidad" />
        <input type="radio" name="Intensidad" value="Si" <? if($Intensidad=="Si"){echo "checked";}?> style="background-color:#E6E600" title="Intensidad"/><br />
        VIH+
        <input type="radio" name="VIH" value="No" <? if($VIH=="No"){echo "checked";}?> title="VIH" />
        <input type="radio" name="VIH" value="Si" <? if($VIH=="Si"){echo "checked";}?> style="background-color:#E6E600" title="VIH"/><br />  
        Cardiopatia/Nefropatia
        <input type="radio" name="Cardiopatia_Nefropatia" value="No" <? if($Cardiopatia_Nefropatia=="No"){echo "checked";}?> title="Cardiopatia Nefropatia"/>
        <input type="radio" name="Cardiopatia_Nefropatia" value="Si" <? if($Cardiopatia_Nefropatia=="Si"){echo "checked";}?> style="background-color:#E6E600" title="Cardiopatia Nefropatia"/><br /> 
        Cond. Médica Grave
        <input type="radio" name="CondMedicaGrave" value="No" <? if($CondMedicaGrave=="No"){echo "checked";}?> title="Cond. Medica Grave"/>
        <input type="radio" name="CondMedicaGrave" value="Si" <? if($CondMedicaGrave=="Si"){echo "checked";}?> style="background-color:#E6E600" title="Cond. Medica Grave"/><br />  
        Molas
        <input type="radio" name="Molas" value="No" <? if($Molas=="No"){echo "checked";}?> title="Molas" />
        <input type="radio" name="Molas" value="Si" <? if($Molas=="Si"){echo "checked";}?> style="background-color:#E6E600" title="Molas"/><br />  
        Ectópicos
        <input type="radio" name="Ectopicos" value="No" <? if($Ectopicos=="No"){echo "checked";}?> title="Ectopicos" />
        <input type="radio" name="Ectopicos" value="Si" <? if($Ectopicos=="Si"){echo "checked";}?> style="background-color:#E6E600" title="Ectopicos"/><br />
    </td>
    <td>&nbsp;</td>
    <td align="center">
    	<input type="text" name="GestasPrevias" value="<? echo $GestasPrevias?>" onKeyDown="xNumero(this)" onKeyUp="xNumero(this)" style="width:30px; font-size:11px" maxlength="2">
    </td>
    <td align="center">
    	<input type="text" name="Abortos" value="<? echo $Abortos?>" onKeyDown="xNumero(this)" onKeyUp="xNumero(this)" style="width:30px; font-size:11px" maxlength="2">
    </td>
    <td align="center">
    	<input type="text" name="Vaginales" value="<? echo $Vaginales?>" onKeyDown="xNumero(this)" onKeyUp="xNumero(this)" style="width:30px; font-size:11px" maxlength="2">
    </td>
    <td align="center">
    	<input type="text" name="NacidosVivos" value="<? echo $NacidosVivos?>" onKeyDown="xNumero(this)" onKeyUp="xNumero(this)" style="width:30px; font-size:11px" maxlength="2">
    </td>
    <td align="center">
    	<input type="text" name="Viven" value="<? echo $Viven?>" onKeyDown="xNumero(this)" onKeyUp="xNumero(this)" style="width:30px; font-size:11px" maxlength="2">
    </td>
    <td align="center">
    	<input type="text" name="FinEmbarazoAnterior" value="<? echo $FinEmbarazoAnterior?>" style="width:80px; font-size:12px" maxlength="10" readonly onClick="if(this.value=='aaaa-mm-dd'){this.value='';}popUpCalendar(this, this, 'yyyy-mm-dd')" onBlur="if(this.value==''){this.value='aaaa-mm-dd';}">
    </td>
    <!--<td style="border-right-color:#000;">
    	&nbsp;&nbsp;&nbsp;&nbsp;<input type="radio" name="MenAnioMasCinco" value="Si" <? if($MenAnioMasCinco=="Si"){echo "checked";}?> style="background-color:#E6E600">
        <br><font size="-2">menor 1 año mayor 5 años</font>
    </td>-->
    </tr>
    <tr>
    <td rowspan="2" align="right" style=" border-bottom-color:#000;">
    	<center><font size="-2">Ultimo Previo</font></center><br>
    	<font size="-2">< 2500g</font>
        <input type="checkbox" name="UltPrevio" value="< 2500 g" <? if($UltPrevio=="< 2500 g"){echo "checked";}?> style="background-color:#E6E600"><br>        <font size="-2">> 4000g</font>
        <input type="checkbox" name="UltPrevio" value="> 4000 g" <? if($UltPrevio=="> 4000 g"){echo "checked";}?> style="background-color:#E6E600"><br>        <font size="-2">Gemerales</font>
        <input type="checkbox" name="Gemerales" value="Si"<? if($Gemerales=="Si"){echo "checked";}?> style="background-color:#E6E600" >
	    <!--<input type="radio" name="UltPrevio" value="Si" <? if($UltPrevio=="Gemerales"){echo "checked";}?> style="background-color:#E6E600">--><br>    </td>
    <td rowspan="2" align="center" style=" border-bottom-color:#000;">&nbsp;</td>
    <td rowspan="2" align="center" style=" border-bottom-color:#000;" width="65px">
    <input type="checkbox" name="EspontConsec" value="3 espont. consecutivos"<? if($EspontConsec=="3 espont. consecutivos"){echo "checked";}?> style="background-color:#E6E600" >
    	<!--<input type="radio" name="EspontConsec" value="Si" <? if($EspontConsec=="Si"){echo "checked";}?> style="background-color:#E6E600">-->        
    	<font size="-2">        
        <br>3 espont. consecutivos</font><br>
        <center><font size="-2">Partos</font></center><br>
        <input type="text" name="Partos" value="<? echo $Partos?>" onKeyDown="xNumero(this)" onKeyUp="xNumero(this)" style="width:30px; font-size:11px" maxlength="2">
    </td>
    <td rowspan="2" align="center" style=" border-bottom-color:#000;">
    	<br><br><br>
    	<center><font size="-2">Cesareas</font></center><br>
        <input type="text" name="Cesareas" value="<? echo $Cesareas?>" onKeyDown="xNumero(this)" onKeyUp="xNumero(this)" style="width:15px; font-size:11px" maxlength="1">
    </td>
    <td rowspan="2" align="center" style=" border-bottom-color:#000;">
    <br><br><br><br>
    	<center><font size="-2">Nacidos Muertos</font></center>
        <input type="text" name="NacidosMuertos" value="<? echo $NacidosMuertos?>" onKeyDown="xNumero(this)" onKeyUp="xNumero(this)" style="width:15px; font-size:11px" maxlength="1">
    </td>
    <td align="right">
    	<center><font size="-2">Muertos 1 sem.</font></center>
        <input type="text" name="Muertos1Sem" value="<? echo $Muertos1Sem?>" onKeyDown="xNumero(this)" onKeyUp="xNumero(this)" style="width:15px; font-size:11px" maxlength="1">
    </td>
    <td align="right" >
    	<center><font size="-2">Embarazo Planeado</font></center>        
    </td> 
    <td style=" border-right-color:#000;">
    	<font size="-2">Si</font>  	
        <input type="radio" name="EmbarazoPlaneado" value="Si" <? if($EmbarazoPlaneado=="Si"){echo "checked";}?> title="Embarazo Planeado">
        <font size="-2">No</font>
        <input type="radio" name="EmbarazoPlaneado" value="No" <? if($EmbarazoPlaneado=="No"){echo "checked";}?> style="background-color:#E6E600" title="Embarazo Planeado">    </td>
    </tr>   
    <tr>
    <td align="right" style=" border-bottom-color:#000;" >
    	<br><center><font size="-2">despues 1 sem.</font></center>
        <input type="text" name="MuertosDespues1Sem" value="<? echo $MuertosDespues1Sem?>" onKeyDown="xNumero(this)" onKeyUp="xNumero(this)" style="width:15px; font-size:11px" maxlength="1">
    </td>
    <td align="right" style=" border-bottom-color:#000;" width="125px" >
    	<font size="-2">Fracaso de Metodo</font><br>        
        <input type="checkbox" name="FracasoMetAnti" value="No Usaba" <? if($FracasoMetAnti=="No Usaba"){echo "checked";}?> title="Fracaso de Metodo Anti">	
        <input type="checkbox" name="FracasoMetAnti" value="Barrera" <? if($FracasoMetAnti=="Barrera"){echo "checked";}?> style="background-color:#E6E600" title="Barrera">      
	    <input type="checkbox" name="FracasoMetAnti" value="DIU" <? if($FracasoMetAnti=="DIU"){echo "checked";}?> style="background-color:#E6E600" title="DIU">
        <input type="checkbox" name="FracasoMetAnti" value="Hormonal" <? if($FracasoMetAnti=="Hormonal"){echo "checked";}?> style="background-color:#E6E600" title="Hormonal">    
        <input type="checkbox" name="FracasoMetAnti" value="Emergencia" <? if($FracasoMetAnti=="Emergencia"){echo "checked";}?> style="background-color:#E6E600" title="Emergencia"> 
        <br>
        <font size="-2">no usa  barr  DIU  horm  emerg</font>                           
    </td> 
    <td style=" border-bottom-color:#000; border-right-color:#000;">
    	<font size="-2">Anticonceptivo</font><br> 
        <input type="checkbox" name="FracasoMetAnti" value="Natural" <? if($FracasoMetAnti=="Natural"){echo "checked";}?> style="background-color:#E6E600" title="Natural">      
    	<input type="checkbox" name="FracasoMetAnti" value="Ligadura" <? if($FracasoMetAnti=="Ligadura"){echo "checked";}?> style="background-color:#E6E600" title="Ligadura">   
    	<input type="checkbox" name="FracasoMetAnti" value="No Aplica" <? if($FracasoMetAnti=="No Aplica"){echo "checked";}?> title="No Aplica">
        <br>
        <font size="-2px">natu  ligad  no aplica</font>
    </td>
    </tr>   
    </table>
    
    <!--Gestacion Actual-->
    
    <table border="1" cellspacing="0" cellpadding="0" bordercolor="#ffffff" style="font : normal normal small-caps 10px Tahoma; width:1058px" align="center">
    <tr align="center" >
    <td align="center" colspan="2" style="border-left-color:#000; border-top-color:#000; font-weight:bold" >Gestacion Actual</td>
    <td colspan="2" align="center" style="border-left-color:#000; border-top-color:#000;" >Año-Mes-Dia</td>    
    <td align="left" style="border-left-color:#000;border-top-color:#000; font-weight:bold" width="85px">EG Confiable por</td>
    <td style="border-top-color:#000;" >Fuma</td>
    <td style="border-top-color:#000" >Cigarrillos por dia</td>
    <td rowspan="3" align="right" style="border-top-color:#000; border-bottom-color:#000" >
    No &nbsp; Si&nbsp;<br>
    Alcohol
    <input type="checkbox" name="Alcohol" value="No" <? if($Alcohol=="No"){echo "checked";}?> >
    <input type="checkbox" name="Alcohol" value="Si" <? if($Alcohol=="Si"){echo "checked";}?> style="background-color:#E6E600" >
    <br>    
    Drogas
    <input type="checkbox" name="Drogas" value="No" <? if($Drogas=="No"){echo "checked";}?> >
    <input type="checkbox" name="Drogas" value="Si" <? if($Drogas=="Si"){echo "checked";}?> style="background-color:#E6E600" >   
    </td>
    <td style="border-left-color:#000;border-top-color:#000;">Antitetanica</td>    
    <td style="border-left-color:#000;border-top-color:#000;">Antirubeola</td>
	<td style="border-left-color:#000;border-top-color:#000; border-right-color:#000;" >Ex Normal</td>           
    </tr>    
    <tr>
    <td rowspan="2" align="center" style="border-left-color:#000;border-top-color:#000;">
    Peso Anterior<br>
    <input type="text" name="PesoAnterior" value="<? echo $PesoAnterior?>" style="width:30px; font-size:11px" maxlength="3" onKeyDown="xNumero(this)" onKeyUp="xNumero(this)">Kg
    </td>
    <td rowspan="2" align="center" style="border-left-color:#000;border-top-color:#000;">
    Talla (cm)<br>
    <input type="text" name="Talla" value="<? echo $Talla?>" style="width:30px; font-size:11px" maxlength="3" onKeyDown="xNumero(this)" onKeyUp="xNumero(this)">
    </td>
    <td align="center" style="border-left-color:#000;border-top-color:#000;" >FUM</td>
    <td align="center" style="border-left-color:#000;border-top-color:#000;" >
    <input type="text" name="FUM" value="<? echo $FUM?>" style="width:80px; font-size:12px" maxlength="10" onKeyDown="xLetra(this)" onKeyUp="xLetra(this)" readonly onClick="if(this.value=='aaaa-mm-dd'){this.value='';}popUpCalendar(this, this, 'yyyy-mm-dd')" onBlur="if(this.value==''){this.value='aaaa-mm-dd';}">
    </td>
    <td rowspan="2" align="right" style="border-left-color:#000;border-bottom-color:#000;"  >    
    <font size="-2">FUM &nbsp; ECO<20s<br>
    Si&nbsp;	
    <input type="checkbox" name="EGFUM" value="Si" <? if($EGFUM=="Si"){echo "checked";}?> >&nbsp;&nbsp;&nbsp;&nbsp;
    <input type="checkbox" name="EGECO" value="Si" <? if($EGECO=="Si"){echo "checked";}?> >&nbsp;&nbsp;&nbsp;&nbsp;<br>
    No
    <input type="checkbox" name="EGFUM" value="No" <? if($EGFUM=="No"){echo "checked";}?> style="background-color:#E6E600" >&nbsp;&nbsp;&nbsp;&nbsp; 
    <input type="checkbox" name="EGECO" value="No" <? if($EGECO=="No"){echo "checked";}?> style="background-color:#E6E600" >&nbsp;&nbsp;&nbsp;&nbsp; 
    </font>
    </td>
    <td rowspan="2" align="right" style=" border-bottom-color:#000;" >
    Si
    <input type="checkbox" name="Fuma" value="Si" <? if($Fuma=="Si"){echo "checked";}?> style="background-color:#E6E600" ><br>
    No
    <input type="checkbox" name="Fuma" value="No" <? if($Fuma=="No"){echo "checked";}?> ><br>
    Pasiva
    <input type="checkbox" name="Fuma" value="Pasiva" <? if($Fuma=="Pasiva"){echo "checked";}?> style="background-color:#E6E600" >
    </td>
    <td rowspan="2" align="center" style=" border-bottom-color:#000;" >   
    <input type="text" name="CigarrillosxDia" value="<? echo $CigarrillosxDia?>" style="width:20px; font-size:12px" maxlength="2" onKeyDown="xNumero(this)" onKeyUp="xNumero(this)"><br> 
    0=No fuma   
    </td>
    <td rowspan="2" align="right" style="border-left-color:#000; border-bottom-color:#000;">Vigente&nbsp; 
    Si
      <input type="checkbox" name="Antitetanica" value="Si" <? if($Antitetanica=="Si"){echo "checked";}?> >
    No
    <input type="checkbox" name="Antitetanica" value="No" <? if($Antitetanica=="No"){echo "checked";}?> style="background-color:#E6E600"><br>
    Dosis mes Gestacion&nbsp;
    <sup>1ª</sup><input type="text" name="Dosis1MesG" value="<? echo $Dosis1MesG?>" style="width:15px; font-size:12px" maxlength="1" onKeyDown="xNumero(this)" onKeyUp="xNumero(this)">
    <sup>2ª</sup><input type="text" name="Dosis2MesG" value="<? echo $Dosis2MesG?>" style="width:15px; font-size:12px" maxlength="1" onKeyDown="xNumero(this)" onKeyUp="xNumero(this)">
    </td>
    <td rowspan="2" align="right" style="border-left-color:#000; border-bottom-color:#000;">
    Previa &nbsp; No Sabe<br>   
    <input type="checkbox" name="AntiRubeola" value="Previa" <? if($AntiRubeola=="Previa"){echo "checked";}?>  >&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
    <input type="checkbox" name="AntiRubeola" value="No Sabe" <? if($AntiRubeola=="No Sabe"){echo "checked";}?> style="background-color:#E6E600">&nbsp;&nbsp;<br>
    Embarazo &nbsp;&nbsp;&nbsp;&nbsp; No&nbsp;&nbsp;<br>
    <input type="checkbox" name="AntiRubeola" value="Embarazo" <? if($AntiRubeola=="Embarazo"){echo "checked";}?>  > &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
    <input type="checkbox" name="AntiRubeola" value="No" <? if($AntiRubeola=="No"){echo "checked";}?> style="background-color:#E6E600" >&nbsp;&nbsp;
    </font>
    </td>
    <td rowspan="2" align="right" style="border-left-color:#000; border-bottom-color:#000; border-right-color:#000">
    Si &nbsp; No<br>
    Odont
    <input type="checkbox" name="ExNormalOdont" value="Si" <? if($ExNormalOdont=="Si"){echo "checked";}?> >
    <input type="checkbox" name="ExNormalOdont" value="No" <? if($ExNormalOdont=="No"){echo "checked";}?> style="background-color:#E6E600" >
    <br>    
    Mamas
    <input type="checkbox" name="ExNormalMamas" value="Si" <? if($ExNormalMamas=="Si"){echo "checked";}?> >
    <input type="checkbox" name="ExNormalMamas" value="No" <? if($ExNormalMamas=="No"){echo "checked";}?> style="background-color:#E6E600" >
    <br>    
    Cervix
    <input type="checkbox" name="ExNormalCervix" value="Si" <? if($ExNormalCervix=="Si"){echo "checked";}?> >
    <input type="checkbox" name="ExNormalCervix" value="No" <? if($ExNormalCervix=="No"){echo "checked";}?> style="background-color:#E6E600" >
    </td>    
    </tr>
    <tr>
    <td align="center" style="border-left-color:#000;border-top-color:#000; border-bottom-color:#000" >FPP</td>
    <td align="center" style="border-left-color:#000;border-top-color:#000;border-bottom-color:#000">
    <input type="text" name="FPP" value="<? echo $FPP?>" style="width:80px; font-size:12px" maxlength="10" onKeyDown="xLetra(this)" onKeyUp="xLetra(this)" readonly onClick="if(this.value=='aaaa-mm-dd'){this.value='';}popUpCalendar(this, this, 'yyyy-mm-dd')" onBlur="if(this.value==''){this.value='aaaa-mm-dd';}">
    </td>
    </tr>
    <tr>
    <td rowspan="2" align="right" style="border-left-color:#000; border-bottom-color:#000" width="90px">
    Grupo &nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Rh&nbsp;<br>
    <select name="Grupo" style="font-size:11px">
    <option value="" <? if($Grupo==""){echo "selected";}?>>NA</option>
    <option value="A" <? if($Grupo=="A"){echo "selected";}?>>A</option>
    <option value="B" <? if($Grupo=="B"){echo "selected";}?>>B</option>
    <option value="AB" <? if($Grupo=="AB"){echo "selected";}?>>AB</option>
    <option value="O" <? if($Grupo=="O"){echo "selected";}?>>O</option>
    </select>
    <!--<input type="text" name="Grupo" value="<? echo $Grupo?>" style="width:30px; font-size:11px" maxlength="2" onKeyDown="xLetra(this)" onKeyUp="xLetra(this)">-->&nbsp;
    +    
    <input type="checkbox" name="RH" value="+" <? if($RH=="+"){echo "checked";}?> ><br>
    -
    <input type="checkbox" name="RH" value="-" <? if($RH=="-"){echo "checked";}?> style="background-color:#E6E600" ><br>
    Sensibil.&nbsp;    
    <input type="checkbox" name="Sensibil" value="No" <? if($Sensibil=="No"){echo "checked";}?> style="background-color:#E6E600" >
    <input type="checkbox" name="Sensibil" value="Si" <? if($Sensibil=="Si"){echo "checked";}?>  ><br>
    Si&nbsp;&nbsp;&nbsp;No&nbsp;
    </td>
    <td colspan="2" align="left" style="border-left-color:#000; border-bottom-color:#000; font-size:8px" >
    <font size="-3">Citologia</font><br>
    - 
    <input type="checkbox" name="Citologia" value="-" <? if($Citologia=="-"){echo "checked";}?> >
    +
    <input type="checkbox" name="Citologia" value="+" <? if($Citologia=="+"){echo "checked";}?> style="background-color:#E6E600">
    No se hizo
    <input type="checkbox" name="Citologia" value="No se hizo" <? if($Citologia=="No se hizo"){echo "checked";}?> style="background-color:#E6E600">
    </td>
    <td rowspan="2" align="left" style="border-left-color:#000; border-bottom-color:#000;" width="92px">
    VIH&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Si &nbsp; No<br>
    consej.&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;   
    <input type="checkbox" name="VIHConsej" value="Si" <? if($VIHConsej=="Si"){echo "checked";}?> >    
    <input type="checkbox" name="VIHConsej" value="No" <? if($VIHConsej=="No"){echo "checked";}?> style="background-color:#E6E600"><br>
    solicitado   
    <input type="checkbox" name="VIHSoli" value="Si" <? if($VIHSoli=="Si"){echo "checked";}?> >    
    <input type="checkbox" name="VIHSoli" value="No" <? if($VIHSoli=="No"){echo "checked";}?> style="background-color:#E6E600">
    </td>
    <td rowspan="2" align="center" style="border-left-color:#000; border-bottom-color:#000;" width="96px">
    VRDL/RPR < 20 sem<br>    
    <input type="checkbox" name="VRDLRPRmen" value="-" <? if($VRDLRPRmen=="-"){echo "checked";}?> >    
    <input type="checkbox" name="VRDLRPRmen" value="+" <? if($VRDLRPRmen=="+"){echo "checked";}?> style="background-color:#E6E600">
    <input type="checkbox" name="VRDLRPRmen" value="No se hizo" <? if($VRDLRPRmen=="No se hizo"){echo "checked";}?> style="background-color:#E6E600"><br>
    &nbsp;&nbsp;&nbsp;&nbsp;-
    &nbsp;&nbsp;&nbsp;&nbsp;+&nbsp;&nbsp;No se &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;hizo      
    </td>    
    <td rowspan="2" align="center" style="border-left-color:#000;border-bottom-color:#000;" width="96px">
    VRDL/RPR >= 20 sem<br>    
    <input type="checkbox" name="VRDLRPRmay" value="-" <? if($VRDLRPRmay=="-"){echo "checked";}?> >    
    <input type="checkbox" name="VRDLRPRmay" value="+" <? if($VRDLRPRmay=="+"){echo "checked";}?> style="background-color:#E6E600">
    <input type="checkbox" name="VRDLRPRmay" value="No se hizo" <? if($VRDLRPRmay=="No se hizo"){echo "checked";}?> style="background-color:#E6E600"><br>
    &nbsp;&nbsp;&nbsp;&nbsp;-
    &nbsp;&nbsp;&nbsp;&nbsp;+&nbsp;&nbsp;No se &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;hizo 
    </td>
    <td rowspan="2" align="center" style="border-left-color:#000; border-bottom-color:#000;" width="100px">
    Sifilis confirmada por FTA<br>    
    Si
    <input type="checkbox" name="SifilisConf" value="Si" <? if($SifilisConf=="Si"){echo "checked";}?> >  
    No
    <input type="checkbox" name="SifilisConf" value="No" <? if($SifilisConf=="No"){echo "checked";}?> style="background-color:#E6E600"><br>
    No se hizo
    <input type="checkbox" name="SifilisConf" value="No se hizo" <? if($SifilisConf=="No se hizo"){echo "checked";}?> style="background-color:#E6E600">
    </td>
    <td rowspan="2" align="center" style="border-left-color:#000; border-bottom-color:#000;" width="83px">
    Hb < 20 sem.<br>    
    <input type="text" name="Hbmen" value="<? echo $Hbmen?>" style="width:30px; font-size:11px" maxlength="4" onKeyDown="xNumero(this)" onKeyUp="xNumero(this)">   <br>
    < 12.5g
    <input type="checkbox" name="HbMen12p5Men" value="< 12.5 g"<? if($HbMen12p5Men=="< 12.5 g"){echo "checked";}?> style="background-color:#E6E600" >
    <!--<input type="radio" name="HbMen12p5Men" value="<? echo $HbMen12p5Men?>" <? if($HbMen12p5Men=="< 12.5 g"){echo "checked";}?>style="background-color:#E6E600">-->    
    </td>
    <td rowspan="2" align="left" style="border-left-color:#000; border-bottom-color:#000;">
    &nbsp;&nbsp;Hb >= 20 sem.&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;AgsHB<br>    
    &nbsp;&nbsp;<input type="text" name="Hbmay" value="<? echo $Hbmay?>" style="width:30px; font-size:11px" maxlength="4" onKeyDown="xNumero(this)" onKeyUp="xNumero(this)"> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;    
    <input type="checkbox" name="AgsHB" value="-" <? if($AgsHB=="-"){echo "checked";}?>>-
    <input type="checkbox" name="AgsHB" value="+" <? if($AgsHB=="+"){echo "checked";}?> style="background-color:#E6E600">+
    <br>
    &nbsp;< 12.5g
    <input type="checkbox" name="HbMen12p5May" value="< 12.5 g"<? if($HbMen12p5May=="< 12.5 g"){echo "checked";}?> style="background-color:#E6E600" >
    <!--<input type="radio" name="HbMen12.5May" value="<? echo $HbMen12p5May?>" <? if($HbMen12p5May=="< 12.5 g"){echo "checked";}?>style="background-color:#E6E600">-->
   IgG Toxoplasma<br>
    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="checkbox" name="agGToxoplasma" value="-" <? if($agGToxoplasma=="-"){echo "checked";}?>>-
    <input type="checkbox" name="agGToxoplasma" value="+" <? if($agGToxoplasma=="+"){echo "checked";}?> style="background-color:#E6E600">+
    </td>
    <td rowspan="2" colspan="2" align="left" style="border-left-color:#000; border-right-color:#000; border-bottom-color:#000;" width="210px">   
    Test o Sullivan&nbsp;&nbsp;IgG Rubeola&nbsp;&nbsp;Version Cefalica &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Externa<br>
    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<font style="font-size:8px">Intento a termino</font><br>
    <input type="checkbox" name="TestSullivan" value="<= 135" <? if($TestSullivan=="<= 135"){echo "checked";}?>><= 135
    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Si
    <input type="checkbox" name="IgGRubeola" value="Si" <? if($IgGRubeola=="Si"){echo "checked";}?>>
    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Si
    <input type="checkbox" name="VersionCefalicaExt" value="Si" <? if($VersionCefalicaExt=="Si"){echo "checked";}?>>
    <br>    
    <input type="checkbox" name="TestSullivan" value="135 - 199" <? if($TestSullivan=="135 - 199"){echo "checked";}?> style="background-color:#E6E600">136 - 199
    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;No
    <input type="checkbox" name="IgGRubeola" value="No" <? if($IgGRubeola=="No"){echo "checked";}?> style="background-color:#E6E600">
    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;No
    <input type="checkbox" name="VersionCefalicaExt" value="No" <? if($VersionCefalicaExt=="No"){echo "checked";}?> style="background-color:#E6E600">
    <br>
    <input type="checkbox" name="TestSullivan" value="> 200" <? if($TestSullivan=="> 200"){echo "checked";}?> style="background-color:#E6E600">
    >200 
    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;n/c
    <input type="checkbox" name="VersionCefalicaExt" value="n/c" <? if($VersionCefalicaExt=="n/c"){echo "checked";}?>>
    </td>
    </tr>
    <tr>
    <td colspan="2" align="left" style="border-left-color:#000; border-bottom-color:#000; font-size:8px" >
    <font size="-3">Colposcopia</font><br>
    - 
    <input type="checkbox" name="Colposcopia" value="-" <? if($Colposcopia=="-"){echo "checked";}?> >
    +
    <input type="checkbox" name="Colposcopia" value="+" <? if($Colposcopia=="+"){echo "checked";}?> style="background-color:#E6E600">
    No se hizo
    <input type="checkbox" name="Colposcopia" value="No se hizo" <? if($Colposcopia=="No se hizo"){echo "checked";}?> style="background-color:#E6E600">
    </td>
    </tr>
    </table>
    <center><font color="#0080C0" size="-1"><b>El Color Amarillo Significa Alerta</b></font></center>	    
    <center><input type="submit" name="GuardarA" value="Guardar"></center>
<?
}?>
</form>
<iframe scrolling="no" id="FrameFondo" name="FrameFondo" frameborder="0" height="0" width="0" style="filter:Alpha(Opacity=200, FinishOpacity=40, Style=2, StartX=20, StartY=40, FinishX=0, FinishY=0);display:none;border:thin; background-color:transparent" ></iframe>
<iframe scrolling="yes" id="PreAntecedentes" name="PreAntecedentes" frameborder="0" height="1" style="display:none;border:#e5e5e5; border-style:solid; "></iframe>
<script language="javascript">
if(PreAntecedentes==1)
{
	VerPreAntecedentes('');	
	PreAntecedentes="";
}
</script>
</body>