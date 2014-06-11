		<?
			if($DatNameSID){session_name("$DatNameSID");}
			session_start();
			include("Funciones.php");
			include_once("General/Configuracion/Configuracion.php");
			if($Eliminar){
				$cons = "Delete from Consumo.TmpMovimiento where IDTraslado='$IDTraslado' and TMPCOD='$TMPCOD'";
				$res = ExQuery($cons);
			}
			$cons00 = "Select AlmacenPpal from Consumo.AlmacenesPpales where Compania='$Compania[0]'";
			$res00 = ExQuery($cons00);
			$fila00 = ExFetch($res00);
			$AlmacenPpalD = $fila00[0];		
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
			<body onload="document.FORMA.Anio.value=parent.document.FORMA.Anio.value; document.FORMA.Fecha.value=parent.document.FORMA.Anio.value+'-'+parent.document.FORMA.Mes.value+'-'+parent.document.FORMA.Dia.value;">
				<form name="FORMA" method="post" action="CrearDetNuevoTraslado.php?">
					<input type="Hidden" name="Anio" value="<? echo $Anio?>" />
					<input type="Hidden" name="Fecha" value="<? echo $Fecha?>" />
					<input type="Hidden" name="AlmacenPpal" value="<? echo $AlmacenPpal?>" />
					<input type="Hidden" name="Comprobante" value="<? echo $Comprobante?>" />
					<input type="Hidden" name="TMPCOD" value="<? echo $TMPCOD?>" />
					<input type="Hidden" name="Tipo" value="<? echo $Tipo?>" />
					<input type="Hidden" name="Numero" value="<? echo $Numero?>" />
					<input type="hidden" name="AlmacenPpalD" value="<? echo $AlmacenPpalD?>" />
					<input type="hidden" name="DatNameSID" value="<? echo $DatNameSID?>" />
					
					<table  width="100%" class="tabla2" style="vertical:align:text-top;"  <?php echo $borderTabla2Mentor; echo $bordercolorTabla2Mentor; echo $cellspacingTabla2Mentor; echo $cellpaddingTabla2Mentor; ?>>
						<tr>
							<td colspan="4" class="encabezado2Horizontal">ORIGEN</td>
							<td colspan="3" class="encabezado2Horizontal">DESTINO</td>
						</tr>
							<tr>
								<td class="encabezado2HorizontalInvertido">ALMAC&Eacute;N</td>
								<td class="encabezado2HorizontalInvertido">C&Oacute;DIGO</td>
								<td class="encabezado2HorizontalInvertido">PRODUCTO</td>
								<td class="encabezado2HorizontalInvertido">CANTIDAD</td>
								<td class="encabezado2HorizontalInvertido">ALMAC&Eacute;N</td>
								<td class="encabezado2HorizontalInvertido">C&Oacute;DIGO</td>
								<td class="encabezado2HorizontalInvertido">PRODUCTO</td>
							<?
								$cons = "Select Codigo1,(NombreProd1 || ' ' || Presentacion || ' ' || UnidadMedida),TmpMovimiento.AutoId,Cantidad,IDTraslado 
										from Consumo.TmpMovimiento, Consumo.CodProductos 
										where TMPCOD = '$TMPCOD' and CodProductos.AutoId = TmpMovimiento.AutoId and TipoTraslado='O'
										and CodProductos.Anio=$Anio and CodProductos.AlmacenPpal='$AlmacenPpal' order by IDTraslado asc";
								$res = ExQuery($cons);
								while($fila = ExFetch($res))
								{
									echo "<tr><td>$AlmacenPpal</td><td>$fila[0]</td><td>$fila[1]</td><td>$fila[3]</td>";
									$cons1 = "Select Codigo1,(NombreProd1 || ' ' || Presentacion || ' ' || UnidadMedida),AlmacenPpalD,TmpMovimiento.AutoId
										from Consumo.TmpMovimiento, Consumo.CodProductos 
										where TMPCOD = '$TMPCOD' and CodProductos.AutoId = TmpMovimiento.AutoId and TipoTraslado='D' and IDTraslado='$fila[4]'";
									$res1 = ExQuery($cons1);
									$fila1 = ExFetch($res1);
									echo "<td>$fila1[2]</td><td>$fila1[0]</td><td>$fila1[1]</td>";
									?><td width="20px">
									<a style="cursor:hand" onClick="location.href='CrearDetNuevoTraslado.php?DatNameSID=<? echo $DatNameSID?>&Editar=1&Anio='+
									document.FORMA.Anio.value+'&Fecha='+document.FORMA.Fecha.value+'&TMPCOD=<? echo $TMPCOD?>&IDTraslado=<? echo $fila[4]?>
									&AlmacenPpal=<? echo $AlmacenPpal?>&Comprobante=<? echo $Comprobante?>&Tipo=<? echo $Tipo?>&AlmacenPpalD=<? echo $AlmacenPpalD?>'">
									<img border="0" src="/Imgs/b_edit.png" /></a>
									</td>
									<td width="20px">
									<a href="#" onClick="if(confirm('Desea eliminar el registro?'))
									{location.href='DetNuevoTraslado.php?DatNameSID=<? echo $DatNameSID?>&Eliminar=1&TMPCOD=<? echo $TMPCOD?>&IDTraslado=<? echo $fila[4]?>
									&AlmacenPpal=<? echo $AlmacenPpal?>&Comprobante=<? echo $Comprobante?>&Tipo=<? echo $Tipo?>&Numero=<? echo $Numero?>&Anio=<? echo $Anio?>';}">
									<img border="0" src="/Imgs/b_drop.png"/></a></td></tr><?
								}
							?>
						<tr>
							<td colspan="7" style="padding-top:10px;padding-bottom:10px;text-align:center;">
								<input type="submit" name="Nuevo" class="boton2Envio" value="Nuevo" onClick="frames.parent.document.FORMA.Guardar.disabled = true" />
							</td>
						</tr>
					</table>
					
					
				</form>
			</body>