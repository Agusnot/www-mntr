		<? 
			if($DatNameSID){session_name("$DatNameSID");}
			session_start();
			include("Funciones.php");
			include("ObtenerSaldos.php");
			include_once("General/Configuracion/Configuracion.php");
			$ND=getdate();
			//UM:27-04-2011
			$cons="Select NumDias from Central.Meses where Numero=$ND[mon]";
			$res=ExQuery($cons);$fila=ExFetch($res);$UltDia=$fila[0];
				if(!$Anio){$Anio=$ND[year];}
			if($Anio != $ND[year])
				{
					$FechaI="$Anio-01-01";
					$Fecha="$Anio-12-31";
				}
				else
				{
					$Fecha="$ND[year]-$ND[mon]-$ND[mday]";
					$FechaI = "$ND[year]-01-01";
				}
			$VrSaldoIni=SaldosIniciales($Anio,$AlmacenPrincipal,$FechaI);
			$VrEntradas=Entradas($Anio,$AlmacenPrincipal,$FechaI,$Fecha);
			$VrSalidas=Salidas($Anio,$AlmacenPrincipal,$FechaI,$Fecha);
			$VrDevoluciones = Devoluciones($Anio,$AlmacenPrincipal,"$ND[year]-01-01",$Fecha);
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
					function AbrirCardex(registro,e,AlmacenPpal,AutoId)
					{
						y = e.clientY;
						sT = document.body.scrollTop;
						frames.FrameOpener.location.href="/Informes/Almacen/Reportes/Cardex.php?DatNameSID=<? echo $DatNameSID?>&Anio=<? echo $Anio?>&Cerrar=1&Producto="+AutoId+"&AlmacenPpal="+AlmacenPpal;
						document.getElementById('FrameOpener').style.position='absolute';
						document.getElementById('FrameOpener').style.top=(y/2)+sT;
						document.getElementById('FrameOpener').style.right='10px';
						document.getElementById('FrameOpener').style.display='';
						document.getElementById('FrameOpener').style.width='990';
						document.getElementById('FrameOpener').style.height='390';
					}

				</script>
			</head>	
			<body <?php echo $backgroundBodyMentor; ?>>
				<div <?php echo $alignDiv3Mentor; ?> class="div3">	
				
					<form name="FORMA" method="post" action="FormaProducto.php" target="_parent">
					<?
					if($AutoId){$Buscar=1;}

					if($EliminarR){
						$cons="Delete from Consumo.CodProductos where AutoId=$AutoId and AlmacenPpal = '$AlmacenPrincipal' and Compania='$Compania[0]' and Anio='$Anio'";
						$res=ExQuery($cons);echo ExError();
					}
					
					if ($Buscar){
						if($AutoId)
						{
							$cons="Select AutoId,Codigo1,NombreProd1,Presentacion,UnidadMedida,Grupo,Estado,AlmacenPpal from Consumo.CodProductos 
							where AutoId=$AutoId and AlmacenPpal='$AlmacenPrincipal' and Compania='$Compania[0]' and Anio=$Anio";		
						}
						else{
							$NomPro = str_replace(" ", "%", $NomPro);
							$cons = "Select AutoId,Codigo1,NombreProd1,Presentacion,UnidadMedida,Grupo,Estado,AlmacenPpal from Consumo.CodProductos where Anio = '$Anio' and
							Codigo1 like '$Codigo%' and NombreProd1 ilike '$NomPro%' and Grupo like '%$Grupo%' and AlmacenPpal = '$AlmacenPrincipal' and Compania='$Compania[0]'
							Order by NombreProd1";
						}
						$res = ExQuery($cons);
						echo ExError();
						if(ExNumRows($res)>20){ 
							?>
							<div align="center" style="margin-top:25px;margin-bottom:25px;">
								<table border="0" width="100%" >
									<tr>
										<td style="text-align:center">
											<input type="submit" class="boton2Envio" name="Nuevo" value="Nuevo producto"/>
										</td>
									</tr>
								</table>
							</div>
							<?							
						}
						if(ExNumRows($res)>0){
							?> 
							
							<table width="100%" class="tabla3"    <?php echo $borderTabla3Mentor; echo $bordercolorTabla3Mentor; echo $cellspacingTabla3Mentor; echo $cellpaddingTabla3Mentor; ?>>
								<tr>
									<td class="encabezado2Horizontal" style="font-size:11px">ID</td>
									<td class="encabezado2Horizontal" style="font-size:11px">C&Oacute;DIGO</td>
									<td class="encabezado2Horizontal" style="font-size:11px">NOMBRE PRODUCTO</td>
									<td class="encabezado2Horizontal" style="font-size:11px">EXISTENCIAS</td>
									<td class="encabezado2Horizontal" style="font-size:11px">COSTO</td>
									<td class="encabezado2Horizontal" style="font-size:11px">GRUPO</td>
									<td class="encabezado2Horizontal" style="font-size:11px">ESTADO</td>
									<td colspan="3" class="encabezado2Horizontal" style="font-size:11px">&nbsp; </td>
								</tr>
							<?
							while ($fila = ExFetch($res)){
								$reg++;
								$CantExistencias=$VrSaldoIni[$fila[0]][0]+$VrEntradas[$fila[0]][0]-$VrSalidas[$fila[0]][0]+$VrDevoluciones[$fila[0]][0];
								$SaldoFinal=$VrSaldoIni[$fila[0]][1]+$VrEntradas[$fila[0]][1]-$VrSalidas[$fila[0]][1]+$VrDevoluciones[$fila[0]][1];
								?><tr onMouseOver="this.bgColor='#AAD4FF'" onMouseOut="this.bgColor='#FFFFFF'"><?
								echo "<td>$fila[0]</td>";
								echo "<td>$fila[1]</td>";
								echo "<td>$fila[2] $fila[3] $fila[4]</td>";
								echo "<td style='text-align:right;padding-right:10px;'>".number_format($CantExistencias,2)."</td>";
								echo "<td style='text-align:right;padding-right:10px;'>".number_format($SaldoFinal,2)."</td>";
								echo "<td>$fila[5]</td>";
								if($fila[6]=='AC') echo "<td>Activo</td>";
								else echo "<td>Inactivo</td>";
					?>
								<td>
								<a target="_parent" href="FormaProducto.php?DatNameSID=<? echo $DatNameSID;?>&Editar=1&Anio=<? echo $Anio?>&AutoId=<? echo $fila[0]; ?>&AlmacenPrincipal=<? echo $fila[7]?>">
								<img border="0" title="Editar" src="/Imgs/b_edit.png" />
								</a>
								</td>
								<td><a href="#"  
								onclick="if(confirm('Desea eliminar el registro?'))
								{location.href='BusquedaProducto.php?DatNameSID=<? echo $DatNameSID?>&AlmacenPrincipal=<? echo $AlmacenPrincipal?>&EliminarR=1&Anio=<? echo $Anio?>&AutoId=<? echo $fila[0];?>'}">
								<img border="0" title="Eliminar" src="/Imgs/b_drop.png"/></a></td>
								<td><img style="cursor:hand" title="Cardex" onClick="AbrirCardex('<? echo $reg?>',event,'<? echo $AlmacenPrincipal?>','<? echo $fila[0]?>')" border="0" src="/Imgs/s_vars.png"></td>
								</tr>
					<?
								$SumCant=$SumCant+$CantExistencias;$SumVr=$SumVr+$SaldoFinal;
							}
							echo "<tr>";
								echo "<td colspan=3 class='filaTotales' style='text-align:right;padding-right:10px;'>SALDOS A $Fecha</td>";
								echo "<td class='filaTotales' style='text-align:right;padding-right:10px;'>".number_format($SumCant,2)."</td>";
								echo "<td class='filaTotales' style='text-align:right;padding-right:10px;'>".number_format($SumVr,2)."</td>";
								echo "<td class='filaTotales' colspan='5'>&nbsp;</td>";
							echo "</tr>";
							echo "</table>";
						}
						else{
							echo "<span class='mensaje1'>No existen registros coincidentes</span></br>";	
						}?>
					<input type="Hidden" name="Anio" value="<? echo $Anio?>" />
					<input type="Hidden" name="AlmacenPrincipal" value="<? echo $AlmacenPrincipal?>">
					<input type="hidden" name="DatNameSID" value="<? echo $DatNameSID?>">
					<div align="center" style="margin-top:25px;margin-bottom:25px;">
						<table border="0" width="100%" >
							<tr>
								<td style="text-align:center">
									<input type="submit" class="boton2Envio" name="Nuevo" value="Nuevo producto"/>
								</td>
							</tr>
						</table>
					</div>	
					<iframe id="FrameOpener" name="FrameOpener" style="display:none" frameborder="0" height="1" style="border:#e5e5e5 ridge" ></iframe>
					</form>
					<? }
					?>
			</div>	
			</body>
		</html>	
