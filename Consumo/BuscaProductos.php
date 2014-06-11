		<?
			//echo $Anio;
				if($DatNameSID){session_name("$DatNameSID");}
			session_start();
			include("Funciones.php");
			include("ObtenerSaldos.php");
			include_once("General/Configuracion/Configuracion.php");
			$ND=getdate();
				//UM:27-04-2011
			$Fecha="$ND[year]-$ND[mon]-$ND[mday]";//$Anio=$ND[year];
			$VrSaldoIni=SaldosIniciales($Anio,$AlmacenPpal,"$Anio-$ND[mon]-01");
			$VrEntradas=Entradas($Anio,$AlmacenPpal,"$Anio-$ND[mon]-01",$Fecha);
			$VrSalidas=Salidas($Anio,$AlmacenPpal,"$Anio-$ND[mon]-01",$Fecha);
				$VrDevoluciones=Devoluciones($Anio,$AlmacenPpal,"$Anio-01-01",$Fecha);
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
		
			<body <?php echo $backgroundBodyMentor; ?>>
				<div <?php echo $alignDiv2Mentor; ?> class="div2">
					<table class="tabla2"  <?php echo $borderTabla2Mentor; echo $bordercolorTabla2Mentor; echo $cellspacingTabla2Mentor; echo $cellpaddingTabla2Mentor; ?>>
						<tr>
						<td class="encabezado2Horizontal">C&Oacute;DIGO</td>
						<td class="encabezado2Horizontal">NOMBRE</td>
						<td class="encabezado2Horizontal">GRUPO</td>
						<td class="encabezado2Horizontal">NOMBRE 2</td>
						<td class="encabezado2Horizontal">SALDO ACTUAL</td>
					</tr>

					<?
						$cons="Select Codigo1,NombreProd1,NombreProd2,AutoId,Presentacion,UnidadMedida,Grupo
						from Consumo.CodProductos 
						where (NombreProd1 || ' ' || Presentacion || ' ' || UnidadMedida) ilike '$NomProducto%' and Compania='$Compania[0]' and AlmacenPpal='$AlmacenPpal' and Estado='AC'
						and Anio = $Anio and AutoId not in(Select AutoId from Consumo.TMPSolicitudConsumo where TMPCOD = '$TMPCOD')
						Order by NombreProd1";
							//echo $cons;
							$res=ExQuery($cons);echo ExError();
						while($fila=ExFetch($res))	{
							$CantExistencias=$VrSaldoIni[$fila[3]][0]+$VrEntradas[$fila[3]][0]-$VrSalidas[$fila[3]][0]+$VrDevoluciones[$fila[3]][0];
							echo "<tr><td>$fila[0] &nbsp;</td>";
							echo "<td>"?>
									<a onclick="parent.document.FORMA.Producto.value='<? echo "$fila[1] $fila[4] $fila[5]"?>'; 
									parent.document.FORMA.AutoId.value='<? echo $fila[3];?>';
									parent.document.FORMA.Codigo.value='<? echo $fila[0];?>';
									parent.document.FORMA.Cantidad.focus();
									" href="#">
										<? echo "$fila[1] $fila[4] $fila[5]";
										?>
									</a>
									<?php
							echo "</td>";
							echo "<td>$fila[6] &nbsp;</td>";
							echo "<td>$fila[2] &nbsp;</td>";
							echo "<td style='text-align:right;padding-right:5px;'>".number_format($CantExistencias,2)."&nbsp;</a></td>";
						}
					?>
					</table>
				</div>	
			</body>
		</html>	

