		<?
			if($DatNameSID){session_name("$DatNameSID");}
			session_start();
			include ("Funciones.php");
			include_once("General/Configuracion/Configuracion.php");
		?>
		
		
		
	
			<script language="javascript" src="/Funciones.js"></script>
			<script language="javascript">
				function Validar()
				{
					if(document.FORMA.Nombre.value==""){alert("Debe llenar el campo <? echo $Campo; ?>");return false;}
				}
				function CerrarThis()
				{
					parent.document.getElementById('FrameOpener').style.position='absolute';
					parent.document.getElementById('FrameOpener').style.top='1px';
					parent.document.getElementById('FrameOpener').style.left='1px';
					parent.document.getElementById('FrameOpener').style.width='1';
					parent.document.getElementById('FrameOpener').style.height='1';
					parent.document.getElementById('FrameOpener').style.display='none';
				}
			</script>
		</head>	
		
		
		<?
			if($Guardar)
			{
				if(!$Editar)
				{
					$cons = "Select * from Consumo.$Tabla Where Compania='$Compania[0]' and AlmacenPpal='$AlmacenPpal' and $Campo='$Nombre'";
								$res = ExQuery($cons);
								if(ExNumRows($res)>0){$MensajeInserta = "El campo $Campo $Nombre ya se encuentra configurado";}
								$cons = "Insert into Consumo.".$Tabla."
					(".$Campo.",AlmacenPpal,Compania) values
					('$Nombre','$AlmacenPpal','$Compania[0]')";		
				}
				else
				{
					$cons = "Update Consumo.".$Tabla." set ".$Campo."='$Nombre'
					where ".$Campo."='$Nombrex' and AlmacenPpal='$AlmacenPpal' and Compania='$Compania[0]'";	
				}
				//echo $cons;exit;
						if(!$MensajeInserta){$res=ExQuery($cons);}
				if(ExError() || $MensajeInserta)
				{
					if(ExError())echo "<em>Se produjo el siguiente error:<strong><br> " . LibErrores(ExErrorNo()) . "</em>";
								else
								{
									?><script language="javascript">
										alert("<? echo $MensajeInserta?>");
									</script><?
								}
				}
				else
				{
					if($VienedeOtro)
					{
									?>
										<script language="javascript">
											CerrarThis();
											parent.document.getElementById('<? echo $Objeto?>').focus();
										</script>
									<?
					}
					else
					{
									?>
										<script language="javascript">
												location.href="ConfigConsumo.php?DatNameSID=<? echo $DatNameSID?>&Tabla=<? echo $Tabla;?>&Campo=<? echo  $Campo;?>&AlmacenPpal=<? echo $AlmacenPpal;?>";
										</script>
									<?
					}
				}
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
			</head>	
			<body  onLoad="document.FORMA.Nombre.focus();">
				<form name="FORMA" method="post" onSubmit="return Validar()">
					<table class="tabla2"    <?php echo $borderTabla2Mentor; echo $bordercolorTabla2Mentor; echo $cellspacingTabla2Mentor; echo $cellpaddingTabla2Mentor; ?>>
						<tr>
							<td colspan="2" class="encabezado2Horizontal"><? echo strtoupper($AlmacenPpal);?></td>
						</tr>
						<tr>
							<td class="encabezado2VerticalInvertido">NOMBRE <? echo strtoupper($Campo);?></td>
							<td><input type="text" name="Nombre" value="<? echo $Nombre;?>" maxlength="100" size="50"  
								onKeyUp="xLetra(this)" onKeyDown="xLetra(this)"/>
							</td>
						</tr>
					</table>
					<div align="center">
						<input type="Hidden" name="Tabla" value="<? echo $Tabla; ?>"  />
						<input type="Hidden" name="Campo" value="<? echo $Campo; ?>"  />
						<input type="Hidden" name="AlmacenPpal" value="<? echo $AlmacenPpal; ?>"  />
						<input type="Hidden" name="Nombrex" value="<? echo $Nombre; ?>"  />
						<input type="Hidden" name="Editar" value="<? echo $Editar; ?>"  />
						<input type="hidden" name="DatNameSID" value="<? echo $DatNameSID?>" />
						<input type="text" name="Nada" value="1" style="visibility:hidden;width:1px;" />
						<input type="submit"   name="Guardar" class="boton2Envio" value="Guardar" />
						<input type="button" name="Cancelar" class="boton2Envio" value="Cancelar" 
						onClick="<?
								if($VienedeOtro)
								{ echo "CerrarThis()";}
								else
								{?>location.href='ConfigConsumo.php?DatNameSID=<? echo $DatNameSID?>&Tabla=<? echo $Tabla;?>&Campo=<? echo $Campo;?>&AlmacenPpal=<? echo $AlmacenPpal?>'<? } ?> " />
					</div>			
				</form>
			</body>
	</html>	