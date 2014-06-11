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
	$HoraHoy=$ND[hours].":".$ND[minutes]." ".$ND[seconds];	
	if($Guardar)
	{
		if(!$AniosEstudio){$AniosEstudio=0;}
		$cons="update historiaclinica.claps set preantecedentes=1, etnia='$Etnia', desplazado='$Desplazado', leeescribe='$LeeEscribe',
		estudios='$Estudios', aniosestudio=$AniosEstudio, estcivil='$EstCivil', controlprenatalen='$ControlPrenatalEn', partoen='$PartoEn'
		where Compania='$Compania[0]' and IdClap=$IdClap and Identificacion='$Paciente[1]' and Estado='AC'";
		$res=ExQuery($cons);
		?><script language="javascript">parent.location.href='Antecedentes.php?DatNameSID=<? echo $DatNameSID?>'</script><?
	}
	$cons="SELECT etnia, desplazado, leeescribe, estudios, aniosestudio, estcivil, controlprenatalen, partoen FROM historiaclinica.claps
	where Compania='$Compania[0]' and IdClap=$IdClap and Identificacion='$Paciente[1]' and estado='AC'";
	$res=ExQuery($cons);
	if(ExNumRows($res)>0)
	{
		$cons1="SELECT etnia, desplazado, leeescribe, estudios, aniosestudio, estcivil FROM historiaclinica.claps
		where Compania='$Compania[0]' and Identificacion='$Paciente[1]' and estado='AN' order by IdClap desc";		
		$res1=ExQuery($cons1);	
		$fila1=ExFetch($res1);
		if(!$Etnia){$Etnia=$fila1[0];}
		if(!$Desplazado){$Desplazado=$fila1[1];}
		if(!$LeeEscribe){$LeeEscribe=$fila1[2];}
		if(!$Estudios){$Estudios=$fila1[3];}
		if(!$AniosEstudio){$AniosEstudio=$fila1[4];}
		if(!$EstCivil){$EstCivil=$fila1[5];}
		
		$fila=ExFetch($res);		
		if(!$Etnia){$Etnia=$fila[0];}
		if(!$Desplazado){$Desplazado=$fila[1];}
		if(!$LeeEscribe){$LeeEscribe=$fila[2];}
		if(!$Estudios){$Estudios=$fila[3];}
		if(!$AniosEstudio){$AniosEstudio=$fila[4];}
		if(!$EstCivil){$EstCivil=$fila[5];}
		if(!$ControlPrenatalEn){$ControlPrenatalEn=$fila[6];}
		if(!$PartoEn){$PartoEn=$fila[7];}
	}
	else
	{		
		$cons="SELECT etnia, desplazado, leeescribe, estudios, aniosestudio, estcivil FROM historiaclinica.claps
		where Compania='$Compania[0]' and Identificacion='$Paciente[1]' order by IdClap desc";
		echo $cons;
		$res=ExQuery($cons);	
		$fila=ExFetch($res);
		if(!$Etnia){$Etnia=$fila[0];}
		if(!$Desplazado){$Desplazado=$fila[1];}
		if(!$LeeEscribe){$LeeEscribe=$fila[2];}
		if(!$Estudios){$Estudios=$fila[3];}
		if(!$AniosEstudio){$AniosEstudio=$fila[4];}
		if(!$EstCivil){$EstCivil=$fila[5];}
	}
