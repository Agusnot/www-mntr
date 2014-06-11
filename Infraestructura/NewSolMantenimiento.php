<?
	if($DatNameSID){session_name("$DatNameSID");}
	session_start();
	include("Funciones.php");
	$ND = getdate();
        if($Identificacion)
        {
            $cons = "Select primApe,segApe,primNom,SegNom from Central.Terceros Where Identificacion='$Identificacion' and Compania='$Compania[0]'";
            $res = ExQuery($cons);
            $fila = ExFetch($res);
            $Nombre = strtoupper("$fila[0] $fila[1] $fila[2] $fila[3]");
        }
	if($Solicitar)
	{
		if(!$Editar)
		{
			if($AutoId != "NoCodi")
            {
                $cons = "Insert into Infraestructura.Mantenimiento(Compania,AutoId,ClaseMantenimiento,TipoMantenimiento,FechaSolicitud,
                UsuarioSolicitud,DetalleSolicitud,EstadoSolicitud,CC,SubUbicacion)
                values('$Compania[0]',$AutoId,'$ClaseMantenimiento','$TipoMantenimiento','$ND[year]-$ND[mon]-$ND[mday] $ND[hours]:$ND[minutes]:$ND[seconds]',
                '$usuario[0]','$DetalleSolicitud','Solicitado','$CCe','$SubUb')";
            }
            else
            {
                if(!$CCe){$CCe = $CC;}
                if(!$Nombree){$Nombree = $Identificacion;}
                $cons = "Insert into Infraestructura.Mantenimiento(Compania,AutoId,ClaseMantenimiento,TipoMantenimiento,FechaSolicitud,
                UsuarioSolicitud,DetalleSolicitud,EstadoSolicitud,Descripcion,CC,Tercero,SubUbicacion)
                values('$Compania[0]',0,'$ClaseMantenimiento','$TipoMantenimiento','$ND[year]-$ND[mon]-$ND[mday] $ND[hours]:$ND[minutes]:$ND[seconds]',
                '$usuario[0]','$DetalleSolicitud','Solicitado','$NombreNoCodi','$CCe','$Nombree','$SubUb')";
            }
		}
		else
		{
			if($AutoId != "NoCodi")
            {
                $cons = "Update InfraEstructura.Mantenimiento set ClaseMantenimiento='$ClaseMantenimiento', TipoMantenimiento='$TipoMantenimiento',
                DetalleSolicitud='$DetalleSolicitud' Where Compania='$Compania[0]' and UsuarioSolicitud='$usuario[0]' and AutoId=$AutoId and 
                FechaSolicitud='$FechaSolicitud'";    
            }
            else
            {
                $cons = "Update Infraestructura.Mantenimiento set ClaseMantenimiento='$ClaseMantenimiento', TipoMantenimiento='$TipoMantenimiento',
                DetalleSolicitud='$DetalleSolicitud', Descripcion='$NombreNoCodi',CC='$CCe',SubUbicacion='$SubUb' Where Compania='$Compania[0]'
                and Tercero='$Identificacion' and Descripcion='$DesNoCodi' and FechaSolicitud='$FechaSolicitud'";
            }

		}
                //echo $cons;
                $res = ExQuery($cons);
                if($AutoId!="NoCodi"){ $enlace="SolMantenimiento.php?DatNameSID=$DatNameSID&AutoId=$AutoId&CC=$CCe&SubUb=$SubUb";}
                else{ $enlace="MantenimientoNoCodi.php?DatNameSID=$DatNameSID&Tercero=$Identificacion&CC=$CCe&SubUb=$SubUb";}
                
                $cons = "Select usuario from Central.UsuariosxModulos Where Modulo = 'Aprobacion Mantenimiento'
                and Madre = 'Almacen' and COmpania = '$Compania[0]'";
                $res = ExQuery($cons);
                if(ExNumRows($res)>0)
                {
                    if($NombreNoCodi){$Nombre_Elemento = $NombreNoCodi;}
                    $Msj = "Se ha enviado un correo interno al responsable de la aprobacion";
                    $consxx = "Select Id from Central.Correos Where COmpania = '$Compania[0]' order by ID desc LIMIT 1";
                    $resxx = ExQuery($consxx);
                    $filaxx = ExFetch($resxx);
                    $Id = $filaxx[0] + 1;
                    $Mensaje = "<b>Se ha realizado una solicitud de mantenimiento</b><br>
                    <b>Elemento:</b>$Nombre_Elemento<br>
                    <b>Codigo:</b>$Codigo_Elemento<br>
                    <b>Detalle:</b> $DetalleSolicitud<br><br>
                    para aprobar las solicitudes de mantenimiento pendientes presione click 
                    <a href=/Infraestructura/AprobMantenimiento.php?DatNameSID=$DatNameSID>
                    aqui</a>";
                    while($fila = ExFetch($res))
                    {
                        $cons1 = "Insert into Central.Correos
                        (Compania,usucrea,fechacrea,usurecive,mensaje,Id,Asunto,Estado,EstadoEnv)
                         values
                        ('$Compania[0]','$usuario[1]','$ND[year]-$ND[mon]-$ND[mday] $ND[hours]:$ND[minutes]:$ND[seconds]',
                        '$fila[0]','$Mensaje',$Id,'<font color=red><b>MENSAJE DEL SISTEMA!!</b></font>','AC','AC')";
                        $res1 = ExQuery($cons1);
                        $Id++;
                    }
                }
                else
                {
                    $Msj = "Su solicitud se ha guardado, pero no existen usuarios que puedan aprobarla";
                }
                
        ?>
			<script language="javascript">
            	alert("Solicitud de mantenimiento creada satisfactoriamente... <?echo $Msj?>");
				location.href="<? echo $enlace;?>";
            </script>
		<?	
	}
