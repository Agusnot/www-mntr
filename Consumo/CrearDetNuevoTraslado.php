		<?
			if($DatNameSID){session_name("$DatNameSID");}
			session_start();
			include("Funciones.php");
			include("ObtenerSaldos.php");
			include_once("General/Configuracion/Configuracion.php");
			//UM:27-04-2011
			$VrSaldoIni=SaldosIniciales($Anio,$AlmacenPpal,$Fecha);
			$VrEntradas=Entradas($Anio,$AlmacenPpal,"$Anio-01-01",$Fecha);
			$VrSalidas=Salidas($Anio,$AlmacenPpal,"$Anio-01-01",$Fecha);
				$VrDevoluciones=Devoluciones($Anio,$AlmacenPpal,"$Anio-01-01",$Fecha);
			if(!$IDTraslado)
			{
				$cons = "Select IDTraslado from Consumo.TmpMovimiento where TMPCOD='$TMPCOD' order by IDTraslado desc";
				$res = ExQuery($cons);
				if(ExNumRows($res)>0)
				{
					$fila = ExFetch($res);
					$IDTraslado = $fila[0] + 1;
				}
				else
				{
					$cons1 = "Select IDTraslado from Consumo.Movimiento where Compania='$Compania[0]' order by IDTraslado desc";
					$res1 = ExQuery($cons1);
					$fila1 = ExFetch($res1);
					$IDTraslado = $fila1[0] + 1;
				}
			}
			if($Guardar)
			{
				//echo "$Anio--$AlmacenPpal--$Fecha";
				$cons="Select Codigo1,NombreProd1,Presentacion,UnidadMedida,AutoId,VrIva,Min,Max,Grupo from Consumo.CodProductos WHERE 
					Compania='$Compania[0]' and Codigo1 = '$Codigo' and AlmacenPpal='$AlmacenPpal' and Anio=$Anio Order By Codigo1";
					$res=ExQuery($cons);echo ExError();
					if(ExNumRows($res)==1)
					{
						$fila=ExFetch($res);
						$CantFinal=$VrSaldoIni[$fila[4]][0]+$VrEntradas[$fila[4]][0]-$VrSalidas[$fila[4]][0]+$VrDevoluciones[$fila[4]][0];
						$SaldoFinal=$VrSaldoIni[$fila[4]][1]+$VrEntradas[$fila[4]][1]-$VrSalidas[$fila[4]][1]+$VrDevoluciones[$fila[4]][1];
						if($CantFinal>0){$VrCosto=$SaldoFinal/$CantFinal;$TotCosto=$VrCosto*$Cantidad;}
					}
				if(!$VrCosto){$VrCosto = 0;} if(!$TotCosto){ $TotCosto = 0;}
				if(!$Editar)
				{
					$cons = "insert into Consumo.TmpMovimiento (TMPCOD,AutoId,Cantidad,VrCosto,TotCosto,TipoTraslado,IDTraslado)
							values ('$TMPCOD',$AutoId,$Cantidad,$VrCosto,$TotCosto,'O','$IDTraslado')";
					$cons1 = "insert into Consumo.TmpMovimiento (TMPCOD,AutoId,Cantidad,VrCosto,TotCosto,TipoTraslado,AlmacenPpalD,IDTraslado)
							values ('$TMPCOD',$AutoIdD,$Cantidad,$VrCosto,$TotCosto,'D','$AlmacenPpalD','$IDTraslado')";
				}
				else
				{
					$cons = "Update Consumo.TmpMovimiento set AutoId = '$AutoId', Cantidad = '$Cantidad', VrCosto = $VrCosto, TotCosto = $TotCosto
							where TMPCOD='$TMPCOD' and IDTraslado='$IDTraslado' and AutoId = '$AutoIdx'";
					//echo $cons;
					$cons1 = "Update Consumo.TmpMovimiento set AutoId = '$AutoIdD', Cantidad = '$Cantidad', VrCosto = $VrCosto, TotCosto = $TotCosto
							where TMPCOD='$TMPCOD' and IDTraslado='$IDTraslado' and AutoId = '$AutoIdDx'";
				}
				$res = ExQuery($cons);
				if(ExError())
				{
					echo "$cons<br>$cons1";
					exit();
				}
				$res1 = ExQuery($cons1);
				//echo "$cons<br>$cons1<br>";
				?><script language="javascript">
					frames.parent.document.FORMA.Guardar.disabled = false;
					frames.parent.Ocultar();
					location.href="DetNuevoTraslado.php?DatNameSID=<? echo $DatNameSID?>&Anio=<? echo $Anio?>&AlmacenPpal=<? echo $AlmacenPpal; ?>&TMPCOD=<? echo $TMPCOD; ?>&Tipo=<? echo $Tipo?>&Comprobante=<? echo $Comprobante; ?>";    
				</script><?	
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
				<script language="javascript" src="/Funciones.js"></script>
				<script language="javascript">
					function Validar()	{
						var b=0;
						if(document.FORMA.Codigo.value == ""){alert("Por Favor, llene el campo Codigo en el Origen"); b=1;}
						else{if(document.FORMA.Cantidad.value == ""){alert("Por Favor, llene el campo Cantidad en el Origen");b=1;}
							else{if(document.FORMA.Nombre.value == ""){alert("Por Favor, llene el campo Producto en el Origen");b=1;}
								else{if(document.FORMA.CodigoD.value == ""){alert("Por Favor, llene el campo Codigo en el Destino"); b=1;}
									else{if(document.FORMA.NombreD.value == ""){alert("Por Favor, llene el campo Producto en el Destino");b=1;}}}}}
						
						if((document.FORMA.ExistCorteO.value - document.FORMA.Cantidad.value) < document.FORMA.MinO.value)
						{
							if(confirm("Las Existencias Quedaran por debajo del minimo permitido, Desea Continuar?"))
							{
								if((document.FORMA.ExistCorteO.value - document.FORMA.Cantidad.value) < 0)
								{
									alert("Las Existencias no Pueden Quedar por debajo de Cero!!");
									b = 1;
								}
							}
							else{b = 1;}
						}
						if((document.FORMA.ExistAnualO.value - document.FORMA.Cantidad.value) < 0)
						{
							alert("Las Existencias Anuales no Pueden Quedar por debajo de Cero!!");
							b = 1;
						}
						if(b==1){return false;}
					}
				</script>
			</head>	
			<body>
				<form name="FORMA" method="post" onSubmit="return Validar()">
					<input type="Hidden" name="Anio" value="<? echo $Anio?>" />
					<input type="Hidden" name="Fecha" value="<? echo $Fecha?>" />
					<input type="Hidden" name="AlmacenPpal" value="<? echo $AlmacenPpal?>" />
					<input type="Hidden" name="Comprobante" value="<? echo $Comprobante?>" />
					<input type="Hidden" name="TMPCOD" value="<? echo $TMPCOD?>" />
					<input type="Hidden" name="Tipo" value="<? echo $Tipo?>" />
					<input type="Hidden" name="Numero" value="<? echo $Numero?>" />
					<input type="hidden" name="DatNameSID" value="<? echo $DatNameSID?>" />
					<?
						if($Editar)	{
							$consO = "Select Codigo1,(NombreProd1 || ' ' || Presentacion || ' ' || UnidadMedida),TmpMovimiento.AutoId,Cantidad 
									  from Consumo.TmpMovimiento, Consumo.CodProductos 
									   where TMPCOD = '$TMPCOD' and CodProductos.AutoId = TmpMovimiento.AutoId and TipoTraslado='O' and IDTraslado='$IDTraslado'";
							$resO = ExQuery($consO);
							$filaO = ExFetch($resO);
							$Codigo = $filaO[0]; $AutoId = $filaO[2]; $Cantidad = $filaO[3]; $Nombre = $filaO[1];
							
							$consD = "Select Codigo1,(NombreProd1 || ' ' || Presentacion || ' ' || UnidadMedida),TmpMovimiento.AutoId,Cantidad, AlmacenPpalD 
									  from Consumo.TmpMovimiento, Consumo.CodProductos 
									   where TMPCOD = '$TMPCOD' and CodProductos.AutoId = TmpMovimiento.AutoId and TipoTraslado='D' and IDTraslado='$IDTraslado'";
							$resD = ExQuery($consD);
							$filaD = ExFetch($resD);
							$AlmacenPpalD = $filaD[4]; $ECodigoD = $filaD[0]; $EAutoIdD = $filaD[2]; $ENombreD = $filaD[1];
						}
					?>
					<table width="100%" class="tabla2" style="vertical:align:text-top;"  <?php echo $borderTabla2Mentor; echo $bordercolorTabla2Mentor; echo $cellspacingTabla2Mentor; echo $cellpaddingTabla2Mentor; ?>>
						<tr>
							<td colspan="4" class="encabezado2Horizontal">ORIGEN</td>
						</tr>
						<tr>
							<td class="encabezado2VerticalInvertido">ALMAC&Eacute;N DE ORIGEN </td>
							<td colspan="3"> <? echo strtoupper($AlmacenPpal);?></td>
						</tr>
						<tr>
							<td width="20%" class="encabezado2VerticalInvertido" >C&Oacute;DIGO</td>
							<td>
								<input type="text" name="Codigo" id="Codigo" size="10" value="<? echo $Codigo?>" onKeyUp="xNumero(this)" onKeyDown="xNumero(this)" onBlur="campoNumero(this)" onFocus="frames.parent.Ocultar()" />
								<input type="Hidden" name="AutoId" value="<? echo $AutoId?>"/>
								<input type="Hidden" name="AutoIdx" value="<? echo $AutoId?>"/>
							</td>
							<td class="encabezado2VerticalInvertido">CANTIDAD</td>
							<td>
								<input type="text" name="Cantidad" value="<? echo $Cantidad?>" onFocus="frames.parent.Ocultar()" size="6" maxlength="6" onKeyUp="xNumero(this)" onKeyDown="xNumero(this)" onBlur="campoNumero(this);FORMA.TotCosto.value = this.value * FORMA.VrCosto.value;" />
							</td>
						</tr>
						<tr>
							<td class="encabezado2VerticalInvertido">PRODUCTO</td>
							<td colspan="3">
								<input type="text" name="Nombre" id="Nombre" style="width:100%" value="<? echo $Nombre?>" onFocus="frames.parent.Mostrar();	frames.parent.Busquedas.location.href='Busquedas.php?DatNameSID=<? echo $DatNameSID?>&Fecha=<? echo $Fecha?>&Anio='+parent.document.FORMA.Anio.value+'&Tipo=NombreProducto&NomProducto='+this.value+'&Objeto='+this.name+'&AlmacenPpal=<? echo $AlmacenPpal?>';" onkeyup="FORMA.Codigo.value=' ';xLetra(this);	frames.parent.Mostrar(); frames.parent.Busquedas.location.href='Busquedas.php?DatNameSID=<? echo $DatNameSID?>&Fecha=<? echo $Fecha?>&Anio='+parent.document.FORMA.Anio.value+'&Tipo=NombreProducto&NomProducto='+this.value+'&Objeto='+this.name+'&AlmacenPpal=<? echo $AlmacenPpal?>';" onKeyDown="xLetra(this)" />
							</td>
						</tr>
						<tr>
							<td colspan="4" style="text-align:center;">
								<table  width="100%" class="tabla2"   <?php echo $borderTabla2Mentor; echo $bordercolorTabla2Mentor; echo $cellspacingTabla2Mentor; echo $cellpaddingTabla2Mentor; ?>>
									<tr>
										<td class="encabezado2VerticalInvertido" width="25%">MAX.</td>
										<td style="text-align:center;" width="25%">
											<input type="text" style="width:100%" name="MaxO" readonly size="2" onFocus="frames.parent.Ocultar()" value="<? echo $MaxO;?>" >
										</td>
										<td class="encabezado2VerticalInvertido" width="25%">MIN </td>
										<td style="text-align:center;" width="25%">
											<input type="text" style="width:100%" name="MinO" readonly size="2" onFocus="frames.parent.Ocultar()" value="<? echo $MinO;?>">
										</td>
									</tr>
									<tr>
										<td class="encabezado2VerticalInvertido" width="25%">EXIST. CORTE</td>
										<td style="text-align:center;" width="25%">
											<input type="text" name="ExistCorteO" style="width:100%" readonly size="2" onFocus="frames.parent.Ocultar()" value="<? echo $ExistCorteO;?>" >
										</td>
										<td class="encabezado2VerticalInvertido" width="25%">EXIST. ANUAL </td>
										<td style="text-align:center;" width="25%">
											<input type="text" name="ExistAnualO" style="width:100%" readonly size="2" onFocus="frames.parent.Ocultar()" value="<? echo $ExistAnualO?>" >
										</td>
									</tr>	
									<tr>
									
										<td class="encabezado2VerticalInvertido" width="25%">VR. COSTO </td>
										<td style="text-align:center;" width="25%">
											<input type="text" name="VrCosto" style="width:100%" readonly size="4" onFocus="frames.parent.Ocultar()" value="<? echo $VrCosto?>" />
										</td>
										<td class="encabezado2VerticalInvertido" width="25%">TOTAL </td>
										<td style="text-align:center;" width="25%">
											<input type="text" name="TotCosto" style="width:100%" readonly size="4" onFocus="frames.parent.Ocultar()" value="<? echo $TotCosto?>" />
										</td>
									</tr>
								</table>	
							</td>
						</tr>
					</table>
					<table  width="100%" class="tabla2" cellspacing="0" cellpadding="2"   <?php echo $borderTabla2Mentor; echo $bordercolorTabla2Mentor; ?>>
						<tr>
							<td class="encabezado2Horizontal" colspan="2">DESTINO</td>
						</tr>
						<tr>
							<td class="encabezado2VerticalInvertido" width="40%">ALMAC&Eacute;N DE DESTINO</td>
							<td colspan="3">
								<select name="AlmacenPpalD" style="width:100%" onChange="document.FORMA.submit();" onFocus="frames.parent.Ocultar()">
									<?
									$cons = "Select AlmacenPpal from Consumo.AlmacenesPpales where Compania='$Compania[0]'";
									$res = ExQuery($cons);
									while($fila = ExFetch($res)){
										if($AlmacenPpalD==$fila[0]){echo "<option selected value='$fila[0]'>$fila[0]</option>";}
										else{echo "<option value='$fila[0]'>$fila[0]</option>";}
									}
									?>
								</select>
							</td>
						</tr>
						<tr>
							<td class="encabezado2VerticalInvertido" width="40%">C&Oacute;DIGO</td>
							<td>
								<input type="text" name="CodigoD" style="width:100%" id="CodigoD" readonly size="10" value="<? echo $ECodigoD?>" onKeyUp="xNumero(this)" onKeyDown="xNumero(this)" onBlur="campoNumero(this)" />
								<input type="Hidden" name="AutoIdD" value="<? echo $EAutoIdD?>"/>
								<input type="Hidden" name="AutoIdDx" value="<? echo $EAutoIdD?>"/>
							</td>
							
						</tr>
						<tr>
							<td class="encabezado2VerticalInvertido" width="40%">PRODUCTO</td>
							<td colspan="3">
								<input type="text" name="NombreD" id="NombreD" style="width:100%" value="<? echo $ENombreD?>" onFocus="frames.parent.Mostrar();	frames.parent.Busquedas.location.href='Busquedas.php?DatNameSID=<? echo $DatNameSID?>&Fecha=<? echo $Fecha?>&Anio='+parent.document.FORMA.Anio.value+'&Tipo=NombreProducto&NomProducto='+this.value+'&Objeto='+this.name+'&AlmacenPpal=<? echo $AlmacenPpalD?>';" onkeyup="FORMA.CodigoD.value=' '; xLetra(this);frames.parent.Busquedas.location.href='Busquedas.php?DatNameSID=<? echo $DatNameSID?>&Fecha=<? echo $Fecha?>&Anio='+parent.document.FORMA.Anio.value+'&Tipo=NombreProducto&NomProducto='+this.value+'&Objeto='+this.name+'&AlmacenPpal=<? echo $AlmacenPpalD?>';"	onKeyDown="xLetra(this)"/>
							</td>	
						</tr>
						<tr>
							<td colspan="4">
								<table  width="100%" class="tabla2" cellspacing="0" cellpadding="2"   <?php echo $borderTabla2Mentor; echo $bordercolorTabla2Mentor; ?> >
									<tr>
										<td class="encabezado2VerticalInvertido">MAX.</td>
										<td style="text-align:center;">
											<input type="text" name="MaxD" readonly size="2" onFocus="frames.parent.Ocultar()">
										</td>
										<td class="encabezado2VerticalInvertido">MIN.</td>
										<td style="text-align:center;">
											<input type="text" name="MinD" readonly size="2" onFocus="frames.parent.Ocultar()">
										</td>
									
										<td class="encabezado2VerticalInvertido">EXIST. CORTE </td>
										<td style="text-align:center;">
											<input type="text" name="ExistCorteD" readonly size="2" onFocus="frames.parent.Ocultar()">
										</td>
										<td class="encabezado2VerticalInvertido">EXIST. ANUAL </td>
										<td style="text-align:center;">
											<input type="text" name="ExistAnualD" readonly size="2" onFocus="frames.parent.Ocultar()">
										</td>
									</tr>
								</table>	
							</td>
						</tr>
						<tr>
							<td colspan="8" style="text-align:center;padding-top:10px;padding-bottom:10px;">
								<input type="submit" name="Guardar" class="boton2Envio" value="Guardar" />
								<input type="button" name="Cerrar" class="boton2Envio" value="Cerrar" onclick="location.href='DetNuevoTraslado.php?DatNameSID=<? echo $DatNameSID?>&Anio=<? echo $Anio?>&AlmacenPpal=<? echo $AlmacenPpal; ?>	&TMPCOD=<? echo $TMPCOD; ?>&Tipo=<? echo $Tipo?>&Comprobante=<? echo $Comprobante; ?>';frames.parent.Ocultar();" />
							</td>
						</tr>
					</table>
									</form>
			</body>