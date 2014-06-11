		<?	if($DatNameSID){session_name("$DatNameSID");}
			session_start();
			include("Funciones.php");
			include_once("General/Configuracion/Configuracion.php");
			$ND=getdate();
			if($ND[mon]<10){$C1="0";}if($ND[mday]<10){$C2="0";}
			if(!$PerIni){$PerIni="$ND[year]-$C1$ND[mon]-01";}
			if(!$PerFin){$PerFin="$ND[year]-$C1$ND[mon]-$C2$ND[mday]";}
			if($Ver)
			{?>
				<script language="javascript">
					open('ImpHCMasivaxFormato.php?DatNameSID=<? echo $DatNameSID?>&Formato=<? echo $Formato?>&TipoFormato=<? echo $TipoFormato?>&PerIni=<? echo $PerIni?>&PerFin=<? echo $PerFin?>&Sexo=<? echo $Sexo?>&Ambito=<? echo $Ambito?>&Pabellon=<? echo $Pabellon?>&Entidad=<? echo $Entidad?>&Contrato=<? echo $Contrato?>','','');
				</script>
		<?	}
		?>
		
		
		<html>
				<head>
					<?php echo $codificacionMentor; ?>
					<?php echo $autorMentor; ?>
					<?php echo $titleMentor; ?>
					<?php echo $iconMentor; ?>
					<?php echo $shortcutIconMentor; ?>
					<link rel="stylesheet" type="text/css" href="../General/Estilos/estilos.css">
					<script language="javascript">
						function Validar()
						{
							if(document.FORMA.TipoFormato.value==""){alert("Debe seleccionar el Tipo de Formato!!!");return false;}
							if(document.FORMA.Formato.value==""){alert("Debe seleccionar el Formato!!!");return false;}
							if(document.FORMA.PerIni.value==""){alert("Debe digitar la fecha inicial!!!");return false;}
							if(document.FORMA.PerFin.value==""){alert("Debe digitar la fecha final!!");return false;}
							if(document.FORMA.PerFin.value<document.FORMA.PerIni.value){alert("La fecha final debe ser mayor o igual a la fecha inicial!!!");return false;}
						}
					</script>
				</head>

				<body <?php echo $backgroundBodyMentor; ?>>
					<?php
						$rutaarchivo[0] = "HISTORIA CL&Iacute;NICA";
						$rutaarchivo[1] = "UTILIDADES";
						$rutaarchivo[2] = "IMPRESI&Oacute;N MASIVA HC";						
						$rutaarchivo[3] = "IMPRESI&Oacute;N POR FORMATO";
						mostrarRutaNavegacionEstatica($rutaarchivo);
					?>
					
					<div <?php echo $alignDiv2Mentor; ?> class="div2">
						<form name="FORMA" method="post" onsubmit="return Validar()">
							<table class="tabla2"  <?php echo $borderTabla2Mentor; echo $bordercolorTabla2Mentor; echo $cellspacingTabla2Mentor; echo $cellpaddingTabla2Mentor; ?> >	
								<tr>
									<td class="encabezado2Horizontal" colspan="4"> IMPRESI&Oacute;N MASIVA POR FORMATO</td>								
								</tr>
								<tr>
									<td class="encabezado2VerticalInvertido">TIPO DE FORMATO</td>
									<td>
									<?	//$cons="select nombre from historiaclinica.Tipoformato where compania='$Compania[0]'  ORDER BY nombre ASC";
										$cons="select especialidad from Salud.Especialidades where compania='$Compania[0]'  ORDER BY Especialidad ASC";
										$res=ExQuery($cons);?>
										<select name="TipoFormato" onChange="document.FORMA.submit()">
											<option></option>
										<?
											while($fila=ExFetch($res))
											{
												if($fila[0]==$TipoFormato){echo "<option value='$fila[0]' selected>$fila[0]</option>";}	
												else{echo "<option value='$fila[0]'>$fila[0]</option>";}	
											}	?>        
										</select>
									</td>    
								<td class="encabezado2VerticalInvertido">FORMATO</td>
								<td>
								<?	$cons="select formato from historiaclinica.formatos where compania='$Compania[0]' and tipoformato='$TipoFormato' group by formato order by formato";
									$res=ExQuery($cons);?>
									<select name="Formato" onChange="document.FORMA.submit()">
										<option></option>
									<?
										while($fila=ExFetch($res))
										{
											if($fila[0]==$Formato){echo "<option value='$fila[0]' selected>$fila[0]</option>";}	
											else{echo "<option value='$fila[0]'>$fila[0]</option>";}	
										}	?>        
									</select>
								</td>
							<tr>    
								<td class="encabezado2VerticalInvertido">PERIODO</td>
								<td>
								<input type="text" name="PerIni" value="<? echo $PerIni?>" style="width:80px;"><input type="text" name="PerFin" value="<? echo $PerFin?>" style="width:80px;">
								</td>        
								<td class="encabezado2VerticalInvertido">SEXO</td>	    
								<td>
									<select name="Sexo" onChange="document.FORMA.submit();">
										<option></option>
										<option value="F" <? if($Sexo=="F"){?> selected="selected"<? }?>>Femenino</option>
										<option value="M" <? if($Sexo=="M"){?> selected="selected"<? }?>>Masculino</option>
									</select>
								</td>
							</tr>
							<tr>
								<td class="encabezado2VerticalInvertido">PROCESO</td>
								<td>
								<?	$cons="select ambito from salud.ambitos where compania='$Compania[0]' order by ambito";
									$res=ExQuery($cons);?>
									<select name="Ambito" onChange="document.FORMA.submit()">    	
										<option></option>
									<? 	while($fila=ExFetch($res))
										{
											if($fila[0]==$Ambito){echo "<option value='$fila[0]' selected>$fila[0]</option>";}
											else{echo "<option value='$fila[0]'>$fila[0]</option>";}
										}
									?>
									</select>
								</td>   
								<td class="encabezado2VerticalInvertido">SERVICIO</td>
								<td>
								<?	if($Ambito){$Amb=" and ambito='$Ambito'"; }
									$cons="select pabellon from salud.pabellones where compania='$Compania[0]' $Amb order by pabellon";
									$res=ExQuery($cons);?>
									<select name="Pabellon" onChange="document.FORMA.submit()">    	
										<option></option>
									<? 	while($fila=ExFetch($res))
										{
											if($fila[0]==$Pabellon){echo "<option value='$fila[0]' selected>$fila[0]</option>";}
											else{echo "<option value='$fila[0]'>$fila[0]</option>";}
										}
									?>
									</select>
								</td>
							</tr>
							<tr>    
								<td class="encabezado2VerticalInvertido">ENTIDAD</td>
								<td>
								<? 	$cons="select identificacion,primape,segape,primnom,segnom from central.terceros where compania='$Compania[0]' and tipo='Asegurador' 
									order by primape,segape,primnom,segnom";
									$res=ExQuery($cons);?>
									 <select name="Entidad" onChange="document.FORMA.submit()">
										<option></option>
									<? 	while($fila=ExFetch($res))
										{
											if($fila[0]==$Entidad){echo "<option value='$fila[0]' selected>$fila[1] $fila[2] $fila[3] $fila[4]</option>";}
											else{echo "<option value='$fila[0]'>$fila[1] $fila[2] $fila[3] $fila[4]</option>";}
										}
									?>
									</select>
								</td>    
								<td class="encabezado2VerticalInvertido">CONTRATO</td>
								<td>
								<?	$cons="select contrato from contratacionsalud.contratos where compania='$Compania[0]' and entidad='$Entidad' group by contrato order by contrato";
									$res=ExQuery($cons);?>
									<select name="Contrato" onChange="document.FORMA.submit()">
										<option></option>
									<? 	while($fila=ExFetch($res))
										{
											if($fila[0]==$Contrato){echo "<option value='$fila[0]' selected>$fila[0]</option>";}
											else{echo "<option value='$fila[0]'>$fila[0]</option>";}
										}
									?>
									</select>
								</td>
							</tr>    
							<tr>
								<td colspan="4" style="text-align:center;">
									<input type="submit" name="Ver" value="Ver" class="boton2Envio">
								</td>
							</tr>    
							</table>
							<?
							if($Ver)
							{?>
								<table class="tabla2" style="margin-top:25px;margin-bottom:25px;" <?php echo $borderTabla2Mentor; echo $bordercolorTabla2Mentor; echo $cellspacingTabla2Mentor; echo $cellpaddingTabla2Mentor; ?> >	
								
								<tr>
									<td class="encabezado2Horizontal">&nbsp;</td>
									<td class="encabezado2Horizontal">NOMBRE</td>
									<td class="encabezado2Horizontal">IDENTIFICACI&Oacute;N</td>
									<td class="encabezado2Horizontal">PROCESO</td>
									<td class="encabezado2Horizontal">SERVICIO</td>
									<td class="encabezado2Horizontal">SEXO</td>
									<td class="encabezado2Horizontal">EDAD</td>
								</tr>
							<?	$cons="Select Ajuste,AgruparxHospi,Alineacion,CierreVoluntario,TblFormat,rutaformatant,Paguinacion,laboratorio from HistoriaClinica.Formatos 
								where Formato='$Formato' and TipoFormato='$TipoFormato' and Compania='$Compania[0]'";
								//echo $cons;
								$res=ExQuery($cons,$conex);
								$fila=ExFetch($res);
								$Tabla=$fila[4];
								$Alineacion=$fila[2];
								
								if($Ambito){$Amb="and ambito='$Ambito'";}
								if($Sexo){$Sex=" and sexo='$Sexo'";}
								if($Pabellon){$Pab=" and unidadhosp='$Pabellon' ";}
								if($Contrato){$Contr="and contrato='$Contrato'";}else{$Contr="";}
								
								if($Entidad)
								{
									$cons="select numservicio from salud.pagadorxservicios where pagadorxservicios.compania='$Compania[0]' 
									and numservicio in (select numservicio from histoclinicafrms.$Tabla, central.terceros
														where $Tabla.compania='$Compania[0]' and terceros.compania='$Compania[0]' and fecha>='$PerIni' and fecha<='$PerFin'
														and identificacion= $Tabla.cedula $Sex $Amb $Pab group by numservicio)
									and entidad='$Entidad' $Contr";
									$res=ExQuery($cons);
									//echo $cons;
									$banPag=0;
									while($fila=ExFetch($res))
									{
										$Pagadores[$fila[0]]=array($fila[1],$fila[2],$fila[3]);	
										if($banpag==0){$Pags="'$fila[0]'"; $banpag=1;}else{$Pags=$Pags.",'$fila[0]'";}
									}
									if($Pags){
										$PagsIn="and numservicio in ($Pags)";
									}
									else{$PagsIn="and numservicio in ('-1','-2')";}
								}
								else{$PagsIn="";}
								
								$cons="select cedula,primape,segape,primnom,segnom,ambito,unidadhosp,sexo,fecnac from histoclinicafrms.$Tabla,central.terceros 
								where $Tabla.compania='$Compania[0]' and terceros.compania='$Compania[0]' and formato='$Formato' and tipoformato='$TipoFormato'
								and fecha>='$PerIni' and fecha<='$PerFin' and identificacion=cedula $Sex $Amb $Pab $PagsIn 
								group by cedula,primape,segape,primnom,segnom,ambito,unidadhosp,sexo,fecnac order by primape,segape,primnom,segnom";
								$res=ExQuery($cons);
								//echo $consListPac;
								$Cont=1;
								while($fila=ExFetch($res))
								{?>
									<tr onMouseOver="this.bgColor='#AAD4FF'" onMouseOut="this.bgColor=''">
										<td style="text-align:left;padding-left:10px;"><? echo $Cont?></td><td><? echo "$fila[1] $fila[2] $fila[3] $fila[4]"?></td>
										<td style="cursor:hand;text-align:left;padding-left:10px;" title="Ver Historia Clinica" onClick="location.href='/HistoriaClinica/ResultBuscarHC.php?DatNameSID=<? echo $DatNameSID?>&Cedula=<? echo $fila[0]?>&Buscar=1'"><? echo $fila[0]?></td>
										<td style="text-align:center;"><? echo $fila[5]?>&nbsp;</td>
										<td style="text-align:center;"><? echo $fila[6]?>&nbsp;</td>
										<td style="text-align:center;"><? echo $fila[7]?>&nbsp;</td>
										<td style="text-align:center;"><? echo ObtenEdad($fila[8])?>&nbsp;</td>
									</tr>
							<?		$Cont++;
								}
							}
							?>
						</div>	
					</body>
		</html>