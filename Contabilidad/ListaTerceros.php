<?
	if($DatNameSID){session_name("$DatNameSID");}	
	session_start();
	include("Funciones.php");	
	include_once("General/Configuracion/Configuracion.php");
	
	if($Eliminar)
	{
		$cons="Select * from Contabilidad.Movimiento where Identificacion='$IdeEliminar' and Movimiento.Compania='$Compania[0]'";
		$res=ExQuery($cons);
		if(ExNumRows($res)==0)
		{
			$cons="Delete from Central.Terceros where Identificacion='$IdeEliminar' and Terceros.Compania='$Compania[0]'";
			$res=ExQuery($cons);
		}
		else
		{
			echo "Tercero tiene movimiento. No es posible eliminar!!!";
		}
	}	
	if(!$Identificacion){$Identificacion="";}
	$cons="Select Identificacion,PrimApe,SegApe,PrimNom,SegNom,Direccion,Regimen from Central.Terceros 
	where Identificacion ilike '$Identificacion%' and PrimApe ilike '%$PrimApe%' and SegApe ilike '%$SegApe%' and PrimNom ilike '%$PrimNom%' and SegNom ilike '%$SegNom%' and Terceros.Compania ilike '$Compania[0]'
	Order By PrimApe,SegApe,PrimNom,SegNom";
	$res=ExQuery($cons);echo ExError($res);
?>
<script language="javascript">
function AbrirCriterios(Tipo,Cedula)
	{
		<? 	$cons00 = "Select AlmacenPpal from Consumo.UsuariosxAlmacenes where Usuario='$usuario[0]' and Compania='$Compania[0]'";
			$res00 = ExQuery($cons00);
			$fila00 = ExFetch($res00)
		?>
		frames.FrameOpener.location.href='/Consumo/EvaluacionCriterios.php?DatNameSID=<? echo $DatNameSID?>&Tipo='+Tipo+'&Cedula='+Cedula+'&AlmacenPpal=<? echo $fila00[0]?>';
		document.getElementById('FrameOpener').style.position='absolute';
		document.getElementById('FrameOpener').style.top='20px';
		document.getElementById('FrameOpener').style.left='8px';
		document.getElementById('FrameOpener').style.display='';
		document.getElementById('FrameOpener').style.width='450';
		document.getElementById('FrameOpener').style.height='470';
	}
function AbrirEstablecimientos(Identificacion)
	{		
		frames.FrameOpener.location.href='/IndustriayComercio/Establecimientos.php?DatNameSID=<? echo $DatNameSID?>&Identificacion='+Identificacion;
		document.getElementById('FrameOpener').style.position='absolute';
		document.getElementById('FrameOpener').style.top='5%';
		document.getElementById('FrameOpener').style.right='3.5%';
		document.getElementById('FrameOpener').style.display='';
		document.getElementById('FrameOpener').style.width='50%';
		document.getElementById('FrameOpener').style.height='90%';
	}	
