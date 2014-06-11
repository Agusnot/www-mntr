		<?	
			if($DatNameSID){session_name("$DatNameSID");}
			session_start();
			include("Funciones.php");
			include_once("General/Configuracion/Configuracion.php");
			$ND=getdate();
			if($Cambiar)
			{
				$cons3="select numservicio from salud.servicios where compania='$Compania[0]' and cedula='$DocCorrecto' order by numservicio desc";
				$res3=ExQuery($cons3);
				if(ExNumRows($res3)>0)
				{
					$fila3=ExFetch($res3);
					$NumServDC=$fila3[0];
					$cons3="insert into salud.regcambioidpacientes (compania,fechacambio,usuario,docant,docnew,unifica) 
					values ('$Compania[0]','$ND[year]-$ND[mon]-$ND[mday] $ND[hours]:$ND[minutes]:$ND[seconds]','$usuario[1]','$DocErrado','$DocCorrecto',1)";
					$res3=ExQuery($cons3);
					$cons3="update odontologia.odontogramaproc set Identificacion='$DocCorrecto',numservicio=$NumServDC
					where compania='$Compania[0]' and Identificacion='$DocErrado'";
					$res3=ExQuery($cons3);			
					$cons="Select table_schema, table_name,Column_name from information_schema.columns Where Column_name='cedula' or
					Column_name='cedpaciente' or Column_name='paciente' order by table_schema,table_name";
					$res=ExQuery($cons);	
					while($fila=ExFetch($res))
					{
						//echo "$fila[0] - - $fila[1]<br>";
						$cons2="select Column_name from information_schema.columns where Column_name='compania' and table_name='$fila[1]' and table_schema='$fila[0]'";
						$res2=ExQuery($cons2);
						if(ExNumRows($res2)>0){$Comp="and compania='$Compania[0]'";}else{$Comp="";}
						if($fila[0]=="histoclinicafrms")
						{
							$Tbl=substr($fila[1],0,3);
							//echo $Tbl."<br>";
							if($Tbl=='tbl'){
								$cons2="select id_historia from histoclinicafrms.".$fila[1]." where compania='$Compania[0]' and cedula='$DocCorrecto'
								order by id_historia desc";	
								$res2=ExQuery($cons2);
								$fila2=ExFetch($res2); 
								if($fila2[0]){$Id_H=$fila2[0]+1;}else{$Id_H=1;}
								$cons4="select id_historia,formato,tipoformato from histoclinicafrms.".$fila[1]." where compania='$Compania[0]' and cedula='$DocErrado'";
								$res4=ExQuery($cons4);
								while($fila4=ExFetch($res4))
								{
									$cons3="update ".$fila[0].".".$fila[1]." set ".$fila[2]."='$DocCorrecto',numservicio=$NumServDC,id_historia=$Id_H 
									where ".$fila[2]."='$DocErrado' and id_historia=$fila4[0] $Comp";
									$res3=ExQuery($cons3);
									$cons3="update histoclinicafrms.ayudaxformatos set cedula='$DocCorrecto',numservicio=$NumServDC,id_historia=$Id_H 
									where cedula='$DocErrado' and id_historia=$fila4[0] and formato='$fila4[1]' and tipoformato='$fila4[2]' $Comp";
									$res3=ExQuery($cons3);
									$cons3="update histoclinicafrms.ayudaxformatos set cedula='$DocCorrecto',numservicio=$NumServDC,id_historia=$Id_H 
									where cedula='$DocErrado' and id_historia=$fila4[0] and formato='$fila4[1]' and tipoformato='$fila4[2]' $Comp";
									$res3=ExQuery($cons3);
									//echo "$cons3<br>";
									$Id_H++;
								}
							}
						}
					
						if($fila[0]=="facturacion"||$fila[0]=="historiaclinica"||$fila[0]=="salud"||$fila[0]=="odontologia"||$fila[0]=="contratacionsalud"&&$fila[1]!="logsuper"){
							$cons2="select Column_name from information_schema.columns where Column_name='numservicio' and table_name='$fila[1]' 
							and table_schema='$fila[0]'";
							$res2=ExQuery($cons2);
							if(ExNumRows($res2)>0){$NS="and numservicio=$NumServDC";}else{$NS="";}
							if($fila[1]=="ordenesmedicas")
							{
								$cons4="select idescritura,numorden from salud.ordenesmedicas where compania='$Compania[0]' and cedula='$DocCorrecto' 
								order by numorden,idescritura desc";
								$res4=ExQuery($cons4);
								$fila4=ExFetch($res4); if($fila4[0]){$NumO=$fila4[0]; $IdEsc=$fila4[1]+1;}else{$NumO=1; $IdEsc=1;}
								$cons4="select idescritura,numorden from salud.ordenesmedicas where compania='$Compania[0]' and cedula='$DocErrado'";
								$res4=ExQuery($cons4);
								while($fila4=ExFetch($res))
								{
									$cons3="update ".$fila[0].".".$fila[1]." set ".$fila[2]."='$DocCorrecto',numorden=$NumO,idescritura=$IdEsc
									where ".$fila[2]."='$DocErrado' $NS $Comp";
									$res3=ExQuery($cons3);
									//echo "$cons3<br>";			
									$IdEsc++;
								}
							}
							else{
								$cons3="update ".$fila[0].".".$fila[1]." set ".$fila[2]."='$DocCorrecto' where ".$fila[2]."='$DocErrado' $NS $Comp";
								$res3=ExQuery($cons3);
								//echo "$cons3<br>";					
							}
						}
					}?>
						<script language="javascript">
							alert("Se ha realizado el cambio exitosamente");
						</script>
			<?	}			
				else
				{?>
					<script language="javascript">
						alert("No se encontro ningun servicio para el documento correcto");
					</script>			
			<?	}
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
					function ValidaDocumento(Objeto)
					{
						frames.FrameOpener.location.href="ValidaDocUnificacion.php?DatNameSID=<? echo $DatNameSID?>&Cedula="+Objeto.value+"&NomCampo="+Objeto.id;
						document.getElementById('FrameOpener').style.position='absolute';
						document.getElementById('FrameOpener').style.top='90px';
						document.getElementById('FrameOpener').style.left='325px';
						document.getElementById('FrameOpener').style.display='';
						document.getElementById('FrameOpener').style.width='400';
						document.getElementById('FrameOpener').style.height='390';
					}
					function Validar()
					{
						if(document.FORMA.DocErrado.value==""){alert("Debe seleccionar el documento de identificacion errado!!!");return false;}
						if(document.FORMA.DocCorrecto.value==""){alert("Debe seleccionar el documento de identificacion correcto!!!");return false;}											   
					}
				</script>
			</head>

		<body <?php echo $backgroundBodyMentor; ?>>
			<?php
				$rutaarchivo[0] = "HISTORIA CL&Iacute;NICA";
				$rutaarchivo[1] = "CONFIGURACI&Oacute;N";
				$rutaarchivo[2] = "UNIFICACI&Oacute;N HISTORIA CL&Iacute;NICA";
				mostrarRutaNavegacionEstatica($rutaarchivo);
					
				?>	
			<div <?php echo $alignDiv2Mentor; ?> class="div2">	
				<form name="FORMA" method="post" onSubmit="return Validar()">
					<table class="tabla2"  <?php echo $borderTabla2Mentor; echo $bordercolorTabla2Mentor; echo $cellspacingTabla2Mentor; echo $cellpaddingTabla2Mentor; ?> >
						<tr> 
							<td colspan="4" class="encabezado2Horizontal"> CAMBIO DE N&Uacute;MERO DE IDENTIFICACI&Oacute;N</td>
						</tr>
						<tr>
							<td class="encabezado2VerticalInvertido">DOCUMENTO ERRADO</td>
							<td><input type="text" name="DocErrado" id="DocErrado" onKeyDown="xLetra(this)" onKeyPress="xLetra(this)" onFocus="ValidaDocumento(this)"  
								onKeyUp="ValidaDocumento(this);xLetra(this)"/>    
							</td>
							<td class="encabezado2VerticalInvertido">DOCUMENTO CORRECTO</td>
							<td><input type="text" name="DocCorrecto" id="DocCorrecto" onKeyDown="xLetra(this)" onKeyPress="xLetra(this)" onFocus="ValidaDocumento(this)" 
								onKeyUp="ValidaDocumento(this);xLetra(this)"/>    
							</td>
						</tr>
						<tr>
							<td colspan="4" style="text-align:center;">
								<input type="submit" class="boton2Envio" value="Unificar Historia Clinica" name="Cambiar">
							</td>
						</tr>
					</table>
				</form>
				<iframe id="FrameOpener" name="FrameOpener" style="display:none" frameborder="0" height="1" style="border:#e5e5e5 ridge">
			</div>	
		</body>
		</html>