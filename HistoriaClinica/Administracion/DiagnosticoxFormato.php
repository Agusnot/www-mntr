		<?
			if($DatNameSID){session_name("$DatNameSID");}
			session_start();
			include("Funciones.php");
			include_once("General/Configuracion/Configuracion.php");
			if($Eliminar)
			{
				$cons="delete from historiaclinica.DxPermitidosxFormato where compania='$Compania[0]' 
				and Formato='$NewFormato' and TipoFormato='$TF' and dxformat='$DxElim'";
				$res=ExQuery($cons);
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
						<td colspan="3" style="text-align:center;">
							<input type="button" class="boton2Envio" value="Nuevo" 	onclick="location.href='NewDxPermitxFormato.php?DatNameSID=<? echo $DatNameSID?>&NewFormato=<? echo $NewFormato?>&TF=<? echo $TF?>'"/>
							<input type="button"  class="boton2Envio" value="Volver" onClick="location.href='/HistoriaClinica/Administracion/ItemsxFormato.php?DatNameSID=<? echo $DatNameSID?>&NewFormato=<? echo $NewFormato?>&TF=<? echo $TF?>'">
						</td>
					</tr>
					<tr>
						<td class="encabezado2Horizontal">C&Oacute;DIGO</td>
						<td class="encabezado2Horizontal">NOMBRE</td>
						<td class="encabezado2Horizontal">&nbsp;</td>
					</tr>
				<?	$cons="select diagnostico,codigo from salud.cie,historiaclinica.DxPermitidosxFormato where compania='$Compania[0]' 
					and Formato='$NewFormato' and TipoFormato='$TF' and dxformat=codigo order by codigo";
					$res=ExQuery($cons);
					while($fila=ExFetch($res))
					{?>
						<tr>
							<td><? echo $fila[1]?></td><td><? echo $fila[0]?></td>
							<td><img src="/Imgs/b_drop.png" title="Eliminar" style="cursor:hand" onclick="if(confirm('Esta seguro de eliminar este registro?')){location.href='DiagnosticoxFormato.php?DatNameSID=<? echo $DatNameSID?>&NewFormato=<? echo $NewFormato?>&TF=<? echo $TF?>&Eliminar=1&DxElim=<? echo $fila[1]?>';}"/></td>
						</tr>		
				<?	}?>        
				</table>
			</div>	
		</body>  
	</html>	