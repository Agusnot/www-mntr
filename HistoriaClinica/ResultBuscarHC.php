		<?
			if($DatNameSID){session_name("$DatNameSID");}
			session_start();
			ini_set("memory_limit","512M");
			include("Funciones.php");
			include_once("General/Configuracion/Configuracion.php");
			$ND=getdate();
			if($ND[mon]<10){$cero1='0';}else{$cero1='';}
			if($ND[mday]<10){$cero2='0';}else{$cero2='';}	
			$FechaComp="$ND[year]-$cero1$ND[mon]-$cero2$ND[mday]";
			//echo $PrimApe;
			$cons="select nombre,usuario from central.usuarios";
			$res=ExQuery($cons);
			while($fila=ExFetch($res))
			{
				$Meds[$fila[1]]	=$fila[0];
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
			<style>
				a{color:blue;text-decoration:none;}
				a:hover{text-decoration:underline;}
				.Estilo1 {
					color: #0000FF;
					font-weight: bold;
				}
				.Estilo2 {
					color: #FF0000;
					font-weight: bold;
				}
			</style>
		</head>	
	
		<body <?php echo $backgroundBodyMentor; ?>>
			
			<div <?php echo $alignDiv3Mentor; ?> class="div3">	


				<?
				if($UnidadIraLista){$Pabellon=$UnidadIraLista;$Buscar=1;}
					if($Buscar){
					
						$cons="select identificacion,primape,segape,primnom,segnom from central.terceros where tipo='Asegurador' and compania='$Compania[0]'";
						$res=ExQuery($cons);
						while($fila=ExFetch($res)){
							$Aseguradores [$fila[0]]= "$fila[1] $fila[2] $fila[3] $fila[4]";
						}
						$cons="select identificacion,eps from central.terceros where tipo='Paciente' and compania='$Compania[0]'";
						$res=ExQuery($cons);
						while($fila=ExFetch($res)){
							$AseguradoresxPaciente [$fila[0]]= $Aseguradores[$fila[1]];
						}
						//echo $Medtte;
						if($PrimApe){$PA="and primape ilike '$PrimApe%'";}
						if($SegApe){$SA="and segape ilike '$SegApe%'";}
						if($PrimNom){$PN="and primnom ilike '$PrimNom%'";}	
						if($SegNom){$SN="and segnom ilike '$SegNom%'";}
						if($Cedula){$C="and identificacion ilike '$Cedula'";}		
						if($Ambito){$Serv2=",salud.servicios";
									$Serv1="and servicios.compania='$Compania[0]' and terceros.identificacion=servicios.cedula and tiposervicio ilike '$Ambito%' and servicios.estado='AC'";}
						if($Pabellon){$U=",salud.pacientesxpabellones"; 
									$U2="and pacientesxpabellones.compania='$Compania[0]' and pacientesxpabellones.cedula=terceros.identificacion and pabellon ilike '$Pabellon%' 
										and pacientesxpabellones.estado='AC'";}
						if($Medtte){
							
							if(!$Ambito){$M2=",salud.servicios"; 
										$M="and servicios.compania='$Compania[0]' and terceros.identificacion=servicios.cedula and medicotte ilike '$Medtte%' and servicios.estado='AC'";}
							else{$M="and medicotte ilike '$Medtte%'";}
						}
						if($EnttidadID){
							$EPS="and eps = '$EnttidadID' ";
						}
						if($Clinica){
							$CLI="and clinica ilike '$Cedula'";
							if(!$Ambito){$Cli2=",salud.servicios"; 
										$Cli="and servicios.compania='$Compania[0]' and terceros.identificacion=servicios.cedula and clinica ilike '$Clinica%' 
										and servicios.estado='AC'";
							}
							else{$Cli=" and clinica ilike '$Clinica%' ";}
						}
						$cons="select identificacion,primape,segape,primnom,segnom,eps from central.terceros $Serv2 $U $M2 $Cli2
						where terceros.compania='$Compania[0]' and (tipo='Paciente' or tipo='Empleado') $Serv1 $PA $SA $PN $SN $C $U2 $M $Cli $EPS
						group by PrimApe,SegApe,PrimNom,SegNom,identificacion,eps
						Order By PrimApe,SegApe,PrimNom,SegNom";		
						//echo $cons;
						$resultado = ExQuery($cons,$conex);echo ExError();

						if(ExNumRows($resultado)==0)
						{			
							echo "<div align='center'>";
								echo "<span class='mensaje1' style='font-size:14px;'>No existen registros coincidentes con el criterio de b&uacute;squeda </span>";
									$PermiteCrear=1;
									if($PermiteCrear==1){
										$Paciente="";
										echo "<br> <br>";
										echo "<span style='margin-top:25px;margin-bottom:25px;'>";
											echo "<p style='font-size:14px;'>";
												echo "<a class='link1' style='font-size:16px;'  target='_top' href='/HistoriaClinica/HistoriaClinica.php?DatNameSID=$DatNameSID&cedula=$Cedula'>Crear Registro</a>";
											echo "</p>";
										echo "</span>";	
									}
							echo "</div>"	;
						}
						elseif(ExNumRows($resultado)==1)
						{			
							$fila=ExFetch($resultado);
							$Paciente[1]=$fila[0];
							$n=1;
							for($i=1;$i<=ExNumFields($resultado);$i++)
							{
								$n++;
								$Paciente[$n]=$fila[$i];
							}

							$consA="Select Mensaje from HistoriaClinica.AlertasHC where cedula='$Paciente[1]' and Compania='$Compania[0]'";
							$resultadoA=ExQuery($consA);
							$filaA=ExFetch($resultadoA);
							if($filaA[0]){
							?>
								<script language="JavaScript">
									alert("<?echo $filaA[0]?>");
								</script>
							<?
							} $Pacie=$Paciente[1]; ?>
							<script language='JavaScript'>
							parent.parent.location.href='HistoriaClinica.php?DatNameSID=<? echo $DatNameSID?>&Pacie=<? echo $Pacie?>';
							</script>
							<?
						}
						elseif(ExNumRows($resultado)>1)
						{

							$consPxS="select cedula,entidad,(primape || ' '  || segape || ' ' || primnom || ' ' || segnom) as nom from salud.pagadorxservicios,salud.servicios,central.terceros 
							where pagadorxservicios.compania='$Compania[0]' and servicios.compania='$Compania[0]' and servicios.numservicio=pagadorxservicios.numservicio and 
							servicios.estado='AC' and '$FechaComp'>=fechaini and (fechafin>='2011-04-20' or fechafin is null) and terceros.compania='$Compania[0]' and identificacion=entidad";
							//echo $consPxS;
							$resPxS=ExQuery($consPxS);
							while($filaPxS=ExFetch($resPxS)){
								$Pagador[$filaPxS[0]]=$filaPxS[2];				
							}
							$consCIE="select codigo,diagnostico from salud.cie";
							$resCIE=ExQuery($consCIE);
							while($filaCIE=ExFetch($resCIE))
							{
								$CIE[$filaCIE[0]]=$filaCIE[1];
							}	
							$consCama="select unidad,idcama,nombre from salud.camasxunidades where compania='$Compania[0]'";
							$resCama=ExQuery($consCama);
							while($filaCama=ExFetch($resCama))
							{
								$Camas[$filaCama[0]][$filaCama[1]]=$filaCama[2];
								//echo "$fila[0] qqq $fila[1] === $fila[2] <br>";
							}
							$consUnd="select pacientesxpabellones.cedula,pacientesxpabellones.pabellon,idcama from salud.pacientesxpabellones
							where pacientesxpabellones.compania='$Compania[0]' and pacientesxpabellones.estado='AC' order by numservicio asc";		
							$resUnd=ExQuery($consUnd);
							while($filaUnd=ExFetch($resUnd)){$PacxPab[$filaUnd[0]]=$filaUnd[1]; $PacxCama[$filaUnd[0]]=$filaUnd[2];}
							if($Ambito){$Amb="and tiposervicio ilike '%$Ambito%'";} if($Clinica){$Clinic=" and clinica='$Clinica'"; }
							$Serv=NULL;
							
							$consSe="select medicotte,dxserv,fechaing,estado,servicios.cedula,numservicio,tiposervicio,clinica 
							from salud.servicios where servicios.compania='$Compania[0]' and servicios.estado='AC' $Amb $Clinic
							order by numservicio asc";
							//echo $consSe;
							$resSe=ExQuery($consSe);
							
							while($filaSe=ExFetch($resSe))
							{
								//echo "$filaSe[4]<br>";				
								if($Serv[$filaSe[4]])
								{
									if($filaSe[0]){$Serv[$filaSe[4]][0]=$Meds[$filaSe[0]];}
									if($filaSe[1]){$Serv[$filaSe[4]][1]=$filaSe[1];}
									if($filaSe[2]){$Serv[$filaSe[4]][2]=$filaSe[2];}
									$Serv[$filaSe[4]][3]=$filaSe[3];
								}
								else{
									$FecIngSer=explode(" ",$filaSe[2]);
									$Serv[$filaSe[4]]	= array($Meds[$filaSe[0]],$filaSe[1],$filaSe[2],$filaSe[3],$filaSe[4],$filaSe[6],$filaSe[7]);					
								}				
							}
							?>	
							<table width='100%'class="tabla3"   style="text-align:justify;"  <?php echo $borderTabla3Mentor; echo $bordercolorTabla3Mentor; echo $cellspacingTabla3Mentor; echo $cellpaddingTabla3Mentor; ?>>
								<tr>
									<td class='encabezado2Horizontal' style="text-align:center;" colspan='2'><?php echo "USUARIOS ENCONTRADOS (".ExNumRows($resultado).")";?></td>
								</tr>
								<?php
							while($fila=ExFetchArray($resultado))
							{
								$cons1="Select * from Salud.Servicios where Estado='AC' and Cedula='$fila[1]' and Compania='$Compania[0]'";
								$res1=ExQuery($cons1);
									if(ExNumRows($res1)>0)
								{
									$fila1=ExFetchArray($res1);
									$Msj1="Paciente actualmente en " . $fila1['tiposervicio'] . " (" . $fila1['fechaing'] . ")<br>" .$fila1['medicotte'];
								}
								else
								{
									$cons1="Select * from Salud.Servicios where Cedula='$fila[4]' and Compania='$Compania[0]' Order By FechaIng Desc";
									$res1=ExQuery($cons1);echo ExError();
									if(ExNumRows($res1)>0)
									{
										$fila1=ExFetchArray($res1);
										$Msj1="ULTIMA NOVEDAD: ".$fila1['tiposervicio'] . " (" . $fila1['fechaing'] . ")<br>" .$fila1['medicotte'];
									}
									
								}

								if(!$filaHosp[0]){$Estado="PACIENTE NO HOSPITALIZADO";}
								else{$Estado="HOSPITALIZADO EN  $filaHosp[0]";}?>
								<tr onMouseOver="this.bgColor='#AAD4FF'" onMouseOut="this.bgColor=''">
								<a name="<? echo $fila['identificacion'] ?>">
									<td style="width:60">
								<? 	if(file_exists($_SERVER['DOCUMENT_ROOT']."/Fotos/Pacientes/".$fila['identificacion'].".JPG")){
										?><img src="/Fotos/Pacientes/<? echo $fila['identificacion'].".JPG";?>" style="width:60; height:80">
								<?	}
									else{?>
										<img src="/Imgs/Logo.jpg" style="width:60; height:80">	
								<?	}?>           
									</td>
									<td><font size="3"><?
									
									echo "<a href='ResultBuscarHC.php?DatNameSID=$DatNameSID&Cedula=".$fila['identificacion']."&Buscar=1'>".
									$fila['primape']." ". $fila['segape']." ". $fila['primnom']." ". $fila['segnom']."</a></font><br>".$fila['identificacion']."
									<br>".$Pagador[$fila['identificacion']]."
									<br>MED TRATANTE: ".$Serv[$fila['identificacion']][0]."
									<br>DX: ".$Serv[$fila['identificacion']][1]." - ".$CIE[$Serv[$fila['identificacion']][1]]."  
									<br>FECHA INGRESO: ".$Serv[$fila['identificacion']][2];
									if($Serv[$fila['identificacion']][3]=='AC'){
										if($Serv[$fila['identificacion']][5]){echo "<BR>".$Serv[$fila['identificacion']][5];}
										if($PacxPab[$fila['identificacion']]){echo "<BR>".$PacxPab[$fila['identificacion']];}
										echo "<br>Cama: ".$Camas[$PacxPab[$fila['identificacion']]][$PacxCama[$fila['identificacion']]];
										
										$consrojo="select fecha,hora from histoclinicafrms.tbl00004 where cedula='".$fila['identificacion']."' and fecha='".$ND[year]."-".$ND[mon]."-".$ND[mday]."' and cargo='Psiquiatra'";
										$resrojo=ExQuery($consrojo);
										if($filarojo=ExFetch($resrojo)){
											echo'<br><br><span class="Estilo2">Paciente Evolucionado por Psiquiatr&iacute;a</span>';}
											else{
												$consazul="select fecha,hora from histoclinicafrms.tbl00004 where cedula='".$fila['identificacion']."' and cargo='Psiquiatra' order by fecha desc limit 1";
												$resazul=ExQuery($consazul);
												if($filaazul=ExFetch($resazul)){
													echo'<br><br><span class="Estilo1">Paciente NO Evolucionado por Psiquiatr&iacute;a desde '.$filaazul[0].' a las '.$filaazul[1].'</span>';
												}
											}
										
										$consrojo="select fecha from histoclinicafrms.tbl00004 where cedula='".$fila['identificacion']."' and fecha='".$ND[year]."-".$ND[mon]."-".$ND[mday]."' and cargo='Psicologo'";
										$resrojo=ExQuery($consrojo);
										if($filarojo=ExFetch($resrojo)){
											echo'<br><span class="Estilo2">Paciente Evolucionado por Psicolog&iacute;a</span>';}
											else{
												$consazul="select fecha,hora from histoclinicafrms.tbl00004 where cedula='".$fila['identificacion']."' and cargo='Psicologo' order by fecha desc limit 1";
												$resazul=ExQuery($consazul);
												if($filaazul=ExFetch($resazul)){
													echo'<br><span class="Estilo1">Paciente NO Evolucionado por Psicolog&iacute;a desde '.$filaazul[0].' a las '.$filaazul[1].'</span>';
												}
											}
										
										$consrojo="select fecha from histoclinicafrms.tbl00004 where cedula='".$fila['identificacion']."' and fecha='".$ND[year]."-".$ND[mon]."-".$ND[mday]."' and cargo='Medico General'";
										$resrojo=ExQuery($consrojo);
										if($filarojo=ExFetch($resrojo)){
											echo'<br><span class="Estilo2">Paciente Evolucionado por Medicina General</span>';}
											else{
												$consazul="select fecha,hora from histoclinicafrms.tbl00004 where cedula='".$fila['identificacion']."' and cargo='Medico General' order by fecha desc limit 1";
												$resazul=ExQuery($consazul);
												if($filaazul=ExFetch($resazul)){
													echo'<br><span class="Estilo1">Paciente NO Evolucionado por Medicina General desde '.$filaazul[0].' a las '.$filaazul[1].'</span>';
												}
											}
										
										$consrojo="select fecha from histoclinicafrms.tbl00004 where cedula='".$fila['identificacion']."' and fecha='".$ND[year]."-".$ND[mon]."-".$ND[mday]."' and cargo='Trabajador Social'";
										$resrojo=ExQuery($consrojo);
										if($filarojo=ExFetch($resrojo)){
											echo'<br><span class="Estilo2">Paciente Evolucionado por Trabajo Social</span>';}
											else{
												$consazul="select fecha,hora from histoclinicafrms.tbl00004 where cedula='".$fila['identificacion']."' and cargo='Trabajador Social' order by fecha desc limit 1";
												$resazul=ExQuery($consazul);
												if($filaazul=ExFetch($resazul)){
													echo'<br><span class="Estilo1">Paciente NO Evolucionado por Trabajo Social desde '.$filaazul[0].' a las '.$filaazul[1].'</span>';
												}
											}
										
										if($Serv[$fila['identificacion']][6]){
											echo "<br>Clinica: ".$Serv[$fila['identificacion']][6];
										}
									}
									echo "<br>$Msj1								
									</td></a>";$Msj1="";//$Meds[$Serv[$fila['identificacion']]][0]
							}
							echo "</table>";
						}
					}
				?>
				<iframe scrolling="no" id="FrameOpener" name="FrameOpener" style="display:none" frameborder="0" height="1" style="border:#e5e5e5 ridge" ></iframe>
			</div>
		</body>
	</html>	