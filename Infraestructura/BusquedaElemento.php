<?
	if($DatNameSID){session_name("$DatNameSID");}
	session_start();
	include("Funciones.php");
	$ND = getdate();
	if($Origen=="Agenda")
        {
            ///Elementos con agendamiento
            $cons = "Select AutoId from Infraestructura.Mantenimiento Where Compania='$Compania[0]' and Agendado=1 and EstadoSolicitud='Aprobado'";
            $res = ExQUery($cons);
            while($fila=ExFetch($res))
            {
                $Agendado[$fila[0]]=1;
            }
        }
        if($Eliminar)
	{
		$cons = "Update Infraestructura.CodElementos set Eliminado=1,UsuarioElimina='$usuario[0]',
		FechaElimina='$ND[year]-$ND[mon]-$ND[mday] $ND[hours]:$ND[minutes]:$ND[seconds]' Where AutoId=$AutoId and Compania='$Compania[0]'";
		$res = ExQuery($cons);
		$Buscar=1;	
	}
	if($EliminarL)
	{
		while(list($cad,$val) = each($EliminarL))
		{
			//echo "$cad----$val";
			$cons = "Update Infraestructura.CodElementos set Eliminado=1,UsuarioElimina='$usuario[0]',
			FechaElimina='$ND[year]-$ND[mon]-$ND[mday] $ND[hours]:$ND[minutes]:$ND[seconds]' Where AutoId=$cad and Compania='$Compania[0]'";
			$res = ExQuery($cons);
			$Buscar=1;	
		}	
	}
	if($Buscar)
	{
		if($Clase){ $PClase = " and CodElementos.Clase='$Clase' ";$cad="&Clase=$Clase";}
		if($Codigo || $Codigo1)
		{ 
			if($Codigo1){$Codigo=$Codigo1;}
			$PCodigo = " and CodElementos.Codigo ilike '%$Codigo' ";
			$LeerCodigo = 1;
			$cad=$cad."&Codigo=$Codigo";
		}
		if($Nombre){ $PNombre = " and Nombre ilike '%$Nombre%' ";$cad=$cad."&Nombre=$Nombre";}
		if($Caracteristicas){ $PCaracteristicas = " and Caracteristicas ilike '%$Caracteristicas%'";$cad=$cad."&Caracteristicas=$Caracteristicas";}
		if($Modelo){ $PModelo = " and Modelo ilike '%$Modelo%' ";$cad=$cad."&Modelo=$Modelo";}
		if($Serie){ $PSerie = " and Serie ilike '%$Serie%' ";$cad=$cad."&Serie=$Serie";}
		if($Marca){ $PMarca = " and Marca ilike '%$Marca%' ";$cad=$cad."&Marca=$Marca";}
		if($FechaAd){ $PFechaAd = " and FechaAdquisicion = '$FechaAd' ";$cad=$cad."&FechaAd=$FechaAd";}
		if($Estado){ $PEstado = " and Estado = '$Estado' ";$cad=$cad."&Estado=$Estado";}
		if($Impacto){ $PImpacto = " and Impacto = '$Impacto' ";$cad=$cad."&Impacto=$Impacto";}
		if($GrupoX){ $Grupo = $GrupoX;}
		if($Grupo){ $PGrupo = " and Grupo = '$Grupo' ";$cad=$cad."&Grupo=$Grupo";}
		if($Incluir)
		{	if($Incluir=="Solo Activos"){$PTipo = " and CodElementos.Tipo != 'Baja'";}
			if($Incluir=="Solo Bajas"){$PTipo = " and CodElementos.Tipo = 'Baja'";}
			$cad=$cad."&Incluir=$Incluir";
		}
		if($Relacion)
		{
			$cad=$cad."&Relacion=$Relacion";
			if($Relacion=="Encontrados"){$ConsUVE = " and UVE is not NULL ";}
			else{$ConsUVE = " and UVE is NULL ";}
		}
		if($Identificacion || $CC)
		{
                    $CampoSelect=",Responsable,Ubicaciones.CentroCostos,CentrosCosto.CentroCostos,PrimApe,SegApe,PrimNom,SegNom";
                    $TablasFrom=",InfraEstructura.Ubicaciones,Central.Terceros, Central.CentrosCosto";
                    if($Identificacion)
                    {                       
                        $CamposWhereID=" and Responsable='$Identificacion' ";
                        $cad=$cad."&Identificaion=$Identificacion";
                    }
                    else
                    {
                        if($TerceroAdm)
                        {
                            $TerceroAdm="'".str_replace(",","','",$TerceroAdm)."'";
                            $CamposWhereID = " and Responsable in($TerceroAdm)";
                        }
                    }
                    if($CC)
                    {
                        $CamposWhereCC=" and Ubicaciones.CentroCostos = '$CC' ";$cad=$cad."&CC=$CC";
                    }
                    else
                    {
                        if($CCAdm)
                        {
                            $CCAdm="'".str_replace(",","','",$CCAdm)."'";
                            $CamposWhereCC=" and Ubicaciones.CentroCostos in ($CCAdm)";
                        }
                    }
                    $CamposWhere=" and CentrosCosto.Compania='$Compania[0]' and Terceros.Compania='$Compania[0]' and Ubicaciones.Compania='$Compania[0]'
                    and Ubicaciones.AutoId = CodElementos.AutoId and CentrosCosto.Codigo = Ubicaciones.CentroCostos and Ubicaciones.Responsable = Terceros.Identificacion
                    and FechaIni<='$ND[year]-$ND[mon]-$ND[mday]' and FechaFin Is NULL
                    $CamposWhereID $CamposWhereCC";
		}
                else
                {
                    $CampoSelect=",Responsable,Ubicaciones.CentroCostos,CentrosCosto.CentroCostos,PrimApe,SegApe,PrimNom,SegNom";
                    $TablasFrom=",InfraEstructura.Ubicaciones,Central.Terceros, Central.CentrosCosto";
                    if($TerceroAdm)
                    {
                        $TerceroAdm="'".str_replace(",","','",$TerceroAdm)."'";
                        $CamposWhereID = " and Responsable in($TerceroAdm)";
                    }
                    if($CCAdm)
                    {
                        $CCAdm="'".str_replace(",","','",$CCAdm)."'";
                        $CamposWhereCC=" and Ubicaciones.CentroCostos in ($CCAdm)";
                    }
                    $CamposWhere=" and CentrosCosto.Compania='$Compania[0]' and Terceros.Compania='$Compania[0]' and Ubicaciones.Compania='$Compania[0]'
                    and Ubicaciones.AutoId = CodElementos.AutoId and CentrosCosto.Codigo = Ubicaciones.CentroCostos and Ubicaciones.Responsable = Terceros.Identificacion
                    and FechaIni<='$ND[year]-$ND[mon]-$ND[mday]' and FechaFin Is NULL
                    $CamposWhereID $CamposWhereCC";
                }
		$cons = "Select distinct CodElementos.Codigo,Nombre,Modelo,Serie,Marca,Grupo,
		FechaAdquisicion,Estado,Impacto,Caracteristicas,CodElementos.AutoId,CodElementos.Tipo,UVE,UUVE,Consumo$CampoSelect
		From Infraestructura.CodElementos$TablasFrom  
		Where CodElementos.Compania = '$Compania[0]' 
		and (Eliminado!= 1 or Eliminado is NULL) and CodElementos.Tipo != 'Orden Compra' $PTipo and (EstadoCompras != 'ANULADO' or EstadoCompras is NULL) $CamposWhere $PClase 
		$PCodigo$PNombre$PModelo$PSerie$PMarca$PFechaAd$PEstado$PImpacto$PCostoIni$PGrupo$PCaracteristicas$ConsUVE
		Order by Grupo,CodElementos.Codigo";
                
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<script language="javascript">
	function AbrirMantenimientoAgenda(AutoId,e,Grupo)
	{
		posY = e.clientY;
		sT = document.body.scrollTop;
		frames.FrameOpener.location.href="MantenimientoAgenda.php?Grupo="+Grupo+"&DatNameSID=<? echo $DatNameSID?>&H=<? echo $H?>&M=<? echo $M?>&Fecha=<? echo $Fecha?>&Responsable=<? echo $Responsable?>&AutoId="+AutoId;
		document.getElementById('FrameOpener').style.position='absolute';
		document.getElementById('FrameOpener').style.right='40px';
		document.getElementById('FrameOpener').style.top=(posY)+sT;
		document.getElementById('FrameOpener').style.display='';
		document.getElementById('FrameOpener').style.width='350';
		document.getElementById('FrameOpener').style.height='300';
	}
	function AbrirMantenimiento(AutoId,e,Identificacion,CentroCostos,SubUbicacion)
	{
		if(AutoId=="NoCodi"){wdt='900';enlace="NewSolMantenimiento.php?DatNameSID=<? echo $DatNameSID?>&AutoId="+AutoId+"&CC=<? echo $CC?>&Identificacion=<? echo $Identificacion?>"}
        else
		{ 
			<? if($Identificacion)
			{
			?>
			wdt='600';enlace="SolMantenimiento.php?DatNameSID=<? echo $DatNameSID?>&AutoId="+AutoId+"&CC="+CentroCostos+"&Identificacion=<? echo $Identificacion?>&SubUb="+SubUbicacion;
			<?
			}
			else
			{
			?>
			wdt='600';enlace="SolMantenimiento.php?DatNameSID=<? echo $DatNameSID?>&AutoId="+AutoId+"&CC="+CentroCostos+"&Identificacion="+Identificacion+"&SubUb="+SubUbicacion;
			<?
			}?>
			
		
		}
                posY = e.clientY;
		sT = document.body.scrollTop;
		frames.FrameOpener.location.href=enlace;
		document.getElementById('FrameOpener').style.position='absolute';
		document.getElementById('FrameOpener').style.right='40px';
		document.getElementById('FrameOpener').style.top=(posY)+sT;
		document.getElementById('FrameOpener').style.display='';
		document.getElementById('FrameOpener').style.width=wdt;
		document.getElementById('FrameOpener').style.height='350';
	}
	function AbrirNotaBaja(Codigo,AutoId,e)
	{
		posY = e.clientY;
		sT = document.body.scrollTop;
		frames.FrameOpener.location.href="NotaBajaPendiente.php?DatNameSID=<? echo $DatNameSID?>&AutoId="+AutoId+"&Codigo="+Codigo;
		document.getElementById('FrameOpener').style.position='absolute';
		document.getElementById('FrameOpener').style.right='40px';
		document.getElementById('FrameOpener').style.top=(posY)+sT-90;
		document.getElementById('FrameOpener').style.display='';
		document.getElementById('FrameOpener').style.width='600';
		document.getElementById('FrameOpener').style.height='200';
	}
</script>
</head>
<body background="/Imgs/Fondo.jpg">
<form name="FORMA" method="post">
<input type="hidden" name="DatNameSID" value="<? echo $DatNameSID?>" />
<input type="hidden" name="Clase" value="<? echo $Clase?>">
<input type="hidden" name="Codigo" value="<? echo $Codigo?>">
<input type="hidden" name="Nombre" value="<? echo $Nombre?>">
<input type="hidden" name="Caracteristicas" value="<? echo $Caracteristicas?>">
<input type="hidden" name="Modelo" value="<? echo $Modelo?>">
<input type="hidden" name="Serie" value="<? echo $Serie?>">
<input type="hidden" name="Marca" value="<? echo $Marca?>">
<input type="hidden" name="FechaAd" value="<? echo $FechaAd?>">
<input type="hidden" name="Estado" value="<? echo $Estado?>">
<input type="hidden" name="Impacto" value="<? echo $Impacto?>">
<input type="hidden" name="Grupo" value="<? echo $Grupo?>">
<input type="hidden" name="Incluir" value="<? echo $Incluir?>">
<input type="hidden" name="Relacion" value="<? echo $Relacion?>">
<input type="hidden" name="Identificacion" value="<? echo $Identificacion?>">
<input type="hidden" name="CC" value="<? echo $CC?>">
<input type="hidden" name="H" value="<? echo $H?>" />
<input type="hidden" name="M" value="<? echo $M?>" />
<?
if($Origen == "Solicitud")
{
    ?><input type="button" name="NoCodi" value="No Codificados" onClick="AbrirMantenimiento('NoCodi',event)" /><?
}
		$res = ExQuery($cons);
		if(ExNumRows($res)>0)
		{
		?>
		<table style='font : normal normal small-caps 12px Tahoma;' border="1" bordercolor="#e5e5e5" >
        <tr bgcolor="#e5e5e5" style="font-weight:bold;" align="center">
        	<td>&nbsp;</td><td>Codigo</td><td>Nombre</td><td>Marca</td><td>Modelo</td><td>Serie</td><td>Estado</td>
            <td>Fecha Adquisicion</td><td>Impacto</td><td>Responsable</td><td>Centro Costos</td><td colspan="4">&nbsp;</td>
        </tr>
        <?
        while($fila = ExFetch($res))
		{
			$cons1 = "Select distinct Ubicaciones.CentroCostos, PrimNom, SegNom, 
            PrimApe, SegApe, FechaIni, FechaFin, CentrosCosto.CentroCostos,Responsable,SubUbicacion
			From Central.Terceros,Infraestructura.Ubicaciones,Central.CentrosCosto
			Where Ubicaciones.Compania='$Compania[0]' and Terceros.Compania='$Compania[0]' and AutoId=$fila[10] and Terceros.Identificacion = Ubicaciones.Responsable
			and CentrosCosto.Codigo = Ubicaciones.CentroCostos and CentrosCosto.Compania = '$Compania[0]' and CentrosCosto.Anio = $ND[year] order by
			FechaFin desc";
			$res1 = ExQuery($cons1);
			$fila1 = ExFetch($res1);
			if($fila[11]=="Baja"){$Baja="title='Dado de Baja' style='text-decoration:underline; color:#F00' ";}
			else{$Baja="";}
			if($fila[5] != $GrupAnt)
			{
				echo "<tr bgcolor='$Estilo[1]'  style='color:white;font-weight:bold;'><td colspan='15' align='center'>$fila[5]</td></tr>";	
			}
			?>
				<tr onMouseOver="this.bgColor='#AAD4FF'" onMouseOut="this.bgColor='#FFFFFF'" <? echo $Baja?> title="<? echo $fila[11];?>" >
			<?
			if($fila[12]){?><td><img src="/Imgs/b_check.png" title="<? echo $fila[13]?>"></td><? }
			else
			{
				?><td><img src="/Imgs/b_alert.png" title="Aun no se ha leido" />
                <?
                if($Relacion=="No Encontrados")
				{?><input type="checkbox" name="EliminarL[<? echo $fila[10]?>]" checked /><? }
				?>
                </td><? 
			}
			echo "<td>$fila[0]</td><td>$fila[1] $fila[9]</td><td>$fila[4]</td><td>$fila[2]</td><td>$fila[3]</td>
			<td>$fila[7]</td><td align='right'>$fila[6]</td><td>$fila[8]</td>
			<td>$fila1[3] $fila1[4] $fila1[1] $fila1[2]</td>
			<td>$fila1[7]($fila1[0]) - $fila1[9]</td>";
			if(!$Origen)
			{
			?>
            <td><a href="NuevoLevInicial.php?DatNameSID=<? echo $DatNameSID?>&Origen=Busqueda&Codigo=<? echo $fila[0]?>&Consumo=<? echo $fila[13]?>&Editar=1&AutoId=<? echo $fila[10];?>&Clase=<? echo $Clase;?>&Tipo=<? echo $fila[11]?>">
            	<img src="/Imgs/b_edit.png" border="0" title="Editar" />
            </a></td>
            <td>
            	<img src="/Imgs/b_print.png" style="cursor:hand;" title="Imprimir la Ficha de este Elemento" 
                     onClick="open('/Informes/Infraestructura/Reportes/FichaElemento.php?DatNameSID=<? echo $DatNameSID?>&AutoId=<? echo $fila[10]?>','','width=800,height=600,scrollbars=yes');"/>
            </td>
            <td align="right">
            	<img src="/Imgs/b_sbrowse.png" style="cursor:hand;" title="Historial de Mantenimiento" />
            </td>
            <!-- <td><img src="/Imgs/down.gif" style="cursor:hand" title="Nota de Baja Pendiente" onClick="AbrirNotaBaja('<? echo $fila[0]?>','<? echo $fila[10]?>',event)"></td> -->
            <td><img src="/Imgs/b_drop.png" style="cursor:hand" title="Eliminar" 
            onClick="if(confirm('Desea eliminar este elemento?(Los resultados seran irreversibles mediante el sistema)'))
            {location.href='BusquedaElemento.php?DatNameSID=<? echo $DatNameSID?>&Eliminar=1&AutoId=<? echo $fila[10].$cad?>'}"></td>
            <?
			}
			if($Origen=="Solicitud")
			{
			?>
			<td><img 
            src="/Imgs/b_tblops.png" style="cursor:hand;" title="Solicitar Mantenimiento a este Elemento" 
            onClick="AbrirMantenimiento('<? echo $fila[10]?>',event,'<? echo $fila1[8]?>','<? echo $fila1[0]?>','<? echo $fila1[9]?>')" />
            </td>
			<?	
			}
			if($Origen=="Agenda" && !$Agendado[$fila[10]])
			{
			?>
			<td><img src="/Imgs/b_tblops.png" style="cursor:hand;" title="Agendar este Elemento" onClick="AbrirMantenimientoAgenda('<? echo $fila[10]?>',event,'<? echo $fila[5]?>')" />
            </td>
			<?	
			}
			echo "</tr>";
			$GrupAnt = $fila[5];
		}
		?>
        </table>
		<?		
		}
		else
		{
			echo "<em>Su busqueda no Arrojo resultados</em>";
		}
	}
?>
<table style='font : normal normal small-caps 12px Tahoma;' border="1" bordercolor="#e5e5e5">
</table>
<iframe id="FrameOpener" name="FrameOpener" style="display:none" frameborder="0" height="1" style="border:#e5e5e5 ridge" ></iframe>
<?
	if($LeerCodigo)
	{
		$cons = "Update Infraestructura.CodElementos set UVE='$ND[year]-$ND[mon]-$ND[mday] $ND[hours]:$ND[minutes]:$ND[seconds]',UUVE='$usuario[0]'
		Where Compania='$Compania[0]' and Codigo='$Codigo'";
		$res = ExQuery($cons);	
	}
?>
</form>
</body>
</html>