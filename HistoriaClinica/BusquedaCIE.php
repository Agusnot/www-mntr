		<?php
			if($DatNameSID){session_name("$DatNameSID");}
			session_start();
			include("Funciones.php");
			include_once("General/Configuracion/Configuracion.php");
			if($Favorito)
			{
				$cons="Update salud.cie set favorito=$Val where Codigo='$DF'";
				$res=ExQuery($cons);
				
			}
			$Nombre = str_replace(" ", "%", $Nombre);
			if($Codigo){$PartCons="and codigo ilike '%$Codigo%'";}
			if($Nombre){$PartCons=$PartCons." and diagnostico ilike '$Nombre%'";}
			if($Clasificacion=="Favoritos"){$PartCons=$PartCons." and favorito = '1'";}
			elseif($Clasificacion=="No Favoritos"){$PartCons=$PartCons." and favorito is null";}
			$cons="SELECT  codigo,diagnostico, favorito FROM salud.cie where 1=1 $PartCons order by codigo,diagnostico";
			//echo $cons.'<br>';
			$res=ExQuery($cons);
			$NumTotReg=ExNumRows($res);
			$cons="SELECT  codigo,diagnostico, favorito FROM salud.cie where 1=1 $PartCons order by codigo,diagnostico limit $NxP offset $Offset";
			$res=ExQuery($cons);
			$NumReg=ExNumRows($res);	
			//echo $NumReg."<br>";
			//echo $cons;
			while($fila=ExFetch($res))
			{
				$MatDiagnosticos[$fila[0]]=array($fila[0],$fila[1],$fila[2]);
			}		
		?>	
		
	<html>	
			<head>
				<?php echo $codificacionMentor; ?>
				<?php echo $autorMentor; ?>
				<?php echo $titleMentor; ?>
				<?php echo $iconMentor; ?>
				<?php echo $shortcutIconMentor; ?>
				<link rel="stylesheet" type="text/css" href="../General/Estilos/estilos.css">
				<script language="javascript" src="/Funciones.js"></script>
				<script language="javascript">	
				</script>
			</head>
			<body <?php echo $backgroundBodyMentor; ?>>
				<div <?php echo $alignDiv3Mentor; ?> class="div3">
					<form name="FORMA" method="post">
							<input type="hidden" name="DatNameSID" value="<? echo $DatNameSID?>">
							<input type="hidden" name="Offset" value="<? echo $Offset?>" />
							<input type="hidden" name="NxP" value="<? echo $NxP?>" />
							<input type="hidden" name="Codigo" value="<? echo $Codigo?>" />
							<input type="hidden" name="Nombre" value="<? echo $Nombre?>" />
							<input type="hidden" name="Clasificacion" value="<? echo $Clasificacion?>" />
							
							<table width="100%" class="tabla3" border="0"  <?php echo $cellspacingTabla3Mentor; echo $cellpaddingTabla3Mentor; ?>>
								<tr>
									<td style="text-align:left;">
										<a href="#" style="cursor:hand; color:black;font-size:12px; font-weight:bold;" onclick="<? if($Offset>0){?>document.FORMA.Offset.value=parseInt(document.FORMA.Offset.value)-parseInt(document.FORMA.NxP.value);document.location.href='BusquedaCIE.php?DatNameSI=<? echo $DatNameSID?>&Codigo=<? echo $Codigo?>&Nombre=<? echo $Nombre?>&Clasificacion=<? echo $Clasificacion?>&NxP=<? echo $NxP?>&Offset='+document.FORMA.Offset.value<? }?>"><img name="Anterior" src="/Imgs/left.gif" border="0" /> Pag. Anterior</a>
									</td>

									<td  style="text-align:center;font-size:12px;color:#002147;font-weight:bold;">Viendo Registros de <? echo $Offset+1?> hasta <? if($NumReg==$NxP){echo $Offset+$NxP;$Ult=$Offset+$NxP;}else{echo $Offset+$NumReg;$Ult=$Offset+$NumReg;}?> de <? echo $NumTotReg?> Diagnosticos</td>

									<td style="text-align:right;">
										<a href="#" style="cursor:hand; color:black; ;font-size:12px;font-weight:bold;" onclick="<? if($Ult<$NumTotReg){?>document.FORMA.Offset.value=parseInt(document.FORMA.Offset.value)+parseInt(document.FORMA.NxP.value);document.location.href='BusquedaCIE.php?DatNameSI=<? echo $DatNameSID?>&Codigo=<? echo $Codigo?>&Nombre=<? echo $Nombre?>&Clasificacion=<? echo $Clasificacion?>&NxP=<? echo $NxP?>&Offset='+document.FORMA.Offset.value<? }?>" >Pag. Siguiente <img name="Siguiente" src="/Imgs/right.gif" border="0" /></a>
									</td>
								</tr>
							</table>
							<!-----   -->
							<table width="100%" class="tabla3"  <?php echo $borderTabla3Mentor; echo $bordercolorTabla3Mentor; echo $cellspacingTabla3Mentor; echo $cellpaddingTabla3Mentor; ?>>
								<tr>
								<td class="encabezado2Horizontal" style="width:30px">C&Oacute;DIGO</td>
								<td class="encabezado2Horizontal">NOMBRE</td>
								<td class="encabezado2Horizontal" style="width:80px">CLASIFICACI&Oacute;N</td>
								<td class="encabezado2Horizontal">&nbsp;</td>
								</tr>
							<? 
								if($MatDiagnosticos)	
								{
									foreach($MatDiagnosticos as $Cod)
									{		
										?>
										<tr onMouseOver="this.bgColor='#AAD4FF'" onMouseOut="this.bgColor=''">
											<a name="<? echo $Cod[0]?>">
											<td align="right"><? echo $Cod[0]?></td>
											<td><? echo $Cod[1]?></td>
											<td><? if($Cod[2]==1){echo "<font color='#009933'><b>Favorito</b></font>";$Bot="No Favorito";$Im="/Imgs/nofavoritos.png";$Val="NULL";}else{echo "No Favorito"; $Bot="Favorito"; $Im="/Imgs/favoritos.png";$Val="1";}?></td>
											<td align="center" style="width:25px"><button type="butto" name="Favorito" value="<? echo $Bot?>" title="<? echo $Bot?>" style="cursor:hand;" onclick="document.location.href='BusquedaCIE.php?DatNameSI=<? echo $DatNameSID?>&Codigo=<? echo $Codigo?>&Nombre=<? echo $Nombre?>&Clasificacion=<? echo $Clasificacion?>&NxP=<? echo $NxP?>&Favorito=1&DF=<? echo $Cod[0]?>&Val=<? echo $Val?>&Offset='+document.FORMA.Offset.value;"><img src="<? echo $Im?>" style="width:20; height:20;" /></button></td>
											</a>
										</tr>
										<?	
									}
								}
								else
								{
									?>
									<tr>
										<td colspan="4" class="mensaje1" style="text-align:center;" >No se encontraron Registros!!!</td>
									</tr>
									<?	
								}
							?> 
							<table width="100%" class="tabla3" border="0"  <?php echo $cellspacingTabla3Mentor; echo $cellpaddingTabla3Mentor; ?>>
								<tr>
									<td style="text-align:left;">
										<a href="#" style="cursor:hand; color:black;font-size:12px; font-weight:bold;" onclick="<? if($Offset>0){?>document.FORMA.Offset.value=parseInt(document.FORMA.Offset.value)-parseInt(document.FORMA.NxP.value);document.location.href='BusquedaCIE.php?DatNameSI=<? echo $DatNameSID?>&Codigo=<? echo $Codigo?>&Nombre=<? echo $Nombre?>&Clasificacion=<? echo $Clasificacion?>&NxP=<? echo $NxP?>&Offset='+document.FORMA.Offset.value<? }?>"><img name="Anterior" src="/Imgs/left.gif" border="0" /> Pag. Anterior</a>
									</td>

									<td style="text-align:center;font-size:12px;color:#002147;font-weight:bold;">Viendo Registros de <? echo $Offset+1?> hasta <? if($NumReg==$NxP){echo $Offset+$NxP;$Ult=$Offset+$NxP;}else{echo $Offset+$NumReg;$Ult=$Offset+$NumReg;}?> de <? echo $NumTotReg?> Diagnosticos</td>

									<td style="text-align:right;">
										<a href="#" style="cursor:hand; color:black; ;font-size:12px;font-weight:bold;" onclick="<? if($Ult<$NumTotReg){?>document.FORMA.Offset.value=parseInt(document.FORMA.Offset.value)+parseInt(document.FORMA.NxP.value);document.location.href='BusquedaCIE.php?DatNameSI=<? echo $DatNameSID?>&Codigo=<? echo $Codigo?>&Nombre=<? echo $Nombre?>&Clasificacion=<? echo $Clasificacion?>&NxP=<? echo $NxP?>&Offset='+document.FORMA.Offset.value<? }?>" >Pag. Siguiente <img name="Siguiente" src="/Imgs/right.gif" border="0" /></a>
									</td>
								</tr>
							</table>
							<?
							if($DF)
							{
							?><script language="javascript">document.location.href='#<? echo $DF?>';</script><?	
							}
							?>	
					</form>
				</div>	
			</body>
