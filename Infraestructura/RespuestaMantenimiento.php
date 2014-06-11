<?
	if($DatNameSID){session_name("$DatNameSID");}
	session_start();
	include("Funciones.php");
	$ND = getdate();
        $nocodi = explode("|",$AutoId);
?>
	<script language="javascript">
    function CerrarThis()
	{
		parent.document.getElementById('FrameOpener').style.position='absolute';
		//parent.document.getElementById('FrameOpener').style.top='1px';
		//parent.document.getElementById('FrameOpener').style.left='1px';
		parent.document.getElementById('FrameOpener').style.width='1';
		parent.document.getElementById('FrameOpener').style.height='1';
		parent.document.getElementById('FrameOpener').style.display='none';
	}
    </script>
<?	
	if($Guardar)
	{
		if(!$FechaResp){$FechaResp = "$ND[year]-$ND[mon]-$ND[mday] $ND[hours]:$ND[minutes]:$ND[seconds]";}
		else{$FechaResp=$FechaResp." $ND[hours]:$ND[minutes]:$ND[seconds]";}
		if($ampm=="PM"){$HHIni=$HHIni + 12;}
		if(!$DuracMant){$DuracMant = 0;}
		if(count($nocodi)==1)
		{
			if($Estado_Actual)
            {
                $AdUpdate_EstadoAct=",ActEstado ='$Estado_Actual',
                FechaActEstado='$ND[year]-$ND[mon]-$ND[mday] $ND[hours]:$ND[minutes]:$ND[seconds]'";
                $consxx = "Update Infraestructura.Codelementos set EstadoAct = '$Estado_Actual',
                FechaEstadoAct='$ND[year]-$ND[mon]-$ND[mday] $ND[hours]:$ND[minutes]:$ND[seconds]',UsuarioEstadoAct='$usuario[1]'
                Where Compania='$Compania[0]' and AutoId = $AutoId";
                $resxx = ExQuery($consxx);
            }
            $cons = "Update Infraestructura.Mantenimiento set FechUltRev='$FechaResp',
			RepTecnico='$RepTecnico', TraRealizado='$TraRealizado', Observaciones='$Observaciones', Repuestos='$Repuestos', TotCosto=$TotCosto,
			EstadoSolicitud='Revisado', ClaseMantenimiento='$ClaseMantenimiento', TipoMantenimiento='$TipoMantenimiento', HoraIni='$HHIni:$MMIni',
            duracion=$DuracMant $AdUpdate_EstadoAct
			Where Compania='$Compania[0]' and ( EstadoSolicitud='Aprobado' or EstadoSolicitud='Revisado') and AutoId=$AutoId";
        }
		else
		{
			$cons = "Update Infraestructura.Mantenimiento set FechUltRev='$FechaResp',
			RepTecnico='$RepTecnico', TraRealizado='$TraRealizado', Observaciones='$Observaciones', Repuestos='$Repuestos', TotCosto=$TotCosto,
			EstadoSolicitud='Revisado', ClaseMantenimiento='$ClaseMantenimiento', TipoMantenimiento='$TipoMantenimiento', HoraIni = '$HHIni:$MMIni',
			duracion=$DuracMant
			Where Compania='$Compania[0]' and ( EstadoSolicitud='Aprobado' or EstadoSolicitud='Revisado') and AutoId=0
			and Descripcion='$nocodi[0]' and Tercero='$nocodi[2]' and CC='$nocodi[1]' and SubUbicacion='$nocodi[3]' and FechaSolicitud ='$nocodi[4]'";
		}
		$res = ExQuery($cons);
        $cons = "Select usuario from Central.UsuariosxModulos Where Modulo = 'Aprobacion Mantenimiento'
        and Madre = 'Almacen' and COmpania = '$Compania[0]'";
        $res = ExQuery($cons);
        if(ExNumRows($res)>0)
        {
            $consxx = "Select Id from Central.Correos Where COmpania = '$Compania[0]' order by ID desc LIMIT 1";
            $resxx = ExQuery($consxx);
            $filaxx = ExFetch($resxx);
            $Id = $filaxx[0] + 1;
            $Ad_Mensaje = "<br>
            <b>Costo Total:</b>$TotCosto<br>";
            $Mensaje = "El usuario <b>$usuario[0]</b> 
            realizo el mantenimiento del Elemento <b>$nombre_elemento</b><br>
            El dia $FechaResp y con el siguiente reporte tecnico<br>
            <b>Clase de Mantenimiento:</b>$ClaseMantenimiento<br>
            <b>Tipo Mantenimiento:</b>$TipoMantenimiento<br>
            <b>Reporte Tecnico:</b>$RepTecnico<br>
            <b>Trabajo Realizado:</b>$TraRealizado<br>
            <b>Observaciones:</b>$Observaciones<br>
            <b>Repuestos:</b>$Repuestos";
            while($fila = ExFetch($res))
            {
                $cons1 = "Insert into Central.Correos
                (Compania,usucrea,fechacrea,usurecive,mensaje,Id,Asunto,Estado,EstadoEnv)
                 values
                ('$Compania[0]','$usuario[1]','$ND[year]-$ND[mon]-$ND[mday] $ND[hours]:$ND[minutes]:$ND[seconds]',
                '$fila[0]','$Mensaje.$Ad_Mensaje',$Id,'<font color=red><b>MENSAJE DEL SISTEMA!!</b></font>','AC','AC')";
                $res1 = ExQuery($cons1);
                $Id++;
            }
        }
        $Mensaje = $Mensaje." Revise las solicitudes para realizar la evaluacion 
            <a href=/Infraestructura/SolMantenimiento.php?Evaluar=1&DatNameSID=$DatNameSID>aqui</a>";
        if($DdBaja == "on")
        {
            $Mensaje = $Mensaje."<br><br>Se ha considerado que el elemento debe ser dado de baja";
            $consxyz = "Select usuario from Central.UsuariosxModulos Where Compania='$Compania[0]'
            and Modulo='Bajas' and Madre='Almacen'";
            $resxyz = ExQuery($consxyz);
            while($filaxyz = ExFetch($resxyz))
            {
                $consxyz1 = "Insert into Central.Correos
                (Compania,usucrea,fechacrea,usurecive,mensaje,Id,Asunto,Estado,EstadoEnv)
                 values
                ('$Compania[0]','$usuario[1]','$ND[year]-$ND[mon]-$ND[mday] $ND[hours]:$ND[minutes]:$ND[seconds]',
                '$filaxyz[0]','$Mensaje<br><br><b>DAR DE BAJA AL PRODUCTO CON CODIGO: $codigo_elemento</b>',
                $Id,'<font color=red><b>MENSAJE DEL SISTEMA!!</b></font>','AC','AC')";
                $resxyz1 = ExQuery($consxyz1);
                $Id++;
            }
        }
        $cons1 = "Insert into Central.Correos
        (Compania,usucrea,fechacrea,usurecive,mensaje,Id,Asunto,Estado,EstadoEnv)
         values
        ('$Compania[0]','$usuario[1]','$ND[year]-$ND[mon]-$ND[mday] $ND[hours]:$ND[minutes]:$ND[seconds]',
        '$usuario_sol','$Mensaje',$Id,'<font color=red><b>MENSAJE DEL SISTEMA!!</b></font>','AC','AC')";
        $res1 = ExQuery($cons1);
        $Id++;
		?>
		<script language="javascript">
            //CerrarThis();
            //parent.document.FORMA.submit();
        </script>
		<?	
	}
