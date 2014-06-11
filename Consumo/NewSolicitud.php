		<?php
			if($DatNameSID){session_name("$DatNameSID");}
			session_start();
			include("Funciones.php");
			include_once("General/Configuracion/Configuracion.php");
			$ND=getdate();
			if(!$FechaSol){
				$FechaSol="$ND[year]-$ND[mon]-$ND[mday] $ND[hours]:$ND[minutes]";
			}
			
			if(!$TMPCOD){
				$TMPCOD=strtotime("$ND[year]-$ND[mon]-$ND[mday] $ND[hours]:$ND[minutes]:$ND[seconds]").rand(1,9999);
			}
			
			if($Cancelar){
				$cons="Delete from Consumo.TmpSolicitudConsumo where TMPCOD='$TMPCOD' or Usuario = '$usuario[0]'";
				$res=ExQuery($cons);
				$Editar = 0;
				$EditarS = 0;
				?><script language="javascript">location.href="SolConsumo.php?DatNameSID=<? echo $DatNameSID?>&Anio=<? echo $Anio?>";</script><?
			}
			
			if($Editar)	{
				$cons = "Select AutoId,Cantidad,CentroCostos,AlmacenPpal,Fecha from Consumo.SolicitudConsumo where Compania='$Compania[0]'
				and IdSolicitud='$IdSolicitud' and Usuario = '$usuario[0]'";
				//echo $cons;
				$res = ExQuery($cons);
				while($fila=ExFetch($res))
				{
					$CC = $fila[2];
					$cons0 = "Select Codigo1, NombreProd1,UnidadMedida,Presentacion from Consumo.CodProductos where AlmacenPpal = '$fila[3]' and AutoId='$fila[0]'
					and Compania = '$Compania[0]'";
					$res0 = ExQuery($cons0);
					$fila0 = ExFetch($res0);
					$cons1 = "Insert into Consumo.TmpSolicitudConsumo (TMPCOD,AlmacenPpal,Fecha,Usuario,AutoId,Codigo,Nombre,Cantidad)
					values ('$TMPCOD','$fila[3]','$fila[4]','$usuario[0]','$fila[0]','$fila0[0]','$fila0[1] $fila0[2] $fila0[3]','$fila[1]')";
					$res1 = ExQuery($cons1); echo ExError();
					$Editar = 0;
					$EditarS = 1;
				}
			}
			if(!$NoProds){
				$NoProds=0;
			}
			
			if($Eliminar==1){
				$cons="Delete from Consumo.TmpSolicitudConsumo where AutoId=$AutoId and TMPCOD='$TMPCOD'";
				$res=ExQuery($cons);
			}
			if($GenerarSolicitud){
				if($CC){
					if(!$EditarS){
						$cons="Select IdSolicitud from Consumo.SolicitudConsumo where Compania='$Compania[0]' Group By IdSolicitud Order By IdSolicitud Desc";
						$res=ExQuery($cons);
						$fila=ExFetch($res);
						$IdSolicitud=$fila[0]+1;
				
						$cons="Select Fecha,Usuario,AutoId,Cantidad,AlmacenPpal from Consumo.TmpSolicitudConsumo where TMPCOD='$TMPCOD'";
						$res=ExQuery($cons);echo ExError();
						while($fila=ExFetch($res))
						{
							$cons2="Insert into Consumo.SolicitudConsumo (IdSolicitud,AlmacenPpal,Fecha,Usuario,AutoId,Cantidad,Compania,Cedula,CentroCostos,Anio) values
							($IdSolicitud,'$fila[4]','$fila[0]','$fila[1]','$fila[2]','$fila[3]','$Compania[0]','$usuario[2]','$CC','$Anio')";
							$res2=ExQuery($cons2);echo ExError();
						}
				
						$cons="Delete from Consumo.TmpSolicitudConsumo where TMPCOD='$TMPCOD' or Usuario = '$usuario[0]'";
						$res=ExQuery($cons);
					}
					else{
						$cons = "Select Fecha,Usuario,AutoId,Cantidad,AlmacenPpal from Consumo.TmpSolicitudConsumo where TMPCOD='$TMPCOD' 
						and AutoId in( Select AutoId from Consumo.SolicitudConsumo where Compania='$Compania[0]' and IdSolicitud='$IdSolicitud' and Usuario = '$usuario[0]' and Anio='$Anio')";
						$res = ExQuery($cons);
						while($fila=ExFetch($res))
						{
							$cons2="Update Consumo.SolicitudConsumo set AlmacenPpal = '$fila[4]', Cantidad = '$fila[3]',CentroCostos = '$CentroCosto' where
							Compania = '$Compania[0]' and IdSolicitud = '$IdSolicitud' and Usuario = '$usuario[0]' and AutoId = '$fila[2]' and Anio='$Anio'";
							$res2=ExQuery($cons2);
						}
						$cons = "Select Fecha,Usuario,AutoId,Cantidad,AlmacenPpal from Consumo.TmpSolicitudConsumo where TMPCOD='$TMPCOD' 
						and AutoId not in( Select AutoId from Consumo.SolicitudConsumo where Compania='$Compania[0]' and IdSolicitud='$IdSolicitud' and Usuario = '$usuario[0]' and Anio='$Anio')";
						$res = ExQuery($cons);
						while($fila=ExFetch($res))
						{
							$cons2="Insert into Consumo.SolicitudConsumo (IdSolicitud,AlmacenPpal,Fecha,Usuario,AutoId,Cantidad,Compania,Cedula,CentroCostos,Anio) values
							($IdSolicitud,'$fila[4]','$fila[0]','$fila[1]','$fila[2]','$fila[3]','$Compania[0]','$usuario[2]','$CentroCosto','$Anio')";
							$res2=ExQuery($cons2);
						}
						$cons = "Delete from Consumo.SolicitudConsumo where Compania = '$Compania[0]' and IdSolicitud='$IdSolicitud' and Anio='$Anio' and
						AutoId not in(Select AutoId from Consumo.TmpSolicitudConsumo where TMPCOD='$TMPCOD')";
						$res = ExQuery($cons);
						$cons="Delete from Consumo.TmpSolicitudConsumo where TMPCOD='$TMPCOD' or Usuario = '$usuario[0]'";
						$res=ExQuery($cons);
					}
					?><script language="javascript">
					open('/Informes/Almacen/Formatos/ImpSolicitudConsumo.php?DatNameSID=<? echo $DatNameSID?>&IdSolicitud=<? echo $IdSolicitud?>&Anio=<? echo $Anio?>','','width=600,height=400,scrollbars=yes');
					location.href='SolConsumo.php?DatNameSID=<? echo $DatNameSID?>&Anio=<? echo $Anio ?>';
					</script>
					<?	
				}
				else{
					?><script language="javascript">
					alert("Antes de realizar esta operacion, seleccione Centro de Costos");
					</script><?
				}
				
			}

			if($Guardar){
				$cons="Select AutoId from Consumo.TmpSolicitudConsumo where TMPCOD='$TMPCOD' and AlmacenPpal='$AlmacenPrincipal' and Usuario='$usuario[0]' and AutoId='$AutoId'";
				$res=ExQuery($cons);
				if(ExNumRows($res)==0){
					$cons="Insert into Consumo.TmpSolicitudConsumo (TMPCOD,AlmacenPpal,Fecha,Usuario,AutoId,Codigo,Cantidad,Nombre) 
					values ('$TMPCOD','$AlmacenPrincipal','$FechaSol','$Usuario','$AutoId','$Codigo','$Cantidad','$Producto')";
					$res=ExQuery($cons);
				}
				else{
					?><script language="javascript">alert("El Producto <? echo $Producto ?> Ya ha sido Solicitado");</script><?
				}
				$AutoId="";$Codigo="";$Cantidad="";$Producto="";$Eliminar=0;
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
					function Validar()
					{
						var b=0;
						if(document.FORMA.Codigo.value==""){alert("Debe seleccionar un producto de la lista");b=1;}
						if(FORMA.Producto.value == ""){alert("El Campo Producto es Obligatorio"); b = 1;}
						else{if(FORMA.Cantidad.value == ""){alert("El Campo Cantidad es Obligatorio"); b = 1;}}
						if(b == 1 )return false;
					}
				</script>
			</head>	
			
			<body <?php echo $backgroundBodyMentor; ?>>
				<?php
					$rutaarchivo[0] = "ALMAC&Eacute;N";
					$rutaarchivo[1] = "SOLICITUD DE CONSUMO";
					$rutaarchivo[2] = "NUEVA SOLICITUD";
					mostrarRutaNavegacionEstatica($rutaarchivo);
				?>
				
				<div <?php echo $alignDiv2Mentor; ?> class="div2">
					<form name="FORMA" method="post" onSubmit="return Validar()">
						<input type="Hidden" name="IdSolicitud" value="<? echo $IdSolicitud?>" />
						<input type="Hidden" name="Editar" value="<? echo $Editar?>" />
						<input type="Hidden" name="EditarS" value="<? echo $EditarS?>" />
						<input type="Hidden" name="Anio" value="<? echo $Anio?>" />
						<input type="hidden" name="DatNameSID" value="<? echo $DatNameSID?>" />
						<input type="hidden" name="AlmacenPrincipal" value="<? echo $AlmacenPrincipal?>" />
						
						<table  width="600px" class="tabla2"  <?php echo $borderTabla2Mentor; echo $bordercolorTabla2Mentor; echo $cellspacingTabla2Mentor; echo $cellpaddingTabla2Mentor; ?>>
							<tr>
								<td colspan="2" class="encabezado2Horizontal"> NUEVA SOLICITUD DE CONSUMO </td>
							</tr>
							<tr>
								<td colspan="2" class="encabezadoGrisaceo" style="text-align:center;">
									ALMAC&Eacute;N PRINCIPAL: <? echo $AlmacenPrincipal?>
								</td>
							</tr>
							<tr>
								<td class="encabezado2HorizontalInvertido">FECHA / HORA</td>
								<td class="encabezado2HorizontalInvertido">USUARIO</td>
							</tr>
							<tr>
								<td><input type="text" name="FechaSol" value="<? echo $FechaSol?>" readonly="yes" style="width:100;"/></td>
								<td><input type="text" name="Usuario" value="<? echo $usuario[0]?>" readonly="yes" style="width:500;"/></td>
							</tr>
							<tr>
								<td colspan="2" class="encabezadoGrisaceo" style="text-align:center;">
									CENTRO DE COSTOS:
									<?
										$cons="Select CentroCostos from Central.CentrosCosto Where COdigo='$CC' and Compania='$Compania[0]'";
										$res = ExQuery($cons);
										$fila = ExFetch($res);
										echo $fila[0];

									?>
								</td>
							</tr>
						</table>

						<br>
						<table width="600px" class="tabla2"  <?php echo $borderTabla2Mentor; echo $bordercolorTabla2Mentor; echo $cellspacingTabla2Mentor; echo $cellpaddingTabla2Mentor; ?>>
							<tr>
								<td class="encabezado2HorizontalInvertido">C&Oacute;DIGO</td>
								<td class="encabezado2HorizontalInvertido">NOMBRE PRODUCTO</td>
								<td class="encabezado2HorizontalInvertido">CANTIDAD</td>
							</tr>

							<?
								$cons="Select Codigo,Nombre,Cantidad,AutoId from Consumo.TmpSolicitudConsumo where TMPCOD='$TMPCOD'";
								$res=ExQuery($cons);echo ExError();
								while($fila=ExFetch($res))	{
									echo "<tr>";
										echo "<td style='text-align:center;'>$fila[0]</td>";
										echo "<td>$fila[1]</td>";
										echo "<td style='text-align:center;'>$fila[2]</td><td>";?>
									<a href="#"  
									onclick="if(confirm('Desea eliminar el registro?'))
									{location.href='NewSolicitud.php?AlmacenPrincipal=<?echo $AlmacenPrincipal?>&DatNameSID=<? echo $DatNameSID?>&FechaSol=<? echo $FechaSol?>&Anio=<? echo $Anio?>&Eliminar=1&TMPCOD=<?echo $TMPCOD?>&AutoId=<? echo $fila[3]?>&CC=<?echo $CC?>&IdSolicitud=<? echo $IdSolicitud?>&EditarS=<? echo $EditarS?>'}">
									<img border="0" src="/Imgs/b_drop.png"/></a></td></tr><?
								}
							?>

							<input type="hidden" name="NoProds" value="<? echo $NoProds?>"/>
							<input type="hidden" name="AutoId" value="<? echo $AutoId?>" readonly />
							<tr>
							<td><input type="text" name="Codigo" value="<?echo $Codigo?>" readonly="yes" style="width:100px;"/></td>
							<td><input type="text" name="Producto" style="width:405px;"
									onfocus="frames.BuscaProductos.location.href='BuscaProductos.php?DatNameSID=<? echo $DatNameSID?>&TMPCOD=<? echo $TMPCOD?>&Anio=<? echo $Anio?>&NomProducto='+this.value+'&AlmacenPpal='+FORMA.AlmacenPrincipal.value" 
									onKeyUp="xLetra(this);
									Codigo.value='';frames.BuscaProductos.location.href='BuscaProductos.php?DatNameSID=<? echo $DatNameSID?>&TMPCOD=<? echo $TMPCOD?>&Anio=<? echo $Anio?>&NomProducto='+this.value+'&AlmacenPpal='+FORMA.AlmacenPrincipal.value" 
									value="<? echo $Producto?>" onKeyDown="xLetra(this)"/></td>
							<td><input type="text" name="Cantidad" value="<?echo $Cantidad?>" style="width:50px;"
							onKeyUp="xNumero(this)" onKeyDown="xNumero(this)" onBlur="campoNumero(this)"/></td>
							<input type="Hidden" name="TMPCOD" value="<? echo $TMPCOD?>" />
							<td><button type="submit" name="Guardar"><img src="/Imgs/b_save.png" title="Guardar"></button></td>
							</tr>
						</table>
					</form>
					
					<form name="FORMA2" method="post">
						<?$CentroCosto = $CC;?>  
						<input type="Hidden" name="CentroCosto" value="<? echo $CentroCosto ?>" >
						<input type="submit" name="GenerarSolicitud" class="boton2Envio" value="Generar Solicitud">
						<input type="submit" name="Cancelar" class="boton2Envio" value="Cancelar" >
						<input type="Hidden" name="TMPCOD" value="<? echo $TMPCOD?>" />
					</form>
					
					<iframe width="100%" id="BuscaProductos" name="BuscaProductos" src="" frameborder="0" style="height:380px;" >
					<iframe id="Busquedas" name="Busquedas" style="display:none;" src="Busquedas.php" frameborder="0" height="400"></iframe>
					</iframe>
				</div>	
			</body>
		</html>	