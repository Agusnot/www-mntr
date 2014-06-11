<?
	if($DatNameSID){session_name("$DatNameSID");}
	session_start();
	include("Funciones.php");
	$ND = getdate();
    if(!$Desde){$Desde = "$ND[year]-$ND[mon]-01";}
    if(!$Hasta){$Hasta = "$ND[year]-$ND[mon]-$ND[mday]";}
    if(!$VerSolo){$VerSolo = "Solicitado";}
	if($Reversar)
	{
            if($AutoId)
            {
                $nocodi = explode("|",$AutoId);
                if(count($nocodi)==1)
                {
                    $cons ="Update Infraestructura.Mantenimiento set EstadoSolicitud='Solicitado', Encargado=NULL, NotaRechazo=NULL,
                    FechaAR = NULL, UsuarioAR = NULL Where Compania='$Compania[0]' and AutoId=$AutoId and (EstadoSolicitud='Aprobado' or EstadoSolicitud='Rechazado')";
                    $res = ExQuery($cons);	
                }
                else
                {
                    $cons ="Update Infraestructura.Mantenimiento set EstadoSolicitud='Solicitado', Encargado=NULL, NotaRechazo=NULL,
                    FechaAR = NULL, UsuarioAR = NULL Where Compania='$Compania[0]' and AutoId=0 and (EstadoSolicitud='Aprobado' or EstadoSolicitud='Rechazado')
                    and Tercero='$nocodi[0]' and Descripcion='$nocodi[1]' and CC='$nocodi[2]' and SubUbicacion='$nocodi[3]' and FechaSolicitud ='$nocodi[4]'";
                }
                $res = ExQuery($cons);    
            }


	}
	if($Guardar)
	{
		if($Aprobar)
		{
			$consxx = "Select Id from Central.Correos Where COmpania = '$Compania[0]' order by ID desc LIMIT 1";
            $resxx = ExQuery($consxx);
            $filaxx = ExFetch($resxx);
            $Id = $filaxx[0] + 1;
            while(list($cad,$val) = each($Aprobar))
			{
                if($val)
                {
                    //USUARIO AL QUE SE DEBE ENVIAR CORREO: echo $usuario_sol[$cad];exit;
                    if($val=="Aprobar")
                    { 
                        $Estado = "Aprobado";
                        $Mensaje = "la solicitud de Mantenimiento del elemento <b>".$nombreE_sol[$cad]."</b> ha sido Aprobada
                        el dia: $ND[year]-$ND[mon]-$ND[mday]";
                    }
                    else
                    {
                        $Estado = "Rechazado";
                        $Mensaje = "la solicitud de Mantenimiento del elemento <b>".$nombreE_sol[$cad]."</b> ha sido Rechazada";
                    }
                    $cade = explode("|",$cad);
                    if(count($cade)==1)
                    {
                        $cons = "Update Infraestructura.Mantenimiento set EstadoSolicitud='$Estado',
                        FechaAR = '$ND[year]-$ND[mon]-$ND[mday] $ND[hours]:$ND[minutes]:$ND[seconds]', UsuarioAR = '$usuario[0]'
                        Where Compania='$Compania[0]' and EstadoSolicitud='Solicitado' and AutoId=$cad";
                    }
                    else
                    {
                        $cons = "Update Infraestructura.Mantenimiento set EstadoSolicitud='$Estado',
                        FechaAR = '$ND[year]-$ND[mon]-$ND[mday] $ND[hours]:$ND[minutes]:$ND[seconds]', UsuarioAR = '$usuario[0]'
                        Where Compania='$Compania[0]' and EstadoSolicitud='Solicitado' and AutoId=0 and Tercero='$cade[0]'
                        and Descripcion='$cade[1]' and CC='$cade[2]' and SubUbicacion='$cade[3]' and FechaSolicitud ='$cade[4]'";
                    }
                    $res = ExQUery($cons);
                    $cons1 = "Insert into Central.Correos
                    (Compania,usucrea,fechacrea,usurecive,mensaje,Id,Asunto,Estado,EstadoEnv)
                     values
                    ('$Compania[0]','$usuario[1]','$ND[year]-$ND[mon]-$ND[mday] $ND[hours]:$ND[minutes]:$ND[seconds]',
                    '".$usuario_sol[$cad]."','$Mensaje',$Id,'<font color=red><b>MENSAJE DEL SISTEMA!!</b></font>','AC','AC')";
                    $res1 = ExQuery($cons1);
                    $Id++;
                }
            }
		}	
	}
	if($VerSolo != "-"){$AdCons_EstadoSolicitud = " and EstadoSolicitud = '$VerSolo'";}
    if($Desde){$AdCons_Desde = " and date(fechasolicitud) >= '$Desde'";}
    if($Hasta){$AdCons_Hasta = " and date(fechasolicitud) <= '$Hasta'";}
    $cons = "Select CodElementos.Codigo,CodElementos.Nombre,Caracteristicas,Grupo,FechaSolicitud,
    UsuarioSolicitud,DetalleSolicitud,EstadoSolicitud,Mantenimiento.AutoId,
    Tercero,Descripcion,CC,SubUbicacion,CentroCostos,usuario,Encargado From
	Infraestructura.CodElementos,Infraestructura.Mantenimiento,Central.CentrosCosto,Central.Usuarios
    Where CodElementos.Compania='$Compania[0]' and Agendado != 1 
	and Mantenimiento.Compania='$Compania[0]' and CodElementos.AutoId = Mantenimiento.AutoId
    and CentrosCosto.Compania = '$Compania[0]' and CentrosCosto.Codigo = CC and CentrosCosto.Anio=$ND[year]
    and Usuarios.Nombre = UsuarioSolicitud
    $AdCons_EstadoSolicitud $AdCons_Desde $AdCons_Hasta
    order by Nombre,Descripcion";
	$res = ExQuery($cons);
