		<?	
			if($DatNameSID){session_name("$DatNameSID");}
			session_start();
			include("Funciones.php");
			include_once("General/Configuracion/Configuracion.php");
			if(!$Pantalla){
				$Pantalla="Select Pantalla from HistoriaClinica.ItemsxFormatos where Formato='$NewFormato' Group By Id_Item,Pantalla Order By Id_Item Desc";
				$resp=ExQuery($Pantalla,$conex);
				$filap=ExFetch($resp);
				$Pantalla=$filap[0];
			}
			
			if($Guardar)
			{ 
				$cons="Select * from HistoriaClinica.ItemsxFormatos where Formato='$NewFormato' and TipoFormato='$TF' and Pantalla=($Pantalla-1)";		
				$res=ExQuery($cons);
				if((ExNumRows($res)>0)||($Pantalla==1))
				{
					if(!$IdItem)
					{
						$cons="Select Id_Item from HistoriaClinica.ItemsxFormatos where Formato='$NewFormato' Group By Id_Item Order By Id_Item Desc";
						$res=ExQuery($cons,$conex);				
						$fila=ExFetch($res);
						$IdItem=$fila[0]+1;
						
						$cons="Select orden from HistoriaClinica.ItemsxFormatos 
						where compania='$Compania[0]' and tipoformato='$TF' and Formato='$NewFormato' and estado='AC' and pantalla=$Pantalla Group By orden Order By orden Desc";
						$res=ExQuery($cons,$conex);
						$fila=ExFetch($res);
						$Orden=$fila[0]+1;
						$cons="Insert into HistoriaClinica.ItemsxFormatos (Formato,Id_Item,Item,TipoFormato,Pantalla,Titulo,Compania,Orden) values 	
						('$NewFormato',$IdItem,'$Item','$TF','$Pantalla','1','$Compania[0]',$Orden)";
						//echo $cons;
					}
					else{
						$cons="Select orden from HistoriaClinica.ItemsxFormatos 
						where compania='$Compania[0]' and tipoformato='$TF' and Formato='$NewFormato' and estado='AC' and pantalla=$Pantalla Group By orden Order By orden Desc";
						$res=ExQuery($cons,$conex);
						$fila=ExFetch($res);
						//echo "<br>$cons";
						$Orden=$fila[0];
						$cons="update HistoriaClinica.ItemsxFormatos set Item='$Item',Pantalla=$Pantalla 
						where compania='$Compania[0]' and tipoformato='$TF' and Formato='$NewFormato' and estado='AC' and  Id_Item=$IdItem";
						//echo "<br>$cons";
					}
					
					
					//echo "<br>$cons";
					$res=ExQuery($cons);	
					$Modificar=0;?>
					<script language="JavaScript">location.href="ItemsxFormato.php?DatNameSID=<? echo $DatNameSID?>&NewFormato=<? echo $NewFormato?>&TF=<? echo $TF?>"</script>
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
				$cons="Delete from HistoriaClinica.ItemsxFormatos where Formato='$NewFormato' and Id_Item='$IdItem' and TipoFormato='$TF' LIMIT 1";
				//$res=ExQuery($cons,$conex);$IdItem="";
				echo "<script language='JavaScript'> location.href='ItemsxFormato.php?NewFormato=$NewFormato&TF=$TF'; </script>";
			}
			if($Modificar)
			{
				$cons="Select * from HistoriaClinica.ItemsxFormatos where Formato='$NewFormato' and Id_Item=$IdItem and TipoFormato='$TF'";
				$res=ExQuery($cons,$conex);
				$fila=ExFetchArray($res);
				$Pantalla=$fila['pantalla'];
				$IdItem=$fila['id_item'];
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

				$cons="Delete from ItemsxFormatos where compania='$Compania[0]' and Formato='$NewFormato' and Id_Item=$IdItem and TipoFormato='$TF' LIMIT 1";
				//$res=ExQuery($cons,$conex);
			
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
			<script language="JavaScript">
				
				function validar()
				{
					if(document.FORMA.Defecto.value==""){document.FORMA.Defecto.value=0}
					if(document.FORMA.LimSup.value==""){document.FORMA.LimSup.value=0}
					if(document.FORMA.LimInf.value==""){document.FORMA.LimInf.value=0}
					
					if(document.FORMA.Titulo.value=="")
					{
							alert("Por Favor Ingrese Todos los Datos!");return false;
					}
				}
				
			</script>
		</head>	
			
		<body <?php echo $backgroundBodyMentor; ?> onLoad="document.FORMA.Pantalla.focus();">
			<div <?php echo $alignDiv2Mentor; ?> class="div2">
				<form name="FORMA"  onSubmit="return validar()">
					 <table class="tabla2"  <?php echo $borderTabla2Mentor; echo $bordercolorTabla2Mentor; echo $cellspacingTabla2Mentor; echo $cellpaddingTabla2Mentor; ?>>					
						<tr>
							<td class="encabezado2Vertical">PANTALLA</td>
							<td><input type="text" name="Pantalla" style="width:30" value="<? echo $Pantalla?>"></td>
							<td class="encabezado2Vertical">T&Iacute;TULO</td>
							<td><input type="text" name="Item" style="width:200" value="<? echo $Item?>"></td>
						</tr>
						<tr>
							<td colspan="4" scope="row" style="text-align:center;">
								<input type="submit" class="boton2Envio" value="Guardar" name="Guardar">
								<input type="button" class="boton2Envio" value="Cancelar" onClick="location.href='ItemsxFormato.php?DatNameSID=<? echo $DatNameSID?>&NewFormato=<? echo $NewFormato?>&TF=<? echo $TF?>'">
							</td>
						</tr>
					 </table>
					<input type="Hidden" name="IdItem" value="<? echo $IdItem?>">
					<input type="Hidden" name="NewFormato" value="<? echo $NewFormato?>">
					<input type="Hidden" name="Defecto">
					<input type="Hidden" name="DatNameSID" value="<? echo $DatNameSID?>">
					<input type="Hidden" name="TF" value="<? echo $TF?>">
					<input type="hidden" name="Modificar" value="<? echo $Modificar?>">
				</form>
			</div>	
		</body>
	</html>		