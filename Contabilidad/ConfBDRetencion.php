		<?
			if($DatNameSID){session_name("$DatNameSID");}
			session_start();
			include("Funciones.php");
			include_once("General/Configuracion/Configuracion.php");
			$ND=getdate();
			if($Eliminar)
			{
				$cons = "Delete from Contabilidad.BasesRetencion where Concepto = '$Concepto' and TipoRetencion = '$TipoRetencion' and Anio = '$Anio'
				and Compania = '$Compania[0]'";
				$res = ExQuery($cons);ExError();
			}
			if($Nuevo)
			{
				?><script language="javascript">location.href="NuevoConfBDR.php?DatNameSID=<? echo $DatNameSID?>&Anio=<? echo $Anio ?>"</script><?
			}
			if(!$Anio){$Anio=$ND[year];}
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
				$rutaarchivo[2] = "CUENTAS CONTABLES";
				$rutaarchivo[3] = "BASES DE RETENCION";										
				mostrarRutaNavegacionEstatica($rutaarchivo);
			?>
			<div <?php echo $alignDiv3Mentor; ?> class="div3">	
					
				<table width="100%" class="tabla3"   <?php echo $borderTabla3Mentor; echo $bordercolorTabla3Mentor; echo $cellspacingTabla3Mentor; echo $cellpaddingTabla3Mentor; ?>>
					<tr>
						<td colspan="4">
							<form name="FORMA" method="post">
								A&Ntilde;O: 
									<select name="Anio" onChange="FORMA.submit()">
										<?
											$cons="Select Anio from Central.Anios where Compania='$Compania[0]' Order By Anio";
											$res=ExQuery($cons);
											while($fila=ExFetch($res))
											{
												if($Anio==$fila[0]){echo "<option selected value='$fila[0]'>$fila[0]</option>";}
												else{echo "<option value='$fila[0]'>$fila[0]</option>";}
											}
										?>
									</select>
						</td>
						<?
						if($Anio){
						?>
						<td colspan="4" style="text-align:right; padding-right:15px;">
							<input type="submit" name="Nuevo" class="boton2Envio" value="Nuevo Registro" />
						</td>
					</tr>
					<tr>
						<td class='encabezado2Horizontal'>CONCEPTO</td>
						<td class='encabezado2Horizontal'>PORCENTAJE</td>
						<td class='encabezado2Horizontal'>BASE</td>
						<td class='encabezado2Horizontal'>CUENTA</td>
						<td class='encabezado2Horizontal'>MONTO M&Iacute;NIMO</td>
						<td class='encabezado2Horizontal'>IVA</td>
						<td class='encabezado2Horizontal' colspan="2">&nbsp;</td>
					</tr>
						<?
							$cons = "Select TipoRetencion, Count(*) from Contabilidad.BasesRetencion where Compania = '$Compania[0]' and Anio = '$Anio' group by TipoRetencion";
							$res = ExQuery($cons);
							while($fila = ExFetch($res))
							{
								echo "<tr><td colspan='8' class='encabezado3HorizontalInvertido'>$fila[0] ($fila[1])</td></tr>";
								$cons1 = "Select Concepto,Porcentaje,Base,Cuenta,MontoMinimo,Iva from Contabilidad.BasesRetencion where Compania = '$Compania[0]' and Anio = '$Anio'
								and TipoRetencion = '$fila[0]'";
								$res1 = ExQuery($cons1);
								while($fila1 = ExFetch($res1))
								{
									?><tr onMouseOver="this.bgColor='#AAD4FF'" onMouseOut="this.bgColor='#FFFFFF'" align="right"><?
									echo "<td align='left'>$fila1[0]</td><td>".number_format($fila1[1],2)."</td><td>$fila1[2]</td><td>$fila1[3]</td><td>".number_format($fila1[4],2)."</td><td>".number_format($fila1[5],2)."</td>";
									?><td width="16px">
									<a href="NuevoConfBDR.php?DatNameSID=<? echo $DatNameSID?>&Editar=1&TipoRetencion=<? echo $fila[0]?>&Concepto=<? echo $fila1[0]?>&Anio=<? echo $Anio?> ">
									<img border=0 src="/Imgs/b_edit.png"></a></td>
									<td width="16px"><a href="#" onClick="if(confirm('Desea eliminar el registro?'))
									{location.href='ConfBDRetencion.php?DatNameSID=<? echo $DatNameSID?>&Eliminar=1&TipoRetencion=<? echo $fila[0]?>&Concepto=<? echo $fila1[0]?>&Anio=<? echo $Anio?> ';}">
										<img border="0" src="/Imgs/b_drop.png"/></a>
										</td></tr><?
								}
							}
					?></table> <?
				} ?>
				<input type="hidden" name="DatNameSID" value="<? echo $DatNameSID?>">
			</form>
			</body>