</script>
	
	<html>
		<head>
			<?php echo $codificacionMentor; ?>
			<?php echo $autorMentor; ?>
			<?php echo $titleMentor; ?>
			<?php echo $iconMentor; ?>
			<?php echo $shortcutIconMentor; ?>
			<link rel="stylesheet" type="text/css" href="../General/Estilos/estilos.css">
			<style>
				a{color:black;text-decoration:none;}
				a:hover{color:blue;text-decoration:underline;}
			</style>
		</head>
			<body <?php echo $backgroundBodyMentor; ?> onLoad="<? if($Mostrar){?>AbrirEstablecimientos('<? echo $Identificacion?>')<? }?>">
				<div <?php echo $alignDiv2Mentor; ?> class="div2">
					
					<table  width="100%" class="tabla2"  <?php echo $borderTabla2Mentor ; echo $bordercolorTabla2Mentor ; echo $cellspacingTabla2Mentor ; echo $cellpaddingTabla2Mentor; ?> style="text-transform:uppercase">
						<tr>
							<td class="encabezado2Horizontal">&nbsp;</td>
							<td class="encabezado2Horizontal"></td>
							<td class="encabezado2Horizontal">IDENTIFICACI&Oacute;N</td>
							<td class="encabezado2Horizontal">NOMBRE</td>
							<td class="encabezado2Horizontal">DIRECCI&Oacute;N</td>
							<td class="encabezado2Horizontal">REGIMEN</td>
						</tr>
					<?
						while($fila=ExFetch($res))
						{
							if($Fondo==1){$BG="#EEF6F6";$Fondo=0;}
							else{$BG="white";$Fondo=1;}
							echo "<tr bgcolor='$BG'>";?>
							<? 
							if($ModOrigen!="Consumo") 
							{ ?>
							<td><center><a href="#" onClick="if(confirm('Desea Eliminar a esta persona de Terceros?')){location.href='ListaTerceros.php?DatNameSID=<? echo $DatNameSID?>&IdeEliminar=<? echo $fila[0]?>&Eliminar=1&TipoTercero=<? echo $TipoTercero?>&PrimApe=<? echo $PrimApe?>&SegApe=<? echo $SegApe?>&PrimNom=<? echo $PrimNom?>&SegNom=<? echo $SegNom?>'}"><img border="0" src="/Imgs/b_drop.png" title="Eliminar"></a></td>
							<? 
							}
							else
							{ ?>
							<td width="20px"><button type="button" name="Seleccion" onClick="AbrirCriterios('Seleccion','<? echo $fila[0]?>')">
									<img src="/Imgs/b_ftext.png" title="Seleccion"></button></td> <? 
							}	
							if($ModOrigen=="Contabilidad")
							{?>
							<td><center> <a href="#" onclick="open('MovimientoxCuenta.php?DatNameSID=<? echo $DatNameSID?>&TerceroSel=<?echo $fila[0]?>','','width=800,height=300,scrollbars=yes')"><img border="0" src="/Imgs/b_tblexport.png"></td>	<? 
							}
							else
							{
								if($ModOrigen=="Presupuesto")
								{?>
									<td> <center> <a href="#" onclick="open('/Presupuesto/MovimientoxCuenta.php?DatNameSID=<? echo $DatNameSID?>&TerceroSel=<?echo $fila[0]?>','','width=800,height=300,scrollbars=yes')"><img border="0" src="/Imgs/b_tblexport.png"></td> <? 
								}
								else 
								{ 
									if($ModOrigen=="ICA")
									{
										?> <td width="20px"><button type="button" name="Establecimientos" onClick="AbrirEstablecimientos('<? echo $fila[0]?>')">
												<img src="/Imgs/b_tblexport.png" title="Establecimientos"></button></td> <?
									}
									else
									{
									?> <td width="20px"><button type="button" name="Desempeno" onClick="AbrirCriterios('Desempeno','<? echo $fila[0]?>')">
												<img src="/Imgs/b_tblexport.png" title="Desempe&ntilde;"></button></td> <?
									}
								}
							}
							
						echo "<td> $fila[0]</td><td><a href='NuevoTercero.php?DatNameSID=$DatNameSID&Identificacion=$fila[0]&Edit=1&ModOrigen=$ModOrigen'>$fila[1] $fila[2] $fila[3] $fila[4]</a></td><td>$fila[5]</td><td>$fila[6]</td>";?>
					<?		echo "</tr>";
						}
					?>
					</table>
					<?
					if($ModOrigen=="ICA")
					{?>
					<center><input type="button" name="Limpiar" value="Limpiar" onClick="location.href='/Contabilidad/nada.html?DatNameSID=<? echo $DatNameSID?>'"/></center>
					<?
					}
					?>
					<iframe id="FrameOpener" name="FrameOpener" style="display:none" frameborder="0" height="1" style="border:#e5e5e5 ridge"></iframe>
				</div>	
			</body>