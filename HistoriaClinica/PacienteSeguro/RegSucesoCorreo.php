		<?	
			if($DatNameSID){session_name("$DatNameSID");}
			session_start();
			include("Funciones.php");
			include_once("General/Configuracion/Configuracion.php");
			$ND = getdate();
			if($ND[mon]<10){$C1="0";}if($ND[mday]<10){$C2="0";}
			if(!$FechaIni){$FechaIni="$ND[year]-$C1$ND[mon]-01";}
			if(!$FechaFin){$FechaFin="$ND[year]-$C1$ND[mon]-$C2$ND[mday]";}
			
		?>	
		
		<html>
				<head>
					<?php echo $codificacionMentor; ?>
					<?php echo $autorMentor; ?>
					<?php echo $titleMentor; ?>
					<?php echo $iconMentor; ?>
					<?php echo $shortcutIconMentor; ?>
					<link rel="stylesheet" type="text/css" href="../../General/Estilos/estilos.css">				
					<script language='javascript' src="/calendario/popcalendar.js"></script>
				</head>

				<body <?php echo $backgroundBodyMentor; ?>>
					<?php
					$rutaarchivo[0] = "HISTORIA CL&Iacute;NICA";
					$rutaarchivo[1] = "PACIENTE SEGURO";
					$rutaarchivo[2] = "REPORTE DE SUCESOS";
					$rutaarchivo[3] = "TRAER SUCESO";
					mostrarRutaNavegacionEstatica($rutaarchivo);
				?>
					<div <?php echo $alignDiv2Mentor; ?> class="div2">
						<form name="FORMA" method="post">
						<table class="tabla2"   <?php echo $borderTabla2Mentor; echo $bordercolorTabla2Mentor; echo $cellspacingTabla2Mentor; echo $cellpaddingTabla2Mentor; ?>> 
							<tr>
								<td class="encabezado2Horizontal" colspan="5" >CORREOS RECIBIDOS</td>
							</tr>
							<tr>
								<td class="encabezado2HorizontalInvertido">DESDE</td>
								<td class="encabezado2HorizontalInvertido">
									<input type="text" name="FechaIni" value="<? echo $FechaIni?>" readonly  onClick="popUpCalendar(this, FORMA.FechaIni, 'yyyy-mm-dd')" style="width:90">
								</td>
								<td class="encabezado2HorizontalInvertido">HASTA</td>
								<td class="encabezado2HorizontalInvertido">
									<input type="text" name="FechaFin" value="<? echo $FechaFin?>" readonly  onClick="popUpCalendar(this, FORMA.FechaFin, 'yyyy-mm-dd')" style="width:90">
								</td>
								<td><input class="boton2Envio" type="submit" name="Ver" value="Ver"></td>
							</tr>
							<tr align="center"><td colspan="5"><input type="button"  class="boton2Envio" value="Regresar" onClick="location.href='RepHallasgos.php?DatNameSID=<? echo $DatNameSID?>'"></tr>
						</table>    
						<br><?
						if($Ver)
						{
							$cons="select id,mensaje,asunto,fechacrea,usucrea from central.correos where compania='$Compania[0]' and usurecive='$usuario[1]' and id not in
							(select correo from pacienteseguro.sucesos where compania='$Compania[0]' and correo is not null) and fechacrea>='$FechaIni 00:00:00' 
							and fechacrea<='$FechaFin 23:59:59' and estado='AC' order by fechacrea desc";
							//echo $cons;
							$res=ExQuery($cons);?>
							<table class="tabla2"   <?php echo $borderTabla2Mentor; echo $bordercolorTabla2Mentor; echo $cellspacingTabla2Mentor; echo $cellpaddingTabla2Mentor; ?>> 
								<tr>	
									<td class="encabezado2Horizontal">FECHA RECEPCI&Oacute;N</td>
									<td class="encabezado2Horizontal">ASUNTO</td>
									<td class="encabezado2Horizontal" >MENSAJE</td>
									<td class="encabezado2Horizontal"> &nbsp;</td>
								</tr>
							<?	while($fila=ExFetch($res))
								{
									echo "<tr><td>$fila[3]</td><td>$fila[2]</td><td>$fila[1]</td>";?>
									<td style="cursor:hand" title="Agregar Como Susceso" onClick="if(confirm('¿Esta seguro de agregar este correo como suceso?'))
									{location.href='RepHallasgos.php?DatNameSID=<? echo $DatNameSID?>&IdCorreo=<? echo $fila[0]?>&UsuCorreo=<? echo $fila[4]?>&FecCreaCorreo=<? echo $fila[3]?>';}">
										<img src="/Imgs/b_check.png">
									</td></tr>
							<? 	}?>
							</table><?	
						}?>    	
						<input type="hidden" name="IdSuceso"  value="<? echo $IdSuceso?>">
						<input type="hidden" name="DatNameSID" value="<? echo $DatNameSID?>">
						</form>
					</div>
				</body>
		</html>