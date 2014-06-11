		<?
			if($DatNameSID){session_name("$DatNameSID");}
			session_start();
			include("Informes.php");
			include_once("General/Configuracion/Configuracion.php");
			$ND=getdate();
			$PerIni="$Anio-$MesIni-$DiaIni";
			$PerFin="$Anio-$MesFin-$DiaFin";


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
				$caracteristicas= "CORTE A : ".$PerFin;
				$fechaimpresion= "FECHA DE IMPRESI&Oacute;N : ".$ND[year]."-".$ND[mon]."-".$ND[mday];
				encabezadoInformeContable(strtoupper($Compania[0]), $Compania[1], "INFORME DE RETENCIONES", $caracteristicas,$fechaimpresion);

					
					$cons="Select Cuenta,Comprobante,Numero from Contabilidad.Movimiento where Cuenta>='$CuentaIni' and Cuenta<='$CuentaFin' and Fecha>='$PerIni' and Fecha<='$PerFin' and Estado='AC' and Movimiento.Compania='$Compania[0]'";

					$res=ExQuery($cons);
					while($fila=ExFetch($res))
					{
						$cons1="Select Haber,ConceptoRte from Contabilidad.Movimiento where Comprobante='$fila[1]' and Numero='$fila[2]' and ConceptoRte!='0' and
						ConceptoRte!=''
						and Estado='AC' and Movimiento.Compania='$Compania[0]' and Cuenta NOT ilike '1%'";

						$res1=ExQuery($cons1);
						while($fila1=ExFetch($res1))
						{
							$ConceptoRte[$fila[0]][$fila1[1]]=$ConceptoRte[$fila[0]][$fila1[1]]+$fila1[0];
						}

					}


					$cons="Select Cuenta from Contabilidad.Movimiento where Cuenta>='$CuentaIni' and Cuenta<='$CuentaFin' 
					and Fecha>='$PerIni' and Fecha<='$PerFin' and Estado='AC' and Movimiento.Compania='$Compania[0]' Group By Cuenta";
					$res=ExQuery($cons);
					while($fila=ExFetch($res))
					{
						$cons2="Select Nombre from Contabilidad.PlanCuentas where Cuenta='$fila[0]' and Compania='$Compania[0]' and Anio=$Anio";
						$res2=ExQuery($cons2);
						$fila2=ExFetch($res2);
						$NomCuenta=$fila2[0];
						if(count($ConceptoRte[$fila[0]])>0){
							?>
						<table  width='70%' class='tablaInformeContable' width='90%' style='margin-top:25px;'  <?php echo $borderTablaInfContable; echo $bordercolorTablaInfContable; echo $cellspacingTablaInfContable; echo $cellpaddingTablaInfContable; ?> >
							<tr>
								<td class='encabezado2HorizontalInfCont' colspan=2><?php echo "$NomCuenta - $fila[0]";?></td>
							</tr>
							<tr>
								<td class='encabezado2HorizontalInfCont'>CONCEPTO</td>
								<td class='encabezado2HorizontalInfCont'>VALOR</td>
							</tr>
							
							<?php	
							while (list($val,$cad) = each ($ConceptoRte[$fila[0]])) {
								if($Fondo==1){$BG="#EEF6F6";$Fondo=0;}
								else{$BG="white";$Fondo=1;}
								echo "<tr><td bgcolor='$BG'>$val</td>";
									echo "<td bgcolor='$BG'>".number_format($cad,2)."</td>";
								echo "</tr>";
								$SubTotal=$SubTotal+$cad;
							}
							?>
							
							<tr>
								<td class='filaTotalesInfContable' style='text-align: right; padding-right: 10px;'>SUBTOTAL</td>
								<td class='filaTotalesInfContable' ><?php echo number_format($SubTotal,2);?></td>
							</tr>
							<?php
						$SubTotal=0;
						echo "</table><br><br>";}
					}
				?>

				<table  width="70%" class="tablaInformeContable" style="margin-top:25px;" <?php echo $borderTablaInfContable; echo $bordercolorTablaInfContable; echo $cellspacingTablaInfContable; echo $cellpaddingTablaInfContable; ?>>
					<tr>
						<td class='encabezado2HorizontalInfCont' colspan="2">RESUMEN</td>
					</tr>
				<?
					$SubTotal=0;
					echo "<tr>";
						echo "<td class='encabezado2HorizontalInfContInv'>CONCEPTO</td>";
						echo "<td class='encabezado2HorizontalInfContInv'>VALOR</td>";
					echo "</tr>";
					$cons1="Select sum(Haber),ConceptoRte from Contabilidad.Movimiento where Fecha>='$PerIni' and Fecha<='$PerFin' and ConceptoRte!='0' and ConceptoRte!=''
					and Estado='AC' and Movimiento.Compania='$Compania[0]' and Cuenta NOT ilike '1%'
					Group By ConceptoRte";
					$res1=ExQuery($cons1);
					while($fila1=ExFetch($res1))
					{
						if($Fondo==1){$BG="#EEF6F6";$Fondo=0;}
						else{$BG="white";$Fondo=1;}
					
						echo "<tr bgcolor='$BG'><td>$fila1[1]</td><td align='right'>".number_format($fila1[0],2)."</td></tr>";
						$SubTotal=$SubTotal+$fila1[0];
					}

					echo "<tr>";
						echo "<td class='filaTotalesInfContable' style='text-align: right; padding-right: 10px;'>SUBTOTAL</td>";
						echo "<td class='filaTotalesInfContable'>".number_format($SubTotal,2)."</td></tr>";
				?>
				</table>
			</div>
		</body>	
	</html>	