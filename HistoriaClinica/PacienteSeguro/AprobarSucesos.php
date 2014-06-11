		<?	
			if($DatNameSID){session_name("$DatNameSID");}
			session_start();
			include("Funciones.php");
			include_once("General/Configuracion/Configuracion.php");
			$ND = getdate();
			
			$cons="select usucomite from pacienteseguro.comite where compania='$Compania[0]' and usucomite='$usuario[1]'";
			$res=ExQuery($cons);
			if(ExNumRows($res)>0){$BanReg=1;}
			
			if($ND[mon]<10){$cero1="0";}else{$cero1="";}
			if($ND[mday]<10){$cero2="0";}else{$cero2="";}
			if($Guardar){								
				//echo $cons;
			}
			//APROBAR
			$aprobacion=$_GET['aprobacion'];
			if($aprobacion!=NULL){
				$cons="update pacienteseguro.regprotocolo set aprobado=1,noaprobado=0 where idsuceso='$aprobacion'";
				$res=ExQuery($cons);
			}
			$rechazar=$_GET['rechazar'];
			$justificacion=$_GET['justificacion'];
			if($rechazar!=NULL){
				switch($justificacion){
					case NULL:
						echo'<script language="javascript">
						cad=null;
						while (cad==null){
							cad=prompt('."'Escriba la justificación'".','."''".');
						}
						location.href="AprobarSucesos.php?DatNameSID='."$DatNameSID".'&rechazar='."$rechazar".'&justificacion="+cad+"";
						</script>';
					break;
					default:
						$cons="update pacienteseguro.regprotocolo set aprobado=0,noaprobado=1,justificacion='$justificacion' where idsuceso='$rechazar'";
						/*echo $cons;*/
						$res=ExQuery($cons);
						
						$cons="update pacienteseguro.sucesos set accion=null where idsuceso='$rechazar'";
						//echo $cons;
						$res=ExQuery($cons);
					break;	
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
						<link rel="stylesheet" type="text/css" href="../../General/Estilos/estilos.css">
						<script language='javascript' src="/calendario/popcalendar.js"></script>
						<script language="javascript" src="/Funciones.js"></script>
						<script language="javascript">
							function Enviar(e,Num)
							{
								y = e.clientY; 
								st = document.body.scrollTop;
								frames.FrameOpener.location.href="/HistoriaClinica/PacienteSeguro/RegistAcciones.php?DatNameSID=<? echo $DatNameSID?>&IdSuceso="+Num;
								document.getElementById('FrameOpener').style.position='absolute';
								document.getElementById('FrameOpener').style.top=y-40+st;
								document.getElementById('FrameOpener').style.left='10%';
								document.getElementById('FrameOpener').style.display='';
								document.getElementById('FrameOpener').style.width='890';
								document.getElementById('FrameOpener').style.height='460';
							}
							function VerDatos(e,Num)
							{
								y = e.clientY; 
								x = e.clientX; 
								st = document.body.scrollTop;
								frames.FrameOpener.location.href="/HistoriaClinica/PacienteSeguro/VerFormatosAcciones.php?DatNameSID=<? echo $DatNameSID?>&NumRep="+Num;
								document.getElementById('FrameOpener').style.position='absolute';
								document.getElementById('FrameOpener').style.top=y-10+st;
								document.getElementById('FrameOpener').style.left=x;
								document.getElementById('FrameOpener').style.display='';
								document.getElementById('FrameOpener').style.width='240';
								document.getElementById('FrameOpener').style.height='110';
							}
						</script>
						<style type="text/css">
						<!--
						a:link {
							color: #E5E5E5;
						}
						a:visited {
							color: #E5E5E5;
						}
						a:hover {
							color: #000000;
						}
						a:active {
							color: #E5E5E5;
						}
						-->
						</style>
				</head>

			<body <?php echo $backgroundBodyMentor; ?>>
					<?php
						$rutaarchivo[0] = "HISTORIA CL&Iacute;NICA";
						$rutaarchivo[1] = "PACIENTE SEGURO";
						$rutaarchivo[2] = "APROBACI&Oacute;N DE SUCESOS";
						mostrarRutaNavegacionEstatica($rutaarchivo);
					?>
					<div <?php echo $alignDiv2Mentor; ?> class="div2">
						<form name="FORMA" method="post">
							<input type="hidden" name="FechaAct" id="FechaAct" value="<? echo "$ND[year]-$cero1$ND[mon]-$cero2$ND[mday]"?>">
							<?

							$cons="SELECT sucesos.fechacrea,nombre,sucesos.cedula,sucesos.cedula,sucesos.ambito,sucesos.pabellon,tipoformato,formato,idhistoria,sucesos.idsuceso,correo,accion
							FROM pacienteseguro.sucesos,central.usuarios,pacienteseguro.regprotocolo
							WHERE sucesos.compania='$Compania[0]' and usuarios.usuario=sucesos.usuario and sucesos.estado='AC' and sucesos.idsuceso=regprotocolo.idsuceso and regprotocolo.aprobado  is null and accion is not null 
							ORDER by fechacrea";

							/*$cons="SELECT fechacrea,nombre,sucesos.cedula,sucesos.cedula,ambito,pabellon,tipoformato,formato,idhistoria,idsuceso,correo,accion
							FROM pacienteseguro.sucesos,central.usuarios
							WHERE sucesos.compania='$Compania[0]'  and usuarios.usuario=sucesos.usuario and sucesos.estado='AC'
							and accion is not null ORDER by fechacrea";*/
							$res=ExQuery($cons);
							?>
							<table class="tabla2"   <?php echo $borderTabla2Mentor; echo $bordercolorTabla2Mentor; echo $cellspacingTabla2Mentor; echo $cellpaddingTabla2Mentor; ?> >
								<tr>
									<td class="encabezado2Horizontal" colspan="12">APROBAR SUCESOS</td>
								</tr>
								<tr>
									<td  class='encabezado2HorizontalInvertido'>FECHA REPORTE</td>
									<td  class='encabezado2HorizontalInvertido'>NO. SUCESO </td>
									<td  class='encabezado2HorizontalInvertido'>REPORTA</td>
									<td  class='encabezado2HorizontalInvertido'>PACIENTE</td>
									<td  class='encabezado2HorizontalInvertido'>IDENTIFICACI&Oacute;N</td>
									<td  class='encabezado2HorizontalInvertido'>PROCESO</td>
									<td  class='encabezado2HorizontalInvertido'>SERVICIO</td>
									<td class='encabezado2HorizontalInvertido'>FORMATO</td>
									<td class='encabezado2HorizontalInvertido'>FECHA AN&Aacute;LISIS</td>
									<td class='encabezado2HorizontalInvertido'>AN&Aacute;LISIS HALLAZGO</td><td></td>
							</tr>
							<?
							while($fila=ExFetch($res)){?>
							<tr align='center' onMouseOver="this.bgColor='#AAD4FF'" onMouseOut="this.bgColor=''">
							<td><? echo $fila[0]?></td>
							<td><? echo $fila[9]?></td>
							<td><? echo $fila[1]?></td>
								<? 	if($fila[10]){?>
							<td style="cursor:hand" title="Ver Correo" colspan="5"
										onClick="open('/Correo/VerCorroInd.php?DatNameSID=<? echo $DatNameSID?>&IdCorreo=<? echo $fila[10]?>','','')"> Suceso No Ligado A Un Paciente </td>
								<?	}
									else
									{
										$cons2="select (primape || ' ' || segape || ' ' || primnom || ' ' || segnom) from central.terceros where compania='$Compania[0]' and 
										identificacion='$fila[3]'";
										$res2=ExQuery($cons2); $fila2=ExFetch($res2);?>
							<td style="cursor:hand" title="Abrir Historia"
										onClick="location.href='/HistoriaClinica/ResultBuscarHC.php?DatNameSID=<? echo $DatNameSID?>&Cedula=<? echo $fila[3]?>&Buscar=1'"><? echo $fila2[0]?></td>
										<td style="cursor:hand" title="Abrir Historia"
										onClick="location.href='/HistoriaClinica/ResultBuscarHC.php?DatNameSID=<? echo $DatNameSID?>&Cedula=<? echo $fila[3]?>&Buscar=1'"><? echo $fila[3]?></td>
										<td><? echo "$fila[4]&nbsp;"?></td><td><? echo "$fila[5]&nbsp;"?></td>
										<td style="cursor:hand" title="Ver Formato" onClick="open('/HistoriaClinica/ImpHistoria.php?DatNameSID=<? echo $DatNameSID?>&Formato=<? echo $fila[7]?>&TipoFormato=<? echo $fila[6]?>&IdHistoria=<? echo $fila[8]?>&CedImpMasv=<? echo $fila[2]?>&Opc=1','','')"><? echo $fila[7]?></td>  
								<?	}
									$cons2="select fechacrea from pacienteseguro.regprotocolo where compania='$Compania[0]' and idsuceso=$fila[9]";
									//echo $cons2;
									$res2=ExQuery($cons2); $fila2=ExFetch($res2);?>
							<td><? echo $fila2[0]?>&nbsp;</td>
								<?	if($fila[11]=="EventoAdverso"){$fila[11]="Evento Adverso";}
									if($fila[11]=="Incidente"){$fila[11]="Incidente (Accion Insegura)";}?>
							<td style="cursor:hand" title="Ver Formato de Registro de Hallazgos"
									onClick="open('VerRepRegHallasgos.php?DatNameSID=<? echo $DatNameSID?>&IdSuceso=<? echo $fila[9]?>','','')"><? echo $fila[11]?>&nbsp;</td>
									<td align="center"><a href="?DatNameSID=<? echo $DatNameSID?>&aprobacion=<?php echo "$fila[9]"; ?>"><img src="/Imgs/b_check.png" width="16" height="16" border="0"></a>
									  <a href="?DatNameSID=<? echo $DatNameSID?>&rechazar=<?php echo "$fila[9]"; ?>"><img src="/Imgs/b_drop.png" width="16" height="16" border="0"></a></td>
								</tr><?
							}?>
							</table>
							<input type="hidden" name="Guardar" value=""/>
							<input type="hidden" name="DatNameSID" value="<? echo $DatNameSID?>">
						</form>
						<iframe id="FrameOpener" name="FrameOpener" style="display:none" frameborder="0" height="1" style="border:#e5e5e5 ridge" ></iframe>
					</div>	
			</body>
	</html>
