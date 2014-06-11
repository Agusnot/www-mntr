		<?php
		if($DatNameSID){session_name("$DatNameSID");}
		session_start();
		include("Funciones.php");
		include_once("General/Configuracion/Configuracion.php");
		$ND=getdate();
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
						$rutaarchivo[0] = "CAMBIO DE CLAVE";				
						mostrarRutaNavegacionEstatica($rutaarchivo);
					?>
					<div <?php echo $alignDiv2Mentor; ?> class="div2">
						<?
						if($NuevaClave)	{
							$NuevaClave=md5($NuevaClave);		
							$cons1="Update Central.Usuarios Set clave='$NuevaClave' where Usuario='$usuario[1]'";
							$resultado1=ExQuery($cons1);echo ExError();

								$cons="Select CambioClave from Central.Compania where Nombre='$Compania[0]'";
								$res=ExQuery($cons);
								$fila=ExFetch($res);
								$Periodicidad=$fila[0];


								$FN="$ND[year]-$ND[mon]-$ND[mday]";

								$FNS=strtotime($FN);
								$FN2=strtotime("+$Periodicidad days",$FNS);
								$FN3=getdate($FN2);
								$FechaNueva="$FN3[year]-$FN3[mon]-$FN3[mday]";
								$cons2="Update Central.Usuarios set CambioClave='$FechaNueva' where Usuario='$usuario[1]'";
								$res2=ExQuery($cons2);

								echo "<span class='mensaje1'>Cambio realizado exitosamente</span>";
								?>
								<script language="JavaScript">
									parent(0).location.href=parent(0).location.href;
								</script>
								<?

					}
					else
					{
						$cons="Select Clave From Central.Usuarios Where Usuario='$usuario[1]'";
						$res=ExQuery($cons);
						$fila=ExFetch($res);
					?>
					<script language="JavaScript">

						var passwordCorrecto=false;

								function Validar(){

								if(document.FORMA.ClaveOriginal.value!=document.FORMA.ClaveOrigMD5.value){

										   document.getElementById('div_pas').innerHTML = "<font color='#FF0000'>Atencion: La clave ingresada no coincide con la registrada en el sistema</font>";
											return false;}


								 if(document.FORMA.NuevaClave.value.length<8){
											document.getElementById('div_pas').innerHTML = "<font color='#FF0000'>Atencion: La nueva clave debe ser superior a 8 caracteres</font>";
											return false;
																					}
								 if(document.FORMA.NuevaClave.value!=document.FORMA.Confirmacion.value){
										   document.getElementById('div_pas').innerHTML = "<font color='#FF0000'>Atencion: La confirmacion no corresponde a la clave</font>";
											return false;}

								 if(document.FORMA.NewClaveMD5.value==document.FORMA.ClaveOrigMD5.value){
										  document.getElementById('div_pas').innerHTML = "<font color='#00CC00'>La nueva clave es correcta</font>";

											return false;}

										for(n=0;n<=document.FORMA.NuevaClave.value.length;n++)
								{
									if(isNaN(document.FORMA.NuevaClave.value.substr(n,1))){NoNumbers=1;}
									else{NoNumbers=0;}
								}
								 if(NoNumbers==1){
										   document.getElementById('div_pas').innerHTML = "<font color='#FF0000'>Atencion: Debe incorporar por lo menos un numero con la clave!</font>";
											return false;}
									   else{

											document.getElementById('div_pas').innerHTML = "<font color='#00CC00'>Atencion: los campos son correctos!!</font>";
											return true;
										}

							}
						</script>
						
						
						<form method="post" name="FORMA" onSubmit="return Validar()">
							<table width="400px" class="tabla1" style="margin-top:25px;margin-bottom:25px;"    <?php echo $borderTabla1Mentor; echo $bordercolorTabla1Mentor; echo $cellspacingTabla1Mentor; echo $cellpaddingTabla1Mentor; ?> >
								<tr>
									<td class="encabezado2Horizontal" colspan="2"> CAMBIO DE CLAVE</td>
								</tr>
								<tr>
									<input type="Hidden" name="ClaveOriginal" value="<?echo $fila[0]?>">
									<input type="Hidden" name="ClaveOrigMD5">
									<input type="Hidden" name="NewClaveMD5">
									<td width="140" class="encabezado2VerticalInvertido" >CLAVE ACTUAL *</td>
									<td width="12"><input name="ClaveActual" type="password" id="ClaveActual" maxlength="16"  onKeyUp="Validar(this.value)" onChange="SubFrm.location.href='SubFrmCambioClave.php?DatNameSID=<? echo $DatNameSID?>&Clave='+this.value"></td>
								</tr>
								<tr>
									<td  class="encabezado2VerticalInvertido">NUEVA CLAVE * </td>
									<td><input name="NuevaClave" type="Password" id="NuevaClave" onKeyUp="Validar(this.value)" maxlength="16" onChange="SubFrm.location.href='SubFrmCambioClave2.php?DatNameSID=<? echo $DatNameSID?>&Clave='+this.value">

										<br>
									</td></tr>
								<tr><td  class="encabezado2VerticalInvertido">CONFIRMACI&Oacute;N *</td>
								<td><input type="Password" name="Confirmacion" id="Confirmacion" maxlength="16" onKeyUp="Validar(this.value)"></td></tr><br>
								</table><div id="div_pas"></div>
								<br>
								<input style="width:120px;" type="Submit" name="Guardar" class="boton2Envio" value="Guardar cambios">
								</center>
								<input type="hidden" name="DatNameSID" value="<? echo $DatNameSID?>">
						</form>

					<iframe name="SubFrm" id="SubFrm" src="SubFrmCambioClave.php?DatNameSID=<? echo $DatNameSID?>" style="visibility:hidden"></iframe>
					<?
					}
					?>
				</div>	
			</body>
	</html>
