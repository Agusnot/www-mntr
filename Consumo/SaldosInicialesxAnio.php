		<?
			if($DatNameSID){session_name("$DatNameSID");}
			session_start();
			include ("Funciones.php");
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
			<script language='javascript' src="/Funciones.js"></script>
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
		</head>	
		<body>
			<div align="center">
				<?
					if($Guardar){
						$cons = "Select Anio from Consumo.SaldosInicialesxAnio where 
						AutoId='$AutoId' and AlmacenPpal='$AlmacenPpal' and Compania='$Compania[0]'";
						$res = ExQuery($cons);
						$f=0;
						while($fila = ExFetch($res))
						{
							if($fila[0] == $Anio){if(!$Editar){$f=1;}}
						}
						if($f==0)
						{
							if(!$Editar)
							{
								$cons = "Insert into Consumo.SaldosInicialesxAnio
										(Compania,AlmacenPpal,AutoId,Anio,Cantidad,VrUnidad,VrTotal) values
										('$Compania[0]','$AlmacenPpal','$AutoId','$Anio','$SaldoIni','$VrUnidad','$VrTotal')";
							}
							else
							{
								$cons = "Update Consumo.SaldosInicialesxAnio set 
										Anio='$Anio',Cantidad='$SaldoIni',VrUnidad='$VrUnidad',VrTotal='$VrTotal'
										where Anio='$Aniox' and AlmacenPpal='$AlmacenPpal' and Compania='$Compania[0]' and AutoId='$AutoId'";
							}
							$res = ExQuery($cons);echo ExError();
						}
						else echo "<span style='mensaje1'>El a&ntilde;o que esta tratando de ingresar ya se ha ingresado anteriormente</span>";
						$Nuevo = 0;
						$Editar = 0;
					}

						$cons = "Select Anio, Cantidad, VrUnidad, VrTotal
						from Consumo.SaldosInicialesxAnio where AutoId = '$AutoId' and Compania = '$Compania[0]' and AlmacenPpal='$AlmacenPpal'
						order by Anio Desc";
						$res = ExQuery($cons);
						if(ExNumRows($res)==0){$Nuevo=1;}

					if(!$Nuevo)	{
							?>
							<table class="tabla1"  <?php echo $borderTabla1Mentor; echo $bordercolorTabla1Mentor; echo $cellspacingTabla1Mentor; echo $cellpaddingTabla1Mentor; ?>>
								<tr>
									<td class="encabezado1Horizontal">A&Ntilde;O</td>
									<td class="encabezado1Horizontal">SALDO INICIAL</td>
									<td class="encabezado1Horizontal">VALOR UNIDAD</td>
									<td class="encabezado1Horizontal">VALOR TOTAL</td>
									<td class="encabezado1Horizontal" colspan="2"> &nbsp; </td>
								</tr>
								<?php
								$b=0;
								while($fila=ExFetch($res))	{
									echo "<tr>";
										echo "<td style='text-align:center;'>$fila[0]</td>";
										echo "<td style='text-align:center;'>".number_format($fila[1],2)."</td>";
										echo "<td style='text-align:center;'>".number_format($fila[2],2)."</td><td>".number_format($fila[3],2)."</td>";	
									if($b==0){
										?>
										<td>
											<img src="/Imgs/b_tblops.png" style=" cursor: hand" title="Lotes" 
											 onClick="parent.AbrirLotes('<? echo $AlmacenPpal?>','<? echo $AutoId?>','<? echo $fila[1]?>','Saldo Inicial','')" />
										</td>
										<td><a href="SaldosInicialesxAnio.php?DatNameSID=<? echo $DatNameSID?>&Anio=<? echo $fila[0]; ?>&AlmacenPpal=<? echo $AlmacenPpal ?>&AutoId=<? echo $AutoId?>&Editar=1&Nuevo=1">
											<img border="0" src="/Imgs/b_edit.png" />
											</a>
										</td>
										<?
									}
									$b++;
									echo "</tr>";
								}
								?>
							</table>
						
				
						<script language="javascript">
							function Validar()
							{
								var b=0;
								if(FORMA.SaldoIni.value==""){alert("Campo Saldo Inicial sin diliegnciar");b=1;}
								else{if(FORMA.VrUnidad.value==""){alert("Campo Valor Unidad sin diliegnciar");b=1;}}
								if(FORMA.VrTotal.value=="NaN"){alert("Hay campos no numericos");b=1;}
								if(b==1)return false;
							}
							function Validar2()
							{
									
							}
						</script>
						<form method="post" onSubmit="return Validar2()">
						<input type="hidden" name="AutoId" value="<? echo $AutoId; ?>" />
						<input type="Hidden" name="AmacenPpal" value="<? echo $AlmacenPpal; ?>" />
						<input type="Hidden" name="Editar" value="<? echo $Editar;?>" />
						<input type="hidden" name="DatNameSID" value="<? echo $DatNameSID?>" />
						<input type="submit" name="Nuevo" class="boton2Envio" value="Nuevo" />
						</form>
				<?
					}
					else
					{
						if($Editar)	{
							$cons = "Select Anio,Cantidad,VrUnidad,VrTotal from Consumo.SaldosInicialesxAnio where 
							Compania='$Compania[0]' and AlmacenPpal='$AlmacenPpal' and AutoId='$AutoId' and Anio='$Anio'";
							$res = ExQuery($cons);
							$fila = ExFetch($res);
							$Anio = $fila[0]; $SaldoIni=$fila[1]; $VrUnidad=$fila[2]; $VrTotal=$fila[3];
							
						}
				?>
						<form name="FORMA" method="post" onSubmit="return Validar()">
							<input type="hidden" name="AutoId" value="<? echo $AutoId; ?>" />
							<input type="Hidden" name="AlmacenPpal" value="<? echo $AlmacenPpal; ?>" />
							<input type="Hidden" name="Editar" value="<? echo $Editar; ?>" />
							<input type="Hidden" name="Aniox" value="<? echo $Anio?>" />
							
							<table class="tabla1"style="margin-top:25px;margin-bottom:25px;"  <?php echo $borderTabla1Mentor; echo $bordercolorTabla1Mentor; echo $cellspacingTabla1Mentor; echo $cellpaddingTabla1Mentor; ?>>
								<tr>
									<td class="encabezado1Horizontal">A&Ntilde;O</td>
									<td class="encabezado1Horizontal">SALDO INICIAL</td>
									<td class="encabezado1Horizontal">VALOR UNIDAD</td>
									<td class="encabezado1Horizontal">VALOR TOTAL</td>
									<td class="encabezado1Horizontal">&nbsp;</td>
								</tr>
								<tr>
									<td style="text-align:center;">
										<select name="Anio">
											<?
												if(!$Editar){ $AdCon = "and Anio not in (Select Anio from Consumo.SaldosInicialesxAnio 
															where Compania='$Compania[0]' and AlmacenPpal='$AlmacenPpal' and AutoId='$AutoId')";}
												$cons1 = "Select Anio from Central.Anios where Compania='$Compania[0]' $AdCon 
												Order By Anio Desc";
												$res1 = ExQuery($cons1);
												while($fila1=ExFetch($res1))
												{
													if($Editar)
													{
														if($Anio == $fila1[0]){ echo "<option selected value='$fila1[0]'>$fila1[0]</option>'";}
													}
													else
													{	
														$AnioNick=getdate();
														if($AnioNick[year] == $fila1[0]){ echo "<option selected value='$fila1[0]'>$fila1[0]</option>'";}
														else{ echo "<option value='$fila1[0]'>$fila1[0]</option>'";}
													}
												}
											?>
										</select>
									</td>
									<td style="text-align:center;">
										<input type="text" name="SaldoIni" maxlength="7" size="7" style="text-align:right" value="<? echo $SaldoIni;?>" 
										onchange="FORMA.VrTotal.value=FORMA.SaldoIni.value*FORMA.VrUnidad.value" 
										onKeyUp="xNumero(this)" onKeyDown="xNumero(this)" onBlur="campoNumero(this)"/>
									</td>
									<td style="text-align:center;">$<input type="text" name="VrUnidad" maxlength="7" size="7" style="text-align:right" value="<? echo $VrUnidad;?>" 
										onchange="FORMA.VrTotal.value=FORMA.SaldoIni.value*FORMA.VrUnidad.value" 
										onKeyUp="xNumero(this)" onKeyDown="xNumero(this)" onBlur="campoNumero(this)"/>
									</td>
									<td style="text-align:center;">$<input type="text" name="VrTotal" maxlength="7" size="7" style="text-align:right" value="<? echo $VrTotal?>" 
										readonly="yes" />
									</td>
									<td>
										<button type="submit" name="Guardar"><img src="/Imgs/b_save.png"></button>
									</td>
								</tr>
						</form>
				<?
					}
				?>
				</table>
				<input type="button" onClick="CerrarThis();parent.document.FORMA.submit();" class="boton2Envio" value="Cerrar">
				<iframe id="FrameOpener" name="FrameOpener" style="display:none" frameborder="0" height="1" style="border:#e5e5e5 ridge"></iframe>
			</div>
		</body>
	</html>	
