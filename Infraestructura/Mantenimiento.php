<?
	if($DatNameSID){session_name("$DatNameSID");}
	session_start();
	include("Funciones.php");
	$ND = getdate();
    if(!$Desde){$Desde = "$ND[year]-$ND[mon]-01";}
    if(!$Hasta){$Hasta = "$ND[year]-$ND[mon]-$ND[mday]";}
    $cons = "Select CC from Infraestructura.TercerosxCC Where Compania='$Compania[0]' and Administrador=1 And Anio=$ND[year] and tercero='$usuario[2]'";
	$res = ExQuery($cons);
	if(ExNumRows($res)>0)
	{
		while($fila=ExFetch($res))
		{
			if(!$CCAdm){$CCAdm="'$fila[0]'";}
			else{$CCAdm=$CCAdm.",'$fila[0]'";}
		}
		$cons1 = "Select distinct(tercero) from Infraestructura.TercerosxCC Where Compania='$Compania[0]' and Anio=$ND[year] and cc in($CCAdm)";
		$res1 = ExQuery($cons1);
		while($fila1=ExFetch($res1))
		{
			if(!$TerceroAdm){$TerceroAdm = "'$fila1[0]'";}
			else{$TerceroAdm=$TerceroAdm.",'$fila1[0]'";}
		}
		//$CCAdm = str_replace("'","",$CCAdm);
		//$TerceroAdm = str_replace("'","",$TerceroAdm);
		//echo $CCAdm."-----".$TerceroAdm;
	}
	if($Desde){$AdCons_Desde = " and date(fechasolicitud) >= '$Desde'";}
    if($Hasta){$AdCons_Hasta = " and date(fechasolicitud) <= '$Hasta'";}
    if(!$TerceroAdm){$Encargado = " = '$usuario[2]'";}
    else{$Encargado = " in($TerceroAdm)";}
	$cons = "Select Codigo,CodElementos.Nombre,Caracteristicas,ClaseMantenimiento,
    TipoMantenimiento,DetalleSolicitud,UsuarioSolicitud, FechaSolicitud, 
    EstadoSolicitud, Mantenimiento.AutoId, Agendado, Descripcion, 
    Tercero, CC, SubUbicacion, FechAgenda,Usuario,FechUltRev
	From Infraestructura.CodElementos,Infraestructura.Mantenimiento,Central.Usuarios 
    Where CodElementos.Compania='$Compania[0]' and Mantenimiento.Compania='$Compania[0]' 
    and CodElementos.AutoId = Mantenimiento.AutoId and Encargado $Encargado
    and EstadoSolicitud !='Cerrado' and UsuarioSolicitud = Usuarios.Nombre
    $AdCons_Desde $AdCons_Hasta
    order by FechaSolicitud Desc";
	//echo $cons;
	$res = ExQuery($cons);
?>
	<script language='javascript' src="/calendario/popcalendar.js"></script>
    <script language="javascript">
    function AbrirRespuesta(AutoId,Estado,e,usuario_sol,nombre_elemento,codigo_elemento)
	{
		<? if($TerceroAdm){$Adm=1;$H = 450;}else{$H=400;}?>
		posY = e.clientY;
		sT = document.body.scrollTop;
        frames.FrameOpener.location.href="RespuestaMantenimiento.php?Adm=<? echo $Adm?>&DatNameSID=<? echo $DatNameSID?>&AutoId="+AutoId+"&Estado="+Estado+"&usuario_sol="+usuario_sol+"&nombre_elemento="+nombre_elemento+"&codigo_elemento="+codigo_elemento;
		document.getElementById('FrameOpener').style.position='absolute';
		document.getElementById('FrameOpener').style.right='10px';
		document.getElementById('FrameOpener').style.top=(posY)+sT;
		document.getElementById('FrameOpener').style.display='';
		document.getElementById('FrameOpener').style.width='700';
		document.getElementById('FrameOpener').style.height='<? echo $H?>';
	}
	function CerrarCaso(AutoId,Estado,e)
	{
		posY = e.clientY;
		sT = document.body.scrollTop;
		frames.FrameOpener.location.href="CerrarCaso.php?DatNameSID=<? echo $DatNameSID?>&AutoId="+AutoId+"&Estado="+Estado;
		document.getElementById('FrameOpener').style.position='absolute';
		document.getElementById('FrameOpener').style.right='10px';
		document.getElementById('FrameOpener').style.top=(posY)+sT;
		document.getElementById('FrameOpener').style.display='';
		document.getElementById('FrameOpener').style.width='300';
		document.getElementById('FrameOpener').style.height='250';
	}
    </script>
