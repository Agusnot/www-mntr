		<?	
			if($DatNameSID){session_name("$DatNameSID");}
			session_start();
			include("Funciones.php");
			include_once("General/Configuracion/Configuracion.php");
			$ND=getdate();
			if($Cambiar)
			{
				$cons="select identificacion from central.terceros where compania='$Compania[0]' and identificacion='$DocNew'";
				$res=ExQuery($cons);
				if(ExNumRows($res)>0)
				{?>
					<script language="javascript">
						alert("El nuevo documento ya se encuentra registrado en la base de datos");
					</script>
		<?		}
				else
				{	
					$cons3="insert into salud.regcambioidpacientes (compania,fechacambio,usuario,docant,docnew,cambia) 
					values ('$Compania[0]','$ND[year]-$ND[mon]-$ND[mday] $ND[hours]:$ND[minutes]:$ND[seconds]','$usuario[1]','$DocBusq','$DocNew',1)";
					$res3=ExQuery($cons3);
					$cons3="update odontologia.odontogramaproc set Identificacion='$DocNew' where compania='$Compania[0]' and Identificacion='$DocBusq'";
					$res3=ExQuery($cons3);
					$cons3="update central.terceros set Identificacion='$DocNew' where compania='$Compania[0]' and Identificacion='$DocBusq'";
					$res3=ExQuery($cons3);
					$cons="Select table_schema, table_name,Column_name from information_schema.columns Where Column_name='cedula' or
					Column_name='cedpaciente' or Column_name='paciente' order by table_schema,table_name";
					//echo $cons;
					$res=ExQuery($cons);
					while($fila=ExFetch($res))
					{
						//echo "$fila[0] - - $fila[1]<br>";
						$cons2="select Column_name from information_schema.columns where Column_name='compania' and table_name='$fila[1]' and table_schema='$fila[0]'";
						$res2=ExQuery($cons2);
						if(ExNumRows($res2)>0){$Comp="and compania='$Compania[0]'";}else{$Comp="";}
						if($fila[0]=="contratacionsalud"||$fila[0]=="facturacion"||$fila[0]=="histoclinicafrms"||$fila[0]=="historiaclinica"||$fila[0]=="salud"||$fila[0]=="odontologia"&&$fila[1]!="logsuper"||($fila[0]=="consumo"&&$fila[1]=="movimiento")){//echo "$fila[0] - - $fila[1]<br>";
							//
						//if($fila[0]!="infraestructura"&&$fila[0]!="consumo"&&$fila[1]!="logsuper"){
							if($fila[0]!="central"&&$fila[1]!="usuarios"){ //
								$cons3="update ".$fila[0].".".$fila[1]." set ".$fila[2]."='$DocNew' where ".$fila[2]."='$DocBusq' $Comp";
								$res3=ExQuery($cons3);
								
							}
						}
						
					}?>
					<script language="javascript">
						alert("Se ha realizado el cambio exitosamente");
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
						frames.FrameOpener.location.href="ValidaDocActualizacion.php?DatNameSID=<? echo $DatNameSID?>&Cedula="+Objeto.value;
						document.getElementById('FrameOpener').style.position='absolute';
						document.getElementById('FrameOpener').style.top='200px';
						document.getElementById('FrameOpener').style.left='325px';
						document.getElementById('FrameOpener').style.display='';
						document.getElementById('FrameOpener').style.width='450px';
						document.getElementById('FrameOpener').style.height='400px';
					}
					function Validar()
					{
						if(document.FORMA.DocBusq.value==""){alert("Debe seleccionar un documento de identificacion!!!");return false;}
						if(document.FORMA.DocNew.value==""){alert("Debe digitar un documento de identificacion nuevo!!!");return false;}											   
					}
				</script>
			</head>

			<body  <?php echo $backgroundBodyMentor; ?>>
				<?php
					$rutaarchivo[0] = "HISTORIA CL&Iacute;NICA";
					$rutaarchivo[1] = "CONFIGURACI&Oacute;N";
					$rutaarchivo[2] = "CAMBIO DE DOCUMENTO ID";
					mostrarRutaNavegacionEstatica($rutaarchivo);
					
				?>
				<div <?php echo $alignDiv2Mentor; ?> class="div2">	
					<form name="FORMA" method="post" onSubmit="return Validar()">
						<table class="tabla2"  <?php echo $borderTabla2Mentor; echo $bordercolorTabla2Mentor; echo $cellspacingTabla2Mentor; echo $cellpaddingTabla2Mentor; ?> >
						<tr> 
							<td class="encabezado2Horizontal" colspan="4">CAMBIO DE N&Uacute;MERO DE IDENTIFICACI&Oacute;N</td>
						</tr>
						<tr>
							<td class="encabezado2VerticalInvertido">DOCUMENTO A BUSCAR</td>
							<td><input type="text" name="DocBusq" onkeydown="xLetra(this)" onkeypress="xLetra(this)" onFocus="ValidaDocumento(this)" onKeyUp="ValidaDocumento(this);xLetra(this)" onKeyDown="xLetra(this)"/>    
							<td class="encabezado2VerticalInvertido" >REEMPLAZAR POR</td>
							<td><input type="text" name="DocNew" onKeyDown="xLetra(this)" onKeyPress="xLetra(this)" onKeyUp="xLetra(this)"/>    
						</tr>
						<tr>
							<td colspan="4" style="text-align:center">
								<input type="submit" class="boton2Envio" value="Realizar Cambio" name="Cambiar">
							</td>
						</tr>
						</table>
					</form>
					<iframe id="FrameOpener" name="FrameOpener" style="display:none" frameborder="0" height="1" style="border:#e5e5e5 ridge">
				</div>	
			</body>
	</html>