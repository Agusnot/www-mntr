		<?php
			if($DatNameSID){session_name("$DatNameSID");}
			session_start();
			include("Funciones.php");	
			include_once("General/Configuracion/Configuracion.php");
			include("ObtenerSaldos.php");
			$ND = getdate(); 
			if($ND[mon]<10){$Mes = "0".$ND[mon];}else{$Mes = $ND[mon];}
			if($ND[mday]<10){$Dia = "0".$ND[mday];}else{$Dia = $ND[mday];}
			$Fecha = "$ND[year]-$Mes-$Dia";
			$Hoy =date('w',mktime(0,0,0,$Mes,$Dia,$ND[year]));
			if(!$AlmacenPpal)
			{
				$cons = "Select AlmacenPpal from Consumo.AlmacenesPpales
				where Compania='$Compania[0]' and SSFarmaceutico = 1";
				$res = ExQuery($cons);
				$fila = ExFetch($res);
				$AlmacenPpal = $fila[0];
			}
			if(!$Ambito)
			{
				$cons = "Select Ambito,ConsultaExtern from Salud.Ambitos where Compania='$Compania[0]'";
				$res = ExQuery($cons);	
				$fila = ExFetch($res);
				$Ambito = "$fila[0]-$fila[1]";
			}
			$AmbitoAux = explode("-",$Ambito);
			if(!$Pabellon && $AmbitoAux[1]=="0")
			{
				$cons = "Select Pabellon from Salud.Pabellones where Compania='$Compania[0]' and Ambito = '$AmbitoAux[0]'";
				$res = ExQuery($cons);
				$fila = ExFetch($res);
				$Pabellon = $fila[0];		
			}
			if($Cantidad)
			{
				$cons="insert into salud.registromedicamentos (compania,almacenppal,numservicio,cedula,autoid,usuariocre,fechacre,hora,cantidad) 
				values ('$Compania[0]','$AlmacenPpal',$NumSer,'$Ced',$AutoId,'$usuario[1]','$ND[year]-$ND[mon]-$ND[mday] $ND[hours]:$ND[minutes]:$ND[seconds]',$Hora,$Cantidad)";
				$res = ExQuery($cons); echo ExError();
				//echo $cons;
			}
			////////////////////////////DESPACHADOS///////////////////////////////////////////////
			$cons = "Select Cedula,AutoId,FechaCre,Hora,Cantidad from Salud.RegistroMedicamentos where Compania='$Compania[0]' and AlmacenPpal='$AlmacenPpal'";
			$res = ExQuery($cons);
			while($fila=ExFetch($res))
			{
				$FechaDesp = substr($fila[2],0,10);
				if($FechaDesp == $Fecha)
				{
					$Despachados[$fila[0]][$fila[1]][$fila[3]]=$fila[4];
					$TotDespachado[$fila[0]][$fila[1]] += $fila[4];
				}
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
				<script language="javascript" src="/Funciones.js"></script>
			</head>

			<body <?php echo $backgroundBodyMentor; ?>>
				<?php
					$rutaarchivo[0] = "HISTORIA CL&Iacute;NICA";
					$rutaarchivo[1] = "REGISTRO DE MEDICAMENTOS";
					mostrarRutaNavegacionEstatica($rutaarchivo);
				?>
				<div <?php echo $alignDiv2Mentor; ?> class="div2">	
					<form name="FORMA" method="post">
						<table class="tabla2" style="margin-top:25px;margin-bottom:25px;" width="900px"  <?php echo $borderTabla2Mentor; echo $bordercolorTabla2Mentor; echo $cellspacingTabla2Mentor; echo $cellpaddingTabla2Mentor; ?>>
							<tr>
								<td class='encabezado2Horizontal'> ALMAC&Eacute;N PRINCIPAL</td>
								<td class='encabezado2Horizontal'>
									<select name="AlmacenPpal" onChange="FORMA.submit();">           
										<?	$cons = "Select AlmacenPpal from Consumo.AlmacenesPpales 
										where Compania='$Compania[0]' and
										SSFarmaceutico = 1";
										$res = ExQuery($cons);
										while($fila = ExFetch($res)){
											if($AlmacenPpal==$fila[0]){echo "<option selected value='$fila[0]'>$fila[0]</option>";}
											else{echo "<option value='$fila[0]'>$fila[0]</option>";}
										}
										?>
									</select>
								</td>
								
						<?
						if($AlmacenPpal){
							?><td class='encabezado2Horizontal'> PROCESO</td>
							<td class='encabezado2Horizontal'><select name="Ambito" onChange="FORMA.submit()">
							<? $cons = "Select Ambito,ConsultaExtern from Salud.Ambitos where Compania='$Compania[0]'  order by Ambito asc";
							$res = ExQuery($cons);
							while($fila=ExFetch($res))
							{
								if($Ambito == "$fila[0]-$fila[1]"){echo "<option selected value='$fila[0]-$fila[1]'>$fila[0]</option>";}
								else {echo "<option value='$fila[0]-$fila[1]'>$fila[0]</option>";}
							}?>
							</select></td><?
						}

							
						if($AmbitoAux[1]=="0")
						{
							?><td class='encabezado2Horizontal'>SERVICIO</td>
							<td class='encabezado2Horizontal'><select name="Pabellon" onChange="FORMA.submit()">
							<? $cons = "Select Pabellon from Salud.Pabellones where Compania='$Compania[0]' AND  Ambito = '$AmbitoAux[0]' order by Pabellon";
								$res = ExQuery($cons);
								while($fila = ExFetch($res))
								{
									if($Pabellon==$fila[0]){echo "<option selected value='$fila[0]'>$fila[0]</option>";}
									else {echo "<option value='$fila[0]'>$fila[0]</option>";}
								}?>
							</select></td><?
						}
						?>
						 </tr>
						</table>
						<?
						if($AmbitoAux[1]=="1" || $Pabellon)
						{
							//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
							switch($Hoy)
							{
								case 0: $ConsDia = " and Domingo = 1 "; break;
								case 1: $ConsDia = " and Lunes = 1 "; break;
								case 2: $ConsDia = " and Martes = 1 "; break;
								case 3: $ConsDia = " and Miercoles = 1 "; break;
								case 4: $ConsDia = " and Jueves = 1 "; break;
								case 5: $ConsDia = " and Viernes = 1 "; break;
								case 6: $ConsDia = " and Sabado = 1 "; break;
							}
							if($Pabellon && $AmbitoAux[1]=="0")	{ 
								
								$AdFromUnidad = ",Salud.PacientesxPabellones";
								$AdWhereUnidad = "and PacientesxPabellones.Cedula = PlantillaMedicamentos.CedPaciente and pacientesxpabellones.Pabellon='$Pabellon' and PacientesxPabellones.Estado='AC'
								and pacientesxpabellones.ambito='$AmbitoAux[0]' and pacientesxpabellones.compania='$Compania[0]'";
							}
							
							if(strtoupper($AmbitoAux[0]) == "SIN AMBITO"){
								$AdFromUnidad = "";
								$AdWhereUnidad = "";
								$condEstado = "";
								$condTipoServ = " and UPPER(servicios.tiposervicio)='URGENCIAS'";
								
							} else {
								$condEstado = " AND servicios.Estado = 'AC' ";
								$condTipoServ = " AND servicios.tiposervicio='$AmbitoAux[0]' ";
							}
						/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
						//-----------------------------------Encontrar la entidad,contrato y No Contrato de la tabla Servicios-----------------------------------------------------------------------------------
							
							
							$cons1="Select entidad,contrato,nocontrato,servicios.cedula from salud.pagadorxservicios,salud.servicios
							where pagadorxservicios.compania='$Compania[0]' and servicios.compania='$Compania[0]' $condEstado  and 
							pagadorxservicios.numservicio=servicios.numservicio	and '$Fecha'>=fechaini and (fechafin>='$Fecha' or FechaFin is NULL)";
							
							$res1=ExQuery($cons1);		
							if(ExNumRows($res1)>0)
							{	
								while($fila1 = ExFetch($res1))
								{
									//echo "$fila1[0]--$fila1[1]--$fila1[2]--$fila1[3]<br>";
									$PacienteAsegurado[$fila1[3]] = array($fila1[0],$fila1[1],$fila1[2]);
								}
								
							}
							else
							{			
								$cons1="Select entidad,contrato,nocontrato,fechafin,servicios.cedula from salud.pagadorxservicios,salud.servicios
								where pagadorxservicios.compania='$Compania[0]' and servicios.compania='$Compania[0]' $condEstado and 
								pagadorxservicios.numservicio=servicios.numservicio	and '$Fecha'>=fechaini ";
								$res1=ExQuery($cons1);	
								if(ExNumRows($res1)>0)
								{				
									while($fila1 = ExFetch($res1))
									{
										if($fila1[3]=='')
										{
											$PacienteAsegurado[$fila1[4]] = array($fila1[0],$fila1[1],$fila1[2]);
										}
										else
										{
											$Eps='-2'; $Contra='-2'; $NoContra='-2';
										}
									}
								}
								else
								{
									$Eps='-2'; $Contra='-2'; $NoContra='-2';
								}
							}	

						/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////	
							
							$cons="Select CedPaciente,servicios.numservicio,primape,segape,primnom,segnom,eps
							from Salud.PlantillaMedicamentos,Consumo.CodProductos,salud.servicios,central.terceros $AdFromUnidad
							where PlantillaMedicamentos.AutoIdProd = CodProductos.AutoId and PlantillaMedicamentos.Compania='$Compania[0]'
							and codproductos.compania='$Compania[0]' 
							and Anio='$ND[year]' and CodProductos.AlmacenPpal='$AlmacenPpal'
							and CodProductos.Estado='AC' $ConsDia and servicios.compania='$Compania[0]'  
							and servicios.cedula=PlantillaMedicamentos.cedpaciente and 	terceros.identificacion=PlantillaMedicamentos.cedpaciente
							$condEstado and terceros.compania='$Compania[0]'
							$condTipoServ  $AdWhereUnidad
							group by CedPaciente,servicios.numservicio,primape,segape,primnom,segnom,eps";
							//echo $cons;
							$res = ExQuery($cons);?>
							
							<table width="900px" class="tabla2" style="text-align:justify;"  <?php echo $borderTabla2Mentor; echo $bordercolorTabla2Mentor; echo $cellspacingTabla2Mentor; echo $cellpaddingTabla2Mentor; ?> >
						<?	if(ExNumRows($res)>0){
								$cons2="select primnom,segnom,primape,segape,identificacion from central.terceros where compania='$Compania[0]'";			
								//echo $cons2;
								$res2=ExQuery($cons2); 
								while($fila2=ExFetch($res2))
								{
									$Aseguradoras[$fila2[4]]= "$fila2[2] $fila2[3] $fila2[0] $fila2[1]";
								}
						?>		
								<tr>
									<td colspan="30" class='encabezado2Horizontal'>PLANTILLA MEDICAMENTOS</td>
								</tr>
								<tr>
									<td class='encabezado2HorizontalInvertido'>IDENTIFICACI&Oacute;N</td>
									<td class='encabezado2HorizontalInvertido'>NOMBRE</td>
									<td class='encabezado2HorizontalInvertido'>ASEGURADORA</td>
									<td class='encabezado2HorizontalInvertido'>CONTRATO</td>
									<td class='encabezado2HorizontalInvertido'>NO. CONTRATO</td></tr>
							<?
								while($fila=ExFetch($res))
								{?>
								   <tr align="center" title="Registrar Medicamentos" onMouseOver="this.bgColor='#AAD4FF'" onMouseOut="this.bgColor=''" style="cursor:hand"
									onClick="location.href='NewRegistroMedicamentos.php?DatNameSID=<? echo $DatNameSID?>&Ced=<? echo $fila[0]?>&AlmacenPpal=<? echo $AlmacenPpal?>&NumSer=<? echo $fila[1]?>&Ambito=<? echo $Ambito?>&Pabellon=<? echo $Pabellon?>'">
										 <a name="<? echo $fila[0];?>">
									   <td><? echo $fila[0]?></td><td><? echo "$fila[2] $fila[3] $fila[4] $fila[5]"?></td><td><? echo $Aseguradoras[$fila[6]]?></td>
										<td><? echo $PacienteAsegurado[$fila[0]][1]?></td>
										<td><? echo $PacienteAsegurado[$fila[0]][2]?></td></a>
									</tr>
						<?		}
							?>
								</table>
						<?	}
						else{?>
							<tr>
								<td colspan="30" class='mensaje1' >No hay pacientes con medicamentos por despachar en esta unidad</td>
							</tr>	
					<?	}	
					}
					?>
					<input type="Hidden" name="DatNameSID" value="<? echo $DatNameSID?>">
				</form>
			</div>	
		</body>
	</html>