?>
<script language="javascript" src="/Funciones.js"></script>
<script language='javascript' src="/calendario/popcalendar.js"></script>
<script language="javascript">
	function Validar()
	{
		if(document.FORMA.RepTecnico.value == ""){alert("Debe llenar el reporte tecnico");return false;}
		if(document.FORMA.TraRealizado.value == ""){alert("Debe llenar el trabajo realizado");return false;}
		if(document.FORMA.TotCosto.value == ""){alert("Registre el Costo total");return false;}	
	}
</script>
<?
	if(count($nocodi)==1)
        {
            $cons = "Select RepTecnico,TraRealizado,Observaciones,Repuestos,TotCosto,TipoMantenimiento,ClaseMantenimiento,
            HoraIni,Duracion From Infraestructura.Mantenimiento
            Where Compania='$Compania[0]' and AutoId=$AutoId and EstadoSolicitud='Revisado'";
	}
        else
        {
            $cons = "Select RepTecnico,TraRealizado,Observaciones,Repuestos,TotCosto,TipoMantenimiento,ClaseMantenimiento,
            HoraIni,Duracion From Infraestructura.Mantenimiento
            Where Compania='$Compania[0]' and AutoId=0 and EstadoSolicitud='Revisado'
            and Descripcion='$nocodi[0]' and Tercero='$nocodi[2]' and CC='$nocodi[1]' and SubUbicacion='$nocodi[3]' and FechaSolicitud ='$nocodi[4]'";
        }
        $res = ExQuery($cons);
	$fila = ExFetch($res);
	$RepTecnico = $fila[0]; $TraRealizado = $fila[1]; $Observaciones = $fila[2]; $Repuestos = $fila[3];
	$TotCosto = $fila[4];
        $TipoMantenimiento = $fila[5]; $ClaseMantenimiento = $fila[6];
        $hora=explode(":",$fila[7]);
        if($hora[0]>12){$HHIni = $hora[0] - 12; $ampm = "PM";}
        else{$HHIni = $hora[0];$ampm = "AM";}
        $MMIni = $hora[1];
        $DuracMant = $fila[8];

