		<?php
			if($DatNameSID){session_name("$DatNameSID");}
			session_start();
			include("Funciones.php");
			include_once("General/Configuracion/Configuracion.php");
			if($Guardar)
			{
				$cons="delete from salud.formatolabext where compania='$Compania[0]'";
				$res=ExQuery($cons);
				$cons="insert into salud.formatolabext (compania,tipoformato,formato,id_item) values ('$Compania[0]','$TipoFormato','$Formato',$Item)";
				$res=ExQuery($cons);
			}
			$cons="select tipoformato,formato,id_item from salud.formatolabext where compania='$Compania[0]'";
			$res=ExQuery($cons);
			$fila=ExFetch($res);
			if(!$TipoFormato){$TipoFormato=$fila[0];}
			if(!$Formato){$Formato=$fila[1];}
			if(!$Item){$Item=$fila[2];}
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
					function Validar()
					{
						if(document.FORMA.TipoFormato.value==""){alert("Debe seleccionar el Tipo de Formato!!!");return false;}
						if(document.FORMA.Formato.value==""){alert("Debe seleccionar el Formato!!!");return false;}
					}
				</script>
			</head>


			<body <?php echo $backgroundBodyMentor; ?>>
				<?php
					$rutaarchivo[0] = "HISTORIA CL&Iacute;NICA";
					$rutaarchivo[1] = "CONFIGURACI&Oacute;N";
					$rutaarchivo[2] = "LABORATORIOS EXTERNOS";
					mostrarRutaNavegacionEstatica($rutaarchivo);
					
				?>	
				<div <?php echo $alignDiv2Mentor; ?> class="div2">	
					<form name="FORMA" method="post" onSubmit="return Validar()">
						<table class="tabla2"  <?php echo $borderTabla2Mentor; echo $bordercolorTabla2Mentor; echo $cellspacingTabla2Mentor; echo $cellpaddingTabla2Mentor; ?>>
							<tr>
								<td colspan="4" class="encabezado2Horizontal"> ASOCIAR LABORATORIOS CON :</td>
							</tr>
							<tr>
								<td class="encabezado2VerticalInvertido">TIPO FORMATO</td>
							<?	$cons="Select * from Salud.especialidades where compania='$Compania[0]' Order By especialidad";
								$res=ExQuery($cons);?>
								<td>
									<select name="TipoFormato" onchange="document.FORMA.submit()"><option></option>
									<?	while($fila=ExFetch($res)){?>
											<option value="<? echo $fila[0]?>" <? if($fila[0]==$TipoFormato){?> selected<? }?>><? echo $fila[0]?></option>	
									<?	}?>
									</select>
								</td>
								<td class="encabezado2VerticalInvertido">FORMATO</td>
							 <?	$cons="Select formato from HistoriaClinica.Formatos where compania='$Compania[0]' and TipoFormato='$TipoFormato' Order By formato";
								$res=ExQuery($cons);?>
								<td>
									<select name="Formato" onChange="document.FORMA.submit()"><option></option>
									<?	while($fila=ExFetch($res)){?>
											<option value="<? echo $fila[0]?>" <? if($fila[0]==$Formato){?> selected<? }?>><? echo $fila[0]?></option>	
									<?	}?>
									</select>
								</td>   
							</tr>
							<tr>
								 <td class="encabezado2VerticalInvertido">ITEM</td>
							 <?	$cons="Select item,id_item from HistoriaClinica.itemsxFormatos where compania='$Compania[0]' and TipoFormato='$TipoFormato' and formato='$Formato' Order By formato";
								$res=ExQuery($cons);?>
								<td  colspan="3">
									<select name="Item">
									<?	while($fila=ExFetch($res)){?>
											<option value="<? echo $fila[1]?>" <? if($fila[1]==$Item){?> selected<? }?>><? echo $fila[0]?></option>	
									<?	}?>
									</select>
								</td>
							</tr>
							<tr>
								<td colspan="4" style="text-align:center;">
									<input type="submit" value="Guardar" class="boton2Envio" name="Guardar">
								</td>
							</tr>
						</table>
						<input type="hidden" name="DatNameSID" value="<? echo $DatNameSID?>"/>
					</form>
				</div>	
			</body>
		</html>