?>
<script language="javascript">
	function CerrarThis()
	{
		parent.document.getElementById('FrameOpener').style.position='absolute';
		parent.document.getElementById('FrameOpener').style.width='1';
		parent.document.getElementById('FrameOpener').style.height='1';
		parent.document.getElementById('FrameOpener').style.display='none';
	}
</script>
<body background="/Imgs/Fondo.jpg">
<form name="FORMA" method="post">
<input type="hidden" name="DatNameSID" value="<? echo $DatNameSID?>" />
<input type="hidden" name="AutoId" value="<? echo $AutoId?>" />
<input type="hidden" name="Editar" value="<? echo $Editar?>" />
<input type="hidden" name="FechaSolicitud" value="<? echo $FechaSolicitud?>" />
<input type="hidden" name="DesNoCodi" value="<? echo $NombreNoCodi?>" />
<div align="right"> 
<button name="Cerrar" title="Cerrar" onClick="CerrarThis()"><img src="/Imgs/b_drop.png" /></button>
</div>
<?
	if($Editar)
	{
            if($AutoId != "NoCodi")
            {
                $cons = "Select ClaseMantenimiento,TipoMantenimiento,DetalleSolicitud From Infraestructura.Mantenimiento
                Where Compania='$Compania[0]' and AutoId=$AutoId and UsuarioSolicitud = '$usuario[0]' and FechaSolicitud='$FechaSolicitud'";
                $res = ExQuery($cons);
                $fila = ExFetch($res);
                $ClaseMantenimiento=$fila[0];$TipoMantenimiento=$fila[1];$DetalleSolicitud=$fila[2];
            }
            else
            {
                $cons = "Select CC,SubUbicacion,DetalleSolicitud,ClaseMantenimiento,TipoMantenimiento
                from Infraestructura.Mantenimiento Where Compania='$Compania[0]' and Tercero='$Identificacion' and Descripcion='$NombreNoCodi'";
                $res = ExQuery($cons);
                $fila = ExFetch($res);
                $CCe = $fila[0]; $SubUb = $fila[1]; $DetalleSolicitud=$fila[2];
                $ClaseMantenimiento = $fila[3]; $TipoMantenimiento = $fila[4];
            }
        }
