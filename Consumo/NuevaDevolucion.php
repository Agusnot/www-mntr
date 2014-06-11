		<? 
			if($DatNameSID){session_name("$DatNameSID");}
			session_start();
			include("Funciones.php");
			include("Consumo/ObtenerSaldos.php");
			include_once("General/Configuracion/Configuracion.php");
			$ND=getdate();
			$FechaIni="$Anio-01-01";
			$FechaFin="$Anio-$Mes-$ND[mday]";
			$VrSaldoIni=SaldosIniciales($Anio,$AlmacenPpal,$FechaIni);
			$VrEntradas=Entradas($Anio,$AlmacenPpal,$FechaIni,$FechaFin);
			$VrSalidas=Salidas($Anio,$AlmacenPpal,$FechaIni,$FechaFin);
			//if(!$NumDevolucion){$NumDevolucion="$Anio-$Mes-$ND[mday] $ND[hours]:$ND[minutes]:$ND[seconds]";}
			if(!$TMPCOD){$TMPCOD=strtotime("$ND[year]-$ND[mon]-$ND[mday] $ND[hours]:$ND[minutes]:$ND[seconds]").rand(1,9999);}
			if($Guardar){


				//TIPO COMPROBANTE
					$cons = "Select * from Consumo.TiposComprobante Where Tipo='Devoluciones'";
					$res = ExQuery($cons);
					if(ExNumRows($res)==0)
					{
						$cons1 = "Insert into Consumo.TiposComprobante values ('Devoluciones')";
						$res1 = ExQuery($cons1);
					}
					//REVISAR COMPROBANTE DE DEVOLUCIONES
				$cons = "Select * from Consumo.Comprobantes where Compania='$Compania[0]' 
					and AlmacenPpal='$AlmacenPpal' and Comprobante='Devoluciones' and Tipo='Devoluciones'";
				$res = ExQuery($cons);
				if(ExNumRows($res)>0){$Nuevo=0;}
				else{$Nuevo=1;}
				if($Nuevo==1)
				{
					//INSERTAR COMPROBANTE DE DEVOLUCIONES
					$cons = "Insert into Consumo.Comprobantes (Compania,AlmacenPpal,Comprobante,Tipo)
					values('$Compania[0]','$AlmacenPpal','Devoluciones','Devoluciones')";
					$res = ExQuery($cons);
				}
				//Grupos por AutoId
				$cons = "Select AutoId,Grupo from Consumo.CodProductos Where Compania='$Compania[0]' and AlmacenPpal='$AlmacenPpal' and Anio=$Anio Group by AutoId,Grupo";
				$res = ExQuery($cons);
				while($fila = ExFetch($res)){$Grupo[$fila[0]]=$fila[1];}
				//INSERTAR LA DEVOLUCION EN CONSUMO.MOVIMIENTO
				$cons = "Select AutoId,Cantidad,DocAfectado,NoDocAfectado,CentroCosto,NumServicio from Consumo.TmpMovimiento Where TMPCOD='$TMPCOD'";
				$res = ExQuery($cons);
				while($fila = ExFetch($res))
				{
					$CantFinal=$VrSaldoIni[$fila[0]][0]+$VrEntradas[$fila[0]][0]-$VrSalidas[$fila[0]][0];
					$SaldoFinal=$VrSaldoIni[$fila[0]][1]+$VrEntradas[$fila[0]][1]-$VrSalidas[$fila[0]][1];
					if($CantFinal==0){$VrCosto=0;}
					if(!$fila[5]){$fila[5]=0;}
			//        else{
					//$VrCosto = $SaldoFinal/$CantFinal;
					
					$cons_ = "Select NoDocAfectado 
					from Consumo.CodProductos,Consumo.TmpMovimiento Where Compania='$Compania[0]' and AlmacenPpal='$AlmacenPpal' and Anio=$Anio
					and CodProductos.AutoId = TmpMovimiento.AutoId and TMPCOD='$TMPCOD'";
					$res_ = ExQuery($cons_);
					$fila_ = ExFetch($res_);
					
					$cons__ = "SELECT vrcosto,cum,numero FROM Consumo.Movimiento 
					WHERE compania='$Compania[0]' AND AlmacenPpal='$AlmacenPpal' AND TipoComprobante='Salidas' 
					AND Numero='$fila_[0]'
					AND cedula='$Cedula' AND autoid='$fila[0]' AND estado='AC'";
					$res__ = ExQuery($cons__);
					$fila__ = ExFetch($res__);
					$VrCosto=$fila__[0];
					$cum=$fila__[1];
			//		}
					$TotCosto = $VrCosto * $fila[1];
					if(!$VrCosto){$VrCosto=0;}
					if(!$TotCosto){$TotCosto=0;}
					if($Conse<10)$z=0; else $z='';
					if($Mes<10)$m=0; else $m='';
					
					$consD = "SELECT numero FROM Consumo.Movimiento 
					WHERE compania='$Compania[0]' and comprobante='Devoluciones' order by numero::integer Desc limit 1";
					$resD = ExQuery($consD);
					$filaD = ExFetch($resD);
					$Conse=substr($filaD[0],6,5);
					
					if(!$NumDevolucion)$NumDevolucion=$Anio."".$m."".$Mes."".($Conse+1);
					$cons1 = "Insert into Consumo.Movimiento
					(Compania,AlmacenPpal,Fecha,Comprobante,TipoComprobante,Numero,Cedula,Detalle,
					AutoId,usuariocre,fechacre,estado,Cantidad,
					VrCosto,TotCosto,DocAfectado,NoDocAfectado,Anio,Grupo,CentroCosto,NumServicio,motivodevolucion,cum) values
					('$Compania[0]','$AlmacenPpal','$Anio-$Mes-$ND[mday]','Devoluciones','Devoluciones','$NumDevolucion','$Cedula','Devolucion de Salidas',
					$fila[0],'$usuario[0]','$ND[year]-$ND[mon]-$ND[mday] $ND[hours]:$ND[minutes]:$ND[seconds]','AC',$fila[1],
					$VrCosto,$TotCosto,'$fila[2]','$fila[3]',$Anio,'".$Grupo[$fila[0]]."','".$fila[4]."',".$fila[5].",'".$MotivoDevo."','".$cum."')";
					//echo $cons1;
					$res1 = ExQuery($cons1);
				}
			?>
			<script language="javascript">
				location.href="ListaDevoluciones.php?DatNameSID=<? echo $DatNameSID?>&Anio=<? echo $Anio?>&Mes=<? echo $Mes?>&AlmacenPpal=<? echo $AlmacenPpal?>";
			</script>
			<?
		}
		if($Eliminar){
			while(list($cad,$val)=each($Eliminar)){
				$valores = explode("|",$cad);
				$cons = "Delete from Consumo.TmpMovimiento Where TMPCOD='$TMPCOD' and AutoId = $valores[0]
				and Cantidad = $valores[1] and DocAfectado = '$valores[2]' and NoDocAfectado='$valores[3]'";
				$res = ExQuery($cons);
			}
		}
		?>
