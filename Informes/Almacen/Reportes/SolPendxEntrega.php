		<?
			if($DatNameSID){session_name("$DatNameSID");}
			session_start();
			include("Funciones.php");
			include("Consumo/ObtenerSaldos.php");
			include_once("General/Configuracion/Configuracion.php");
			$ND=getdate();
				//echo $Anio;
			if(!$Anio){$Anio=$ND[year];}
				//echo $Anio;
			$VrSaldoIni=SaldosIniciales($Anio,$AlmacenPpal,"$Anio-$ND[mon]-01");
			$VrEntradas=Entradas($Anio,$AlmacenPpal,"$Anio-$ND[mon]-01",$Fecha);
			$VrSalidas=Salidas($Anio,$AlmacenPpal,"$Anio-$ND[mon]-01",$Fecha);
		?>
		
		
	<html>
		<head>
			<?php echo $codificacionMentor; ?>
			<?php echo $autorMentor; ?>
			<?php echo $titleMentor; ?>
			<?php echo $iconMentor; ?>
			<?php echo $shortcutIconMentor; ?>
			<link rel="stylesheet" type="text/css" href="../../../General/Estilos/estilos.css">
			<script language="javascript">
				function CerrarThis(Cedula,Nombre,CC,Solicitud,Anio){
					if (typeof Cedula != "undefined" && typeof Nombre != "undefined")
					{
						parent.document.FORMA.Cedula.value=Cedula;
						parent.document.FORMA.Tercero.value=Nombre;
					}
					//parent.document.FORMA.Detalle.value="ENTREGA DE PEDIDO PARA " + CC + " SOLICITUD No " + Solicitud + " DE " + Anio;
					parent.document.getElementById('FrameOpener').style.position='absolute';
					parent.document.getElementById('FrameOpener').style.top='1px';
					parent.document.getElementById('FrameOpener').style.left='1px';
					parent.document.getElementById('FrameOpener').style.width='1';
					parent.document.getElementById('FrameOpener').style.height='1';
					parent.document.getElementById('FrameOpener').style.display='none';
				}
			</script>
		</head>	

		<body>
			<div align="center">
				<input type="button" value="Cerrar" class="boton2Envio" onClick="CerrarThis()">
				<table class="tabla1" style="margin-top:10px;margin-bottom:10px;" width="95%" style="font-size:11px;" <?php echo $borderTabla1Mentor; echo $bordercolorTabla1Mentor; echo $cellspacingTabla1Mentor; echo $cellpaddingTabla1Mentor; ?>>
					<tr>
						<td class="encabezado2Horizontal">SOLICITUD</td>
						<td class="encabezado2Horizontal">USUARIO</td>
						<td class="encabezado2Horizontal">CENTRO DE COSTOS</td>
					</tr>
					<?	
						$cons20="Select sum(Cantidad),IdSolicitud,AutoId from Consumo.Movimiento 
						where Compania='$Compania[0]' and AlmacenPpal='$AlmacenPpal' and TipoComprobante='Salidas'
						and Estado = 'AC' and IdSolicitud is not NULL 
						Group By IdSolicitud,AutoId";
						$res20=ExQuery($cons20);
						while($fila20=ExFetch($res20))
						{
							$SalidasProd[$fila20[1]][$fila20[2]]=$fila20[0];
						}
						$cons2="Select SolicitudConsumo.AutoId,CantAprobada,NombreProd1,Presentacion,UnidadMedida,IdSolicitud from Consumo.SolicitudConsumo,Consumo.CodProductos where 
						SolicitudConsumo.AutoId=CodProductos.AutoId and CodProductos.Compania='$Compania[0]' and CodProductos.AlmacenPpal='$AlmacenPpal' and 
						SolicitudConsumo.Compania='$Compania[0]' and SolicitudConsumo.AlmacenPpal='$AlmacenPpal' and CodProductos.Anio=$Anio";
						$res2=ExQuery($cons2);
						while($fila2=ExFetch($res2))
						{
							$CantExistencias=$VrSaldoIni[$fila2[0]][0]+$VrEntradas[$fila2[0]][0]-$VrSalidas[$fila2[0]][0];
							if($fila2[1]-$SalidasProd[$fila2[5]][$fila2[0]] > 0)
							{
								$Pendientes[$fila2[5]][$fila2[0]]=array($fila2[0],"$fila2[2] $fila2[3] $fila2[4]",$fila2[1],$SalidasProd[$fila2[5]][$fila2[0]],$fila2[1]-$SalidasProd[$fila2[5]][$fila2[0]],$CantExistencias);
							}
							
						}
						$cons1="Select Codigo,CentroCostos from central.centroscosto where Compania='$Compania[0]' and Anio=$Anio";
						$res1 = ExQuery($cons1);
						while($fila1 = ExFetch($res1))
						{
							$CC[$fila1[0]] = $fila1[1];
						}
						$cons="Select IdSolicitud,SolicitudConsumo.Cedula,Usuario,SolicitudConsumo.CentroCostos,Fecha,PrimApe,SegApe,PrimNom,SegNom
						from Consumo.SolicitudConsumo,Central.Terceros 
						where Estado='Aprobada' and Terceros.Identificacion=SolicitudConsumo.Cedula and SolicitudConsumo.Compania='$Compania[0]' and AlmacenPpal='$AlmacenPpal' and Terceros.Compania='$Compania[0]' and Anio = $Anio
						Group By IdSolicitud,SolicitudConsumo.Cedula,Usuario,SolicitudConsumo.CentroCostos,Fecha,PrimApe,SegApe,PrimNom,SegNom
						Order By Usuario";
						//echo $cons;
						$res=ExQuery($cons);
						while($fila=ExFetch($res)){
							if($Pendientes[$fila[0]]){
								?>
								<tr>
									<td><?php echo $fila[0];?></td>
									<td>
										<a href="#" onclick="CerrarThis('<? echo $fila[1]?>','<? echo "$fila[5] $fila[6] $fila[7] $fila[8]"?>','<? echo strtoupper($CC[$fila[3]])?>','<? echo $fila[0]?>','<? echo $Anio?>')"><? echo "$fila[1] - $fila[2] </a>(".substr($fila[4],0,16).")";?>
									</td>
									<td><?php echo "$fila[3] - ".$CC[$fila[3]]; ?></td>
								</tr>
								<tr>
									<td colspan="4">
										<table class="tabla1" width="100%" style="font-size:11px;" <?php echo $borderTabla1Mentor; echo $bordercolorTabla1Mentor; echo $cellspacingTabla1Mentor; echo $cellpaddingTabla1Mentor; ?>>
											<tr>
												<td width="5%" class="encabezado1HorizontalInvertido">COD.</td>
												<td class="encabezado1HorizontalInvertido">PRODUCTO</td>
												<td width="8%" class="encabezado1HorizontalInvertido">APROB.</td>
												<td width='8%' class="encabezado1HorizontalInvertido">ENTR.</td>
												<td width='8%' class="encabezado1HorizontalInvertido">PEND.</td>
												<td width='8%' class="encabezado1HorizontalInvertido">EXIST.</td>
											</tr>
											<?php
								
												foreach($Pendientes[$fila[0]] as $Solicitud){
													if(!$Solicitud[3]){$Solicitud[3] = 0;}
													echo "<tr>";
														echo "<td>$Solicitud[0]</td>";
														echo "<td>$Solicitud[1]</td><td style='text-align:right;'>".number_format($Solicitud[2],2)."</td>";
														echo "<td style='text-align:right;'>".number_format($Solicitud[3],2)."</td>";
														echo "<td style='text-align:right;'>".number_format($Solicitud[4],2)."</td>";
														echo "<td style='text-align:right;'>".number_format($Solicitud[5],2)."</td>";
													echo "</tr>";
												}
											?>	
										</table>
									</td>
								</tr>
								<?php
							}
						}
					?>
				</table>
			</div>	
		</body>
</html>
