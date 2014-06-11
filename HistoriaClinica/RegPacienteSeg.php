<?
	if($DatNameSID){session_name("$DatNameSID");}
	session_start();
	include("Funciones.php");
	$ND=getdate();
	if($Guardar){
		$cons="select tiposervicio,numservicio from salud.servicios where compania='$Compania[0]' and cedula='$Paciente[1]' and estado='AC'";
		$row=ExQuery($cons);
		$Ambito=ExFetch($row);
		if($Ambito[1]){
			$cons="select pabellon from salud.pacientesxpabellones where compania='$Compania[0]' and numservicio=$Ambito[1] and cedula='$Paciente[1]'";
			$row=ExQuery($cons);
			$Unidad=ExFetch($row);
		}
		$cons="select numrep from historiaclinica.regpacienteseg where compania='$Compania[0]' order by numrep desc";
		$row=ExQuery($cons);
		$fila=ExFetch($row);
		$Autoid=$fila[0]+1;
		
		$cons="select cargo from salud.medicos where compania='$Compania[0]' and usuario='$usuario[1]'";
		$row=ExQuery($cons);		
		$fila=ExFetch($row);
		if($Medio1=="on"){$Medio1="1";}else{$Medio1="0";}
		if($Medio2=="on"){$Medio2="1";}else{$Medio2="0";}
		if($Medio3=="on"){$Medio3="1";}else{$Medio3="0";}
		$cons="insert into historiaclinica.regpacienteseg 
		(compania,usuariocrea,fechacrea,equip1,equip2,equip3,equip4,medio1,medio2,medio3,crono1,crono2,crono3,crono4,crono5,crono6,crono7,cargo,formato,tipoformato,idhistoria,ambito,unidad,numrep,cedula)
		values ('$Compania[0]','$usuario[1]','$ND[year]-$ND[mon]-$ND[mday] $ND[hours]:$ND[minutes]:$ND[seconds]','$Equip1','$Equip2','$Equip3','$Equip4','$Medio1','$Medio2','$Medio3','$CronoInc1','$CronoInc2','$CronoInc3','$CronoInc4','$CronoInc5','$CronoInc6','$CronoInc7','$fila[0]','$Formato','$TipoFormato',$IdHistoria,'$Ambito[0]','$Unidad[0]',$Autoid,'$Paciente[1]')"; 
		$row=ExQuery($cons);
		//echo $cons;?>
		<script language="javascript">
			location.href='Datos.php?DatNameSID=<? echo $DatNameSID?>&Formato=<? echo $Formato?>&TipoFormato=<? echo $TipoFormato?>&LimSup=<? echo $LimSup?>&LimInf=<? echo $LimInf?>&IdHistoria=<? echo  $IdHistoria?>#<? echo  $IdHistoria?>';
		</script>	
<?	}
?>	
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<script language="javascript" src="/Funciones.js"></script>
<script language="javascript">
	function Validar()
	{
		if(document.FORMA.Equip1.value==""){alert("Debe digitar al menos un integrante del equipo de investigacion!!!");return false;}
		if(document.FORMA.CronoInc1.value==""){alert("Debe Digitar almenos un momento de la cronologia!!!");return false;}
	}
</script>
</head>

