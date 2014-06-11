		<?php
			if($DatNameSID){session_name("$DatNameSID");}
			session_start();
			include("Funciones.php");
			include_once("General/Configuracion/Configuracion.php");
			
			$consU="select usuarios.nombre,medicos.cargo from salud.medicos inner join central.usuarios on usuarios.usuario=medicos.usuario where  usuarios.usuario='$usuario[1]'";
			$resU=ExQuery($consU);
			$filaU=ExFetch($resU);
			
			if($Eliminar)
			{
				$ND=getdate();
				$cons="delete from salud.elementoscustodia where compania='$Compania[0]' and cedula='$Ced' and numservicio=$NumServ and elemento='$Elemento'";
				$res=ExQuery($cons);
				$Detalle="Eliminar Elemento en custodia:$Elemento";
				$cons="insert into salud.logsuper (compania,accion,cedula,numservicio,fecha,usuario,formato,detalle) values (
				'$Compania[0]','Eliminar','$Ced','$NumServ','$ND[year]-$ND[mon]-$ND[mday] $ND[hours]:$ND[minutes]:$ND[seconds]','$usuario[1]','Elementos Custodia','$Detalle')";
				$res=ExQuery($cons);
				//echo $cons;		
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
				<script language="javascript">
					function raton(e,Elemento,FN) { 
						x = e.clientX; 
						y = e.clientY; 	
						frames.FrameOpener.location.href="CambiarNC.php?DatNameSID=<? echo $DatNameSID?>&Elemento="+Elemento+"&NumServ=<? echo $NumServ?>&Ced=<? echo $Ced?>&Ambito=<? echo $Ambito?>&UnidadHosp=<? echo $UndHosp?>&FN="+FN;
						document.getElementById('FrameOpener').style.position='absolute';
						document.getElementById('FrameOpener').style.top=y-30;
						document.getElementById('FrameOpener').style.left=x;
						document.getElementById('FrameOpener').style.display='';
						document.getElementById('FrameOpener').style.width='260px';
						document.getElementById('FrameOpener').style.height='350px';
					} 
					function raton2(e,Elemento) { 		
						x = e.clientX; 
						y = e.clientY; 	
						frames.FrameOpener.location.href="CambiarNota.php?DatNameSID=<? echo $DatNameSID?>&Elemento="+Elemento+"&NumServ=<? echo $NumServ?>&Ced=<? echo $Ced?>&Ambito=<? echo $Ambito?>&UnidadHosp=<? echo $UndHosp?>";
						document.getElementById('FrameOpener').style.position='absolute';
						document.getElementById('FrameOpener').style.top=y+25;
						document.getElementById('FrameOpener').style.left=x;
						document.getElementById('FrameOpener').style.display='';
						document.getElementById('FrameOpener').style.width='180px';
						document.getElementById('FrameOpener').style.height='140px';
					}
					
					function Imprimir(nombre,hid,hidCont,hidFoo){
						document.getElementById(hidCont).style.display='inline';
						document.getElementById(hidFoo).style.display='inline';
						document.getElementById(hid).style.display='none';
						var ficha = document.getElementById(nombre);
						var ventimp = window.open('', 'popimpr');
						ventimp.document.write( ficha.innerHTML );
						ventimp.document.close();
						ventimp.print( );
						ventimp.close();
						document.getElementById(hid).style.display='inline';
						document.getElementById(hidCont).style.display='none';
						document.getElementById(hidFoo).style.display='none';
						document.forms.FORMA.submit();
						}
						
						function MFecha(){
						document.FORMA.Fech.value=1;
						}
						
						function MFecha_(){
						document.FORMA.Fech_.value=1;
						}
				</script>
			</head>

			<body <?php echo $backgroundBodyMentor; ?>>
				<?php
					$rutaarchivo[0] = "HISTORIA CL&Iacute;NICA";
					$rutaarchivo[1] = "HOSPITALIZACI&Oacute;N";
					$rutaarchivo[2] = "CONTROL DE PERTENENCIAS";
					$rutaarchivo[3] = "CUSTODIA";
					
					mostrarRutaNavegacionEstatica($rutaarchivo);
			?>	
				<div align="center">
					<form name="FORMA" method="post">
					<table class="tabla2" width="500px"   <?php echo $borderTabla2Mentor; echo $bordercolorTabla2Mentor; echo $cellspacingTabla2Mentor; echo $cellpaddingtabla1Mentor; ?>>
						<tr>
							<td class='encabezado2Horizontal' colspan = "2"> DATOS PACIENTE </td>
							
						</tr>
					
					<? 	$cons="select primnom,segnom,primape,segape from central.terceros where compania='$Compania[0]' and identificacion='$Ced'";
						$res=ExQuery($cons);$fila=ExFetch($res);?>
						
						<tr>
							<td class='encabezado2HorizontalInvertido'>NOMBRE</td>
							<td><? echo "$fila[0] $fila[1] $fila[2] $fila[3]"?></td>
						</tr>
						<tr>
							<td class='encabezado2HorizontalInvertido'>CEDULA</td>
							<td><? echo $Ced?></td>
						</tr>
						<tr>
							<td class='encabezado2HorizontalInvertido'>PROCESO</td>
							<td><? echo $Ambito?></td>
						</tr>
						<tr>
							<td class='encabezado2HorizontalInvertido'>SERVICIO</td>
							<td><? echo $UndHosp?></td>
						</tr>    
						<tr>
							<td colspan="2" align="center">
								<input style="width:80px" class="boton2Envio" type="button" value="Nuevo" 
							onClick="location.href='NewEltoCustodia.php?DatNameSID=<? echo $DatNameSID?>&Ced=<? echo $Ced?>&NumServ=<? echo $NumServ?>&Ambito=<? echo $Ambito?>&UndHosp=<? echo $UndHosp?>'">
							<input type="button"  class="boton2Envio" value="Cancelar" onClick="location.href='ControlPertenencias.php?DatNameSID=<? echo $DatNameSID?>&Ambito=<? echo $Ambito?>&UnidadHosp=<? echo $UndHosp?>'"></td>
						</tr>
					</table>
					<br><div id="ECS">
					<div id="MECS">








					<table width="740" class="tabla2" <?php echo $borderTabla2Mentor; echo $bordercolorTabla2Mentor; echo $cellspacingTabla2Mentor; echo $cellpaddingtabla1Mentor; ?>>
					  <td width="724">
					  <table width="724" <?php echo $borderTabla2Mentor; echo $bordercolorTabla2Mentor; echo $cellspacingTabla2Mentor; echo $cellpaddingtabla1Mentor; ?>>
					  <tr align="center">
						<td width="132" rowspan="4">
						<img src="/Imgs/Logo.jpg" width="88" height="78">	</td>
						<td width="471" rowspan="4">
						  <?
						echo "<font size='4' >".$Compania[0]."</font>";
						echo "<br>CODIGO ".$Compania[17]."";
						echo "<br>".$Compania[1]."</br>";
						echo "".$Compania[2]." - ";
						echo " TELEFONOS ".$Compania[3]."";	
						?>	</td>
					  </tr>
					 
					</table>
					<br>
					<table width="724" class="tabla2" <?php echo $borderTabla2Mentor; echo $bordercolorTabla2Mentor; echo $cellspacingTabla2Mentor; echo $cellpaddingtabla1Mentor; ?>>
					<tr>
					<td width="708" align="center">


					<?

					$cons9="Select * from Central.Terceros where Identificacion='$Ced' and compania='$Compania[0]'";
							//echo $cons9;
							$res9=ExQuery($cons9);echo ExError();
							$fila9=ExFetch($res9);

							$Paciente[1]=$fila9[0];
							$n=1;
							for($i=1;$i<=ExNumFields($res9);$i++)
							{
								$n++;
								$Paciente[$n]=$fila9[$i];
								//echo "<br>$n=$Paciente[$n]";
							}
					?>

					<table  width="100%"  class="tabla2" <?php echo $borderTabla2Mentor; echo $bordercolorTabla2Mentor; echo $cellspacingTabla2Mentor; echo $cellpaddingtabla1Mentor; ?>>
							<tr><td>
								<table class="tabla2" <?php echo $borderTabla2Mentor; echo $bordercolorTabla2Mentor; echo $cellspacingTabla2Mentor; echo $cellpaddingtabla1Mentor; ?>>
									<tr>
										<td class='encabezado2VerticalInvertido'>NOMBRE:</td>
										<td><? echo "$Paciente[2] $Paciente[3] $Paciente[4] $Paciente[5]"?></td>
									</tr>
									<tr>
										<td class='encabezado2VerticalInvertido'>IDENTIFICACI&Oacute;N:</td>
										<td><? echo "$Paciente[1]"?></td>
									</tr>
									<tr>
										<td class='encabezado2VerticalInvertido'>FECHA DE NACIMIENTO:</td>
										<td><? echo "$Paciente[23] ($Edad)"?></td>
									</tr>
									<tr>
										<td class='encabezado2VerticalInvertido'>DIRECCI&Oacute;N:</td>
										<td><? echo $Paciente[7];?></td>
									</tr>
									<tr>
										<td class='encabezado2VerticalInvertido'>TIPO DE USUARIO:</td>
										<td><? echo "$Paciente[27]"?></td>
									</tr>
									<tr>
										<td class='encabezado2VerticalInvertido'>NIVEL DE USUARIO:</td>
										<td><? echo "$Paciente[28]"?></td>
									</tr>
								</table>
							</td>
							</table>




					</td>
					</tr>
					</table>  
					  </td>
					  </tr>	
					  <tr><td height="2" colspan="7">
					  </td> 
					  </tr> 
					</table>

					<input type="hidden" name="DatNameSID" value="<? echo $DatNameSID?>">








					</div>
					<table class="tabla2" <?php echo $borderTabla2Mentor; echo $bordercolorTabla2Mentor; echo $cellspacingTabla2Mentor; echo $cellpaddingtabla1Mentor; ?>>
						<tr>
							<td class='encabezado2Horizontal' colspan="15">ELEMENTOS SIN SALIDA</td></tr>
					<?
						$cons="select super from central.usuarios where usuario='$usuario[1]'";
						$res=ExQuery($cons);$fila=ExFetch($res);
						$Super=$fila[0];
						if($Fech_)
						$cons="select responsable,elemento,estado,fechasalida,nota,nc,cantidad,fechacustodia from salud.elementoscustodia where compania='$Compania[0]' and cedula='$Ced' and numservicio=$NumServ and 
						fechacustodia between '$FechaIni_ 00:00:00' and '$FechaFin_ 23:59:59' and fechasalida is null order by responsable,elemento";
						else
						$cons="select responsable,elemento,estado,fechasalida,nota,nc,cantidad,fechacustodia from salud.elementoscustodia where compania='$Compania[0]' and cedula='$Ced' and numservicio=$NumServ and 
						fechasalida is null order by responsable,elemento";
						$res=ExQuery($cons);
						if(ExNumRows($res)>0)
						{?>
							<tr>
								<td class='encabezado2HorizontalInvertido'>RESPONSABLE</td>
								<td class='encabezado2HorizontalInvertido'>ELEMENTO</td>
								<td class='encabezado2HorizontalInvertido'>CANTIDAD</td>
								<td class='encabezado2HorizontalInvertido'>ESTADO</td>
								<td class='encabezado2HorizontalInvertido'>FECHA ENTRADA</td>
								<td class='encabezado2HorizontalInvertido'>FECHA SALIDA</td>
								<td class='encabezado2HorizontalInvertido'>NOTA</td>
								<td class='encabezado2HorizontalInvertido'>NC</td>
							<? if($Super){echo "<td class='encabezado2HorizontalInvertido'></td>";}?>
							</tr>
						<?
							while($fila=ExFetch($res))
							{			
								if($fila[5]==1){$NoC="Si";}else{$NoC="No";}
								?>
								<tr align="center" onMouseOver="this.bgColor='#AAD4FF'" onMouseOut="this.bgColor=''">            
								<td><? echo $fila[0]?></td><td><? echo $fila[1]?></td><td><? echo $fila[6]?></td><td><? echo $fila[2]?></td>
								 <td style="cursor:hand" title=""><? echo substr($fila[7],0,10);?>&nbsp;</td>
								<td style="cursor:hand" title="Dar de Baja" onClick="raton(event,'<? echo $fila[1]?>',1)">&nbsp </td>
								<td style="cursor:hand" title="Modificar" onClick="raton2(event,'<? echo $fila[1]?>')"><? echo $fila[4]?>&nbsp;</td>
								<td style="cursor:hand" title="Modificar" onClick="raton(event,'<? echo $fila[1]?>',0)"><? echo $NoC?></td>
								<? if($Super){?>
									<td><img title="Eliminar" style="cursor:hand" 
									onClick="if(confirm('Desea eliminar este registro?')){location.href='ElementosCustodia.php?DatNameSID=<? echo $DatNameSID?>&Elemento=<? echo $fila[1]?>&Eliminar=1&Ced=<? echo $Ced?>&NumServ=<? echo $NumServ?>&Ambito=<? echo $Ambito?>&UndHosp=<? echo $UndHosp?>';}" src="/Imgs/b_drop.png"></td>
							<?	}?>
								</tr>
								<?
							}
						?>        
					<?	}
						else
						{
							echo "<tr>";
								echo "<td class='mensaje1' style='text-align:center;' colspan='15'>No hay elementos sin salida</td>";
							echo "</tr>";
						}
					?><tr id="CECS">
						<td style="text-align:center;font-weight:bold" colspan="7" >
							<input value="Imprimir" class="boton2Envio" onClick="MFecha_();" type="submit">
							Fecha Inicial:<input type="Text" name="FechaIni_" onKeyUp="Validar(this.value)"  readonly onClick="popUpCalendar(this, FORMA.FechaIni_, 'yyyy-mm-dd')" style="width:80px">
					&nbsp;&nbsp;Fecha Final:<input type="Text" name="FechaFin_" onKeyUp="Validar(this.value)"  readonly onClick="popUpCalendar(this, FORMA.FechaFin_, 'yyyy-mm-dd')" style="width:80px">
					<input type="Hidden" name="Fech_" value=""></td></tr>
					</table>
					<br><br><br>
					<div id="FECS">
					<table width="740" border="0" class="tabla2" <?php  echo $cellspacingTabla2Mentor; echo $cellpaddingtabla1Mentor; ?>>
					<?
							echo "<tr><td>__________________________________</td><td style='width:120px;'></td><td>__________________________________</td></tr>";

							echo "<tr><td>$filaU[0]<br>$filaU[1]</td><td><center><b></b></center></td><td>Paciente o Acudiente<br>CC No.</td></tr>";
							echo "</table>";?>
					</div>
					</div><div id="ESS">
					<div id="MESS">









					<table width="740" class="tabla2" <?php echo $borderTabla2Mentor; echo $bordercolorTabla2Mentor; echo $cellspacingTabla2Mentor; echo $cellpaddingtabla1Mentor; ?>>
					  <td width="724">
					  <table width="724" class="tabla2" <?php echo $borderTabla2Mentor; echo $bordercolorTabla2Mentor; echo $cellspacingTabla2Mentor; echo $cellpaddingtabla1Mentor; ?>>
					  <tr align="center">
						<td width="132" rowspan="4">
						<img src="/Imgs/Logo.jpg" width="88" height="78">	</td>
						<td width="471" rowspan="4">
						  <?
						echo "<font size='4' >".$Compania[0]."</font>";
						echo "<br>CODIGO ".$Compania[17]."";
						echo "<br>".$Compania[1]."</br>";
						echo "".$Compania[2]." - ";
						echo " TELEFONOS ".$Compania[3]."";	
						?>	</td>
					  </tr>
					 
					</table>
					<br>
					<table width="724" class="tabla2" <?php echo $borderTabla2Mentor; echo $bordercolorTabla2Mentor; echo $cellspacingTabla2Mentor; echo $cellpaddingtabla1Mentor; ?>>
					<tr>
					<td width="708" align="center">


					<?

					$cons9="Select * from Central.Terceros where Identificacion='$Ced' and compania='$Compania[0]'";
							//echo $cons9;
							$res9=ExQuery($cons9);echo ExError();
							$fila9=ExFetch($res9);

							$Paciente[1]=$fila9[0];
							$n=1;
							for($i=1;$i<=ExNumFields($res9);$i++)
							{
								$n++;
								$Paciente[$n]=$fila9[$i];
								//echo "<br>$n=$Paciente[$n]";
							}
					?>

					<table class="tabla2" <?php echo $borderTabla2Mentor; echo $bordercolorTabla2Mentor; echo $cellspacingTabla2Mentor; echo $cellpaddingtabla1Mentor; ?> width="100%">
							<tr><td>
								<table class="tabla2" <?php echo $borderTabla2Mentor; echo $bordercolorTabla2Mentor; echo $cellspacingTabla2Mentor; echo $cellpaddingtabla1Mentor; ?>>
									<tr>
										<td class='encabezado2HorizontalInvertido'>NOMBRE:</td>
										<td><? echo "$Paciente[2] $Paciente[3] $Paciente[4] $Paciente[5]"?></td>
									</tr>
									<tr>
										<td class='encabezado2HorizontalInvertido'>IDENTIFICACI&Oacute;N:</td>
										<td><? echo "$Paciente[1]"?></td>
									</tr>
									<tr>
										<td class='encabezado2HorizontalInvertido'>FECHA DE NACIMIENTO:</td>
										<td><? echo "$Paciente[23] ($Edad)"?></td>
									</tr>
									<tr>
										<td class='encabezado2HorizontalInvertido'>DIRECCI&Oacute;N:</td>
										<td><? echo $Paciente[7];?></td>
									</tr>
									<tr>
										<td class='encabezado2HorizontalInvertido'>TIPO DE USUARIO:</td>
										<td><? echo "$Paciente[27]"?></td>
									</tr>
									<tr>
										<td class='encabezado2HorizontalInvertido'>NIVEL DE USUARIO:</td>
										<td><? echo "$Paciente[28]"?></td>
									</tr>
								</table>
							</td>
							</table>




					</td>
					</tr>
					</table>  
					  </td>
					  </tr>	
					  <tr><td height="2" colspan="7">
					  </td> 
					  </tr> 
					</table>

					<input type="hidden" name="DatNameSID" value="<? echo $DatNameSID?>">








					</div>
					<table class="tabla2" <?php echo $borderTabla2Mentor; echo $bordercolorTabla2Mentor; echo $cellspacingTabla2Mentor; echo $cellpaddingtabla1Mentor; ?>>
						<tr>
							<td class='encabezado2Horizontal' colspan="15">ELEMENTOS CON SALIDA</td>
						</tr>
							<?
								if($Fech)
								$cons="select responsable,elemento,estado,fechasalida,nota,nc from salud.elementoscustodia where compania='$Compania[0]' and cedula='$Ced' and numservicio=$NumServ and 
								fechasalida between '$FechaIni 00:00:00' and '$FechaFin 23:59:59' order by responsable,elemento";
								else $cons="select responsable,elemento,estado,fechasalida,nota,nc from salud.elementoscustodia where compania='$Compania[0]' and cedula='$Ced' and numservicio=$NumServ and 
								fechasalida is not null order by responsable,elemento";

								$res=ExQuery($cons);
								if(ExNumRows($res)>0)
								{?>
									<tr>
										<td class='encabezado2HorizontalInvertido'>RESPONSABLE</td>
										<td class='encabezado2HorizontalInvertido'>ELEMENTO</td>
										<td class='encabezado2HorizontalInvertido'>ESTADO</td>
										<td class='encabezado2HorizontalInvertido'>FECHA SALIDA</td>
										<td class='encabezado2HorizontalInvertido'>NOTA</td>
										<td class='encabezado2HorizontalInvertido'>NC</td>
							<? if($Super){echo "<td class='encabezado2HorizontalInvertido'></td>";}?>
							</tr>
						<?
							while($fila=ExFetch($res))
							{
								$FS=substr($fila[3],0,10);
								if($fila[5]==1){$NoC="Si";}else{$NoC="No";}
								?>
								<tr align="center" onMouseOver="this.bgColor='#AAD4FF'" onMouseOut="this.bgColor=''">            
								<td><? echo $fila[0]?></td><td><? echo $fila[1]?></td><td><? echo $fila[2]?></td><td><? echo $FS?></td><td><? echo $fila[4]?>&nbsp;</td><td><? echo $NoC?></td>
							 <? if($Super){?>
									<td><img title="Eliminar" style="cursor:hand" 
									onClick="if(confirm('Desea eliminar este registro?')){location.href='ElementosCustodia.php?DatNameSID=<? echo $DatNameSID?>&Elemento=<? echo $fila[1]?>&Eliminar=1&Ced=<? echo $Ced?>&NumServ=<? echo $NumServ?>&Ambito=<? echo $Ambito?>&UndHosp=<? echo $UndHosp?>';}" src="/Imgs/b_drop.png"></td>
							<?	}?>
								</tr>
								<?
							}
						?>        
					<?	}
						else
						{
							echo "<tr><td class='mensaje1' style='text-align:center;' colspan='15'>No Hay Elementos Con Salida</td></tr>";
						}
					?><tr id="CESS">
						<td  colspan="6" style="text-align:center;font-weight:bold">
						<input value="Imprimir" class="boton2Envio" onClick="MFecha();" type="submit">
					Fecha Inicial:<input type="Text" name="FechaIni" onKeyUp="Validar(this.value)"  readonly onClick="popUpCalendar(this, FORMA.FechaIni, 'yyyy-mm-dd')" style="width:80px">
					&nbsp;&nbsp;Fecha Final:<input type="Text" name="FechaFin" onKeyUp="Validar(this.value)"  readonly onClick="popUpCalendar(this, FORMA.FechaFin, 'yyyy-mm-dd')" style="width:80px">
					<input type="Hidden" name="Fech" value=""></td></tr>
					</table>
					<br><br><br>
						<div id="FESS">
						  <table width="740" border="0" align="center" cellpadding="2" bordercolor="#e5e5e5"   style='font : normal normal small-caps 12px Tahoma;'>
						<?
								echo "<tr><td>__________________________________</td><td style='width:120px;'></td><td>__________________________________</td></tr>";

								echo "<tr><td>$filaU[0]<br>$filaU[1]</td><td><center><b></b></center></td><td>Paciente o Acudiente<br>CC No.</td></tr>";
								echo "</table>";?>
						</div>
					</div>
					<input type="Hidden" name="DatNameSID" value="<? echo $DatNameSID?>">
					</form> 
					<iframe scrolling="no" id="FrameOpener" name="FrameOpener" style="display:none" frameborder="0" height="1" style="border:#e5e5e5 ridge" ></iframe>   
				</div>	
		</body>
	</html>
			<script language="javascript">
				document.getElementById('MECS').style.display='none';
				document.getElementById('MESS').style.display='none';
				document.getElementById('FECS').style.display='none';
				document.getElementById('FESS').style.display='none';
			</script>
			<?if($Fech){?><script>Imprimir('ESS','CESS','MESS','FESS');</script><?}?>
			<?if($Fech_){?><script>Imprimir('ECS','CECS','MECS','FECS');</script><?}?>