		<?	
			if($DatNameSID){session_name("$DatNameSID");}
			session_start();
			include("Funciones.php");
			include_once("General/Configuracion/Configuracion.php");
			$MatIndicadores=NULL;		
			unset($MatIndicadores);
			if($Eliminar){
				if($Item)
				{
					$eliminar="DELETE FROM historiaclinica.indicadoresxhc WHERE Item=$Item and Compania='$Compania[0]' and TipoFormato='$TF'
					and Formato='$NewFormato' and TablaFormato='$TablaFormato' and VrItem='$Valor'";
					//echo $eliminar;
					$reselim=ExQuery($eliminar);
				}
				/*echo"<script languaje='javascript'> location.href='IndicadoresxFormato.php?DatNameSID=$DatNameSID&NewFormato=$NewFormato&TF=$TF&TablaFormato=$TablaFormato';</script>";*/
			}	
			$consulta="SELECT Item,vritem,Indicador,tablaformato,fechacrea FROM historiaclinica.indicadoresxhc where Compania='$Compania[0]' and Formato='$NewFormato' and TipoFormato='$TF' and TablaFormato='$TablaFormato' and vritem=''";
			$res=ExQuery($consulta);
			//echo $consulta;		
			if(ExNumRows($res)>0)
			{		
				$fila=ExFetch($res);
				$MatIndicadores[$fila[0]][$fila[1]]=array($fila[0],$fila[1],$fila[2],$fila[3],$fila[4],"");
				$Deshab="disabled";
				//echo "1<br>";
			}
			else
			{
				$consulta="select itemsxformatos.id_item,vritem,Indicador,
				tablaformato,fechacrea,itemsxformatos.item from HistoriaClinica.indicadoresxhc,historiaclinica.itemsxformatos
				where indicadoresxhc.Formato='$NewFormato' and indicadoresxhc.TipoFormato='$TF'	and itemsxformatos.id_item=indicadoresxhc.item
				and itemsxformatos.TipoFormato='$TF' and itemsxformatos.Formato='$NewFormato' and indicadoresxhc.compania='$Compania[0]' 	
				and itemsxformatos.compania='$Compania[0]'";
				//echo "2<br>";
				//echo $consulta;		
				$res=ExQuery($consulta);	
				while($fila=ExFetch($res))
				{
					$MatIndicadores[$fila[0]][$fila[1]]=array($fila[0],$fila[1],$fila[2],$fila[3],$fila[4],$fila[5]);
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
				<link rel="stylesheet" type="text/css" href="../../General/Estilos/estilos.css">
				<script language='javascript' src="/Funciones.js"></script>
			</head>
			
			<body <?php echo $backgroundBodyMentor; ?>>
				<div <?php echo $alignDiv2Mentor; ?> class="div2">
					<form name="FORMA" method="post">
						<input type="Hidden" name="NewFormato" value="<? echo $NewFormato?>">
						<input type="Hidden" name="TF" value="<? echo $TF?>">
						<input type="Hidden" name="DatNameSID" value="<? echo $DatNameSID?>">
						<input type="hidden" name="TablaFormato" value="<? echo $TablaFormato?>" />
						
						<table class="tabla2"  <?php echo $borderTabla2Mentor; echo $bordercolorTabla2Mentor; echo $cellspacingTabla2Mentor; echo $cellpaddingTabla2Mentor; ?>>        
							<tr>   
								<td class="encabezado2Horizontal">ITEM</td>
								<td class="encabezado2Horizontal">VALOR</td>
								<td class="encabezado2Horizontal" colspan="2">INDICADOR</td>
							 </tr>
							  <? 
								if(!empty($MatIndicadores))
								{		
									foreach($MatIndicadores as $IdIt)
									{
										foreach($IdIt as $Ind)
										{
											//echo $Ind[0]." -- ".$Ind[1]." -- ".$Ind[2]." -- ".$Ind[3]." -- ".$Ind[4]." -- ".$Ind[5]."<br>";
											?>
											<tr bgcolor="white">
											<td><span class="style5"><? if($Ind[5]){ echo $Ind[5];}else{echo "NA";}?></span></td>
											<td><span class="style5"><? if($Ind[1]){ echo $Ind[1];}else{echo "NA";}?></span></td>
											<td><span class="style5"><? echo $Ind[2]?></span></td>               
											<td><a href='#' onClick="if(confirm('Desea eliminar el identificador <? echo $Ind[2]?>?')){location.href='IndicadoresxFormato.php?DatNameSID=<? echo $DatNameSID?>&Eliminar=1&Item=<? echo $Ind[0]?>&Indicador=<? echo $Ind[2]?>&NewFormato=<? echo $NewFormato?>&TF=<? echo $TF?>&TablaFormato=<? echo $TablaFormato?>&Valor=<? echo $Ind[1]?>'}"><img src='/Imgs/b_drop.png' border=0></a></td>
											</tr>
											<?		
										}
									}	
								}
								else
								{
									?>
									<tr>
										<td colspan="4" class="mensaje1">No se encontraron indicadores asociados con el formato!!!</td>
									</tr>
									<?	
								}
								?> 
						</table>
						<input type="button" class="boton2Envio" name="Nuevo" value="Nuevo" <? echo $Deshab?> onClick="location.href='NuevoIndicadorxFormato.php?DatNameSID=<? echo $DatNameSID?>&NewFormato=<? echo $NewFormato?>&TF=<? echo $TF?>&TablaFormato=<? echo $TablaFormato?>'">	        	
					</form>
				</div>
			</body>
		</html>	