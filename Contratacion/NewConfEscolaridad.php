		<?	if($DatNameSID){session_name("$DatNameSID");}
			session_start();
			include("Funciones.php");
			include_once("General/Configuracion/Configuracion.php");
			if($Guardar){
				if(!$Edit){
					$cons="select id from historiaclinica.escolaridad where compania='$Compania[0]' order by id desc";
					$res=ExQuery($cons);
					$fila=ExFetch($res);
					$AutoId=$fila[0]+1;
					$cons="insert into historiaclinica.escolaridad(compania,escolaridad,id) values ('$Compania[0]','$escolaridad','$AutoId')";
					//echo $cons;
				}
				else{
					$cons="update historiaclinica.escolaridad set escolaridad='$escolaridad',id=$Id where compania='$Compania[0]' and escolaridad='$escolaridadAnt' and id=$IdAnt";
					//echo $cons;			
				}		
				$res=ExQuery($cons);?>
				<script language="javascript">
				location.href='ConfEscolaridad.php?DatNameSID=<? echo $DatNameSID?>';
				</script><?		
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
				<script language='javascript' src="/Funciones.js"></script>
				<script language="javascript">
				function validar(){
					if(document.FORMA.Interprograma.value==""){
						alert("Debe digitar un nivel de escolaridad!!!");return false;
					}
				}
				function evitarSubmit(evento){
					if(document.all){ tecla = evento.keyCode;}
					else{ tecla = evento.which;}
					return(tecla != 13);
				}
				function Pasar(evento,proxCampo){
					if(evento.keyCode == 13){document.getElementById(proxCampo).focus();}
				}

			</script>
			</head>
		<body <?php echo $backgroundBodyMentor; ?>>
				<?php
					$rutaarchivo[0] = "HISTORIA CL&Iacute;NICA";
					$rutaarchivo[1] = "CONFIGURACI&Oacute;N";
					$rutaarchivo[2] = "NIVEL DE ESCOLARIDAD";									
					$rutaarchivo[3] = "NUEVO";
									
					mostrarRutaNavegacionEstatica($rutaarchivo);
					
				?>
			<div <?php echo $alignDiv2Mentor; ?> class="div2">
				<form name="FORMA" method="post" onSubmit="return validar()">
					<table class="tabla2"  <?php echo $borderTabla2Mentor; echo $bordercolorTabla2Mentor; echo $cellspacingTabla2Mentor; echo $cellpaddingTabla2Mentor; ?>> 	
						<tr>
							<td colspan="2" class="encabezado2Horizontal"> NUEVO NIVEL ESCOLARIDAD</td>
						</tr>
						<tr>
							<td class="encabezado2VerticalInvertido">NIVEL DE ESCOLARIDAD</td>
							<td>
								<input type="text" name="escolaridad" value="<? echo $escolaridad?>" onKeyDown="xLetra(this)" onKeyUp="xLetra(this);Pasar(event,'Guardar')" onKeyPress="return evitarSubmit(event)">
							</td>
						</tr>    
						<tr>
							<td colspan="4" style="text-align:center;">
								<input type="submit" class="boton2Envio" name="Guardar" value="Guardar">
								<input type="button" class="boton2Envio" value="Cancelar" onClick="location.href='ConfEscolaridad.php?DatNameSID=<? echo $DatNameSID?>'">
							</td>
						</tr>
					</table>
				<input type="hidden" name="escolaridadAnt" value="<? echo $escolaridad?>">
				<input type="hidden" name="IdAnt" value="<? echo $Id?>">
				<input type="hidden" value="DatNameSID" name="<? echo $DatNameSID?>">
				</form>  
			</div>
		</body>
		</html>