<body background="/Imgs/Fondo.jpg">
<form name="FORMA" method="post" onSubmit="return Validar()">
<table bordercolor="#e5e5e5" border="1" style='font : normal normal small-caps 12px Tahoma;' align="left" cellpadding="4">
<tr bgcolor="#e5e5e5" style="font-weight:bold" align="center"><td colspan="2">PROTOCOLO DE INVESTIGACION DE EVENTOS ADVERSOS</td></tr>
<tr bgcolor="#e5e5e5" style="font-weight:bold" align="center"><td colspan="2">Equipo Investigador</td></tr>
<tr><td colspan="2"><strong>1.</strong><input type="text" name="Equip1" onKeyDown="xLetra(this)" onKeyPress="xLetra(this)" onKeyUp="xLetra(this)" style="width:400"></td></tr>
<tr><td colspan="2"><strong>2.</strong><input type="text" name="Equip2" onKeyDown="xLetra(this)" onKeyPress="xLetra(this)" onKeyUp="xLetra(this)" style="width:400"></td></tr>
<tr><td colspan="2"><strong>3.</strong><input type="text" name="Equip3" onKeyDown="xLetra(this)" onKeyPress="xLetra(this)" onKeyUp="xLetra(this)" style="width:400"></td></tr>
<tr><td colspan="2"><strong>4.</strong><input type="text" name="Equip4" onKeyDown="xLetra(this)" onKeyPress="xLetra(this)" onKeyUp="xLetra(this)" style="width:400"></td></tr>
<tr bgcolor="#e5e5e5" style="font-weight:bold" align="center"><td colspan="2">Medios Utilizados para Optener la Informacio</td></tr>
<tr><td><strong>1.</strong>Analisis de la Historia Clinica, Protocolos, Procedimientos</td><td><input type="checkbox" name="Medio1"></td></tr>
<tr><td><strong>2.</strong>Entrevista a las Personas que Interviene en el Proceso</td><td><input type="checkbox" name="Medio2"></td></tr>
<tr><td><strong>3.</strong>Otros Mecanismos: Declaraciones, Observaciones, etc.</td><td><input type="checkbox" name="Medio3"></td></tr>
<tr bgcolor="#e5e5e5" style="font-weight:bold" align="center"><td colspan="2">Cronologia del Incidente</td></tr>
<tr><td colspan="2"><strong>1.</strong><input type="text" name="CronoInc1" onKeyDown="xLetra(this)" onKeyPress="xLetra(this)" onKeyUp="xLetra(this)" style="width:400"></td></tr>
<tr><td colspan="2"><strong>2.</strong><input type="text" name="CronoInc2" onKeyDown="xLetra(this)" onKeyPress="xLetra(this)" onKeyUp="xLetra(this)" style="width:400"></td></tr>
<tr><td colspan="2"><strong>3.</strong><input type="text" name="CronoInc3" onKeyDown="xLetra(this)" onKeyPress="xLetra(this)" onKeyUp="xLetra(this)" style="width:400"></td></tr>
<tr><td colspan="2"><strong>4.</strong><input type="text" name="CronoInc4" onKeyDown="xLetra(this)" onKeyPress="xLetra(this)" onKeyUp="xLetra(this)" style="width:400"></td></tr>
<tr><td colspan="2"><strong>5.</strong><input type="text" name="CronoInc5" onKeyDown="xLetra(this)" onKeyPress="xLetra(this)" onKeyUp="xLetra(this)" style="width:400"></td></tr>
<tr><td colspan="2"><strong>6.</strong><input type="text" name="CronoInc6" onKeyDown="xLetra(this)" onKeyPress="xLetra(this)" onKeyUp="xLetra(this)" style="width:400"></td></tr>
<tr><td colspan="2"><strong>7.</strong><input type="text" name="CronoInc7" onKeyDown="xLetra(this)" onKeyPress="xLetra(this)" onKeyUp="xLetra(this)" style="width:400"></td></tr>
<tr align="center">
	<td colspan="2">    	
    	<input type="submit" value="Guardar" name="Guardar">
      	<input type="button" value="Cancelar" 
        onClick="location.href='Datos.php?DatNameSID=<? echo $DatNameSID?>&Formato=<? echo $Formato?>&TipoFormato=<? echo $TipoFormato?>&LimSup=<? echo $LimSup?>&LimInf=<? echo $LimInf?>&IdHistoria=<? echo  $IdHistoria?>#<? echo  $IdHistoria?>'">  
       
    </td>
</tr>
</table>
<input type="hidden" name="Formato" value="<? echo $Formato?>">
<input type="hidden" name="TipoFormato" value="<? echo $TipoFormato?>">
<input type="hidden" name="LimSup" value="<? echo $LimSup?>">
<input type="hidden" name="LimInf" value="<? echo $LimInf?>">
<input type="hidden" name="IdHistoria" value="<? echo $IdHistoria?>">
<input type="hidden" name="DatNameSID" value="<? echo $DatNameSID?>">
</form>
</body>
</html>
