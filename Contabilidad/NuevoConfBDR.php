		<?
			if($DatNameSID){session_name("$DatNameSID");}
			session_start();
			include("Funciones.php");
			include_once("General/Configuracion/Configuracion.php");
			
			if($Guardar){
				if($Consumo=="on"){$Consumo=1;}else{$Consumo=0;}
				if(!$Editar)
				{
					$cons = "Insert into Contabilidad.BasesRetencion 
					(Compania,Concepto,Porcentaje,Base,Cuenta,MontoMinimo,IVA,Anio,TipoRetencion,Consumo) values
					('$Compania[0]','$Concepto',$Porc,$Base,'$Cuenta',$MontoMin,$IVA,$Anio,'$TipoRetencion',$Consumo)";
				}
				else
				{
					$cons = "Update Contabilidad.BasesRetencion set Concepto = '$Concepto', Porcentaje = $Porc,
					Base = $Base, Cuenta = '$Cuenta', MontoMinimo = '$MontoMin', IVA = $IVA,Consumo=$Consumo, TipoRetencion = '$TipoRetencion' 
					where Concepto = '$ConceptoX' and Anio = '$Anio' and Compania = '$Compania[0]'";
				}
				echo ExError();
				$res = ExQuery($cons);
				?><script language="javascript">location.href="ConfBDRetencion.php?DatNameSID=<? echo $DatNameSID?>&Anio=<? echo $Anio;?>";</script><?
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
					if(document.FORMA.ValCuenta.value=="0"){alert("Seleccione una cuenta de la lista!!");return false;}
					if (document.FORMA.Concepto.value == ""){alert("Por favor, llene el campo Concepto");b = 1;}
					else{if(document.FORMA.Porc.value == ""){alert("Por favor, llene el campo Porcentaje");b = 1;}
						else{if(document.FORMA.Base.value == ""){alert("Por favor, llene el campo Base");b = 1;}
							else{if(document.FORMA.Cuenta.value == ""){alert("Por favor, llene el campo Cuenta");b = 1;}
								else{if(document.FORMA.MontoMin.value == ""){alert("Por favor, llene el campo Monto Minimo");b = 1;}
									else{if(document.FORMA.IVA.value == ""){alert("Por favor, llene el campo IVA");b = 1;}}}}}}
					if(b!=0){ return false;}
				}
			</script>
		</head>

		<body <?php echo $backgroundBodyMentor; ?>>	
			<?php
				$rutaarchivo[0] = "CONTABILIDAD";
				$rutaarchivo[1] = "CONFIGURACION";
				$rutaarchivo[2] = "CUENTAS CONTABLES";
				$rutaarchivo[3] = "BASES DE RETENCION";											
				$rutaarchivo[4] = "NUEVO";	
				mostrarRutaNavegacionEstatica($rutaarchivo);
			?>
			<div <?php echo $alignDiv2Mentor; ?> class="div2">
					<form name="FORMA" method="post" onSubmit="return Validar()">
						<input type="hidden" name="Anio" value="<? echo $Anio?>" />
						<input type="hidden" name="ConceptoX" value="<? echo $Concepto ?>" />
						<input type="hidden" name="Editar" value="<? echo $Editar?>"  />
						<?
							if($Editar)	{
								$cons1 = "Select Concepto,TipoRetencion,Porcentaje,Base,Cuenta,MontoMinimo,Iva,Consumo 
								from Contabilidad.BasesRetencion where Compania = '$Compania[0]' and Anio = '$Anio' and Concepto = '$Concepto'
								and TipoRetencion = '$TipoRetencion'";
								$res1 = ExQuery($cons1);
								$fila1 = ExFetch($res1);
								$Concepto = $fila1[0]; $TipoRetencion = $fila1[1]; $Porc = $fila1[2]; $Base = $fila1[3];
								$Cuenta = $fila1[4]; $MontoMin = $fila1[5]; $IVA = $fila1[6];$ValidaCuenta=1;$xConsumo=$fila1[7];
							}
						?>
					<table class="tabla2"   <?php echo $borderTabla2Mentor; echo $bordercolorTabla2Mentor; echo $cellspacingTabla2Mentor; echo $cellpaddingTabla2Mentor; ?>>
						<tr>
							<td class='encabezado2Horizontal' colspan="4">NUEVA BASE DE RETENCI&Oacute;N A&Ntilde;O <? echo $Anio?></td>
						</tr>
						<tr>
							<td class='encabezado2VerticalInvertido'>TIPO DE RETENCI&Oacute;N:</td>
							<td colspan="3"><select name="TipoRetencion" style="width:100%" onFocus="Ocultar();">
							<?
								$cons = "Select Tipo from Contabilidad.TiposRetencion where Compania = '$Compania[0]'";
								$res = ExQuery($cons);
								while($fila = ExFetch($res))
								{
									if($TipoRetencion == $fila[0]){ echo "<option selected value='$fila[0]'>$fila[0]</option>";}
									else {echo "<option value='$fila[0]'>$fila[0]</option>";}
								}	
							?>
							</select></td>
						</tr>
						<tr>
							<td class='encabezado2VerticalInvertido'>CONCEPTO:</td>
							<td colspan="3"><input type="text" name="Concepto" value="<? echo $Concepto?>" style="width:100%"  onfocus="Ocultar();" 
							onKeyUp="xLetra(this)" onKeyDown="xLetra(this)"/></td>
						</tr>
						<tr>
							<td class='encabezado2VerticalInvertido'>PORCENTAJE:</td>
							<td><input type="text" name="Porc" value="<? echo $Porc?>" onFocus="Ocultar();" maxlength="5" size="6"
							onKeyUp="xNumero(this)" onKeyDown="xNumero(this)" onBlur="campoNumero(this)" /></td>
							<td class='encabezado2VerticalInvertido'>BASE:</td>
							<td><input type="text" name="Base" value="<? echo $Base?>" onFocus="Ocultar();" maxlength="6" size="4"
							onKeyUp="xNumero(this)" onKeyDown="xNumero(this)" onBlur="campoNumero(this)" /></td>
						</tr>
						<tr>
							<td class='encabezado2VerticalInvertido' colspan="3" >CUENTA:</td>
							<td><input type="text" name="Cuenta" id="Cuenta" value="<? echo $Cuenta?>"
							onFocus="Mostrar();frames.Busquedas.location.href='Busquedas.php?DatNameSID=<? echo $DatNameSID?>&Tipo=PlanCuentasDetalle&Objeto=Cuenta&Cuenta='+this.value+'&Anio=<? echo $Anio?>';" 
							onkeyup="xNumero(this);frames.Busquedas.location.href='Busquedas.php?DatNameSID=<? echo $DatNameSID?>&Tipo=PlanCuentasDetalle&Objeto=Cuenta&Cuenta='+this.value+'&Anio=<? echo $Anio?>';ValCuenta.value=0"
							onKeyDown="xNumero(this)" onBlur="campoNumero(this)"  /></td>
						</tr>
						<tr>
							<td class='encabezado2VerticalInvertido'>MONTO M&Iacute;NIMO:</td>
							<td><input type="text" name="MontoMin" value="<? echo $MontoMin?>" onFocus="Ocultar();" maxlength="10" size="11"
							onKeyUp="xNumero(this)" onKeyDown="xNumero(this)" onBlur="campoNumero(this)"/></td>
							<td class='encabezado2VerticalInvertido'>IVA: </td>
							<td><input type="text" name="IVA" value="<? echo $IVA?>" onFocus="Ocultar();" maxlength="5" size="6"
							onKeyUp="xNumero(this)" onKeyDown="xNumero(this)" onBlur="campoNumero(this)"/></td>
						</tr>
						<tr><td class='encabezado2Horizontal' colspan="4" >AFECTACIONES EN L&Iacute;NEA</td></tr>
						<tr><td class='encabezado2VerticalInvertido' colspan="4">CONSUMO 
						<? if($xConsumo==1){?>
						<input type="checkbox" name="Consumo" checked>
						<? }else{?>
						<input type="checkbox" name="Consumo">
						<? }?>
						</td></tr>
					</table>
				<input type="hidden" name="DatNameSID" value="<? echo $DatNameSID?>">
				<input type="submit" class="boton2Envio" name="Guardar" value="Guardar" />
				<input type="Hidden" name="ValCuenta" value="<? echo $ValCuenta?>">
				<input type="button"  class="boton2Envio" name="Cancelar" value="Cancelar" onClick="location.href='ConfBDRetencion.php?DatNameSID=<? echo $DatNameSID?>&Anio=<? echo $Anio?>'" />
				</form>
				<iframe id="Busquedas" name="Busquedas" style="display:none;" src="" frameborder="0" height="400"></iframe>
			</div>	
		</body>
	</html>