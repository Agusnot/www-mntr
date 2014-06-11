<?
	//7366239
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
	$cons="select idclap,identificacion,fechacrea,Preantecedentes,Antecedentes from historiaclinica.claps where Compania='$Compania[0]' and Identificacion='$Paciente[1]'
	and estado='AC' order by idclap desc";
	$res=ExQuery($cons);
	$fila=ExFetch($res);
	$IdClap=$fila[0];
	if(!$fila)
	{
		?>
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
	else
	{
		if($Guardar)
		{	
			/*$cons="SELECT idcontrol FROM historiaclinica.clapcontroles where Compania='$Compania[0]' and IdClap=$IdClap and Identificacion='$Paciente[1]' 
			and Estado='AC' order by idcontrol desc";
			$res=ExQuery($cons);
			$fila=ExFetch($res);if(($fila[0]+1)>$IdControl){$IdControl=$fila[0]+1;}*/
			//--
			$cons="INSERT INTO historiaclinica.clapcontroles(compania, idclap, identificacion, idcontrol, numservicio, fechacrea, usuariocrea, fechacontrol, 
			edadgestacion, peso, presionarterialsistolica, presionarterialdiastolica, alturauterina, presentacion, fcf, movimientosfetales, fe, folatos, 
			calcio, estadonutricional, signosalarma_examenes_tratamientos, profesional, proximacitames, proximacitadia, estado)
			VALUES ('$Compania[0]', $IdClap, '$Paciente[1]', $IdControl, $NumServicio, '$FechaHoy $HoraHoy', '$usuario[1]', '$FechaControl[$IdControl]', 
			$EdadGestacional[$IdControl], $Peso[$IdControl], $PASistolica[$IdControl], $PADiastolica[$IdControl], $AlturaUterina[$IdControl], '$Presentacion[$IdControl]',
			$FCF[$IdControl], '$MovFetales[$IdControl]', '$Fe[$IdControl]', '$Folatos[$IdControl]', '$Calcio[$IdControl]', '$EstNutricional[$IdControl]',
			'$SignosAlarmaExamenesTratamientos[$IdControl]', '$usuario[0]', '$MesProxCita[$IdControl]', '$DiaProxCita[$IdControl]', 'AN')";
			$res=ExQuery($cons);
			if(ExError($res)){$Error=1;}else{$Error=0;}
			$IdControl="";			
		}
		//---
		$cons="Select Numero,Mes from central.meses order by numero";
		$res=ExQuery($cons);
		while($fila=ExFetch($res))
		{
			$Meses[$fila[0]]=array($fila[0],$fila[1]);	
		}
		//--
		$LLenaControl=1;
		$cons="SELECT idcontrol FROM historiaclinica.clapcontroles where Compania='$Compania[0]' and IdClap=$IdClap and Identificacion='$Paciente[1]' order by idcontrol desc";
		$res=ExQuery($cons);
		$fila=ExFetch($res);
		if(!$fila){$IdControl=1;}
		else{if(!$IdControl){$IdControl=$fila[0]+1;}}	
		$cons="SELECT idcontrol, fechacontrol, edadgestacion, peso, presionarterialsistolica, presionarterialdiastolica, alturauterina, presentacion, fcf, 
		movimientosfetales, fe, folatos, calcio, estadonutricional, signosalarma_examenes_tratamientos, profesional, proximacitames, proximacitadia 
		FROM historiaclinica.clapcontroles where Compania='$Compania[0]' and IdClap=$IdClap and Identificacion='$Paciente[1]' and Estado='AN'";
		//echo $cons;
		$res=ExQuery($cons);
		while($fila=ExFetch($res))
		{
			$MatControles[$fila[0]]=array($fila[0],$fila[1],$fila[2],$fila[3],$fila[4],$fila[5],$fila[6],$fila[7],$fila[8],$fila[9],$fila[10],$fila[11],$fila[12],$fila[13],$fila[14],$fila[15],$Meses[$fila[16]][1],$fila[17]);
		}
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
	ctext=0;
	cotros=0;	
	for (i=0;i<frm.elements.length;i++)
	{	
		if(frm.elements[i].type=="text")
		{
			if(frm.elements[i].value==""){alert("Por favor ingrese el valor correspondiente a "+frm.elements[i].title);break;}						
			ctext++;	
		}
		else
		{
			cotros++;	
		}				
	}	
	//alert(frm.elements.length+"  --  "+ctext+" -- "+cotros);
	sum=parseInt(frm.elements.length)-(parseInt(ctext)+parseInt(cotros));
	if(parseInt(sum)>0){return false;}
}
</script>
</head>
<body background="/Imgs/Fondo.jpg">
<form name="FORMA" method="post" onSubmit="return Validar();">
<input type="hidden" name="DatNameSID" value="<? echo $DatNameSID?>">
<input type="hidden" name="IdClap" value="<? echo $IdClap?>">
<input type="hidden" name="IdControl" value="<? echo $IdControl?>">
<?
if($LLenaControl)
{
	echo "<center><font face='Tahoma' color='#0066FF' size='+2' ><b>CONTROLES </b></font><br>";
	?>
    <table border="1" cellspacing="0" cellpadding="0" bordercolor="#ffffff" style="font : normal normal small-caps 8px Tahoma; width:1058px" align="center">
    <tr align="center" >
    <td style="border-left-color:#000; border-top-color:#000; font-weight:bold">No</td>
    <td style="border-left-color:#000; border-top-color:#000; font-weight:bold">Fecha</td>
    <td style="border-left-color:#000; border-top-color:#000; font-weight:bold">Edad<br />Gestacional</td>
    <td style="border-left-color:#000; border-top-color:#000; font-weight:bold">Peso</td>
    <td style="border-left-color:#000; border-top-color:#000; font-weight:bold">Presion<br />Arterial</td>
    <td style="border-left-color:#000; border-top-color:#000; font-weight:bold">Altura<br />Uterina</td>
    <td style="border-left-color:#000; border-top-color:#000; font-weight:bold">Presentacion</td>
    <td style="border-left-color:#000; border-top-color:#000; font-weight:bold">FCF<br />lpm</td>
    <td style="border-left-color:#000; border-top-color:#000; font-weight:bold">Movim.<br />Fetales</td>
    <td style="border-left-color:#000; border-top-color:#000; font-weight:bold">Fe</td>
    <td style="border-left-color:#000; border-top-color:#000; font-weight:bold">Folatos</td>
    <td style="border-left-color:#000; border-top-color:#000; font-weight:bold">Calcio</td>
    <td style="border-left-color:#000; border-top-color:#000; font-weight:bold">Estado<br />Nutricional</td>
    <td style="border-left-color:#000; border-top-color:#000; font-weight:bold;">Signos de Alarma, Examenes, Tratamientos</td>
    <td style="border-left-color:#000; border-top-color:#000; font-weight:bold">Profesional</td>
    <td style="border-left-color:#000; border-top-color:#000; border-right-color:#000; font-weight:bold">Proxima<br />Cita</td>
    </tr>
    <?
    if($MatControles)
	{
		foreach($MatControles as $IC)
		{
		?>
		<tr align="center" >
        <td style="border-left-color:#000; border-top-color:#000; " align="right"><? echo $IC[0];?></td>
        <td style="border-left-color:#000; border-top-color:#000; ">
        <? echo $IC[1]?>       
        </td>
        <td style="border-left-color:#000; border-top-color:#000; ">
        <? echo $IC[2]?>       
        </td>
        <td style="border-left-color:#000; border-top-color:#000; ">
        <? echo $IC[3]?>       
        </td>
        <td style="border-left-color:#000; border-top-color:#000; ">
        <? echo $IC[4]?> / <? echo $IC[5]?>       
        </td>
        <td style="border-left-color:#000; border-top-color:#000; ">
        <? echo $IC[6]?>       
        </td>
        <td style="border-left-color:#000; border-top-color:#000; ">
        <? echo $IC[7]?>       
        </td>
        <td style="border-left-color:#000; border-top-color:#000; ">
        <? echo $IC[8]?>       
        </td>
        <td style="border-left-color:#000; border-top-color:#000; ">
        <? echo $IC[9]?>       
        </td>
        <td style="border-left-color:#000; border-top-color:#000; ">
        <? echo $IC[10]?>       
        </td>
        <td style="border-left-color:#000; border-top-color:#000; ">
       <? echo $IC[11]?>       
        </td>
        <td style="border-left-color:#000; border-top-color:#000; ">
        <? echo $IC[12]?>       
        </td>
        <td style="border-left-color:#000; border-top-color:#000; ">
        <? echo $IC[13]?>       
        </td>
        <td style="border-left-color:#000; border-top-color:#000; width:325px" align="left">
       <? echo $IC[14]?>       
        </td>
        <td style="border-left-color:#000; border-top-color:#000;" align="left">
		<? echo $IC[15]?>
        </td>
        <td style="border-left-color:#000; border-top-color:#000; border-right-color:#000; ">
        <? echo $IC[16]?> - <? echo $IC[17]?>       
        </td>
        </tr>
		<?
		}
    }?>
    <tr align="center" >
    <td style="border-left-color:#000; border-top-color:#000; border-bottom-color:#000; " align="right"><? echo $IdControl;?></td>
    <td style="border-left-color:#000; border-top-color:#000; border-bottom-color:#000; ">
    <input type="text" name="FechaControl[<? echo $IdControl?>]" id="FechaControl[<? echo $IdControl?>]" value="" style="font-size:9px; width:57px" maxlength="10" readonly onClick="popUpCalendar(this, this, 'yyyy-mm-dd')" title="Fecha Control"/>
    </td>
    <td style="border-left-color:#000; border-top-color:#000;border-bottom-color:#000;  ">
    <input type="text" name="EdadGestacional[<? echo $IdControl?>]" id="EdadGestacional[<? echo $IdControl?>]" value="" style="font-size:9px; width:20px" maxlength="2" onKeyDown="xNumero(this)" onKeyUp="xNumero(this)" title="Edad Gestacional"/>
    </td>
    <td style="border-left-color:#000; border-top-color:#000; border-bottom-color:#000; ">
    <input type="text" name="Peso[<? echo $IdControl?>]" id="Peso[<? echo $IdControl?>]" value="" style="font-size:9px; width:30px" maxlength="4" onKeyDown="xNumero(this)" onKeyUp="xNumero(this)" title="Peso"/>
    </td>
    <td style="border-left-color:#000; border-top-color:#000;border-bottom-color:#000;  ">
    <input type="text" name="PASistolica[<? echo $IdControl?>]" id="PASistolica[<? echo $IdControl?>]" value="" style="font-size:9px; width:25px" maxlength="3" onKeyDown="xNumero(this)" onKeyUp="xNumero(this)" title="Presion Arterial Sistolica"/>
    /
    <input type="text" name="PADiastolica[<? echo $IdControl?>]" id="PADiastolica[<? echo $IdControl?>]" value="" style="font-size:9px; width:25px" maxlength="3" onKeyDown="xNumero(this)" onKeyUp="xNumero(this)" title="Presion Arterial Diastolica"/>
    </td>
    <td style="border-left-color:#000; border-top-color:#000; border-bottom-color:#000; ">
    <input type="text" name="AlturaUterina[<? echo $IdControl?>]" id="AlturaUterina[<? echo $IdControl?>]" value="" style="font-size:9px; width:20px" maxlength="2" onKeyDown="xNumero(this)" onKeyUp="xNumero(this)" title="Altura Uterina"/>
    </td>
    <td style="border-left-color:#000; border-top-color:#000;border-bottom-color:#000;  ">
    <input type="text" name="Presentacion[<? echo $IdControl?>]" id="Presentacion[<? echo $IdControl?>]" value="" style="font-size:9px; width:40px" maxlength="5" onKeyDown="xLetra(this)" onKeyUp="xLetra(this)" title="Presentación"/>
    </td>
    <td style="border-left-color:#000; border-top-color:#000;border-bottom-color:#000;  ">
    <input type="text" name="FCF[<? echo $IdControl?>]" id="FCF[<? echo $IdControl?>]" value="" style="font-size:9px; width:25px" maxlength="3" onKeyDown="xNumero(this)" onKeyUp="xNumero(this)" title="FCF"/>
    </td>
    <td style="border-left-color:#000; border-top-color:#000; border-bottom-color:#000; ">
    <input type="text" name="MovFetales[<? echo $IdControl?>]" id="MovFetales[<? echo $IdControl?>]" value="" style="font-size:9px; width:20px" maxlength="1" onKeyDown="xLetra(this)" onKeyUp="xLetra(this)" title="Movimientos Fetales"/>
    </td>
    <td style="border-left-color:#000; border-top-color:#000; border-bottom-color:#000; ">
    <input type="text" name="Fe[<? echo $IdControl?>]" id="Fe[<? echo $IdControl?>]" value="" style="font-size:9px; width:30px" maxlength="4" onKeyDown="xLetra(this)" onKeyUp="xLetra(this)" title="Fe"/>
    </td>
    <td style="border-left-color:#000; border-top-color:#000; border-bottom-color:#000; ">
    <input type="text" name="Folatos[<? echo $IdControl?>]" id="Folatos[<? echo $IdControl?>]" value="" style="font-size:9px; width:30px" maxlength="4" onKeyDown="xLetra(this)" onKeyUp="xLetra(this)" title="Folatos"/>
    </td>
    <td style="border-left-color:#000; border-top-color:#000; border-bottom-color:#000; ">
    <input type="text" name="Calcio[<? echo $IdControl?>]" id="Calcio[<? echo $IdControl?>]" value="" style="font-size:9px; width:30px" maxlength="4" onKeyDown="xLetra(this)" onKeyUp="xLetra(this)" title="Calcio"/>
    </td>
    <td style="border-left-color:#000; border-top-color:#000; border-bottom-color:#000; ">
    <input type="text" name="EstNutricional[<? echo $IdControl?>]" id="EstNutricional[<? echo $IdControl?>]" value="" style="font-size:9px; width:60px" maxlength="8" onKeyDown="xLetra(this)" onKeyUp="xLetra(this)" title="Estado Nutricional"/>
    </td>
    <td style="border-left-color:#000; border-top-color:#000; border-bottom-color:#000;  ">
    <input type="text" name="SignosAlarmaExamenesTratamientos[<? echo $IdControl?>]" id="SignosAlarmaExamenesTratamientos[<? echo $IdControl?>]" value="" style="font-size:9px; width:320px" maxlength="999" onKeyDown="xLetra(this)" onKeyUp="xLetra(this)" title="Signos de Alarma"/>
    </td>
    <td style="border-left-color:#000; border-top-color:#000; border-bottom-color:#000; "><? echo $usuario[0]?></td>
    <td style="border-left-color:#000; border-top-color:#000; border-right-color:#000; border-bottom-color:#000; ">
    <input type="text" name="MesProxCita[<? echo $IdControl?>]" id="MesProxCita[<? echo $IdControl?>]" value="" style="font-size:9px; width:25px" maxlength="2" onKeyDown="xNumero(this); if(parseInt(this.value)>12){this.value=12;}" onKeyUp="xNumero(this); if(parseInt(this.value)>12){this.value=12;}" title="Mes Proxima Cita"/>
    -
    <input type="text" name="DiaProxCita[<? echo $IdControl?>]" id="DiaProxCita[<? echo $IdControl?>]" value="" style="font-size:9px; width:25px" maxlength="2" onKeyDown="xNumero(this);if(parseInt(this.value)>31){this.value=30;}" onKeyUp="xNumero(this);if(parseInt(this.value)>31){this.value=30;}" title="Dia Proxima Cita"/>
    </td>
    </tr>
    </table>
    <center><input type="submit" name="Guardar" value="Guardar"></center>
    <?
}
?>
</form>
<iframe scrolling="no" id="FrameFondo" name="FrameFondo" frameborder="0" height="0" width="0" style="filter:Alpha(Opacity=200, FinishOpacity=40, Style=2, StartX=20, StartY=40, FinishX=0, FinishY=0);display:none;border:thin; background-color:transparent" ></iframe>
</body>
