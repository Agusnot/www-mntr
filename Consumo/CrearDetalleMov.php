		<?
			if($DatNameSID){session_name("$DatNameSID");}
			session_start();
			include("Funciones.php");
			include_once("General/Configuracion/Configuracion.php");
				$consyyy = "Select * from Consumo.AlmacenesPpales Where Compania='$Compania[0]' and AlmacenPpal='$AlmacenPpal'
				and SSFarmaceutico=1";
				$resyyy = ExQuery($consyyy);
				if(ExNumRows($resyyy)){$AlmacenSF = 1;}
				if($Guardar)
			{
					$consxxx = "Select CUM from Consumo.CUMSxProducto Where Compania='$Compania[0]' and AlmacenPpal='$AlmacenPpal'
					and Autoid=$AutoId";
					$resxxx = ExQUery($consxxx);
					if(ExNumRows($resxxx)>0 || !$AlmacenSF || $Tipo != "Entradas")
					{
						if(!$Cantidad){$Cantidad=0;}
						if(!$VrCosto){$VrCosto=0;}
						if(!$TotCosto){$TotCosto=0;}
						if(!$VrVenta){$VrVenta=0;}
						if(!$TotVenta){$TotVenta=0;}
						if(!$PorcIVA){$PorcIVA=0;}
						if(!$VrIVA){$VrIVA=0;}
				if(!$PorcReteFte){$PorcReteFte=0;}
						if(!$VrReteFte){$VrReteFte=0;}
						if(!$PorcDescto){$PorcDescto=0;}
						if(!$VrDescto){$VrDescto=0;}
						if(!$PorcICA){$PorcICA=0;}
						if(!$VrICA){$VrICA=0;}
				if($incluyeIVA){$incluyeIVA = 1;}
				else{ $incluyeIVA = 0;}
				if(!$ConceptoReteFte){$ConceptoReteFte="NULL";}else{$ConceptoReteFte="'$ConceptoReteFte'";}
				$TotCosto = round($TotCosto);
						$VrIVA = round($VrIVA);
						if($Editar)
				{
					$cons = "Update Consumo.TmpMovimiento set AutoId = $AutoId,
					Cantidad = $Cantidad,VrCosto = $VrCosto, TotCosto=$TotCosto, VrVenta=$VrVenta,
					TotVenta = $TotVenta, PorcIVA = $PorcIVA, VrIVA = $VrIVA, PorcReteFte=$PorcReteFte,
					VrReteFte = $VrReteFte, PorcDescto = $PorcDescto, VrDescto = $VrDescto,
					PorcICA = $PorcICA, VrICA = $VrICA, CentroCosto = '$CC', IncluyeIVA = $incluyeIVA,conceptortefte=$ConceptoReteFte
					where TMPCOD = '$TMPCOD' and AutoId = $AutoId";
				}
				else
				{
					$cons = "Insert into Consumo.TmpMovimiento
					(TMPCOD, AutoId, Cantidad, VrCosto, TotCosto, VrVenta, TotVenta, PorcIVA,
					VrIVA, PorcReteFte, VrReteFte, PorcDescto, VrDescto, PorcICA, VrICA, CentroCosto, incluyeIVA,conceptortefte)
					values
					('$TMPCOD',$AutoId,$Cantidad,$VrCosto,$TotCosto,$VrVenta,$TotVenta,$PorcIVA,$VrIVA,$PorcReteFte,$VrReteFte,$PorcDescto,$VrDescto,
					 $PorcICA,$VrICA, '$CC', $incluyeIVA,$ConceptoReteFte)";
				}
				$res = ExQuery($cons);
				$consL = "Select SUM(Cantidad) from Consumo.Lotes Where Compania='$Compania[0]' and AlmacenPpal='$AlmacenPpal' and Numero='$Numero'
				and TMPCOD='$TMPCOD' and AutoId = $AutoId and Cantidad <= $Cantidad group by AutoId";
				//echo $consL; exit;
				$resL = ExQuery($consL);
				if(ExNumRows($resL)==0)
				{
					$consL1 = "Delete from Consumo.Lotes Where Compania='$Compania[0]' and AlmacenPpal='$AlmacenPpal' and Numero='$Numero'
					and TMPCOD='$TMPCOD' and AutoId = $AutoId";
					$resL1 = ExQuery($consL1);
				}
				?>
				<script language="javascript">
					location.href="DetNuevoMovimientos.php?DatNameSID=<? echo $DatNameSID?>&Anio=<? echo $Anio?>&TMPCOD=<? echo $TMPCOD?>&AlmacenPpal=<? echo $AlmacenPpal?>&Comprobante=<? echo $Comprobante?>&Tipo=<? echo $Tipo?>&Numero=<? echo $Numero?>";
					parent.frames.TotMovimientos.location.href='TotMovimientos.php?DatNameSID=<? echo $DatNameSID?>&TMPCOD=<?echo $TMPCOD?>&Comprobante=<? echo $Comprobante?>&Numero=<? echo $Numero?>&AlmacenPpal=<? echo $AlmacenPpal?>';
				</script>
				<?
					}
					else
					{
						?>
							<script language="javascript">
								alert("No se encuentran configurados los CUM para este producto <?echo $Tipo?>");
							</script>
						<?
					}
						
			}
		?>
		<script language="javascript" src="/Funciones.js"></script>
		<script>

			parent.document.FORMA.Guardar.disabled=true;
				function Validar()
			{
				var b=0;
				
				if(document.FORMA.ValCantMax.value>0)
				{
					if((document.FORMA.Cantidad.value*1)>(document.FORMA.ValCantMax.value*1))
					{
						alert("No puede superar la cantidad de registros establecidos en la Orden de Compra!");return false;
					}
				}
				if(FORMA.Codigo.value=="" || FORMA.Nombre.value==""){alert("Falta llenar campos");b=1;}
				else{if(FORMA.Cantidad.value==""){alert("El Campo Cantidad es necesario");b=1;}
					else{if(FORMA.CC.value==""){alert("El Campo Centro de Costo es necesario");b=1;}}}
				if(parent.document.FORMA.Tipo.value=="Entradas" || parent.document.FORMA.Tipo.value=="Orden de Compra")
				{
					if((document.FORMA.Existencias.value-((document.FORMA.Cantidad.value)*-1)>document.FORMA.Maximo.value))
					{
						if(confirm("La cantidad de unidades quedara por encima del maximo permitido para este producto, desea continuar?")==false)
						{b=1;}
						else{$b=0;}
					}
				}												
				if(parent.document.FORMA.Tipo.value=="Salidas")
				{
					if((document.FORMA.Existencias.value-document.FORMA.Cantidad.value)<document.FORMA.Minimo.value)
					{
						if(confirm("La cantidad de unidades quedará por debajo del minimo permitido para este producto, desea continuar?")==true)
						{
							if((document.FORMA.Existencias.value-document.FORMA.Cantidad.value)<0)
							{
								alert("Las existencias no pueden estar por debajo de cero!");return false;					
							}
						}	
						else{b=1;}							
					}
					if((document.FORMA.ExAnuales.value-document.FORMA.Cantidad.value)<0)
					{
						alert("Las existencias Anuales no pueden estar por debajo de cero!");return false;					
					}
				}
				if(b==1)return false;
			}

			function Mostrar()
			{
				parent.document.getElementById('Busquedas').style.position='absolute';
				parent.document.getElementById('Busquedas').style.top='50px';
				parent.document.getElementById('Busquedas').style.right='10px';
				parent.document.getElementById('Busquedas').style.display='';
			}
			function Ocultar()
			{
				parent.document.getElementById('Busquedas').style.display='none';
			}
			function CalculaTotales(Objeto)
			{
				document.FORMA.TotCosto.value=document.FORMA.Cantidad.value*document.FORMA.VrCosto.value;
						document.FORMA.TotVenta.value=document.FORMA.Cantidad.value*document.FORMA.VrVenta.value;
				if(document.FORMA.TotVenta.style.visibility == "hidden")
				{
					if(document.FORMA.incluyeIVA.checked == false){document.FORMA.VrIVA.value=(document.FORMA.TotCosto.value*document.FORMA.PorcIVA.value)/100;}
					document.FORMA.VrReteFte.value=(document.FORMA.TotCosto.value*document.FORMA.PorcReteFte.value)/100;
					document.FORMA.VrICA.value=(document.FORMA.TotCosto.value*document.FORMA.PorcICA.value)/100;	
				}
				if(document.FORMA.TotCosto.value == "")
				{
					if(document.FORMA.incluyeIVA.checked == false){document.FORMA.VrIVA.value=(document.FORMA.TotVenta.value*document.FORMA.PorcIVA.value)/100; }
					document.FORMA.VrReteFte.value=(document.FORMA.TotVenta.value*document.FORMA.PorcReteFte.value)/100;
					document.FORMA.VrICA.value=(document.FORMA.TotVenta.value*document.FORMA.PorcICA.value)/100;	
			}	
				
				
				if(parent.document.FORMA.Tipo.value=="Salidas")
				{
					if(Objeto.name == "PorcIVA"){
							if(document.FORMA.incluyeIVA.checked == false){document.FORMA.VrIVA.value=(document.FORMA.TotVenta.value*document.FORMA.PorcIVA.value)/100;}}
					if(Objeto.name == "PorcReteFte"){document.FORMA.VrReteFte.value=(document.FORMA.TotVenta.value*document.FORMA.PorcReteFte.value)/100;}
					document.FORMA.VrICA.value=(document.FORMA.TotVenta.value*document.FORMA.PorcICA.value)/100;
				}	
				if(parent.document.FORMA.Tipo.value=="Entradas" || parent.document.FORMA.Tipo.value=="Orden de Compra" || parent.document.FORMA.Tipo.value=="Remisiones")
				{
					if(Objeto.name == "PorcIVA"){
							if(document.FORMA.incluyeIVA.checked == false){document.FORMA.VrIVA.value=(document.FORMA.TotCosto.value*document.FORMA.PorcIVA.value)/100;}}
					if(Objeto.name == "PorcReteFte"){document.FORMA.VrICA.value=(document.FORMA.TotCosto.value*document.FORMA.PorcICA.value)/100;}
					document.FORMA.VrReteFte.value=(document.FORMA.TotCosto.value*document.FORMA.PorcReteFte.value)/100;
				}
			}
			function OpChequeo(Chk)
			{
				if(Chk.checked == true){ document.FORMA.VrIVA.value = 0;}
				else{document.FORMA.VrIVA.value = (document.FORMA.TotCosto.value * document.FORMA.PorcIVA.value)/100;}
			}
		</script>

		<?
			$cons="Select Costo,Venta,IVA,Descto,ICA,ReteFte from Consumo.Comprobantes where Comprobante='$Comprobante' and Compania='$Compania[0]' and AlmacenPpal='$AlmacenPpal'";
			$res=ExQuery($cons);
			$fila=ExFetch($res);
			$MuestraCosto=$fila[0];$MuestraVenta=$fila[1];$MuestraIVA=$fila[2];$MuestraDescto=$fila[3];$MuestraICA=$fila[4];$MuestraReteFte=$fila[5];
			
			if($MuestraCosto=="NO"){$StyloCosto=" visibility:hidden; ";}
			if($MuestraVenta=="NO"){$StyloVenta=" visibility:hidden; ";}
			if($MuestraIVA=="NO"){$StyloIVA=" visibility:hidden; ";$DisIVA=" disabled ";}
			if($MuestraDescto=="NO"){$StyloDescto=" visibility:hidden; ";$DisDescto=" disabled ";}
			if($MuestraICA=="NO"){$StyloICA=" visibility:hidden; ";$DisICA=" disabled ";}
			if($MuestraReteFte=="NO"){$StyloReteFte=" visibility:hidden; ";$DisReteFte=" disabled ";}

			if($Editar)
			{
				$cons = "Select Codigo1,NombreProd1,Cantidad,VrCosto,TotCosto,VrVenta,TotVenta,PorcIVA,TmpMovimiento.VrIVA,PorcReteFte,TmpMovimiento.VrReteFte,PorcDescto,VrDescto,PorcICA,
				TmpMovimiento.VrICA,CentroCosto,UnidadMedida,Presentacion,DocAfectado,NoDocAfectado,IncluyeIVA,conceptortefte
				from Consumo.TmpMovimiento,Consumo.CodProductos 
				where TmpMovimiento.AutoId = CodProductos.AutoId and TMPCOD = '$TMPCOD' and TmpMovimiento.AutoId = '$AutoId'
				and CodProductos.Anio = $Anio and Compania='$Compania[0]' and AlmacenPpal='$AlmacenPpal'";
				$res = ExQuery($cons);
				$fila = ExFetch($res);
				//echo $cons; echo ExError();
				$Codigo = $fila[0]; $Nombre = "$fila[1] $fila[16] $fila[17]"; $Cantidad = $fila[2]; $VrCosto = $fila[3]; $TotCosto = $fila[4];
				$VrVenta = $fila[5]; $TotVenta = $fila[6]; $PorcIVA = $fila[7]; $VrIVA = $fila[8]; $PorcReteFte = $fila[9];
				$VrReteFte = $fila[10]; $PorcDescto = $fila[11]; $VrDescto = $fila[12]; $PorcICA = $fila[13]; $VrICA = $fila[14]; $CC = $fila[15];
				$DocAfectado=$fila[18];$NoDocAfectado=$fila[19];$ConceptoReteFte=$fila[21];
				if($fila[20]==1){$ChkII=" checked "; $VrIVA = 0;}

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
				<form name="FORMA" method="post" onsubmit="return Validar()">
				<input type="hidden" name="AlmacenPpal" value="<?echo $AlmacenPpal?>" />
				<input type="hidden" name="TMPCOD" value="<? echo $TMPCOD?>" />
				<input type="hidden" name="Numero" value="<? echo $Numero?>" />
				<input type="hidden" name="TipoMov" value="<? echo $Tipo?>" />
				<input type="hidden" name="DatNameSID" value="<? echo $DatNameSID?>" />

				<script language="javascript">
					parent.frames.Busquedas.location.href="Busquedas.php?DatNameSID=<? echo $DatNameSID?>&Editar=<? echo $Editar?>&Numero=<? echo $Numero?>&TipoMov=<? echo $Tipo?>&Tipo=CodProducto&Codigo=<?echo $Codigo?>&AlmacenPpal=<?echo $AlmacenPpal?>&Anio="+parent.document.FORMA.Anio.value+"&Fecha="+parent.document.FORMA.Anio.value+"-"+parent.document.FORMA.Mes.value+"-"+parent.document.FORMA.Dia.value+"&Tarifario="+parent.document.FORMA.Tarifario.value;
				</script>

				<?
				$ValCantMax=0;
					if($Tipo=="Entradas" || $Tipo=="Orden de Compra" || $Tipo=="Remisiones")
					{
						$CC="";$ReadCosto="";
					}
					else{$ReadCosto=" readonly ";}

					if($DocAfectado)
					{
						$ReadCosto=" readonly "; $ReadNombre=" readonly "; $ReadCodigo=" readonly "; $ReadIVA=" readonly ";

						$cons20="Select sum(Cantidad) from Consumo.Movimiento where Compania='$Compania[0]' and AlmacenPpal='$AlmacenPpal' and TipoComprobante='Entradas' and Estado='AC'
						and DocAfectado='$DocAfectado' and NoDocAfectado='$NoDocAfectado' and AutoId=$AutoId";
						$res20=ExQuery($cons20);
						$fila20=ExFetch($res20);

						$cons9="Select Cantidad from Consumo.Movimiento where AutoId=$AutoId and Comprobante='$DocAfectado' and Numero='$NoDocAfectado' and Compania='$Compania[0]'
						and AlmacenPpal='$AlmacenPpal'";
						$res9=ExQuery($cons9);
						$fila9=ExFetch($res9);
						$ValCantMax=$fila9[0]-$fila20[0];
					}
					?>
				
				
						<table width="680px" class="tabla2"    <?php echo $borderTabla2Mentor; echo $bordercolorTabla2Mentor; echo $cellspacingTabla2Mentor; echo $cellpaddingTabla2Mentor; ?>>
							<tr>
								<td class="encabezado2Horizontal" colspan="6"> NUEVO ITEM </td>
							</tr>
							<tr>
								<td class="encabezado2VerticalInvertido">C&Oacute;DIGO</td>
									<input type="Hidden" name="AutoId" value="<?echo $AutoId?>" />
								<td>
									<input type="text" name="Codigo" value="<? echo $Codigo?>" onblur="campoNumero(this);parent.document.frames.Busquedas.location.href='Busquedas.php?DatNameSID=<? echo $DatNameSID?>&Editar=<? echo $Editar?>&Numero=<? echo $Numero?>&TipoMov=<? echo $Tipo?>&Tipo=CodProducto&Codigo='+this.value+'&AlmacenPpal=<?echo $AlmacenPpal?>&Anio='+parent.document.FORMA.Anio.value+'&Fecha='+parent.document.FORMA.Anio.value+'-'+parent.document.FORMA.Mes.value+'-'+parent.document.FORMA.Dia.value+'&Tarifario='+parent.document.FORMA.Tarifario.value" onchange="parent.document.frames.Busquedas.location.href='Busquedas.php?DatNameSID=<? echo $DatNameSID?>&Editar=<? echo $Editar?>&Numero=<? echo $Numero?>&TipoMov=<? echo $Tipo?>&Tipo=CodProducto&Codigo='+this.value+'&AlmacenPpal=<?echo $AlmacenPpal?>&Anio='+parent.document.FORMA.Anio.value+'&Fecha='+parent.document.FORMA.Anio.value+'-'+parent.document.FORMA.Mes.value+'-'+parent.document.FORMA.Dia.value+'&Tarifario='+parent.document.FORMA.Tarifario.value"	onkeyup="xNumero(this)" onfocus="Ocultar();" maxlength="12" size="12" <? echo $ReadCodigo?> onKeyDown="xNumero(this)"/>
								</td>
								<td class="encabezado2VerticalInvertido">CANTIDAD</td>
								<td>
									<input onfocus="Ocultar();" type="text" name="Cantidad" value="<? echo $Cantidad?>" size="6" onchange="CalculaTotales(this)" onKeyUp="xNumero(this)" onKeyDown="xNumero(this)" onBlur="campoNumero(this)"/>
								</td>
								<td class="encabezado2VerticalInvertido">CENTRO COSTO</td>
								<td>
									<input readonly="yes" style="width:100%;" onfocus="parent.document.frames.Busquedas.location.href='Busquedas.php?DatNameSID=<? echo $DatNameSID?>&Tipo=CC&AutoId='+document.FORMA.AutoId.value + '&AlmacenPpal=<? echo $AlmacenPpal?>&Anio='+parent.document.FORMA.Anio.value+'&Mes='+parent.document.FORMA.Mes.value;Mostrar();" onkeyup="parent.document.frames.Busquedas.location.href='Busquedas.php?DatNameSID=<? echo $DatNameSID?>&Tipo=CC&AutoId='+document.FORMA.AutoId.value + '&AlmacenPpal=<? echo $AlmacenPpal?>&Anio='+parent.document.FORMA.Anio.value+'&Mes='+parent.document.FORMA.Mes.value;Mostrar();"  type="text" name="CC" value="<? echo $CC?>" size="6"/>
								</td>
							</tr>
							<tr>
								<td class="encabezado2VerticalInvertido">NOMBRE</td>
								<td colspan="5">
									<input style="width:100%;" onfocus="Mostrar();parent.document.frames.Busquedas.location.href='Busquedas.php?DatNameSID=<? echo $DatNameSID?>&Editar=<? echo $Editar?>&Numero=<? echo $Numero?>&TipoMov=<? echo $Tipo?>&Tipo=NomProducto&Nombre='+this.value+'&AlmacenPpal=<?echo $AlmacenPpal?>&Anio='+parent.document.FORMA.Anio.value+'&Fecha='+parent.document.FORMA.Anio.value+'-'+parent.document.FORMA.Mes.value+'-'+parent.document.FORMA.Dia.value+'&Tarifario='+parent.document.FORMA.Tarifario.value;" onkeyup="xLetra(this);Codigo.value='';parent.document.frames.Busquedas.location.href='Busquedas.php?DatNameSID=<? echo $DatNameSID?>&Editar=<? echo $Editar?>&Numero=<? echo $Numero?>&TipoMov=<? echo $Tipo?>&Tipo=NomProducto&Nombre='+this.value+'&AlmacenPpal=<?echo $AlmacenPpal?>&Anio='+parent.document.FORMA.Anio.value+'&Fecha='+parent.document.FORMA.Anio.value+'-'+parent.document.FORMA.Mes.value+'-'+parent.document.FORMA.Dia.value+'&Tarifario='+parent.document.FORMA.Tarifario.value;" type="text" name="Nombre" value="<? echo $Nombre?>" maxlength="100" size="80"  <? echo $ReadNombre ?> onkeydown="xLetra(this)"/>
								</td>
							</tr>
							<tr>
								<td rowspan="2" class="encabezado2VerticalInvertido">COSTO</td>
								<td class="encabezado2VerticalInvertido">VALOR</td><td>
									<input onfocus="Ocultar();" type="text" name="VrCosto" value="<? echo $VrCosto?>" maxlength="10" size="10" <? echo $ReadCosto?>	onchange="CalculaTotales(this)" style="<?echo $StyloCosto?>" onKeyUp="xNumero(this)" onKeyDown="xNumero(this)" onBlur="campoNumero(this)" />
								</td>
								<td rowspan="2" class="encabezado2VerticalInvertido" style="text-align:center;">VENTA</td>
								<td class="encabezado2VerticalInvertido">VALOR</td>
								<td>
									<input onfocus="Ocultar();" type="text" name="VrVenta" value="<? echo $VrVenta?>" size="10" readonly="yes" style="<?echo $StyloVenta?>"	onKeyUp="xNumero(this)" onKeyDown="xNumero(this)" onBlur="campoNumero(this)" />
								</td>
							</tr>
							<tr>

								<td class="encabezado2VerticalInvertido">TOTAL</td>
								<td>
									<input onfocus="Ocultar();" readonly="yes" type="text" name="TotCosto" value="<? echo $TotCosto?>"  size="10"  style="<?echo $StyloCosto?>"	onKeyUp="xNumero(this)" onKeyDown="xNumero(this)" onBlur="campoNumero(this)" />
								</td>
								<td class="encabezado2VerticalInvertido">TOTAL</td>
								<td>
									<input onfocus="Ocultar();" readonly="yes" type="text" name="TotVenta" value="<? echo $TotVenta?>" size="10"   style="<?echo $StyloVenta?>"	onKeyUp="xNumero(this)" onKeyDown="xNumero(this)" onBlur="campoNumero(this)" />
								</td>
							</tr>
							<tr>
								<td class="encabezado2VerticalInvertido">DESCUENTO</td>
								<td  style="text-align:center;" <?php if($Tipo!="Salidas"){ echo "colspan='2'";}?>>
									<input type="text" style="width:30px;" name="PorcDescto" value="<? echo $PorcDescto;?>" <? echo $ReadDto ?> <? echo $DisDescto?> 
									onchange="VrDescto.value=(TotVenta.value*this.value)/100" style="<?echo $StyloDescto?>"
									onKeyUp="xNumero(this)" onKeyDown="xNumero(this)" onBlur="campoNumero(this)">%
									<input type="text" onfocus="Ocultar();" name="VrDescto" value="<? echo $VrDescto;?>" <? echo $DisDescto ?> size="6" <? echo $ReadDto ?> 
									onchange="PorcDescto.value=0" style="<?echo $StyloDescto?>" 
									onKeyUp="xNumero(this)" onKeyDown="xNumero(this)" onBlur="campoNumero(this)"/>
								</td>
								<td class="encabezado2VerticalInvertido">
									IVA INCLUIDO 
									<input type="checkbox" name="incluyeIVA" <? echo $ChkII?> onclick="OpChequeo(this)" />
								</td>
								<td  style="text-align:center;" <?php if($Tipo!="Salidas"){ echo "colspan='2'";}?>>
									<input onfocus="Ocultar();" type="text" readonly name="VrIVA" <? echo $DisIVA ?> <? echo $ReadIVA?> value="<? echo $VrIVA?>" size="6" style="<?echo $StyloIVA?>" 
									onKeyUp="xNumero(this)" onKeyDown="xNumero(this)" onBlur="campoNumero(this)"/>
									<input onfocus="Ocultar();" type="text" name="PorcIVA" <? echo $DisIVA?> value="<? echo $PorcIVA?>" <? echo $ReadIVA?> 
									onchange="CalculaTotales(this)" size="6" style="width:30px;<?echo $StyloIVA?>" 
									onKeyUp="xNumero(this)" onKeyDown="xNumero(this)" onBlur="campoNumero(this)"/>%
								</td>
									<? if($Tipo=="Salidas"){
										?>            
										<td class="encabezado2VerticalInvertido">PAC ANUAL</td>
										<td>
											<input type="text" name="PACAnual" style=" width:40px;" readonly="readonly"  style="border:0px;" 
											onKeyUp="xNumero(this)" onKeyDown="xNumero(this)" onBlur="campoNumero(this)"/>
											<input type="text" name="PACAnualProy" style=" width:40px;" readonly="readonly"  style="border:0px;"
											onKeyUp="xNumero(this)" onKeyDown="xNumero(this)" onBlur="campoNumero(this)"/>
											<? 
									}
									
									?>
							</tr>
							<tr>
								
								<td class="encabezado2VerticalInvertido">ICA</td>
								<td  style="text-align:center;" <?php if($Tipo!="Salidas"){ echo "colspan='2'"; }?>>
									<input type="text" onfocus="Ocultar();" name="VrICA" <? echo $DisICA ?> value="<? echo $VrICA?>" <? echo $ReadICA?> size="6" style="<?echo $StyloICA?>"
									onKeyUp="xNumero(this)" onKeyDown="xNumero(this)" onBlur="campoNumero(this)" />

									<input type="text" onfocus="Ocultar();" name="PorcICA" <? echo $DisICA?> value="<? echo $PorcICA?>" <? echo $ReadICA?> size="6" 
									onchange="CalculaTotales(this)"  style="width:30px;<?echo $StyloICA?>" 
									onKeyUp="xNumero(this)" onKeyDown="xNumero(this)" onBlur="campoNumero(this)"/>%
								</td>
								<td class="encabezado2VerticalInvertido">RETEFUENTE </td>
								<td  style="text-align:center;" <?php if($Tipo!="Salidas"){ echo "colspan='2'"; }?>>
								
									<input type="text" readonly onfocus="Ocultar();" <? echo $DisReteFte?> name="VrReteFte" <? echo $ReadRte?> value="<? echo $VrReteFte?>" 
									size="6" style="<?echo $StyloReteFte?>" onKeyUp="xNumero(this)" onKeyDown="xNumero(this)" onBlur="campoNumero(this)"/>

									<input type="text" readonly="readonly" onfocus="Mostrar();parent.document.frames.Busquedas.location.href='Busquedas.php?DatNameSID=<? echo $DatNameSID?>&Tipo=Retenciones&Anio=<? echo $Anio?>'" name="PorcReteFte" value="<? echo $PorcReteFte?>" <? echo $DisReteFte?> 
									size="6" style="width:30px;<?echo $StyloReteFte ?>"/>%
								</td>
									<input type="Hidden" name="ConceptoReteFte" value="<? echo $ConceptoReteFte?>" />
									<? if($Tipo=="Salidas"){
										?><td class="encabezado2VerticalInvertido">PAC MENSUAL</td>
										<td>
											<input type="text" name="PACMensual" style=" width:40px;" readonly="readonly"  style="border:0px;" onKeyUp="xNumero(this)" onKeyDown="xNumero(this)" onBlur="campoNumero(this)"/>
											<input type="text" name="PACMensualProy" style=" width:40px;" readonly="readonly"  style="border:0px;" onKeyUp="xNumero(this)" onKeyDown="xNumero(this)" onBlur="campoNumero(this)"/>
										</td>	
										<?
									}?>

							</tr>
						</table>
						
							<input type="Hidden" name="Minimo" value="<? echo $Minimo?>" />
							<input type="Hidden" name="Maximo" value="<? echo $Maximo?>" />
							<input type="Hidden" name="Existencias" value="<? echo $Existencias?>" />
							<input type="hidden" name="ExAnuales" value="<? echo $ExAnuales?>" />
						
							<input type="Hidden" name="Comprobante" value="<? echo $Comprobante?>" />
							<input type="Hidden" name="ValCantMax" value="<? echo $ValCantMax?>" />
							<input type="hidden" name="Tipo" value="<? echo $Tipo ?>" />
							<input type="hidden" name="Editar" value="<? echo $Editar?>" />
							<input type="hidden" name="Numero" value="<? echo $Numero?>" />
							<input type="hidden" name="Anio" value="<? echo $Anio?>" />
							<div style="margin-top:15px;margin-bottom:15px;">
								<input type="submit" name="Guardar" class="boton2Envio" value="Guardar" onclick="Ocultar()" />
								<input type="button" name="Cancelar" class="boton2Envio" value="Cancelar" onclick="location.href='DetNuevoMovimientos.php?DatNameSID=<? echo $DatNameSID?>&Anio=<? echo $Anio?>&Numero=<? echo $Numero?>&AlmacenPpal=<? echo $AlmacenPpal; ?>&TMPCOD=<? echo $TMPCOD; ?>&Tipo=<? echo $Tipo?>&Comprobante=<? echo $Comprobante; ?>';Ocultar();parent.frames.TotMovimientos.location.href='TotMovimientos.php?DatNameSID=<? echo $DatNameSID?>&TMPCOD=<?echo $TMPCOD?>';" />
							</div>	
						
					</form>
			</div>
		</body>
	</html>	
		