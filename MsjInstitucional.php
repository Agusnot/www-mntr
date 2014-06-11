		<?	
			if($DatNameSID){session_name("$DatNameSID");}
			session_start();
			include("Funciones.php");
			include_once("General/Configuracion/Configuracion.php");
			
			$ND=getdate();
			if($Guardar)
			{
				$cons="delete from central.msjinstitucional";
				$res=ExQuery($cons);		
				$cons="insert into central.msjinstitucional (mensaje,duracion) values ('$msj','$Duracion')";
				$res=ExQuery($cons);
			}
			$cons="select mensaje,duracion from central.msjinstitucional";
			$res=ExQuery($cons);
			$fila=ExFetch($res);
		?>	
		<html>
				<head>
					<?php echo $codificacionMentor; ?>
					<?php echo $autorMentor; ?>
					<?php echo $titleMentor; ?>
					<?php echo $iconMentor; ?>
					<?php echo $shortcutIconMentor; ?>
					<link rel="stylesheet" type="text/css" href="../General/Estilos/estilos.css">
					<script language="javascript" src="/Funciones.js"></script>
					<script language="javascript">
						function Validar()
						{
							if(document.FORMA.msj.value==""){alert("Debe dijitar el mensaje!!!");return false;}
							if(document.FORMA.Duracion.value==""){alert("Debe digitar la duracion!!!");return false;}
						}
					</script>
				</head>

				
				
				<body <?php echo $backgroundBodyMentor; ?>>
					<?php mostrarRutaNavegacion($_SERVER['PHP_SELF']);	?>
					<div <?php echo $alignDiv2Mentor; ?> class="div2">
						<form name="FORMA" method="post" onSubmit="return Validar()">  
							
							<table class="tabla2"  <?php echo $borderTabla2Mentor; echo $bordercolorTabla2Mentor; echo $cellspacingTabla2Mentor; echo $cellpaddingTabla2Mentor; ?>>
								<tr>    	
									<td class="encabezado2Horizontal">MENSAJE INSTITUCIONAL</td>
								</tr>
								<tr>    	
									<td><textarea name="msj" cols="80" rows="12" onKeyDown="xLetra(this)" onKeyPress="xLetra(this)" onKeyUp="xLetra(this)" style="text-align:center"><? echo $fila[0]?></textarea></td>
								</tr>
								<tr align="center">
									<td style="color:#002147;font-weight:bold;">Duracion: 
										<input type="text" name="Duracion" onKeyDown="xNumero(this)" onKeyPress="xNumero(this)" onKeyUp="xNumero(this)" style=" width:30" maxlength="2" value="<? echo $fila[1]?>"/>
										segundos
									</td>
								</tr>
								<tr align="center">
									<td><input type="submit" value="Guardar" name="Guardar" class="boton2Envio"/></td>
								</tr>
							</table>
							<input type="hidden" name="DatNameSID" value="<? echo $DatNameSID?>"/>
						</form> 
					</div>	
				</body>	
		</html>
