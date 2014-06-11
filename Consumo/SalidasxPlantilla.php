		<?php
			if($DatNameSID){session_name("$DatNameSID");}
			session_start();
			include("Informes.php");
			include("ObtenerSaldos.php");
			include_once("General/Configuracion/Configuracion.php");
			
			ini_set("memory_limit","512M");
			$ND = getdate();
			if(!$Anio){$Anio = $ND[year];}
			if(!$Mes){$Mes = $ND[mon];}
			if($Mes < 10){ $Mes = "0".$Mes;}
			if(!$Dia){$Dia = $ND[mday];}
			if($Dia < 10){ $Dia = "0".$Dia;}
			$Fecha = "$Anio-$Mes-$Dia";
			
			$AmbitoAux = explode("-",$Ambito);
			////////////////////////////////////OBTENER LOS MEDICAMENTOS YA DESPACHADOS/////////////////////////////////////////////////////////////
			$cons = "Select consumo.movimiento.Cedula,Movimiento.AutoId,Numero,Cantidad,Control,NumeroControlados
				from Consumo.Movimiento,Consumo.CodProductos, salud.pacientesxpabellones  
				where Movimiento.Compania='$Compania[0]' and CodProductos.Compania='$Compania[0]'
				and Comprobante = 'Salidas por Plantilla' and Movimiento.Autoid = CodProductos.Autoid
			and TipoComprobante = 'Salidas' and fechadespacho='$Fecha' 
				and Movimiento.AlmacenPpal = '$AlmacenPpal' and Movimiento.Anio = $Anio and
				CodProductos.AlmacenPpal = '$AlmacenPpal' and CodProductos.Anio = $Anio
				and Detalle like 'Despacho medicamentos $AmbitoAux[0]%' and salud.pacientesxpabellones.pabellon='$Pabellon' 
				and salud.pacientesxpabellones.cedula=consumo.movimiento.cedula and salud.pacientesxpabellones.estado='AC'
				order by cedula ASC";/*and fecha = '$ND[year]-$ND[mon]-$ND[mday]' and*/ 
			$res = ExQuery($cons);
			while($fila = ExFetch($res))
			{
				$Despachados[$fila[0]][$fila[2]][$fila[1]]=$fila[3];
						$TotalPlantilla[$fila[0]][$fila[1]]=$fila[3];
						if($fila[4]=="Si")
						{
							$DespachadosControl[$fila[0]]=$DespachadosControl[$fila[0]]+1;
							if($fila[5]){$NDP[$fila[0]][$fila[2]] = $fila[5];}
						}
						else{$DespachadosNOControl[$fila[0]]=$DespachadosNOControl[$fila[0]]+1;}
			}
			////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
			
			/*$cons = "Select NoDocAfectado,Cedula,AutoId,Cantidad from Consumo.Movimiento
			Where AlmacenPpal = '$AlmacenPpal' and Compania = '$Compania[0]'
			and Estado = 'AC'";
			$res = ExQuery($cons);
			while($fila = ExFetch($res))
			{
				$Devoluciones[$fila[0]][$fila[1]][$fila[2]] = $fila[3];
			}*/
			$cons = "Select CumsxProducto.AutoId,CUM,Cantidad,
			Lotes.Laboratorio,Lotes.Presentacion,Numero,Salidas
			from Consumo.CumsxProducto,Consumo.Lotes
			Where Lotes.Laboratorio = CumsXProducto.Laboratorio
			and Lotes.Presentacion = CumsXProducto.Presentacion
			and Lotes.Autoid = CumsXProducto.Autoid
			and (Salidas < Cantidad or Salidas is NULL)
			order by Autoid,Vence Desc";
			
			$res = ExQuery($cons);
			while($fila = ExFetch($res))
			{
				//echo "$fila[0]....$fila[1]....$fila[2]<br>";
				$C[$fila[0]] = $C[$fila[0]] + 1;
				$Lote[$fila[0]][$C[$fila[0]]] = array($fila[2]-$fila[6],$fila[1],$fila[3],$fila[4],$fila[5]);
			}
			$cons = "Select NumeroControlados
			from Consumo.Movimiento
			Where NumeroControlados
			is not NULL
			order by NumeroControlados desc Limit 1";
			$res = ExQuery($cons);
			$fila = ExFetch($res);
			$Controlados = $fila[0];
			$cons = "Select UsuariosxAlmacenes.AlmacenPpal,FormatoFormula,IniControlados,Redondear
			from Consumo.UsuariosxAlmacenes, Consumo.AlmacenesPpales
			where UsuariosxAlmacenes.AlmacenPpal = AlmacenesPpales.AlmacenPpal and  AlmacenesPpales.Compania='$Compania[0]' and
			Usuario='$usuario[1]' and SSFarmaceutico = 1";
			$res = ExQuery($cons);
			while($fila=ExFetch($res))
			{
				if(!$AlmacenPpal){$AlmacenPpal=$fila[0];}
				$Formula[$fila[0]]=$fila[1];
				$Inicial[$fila[0]]=$fila[2];
				$Redondear[$fila[0]] = $fila[3];
			}
			if(!$Ambito)
			{
				$cons = "Select Ambito,CentroCostos from Salud.Ambitos where Compania='$Compania[0]'
				and ambito!='Sin Ambito' order by ConsultaExtern desc";
				$res = ExQuery($cons);
				$fila = ExFetch($res);
				$Ambito = "$fila[0]";
				$CC[$fila[0]] = $fila[1];
			}
			$cons = "Select Autoid,Servicios.NumServicio,ValorVenta
			from Consumo.TarifasxProducto,ContratacionSalud.Contratos,Salud.PagadorxServicios,Salud.Servicios,Salud.Ambitos
			Where TarifasxProducto.Compania = '$Compania[0]' and TarifasxProducto.AlmacenPpal='$AlmacenPpal'
			and TarifasxProducto.FechaIni <= '$Fecha' and (TarifasxProducto.FechaFin >= '$Fecha' or TarifasxProducto.FechaFin is NULL)
			and Contratos.Compania = '$Compania[0]'
			and Contratos.FechaIni <= '$Fecha' and (Contratos.FechaFin >= '$Fecha' or Contratos.FechaFin is NULL)
			and PagadorxServicios.Compania = '$Compania[0]'
			and PagadorxServicios.FechaIni <= '$Fecha' and (PagadorxServicios.FechaFin >= '$Fecha' or PagadorxServicios.FechaFin is NULL)
			and Servicios.Compania='$Compania[0]'
			and Servicios.Estado = 'AC'
			and Ambitos.Compania='$Compania[0]'
			and Ambitos.ConsultaExtern != 1
			and Ambitos.Ambito = '$Ambito'
			and Servicios.TipoServicio = Ambitos.Ambito
			and Contratos.PlanTarifaMeds = TarifasxProducto.Tarifario
			and Contratos.Entidad = PagadorxServicios.Entidad
			and PagadorxServicios.Contrato = Contratos.Contrato
			and PagadorxServicios.NoContrato = Contratos.Numero
			and PagadorxServicios.NumServicio = Servicios.NumServicio
			order by NumServicio,AutoId";
			$res = ExQuery($cons);//echo $cons;exit;
			while($fila = ExFetch($res))
			{
				$Tarifa[$fila[1]][$fila[0]] = round($fila[2],0);
			}
			//print_r ($Tarifa);exit;

			if($DespacharMedicamentos)
			{
				$Numero=ConsecutivoComp("Salidas por Plantilla",$Anio,"Consumo");
				//$Numero = $Numero - 1;
				for($i=1;$i<=count($Despacho);$i++)
				{
					foreach($meds_sel as $key => $value){
						if($Despacho[$i][1]==$value){
							if($Despachar[$Despacho[$i][0]])
							{
								if(($Exist[$Despacho[$i][1]]-$Despacho[$i][2])>=0 && ($ExistAnu[$Despacho[$i][1]]-$Despacho[$i][2])>=0)
								{
									if(!$Despacho[$i][3]){$Despacho[$i][3]=0;};
									if(!$Despacho[$i][5]){$Despacho[$i][5]=0;};
									if($CedAnterior!=$Despacho[$i][0])
									{
										if($b){$Numero++;}
										else{$b=1;}
									}
									reset($Lote);
									unset($EsteDesp);
									$EsteDesp = $Despacho[$i][2];
									//echo "Lote para Autoid:".$Despacho[$i][1]."<br>";
									if(!$Lote[$Despacho[$i][1]])
									{
										$Lote[$Despacho[$i][1]]=array(0,0,0);
									}
									foreach($Lote[$Despacho[$i][1]] as $CUM)
									{
										if($CUM[0]>0)
										{
											if($CUM[0] >= $EsteDesp)
											{
												$CUM[0] = $CUM[0] - $EsteDesp;
												$EsteDesp = 0;
												$Cantidad = $Despacho[$i][2];
												$do_break = 1;
											}
											if($CUM[0]<$EsteDesp)
											{
												$EsteDesp = $EsteDesp - $CUM[0];
												$CUM[0] = 0;
												$Cantidad = $EsteDesp;
												$do_break = 0;
											}
											$consxx = "Update Consumo.Lotes set Salidas = Salidas + $Cantidad
											Where Laboratorio = '$CUM[2]' and Presentacion = '$CUM[3]'
											and Numero = '$CUM[4]' and Compania = '$Compania[0]' 
											and AlmacenPpal = '$AlmacenPpal' and Autoid = ".$Despacho[$i][1];
											$resxx = ExQuery($consxx);

																		$consL = "Select lote from Consumo.Lotes 
											Where Laboratorio = '$CUM[2]' and Presentacion = '$CUM[3]'
											and Numero = '$CUM[4]' and Compania = '$Compania[0]' 
											and AlmacenPpal = '$AlmacenPpal' and Autoid = ".$Despacho[$i][1];
											$resL = ExQuery($consL);
																		$filaL = ExFetch($resL);

																		//  Cuantas se despacharon
																		$cons_ = "select cantidad,fechadespacho,vrcosto from Consumo.Movimiento where 
																										 cedula='".$Despacho[$i][0]."'
																										 and Autoid='".$Despacho[$i][1]."' and fechadespacho='$Fecha'";

								$res_ = ExQuery($cons_);
						while($fila_ = ExFetch($res_)){
								$C[0]=$fila_[0];
								$Fdes[0]=$fila_[1];
								$vcost[0]=$fila_[2];}
										// Cuando la cantidad ingresada es cero no debe crear ningún registro
										if($Cantidad>0){
											if($C[0]==''&&$vcost[0]==''&&$Fdes[0]==''){
												$cons = "Insert into Consumo.Movimiento
													(Compania,AlmacenPpal,Fecha,Comprobante,TipoComprobante,
													Numero,Cedula,Detalle,AutoId,UsuarioCre,
													FechaCre,Estado,Cantidad,VrCosto,TotCosto,
													VrVenta,TotVenta,CentroCosto,Anio,Grupo,
													NumServicio,NumOrden,IdEscritura,NumeroControlados,CUM,FechaDespacho,lote)
													values
													('$Compania[0]','$AlmacenPpal','$ND[year]-$ND[mon]-$ND[mday]','Salidas por Plantilla','Salidas',
													'$Numero','".$Despacho[$i][0]."','Despacho medicamentos $Ambito - $Pabellon',".$Despacho[$i][1].",'$usuario[1]',
													'$ND[year]-$ND[mon]-$ND[mday] $ND[hours]:$ND[minutes]:$ND[seconds]','AC',$Cantidad,".$Despacho[$i][3].",".$Despacho[$i][4].",
													".$Despacho[$i][5].",".$Despacho[$i][6].",'".$Despacho[$i][7]."',$Anio,'".$Despacho[$i][8]."',
													".$Despacho[$i][9].",".$Despacho[$i][10].",".$Despacho[$i][11].",".$Despacho[$i][12].",'$CUM[1]','$Fecha','$filaL[0]')";
													$res = ExQuery($cons);
											}else{
											$conss = "select detalle,cedula,autoid,fechadespacho FROM Consumo.Movimiento Where cedula='".$Despacho[$i][0]."' and Autoid = ".$Despacho[$i][1]." and fechadespacho='".$Fdes[0]."'"; 
											$ress = ExQuery($conss);
											$filaa = ExFetch($ress);
											if($filaa[0]=='Despacho medicamentos '.$Ambito.' - '.$Pabellon
											 &&$filaa[1]==$Despacho[$i][0]
											 &&$filaa[2]==$Despacho[$i][1]
											 &&$filaa[3]==$Fdes[0]){
											$consxx_ = "Update Consumo.Movimiento set cantidad = ($C[0] + $Cantidad),totcosto=(($C[0] + $Cantidad) * $vcost[0])
												Where cedula='".$Despacho[$i][0]."' and Autoid = ".$Despacho[$i][1]." and detalle='Despacho medicamentos $Ambito - $Pabellon' 
																			and fechadespacho='".$Fdes[0]."'"; 
												$resxx_ = ExQuery($consxx_);
											   }else{
															$cons = "Insert into Consumo.Movimiento
															(Compania,AlmacenPpal,Fecha,Comprobante,TipoComprobante,
															Numero,Cedula,Detalle,AutoId,UsuarioCre,
															FechaCre,Estado,Cantidad,VrCosto,TotCosto,
															VrVenta,TotVenta,CentroCosto,Anio,Grupo,
															NumServicio,NumOrden,IdEscritura,NumeroControlados,CUM,FechaDespacho,lote)
															values
															('$Compania[0]','$AlmacenPpal','$ND[year]-$ND[mon]-$ND[mday]','Salidas por Plantilla','Salidas',
															'$Numero','".$Despacho[$i][0]."','Despacho medicamentos $Ambito - $Pabellon',".$Despacho[$i][1].",'$usuario[1]',
															'$ND[year]-$ND[mon]-$ND[mday] $ND[hours]:$ND[minutes]:$ND[seconds]','AC',$Cantidad,".$Despacho[$i][3].",".$Despacho[$i][4].",
															".$Despacho[$i][5].",".$Despacho[$i][6].",'".$Despacho[$i][7]."',$Anio,'".$Despacho[$i][8]."',
															".$Despacho[$i][9].",".$Despacho[$i][10].",".$Despacho[$i][11].",".$Despacho[$i][12].",'$CUM[1]','$Fecha','$filaL[0]')";
															$res = ExQuery($cons);
													 }
											}
										}

											$CedAnterior=$Despacho[$i][0];
											$Exist[$Despacho[$i][1]] = $Exist[$Despacho[$i][1]] - $Despacho[$i][2];
											$ExistAnu[$Despacho[$i][1]] = $ExistAnu[$Despacho[$i][1]] - $Despacho[$i][2];
											if($do_break == 1){break;}
										}
										if($EsteDesp==0){break;}
									}
								}
							}
						}
					}
				}
				
				$AmbitoAux = explode("-",$Ambito);
			////////////////////////////////////OBTENER LOS MEDICAMENTOS YA DESPACHADOS/////////////////////////////////////////////////////////////
			$cons = "Select consumo.movimiento.Cedula,Movimiento.AutoId,Numero,Cantidad,Control,NumeroControlados
				from Consumo.Movimiento,Consumo.CodProductos, salud.pacientesxpabellones
				where Movimiento.Compania='$Compania[0]' and CodProductos.Compania='$Compania[0]'
				and Comprobante = 'Salidas por Plantilla' and Movimiento.Autoid = CodProductos.Autoid
			and TipoComprobante = 'Salidas' and fecha = '$ND[year]-$ND[mon]-$ND[mday]' and 
				Movimiento.AlmacenPpal = '$AlmacenPpal' and Movimiento.Anio = $Anio and
				CodProductos.AlmacenPpal = '$AlmacenPpal' and CodProductos.Anio = $Anio
				and Detalle like 'Despacho medicamentos $AmbitoAux[0]%' and salud.pacientesxpabellones.pabellon='$Pabellon'
				and salud.pacientesxpabellones.cedula=consumo.movimiento.cedula and salud.pacientesxpabellones.estado='AC' 
				order by Cedula";
			$res = ExQuery($cons);
			while($fila = ExFetch($res))
			{
				$Despachados[$fila[0]][$fila[2]][$fila[1]]=$fila[3];
						$TotalPlantilla[$fila[0]][$fila[1]]=$fila[3];
						if($fila[4]=="Si")
						{
							$DespachadosControl[$fila[0]]=$DespachadosControl[$fila[0]]+1;
							if($fila[5]){$NDP[$fila[0]][$fila[2]] = $fila[5];}
						}
						else{$DespachadosNOControl[$fila[0]]=$DespachadosNOControl[$fila[0]]+1;}
			}
			////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

			?>
				<!--<script language="javascript">location.href="SalidasxPlantilla.php?DatNameSID=<? echo $DatNameSID?>&Ambito=<? echo $Ambito;?>&Pabellon=<? echo $Pabellon;?>";</script>-->
		<?php
			}
			$VrSaldoAnu=SaldosIniciales($Anio,$AlmacenPpal,"$Anio-01-01");
			$VrEntradasAnu=Entradas($Anio,$AlmacenPpal,"$Anio-01-01","$ND[year]-$ND[mon]-$ND[mday]");
			$VrSalidasAnu=Salidas($Anio,$AlmacenPpal,"$Anio-01-01","$ND[year]-$ND[mon]-$ND[mday]");
			$VrDevolucionesAnu=Devoluciones($Anio,$AlmacenPpal,"$Anio-01-01","$ND[year]-$ND[mon]-$ND[mday]");
			$VrSaldoIni=SaldosIniciales($Anio,$AlmacenPpal,"$Anio-01-01");
			$VrEntradas=Entradas($Anio,$AlmacenPpal,"$Anio-01-01",$Fecha);
			$VrSalidas=Salidas($Anio,$AlmacenPpal,"$Anio-01-01",$Fecha);
			$VrDevoluciones=Devoluciones($Anio,$AlmacenPpal,"$Anio-01-01",$Fecha);
			$cons = "Select consumo.movimiento.Cedula,Movimiento.Autoid,Cantidad,Numero,PrimNom,SegNom,PrimApe,SegApe,
			NombreProd1,UnidadMedida,Presentacion
			from Consumo.Movimiento,Central.Terceros,Consumo.CodProductos, salud.pacientesxpabellones 
			Where Movimiento.AlmacenPpal = '$AlmacenPpal' and Comprobante = 'Salidas por Plantilla'
			and FechaDespacho = '$Fecha' and Detalle like 'Despacho medicamentos%' 
			and salud.pacientesxpabellones.pabellon='$Pabellon'
			and salud.pacientesxpabellones.cedula=consumo.movimiento.cedula and salud.pacientesxpabellones.estado='AC' 
			and Terceros.Compania='$Compania[0]' and Identificacion = consumo.movimiento.Cedula
			and CodProductos.AlmacenPpal = '$AlmacenPpal' and CodProductos.Compania = '$Compania[0]'
			and CodProductos.Anio = $Anio and CodProductos.Autoid = Movimiento.Autoid
			order by Numero,Cedula,Autoid,NombreProd1,UnidadMedida,Presentacion";
			$res = ExQuery($cons);unset($C);
			while($fila=ExFetch($res))
			{
				$C++;
				$Despachados[$fila[0]][$fila[1]] = array($fila[2],$fila[0],$fila[3],$fila[1],
					"$fila[4] $fila[5] $fila[6] $fila[7]","$fila[8] $fila[9] $fila[10]");
				
			}
			$cons = "Select Identificacion,PrimNom,SegNom,PrimApe,SegApe,
			PlantillaMedicamentos.AlmacenPpal,AutoidProd,PlantillaMedicamentos.Usuario,FechaFormula,
			FechaIni,FechaFin,CantDiaria,ViaSuministro,Lunes,Martes,
			Miercoles,Jueves,Viernes,Sabado,Domingo,Justificacion,
			PlantillaMedicamentos.Notas,PlantillaMedicamentos.NumServicio,Detalle,
			PlantillaMedicamentos.Posologia,PlantillaMedicamentos.NumOrden,
			PlantillaMedicamentos.IdEscritura,Ambito,Pabellon,
			Control,Pos,Grupo,presentacion
			from Salud.PlantillaMedicamentos,Salud.PacientesxPabellones,Central.Terceros,Consumo.CodProductos
			Where PlantillaMedicamentos.Compania='$Compania[0]' and
			PlantillaMedicamentos.Estado = 'AC' and TipoMedicamento = 'Medicamento Programado'
			and PacientesxPabellones.Compania = '$Compania[0]'
			and CedPaciente = Cedula and PlantillaMedicamentos.Numservicio = PacientesxPabellones.NumServicio
			and PacientesxPabellones.Estado = 'AC'
			and Terceros.Compania='$Compania[0]'
			and Identificacion = CedPaciente
			and AutoidProd = Autoid and Codproductos.Estado = 'AC' and Anio = $Anio
			and CodProductos.AlmacenPpal = '$AlmacenPpal' and Codproductos.Compania='$Compania[0]'
			and (plantillamedicamentos.cedpaciente, plantillamedicamentos.numservicio, plantillamedicamentos.autoidprod, plantillamedicamentos.cantdiaria) NOT IN (SELECT movimiento.cedula, movimiento.numservicio, movimiento.autoid, movimiento.cantidad FROM consumo.movimiento WHERE movimiento.fechadespacho='$Fecha')
			order by Ambito,Pabellon,PrimNom,SegNom,PrimApe,SegApe,Detalle";
			//echo $cons;
			$res = ExQuery($cons);
			while($fila = ExFetch($res))
			{
				$C++;
		//        $Plantilla[$C] = array(Identificacion[0](0),Nombre[1-2-3-4](1),AlmacenPpal[5](2),AutoId[6](3)
		//                              Usuario[7](4),FechaFormula[8](5),FechaIni[9](6),FechaFin[10](7),Cantidad(8),Via(9),
		//                              Lunes(10),Martes(11),Miercoles(12),Jueves(13),Viernes(14),Sabado(15),Domingo(16),
		//                              Justificacion(17),Notas(18),NumServicio(19),Detalle(20),Posologia(21),NumOrden(22)
		//                              IdEscritura(23),Ambito(24),Pabellon(25),Control(26),Pos(27),Grupo(28);
				if($fila[32]=='AMPOLLA'){
					if($Redondear[$AlmacenPpal]){$fila[11] = ceil($fila[11]);}
				}		
				if($Redondear[$AlmacenPpal]){$fila[11];}
				if($fila[11] - $Despachados[$fila[0]][$fila[6]][0]>0)
				{
					$fila[11] = $fila[11] - $Despachados[$fila[0]][$fila[6]][0];
					$Plantilla[$C] = array($fila[0],"$fila[1] $fila[2] $fila[3] $fila[4]",$fila[5],$fila[6],
										$fila[7],$fila[8],$fila[9],$fila[10],$fila[11],$fila[12],
										$fila[13],$fila[14],$fila[15],$fila[16],$fila[17],$fila[18],$fila[19],
										$fila[20],$fila[21],$fila[22],$fila[23],$fila[24],$fila[25],
										$fila[26],$fila[27],$fila[28],$fila[29],$fila[30],$fila[31]);
				}
				if($Despachados[$fila[0]][$fila[6]])
				{
					array_push($Despachados[$fila[0]][$fila[6]],$fila[24],$fila[12]);
				}
			}
		?>
		<script language="javascript" src="/Funciones.js"></script>
		<script language="javascript">
		   function ValidarCantidad(CedPaciente,IDProd,ValAnt,Medicamento,Y)
			{
				var Cantidad = parseFloat(document.getElementById("Despacho[" + Y + "][2]").value);
				var ExCorte = parseFloat(document.getElementById("Exist[" + IDProd + "]").value);
				var ExAnual = parseFloat(document.getElementById("ExistAnu[" + IDProd + "]").value);

				if(Cantidad>parseFloat(document.getElementById("Exist[" + IDProd + "]").value))
				{
					if(ExCorte-Cantidad>0)
					{
						if(ExAnual-Cantidad>0){document.getElementById("Despacho[" + Y + "][2]").value= ValAnt;}
						else
						{
							alert("El Valor que intenta despachar para "+Medicamento+" es invalido sobrepasa la existencia Anual");
							document.getElementById("Despacho[" + Y + "][2]").value= "";
						}
					}
					else
					{
						alert("El Valor que intenta despachar para "+Medicamento+" es invalido");
						document.getElementById("Despacho[" + Y + "][2]").value= "";
					}
				}
				if((Cantidad>parseFloat(ValAnt)))
				{
					alert("El Valor que intenta despachar para "+Medicamento+" es mayor que el de la Orden Medica");
					document.getElementById("Despacho[" + Y + "][2]").value= ValAnt;
				}
			}
			function Act_Desact(objeto)
			{
				if(objeto.checked==true)
				{
					for (i=0;i<document.FORMA.elements.length;i++)
					{
						if(document.FORMA.elements[i].type == "checkbox")
						{
							document.FORMA.elements[i].checked = true;
						}
					}
				}
				else
				{
					for (i=0;i<document.FORMA.elements.length;i++)
					{
						if(document.FORMA.elements[i].type == "checkbox")
						{
								document.FORMA.elements[i].checked = false;
						}
					}
				}
			}
			function Info(Evento,Dato,Div)
				{
					var PosMouseX,PosMouseY;
					PosMouseX=Evento.clientX;
					PosMouseY=Evento.clientY;
					//--
					var ajusteX, ajusteY;
					if( self.pageYOffset ) {
					  ajusteX = self.pageXOffset;
					  ajusteY = self.pageYOffset;
					} else if( document.documentElement && document.documentElement.scrollTop ) {
					  ajusteX = document.documentElement.scrollLeft;
					  ajusteY = document.documentElement.scrollTop;
					} else if( document.body ) {
					  ajusteX = document.body.scrollLeft;
					  ajusteY = document.body.scrollTop;
					}
					var leftOffset;
					if(Div=="Mensaje"){leftOffset = ajusteX + (PosMouseX )-350;}
					else{leftOffset = ajusteX + (PosMouseX )-200;}
					var topOffset = ajusteY + (PosMouseY )-30 ;
					//alert(Div+"Msj");
					var Msjforma=Div+"Msj";	
					document.getElementById(Msjforma).value=Dato;
					document.getElementById(Div).style.top = topOffset + "px";
					document.getElementById(Div).style.left = leftOffset + "px";
					document.getElementById(Div).style.display = "block";
					if(Dato==""){document.getElementById(Msjforma).style.background="none";}
				}
				function AbrirTarjetaMeds(AlmacenPpal)
			{
					var Fecha = document.FORMA.Anio.value+"-"+document.FORMA.Mes.value+"-"+document.FORMA.Dia.value;
					var Ambito = document.FORMA.Ambito.value;
					var Pabellon = document.FORMA.Pabellon.value;
					open("/Informes/Almacen/Reportes/TarjetaMeds.php?DatNameSID=<? echo $DatNameSID?>&AlmacenPpal="+AlmacenPpal+"&Fecha="+Fecha+"&Ambito="+Ambito+"&Pabellon="+Pabellon,"","width=1000,height=1000,scrollbars=yes")
			}
				function AbrirFormulasControl(ListaPacientes,AlmacenPpal)
				{
					var Fecha = document.FORMA.Anio.value+"-"+document.FORMA.Mes.value+"-"+document.FORMA.Dia.value;
					open("/Informes/Almacen/Reportes/FormulasControl.php?DatNameSID=<? echo $DatNameSID?>&AlmacenPpal="+AlmacenPpal+"&Fecha="+Fecha+"&Origen=Plantilla&Pacientes="+ListaPacientes,"","width=1000,height=1000,scrollbars=yes")
				}
				function AbrirDespachos(AlmacenPpal)
			{
					var Fecha = document.FORMA.Anio.value+"-"+document.FORMA.Mes.value+"-"+document.FORMA.Dia.value;
					var Ambito = document.FORMA.Ambito.value;
					var Pabellon = document.FORMA.Pabellon.value;
					open("/Informes/Almacen/Reportes/ConsoDespachos.php?DatNameSID=<? echo $DatNameSID?>&Verx=paciente&AlmacenPpal="+AlmacenPpal+"&Fecha="+Fecha+"&Ambito="+Ambito+"&Pabellon="+Pabellon,"","width=800,height=600,scrollbars=yes")
			}
			function VerOrden(Cedula,Formato,Tipo,Numero,Urgentes,Despachados,Paciente,AlmacenPpal)
			{
					var Fecha = document.FORMA.Anio.value+"-"+document.FORMA.Mes.value+"-"+document.FORMA.Dia.value;
					open("/Informes/Almacen/Reportes/"+Formato+"?Urgentes="+Urgentes+"&Despachados="+Despachados+"&Tipo="+Tipo+"&DatNameSID=<? echo $DatNameSID?>&Cedula="+Cedula+"&Numero="+Numero+"&AlmacenPpal="+AlmacenPpal+"&Fecha="+Fecha+"&Origen=Plantilla&Pacientes="+Paciente,"","width=700,height=500,scrollbars=yes")
			}
		</script>
		
		
		<form name="FORMA" method="post">
			<input type="hidden" name="DatNameSID" value="<? echo $DatNameSID?>" />
			<table style='font : normal small-caps 12px Tahoma;' border="0">
				<tr bgcolor="#e5e5e5" style="font-weight: bold">
					<td>Fecha</td>
					<td><select name="Anio" onChange="FORMA.submit()">
					<?php $cons = "Select Anio from Central.Anios where Compania='$Compania[0]' order by Anio";
						   $res = ExQuery($cons);
						   while($fila = ExFetch($res))
						   {
								if($Anio == $fila[0]){echo "<option selected value='$fila[0]'>$fila[0]</option>";}
										else{echo "<option value='$fila[0]'>$fila[0]</option>";}
						   }
					?>
					</select></td>
					<td><select name="Mes" onChange="FORMA.submit()">
					<?php $cons = "Select Mes,Numero,NumDias from Central.Meses";
						   $res = ExQuery($cons);
					   while($fila = ExFetch($res))
						   {
										$DiaMax[$fila[1]] = $fila[2];
										if($Mes == $fila[1]){echo "<option selected value='$fila[1]'>$fila[0]</option>";}
										else{echo "<option value='$fila[1]'>$fila[0]</option>";}
						   }
						?>
					</select></td>
					<td>
					<select name="Dia" onChange="FORMA.submit()">
					<?php
						for($i=1;$i<=$DiaMax[round($Mes,0)];$i++)
								{
										if($Dia == $i){echo "<option selected value='$i'>$i</option>";}
										else {echo "<option value='$i'>$i</option>";}
								}
						?>
					</select>
					</td>
					<td bgcolor="#e5e5e5">Almacen Principal</td>
					<td><select name="AlmacenPpal" onChange="FORMA.submit();">
					<?php
						$cons = "Select UsuariosxAlmacenes.AlmacenPpal from Consumo.UsuariosxAlmacenes, Consumo.AlmacenesPpales
						where UsuariosxAlmacenes.AlmacenPpal = AlmacenesPpales.AlmacenPpal and  AlmacenesPpales.Compania='$Compania[0]' and
						Usuario='$usuario[1]' and SSFarmaceutico = 1";
						$res = ExQuery($cons);
						while($fila = ExFetch($res))
						{
								if($AlmacenPpal==$fila[0]){echo "<option selected value='$fila[0]'>$fila[0]</option>";}
								else{echo "<option value='$fila[0]'>$fila[0]</option>";}
						}
					?>
				   </select> </td>
				   <td bgcolor="#e5e5e5">Proceso</td>
					<td><select name="Ambito" onChange="FORMA.submit()">
					<?php //$cons = "Select Ambito,ConsultaExtern from Salud.Ambitos where Compania='$Compania[0]' and ambito!='Sin Ambito'";
					$cons = "Select Ambito,ConsultaExtern from Salud.Ambitos where Compania='$Compania[0]' and ambito!='Sin Ambito'";
						$res = ExQuery($cons);
						while($fila=ExFetch($res))
						{
							//$Ambito = "$fila[0]";
								if($Ambito == "$fila[0]"){?><option selected value="<? echo $fila[0];?>"><? echo $fila[0];?></option><?}
								else {?><option value="<? echo $fila[0]?>"><? echo $fila[0]?></option><?}
						}?>
					</select></td>
					<td bgcolor="#e5e5e5">Unidad</td>
					<td><select name="Pabellon" onChange="FORMA.submit()"><option></option>
						<?php $cons = "Select Pabellon,CentroCosto from Salud.Pabellones where Compania='$Compania[0]' and Ambito = '$Ambito'";
							$res = ExQuery($cons);
							while($fila = ExFetch($res))
							{
									if($Pabellon==$fila[0]){?><option selected value="<? echo $fila[0]?>"><?php echo $fila[0]?></option><?php }
									else {?><option value="<? echo $fila[0]?>"><? echo $fila[0]?></option><?php }
									$CC[$fila[0]] = $fila[1];
							}?>
					</select></td>
				</tr>
			</table>
			<?php
			if($Plantilla)
			{
			?>
			<input type="checkbox" name="Activar" title="Desactivar / Activar todos los despachos"
						   onclick="Act_Desact(this)" checked />
			<input type="button" name="VerTarjetas" value="Ver Tarjetas" onClick="AbrirTarjetaMeds('<? echo $AlmacenPpal?>')"
						   style="width: 70px; font-size: 9px; height: 30px;"/>
			<input type="button" name="VerDespachos" value="Ver Despachos" onClick="AbrirDespachos('<? echo $AlmacenPpal?>')"
				   style="width: 95px; font-size: 9px; height: 30px;"/>
			<?php if($Plantilla){?><input type="submit" name="DespacharMedicamentos" value="Despachar Medicamentos"
					   onclick ="this.style.visibility = 'hidden';"
					   style="width: 130px; font-size: 9px; height: 30px;"/><? }?>
			<input type="button" name="VerFormulas" value="Formulas de medicamentos controlados"
				   onclick="AbrirFormulasControl('<?echo str_replace("'","|",$LTerceros);?>','<? echo $AlmacenPpal?>')"
				   style="width: 195px; font-size: 9px; height: 30px;" />
			<table style='font : normal small-caps 12px Tahoma;' border="1" bordercolor="#e5e5e5" width="90%">
			<?php
			$varpr = "";
			//print_r($Plantilla);
			$fechaactual = date("Y-m-d H:i:s");
			$timeactual = strtotime($fechaactual);
			
			foreach($Plantilla as $Meds)
			{
			//print_r($Meds);echo "<br />";
				$cons = "SELECT * FROM salud.horacantidadxmedicamento,salud.plantillamedicamentos "
						. "WHERE horacantidadxmedicamento.fecha=plantillamedicamentos.fechaini "
						. "AND plantillamedicamentos.autoidprod=horacantidadxmedicamento.autoid "
						. "AND plantillamedicamentos.tipomedicamento='Medicamento Programado' "
						. "AND horacantidadxmedicamento.tipo='P' "
						. "AND horacantidadxmedicamento.paciente='".$Meds[0]."' "
						. "AND horacantidadxmedicamento.estado='AC' "
						. "AND horacantidadxmedicamento.autoid='".$Meds[3]."' "
						. "AND horacantidadxmedicamento.idescritura='".$Meds[23]."'";
				$res = ExQuery($cons);
				while($fila = ExFetchArray($res)){
					$timedespacho = strtotime($Fecha." ".$fila['hora'].":00:00");
					
					$timedespacho0 = strtotime(date("Y-m-d")." "."00".":00:00");
					$timedespacho8 = strtotime(date("Y-m-d")." "."08".":00:00");
					
					// si la fecha de despacho está en el intervalo no debe restar
					if($timedespacho>=$timedespacho0 && $timedespacho<=$timedespacho8){
						// No hace nada
					}
					else{
						if($timeactual>=$timedespacho){
							$Meds[8]-=$fila['cantidad'];
						}
					}
				}
				
				if($Meds[8]<0){
					$Meds[8]=0;
				}
				
				if($Meds[2]==$AlmacenPpal && $Meds[24]==$Ambito && $Meds[25]==$Pabellon)
				{
					if($Fecha>=$Meds[6] && (!$Meds[7] || $Fecha<=$Meds[7]))
					{
						$pos = stripos($Meds[21],"Fechas");
						$fecha = stripos($Meds[21],"$Fecha");
						$diaFecha = getdate(strtotime($Fecha));
						$Mostrar = 0;
						switch($diaFecha[wday])
						{
							case 0:if($Meds[16]){$Mostrar = 1;};break;
							case 1:if($Meds[10]){$Mostrar = 1;};break;
							case 2:if($Meds[11]){$Mostrar = 1;};break;
							case 3:if($Meds[12]){$Mostrar = 1;};break;
							case 4:if($Meds[13]){$Mostrar = 1;};break;
							case 5:if($Meds[14]){$Mostrar = 1;};break;
							case 6:if($Meds[15]){$Mostrar = 1;};break;
							default: echo "Entra...$diaFecha";
						}
						//echo "$Mostrar<br>";
						if(($Mostrar || $pos !== false)&&($pos === false || $fecha !== false))
						{
							unset($CantFinal,$CantFinalAnu);
							$CantFinal=$VrSaldoIni[$Meds[3]][0]+$VrEntradas[$Meds[3]][0]-$VrSalidas[$Meds[3]][0]+$VrDevoluciones[$Meds[3]][0];
							$VrFinal=$VrSaldoIni[$Meds[3]][1]+$VrEntradas[$Meds[3]][1]-$VrSalidas[$Meds[3]][1]+$VrDevoluciones[$Meds[3]][1];
							$CantFinalAnu=$VrSaldoAnu[$Meds[3]][0]+$VrEntradasAnu[$Meds[3]][0]-$VrSalidasAnu[$Meds[3]][0]+$VrDevolucionesAnu[$Meds[3]][0];
							if($CantFinal>0){$VrCosto = $VrFinal/$CantFinal;}
							else{$VrCosto = 0;}
							$VrCosto = Round($VrCosto,0);
							if(!$CC[$Meds[25]]){$Centro = $CC[$Meds[24]];}
							else{$Centro = $CC[$Meds[25]];}
							
							if($CedAnterior != $Meds[0])
							{
								$X++;
								?>
								<!--<tr bgcolor="#e5e5e5" style="font-weight: bold">
									<td colspan="7">
										<input type="checkbox" name="Despachar[<? echo $Meds[0]?>]" checked />
										<?php echo strtoupper("$X.$Meds[1] - $Meds[0]")?>
									</td>
								</tr>
								<tr bgcolor="#e5e5e5">
									<td></td><td>Medicamento</td><td width="21%">Posologia</td><td>Via</td><td>Cantidad</td><td>Ext. Fecha</td><td>Ext. Actual</td>
								</tr>-->
								<?php
							}
							$Y++;
							if($Meds[26]=="Si"){$Controlados++;}
							
							//echo $conshxm = "select * from salud.horacantidadxmedicamento, salud.plantillamedicamentos where plantillamedicamentos.cedpaciente=horacantidadxmedicamento.paciente and plantillamedicamentos.autoidprod=horacantidadxmedicamento.autoid and paciente='".$Meds[0]."'";
							//echo $conshxm = "select * salud.plantillamedicamentos where plantillamedicamentos.cedpaciente=horacantidadxmedicamento.paciente and plantillamedicamentos.autoidprod=horacantidadxmedicamento.autoid and paciente='".$Meds[0]."'";
							//$reshxm = ExQuery($conshxm);
							
							if($varpr!=$Meds[0]){
								$varpr = $Meds[0];
							?>
							<tr bgcolor="#e5e5e5" style="font-weight: bold">
									<td colspan="7">
										<input type="checkbox" name="Despachar[<? echo $Meds[0]?>]" checked />
										<?php echo strtoupper("$X.$Meds[1] - $Meds[0]")?>
									</td>
								</tr>
								<tr bgcolor="#e5e5e5">
									<td></td><td>Medicamento</td><td width="21%">Posologia</td><td>Via</td><td>Cantidad</td><td>Ext. Fecha</td><td>Ext. Actual</td>
								</tr>
							<?php } ?>
							<tr>
								<td style="text-align: center;"><input name="meds_sel[]" id="meds_sel[]" value="<?php echo $Meds[3]?>" type="checkbox"></td>
								<td><?php echo utf8_decode($Meds[20])?><br>
									<font color="blue"><i><?php echo $Meds[17]?><br></i></font>
									<font color="red"><i><?php echo $Meds[18]?></i></font>
								</td>
								<td><?php echo utf8_decode($Meds[21])?></td>
								<td><?php echo utf8_decode($Meds[9])?></td>
								<td align="center">
									<input type="hidden" name="Despacho[<?echo $Y?>][0]" value="<?php echo $Meds[0]?>" />
									<input type="hidden" name="Despacho[<?echo $Y?>][1]" value="<?php echo $Meds[3]?>" />
									<input type="text" name="Despacho[<?echo $Y?>][2]"
									id="Despacho[<?php echo $Y?>][2]"
									value="<? echo ceil($Meds[8]); ?>" maxlength="7" onKeyUp="xNumero(this)" onKeyDown="xNumero(this)"
									onBlur="campoNumero(this);
									ValidarCantidad('<? echo $Meds[0]?>','<? echo $Meds[3]?>','<? echo ceil($Meds[8])?>','<? echo $Meds[20]?>','<?echo $Y?>');"
									onchange="getElementById('VrTotal<?echo $Meds[0].$Meds[3]?>').value=parseInt(this.value)*getElementById('VrCosto<?echo $Meds[0].$Meds[3]?>').value;
									getElementById('TotVenta<?echo $Meds[0].$Meds[3]?>').value=parseInt(this.value)*getElementById('VrVenta<?echo $Meds[0].$Meds[3]?>').value;"
									style="width:60px; text-align:right" />
									<input type="hidden" name="Despacho[<?echo $Y?>][3]" value="<? echo $VrCosto?>"
										   id="VrCosto<?echo $Meds[0].$Meds[3]?>"/>
									<input type="hidden" name="Despacho[<?echo $Y?>][4]" value="<? echo ($VrCosto * ceil($Meds[8]))?>"
										   id="VrTotal<?echo $Meds[0].$Meds[3]?>"/>
									<input type="hidden" name="Despacho[<?echo $Y?>][5]" value="<? echo $Tarifa[$Meds[19]][$Meds[3]]?>"
										   id="VrVenta<?echo $Meds[0].$Meds[3]?>"/>
									<input type="hidden" name="Despacho[<?echo $Y?>][6]" value="<? echo ($Tarifa[$Meds[19]][$Meds[3]] * ceil($Meds[8]))?>"
										   id="TotVenta<?echo $Meds[0].$Meds[3]?>"/>
									<input type="hidden" name="Despacho[<?echo $Y?>][7]" value="<? echo $Centro?>" />
									<input type="hidden" name="Despacho[<?echo $Y?>][8]" value="<? echo $Meds[28]?>" />
									<input type="hidden" name="Despacho[<?echo $Y?>][9]" value="<? echo $Meds[19]?>" />
									<input type="hidden" name="Despacho[<?echo $Y?>][10]" value ="<? echo $Meds[22]?>" />
									<input type="hidden" name="Despacho[<?echo $Y?>][11]" value="<? echo $Meds[23]?>" />
									<input type="hidden" name="Despacho[<?echo $Y?>][12]"
										   value="<? if($Meds[26]=="Si"){echo $Controlados;}else{echo "NULL";}?>"/>
								</td>
								<td align="center">
									<input type="text" name="Exist[<? echo $Meds[3]?>]"
										id="Exist[<? echo $Meds[3]?>]"
										value="<? echo $CantFinal?>" readonly
										style="width:60px; text-align:right;border:#FFFFFF" /><? //echo $CantFinal;?>
								</td>
								<td align="center">
									<input type="text" name="ExistAnu[<? echo $Meds[3]?>]"
										id="ExistAnu[<? echo $Meds[3]?>]"
										value="<? echo $CantFinalAnu?>" readonly
										style="width:60px; text-align:right;border:#FFFFFF" />
								</td>
							</tr>
							<?php
							$CedAnterior = $Meds[0];
						}

					}

				}
			}
			?>
			</table>
			<?php
			}
			if($Despachados)
			{
				unset($X);
				?>
				<table style='font : normal small-caps 12px Tahoma;' border="1" bordercolor="#e5e5e5" width="90%">
					<tr>
						<td align="center" bgcolor="<? echo $Estilo[1]?>" style="color: white; font-weight: bold" colspan="8">
							MEDICAMENTOS DESPACHADOS PARA ESTA FECHA
						</td>
					</tr>
					<?php
					foreach($Despachados as $Despachados1)
					{
						foreach($Despachados1 as $Despachados2)
						{
							if($Despachados2[1]!=$CedAnterior)
							{if($Despachados2[2]){
								$X++;
								?>
								<tr bgcolor="#e5e5e5" style="font-weight: bold">
									<td colspan="6">
										<?echo strtoupper("$X.$Despachados2[4] - $Despachados2[1]")?>
									</td>
								</tr>
								<!--<tr>
									<td colspan="5"><font color="blue" style=" font-weight: bold"><? echo $Despachados2[2];?></font></td>
								</tr>-->
								<tr bgcolor="#e5e5e5">
									<td>Medicamento</td><td width="21%">Posologia</td><td>Via</td><td>Cantidad</td><td>Devoluciones</td><td>Total</td>
								</tr>
								
								<?php
							}}
							//echo "$Despachados2[1] - $Despachados2[3]<br>";
									if($Despachados2[1]!=""){
									$cons = "Select NoDocAfectado,Cedula,AutoId,Cantidad from Consumo.Movimiento
										Where AlmacenPpal = '$AlmacenPpal' and Compania = '$Compania[0]'
										and Estado = 'AC' and cedula='$Despachados2[1]' and autoid=$Despachados2[3]";
									$res = ExQuery($cons);
									while($fila = ExFetch($res))
									{
										$Devoluciones[$fila[0]][$fila[1]][$fila[2]] = $fila[3];
									}}
									if(number_format($Despachados2[0]-$Devoluciones[$Despachados2[2]][$Despachados2[1]][$Despachados2[3]],2)!="0.00"){
							?>
								<tr bgcolor="#EEF6F6">
									<td><?echo $Despachados2[5];?></td>
									<td><?echo $Despachados2[6];?></td>
									<td><?echo $Despachados2[7];?></td>
									<td align="right"><?echo number_format($Despachados2[0],2);?></td>
									<td align="right"><?echo number_format($Devoluciones[$Despachados2[2]][$Despachados2[1]][$Despachados2[3]],2);?></td>
									<td align="right" style="font-weight: bold"><?echo number_format($Despachados2[0]-$Devoluciones[$Despachados2[2]][$Despachados2[1]][$Despachados2[3]],2);?></td>
								</tr>
							<?php }
							$CedAnterior = $Despachados2[1];
						}
					}
					?>
				</table>
				<?php
			}
			?>
		<div id='Mensaje' name='Mensaje' style='position: absolute; width:auto;
			 height:auto; display: none; background:#FFFFFF;'>
		<input type="text" name="MensajeMsj" value="Mensaje de prueba" readonly
			   style=" color:#666699; border-style: ridge;
			   background:none;
			   font:normal small-caps 12px Tahoma;
			   font-weight:bold;
			   text-align:center;
			   width:150px; border-color:#666699"/>
		</div>
		</form>
		<iframe id="FrameOpener" name="FrameOpener" style="display:none" frameborder="0" height="1" style="border:#e5e5e5 ridge"></iframe>
		<?php
			$_SESSION["TarjetaMeds"]=$TotalPlantilla;
			$_SESSION["OrdenPacientes"] = $Numx;
		?>
		</body>
        