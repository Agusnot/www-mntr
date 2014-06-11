<?
	if($DatNameSID){session_name("$DatNameSID");}
	session_start();
	include("Funciones.php");
	include_once("General/Configuracion/Configuracion.php");
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
			<form name="FORMA">
				<table width="100%"  class="tabla2" style="text-align:center; padding:0px;"  cellpadding="0" cellspacing="0" <?php echo $borderTabla2Mentor ; echo $bordercolorTabla2Mentor ;?>>
					<tr>
						<td class="encabezado2Horizontal"  height="25px">DESCRIPCI&Oacute;N</td>
						<td class="encabezado2Horizontal"  height="25px">SALDO</td>
						<td class="encabezado2Horizontal"  height="25px">TOTAL DEBITOS</td>
						<td class="encabezado2Horizontal"  height="25px">TOTAL CREDITOS</td>
						<td class="encabezado2Horizontal"  height="25px">DIFERENCIA</td>
					</tr>
					<tr>
				<td >
				<input type="Text" name="Descripcion" style="width:200px;" readonly="yes">
				</td>
				<td>
				<input type="Text" name="Saldo" style="width:100px;" readonly="yes">
				</td>
				<td>
				<input type="Text" name="TotDebitos" style="width:100px;text-align:right" readonly="yes">
				</td>
				<td>
				<input type="Text" name="TotCreditos" style="width:100px;;text-align:right" readonly="yes">
				</td>
				<td>
				<input type="Text" name="Diferencia" style="width:100px;;text-align:right" readonly="yes">
				</td>
				</tr>
				</table>
			</form>