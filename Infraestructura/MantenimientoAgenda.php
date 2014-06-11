<?
	if($DatNameSID){session_name("$DatNameSID");}
	session_start();
	include("Funciones.php");
	$ND = getdate();
        if($Guardar)
	{
		if(!$Editar)
		{
			$cons = "Insert into Infraestructura.Mantenimiento (Compania,AutoId,FechaSolicitud,UsuarioSolicitud,DetalleSolicitud,
			EstadoSolicitud,FechaAR,UsuarioAR,Encargado,Agendado,HoraIni,Duracion,FechAgenda,TipoDuracion) values
			('$Compania[0]',$AutoId,'$ND[year]-$ND[mon]-$ND[mday] $ND[hours]:$ND[minutes]:$ND[seconds]','$usuario[0]','$Obs',
			'Aprobado','$ND[year]-$ND[mon]-$ND[mday] $ND[hours]:$ND[minutes]:$ND[seconds]','$usuario[0]','$Responsable',1,'$H:$M',$Valor,'$Fecha','$Tipo')";
		}
		else
		{
			$cons = "Update Infraestructura.Mantenimiento set DetalleSolicitud='$Obs',FechaSolicitud='$ND[year]-$ND[mon]-$ND[mday] $ND[hours]:$ND[minutes]:$ND[seconds]',
			UsuarioSolicitud='$usuario[0]',FechaAR='$ND[year]-$ND[mon]-$ND[mday] $ND[hours]:$ND[minutes]:$ND[seconds]',UsuarioAR='$usuario[0]',Encargado='$Responsable',
			Duracion=$Tiempo Where Compania='$Compania[0]' and AutoId='$AutoId' and HoraIni='$H:$M'";	
		}
		$res = ExQuery($cons);	
		?>
		<script language="javascript">
        	parent.parent.location.href="AgendaMantenimiento.php?DatNameSID=<? echo $DatNameSID?>&Grupo=<? echo $Grupo?>&Responsable=<? echo $Responsable?>&Fecha=<? echo $Fecha?>";
        </script>
		<?
	}
?>
<script language="javascript" src="/Funciones.js"></script>
<script language="javascript">
	function CerrarThis()
	{
		parent.document.getElementById('FrameOpener').style.position='absolute';
		parent.document.getElementById('FrameOpener').style.width='1';
		parent.document.getElementById('FrameOpener').style.height='1';
		parent.document.getElementById('FrameOpener').style.display='none';	
	}
</script>
<?
	$cons = "Select AutoId,EstadoSolicitud,DetalleSolicitud,Duracion from Infraestructura.Mantenimiento 
	Where Compania='$Compania[0]' and AutoId=$AutoId and Agendado=1 and HoraIni='$H:$M' and FechAgenda='$Fecha'";
	$res = ExQuery($cons);
	if(ExNumRows($res)>0)
	{
		$fila=ExFetch($res);
		if($fila[1]=="Aprobado"){$Editar=1; $Obs=$fila[2]; $Tiempo=$fila[3];}
		else{$Mensaje=1;}	
	}
?>
<body background="/Imgs/Fondo.jpg">
<form name="FORMA" method="post">
<input type="hidden" name="Editar" value="<? echo $Editar?>" />
<input type="hidden" name="DatNameSID" value="<? echo $DatNameSID?>" />
<input type="hidden" name="AutoId" value="<? echo $AutoId?>" />
<input type="hidden" name="Responsalbe" value="<? echo $Responsable?>" />
<input type="hidden" name="H" value="<? echo $H?>" />
<input type="hidden" name="M" value="<? echo $M?>" />
<input type="hidden" name="Fecha" value="<? echo $Fecha?>" />
<input type="hidden" name="Grupo" value="<? echo $Grupo?>" />
<table border="0" style='font : normal normal small-caps 12px Tahoma;' align="center" width="100%">
<tr align="center" style="font-weight:bold"><td colspan="2">Mantenimiento agendado para el Dia:<br /> <? echo $Fecha?> a las <? echo "$H:$M";?></td></tr>
<tr align="center" style="font-weight:bold"><td colspan="2">Elemento: <? 
$cons = "Select Nombre,Caracteristicas,Marca from Infraestructura.CodElementos Where Compania='$Compania[0]' and AutoId=$AutoId";
$res = ExQuery($cons);
$fila = ExFetch($res);
echo utf8_decode("$fila[0] $fila[1] $fila[2]");?>
</td></tr>
<tr align="center" style="font-weight:bold"><td colspan="2">Responsable: <? 
$cons = "Select Nombre from Central.Usuarios Where Cedula = '$Responsable'";
$res = ExQuery($cons);
$fila = ExFetch($res);
echo strtoupper("$fila[0]");?></td></tr>
<tr><td width="50%">
	<table style='font : normal normal small-caps 12px Tahoma;' border="1" bordercolor="#e5e5e5" width="100%">
    	<tr><td bgcolor="#e5e5e5" style="font-weight:bold" align="center">Observaciones Mantenimiento Agendado</td></tr>
        <tr><td><textarea rows="5" style="width:100%; background:/Imgs/Fondo.jpg" name="Obs"><? echo $Obs?></textarea></td></tr>
		<tr><td bgcolor="#e5e5e5" style="font-weight:bold" align="center">Tiempo en Mantenimiento</td></tr>
        <tr><td align="center">
        <input type="hidden" name="Valida" value="12" />
        <input type="text" name="Valor" size="4" onkeyup="xNumero(this)" onkeydown="xNumero(this)" 
               onBlur="campoNumero(this);if(parseInt(this.value)>parseInt(Valida.value)){this.value='';};" />
        <select name="Tipo" onchange="if(this.value=='Horas'){Valida.value='12';Valor.value='';}else{Valida.value='180';Valor.value='';}">
            <option <?if($Tipo=="Horas"){echo " selected ";}?>value="Horas">Horas</option>
            <option <?if($Tipo=="Minutos"){echo " selected ";}?>value="Minutos">Minutos</option>
        </select></td></tr>
    </table>
</td>
</tr>
<tr>
<td align="center">
	<button type="submit" name="Guardar" title="Guardar"><img src="/Imgs/b_save.png" /></button>
    <button type="button" name="Cancelar" title="Cancelar" onClick="CerrarThis()"><img src="/Imgs/b_drop.png" /></button>
</td>
</tr>
</table>
</form>
<?
	if($Mensaje)
	{
	?><script language="javascript">alert("El elemento esta siendo procesado");CerrarThis();</script><?	
	}
?>
</body>