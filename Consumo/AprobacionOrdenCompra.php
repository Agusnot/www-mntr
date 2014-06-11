		<?php	
			if($DatNameSID){session_name("$DatNameSID");}
			session_start();
			include("Funciones.php");
			include_once("General/Configuracion/Configuracion.php");
			$ND=getdate();
			if(!$Anio){$Anio = $ND[year];}
			if(!$AlmacenPpal)
			{
				$cons = "Select AlmacenPpal from Consumo.UsuariosxAlmacenes where Usuario='$usuario[0]' and Compania='$Compania[0]'";
				$res = ExQuery($cons);
				$fila = ExFetch($res);
				$AlmacenPpal = $fila[0];		
			}
			if($Guardar)
			{
				while(list($cad,$val)=each($AprobarOC))
				{
					if($val!=""){
					if($val=="Aprobar"){$Aprobadox=$usuario[0];}
					elseif($val=="Rechazar"){$Aprobadox="Rechazado";}
					$cons="Update Consumo.Movimiento set Aprobadox='$Aprobadox', FechaAprobac='$ND[year]-$ND[mon]-$ND[mday] $ND[hours]:$ND[minutes]'
					where Compania='$Compania[0]' and AlmacenPpal='$AlmacenPpal' and TipoComprobante='Orden de Compra' and Numero='$cad'";
					$res=ExQuery($cons);}
				}
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
			<script language="javascript">
				function Mostrar(x,Anio,Comprobante)
				{open("/Informes/Almacen/Formatos/OrdenCompra.php?DatNameSID=<? echo $DatNameSID?>&Anio="+Anio+"&AlmacenPpal=<? echo $AlmacenPpal?>&Comprobante="+Comprobante+"&Numero="+x,'','width=600,height=600,scrollbars=yes');}
			</script>
		</head>	
		<body <?php echo $backgroundBodyMentor; ?>>
			<?php
				$rutaarchivo[0] = "ALMAC&Eacute;N";
				$rutaarchivo[1] = "APROBAR ORDEN DE COMPRA";				
				mostrarRutaNavegacionEstatica($rutaarchivo);
			?>
			<div <?php echo $alignDiv2Mentor; ?> class="div2">
				<form name="FORMA" method="post">
					<input type="hidden" name="DatNameSID" value="<? echo $DatNameSID?>" />
					<table class="tabla2"  <?php echo $borderTabla2Mentor; echo $bordercolorTabla2Mentor; echo $cellspacingTabla2Mentor; echo $cellpaddingTabla2Mentor; ?>>
						<tr>
							<td class="encabezado2Horizontal" colspan="3"> APROBACI&Oacute;N ORDEN DE COMPRA </td>
						</tr>
						<tr>
							<td class="encabezado2HorizontalInvertido"> ALMAC&Eacute;N </td>
							<td class="encabezado2HorizontalInvertido"> A&Ntilde;O </td>
							<td class="encabezado2HorizontalInvertido"> &nbsp; </td>							
						</tr>
						<tr>
							<td style="text-align:center;">
								
								<select name="AlmacenPpal" onChange="document.FORMA.submit();">
									<option value="">&nbsp; </option>
									<?
										$cons = "Select AlmacenPpal from Consumo.UsuariosxAlmacenes where Usuario='$usuario[1]' and Compania='$Compania[0]'";
										$res = ExQuery($cons);
										while($fila = ExFetch($res)){
											if($AlmacenPpal==$fila[0]){echo "<option selected value='$fila[0]'>$fila[0]</option>";}
											else{echo "<option value='$fila[0]'>$fila[0]</option>";}
										}
									?>
								</select>
							</td>
							<td  style="text-align:center;">
								<select name="Anio" onChange="document.FORMA.submit();" title="Año">
									<option value="">&nbsp; </option>
									<?
										$cons = "Select Anio from Central.Anios Where Compania='$Compania[0]' order by anio asc";
										$res = ExQuery($cons);
										while($fila = ExFetch($res)){
											if($Anio==$fila[0]){ echo "<option selected value='$fila[0]'>$fila[0]</option>";}
											else{echo "<option value='$fila[0]'>$fila[0]</option>";}
										}
									?>
								</select>
							</td>
							<td>
								<input type="submit" class="boton2Envio" value="Buscar"/>
							</td>
						</tr>
					</table>		
							
					<? 
						if($AlmacenPpal && $Anio){
					?>
					<table class="tabla3"  <?php echo $borderTabla3Mentor; echo $bordercolorTabla3Mentor; echo $cellspacingTabla3Mentor; echo $cellpaddingTabla3Mentor; ?>  width="100%">
						<tr>
							<td class="encabezado2Horizontal">FECHA</td>
							<td class="encabezado2Horizontal">N&Uacute;MERO</td>
							<td class="encabezado2Horizontal">COMPROBANTE</td>
							<td class="encabezado2Horizontal">DETALLE</td>
							<td colspan="2" class="encabezado2Horizontal">TERCERO</td>
							<td class="encabezado2Horizontal">VALOR</td>
							<td class="encabezado2Horizontal">APROBAR</td>
						</tr>
					<?
						$cons="Select Fecha,Numero,Comprobante,Detalle,PrimApe,SegApe,PrimNom,SegNom,Movimiento.Cedula,Sum(TotCosto)+sum(VrIVA)-sum(VrDescto),Aprobadox
						from Consumo.Movimiento,Central.Terceros where Movimiento.Cedula=Terceros.Identificacion and Terceros.Compania='$Compania[0]' 
						and Movimiento.Compania='$Compania[0]' and VoBo=1 and
						TipoComprobante='Orden de Compra' and AlmacenPpal='$AlmacenPpal' and Anio=$Anio and Aprobadox isNULL Group by Numero,Fecha,Comprobante,Detalle,
						PrimApe,SegApe,PrimNom,SegNom,Movimiento.Cedula,Aprobadox Order By Numero";
						$res=ExQuery($cons);
						if(ExNumRows($res)>0)
						{
							while($fila=ExFetch($res))
							{
								$AnioOrden = substr($fila[0],0,4);
								if($fila[10]=="")
								{
									echo "<tr>";
										echo"<td style='text-align:center;'>$fila[0]</td>";
									?><td style="cursor:hand" title="Ver Orden de Compra" onMouseOver="this.bgColor='#AAD4FF'" 	onmouseout="this.bgColor='#FFFFFF'" onclick="Mostrar('<? echo $fila[1]?>','<? echo $AnioOrden?>','<? echo $fila[2]?>')" align="center">
									<?
									echo "$fila[1]</td>
									<td>$fila[2]</td><td>$fila[3]</td><td title='C.C.$fila[8]'>$fila[4] $fila[5] $fila[6] $fila[7]</td>";
									?>
									<td width="16px" style="cursor:hand"
									onMouseOver="this.bgColor='#AAD4FF'" 
									onmouseout="this.bgColor='#FFFFFF'">
										<img title="Historial de Ordenes de Compra de este Tercero" src="/Imgs/s_process.png" 
										onClick="open('/Informes/Almacen/Reportes/OrdenCompraxTercero.php?DatNameSID=<? echo $DatNameSID?>&Numero=<? echo $fila[1]?>&AlmacenPpal=<? echo $AlmacenPpal ?>
										&FechaIni=<? echo "$ND[year]-01-01"?>&FechaFin=<? echo "$ND[year]-$ND[mon]-$ND[mday]"?>
										&Cedula=<? echo $fila[8]?>&Nombre=<? echo "$fila[4] $fila[5] $fila[6]"?>','','width=400,height=400,scrollbars=yes')" ></td>
									<td align='right'><? echo number_format($fila[9],2) ?></td>
									<td><select name="AprobarOC[<? echo $fila[1]?>]">
									<option></option>
										<option value="Rechazar">Rechazar</option>
										<option value="Aprobar">Aprobar</option>
									</select></td>
									</tr>		
									<?
								}
							}
							?>
							</table>
							<input type="submit" name="Guardar" value="Guardar" />
							<?	
						}
						else
						{
							echo "<div align='center' class='mensaje1' style='margin-top:25px;margin-bottom:25px;'>No hay solicitudes pendientes de aprobaci&oacute;n</div>";	
						}
						} ?>
				</form>
			</div>	
		</body>