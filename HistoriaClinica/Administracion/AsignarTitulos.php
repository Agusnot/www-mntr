		<?
			if($DatNameSID){session_name("$DatNameSID");}
			session_start();
			include("Funciones.php");
			include_once("General/Configuracion/Configuracion.php");
			if($Guardar)
			{
				if($Titulo=="")
				{
					echo "<script languaje='javascript'>alert('Por Favor Digite Titulo!');</script>";
				}
				else
				{
					$cons="Insert into HistoriaClinica.TitulosxFormato(Formato,TipoFormato,Titulo,Compania) values ('$NewFormato','$TF','$Titulo','$Compania[0]')";
					$res=ExQuery($cons,$conex);echo ExError($conex);
					$Titulo="";
				}
			}
			if($Eliminar)
			{
				$cons="Delete from HistoriaClinica.TitulosxFormato where Formato='$NewFormato' and Titulo='$Titulo' and TipoFormato='$TF' and Compania='$Compania[0]'";
				$res=ExQuery($cons,$conex);
				$Titulo="";
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
								<td class="encabezado2Horizontal" colspan="2">TITULO</td>
							</tr>
							<?
								$cons="Select * from HistoriaClinica.TitulosxFormato where Formato='$NewFormato' and TipoFormato='$TF' and Compania='$Compania[0]' order by Titulo Desc";
								$res=ExQuery($cons,$conex);
								while($fila=ExFetchArray($res))
								{
									echo "<tr><td>$fila[2]</td><td><a href='AsignarTitulos.php?DatNameSID=$DatNameSID&Eliminar=1&NewFormato=$NewFormato&Titulo=$fila[2]&TF=$TF'><img src='/Imgs/b_drop.png' border=0></a></td></tr>";
								}

							?>
							<tr>

							<form name="FORMA">
							<input type="hidden" name="DatNameSID" value="<? echo $DatNameSID?>">
							<input type="Hidden" name="NewFormato" value="<?echo $NewFormato?>">
							<input type="Hidden" name="Titulo" value="<?echo $ClasePermiso?>">
							<input type="Hidden" name="TF" value="<?echo $TF?>">
							<td><input type="text" name="Titulo" value="<? echo $Titulo?>">
							<input class="boton1Envio" type="Submit" name="Guardar" value="G"></td>

							</form>
						</table>
					</div>	
				</body>
		</html>		