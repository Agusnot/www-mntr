<?
	if($DatNameSID){session_name("$DatNameSID");}
	session_start();
	include("Funciones.php");
	include_once("General/Configuracion/Configuracion.php");
	$ND=getdate();

	if(!$DiaTrabajo){$DiaTrabajo=$ND[mday];}
	if(!$AnioTrabajo){$AnioTrabajo=$ND[year];}
	if(!$MesTrabajo){$MesTrabajo=$ND[mon];}
	$AnioI=$AnioTrabajo;
	$MesI=$MesTrabajo;
	$DiaI=$DiaTrabajo;

	$cons="Select Anio from Central.Anios where Compania='$Compania[0]' order by Anio desc";
	$res=ExQuery($cons);
	if(ExNumRows($res)==0)
	{
		echo "<em>No hay años configurados, no puede registrar movimientos<em>";
		exit;
	}
?>

	<html>
		<head>
			<title>
				<?php echo $codificacionMentor; ?>
				<?php echo $autorMentor; ?>
				<?php echo $titleMentor; ?>
				<?php echo $iconMentor; ?>
				<?php echo $shortcutIconMentor; ?>
				<link rel="stylesheet" type="text/css" href="../General/Estilos/estilos.css">
			</title>
		</head>

		<body>
			
				<form name="FORMA" target="Abajo" action="ListaMovimiento.php">
					<?php
						$rutaarchivo[0] = "CONTABILIDAD";
							if(!empty($_GET['Tipo'])){
								$rutaarchivo[] = $_GET['Tipo'];	
							}
							
							if(!empty($_GET['Comprobante'])){
								$rutaarchivo[] = $_GET['Comprobante'];	
							}
												
						mostrarRutaNavegacionEstatica($rutaarchivo);
				?>
					<div align="center">
						<table class='tabla1'  <?php echo $borderTabla1Mentor; echo $bordercolorTabla1Mentor; echo $cellspacingTabla1Mentor; echo $cellpaddingTabla1Mentor; ?> >
							<tr>
								<td class="encabezado2Horizontal">VISUALIZAR PERIODO</td>
								<td class="encabezado2Horizontal">COMPROBANTE</td>
								<td class="encabezado2Horizontal" colspan="2"> &nbsp;</td>
							</tr>
							<tr>
								<td>
									<select name="AnioI" onChange="document.FORMA.submit()">
									<?
										$cons="Select Anio from Central.Anios where Compania='$Compania[0]' order by Anio desc";
										$res=ExQuery($cons);
										while($fila=ExFetch($res))
										{
											if($AnioI==$fila[0]){echo "<option value='$fila[0]' selected>$fila[0]</option>";}
											else{echo "<option value='$fila[0]'>$fila[0]</option>";}
										}
									?>
									</select>

									<select name="MesI" onChange="document.FORMA.submit();">
									<?
										$cons="Select Mes,Numero,NumDias from Central.Meses Order By Numero";
										$res=ExQuery($cons);
										while($fila=ExFetch($res))
										{
											if($MesI==$fila[1]){echo "<option value='$fila[1]' selected>$fila[0]</option>";$NumDias=$fila[2];}
											else{echo "<option value='$fila[1]'>$fila[0]</option>";}
										}
									?>
									</select>
									
								</td>
								<td>
									<select name="Comprobante" onChange="javascript:document.FORMA.submit();">
										<?
										$cons="SELECT Comprobante FROM Contabilidad.Comprobantes WHERE TipoComprobant='$Tipo' and Compania='$Compania[0]'
										ORDER BY Comprobante";										
										$res=ExQuery($cons);
										if(ExNumRows($res)==0){
											exit;
										}
										else{
											while($fila=ExFetch($res))	{
												if(!$Comprobante){
													$Comprobante=$fila[0];
												}
												if($Comprobante==$fila[0]){
													echo "<option value='$fila[0]' selected>$fila[0]</option>";
												}
												else{
													echo "<option value='$fila[0]'>$fila[0]</option>";
												}
											}
										}

										$cons = "Select Mes From Central.CierreXPeriodos Where Compania='$Compania[0]' and UPPER(Modulo)='CONTABILIDAD' and Anio=$AnioI and Mes=$MesI";
										$res = ExQuery($cons);
										if(ExNumRows($res)==1)
										{	$Disabled=" disabled ";
										}

										?>
									</select>
									
								</td>
								
								<td>									
									<input type="hidden" name="DatNameSID" value="<? echo $DatNameSID?>">
									<input type="Button" name="Nuevo" class="boton2Envio" value="Nuevo" <? echo $Disabled?> onClick="parent.location.href='NuevoMovimiento.php?DatNameSID=<? echo $DatNameSID?>&Anio='+AnioI.value+'&Mes='+MesI.value+'&Comprobante='+Comprobante.value+'&Tipo=<?echo $Tipo?>'">
									<input type="Hidden" name="Tipo" value="<?echo $Tipo?>">
								</td>
							</tr>
						</table>
					</div>	
				</form>
				<iframe id="Busquedas" src="Busquedas.php?DatNameSID=<? echo $DatNameSID?>" frameborder="0" style="visibility:hidden"></iframe>
		</body>
	</html>