?>
<table style='font : normal normal small-caps 12px Tahoma;' border="1" bordercolor="#e5e5e5" width="100%">
<tr bgcolor="#e5e5e5" style="font-weight:bold" align="center"><td colspan="3">Solicitud de Mantenimiento para
<?  if($AutoId=="NoCodi")
    {
    ?>
    </td></tr>
    <tr><td align="right" bgcolor="e5e5e5" style="font-weight:bold">Descripcion</td><td colspan="2">
            <input type="text" name="NombreNoCodi" value="<? echo $NombreNoCodi?>" style="width:660"/></td></tr>
    <tr><td align="center" bgcolor="e5e5e5" style="font-weight:bold">Centro de Costo</td>
        <td align="center" bgcolor="e5e5e5" style="font-weight:bold">Nombre</td>
        <td align="center" bgcolor="e5e5e5" style="font-weight:bold">SubUbicacion</td></tr>
    <tr>
        <?
        if(!$CC)
        {
            ?><td><select name="CCe" onChange="FORMA.submit()">
                 <?
                    if($Identificacion)
                    {
                        echo "<option></option>";
                        $cons = "Select CentroCostos,CC from Infraestructura.TercerosxCC, Central.CentrosCosto
                            Where TercerosxCC.Compania='$Compania[0]' and CentrosCosto.Compania='$Compania[0]'
                        and TercerosxCC.Anio=$ND[year] and CentrosCosto.Anio=$ND[year] and CentrosCosto.Codigo=TercerosxCC.CC
                        and Tercero='$Identificacion' order by CentroCostos";
                        $res = ExQuery($cons);
                        echo $cons;
                        while($fila = ExFetch($res))
                        {
                            if($CCe==$fila[1]){echo "<option selected value='$fila[1]'>$fila[0]-$fila[1]</option>";}
                            else{echo "<option value='$fila[1]'>$fila[0]-$fila[1]</option>";}
                        }
                       
                    }
                    else
                    {
                        $cons = "Select CentroCostos,Codigo,Tipo from Central.CentrosCosto,Infraestructura.TercerosxCC
                        Where CentrosCosto.Compania='$Compania[0]' and TercerosxCC.Compania='$Compania[0]'
                        and CentrosCosto.Anio='$ND[year]' and TercerosxCC.Anio='$ND[year]' and Tipo ='Detalle'
                        and CentrosCosto.Codigo = TercerosxCC.CC
                        and Tercero='$usuario[2]' and Administrador=1";
                        //echo $cons;
                        $res = ExQuery($cons);
                        while($fila = ExFetch($res))
                        {
                                if($CCe == $fila[1]){echo "<option selected value='$fila[1]'>$fila[1]-$fila[0]</option>";}
                                else{echo "<option value='$fila[1]'>$fila[1]-$fila[0]</option>";}
                        }
                    }
                 ?>
                </select></td>
                <?
        }
        else
        {
            echo "<td>$CC</td>";
        }
        if(!$Nombre)
        {
        ?>
            <td><select name="Nombree" onChange="FORMA.submit()">
            <?
                if($CC || $CCe)
                {

                    $cons = "Select Identificacion,primape,segape,primnom,segnom from Central.Terceros,Infraestructura.TercerosxCC
                    Where TercerosxCC.COmpania='$Compania[0]' and Terceros.Compania='$Compania[0]' and TercerosxCC.Anio=$ND[year]
                    and Terceros.Identificacion = TercerosxCC.Tercero and (CC='$CC' or CC='$CCe')";
                    echo $cons;
                    $res = ExQuery($cons);
                    while($fila = ExFetch($res))
                    {
                        if($fila[0]==$Nombree){echo "<option selected value='$fila[0]'>".strtoupper("$fila[1] $fila[2] $fila[3] $fila[4]")."</option>";}
                        else{echo "<option value='$fila[0]'>".strtoupper("$fila[1] $fila[2] $fila[3] $fila[4]")."</option>";}
                    }
                }
            ?>
            </select></td>
        <?
        }
        else
        {
            echo "<td>$Nombre</td>";
        }
        ?>
        <td align="center"><select name="SubUb">
            <?
                $cons = "Select SubUbicacion from Infraestructura.SubUbicaciones,Central.CentrosCosto Where
                SubUbicaciones.CC = CentrosCosto.Codigo and CentrosCosto.Anio=$ND[year] and SubUbicaciones.Compania='$Compania[0]'
                and CentrosCosto.Compania='$Compania[0]' and (CC = '$CC' or CC = '$CCe') and SubUbicacion != '-'";
                $res = ExQuery($cons);
                while($fila = ExFetch($res))
                {
                    echo "<option value='$fila[0]'>$fila[0]</option>";
                }
            ?>
        </select></td>
    </tr>
    <?
    }
    else{
    $cons = "Select Nombre,Caracteristicas,Codigo 
    From Infraestructura.CodElementos 
    Where Compania='$Compania[0]' and Clase='Devolutivos' and AutoId=$AutoId";
	$res = ExQuery($cons);
	$fila = ExFetch($res);
	echo "$fila[0] $fila[1]</td></tr>";$Nombre_Elemento="$fila[0] $fila[1]";$Codigo_Elemento=$fila[2];}
?>    
<input type="hidden" name="Nombre_Elemento" value="<?echo $Nombre_Elemento?>" />
<input type="hidden" name="Codigo_Elemento" value="<?echo $Codigo_Elemento?>" />
<tr bgcolor="#e5e5e5" style="font-weight:bold" align="center"><td colspan="3" >Detalle Solicitud</td></tr>
<tr><td colspan="3"><textarea name="DetalleSolicitud" rows="5" style="width:100%"><? echo $DetalleSolicitud?></textarea></td></tr>
</table>
<center>
    <input type="submit" name="Solicitar" value="Solicitar" />
    <input type="button" name="Volver" value="Ver solicitudes"
    <? if($AutoId!="NoCodi"){ ?>onClick="location.href='SolMantenimiento.php?DatNameSID=<? echo $DatNameSID?>&AutoId=<? echo $AutoId?>&CC=<?echo $CCe?>&SubUb=<?echo $SubUb?>';" <?}
     else{ if(!$Identificacion){ $Identificacion=$Nombree;}
     ?> onClick="location.href='MantenimientoNoCodi.php?DatNameSID=<? echo $DatNameSID?>&Tercero=<? echo $Identificacion?>';"  <?}?> />

</center>
</form>
</body>