		<?
			if($DatNameSID){session_name("$DatNameSID");}
			session_start();
			include("Funciones.php");
			include_once("General/Configuracion/Configuracion.php");

			$ND=getdate();
			if(!$AnioSel){$AnioSel=$ND[year];}
			if($Iniciar)
			{

				$cons="Select Formato from Contabilidad.Comprobantes where Comprobante='$ComprobanteDestino' and Compania='$Compania[0]'";
				$res=ExQuery($cons);
				$fila=ExFetch($res);
				$Archivo=$fila[0];

				$cons2="Select Ingresos,Gastos,Utilidad,Perdida,Costos from Contabilidad.CuentasCierre where Anio='$AnioSel' and Compania='$Compania[0]'";
				$res2=ExQuery($cons2);
				$fila2=ExFetch($res2);
				$CtaIngresos=$fila2[0];$CtaGastos=$fila2[1];$CtaUtilidad=$fila2[2];$CtaPerdida=$fila2[3];$CtaCostos=$fila2[4];
				$Numero=ConsecutivoComp($ComprobanteDestino,$AnioSel,"Contabilidad");

				$consT="Select Identificacion from Central.Terceros where PrimApe ilike 'Varios' and Terceros.Compania='$Compania[0]'";
				$resT=ExQuery($consT);echo ExError($resT);
				$filaT=ExFetch($resT);
				$Tercero=$filaT[0];

				$PerIni="$AnioSel-01-01";$PerFin="$AnioSel-12-31";
				$cons="Select NoCaracteres from Contabilidad.EstructuraPuc where Compania='$Compania[0]' and Anio=$AnioSel  Order By Nivel";
				$res=ExQuery($cons,$conex);echo ExError($res);
				while($fila=ExFetchArray($res))
				{
					$Nivel++;$TotNivel++;
					if(!$fila[0]){$fila[0]="-100";}
					$TotCaracteres=$TotCaracteres+$fila[0];
					$Digitos[$Nivel]=$TotCaracteres;
				}

				$cons3="Select sum(Debe),sum(Haber),Cuenta,Identificacion from Contabilidad.Movimiento 
				where (Cuenta ilike '4%' Or Cuenta ilike '5%' Or Cuenta ilike '6%') and Fecha>='$AnioSel-01-01' and Fecha<='$PerFin' and Compania='$Compania[0]' and Estado='AC' and Cuenta!='0' and Cuenta!='1'
				Group By Cuenta,Identificacion Order By Cuenta";

				$res3=ExQuery($cons3);
				while($fila3=ExFetch($res3))
				{
					for($Nivel=1;$Nivel<=$TotNivel;$Nivel++)
					{
						$ParteCuenta=substr($fila3[2],0,$Digitos[$Nivel]);
						if($ParteAnt!=$ParteCuenta)
						{
							$MPCuenta[$ParteCuenta]['Debitos'][$fila3[3]]=$MPCuenta[$ParteCuenta]['Debitos'][$fila3[3]]+$fila3[0];
							$MPCuenta[$ParteCuenta]['Creditos'][$fila3[3]]=$MPCuenta[$ParteCuenta]['Creditos'][$fila3[3]]+$fila3[1];
							$TercxCuenta[$ParteCuenta][$fila3[3]]=$fila3[3];
						}
						$ParteAnt=$ParteCuenta;
					}
				}

				$consCta="Select Cuenta,Nombre,Tipo,Naturaleza,length(Cuenta) as Digitos from Contabilidad.PlanCuentas 
				where (Cuenta ilike '4%' Or Cuenta ilike '5%' Or Cuenta ilike '6%') and Cuenta!='$CtaIngresos' and Cuenta!='$CtaGastos' and Cuenta!='$CtaUtilidad' and Cuenta!='$CtaCostos'
				and Tipo='Detalle' and Compania='$Compania[0]' and Anio=$AnioSel
				Order By Cuenta";

				$resCta=ExQuery($consCta);echo ExError($resCta);
				while($filaCta=ExFetchArray($resCta))
				{
					if(count($TercxCuenta[$filaCta[0]])>0){
					foreach($TercxCuenta[$filaCta[0]] as $TerceroBusq)
					{
						$AutoId++;
						$Debitos=$MPCuenta[$filaCta[0]]['Debitos'][$TerceroBusq];
						$Creditos=$MPCuenta[$filaCta[0]]['Creditos'][$TerceroBusq];
			
						if(!$Debitos){$Debitos=0;}if(!$Creditos){$Creditos=0;}
						if($filaCta[3]=="Debito")
						{
							$SaldoF=$SaldoI-$Creditos+$Debitos;
							if($SaldoF>0)
							{
								$Credito=abs($SaldoF);$Debito=0;
							}
							else
							{
								$Debito=abs($SaldoF);$Credito=0;
							}
						}
						elseif($filaCta[3]=="Credito")
						{
							$SaldoF=$SaldoI+$Creditos-$Debitos;
							if($SaldoF>0)
							{
								$Debito=abs($SaldoF);$Credito=0;
							}
							else
							{
								$Credito=abs($SaldoF);$Debito=0;
							}
						}

						if($Debito || $Credito)
						{
							$cons9="Insert into Contabilidad.Movimiento (AutoId,Fecha,Comprobante,Numero,Identificacion,Detalle,Cuenta,Debe,Haber,Compania,UsuarioCre,FechaCre,Estado,Anio)
							values('$AutoId','$AnioSel-12-31','$ComprobanteDestino',$Numero,'$TerceroBusq','Cierre Periodo Fiscal $AnioSel','$filaCta[0]','$Debito','$Credito','$Compania[0]','$usuario[0]',
							'$ND[year]-$ND[mon]-$ND[mday]','AC',$AnioSel)";
							$res9=ExQuery($cons9);
						}
					}
				}}
				
				for($i=4;$i<=6;$i++)
				{	
					$cons10="Select sum(Debe),sum(Haber) from Contabilidad.Movimiento where Comprobante='$ComprobanteDestino' and date_part('year',Fecha)=$AnioSel and Compania='$Compania[0]' 
					and Cuenta ilike '$i%' and Cuenta!='$CtaIngresos' and Cuenta!='$CtaGastos' and Cuenta!='$CtaUtilidad' and Cuenta!='$CtaCostos' and Estado='AC'";

					$res10=ExQuery($cons10);
					$fila10=ExFetch($res10);
					if($i==4){$Cuenta=$CtaIngresos;}if($i==5){$Cuenta=$CtaGastos;}if($i==6){$Cuenta=$CtaCostos;}
					if($fila10[0]>0)
					{
						$AutoId++;
						$cons11="Insert into Contabilidad.Movimiento (AutoId,Fecha,Comprobante,Numero,Identificacion,Detalle,Cuenta,Debe,Haber,Compania,UsuarioCre,FechaCre,Estado,Anio)
						values('$AutoId','$AnioSel-12-31','$ComprobanteDestino',$Numero,'$Tercero','Cierre Periodo Fiscal $AnioSel','$Cuenta','0','$fila10[0]','$Compania[0]','$usuario[0]','$ND[year]-$ND[mon]-$ND[mday]','AC',$AnioSel)";
						$res11=ExQuery($cons11);echo ExError($res11);
					}
					if($fila10[1]>0)
					{
						$AutoId++;
						$cons11="Insert into Contabilidad.Movimiento (AutoId,Fecha,Comprobante,Numero,Identificacion,Detalle,Cuenta,Debe,Haber,Compania,UsuarioCre,FechaCre,Estado,Anio)
						values('$AutoId','$AnioSel-12-31','$ComprobanteDestino',$Numero,'$Tercero','Cierre Periodo Fiscal $AnioSel','$Cuenta','$fila10[1]','0','$Compania[0]','$usuario[0]','$ND[year]-$ND[mon]-$ND[mday]','AC',$AnioSel)";
					$res11=ExQuery($cons11);echo ExError($res11);
					}
				}

				$cons10="Select sum(Debe),sum(Haber) from Contabilidad.Movimiento where Comprobante='$ComprobanteDestino' and date_part('year',Fecha)=$AnioSel and Compania='$Compania[0]' 
				and Cuenta ilike '59%' and Estado='AC'";
				$res10=ExQuery($cons10);
				$fila10=ExFetch($res10);
				$Totales=$fila10[0]-$fila10[1];	
				if($Totales>0)
				{
					$Total=abs($Totales);
					$AutoId++;
					$cons11="Insert into Contabilidad.Movimiento (AutoId,Fecha,Comprobante,Numero,Identificacion,Detalle,Cuenta,Debe,Haber,Compania,UsuarioCre,FechaCre,Estado,Anio)
					values('$AutoId','$AnioSel-12-31','$ComprobanteDestino',$Numero,'$Tercero','Cierre Periodo Fiscal $AnioSel','$CtaIngresos','0','$Total','$Compania[0]','$usuario[0]','$ND[year]-$ND[mon]-$ND[mday]','AC',$AnioSel)";
					$res11=ExQuery($cons11);
					$AutoId++;
					$cons11="Insert into Contabilidad.Movimiento (AutoId,Fecha,Comprobante,Numero,Identificacion,Detalle,Cuenta,Debe,Haber,Compania,UsuarioCre,FechaCre,Estado,Anio)
					values('$AutoId','$AnioSel-12-31','$ComprobanteDestino',$Numero,'$Tercero','Cierre Periodo Fiscal $AnioSel','$CtaPerdida','$Total','0','$Compania[0]','$usuario[0]','$ND[year]-$ND[mon]-$ND[mday]','AC',$AnioSel)";
					$res11=ExQuery($cons11);
				}
				elseif($Totales<0)
				{
					$Total=abs($Totales);
					$AutoId++;
					$cons11="Insert into Contabilidad.Movimiento (AutoId,Fecha,Comprobante,Numero,Identificacion,Detalle,Cuenta,Debe,Haber,Compania,UsuarioCre,FechaCre,Estado,Anio)
					values('$AutoId','$AnioSel-12-31','$ComprobanteDestino',$Numero,'$Tercero','Cierre Periodo Fiscal $AnioSel','$CtaIngresos','$Total','0','$Compania[0]','$usuario[0]','$ND[year]-$ND[mon]-$ND[mday]','AC',$AnioSel)";
					$res11=ExQuery($cons11);
					$AutoId++;
					$cons11="Insert into Contabilidad.Movimiento (AutoId,Fecha,Comprobante,Numero,Identificacion,Detalle,Cuenta,Debe,Haber,Compania,UsuarioCre,FechaCre,Estado,Anio)
					values('$AutoId','$AnioSel-12-31','$ComprobanteDestino',$Numero,'$Tercero','Cierre Periodo Fiscal $AnioSel','$CtaUtilidad','0','$Total','$Compania[0]','$usuario[0]','$ND[year]-$ND[mon]-$ND[mday]','AC',$AnioSel)";
					$res11=ExQuery($cons11);
				}
				
				$cons="Delete from Central.CierrexPeriodos where Compania='$Compania[0]' and Anio=$AnioSel";
				$res=ExQuery($cons);
				for($i=1;$i<=12;$i++)
				{
					$cons="Insert into Central.CierrexPeriodos(Compania,Anio,Mes,CierreFiscal,Modulo)
					values('$Compania[0]',$AnioSel,$i,1,'Contabilidad')";
					$res=ExQuery($cons);echo ExError($res);
				}
				
				?>
				<script language="JavaScript">
					open("/Informes/Contabilidad/<?echo $Archivo?>?DatNameSID=<? echo $DatNameSID?>&Numero=<?echo $Numero?>&Comprobante=<?echo $ComprobanteDestino?>","","width=650,height=500,scrollbars=yes");
					location.href='Movimiento.php?DatNameSID=<? echo $DatNameSID?>&Comprobante=<?echo $ComprobanteDestino?>&Mes=12&Tipo=Contables&Numero=<?echo $Numero?>&Anio=<?$AnioSel?>'
				</script>
				<?
			}
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
		<body <?php echo $backgroundBodyMentor; ?>>
			
			<?php
				$rutaarchivo[0] = "CONTABILIDAD";
				$rutaarchivo[1] = "PROCESOS CONTABLES";
				$rutaarchivo[2] = "CIERRE FISCAL";
				mostrarRutaNavegacionEstatica($rutaarchivo);
				
				?>
			<div <?php echo $alignDiv1Mentor; ?> class="div1">
				<form name="FORMA">
				<input type="hidden" name="DatNameSID" value="<? echo $DatNameSID?>">

				<table class="tabla1" width="250px"   style="text-align:center;"  <?php echo $borderTabla1Mentor; echo $bordercolorTabla1Mentor; echo $cellspacingTabla1Mentor; echo $cellpaddingTabla1Mentor; ?>>
					<tr>
						<td  class='encabezado1Horizontal' colspan="2"> CIERRE FISCAL</td>
					</tr>
					<tr>
						<td class='encabezado1HorizontalInvertido' >A&Ntilde;O</td>
						<td>
							<select name="AnioSel" onChange="document.FORMA.submit();">
							<?	
								$cons = "Select Anio from Central.Anios where Compania = '$Compania[0]' Order By Anio DESC LIMIT 20";
								$res = ExQuery($cons);
								while($fila = ExFetch($res))
								{
									if($fila[0]==$AnioSel){echo "<option selected value=$fila[0]>$fila[0]</option>";}
									else{echo "<option value=$fila[0]>$fila[0]</option>";}	
								}

							?>
							</select>
						</td>
					</tr>
				</table>
				<?
					$cons="Select sum(Debe),sum(Haber) from Contabilidad.Movimiento where Compania='$Compania[0]' and Estado='AC' and Fecha>='$AnioSel-01-01' and Fecha<='$AnioSel-12-31'";
					$res=ExQuery($cons);
					$fila=ExFetch($res);
					$Tot=round($fila[0])-round($fila[1]);
					if($Tot!=0){echo "<div class='mensaje1' style='margin-top:15px;margin-bottom:15px;'>Documentos descuadrados, ejecute revisi&oacute;n de integridad para detectarlos. Proceso Abortado!!!</div>";exit;}

					$cons="Select Comprobante from Contabilidad.Comprobantes where Cierre='1' and Compania='$Compania[0]'";
					$res=ExQuery($cons);
					if(ExNumRows($res)>1){echo "<div class='mensaje1' style='margin-top:15px;margin-bottom:15px;'>Existe mas de un comprobante marcado como Cierre, marque un solo comprobante para continuar!!!</div>";exit;}
					if(ExNumRows($res)==0){echo "<div class='mensaje1' style='margin-top:15px;margin-bottom:15px;'>No hay comprobante marcado para cierre, proceda a marcarlo para poder realizar el cierre!!!</div>";exit;}
					$fila=ExFetch($res);
					$ComprobanteDestino=$fila[0];
					$cons2="Select * from Contabilidad.Movimiento where Comprobante='$fila[0]' and date_part('year',Fecha)='$AnioSel' and Estado='AC' and Compania='$Compania[0]'";
					$res2=ExQuery($cons2);echo ExError($res2);
					if(ExNumRows($res2)>0){echo "<div class='mensaje1' style='margin-top:15px;margin-bottom:15px;'>Existe movimiento sobre el comprobante de cierre para el periodo seleccionado, no es posible ejecutar el proceso!!!</div>";exit;}
				?>
				<input type="Hidden" name="ComprobanteDestino" value="<?echo $ComprobanteDestino?>">
				
					<table class="tabla2" style="margin-top:25px;margin-bottom:25px;"   <?php echo $borderTabla2Mentor; echo $bordercolorTabla2Mentor; echo $cellspacingTabla2Mentor; echo $cellpaddingTabla2Mentor; ?>>
						<tr>
							<td class="encabezado2Horizontal">COMPRIBANTE</td>
							<td class="encabezado2Horizontal">D&Eacute;BITOS</td>
							<td class="encabezado2Horizontal">CR&Eacute;DITOS</td>
							<td class="encabezado2Horizontal">DIFERENCIA</td>
						</tr>
						<?
							$cons="Select sum(Debe),sum(Haber),Comprobante from Contabilidad.Movimiento where Compania='$Compania[0]' and Estado='AC' and Fecha>='$AnioSel-01-01' and Fecha<='$AnioSel-12-31' and Comprobante!='' Group By Comprobante";
							$res=ExQuery($cons);
							while($fila=ExFetch($res))
							{
								$Dif=$fila[1]-$fila[0];
								echo "<tr><td>$fila[2]</td><td align='right'>".number_format($fila[0],2)."</td><td align='right'>".number_format($fila[1],2)."</td><td align='right'>".number_format($Dif,2)."</td></tr>";
							}
						?>
					</table>
				<br><input type="Submit" class="boton2Envio" name="Iniciar" value="Iniciar">
				</form>
			</div>	
		</body>