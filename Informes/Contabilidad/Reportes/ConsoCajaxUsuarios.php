<?
	if($DatNameSID){session_name("$DatNameSID");}
	session_start();
	include("Funciones.php");
	include_once("General/Configuracion/Configuracion.php");
	$ND=getdate();
	$Corte="$Anio-$MesFin-$DiaFin";
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
			
			
			<?php 
				$caracteristicas = "CORTE A: ".$Corte ;
				$fechaimpresion = "FECHA DE IMPRESION $ND[year]-$ND[mon]-$ND[mday]";
				encabezadoInformeContable($Compania[0], $Compania[1], "CONSOLIDADO DIARIO DE CAJA POR USUARIOS", $caracteristicas,$fechaimpresion);
			?>	

			<table  rules="groups" class="tablaInformeContable" style="text-align:justify;" <?php echo $borderTablaInfContable; echo $bordercolorTablaInfContable; echo $cellspacingTablaInfContable; echo $cellpaddingTablaInfContable; ?>>
		<?
				$consCta="Select Cuenta from Contabilidad.PlanCuentas where Cuenta like '110505%' and Compania='$Compania[0]' and Tipo='Detalle' and Anio=$Anio";
				$resCta=ExQuery($consCta);
				while($filaCta=ExFetch($resCta))
				{
					$consPrev="Select Comprobante from Contabilidad.Movimiento  where Fecha='$Corte' and Cuenta='$filaCta[0]' and Estado='AC' and Debe>0 Group By Comprobante";
					$resPrev=ExQuery($consPrev);
					while($filaPrev=ExFetch($resPrev))
					{
						echo "<tr>";
							echo "<td colspan='2' class='encabezado2HorizontalInfCont'  >".strtoupper($filaPrev[0])."</td>";
						echo "</tr>";
						echo "<tr>";
							echo "<td class='encabezado2HorizontalInfCont'>USUARIO</td>";
							echo "<td class='encabezado2HorizontalInfCont'>INGRESOS</td></tr>";
						$cons="Select sum(Debe),UsuarioCre from Contabilidad.Movimiento where Comprobante='$filaPrev[0]' and Cuenta='$filaCta[0]' 
						and (CierrexCajero='$Corte') and Estado='AC' Group By UsuarioCre";
						$res=ExQuery($cons);echo mysql_error();
						while($fila=ExFetch($res))
						{
							echo "<tr><td>$fila[1]</td><td style='text-align:right; padding-right:10px;'>".number_format($fila[0],2)."</td></tr>";
							$Total=$Total+$fila[0];
						}
					echo "<tr>";
						echo "<td class='encabezado2HorizontalInfCont' style='text-align:right; padding-right:10px;' >TOTAL</td>";
						echo "<td class='encabezado2HorizontalInfCont' style='text-align:right; padding-right:10px;'>".number_format($Total,2)."</td>";
					echo "</tr>";
				}
				$TotIngresos=$Total;
				$Total=0;
			
				$consPrev="Select Comprobante from Contabilidad.Movimiento  where Fecha='$Corte' and Cuenta='$filaCta[0]' and Estado='AC' and Haber>0 Group By Comprobante";
				$resPrev=ExQuery($consPrev);
				while($filaPrev=ExFetch($resPrev))
				{
					echo "<tr><td colspan=2 class='encabezado2HorizontalInfCont'>".strtoupper($filaPrev[0])."</td></tr>";
					echo "<tr><td class='encabezado2HorizontalInfCont' style='text-align:right; padding-right: 10px;'>USUARIO</td><td class='encabezado2HorizontalInfCont'>EGRESOS</td></tr>";
					$cons="Select sum(Haber),UsuarioCre from Contabilidad.Movimiento where Comprobante='$filaPrev[0]' and Cuenta='$filaCta[0]' 
					and (Fecha='$Corte') and Estado='AC' and Compania='$Compania[0]' Group By UsuarioCre";
					$res=ExQuery($cons);echo mysql_error();
					while($fila=ExFetch($res))
					{
						echo "<tr><td>$fila[1]</td><td align='right'>".number_format($fila[0],2)."</td></tr>";
						$Total=$Total+$fila[0];
					}
					echo "<tr bgcolor='#e5e5e5' style='font-weight:bold' align='right'><td>TOTAL</td><td align='right'>".number_format($Total,2)."</td></tr>";
				}
				$TotEgresos=$Total;
			
				$consPrev="Select Cuenta,Nombre,Naturaleza from Contabilidad.PlanCuentas
				where Cuenta='$filaCta[0]' and Compania='$Compania[0]'";
				$resPrev=ExQuery($consPrev);echo mysql_error();
				$filaPrev=ExFetch($resPrev);
			
				$cons2="Select sum(Debe) from Contabilidad.Movimiento where CierrexCajero<'$Corte' and CierrexCajero IS NOT NULL 
				and Cuenta='$filaCta[0]' and Compania='$Compania[0]' and Estado='AC'";
			
				$res2=ExQuery($cons2);
				$fila2=ExFetch($res2);echo mysql_error();
				$DebitosSI=$fila2[0];
				
				$cons2="Select sum(Haber) from Contabilidad.Movimiento where Fecha<'$Corte'  and Cuenta='$filaCta[0]' and Compania='$Compania[0]' and Estado='AC'";
				$res2=ExQuery($cons2);
				$fila2=ExFetch($res2);echo mysql_error();
				$CreditosSI=$fila2[0];
			
				if($filaPrev[2]=="Debito"){$SaldoI=$DebitosSI-$CreditosSI;$MovSI="Debito";}
				elseif($filaPrev[2]=="Credito"){$SaldoI=$CreditosSI-$DebitosSI;$MovSI="Credito";}
				$SaldoF=$SaldoI+$TotIngresos-$TotEgresos;
		?>
		</table><br><br>

			<div align="center">
				<table  width="250px" class="tablaInformeContable" style="text-align:justify;" <?php echo $borderTablaInfContable; echo $bordercolorTablaInfContable; echo $cellspacingTablaInfContable; echo $cellpaddingTablaInfContable; ?>>
					<tr>
						<td class='encabezado2HorizontalInfCont'>SALDO INICIAL</td>
						<td class='encabezado2HorizontalInfCont' style="text-align:right; padding-right:10px"><?echo number_format($SaldoI,2)?></td>
					</tr>
					<tr>
						<td >INGRESOS</td>
						<td style="text-align:right; padding-right:10px"><?echo number_format($TotIngresos,2)?></td>
					</tr>
					<tr>
						<td >EGRESOS</td>
						<td style="text-align:right; padding-right:10px"><?echo number_format($TotEgresos,2)?></td>
					</tr>
					<tr>
					<td class='encabezado2HorizontalInfCont'>SALDO FINAL</td>
					<td class='encabezado2HorizontalInfCont' style="text-align:right; padding-right:10px" ><?echo number_format($SaldoF,2)?></td>
				</tr>
				</table>
				
				<br><br>

				<?	}?>

		____________________________________<br>
		<?echo strtoupper($usuario[0]);
		?>
	</div>
	</body>
</html>	