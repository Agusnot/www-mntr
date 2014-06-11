		<?
			if($DatNameSID){session_name("$DatNameSID");}
			session_start();
			include("Informes.php");
			include_once("General/Configuracion/Configuracion.php");
			
			$cons="Select SaldoExtracto from Contabilidad.SaldosConciliacion where Anio=$Anio and Mes=$Mes and Cuenta='$Banco' and Compania='$Compania[0]'";
			$res=ExQuery($cons);
			$fila=ExFetch($res);
			$SaldoExtracto=$fila[0];
			
			
			if($Guardar){
				if(!$VrExtracto){$VrExtracto=0;}
				$cons1="Select * from Contabilidad.SaldosConciliacion where Cuenta='$Banco' and Anio=$Anio and Mes=$Mes and Compania='$Compania[0]'";
				$res1=ExQuery($cons1);
				if(ExNumRows($res1)==0)
				{
					$cons="Insert into Contabilidad.SaldosConciliacion (Compania,Cuenta,Anio,Mes,SaldoExtracto) 
					values ('$Compania[0]','$Banco',$Anio,$Mes,$VrExtracto)";
				}
				else
				{
					$cons="Update Contabilidad.SaldosConciliacion set SaldoExtracto=$VrExtracto where Cuenta='$Banco' and Anio=$Anio and Mes=$Mes and Compania='$Compania[0]'";
				}
				$res=ExQuery($cons);

				echo ExError();
				$i=0;
				while (list($val,$cad) = each ($Comprobante)) 
				{
					$i++;
					$Divide=split("_",$cad);
					$ComprobanteAct=$Divide[0];$NumComprobanteAct=$Divide[1];
					if(!$Checkeo[$i]){$Checkeo[$i]=0;$AnioUp=0;}
					else{$AnioUp=$Anio;}

					if($TipoMov[$i]=="Movimiento"){$Tabla="Movimiento";}
					if($TipoMov[$i]=="PartidaInicial"){$Tabla="PartidaInicialConciliatoria";}
					if($Checkeo[$i]){$UpFecha="'$AnioUp-$Checkeo[$i]-01'";}else{$UpFecha="NULL";}

					$cons="Update Contabilidad.$Tabla set FechaConciliado=$UpFecha where Cuenta='$Banco' and Numero='$NumComprobanteAct' 
					and Comprobante='$ComprobanteAct' and Compania='$Compania[0]' and (FechaConciliado IS NULL Or FechaConciliado='$Anio-$Mes-01') and Debe='$Debe[$i]' and Haber='$Haber[$i]' and AutoId='$AutoId[$i]'";
					$res=ExQuery($cons);
					echo ExError();
				}
				?>
				<script language="JavaScript">
					location.href="ImpConciliacion.php?DatNameSID=<? echo $DatNameSID?>&Anio=<?echo $Anio?>&Mes=<?echo $Mes?>&Cuenta=<?echo $Banco?>";
				</script>
				<?
			}
			if($Mes<10){$Mes="0".$Mes;}
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
		<body>
			<div align="center" style="margin-top:50px;margin-bottom:50px;">
				<form name="FORMA" method="post">
				<table  rules="groups" class="tablaInformeContable" style="margin-top:25px;"  <?php echo $borderTablaInfContable; echo $bordercolorTablaInfContable; echo $cellspacingTablaInfContable; echo $cellpaddingTablaInfContable; ?>>
					<tr>
						<td colspan="7">
							<?
							$cons="Select Nombre from contabilidad.Plancuentas where Cuenta='$Banco' and Anio=$Anio and Compania='$Compania[0]'";
							$res=ExQuery($cons);
							$fila=ExFetch($res);
							echo "<strong>Cuenta: $fila[0]</strong>";
							?>
						</td>
					</tr>
					<tr>
						<td class='encabezado2HorizontalInfCont' colspan="6" >
							VALOR EXTRACTO: 
							<input type="Text" style="text-align:right; border:0px; width:90px; font-size:11px"   name="VrExtracto" value="<?echo $SaldoExtracto?>">
							<img src="/Imgs/flecha_der.png" />
							<? echo number_format($SaldoExtracto,2)?>
						</td>
						<td>
							<input type="submit" name="Guardar" class="boton2Envio" value="Guardar" /></td>
						</td>
					</tr>
				<?

					$cons="Select NumDias from Central.Meses where Numero=$Mes";
					$res=ExQuery($cons);
					$fila=ExFetch($res);
					$MaxNumDias=$fila[0];

					$cons="SELECT Comprobante FROM Contabilidad.Movimiento 
					WHERE Fecha<='$Anio-$Mes-$MaxNumDias' and Cuenta='$Banco' and (FechaConciliado IS NULL Or (FechaConciliado>='$Anio-$Mes-01')) and Compania='$Compania[0]' and Estado='AC' Group By Comprobante
					Union Select Comprobante from Contabilidad.PartidaInicialConciliatoria
					WHERE Fecha<='$Anio-$Mes-$MaxNumDias' and Cuenta='$Banco' and (FechaConciliado IS NULL Or (FechaConciliado>='$Anio-$Mes-01')) and Compania='$Compania[0]' Group By Comprobante";

					$i=0;
					$res=ExQuery($cons);echo ExError();
					while($fila=ExFetch($res))
					{
						echo "<tr bgcolor='#e5e5e5'><td colspan=7 style='font-weight:bold;text-align:center'>$fila[0]</td></tr>";
						echo "<tr bgcolor='#e5e5e5' style='font-weight:bold;text-align:center'><td>Fecha</td><td>Numero</td><td>Cheque</td><td>Tercero</td><td>Debito</td><td>Credito</td><td>Conc</td></tr>";
						$cons1="Select Fecha,Numero,NoCheque,Identificacion,Debe,Haber,'Movimiento',FechaConciliado,AutoId from Contabilidad.Movimiento 
						where Fecha<='$Anio-$Mes-$MaxNumDias' and Cuenta='$Banco' and (FechaConciliado IS NULL Or (FechaConciliado>='$Anio-$Mes-01'))
						and Comprobante='$fila[0]' and Compania='$Compania[0]' and Estado='AC' 
						Union Select Fecha,Numero,NoCheque,Identificacion,Debe,Haber,'PartidaInicial',FechaConciliado,AutoId from Contabilidad.PartidaInicialConciliatoria 
						where Fecha<='$Anio-$Mes-$MaxNumDias' and Cuenta='$Banco' and (FechaConciliado IS NULL Or (FechaConciliado>='$Anio-$Mes-01'))
						and Comprobante='$fila[0]' and Compania='$Compania[0]' Order By Numero,Debe,Haber";

						$res1=ExQuery($cons1);echo ExError();
						while($fila1=ExFetch($res1))
						{
							$i++;
							if($Fondo==1){$BG="#EEF6F6";$Fondo=0;}
							else{$BG="white";$Fondo=1;}

							if($fila1[4]){$Valor=$fila1[4];}
							if($fila1[5]){$Valor=$fila1[5];}
							$cons2="Select PrimApe,SegApe,PrimNom,SegNom from Central.Terceros where Identificacion='$fila1[3]' and Terceros.Compania='$Compania[0]'";
							$res2=ExQuery($cons2);
							$fila2=ExFetch($res2);
							$Tercero="$fila2[0] $fila2[1] $fila2[2] $fila2[3]";
							if($fila1[7]=="PartidaInicial"){$Msj=" *";}else{$Msj="";}

							echo "<tr>";
								echo "<td class='encabezado2HorizontalInfCont'>$fila1[0]</td>";
								echo "<td class='encabezado2HorizontalInfCont' align='right'>$fila1[1]$Msj</td>";
								echo "<td class='encabezado2HorizontalInfCont' align='right'>$fila1[2]</td>";
								echo "<td class='encabezado2HorizontalInfCont'>$fila1[3] $Tercero</td>";
								echo "<td class='encabezado2HorizontalInfCont' align='right'>".number_format($fila1[4],2)."</td>";
								echo "<td  class='encabezado2HorizontalInfCont' align='right'>".number_format($fila1[5],2)."</td>";
							$Msj1="";$Disabled="";

							if($fila1[7]!="$Anio-$Mes-01" && $fila1[7]!=""){$Msj1="<em>Conc ".substr($fila1[7],0,7)."</em>";$Disabled=" disabled ";}

							if($fila1[7]=="$Anio-$Mes-01"){$Checked=" checked ";}
							else{$Checked="";}?>
							<td><input <?echo $Checked?> type='Checkbox' <?echo $Disabled?> value="<? echo $Mes?>" name='<? echo "Checkeo[$i]"?>'> <?echo $Msj1?></td>
							<input type="Hidden" name="<?echo "TipoMov[$i]"?>" value="<?echo $fila1[6]?>">
							<input type="Hidden" name="<?echo "Debe[$i]"?>" value="<?echo $fila1[4]?>">
							<input type="Hidden" name="<?echo "Haber[$i]"?>" value="<?echo $fila1[5]?>">
							<input type="Hidden" name="<?echo "AutoId[$i]"?>" value="<?echo $fila1[8]?>">
				<?			echo "<td><input type='Hidden' name='Comprobante[$i]' value='$fila[0]_$fila1[1]'></td>";
							echo "</tr>";
						}
					}
				?>
				<input type="Hidden" name="Anio" value="<?echo $Anio?>">
				<input type="Hidden" name="Mes" value="<?echo $Mes?>">
				<input type="Hidden" name="Banco" value="<?echo $Banco?>">
				<input type="hidden" name="DatNameSID" value="<? echo $DatNameSID?>" />
				<tr><td colspan="7" align="right"><input type="Submit" name="Guardar" class="boton2Envio" value="Guardar"></td></tr>
				</form>
				</table>
				<font size="2">
				<em>* Partidas conciliatorias Iniciales</em></font>
			</div>	
		</body>
	</html>	
		