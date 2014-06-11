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
			$NumRec=0;$NumPag=1;
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
					global $Compania;global $PerFin;global $Estilo;global $IncluyeCC;global $ND;global $NumPag;global $TotPaginas;global $Corte;
					$caracteristicas= "CORTE A : ".$Corte;
					$fechaimpresion= "FECHA DE IMPRESI&Oacute;N : ".$ND[year]."-".$ND[mon]."-".$ND[mday];
					encabezadoInformeContable(strtoupper($Compania[0]), $Compania[1], "ESTADO DE CARTERA", $caracteristicas,$fechaimpresion);
					?>
					<table width="70%" class="tablaInformeContable" style="text-align:center; margin-top:25px;"  <?php echo $borderTablaInfContable; echo $bordercolorTablaInfContable; echo $cellspacingTablaInfContable; echo $cellpaddingTablaInfContable; ?>  >
					<?
						$cons1="Select Movimiento.Identificacion,PrimApe,SegApe,PrimNom,SegNom,Cuenta from Contabilidad.Movimiento,Central.Terceros 
						where Movimiento.Identificacion=Terceros.Identificacion 
						and Terceros.Compania='$Compania[0]'
						and Movimiento.Compania='$Compania[0]'
						and Movimiento.Cuenta>='$CuentaIni' and Movimiento.Cuenta<='$CuentaFin'
						and Fecha<='$Corte'
						$condAdc Group By Movimiento.Identificacion,PrimApe,SegApe,PrimNom,SegNom,Cuenta";
						$res1=ExQuery($cons1);
						while($fila1=ExFetch($res1)){
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
							$MatCartera[$fila2[3]][$fila2[4]][$fila2[7]][$fila2[6]]=$MatCartera[$fila2[3]][$fila2[4]][$fila2[7]][$fila2[6]]+$fila2[0];
							$MatDocSoporte[$fila2[3]][$fila2[4]][$fila2[1]]=array($fila2[6],$fila2[7]);
						}

						$cons3="Select sum(Haber) as Suma,DocSoporte,Fecha,Cuenta,Identificacion,'$Corte'-Fecha,date_part('month',Fecha),date_part('year',Fecha) from Contabilidad.Movimiento where 
						Estado='AC' and Movimiento.Compania='$Compania[0]' $condAdc 
						and Movimiento.Cuenta>='$CuentaIni' and Movimiento.Cuenta<='$CuentaFin'
						and Fecha<='$Corte'
						Group By DocSoporte,Fecha,Cuenta,Identificacion having sum(Haber)>0";

						$res3=ExQuery($cons3);
						while($fila3=ExFetch($res3)){
							$MesPag=$MatDocSoporte[$fila3[3]][$fila3[4]][$fila3[1]][0];
							$AnioPag=$MatDocSoporte[$fila3[3]][$fila3[4]][$fila3[1]][1];
							if(!$MesPag || !$AnioPag){$MesPag=$fila3[6];$AnioPag=$fila3[7];}
							$MatPagos[$fila3[3]][$fila3[4]][$AnioPag][$MesPag]=array($MatPagos[$fila3[3]][$fila3[4]][$AnioPag][$MesPag][0]+$fila3[0],"*",$AnioPag,$MesPag);
						}

						$cons="Select Movimiento.Cuenta,Nombre from Contabilidad.Movimiento,Contabilidad.PlanCuentas 
						where Movimiento.Cuenta=PlanCuentas.Cuenta 
						and Movimiento.Cuenta>='$CuentaIni' and Movimiento.Cuenta<='$CuentaFin' $condAdc and 
						Movimiento.Compania='$Compania[0]' 
						and PlanCuentas.Compania='$Compania[0]'
						and PlanCuentas.Anio=$Anio
						Group By Movimiento.Cuenta,Nombre";
						$res=ExQuery($cons);
						while($fila=ExFetch($res))	{
							echo "<tr>";
								echo "<td class='encabezado3HorizontalInfCont' colspan=5>$fila[0] $fila[1]</td>";
							echo "</tr>";
							foreach($MatTerceros[$fila[0]] as $Ident){
								?>
								<table width="70%"  class="tablaInformeContable"  <?php echo $borderTablaInfContable; echo $bordercolorTablaInfContable; echo $cellspacingTablaInfContable; echo $cellpaddingTablaInfContable; ?>>
								<?			
								echo "<tr><td class='encabezado2HorizontalInfContInv' colspan=5><strong>$Ident[0] $Ident[1] $Ident[2] $Ident[3] $Ident[4]</td></tr>";
								echo "<tr>";
									echo "<td class='encabezado1HorizontalInfCont'>PERIODO</td>";
									echo "<td  class='encabezado1HorizontalInfCont'>DEBITOS</td>";
									echo "<td class='encabezado1HorizontalInfCont'>CREDITOS</td>";
									echo "<td class='encabezado1HorizontalInfCont'>SALDO</td>";
								echo "</tr>";
								
								if(count($AniosCart[$fila[0]][$Ident[0]])>0){
									foreach($AniosCart[$fila[0]][$Ident[0]] as $AniosC)	{
										foreach($MesCart[$fila[0]][$Ident[0]][$AniosC] as $MesC){
											$Saldo=$MatCartera[$fila[0]][$Ident[0]][$AniosC][$MesC]-$MatPagos[$fila[0]][$Ident[0]][$AniosC][$MesC][0];
											if($Saldo!=0){
												echo "<tr><td>$NombreMes[$MesC] - $AniosC</td>";
												echo "<td>".number_format($MatCartera[$fila[0]][$Ident[0]][$AniosC][$MesC],2)."</td>";
												echo "<td>".number_format($MatPagos[$fila[0]][$Ident[0]][$AniosC][$MesC][0],2)."</td>";
												$MatPagos[$fila[0]][$Ident[0]][$AniosC][$MesC][3]="Ok";
												echo "<td>".number_format($Saldo,2)."</td>";
												echo "</tr>";
												$TotEnt=$TotEnt+$Saldo;$TotGral=$TotGral+$Saldo;
											}
										}
									}
								}
								echo "<tr>";
									echo "<td colspan='3' class='filaTotalesInfContable' style='text-align:right;padding-right:10px;'>TOTAL ENTIDAD</td>";
									echo "<td class='filaTotalesInfContable' style='text-align:center;padding-right:10px;'>".number_format($TotEnt,2)."</td>";
								echo "</tr>";
								$TotEnt=0;
							}
						}
						echo "<tr>";
							echo "<td colspan='3' class='filaTotalesInfContable' style='text-align:right;padding-right:10px;'>TOTAL CARTERA</td>";
							echo "<td class='filaTotalesInfContable' style='text-align:center;padding-right:10px;'>".number_format($TotGral,2)."</td>";
						echo "</tr>";
						echo "</table>";
					?>
				</div>	
			</body>
	</html>		