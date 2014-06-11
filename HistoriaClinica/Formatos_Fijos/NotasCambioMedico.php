		<?php
			if($DatNameSID){session_name("$DatNameSID");}
			session_start();
			include("Funciones.php");
			include_once("General/Configuracion/Configuracion.php");
			$ND=getdate();
			$Anio=$ND[year];
			//if(!$SelUnidad){$SelUnidad=$Unidad;}
			if(!$Mes){$Mes=$ND[mon];}
			if($Mes<10){$Mes="0" . $Mes;}
			if(!$Dia){$Dia=$ND[mday];}
			if($Dia<10){$Dia='0' . $Dia;}
			/*$cons="Select vistobuenojefe from salud.medicos,salud.cargos where medicos.Compania='$Compania[0]' and medicos.compania=cargos.compania
			and usuario='$usuario[1]' and vistobuenojefe=1 and medicos.cargo=cargos.cargos";	
			$res=ExQuery($cons);
			$fila=ExFetch($res);
			$PermiteNNota=$fila[0];		
			if(!$PermiteNNota)
			{
				echo "<center><font face='Tahoma' color='#0066FF' size='+2' ><b>Lo Sentimos, está sección es solo para Enfermeros Jefe!!! </b></font></center><br>";
				exit;	
				$Disa="disabled";
			}*/	
			//echo $Mes." --> ".$Dia." $Anio --> $SelUnidad<br>";
		?>
		<head>
				<?php echo $codificacionMentor; ?>
				<?php echo $autorMentor; ?>
				<?php echo $titleMentor; ?>
				<?php echo $iconMentor; ?>
				<?php echo $shortcutIconMentor; ?>
				<link rel="stylesheet" type="text/css" href="../../General/Estilos/estilos.css">
		</head>

		<body  <?php echo $backgroundBodyMentor; ?>>
			<?php
					$rutaarchivo[0] = "HISTORIA CL&Iacute;NICA";
					$rutaarchivo[1] = "CAMBIOS TURNO";										
					$rutaarchivo[2] = "NOTAS CAMBIO M&Eacute;DICO";										
					mostrarRutaNavegacionEstatica($rutaarchivo);
					
			?>
			<div <?php echo $alignDiv2Mentor; ?> class="div2">	
				<form name="FORMA" method="post">
					<input type="hidden" name="DatNameSid" value="<? echo $DatNameSID?>">
					
					<table  class="tabla2" style="margin-top:25px;margin-bottom:25px;"   <?php echo $borderTabla2Mentor; echo $bordercolorTabla2Mentor; echo $cellspacingTabla2Mentor; echo $cellpaddingTabla2Mentor; ?>>
						<tr>
							<td colspan="5" class="encabezado2Horizontal"> NOTAS DE CAMBIO M&Eacute;DICO <? echo $SelUnidad?></td>
						</tr>
						<tr>
						 <td class="encabezado2VerticalInvertido">MES:</td>
						 <td>
								<select name="Mes" onChange="FORMA.submit();">
								<?	$cons="Select numero,mes from central.meses";	
								$res=ExQuery($cons);
								while($fila=ExFetch($res))
								{
									if($fila[0]==$Mes){echo "<option value='$fila[0]' selected>$fila[1]</option>";}
									else{echo "<option value='$fila[0]'>$fila[1]</option>";}
								}
								?>
								</select>
						</td>
							<td class="encabezado2VerticalInvertido">D&Iacute;A</td>	
							<td>
							<select name='Dia' onChange="FORMA.submit();">	
							<?
							$UltDia=UltimoDia($ND[year],$Mes);
							for($i=1;$i<=$UltDia;$i++)
							{
								if($i==$Dia)
								{echo "<option value=$i selected>$i</option>";}
								else
								{echo "<option value=$i>$i</option>";}
							}
							?>
							</select>
							</td>
							<!--<td>Unidad</td>
							<td>
							<select name="SelUnidad" onChange="FORMA.submit();">
							<option value="">-Seleccione servicio-</option>
							<?
							/*$cons="select pabellon from salud.pabellones where compania='$Compania[0]' order by Pabellon";
							$res=ExQuery($cons);	
							while($fila=ExFetch($res))
							{
								if($fila[0]==$SelUnidad){echo "<option selected value='$fila[0]'>$fila[0]</option>";}
								else{echo "<option value='$fila[0]'>$fila[0]</option>";}
							}*/
							?>
							</select>	
							</td>-->
							<td style="text-align:center;">
								<input type="Button" class="boton2Envio" value="Agregar Registro" onClick="location.href='NuevaNotaCambioMedico.php?&DatNameSID=<? echo $DatNameSID?>&SelUnidad=<? echo $SelUnidad?>'" title="Crear Nota con Fecha de Hoy" <? echo $Disa;?>>				</td>
							</tr>
					</table>
					
					<table width="100%" class="tabla2" style="margin-top:25px;margin-bottom:25px;"  <?php echo $borderTabla2Mentor; echo $bordercolorTabla2Mentor; echo $cellspacingTabla2Mentor; echo $cellpaddingTabla2Mentor; ?>>
						<?
						$cons = "Select fecha,usuario,nota,unidad From HistoriaClinica.NotasCambioMedico where Compania='$Compania[0]' and date(Fecha)='$Anio-$Mes-$Dia' Order By Fecha Desc;";
						//echo $cons;
						$res = ExQuery($cons);
						while($fila=ExFetch($res))
						{
							$fila[2]=str_replace("\r\n","<br>",$fila[2]);
							?>
							<tr>
								<td class="encabezado2HorizontalInvertido">CREADA POR:<? echo " $fila[1] - $fila[0]";?></td>
							</tr>
							<tr>
								<td style='text-align:justify;padding-top:5px;padding-bottom:5px;padding-left:10px;padding-right:10px;'><? echo $fila[2];?></td>
							</tr>
						<?
						}?>
					</table>
				</form>
			</div>	
		</body>
	</html>	