?>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<style type="text/css"> 
body { background-color: transparent } 
</style>
<script language="javascript" src="/Funciones.js"></script>
<script language='javascript' src="/calendario/popcalendar.js"></script>
<script language="javascript">
function VerPreAntecedentes()
{
	document.getElementById('FrameFondo').src="Framefondo.php";				
	document.getElementById('FrameFondo').style.position='absolute';
	document.getElementById('FrameFondo').style.top='1px';
	document.getElementById('FrameFondo').style.left='1px';
	document.getElementById('FrameFondo').style.display='';
	document.getElementById('FrameFondo').style.width='100%';
	document.getElementById('FrameFondo').style.height='100%';	
	//---
	document.getElementById('PreAntecedentes').src='PreAntecedentes.php?DatNameSID=<? echo $DatNameSID?>&IdClap=<? echo $fila[0]?>&Identificacion=<? echo $fila[1]?>';
	document.getElementById('PreAntecedentes').style.position='absolute';
	document.getElementById('PreAntecedentes').style.top='15%';
	document.getElementById('PreAntecedentes').style.left='40%';
	document.getElementById('PreAntecedentes').style.display='';
	document.getElementById('PreAntecedentes').style.width='25%';
	document.getElementById('PreAntecedentes').style.height='35%';		
}
function Validar()
{
	b=0;	
	for (i=0;i<document.FORMA.Etnia.length;i++)
	{
		if(document.FORMA.Etnia[i].checked){b=1;break;}
	}
	if(b==0)
	{
		if(!document.FORMA.Etnia.checked)
		{			
			alert("Por favor seleccione seleccione una Etnia!!!");return false;
		}
	}
	b=0;	
	for (i=0;i<document.FORMA.Desplazado.length;i++)
	{
		if(document.FORMA.Desplazado[i].checked){b=1;break;}
	}
	if(b==0)
	{
		if(!document.FORMA.Desplazado.checked)
		{			
			alert("Por favor responda el Campo Desplazado!!!");return false;
		}
	}
	b=0;	
	for (i=0;i<document.FORMA.LeeEscribe.length;i++)
	{
		if(document.FORMA.LeeEscribe[i].checked){b=1;break;}
	}
	if(b==0)
	{
		if(!document.FORMA.LeeEscribe.checked)
		{			
			alert("Por favor responda el Campo Lee y Escribe!!!");return false;
		}
	}
	b=0;	
	for (i=0;i<document.FORMA.Estudios.length;i++)
	{
		if(document.FORMA.Estudios[i].checked){b=1;Estu=document.FORMA.Estudios[i].value;break;}
	}
	if(b==0)
	{
		if(!document.FORMA.Estudios.checked)
		{			
			alert("Por favor responda el campo Estudios!!!");return false;
		}
	}
	else
	{
		//alert(Estu);
		if(Estu!="Ninguno")
		{
			if(document.FORMA.AniosEstudio.value==""){alert("Por favor ingrese el numero de años en el mayor nivel educativo!!!");return false;}	
		}
	}
	b=0;	
	for (i=0;i<document.FORMA.EstCivil.length;i++)
	{
		if(document.FORMA.EstCivil[i].checked){b=1;break;}
	}
	if(b==0)
	{
		if(!document.FORMA.EstCivil.checked)
		{			
			alert("Por favor seleccione el Estado civil!!!");return false;
		}
	}
	if(document.FORMA.ControlPrenatalEn.value==""){alert("Ingrese la Fecha del Control Prenatal!!!");return false;}
	if(document.FORMA.PartoEn.value==""){alert("Ingrese la Fecha del parto!!!");return false;}
}
function Salir()
{	
	//---	
	parent.document.getElementById('PreAntecedentes').style.top='0';
	parent.document.getElementById('PreAntecedentes').style.left='0';
	parent.document.getElementById('PreAntecedentes').style.display='none';
	parent.document.getElementById('PreAntecedentes').style.width='';
	parent.document.getElementById('PreAntecedentes').style.height='';	
	//----
	parent.document.getElementById('FrameFondo').style.top='1px';
	parent.document.getElementById('FrameFondo').style.left='1px';
	parent.document.getElementById('FrameFondo').style.display='';
	parent.document.getElementById('FrameFondo').style.width='';
	parent.document.getElementById('FrameFondo').style.height='';		
}
</script>
</head>
<body background="/Imgs/Fondo.jpg">
<form name="FORMA" method="post" onSubmit="return Validar()">
<input type="hidden" name="DatNameSID" value="<? echo $DatNameSID?>">
<input type="hidden" name="IdClap" value="<? echo $IdClap?>">
<table border="1" cellspacing="0" cellpadding="0" bordercolor="#ffffff" style="font : normal normal small-caps 11px Tahoma;" width="100%">
<tr align="center"  style="font-weight:bold"><td style="border-left-color:#000; border-top-color:#000">Etnia</td><td style="border-left-color:#000; border-top-color:#000">Desplazado</td><td style="border-left-color:#000;  border-top-color:#000">Lee y Escribe</td><td style="border-left-color:#000; border-top-color:#000">Estudios</td><td style="border-left-color:#000;  border-top-color:#000">Estado Civil</td><td style="border-left-color:#000;  border-top-color:#000; border-right-color:#000">&nbsp;</td></tr>
<tr>
<td rowspan="4" style="border-left-color:#000; border-bottom-color:#000">
	<input type="radio" name="Etnia" value="Blanca" <? if($Etnia=="Blanca"){echo "checked";}?>  />Blanca<br />
    <input type="radio" name="Etnia" value="Indigena" <? if($Etnia=="Indigena"){echo "checked";}?>/>Indigena<br />
    <input type="radio" name="Etnia" value="Mestiza" <? if($Etnia=="Mestiza"){echo "checked";}?> />Mestiza<br />
    <input type="radio" name="Etnia" value="Negra"  <? if($Etnia=="Negra"){echo "checked";}?>/>Negra<br />
    <input type="radio" name="Etnia" value="Otra"  <? if($Etnia=="Otra"){echo "checked";}?>/>Otra
