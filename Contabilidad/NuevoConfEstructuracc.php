			<?
				if($DatNameSID){session_name("$DatNameSID");}
				session_start();
				include ("Funciones.php");
				include_once("General/Configuracion/Configuracion.php");
				if(!$Nivel)
				{
					$cons = "Select Nivel from Central.EstructuraxCC where compania='$Compania[0]' and Anio='$Anio' order by Nivel desc";
					$res = ExQuery($cons);
					$fila = ExFetch($res);
					echo ExError();
					$Nivel = $fila[0] + 1;
				}
				if($Guardar)
				{
					if(!$Editar)
					{
						$cons="Insert into Central.EstructuraxCC(Compania,Anio,Nivel,Digitos) 
						values ('$Compania[0]','$Anio','$Nivel','$Digitos')";		   
					}
					else
					{
						$cons = "Update central.EstructuraxCC set Digitos = '$Digitos' where
						Compania = '$Compania[0]' and Anio = '$Anio' and Nivel = '$Nivel'";
					}
					$res=ExQuery($cons);
					echo ExError($res);
					?><script language="javascript">location.href='ConfEstructuraCC.php?DatNameSID=<? echo $DatNameSID?>&Anio=<? echo $Anio?>';</script><?
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
			<script language="javascript" src="/Funciones.js"></script>
			<script language="javascript">
				function Validar()
				{
					if(document.FORMA.Digitos.value == ""){alert("Ingrese un Numero de Digitos");return false;}
				}
			</script>
		</head>	
			
			<body <?php echo $backgroundBodyMentor; ?>>	
				<?php
				$rutaarchivo[0] = "CONTABILIDAD";
				$rutaarchivo[1] = "CONFIGURACION";
				$rutaarchivo[2] = "ESTRUCTURA CENTROS COSTO";
				mostrarRutaNavegacionEstatica($rutaarchivo);
				
				?>
				<div <?php echo $alignDiv1Mentor; ?> class="div1">
				<?php
				
				if($Editar)	{
					$cons = "Select Nivel,Digitos from Central.EstructuraxCC where compania='$Compania[0]' and Anio='$Anio' and Nivel = '$Nivel'";
					$res = ExQuery($cons);
					$fila = ExFetch($res);
					$Nivel = $fila[0]; $Digitos=$fila[1];
				}
				?>
			
			
			<form name="FORMA" method="post" onSubmit="return Validar()">
				<input type="hidden" name="Anio" value="<? echo $Anio?>" />
				<input type="hidden" name="Editar" value="<? echo $Editar?>" />
				
			   <table class="tabla1"  width="300px" style="text-align:center;"  <?php echo $borderTabla1Mentor; echo $bordercolorTabla1Mentor; echo $cellspacingTabla1Mentor; echo $cellpaddingTabla1Mentor; ?>>
					<tr>
						<td class='encabezado2Horizontal' colspan="2" >ESTRUCTURA CENTRO DE COSTOS A&Ntilde;O <? echo $Anio?></td></tr>
						<td class='encabezado1HorizontalInvertido'>NIVEL</td>
							<td><input type="text" name="Nivel" value="<? echo $Nivel?>" readonly maxlength="3" size="3" /></td>
					</tr>
					<tr>
					<td class='encabezado2HorizontalInvertido'>D&Iacute;GITOS</td>
					<td><input type="text" name="Digitos" value="<? echo $Digitos?>" onKeyUp="xNumero(this)" onKeyDown="xNumero(this)" onBlur="campoNumero(this)" 
					maxlength="3" size="3" /></td>
				</tr>
				</table>
				
			<div style="margin-top:15px;margin-bottom:15px;">
				<input type="submit" name="Guardar" class="boton2Envio" value="Guardar" />
				<input type="hidden" name="DatNameSID" value="<? echo $DatNameSID?>">
				<input type="button" name="Cancelar"  class="boton2Envio" value="Cancelar" onClick="location.href='ConfEstructuraCC.php?DatNameSID=<? echo $DatNameSID?>&Anio=<? echo $Anio?>'"  />
			</div>	
			</form>
			</div>	
		</body>
	</html>	