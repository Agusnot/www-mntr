		<?
			if($DatNameSID){session_name("$DatNameSID");}
			session_start();
			include("Funciones.php");
			include_once("General/Configuracion/Configuracion.php");
			
			if($Guardar){
				$cons="Delete from Alertas.AlertasxModulos where Id='$Id' and compania='$Compania[0]'";
				$res=ExQuery($cons);
					if (count($Option) > 0){
						while (list($val,$cad) = each ($Option)){
							$Cond=split("_",$val);
							$Modulo=$Cond[1];$Madre=$Cond[0];
							if(!$Modulo){$Modulo=$Madre;$Madre="";}
							$cons="Insert into Alertas.AlertasxModulos(Id,Modulo,Madre,compania) values ('$Id','$Modulo','$Madre','$Compania[0]')";
							$res=ExQuery($cons);			
						}
					}	
				?>
				<script language="javascript">
					alert("Las Alertas se han reprogramado Correctamente");
					window.close();
				</script>
				<?
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
				
				<script language="JavaScript">
					function Marcar()
					{
						if(document.FORMA.Marcacion.checked==1){MarcarTodo();}
						else{QuitarTodo();}
					}

					function MarcarTodo()
					{
						for (i=0;i<document.FORMA.elements.length;i++) 
						if(document.FORMA.elements[i].type == "checkbox") 
						document.FORMA.elements[i].checked=1 
					}
					function QuitarTodo()
					{
						for (i=0;i<document.FORMA.elements.length;i++) 
						if(document.FORMA.elements[i].type == "checkbox") 
						document.FORMA.elements[i].checked=0
					}
				</script>
			</head>
			
			<body <?php echo $backgroundBodyMentor; ?> >
				<?php
						$rutaarchivo[0] = "ADMINISTRADOR";
						$rutaarchivo[1] = "ALERTAS";				
						$rutaarchivo[2] = "MODULOS X ALERTA";
							
						mostrarRutaNavegacionEstatica($rutaarchivo);
				?>
				<form name="FORMA" method="post">
						<div align="center">
							<table class="tabla2" style="text-align:left;"  <?php echo $borderTabla2Mentor; echo $bordercolorTabla2Mentor; echo $cellspacingTabla2Mentor; echo $cellpaddingTabla2Mentor; ?>  >
							<tr>
								<td> &nbsp;</td>
								<td style="text-align:center;"><input type="checkbox" name="Marcacion" onClick="Marcar()"></td>
							</tr>
							<?
							$cons="Select Perfil from Central.AccesoxModulos 
							where Nivel=0 AND ind_estado = '0' Order By Id";
							$res=ExQuery($cons);
							while($fila=ExFetch($res))
							{
								$consV1="Select * from Alertas.AlertasxModulos where Id='$Id' and Modulo='$fila[0]' and compania='$Compania[0]'";
								$resV1=ExQuery($consV1);
								if(ExNumRows($resV1)==1){$Check1="checked";}else{$Check1="";}
								echo "<tr><td class='encabezado2Horizontal'>$fila[0]</td><td ><input name='Option[$Madre_$fila[0]]' $Check1 type='checkbox'></td></tr>";
								$cons1="Select Perfil from Central.AccesoxModulos where AccesoxModulos.Madre='$fila[0]' and ModuloGr='$fila[0]' AND ind_estado = '0' Order By Id";
								$res1=ExQuery($cons1);
								while($fila1=ExFetch($res1))
								{
									$consV2="Select * from Alertas.AlertasxModulos where Id='$Id' and Modulo='$fila1[0]' and Madre='$fila[0]' and compania='$Compania[0]'";
									$resV2=ExQuery($consV2);
									if(ExNumRows($resV2)==1){$Check2="checked";}else{$Check2="";}

									echo "<tr><td><ul>$fila1[0]</td><td><input $Check2 type='checkbox' name='Option[$fila[0]_$fila1[0]]'></td></tr>";

									$cons2="Select Perfil from Central.AccesoxModulos
									where AccesoxModulos.Madre='$fila1[0]' and ModuloGr='$fila[0]' Order By Id";
									$res2=ExQuery($cons2);
									while($fila2=ExFetch($res2))
									{
										$consV3="Select * from Alertas.AlertasxModulos where Id='$Id' and Modulo='$fila2[0]' and Madre='$fila[0]' and compania='$Compania[0]'";
										$resV3=ExQuery($consV3);
										if(ExNumRows($resV3)==1){$Check3="checked";}else{$Check3="";}
										echo "<tr><td><ul><ul>$fila2[0]</td><td><input $Check3 type='checkbox' name='Option[$fila[0]_$fila2[0]]'></td></tr>";

										$cons3="Select Perfil from Central.AccesoxModulos
										where AccesoxModulos.Madre='$fila2[0]' and ModuloGr='$fila[0]' Order By Id";
										$res3=ExQuery($cons3);
										while($fila3=ExFetch($res3))
										{
											$consV4="Select * from Alertas.AlertasxModulos where Id='$Id' and Modulo='$fila3[0]' and Madre='$fila[0]' and compania='$Compania[0]'";
											$resV4=ExQuery($consV4);
											if(ExNumRows($resV4)==1){$Check3="checked";}else{$Check3="";}
											echo "<tr><td><ul><ul><ul>$fila3[0]</td><td><input $Check3 type='checkbox' name='Option[$fila[0]_$fila3[0]]'></td></tr>";
										}		
									}
								}
							}
						?>
						</table>
					</div>	
					<br>
					<center>
					<input type="hidden" name="Usuario" value="<? echo $Usuario?>">
					<input type="submit" name="Guardar" class="boton2Envio" value="Guardar">
					<input type="hidden" name="DatNameSID" value="<? echo $DatNameSID?>">
				</form>
			</body>