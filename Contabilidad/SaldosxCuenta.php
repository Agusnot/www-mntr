		<?
			if($DatNameSID){session_name("$DatNameSID");}
			session_start();
			include("Funciones.php");
			include_once("General/Configuracion/Configuracion.php");
			$Anio=$AnioAc;
			if(!$Cuenta && $Cuenta!='0'){exit;}
		?>

	<html>
		<head>
			<?php echo $codificacionMentor; ?>
			<?php echo $autorMentor; ?>
			<?php echo $titleMentor; ?>
			<?php echo $iconMentor; ?>
			<?php echo $shortcutIconMentor; ?>
			<link rel="stylesheet" type="text/css" href="../General/Estilos/estilos.css">
		</head>
		
		<body>
			<div align="center">
				<table  width="100%" class="tabla2" style="text-align:center;"  <?php echo $borderTabla2Mentor ; echo $bordercolorTabla2Mentor ; echo $cellspacingTabla2Mentor ; echo $cellpaddingTabla2Mentor; ?>>

					<tr>
						<td class="encabezado2Horizontal">MES</td>
						<td class="encabezado2Horizontal">SALDO INICIAL</td>
						<td class="encabezado2Horizontal">DEBITOS</td>
						<td class="encabezado2Horizontal">CREDITOS</td>
						<td class="encabezado2Horizontal">SALDO FINAL</td>
					</tr>
					<?
						if($TerceroSel)
						{
							$cons="Select PrimApe,SegApe,PrimNom from Central.Terceros where Identificacion='$TerceroSel' and Terceros.Compania='$Compania[0]'";
							$res=ExQuery($cons);echo ExError($res);
							$fila=ExFetch($res);
							$CondAdc=" and Identificacion='$TerceroSel'";
							echo "<tr>";
								echo "<td class='encabezado2Horizontal' colspan=5>TERCERO SELECCIONADO: $TerceroSel - " . strtoupper("$fila[0] $fila[1] $fila[2] $fila[3]") . "</td>";
								echo "</tr>";
						}

						$consPrev="Select Naturaleza from Contabilidad.PlanCuentas where Cuenta='$Cuenta' and Compania='$Compania[0]' and Anio=$AnioAc";
						$resPrev=ExQuery($consPrev,$conex);
						$filaPrev=ExFetch($resPrev);

						if($filaPrev[0]=="Debito"){$SaldoI=$MATMOVSICuenta[$Cuenta][0]-$MATMOVSICuenta[$Cuenta][1];}
						if($filaPrev[0]=="Credito"){$SaldoI=$MATMOVSICuenta[$Cuenta][1]-$MATMOVSICuenta[$Cuenta][0];}


						for($i=1;$i<=12;$i++)
						{
							if($Fondo==1){$BG="#EEF6F6";$Fondo=0;}
							else{$BG="white";$Fondo=1;}
							echo "<tr bgcolor='$BG'><td style='text-align:left; padding-left:10px;'>".strtoupper($NombreMes[$i])."</td>";
							if($filaPrev[0]=="Debito"){$SaldoF=$SaldoI-$MATMOVMPCuenta[$Cuenta][1][$i]+$MATMOVMPCuenta[$Cuenta][0][$i];}
							elseif($filaPrev[0]=="Credito"){$SaldoF=$SaldoI+$MATMOVMPCuenta[$Cuenta][1][$i]-$MATMOVMPCuenta[$Cuenta][0][$i];}
							echo "<td>".number_format($SaldoI,2)."</td><td>".number_format($MATMOVMPCuenta[$Cuenta][0][$i],2)."</td><td>".number_format($MATMOVMPCuenta[$Cuenta][1][$i],2)."</td><td >".number_format($SaldoF,2)."</td></tr>";
							$SumDeb=$SumDeb+$Debitos;
							$SumCred=$SumCred+$Creditos;
							$SaldoI=$SaldoF;
						}
					?>
					<tr>
						<td class="filaTotalesInfContable" style="text-align:center" colspan="2">TOTAL</td>
						<td class="filaTotalesInfContable" style="text-align:center" ><?echo number_format($SumDeb,2)?></td>
						<td class="filaTotalesInfContable" style="text-align:center"><?echo number_format($SumCred,2)?></td>
						<td class="filaTotalesInfContable">&nbsp;</td>
					</tr>
				</table>
			</div>	
		</body>