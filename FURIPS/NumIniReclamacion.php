<? 
	if($DatNameSID){session_name("$DatNameSID");}
	session_start();
	include("Funciones.php");
	$ND=getdate();
	$FechaHoy="$ND[year]-$ND[mon]-$ND[mday]";
	$HoraHoy="$ND[hours]:$ND[minutes]:$ND[seconds]";	
	if($Guardar)
	{
		$cons="Select numreclamacionini from furips.reclamacionini where compania='$Compania[0]'";
		$res=ExQuery($cons);
		if(ExNumRows($res)==0)	
		{
			$cons="insert into furips.reclamacionini (compania,numreclamacionini) values('$Compania[0]', $NumInicial)";
			$res=ExQuery($cons);
			if(!ExError($res))
			{
				?><script language="javascript">alert("El Numero se guardo correctamente!!!");</script><?	
			}
			else			
			{
				?><script language="javascript">alert("No se pudo guardar el numero!!!");</script><?		
			}
		}
		else
		{
			$cons="Update furips.reclamacionini set numreclamacionini=$NumInicial where Compania='$Compania[0]'";
			$res=Exquery($cons);	
			if(!ExError($res))
			{
				?><script language="javascript">alert("El Numero se actualizo correctamente!!!");</script><?	
			}
			else			
			{
				?><script language="javascript">alert("No se pudo guardar el numero!!!");</script><?		
			}
		}
	}
	$cons="Select numreclamacionini from furips.reclamacionini where compania='$Compania[0]'";
	$res=ExQuery($cons);
	$fila=ExFetch($res);
	if(!$NumInicial){$NumInicial=$fila[0];}
?>
<head>
<script language="javascript" src="/Funciones.js"></script>
<script language="javascript">
function Validar()
{
	if(document.FORMA.NumInicial.value==""){alert("Por favor ingrese el numero inicial de Reclamacion!!!");return false;}
	if(parseInt(document.FORMA.NumInicial.value)<=0){alert("El Numero Inicial debe ser mayor que cero!!!");return false;}
}
</script>
</head>
<body background="/Imgs/Fondo.jpg">
<form name="FORMA" method="post" onSubmit="return Validar()">
<input type="hidden" name="DatNameSID" value="<? echo $DatNameSID?>" />
<table border="1" bordercolor="#e5e5e5" style='font : normal normal small-caps 12px Tahoma;' align="center">
<tr>
    <td colspan="2" bgcolor="#e5e5e5" style="font-weight:bold" align="center">NUMERO INICIAL DE RECLAMACION</td>
</tr>
<tr>
<td>Numero</td>
<td><input type="text" name="NumInicial" value="<? echo $NumInicial?>" onKeyDown="xNumero(this)" onKeyUp="xNumero(this)" /></td>
</tr>
</table>
<center><input type="submit" name="Guardar" value="Guardar" /> </center>
</form>
</body>