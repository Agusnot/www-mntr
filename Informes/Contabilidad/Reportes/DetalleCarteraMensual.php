		<?
			if($DatNameSID){session_name("$DatNameSID");}
			session_start();
			include("Informes.php");
			include_once("General/Configuracion/Configuracion.php");
			$ND=getdate();

			$Corte="$Anio-$MesFin-$DiaFin";
			$Dias=array(30,30,30,90,180,5000);
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
				encabezadoInformeContable(strtoupper($Compania[0]), $Compania[1], "ESTADO DE CARTERA", $caracteristicas,$fechaimpresion);
				
				?>
					<table  rules="groups"  width="70%" class="tablaInformeContable" width="70%" style="margin-top:25px;"  <?php echo $borderTablaInfContable; echo $bordercolorTablaInfContable; echo $cellspacingTablaInfContable; echo $cellpaddingTablaInfContable; ?>>

				<?	
				$cons1="Select Movimiento.Identificacion,PrimApe,SegApe,PrimNom,SegNom,Cuenta from Contabilidad.Movimiento,Central.Terceros 
				where Movimiento.Identificacion=Terceros.Identificacion 
				and Terceros.Compania='$Compania[0]'
				and Movimiento.Compania='$Compania[0]'
				and Movimiento.Cuenta>='$CuentaIni' and Movimiento.Cuenta<='$CuentaFin'
				and Fecha<='$Corte'
				$condAdc Group By Movimiento.Identificacion,PrimApe,SegApe,PrimNom,SegNom,Cuenta";
				$res1=ExQuery($cons1);
				while($fila1=ExFetch($res1))
				{
					$MatTerceros[$fila1[5]][$fila1[0]]=array($fila1[0],$fila1[1],$fila1[2],$fila1[3],$fila1[4],$fila1[5]);
				}

				$cons2="Select sum(Debe) as Suma,DocSoporte,Fecha,Cuenta,Identificacion,'$Corte'-Fecha,date_part('month',Fecha),date_part('year',Fecha) from Contabilidad.Movimiento where 
				Estado='AC' and Movimiento.Compania='$Compania[0]' $condAdc 
				and Movimiento.Cuenta>='$CuentaIni' and Movimiento.Cuenta<='$CuentaFin'
				and Fecha<='$Corte'
				Group By DocSoporte,Fecha,Cuenta,Identificacion having sum(Debe)>0 Order By Fecha Desc";
				$res2=ExQuery($cons2);
				while($fila2=ExFetch($res2))
				{
					$AniosCart[$fila2[3]][$fila2[4]][$fila2[7]]=$fila2[7];
					$MesCart[$fila2[3]][$fila2[4]][$fila2[7]][$fila2[6]]=$fila2[6];
					if(!$DocsCargados[$fila2[3]][$fila2[4]][$fila2[1]])
					{
						$DocsCargados[$fila2[3]][$fila2[4]][$fila2[1]]=array($fila2[7],$fila2[6]);
						$AnioCT=$fila2[7];
						$MesCT=$fila2[6];
					}
					else
					{
						$AnioCT=$DocsCargados[$fila2[3]][$fila2[4]][$fila2[1]][0];
						$MesCT=$DocsCargados[$fila2[3]][$fila2[4]][$fila2[1]][1];
					}
					$DocxFecha[$fila2[3]][$fila2[4]][$AnioCT][$MesCT][$fila2[1]]=$fila2[1];
					$MatCartera[$fila2[3]][$fila2[4]][$fila2[1]]=array($fila2[2],$fila2[1],$MatCartera[$fila2[3]][$fila2[4]][$fila2[1]][2]+$fila2[0]);
				}

				$cons3="Select sum(Haber) as Suma,DocSoporte,Fecha,Cuenta,Identificacion,'$Corte'-Fecha,date_part('month',Fecha),date_part('year',Fecha) from Contabilidad.Movimiento where 
				Estado='AC' and Movimiento.Compania='$Compania[0]' $condAdc 
				and Movimiento.Cuenta>='$CuentaIni' and Movimiento.Cuenta<='$CuentaFin'
				and Fecha<='$Corte'
				Group By DocSoporte,Fecha,Cuenta,Identificacion having sum(Haber)>0";

				$res3=ExQuery($cons3);
				while($fila3=ExFetch($res3))
				{
					$MatPagos[$fila3[3]][$fila3[4]][$fila3[1]]=array($fila3[2],$fila3[1],$MatPagos[$fila3[3]][$fila3[4]][$fila3[1]][2]+$fila3[0],"*");
				}

				$cons="Select Movimiento.Cuenta,Nombre from Contabilidad.Movimiento,Contabilidad.PlanCuentas 
				where Movimiento.Cuenta=PlanCuentas.Cuenta 
				and Movimiento.Cuenta>='$CuentaIni' and Movimiento.Cuenta<='$CuentaFin' $condAdc and 
				Movimiento.Compania='$Compania[0]' 
				and PlanCuentas.Compania='$Compania[0]'
				and PlanCuentas.Anio=$Anio
				Group By Movimiento.Cuenta,Nombre";
				$res=ExQuery($cons);
				while($fila=ExFetch($res))
				{
					echo "<tr>";
						echo"<td class='encabezado2HorizontalInfCont' colspan=5> $fila[0] $fila[1]</td>";
					echo "</tr>";
					foreach($MatTerceros[$fila[0]] as $Ident)
					{?>
						<table  rules="groups"  width="70%" class="tablaInformeContable" <?php echo $borderTablaInfContable; echo $bordercolorTablaInfContable; echo $cellspacingTablaInfContable; echo $cellpaddingTablaInfContable; ?>>
							<?	
							echo "<tr>";
								echo "<td class='encabezado2HorizontalInfContInv' colspan=5><strong>$Ident[0] $Ident[1] $Ident[2] $Ident[3] $Ident[4]</td>";
							echo "</tr>";
								if(count($AniosCart[$fila[0]][$Ident[0]])>0){
									foreach($AniosCart[$fila[0]][$Ident[0]] as $AniosC)
									{
										foreach($MesCart[$fila[0]][$Ident[0]][$AniosC] as $MesC)
										{
											foreach($DocxFecha[$fila[0]][$Ident[0]][$AniosC][$MesC] as $Documentos)
											{
												$Deuda=$MatCartera[$fila[0]][$Ident[0]][$Documentos][2];
												$Pagos=$MatPagos[$fila[0]][$Ident[0]][$Documentos][2];
												$MatPagos[$fila[0]][$Ident[0]][$Documentos][3]="Ok";
												$Saldo=$Deuda-$Pagos;
												if($Saldo!=0)
												{
													if($PerAnt!=$MesC.$AniosC)
													{
														echo "<tr>";
															echo "<td class='encabezado2HorizontalInfCont' colspan=5><strong>$NombreMes[$MesC] - $AniosC</td>";
														echo "</tr>";
														echo "<tr>";
															echo "<td class='encabezado2HorizontalInfCont'>FECHA </td>";
															echo "<td class='encabezado2HorizontalInfCont'>DOCUMENTO</td>";
															echo "<td class='encabezado2HorizontalInfCont'>VALOR DB</td>";
															echo "<td class='encabezado2HorizontalInfCont'>VALOR CR</td>";
															echo "<td class='encabezado2HorizontalInfCont'>SALDO</td>";
														echo "</tr>";
														$PerAnt=$MesC.$AniosC;
													}
						
												echo "<tr>";
													echo "<td>".$MatCartera[$fila[0]][$Ident[0]][$Documentos][0]."</td>";
													echo "<td>".$MatCartera[$fila[0]][$Ident[0]][$Documentos][1]."</td>";
													echo "<td>".number_format($MatCartera[$fila[0]][$Ident[0]][$Documentos][2])."</td>";
													echo "<td>".number_format($Pagos[2],2)."</td>";
													echo "<td>".number_format($Saldo,2)."</td>";
												echo "</tr>";
												$TotalSaldo=$TotalSaldo+$Saldo;
												$TotMes=$TotMes+$Saldo;$TotGral=$TotGral+$Saldo;}
											}
											if($TotMes!=0)
											{
												echo "<tr>";
													echo "<td class='filaTotalesInfContable' style='text-align: right; padding-right: 10px;' colspan='4'>TOTAL MES</td>";
													echo "<td class='filaTotalesInfContable' style='text-align: right; padding-right: 10px;'>".number_format($TotMes,2)."</td>";
												echo "</tr>";
												$TotMes=0;
											}
										}
									}
								}
						
						if(count($MatPagos[$fila[0]][$Ident[0]])>0)
						{
							foreach($MatPagos[$fila[0]][$Ident[0]] as $Validar)
							{
								if($Validar[3]=="*")
								{
									echo "<tr><td colspan=5><hr></td></tr>";
									echo "<tr>";
										echo "<td  class='encabezado2HorizontalInfContInv' style='color:red;' colspan=5>CREDITOS SIN REFERENCIA</td>";
									echo "</tr>";
									echo "<tr>";
										echo "<td  class='encabezado2HorizontalInfContInv' style='color:red;'>$Validar[0]</td>";
										echo "<td  class='encabezado2HorizontalInfContInv' style='color:red;'>$Validar[1]</td>";
										echo "<td  class='encabezado2HorizontalInfContInv' style='color:red;' >0.00</td>";
										echo "<td  class='encabezado2HorizontalInfContInv' style='color:red;'>-".number_format($Validar[2],2)."</td></tr>";
									$TotalSaldo=$TotalSaldo-$Validar[2];$TotGral=$TotGral-$Validar[2];
								}
							}
						}
						echo "<tr>";
							echo  "<td class='filaTotalesInfContable' style='text-align: right; padding-right: 10px;' colspan='4'>TOTAL ENTIDAD</td>";
							 echo "<td class='filaTotalesInfContable' style='text-align: right; padding-right: 10px;'>".number_format($TotalSaldo,2)."</td>";
						echo "</tr>";
						$TotalSaldo=0;
					}
				}
					echo "<tr>";
						echo "<td class='filaTotalesInfContable' style='text-align: right; padding-right: 10px;' colspan='4'>TOTAL CARTERA</td>";
						echo "<td class='filaTotalesInfContable' style='text-align: right; padding-right: 10px;'>".number_format($TotGral,2)."</td>";
					echo "</tr>";

				echo "</table>";
				?>
			</div>			
		</body>
	</html>	