<script language="JavaScript" src="/Funciones.js"></script>
<script language="JavaScript">
	function Mostrar()
	{
		document.getElementById('Busquedas').style.position='absolute';
		document.getElementById('Busquedas').style.top='20px';
		document.getElementById('Busquedas').style.right='10px';
		document.getElementById('Busquedas').style.display='';
	}
	function Ocultar()
	{
		document.getElementById('Busquedas').style.display='none';
	}
        function CargarSalidas(Cedula,AlmacenPpal,Anio,Numero)
	{   
	    frames.FrameOpener.location.href="VerSalidas.php?TMPCOD=<? echo $TMPCOD?>&DatNameSID=<? echo $DatNameSID?>&Cedula="+Cedula+"&AlmacenPpal=<? echo $AlmacenPpal?>&Anio=<? echo $Anio?>&Mes=<? echo $Mes?>&MotivoDevo=<? echo $MotivoDevo?>&Numero="+Numero;
		document.getElementById('FrameOpener').style.position='absolute';
		document.getElementById('FrameOpener').style.top='50px';
		document.getElementById('FrameOpener').style.left='15px';
		document.getElementById('FrameOpener').style.display='';
		document.getElementById('FrameOpener').style.width='690';
		document.getElementById('FrameOpener').style.height='300';
	}
        function CargarProductosxSalida(Comprobante,Numero,AlmacenPpal,Cedula)
	{
		frames.FrameOpener.location.href="ProductosxSalida.php?TMPCOD=<? echo $TMPCOD?>&Mes=<? echo $Mes?>&Anio=<? echo $Anio?>&DatNameSID=<? echo $DatNameSID?>&Cedula="+Cedula+"&Comprobante="+Comprobante+"&Numero="+Numero+"&AlmacenPpal="+AlmacenPpal;
		document.getElementById('FrameOpener').style.position='absolute';
		document.getElementById('FrameOpener').style.top='50px';
		document.getElementById('FrameOpener').style.left='15px';
		document.getElementById('FrameOpener').style.display='';
		document.getElementById('FrameOpener').style.width='690';
		document.getElementById('FrameOpener').style.height='300';
	}
	function CargarNuevaDevolucion(Cedula,AlmacenPpal,Anio,Numero)
	{   
	    frames.FrameOpener.location.href="NuevaDevolucion.php?TMPCOD=<? echo $TMPCOD?>&DatNameSID=<? echo $DatNameSID?>&Cedula="+Cedula+"&AlmacenPpal=<? echo $AlmacenPpal?>&Anio=<? echo $Anio?>&Mes=<? echo $Mes?>&MotivoDevo=<? echo $MotivoDevo?>&Numero="+Numero;
		document.getElementById('FrameOpener').style.position='absolute';
		document.getElementById('FrameOpener').style.top='50px';
		document.getElementById('FrameOpener').style.left='15px';
		document.getElementById('FrameOpener').style.display='';
		document.getElementById('FrameOpener').style.width='690';
		document.getElementById('FrameOpener').style.height='300';
	}
	function Valida(Cedula){
	if(document.FORMA.Tercero.value==""){alert("Debe seleccionar un Tercero!!!");return false;}
	if(document.FORMA.Cedula.value==""){alert("Debe seleccionar un Número de Cédula!!!");return false;}
	if(document.FORMA.MotivoDevo.value==""){alert("Debe seleccionar un Motivo de Devolucion!!!");return false;}
    if(document.FORMA.Tercero.value!=""&&document.FORMA.Cedula.value!=""&&document.FORMA.MotivoDevo.value!="")
	   Ocultar();CargarSalidas(Cedula,'<? echo $AlmacenPpal?>','<? echo $Anio?>');
}
</script>
<?
	if($ValoresE){
		$Valores = explode("|",$ValoresE);
		$cons = "Select AutoId,Cantidad,DocAfectado,NoDocAfectado from Consumo.Movimiento Where Compania='$Compania[0]' and AlmacenPpal='$AlmacenPpal'
		and Fecha = '$Valores[0]' and Numero = '$Valores[1]' and Cedula = '$Valores[2]' and Comprobante='Devoluciones'";
		$res = ExQuery($cons);
		while($fila = ExFetch($res))
		{
			$cons1 = "Insert into Consumo.TmpMovimiento (TMPCOD,AutoId,Cantidad,DocAfectado,NoDocAfectado)
			values ('$TMPCOD',$fila[0],$fila[1],'$fila[2]','$fila[3]')";
			$res1 = ExQuery($cons1);
		}
		$NumDevoluciones = $Valores[1];
		$Cedula = $Valores[2];
		$Tercero = $Valores[3];
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
				<div <?php echo $alignDiv2Mentor; ?> class="div2">
					<form name="FORMA" method="post">
						<input type="hidden" name="AlmacenPpal" value="<? echo $AlmacenPpal?>" />
						<input type="hidden" name="Anio" value="<? echo $Anio?>" />
						<input type="hidden" name="DatNameSID" value="<? echo $DatNameSID?>" />
						<input type="hidden" name="TMPCOD" value="<? echo $TMPCOD?>" />
						<input type="hidden" name="NumDevoluciones" value="<? echo $NumDevoluciones?>" />
						
						<table   class="tabla2" width="600px"  <?php echo $borderTabla2Mentor; echo $bordercolorTabla2Mentor; echo $cellspacingTabla2Mentor; echo $cellpaddingTabla2Mentor; ?>>
							<tr>
								<td colspan="2" class="encabezado2Horizontal">DEVOLUCI&Oacute;N</td>
							</tr>
							<tr>
								<td class="encabezado2VerticalInvertido">TERCERO</td>
								<td>
									<input type="Text" name="Tercero" value="<? echo $Tercero?>" style="width:100%;" onKeyUp="xLetra(this);if(this.readOnly==false){Mostrar();};Cedula.value='';frames.Busquedas.location.href='Busquedas.php?DatNameSID=<? echo $DatNameSID?>&Tipo=Nombre&Nombre='+this.value" onKeyDown="xLetra(this)"/>
								</td>
							</tr>
							<tr>
								<td class="encabezado2VerticalInvertido">IDENTIFICACI&Oacute;N</td>
								<td>
									<input type="Text" value="<? echo $Cedula?>" style="width:100%;" name="Cedula"	onchange="frames.Busquedas.location.href='Busquedas.php?DatNameSID=<? echo $DatNameSID?>&Tipo=Cedula&Cedula='+this.value" onKeyUp="xLetra(this)" onKeyDown="xLetra(this)">
								</td>
							</tr>
							<tr>
							<td class="encabezado2VerticalInvertido">MOTIVO DEVOLUCI&Oacute;N</td>
							<td>
								<?
								if($AlmacenPpal=="FARMACIA"){
									?>	
									<select name="MotivoDevo">
										<option value="">&nbsp;</option>
										<option value="CAMBIO DE SERVICIO" <?if($MotivoDevo=="CAMBIO DE SERVICIO"){?>selected<?}?>>Cambio de servicio</option>
										<option value="PACIENTE MUERTO" <?if($MotivoDevo=="CAMBIO DE VIA DE ADMINISTRACION"){?>selected<?}?>>Cambio de via de administraci&oacute;n</option>
										<option value="PACIENTE MUERTO" <?if($MotivoDevo=="DEVOLUCION POR AREA"){?>selected<?}?>>Devoluci&oacute;n por area</option>
										<option value="PACIENTE MUERTO" <?if($MotivoDevo=="INEFECTIVIDAD DE TRATAMIENTO"){?>selected<?}?>>Inefectividad de tratamiento</option>
										<option value="LA FAMILIA TRAE EL MEDICAMENTO" <?if($MotivoDevo=="LA FAMILIA TRAE EL MEDICAMENTO"){?>selected<?}?>>La familia trae el medicamento</option>
										<option value="PACIENTE MUERTO" <?if($MotivoDevo=="EL MEDICO AUMENTO LA DOSIS"){?>selected<?}?>>El m&eacute;dico aument&oacute; la dosis</option>
										<option value="EL MEDICO BAJO LA DOSIS" <?if($MotivoDevo=="EL MEDICO DISMINUYO LA DOSIS"){?>selected<?}?>>El m&eacute;dico disminuy&oacute; la dosis</option>
										<option value="EL MEDICO SUPRIMIO EL MEDICAMENTO" <?if($MotivoDevo=="EL MEDICO SUPRIMIO EL MEDICAMENTO"){?>selected<?}?>>El m&eacute;dico suprimi&oacute; el medicamento</option>
										<option value="PACIENTE MUERTO" <?if($MotivoDevo=="PACIENTE MUERTO"){?>selected<?}?>>Paciente muerto</option>
										<option value="LA FAMILIA TRAE EL MEDICAMENTO" <?if($MotivoDevo=="PACIENTE REMITIDO"){?>selected<?}?>>Paciente remitido</option>
										<option value="EL PACIENTE TIENE SALIDA" <?if($MotivoDevo=="PACIENTE TIENE SALIDA"){?>selected<?}?>>Paciente tiene salida</option>
										<option value="PACIENTE MUERTO" <?if($MotivoDevo=="REACCION ADVERSA AL MEDICAMENTO"){?>selected<?}?>>Reacci&oacute;n adversa al medicamento</option>
										<option value="PACIENTE MUERTO" <?if($MotivoDevo=="RESISTENCIA AL MEDICAMENTO"){?>selected<?}?>>Resistencia al medicamento</option>
										<option value="PACIENTE MUERTO" <?if($MotivoDevo=="OTRA"){?>selected<?}?>>Otra</option>
									</select>
									<?
								}
								else{
									?>
									<select name="MotivoDevo">
										<option value="">&nbsp;</option>
										<option value="EJEMPLO 1" <?if($MotivoDevo=="EJEMPLO 1"){?>selected<?}?>>Ejemplo 1</option>
										<option value="EJEMPLO 2" <?if($MotivoDevo=="EJEMPLO 2"){?>selected<?}?>>Ejemplo 2</option>
										<option value="EJEMPLO 3" <?if($MotivoDevo=="EJEMPLO 3"){?>selected<?}?>>Ejemplo 3</option>
										<option value="EJEMPLO 4" <?if($MotivoDevo=="EJEMPLO 4"){?>selected<?}?>>Ejemplo 4</option>
										<option value="EJEMPLO 5" <?if($MotivoDevo=="EJEMPLO 5"){?>selected<?}?>>Ejemplo 5</option>
									</select>
									<?
								}?>
							</td>
							</tr>
							<tr>
								<td colspan="4" style="text-align:center;">
									<input type="button" name="cargarSalida" class="boton2Envio" value="Cargar Salidas" title="Cargar Salidas" onClick="Valida(Cedula.value);"/>
								</td>
							</tr>
						</table>
						<br /><br />
						<?
						if($Cedula)
						{
							$cons = "Select TmpMovimiento.AutoId,NombreProd1,UnidadMedida,Presentacion,Cantidad,DocAfectado,NoDocAfectado 
							from Consumo.CodProductos,Consumo.TmpMovimiento Where Compania='$Compania[0]' and AlmacenPpal='$AlmacenPpal' and Anio=$Anio
							and CodProductos.AutoId = TmpMovimiento.AutoId and TMPCOD='$TMPCOD'";
							$res = ExQuery($cons);
							if(ExNumRows($res)>0)
							{
							?>
							<script language="javascript">
								document.FORMA.Tercero.readOnly = true;
								document.FORMA.Cedula.readOnly = true;
							</script>
							<table border="1" bordercolor="#e5e5e5" style='font : normal normal small-caps 11px Tahoma;' width="700">
								<tr bgcolor="#e5e5e5" style="font-weight:bold">
									<td>Producto</td><td>Cantidad</td><td>Doc Afectado</td><td>Numero</td>
								</tr>
								<?
								while($fila=ExFetch($res))
								{
								?>
									<tr>
										<td><? echo "$fila[1] $fila[2] $fila[3]"?></td>
										<td><? echo $fila[4]?></td>
										<td><? echo $fila[5]?></td>
										<td><? echo $fila[6]?></td>
										<td width="16px"><button type="submit" name="Eliminar[<? echo "$fila[0]|$fila[4]|$fila[5]|$fila[6]";?>]" 
										title="Eliminar" style="border:#FFFFFF; cursor:hand" onClick="return (confirm('Desea Eliminar este registro'))">
										<img src="/Imgs/b_drop.png"/></button></td>
									</tr>
								<?
								}
								?>
							</table>
							<div style="width:700" align="center">
								<input type="submit" name="Guardar" value="Ejecutar devolucion" 
									   onclick ="this.style.visibility = 'hidden';
									   FORMA.Cancelar.style.visibility = 'hidden'"/>
								<input type="button" name="Cancelar" value="Cancelar" 
								onclick="location.href='ListaDevoluciones.php?DatNameSID=<? echo $DatNameSID?>&AlmacenPpal=<? echo $AlmacenPpal?>&AnioI=<? echo $Anio?>&MesI=<? echo $Mes?>'" />
							</div>
							<?
							}
							else
							{
							?>
							<script language="javascript">
								document.FORMA.Tercero.readOnly = false;
								document.FORMA.Cedula.readOnly = false;
							</script>
							<?
							}
						}
						else
						{
						?><div  align="center">
								<input type="button" name="Cancelar" class="boton2Envio" value="Cancelar" onclick="location.href='ListaDevoluciones.php?DatNameSID=<? echo $DatNameSID?>&AlmacenPpal=<? echo $AlmacenPpal?>&AnioI=<? echo $Anio?>&MesI=<? echo $Mes?>'" />
						  </div><?
						}
						?>
					</form>
					<iframe id="Busquedas" name="Busquedas" style="display:none;" src="Busquedas.php" frameborder="0" height="400"></iframe>
					<iframe id="FrameOpener" name="FrameOpener" style="display:none" frameborder="0" height="1" style="border:#e5e5e5 ridge" ></iframe>
				</div>	
			</body>
		</html>	