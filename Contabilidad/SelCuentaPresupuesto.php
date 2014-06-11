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
				$cons="Update Contabilidad.PlanCuentas set AfectacionPresup='$CuentaSel' where Cuenta='$Cuenta' and Compania='$Compania[0]' and Anio='$AnioAc'";
				$res=ExQuery($cons);
				?>
				<script language="JavaScript">
					parent.document.FORMA.AfectacionPresup.value='<?echo $CuentaSel?>';
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
		</head>
		
		<body>
			<form name="FORMA">
			
				<table class="tabla2"  <?php echo $borderTabla2Mentor ; echo $bordercolorTabla2Mentor ; echo $cellspacingTabla2Mentor ; echo $cellpaddingTabla2Mentor; ?>>
					<tr>
					<td class="encabezado2Horizontal">CUENTA</td>
					<td>
						<input type="Hidden" name="Cuenta" value="<?echo $Cuenta?>">
						<select name="CuentaSel" style="width:480px;">
							<option></option>
								<?

									$cons="Select AfectacionPresup from Contabilidad.PlanCuentas where Cuenta='$Cuenta' and Anio='$AnioAc' and Compania='$Compania[0]'";
									$res=ExQuery($cons);
									$fila=ExFetch($res);
									$CtaAfectacion=$fila[0];

									$cons="Select Cuenta from Presupuesto.ClasesCuenta where Clase='Ingreso'";
									$res=ExQuery($cons);
									while($fila=ExFetch($res))
									{
										$cons1="Select Cuenta,Nombre from Presupuesto.PlanCuentas where Cuenta ilike '$fila[0]%' and Tipo='Detalle' and Anio='$AnioAc' and Compania='$Compania[0]' Order By Cuenta";
										$res1=ExQuery($cons1);
										while($fila1=ExFetch($res1))
										{
											if($fila1[0]==$CtaAfectacion){echo "<option selected value='$fila1[0]'>$fila1[0] $fila1[1]</option>";}
											else{echo "<option value='$fila1[0]'>$fila1[0] $fila1[1]</option>";}
										}
									}
								?>
						</select>
					</td>
				</tr>
				<tr>
					<td colspan="2" align="center">
						<input type="Submit" name="Guardar" class="boton2Envio" value="Guardar y Regresar">
						<input type="hidden" name="DatNameSID" value="<? echo $DatNameSID?>">
						<input type="Button" value="Quitar" class="boton2Envio" style="width:160px;" onClick="document.FORMA.CuentaSel.value=''">
					</td>
				</tr>
			</table>
		</form>
	</body>