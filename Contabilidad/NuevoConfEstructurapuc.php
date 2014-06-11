		<?
			if($DatNameSID){session_name("$DatNameSID");}
			session_start();
			include ("Funciones.php");
			include_once("General/Configuracion/Configuracion.php");
			
			if(!$Nivel)	{
				$cons = "Select Nivel from Contabilidad.EstructuraPuc where compania='$Compania[0]' and Anio='$Anio' order by Nivel desc";
				$res = ExQuery($cons);
				$fila = ExFetch($res);
				echo ExError();
				$Nivel = $fila[0] + 1;
			}
			
			if($Guardar){
				if(!$Editar)
				{
					$cons="Insert into Contabilidad.EstructuraPuc(NoCaracteres,Detalle,Nivel,Compania,Anio) 
					values ('$NoCaracteres','$Detalle','$Nivel','$Compania[0]','$Anio')";		   
				}
				else
				{
					$cons = "Update Contabilidad.EstructuraPuc set NoCaracteres = '$NoCaracteres', Detalle = '$Detalle' where
					Compania = '$Compania[0]' and Anio = '$Anio' and Nivel = '$Nivel'";
				}
				$res=ExQuery($cons);
				echo ExError($res);
				?><script language="javascript">location.href='ConfEstructuraPUC.php?DatNameSID=<? echo $DatNameSID?>&Anio=<? echo $Anio?>';</script><?
			}
		?>
		
		<script language="javascript" src="/Funciones.js"></script>
		<script language="javascript">
			function Validar()
			{
				if (document.FORMA.NoCaracteres.value == ""){alert("Ingrese un Numero de Caracteres");return false;}
				else{if (document.FORMA.Detalle.value == ""){alert("Ingrese el Detalle");return false;}
					 else{return true}}	
			}
		</script>
		<? 
			if($Editar)	{
				$cons = "Select NoCaracteres,Detalle,Nivel from Contabilidad.EstructuraPuc where compania='$Compania[0]' and Anio='$Anio' and Nivel = '$Nivel'";
				$res = ExQuery($cons);
				$fila = ExFetch($res);
				$NoCaracteres = $fila[0]; $Detalle = $fila[1]; $Nivel = $fila[2];
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
				$rutaarchivo[1] = "CONFIGURACION";
				$rutaarchivo[2] = "CUENTAS CONTABLES";
				$rutaarchivo[3] = "ESTRUCTURA PLAN DE CUENTAS";
				$rutaarchivo[4] = "NUEVO";
										
				mostrarRutaNavegacionEstatica($rutaarchivo);
			?>
			<div <?php echo $alignDiv1Mentor; ?> class="div1">	
			
				<form name="FORMA" method="post" onSubmit="return Validar()">
					<input type="hidden" name="Anio" value="<? echo $Anio?>" />
					<input type="hidden" name="Editar" value="<? echo $Editar?>" />
				   <table class="tabla1"   <?php echo $borderTabla1Mentor; echo $bordercolorTabla1Mentor; echo $cellspacingTabla1Mentor; echo $cellpaddingTabla1Mentor; ?>>
						<tr>
							<td class='encabezado2Horizontal' colspan="2">ESTRUCTURA PLAN DE CUENTAS A&Ntilde;O <? echo $Anio?></td>
						</tr>
						<tr>
						<td class="encabezado2VerticalInvertido">NO. DE CARACTERES</td>
						<td><input type="text" name="NoCaracteres" value="<? echo $NoCaracteres?>" maxlength="2" size="2" onkeyup="xNumero(this)" onKeyDown="xNumero(this)" onBlur="campoNumero(this)" /></td>
					</tr>
					<tr>
						<td class="encabezado2VerticalInvertido">DETALLE</td>
						<td><input type="text" name="Detalle" value="<? echo $Detalle?>" 
						onkeyup="xLetra(this)" onKeyDown="xLetra(this)"/></td>
					</tr>
					<tr>
						<td class="encabezado2VerticalInvertido">NIVEL</td>
						<td><input type="text" name="Nivel" value="<? echo $Nivel?>" readonly maxlength="3" size="3" /></td>
					</tr>
					</table>
					<input type="hidden" name="DatNameSID" value="<? echo $DatNameSID?>">
				<input type="submit" name="Guardar" class="boton2Envio" value="Guardar" />
				<input type="button" name="Cancelar" class="boton2Envio" value="Cancelar" onClick="location.href='ConfEstructuraPUC.php?DatNameSID=<? echo $DatNameSID?>&Anio=<? echo $Anio?>'"  />
				</form>
			</body>