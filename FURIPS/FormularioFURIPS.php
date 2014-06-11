<? 
	if($DatNameSID){session_name("$DatNameSID");}
	session_start();
	include("Funciones.php");
	$ND=getdate();
	$FechaHoy="$ND[year]-$ND[mon]-$ND[mday]";
	$HoraHoy="$ND[hours]:$ND[minutes]:$ND[seconds]";
	//--
	include "../xajax/xajax_core/xajax.inc.php";	
	$xajax= new xajax();	
	//---------------
function autocompleta($Div,$input,$CampoAct,$CampoSig,$Tabla,$Columna1,$Columna2,$ColCondicion1,$ValorCond1)           
{	
	$respuesta = new xajaxResponse();
	if($ColCondicion1&&$ValorCond1)
	{
		$PartCons="and $ColCondicion1='$ValorCond1' ";	
	}
	$con= "SELECT  $Columna1 ,$Columna2 FROM $Tabla WHERE  $Columna2  ILIKE '".$input."%' $PartCons LIMIT 10 ";
	$res45 = ExQuery($con);
	$num = ExNumRows($res45);
	if ($input == "")
   {  
	 $respuesta->Assign($Div, "innerHTML", ""); 
	 return $respuesta; 
   }
   if ($num == 0) 
   {
		$output = "<font color='red'>No existe</font>"; 
   }   
   else 
   {
	   $output .= "<div id='divLista'> <table  border='2' align='center' cellpadding='2' bordercolor='#e5e5e5'   style='font : normal normal small-caps 12px Tahoma;' > <tr onMouseOver='this.bgColor='#AAD4FF'' onMouseOut='this.bgColor='''>";	   
	   while ($row = ExFetch($res45)) 
	   {		   
	   	   if($CampoSig){$PartS="xajax_seleccion('$CampoSig','".$row[0]."');";}
		   $output .= "<tr style='cursor:hand'><td
		   onClick=\"alert('$Div');xajax_seleccion('$CampoAct','".$row[1]."'); $PartS xajax_autocompleta('$Div','','$CampoAct','$CampoSig','$Tabla','$Columna1','$Columna2','','');\">".$row
		   [0]." - ".$row[1]."</td></tr>";
	   }
	   $output .= "</table></div>";
    }
	$respuesta->Assign($Div, "innerHTML", $output);
	return $respuesta;     
}
//---
function seleccion($Campo,$Valor)
{
	$respuesta = new xajaxResponse();
	$respuesta->Assign($Campo, "value", $Valor);
	return $respuesta;      
}
//--
$xajax->registerFunction('autocompleta');
$xajax->registerFunction('seleccion');
$xajax->processRequest();
?> 
<head>
<?php $xajax->printJavascript("../xajax/"); ?>
<style type="text/css">
#divLista{ position:absolute; left: 82px;width:500px;height:100px;overflow:auto;border:solid 1px #ccc;background-color:#fff;}
</style>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link rel="stylesheet" type="text/css" media="all" href="/calendario/Calendar/calendar-win2k-cold-1.css" title="win2k-cold-1"/>
<script language="javascript" src="/Funciones.js"></script>
<script type="text/javascript" src="/calendario/Calendar/calendar.js"></script>
<script type="text/javascript" src="/calendario/Calendar/calendar-es.js"></script>
<script type="text/javascript" src="/calendario/Calendar/calendar-setup.js"></script>
<script language='javascript' src="/calendario/popcalendar.js"></script>
<script>
function Cal(Campo)
{
	//alert(Campo.name)
	Calendar.setup({
	inputField     :    Campo.name, 	      
	ifFormat       :    "%Y/%m/%d",       
	showsTime      :    true,            
	//button         :    "calendario",   
	singleClick    :    false,           
	step           :    1                
	});	
}
</script>
</head>
<body background="/Imgs/Fondo.jpg">
<form name="FORMA" method="post">
<input type="hidden" name="DatNameSID" value="<? echo $DatNameSID?>" />
<input type="hidden" name="Identificacion" value="<? echo $Identificacion?>" />
<table border="1" bordercolor="#e5e5e5" style='font : normal normal small-caps 12px Tahoma;' align="center" cellpadding="0">
<tr >
<td  bgcolor="#e5e5e5" style="font-weight:bold">Fecha Radicacion</td><td><input type="text" id="FechaRadicacion" name="FechaRadicacion" value="<? echo $FechaRadicacion; ?>" maxlength="10" style="width:70px" onFocus="Cal(this);" onClick="popUpCalendar(this, this, 'yyyy-mm-dd')"s/></td>
<td  bgcolor="#e5e5e5" style="font-weight:bold" title="Respuesta a Glosa">RG</td>
<td>
<select name="RespuestaGlosa" title="Respuesta a Glosa">
<option value=""></option>
<option value="0" <? if($RespuestaGlosa=="0"){echo "selected";}?>>Glosa Total</option>
<option value="1" <? if($RespuestaGlosa=="1"){echo "selected";}?>>Pago Parcial</option>
</select>
</td>
<td  bgcolor="#e5e5e5" style="font-weight:bold">No Radicado</td><td><input type="text" name="NoRadicado" value="<? echo $NoRadicado; ?>" maxlength="10"/></td>
</tr>
<tr>
<td  bgcolor="#e5e5e5" style="font-weight:bold">No Radicado Anterior/<br>(Respuesta a glosa, marcar RG)</td><td><input type="text" name="NoRadicadoAnterior" value="<? echo $NoRadicadoAnterior; ?>" maxlength="10" /></td>
<td colspan="3" align="right"  bgcolor="#e5e5e5" style="font-weight:bold">Nro Factura/<br>Cuenta de cobro</td><td><input type="text" name="NoFactura" value="<? echo $NoFactura; ?>" maxlength="20" /></td>
</tr>
</table>
<table border="1" bordercolor="#e5e5e5" style='font : normal normal small-caps 11px Tahoma;' align="center">
<tr>
<td colspan="6" bgcolor="#e5e5e5" style="font-weight:bold" align="center">DATOS DE LA INSTITUCION PRESTADORA DE SERVICIOS DE SALUD</td>
</tr>
<tr>
<?
$conse="SELECT nombre, codsgsss, nit, direccion, telefonos,departamento,  municipio  FROM central.compania where nombre='$Compania[0]'";
$rese=ExQuery($conse);
$filae=ExFetch($rese);
if(!$RazonSocialIPS){$RazonSocialIPS=$filae[0];}
if(!$CodigoHabilitacionIPS){$CodigoHabilitacionIPS=$filae[1];}
if(!$NITIPS){$NITIPS=$filae[2];}
if(!$DireccionIPS){$DireccionIPS=$filae[3];}
if(!$TelefonoIPS){$TelefonoIPS=$filae[4];}
if(!$CodDepartamentoIPS&&!$DepartamentoIPS)
{
	$CodDepartamentoIPS=$filae[5];	
	$consd="Select departamento from central.departamentos where codigo='$CodDepartamentoIPS'";
	$resd=ExQuery($consd);
	$filad=ExFetch($resd);
	$DepartamentoIPS=$filad[0];
}
if(!$CodMunicipioIPS&&!$MunicipioIPS)
{
	$CodMunicipioIPS=$filae[6];
	$consm="Select municipio from central.municipios where departamento='$CodDepartamentoIPS' and codmpo='$CodMunicipioIPS'";
	$resm=ExQuery($consm);
	$filam=ExFetch($resm);
	$MunicipioIPS=$filam[0];	
}
?>
<td bgcolor="#e5e5e5" style="font-weight:bold">Razon Social</td><td><input type="text" name="RazonSocialIPS" value="<? echo $RazonSocialIPS?>" style="width:270px" readonly></td>
<td bgcolor="#e5e5e5" style="font-weight:bold">NIT</td><td><input type="text" name="NIT" value="<? echo $NITIPS?>" readonly style="width:100px"></td>
<td bgcolor="#e5e5e5" style="font-weight:bold">Codigo Habilitacion</td><td><input type="text" name="CodigoHabilitacioIPS" value="<? echo $CodigoHabilitacionIPS?>" maxlength="12" readonly></td>

