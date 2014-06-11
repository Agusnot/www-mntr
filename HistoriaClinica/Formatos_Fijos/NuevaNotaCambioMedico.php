		<?php
			if($DatNameSID){session_name("$DatNameSID");}
			session_start();
			include("Funciones.php");
			include_once("General/Configuracion/Configuracion.php");
			$ND=getdate();
			$Dia=$ND[mday];
			$Mes=$ND[mon];
			$Anio=$ND[year];
			$Horas=$ND[hours];
			$Minutos=$ND[minutes];
			$Segundos=$ND[seconds];	
			if($Guardar)
			{
				$cons = "Insert Into HistoriaClinica.NotasCambioMedico (Compania,Fecha,Usuario,Nota,Unidad) 
				Values('$Compania[0]','$Anio-$Mes-$Dia $Horas:$Minutos:$Segundos','$usuario[0]','$Anotacion','$SelUnidad')";
				$res = ExQuery($cons);
				?><script language='JavaScript'>location.href='NotasCambioMedico.php?DatNameSID=<? echo $DatNameSID?>&SelUnidad=<? echo $SelUnidad?>';</script>
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
			<link rel="stylesheet" type="text/css" href="../../General/Estilos/estilos.css">
			<script language="javascript" src="/funciones.js">
			</script>
			<script language="JavaScript">
			function ltrim(str) { 
				for(var k = 0; k < str.length && isWhitespace(str.charAt(k)); k++);
				return str.substring(k, str.length);
			}
			function rtrim(str) {
				for(var j=str.length-1; j>=0 && isWhitespace(str.charAt(j)) ; j--) ;
				return str.substring(0,j+1);
			}
			function trim(str) {
				return ltrim(rtrim(str));
			}
			function isWhitespace(charToCheck) {
				var whitespaceChars = " \t\n\r\f";
				return (whitespaceChars.indexOf(charToCheck) != -1);
			}
			function Validar()
			{
				str=trim(document.FORMA.Anotacion.value);	
				if(str==""){alert("Por Favor Ingrese el texto de la Nota de Cambio de Jefe!!!");return false;}
			}
			</script>

		</head>
		<body <?php echo $backgroundBodyMentor; ?>>
			<?php
				$rutaarchivo[0] = "HISTORIA CL&Iacute;NICA";
				$rutaarchivo[1] = "CAMBIOS TURNO";										
				$rutaarchivo[2] = "NOTAS CAMBIO M&Eacute;DICO";										
				$rutaarchivo[3] = "NUEVA";	
				mostrarRutaNavegacionEstatica($rutaarchivo);
					
			?>
			<div <?php echo $alignDiv2Mentor; ?> class="div2">	
				<form name="FORMA" onSubmit="return Validar()">
					<input type="hidden" name="DatNameSID" value="<? echo $DatNameSID?>">
					<input type='Hidden' name='SelUnidad' value='<? echo $SelUnidad?>'>
					<input type="Hidden" name="AutoId" value=<? echo $AutoId ?>>
					
					<table class="tabla2"  <?php echo $borderTabla2Mentor; echo $bordercolorTabla2Mentor; echo $cellspacingTabla2Mentor; echo $cellpaddingTabla2Mentor; ?>>
						<tr>
							<td class="encabezado2Horizontal">NUEVA NOTA DE CAMBIO DE M&Eacute;DICO</td>
						</tr>	
							<tr>
								<td class="encabezado2VerticalInvertido">CREADA POR:  <? echo strtoupper($usuario[0])." - $Anio/$Mes/$Dia - $Horas:$Minutos";?></td>
							</tr>
							<tr>
								<td style='text-align:justify;'>
									<textarea name="Anotacion" style="width:750px;height:300px;"></textarea>    
							</td></tr>
					</table>
					<div align="center">
						<input type="Submit"  name="Guardar" class="boton2Envio" value="Guardar">
						<input type="Button" name="Cancelar" class="boton2Envio" value="Cancelar" onClick="location.href='NotasCambioMedico.php?DatNameSID=<? echo $DatNameSID?>&SelUnidad=<? echo $SelUnidad?>'">
					</div>
				</form>
			</div>	
		</body>
	</html>	
