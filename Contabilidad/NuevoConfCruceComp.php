		<?
			if($DatNameSID){session_name("$DatNameSID");}
			session_start();
			include("Funciones.php");
			include_once("General/Configuracion/Configuracion.php");
			$ND = getdate();
				if($Guardar){
					if(!$CuentaCru)	{
						if($Bancos)	{
							$CuentaCru = "Bancos";
						}
					}
					if(!$Editar){
						$cons = "Insert into Contabilidad.CruzarComprobantes
						(Comprobante,CruzarCon,Movimiento,Cuenta,CuentaCruzar,Compania,Anio) values
						('$Comprobante','$CruzarCon','$Movimiento','$Cuenta','$CuentaCru','$Compania[0]',$Anio)";
					}
					else{
					$cons = "Update Contabilidad.CruzarComprobantes set
					Comprobante = '$Comprobante', CruzarCon = '$CruzarCon', Movimiento = '$Movimiento', Cuenta = '$Cuenta',
					CuentaCruzar = '$CuentaCru' where Compania='$Compania[0]' and Comprobante = '$ComprobanteX' and CruzarCon = '$CruzarConX'
					and Movimiento='$MovimientoX' and Cuenta = '$CuentaX' and CuentaCruzar = '$CuentaCruX' and Anio = '$Anio'";
					}
					$res = ExQuery($cons);
					echo ExError();
					?><script language="javascript">location.href="ConfCruceComprobantes.php?DatNameSID=<? echo $DatNameSID?>&Anio=<? echo $Anio?>";</script><?
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
					function Mostrar()
					{
						document.getElementById('Busquedas').style.position='absolute';
						document.getElementById('Busquedas').style.top='50px';
						document.getElementById('Busquedas').style.right='10px';
						document.getElementById('Busquedas').style.display='';
					}
					function Ocultar()
					{
						document.getElementById('Busquedas').style.display='none';
					}
					function Validar()
					{
						var b = 0;
						if(document.FORMA.Comprobante.value==""){alert("Por Favor llene el campo Comprobante"); b=1;}
						else{if(document.FORMA.CruzarCon.value==""){alert("Por Favor llene el campo Cruzar Con"); b=1;}
							else{if(document.FORMA.Cuenta.value==""){alert("Por Favor llene el campo Cuenta"); b=1;}
								else{if(document.FORMA.CuentaCru.value==""){alert("Por Favor llene el campo Cuenta a Cruzar"); b=1;}}}}
						if(b==1){return false;}
					}
				</script>
		</head>	
		
		<body <?php echo $backgroundBodyMentor; ?>>	
				<?php
				$rutaarchivo[0] = "CONTABILIDAD";
				$rutaarchivo[1] = "CONFIGURACION";
				$rutaarchivo[2] = "COMPROBANTES";
				$rutaarchivo[3] = " CRUCE DE COMPROBANTES";	
				$rutaarchivo[4] = " NUEVO";	
				
				mostrarRutaNavegacionEstatica($rutaarchivo);
			?>
			<div <?php echo $alignDiv2Mentor; ?> class="div2">		
		
				<?
					if($Editar)
					{
						$cons = "Select Comprobante,CruzarCon,Movimiento,Cuenta,CuentaCruzar from Contabilidad.CruzarComprobantes 
						where Compania='$Compania[0]' and Comprobante = '$Comprobante' and CruzarCon = '$CruzarCon'
								and Movimiento='$Movimiento' and Cuenta='$Cuenta' and CuentaCruzar='$CuentaCruzar'
								and Anio=$Anio";
								$res = ExQuery($cons);
						$fila = ExFetch($res);
						$Comprobante = $fila[0]; $CruzarCon = $fila[1]; $Movimiento = $fila[2]; $Cuenta = $fila[3]; 
						$CuentaCru = $fila[4];
						if($fila[4]=="Bancos"){ $ChkBancos = " checked "; $disCuentaCru = " disabled ";}
						if($Movimiento=="Haber"){ $HS = " selected ";}
						else{ $DS = " selected ";}
					}
				?>
				
				<form name="FORMA" method="post" onSubmit="return Validar()">
				<input type="hidden" name="Editar" value="<? echo $Editar?>" />
				<input type="hidden" name="ComprobanteX" value="<? echo $Comprobante?>" />
				<input type="hidden" name="CruzarConX" value="<? echo $CruzarCon ?>" />
				<input type="hidden" name="MovimientoX" value="<? echo $Movimiento?>" />
				<input type="hidden" name="CuentaX" value="<? echo $Cuenta?>" />
				<input type="hidden" name="CuentaCruX" value="<? echo $CuentaCru?>" />
				<input type="hidden" name="Anio" value="<? echo $Anio?>" />
				
				<table class="tabla2"   <?php echo $borderTabla2Mentor; echo $bordercolorTabla2Mentor; echo $cellspacingTabla2Mentor; echo $cellpaddingTabla2Mentor; ?>>
					<tr>
						<td  class='encabezado2Horizontal' colspan="4" >NUEVO CRUCE DE COMPROBANTES PARA EL A&Ntilde;O <? echo $Anio?></td>
					</tr>
					<tr>
						<td class="encabezado2VerticalInvertido">COMPROBANTE:</td>
						<td colspan="3"><input type="text" name="Comprobante" value="<? echo $Comprobante?>" style="width:100%" 
						onFocus="Mostrar();frames.Busquedas.location.href='Busquedas.php?DatNameSID=<? echo $DatNameSID?>&ObjComprobante=Comprobante&Tipo=Comprobante&Comprobante='+this.value" 
						onkeyup="xLetra(this);frames.Busquedas.location.href='Busquedas.php?DatNameSID=<? echo $DatNameSID?>&Tipo=Comprobante&Comprobante='+this.value;"
						onKeyDown="xLetra(this)"/></td>
					</tr>
					<tr>
						<td class="encabezado2VerticalInvertido">CRUZAR CON :</td>
						<td colspan="3"><input type="text" name="CruzarCon" value="<? echo $CruzarCon?>" style="width:100%" 
						onFocus="Mostrar();frames.Busquedas.location.href='Busquedas.php?DatNameSID=<? echo $DatNameSID?>&ObjComprobante=CruzarCon&Tipo=Comprobante&Comprobante='+this.value;" 
						onkeyup="xLetra(this);frames.Busquedas.location.href='Busquedas.php?DatNameSID=<? echo $DatNameSID?>&ObjComprobante=CruzarCon&Tipo=Comprobante&Comprobante='+this.value;"
						onKeyDown="xLetra(this)"/></td>
					</tr>
					<tr>
						<td class="encabezado2VerticalInvertido" colspan="3" >MOVIMIENTO: </td>
						<td><select name="Movimiento" style="width:100%">
							<option <? echo $HS ?> value="Haber" >HABER</option>
							<option <? echo $DS ?> value="Debe" >DEBE</option>
						</select></td>
					</tr>
					<tr>
						<td class="encabezado2VerticalInvertido">CUENTA:</td>
						<td><input type="text" name="Cuenta" value="<? echo $Cuenta?>"
						onFocus="Mostrar();frames.Busquedas.location.href='Busquedas.php?DatNameSID=<? echo $DatNameSID?>&NoMovimiento=1&ObjCuenta=Cuenta&Tipo=PlanCuentas&Cuenta='+this.value+'&Anio=<? echo $Anio?>'" 
						onkeyup="xNumero(this);frames.Busquedas.location.href='Busquedas.php?DatNameSID=<? echo $DatNameSID?>&NoMovimiento=1&ObjCuenta=Cuenta&Tipo=PlanCuentas&Cuenta='+this.value+'&Anio=<? echo $Anio?>';"
						onKeyDown="xNumero(this)" onBlur="campoNumero(this)"  /></td>
						<td class="encabezado2VerticalInvertido">CUENTA A CRUZAR:</td>
						<td><input type="text" name="CuentaCru" value="<? echo $CuentaCru?>" <? echo $disCuentaCru ?>
						onFocus="Mostrar();frames.Busquedas.location.href='Busquedas.php?DatNameSID=<? echo $DatNameSID?>&NoMovimiento=1&ObjCuenta=CuentaCru&Tipo=PlanCuentas&Cuenta='+this.value+'&Anio=<? echo $Anio ?>'" 
						onkeyup="xNumero(this);frames.Busquedas.location.href='Busquedas.php?DatNameSID=<? echo $DatNameSID?>&NoMovimiento=1&ObjCuenta=CuentaCru&Tipo=PlanCuentas&Cuenta='+this.value+'&Anio=<? echo $Anio ?>';"
						onKeyDown="xNumero(this)" onBlur="campoNumero(this)" /></td>
					</tr>
					<tr>
						<td colspan="4" class="encabezado2VerticalInvertido">BANCOS
							<input type="checkbox" name="Bancos" <? echo $ChkBancos ?> onClick="if(this.checked==true){FORMA.CuentaCru.value='Bancos';FORMA.CuentaCru.disabled=true;}
							else{FORMA.CuentaCru.value='';FORMA.CuentaCru.disabled=false;};">
						</td>
					</tr>
				</table>
				<input type="hidden" name="DatNameSID" value="<? echo $DatNameSID?>">
				<input type="submit" class="boton2Envio" name="Guardar" value="Guardar" />
				<input type="button"  class="boton2Envio" name="Cancelar" value="Cancelar" onClick="location.href='ConfCruceComprobantes.php?DatNameSID=<? echo $DatNameSID?>&Anio=<? echo $Anio?>'" />
				</form>
				<iframe id="Busquedas" name="Busquedas" style="display:none;" src="Busquedas.php?DatNameSID=<? echo $DatNameSID?>&" frameborder="0" height="400"></iframe>
			</div>
		</body>
	</html>	