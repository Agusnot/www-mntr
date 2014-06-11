		<?php
			if($DatNameSID){session_name("$DatNameSID");}
			session_start();
			include("Funciones.php");
			include_once("General/Configuracion/Configuracion.php");
			if($Guardar){
				
				while (list($cad,$val) = each ($Aux))
				{
					$Aux2[$cad]=2;
				}
				while (list($cad,$val) = each ($Campo))
				{
					$Aux2[$cad]=1;
				}
				while (list($cad,$val) = each ($Aux2))
				{
					if($val==1){
						$cons="update historiaclinica.fichaid set obligatorio=1 where compania='$Compania[0]' and campo='$cad'";			
						$res=ExQuery($cons);
					}
					else{
						$cons="update historiaclinica.fichaid set obligatorio=0 where compania='$Compania[0]' and campo='$cad'";			
						$res=ExQuery($cons);
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
				<script language="javascript">
				function MarcarTodo()
					{
						for (i=0;i<document.FORMA.elements.length;i++) 
						if(document.FORMA.elements[i].type == "checkbox") 
						document.FORMA.elements[i].checked=1 
					}
					
					function QuitarTodo()
					{
						for (i=0;i<document.FORMA.elements.length;i++) 
						if(document.FORMA.elements[i].type == "checkbox") 
						document.FORMA.elements[i].checked=0
					}
					
					function Marcar()
					{
						if(document.FORMA.Habilitar.checked==1){MarcarTodo();}
						else{QuitarTodo();}
					}
				</script>
			</head>

		<body <?php echo $backgroundBodyMentor; ?>>
			<?php
				$rutaarchivo[0] = "HISTORIA CL&Iacute;NICA";
				$rutaarchivo[1] = "CONFIGURACI&Oacute;N";
				$rutaarchivo[2] = "FICHA ID";
				mostrarRutaNavegacionEstatica($rutaarchivo);
			?>
				<div <?php echo $alignDiv2Mentor; ?> class="div2">
					<form name="FORMA" method="post">
					
						<table class="tabla2"  <?php echo $borderTabla2Mentor; echo $bordercolorTabla2Mentor; echo $cellspacingTabla2Mentor; echo $cellpaddingTabla2Mentor; ?>>
							<tr>
								<td class="encabezado2Horizontal" colspan="10">CAMPOS FICHA IDENTIFICACI&Oacute;N</td>
							</tr>
							<tr>
								<td class="encabezado2HorizontalInvertido" style="text-align:center;" colspan="10">
									SELECCIONAR TODOS
									<input type="checkbox" name="Habilitar" onClick="Marcar()">
								</td>
							</tr>
						<?
							$cons="select campo,obligatorio from historiaclinica.fichaid where compania='$Compania[0]' order by campo";
							$res=ExQuery($cons); echo ExError();
							echo "<tr>";
							while($fila=ExFetch($res))
							{
								$cont++;?>		
								<td><? $Aux=str_replace('_',' ',$fila[0]);echo strtoupper($Aux)?></td>
								<td><input type="checkbox" name="Campo[<? echo $fila[0]?>]" <? if($fila[1]==1){?> checked <? }?> value="<? echo $fila[1]?>"></td>
								<input type="hidden" name="Aux[<? echo $fila[0]?>]" value="<? if($fila[1]==1){echo "1";} else{echo "2";}?>">
						<?		if($cont==5){ echo "</tr><tr>"; $cont=0;}
							}
						?>
							<tr>
								<td colspan="10" style="text-align:center;">
									<input type="submit" class="boton2Envio" name="Guardar" value="Guardar">
								</td>
							</tr>
						</table>
						<input type="Hidden" name="DatNameSID" value="<? echo $DatNameSID?>">
					</form>
			</div>	
		</body>
	</html>
