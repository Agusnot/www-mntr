<?
	if($DatNameSID){session_name("$DatNameSID");}
	session_start();
	include("Funciones.php");
	include_once("General/Configuracion/Configuracion.php");	
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
			<div align="center">
					<table class="tablaInformeContable"  <?php echo $borderTablaInfContable; echo $bordercolorTablaInfContable; echo $cellspacingTablaInfContable; echo $cellpaddingTablaInfContable; ?>>
					<?	
					if($Comprobante){$CondAdc=" and Comprobante='$Comprobante'";}
					
					$consComp="Select Comprobante from Contabilidad.Comprobantes where Compania='$Compania[0]' $CondAdc";
					$resComp=ExQuery($consComp);
					while($filaComp=ExFetch($resComp))
					{
						$cons="Select Fecha,Numero,Cuenta,Debe,Haber,DocSoporte,UsuarioCre,Movimiento.Identificacion,PrimApe,SegApe,PrimNom,SegNom,Detalle 
						from Contabilidad.Movimiento,Central.Terceros 
						where Movimiento.Identificacion=Terceros.Identificacion and Terceros.Compania='$Compania[0]' and Comprobante='$filaComp[0]'
						and Estado='AC' and Movimiento.Compania='$Compania[0]' and Fecha>='$Anio-$MesIni-$DiaIni' and Fecha<='$Anio-$MesFin-$DiaFin' Order By Numero,Debe";
						$res=ExQuery($cons);
						if(ExNumRows($res)>0)
						{
							echo "<tr>";
								echo "<td class='encabezado2HorizontalInfCont' colspan=9 >".strtoupper($filaComp[0])."</td>";
							echo "</tr>";
							echo "<tr>";
								echo "<td class='encabezado2HorizontalInfCont'>FECHA</td>";
								echo "<td class='encabezado2HorizontalInfCont'>N&Uacute;MERO</td>";
								echo "<td class='encabezado2HorizontalInfCont'>CUENTA</td>";
								echo "<td class='encabezado2HorizontalInfCont'>DEBITOS</td>";
								echo "<td class='encabezado2HorizontalInfCont'>CREDITOS</td>";
								echo "<td class='encabezado2HorizontalInfCont'>DOC SOPORTE</td>";
								echo "<td class='encabezado2HorizontalInfCont'>TERCERO</td>";
								echo "<td class='encabezado2HorizontalInfCont'>DETALLE</td>";
								echo "<td class='encabezado2HorizontalInfCont'>USUARIO CREADOR</td>";
							echo "</tr>";
							while($fila=ExFetch($res))
							{
								if(!$NumSaltar){$NumSaltar=$fila[1];$Aux=$NumSaltar;}
								$NumSaltar=$fila[1];
								if($NumSaltar!=$Aux)
								{
									echo "<tr>";
										echo "<td class='filaTotalesInfContable' style='text-align-right; padding-right:10px;' colspan=3>SUMAS</td>";
										echo "<td class='filaTotalesInfContable' style='text-align-right; padding-right:10px;'>".number_format($SumDB,2)."</td>";
										echo "<td class='filaTotalesInfContable' style='text-align-right; padding-right:10px;'>".number_format($SumCR,2)."</td>";
										echo "<td class='filaTotalesInfContable' colspan='4'> &nbsp;</td>";
									echo "</tr>";
									$Aux=$NumSaltar;
									$SumCR=0;$SumDB=0;
								}
								$SumDB=$SumDB+$fila[3];
								$SumCR=$SumCR+$fila[4];
								$TotDB=$TotDB+$fila[3];$TotCR=$TotCR+$fila[4];
								echo "<tr>";
									echo "<td>$fila[0]</td>";
									echo "<td>$fila[1]</td>";
									echo "<td>$fila[2]</td>";
									echo "<td style='text-align-right; padding-right:10px;'>".number_format($fila[3],2)."</td>";
									echo "<td style='text-align-right; padding-right:10px;'>".number_format($fila[4],2)."</td>";
									echo "<td>$fila[5]</td>";
									echo "<td>$fila[7] - $fila[8] $fila[9] $fila[10] $fila[11]</td>";
									echo "<td>".substr($fila[12],0,20)."</td><td>$fila[6]</td>";
								echo "</tr>";
							}
							echo "<tr >";
								echo "<td class='filaTotalesInfContable' style='text-align-right; padding-right:10px;'  colspan=3>SUMAS</td>";
								echo "<td class='filaTotalesInfContable' style='text-align-right; padding-right:10px;'>".number_format($SumDB,2)."</td>";
								echo "<td class='filaTotalesInfContable' style='text-align-right; padding-right:10px;'>".number_format($SumCR,2)."</td>";
								echo "<td class='filaTotalesInfContable' colspan='4'> &nbsp;</td></tr>";

						}
					}
					echo "<tr>";
						echo "<td  class='filaTotalesInfContable' style='text-align-right; padding-right:10px;' colspan=3>TOTAL GENERAL</td>";
						echo "<td class='filaTotalesInfContable' style='text-align-right; padding-right:10px;'>".number_format($TotDB,2)."</td>";
						echo "<td class='filaTotalesInfContable' style='text-align-right; padding-right:10px;'>".number_format($TotCR,2)."</td>";
						echo "<td class='filaTotalesInfContable' style='text-align-right; padding-right:10px;' colspan='4'></td>";
					echo "</tr>";
					
					?>
				</table>
			</div>	
		</body>
	</html>	
				
