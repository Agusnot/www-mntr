<?
	if($DatNameSID){session_name("$DatNameSID");}
	session_start();
	include("Funciones.php");
	include_once("General/Configuracion/Configuracion.php");
	$lev=error_reporting ('Err');
	if($Antes)	{
		$cons="Select * from Central.Anios where Compania='$Compania[0]' Order By Anio Asc";
		$res=ExQuery($cons);
		$fila=ExFetch($res);
		$Anio=$fila[0]-1;
		$cons2="Insert into Central.Anios(Anio,Compania) values ($Anio,'$Compania[0]')";
		$res2=ExQuery($cons2);
	}

	if($Despues){
		$cons="Select * from Central.Anios where Compania='$Compania[0]' Order By Anio Desc";
		$res=ExQuery($cons);
		$fila=ExFetch($res);
		$Anio=$fila[0]+1;
		$cons2="Insert into Central.Anios(Anio,Compania) values ($Anio,'$Compania[0]')";
		$res2=ExQuery($cons2);
	}

	if($Elimina){
		$cons="Delete from Central.Anios where Compania='$Compania[0]' and Anio=$Anio";
		$res=ExQuery($cons);
	}
	$cons="Select * from Central.Anios where Compania='$Compania[0]' Order By Anio";
	$res=ExQuery($cons);
	
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
			<?php mostrarRutaNavegacion($_SERVER['PHP_SELF']);	?>
			
				<div <?php echo $alignDiv1Mentor; ?> class="div1"> 
					<table class="tabla1" width="250px"  <?php echo $borderTabla1Mentor; echo $bordercolorTabla1Mentor; echo $cellspacingTabla1Mentor;?> cellpadding="3px">
						<tr>
							<td class="boton2Envio" style= "cursor:hand; text-align:center;" colspan="2" onClick="location.href='GestionaAnios.php?DatNameSID=<? echo $DatNameSID ?>&Antes=1'"  >
								Insertar
							</td>
						</tr>
						<?	
						while($fila=ExFetch($res)){
							echo "<tr>";
								echo "<td style='text-align: center;'>$fila[0]</td>";
								echo "<td align='center'>";
									echo "<a href='GestionaAnios.php?DatNameSID=$DatNameSID&Elimina=1&Anio=$fila[0]'>";
										echo "<img border=0 src='/Imgs/b_drop.png'>";
									echo "</a>";
								echo "</td>";
							
							echo "</tr>";
						}
						?>
						<tr>
							<td  class="boton2Envio" style="cursor:hand; text-align:center;"  colspan="2" onClick="location.href='GestionaAnios.php?DatNameSID=<? echo $DatNameSID ?>&Despues=1'"align="center">
								Insertar
							</td>
						</tr>
				</table>
			</div>	
		</body>	
	</html>