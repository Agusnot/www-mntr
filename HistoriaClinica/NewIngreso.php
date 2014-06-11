		<?php
			if($DatNameSID){session_name("$DatNameSID");}
			session_start();
			include("Funciones.php");	
			include_once("General/Configuracion/Configuracion.php");
			$ND=getdate();
			if($ND[mon]<10){$cero='0';}else{$cero='';}
			if($ND[mday]<10){$cero1='0';}else{$cero1='';}
			$FechaComp="$ND[year]-$cero$ND[mon]-$cero1$ND[mday]";
			$MalFecha=0;
			if(!$TMPCOD){$TMPCOD=strtotime("$ND[year]-$ND[mon]-$ND[mday] $ND[hours]:$ND[minutes]:$ND[seconds]").rand(1,9999);}
			
			$consCargo = "SELECT cargo FROM salud.medicos WHERE usuario = '$usuario[1]'";
			$resCargo = ExQuery($consCargo);
			$filaCargo = ExFetch($resCargo);
			$cargo = $filaCargo[0]; 
			
			
			if($cargo == 'SIAU' ){
				$area = "ADMINISTRATIVA";
				$ingreso = 0;
			}
			if($cargo == 'AUXILIAR DE ENFERMERIA' || $cargo == 'JEFE DE ENFERMERIA' ){
				$area = "ASISTENCIAL";
				$ingreso = 2;
			}
			
			if($Cancelar){
				$cons = "Delete from Salud.tmpalertasingreso where Compania='$Compania[0]' and numservicio=$NumServ and cedula='$Ced' and Usuario='$usuario[1]' and tmpcod='$TMPCOD'";		
				//echo $cons;
				$res = ExQuery($cons);
				?><script language="javascript">location.href='Ingreso.php?DatNameSID=<? echo $DatNameSID?>&Ambito=<? echo $Ambito?>';</script><?
			}
			
			if($Anadir){	
				$cons="select numalert from salud.tmpalertasingreso where compania='$Compania[0]' and usuario='$usuario[1]' and cedula='$Ced' and tmpcod='$TMPCOD' and numservicio=$NumServ
				order by numalert desc";
				$res=ExQuery($cons);
				if(ExNumRows($res)>0){
					$fila=ExFetch($res);
					$NumAlert=$fila[0]+1;
				}
				else{
					$NumAlert=1;
				}

				$cons="insert into salud.tmpalertasingreso (compania,usuario,cedula,tmpcod,alerta,numservicio,fechaini,numalert) 
				values ('$Compania[0]','$usuario[1]','$Ced','$TMPCOD','$Alerta',$NumServ,'$FechaIni',$NumAlert)";
				$res=ExQuery($cons);
			}
			if($Ingresar){				
			
				if($cargo == 'SIAU' ){
					$ingreso = 2;
				}
				if($cargo == 'AUXILIAR DE ENFERMERIA' || $cargo == 'JEFE DE ENFERMERIA' ){
					$ingreso = 1;
				}
				
				$cons="select alerta,fechaini,fechafin from salud.tmpalertasingreso 
				where compania='$Compania[0]' and usuario='$usuario[1]' and tmpcod='$TMPCOD' and numservicio=$NumServ and cedula='$Ced'";
				$res=ExQuery($cons);
				while($fila=ExFetch($res)){
					// Se quita el campo numservicio porque no está creado en la BD para alertasingreso y se quita el campo fechaini ya que segun reunion con Dr Juan Carlos deben ser indefinidas
					//$cons2="insert into salud.alertasingreso (compania,usuario,fecha,numservicio,fechaini,fechafin,alerta,cedula) 
					$cons2="insert into salud.alertasingreso (compania,usuario,fecha,fechaini,alerta,cedula) 
					values ('$Compania[0]','$usuario[1]','$ND[year]-$ND[mon]-$ND[mday] $ND[hours]:$ND[minutes]:$ND[seconds]','$fila[1]','$fila[0]','$Ced')";
					$res2=ExQuery($cons2);
				}
				$cons="update salud.servicios set usuarioingreso='$usuario[1]',ingreso=$ingreso,fecharegingreso='$ND[year]-$ND[mon]-$ND[mday] $ND[hours]:$ND[minutes]:$ND[seconds]',notaingreso='$Notas', autorizac1='$Autorizac', fisicas='$Fisicas', mentales='$Mentales', observaciones='$Observaciones', acompanante='$Acompanante'
				where cedula='$Ced' and numservicio=$NumServ and tiposervicio='$Ambito'";
				//echo $cons;
				$res=ExQuery($cons);
				$cons = "Delete from Salud.tmpalertasingreso where Compania='$Compania[0]' and numservicio=$NumServ and cedula='$Ced' and Usuario='$usuario[1]' and tmpcod='$TMPCOD'";
				//echo $cons;
				$res = ExQuery($cons);
				while(list($cad,$val) = each($Pregunta)){
					if($val){
						$cons ="insert into salud.checkeos (compania,cedula,usuario,tipo,fecha,pregunta,numservicio,respuesta) 
						values ('$Compania[0]','$Ced','$usuario[1]','Ingreso','$ND[year]-$ND[mon]-$ND[mday] $ND[hours]:$ND[minutes]:$ND[seconds]','$cad',$NumServ,'$val')";
						$res = ExQuery($cons);
						//echo $cons."<br>";
					}
				}
				?>	<script language="javascript">				
						location.href='Ingreso.php?DatNameSID=<? echo $DatNameSID?>&Ambito=<? echo $Ambito?>';
				   </script><?
				
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
					<script language='javascript' src="/Funciones.js"></script>
					<script language='javascript' src="/calendario/popcalendar.js"></script>
					<script language="javascript">
					function Salir()
					{
						document.FORMA.Cancelar.value='1';
						document.FORMA.submit();
					}
					function ChequearTodos(chkbox) 
					{ 
						for (var i=0;i < document.forms["FORMA"].elements.length;i++) 
						{ 
							var elemento = document.forms[0].elements[i]; 
							if (elemento.type == "checkbox") 
							{ 
								elemento.checked = chkbox.checked 
							} 
						} 
					}

					function Inserta(){
						if(document.FORMA.Alerta.value==""){
							alert("El Campo Notificacion no puede quedar en blanco!!!");
						}
						else{
							if(document.FORMA.FechaIni.value==""){
								alert("Debe ingresar la fecha de inicio!!");
							}
							else{
								if(document.FORMA.FechaIni.value<document.FORMA.FechaComp.value){
									alert("La fecha inicial es menor a la fecha actual!!!");
								}
								else{
									document.FORMA.Anadir.value=1;
									document.FORMA.submit();
									
								}
							}	
						}
					}

					function Validar()
					{
						
						var c=1;
						//alert(document.getElementById('Aux'+c).value);
						for (var i=0;i < document.forms["FORMA"].elements.length;i++) 
						{ 				
							var elemento = document.forms[0].elements[i]; 
							//alert(elemento.type);
							if (elemento.type == "select-one") 
							{ 
								
								if(document.getElementById('Aux'+c).value=="1"&&document.getElementById('Pregunta'+c).value==""){
									alert("La pregunta "+document.getElementById('Aux'+c).name+" es obligatoria"); return false;
								}	
								c++;
							} 
						} 
						if(document.FORMA.Autorizac.value==""){alert("Debe digitar el numero de Autoriazacion!!!");return false;}
					}
					</script>
				</head>

			<body <?php echo $backgroundBodyMentor; ?>>
				<?php
						$rutaarchivo[0] = "HISTORIA CL&Iacute;NICA";
						$rutaarchivo[1] = "INGRESO";																
						$rutaarchivo[2] = "NUEVO INGRESO";
						mostrarRutaNavegacionEstatica($rutaarchivo);
				?>
				<div <?php echo $alignDiv2Mentor; ?> class="div2">	
					<form name="FORMA" method="post" onSubmit="return Validar()">
						<table class="tabla2" style="margin-top:25px;margin-bottom:25px;" width="900px"  <?php echo $bordertabla2Mentor; echo $bordercolortabla2Mentor; echo $cellspacingtabla2Mentor; echo $cellpaddingtabla2Mentor; ?>>
							<tr>
								<td class='encabezado2Horizontal'>CEDULA</td>
								<td class='encabezado2Horizontal'>NOMBRE</td>
								<td class='encabezado2Horizontal'>ASEGURADORA</td>
								<td class='encabezado2Horizontal'>CONTRATO</td>
								<td class='encabezado2Horizontal'>NO. CONTRATO</td>
								<td class='encabezado2Horizontal'>NO. AUTORIZACI&Oacute;N</td>
							</tr>
						<? 	$cons="select primnom,segnom,primape,segape,identificacion,numservicio,autorizac1 from salud.servicios,central.terceros where 
							servicios.compania='$Compania[0]' and terceros.compania='$Compania[0]' and servicios.estado='AC' and servicios.cedula=terceros.identificacion and servicios.ingreso=$ingreso
							and tiposervicio='$Ambito' and cedula='$Ced' group by primnom,segnom,primape,segape,identificacion,numservicio,autorizac1
							order by primnom,segnom,primape,segape";
							//echo $cons;
							$res=ExQuery($cons); 
							$fila=ExFetch($res);

							$cons2="select primnom,segnom,primape,segape,contrato,nocontrato from central.terceros,salud.pagadorxservicios 
							where terceros.compania='$Compania[0]' and pagadorxservicios.compania='$Compania[0]' and terceros.identificacion=pagadorxservicios.entidad 
							and pagadorxservicios.numservicio=$fila[5]	and '$FechaComp'>=fechaini and '$FechaComp'<=fechafin";	
							$res2=ExQuery($cons2);	
							//echo $cons2;  		  	
							if(ExNumRows($res2)>0){
								$fila2=ExFetch($res2);
								$EPS="$fila2[0] $fila2[1] $fila2[2] $fila2[3]"; $Contra="$fila2[4]"; $NoContra="$fila2[5]";
							}
							else{			
								$cons3="select primnom,segnom,primape,segape,contrato,nocontrato,fechafin from central.terceros,salud.pagadorxservicios 
								where terceros.compania='$Compania[0]' and pagadorxservicios.compania='$Compania[0]' and terceros.identificacion=pagadorxservicios.entidad and 
								pagadorxservicios.numservicio=$fila[5]	and '$FechaComp'>=fechaini";
								$res3=ExQuery($cons3);	
								//echo $cons3;
								if(ExNumRows($res3)>0){				
									$fila3=ExFetch($res3);
									if(!$fila3[6]){
										$EPS="$fila3[0] $fila3[1] $fila3[2] $fila3[3]"; $Contra="$fila3[4]"; $NoContra="$fila3[5]";	
									}
									else{
										$EPS=""; $Contra=""; $NoContra="";
									}
								}
								else{
									$EPS=""; $Contra=""; $NoContra="";
								}
							}
							?>
							<tr align='center'><? 
							echo "<td>$fila[4]</td><td>$fila[0] $fila[1] $fila[2] $fila[3]</td>";
							if($EPS){echo "<td>$EPS</td><td>$Contra</td><td>$NoContra</td>";}else{echo "<td colspan='3'> - Sin Asegurador Activo - </td>";}
							?>
							<td>
								<input type="text" name="Autorizac" onKeyDown="xLetra(this)" onKeyPress="xLetra(this)" onKeyUp="xLetra(this)" value="<? echo $fila[6]?>" style="width:100">
							</td>
							<tr>
								<td class="encabezado2HorizontalInvertido" style="background-color:#E5E5E5;" colspan="6">LISTA DE VERIFICACI&Oacute;N DE INGRESO</td></tr>
						<?	

							$cons3="select pregunta,obligatorio from salud.preguntasingreso where compania='$Compania[0]' AND area = '$area' ";
							$res3=ExQuery($cons3);
							if(ExNumRows($res3)>0){?>
								<tr><td colspan="6">&nbsp;</td></tr>
							<?	$cont=1;
								while($fila3=ExFetch($res3)){?>             
									<tr><td colspan="5"><? echo $fila3[0]?></td>
									<td colspan="2" align="center">
									<select name="Pregunta[<? echo $fila3[0]?>]" id="Pregunta<? echo $cont?>">	
										<option></option>
										<option value="Si">Si</option>
										<option value="No">No</option>
									</select>
									</td></tr>
									<input type="hidden" name="<? echo $fila3[0]?>" id="Aux<? echo $cont?>" value="<? echo $fila3[1]?>"> 
						<?			$cont++;
								}
							}
							else{?>    
								<tr>
									<td style="font-weight:bold;color:#000;text-align:center;" colspan="6">No sen han ingresado preguntas de verificacion de Ingreso</td></tr>
						<?	}?>    
							<tr>
								<td  class="encabezado2HorizontalInvertido" style="background-color:#E5E5E5;" colspan="6">NOTIFICACIONES</td></tr>
						<?	$cons4="select alerta,fechaini,fechafin,numalert from salud.tmpalertasingreso 
							where compania='$Compania[0]' and usuario='$usuario[1]' and cedula='$Ced' and numservicio=$fila[5] and tmpcod='$TMPCOD'";
							//echo $cons4;
							$res4=ExQuery($cons4);
							if(ExNumRows($res4)>0){
								while($fila4=ExFetch($res4)){?>
									<tr align="center" onMouseOver="this.bgColor='#AAD4FF'" onMouseOut="this.bgColor=''">
										<td colspan="4" align="left"><? echo $fila4[0]?></td>
										<td colspan="2"><strong>Desde:</strong> <? echo $fila4[1]?> <!--<strong>Hasta:</strong> <? echo $fila4[2]?>-->
										<!--<img src="/Imgs/b_drop.png" style="cursor:hand" title="Eliminar Alerta" onClick="FORMA.Eliminar.value='1';FORMA.numalert.value='<? echo $fila[3]?>';FORMA.submit();" />-->
										</td>               
									</tr>
							<?	}
							}?>     
							<tr><td align="center" colspan="6">Notificaci&oacute;n <input type="text" name="Alerta" onKeyDown="xLetra(this)" onKeyUp="xLetra(this)" style="width:300">
							Desde: <input type="text" readonly name="FechaIni" style="width:80" onClick="popUpCalendar(this, FORMA.FechaIni, 'yyyy-mm-dd')"> 
							<!--Hasta: <input type="text" readonly name="FechaFin" style="width:80" onClick="popUpCalendar(this, FORMA.FechaFin, 'yyyy-mm-dd')"> -->
							<button type="button" onClick="Inserta()"><img src="/Imgs/b_check.png" title="A&ntilde;adir" /></button>
							</td></tr> 
							<input type="hidden" name="FechaComp" value="<? echo $FechaComp?>"> 
							<?php if($area == "ASISTENCIAL") { ?>
							<tr><td  align="center"  bgcolor="#e5e5e5" style="font-weight:bold" colspan="6">Plan de cuidado</td></tr>
							<tr><td colspan="6"><textarea name="Notas" style="width:100%" rows="6" onKeyDown="xLetra(this)" onKeyUp="xLetra(this)"><? echo $Notas?></textarea></td></tr>
							
							<tr><td  align="center"  bgcolor="#e5e5e5" style="font-weight:bold" colspan="6">Condiciones Fisicas</td></tr>
							<tr><td colspan="6"><textarea name="Fisicas" style="width:100%" rows="6" onKeyDown="xLetra(this)" onKeyUp="xLetra(this)"></textarea></td></tr>
							
							<tr><td  align="center"  bgcolor="#e5e5e5" style="font-weight:bold" colspan="6">Condiciones Mentales</td></tr>
							<tr><td colspan="6"><textarea name="Mentales" style="width:100%" rows="6" onKeyDown="xLetra(this)" onKeyUp="xLetra(this)"></textarea></td></tr>
							
							<tr><td  align="center"  bgcolor="#e5e5e5" style="font-weight:bold" colspan="6">Observaciones</td></tr>
							<tr><td colspan="6"><textarea name="Observaciones" style="width:100%" rows="6" onKeyDown="xLetra(this)" onKeyUp="xLetra(this)"></textarea></td></tr>
							
							<tr><td  align="center"  bgcolor="#e5e5e5" style="font-weight:bold" colspan="6">Acompañante</td></tr>
							<tr><td colspan="6"><textarea name="Acompanante" style="width:100%" rows="6" onKeyDown="xLetra(this)" onKeyUp="xLetra(this)"></textarea></td></tr>
							<?php } ?>
							<tr>
								<td style="text-align:center;" colspan="6">
									<input type="submit" class="boton2Envio" value="Ingresar" name="Ingresar">
									<input type="button" class="boton2Envio" value="Cancelar" onClick="Salir()">
								</td>
							</tr>
						</table>

						<input type="hidden" name="Ced" value="<? echo $Ced?>">
						<input type="hidden" name="Cancelar" value="">
						<input type="hidden" name="Ambito" value="<? echo $Ambito?>">
						<input type="hidden" name="NumServ" value="<? echo $fila[5]?>">
						<input type="hidden" name="Anadir" value="">
						<input type="hidden" name="TMPCOD" value="<? echo $TMPCOD?>">
						<input type="hidden" name="Eliminar" value="">
						<input type="Hidden" name="DatNameSID" value="<? echo $DatNameSID?>">
					</form> 
				</div>
			</body>
			
		</html>
