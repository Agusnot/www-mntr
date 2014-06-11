		<?php
			if($DatNameSID){session_name("$DatNameSID");}
			session_start();
			include("Funciones.php");
			include_once("General/Configuracion/Configuracion.php");
			if(!$Acciones){
				$cons="select accion,responsable,fechauno,fechaaplaza,tipoaccion from pacienteseguro.accionespropuestas where compania='$Compania[0]' and idsuceso=$IdSuceso";
				$res=ExQuery($cons);
				while($fila=ExFetch($res))
				{
					$Acciones=$Acciones."***$fila[0];;;$fila[1];;;$fila[2];;;$fila[4]";
				}							
			}
			
			$cons="select nombre,usuario from central.usuarios";
			$res=ExQuery($cons);
			while($fila=ExFetch($res))
			{
				$Usus[$fila[1]]	=$fila[0];
			}
			if($Guardar)
			{
				$ND=getdate();
				$Accs=explode("***",$Acciones);
				$cons="delete from pacienteseguro.accionespropuestas where compania='$Compania[0]' and idsuceso=$IdSuceso";
				$res=ExQuery($cons);
				foreach($Accs as $AccsSub)
				{
					if($AccsSub){
						$Acs=explode(";;;",$AccsSub);				
						$cons="insert into pacienteseguro.accionespropuestas (compania,idsuceso,accion,responsable,fechauno,usucrea,fechacrea,tipoaccion) values 
						('$Compania[0]',$IdSuceso,'$Acs[0]','$Acs[1]','$Acs[2]','$usuario[1]','$ND[year]-$ND[mon]-$ND[mday] $ND[hours]:$ND[minutes]:$ND[seconds]','$Acs[3]')";
						//echo $cons."<br>";
						$res=ExQuery($cons);
						$cons="select id from central.correos where compania='$Compania[0]' order by id desc"; 
						$res=ExQuery($cons); $fila=ExFetch($res); $Id=$fila[0]+1;
						
						$Msj="Se le asignado la accion $Acs[0] para la resolucion de un suceso de Paciente Seguro para lo cual dispone hasta $Acs[2] para ser resuelto. 
						Para mayor informacion comuniquese con la persona que le envia este correo";
						$cons="insert into central.correos (compania,usucrea,fechacrea,usurecive,mensaje,id,asunto) values
						('$Compania[0]','$usuario[1]','$ND[year]-$ND[mon]-$ND[mday] $ND[hours]:$ND[minutes]:$ND[seconds]','$Acs[1]','$Msj',$Id,'Paciente Seguro')";
						$res=ExQuery($cons);
					}
				}
				$cons="update pacienteseguro.sucesos set accionespropuestas=1,fechaaccionespropuestas='$ND[year]-$ND[mon]-$ND[mday] $ND[hours]:$ND[minutes]:$ND[seconds]'
				,usuaccionespropuestas='$usuario[1]' where compania='$Compania[0]' and idsuceso=$IdSuceso";
				$res=ExQuery($cons);
				?>
				<script language="javascript">
					parent.document.FORMA.submit();
				</script>
		<?	}
			if($Agregar==1)
			{		
				$Acciones=$Acciones."***$Accion;;;$Responsable;;;$Fecha;;;$TipoAccion";		
				//echo $Acciones;			
				$Agregar="";
			}
			if($Eliminar)
			{
				$Acciones=str_replace($Eliminar,"",$Acciones);	
				$Eliminar="";
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
				<script language='javascript' src="/calendario/popcalendar.js"></script>
				<script language="javascript" src="/Funciones.js"></script>
				<script language="javascript">
					function CerrarThis()
					{
						parent.document.getElementById('FrameOpener').style.position='absolute';
						parent.document.getElementById('FrameOpener').style.top='1px';
						parent.document.getElementById('FrameOpener').style.left='1px';
						parent.document.getElementById('FrameOpener').style.width='1';
						parent.document.getElementById('FrameOpener').style.height='1';
						parent.document.getElementById('FrameOpener').style.display='none';
						//parent.document.FORMA.submit();
					}
					function Validar2()
					{
						if(document.FORMA.Accion.value==""){alert("Debe digitar la accion!!!"); return false;}
						if(document.FORMA.Responsable.value==""){alert("Debe seleccionar el responsable!!!"); return false;}
						if(document.FORMA.Fecha.value==""){alert("Debe seleccionar la fecha!!!"); return false;}
						document.FORMA.Agregar.value="1";
						document.FORMA.submit();
					}
					function EliminaAccion(Linea)
					{
						document.FORMA.Eliminar.value=Linea;
						document.FORMA.submit();
					}
					function Validar()
					{
						if(document.FORMA.Acciones.value==""){alert("Debe agregrar almenos una accion!!!"); return false;}
					}
				</script>	
			</head>

		<body <?php echo $backgroundBodyMentor; ?>>
			<form name="FORMA" method="post" onSubmit="return Validar()">
					<table class="tabla2"   <?php echo $borderTabla2Mentor; echo $bordercolorTabla2Mentor; echo $cellspacingTabla2Mentor; echo $cellpaddingTabla2Mentor; ?>>
						<tr>	
							<td class="encabezado2Horizontal">ACCIONES</td>
							<td class="encabezado2Horizontal">TIPO DE ACCI&Oacute;N </td>
							<td class="encabezado2Horizontal">RESPONSABLE</td>
							<td class="encabezado2Horizontal">FECHA LIMITE</td>
							<td class="encabezado2Horizontal">&nbsp;</td>
						</tr>
						<tr>    	
							<td><input type="text" name="Accion" onKeyDown="xLetra(this)" onKeyPress="xLetra(this)" onKeyUp="xLetra(this)" style="width:300"></td>
							<td><select name="TipoAccion" id="TipoAccion">
							  <option selected>-</option>
							  <option value="Preventiva">Preventiva</option>
							  <option value="Correctiva">Correctiva</option>
							</select>        </td>
							<td>
							<?	$cons="select nombre,usuario from central.usuarios where usuario is not null $Resp order by nombre";
								$res=ExQuery($cons);?>
								<select name="Responsable">
									<option></option>
								<?	while($fila=ExFetch($res))
									{
										echo "<option value='$fila[1]'>".strtoupper($fila[1])."</option>";
									}?>
								</select>       	</td>
							<td><input type="text" name="Fecha" readonly onClick="popUpCalendar(this, FORMA.Fecha, 'yyyy-mm-dd')" style="width:90px"> </td>
							<td><button title="Agregar" onClick="Validar2()"><img src="/Imgs/b_check.png"></button></td>
						</tr>
					<?	if($Acciones)
						{
							$Accs=explode("***",$Acciones);
							foreach($Accs as $AccsSub)
							{
								if($AccsSub){
									$Acs=explode(";;;",$AccsSub);?>
									<tr><td><? echo $Acs[0]?></td>
									  <td><? echo $Acs[3]?></td>
									  <td><? echo $Usus[$Acs[1]]?></td><td><? echo $Acs[2]?></td>
									<td><button title="Eliminar" 
										onClick="if(confirm('¿Esta seguro de eliminar este registro?')){EliminaAccion('<? echo "***$AccsSub";?>')}"
										><img src="/Imgs/b_drop.png"></button></td>
						<?		}
							}
						}?>
						<tr align="center">
							<td colspan="6"><input class="boton2Envio" type="submit" value="Guardar" name="Guardar"/>
							<input type="button" class="boton2Envio" value="Cancelar" onClick="CerrarThis()"/></td>
						</tr>
					</table>
					<input type="hidden" name="Eliminar" value="">
					<input type="hidden" name="Agregar" value="">
					<input type="hidden" name="Acciones" value="<? echo $Acciones?>">
					<input type="hidden" name="IdSuceso" value="<? echo $IdSuceso?>">
					<input type="hidden" name="DatNameSID" value="<? echo $DatNameSID?>">
			</form>    
		</body>
		</html>
