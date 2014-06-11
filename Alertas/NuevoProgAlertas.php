		<?php
			if($DatNameSID){session_name("$DatNameSID");}
			session_start();
			include("Funciones.php");
			include_once("General/Configuracion/Configuracion.php");
			$ND=getdate();
			
			
			
			
			function  reemplazarOcurrencia($cadenaBusqueda,$cadenaReemplazo)  {
				// Busca y reemplaza ocurrencias en una tabla
					
					$cons = "UPDATE instruccionconsql SET codigo = replace( instruccionconsql,'". $cadenaBusqueda."'".",".'"'. $cadenaReemplazo.'")';
					$res = ExQuery($cons);
					echo "CONSULTA". $cons;
					
			}
			
			
			function seleccionarModulosActivos(){
				$cons = "SELECT * FROM Central.AccesoxModulos WHERE ind_estado = '0'";
				$res = ExQuery($cons);
				return $res;
			}
			
			function eliminarAlertasxModulos($id, $compania){
				$cons = "DELETE FROM Alertas.AlertasxModulos WHERE compania= '$compania' AND id= '$id'";
				ExQuery($cons);	
			}
	
			function insertarAlertaxModulo($id, $modulo, $madre, $compania){
				$cons = "INSERT INTO Alertas.AlertasxModulos (id, modulo, madre, compania) VALUES ( '$id', '$modulo', '$madre', '$compania')";
				ExQuery($cons);	
			}
	
			function asociarAlertasxModulos($id, $compania){
				$listado = seleccionarModulosActivos();
					while($fila = ExFetchArray($listado)){
						//$modulo = $fila['perfil'];
						//$madre = $fila['madre'];
						$modulo= str_replace( "'",'', $fila['perfil']);
						$madre= str_replace( "'",'', $fila['modulogr']);
						
						insertarAlertaxModulo($id, $modulo, $madre, $compania);
					}
			}
				
				
				
			if ($OpIgual=="="){ $SOpIgual=" selected ";}
			else{ $SOpLike=" selected ";}



			if($Guardar)
			{
				//$InstruccionSQL = "SELECT * FROM ".$BaseDatos.".".$Tabla." WHERE ".$Campo." ".$Op." |".$ValorCampo."| and Compania=|$Compania[0]|";
				//echo $InstruccionSQL;
				
				$InstrucSQL= str_replace("\'","|",$InstrucSQL);
				
				if(!$Edit)
				{
					$cons="Select Id from Alertas.AlertasProgramadas where Compania='$Compania[0]' order by Id Desc";
					$res = ExQuery($cons);
					$fila = ExFetch($res);
					$Id = $fila[0] + 1;
					 
					$cons = "Insert into Alertas.AlertasProgramadas
					
					(Id,Compania,UsuarioCrea,FechaCrea,InstruccionSQL,MsjAlerta,Archivo,descripcion,Bloqueante) values
					('$Id','$Compania[0]','$usuario[0]','$ND[year]-$ND[mon]-$ND[mday] $ND[hours]:$ND[minutes]','$InstrucSQL','$MsjAlerta','$Archivo','$Descripcion','$Bloqueante')";
					
					eliminarAlertasxModulos($Id, $Compania[0]);
					asociarAlertasxModulos($Id, $Compania[0]);
				}
				else
				{
					$cons = "Update Alertas.AlertasProgramadas set
					InstruccionSQL='$InstrucSQL',MsjAlerta='$MsjAlerta',Archivo='$Archivo', Estado='$Estado',descripcion='$Descripcion'
					,UsuarioMod='$usuario[0]',FechaMod='$ND[year]-$ND[mon]-$ND[mday] $ND[hours]:$ND[minutes]',Bloqueante='$Bloqueante'		
					where Id='$Id' and Compania='$Compania[0]'";
					
					eliminarAlertasxModulos($Id, $Compania[0]);
					asociarAlertasxModulos($Id, $Compania[0]);
					
				}
				//echo $cons;
				$res=ExQuery($cons);	
				
				?>
				<script language="javascript">location.href="ProgAlertas.php?DatNameSID=<? echo $DatNameSID?>";</script>
				<?
			}
			
			if($Edit=="1"){
				$cons="Select InstruccionSQL,MsjAlerta,estado,descripcion,Bloqueante,archivo from Alertas.AlertasProgramadas where Id='$Id' and Compania='$Compania[0]'";
				$res=ExQuery($cons);
				$fila=ExFetch($res);		
				$InstrucSQL=$fila[0];$MsjAlerta=$fila[1];$Estado=$fila[2]; $Descripcion=$fila[3]; $Bloqueante=$fila[4]; $Archivo=$fila[5];
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
				<script language="javascript" src="/Funciones.js"></script>
				<script language="javascript">
					function Validar()
					{
						if(document.FORMA.InstrucSQL.value==""){alert("Debe digitar la consulta SQL!!!");return false;}		
						if(document.FORMA.MsjAlerta.value==""){alert("Debe digitar el mensaje de la alerta!!!");return false;}
					}
				</script>
			</head>
			
			<body <?php echo $backgroundBodyMentor; ?>>
				<?php
					$rutaarchivo[0] = "ADMINISTRADOR";
					$rutaarchivo[1] = "ALERTAS";
					$rutaarchivo[2] = "NUEVA ALERTA";
					
					mostrarRutaNavegacionEstatica($rutaarchivo);					
				?>
				
				<form name="FORMA" method="post" onSubmit="return Validar()">
					
					<div <?php echo $alignDiv2Mentor; ?> class="div2" >
							<table class="tabla2"  <?php echo $borderTabla2Mentor; echo $bordercolorTabla2Mentor; echo $cellspacingTabla2Mentor; echo $cellpaddingTabla2Mentor; ?>>
								<tr>
									<td class="encabezado2Horizontal" colspan="2">
										CREACI&Oacute;N NUEVA ALERTA
									</td>									
								</tr>
								<tr>
									<td class= "encabezado2VerticalInvertido">INSTRUCCI&Oacute;N SQL</td>
									<td>
										<textarea name="InstrucSQL" cols="70" rows="8"><? echo $InstrucSQL?></textarea>
									</td>
								</tr>
							   
								<tr>
									<td class= "encabezado2VerticalInvertido" > MENSAJE</td>
									<td><input type="text" name="MsjAlerta" value="<? echo $MsjAlerta ?>" style="width:100%;" /></td>
								</tr>
								<tr>
									<td class= "encabezado2VerticalInvertido"> ESTADO </td>
									<td>
										<select name="Estado">
											<option <? if($Estado=="Activo"){echo "selected";} ?> value="Activo">Activo</option>
											<option <? if($Estado=="Inactivo"){echo "selected";} ?> value="Inactivo">Inactivo</option>
										</select>
									</td>
								</tr>
								<tr>
									<td class= "encabezado2VerticalInvertido">ARCHIVO</td>
									<td><input type="text" name="Archivo" value="<? echo $Archivo?>" style="width:100%;"/></td>
								</tr>
								<tr>
									<td class= "encabezado2VerticalInvertido">DESCRIPCI&Oacute;N</td>
									<td>
										<textarea name="Descripcion" cols="70" rows="6"><? echo $Descripcion?></textarea>
									</td>
									
								</tr>
								<tr>
									<td class= "encabezado2VerticalInvertido"> BLOQUEANTE </td>
									<td>
										<select name="Bloqueante">
											<option value="No" <? if($Bloqueante=="No"){?> selected<? }?>>No</option>
											<option value="Si" <? if($Bloqueante=="Si"){?> selected<? }?>>Si</option>
										</select>
									</td>
								</tr>
								<tr align="center">
									<td colspan="4">
										<input type="submit" value="Guardar" name="Guardar" class="boton2Envio"/>
										<input type="button" name="Cancelar" value="Cancelar" class="boton2Envio" onClick="location.href='ProgAlertas.php?DatNameSID=<? echo $DatNameSID?>'" />
									</td>
								</tr>
							</table>
					</div>
					<input type="hidden" name="Insertar">
					<input type="hidden" name="Editar" value="<? echo $Editar?>">
					<input type="hidden" name="Id" value="<? echo $Id?>">
					<input type="hidden" name="DatNameSID" value="<? echo $DatNameSID?>">
				</form>
			
			
			
			</body>
		</html>