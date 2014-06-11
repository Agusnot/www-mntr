<?
	if($DatNameSID){session_name("$DatNameSID");}
	session_start();
	include("Funciones.php");
	include_once("General/Configuracion/Configuracion.php");
	if(!$NewRecord){$NewRecord=0;}
	if(!$Cerrar){$Cerrar=0;}
	if($Eliminar)
	{
		$NumCar=strlen($Cuenta);

		$cons3="Select sum(NoCaracteres) from Contabilidad.EstructuraPuc where Compania='$Compania[0]' and Anio=$AnioAc";
		$res3=ExQuery($cons3,$conex);echo ExError($res3);
		$fila3=ExFetch($res3);
		$NoTotalCar=$fila3[0];

		$cons2="Select NoCaracteres from Contabilidad.EstructuraPuc where Compania='$Compania[0]' and Anio=$AnioAc order By Nivel";
		$res2=ExQuery($cons2,$conex);echo ExError($res2);
		while($fila2=ExFetch($res2))
		{
			$NumCarPUC=$NumCarPUC+$fila2[0];
			if($Act){$MaxLen=$fila2[0];$Act=0;}
			if($NumCar==$NumCarPUC){$Act=1;}
		}

		$cons="Select * from Contabilidad.Movimiento where Cuenta ilike '$Cuenta%' and Compania='$Compania[0]'";
		$res=ExQuery($cons);
		if(ExNumRows($res)==0)
		{
			$cons1="Select * from Contabilidad.PlanCuentas where Cuenta ilike '$Cuenta%' and Compania='$Compania[0]' and Anio=$AnioAc";
			$res1=ExQuery($cons1);
			if(ExNumRows($res1)==1)
			{
				$cons="Delete from Contabilidad.PlanCuentas where Cuenta ilike '$Cuenta%' and Compania='$Compania[0]' and Anio=$AnioAc";
				$res=ExQuery($cons);echo ExError($res);
				$CtaDest=substr($Cuenta,0,strlen($Cuenta)-$MaxLen);
				$Cuenta=$CtaDest;echo $CtaBuscar;
?>		
				<script language="JavaScript">
					parent(0).Abajo.location.href="ListaCuentasPUC.php?DatNameSID=<? echo $DatNameSID?>&Seccion=<?echo substr($Seccion,1,strlen($Seccion)-2)?>&CtaBuscar=<?echo $CtaBuscar?>#<?echo $CtaDest?>";
					parent(0).Abajo.location.href="ListaCuentasPUC.php?DatNameSID=<? echo $DatNameSID?>&CtaBuscar=<?echo $CtaBuscar?>#<?echo $CtaDest?>";
					location.href="DetalleCuenta.php?DatNameSID=<? echo $DatNameSID?> Cuenta=<?echo $Cuenta?>&Seccion=<?echo $Seccion?>";
				</script>
<?			}
			else
			{
				
				echo "<div align='center'><p style='color:#FF0000;'>Cuenta tiene subcuentas, no es posible eliminar</p></div>";
			}
		}
		else
		{
			echo "<div align='center'><p style='color:#FF0000;'>Cuenta tiene movimiento, no es posible eliminar</p></div>";
		}
	}
	if($Nuevo)
	{
		$Nombre="";$Tipo="";$CentoCostos="";$Corriente="";
		$cons="Select * from Contabilidad.PlanCuentas where Cuenta='$Cuenta' and Compania='$Compania[0]' and Anio=$AnioAc";
		$res=ExQuery($cons,$conex);echo ExError($res);
		$fila=ExFetchArray($res);
		$Naturaleza=$fila['naturaleza'];$Tipo="";$Tercero=0;
	}

	if($Guardar)
	{
		if($Tercero=="on"){$Tercero=1;}else{$Tercero=0;}
		if($Documentos=="on"){$Documentos=1;}else{$Documentos=0;}
		if(!$Tercero){$Tercero=0;}
		if(!$Banco){$Banco=0;}
		if($NewRecord)
		{

			$Cuenta=$PreNewCuenta.$NewCuenta;
			$cons="Insert into Contabilidad.PlanCuentas (Anio,Compania,Cuenta,Nombre,Naturaleza,Tipo,CentroCostos,Corriente,Banco,Diferido,Tercero,Cod1001,Documentos)
			values('$AnioAc','$Compania[0]','" .$Cuenta. "','$Nombre','$Naturaleza','$Tipo','$CentroCostos','$Corriente',$Banco,'$Diferido',$Tercero,'$Codigo1001',$Documentos)";
		}
		else
		{
			$cons="Update Contabilidad.PlanCuentas set Nombre='$Nombre',Naturaleza='$Naturaleza',Tipo='$Tipo',CentroCostos='$CentroCostos',Corriente='$Corriente',Banco=$Banco,
			Diferido='$Diferido',Tercero=$Tercero,Cod1001='$Codigo1001',Documentos=$Documentos 
			where Cuenta='$Cuenta' and Compania='$Compania[0]' and Anio=$AnioAc";
			$SeccAnt=$Seccion;
			$Seccion=substr($Seccion,0,strlen($Seccion)-2);
		}
		$res=ExQuery($cons,$conex);echo ExError($res);
		if($Cerrar!=1){

		if($Tipo=="Detalle"){if($PreNewCuenta){$Cuenta=$PreNewCuenta;}}
		if($NewRecord){
		?>
		<script language="JavaScript">
			parent(0).Abajo.location.href="ListaCuentasPUC.php?DatNameSID=<? echo $DatNameSID?>&Seccion=<?echo $Seccion?>&CtaBuscar=<?echo $CtaBuscar?>#<?echo $PreNewCuenta?>";
			parent(0).Abajo.location.href="ListaCuentasPUC.php?DatNameSID=<? echo $DatNameSID?>&CtaBuscar=<?echo $CtaBuscar?>#<?echo $PreNewCuenta?>";
		</script>
		<?
		}
		if($Tipo=="Titulo" && $NewRecord){$Seccion=$Seccion."_0";}
		}

		else
		{?>
		<script language="JavaScript">
			window.close();
		</script>
<?		}
if($SeccAnt){$Seccion=$SeccAnt;}
	}

	if(!$Nuevo)
	{
		$cons="Select * from Contabilidad.PlanCuentas where Cuenta='$Cuenta' and Compania='$Compania[0]' and Anio=$AnioAc";
		$res=ExQuery($cons,$conex);echo ExError($res);
		$fila=ExFetchArray($res);
		$Nombre=$fila['nombre'];$Naturaleza=$fila['naturaleza'];$Tipo=$fila['tipo'];$CentoCostos=$fila['centrocostos'];$Corriente=$fila['corriente'];
		$Banco=$fila['banco'];$AfectacionPresup=$fila['afectacionpresup'];$Tercero=$fila['tercero'];$Codigo1001=$fila['cod1001'];$Documentos=$fila['documentos'];
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
		<body>
			<div align="center">
				<iframe id="FrameOpener" name="FrameOpener" style="display:none" frameborder="0" height="1" style="border:#e5e5e5 ridge" ></iframe>

				<script language="JavaScript">
					parent(2).location.href="SaldosxCuenta.php?DatNameSID=<? echo $DatNameSID?>&Cuenta=<?echo $Cuenta?>";
				</script>
				
				<script language="JavaScript">
					function Validar(){
						if(document.FORMA.NewCuenta.value.length!=document.FORMA.NewCuenta.maxLength){alert("Digitos incorrectos");return false;}
						if(document.FORMA.NewCuenta.value==""){alert("Escriba una cuenta valida!!!");return false;}
						if(document.FORMA.Nombre.value==""){alert("Escriba un nombre de cuenta valido!!!");return false;}
						if(document.FORMA.Tipo.value==""){alert("Seleccione un tipo de Cuenta!!!");document.FORMA.Tipo.focus();return false;}
					}
				</script>
				
				<form name="FORMA" onSubmit="return Validar()">
				<table class="tabla2"  <?php echo $borderTabla2Mentor ; echo $bordercolorTabla2Mentor ; echo $cellspacingTabla2Mentor ; echo $cellpaddingTabla2Mentor; ?>>
					<tr>
						<td class="encabezado2Horizontal">C&Oacute;DIGO</td>
						
				<?
					if($Nuevo){
						echo "<td colspan='3'>";
						echo $Cuenta;
						$NumCar=strlen($Cuenta);

						$cons3="Select sum(NoCaracteres) from Contabilidad.EstructuraPuc where Compania='$Compania[0]' and Anio=$AnioAc";
						$res3=ExQuery($cons3,$conex);echo ExError($res3);
						$fila3=ExFetch($res3);
						$NoTotalCar=$fila3[0];

						$cons2="Select NoCaracteres from Contabilidad.EstructuraPuc where Compania='$Compania[0]' and Anio=$AnioAc order By Nivel";
						$res2=ExQuery($cons2,$conex);echo ExError($res2);
						while($fila2=ExFetch($res2))
						{
							$NumCarPUC=$NumCarPUC+$fila2[0];
							if($Act){$MaxLen=$fila2[0];$Act=0;$TotNumCar=$NumCarPUC;}
							if($NumCar==$NumCarPUC){$Act=1;}
						}

						if(!$TotNumCar){$TotNumCar=0;}
						$cons9="Select Cuenta from Contabilidad.PlanCuentas where Compania='$Compania[0]' and Anio=$AnioAc and Cuenta like '$Cuenta%' and length(Cuenta)=$TotNumCar Order By Cuenta Desc";
						$res9=ExQuery($cons9);
						$fila9=ExFetch($res9);
						$fila9[0]++;
						$fila9[0]=substr($fila9[0],strlen($fila9[0])-$MaxLen,$MaxLen);
						for($Ceros=1;$Ceros<=$MaxLen;$Ceros++)
						{
							$VrCeros=$VrCeros."0";
						}

						$CadNew=substr($VrCeros,0,strlen($VrCeros)-strlen($fila9[0])).$fila9[0];

						if($Mayores==1){$MaxLen=1;}
						?>
						<input type="Hidden" name="PreNewCuenta" value="<?echo $Cuenta?>">
						<input type="Text" name="NewCuenta" style="width:<?echo ($MaxLen*15)?>px" maxlength="<?echo $MaxLen?>" value="<? echo $CadNew?>">
						<?
						if($MaxLen==0)
						{?>
							<a href="#" onClick="open('/Contabilidad/ConfEstructuraPUC.php?DatNameSID=<? echo $DatNameSID?>', '','width=480,height=280')"><em> No hay estructura para este nivel. Crear estructura</a></em>
				<?		}
						echo "</td></tr>";
					}
					else{
						?>
						<td>
							<input type="Text" name="Cuenta" readonly="yes" value="<?echo $Cuenta?>">
						</td>
						
						<td class="encabezado2Horizontal">
							AFECTACI&Oacute;N PRESUPUESTAL:
						</td>
						
						<td>
							<input name="AfectacionPresup" type="Text" style="width:100px;font-weight:bold;color:<?echo $ColAfect?>" readonly="yes" value="<?echo $AfectacionPresup?>">
						</td>	
						<?
					}
				?>

					</td>
				</tr>
				<tr>
					<td class="encabezado2Horizontal">NOMBRE</td>
					<td colspan="3"><input style="width:100%;" type="Text" name="Nombre" value="<?echo $Nombre?>"></td></tr>
				<tr>
					<td class="encabezado2Horizontal">NATURALEZA</td>
					<td>
						<select name="Naturaleza">
							<option value="">-Naturaleza-</option>
							<?
								$cons1="Select Naturaleza from Contabilidad.NaturalezaCuentas";
								$res1=ExQuery($cons1);
								while($fila1=ExFetch($res1))
								{
									if($fila['naturaleza']==$fila1[0]){echo "<option selected value='$fila1[0]'>$fila1[0]</option>";}
									else{echo "<option value='$fila1[0]'>$fila1[0]</option>";}
								}
							?>
						</select>
					</td>
					
					<td class="encabezado2Horizontal">TIPO</td>
					<td>
						<select name="Tipo" onChange="if(this.value=='Titulo'){CentroCostos.checked=false;CentroCostos.disabled=true}else{CentroCostos.disabled=false;}">
							<option value="">-Tipo-</option>
							<?
								$cons1="Select Tipo from Contabilidad.TiposCuenta";
								$res1=ExQuery($cons1);
								while($fila1=ExFetch($res1))
								{
									if($Tipo==$fila1[0]){echo "<option selected value='$fila1[0]'>$fila1[0]</option>";}
									else{echo "<option value='$fila1[0]'>$fila1[0]</option>";}
								}
							?>
						</select>
					</td>
				</tr>
			
				<tr>
					<td class="encabezado2Horizontal">CENTRO DE COSTOS</td>
					<td>
						<?
							if($Tipo=="Titulo"){$Disab=" disabled";}
							else{$Disab="";}
						?>

							<?if($fila['centrocostos']=='on'){?><input checked <? echo $Disab?> type="Checkbox" name="CentroCostos"><?}
							else{?><input <?echo $Disab?> type="Checkbox" name="CentroCostos"><?}?>
					</td>		

					<td class="encabezado2Horizontal">DIFERIDO</td>
					<td>
						<? 
						if($fila['diferido']=='on'){
							?>
							<input checked <? echo $Disab?> type="Checkbox" name="Diferido"><?
						}
						else{
							?>
							<input <? echo $Disab?> type="Checkbox" name="Diferido"><?
						}
						?>
					</td>			

				</tr>
				<tr>
					<td class="encabezado2Horizontal">TERCERO</td>
					<td>
						<? if($fila['tercero']==1){?><input checked <? echo $Disab?> type="Checkbox" name="Tercero"><?}
						else{?><input <? echo $Disab?> type="Checkbox" name="Tercero"><?}?>
					</td>
				
					<td class="encabezado2Horizontal">DOCUMENTOS</td>
					<td>
						<?
							if($Documentos==1)
							{
						?>
							<input type="checkbox" name="Documentos" checked>
							<? }
							else
							{
							?>
							<input type="checkbox" name="Documentos">
							<?	}?>
					</td>
				</tr>
				<tr>
				<td class="encabezado2Horizontal">CORRIENTE</td>
				<td>
					<?if($fila['corriente']=='on'){?><input checked type="Checkbox" name="Corriente"><?}
					else{?><input type="Checkbox" name="Corriente"><?}?>
					<?
						if($Tipo=="Detalle")
						{
					?>
				</td>
				<td class="encabezado2Horizontal">BANCO</td>
				<td>
					<select name="Banco">
						<?if($Banco=="1")
							{?>
							<option selected value="1">Si</option>
							<option value="0">No</option>
							<?}?>
						<?if($Banco=="0")
							{?>
							<option value="1">Si</option>
							<option selected value="0">No</option>
							<?}?>

					</select>
					<?	if($Banco=="1"){?>
						
						</script>
						<input type="Button" class="boton2Envio" value="Cheque" onClick="open('FormaCheque.php?DatNameSID=<? echo $DatNameSID?>&Cuenta=<?echo $Cuenta?>','','width=800,height=250')">
						<input type="Button" class="boton2Envio" value="Detalle Banco" onClick="AbrirBanco()">
						<?
						}?>

				</td>
				</tr>
				<tr>
					<td class="encabezado2Horizontal">
					EXOGENA 1001</td>
					<td colspan="3">
						<input type="text" name="Codigo1001" style="width:100%;' value="<?echo $Codigo1001?>" onClick="AbreExogena()"/></td>
				</tr><?}?>
				</table>
				<br>
				<?
					$cons="Select * from Contabilidad.Movimiento where Compania='$Compania[0]' and Cuenta ilike '$Cuenta%' and date_part('year',Fecha)=$AnioAc";
					$res=ExQuery($cons);
					$NumRecs=ExNumRows($res);
					$NumRecs=0;
				?>
				<script language="javascript">
				function AbreExogena()
				{
					frames.FrameOpener.location.href="/Contabilidad/BusquedaxOtros.php?DatNameSID=<? echo $DatNameSID?>&Tipo=CodigoExogena&Campo=Codigo1001";
					document.getElementById('FrameOpener').style.position='absolute';
					document.getElementById('FrameOpener').style.top='10px';
					document.getElementById('FrameOpener').style.left='15px';
					document.getElementById('FrameOpener').style.display='';
					document.getElementById('FrameOpener').style.width='590';
					document.getElementById('FrameOpener').style.height='250';
				}

				function AbreAfectacionPptal()
				{
						frames.FrameOpener.location.href="SelCuentaPresupuesto.php?DatNameSID=<? echo $DatNameSID?>&Cuenta=<? echo $Cuenta?>";
						document.getElementById('FrameOpener').style.position='absolute';
						document.getElementById('FrameOpener').style.top='110px';
						document.getElementById('FrameOpener').style.left='15px';
						document.getElementById('FrameOpener').style.display='';
						document.getElementById('FrameOpener').style.width='590';
						document.getElementById('FrameOpener').style.height='120';
				}
				function AbrirBanco()
				{
						frames.FrameOpener.location.href="DetallesBanco.php?DatNameSID=<? echo $DatNameSID?>&Cuenta=<?echo $Cuenta?>&Anio=<?echo $AnioAc?>";
						document.getElementById('FrameOpener').style.position='absolute';
						document.getElementById('FrameOpener').style.top='10px';
						document.getElementById('FrameOpener').style.left='10px';
						document.getElementById('FrameOpener').style.display='';
						document.getElementById('FrameOpener').style.width='620';
						document.getElementById('FrameOpener').style.height='270';
				}
				</script>
				<input type="Submit" class="boton2Envio" name="Guardar" value="Guardar"><??>
				<?if(!$Nuevo){
				if($NumRecs==0){?>
				<input type="Button" class="boton2Envio" value="Eliminar" onClick="location.href='DetalleCuenta.php?DatNameSID=<? echo $DatNameSID?>&Eliminar=1&Cuenta=<?echo $Cuenta?>&CtaBuscar=<?echo $CtaBuscar?>'">
				<?}
					if($Tipo=="Titulo")
					{
				?>
					<input type="Submit" class="boton2Envio" name="Nuevo" value="Nuevo">
				<?
					}
					else
					{?>
					<input type="Button" class="boton2Envio" value="Afectaci&oacute;n Presupuestal" style="width:170px;" onClick="AbreAfectacionPptal();">

				<?	}
					
					}
				?>
				<input type="Hidden" name="NewRecord" value="<?echo $Nuevo?>">
				<input type="Hidden" name="Long" value="<?echo $MaxLen?>">
				<input type="Hidden" name="Seccion" value="<?echo $Seccion?>">
				<input type="Hidden" name="Cerrar" value="<?echo $Cerrar?>">
				<input type="Hidden" name="CtaBuscar" value="<?echo $CtaBuscar?>">
				<input type="Hidden" name="DatNameSID" value="<?echo $DatNameSID?>">
				</form>
				VER INFORME:
				<select name="Informe" onChange="parent.location.href=this.value">
				<option value="SaldosxCuenta.php?DatNameSID=<? echo $DatNameSID?>&Cuenta=<?echo $Cuenta?>">- Seleccione Informe -</option>
				<option value="SaldosxCuenta.php?DatNameSID=<? echo $DatNameSID?>&Cuenta=<?echo $Cuenta?>">Saldos x Cuenta</option>
				<option value="MovimientoxCuenta.php?DatNameSID=<? echo $DatNameSID?>&Cuenta=<?echo $Cuenta?>">Movimiento x Cuenta</option>
				<option value="TerceroxCuenta.php?DatNameSID=<? echo $DatNameSID?>&Cuenta=<?echo $Cuenta?>">Terceros</option>
				</select>
				<hr>
			</div>	
		</body>
	</html>
