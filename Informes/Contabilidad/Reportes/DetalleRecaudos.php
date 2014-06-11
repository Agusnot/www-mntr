		<?
			session_name("$DatNameSID");
			session_start();
			include("Funciones.php");
			include_once("General/Configuracion/Configuracion.php");
			$ND=getdate();
			if(!$CuentaIni){$CuentaIni=0;}
			if(!$CuentaFin){$CuentaFin=9999999999;}
			$PerIni="$Anio-$MesIni-$DiaIni";
			$PerFin="$Anio-$MesFin-$DiaFin";

			$cons="Select NoCaracteres from Contabilidad.EstructuraPuc where Compania='$Compania[0]' and Anio=$Anio Order By Nivel";
			$res=ExQuery($cons,$conex);
			while($fila=ExFetchArray($res))
			{
				$Nivel++;$TotNivel++;
				if(!$fila[0]){$fila[0]="-100";}
				$TotCaracteres=$TotCaracteres+$fila[0];
				$Digitos[$Nivel]=$TotCaracteres;
			}

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
				$caracteristicas= "PERIODO : ".$PerIni." A  ".$PerFin;
				$fechaimpresion= "FECHA DE IMPRESI&Oacute;N : ".$ND[year]."-".$ND[mon]."-".$ND[mday];
				encabezadoInformeContable(strtoupper($Compania[0]), $Compania[1], "INFORME DETALLADO DE RECAUDOS", $caracteristicas,$fechaimpresion);
				?>
				
				<table class="tablaInformeContable"  style="margin-top:25px;"  <?php echo $borderTablaInfContable; echo $bordercolorTablaInfContable; echo $cellspacingTablaInfContable; echo $cellpaddingTablaInfContable; ?> >
					<tr>
						<?

							$cons2="Select sum(Debe),sum(Haber),Cuenta,date_part('year',Fecha) as MovAnio from Contabilidad.Movimiento 
							where Fecha<'$PerIni' and Compania='$Compania[0]' and Estado='AC' and Cuenta!='0' and Cuenta!='1' and $ExcluyeComprobantes and
							Cuenta>='$CuentaIni' and Cuenta<='$CuentaFin' $CondCC
							Group By Cuenta,MovAnio Order By Cuenta";
							$res2=ExQuery($cons2);
							while($fila2=ExFetch($res2))
							{
								$CuentaMad=substr($fila2[2],0,1);
								if(($CuentaMad==4 || $CuentaMad==5 || $CuentaMad==6 || $CuentaMad==7 || $CuentaMad==0) && $Anio!=$fila2[3]){}
								else{
								for($Nivel=1;$Nivel<=$TotNivel;$Nivel++)
								{
									$ParteCuenta=substr($fila2[2],0,$Digitos[$Nivel]);
									if($ParteAnt!=$ParteCuenta){
									$SICuenta[$ParteCuenta]['debitos']=$SICuenta[$ParteCuenta]['debitos']+$fila2[0];
									$SICuenta[$ParteCuenta]['creditos']=$SICuenta[$ParteCuenta]['creditos']+$fila2[1];}
									$ParteAnt=$ParteCuenta;
								}
								}
							}


							$cons="Select PlanCuentas.Cuenta,PlanCuentas.Nombre from Contabilidad.Movimiento,Contabilidad.PlanCuentas 
							where PlanCuentas.Cuenta=Movimiento.Cuenta and Estado='AC' and Movimiento.Compania='$Compania[0]' and PlanCuentas.Compania='$Compania[0]' 
							and Movimiento.Anio=$Anio  and PlanCuentas.Anio=$Anio
							and Fecha>='$PerIni' and Fecha<='$PerFin' and PlanCuentas.Cuenta>='$CuentaIni' and PlanCuentas.Cuenta<='$CuentaFin'
							Group By PlanCuentas.Cuenta,PlanCuentas.Nombre";

							$res=ExQuery($cons);echo ExError();
							while($fila=ExFetch($res))
							{
								$SaldoIni=$SICuenta[$fila[0]]['debitos']-$SICuenta[$fila[0]]['creditos'];
								echo "<tr>";
									echo "<td colspan='5' class='encabezado2HorizontalInfCont' >$fila[0] $fila[1]</td>";
								echo "</tr>";	
								echo "<tr>";
									echo "<td class='encabezado2HorizontalInfContInv' colspan='3'>SALDO INICIAL:</td>";
									echo "<td  class='encabezado2HorizontalInfContInv' colspan='2'>" . number_format($SaldoIni,2) . " </td>";
								echo "</tr>";	

								echo "<tr";
								echo "<td class='encabezado2HorizontalInfCont'>FECHA</td>";
								echo "<td class='encabezado2HorizontalInfCont'>COMPROBANTE</td>";
								echo "<td class='encabezado2HorizontalInfCont'>N&Uacute;MERO</td>";
								echo "<td class='encabezado2HorizontalInfCont'>DEBE</td>";
								echo "<td class='encabezado2HorizontalInfCont'> HABER</td></tr>";
								$cons2="Select Comprobante,Numero,Debe,Haber,Fecha from Contabilidad.Movimiento where Movimiento.Compania='$Compania[0]' and Compania='$Compania[0]' and Cuenta='$fila[0]'
								and Estado='AC' and Fecha>='$PerIni' and Fecha<='$PerFin' Order By Fecha,Numero";
								$res2=ExQuery($cons2);echo ExError();
								while($fila2=ExFetch($res2))
								{
									echo "<tr>";
										echo "<td>$fila2[4]</td>";
										echo "<td>$fila2[0]</td>";
										echo "<td align='right'>$fila2[1]</td>";
										echo "<td align='right'>".number_format($fila2[2],2)."</td>";
										echo "<td align='right'>".number_format($fila2[3],2)."</td>";
									echo "</tr>";
									$SumDebe=$SumDebe+$fila2[2];
									$SumHaber=$SumHaber+$fila2[3];
								}
								echo "<tr>";
									echo"<td class='filaTotalesInfContable' style='text-align: right; padding-right: 10px;' colspan=3>SUMAS</td>";
									echo "<td class='filaTotalesInfContable'>". number_format($SumDebe,2)."</td><td>" . number_format($SumHaber,2)."</td>";
								echo "</tr>";
								$Total=$Total+$SubTotal;
								$SubTotal=0;
							}
							$SaldoSig=$SaldoIni+$SumDebe-$SumHaber;
							echo "<tr>";
								echo "<td class='filaTotalesInfContable' style='text-align: right; padding-right: 10px;' colspan='4'>SALDO SIGUIENTE</td>";
								echo "<td class='filaTotalesInfContable' style='text-align: right; padding-right: 10px;'>".number_format($SaldoSig,2)."</td>";
							echo "</tr>";

				?>
						
				</table>