?>
<script language='javascript' src="/calendario/popcalendar.js"></script>
<script language="javascript">
	function AbrirResponsables(AutoId,Grupo,e,NombreElemento)
	{
		posY = e.clientY;
		sT = document.body.scrollTop;
		frames.FrameOpener.location.href="AsignarResponsable.php?DatNameSID=<? echo $DatNameSID?>&AutoId="+AutoId+"&Grupo="+Grupo+"&NombreElemento="+NombreElemento;
		document.getElementById('FrameOpener').style.position='absolute';
		document.getElementById('FrameOpener').style.right='10px';
		document.getElementById('FrameOpener').style.top=(posY)+sT;
		document.getElementById('FrameOpener').style.display='';
		document.getElementById('FrameOpener').style.width='400';
		document.getElementById('FrameOpener').style.height='240';
	}
        function AbrirNotaRechazo(AutoId,FechaSolicitud,e)
	{
                posY = e.clientY;
		sT = document.body.scrollTop;
		frames.FrameOpener.location.href="NotaRechazo.php?DatNameSID=<? echo $DatNameSID?>&AutoId="+AutoId+"&FechaSolicitud="+FechaSolicitud;
		document.getElementById('FrameOpener').style.position='absolute';
		document.getElementById('FrameOpener').style.right='10px';
		document.getElementById('FrameOpener').style.top=(posY)+sT;
		document.getElementById('FrameOpener').style.display='';
		document.getElementById('FrameOpener').style.width='500';
		document.getElementById('FrameOpener').style.height='255';
	}
</script>
<body background="/Imgs/Fondo.jpg">
<form name="FORMA" method="post">
<input type="hidden" name="DatNameSID" value="<? echo $DatNameSID?>" />
<table style='font : normal normal small-caps 12px Tahoma;' border="1" bordercolor="#e5e5e5" width="100%">
<tr bgcolor="#e5e5e5" style="font-weight:bold" align="center"><td colspan="9">Solicitudes de Mantenimiento</td></tr>
<tr>
    <td colspan="9" align="center" bgcolor="#e5e5e5">
        Desde: <input type="text" name="Desde" size="8" 
        onclick="popUpCalendar(this, FORMA.Desde, 'yyyy-mm-dd')"  value="<? echo $Desde; ?>" readonly="yes" />
        Hasta: <input type="text" name="Hasta" size="8" 
        onclick="popUpCalendar(this, FORMA.Hasta, 'yyyy-mm-dd')"  value="<? echo $Hasta; ?>" readonly="yes" />
        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
        <select name="VerSolo">
            <option <?if($VerSolo=="-")echo " selected ";?> value="-">Ver Todas las solicitudes</option>
            <option <?if($VerSolo=="Solicitado")echo " selected ";?> value="Solicitado">Ver Pendientes de Aprobacion</option>
            <option <?if($VerSolo=="Aprobado")echo " selected ";?> value="Aprobado">Ver Aprobadas sin Revision</option>
            <option <?if($VerSolo=="Rechazado")echo " selected ";?> value="Rechazado">Ver Rechazadas</option>
            <option <?if($VerSolo=="Cerrado")echo " selected ";?> value="Cerrado">Ver Casos Cerrados</option>
            <option <?if($VerSolo=="Evaluado")echo " selected ";?> value="Evaluado">Ver Evaluados</option>
            <option <?if($VerSolo=="Revisado")echo " selected ";?> value="Revisado">Ver Revisados sin Evaluacion</option>
        </select>
        <button type="submit" name="Buscar" title="Buscar de acuerdo a filtro">
            <img src="/Imgs/b_search.png" />
        </button>
    </td>
