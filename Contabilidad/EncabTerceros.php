<?
	if($DatNameSID){session_name("$DatNameSID");}
	session_start();
	include("Funciones.php");
	include_once("General/Configuracion/Configuracion.php");
	$ND=getdate();
	if(!$TipoTercero){$TipoTercero="Persona Natural";}
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
					$rutaarchivo[1] = "GESTION DE TERCEROS";	
						
					mostrarRutaNavegacionEstatica($rutaarchivo);
					
					
				?>
			<div <?php echo $alignDiv2Mentor; ?> class="div2">
				<form name="FORMA" action="ListaTerceros.php?DatNameSID=<? echo $DatNameSID?>" target="Abajo">
				
					<table class="tabla2" style="text-align:center;" <?php echo $borderTabla2Mentor ; echo $bordercolorTabla2Mentor ; echo $cellspacingTabla2Mentor ; echo $cellpaddingTabla2Mentor; ?> width="100%">
						<tr>
							<td class="encabezado2Horizontal">BUSCAR POR</td>
							<td class="encabezado2Horizontal">IDENTIFICACI&Oacute;N</td>
								<? if($TipoTercero=="Persona Juridica"){ 
									?> 
									<td class="encabezado2Horizontal">RAZ&Oacute;N SOCIAL </td> <? 
								} 

								if($TipoTercero=="Persona Natural"){
									?>
									<td class="encabezado2Horizontal">PRIMER APELLIDO</td>
									<td class="encabezado2Horizontal">SEGUNDO APELLIDO</td>
									<td class="encabezado2Horizontal">PRIMER NOMBRE</td>
									<td class="encabezado2Horizontal">SEGUNDO NOMBRE</td>
									<?
								}?>
							<td  class="encabezado2Horizontal" colspan="3">&nbsp;</td>
						</tr>
						<tr>
							<td>
								<select name="TipoTercero" onChange="location.href='EncabTerceros.php?DatNameSID=<? echo $DatNameSID?>&TipoTercero='+this.value+'&ModOrigen=<?echo $ModOrigen?>'">
								<?
									$cons="Select Tipo from Central.TiposPersonas";
									$res=ExQuery($cons);
									while($fila=ExFetch($res))
									{
										if($TipoTercero==$fila[0]){echo "<option selected value='$fila[0]'>$fila[0]</option>";}
										else{echo "<option value='$fila[0]'>$fila[0]</option>";}
									}
								?>
								</select>
							</td>
							<td>
								<input type="Text" name="Identificacion" style="width:100%;"></td>
								<? if($TipoTercero=="Persona Juridica"){?><td><input type="Text" name="PrimApe" style="width:100%;"></td><?}?>
								<? if($TipoTercero=="Persona Natural"){?>
							<td><input type="Text" name="PrimApe" style="width:100%;"></td>
							<td><input type="Text" name="SegApe" style="width:100%;"></td>
							<td><input type="Text" name="PrimNom" style="width:100%;"></td>
							<td><input type="Text" name="SegNom" style="width:100%;"></td>

						<? }?>


						<td> <input type="Submit" class="boton2Envio" value="Buscar"></td>
						<input type='Hidden' name='ModOrigen' value="<? echo $ModOrigen?>">
						<input type="hidden" name="DatNameSID" value="<? echo $DatNameSID?>">
						<td><input type="Button" class="boton2Envio" value="Nuevo" <? if($ModOrigen == "Consumo"||$ModOrigen=="ICALiq"){ echo " disabled ";}?>onClick="parent(1).location.href='NuevoTercero.php?DatNameSID=<? echo $DatNameSID?>&ModOrigen=<? echo $ModOrigen?>'"></td>
				</tr>
				</table>
				</form>
				<hr>
			</div>	
		</body>	