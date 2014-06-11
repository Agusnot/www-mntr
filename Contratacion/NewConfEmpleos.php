		<?	if($DatNameSID){session_name("$DatNameSID");}
			session_start();
			include("Funciones.php");
			include_once("General/Configuracion/Configuracion.php");
			if($Guardar){
				if(!$Edit){
					$cons="select id from historiaclinica.empleosOIT where compania='$Compania[0]' order by id desc";
					$res=ExQuery($cons);
					$fila=ExFetch($res);
					$AutoId=$fila[0]+1;
					$cons="insert into historiaclinica.empleosOIT(compania,empleo,id) values ('$Compania[0]','$empleo','$AutoId')";
					//echo $cons;
				}
				else{
					$cons="update historiaclinica.empleosOIT set empleo='$empleo',id=$Id where compania='$Compania[0]' and empleo='$empleoAnt' and id=$IdAnt";
					//echo $cons;			
				}		
				$res=ExQuery($cons);?>
				<script language="javascript">
				location.href='ConfEmpleos.php?DatNameSID=<? echo $DatNameSID?>';
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
								alert("Debe digitar una ocupación!!!");return false;
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
						$rutaarchivo[2] = "OCUPACIONES";					
						$rutaarchivo[3] = "NUEVA";				
										
						mostrarRutaNavegacionEstatica($rutaarchivo);
					
					?>
					<div <?php echo $alignDiv2Mentor; ?> class="div2">
						<form name="FORMA" method="post" onSubmit="return validar()">
							<table class="tabla2"  <?php echo $borderTabla2Mentor; echo $bordercolorTabla2Mentor; echo $cellspacingTabla2Mentor; echo $cellpaddingTabla2Mentor; ?>> 	
								<tr>
									<td colspan="2" class="encabezado2Horizontal"> NUEVA OCUPACI&Oacute;N </td>
								</tr>
								<tr>
									<td class="encabezado2VerticalInvertido">OCUPACI&Oacute;N</td>
									<td>
										<input type="text" name="empleo" value="<? echo $empleo?>" onKeyDown="xLetra(this)" onKeyUp="xLetra(this);Pasar(event,'Guardar')" onKeyPress="return evitarSubmit(event)">
									</td>
								</tr>    
								<tr>
									<td colspan="4" style="text-align:center">
										<input type="submit" class="boton2Envio" name="Guardar" value="Guardar">
										<input type="button" class="boton2Envio" value="Cancelar" onClick="location.href='ConfEmpleos.php?DatNameSID=<? echo $DatNameSID?>'">
									</td>
								</tr>
							</table>
							<input type="hidden" name="empleoAnt" value="<? echo $empleo?>">
							<input type="hidden" name="IdAnt" value="<? echo $Id?>">
							<input type="hidden" value="DatNameSID" name="<? echo $DatNameSID?>">
						</form>  
					</div>			
				</body>
		</html>