?>
<body background="/Imgs/Fondo.jpg">
<form name="FORMA" method="post" onSubmit="return Validar()">
<input type="hidden" name="DatNameSID" value="<? echo $DatNameSID ?>" />
<input type="hidden" name="AutoId" value="<? echo $AutoId?>"  />
<input type="hidden" name="usuario_sol" value="<?echo $usuario_sol?>" />
<input type="hidden" name="nombre_elemento" value="<?echo $nombre_elemento?>" />
<input type="hidden" name="codigo_elemento" value="<?echo $codigo_elemento?>" />
<div align="right">
<button name="Cerrar" title="Cerrar" onClick="CerrarThis()"><img src="/Imgs/b_drop.png" /></button>
</div>
<table style='font : normal normal small-caps 12px Tahoma;' border="1" bordercolor="#e5e5e5" width="100%">
	<?
	if($Adm)
	{
	?>
	<tr bgcolor="#e5e5e5" style="font-weight:bold" align="center">
    	<td align="right">Fecha de Mantenimiento</td>
        <td align="left">
        <input type="text" name="FechaResp" readonly="readonly" style="text-align:center"
        	onclick="popUpCalendar(this, FORMA.FechaResp, 'yyyy-mm-dd')"  value="<? echo "$ND[year]-$ND[mon]-$ND[mday]"?>"  />
        </td>
    </tr>
	<?
	}
	?>
    <tr bgcolor="#e5e5e5" style="font-weight:bold" align="center">
        <td align="right" <? if($AutoId){ echo " width='10%' ";}?> >Clase de Mantenimiento
    <select name="ClaseMantenimiento" style="text-align:center">
	<?
    $cons = "Select Clase From Infraestructura.ClasesMantenimiento";
	$res = ExQuery($cons);
	while($fila = ExFetch($res))
	{
		if($ClaseMantenimiento == $fila[0]){echo "<option selected value='$fila[0]'>$fila[0]</option>";}
		else{echo "<option value='$fila[0]'>$fila[0]</option>";	}
	}
	?>
</select></td>
<td align="right">Tipo de Mantenimiento
    <select name="TipoMantenimiento" text-align:center">
	<?
    $cons = "Select Tipo From Infraestructura.TiposMantenimiento";
	$res = ExQuery($cons);
	while($fila = ExFetch($res))
	{
		if($TipoMantenimiento==$fila[0]){echo "<option selected value='$fila[0]'>$fila[0]</option>";}
		else{echo "<option value='$fila[0]'>$fila[0]</option>";	}
	}
	?>
