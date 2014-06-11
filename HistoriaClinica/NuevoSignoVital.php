<? 
	if($DatNameSID){session_name("$DatNameSID");}
	session_start();
	include("Funciones.php");
	$ND=getdate();
	if($Guardar)
	{
		$Fecha="$ND[year]-$ND[mon]-$ND[mday] $ND[hours]:$ND[minutes]:$ND[seconds]";		
		$cons="Select AutoId from historiaclinica.signosvitales where Compania='$Compania[0]' order by AutoId desc";
		$res=ExQuery($cons);$fila=ExFetch($res);$AutoId=$fila[0];
		if($AutoId){$AutoId++;}else{$AutoId=1;}		
		if(!$NumServicio){$NumServicio=-1;}
		$cons="Insert into historiaclinica.signosvitales (Compania,AutoId,Cedula,NumServicio,Fecha,Usuario,Temperatura,Pulso,Respiracion,TensionArterial1,TensionArterial2) values('$Compania[0]',$AutoId,'$Paciente[1]',$NumServicio,'$Fecha','$usuario[1]',$Temperatura,$Pulso,$Respiracion,$TensionArterial1,$TensionArterial2)";	
		$res=ExQuery($cons);
		?><script language="javascript">location.href='ContSignosVitales.php?DatNameSID=<? echo $DatNameSID?>&NumServicio=<? echo $NumServicio?>&Servicios=<? echo $Servicios?>';</script><?		
	}
?>
<head>
<script language="javascript" src="/Funciones.js"></script>
<script language="javascript">
function Validar()
{
	if(document.FORMA.Temperatura.value==""){alert("Por favor ingrese el valor para la Temperatura!!!");return false;}
	else{if(parseInt(document.FORMA.Temperatura.value)<0){alert("La Temperatura no puede ser menor que Cero!!!");return false;}}
	if(document.FORMA.Pulso.value==""){alert("Por favor ingrese el valor para el Pulso!!!");return false;}	
	else{if(parseInt(document.FORMA.Pulso.value)<0){alert("El Pulso no puede ser menor que Cero!!!");return false;}}
	if(document.FORMA.Respiracion.value==""){alert("Por favor ingrese el valor para la Respiracion!!!");return false;}	
	else{if(parseInt(document.FORMA.Respiracion.value)<0){alert("La Respiracion no puede ser menor que Cero!!!");return false;}}
	if(document.FORMA.TensionArterial1.value==""){alert("Por favor ingrese el valor para la Tension Arterial!!!");return false;}
	else{if(parseInt(document.FORMA.TensionArterial1.value)<0){alert("La Tension Arterial no puede ser menor que Cero!!!");return false;}}	
	if(document.FORMA.TensionArterial2.value==""){alert("Por favor ingrese el valor para la Tension Arterial!!!");return false;}	
	else{if(parseInt(document.FORMA.TensionArterial2.value)<0){alert("La Tension Arterial no puede ser menor que Cero!!!");return false;}}	
	if(!confirm("Esta seguro de Guardar los Signos Vitales del Paciente <? echo "$Paciente[2] $Paciente[3] $Paciente[4] $Paciente[5] ";?>?")){return false;}
}
</script>
</head>
<body background="/Imgs/Fondo.jpg">
<form name="FORMA" method="post"  target="ContenidoSV" onSubmit="return Validar();">
<input type="hidden" name="DatNameSID" value="<? echo $DatNameSID?>" />
<input type="hidden" name="NumServicio" value="<? echo $NumServicio?>" />
<input type="hidden" name="Servicios" value="<? echo $Servicios?>" />
<table align="center" border="1" bordercolor="#e5e5e5" style='font : normal normal small-caps 12px Tahoma;' >
<tr bgcolor="#e5e5e5" style="font-weight:bold">
    <td colspan="4" align="center"><? echo "Nuevos Signos Vitales: $Paciente[2] $Paciente[3] $Paciente[4] $Paciente[5] - $Paciente[1]";?></td>
</tr>
<tr align="center" bgcolor="#e5e5e5" style="font-weight:bold">
<td>Temperatura <br />(ºC)</td><td>Pulso <br />(x min.)</td><td>Respiracion <br />(x min.)</td><td>Tension Arterial <br />(Diast./Sist.)</td>
</tr>
<tr align="center">
<td><input type="text" name="Temperatura" value="<? echo $Temperatura?>" size="8" onKeyDown="xNumero(this)" onKeyUp="xNumero(this)" maxlength="5"  style="text-align:right"/></td>
<td><input type="text" name="Pulso" value="<? echo $Pulso?>" size="8" onKeyDown="xNumero(this)" onKeyUp="xNumero(this)" maxlength="5" style="text-align:right"/></td>
<td><input type="text" name="Respiracion" value="<? echo $Respiracion?>" size="8"  onkeydown="xNumero(this)" onKeyUp="xNumero(this)" maxlength="5"style="text-align:right"/></td>
<td><input type="text" name="TensionArterial1" value="<? echo $TensionArterial1?>" size="4" onKeyDown="xNumero(this)" onKeyUp="xNumero(this)" maxlength="5" style="text-align:right" />/<input type="text" name="TensionArterial2" value="<? echo $TensionArterial2?>" size="5" onKeyDown="xNumero(this)" onKeyUp="xNumero(this)" maxlength="5" style="text-align:right"/></td>
</tr>
</table>
<center>
<input type="submit" name="Guardar" value="Guardar" />
<input type="button" name="Ir a Signos Vitales" value="Ir a Signos Vitales" onClick="location.href='ContSignosVitales.php?DatNameSID=<? echo $DatNameSID?>&NumServicio=<? echo $NumServicio?>&Servicios=<? echo $Servicios?>'"/>
</center>
</form>
</body>