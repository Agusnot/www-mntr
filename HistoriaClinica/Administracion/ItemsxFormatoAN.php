		<?
			if($DatNameSID){session_name("$DatNameSID");}
			session_start();
			include("Funciones.php");
			include_once("General/Configuracion/Configuracion.php");
			if($Habilitar){
			
				$cons="Select orden from HistoriaClinica.ItemsxFormatos 
				where compania='$Compania[0]' and tipoformato='$TF' and Formato='$NewFormato' and estado='AC' and pantalla=$Pantalla Group By orden Order By orden Desc";
				$res=ExQuery($cons);
				$fila=ExFetch($res);
				$Orden=$fila[0]+1;
				$cons="update HistoriaClinica.ItemsxFormatos set estado='AC',orden=$Orden 
				where compania='$Compania[0]' and  Id_Item=$IdItem and tipoformato='$TF' and Formato='$NewFormato' and estado='AN'";
				//echo $cons;
				$res=ExQuery($cons);
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
			</head>

			<body <?php echo $backgroundBodyMentor; ?>>
				<div align="center">
					<form name="FORMA" method="post">
						<table width="100%" class="tabla3"   <?php echo $borderTabla3Mentor; echo $bordercolorTabla3Mentor; echo $cellspacingTabla3Mentor; echo $cellpaddingTabla3Mentor; ?>>
							<tr>
										
								<td colspan="30" style="text-align:left;">
									<table cellpadding="2" cellspacing="0" border="0" style="font-size:12px;">
										<tr>
											<td> <label for="ElementosActivos">Activos </label> </td>
											<td> <input type="radio" name="Elementos" id="ElementosActivos" onClick="location.href='ItemsxFormato.php?DatNameSID=<? echo $DatNameSID?>&NewFormato=<? echo $NewFormato?>&TF=<? echo $TF?>'"> </td>
											<td> <label for="ElementosInactivos">Inactivos </label> </td>
											<td> <input type="radio" name="Elementos" id="ElementosInactivos" checked></td>
										</tr>
									</table>	
											
								</td>
							</tr>
							<tr>
								  <td class="encabezado2Horizontal">PANT.</td>
								  <td class="encabezado2Horizontal">ITEM </td>
								  <td class="encabezado2Horizontal">T. DATO </td>
								  <td class="encabezado2Horizontal"> LIM. INF.</td>
								  <td class="encabezado2Horizontal">LIM. SUP.</td>
								  <td class="encabezado2Horizontal">LONG.</td>
								  <td class="encabezado2Horizontal">TIPO CONTROL</td>
								  <td class="encabezado2Horizontal">ANCHO</td>
								  <td class="encabezado2Horizontal">ALTO</td>
								  <td class="encabezado2Horizontal">OBL.</td>
								  <td class="encabezado2Horizontal">LS</td>
								  <td class="encabezado2Horizontal">CF</td>  
							</tr>  
						   <?
							$cons="Select * from HistoriaClinica.ItemsxFormatos where Formato='$NewFormato' and TipoFormato='$TF' and Compania='$Compania[0]' and estado='AN'
							order by Pantalla,Orden,Id_Item";
							//echo $cons;
							$res=ExQuery($cons,$conex);
							while($fila=ExFetchArray($res))
							{
								if($Fondo==1){$BG="#EEF6F6";$Fondo=0;}
								else{$BG="";$Fondo=1;}
								if($fila['item']!='Diagnostico'&&$fila['item']!="CUP No Pos"&&$fila['item']!="Medicamento No POS"){?>
								<tr bgcolor="<?echo $BG?>" align="center">
								
									<td><? echo $fila['pantalla']?></td>
									<td><? echo $fila['item']?></td>
									<td><? echo $fila['tipodato']?></td>
									<td><? echo $fila['liminf']?></td>
									<td><? echo $fila['limsup']?></td>
									<td><? echo $fila['longitud']?></td>
									<td><? if($fila['titulo']!=''){echo "Titulo";}echo $fila['tipocontrol']?></td>
									<td><? echo $fila['ancho']?></td>
									<td><? echo $fila['alto']?></td>
									<td><? if($fila['obligatorio']=='1'){echo "Si";}else{ echo "No";}?></td>
									<td><? if($fila['lineasola']=='1'){echo "Si";}else{ echo "No";}?></td>
									<td><? if($fila['cierrafila']=='1'){echo "Si";}else{ echo "No";}?></td>
									<td><img src="../../Imgs/s_process.png" title="Habilitar" style="cursor:hand"
											onClick="if(confirm('Desea habilitar este elemento?')){location.href='ItemsxFormatoAN.php?DatNameSID=<? echo $DatNameSID?>&Habilitar=1&NewFormato=<? echo $NewFormato?>&IdItem=<? echo $fila['id_item']?>&Pantalla=<? echo $fila['pantalla']?>&TF=<? echo $TF?>'}"></td>
								</tr>  
						<?		}
							}?>	        
						</table>
						<input type="hidden" name="DatNameSID" value="<? echo $DatNameSID?>">
					</form>  
				</div>	
			</body>
		</html>
