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
	//--
	if($IdClap)
	{
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
	elseif(!$fila[5])
	{
		echo "<center><font face='Tahoma' color='#0066FF' size='+2' ><b>No se han Registrado datos en Parto/Aborto para continuar con esta Hoja!!! </b></font><br>";	
		exit;
	}
	else
	{
		$LlenaRN=1;	
	}	
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
<?
if($LlenaRN&&$NumServicio)
{	
    echo "<center><font face='Tahoma' color='#0066FF' size='+2' ><b>RECIEN NACIDO</b></font><br>";
	?>
    <table border="1" cellspacing="0" cellpadding="0" bordercolor="#ffffff" style="font : normal normal small-caps 8px Tahoma; width:1058px" align="center">
    <tr align="center" >
    <td style="border-left-color:#000; border-top-color:#000; ">SEXO</td>
    <td style="border-left-color:#000; border-top-color:#000; ">Peso al Nacer</td>  
    <td style="border-left-color:#000; border-top-color:#000; ">Per. Cefalico</td>  
    <td colspan="2" style="border-left-color:#000; border-top-color:#000; ">E.G Confiable</td>  
    <td style="border-left-color:#000; border-top-color:#000; ">Peso E.G.</td>  
    <td style="border-left-color:#000; border-top-color:#000; ">APGAR</td>  
    <td style="border-left-color:#000; border-top-color:#000; ">REANIMACIÓN</td>
    <td style="border-top-color:#000; " align="left">&nbsp;&nbsp;No&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Si</td>  
    <td style="border-left-color:#000; border-top-color:#000; ">Fallece en Sala de parto</td>  
    <td style="border-left-color:#000; border-top-color:#000; ">ATENDIÓ</td>  
    <td style="border-top-color:#000; ">Medico</td>  
    <td style="border-top-color:#000; ">Enf.</td>  
    <td style="border-top-color:#000; ">Auxil.</td>  
    <td style="border-top-color:#000; ">Estud.</td>  
    <td style="border-top-color:#000; ">Empir.</td>  
    <td style="border-top-color:#000; ">Otro</td>  
    <td style="border-left-color:#000; border-top-color:#000; border-right-color:#000; ">Nombre del Profesional</td>  
    </tr>
    <tr align="center">
    <td rowspan="2" align="right"  style="border-left-color:#000; ">
    F&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;M&nbsp;&nbsp;<br>
    <input type="radio" id="Sexo" name="Sexo" value="F" <? if($Sexo=="F"){echo "checked";}?>  title="Sexo Recien Nacido"/>
    <input type="radio" id="Sexo" name="Sexo" value="M" <? if($Sexo=="M"){echo "checked";}?>  title="Sexo Recien Nacido"/><br>
    No Definido
    <input type="radio" id="Sexo" name="Sexo" value="No definido" <? if($Sexo=="No definido"){echo "checked";}?>  title="Sexo Recien Nacido" style="background-color:#E6E600"/>
    </td>
    <td rowspan="2" style="border-left-color:#000; ">
    <input type="text" id="PesoNacer" name="PesoNacer" value="<? echo $PesoNacer?>" style="width:35px; font-size:11px" maxlength="4" onKeyDown="xNumero(this)" onKeyUp="xNumero(this)" title="Peso al Nacer">g<br>
    menor 2500 g 
    <input type="checkbox" id="PesoMenor2500g" name="PesoMenor2500g" value="menor 2500 g"<? if($PesoMenor2500g=="menor 2500 g"){echo "checked";}?> style="background-color:#E6E600" title="Peso menor a 2500 g" >
    </td>
    <td style="border-left-color:#000; ">
    <input type="text" id="PerCefalico" name="PerCefalico" value="<? echo $PerCefalico?>" style="width:30px; font-size:11px" maxlength="3" onKeyDown="xNumero(this)" onKeyUp="xNumero(this)" title="Per Cefalico"> cm
    </td>
    <td style="border-left-color:#000; ">
    Sem<br>
    <input type="text" id="EGConfiableSemanasRN" name="EGConfiableSemanasRN" value="<? echo $EGConfiableSemanasRN?>" style="width:25px; font-size:11px" maxlength="2" onKeyDown="xNumero(this)" onKeyUp="xNumero(this)" title="Semanas E.G. Confiable">  
    </td>
    <td>Dias<br>
    <input type="text" id="EGConfiableDiasRN" name="EGConfiableDiasRN" value="<? echo $EGConfiableDiasRN?>" style="width:20px; font-size:11px" maxlength="" onKeyDown="xNumero(this)" onKeyUp="xNumero(this)" title="Dias E.G. Confiable">  
    </td>
    <td rowspan="2"  style="border-left-color:#000; " align="right">
    adec.<input type="radio" id="PesoEGRN" name="PesoEGRN" value="Adecuado" <? if($Sexo=="Adecuado"){echo "checked";}?>  title="Peso E.G."/><br>
    peq.<input type="radio" id="PesoEGRN" name="PesoEGRN" value="Pequeño" <? if($PesoEGRN=="Pequeño"){echo "checked";}?>  title="Peso E.G." style="background-color:#E6E600"/><br>
    Gde.<input type="radio" id="PesoEGRN" name="PesoEGRN" value="Grande" <? if($PesoEGRN=="Grande"){echo "checked";}?>  title="Peso E.G." style="background-color:#E6E600"/>
    </td>
    <td style="border-left-color:#000; ">
    1er min
    <input type="text" id="APGAR1erm" name="APGAR1erm" value="<? echo $APGAR1erm?>" style="width:20px; font-size:11px" maxlength="2" onKeyDown="xNumero(this);if(parseInt(this.value)>10){this.value=10;}" onKeyUp="xNumero(this);if(parseInt(this.value)>10){this.value=10;}" title="APGAR primer minuto">
    </td>
    <td style="border-left-color:#000; " align="right" valign="top">
    <br>
    Flujo libre O<sub>2</sub><br>
    Ventilacion Presion+<br><br>
    Intubación OT   
    </td>
    <td align="left" valign="top">    
    <input type="radio" id="FlujolibreO2" name="FlujolibreO2" value="No" <? if($FlujolibreO2=="No"){echo "checked";}?>  title="Flujo Libre O2"/>
    <input type="radio" id="FlujolibreO2" name="FlujolibreO2" value="Si" <? if($FlujolibreO2=="Si"){echo "checked";}?>  title="Flujo Libre O2" style="background-color:#E6E600"/><br>
    <input type="radio" id="VentilacionPresion" name="VentilacionPresion" value="No" <? if($VentilacionPresion=="No"){echo "checked";}?>  title="Ventilación presion +"/>
    <input type="radio" id="VentilacionPresion" name="VentilacionPresion" value="Si" <? if($VentilacionPresion=="Si"){echo "checked";}?>  title="Ventilación presion +" style="background-color:#E6E600"/><br>
    <input type="radio" id="IntubacionOT" name="IntubacionOT" value="No" <? if($IntubacionOT=="No"){echo "checked";}?>  title="Intubación OT"/>
    <input type="radio" id="IntubacionOT" name="IntubacionOT" value="Si" <? if($IntubacionOT=="Si"){echo "checked";}?>  title="Intubación OT" style="background-color:#E6E600"/>
    </td>
    <td style="border-left-color:#000; ">
    Si
    <input type="radio" id="FalleceSalaParto" name="FalleceSalaParto" value="Si" <? if($FalleceSalaParto=="Si"){echo "checked";}?>  title="Fallece en sala de parto" style="background-color:#E6E600"/>
    No
    <input type="radio" id="FalleceSalaParto" name="FalleceSalaParto" value="No" <? if($FalleceSalaParto=="No"){echo "checked";}?>  title="Fallece en sala de parto"/>
    </td>
    <td style="border-left-color:#000; border-top-color:#000; ">PARTO</td>
    <td style="border-top-color:#000; ">
    <input type="radio" id="AtendioParto" name="AtendioParto" value="Medico" <? if($AtendioParto=="Medico"){echo "checked";}?>  title="Atendio Parto" />
    </td>
    <td style="border-top-color:#000; ">
    <input type="radio" id="AtendioParto" name="AtendioParto" value="Enfermera" <? if($AtendioParto=="Enfermera"){echo "checked";}?>  title="Atendio Parto" />
    </td>
    <td style="border-top-color:#000; ">
    <input type="radio" id="AtendioParto" name="AtendioParto" value="Auxiliar" <? if($AtendioParto=="Auxiliar"){echo "checked";}?>  title="Atendio Parto" />
    </td>
    <td style="border-top-color:#000; ">
    <input type="radio" id="AtendioParto" name="AtendioParto" value="Estudiante" <? if($AtendioParto=="Estudiante"){echo "checked";}?>  title="Atendio Parto" style="background-color:#E6E600"/>
    </td>
    <td style="border-top-color:#000; ">
    <input type="radio" id="AtendioParto" name="AtendioParto" value="Empirica" <? if($AtendioParto=="Empirica"){echo "checked";}?>  title="Atendio Parto" style="background-color:#E6E600"/>
    </td>
    <td style="border-top-color:#000; ">
    <input type="radio" id="AtendioParto" name="AtendioParto" value="Otro" <? if($AtendioParto=="Otro"){echo "checked";}?>  title="Atendio Parto" style="background-color:#E6E600"/>
    </td>
    <td style=" border-left-color:#000;border-top-color:#000; border-right-color:#000; ">
    <input type="text" id="ProfesionalParto" name="ProfesionalParto" value="<? echo $ProfesionalParto?>" style="width:160px; font-size:11px" maxlength="40" onKeyDown="xLetra(this)" onKeyUp="xLetra(this)" title="Nombre Profesional Parto"> 
    </td>
    </tr>
    <tr align="center">
    <td style="border-left-color:#000; border-top-color:#000; ">
    Talla<br>
    <input type="text" id="TallaRN" name="TallaRN" value="<? echo $TallaRN?>" style="width:30px; font-size:11px" maxlength="3" onKeyDown="xNumero(this)" onKeyUp="xNumero(this)" title="Talla Recien Nacido"> cm
    </td>
    <td colspan="2" style="border-left-color:#000;">
    <input type="checkbox" id="EGFUM" name="EGFUM" value="FUM"<? if($EGFUM=="FUM"){echo "checked";}?> title="Edad Gestacional RN - FUM" >FUM
    <input type="checkbox" id="EGECO" name="EGECO" value="ECO"<? if($EGECO=="ECO"){echo "checked";}?> title="Edad Gestacional RN - ECO" >ECO<br>
    <input type="checkbox" id="EGEstimada" name="EGEstimada" value="Estimada"<? if($EGEstimada=="Estimada"){echo "checked";}?> style="background-color:#E6E600" title="Edad Gestacional RN - Estimada" >Estimada
    </td>
    <td style="border-left-color:#000; ">
    5o min
    <input type="text" id="APGAR5om" name="APGAR5om" value="<? echo $APGAR5om?>" style="width:20px; font-size:11px" maxlength="2" onKeyDown="xNumero(this);if(parseInt(this.value)>10){this.value=10;}" onKeyUp="xNumero(this);if(parseInt(this.value)>10){this.value=10;}" title="APGAR quinto minuto">
    </td>
    <td style="border-left-color:#000; " align="right" valign="top">
   	<br>
    Masaje Cardiaco<br><br>
    Adrenalina<br>    
    Ninguna<input type="checkbox" id="ReanimacionNinguna" name="ReanimacionNinguna" value="Ninguna" <? if($ReanimacionNinguna=="Ninguna"){echo "checked";}?>  title="Reanimación" /> 
    </td>
    <td align="left" valign="top">    
    <input type="radio" id="MasajeCardiaco" name="MasajeCardiaco" value="No" <? if($MasajeCardiaco=="No"){echo "checked";}?>  title="Masaje Cardiaco"/>
    <input type="radio" id="MasajeCardiaco" name="MasajeCardiaco" value="Si" <? if($MasajeCardiaco=="Si"){echo "checked";}?>  title="Masaje Cardiaco" style="background-color:#E6E600"/><br>
    <input type="radio" id="Adrenalina" name="Adrenalina" value="No" <? if($Adrenalina=="No"){echo "checked";}?>  title="Adrenalina"/>
    <input type="radio" id="Adrenalina" name="Adrenalina" value="Si" <? if($Adrenalina=="Si"){echo "checked";}?>  title="Adrenalina" style="background-color:#E6E600"/>		    </td>
    <td style="border-left-color:#000; border-top-color:#000; ">
    REFERIDO<BR>
    Madre-hijo&nbsp;&nbsp;Hosp.&nbsp;&nbsp;Otro Hosp.<br>
    <input type="radio" id="Referido" name="Referido" value="Madre - hijo" <? if($Referido=="Madre - hijo"){echo "checked";}?>  title="Referido" />	
    <input type="radio" id="Referido" name="Referido" value="Hospital" <? if($Referido=="Hospital"){echo "checked";}?>  title="Referido" style="background-color:#E6E600"/>	
    <input type="radio" id="Referido" name="Referido" value="Otro Hospital" <? if($Referido=="Otro Hospital"){echo "checked";}?>  title="Referido" style="background-color:#E6E600"/>	
    </td>
    <td style="border-left-color:#000; border-top-color:#000; ">NEONATO</td>
    <td style="border-top-color:#000; ">
    <input type="radio" id="AtendioNeonato" name="AtendioNeonato" value="Medico" <? if($AtendioNeonato=="Medico"){echo "checked";}?>  title="Atendio Neonato" />
    </td>
    <td style="border-top-color:#000; ">
    <input type="radio" id="AtendioNeonato" name="AtendioNeonato" value="Enfermera" <? if($AtendioNeonato=="Enfermera"){echo "checked";}?>  title="Atendio Neonato" />
    </td>
    <td style="border-top-color:#000; ">
    <input type="radio" id="AtendioNeonato" name="AtendioNeonato" value="Auxiliar" <? if($AtendioNeonato=="Auxiliar"){echo "checked";}?>  title="Atendio Neonato" />
    </td>
    <td style="border-top-color:#000; ">
    <input type="radio" id="AtendioNeonato" name="AtendioNeonato" value="Estudiante" <? if($AtendioNeonato=="Estudiante"){echo "checked";}?>  title="Atendio Neonato" style="background-color:#E6E600"/>
    </td>
    <td style="border-top-color:#000; ">
    <input type="radio" id="AtendioNeonato" name="AtendioNeonato" value="Empirica" <? if($AtendioNeonato=="Empirica"){echo "checked";}?>  title="Atendio Neonato" style="background-color:#E6E600"/>
    </td>
    <td style="border-top-color:#000; ">
    <input type="radio" id="AtendioNeonato" name="AtendioNeonato" value="Otro" <? if($AtendioNeonato=="Otro"){echo "checked";}?>  title="Atendio Neonato" style="background-color:#E6E600"/>
    </td>
    <td style=" border-left-color:#000;border-top-color:#000; border-right-color:#000;">
    <input type="text" id="ProfesionalParto" name="ProfesionalNeonato" value="<? echo $ProfesionalNeonato?>" style="width:160px; font-size:11px" maxlength="40" onKeyDown="xLetra(this)" onKeyUp="xLetra(this)" title="Nombre Profesional Neonato"> 
    </td>
    </tr>
    <tr align="center">
    <td style=" border-left-color:#000;border-top-color:#000;">
    DEFECTOS CONGENITOS
    </td>
    <td colspan="4" style=" border-left-color:#000;border-top-color:#000;"><strong>ENFERMEDADES</strong>&nbsp;&nbsp;&nbsp;Ninguna<input type="checkbox" id="EnfermedadesRN" name="EnfermedadesRN" value="Ninguna" <? if($EnfermedadesRN=="Ninguna"){echo "checked";}?>  title="Enfermedades" /></td>
    <td colspan="6" style=" border-left-color:#000;border-top-color:#000;">
    TAMIZACION NEONATAL NORMAL
    </td>
    <TD colspan="7" style=" border-left-color:#000;border-top-color:#000; border-right-color:#000;"><strong>PUERPERIO</strong></TD>
    </tr>
    <tr align="center">
    <td rowspan="4" style=" border-left-color:#000;">    
    <input type="radio" id="DefectosCongenitos" name="DefectosCongenitos" value="No" <? if($DefectosCongenitos=="No"){echo "checked";}?>  title="Defectos Congenitos"/>No &nbsp;&nbsp;&nbsp;<br>   
    <input type="radio" id="DefectosCongenitos" name="DefectosCongenitos" value="menor" <? if($DefectosCongenitos=="menor"){echo "checked";}?>  title="Defectos Congenitos" style="background-color:#E6E600"/>Menor<br>
    <input type="radio" id="DefectosCongenitos" name="DefectosCongenitos" value="mayor" <? if($DefectosCongenitos=="mayor"){echo "checked";}?>  title="Defectos Congenitos" style="background-color:#E6E600"/>Mayor<br>
    <input type="text" id="CodigoDefectosCongenitos" name="CodigoDefectosCongenitos" value="<? echo $CodigoDefectosCongenitos?>" style="width:50px; font-size:12px; background-color:#FF0;" maxlength="4" onKeyDown="xNumero(this)" onKeyUp="xNumero(this)" title="Codigo Defectos Congenitos" />
    </td>
    <td colspan="4" rowspan="4" style=" border-left-color:#000;">
    <input type="text" id="CodigoEnfermedadRN1" name="CodigoEnfermedadRN1" value="<? echo $CodigoEnfermedadRN1?>" style="width:50px; font-size:12px; background-color:#FF0;" maxlength="4" onKeyDown="xNumero(this)" onKeyUp="xNumero(this)" title="Codigo Enfermedad 1" />
    <input type="text" id="NotasEnfermedadRN1" name="NotasEnfermedadRN1" value="<? echo $NotasEnfermedadRN1?>" style="width:150px; font-size:11px;" maxlength="999" onKeyDown="xLetra(this)" onKeyUp="xLetra(this)" title="Notas Enfermedad 1" /><br>
    <input type="text" id="CodigoEnfermedadRN2" name="CodigoEnfermedadRN2" value="<? echo $CodigoEnfermedadRN2?>" style="width:50px; font-size:12px; background-color:#FF0;" maxlength="4" onKeyDown="xNumero(this)" onKeyUp="xNumero(this)" title="Codigo Enfermedad 2" />
    <input type="text" id="NotasEnfermedadRN2" name="NotasEnfermedadRN2" value="<? echo $NotasEnfermedadRN2?>" style="width:150px; font-size:11px;" maxlength="999" onKeyDown="xLetra(this)" onKeyUp="xLetra(this)" title="Notas Enfermedad 1" /><br>
    <input type="text" id="CodigoEnfermedadRN3" name="CodigoEnfermedadRN3" value="<? echo $CodigoEnfermedadRN3?>" style="width:50px; font-size:12px; background-color:#FF0;" maxlength="4" onKeyDown="xNumero(this)" onKeyUp="xNumero(this)" title="Codigo Enfermedad 3" />
    <input type="text" id="NotasEnfermedadRN3" name="NotasEnfermedadRN3" value="<? echo $NotasEnfermedadRN3?>" style="width:150px; font-size:11px;" maxlength="999" onKeyDown="xLetra(this)" onKeyUp="xLetra(this)" title="Notas Enfermedad 1" />
    </td>
    <td style=" border-left-color:#000;">&nbsp;
    
    </td>
    <td>
    VDRL
    </td>
    <td>
    TSH&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Hbpatia    
    </td>
    <td>
    Audición
    </td>
    <td>
    Hiper Bilir.&nbsp;&nbsp;&nbsp;Meconio 1er dia
    </td>
    <td>
    Boca arriba    
    </td>
    <td colspan="2" style=" border-left-color:#000; border-top-color:#000;">
    hora min
    </td>
    <td style=" border-left-color:#000; border-top-color:#000;">
    T ºC
    </td>
    <td style=" border-left-color:#000; border-top-color:#000;">
    pulso
    </td>
    <td style=" border-left-color:#000; border-top-color:#000;">
    PA
    </td>
    <td style=" border-left-color:#000; border-top-color:#000;">
    invol uter
    </td>
    <td style=" border-left-color:#000; border-top-color:#000; border-right-color:#000;" align="left">
    &nbsp;loquios
    </td>
    </tr>
    <tr align="center">
    <td rowspan="3" align="right" style=" border-left-color:#000; border-bottom-color:#000">
    Si<br>
    No<br>
    No Se Hizo<br>
    Pend. Result.   
    </td>
    <td rowspan="3" style="border-bottom-color:#000">
    <input type="radio" id="VRDL" name="VRDL" value="Si" <? if($VRDL=="Si"){echo "checked";}?>  title="VRDL"/><br>
    <input type="radio" id="VRDL" name="VRDL" value="No" <? if($VRDL=="No"){echo "checked";}?>  title="VRDL" style="background-color:#E6E600"/><br>
    <input type="radio" id="VRDL" name="VRDL" value="No se hizo" <? if($VRDL=="No se hizo"){echo "checked";}?>  title="VRDL" style="background-color:#E6E600"/><br>
    <input type="radio" id="VRDL" name="VRDL" value="Pendiente Resultados" <? if($VRDL=="Pendiente Resultados"){echo "checked";}?>  title="VRDL" style="background-color:#E6E600"/>
    </td>
    <td rowspan="3" style="border-bottom-color:#000">
    <input type="radio" id="TSH" name="TSH" value="Si" <? if($TSH=="Si"){echo "checked";}?>  title="TSH"/>&nbsp;&nbsp;
    <input type="radio" id="Hbpatia" name="Hbpatia" value="Si" <? if($Hbpatia=="Si"){echo "checked";}?>  title="Hbpatia"/><br>
    <input type="radio" id="TSH" name="TSH" value="No" <? if($TSH=="No"){echo "checked";}?>  title="TSH" style="background-color:#E6E600"/>&nbsp;&nbsp;
    <input type="radio" id="Hbpatia" name="Hbpatia" value="No" <? if($Hbpatia=="No"){echo "checked";}?>  title="Hbpatia" style="background-color:#E6E600"/><br>
    <input type="radio" id="TSH" name="TSH" value="No se hizo" <? if($TSH=="No se hizo"){echo "checked";}?>  title="TSH" style="background-color:#E6E600"/>&nbsp;&nbsp;
    <input type="radio" id="Hbpatia" name="Hbpatia" value="No se hizo" <? if($Hbpatia=="No se hizo"){echo "checked";}?>  title="Hbpatia" style="background-color:#E6E600"/><br>
    <input type="radio" id="TSH" name="TSH" value="Pendiente Resultados" <? if($TSH=="Pendiente Resultados"){echo "checked";}?>  title="TSH" style="background-color:#E6E600"/>&nbsp;&nbsp;
    <input type="radio" id="Hbpatia" name="Hbpatia" value="Pendiente Resultados" <? if($Hbpatia=="Pendiente Resultados"){echo "checked";}?>  title="Hbpatia" style="background-color:#E6E600"/>
    </td>
    <td rowspan="3" style="border-bottom-color:#000">
    <input type="radio" id="Audicion" name="Audicion" value="Si" <? if($Audicion=="Si"){echo "checked";}?>  title="Audicion"/><br>
    <input type="radio" id="Audicion" name="Audicion" value="No" <? if($Audicion=="No"){echo "checked";}?>  title="Audicion" style="background-color:#E6E600"/><br>
    <input type="radio" id="Audicion" name="Audicion" value="No se hizo" <? if($Audicion=="No se hizo"){echo "checked";}?>  title="Audicion" style="background-color:#E6E600"/><br>
    <input type="radio" id="Audicion" name="Audicion" value="Pendiente Resultados" <? if($Audicion=="Pendiente Resultados"){echo "checked";}?>  title="Audicion" style="background-color:#E6E600"/>
    </td>
    <td rowspan="3" style="border-bottom-color:#000">
    <input type="radio" id="HiperBilir" name="HiperBilir" value="Si" <? if($HiperBilir=="Si"){echo "checked";}?>  title="Hiper Bilir"/>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
    <input type="radio" id="Meconio1erDia" name="Meconio1erDia" value="Si" <? if($Meconio1erDia=="Si"){echo "checked";}?>  title="Meconio 1er Dia"/><br>
    <input type="radio" id="HiperBilir" name="HiperBilir" value="No" <? if($HiperBilir=="No"){echo "checked";}?>  title="Hiper Bilir" style="background-color:#E6E600"/>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
    <input type="radio" id="Meconio1erDia" name="Meconio1erDia" value="No" <? if($Meconio1erDia=="No"){echo "checked";}?>  title="Meconio 1er Dia" style="background-color:#E6E600"/><br>
    <input type="radio" id="HiperBilir" name="HiperBilir" value="No se hizo" <? if($HiperBilir=="No se hizo"){echo "checked";}?>  title="Hiper Bilir" style="background-color:#E6E600"/>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<br>
    <input type="radio" id="HiperBilir" name="HiperBilir" value="Pendiente Resultados" <? if($HiperBilir=="Pendiente Resultados"){echo "checked";}?>  title="Hiper Bilir" style="background-color:#E6E600"/> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
    </td>
    <td rowspan="3" valign="top" style="border-bottom-color:#000">
    <input type="radio" id="BocaArriba" name="BocaArriba" value="Si" <? if($BocaArriba=="Si"){echo "checked";}?>  title="Boca Arriba"/><br>
    <input type="radio" id="BocaArriba" name="BocaArriba" value="No" <? if($BocaArriba=="No"){echo "checked";}?>  title="Boca Arriba" style="background-color:#E6E600"/>
    </td>
    <td style=" border-left-color:#000; border-top-color:#000" >
    <input type="text" id="HoraP1" name="HoraP1" value="<? echo $HoraP1?>" style="width:25px; font-size:11px" maxlength="2" onKeyDown="xNumero(this)" onKeyUp="xNumero(this)" title="Hora Puerperio 1">   
    </td>
    <td style=" border-top-color:#000">
    <input type="text" id="MinP1" name="MinP1" value="<? echo $MinP1?>" style="width:25px; font-size:11px" maxlength="2" onKeyDown="xNumero(this)" onKeyUp="xNumero(this)" title="Minuto Puerperio 1">
    </td>
    <td style=" border-left-color:#000;border-top-color:#000">
    <input type="text" id="TempP1" name="TempP1" value="<? echo $TempP1?>" style="width:25px; font-size:11px" maxlength="2" onKeyDown="xNumero(this)" onKeyUp="xNumero(this)" title="Temperatura Puerperio 1">
    </td>
    <td style=" border-left-color:#000;border-top-color:#000">
    <input type="text" id="PulsoP1" name="PulsoP1" value="<? echo $PulsoP1?>" style="width:25px; font-size:11px" maxlength="3" onKeyDown="xNumero(this)" onKeyUp="xNumero(this)" title="Pulso Puerperio 1">
    </td>
    <td style=" border-left-color:#000;border-top-color:#000">
    <input type="text" id="PASP1" name="PASP1" value="<? echo $PASP1?>" style="width:20px; font-size:9px" maxlength="3" onKeyDown="xNumero(this)" onKeyUp="xNumero(this)" title="Presion Arterial Sistolica Puerperio 1">
    <input type="text" id="PADP1" name="PADP1" value="<? echo $PADP1?>" style="width:20px; font-size:9px" maxlength="3" onKeyDown="xNumero(this)" onKeyUp="xNumero(this)" title="Presion Arterial Diastolica Puerperio 1">
    </td>
    <td style=" border-left-color:#000;border-top-color:#000">
    <input type="text" id="InvolUterP1" name="InvolUterP1" value="<? echo $InvolUterP1?>" style="width:30px; font-size:11px" maxlength="4" onKeyDown="xLetra(this)" onKeyUp="xLetra(this)" title="Invol Uter Puerperio 1">
    </td>
    <td style=" border-left-color:#000;border-top-color:#000; border-right-color:#000" align="left">
    &nbsp;<input type="text" id="LoquiosP1" name="LoquiosP1" value="<? echo $LoquiosP1?>" style="width:30px; font-size:11px" maxlength="4" onKeyDown="xLetra(this)" onKeyUp="xLetra(this)" title="Invol Uter Puerperio 1">
    </td>    
    </tr>
    <tr align="center">
    <td style=" border-left-color:#000; border-top-color:#000" >
    <input type="text" id="HoraP2" name="HoraP2" value="<? echo $HoraP2?>" style="width:25px; font-size:11px" maxlength="2" onKeyDown="xNumero(this)" onKeyUp="xNumero(this)" title="Hora Puerperio 1">   
    </td>
    <td style=" border-top-color:#000">
    <input type="text" id="MinP2" name="MinP2" value="<? echo $MinP2?>" style="width:25px; font-size:11px" maxlength="2" onKeyDown="xNumero(this)" onKeyUp="xNumero(this)" title="Minuto Puerperio 1">
    </td>
    <td style=" border-left-color:#000;border-top-color:#000">
    <input type="text" id="TempP2" name="TempP2" value="<? echo $TempP2?>" style="width:25px; font-size:11px" maxlength="2" onKeyDown="xNumero(this)" onKeyUp="xNumero(this)" title="Temperatura Puerperio 1">
    </td>
    <td style=" border-left-color:#000;border-top-color:#000">
    <input type="text" id="PulsoP2" name="PulsoP2" value="<? echo $PulsoP2?>" style="width:25px; font-size:11px" maxlength="3" onKeyDown="xNumero(this)" onKeyUp="xNumero(this)" title="Pulso Puerperio 1">
    </td>
    <td style=" border-left-color:#000;border-top-color:#000">
    <input type="text" id="PASP2" name="PASP2" value="<? echo $PASP2?>" style="width:20px; font-size:9px" maxlength="3" onKeyDown="xNumero(this)" onKeyUp="xNumero(this)" title="Presion Arterial Sistolica Puerperio 1">
    <input type="text" id="PADP2" name="PADP2" value="<? echo $PADP2?>" style="width:20px; font-size:9px" maxlength="3" onKeyDown="xNumero(this)" onKeyUp="xNumero(this)" title="Presion Arterial Diastolica Puerperio 1">
    </td>
    <td style=" border-left-color:#000;border-top-color:#000">
    <input type="text" id="InvolUterP2" name="InvolUterP2" value="<? echo $InvolUterP2?>" style="width:30px; font-size:11px" maxlength="4" onKeyDown="xLetra(this)" onKeyUp="xLetra(this)" title="Invol Uter Puerperio 1">
    </td>
    <td style=" border-left-color:#000;border-top-color:#000; border-right-color:#000" align="left">
    &nbsp;<input type="text" id="LoquiosP2" name="LoquiosP2" value="<? echo $LoquiosP2?>" style="width:30px; font-size:11px" maxlength="4" onKeyDown="xLetra(this)" onKeyUp="xLetra(this)" title="Invol Uter Puerperio 1">
    </td>  
    </tr>
    <tr align="center">
    <td style=" border-left-color:#000; border-top-color:#000; border-bottom-color:#000" >
    <input type="text" id="HoraP3" name="HoraP3" value="<? echo $HoraP3?>" style="width:25px; font-size:11px" maxlength="2" onKeyDown="xNumero(this)" onKeyUp="xNumero(this)" title="Hora Puerperio 1">   
    </td>
    <td style=" border-top-color:#000; border-bottom-color:#000">
    <input type="text" id="MinP3" name="MinP3" value="<? echo $MinP3?>" style="width:25px; font-size:11px" maxlength="2" onKeyDown="xNumero(this)" onKeyUp="xNumero(this)" title="Minuto Puerperio 1">
    </td>
    <td style=" border-left-color:#000;border-top-color:#000; border-bottom-color:#000">
    <input type="text" id="TempP3" name="TempP3" value="<? echo $TempP3?>" style="width:25px; font-size:11px" maxlength="2" onKeyDown="xNumero(this)" onKeyUp="xNumero(this)" title="Temperatura Puerperio 1">
    </td>
    <td style=" border-left-color:#000;border-top-color:#000; border-bottom-color:#000">
    <input type="text" id="PulsoP3" name="PulsoP3" value="<? echo $PulsoP3?>" style="width:25px; font-size:11px" maxlength="3" onKeyDown="xNumero(this)" onKeyUp="xNumero(this)" title="Pulso Puerperio 1">
    </td>
    <td style=" border-left-color:#000;border-top-color:#000; border-bottom-color:#000">
    <input type="text" id="PASP3" name="PASP3" value="<? echo $PASP3?>" style="width:20px; font-size:9px" maxlength="3" onKeyDown="xNumero(this)" onKeyUp="xNumero(this)" title="Presion Arterial Sistolica Puerperio 1">
    <input type="text" id="PADP3" name="PADP3" value="<? echo $PADP3?>" style="width:20px; font-size:9px" maxlength="3" onKeyDown="xNumero(this)" onKeyUp="xNumero(this)" title="Presion Arterial Diastolica Puerperio 1">
    </td>
    <td style=" border-left-color:#000;border-top-color:#000; border-bottom-color:#000">
    <input type="text" id="InvolUterP3" name="InvolUterP3" value="<? echo $InvolUterP3?>" style="width:30px; font-size:11px" maxlength="4" onKeyDown="xLetra(this)" onKeyUp="xLetra(this)" title="Invol Uter Puerperio 1">
    </td>
    <td style=" border-left-color:#000;border-top-color:#000; border-right-color:#000; border-bottom-color:#000" align="left">
    &nbsp;<input type="text" id="LoquiosP3" name="LoquiosP3" value="<? echo $LoquiosP3?>" style="width:30px; font-size:11px" maxlength="4" onKeyDown="xLetra(this)" onKeyUp="xLetra(this)" title="Invol Uter Puerperio 1">
    </td>      
    </tr>
    <tr align="center">
    <td style=" border-left-color:#000;border-top-color:#000; border-bottom-color:#000">
    Vitamina K<br>
	Si<input type="radio" id="VitaminaK" name="VitaminaK" value="Si" <? if($VitaminaK=="Si"){echo "checked";}?>  title="Vitamina K" /><br>  
    No<input type="radio" id="VitaminaK" name="VitaminaK" value="No" <? if($VitaminaK=="No"){echo "checked";}?>  title="Vitamina K" style="background-color:#E6E600"/>
    </td>
    <td colspan="2" align="right" style=" border-left-color:#000;border-top-color:#000; border-bottom-color:#000">
    Grupo &nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Rh&nbsp;<br>
    <select name="GrupoRN" style="font-size:11px">
    <option value="" <? if($GrupoRN==""){echo "selected";}?>>NA</option>
    <option value="A" <? if($GrupoRN=="A"){echo "selected";}?>>A</option>
    <option value="B" <? if($GrupoRN=="B"){echo "selected";}?>>B</option>
    <option value="AB" <? if($GrupoRN=="AB"){echo "selected";}?>>AB</option>
    <option value="O" <? if($GrupoRN=="O"){echo "selected";}?>>O</option>
    </select>
    <!--<input type="text" name="Grupo" value="<? echo $Grupo?>" style="width:30px; font-size:11px" maxlength="2" onKeyDown="xLetra(this)" onKeyUp="xLetra(this)">-->&nbsp;
    +    
    <input type="checkbox" name="RHRN" value="+" <? if($RHRN=="+"){echo "checked";}?> ><br>
    -
    <input type="checkbox" name="RHRN" value="-" <? if($RHRN=="-"){echo "checked";}?> style="background-color:#E6E600" ><br>
    Sensibil.&nbsp;    
    <input type="checkbox" name="SensibilRN" value="No" <? if($SensibilRN=="No"){echo "checked";}?> style="background-color:#E6E600" >
    <input type="checkbox" name="SensibilRN" value="Si" <? if($SensibilRN=="Si"){echo "checked";}?>  ><br>
    Si&nbsp;&nbsp;&nbsp;No&nbsp;
    </td>    
    <TD colspan="2" align="center" style=" border-left-color:#000;border-top-color:#000; border-bottom-color:#000">
    Profilaxis Oftalmica<br>
    Si<input type="checkbox" id="ProfilaxisOftalmica" name="ProfilaxisOftalmica" value="Si" <? if($ProfilaxisOftalmica=="Si"){echo "checked";}?>  title="Vitamina K" /><br>  
    No<input type="checkbox" id="ProfilaxisOftalmica" name="ProfilaxisOftalmica" value="No" <? if($ProfilaxisOftalmica=="No"){echo "checked";}?>  title="Vitamina K" style="background-color:#E6E600"/>
    </TD>
    <TD colspan="13" align="left" style=" border-left-color:#000; border-bottom-color:#000; border-right-color:#000;">
    Notas<br>
    <textarea id="NotasRN" name="NotasRN" style="width:100%; height:60px" ><? echo $NotasRN?></textarea>
    </TD>
    </tr>
    </table>
    <center><input type="submit" name="Guardar" value="Guardar"></center>
    <?
}
?>