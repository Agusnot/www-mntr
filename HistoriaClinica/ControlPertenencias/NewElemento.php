		<?php
			if($DatNameSID){session_name("$DatNameSID");}
			session_start();
			include("Funciones.php");
			include_once("General/Configuracion/Configuracion.php");	
			if($Guardar){
				if(!$Edit){
					$cons="insert into salud.elementos (elemento,compania) values ('$NewElemento','$Compania[0]')";		
				}
				else{
					$cons="update salud.elementos set elemento='$NewElemento' where compania='$Compania[0]' and elemento='$ElemtoAnt'";
				}
				$res=ExQuery($cons);
				?><script language="javascript">location.href='Elementos.php?DatNameSID=<? echo $DatNameSID?>';</script><?
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
				<script language='javascript' src="/Funciones.js"></script>
				<script language="javascript">
					function validar()
					{
						if(document.FORMA.NewElemento.value==""){
							alert("Debe digitar el nuevo elemento");return false;
						}
					}
				</script>
			</head>

		<body <?php echo $backgroundBodyMentor; ?>>
			<?php
					$rutaarchivo[0] = "HISTORIA CL&Iacute;NICA";
					$rutaarchivo[1] = "HOSPITALIZACI&Oacute;N";
					$rutaarchivo[2] = "CONTROL DE PERTENENCIAS";
					$rutaarchivo[3] = "ELEMENTOS";
					$rutaarchivo[4] = "NUEVO ELEMENTO";
					mostrarRutaNavegacionEstatica($rutaarchivo);
			?>
			<div <?php echo $alignDiv1Mentor; ?> class="div1">	
				<form name="FORMA" method="post" onSubmit="return validar()">
					<table class="tabla1"  width="250px;"  <?php echo $bordertabla1Mentor; echo $bordercolortabla1Mentor; echo $cellspacingtabla1Mentor; echo $cellpaddingtabla1Mentor; ?>>	
						<tr>
							<td  class='encabezado2Horizontal' colspan="4">NUEVO ELEMENTO</td>
						</tr>
						<tr>
							<td style="text-align:center;">
								<input type="text" style="width:95%;" onKeyDown="xLetra(this)" onKeyUp="xLetra(this)" name="NewElemento" value="<? echo $Elemento?>">
							</td>
						</tr>
						<tr>
							<td style="text-align:center;">
								<input type="submit" class="boton2Envio" name="Guardar" value="Guardar">
								<input type="button"  class="boton2Envio" value="Cancelar" onClick="location.href='Elementos.php?DatNameSID=<? echo $DatNameSID?>'">
							</td>
						</tr>
					</table>
				<input type="hidden" name="ElemtoAnt" value="<? echo $Elemento?>">
				<input type="hidden" name="Edit" value="<? echo $Edit?>">
				<input type="Hidden" name="DatNameSID" value="<? echo $DatNameSID?>">
				</form>
			</div>
		</body>
	</html>
