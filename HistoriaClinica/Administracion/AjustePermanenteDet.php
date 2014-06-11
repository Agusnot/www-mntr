		<?
			if($DatNameSID){session_name("$DatNameSID");}
			session_start();
			include("Funciones.php");
			include_once("General/Configuracion/Configuracion.php");
			if($Eliminar)
			{
				$cons="Delete from HistoriaClinica.AjustePermanenteDet where Formato='$NewFormato' and TipoFormato='$TF' and Item='$Item' and Perfil='$Perfil' and Compania='$Compania[0]'";
				$res=ExQuery($cons);
			}
			//--
			if($Guardar)
			{
				$cons="Insert into HistoriaClinica.AjustePermanenteDet(Item,Formato,TipoFormato,Perfil,Compania) values ('$Item','$NewFormato','$TF','$Perfil','$Compania[0]')";
				$res=ExQuery($cons);
				echo ExError($conex);
			}	
		?>
		
		<html>
				<head>
					<?php echo $codificacionMentor; ?>
					<?php echo $autorMentor; ?>
					<?php echo $titleMentor; ?>
					<?php echo $iconMentor; ?>
					<?php echo $shortcutIconMentor; ?>
					<link rel="stylesheet" type="text/css" href="../../General/Estilos/estilos.css">	

				</head>
				
			<body <?php echo $backgroundBodyMentor; ?>>
				<div <?php echo $alignDiv2Mentor; ?> class="div2">
					<table class="tabla2"  <?php echo $borderTabla2Mentor; echo $bordercolorTabla2Mentor; echo $cellspacingTabla2Mentor; echo $cellpaddingTabla2Mentor; ?>>
						<tr>
							<td class="encabezado2Horizontal">ITEM</td>
							<td class="encabezado2Horizontal">&nbsp;</td>
						</tr>
					<?
						$cons="Select ajustepermanentedet.item, ajustepermanentedet.perfil,ajustepermanentedet.formato,ajustepermanentedet.tipoformato from HistoriaClinica.AjustePermanenteDet,HistoriaClinica.AjustePermanente where AjustePermanente.Perfil=AjustePermanenteDet.Perfil 
						and AjustePermanenteDet.Perfil='$Perfil'
						and ajustepermanente.formato=ajustepermanentedet.formato
						and AjustePermanenteDet.Formato='$NewFormato' 
						and ajustepermanente.tipoformato=ajustepermanentedet.tipoformato
						and AjustePermanenteDet.TipoFormato='$TF' and AjustePermanenteDet.Compania='$Compania[0]' and ajustepermanente.compania=ajustepermanentedet.compania group by ajustepermanentedet.perfil,ajustepermanentedet.formato,ajustepermanentedet.tipoformato,ajustepermanentedet.item";	
						$res=ExQuery($cons);
						//echo $cons;
						while($fila=ExFetchArray($res))
						{
							echo "<tr><td>" . $fila['item'] . "</td>";
							echo "<td><a href='AjustePermanenteDet.php?DatNameSID=$DatNameSID&Eliminar=1&Item=".$fila['item']."&TF=$TF&NewFormato=$NewFormato&Perfil=$Perfil&TipoPermiso=$TipoPermiso'><img src='/Imgs/b_drop.png' border=0></a></td></tr>";
							$consAdc=$consAdc . "'" . $fila['item'] . "'" . " and Item !=";
						}

						if(!$consAdc){$consAdc="1=1";}
						else
						{
							$consAdc=" Item !=" . $consAdc;
							$consAdc=substr($consAdc,1,strlen($consAdc)-12);
						}
					?>
					<tr>
					<td>
					<form name="FORMA" >
					<input type="Hidden" name="DatNameSID" value="<? echo $DatNameSID?>">
					<select name="Item" id="Item">
					<?

						$cons="Select Item from HistoriaClinica.ItemsxFormatos where Formato='$NewFormato' and TipoFormato='$TF' and Compania='$Compania[0]' and SubFormato=0 and titulo Is Null and $consAdc Order By Item";
						$res=ExQuery($cons,$conex);echo ExError($conex);
						while($fila=ExFetch($res))
						{
							echo "<option value='$fila[0]'>$fila[0]</option>";
						}

					?>
					</select></td>
					<input type="Hidden" name="NewFormato" value="<? echo $NewFormato?>">
					<input type="Hidden" name="Perfil" value="<? echo $Perfil?>">
					<input type="Hidden" name="TF" value="<? echo $TF?>">
					<input type="hidden" name="ClasePermiso" value="<? echo $ClasePermiso?>">
					<?	if(ExNumRows($res)>0){?>
					<td><input type="Submit" name="Guardar" class="boton1Envio" value="G"></td><? }?>
					</table>
					<input type="button" name="Regresar"  class="boton2Envio"  value="Regresar" onClick="location.href='AjustePermanente.php?DatNameSID=<? echo $DatNameSID?>&NewFormato=<? echo $NewFormato?>&TF=<? echo $TF?>&ClasePermiso=<? echo $ClasePermiso?>'">
					</form>
				</div>
			</body>
		</html>		