</td>
<td rowspan="4" style="border-left-color:#000; border-bottom-color:#000">
	<input type="radio" name="Desplazado" value="Si" <? if($Desplazado=="Si"){echo "checked";}?> style="background-color:#E6E600" />Si<br />
    <input type="radio" name="Desplazado" value="No" <? if($Desplazado=="No"){echo "checked";}?> />No
</td>
<td rowspan="4" style="border-left-color:#000; border-bottom-color:#000">
	<input type="radio" name="LeeEscribe" value="Si" <? if($LeeEscribe=="Si"){echo "checked";}?> style="background-color:#E6E600"  />Si<br />
    <input type="radio" name="LeeEscribe" value="No" <? if($LeeEscribe=="No"){echo "checked";}?> style="background-color:#E6E600" />No
</td>
<td rowspan="4" style="border-left-color:#000;  border-bottom-color:#000">	
	<input type="radio" name="Estudios" value="Ninguno" <? if($Estudios=="Ninguno"){echo "checked";}?> style="background-color:#E6E600"  />Ninguno<br />
    <input type="radio" name="Estudios" value="Primaria" <? if($Estudios=="Primaria"){echo "checked";}?>/>Primaria<br />
    <input type="radio" name="Estudios" value="Secundaria" <? if($Estudios=="Secundaria"){echo "checked";}?>/>Secundaria<br />
    <input type="radio" name="Estudios" value="Universitarios" <? if($Estudios=="Universitarios"){echo "checked";}?>/>Universitarios<br />
    &nbsp;Años en el mayor nivel <input type="text" name="AniosEstudio" value="<? echo $AniosEstudio?>" style="width:20px; font-size:10px; text-align:right" maxlength="2" onKeyDown="xNumero(this)" onKeyUp="xNumero(this)"/>
</td>
<td rowspan="4" style="border-left-color:#000;  border-bottom-color:#000">	
	<input type="radio" name="EstCivil" value="Casada" <? if($EstCivil=="Casada"){echo "checked";}?> />Casada<br />
    <input type="radio" name="EstCivil" value="Union Libre" <? if($EstCivil=="Union Libre"){echo "checked";}?>/>Union Libre<br />
    <input type="radio" name="EstCivil" value="Soltera" <? if($EstCivil=="Soltera"){echo "checked";}?> style="background-color:#E6E600" />Soltera<br />
    <input type="radio" name="EstCivil" value="Ninguno" <? if($EstCivil=="Ninguno"){echo "checked";}?> style="background-color:#E6E600" />Ninguno
</td>
<td align="center"  style="border-left-color:#000;  border-right-color:#000">Control Prenatal en</td>
</tr>
<tr>
<td align="center"  style="border-left-color:#000;  border-right-color:#000"><input type="text" name="ControlPrenatalEn" value="<? echo $ControlPrenatalEn?>" style="width:80px; font-size:10px; text-align:right" maxlength="10" onKeyDown="xLetra(this)" onKeyUp="xLerra(this)" /></td>
</tr>
<tr>
<td align="center"  style="border-left-color:#000; border-right-color:#000">Parto En</td>
</tr>
<tr>
<td align="center" style="border-left-color:#000; border-bottom-color:#000; border-right-color:#000"><input type="text" name="PartoEn" value="<? echo $PartoEn?>" style="width:80px; font-size:10px; text-align:right" maxlength="10" onKeyDown="xLetra(this)" onKeyUp="xLerra(this)"/></td>
</tr>
</table>
<center><font color="#0080C0" size="-1"><b>El Color Amarillo Significa Alerta</b></font></center>
<br />
<center>
<input type="submit" name="Guardar" value="Guardar" />
<? if($VerIdentificacion){?><input type="button" name="Cerrar" value="Cerrar" onClick="Salir()"><? }?>
</center>
</form>
<iframe scrolling="no" id="FrameFondo" name="FrameFondo" frameborder="0" height="0" width="0" style="filter:Alpha(Opacity=200, FinishOpacity=40, Style=2, StartX=20, StartY=40, FinishX=0, FinishY=0);display:none;border:thin; background-color:transparent" ></iframe>
<iframe scrolling="yes" id="PreAntecedentes" name="PreAntecedentes" frameborder="0" height="1" style="display:none;border:#e5e5e5; border-style:solid; "></iframe>
</body>