		<?
				if($DatNameSID){session_name("$DatNameSID");}
			session_start();
			include("Informes.php");
			include_once("General/Configuracion/Configuracion.php");
			$ND=getdate();

			$Corte="$Anio-$MesFin-$DiaFin";
			$Dias=array(30,30,30,90,180,5000);
			$DetDias=array("DE 0 A 30 DIAS","DE 30 A 60 DIAS","DE 60 A 90 DIAS","DE 90 A 180 DIAS","DE 180 A 360 DIAS","MAS DE 360 DIAS");
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
		
		<body <?php echo $backgroundBodyInfContableMentor; ?>>
			<div class="divInformeContable" <?php echo $alignDivInformeContable;?>>
					<?
					$NumRec=0;$NumPag=1;
					
					global $Compania;global $PerFin;global $Estilo;global $IncluyeCC;global $ND;global $NumPag;global $TotPaginas;global $Corte;
					$caracteristicas= "CORTE A : ".$Corte;
					$fechaimpresion= "FECHA DE IMPRESI&Oacute;N : ".$ND[year]."-".$ND[mon]."-".$ND[mday];
					encabezadoInformeContable(strtoupper($Compania[0]), $Compania[1], "ESTADO DE CUENTAS POR PAGAR", $caracteristicas,$fechaimpresion);
					?>
						<table class="tablaInformeContable" width="70%" style="margin-top:25px;"  <?php echo $borderTablaInfContable; echo $bordercolorTablaInfContable; echo $cellspacingTablaInfContable; echo $cellpaddingTablaInfContable; ?>>

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

					$cons2="Select sum(Haber) as Suma,DocSoporte,Fecha,Cuenta,Identificacion from Contabilidad.Movimiento where 
					Estado='AC' and Movimiento.Compania='$Compania[0]' $condAdc 
					and Movimiento.Cuenta>='$CuentaIni' and Movimiento.Cuenta<='$CuentaFin'
					and Fecha<='$Corte'
					Group By DocSoporte,Fecha,Cuenta,Identificacion having sum(Haber)>0 Order By Fecha Desc";

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
						$MatCartera[$fila2[3]][$fila2[4]][$Periodo][$fila2[1]]=array($MatCartera[$fila2[3]][$fila2[4]][$Periodo][$fila2[1]][0]+$fila2[0],$fila2[1],$fila2[2]);

					}

					$cons3="Select sum(Debe) as Suma,DocSoporte,Fecha,Cuenta,Identificacion from Contabilidad.Movimiento where 
					Estado='AC' and Movimiento.Compania='$Compania[0]' $condAdc 
					and Movimiento.Cuenta>='$CuentaIni' and Movimiento.Cuenta<='$CuentaFin'
					and Fecha<='$Corte'
					Group By DocSoporte,Fecha,Cuenta,Identificacion having sum(Debe)>0";

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
				//			$CarteraSinSoporte[$fila3[3]][$fila3[4]][$Periodo][$fila3[1]]=array($CarteraSinSoporte[$fila3[3]][$fila3[4]][$Periodo][$fila3[1]][0]+$fila3[0],$fila3[1],$fila3[2]);
						}

						$MatCartera[$fila3[3]][$fila3[4]][$Periodo][$fila3[1]][0]=$MatCartera[$fila3[3]][$fila3[4]][$Periodo][$fila3[1]][0]-$fila3[0];
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
							echo "<td class='encabezado2HorizontalInfCont' colspan=5>$fila[0] $fila[1]</td></tr>";
						foreach($MatTerceros[$fila[0]] as $Ident)
						{?>
							<table width="70%" <?php echo $borderTablaInfContable; echo $bordercolorTablaInfContable; echo $cellspacingTablaInfContable; echo $cellpaddingTablaInfContable; ?> >
				<?			echo "<tr>";
								echo "<td class='encabezado2HorizontalInfContInv' colspan=5>$Ident[0] $Ident[1] $Ident[2] $Ident[3] $Ident[4]</td>";
							echo "</tr>";
							for($i=0;$i<=count($Dias)-1;$i++)
							{
								if(count($MatCartera[$fila[0]][$Ident[0]][$i])>0)
								{
									foreach($MatCartera[$fila[0]][$Ident[0]][$i] as $Documento)
									{
										$TotPeriodo[$i]=$TotPeriodo[$i]+$Documento[0];
									}
									if($TotPeriodo[$i]>0)
									{
										echo "<tr style='font-weight:bold' bgcolor='#e5e5e5'><td colspan=3 align='center'><font size=3>".$DetDias[$i]."</td></tr>";
										echo "<tr style='font-weight:bold' bgcolor='#e5e5e5'><td>Fecha</td><td>Doc</td><td align='right'>Saldo</td></tr>";
										foreach($MatCartera[$fila[0]][$Ident[0]][$i] as $Documento)
										{
											if($Documento[0]>0)
											{
												echo "<tr><td>$Documento[2]</td><td>$Documento[1]</td><td align='right'>".number_format($Documento[0],2)."</td></tr>";
											}
										}
										echo "<tr>";
											echo "<td class='filaTotalesInfContable' colspan=2>TOTAL PERIODO</td>";
											echo "<td class='filaTotalesInfContable'>".number_format($TotPeriodo[$i],2)."</td></tr>";
										$TotEntidad=$TotEntidad+$TotPeriodo[$i];$TotPeriodo[$i]=0;
									}
								}
								if(count($CarteraSinSoporte[$fila[0]][$Ident[0]][$i])>0)
								{
									echo "<tr style='color:red'><td align='right'><strong>DEBITOS SIN REFERENCIA</td></tr>";
									echo "<tr style='font-weight:bold' bgcolor='#e5e5e5'><td>FECHA</td><td>DOCUMENTO</td><td align='right'>Saldo</td></tr>";
									foreach($CarteraSinSoporte[$fila[0]][$Ident[0]][$i] as $Documento)
									{
										echo "<tr><td>$Documento[2]</td><td>$Documento[1]</td><td align='right'>-".number_format($Documento[0],2)."</td></tr>";
										$TotEntidad=$TotEntidad-$Documento[0];
									}
								}
							}
							echo "<tr>";
								echo "<td class='filaTotalesInfContable' style='text-align: right; padding-right: 10px;' colspan=2>TOTAL ENTIDAD</td>";
								echo "<td class='filaTotalesInfContable' style='text-align: right; padding-right: 25px;'>".number_format($TotEntidad,2)."</td>";
							echo "</tr>";
							$TotCartera=$TotCartera+$TotEntidad;
							$TotEntidad=0;
						}
						echo "<tr>";
							echo "<td class='filaTotalesInfContable' style='text-align: right; padding-right: 10px;' colspan=2>TOTAL CARTERA</td>";
							echo "<td class='filaTotalesInfContable' style='text-align: right; padding-right: 25px;'>".number_format($TotCartera,2)."</td></tr>";
					}
					echo "</table>";
				?>
			</div>	
		</body>
	</html>	