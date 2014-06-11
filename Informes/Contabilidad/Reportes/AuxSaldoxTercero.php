<?
	if($DatNameSID){session_name("$DatNameSID");}
	session_start();
	include("Informes.php");
	include_once("General/Configuracion/Configuracion.php");
	if(!$CuentaIni){$CuentaIni=0;}
	if(!$CuentaFin){$CuentaFin=9999999999;}
	$PerIni="$Anio-$MesIni-$DiaIni";
	$PerFin="$AnioFin-$MesFin-$DiaFin";
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
		

			<table  width="100%" class="tablaInformeContable" border="0"  <?php  echo $cellspacingTablaInfContable; echo $cellpaddingTablaInfContable; ?>>
			<?
					echo "<tr><td>$filaCC[0]</td></tr>";
					$consPrev="Select Cuenta,Nombre,Naturaleza from Contabilidad.PlanCuentas
					where Cuenta>='$CuentaIni' and Cuenta<='$CuentaFin' and Tipo='Detalle' and Compania='$Compania[0]' and anio='$Anio'
					Group By Cuenta,Nombre,Naturaleza Order By Cuenta";
					$resPrev=ExQuery($consPrev);echo ExError($resPrev);
					while($filaPrev=ExFetch($resPrev))
					{
						if($Tercero){$condAdc1=" and Movimiento.Identificacion='$Tercero'";}
						if($Comprobante){$condAdc2=" and Movimiento.Comprobante='$Comprobante'";}

						$consT="Select Movimiento.Identificacion,(PrimApe || ' ' || SegApe || ' ' || PrimNom || ' ' || SegNom) as Tercero from Contabilidad.Movimiento,Central.Terceros where Movimiento.Identificacion=Terceros.Identificacion 
						and Fecha<='$PerFin' and Cuenta='$filaPrev[0]' 
						and Movimiento.Compania='$Compania[0]' $condAdc1 and Estado='AC' and Terceros.Compania='$Compania[0]' 
						Group By Movimiento.Identificacion,PrimApe,SegApe,PrimNom,SegNom Order By PrimApe,SegApe,PrimNom,SegNom";

						$resT=ExQuery($consT);echo ExError($resT);
						while($filaT=ExFetch($resT))
						{
							
						$SaldoI=0;
						$cons2="Select sum(Debe),sum(Haber) from Contabilidad.Movimiento where Fecha<'$PerIni' and Cuenta='$filaPrev[0]' and Compania='$Compania[0]' and Identificacion='$filaT[0]' $condAdc1 $condAdc2 and Estado='AC'";

						$res2=ExQuery($cons2);
						$fila2=ExFetch($res2);echo ExError($res2);
						$DebitosSI=$fila2[0];$CreditosSI=$fila2[1];
						if($filaPrev[2]=="Debito"){$SaldoI=$DebitosSI-$CreditosSI;$MovSI="Debito";}
						elseif($filaPrev[2]=="Credito"){$SaldoI=$CreditosSI-$DebitosSI;$MovSI="Credito";}
					
						 $cons="Select Cuenta,Comprobante,Numero,Fecha,Debe,Haber,Detalle,UsuarioCre,(PrimApe || ' ' || SegApe || ' '  || PrimNom || ' ' || SegNom) as Tercero,Movimiento.Identificacion,Movimiento.Detalle,NoCheque,DocSoporte 
						from Contabilidad.Movimiento,Central.Terceros 
						where Movimiento.Identificacion=Terceros.Identificacion and Movimiento.Identificacion='$filaT[0]' and Cuenta='$filaPrev[0]' and Fecha>='$PerIni' and Fecha<='$PerFin' 
						and Terceros.Compania='$Compania[0]'
						and Movimiento.Compania='$Compania[0]' $condAdc1 $condAdc2 and Estado='AC' Order By Cuenta,Tercero,Fecha,Detalle";
						$res=ExQuery($cons,$conex);echo ExError($res);

						if($SaldoI!=0){$Muestre=1;}
						if(ExNumRows($res)>0){$Muestre=1;}

						if($Muestre==1){
						echo "<tr><td>&nbsp;</td></tr>";
						echo "<tr><td>&nbsp;</td></tr>";
						echo "<tr><td>&nbsp;</td></tr>";
						echo "<tr>";
							echo "<td class='encabezado2HorizontalInfCont'>CUENTA</td>";
							echo "<td class='encabezado2HorizontalInfCont' colspan=7>$filaPrev[0] - $filaPrev[1]<br>$filaT[0] $filaT[1]</td>";
							echo "<td class='encabezado2HorizontalInfCont'>SALDO</td>";
						echo "<td class='encabezado2HorizontalInfCont' style='text-align:right; padding-right:10px;'>".number_format($SaldoI,2)."</td>";
						echo "</tr>";
						echo "<tr>";
							echo "<td class='encabezado2HorizontalInfContInv'>FECHA</td>";
							echo "<td class='encabezado2HorizontalInfContInv'>COMPROBANTE</td>";
							echo "<td class='encabezado2HorizontalInfContInv'>N&Uacute;MERO</td>";
							echo "<td class='encabezado2HorizontalInfContInv'>CHEQUE</td>";
							echo "<td class='encabezado2HorizontalInfContInv'>DOC. REF.</td>";
							echo "<td class='encabezado2HorizontalInfContInv'>DESCRIPCI&Oacute;N</td>";
							echo "<td class='encabezado2HorizontalInfContInv'>USUARIO</td>";
							echo "<td class='encabezado2HorizontalInfContInv'>D&Eacute;BITOS</td>";
							echo "<td class='encabezado2HorizontalInfContInv'>CR&Eacute;DITOS</td>";
							echo "<td class='encabezado2HorizontalInfContInv'>SALDO</td>";

						while($fila=ExFetchArray($res))
						{
							if($Fondo==1){$BG="#EEF6F6";$Fondo=0;}
							else{$BG="white";$Fondo=1;}
					
							if($filaPrev[2]=="Debito"){$SaldoF=$SaldoI+$fila['debe']-$fila['haber'];}
							elseif($filaPrev[2]=="Credito"){$SaldoF=$SaldoI+$fila['haber']-$fila['debe'];}
					
							echo "<tr bgcolor='$BG'><td>".$fila['fecha']."</td><td>".$fila['comprobante']."</a></td><td>".
							substr("000000000",1,8-strlen($fila['numero'])).$fila['numero']."</td>";
							echo "<td>".$fila['noCheque']."</td>";
							echo "<td>".$fila['docsoporte']."</td>";
							echo "<td>".$fila['detalle']
							."</td>";
							echo "<td>".$fila['usuariocre']."</td>
							<td align='right'>".number_format($fila['debe'],2)."</td>
							<td align='right'>".number_format($fila['haber'],2)."</td><td align='right'>".number_format($SaldoF,2)."</td>";
							echo "</tr>";
							$SaldoI=$SaldoF;
							$SumDebitos=$SumDebitos+$fila['debe'];$SumCreditos=$SumCreditos+$fila['haber'];
							$SumDebe=$SumDebe+$fila['debe'];$SumHaber=$SumHaber+$fila['haber'];
						}
						$Fondo=0;
						$Muestre=0;
						}
						if($SumDebe || $SumHaber){
							echo "<tr>";
								echo "<td class='filaTotalesInfContable' style='text-align:right;padding-right:10px;' colspan=7>SUMAS</td>";
								echo "<td class='filaTotalesInfContable' style='text-align:right;padding-right:10px;'>".number_format($SumDebe,2)."</td>";
								echo "<td class='filaTotalesInfContable' style='text-align:right;padding-right:10px;'>".number_format($SumHaber,2)."</td>";
							echo"</tr>";
						}
						$SumDebe=0;$SumHaber=0;
						}
					}
					echo "<tr>";
						echo "<td class='filaTotalesInfContable' style='text-align:right;padding-right:10px;'colspan=7>SUMAS</td>";
						echo "<td class='filaTotalesInfContable' style='text-align:right;padding-right:10px;'>".number_format($SumDebitos,2)."</td>";
						echo "<td class='filaTotalesInfContable' style='text-align:right;padding-right:10px;'>".number_format($SumCreditos,2)."</td>";
					echo "</tr>";
			?>
			</table>