		<?php
			if($DatNameSID){session_name("$DatNameSID");}
			session_start();
			include("Funciones.php");	
			include_once("General/Configuracion/Configuracion.php");
			
			function Edad($edad){
				list($anio,$mes,$dia) = explode("-",$edad);
				$anio_dif = date("Y") - $anio;
				$mes_dif = date("m") - $mes;
				$dia_dif = date("d") - $dia;
				if($mes_dif<0){
					$anio_dif--;
					//echo "fechanac=$edad $anio_dif $mes_dif $dia_dif<br>";
				}
				elseif($mes_dif==0){
					if ($dia_dif < 0){
						$anio_dif;
						//echo "fechanac=$edad $anio_dif $mes_dif $dia_dif<br>";
					}
				}		
				return $anio_dif;
			}
			$Edad=Edad($Paciente[23]);
		?>
		
			<html>
				<head>
					<?php echo $codificacionMentor; ?>
					<?php echo $autorMentor; ?>
					<?php echo $titleMentor; ?>
					<?php echo $iconMentor; ?>
					<?php echo $shortcutIconMentor; ?>
					<link rel="stylesheet" type="text/css" href="../../General/Estilos/estilos.css">
					<script language="javascript">
						function CerrarThis()	{
							parent.document.getElementById('FrameOpener').style.position='absolute';
							parent.document.getElementById('FrameOpener').style.top='1px';
							parent.document.getElementById('FrameOpener').style.left='1px';
							parent.document.getElementById('FrameOpener').style.width='1';
							parent.document.getElementById('FrameOpener').style.height='1';
							parent.document.getElementById('FrameOpener').style.display='none';
						}
						function AbrirPyP(){
						
							frames.FrameOpener.location.href="VistaPrevia.php?HistoC=1&DatNameSID=<? echo $DatNameSID?>&Entidad=<? echo $Paciente[26]?>&Edad=<? echo $Edad?>&Sexo=<? echo $Paciente[24]?>&Dx=1&CodDx="+parent.document.FORMA.<? echo $ControlOrigen?>.value;
							document.getElementById('FrameOpener').style.position='absolute';
							document.getElementById('FrameOpener').style.top='1';
							document.getElementById('FrameOpener').style.left='1';
							document.getElementById('FrameOpener').style.display='';
							document.getElementById('FrameOpener').style.width='100%';
							document.getElementById('FrameOpener').style.height='100%';		
						}
					</script>
				
				</head>	
				
				<body <?php echo $backgroundBodyMentor; ?>>
					<div <?php echo $alignDiv2Mentor; ?> class="div2">
				
							<form name="FORMA">
								<table class="tabla2"  <?php echo $borderTabla2Mentor; echo $bordercolorTabla2Mentor; echo $cellspacingTabla2Mentor; echo $cellpaddingTabla2Mentor; ?>>
									<tr>
										<td class="encabezado2Horizontal">C&Oacute;DIGO</td>
										<td class="encabezado2Horizontal">DIAGN&Oacute;STICO</td>
									</tr>
									<tr>
									<td><input type="Text" name="Codigo" style="width:50px;" onKeyUp="frames.ListaCIE.location.href='ListaCIE.php?Codigo='+this.value"></td>
									<td><input type="Text" name="Diagnostico" style="width:500px;" onKeyUp="frames.ListaCIE.location.href='ListaCIE.php?Diagnostico='+this.value"></td></tr>
									<tr><td colspan="2">
									<iframe name="ListaCIE" id="ListaCIE" src="ListaCIE.php" name="ListaDx" style="height:240px;width:550px;" frameborder="0">

									</iframe>
									</td></tr>
									<tr>
									<td><input type="Text" name="CodSeleccionado" style="width:50px;border:0px;background:#e5e5e5;font : 13px Tahoma;"></td>
									<td><input type="Text" name="DxSeleccionado" style="width:500px;border:0px;background:#e5e5e5;font : 13px Tahoma;"></td>

									</tr>
								</table>
							
								<input type="Button" class="boton2Envio" value="Asignar Diagnostico" onClick="if(!CodSeleccionado.value){alert('Seleccione un Diagnostico');return false;}parent.document.FORMA.<? echo $ControlOrigen?>.value=CodSeleccionado.value;parent.document.FORMA.<? echo $DetalleObj?>.value=DxSeleccionado.value;CerrarThis()">
								<input type="hidden" name="DatNameSID" value="<? echo $DatNameSID?>">
							</form>

							<iframe scrolling="no" id="FrameFondo" name="FrameFondo" frameborder="0" height="0" width="0" style="filter:Alpha(Opacity=200, FinishOpacity=40, Style=2, StartX=20, StartY=40, FinishX=0, FinishY=0);display:none;border:thin; background-color:transparent" ></iframe>
							<iframe id="FrameOpener" name="FrameOpener" style="display:none" frameborder="0" height="1"> 
					</div>		
				</body>
			</html>
