		<?php
			if($DatNameSID){session_name("$DatNameSID");}
			session_start();
			include("Funciones.php");
			include_once("General/Configuracion/Configuracion.php");
			$ND=getdate();
			if($Eliminar)
			{
				$cons="delete from salud.formatosegreso where compania='$Compania[0]' and ambito='$Ambito' and tipoformato='$TipoFormato' and formato='$Formato'";
				//echo $cons;
				$res=ExQuery($cons);
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
				$rutaarchivo[0] = "HISTORIA CL&Iacute;NICA";
				$rutaarchivo[1] = "CONFIGURACI&Oacute;N";
				$rutaarchivo[2] = "FORMATOS EGRESO POR PROCESO";
				mostrarRutaNavegacionEstatica($rutaarchivo);
					
			?>	
			<div <?php echo $alignDiv2Mentor; ?> class="div2">
				<form name="FORMA" method="post" onSubmit="return validar()">  
					<table class="tabla2"  <?php echo $borderTabla2Mentor; echo $bordercolorTabla2Mentor; echo $cellspacingTabla2Mentor; echo $cellpaddingTabla2Mentor; ?>> 
						<tr>
							<td colspan="4" class="encabezado2Horizontal" >FORMATOS EGRESO POR PROCESO</td>
						</tr>
							<?	$cons="select ambito from salud.ambitos where compania='$Compania[0]' and ambito!='Sin Ambito' 
							and ambito in (select ambito from salud.formatosegreso where compania='$Compania[0]') order by ambito";
							$res=ExQuery($cons);
							while($fila=ExFetch($res))
							{?>
						<tr>
							<td colspan="4" class="encabezadoGrisaceo" style="text-align:center;"><? echo strtoupper($fila[0])?></td>
						</tr>
						<tr>
							<td class="encabezado2HorizontalInvertido">TIPO DE FORMATO</td>
							<td class="encabezado2HorizontalInvertido">FORMATO</td>
							<td  class="encabezado2HorizontalInvertido" colspan="2">&nbsp;</td>
						</tr>
							<? $cons2="select tipoformato,formato from salud.formatosegreso where compania='$Compania[0]' and ambito='$fila[0]' order by tipoformato,formato";
							$res2=ExQuery($cons2);
							while($fila2=ExFetch($res2))
							{?>
								<tr>
									<td><? echo $fila2[0]?></td><td><? echo $fila2[1]?></td>                
									<td>
										<img src="/Imgs/b_drop.png" title="Eliminar" style="cursor:hand" 
										onClick="if(confirm('¿Esta seguro de eliminar este registro?')){location.href='FormatosxEgrxAmb.php?DatNameSID=<? echo $DatNameSID?>&Eliminar=1&TipoFormato=<? echo $fila2[0]?>&Formato=<? echo $fila2[1]?>&Ambito=<? echo $fila[0]?>';}">
									</td>
								</tr>
						<?	}
						}?> 
						<tr align="center">
							<td colspan="4">
								<input type="button" class="boton2Envio" value="Nuevo" onClick="location.href='NewFormatoxEgrxAmb.php?DatNameSID=<? echo $DatNameSID?>';">
							</td>
						</tr>       
					</table>
				</form>
			</div>	
		</body>
	</html>