		<?
			if($DatNameSID){session_name("$DatNameSID");}
			session_start();
			include("Funciones.php");
			include_once("General/Configuracion/Configuracion.php");
			$ND=getdate();
			if(!$Anio){$Anio=$ND[year];}
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
				$rutaarchivo[2] = "CENTROS DE COSTO";
				mostrarRutaNavegacionEstatica($rutaarchivo);
				
				?>
				<form name="FORMA" method="post">
					<table class="tabla2" style="margin-top:0px;"    <?php echo $borderTabla2Mentor; echo $bordercolorTabla2Mentor; echo $cellspacingTabla2Mentor; echo $cellpaddingTabla2Mentor; ?>>
						<tr>
							<td class='encabezado2Horizontal' >
								SELECCI&Oacute;N PERIODO
									<select name="Anio" onChange="document.FORMA.submit();">
									<option></option>

									<?
										$cons="Select Anio from Central.Anios where Compania='$Compania[0]' order by Anio desc";
										$res=ExQuery($cons);
										while($fila=ExFetch($res))
										{
											if($Anio==$fila[0]){echo "<option selected value='$fila[0]'>$fila[0]</option>";}
											else{echo "<option value='$fila[0]'>$fila[0]</option>";}
										}
									?>
									</select>
							</td>
							<td rowspan="3" valign="middle">
								<iframe name="Movimiento" src="ConfMovxCC.php?DatNameSID=<? echo $DatNameSID?>&Anio=<? echo $Anio?>" frameborder="0" style="width:400px; height:100%"></iframe>
							</td>
						</tr>
						<tr>
							<td>
								<iframe name="Detalle" src="ConfEncxCC.php?DatNameSID=<? echo $DatNameSID?>&Anio=<? echo $Anio?>" frameborder="0" width="400px;"></iframe>
							</td>
						</tr>
						<tr>
							<td>
								<iframe name="Lista" src="ConfListxCC.php?DatNameSID=<? echo $DatNameSID?>&Anio=<? echo $Anio?>" frameborder="0" style="width:400px; height:280px"></iframe>
							</td>
						</tr>
						<input type="hidden" name="DatNameSID" value="<? echo $DatNameSID?>">
				</form>
		</body>
</html>