</tr>
<tr>
<td bgcolor="#e5e5e5" style="font-weight:bold">Direccion</td><td colspan="3"><input type="text" name="DireccionIPS" value="<? echo $DireccionIPS?>" style="width:100%" readonly></td>
<td bgcolor="#e5e5e5" style="font-weight:bold">Telefono</td><td><input type="text" name="TelefonoIPS" value="<? echo $TelefonoIPS?>" readonly></td>
</tr>
<tr>
<td bgcolor="#e5e5e5" style="font-weight:bold">Departamento</td><td><input type="text" name="DepartamentoIPS" value="<? echo $DepartamentoIPS?>" readonly><b>Cod</b> <input type="text" name="CodDepartamentoIPS" value="<? echo $CodDepartamentoIPS?>" style="width:40px" maxlength="2" readonly></td>
<td bgcolor="#e5e5e5" style="font-weight:bold">Municipio</td><td colspan="3"><input type="text" name="MunicipioIPS" value="<? echo $MunicipioIPS?>" readonly style="width:100px"><b>Cod</b> <input type="text" name="CodMunicipioIPS" value="<? echo $CodMunicipioIPS?>" style="width:40px" maxlength="3" readonly></td>
</tr>
<tr>
<td colspan="6" bgcolor="#e5e5e5" style="font-weight:bold" align="center">DATOS DE LA VICTIMA DEL EVENTO CATASTROFICO O ACCIDENTE DE TRANSITO</td>
</tr>
<tr bgcolor="#e5e5e5" style="font-weight:bold" align="center">
<?

