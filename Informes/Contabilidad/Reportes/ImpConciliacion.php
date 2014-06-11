	<?
			if($DatNameSID){session_name("$DatNameSID");}
			session_start();
			include("Informes.php");
			include_once("General/Configuracion/Configuracion.php");
			
			$ND=getdate();
			$cons="Select SaldoExtracto from Contabilidad.SaldosConciliacion where Anio=$Anio and Mes=$Mes and Cuenta='$Cuenta' and Compania='$Compania[0]'";
			$res=ExQuery($cons);
			$fila=ExFetch($res);
			$SaldoExtracto=$fila[0];

			$cons="Select Nombre,Cuenta from Contabilidad.PlanCuentas where Cuenta='$Cuenta' and Compania='$Compania[0]' and Anio=$Anio";
			$res=ExQuery($cons);
			$fila=ExFetch($res);
			$NomBanco=$fila[0];

			$cons="Select NumDias from Central.Meses where Numero=$Mes";
			$res=ExQuery($cons);
			$fila=ExFetch($res);
			$MaxNumDias=$fila[0];


			$cons3="Select sum(Debe),sum(Haber),Movimiento.Cuenta,Naturaleza from Contabilidad.Movimiento,Contabilidad.PlanCuentas 
			where Movimiento.Cuenta=PlanCuentas.Cuenta and Fecha<='$Anio-$Mes-$MaxNumDias' and Movimiento.Compania='$Compania[0]' 
			and PlanCuentas.Compania='$Compania[0]'
			and Estado='AC' and Movimiento.Cuenta='$Cuenta' 
			and PlanCuentas.Anio=$Anio
			Group By Movimiento.Cuenta,Naturaleza
			Order By Cuenta";
			$res3=ExQuery($cons3);echo ExError();
			$fila3=ExFetch($res3);

			$Debitos=$fila3[0];$Creditos=$fila3[1];
			if($fila3[3]=="Debito"){$SaldoF=$SaldoI-$Creditos+$Debitos;}
			elseif($fila3[3]=="Credito"){$SaldoF=$SaldoI+$Creditos-$Debitos;}
			$SaldoLibros=$SaldoF;
			$cons2="Select Mes from Central.Meses where Numero=$Mes";
			$res2=ExQuery($cons2);
			$fila2=ExFetch($res2);
			$MesConcil=$fila2[0];
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
				<div align="center" style="margin-top:50px;margin-bottom:50px;">
					<table width="70%"  rules="groups" class="tablaInformeContable"  border="0" style="margin-top:25px;"  <?php echo $cellspacingTablaInfContable; echo $cellpaddingTablaInfContable; ?> >
						<tr>
							<td colspan="8">
								<?php 
									$caracteristicas= strtoupper($MesConcil);
									$fechaimpresion= "FECHA DE IMPRESI&Oacute;N : ".$ND[year]."-".$ND[mon]."-".$ND[mday];
									encabezadoInformeContable(strtoupper($Compania[0]), $Compania[1], "CONCILIACION BANCARIA", $caracteristicas,$fechaimpresion);
								?>
							</td>							
						</tr>
					</table>
					
					<table width="70%" border="1" rules="groups" class="tablaInformeContable" style="margin-top:25px;"  <?php echo $borderTablaInfContable; echo $bordercolorTablaInfContable; echo $cellspacingTablaInfContable; echo $cellpaddingTablaInfContable; ?>>
						<tr>
							<td class='encabezado2HorizontalInfCont'>BANCO</td>
							<td class='encabezado2HorizontalInfCont'><?echo $NomBanco?> - <?echo $Cuenta?> </td>
						</tr>
						<tr>
							<td style="text-align:right; margin-right:10px;">SALDO SEG&Uacute;N EXTRACTO</td>
							<td ><?echo number_format($SaldoExtracto,2)?></td>
						</tr>
						<tr>
							<td style="text-align:right; margin-right:10px;">SALDO SEG&Uacute;N LIBRO</td>
							<td ><?echo number_format($SaldoLibros,2)?></td>
						</tr>
					
					<?
					$cons="SELECT Comprobante,sum(Debe),sum(Haber) FROM Contabilidad.Movimiento WHERE Fecha<='$Anio-$Mes-$MaxNumDias' and Cuenta='$Cuenta' and (FechaConciliado IS NULL Or (FechaConciliado>'$Anio-$Mes-01'))
					and Compania='$Compania[0]' and Estado='AC' Group By Comprobante
					Union SELECT Comprobante,sum(Debe),sum(Haber) FROM Contabilidad.PartidaInicialConciliatoria WHERE Fecha<='$Anio-$Mes-$MaxNumDias' and Cuenta='$Cuenta' and (FechaConciliado IS NULL Or (FechaConciliado>'$Anio-$Mes-01'))
					and Compania='$Compania[0]'
					Group By Comprobante";

					$res=ExQuery($cons);echo ExError();
					while($fila=ExFetch($res))
					{
						if($fila[1])
						{
							$Valor=-$fila[1];$Car="-";
							echo "<tr><td>$Car $fila[0] NO REGISTRADO EN EXTRACTO</td><td align='right'>".number_format($Valor,2)."</td></tr>";
							$SaldoConcic=$SaldoConcic+$Valor;
						}
						if($fila[2])
						{
							$Valor=$fila[2];$Car="+";
							echo "<tr><td>$Car $fila[0] NO REGISTRADO EN EXTRACTO</td><td align='right'>".number_format($Valor,2)."</td></tr>";
							$SaldoConcic=$SaldoConcic+$Valor;
						}
					}

					$SaldoConcic=$SaldoConcic+$SaldoLibros;
					$Diferencia=$SaldoExtracto-$SaldoConcic;
				?>	
				<tr>
					<td class="filaTotalesInfContable" style="text-align:right;">SALDO CONCILIADO</td>
					<td class="filaTotalesInfContable" ><?echo number_format($SaldoConcic,2)?></td>
				</tr>
				<tr>
					<td class="filaTotalesInfContable" style="text-align:right;">DIFERENCIA</td>
					<td class="filaTotalesInfContable" ><?echo number_format($Diferencia,2)?></td>
				</tr>
				
				
				<table width="100%" border="1" rules="groups" bordercolor="#ffffff" style="font-family:<?echo $Estilo[8]?>;font-size:11;font-style:<?echo $Estilo[10]?>">
				<tr><td colspan="5" align="center"><strong>DETALLE DE MOVIMIENTOS NO REGISTRADOS EN EXTRACTO</td></tr>
				<?
					$cons="SELECT Comprobante FROM Contabilidad.Movimiento
					WHERE Fecha<='$Anio-$Mes-$MaxNumDias' and Cuenta='$Cuenta' and (FechaConciliado IS NULL Or (FechaConciliado>'$Anio-$Mes-01')) and Compania='$Compania[0]' and Estado='AC'
					Union Select Comprobante from Contabilidad.PartidaInicialConciliatoria
					WHERE Fecha<='$Anio-$Mes-$MaxNumDias' and Cuenta='$Cuenta' and (FechaConciliado IS NULL Or (FechaConciliado>'$Anio-$Mes-01')) and Compania='$Compania[0]' Group By Comprobante";

					$res=ExQuery($cons);echo ExError();
					while($fila=ExFetch($res))
					{
						echo "<tr bgcolor='#e5e5e5'><td colspan=6 style='font-weight:bold;text-align:center'>$fila[0]</td></tr>";
						echo "<tr bgcolor='#e5e5e5' style='font-weight:bold;text-align:center'><td>Fecha</td><td>Numero</td><td>Cheque</td><td>Tercero</td><td>Debito</td><td>Credito</td></tr>";

						$cons1="Select Fecha,Numero,NoCheque,Identificacion,Debe,Haber,FechaConciliado,'Movimiento',AutoId from Contabilidad.Movimiento 
						where Fecha<='$Anio-$Mes-$MaxNumDias' and Cuenta='$Cuenta' and (FechaConciliado IS NULL Or (FechaConciliado>'$Anio-$Mes-01'))
						and Comprobante='$fila[0]' and Compania='$Compania[0]' and Estado='AC' 
						Union Select Fecha,Numero,NoCheque,Identificacion,Debe,Haber,FechaConciliado,'PartidaInicial',AutoId from Contabilidad.PartidaInicialConciliatoria 
						where Fecha<='$Anio-$Mes-$MaxNumDias' and Cuenta='$Cuenta' and (FechaConciliado IS NULL Or (FechaConciliado>'$Anio-$Mes-01'))
						and Comprobante='$fila[0]' and Compania='$Compania[0]' Order By Numero";

						$res1=ExQuery($cons1);echo ExError();
						while($fila1=ExFetch($res1)){
							if($Fondo==1){$BG="#EEF6F6";$Fondo=0;}
							else{$BG="white";$Fondo=1;}

							$cons2="Select PrimApe,SegApe,PrimNom,SegNom from Central.Terceros where Identificacion='$fila1[3]' and Terceros.Compania='$Compania[0]'";
							$res2=ExQuery($cons2);
							$fila2=ExFetch($res2);
							$Tercero="$fila2[0] $fila2[1] $fila2[2] $fila2[3]";
							echo "<tr bgcolor='$BG'><td>$fila1[0]</td><td align='right'>$fila1[1]</td><td align='right'>$fila1[2]</td><td>$fila1[3] $Tercero</td>";
							echo "<td align='right'>".number_format($fila1[4],2)."</td>";
							echo "<td align='right'>".number_format($fila1[5],2)."</td>";
							echo "</tr>";
						}
					}
				?>
				</table>
				<br><br><br>
				</table>
				<table class="tablaInformeContable"  border="0" style="margin-top:25px;"  <?php echo $cellspacingTablaInfContable; echo $cellpaddingTablaInfContable; ?>>
					<tr>
						<td style="text-align:center;" width="250px">_______________________________________________<br></td>
						<td width="100px"> &nbsp; </td>
						<td style="text-align:center;" width="250px">_______________________________________________<br></td>
					</tr>
					<tr>					
						<td width="250px"> APROB&Oacute;</td>
						<td width="100px"> &nbsp; </td>
						<td width="250px"> ELABOR&Oacute;</td>				
					</tr>
				</table>
			</div>	
		</body>
	</html>	