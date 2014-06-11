		<?	
			if($DatNameSID){session_name("$DatNameSID");}
			session_start();
			include("Funciones.php");	
			include_once("General/Configuracion/Configuracion.php");
			$ND = getdate();
			$cons="select usucomite from pacienteseguro.comite where compania='$Compania[0]' and usucomite='$usuario[1]'";
			$res=ExQuery($cons);
			if(ExNumRows($res)>0){$BanReg=1;}
			
			if($IdCorreo)
			{
				$cons="insert into pacienteseguro.sucesos (compania,usuario,fechacrea,correo) values('$Compania[0]','$UsuCorreo','$FecCreaCorreo',$IdCorreo)";
				$res=ExQuery($cons);
			}
			
			if($IdSuceso){		
				if($Accion!="Descartar"){?>
					<script language="javascript">
						location.href="RegFormatoPacSeg.php?DatNameSID=<? echo $DatNameSID?>&IdSuceso=<? echo $IdSuceso?>&Accion=<? echo $Accion?>";
					</script>
			<?	}
			}
			
		?>

		
		<html>
			<head>
				<?php echo $codificacionMentor; ?>
				<?php echo $autorMentor; ?>
				<?php echo $titleMentor; ?>
				<?php echo $iconMentor; ?>
				<?php echo $shortcutIconMentor; ?>
				<link rel="stylesheet" type="text/css" href="../../General/Estilos/estilos.css">
				
				<script language="javascript" src="/Funciones.js"></script>
				<script language='javascript' src="/calendario/popcalendar.js"></script>
				<script language="javascript">
					function VerDatosFormato(e,Num)
					{
						y = e.clientY; 
						st = document.body.scrollTop;
						frames.FrameOpener.location.href="/HistoriaClinica/PacienteSeguro/VerDatosFormato.php?DatNameSID=<? echo $DatNameSID?>&NumRep="+Num;
						document.getElementById('FrameOpener').style.position='absolute';
						document.getElementById('FrameOpener').style.top=y-20;
						document.getElementById('FrameOpener').style.left='10%';
						document.getElementById('FrameOpener').style.display='';
						document.getElementById('FrameOpener').style.width='800';
						document.getElementById('FrameOpener').style.height='550';
					}
					function Enviar(e,Num)
					{
						y = e.clientY; 
						st = document.body.scrollTop;
						frames.FrameOpener.location.href="/HistoriaClinica/PacienteSeguro/PasaraPacienteSeg.php?DatNameSID=<? echo $DatNameSID?>&NumRep="+Num;
						document.getElementById('FrameOpener').style.position='absolute';
						document.getElementById('FrameOpener').style.top=y-40+st;
						document.getElementById('FrameOpener').style.left='10%';
						document.getElementById('FrameOpener').style.display='';
						document.getElementById('FrameOpener').style.width='730';
						document.getElementById('FrameOpener').style.height='390';
					}
				</script>
			</head>

		<body <?php echo $backgroundBodyMentor; ?>>
				<?php
					$rutaarchivo[0] = "HISTORIA CL&Iacute;NICA";
					$rutaarchivo[1] = "PACIENTE SEGURO";
					$rutaarchivo[2] = "REPORTE DE SUCESOS";
					mostrarRutaNavegacionEstatica($rutaarchivo);
				?>
				<div <?php echo $alignDiv2Mentor; ?> class="div2">
					
					<form name="FORMA" method="post">
						<?
						$cons="SELECT fechacrea,nombre,sucesos.cedula,sucesos.cedula,ambito,pabellon ,tipoformato,formato,idhistoria,idsuceso,correo
						FROM pacienteseguro.sucesos,central.usuarios
						WHERE sucesos.compania='$Compania[0]'  and usuarios.usuario=sucesos.usuario and sucesos.estado='AC'
						and accion is null ORDER by fechacrea";
						$res=ExQuery($cons);	
						//echo $cons;
						//if($Ver){?>
							<table class="tabla2"   <?php echo $borderTabla2Mentor; echo $bordercolorTabla2Mentor; echo $cellspacingTabla2Mentor; echo $cellpaddingTabla2Mentor; ?>> 
								<tr>
									<td class="encabezado2Horizontal" colspan="11" >REPORTE DE SUCESOS</td>
								</tr>
								<tr>
									<td class='encabezado2HorizontalInvertido'>FECHA REPORTE </td>
									<td class='encabezado2HorizontalInvertido'>REPORTA</td>
									<td class='encabezado2HorizontalInvertido'>PACIENTE</td>
									<td class='encabezado2HorizontalInvertido'>IDENTIFICACI&Oacute;N</td>
									<td class='encabezado2HorizontalInvertido'>PROCESO</td>
									<td class='encabezado2HorizontalInvertido'>SERVICIO</td>
									<td class='encabezado2HorizontalInvertido'>FORMATO</td>
									<td class='encabezado2HorizontalInvertido'> &nbsp; </td>            
								</tr><?
								while($fila=ExFetch($res)){?>
									<tr align='center' onMouseOver="this.bgColor='#AAD4FF'" onMouseOut="this.bgColor=''">
										<td><? echo $fila[0]?></td><td><? echo $fila[1]?></td>
								<? 	if($fila[10]){?>
										<td style="cursor:hand" title="Ver Correo" colspan="5"
										onClick="open('/Correo/VerCorroInd.php?DatNameSID=<? echo $DatNameSID?>&IdCorreo=<? echo $fila[10]?>','','')">
											Suceso No Ligado A Un Paciente
										</td>
								<?	}
									else{
										$cons2="select (primape || ' ' || segape || ' ' || primnom || ' ' || segnom) from central.terceros where compania='$Compania[0]' and 
										identificacion='$fila[3]'";
										$res2=ExQuery($cons2); $fila2=ExFetch($res2);?>
										<td style="cursor:hand" title="Abrir Historia"
										onClick="location.href='/HistoriaClinica/ResultBuscarHC.php?DatNameSID=<? echo $DatNameSID?>&Cedula=<? echo $fila[3]?>&Buscar=1'">
										<? echo $fila2[0]?></td>
										<td style="cursor:hand" title="Abrir Historia"
										onClick="location.href='/HistoriaClinica/ResultBuscarHC.php?DatNameSID=<? echo $DatNameSID?>&Cedula=<? echo $fila[3]?>&Buscar=1'">
										<? echo $fila[3]?></td>
										<td><? echo "$fila[4]&nbsp;"?></td><td><? echo "$fila[5]&nbsp;"?></td>
										<td style="cursor:hand" title="Ver Formato" onClick="open('/HistoriaClinica/ImpHistoria.php?DatNameSID=<? echo $DatNameSID?>&Formato=<? echo $fila[7]?>&TipoFormato=<? echo $fila[6]?>&IdHistoria=<? echo $fila[8]?>&CedImpMasv=<? echo $fila[2]?>&Opc=1','','')">
										<? echo $fila[7]?></td>  
								<?	}?>              
										<td>
										<?	if($BanReg==1){?>
												<button title="Diligenciar" onClick="location.href='RepHallasgos.php?DatNameSID=<? echo $DatNameSID?>&IdSuceso=<? echo $fila[9]?>'">
													<img src="/Imgs/b_check.png"></button>
										<?	}
											else{?>
												<button onClick="alert('Usted no ha sido configurado como un usuario perteneciente al comite de Paciente Seguro!!!')">
													<img src="/Imgs/b_check.png"></button>
										<?	}?>
										</td>
									</tr><?
								}
								if($BanReg==1){?>
									<tr align="center">
										<td colspan="8">
											<input type="button" class="boton2Envio" value="Traer suceso desde correo" onClick="location.href='RegSucesoCorreo.php?DatNameSID=<? echo $DatNameSID?>'">
										</td>
									</tr>
							<?	}?>
							</table><?
						//}?>
						<input type="hidden" name="IdSuceso"  value="">
						<input type="hidden" name="Guardar" value=""/>
						<input type="hidden" name="IdCorreo" value="">
						<input type="hidden" name="DatNameSID" value="<? echo $DatNameSID?>">
					</form>
					<iframe id="FrameOpener" name="FrameOpener" style="display:none" frameborder="0" height="1" style="border:#e5e5e5 ridge" ></iframe>
				</div>	
		</body>
	</html>
