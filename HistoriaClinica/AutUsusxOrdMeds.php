		<?
			if($DatNameSID){session_name("$DatNameSID");}
			session_start();
			include("Funciones.php");
			include_once("General/Configuracion/Configuracion.php");
		?>
		
		<script language="javascript">
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
		<?	
			
			if($Guardar)
			{
				$cons="Delete from Salud.ususxordmeds where Usuario='$Usuario'";
				$res=ExQuery($cons);
				while (list($val,$cad) = each ($Option)) 
				{			
					$cons="Insert into Salud.ususxordmeds(Usuario,Modulo) values ('$Usuario','$val')";
					$res=ExQuery($cons);
					//echo $cons."<br>";
				}
				?>
				<script language="javascript">
					//alert("El usuario debe reabrir la historia clinica para que el cambio surta efecto");
					CerrarThis();
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
			
				<script language="JavaScript">	

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

		<body>
			<div align="center">
				<form name="FORMA" method="post">
					<input type="hidden" name="Usuario" value="<? echo $Usuario?>" />
					
					<table width="100%" class="tabla1"  <?php echo $borderTabla1Mentor; echo $bordercolorTabla1Mentor; echo $cellspacingTabla1Mentor; echo $cellpaddingTabla1Mentor; ?>>
						<tr>
							<td style="text-align:right;padding-right:10px;">
								<button type="submit" name="Guardar"><img src="/Imgs/b_save.png" title="Guardar"></button>
								<button type="button" name="Cerrar" onClick="CerrarThis()"><img src="/Imgs/b_drop.png" title="Cerrar"></button>
							</td>
							<td style="text-align:center;">
								<input type="checkbox" name="Habilitar" title="Habilitar/Deshabilitar Todo" onClick="Marcar()" /></td>
							</td>
						</tr>
						<?
							$cons="Select Perfil from Salud.accesoxordmeds Order By Id";
							$res=ExQuery($cons);
							if(ExNumRows($res) > 0){
								echo "<tr>";
									echo "<td class='encabezado2Horizontal'>ORDEN MEDICA</td>";
									echo "<td class='encabezado2Horizontal'>&nbsp;</td>";
								echo "</tr>";
							}
							while($fila=ExFetch($res))	{
								$consV1="Select * from Salud.ususxordmeds where Usuario='$Usuario' and Modulo='$fila[0]'";
								$resV1=ExQuery($consV1);
								if(ExNumRows($resV1)==1){$Check1="checked";}else{$Check1="";}
								echo "<tr>";
									echo "<td style='text-align:left;padding-left:10px;'>$fila[0]</td>";
									echo "<td style='text-align:center;'><input name='Option[$fila[0]]' $Check1 type='checkbox'></td>";
								echo "</tr>";		
							}
						?>
					</table>
					<input type="Hidden" name="DatNameSID" value="<? echo $DatNameSID?>">
				</form>   
			</div>	
		</body>
	</html>
