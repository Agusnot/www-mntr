		<?
			if($DatNameSID){session_name("$DatNameSID");}
			session_start();
			include("Funciones.php");
			include_once("General/Configuracion/Configuracion.php");
			
			if($Eliminar){
				$cons="Select * from Contabilidad.Movimiento where Comprobante='$Comprobante' and Compania='$Compania[0]'";
				$res=ExQuery($cons);
				if(ExNumRows($res)>0)
				{
					echo "<br><em><font style='color:red;font-weight:bold'>Comprobante tiene movimiento, no es posible eliminar!!!<br><br></font>";		
				}
				else
				{
					$cons="Delete from Contabilidad.Comprobantes where Comprobante='$Comprobante' and Compania='$Compania[0]'";
					$res=ExQuery($cons);
					echo ExError($res);	
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
		</head>
		<body <?php echo $backgroundBodyMentor; ?>>	
			<?php
				$rutaarchivo[0] = "CONTABILIDAD";
				$rutaarchivo[1] = "CONFIGURACION";
				$rutaarchivo[2] = "COMPROBANTES";
				$rutaarchivo[3] = "COMPROBANTES";										
				mostrarRutaNavegacionEstatica($rutaarchivo);
			?>
			<div <?php echo $alignDiv3Mentor; ?> class="div3">
		
					<input type="button" value="Nuevo Registro" class="boton2Envio" style="margin-top:15px; margin-bottom:15px;" onClick="location.href='NuevoConfComprobante.php?DatNameSID=<? echo $DatNameSID?>'"/>
					<table class="tabla3"   <?php echo $borderTabla3Mentor; echo $bordercolorTabla3Mentor; echo $cellspacingTabla3Mentor; echo $cellpaddingTabla3Mentor; ?>>
						<tr>
							<td class='encabezado2Horizontal'>COMPROBANTE</td>
							<td class='encabezado2Horizontal'>RETENCI&Oacute;N</td>
							<td class='encabezado2Horizontal'>CUENTA CERO</td>
							<td class='encabezado2Horizontal'>CIERRE</td>
							<td class='encabezado2Horizontal'>N&Uacute;MERO</td>
							<td class='encabezado2Horizontal'>FORMATO</td>
							<td class='encabezado2Horizontal'>PRESUPUESTO</td>
							<td class='encabezado2Horizontal' colspan="2">&nbsp;</td>
						</tr>
						<?
							$cons1 = "Select TipoComprobant,count(*) from Contabilidad.Comprobantes where Compania='$Compania[0]' Group by TipoComprobant";
							$res1 = ExQuery($cons1);echo ExError($res1);
							while ($fila1 = ExFetch($res1))
							{
								echo "<tr>";
									echo "<td colspan=9 class='encabezado2HorizontalInvertido' style='background-color:E5E5E5'>$fila1[0] ($fila1[1])</td>";
								echo "</tr>";
								$cons2 = "Select Comprobante,Retencion,CruceCtaCero,Cierre,NumeroInicial,Formato,CompPresupuesto,CompPresupuestoAdc from Contabilidad.Comprobantes where Compania='$Compania[0]' and TipoComprobant = '$fila1[0]'";
								$res2 = ExQuery($cons2);echo ExError($res2);
								while ($fila2 = ExFetch($res2))
								{
									if(!$fila2[4]){$fila2[4]="&nbsp;";}?>
									<tr onMouseOver="this.bgColor='#AAD4FF'" onMouseOut="this.bgColor='#FFFFFF'">
									<? echo "
									<td>$fila2[0]</td>
									<td>$fila2[1]</td>
									<td>&nbsp;$fila2[2]</td>
									<td>&nbsp;$fila2[3]</td>
									<td>&nbsp;$fila2[4]</td>
									<td>&nbsp;$fila2[5]</td>
									<td>&nbsp;$fila2[6] - $fila2[7]</td>";
									echo "
									<td><a href='NuevoConfComprobante.php?DatNameSID=$DatNameSID&Editar=1&Comprobante=$fila2[0]'><img border=0 src='/Imgs/b_edit.png'></a></td>";?>
									<td><a href="#" onClick="if(confirm('Desea eliminar el registro?')){location.href='ConfComprobantes.php?DatNameSID=<? echo $DatNameSID?>&Eliminar=1&Comprobante=<?echo $fila2[0]?>';}">
									<img border="0" src="/Imgs/b_drop.png"/></a>
									</td></tr>		
								<?}
							}
											
						?>
				</table><br />
				

</body>