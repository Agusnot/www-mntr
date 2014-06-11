	<?php
		if($DatNameSID){session_name("$DatNameSID");}
		session_start();
		include("../../../Funciones.php");
		include_once("../../../General/Configuracion/Configuracion.php");
	?>






		<html>
				<head>
					<?php echo $codificacionMentor; ?>
					<?php echo $autorMentor; ?>
					<?php echo $titleMentor; ?>
					<?php echo $iconMentor; ?>
					<?php echo $shortcutIconMentor; ?>
					<link rel="stylesheet" type="text/css" href="../../../General/Estilos/estilos.css">
				</head>
		<body <?php echo $backgroundBodyInfContableMentor;?>>
			<div class="divInformeContable" <?php echo $alignDivInformeContable;?>>
			
				<table width="70%" class='tablaInformeContable' width='90%' style='margin-top:25px;text-align:center;'  <?php echo $borderTablaInfContable; echo $bordercolorTablaInfContable; echo $cellspacingTablaInfContable; echo $cellpaddingTablaInfContable; ?>>	
				  
					  <tr>
						<td class='encabezado2HorizontalInfCont'>FECHA</td>
						<td width="10" class='encabezado2HorizontalInfCont'></td>
						<td class='encabezado2HorizontalInfCont'>NIT</td>
						<td width="10" class='encabezado2HorizontalInfCont'></td>
						<td class='encabezado2HorizontalInfCont'>NOMBRE TERCERO</td>
						<td class='encabezado2HorizontalInfCont'></td>
						<td class='encabezado2HorizontalInfCont'>COMPROBANTE</td>
						<td width="10" class='encabezado2HorizontalInfCont'></td>
						<td class='encabezado2HorizontalInfCont'>% RETENCI&Oacute;N </td>
						<td width="10" class='encabezado2HorizontalInfCont'></td>
						<td class='encabezado2HorizontalInfCont'>CONCEPTO</td>
						<td width="10" class='encabezado2HorizontalInfCont'></td>
						<td class='encabezado2HorizontalInfCont'>BASE</td>
						<td width="10" class='encabezado2HorizontalInfCont'></td>
						<td class='encabezado2HorizontalInfCont'>VALOR RETENIDO </td>
					  </tr>
						  <?php
						  $suma=0;

						$Tercero=$_GET['Tercero'];
						$qt='="$Tercero"';
						if($Tercero==NULL){$qt='is not null';}
						$FechaIni="".$_GET['Anio']."-".$_GET['MesIni']."-".$_GET['DiaIni']."";
						$FechaFin="".$_GET['Anio']."-".$_GET['MesFin']."-".$_GET['DiaFin']."";
						//echo"Tercero: $Tercero, Fecha 1: $FechaIni, Fecha 2: $FechaFin";
						$cons="SELECT fecha,comprobante,numero,contabilidad.movimiento.identificacion,central.terceros.primape,conceptorte,porcretenido,basegravable,haber
						FROM contabilidad.movimiento
						INNER JOIN central.terceros 
						ON contabilidad.movimiento.identificacion=central.terceros.identificacion
						WHERE basegravable !='0' AND central.terceros.identificacion $qt
						AND fecha BETWEEN '$FechaIni' and '$FechaFin'
						ORDER BY fecha, contabilidad.movimiento.identificacion";
						//echo"$cons";
						$res=ExQuery($cons);
						while($fila=ExFetch($res)){ ?>
						  <tr>
							<td><?php echo"$fila[0]"; ?></td>
							<td></td>
							<td><?php echo"$fila[3]"; ?></td>
							<td  ></td>
							<td><?php echo"$fila[4]"; ?></td>
							<td  ></td>
							<td><?php echo"$fila[1]"; ?>(<?php echo"$fila[2]"; ?>)
								
							</td>
							<td  ></td>
							<td><?php echo"$fila[6]"; ?></td>
							<td  ></td>
							<td><?php echo"$fila[5]"; ?></td>
							<td  ></td>
							<td><?php echo number_format($fila[7],2); ?></td>
							<td  ></td>
							<td><div align="right">
							  <?php $suma=$suma+$fila[8];echo number_format($fila[8],2); ?>
							</td>
						  </tr>
							<tr>
							  <td></td>
							  <td></td>
							  <td></td>
							  <td></td>
							  <td></td>
							  <td></td>
							  <td></td>
							  <td></td>
							  <td></td>
							  <td></td>
							  <td></td>
							  <td></td>
							  <td></td>
							  <td></td>
							  <td></td>
							</tr>
							<?php }
						?>
							<tr>
							<td></td>
							<td></td>
							<td></td>
							<td></td>
							<td></td>
							<td></td>
							<td></td>
							<td ></td>
							<td></td>
							<td ></td>
							<td></td>
							<td ></td>
							<td class="filaTotalesInfContable" style='text-align: right; padding-right: 10px;'>TOTAL:</td>
							<td></td>
							<td class="filaTotalesInfContable" style='text-align: right; padding-right: 10px;' ><?php echo number_format($suma,2); ?></td>
						  </tr>
				</table>
			</div>	

		</body>
		</html>
