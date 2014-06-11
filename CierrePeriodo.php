		<?
			if($DatNameSID){session_name("$DatNameSID");}
			session_start();
			include("Funciones.php");
			include_once("General/Configuracion/Configuracion.php");
			$ND=getdate();
			if($Cerrar)	{
				if($Mes){
					$cons="Insert into Central.CierrexPeriodos (Compania,Anio,Mes,Modulo) values('$Compania[0]','$AnioAc1','$Mes','$ModOrigen')";
					$res=ExQuery($cons);
					//echo $cons."<br><br>";
					echo ExError($res);
				}
				
			}
			
			if($Abrir)	{
				if($Mes){
					$cons="Delete from Central.CierrexPeriodos where Compania='$Compania[0]' and Anio='$AnioAc1' and Mes='$Mes' and Modulo = '$ModOrigen'";
					$res=ExQuery($cons);
					echo ExError($res);
				}
			}
			
			if($RetirarCierreFiscal){
				$cons="Select Comprobante from Contabilidad.Comprobantes where Cierre='1' and Compania='$Compania[0]'";

				$res=ExQuery($cons);
				if(ExNumRows($res)>1){echo "<em>Existe mas de un comprobante marcado como Cierre, marque un solo comprobante para continuar!!!</em>";exit;}
				if(ExNumRows($res)==0){echo "<em>No hay comprobante marcado para cierre, proceda a marcarlo para poder realizar el cierre!!!</em>";exit;}
				$fila=ExFetch($res);
				$ComprobanteDestino=$fila[0];
				$cons="Delete from Contabilidad.Movimiento where Comprobante='$ComprobanteDestino' and Fecha>='$AnioAc1-01-01' and Fecha<='$AnioAc1-12-31' and Compania='$Compania[0]'";

				$res=ExQuery($cons);
				echo ExError($res);
				$cons="Update Central.CierrexPeriodos set CierreFiscal=0 where Anio=$AnioAc1 and Compania='$Compania[0]'";
				$res=ExQuery($cons);
				echo ExError($res);
			}
		?>
	
	<html>
		<head>
			<?php echo $codificacionMentor; ?>
			<?php echo $autorMentor; ?>
			<?php echo $titleMentor; ?>
			<?php echo $iconMentor; ?>
			<?php echo $shortcutIconMentor; ?>
			<link rel="stylesheet" type="text/css" href="General/Estilos/estilos.css">	
		</head>	
	
		<body <?php echo $backgroundBodyMentor; ?>>
			<?php
				if (strtoupper($ModOrigen)=="CONSUMO"){
					$rutaarchivo[0] = "ALMAC&Eacute;N";
					$rutaarchivo[1] = "PROCESOS DE CONSUMO";
					$rutaarchivo[2] = "CIERRE POR PERIODOS";
				} 
				else {
					$rutaarchivo[0] = "CONTABILIDAD";
					$rutaarchivo[1] = "PROCESOS CONTABLES";
					$rutaarchivo[2] = "CIERRE MENSUAL";
				}
				mostrarRutaNavegacionEstatica($rutaarchivo);
			?>
			
			<div <?php echo $alignDiv2Mentor; ?> class="div2">
				<form name="FORMA" method="post">
					<input type="hidden" name="ModOrigen" value="<? echo $ModOrigen?>">
					<input type="hidden" name="Mes" value="" />
					
					<table class="tabla2" width="300px"  <?php echo $borderTabla2Mentor; echo $bordercolorTabla2Mentor; echo $cellspacingTabla2Mentor; echo $cellpaddingTabla2Mentor; ?>>
						<tr>
							<td colspan="3" class="encabezado2Horizontal">
								CIERRE POR PERIODOS 
								<?if($ModOrigen=="Consumo"){
									echo "CONSUMO";
								}
								else{
									echo "CONTABILIDAD Y PRESUPUESTO";
								}?>
							</td>
						</tr>
						<tr>
							<td colspan="3" class="encabezadoGrisaceo" style="text-align:center;">A&Ntilde;O							
								<select name="AnioAc1" onChange="document.FORMA.submit();">
									<?
											if(!$AnioAc1){$AnioAc1=$ND[year];}
											$cons = "Select Anio from Central.Anios where Compania = '$Compania[0]' Order By Anio DESC LIMIT 20";
											$res = ExQuery($cons);
											while($fila = ExFetch($res))
											{
												if($fila[0]==$AnioAc1){echo "<option selected value=$fila[0]>$fila[0]</option>";}
												else{echo "<option value=$fila[0]>$fila[0]</option>";}	
											}
									?>
								</select>
							</td>	
						</tr>
						<tr>
							<td  class="encabezado2HorizontalInvertido">MES</td>
							<td  class="encabezado2HorizontalInvertido">ESTADO</td>
							<td  class="encabezado2HorizontalInvertido">&nbsp;</td>
						</tr>
						<?
							for($i=1;$i<=12;$i++){
								$cons="Select * from Central.Meses where Numero=$i";
								$res=ExQuery($cons);
								$fila=ExFetch($res);
								echo "<tr>";
									echo "<td style='text-align:left;padding-left:10px;'>".strtoupper($fila[0])."</td>";
								
								$cons2="Select CierreFiscal from Central.CierrexPeriodos where Mes=$i and Anio=$AnioAc1 and Compania='$Compania[0]' and Modulo='$ModOrigen'";
								$res2=ExQuery($cons2);echo ExError($res2);
								if(ExNumRows($res2)==1)	{
									$fila2=ExFetch($res2);
									if($fila2[0]==1){
										echo "<td style='color:FF6600;font-weight:bold;font-size:11px;text-align:center;'>Cierre Fiscal</td>";
										$CierreFiscal=1;
									}
									else{
										echo "<td style='color:FF0000;font-weight:bold;font-size:11px;text-align:center;'>Cerrado</td>";
										echo "<td><a href='CierrePeriodo.php?DatNameSID=$DatNameSID&Abrir=1&AnioAc1=$AnioAc1&Mes=$i&ModOrigen=$ModOrigen'><img alt='Abrir Periodo' border=0 src='/Imgs/b_usradd.png'></a></td>";
									}
								}
								else{
									echo "<td style='color:002147;font-weight:bold;font-size:11px;text-align:center;'>Abierto</td>";
									echo "<td><a href='CierrePeriodo.php?DatNameSID=$DatNameSID&Cerrar=1&AnioAc1=$AnioAc1&Mes=$i&ModOrigen=$ModOrigen'><img alt='Cerrar Periodo' border=0 src='/Imgs/b_usrdrop.png'></a></td>";
								}
							}
							if($CierreFiscal==1)
							{	echo "</table><br>";
								echo "<input type='Button' value='Retirar Cierre Fiscal' onclick=location.href='CierrePeriodo.php?DatNameSID=$DatNameSID&RetirarCierreFiscal=1&AnioAc1=$AnioAc1&ModOrigen=$ModOrigen'>";
							}
						?>
					</table>
					<input type="hidden" name="DatNameSID" value="<? echo $DatNameSID?>">
				</form>
			</div>	
		</body>
