		<?	
			if($DatNameSID){session_name("$DatNameSID");}
			session_start();
			include("Funciones.php");
			include_once("General/Configuracion/Configuracion.php");
			$Pantalla="Select Pantalla from HistoriaClinica.ItemsxFormatos where Formato='$NewFormato' Group By Id_Item,Pantalla Order By Id_Item Desc";
			$resp=ExQuery($Pantalla,$conex);
			$filap=ExFetch($resp);
			$Pantalla=$filap[0];
			if($Guardar)
			{ 
				$cons="Select * from HistoriaClinica.ItemsxFormatos where Formato='$NewFormato' and TipoFormato='$TF' and Pantalla=($Pantalla-1)";
				$res=ExQuery($cons);
				if((ExNumRows($res)>0)||($Pantalla==1))
				{		
					if(!$IdItem)
					{
						$cons="Select Id_Item from HistoriaClinica.ItemsxFormatos where compania='$Compania[0]' and TipoFormato='$TF' and
						Formato='$NewFormato' Group By Id_Item Order By Id_Item Desc";
						$res=ExQuery($cons);
						$fila=ExFetch($res);
						$IdItem=$fila[0]+1;
					}	
					if(!$Orden)
					{
						$cons="Select Orden from HistoriaClinica.ItemsxFormatos where Compania='$Compania[0]' and TipoFormato='$TF' and formato='$NewFormato' and Pantalla='$Pantalla' order by orden desc";
						$res=ExQuery($cons);
						$fila=ExFetch($res);$Orden=$fila[0];	
					}
					if(!$Modificar)
					{
						$cons="Select Item from HistoriaClinica.ItemsxFormatos where Compania='$Compania[0]' and Pantalla='$Pantalla' and Item='$TFTraerDe/$Traerde' and TipoFormato='$TF' and Formato='$NewFormato'";
						$res=ExQuery($cons);
						if(ExNumRows($res)==0)
						{		
							$cons="Insert into HistoriaClinica.ItemsxFormatos (Formato,Id_Item,Item,TipoFormato,Pantalla,SubFormato,Compania,Ancho,Alto,Orden) 
							values ('$NewFormato',$IdItem,'$TFTraerDe/$Traerde','$TF','$Pantalla','1','$Compania[0]',$Ancho,$Alto,$Orden)";
							$res=ExQuery($cons);	echo ExError($conex);
							?><script language="JavaScript">location.href="ItemsxFormato.php?DatNameSID=<? echo $DatNameSID?>&NewFormato=<? echo $NewFormato?>&TF=<? echo $TF?>"</script><?
						}
						else
						{
							?><script language="javascript">alert("El SubFormato que desea ingresar ya exite!!!");</script><?	
						}
					}
					else
					{
						$cons="Update HistoriaClinica.ItemsxFormatos set Pantalla='$Pantalla', Ancho=$Ancho, Alto=$Alto, Item='$TFTraerDe/$Traerde' where
						compania='$Compania[0]' and Formato='$NewFormato' and TipoFormato='$TF' and Id_Item=$IdItemAnt";
						$res=ExQuery($cons);
						?><script language="JavaScript">location.href="ItemsxFormato.php?DatNameSID=<? echo $DatNameSID?>&NewFormato=<? echo $NewFormato?>&TF=<? echo $TF?>"</script><?
					}
					?>			
		<?		}
				else
				{?>
					<script language="JavaScript">
						alert("La pantalla no tiene secuencia!!!");
					</script>
			<?	}
			}
			if($Eliminar)
			{
				$cons="Delete from HistoriaClinica.ItemsxFormatos where Formato='$NewFormato' and Id_Item='$IdItem' and TipoFormato='$TF'";
				$res=ExQuery($cons,$conex);$IdItem="";
				echo "<script language='JavaScript'> location.href='ItemsxFormato.php?DatNameSID=$DatNameSID&NewFormato=$NewFormato&TF=$TF'; </script>";
			}
			if($Modificar)
			{
				$cons="Select * from HistoriaClinica.ItemsxFormatos where compania='$Compania[0]' and Formato='$NewFormato' and Id_Item=$IdItem and TipoFormato='$TF' and subformato=1";
				$res=ExQuery($cons,$conex);
				$fila=ExFetchArray($res);
				if(!$Pantalla)$Pantalla=$fila['pantalla'];
				if(!$PantallaAnt)$PantallaAnt=$fila['pantalla'];
				if(!$IdItem)$IdItem=$fila['id_item'];
				if(!$IdItemAnt)$IdItemAnt=$fila['id_item'];
				$Item=$fila['item'];
				$TipoDato=$fila['tipodato'];
				$TF=$fila['tipoformato'];
				$LimInf=$fila['liminf'];
				$LimSup=$fila['limsup'];
				$Longitud=$fila['longitud'];
				$TipoControl=$fila['tipocontrol'];
				$Ancho=$fila['ancho'];
				$Alto=$fila['alto'];
				$Defecto=$fila['defecto'];
				$Parametro=$fila['parametro'];
				$Traerde=$fila['traerde'];
				$Obligatorio=$fila['obligatorio'];
				$CierraFila=$fila['cierrafila'];
				$LineaSola=$fila['lineasola'];
				$Titulo=$fila['titulo'];
				if(!$Orden)$Orden=$fila['orden'];
				$Item=explode("/",$Item);
				if(!$TFTraerDe)$TFTraerDe=$Item[0];
				if(!$TraerDe)$Traerde=$Item[1];
				if(!$TFTraerDeAnt)$TFTraerDeAnt=$Item[0];
				if(!$TraerDeAnt)$TraerDeAnt=$Item[1];
				
				/*$cons="Delete from HistoriaClinica.ItemsxFormatos where compania='$Compania[0]' and Formato='$NewFormato' and Id_Item=$IdItem 
				and TipoFormato='$TF' and subformato=1";
				//echo $cons;
				$res=ExQuery($cons);*/
			
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
			<div <?php echo $alignDiv2Mentor; ?> class="div2">
				<form name="FORMA" method="post">
					<input type="hidden" name="DatNameSID" value="<? echo $DatNameSID?>" />
					<input type="Hidden" name="IdItem" value="<? echo $IdItem?>">
					<input type="Hidden" name="NewFormato" value="<? echo $NewFormato?>">
					<input type="Hidden" name="Defecto">
					<input type="Hidden" name="TF" value="<? echo $TF?>">
					<input type="hidden" name="TFTraerDeAnt" value="<? echo $TFTraerDeAnt?>">
					<input type="hidden" name="TraerDeAnt" value="<? echo $TraerDeAnt?>">
					<input type="hidden" name="PantallaAnt" value="<? echo $PantallaAnt?>">    
					<input type="hidden" name="IdItemAnt" value="<? echo $IdItemAnt?>">    
					<input type="hidden" name="Orden" value="<? echo $Orden?>">
					
					  <table class="tabla2"  <?php echo $borderTabla2Mentor; echo $bordercolorTabla2Mentor; echo $cellspacingTabla2Mentor; echo $cellpaddingTabla2Mentor; ?>>
							<tr>
								<td class="encabezado2Horizontal">PANTALLA</td>
								<td class="encabezado2Horizontal">TIPO FORMATO</td>
								<td class="encabezado2Horizontal">FORMATO</td>
								<td class="encabezado2Horizontal">ANCHO</td>
								<td class="encabezado2Horizontal">ALTO</td>
							</tr>
							<tr><td><input type="text" name="Pantalla" style="width:60px;" value="<? echo $Pantalla?>"></td>
							<td>
								  <select name="TFTraerDe" onChange="FORMA.submit();" >
								  <option></option>
								<?
									$cons11="Select Nombre from HistoriaClinica.TipoFormato where Compania='$Compania[0]' order by Prioridad";
										$res11=ExQuery($cons11);
									while($fila11=ExFetch($res11))
									{
										if($TFTraerDe==$fila11[0]){echo "<option selected value='$fila11[0]'>$fila11[0]</option>";}
										else{echo "<option value='$fila11[0]'>$fila11[0]</option>";}
									}
								?>      
								  </select>
							</td>
							<td>      
								  <select name="Traerde" id="Traerde">
								  <option></option>
									<?
									$cons="Select Formato from HistoriaClinica.Formatos where TipoFormato='$TFTraerDe' and Compania='$Compania[0]' order by Formato";
									$res=ExQuery($cons,$conex);
									while($fila=ExFetch($res))
									{
										if($fila[0]==$Traerde){echo "<option selected value='$fila[0]'>$fila[0]</option>";}
										else{echo "<option value='$fila[0]'>$fila[0]</option>";}
									}
									?>
									</select>
							</td>
								<td><input type="text" name="Ancho" value="<? echo $Ancho?>" style="width:60px;"></td>
								<td><input type="text" name="Alto" value="<? echo $Alto?>" style="width:60px;"></td>

							</tr>
								<tr>
									<td colspan="5" scope="row" style="text-align:center;">
										<input type="submit" value="Guardar" name="Guardar" class="boton2Envio">
										<input type="button" value="Cancelar" class="boton2Envio" onClick="location.href='ItemsxFormato.php?DatNameSID=<? echo $DatNameSID?>&NewFormato=<? echo $NewFormato?>&TF=<? echo $TF?>'">
									</td>
								</tr>

					</table>
				</form>
				<iframe id="FrameOpener" name="FrameOpener" style="display:none" frameborder="0" height="1" style="border:#e5e5e5 ridge" >
			</div>	
		</body>
	</html>	