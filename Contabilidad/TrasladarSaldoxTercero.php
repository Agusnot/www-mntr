		<?
			if($DatNameSID){session_name("$DatNameSID");}
			session_start();
			include("Funciones.php");
			include_once("General/Configuracion/Configuracion.php");
			if($Iniciar)
			{
				$cons="Update $BaseDatos.Movimiento set Identificacion='$IdNueva' where Identificacion='$IdAnterior'";
				$res=ExQuery($cons);echo ExError($res);
				$NumerRows=ExAfectedRows($res);
				?>
				<script language="JavaScript">
					alert("Se afectaron un total de <?echo $NumerRows?> Registros");
					location.href='/ModOpciones.php?DatNameSID=<? echo $DatNameSID?>';
				</script>
		<?
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
		
			<script language="javascript">
				function SelTercero(Campo)
				{
					frames.FrameOpener.location.href='/Contabilidad/BusquedaxOtros.php?DatNameSID=<? echo $DatNameSID?>&Tipo=Tercero&Campo='+Campo;
					document.getElementById('FrameOpener').style.position='absolute';
					document.getElementById('FrameOpener').style.top='5px';
					document.getElementById('FrameOpener').style.left='1px';
					document.getElementById('FrameOpener').style.display='';
					document.getElementById('FrameOpener').style.width='690';
					document.getElementById('FrameOpener').style.height='390';
				
				}
			</script>
		</head>
		<body <?php echo $backgroundBodyMentor; ?>>
			
			<?php
				$rutaarchivo[0] = "CONTABILIDAD";
				$rutaarchivo[1] = "PROCESOS CONTABLES";
				$rutaarchivo[2] = "TRASLADAR SALDO POR TERCERO";
				mostrarRutaNavegacionEstatica($rutaarchivo);
				
				?>
			<div <?php echo $alignDiv1Mentor; ?> class="div1">	
				<form name="FORMA" onSubmit="if(confirm('Desea trasladar saldos entre los terceros seleccionados?')==true)">
					<table class="tabla1"  width="450px" style="text-align:center;"  <?php echo $borderTabla1Mentor; echo $bordercolorTabla1Mentor; echo $cellspacingTabla1Mentor; echo $cellpaddingTabla1Mentor; ?>>
							<tr>
								<td class='encabezado2Horizontal' colspan="2"> TRASLADO DE SALDOS POR TERCERO</td>
							</tr>
							<tr>
								<td class='encabezado1HorizontalInvertido' >IDENTIFICACI&Oacute;N ANTERIOR</td>
								<td><input readonly="yes" type="Text" name="IdAnterior" onClick="SelTercero(this.name)"></td></tr>
							<tr>
								<td class='encabezado1HorizontalInvertido' >IDENTIFICACI&Oacute;N NUEVA</td>
								<td><input readonly="yes" type="Text" name="IdNueva" onClick="SelTercero(this.name)"></td>
							</tr>
							<tr>
							<td colspan="2" style="text:align:center;padding-top:7px;padding-bottom:7px;">
								<input type="Submit" class="boton2Envio" name="Iniciar" value="Trasladar Saldos"></td></tr>
								<input type="Hidden" name="BaseDatos" value="<?echo $BaseDatos?>">
								<input type="hidden" name="DatNameSID" value="<? echo $DatNameSID?>">
					</table>
				</form>
				<iframe id="FrameOpener" name="FrameOpener" style="display:none;border:#e5e5e5 ridge" frameborder="0" height="1"></iframe>
			</div>	
		</body>
	</html>	
