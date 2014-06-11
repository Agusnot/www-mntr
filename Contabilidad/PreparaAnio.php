			<?
				if($DatNameSID){session_name("$DatNameSID");}
				session_start();
				include("Funciones.php");
				include_once("General/Configuracion/Configuracion.php");
				$ND=getdate();
				if(!$AnioSel){$AnioSel=$ND[year];}
				if($Iniciar)
				{
					while (list($val,$cad) = each ($BD)) 
					{
						$AnioAnt=$AnioSel-1;
						$cons="Select * from $val";
						$res=ExQuery($cons);
						for($i=0;$i<=ExNumFields($res)-1;$i++)
						{
							$Campos=$Campos.ExFieldName($res,$i).",";
						}
						$Campos=substr($Campos,0,strlen($Campos)-1);
						$Campos2=str_replace("anio","'$AnioSel'",$Campos);
						$cons2="Insert into $val ($Campos) Select $Campos2 from $val where Compania='$Compania[0]' and Anio='$AnioAnt'";
						$Campos="";$Campos2="";
						$res2=ExQuery($cons2);echo ExError($res2);
						$NumTablas++;
					}
			?>
					<script language="javascript">
						alert("Se cargaron <? echo $NumTablas?> tablas exitosamente");
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
		</head>
		
		<body <?php echo $backgroundBodyMentor; ?>>
			
			<?php
				
					$rutaarchivo[0] = "CONTABILIDAD";
					$rutaarchivo[1] = "PROCESOS CONTABLES";
					$rutaarchivo[2] = "PREPARAR A&Ntilde;O";
					mostrarRutaNavegacionEstatica($rutaarchivo);
				
				
				?>
			<div <?php echo $alignDiv2Mentor; ?> class="div2">	
			
				<form name="FORMA">
				
					<table class="tabla2"  style="text-align:center;"  <?php echo $borderTabla2Mentor; echo $bordercolorTabla2Mentor; echo $cellspacingTabla2Mentor; echo $cellpaddingTabla2Mentor; ?>>
						<tr>
							<td class='encabezado2VerticalInvertido'>A&Ntilde;O A PREPARAR</td>
							<td>
								<select name="AnioSel" onChange="document.FORMA.submit();">
									<?	
										$cons="Select Anio from Central.Anios where Compania='$Compania[0]' Order By Anio";
										$res=ExQuery($cons);
										while($fila=ExFetch($res))
										{
											if($fila[0]==$AnioSel){echo "<option selected value=$fila[0]>$fila[0]</option>";}
											else{echo "<option value=$fila[0]>$fila[0]</option>";}
										}
										$Msj="<img alt='Documento con registros' src='/Imgs/b_deltbl.png'>";
									?>
								</select>
							</td>
						</tr>
						<tr>
							<td class='encabezado2Horizontal'> DOCUMENTOS</td>
							<td class='encabezado2Horizontal'>CONTABILIDAD</td>
						</tr>

						<tr>
							<td>ESTRUCTURA PUC</td>
							<td style="text-align:center;">
								<? $cons="Select * from Contabilidad.EstructuraPUC where Compania='$Compania[0]' and Anio=$AnioSel";$res=ExQuery($cons);if(ExNumRows($res)==0){$Haga=1;?>
								<input type="Checkbox" name="BD[Contabilidad.EstructuraPUC]"><? }else{echo "$Msj";}?>
							</td>
						</tr>	

						<tr>
							<td>PLAN DE CUENTAS</td>
							<td style="text-align:center;">
								<? $cons="Select * from Contabilidad.PlanCuentas where Compania='$Compania[0]' and Anio=$AnioSel";$res=ExQuery($cons);if(ExNumRows($res)==0){$Haga=1;?>
								<input type="Checkbox" name="BD[Contabilidad.PlanCuentas]"><? }else{echo "$Msj";}?>
							</td>
						</tr>
						
						<tr>
							<td>ESTRUCTURA POR CC</td><td style="text-align:center;">
								<? $cons="Select * from Central.EstructuraxCC where Compania='$Compania[0]' and Anio=$AnioSel";$res=ExQuery($cons);if(ExNumRows($res)==0){$Haga=1;?>
								<input type="Checkbox" name="BD[Central.EstructuraxCC]"><?}else{echo "$Msj";}?>
							</td>
						</tr>
						
						
						<tr>
							<td >CENTROS DE COSTO</td>
							<td style="text-align:center;">
								<? $cons="Select * from Central.CentrosCosto where Compania='$Compania[0]' and Anio=$AnioSel";$res=ExQuery($cons);if(ExNumRows($res)==0){$Haga=1;?>
								<input type="Checkbox" name="BD[Central.CentrosCosto]"><?}else{echo "$Msj";}?></td>
						</tr>

						<tr>
							<td>BASES DE RETENCI&Oacute;N</td>
							<td style="text-align:center;">
								<? $cons="Select * from Contabilidad.BasesRetencion where Compania='$Compania[0]' and Anio=$AnioSel";$res=ExQuery($cons);if(ExNumRows($res)==0){$Haga=1;?>
								<input type="Checkbox" name="BD[Contabilidad.BasesRetencion]"><?}else{echo "$Msj";}?>
							</td>
						</tr>

						<tr>
							<td>CUENTAS DE CIERRE</td>
							<td style="text-align:center;">
								<? $cons="Select * from Contabilidad.CuentasCierre where Compania='$Compania[0]' and Anio=$AnioSel";$res=ExQuery($cons);if(ExNumRows($res)==0){$Haga=1;?>
								<input type="Checkbox" name="BD[Contabilidad.CuentasCierre]"><?}else{echo "$Msj";}?>
							</td>
						</tr>

						<tr>
							<td>CRUCE DE COMPROBANTES</td>
							<td style="text-align:center;">
								<? $cons="Select * from Contabilidad.CruzarComprobantes where Compania='$Compania[0]' and Anio=$AnioSel";$res=ExQuery($cons);if(ExNumRows($res)==0){$Haga=1;?>
								<input type="Checkbox" name="BD[Contabilidad.CruzarComprobantes]"><?}else{echo "$Msj";}?>
							</td>
						</tr>

						<tr>
							<td>CONCEPTOS DE AFECTACI&Oacute;N</td>
							<td style="text-align:center;">
								<? $cons="Select * from Contabilidad.ConceptosAfectacion where Compania='$Compania[0]' and Anio=$AnioSel";$res=ExQuery($cons);if(ExNumRows($res)==0){$Haga=1;?>
								<input type="Checkbox" name="BD[Contabilidad.ConceptosAfectacion]"><?}else{echo "$Msj";}?>
							</td>
						</tr>

						<tr>
							<td>CONCEPTOS CONTABLES</td>
							<td style="text-align:center;">
								<? $cons="Select * from Contabilidad.ConceptosPago where Compania='$Compania[0]' and Anio=$AnioSel";$res=ExQuery($cons);if(ExNumRows($res)==0){$Haga=1;?>
								<input type="Checkbox" name="BD[Contabilidad.ConceptosPago]" onClick="ConcPago.value=1"><?}else{echo "$Msj";}?>
							</td>
						</tr>
						<input type="Hidden" id="ConcPago" name="BD[Contabilidad.ConceptosPagoxCC]" value="">

						<tr>
							<td colspan="2">
								<? 
									if($Haga==1){
										?>
										<input type="submit" class="boton2Envio" name="Iniciar" value="Iniciar"><?
									}
								?>
							</td>
						</tr>
					</table>
					<input type="hidden" name="DatNameSID" value="<? echo $DatNameSID?>">
				</form>
			</div>
		</body>
	</html>	