</tr>
<tr bgcolor="#e5e5e5" style="font-weight:bold" align="center"><td>Codigo</td><td colspan="2">Elemento</td>
    <td>Fecha Solicitud</td><td>Usuario</td><td>Detalle</td><td>Ubicacion</td>
<td colspan="2">Accion</td></tr>
<?
while($fila = ExFetch($res))
{
	if(!$fila[9]){$Index = $fila[8];}
        else{$Index="$fila[9]|$fila[10]|$fila[11]|$fila[12]|$fila[4]";}
        if($fila[8]!=0)
        {
            echo "<tr onMouseOver=\"this.bgColor='#AAD4FF'\" onMouseOut=\"this.bgColor=''\"><td>$fila[0]</td><td>$fila[1] $fila[2]</td>";
        }
        else
        {
            echo "<tr onMouseOver=\"this.bgColor='#AAD4FF'\" onMouseOut=\"this.bgColor=''\"><td colspan='2'>$fila[10]</td>";
        }
        ?><td style=" cursor: hand"
            onClick="open('/Informes/Infraestructura/Reportes/HistorialMantenimiento.php?DatNameSID=<? echo $DatNameSID?>&AutoId=<? echo $Index?>','','width=800,height=600,scrollbars=yes');">
            <img src="/Imgs/s_process.png" title="Ver Historial de Mantenimiento Para este elemento" /></td><?
            echo "<td>".substr($fila[4],0,10)."</td><td>$fila[5]";?>
            <input type="hidden" name="usuario_sol[<? echo $Index?>]" value="<?echo $fila[14]?>" />
            <input type="hidden" name="nombreE_sol[<? echo $Index?>]" value="<?echo trim("$fila[1] $fila[2] $fila[10]")?>" />
            <?
	echo "</td><td>$fila[6]</td>";
        ?><td><? echo "$fila[13] - $fila[12]";?></td><?
        if($fila[7]=="Solicitado")
        {
            ?>
        <td colspan="2"><select name="Aprobar[<? echo $Index?>]">
    	<option></option>
        <option value="Aprobar">Aprobar</option>
        <option value="Rechazar">Rechazar</option>
    </select>
        </td>
	<? }
	else
	{
		if($fila[7]=="Aprobado" || $fila[7]=="Rechazado")
		{ ?>
			<td><button name="Reversar" title="Reversar <? echo $fila[7]?>" 
			onclick="if(confirm('Desea Reversar la Decision?')){location.href='AprobMantenimiento.php?DatNameSID=<? echo $DatNameSID?>&Reversar=1&AutoId=<? echo $Index;?>'}">
			<img src="/Imgs/b_drop.png" /></button></td>
			<?	if($fila[7]=="Aprobado")
				{	
                    if($fila[15]){$usr_bgcolor="#009933";} else{$usr_bgcolor="";}
                    ?>
					<td bgcolor="<?echo $usr_bgcolor?>"><button name="Asignar" title="Asignar responsable"
					onclick="AbrirResponsables('<? echo $Index;?>','<? echo $fila[3];?>',event,'<?echo trim("$fila[1] $fila[2] $fila[10]")?>');"><img src="/Imgs/b_usradd.png" /></button></td>
					<?	
				}
                                if($fila[7]=="Rechazado")
                                {
                                        ?>
					<td><button name="NotaRechazo" title="Adjuntar nota de rechazo"
					onclick="AbrirNotaRechazo('<? echo $Index;?>','<? echo $fila[4];?>',event);"><img src="/Imgs/b_edit.png" /></button></td>
					<?
                                }
		}
		
	}
	echo "</tr>";
}
?>
</table>
<input type="submit" name="Guardar" value="Guardar" />
</form>
<iframe id="FrameOpener" name="FrameOpener" style="display:none" frameborder="0" height="1" style="border:#e5e5e5 ridge" ></iframe>
</body>