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
				<link rel="stylesheet" type="text/css" href="../../General/Estilos/estilos.css">
			</head>

		<body <?php echo $backgroundBodyMentor; ?>>
			<div <?php echo $alignDiv3Mentor; ?> class="div3">		
				<form name="FORMA" method="post" onSubmit="return Validar()">
					<?
					if($Codigo || $Nombre || $Grupo || $Tipo){ 
						$cons="select codigo,grupo from contratacionsalud.gruposservicio where compania='$Compania[0]'";
						$res=ExQuery($cons);
						while($fila=ExFetch($res)){
							$Grupos[$fila[0]]=$fila[1];
						}
						$cons="select codigo,tipo from contratacionsalud.tiposservicio where compania='$Compania[0]'";
						$res=ExQuery($cons);
						while($fila=ExFetch($res)){
							$Tipos[$fila[0]]=$fila[1]; //echo "Tipos[$fila[0]]=".$Tipos[$fila[0]]."<br>";
						}
						$Nombre = str_replace(" ", "%", $Nombre);
						if($Codigo){$Cod=" and codigo ilike '$Codigo%'";}
						if($Nombre){$Nom=" and nombre ilike '$Nombre%'";}
						if($Grupo){$Grup=" and grupo='$Grupo'";}
						if($Tipo){$Tip=" and tipo='$Tipo'";}
						$cons="select codigo,nombre,grupo,tipo from contratacionsalud.cups where cups.compania='$Compania[0]' $Cod $Nom $Grup $Tip";		
						$res=ExQuery($cons);?>
						<table class="tabla2" style="margin-top:25px;margin-bottom:25px;"    <?php echo $borderTabla2Mentor; echo $bordercolorTabla2Mentor; echo $cellspacingTabla2Mentor; echo $cellpaddingTabla2Mentor; ?>>	  
							<tr>
								<td class="encabezado2Horizontal">C&Oacute;DIGO</td>
								<td class="encabezado2Horizontal">NOMBRE</td>
								<td class="encabezado2Horizontal">GRUPO</td>
								<td class="encabezado2Horizontal">TIPO</td>
							</tr>        
						<?	while($fila=ExFetch($res)){ ?>
								<tr>
									<td><? echo $fila[0]?></td><td><? echo $fila[1]?></td><td><? echo $Grupos[$fila[2]]?>&nbsp;</td><td><? echo $Tipos[$fila[3]]?>&nbsp;</td>
								</tr>
						<?	}?>
						</table>
					<?
					}?>
					<input type="hidden" name="DatNameSID" value="<? echo $DatNameSID?>">
				</form>
			</div>	
		</body>
	</html>
