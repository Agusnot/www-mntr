<? 
	if($DatNameSID){session_name("$DatNameSID");}
	session_start();
	include("Funciones.php");
	$ND=getdate();
	if($Guardar)
	{
		$Fecha="$ND[year]-$ND[mon]-$ND[mday] $ND[hours]:$ND[minutes]";		
		$cons="Select AutoId,FechaFormato,NumRegistro,UsuarioCrea from historiaclinica.ctrlliquidos where Compania='$Compania[0]' 
		and Cedula='$Paciente[1]' and NumServicio=$NumServicio and Estado='AC' order by AutoId,NumRegistro desc";			
		$res=ExQuery($cons);$fila=ExFetch($res);$AutoId=$fila[0];$FechaFormato=$fila[1];$NumRegistro=$fila[2];$UsuarioCrea=$fila[3];
		if($NumRegistro=="0")
		{
			$NumRegistro=1;
			$cons="Update HistoriaClinica.CtrlLiquidos set cedula='$Paciente[1]', numservicio=$NumServicio, numregistro=1, fechaformato='$FechaFormato', fecharegistro='$Fecha', hora='$Hora', parclase='$ParClase', parcantidad=$ParCantidad, oralclase='$OralClase',   oralcantidad=$OralCantidad, orina=$Orina, materiafecal=$MateriaFecal, vomito=$Vomito, drenaje=$Drenaje, succion=$Succion, usuariocrea='$UsuarioCrea', usuarioregistra='$usuario[1]', observaciones='$Observaciones' where Compania='$Compania[0]' and Cedula='$Paciente[1]'
			and NumServicio=$NumServicio and AutoId=$AutoId and NumRegistro=0";			
			$res=ExQuery($cons);
		}
		else
		{
			$NumRegistro++;
			$cons="Insert into historiaclinica.CtrlLiquidos (Compania,AutoId, Cedula, numservicio, numregistro, fechaformato, fecharegistro,
			hora, parclase, parcantidad, oralclase, oralcantidad, orina, materiafecal, vomito, drenaje, succion, usuariocrea, usuarioregistra, 
			estado, observaciones)
			values('$Compania[0]',$AutoId,'$Paciente[1]',$NumServicio,$NumRegistro,'$FechaFormato','$Fecha','$Hora','$ParClase',
			$ParCantidad,'$OralClase',$OralCantidad,$Orina,$MateriaFecal,$Vomito,$Drenaje,$Succion,'$UsuarioCrea','$usuario[1]',
			'AC','$Observaciones')";	
			$res=ExQuery($cons);	
		}						
		?><script language="javascript">location.href='ContControlLiquidos.php?DatNameSID=<? echo $DatNameSID?>&NumServicio=<? echo $NumServicio?>&Servicios=<? echo $Servicios?>';</script><?	
	}
	if($AbrirFormato)
	{
		$FechaFormato="$ND[year]-$ND[mon]-$ND[mday] $ND[hours]:$ND[minutes]";		
		$cons="Select AutoId from historiaclinica.ctrlliquidos where Compania='$Compania[0]' order by AutoId desc";
		$res=ExQuery($cons);$fila=ExFetch($res);$AutoId=$fila[0];
		if($AutoId){$AutoId++;}else{$AutoId=1;}					
		$cons="Insert into historiaClinica.ctrlliquidos (Compania,AutoId,Cedula,NumServicio,NumRegistro,FechaFormato,UsuarioCrea,Estado)
		values('$Compania[0]',$AutoId,'$Paciente[1]',$NumServicio,0,'$FechaFormato','$usuario[1]','AC')";
		$res=ExQuery($cons);$AbrirFormato="";
		?><script language="javascript">
		location.href='ContControlLiquidos.php?DatNameSID=<? echo $DatNameSID?>&NumServicio=<? echo $NumServicio?>&Servicios=<? echo $Servicios?>&NumFormato=<? echo $NumFormato?>';
		parent(2).location.href="OpcionesControlLiquidos.php?DatNameSID=<? echo $DatNameSID?>&NumServicio=<? echo $NumServicio?>&NumFormato=<? echo $NumFormato?>";
        </script><?
	}
	if($CerrarFormato)
	{
		$FechaCierre="$ND[year]-$ND[mon]-$ND[mday] $ND[hours]:$ND[minutes]";		
		$cons="Select AutoId from historiaclinica.ctrlliquidos where Compania='$Compania[0]' and Cedula='$Paciente[1]'
		and NumServicio=$NumServicio and estado='AC' order by AutoId desc";
		$res=ExQuery($cons);$fila=ExFetch($res);$AutoId=$fila[0];		
		$cons="Update HistoriaClinica.CtrlLiquidos set estado='CE',fechacierre='$FechaCierre', usuariocierra='$usuario[1]' 
		where Compania='$Compania[0]' and Cedula='$Paciente[1]'	and NumServicio=$NumServicio and AutoId=$AutoId and Estado='AC'";
		$res=ExQuery($cons);$CerrarFormato="";
		?><script language="javascript">
		location.href='ContControlLiquidos.php?DatNameSID=<? echo $DatNameSID?>&NumServicio=<? echo $NumServicio?>&Servicios=<? echo $Servicios?>&NumFormato=<? echo $NumFormato?>';
		parent(2).location.href="OpcionesControlLiquidos.php?DatNameSID=<? echo $DatNameSID?>&NumServicio=<? echo $NumServicio?>&NumFormato=<? echo $NumFormato?>";
        </script><?
	}
