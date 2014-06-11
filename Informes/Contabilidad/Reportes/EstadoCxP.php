		<?
			if($DatNameSID){session_name("$DatNameSID");}
			session_start();
			include("Funciones.php");
			include_once("General/Configuracion/Configuracion.php");
			$ND=getdate();

			$Corte="$Anio-$MesFin-$DiaFin";
			$Dias=array(1,30,30,30,30,60,180,5000);
			$DetDias=array("NO VENCIDAS","DE 1 A 30 DIAS","DE 31 A 60 DIAS","DE 61 A 90 DIAS","DE 91 A 120 DIAS","DE 121 A 180 DIAS","DE 181 A 360 DIAS","MAS DE 360 DIAS");

			if($Tercero){$condAdc=" and Movimiento.Identificacion='$Tercero'";}
			if($NoDoc){$cond2=" and DocSoporte='$NoDoc'";}			
			
			?>
			
	<html>
		<head>
			<?php echo $codificacionMentor; ?>
			<?php echo $autorMentor; ?>
			<?php echo $titleMentor; ?>
			<?php echo $iconMentor; ?>
			<?php echo $shortcutIconMentor; ?>
			<link rel="stylesheet" type="text/css" href="../../../General/Estilos/estilos.css">
		</head>
		
		<body <?php echo $backgroundBodyInfContableMentor;?>>
			<div class="divInformeContable" <?php echo $alignDivInformeContable;?>>
				<?php
					$NumRec=0;$NumPag=1;
					global $Compania;global $PerFin;global $Estilo;global $IncluyeCC;global $ND;global $NumPag;global $TotPaginas;global $Corte;
					$caracteristicas= "CORTE A : ".$Corte;
					$fechaimpresion= "FECHA DE IMPRESI&Oacute;N : ".$ND[year]."-".$ND[mon]."-".$ND[mday];
					encabezadoInformeContable(strtoupper($Compania[0]), $Compania[1], "ESTADO DE CUENTAS POR PAGAR", $caracteristicas,$fechaimpresion);				
				?>
					
					<table class="tablaInformeContable" style="margin-top:25px;"  <?php echo $borderTablaInfContable; echo $bordercolorTablaInfContable; echo $cellspacingTablaInfContable; echo $cellpaddingTablaInfContable; ?>>
						<tr><td class='encabezado2HorizontalInfCont'>CONCEPTO</td>
							<?
								if($MostrarDocs)	{
									echo "<td class='encabezado2HorizontalInfCont'>DOCUMENTO</td>";
									echo "<td class='encabezado2HorizontalInfCont'>FECHA DOC</td>";
									echo "<td>FECHA VENCIMIENTO</td>";
								}

								for($i=0;$i<=count($Dias)-1;$i++)	{
									echo "<td class='encabezado2HorizontalInfCont'>$DetDias[$i]</td>";
								}
							?>
							<td class='encabezado2HorizontalInfCont' >TOTAL</td>
					</tr>
					<?	
					$cons1="Select Movimiento.Identificacion,PrimApe,SegApe,PrimNom,SegNom,Cuenta from Contabilidad.Movimiento,Central.Terceros 
					where Movimiento.Identificacion=Terceros.Identificacion 
					and Terceros.Compania='$Compania[0]'
					and Movimiento.Compania='$Compania[0]'
					and Movimiento.Cuenta>='$CuentaIni' and Movimiento.Cuenta<='$CuentaFin'
					and Fecha<='$Corte'
					$condAdc Group By Movimiento.Identificacion,PrimApe,SegApe,PrimNom,SegNom,Cuenta Order By Cuenta Asc";
					$res1=ExQuery($cons1);
					while($fila1=ExFetch($res1))
					{
						$MatTerceros[$fila1[5]][$fila1[0]]=array($fila1[0],$fila1[1],$fila1[2],$fila1[3],$fila1[4],$fila1[5]);
					}

					$cons2="Select sum(Haber) as Suma,DocSoporte,FechaDocumento,Cuenta,Identificacion,Fecha from Contabilidad.Movimiento where 
					Estado='AC' and Movimiento.Compania='$Compania[0]' $condAdc 
					and Movimiento.Cuenta>='$CuentaIni' and Movimiento.Cuenta<='$CuentaFin'
					and Fecha<='$Corte'

					Group By DocSoporte,FechaDocumento,Cuenta,Identificacion,Fecha having sum(Haber)>0 Order By FechaDocumento Asc";
					$res2=ExQuery($cons2);
					while($fila2=ExFetch($res2))
					{
						$Date2=$fila2[2];$Date1="$Corte";
						$s = strtotime($Date1)-strtotime($Date2);$d = intval($s/86400);
						$fila2[5]=$d;
						$DiasMin=0;$DiasMax=0;$Periodo=0;
						for($i=0;$i<=count($Dias);$i++)
						{
							$DiasMax=$DiasMax+$Dias[$i];
							if($fila2[5]>=$DiasMin and $fila2[5]<=$DiasMax)
							{
								$Periodo=$i;break;
							}
							$DiasMin=$DiasMax;
						}
				//		echo "$fila2[3]-->$fila2[4]--->$Periodo--->$fila2[5]--->$fila2[2]--->$fila2[0]<br>";
						$MatDocSoporte[$fila2[3]][$fila2[4]][$fila2[1]]=$Periodo;
						$MatCartera[$fila2[3]][$fila2[4]][$Periodo]=$MatCartera[$fila2[3]][$fila2[4]][$Periodo]+$fila2[0];
				//		echo "$fila2[3]-->$fila2[4]-->$Periodo-->$fila2[0]------>". $MatCartera[$fila2[3]][$fila2[4]][$Periodo]."<br>";
					}
					
					
					
					$cons3="Select sum(Debe) as Suma,DocSoporte,Fecha,Cuenta,Identificacion from Contabilidad.Movimiento where 
					Estado='AC' and Movimiento.Compania='$Compania[0]' $condAdc 
					and Movimiento.Cuenta>='$CuentaIni' and Movimiento.Cuenta<='$CuentaFin'
					and Fecha<='$Corte'
					Group By DocSoporte,Fecha,Cuenta,Identificacion having sum(Debe)>0 Order By Cuenta,Fecha Asc";

					$res3=ExQuery($cons3);
					while($fila3=ExFetch($res3))
					{
						$Date2=$fila3[2];$Date1="$Corte";
						$s = strtotime($Date1)-strtotime($Date2);$d = intval($s/86400);
						$fila3[5]=$d;

						$Periodo=0;
						$Periodo=$MatDocSoporte[$fila3[3]][$fila3[4]][$fila3[1]];
						if(!count($MatDocSoporte[$fila3[3]][$fila3[4]][$fila3[1]]))
						{
							$DiasMin=0;$DiasMax=0;
							for($i=0;$i<=count($Dias);$i++)
							{
								$DiasMax=$DiasMax+$Dias[$i];
								if($fila3[5]>=$DiasMin and $fila3[5]<=$DiasMax)
								{
									$Periodo=$i;break;
								}
								$DiasMin=$DiasMax;
							}
						}
				//		echo "$fila3[1]--->$Periodo<br>";
						$MatCartera[$fila3[3]][$fila3[4]][$Periodo]=$MatCartera[$fila3[3]][$fila3[4]][$Periodo]-$fila3[0];
					}

				//////////// MOVIMIENTO X DOCUMENTOS
				if($MostrarDocs=="on"){

					$cons2="Select sum(Haber) as Suma,DocSoporte,FechaDocumento,Cuenta,Identificacion,Fecha,comprobante from Contabilidad.Movimiento where 
					Estado='AC' and Movimiento.Compania='$Compania[0]' $condAdc 
					and Movimiento.Cuenta>='$CuentaIni' and Movimiento.Cuenta<='$CuentaFin'
					and Fecha<='$Corte'
					Group By DocSoporte,FechaDocumento,Cuenta,Identificacion,Fecha,comprobante having sum(Haber)>0 Order By Cuenta,FechaDocumento Asc";

					$res2=ExQuery($cons2);
					while($fila2=ExFetch($res2))
					{
						$FechaDoc=$fila2[5];
						$Date2=$fila2[2];$Date1="$Corte";
						$s = strtotime($Date1)-strtotime($Date2);$d = intval($s/86400);
						$fila2[5]=$d;

						$DiasMin=0;$DiasMax=0;$Periodo=0;
						for($i=0;$i<=count($Dias);$i++)
						{
							$DiasMax=$DiasMax+$Dias[$i];
							if($fila2[5]>=$DiasMin and $fila2[5]<=$DiasMax)
							{
								$Periodo=$i;break;
							}
							$DiasMin=$DiasMax;
						}
				//		echo "$fila2[3]-->$fila2[4]--->$Periodo--->$fila2[5]--->$fila2[2]--->$fila2[0]<br>";
						$MatDocSoportexDoc[$fila2[3]][$fila2[4]][$fila2[1]]=$Periodo;
						$MatCarteraxDoc[$fila2[3]][$fila2[4]][$Periodo][$fila2[1]]=array($MatCarteraxDoc[$fila2[3]][$fila2[4]][$Periodo][$fila2[1]][0]+$fila2[0],$fila2[1],$fila2[2],$FechaDoc,$fila2[6]);

					}

					$cons3="Select sum(Debe) as Suma,DocSoporte,Fecha,Cuenta,Identificacion from Contabilidad.Movimiento where 
					Estado='AC' and Movimiento.Compania='$Compania[0]' $condAdc 
					and Movimiento.Cuenta>='$CuentaIni' and Movimiento.Cuenta<='$CuentaFin'
					and Fecha<='$Corte'
					Group By DocSoporte,Fecha,Cuenta,Identificacion having sum(Debe)>0 Order By Cuenta,Fecha Asc";
				   
					$res3=ExQuery($cons3);
					while($fila3=ExFetch($res3))
					{
						$Date2=$fila3[2];$Date1="$Corte";
						$s = strtotime($Date1)-strtotime($Date2);$d = intval($s/86400);
						$fila3[5]=$d;

						$Periodo=0;
						$Periodo=$MatDocSoportexDoc[$fila3[3]][$fila3[4]][$fila3[1]];
						if(!count($MatDocSoportexDoc[$fila3[3]][$fila3[4]][$fila3[1]]))
						{
							$DiasMin=0;$DiasMax=0;
							for($i=0;$i<=count($Dias);$i++)
							{
								$DiasMax=$DiasMax+$Dias[$i];
								if($fila3[5]>=$DiasMin and $fila3[5]<=$DiasMax)
								{
									$Periodo=$i;break;
								}
								$DiasMin=$DiasMax;
							}
							if($VrGestion>0)
							$VrGestion=$MatCarteraxDoc[$fila3[3]][$fila3[4]][$Periodo][$fila3[1]]-$fila3[0];
							$MatCarteraxDoc[$fila3[3]][$fila3[4]][$Periodo][$fila3[1]]=array($VrGestion,$fila3[1],$fila3[2]);
						}
						else
						{
							$MatCarteraxDoc[$fila3[3]][$fila3[4]][$Periodo][$fila3[1]][0]=$MatCarteraxDoc[$fila3[3]][$fila3[4]][$Periodo][$fila3[1]][0]-$fila3[0];
						}
					}
				}

					$cons="Select Movimiento.Cuenta,Nombre from Contabilidad.Movimiento,Contabilidad.PlanCuentas 
					where Movimiento.Cuenta=PlanCuentas.Cuenta 
					and Movimiento.Cuenta>='$CuentaIni' and Movimiento.Cuenta<='$CuentaFin' $condAdc and 

					Movimiento.Compania='$Compania[0]' 
					and PlanCuentas.Compania='$Compania[0]'
					and PlanCuentas.Anio=$Anio

					Group By Movimiento.Cuenta,Nombre Order By Movimiento.Cuenta Asc";
					$res=ExQuery($cons);
					while($fila=ExFetch($res))
					{

						echo "<tr><td class='encabezado2HorizontalInfContInv' colspan=11><strong>$fila[0] $fila[1]</td></tr>";
						foreach($MatTerceros[$fila[0]] as $Ident)
						{
							for($i=0;$i<=count($Dias)-1;$i++)
							{
								$TotEntidad=$TotEntidad+$MatCartera[$fila[0]][$Ident[0]][$i];
							}
							if($TotEntidad!=0)
							{
								if($MostrarDocs){$Colspan=4;}else{$Colspan=0;}
								echo "<tr><td valign='top' colspan=$Colspan>$Ident[0] $Ident[1] $Ident[2] $Ident[3] $Ident[4]</td>";
							}
							if($TotEntidad!=0)
							{
								for($i=0;$i<=count($Dias)-1;$i++)
								{
									echo "<td  valign='top'>".number_format($MatCartera[$fila[0]][$Ident[0]][$i],2);

									echo "</td>";
									$TotCol[$i]=$TotCol[$i]+$MatCartera[$fila[0]][$Ident[0]][$i];
									$SubTotxCta[$fila[0]][$i]=$SubTotxCta[$fila[0]][$i]+$MatCartera[$fila[0]][$Ident[0]][$i];
								}
								echo "<td style='font-weight:bold' valign='top'>".number_format($TotEntidad,2)."</strong></td>";
								$TotEntidad=0;
								echo "</tr>";

								if($MostrarDocs=="on")
								{
									for($i=0;$i<=count($Dias)-1;$i++)
									{
										if(count($MatCarteraxDoc[$fila[0]][$Ident[0]][$i])>0)
										{
											foreach($MatCarteraxDoc[$fila[0]][$Ident[0]][$i] as $Documento)
											{
												$TotPeriodo[$i]=$TotPeriodo[$i]+$Documento[0];
											}
											if($TotPeriodo[$i]!=0)
											{
												foreach($MatCarteraxDoc[$fila[0]][$Ident[0]][$i] as $Documento)
												{
													if($Documento[0]!=0)
													{
														
														echo "<tr><td></td><td>$Documento[1] $Documento[4]</td><td>$Documento[3]</td><td>$Documento[2]</td>";
														for($m=1;$m<=$i;$m++)
														{
															echo "<td></td>";
														}
														echo "<td>".number_format($Documento[0],2)."</td></tr>";
													}
												}
											}
										}
									}
								}
							}
						}
						if($MostrarDocs){$ColsPan2="4";}else{$ColsPan2=0;}
						echo "<tr>";
							echo "<td  class='filaTotalesInfContable' style='text-align:right; padding-right: 10px;' colspan='$ColsPan2'>SUMAS</td>";
						for($i=0;$i<=count($Dias)-1;$i++)
						{
							echo "<td class='filaTotalesInfContable' >".number_format($SubTotxCta[$fila[0]][$i],2)."</td>";
							$SubTotCompa=$SubTotCompa+$SubTotxCta[$fila[0]][$i];
						}
						echo "<td class='filaTotalesInfContable'>".number_format($SubTotCompa,2)."</td>";$SubTotCompa=0;
						echo "</tr>";
					}
					echo "<tr>";
						echo "<td class='filaTotalesInfContable' style='text-align:right; padding-right: 10px;' colspan='$ColsPan2'>TOTALES</td>";
					for($i=0;$i<=count($Dias)-1;$i++)
					{
						echo "<td class='filaTotalesInfContable'>".number_format($TotCol[$i],2)."</td>";
						$TotGral=$TotGral+$TotCol[$i];
					}
					echo "<td class='filaTotalesInfContable'>".number_format($TotGral,2)."</td>";
				?>
				</table>
			</div>	
		</body>
	</html>		