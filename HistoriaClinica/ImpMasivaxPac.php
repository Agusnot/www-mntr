		<?	
			if($DatNameSID){session_name("$DatNameSID");}
			session_start();
			include("Funciones.php");
			include_once("General/Configuracion/Configuracion.php");
			$ND=getdate();
			if($ND[mon]<10){$C1="0";}
			if($ND[mday]<10){$C2="0";}
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
				<link rel="stylesheet" type="text/css" href="../General/Estilos/estilos.css">
				<script language="javascript" src="/Funciones.js"></script>
				<script language='javascript' src="/calendario/popcalendar.js"></script>
				<script language="javascript">
					function Validar()
					{
						if(document.FORMA.FechaIni.value>document.FORMA.FechaFin.value){alert("La fecha inicial debe ser menor o igual a la fecha final!!!"); return false;}
						if(document.FORMA.Cedula.value==""){alert("Debe digitrar la cedula del paciente"); return false;}
					}
					function ValidaDocumento(Objeto){
						frames.FrameOpener.location.href="/Admision/Agenda/ValidaDocumentoAgendaInforme.php?DatNameSID=<? echo $DatNameSID?>&Cedula="+Objeto.value;
						document.getElementById('FrameOpener').style.position='absolute';
						document.getElementById('FrameOpener').style.top='150px';
						document.getElementById('FrameOpener').style.left='325px';
						document.getElementById('FrameOpener').style.display='';
						document.getElementById('FrameOpener').style.width='400';
						document.getElementById('FrameOpener').style.height='250';
					}
				</script>
			</head>

				<body <?php echo $backgroundBodyMentor; ?>>
					<?php
						$rutaarchivo[0] = "HISTORIA CL&Iacute;NICA";							
						$rutaarchivo[1] = "UTILIDADES";
						$rutaarchivo[2] = "IMPRESI&Oacute;N MASIVA HC";
						$rutaarchivo[3] = "IMPRESI&Oacute;N POR PACIENTE";
						mostrarRutaNavegacionEstatica($rutaarchivo);
					?>
					
					<div <?php echo $alignDiv2Mentor; ?> class="div2">
						<form name="FORMA" method="post" onSubmit="return Validar()">
						<table class="tabla2"  <?php echo $borderTabla2Mentor; echo $bordercolorTabla2Mentor; echo $cellspacingTabla2Mentor; echo $cellpaddingTabla2Mentor; ?> >	       
							<tr>
								<td class="encabezado2Horizontal" colspan="7"> IMPRESI&Oacute;N POR PACIENTE </td>
							</tr>
							<tr>
								<td  class="encabezado2VerticalInvertido">DESDE</td>
								<td>
									<input type="text" name="FechaIni" value="<? echo $FechaIni?>" readonly onClick="popUpCalendar(this, FORMA.FechaIni, 'yyyy-mm-dd')" style="width:80">
								</td>
								<td class="encabezado2VerticalInvertido">HASTA</td>
								<td>
								   <input type="text" name="FechaFin" value="<? echo $FechaFin?>" readonly onClick="popUpCalendar(this, FORMA.FechaFin, 'yyyy-mm-dd')" style="width:80">
								</td>
								<td class="encabezado2VerticalInvertido">IDENTIFICACI&Oacute;N</td>
								<td><input type="text" name="Cedula" value="<? echo $Cedula?>" onFocus="ValidaDocumento(this)"	onKeyDown="xLetra(this)" onKeyUp="ValidaDocumento(this);xLetra(this);" onKeyPress="ValidaDocumento(this);xLetra(this);" style="width:90">
								</td>
								<td><input type="submit" name="Ver" value="Ver" class="boton2Envio"></td>
							</tr>
						</table>        
						<br>
						<?
						if($Ver)
						{?>
							<table class="tabla2"  <?php echo $borderTabla2Mentor; echo $bordercolorTabla2Mentor; echo $cellspacingTabla2Mentor; echo $cellpaddingTabla2Mentor; ?> >	       
						<? $cons="select numservicio,tiposervicio,fechaing,fechaegr,estado from salud.servicios 
							where servicios.compania='$Compania[0]' and servicios.cedula='$Cedula' 
							and (fechaing<='$FechaFin 23:59:59' and (fechaegr>='$FechaIni 00:00:00' or fechaegr is null))
							order by numservicio";
							$res=ExQuery($cons);
							//echo $cons;
							
							if(ExNumRows($res)>0)
							{?>
								<tr>	
									<td class="encabezado2Horizontal">NO. SERVICIO</td>
									<td class="encabezado2Horizontal">PROCESO</td>
									<td class="encabezado2Horizontal">FECHA INICIAL</td>
									<td class="encabezado2Horizontal">FECHA FINAL</td>
									<td class="encabezado2Horizontal">ESTADO</td>
								</tr>	
							<?	while($fila=ExFetch($res))
								{
									if($fila[4]=="AC"){$Estado="Activo";}else{$Estado="Inactivo";}?>
									<tr style="cursor:hand" title="Ver Formatos"  onMouseOut="this.bgColor=''" onMouseOver="this.bgColor='#33CCFF'"
									onClick="location.href='VerFormatosxPac.php?DatNameSID=<? echo $DatNameSID?>&NumServ=<? echo $fila[0]?>&FechaIni=<? echo $FechaIni?>&FechaFin=<? echo $FechaFin?>&CedPac=<? echo $Cedula?>'">
										<td><? echo $fila[0]?></td><td><? echo $fila[1]?></td><td><? echo $fila[2]?></td><td><? echo $fila[3]?></td><td><? echo $Estado?></td>
									</tr>
							<?	}
							}
							else{?>
								<tr>
									<td class="mensaje1" colspan="10">No hay elementos para ser liquidados durante este periodo</td>
								</tr>	
						<?	}?>
							</table><?
						}
						?>
						<input type="hidden" name="DatNameSID" value="<? echo $DatNameSID?>">
						</form>
						<iframe id="FrameOpener" name="FrameOpener" style="display:none" frameborder="0" height="1" style="border:#e5e5e5 ridge">
					</div>	
				</body>
		</html>