			<?
				if($DatNameSID){session_name("$DatNameSID");}
				session_start();
				include ("Funciones.php");
				include_once("General/Configuracion/Configuracion.php");
				if(!$Anio){$ND=getdate();$Anio = $ND[year];}
				if($Eliminar)
				{
					$cons = "Delete from Central.EstructuraXCC where Compania = '$Compania[0]' and Anio = '$Anio' and Nivel = '$Nivel'";
					$res = ExQuery($cons);
				}
				if($Nuevo){?><script language="javascript">location.href="NuevoConfEstructuracc.php?DatNameSID=<? echo $DatNameSID?>&Anio=<? echo $Anio?>";</script><? }
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
				$rutaarchivo[2] = "ESTRUCTURA CENTROS COSTO";			
				mostrarRutaNavegacionEstatica($rutaarchivo);
			?>
			<div <?php echo $alignDiv1Mentor; ?> class="div1">
					<form name="FORMA" method="post">
						<table border="0">
							<tr>
							  <td>A&Ntilde;O</td>
							  <td>
									<select name="Anio" onChange="FORMA.submit()" />
										<?
											$cons = "Select Anio from Central.Anios where Compania = '$Compania[0]' order by Anio desc";
											$res = ExQuery($cons);
											while($fila = ExFetch($res))
											{
												if($Anio==$fila[0]){echo "<option selected value='$fila[0]'>$fila[0]</option>";}
												else {echo "<option value='$fila[0]'>$fila[0]</option>";}
											}
										?>
									</select>
								</td>
							</tr>
						</table>
							
						<input type="submit" value="Nuevo" class="boton2Envio" style="margin-top:25px;margin-bottom: 25px;" name = "Nuevo"/>
						
						<? 
						if($Anio)
						{ ?>
						<table class="tabla1"  width="300px" style="text-align:center;"  <?php echo $borderTabla1Mentor; echo $bordercolorTabla1Mentor; echo $cellspacingTabla1Mentor; echo $cellpaddingTabla1Mentor; ?>>
							<tr>
								<td class='encabezado1Horizontal' >NIVEL</td>
								<td class='encabezado1Horizontal'>D&Iacute;GITOS</td>
								<td class='encabezado1Horizontal'>&nbsp;</td>
								<td class='encabezado1Horizontal'>&nbsp;</td>
							</tr>
							<?
							$cons = "Select Nivel,Digitos from Central.EstructuraxCC where compania='$Compania[0]' and Anio='$Anio' Order By Nivel";
							$res = ExQuery($cons);
							$NumFilas = ExNumRows($res);
							$Ult = 0;
							while ($fila = ExFetch($res))
							{
								$Ult++;
								?><tr onMouseOver="this.bgColor='#AAD4FF'" onMouseOut="this.bgColor='#FFFFFF'"><?
								echo "<td>$fila[0]</td><td>$fila[1]</td>";
								?><td width="16px">
								   <a href="NuevoConfEstructuracc.php?DatNameSID=<? echo $DatNameSID?>&Editar=1&Anio=<? echo $Anio?>&Nivel=<? echo $fila[0]?>">
								   <img border=0 src="/Imgs/b_edit.png"></a></td><?
								if($Ult == $NumFilas)
								{
									?><td width="16px"><a href="#" onClick="if(confirm('Desea eliminar el registro?'))
									{location.href='ConfEstructuracc.php?DatNameSID=<? echo $DatNameSID?>&Eliminar=1&Anio=<? echo $Anio?>&Nivel=<? echo $fila[0]?>';}">
									<img border="0" src="/Imgs/b_drop.png"/></a></td></tr><?
								}
								else
								{
									echo "<td>&nbsp;</td>";
								}
								echo "</tr>";
							}
							?>
						</table>
						
						<? } ?>
						<input type="hidden" name="DatNameSID" value="<? echo $DatNameSID?>">
					</form>
				</body>