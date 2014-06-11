		<?
			if($DatNameSID){session_name("$DatNameSID");}
			session_start();
			include("Funciones.php");
			include("Consumo/ObtenerSaldos.php");
			include_once("General/Configuracion/Configuracion.php");
			$ND=getdate();
			$FechaIni="$Anio-01-01";
			$diacorte=$_GET['diacorte'];
			//$Corte="$Anio-$Mes-$ND[mday]";
			$Corte="$Anio-$Mes-$diacorte";
			//$Mes="06";
			$Fecha=$Corte;
			$VrSaldoIni=SaldosIniciales($ND[year],$AlmacenPpal,"$FechaIni");
			$VrEntradas=Entradas($Anio,$AlmacenPpal,$FechaIni,$Corte);
			$VrSalidas=Salidas($Anio,$AlmacenPpal,$FechaIni,$Corte);
			$VrDevoluciones=Devoluciones($Anio,$AlmacenPpal,$FechaIni,$Corte);
			
			if($Guardar || $Cerrar){

				if($Guardar)
				{
				?>
					<script language="javascript">
						alert("Se ha actualizado el ajuste de Inventario!!\n <? echo "$NomInventario  - $usuario[0]"?>");
					</script>
				<?
					while (list($val,$cad) = each ($Dif)) 
					{
						if(!$Cont1[$val]){$Cont1[$val]=0;}if(!$Existe[$val]){$Existe[$val]=0;}if(!$Cont2[$val]){$Cont2[$val]=0;}if(!$Cont3[$val]){$Cont3[$val]=0;}
						if(!$PCosto[$val]){$PCosto[$val]=0;}if(!$Dif[$val]){$Dif[$val]=0;}if(!$Cost[$val]){$Cost[$val]=0;}

						$cons="Select * from Consumo.Inventarios where NomInventario='$NomInventario' and Compania='$Compania[0]' and  AutoId=$val";
						$res=ExQuery($cons);
						if(ExNumRows($res)>0)
						{
							$cons="Update Consumo.Inventarios set Existencias=$Existe[$val],Cont1=$Cont1[$val],Cont2=$Cont2[$val],ContDef=$Cont3[$val],VrCosto=$PCosto[$val],
							Diferencia=$Dif[$val],TotCostoDif=$Cost[$val] where NomInventario='$NomInventario' and Compania='$Compania[0]' and  AutoId=$val";
						}
						else
						{
							$cons="Insert into Consumo.Inventarios(NomInventario,Compania,AlmacenPpal,AutoId,Existencias,Cont1,Cont2,ContDef,VrCosto,Diferencia,TotCostoDif,Usuario,Anio,Mes,Grupo,diacorte)
							values('$NomInventario','$Compania[0]','$AlmacenPpal',$val,$Existe[$val],$Cont1[$val],$Cont2[$val],$Cont3[$val],$PCosto[$val],$Dif[$val],$Cost[$val],'$usuario[0]',$Anio,$Mes,'$Grupo','$diacorte')";
						}
						$res=ExQuery($cons);echo ExError();
				
					}
				}
				if($Cerrar)
				{

					$cons="Update Consumo.Inventarios set FechaCierre='$ND[year]-$ND[mon]-$ND[mday] $ND[hours]:$ND[minutes]:$ND[seconds]'
					where NomInventario='$NomInventario' and Compania='$Compania[0]'";
					$res=ExQuery($cons);
			
					$cons="Delete from Consumo.Inventarios where NomInventario='$NomInventario' and Compania='$Compania[0]' and Diferencia=0";
					$res=ExQuery($cons);

					if(!$Numero){$Numero="$ND[year]$ND[mon]$ND[mday]$ND[hours]$ND[minutes]";}

					$cons9="Select AutoId,Diferencia,VrCosto,TotCostoDif,Grupo from  Consumo.Inventarios where NomInventario='$NomInventario' and Compania='$Compania[0]'";
					$res9=ExQuery($cons9);
					while ($fila9=ExFetch($res9)) 
					{
						if($fila9[1]>0){$TipoAjuste="Ingreso Ajuste";}
						if($fila9[1]<0){$TipoAjuste="Salida Ajuste";}

						$DetalleAjuste="Ajuste de Inventario";
						$cons3="Insert into Consumo.Movimiento(Compania,AlmacenPpal,Fecha,Comprobante,TipoComprobante,Numero,Cedula,Detalle,AutoId,UsuarioCre,FechaCre,Estado,Cantidad,VrCosto,TotCosto,CentroCosto,Anio,VrIVA,VrVenta,TotVenta,PorcIVA,PorcReteFte,VrReteFte,PorcDescto,VrDescto,Grupo) 
						values('$Compania[0]','$AlmacenPpal','$Fecha','Ajustes','$TipoAjuste','$NomInventario','99999999999-0','$DetalleAjuste','$fila9[0]','$usuario[0]','$NomInventario','AC','".abs($fila9[1])."','".$fila9[2]."','".abs($fila9[3])."','000',$Anio,0,0,0,0,0,0,0,0,'$fila9[4]')";

						$res3=ExQuery($cons3);echo ExError();
					}
					
					
		////////////////////N O T A      C O N T A B L E         D E           S A L I D A ////////////////////////

		/*		if(!$TMPCOD){$TMPCOD=strtotime("$ND[year]-$ND[mon]-$ND[mday] $ND[hours]:$ND[minutes]:$ND[seconds]").rand(1,9999);}

					$cons3="Select Comprobante,Cuenta  from Consumo.AjustesxCtaContable where Compania='$Compania[0]' and AlmacenPpal='$AlmacenPpal' and Tipo='Salida Ajuste'";
					$res3=ExQuery($cons3);
					$fila3=ExFetch($res3);
					$Comprobante=$fila3[0];
					$CtaSalida=$fila3[1];
				$NumeroCont=ConsecutivoComp($Comprobante,$Anio,"Contabilidad");
					$cons="Select TipoComprobant,CompPresupuesto from Contabilidad.Comprobantes where Comprobante='$Comprobante' and Compania='$Compania[0]'";
					$res=mysql_query($cons);
					$fila=ExFetch($res);
					$TipoCompCont=$fila[0];
					
					$cons2="SELECT sum( TotCosto ) , CtaContable FROM Consumo.Movimiento, Consumo.CodProductos, Consumo.Grupos 
					WHERE CodProductos.Grupo = Grupos.Grupo AND Movimiento.AutoId = CodProductos.AutoId  
					AND Movimiento.Compania = '$Compania[0]' AND Grupos.Compania = '$Compania[0]' AND Movimiento.AlmacenPpal = '$AlmacenPpal'
					AND Comprobante = 'Ajustes' and TipoComprobante='Salida Ajuste' AND Numero = '$NomInventario' AND CodProductos.Anio = $Anio GROUP BY CtaContable";

					$cons2="SELECT sum( TotCosto ) , CtaContable 
					FROM Consumo.Movimiento, Consumo.CodProductos, Consumo.Grupos WHERE CodProductos.Grupo = Grupos.Grupo 
					AND Movimiento.AutoId = CodProductos.AutoId  AND Movimiento.Compania = '$Compania[0]' 
					AND Grupos.Compania = '$Compania[0]' AND Movimiento.AlmacenPpal = '$AlmacenPpal'
					and CodProductos.AlmacenPpal='$AlmacenPpal' and Grupos.AlmacenPpal='$AlmacenPpal'
					and Movimiento.Anio=$Anio and CodProductos.Anio=$Anio and Grupos.Anio=$Anio
					and CodProductos.Compania='$Compania[0]'
					AND Comprobante = 'Ajustes' and TipoComprobante='Salida Ajuste' AND Numero = '$NomInventario' GROUP BY CtaContable";

					$res2=ExQuery($cons2);
					$fila2=ExFetch($res2);

					$AutoId++;
					if($fila2[0]>0){
					$cons9="Insert into Contabilidad.TmpMovimiento(NumReg,AutoId,Comprobante,Cuenta,Identificacion,Debe,Haber,CC,DocSoporte,Compania,Detalle)
					values('$TMPCOD',$AutoId,'$Comprobante','$fila2[1]','99999999999-0',0,$fila2[0],'000','$NumeroCont','$Compania[0]','$DetalleAjuste')";
					$res=ExQuery($cons9);

					$AutoId++;
					$cons9="Insert into Contabilidad.TmpMovimiento(NumReg,AutoId,Comprobante,Cuenta,Identificacion,Debe,Haber,CC,DocSoporte,Compania,Detalle)
					values('$TMPCOD',$AutoId,'$Comprobante','$CtaSalida','99999999999-0',$fila2[0],0,'000','$NumeroCont','$Compania[0]','$DetalleAjuste')";
					$res=ExQuery($cons9);
					}

		////////////////////N O T A      C O N T A B L E         D E           E N T R A D A ////////////////////////

					$cons3="Select Comprobante,Cuenta  from  Consumo.AjustesxCtaContable where Compania='$Compania[0]' and AlmacenPpal='$AlmacenPpal' and Tipo='Ingreso Ajuste'";
					$res3=ExQuery($cons3);
					$fila3=ExFetch($res3);
					$Comprobante=$fila3[0];
					$CtaIngreso=$fila3[1];
					
		/*			$cons2="SELECT sum( TotCosto ) , CtaContable FROM Consumo.Movimiento, Consumo.CodProductos, Consumo.Grupos 
					WHERE CodProductos.Grupo = Grupos.Grupo AND Movimiento.AutoId = CodProductos.AutoId  
					AND Movimiento.Compania = '$Compania[0]' AND Grupos.Compania = '$Compania[0]' AND Movimiento.AlmacenPpal = '$AlmacenPpal'
					AND Comprobante = 'Ajustes' and TipoComprobante='Ingreso Ajuste' AND Numero = '$NomInventario' AND CodProductos.Anio = $Anio GROUP BY CtaContable";

					$cons2="SELECT sum( TotCosto ) , CtaContable 
					FROM Consumo.Movimiento, Consumo.CodProductos, Consumo.Grupos WHERE CodProductos.Grupo = Grupos.Grupo 
					AND Movimiento.AutoId = CodProductos.AutoId  AND Movimiento.Compania = '$Compania[0]' 
					AND Grupos.Compania = '$Compania[0]' AND Movimiento.AlmacenPpal = '$AlmacenPpal'
					and CodProductos.AlmacenPpal='$AlmacenPpal' and Grupos.AlmacenPpal='$AlmacenPpal'
					and Movimiento.Anio=$Anio and CodProductos.Anio=$Anio and Grupos.Anio=$Anio
					and CodProductos.Compania='$Compania[0]'
					AND Comprobante = 'Ajustes' and TipoComprobante='Ingreso Ajuste' AND Numero = '$NomInventario' GROUP BY CtaContable";


					$res2=ExQuery($cons2);
					$fila2=ExFetch($res2);

					$AutoId++;
					if($fila2[0]>0){
					$cons9="Insert into Contabilidad.TmpMovimiento(NumReg,AutoId,Comprobante,Cuenta,Identificacion,Debe,Haber,CC,DocSoporte,Compania,Detalle)
					values('$TMPCOD',$AutoId,'$Comprobante','$fila2[1]','99999999999-0',$fila2[0],0,'000','$NumeroCont','$Compania[0]','$DetalleAjuste')";
					$res=ExQuery($cons9);echo ExError();

					$AutoId++;
					$cons9="Insert into Contabilidad.TmpMovimiento(NumReg,AutoId,Comprobante,Cuenta,Identificacion,Debe,Haber,CC,DocSoporte,Compania,Detalle)
					values('$TMPCOD',$AutoId,'$Comprobante','$CtaIngreso','99999999999-0',0,$fila2[0],'000','$NumeroCont','$Compania[0]','$DetalleAjuste')";
					$res=ExQuery($cons9);echo ExError();}
		*/
					?>
					<script language="javascript">
					alert("Cierre Realizado!!!");
					parent.parent.location.href="/Contabilidad/NuevoMovimiento.php?DatNameSID=<? echo $DatNameSID?>&DocGen=Consumo&DocConsumo=<? echo $Comprobante?>&NumDocConsumo=<? echo $Numero?>&AlmacenPpal=<? echo $AlmacenPpal?>&Comprobante=<? echo $Comprobante?>&Numero=<? echo $NumeroCont?>&Tipo=<? echo $TipoCompCont?>&Detalle=Ajuste de Inventario&Tercero=VARIOS&Identificacion=99999999999-0&Anio=<?echo $Anio?>&Mes=<?echo $Mes?>&Dia=<? echo $Dia?>&Edit=1&NUMREG=<? echo $TMPCOD?>&Archivo=/Consumo/ImpCausacion.php&phpMovimiento=_Consumo_Movimiento.php&ParamsAdc=Tipo_<? echo $Tipo?>*AlmacenPpal_<? echo $AlmacenPpal?>*Comprobante_<? echo $Comprobante?>*Anio_<? echo $Anio?>*Mes_<? echo $Mes?>";

					open('/Informes/Almacen/Formatos/Ajustes?DatNameSID=<? echo $DatNameSID?>&NomInventario=<? echo $NomInventario?>&AlmacenPpal=<? echo $AlmacenPpal?>&Anio=<? echo $Anio?>','','width=700,height=500,scrollbars=yes')
					</script>
					<?
				}
			}
		?>
		
		<html>
			<head>
				<script language='javascript' src="/Funciones.js"></script>
				<?php echo $codificacionMentor; ?>
				<?php echo $autorMentor; ?>
				<?php echo $titleMentor; ?>
				<?php echo $iconMentor; ?>
				<?php echo $shortcutIconMentor; ?>
				<link rel="stylesheet" type="text/css" href="../General/Estilos/estilos.css">
			</head>	
			

			<body <?php echo $backgroundBodyMentor; ?>>
				<div align="center">
					<form name="FORMA" method="post">
						<input type="Hidden" name="NomInventario" value="<? echo $NomInventario?>">
						<input type="hidden" name="DatNameSID" value="<? echo $DatNameSID?>" />
						<input type="hidden" name="Mes" value="<?echo $Mes?>" />
						<input type="hidden" name="Anio" value="<?echo $Anio?>" />
						
						<table class="tabla2" width="100%" style="text-align:center;"   <?php echo $borderTabla2Mentor; echo $bordercolorTabla2Mentor; echo $cellspacingTabla2Mentor; echo $cellpaddingTabla2Mentor; ?>>
							<tr>
								<td colspan="8" class='encabezado2Horizontal' ><? echo "$NomInventario - ".strtoupper($usuario[0]);?></td>
							</tr>
							<tr>
								<td class='encabezado2HorizontalInvertido'>COD.</td>
								<td class='encabezado2HorizontalInvertido'>PRODUCTO</td>
								<td class='encabezado2HorizontalInvertido'>EXIST.</td>
								<td class='encabezado2HorizontalInvertido'>CONT. 1</td>
								<td class='encabezado2HorizontalInvertido'>CONT. 2</td>
								<td class='encabezado2HorizontalInvertido'>CONT. DEF.</td>
								<td class='encabezado2HorizontalInvertido'>DIF.</td>
								<td class='encabezado2HorizontalInvertido'>COSTO</td>
							</tr>
							<?

								if($Grupo){$condGrupo=" and Grupo='$Grupo'";}
								if($Bodega){$condBodega=" and Bodega='$Bodega'";}
								if($Estante){$condEstante=" and Estante='$Estante'";}
								if($Nivel){$condNivel=" and Nivel='$Nivel'";}

								if($Grupo){
									$cons="Select Codigo1,NombreProd1,UnidadMedida,Presentacion,AutoId from Consumo.CodProductos where Compania='$Compania[0]' and AlmacenPpal='$AlmacenPpal' $condGrupo
									$condBodega $condEstante $condNivel and Anio=$Anio and Estado='AC'
									Order By NombreProd1,UnidadMedida,Presentacion";

									$res=ExQuery($cons);
									while($fila=ExFetch($res)){
								
										$Diferencia=0;$TotCosto=0;$VrCosto=0;
										//$CantExistencias=$VrSaldoIni[$fila[4]][0];
										//$SaldoFinal=$VrSaldoIni[$fila[4]][1];

										$CantExistencias=$VrSaldoIni[$fila[4]][0]+$VrEntradas[$fila[4]][0]-$VrSalidas[$fila[4]][0]+$VrDevoluciones[$fila[4]][0];
										$SaldoFinal=$VrSaldoIni[$fila[4]][1]+$VrEntradas[$fila[4]][1]-$VrSalidas[$fila[4]][1]+$VrDevoluciones[$fila[4]][1];

										if($CantExistencias>0){$VrCosto=$SaldoFinal/$CantExistencias;}else{$CantExistencias=0;}
										if($CantExistencias==0){
										$consAJCERO="select vrunidad from consumo.saldosinicialesxanio where autoid='$fila[4]' and anio='$Anio'";
										$resAJCERO=ExQuery($consAJCERO);
										$filaAJCERO=ExFetch($resAJCERO);
										$VrCosto=$filaAJCERO[0];
										}
										$PrecioCosto=$VrSaldoIni[$fila[4]][1];
										$cons2="Select Existencias,Cont1,Cont2,ContDef,VrCosto,Diferencia,TotCostoDif from Consumo.Inventarios where AutoId=$fila[4] and Compania='$Compania[0]' and AlmacenPpal='$AlmacenPpal' 
										and NomInventario='$NomInventario'";
										$res2=ExQuery($cons2);
										if(ExNumRows($res2)>0)
										{
											$fila2=ExFetch($res2);
											$VrCosto=$fila2[4];$CantExistencias=$fila2[0];$Conteo1=$fila2[1];$Conteo2=$fila2[2];$Conteo3=$fila2[3];$Diferencia=$fila2[5];$TotCosto=$fila2[6];
										}
										?>
										<input type="Hidden" name="PCosto[<? echo $fila[4]?>]" id="PCosto_<? echo $fila[4]?>" value="<? echo $VrCosto?>">
										<?		
										echo "<tr>";
											echo "<td>$fila[0]</td>";
											echo "<td style='text-align:justify;'>$fila[1] $fila[2] $fila[3]</td>";
											echo "<td><input type='text' readonly value='$CantExistencias' name='Existe[$fila[4]]' id='Existe_$fila[4]' style='width:50px;text-align:center;border:0px;' ></td>";
											echo "<td><input type='text' style='width:40px;text-align:center' name='Cont1[$fila[4]]' id='Cont1_$fila[4]' value='$Conteo1' onKeyUp='xNumero(this)' onKeyDown='xNumero(this)' onBlur='campoNumero(this)' ></td>";
											echo "<td><input type='text' style='width:40px;text-align:center' name='Cont2[$fila[4]]' id='Cont2_$fila[4]' value='$Conteo2' onKeyUp='xNumero(this)' onKeyDown='xNumero(this)' onBlur='campoNumero(this)'></td>";?>
											<td>
												<input type='text' style='width:40px;text-align:right' name='Cont3[<? echo $fila[4]?>]' value="<? echo $Conteo3?>" id="Cont3[<? echo $fila[4]?>]" onKeyUp='xNumero(this)' onKeyDown='xNumero(this)' onBlur='campoNumero(this)' onChange="Dif_<? echo $fila[4]?>.value=this.value-Existe_<? echo $fila[4]?>.value;Cost_<? echo $fila[4]?>.value=(this.value-Existe_<? echo $fila[4]?>.value)*PCosto_<? echo $fila[4]?>.value">
											</td>
											<?
											echo "<td><input type='text' style='width:40px;text-align:center' readonly name='Dif[$fila[4]]' id='Dif_$fila[4]' value='$Diferencia'></td>";
											echo "<td><input type='text' style='width:80px;text-align:center' readonly name='Cost[$fila[4]]' id='Cost_$fila[4]' value='$TotCosto'></td>";
										echo "</tr>";
									}
							?>
						</table>
						<input type="hidden" name="Anio" value="<? echo $Anio?>">
						<input type="hidden" name="Mes" value="<? echo $Mes?>">
						<input type="hidden" name="Grupo" value="<? echo $Grupo?>">

						<input type="submit" name="Guardar" class="boton2Envio" value="Guardar Inventario">
						<input type="submit" name="Cerrar" class="boton2Envio" value="Cerrar Inventario">
						<input type="button" class="boton2Envio" value="Volver" onClick="parent.location.href='ListaMovimientoxAjustes.php?DatNameSID=<? echo $DatNameSID?>&AlmacenPpal=<? echo $AlmacenPpal?>&AnioI=<? echo $Anio?>&MesI=<? echo $Mes?>'">
						<input type="Hidden" name="Editar" value="<? echo $Editar?>">
					</form>
					<?	}?>
				</div>	
			</body>
</html>
