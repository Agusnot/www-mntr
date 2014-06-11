		<?
			if($DatNameSID){session_name("$DatNameSID");}
			session_start();
			include("Funciones.php");
			include_once("General/Configuracion/Configuracion.php");
			$ND=getdate();
			$AnioI=$ND[year];
			$MesI=$ND[mon];
			$DiaI=$ND[mday];
			$diacorte=$_GET['diacorte'];
			//if(!$NomInventario){$NomInventario="2011-11-23 $ND[hours]:$ND[minutes]:$ND[seconds]";}
			if(!$NomInventario){$NomInventario="$AnioI-$MesI-$DiaI $ND[hours]:$ND[minutes]:$ND[seconds]";}
		?>
	
		<html>
			<head>
				<?php echo $codificacionMentor; ?>
				<?php echo $autorMentor; ?>
				<?php echo $titleMentor; ?>
				<?php echo $iconMentor; ?>
				<?php echo $shortcutIconMentor; ?>
				<link rel="stylesheet" type="text/css" href="../General/Estilos/estilos.css">
			</head>
			<body <?php echo $backgroundBodyMentor; ?>>
				<div <?php echo $alignDiv2Mentor; ?> class="div2">
					<form name="FORMA" target="ListaProductos" action="ProductosConteo.php">
						<input type="hidden" name="AlmacenPpal" value="<? echo $AlmacenPpal?>">
						<input type="hidden" name="NomInventario" value="<? echo $NomInventario?>">
						<input type="hidden" name="Editar" value="<? echo $Editar?>">
						<input type="hidden" name="DatNameSID" value="<? echo $DatNameSID?>" />
					
						<table class="tabla2" width="850px"  <?php echo $borderTabla2Mentor; echo $bordercolorTabla2Mentor; echo $cellspacingTabla2Mentor; echo $cellpaddingTabla2Mentor; ?>>
							<tr>
								<td colspan="6" class='encabezado2Horizontal'>FILTRAR CONTENIDOS</td>
							</tr>
							<tr>
								<td class='encabezado2VerticalInvertido'>GRUPO</td>
								<td colspan="5">
									<select name="Grupo" style="width:100%;" onChange="document.FORMA.submit();">
										<option value="">&nbsp; </option>
											<?
											$cons="Select Grupo from Consumo.Grupos where Compania='$Compania[0]' and AlmacenPpal='$AlmacenPpal' and Anio=$Anio Order By Grupo";
											$res=ExQuery($cons);
											while($fila=ExFetch($res)){
												echo "<option value='$fila[0]'>$fila[0]</option>";
											}
											?>
									</select>
								</td>
							</tr>
							<tr>
								<td class='encabezado2VerticalInvertido'>BODEGA</td>
								<td>
									<select name="Bodega"   onChange="document.FORMA.submit();"><option>
										<?
											$cons="Select Bodega from Consumo.Bodegas where Compania='$Compania[0]' and AlmacenPpal='$AlmacenPpal'";
											$res=ExQuery($cons);
											while($fila=ExFetch($res)){
												echo "<option value='$fila[0]'>$fila[0]</option>";
											}
										?>
									</select>
								</td>
								<td class='encabezado2VerticalInvertido'>ESTANTE</td><td>
									<select name="Estante"   onChange="document.FORMA.submit();"><option>
										<?
											$cons="Select Estante from Consumo.CodProductos where Compania='$Compania[0]' and Estante!='' and AlmacenPpal='$AlmacenPpal' Group By Estante ";
											$res=ExQuery($cons);
											while($fila=ExFetch($res)){
												echo "<option value='$fila[0]'>$fila[0]</option>";
											}
										?>
									</select>

								</td>
								<td class='encabezado2VerticalInvertido'>NIVEL</td>
								<td>
									<select name="Nivel" onChange="document.FORMA.submit();"><option>
										<?
											$cons="Select Nivel from Consumo.CodProductos where Compania='$Compania[0]' and Nivel!='' and AlmacenPpal='$AlmacenPpal' Group By Nivel";
											$res=ExQuery($cons);
											while($fila=ExFetch($res)){
												echo "<option value='$fila[0]'>$fila[0]</option>";
											}
										?>
									</select>
									<input type="hidden" name="Anio" value="<? echo $Anio?>">
									<input type="hidden" name="Mes" value="<? echo $Mes?>">
									<input type="hidden" name="diacorte" value="<? echo $diacorte?>">
								</td>
							</tr>
							<tr>
								<td colspan="6" style="text-align:center;">
									<iframe frameborder="0" style="width:800px;height:400px;" id="ListaProductos" name="ListaProductos" src="ProductosConteo.php?diacorte=<? echo $diacorte?>&DatNameSID=<? echo $DatNameSID?>&Anio=<? echo $Anio?>&Mes=<? echo $Mes?>&AlmacenPpal=<? echo $AlmacenPpal?>&NomInventario=<? echo $NomInventario?>&Editar=<? echo $Editar?>" ></iframe>
								</td>
							</tr>
					</table>
				</div>			
			</body>
		</html>
