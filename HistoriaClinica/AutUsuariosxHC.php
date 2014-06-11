		<?
			if($DatNameSID){session_name("$DatNameSID");}
			session_start();
			include("Funciones.php");
			include_once("General/Configuracion/Configuracion.php");
		?>
		
		<script language="javascript">
			function CerrarThis()
			{
				parent.document.getElementById('FrameOpener').style.position='absolute';
				parent.document.getElementById('FrameOpener').style.top='1px';
				parent.document.getElementById('FrameOpener').style.left='1px';
				parent.document.getElementById('FrameOpener').style.width='1';
				parent.document.getElementById('FrameOpener').style.height='1';
				parent.document.getElementById('FrameOpener').style.display='none';
			}	
		</script>
		<?	
			
			if($Guardar)
			{
				$cons="Delete from Salud.UsuariosxHC where Usuario='$Usuario'";
				$res=ExQuery($cons);
				while (list($val,$cad) = each ($Option)) 
				{
					$Cond=split("_",$val);
					$Modulo=$Cond[1];$Madre=$Cond[0];
					if(!$Modulo){$Modulo=$Madre;$Madre="";}
					$cons="Insert into Salud.UsuariosxHC(Usuario,Modulo,Madre) values ('$Usuario','$Modulo','$Madre')";
					$res=ExQuery($cons);
					echo ExError($res);
				}
				?>
				<script language="javascript">
					alert("El usuario debe reabrir la historia clinica para que el cambio surta efecto");
					CerrarThis();
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
					
					function Marcar()
					{
						if(document.FORMA.Habilitar.checked==1){MarcarTodo();}
						else{QuitarTodo();}
					}
				</script>
			</head>

			<body>
				<div align="center">
					<form name="FORMA" method="post">
						<input type="hidden" name="Usuario" value="<? echo $Usuario?>" />
						
						<table width="100%" class="tabla1"  <?php echo $borderTabla1Mentor; echo $bordercolorTabla1Mentor; echo $cellspacingTabla1Mentor; echo $cellpaddingTabla1Mentor; ?>>
							<tr>
								<td style="text-align:right;padding-right:10px;">
									<button type="submit" name="Guardar"><img src="/Imgs/b_save.png" title="Guardar"></button>
									<button type="button" name="Cerrar" onClick="CerrarThis()"><img src="/Imgs/b_drop.png" title="Cerrar"></button>
								</td>
								<td style="text-align:center;">
									<input type="checkbox" name="Habilitar" title="Habilitar/Deshabilitar Todo" onClick="Marcar()" /></td>
								</td>
							</tr>
							<?
								$cons="Select Perfil from Salud.AccesoxHC where Nivel=0 Order By Id";
								$res=ExQuery($cons);
								while($fila=ExFetch($res))
								{
									$consV1="Select * from Salud.UsuariosxHC where Usuario='$Usuario' and Modulo='$fila[0]'";
									$resV1=ExQuery($consV1);
									if(ExNumRows($resV1)==1){$Check1="checked";}else{$Check1="";}
										echo "<tr>";
											echo "<td class='encabezado2Horizontal'>".strtoupper($fila[0])."</td>";
											echo "<td class='encabezado2Horizontal' style='text-align:center;' ><input name='Option[$Madre_$fila[0]]' $Check1 type='checkbox'></td></tr>";
									$cons1="Select Perfil from Salud.AccesoxHC where AccesoxHC.Madre='$fila[0]' and ModuloGR='$fila[0]' Order By Id";
									$res1=ExQuery($cons1);
									
									while($fila1=ExFetch($res1)){
										$consV2="Select * from Salud.UsuariosxHC where Usuario='$Usuario' and Modulo='$fila1[0]' and Madre='$fila[0]'";
										$resV2=ExQuery($consV2);
										if(ExNumRows($resV2)==1){$Check2="checked";}else{$Check2="";}

										echo "<tr><td><ul>$fila1[0]</td>";
										echo "<td style='text-align:center;'><input $Check2 type='checkbox' name='Option[$fila[0]_$fila1[0]]'></td></tr>";

										$cons2="Select Perfil from Salud.AccesoxHC where AccesoxHC.Madre='$fila1[0]' and ModuloGr='$fila[0]' Order By Id";
										$res2=ExQuery($cons2);
										while($fila2=ExFetch($res2))
										{
											$consV3="Select * from Salud.UsuariosxHC where Usuario='$Usuario' and Modulo='$fila2[0]' and Madre='$fila[0]'";
											$resV3=ExQuery($consV3);
											if(ExNumRows($resV3)==1){$Check3="checked";}else{$Check3="";}
											echo "<tr><td><ul><ul>$fila2[0]</td><td><input $Check3 type='checkbox' name='Option[$fila[0]_$fila2[0]]'></td></tr>";

											$cons3="Select Perfil from Salud.AccesoxHC	where AccesoxHC.Madre='$fila2[0]' and ModuloGr='$fila[0]' Order By Id";
											$res3=ExQuery($cons3);
											while($fila3=ExFetch($res3))
											{
												$consV4="Select * from Salud.UsuariosxHC where Usuario='$Usuario' and Modulo='$fila3[0]' and Madre='$fila[0]'";
												$resV4=ExQuery($consV4);
												if(ExNumRows($resV4)==1){$Check3="checked";}else{$Check3="";}
												echo "<tr><td><ul><ul><ul>$fila3[0]</td><td><input $Check3 type='checkbox' name='Option[$fila[0]_$fila3[0]]'></td></tr>";
											}		
										}
									}
								}
							?>
						</table>
						<input type="Hidden" name="DatNameSID" value="<? echo $DatNameSID?>">
					</form> 
				</div>			
			</body>
		</html>