</select></td></tr>
<tr><td width="50%">
	<table style='font : normal normal small-caps 12px Tahoma;' border="1" bordercolor="#e5e5e5" width="100%">
    <tr bgcolor="#e5e5e5" style="font-weight:bold"><td>Reporte Tecnico</td></tr>
    <tr><td>
    	<textarea name="RepTecnico" rows="3" style="width:100%; background: '/Imgs/Fondo.jpg'" onKeyUp="xLetra(this)" onKeyDown="xLetra(this)"><? echo $RepTecnico ?></textarea>
    </td></tr>
    <tr bgcolor="#e5e5e5" style="font-weight:bold"><td>Trabajo Realizado</td></tr>
    <tr><td>
    	<textarea name="TraRealizado" rows="4" style="width:100%; background: '/Imgs/Fondo.jpg'" onKeyUp="xLetra(this)" onKeyDown="xLetra(this)"><? echo $TraRealizado ?></textarea>
    </td></tr>
    <tr bgcolor="#e5e5e5" style="font-weight:bold"><td>Observaciones</td></tr>
    <tr><td>
    	<textarea name="Observaciones" rows="4" style="width:100%; background: '/Imgs/Fondo.jpg'" onKeyUp="xLetra(this)" onKeyDown="xLetra(this)"><? echo $Observaciones ?></textarea>
    </td></tr>
    </table>
</td>
<td width="50%" valign="top">
	<table style='font : normal normal small-caps 12px Tahoma;' border="1" bordercolor="#e5e5e5" width="100%">
    <tr bgcolor="#e5e5e5" style="font-weight:bold"><td colspan="2">Repuestos</td></tr>
    <tr><td colspan="2">
    	<textarea name="Repuestos" rows="7" style="width:100%; background: '/Imgs/Fondo.jpg'" onKeyUp="xLetra(this)" onKeyDown="xLetra(this)"><? echo $Repuestos ?></textarea>
    </td></tr>
    <tr bgcolor="#e5e5e5" style="font-weight:bold"><td align="center" colspan="2">Costo Total</td></tr>
    <tr><td align="right" colspan="2">
    	<input type="text" name="TotCosto" style="text-align:right" value="<? echo $TotCosto?>" 
        onkeyup="xNumero(this)" onKeyDown="xNumero(this)" onBlur="campoNumero(this)"/>
    </td></tr>
    <tr bgcolor="#e5e5e5" style="font-weight: bold">
        <td align="right">Estado Actual</td>
        <td>
            <select name="Estado_Actual"><option value=""></option>
                <?
                $cons = "Select nombre from Central.Estados";
                $res = ExQuery($cons);
                while($fila = ExFetch($res))
                {
                    ?><option value="<?echo $fila[0]?>"><? echo $fila[0]?></option><?
                }
                ?>
            </select>
        </td>
    </tr>
    <tr bgcolor="#e5e5e5" style="font-weight:bold" align="center">
        <td>Hora de Inicio(HH:MM)</td><td>Tiempo Mantenimiento</td>
    </tr>
    <tr>
        <td><input type="text" name="HHIni" value="<? echo $HHIni?>" size="3" onKeyUp="xNumero(this)" onKeyDown="xNumero(this)" 
                   onBlur="campoNumero(this);if(this.value>12 || this.value<0){this,value='';}" style=" text-align: right" />
        :<input type="text" name="MMIni" value="<? echo $MMIni?>" size="3" onKeyUp="xNumero(this)" onKeyDown="xNumero(this)" 
                onBlur="campoNumero(this);if(this.value>59 || this.value<0){this,value='';}" style=" text-align: right" />
        <select name="ampm">
            <option <? if($ampm == "AM"){ echo " selected ";}?> value="am">AM</option>
            <option <? if($ampm == "PM"){ echo "selected ";}?> value="pm">PM</option>
        </select></td>
        <td align="right"><input type="text" name="DuracMant" value="<? echo $DuracMant?>" size="3" onKeyUp="xNumero(this)" onKeyDown="xNumero(this)"
                                 onBlur="campoNumero(this);if(this.value<0){this,value='';}" style=" text-align: right" />Minutos</td>
    </tr>
    <tr height="100%">
        <td>
            Solicitar baja?<input type="checkbox" name="DdBaja" title="SI / NO"/>
        </td>
        <td valign="bottom" align="right">
    	<input type="submit" name="Guardar" value="Guardar" />
    </td></tr>
    </table>
</td>
</tr>
</table>
</form>
</body>