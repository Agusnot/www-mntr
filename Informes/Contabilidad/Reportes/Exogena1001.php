<?
	if($DatNameSID){session_name("$DatNameSID");}
	session_start();
	include("Informes.php");
	include_once("General/Configuracion/Configuracion.php");
	$ND=getdate();
	if(!$CuentaIni){$CuentaIni=2;}
	if(!$CuentaFin){$CuentaFin=29999999999;}
	?>
		<html>
				<head>
					<?php echo $codificacionMentor; ?>
					<?php echo $autorMentor; ?>
					<?php echo $titleMentor; ?>
					<?php echo $iconMentor; ?>
					<?php echo $shortcutIconMentor; ?>
					<link rel="stylesheet" type="text/css" href="../../../General/Estilos/estilos.css">
				</head>	
				<body <?php echo $backgroundBodyInfContableMentor;?>>
					<div class="divInformeContable" <?php echo $alignDivInformeContable;?>>
						<?php
							$informe = "INFORMACION EXOGENA 1001";
							$caracteristicas = "VIGENCIA ".$Anio;
							$fechaimpresion = "FECHA DE IMPRESION : "."$ND[year]-$ND[mon]-$ND[mday]";
							encabezadoInformeContable($Compania[0], $Compania[1], $informe, $caracteristicas,$fechaimpresion);
						?>

		
						<table width="100%" rules="groups" style="margin-top:25px" class="tablaInformeContable" <?php echo $borderTablaInfContable; echo $bordercolorTablaInfContable; echo $cellspacingTablaInfContable; echo $cellpaddingTablaInfContable; ?> >
							<tr>
								<td class='encabezado2HorizontalInfCont'>CONCEPTO</td>
								<td class='encabezado2HorizontalInfCont'>TIPO DOCUMENTO</td>
								<td class='encabezado2HorizontalInfCont'>NO IDENTIFICACI&Oacute;N</td>
								<td class='encabezado2HorizontalInfCont'>DIG. VER</td>
								<td class='encabezado2HorizontalInfCont'>PRIMER APELLIDO</td>
								<td class='encabezado2HorizontalInfCont'>SEGUNDO APELLIDO</td>
								<td class='encabezado2HorizontalInfCont'> PRIMER NOMBRE</td>
								<td class='encabezado2HorizontalInfCont'>SEGUNDO NOMBRE</td>
								<td class='encabezado2HorizontalInfCont'>RAZ&Oacute;N SOCIAL</td>
								<td class='encabezado2HorizontalInfCont'>DIRECCI&Oacute;N</td>
								<td class='encabezado2HorizontalInfCont'>DEPARTAMENTO</td>
								<td class='encabezado2HorizontalInfCont'>MUNICIPIO</td>
								<td class='encabezado2HorizontalInfCont'>PA&Iacute;S</td>
								<td class='encabezado2HorizontalInfCont'>ABONO EN CUENTA</td>
							</tr>
						<?
							$cons="Select Cuenta,sum(Debe),Movimiento.Identificacion,PrimApe,SegApe,PrimNom,SegNom,Direccion,Departamento,Municipio,Pais 
							from Contabilidad.Movimiento,Central.Terceros,Contabilidad.Comprobantes where Terceros.Compania='$Compania[0]' 
							and Movimiento.Compania='$Compania[0]' and Terceros.Compania='$Compania[0]' and Comprobantes.Compania='$Compania[0]'
							and Movimiento.Identificacion=Terceros.Identificacion and Debe>0
							and Comprobantes.Comprobante=Movimiento.Comprobante
							and TipoComprobant='Cuentas x Pagar'   
							and Fecha>='$Anio-$MesIni-$DiaIni' and Fecha<='$Anio-$MesFin-$DiaFin'
							and Estado='AC'
							Group By Cuenta,Movimiento.Identificacion ,PrimApe,SegApe,PrimNom,SegNom,Direccion,Departamento,Municipio,Pais
							Order By PrimApe,SegApe,PrimNom,SegNom";
							$res=ExQuery($cons);
							
							while($fila=ExFetch($res)){
								if(!$fila[4] && !$fila[5] && !$fila[6])	{
									$RazonSoc=$fila[3];
									$PrimApe="";$TipoDoc="NIT";		
								}
								else{
									$PrimApe=$fila[3];$RazonSoc="";$TipoDoc="CC";
								}
								$cons2="Select Codigo from Central.Departamentos where Departamento='$fila[8]'";
								$res2=ExQuery($cons2);
								$fila2=ExFetch($res2);
								$CodDepto=$fila2[0];
								
								$cons2="Select CodMpo from Central.Municipios where Departamento='$CodDepto' and Municipio='$fila[9]'";
								$res2=ExQuery($cons2);
								$fila2=ExFetch($res2);
								$CodMpo=$fila2[0];

								$NoIdent=explode("-",$fila[2]);
								echo "<tr><td>$fila[0]</td><td>$TipoDoc</td><td>$NoIdent[0]</td><td>$NoIdent[1]</td><td>$PrimApe</td><td>$fila[4]</td><td>$fila[5]</td><td>$fila[6]</td><td>$RazonSoc</td><td>$fila[7]</td><td>$CodDepto</td><td>$CodMpo</td><td>0057</td><td align='right'>".number_format($fila[1],2)."</td></tr>";	
							}
							?>
					</div>
				</body>
		</html>	

