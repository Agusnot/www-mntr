		<?	if($DatNameSID){session_name("$DatNameSID");}
			session_start();
			include("Funciones.php");
			include_once("General/Configuracion/Configuracion.php");
			if($Agregar)
			{
				while( list($cad,$val) = each($Dx))
				{
					if($cad && $val)
					{	
						$cons="insert into historiaclinica.dxpermitidosxformato (compania,tipoformato,formato,dxformat) 
						values ('$Compania[0]','$TF','$Formato','$cad')";
						$res=ExQuery($cons);
					}
				}?>
				<script language="javascript">
					parent.location.href='DiagnosticoxFormato.php?DatNameSID=<? echo $DatNameSID?>&NewFormato=<? echo $Formato?>&TF=<? echo $TF?>';
				</script>
		<?	}
			
		?>
		
		
			<html>
					<head>	
						<?php echo $codificacionMentor; ?>
						<?php echo $autorMentor; ?>
						<?php echo $titleMentor; ?>
						<?php echo $iconMentor; ?>
						<?php echo $shortcutIconMentor; ?>
						<link rel="stylesheet" type="text/css" href="../../General/Estilos/estilos.css">
							
						<script language="javascript">
						function Validar()
						{
							var ban=0;
							for (var i=0;i < document.forms["FORMA"].elements.length;i++) 
							{ 
								var elemento = document.forms[0].elements[i]; 
								if (elemento.type == "checkbox") 
								{ 
									if(elemento.checked&&elemento.name!='Todos'){
										ban=1
									}
								} 	
							} 
							if(ban==0){
								alert("Debe seleccionar almenos un diagnostico!!!");return false;
							}	
						}
						</script>
					</head>
					
					<body <?php echo $backgroundBodyMentor; ?>>
						<div align="center">
							<form name="FORMA" method="post" onSubmit="return Validar()">
							<?
							if($Codigo||$Nombre)
							{
								$cons="select dxformat from historiaclinica.dxpermitidosxformato where compania='$Compania[0]' and formato='$Formato' and tipoformato='$TF'";
								$res=ExQuery($cons);
								//echo $cons;
								if(ExNumRows($res)>0)
								{
									$DxPrev=1;
								}
								
								if($Codigo){$Cod="And codigo ilike '$Codigo%'";}
								if($Nombre){$Nom="and diagnostico ilike '%$Nombre%'";}
								if($DxPrev){$DxP=" and codigo not in (select dxformat from historiaclinica.dxpermitidosxformato where compania='$Compania[0]' and formato='$Formato' and tipoformato='$TF')";}
								$cons="select codigo,diagnostico from salud.cie where codigo is not null $Cod $Nom $DxP order by codigo,diagnostico";	
								$res=ExQuery($cons);
							}	
							?>

							<table class="tabla2"   <?php echo $borderTabla2Mentor; echo $bordercolorTabla2Mentor; echo $cellspacingTabla2Mentor; echo $cellpaddingTabla2Mentor; ?>>
								<?	if($Codigo||$Nombre)
									{?>
										<tr>
											<td colspan="3" style="text-align:center;">
												<input type="submit" class="boton2Envio" name="Agregar" value="Agregar">
											</td>
								<?	}?>        
									<tr> 
										<td class="encabezado2Horizontal">C&Oacute;DIGO</td>
										<td class="encabezado2Horizontal">NOMBRE</td>
										<td class="encabezado2Horizontal">&nbsp;</td>
									</tr>
								<?	if($Codigo||$Nombre)
									{
										while($fila=ExFetch($res))
										{?>
											<tr onMouseOver="this.bgColor='#AAD4FF'" onMouseOut="this.bgColor=''">
												<td><? echo $fila[0]?></td><td><? echo $fila[1]?></td>
												<td><input type="checkbox" name="Dx[<? echo $fila[0]?>]" checked></td>
											</tr>		
									<?	}
									}?>    
							</table>
							<input type="hidden" name="CodCup" value="<? echo $CodCup?>">
							<input type="hidden" name="Finalidad" value="<? echo $Finalidad?>">
							<input type="hidden" name="TF" value="<? echo $TF?>">
							<input type="hidden" name="DatNameSID" value="<? echo $DatNameSID?>">
							</form>
						</div>	
					</body>
			</html>    