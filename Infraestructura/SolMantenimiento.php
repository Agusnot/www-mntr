<?
	if($DatNameSID){session_name("$DatNameSID");}
	session_start();
	include("Funciones.php");
	if(!$Evaluar){$AI = " and AutoId=$AutoId";}
	else{$AI="";}
	$cons = "Select Nombre,Marca,Modelo,Caracteristicas,AutoId From Infraestructura.CodElementos Where Compania='$Compania[0]' and Clase='Devolutivos' $AI";
	$res = ExQuery($cons);
	if(!$Evaluar){$fila = ExFetch($res);}
	else
	{
		while($fila = ExFetch($res))
		{
			$Nom[$fila[4]] = "$fila[0] $fila[1] $fila[2] $fila[3]";
		}	
	}
	if($Eliminar)
	{
		$cons = "Delete from Infraestructura.Mantenimiento Where AutoId=$AutoId and UsuarioSolicitud='$usuario[0]' and FechaSolicitud='$FechaSolicitud'";
		$res = ExQuery($cons);		
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
	function AbrirEvaluar(AutoId,e)
	{
		posY = e.clientY;
		sT = document.body.scrollTop;
		frames.FrameOpener.location.href="Evaluar.php?DatNameSID=<? echo $DatNameSID?>&AutoId="+AutoId;
		document.getElementById('FrameOpener').style.position='absolute';
		document.getElementById('FrameOpener').style.right='10px';
		document.getElementById('FrameOpener').style.top=(posY)+sT;
		document.getElementById('FrameOpener').style.display='';
		document.getElementById('FrameOpener').style.width='210';
		document.getElementById('FrameOpener').style.height='240';
	}
</script>
<body background="/Imgs/Fondo.jpg">
<? if(!$Evaluar){?><div align="right">
<button name="Cerrar" title="Cerrar" onClick="CerrarThis()"><img src="/Imgs/b_drop.png" /></button>
</div><? }?>
<table style='font : normal normal small-caps 12px Tahoma;' border="1" bordercolor="#e5e5e5" width="100%">
<? if(!$Evaluar){?><tr bgcolor="#e5e5e5" align="center" style="font-weight:bold"><td colspan="7">Historial de Mantenimiento para <? echo "$fila[0] $fila[1]";?></td></tr><? }?>
<tr bgcolor="#e5e5e5" align="center" style="font-weight:bold">
<? if($Evaluar){?><td>Elemento</td><? }?><td>Fecha</td><td>Clase</td><td>Tipo</td><td>Detalle</td><td>Estado Solicitud</td><td colspan="2">&nbsp;</td></tr>
<?
if($Evaluar)
{
    $ConsEv = " and (EstadoSolicitud = 'Revisado' or EstadoSolicitud = 'Evaluado') ";
    $usu = " and Nombre='$usuario[0]'";
}
$cons = "Select FechaSolicitud,ClaseMantenimiento,TipoMantenimiento,DetalleSolicitud,EstadoSolicitud,AutoId,
Descripcion, Tercero, CC, SubUbicacion, NotaRechazo, Motivo
From InfraEstructura.Mantenimiento, Central.Usuarios
Where Compania='$Compania[0]' and Usuarios.Nombre=UsuarioSolicitud $usu
$AI $ConsEv order by FechaSolicitud desc";
//echo $cons;
$res = ExQuery($cons);
while($fila = ExFetch($res))
{
	if($fila[4]=="Rechazado"){$TitRechazo="Mantenimiento Rechazado: $fila[10]. $fila[11]";}
        else{$TitRechazo="Estado Solicitud: $fila[4]";}
        echo "<tr title='$TitRechazo'>";
        if($Evaluar)
        {
            if(!$fila[6]){echo "<td>".$Nom[$fila[5]]."</td>";}
            else{echo "<td>$fila[6] $fila[7] $fila[8] $fila[9]</td>";}
        }
	echo"<td>".substr($fila[0],0,10)."</td><td>$fila[1]</td><td>$fila[2]</td><td>$fila[3]</td><td>$fila[4]</td>";
	if($fila[4]!="Cerrado")
	{
		$cs++;
                if($fila[4]=="Rechazado"){$cs--;}
                if($fila[4]=="Solicitado")
		{
		?>
		<td><a href="NewSolMantenimiento.php?DatNameSID=<? echo $DatNameSID?>&Editar=1&FechaSolicitud=<? echo $fila[0]?>&AutoId=<? echo $AutoId;?>&CCe=<?echo $CC?>&SubUb=<?echo $SubUb?>">
			<img src="/Imgs/b_edit.png" border="0" title="Editar" />
		</a></td>
		<td><img src="/Imgs/b_drop.png" title="Eliminar" style="cursor:hand" 
		onclick="if(confirm('Desea Eliminar el Registro?')){location.href='SolMantenimiento.php?DatNameSID=<? echo $DatNameSID?>&Eliminar=1&FechaSolicitud=<? echo $fila[0]?>&AutoId=<? echo $AutoId;?>'}" /></td>
		<?	
		}
		if(($fila[4]=="Revisado" || $fila[4]=="Evaluado") && $Evaluar)
		{
		if(!$fila[6]){$Index=$fila[5];}
                else{$Index="$fila[6]|$fila[7]|$fila[8]|$fila[9]|$fila[0]";}
                ?>
		<td><img src="/Imgs/b_tblanalyse.png" title="Evaluar" style="cursor:hand" 
		onclick="AbrirEvaluar('<? echo $Index?>',event)" /></td>
		<?	
		}
        }
	
	echo "</tr>";
		
}
if($cs>=1){$NuevoDis = " disabled title='Solo se puede solicitar nuevamente, si el estado mas reciente es Cerrado o Rechazado' ";}
?>
</table>
<? if(!$Evaluar){?><input type="button" name="Nuevo" value="Nuevo" <? echo $NuevoDis?> onClick="location.href='NewSolMantenimiento.php?DatNameSID=<? echo $DatNameSID?>&AutoId=<? echo $AutoId?>&CCe=<?echo $CC?>&SubUb=<?echo $SubUb?>'" /><? }?>
</table>
<iframe id="FrameOpener" name="FrameOpener" style="display:none" frameborder="0" height="1" style="border:#e5e5e5 ridge" ></iframe>