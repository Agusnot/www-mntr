		<?
			if($DatNameSID){session_name("$DatNameSID");}
			session_start();
			include("Funciones.php");
			include_once("General/Configuracion/Configuracion.php");
			$ND=getdate();
			if($Eliminar)
			{
				$cons="Select * from Central.CentrosCosto where Compania='$Compania[0]' and Anio=$Anio and Codigo like '$Codigo%'";
				$res=ExQuery($cons);
				if(ExNumRows($res)>1){echo "<em><font size=-1>Centro tiene Hijos. No puedo eliminar</em></font>";}
				else{
				$cons="Delete from Central.CentrosCosto where Compania='$Compania[0]' and Anio=$Anio and Codigo='$Codigo'";
				$res=ExQuery($cons);echo ExError();
				?>
				<script language="javascript">
				parent.frames.Lista.location.href="ConfListxCC.php?DatNameSID=<? echo $DatNameSID?>&Anio=<? echo $Anio?>";
				</script>
				<? 
					$Nuevo=0;$Edit=0;$Codigo='';$Detalle="";$Tipo="";
					}
				
			}
			if($Guardar)
			{
				if($Edit)
				{
					$cons="Update Central.CentrosCosto set Codigo='$Codigo',CentroCostos='$Detalle',Tipo='$Tipo' 
					where Compania='$Compania[0]' and Anio=$Anio and Codigo='$Codigo'";
				}
				if($Nuevo)
				{

					$cons="Insert into Central.CentrosCosto (Codigo,CentroCostos,Tipo,Compania,Anio)
					values('$PreNewCuenta$NewCuenta','$Detalle','$Tipo','$Compania[0]',$Anio)";
					$Edit=1;$Codigo="$PreNewCuenta$NewCuenta";$Nuevo=0;
				}
				if(ExError())
				{
					echo "Se produjo un error->".ExError($res);
				}
				$res=ExQuery($cons);
				?>
				<script language="javascript">
				parent.frames.Lista.location.href="ConfListxCC.php?DatNameSID=<? echo $DatNameSID?>&Anio=<? echo $Anio?>";
				</script>
				<?
			}

			if($Edit)
			{
				$cons="Select Codigo,CentroCostos,Tipo from Central.CentrosCosto where Compania='$Compania[0]' and Anio=$Anio and Codigo='$Codigo'";
				$res=ExQuery($cons);
				$fila=ExFetch($res);
				$Codigo=$fila[0];$Detalle=$fila[1];$Tipo=$fila[2];
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
			<script language="javascript" src="/funciones.js"></script>
			<script language="javascript">
				function Validar()
				{
					if(document.FORMA.Edit.value==1)
					{
						if(document.FORMA.Codigo.value==""){alert("Ingrese el codigo");return false;}
					}
					if(document.FORMA.Nuevo.value==1)
					{
						if(document.FORMA.NewCuenta.value.length!=document.FORMA.NewCuenta.maxLength){alert("Digitos incorrectos");return false;}
						if(document.FORMA.NewCuenta.value==""){alert("Ingrese el codigo");return false;}
					}
					if(document.FORMA.Detalle.value==""){alert("Ingrese el centro de costos");return false;}
				}
				
			</script>
			
		</head>	
		<body <?php echo $backgroundBodyMentor; ?>>	
				
			<div style="margin-top:0px; position:absolute; top:0px; left:0px;">
				<form name="FORMA" method="post" onSubmit="return Validar();">
						<table class="tabla2" style="margin-top:5px; margin-left:25px;" border="0" <?php  echo $cellspacingTabla2Mentor; echo $cellpaddingTabla2Mentor; ?>>
							<tr>
								<td class='encabezado1VerticalInvertido'>C&Oacute;DIGO</td><td>
									<?		
									if ($Nuevo){echo $Codigo;
									$NumCar=strlen($Codigo);
									$cons3="Select sum(Digitos) from Central.EstructuraxCC where Compania='$Compania[0]' and Anio=$Anio";
									$res3=ExQuery($cons3,$conex);echo ExError($res3);
									$fila3=ExFetch($res3);
									$NoTotalCar=$fila3[0];

									$cons2="Select Digitos,Nivel from Central.EstructuraxCC where Compania='$Compania[0]' and Anio=$Anio order By Nivel";
									$res2=ExQuery($cons2,$conex);echo ExError($res2);

							while($fila2=ExFetch($res2))
							{
								$TotNiveles++;
								$NumCarPUC=$NumCarPUC+$fila2[0];
								if($Act){$MaxLen=$fila2[0];$Act=0;$Nivel=$fila2[1];}
								if($NumCar==$NumCarPUC){$Act=1;}
							}
							if($Nivel!=$TotNiveles){$Tipo="Titulo";}else{$Tipo="Detalle";}
							if($Nuevo && $Madre)
							{
								$cons="Select Digitos from Central.EstructuraxCC where Compania='$Compania[0]' and Anio=$Anio and Nivel=1";
								$res=ExQuery($cons);
								$fila=ExFetch($res);
								$MaxLen=$fila[0];
							}
							?>
							<input type="Hidden" name="PreNewCuenta" value="<?echo $Codigo?>">
							<input type="Text" name="NewCuenta" style="width:<? echo ($MaxLen*15)?>px" maxlength="<? echo $MaxLen?>" onKeyUp="xNumero(this)" onKeyDown="xNumero(this)" onBlur="campoNumero(this)"></td></tr>
							<?		}
									else{
							?>
						<input type="text" name="Codigo" style="width:100px;" readonly value="<? echo $Codigo?>" onKeyUp="xNumero(this)" onKeyDown="xNumero(this)" onBlur="campoNumero(this)"></td><? }?></tr>
						<input type="hidden" name="Edit" value="<? echo $Edit?>">
						<input type="hidden" name="Nuevo" value="<? echo $Nuevo?>">
						<tr>
							<td class='encabezado2VerticalInvertido'>DETALLE</td>
							<td><input type="text" name="Detalle" maxlength="80" style="width:250px;" value="<? echo $Detalle?>" onKeyUp="xLetra(this)" onKeyDown="xLetra(this)"></td></tr>
						<tr>
							<td class='encabezado2VerticalInvertido'>TIPO</td><td>
								<select name="Tipo">

								<option <? if($Tipo=="Titulo"){echo " selected "; }?> value="Titulo">Titulo</option>
								<option <? if($Tipo=="Detalle"){echo " selected "; }?> value="Detalle">Detalle</option>
								</select>
							</td>
						</tr>
						<? if($Nuevo || $Edit){?>
						<tr>
							<td colspan="2" align="center"><input type="submit" class="boton2Envio" name="Guardar" value="Guardar">
								<input type="submit" class="boton2Envio" name="Eliminar" value="Eliminar">
							</td>
						</tr><? }?>
						<input type="hidden" name="DatNameSID" value="<? echo $DatNameSID?>">
					</table>
				</form>
			</div>
		</body>
	</html>
