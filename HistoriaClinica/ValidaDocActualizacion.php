		<?php
			if($DatNameSID){session_name("$DatNameSID");}
			session_start();
			include("Funciones.php");
			include_once("General/Configuracion/Configuracion.php");
			
		?>
		<script language="javascript">
			function CerrarThis()	{
				parent.document.getElementById('FrameOpener').style.position='absolute';
				parent.document.getElementById('FrameOpener').style.top='1px';
				parent.document.getElementById('FrameOpener').style.left='1px';
				parent.document.getElementById('FrameOpener').style.width='1';
				parent.document.getElementById('FrameOpener').style.height='1';
				parent.document.getElementById('FrameOpener').style.display='none';
				//parent.document.FORMA.submit();
			}

		</script>
		<?
			if($CedDef)	{
				$cons="Select Identificacion from Central.Terceros where Identificacion='$CedDef' and Compania='$Compania[0]' and Tipo='Paciente'";
				$res=ExQuery($cons);
				$fila=ExFetchArray($res);
				
				?>
				<script language="javascript">
					
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
			
			<style>
				a{color:blue; text-decoration:none;}
				a:hover{color:red; text-decoration:underline;}
			</style>


			
		</head>
		
		<body>
			<div align ="center">
				<form name="FORMA" method="post">
					
					<input type="button" value=" X " onClick="CerrarThis()" style="position:absolute;top:1px;right:1px;" title="Cerrar esta ventana">
					<table class="tabla1"  <?php echo $borderTabla1Mentor; echo $bordercolorTabla1Mentor; echo $cellspacingTabla1Mentor; echo $cellpaddingTabla1Mentor; ?>>
					<?
					if($Cedula){
						$cons="Select Identificacion,PrimApe,SegApe,PrimNom,SegNom from Central.Terceros where Compania='$Compania[0]' and Identificacion ilike '$Cedula%' and tipo='Paciente'";
						$res=ExQuery($cons);
						while($fila=ExFetch($res))
						{
							?>
							<tr title="seleccionar" onMouseOver="this.bgColor='#AAD4FF'" style="cursor:hand" onMouseOut="this.bgColor=''"
							onClick="parent.document.FORMA.DocBusq.value='<? echo $fila[0]?>';CerrarThis();">
								<td><? echo $fila[0]?></td>
								<td><? echo "$fila[1] $fila[2] $fila[3] $fila[4]"?></td>
							</tr>
					<?	}
					}
					?>
					<input type="hidden" name="DatNameSID" value="<? echo $DatNameSID?>">
					</table>
				</form>
			</div>	
		</body>
	</html>
