		<?
			if($DatNameSID){session_name("$DatNameSID");}
			session_start();
			include("Funciones.php");
			include_once("General/Configuracion/Configuracion.php");			
		?>
		
		<html>
				<head>
					<?php echo $codificacionMentor; ?>
					<?php echo $autorMentor; ?>
					<?php echo $titleMentor; ?>
					<?php echo $iconMentor; ?>
					<?php echo $shortcutIconMentor; ?>
					<link rel="stylesheet" type="text/css" href="../General/Estilos/estilos.css">				
					<script language="JavaScript">
						function valor(){		
							if (document.FORMA1.TF.value==""){
								alert("Por Favor Seleccione un Formato!");
							}
							else{
								if(document.FORMA1.NewFormato.value==""){
									alert("Debe ingresar un formato nuevo!!!");
								}
								else{
									document.FORMA1.submit();
								}
							}		
						}
						
					</script>
				</head>

				<body <?php echo $backgroundBodyMentor; ?>>
					<?php
						$rutaarchivo[0] = "HISTORIA CL&Iacute;NICA";
						$rutaarchivo[1] = "CONFIGURACI&Oacute;N";
						$rutaarchivo[2] = "ADMINISTRACI&Oacute;N DE FORMATOS";
						mostrarRutaNavegacionEstatica($rutaarchivo);
					?>
					
					<div <?php echo $alignDiv2Mentor; ?> class="div2">
						<form name="FORMA2" method="post">					
							 <table width="500px" class="tabla2"  <?php echo $borderTabla2Mentor; echo $bordercolorTabla2Mentor; echo $cellspacingTabla2Mentor; echo $cellpaddingTabla2Mentor; ?>>
								<tr>
									<td class="encabezado2Horizontal" colspan="2"> ADMINISTRACI&Oacute;N DE FORMATOS </td>
								</tr>
								<tr>
									<?	//$cons="Select * from HistoriaClinica.TipoFormato where compania='$Compania[0]'  Order By nombre";									
										$cons="Select especialidad from Salud.Especialidades where compania='$Compania[0]'  Order By Especialidad";
										$res=ExQuery($cons);?>    
									<td colspan="2">      
										<select style="width:500px;" name="TF" onChange="document.FORMA2.submit()">
											<option value="">-Seleccione Tipo de Formato-</option>
												<?												
													while($fila=ExFetch($res)){
														if(($fila[0]==$TF))	{
															echo "<option selected value='$fila[0]'>$fila[0]</option>";
														}
														else {
															echo "<option value='$fila[0]'>$fila[0]</option>";
														}
																
													}
												?>
										</select>
									</td>
								</tr>
									<input type="hidden" name="DatNameSID" value="<? echo $DatNameSID?>">
								</form>
								

									 <?
										$PartEst="and estado = 'AC'";
										if($TF){$cons="Select Formato,TipoFormato from HistoriaClinica.Formatos where TipoFormato='$TF' and compania='$Compania[0]' 
										$PartEst order by Formato";}
										else{$cons="Select Formato,TipoFormato from HistoriaClinica.Formatos where TipoFormato='$TipoFormato' and compania='$Compania[0]'
										$PartEst order by Formato";}
										$res=ExQuery($cons,$conex);echo ExError();
										while($fila=ExFetch($res)){
												echo "<tr>";
													echo "<td style='background-color:#FFFFFF;text-align:left;padding-left:10px;' colspan=2>";
														if($fila[6]!=2){
															echo "<a href='Administracion/EditFormato.php?DatNameSID=$DatNameSID&NewFormato=$fila[0]&TF=$fila[1]&Edit=1'>";
														}
														echo "* $fila[0]</a>";
													echo "</td>";
												echo "</tr>";
											}
									  ?>
									  <form name="FORMA1" action="Administracion/EditFormato.php">
										<input type="Hidden" name="TF" value="<? echo $TF?>">
										<input type="hidden" name="DatNameSID" value="<? echo $DatNameSID?>">
										<tr>
										  <td><input type="Text" style="width:400px;" name="NewFormato"></td>
										 <td><Button onClick="valor()" value="G"><img src="/Imgs/HistoriaClinica/bigfolder.png"></Button></td>
										</tr> 
									  </form>
								  </table>
								 
					
				
			</body>