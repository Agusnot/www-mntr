	
		<?
				if($DatNameSID){session_name("$DatNameSID");}
				session_start();
				include("Funciones.php");	
				include_once("General/Configuracion/Configuracion.php");	
				
				if($Guardar){
					if (count($CheckUsus) > 0){
						while (list($cad,$val) = each($CheckUsus)) {
							$cons="insert into alertas.usuariosxalertas (usuario,idalerta,compania) values ('$cad',$Id,'$Compania[0]')";
							$res=ExQuery($cons);
							//echo $cons."<br>";
						}
					}	?>
					<script language="javascript">
						location.href='UsuariosxAlertas.php?DatNameSID=<? echo $DatNameSID?>&Id=<? echo $Id?>';
					</script>
				<?
			}
			
			$cons1="select usuario from alertas.usuariosxalertas where compania='$Compania[0]' and idalerta=$Id ORDER BY usuario ASC";
			$res1=ExQuery($cons1);
			$cons="select nombre,usuario from central.usuarios "; 
			if(ExNumRows($res1)>0){	
				$cons=$cons." where usuario not in ('0'";
				while($fila1=ExFetch($res1))
				{
					//echo $fila1[0];
					$cons=$cons.",'$fila1[0]'";
				}
				$cons=$cons.")";
			}
			$cons=$cons." order by usuario ASC";
			$res=ExQuery($cons);
			//echo $cons;
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
					function Marcar()
					{
						if(document.FORMA.Marcacion.checked==1){MarcarTodo();}
						else{QuitarTodo();}
					}

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
				</script>
			</head>

			<body <?php echo $backgroundBodyMentor; ?>>
				<?php
						$rutaarchivo[0] = "ADMINISTRADOR";
						$rutaarchivo[1] = "ALERTAS";				
						$rutaarchivo[2] = "USUARIOS X ALERTA";
							
						mostrarRutaNavegacionEstatica($rutaarchivo);
				?>
				<form name="FORMA" method="post">
					<div align="center">
						<table  class="tabla2"  <?php echo $borderTabla2Mentor; echo $bordercolorTabla2Mentor; echo $cellspacingTabla2Mentor; echo $cellpaddingTabla2Mentor; ?> width="95%" rules="group"  >
							<tr>
								<td class="encabezado2Horizontal" >USUARIOS</td>
								<td style="text-align:center"><input type="checkbox" name="Marcacion" onClick="Marcar()"></td>
							</tr>
							<?	while($fila=ExFetch($res))
							{?>
								<tr>
									<td><? echo $fila[1]?></td>
									<td style="text-align:center"><input type="checkbox" name="CheckUsus[<? echo $fila[1]?>]"</td>
								</tr>	
							<?	}?>
							<tr>
								<td colspan="3" align="center">
									<input type="submit" value="Guardar" class="boton2Envio" name="Guardar"/>
									<input type="button" value="Cancelar"  class="boton2Envio" onClick="location.href='UsuariosxAlertas.php?DatNameSID=<? echo $DatNameSID?>&Id=<? echo $Id?>'">
								</td>
							</tr>
						</table>
					</div>	
					<input type="hidden" name="Id" value="<? echo $Id?>" />
					<input type="hidden" name="DatNameSID" value="<? echo $DatNameSID?>" />
				</form>    
			</body>
	</html>
