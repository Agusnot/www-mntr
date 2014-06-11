		<?
			if($DatNameSID){session_name("$DatNameSID");}
			session_start();
			include("Funciones.php");
			include_once("General/Configuracion/Configuracion.php");
			if($Eliminar){
				$cons = "Delete from Consumo.CriteriosXProveedor where 
				Compania='$Compania[0]' and AlmacenPpal='$AlmacenPpal' and Grupo='$Grupo' and Fecha='$Fecha'";
				$res = ExQuery($cons); echo ExError();
				$Eliminar=0;
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
					function CerrarThis(){
						parent.document.getElementById('FrameOpener').style.position='absolute';
						parent.document.getElementById('FrameOpener').style.top='1px';
						parent.document.getElementById('FrameOpener').style.left='1px';
						parent.document.getElementById('FrameOpener').style.width='1';
						parent.document.getElementById('FrameOpener').style.height='1';
						parent.document.getElementById('FrameOpener').style.display='none';
					}
					function NuevoReg()	{
						document.FORMA.action = "NuevaEvaluacionCriterio.php";
						document.FORMA.submit();
					}
				</script>
			</head>	
			<body>
				<div align="center">
					<form name="FORMA" method="post">
						<table  width="100%"  class="tabla1"  <?php echo $borderTabla1Mentor; echo $bordercolorTabla1Mentor; echo $cellspacingTabla1Mentor; echo $cellpaddingTabla1Mentor; ?>>
							<tr>
								<td colspan="2" class="encabezado1Horizontal">CRITERIOS DE <? echo strtoupper($Tipo);?></td>
							</tr>
							<tr>
								<td width="30%" class="encabezado1VerticalInvertido"> ALMAC&Eacute;N PRINCIPAL</td>
								<td width="70%">
									<select name="AlmacenPpal" onChange="document.FORMA.submit();" style="width:100%;">
										<?
											$cons = "Select AlmacenPpal from Consumo.UsuariosxAlmacenes where Usuario='$usuario[0]' and Compania='$Compania[0]'";
											$res = ExQuery($cons);
											while($fila = ExFetch($res)){
												if($AlmacenPpal==$fila[0]){echo "<option selected value='$fila[0]'>$fila[0]</option>";}
												else{echo "<option value='$fila[0]'>$fila[0]</option>";}
											}
											if($Tipo=='Seleccion'){
												$cons="Select Grupo from Consumo.Grupos
														where AlmacenPpal='$AlmacenPpal' and Compania='$Compania[0]' 
														and Grupo not in
															(Select Grupo from Consumo.CriteriosXProveedor where AlmacenPpal='Almacen Consumo' and Compania='$Compania[0]' and Tipo='$Tipo')";
											}
											else{$cons="Select Grupo from Consumo.Grupos where AlmacenPpal='$AlmacenPpal' and Compania='$Compania[0]'";}
												
											$res=ExQuery($cons);
											$fila = ExFetch($res);
										?>
										<input type="Hidden" name="Grupo" value="<? echo $fila[0]?>" />
									</select>
									
								</td>
							</tr>
						</table>
						
						<table width="100%" class="tabla1"  <?php echo $borderTabla1Mentor; echo $bordercolorTabla1Mentor; echo $cellspacingTabla1Mentor; echo $cellpaddingTabla1Mentor; ?>>
						<?
							$cons="Select Grupo,Fecha from Consumo.CriteriosXProveedor 
								where Compania='$Compania[0]' and AlmacenPpal='$AlmacenPpal' and Tipo='$Tipo' group by Fecha, Grupo";
							//echo $cons;
							$res = ExQuery($cons);
							if(ExNumRows($res)>0)
							{
								echo"<tr>";
									echo "<td class='encabezado1Horizontal'>GRUPO</td>";
									echo "<td class='encabezado1Horizontal'>FECHA</td>";
								echo "</tr>";
								while($fila = ExFetch($res)){
									echo "<tr><td>$fila[0]</td><td>$fila[1]</td>";
									?>
									<td>
									<a href="NuevaEvaluacionCriterio.php?DatNameSID=<? echo $DatNameSID?>&Editar=1&Fecha=<? echo $fila[1]?>&Grupo=<? echo $fila[0]?>&Tipo=<? echo $Tipo?>&Cedula=<? echo $Cedula?>&AlmacenPpal=<? echo $AlmacenPpal?>">
									<img title="Editar" border="0" src="/Imgs/b_edit.png" />
									</a>
									</td>
									<td><a href="#"  
									onclick="if(confirm('Desea eliminar el registro?'))
									{location.href='EvaluacionCriterios.php?DatNameSID=<? echo $DatNameSID?>&Eliminar=1&Fecha=<? echo $fila[1]?>&Grupo=<? echo $fila[0]?>&Tipo=<? echo $Tipo?>&Cedula=<? echo $Cedula?>&AlmacenPpal=<? echo $AlmacenPpal?>'}">
									<img border="0" src="/Imgs/b_drop.png"/></a></td></tr>
									<?
								}
							}
						?>
					</table>
					<input type="Hidden" name="Cedula" value="<? echo $Cedula?>" />
					<input type="Hidden" name="Tipo" value="<? echo $Tipo?>" />
					<input type="button" name="Nuevo" class="boton2Envio" value="Nuevo" onClick="NuevoReg()"  />
					<input type="button" name="cerrar" class="boton2Envio" value="Cerrar" onClick="CerrarThis()" />
					<input type="hidden" name="DatNameSID" value="<? echo $DatNameSID?>" />
				</form>
			</div>	
			</body>

