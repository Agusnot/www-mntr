		<?
			if($DatNameSID){session_name("$DatNameSID");}
			session_start();
			include("Funciones.php");
			include_once("General/Configuracion/Configuracion.php");
			$ND = getdate();
			if($Guardar)
			{
				if(!$Editar)
				{
					$cons = "Insert into Contabilidad.ConceptosAfectacion
					(Comprobante,Concepto,Cuenta,CuentaBase,Opera,Compania,Anio) values
					('$Comprobante','$Concepto','$CuentaDestino','$CuentaBase','$Movimiento','$Compania[0]',$Anio)";
				}
				else
				{
					$cons = "Update Contabilidad.ConceptosAfectacion set
					Comprobante = '$Comprobante', Concepto = '$Concepto', Cuenta = '$CuentaDestino', CuentaBase = '$CuentaBase',
					Opera = '$Movimiento' where Compania='$Compania[0]' and Comprobante = '$ComprobanteX' and Concepto = '$ConceptoX'
					and Opera='$OperaX' and CuentaBase = '$CuentaBaseX' and Cuenta = '$CuentaDestinoX' and Anio = '$Anio'";
				}
				$res = ExQuery($cons);
				echo ExError();
				//echo $cons;exit;
				?><script language="javascript">location.href="ConfConceptosAfectacion.php?DatNameSID=<? echo $DatNameSID?>&Anio=<? echo $Anio?>";</script><?
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
						if(document.FORMA.Comprobante.value==""){alert("Seleccione un comprobante");return false;}
						if(document.FORMA.Concepto.value==""){alert("Escriba un concepto");return false;}
						if(document.FORMA.Movimiento.value==""){alert("Seleccione un movimiento");return false;}
						if(document.FORMA.CuentaDestino.value==""){alert("Seleccione una cuenta de destino");return false;}
						if(document.FORMA.CuentaBase.value==""){alert("Seleccione una cuenta de base");return false;}
						if(document.FORMA.ValCuentaDestino.value=="0" || document.FORMA.ValCuentaDestino.value == ""){alert("Seleccione una cuenta destino de la lista");return false;}
						if(document.FORMA.ValCuentaBase.value=="0" || document.FORMA.ValCuentaBase.value == ""){alert("Seleccione una cuenta base de la lista");return false;}
					}
				</script>
		</head>
		
			<body <?php echo $backgroundBodyMentor; ?>>	
				<?php
				$rutaarchivo[0] = "CONTABILIDAD";
				$rutaarchivo[1] = "CONFIGURACION";
				$rutaarchivo[2] = "COMPROBANTES";
				$rutaarchivo[3] = "CONCEPTOS DE AFECTACION";					
				$rutaarchivo[4] = "NUEVO";
				
				mostrarRutaNavegacionEstatica($rutaarchivo);
			?>
			<div <?php echo $alignDiv2Mentor; ?> class="div2">
				
				<?
					if($Editar)
					{
						$cons = "Select Comprobante,Concepto,Cuenta,CuentaBase,Opera from Contabilidad.ConceptosAfectacion
						where Compania='$Compania[0]' and Comprobante = '$Comprobante' and Concepto = '$Concepto' and Anio=$Anio";
						//echo $cons;
						$res = ExQuery($cons);
						$fila = ExFetch($res);
						$Comprobante = $fila[0]; $Concepto = $fila[1]; $Cuenta = $fila[2]; $CuentaBase = $fila[3]; $Opera = $fila[4];
						$ValCuentaBase = 1; $ValCuentaDestino = 1;
					}
				?>
				
				<form name="FORMA" method="post" onSubmit="return Validar()">
				<input type="hidden" name="Editar" value="<? echo $Editar?>" />
				<input type="hidden" name="ComprobanteX" value="<? echo $Comprobante?>" />
				<input type="hidden" name="ConceptoX" value="<? echo $Concepto ?>" />
				<input type="hidden" name="CuentaDestinoX" value="<? echo $Cuenta?>" />
				<input type="hidden" name="CuentaBaseX" value="<? echo $CuentaBase?>" />
				<input type="hidden" name="OperaX" value="<? echo $Opera?>" />
				<input type="hidden" name="AnioX" value="<? echo $Anio?>" />
				
				
				<table class="tabla2"   <?php echo $borderTabla2Mentor; echo $bordercolorTabla2Mentor; echo $cellspacingTabla2Mentor; echo $cellpaddingTabla2Mentor; ?>>
					<tr>
						<td class='encabezado2Horizontal' colspan="4" >CONCEPTO DE AFECTACI&Oacute;N A&Ntilde;O <? echo $Anio?></td>
					</tr>
					<tr>
						<td class="encabezado2VerticalInvertido">COMPROBANTE:</td>
						<td colspan="3"><input type="text" name="Comprobante" value="<? echo $Comprobante?>" style="width:100%" 
						onFocus="Mostrar();frames.Busquedas.location.href='Busquedas.php?DatNameSID=<? echo $DatNameSID?>&ObjComprobante=Comprobante&Tipo=Comprobante&Comprobante='+this.value" 
						onkeyup="xLetra(this);frames.Busquedas.location.href='Busquedas.php?DatNameSID=<? echo $DatNameSID?>&Tipo=Comprobante&Comprobante='+this.value;"
						onKeyDown="xLetra(this)"/></td>
					</tr>
					<tr>
						<td class="encabezado2VerticalInvertido">CONCEPTO:</td>
						<td colspan="3"><input type="text" name="Concepto" value="<? echo $Concepto?>" style="width:100%"  onFocus="Ocultar()" 
						onkeyup="xLetra(this);" onKeyDown="xLetra(this)"/></td>
					</tr>
					<tr>
						<td class="encabezado2VerticalInvertido" colspan="3" >OPERACI&Oacute;N: </td>
						<td><select name="Movimiento" style="width:100%">
							<option <? echo $Suma ?> value="+" >Suma (+)</option>
							<option <? echo $Resta ?> value="-" >Resta (-)</option>
						</select></td>
					</tr>
					<tr>
						<td class="encabezado2VerticalInvertido">CUENTA DESTINO:</td>
						<td><input type="text" name="CuentaDestino" value="<? echo $Cuenta?>"
						onFocus="Mostrar();frames.Busquedas.location.href='Busquedas.php?DatNameSID=<? echo $DatNameSID?>&Objeto=CuentaDestino&Tipo=PlanCuentasDetalle&Cuenta='+this.value+'&Anio=<? echo $Anio?>'" 
						onkeyup="ValCuentaDestino.value=0;xNumero(this);frames.Busquedas.location.href='Busquedas.php?DatNameSID=<? echo $DatNameSID?>&Objeto=CuentaDestino&Tipo=PlanCuentasDetalle&Cuenta='+this.value+'&Anio=<? echo $Anio?>';"
						onKeyDown="ValCuentaDestino.value=0;xNumero(this)" onBlur="campoNumero(this)"  /></td>
						<td class="encabezado2VerticalInvertido">CUENTA BASE:</td>

						<input type="hidden" name="ValCuentaBase" value="<? echo $ValCuentaBase?>">
						<input type="hidden" name="ValCuentaDestino" value="<? echo $ValCuentaDestino?>">
						<td><input type="text" name="CuentaBase" value="<? echo $CuentaBase?>"
						onFocus="Mostrar();frames.Busquedas.location.href='Busquedas.php?DatNameSID=<? echo $DatNameSID?>&Objeto=CuentaBase&Tipo=PlanCuentasDetalle&Cuenta='+this.value+'&Anio=<? echo $Anio ?>'" 
						onkeyup="ValCuentaBase.value=0;xNumero(this);frames.Busquedas.location.href='Busquedas.php?DatNameSID=<? echo $DatNameSID?>&Objeto=CuentaBase&Tipo=PlanCuentasDetalle&Cuenta='+this.value+'&Anio=<? echo $Anio ?>';"
						onKeyDown="ValCuentaBase.value=0;xNumero(this)" onBlur="campoNumero(this)"  /></td>
					</tr>
				</table>
				<input type="hidden" name="DatNameSID" value="<? echo $DatNameSID?>">
				<input type="submit" class="boton2Envio" name="Guardar" value="Guardar" />
				<input type="button" class="boton2Envio" name="Cancelar" value="Cancelar" onClick="location.href='ConfConceptosAfectacion.php?DatNameSID=<? echo $DatNameSID?>&Anio=<? echo $Anio?>'" />
				</form>
				<iframe id="Busquedas" name="Busquedas" style="display:none;" src="Busquedas.php?DatNameSID=<? echo $DatNameSID?>" frameborder="0" height="400"></iframe>
				</body>