$cons="SELECT identificacion, primape, segape, primnom, segnom, tipodoc,fecnac, sexo, direccion, telefono, departamento, municipio
FROM central.terceros where Compania='$Compania[0]' and Identificacion='$Identificacion'";
$res=ExQuery($cons);
$fila=ExFetch($res);
if(!$PrimApeVic){$PrimApeVic=$fila[1];}
if(!$SegApeVic){$SegApeVic=$fila[2];}
if(!$PrimNomVic){$PrimNomVic=$fila[3];}
if(!$SegNomVic){$SegNomVic=$fila[4];}
if(!$TipoDocumentoVic)
{
	//echo $fila[5];
	if($fila[5]=="Cedula de ciudadania"){$fila[5]="CC";}
	if($fila[5]=="Adulto sin identificacion"){$fila[5]="AS";}
	if($fila[5]=="Cedula de extranjeria"){$fila[5]="CE";}
	if($fila[5]=="Menor sin identificacion"){$fila[5]="MS";}
	if($fila[5]=="Pasaporte"){$fila[5]="PA";}
	if($fila[5]=="Registro civil"){$fila[5]="RC";}
	if($fila[5]=="Tarjeta de identidad"){$fila[5]="TI";}
	$TipoDocumentoVic=$fila[5];
}
if(!$FechaNacimientoVic){$FechaNacimientoVic=$fila[6];}
if(!$SexoVic){$SexoVic=$fila[7];}
if(!$DireccionVic){$DireccionVic=$fila[8]; }
if(!$TelefonoVic){$TelefonoVic=$fila[9]; }
if(!$CodDepartamentoVic&&!$DepartamentoVic)
{
	//echo $fila[10];
	$DepartamentoVic=$fila[10];	
	$consd="Select codigo from central.departamentos where Departamento='$DepartamentoVic'";
	$resd=ExQuery($consd);
	$filad=ExFetch($resd);
	$CodDepartamentoVic=$filad[0];
}
if(!$CodMunicipioVic&&!$MunicipioVic)
{
	$MunicipioVic=$fila[11];
	$consm="Select codmpo from central.municipios where departamento='$CodDepartamentoIPS' and municipio='$MunicipioVic'";
	$resm=ExQuery($consm);
	$filam=ExFetch($resm);
	$CodMunicipioVic=$filam[0];	
}
?>
<td >Primer Apellido</td>
<td >Segundo Apellido</td>
<td >Primer Nombre</td>
<td colspan="2" >Segundo Nombre</td>
<td >Tipo Documento</td>
</tr>
<tr >
<td ><input type="text" name="PrimApeVic" value="<? echo $PrimApeVic?>" maxlength="20" style="width:100" readonly></td>
<td ><input type="text" name="SegApeVic" value="<? echo $SegApeVic?>" maxlength="20" style="width:100%" readonly></td>
<td ><input type="text" name="PrimNomVic" value="<? echo $PrimNomVic?>" maxlength="20" style="width:100" readonly></td>
<td colspan="2" ><input type="text" name="SegNomVic" value="<? echo $SegNomVic?>" maxlength="30" style="width:100%" readonly></td>
<td >
<select name="TipoDocumentoVic"<? if($TipoDocumentoVic!=''){?> onChange="this.value='<? echo $TipoDocumentoVic?>';"<? }?>>
<option value=""></option>
<?
$cons="Select  codigo, tipodoc from central.tiposdocumentos where codigo!='NU' order by codigo";
$res=ExQuery($cons);
while($fila=ExFetch($res))
{
	if($TipoDocumentoVic==$fila[0])
	{
		echo "<option value='$fila[0]' selected>$fila[1]</option>";	
	}	
	else
	{
		echo "<option value='$fila[0]' >$fila[1]</option>";	
	}
}
?>
<!--<option value="CC" <? if($TipoDocumentoVic=="CC"){echo "selected";}?>>Cedula de Ciudadania</option>
<option value="CE" <? if($TipoDocumentoVic=="CE"){echo "selected";}?>>Cedula de Extrangeria</option>
<option value="PA" <? if($TipoDocumentoVic=="PA"){echo "selected";}?>>Pasaporte</option>
<option value="TI" <? if($TipoDocumentoVic=="TI"){echo "selected";}?>>Tarjeta de Identidad</option>
<option value="RC" <? if($TipoDocumentoVic=="RC"){echo "selected";}?>>Registro Civil</option>
<option value="AS" <? if($TipoDocumentoVic=="AS"){echo "selected";}?>>Adulto sin Identificar</option>
<option value="MS" <? if($TipoDocumentoVic=="MS"){echo "selected";}?>>Menor sin Identificar</option>-->
</select>
</td>
</tr>
<tr>
<td bgcolor="#e5e5e5" style="font-weight:bold">No. Documento</td>
<td ><input type="text" name="Identificacion" value="<? echo $Identificacion?>" maxlength="16" readonly></td>
<td bgcolor="#e5e5e5" style="font-weight:bold">Fecha Nacimiento</td>
<td ><input type="text" name="FechaNacimientoVic" value="<? echo $FechaNacimientoVic?>" maxlength="10" readonly style="width:100px"></td>
<td bgcolor="#e5e5e5" style="font-weight:bold">Sexo</td>
<td ><select name="SexoVic" <? if($SexoVic!=""){?> onChange="this.value='<? echo $SexoVic;?>';" <? }?>><option value=""></option><option value="M" <? if($SexoVic=="M"){echo "selected";}?>>Masculino</option><option value="F" <? if($SexoVic=="F"){echo "selected";}?>>Femenino</option></select></td>
</tr>
<tr>
<td bgcolor="#e5e5e5" style="font-weight:bold">Direccion Residencia</td><td colspan="3"><input type="text" name="DireccionVic" value="<? echo $DireccionVic?>" style="width:100%" readonly></td>
<td bgcolor="#e5e5e5" style="font-weight:bold">Telefono</td><td><input type="text" name="TelefonoVic" value="<? echo $TelefonoVic?>" readonly></td>
</tr>
<tr>
<td bgcolor="#e5e5e5" style="font-weight:bold">Departamento</td><td><input type="text" name="DepartamentoVic" value="<? echo $DepartamentoVic?>" readonly><b>Cod</b> <input type="text" name="CodDepartamentoVic" value="<? echo $CodDepartamentoVic?>" style="width:40px" maxlength="2" readonly></td>
<td bgcolor="#e5e5e5" style="font-weight:bold">Municipio</td><td colspan="3"><input type="text" name="MunicipioVic" value="<? echo $MunicipioVic?>" readonly style="width:100px"><b>Cod</b> <input type="text" name="CodMunicipioVic" value="<? echo $CodMunicipioVic?>" style="width:40px" maxlength="3" readonly></td>
</tr>
<tr>
<td bgcolor="#e5e5e5" style="font-weight:bold">Condicion de la Victima</td><td colspan="5">
<select name="CondicionVictima"><option value=""></option><option value="1" <? if($CondicionVictima=="1"){echo "selected";}?>>Conductor</option><option value="2" <? if($CondicionVictima=="2"){echo "selected";}?>>Peaton</option><option value="3" <? if($CondicionVictima=="3"){echo "selected";}?>>Ocupante</option><option value="4" <? if($CondicionVictima=="4"){echo "selected";}?>>Ciclista</option></select>
</td>
</tr>
<tr>
<td colspan="6" bgcolor="#e5e5e5" style="font-weight:bold" align="center">DATOS DEL SITIO DONDE OCURRIO EL EVENTO CATASTROFICO O EL ACCIDENTE DE TRANSITO</td>
</tr>
<tr>
<td bgcolor="#e5e5e5" style="font-weight:bold">Naturaleza del Evento</td><td >
<select name="NaturalezaEvento" onChange="if(this.value=='17'){document.FORMA.OtroCual.disabled=false;}else{document.FORMA.OtroCual.disabled=true;}"><option value="" ></option><option value="01" <? if($NaturalezaEvento=="01"){echo "selected";}?>>Accidente de transito</option><option value="02" <? if($NaturalezaEvento=="02"){echo "selected";}?>>Sismo</option><option value="03" <? if($NaturalezaEvento=="03"){echo "selected";}?>>Maremoto</option><option value="04" <? if($NaturalezaEvento=="04"){echo "selected";}?>>Erupcion volcanica</option><option value="05" <? if($NaturalezaEvento=="05"){echo "selected";}?>>Deslizamiento de tierra</option><option value="06" <? if($NaturalezaEvento=="06"){echo "selected";}?>>Inundacion</option><option value="07" <? if($NaturalezaEvento=="07"){echo "selected";}?>>Avalancha</option><option value="08" <? if($NaturalezaEvento=="08"){echo "selected";}?>>Incendio natural</option><option value="09" <? if($NaturalezaEvento=="09"){echo "selected";}?>>Explocion terrorista</option><option value="10" <? if($NaturalezaEvento=="10"){echo "selected";}?>>Incendio terrorista</option><option value="11" <? if($NaturalezaEvento=="11"){echo "selected";}?>>Combate</option><option value="12" <? if($NaturalezaEvento=="12"){echo "selected";}?>>Ataques a Municipios</option><option value="13" <? if($NaturalezaEvento=="13"){echo "selected";}?>>Masacre</option><option value="14" <? if($NaturalezaEvento=="14"){echo "selected";}?>>Desplazados</option><option value="15" <? if($NaturalezaEvento=="15"){echo "selected";}?>>Mina antipersonal</option><option value="16" <? if($NaturalezaEvento=="16"){echo "selected";}?>>Huracan</option><option value="17" <? if($NaturalezaEvento=="17"){echo "selected";}?>>Otro</option></select>
</td>
<td bgcolor="#e5e5e5" style="font-weight:bold" >Si Otro Cual?</td>
<td colspan="3"><input type="text" name="OtroCual" value="<? echo $OtroCual?>" style="width:100%" maxlength="25"></td>
</tr>
<tr>
<td bgcolor="#e5e5e5" style="font-weight:bold">Direccion Ocurrencia</td><td ><input type="text" name="DireccionAccidente" value="<? echo $DireccionAccidente?>" style="width:100%" maxlength="40" ></td>
<td bgcolor="#e5e5e5" style="font-weight:bold">Fecha Evento</td><td ><input type="text" id="FechaEvento" name="FechaEvento" value="<? echo $FechaEvento?>" style="width:100%" onFocus="Cal(this);" onClick="popUpCalendar(this, this, 'yyyy-mm-dd')" readonly></td>
<td bgcolor="#e5e5e5" style="font-weight:bold">Hora</td>
<td >
<select name="HorasEvento" style="font-size:10px" >
    <?
    for($i=0; $i<=23;$i++)
	{
		if($i==$HorasEvento)
		{
			?><option value="<? echo $i?>" selected><? if($i<10){echo "0".$i;}else{ echo $i;}?></option><?	
        }
		else
		{
			?><option value="<? echo $i?>"><? if($i<10){echo "0".$i;}else{ echo $i;}?></option><?		
		}
    }?>
    </select>:
    <select name="MinutosEvento" style="font-size:10px">
    <?
    for($j=0; $j<=59;$j++)
	{
		if($j==$MinutosEvento)
		{
			?><option value="<? echo $j?>" selected><? if($j<10){echo "0".$j;}else{ echo $j;}?></option><?	
        }
		else
		{
			?><option value="<? echo $j?>"><? if($j<10){echo "0".$j;}else{ echo $j;}?></option><?		
		}
    }?>