?>
<head>
<script language="javascript" src="/Funciones.js"></script>
<script language="javascript">
function Validar()
{
	if(document.FORMA.Hora.value==""){alert("Por favor ingrese la Hora!!!");return false;}	
	if(document.FORMA.ParClase.value==""){alert("Por favor ingrese la clase de Liquido Parenteral!!!");return false;}	
	if(document.FORMA.ParCantidad.value==""){alert("Por favor ingrese la Cantidad de Liquido Parenteral!!!");return false;}			
	if(document.FORMA.OralClase.value==""){alert("Por favor ingrese la clase de Liquido Oral!!!");return false;}
	if(document.FORMA.OralCantidad.value==""){alert("Por favor ingrese la Cantidad de Liquido Oral!!!");return false;}
	if(document.FORMA.Orina.value==""){alert("Por favor ingrese la Cantidad de Orina!!!");return false;}
	if(document.FORMA.MateriaFecal.value==""){alert("Por favor ingrese la Cantidad de Materia Fecal!!!");return false;}
	if(document.FORMA.Vomito.value==""){alert("Por favor ingrese la Cantidad de Vomito!!!");return false;}
	if(document.FORMA.Drenaje.value==""){alert("Por favor ingrese la Cantidad de liquido Drenado!!!");return false;}
	if(document.FORMA.Succion.value==""){alert("Por favor ingrese la Cantidad de liquido Succionado!!!");return false;}
	if(document.FORMA.ParClase.value!="---")
	{
		if(document.FORMA.ParCantidad.value=="0"){alert("Debe Ingresar la Cantidad de Liquido Parenteral Administrado");return false;}	
	}
	if(document.FORMA.OralClase.value!="---")
	{
		if(document.FORMA.OralCantidad.value=="0"){alert("Debe Ingresar la Cantidad de Liquido Oral Administrado");return false;}	
	}
	if(document.FORMA.ParClase.value=="---"&&document.FORMA.OralClase.value=="---"&&document.FORMA.ParCantidad.value=="0"&&document.FORMA.OralCantidad.value=="0"&&document.FORMA.Orina.value=="0"&&document.FORMA.MateriaFecal.value=="0"&&document.FORMA.Vomito.value=="0"&&document.FORMA.Drenaje.value=="0"&&document.FORMA.Succion.value=="0")
	{
		alert("Debe Ingresar por lo menos un valor valido para el Control de Liquidos");return false;	
	}
}
</script>
</head>
<body background="/Imgs/Fondo.jpg">
<form name="FORMA" method="post"  target="ContenidoCL" onSubmit="return Validar();">
<input type="hidden" name="DatNameSID" value="<? echo $DatNameSID?>" />
<input type="hidden" name="NumServicio" value="<? echo $NumServicio?>" />
<input type="hidden" name="Servicios" value="<? echo $Servicios?>" />
<center><? echo "<B>CONTROL DE LIQUIDOS <BR>$Paciente[2] $Paciente[3] $Paciente[4] $Paciente[5] - $Paciente[1]</B>";?></center>
<table align="center" border="1" bordercolor="#e5e5e5" style='font : normal normal small-caps 12px Tahoma;' >
<tr bgcolor="#e5e5e5" style="font-weight:bold" align="center">
<td rowspan=3>Hora</td><td colspan=4>Liquidos administrados</td><td colspan=5>Liquidos eliminados</td>
</tr>
<tr bgcolor="#e5e5e5" style="font-weight:bold" align="center"><td colspan=2>Parenteral</td><td colspan=2>Oral</td><td rowspan=2>Orina</td><td rowspan=2>Materia fecal</td><td rowspan=2>Vomito</td><td rowspan=2>Drenaje</td><td rowspan=2>Succion</td>
</tr>
<tr bgcolor="#e5e5e5" style="font-weight:bold" align="center"><td>Clase</td><td>Cantidad</td><td>Clase</td><td>Cantidad</td>
</tr>
<tr align="center">
<td><input type="text" name="Hora" value="" size="2" maxlength="5" /></td>
<td><input type="text" name="ParClase" value="---" size="6" onKeyDown="xLetra(this)" onKeyUp="xLetra(this)"/></td>
<td><input type="text" name="ParCantidad" value="0" size="6" onKeyDown="xNumero(this)" onKeyUp="xNumero(this)" maxlength="5"/></td>
<td><input type="text" name="OralClase" value="---" size="6" onKeyDown="xLetra(this)" onKeyUp="xLetra(this)"/></td>
<td><input type="text" name="OralCantidad" value="0" size="6" onKeyDown="xNumero(this)" onKeyUp="xNumero(this)" maxlength="5"/></td>
<td><input type="text" name="Orina" value="0" size="6" onKeyDown="xNumero(this)" onKeyUp="xNumero(this)" maxlength="5"/></td>
<td><input type="text" name="MateriaFecal" value="0" size="6" onKeyDown="xNumero(this)" onKeyUp="xNumero(this)" maxlength="5"/></td>
<td><input type="text" name="Vomito" value="0" size="6" onKeyDown="xNumero(this)" onKeyUp="xNumero(this)" maxlength="5"/></td>
<td><input type="text" name="Drenaje" value="0" size="6" onKeyDown="xNumero(this)" onKeyUp="xNumero(this)" maxlength="5"/></td>
<td><input type="text" name="Succion" value="0" size="6" onKeyDown="xNumero(this)" onKeyUp="xNumero(this)" maxlength="5"/></td>
</tr>
</table>
<b>Observaciones:</b><br />
<input type="text" name="Observaciones" value="<? echo $Observaciones?>" size="122" /><br />
<center>
<input type="submit" name="Guardar" value="Guardar" />
<input type="button" name="Cancelar" value="Cancelar" onClick="location.href='ContControlLiquidos.php?DatNameSID=<? echo $DatNameSID?>&NumServicio=<? echo $NumServicio?>&Servicios=<? echo $Servicios?>'"/>
</center>
</form>
</body>