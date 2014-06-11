		<?
			if($DatNameSID){session_name("$DatNameSID");}
			session_start();
			include("Funciones.php");
			include_once("General/Configuracion/Configuracion.php");	
			$ND=getdate();
			if($RevAprob)
			{
				//echo $CantDesp."----";
				if(!$CantDesp)
				{
					$consxx = "Update Consumo.SolicitudConsumo set CantAprobada = 0, Estado = 'Rechazada', UsuarioReversion = '$usuario[1]'  
					where Compania='$Compania[0]' and AlmacenPpal = '$AlmacenPpal' and IdSolicitud=$IdSolicitud
					and AutoId=$AutoId and Anio=$Anio";
				}
				else
				{
					$consxx = "Update Consumo.SolicitudConsumo set CantAprobada = $CantDesp, UsuarioReversion = '$usuario[1]'   
					where Compania='$Compania[0]' and AlmacenPpal = '$AlmacenPpal' and IdSolicitud=$IdSolicitud
					and AutoId=$AutoId and Anio=$Anio";
				}
				$resxx = ExQuery($consxx);
				//echo $consxx;
				echo "<font color='red'><em>Se ha realizado la reversion de aprobacion</em></font>";
			}
			if($AprobarTotal)
			{
				$consx = "Update Consumo.SolicitudConsumo set cantAprobada=cantidad, Estado='Aprobada',Aprobadox='$usuario[0]', FechaAprob='$ND[year]-$ND[mon]-$ND[mday]'
				Where IdSolicitud='$IdSolicitud' and Compania='$Compania[0]' and AlmacenPpal='$AlmacenPpal' and Estado = 'Solicitado'";
				$res = ExQuery($consx);
			}
			if($RechazarTotal)
			{
				$consx = "Update Consumo.SolicitudConsumo set cantAprobada=cantidad, Estado='Rechazada',Aprobadox='$usuario[0]', FechaAprob='$ND[year]-$ND[mon]-$ND[mday]'
				Where IdSolicitud='$IdSolicitud' and Compania='$Compania[0]' and AlmacenPpal='$AlmacenPpal' and Estado = 'Solicitado'";
				$res = ExQuery($consx);
			}
			$cons="Select Fecha,Usuario,Codigo1,Cantidad,NombreProd1,Presentacion,UnidadMedida,
			SolicitudConsumo.Estado,SolicitudConsumo.AutoId,SolicitudConsumo.Cedula,CantAprobada,SolicitudConsumo.Anio,SUM(CantAprobada)
			from Consumo.SolicitudConsumo,Consumo.CodProductos 
			where SolicitudConsumo.IdSolicitud=$IdSolicitud and SolicitudConsumo.AutoId=CodProductos.AutoId
			and SolicitudConsumo.Compania='$Compania[0]' and CodProductos.Compania='$Compania[0]' and CodProductos.Anio=$Anio
			and SolicitudConsumo.AlmacenPpal='$AlmacenPpal' and CodProductos.AlmacenPpal='$AlmacenPpal'
			group by Fecha,Usuario,Codigo1,Cantidad,NombreProd1,Presentacion,UnidadMedida,SolicitudConsumo.Estado,SolicitudConsumo.Autoid,
			SolicitudConsumo.Cedula,CantAprobada,SolicitudConsumo.Anio";
			//echo $cons;exit;
			$res=ExQuery($cons);
			$fila=ExFetch($res);
			echo ExError();
			$Fecha=$fila[0];$Usuario=$fila[1];$CantidadTotalAprobada=$fila[12];
			if($fila[7]=="Anulado"){echo "<img style='position:absolute;left:100px;top:100px;' src='/Imgs/Anulado.gif'>";}
			if($Guardar)
			{
				if($Estado)
				{
					while (list($val,$cad) = each ($Estado)) 
					{
						$cons="Update Consumo.SolicitudConsumo set 
						Estado='$cad', Aprobadox='$usuario[0]', FechaAprob='$ND[year]-$ND[mon]-$ND[mday]' 
						where AutoId='$val' and IdSolicitud='$IdSolicitud' and Compania='$Compania[0]' and AlmacenPpal='$AlmacenPpal'";
						$res=ExQuery($cons);
					}	
				}
				if($CantAprobada)
				{
					while (list($val,$cad) = each ($CantAprobada)) 
					{
						$cons="Update Consumo.SolicitudConsumo set 
						CantAprobada='$cad'
						where AutoId='$val' and IdSolicitud='$IdSolicitud' and Compania='$Compania[0]' and AlmacenPpal='$AlmacenPpal'";
						$res=ExQuery($cons);
					}		
				}
				?><script language="javascript">
				location.href="AprobacionSolConsumo.php?DatNameSID=<? echo $DatNameSID?>&AlmacenPpal=<? echo $AlmacenPpal?>&Estado=<? echo $EstadoPro?>";
				</script><?
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
				<script language='javascript' src="/Funciones.js"></script>
				<script language="javascript">
				function AbrirReporte(ruta)
				{
					frames.FrameOpener.location.href=ruta;
					document.getElementById('FrameOpener').style.position='absolute';
					document.getElementById('FrameOpener').style.top='50px';
					document.getElementById('FrameOpener').style.left='5px';
					document.getElementById('FrameOpener').style.display='';
					document.getElementById('FrameOpener').style.width='500';
					document.getElementById('FrameOpener').style.height='400';
				}
				function Aprobar(x,y)
				{
					if(document.getElementById(x).value=="Aprobada")
					{
						z=x*10;
						document.getElementById(z).disabled=false;
						document.getElementById(z).value=y;
					}
					else
					{
						z=x*10;
						document.getElementById(z).value="";
						document.getElementById(z).disabled=true;
					}
				}
				function Verificar(w,c)
				{
					i=w*10;
					if(document.getElementById(i).value>c)
					{
						alert("La cantidad aprobada no puede ser mayor que la solicitada");
						document.getElementById(i).value=c;
						//document.getElementById(i).focus();
					}
				}
			</script>
		</head>	
		<body <?php echo $backgroundBodyMentor; ?>>
			<?php
					$rutaarchivo[0] = "ALMAC&Eacute;N";
					$rutaarchivo[1] = "APROBAR SOLICITUD DE CONSUMO";
					$rutaarchivo[2] = "DETALLE SOLICTUD";
					mostrarRutaNavegacionEstatica($rutaarchivo);
				?>
			<div <?php echo $alignDiv2Mentor; ?> class="div2">	
				<table class="tabla2" border="0" width="90%"  <?php  echo $cellspacingTabla2Mentor; echo $cellpaddingTabla2Mentor; ?>>
					<tr>
						<td class="encabezado2VerticalInvertido"> FECHA Y HORA SOLICITUD</td>
						<td><? echo $Fecha?></td>
						<td width="50%">&nbsp; </td>
						<td rowspan="2">
							<table border="0" cellpadding="2" cellspacing="0">
								<tr>
									<td class="encabezado2HorizontalInvertido">NO. SOLICITUD</td>
								</tr>
								<tr>
									<td style="text-align:center;">
										<? echo $IdSolicitud?>
									</td>
								</tr>
							</table>
						</td>
					</tr>
					<tr>
						<td class="encabezado2VerticalInvertido">SOLICITANTE</td>
						<td><? echo $Usuario?></td>
						<td width="50%">&nbsp; </td>
					</tr>
				</table>
				<br />
				<form name="FORMA" method="post">
				<?
				if(!$CantidadTotalAprobada )
				{ ?>
				<div align="right" style="margin-right:5%;margin-top:10px;margin-bottom:10px;">
					<button type="submit" name="AprobarTotal" title="Aprobar todos los productos de la solicitud">
						<img src="/Imgs/b_check.png" >
					</button>
					<button type="submit" name="RechazarTotal" title="Rechazar todos los productos de la solicitud">
						<img src="/Imgs/b_drop.png" >
					</button> 
				</div>
				<? }?>
				
				<table class="tabla2" width="90%"  <?php echo $borderTabla2Mentor; echo $bordercolorTabla2Mentor; echo $cellspacingTabla2Mentor; echo $cellpaddingTabla2Mentor; ?>>
					<tr>
						<?
							$cons1="Select CentroCostos from Central.CentrosCosto where Compania='$Compania[0]' and Codigo='$CC'";
							$res1=ExQuery($cons1);
							$fila1=ExFetch($res1);
						?>
					<td class="encabezado2Horizontal" colspan="7">CENTRO DE COSTOS <? echo $CC." - ".strtoupper($fila1[0]);?> </td>
					
				</tr>
				<tr>
					<td class="encabezado2HorizontalInvertido">C&Oacute;DIGO</td>
					<td class="encabezado2HorizontalInvertido" colspan="2">NOMBRE</td>
					<td class="encabezado2HorizontalInvertido" style="font-size:11px;">CANTIDAD SOLICITADA</td>
					<td class="encabezado2HorizontalInvertido" style="font-size:11px;">CANTIDAD APROBADA</td>
					<td class="encabezado2HorizontalInvertido" style="font-size:11px;">CANTIDAD DESPACHADA</td>
					<td class="encabezado2HorizontalInvertido">ESTADO</td>
				</tr>
				<?
					$res=ExQuery($cons);
					while($fila=ExFetch($res)){
						$cons2="Select sum(Cantidad) from Consumo.Movimiento
						where AutoId='$fila[8]' and IdSolicitud='$IdSolicitud' and TipoComprobante='Salidas' and Cedula='$fila[9]'
						and Compania='$Compania[0]' and AlmacenPpal='$AlmacenPpal' and Estado='AC'";
						$res2=ExQuery($cons2);
						$fila2=ExFetch($res2);
						echo "<tr>";
							echo "<td style='text-align:center'>$fila[2] &nbsp;</td>";
							echo "<td>$fila[4] $fila[5] $fila[6] &nbsp;</td>";
						?>
						<td width="16px" style="cursor:hand"	onMouseOver="this.bgColor='#AAD4FF'" onmouseout="this.bgColor='#FFFFFF'">
							<img title="Historial de despacho de este producto" src="/Imgs/s_process.png" onClick="AbrirReporte('/Informes/Almacen/Reportes/ProductosxTercero.php?DatNameSID=<? echo $DatNameSID?>&AutoId=<? echo $fila[8]?>&Tercero=<? echo $fila[9]?>&CC=<? echo $CC?>&FechaIni=<? echo "$ND[year]-01-01"?>&FechaFin=<? echo "$ND[year]-$ND[mon]-$ND[mday]"?>&Nombre=<? echo "$fila[4] $fila[5] $fila[6]"?>')" >
						</td>
						<?
						echo "<td style='text-align:center;'>$fila[3]&nbsp;</td>";
						echo "<td style='text-align:center;'>$fila[10]&nbsp;</td>";
						echo "<td style='text-align:center;'>$fila2[0]&nbsp;</td>";
						
						if($fila[7]!="Solicitado"){
							echo "<td>$fila[7]";
								if($fila[7]=="Aprobada" && (!$fila2[0] || $fila2[0] < $fila[10])){
									?><img src="/Imgs/b_drop.png" style="margin-left:15px;margin-right:15px;" title="Reversar Aprobacion" style="cursor:hand" onClick="location.href='DetAprobSolConsumo.php?DatNameSID=<? echo $DatNameSID?>&RevAprob=1&AutoId=<? echo $fila[8]?>&CantDesp=<? echo $fila2[0]?>&CantAprob=<? echo $fila[10]?>&IdSolicitud=<? echo $IdSolicitud?>&AlmacenPpal=<? echo $AlmacenPpal?>&EstadoPro=<? echo $EstadoPro?>&CC=<? echo $CC?>&Anio=<? echo $fila[11]?>'" />
								</td>
							</tr><?
							}
							else{
								?></td></tr><?
							}
						}
						else{
							?>
							<td>
							<select name="Estado[<? echo $fila[8] ?>]" id="<? echo $fila[8] ?>" onChange="Aprobar(<? echo $fila[8] ?>,<? echo $fila[3]?>)">
								<option value="Solicitado">Solicitado</option>
								<option value="Aprobada">Aprobada</option>
								<option value="Rechazada">Rechazada</option>
							</select>
							<input type="text" name="CantAprobada[<? echo $fila[8] ?>]" id="<? echo $fila[8]*10 ?>" size="3" maxlength="5" disabled 
							onChange="Verificar(<? echo $fila[8]?>,<? echo $fila[3]?>);this.focus();" onKeyUp="xNumero(this)" onKeyDown="xNumero(this)" onBlur="campoNumero(this)">
							</td></tr>
							<?
						}
					}
				?>
				</table>
				<div align="center" style="margin-top:15px;margin-bottom:15px;">
					<input type="hidden" name="DatNameSID" value="<? echo $DatNameSID?>" />
					<input type="Hidden" name="IdSolicitud" value="<? echo $IdSolicitud?>">
					<input type="Hidden" name="EstadoPro" value="<? echo $EstadoPro;?>" />
					<input type="Hidden" name="AlmacenPpal" value="<? echo $AlmacenPpal;?>" />
					<input type="submit" name="Guardar" class="boton2Envio" value="Guardar" />
					<input type="button" name="Cancelar" class="boton2Envio" value="Cancelar" onClick="location.href='AprobacionSolConsumo.php?DatNameSID=<? echo $DatNameSID?>&AlmacenPpal=<? echo $AlmacenPpal?>&Estado=<? echo $EstadoPro?>'" />
				</div>	
				</form>
				<iframe id="FrameOpener" name="FrameOpener" style="display:none" frameborder="0" height="1" style="border:#e5e5e5 ridge" >
			</div>	
		</body>
	</html>	