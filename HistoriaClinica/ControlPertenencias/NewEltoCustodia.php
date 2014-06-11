		<?php
			if($DatNameSID){session_name("$DatNameSID");}
			session_start();
			include("Funciones.php");
			include_once("General/Configuracion/Configuracion.php");
			if($Asignar){
				$ND=getdate();
				while( list($cad,$val) = each($Elto)){
					if(!$CantElto[$cad]){$CantElto[$cad]=1;}
					 $cons="insert into salud.elementoscustodia (compania,cedula,numservicio,responsable,elemento,estado,fechacustodia,usuario,nota,cantidad) 
					 values ('$Compania[0]','$Ced',$NumServ,'$usuario[1]','$cad','$EstadoElto[$cad]',
					 '$ND[year]-$ND[mon]-$ND[mday] $ND[hours]:$ND[minutes]:$ND[seconds]','$usuario[1]','$Nota[$cad]',$CantElto[$cad])";			 
					 $res=ExQuery($cons);echo ExError();
					 //echo $cons;
				}
				?><script language="javascript">location.href='ElementosCustodia.php?DatNameSID=<? echo $DatNameSID?>&Ced=<? echo $Ced?>&NumServ=<? echo $NumServ?>&Ambito=<? echo $Ambito?>&UndHosp=<? echo $UndHosp?>';</script><?
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
				<script language='javascript' src="/Funciones.js"></script>
				<script language="javascript">
					function validar()
					{		
						var ban=0;
						for (var i=0;i < document.forms["FORMA"].elements.length;i++) 
						{ 
							var elemento = document.forms[0].elements[i]; 
							if (elemento.type == "checkbox") 
							{ 
								if(elemento.checked&&elemento.name!='Todos'){ban=1;}
							} 	
						} 
						if(ban==0)
						{
							alert("Debe seleccionar almenos un Elemento!!");return false;
						}
						else{
							if(document.FORMA.Responsalbe.value=="")
							{
								alert("El registro responsable no puede quedar vacio!!!");return false;
							}  
							
						}
					}
				</script>
			</head>

		<body <?php echo $backgroundBodyMentor; ?>>
			<?php
					$rutaarchivo[0] = "HISTORIA CL&Iacute;NICA";
					$rutaarchivo[1] = "HOSPITALIZACI&Oacute;N";
					$rutaarchivo[2] = "CONTROL DE PERTENENCIAS";
					$rutaarchivo[3] = "CUSTODIA";
					$rutaarchivo[4] = "NUEVO";
					
					mostrarRutaNavegacionEstatica($rutaarchivo);
			?>	
			
			<div <?php echo $alignDiv2Mentor; ?> class="div2">
				<form name="FORMA" method="post" onSubmit="return validar()">
					
				<table class="tabla2"    <?php echo $borderTabla2Mentor; echo $bordercolorTabla2Mentor; echo $cellspacingTabla2Mentor; echo $cellpaddingTabla2Mentor; ?>>
				<?
					$cons="select elemento from salud.elementos 
					where compania='$Compania[0]' and elemento not in ( select elemento from salud.elementoscustodia where cedula='$Ced' and numservicio='$NumServ')";
					$res=ExQuery($cons);
					if(ExNumRows($res)>0)
					{	?>		
						<tr>
							<td  class='encabezado2Horizontal' colspan="2">ELEMENTO</td>
							<td  class='encabezado2Horizontal' >CANTIDAD</td>
							<td  class='encabezado2Horizontal' >ESTADO</td>
							<td  class='encabezado2Horizontal' >NOTA</td>
						</tr>
						<?	while($fila=ExFetch($res))
							{		
								?>
								<tr>
									<td><? echo $fila[0]?></td><td> <input type="checkbox" name="Elto[<? echo $fila[0]?>]"></td>
									<td style="text-align:center;">
									<input type="text" name="CantElto[<? echo $fila[0]?>]" onKeyPress="xNumero(this)" onKeyDown="xNumero(this)" 
										onKeyPress="xNumero(this)" style="width:40">
									</td>	
									<td><select name="EstadoElto[<? echo $fila[0]?>]">
											<option value="Bueno">Bueno</option>
											<option value="Regular">Regular</option>
											<option value="Malo">Malo</option>
										</select>
									</td>
									<td>
										<input type="text" name="Nota[<? echo $fila[0]?>]" onKeyDown="xLetra(this)" onKeyUp="xLetra(this)" >
									</td>                
								</tr>
								<?
							}
							?>
							<tr>
								<td colspan="5" align="center">
									<input type="submit" name="Asignar" class="boton2Envio" value="Asignar">
									<input type="button" class="boton2Envio" value="Cancelar" 
									onClick="location.href='ElementosCustodia.php?DatNameSID=<? echo $DatNameSID?>&Ced=<? echo $Ced?>&NumServ=<? echo $NumServ?>&Ambito=<? echo $Ambito?>&UndHosp=<? echo $UndHosp?>'">
								</td>
							</tr>
							<?		
							}
							else
							{
								echo "<tr><td class = 'mensaje1'>No se han asignado elementos</td></tr>";
								?>
							<td style="text-align:center;">		
								<input type="button" class="boton2Envio" value="Cancelar." 
								onClick="location.href='ElementosCustodia.php?DatNameSID=<? echo $DatNameSID?>&Ced=<? echo $Ced?>&NumServ=<? echo $NumServ?>&Ambito=<? echo $Ambito?>&UndHosp=<? echo $UndHosp?>'">
							
							</td>
						<?
					}
				?>        
				</table>
				<input type="hidden" name="Ced" value="<? echo $Ced?>">
				<input type="hidden" name="NumServ" value="<? echo $NumServ?>">
				<input type="hidden" name="Ambito" value="<? echo $Ambito?>">
				<input type="hidden" name="UndHosp" value="<? echo $UndHosp?>">
				<input type="Hidden" name="DatNameSID" value="<? echo $DatNameSID?>">
				</form>
			</div>	
		</body>
	</html>