</select>
</td>
</tr>
<tr>
<!--<td bgcolor="#e5e5e5" style="font-weight:bold">Departamento</td><td><input type="text" name="DepartamentoOcurrencia" value="<? echo $DepartamentoOcurrencia?>" onKeyUp="xajax_autocompleta('divDeptoOcurrencia',this.value,this.name,document.FORMA.CodDepartamentoOcurrencia.name,'central.departamentos','codigo','departamento','','')"><b>Cod</b> <input type="text" name="CodDepartamentoOcurrencia" value="<? echo $CodDepartamentoOcurrencia?>" style="width:40px" maxlength="2" readonly><div id="divDeptoOcurrencia" style="margin-top:3px;"></div></td>
<td bgcolor="#e5e5e5" style="font-weight:bold">Municipio</td><td colspan="3"><input type="text" name="MunicipioOcurrencia" value="<? echo $MunicipioOcurrencia?>" onKeyUp="xajax_autocompleta('divMunicipioOcurrencia',this.value,this.name,document.FORMA.CodMunicipioOcurrencia.name,'central.municipios','codmpo','municipio','departamento',document.FORMA.CodDepartamentoOcurrencia.value)" style="width:100px"><b>Cod</b> <input type="text" name="CodMunicipioOcurrencia" value="<? echo $CodMunicipioOcurrencia?>" style="width:40px" maxlength="3" readonly><div id="divMunicipioOcurrencia" style="margin-top:3px;"></div></td>-->
<td bgcolor="#e5e5e5" style="font-weight:bold">Departamento</td><td><input type="text" name="DepartamentoOcurrencia" value="<? echo $DepartamentoOcurrencia?>" ><b>Cod</b> <input type="text" name="CodDepartamentoOcurrencia" value="<? echo $CodDepartamentoOcurrencia?>" style="width:40px" maxlength="2" readonly></td>
<td bgcolor="#e5e5e5" style="font-weight:bold">Municipio</td><td colspan="3"><input type="text" name="MunicipioOcurrencia" value="<? echo $MunicipioOcurrencia?>" style="width:100px"><b>Cod</b> <input type="text" name="CodMunicipioOcurrencia" value="<? echo $CodMunicipioOcurrencia?>" style="width:40px" maxlength="3" readonly></td>
</tr>
</table>
<center>
<input type="button" name="Cancelar" value="Cancelar" onClick="location.href='FURIPSPendientes.php?DatNameSID=<? echo $DatNameSID?>'" />
</center>
</form>
<script type="text/javascript">
if(document.FORMA.NaturalezaEvento.value=='17'){document.FORMA.OtroCual.disabled=false;}else{document.FORMA.OtroCual.disabled=true;}
	
</script>
</body>