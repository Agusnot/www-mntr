		<?php
			if($DatNameSID){session_name("$DatNameSID");}
			session_start();
			include("Funciones.php");
			include_once("General/Configuracion/Configuracion.php");
			$ND=getdate();
			$cons="select aprobador from pacienteseguro.aprobador where compania='$Compania[0]' and aprobador='$usuario[1]'";
			$res=ExQuery($cons);
			if(ExNumRows($res)>0){$BanAprov=1;}
			
			$cons="select usucomite from pacienteseguro.comite where compania='$Compania[0]' and usucomite='$usuario[1]'";
			$res=ExQuery($cons);
			if(ExNumRows($res)>0){$BanReg=1;}
			
			if($IdAccion){//IdAccion FecApl Aprueba Realiza Cierra		
				$cons="select fechaaplaza,fechaaprueba,fecharealizado,fechacierra,fechacrea,fechauno
				from pacienteseguro.accionespropuestas where compania='$Compania[0]'
				and idsuceso=$IdSuceso and idaccion=$IdAccion ";
				$res=ExQuery($cons); $fila=ExFetch($res);		
				if($fila[0]!=$FecApl){$FA=",fechaaplaza='$FecApl',fechauno='$fila[5]',usumod='$usuario[1]',fechamod='$ND[year]-$ND[mon]-$ND[mday] $ND[hours]:$ND[minutes]:$ND[seconds]'";}
				if($BanAprov){ 
					if($Aprueba==1&&!$fila[1]){$AP=",fechaaprueba='$ND[year]-$ND[mon]-$ND[mday] $ND[hours]:$ND[minutes]:$ND[seconds]',usuarioaprueba='$usuario[1]'";}
					if($Aprueba!=1&&$fila[1]){$AP=",fechaaprueba=NULL"; $Realiza=0; $Cierra=0;}
				}
				if($BanReg){
					if($Realiza==1&&!$fila[2]){$RE=",fecharealizado='$ND[year]-$ND[mon]-$ND[mday] $ND[hours]:$ND[minutes]:$ND[seconds]',usurealizado='$usuario[1]'";}
					if($Realiza!=1&&$fila[2]){$RE=",fecharealizado=NULL"; $Cierra=0;}				
				}
				if($BanAprov){
					if($Cierra==1&&!$fila[3]){$CI=",fechacierra='$ND[year]-$ND[mon]-$ND[mday] $ND[hours]:$ND[minutes]:$ND[seconds]',usucierra='$usuario[1]'";}
					if($Cierra!=1&&$fila[3]){
						$CI=",fechacierra=NULL"; 
					}
				}
				
				$cons2="update pacienteseguro.accionespropuestas set observacion='$Observacion', fechacrea='$fila[4]' $FA $AP $RE $CI where compania='$Compania[0]' 
				and idaccion=$IdAccion and idsuceso=$IdSuceso";
				//echo $cons2;
				$res2=ExQuery($cons2);
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
						function CerrarThis()
						{
							parent.document.getElementById('FrameOpener').style.position='absolute';
							parent.document.getElementById('FrameOpener').style.top='1px';
							parent.document.getElementById('FrameOpener').style.left='1px';
							parent.document.getElementById('FrameOpener').style.width='1';
							parent.document.getElementById('FrameOpener').style.height='1';
							parent.document.getElementById('FrameOpener').style.display='none';
							//parent.document.FORMA.submit();
						}
					</script>	
				</head>

				<body <?php echo $backgroundBodyMentor; ?>>
						<form name="FORMA" method="post" onSubmit="return Validar()">
								<table class="tabla2"   <?php echo $borderTabla2Mentor; echo $bordercolorTabla2Mentor; echo $cellspacingTabla2Mentor; echo $cellpaddingTabla2Mentor; ?>>
									<tr>
										<td class="encabezado2Horizontal" style="font-size:11px;">ACCIONES</td>
										<td class="encabezado2Horizontal" style="font-size:11px;">TIPO DE ACCI&Oacute;N </td>
										<td class="encabezado2Horizontal" style="font-size:11px;">RESPONSABLE</td>
										<td class="encabezado2Horizontal" style="font-size:11px;">FECHA LIMITE INFERIOR</td>
										<td class="encabezado2Horizontal" style="font-size:11px;">NUEVA FECHA LIMITE</td>
										<td class="encabezado2Horizontal" style="font-size:11px;">OBSERVACI&Oacute;N</td>
										<td class="encabezado2Horizontal" style="font-size:11px;">APRUEBA ACCI&Oacute;N</td>
										<td class="encabezado2Horizontal" style="font-size:11px;">ACCI&Oacute;N REALIZADA</td>
										<td class="encabezado2Horizontal" style="font-size:11px;">ACCI&Oacute;N CERRADA</td>
										<td class="encabezado2Horizontal" style="font-size:11px;">&nbsp;</td>
									</tr>
								<?
									$cons="select accion,nombre,fechauno,fechaaplaza,idaccion,fechaaprueba,fecharealizado,fechacierra,observacion,tipoaccion from pacienteseguro.accionespropuestas,central.usuarios
									where compania='$Compania[0]' and idsuceso=$IdSuceso and  usuario=responsable order by fechacrea,accion";
									$res=ExQuery($cons);
									while($fila=ExFetch($res))
									{?>
										<tr align="center">
											<td><? echo $fila[0]?></td>
											<td><? echo $fila[9]?></td>
											<td><? echo $fila[1]?></td><td><? echo $fila[2]?></td>
											<td><input type="text" name="FechaAplaza_<? echo $fila[4]?>" id="FechaAplaza_<? echo $fila[4]?>" 
											readonly="readonly" value="<? echo $fila[3]?>" style="width:90"
											onClick="<?	if($BanAprov){?>popUpCalendar(this, FORMA.FechaAplaza_<? echo $fila[4]?>, 'yyyy-mm-dd')<? }
											else{?> alert('Usted no ha sido registrado como usuario aprovador!!!');<? }?>"/></td>
											<td><textarea name="Observacion_<? echo $fila[4]?>" id="Observacion_<? echo $fila[4]?>"><? echo $fila[8]?></textarea></td>
											<td><input type="checkbox" name="Aprueba_<? echo $fila[4]?>" id="Aprueba_<? echo $fila[4]?>" <? if(!$BanAprov){?> disabled<? }?>
											<? if($fila[5]){?> checked<? }?>></td>
											<td><input type="checkbox" name="Realiza_<? echo $fila[4]?>" id="Realiza_<? echo $fila[4]?>" <? if(!$BanReg||!$fila[5]){?> disabled<? }?>
											<? if($fila[6]&&$fila[5]){?> checked<? }?>></td>
											<td><input type="checkbox" name="Cierra_<? echo $fila[4]?>" id="Cirra_<? echo $fila[4]?>" <? if(!$BanReg||!$fila[5]||!$fila[6]){?> disabled<? }?>
											<? if($fila[7]&&$fila[6]&&$fila[5]){?> checked<? }?>></td>
											<td>
												<button style="cursor:hand" title="Registrar" 
												onClick="
												if(document.FORMA.Aprueba_<? echo $fila[4]?>.checked==true){document.FORMA.AuxAgr.value=1;}else{document.FORMA.AuxAgr.value=0;};
												if(document.FORMA.Realiza_<? echo $fila[4]?>.checked==true&&document.FORMA.AuxAgr.value==1)
												{document.FORMA.AuxRea.value=1;}else{document.FORMA.AuxRea.value=0;};
												if(document.FORMA.Cierra_<? echo $fila[4]?>.checked==true&&document.FORMA.AuxAgr.value==1&&document.FORMA.AuxRea.value==1)
												{document.FORMA.AuxCie.value=1;}else{document.FORMA.AuxCie.value=0;};
																
												if(confirm('¿Esta seguro de realizar este registro?')){
													location.href='RegSeguimientoCaso.php?DatNameSID=<? echo $DatNameSID?>&IdAccion=<? echo $fila[4]?>&IdSuceso=<? echo $IdSuceso?>&FecApl='+document.FORMA.FechaAplaza_<? echo $fila[4]?>.value+'&Aprueba='+document.FORMA.AuxAgr.value+'&Realiza='+document.FORMA.AuxRea.value+'&Cierra='+document.FORMA.AuxCie.value+'&Observacion='+document.FORMA.Observacion_<? echo $fila[4]?>.value+'';
													}">
													<img src="/Imgs/b_check.png">              	</button>            </td>
										</tr>			
								<?	}?>    
									</tr>
									 <tr align="center">
										<td colspan="17"><input type="button" class="boton2Envio" value="Cerrar" onClick="CerrarThis()"/></td>
									</tr>
								</table>
								<input type="hidden" name="AuxAgr" value="">
								<input type="hidden" name="IdAccion" value="">
								<input type="hidden" name="AuxRea" value="">
								<input type="hidden" name="AuxCie" value="">
								<input type="hidden" name="Eliminar" value="">
								<input type="hidden" name="Agregar" value="">
								<input type="hidden" name="Acciones" value="<? echo $Acciones?>">
								<input type="hidden" name="IdSuceso" value="<? echo $IdSuceso?>">
								<input type="hidden" name="DatNameSID" value="<? echo $DatNameSID?>">
						</form>    
				</body>
		</html>