<body background="/Imgs/Fondo.jpg">
    <form name="FORMA" method="post">
    <table style='font : normal normal small-caps 12px Tahoma;' border="1" bordercolor="#e5e5e5" width="100%">
    <tr>
        <td colspan="9" align="center" bgcolor="#e5e5e5">
        Desde: <input type="text" name="Desde" size="8" 
        onclick="popUpCalendar(this, FORMA.Desde, 'yyyy-mm-dd')"  value="<? echo $Desde; ?>" readonly="yes" />
        Hasta: <input type="text" name="Hasta" size="8" 
        onclick="popUpCalendar(this, FORMA.Hasta, 'yyyy-mm-dd')"  value="<? echo $Hasta; ?>" readonly="yes" />
        
        <button type="submit" name="Buscar" title="Buscar de acuerdo a filtro">
            <img src="/Imgs/b_search.png" />
        </button>
    </td>
    </tr>
    <tr bgcolor="#e5e5e5" align="center" style="font-weight:bold">
    <td>Codigo</td><td>Elemento</td><td>Clase Mantenimeinto</td><td>Tipo Mantenimiento</td><td>Detalle</td><td>Usuario</td><td>Fecha Solicitud</td><td>&nbsp;</td></tr>
<?	
	while($fila = ExFetch($res))
	{
		if($fila[10]){$Ag="<img src='/Imgs/b_tblexport.png' title='Agendado' />";}
		else{$Ag="";}
                if(!$fila[11]){$Elemento="<td>$Ag $fila[0]</td><td>$fila[1] $fila[2]</td>";}
                else{$Elemento="<td colspan='2'>$Ag $fila[11] $fila[12] $fila[13] $fila[14]</td>";}
		if(!$fila[11]){ $Index = "$fila[9]";}
        else{ $Index = "$fila[11]|$fila[13]|$fila[12]|$fila[14]|$fila[7]";}
		$Nombrexx = explode("|",$fila[11]);
        $Nombre = trim("$fila[1] $fila[2] $Nombrexx[0]");
        if(!$fila[10] || $fila[15]=="$ND[year]-$ND[mon]-$ND[mday]")
                {
                echo "<tr title=\"Doble Clic para ver el informe tecnico\" 
            onMouseOver=\"this.bgColor='#AAD4FF'\" onMouseOut=\"this.bgColor=''\" style=\" cursor: hand\"
		onDblClick=\"open('/Informes/Infraestructura/Formatos/SolicitudMantenimiento.php?DatNameSID=$DatNameSID&AutoId=$Index&FechaSolicitud=$fila[7]','','width=800,height=600,scrollbars=yes');\">
		$Elemento<td>$fila[3]</td><td>$fila[4]</td><td>$fila[5]</td><td>$fila[6]</td><td>".substr($fila[7],0,10)."</td>";
        if($fila[8]=="Aprobado" || $fila[8]=="Revisado")
		{
            if($fila[17]){$Rev_bgcolor="#009933";} else{$Rev_bgcolor="";}
            ?>
			<td bgcolor="<? echo $Rev_bgcolor?>"><button name="Respuesta" 
              onClick="AbrirRespuesta('<? echo $Index?>','<? echo $fila[8]?>',event,'<?echo $fila[16]?>','<?echo $Nombre?>','<?echo $fila[0]?>')" title="Respuesta">
            	<img src="/Imgs/s_process.png" />
            </button>
            </td>
		<?
		}
		if($fila[8]=="Evaluado" || $fila[8]=="Cerrado")
		{
		?>
			<td><button name="Cerrar_Caso" onClick="CerrarCaso('<? echo $Index?>','<? echo $fila[8]?>',event)" title="Cerrar Caso">
            	<img src="/Imgs/b_deltbl.png" />
            </button></td>
		<?
		}
		echo "</tr>";
                }
                
	}
?>
	</table>
    </form>
</body>
<iframe id="FrameOpener" name="FrameOpener" style="display:none" frameborder="0" height="1" style="border:#e5e5e5 ridge" ></iframe>